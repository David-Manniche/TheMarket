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
        $this->setBaseCurrency();
    }

    private function validateSettings()
    {
        $settings = $this->getSettings();
        if (!isset($settings['access_key'])) {
            $message = Labels::getLabel('MSG_SETTINGS_NOT_UPDATED', $this->adminLangId);
            LibHelper::dieJsonError($message);
        }
        $this->accessKey = $settings['access_key'];
    }

    private function accessKey()
    {
        return '?access_key=' . $this->accessKey;
    }

    public function getRates($toCurrencies = [])
    {
        $accessKey = $this->accessKey();

        $toCurrenciesQuery = '';
        if (is_array($toCurrencies) && !empty(array_filter($toCurrencies))) {
            $toCurrenciesQuery = '&symbols=' . implode(',', $toCurrencies);
        }
        
        $getConversionRates = static::PRODUCTION_URL . 'latest' . $accessKey . '&base=' . $this->baseCurrencyCode . $toCurrenciesQuery;
        $data = $this->getExternalApiData($getConversionRates);
        if (false === $data['success'] && !empty($data['error'])) {
            $message = 'Err - ' . $data['error']['code'] . ' - ' . $data['error']['type'];
            LibHelper::dieJsonError($message);
        }
        return $data['rates'];
    }
}
