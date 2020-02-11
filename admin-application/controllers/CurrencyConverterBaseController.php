<?php

class CurrencyConverterBaseController extends PluginSettingController
{
    protected $baseCurrencyId;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->baseCurrencyId = FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1);
    }

    protected function getAllCurrencies($exceptDefault = false)
    {
        $currencies = Currency::getCurrencyAssoc($this->adminLangId);
        if (true === $exceptDefault) {
            unset($currencies[$this->baseCurrencyId]);
        }
        return $currencies;
    }

    protected function getCurrencyCode($id)
    {
        $currencies = $this->getAllCurrencies($this->adminLangId);
        return isset($currencies[$id]) ? $currencies[$id] : '';
    }

    protected function getBaseCurrencyCode()
    {
        $baseCurrencyCode = $this->getCurrencyCode($this->baseCurrencyId);
        if (empty($baseCurrencyCode)) {
            $message = Labels::getLabel('MSG_BASE_CURRENCY_NOT_INITIALIZED', $this->adminLangId);
            LibHelper::dieJsonError($message);
        }
        return $baseCurrencyCode;
    }

    public function update()
    {
        $defaultConverter = get_called_class();
        if (__CLASS__ === $defaultConverter) {
            $message = Labels::getLabel('MSG_INVALID_ACCESS', $this->adminLangId);
            LibHelper::dieJsonError($message);
        }
        $currencies = $this->getAllCurrencies(true);
        $obj = new $defaultConverter(__FUNCTION__);
        $currenciesData = $obj->getRates($currencies);
        if (empty($currenciesData) || false === $currenciesData['status'] || !isset($currenciesData['data']) || empty($currenciesData['data'])) {
            $message = !empty($currenciesData['msg']) ? $currenciesData['msg'] : Labels::getLabel('MSG_UNABLE_TO_UPDATE', $this->adminLangId);
            LibHelper::dieJsonError($message);
        }
        $currObj = new Currency();
        if (false === $currObj->updatePricingRates($currenciesData['data'])) {
            LibHelper::dieJsonError($currObj->getError());
        }
        FatUtility::dieJsonSuccess(Labels::getLabel('MSG_Updated_Successfully', $this->adminLangId));
    }
}
