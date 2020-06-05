<?php

class ShipStationShippingSettingsController extends ShippingServicesSettingsController
{
    public static function getConfigurationKeys()
    {
        return [
            'test_label' => [
                'type' => PluginSetting::TYPE_BOOL,
                'required' => true,
                'label' => "Test label",
            ],
            'api_key' => [
                'type' => PluginSetting::TYPE_STRING,
                'required' => true,
                'label' => "API Key",
            ],
            'api_secret_key' => [
                'type' => PluginSetting::TYPE_STRING,
                'required' => true,
                'label' => "API Secret Key",
            ]
        ];
    }
}
