<?php
class PluginSetting
{
    private $error;
    private $keyName;

    public const DB_TBL = 'tbl_plugin_settings';
    public const DB_TBL_PREFIX = 'pluginsetting_';

    public const TYPE_STRING = 1;
    public const TYPE_INT = 2;

    public function __construct($pluginCode = '')
    {
        $pluginCode = empty($pluginCode) ? get_called_class() : $pluginCode;
        if ($pluginCode == __CLASS__) {
            $this->error = Labels::getLabel('LBL_INVALID_KEY_NAME', CommonHelper::getLangId());
            return false;
        }

        $this->keyName = $pluginCode;
        $this->db = FatApp::getDb();
    }
    
    public function getError()
    {
        return $this->error;
    }

    private function fetchData($pluginId)
    {
        $pluginId = FatUtility::int($pluginId);
        if (1 > $pluginId) {
            $this->error = Labels::getLabel('MSG_INVALID_REQUEST', CommonHelper::getLangId());
            return false;
        }
        $srch = new SearchBase(static::DB_TBL, 'tads');
        $srch->addCondition('tads.' . static::DB_TBL_PREFIX . 'plugin_id', '=', (int) $pluginId);
        $rs = $srch->getResultSet();
        return $this->db->fetchAll($rs);
    }

    private function delete($pluginId)
    {
        $pluginId = FatUtility::int($pluginId);
        if (1 > $pluginId) {
            $this->error = Labels::getLabel('MSG_INVALID_REQUEST', CommonHelper::getLangId());
            return false;
        }
        $statement = [
            'smt' => static::DB_TBL_PREFIX . 'plugin_id = ?',
            'vals' => [
                    $pluginId
                ]
        ];
        if (!$this->db->deleteRecords(static::DB_TBL, $statement)) {
            $this->error = $this->db->getError();
            return false;
        }
        return true;
    }

    public function get()
    {
        $settingsData = Plugin::getAttributesByCode($this->keyName);
        if (!$settingsData) {
            return false;
        }

        $pluginSettings = $this->fetchData($settingsData["plugin_id"]);

        $pluginSettingArr = [];

        foreach ($pluginSettings as $val) {
            $pluginSettingArr[$val[ static::DB_TBL_PREFIX . "key"]] = $val[ static::DB_TBL_PREFIX . "value"];
        }
        $pluginSettingArr['plugin_name'] = $settingsData['plugin_identifier'] ;
        return array_merge($pluginSettingArr, $settingsData);
    }

    public function save($data)
    {
        if (empty($data)) {
            $this->error = Labels::getLabel('MSG_PLEASE_PROVIDE_DATA_TO_SAVE_SETTINGS', CommonHelper::getLangId());
            return false;
        }
        $frm = $this->keyName::getSettingsForm(CommonHelper::getLangId());
        $data = $frm->getFormDataFromArray(FatApp::getPostedData());
        unset($data['btn_submit']);

        $pluginId = $data["plugin_id"];

        if (!$this->delete($pluginId)) {
            return false;
        }

        foreach ($data as $key => $val) {
            $updateData = [
                'pluginsetting_plugin_id' => $pluginId,
                'pluginsetting_key' => $key,
                'pluginsetting_value' => is_array($val) ? serialize($val) : $val,
            ];

            if (!$this->db->insertFromArray(static::DB_TBL, $updateData, false, ['IGNORE'])) {
                $this->error = $this->db->getError();
                return false;
            }
        }
        return true;
    }

    public static function getSettings()
    {
        $obj = new PluginSetting(get_called_class());
        return $obj->get();
    }

    public static function getStatus()
    {
        return Plugin::getAttributesByCode(get_called_class(), 'plugin_active');
    }

    public static function getSettingsForm($langId)
    {
        $langId = FatUtility::int($langId);
        if (1 > $langId) {
            return false;
        }

        $plugin = get_called_class();
        $requirements = $plugin::requirements($langId);

        if (empty($requirements) || !is_array($requirements)) {
            return false;
        }

        $frm = new Form('frmPlugins');
        $frm->addHiddenField('', 'keyName', $plugin);
        $frm->addHiddenField('', 'plugin_id');

        foreach ($requirements as $fieldName => $attributes) {
            switch ($attributes['type']) {
                case static::TYPE_STRING:
                    $fld = $frm->addTextBox($attributes['label'], $fieldName);
                    break;
                case static::TYPE_INT:
                    $fld = $frm->addIntegerField($attributes['label'], $fieldName);
                    break;
                default:
                    $fld = $frm->addTextBox($attributes['label'], $fieldName);
                    break;
            }
            if (true == $attributes['required']) {
                $fld->requirements()->setRequired(true);
            }
        }

        $frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $langId));
        return $frm;
    }

    /*
        - This function is used for overriding functionality in derived class
        - It is being used when no requirements are defined in derived class
    */
    public static function requirements($langId)
    {
        return [];
    }
}
