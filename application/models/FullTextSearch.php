<?php
class FullTextSearch
{
	private $elasticSearch;
	public const LIMIT = 100;

	// fetch product data from product table based on status key.
	//lastUpdateProducts
	public static function insertProduct($productId = 0)
	{
		$defaultPlugin = (new self())->getDefaultPlugin();
		if(!$defaultPlugin) {
			return false;
		}
		$productId = FatUtility::int($productId);
		$languages = (new self())->getSystemLanguages();
		if(0 > count($languages)) {
			return false;
		}

		foreach($languages as $language)
		{
			$langId = FatUtility::int($language['language_id']);
			$fullTextSearch = new $defaultPlugin(strtolower($language['language_code']));
			$srch  = Product::getSearchObject($langId);
			$srch->joinTable(Product::DB_PRODUCT_EXTERNAL_RELATIONS,'INNER JOIN',Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX.'product_id ='.Product::DB_TBL_PREFIX.'id');
			$srch->addCondition(Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX.'indexed_for_search', '=', applicationConstants::NO);
			$srch->addCondition(Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX.'lang_id', '=', $langId);
			if(1 > $productId )
			{
				$srch->setFetchRecordCount(self::LIMIT);
			}
			else
			{
				$srch->addCondition(Product::DB_TBL_PREFIX.'id', '=', $productId);
				$srch->doNotLimitRecords(true);
				$srch->doNotCalculateRecords(true);
			}

			$rs    = $srch->getResultSet();
			$products = FatApp::getDb()->fetchAll($srch->getResultSet());

			if(1 > count($products)){
				continue;
			}

			foreach( $products as $key => $product) {

				$productId = FatUtility::int($product['product_id']);

				// Getting Product min price
				$minimumPrice = (new self())->getMinimumPriceOfProduct($productId);
				if(array_key_exists('price',$minimumPrice)){
					$minimumPrice['price'] = (double)$minimumPrice['price'];
				}
				$product['min_price'] =  $minimumPrice;
				// get product rating
				$product['product_rating'] = (double)(new self())->productRating($productId);
				// get product rating
				$productDiscount  = (new self())->getProductDiscount($productId);
				$product['product_discount'] = (double) $productDiscount['discountedValue'];
				$data = array( 'general' => $product );
				// Checking Document Id Exists Or Not
				if(!$response = $fullTextSearch->isDocumentExists($productId)){
					$results = $fullTextSearch->addDocument($productId,$data);
					if(!$results) {
						continue;
					}
					(new self())->updateProductStatus($productId,$langId);
					continue;
				}
				$upDateGeneralData = $fullTextSearch->updateDocument($productId,$data);
				if(!$upDateGeneralData){
					continue;
				}
				(new self())->updateProductStatus($productId,$langId);
			}
		}
		return true;
	}

	public static function insertInventory($productId, $sellerProductId = 0)
	{
		$defaultPlugin = (new self())->getDefaultPlugin();
		if(!$defaultPlugin){
			return false;
		}
		$languages = (new self())->getSystemLanguages();
		if(0 > count($languages)) {
			return false;
		}
		foreach($languages as $language)
		{
			$langId = $language['language_id'];
			$fullTextSearch = new $defaultPlugin(strtolower($language['language_code']));
			$srch  = SellerProduct::getSearchObject($langId);
			if(1 > $productId)
			{
				return false;
			}
			$srch->addCondition('selprod_product_id', '=', $productId);
			if($sellerProductId != 0){
				$srch->addCondition('selprod_id', '=', $sellerProductId);
			}
			$srch->joinTable(SellerProduct::DB_TBL_EXTERNAL_RELATIONS,'INNER JOIN', SellerProduct::DB_TBL_PREFIX.'id ='.SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX.'selprod_id');
			$srch->addCondition(SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX.'indexed_for_search', '=', applicationConstants::NO);
			$srch->addCondition(SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX.'lang_id', '=', $langId);

			$rs    = $srch->getResultSet();
			$sellerProducts = FatApp::getDb()->fetchAll($rs);

			foreach( $sellerProducts as $key => $sellerProduct) {
				$sellerProducts[$key]['shop']     = Shop::getAttributesByUserId($sellerProduct['selprod_user_id'],null,true, $language['language_id']);
				$sellerProducts[$key]['userData'] = User::getAttributesById($sellerProduct['selprod_user_id']);
				$sellerProducts[$key]['options']  = (new self())->getSellerProductOptions($sellerProduct['selprod_id'],$language['language_id']);
				$sellerProducts[$key]['reviews']  = (new self())->getSellerProductReviews($sellerProduct['selprod_id'],$language['language_id']);
				$sellerProducts[$key]['min_price'] = (new self())->getSellerProductMinimumPrice($sellerProduct['selprod_id']);
			}

			if(1 > count($sellerProducts)){
				return false;
			}
			if(!$response = $fullTextSearch->isDocumentExists($productId)){
				return false;
			}
			if(1 > $sellerProductId ) {
				$data = array('inventories' => $sellerProducts);
				$results = $fullTextSearch->updateDocument($productId,$data);
				if(!$results) {
					continue;
				}
				$updateStatus = (new self())->updateSellerProductStatus($productId,$langId);
				continue;
			}

			$dataIndexArray = array('selprod_id' => $sellerProductId);

			$data = array('inventories' => $sellerProducts[0]);

			$results = $fullTextSearch->updateDocumentData($productId,'inventories',$dataIndexArray, $data);

			if(!$results) {
				continue;
			}

			$updateStatus = (new self())->updateSellerProductStatus($productId,$langId);
			continue;
		}
		return true;
	}

