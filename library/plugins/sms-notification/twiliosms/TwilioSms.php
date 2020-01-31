<?php

include_once CONF_INSTALLATION_PATH . 'library/Twilio/vendor/autoload.php';
use Twilio\Rest\Client;

class TwilioSms extends SmsNotificationBase
{
    public const KEY_NAME = 'TwilioSms';
    private $fromNumber;

    public function __construct()
    {
        $this->validateSettings();
    }
    
    private function validateSettings()
    {
        $settings = $this->getSettings();
        if (!isset($settings['account_sid']) || !isset($settings['auth_token']) || !isset($settings['phone'])) {
            $this->error = Labels::getLabel('MSG_SETTINGS_NOT_UPDATED', CommonHelper::getLangId());
            return false;
        }
        $this->account_sid = $settings['account_sid'];
        $this->auth_token = $settings['auth_token'];
        $this->fromNumber = $settings['phone'];
    }
    
    public function send($to, $body)
    {
        $db = FatApp::getDb();
        if (empty($to) || empty($body)) {
            $this->error = Labels::getLabel('LBL_INVALID_REQUEST', CommonHelper::getLangId());
            return false;
        }
        
        $twilio = new Client($this->account_sid, $this->auth_token);
        $response = $twilio->messages->create(
            $to,
            [
                "body" => $body,
                "from" => $this->fromNumber,
                "statusCallback" => CommonHelper::generateFullUrl(static::KEY_NAME, 'callback', [static::KEY_NAME])
            ]
        );
        
        return [
            'success' => true,
            'msg' => Labels::getLabel("MSG_SUCCESS", CommonHelper::getLangId()),
            'data' => $response
        ];
    }
}
