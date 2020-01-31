<?php
class SmsNotificationBase extends pluginBase
{
    protected function save($data)
    {
        if (empty($data)) {
            $this->error = Labels::getLabel('LBL_INVALID_REQUEST', CommonHelper::getLangId());
            return false;
        }
        $smsArchiveObj = new SmsArchive();
        $smsArchiveObj->assignValues($data);
        if (!$smsArchiveObj->save()) {
            $this->error = $smsArchiveObj->getError();
            return false;
        }
        return true;
    }
    
    protected function updateArchiveSms($sid, $data)
    {
        if (empty($data) || empty($sid)) {
            $this->error = Labels::getlabel("MSG_INVALID_REQUEST", CommonHelper::getLangId());
            return false;
        }
        $db = FatApp::getDb();
        $class = get_called_class();
        $where = ['smt' => 'smsarchive_response_id = ?', 'vals' => [$sid]];
        if (!$db->updateFromArray($class::DB_TBL, $data, $where)) {
            $this->error = $db->getError();
            return false;
        }
        return true;
    }
}
