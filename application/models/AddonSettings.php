<?php
class AddonSettings
{
    public const DB_ADDON_SETTINGS_TBL = 'tbl_addon_settings';
    public const DB_ADDON_SETTINGS_TBL_PREFIX = 'addonsetting_';

    public function __construct($addonCode)
    {
        $this->db = FatApp::getDb();
        $this->addonKey = $addonCode;
        $this->error = '';
    }

    public function getError($langId, $key)
    {
        return Labels::getLabel($this->error, $langId);
    }

    public function saveSettings($arr)
    {
        if (empty($arr)) {
            $this->error = Labels::getLabel('ERR_Please_provide_data_to_save_settings', $this->commonLangId);
            return false;
        }

        $addon = $this->getAddonByCode($this->addonKey);
        if (!$addon) {
            $this->error = Labels::getLabel('ERR_Error:_Addon_with_defined_addon_key_does_not_exist.', $this->commonLangId);
            return false;
        }

        $addonId = $addon["addon_id"];

        if (!$this->db->deleteRecords(static::DB_ADDON_SETTINGS_TBL, array('smt' => static::DB_ADDON_SETTINGS_TBL_PREFIX . 'addon_id = ?', 'vals' => array($addonId)))) {
            $this->error = $this->db->getError();
            return false;
        }

        foreach ($arr as $key => $val) {
            if ($key == "btn_submit") {
                continue;
            }

            $data = array(
                'addonsetting_addon_id' => $addonId,
                'addonsetting_key' => $key
            );

            if (!is_array($val)) {
                $data['addonsetting_value'] = $val;
            } else {
                $data['addonsetting_value'] = serialize($val);
            }

            if (!$this->db->insertFromArray(static::DB_ADDON_SETTINGS_TBL, $data, false, array('IGNORE'))) {
                $this->error = $this->db->getError();
                return false;
            }
        }
        return true;
    }

    public function getSettings()
    {
        if (!isset($this->addonKey)) {
            $this->error = Labels::getLabel('ERR_Error:_Please_create_an_object_with_Addon_Key.', $this->commonLangId);
            return false;
        }

        $addon = $this->getAddonByCode($this->addonKey);

        if (!$addon) {
            $this->error = Labels::getLabel('ERR_Error:_Addon_with_this_key_does_not_exist.', $this->commonLangId);
            return false;
        }

        $addonSettings = $this->getAddonFieldsById($addon["addon_id"]);

        $addonSettingArr = array();

        foreach ($addonSettings as $pkey => $pval) {
            $addonSettingArr[$pval["addonsetting_key"]] = $pval["addonsetting_value"];
        }
        $addonSettingArr['addon_name'] = $addon['addon_identifier'] ;
        return array_merge($addonSettingArr, $addon);
    }

    private function getAddonByCode($code)
    {
        if (empty($code)) {
            return false;
        }
        $srch = new SearchBase(Addons::DB_TBL, 'tad');
        $srch->addCondition('tad.' . Addons::DB_TBL_PREFIX . 'code', '=', $code);
        $rs = $srch->getResultSet();
        $addon = $this->db->fetch($rs);
        return $addon;
    }

    private function getAddonFieldsById($addon_id)
    {
        $srch = new SearchBase(static::DB_ADDON_SETTINGS_TBL, 'tads');
        $srch->addCondition('tads.' . static::DB_ADDON_SETTINGS_TBL_PREFIX . 'addon_id', '=', (int) $addon_id);
        /* $srch->addMultipleFields(array()); */
        /* die($srch->getQuery()); */
        $rs = $srch->getResultSet();
        $addonSettings = $this->db->fetchAll($rs);
        return $addonSettings;
    }
}
