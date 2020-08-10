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

    public const TYPE_PRODUCT_LAYOUT1 = 1;
    public const TYPE_PRODUCT_LAYOUT2 = 2;
    public const TYPE_PRODUCT_LAYOUT3 = 3;
    public const TYPE_CATEGORY_LAYOUT1 = 4;
    public const TYPE_CATEGORY_LAYOUT2 = 5;
    public const TYPE_SHOP_LAYOUT1 = 6;
    public const TYPE_BRAND_LAYOUT1 = 7;
    public const TYPE_BLOG_LAYOUT1 = 8;

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
            self::COLLECTION_TYPE_BLOG => Labels::getLabel('LBL_BLOG', $langId),
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
            self::TYPE_BLOG_LAYOUT1 => Labels::getLabel('LBL_BLOG_LAYOUT1', $langId),
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

        $srch->addMultipleFields(array('selprod_id', 'IFNULL(selprod_title,product_identifier) as selprod_title'));

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
        $srch->addMultipleFields(array('prodcat_id', 'IFNULL(prodcat_name, prodcat_identifier) as prodcat_name'));
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
        $srch->addMultipleFields(array('shop_id', 'IFNULL(shop_name, shop_identifier) as shop_name'));
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
        $srch->addMultipleFields(array('brand_id', 'IFNULL(brand_name, brand_identifier) as brand_name'));
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
        $srch->addMultipleFields(array('post_id', 'IFNULL(post_title, post_identifier) as post_title'));
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
}
