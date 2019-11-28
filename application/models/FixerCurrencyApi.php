<?php
class FixerCurrencyApi
{
    private const HOST = 'http://data.fixer.io/api/';

    private $baseCurrency;

    public function __construct($baseCurrency)
    {
        $this->baseCurrency = $baseCurrency;
    }

    private function accessKey()
    {
        $obj = new AddonSettings(get_class($this));
        $settings = $obj->getSettings();
        $accessKey = $settings['apiKey'];
        if (empty($accessKey)) {
            $this->error = Labels::getLabel('MSG_YOU_HAVE_NOT_ENTERED_A_VALID_SUBSCRIPTION_KEY', CommonHelper::getLangId());
            return false;
        }
        return '?access_key=' . $accessKey;
    }

    private function getData($apiUrl)
    {
        if (empty($apiUrl)) {
            $this->error = Labels::getLabel('MSG_INVALID_REQUEST_URL', CommonHelper::getLangId());
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $result = curl_exec($ch);
    
        curl_close($ch);
        return json_decode($result, true);
    }

    public function getAllCurrencies()
    {
        $accessKey = $this->accessKey();
        if (!$accessKey) {
            return false;
        }
        
        $getAllCurrenciesUrl = static::HOST . 'symbols' . $accessKey;
        $data = $this->getData($getAllCurrenciesUrl);
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

        $toCurrenciesQuery = '';
        if (is_array($toCurrencies) && !empty(array_filter($toCurrencies))) {
            $toCurrenciesQuery = '&symbols=' . implode(',', $toCurrencies);
        }
        
        $getConversionRates = static::HOST . 'latest' . $accessKey . '&base=' . $this->baseCurrency . $toCurrenciesQuery;
        $data = $this->getData($getConversionRates);
        if (false === $data['success'] && !empty($data['error'])) {
            $this->error = 'Error : ' . $data['error']['code'] . ' - ' . $data['error']['type'];
            return false;
        }
        return $data['rates'];
    }

    public function getSettingsForm()
    {
        $frm = new Form('frmAddons');
        $frm->addHiddenField('', 'keyName', get_class($this));
        $frm->addRequiredField(Labels::getLabel('LBL_API_KEY', CommonHelper::getLangId()), 'apiKey');
        $frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Save_Changes', CommonHelper::getLangId()));
        return $frm;
    }

    public function getError()
    {
        return $this->error;
    }
}
