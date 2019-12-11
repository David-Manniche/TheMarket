<?php
class AppleLoginController extends SocialMediaController
{
    private const PRODUCTION_URL = 'https://appleid.apple.com/auth/';
    public const KEY_NAME = 'AppleLogin';

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

    public function index()
    {
        $post = FatApp::getPostedData();
        $userType = FatApp::getPostedData('type', FatUtility::VAR_INT, User::USER_TYPE_BUYER);

        if (isset($post['id_token'])) {
            if (false ===  MOBILE_APP_API_CALL && $_SESSION['appleSignIn']['state'] != $post['state']) {
                $message = 'Authorization server returned an invalid state parameter';
                $this->setErrorMessage($message);
            }
            if (isset($_REQUEST['error'])) {
                $message = 'Authorization server returned an error: ' . htmlspecialchars($_REQUEST['error']);
                $this->setErrorMessage($message);
            }
            $claims = explode('.', $post['id_token'])[1];
            $claims = json_decode(base64_decode($claims), true);
            
            $appleUserInfo = isset($post['user']) ? json_decode($post['user'], true) : false;
    
            $appleId = isset($claims['sub']) ? $claims['sub'] : '';
    
            if (false === $appleUserInfo) {
                if (!isset($claims['email'])) {
                    $message = Labels::getLabel('MSG_UNABLE_TO_FETCH_USER_INFO', $this->siteLangId);
                    $this->setErrorMessage($message);
                }
                $email = $claims['email'];
            } else {
                $email = $appleUserInfo['email'];
            }

            $this->doLogin($email, $appleId, $userType);
        }

        $this->redirectAndAuthenticateUser(static::getRequestUri());
    }
}
