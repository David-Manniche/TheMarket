<?php

include_once CONF_INSTALLATION_PATH . 'library/facebook-auth/autoload.php';

// Include required libraries
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

class FacebookLoginController extends SocialMediaAuthController
{
    public const KEY_NAME = 'FacebookLogin';

    private $userName;
    private $facebookId;
    private $email;
    private $fbAuthObj;
    private $helper;

    public $requiredKeys = [
        'app_id',
        'app_secret'
    ];

    public function __construct($action)
    {
        parent::__construct($action);
    }

    private function initialize()
    {
        $this->fbAuthObj = new Facebook(
            [
            'app_id' => $this->settings['app_id'],
            'app_secret' => $this->settings['app_secret'],
            'default_graph_version' => 'v3.2',
            ]
        );
        $this->helper = $this->fbAuthObj->getRedirectLoginHelper();
    }

    private function getRequestUri()
    {
        $permissions = ['email', 'public_profile'];
        return $this->helper->getLoginUrl(CommonHelper::generateFullUrl(static::KEY_NAME, 'index', [], '', false), $permissions);
    }

    private function verifyAccessToken($accessToken)
    {
        $this->fbAuthObj->setDefaultAccessToken($accessToken);

        try {
            $graphResponse = $this->fbAuthObj->get('/me?fields=id, name, email, first_name, last_name');
            $fbUser = $graphResponse->getGraphUser();
        } catch (FacebookResponseException $e) {
            $message = Labels::getLabel('MSG_GRAPH_RETURNED_AN_ERROR:_', $this->siteLangId);
            $message .= $e->getMessage();
            $this->setErrorAndRedirect($message);
        } catch (FacebookSDKException $e) {
            $message = Labels::getLabel('MSG_FACEBOOK_SDK_RETURNED_AN_ERROR:_', $this->siteLangId);
            $message .= $e->getMessage();
            $this->setErrorAndRedirect($message);
        }
        $this->userName = $fbUser['name'];
        $this->facebookId = $fbUser['id'];
        $this->email = !empty($fbUser['email']) ? $fbUser['email'] : '';
    }

    public function index()
    {
        if (false == $this->validateSettings($this->siteLangId)) {
            $this->setErrorAndRedirect($this->error, true);
            return false;
        }
        $this->initialize();
        
        $get = FatApp::getQueryStringData();
        $userType = FatApp::getPostedData('type', FatUtility::VAR_INT, User::USER_TYPE_BUYER);
        $accessToken = FatApp::getPostedData('accessToken', FatUtility::VAR_STRING, '');
        if (empty($accessToken)) {
            try {
                $accessToken = $this->helper->getAccessToken();
            } catch (FacebookResponseException $e) {
                $message = Labels::getLabel('MSG_GRAPH_RETURNED_AN_ERROR:_', $this->siteLangId);
                $message .= $e->getMessage();
                $this->setErrorAndRedirect($message, true);
            } catch (FacebookSDKException $e) {
                $message = Labels::getLabel('MSG_FACEBOOK_SDK_RETURNED_AN_ERROR:_', $this->siteLangId);
                $message .= $e->getMessage();
                $this->setErrorAndRedirect($message, true);
            }
        }

        if (!empty($accessToken)) {
            if (isset($get['state'])) {
                $this->helper->getPersistentDataHandler()->set('state', $get['state']);
            }
            $this->verifyAccessToken($accessToken);

            $userInfo = $this->doLogin($this->email, $this->userName, $this->facebookId, $userType);
            $this->redirectToDashboard($userInfo['user_preferred_dashboard']);
        }
        FatApp::redirectUser($this->getRequestUri());
    }
}
