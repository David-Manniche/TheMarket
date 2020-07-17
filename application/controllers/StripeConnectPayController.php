<?php

class StripeConnectPayController extends PaymentController
{
    public const KEY_NAME = 'StripeConnect';
    public $stripeConnect;
    private $liveMode = '';
    private $paymentAmount = 0;
    private $sourceId = '';
    private $orderInfo = [];
        
    /**
     * __construct
     *
     * @param  string $action
     * @return void
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
        
        $error = '';
        $this->stripeConnect = PluginHelper::callPlugin(self::KEY_NAME, [$this->siteLangId], $error, $this->siteLangId);
        if (false === $this->stripeConnect) {
            $this->setErrorAndRedirect($error);
        }
        $this->init();
    }
    
    /**
     * init
     *
     * @return void
     */
    public function init()
    {
        $userId = UserAuthentication::getLoggedUserId(true);
        if (1 > $userId) {
            $msg = Labels::getLabel('MSG_INVALID_USER', $this->siteLangId);
            $this->setErrorAndRedirect($msg);
        }

        if (false === $this->stripeConnect->init($userId)) {
            $this->setErrorAndRedirect();
        }

        if (!empty($this->stripeConnect->getError())) {
            $this->setErrorAndRedirect();
        }

        $this->settings = $this->stripeConnect->getSettings();

        if (isset($this->settings['env']) && Plugin::ENV_PRODUCTION == $this->settings['env']) {
            $this->liveMode = "live_";
        }
    }
    
    /**
     * allowedCurrenciesArr
     *
     * @return array
     */
    protected function allowedCurrenciesArr(): array
    {
        return [
            'USD', 'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BIF', 'BMD', 'BND', 'BOB', 'BRL', 'BSD', 'BWP', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY', 'COP', 'CRC', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EGP', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP', 'GEL', 'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'ISK', 'JMD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KRW', 'KYD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MOP', 'MRO', 'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SEK', 'SGD', 'SHP', 'SLL', 'SOS', 'SRD', 'STD', 'SZL', 'THB', 'TJS', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'UYU', 'UZS', 'VND', 'VUV', 'WST', 'XAF', 'XCD', 'XOF', 'XPF', 'YER', 'ZAR', 'ZMW'
        ];
    }
    
    /**
     * getCardForm
     *
     * @return object
     */
    private function getCardForm(): object
    {
        $frm = new Form('frmPaymentForm');
        $frm->addRequiredField(Labels::getLabel('LBL_ENTER_CREDIT_CARD_NUMBER', $this->siteLangId), 'number');
        $frm->addRequiredField(Labels::getLabel('LBL_CARD_HOLDER_FULL_NAME', $this->siteLangId), 'name');
        $data['months'] = applicationConstants::getMonthsArr($this->siteLangId);
        $today = getdate();
        $data['year_expire'] = array();
        for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
            $data['year_expire'][strftime('%Y', mktime(0, 0, 0, 1, 1, $i))] = strftime('%Y', mktime(0, 0, 0, 1, 1, $i));
        }
        $frm->addSelectBox(Labels::getLabel('LBL_EXPIRY_MONTH', $this->siteLangId), 'exp_month', $data['months'], '', array(), '');
        $frm->addSelectBox(Labels::getLabel('LBL_EXPIRY_YEAR', $this->siteLangId), 'exp_year', $data['year_expire'], '', array(), '');
        $frm->addPasswordField(Labels::getLabel('LBL_CVV_SECURITY_CODE', $this->siteLangId), 'cvc')->requirements()->setRequired();
        $frm->addCheckBox(Labels::getLabel('LBL_SAVE_THIS_CARD_FOR_FASTER_CHECKOUT', $this->siteLangId), 'cc_save_card', '1');
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Pay_Now', $this->siteLangId));

