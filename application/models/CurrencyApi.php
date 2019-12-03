<?php
class CurrencyApi
{
    public function __construct($baseCurrencyCode = '')
    {
        $this->baseCurrencyCode = strtoupper($baseCurrencyCode);
        if (empty($this->baseCurrencyCode)) {
            $baseCurrency = Currency::getDefault();
            if (empty($baseCurrency)) {
                trigger_error("Invalid Base Currency", E_USER_ERROR);
            }
            $this->baseCurrencyCode = strtoupper($baseCurrency['currency_code']);
        }
    }

    private function getData($functionName, $extraParam = [])
    {
        $defaultCurrConvAPI = FatApp::getConfig('CONF_DEFAULT_CURRENCY_CONVERTER_API', FatUtility::VAR_INT, 0);
        if (empty($defaultCurrConvAPI)) {
            $this->error = Labels::getLabel('MSG_DEFAULT_CURRENCY_CONVERTER_NOT_DEFINED', CommonHelper::getLangId());
            return false;
        }
        
        $className = Addon::getAttributesById($defaultCurrConvAPI, 'addon_code');
        
        try {
            $classObj = new $className($this->baseCurrencyCode);
            $data = $classObj->$functionName($extraParam);
            if (!$data) {
                throw new Exception($classObj->getError());
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
