<?php
class AppleSignIn extends AddonSetting
{
    public function getSettingsForm()
    {
        $frm = new Form('frmAddons');
        $frm->addHiddenField('', 'keyName', get_class($this));
        $frm->addHiddenField('', 'addon_id');
        $frm->addRequiredField(Labels::getLabel('LBL_CLIENT_ID', CommonHelper::getLangId()), 'clientId');
        $privateKeyFld = $frm->addTextArea(Labels::getLabel('LBL_PRIVATE_KEY', CommonHelper::getLangId()), 'privateKey');
        $privateKeyFld->requirements()->setRequired();
        
        $frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Save_Changes', CommonHelper::getLangId()));
        return $frm;
    }
}
