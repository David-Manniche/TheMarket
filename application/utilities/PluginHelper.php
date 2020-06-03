<?php

trait PluginHelper
{
    public $error;
    public $settings = [];
    public $langId = 0;
    public $keyName;
    
    /**
     * getError
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
    
    /**
     * getSettings
     *
     * @param  string $column
     * @param  int $langId
     * @return array
     */
    public function getSettings(string $column = '', int $langId = 0)
    {
        $langId = FatUtility::int($langId);
        if (1 > $langId) {
            $langId = CommonHelper::getLangId();
        }

        try {
            $this->keyName = get_called_class()::KEY_NAME;
        } catch (\Error $e) {
            $this->error = $e->getMessage();
            return false;
        }
        $pluginSetting = new PluginSetting(0, $this->keyName);
        return $pluginSetting->get($langId, $column);
    }
    
    /**
     * validateSettings - To validate plugin required keys are updated in db or not.
     *
     * @param  mixed $langId
     * @return bool
     */
    protected function validateSettings(int $langId)
    {
        $this->settings = $this->getSettings();
        if (isset($this->requiredKeys) && !empty($this->requiredKeys) && is_array($this->requiredKeys)) {
            foreach ($this->requiredKeys as $key) {
                if (!array_key_exists($key, $this->settings) || empty($this->settings[$key])) {
                    $this->error = $this->keyName . ' ' . Labels::getLabel('MSG_SETTINGS_NOT_CONFIGURED', $langId);
                    return false;
                }
            }
        }
        return true;
    }
    
    /**
     * includePlugin
     *
     * @param  string $keyName
     * @param  string $directory
     * @param  int $langId
     * @param  string $error
     * @return mixed
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
