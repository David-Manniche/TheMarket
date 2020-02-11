<?php

class PayPalPayoutSettingsController extends PayoutSettingsController
{
    public static function getConfigurationKeys()
    {
        return [
                'client_id' => [
                    'type' => 'string',
                    'required' => true,
                    'label' => "Client ID",
                ],
                'client_secret' => [
                    'type' => 'string',
                    'required' => true,
                    'label' => "Client Secret",
                ]
            ];
    }
}
