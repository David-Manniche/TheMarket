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

    public function createAccount()
    {
        if (false === $this->loadLoggedUserInfo()) {
            return false;
        }

        $data = [
            'type' => 'custom',
            'country' => strtoupper($this->userData['country_code']),
            'email' => $this->userData['credential_email'],
            'requested_capabilities' => [
              'card_payments',
              'transfers',
            ],
            'business_type' => 'individual',
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
        } catch (\Error $e) {
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

    public function validateStripeUser()
    {
        $stripeAccountObj = $this->getRemoteUserInfo();
        CommonHelper::printArray($stripeAccountObj, true);
    }
}
