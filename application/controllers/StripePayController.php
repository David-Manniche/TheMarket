<?php

require_once CONF_INSTALLATION_PATH . 'library/payment-plugins/stripe/init.php';
class StripePayController extends PaymentController
{
    public const KEY_NAME = 'Stripe';

    private $error = false;

    private $paymentSettings = false;
   
    protected function allowedCurrenciesArr()
    {
        return [
            'USD', 'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BIF', 'BMD', 'BND', 'BOB', 'BRL', 'BSD', 'BWP', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY', 'COP', 'CRC', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EGP', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP', 'GEL', 'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'ISK', 'JMD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KRW', 'KYD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MOP', 'MRO', 'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SEK', 'SGD', 'SHP', 'SLL', 'SOS', 'SRD', 'STD', 'SZL', 'THB', 'TJS', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'UYU', 'UZS', 'VND', 'VUV', 'WST', 'XAF', 'XCD', 'XOF', 'XPF', 'YER', 'ZAR', 'ZMW'
        ];
    }

    public function charge($orderId)
    {
        if (empty(trim($orderId))) {
            $message = Labels::getLabel('MSG_Invalid_Access', $this->siteLangId);
            if (true === MOBILE_APP_API_CALL) {
                FatUtility::dieJsonError($message);
            }
            Message::addErrorMessage($message);
            CommonHelper::redirectUserReferer();
        }

        $this->paymentSettings = $this->getPaymentSettings();
        $stripe = array(
            'secret_key' => $this->paymentSettings['privateKey'],
            'publishable_key' => $this->paymentSettings['publishableKey']
        );
        $this->set('stripe', $stripe);

        if (!isset($this->paymentSettings['privateKey']) && !isset($this->paymentSettings['publishableKey'])) {
            Message::addErrorMessage(Labels::getLabel('STRIPE_INVALID_PAYMENT_GATEWAY_SETUP_ERROR', $this->siteLangId));
            CommonHelper::redirectUserReferer();
        }

        if (strlen(trim($this->paymentSettings['privateKey'])) > 0 && strlen(trim($this->paymentSettings['publishableKey'])) > 0) {
            if (strpos($this->paymentSettings['privateKey'], 'test') !== false || strpos($this->paymentSettings['publishableKey'], 'test') !== false) {
            }
            \Stripe\Stripe::setApiKey($stripe['secret_key']);
        } else {
            $this->error = Labels::getLabel('STRIPE_INVALID_PAYMENT_GATEWAY_SETUP_ERROR', $this->siteLangId);
        }

        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
        $payableAmount = $this->formatPayableAmount($paymentAmount);
        $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();

        if (!$orderInfo['id']) {
            if (true === MOBILE_APP_API_CALL) {
                $message = Labels::getLabel('MSG_Invalid_Access', $this->siteLangId);
                FatUtility::dieJsonError($message);
            }
            FatUtility::exitWithErrorCode(404);
        } elseif ($orderInfo && $orderInfo["order_is_paid"] == Orders::ORDER_IS_PENDING) {
            /* $checkPayment = $this->doPayment($payableAmount, $orderInfo); */
            $frm = $this->getPaymentForm($orderId);
            $this->set('frm', $frm);
            
            if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                $charge = $this->stripeAuthentication($orderId);
                if (isset($charge['id']) && $charge['id']) {
                    $payment_method = \Stripe\PaymentMethod::create([
                        'type' => 'card',
                          'card' => [
                            'number' => $_POST['cc_number'],
                            'exp_month' => $_POST['cc_expire_date_month'],
                            'exp_year' => $_POST['cc_expire_date_year'],
                            'cvc' => $_POST['cc_cvv'],
                          ],
                        ]);
                    $payment_method = $payment_method->__toArray();
                    
                    $this->set('order_id', $orderId);
                    $this->set('payment_intent_id', $charge['id']);
                    $this->set('payment_method_id', $payment_method['id']);
                    $this->set('client_secret', $charge['client_secret']);
                } else {
                    $this->error = Labels::getLabel('LBL_STRIPE_AUTHENTICATION_ERROR', $this->siteLangId);
                }
            }
        } else {
            $message = Labels::getLabel('MSG_INVALID_ORDER_PAID_CANCELLED', $this->siteLangId);
            if (true === MOBILE_APP_API_CALL) {
                FatUtility::dieJsonError($message);
            }
            $this->error = $message;
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
        $this->_template->render(true, false);
    }

