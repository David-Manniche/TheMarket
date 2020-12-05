<?php

/**
 * Dpo - API's reference https://docs.paygate.co.za/
 */
class Dpo extends PaymentMethodBase
{
    public const KEY_NAME = __CLASS__;

    public $requiredKeys = [
        'paygate_id',
        'encryption_key',
    ];
    private $env = Plugin::ENV_SANDBOX;
    private $paygateId = '';
    private $encryptionKey = '';
    protected $response = '';

    /**
     * __construct
     *
     * @param  int $langId
     * @return void
     */
    public function __construct(int $langId)
    {
        $this->langId = 0 < $langId ? $langId : CommonHelper::getLangId();
        $this->requiredKeys();
    }

    /**
     * requiredKeys
     *
     * @return void
     */
    public function requiredKeys()
    {
        $this->env = FatUtility::int($this->getKey('env'));
        if (0 < $this->env) {
            $this->requiredKeys = [
                'live_paygate_id',
                'live_encryption_key',
            ];
        }
    }

    /**
     * init
     *
     * @param  int $userId
     * @return bool
     */
    public function init(int $userId): bool
    {
        if (false == $this->validateSettings()) {
            return false;
        }

        if (false === $this->loadBaseCurrencyCode()) {
            return false;
        }

        if (0 < $userId) {
            if (false === $this->loadLoggedUserInfo($userId)) {
                return false;
            }
        }

        $this->paygateId = Plugin::ENV_PRODUCTION == $this->settings['env'] ? $this->settings['live_paygate_id'] : $this->settings['paygate_id'];
        $this->encryptionKey = Plugin::ENV_PRODUCTION == $this->settings['env'] ? $this->settings['live_encryption_key'] : $this->settings['encryption_key'];
        return true;
    }

    /**
     * getResponse
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }
}
