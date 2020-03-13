<?php

class PluginSettingController extends AdminBaseController
{
    use PluginHelper;

    protected $keyName;
    protected $frmObj;
    protected $pluginSettingObj;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->admin_id = AdminAuthentication::getLoggedAdminId();
        $this->objPrivilege->canEditPlugins($this->admin_id);

        if (get_called_class() == __CLASS__) {
            LibHelper::dieJsonError(Labels::getLabel('MSG_INVALID_ACCESS', $this->adminLangId));
        }

        $this->keyName = FatApp::getPostedData('keyName', FatUtility::VAR_STRING, '');
        if (empty($this->keyName)) {
            try {
                $this->keyName = get_called_class()::KEY_NAME;
            } catch (\Error $e) {
                $message = $e->getMessage();
                LibHelper::dieJsonError($message);
            }
            if (empty($this->keyName)) {
                LibHelper::dieJsonError(Labels::getLabel('LBL_INVALID_KEY_NAME', $this->adminLangId));
            }
        }
    }

    private function setFormObj()
    {
        $this->frmObj = $this->getForm();
        if (false === $this->frmObj) {
            LibHelper::dieJsonError($Labels::getLabel('LBL_REQUIREMENT_SETTINGS_ARE_NOT_DEFINED', $this->adminLangId));
        }
    }

    public function index()
    {
        $this->setFormObj();
        $pluginSetting = new PluginSetting(0, $this->keyName);
        $settings = $pluginSetting->get();
        if (false === $settings) {
            Message::addErrorMessage(Labels::getLabel('LBL_SETTINGS_NOT_AVALIABLE_FOR_THIS_PLUGIN', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->frmObj->fill($settings);
        $identifier = isset($settings['plugin_identifier']) ? $settings['plugin_identifier'] : '';
        $this->set('frm', $this->frmObj);
        $this->set('identifier', $identifier);
        $this->_template->render(false, false, 'plugins/settings.php');
    }

    public function setup()
    {
        $this->setFormObj();
        $post = $this->frmObj->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($this->frmObj->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        
        $pluginSetting = new PluginSetting($post["plugin_id"]);
        if (!$pluginSetting->save($post)) {
            Message::addErrorMessage(Labels::getLabel($plugin->getError()));
            FatUtility::dieWithError(Message::getHtml());
        }
        $this->set('msg', $this->str_setup_successful);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function getForm()
    {
        $class = get_called_class();
        try {
            $requirements = $class::getConfigurationKeys();
        } catch (\Error $e) {
            if (false == method_exists($class, 'form')) {
                FatUtility::dieJsonError($e->getMessage());
            }
            $frm = $class::form($this->adminLangId);
        }
        
        if ((empty($requirements) || !is_array($requirements)) && !isset($frm)) {
            return false;
        }
        if (isset($frm)) {
            $frm = PluginSetting::addKeyFields($frm);
        } else {
            $frm = PluginSetting::getForm($requirements, $this->adminLangId);
        }
        $frm->fill(['keyName' => $this->keyName]);
        return $frm;
    }
}
