<?php
/*
    Reference : https://data.fixer.io
*/
class FixerCurrencyConverterController extends CurrencyConverterBaseController
{
    public const KEY_NAME = 'FixerCurrencyConverter';
    private const PRODUCTION_URL = 'http://data.fixer.io/api/';

    private $accessKey;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->validateSettings();
    }

    private function validateSettings()
    {
        $settings = $this->getSettings();
        if (!isset($settings['access_key'])) {
            $message = Labels::getLabel('MSG_PLUGIN_SETTINGS_NOT_CONFIGURED', $this->adminLangId);
            LibHelper::dieJsonError($message);
        }
        $this->accessKey = $settings['access_key'];
    }

    private function accessKey()
    {
        return '?access_key=' . $this->accessKey;
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
            $toCurrenciesQuery = '&symbols=' . implode(',', $toCurrencies);
        }
        
        $getConversionRates = static::PRODUCTION_URL . 'latest' . $accessKey . '&base=' . $baseCurrencyCode . $toCurrenciesQuery;
        $data = $this->getData($getConversionRates);
        
        $status = true;
        $message = '';
        if (false === $data['success'] && !empty($data['error'])) {
            $status = false;
            $message = 'Error - ' . $data['error']['code'] . ' - ' . $data['error']['type'];
        }
        return [
            'status' => $status,
            'msg' => $message,
            'data' => isset($data['rates']) ? $data['rates'] : []
        ];
    }
}
