<?php
class UpdateLangData
{
    private $allLangs;
    private $sourceLangId;
    private $dataToUpdate;
    private $tbl;

    public const LANG_TBLS = [
        Product::DB_TBL_LANG => Product::LANG_FIELDS,
    ];

    
    public function __construct($tbl)
    {
        if (empty($tbl) || !array_key_exists($tbl, static::LANG_TBLS)) {
            trigger_error(Labels::getLabel('MSG_INVALID_REQUEST', CommonHelper::getLangId()), E_USER_ERROR);
        }
        $this->tbl = $tbl;
    }


    public function index($recordId, $fromLangId = 0, $toLangId = 0, $return = false)
    {
        $this->sourceLangId = (0 < $fromLangId) ? $fromLangId : FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);

        $translateFromlang = strtolower(Language::getAttributesById($this->sourceLangId, 'language_code'));
        $translateObj = new Translate($translateFromlang);

        if (0 < $toLangId) {
            $allLangs = [Language::getAttributesById($toLangId, array('language_id','language_code'))];
        } else {
            $allLangs = Language::getAllNames(false);
            unset($allLangs[$this->sourceLangId]);
        }

        // CommonHelper::printArray($allLangs, true);
        $this->dataToUpdate = $this->getRecordData($recordId);
        $attr  = $this->getTableTranslateFields($this->tbl);

        
        foreach ($allLangs as $lang) {
            $toLang = strtolower($lang['language_code']);
            $toLangId = $lang['language_id'];
            
            $langIdCol = static::LANG_TBLS[$this->tbl]['langPrefix'] . 'lang_id';

            $this->dataToUpdate[$langIdCol] = $toLangId;

            $translatedData = [];
            foreach ($attr as $column) {
                if (!empty($this->dataToUpdate[$column])) {
                    $translatedData[$column] = $translateObj->getTranslatedData($toLang, $this->dataToUpdate[$column]);
                    if (false === $translatedData[$column]) {
                        unset($translatedData[$column]);
                    }
                }
            }
            if (0 < count($translatedData)) {
                $this->dataToUpdate = array_merge($this->dataToUpdate, $translatedData);
                // CommonHelper::printArray($this->dataToUpdate);
                if (true === $return) {
                    return $this->dataToUpdate;
                }

                if (!FatApp::getDB()->insertFromArray($this->tbl, $this->dataToUpdate, false, array(), $this->dataToUpdate)) {
                    return false;
                }
            }
        }
    }

    private function getRecordData($recordId)
    {
        $srch = new SearchBase($this->tbl, 'tld');
        // $srch->addMultipleFields(['tld.*']);
        $srch->addCondition('tld.' . static::LANG_TBLS[$this->tbl]['langPrefix'] . 'lang_id', '=', $this->sourceLangId);
        $srch->addCondition('tld.' . static::LANG_TBLS[$this->tbl]['recordIdCol'], '=', $recordId);

        $srch->setPageSize(1);
        $rs = $srch->getResultSet();
        // echo $srch->getQuery();
        return FatApp::getDb()->fetch($rs);
    }

    private function getTableTranslateFields()
    {
        $qry = FatApp::getDb()->query('SELECT `COLUMN_NAME` 
        FROM `INFORMATION_SCHEMA`.`COLUMNS` 
        WHERE `TABLE_SCHEMA`="' . CONF_DB_NAME . '" 
        AND `TABLE_NAME`="' . $this->tbl . '" 
        AND SUBSTR(`COLUMN_NAME`,1,LENGTH("' . static::LANG_TBLS[$this->tbl]['langPrefix'] . '")) != "' . static::LANG_TBLS[$this->tbl]['langPrefix'] . '"');

        return array_keys(FatApp::getDb()->fetchAll($qry, 'COLUMN_NAME'));
    }
}
