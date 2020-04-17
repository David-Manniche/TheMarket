<?php

class StripeConnect extends PaymentMethodBase
{
    public const KEY_NAME = __CLASS__;
    private $stripeAccountId;
    private $requiredPendingFields = [];

    public $requiredKeys = [
        'publishable_key',
        'secret_key'
    ];
            
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
    public function createAccount()
    {
        if (false === $this->loadLoggedUserInfo()) {
            return false;
        }
        
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
                // 'mcc' => mt_rand(1000, 9999), /* Just for testing. To know about merchant category codes https://en.wikipedia.org/wiki/Merchant_category_code */
                'name' => $this->userData['shop_name'],
                // 'url' => CommonHelper::generateUrl('shops', 'view', [$this->userData['shop_id']]),
                'url' => 'https://satbir.yokartv8.4livedemo.com/jasons-store',
                'product_description' => $this->userData['shop_description'],
            ],
            'individual' => [
                'address' => [
                    'city' => $this->userData['user_city'],
                    'line1' => $this->userData['user_address1'],
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
                // 'ssn_last_4' => mt_rand(1000, 9999), /* Just for testing. To know about social security number (U.S. only)*/
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

        try {
            $resp = \Stripe\Account::create($data);
            $this->stripeAccountId = $resp->id;
            return $this->updateUserMeta('stripe_account_id', $resp->id);
        } catch (Throwable $e) {
            $this->error = $e->getMessage();
            return false;
        }
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

    public function getRemoteUserInfo(): object
    {

        return \Stripe\Account::retrieve($this->getAccountId());
    }

    public function setupFinancialInfo()
    {

    }

    public function validateUser()
    {
        $stripeAccountObj = $this->getRemoteUserInfo();
        CommonHelper::printArray($stripeAccountObj, true);
    }
}
