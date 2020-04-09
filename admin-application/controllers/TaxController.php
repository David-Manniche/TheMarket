<?php

class TaxController extends AdminBaseController
{
    private $canView;
    private $canEdit;

    public function __construct($action)
    {
        $ajaxCallArray = array('deleteRecord', 'form', 'langForm', 'search', 'setup', 'langSetup');
        if (!FatUtility::isAjaxCall() && in_array($action, $ajaxCallArray)) {
            die($this->str_invalid_Action);
        }
        parent::__construct($action);
        $this->admin_id = AdminAuthentication::getLoggedAdminId();
        $this->canView = $this->objPrivilege->canViewTax($this->admin_id, true);
        $this->canEdit = $this->objPrivilege->canEditTax($this->admin_id, true);
        $this->set("canView", $this->canView);
        $this->set("canEdit", $this->canEdit);
    }

    public function index()
    {
        $this->objPrivilege->canViewTax();
        $frmSearch = $this->getSearchForm();
        $this->set("frmSearch", $frmSearch);
        $this->_template->addJs('js/import-export.js');
        $this->_template->render();
    }

    private function getSearchForm()
    {
        $frm = new Form('frmTaxSearch');
        $f1 = $frm->addTextBox(Labels::getLabel('LBL_Keyword', $this->adminLangId), 'keyword');
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    public function search()
    {
        $this->objPrivilege->canViewTax();

        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $searchForm = $this->getSearchForm();
        $data = FatApp::getPostedData();
        $page = (empty($data['page']) || $data['page'] <= 0) ? 1 : $data['page'];
        $post = $searchForm->getFormDataFromArray($data);

        $taxObj = new Tax();
        $srch = $taxObj->getSearchObject($this->adminLangId, false);
        $srch->addCondition('taxcat_deleted', '=', 0);
        
        $defaultTaxApi = FatApp::getConfig('CONF_DEFAULT_PLUGIN_' . Plugin::TYPE_TAX_SERVICES, FatUtility::VAR_INT, 0);
        $defaultTaxApiIsActive = Plugin::getAttributesById($defaultTaxApi, 'plugin_active');
        
        if ($defaultTaxApiIsActive) {
            $srch->addCondition('taxcat_plugin_id', '=', $defaultTaxApi);
        }
        
        $srch->addFld('t.*');

        if (!empty($post['keyword'])) {
            $cnd = $srch->addCondition('t.taxcat_identifier', 'like', '%' . $post['keyword'] . '%');
            $cnd->attachCondition('t_l.taxcat_name', 'like', '%' . $post['keyword'] . '%', 'OR');
        }

        $page = (empty($page) || $page <= 0) ? 1 : $page;
        $page = FatUtility::int($page);
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);

        $srch->joinTable(
            Tax::DB_TBL_VALUES,
            'LEFT OUTER JOIN',
            'tv.taxval_taxcat_id = t.taxcat_id AND taxval_seller_user_id = 0',
            'tv'
        );
        $srch->addMultipleFields(array("t_l.taxcat_name", "tv.taxval_is_percent,tv.taxval_value"));
        $srch->addOrder('taxcat_active', 'DESC');
        $rs = $srch->getResultSet();
        $records = array();
        if ($rs) {
            $records = FatApp::getDb()->fetchAll($rs);
        }

        $this->set("arr_listing", $records);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->set('yesNoArr', applicationConstants::getYesNoArr($this->adminLangId));
        $this->set('activeInactiveArr', applicationConstants::getActiveInactiveArr($this->adminLangId));
        $this->set('defaultTaxApiIsActive', $defaultTaxApiIsActive);
        $this->_template->render(false, false);
    }

    public function setup()
    {
        $this->objPrivilege->canEditTax();

        $frm = $this->getForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());

        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        
        $defaultTaxApi = FatApp::getConfig('CONF_DEFAULT_PLUGIN_' . Plugin::TYPE_TAX_SERVICES, FatUtility::VAR_INT, 0);
        $defaultTaxApiIsActive = Plugin::getAttributesById($defaultTaxApi, 'plugin_active');
        
