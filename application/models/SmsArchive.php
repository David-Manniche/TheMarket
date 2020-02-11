<?php
class SmsArchive extends MyAppModel
{
    public const DB_TBL = 'tbl_sms_archives';
    public const DB_TBL_PREFIX = 'smsarchive_';
    
    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    public static function send($toNumber, $tpl, $langId, $replacements = [], &$error = '')
    {
        $langId  = FatUtility::int($langId);
        $defaultPushNotiAPI = FatApp::getConfig('CONF_DEFAULT_PLUGIN_' . Plugin::TYPE_SMS_NOTIFICATION, FatUtility::VAR_INT, 0);
        if (empty($defaultPushNotiAPI) || empty($toNumber) || empty($tpl) || 1 > $langId) {
            $error = Labels::getLabel('MSG_INVALID_REQUEST', $langId);
            return false;
        }

        $keyName = Plugin::getAttributesById($defaultPushNotiAPI, 'plugin_code');
        if (1 > Plugin::isActive($keyName)) {
            $error = Labels::getLabel('MSG_PLUGIN_NOT_ACTIVE', $langId);
            return false;
        }

        $tplData = SmsTemplate::getTpl($tpl, $langId);
        if (1 > $tplData['stpl_status']) {
            $error = Labels::getLabel("MSG_TEMPLATE_NOT_ACTIVE", $langId);
            return false;
        }

        $replacements = array_merge($replacements, LibHelper::getCommonReplacementVarsArr($langId));
        $body = CommonHelper::replaceStringData($tplData['stpl_body'], $replacements);

        require_once CONF_PLUGIN_DIR . '/sms-notification/' . strtolower($keyName) . '/' . $keyName . '.php';

        $smsGateway = new $keyName();
        $response = $smsGateway->send($toNumber, $body);
        
        if (false == $response || false == $response['status']) {
            $error = isset($response['msg']) ? $response['msg'] :  $smsGateway->getError();
            return false;
        }
       
        $dataToSave = [
            'smsarchive_to' => $toNumber,
            'smsarchive_tpl_name' => $tpl,
            'smsarchive_body' => $body,
            'smsarchive_sent_on' => date('Y-m-d H:i:s'),
            'smsarchive_response_id' => !empty($response['response_id']) ? $response['response_id'] : 0
        ];

        $smsArchive = new SmsArchive();
        $smsArchive->assignValues($dataToSave);
        if (!$smsArchive->save()) {
            $error = $smsArchive->getError();
            return false;
        }
        return true;
    }

    public static function updateStatus($langId, $messageId, $status, $response)
    {
        $langId = FatUtility::int($langId);
        
        if (empty($messageId) || empty($status) || 1 > $langId) {
            return false;
        }

        $db = FatApp::getDb();

        $dataToSave = [
            'smsarchive_status' => $status,
            'smsarchive_response' => json_encode($response),
        ];

        $where = ['smt' => 'smsarchive_response_id = ?', 'vals' => [$messageId]];
        if (!$db->updateFromArray(SmsArchive::DB_TBL, $dataToSave, $where)) {
            return false;
        }
        return true;
    }
}
