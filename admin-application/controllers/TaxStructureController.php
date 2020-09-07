<?php

class TaxStructureController extends AdminBaseController
{
    private $canEdit;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewTax();
    }

    public function index()
    {
        $this->set("canEdit", $this->objPrivilege->canEditTax($this->admin_id, true));
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

        $this->canEdit = $this->objPrivilege->canEditTax($this->admin_id, true);
        $this->set("canEdit", $this->canEdit);
        $this->_template->render(false, false);
    }

    public function form($taxStrId = 0)
    {
        $this->objPrivilege->canEditTax();
        $siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
        $taxStrId = FatUtility::int($taxStrId);
       
        $frm = TaxStructure::getForm($this->adminLangId, $taxStrId);

        if (0 < $taxStrId) {
            $srch = TaxStructure::getSearchObject($this->adminLangId);
            $srch->addCondition('taxstr_id', '=', $taxStrId);
            $rs = $srch->getResultSet();
            $data = FatApp::getDb()->fetch($rs);

            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $frm->fill($data);
        }

        $this->set('siteDefaultLangId', $siteDefaultLangId);
        $this->set('languages', Language::getAllNames());
        $this->set('taxStrId', $taxStrId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setup()
    {
        $this->objPrivilege->canEditTax();

        $frm = TaxStructure::getForm($this->adminLangId);
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

}
