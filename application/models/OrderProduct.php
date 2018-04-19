<?php
class OrderProduct extends MyAppModel{
	
	const DB_TBL = 'tbl_order_products';
	const DB_TBL_PREFIX = 'op_';
	
	const DB_TBL_LANG = 'tbl_order_products_lang';
	const DB_TBL_CHARGES = 'tbl_order_product_charges';
	const DB_TBL_CHARGES_PREFIX= 	'opcharge_';
	const DB_TBL_OP_TO_SHIPPING_USERS = 'tbl_order_product_to_shipping_users';
	
	const CHARGE_TYPE_TAX = 1;
	const CHARGE_TYPE_DISCOUNT = 2;
	const CHARGE_TYPE_SHIPPING = 3;
	/* const CHARGE_TYPE_BATCH_DISCOUNT = 4; */
	const CHARGE_TYPE_REWARD_POINT_DISCOUNT = 5; 
	const CHARGE_TYPE_VOLUME_DISCOUNT = 6;
	const CHARGE_TYPE_ADJUST_SUBSCRIPTION_PRICE = 7;
	
	public function __construct($id = 0) {
		parent::__construct ( static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id );
	}
	
	public static function getSearchObject( $langId = 0 ) {
		$srch = new SearchBase(static::DB_TBL, 'op');

		if ( $langId > 0) {
			$srch->joinTable( static::DB_TBL_LANG, 'LEFT OUTER JOIN',
			'op_l.oplang_op_id = o.op_id
			AND op_l.oplang_lang_id = ' . $langId, 'op_l');
		}
		
		return $srch;
	}

	public static function getChargeTypeArr($langId){
		$langId = FatUtility::int($langId);
		if($langId < 1){
			$langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
		}
		return array(
			static::CHARGE_TYPE_TAX =>	Labels::getLabel('LBL_Order_Product_Tax_Charges', $langId),
			static::CHARGE_TYPE_DISCOUNT =>	Labels::getLabel('LBL_Order_Product_Discount_Charges', $langId),
			static::CHARGE_TYPE_SHIPPING	=>	Labels::getLabel('LBL_Order_Product_Shipping_Charges', $langId),			
			/* static::CHARGE_TYPE_BATCH_DISCOUNT	=>	Labels::getLabel('LBL_Order_Product_Batch_Discount', $langId),			 */
			static::CHARGE_TYPE_REWARD_POINT_DISCOUNT	=>	Labels::getLabel('LBL_Order_Product_Reward_Point', $langId),
			static::CHARGE_TYPE_VOLUME_DISCOUNT	=>	Labels::getLabel('LBL_Order_Product_Volume_Discount', $langId),
		);
	}

	public static function getOpIdArrByOrderId($orderId){
		$opSrch = static::getSearchObject();
		$opSrch->doNotCalculateRecords();
		$opSrch->doNotLimitRecords();
		$opSrch->addMultipleFields(array('op_id'));
		$opSrch->addCondition('op_order_id','=',$orderId);
		$rs = $opSrch->getResultSet();
		return $row = FatApp::getDb()->fetchAll($rs,'op_id');
	}	
}