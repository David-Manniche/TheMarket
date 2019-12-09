<?php
class PluginBaseController extends MyAppController
{
    protected $keyName;
    protected $pluginSettings;

    public function __construct($action)
    {
        parent::__construct($action);

        $this->keyName = FatApp::getPostedData('keyName', FatUtility::VAR_STRING, '');
        if (empty($this->keyName)) {
            FatUtility::dieJsonError(Labels::getLabel('LBL_INVALID_KEY_NAME', $this->siteLangId));
        }
        try {
            $obj = new PluginSetting($this->keyName);
            $this->pluginSettings = $obj->get();
        } catch (\Error $e) {
            FatUtility::dieJsonError($e->getMessage());
        } catch (\Exception $e) {
            FatUtility::dieJsonError($e->getMessage());
        }
    }
}
