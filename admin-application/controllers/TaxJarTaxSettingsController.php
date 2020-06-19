<?php

class TaxJarTaxSettingsController extends TaxSettingsController
{
    public static function getConfigurationKeys()
    {
        return [
            'live_key' => [
                'type' => PluginSetting::TYPE_STRING,
                'required' => true,
                'label' => "Live Key/Token",
            ],
            'sandbox_key' => [
                'type' => PluginSetting::TYPE_STRING,
                'required' => false,
                'label' => "Sandbox Key/Token",
            ],
            'environment' => [
                'type' => PluginSetting::TYPE_BOOL,
                'required' => true,
                'label' => "Production Mode",
            ]
        ];
    }
}
