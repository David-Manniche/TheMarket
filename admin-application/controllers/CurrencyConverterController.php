<?php
/*
    Reference : https://www.currencyconverterapi.com/
*/
class CurrencyConverterController extends CurrencyConverterBaseController
{
    public const KEY_NAME = 'CurrencyConverter';
    private const PRODUCTION_URL = 'https://free.currconv.com/api/v7/';

    private $apiKey;
    private $baseCurrencyCode;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->validateSettings();
        $this->setBaseCurrency();
    }

    private function validateSettings()
    {
        $settings = $this->getSettings();
        if (!isset($settings['api_key'])) {
            $message = Labels::getLabel('MSG_SETTINGS_NOT_UPDATED', $this->adminLangId);
            LibHelper::dieJsonError($message);
        }
        $this->apiKey = $settings['api_key'];
    }

    private function setBaseCurrency()
    {
        $this->baseCurrencyCode = $this->getBaseCurrencyCode();
        if (empty($this->baseCurrencyCode)) {
            $message = Labels::getLabel('MSG_BASE_CURRENCY_NOT_INITIALIZED', $this->adminLangId);
            LibHelper::dieJsonError($message);
        }
    }

    private function accessKey()
    {
        return '?apiKey=' . $this->apiKey;
    }

    public function getRates($toCurrencies = [])
    {
        $accessKey = $this->accessKey();

        $toCurrenciesQuery = '';
        if (is_array($toCurrencies) && !empty(array_filter($toCurrencies))) {
            foreach ($toCurrencies as $currencyCode) {
                $toCurrenciesQuery .= $this->baseCurrencyCode . '_' . $currencyCode . ',';
            }
        }
        
        $getConversionRatesUrl = static::PRODUCTION_URL . 'convert' . $accessKey . '&compact=ultra&q=' . rtrim($toCurrenciesQuery, ',');
        $response = $this->getExternalApiData($getConversionRatesUrl);
        $data = [];
        foreach ($response as $key => $rate) {
            $data[str_replace($this->baseCurrencyCode . '_', '', $key)] = $rate;
        }
        return $data;
    }
}
