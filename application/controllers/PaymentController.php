<?php

abstract class PaymentController extends MyAppController
{
    abstract protected function allowedCurrenciesArr();
    abstract public function charge($orderId);
    
    protected $systemCurrencyCode;
    protected $systemCurrencyId;
    public $settings = [];

    public function __construct($action)
    {
        parent::__construct($action);

        $currency = Currency::getDefault();
        if (empty($currency)) {
            $this->setErrorAndRedirect(Labels::getLabel('MSG_DEFAULT_CURRENCY_NOT_SET', $this->siteLangId));
        }

        $this->systemCurrencyId = $currency['currency_id'];
        $this->systemCurrencyCode = strtoupper($currency['currency_code']);

        if (!is_array($this->allowedCurrenciesArr())) {
            $this->setErrorAndRedirect(Labels::getLabel('MSG_INVALID_CURRENCY_FORMAT', $this->siteLangId));
        }

        if (!in_array($this->systemCurrencyCode, $this->allowedCurrenciesArr())) {
            $this->setErrorAndRedirect(Labels::getLabel('MSG_INVALID_ORDER_CURRENCY_PASSED_TO_GATEWAY', $this->siteLangId));
        }

        $this->loadPaymenMethod();
    }

    private function loadPaymenMethod(): void
    {
        if (defined('static::KEY_NAME')) {
            $pluginKeyName = static::KEY_NAME;
            
            $this->plugin = PluginHelper::callPlugin($pluginKeyName, [$this->siteLangId], $error, $this->siteLangId);
            if (false === $this->plugin) {
                Message::addErrorMessage($error);
                CommonHelper::redirectUserReferer();
            }
        }
    }

    protected function setErrorAndRedirect(string $msg = "")
    {
        $msg = !empty($msg) ? $msg : $this->stripeConnect->getError();
        LibHelper::exitWithError($msg, false, true);
        CommonHelper::redirectUserReferer();
    }
}
