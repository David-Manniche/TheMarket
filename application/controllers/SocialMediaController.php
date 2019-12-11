<?php
class SocialMediaController extends PluginBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    protected function setErrorMessage($message, $errRedirection = true)
    {
        if (false === $errRedirection || true ===  MOBILE_APP_API_CALL) {
            LibHelper::dieJsonError($message);
        }

        Message::addErrorMessage($message);
        FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm'));
    }
      
    private function goToDashboard($preferredDashboard = 0, $referredRedirection = true)
    {
        $referredUrl = User::getPreferedDashbordRedirectUrl($preferredDashboard);
        $cartObj = new Cart();
        if ($cartObj->hasProducts()) {
            $referredUrl = CommonHelper::generateFullUrl('cart');
            if (true === $referredRedirection) {
                FatApp::redirectUser($referredUrl);
            }
        }
        if (isset($_SESSION['referer_page_url'])) {
            $referredUrl = $_SESSION['referer_page_url'];
            unset($_SESSION['referer_page_url']);
            if (true === $referredRedirection) {
                FatApp::redirectUser($referredUrl);
            }
        }
        if (false === $referredRedirection) {
            $message = Labels::getLabel('MSG_LOGGEDIN_SUCCESSFULLY', $this->siteLangId);
            $this->set('url', $referredUrl);
            $this->set('msg', $message);
            $this->_template->render(false, false, 'json-success.php');
        }
        FatApp::redirectUser($referredUrl);
    }
    
    protected function redirectAndAuthenticateUser($url, $errRedirection = true)
    {
        if (empty($url)) {
            $message = Labels::getLabel("MSG_INVALID_AUTH_URI", $this->siteLangId);
            $this->setErrorMessage($message, $errRedirection);
        }
        FatApp::redirectUser($url);
    }

    protected function doLogin($email, $socialAccountID, $userType)
    {
        try {
            $keyName = get_called_class()::KEY_NAME;
        } catch (\Error $e) {
            FatUtility::dieJsonError('ERR - ' . $e->getMessage());
        }

        $userObj = new User();
        $userInfo = $userObj->validateUser($email, $socialAccountID, $keyName, $userType);
        if (false === $row) {
            $this->setErrorMessage($userObj->getError());
        }

        if (true ===  MOBILE_APP_API_CALL) {
            $userId = $userInfo['user_id'];
            $userObj = new User($userId);
            if (!$token = $userObj->setMobileAppToken()) {
                FatUtility::dieJsonError(Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId));
            }
            $this->set('token', $token);
            $this->set('userInfo', $userInfo);
            $this->_template->render(true, true, 'guest-user/login.php');
        }
    }
}