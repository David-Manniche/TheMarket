<?php
class AppleController extends SocialLoginController
{
    private $email;
    private $isPrivateEmailId;
    private $appleId;
    private $userType;

    public function __construct($action)
    {
        parent::__construct($action);

        $this->userType = FatApp::getPostedData('type', FatUtility::VAR_INT, 0);
        if (true ===  MOBILE_APP_API_CALL && 1 > $this->userType) {
            $message = Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId);
            $this->setLoginErrorMessage($message);
        }
    }

    private function validateResponse($response)
    {
        if (false ===  MOBILE_APP_API_CALL && $_SESSION['appleSignIn']['state'] != $appleResponse['state']) {
            $message = 'Authorization server returned an invalid state parameter';
            $this->setLoginErrorMessage($message);
        }
        if (isset($_REQUEST['error'])) {
            $message = 'Authorization server returned an error: ' . htmlspecialchars($_REQUEST['error']);
            $this->setLoginErrorMessage($message);
        }
        $claims = explode('.', $appleResponse['id_token'])[1];
        $claims = json_decode(base64_decode($claims), true);
        $appleUserInfo = isset($appleResponse['user']) ? json_decode($appleResponse['user'], true) : false;
        
        $this->isPrivateEmailId = false;
        if (isset($claims['is_private_email']) && $claims['is_private_email'] == true ) {
            $this->isPrivateEmailId = true;
        }

        $this->appleId = isset($claims['sub']) ? $claims['sub'] : '';

        if (false === $appleUserInfo) {
            if (!isset($claims['email'])) {
                $message = Labels::getLabel('MSG_UNABLE_TO_FETCH_USER_INFO', $this->siteLangId);
                Message::addErrorMessage($message);
                FatApp::redirectUser(CommonHelper::generateUrl());
            }
            $this->email = $claims['email'];
        } else {
            $this->email = $appleUserInfo['email'];
        }
    }

    public function index()
    {
        $appleResponse = FatApp::getPostedData();
        if (isset($appleResponse['id_token'])) {
            $this->validateResponse();
                    
            if (true === $this->isPrivateEmailId && !empty($this->appleId)) {
                $userInfo = $this->getUserInfo($this->appleId, $this->userType, static::APPLE_LOGIN);
            } else {
                $userInfo = $this->getUserInfo($this->email, $this->userType, static::APPLE_LOGIN);
            }
            if (!empty($userInfo)) {
                $userId = $userInfo['user_id'];
                $userObj = new User($userId);
                $arr = array('user_apple_id' => $this->appleId);
                if (!$userObj->setUserInfo($arr)) {
                    $message = Labels::getLabel($userObj->getError(), $this->siteLangId);
                    $this->setLoginErrorMessage($message);
                }
            } else {
                $this->userType = (0 < $this->userType ? $this->userType : User::USER_TYPE_BUYER);
                $exp = explode("@", $this->email);
                $appleUserName = substr($exp[0], 0, 80) . rand();
                $userId = $this->setupUser($this->userType, $appleUserName, static::APPLE_LOGIN, $this->appleId, $this->email);
                $userObj = new User($userId);
                if (!$userInfo = $userObj->getUserInfo(static::USER_INFO_ATTR)) {
                    $message = Labels::getLabel("MSG_USER_COULD_NOT_BE_SET", $this->siteLangId);
                    $this->setLoginErrorMessage($message);
                }
            }

            if (!empty($this->appleId) && !empty($userInfo['user_apple_id']) && $userInfo['user_apple_id'] != $this->appleId) {
                $message = Labels::getLabel("MSG_USER_SOCIAL_CREDENTIALS_NOT_MATCHED", $this->siteLangId);
                $this->setLoginErrorMessage($message);
            }

            $this->doLogin($userInfo);
        }

        $this->redirectAndAuthenticateUser(Apple::getRequestUri());
    }
}
