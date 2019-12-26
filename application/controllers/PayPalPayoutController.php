<?php
class PayPalPayoutController extends PayoutBaseController
{
    public function callback()
    {
        $post = file_get_contents('php://input');
        if (empty($post)) {
            $message = Labels::getLabel('LBL_INVALID_REQUEST', $this->siteLangId);
            LibHelper::dieJsonError($message);
        }
        $webhookData = json_decode($post, true);
        $event_type = $webhookData['event_type'];
        $requestData = $webhookData['resource'];
        $senderBatchIdArr = explode('_', $requestData['sender_batch_id']);
        $recordId = end($senderBatchIdArr);
        $recordId = FatUtility::int($recordId);

        switch ($event_type) {
            case "PAYMENT.PAYOUTS-ITEM.SUCCEEDED":
                $withdrawStatus = Transactions::WITHDRAWL_STATUS_COMPLETED;
                break;
            
            case "PAYMENT.PAYOUTS-ITEM.CANCELED":
            case "PAYMENT.PAYOUTS-ITEM.DENIED":
                $withdrawStatus = Transactions::WITHDRAWL_STATUS_DECLINED;
                break;
            
            case "PAYMENT.PAYOUTS-ITEM.FAILED":
                $withdrawStatus = Transactions::WITHDRAWL_STATUS_PAYOUT_FAILED;
                break;
        }
        $this->updateWithdrawalRequest($recordId, $post, $withdrawStatus);
    }
}
