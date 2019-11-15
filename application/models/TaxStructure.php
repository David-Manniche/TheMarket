<?php
class TaxStructure extends MyAppModel
{
    const DB_TBL = 'tbl_tax_structure';
    const DB_TBL_PREFIX = 'taxstr_';

    const DB_TBL_LANG = 'tbl_tax_structure_lang';
    const DB_TBL_LANG_PREFIX = 'taxstrlang_';

    const DB_TBL_OPTIONS = 'tbl_tax_structure_options';
    const DB_TBL_OPTIONS_PREFIX = 'taxstro_';

    const DB_TBL_OPTIONS_LANG = 'tbl_tax_structure_options_lang';
    const DB_TBL_OPTIONS_LANG_PREFIX = 'taxstrolang_';

    const TYPE_SINGLE = 1;
    const TYPE_COMBINED = 2;


    private $db;

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
        $this->objMainTableRecord->setSensitiveFields(array('taxstr_type'));
    }

    public static function getSearchObject($langId = 0)
    {
        $langId = FatUtility::int($langId);
        $srch = new SearchBase(static::DB_TBL, 'ts');

        if ($langId > 0) {
            $srch->joinTable(
                static::DB_TBL_LANG,
                'LEFT OUTER JOIN',
                'ts_l.' . static::DB_TBL_LANG_PREFIX . 'taxstr_id = ts.' . static::tblFld('id') . ' and
			ts_l.' . static::DB_TBL_LANG_PREFIX . 'lang_id = ' . $langId,
                'ts_l'
            );
        }
        return $srch;
    }

    public static function getAllAssoc($langId)
    {
        $langId = FatUtility::int($langId);
        $srch = static::getSearchObject($langId);
        $srch->addMultipleFields(array('taxstr_id', 'IFNULL(taxstr_name, taxstr_identifier) as taxstr_name'));
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        return FatApp::getDb()->fetchAllAssoc($srch->getResultSet());
    }

    public function addUpdateData($data)
    {
        unset($data['taxstr_id']);
        $assignValues = array(
        'taxstr_identifier' => $data['taxstr_identifier'],
        'taxstr_state_dependent' => $data['taxstr_state_dependent']
        );

        if ($this->mainTableRecordId > 0) {
            $assignValues['taxstr_id'] = $this->mainTableRecordId;
        }

        $record = new TableRecord(self::DB_TBL);
        $record->assignValues($assignValues);

        if (!$record->addNew(array(), $assignValues)) {
            $this->error = $record->getError();
            return false;
        }

        $this->mainTableRecordId = $record->getId();
        return true;
    }

    public function getOptions($langId = 0)
    {
        $langId = FatUtility::int($langId);

        if (1 > $this->mainTableRecordId || $langId == 0) {
            trigger_error(Labels::getLabel('MSG_Invalid_access.', CommonHelper::getLangId()), E_USER_ERROR);
        }


        $srch = static::getSearchObject($langId);
        $srch->joinTable(
            static::DB_TBL_OPTIONS,
            'INNER JOIN',
            'tso.' . static::DB_TBL_OPTIONS_PREFIX . 'taxstr_id = ts.' . static::tblFld('id'),
            'tso'
        );
        if (0 < $langId) {
            $srch->joinTable(
                static::DB_TBL_OPTIONS_LANG,
                'LEFT OUTER JOIN',
                'tso_l.' . static::DB_TBL_OPTIONS_LANG_PREFIX . 'taxstro_id = tso.' . static::DB_TBL_OPTIONS_PREFIX . 'id AND tso_l.' . static::DB_TBL_OPTIONS_LANG_PREFIX . 'lang_id = ' . $langId,
                'tso_l'
            );
        }
        $srch->addCondition('taxstro_taxstr_id', '=', $this->mainTableRecordId);
        $srch->addMultipleFields(array('taxstr_id','taxstro_id','taxstro_taxstr_id', 'taxstro_interstate', 'taxstr_identifier','ifnull(taxstro_name, taxstro_name) as taxstro_name','taxstro_identifier'));
        $rs = $srch->getResultSet();
        return FatApp::getDb()->fetchAll($rs);
    }

    public function getOptionData($taxstrOptionId)
    {
        if (1 > $this->mainTableRecordId) {
            trigger_error(Labels::getLabel('MSG_Invalid_access.', CommonHelper::getLangId()), E_USER_ERROR);
        }

        $srch = static::getSearchObject();
        $srch->joinTable(
            static::DB_TBL_OPTIONS,
            'INNER JOIN',
            'tso.' . static::DB_TBL_OPTIONS_PREFIX . 'taxstr_id = ts.' . static::tblFld('id'),
            'tso'
        );
        $srch->addCondition('taxstro_id', '=', $taxstrOptionId);
        $srch->addCondition('taxstro_taxstr_id', '=', $this->mainTableRecordId);
        $rs = $srch->getResultSet();
        $record = FatApp::getDb()->fetch($rs);
        if ($record) {
            $lang_record = CommonHelper::getLangFields(
                $taxstrOptionId,
                'taxstrolang_taxstro_id',
                'taxstrolang_lang_id',
                array('taxstro_name'),
                static::DB_TBL_OPTIONS.'_lang'
            );
            return array_merge($record, $lang_record);
        }
    }

    public function getName($langId)
    {
        if (1 > $this->mainTableRecordId) {
            trigger_error(Labels::getLabel('MSG_Invalid_access.', CommonHelper::getLangId()), E_USER_ERROR);
        }

        $langId = FatUtility::int($langId);
        $srch = static::getSearchObject($langId);
        $srch->addCondition('taxstr_id', '=', $this->mainTableRecordId);
        $rs = $srch->getResultSet();
        return $record = FatApp::getDb()->fetch($rs);
    }
}
