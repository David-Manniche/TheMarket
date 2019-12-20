<?php
class FacebookLoginSettingsController extends SocialLoginSettingsController
{
    public static function getConfigurationKeys()
    {
        return [
                'app_id' => [
                    'type' => 'string',
                    'required' => true,
                    'label' => "App Id",
                ],
                'app_secret' => [
                    'type' => 'string',
                    'required' => true,
                    'label' => "App Secret",
                ],
            ];
    }
}
