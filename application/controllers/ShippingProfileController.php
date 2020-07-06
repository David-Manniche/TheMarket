<?php
class ShippingProfileController extends SellerBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->userPrivilege->canViewShippingProfiles(UserAuthentication::getLoggedUserId());
    }

    public function index()
    {
        $searchFrm = $this->getSearchForm();
        $this->set("searchFrm", $searchFrm);
        $this->_template->render();
    }
    
    public function search()
    {
        $userId = UserAuthentication::getLoggedUserId();
        $pageSize = FatApp::getConfig('conf_page_size', FatUtility::VAR_INT, 10);
        $searchForm = $this->getSearchForm();
        $data = FatApp::getPostedData();
        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        $post = $searchForm->getFormDataFromArray($data);
        
        $prodCountSrch = ShippingProfileProduct::getSearchObject($this->siteLangId);
        $prodCountSrch->addCondition('shippro_user_id', '=', $this->userParentId);
        $prodCountSrch->doNotCalculateRecords();
        $prodCountSrch->doNotLimitRecords();
        $prodCountSrch->addGroupBy('shippro_shipprofile_id');
        $prodCountSrch->addMultipleFields(array("COUNT(*) as totalProducts, shippro_shipprofile_id"));
        $prodCountQuery = $prodCountSrch->getQuery();
        
        $srch = ShippingProfile::getSearchObject();
        $srch->addCondition('sprofile.shipprofile_user_id', '=', $userId);
        $srch->joinTable('(' . $prodCountQuery . ')', 'LEFT OUTER JOIN', 'sproduct.shippro_shipprofile_id = sprofile.shipprofile_id', 'sproduct');
        
        $srch->addMultipleFields(array('sprofile.*', 'if(sproduct.totalProducts is null, 0, sproduct.totalProducts) as totalProducts'));
        
        $srch->addOrder('shipprofile_default', 'DESC');
        $srch->addOrder('shipprofile_id', 'ASC');
        
        if (!empty($post['keyword'])) {
            $srch->addCondition('sprofile.shipprofile_name', 'like', '%' . $post['keyword'] . '%');
        }
        
        $srch->setPageNumber($page);
        $srch->setPageSize($pageSize);
        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
        $zones = array();
        if (!empty($records)) {
            $profileIds = array_column($records, 'shipprofile_id');
            $profileIds = array_map('intval', $profileIds);
            $zones = $this->getZones($profileIds);
        }
        
        $this->set('arr_listing', $records);
        $this->set('zones', $zones);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pageSize);
        $this->set('postedData', $post);
        $this->_template->render(false, false);
    }
    
    public function form($profileId = 0)
    {
        $userId = UserAuthentication::getLoggedUserId();
        $profileId = FatUtility::int($profileId);
        $frm = $this->getForm($profileId);
        $data = [];
        if (0 < $profileId) {
            $data = ShippingProfile::getAttributesById($profileId);
            if ($data === false) {
                Message::addErrorMessage(Labels::getLabel('LBL_Invalid_Request', $this->siteLangId));
                FatApp::redirectUser(CommonHelper::generateUrl('shippingProfile'));
            }
            if ($data['shipprofile_user_id'] != $userId) {
                Message::addErrorMessage(Labels::getLabel('LBL_Invalid_Request', $this->siteLangId));
                FatApp::redirectUser(CommonHelper::generateUrl('shippingProfile'));
            }
            $frm->fill($data);
        }
        
        $this->set('profile_id', $profileId);
        $this->set('frm', $frm);
        $this->set('profileData', $data);
        $this->set('canEdit', $this->userPrivilege->canEditShippingProfiles(UserAuthentication::getLoggedUserId(), true));
        $this->_template->render();
    }
    
    public function setup()
    {
        $this->userPrivilege->canEditShippingProfiles(UserAuthentication::getLoggedUserId());
        $frm = $this->getForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (empty($post)) {
            Message::addErrorMessage(Labels::getLabel('LBL_Invalid_Request', $this->siteLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $profileId = $post['shipprofile_id'];
        unset($post['shipprofile_id']);

        $spObj = new ShippingProfile($profileId);
        $spObj->assignValues($post);

        if (!$spObj->save()) {
            Message::addErrorMessage($spObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        
        $profileId = $spObj->getMainTableRecordId();
        $this->set('msg', Labels::getLabel('LBL_Updated_Successfully', $this->siteLangId));
        $this->set('profileId', $profileId);
        $this->_template->render(false, false, 'json-success.php');
    }
    
    private function getZones($profileIds)
    {
        if (empty($profileIds)) {
            return array();
        }
        $zSrch = ShippingProfileZone::getSearchObject();
        $zSrch->addCondition("shipprozone_shipprofile_id", "IN", $profileIds);
        $zRs = $zSrch->getResultSet();
        $zonesData = FatApp::getDb()->fetchAll($zRs);
        $zones = array();
        if (!empty($zonesData)) {
            foreach ($zonesData as $zone) {
                $profileId = $zone['shipprozone_shipprofile_id'];
                $zones[$profileId][] = $zone;
            }
        }
        return $zones;
    }
    
    private function getSearchForm()
    {
        $frm = new Form('frmSearch');
        $frm->addTextBox(Labels::getLabel('LBL_Keyword', $this->siteLangId), 'keyword');
        $fldSubmit = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Search', $this->siteLangId));
        $fldCancel = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear_Search', $this->siteLangId));
        $fldSubmit->attachField($fldCancel);
        return $frm;
    }
    
    private function getForm($profileId = 0)
    {
        $userId = UserAuthentication::getLoggedUserId();
        $profileId = FatUtility::int($profileId);
        $frm = new Form('frmShippingProfile');
        $frm->addHiddenField('', 'shipprofile_id', $profileId);
        $frm->addHiddenField('', 'shipprofile_user_id', $userId);
        $fld = $frm->addRequiredField(Labels::getLabel('LBL_Profile_Name', $this->siteLangId), 'shipprofile_name');
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $this->siteLangId));
        return $frm;
    }
}