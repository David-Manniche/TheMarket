<?php
class ShippingPackagesController extends SellerBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->userPrivilege->canViewShippingPackages(UserAuthentication::getLoggedUserId());
    }
    
    public function index()
    {
        $frmSearch = $this->getSearchForm();
        $this->set("frmSearch", $frmSearch);
        $this->set('canEdit', $this->userPrivilege->canEditShippingPackages(0, true));
        $this->_template->render();
    }
    
    public function search()
    {
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $searchForm = $this->getSearchForm();
        $data = FatApp::getPostedData();
        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        $post = $searchForm->getFormDataFromArray($data);
        $srch = ShippingPackage::getSearchObject();
        $srch->addOrder('shippack_name', 'ASC');
        if (!empty($post['keyword'])) {
            $srch->addCondition('spack.shippack_name', 'like', '%' . $post['keyword'] . '%');
        }
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
        
        $this->set('arr_listing', $records);
        $this->set('unitTypeArray', ShippingPackage::getUnitTypes($this->siteLangId));
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->set('canEdit', $this->userPrivilege->canEditShippingPackages(0, true));
        $this->_template->render(false, false);
    }
    
    public function form($packageId = 0)
    {
        $this->userPrivilege->canEditShippingPackages();
        $packageId = FatUtility::int($packageId);
        $data = array();
        $frm = $this->getForm();
        if (0 < $packageId) {
            $data = ShippingPackage::getAttributesById($packageId);
            if (empty($data)) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $frm->fill($data);
        }
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }
    
    private function getSearchForm()
    {
        $frm = new Form('frmSearch');
        $frm->addTextBox(Labels::getLabel('LBL_Keyword', $this->siteLangId), 'keyword', '', array('placeholder' => Labels::getLabel('LBL_Keyword', $this->siteLangId) ));
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Search', $this->siteLangId));
        $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear_Search', $this->siteLangId));
        return $frm;
    }
    
    
    private function getForm()
    {
        $unitTypeArray = ShippingPackage::getUnitTypes($this->siteLangId);
        $frm = new Form('frmShippingPackages');
        $frm->addHiddenField('', 'shippack_id');
        $fld = $frm->addRequiredField(Labels::getLabel('LBL_Package_Name', $this->siteLangId), 'shippack_name');
        $frm->addFloatField(Labels::getLabel('LBL_Length', $this->siteLangId), 'shippack_length');
        $frm->addFloatField(Labels::getLabel('LBL_Width', $this->siteLangId), 'shippack_width');
        $frm->addFloatField(Labels::getLabel('LBL_Height', $this->siteLangId), 'shippack_height');
        
        $frm->addSelectBox(Labels::getLabel('LBL_Unit', $this->siteLangId), 'shippack_units', $unitTypeArray);
        
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $this->siteLangId));
        
        return $frm;
    }
}
