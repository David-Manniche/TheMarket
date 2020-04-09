<?php
/*
    Reference : https://www.currencyconverterapi.com
*/
class CurrencyConverterController extends CurrencyConverterBaseController
{
    public const KEY_NAME = 'CurrencyConverter';
    private const PRODUCTION_URL = 'https://free.currconv.com/api/v7/';

    private $apiKey;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->validateSettings();
    }

    private function validateSettings()
    {
        $settings = $this->getSettings();
        if (!isset($settings['api_key'])) {
            $message = Labels::getLabel('MSG_PLUGIN_SETTINGS_NOT_CONFIGURED', $this->adminLangId);
            LibHelper::dieJsonError($message);
        }
        $this->apiKey = $settings['api_key'];
    }

    private function accessKey()
    {
        return '?apiKey=' . $this->apiKey;
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
        $accessKey = $this->accessKey();
        $baseCurrencyCode = $this->getBaseCurrencyCode();

        $toCurrenciesQuery = '';
        if (is_array($toCurrencies) && !empty(array_filter($toCurrencies))) {
            foreach ($toCurrencies as $currencyCode) {
                $toCurrenciesQuery .= $baseCurrencyCode . '_' . $currencyCode . ',';
            }
        }
        
        $getConversionRatesUrl = static::PRODUCTION_URL . 'convert' . $accessKey . '&compact=ultra&q=' . rtrim($toCurrenciesQuery, ',');
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
