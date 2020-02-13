<?php
class FullTextSearch extends FatModel
{
    private $langId;
    private $pageSize;
    private $fullTextSearch;
    private $search;
    private $fields;
	private $groupByFields;
	private $sortField;

	public const SIZE = 50;

    public function __construct($langId)
    {
        $this->langId = FatUtility::int($langId);
        if (1 > $langId) {
            trigger_error(Labels::getLabel('LBL_INVALID_REQUEST', CommonHelper::getLangId()), E_USER_ERROR);
        }

        $defaultPlugin = $this->getDefaultPlugin();
        if (false == $defaultPlugin) {
            trigger_error(Labels::getLabel('LBL_PLUGIN_NOT_ACTIVATED', $this->langId), E_USER_ERROR);
        }

        require_once CONF_INSTALLATION_PATH . 'library/plugins/full-text-search/' . $defaultPlugin . '.php';
        $this->fullTextSearch = new $defaultPlugin($this->langId);
        $this->pageSize = FatApp::getConfig('conf_page_size', FatUtility::VAR_INT, 10);
        $this->search 	 = [];
        $this->fields    = [];
		$this->sortField = [];
    }

    public function setFields($arr = [])
    {
        $this->fields = $arr;
    }

	public function setSortFields($arr = [])
	{
		$this->sortField = $arr;
	}

	public function setGroupByField($field)
	{
		$this->groupByFields = $field;
	}

    public function setPageNumber($page)
    {
        $this->page = FatUtility::int($page);
    }

    public function setPageSize($pageSize)
    {
        $this->pageSize = FatUtility::int($pageSize);
    }
	/* [ Search Function Start */

    public function addKeywordCondition($keyword)
    {
        if (empty($keyword)) {
            return false;
        }
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

		if(array_key_exists('must',$this->search))
		{
			array_push($this->search["must"], $textSearch);
		}
		else
		{
			$this->search["must"][0] = $textSearch;
		}
    }

    public function addBrandConditions($brands = [])
    {
        if (empty($brands)) {
            return ;
        }

        $brandsFilters['bool']['should'] = array();
        foreach ($brands as $key => $brand) {
            $brandsFilters['bool']['should'][$key] = ['match' => ['brand.brand_id' => $brand ]];
        }
		if(array_key_exists('must',$this->search))
		{
			array_push($this->search["must"], $brandsFilters);
		}
		else
		{
			$this->search["must"][0] = $brandsFilters;
		}
        //array('brand.brand_id','brand.brand_name'), 'brand.brand_name', array('brand.brand_name.keyword' => 'asc')
    }

	public function addPriceFilters($minPrice,$maxPrice)
	{
		if(empty($minPrice) && empty($maxPrice))
		{
			return;
		}
		$priceFilters['range'] = [
						'general.theprice'=> [ 'gte' => $minPrice, 'lte' => $maxPrice ]
					];

		if(array_key_exists('must',$this->search))
		{
			array_push($this->search["must"], $priceFilters);
		}
		else
		{
			$this->search["must"][0] = $priceFilters;
		}
	}

	public function addCategoryFilter($categoryId)
	{
		$categoryId = FatUtility::int($categoryId);

		if ($categoryId)
		{
			$catCode = ProductCategory::getAttributesById($categoryId, 'prodcat_code');
			$categoryFilter['wildcard'] = ['categories.prodcat_code'=> [ "value" => $catCode.'*',"boost"=> "2.0", "rewrite"=>"constant_score" ] ];
			if(array_key_exists('must',$this->search))
			{
				array_push($this->search["must"], $categoryFilter);
			}
			else
			{
				$this->search["must"][0] = $categoryFilter;
			}
		}
	}

    public function fetch($aggregationPrice = false)
    {
        if(empty($this->search))
		{
			$this->search = ['match_all' => []];
		}

        return $this->fullTextSearch->search($this->search, $this->page, $this->pageSize, $aggregationPrice, $this->fields, $this->groupByFields,$this->sortField);
    }

	/*  Search Function End ] */


	/* [ UpdatedRecordLog Insert and Update Functions  Start */

    public static function updateLastProcessedRecord($lastProcessedRecordTime)
    {
        $labelsUpdatedAt = array('conf_name' => 'CONF_FULL_TEXT_SEARCH_LAST_PROCESSED','conf_val' => $lastProcessedRecordTime);
		FatApp::getDb()->insertFromArray('tbl_configurations', $labelsUpdatedAt, false, array(), $labelsUpdatedAt);
    }

