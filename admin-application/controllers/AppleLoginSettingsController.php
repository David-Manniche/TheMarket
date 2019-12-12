<?php
class AppleLoginSettingsController extends SocialLoginSettingsController
{
    public const KEY_NAME = 'AppleLogin';
    public static function getConfigurationKeys()
    {
        return [
                static::KEY_NAME . '_client_id' => [
                    'type' => 'string',
                    'required' => true,
                    'label' => "Client ID / Service ID",
                ]
            ];
    }
}
