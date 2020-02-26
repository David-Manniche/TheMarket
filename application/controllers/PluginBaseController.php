<?php

class PluginBaseController extends MyAppController
{
    use PluginHelper;
    
    private $keyName;
    private $plugin;

    public function __construct($action)
    {
        parent::__construct($action);
    }

    protected function updateUserInfo($detail = [])
    {
        if (!is_array($detail)) {
            FatUtility::dieJsonError(Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId));
        }
        $obj = new User(UserAuthentication::getLoggedUserId());
        foreach ($detail as $key => $value) {
            if (false === $obj->updateUserMeta($key, $value)) {
                Message::addErrorMessage($obj->getError());
                if (true === $redirect) {
                    $this->redirectBack();
                }
            }
        }
        Message::addMessage(Labels::getLabel("MSG_SUCCESSFULLY_UPDATED", $this->siteLangId));
        FatUtility::dieJsonSuccess(Message::getHtml());
    }

    public function getFormObj($fields)
    {
        if (!is_array($fields)) {
            FatUtility::dieJsonError(Labels::getLabel('LBL_INVALID_FORM_FIELDS', $this->siteLangId));
        }
        $keyName = $this->getKeyName();
        $frm = PluginSetting::getForm($fields, $this->siteLangId);
        $pluginSetting = new PluginSetting();
        $settings = $pluginSetting->getConfDataByCode($keyName, ['plugin_identifier']);
        if (false === $settings) {
            LibHelper::dieJsonError($pluginSetting->getError());
        }

        $this->identifier = isset($settings['plugin_identifier']) ? $settings['plugin_identifier'] : '';
        $frm->fill(['plugin_id' => $settings['plugin_id'], 'keyName' => $keyName]);
        return $frm;
    }

    public function getForm($fields, $data)
    {
        $frm = $this->getFormObj($fields);
        if (is_array($data) && !empty($data)) {
            $frm->fill($data);
        }

        $this->set('frm', $frm);
        $this->set('identifier', $this->identifier);
        $this->_template->render(false, false, 'plugins/form.php');
    }
}
