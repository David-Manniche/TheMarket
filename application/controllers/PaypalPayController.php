<?php

class PaypalPayController extends PaymentController
{
    public const KEY_NAME = "Paypal";
    private $externalLibUrl = '';
    
    /**
     * __construct
     *
     * @param  string $action
     * @return void
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
        $this->init();
    }
    
    /**
     * allowedCurrenciesArr
     *
     * @return array
     */
    protected function allowedCurrenciesArr(): array
    {
        return [
            "AUD", "BRL", "CAD", "CZK", "DKK", "EUR", "HKD", "HUF", "INR", "ILS", "JPY", "MYR", "MXN", "TWD", "NZD", "NOK", "PHP", "PLN", "GBP", "RUB", "SGD", "SEK", "CHF", "THB", "USD"
        ];
    }
    
    /**
     * init
     *
     * @return void
     */
    private function init(): void
    {
        $this->userId = UserAuthentication::getLoggedUserId();
        if (false === $this->plugin->init($this->userId)) {
            $this->setErrorAndRedirect($this->plugin->getError(), FatUtility::isAjaxCall());
        }

        $this->settings = $this->plugin->getSettings();
        $this->clientId = 0 < $this->settings['env'] ? $this->settings['live_client_id'] : $this->settings['client_id'];
        $this->externalLibUrl = 'https://www.paypal.com/sdk/js?client-id=' . $this->clientId . '&currency=' . $this->systemCurrencyCode;
    }
    
    /**
     * charge
     *
     * @param  string $orderId
     * @return void
     */
    public function charge($orderId)
    {
        if ($orderId == '') {
            $msg = Labels::getLabel('MSG_Invalid_Access', $this->siteLangId);
            $this->setErrorAndRedirect($msg, FatUtility::isAjaxCall());
        }

        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
        $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();
        if (!empty($orderInfo) && $orderInfo["order_is_paid"] != Orders::ORDER_IS_PENDING) {
            $msg = Labels::getLabel('MSG_INVALID_ORDER_PAID_CANCELLED', $this->siteLangId);
            $this->setErrorAndRedirect($msg, FatUtility::isAjaxCall());
        }
        $this->set('orderInfo', $orderInfo);
        $this->set('paymentAmount', $paymentAmount);
        $this->set('exculdeMainHeaderDiv', true);
        $this->set('externalLibUrl', $this->externalLibUrl);
        if (FatUtility::isAjaxCall()) {
            $json['html'] = $this->_template->render(false, false, 'paypal-pay/charge.php', true, false);
            FatUtility::dieJsonSuccess($json);
        }
        $this->_template->render(true, false);
    }
    
    /**
     * createOrder
     *
     * @param  string $orderId
     * @return string json
     */
    public function createOrder(string $orderId)
    {
        if (false === $this->plugin->createOrder($orderId)) {
            $error = $this->plugin->getError();
            $msg = is_array($error) && isset($error['error']) ? $error['error'] . ' : ' . $error['error_description'] : $error;
            $this->setErrorAndRedirect($msg, true);
        }
        $order = $this->plugin->getResponse();
        echo json_encode($order->result, JSON_PRETTY_PRINT);
    }
    
    /**
     * captureOrder
     *
     * @param  mixed $paypalOrderId
     * @return string json
     */
    public function captureOrder(string $paypalOrderId)
    {
        //=== Save order either by retrieving order from paypal OR the order we still have in session
        if (false === $this->plugin->captureOrder($paypalOrderId)) {
            $error = $this->plugin->getError();
            $msg = is_array($error) && isset($error['error']) ? $error['error'] . ' : ' . $error['error_description'] : $error;
            $this->setErrorAndRedirect($msg, true);
        }
        $order = $this->plugin->getResponse();
        echo json_encode($order->result, JSON_PRETTY_PRINT);
    }
    
    /**
     * callback
     *
     * @param  string $orderId
     * @return void
     */
    public function callback(string $orderId)
    {
        $post = FatApp::getPostedData();
        $purchaseUnit = isset($post['purchase_units']) ? current($post['purchase_units']) : [];

        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
        
        $capturePayment = isset($purchaseUnit['payments']['captures']) ? current($purchaseUnit['payments']['captures']) : [];
        $paidAmountCurrency = isset($capturePayment['amount']['currency_code']) ? $capturePayment['amount']['currency_code'] : '';
        $paidAmount = isset($capturePayment['amount']['value']) ? $capturePayment['amount']['value'] : [];

        if (empty($purchaseUnit) || $purchaseUnit['reference_id'] != $orderId || empty($capturePayment) || $paidAmountCurrency != $this->systemCurrencyCode || $paidAmount != $paymentAmount) {
            $msg = Labels::getLabel("MSG_INVALID_PAYMENT", $this->sitelangId);
            $this->setErrorAndRedirect($msg, true);
        }
        
        if ('COMPLETED' != $capturePayment['status']) {
            $msg = Labels::getLabel("MSG_PAYMENT_FAILED_:_{STATUS}", $this->siteLangId);
            $msg = CommonHelper::replaceStringData($msg, ['{STATUS}' => $capturePayment['status']]);
            $orderPaymentObj->addOrderPaymentComments($msg);
            if (false === MOBILE_APP_API_CALL) {
                $this->setErrorAndRedirect($msg, true);
            }
        }

        $message = 'Paypal Order Id: ' . (string) $post['id'] . "&";
        $message .= 'Paypal Order Payment Capture Id: ' . (string) $capturePayment['id'] . "&";
        $message .= 'Amount: ' . (string) $paidAmount . "&";
        $message .= 'Currency: ' . (string) $paidAmountCurrency . "&";
        $message .= 'Status: ' . (string) $capturePayment['status'] . "&";
        /* Recording Payment in DB */
        $orderPaymentObj->addOrderPayment(self::KEY_NAME, $post['id'], $paymentAmount, Labels::getLabel("MSG_RECEIVED_PAYMENT", $this->siteLangId), $message);
        /* End Recording Payment in DB */
        $json['redirecUrl'] = UrlHelper::generateUrl('custom', 'paymentSuccess', array($orderId));
        FatUtility::dieJsonSuccess($json);
    }
    
    /**
     * getExternalLibraries
     *
     * @return void
     */
    public function getExternalLibraries()
    {
        $json['libraries'] = [$this->externalLibUrl];
        FatUtility::dieJsonSuccess($json);
    }
}
