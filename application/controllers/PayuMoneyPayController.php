<?php

class PayuMoneyPayController extends PaymentController
{
    public const KEY_NAME = "PayuMoney";

    public function __construct($action)
    {
        parent::__construct($action);
        $this->init();
    }

    protected function allowedCurrenciesArr()
    {
        return ['INR'];
    }
    
    private function init(): void
    {
        if (false === $this->plugin->validateSettings($this->siteLangId)) {
            $this->setErrorAndRedirect($this->plugin->getError());
        }

        $this->settings = $this->plugin->getSettings();
    }

    public function charge($orderId)
    {
        if (empty(trim($orderId))) {
            Message::addErrorMessage(Labels::getLabel('MSG_Invalid_Access', $this->siteLangId));
            CommonHelper::redirectUserReferer();
        }

        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
        $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();
        if (!$orderInfo['id']) {
            FatUtility::exitWIthErrorCode(404);
        } elseif ($orderInfo["order_payment_status"] == Orders::ORDER_PAYMENT_PENDING) {
            $frm = $this->getPaymentForm($orderId);
            $this->set('frm', $frm);
            $this->set('paymentAmount', $paymentAmount);
        } else {
            $this->set('error', Labels::getLabel('MSG_INVALID_ORDER_PAID_CANCELLED', $this->siteLangId));
        }
        $this->set('orderInfo', $orderInfo);
        $this->set('exculdeMainHeaderDiv', true);
        if (FatUtility::isAjaxCall()) {
            $json['html'] = $this->_template->render(false, false, 'payu-money-pay/charge-ajax.php', true, false);
            FatUtility::dieJsonSuccess($json);
        }
        $this->_template->render(true, false);
    }

    public function callback()
    {
        $post = FatApp::getPostedData();
        foreach ($post as $key => $value) {
            $request .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
        }
        $orderId = (isset($post['udf1'])) ? $post['udf1'] : 0;
        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $paymentGatewayCharge = $orderPaymentObj->getOrderPaymentGatewayAmount();
        $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();
        if ($orderInfo) {
            $orderPaymentGatewayDescription = sprintf(Labels::getLabel('MSG_Order_Payment_Gateway_Description', $this->siteLangId), $orderInfo["site_system_name"], $orderInfo['invoice']);
            switch ($post['status']) {
                case 'success':
                    $receiver_match = (strtolower($post['key']) == strtolower($this->settings['merchant_key']));
                    $total_paid_match = ((float)$post['amount'] == (float)$paymentGatewayCharge);
                    $hash_string = $this->settings["salt"] . "|" . $post["status"] . "||||||||||" . $post["udf1"] . "|" . $post["email"] . "|" . $post["firstname"] . "|" . $post["productinfo"] . "|" . $post["amount"] . "|" . $post["txnid"] . "|" . $post["key"];
                    $reverse_hash = strtolower(hash('sha512', $hash_string));
                    $reverse_hash_match = ($post['hash'] == $reverse_hash);
                    if ($receiver_match && $total_paid_match && $reverse_hash_match) {
                        $order_payment_status = 1;
                    }
                    if (!$receiver_match) {
                        $request .= "\n\n PAYUMONEY_NOTE :: RECEIVER MERCHANT MISMATCH! " . strtolower($post['key']) . "\n\n";
                    }
                    if (!$total_paid_match) {
                        $request .= "\n\n PAYUMONEY_NOTE :: TOTAL PAID MISMATCH! " . strtolower($post['amount']) . "\n\n";
                    }
                    if (!$reverse_hash_match) {
                        $request .= "\n\n PAYUMONEY_NOTE :: REVERSE HASH MISMATCH! " . strtolower($post['hash']) . "\n\n";
                    }
                    break;
            }
            if ($order_payment_status == 1) {
                $orderPaymentObj->addOrderPayment($this->settings["plugin_code"], $post["mihpayid"], $paymentGatewayCharge, Labels::getLabel("LBL_Received_Payment", $this->siteLangId), json_encode($post));
                FatApp::redirectUser(UrlHelper::generateUrl('custom', 'paymentSuccess', array($orderId)));
            } else {
                TransactionFailureLog::set(TransactionFailureLog::LOG_TYPE_CHECKOUT, $orderId, json_encode($post));
                $orderPaymentObj->addOrderPaymentComments($request);
                FatApp::redirectUser(CommonHelper::getPaymentFailurePageUrl());
            }
        } else {
            Message::addErrorMessage(Labels::getLabel('MSG_ERROR_INVALID_ACCESS', $this->siteLangId));
            FatApp::redirectUser(CommonHelper::getPaymentFailurePageUrl());
        }
    }

