<?php
class CartHistory extends FatModel
{
    const DB_TBL = 'tbl_cart_history';
    const DB_TBL_PREFIX = 'carthistory_';
    
    const TYPE_PRODUCT = 1;
     
    const ACTION_ADDED = 1;
    const ACTION_DELETED = 2;
    const ACTION_PURCHASED = 3;
    
    const MAX_EMAIL_COUNT = 2;
    
    private $totalRecords;
    private $totalPages;
    private $pageSize;
    
    public static function saveCartHistory($userId, $selProdId, $qty, $action)
    { 
        $userId = FatUtility::int($userId);
        $selProdId = FatUtility::int($selProdId);
        $qty = FatUtility::int($qty);
        $action = FatUtility::int($action);
        if( $userId < 1 || $selProdId < 1 || $qty < 1 || !in_array($action, array_keys(static::getActionArr()))){
            return false;
        }
        
        $data = array(
            static::DB_TBL_PREFIX.'user_id' => $userId,
            static::DB_TBL_PREFIX.'selprod_id' => $selProdId,
            static::DB_TBL_PREFIX.'type' => static::TYPE_PRODUCT,
            static::DB_TBL_PREFIX.'qty' => $qty,
            static::DB_TBL_PREFIX.'action' => $action,
            static::DB_TBL_PREFIX.'added_on' => date('Y-m-d H:i:s'),
        );
        $record = new TableRecord(static::DB_TBL);
        $record->assignValues($data);
        if (!$record->addNew(array(), $data)) {
            return false;
        }
        return true;
    }
    
    public static function getActionArr($langId = 0)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        } 
        return array(
            static::ACTION_ADDED => Labels::getLabel('LBL_Added', $langId),
            static::ACTION_DELETED => Labels::getLabel('LBL_Deleted', $langId),
            static::ACTION_PURCHASED => Labels::getLabel('LBL_Purchased', $langId)
        );
    }
    
    public function getAbandonedCartList($langId, $userId = 0, $selProdId = 0, $action = 0, $page = 1)
    {   
        $srch = new CartHistorySearch();
        $srch->joinUsers();
        $srch->joinSellerProducts($langId);   
        if($userId > 1){
            $srch->addUserCondition($userId);
        }
        if($selProdId > 1){
            $srch->addSellerProductCondition($selProdId);
        }
        $srch->addActionCondition($action);
        $srch->addEmailCountCondition();
        $srch->addDiscountNotificationCondition();
        $srch->addMultipleFields(array('ch.*', 'user_name', 'selprod_product_id', 'selprod_title')); 
        
        $srch->setPageNumber($page);
        $srch->setPageSize($this->setPageSize());
        $rs = $srch->getResultSet();  
        $this->totalRecords = $srch->recordCount();
        $this->totalPages = $srch->pages();
        $this->pageSize = $this->setPageSize();
        return FatApp::getDb()->fetchAll($rs);        
    }
    
    public function getAbandonedCartProducts($langId, $page = 1)
    {
        $srch = new CartHistorySearch();
        $srch->joinSellerProducts($langId);
        $srch->addActionCondition();
        $srch->addMultipleFields(array('carthistory_selprod_id', 'selprod_title', 'count(carthistory_selprod_id) as product_count')); 
        $srch->addGroupBySellerProduct();
        
        $srch->setPageNumber($page);        
        $srch->setPageSize($this->setPageSize());        
        $rs = $srch->getResultSet();                  
        $this->totalRecords = $srch->recordCount();
        $this->totalPages = $srch->pages();
        $this->pageSize = $this->setPageSize();
        return FatApp::getDb()->fetchAll($rs);        
    }
    
    public function recordCount()
    { 
        return $this->totalRecords;
    }
    
    public function pages()
    {
        return $this->totalPages;
    }
    
    public function getPageSize()
    {
        return $this->pageSize;
    }
    
    public function setPageSize()
    {
        return FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
    }
    
    
    
    
}
