<?php

class TransferbankPayController extends PaymentController
{
    public const KEY_NAME = "TransferBank";

    public function __construct($action)
    {
        parent::__construct($action);
        $this->init();
    }

    protected function allowedCurrenciesArr()
    {
        return [$this->systemCurrencyCode];
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
        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
        $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();
        if (!$orderInfo['id']) {
            FatUtility::exitWIthErrorCode(404);
        } elseif ($orderInfo && $orderInfo["order_is_paid"] == Orders::ORDER_IS_PENDING) {
            $frm = $this->getPaymentForm($orderId);
            $this->set('frm', $frm);
            $this->set('paymentAmount', $paymentAmount);
        } else {
            $this->set('error', Labels::getLabel('MSG_INVALID_ORDER_PAID_CANCELLED', $this->siteLangId));
        }
        $this->set('orderInfo', $orderInfo);
        $this->set('exculdeMainHeaderDiv', true);
        $this->_template->render(true, false);
    }

    public function send($orderId)
    {
        $orderPaymentObj = new OrderPayment($orderId, $this->siteLangId);
        $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();
        if ($orderInfo) {
            $cartObj = new Cart();
            $cartObj->clear();
            $cartObj->updateUserCart();
            $comment = Labels::getLabel('MSG_PAYMENT_INSTRUCTIONS', $this->siteLangId) . "\n\n";
            $comment .= $this->settings["bank_details"] . "\n\n";
            $comment .= Labels::getLabel('MSG_PAYMENT_NOTE', $this->siteLangId);
            $orderPaymentObj->addOrderPaymentComments($comment);
            $json['redirect'] = CommonHelper::generateUrl('custom', 'paymentSuccess', array($orderId));
        } else {
            $json['error'] = 'Invalid Request.';
        }
        echo json_encode($json);
    }

    private function getPaymentForm($orderId)
    {
        $frm = new Form('frmPaymentForm', array('id' => 'frmPaymentForm', 'action' => CommonHelper::generateUrl('TransferbankPay', 'send', array($orderId)), 'class' => "form form--normal"));

        $frm->addHtml('', 'htmlNote', Labels::getLabel('MSG_Bank_Transfer_Note', $this->siteLangId));
        $frm->addHtml('', 'htmlNote', '<div class="bank--details">' . nl2br($this->settings["bank_details"]) . '</div>');
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Confirm_Order', $this->siteLangId), array('id' => 'button-confirm'));
        return $frm;
    }
}
