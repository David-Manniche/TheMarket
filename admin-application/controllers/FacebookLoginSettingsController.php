<?php
class FacebookLoginSettingsController extends SocialLoginSettingsController
{
    public const KEY_NAME = 'FacebookLogin';
    public static function getConfigurationKeys()
    {
        return [
                static::KEY_NAME . '_app_id' => [
                    'type' => 'string',
                    'required' => true,
                    'label' => "App Id",
                ],
                static::KEY_NAME . '_app_secret' => [
                    'type' => 'string',
                    'required' => true,
                    'label' => "App Secret",
                ],
            ];
    }
}
