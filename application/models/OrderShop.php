<?php
class OrderShop extends MyAppModel
{
    public const DB_TBL = 'tbl_order_shops';
    public const DB_TBL_PREFIX = 'os_';

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    public static function getSearchObject()
    {
        $srch = new SearchBase(static::DB_TBL, 'os');
        return $srch;
    }
}
