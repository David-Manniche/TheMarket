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
        $page = FatUtility::int($page);
        $page = ($page > 0) ? $page : 1;
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
        $page = FatUtility::int($page);
        $page = ($page > 0) ? $page : 1; 
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
        if($langId < 1 || $userId < 1 || $couponId < 1 || $selProdId < 1 || !in_array($action, array_keys(static::getActionArr()))){ 
            return false;
        }
        
        $user = new User($userId);
        $userData = $user->getUserInfo(array('user_name', 'credential_email'), false, false, true);
        $couponData = DiscountCoupons::getAttributesById($couponId);
        $selProdData = SellerProduct::getSelProdDataById($selProdId, $langId);
             
        $discount = ($couponData['coupon_discount_in_percent'] == applicationConstants::PERCENTAGE) ? $couponData['coupon_discount_value'].'%' : CommonHelper::displayMoneyFormat($couponData['coupon_discount_value']);        
        $arrReplacements = array(
            '{user_full_name}' => trim($userData['user_name']),
            '{checkout_now}' => CommonHelper::generateFullUrl('GuestUser', 'redirectAbandonedCartUser', array($userId, $selProdId), CONF_WEBROOT_FRONTEND),
            '{coupon_code}' => $couponData['coupon_code'],
            '{discount}' => $discount,
            '{product_name}' => trim($selProdData['selprod_title'])
        );
        
        $tpl = "";
        if($action == static::ACTION_ADDED){                    
            $prodImage = CommonHelper::generateFullUrl('image', 'product', array($selProdData['selprod_product_id'], "THUMB", $selProdId, 0, $langId),CONF_WEBROOT_FRONTEND);
            $arrReplacements['{product_image}'] = $prodImage;
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
    
    public static function sendReminderAbandonedCart()
    { 
        $langId = FatApp::getConfig('CONF_DEFAULT_SITE_LANG', FatUtility::VAR_INT, 1);
        $srch = new CartHistorySearch();
        $srch->joinUsers(true);
        $srch->joinSellerProducts($langId);
        $srch->addActionCondition(static::ACTION_ADDED);
        $srch->addEmailCountCondition();
        $srch->addDiscountNotificationCondition(); 
        $srch->addMultipleFields(array('user_id', 'user_name', 'credential_email', 'selprod_id', 'selprod_product_id', 'selprod_title', 'selprod_price')); 
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $rs = $srch->getResultSet();  
        $records = FatApp::getDb()->fetchAll($rs);        
        
        $prevUserId = 0 ;         
        $productHtml = "";
        $selProdIds = array();
        foreach($records as $key=>$data){ 
            if($prevUserId == 0 || $prevUserId == $data['user_id'] ){
                $prevUserId = $data['user_id'];
            }else{ 
                if(self::sendAbandonedCartEmail($records[$key-1]['user_id'], $records[$key-1]['user_name'], $records[$key-1]['credential_email'], $productHtml)){
                    self::updateReminderCount($records[$key-1]['user_id'], $selProdIds);
                }                    
                $prevUserId = $data['user_id'];
                $productHtml = "";
                $selProdIds = array();
            }

            $selProdIds[] = $data['selprod_id'];
            $tpl = new FatTemplate('', '');
            $tpl->set('data', $data);
            $tpl->set('langId', $langId);
            $productHtml .= $tpl->render(false, false, '_partial/abandoned-cart-product-html.php', true);
            
            if(($key+1) == count($records)){
                if(self::sendAbandonedCartEmail($data['user_id'], $data['user_name'], $data['credential_email'], $productHtml)){
                    self::updateReminderCount($data['user_id'], $selProdIds);
                }                    
            }    
        }    
        return true;
    }
    
    public static function sendAbandonedCartEmail($userId, $userName, $userEmail, $productHtml)
    {   
        $tpl = new FatTemplate('', '');
        $tpl->set('userId', $userId);
        $checkOutButtonHtml = $tpl->render(false, false, '_partial/abandoned-cart-checkout-button.php', true);
        $arrReplacements = array(
            '{user_full_name}' => $userName,
            '{product_detail_table}' => $productHtml.$checkOutButtonHtml
        );
        $tpl = "abandoned_cart_email";
        $langId = FatApp::getConfig('CONF_DEFAULT_SITE_LANG', FatUtility::VAR_INT, 1);
        if(!EmailHandler::sendMailTpl($userEmail, $tpl, $langId, $arrReplacements)) {
            return false;              
        }
        return true;
    }

    public static function updateReminderCount($userId, $selProdIds)
    {
        $userId = FatUtility::int($userId);
        if($userId < 1 || !is_array($selProdIds)){ 
            return false;
        }       
        foreach($selProdIds as $selProdId){
            $where = array('smt' => static::DB_TBL_PREFIX.'user_id = ? AND '.static::DB_TBL_PREFIX.'selprod_id = ?', 'vals' => array($userId, $selProdId));
            $data = array(static::DB_TBL_PREFIX.'email_count' => 'mysql_func_'.static::DB_TBL_PREFIX.'email_count + 1');
            if (!FatApp::getDb()->updateFromArray(static::DB_TBL, $data, $where, true)) {
                return false;
            }
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
