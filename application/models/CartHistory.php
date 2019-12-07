<?php
class CartHistory extends FatModel
{
    const DB_TBL = 'tbl_cart_history';
    const DB_TBL_PREFIX = 'carthistory_';
    
    const TYPE_PRODUCT = 1;
     
    const ACTION_ADDED = 1;
    const ACTION_DELETED = 2;
    const ACTION_PURCHASED = 3;
    
    const actionArr = [self::ACTION_ADDED, self::ACTION_DELETED, self::ACTION_PURCHASED];

    public static function saveCartHistory($userId, $selProdId, $qty, $action)
    { 
        $userId = FatUtility::int($userId);
        $selProdId = FatUtility::int($selProdId);
        $qty = FatUtility::int($qty);
        $action = FatUtility::int($action);
        if( $userId < 1 || $selProdId < 1 || $qty < 1 || !in_array($action, static::actionArr)){
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
    
}
