<?php

require_once CONF_INSTALLATION_PATH . 'library/Twilio/vendor/autoload.php';
use Twilio\Rest\Client;

class TwilioSms extends SmsNotificationBase
{
    public const KEY_NAME = 'TwilioSms';
    private $settings = [];
    private $langId = 0;
    

    public function __construct($langId)
    {
        $this->langId = FatUtility::int($langId);
        if (1 > $this->langId) {
            $this->langId = CommonHelper::getLangId();
        }
        $this->validateSettings();
    }
    
    private function validateSettings()
    {
        $this->settings = $this->getSettings();
        $requiredKeyArr = ['account_sid', 'auth_token', 'sender_id'];
        foreach ($requiredKeyArr as $key) {
            if (!array_key_exists($key, $this->settings)) {
                $this->error = Labels::getLabel('MSG_PLUGIN_SETTINGS_NOT_CONFIGURED', $this->langId);
                return false;
            }
        }
    }
    
    public function send($to, $body)
    {
        if (empty($to) || empty($body)) {
            return [
                'status' => false,
                'msg' => Labels::getLabel('LBL_INVALID_REQUEST', $this->langId)
            ];
        }

        try {
            $twilio = new Client($this->settings['account_sid'], $this->settings['auth_token']);
            $response = $twilio->messages->create(
                $to,
                [
                    "body" => $body,
                    "from" => $this->settings['sender_id'],
                    "statusCallback" => CommonHelper::generateFullUrl('SmsNotification', 'callback', [static::KEY_NAME], '', false)
                ]
            );
        } catch (Exception $e) {
            return [
                'status' => false,
                'msg' => $e->getMessage()
            ];
        }
        
        return [
            'status' => true,
            'msg' => Labels::getLabel("MSG_SUCCESS", $this->langId),
            'response_id' => $response->sid,
            'data' => $response
        ];
    }

    public function callback()
    {
        $data = FatApp::getPostedData();
        
        if (empty($data) || !array_key_exists('MessageSid', $data)) {
            $this->error = Labels::getLabel('LBL_INVALID_REQUEST', $this->langId);
            return false;
        }
        return SmsArchive::updateStatus($this->langId, $data['MessageSid'], $data['MessageStatus'], $data);
    }
}
