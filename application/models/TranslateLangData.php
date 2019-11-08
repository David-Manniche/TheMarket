<?php
/*
    Microsoft Translator Text API 3.0
*/
require CONF_INSTALLATION_PATH . 'library/TranslateApi.php';

class TranslateLangData
{
    private $fromLangId;
    private $tbl;
    private $langTranslateFields;
    private $langTablePrimaryFields;
    private $error;

    public function __construct($tbl)
    {
        if (empty($tbl)) {
            trigger_error(Labels::getLabel('MSG_INVALID_REQUEST', CommonHelper::getLangId()), E_USER_ERROR);
        }

        $this->tbl = $tbl;
        $this->langTranslateFields = $this->getLangTranslateFields();
        $this->langTablePrimaryFields = $this->getLangTablePrimaryFields();
    }

    public function getTranslatedData($recordId, $toLangId = 0, $fromLangId = 0)
    {
        $this->fromLangId = (0 < $fromLangId) ? $fromLangId : FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);

        $translateFromlang = strtolower(Language::getAttributesById($this->fromLangId, 'language_code'));
        $translateObj = new TranslateApi($translateFromlang);

        if (0 < $toLangId) {
            $languages = [Language::getAttributesById($toLangId, array('language_id','language_code'))];
        } else {
            $languages = Language::getAllNames(false);
            unset($languages[$this->fromLangId]);
        }
        
        $toLangQueryString = strtolower("&to=" . implode("&to=", array_column($languages, 'language_code')));
        $langArr = array_change_key_case(array_flip(array_column($languages, 'language_code', 'language_id')));

        if (empty($langArr)) {
            $this->error = Labels::getLabel('MSG_NO_TARGET_LANGUAGE_PROVIDED', CommonHelper::getLangId());
            return false;
        }

        $recordData = $this->getRecordData($recordId);

        if (empty($recordData)) {
            $this->error = Labels::getLabel('MSG_PLEASE_PROVIDE_DATA_TO_TRANSLATE', CommonHelper::getLangId());
            return false;
        }

        $dataToUpdate = $this->getDataToTranslate($recordData);
        $response = $translateObj->translateData($toLangQueryString, $dataToUpdate);
        if (false === $response) {
            $this->error = $translateObj->getError();
            return false;
        }

        if (!empty($response['error'])) {
            $this->error = $response['error']['message'];
            return false;
        }
        $convertedLangsData = [];

        $response = array_column($response, 'translations');
        for ($i = 0; $i < count($langArr); $i++) {
            $convertedData = array_column($response, $i);
            $targetLang = $convertedData[0]['to'];
            $convertedLangsData[$targetLang] = array_column($convertedData, 'text');
        }

        $translatedDataToUpdate = [];
        foreach ($convertedLangsData as $lang => $langData) {
            $langRecordData = [
                $this->langTablePrimaryFields['recordIdCol'] => $recordId,
                $this->langTablePrimaryFields['langIdCol'] => $langArr[$lang],
            ];
            $dataToupdate = array_combine(array_keys($recordData), $langData);
            $translatedDataToUpdate[$langArr[$lang]] = array_merge($langRecordData, $dataToupdate);
        }

        return $translatedDataToUpdate;
    }

    public function updateTranslatedData($recordId, $fromLangId = 0, $toLangId = 0)
    {
        $data = $this->getTranslatedData($recordId, $toLangId, $fromLangId);
        if (false === $data || empty($data) || 1 > count($data)) {
            return false;
        }

        foreach ($data as $translatedData) {
            if (!FatApp::getDB()->insertFromArray($this->tbl, $translatedData, false, array(), $translatedData)) {
                $this->error = Labels::getLabel('MSG_UNABLE_TO_UPDATE_DATA', CommonHelper::getLangId());
                return false;
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
        $srch->doNotCalculateRecords();
        $srch->addMultipleFields($this->langTranslateFields);
        $srch->addCondition('tld.' . $this->langTablePrimaryFields['langIdCol'], '=', $this->fromLangId);
        $srch->addCondition('tld.' . $this->langTablePrimaryFields['recordIdCol'], '=', $recordId);
        $srch->setPageSize(1);
        $rs = $srch->getResultSet();
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

    public function getError()
    {
        return $this->error;
    }
}
