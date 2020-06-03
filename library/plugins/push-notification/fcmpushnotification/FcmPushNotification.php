<?php
class FcmPushNotification extends PushNotificationBase
{
    public const KEY_NAME = __CLASS__;
    private const PRODUCTION_URL = 'https://fcm.googleapis.com/fcm/send';
    public const LIMIT = 1000;

    private $deviceTokens;
    public $requiredKeys = [
        'server_api_key'
    ];

    public function __construct($deviceTokens)
    {
        $this->deviceTokens = $deviceTokens;
    }

    private function init()
    {
        if (false == $this->validateSettings(CommonHelper::getLangId())) {
            return false;
        }
        
        if (!is_array($this->deviceTokens) || empty($this->deviceTokens) || 1000 < count($this->deviceTokens)) {
            $this->error = Labels::getLabel('LBL_ARRAY_MUST_CONTAIN_AT_LEAST_1_AND_AT_MOST_1000_REGISTRATION_TOKENS', CommonHelper::getLangId());
            return false;
        }
    }

    public function notify($title, $message, $os, $data = [])
    {
        if ($this->init()) {
            return false;
        }
            
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
            'Authorization: key=' . $this->settings['server_api_key'],
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
