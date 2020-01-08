<?php
class FcmPushNotificationSettingsController extends PushNotificationSettingsController
{
    public static function getConfigurationKeys()
    {
        return [
                'server_api_key' => [
                    'type' => 'string',
                    'required' => true,
                    'label' => "FCM Server API Key",
                ]
            ];
    }
}
