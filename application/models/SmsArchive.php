<?php
class SmsArchive extends MyAppModel
{
    public const DB_TBL = 'tbl_sms_archives';
    public const DB_TBL_PREFIX = 'smsarchive_';
    
    private $db;

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
    }

    public static function send($toNumber, $body, $tpl)
    {
        $defaultPushNotiAPI = FatApp::getConfig('CONF_DEFAULT_PLUGIN_' . Plugin::TYPE_SMS_NOTIFICATION, FatUtility::VAR_INT, 0);
        if (empty($defaultPushNotiAPI) || empty($toNumber) || empty($body) || empty($tpl)) {
            return false;
        }

        $keyName = Plugin::getAttributesById($defaultPushNotiAPI, 'plugin_code');
        if (1 > Plugin::isActive($keyName)) {
            // $this->error =  Labels::getLabel('MSG_PLUGIN_IS_NOT_ACTIVE', CommonHelper::getLangId());
            return false;
        }

        require_once CONF_PLUGIN_DIR . '/sms-notification/' . strtolower($keyName) . '/' . $keyName . '.php';

        $obj = new $keyName();
        $response = $obj->send($toNumber, $body);
        if (false == $response) {
            // $this->error = $obj->getError();
            return false;
        }
        if (true == $response['status']) {
            $dataToSave = [
                'smsarchive_to' => $toNumber,
                'smsarchive_tpl_name' => $tpl,
                'smsarchive_body' => $body,
                'smsarchive_sent_on' => date('Y-m-d H:i:s'),
                'smsarchive_response_id' => !empty($response->sid) ? $response->sid : 0
            ];
    
            $smsArObj = new SmsArchive();
            $smsArObj->assignValues($dataToSave);
            if (!$smsArObj->save()) {
                // $error = $smsArObj->getError();
                return false;
            }
            return true;
        }
        return false;
    }
}
