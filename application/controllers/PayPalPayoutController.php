<?php
class PayPalPayoutController extends PayoutBaseController
{
    public const KEY_NAME = 'PayPalPayout';
    public static function particulars()
    {
        return [
                'amount' => [
                    'type' => 'float',
                    'required' => true,
                    'label' => "Amount",
                ],
                'email' => [
                    'type' => 'string',
                    'required' => false,
                    'label' => "Email Id",
                ],
                'paypal_id' => [
                    'type' => 'string',
                    'required' => false,
                    'label' => "PayPal Id",
                ],
            ];
    }

    private function validateWithdrawalRequest()
    {
        $userId = UserAuthentication::getLoggedUserId();
        $post = FatApp::getPostedData();

        $balance = User::getUserBalance($userId);
        $lastWithdrawal = User::getUserLastWithdrawalRequest($userId);

        if ($lastWithdrawal && (strtotime($lastWithdrawal["withdrawal_request_date"] . "+" . FatApp::getConfig("CONF_MIN_INTERVAL_WITHDRAW_REQUESTS", FatUtility::VAR_INT, 0) . " days") - time()) > 0) {
            $nextWithdrawalDate = date('d M,Y', strtotime($lastWithdrawal["withdrawal_request_date"] . "+" . FatApp::getConfig("CONF_MIN_INTERVAL_WITHDRAW_REQUESTS") . " days"));

            $message = sprintf(Labels::getLabel('MSG_Withdrawal_Request_Date', $this->siteLangId), FatDate::format($lastWithdrawal["withdrawal_request_date"]), FatDate::format($nextWithdrawalDate), FatApp::getConfig("CONF_MIN_INTERVAL_WITHDRAW_REQUESTS"));
            FatUtility::dieJsonError($message);
        }

        $minimumWithdrawLimit = FatApp::getConfig("CONF_MIN_WITHDRAW_LIMIT", FatUtility::VAR_INT, 0);
        if ($balance < $minimumWithdrawLimit) {
            $message = sprintf(Labels::getLabel('MSG_Withdrawal_Request_Minimum_Balance_Less', $this->siteLangId), CommonHelper::displayMoneyFormat($minimumWithdrawLimit));
            FatUtility::dieJsonError($message);
        }

        if (($minimumWithdrawLimit > $post["amount"])) {
            $message = sprintf(Labels::getLabel('MSG_Your_withdrawal_request_amount_is_less_than_the_minimum_allowed_amount_of_%s', $this->siteLangId), CommonHelper::displayMoneyFormat($minimumWithdrawLimit));
            FatUtility::dieJsonError($message);
        }

        $maximumWithdrawLimit = FatApp::getConfig("CONF_MAX_WITHDRAW_LIMIT", FatUtility::VAR_INT, 0);
        if (($maximumWithdrawLimit < $post["amount"])) {
            $message = sprintf(Labels::getLabel('MSG_Your_withdrawal_request_amount_is_greater_than_the_maximum_allowed_amount_of_%s', $this->siteLangId), CommonHelper::displayMoneyFormat($maximumWithdrawLimit));
            FatUtility::dieJsonError($message);
        }

        if (($post["amount"] > $balance)) {
            $message = Labels::getLabel('MSG_Withdrawal_Request_Greater', $this->siteLangId);
            FatUtility::dieJsonError($message);
        }
    }

    public function saveWithdrawalSpecifics($withdrawalId, $data, $elements)
    {
        if (empty($withdrawalId) || empty($data) || empty($elements)) {
            $this->error = Labels::getLabel('MSG_INVALID_REQUEST', CommonHelper::getLangId());
            return false;
        }

        foreach ($data as $key => $val) {
            if (!in_array($key, $elements)) {
                continue;
            }
            $updateData = [
                'uwrs_withdrawal_id' => $withdrawalId,
                'uwrs_key' => $key,
                'uwrs_value' => is_array($val) ? serialize($val) : $val,
            ];

            if (!FatApp::getDb()->insertFromArray(User::DB_TBL_USR_WITHDRAWAL_REQ_SPEC, $updateData, true, array(), $updateData)) {
                $message = Labels::getLabel('LBL_ACTION_TRYING_PERFORM_NOT_VALID', $this->siteLangId);
                FatUtility::dieJsonError($message);
            }
        }
        return true;
    }

