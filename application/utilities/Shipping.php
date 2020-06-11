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
    
    public function __construct(int $langId = 0)
    {
        $this->langId = FatUtility::int($langId);
    }

    /**
    * getShippingMethods
    *
    * @param  int $langId
    * @return array
    */
    public static function getShippedByArr(int $langId):array
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
    public static function getLevels(int $langId):array
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
    public static function getShippingMethods($langId):array
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
    public function getSellerProductShippingProfileIds(array $selProdIdArr):array
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

    public function getSellerProductShippingRates(array $selProdIdArr, int $countryId, int $stateId):array
    {
        $countryId = FatUtility::int($countryId);
        $stateId = FatUtility::int($stateId);

        $srch = new ProductSearch();
        $srch->setDefinedCriteria(0, 0, array(), false);
        $srch->joinProductShippedBy();
        $srch->joinShippingProfileProducts();
        $srch->joinShippingProfile($this->langId);
        $srch->joinShippingProfileZones();
        $srch->joinShippingZones();
        $srch->joinShippingRates($this->langId);
        $srch->joinShippingLocations($countryId, $stateId);
        $srch->addCondition('selprod_id', 'IN', $selProdIdArr);
        $srch->addMultipleFields(array('selprod_id', 'shippro_shipprofile_id', 'shipprozone_id','shiprate_id', 'coalesce(shipr_l.shiprate_name, shipr.shiprate_identifier) as shiprate_name', 'shiprate_cost', 'shiprate_condition_type', 'shiprate_min_val', 'shiprate_max_val', 'psbs.psbs_user_id', 'product_id', 'shiploc_shipzone_id' ,'if(psbs_user_id > 0 or product_seller_id > 0, 1, 0) as shiippingBySeller', 'shipprofile_default', 'shop_id', 'shipprofile_name'));
        $srch->addCondition('shiprate_id', '!=', 'null');
        $srch->addGroupBy('selprod_id');
        $srch->addGroupBy('shiprate_id');
        $srch->addOrder('shiprate_cost');
        //$srch->addOrder('shiprate_condition_type', 'desc');
        $prodSrchRs = $srch->getResultSet();
        return FatApp::getDb()->fetchAll($prodSrchRs);
    }


    /**
     * calculateCharges
     *
     * @param  array $physicalSelProdIdArr
     * @param  int $shipToCountryId
     * @param  int $shipToStateId
     * @param  array $productInfo
     * @return array
     */
    public function calculateCharges(array $physicalSelProdIdArr, int $shipToCountryId, int $shipToStateId, array $productInfo):array
    {
        $selProdShipRates = $this->getSellerProductShippingRates($physicalSelProdIdArr, $shipToCountryId, $shipToStateId);

        foreach ($selProdShipRates as $rateId => $rates) {
            $product = $productInfo[$rates['selprod_id']];
            $shippedBy = Shipping::BY_ADMIN;
            $shippingLevel = Shipping::LEVEL_PRODUCT;

            if ($rates['shiippingBySeller']) {
                $shippedBy = Shipping::BY_SHOP;
            }
            
            if ($rates['shipprofile_default']) {
                $shippingLevel = Shipping::LEVEL_ORDER;
                if ($rates['shiippingBySeller']) {
                    $shippingLevel = Shipping::LEVEL_SHOP;
                }
            }
            
            $shippingCost = [
                'id' => $rates['shiprate_id'],
                'code' => $rates['selprod_id'],
                'title' => $rates['shiprate_name'],
                'cost' => $rates['shiprate_cost'],
                'shiprate_condition_type' => $rates['shiprate_condition_type'],
                'shiprate_min_val' => $rates['shiprate_condition_type'],
                'shiprate_max_val' => $rates['shiprate_max_val'],
                'shipping_level' => $shippingLevel,
                'shipping_type' => Shipping::TYPE_MANUAL,
                /* 'shipprofile_key' => $rates['shipprofile_id'], */
                'shipping_label' => $rates['shipprofile_name'],
            ];
            unset($physicalSelProdIdArr[$rates['selprod_id']]);
            
            $shippedByArr[$shippingLevel]['products'][$rates['selprod_id']] = $product;
            switch ($shippingLevel) {
                case Shipping::LEVEL_PRODUCT:
                    $shippedByArr[$shippingLevel]['shipping_options'][$rates['selprod_id']][] = $rates;
                   
                    if (isset($shippedByArr[$shippingLevel]['rates'][$rates['selprod_id']][$rates['shiprate_id']]) && $shippedByArr[$shippingLevel]['rates'][$rates['selprod_id']][$rates['shiprate_id']] != null) {
                        $this->setCost($shippedByArr[$shippingLevel]['rates'][$rates['selprod_id']][$rates['shiprate_id']], $shippingCost);
                    }
                    $shippedByArr[$shippingLevel]['rates'][$rates['selprod_id']][$rates['shiprate_id']] = $shippingCost;
                    break;
                case Shipping::LEVEL_ORDER:
                case Shipping::LEVEL_SHOP:
                    $shippedByArr[$shippingLevel]['shipping_options'][$rates['shiprate_id']] = $rates;
                    if (isset($shippedByArr[$shippingLevel]['rates'][$rates['shiprate_id']]) && $shippedByArr[$shippingLevel]['rates'][$rates['shiprate_id']] != null) {
                        $this->setCost($shippedByArr[$shippingLevel]['rates'][$rates['shiprate_id']], $shippingCost);
                    }
                    $shippedByArr[$shippingLevel]['rates'][$rates['shiprate_id']] = $shippingCost;
                    break;
            }
        }

        $this->setCombinedCharges($shippedByArr);

        /*Include Physical products whose shipping rates not defined */
        foreach ($physicalSelProdIdArr as $selProdId) {
            $shippedByArr[Shipping::LEVEL_PRODUCT]['products'][$selProdId] = $productInfo[$selProdId];
            $shippedByArr[Shipping::LEVEL_PRODUCT]['shipping_options'][$selProdId] = [];
            $shippedByArr[Shipping::LEVEL_PRODUCT]['rates'][$selProdId] = [];
        }
       
        return $shippedByArr;
    }
    
    /**
    * setCost
    *
    * @param  array $item
    * @param  array $shippingCost
    */
    public function setCost(array &$item, array &$shippingCost)
    {
        $code = '';
        if (isset($item['code']) && $item['code'] != '') {
            $code = $item['code'];
        }

        if ($code != '') {
            $shippingCost['code'] = $shippingCost['code'] . '_' . $code;
            $arr = array_filter(explode('_', $shippingCost['code']));
            sort($arr);
            $shippingCost['code'] = implode('_', $arr);
        }
    }

    /**
    * setCombinedCharges
    *
    * @param  array $shippedByArr
    * @return array
    */
    public function setCombinedCharges(array &$shippedByArr):array
    {
        $levels = array_keys($shippedByArr);
        $weightUnitsArr = applicationConstants::getWeightUnitsArr($this->langId);

        foreach ($levels as $level) {
            if ($level == Shipping::LEVEL_PRODUCT) {
                foreach ($shippedByArr[$level]['products'] as $selProdId => $product) {
                    $prodCombinedWeight = 0;
                    $prodCombinedPrice = 0;

                    $prodWeight = $product['product_weight'] * $product['quantity'];
                    $productWeightClass = isset($weightUnitsArr[$product['product_weight_unit']]) ? $weightUnitsArr[$product['product_weight_unit']] : '';
                    $productWeightInOunce = static::convertWeightInOunce($product['product_weight'], $productWeightClass);
                    $prodCombinedWeight = $prodCombinedWeight + $productWeightInOunce;
    
                    $prodCombinedPrice = $prodCombinedPrice + ($product['theprice'] * $product['quantity']);
                    $this->filterShippingRates($shippedByArr[$level]['rates'][$selProdId], $prodCombinedWeight, $prodCombinedPrice);
                }
            } else {
                $prodCombinedWeight = 0;
                $prodCombinedPrice = 0;
                foreach ($shippedByArr[$level]['products'] as $product) {
                    $prodWeight = $product['product_weight'] * $product['quantity'];
                    $productWeightClass = isset($weightUnitsArr[$product['product_weight_unit']]) ? $weightUnitsArr[$product['product_weight_unit']] : '';
                    $productWeightInOunce = static::convertWeightInOunce($product['product_weight'], $productWeightClass);
                    $prodCombinedWeight = $prodCombinedWeight + $productWeightInOunce;
    
                    $prodCombinedPrice = $prodCombinedPrice + ($product['theprice'] * $product['quantity']);
                }

                $this->filterShippingRates($shippedByArr[$level]['rates'], $prodCombinedWeight, $prodCombinedPrice);
            }
        }
        return $shippedByArr;
    }

    /**
    * filterShippingRates
    *
    * @param  array $rates
    * @param  float $weight
    * @param  float $price
    * @return array
    */
    public function filterShippingRates(&$rates, float $weight = 0, float $price = 0):array
    {
        $priceOrWeighCondMatched = false;
        $defaultShippingRates = [];
        $priceOrWeightCost = '';
        $priceOrWeightCostId = 0;

        foreach ($rates as $key => $rate) {
            switch ($rate['shiprate_condition_type']) {

                case ShippingRate::CONDITION_TYPE_PRICE:
                    if ($price < $rate['shiprate_min_val'] || $price > $rate['shiprate_max_val']) {
                        unset($rates[$rate['id']]);
                        continue 2;
                    }
                    $priceOrWeighCondMatched = true;
                    break;

                case ShippingRate::CONDITION_TYPE_WEIGHT:
                    if ($weight < $rate['shiprate_min_val'] || $weight > $rate['shiprate_max_val']) {
                        unset($rates[$rate['id']]);
                        continue 2;
                    }
                    $priceOrWeighCondMatched = true;
                    break;

                default:
                    if (true == $priceOrWeighCondMatched) {
                        unset($rates[$rate['id']]);
                        continue 2;
                    }
                    $priceOrWeightCost = $rate['cost'];
                    $priceOrWeightCostId = $rate['id'];
                    $defaultShippingRates[] = $rate['id'];
                break;
            }

            if (in_array($rate['shiprate_condition_type'], [ShippingRate::CONDITION_TYPE_PRICE, ShippingRate::CONDITION_TYPE_WEIGHT])) {
                if ($priceOrWeightCost != '' && $priceOrWeightCost < $rate['cost']) {
                    unset($rates[$priceOrWeightCostId]);
                    $priceOrWeightCost = $rate['cost'];
                    $priceOrWeightCostId = $rate['id'];
                    continue;
                } else {
                    unset($rates[$rate['id']]);
                }
            }

            if (true == $priceOrWeighCondMatched && !empty($defaultShippingRates)) {
                foreach ($defaultShippingRates as $rateId) {
                    unset($rates[$rateId]);
                }
            }
        }
        return $rates;
    }

    /**
    * convertWeightInOunce
    *
    * @param  float $productWeight
    * @param  string $productWeightClass
    * @return float
    */
    public static function convertWeightInOunce(float $productWeight, string $productWeightClass):float
    {
        $coversionRate = 1;
        switch (strtoupper($productWeightClass)) {
            case "KG":
                $coversionRate = "35.274";
                break;
            case "GM":
                $coversionRate = "0.035274";
                break;
            case "PN":
                $coversionRate = "16";
                break;
            case "OU":
                $coversionRate = "1";
                break;
            case "Ltr":
                $coversionRate = "33.814";
                break;
            case "Ml":
                $coversionRate = "0.033814";
                break;
        }

        return $productWeight * $coversionRate;
    }

    /**
    * convertLengthInCenti
    *
    * @param  float $productWeight
    * @param  string $productWeightClass
    * @return float
    */
    public function convertLengthInCenti(float $productWeight, string $productWeightClass):float
    {
        $coversionRate = 1;
        switch (strtoupper($productWeightClass)) {
            case "IN":
                $coversionRate = "2.54";
                break;
            case "MM":
                $coversionRate = "0.1";
                break;
            case "CM":
                $coversionRate = "1";
                break;
        }

        return $productWeight * $coversionRate;
    }
}