    public static function updateQueue($data)
    {
        if (empty($data)) {
            return false;
        }

        return FatApp::getDb()->insertFromArray(UpdatedRecordLog::DB_TBL, $data, false, array(), $data);

        /* $updatedRecordLog = new UpdatedRecordLog();
        $updatedRecordLog->assignValues($data);
        if (!$updatedRecordLog->save()) {
            return false;
        }
        return true; */
    }

    public static function setup()
    {
        $srch = UpdatedRecordLog::getSearchObject();
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addCondition(UpdatedRecordLog::DB_TBL_PREFIX . 'added_on', '>', FatApp::getConfig('CONF_FULL_TEXT_SEARCH_LAST_PROCESSED', FatUtility::VAR_STRING, ''));
        $srch->addOrder(UpdatedRecordLog::DB_TBL_PREFIX . 'added_on', 'asc');
        $rs = $srch->getResultSet();
        $record = FatApp::getDb()->fetchAll($rs);
		
        if (empty($record)) {
            return false;
        }

        foreach ($record as $row) {
            $recordId = $row[UpdatedRecordLog::DB_TBL_PREFIX . 'record_id'];
            switch ($row[UpdatedRecordLog::DB_TBL_PREFIX . 'record_type']) {
                case UpdatedRecordLog::TYPE_SHOP:
                    if (!static::updateShopProductsQueue($recordId)) {
                        return false;
                    }
                    static::updateLastProcessedRecord($row['urlog_added_on']);
                    break;
                case UpdatedRecordLog::TYPE_USER:
                    if (!static::updateUserProductsQueue($recordId)) {
                        return false;
                    }
                    static::updateLastProcessedRecord($row['urlog_added_on']);
                    break;
                case UpdatedRecordLog::TYPE_CATEGORY:
                    if (!static::updateCategoryProductsQueue($recordId)) {
                        return false;
                    }
                    static::updateLastProcessedRecord($row['urlog_added_on']);
                    break;
                case UpdatedRecordLog::TYPE_BRAND:
                    if (!static::updateBrandProductsQueue($recordId)) {
                        return false;
                    }
                    static::updateLastProcessedRecord($row['urlog_added_on']);
                    break;
                case UpdatedRecordLog::TYPE_COUNTRY:
                    if (!static::updateCountryProductsQueue($recordId)) {
                        return false;
                    }
                    static::updateLastProcessedRecord($row['urlog_added_on']);
                    break;
                case UpdatedRecordLog::TYPE_STATE:
                    if (!static::updateStateProductsQueue($recordId)) {
                        return false;
                    }
                    static::updateLastProcessedRecord($row['urlog_added_on']);
                    break;
                case UpdatedRecordLog::TYPE_PRODUCT:
                    if (!static::updateProducts($recordId)) {
                        return false;
                    }
                    static::updateLastProcessedRecord($row['urlog_added_on']);
                    break;
                case UpdatedRecordLog::TYPE_INVENTORY:
                    if (!static::updateProductInventory($recordId)) {
                        return false;
                    }
                    static::updateLastProcessedRecord($row['urlog_added_on']);
                    break;
            }
        }
    }

    public static function updateShopProductsQueue($shopId)
    {
        $shopId = FatUtility::int($shopId);
        if (1 > $shopId) {
            return false;
        }

        $srch = new ProductSearch(0, null, null, false, false, false);
        $srch->joinSellerProducts(0, '', array(), false);
        $srch->joinSellers();
        $srch->joinShops(0, false, false, $shopId);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addCondition('shop_id', '=', $shopId);
        $srch->addMultipleFields(array('distinct(product_id)'));
        $rs = $srch->getResultSet();
        while ($record = FatApp::getDb()->fetch($rs)) {
            $data = [
                'urlog_record_id' => $record['product_id'],
                'urlog_subrecord_id' => 0,
                'urlog_record_type' => UpdatedRecordLog::TYPE_PRODUCT,
                'urlog_added_on' => date('Y-m-d H:i:s')
            ];
            if (!static::updateQueue($data)) {
                return false;
            }
        }
        return true;
    }
	
