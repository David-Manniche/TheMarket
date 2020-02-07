<?php
class FullTextSearch
{
	public const SIZE = 50;
	public const SEARCHRESULTSIZE = 12;
	
	/* [ Start ElasticSearch Search Functions */
	public static function search($criteria = array(),$langId, $page = 1) {
		$size = self::SEARCHRESULTSIZE;
		$from = $size * ($page - 1);
		
		$searchData = array();
		$search["must"] = array();
		$langId = FatUtility::int($langId);
		$defaultPlugin = (new self())->getDefaultPlugin();
		if(!$defaultPlugin) 
		{
			return false;
		}
		$fullTextSearch = new $defaultPlugin($langId);
		if(array_key_exists('keyword',$criteria)) 
		{
			$textSearch = (new self())->textSearchConditions($criteria['keyword']);
			array_push($search["must"], $textSearch);
		}
		
		/*if(array_key_exists('category',$criteria))
		{
			$category_id = FatUtility::int($criteria['category']);
			$catCode = ProductCategory::getAttributesById($category_id, 'prodcat_code');
			$categoryFilter['wildcard'] = ['categories.prodcat_code'=> [ "value" => $catCode.'*',"boost"=> "2.0", "rewrite"=>"constant_score" ] ];
			array_push($search["must"], $categoryFilter);
		}*/
		
		if(array_key_exists('brand',$criteria))
		{
			$brandsFilters = (new self())->brandConditions($criteria['brand']);
			array_push($search["must"], $brandsFilters);
		}
		
		
		$data = $fullTextSearch->search($search, $from, $size);
		
		return $data;
	}
	
	
	
	/* Search all the brands and calculating aggregation price results */
	
	public static function getSearchBrands($criteria,$langId)
	{
		$allProductbrands = array();
		$search["must"] = [];
		if(array_key_exists('keyword',$criteria) && !empty($criteria['keyword'])) 
		{
			$textSearch = (new self())->textSearchConditions($criteria['keyword']);
			array_push($search["must"], $textSearch);
		}
		
		$defaultPlugin = (new self())->getDefaultPlugin();
		
		$fullTextSearch = new $defaultPlugin($langId);
		
		$allProductbrands = $fullTextSearch->search($search, 0, 1000, false, array('brand.brand_id','brand.brand_name'),'brand.brand_name',array('brand.brand_name.keyword' => 'asc') );
		
		return $allProductbrands;
		
		
	}
	
	private function brandConditions($brands)
	{
		$brandsFilters['bool']['should'] = array();
		foreach($brands as $key => $brand) 
		{
			$brandsFilters['bool']['should'][$key] = ['match' => ['brand.brand_id' => $brand ]];
		}
		return $brandsFilters;
	}
	
	/* 
	*	Pass text  in the function
	*   return @array for text based condition
	*/
	
	private function textSearchConditions($keyword)
	{
		$textSearch = 	[ 'bool' =>
							[ 'should'=>
					 			[
									/* [ product general fields */
									['match' => ['general.product_name' => $keyword ]  ],
									['match' => ['general.product_model' => $keyword ] ],
									['match' => ['general.product_description' => $keyword ] ],
									['match' => ['general.product_tags_string' => $keyword ] ],
									/*  product general fields ] */
									
									/* [ inventory fields */
									['match' => ['inventories.selprod_title' => $keyword ] ],
									['match' => ['inventories.selprod_sku' => $keyword ] ],
									/*  inventory fields ] */
									
									/* [ brands fields */
									['match' => ['brand.brand_name' => ['query' => $keyword , 'fuzziness'=> '1' ] ] ],
									['match' => ['brand_short_description' => $keyword ] ],
									/*  brands fields ] */
									
									/* [ categories fields */
									['match' => ['categories.prodcat_identifier' => ['query' => $keyword , 'fuzziness' => '1' ] ] ],
									['match' => ['categories.prodcat_name' => $keyword ] ],
									/*  categories fields ] */
									
									/* [ options fields */
									['match' => ['options.optionvalue_identifier' => ['query' => $keyword , 'fuzziness' => '1' ] ] ],
									['match' => ['options.optionvalue_name' => ['query' => $keyword , 'fuzziness' => '1' ] ] ],
									/*  options fields ] */
								]
							]
					];
		return  $textSearch;
	}
	
