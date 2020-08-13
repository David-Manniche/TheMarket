<?php

class Collections extends MyAppModel
{
    public const DB_TBL = 'tbl_collections';
    public const DB_TBL_PREFIX = 'collection_';

    public const DB_TBL_LANG = 'tbl_collections_lang';
    public const DB_TBL_LANG_PREFIX = 'collectionlang_';
    
    public const DB_TBL_COLLECTION_TO_RECORDS = 'tbl_collection_to_records';
    public const DB_TBL_COLLECTION_TO_RECORDS_PREFIX = 'ctr_';

    public const COLLECTION_TYPE_PRODUCT = 1;
    public const COLLECTION_TYPE_CATEGORY = 2;
    public const COLLECTION_TYPE_SHOP = 3;
    public const COLLECTION_TYPE_BRAND = 4;
    public const COLLECTION_TYPE_BLOG = 5;
    public const COLLECTION_TYPE_SPONSORED_PRODUCTS = 6;
    public const COLLECTION_TYPE_SPONSORED_SHOPS = 7;
    public const COLLECTION_TYPE_BANNER = 8;

    public const TYPE_PRODUCT_LAYOUT1 = 1;
    public const TYPE_PRODUCT_LAYOUT2 = 2;
    public const TYPE_PRODUCT_LAYOUT3 = 3;
    public const TYPE_CATEGORY_LAYOUT1 = 4;
    public const TYPE_CATEGORY_LAYOUT2 = 5;
    public const TYPE_SHOP_LAYOUT1 = 6;
    public const TYPE_BRAND_LAYOUT1 = 7;
    public const TYPE_BLOG_LAYOUT1 = 8;
    public const TYPE_SPONSORED_PRODUCT_LAYOUT = 9;
    public const TYPE_SPONSORED_SHOP_LAYOUT = 10;
    public const TYPE_BANNER_LAYOUT1 = 11;
    public const TYPE_BANNER_LAYOUT2 = 12;

    public const LIMIT_PRODUCT_LAYOUT1 = 12;
    public const LIMIT_PRODUCT_LAYOUT2 = 6;
    public const LIMIT_PRODUCT_LAYOUT3 = 12;
    public const LIMIT_CATEGORY_LAYOUT1 = 8;
    public const LIMIT_CATEGORY_LAYOUT2 = 4;
    public const LIMIT_SHOP_LAYOUT1 = 4;
    public const LIMIT_BRAND_LAYOUT1 = 5;
    public const LIMIT_BLOG_LAYOUT1 = 3;

    public const COLLECTION_CRITERIA_PRICE_LOW_TO_HIGH = 1;
    public const COLLECTION_CRITERIA_PRICE_HIGH_TO_LOW = 2;

    public const COLLECTION_WITHOUT_MEDIA = [
        self::COLLECTION_TYPE_SHOP,
        self::COLLECTION_TYPE_BRAND,
        self::COLLECTION_TYPE_BLOG,
        self::COLLECTION_TYPE_SPONSORED_PRODUCTS,
        self::COLLECTION_TYPE_SPONSORED_SHOPS,
        self::COLLECTION_TYPE_BANNER,
    ];
    
    public const COLLECTION_WITHOUT_RECORDS = [
        self::COLLECTION_TYPE_SPONSORED_PRODUCTS,
        self::COLLECTION_TYPE_SPONSORED_SHOPS,
        self::COLLECTION_TYPE_BANNER
    ];
    
    /**
     * __construct
     *
     * @param  int $id
     * @return void
     */
    public function __construct(int $id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
    }
    