    private function getPaymentForm($orderId)
    {
        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $paymentGatewayCharge = $orderPaymentObj->getOrderPaymentGatewayAmount();
        if (FatApp::getConfig('CONF_TRANSACTION_MODE', FatUtility::VAR_BOOLEAN, false) == true) {
            $actionUrl = 'https://secure.payu.in/_payment';
        } else {
            $actionUrl = 'https://sandboxsecure.payu.in/_payment';
        }

        $frm = new Form('frmPayuMoney', array('id' => 'frmPayuMoney', 'action' => $actionUrl, 'class' => "form form--normal"));

        /* Retrieve Primary Info corresponding to your order */
        $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();
        $firstname = $orderInfo["customer_name"];
        $phone_number = $orderInfo["customer_phone"];
        $address_line_1 = $orderInfo["customer_billing_address_1"];
        $address_line_2 = $orderInfo["customer_billing_address_2"];
        $zip_code = $orderInfo["customer_billing_postcode"];
        $email = $orderInfo["customer_email"];
        $orderPaymentGatewayDescription = sprintf(Labels::getLabel('MSG_Order_Payment_Gateway_Description', $this->siteLangId), $orderInfo["site_system_name"], $orderInfo['invoice']);
        $txnid = $orderInfo["invoice"];

        $cancelBtnUrl = CommonHelper::getPaymentCancelPageUrl();
        if ($orderInfo['order_type'] == Orders::ORDER_WALLET_RECHARGE) {
            $cancelBtnUrl = CommonHelper::getPaymentFailurePageUrl();
        }

        $frm->addHiddenField('key', 'key', $this->settings["merchant_key"]);
        $frm->addHiddenField('txnid', 'txnid', $txnid);
        $frm->addHiddenField('amount', 'amount', $paymentGatewayCharge);
        $frm->addHiddenField('productinfo', 'productinfo', $orderPaymentGatewayDescription);
        $frm->addHiddenField('firstname', 'firstname', $firstname);
        $frm->addHiddenField('Lastname', 'Lastname', '');
        $frm->addHiddenField('Zipcode', 'Zipcode', $zip_code);
        $frm->addHiddenField('email', 'email', $email);
        $frm->addHiddenField('phone', 'phone', $phone_number);
        $frm->addHiddenField('surl', 'surl', UrlHelper::generateFullUrl('PayuMoneyPay', 'callback'));
        $frm->addHiddenField('furl', 'furl', UrlHelper::generateFullUrl('PayuMoneyPay', 'callback'));

        $frm->addHiddenField('curl', 'curl', $cancelBtnUrl);
        $key = $this->settings["merchant_key"];
        $amount = $paymentGatewayCharge;
        $salt = $this->settings["salt"];
        $udf1 = $orderId;
        $Hash = hash('sha512', $key . '|' . $txnid . '|' . $paymentGatewayCharge . '|' . $orderPaymentGatewayDescription . '|' . $firstname . '|' . $email . '|' . $udf1 . '||||||||||' . $salt);
        $frm->addHiddenField('hash', 'hash', $Hash);
        $frm->addHiddenField('udf1', 'udf1', $udf1);
        $frm->addHiddenField('Pg', 'Pg', 'CC');
        $frm->addHiddenField('address1', 'address1', $address_line_1);
        $frm->addHiddenField('address2', 'address2', $address_line_2);
        $frm->addHiddenField('city', 'city', $orderInfo["customer_billing_city"]);
        $frm->addHiddenField('country', 'country', $orderInfo["customer_billing_country"]);
        $frm->addHiddenField('state', 'state', $orderInfo["customer_billing_state"]);
        $frm->addHiddenField('custom_note', 'custom_note', Labels::getLabel('MSG_ORDER_CUSTOM_NOTE', $this->siteLangId));
        /* $frm->addHiddenField('api_version', 'api_version', 1);
        $frm->addHiddenField('service_provider', 'service_provider', 'payu_paisa'); */
        $frm->setJsErrorDisplay('afterfield');
        return $frm;
    }
}