        if (!$defaultTaxApiIsActive) {
            if (Tax::validatePostOptions($this->adminLangId) == false) {
                Message::addErrorMessage(Labels::getLabel('LBL_Invalid_Tax_Option_Rate', $this->adminLangId));
                FatUtility::dieJsonError(Message::getHtml());
            }
        }

        $taxcat_id = $post['taxcat_id'];
        unset($post['taxcat_id']);

        $record = new Tax($taxcat_id);
        if (!$record->addUpdateData($post)) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        if ($taxcat_id == 0) {
            $taxcat_id = $record->getMainTableRecordId();
        }
      
        
        if (!$defaultTaxApiIsActive) {
            $taxvalOptions = array();
            $taxStructure = new TaxStructure(FatApp::getConfig('CONF_TAX_STRUCTURE', FatUtility::VAR_FLOAT, 0));
            $options = $taxStructure->getOptions($this->adminLangId);
            foreach ($options as $optionVal) {
                $taxvalOptions[$optionVal['taxstro_id']] = $post[$optionVal['taxstro_id']];
            }

            $data = array(
            'taxval_taxcat_id' => $taxcat_id,
            'taxval_seller_user_id' => 0,
            'taxval_is_percent' => $post['taxval_is_percent'],
            'taxval_value' => $post['taxval_value'],
            'taxval_options' => FatUtility::convertToJson($taxvalOptions),
            );

            $obj = new Tax();
            if (!$obj->addUpdateTaxValues($data, $data)) {
                Message::addErrorMessage($obj->getError());
                FatUtility::dieWithError(Message::getHtml());
            }
        }

        $newTabLangId = 0;
        if ($taxcat_id > 0) {
            $languages = Language::getAllNames();
            foreach ($languages as $langId => $langName) {
                if (!$row = Tax::getAttributesByLangId($langId, $taxcat_id)) {
                    $newTabLangId = $langId;
                    break;
                }
            }
        } else {
            $taxcat_id = $record->getMainTableRecordId();
            $newTabLangId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG', FatUtility::VAR_INT, 1);
        }

        $this->set('msg', $this->str_setup_successful);
        $this->set('taxcatId', $taxcat_id);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function langSetup()
    {
        $this->objPrivilege->canEditTax();
        $post = FatApp::getPostedData();

        $taxcat_id = $post['taxcat_id'];
        $lang_id = $post['lang_id'];

        if ($taxcat_id == 0 || $lang_id == 0) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }

        $frm = $this->getLangForm($taxcat_id, $lang_id);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        unset($post['taxcat_id']);
        unset($post['lang_id']);

        $data = array(
        'taxcatlang_taxcat_id' => $taxcat_id,
        'taxcatlang_lang_id' => $lang_id,
        'taxcat_name' => $post['taxcat_name'],
        );

