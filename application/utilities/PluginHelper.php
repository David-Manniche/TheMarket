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
    * This function is used for KingPin plugins only.
     */
    public static function includePlugin(string $keyName, string $directory, int $langId = 0, &$error = '')
    {
        if (1 > $langId) {
            $langId = CommonHelper::getLangId();
        }

        if (empty($directory)) {
            $error = Labels::getLabel('MSG_INVALID_REQUEST', $langId);
            return false;
        }

        if (1 > Plugin::isActive($keyName)) {
            $error =  Labels::getLabel('MSG_PLUGIN_IS_NOT_ACTIVE', $langId);
            return false;
        }
        $file = CONF_PLUGIN_DIR . '/' . $directory . '/' . strtolower($keyName) . '/' . $keyName . '.php';

        if (!file_exists($file)) {
            $error =  Labels::getLabel('MSG_UNABLE_TO_LOCATE_REQUIRED_FILE', $langId);
            return false;
        }
        
        require_once $file;
    }
}
