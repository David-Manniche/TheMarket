<?php
class SmsNotificationController extends PluginBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function callback($keyName)
    {
        require_once CONF_PLUGIN_DIR . '/sms-notification/' . strtolower($keyName) . '/' . $keyName . '.php';
        $smsNotification = new $keyName($this->siteLangId);
        $smsNotification->callback();
    }
}