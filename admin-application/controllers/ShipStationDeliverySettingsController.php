<?php

class ShipStationDeliverySettingsController extends ShippingModuleSettingsController
{
    public static function getConfigurationKeys()
    {
        return [
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
