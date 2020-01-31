<?php
class SmsNotificationBaseController extends PluginBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    protected function updateArchiveSms($data)
    {
        if (empty($data) || empty($sid)) {
            Message::addErrorMessage(Labels::getLabel('MSG_INVALID_REQUEST', CommonHelper::getLangId()));
            FatUtility::dieWithError(Message::getHtml());
        }

        $db = FatApp::getDb();

        $dataToSave = [
            'smsarchive_status' => $data['MessageStatus'],
            'smsarchive_response' => json_encode($data),
        ];

        $where = ['smt' => 'smsarchive_response_id = ?', 'vals' => [$data['MessageSid']]];
        if (!$db->updateFromArray(SmsArchive::DB_TBL, $dataToSave, $where)) {
            Message::addErrorMessage($db->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        FatUtility::dieWithError(Labels::getLabel('MSG_SUCCESS', CommonHelper::getlangId()));
    }
}
