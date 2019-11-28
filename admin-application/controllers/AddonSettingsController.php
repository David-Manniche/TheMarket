<?php
class AddonSettingsController extends AdminBaseController
{
    protected $keyName;
    protected $frmObj;
    protected $addonSettingsObj;
    protected $addonSettings;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->keyName = FatApp::getPostedData('keyName', FatUtility::VAR_STRING, '');
        if (empty($this->keyName)) {
            FatUtility::dieJsonError(Labels::getLabel('LBL_INVALID_KEY_NAME', $this->adminLangId));
        }
        try {
            $obj = new $this->keyName();
            $this->frmObj = $obj->getSettingsForm();
        } catch (\Error $e) {
            FatUtility::dieJsonError($e->getMessage());
        }

        $this->addonSettingsObj = new AddonSettings($this->keyName);
        $this->addonSettings = $this->addonSettingsObj->getSettings();
        if (!$this->addonSettings) {
            Message::addErrorMessage($this->addonSettingsObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
    }

    public function index()
    {
        $this->frmObj->fill($this->addonSettings);
        $this->set('frm', $this->frmObj);
        $this->_template->render(false, false, 'addons/settings.php');
    }

    public function setUpSettings()
    {
        $post = $this->frmObj->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($this->frmObj->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        if (!$this->addonSettingsObj->saveSettings($post)) {
            Message::addErrorMessage($addon->getError());
            FatUtility::dieWithError(Message::getHtml());
        }

        $this->set('msg', $this->str_setup_successful);
        $this->_template->render(false, false, 'json-success.php');
    }
}
