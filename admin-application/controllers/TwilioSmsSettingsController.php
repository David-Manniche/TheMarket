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
            'phone' => [
                'type' => 'string',
                'required' => true,
                'label' => "From Phone Number",
            ]
        ];
    }
}
