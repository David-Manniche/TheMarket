<?php
class TranslateLangData
{
    private $fromLangId;
    private $tbl;
    private $langTranslateFields;
    private $langTablePrimaryFields;

    public function __construct($tbl)
    {
        if (empty($tbl)) {
            trigger_error(Labels::getLabel('MSG_INVALID_REQUEST', CommonHelper::getLangId()), E_USER_ERROR);
        }
        $this->tbl = $tbl;
        $this->langTranslateFields = static::getLangTranslateFields();
        $this->langTablePrimaryFields = static::getLangTablePrimaryFields();
    }

    public function getTranslatedData($recordId, $toLangId = 0, $fromLangId = 0)
    {
        $this->fromLangId = (0 < $fromLangId) ? $fromLangId : FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);

        $translateFromlang = strtolower(Language::getAttributesById($this->fromLangId, 'language_code'));
        $translateObj = new TranslateApi($translateFromlang);

        if (0 < $toLangId) {
            $allLangs = [Language::getAttributesById($toLangId, array('language_id','language_code'))];
        } else {
            $allLangs = Language::getAllNames(false);
            unset($allLangs[$this->fromLangId]);
        }

        $recordData = $this->getRecordData($recordId);
        $dataToUpdate = $this->getDataToTranslate($recordData);
        $translatedDataToUpdate = [];
        foreach ($allLangs as $lang) {
            $toLang = strtolower($lang['language_code']);
            $toLangId = $lang['language_id'];

            $langRecordData = [
                $this->langTablePrimaryFields['recordIdCol'] => $recordId,
                $this->langTablePrimaryFields['langIdCol'] => $toLangId,
            ];

            $response = $translateObj->getTranslatedData($toLang, $dataToUpdate);

            // Uncomment This line when live
                /* if (!empty($response)) {
                    if (!empty($response['error'])) {
                        trigger_error($response['error']['message'], E_USER_ERROR);
                    }
                    $translatedDataToUpdate = array_column($response['translations'], 'Text');
                } */
            // ^^^^^^^^^^

            //Remove This line
            $translatedDataToUpdate[$toLangId] = array_column($response, 'Text');
            // ^^^^^^^^^^

            $translatedDataToUpdate[$toLangId] = array_combine(array_keys($recordData), $translatedDataToUpdate[$toLangId]);
            $translatedDataToUpdate[$toLangId] = array_merge($langRecordData, $translatedDataToUpdate[$toLangId]);
        }
        return $translatedDataToUpdate;
    }

    public function updateTranslatedData($recordId, $fromLangId = 0, $toLangId = 0)
    {
        $data = $this->getTranslatedData($recordId, $fromLangId, $toLangId);
        if (!empty($data) && 0 < count($data)) {
            foreach ($data as $translatedData) {
                if (!FatApp::getDB()->insertFromArray($this->tbl, $translatedData, false, array(), $translatedData)) {
                    // return false;
                }
            }
        }
    }

    private function getDataToTranslate($dataToUpdate)
    {
        $inputData = [];
        foreach ($dataToUpdate as $value) {
            $inputData[] = ['Text' => $value];
        }
        return $inputData;
    }

    private function getRecordData($recordId)
    {
        $srch = new SearchBase($this->tbl, 'tld');
        $srch->addMultipleFields($this->langTranslateFields);
        $srch->addCondition('tld.' . $this->langTablePrimaryFields['langIdCol'], '=', $this->fromLangId);
        $srch->addCondition('tld.' . $this->langTablePrimaryFields['recordIdCol'], '=', $recordId);

        $srch->setPageSize(1);
        $rs = $srch->getResultSet();
        // echo $srch->getQuery();
        return array_filter(FatApp::getDb()->fetch($rs));
    }


    private function getLangTranslateFields()
    {
        $qry = $this->getTableSchemaQry($this->tbl);
        $qry = FatApp::getDb()->query($qry . '
           AND COLUMN_KEY != "PRI"');

        return array_keys(FatApp::getDb()->fetchAll($qry, 'COLUMN_NAME'));
    }

    private function getLangTablePrimaryFields()
    {
        $qry = $this->getTableSchemaQry($this->tbl);
        $qry = FatApp::getDb()->query($qry . '
           AND COLUMN_KEY = "PRI"');

        $result = array_keys(FatApp::getDb()->fetchAll($qry, 'COLUMN_NAME'));
        $primaryCols = [];
        foreach ($result as $column) {
            if (0 < strpos($column, '_lang_id')) {
                $primaryCols['langIdCol'] = $column;
            } else {
                $primaryCols['recordIdCol'] = $column;
            }
        }
        return $primaryCols;
    }

    private function getTableSchemaQry()
    {
        return 'SELECT COLUMN_NAME
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = "' . CONF_DB_NAME . '"
           AND TABLE_NAME = "' . $this->tbl . '"';
    }
}
