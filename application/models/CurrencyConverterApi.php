<?php
/*
    Reference : https://www.currencyconverterapi.com/
*/
class CurrencyConverterApi extends CurrencyAddon
{
    private const PRODUCTION_URL = 'https://free.currconv.com/api/v7/';

    public function __construct($baseCurrencyCode = '')
    {
        $this->baseCurrencyCode = $baseCurrencyCode;
    }

    private function formatAccessKey($accessKey)
    {
        return '?apiKey=' . $accessKey;
    }

    private function accessKey($formatAccessKey = true)
    {
        $settings = static::getSettings();
        $accessKey = $settings['apiKey'];
        if (empty($accessKey)) {
            $this->error = Labels::getLabel('MSG_YOU_HAVE_NOT_ENTERED_A_VALID_API_KEY', CommonHelper::getLangId());
            return false;
        }
        if (true === $formatAccessKey) {
            return $this->formatAccessKey($accessKey);
        }
        return $accessKey;
    }

    public function getAllCurrencies()
    {
        $accessKey = $this->accessKey();
        if (!$accessKey) {
            return false;
        }
        
        $getAllCurrenciesUrl = static::PRODUCTION_URL . 'currencies' . $accessKey;
        $data = $this->getExternalApiData($getAllCurrenciesUrl);
        if (!empty($data['error'])) {
            $this->error = $data['error'];
            return false;
        }
        return $data['results'];
    }

    public function getConversionRate($toCurrencies = [])
    {
        $accessKey = $this->accessKey();
        if (!$accessKey) {
            return false;
        }

        if (empty($this->baseCurrencyCode)) {
            $this->error = Labels::getLabel('LBL_INVALID_BASE_CURRENCY', CommonHelper::getLangId());
            return false;
        }

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

    public static function getSettingsForm()
    {
        $frm = new Form('frmAddons');
        $frm->addHiddenField('', 'keyName', __CLASS__);
        $frm->addHiddenField('', 'addon_id');
        $frm->addRequiredField(Labels::getLabel('LBL_API_KEY', CommonHelper::getLangId()), 'apiKey');
        $frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Save_Changes', CommonHelper::getLangId()));
        return $frm;
    }
}
