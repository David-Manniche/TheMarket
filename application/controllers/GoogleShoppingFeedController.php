<?php
include_once CONF_INSTALLATION_PATH . 'library/GoogleAPI/vendor/autoload.php';

class GoogleShoppingFeedController extends AdvertisementFeedBaseController
{
    public const KEY_NAME = 'GoogleShoppingFeed';
    public const SCOPE = 'https://www.googleapis.com/auth/content';

    private const PRODUCTION_URL = 'https://www.googleapis.com/content/v2/';
    private const INSERT_URL = '{merchantId}/products';
    private const GET_URL = '{merchantId}/products/{productId}';
    private const DELETE_URL = '{merchantId}/products/{productId}';
    private const LIST_URL = '{merchantId}/products';
    
    private const BATCH_REQUEST_URL = 'products/batch';

    private $merchantId;

    private $client;
    private $clientId;
    private $clientSecret;
    private $developerKey;

    public function __construct($action)
    {
        parent::__construct($action);
        
        $this->merchantId = $this->getMerchantAccountDetail(self::KEY_NAME . '_merchantId');
        if (empty($this->merchantId)) {
            $this->setupMerchantDetail();
        }
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
        $redirectUri = CommonHelper::generateFullUrl(static::KEY_NAME, 'setupMerchantDetail');
        
        $this->client = new Google_Client();
        $this->client->setApplicationName(FatApp::getConfig('CONF_WEBSITE_NAME_' . $this->siteLangId)); // Set your application name
        $this->client->setScopes(self::SCOPE);
        $this->client->setClientId($this->clientId);
        $this->client->setClientSecret($this->clientSecret);
        $this->client->setRedirectUri($redirectUri);
        $this->client->setDeveloperKey($this->developerKey);
    }

    private function makeUrl($url, $replaceData = [])
    {
        $url = self::PRODUCTION_URL . $url;
        $replaceData = ['{merchantId}' => $this->merchantId] + $replaceData;
        return CommonHelper::replaceStringData($url, $replaceData);
    }

    public function setupMerchantDetail()
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

            $service = new Google_Service_ShoppingContent($this->client);
            $authDetail = $service->accounts->authinfo();
            $accountDetail = $authDetail->accountIdentifiers;
            if (empty($accountDetail)) {
                Message::addErrorMessage(Labels::getLabel("MSG_MERCHANT_ACCOUNT_DETAIL_NOT_FOUND", $this->siteLangId));
                $this->redirectBack();
            }
            $merchantId = array_shift($accountDetail)->merchantId;
            $this->updateMerchantAccountDetail([self::KEY_NAME . '_merchantId' => $merchantId]);
        }
        $authUrl = $this->client->createAuthUrl();
        FatApp::redirectUser($authUrl);
    }

    public function insert($data)
    {
        if (empty($data) || !is_array($data)) {
            $this->error = Labels::getLabel('LBL_INVALID_REQUEST', CommonHelper::getLangId());
            return false;
        }
        $url = $this->makeUrl(self::INSERT_URL);
        return $this->doRequest($url, 'POST', $data);
    }

    public function get($productId)
    {
        if (empty($productId)) {
            $this->error = Labels::getLabel('MSG_INVALID_REQUEST', CommonHelper::getLangId());
            return false;
        }

        $url = $this->makeUrl(self::GET_URL, ['{productId}' => $productId]);
        return $this->doRequest($url, 'GET');
    }

    public function delete($productId)
    {
        if (empty($productId)) {
            $this->error = Labels::getLabel('MSG_INVALID_REQUEST', CommonHelper::getLangId());
            return false;
        }

        $url = $this->makeUrl(self::DELETE_URL, ['{productId}' => $productId]);
        return $this->doRequest($url, 'DELETE');
    }

    public function list()
    {
        $url = $this->makeUrl(self::LIST_URL);
        return $this->doRequest($url, 'GET');
    }
}
