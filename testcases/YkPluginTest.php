<?php
class YkPluginTest extends YkAppTest
{
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
            FatUtility::dieJsonError($error);
        }
    }
}