	// param user_id 
    public static function updateUserProductsQueue($sellerId)
    {
        $sellerId = FatUtility::int($sellerId);
        if (1 > $sellerId) {
            return false;
        }

        $srch = new ProductSearch(0, null, null, false, false, false);
        $srch->joinSellerProducts($sellerId, '', array(), false);
        $srch->joinSellers();
        $srch->joinShops(0, false, false, 0);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        //$srch->addCondition('selprod_user_id', '=', $sellerId);
        $srch->addMultipleFields(array('distinct(product_id)'));
        $rs = $srch->getResultSet();
        while ($record = FatApp::getDb()->fetch($rs)) {
            $data = [
                'urlog_record_id' => $record['product_id'],
                'urlog_subrecord_id' => 0,
                'urlog_record_type' => UpdatedRecordLog::TYPE_PRODUCT,
				//'urlog_record_type' => UpdatedRecordLog::TYPE_INVENTORY,
                'urlog_added_on' => date('Y-m-d H:i:s')
            ];
            if (!static::updateQueue($data)) {
                return false;
            }
        }
        return true;
    }

    public static function updateCategoryProductsQueue($categoryId)
    {
        $categoryId = FatUtility::int($categoryId);
        if (1 > $categoryId) {
            return false;
        }

        $srch = new ProductSearch(0, null, null, false, false, false);
        $srch->joinProductToCategory(0, false, false, false);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addCondition('prodcat_id', '=', $categoryId);
        $srch->addMultipleFields(array('distinct(product_id)'));
        $rs = $srch->getResultSet();
        while ($record = FatApp::getDb()->fetch($rs)) {
            $data = [
                'urlog_record_id' => $record['product_id'],
                'urlog_subrecord_id' => 0,
                'urlog_record_type' => UpdatedRecordLog::TYPE_PRODUCT,
                'urlog_added_on' => date('Y-m-d H:i:s')
            ];
            if (!static::updateQueue($data)) {
                return false;
            }
        }
        return true;
    }

    public static function updateBrandProductsQueue($brandId)
    {
        $brandId = FatUtility::int($brandId);
        if (1 > $brandId) {
            return false;
        }

        $srch = new ProductSearch(0, null, null, false, false, false);
        $srch->joinBrands(0, false, false, false);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addCondition('brand_id', '=', $brandId);
        $srch->addMultipleFields(array('distinct(product_id)'));
        $rs = $srch->getResultSet();
        while ($record = FatApp::getDb()->fetch($rs)) {
            $data = [
                'urlog_record_id' => $record['product_id'],
                'urlog_subrecord_id' => 0,
                'urlog_record_type' => UpdatedRecordLog::TYPE_PRODUCT,
                'urlog_added_on' => date('Y-m-d H:i:s')
            ];
            if (!static::updateQueue($data)) {
                return false;
            }
        }
        return true;
    }

    public static function updateCountryProductsQueue($countryId)
    {
        $countryId = FatUtility::int($countryId);
        if (1 > $countryId) {
            return false;
        }

        $srch = new ProductSearch(0, null, null, false, false, false);
        $srch->joinSellerProducts(0, '', array(), false);
        $srch->joinSellers();
        $srch->joinShops(0, false, false, 0);
        $srch->joinShopCountry(0, false);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addCondition('country_id', '=', $countryId);
        $srch->addMultipleFields(array('distinct(product_id)'));
        $rs = $srch->getResultSet();
        while ($record = FatApp::getDb()->fetch($rs)) {
            $data = [
                'urlog_record_id' => $record['product_id'],
                'urlog_subrecord_id' => 0,
                'urlog_record_type' => UpdatedRecordLog::TYPE_PRODUCT,
                'urlog_added_on' => date('Y-m-d H:i:s')
            ];
            if (!static::updateQueue($data)) {
                return false;
            }
        }
        return true;
    }

    public static function updateStateProductsQueue($stateId)
    {
        $stateId = FatUtility::int($stateId);
        if (1 > $stateId) {
            return false;
        }

        $srch = new ProductSearch(0, null, null, false, false, false);
        $srch->joinSellerProducts(0, '', array(), false);
        $srch->joinSellers();
        $srch->joinShops(0, false, false, 0);
        $srch->joinShopState(0, false);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addCondition('state_id', '=', $stateId);
        $srch->addMultipleFields(array('distinct(product_id)'));
        $rs = $srch->getResultSet();
        while ($record = FatApp::getDb()->fetch($rs)) {
            $data = [
                'urlog_record_id' => $record['product_id'],
                'urlog_subrecord_id' => 0,
                'urlog_record_type' => UpdatedRecordLog::TYPE_PRODUCT,
                'urlog_added_on' => date('Y-m-d H:i:s')
            ];
            if (!static::updateQueue($data)) {
                return false;
            }
        }
        return true;
    }

