<?php
class CurrencyAddon extends AddonSetting
{
    public function __construct()
    {
        parent::__construct(get_called_class());
    }
    
    protected function getExternalApiData($apiUrl)
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
}
