<?php

include_once CONF_INSTALLATION_PATH . 'library/GoogleAPI/vendor/autoload.php';

class GoogleLoginController extends SocialMediaAuthController
{
    public const KEY_NAME = 'GoogleLogin';

    private $client;
    private $clientId;
    private $clientSecret;
    private $developerKey;

    public function __construct($action)
    {
        parent::__construct($action);
    }

    private function validateSettings()
    {
        $settings = $this->getSettings();
        if (!isset($settings['client_id']) || !isset($settings['client_secret']) || !isset($settings['developer_key'])) {
            $message = Labels::getLabel('MSG_PLUGIN_SETTINGS_NOT_CONFIGURED', $this->siteLangId);
            $this->setErrorAndRedirect($message, true);
        }
        $this->clientId = $settings['client_id'];
        $this->clientSecret = $settings['client_secret'];
        $this->developerKey = $settings['developer_key'];
    }

    private function setupConfiguration()
    {
        $this->validateSettings();
        $redirectUri = CommonHelper::generateFullUrl(static::KEY_NAME, 'index', [], '', false);
        
        $this->client = new Google_Client();
        $this->client->setApplicationName(FatApp::getConfig('CONF_WEBSITE_NAME_' . $this->siteLangId)); // Set your applicatio name
        $this->client->setScopes(['email']);
        $this->client->setClientId($this->clientId);
        $this->client->setClientSecret($this->clientSecret);
        $this->client->setRedirectUri($redirectUri);
        $this->client->setDeveloperKey($this->developerKey);
    }


    public function index()
    {
        $get = FatApp::getQueryStringData();
        $userType = FatApp::getPostedData('type', FatUtility::VAR_INT, User::USER_TYPE_BUYER);
        $accessToken = FatApp::getPostedData('accessToken', FatUtility::VAR_STRING, '');
        
        if (true === MOBILE_APP_API_CALL && empty($accessToken)) {
            $message = Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId);
            $this->setErrorAndRedirect($message, true);
        }

        $this->setupConfiguration();
        
        if (!empty($accessToken) || isset($get['code'])) {
            if (empty($accessToken)) {
                $this->client->authenticate($get['code']);
                $accessToken = $this->client->getAccessToken();
                if (null == $accessToken) {
					$message = Labels::getLabel('MSG_UNABLE_TO_ACCESS_THIS_ACCOUNT', $this->siteLangId);
					$this->setErrorAndRedirect($message, true);
				}
            }

            $this->client->setAccessToken($accessToken);

            $this->oauth2 = new Google_Service_Oauth2($this->client);
            $user = $this->oauth2->userinfo->get();

            $userGoogleEmail = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
            $userGoogleId = $user['id'];
            $userGoogleName = $user['name'];

            if ($user['name'] == '') {
                $exp = explode("@", $user['email']);
                $userGoogleName = substr($exp[0], 0, 80);
            }
            
            $userInfo = $this->doLogin($userGoogleEmail, $userGoogleName, $userGoogleId, $userType);
            $this->redirectToDashboard($userInfo['user_preferred_dashboard']);
        }
        $authUrl = $this->client->createAuthUrl();
        FatApp::redirectUser($authUrl);
    }
}