	public static function insertbrand($productId = 0)
	{
		$productId = FatUtility::int($productId);
		$defaultPlugin = (new self())->getDefaultPlugin();
		if(!$defaultPlugin){
			return false;
		}
		$languages = (new self())->getSystemLanguages();
		if(0 > count($languages)) {
			return false;
		}
		foreach($languages as $language)
		{
			$langId = FatUtility::int($language['language_id']);
			$fullTextSearch = new $defaultPlugin(strtolower($language['language_code']));
			$srch  = Product::getSearchObject($langId);
			$srch->joinTable(Product::DB_PRODUCT_EXTERNAL_RELATIONS,'INNER JOIN',Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX.'product_id ='.Product::DB_TBL_PREFIX.'id');
			$srch->addCondition(Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX.'lang_id', '=', $langId);
			if(1 > $productId )
			{
				$srch->setFetchRecordCount(self::LIMIT);
			}
			else
			{
				$srch->addCondition(Product::DB_TBL_PREFIX.'id', '=', $productId);
				$srch->addCondition(Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX.'indexed_for_search', '=', applicationConstants::NO);
			}
			$rs    = $srch->getResultSet();
			$products = FatApp::getDb()->fetchAll($srch->getResultSet());

			if(1 > count($products)) {
				return false;
			}

			foreach( $products as $key => $product) {
				$productId = FatUtility::int($product['product_id']);
				if(!$response = $fullTextSearch->isDocumentExists($productId)){
					continue;
				}
				$brand = array();
				$srch  = Brand::getSearchObject($langId);
				$srch->addCondition('brand_id', '=', $product['product_brand_id']);
				$rs    = $srch->getResultSet();
				$brand  = FatApp::getDb()->fetch($rs);
				$data = array('brand' => $brand);
				if(!$result = $fullTextSearch->updateDocument($productId,$data)) {
					continue;
				}
				(new self())->updateProductStatus($productId,$langId);
			}
		}
		return true;
	}

