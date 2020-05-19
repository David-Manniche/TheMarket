<?php
require_once dirname(__FILE__) . '/StripeConnectFunctions.php';

class StripeConnect extends PaymentMethodBase
{
    public const KEY_NAME = __CLASS__;
    private $stripeAccountId;
    private $stripeAccountType;
    private $requiredFields = [];
    private $userInfoObj;
    private $response;

    public $requiredKeys = [
        'client_id',
        'publishable_key',
        'secret_key'
    ];

    private const CONNECT_URI = "https://connect.stripe.com/oauth";

    use StripeConnectFunctions;

    public const REQUEST_CREATE_ACCOUNT = 1;
    public const REQUEST_RETRIEVE_ACCOUNT = 2;
    public const REQUEST_UPDATE_ACCOUNT = 3;
    public const REQUEST_PERSON_TOKEN = 4;
    public const REQUEST_ADD_BANK_ACCOUNT = 5;
    public const REQUEST_UPDATE_BUSINESS_TYPE = 6;
    public const REQUEST_CREATE_PERSON = 7;
    public const REQUEST_UPDATE_PERSON = 8;
    public const REQUEST_UPLOAD_VERIFICATION_FILE = 9;

    /**
     * __construct
     *
     * @param  int $langId
     * @return void
     */
    public function __construct(int $langId)
    {
        $this->langId = 0 < $langId ? $langId : CommonHelper::getLangId();
    }

    /**
     * init
     *
     * @return void
     */
    public function init()
    {
        if (false == $this->validateSettings()) {
            return false;
        }

        if (false === $this->validateLoggedUser()) {
            return false;
        }

        $this->loadLoggedUserInfo();

        require_once dirname(__FILE__) . '/vendor/autoload.php';

        \Stripe\Stripe::setApiKey($this->settings['secret_key']);
        return true;
    }

    /**
     * getRedirectUri
     * 
     * @return string
     */
    public function getRedirectUri(): string
    {
        return self::CONNECT_URI . "/authorize?response_type=code&client_id=" . $this->settings['client_id'] . "&scope=read_write&redirect_uri=" . CommonHelper::generateFullUrl(self::KEY_NAME, 'callback');
    }

    /**
     * getKeys - To get plugin keys
     * 
     * @return array
     */
    public function getKeys(): array
    {
        return $this->settings;
    }

    /**
     * connect
     *
     * @return bool
     */
    private function connect(): bool
    {
        $params = [
            'clientId'                => $this->settings['client_id'],
            'clientSecret'            => $this->settings['secret_key'],
            'redirectUri'             => $this->getRedirectUri(),
            'urlAuthorize'            => self::CONNECT_URI . '/authorize',
            'urlAccessToken'          => self::CONNECT_URI . '/token',
            'urlResourceOwnerDetails' => 'https://api.stripe.com/v1/account'
        ];
        $this->stripe = new \League\OAuth2\Client\Provider\GenericProvider($params);
        return true;
    }

    /**
     * register
     *
     * @return bool
     */
    public function register(): bool
    {
        if (empty($this->getAccountId())) {
            if (false === $this->doRequest(self::REQUEST_CREATE_ACCOUNT)) {
                return false;
            }
        }
        return true;
    }