    /**
     * getSearchObject
     *
     * @param  bool $isActive
     * @param  int $langId
     * @return object
     */
    public static function getSearchObject(bool $isActive = true, int $langId = 0): object
    {
        $srch = new SearchBase(static::DB_TBL, 'c');

        $srch->addCondition('c.' . static::DB_TBL_PREFIX . 'deleted', '=', applicationConstants::NO);
        if ($isActive == true) {
            $srch->addCondition('c.' . static::DB_TBL_PREFIX . 'active', '=', applicationConstants::ACTIVE);
        }

        if ($langId > 0) {
            $srch->joinTable(
                static::DB_TBL_LANG,
                'LEFT OUTER JOIN',
                'c_l.' . static::DB_TBL_LANG_PREFIX . 'collection_id = c.' . static::tblFld('id') . ' and
			    c_l.' . static::DB_TBL_LANG_PREFIX . 'lang_id = ' . $langId,
                'c_l'
            );
        }

        return $srch;
    }
    
    /**
     * getTypeArr
     *
     * @param  int $langId
     * @return array
     */
    public static function getTypeArr(int $langId): array
    {
        if (1 > $langId) {
            trigger_error(Labels::getLabel('MSG_Language_Id_not_specified.', $langId), E_USER_ERROR);
        }
        return [
            self::COLLECTION_TYPE_PRODUCT => Labels::getLabel('LBL_Product', $langId),
            self::COLLECTION_TYPE_CATEGORY => Labels::getLabel('LBL_Category', $langId),
            self::COLLECTION_TYPE_SHOP => Labels::getLabel('LBL_Shop', $langId),
            self::COLLECTION_TYPE_BRAND => Labels::getLabel('LBL_Brand', $langId),
            self::COLLECTION_TYPE_BLOG => Labels::getLabel('LBL_Blog', $langId),
            self::COLLECTION_TYPE_SPONSORED_PRODUCTS => Labels::getLabel('LBL_Sponsored_Products', $langId),
            self::COLLECTION_TYPE_SPONSORED_SHOPS => Labels::getLabel('LBL_Sponsored_Shops', $langId),
            self::COLLECTION_TYPE_BANNER => Labels::getLabel('LBL_Banner', $langId),
        ];
    }
    
    /**
     * getLayoutTypeArr
     *
     * @param  int $langId
     * @return array
     */
    public static function getLayoutTypeArr(int $langId): array
    {
        if (1 > $langId) {
            trigger_error(Labels::getLabel('MSG_Language_Id_not_specified.', $langId), E_USER_ERROR);
        }

        return [
            self::TYPE_PRODUCT_LAYOUT1 => Labels::getLabel('LBL_Product_Layout1', $langId),
            self::TYPE_PRODUCT_LAYOUT2 => Labels::getLabel('LBL_Product_Layout2', $langId),
            self::TYPE_PRODUCT_LAYOUT3 => Labels::getLabel('LBL_Product_Layout3', $langId),
            self::TYPE_CATEGORY_LAYOUT1 => Labels::getLabel('LBL_Category_Layout1', $langId),
            self::TYPE_CATEGORY_LAYOUT2 => Labels::getLabel('LBL_Category_Layout2', $langId),
            self::TYPE_SHOP_LAYOUT1 => Labels::getLabel('LBL_Shop_Layout1', $langId),
            self::TYPE_BRAND_LAYOUT1 => Labels::getLabel('LBL_Brand_Layout1', $langId),
            self::TYPE_BLOG_LAYOUT1 => Labels::getLabel('LBL_Blog_Layout1', $langId),
            self::TYPE_SPONSORED_PRODUCT_LAYOUT => Labels::getLabel('LBL_Sponsored_Products', $langId),
            self::TYPE_SPONSORED_SHOP_LAYOUT => Labels::getLabel('LBL_Sponsored_Shops', $langId),
            self::TYPE_BANNER_LAYOUT1 => Labels::getLabel('LBL_Banner_Layout1', $langId),
            self::TYPE_BANNER_LAYOUT2 => Labels::getLabel('LBL_Banner_Layout2', $langId),
        ];
    }
    
