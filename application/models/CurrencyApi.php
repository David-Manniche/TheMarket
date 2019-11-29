<?php
class CurrencyApi
{
    private $baseCurrency;
    private $addonClassObj;

    public function __construct($baseCurrency = 0)
    {
        $baseCurrency = Currency::getDefault($baseCurrency);
        if (empty($baseCurrency)) {
            trigger_error("Invalid Base Currency", E_USER_ERROR);
        }
        $this->baseCurrency = strtoupper($baseCurrency['currency_code']);

        $defaultCurrConvAPI = FatApp::getConfig('CONF_DEFAULT_CURRENCY_CONVERTER_API', FatUtility::VAR_INT, 0);
        if (empty($defaultCurrConvAPI)) {
            $this->error = Labels::getLabel('MSG_CURRENCY_CONVERTER_NOT_DEFINED', CommonHelper::getLangId());
            return false;
        }
        
        $className = Addon::getAttributesById($defaultCurrConvAPI, 'addon_code');
        $this->addonClassObj = new $className($this->baseCurrency);
    }

    private function getData($functionName, $extraParam = [])
    {
        try {
            $data = $this->addonClassObj->$functionName($extraParam);
            if (!$data) {
                throw new Exception($this->addonClassObj->getError());
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
        
        return $data;
    }
    
    public function getError()
    {
        return $this->error;
    }

    public function getConversionRate($toCurrencies = [])
    {
        $toCurrencies = !is_array($toCurrencies) ? [] : $toCurrencies;
        return $this->getData(__FUNCTION__, $toCurrencies);
    }

    public function getAllCurrencies()
    {
        return $this->getData(__FUNCTION__);
    }
}