    /**
     * accessAccountId
     * 
     * @param string $code 
     * @return bool
     */
    public function accessAccountId(string $code): bool
    {
        try {
            $this->connect();
            $accessToken = $this->stripe->getAccessToken('authorization_code', [
                'code' => $code
            ]);
            $this->stripeAccountId = $this->stripe->getResourceOwner($accessToken)->getId();
            return $this->updateUserMeta('stripe_account_id', $this->stripeAccountId);
        }
        catch (Exception $e){
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * isUserAccountRejected
     *
     * @return bool
     */
    public function isUserAccountRejected(): bool
    {
        $this->userInfoObj = $this->getRemoteUserInfo();
        $requirements = $this->userInfoObj->requirements;
        if (isset($requirements->disabled_reason) && false !== strpos($requirements->disabled_reason, "rejected")) {
            $this->unsetUserAccountElements();
            $msg = Labels::getLabel('MSG_YOUR_ACCOUNT_HAS_BEEN_', $this->langId);
            $this->error = $msg . ucwords(str_replace(".", " - ", $requirements->disabled_reason));
            return true;
        }
        return false;
    }

    /**
     * unsetUserAccountElements
     * 
     * @return type
     */
    private function unsetUserAccountElements(): bool
    {
        FatApp::getDb()->deleteRecords(User::DB_TBL_META, ['smt' => 'usermeta_user_id = ? AND usermeta_key LIKE ? ', 'vals' => [$this->userId, 'stripe_%']]);
        return true;
    }

    /**
     * createAccount
     *
     * Can follow: https://stripe.com/docs/api/persons/create OR https://medium.com/@Keithweaver_/creating-your-own-marketplace-with-stripe-connect-php-like-shopify-or-uber-6eadbb08993f for help.
     * 
     * @return bool
     */
    public function createAccount(): bool
    {
        $name = explode(' ', $this->userData['user_name']);
        $dob = explode('-', $this->userData['user_dob']);
        $data = [
            'type' => 'custom',
            'country' => strtoupper($this->userData['country_code']),
            'email' => $this->userData['credential_email'],
            'requested_capabilities' => [
                'card_payments',
                'transfers',
            ],
            'business_profile' => [
                'name' => $this->userData['shop_name'],
                // 'url' => CommonHelper::generateFullUrl('shops', 'view', [$this->userData['shop_id']]),
                'url' => 'https://satbir.yokartv8.4livedemo.com' . CommonHelper::generateUrl('shops', 'view', [$this->userData['shop_id']]),
                'support_url' => 'https://satbir.yokartv8.4livedemo.com' . CommonHelper::generateUrl('shops', 'view', [$this->userData['shop_id']]),
                'support_phone' => $this->userData['shop_phone'],
                'support_email' => $this->userData['credential_email'],
                'support_address' => [
                    'city' => $this->userData['shop_city'],
                    'country' => strtoupper($this->userData['country_code']),
                    'line1' => $name[0],
                    'line2' => $this->userData['shop_name'],
                    'postal_code' => $this->userData['shop_postalcode'],
                    'state' => $this->userData['state_code'],
                ],
                'product_description' => $this->userData['shop_description'],
            ],
            'tos_acceptance' => [
                'date' => time(),
                'ip' => CommonHelper::getClientIp(),
                'user_agent' => CommonHelper::userAgent(),
            ]
        ];

        if (true === $this->getBaseCurrencyCode()) {
            $data['default_currency'] = $this->systemCurrencyCode;
        }

        $resp = $this->create($data);
        if (false === $resp) {
            return false;
        }
        $this->stripeAccountId = $resp->id;
        $this->updateUserMeta('stripe_account_type', 'custom');
        return $this->updateUserMeta('stripe_account_id', $resp->id);
    }
    

    /**
     * getPersonToken
     *
     * @return string
     */
    public function getPersonToken(): string
    {
        $personId = $this->getUserMeta('stripe_person_token');
        if (empty($personId)) {
            if (false === $this->createPersonToken()) {
                return false;
            }
            $personId = $this->getUserMeta('stripe_person_token');
        }
        return (string)$personId;
    }

    /**
     * createPersonToken
     *
     * @return string
     */
    private function createPersonToken(): bool
    {
        $resp = $this->createToken();
        if (false === $resp) {
            return false;
        }
        return $this->updateUserMeta('stripe_person_token', $resp->id);
    }

    /**
     * getAccountId
     *
     * @return string
     */
    public function getAccountId(): string
    {
        if (!empty($this->stripeAccountId)) {
            return $this->stripeAccountId;
        }
        
        return $this->getUserMeta('stripe_account_id');
    }

    /**
     * getAccountType
     *
     * @return string
     */
    public function getAccountType(): string
    {
        if (!empty($this->stripeAccountType)) {
            return $this->stripeAccountType;
        }
        
        $this->stripeAccountType = $this->getUserMeta('stripe_account_type');
        if (empty($this->stripeAccountType)) {
            $this->accessAccountType();
            $this->updateUserMeta('stripe_account_type', $this->stripeAccountType);
        }
        return $this->stripeAccountType;
    }

    /**
     * accessAccountType
     *
     * @return bool
     */
    private function accessAccountType(): bool
    {
        $this->userInfoObj = $this->getRemoteUserInfo();
        $this->stripeAccountType = $this->userInfoObj->type;
        return true;
    }

    /**
     * getRelationshipPersonId
     *
     * @return string
     */
    public function getRelationshipPersonId(): string
    {
        return $this->getUserMeta('stripe_person_id');
    }

    /**
     * getRemoteUserInfo
     *
     * @return object
     */
    public function getRemoteUserInfo(): object
    {
        if (!empty($this->userInfoObj)) {
            return $this->userInfoObj;
        }
        
        return $this->doRequest(self::REQUEST_RETRIEVE_ACCOUNT);
    }

    /**
     * getRequiredFields
     *
     * @return array
     */
    public function getRequiredFields(): array
    {
        if (empty($this->getAccountId())) {
            return [];
        }

        if (!empty($this->requiredFields)) {
            return $this->requiredFields;
        }

        $this->userInfoObj = $this->getRemoteUserInfo();
        if (isset($this->userInfoObj->requirements->currently_due)) {
            $this->requiredFields  = $this->userInfoObj->requirements->currently_due;
        }
        return $this->requiredFields;
    }
    
    /**
     * isFinancialInfoRequired
     *
     * @return bool
     */
    public function isFinancialInfoRequired(): bool
    {
        if (!empty($this->getAccountId()) && in_array('external_account', $this->getRequiredFields())) {
            return true;
        }
        return false;
    }

    /**
     * updateRequiredFields
     * 
     * @param array $data 
     * @return bool
     */
    public function updateRequiredFields(array $data): bool
    {
        $requestType = '';
        $actionType = 'N/A';
        if (isset($data['action_type'])) {
            $actionType = $data['action_type'];
            unset($data['action_type']);
        }
        
        switch ($actionType) {
            case 'business_type':
                $requestType = self::REQUEST_UPDATE_BUSINESS_TYPE;
                break;
            case 'external_account':
                $requestType = self::REQUEST_ADD_BANK_ACCOUNT;
                break;
            default:
                $requestType = self::REQUEST_UPDATE_ACCOUNT;
                break;
        }

        return $this->doRequest($requestType, $data);
    }

    /**
     * updateBusinessType
     * 
     * @param array $data 
     * @return bool
     */
    private function updateBusinessType(array $data): bool
    {
        if (false === $this->update($data)) {
            return false;
        }

        $this->updateUserMeta('stripe_business_type', $data['business_type']);
        return true;
    }

    /**
     * addFinancialInfo
     * 
     * @param array $data 
     * @return bool
     */
    private function addFinancialInfo(array $data): bool
    {
        $businessType = $this->getUserMeta('stripe_business_type');

        $this->getBaseCurrencyCode();
        $data = [
                'external_account' => [
                    'object' => 'bank_account',
                    'account_holder_name' => $data['account_holder_name'],
                    'account_number' => $data['account_number'],
                    'account_holder_type' => $businessType,
                    /*'country' => strtoupper($this->userData['country_code']),
                    'currency' => $this->systemCurrencyCode,*/
                    'country' => 'US',
                    'currency' => 'USD',
                    'routing_number' => $data['routing_number'],
                ]
            ];

        $resp = $this->createExternalAccount($data);
        if (false === $resp) {
            return false;
        }
        return $this->updateUserMeta('stripe_bank_account_id', $resp->id);
    }

    /**
     * updateAccount
     * 
     * @param array $data 
     * @return bool
     */
    private function updateAccount(array $data): bool
    {
        $relationship = [];
        $personData = [];

        $personId = $this->getRelationshipPersonId();

        if (array_key_exists('relationship', $data)) {
            $relationship = $data['relationship'];
            unset($data['relationship']);
        }

        if (!empty($personId) && array_key_exists($personId, $data)) {
            $personData = $data[$personId];
            unset($data[$personId]);
        }

        if (array_key_exists('individual', $data) && in_array('individual.id_number', $this->getRequiredFields())) {
            $data['individual']['id_number'] = $this->doRequest(self::REQUEST_PERSON_TOKEN);
        }

        if (!empty($data)) {
            $this->update($data);
        }

        if (empty($personId) && !empty($relationship)) {
            $resp = $this->doRequest(self::REQUEST_CREATE_PERSON, ['relationship' => $relationship]);
            if (false === $resp) {
                return false;
            }
            $this->updateUserMeta('stripe_person_id', $resp->id);
        } else if (!empty($personId) && (!empty($relationship) || !empty($personData))) {
            $relationship = !empty($relationship) ? ['relationship' => $relationship] : [];
            $data = array_merge($relationship, $personData);
            // CommonHelper::printArray($data, true);
            $resp = $this->doRequest(self::REQUEST_UPDATE_PERSON, $data);
            if (false === $resp) {
                return false;
            }
        }

        return true;
    }

    /**
     * uploadVerificationFile
     * 
     * @param string $path 
     * @return bool
     */
    public function uploadVerificationFile(string $path): bool
    {
        $this->response = $this->doRequest(self::REQUEST_UPLOAD_VERIFICATION_FILE, [$path]);
        if (false === $this->response) {
            return false;
        }
        return true;
    }

    /**
     * updateVericationDocument
     * 
     * @param string $side - Front/Back of uploaded document
     * @param string $responseId - Returned from "function createFile"
     * @return bool
     */
    public function updateVericationDocument(string $side): bool
    {
        if (empty($this->response)) {
            $this->error = Labels::getLabel('LBL_INVALID_REQUEST', $this->langId);
            return false;
        }

        $data = [
            'verification' => [
                'document' => [
                    $side => $this->response
                ]
            ]
        ];
        $resp = $this->doRequest(self::REQUEST_UPDATE_PERSON, $data);
        return (false === $resp) ? false : true;
    }
}
