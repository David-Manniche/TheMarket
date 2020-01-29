<?php
class PluginSettingController extends AdminBaseController
{
    protected $keyName;
    protected $confFrmObj;
    protected $particularFrmObj;
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
                $message = 'ERR - ' . $e->getMessage();
                LibHelper::dieJsonError($message);
            }
            if (empty($this->keyName)) {
                LibHelper::dieJsonError(Labels::getLabel('LBL_INVALID_KEY_NAME', $this->adminLangId));
            }
        }
    }

    private function setConfFormObj()
    {
        $this->confFrmObj = $this->getConfForm();
        if (false === $this->confFrmObj) {
            LibHelper::dieJsonError($Labels::getLabel('LBL_REQUIREMENT_SETTINGS_ARE_NOT_DEFINED', $this->adminLangId));
        }
    }

    private function getConfForm()
    {
        try {
            $requirements = get_called_class()::getConfigurationKeys();
        } catch (\Error $e) {
            FatUtility::dieJsonError('ERR - ' . $e->getMessage());
        }
        
        if (empty($requirements) || !is_array($requirements)) {
            return false;
        }
        $frm = PluginSetting::getForm($requirements, $this->adminLangId);
        $frm->fill(['keyName' => $this->keyName]);
        return $frm;
    }

    private function setParticularsFormObj()
    {
        $this->particularFrmObj = $this->getParticulars();
        if (false === $this->particularFrmObj) {
            LibHelper::dieJsonError($Labels::getLabel('LBL_FORM_ELEMENTS_ARE_NOT_DEFINED', $this->adminLangId));
        }
    }

    private function getParticulars()
    {
        $class = get_called_class();
        try {
            $requirements = $class::getConfigurationKeys();
            $keyName = get_called_class()::KEY_NAME;
            $particulars = $keyName::particulars();
        } catch (\Error $e) {
            if (false == method_exists($class, 'form')) {
                FatUtility::dieJsonError('ERR - ' . $e->getMessage());
            }
            $frm = $class::form($this->adminLangId);
        }
        
        if ((empty($requirements) || !is_array($requirements)) && !isset($frm)) {
            return false;
        }
        if (isset($frm)) {
            $frm = PluginSetting::setupForm($frm, $this->adminLangId);
        } else {
            $frm = PluginSetting::getForm($requirements, $this->adminLangId);
        }
        if (empty($particulars) || !is_array($particulars)) {
            return false;
        }
        $frm = PluginSetting::getForm($particulars, $this->adminLangId);
        $frm->fill(['keyName' => $this->keyName]);
        return $frm;
    }

    public function index()
    {
        $this->setConfFormObj();
        $pluginSetting = PluginSetting::getConfDataByCode($this->keyName);
        if (false === $pluginSetting) {
            Message::addErrorMessage(Labels::getLabel('LBL_SETTINGS_NOT_AVALIABLE_FOR_THIS_PLUGIN', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->confFrmObj->fill($pluginSetting);
        $identifier = isset($pluginSetting['plugin_identifier']) ? $pluginSetting['plugin_identifier'] : '';
        $this->set('frm', $this->confFrmObj);
        $this->set('identifier', $identifier);
        $this->_template->render(false, false, 'plugins/settings.php');
    }
    
    public function getParticularsForm()
    {
        $this->setParticularsFormObj();
        $identifier = isset($pluginSetting['plugin_identifier']) ? $pluginSetting['plugin_identifier'] : '';
        $this->set('frm', $this->particularFrmObj);
        $this->set('identifier', $identifier);
        $this->_template->render(false, false, 'plugins/particulars.php');
    }

    
    public function getSettings()
    {
        try {
            $keyName = get_called_class()::KEY_NAME;
        } catch (\Error $e) {
            $message = 'ERR - ' . $e->getMessage();
            LibHelper::dieJsonError($message);
        }
        if (empty($keyName)) {
            LibHelper::dieJsonError(Labels::getLabel('LBL_INVALID_KEY_NAME', $this->adminLangId));
        }
        return PluginSetting::getConfDataByCode($keyName);
    }

    public function setup()
    {
        $this->setConfFormObj();
        $post = $this->confFrmObj->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($this->confFrmObj->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        
        $obj = new PluginSetting();
        if (!$obj->save($post)) {
            Message::addErrorMessage($plugin->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        $this->set('msg', $this->str_setup_successful);
        $this->_template->render(false, false, 'json-success.php');
    }
}
