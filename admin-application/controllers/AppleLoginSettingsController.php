<?php
class AppleLoginSettingsController extends SocialLoginSettingsController
{
    public static function getConfigurationKeys()
    {
        return [
                'client_id' => [
                    'type' => 'string',
                    'required' => true,
                    'label' => "Client ID / Service ID",
                ]
            ];
    }
}
