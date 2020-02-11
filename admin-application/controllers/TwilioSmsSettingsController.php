<?php

class TwilioSmsSettingsController extends SmsNotificationSettingsController
{
    public static function getConfigurationKeys()
    {
        return [
            'account_sid' => [
                'type' => 'string',
                'required' => true,
                'label' => "Account Sid",
            ],
            'auth_token' => [
                'type' => 'string',
                'required' => true,
                'label' => "Auth Token",
            ],
            'sender_id' => [
                'type' => 'string',
                'required' => true,
                'label' => "Sender Id",
            ]
        ];
    }
}