	/* End ElasticSearch Search Functions ] */
	
	
	
	
	
	
	/* [ Start ElasticSearch Data Insert Functions */
	public static function insertProduct($productId = 0)
	{
		$defaultPlugin = (new self())->getDefaultPlugin();
		if(!$defaultPlugin) 
		{
			return false;
		}

		$productId = FatUtility::int($productId);
		$languages = Language::getAllNames();
		if(0 > count($languages)) 
		{
			return false;
		}

		foreach($languages as $langId => $language)
		{
			$langId = FatUtility::int($langId);
			$fullTextSearch = new $defaultPlugin($langId);
			
			$srch = new ProductSearch($langId);
			$srch->addMultipleFields(array('product_id','product_name', 'product_type', 'product_model', 'product_seller_id', 'product_updated_on', 'product_active', 'product_approved', 'product_upc', 'product_isbn', 'product_ship_country', 'product_ship_free', 'product_cod_enabled', 'product_short_description', 'product_description', 'product_tags_string', 'theprice', 'selprod_id','prod_rating as product_rating', 'brand_id','brand_name','brand_short_description','brand_active' ));
			
			$srch->joinTable( Product::DB_PRODUCT_EXTERNAL_RELATIONS, 'INNER JOIN', Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX . 'product_id =' .Product::DB_TBL_PREFIX.  'id' );
			$srch->addCondition( Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX. 'indexed_for_search', '=', applicationConstants::NO );
			$srch->addCondition( Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX. 'lang_id', '=', $langId );
			
			$srch->joinForPrice();
			$srch->joinProductRating();
			$srch->joinBrands();
			
			if(1 > $productId )
			{
				$srch->setPageSize(FatUtility::int(self::SIZE));
			}
			else
			{
				$srch->addCondition(Product::DB_TBL_PREFIX.'id', '=', $productId);
				$srch->doNotLimitRecords(true);
				$srch->doNotCalculateRecords(true);
			}
			
			$rs    = $srch->getResultSet();
			$products = FatApp::getDb()->fetchAll($srch->getResultSet());
			
			if(1 > count($products))
			{
				continue;
			}
			
			foreach( $products as $key => $product) 
			{
				$productId = FatUtility::int($product['product_id']);
				$convertFieldType = array('theprice', 'product_rating');
				
				foreach($convertFieldType as $convertFieldKey) {
					if(array_key_exists($convertFieldKey,$product)) {
						$product[$convertFieldKey] = FatUtility::float($product[$convertFieldKey]);
					}
				}
				
				$brand = array();
				$brandKeys = array('brand_id', 'brand_name','brand_short_description','brand_active');
				
				foreach($brandKeys as $brandKey) {
					if(array_key_exists($brandKey,$product)) {
						$brand[$brandKey] = $product[$brandKey];
						unset($product[$brandKey]);
					}
				}
				$inventories = static::insertInventory($productId,$langId);
				$categories = static::insertCategory($productId,$langId);
				$options = static::insertOptions($productId,$langId);
				$data = array( 'general' => $product, 'brand' => $brand, 'categories'=> $categories,'options' => $options, 'inventories' => $inventories );
				
				// Checking Document Id Exists Or Not
				if( !$response = $fullTextSearch->isDocumentExists($productId) )
				{
					$results = $fullTextSearch->createDocument($productId, $data);
					if(!$results) 
					{
						continue;
					}
					(new self())->updateProductStatus($productId, $langId);
					continue;
				}
				$upDateGeneralData = $fullTextSearch->updateDocument($productId, $data);
				if( !$upDateGeneralData )
				{
					continue;
				}
				(new self())->updateProductStatus($productId, $langId);
			}
		}
		return true;
	}
	
