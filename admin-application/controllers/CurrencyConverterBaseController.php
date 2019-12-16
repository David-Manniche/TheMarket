<?php
class CurrencyConverterBaseController extends PluginSettingController
{
    protected $baseCurrencyId;
    protected $baseCurrencyCode;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->baseCurrencyId = FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1);
    }
    
    protected function getExternalApiData($apiUrl)
    {
        if (empty($apiUrl)) {
            $message = Labels::getLabel('MSG_INVALID_REQUEST_URL', $this->adminLangId);
            LibHelper::dieJsonError($message);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $result = curl_exec($ch);
    
        curl_close($ch);
        return json_decode($result, true);
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
        return $this->getCurrencyCode($this->baseCurrencyId);
    }

    protected function setBaseCurrency()
    {
        $this->baseCurrencyCode = $this->getBaseCurrencyCode();
        if (empty($this->baseCurrencyCode)) {
            $message = Labels::getLabel('MSG_BASE_CURRENCY_NOT_INITIALIZED', $this->adminLangId);
            LibHelper::dieJsonError($message);
        }
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
        $currObj = new Currency();
        if (false === $currObj->updatePricingRates($currenciesData)) {
            LibHelper::dieJsonError($currObj->getError());
        }
        FatUtility::dieJsonSuccess(Labels::getLabel('MSG_Updated_Successfully', $this->adminLangId));
    }
}
