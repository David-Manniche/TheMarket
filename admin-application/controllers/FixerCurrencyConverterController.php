<?php
/*
    Reference : https://data.fixer.io
*/
class FixerCurrencyConverterController extends CurrencyConverterBaseController
{
    public const KEY_NAME = 'FixerCurrencyConverter';
    private const PRODUCTION_URL = 'http://data.fixer.io/api/';

    public $requiredKeys = ['access_key'];

    public function __construct($action)
    {
        parent::__construct($action);
        if (false == $this->validateSettings($this->adminLangId)) {
            FatUtility::dieJsonError($this->error);
        }
    }

    private function accessKey()
    {
        return '?access_key=' . $this->settings['access_key'];
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