    public function checkCardType()
    {
        $post = FatApp::getPostedData();
        $res = ValidateElement::ccNumber($post['cc']);
        echo json_encode($res);
        exit;
    }

    private function formatPayableAmount($amount = null)
    {
        if ($amount == null) {
            return false;
        }
        $amount = number_format($amount, 2, '.', '');
        return $amount * 100;
    }

    private function getPaymentSettings()
    {
        $pluginSetting = new PluginSetting(0, self::KEY_NAME);
        return $pluginSetting->get($this->siteLangId);
    }

    private function getPaymentForm($orderId)
    {
        $frm = new Form('frmPaymentForm', array('id' => 'frmPaymentForm', 'action' => CommonHelper::generateUrl('StripePay', 'charge', array($orderId)), 'class' => "form form--normal"));
        $frm->addRequiredField(Labels::getLabel('LBL_ENTER_CREDIT_CARD_NUMBER', $this->siteLangId), 'cc_number');
        $frm->addRequiredField(Labels::getLabel('LBL_CARD_HOLDER_NAME', $this->siteLangId), 'cc_owner');
        $data['months'] = applicationConstants::getMonthsArr($this->siteLangId);
        $today = getdate();
        $data['year_expire'] = array();
        for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
            $data['year_expire'][strftime('%Y', mktime(0, 0, 0, 1, 1, $i))] = strftime('%Y', mktime(0, 0, 0, 1, 1, $i));
        }
        $frm->addSelectBox(Labels::getLabel('LBL_EXPIRY_MONTH', $this->siteLangId), 'cc_expire_date_month', $data['months'], '', array(), '');
        $frm->addSelectBox(Labels::getLabel('LBL_EXPIRY_YEAR', $this->siteLangId), 'cc_expire_date_year', $data['year_expire'], '', array(), '');
        $frm->addPasswordField(Labels::getLabel('LBL_CVV_SECURITY_CODE', $this->siteLangId), 'cc_cvv')->requirements()->setRequired();
        /* $frm->addCheckBox(Labels::getLabel('LBL_SAVE_THIS_CARD_FOR_FASTER_CHECKOUT',$this->siteLangId), 'cc_save_card','1'); */
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Pay_Now', $this->siteLangId));

