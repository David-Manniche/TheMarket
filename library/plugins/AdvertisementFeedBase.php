<?php
class AdvertisementFeedBase
{
    public function getSettings($column = '')
    {
        $class = get_called_class();
        if (defined($class . '::KEY_NAME')) {
            return PluginSetting::getConfDataByCode($class::KEY_NAME, $column, CommonHelper::getLangId());
        }
        return PluginSetting::getConfDataByCode($class, $column, CommonHelper::getLangId());
    }

    public function getError()
    {
        return $this->error;
    }

    protected function getUserMeta($key = '')
    {
        return User::getUserMeta(UserAuthentication::getLoggedUserId(), $key);
    }
}
