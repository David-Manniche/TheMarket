<?php

class StripeConnectSettingsController extends PaymentMethodSettingsController
{
    public static function getConfigurationKeys()
    {
        return [
            'env' => [
                'type' => PluginSetting::TYPE_BOOL,
                'required' => true,
                'label' => "Enable Live Mode",
            ],
            'client_id' => [
                'type' => PluginSetting::TYPE_STRING,
                'required' => true,
                'label' => "Client Id",
            ],
            'publishable_key' => [
                'type' => PluginSetting::TYPE_STRING,
                'required' => true,
                'label' => "Publishable Key",
            ],
            'secret_key' => [
                'type' => PluginSetting::TYPE_STRING,
                'required' => true,
                'label' => "Secret key",
            ],
            'live_client_id' => [
                'type' => PluginSetting::TYPE_STRING,
                'required' => true,
                'label' => "Live Client Id",
            ],
            'live_publishable_key' => [
                'type' => PluginSetting::TYPE_STRING,
                'required' => true,
                'label' => "Live Publishable Key",
            ],
            'live_secret_key' => [
                'type' => PluginSetting::TYPE_STRING,
                'required' => true,
                'label' => "Live Secret key",
            ],
        ];
    }
}
