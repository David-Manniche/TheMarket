<?php

class StripeConnectSettingsController extends PaymentMethodSettingsController
{
    public static function getConfigurationKeys()
    {
        return [
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
        ];
    }
}
