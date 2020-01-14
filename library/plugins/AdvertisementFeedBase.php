<?php
class AdvertisementFeedBase
{
    protected $envoirment;

    public function __construct()
    {
        $this->envoirment = FatApp::getConfig('CONF_TRANSACTION_MODE', FatUtility::VAR_BOOLEAN, false);
    }
    
    public function getSettings($column = '')
    {
        $class = $keyName = get_called_class();
        if (defined($class . '::KEY_NAME')) {
            $keyName = $class::KEY_NAME;
        }
        return PluginSetting::getConfDataByCode($keyName, $column);
    }

    public function getError()
    {
        return $this->error;
    }

    protected function getUserAccountDetail($key = '')
    {
        return User::getUserMeta(UserAuthentication::getLoggedUserId(), $key);
    }
}
