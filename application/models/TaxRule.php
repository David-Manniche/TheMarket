<?php
class TaxRule extends MyAppModel
{
    const DB_TBL = 'tbl_tax_rules';
    const DB_TBL_PREFIX = 'taxrule_';
	
	const DB_TBL_LANG = 'tbl_tax_rules_lang';
    const DB_TBL_LANG_PREFIX = 'taxrulelang_';
	
	const TYPE_ALL_STATES = -1;
	const TYPE_INCLUDE_STATES = 1;
	const TYPE_EXCLUDE_STATES = 2;
	
    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
    }

    public static function getSearchObject($langId = 0)
    {
		$langId = FatUtility::int($langId);
		$srch = new SearchBase(static::DB_TBL, 'taxRule');
		if ($langId > 0) {
            $srch->joinTable(
                static::DB_TBL_LANG,
                'LEFT OUTER JOIN',
                'taxRule_l.' . static::DB_TBL_LANG_PREFIX . 'taxrule_id = taxRule.' . static::tblFld('id') . ' and 
			taxRule_l.' . static::DB_TBL_LANG_PREFIX . 'lang_id = ' . $langId,
                'taxRule_l'
            );
        }
		return $srch;
    }
	
	public static function getTypeOptions($langId)
	{
		return array(
			self::TYPE_ALL_STATES => Labels::getLabel('LBL_ALL_STATES', $langId),
			self::TYPE_INCLUDE_STATES => Labels::getLabel('LBL_INCLUDE_STATES', $langId),
			self::TYPE_EXCLUDE_STATES => Labels::getLabel('LBL_EXCLUDE_STATES', $langId),
		);
	}
	
	public function deleteRules($taxCatId)
	{
		if (!FatApp::getDb()->query('DELETE rules, ruleDetails FROM '. self::DB_TBL .' rules LEFT JOIN '. TaxRuleCombined::DB_TBL .' ruleDetails ON ruleDetails.taxruledet_taxrule_id  = rules.taxrule_id  WHERE rules.taxrule_taxcat_id = '. $taxCatId)) {
			$this->error = FatApp::getDb()->getError();
            return false;
		};
		return true;
	}
	
	public static function getRuleForm($langId, $userId)
    {
        $frm = new Form('frmTaxRule');
        $frm->addHiddenField('', 'taxcat_id', 0);
        $frm->addHiddenField('', 'taxval_seller_user_id', $userId);
        
		/* [ TAX CATEGORY RULE FORM */
		$frm->addHiddenField('', 'taxrule_id[]', 0);
		/* $frm->addRequiredField(Labels::getLabel('LBL_Name', $langId), 'taxrule_name[]', ''); */
		$fld = $frm->addFloatField(Labels::getLabel('LBL_Tax_Rate(%)', $langId), 'taxrule_rate[]', '');
        $fld->requirements()->setPositive();
		 
		$fld = $frm->addCheckBox(Labels::getLabel('LBL_Combined_Tax', $langId), 'taxrule_is_combined[]', 1);
		/* ] */
		
		/* [ TAX CATEGORY RULE LOCATIONS FORM */
		$countryObj = new Countries();
		$countriesOptions = $countryObj->getCountriesArr($langId, true);
		array_walk($countriesOptions, function(&$v){
			$v = str_replace("'", "\'", trim($v));
		});
		$locattionTypeOtions = static::getTypeOptions($langId);
		
		$frm->addSelectBox(Labels::getLabel('LBL_Country', $langId), 'taxruleloc_country_id[]', $countriesOptions, '', array(), Labels::getLabel('LBL_Select_Country', $langId));
		$frm->addSelectBox(Labels::getLabel('LBL_States', $langId), 'taxruleloc_type[]', $locattionTypeOtions, '', array(), Labels::getLabel('LBL_Select', $langId));
		$frm->addSelectBox(Labels::getLabel('LBL_States', $langId), 'taxruleloc_state_id[]', array(), '', array(), '');
		
		/* ] */
		
		/* [ TAX GROUP RULE COMBINED DETAILS FORM */
		$frm->addHiddenField('', 'taxruledet_id[]');
		/* $frm->addTextBox(Labels::getLabel('LBL_Name', $langId), 'taxruledet_name[]', ''); */
		$fld = $frm->addTextBox(Labels::getLabel('LBL_Tax_Rate(%)', $langId), 'taxruledet_rate[]', '');
        //$fld->requirements()->required();
		/* ] */
		
		$siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
        $languages = Language::getAllNames();
        foreach ($languages as $languageId => $lang) {
            if ($languageId == $siteDefaultLangId) {
                $frm->addRequiredField(Labels::getLabel('LBL_Rule_Name', $languageId), 'taxrule_name[' . $languageId . '][]');
            } else {
                $frm->addTextBox(Labels::getLabel('LBL_Rule_Name', $languageId), 'taxrule_name[' . $languageId . '][]');
            }
			if ($languageId == $siteDefaultLangId) {
                $frm->addRequiredField(Labels::getLabel('LBL_Combined_Tax_Name', $languageId), 'taxruledet_name[' . $languageId . '][]');
            } else {
                $frm->addTextBox(Labels::getLabel('LBL_Combined_Tax_Name', $languageId), 'taxruledet_name[' . $languageId . '][]');
            }
        }

        $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
        unset($languages[$siteDefaultLangId]);
        if (!empty($translatorSubscriptionKey) && count($languages) > 0) {
            $frm->addCheckBox(Labels::getLabel('LBL_Translate_To_Other_Languages', $langId), 'auto_update_other_langs_data', 1, array(), false, 0);
        }
		
		$fld_submit = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save', $langId));
        $fld_cancel = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Cancel', $langId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }
	
	public function updateRuleLangData($langData)
    {
        if ($this->mainTableRecordId < 1 || empty($langData)) {
            $this->error = Labels::getLabel('ERR_Invalid_Request', $this->commonLangId);
            return false;
        }
        $autoUpdateOtherLangsData = isset($langData['auto_update_other_langs_data']) ? FatUtility::int($langData['auto_update_other_langs_data']) : 0;
        foreach ($langData['taxrule_name'] as $langId => $ruleName) {
            if (empty($ruleName) && $autoUpdateOtherLangsData > 0) {
                $this->saveTranslatedRuleLangData($langId);
            } elseif (!empty($ruleName)) {
                $data = array(
					TaxRule::DB_TBL_LANG_PREFIX.'taxrule_id' => $this->mainTableRecordId,
					TaxRule::DB_TBL_LANG_PREFIX.'lang_id' => $langId,
					TaxRule::DB_TBL_PREFIX.'name' => $ruleName,
				);
				
                if (!$this->updateLangData($langId, $data)) {
                    $this->error = $this->getError();
                    return false;
                }
            }
        }
        return true;
    }
	
	public function saveTranslatedRuleLangData($langId)
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
	
	public function getRules($taxCatId, $langId = 0)
	{
		$srch = TaxRule::getSearchObject($langId);
		$srch->addCondition('taxrule_taxcat_id', '=', $taxCatId);
		$rs = $srch->getResultSet();
		$rulesData = FatApp::getDb()->fetchAll($rs);
		return $rulesData;
	}
	
	public function getCombinedRuleDetails($rulesIds)
	{
		if (empty($rulesIds)) {
			return [];
		}
		$srch = TaxRuleCombined::getSearchObject();
		$srch->addCondition('taxruledet_taxrule_id', 'IN', $rulesIds);
		$rs = $srch->getResultSet();
		$combinedData = FatApp::getDb()->fetchAll($rs);
		
		$taxRuleCom = new TaxRuleCombined();
		$languages = Language::getAllNames();
		foreach ($combinedData as $key => $val) {
			foreach ($languages as $langId => $lang) {
                $rulesLangData = $taxRuleCom->getAttributesByLangId($langId, $val['taxruledet_id']);
                if (!empty($rulesLangData)) {
                    $combinedData[$key]['taxruledet_name'][$langId] = $rulesLangData['taxruledet_name'];
                }
			}
		}
		
		return self::groupDataByKey($combinedData, 'taxruledet_taxrule_id');
	}
	
	public function getLocations($taxCatId)
	{
		$srch = TaxRuleLocation::getSearchObject();
		$srch->addCondition('taxruleloc_taxcat_id', '=', $taxCatId);
		$rs = $srch->getResultSet();
		$locationsData = FatApp::getDb()->fetchAll($rs);
		return self::groupDataByKey($locationsData, 'taxruleloc_taxrule_id');
	}
	
	public static function groupDataByKey($data, $key)
	{
		$groupedData = [];
		if (!empty($data)) {
			foreach($data as $val) {
				$groupedData[$val[$key]][] = $val; 
			}
		}
		return $groupedData;
	}
}