<?php

class AvalaraTaxSettingsController extends TaxSettingsController
{
    public static function form($langId)
    {
        $frm = new Form('frmAvalaraTax');
        $frm->addTextBox(Labels::getLabel('LBL_Account_Number', $langId), 'account_number')->requirements()->setRequired(true);
        $frm->addTextBox(Labels::getLabel('LBL_License_Key', $langId), 'license_key')->requirements()->setRequired(true);
        $frm->addTextBox(Labels::getLabel('LBL_Company_Code', $langId), 'company_code')->requirements()->setRequired(true);
        $frm->addCheckBox(Labels::getLabel('LBL_Commit_Transaction', $langId), 'commit_transaction',applicationConstants::YES);
        $frm->addCheckBox(Labels::getLabel('LBL_Production_Mode', $langId), 'environment',applicationConstants::YES);    
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $langId));
        return $frm;
    }
}
