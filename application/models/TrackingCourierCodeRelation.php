<?php

class TrackingCourierCodeRelation extends MyAppModel
{
    public const DB_TBL = 'tbl_tracking_courier_code_relation';
    public const DB_TBL_PREFIX = 'tccr_';
   
    public function __construct(int $id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
    }
    
    /**
    * getSearchObject
    *
    * @return object
    */
    public static function getSearchObject(): object
    {
        $srch = new SearchBase(static::DB_TBL, 'tccr');
        return $srch;
    }
    
    /**
    * getDefaultShipAndTrackingRecords
    *
    * @param  int $shipApiPluginId
    * @param  int $trackingApiPluginId
    * @return array
    */
    public function getDefaultShipAndTrackingRecords(int $shipApiPluginId, int $trackingApiPluginId): array
    {
        $srch = static::getSearchObject();
        $srch->addCondition('tccr_shipapi_plugin_id', '=', $shipApiPluginId);
        $srch->addCondition('tccr_tracking_plugin_id', '=', $trackingApiPluginId);
        $rs = $srch->getResultSet();
        return FatApp::getDb()->fetchAll($rs);
    }
    
    /**
    * getDataByShipCourierCode
    *
    * @param  string $shipApiPluginId
    * @return array
    */
    public function getDataByShipCourierCode(string $shipCourierCode): array
    {
        $srch = static::getSearchObject();
        $srch->addCondition('tccr_shipapi_courier_code', '=', $shipCourierCode);
        $rs = $srch->getResultSet();
        return FatApp::getDb()->fetch($rs);
    }

}