    /**
     * getTypeSpecificLayouts
     *
     * @param  int $langId
     * @return array
     */
    public static function getTypeSpecificLayouts(int $langId): array
    {
        $collectionLayouts = [
            Collections::COLLECTION_TYPE_PRODUCT => [
                Collections::TYPE_PRODUCT_LAYOUT1 => Labels::getLabel('LBL_Product_Layout1', $langId),
                Collections::TYPE_PRODUCT_LAYOUT2 => Labels::getLabel('LBL_Product_Layout2', $langId),
                Collections::TYPE_PRODUCT_LAYOUT3 => Labels::getLabel('LBL_Product_Layout3', $langId),
            ],
            Collections::COLLECTION_TYPE_CATEGORY => [
                Collections::TYPE_CATEGORY_LAYOUT1 => Labels::getLabel('LBL_Category_Layout1', $langId),
                Collections::TYPE_CATEGORY_LAYOUT2 => Labels::getLabel('LBL_Category_Layout2', $langId),
            ],
            Collections::COLLECTION_TYPE_SHOP => [
                Collections::TYPE_SHOP_LAYOUT1 => Labels::getLabel('LBL_Shop_Layout1', $langId),
            ],
            Collections::COLLECTION_TYPE_BRAND => [
                Collections::TYPE_BRAND_LAYOUT1 => Labels::getLabel('LBL_Brand_Layout1', $langId),
            ],
            Collections::COLLECTION_TYPE_BLOG => [
                Collections::TYPE_BLOG_LAYOUT1 => Labels::getLabel('LBL_Blog_Layout1', $langId),
            ],
            Collections::COLLECTION_TYPE_BANNER => [
                Collections::TYPE_BANNER_LAYOUT1 => Labels::getLabel('LBL_Banner_Layout1', $langId),
                Collections::TYPE_BANNER_LAYOUT2 => Labels::getLabel('LBL_Banner_Layout2', $langId),
            ],
            Collections::COLLECTION_TYPE_SPONSORED_PRODUCTS => [
                Collections::TYPE_SPONSORED_PRODUCT_LAYOUT => Labels::getLabel('LBL_Sponsored_Products', $langId),
            ],
            Collections::COLLECTION_TYPE_SPONSORED_SHOPS => [
                Collections::TYPE_SPONSORED_SHOP_LAYOUT => Labels::getLabel('LBL_Sponsored_Shops', $langId),
            ]
        ];
        return $collectionLayouts;
    }
    
    /**
     * getLayoutImagesArr
     *
     * @return array
     */
    public static function getLayoutImagesArr(): array
    {
        return [
            self::TYPE_PRODUCT_LAYOUT1 => 'product-layout-1.jpg',
            self::TYPE_PRODUCT_LAYOUT2 => 'product-layout-2.jpg',
            self::TYPE_PRODUCT_LAYOUT3 => 'product-layout-3.jpg',
            self::TYPE_CATEGORY_LAYOUT1 => 'category-layout-1.jpg',
            self::TYPE_CATEGORY_LAYOUT2 => 'category-layout-2.jpg',
            self::TYPE_SHOP_LAYOUT1 => 'shop-layout-1.jpg',
            self::TYPE_BRAND_LAYOUT1 => 'brand-layout-1.jpg',
            self::TYPE_BLOG_LAYOUT1 => 'blog-layout-1.jpg',
            self::TYPE_SPONSORED_PRODUCT_LAYOUT => 'sponsored-product-layout-1.jpg',
            self::TYPE_SPONSORED_SHOP_LAYOUT => 'sponsored-shop-layout-1.jpg',
            self::TYPE_BANNER_LAYOUT1 => 'banner-layout-1.jpg',
            self::TYPE_BANNER_LAYOUT2 => 'banner-layout-2.jpg',
        ];
    }
    
    /**
     * getCriteria
     *
     * @return array
     */
    public static function getCriteria()
    {
        return [
            static::COLLECTION_CRITERIA_PRICE_LOW_TO_HIGH => "Price Low to High",
            static::COLLECTION_CRITERIA_PRICE_HIGH_TO_LOW => "Price High to Low",
        ];
    }
    
