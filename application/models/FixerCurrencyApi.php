<?php
class FixerCurrencyApi extends CurrencyAddon
{
    private const PRODUCTION_URL = 'http://data.fixer.io/api/';
    
    public function __construct($baseCurrencyCode = '')
    {
        $this->baseCurrencyCode = $baseCurrencyCode;
    }

    private function formatAccessKey($accessKey)
    {
        return '?access_key=' . $accessKey;
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
        
        $getAllCurrenciesUrl = static::PRODUCTION_URL . 'symbols' . $accessKey;
        $data = $this->getExternalApiData($getAllCurrenciesUrl);
        if (false === $data['success'] && !empty($data['error'])) {
            $this->error = 'Error : ' . $data['error']['code'] . '-' . $data['error']['type'];
            return false;
        }
        return $data['symbols'];
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
            $toCurrenciesQuery = '&symbols=' . implode(',', $toCurrencies);
        }
        
        $getConversionRates = static::PRODUCTION_URL . 'latest' . $accessKey . '&base=' . $this->baseCurrencyCode . $toCurrenciesQuery;
        $data = $this->getExternalApiData($getConversionRates);
        if (false === $data['success'] && !empty($data['error'])) {
            $this->error = 'Error : ' . $data['error']['code'] . ' - ' . $data['error']['type'];
            return false;
        }
        return $data['rates'];
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
