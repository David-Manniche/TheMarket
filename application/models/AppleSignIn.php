<?php
class AppleSignIn extends AddonSetting
{
    private const PRODUCTION = 'https://appleid.apple.com/auth/';
    private $settings;
    private $redirectUri;

    public function __construct()
    {
        parent::__construct(get_class($this));
        $this->settings = self::getSettings();
        $this->redirectUri = CommonHelper::generateFullUrl('GuestUser', 'loginApple', array(), '', false);
    }

    public function getRequestUri()
    {
        $_SESSION['appleSignIn']['state'] = bin2hex(random_bytes(5));
        return static::PRODUCTION . 'authorize?' . http_build_query([
            'response_type' => 'code id_token',
            'response_mode' => 'form_post',
            'client_id' => $this->settings['clientId'],
            'redirect_uri' => $this->redirectUri,
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
