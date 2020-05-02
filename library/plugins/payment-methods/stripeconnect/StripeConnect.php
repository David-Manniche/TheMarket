<?php

class StripeConnect extends PaymentMethodBase
{
    public const KEY_NAME = __CLASS__;
    private $stripeAccountId;
    private $requiredFields = [];
    private $business_profile = [];
    private $userInfoObj;

    public $requiredKeys = [
        'publishable_key',
        'secret_key'
    ];

    public const REQUEST_CREATE_ACCOUNT = 1;
    public const REQUEST_PERSON_ID = 2;

    /**
     * __construct
     *
     * @param  int $langId
     * @return void
     */
    public function __construct(int $langId)
    {
        $this->langId = 0 < $langId ? $langId : CommonHelper::getLangId();
        if (false == $this->validateSettings()) {
            return false;
        }
        $this->initialize();
    }

    /**
     * initialize
     *
     * @return void
     */
    private function initialize()
    {
        if (false === $this->validateLoggedUser()) {
            return false;
        }

        require_once dirname(__FILE__) . '/vendor/autoload.php';

        \Stripe\Stripe::setApiKey($this->settings['secret_key']);
        return true;
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
            'business_type' => 'individual',
            'business_profile' => [
                'name' => $this->userData['shop_name'],
                // 'url' => CommonHelper::generateFullUrl('shops', 'view', [$this->userData['shop_id']]),
                'url' => 'https://satbir.yokartv8.4livedemo.com' . CommonHelper::generateUrl('shops', 'view', [$this->userData['shop_id']]),
                'support_url' => 'https://satbir.yokartv8.4livedemo.com' . CommonHelper::generateUrl('shops', 'view', [$this->userData['shop_id']]),
                'support_phone' => $this->userData['shop_phone'],
                'support_email' => $this->userData['credential_email'],
                'support_address' => $this->userData['user_city'] . ', ' . $this->userData['state_code'] . ', ' . $this->userData['country_code'],
                'product_description' => $this->userData['shop_description'],
            ],
            'individual' => [
                'address' => [
                    'city' => $this->userData['user_city'],
                    'line1' => $this->userData['user_city'],
                    'postal_code' => $this->userData['shop_postalcode'],
                    'state' => $this->userData['state_code'],
                ],
                'dob' => [
                    'day' => $dob[2],
                    'month' => $dob[1],
                    'year' => $dob[0],
                ],
                'email' => $this->userData['credential_email'],
                'first_name' => $name[0],
                'last_name' => isset($name[1]) ? $name[1] : '',
                'phone' => $this->userData['shop_phone'],
                'id_number' => $this->doRequest(self::REQUEST_PERSON_ID)
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

    public function createAccount(): bool
    {
        if (false === $this->loadLoggedUserInfo()) {
            return false;
        }
        $data = $this->userAccountData();
        $resp = \Stripe\Account::create($data);
        $this->stripeAccountId = $resp->id;
        return $this->updateUserMeta('stripe_account_id', $resp->id);
    }
    
    /**
     * doRequest
     *
     * @param  mixed $requestType
     * @return mixed
     */
    private function doRequest(int $requestType)
    {
        try {
            switch ($requestType) {
                case self::REQUEST_CREATE_ACCOUNT:
                    return $this->createAccount();
                    break;
                case self::REQUEST_PERSON_ID:
                    return $this->getPersonId();
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
        return empty($this->stripeAccountId) ? $this->getUserMeta('stripe_account_id') : $this->stripeAccountId;
    }

    /**
     * getRemoteUserInfo
     *
     * @return object
     */
    public function getRemoteUserInfo(): object
    {
        return \Stripe\Account::retrieve($this->getAccountId());
    }

    /**
     * getRequiredFields
     *
     * @return array
     */
    public function getRequiredFields(): array
    {

        return $this->requiredFields;
    }

    /**
     * getBusinessProfile
     *
     * @return object
     */
    public function getBusinessProfile(): object
    {

        return (object) $this->business_profile;
    }

    public function updateRequiredFields(array $data): bool
    {
        $accountObj = $this->getRemoteUserInfo();
        // $accountObj->individual->
    }

    /**
     * userAccountCanTransfer
     *
     * @return bool
     */
    public function userAccountCanTransfer(): bool
    {
        $user = !empty($this->userInfoObj) ? $this->userInfoObj : $this->getRemoteUserInfo();
        return ('inactive' == $user->capabilities->transfers) ? false : true;
    }

    /**
     * isUserValid
     *
     * @return bool
     */
    public function isUserValid(): bool
    {
        $this->userInfoObj = $this->getRemoteUserInfo();
        if (empty($this->getAccountId()) || false === $this->userAccountCanTransfer()) {
            if (false === $this->doRequest(self::REQUEST_CREATE_ACCOUNT)) {
                $this->error = $this->getError();
                return false;
            }
        }

        if (isset($this->userInfoObj->business_profile)) {
            $this->business_profile  = $this->userInfoObj->business_profile;
            if ('US' != strtoupper($this->userData['country_code'])) {
                unset($this->business_profile['mcc']);
            }
        }

        if (isset($this->userInfoObj->business_profile)) {
            $this->requiredFields  = $this->userInfoObj->requirements->currently_due;
            $this->requiredFields = array_diff($this->requiredFields, ['external_account']);
            $this->requiredFields = array_map('array_filter', $this->requiredFields);
            return false;
        }
        return true;
    }
}
