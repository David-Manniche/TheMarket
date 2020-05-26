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
    }

    private function includePlugin()
    {
        $error = '';
        if (false === PluginHelper::includePlugin(self::KEY_NAME, 'payment-methods', $this->siteLangId, $error)) {
            $this->setErrorAndRedirect($error);
        }

        $this->stripeConnect = new StripeConnect($this->siteLangId);

        $this->settings = $this->stripeConnect->getKeys();

        if (isset($this->settings['env']) && applicationConstants::YES == $this->settings['env']) {
            $this->liveMode = "live_";
        }
    }

    public function init()
    {
        $this->includePlugin();

        if (false === $this->stripeConnect->init()) {
            $this->setErrorAndRedirect();
        }

        if (!empty($this->stripeConnect->getError())) {
            $this->setErrorAndRedirect();
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
        } elseif ($orderInfo && $orderInfo["order_is_paid"] == Orders::ORDER_IS_PENDING) {
            $cancelUrl = CommonHelper::getPaymentCancelPageUrl();
            if ($orderInfo['order_type'] == Orders::ORDER_WALLET_RECHARGE) {
                $cancelUrl = CommonHelper::getPaymentFailurePageUrl();
            }

            if (false === $this->stripeConnect->createCustomerObject($orderInfo)) {
                $this->setErrorAndRedirect();
            }

            $customerId = $this->stripeConnect->getCustomerId();

            $orderFormattedData = $this->stripeConnect->formatCustomerDataFromOrder($orderInfo);
            $data = [
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                'success_url' => CommonHelper::generateFullUrl('custom', 'paymentSuccess', [$orderInfo['id']]),
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
                    'unit_amount' => $this->formatPayableAmount($singleItemPrice),
                    'currency' => $orderInfo['order_currency_code'],
                    'product_data' => [
                        'name' => $op['op_selprod_title'],
                        'metadata' => [
                            'id' => $op['op_id']
                        ]
                    ],
                    'nickname' => Labels::getLabel('LBL_SHIPPING_COST_AND_TAX_CHARGES_INCLUDED', $this->siteLangId)
                ];
                // CommonHelper::printArray($priceData);
                if (false === $this->stripeConnect->createPriceObject($priceData)) {
                    $this->setErrorAndRedirect();
                }

                $data['line_items'][] = [
                    'price' => $this->stripeConnect->getPriceId(),
                    'quantity' => $op['op_qty']
                ];

                // No need of application_fee_amount as tranfer_data amount are mutually exclusive.
                // $data['payment_intent_data']['application_fee_amount'] = $this->formatPayableAmount($op['op_commission_charged']);
                
                $data['payment_intent_data']['statement_descriptor'] = $op['op_invoice_number'];

                /*$accountId = User::getUserMeta($op['op_selprod_user_id'], 'stripe_account_id');
                if (!empty($accountId)) {
                    $data['payment_intent_data']['transfer_data'] = [
                        'destination' => $accountId,
                        'amount' => $this->formatPayableAmount($netAmount - $op['op_commission_charged'])
                    ];
                }*/
            }
            /*CommonHelper::printArray($orderInfo);
            CommonHelper::printArray($orderProducts, true);*/

            if (false === $this->stripeConnect->initiateSession($data)) {
                $this->setErrorAndRedirect();
            }

            $sessionId = $this->stripeConnect->getSessionId();
            $publishableKey = $this->settings[$this->liveMode . 'publishable_key'];

            $this->set('publishableKey', $publishableKey);
            $this->set('sessionId', $sessionId);
            $this->set('exculdeMainHeaderDiv', true);
            $this->_template->render(true, false);
        } else {
            $message = Labels::getLabel('MSG_INVALID_ORDER._ALREADY_PAID_OR_CANCELLED', $this->siteLangId);
            LibHelper::exitWithError($message, false, true);
            CommonHelper::redirectUserReferer();
        }
    }

    private function formatPayableAmount($amount)
    {
        $amount = number_format($amount, 2, '.', '');
        return $amount * 100;
    }

    public function distribute()
    {   
        $this->includePlugin();

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        $data = [
            'payload' => $payload,
            'sig_header' => $sig_header
        ];
        
        if ($this->stripeConnect->createWebhookEvent($data)) {
            $this->setErrorAndRedirect();
        }

        $event = $this->stripeConnect->getWebhookEvent();
        if ($event->type == "order.payment_succeeded") {
            $orderResp = $event->data->object;
            $metaData = $orderResp->metadata;
            $charge = $orderResp->metadata;

            $orderId = $metaData->orderId;
            $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
            $paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
            $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();

            $orderObj = new Orders();
            $orderProducts = $orderObj->getChildOrders(array('order_id' => $orderInfo['id']), $orderInfo['order_type'], $orderInfo['order_language_id']);

            foreach ($orderProducts as $op) {
                $accountId = User::getUserMeta($op['op_selprod_user_id'], 'stripe_account_id');
                if (empty($accountId)) {
                    continue;
                }
                
                $netAmount = CommonHelper::orderProductAmount($op, 'NETAMOUNT');

                $data = [
                    'amount' => $this->formatPayableAmount($netAmount - $op['op_commission_charged']),
                    'currency' => $orderInfo['order_currency_code'],
                    'destination' => $accountId,
                    'transfer_group' => $orderId,
                ];

                if (false === $this->stripeConnect->doTransfer($data)) {
                    $this->setErrorAndRedirect();        
                }
            }
            $orderPaymentObj = new OrderPayment($orderInfo['id']);
            $orderPaymentObj->addOrderPayment($this->settings["plugin_name"], $orderResp->id, $paymentAmount, Labels::getLabel("MSG_Received_Payment", $this->siteLangId), $message);
        } elseif ($event->type == "payment_intent.succeeded") {
            $intent = $event->data->object;
            $intentId = $intent->id;
        } elseif ($event->type == "payment_intent.payment_failed") {
            $intent = $event->data->object;
            $error_message = $intent->last_payment_error ? $intent->last_payment_error->message : "";
            $this->setErrorAndRedirect($error_message);
        }

        EmailHandler::sendSmtpEmail('satbir.kaushik@fatbit.in', 'Stripe Payment Response', json_encode($event));
    }
}
