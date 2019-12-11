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
    
    public function sendDiscountEmail($langId, $userId, $action, $couponId, $selProdId)
    { 
        $langId = FatUtility::int($langId);
        $userId = FatUtility::int($userId);
        $action = FatUtility::int($action);
        $couponId = FatUtility::int($couponId);
        $selProdId = FatUtility::int($selProdId);
        if($langId < 1 || $userId < 1 || $action < 1 || $couponId < 1 || $selProdId < 1){ 
            return false;
        }
        
        $user = new User($userId);
        $userData = $user->getUserInfo(array('user_name', 'credential_email'), false, false, true);
        $couponData = DiscountCoupons::getAttributesById($couponId);
        $selProdData = SellerProduct::getSelProdDataById($selProdId, $langId);
                
        $discount = ($couponData['coupon_discount_in_percent'] == applicationConstants::PERCENTAGE) ? $couponData['coupon_discount_value'].'%' : CommonHelper::displayMoneyFormat($couponData['coupon_discount_value']);        
        $arrReplacements = array(
            '{user_full_name}' => trim($userData['user_name']),
            '{checkout_now}' => CommonHelper::generateFullUrl('GuestUser', 'redirectUser', array($userId, $selProdId)),
            '{coupon_code}' => $couponData['coupon_code'],
            '{discount}' => $discount,
            '{product_name}' => trim($selProdData['selprod_title'])
        );
        
        $tpl = "";
        if($action == static::ACTION_ADDED){            
            $productImages = AttachedFile::getMultipleAttachments(AttachedFile::FILETYPE_PRODUCT_IMAGE, $selProdData['selprod_product_id'], 0, 0, false, 0, 0, true);
            $productImages = reset($productImages);             
            $prodImg = CommonHelper::generateFullUrl('image','product', array($productImages['afile_record_id'], "THUMB",$productImages['afile_id']),CONF_WEBROOT_FRONTEND);
            $arrReplacements['{product_image}'] = $prodImg;
            $arrReplacements['{product_price}'] = CommonHelper::displayMoneyFormat($selProdData['selprod_price']);
            $tpl = "abandoned_cart_discount_notification";
        }        
        if($action == static::ACTION_DELETED){
            $tpl = "abandoned_cart_deleted_discount_notification";
        }         
        if(!EmailHandler::sendMailTpl($userData['credential_email'], $tpl, $langId, $arrReplacements)) {
            return false;
        }
        return true;        
    }
    
    public function updateDiscountNotification($userId, $selProdId)
    {
        $userId = FatUtility::int($userId);
        $selProdId = FatUtility::int($selProdId);
        if($userId < 1 || $selProdId < 1){ 
            return false;
        }
        
        $where = array('smt' => static::DB_TBL_PREFIX.'user_id = ? AND '.static::DB_TBL_PREFIX.'selprod_id = ?', 'vals' => array($userId, $selProdId));
        if (!FatApp::getDb()->updateFromArray(static::DB_TBL, array(static::DB_TBL_PREFIX.'discount_notification' => 1), $where)) {
            return false;
        }
        return true;
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
