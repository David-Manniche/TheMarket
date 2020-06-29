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
     * @return array
     */
    public function getSettings(string $column = '')
    {
        if (!empty($this->settings)) {
            return $this->settings;
        }

        $this->langId = 0 < $this->langId ? $this->langId : CommonHelper::getLangId();

        try {
            $this->keyName = get_called_class()::KEY_NAME;
        } catch (\Error $e) {
            $this->error = $e->getMessage();
            return false;
        }
        $pluginSetting = new PluginSetting(0, $this->keyName);
        return $this->settings = $pluginSetting->get($this->langId, $column);
    }
    
    /**
     * validateSettings - To validate plugin required keys are updated in db or not.
     *
     * @param  int $langId
     * @return bool
     */
    protected function validateSettings(int $langId = 0): bool
    {
        $this->langId = 0 < $langId ? $langId : CommonHelper::getLangId();
        $this->settings = $this->getSettings();
        if (isset($this->requiredKeys) && !empty($this->requiredKeys) && is_array($this->requiredKeys)) {
            foreach ($this->requiredKeys as $key) {
                if (!array_key_exists($key, $this->settings) || '' == $this->settings[$key]) {
                    $this->error = $this->keyName . ' : ' . ' "' . $key . '" ' . Labels::getLabel('MSG_SETTINGS_NOT_CONFIGURED', $langId);
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
     * @param  string $error
     * @param  int $langId
     * @return mixed
     */
    public static function includePlugin(string $keyName, string $directory, &$error = '', int $langId = 0, bool $checkActive = true)
    {
        if (1 > $langId) {
            $langId = CommonHelper::getLangId();
        }

        if (empty($directory)) {
            $error = Labels::getLabel('MSG_INVALID_REQUEST', $langId);
            return false;
        }

        if (true === $checkActive && 1 > Plugin::isActive($keyName)) {
            $error =  Labels::getLabel('MSG_PLUGIN_IS_NOT_ACTIVE', $langId);
            return false;
        }
        
        $file = CONF_PLUGIN_DIR . $directory . '/' . strtolower($keyName) . '/' . $keyName . '.php';
        
        if (!file_exists($file)) {
            $error =  Labels::getLabel('MSG_UNABLE_TO_LOCATE_REQUIRED_FILE', $langId);
            return false;
        }
        
        try {
            require_once $file;
        } catch (\Error $e) {
            $error = $e->getMessage();
            return false;
        }
    }

    /**
     * callPlugin - Used to call plugin file without including plugin. This function is used for files exists in library\plugins.
     *
     * @param string $keyname - ClassName
     * @param string $args - Constructor Arguments
     * @param string $error
     * @param int $langId
     * @return mixed
     */
    public static function callPlugin(string $keyName, array $args = [], &$error = '', int $langId = 0, bool $checkActive = true)
    {
        if (1 > $langId) {
            $langId = CommonHelper::getLangId();
        }
        
        if (empty($keyName)) {
            $error =  Labels::getLabel('MSG_INVALID_KEY_NAME', $langId);
            return false;
        }

        $pluginType = Plugin::getAttributesByCode($keyName, 'plugin_type');

        $directory = Plugin::getDirectory($pluginType);

        if (false == $directory) {
            $error =  Labels::getLabel('MSG_INVALID_PLUGIN_TYPE', $langId);
            return false;
        }
        
        $error = '';
        if (false === PluginHelper::includePlugin($keyName, $directory, $error, $langId, $checkActive)) {
            return false;
        }

        $reflect  = new ReflectionClass($keyName);
        return $reflect->newInstanceArgs($args);
    }
}