	/* UpdatedRecordLog Insert and Update Functions  End ] */


	/* [ Start ElasticSearch Data Insert Functions */
    public static function updateProducts($productId)
    {
        $productId = FatUtility::int($productId);
        if (1 > $productId) {
            return false;
        }

        return static::insertProduct($productId);
    }

    public static function updateProductInventory($selProdId)
    {
        $selProdId = FatUtility::int($selProdId);
        if (1 > $selProdId) {
            return false;
        }

        $languages = Language::getAllNames();
        if (0 > count($languages)) {
            return false;
        }

        $productId = SellerProduct::getAttributesById($selProdId, 'selprod_product_id');
        return static::insertSellerProduct($productId, $selProdId);
    }

	/*
	* Insert Product
	*/

    public static function insertProduct($productId = 0)
    {
        $productId = FatUtility::int($productId);
        $languages = Language::getAllNames();
        if (0 > count($languages)) {
            return false;
        }

        foreach ($languages as $langId => $language) 
		{
			$langId = FatUtility::int($langId);

			$defaultPlugin = (new self($langId))->getDefaultPlugin();
			if (!$defaultPlugin) {
				return false;
			}

            $fullTextSearch = new $defaultPlugin($langId);

            $srch = new ProductSearch($langId);
            $srch->addMultipleFields(array('product_id','product_name', 'product_type', 'product_model', 'product_seller_id', 'product_updated_on', 'product_active', 'product_approved', 'product_upc', 'product_isbn', 'product_ship_country', 'product_ship_free', 'product_cod_enabled', 'product_short_description', 'product_description', 'product_tags_string', 'theprice', 'selprod_id','prod_rating as product_rating', 'brand_id','brand_name','brand_short_description','brand_active' ));
            $srch->joinForPrice();
            $srch->joinProductRating();
            $srch->joinBrands();

            if (1 > $productId)
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
			
            if (1 > count($products)) {
				if (!$response = $fullTextSearch->isDocumentExists($productId)) {
					// log write code
					continue;
				}
				$fullTextSearch->deleteDocument($productId);
				continue;
            }

            foreach ($products as $key => $product) 
			{
                $productId = FatUtility::int($product['product_id']);
                $convertFieldType = array('theprice', 'product_rating');

                foreach ($convertFieldType as $convertFieldKey) {
                    if (array_key_exists($convertFieldKey, $product)) {
                        $product[$convertFieldKey] = FatUtility::float($product[$convertFieldKey]);
                    }
                }

                $brand = array();
                $brandKeys = array('brand_id', 'brand_name','brand_short_description','brand_active');

                foreach ($brandKeys as $brandKey) {
                    if (array_key_exists($brandKey, $product)) {
                        $brand[$brandKey] = $product[$brandKey];
                        unset($product[$brandKey]);
                    }
                }
                //$inventories = static::insertInventory($productId, $langId);
                $categories = static::insertCategory($productId, $langId);
                $options = static::insertOptions($productId, $langId);
                $data = array( 'general' => $product, 'brand' => $brand, 'categories'=> $categories,'options' => $options/* , 'inventories' => $inventories */ );

                // Checking Document Id Exists Or Not
                if (!$response = $fullTextSearch->isDocumentExists($productId)) {
                    $results = $fullTextSearch->createDocument($productId, $data);
                    if (!$results) {
                        continue;
                    }
                    return static::insertSellerProduct($productId);
                    continue;
                }
                $upDateGeneralData = $fullTextSearch->updateDocument($productId, $data);
                if (!$upDateGeneralData) {
                    continue;
                }
                return static::insertSellerProduct($productId);
            }
        }
        return true;
    }

	/*
    * Insert Inventory
    * Param @productId
    * Param @sellerProductId required when you want to update a seller product data
    */

