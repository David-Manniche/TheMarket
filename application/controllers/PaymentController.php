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
            $this->setErrorAndRedirect(Labels::getLabel('MSG_DEFAULT_CURRENCY_NOT_SET', $this->siteLangId), FatUtility::isAjaxCall());
        }

        $this->systemCurrencyId = $currency['currency_id'];
        $this->systemCurrencyCode = strtoupper($currency['currency_code']);

        if (!is_array($this->allowedCurrenciesArr())) {
            $this->setErrorAndRedirect(Labels::getLabel('MSG_INVALID_CURRENCY_FORMAT', $this->siteLangId), FatUtility::isAjaxCall());
        }

        if (!in_array($this->systemCurrencyCode, $this->allowedCurrenciesArr())) {
            $this->setErrorAndRedirect(Labels::getLabel('MSG_INVALID_ORDER_CURRENCY_PASSED_TO_GATEWAY', $this->siteLangId), FatUtility::isAjaxCall());
        }
        $this->set('systemCurrencyCode', $this->systemCurrencyCode);
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

    protected function setErrorAndRedirect(string $msg = "", bool $json = false, $redirect = true)
    {
        $msg = !empty($msg) ? $msg : $this->stripeConnect->getError();
        $json = FatUtility::isAjaxCall() ? true : $json;
        LibHelper::exitWithError($msg, $json, $redirect);
        CommonHelper::redirectUserReferer();
    }
}
