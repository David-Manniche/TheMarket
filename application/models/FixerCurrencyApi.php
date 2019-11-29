<?php
class FixerCurrencyApi extends CurrencyAddons
{
    private const PRODUCTION = 'http://data.fixer.io/api/';
    
    public function __construct($baseCurrencyCode = '')
    {
        parent::__construct($baseCurrencyCode);
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
        return '?access_key=' . $accessKey;
    }

    public function getAllCurrencies()
    {
        $accessKey = $this->accessKey();
        if (!$accessKey) {
            return false;
        }
        
        $getAllCurrenciesUrl = static::PRODUCTION . 'symbols' . $accessKey;
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
        if (empty($this->baseCurrencyCode)) {
            $this->error = Labels::getLabel('LBL_INVALID_BASE_CURRENCY', CommonHelper::getLangId());
            return false;
        }

        $toCurrenciesQuery = '';
        if (is_array($toCurrencies) && !empty(array_filter($toCurrencies))) {
            $toCurrenciesQuery = '&symbols=' . implode(',', $toCurrencies);
        }
        
        $getConversionRates = static::PRODUCTION . 'latest' . $accessKey . '&base=' . $this->baseCurrencyCode . $toCurrenciesQuery;
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
        $frm->addHiddenField('', 'addon_id');
        $frm->addRequiredField(Labels::getLabel('LBL_API_KEY', CommonHelper::getLangId()), 'apiKey');
        $frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Save_Changes', CommonHelper::getLangId()));
        return $frm;
    }
}
