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
        $frm->addTextBox(Labels::getLabel('LBL_Buyer', $this->adminLangId), 'buyer', '');
        $frm->addTextBox(Labels::getLabel('LBL_Seller_Products', $this->adminLangId), 'seller_products', '');
        $frm->addSelectBox(Labels::getLabel('LBL_Action', $this->adminLangId), 'carthistory_type', CartHistory::actionArr, '', array(), Labels::getLabel('LBL_Select', $this->adminLangId));
        $fld_submit=$frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }
    
    

}
