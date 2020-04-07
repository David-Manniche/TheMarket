<?php

trait PluginHelper
{
    public $error;
    public $settings = [];
    public $langId = 0;
    public $keyName;

    public function getError()
    {
        return $this->error;
    }

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
    
    protected function validateSettings()
    {
        $this->settings = $this->getSettings();
        if (isset($this->requiredKeys) && !empty($this->requiredKeys) && is_array($this->requiredKeys)) {
            foreach ($this->requiredKeys as $key) {
                if (!array_key_exists($key, $this->settings)) {
                    $this->error = Labels::getLabel('MSG_PLUGIN_SETTINGS_NOT_CONFIGURED', $this->langId);
                    return false;
                }
            }
        }
        return true;
    }

    /**
    * This function is used for kingPin plugins only.
     */
    public static function includePlugin(int $type, string $directory, int $langId = 0, &$error = '')
    {
        if (1 > $langId) {
            $langId = CommonHelper::getLangId();
        }

        if (empty($type) || 1 > $type || empty($directory)) {
            $error = Labels::getLabel('MSG_INVALID_REQUEST', $langId);
            return false;
        }

        $pluginsTypeArr = Plugin::getTypeArr($langId);
        if (!isset($pluginsTypeArr[$type])) {
            $error = Labels::getLabel('MSG_INVALID_PLUGIN_TYPE.', $langId);
            return false;
        }

        $defaultPushNotiAPI = FatApp::getConfig('CONF_DEFAULT_PLUGIN_' . $type, FatUtility::VAR_INT, 0);
        if (empty($defaultPushNotiAPI)) {
            $msg =  Labels::getLabel('MSG_NO_DEFAULT_PLUGIN_SET_FOR_THIS_TYPE_{TYPE}.', $langId);
            $error = CommonHelper::replaceStringData($msg, ['{TYPE}' => $pluginsTypeArr[$type]]);
            return false;
        }

        $keyName = Plugin::getAttributesById($defaultPushNotiAPI, 'plugin_code');
        if (1 > Plugin::isActive($keyName)) {
            $error =  Labels::getLabel('MSG_PLUGIN_IS_NOT_ACTIVE', $langId);
            return false;
        }
        $file = CONF_PLUGIN_DIR . '/' . $directory . '/' . strtolower($keyName) . '/' . $keyName . '.php';

        if (!file_exists($file)) {
            $error =  Labels::getLabel('MSG_FILE_NOT_FOUND', $langId);
            return false;
        }
        
        require_once $file;
    }
}
