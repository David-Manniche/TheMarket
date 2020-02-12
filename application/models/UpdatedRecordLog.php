<?php
class UpdatedRecordLog extends MyAppModel
{
    public const DB_TBL = 'tbl_updated_record_log';
    public const DB_TBL_PREFIX = 'urlog_';

    public const TYPE_SHOP = 1;
    public const TYPE_USER = 2;
    public const TYPE_CATEGORY = 3;
    public const TYPE_BRAND = 4;
    public const TYPE_COUNTRY = 5;
    public const TYPE_STATE = 6;
    public const TYPE_PRODUCT = 7;
    public const TYPE_INVENTORY = 8;

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    public static function getSearchObject()
    {
        $srch = new SearchBase(static::DB_TBL, 'urlog');
        return $srch;
    }

    public static function getTypeArr()
    {
        return [
            Shop::DB_TBL_PREFIX => static::TYPE_SHOP,
            Product::DB_TBL_PREFIX =>  static::TYPE_PRODUCT,
            User::DB_TBL_PREFIX =>  static::TYPE_USER,
            ProductCategory::DB_TBL_PREFIX =>  static::TYPE_CATEGORY,
            SellerProduct::DB_TBL_PREFIX =>  static::TYPE_INVENTORY,
            Brand::DB_TBL_PREFIX =>  static::TYPE_BRAND,
            Countries::DB_TBL_PREFIX =>  static::TYPE_COUNTRY,
            States::DB_TBL_PREFIX =>  static::TYPE_STATE,
        ];
    }
}
