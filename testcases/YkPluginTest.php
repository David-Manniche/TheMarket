<?php
class YkPluginTest extends YkAppTest
{
    protected $classObj = '';
    protected $error = '';

    /**
     * init
     *
     * @return bool
     */
    protected function init(): bool
    {
        $class = static::KEY_NAME;
        $this->langId = CommonHelper::getLangId();
        $this->classObj = new $class($this->langId);

        $this->classObj = PluginHelper::callPlugin($class, [$this->langId], $this->error, $this->langId);
        if (false === $this->classObj) {
            return false;
        }

        if (method_exists($this->classObj, 'init') && false === $this->classObj->init()) {
            $this->error = $this->classObj->getError();
            return false;
        }
        return true;
    }

    /**
     * setupBeforeClass - This will treat as constructor.
     *
     * @return void
     */
    public static function setupBeforeClass(): void
    {
        $class = get_called_class();
        $keyName = $class::KEY_NAME;
        $pluginType = Plugin::getAttributesByCode($keyName, 'plugin_type');
        $directory = Plugin::getDirectory($pluginType);
        $langId = CommonHelper::getLangId();

        if (false === PluginHelper::includePlugin($keyName, $directory, $error, $langId, false)) {
            // FatUtility::dieJsonError($error);
        }
    }
}
