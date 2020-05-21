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
		$srch->joinTable(Product::DB_TBL, 'LEFT JOIN', 'pro.product_id = sppro.shippro_product_id', 'pro');
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
}