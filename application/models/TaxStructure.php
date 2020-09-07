<?php

class TaxStructure extends MyAppModel
{
    public const DB_TBL = 'tbl_tax_structure';
    public const DB_TBL_PREFIX = 'taxstr_';

    public const DB_TBL_LANG = 'tbl_tax_structure_lang';
    public const DB_TBL_LANG_PREFIX = 'taxstrlang_';

    public const TYPE_SINGLE = 1;
    public const TYPE_COMBINED = 2;

    private $db;

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
    }

    /**
    * getSearchObject
    *
    * @return object
    */
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

    /**
    * getForm
    *
    * @param  int $langId
    * @param  int $taxStrId
    * @return object
    */
    public static function getForm($langId, $taxStrId = 0)
    {
        $taxStrId = FatUtility::int($taxStrId);

        $frm = new Form('frmTaxStructure');
        $frm->addHiddenField('', 'taxstr_id', $taxStrId);
        $frm->addCheckBox(Labels::getLabel('LBL_Combined_Tax', $langId), 'taxstr_is_combined', 1);

        $siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
        $languages = Language::getAllNames();
        foreach ($languages as $languageId => $lang) {
            if ($languageId == $siteDefaultLangId) {
                $frm->addRequiredField(Labels::getLabel('LBL_Tax_name', $languageId), 'taxstr_name[' . $languageId . ']');
            } else {
                $frm->addTextBox(Labels::getLabel('LBL_Tax_name', $languageId), 'taxstr_name[' . $languageId . ']');
            }
        }

        $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
        unset($languages[$siteDefaultLangId]);
        if (!empty($translatorSubscriptionKey) && count($languages) > 0) {
            $frm->addCheckBox(Labels::getLabel('LBL_Translate_To_Other_Languages', $langId), 'auto_update_other_langs_data', 1, array(), false, 0);
        }

        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $langId));
        return $frm;
    }

    /**
    * addUpdateData
    *
    * @param  array $data
    * @return bool
    */
    public function addUpdateData($data): bool
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
}
