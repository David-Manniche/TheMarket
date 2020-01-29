<?php
class PluginSetting
{
    private $error;

    public const DB_TBL = 'tbl_plugin_settings';
    public const DB_TBL_PREFIX = 'pluginsetting_';

    public const TYPE_STRING = 1;
    public const TYPE_INT = 2;
    public const TYPE_FLOAT = 3;

    public function getError()
    {
        return $this->error;
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
        if (!FatApp::getDb()->deleteRecords(static::DB_TBL, $statement)) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }
        return true;
    }

    private static function getFieldType($type)
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

    public static function getConfDataById($pluginId, $column = '')
    {
        $pluginId = FatUtility::int($pluginId);
        if (1 > $pluginId) {
            return false;
        }
        $srch = new SearchBase(static::DB_TBL, 'tps');
        $srch->addCondition('tps.' . static::DB_TBL_PREFIX . 'plugin_id', '=', (int) $pluginId);
        
        $singleColumn = false;
        if (!empty($column) && is_string($column)) {
            $srch->addCondition('tps.' . static::DB_TBL_PREFIX . 'key', '=', $column);
            $singleColumn = true;
        }
        $rs = $srch->getResultSet();
        if ($singleColumn) {
            $result = FatApp::getDb()->fetch($rs);
            return $result[static::DB_TBL_PREFIX . 'value'];
        }
        return FatApp::getDb()->fetchAll($rs);
    }

    public static function getConfDataByCode($keyName, $column = '', $langId = 0)
    {
        $settingsData = Plugin::getAttributesByCode($keyName, '', $langId);
        if (!$settingsData) {
            return false;
        }
        $pluginSettings = PluginSetting::getConfDataById($settingsData["plugin_id"], $column);
        if (!empty($column) && is_string($column)) {
            return $pluginSettings;
        }

        $pluginSettingArr = [];

        foreach ($pluginSettings as $val) {
            $pluginSettingArr[$val[ static::DB_TBL_PREFIX . "key"]] = $val[ static::DB_TBL_PREFIX . "value"];
        }
        $pluginSettingArr['plugin_name'] = !empty($settingsData['plugin_name']) ? $settingsData['plugin_name'] : $settingsData['plugin_identifier'] ;
        return array_merge($pluginSettingArr, $settingsData);
    }

    public function save($data)
    {
        if (empty($data) || !is_array($data)) {
            $this->error = Labels::getLabel('MSG_PLEASE_PROVIDE_DATA_TO_SAVE_SETTINGS', CommonHelper::getLangId());
            return false;
        }
        unset($data['keyName'], $data['btn_submit']);

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

        $frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $langId));
        return $frm;
    }

    public static function setupForm($frm, $langId)
    {
        $frm->addHiddenField('', 'keyName');
        $frm->addHiddenField('', 'plugin_id');
        return $frm;
    }
}
