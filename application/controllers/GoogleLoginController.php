<?php

include_once CONF_INSTALLATION_PATH . 'library/GoogleAPI/vendor/autoload.php';

class GoogleLoginController extends SocialMediaAuthController
{
    public const KEY_NAME = 'GoogleLogin';

    private $client;
    public $requiredKeys = [
        'client_id',
        'client_secret',
        'developer_key'
    ];

    public function __construct($action)
    {
        parent::__construct($action);
        if (false === $this->validateSettings()) {
            $this->setErrorAndRedirect($this->error, true);
            return false;
        }
    }

    private function setupConfiguration()
    {
        $redirectUri = CommonHelper::generateFullUrl(static::KEY_NAME, 'index', [], '', false);
        
        $this->client = new Google_Client();
        $this->client->setApplicationName(FatApp::getConfig('CONF_WEBSITE_NAME_' . $this->siteLangId)); // Set your applicatio name
        $this->client->setScopes(['email']);
        $this->client->setClientId($this->settings['client_id']);
        $this->client->setClientSecret($this->settings['client_secret']);
        $this->client->setRedirectUri($redirectUri);
        $this->client->setDeveloperKey($this->settings['developer_key']);
    }


    public function index()
    {
        $get = FatApp::getQueryStringData();
        $userType = FatApp::getPostedData('type', FatUtility::VAR_INT, User::USER_TYPE_BUYER);
        $accessToken = FatApp::getPostedData('accessToken', FatUtility::VAR_STRING, '');
        
        if (true === MOBILE_APP_API_CALL && empty($accessToken)) {
            $message = Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId);
            $this->setErrorAndRedirect($message, true);
        }

        $this->setupConfiguration();
        
        if (!empty($accessToken) || isset($get['code'])) {
            if (empty($accessToken)) {
                $this->client->authenticate($get['code']);
                $accessToken = $this->client->getAccessToken();
            }

            $this->client->setAccessToken($accessToken);

            $this->oauth2 = new Google_Service_Oauth2($this->client);
            $user = $this->oauth2->userinfo->get();

            $userGoogleEmail = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
            $userGoogleId = $user['id'];
            $userGoogleName = $user['name'];

            if ($user['name'] == '') {
                $exp = explode("@", $user['email']);
                $userGoogleName = substr($exp[0], 0, 80);
            }
            
            $userInfo = $this->doLogin($userGoogleEmail, $userGoogleName, $userGoogleId, $userType);
            $this->redirectToDashboard($userInfo['user_preferred_dashboard']);
        }
        $authUrl = $this->client->createAuthUrl();
        FatApp::redirectUser($authUrl);
    }
}
