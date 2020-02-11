<?php

class PluginBaseController extends MyAppController
{
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

    private function getKeyName()
    {
        $this->plugin = get_called_class();
        try {
            return ($this->plugin)::KEY_NAME;
        } catch (\Error $e) {
            $message = $e->getMessage();
            if (true === MOBILE_APP_API_CALL) {
                LibHelper::dieJsonError($message);
            }
            Message::addErrorMessage($message);
            CommonHelper::redirectUserReferer();
        }
    }

    public function getSettings()
    {
        return PluginSetting::getConfDataByCode($this->getKeyName());
    }

    public function getFormObj($fields)
    {
        if (!is_array($fields)) {
            FatUtility::dieJsonError(Labels::getLabel('LBL_INVALID_FORM_FIELDS', $this->siteLangId));
        }
        $keyName = $this->getKeyName();
        $frm = PluginSetting::getForm($fields, $this->siteLangId);
        $pluginSetting = PluginSetting::getConfDataByCode($keyName, ['plugin_identifier']);
        $this->identifier = isset($pluginSetting['plugin_identifier']) ? $pluginSetting['plugin_identifier'] : '';
        $frm->fill(['plugin_id' => $pluginSetting['plugin_id'], 'keyName' => $keyName]);
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
