<?php
class ElasticSearchSettingsController extends FullTextSearchSettingsController
{
    public static function getConfigurationKeys()
    {
        return [
                'host' => [
                    'type' => 'string',
                    'required' => true,
                    'label' => "Elastic Search host Url",
                ],
                'username' => [
                    'type' => 'string',
                    'required' => false,
                    'label' => "Elastic Search username",
                ],
                'password' => [
                    'type' => 'string',
                    'required' => false,
                    'label' => "Elastic Search password",
                ]
            ];
    }
}
