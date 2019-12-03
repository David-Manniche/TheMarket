<?php
class AppleSignIn extends LoginAddon
{
    private const PRODUCTION_URL = 'https://appleid.apple.com/auth/';
    
    public function getRequestUri()
    {
        $settings = static::getSettings();
        $redirectUri = CommonHelper::generateFullUrl('GuestUser', 'loginApple', array(), '', false);
        $_SESSION['appleSignIn']['state'] = bin2hex(random_bytes(5));
        return static::PRODUCTION_URL . 'authorize?' . http_build_query([
            'response_type' => 'code id_token',
            'response_mode' => 'form_post',
            'client_id' => $settings['clientId'],
            'redirect_uri' => $redirectUri,
            'state' => $_SESSION['appleSignIn']['state'],
            'scope' => 'name email',
        ]);
    }

    public static function getSettingsForm()
    {
        $frm = new Form('frmAddons');
        $frm->addHiddenField('', 'keyName', __CLASS__);
        $frm->addHiddenField('', 'addon_id');
        $frm->addRequiredField(Labels::getLabel('LBL_CLIENT_ID', CommonHelper::getLangId()), 'clientId');        
        $frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Save_Changes', CommonHelper::getLangId()));
        return $frm;
    }
}
