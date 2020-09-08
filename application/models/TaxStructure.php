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

    public static function getAllAssoc($langId, $onlyParent = true)
    {
        $langId = FatUtility::int($langId);
        $srch = static::getSearchObject($langId);
        $srch->addMultipleFields(array('taxstr_id', 'IFNULL(taxstr_name, taxstr_identifier) as taxstr_name'));
        if ($onlyParent) {
            $srch->addCondition('taxstr_parent', '=', 0);
        }
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        return FatApp::getDb()->fetchAllAssoc($srch->getResultSet());
    }

    public function getCombinedTaxes($parentId)
    {
        $srch = static::getSearchObject();
        $srch->addMultipleFields(array('taxstr_id'));
        $srch->addCondition('taxstr_parent', '=', $parentId);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $combinedTaxes = FatApp::getDb()->fetchAll($srch->getResultSet());

        $languages = Language::getAllNames();
        $combinedTaxStructure = [];
        $row = 0;
        foreach ($combinedTaxes as $val) {
            foreach ($languages as $langId => $lang) {
                $taxLangData = $this->getAttributesByLangId($langId, $val['taxstr_id']);
                if (!empty($taxLangData)) {
                    $combinedTaxStructure[$row][$langId] = $taxLangData['taxstr_name'];
                }
            }
            $row++;
        }
        return $combinedTaxStructure;
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
            if ($languageId == $siteDefaultLangId) {
                $frm->addRequiredField(Labels::getLabel('LBL_Tax_Component_Name', $languageId), 'taxstr_component_name[0][' . $languageId . ']');
            } else {
                $frm->addTextBox(Labels::getLabel('LBL_Tax_Component_Name', $languageId), 'taxstr_component_name[0][' . $languageId . ']');
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
    * @param  array $post
    * @return bool
    */
    public function addUpdateData($post): bool
    {
		$siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
		if (empty($post)) {
            $this->error = Labels::getLabel('ERR_Invalid_Request', $siteDefaultLangId);
            return false;
        }
        
		unset($post['taxstr_id']);
		
		$data = [
			'taxstr_identifier' => $post['taxstr_name'][$siteDefaultLangId],
			'taxstr_parent' => 0,
			'taxstr_is_combined' => ($post['taxstr_is_combined']) ? $post['taxstr_is_combined'] : 0,
		];
        $this->assignValues($data);
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }
		
        $autoUpdateOtherLangsData = isset($post['auto_update_other_langs_data']) ? FatUtility::int($post['auto_update_other_langs_data']) : 0;
        foreach ($post['taxstr_name'] as $langId => $taxStrName) {
            if (empty($taxStrName) && $autoUpdateOtherLangsData > 0) {
                $this->saveTranslatedLangData($langId);
            } elseif (!empty($taxStrName)) {
                $data = array(
                     static::DB_TBL_LANG_PREFIX . 'taxstr_id' => $this->mainTableRecordId,
                     static::DB_TBL_LANG_PREFIX . 'lang_id' => $langId,
                    'taxstr_name' => $taxStrName,
                );
                if (!$this->updateLangData($langId, $data)) {
                    $this->error = $this->getError();
                    return false;
                }
            }
        }
        
        if (!$post['taxstr_is_combined']) {
            return true;
        }
        $parentId = $this->mainTableRecordId;
        if (!$this->addUpdateCombinedData($post, $parentId)) {
            $this->error = $this->getError();
            return false;
        }
		
        return true;
    }

    /**
    * addUpdateCombinedData
    *
    * @param  array $post
    * @return bool
    */
    private function addUpdateCombinedData($post, $parentId): bool
    {
        $parentId = FatUtility::int($parentId);
		$siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
		
        unset($post['taxstr_id']);
        foreach ($post['taxstr_component_name'] as $taxStrValues) {
            $this->mainTableRecordId = 0;
            $data = array(
                'taxstr_identifier' => $taxStrValues[$siteDefaultLangId],
                'taxstr_parent' => $parentId,
                'taxstr_is_combined' => 0
            );
            
            $this->assignValues($data);
            if (!$this->save()) {
                $this->error = $this->getError();
                return false;
            }
            foreach ($taxStrValues as $langId => $taxStrName) {
                $autoUpdateOtherLangsData = isset($post['auto_update_other_langs_data']) ? FatUtility::int($post['auto_update_other_langs_data']) : 0;
                if (empty($taxStrName) && $autoUpdateOtherLangsData > 0) {
                    $this->saveTranslatedLangData($langId);
                } elseif (!empty($taxStrName)) {
                    $data = array(
                        static::DB_TBL_LANG_PREFIX . 'taxstr_id' => $this->mainTableRecordId,
                        static::DB_TBL_LANG_PREFIX . 'lang_id' => $langId,
                        'taxstr_name' => $taxStrName,
                    );
                    if (!$this->updateLangData($langId, $data)) {
                        $this->error = $this->getError();
                        return false;
                    }
                }
            }
        }
        return true;
    }
	
	public function saveTranslatedLangData($langId)
    {
        $langId = FatUtility::int($langId);
        if ($this->mainTableRecordId < 1 || $langId < 1) {
            $this->error = Labels::getLabel('ERR_Invalid_Request', $this->commonLangId);
            return false;
        }

        $translateLangobj = new TranslateLangData(static::DB_TBL_LANG);
        if (false === $translateLangobj->updateTranslatedData($this->mainTableRecordId, 0, $langId)) {
            $this->error = $translateLangobj->getError();
            return false;
        }
        return true;
    }

    public function getTranslatedData($data, $toLangId)
    {
        $toLangId = FatUtility::int($toLangId);
        if (empty($data) || $toLangId < 1) {
            $this->error = Labels::getLabel('ERR_Invalid_Request', $this->commonLangId);
            return false;
        }

        $translateLangobj = new TranslateLangData(static::DB_TBL_LANG);
        $translatedData = $translateLangobj->directTranslate($data, $toLangId);
        if (false === $translatedData) {
            $this->error = $translateLangobj->getError();
            return false;
        }
        return $translatedData;
    }
}
