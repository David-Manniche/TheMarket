<?php

class EbsPayController extends PaymentController
{
    public const KEY_NAME = "Ebs";
    private $error = false;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->init();
    }

    protected function allowedCurrenciesArr()
    {
        return [
            'INR', 'USD', 'GBP', 'Eur', 'AED', 'QAR', 'OMR', 'CAD', 'HKD', 'SGD', 'AUD'
        ];
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

        $ebs = array(
        'account_id' => trim($this->settings['accountId']),
        'secret_key' => trim($this->settings['secretKey'])
        );
        $this->set('ebs', $ebs);

        if (!strlen(trim($ebs['account_id'])) > 0 && strlen(trim($ebs['secret_key'])) > 0) {
            $this->error = Labels::getLabel('MSG_PAYMENT_GATEWAY_SETUP_ERROR', $this->siteLangId);
        }

        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
        $payableAmount = $this->formatPayableAmount($paymentAmount);
        $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();

        if (!$orderInfo['id']) {
            FatUtility::exitWithErrorCode(404);
        } elseif ($orderInfo && $orderInfo["order_is_paid"] == Orders::ORDER_IS_PENDING) {
            $frm = $this->getPaymentForm($orderId);
            $this->set('frm', $frm);
            $this->set('success', true);
        } else {
            $this->error = Labels::getLabel('MSG_INVALID_ORDER_PAID_CANCELLED', $this->siteLangId);
        }
        $this->set('paymentAmount', $paymentAmount);
        $this->set('orderInfo', $orderInfo);
        if ($this->error) {
            $this->set('error', $this->error);
        }

        $cancelBtnUrl = CommonHelper::getPaymentCancelPageUrl();
        if ($orderInfo['order_type'] == Orders::ORDER_WALLET_RECHARGE) {
            $cancelBtnUrl = CommonHelper::getPaymentFailurePageUrl();
        }
        $this->set('cancelBtnUrl', $cancelBtnUrl);
        $this->set('exculdeMainHeaderDiv', true);
        if (FatUtility::isAjaxCall()) {
            $json['html'] = $this->_template->render(false, false, 'ebs-pay/charge.php', true, false);
            FatUtility::dieJsonSuccess($json);
        }
        $this->_template->render(true, false);
    }

    private function formatPayableAmount($amount = null)
    {
        if ($amount == null) {
            return false;
        }
        $amount = number_format($amount, 2, '.', '');
        return $amount * 100;
    }
    
    private function getPaymentForm($orderId)
    {
        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
        $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();

        $frm = new Form('frmPaymentForm', array('id' => 'frmPaymentForm', 'action' => 'https://secure.ebs.in/pg/ma/sale/pay/', 'class' => "form form--normal"));
        if (FatApp::getConfig('CONF_TRANSACTION_MODE', FatUtility::VAR_BOOLEAN, false) == true) {
            $mode = "LIVE";
        } else {
            $mode = "TEST";
        }
        $frm->addHiddenField('', 'mode', $mode);
        $frm->addHiddenField('', 'account_id', $this->settings["accountId"]);
        $frm->addHiddenField('', 'reference_no', $orderId);

        $frm->addHiddenField('', 'amount', $paymentAmount);
        $order_payment_gateway_description = sprintf(Labels::getLabel('M_Order_Payment_Gateway_Description', $this->siteLangId), $orderInfo["site_system_name"], $orderInfo['invoice']);
        $frm->addHiddenField('', 'description', $order_payment_gateway_description);
        $frm->addHiddenField('', 'name', $orderInfo["customer_name"]);
        $frm->addHiddenField('', 'address', $orderInfo["customer_billing_address_1"] . ' ' . $orderInfo["customer_billing_address_2"]);
        $frm->addHiddenField('', 'city', $orderInfo["customer_billing_city"]);
        $frm->addHiddenField('', 'state', $orderInfo["customer_billing_state"]);
        $frm->addHiddenField('', 'postal_code', $orderInfo["customer_billing_postcode"]);
        $frm->addHiddenField('', 'country', $orderInfo["customer_billing_country_code"]);
        $frm->addHiddenField('', 'email', $orderInfo['customer_email']);
        $frm->addHiddenField('', 'phone', $orderInfo['customer_billing_phone']);

        $frm->addHiddenField('', 'ship_name', $orderInfo["customer_shipping_name"]);
        $frm->addHiddenField('', 'ship_address', $orderInfo["customer_shipping_address_1"] . ' ' . $orderInfo["customer_shipping_address_2"]);
        $frm->addHiddenField('', 'ship_city', $orderInfo["customer_shipping_city"]);
        $frm->addHiddenField('', 'ship_state', $orderInfo["customer_shipping_state"]);
        $frm->addHiddenField('', 'ship_postal_code', $orderInfo["customer_shipping_postcode"]);
        $frm->addHiddenField('', 'ship_country', $orderInfo["customer_shipping_country_code"]);
        $frm->addHiddenField('', 'ship_phone', $orderInfo['customer_shipping_phone']);
        $return_url = UrlHelper::generateFullUrl('ebsPay', 'callback');
        $string = $this->settings["secretKey"] . "|" . $this->settings["accountId"] . "|" . $paymentAmount . "|" . $orderId . "|" . $return_url . "|" . $mode;
        /* echo $string; die; */
        $secure_hash = md5($string);

        $frm->addHiddenField('', 'secure_hash', $secure_hash);
        $frm->addHiddenField('', 'return_url', $return_url . '?DR={DR}');
        $frm->setJsErrorDisplay('afterfield');
        return $frm;
    }

    public function callback()
    {
        $get = FatApp::getQueryStringData();
        if (isset($get['DR'])) {
            include_once CONF_INSTALLATION_PATH . 'library/payment-plugins/ebs/Rc43.php';
            
            $secret_key = $this->settings["secretKey"];
            $DR = preg_replace("/\s/", "+", $get['DR']);
            $rc4 = new Crypt_RC4($secret_key);
            $QueryString = base64_decode($DR);
            $rc4->decrypt($QueryString);
            $QueryString = explode('&', $QueryString);
            $response = array();
            foreach ($QueryString as $param) {
                $param = explode('=', $param);
                $response[$param[0]] = urldecode($param[1]);
            }

            $data['response'] = $response;
            $orderId = (isset($response['MerchantRefNo'])) ? $response['MerchantRefNo'] : 0;
            $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
            $paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
            if ($response['ResponseCode'] == '0') {
                if ($orderPaymentObj->addOrderPayment($this->settings["plugin_code"], $response['TransactionID'], $paymentAmount, Labels::getLabel("LBL_Received_Payment", $this->siteLangId), serialize($response))) {
                }
                FatApp::redirectUser(UrlHelper::generateUrl('custom', 'paymentSuccess', array($orderId)));
            } else {
                $orderPaymentObj->addOrderPaymentComments(serialize($response));
                FatApp::redirectUser(CommonHelper::getPaymentFailurePageUrl());
            }
        }
    }
}
