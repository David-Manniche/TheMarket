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
        $this->init($action);
    }

    public function init()
    {
        $error = '';
        if (false === PluginHelper::includePlugin(self::KEY_NAME, 'payment-methods', $this->siteLangId, $error)) {
            $this->setErrorAndRedirect($error);
        }

        $this->stripeConnect = new StripeConnect($this->siteLangId);

        if (false === $this->stripeConnect->init()) {
            $this->setStripeErrorAndRedirect();
        }

        if (!empty($this->stripeConnect->getError())) {
            $this->setStripeErrorAndRedirect();
        }

        $this->settings = $this->stripeConnect->getKeys();

        if (isset($this->settings['env']) && applicationConstants::YES == $this->settings['env']) {
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
        } elseif ($orderInfo && $orderInfo["order_is_paid"] != Orders::ORDER_IS_PENDING) {
            $cancelUrl = CommonHelper::getPaymentCancelPageUrl();
            if ($orderInfo['order_type'] == Orders::ORDER_WALLET_RECHARGE) {
                $cancelUrl = CommonHelper::getPaymentFailurePageUrl();
            }

            $data = [
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                'success_url' => CommonHelper::generateFullUrl(self::KEY_NAME . 'Pay', 'distribute'),
                'cancel_url' => $cancelUrl,
                'line_items' => [],
            ];

            foreach ($orderProducts as $op) {
                $shippingCost = CommonHelper::orderProductAmount($op, 'SHIPPING');
                $volumeDiscount = CommonHelper::orderProductAmount($op, 'VOLUME_DISCOUNT');
                $total = CommonHelper::orderProductAmount($op, 'cart_total') + $shippingCost + $volumeDiscount;

                $priceData = [
                    'unit_amount' => $total,
                    'currency' => $orderInfo['order_currency_code'],
                    'product_data' => [
                        'name' => $op['op_selprod_title'],
                        'metadata' => [
                            'id' => $op['op_id']
                        ]
                    ]
                ];

                // CommonHelper::printArray($priceData, true);

                if (false === $this->stripeConnect->createPriceObject($priceData)) {
                    $this->setErrorAndRedirect();
                }

                $data['line_items'][] = [
                    'price' => $this->stripeConnect->getPriceId(),
                    'quantity' => $op['op_qty']
                ];
            }

            if (false === $this->stripeConnect->initiateSession($data)) {
                $this->setErrorAndRedirect();
            }

            $sessionId = $this->stripeConnect->getSessionId();
            $publishableKey = $this->settings[$this->liveMode . 'publishable_key'];

            $this->set('publishableKey', $publishableKey);
            $this->set('sessionId', $sessionId);
            $this->_template->render(true, false);
        }
        die('here');
        $message = Labels::getLabel('MSG_INVALID_ORDER._ALREADY_PAID_OR_CANCELLED', $this->siteLangId);
        LibHelper::exitWithError($message, false, true);
        CommonHelper::redirectUserReferer();
    }

    public function distribute()
    {
        CommonHelper::printArray(FatApp::getPostedData(), true);
    }

    private function doPayment($payment_amount, $orderInfo)
    {
        $this->paymentSettings = $this->getPaymentSettings();
        if ($payment_amount == null || !$this->paymentSettings || $orderInfo['id'] == null) {
            return false;
        }
        $checkPayment = false;
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            try {
                $stripeToken = FatApp::getPostedData('stripeToken', FatUtility::VAR_STRING, '');

                if (empty($stripeToken)) {
                    $message = Labels::getLabel('MSG_The_Stripe_Token_was_not_generated_correctly', $this->siteLangId);
                    if (true === MOBILE_APP_API_CALL) {
                        FatUtility::dieJsonError($message);
                    }
                    throw new Exception($message);
                } else {
                    $stripe = array(
                    'secret_key' => $this->paymentSettings['privateKey'],
                    'publishable_key' => $this->paymentSettings['publishableKey']
                    );
                    if (!empty(trim($this->paymentSettings['privateKey'])) && !empty(trim($this->paymentSettings['publishableKey']))) {
                        \Stripe\Stripe::setApiKey($stripe['secret_key']);
                    }

                    $customer = \Stripe\Customer::create(
                        array(
                          "email" => $orderInfo['customer_email'],
                          "source" => $stripeToken,
                        )
                    );
                    $charge = \Stripe\Charge::create(
                        array(
                        "customer" => $customer->id,
                        'amount' => $payment_amount,
                        'currency' => $this->systemCurrencyCode,
                        )
                    );
                    $charge = $charge->__toArray();

                    if (isset($charge['status'])) {
                        if (strtolower($charge['status']) == 'succeeded') {
                            $message = '';
                            $message .= 'Id: ' . (string)$charge['id'] . "&";
                            $message .= 'Object: ' . (string)$charge['object'] . "&";
                            $message .= 'Amount: ' . (string)$charge['amount'] . "&";
                            $message .= 'Amount Refunded: ' . (string)$charge['amount_refunded'] . "&";
                            $message .= 'Application Fee: ' . (string)$charge['application_fee'] . "&";
                            $message .= 'Balance Transaction: ' . (string)$charge['balance_transaction'] . "&";
                            $message .= 'Captured: ' . (string)$charge['captured'] . "&";
                            $message .= 'Created: ' . (string)$charge['created'] . "&";
                            $message .= 'Currency: ' . (string)$charge['currency'] . "&";
                            $message .= 'Customer: ' . (string)$charge['customer'] . "&";
                            $message .= 'Description: ' . (string)$charge['description'] . "&";
                            $message .= 'Destination: ' . (string)$charge['destination'] . "&";
                            $message .= 'Dispute: ' . (string)$charge['dispute'] . "&";
                            $message .= 'Failure Code: ' . (string)$charge['failure_code'] . "&";
                            $message .= 'Failure Message: ' . (string)$charge['failure_message'] . "&";
                            $message .= 'Invoice: ' . (string)$charge['invoice'] . "&";
                            $message .= 'Livemode: ' . (string)$charge['livemode'] . "&";
                            $message .= 'Paid: ' . (string)$charge['paid'] . "&";
                            $message .= 'Receipt Email: ' . (string)$charge['receipt_email'] . "&";
                            $message .= 'Receipt Number: ' . (string)$charge['receipt_number'] . "&";
                            $message .= 'Refunded: ' . (string)$charge['refunded'] . "&";
                            $message .= 'Shipping: ' . (string)$charge['shipping'] . "&";
                            $message .= 'Statement Descriptor: ' . (string)$charge['statement_descriptor'] . "&";
                            $message .= 'Status: ' . (string)$charge['status'] . "&";
                            /* Recording Payment in DB */
                            $orderPaymentObj = new OrderPayment($orderInfo['id']);
                            $orderPaymentObj->addOrderPayment($this->paymentSettings["pmethod_name"], $charge['id'], ($payment_amount / 100), Labels::getLabel("MSG_Received_Payment", $this->siteLangId), $message);
                            /* End Recording Payment in DB */
                            $checkPayment = true;

                            if (false === MOBILE_APP_API_CALL) {
                                FatApp::redirectUser(CommonHelper::generateUrl('custom', 'paymentSuccess', array($orderInfo['id'])));
                            }
                        } else {
                            $orderPaymentObj->addOrderPaymentComments($message);
                            if (false === MOBILE_APP_API_CALL) {
                                FatApp::redirectUser(CommonHelper::generateUrl('custom', 'paymentFailed'));
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                $this->error = $e->getMessage();
            }
        }
        return $checkPayment;
    }
}
