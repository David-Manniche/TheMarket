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

    protected function getUserInfo($recordIdentifier, $userType, $loginType)
    {
        $userObj = new User();
        $row = $userObj->validateUser($recordIdentifier, $userType, $loginType);
        if (false === $row) {
            $this->setErrorMessage($userObj->getError());
        }
        return $row;
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
    
    protected function setupUser($user_type, $userName, $loginType, $socialLoginId, $userEmail)
    {
        $userObj = new User();
        $userId = $userObj->setupUser($user_type, $userName, $loginType, $socialLoginId, $userEmail);
        if (false === $userId) {
            $this->setErrorMessage($userObj->getError());
        }
        return $userId;
    }

    protected function doLogin($userInfo, $referredRedirection = true)
    {
        $userId = FatUtility::int($userInfo['user_id']);
        
        if (1 > $userId) {
            $message = Labels::getLabel("LBL_INVALID_REQUEST", $this->siteLangId);
            $this->setErrorMessage($message, $referredRedirection);
        }

        $userObj = new User($userId);
        
        if (!$userObj->doLogin()) {
            $message = Labels::getLabel($userObj->getError(), $this->siteLangId);
            $this->setErrorMessage($message, $referredRedirection);
        }

        if (true ===  MOBILE_APP_API_CALL) {
            if (!$token = $userObj->setMobileAppToken()) {
                FatUtility::dieJsonError(Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId));
            }
            $this->set('token', $token);
            $this->set('userInfo', $userInfo);
            $this->_template->render(true, true, 'guest-user/login.php');
        }
        $this->goToDashboard($userInfo['user_preferred_dashboard'], $referredRedirection);
    }

    protected function redirectAndAuthenticateUser($url, $errRedirection = true)
    {
        if (empty($url)) {
            $message = Labels::getLabel("MSG_INVALID_AUTH_URI", $this->siteLangId);
            $this->setErrorMessage($message, $errRedirection);
        }
        FatApp::redirectUser($url);
    }
}
