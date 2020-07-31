<?php

class CashOnDeliverySettingsController extends PaymentMethodSettingsController
{
    public static function form($langId)
    {
        $frm = new Form('frmPaymentMethods');
        $fld = $frm->addTextArea(Labels::getLabel('LBL_COD_(cash_on_delivery)_Note', $langId), 'cod_note');
        $fld->html_after_field = '<small>' . Labels::getLabel('LBL_Please_enter_details_here', $langId) . '<small>';
        $frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $langId));
        return $frm;
    }
}
