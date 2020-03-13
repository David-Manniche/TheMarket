<?php

trait PluginHelper
{
    public $error;

    public function getSettings($column = '', $langId = 0)
    {
        $langId = FatUtility::int($langId);
        if (1 > $langId) {
            $langId = CommonHelper::getLangId();
        }

        try {
            $keyName = get_called_class()::KEY_NAME;
        } catch (\Error $e) {
            $message = $e->getMessage();
            if (true === MOBILE_APP_API_CALL) {
                LibHelper::dieJsonError($message);
            }
            Message::addErrorMessage($message);
            CommonHelper::redirectUserReferer();
        }
        $pluginSetting = new PluginSetting(0, $keyName);
        return $pluginSetting->get($langId, $column);
    }
    
    public function getError()
    {
        return $this->error;
    }
}
