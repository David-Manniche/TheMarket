<?php

class TwocheckoutSettingsController extends PaymentMethodSettingsController
{
    public static function form($langId)
    {
        $frm = new Form('frmPaymentMethods');
        
        $paymentTypesArr = array(
        'HOSTED' => 'Hosted Checkout',
        'API' => 'Payment API'
        );
        $frm->addRadioButtons(Labels::getLabel('LBL_Payment_Type', $langId), 'payment_type', $paymentTypesArr, 'HOSTED', array('class' => 'box--scroller'));
        $frm->addRequiredField(Labels::getLabel('LBL_Seller_ID', $langId), 'sellerId');
        $frm->addRequiredField(Labels::getLabel('LBL_Publishable_Key', $langId), 'publishableKey');
        $frm->addRequiredField(Labels::getLabel('LBL_Private_Key', $langId), 'privateKey');
        $frm->addRequiredField(Labels::getLabel('LBL_Secret_Word', $langId), 'hashSecretWord');
        
        $frm->addHTML(
            'Remember',
            '&nbsp;',
            'In case of <strong>Hosted Checkout</strong>, Admin must set <strong>Direct Return (URL)</strong> to <strong>Header Redirect</strong> and 
		<strong>Approved URL</strong> to <strong>' . CommonHelper::generateFullUrl('twocheckout_pay', 'callback', array(), CONF_WEBROOT_URL) . '</strong> under <strong>2Checkout Accounts</strong> Section.<br/><br/>'
        );
        
        
        $frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $langId));
        return $frm;
    }
}
