<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class PluginBaseTest extends TestCase
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

        $error = '';
        if (false === PluginHelper::includePlugin($keyName, $directory, $error, $langId)) {
            FatUtility::dieJsonError($error);
        }
    }
}
