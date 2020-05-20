<?php

class StripeConnectPayController extends PaymentController
{
    public const KEY_NAME = 'StripeConnect';
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
            $checkPayment = $this->doPayment($payableAmount, $orderInfo);
            $frm = $this->getPaymentForm($orderId);
            $this->set('frm', $frm);
            if ($checkPayment) {
                $this->set('success', true);
                if (true === MOBILE_APP_API_CALL) {
                    $this->_template->render();
                }
            } else {
                if (true === MOBILE_APP_API_CALL) {
                    $message = Labels::getLabel('MSG_Payment_Failed', $this->siteLangId);
                    FatUtility::dieJsonError($message);
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