	public static function insertCategories($productId = 0)
	{
		$productId = FatUtility::int($productId);
		$defaultPlugin = (new self())->getDefaultPlugin();
		if(!$defaultPlugin){
			return false;
		}
		$languages = (new self())->getSystemLanguages();
		if(0 > count($languages)) {
			return false;
		}
		foreach($languages as $language)
		{
			$langId = FatUtility::int($language['language_id']);
			$fullTextSearch = new $defaultPlugin(strtolower($language['language_code']));
			$srch  = Product::getSearchObject($language['language_id']);
			$srch->joinTable(Product::DB_PRODUCT_EXTERNAL_RELATIONS,'INNER JOIN',Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX.'product_id ='.Product::DB_TBL_PREFIX.'id');
			$srch->addCondition(Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX.'indexed_for_search', '=', applicationConstants::NO);
			$srch->addCondition(Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX.'lang_id', '=', $langId);
			if(1 > $productId )
			{
				$srch->setFetchRecordCount(self::LIMIT);
			}
			else
			{
				$srch->addCondition(Product::DB_TBL_PREFIX.'id', '=', $productId);
			}

			$rs    = $srch->getResultSet();
			$products = FatApp::getDb()->fetchAll($srch->getResultSet());
			if(1 > count($products)){
				return false;
			}
			foreach( $products as $key => $product) {
				$productId = FatUtility::int($product['product_id']);
				if(!$response = $fullTextSearch->isDocumentExists($productId)){
					continue;
				}
				$categories = array();
				$srch = new SearchBase(Product::DB_TBL_PRODUCT_TO_CATEGORY, 'ptc');
        		$srch->addCondition(Product::DB_TBL_PRODUCT_TO_CATEGORY_PREFIX . 'product_id', '=', $productId);
        		$srch->joinTable(ProductCategory::DB_TBL, 'INNER JOIN', ProductCategory::DB_TBL_PREFIX.'id = ptc.'.Product::DB_TBL_PRODUCT_TO_CATEGORY_PREFIX.'prodcat_id', 'cat');
				$srch->joinTable(ProductCategory::DB_TBL_LANG, 'LEFT OUTER JOIN', ProductCategory::DB_TBL_LANG_PREFIX.'prodcat_id = '.ProductCategory::tblFld('id').' and '.ProductCategory::DB_TBL_LANG_PREFIX.'lang_id = '.$langId);
				$rs = $srch->getResultSet();
        		$categories = FatApp::getDb()->fetchAll($rs);
				$data = array('categories' => $categories);

				if(!$result = $fullTextSearch->updateDocument($productId,$data)) {
					continue;
				}
				(new self())->updateProductStatus($productId,$langId);
			}
		}
		return true;
	}

	public static function insertOptions($productId = 0)
	{
		$productId = FatUtility::int($productId);
		$defaultPlugin = (new self())->getDefaultPlugin();
		if(!$defaultPlugin) {
			return false;
		}
		$languages = (new self())->getSystemLanguages();
		if(0 > count($languages)) {
			return false;
		}
		foreach($languages as $language)
		{
			$langId = FatUtility::int($language['language_id']);
			$fullTextSearch = new $defaultPlugin(strtolower($language['language_code']));
			$srch  = Product::getSearchObject($language['language_id']);
			$srch->joinTable(Product::DB_PRODUCT_EXTERNAL_RELATIONS,'INNER JOIN',Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX.'product_id ='.Product::DB_TBL_PREFIX.'id');
			$srch->addCondition(Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX.'indexed_for_search', '=', applicationConstants::NO);
			$srch->addCondition(Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX.'lang_id', '=', $langId);
			if(1 > $productId )
			{
				$srch->setFetchRecordCount(self::LIMIT);
			}
			else
			{
				$srch->addCondition(Product::DB_TBL_PREFIX.'id', '=', $productId);
			}
			$rs    = $srch->getResultSet();
			$products = FatApp::getDb()->fetchAll($srch->getResultSet());
			if(1 > count($products)){
				return false;
			}
			foreach( $products as $key => $product) {
				$productId = FatUtility::int($product['product_id']);
				if(!$response = $fullTextSearch->isDocumentExists($productId)){
					continue;
				}
				$allOptions = array();
				$srch = new SearchBase(Product::DB_PRODUCT_TO_OPTION);
		        $srch->addCondition(Product::DB_PRODUCT_TO_OPTION_PREFIX . 'product_id', '=', $productId);
		        $srch->joinTable(OptionValue::DB_TBL, 'LEFT JOIN', 'prodoption_option_id = opval.optionvalue_option_id', 'opval');
		        $rs = $srch->getResultSet();
		        $allOptions = FatApp::getDb()->fetchAll($rs);
				$data = array('options' => $allOptions);
				if(!$result = $fullTextSearch->updateDocument($productId,$data)) {
					continue;
				}
				(new self())->updateProductStatus($productId,$langId);
			}
		}
		return true;
	}