        $taxObj = new Tax($taxcat_id);
        if (!$taxObj->updateLangData($lang_id, $data)) {
            Message::addErrorMessage($taxObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        
        $autoUpdateOtherLangsData = FatApp::getPostedData('auto_update_other_langs_data', FatUtility::VAR_INT, 0);
        if (0 < $autoUpdateOtherLangsData) {
            $updateLangDataobj = new TranslateLangData(Tax::DB_TBL_LANG);
            if (false === $updateLangDataobj->updateTranslatedData($taxcat_id)) {
                Message::addErrorMessage($updateLangDataobj->getError());
                FatUtility::dieWithError(Message::getHtml());
            }
        }

        $newTabLangId = 0;
        $languages = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            if (!$row = Tax::getAttributesByLangId($langId, $taxcat_id)) {
                $newTabLangId = $langId;
                break;
            }
        }

        $this->set('msg', $this->str_setup_successful);
        $this->set('taxcatId', $taxcat_id);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function form($taxcat_id = 0)
    {
        $this->objPrivilege->canEditTax();

        $taxcat_id = FatUtility::int($taxcat_id);
        $frm = $this->getForm($taxcat_id);

        if (0 < $taxcat_id) {
            $taxObj = new Tax($taxcat_id);
            $srch = $taxObj->getSearchObject($this->adminLangId, false);

            $srch->joinTable(
                Tax::DB_TBL_VALUES,
                'LEFT OUTER JOIN',
                'tv.taxval_taxcat_id = t.taxcat_id AND taxval_seller_user_id = 0',
                'tv'
            );
            $srch->addCondition('taxcat_id', '=', $taxcat_id);
            $srch->addMultipleFields(array("t.*", "t_l.taxcat_name", "tv.taxval_is_percent,tv.taxval_value,tv.taxval_options"));

            $rs = $srch->getResultSet();
            $data = FatApp::getDb()->fetch($rs);

            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }

            $taxOptions = json_decode($data['taxval_options'], true);
            $taxStructure = new TaxStructure(FatApp::getConfig('CONF_TAX_STRUCTURE', FatUtility::VAR_FLOAT, 0));
            $options = $taxStructure->getOptions($this->adminLangId);
            foreach ($options as $optionVal) {
                $data[$optionVal['taxstro_id']] = $taxOptions[$optionVal['taxstro_id']];
            }
            /* if (FatApp::getConfig('CONF_TAX_STRUCTURE', FatUtility::VAR_FLOAT, 0) == Tax::STRUCTURE_GST) {
                $taxValues = Tax::getCombinedValues($data['taxval_value']);
                $data = array_merge($data, $taxValues);
            } */
            $frm->fill($data);
        }

        $this->set('languages', Language::getAllNames());
        $this->set('taxcat_id', $taxcat_id);
        $this->set('frmTax', $frm);
        $this->_template->render(false, false);
    }

    public function langForm($taxcat_id = 0, $lang_id = 0, $autoFillLangData = 0)
    {
        $this->objPrivilege->canEditTax();

        $taxcat_id = FatUtility::int($taxcat_id);
        $lang_id = FatUtility::int($lang_id);

        if ($taxcat_id == 0 || $lang_id == 0) {
            FatUtility::dieWithError($this->str_invalid_request);
        }

        $taxLangFrm = $this->getLangForm($taxcat_id, $lang_id);
        if (0 < $autoFillLangData) {
            $updateLangDataobj = new TranslateLangData(Tax::DB_TBL_LANG);
            $translatedData = $updateLangDataobj->getTranslatedData($taxcat_id, $lang_id);
            if (false === $translatedData) {
                Message::addErrorMessage($updateLangDataobj->getError());
                FatUtility::dieWithError(Message::getHtml());
            }
            $langData = current($translatedData);
        } else {
            $langData = Tax::getAttributesByLangId($lang_id, $taxcat_id);
        }

        if ($langData) {
            $taxLangFrm->fill($langData);
        }

        $this->set('languages', Language::getAllNames());
        $this->set('taxcat_id', $taxcat_id);
        $this->set('taxcat_lang_id', $lang_id);
        $this->set('taxLangFrm', $taxLangFrm);
        $this->set('formLayout', Language::getLayoutDirection($lang_id));
        $this->_template->render(false, false);
    }

    public function deleteRecord()
    {
        $this->objPrivilege->canEditTax();

        $taxcat_id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if (1 > $taxcat_id) {
            FatUtility::dieJsonError($this->str_invalid_request_id);
        }

        $this->markAsDeleted($taxcat_id);

        FatUtility::dieJsonSuccess($this->str_delete_record);
    }

    public function deleteSelected()
    {
        $this->objPrivilege->canEditTax();
        $taxcatIdsArr = FatUtility::int(FatApp::getPostedData('taxcat_ids'));

        if (empty($taxcatIdsArr)) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }

