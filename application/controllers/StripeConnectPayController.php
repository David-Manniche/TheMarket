<?php

class StripeConnectPayController extends PaymentController
{
    public const KEY_NAME = 'StripeConnect';
    private $stripeConnect;
    private $settings;
    private $liveMode = '';
    
    public function __construct($action)
    {
        parent::__construct($action);
        
        $error = '';
        $this->stripeConnect = PluginHelper::callPlugin(self::KEY_NAME, [$this->siteLangId], $error, $this->siteLangId);
        if (false === $this->stripeConnect) {
            $this->setErrorAndRedirect($error);
        }
    }

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

        $this->settings = $this->stripeConnect->getKeys();

        if (isset($this->settings['env']) && Plugin::ENV_PRODUCTION == $this->settings['env']) {
            $this->liveMode = "live_";
        }
    }

    private function setErrorAndRedirect(string $msg = "")
    {
        $msg = !empty($msg) ? $msg : $this->stripeConnect->getError();
        LibHelper::exitWithError($msg, false, true);
        CommonHelper::redirectUserReferer();
    }

    protected function allowedCurrenciesArr()
    {
        return [
            'USD', 'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BIF', 'BMD', 'BND', 'BOB', 'BRL', 'BSD', 'BWP', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY', 'COP', 'CRC', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EGP', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP', 'GEL', 'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'ISK', 'JMD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KRW', 'KYD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MOP', 'MRO', 'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SEK', 'SGD', 'SHP', 'SLL', 'SOS', 'SRD', 'STD', 'SZL', 'THB', 'TJS', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'UYU', 'UZS', 'VND', 'VUV', 'WST', 'XAF', 'XCD', 'XOF', 'XPF', 'YER', 'ZAR', 'ZMW'
        ];
    }

    public function charge($orderId)
    {
        $this->init();

        if (empty(trim($orderId))) {
            $msg = Labels::getLabel('MSG_Invalid_Access', $this->siteLangId);
            $this->setErrorAndRedirect($msg);
        }

        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
        $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();

        $orderObj = new Orders();
        $orderProducts = $orderObj->getChildOrders(array('order_id' => $orderInfo['id']), $orderInfo['order_type'], $orderInfo['order_language_id']);

        if (!$orderInfo['id']) {
            $msg = Labels::getLabel('MSG_Invalid_Access', $this->siteLangId);
            $this->setErrorAndRedirect($msg);
        }
        
        if ($orderInfo["order_is_paid"] != Orders::ORDER_IS_PENDING) {
            $msg = Labels::getLabel('MSG_INVALID_ORDER._ALREADY_PAID_OR_CANCELLED', $this->siteLangId);
            $this->setErrorAndRedirect($msg);
        }
 
        if (false === $this->stripeConnect->createCustomerObject($orderInfo)) {
            $this->setErrorAndRedirect();
        }
        $customerId = $this->stripeConnect->getCustomerId();
 
 
        $cancelUrl = CommonHelper::getPaymentCancelPageUrl();
        if ($orderInfo['order_type'] == Orders::ORDER_WALLET_RECHARGE) {
            $cancelUrl = CommonHelper::getPaymentFailurePageUrl();
        }

        $orderFormattedData = $this->stripeConnect->formatCustomerDataFromOrder($orderInfo);
        $data = [
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'success_url' => CommonHelper::generateFullUrl('custom', 'paymentSuccess', array($orderInfo['id'])),
            'cancel_url' => $cancelUrl,
            'line_items' => [],
            'customer' => $customerId,
            'payment_intent_data' => [
                'receipt_email' => FatApp::getConfig('CONF_SITE_OWNER_EMAIL'),
                'shipping' => $orderFormattedData['shipping']
            ],
            'metadata' => [
                'orderId' => $orderId
            ]
        ];

        $charges = $orderObj->getOrderProductChargesByOrderId($orderInfo['id']);
        foreach ($orderProducts as $op) {
            $netAmount = CommonHelper::orderProductAmount($op, 'NETAMOUNT');
            $singleItemPrice = $netAmount / $op['op_qty'];
            $priceData = [
                'unit_amount' => $this->convertInPaisa($singleItemPrice),
                'currency' => $orderInfo['order_currency_code'],
                'product_data' => [
                    'name' => $op['op_selprod_title'],
                    'metadata' => [
                        'id' => $op['op_id']
                    ]
                ],
                'nickname' => Labels::getLabel('LBL_SHIPPING_COST_AND_TAX_CHARGES_INCLUDED', $this->siteLangId)
            ];
            
            if (false === $this->stripeConnect->createPriceObject($priceData)) {
                $this->setErrorAndRedirect();
            }

            $data['line_items'][] = [
                'price' => $this->stripeConnect->getPriceId(),
                'quantity' => $op['op_qty']
            ];

            // You may not provide the application_fee_amount parameter and the transfer_data[amount] parameter simultaneously. They are mutually exclusive.
            // $data['payment_intent_data']['application_fee_amount'] = $this->convertInPaisa($op['op_commission_charged']);
            
            $data['payment_intent_data']['statement_descriptor'] = $op['op_invoice_number'];

            $accountId = User::getUserMeta($op['op_selprod_user_id'], 'stripe_account_id');
            if (!empty($accountId)) {
                $data['payment_intent_data']['transfer_data'] = [
                    'destination' => $accountId,
                    'amount' => $this->convertInPaisa($netAmount - $op['op_commission_charged'])
                ];
            }
        }

        if (false === $this->stripeConnect->initiateSession($data)) {
            $this->setErrorAndRedirect();
        }

        $sessionId = $this->stripeConnect->getSessionId();
        $publishableKey = $this->settings[$this->liveMode . 'publishable_key'];

        $this->set('publishableKey', $publishableKey);
        $this->set('sessionId', $sessionId);
        $this->set('exculdeMainHeaderDiv', true);
        $this->_template->render(true, false);
    }

    private function convertInPaisa($amount)
    {
        $amount = number_format($amount, 2, '.', '');
        return $amount * 100;
    }
    
    public function paymentStatus()
    {
        if (false === $this->stripeConnect->init()) {
            $this->setErrorAndRedirect();
        }

        $this->settings = $this->stripeConnect->getKeys();

        $payload = @file_get_contents('php://input');
        $payload = json_decode($payload, true);
        
        // $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        if ($payload['type'] == "payment_intent.succeeded") {
            $intent = $payload['data']['object'];
            $intentId = $intent['id'];
            $charges = $intent['charges']['data'];

            $transferIdsArr = [];

            $message = '';
            foreach ($charges as $charge) {
                $message .= 'Id: ' . $charge['id'] . "&";
                $message .= 'Object: ' . $charge['object'] . "&";
                $message .= 'Amount: ' . $charge['amount'] . "&";
                $message .= 'Amount Refunded: ' . $charge['amount_refunded'] . "&";
                $message .= 'Application Fee: ' . $charge['application_fee'] . "&";
                $message .= 'Balance Transaction: ' . $charge['balance_transaction'] . "&";
                $message .= 'Captured: ' . $charge['captured'] . "&";
                $message .= 'Created: ' . $charge['created'] . "&";
                $message .= 'Currency: ' . $charge['currency'] . "&";
                $message .= 'Customer: ' . $charge['customer'] . "&";
                $message .= 'Description: ' . $charge['description'] . "&";
                $message .= 'Destination: ' . $charge['destination'] . "&";
                $message .= 'Dispute: ' . $charge['dispute'] . "&";
                $message .= 'Failure Code: ' . $charge['failure_code'] . "&";
                $message .= 'Failure Message: ' . $charge['failure_message'] . "&";
                $message .= 'Invoice: ' . $charge['invoice'] . "&";
                $message .= 'Livemode: ' . $charge['livemode'] . "&";
                $message .= 'Paid: ' . $charge['paid'] . "&";
                $message .= 'Receipt Email: ' . $charge['receipt_email'] . "&";
                $message .= 'Receipt Number: ' . $charge['receipt_number'] . "&";
                $message .= 'Refunded: ' . $charge['refunded'] . "&";
                $message .= 'Statement Descriptor: ' . $charge['statement_descriptor'] . "&";
                $message .= 'Status: ' . $charge['status'] . "& \n\n";

                $transferIdsArr[$charge['destination']] = $charge['transfer'];
            }

            $orderInfo = explode('-', $charges[0]['statement_descriptor']);
            $orderId = $orderInfo[0];
            
            /* Recording Payment in DB */
            $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);

            $paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();

            if (false === $orderPaymentObj->addOrderPayment($this->settings["plugin_code"], $intentId, $paymentAmount, Labels::getLabel("MSG_Received_Payment", $this->siteLangId), $message)) {
                $orderPaymentObj->addOrderPaymentComments($message);
            }

            $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();

            $orderObj = new Orders();
            $orderProducts = $orderObj->getChildOrders(array('order_id' => $orderInfo['id']), $orderInfo['order_type'], $orderInfo['order_language_id']);

            foreach ($orderProducts as $op) {
                $productSoldAmount = CommonHelper::orderProductAmount($op, 'NETAMOUNT');
                $amountToBePaidToSeller = CommonHelper::orderProductAmount($op, 'NETAMOUNT', false, User::USER_TYPE_SELLER);

                $accountId = User::getUserMeta($op['op_selprod_user_id'], 'stripe_account_id');
                // Credit sold product amount to seller wallet.
                $comments = Labels::getLabel('MSG_PRODUCT_SOLD._#{invoice-no}', $this->siteLangId);
                $comments = CommonHelper::replaceStringData($comments, ['{invoice-no}' => $op['op_invoice_number']]);
                Transactions::creditWallet($op['op_selprod_user_id'], Transactions::TYPE_PRODUCT_SALE, $productSoldAmount, $this->siteLangId, $comments, $op['op_id']);
                
                // Debit sold product amount to seller wallet.
                $txnId = isset($transferIdsArr[$accountId]) ? $transferIdsArr[$accountId] : '';
                $comments = Labels::getLabel('MSG_TRANSFERED_TO_{account-id}_ACCOUNT._TRANSFER_ID_:_{txn-id}', $this->siteLangId);
                $comments = CommonHelper::replaceStringData($comments, ['{account-id}' => $accountId, '{txn-id}' => $txnId]);
                Transactions::debitWallet($op['op_selprod_user_id'], Transactions::TYPE_PRODUCT_SALE, $productSoldAmount, $this->siteLangId, $comments, $op['op_id'], $txnId);

                $restAmountToBePaid = $amountToBePaidToSeller - $productSoldAmount;

                if (0 < $restAmountToBePaid) {
                    $comments = Labels::getLabel('MSG_PENDING_AMOUNT_FROM_#{invoice-no}', $this->siteLangId);
                    $comments = CommonHelper::replaceStringData($comments, ['{invoice-no}' => $op['op_invoice_number']]);

                    Transactions::creditWallet($op['op_selprod_user_id'], Transactions::TYPE_TRANSFER_TO_THIRD_PARTY_ACCOUNT, $restAmountToBePaid, $this->siteLangId, $comments, $op['op_id']);

                    $charge = [
                        'amount' => $this->convertInPaisa($restAmountToBePaid),
                        'currency' => $orderInfo['order_currency_code'],
                        'destination' => $accountId,
                        'transfer_group' => $op['op_invoice_number'],
                        'description' => $comments,
                        'metadata' => [
                            'op_id' => $op['op_id']
                        ]
                    ];
        
                    if (false === $this->stripeConnect->doTransfer($charge)) {
                        $this->setErrorAndRedirect();
                    }

                    $resp = $this->stripeConnect->getResponse();
                    if (empty($resp->id)) {
                        continue;
                    }

                    $comments = Labels::getLabel('MSG_PENDING_DISCOUNT_AMOUNT_CREDITED_TO_YOUR_{account-name}._ACCOUNT_ADDRESS_:_{account-address}', $this->siteLangId);
                    $comments = CommonHelper::replaceStringData($comments, ['{account-name}' => self::KEY_NAME, '{account-address}' => $accountId]);

                    Transactions::debitWallet($op['op_selprod_user_id'], Transactions::TYPE_TRANSFER_TO_THIRD_PARTY_ACCOUNT, $restAmountToBePaid, $this->siteLangId, $comments, $op['op_id'], $resp->id);
                }
            }
        } elseif ($payload['type'] == "payment_intent.payment_failed") {
            $intent = $payload['data']['object'];
            $intentId = $intent['id'];

            $error_message = $intent['last_payment_error'] ? $intent['last_payment_error']['message'] : "";
            
            $orderInfo = explode('-', $charges[0]['statement_descriptor']);
            $orderId = $orderInfo[0];

            /* Recording Payment in DB */
            $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);

            $orderPaymentObj->addOrderPaymentComments($error_message);
        }
    }
}
