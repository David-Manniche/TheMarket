<?php
class SellerPluginBaseController extends SellerBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function getSettings($langId = 0, $column = '')
    {
        try {
            $keyName = get_called_class()::KEY_NAME;
        } catch (\Error $e) {
            $message = $e->getMessage();
            if (true ===  MOBILE_APP_API_CALL) {
                LibHelper::dieJsonError($message);
            }
            Message::addErrorMessage($message);
            CommonHelper::redirectUserReferer();
        }
        return PluginSetting::getConfDataByCode($keyName, $column, $langId);
    }
}
