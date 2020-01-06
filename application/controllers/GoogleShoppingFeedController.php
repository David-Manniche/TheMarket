<?php
include_once CONF_INSTALLATION_PATH . 'library/GoogleAPI/vendor/autoload.php';

class GoogleShoppingFeedController extends AdvertisementFeedBaseController
{
    public const KEY_NAME = 'GoogleShoppingFeed';

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
            $message = Labels::getLabel('MSG_SETTINGS_NOT_UPDATED', $this->siteLangId);
            $this->setErrorAndRedirect($message, true);
        }
        $this->clientId = $settings['client_id'];
        $this->clientSecret = $settings['client_secret'];
        $this->developerKey = $settings['developer_key'];
    }

    private function setupConfiguration()
    {
        $this->validateSettings();
        $redirectUri = CommonHelper::generateFullUrl(static::KEY_NAME);
        
        $this->client = new Google_Client();
        $this->client->setApplicationName(FatApp::getConfig('CONF_WEBSITE_NAME_' . $this->siteLangId)); // Set your application name
        $this->client->setScopes('https://www.googleapis.com/auth/content');
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
        
        if (true ===  MOBILE_APP_API_CALL && empty($accessToken)) {
            $message = Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId);
            $this->setErrorAndRedirect($message, true);
        }

        $this->setupConfiguration();
        
        if (!empty($accessToken) || isset($get['code'])) {
            if (empty($accessToken)) {
                $this->client->authenticate($get['code']);
                $accessToken = $this->client->getAccessToken();
            }

            $this->client->setAccessToken($accessToken);

            $this->oauth2 = new Google_Service_Oauth2($this->client);
            CommonHelper::printArray($this->oauth2, true);
            // $user = $this->oauth2->userinfo->get();
        }
        $authUrl = $this->client->createAuthUrl();
        FatApp::redirectUser($authUrl);
    }
}