        foreach ($taxcatIdsArr as $taxcat_id) {
            if (1 > $taxcat_id) {
                continue;
            }
            $this->markAsDeleted($taxcat_id);
        }
        $this->set('msg', $this->str_delete_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    private function markAsDeleted($taxcat_id)
    {
        $taxcat_id = FatUtility::int($taxcat_id);
        if (1 > $taxcat_id) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }
        $taxtObj = new Tax($taxcat_id);
        if (!$taxtObj->canRecordMarkDelete($taxcat_id)) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $taxtObj->assignValues(array(Tax::tblFld('deleted') => 1));
        if (!$taxtObj->save()) {
            Message::addErrorMessage($taxtObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
    }

    public function changeStatus()
    {
        $this->objPrivilege->canEditTax();
        $taxcatId = FatApp::getPostedData('taxcatId', FatUtility::VAR_INT, 0);
        if (0 >= $taxcatId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }

        $data = Tax::getAttributesById($taxcatId, array('taxcat_id', 'taxcat_active'));

        if ($data == false) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }

        $status = ($data['taxcat_active'] == applicationConstants::ACTIVE) ? applicationConstants::INACTIVE : applicationConstants::ACTIVE;

        $this->updateTaxStatus($taxcatId, $status);

        $this->set('msg', $this->str_update_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function toggleBulkStatuses()
    {
        $this->objPrivilege->canEditTax();

        $status = FatApp::getPostedData('status', FatUtility::VAR_INT, -1);
        $taxcatIdsArr = FatUtility::int(FatApp::getPostedData('taxcat_ids'));
        if (empty($taxcatIdsArr) || -1 == $status) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }

        foreach ($taxcatIdsArr as $taxcatId) {
            if (1 > $taxcatId) {
                continue;
            }

            $this->updateTaxStatus($taxcatId, $status);
        }
        $this->set('msg', $this->str_update_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    private function updateTaxStatus($taxcatId, $status)
    {
        $status = FatUtility::int($status);
        $taxcatId = FatUtility::int($taxcatId);
        if (1 > $taxcatId || -1 == $status) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }

        $obj = new Tax($taxcatId);
        if (!$obj->changeStatus($status)) {
            Message::addErrorMessage($obj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
    }

    private function getLangForm($taxcat_id = 0, $lang_id = 0)
    {
        $frm = new Form('frmTaxLang');
        $frm->addHiddenField('', 'taxcat_id', $taxcat_id);
        $frm->addSelectBox(Labels::getLabel('LBL_LANGUAGE', $this->adminLangId), 'lang_id', Language::getAllNames(), $lang_id, array(), '');
        $frm->addRequiredField(Labels::getLabel('LBL_Tax_Category_Name', $this->adminLangId), 'taxcat_name');
        
        $siteLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
        $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');

        if (!empty($translatorSubscriptionKey) && $lang_id == $siteLangId) {
            $frm->addCheckBox(Labels::getLabel('LBL_UPDATE_OTHER_LANGUAGES_DATA', $this->adminLangId), 'auto_update_other_langs_data', 1, array(), false, 0);
        }
        
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Update', $this->adminLangId));
        return $frm;
    }

    private function getForm($taxcat_id = 0)
    {
        $this->objPrivilege->canEditTax();
        $taxcat_id = FatUtility::int($taxcat_id);

        $frm = new Form('frmTax');
        $frm->addHiddenField('', 'taxcat_id', $taxcat_id);
        $frm->addRequiredField(Labels::getLabel('LBL_Tax_Category_Identifier', $this->adminLangId), 'taxcat_identifier');
        
        $defaultTaxApi = FatApp::getConfig('CONF_DEFAULT_PLUGIN_' . Plugin::TYPE_TAX_SERVICES, FatUtility::VAR_INT, 0);
        $defaultTaxApiIsActive = Plugin::getAttributesById($defaultTaxApi, 'plugin_active');
        
        if ($defaultTaxApiIsActive) {
            $frm->addHiddenField('', 'taxcat_plugin_id', $defaultTaxApi)->requirements()->setRequired();
            $frm->addRequiredField(Labels::getLabel('LBL_Tax_Code', $this->adminLangId), 'taxcat_code');
        } else {
            $typeArr = applicationConstants::getYesNoArr($this->adminLangId);
            $frm->addSelectBox(Labels::getLabel('LBL_Percentage', $this->adminLangId), 'taxval_is_percent', $typeArr, '', array(), '');

            $fld = $frm->addFloatField(Labels::getLabel('LBL_Total_Value', $this->adminLangId), 'taxval_value');
            $fld->requirements()->setFloatPositive(true);
            $fld->requirements()->setRange('0', '100');

            if (FatApp::getConfig('CONF_TAX_STRUCTURE', FatUtility::VAR_FLOAT, 0) == TaxStructure::TYPE_COMBINED) {
                $taxStructure = new TaxStructure(FatApp::getConfig('CONF_TAX_STRUCTURE', FatUtility::VAR_FLOAT, 0));
                $options = $taxStructure->getOptions($this->adminLangId);
                foreach ($options as $optionVal) {
                    $frm->addRequiredField($optionVal['taxstro_name'], $optionVal['taxstro_id'], '', array('data-type' => $optionVal['taxstro_interstate']));
                }
            }
        }

        $activeInactiveArr = applicationConstants::getActiveInactiveArr($this->adminLangId);

        $frm->addSelectBox(Labels::getLabel('LBL_Status', $this->adminLangId), 'taxcat_active', $activeInactiveArr, '', array(), '');
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }
    
    public function autoCompleteTaxCategories()
    {
        $pagesize = 10;
        $post = FatApp::getPostedData();
        $this->objPrivilege->canViewTax();
        $srch = Tax::getSearchObject($this->adminLangId,true);
        $srch->addCondition('taxcat_deleted', '=', 0);
        $defaultTaxApi = FatApp::getConfig('CONF_DEFAULT_PLUGIN_' . Plugin::TYPE_TAX_SERVICES, FatUtility::VAR_INT, 0);
        $defaultTaxApiIsActive = Plugin::getAttributesById($defaultTaxApi, 'plugin_active'); 
        
        $srch->addFld('taxcat_id'); 
        if ($defaultTaxApiIsActive) {
            $srch->addFld('concat(IFNULL(taxcat_name,taxcat_identifier), " (",taxcat_code,")")as taxcat_name');
            $srch->addCondition('taxcat_plugin_id', '=', $defaultTaxApi);
        }else{
            $srch->addFld('IFNULL(taxcat_name,taxcat_identifier)as taxcat_name'); 
        }       

        if (!empty($post['keyword'])) {
            $srch->addCondition('taxcat_name', 'LIKE', '%' . $post['keyword'] . '%')
            ->attachCondition('taxcat_identifier', 'LIKE', '%' . $post['keyword'] . '%')
            ->attachCondition('taxcat_code', 'LIKE', '%' . $post['keyword'] . '%');
        }
        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
        $db = FatApp::getDb();
        $taxCategories = $db->fetchAll($rs, 'taxcat_id');
        $json = array();
        foreach ($taxCategories as $key => $taxCategory) {
            $json[] = array(
                'id' => $key,
                'name' => strip_tags(html_entity_decode($taxCategory['taxcat_name'], ENT_QUOTES, 'UTF-8'))
            );
        }
        die(json_encode($json));     
    }


    /* public function getCombinedValues($value)
    {
        $this->objPrivilege->canViewTax();
        $tax =  Tax::getCombinedValues($value);
        FatUtility::dieJsonSuccess($tax);
    } */
}
