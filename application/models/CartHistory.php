<?php
class CartHistory extends FatModel
{
    const DB_TBL = 'tbl_cart_history';
    const DB_TBL_PREFIX = 'carthistory_';

    public static function saveLog($userId, $selProdId, $qty, $action, $amount)
    { 
        $userId = FatUtility::int($userId);
        $selProdId = FatUtility::int($selProdId);
        $qty = FatUtility::int($qty);
        $action = FatUtility::int($action);
        $amount = FatUtility::int($amount);
        if( $userId < 1 || $selProdId < 1 || $qty < 1 || !in_array($action, array_keys(static::getActionArr()))){
            return false;
        }
        
        $data = array(
            static::DB_TBL_PREFIX.'user_id' => $userId,
            static::DB_TBL_PREFIX.'selprod_id' => $selProdId,
            static::DB_TBL_PREFIX.'qty' => $qty,
            static::DB_TBL_PREFIX.'action' => $action,
            static::DB_TBL_PREFIX.'amount' => $amount,
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
