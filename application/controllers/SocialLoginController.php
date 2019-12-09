<?php
class SocialLoginController extends PluginBaseController
{
    private const FB_LOGIN = 1;
    private const GOOGLE_LOGIN = 2;
    private const APPLE_LOGIN = 3;

    protected const USER_INFO_ATTR = [
        'user_id',
        'user_name',
        'user_phone',
        'credential_email',
        'user_registered_initially_for',
        'user_preferred_dashboard',
        'user_deleted',
        'user_is_buyer',
        'user_is_supplier',
        'user_is_advertiser',
        'user_is_affiliate',
        'user_googleplus_id',
        'user_facebook_id',
        'user_apple_id',
        'credential_active',
        'credential_username',
        'credential_password',
    ];

    public function __construct($action)
    {
        parent::__construct($action);
    }

    protected function setLoginErrorMessage($message, $errRedirection = true)
    {
        if (true ===  MOBILE_APP_API_CALL) {
            LibHelper::dieJsonError($message);
        }
        if (true === $errRedirection) {
            Message::addErrorMessage($message);
            CommonHelper::redirectUserReferer();
        } else {
            FatUtility::dieJsonError($message);
        }
    }

    protected function getUserInfo($recordIdentifier, $userType, $loginType)
    {
        $db = FatApp::getDb();
        $userObj = new User();
        $srch = $userObj->getUserSearchObj(static::USER_INFO_ATTR);
        
        $cnd = $srch->addCondition('credential_email', '=', $recordIdentifier);

        switch ($loginType) {
            case static::FB_LOGIN:
                $cnd->attachCondition('user_facebook_id', '=', $recordIdentifier);
                break;
            case static::GOOGLE_LOGIN:
                $cnd->attachCondition('user_googleplus_id', '=', $recordIdentifier);
                break;
            case static::APPLE_LOGIN:
                $cnd->attachCondition('user_apple_id', '=', $recordIdentifier);
                break;
            default:
                $message = Labels::getLabel('MSG_INVALID_SOCIAL_LOGIN_TYPE', $this->siteLangId);
                $this->setLoginErrorMessage($message);
                break;
        }
        $rs = $srch->getResultSet();
        if (!$rs) {
            $message = Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId);
            $this->setLoginErrorMessage($message);
        }
        $row = $db->fetch($rs);
        if (empty($row)) {
            return [];
        }

        if ($row['credential_active'] != applicationConstants::ACTIVE) {
            $message = Labels::getLabel('ERR_YOUR_ACCOUNT_HAS_BEEN_DEACTIVATED', $this->siteLangId);
            $this->setLoginErrorMessage($message);
        }