    /**
     * addUpdateCollectionRecord
     *
     * @param  int $collectionId
     * @param  int $recordId
     * @return bool
     */
    public function addUpdateCollectionRecord(int $collectionId, int $recordId): bool
    {
        if (!$collectionId || !$recordId) {
            $this->error = Labels::getLabel('MSG_Invalid_Request', $this->commonLangId);
            return false;
        }
        
        $record = new TableRecord(static::DB_TBL_COLLECTION_TO_RECORDS);
        $dataToSave = array();
        $dataToSave[static::DB_TBL_COLLECTION_TO_RECORDS_PREFIX . 'collection_id'] = $collectionId;
        $dataToSave[static::DB_TBL_COLLECTION_TO_RECORDS_PREFIX . 'record_id'] = $recordId;
        $record->assignValues($dataToSave);
        if (!$record->addNew(array(), $dataToSave)) {
            $this->error = $record->getError();
            return false;
        }
        return true;
    }
    
    /**
     * addUpdateData
     *
     * @param  array $data
     * @return bool
     */
    public function addUpdateData(array $data): bool
    {
        unset($data['collection_id']);
        $assignValues = $data;
        $assignValues['collection_deleted'] = 0;
        if ($this->mainTableRecordId > 0) {
            $assignValues['collection_id'] = $this->mainTableRecordId;
        }
        
        $this->assignValues($assignValues);
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }

