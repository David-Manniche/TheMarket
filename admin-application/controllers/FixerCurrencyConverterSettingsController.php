<?php

class FixerCurrencyConverterSettingsController extends CurrencyApiSettingsController
{
    public static function getConfigurationKeys()
    {
        return [
                'access_key' => [
                    'type' => 'string',
                    'required' => true,
                    'label' => "Access Key",
                ]
            ];
    }
}