        if ($row['user_deleted'] == applicationConstants::YES) {
            $message = Labels::getLabel("ERR_USER_INACTIVE_OR_DELETED", $this->siteLangId);
            $this->setLoginErrorMessage($message);
        }
        if (0 < $userType) {
            $userTypeArr = [
                User::USER_TYPE_BUYER => 'user_is_buyer',
                User::USER_TYPE_SELLER => 'user_is_supplier',
                User::USER_TYPE_ADVERTISER => 'user_is_advertiser',
                User::USER_TYPE_AFFILIATE => 'user_is_affiliate',
            ];

            $invalidUser = false;
            if (in_array($userType, array_keys($userTypeArr)) && $row[$userTypeArr[$userType]] == applicationConstants::NO) {
                $invalidUser = true;
            } elseif (!in_array($userType, array_keys($userTypeArr)) && $row['user_registered_initially_for'] != $userType) {
                $invalidUser = true;
            }

            if ($invalidUser) {
                $message = Labels::getLabel('MSG_Invalid_User', $this->siteLangId);
                $this->setLoginErrorMessage($message);
            }
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
        $db = FatApp::getDb();
        $user_is_supplier = (FatApp::getConfig("CONF_ACTIVATE_SEPARATE_SIGNUP_FORM", FatUtility::VAR_INT, 1)) ? 0: 1;
        $user_is_advertiser = (FatApp::getConfig("CONF_ADMIN_APPROVAL_SUPPLIER_REGISTRATION", FatUtility::VAR_INT, 1) || FatApp::getConfig("CONF_ACTIVATE_SEPARATE_SIGNUP_FORM", FatUtility::VAR_INT, 1)) ? 0: 1;

        if (isset($user_type) && $user_type == User::USER_TYPE_BUYER) {
            $userPreferredDashboard = User::USER_BUYER_DASHBOARD;
            $user_registered_initially_for = User::USER_TYPE_BUYER;
        }
        if (isset($user_type) && $user_type == User::USER_TYPE_SELLER) {
            $userPreferredDashboard = User::USER_SELLER_DASHBOARD;
            $user_registered_initially_for = User::USER_TYPE_SELLER;
        }

        $db->startTransaction();

        switch ($loginType) {
            case static::FB_LOGIN:
                $userFacebookId = $socialLoginId;
                break;
            case static::GOOGLE_LOGIN:
                $userGoogleId = $socialLoginId;
                break;
            case static::APPLE_LOGIN:
                $userAppleId = $socialLoginId;
                break;
        }

        $userData = array(
            'user_name' => $userName,
            'user_is_buyer' => (isset($user_type) && $user_type == User::USER_TYPE_BUYER) ? 1 : 0,
            'user_is_supplier' => (isset($user_type) && $user_type == User::USER_TYPE_SELLER) ? 1 : 0,
            'user_is_advertiser' => $user_is_advertiser,
            'user_googleplus_id' => !empty($userGoogleId) ? $userGoogleId : '',
            'user_facebook_id' => !empty($userFacebookId) ? $userFacebookId : '',
            'user_apple_id' => !empty($userAppleId) ? $userAppleId : '',
            'user_preferred_dashboard' => $userPreferredDashboard,
            'user_registered_initially_for' => $user_registered_initially_for
        );
        $userObj->assignValues($userData);
        if (!$userObj->save()) {
            $message = Labels::getLabel("MSG_USER_COULD_NOT_BE_SET", $this->siteLangId) . $userObj->getError();
            Message::addErrorMessage($message);
            $db->rollbackTransaction();
            if (true ===  MOBILE_APP_API_CALL) {
                LibHelper::dieJsonError($message);
            }
            CommonHelper::redirectUserReferer();
        }
        $userId = $userObj->getMainTableRecordId();

        $socialUid = !empty($userGoogleId) ? $userGoogleId : $userFacebookId;
        $username = str_replace(" ", "", $userName) . $socialUid;

        if (!$userObj->setLoginCredentials($username, $userEmail, uniqid(), 1, 1)) {
            $message = Labels::getLabel("MSG_LOGIN_CREDENTIALS_COULD_NOT_BE_SET", $this->siteLangId) . $userObj->getError();
            Message::addErrorMessage($message);
            $db->rollbackTransaction();
            if (true ===  MOBILE_APP_API_CALL) {
                LibHelper::dieJsonError($message);
            }
            CommonHelper::redirectUserReferer();
        }

        $userData['user_username'] = $username;
        $userData['user_email'] = $userEmail;
        if (FatApp::getConfig('CONF_NOTIFY_ADMIN_REGISTRATION', FatUtility::VAR_INT, 1)) {
            if (!$this->notifyAdminRegistration($userObj, $userData)) {
                $message = Labels::getLabel("MSG_NOTIFICATION_EMAIL_COULD_NOT_BE_SENT", $this->siteLangId);
                Message::addErrorMessage($message);
                $db->rollbackTransaction();
                if (true ===  MOBILE_APP_API_CALL) {
                    LibHelper::dieJsonError($message);
                }
                if (FatUtility::isAjaxCall()) {
                    FatUtility::dieWithError(Message::getHtml());
                }
            }
        }

        if (FatApp::getConfig('CONF_WELCOME_EMAIL_REGISTRATION', FatUtility::VAR_INT, 1) && $userEmail) {
            $data['user_email'] = $userEmail;
            $data['user_name'] = $username;

            //ToDO::Change login link to contact us link
            $data['link'] = CommonHelper::generateFullUrl('GuestUser', 'loginForm');
            $userEmailObj = new User($userId);
            if (!$this->userWelcomeEmailRegistration($userEmailObj, $data)) {
                $message = Labels::getLabel("MSG_WELCOME_EMAIL_COULD_NOT_BE_SENT", $this->siteLangId);
                Message::addErrorMessage($message);
                $db->rollbackTransaction();
                if (true ===  MOBILE_APP_API_CALL) {
                    LibHelper::dieJsonError($message);
                }
                CommonHelper::redirectUserReferer();
            }
        }

        $db->commitTransaction();
        $userObj->setUpRewardEntry($userId, $this->siteLangId);
        return $userId;
    }

    protected function doLogin($userInfo, $referredRedirection = true)
    {
        $authentication = new UserAuthentication();
        $userName = $userInfo['credential_username'];
        $password = $userInfo['credential_password'];
        $remoteAddress = $_SERVER['REMOTE_ADDR'];
        if (!$authentication->login($userName, $password, $remoteAddress, false)) {
            $message = Labels::getLabel($authentication->getError(), $this->siteLangId);
            $this->setLoginErrorMessage($message, $referredRedirection);
        }
        if (true ===  MOBILE_APP_API_CALL) {
            $userId = $userInfo['user_id'];
            $userObj = new User($userId);
            if (!$token = $userObj->setMobileAppToken()) {
                FatUtility::dieJsonError(Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId));
            }
            unset($userInfo['credential_password']);
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
            $this->setLoginErrorMessage($message, $errRedirection);
        }
        FatApp::redirectUser($url);
    }
}
