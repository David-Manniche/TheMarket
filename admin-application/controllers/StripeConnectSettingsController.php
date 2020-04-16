<?php

class StripeConnectSettingsController extends PaymentMethodSettingsController
{
    public static function getConfigurationKeys()
    {
        return [
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
