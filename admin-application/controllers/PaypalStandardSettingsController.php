<?php

class PaypalStandardSettingsController extends PaymentMethodSettingsController
{ 
    public static function form($langId)
    {
        $frm = new Form('frmPaymentMethods');
        $frm->addRequiredField(Labels::getLabel('LBL_Merchant_Email', $langId), 'merchant_email');
        
        $paymentGatewayStatus = Orders::getPaymentGatewayStatusArr($langId);
        $frm->addSelectBox(Labels::getLabel('LBL_Order_Status_(Initial)', $langId), 'order_status_initial', $paymentGatewayStatus)->requirement->setRequired(true);
        $frm->addSelectBox(Labels::getLabel('LBL_Order_Status_(Pending)', $langId), 'order_status_pending', $paymentGatewayStatus)->requirement->setRequired(true);
        $frm->addSelectBox(Labels::getLabel('LBL_Order_Status_(Processed)', $langId), 'order_status_processed', $paymentGatewayStatus)->requirement->setRequired(true);
        $frm->addSelectBox(Labels::getLabel('LBL_Order_Status_(Completed)', $langId), 'order_status_completed', $paymentGatewayStatus)->requirement->setRequired(true);
        $frm->addSelectBox(Labels::getLabel('LBL_Order_Status_(Others)', $langId), 'order_status_others', $paymentGatewayStatus)->requirement->setRequired(true);
        
        $frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $langId));
        return $frm;
    }
}
