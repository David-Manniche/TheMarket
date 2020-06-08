<?php

class Shipping
{
    public $langId;

    public const BY_ADMIN = 1;
    public const BY_SHOP = 2;

    public const LEVEL_ORDER = 1;
    public const LEVEL_SHOP = 2;
    public const LEVEL_PRODUCT = 3;

    public const TYPE_MANUAL = -1;
    
    public function __construct($langId = 0)
    {
        $this->langId = FatUtility::int($langId);
    }

    /**
    * getShippingMethods
    *
    * @param  int $langId
    * @return array
    */
    public static function getShippedByArr(int $langId)
    {
        $langId = FatUtility::int($langId);
        $arr = [
            static::BY_ADMIN => Labels::getLabel('LBL_ADMIN_SHIPPING', $langId),
            static::BY_SHOP => Labels::getLabel('LBL_SHOP_SHIPPING', $langId)
        ];
        return $arr;
    }

    /**
    * getShippingMethods
    *
    * @param  int $langId
    * @return array
    */
    public static function getLevels(int $langId)
    {
        $langId = FatUtility::int($langId);
        $arr = [
            static::LEVEL_ORDER => Labels::getLabel('LBL_ADMIN_LEVEL_SHIPPING', $langId),
            static::LEVEL_SHOP => Labels::getLabel('LBL_SHOP_LEVEL_SHIPPING', $langId),
            static::LEVEL_PRODUCT => Labels::getLabel('LBL_PRODUCT_LEVEL_SHIPPING', $langId),
        ];
        return $arr;
    }

    /**
    * getShippingMethods
    *
    * @param  int $langId
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
     * getSellerProductRates
     *
     * @param  array $selProdIdArr
     * @param  int $$countryId
     * @param  int $stateId
     * @return array
    */

    public function getSellerProductRates($cartProducts)
    {
        $shippedByArr = [
            static::BY_ADMIN => ''
        ];

        if (!FatApp::getConfig('CONF_SHIPPED_BY_ADMIN_ONLY', FatUtility::VAR_INT, 0)) {
            $shippedByArr[static::BY_SHOP] = '';
        }

        
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
     * @param  int $$countryId
     * @param  int $stateId
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
        $srch->joinShippingProfileZones();
        $srch->joinShippingZones();
        $srch->joinShippingRates($this->langId);
        $srch->joinShippingLocations($countryId, $stateId);
        $srch->addCondition('selprod_id', 'IN', $selProdIdArr);
        $srch->addMultipleFields(array('selprod_id', 'shippro_shipprofile_id', 'shipprozone_id','shiprate_id', 'coalesce(shipr_l.shiprate_name, shipr.shiprate_identifier) as shiprate_name', 'shiprate_cost', 'shiprate_condition_type', 'shiprate_min_val', 'shiprate_max_val', 'psbs.psbs_user_id', 'product_id', 'shiploc_shipzone_id' ,'if(psbs_user_id > 0 or product_seller_id > 0, 1, 0) as shiippingBySeller', 'shipprofile_default', 'shop_id'));
        $srch->addCondition('shiprate_id', '!=', 'null');
        $srch->addGroupBy('selprod_id');
        $srch->addGroupBy('shiprate_id');
        $prodSrchRs = $srch->getResultSet();
        return FatApp::getDb()->fetchAll($prodSrchRs);
    }
}