        return true;
    }
    
    /**
     * removeCollectionRecord
     *
     * @param  int $collectionId
     * @param  int $recordId
     * @return bool
     */
    public function removeCollectionRecord(int $collectionId, int $recordId): bool
    {
        $db = FatApp::getDb();
        if (!$collectionId || !$recordId) {
            $this->error = Labels::getLabel('ERR_Invalid_Request', $this->commonLangId);
            return false;
        }
        if (!$db->deleteRecords(static::DB_TBL_COLLECTION_TO_RECORDS, array('smt' => static::DB_TBL_COLLECTION_TO_RECORDS_PREFIX . 'collection_id = ? AND ' . static::DB_TBL_COLLECTION_TO_RECORDS_PREFIX . 'record_id = ?', 'vals' => array($collectionId, $recordId)))) {
            $this->error = $db->getError();
            return false;
        }
        return true;
    }
    
    /**
     * canRecordMarkDelete
     *
     * @param  int $collection_id
     * @return bool
     */
    public function canRecordMarkDelete(int $collection_id): bool
    {
        $srch = static::getSearchObject(false);
        $srch->addCondition('collection_deleted', '=', applicationConstants::NO);
        $srch->addCondition('collection_id', '=', $collection_id);
        $srch->addFld('collection_id');
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        if (!empty($row) && $row['collection_id'] == $collection_id) {
            return true;
        }
        return false;
    }
    
    /**
     * getSellProds
     *
     * @param  int $collection_id
     * @param  int $lang_id
     * @return array
     */
    public static function getSellProds(int $collection_id, int $lang_id): array
    {
        if (!$collection_id || !$lang_id) {
            trigger_error(Labels::getLabel('MSG_Arguments_not_specified.', $lang_id), E_USER_ERROR);
            return false;
        }

        $srch = new SearchBase(static::DB_TBL_COLLECTION_TO_RECORDS);
        $srch->addCondition(static::DB_TBL_COLLECTION_TO_RECORDS_PREFIX . 'collection_id', '=', $collection_id);
        $srch->joinTable(SellerProduct::DB_TBL, 'INNER JOIN', SellerProduct::DB_TBL_PREFIX . 'id = ' . static::DB_TBL_COLLECTION_TO_RECORDS_PREFIX . 'record_id');
        $srch->joinTable(Product::DB_TBL, 'INNER JOIN', SellerProduct::DB_TBL_PREFIX . 'product_id = ' . Product::DB_TBL_PREFIX . 'id');

        $srch->joinTable(SellerProduct::DB_TBL . '_lang', 'LEFT JOIN', 'lang.selprodlang_selprod_id = ' . SellerProduct::DB_TBL_PREFIX . 'id AND selprodlang_lang_id = ' . $lang_id, 'lang');

        $srch->addMultipleFields(array('selprod_id as record_id', 'IFNULL(selprod_title,product_identifier) as record_title'));

        $rs = $srch->getResultSet();
        $db = FatApp::getDb();
        $data = array();
        while ($row = $db->fetch($rs)) {
            $data[] = $row;
        }
        return $data;
    }
    
    /**
     * getCategories
     *
     * @param  int $collection_id
     * @param  int $lang_id
     * @return array
     */
    public static function getCategories(int $collection_id, int $lang_id): array
    {
        if (!$collection_id || !$lang_id) {
            trigger_error(Labels::getLabel("ERR_Arguments_not_specified.", $lang_id), E_USER_ERROR);
            return false;
        }

        $srch = new SearchBase(static::DB_TBL_COLLECTION_TO_RECORDS);
        $srch->doNotLimitRecords();
        $srch->doNotCalculateRecords();
        $srch->addCondition(static::DB_TBL_COLLECTION_TO_RECORDS_PREFIX . 'collection_id', '=', $collection_id);

        $srch->joinTable(ProductCategory::DB_TBL, 'INNER JOIN', ProductCategory::DB_TBL_PREFIX . 'id = ' . static::DB_TBL_COLLECTION_TO_RECORDS_PREFIX . 'record_id');

        $srch->joinTable(ProductCategory::DB_TBL_LANG, 'LEFT JOIN', 'lang.prodcatlang_prodcat_id = ' . ProductCategory::DB_TBL_PREFIX . 'id AND prodcatlang_lang_id = ' . $lang_id, 'lang');
        $srch->addMultipleFields(array('prodcat_id as record_id', 'IFNULL(prodcat_name, prodcat_identifier) as record_title'));
        $rs = $srch->getResultSet();
        $db = FatApp::getDb();
        $data = $db->fetchAll($rs);
        return $data;
    }
    
    /**
     * getShops
     *
     * @param  int $collection_id
     * @param  int $lang_id
     * @return array
     */
    public static function getShops(int $collection_id, int $lang_id): array
    {
        if (!$collection_id || !$lang_id) {
            trigger_error(Labels::getLabel("ERR_Arguments_not_specified.", $lang_id), E_USER_ERROR);
            return false;
        }

        $srch = new SearchBase(static::DB_TBL_COLLECTION_TO_RECORDS);
        $srch->doNotLimitRecords();
        $srch->doNotCalculateRecords();
        $srch->addCondition(static::DB_TBL_COLLECTION_TO_RECORDS_PREFIX . 'collection_id', '=', $collection_id);

        $srch->joinTable(Shop::DB_TBL, 'INNER JOIN', Shop::DB_TBL_PREFIX . 'id = ' . static::DB_TBL_COLLECTION_TO_RECORDS_PREFIX . 'record_id');

        $srch->joinTable(Shop::DB_TBL_LANG, 'LEFT JOIN', 'lang.shoplang_shop_id = ' . Shop::DB_TBL_PREFIX . 'id AND shoplang_lang_id = ' . $lang_id, 'lang');
        $srch->addMultipleFields(array('shop_id as record_id', 'IFNULL(shop_name, shop_identifier) as record_title'));
        $rs = $srch->getResultSet();

        $db = FatApp::getDb();
        $data = $db->fetchAll($rs);
        return $data;
    }
    
    /**
     * getBrands
     *
     * @param  int $collectionId
     * @param  int $langId
     * @return array
     */
    public static function getBrands(int $collectionId, int $langId): array
    {
        if (!$collectionId || !$langId) {
            trigger_error(Labels::getLabel("ERR_Arguments_not_specified.", $langId), E_USER_ERROR);
            return false;
        }

        $srch = new SearchBase(static::DB_TBL_COLLECTION_TO_RECORDS);
        $srch->doNotLimitRecords();
        $srch->doNotCalculateRecords();
        $srch->addCondition(static::DB_TBL_COLLECTION_TO_RECORDS_PREFIX . 'collection_id', '=', $collectionId);
        $srch->joinTable(Brand::DB_TBL, 'INNER JOIN', Brand::DB_TBL_PREFIX . 'id = ' . static::DB_TBL_COLLECTION_TO_RECORDS_PREFIX . 'record_id');
        $srch->joinTable(Brand::DB_TBL_LANG, 'LEFT JOIN', 'lang.brandlang_brand_id = ' . Brand::DB_TBL_PREFIX . 'id AND brandlang_lang_id = ' . $langId, 'lang');
        $srch->addMultipleFields(array('brand_id as record_id', 'IFNULL(brand_name, brand_identifier) as record_title'));
        $rs = $srch->getResultSet();

        $db = FatApp::getDb();
        $data = $db->fetchAll($rs);
        return $data;
    }
    
    /**
     * getBlogs
     *
     * @param  int $collectionId
     * @param  int $langId
     * @return array
     */
    public static function getBlogs(int $collectionId, int $langId): array
    {
        if (!$collectionId || !$langId) {
            trigger_error(Labels::getLabel("ERR_Arguments_not_specified.", $langId), E_USER_ERROR);
            return false;
        }

        $srch = new SearchBase(static::DB_TBL_COLLECTION_TO_RECORDS);
        $srch->doNotLimitRecords();
        $srch->doNotCalculateRecords();
        $srch->addCondition(static::DB_TBL_COLLECTION_TO_RECORDS_PREFIX . 'collection_id', '=', $collectionId);

        $srch->joinTable(BlogPost::DB_TBL, 'INNER JOIN', BlogPost::DB_TBL_PREFIX . 'id = ' . static::DB_TBL_COLLECTION_TO_RECORDS_PREFIX . 'record_id');

        $srch->joinTable(BlogPost::DB_TBL_LANG, 'LEFT JOIN', 'lang.postlang_post_id = ' . BlogPost::DB_TBL_PREFIX . 'id AND postlang_lang_id = ' . $langId, 'lang');
        $srch->addMultipleFields(array('post_id as record_id', 'IFNULL(post_title, post_identifier) as record_title'));
        $rs = $srch->getResultSet();
        $db = FatApp::getDb();
        $data = $db->fetchAll($rs);
        return $data;
    }
    
    /**
     * setLastUpdatedOn
     *
     * @param  int $collectionId
     * @return bool
     */
    public static function setLastUpdatedOn(int $collectionId): bool
    {
        $collectionId = FatUtility::int($collectionId);
        if (1 > $collectionId) {
            return false;
        }

        $collectionObj = new Collections($collectionId);
        $collectionObj->addUpdateData(array('collection_img_updated_on' => date('Y-m-d H:i:s')));
        return true;
    }
       
    /**
     * saveLangData
     *
     * @param  int $langId
     * @param  string $prodCatName
     * @return bool
     */
    public function saveLangData(int $langId, string $collectionName): bool
    {
        $langId = FatUtility::int($langId);
        if ($this->mainTableRecordId < 1 || $langId < 1) {
            $this->error = Labels::getLabel('ERR_Invalid_Request', $this->commonLangId);
            return false;
        }

        $data = array(
            'collectionlang_collection_id' => $this->mainTableRecordId,
            'collectionlang_lang_id' => $langId,
            'collection_name' => $collectionName,
        );

        if (!$this->updateLangData($langId, $data)) {
            $this->error = $this->getError();
            return false;
        }
        return true;
    }
    
    /**
     * saveTranslatedLangData
     *
     * @param  int $langId
     * @return bool
     */
    public function saveTranslatedLangData(int $langId): bool
    {
        $langId = FatUtility::int($langId);
        if ($this->mainTableRecordId < 1 || $langId < 1) {
            $this->error = Labels::getLabel('ERR_Invalid_Request', $this->commonLangId);
            return false;
        }

        $translateLangobj = new TranslateLangData(static::DB_TBL_LANG);
        if (false === $translateLangobj->updateTranslatedData($this->mainTableRecordId, 0, $langId)) {
            $this->error = $translateLangobj->getError();
            return false;
        }
        return true;
    }
}
