<?php
class AddonSettingController extends AdminBaseController
{
    protected $keyName;
    protected $frmObj;
    protected $addonSettingObj;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->admin_id = AdminAuthentication::getLoggedAdminId();
        $this->objPrivilege->canEditAddons($this->admin_id);

        $this->keyName = FatApp::getPostedData('keyName', FatUtility::VAR_STRING, '');
        if (empty($this->keyName)) {
            FatUtility::dieJsonError(Labels::getLabel('LBL_INVALID_KEY_NAME', $this->adminLangId));
        }
        try {
            $this->frmObj = $this->keyName::getSettingsForm($this->adminLangId);
        } catch (\Error $e) {
            FatUtility::dieJsonError($e->getMessage());
        }

        $this->addonSettingObj = new AddonSetting($this->keyName);
    }

    public function index()
    {
        $addonSetting = $this->addonSettingObj->get();
        if (!$addonSetting) {
            Message::addErrorMessage($this->addonSettingObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $this->frmObj->fill($addonSetting);
        $this->set('frm', $this->frmObj);
        $this->_template->render(false, false, 'addons/settings.php');
    }

    public function setup()
    {
        $post = $this->frmObj->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($this->frmObj->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        if (!$this->addonSettingObj->save($post)) {
            Message::addErrorMessage($addon->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        $this->set('msg', $this->str_setup_successful);
        $this->_template->render(false, false, 'json-success.php');
    }
}
