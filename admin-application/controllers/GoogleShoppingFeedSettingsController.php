<?php
class GoogleShoppingFeedSettingsController extends AdvertisementFeedSettingsController
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
                ],
                'developer_key' => [
                    'type' => 'string',
                    'required' => true,
                    'label' => "Developer Key",
                ],
            ];
    }
}
