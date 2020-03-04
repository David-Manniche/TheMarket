<?php
class FullTextSearch extends FatModel
{
    private $langId;
    private $results;

    public const LIMIT = 50;

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
    }

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
        //$srch->doNotLimitRecords();
        $srch->setPageSize(100);
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

        $defaultPlugin = (new self(CommonHelper::getLangId()))->getDefaultPlugin();
        if (!$defaultPlugin) {
            return false;
        }

        foreach ($languages as $langId => $language) {
            $langId = FatUtility::int($langId);
            $srch = new ProductSearch($langId);
            $srch->setDefinedCriteria(1, 0, array(), false);
            $srch->joinProductToCategory();
            $srch->joinSellerSubscription();
            $srch->addSubscriptionValidCondition();
            $srch->addCondition('selprod_deleted', '=', applicationConstants::NO);
            $srch->addMultipleFields(array('product_id','product_name', 'product_type', 'product_model', 'product_seller_id', 'product_updated_on', 'product_active', 'product_approved', 'product_upc', 'product_isbn', 'product_ship_country', 'product_ship_free', 'product_cod_enabled', 'product_short_description', 'product_description', 'product_tags_string', 'theprice', 'selprod_id','prod_rating as product_rating', 'brand_id','brand_name','brand_short_description','brand_active'));
            $srch->joinProductRating();
            $srch->doNotCalculateRecords();

            if (1 > $productId) {
                //$srch->setPageSize(FatUtility::int(self::LIMIT));
                $srch->doNotLimitRecords();
            } else {
                $srch->addCondition(Product::DB_TBL_PREFIX . 'id', '=', $productId);
                $srch->setPageSize(1);
            }

            $rs    = $srch->getResultSet();
            $products = FatApp::getDb()->fetchAll($srch->getResultSet());
                        
            $fullTextSearch = new $defaultPlugin($langId);

            if (empty($products)) {
                if (!$fullTextSearch->isDocumentExists($productId)) {
                    static::logError($fullTextSearch->getError());
                    continue;
                }

                if (!$fullTextSearch->deleteDocument($productId)) {
                    static::logError($fullTextSearch->getError());
                }
                continue;
            }

            foreach ($products as $key => $product) {
                $convertFieldType = array('theprice', 'product_rating');

                foreach ($convertFieldType as $convertFieldKey) {
                    if (array_key_exists($convertFieldKey, $product)) {
                        $product[$convertFieldKey] = FatUtility::float($product[$convertFieldKey]);
                    }
                }

                $brands = [];
                $brandKeys = array('brand_id', 'brand_name','brand_short_description','brand_active');

                foreach ($brandKeys as $brandKey) {
                    if (array_key_exists($brandKey, $product)) {
                        $brands[$brandKey] = $product[$brandKey];
                        unset($product[$brandKey]);
                    }
                }

                $data = array(
                    'general' => $product,
                    'brand' => $brands,
                    'categories' => static::getCategories($product['product_id'], $langId),
                    'options' => static::getOptions($product['product_id'], $langId),
                    /* 'inventories' => static::insertInventory($productId, $langId) */
                );

                // Checking Document Id Exists Or Not
                if (!$fullTextSearch->isDocumentExists($product['product_id'])) {
                    if (!$fullTextSearch->createDocument($product['product_id'], $data)) {
                        static::logError($fullTextSearch->getError());
                        continue;
                    }
                    static::insertSellerProduct($product['product_id'], 0, $langId, $fullTextSearch);
                    continue;
                }
                
                if (!$fullTextSearch->updateDocument($product['product_id'], $data)) {
                    static::logError($fullTextSearch->getError());
                    continue;
                }

                static::insertSellerProduct($product['product_id'], 0, $langId, $fullTextSearch);
            }
        }
        return true;
    }
    
    public static function insertInventory($productId, $langId)
    {
        $langId = FatUtility::int($langId);
        $productId = FatUtility::int($productId);
        
        if (1 > $productId) {
            return false;
        }
        
        $defaultPlugin = (new self(CommonHelper::getLangId()))->getDefaultPlugin();
        if (!$defaultPlugin) {
            return false;
        }
        
        $fullTextSearch = new $defaultPlugin($langId);
        
        $srch  = SellerProduct::getSearchObject($langId);
        $srch->addCondition(SellerProduct::DB_TBL_PREFIX . 'product_id', '=', $productId);
        $srch->addMultipleFields(array('selprod_id','selprod_title','selprod_code','selprod_stock', 'selprod_condition', 'selprod_active', 'selprod_cod_enabled', 'selprod_available_from','selprod_price', 'selprod_sold_count', 'selprod_sku'));
        $rs    = $srch->getResultSet();
        $sellerProducts = FatApp::getDb()->fetchAll($rs);
        
        foreach ($sellerProducts as $key => $sellerProduct) {
            $sellerProducts[$key]['min_price'] = (new self())->getSellerProductMinimumPrice($sellerProduct['selprod_id']);
        }
        return $sellerProducts;
    }
    
    public static function getCategories($productId, $langId)
    {
        $productId = FatUtility::int($productId);
        $langId = FatUtility::int($langId);
        
        $srch = new SearchBase(Product::DB_TBL_PRODUCT_TO_CATEGORY, 'ptc');
        $srch->addCondition(Product::DB_TBL_PRODUCT_TO_CATEGORY_PREFIX . 'product_id', '=', $productId);
        $srch->joinTable(ProductCategory::DB_TBL, 'INNER JOIN', ProductCategory::DB_TBL_PREFIX. 'id = ptc.' . Product::DB_TBL_PRODUCT_TO_CATEGORY_PREFIX . 'prodcat_id', 'cat');
        $srch->joinTable(ProductCategory::DB_TBL_LANG, 'LEFT OUTER JOIN', ProductCategory::DB_TBL_LANG_PREFIX. 'prodcat_id = '. ProductCategory::tblFld('id'). ' and ' .ProductCategory::DB_TBL_LANG_PREFIX. 'lang_id = '.$langId);
        $srch->doNotLimitRecords();
        $srch->doNotCalculateRecords();
        $rs = $srch->getResultSet();
        return FatApp::getDb()->fetchAll($rs);
    }
        
    public static function getOptions($productId, $langId)
    {
        $productId = FatUtility::int($productId);
        $langId = FatUtility::int($langId);
        
        $srch = new SearchBase(Product::DB_PRODUCT_TO_OPTION);
        $srch->addCondition(Product::DB_PRODUCT_TO_OPTION_PREFIX . 'product_id', '=', $productId);
        $srch->joinTable(OptionValue::DB_TBL, 'LEFT JOIN', Product::DB_PRODUCT_TO_OPTION_PREFIX . 'option_id = opval.'. OptionValue::DB_TBL_PREFIX .'option_id', 'opval');
        $srch->joinTable(OptionValue::DB_TBL_LANG, 'LEFT OUTER JOIN', OptionValue::DB_TBL_LANG_PREFIX . 'optionvalue_id = '. OptionValue::tblFld('id'). ' and ' . OptionValue::DB_TBL_LANG_PREFIX. 'lang_id = '.$langId);
        
        $srch->doNotLimitRecords();
        $srch->doNotCalculateRecords();
        
        $rs = $srch->getResultSet();
        return FatApp::getDb()->fetchAll($rs);
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
        $plugin = new Plugin();
        $defaultPlugin = $plugin->getDefaultPluginData(Plugin::TYPE_FULL_TEXT_SEARCH, "plugin_code");
        if (0 > $defaultPlugin) {
            return false;
        }
        return $defaultPlugin;
    }
    
    private function updateProductStatus($productId, $langId)
    {
        if (FatApp::getDb()->deleteRecords(Product::DB_PRODUCT_EXTERNAL_RELATIONS, array('smt' => Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX. 'product_id = ? and ' .Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX. 'lang_id = ? ' , 'vals' => array($productId, $langId)))) {
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
        if (!$minimumPrice) {
            return array();
        }
        return $minimumPrice;
    }
    
    /*
    * Insert Inventory
    * Param @productId
    * Param @sellerProductId required when you want to update a seller product data
    */

    public static function insertSellerProduct($productId, $sellerProductId = 0, $langId = 0, $fullTextSearch = null)
    {
        $langId = FatUtility::int($langId);
        if (1 > $langId) {
            $languages = Language::getAllNames();
        } else {
            $languages[$langId] = $langId;
        }
        
        $productId = FatUtility::int($productId);
        $sellerProductId = FatUtility::int($sellerProductId);
        
        if (0 > count($languages)) {
            return false;
        }
        
        if (1 > $productId) {
            return false;
        }

        if (null == $fullTextSearch) {
            $defaultPlugin = (new self($langId))->getDefaultPlugin();
            if (!$defaultPlugin) {
                return false;
            }
        }

        foreach ($languages as $langId => $language) {
            $srch = new ProductSearch($langId);
            $srch->setDefinedCriteria(1, 0, array(), false);
            $srch->joinProductToCategory();
            $srch->joinSellerSubscription();
            $srch->addSubscriptionValidCondition();
            $srch->addCondition('msellprod.' . SellerProduct::DB_TBL_PREFIX . 'deleted', '=', applicationConstants::NO);
            $srch->addCondition('msellprod.' . SellerProduct::DB_TBL_PREFIX . 'product_id', '=', $productId);
            $srch->addCondition(User::DB_TBL_CRED_PREFIX . 'active', '=', applicationConstants::ACTIVE);
            $srch->addCondition('msellprod.' . SellerProduct::DB_TBL_PREFIX . 'active', '=', applicationConstants::ACTIVE);
            if (0 < $sellerProductId) {
                $srch->addCondition('msellprod.' . SellerProduct::DB_TBL_PREFIX . 'id', '=', $sellerProductId);
            }
           
            $srch->addMultipleFields(array('msellprod.selprod_id','sprods_l.selprod_title','msellprod.selprod_product_id','msellprod.selprod_code','msellprod.selprod_stock', 'msellprod.selprod_condition', 'msellprod.selprod_active', 'msellprod.selprod_cod_enabled', 'msellprod.selprod_available_from','msellprod.selprod_price', 'msellprod.selprod_sold_count', 'msellprod.selprod_sku','msellprod.selprod_user_id','shop_id','shop_name', 'shop_contact_person', 'shop_description','shop_active','user_id','user_name','user_phone' ));
            $rs  = $srch->getResultSet();
            $sellerProducts = FatApp::getDb()->fetchAll($rs);
            
            if (null == $fullTextSearch) {
                $fullTextSearch = new $defaultPlugin($langId);
            }

            if (empty($sellerProducts)) {
                if (!$fullTextSearch->isDocumentExists($productId)) {
                    static::logError($fullTextSearch->getError());
                    continue;
                }

                if ($sellerProductId > 0) {
                    $sellerProductKey = array('selprod_id' => $sellerProductId );
                    if (!$fullTextSearch->deleteDocumentData($productId, 'inventories', $sellerProductKey)) {
                        static::logError($fullTextSearch->getError());
                        continue;
                    }
                }

                // Todo remove all inventories
                continue;
            }

                      
            
            $shopFields = array('shop_id','shop_name','shop_description','shop_contact_person','shop_active');
            $userFields = array('user_id','user_name','user_phone');
            
            foreach ($sellerProducts as $key => $sellerProduct) {
                $sellerUserId = FatUtility::int($sellerProduct['selprod_user_id']);
                foreach ($shopFields as $shopFieldName) {
                    if (array_key_exists($shopFieldName, $sellerProduct)) {
                        $sellerProducts[$key]['shop'][$shopFieldName] = $sellerProduct[$shopFieldName];
                        unset($sellerProducts[$key][$shopFieldName]);
                    }
                }
                
                foreach ($userFields as $userFieldName) {
                    if (array_key_exists($userFieldName, $sellerProduct)) {
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
 

   

    /*
    * @productId -
    */
    public static function updateGeneralMinPrice($productId)
    {
        $productId = FatUtility::int($productId);
        $languages = Language::getAllNames();

        if (0 > count($languages)) {
            return false;
        }

        foreach ($languages as $langId => $language) {
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

            if (0 > $data) {
                return false;
            }
            $convertFieldType = array('theprice');
            foreach ($convertFieldType as $convertFieldKey) {
                if (array_key_exists($convertFieldKey, $data)) {
                    $data[$convertFieldKey] = FatUtility::float($data[$convertFieldKey]);
                }
            }

            $updatePrice = $fullTextSearch->updateDocument($productId, $data);
            if (!$updatePrice) {
                continue;
            }
        }
    }

    /* End Elastic Search Data Insert Functions ] */


    /* Search all the categories */

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


    

    public static function getListingObj($criteria, $langId, $userId = 0)
    {
        $langId = FatUtility::int($langId);
        if (1 > $langId) {
            trigger_error(Labels::getLabel('LBL_INVALID_REQUEST', $langId), E_USER_ERROR);
        }

        $defaultPlugin = (new self($langId))->getDefaultPlugin();
        if (!$defaultPlugin) {
            trigger_error(Labels::getLabel('LBL_INVALID_REQUEST', $langId), E_USER_ERROR);
        }

        require_once CONF_INSTALLATION_PATH . 'library/plugins/full-text-search/' . $defaultPlugin . '.php';
        $srch = new $defaultPlugin($langId);
            
        if (array_key_exists('keyword', $criteria)) {
            $srch->addKeywordCondition($criteria['keyword']);
        }
        
        if (array_key_exists('brand', $criteria) && !empty($criteria['brand'])) {
            if (true ===  MOBILE_APP_API_CALL) {
                $criteria['brand'] = json_decode($criteria['brand'], true);
            }
            $srch->addBrandConditions($criteria['brand']);
        }

        if (array_key_exists('category', $criteria)) {
            $srch->addCategoryCondition($criteria['category']);
        }
       
        if (array_key_exists('shop_id', $criteria)) {
            $shop_id = FatUtility::int($criteria['shop_id']);
            $srch->addShopIdCondition($shop_id);
        }
        
        if (array_key_exists('optionvalue', $criteria)) {
            if (!empty($criteria['optionvalue'])) {
                $srch->addOptionCondition($criteria['optionvalue']);
            }
        }
        
        if (array_key_exists('condition', $criteria)) {
            if (true === MOBILE_APP_API_CALL) {
                $criteria['condition'] = json_decode($criteria['condition'], true);
            }
            $condition = is_array($criteria['condition']) ? array_filter($criteria['condition']) : $criteria['condition'];
            $srch->addConditionCondition($condition);
        }

        if (array_key_exists('out_of_stock', $criteria)) {
            if (!empty($criteria['out_of_stock']) && $criteria['out_of_stock'] == 1) {
                $srch->excludeOutOfStockProducts();
            }
        }

        $minPriceRange = '';
        if (array_key_exists('price-min-range', $criteria)) {
            $minPriceRange = floor($criteria['price-min-range']);
        } elseif (array_key_exists('min_price_range', $criteria)) {
            $minPriceRange = floor($criteria['min_price_range']);
        }
                
        if (!empty($minPriceRange) && isset($criteria['currency_id'])) {
            $$minPriceRange = CommonHelper::convertExistingToOtherCurrency($criteria['currency_id'], $minPriceRange, FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1), false);
        }

        $maxPriceRange = '';
        if (array_key_exists('price-max-range', $criteria)) {
            $maxPriceRange = ceil($criteria['price-max-range']);
        } elseif (array_key_exists('max_price_range', $criteria)) {
            $maxPriceRange = ceil($criteria['max_price_range']);
        }

        if (!empty($maxPriceRange)) {
            $maxPriceRange = CommonHelper::convertExistingToOtherCurrency($criteria['currency_id'], $maxPriceRange, FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1), false);
        }
        
        $srch->addPriceFilters($minPriceRange, $maxPriceRange);

        if (array_key_exists('featured', $criteria)) {
            $featured = FatUtility::int($criteria['featured']);
            if (0 < $featured) {
                $srch->addFeaturedProdCondition();
            }
        }

        if (array_key_exists('sortBy', $criteria)) {
            $sortBy = $criteria['sortBy'];
        }

        $sortOrder = 'asc';
        if (array_key_exists('sortOrder', $criteria)) {
            $sortOrder = $criteria['sortOrder'];
        }

        $sortFields = [];

        if (!empty($sortBy)) {
            $sortByArr = explode("_", $sortBy);
            $sortBy = isset($sortByArr[0]) ? $sortByArr[0] : $sortBy;
            $sortOrder = isset($sortByArr[1]) ? $sortByArr[1] : $sortOrder;

            if (!in_array($sortOrder, array('asc', 'desc'))) {
                $sortOrder = 'asc';
            }

            if (!in_array($sortBy, array('keyword', 'price', 'popularity', 'rating', 'discounted'))) {
                $sortOrder = 'keyword_relevancy';
            }

            switch ($sortBy) {
                case 'price':
                    $sortFields = array('general.theprice' => array('order' => $sortOrder));
                    break;
                case 'popularity':
                    $sortFields = array('inventories.selprod_sold_count' => array('order' => $sortOrder));
                    break;
               /*  case 'discounted':
                    $srch->addFld('ROUND(((selprod_price - theprice)*100)/selprod_price) as discountedValue');
                    $srch->addOrder('discountedValue', 'DESC');
                    break; */
                case 'rating':
                    $sortFields = array('general.product_rating' => array('order' => $sortOrder));
                    break;
                default:
                   // $srch->addOrder('keyword_relevancy', 'DESC');
                    break;
            }
        }

        $srch->setSortFields($sortFields);

        return $srch;
    }
}
