<?php
class AbandonedCartController extends AdminBaseController
{
   
    public function __construct($action)
    { 
		parent::__construct($action);
        $this->objPrivilege->canViewAbandonedCart(); 
	}
    
    public function index()
    { 
        $frmSearch = $this->getSearchForm();
        $this->set('frmSearch', $frmSearch);
        $this->_template->render();
	}
    
    private function getSearchForm()
    {
        $frm = new Form('frmAbandonedCartSearch');
        $frm->addTextBox(Labels::getLabel('LBL_User', $this->adminLangId), 'user_name');
        $frm->addTextBox(Labels::getLabel('LBL_Seller_Product', $this->adminLangId), 'seller_product');                               
        $frm->addSelectBox(Labels::getLabel('LBL_Cart_Action', $this->adminLangId), 'abandonedcart_action', AbandonedCart::getActionArr($this->adminLangId), '', array(), Labels::getLabel('LBL_Select', $this->adminLangId));
        $frm->addHiddenField('', 'abandonedcart_user_id');
        $frm->addHiddenField('', 'abandonedcart_selprod_id');
        $frm->addHiddenField('', 'page', 1);
        $fld_submit = $frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }
    
    public function search()
    {    
        $frmSearch = $this->getSearchForm();
        $postedData = $frmSearch->getFormDataFromArray(FatApp::getPostedData());                
        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);     
        $userId = FatApp::getPostedData('abandonedcart_user_id', FatUtility::VAR_INT, 0);
        $selProdId = FatApp::getPostedData('abandonedcart_selprod_id', FatUtility::VAR_INT, 0);
        $action = FatApp::getPostedData('abandonedcart_action', FatUtility::VAR_INT, 0);
        
        $abandonedCart = new AbandonedCart();
        $records = $abandonedCart->getAbandonedCartList($this->adminLangId, $userId, $selProdId, $action, $page);
        $this->set("records", $records);
        $this->set('page', $page);
        $this->set('pageSize', $abandonedCart->getPageSize());
        $this->set('recordCount', $abandonedCart->recordCount());        
        $this->set('pageCount', $abandonedCart->pages());
        $this->set('postedData', $postedData);
        $this->_template->render(false, false);
    }
    
    public function products()
    {     
        $this->_template->render();
    }
    
    public function getProducts()
    {   
        $postedData = FatApp::getPostedData();                
        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);     
        $abandonedCart = new AbandonedCart();
        $records = $abandonedCart->getAbandonedCartProducts($this->adminLangId, $page);        
        $this->set("records", $records);
        $this->set('page', $page);
        $this->set('pageSize', $abandonedCart->getPageSize());
        $this->set('recordCount', $abandonedCart->recordCount());        
        $this->set('pageCount', $abandonedCart->pages());
        $this->set('postedData', $postedData);
        $this->_template->render(false, false);
    }
    
    public function discountNotification()
    {
        $userId = FatApp::getPostedData('userId', FatUtility::VAR_INT, 0); 
        $action = FatApp::getPostedData('action', FatUtility::VAR_INT, 0); 
        $couponId = FatApp::getPostedData('couponId', FatUtility::VAR_INT, 0); 
        $selProdId = FatApp::getPostedData('selProdId', FatUtility::VAR_INT, 0); 
        if($userId < 1 || $action < 1 || $couponId < 1 || $selProdId < 1){
            Message::addErrorMessage(Labels::getLabel('MSG_Email_Not_Sent_Invalid_Parameters', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        
        $abandonedCart = new AbandonedCart();
        if(!$abandonedCart->sendDiscountEmail($this->adminLangId, $userId, $action, $couponId, $selProdId)){
            Message::addErrorMessage(Labels::getLabel('MSG_Email_Not_Sent', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }           
        $abandonedCart->updateDiscountNotification($userId, $selProdId);
        $this->set('msg', Labels::getLabel('MSG_Email_Sent_Successful', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }
}
