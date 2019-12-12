<?php
class FacebookLoginController extends SocialMediaController
{
    public const KEY_NAME = 'FacebookLogin';

    private $userName;
    private $facebookId;
    private $email;
    private $settings;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->settings = $this->getSettings();
    }

    private function verifyMobileUser($accessToken)
    {
        if (empty($accessToken)) {
            $message = Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId);
            $this->setErrorAndRedirect($message);
        }

        include_once CONF_INSTALLATION_PATH . 'library/facebook/facebook.php';
        $facebook = new Facebook(
            [
            'appId' => $this->settings[static::KEY_NAME . '_app_id'],
            'secret' => $this->settings[static::KEY_NAME . '_app_secret'],
            ]
        );
        $facebook->setAccessToken($accessToken);
        $user = $facebook->getUser();
        if (!$user) {
            $message = Labels::getLabel('MSG_Invalid_Token', $this->siteLangId);
            $this->setErrorAndRedirect($message);
        }

        try {
            // Proceed knowing you have a logged in user who's authenticated.
            $userProfile = $facebook->api('/me?fields=id,name,email');
        } catch (FacebookApiException $e) {
            $message = $e->getMessage();
            $this->setErrorAndRedirect($message);
        }

        if (empty($userProfile)) {
            $message = Labels::getLabel('MSG_ERROR_INVALID_REQUEST', $this->siteLangId);
            $this->setErrorAndRedirect($message);
        }

        // User info ok? Let's print it (Here we will be adding the login and registering routines)
        $this->userName = $userProfile['name'];
        $this->facebookId = $userProfile['id'];
        $this->email = !empty($userProfile['email']) ? $userProfile['email'] : '';
    }

    public function index()
    {
        $userType = FatApp::getPostedData('type', FatUtility::VAR_INT, User::USER_TYPE_BUYER);
        if (true ===  MOBILE_APP_API_CALL) {
            $accessToken = FatApp::getPostedData('accessToken', FatUtility::VAR_STRING, '');
            $this->verifyMobileUser($accessToken);
        } else {
            $this->email = FatApp::getPostedData('email', FatUtility::VAR_STRING, '');
            $this->facebookId = FatApp::getPostedData('id', FatUtility::VAR_STRING, '');

            $firstName = FatApp::getPostedData('first_name', FatUtility::VAR_STRING, '');
            $this->userName = trim($firstName . ' ' . FatApp::getPostedData('last_name', FatUtility::VAR_STRING, ''));
        }
        if ((empty($this->email) && empty($this->facebookId)) || empty($this->userName)) {
            $message = Labels::getLabel("MSG_INVALID_REQUEST", $this->siteLangId);
            $this->setErrorAndRedirect($message);
        }

        $userInfo = $this->doLogin($email, $this->userName, $facebookId, $userType);
        
        unset($_SESSION['fb_' . $this->settings[static::KEY_NAME . '_app_id'] . '_code']);
        unset($_SESSION['fb_' . $this->settings[static::KEY_NAME . '_app_id'] . '_access_token']);
        unset($_SESSION['fb_' . $this->settings[static::KEY_NAME . '_app_id'] . '_user_id']);

        $this->redirectToDashboard($userInfo['user_preferred_dashboard']);
    }
}