	public static function insertInventory($productId, $langId)
	{
		$langId = FatUtility::int($langId);
		$productId = FatUtility::int($productId);
		
		if(1 > $productId)
		{
			return false;
		}
		
		$defaultPlugin = (new self())->getDefaultPlugin();
		if(!$defaultPlugin)
		{
			return false;
		}
		
		$fullTextSearch = new $defaultPlugin( $langId );
		
		$srch  = SellerProduct::getSearchObject( $langId );
		$srch->addCondition( SellerProduct::DB_TBL_PREFIX . 'product_id', '=', $productId);
		$srch->addMultipleFields(array('selprod_id','selprod_title','selprod_code','selprod_stock', 'selprod_condition', 'selprod_active', 'selprod_cod_enabled', 'selprod_available_from','selprod_price', 'selprod_sold_count', 'selprod_sku'));
		$rs    = $srch->getResultSet();
		$sellerProducts = FatApp::getDb()->fetchAll($rs);
		
		foreach( $sellerProducts as $key => $sellerProduct) {	
			$sellerProducts[$key]['min_price'] = (new self())->getSellerProductMinimumPrice( $sellerProduct['selprod_id'] );
		}
		return $sellerProducts;
	}
	
	public static function insertCategory($productId, $langId)
	{
		$categories = array();
		
		$productId = FatUtility::int($productId);
		$langId = FatUtility::int($langId);
		
		$srch = new SearchBase(Product::DB_TBL_PRODUCT_TO_CATEGORY, 'ptc');
		$srch->addCondition(Product::DB_TBL_PRODUCT_TO_CATEGORY_PREFIX . 'product_id', '=', $productId);
		$srch->joinTable(ProductCategory::DB_TBL, 'INNER JOIN', ProductCategory::DB_TBL_PREFIX. 'id = ptc.' . Product::DB_TBL_PRODUCT_TO_CATEGORY_PREFIX . 'prodcat_id', 'cat');
		$srch->joinTable(ProductCategory::DB_TBL_LANG, 'LEFT OUTER JOIN', ProductCategory::DB_TBL_LANG_PREFIX. 'prodcat_id = '. ProductCategory::tblFld('id'). ' and ' .ProductCategory::DB_TBL_LANG_PREFIX. 'lang_id = '.$langId);
		$rs = $srch->getResultSet();
		$categories = FatApp::getDb()->fetchAll($rs);
		
		return $categories;
	}
	
	public static function insertOptions($productId, $langId)
	{
		$allOptions = array();
		
		$productId = FatUtility::int($productId);
		$langId = FatUtility::int($langId);
		
		$srch = new SearchBase(Product::DB_PRODUCT_TO_OPTION);
		$srch->addCondition(Product::DB_PRODUCT_TO_OPTION_PREFIX . 'product_id', '=', $productId);
		$srch->joinTable(OptionValue::DB_TBL, 'LEFT JOIN', Product::DB_PRODUCT_TO_OPTION_PREFIX . 'option_id = opval.'. OptionValue::DB_TBL_PREFIX .'option_id', 'opval');
		$srch->joinTable(OptionValue::DB_TBL_LANG, 'LEFT OUTER JOIN', OptionValue::DB_TBL_LANG_PREFIX . 'optionvalue_id = '. OptionValue::tblFld('id'). ' and ' . OptionValue::DB_TBL_LANG_PREFIX. 'lang_id = '.$langId );
		
		$srch->doNotLimitRecords();
		$srch->doNotCalculateRecords();
		
		$rs = $srch->getResultSet();
		$allOptions = FatApp::getDb()->fetchAll($rs);
		
		return $allOptions;
	}
	
