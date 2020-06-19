<?php
/*
    Reference : https://www.currencyconverterapi.com
*/
class CurrencyConverterController extends CurrencyConverterBaseController
{
    public const KEY_NAME = 'CurrencyConverter';
    private const PRODUCTION_URL = 'https://free.currconv.com/api/v7/';

    public $requiredKeys = ['api_key'];

    public function __construct($action)
    {
        parent::__construct($action);
        if (false == $this->validateSettings($this->adminLangId)) {
            FatUtility::dieJsonError($this->error);
        }
    }
    
    private function apiKey()
    {
        return '?apiKey=' . $this->settings['api_key'];
    }

    private function getData($apiUrl)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $result = curl_exec($ch);
    
        curl_close($ch);
        return json_decode($result, true);
    }

    public function getRates($toCurrencies = [])
    {
        $apiKey = $this->apiKey();
        $baseCurrencyCode = $this->getBaseCurrencyCode();

        $toCurrenciesQuery = '';
        if (is_array($toCurrencies) && !empty(array_filter($toCurrencies))) {
            foreach ($toCurrencies as $currencyCode) {
                $toCurrenciesQuery .= $baseCurrencyCode . '_' . $currencyCode . ',';
            }
        }
        
        $getConversionRatesUrl = static::PRODUCTION_URL . 'convert' . $apiKey . '&compact=ultra&q=' . rtrim($toCurrenciesQuery, ',');
        $response = $this->getData($getConversionRatesUrl);
        $data = [];
        foreach ($response as $key => $rate) {
            $data[str_replace($baseCurrencyCode . '_', '', $key)] = $rate;
        }
        return [
            'status' => true,
            'data' => $data
        ];
    }
}
