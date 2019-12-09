<?php
class Apple extends LoginPluginBase
{
    private const PRODUCTION_URL = 'https://appleid.apple.com/auth/';
    
    public static function getRequestUri()
    {
        $settings = static::getSettings();
        $redirectUri = CommonHelper::generateFullUrl('Apple', 'index', array(), '', false);
        $_SESSION['appleSignIn']['state'] = bin2hex(random_bytes(5));
        return static::PRODUCTION_URL . 'authorize?' . http_build_query([
            'response_type' => 'code id_token',
            'response_mode' => 'form_post',
            'client_id' => $settings['clientId'],
            'redirect_uri' => $redirectUri,
            'state' => $_SESSION['appleSignIn']['state'],
            'scope' => 'name email',
        ]);
    }

    public static function requirements($langId)
    {
        return [
                'clientId' => [
                    'type' => static::TYPE_STRING,
                    'required' => true,
                    'label' => Labels::getLabel('LBL_CLIENT_ID/SERVICE_ID', $langId),
                ]
            ];
    }
}
