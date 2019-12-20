<?php
class CurrencyConverterSettingsController extends CurrencyApiSettingsController
{
    public static function getConfigurationKeys()
    {
        return [
                'api_key' => [
                    'type' => 'string',
                    'required' => true,
                    'label' => "Api Key",
                ]
            ];
    }
}
