<?php
class ShippingProfileZone extends MyAppModel
{
    const DB_TBL = 'tbl_shipping_profile_zones';
    const DB_TBL_PREFIX = 'shipprozone_';

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }
    
    public static function getSearchObject()
    {
        $srch = new SearchBase(static::DB_TBL, 'spzone');
        $srch->joinTable(ShippingZone::DB_TBL, 'LEFT OUTER JOIN', 'szone.shipzone_id = spzone.shipprozone_shipzone_id', 'szone');
        return $srch;
    }

    /* public function addZone($data)
    {
        if (!FatApp::getDb()->insertFromArray(self::DB_TBL, $data, true, array(), $data)) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }
        return true;
    } */
}