    public function setup()
    {
        $this->validateWithdrawalRequest();

        $particulars = self::particulars();
        $frm = PluginSetting::getForm($particulars, $this->siteLangId);

        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        $post['withdrawal_amount'] = $post['amount'];

        if (false === $post) {
            LibHelper::dieJsonError(current($frm->getValidationErrors()));
        }

        foreach ($post as $key => $value) {
            if (in_array($key, $particulars) && true === $particulars[$key]['required'] && empty($value)) {
                $message = Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId);
                FatUtility::dieJsonError($message);
            }
        }

        $userId = UserAuthentication::getLoggedUserId();
        $userObj = new User($userId);
        $withdrawal_payment_method = FatApp::getPostedData('plugin_id', FatUtility::VAR_INT, 0);

        // $withdrawal_payment_method = ($withdrawal_payment_method > 0 && array_key_exists($withdrawal_payment_method, User::getAffiliatePaymentMethodArr($this->siteLangId))) ? $withdrawal_payment_method  : User::AFFILIATE_PAYMENT_METHOD_BANK;


        $post['withdrawal_payment_method'] = $withdrawal_payment_method;

        if (!$withdrawRequestId = $userObj->addWithdrawalRequest(array_merge($post, array("ub_user_id" => $userId)), $this->siteLangId)) {
            $message = Labels::getLabel($userObj->getError(), $this->siteLangId);
            FatUtility::dieJsonError($message);
        }

        $this->saveWithdrawalSpecifics($withdrawRequestId, $post, array_keys($particulars));

        $emailNotificationObj = new EmailHandler();
        if (!$emailNotificationObj->sendWithdrawRequestNotification($withdrawRequestId, $this->siteLangId, "A")) {
            $message = Labels::getLabel($emailNotificationObj->getError(), $this->siteLangId);
            FatUtility::dieJsonError($message);
        }

        //send notification to admin
        $notificationData = array(
            'notification_record_type' => Notification::TYPE_WITHDRAWAL_REQUEST,
            'notification_record_id' => $withdrawRequestId,
            'notification_user_id' => UserAuthentication::getLoggedUserId(),
            'notification_label_key' => Notification::WITHDRAWL_REQUEST_NOTIFICATION,
            'notification_added_on' => date('Y-m-d H:i:s'),
        );

        if (!Notification::saveNotifications($notificationData)) {
            $message = Labels::getLabel("MSG_NOTIFICATION_COULD_NOT_BE_SENT", $this->siteLangId);
            FatUtility::dieJsonError($message);
        }

        $this->set('msg', Labels::getLabel('MSG_Withdraw_request_placed_successfully', $this->siteLangId));

        if (true ===  MOBILE_APP_API_CALL) {
            $this->_template->render();
        }
        $this->_template->render(false, false, 'json-success.php');
    }

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
        
        $txnStatus = '';
        switch ($event_type) {
            case "PAYMENT.PAYOUTS-ITEM.SUCCEEDED":
                $withdrawStatus = Transactions::WITHDRAWL_STATUS_COMPLETED;
                $txnStatus = Transactions::STATUS_COMPLETED;
                break;
            
            case "PAYMENT.PAYOUTS-ITEM.CANCELED":
            case "PAYMENT.PAYOUTS-ITEM.DENIED":
                $withdrawStatus = Transactions::WITHDRAWL_STATUS_DECLINED;
                $txnStatus = Transactions::STATUS_DECLINED;
                break;
            
            case "PAYMENT.PAYOUTS-ITEM.FAILED":
                $withdrawStatus = Transactions::WITHDRAWL_STATUS_PAYOUT_FAILED;
                $txnStatus = Transactions::STATUS_DECLINED;
                break;
            case "PAYMENT.PAYOUTS-ITEM.UNCLAIMED":
                $withdrawStatus = Transactions::WITHDRAWL_STATUS_PAYOUT_UNCLAIMED;
                $txnStatus = Transactions::STATUS_COMPLETED;
                break;
        }
        $this->updateWithdrawalRequest($recordId, $post, $withdrawStatus, $txnStatus);
    }
}