        return $frm;
    }
    
    /**
     * getSavedCardPaymentForm
     *
     * @return object
     */
    private function getSavedCardPaymentForm(): object
    {
        $frm = new Form('frmCardPaymentForm');
        $frm->addHiddenField('', 'card_id');
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Pay_Now', $this->siteLangId));
        $frm->addButton('', 'btn_addnew', Labels::getLabel('LBL_ADD_NEW_?', $this->siteLangId));
        return $frm;
    }
    
    /**
     * checkCardType
     *
     * @return void
     */
    public function checkCardType()
    {
        $post = FatApp::getPostedData();
        $res = ValidateElement::ccNumber($post['cc']);
        echo json_encode($res);
        exit;
    }
    
    /**
     * addCardForm
     *
     * @param  mixed $orderId
     * @return void
     */
    public function addCardForm($orderId)
    {
        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();
        $cancelBtnUrl = CommonHelper::getPaymentCancelPageUrl();
        if ($orderInfo['order_type'] == Orders::ORDER_WALLET_RECHARGE) {
            $cancelBtnUrl = CommonHelper::getPaymentFailurePageUrl();
        }
        $frm = $this->getCardForm();

        $this->set('frm', $frm);
        $this->set('cancelBtnUrl', $cancelBtnUrl);
        $this->set('orderId', $orderId);
        $this->_template->render(false, false);
    }
    
    /**
     * removeCard
     *
     * @return void
     */
    public function removeCard()
    {
        $cardId = FatApp::getPostedData('cardId', FatUtility::VAR_STRING, '');
        if (empty($cardId)) {
            $this->setError(Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId));
        }

        if (false === $this->stripeConnect->removeCard(['cardId' => $cardId])) {
            $this->setErrorAndRedirect();
        }
        $msg = Labels::getLabel("MSG_REMOVED_SUCCESSFULLY", $this->siteLangId);
        FatUtility::dieJsonSuccess($msg);
    }
    
    /**
     * getOrderInfo
     *
     * @param  string $orderId
     * @return array
     */
    private function getOrderInfo(string $orderId): array
    {
        if (empty($this->orderInfo)) {
            $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
            $this->paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
            $this->orderInfo = $orderPaymentObj->getOrderPrimaryinfo();
        }
        return $this->orderInfo;
    }
        
    /**
     * charge
     *
     * @param  string $orderId
     * @return void
     */
    public function charge($orderId)
    {
        $this->orderId = $orderId;
        if (empty(trim($this->orderId))) {
            $msg = Labels::getLabel('MSG_Invalid_Access', $this->siteLangId);
            $this->setErrorAndRedirect($msg);
        }

        $this->orderInfo = $this->getOrderInfo($this->orderId);

        if (!$this->orderInfo['id']) {
            $msg = Labels::getLabel('MSG_Invalid_Access', $this->siteLangId);
            $this->setErrorAndRedirect($msg);
        }
        
        if ($this->orderInfo["order_is_paid"] != Orders::ORDER_IS_PENDING) {
            $msg = Labels::getLabel('MSG_INVALID_ORDER._ALREADY_PAID_OR_CANCELLED', $this->siteLangId);
            $this->setErrorAndRedirect($msg);
        }

        $frm = $this->getSavedCardPaymentForm();
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $cardId = FatApp::getPostedData('card_id', FatUtility::VAR_STRING, '');
            if (!empty($cardId)) {
                $this->sourceId = $cardId;
            } else {
                $frm = $this->getCardForm();
                $cardData = $frm->getFormDataFromArray(FatApp::getPostedData());
                if (false === $cardData) {
                    $this->setErrorAndRedirect(current($frm->getValidationErrors()));
                }
                $saveCard = FatApp::getPostedData('cc_save_card', FatUtility::VAR_INT, 0);
                unset($cardData['btn_submit'], $cardData['cc_save_card']);

                if (false === $this->stripeConnect->generateCardToken($cardData)) {
                    $this->setErrorAndRedirect();
                }
                $cardTokenResponse = $this->stripeConnect->getResponse();
                if (0 < $saveCard) {
                    if (false === $this->stripeConnect->addCard(['cardToken' => $cardTokenResponse->id])) {
                        $this->setErrorAndRedirect();
                    }
                }
                $sourceResponse = $this->stripeConnect->getResponse();
                $this->sourceId = $sourceResponse->id;
            }

            $this->doPayment();

            $json['status'] = applicationConstants::ACTIVE;
            $json['msg'] = Labels::getLabel('MSG_SUCCESS', $this->siteLangId);
            $json['redirectUrl'] = UrlHelper::generateFullUrl('custom', 'paymentSuccess', array($this->orderId));
            FatUtility::dieJsonSuccess($json);
        } else {
            if (false === $this->stripeConnect->createCustomerObject($this->orderInfo)) {
                $this->setErrorAndRedirect();
            }
            $this->customerId = $this->stripeConnect->getCustomerId();   
            $this->set('customerId', $this->customerId);
        }

        $this->stripeConnect->loadSavedCards();
        $savedCards = $this->stripeConnect->getResponse()->toArray();
        $this->set('savedCards', $savedCards);

        $cancelBtnUrl = CommonHelper::getPaymentCancelPageUrl();
        if ($this->orderInfo['order_type'] == Orders::ORDER_WALLET_RECHARGE) {
            $cancelBtnUrl = CommonHelper::getPaymentFailurePageUrl();
        }
        
        $this->set('paymentAmount', $this->paymentAmount);
        $this->set('orderInfo', $this->orderInfo);
        
        if (true === MOBILE_APP_API_CALL) {
            $this->_template->render();
        }

        $this->set('frm', $frm);
        $this->set('cancelBtnUrl', $cancelBtnUrl);
        $this->set('exculdeMainHeaderDiv', true);
        $this->_template->render(true, false);
    }
    
    /**
     * convertInPaisa
     *
     * @param  mixed $amount
     * @return void
     */
    private function convertInPaisa($amount)
    {
        $amount = number_format($amount, 2, '.', '');
        return $amount * 100;
    }
        
    /**
     * doPayment
     *
     * @return void
     */
    private function doPayment()
    {
        if (empty($this->sourceId)) {
            $msg = Labels::getLabel('MSG_NO_SOURCE_PROVIDED', $this->siteLangId);
            $this->setErrorAndRedirect($msg);
        }

        $customerId = $this->stripeConnect->getCustomerId();
        $desc = Labels::getLabel('LBL_ORDER_#{order-id}_PLACED._SHIPPING_AND_TAX_CHARGES_INCLUDED', $this->siteLangId);
        $desc = CommonHelper::replaceStringData($desc, ['{order-id}' => $this->orderId]);
        $chargeData = [
            'amount' => $this->convertInPaisa($this->paymentAmount),
            'currency' => $this->systemCurrencyCode,
            'customer' => $customerId,
            'description' =>  $desc,
            'metadata' => [
                'order_id' => $this->orderId
            ],
            'statement_descriptor' => $this->orderId,
            'transfer_group' => $this->orderId,
            'source' => $this->sourceId,
        ];

        if (false === $this->stripeConnect->doCharge($chargeData)) {
            $this->setErrorAndRedirect();
        }
        $this->distribute();
        return true;
    }
    
    /**
     * distribute
     *
     * @return void
     */
    private function distribute()
    {
        $chargeResponse = $this->stripeConnect->getResponse();
        if ('succeeded' != $chargeResponse['status']) {
            $msg = Labels::getLabel('MSG_UNABLE_TO_CHARGE', $this->siteLangId);
            $this->setErrorAndRedirect($msg);
        }

        $chargeId = $chargeResponse['id'];

        $message = 'Id: ' . $chargeResponse['id'] . "&";
        $message .= 'Object: ' . $chargeResponse['object'] . "&";
        $message .= 'Amount: ' . $chargeResponse['amount'] . "&";
        $message .= 'Amount Refunded: ' . $chargeResponse['amount_refunded'] . "&";
        $message .= 'Application Fee: ' . $chargeResponse['application_fee'] . "&";
        $message .= 'Balance Transaction: ' . $chargeResponse['balance_transaction'] . "&";
        $message .= 'Captured: ' . $chargeResponse['captured'] . "&";
        $message .= 'Created: ' . $chargeResponse['created'] . "&";
        $message .= 'Currency: ' . $chargeResponse['currency'] . "&";
        $message .= 'Customer: ' . $chargeResponse['customer'] . "&";
        $message .= 'Description: ' . $chargeResponse['description'] . "&";
        $message .= 'Destination: ' . $chargeResponse['destination'] . "&";
        $message .= 'Dispute: ' . $chargeResponse['dispute'] . "&";
        $message .= 'Failure Code: ' . $chargeResponse['failure_code'] . "&";
        $message .= 'Failure Message: ' . $chargeResponse['failure_message'] . "&";
        $message .= 'Invoice: ' . $chargeResponse['invoice'] . "&";
        $message .= 'Livemode: ' . $chargeResponse['livemode'] . "&";
        $message .= 'Paid: ' . $chargeResponse['paid'] . "&";
        $message .= 'Receipt Email: ' . $chargeResponse['receipt_email'] . "&";
        $message .= 'Receipt Number: ' . $chargeResponse['receipt_number'] . "&";
        $message .= 'Refunded: ' . $chargeResponse['refunded'] . "&";
        $message .= 'Statement Descriptor: ' . $chargeResponse['statement_descriptor'] . "&";
        $message .= 'Status: ' . $chargeResponse['status'] . "";
        
        /* Recording Payment in DB */
        $orderPaymentObj = new OrderPayment($this->orderId, $this->siteLangId);

        $this->paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();

        if (false === $orderPaymentObj->addOrderPayment($this->settings["plugin_code"], $chargeId, $this->paymentAmount, Labels::getLabel("MSG_RECEIVED_PAYMENT", $this->siteLangId), $message)) {
            $orderPaymentObj->addOrderPaymentComments($message);
        }

        $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();

        $orderObj = new Orders();
        $orderProducts = $orderObj->getChildOrders(array('order_id' => $orderInfo['id']), $orderInfo['order_type'], $orderInfo['order_language_id']);

        foreach ($orderProducts as $op) {
            $productSoldAmount = CommonHelper::orderProductAmount($op, 'NETAMOUNT');
            $amountToBePaidToSeller = CommonHelper::orderProductAmount($op, 'NETAMOUNT', false, User::USER_TYPE_SELLER);
            $amountToBePaidToSeller = ($amountToBePaidToSeller - $op['op_commission_charged']);

            $accountId = User::getUserMeta($op['op_selprod_user_id'], 'stripe_account_id');
            // Credit sold product amount to seller wallet.
            $comments = Labels::getLabel('MSG_PRODUCT_SOLD._#{invoice-no}._COMMISSION_CHARGED_{commission-amount}', $this->siteLangId);
            $comments = CommonHelper::replaceStringData($comments, ['{invoice-no}' => $op['op_invoice_number'], '{commission-amount}' => $op['op_commission_charged']]);
            Transactions::creditWallet($op['op_selprod_user_id'], Transactions::TYPE_PRODUCT_SALE, $amountToBePaidToSeller, $this->siteLangId, $comments, $op['op_id']);
            
            $charge = [
                'amount' => $this->convertInPaisa($amountToBePaidToSeller),
                'currency' => $orderInfo['order_currency_code'],
                'destination' => $accountId,
                // 'transfer_group' => $op['op_invoice_number'],
                'description' => $comments,
                'metadata' => [
                    'op_id' => $op['op_id']
                ],
                'source_transaction' => $chargeId
            ];

            if (false === $this->stripeConnect->doTransfer($charge)) {
                $this->setErrorAndRedirect();
            }

            $resp = $this->stripeConnect->getResponse();
            if (empty($resp->id)) {
                continue;
            }

            // Debit sold product amount to seller wallet.
            $comments = Labels::getLabel('MSG_TRANSFERED_TO_ACCOUNT_{account-id}.', $this->siteLangId);
            $comments = CommonHelper::replaceStringData($comments, ['{account-id}' => $accountId]);
            Transactions::debitWallet($op['op_selprod_user_id'], Transactions::TYPE_TRANSFER_TO_THIRD_PARTY_ACCOUNT, $amountToBePaidToSeller, $this->siteLangId, $comments, $op['op_id'], $resp->id);
        }
        return true;
    }

    /**
     * setError
     *
     * @param  mixed $msg
     * @return void
     */
    private function setError(string $msg = "")
    {
        $msg = !empty($msg) ? $msg : $this->stripeConnect->getError();
        LibHelper::exitWithError($msg, true);
    }


    /**
     * getCustomer
     *
     * @return void
     */
    public function getCustomer()
    {
        if (empty($this->stripeConnect->getCustomerId())) {
            $this->setError(Labels::getLabel('MSG_INVALID_CUSTOMER', $this->siteLangId));
        }
        $this->stripeConnect->loadCustomer();
        $customerInfo = $this->stripeConnect->getResponse()->toArray();
        $this->set('customerInfo', $customerInfo);
        $this->_template->render();
    }

    /**
     * markCardAsDefault
     *
     * @return void
     */
    public function markCardAsDefault()
    {
        $source = FatApp::getPostedData('source', FatUtility::VAR_STRING, '');
        if (empty($source)) {
            $this->setError(Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId));
        }

        if (empty($this->stripeConnect->getCustomerId())) {
            $this->setError(Labels::getLabel('MSG_INVALID_CUSTOMER', $this->siteLangId));
        }
        
        $requestParam['default_source'] = $source;
        if (false === $this->stripeConnect->updateCustomerInfo($requestParam)) {
            $this->setError();
        }
        FatUtility::dieJsonSuccess(Labels::getLabel('MSG_SUCCESSFULLY_UPDATED', $this->siteLangId));
    }
}
