<?php
class AppleSettingController extends PluginSettingController
{
    public static function requirements()
    {
        return [
                'client_id' => [
                    'type' => static::TYPE_STRING,
                    'required' => true,
                    'label' => "Client ID/Service ID",
                ]
            ];
    }
}
