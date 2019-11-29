<?php
/* 
    Reference : https://www.currencyconverterapi.com/
*/
class CurrencyConverterApi
{
    private const HOST = 'https://api.currconv.com/api/v7/';
    
    public function __construct($baseCurrency = '')
    {
        $this->baseCurrency = $baseCurrency;
    }

    private function accessKey()
    {
        $obj = new AddonSetting(get_class($this));
        $settings = $obj->get();
        $accessKey = $settings['apiKey'];
        if (empty($accessKey)) {
            $this->error = Labels::getLabel('MSG_YOU_HAVE_NOT_ENTERED_A_VALID_API_KEY', CommonHelper::getLangId());
            return false;
        }
        return '?apiKey=' . $accessKey;
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
        
        $getAllCurrenciesUrl = static::HOST . 'currencies' . $accessKey;
        $data = $this->getData($getAllCurrenciesUrl);
        return $data;
    }

    public function getConversionRate($toCurrencies = [])
    {
        $accessKey = $this->accessKey();
        if (!$accessKey) {
            return false;
        }
        if (empty($this->baseCurrency)) {
            $this->error = Labels::getLabel('LBL_INVALID_BASE_CURRENCY', CommonHelper::getLangId());
            return false;
        }

        $toCurrenciesQuery = '';
        if (is_array($toCurrencies) && !empty(array_filter($toCurrencies))) {
            $toCurrenciesQuery = '&symbols=' . implode(',', $toCurrencies);
        }
        
        $getConversionRates = static::HOST . 'convert' . $accessKey . '&compact=ultra&q=';
        // $data = $this->getData($getConversionRates);
        return [];
    }

    public function getSettingsForm()
    {
        $frm = new Form('frmAddons');
        $frm->addHiddenField('', 'keyName', get_class($this));
        $frm->addHiddenField('', 'addon_id');
        $frm->addRequiredField(Labels::getLabel('LBL_API_KEY', CommonHelper::getLangId()), 'apiKey');
        $frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Save_Changes', CommonHelper::getLangId()));
        return $frm;
    }

    public function getError()
    {
        return $this->error;
    }
}