    public static function insertSellerProduct($productId, $sellerProductId = 0)
    {
        $languages = Language::getAllNames();
		$productId = FatUtility::int($productId);
		$sellerProductId = FatUtility::int($sellerProductId);
		
        if (0 > count($languages)) {
            return false;
        }
		
		if (1 > $productId) {
            return false;
        }

        foreach ($languages as $langId => $language) 
		{
			$langId = FatUtility::int($langId);

			$defaultPlugin = (new self($langId))->getDefaultPlugin();
			if (!$defaultPlugin) {
				return false;
			}

            $fullTextSearch = new $defaultPlugin($langId);
			
			$srch = new ProductSearch($langId);
			$srch->joinTable(SellerProduct::DB_TBL, 'INNER JOIN', Product::DB_TBL_PREFIX . 'id = sp.selprod_product_id', 'sp');
			$srch->joinTable(
						SellerProduct::DB_TBL_LANG, 'LEFT OUTER JOIN', 'sp_l.' .SellerProduct::DB_TBL_LANG_PREFIX. 'selprod_id = sp.'. SellerProduct::tblFld('id'). ' 
						and sp_l.' .SellerProduct::DB_TBL_LANG_PREFIX. 'lang_id = ' .$langId, 'sp_l'
				);
			$srch->joinSellers();
			$srch->joinShops();
			$srch->addCondition(SellerProduct::DB_TBL_PREFIX . 'product_id', '=', $productId);
			$srch->addCondition(User::DB_TBL_CRED_PREFIX . 'active', '=', applicationConstants::ACTIVE);
			$srch->addCondition(SellerProduct::DB_TBL_PREFIX . 'active', '=', applicationConstants::ACTIVE);
			
			if ($sellerProductId != 0) 
			{
                $srch->addCondition(SellerProduct::DB_TBL_PREFIX . 'id', '=', $sellerProductId);
            }
			$srch->addMultipleFields( array('selprod_id','selprod_title','selprod_product_id','selprod_code','selprod_stock', 'selprod_condition', 'selprod_active', 'selprod_cod_enabled', 'selprod_available_from','selprod_price', 'selprod_sold_count', 'selprod_sku','selprod_user_id','shop_id','shop_name', 'shop_contact_person', 'shop_description','shop_active','user_id','user_name','user_phone' ) );
			
			
			$rs    = $srch->getResultSet();
            $sellerProducts = FatApp::getDb()->fetchAll($rs);
			
			if(1 > count($sellerProducts) && $sellerProductId > 0)
			{
				if (!$response = $fullTextSearch->isDocumentExists($productId)) {
					// log write code
					continue;
				}
				$sellerProductKey = array('selprod_id' => $sellerProductId );
				$fullTextSearch->deleteDocumentData($productId, 'inventories', $sellerProductKey);
				continue;
			}
			
            /*$srch  = SellerProduct::getSearchObject($langId);
            $srch->addCondition(SellerProduct::DB_TBL_PREFIX . 'product_id', '=', $productId);
            if ($sellerProductId != 0) {
                $srch->addCondition(SellerProduct::DB_TBL_PREFIX . 'id', '=', $sellerProductId);
            }
            $srch->addMultipleFields( array('selprod_id','selprod_title','selprod_code','selprod_stock', 'selprod_condition', 'selprod_active', 'selprod_cod_enabled', 'selprod_available_from','selprod_price', 'selprod_sold_count', 'selprod_sku','selprod_user_id') );
			
            $rs    = $srch->getResultSet();
            $sellerProducts = FatApp::getDb()->fetchAll($rs);*/
			
			$shopFields = array('shop_id','shop_name','shop_description','shop_contact_person','shop_active');
            $userFields = array('user_id','user_name','user_phone');
			
			foreach ($sellerProducts as $key => $sellerProduct) 
			{
				
				$sellerUserId = FatUtility::int($sellerProduct['selprod_user_id']);
				foreach($shopFields as $shopFieldName)
				{
					if(array_key_exists($shopFieldName,$sellerProduct))
					{
						$sellerProducts[$key]['shop'][$shopFieldName] = $sellerProduct[$shopFieldName];
						unset($sellerProducts[$key][$shopFieldName]);
					}
				}
				
				foreach($userFields as $userFieldName)
				{
					if(array_key_exists($userFieldName,$sellerProduct))
					{
						$sellerProducts[$key]['user'][$userFieldName] = $sellerProduct[$userFieldName];
						unset($sellerProducts[$key][$userFieldName]);
					}
				}
				
                //$sellerProducts[$key]['shop']     = Shop::getAttributesByUserId($sellerUserId, $shopFields , true, $langId);
                //$sellerProducts[$key]['userData'] = User::getAttributesById($sellerUserId, $userFields);
				//$sellerProducts[$key]['options']  = (new self())->getSellerProductOptions($sellerProduct['selprod_id'], $language['language_id']);
                //$sellerProducts[$key]['reviews']  = (new self())->getSellerProductReviews($sellerProduct['selprod_id'], $language['language_id']);
                //$sellerProducts[$key]['min_price'] = (new self())->getSellerProductMinimumPrice($sellerProduct['selprod_id']);
            }
			
            if (1 > count($sellerProducts)) {
                return false;
            }

            if (!$response = $fullTextSearch->isDocumentExists($productId)) {
                return false;
            }
            if (1 > $sellerProductId) {
                $data = array('inventories' => $sellerProducts);
                $results = $fullTextSearch->updateDocument($productId, $data);
                if (!$results) {
                    continue;
                }
                continue;
            }

            $dataIndexArray = array('selprod_id' => $sellerProductId);

            $data = array('inventories' => $sellerProducts[0]);

            $results = $fullTextSearch->updateDocumentData($productId, 'inventories', $dataIndexArray, $data);

            if (!$results) {
                continue;
            }
			static::updateGeneralMinPrice($productId);

            continue;
        }
        return true;
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
        $srch->joinTable(OptionValue::DB_TBL_LANG, 'LEFT OUTER JOIN', OptionValue::DB_TBL_LANG_PREFIX . 'optionvalue_id = '. OptionValue::tblFld('id'). ' and ' . OptionValue::DB_TBL_LANG_PREFIX. 'lang_id = '.$langId);

        $srch->doNotLimitRecords();
        $srch->doNotCalculateRecords();

        $rs = $srch->getResultSet();
        $allOptions = FatApp::getDb()->fetchAll($rs);

        return $allOptions;
    }

