<?php
require_once dirname(__FILE__) . '/StripeConnectFunctions.php';

class StripeConnect extends PaymentMethodBase
{
    public const KEY_NAME = __CLASS__;
    private $stripeAccountId;
    private $requiredFields = [];
    private $userInfoObj;

    public $requiredKeys = [
        'publishable_key',
        'secret_key'
    ];

    use StripeConnectFunctions;

    public const REQUEST_CREATE_ACCOUNT = 1;
    public const REQUEST_RETRIEVE_ACCOUNT = 2;
    public const REQUEST_UPDATE_ACCOUNT = 3;
    public const REQUEST_PERSON_ID = 4;
    public const REQUEST_ADD_BANK_ACCOUNT = 5;
    public const REQUEST_UPDATE_BUSINESS_TYPE = 6;

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
    public function connect(): bool
    {
        if (empty($this->getAccountId())) {
            if (false === $this->doRequest(self::REQUEST_CREATE_ACCOUNT)) {
                return false;
            }
        }
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
        $this->stripeAccountId = $resp->id;
        return $this->updateUserMeta('stripe_account_id', $resp->id);
    }
    

    /**
     * getPersonId
     *
     * @return string
     */
    public function getPersonId(): string
    {
        $personId = $this->getUserMeta('stripe_person_id');
        if (empty($personId)) {
            $this->createPersonId();
            $personId = $this->getUserMeta('stripe_person_id');
        }
        return (string)$personId;
    }

    /**
     * createPersonId
     *
     * @return string
     */
    private function createPersonId(): bool
    {
        $resp = $this->createToken();
        return $this->updateUserMeta('stripe_person_id', $resp->id);
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
        $this->update($data);
        $this->updateUserMeta('business_type', $data['business_type']);
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
        $businessType = $this->getUserMeta('business_type');
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
        return $this->updateUserMeta('stripe_bank_account_id', $resp->id);
    }

    /**
     * updateAccount
     * 
     * @param array $data 
     * @return bool
     */
    public function updateAccount(array $data): bool
    {
        $this->update($data);
        return true;
    }

    /**
     * userAccountCanTransfer
     *
     * @return bool
     */
    public function userAccountCanTransfer(): bool
    {
        $this->userInfoObj = $this->getRemoteUserInfo();
        return ('inactive' == $this->userInfoObj->capabilities->transfers) ? false : true;
    }
}
