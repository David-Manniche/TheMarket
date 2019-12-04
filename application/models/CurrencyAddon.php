<?php
class CurrencyAddon extends AddonSetting
{
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
