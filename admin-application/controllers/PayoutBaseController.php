<?php
class PayoutBaseController extends PluginSettingController
{
    protected $envoirment;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->envoirment = FatApp::getConfig('CONF_TRANSACTION_MODE', FatUtility::VAR_BOOLEAN, false);
    }

    public function index()
    {
        $recordId = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if (1 > $recordId) {
            $message = Labels::getLabel('LBL_INVALID_REQUEST', CommonHelper::getLangId());
            LibHelper::dieJsonError($message);
        }
        
        $srch = new WithdrawalRequestsSearch();
        $srch->joinUsers(true);
        $srch->joinForUserBalance();
        $srch->joinTable(User::DB_TBL_USR_WITHDRAWAL_REQ_SPEC, 'LEFT JOIN', User::DB_TBL_USR_WITHDRAWAL_REQ_SPEC_PREFIX . 'withdrawal_id = tuwr.withdrawal_id');
        $srch->addMultipleFields(['withdrawal_amount' ,'credential_email']);
        $srch->addCondition('tuwr.withdrawal_id', '=', $recordId);
        $rs = $srch->getResultSet();
        
        $record = FatApp::getDb()->fetch($rs);
        $specifics = WithdrawalRequestsSearch::getWithDrawalSpecifics($recordId);
        try {
            $calledClass = get_called_class();
            $obj = new $calledClass(__FUNCTION__);
            $response = $obj->release($recordId, $record['credential_email'], $record['withdrawal_amount'], $specifics);
        } catch (\Error $e) {
            $message = 'ERR - ' . $e->getMessage();
            LibHelper::dieJsonError($message);
        }
        CommonHelper::printArray($response, true);
    }
}
