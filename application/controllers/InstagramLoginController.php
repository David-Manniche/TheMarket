<?php

include_once CONF_INSTALLATION_PATH . 'library/instagram/instagram-login-api.php';
class InstagramLoginController extends SocialMediaAuthController
{
    private const PRODUCTION_URL = 'https://api.instagram.com/oauth/';
    public const KEY_NAME = 'InstagramLogin';

    private $redirectUri;

    public $requiredKeys = [
        'client_id',
        'client_secret'
    ];

    public function __construct($action)
    {
        parent::__construct($action);
        if (false == $this->validateSettings($this->siteLangId)) {
            $this->setErrorAndRedirect($this->error, true);
            return false;
        }
        $this->redirectUri = CommonHelper::generateFullUrl(static::KEY_NAME, 'index', [], '', false);
    }
    
    private function getRequestUri()
    {
        return static::PRODUCTION_URL . 'authorize?' . http_build_query([
            'response_type' => 'code',
            'client_id' => $this->settings['client_id'],
            'redirect_uri' => $this->redirectUri,
            'scope' => 'basic',
        ]);
    }

    public function index()
    {
        $get = FatApp::getQueryStringData();
        $userType = FatApp::getPostedData('type', FatUtility::VAR_INT, User::USER_TYPE_BUYER);
        $accessToken = FatApp::getPostedData('accessToken', FatUtility::VAR_STRING, '');
        
        $instaAuthObj = new InstagramApi();

        if (empty($accessToken)) {
            if (isset($get['code'])) {
                try {
                    $accessToken = $instaAuthObj->GetAccessToken($this->settings['client_id'], $this->redirectUri, $this->settings['client_secret'], $get['code']);
                } catch (\Error $e) {
                    $this->setErrorAndRedirect($e->getMessage());
                }
            }
        }

        if (!empty($accessToken)) {
            $userInfo = $instaAuthObj->GetUserProfileInfo($accessToken);
            $instagramId = $userInfo['id'];
            $userName = $userInfo['username'];
            // $fullName = $userInfo['full_name'];
            $userName = $userName . $instagramId;
            if (empty($instagramId)) {
                FatUtility::dieJsonError(Labels::getLabel("MSG_INVALID_REQUEST", $this->siteLangId));
            }

            $userInfo = $this->doLogin('', $userName, $instagramId, $userType);
            $this->redirectToDashboard($userInfo['user_preferred_dashboard']);
        }
        FatApp::redirectUser($this->getRequestUri());
    }
}
