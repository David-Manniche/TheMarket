<?php
class PluginBaseController extends MyAppController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function getSettings()
    {
        try {
            $keyName = get_called_class()::KEY_NAME;
        } catch (\Error $e) {
            $message = 'ERR - ' . $e->getMessage();
            if (true ===  MOBILE_APP_API_CALL) {
                LibHelper::dieJsonError($message);
            }
            Message::addErrorMessage($message);
            CommonHelper::redirectUserReferer();
        }
        return PluginSetting::getConfDataByCode($keyName);
    }

    private function setParticularsFormObj($keyName)
    {
        $this->particularFrmObj = $this->getParticulars($keyName);
        if (false === $this->particularFrmObj) {
            LibHelper::dieJsonError($Labels::getLabel('LBL_FORM_ELEMENTS_ARE_NOT_DEFINED', $this->siteLangId));
        }
    }

    private function getParticulars($keyName)
    {
        try {
            $particulars = $keyName::particulars();
        } catch (\Error $e) {
            FatUtility::dieJsonError('ERR - ' . $e->getMessage());
        }
        
        if (empty($particulars) || !is_array($particulars)) {
            return false;
        }
        $frm = PluginSetting::getForm($particulars, $this->siteLangId);
        return $frm;
    }

    public function getParticularsForm($keyName)
    {
        $this->setParticularsFormObj($keyName);
        $pluginSetting = PluginSetting::getConfDataByCode($keyName, ['plugin_identifier']);
        $identifier = isset($pluginSetting['plugin_identifier']) ? $pluginSetting['plugin_identifier'] : '';
        $this->particularFrmObj->fill(['plugin_id' => $pluginSetting['plugin_id'], 'keyName' => $keyName]);
        $this->set('frm', $this->particularFrmObj);
        $this->set('identifier', $identifier);
        $this->_template->render(false, false, 'plugins/particulars.php');
    }
}