	private function getDefaultPlugin()
	{
		$plugin = new Plugin(Plugin::TYPE_FULL_TEXT_SEARCH);
		$defaultPlugin = $plugin->getDefaultPluginData(Plugin::TYPE_FULL_TEXT_SEARCH,"plugin_code");
		if(0 > $defaultPlugin){
			return false;
		}
		require_once CONF_INSTALLATION_PATH . 'library/plugins/full-text-search/'.$defaultPlugin.'.php';
		return $defaultPlugin;
	}

	private function getSystemLanguages()
	{
		$srch = Language::getSearchObject();
		$srch->doNotLimitRecords(true);
        $srch->doNotCalculateRecords(true);
		$languages = FatApp::getDb()->fetchAll($srch->getResultSet());
		return $languages;
	}
	/* Get Seller Product Options */
	private function getSellerProductOptions($sellerProductId,$langId) {

		$allOptions = array();
		$srch = new SearchBase(SellerProduct::DB_TBL_SELLER_PROD_OPTIONS);
		$srch->addCondition(SellerProduct::DB_TBL_SELLER_PROD_OPTIONS_PREFIX.'selprod_id', '=', $sellerProductId);
		$srch->joinTable(Option::DB_TBL,'LEFT JOIN', SellerProduct::DB_TBL_SELLER_PROD_OPTIONS_PREFIX.'option_id ='.Option::DB_TBL_PREFIX.'id');
		$srch->joinTable(Option::DB_TBL_LANG,'LEFT JOIN', Option::DB_TBL_LANG_PREFIX.'option_id ='. Option::DB_TBL_PREFIX. 'id'); // lang
		$srch->joinTable(OptionValue::DB_TBL,'LEFT JOIN', SellerProduct::DB_TBL_SELLER_PROD_OPTIONS_PREFIX.'optionvalue_id ='.OptionValue::DB_TBL_PREFIX.'id');
		$srch->addCondition(Option::DB_TBL_LANG_PREFIX.'lang_id', '=', $langId);
		$rs = $srch->getResultSet();

        $allOptions = FatApp::getDb()->fetchAll($rs);
		return $allOptions;
	}

	/* Collecting Seller Product reviews  */
	private function getSellerProductReviews($sellerProductId,$langId)
    {
		$sellerProductReviews = array();
		$sellerProductId = FatUtility::int($sellerProductId);
		$srch = new SearchBase(SelProdReview::DB_TBL);
		$srch->addCondition(SelProdReview::DB_TBL_PREFIX.'selprod_id', '=',$sellerProductId);
		$srch->addCondition(SelProdReview::DB_TBL_PREFIX.'lang_id',  '=', $langId);
		$rs = $srch->getResultSet();
        $sellerProductReviews = FatApp::getDb()->fetchAll($rs);

		if(1 > count($sellerProductReviews) ){
			return $sellerProductReviews;
		}
		foreach($sellerProductReviews as  $key => $selReview){
			$user = array();
			$user = User::getAttributesById($selReview[SelProdReview::DB_TBL_PREFIX.'postedby_user_id']);
			$sellerProductReviews[$key]['user'] = $user;
		}
		return $sellerProductReviews;
	}

	/* Collecting Seller Product Minimum Price */
	private function getSellerProductMinimumPrice($sellerProductId)
	{

		$sellerProductId = FatUtility::int($sellerProductId);
		$minimumPrice = array();

		$srch = new SearchBase(Product::DB_PRODUCT_MIN_PRICE);
		$srch->doNotLimitRecords();
		$srch->doNotCalculateRecords();
		$srch->addCondition(Product::DB_PRODUCT_MIN_PRICE_PREFIX.'selprod_id','=', $sellerProductId);
		$srch->addMultipleFields(array('pmp_product_id','pmp_selprod_id','pmp_min_price as theprice','pmp_splprice_id','if(pmp_splprice_id,1,0) as special_price_found'));
		$rs = $srch->getResultSet();
		$minimumPrice = FatApp::getDb()->fetch($rs);

		return $minimumPrice;
	}

	private function updateProductStatus($productId,$langId)
	{
		if(FatApp::getDb()->updateFromArray(Product::DB_PRODUCT_EXTERNAL_RELATIONS, array(Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX.'indexed_for_search' => applicationConstants::YES), array('smt' => Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX. 'product_id = ? and '.Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX.'lang_id = ? ' , 'vals' => array($productId,$langId)))){
			return true;
		}
		return false;
	}

