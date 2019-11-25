<?php
abstract class PaymentController extends MyAppController
{
    abstract protected function allowedCurrenciesArr();
    abstract public function charge($orderId);

    public function __construct($action)
    {
        parent::__construct($action);

        $currency = Currency::getDefault();
        if (empty($currency)) {
            throw new Exception("Default Currency not set");
        }

        $this->systemCurrencyId = $currency['currency_id'];
        $this->systemCurrencyCode = $currency['currency_code'];

        if (!is_array($this->allowedCurrenciesArr())) {
            trigger_error('Invalid currency format', E_USER_ERROR);
        }

        if (!in_array($this->systemCurrencyCode, $this->allowedCurrenciesArr())) {
            Message::addErrorMessage(Labels::getLabel('MSG_INVALID_ORDER_CURRENCY_PASSED_TO_GATEWAY', $this->siteLangId));
            CommonHelper::redirectUserReferer();
        }
    }
}