        return $frm;
    }
    
    public function stripeAuthentication($orderId = 0)
    {
        $this->paymentSettings = $this->getPaymentSettings();
        if (!$this->paymentSettings) {
            $this->error =  Labels::getLabel('STRIPE_INVALID_PAYMENT_GATEWAY_SETUP_ERROR', $this->siteLangId);
        }
        
        $stripeToken = FatApp::getPostedData('stripeToken', FatUtility::VAR_STRING, '');
        
        if (empty($stripeToken)) {
            $message = Labels::getLabel('MSG_The_Stripe_Token_was_not_generated_correctly', $this->siteLangId);
            if (true === MOBILE_APP_API_CALL) {
                FatUtility::dieJsonError($message);
            }
            throw new Exception($message);
        }
        
        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
        $paymentAmount = $this->formatPayableAmount($paymentAmount);
        
        $stripe = array(
        'secret_key' => $this->paymentSettings['privateKey'],
        'publishable_key' => $this->paymentSettings['publishableKey']
        );
        
        $this->set('stripe', $stripe);
        
        if (!empty(trim($this->paymentSettings['privateKey'])) && !empty(trim($this->paymentSettings['publishableKey']))) {
            \Stripe\Stripe::setApiKey($stripe['secret_key']);
        }
        
        try {
            if (!empty(trim($this->paymentSettings['privateKey'])) && !empty(trim($this->paymentSettings['publishableKey']))) {
                \Stripe\Stripe::setApiKey($stripe['secret_key']);
                
                $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();
                
                $customer = \Stripe\Customer::create([
                            "email" => $orderInfo['customer_email'],
                            "source" => $_POST['stripeToken'],
                ]);
                $charge = \Stripe\PaymentIntent::create([
                            "customer" => $customer->id,
                            'amount' => $paymentAmount,
                            'currency' => $this->systemCurrencyCode,
                            'payment_method_types' => ['card'],
                            'payment_method_options' => array('card' => array('installments' => null,'request_three_d_secure' => 'any')),
                            'metadata'=>array('order_id'=>$orderId)
                            

                ]);
               
                $charge = $charge->__toArray();
                return $charge;
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
        
        if ($this->error) {
            return $this->error;
            Message::addErrorMessage($this->error);
            CommonHelper::redirectUserReferer();
        }
    }
    
    public function stripeSuccess()
    {
        $this->paymentSettings = $this->getPaymentSettings();
        $stripe = [
            'secret_key' => $this->paymentSettings['privateKey'],
            'publishable_key' => $this->paymentSettings['publishableKey']
        ];
       
        \Stripe\Stripe::setApiKey($stripe['secret_key']);

        $charge = \Stripe\PaymentIntent::retrieve(
            $_POST['payment_intent_id']
        );
        
        $charge = $charge->__toArray();
       
        $orderPaymentObj = new OrderPayment($_POST['order_id']);
       
        if (strtolower($charge['status']) == 'succeeded') {
            $message .= 'Id: ' . (string) $charge['charges']['data'][0]['id'] . "&";
            $message .= 'Object: ' . (string) $charge['charges']['data'][0]['object'] . "&";
            $message .= 'Amount: ' . (string) $charge['charges']['data'][0]['amount'] . "&";
            $message .= 'Amount Refunded: ' . (string) $charge['charges']['data'][0]['amount_refunded'] . "&";
            $message .= 'Application Fee: ' . (string) $charge['charges']['data'][0]['application_fee_amount'] . "&";
            $message .= 'Balance Transaction: ' . (string) $charge['balance_transaction'] . "&";
            $message .= 'Captured: ' . (string) $charge['charges']['data'][0]['captured'] . "&";
            $message .= 'Created: ' . (string) $charge['charges']['data'][0]['created'] . "&";
            $message .= 'Currency: ' . (string) $charge['charges']['data'][0]['currency'] . "&";
            $message .= 'Customer: ' . (string) $charge['charges']['data'][0]['customer'] . "&";
            $message .= 'Description: ' . (string) $charge['charges']['data'][0]['description'] . "&";
            $message .= 'Destination: ' . (string) $charge['charges']['data'][0]['destination'] . "&";
            $message .= 'Dispute: ' . (string) $charge['charges']['data'][0]['dispute'] . "&";
            $message .= 'Failure Code: ' . (string) $charge['charges']['data'][0]['failure_code'] . "&";
            $message .= 'Failure Message: ' . (string) $charge['charges']['data'][0]['failure_message'] . "&";
            $message .= 'Invoice: ' . (string) $charge['charges']['data'][0]['invoice'] . "&";
            $message .= 'Livemode: ' . (string) $charge['charges']['data'][0]['livemode'] . "&";
            $message .= 'Paid: ' . (string) $charge['paid'] . "&";
            $message .= 'Receipt Email: ' . (string) $charge['charges']['data'][0]['receipt_email'] . "&";
            $message .= 'Receipt Number: ' . (string) $charge['charges']['data'][0]['receipt_number'] . "&";
            $message .= 'Refunded: ' . (string) $charge['charges']['data'][0]['refunded'] . "&";
            $message .= 'Shipping: ' . (string) $charge['charges']['data'][0]['shipping'] . "&";
            $message .= 'Statement Descriptor: ' . (string) $charge['charges']['data'][0]['statement_descriptor'] . "&";
            $message .= 'Status: ' . (string) $charge['charges']['data'][0]['status'] . "&";
            /* Recording Payment in DB */
            /* $paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
            $payableAmount = $this->formatPayableAmount($paymentAmount); */
            $payment_amount = $charge['charges']['data'][0]['amount'];
            $orderPaymentObj->addOrderPayment($this->paymentSettings["pmethod_name"], $charge['id'], ($payment_amount / 100), Labels::getLabel("MSG_Received_Payment", $this->siteLangId), $message);
            /* End Recording Payment in DB */
            if (false === MOBILE_APP_API_CALL) {
                FatApp::redirectUser(CommonHelper::generateUrl('custom', 'paymentSuccess', array($_POST['order_id'])));
            }
        } else {
            $orderPaymentObj->addOrderPaymentComments($message);
            if (false === MOBILE_APP_API_CALL) {
                FatApp::redirectUser(CommonHelper::generateUrl('custom', 'paymentFailed'));
            }
        }
    }
}
