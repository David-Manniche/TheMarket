<?php

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

    public const REQUEST_CREATE_ACCOUNT = 1;
    public const REQUEST_UPDATE_ACCOUNT = 2;
    public const REQUEST_PERSON_ID = 3;
    public const REQUEST_ADD_BANK_ACCOUNT = 4;
    public const REQUEST_UPDATE_BUSINESS_TYPE = 5;

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

        if (false === $this->loadLoggedUserInfo()) {
            return false;
        }

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
     * create
     *
     * @return object
     */
    private function create(array $data): object
    {
        return \Stripe\Account::create($data);
    }

    /**
     * update
     *
     * @return object
     */
    private function update(array $data): object
    {
        return \Stripe\Account::update($this->getAccountId(), $data);
    }

    /**
     * createExternalAccount - For Financial(Bank) Data
     *
     * @return object
     */
    private function createExternalAccount(array $data): object
    {
        return \Stripe\Account::createExternalAccount($this->getAccountId(), $data);
    }
    


    /* Need to this below array dynamic as per country rules. Base on required Fields via validateUser() function requirements->currently_due.
        Must follow https://medium.com/@Keithweaver_/creating-your-own-marketplace-with-stripe-connect-php-like-shopify-or-uber-6eadbb08993f for help.
    */
    public function userAccountData(): array
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
        return $data;
    }
    
    /**
     * createAccount
     *
     * @return bool
     */
    public function createAccount(): bool
    {
        $data = $this->userAccountData();
        $resp = $this->create($data);
        $this->stripeAccountId = $resp->id;
        return $this->updateUserMeta('stripe_account_id', $resp->id);
    }
    
    /**
     * doRequest
     *
     * @param  mixed $requestType
     * @return mixed
     */
    private function doRequest(int $requestType, array $data = [])
    {
        try {
            switch ($requestType) {
                case self::REQUEST_CREATE_ACCOUNT:
                    return $this->createAccount();
                    break;
                case self::REQUEST_UPDATE_ACCOUNT:
                    return $this->updateAccount($data);
                    break;
                case self::REQUEST_PERSON_ID:
                    return $this->getPersonId();
                    break;
                case self::REQUEST_ADD_BANK_ACCOUNT:
                    return $this->addFinancialInfo($data);
                    break;
                case self::REQUEST_UPDATE_BUSINESS_TYPE:
                    return $this->updateBusinessType($data);
                    break;
            }
        } catch (\Stripe\Exception\CardException $e) {
            // Since it's a decline, \Stripe\Exception\CardException will be caught
            $this->error = $e->getError()->param . ' - ' . $e->getMessage();
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Too many requests made to the API too quickly
            $this->error = $e->getError()->param . ' - ' . $e->getMessage();
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            $this->error = $e->getError()->param . ' - ' . $e->getMessage();
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            $this->error = $e->getError()->param . ' - ' . $e->getMessage();
            // (maybe you changed API keys recently)
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
            $this->error = $e->getError()->param . ' - ' . $e->getMessage();
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            $this->error = $e->getError()->param . ' - ' . $e->getMessage();
            // yourself an email
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $this->error = $e->getMessage();
        }
        return false;
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
        $resp = \Stripe\Token::create([
            'pii' => ['id_number' => '000000000'],
        ]);
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
        
        return \Stripe\Account::retrieve($this->getAccountId());
    }

    /**
     * getRequiredFields
     *
     * @return array
     */
    public function getRequiredFields(): array
    {
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
        if (in_array('external_account', $this->getRequiredFields())) {
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

    /**
     * isUserValid
     *
     * @return bool
     */
    public function isUserValid(): bool
    {
        if (empty($this->getAccountId())) {
            if (false === $this->doRequest(self::REQUEST_CREATE_ACCOUNT)) {
                $this->error = $this->getError();
                return false;
            }
        }
        return (1 > count($this->getRequiredFields()));
    }
}
