<?php
class CurrencyApi
{
    private $className;

    public function __construct()
    {
        if (!$this->className = static::getDefaultCurrencyApiClass()) {
            $this->error = Labels::getLabel('MSG_DEFAULT_CURRENCY_CONVERTER_NOT_DEFINED', CommonHelper::getLangId());
        }
    }

    public static function getDefaultCurrencyApiClass()
    {
        $defaultCurrConvAPI = FatApp::getConfig('CONF_DEFAULT_CURRENCY_CONVERTER_API', FatUtility::VAR_INT, 0);
        if (empty($defaultCurrConvAPI)) {
            return false;
        }
        
        return Plugin::getAttributesById($defaultCurrConvAPI, 'plugin_code');
    }
    
    public function getError()
    {
        return $this->error;
    }

    public function getConversionRate($toCurrencies = [])
    {
        $baseCurrencyCode = Currency::getDefaultCurrencyCode();
        if (!$baseCurrencyCode) {
            trigger_error("Invalid Base Currency", E_USER_ERROR);
        }

        $toCurrencies = !is_array($toCurrencies) ? [] : $toCurrencies;
        try {
            $obj = new $this->className($baseCurrencyCode);
            $data = $obj->getConversionRate($toCurrencies);
            if (!$data) {
                throw new Exception($obj->getError());
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
        
        return $data;
    }
}
