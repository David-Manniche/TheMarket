<?php
class PayoutBaseController extends PluginBaseController
{
    public function updateWithdrawalRequest($recordId, $data, $status, $txnstatus = '')
    {
        $txnstatus = empty($txnstatus) ? Transactions::STATUS_COMPLETED : $txnstatus;
        $updateData = [
            'uwrs_withdrawal_id' => $recordId,
            'uwrs_key' => 'WEBHOOK_RESPONSE',
            'uwrs_value' => is_array($data) ? serialize($data) : $data,
        ];

        if (!FatApp::getDb()->insertFromArray(User::DB_TBL_USR_WITHDRAWAL_REQ_SPEC, $updateData, true, array(), $updateData)) {
            $message = Labels::getLabel('LBL_ACTION_TRYING_PERFORM_NOT_VALID', $this->siteLangId);
            FatUtility::dieJsonError($message);
        }

        $assignFields = array('withdrawal_status' => $status);
        if (!FatApp::getDb()->updateFromArray(User::DB_TBL_USR_WITHDRAWAL_REQ, $assignFields, array('smt' => 'withdrawal_id=?','vals' => array($recordId)))) {
            Message::addErrorMessage(FatApp::getDb()->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $emailNotificationObj = new EmailHandler();
        if (!$emailNotificationObj->sendWithdrawRequestNotification($recordId, $this->siteLangId, "U")) {
            Message::addErrorMessage(Labels::getLabel($emailNotificationObj->getError(), $this->siteLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        
        FatApp::getDb()->updateFromArray(
            Transactions::DB_TBL,
            array("utxn_status" => $txnstatus),
            array('smt' => 'utxn_withdrawal_id=?','vals' => array($recordId))
        );
        
        if ($status == Transactions::WITHDRAWL_STATUS_DECLINED) {
            $transObj = new Transactions();
            $txnDetail = $transObj->getAttributesBywithdrawlId($recordId);
            $formattedRequestValue = '#' . str_pad($recordId, 6, '0', STR_PAD_LEFT);
            
            $txnArray["utxn_user_id"] = $txnDetail["utxn_user_id"];
            $txnArray["utxn_credit"] = $txnDetail["utxn_debit"];
            $txnArray["utxn_status"] = $txnstatus;
            $txnArray["utxn_withdrawal_id"] = $txnDetail["utxn_withdrawal_id"];
            $txnArray["utxn_type"] = Transactions::TYPE_MONEY_WITHDRAWL_REFUND;
            $txnArray["utxn_comments"] = sprintf(Labels::getLabel('MSG_Withdrawal_Request_Declined_Amount_Refunded', $this->siteLangId), $formattedRequestValue);
            
            if ($txnId = $transObj->addTransaction($txnArray)) {
                $emailNotificationObj->sendTxnNotification($txnId, $this->siteLangId);
            }
        }
                
        $this->set('msg', Labels::getLabel('LBL_Status_Updated_Successfully', $this->siteLangId));
        $this->_template->render(false, false, 'json-success.php');
    }
}
