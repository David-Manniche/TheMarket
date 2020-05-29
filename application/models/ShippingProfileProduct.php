<?php
class ShippingProfileProduct extends MyAppModel
{
    const DB_TBL = 'tbl_shipping_profile_products';
    const DB_TBL_PREFIX = 'shippro_';

    public function __construct()
    {
    }

    public static function getSearchObject()
    {
        $srch = new SearchBase(static::DB_TBL, 'sppro');
        $srch->joinTable(Product::DB_TBL, 'LEFT OUTER JOIN', 'pro.product_id = sppro.shippro_product_id', 'pro');
        $srch->addMultipleFields(array('product_id', 'product_identifier as product_name', 'shippro_shipprofile_id as profile_id'));
        return $srch;
    }
    
    public function addProduct($data)
    {
        if (!FatApp::getDb()->insertFromArray(self::DB_TBL, $data, true, array(), $data)) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }
        return true;
    }

    public static function getUserSearchObject($userId = 0)
    {
        $srch = new SearchBase(static::DB_TBL, 'sppro');
        $fields = array('sppro.shippro_product_id');
        if (0 < $userId) {
            $srch->joinTable(static::DB_TBL, 'LEFT OUTER JOIN', 'spprot.shippro_product_id = sppro.shippro_product_id and spprot.shippro_user_id = ' . $userId, 'spprot');
            $fields[] = 'if(spprot.shippro_user_id > 0, spprot.shippro_user_id, sppro.shippro_user_id) as shippro_user_id';
            $fields[] = 'if(spprot.shippro_user_id > 0, spprot.shippro_shipprofile_id, sppro.shippro_shipprofile_id) as shippro_shipprofile_id';
        } else {
            $fields[] = 'sppro.shippro_user_id';
            $fields[] = 'sppro.shippro_shipprofile_id';
        }
        $srch->addMultipleFields($fields);
        $srch->addGroupBy('sppro.shippro_product_id');
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        return $srch;
    }
}