	/*
	* @productId -
	*/
	public static function updateGeneralMinPrice($productId)
	{
		$productId = FatUtility::int($productId);
        $languages = Language::getAllNames();

		if (0 > count($languages))
		{
            return false;
        }

        foreach ($languages as $langId => $language)
		{
			$langId = FatUtility::int($langId);
			$defaultPlugin = (new self($langId))->getDefaultPlugin();
			if (!$defaultPlugin) {
				return false;
			}

            $fullTextSearch = new $defaultPlugin($langId);
			$srch = new ProductSearch($langId);
            $srch->addMultipleFields(array('theprice', 'selprod_id'));
            $srch->joinForPrice();
			$srch->addCondition(Product::DB_TBL_PREFIX.'id', '=', $productId);
            $srch->doNotLimitRecords(true);
            $srch->doNotCalculateRecords(true);
            $rs    = $srch->getResultSet();
            $data = FatApp::getDb()->fetch($srch->getResultSet());

			if(0 > $data)
			{
				return false;
			}
			$convertFieldType = array('theprice');
			foreach ($convertFieldType as $convertFieldKey)
			{
				if (array_key_exists($convertFieldKey, $data))
				{
					$data[$convertFieldKey] = FatUtility::float($data[$convertFieldKey]);
				}
			}

			$updatePrice = $fullTextSearch->updateDocument($productId,$data);
			if(!$updatePrice)
			{
				continue;
			}
		}
	}

    /* End Elastic Search Data Insert Functions ] */


    /* Search all the categories */
    private function getSearchCategories($criteria, $fullTextSearch)
    {
        $Categories = $fullTextSearch->search($criteria, false, array('categories.prodcat_code'));
        return $Categories;
    }

    private function getDefaultPlugin()
    {
        $plugin = new Plugin(Plugin::TYPE_FULL_TEXT_SEARCH);
        $defaultPlugin = $plugin->getDefaultPluginData(Plugin::TYPE_FULL_TEXT_SEARCH, "plugin_code");
        if (0 > $defaultPlugin) {
            return false;
        }
        return $defaultPlugin;
    }

    /*private function updateProductStatus($productId, $langId)
    {
        if (FatApp::getDb()->deleteRecords(Product::DB_PRODUCT_EXTERNAL_RELATIONS, array('smt' => Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX. 'product_id = ? and ' .Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX. 'lang_id = ? ' , 'vals' => array($productId, $langId)))) {
            return true;
        }

        return false;
    }

    private function updateSellerProductStatus($productId, $langId, $sellerProductId = 0)
    {
        if (1 > $sellerProductId) {
            FatApp::getDb()->updateFromArray(SellerProduct::DB_TBL_EXTERNAL_RELATIONS, array(SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX. 'indexed_for_search' => applicationConstants::YES), array('smt' => SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX. 'product_id = ? and ' .SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX. 'lang_id = ?','vals' => array($productId, $langId)));
        }

        FatApp::getDb()->updateFromArray(SellerProduct::DB_TBL_EXTERNAL_RELATIONS, array(SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX.'indexed_for_search' => applicationConstants::YES), array('smt' => SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX.'selprod_id = ?', 'vals' => array($sellerProductId)));
        return true;
    }*/
}
