<?php

class Shipping
{
    public $langId;

    public const TYPE_MANUAL = -1;
    
    public function __construct($langId = 0)
    {
        $this->langId = FatUtility::int($langId);
    }

    /**
     * getShippingMethods
     *
     * @return array
     */
    public static function getShippingMethods($langId)
    {
        $langId = FatUtility::int($langId);
        $arr = [
            static::TYPE_MANUAL => Labels::getLabel('LBL_System_Level_Shipping', $langId)
        ];

        // Add third party shipping plugins
        return $arr;
    }


    /**
     * getSellerProductShippingProfileIds
     *
     * @param  array $selProdIdArr
     * @return array
     */
    public function getSellerProductShippingProfileIds(array $selProdIdArr)
    {
        $selProdIdArr = FatUtility::int($selProdIdArr);

        $selProdShipProfileArr = [];
        if (empty($selProdIdArr)) {
            return $selProdShipProfileArr;
        }
        
        $srch = new ProductSearch($this->langId);
        $srch->setDefinedCriteria(0, 0, array(), false);
        $srch->joinProductShippedBySeller();
        $srch->joinShippingProfileProducts();
        $srch->addCondition('selprod_id', 'IN', $selProdIdArr);
        $srch->addMultipleFields(array('selprod_id', 'shippro_shipprofile_id'));
        $srch->addGroupBy('selprod_id');
        $prodSrchRs = $srch->getResultSet();
        return FatApp::getDb()->fetchAllAssoc($prodSrchRs);
    }

    /**
     * getSellerProductShippingRates
     *
     * @param  array $selProdIdArr
     * @return array
     */

    public function getSellerProductShippingRates(array $selProdIdArr, int $countryId, int $stateId)
    {
        $countryId = FatUtility::int($countryId);
        $stateId = FatUtility::int($stateId);

        $srch = new ProductSearch();
        $srch->setDefinedCriteria(0, 0, array(), false);
        $srch->joinProductShippedBy();
        $srch->joinShippingProfileProducts();
        $srch->joinShippingProfile();
        $srch->joinShippingZones();
        $srch->joinShippingRates($this->langId);
        $srch->joinShippingLocations($countryId, $stateId);
        $srch->addCondition('selprod_id', 'IN', $selProdIdArr);
        $srch->addMultipleFields(array('selprod_id', 'shippro_shipprofile_id', 'shipprozone_id','shiprate_id', 'coalesce(shipr_l.shiprate_name, shipr.shiprate_identifier) as shiprate_name', 'shiprate_cost', 'shiprate_condition_type', 'shiprate_min_val', 'shiprate_max_val', 'psbs.psbs_user_id'));
        $srch->addCondition('shiprate_id', '!=', 'null');
        $srch->addGroupBy('shiprate_id');
        echo $srch->getQuery();
        $prodSrchRs = $srch->getResultSet();
        return FatApp::getDb()->fetchAll($prodSrchRs, 'shiprate_id');
    }
}
