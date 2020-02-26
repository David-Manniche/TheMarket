<?php

class PluginSetting extends MyAppModel
{
    public const DB_TBL = 'tbl_plugin_settings';
    public const DB_TBL_PREFIX = 'pluginsetting_';

    public const TYPE_STRING = 1;
    public const TYPE_INT = 2;
    public const TYPE_FLOAT = 3;

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    private function delete(): bool
    {
        if (1 > $this->mainTableRecordId) {
            $this->error = Labels::getLabel('MSG_INVALID_REQUEST', CommonHelper::getLangId());
            return false;
        }
        $statement = [
            'smt' => static::DB_TBL_PREFIX . 'plugin_id = ?',
            'vals' => [
                $this->mainTableRecordId
            ]
        ];
        if (!FatApp::getDb()->deleteRecords(static::DB_TBL, $statement)) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }
        return true;
    }

    private static function getFieldType(string $type): int
    {
        $type = strtolower($type);
        switch ($type) {
            case 'int':
                return static::TYPE_INT;
                break;
            case 'float':
                return static::TYPE_FLOAT;
                break;
            default:
                return static::TYPE_STRING;
                break;
        }
    }

    public function getConfDataById($column = '')
    {
        if (1 > $this->mainTableRecordId) {
            $this->error = Labels::getLabel('MSG_INVALID_REQUEST', CommonHelper::getLangId());
            return false;
        }
        $srch = new SearchBase(static::DB_TBL, 'tps');
        $srch->addCondition('tps.' . static::DB_TBL_PREFIX . 'plugin_id', '=', $this->mainTableRecordId);
        
        if (!empty($column) && is_string($column)) {
            $srch->addCondition('tps.' . static::DB_TBL_PREFIX . 'key', '=', $column);
        }

        $rs = $srch->getResultSet();
        if (!empty($column) && is_string($column)) {
            $result = FatApp::getDb()->fetch($rs);
            return $result[static::DB_TBL_PREFIX . 'value'];
        }
        return FatApp::getDb()->fetchAll($rs);
    }

    public function getConfDataByCode($keyName, $column = '', $langId = 0)
    {
        $langId = FatUtility::int($langId);
        $langId = 1 > $langId ? CommonHelper::getLangId() : $langId;

        $settingsData = Plugin::getAttributesByCode($keyName, '', $langId);
        if (!$settingsData) {
            $this->error = Labels::getLabel('LBL_SETTINGS_NOT_AVALIABLE_FOR_THIS_PLUGIN', $langId);
            return false;
        }
        $this->setMainTableRecordId($settingsData["plugin_id"]);
        $settings = $this->getConfDataById($column);
        if (false === $settings) {
            return false;
        }
        if (!empty($column) && is_string($column)) {
            return $settings;
        }

        $pluginSettingArr = [];

        foreach ($settings as $val) {
            $pluginSettingArr[$val[ static::DB_TBL_PREFIX . "key"]] = $val[ static::DB_TBL_PREFIX . "value"];
        }
        $pluginSettingArr['plugin_name'] = !empty($settingsData['plugin_name']) ? $settingsData['plugin_name'] : $settingsData['plugin_identifier'];
        return array_merge($pluginSettingArr, $settingsData);
    }

    public function save()
    {
        trigger_error(Labels::getLabel('LBL_USE_SAVESETTINGS_INSTEAD_OF_SAVE_FUNCTION', CommonHelper::getLangId()), E_USER_ERROR);
    }

    public function saveSettings($data): bool
    {
        if (empty($data) || !is_array($data)) {
            $this->error = Labels::getLabel('MSG_PLEASE_PROVIDE_DATA_TO_SAVE_SETTINGS', CommonHelper::getLangId());
            return false;
        }
        unset($data['keyName'], $data['btn_submit']);

        if (!$this->delete()) {
            return false;
        }
        foreach ($data as $key => $val) {
            $updateData = [
                'pluginsetting_plugin_id' => $this->mainTableRecordId,
                'pluginsetting_key' => $key,
                'pluginsetting_value' => is_array($val) ? serialize($val) : $val,
            ];

            if (!FatApp::getDb()->insertFromArray(static::DB_TBL, $updateData, false, ['IGNORE'])) {
                $this->error = FatApp::getDb()->getError();
                return false;
            }
        }
        return true;
    }

    public static function getForm($requirements, $langId)
    {
        $frm = new Form('frmPlugins');
        $frm->addHiddenField('', 'keyName');
        $frm->addHiddenField('', 'plugin_id');

        foreach ($requirements as $fieldName => $attributes) {
            $label = 'LBL_' . str_replace(' ', '_', strtoupper($attributes['label']));
            $label = Labels::getLabel($label, $langId);
            $fieldType = static::getFieldType($attributes['type']);

            switch ($fieldType) {
                case static::TYPE_INT:
                    $fld = $frm->addIntegerField($label, $fieldName);
                    break;
                case static::TYPE_FLOAT:
                    $fld = $frm->addFloatField($label, $fieldName);
                    break;
                default:
                    $fld = $frm->addTextBox($label, $fieldName);
                    break;
            }
            if (true == $attributes['required']) {
                $fld->requirements()->setRequired(true);
            }
        }

        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $langId));
        return $frm;
    }

    public static function setupForm($frm, $langId)
    {
        $frm->addHiddenField('', 'keyName');
        $frm->addHiddenField('', 'plugin_id');
        return $frm;
    }
}
