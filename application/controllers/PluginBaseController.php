<?php
class PluginBaseController extends MyAppController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    public static function getSettings($keyName)
    {
        return PluginSetting::getConfDataByCode($keyName);
    }
}
