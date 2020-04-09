<?php
class FcmPushNotification extends PushNotificationBase
{
    private const PRODUCTION_URL = 'https://fcm.googleapis.com/fcm/send';
    public const KEY_NAME = 'FcmPushNotification';
    public const LIMIT = 1000;

    private $deviceTokens;

    public function __construct($deviceTokens)
    {
        $this->validateSettings();
        
        if (!is_array($deviceTokens) || empty($deviceTokens) || 1000 < count($deviceTokens)) {
            $this->error = Labels::getLabel('LBL_ARRAY_MUST_CONTAIN_AT_LEAST_1_AND_AT_MOST_1000_REGISTRATION_TOKENS', CommonHelper::getLangId());
            return false;
        }

        $this->deviceTokens = $deviceTokens;
    }

    private function validateSettings()
    {
        $settings = $this->getSettings();
        if (!isset($settings['server_api_key'])) {
            $this->error = Labels::getLabel('MSG_PLUGIN_SETTINGS_NOT_CONFIGURED', CommonHelper::getLangId());
            return false;
        }
        $this->serverApiKey = $settings['server_api_key'];
    }
    
    public function notify($title, $message, $os, $data = [])
    {
        if (empty($title) || empty($message)) {
            $this->error = Labels::getLabel('LBL_INVALID_REQUEST', CommonHelper::getLangId());
            return false;
        }

        $msg = [
            'title' => $title,
            'body' => $message,
            'image' => isset($data['image']) ? $data['image'] : ''
        ];
        
        $fields = [
            'registration_ids' => $this->deviceTokens,
            'notification' => $msg,
            'data' => $data['customData'],
            'priority' => 'high'
        ];

        if (User::DEVICE_OS_ANDROID == $os) {
            unset($fields['notification']);
            $fields['data'] = array_merge($msg, $fields['data']);
        }
        
        $headers = [
            'Authorization: key=' . $this->serverApiKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, static::PRODUCTION_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response, true);
        return [
            'success' => isset($result['success']) ? $result['success'] : 0,
            'failure' => isset($result['failure']) ? $result['failure'] : 0,
            'data' => $response
        ];
    }
}