	/* End Elastic Search Data Insert Functions ] */
	
	
	/* Search all the categories */
	private function getSearchCategories($criteria,$fullTextSearch)
	{
		$Categories = $fullTextSearch->search($criteria,false,array('categories.prodcat_code'));
		return $Categories;
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
	
	private function updateProductStatus($productId,$langId)
	{
		if(FatApp::getDb()->deleteRecords(Product::DB_PRODUCT_EXTERNAL_RELATIONS, array('smt' => Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX. 'product_id = ? and ' .Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX. 'lang_id = ? ' , 'vals' => array($productId, $langId)))){
			return true;
		}
		
		return false;
	}
	
	/* Collecting Seller Product Minimum Price */
	private function getSellerProductMinimumPrice($sellerProductId)
	{
		$sellerProductId = FatUtility::int($sellerProductId);
		
		$srch = new SearchBase(Product::DB_PRODUCT_MIN_PRICE);
		$srch->doNotLimitRecords();
		$srch->doNotCalculateRecords();
		$srch->addCondition(Product::DB_PRODUCT_MIN_PRICE_PREFIX. 'selprod_id', '=', $sellerProductId);
		$srch->addMultipleFields(array('pmp_product_id','pmp_selprod_id','pmp_min_price as theprice','pmp_splprice_id','if(pmp_splprice_id,1,0) as special_price_found'));
		$rs = $srch->getResultSet();
		$minimumPrice = FatApp::getDb()->fetch($rs);
		if(!$minimumPrice){
			return array();
		}
		return $minimumPrice;
	}
	
	/*
	* Insert Inventory 
	* Param @productId 
	* Param @sellerProductId required when you want to update a seller product data
	*/ 
	
	/*public static function insertInventory($productId, $sellerProductId = 0)
	{
		$defaultPlugin = (new self())->getDefaultPlugin();
		if(!$defaultPlugin)
		{
			return false;
		}
		$languages = Language::getAllNames();
		if(0 > count($languages))
		{
			return false;
		}
		
		foreach($languages as $langId => $language)
		{
			$langId = FatUtility::int($langId);
			$fullTextSearch = new $defaultPlugin( $langId );
			$srch  = SellerProduct::getSearchObject( $langId );
			
			if(1 > $productId)
			{
				return false;
			}
			
			$srch->addCondition( SellerProduct::DB_TBL_PREFIX . 'product_id', '=', $productId);
			if($sellerProductId != 0)
			{
				$srch->addCondition( SellerProduct::DB_TBL_PREFIX . 'id', '=', $sellerProductId);
			}
			$srch->joinTable( SellerProduct::DB_TBL_EXTERNAL_RELATIONS, 'INNER JOIN' , SellerProduct::DB_TBL_PREFIX . 'id = '. SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX . 'selprod_id');
			$srch->addCondition( SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX . 'indexed_for_search', '=', applicationConstants::NO);
			$srch->addCondition( SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX . 'lang_id', '=', $langId);
			$srch->addMultipleFields(array('selprod_id','selprod_title','selprod_code','selprod_stock', 'selprod_condition', 'selprod_active', 'selprod_cod_enabled', 'selprod_available_from','selprod_price', 'selprod_sold_count', 'selprod_sku'));

			$rs    = $srch->getResultSet();
			$sellerProducts = FatApp::getDb()->fetchAll($rs);

			foreach( $sellerProducts as $key => $sellerProduct) {
				$sellerProducts[$key]['shop']     = Shop::getAttributesByUserId($sellerProduct['selprod_user_id'], null, true, $language['language_id']);
				$sellerProducts[$key]['userData'] = User::getAttributesById( $sellerProduct['selprod_user_id'] );
				$sellerProducts[$key]['options']  = (new self())->getSellerProductOptions( $sellerProduct['selprod_id'], $language['language_id']);
				$sellerProducts[$key]['reviews']  = (new self())->getSellerProductReviews( $sellerProduct['selprod_id'], $language['language_id']);
				$sellerProducts[$key]['min_price'] = (new self())->getSellerProductMinimumPrice( $sellerProduct['selprod_id'] );
			}

			if(1 > count($sellerProducts))
			{
				return false;
			}
			
			if(!$response = $fullTextSearch->isDocumentExists($productId))
			{
				return false;
			}
			if(1 > $sellerProductId ) 
			{
				$data = array('inventories' => $sellerProducts);
				$results = $fullTextSearch->updateDocument($productId,$data);
				if(!$results) 
				{
					continue;
				}
				$updateStatus = (new self())->updateSellerProductStatus($productId, $langId);
				continue;
			}

			$dataIndexArray = array('selprod_id' => $sellerProductId);

			$data = array('inventories' => $sellerProducts[0]);

			$results = $fullTextSearch->updateDocumentData($productId, 'inventories', $dataIndexArray, $data);

			if(!$results) 
			{
				continue;
			}
			$updateStatus = (new self())->updateSellerProductStatus($productId, $langId);
			continue;
		}
		return true;
	}*/
	
	/*
	* Insert Brand 
	* Param @productId if you want to insert or update only one brand
	*/ 
	
	/*public static function insertbrand($productId = 0)
	{
		$productId = FatUtility::int($productId);
		$defaultPlugin = (new self())->getDefaultPlugin();
		if(!$defaultPlugin)
		{
			return false;
		}
		
		$languages = Language::getAllNames();
		
		if(0 > count($languages)) 
		{
			return false;
		}
		
		foreach($languages as $langId => $language)
		{
			$langId = FatUtility::int($langId);
			$fullTextSearch = new $defaultPlugin($langId);
			$srch  = Product::getSearchObject($langId);
			//$srch->joinTable(Product::DB_PRODUCT_EXTERNAL_RELATIONS, 'INNER JOIN', Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX .'product_id ='. Product::DB_TBL_PREFIX. 'id');
			//$srch->addCondition(Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX . 'lang_id', '=' , $langId );
			$srch->addMultipleFields(array('brand_id','brand_identifier','brand_name','brand_short_description', 'brand_active', 'brand_seller_id'));
			
			if(1 > $productId )
			{
				$srch->setPageSize(FatUtility::int(self::SIZE));
			}
			else
			{
				$srch->addCondition(Product::DB_TBL_PREFIX. 'id', '=', $productId);
				$srch->addCondition(Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX. 'indexed_for_search', '=', applicationConstants::NO);
			}
			
			$rs = $srch->getResultSet();
			$products = FatApp::getDb()->fetchAll($srch->getResultSet());
			
			echo "<Pre>";
			print_r($products);
			die;
			
			if(1 > count($products)) 
			{
				return false;
			}

			foreach( $products as $key => $product) 
			{
				$productId = FatUtility::int($product['product_id']);
				
				if(!$response = $fullTextSearch->isDocumentExists($productId)) 
				{
					continue;
				}
				
				$brand = array();
				$srch  = Brand::getSearchObject($langId);
				$srch->addCondition('brand_id', '=', FatUtility::int($product['product_brand_id']));
				$rs  = $srch->getResultSet();
				$brand = FatApp::getDb()->fetch($rs);
				$data = array('brand' => $brand);
				
				if( !$result = $fullTextSearch->updateDocument($productId, $data) ) 
				{
					continue;
				}
				(new self())->updateProductStatus($productId, $langId);
			}
		}
		return true;
	} */
	
	/*
	* Insert Category 
	* Param @productId if you want to insert or update only one category
	*/ 
	

	
	
	/*public static function insertCategories($productId = 0)
	{
		$productId = FatUtility::int($productId);
		$defaultPlugin = (new self())->getDefaultPlugin();
		if(!$defaultPlugin){
			return false;
		}
		
		$languages = Language::getAllNames();
		
		if(0 > count($languages)) 
		{
			return false;
		}
		
		foreach($languages as $langId => $language)
		{
			$langId = FatUtility::int($langId);
			$fullTextSearch = new $defaultPlugin($langId);
			
			$srch  = Product::getSearchObject($langId);
			$srch->joinTable(Product::DB_PRODUCT_EXTERNAL_RELATIONS, 'INNER JOIN', Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX. 'product_id =' .Product::DB_TBL_PREFIX. 'id');
			$srch->addCondition(Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX. 'indexed_for_search', '=', applicationConstants::NO);
			$srch->addCondition(Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX. 'lang_id', '=', $langId);
			$srch->addMultipleFields(array('prodcat_id','prodcat_code','prodcat_parent','prodcat_identifier', 'prodcat_active'));
			if(1 > $productId )
			{
				$srch->setPageSize(FatUtility::int(self::SIZE));
			}
			else
			{
				$srch->addCondition(Product::DB_TBL_PREFIX.'id', '=', $productId);
			}

			$rs    = $srch->getResultSet();
			$products = FatApp::getDb()->fetchAll($srch->getResultSet());
			
			if(1 > count($products))
			{
				return false;
			}
			foreach( $products as $key => $product) 
			{
				$productId = FatUtility::int($product['product_id']);
				if(!$response = $fullTextSearch->isDocumentExists($productId))
				{
					continue;
				}
				$categories = array();
				$srch = new SearchBase(Product::DB_TBL_PRODUCT_TO_CATEGORY, 'ptc');
        		$srch->addCondition(Product::DB_TBL_PRODUCT_TO_CATEGORY_PREFIX . 'product_id', '=', $productId);
        		$srch->joinTable(ProductCategory::DB_TBL, 'INNER JOIN', ProductCategory::DB_TBL_PREFIX.'id = ptc.'. Product::DB_TBL_PRODUCT_TO_CATEGORY_PREFIX. 'prodcat_id', 'cat');
				$srch->joinTable(ProductCategory::DB_TBL_LANG, 'LEFT OUTER JOIN', ProductCategory::DB_TBL_LANG_PREFIX. 'prodcat_id = '. ProductCategory::tblFld('id'). ' and ' .ProductCategory::DB_TBL_LANG_PREFIX. 'lang_id = '.$langId);
				$rs = $srch->getResultSet();
        		$categories = FatApp::getDb()->fetchAll($rs);
				$data = array('categories' => $categories);

				if(!$result = $fullTextSearch->updateDocument($productId, $data)) 
				{
					continue;
				}
				(new self())->updateProductStatus($productId, $langId);
			}
		}
		return true;
	}*/
	
	/*
	* Insert Options 
	* Param @productId if you want to insert or update only one Options
	*/
	
	/*public static function insertOptions($productId = 0)
	{
		$productId = FatUtility::int($productId);
		$defaultPlugin = (new self())->getDefaultPlugin();
		
		if(!$defaultPlugin) 
		{
			return false;
		}
		
		$languages = Language::getAllNames();
		
		if(0 > count($languages)) 
		{
			return false;
		}
		
		foreach($languages as $langId => $language)
		{
			$langId = FatUtility::int($langId);
			
			$fullTextSearch = new $defaultPlugin($langId);
			$srch  = Product::getSearchObject($langId);
			$srch->joinTable(Product::DB_PRODUCT_EXTERNAL_RELATIONS,'INNER JOIN', Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX. 'product_id ='. Product::DB_TBL_PREFIX. 'id');
			$srch->addCondition(Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX. 'indexed_for_search', '=', applicationConstants::NO);
			$srch->addCondition(Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX. 'lang_id', '=', $langId);
			
			if(1 > $productId )
			{
				$srch->setPageSize(self::PAGESIZE);
			}
			else
			{
				$srch->addCondition(Product::DB_TBL_PREFIX.'id', '=', $productId);
			}
			$rs    = $srch->getResultSet();
			$products = FatApp::getDb()->fetchAll($srch->getResultSet());
			
			if(1 > count($products))
			{
				return false;
			}
			foreach( $products as $key => $product) 
			{
				$productId = FatUtility::int($product['product_id']);
				
				if(!$response = $fullTextSearch->isDocumentExists($productId))
				{
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
	}*/

	private function updateSellerProductStatus($productId, $langId, $sellerProductId = 0)
	{
		if(1 > $sellerProductId ) 
		{
			FatApp::getDb()->updateFromArray(SellerProduct::DB_TBL_EXTERNAL_RELATIONS, array(SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX. 'indexed_for_search' => applicationConstants::YES), array('smt' => SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX. 'product_id = ? and ' .SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX. 'lang_id = ?','vals' => array($productId, $langId)));
		}

		FatApp::getDb()->updateFromArray(SellerProduct::DB_TBL_EXTERNAL_RELATIONS, array(SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX.'indexed_for_search' => applicationConstants::YES), array('smt' => SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX.'selprod_id = ?', 'vals' => array($sellerProductId)));
		return true;
	}

	/* Get Seller Product Options */
	/*private function getSellerProductOptions($sellerProductId, $langId) {
		$allOptions = array();
		
		$sellerProductId = FatUtility::int($sellerProductId);
		$langId = FatUtility::int($langId);
		
		$srch = new SearchBase(SellerProduct::DB_TBL_SELLER_PROD_OPTIONS);
		$srch->addCondition(SellerProduct::DB_TBL_SELLER_PROD_OPTIONS_PREFIX. 'selprod_id', '=', $sellerProductId);
		$srch->joinTable(Option::DB_TBL, 'LEFT JOIN', SellerProduct::DB_TBL_SELLER_PROD_OPTIONS_PREFIX. 'option_id =' .Option::DB_TBL_PREFIX.'id');
		$srch->joinTable(Option::DB_TBL_LANG, 'LEFT JOIN', Option::DB_TBL_LANG_PREFIX. 'option_id =' .Option::DB_TBL_PREFIX. 'id'); // lang
		$srch->joinTable(OptionValue::DB_TBL, 'LEFT JOIN', SellerProduct::DB_TBL_SELLER_PROD_OPTIONS_PREFIX. 'optionvalue_id =' .OptionValue::DB_TBL_PREFIX. 'id');
		$srch->addCondition(Option::DB_TBL_LANG_PREFIX. 'lang_id', '=', $langId);
		$rs = $srch->getResultSet();

        $allOptions = FatApp::getDb()->fetchAll($rs);
		return $allOptions;
	}*/

	/* Collecting Seller Product reviews  */
	/*private function getSellerProductReviews($sellerProductId, $langId)
    {
		$sellerProductReviews = array();
		$sellerProductId = FatUtility::int($sellerProductId);
		$srch = new SearchBase(SelProdReview::DB_TBL);
		$srch->addCondition(SelProdReview::DB_TBL_PREFIX. 'selprod_id', '=',$sellerProductId);
		$srch->addCondition(SelProdReview::DB_TBL_PREFIX. 'lang_id',  '=', $langId);
		$rs = $srch->getResultSet();
        $sellerProductReviews = FatApp::getDb()->fetchAll($rs);

		if(1 > count($sellerProductReviews) ){
			return $sellerProductReviews;
		}
		foreach($sellerProductReviews as  $key => $selReview){
			$user = array();
			$user = User::getAttributesById($selReview[SelProdReview::DB_TBL_PREFIX. 'postedby_user_id']);
			$sellerProductReviews[$key]['user'] = $user;
		}
		return $sellerProductReviews;
	}*/

	

	/* getting Product Minimum price */
	/*private function getMinimumPriceOfProduct($productId) {
		$minPriceSeller = array();
		
		$srch  = SellerProduct::getSearchObject();
		$srch->addCondition('selprod_product_id', '=', $productId);
        $srch->doNotLimitRecords();
        $srch->doNotCalculateRecords();
		$srch->addMultipleFields(array('selprod_id'));
		$rs    = $srch->getResultSet();
		
		$sellerProducts = FatApp::getDb()->fetchAll($rs);
		if(1 > count($sellerProducts)){
			return $minPriceSeller;
		}
		foreach($sellerProducts as $key => $selerProduct){
			 $sellerProductIds[] = $selerProduct['selprod_id'];
		}
		if(1> count($sellerProductIds)){
			return $minPriceSeller;
		}
		$srch = new SearchBase(Product::DB_PRODUCT_MIN_PRICE);
		$srch->addCondition( Product::DB_PRODUCT_MIN_PRICE_PREFIX.'selprod_id', "IN", $sellerProductIds );
		$srch->addMultipleFields( array('pmp_selprod_id as selprod_id','MIN(pmp_min_price) as price') );
		$srch->doNotLimitRecords();
        $srch->doNotCalculateRecords();
		$rs = $srch->getResultSet();
		$minPriceSeller = FatApp::getDb()->fetch($rs);
		
		return $minPriceSeller;
	}*/

	/* Product Rating */

	/*private function productRating($productId) {

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
		if(!isset($ProductRating['prod_rating']))
		{
			return 0;
		}
		return $ProductRating['prod_rating'];
	}*/

	/*private function getProductDiscount($productId) {
		// $discount = array();
		$srch = new SearchBase(SellerProduct::DB_TBL);
		$srch->joinTable(Product::DB_PRODUCT_MIN_PRICE, "LEFT JOIN", SellerProduct::DB_TBL_PREFIX. "id = " .Product::DB_PRODUCT_MIN_PRICE_PREFIX. "selprod_id");
		$srch->addCondition(SellerProduct::DB_TBL_PREFIX. "product_id", '=', $productId);
		$srch->doNotCalculateRecords();
		$srch->doNotLimitRecords();
		$srch->addMultipleFields(array("selprod_id, ROUND(((selprod_price - pmp_min_price)*100)/selprod_price) as discountedValue"));
		$rs    = $srch->getResultSet();
		$discount = FatApp::getDb()->fetchArray($rs);
		echo "<Pre>";
		print_r($discount);
		if(empty($discount))
		{
			return array();
		}
		return $discount; 
	}*/

}
