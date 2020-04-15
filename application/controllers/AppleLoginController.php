<?php

class AppleLoginController extends SocialMediaAuthController
{
    private const PRODUCTION_URL = 'https://appleid.apple.com/auth/';
    public const KEY_NAME = 'AppleLogin';

    public $requiredKeys = ['client_id'];

    public function __construct($action)
    {
        parent::__construct($action);
    }
    
    private function getRequestUri()
    {
        if (false == $this->validateSettings($this->siteLangId)) {
            $this->setErrorAndRedirect($this->error, true);
            return false;
        }
        
        $redirectUri = CommonHelper::generateFullUrl(static::KEY_NAME, 'index', array(), '', false);
        $_SESSION['appleSignIn']['state'] = bin2hex(random_bytes(5));
        return static::PRODUCTION_URL . 'authorize?' . http_build_query([
            'response_type' => 'code id_token',
            'response_mode' => 'form_post',
            'client_id' => $this->settings['client_id'],
            'redirect_uri' => $redirectUri,
            'state' => $_SESSION['appleSignIn']['state'],
            'scope' => 'name email',
        ]);
    }

    public function index()
    {
        $post = FatApp::getPostedData();
        $userType = FatApp::getPostedData('type', FatUtility::VAR_INT, User::USER_TYPE_BUYER);

        if (isset($post['id_token'])) {
            if (false === MOBILE_APP_API_CALL && $_SESSION['appleSignIn']['state'] != $post['state']) {
                $message = Labels::getLabel('MSG_AUTHORIZATION_SERVER_RETURNED_AN_INVALID_STATE_PARAMETER', $this->siteLangId);
                $this->setErrorAndRedirect($message, true);
            }
            if (isset($post['error'])) {
                $message = Labels::getLabel('MSG_AUTHORIZATION_SERVER_RETURNED_AN_ERROR: ', $this->siteLangId);
                $message .= htmlspecialchars($post['error']);
                $this->setErrorAndRedirect($message, true);
            }
            $claims = explode('.', $post['id_token'])[1];
            $claims = json_decode(base64_decode($claims), true);
            
            $appleUserInfo = isset($post['user']) ? json_decode($post['user'], true) : false;
    
            $appleId = isset($claims['sub']) ? $claims['sub'] : '';
    
            if (false === $appleUserInfo) {
                if (!isset($claims['email'])) {
                    $message = Labels::getLabel('MSG_UNABLE_TO_FETCH_USER_INFO', $this->siteLangId);
                    $this->setErrorAndRedirect($message, true);
                }
                $email = $claims['email'];
            } else {
                $email = $appleUserInfo['email'];
            }
            
            $exp = explode("@", $email);
            $username = substr($exp[0], 0, 80) . rand();

            $userInfo = $this->doLogin($email, $username, $appleId, $userType);
            $this->redirectToDashboard($userInfo['user_preferred_dashboard']);
        }
        FatApp::redirectUser($this->getRequestUri());
    }
}
