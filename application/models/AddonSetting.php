<?php
class AddonSetting
{
    private $error;
    private $keyName;

    public const DB_TBL = 'tbl_addon_settings';
    public const DB_TBL_PREFIX = 'addonsetting_';

    public function __construct($addonCode)
    {
        $this->db = FatApp::getDb();
        $this->keyName = $addonCode;
        if (empty($this->keyName)) {
            $this->error = Labels::getLabel('LBL_INVALID_KEY_NAME', $this->commonLangId);
            return false;
        }
    }

    public function getError()
    {
        return $this->error;
    }

    public function fetchData($addOnId)
    {
        $addOnId = FatUtility::int($addOnId);
        if (1 > $addOnId) {
            $this->error = Labels::getLabel('MSG_INVALID_REQUEST', $this->commonLangId);
            return false;
        }
        $srch = new SearchBase(static::DB_TBL, 'tads');
        $srch->addCondition('tads.' . static::DB_TBL_PREFIX . 'addon_id', '=', (int) $addOnId);
        $rs = $srch->getResultSet();
        return $this->db->fetchAll($rs);
    }

    public function save($data)
    {
        if (empty($data)) {
            $this->error = Labels::getLabel('MSG_PLEASE_PROVIDE_DATA_TO_SAVE_SETTINGS', $this->commonLangId);
            return false;
        }
        $frm = $this->keyName::getSettingsForm();
        $data = $frm->getFormDataFromArray(FatApp::getPostedData());
        unset($data['btn_submit']);

        $addOnId = $data["addon_id"];

        if (!$this->delete($addOnId)) {
            return false;
        }

        foreach ($data as $key => $val) {
            $updateData = [
                'addonsetting_addon_id' => $addOnId,
                'addonsetting_key' => $key,
                'addonsetting_value' => is_array($val) ? serialize($val) : $val,
            ];

            if (!$this->db->insertFromArray(static::DB_TBL, $updateData, false, ['IGNORE'])) {
                $this->error = $this->db->getError();
                return false;
            }
        }
        return true;
    }

    public function delete($addOnId)
    {
        $addOnId = FatUtility::int($addOnId);
        if (1 > $addOnId) {
            $this->error = Labels::getLabel('MSG_INVALID_REQUEST', $this->commonLangId);
            return false;
        }
        $statement = [
            'smt' => static::DB_TBL_PREFIX . 'addon_id = ?',
            'vals' => [
                    $addOnId
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
        $settingsData = Addon::getAttributesByCode($this->keyName);
        if (!$settingsData) {
            return false;
        }

        $addonSettings = $this->fetchData($settingsData["addon_id"]);

        $addonSettingArr = [];

        foreach ($addonSettings as $val) {
            $addonSettingArr[$val[ static::DB_TBL_PREFIX . "key"]] = $val[ static::DB_TBL_PREFIX . "value"];
        }
        $addonSettingArr['addon_name'] = $settingsData['addon_identifier'] ;
        return array_merge($addonSettingArr, $settingsData);
    }

    public static function getSettings()
    {
        if (get_called_class() == __CLASS__) {
            return false;
        }
        $obj = new AddonSetting(get_called_class());
        return $obj->get();
    }

    protected function getExternalApiData($apiUrl)
    {
        if (empty($apiUrl)) {
            $this->error = Labels::getLabel('MSG_INVALID_REQUEST_URL', CommonHelper::getLangId());
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $result = curl_exec($ch);
    
        curl_close($ch);
        return json_decode($result, true);
    }
}
