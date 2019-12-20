<?php
class FcmPushNotification extends PushNotificationBase
{
    private const PRODUCTION_URL = 'https://fcm.googleapis.com/fcm/send';
    public const KEY_NAME = 'FcmPushNotification';
    public const LIMIT = 1000;

    private function validateSettings()
    {
        $settings = $this->getSettings();
        if (!isset($settings['server_api_key'])) {
            $this->error = Labels::getLabel('MSG_SETTINGS_NOT_UPDATED', CommonHelper::getLangId());
            return false;
        }
        $this->serverApiKey = $settings['server_api_key'];
    }
    
    public function notify($deviceTokens, $title, $message)
    {
        if (!is_array($deviceTokens) || empty($deviceTokens) || 1000 < count($deviceTokens)) {
            $this->error = Labels::getLabel('LBL_ARRAY_MUST_CONTAIN_AT_LEAST_1_AND_AT_MOST_1000_REGISTRATION_TOKENS', CommonHelper::getLangId());
            return false;
        }

        if (empty($title) || empty($message)) {
            $this->error = Labels::getLabel('LBL_INVALID_REQUEST_PARAMETERS', CommonHelper::getLangId());
            return false;
        }

        $msg = [
            'title' => $title,
            'message' => $message
        ];
        $fields = [
            'registration_ids' => $deviceTokens,
            'data' => $msg
        ];

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
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
