<?php

class FacebookLoginController extends SocialMediaAuthController
{
    public const KEY_NAME = 'FacebookLogin';

    public function __construct($action)
    {
        parent::__construct($action);

        $error = '';
        $this->fb = PluginHelper::callPlugin(self::KEY_NAME, [$this->siteLangId], $error, $this->siteLangId);
        if (false === $this->fb) {
            $this->setErrorAndRedirect($error);
        }

        if (false === $this->fb->init()) {
            $this->setErrorAndRedirect($this->fb->getError());
        }
    }
    
    public function index()
    {
        $get = FatApp::getQueryStringData();
        $userType = FatApp::getPostedData('type', FatUtility::VAR_INT, User::USER_TYPE_BUYER);
        $accessToken = FatApp::getPostedData('accessToken', FatUtility::VAR_STRING, '');

        if (!empty($accessToken)) {
            if (isset($get['state'])) {
                $this->helper->getPersistentDataHandler()->set('state', $get['state']);
            }
            if (false === $this->fb->verifyAccessToken($accessToken)) {
                $this->setErrorAndRedirect($this->fb->getError());
            }
            $resp = $this->fb->getResponse();
            $email = !empty($resp['email']) ? $resp['email'] : '';

            $userInfo = $this->doLogin($email, $resp['userName'], $resp['facebookId'], $userType);
            $this->redirectToDashboard($userInfo['user_preferred_dashboard']);
        }
        FatApp::redirectUser($this->fb->getRequestUri());
    }
}
