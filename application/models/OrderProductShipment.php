<?php

class OrderProductShipment extends MyAppModel
{
    public const DB_TBL = 'tbl_order_product_shipment';
    public const DB_TBL_PREFIX = 'opship_';

    private $langId;
        
    /**
     * __construct
     *
     * @param  int $id
     * @param  int $langId
     * @return void
     */
    public function __construct(int $id = 0, int $langId = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'op_id', $id);
        $this->langId = (0 < $langId ? $langId : $this->commonLangId);
    }
}
