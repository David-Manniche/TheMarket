<?php
class PluginBaseController extends MyAppController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function getSettings()
    {
        try {
            $keyName = get_called_class()::KEY_NAME;
        } catch (\Error $e) {
            $message = 'ERR - ' . $e->getMessage();
            if (true ===  MOBILE_APP_API_CALL) {
                LibHelper::dieJsonError($message);
            }
            Message::addErrorMessage($message);
            CommonHelper::redirectUserReferer();
        }
        return PluginSetting::getConfDataByCode($keyName);
    }
}
