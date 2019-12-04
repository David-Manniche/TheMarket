<?php
class PluginSettingController extends AdminBaseController
{
    protected $keyName;
    protected $frmObj;
    protected $pluginSettingObj;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->admin_id = AdminAuthentication::getLoggedAdminId();
        $this->objPrivilege->canEditPlugins($this->admin_id);

        $this->keyName = FatApp::getPostedData('keyName', FatUtility::VAR_STRING, '');
        if (empty($this->keyName)) {
            FatUtility::dieJsonError(Labels::getLabel('LBL_INVALID_KEY_NAME', $this->adminLangId));
        }
        try {
            if (!$this->frmObj = $this->keyName::getSettingsForm($this->adminLangId)) {
                throw new Exception(Labels::getLabel('LBL_REQUIREMENT_SETTINGS_ARE_NOT_DEFINED', $this->adminLangId));
            }
        } catch (\Error $e) {
            FatUtility::dieJsonError($e->getMessage());
        } catch (\Exception $e) {
            FatUtility::dieJsonError($e->getMessage());
        }

        $this->pluginSettingObj = new PluginSetting($this->keyName);
    }

    public function index()
    {
        $pluginSetting = $this->pluginSettingObj->get();
        if (!$pluginSetting) {
            Message::addErrorMessage($this->pluginSettingObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $this->frmObj->fill($pluginSetting);
        $this->set('frm', $this->frmObj);
        $this->_template->render(false, false, 'plugins/settings.php');
    }

    public function setup()
    {
        $post = $this->frmObj->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($this->frmObj->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        if (!$this->pluginSettingObj->save($post)) {
            Message::addErrorMessage($plugin->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        $this->set('msg', $this->str_setup_successful);
        $this->_template->render(false, false, 'json-success.php');
    }
}
