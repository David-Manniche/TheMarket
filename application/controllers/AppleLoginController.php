<?php
class AppleLoginController extends SocialMediaController
{
    private const PRODUCTION_URL = 'https://appleid.apple.com/auth/';
    private const KEY_NAME = 'AppleLogin';

    private $email;
    private $isPrivateEmailId;
    private $appleId;

    public function __construct($action)
    {
        parent::__construct($action);
    }

    
    private function getRequestUri()
    {
        $settings = static::getSettings(static::KEY_NAME);
        $redirectUri = CommonHelper::generateFullUrl('AppleLogin');
        $_SESSION['appleSignIn']['state'] = bin2hex(random_bytes(5));
        return static::PRODUCTION_URL . 'authorize?' . http_build_query([
            'response_type' => 'code id_token',
            'response_mode' => 'form_post',
            'client_id' => $settings['client_id'],
            'redirect_uri' => $redirectUri,
            'state' => $_SESSION['appleSignIn']['state'],
            'scope' => 'name email',
        ]);
    }

    private function validateResponse($appleResponse)
    {
        if (false ===  MOBILE_APP_API_CALL && $_SESSION['appleSignIn']['state'] != $appleResponse['state']) {
            $message = 'Authorization server returned an invalid state parameter';
            $this->setErrorMessage($message);
        }
        if (isset($_REQUEST['error'])) {
            $message = 'Authorization server returned an error: ' . htmlspecialchars($_REQUEST['error']);
            $this->setErrorMessage($message);
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
        $post = FatApp::getPostedData();
        $userType = FatApp::getPostedData('type', FatUtility::VAR_INT, User::USER_TYPE_BUYER);

        if (isset($post['id_token'])) {
            $this->validateResponse($post);
            $userInfo = $this->doLogin($this->email, $this->appleId, $userType);
        }

        $this->redirectAndAuthenticateUser(static::getRequestUri());
    }
}