	private function updateSellerProductStatus($productId, $langId, $sellerProductId = 0)
	{
		if(1 > $sellerProductId ) {
			FatApp::getDb()->updateFromArray(SellerProduct::DB_TBL_EXTERNAL_RELATIONS, array(SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX.'indexed_for_search' => applicationConstants::YES), array('smt' => SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX.'product_id = ? and '.SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX.'lang_id = ?','vals' => array($productId,$langId)));
		}

		FatApp::getDb()->updateFromArray(SellerProduct::DB_TBL_EXTERNAL_RELATIONS, array(SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX.'indexed_for_search' => applicationConstants::YES), array('smt' => SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX.'selprod_id = ?', 'vals' => array($sellerProductId)));
		return true;
	}


	/* getting Product Minimum price */
	private function getMinimumPriceOfProduct($productId) {

		$srch  = SellerProduct::getSearchObject();
		//$srch->joinTable(User::DB_TBL, 'LEFT OUTER JOIN', User::DB_TBL_PREFIX.'id ='.SellerProduct::DB_TBL_PREFIX.'user_id');
		$srch->addCondition('selprod_product_id', '=', $productId);
        $srch->doNotLimitRecords();
        $srch->doNotCalculateRecords();
		$srch->addMultipleFields(array('selprod_id'));
		$rs    = $srch->getResultSet();
		$sellerProducts = FatApp::getDb()->fetchAll($rs);
		if(1 > count($sellerProducts)){
			return array();
		}
		foreach($sellerProducts as $key => $selerProduct){
			 $sellerProductIds[] = $selerProduct['selprod_id'];
		}
		if(1> count($sellerProductIds)){
			return array();
		}
		$srch = new SearchBase(Product::DB_PRODUCT_MIN_PRICE);
		$srch->addCondition(Product::DB_PRODUCT_MIN_PRICE_PREFIX.'selprod_id', "IN", $sellerProductIds);
		$srch->addMultipleFields(array('pmp_selprod_id as selprod_id','MIN(pmp_min_price) as price'));
		$srch->doNotLimitRecords();
        $srch->doNotCalculateRecords();
		$rs = $srch->getResultSet();
		$minPriceSeller = FatApp::getDb()->fetch($rs);
		return $minPriceSeller;
	}

	/* Product Rating */

	private function productRating($productId) {

		$selProdReviewObj = new SelProdReviewSearch();
		$selProdReviewObj->joinSelProdRating();
		$selProdReviewObj->addCondition('sprating_rating_type', '=', SelProdRating::TYPE_PRODUCT);
		$selProdReviewObj->doNotCalculateRecords();
		$selProdReviewObj->doNotLimitRecords();
		$selProdReviewObj->addGroupBy('spr.spreview_product_id');
		$selProdReviewObj->addCondition('spr.spreview_status', '=', SelProdReview::STATUS_APPROVED);
		$selProdReviewObj->addMultipleFields(array("ROUND(AVG(sprating_rating),2) as prod_rating"));
		$selProdReviewObj->addCondition('spr.spreview_product_id', '=', $productId);
		$rs    = $selProdReviewObj->getResultSet();
		$ProductRating = FatApp::getDb()->fetch($rs);
		if(!isset($ProductRating['prod_rating'])){
			return 0;
		}
		return $ProductRating['prod_rating'];
	}

	private function getProductDiscount($productId){
		$productDiscount = array();
		$srch = new SearchBase(SellerProduct::DB_TBL);
		$srch->joinTable(Product::DB_PRODUCT_MIN_PRICE, "LEFT JOIN", SellerProduct::DB_TBL_PREFIX."id =".Product::DB_PRODUCT_MIN_PRICE_PREFIX."selprod_id");
		$srch->addCondition(SellerProduct::DB_TBL_PREFIX."product_id", '=', $productId);
		$srch->doNotCalculateRecords();
		$srch->doNotLimitRecords();
		$srch->addMultipleFields(array("selprod_id, ROUND(((selprod_price - pmp_min_price)*100)/selprod_price) as discountedValue"));
		$rs    = $srch->getResultSet();
		$productDiscount = FatApp::getDb()->fetch($rs);

		if(1 > count($productDiscount)){
			return $productDiscount;
		}
		return $productDiscount;
	}

}
