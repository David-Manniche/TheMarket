<?php

trait PluginHelper
{
    public $error;

    public function getSettings($langId = 0, $column = '')
    {
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
        $pluginSetting = new PluginSetting();
        return $pluginSetting->getConfDataByCode($keyName, $column, $langId);
    }

    public function getError()
    {
        return $this->error;
    }
}
