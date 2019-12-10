<?php
class CartHistorySearch extends SearchBase
{
    public function __construct()
    {
        parent::__construct(CartHistory::DB_TBL, 'ch');
    }

    public function joinUsers()
    { 
        $this->joinTable(User::DB_TBL, 'LEFT OUTER JOIN', CartHistory::DB_TBL_PREFIX.'user_id = user.user_id', 'user');
    }
    
    public function joinSellerProducts($langId)
    {        
        $this->joinTable(SellerProduct::DB_TBL, 'LEFT OUTER JOIN', CartHistory::DB_TBL_PREFIX.'selprod_id = sp.selprod_id', 'sp');
        $langId = FatUtility::int($langId);
        if ($langId > 0) {
            $this->joinTable(SellerProduct::DB_TBL_LANG, 'LEFT OUTER JOIN', 'sp_l.selprodlang_selprod_id = sp.selprod_id AND sp_l.selprodlang_lang_id = '.$langId, 'sp_l');
        }
    }

    public function addUserCondition($userId)
    { 
        $this->addCondition(CartHistory::DB_TBL_PREFIX.'user_id', '=', $userId);
    }
    
    public function addSellerProductCondition($selProdId)
    { 
        $this->addCondition(CartHistory::DB_TBL_PREFIX.'selprod_id', '=', $selProdId);
    }
    
    public function addActionCondition($action = 0)
    { 
        if($action > 0 && $action <= CartHistory::ACTION_PURCHASED){
            $this->addCondition(CartHistory::DB_TBL_PREFIX.'action', '=', $action);
        }else{
            $this->addCondition(CartHistory::DB_TBL_PREFIX.'action', '!=', CartHistory::ACTION_PURCHASED);
        }      
    }
    
    public function addEmailCountCondition()
    { 
        $this->addCondition(CartHistory::DB_TBL_PREFIX.'email_count', '<', CartHistory::MAX_EMAIL_COUNT);
    }
    
    public function addDiscountNotificationCondition()
    {   
        $this->addCondition(CartHistory::DB_TBL_PREFIX.'discount_notification', '=', 0);
    }
    
    public function addGroupBySellerProduct()
    { 
        $this->addGroupBy(CartHistory::DB_TBL_PREFIX.'selprod_id');
    }

}


