<?php
class PluginSettingController extends AdminBaseController
{
    protected $keyName;
    protected $frmObj;
    protected $pluginSettingObj;

    public const TYPE_STRING = 1;
    public const TYPE_INT = 2;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->admin_id = AdminAuthentication::getLoggedAdminId();
        $this->objPrivilege->canEditPlugins($this->admin_id);

        if (get_called_class() == __CLASS__) {
            FatUtility::dieJsonError(Labels::getLabel('LBL_INVALID_KEY_NAME', $this->adminLangId));
        }

        $this->keyName = FatApp::getPostedData('keyName', FatUtility::VAR_STRING, '');
        if (empty($this->keyName)) {
            FatUtility::dieJsonError(Labels::getLabel('LBL_INVALID_KEY_NAME', $this->adminLangId));
        }
        
        try {
            if (!$this->frmObj = $this->getSettingsForm($this->adminLangId)) {
                throw new Exception(Labels::getLabel('LBL_REQUIREMENT_SETTINGS_ARE_NOT_DEFINED', $this->adminLangId));
            }
        } catch (\Error $e) {
            FatUtility::dieJsonError($e->getMessage());
        } catch (\Exception $e) {
            FatUtility::dieJsonError($e->getMessage());
        }
    }

    public function index()
    {
        $pluginSetting = PluginSetting::getConfDataByCode($this->keyName);
        if (false === $pluginSetting) {
            Message::addErrorMessage(Labels::getLabel('LBL_SETTINGS_NOT_AVALIABLE_FOR_THIS_PLUGIN', $this->adminLangId));
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
        
        $obj = new PluginSetting();
        if (!$obj->save($post)) {
            Message::addErrorMessage($plugin->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        $this->set('msg', $this->str_setup_successful);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function getSettingsForm()
    {
        $requirements = get_called_class()::requirements();
        if (empty($requirements) || !is_array($requirements)) {
            return false;
        }
        $frm = new Form('frmPlugins');
        $frm->addHiddenField('', 'keyName', $this->keyName);
        $frm->addHiddenField('', 'plugin_id');

        foreach ($requirements as $fieldName => $attributes) {
            $label = 'LBL_' . str_replace(' ', '_', strtoupper($attributes['label']));
            $label = Labels::getLabel($label, $this->adminLangId);

            switch ($attributes['type']) {
                case static::TYPE_STRING:
                    $fld = $frm->addTextBox($label, $fieldName);
                    break;
                case static::TYPE_INT:
                    $fld = $frm->addIntegerField($label, $fieldName);
                    break;
                default:
                    $fld = $frm->addTextBox($label, $fieldName);
                    break;
            }
            if (true == $attributes['required']) {
                $fld->requirements()->setRequired(true);
            }
        }

        $frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }
}
