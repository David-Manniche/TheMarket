<?php
class ShippingProfileProductsController extends SellerBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
	}
	
	public function index($profileId)
    {
		$frm = $this->getForm($profileId);
        $this->set("frm", $frm);
		$this->_template->render(false, false);
    }
	
	public function search($profileId)
	{
		$pageSize = FatApp::getConfig('conf_page_size', FatUtility::VAR_INT, 10);
        $post = FatApp::getPostedData();
        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        
		$srch = SellerShipProfileProduct::getSearchObject($this->siteLangId);
		$srch->addCondition('selshipprod_shipprofile_id', '=', $profileId);
		$srch->addOrder('product_name', 'ASC');
        $srch->setPageNumber($page);
        $srch->setPageSize($pageSize);
        $rs = $srch->getResultSet();
		$records = FatApp::getDb()->fetchAll($rs);
		
		$profileData = ShippingProfile::getAttributesById($profileId);
		
		$this->set('productsData', $records);
		$this->set('profileId', $profileId);
		$this->set('profileData', $profileData);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pageSize);
        $this->set('postedData', $post);
        $this->_template->render(false, false);
	}
	
	public function setup()
	{
        $frm = $this->getForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
		
        if (false === $post) {
            Message::addErrorMessage(Labels::getLabel('LBL_Invalid_Request', $this->siteLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
		$data = array(
			'selshipprod_shipprofile_id' => $post['selshipprod_shipprofile_id'],
			'selshipprod_selprod_id' => $post['selshipprod_selprod_id']
		);
		
        $spObj = new SellerShipProfileProduct();
        if (!$spObj->addProduct($data)) {
            Message::addErrorMessage($spObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('msg', Labels::getLabel('LBL_Updated_Successfully', $this->siteLangId));
        $this->_template->render(false, false, 'json-success.php');
	}
	
	public function removeProduct($productId)
	{
		$sObj = new SellerShipProfileProduct();
		$userId = UserAuthentication::getLoggedUserId();
		$defaultProfileId = ShippingProfile::getDefaultProfileId($userId);
		/* [ REMOVE PRODUCT FROM CURRENT PROFILE AND ADD TO DEFAULT PROFILE */
		$data = array(
			'selshipprod_shipprofile_id' => $defaultProfileId,
			'selshipprod_selprod_id' => $productId
		);
		
		$spObj = new SellerShipProfileProduct();
        if (!$spObj->addProduct($data)) {
            Message::addErrorMessage($spObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
		/* ] */
		
		$this->set('msg', Labels::getLabel('LBL_Product_Removed_from_current_profile.', $this->siteLangId));
        $this->_template->render(false, false, 'json-success.php');
	}
	
	private function getForm($profileId = 0)
	{
		$profileId = FatUtility::int($profileId);
        $frm = new Form('frmProfileProducts');
        $frm->addHiddenField('', 'selshipprod_shipprofile_id', $profileId);
        $frm->addHiddenField('', 'selshipprod_selprod_id', '')->requirements()->setRequired(true);
        $fld = $frm->addTextBox(Labels::getLabel('', $this->siteLangId), 'product_name');
		$frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $this->siteLangId));
        return $frm;
	}
}