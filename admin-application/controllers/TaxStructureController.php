<?php
class TaxStructureController extends AdminBaseController
{
    private $canView;
    private $canEdit;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewTax();
    }

    public function index()
    {
        $this->_template->render();
    }

    public function search()
    {
        $srch = TaxStructure::getSearchObject($this->adminLangId);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addOrder('taxstr_id', 'ASC');
        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
        $this->set("listing", $records);

        $this->canView = $this->objPrivilege->canViewTax($this->admin_id, true);
        $this->canEdit = $this->objPrivilege->canEditTax($this->admin_id, true);
        $this->set("canView", $this->canView);
        $this->set("canEdit", $this->canEdit);
        $this->_template->render(false, false);
    }

    public function form($taxStrId = 0)
    {
        $this->objPrivilege->canEditTax();

        $taxStrId = FatUtility::int($taxStrId);
        $frm = $this->getForm($taxStrId);

        $type = 0;
        if (0 < $taxStrId) {
            $srch = TaxStructure::getSearchObject($this->adminLangId);
            $srch->addCondition('taxstr_id', '=', $taxStrId);
            $rs =  $srch->getResultSet();
            $data = FatApp::getDb()->fetch($rs);

            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $frm->fill($data);
            $type =  $data['taxstr_type'];
        }

        $this->set('type', $type);
        $this->set('languages', Language::getAllNames());
        $this->set('taxStrId', $taxStrId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function options($taxStrId)
    {
        $taxStrId = FatUtility::int($taxStrId);
        if (1 > $taxStrId) {
            FatUtility::dieWithError($this->str_invalid_request);
        }

        $this->set('taxStrId', $taxStrId);
        $this->set('languages', Language::getAllNames());
        $this->_template->render(false, false);
    }

    public function searchOptions($taxStrId)
    {
        $taxStructure = new TaxStructure($taxStrId);
        $options =  $taxStructure->getOptions($this->adminLangId);

        $this->set('listing', $options);

        $this->canView = $this->objPrivilege->canViewTax($this->admin_id, true);
        $this->canEdit = $this->objPrivilege->canEditTax($this->admin_id, true);
        $this->set("canView", $this->canView);
        $this->set("canEdit", $this->canEdit);
        $this->_template->render(false, false);
    }

    public function addOptionForm($taxstrId, $taxstrOptionId = 0)
    {
        $this->objPrivilege->canEditTax();

        $taxstrOptionId = FatUtility::int($taxstrOptionId);
        $frm = $this->getOptionForm($taxstrId, $taxstrOptionId);

        if (0 < $taxstrOptionId) {
            $taxStructure = new TaxStructure($taxstrId);
            $data =  $taxStructure->getOptionData($taxstrOptionId);
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $frm->fill($data);
        }

        $this->set('languages', Language::getAllNames());
        $this->set('taxstrOptionId', $taxstrOptionId);
        $this->set('taxstrId', $taxstrId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function deleteOption()
    {
        $this->objPrivilege->canEditTax();

        $taxstrOptionId = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if ($taxstrOptionId < 1) {
            Message::addErrorMessage(
                Labels::getLabel('MSG_INVALID_REQUEST_ID', $this->adminLangId)
            );
            FatUtility::dieJsonError(Message::getHtml());
        }

        FatApp::getDb()->deleteRecords(TaxStructure::DB_TBL_OPTIONS, array( 'smt' => 'taxstro_id = ?', 'vals' => array( $taxstrOptionId )));
        FatApp::getDb()->deleteRecords(TaxStructure::DB_TBL_OPTIONS_LANG, array( 'smt' => 'taxstrolang_taxstro_id = ?', 'vals' => array( $taxstrOptionId )));
        $this->set('msg', Labels::getLabel('MSG_RECORD_DELETED_SUCCESSFULLY', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
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

        $taxStrId = $post['taxstr_id'];
        unset($post['taxstr_id']);

        $record = new TaxStructure($taxStrId);
        if (!$record->addUpdateData($post)) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        if ($taxStrId == 0) {
            $taxStrId = $record->getMainTableRecordId();
        }

        $newTabLangId = 0;
        if ($taxStrId > 0) {
            $languages = Language::getAllNames();
            foreach ($languages as $langId => $langName) {
                if (!$row = TaxStructure::getAttributesByLangId($langId, $taxStrId)) {
                    $newTabLangId = $langId;
                    break;
                }
            }
        } else {
            $taxStrId = $record->getMainTableRecordId();
            $newTabLangId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG', FatUtility::VAR_INT, 1);
        }

        $this->set('msg', $this->str_setup_successful);
        $this->set('taxStrId', $taxStrId);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function langSetup()
    {
        $this->objPrivilege->canEditTax();
        $post = FatApp::getPostedData();

        $taxStrId = $post['taxstr_id'];
        $langId = $post['lang_id'];

        if ($taxStrId == 0 || $langId == 0) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }

        $frm = $this->getLangForm($taxStrId, $langId);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        unset($post['taxcat_id']);
        unset($post['lang_id']);

        $data = array(
        'taxstrlang_taxstr_id' => $taxStrId,
        'taxstrlang_lang_id' => $langId,
        'taxstr_name' => $post['taxstr_name'],
        );

        $taxStructure = new TaxStructure($taxStrId);
        if (!$taxStructure->updateLangData($langId, $data)) {
            Message::addErrorMessage($taxStructure->getError());
            FatUtility::dieWithError(Message::getHtml());
        }

        $newTabLangId = 0;
        $languages = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            if (!$row = TaxStructure::getAttributesByLangId($langId, $taxStrId)) {
                $newTabLangId = $langId;
                break;
            }
        }

        $this->set('msg', $this->str_setup_successful);
        $this->set('taxStrId', $taxStrId);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function langForm($taxStrId = 0, $langId = 0)
    {
        $this->objPrivilege->canEditTax();

        $taxStrId = FatUtility::int($taxStrId);
        $langId = FatUtility::int($langId);

        if ($taxStrId == 0 || $langId == 0) {
            FatUtility::dieWithError($this->str_invalid_request);
        }
        $langFrm = $this->getLangForm($taxStrId, $langId);

        $langData = TaxStructure::getAttributesByLangId($langId, $taxStrId);

        if ($langData) {
            $langFrm->fill($langData);
        }

        $data = TaxStructure::getAttributesById($taxStrId);
        $type = $data['taxstr_type'];

        $this->set('type', $type);
        $this->set('languages', Language::getAllNames());
        $this->set('taxStrId', $taxStrId);
        $this->set('taxStrLangId', $langId);
        $this->set('langFrm', $langFrm);
        $this->set('formLayout', Language::getLayoutDirection($langId));
        $this->_template->render(false, false);
    }

    private function getForm($taxStrId = 0)
    {
        $this->objPrivilege->canEditTax();
        $taxStrId = FatUtility::int($taxStrId);

        $frm = new Form('frmTaxStructure');
        $frm->addHiddenField('', 'taxstr_id', $taxStrId);
        $frm->addRequiredField(Labels::getLabel('LBL_Tax_Structure_Identifier', $this->adminLangId), 'taxstr_identifier');
        $row = TaxStructure::getAttributesById($taxStrId);
        if ($row['taxstr_type'] != TaxStructure::TYPE_SINGLE) {
            $typeArr = applicationConstants::getYesNoArr($this->adminLangId);
            $frm->addSelectBox(Labels::getLabel('LBL_State_Dependent', $this->adminLangId), 'taxstr_state_dependent', $typeArr, '', array(), '');
        }
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    private function getOptionForm($taxstrId, $taxstrOptionId = 0)
    {
        $this->objPrivilege->canEditTax();
        $taxstrOptionId = FatUtility::int($taxstrOptionId);

        $frm = new Form('frmTaxStructure');
        $frm->addHiddenField('', 'taxstro_id', $taxstrOptionId);
        $frm->addHiddenField('', 'taxstro_taxstr_id', $taxstrId);
        $frm->addRequiredField(Labels::getLabel('LBL_Tax_Option_Identifier', $this->adminLangId), 'taxstro_identifier');
        $languages = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            $fld = $frm->addRequiredField(
                Labels::getLabel('LBL_Tax_Option_Name',  $this->adminLangId).' '.$langName,
                'taxstro_name'.$langId
            );
            $fld->setWrapperAttribute('class', 'layout--'.Language::getLayoutDirection($langId));
        }
        $typeArr = applicationConstants::getYesNoArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel('LBL_Interstate', $this->adminLangId), 'taxstro_interstate', $typeArr, '', array(), '');
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    public function optionSetup()
    {
        $this->objPrivilege->canEditOptions();
        $post = FatApp::getPostedData();
        $frm = $this->getOptionForm($post['taxstro_taxstr_id'], $post['taxstro_id']);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());

        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $taxstro_id = FatUtility::int($post['taxstro_id']);
        // unset($post['taxstro_id']);
        $assignValues = array(
        'taxstro_id' => $post['taxstro_id'],
        'taxstro_taxstr_id' => $post['taxstro_taxstr_id'],
        'taxstro_interstate' => $post['taxstro_interstate'],
        'taxstro_identifier' => $post['taxstro_identifier']
        );
        $db = FatApp::getDb();
        if (!$db->insertFromArray(TaxStructure::DB_TBL_OPTIONS, $assignValues, false, array(), $assignValues)) {
            Message::addErrorMessage($db->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $taxstro_id = ($taxstro_id > 0)?$taxstro_id:FatApp::getDb()->getInsertId();

        $languages = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            $data = array(
            'taxstrolang_taxstro_id' => $taxstro_id,
            'taxstrolang_lang_id' => $langId,
            'taxstro_name' => $post['taxstro_name'.$langId],
            );

            if (!$db->insertFromArray(TaxStructure::DB_TBL_OPTIONS_LANG, $data, false, array(), $data)) {
                Message::addErrorMessage($db->getError());
                FatUtility::dieJsonError(Message::getHtml());
            }
        }
        if ($taxstro_id > 0) {
            $msg = Labels::getLabel('MSG_UPDATED_SUCCESSFULLY', $this->adminLangId);
        } else {
            $msg = Labels::getLabel('MSG_SET_UP_SUCCESSFULLY', $this->adminLangId);
        }
        $this->set('msg', $msg);
        $this->set('taxstroId', $taxstro_id);
        $this->set('taxstrId', $post['taxstro_taxstr_id']);
        $this->_template->render(false, false, 'json-success.php');
    }

    private function getLangForm($taxStrId = 0, $langId = 0)
    {
        $frm = new Form('frmTaxStructureLang');
        $frm->addHiddenField('', 'taxstr_id', $taxStrId);
        $frm->addHiddenField('', 'lang_id', $langId);
        $frm->addRequiredField(Labels::getLabel('LBL_Tax_Structure_Name', $this->adminLangId), 'taxstr_name');
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Update', $this->adminLangId));
        return $frm;
    }
}
