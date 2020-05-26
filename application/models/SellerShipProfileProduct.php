<?php
class SellerShipProfileProduct extends MyAppModel
{
    const DB_TBL = 'tbl_seller_shipping_profile_products';
    const DB_TBL_PREFIX = 'selshipprod_';

    public function __construct()
    {
       
    }

    public static function getSearchObject($langId)
    {
		$srch = new SearchBase(static::DB_TBL, 'selsppro');
		$srch->joinTable(SellerProduct::DB_TBL, 'LEFT JOIN', 'sp.selprod_id = selsppro.selshipprod_selprod_id', 'sp');
		
		$srch->joinTable(SellerProduct::DB_TBL_LANG,
                'LEFT OUTER JOIN',
                'sp_l.selprodlang_selprod_id = sp.selprod_id  AND sp_l.selprodlang_lang_id = '.$langId, 'sp_l' );
        
		
		$srch->joinTable(Product::DB_TBL, 'INNER JOIN', 'p.product_id = sp.selprod_product_id', 'p');
        $srch->joinTable(Product::DB_TBL_LANG, 'LEFT OUTER JOIN', 'p.product_id = p_l.productlang_product_id AND p_l.productlang_lang_id = '. $langId, 'p_l');
		
		$srch->addMultipleFields(array('selprod_id', 'IFNULL(selprod_title  ,IFNULL(product_name, product_identifier)) as product_name', 'product_id'));
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