<?php
class PluginBaseController extends MyAppController
{
    private $keyName;
    private $plugin;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->plugin = get_called_class();
        try {
            $this->keyName = ($this->plugin)::KEY_NAME;
        } catch (\Error $e) {
            $message = 'ERR - ' . $e->getMessage();
            if (true ===  MOBILE_APP_API_CALL) {
                LibHelper::dieJsonError($message);
            }
            Message::addErrorMessage($message);
            CommonHelper::redirectUserReferer();
        }
    }

    public function getSettings()
    {
        return PluginSetting::getConfDataByCode($this->keyName);
    }

    private function getParticulars()
    {
        try {
            $particulars = ($this->plugin)::particulars();
        } catch (\Error $e) {
            FatUtility::dieJsonError($e->getMessage());
        }
        
        if (empty($particulars) || !is_array($particulars)) {
            return false;
        }
        $frm = PluginSetting::getForm($particulars, $this->siteLangId);
        return $frm;
    }

    public function getParticularsForm()
    {
        $frm = $this->getParticulars();
        $pluginSetting = PluginSetting::getConfDataByCode($this->keyName, ['plugin_identifier']);
        $identifier = isset($pluginSetting['plugin_identifier']) ? $pluginSetting['plugin_identifier'] : '';
        $frm->fill(['plugin_id' => $pluginSetting['plugin_id'], 'keyName' => $this->keyName]);
        $this->set('frm', $frm);
        $this->set('identifier', $identifier);
        $this->_template->render(false, false, 'plugins/particulars.php');
    }
}
