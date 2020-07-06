<?php

class Shipping
{
    public const BY_ADMIN = 1;
    public const BY_SHOP = 2;

    public const LEVEL_ORDER = 1;
    public const LEVEL_SHOP = 2;
    public const LEVEL_PRODUCT = 3;

    public const TYPE_MANUAL = -1;
    private const RATE_CACHE_KEY_NAME = "shipRateCache_";

    private $langId;
    private $pluginKey = '';
    private $pluginId = 0;
    private $successMsg = '';
    private $shippedByArr = [];
    private $shippingApiObj = [];
    private $selProdShipRates = [];
        
    /**
     * __construct
     *
     * @param  int $langId
     * @return void
     */
    public function __construct(int $langId)
    {
        $this->langId = $langId;
    }
    
    /**
     * getShippedByArr
     *
     * @param  int $langId
     * @return array
     */
    public static function getShippedByArr(int $langId): array
    {
        return [
            self::BY_ADMIN => Labels::getLabel('LBL_ADMIN_SHIPPING', $langId),
            self::BY_SHOP => Labels::getLabel('LBL_SHOP_SHIPPING', $langId)
        ];
    }

    /**
     * getLevels
     *
     * @param  int $langId
     * @return array
     */
    public static function getLevels(int $langId): array
    {
        return [
            self::LEVEL_ORDER => Labels::getLabel('LBL_ADMIN_LEVEL_SHIPPING', $langId),
            self::LEVEL_SHOP => Labels::getLabel('LBL_SHOP_LEVEL_SHIPPING', $langId),
            self::LEVEL_PRODUCT => Labels::getLabel('LBL_PRODUCT_LEVEL_SHIPPING', $langId),
        ];
    }
    
    /**
     * getShippingMethods
     *
     * @param  int $langId
     * @return array
     */
    public static function getShippingMethods(int $langId): array
    {
        $thirdPartyApis = Plugin::getDataByType(Plugin::TYPE_SHIPPING_SERVICES, 1, true);
        if (!empty($thirdPartyApis)) {
            return $thirdPartyApis;
        }

        return [
            self::TYPE_MANUAL => Labels::getLabel('LBL_SYSTEM_LEVEL_SHIPPING', $langId)
        ];
    }
       
    /**
     * formatOutput
     *
     * @param  int $status
     * @param  array $data - Output Data
     * @return array
     */
    private function formatOutput(int $status, array $data = []): array
    {
        $status = (null != $status ? $status : applicationConstants::FAILURE);
        if (empty($this->error) && applicationConstants::FAILURE == $status) {
            $this->error = Labels::getLabel('MSG_FAILURE', $this->langId);
        }

        if (empty($this->successMsg) && applicationConstants::SUCCESS == $status) {
            $this->successMsg = Labels::getLabel('MSG_SUCCESS', $this->langId);
        }
        
        $msg = (applicationConstants::FAILURE == $status ? $this->error : $this->successMsg);

        return  [
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ];
    }

    /**
     * getSellerProductShippingProfileIds
     *
     * @param  array $selProdIdArr
     * @return array
     */
    public function getSellerProductShippingProfileIds(array $selProdIdArr): array
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
     * @param  int $countryId
     * @param  int $stateId
     * @return array
     */

    public function getSellerProductShippingRates(array $selProdIdArr, int $countryId, int $stateId): array
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
        $srch->joinShippingLocations($countryId, $stateId, 0);
        $srch->addCondition('selprod_id', 'IN', $selProdIdArr);
        $srch->addMultipleFields(array('selprod_id', 'shippro_shipprofile_id', 'shipprozone_id','shiprate_id', 'coalesce(shipr_l.shiprate_name, shipr.shiprate_identifier) as shiprate_name', 'shiprate_cost', 'shiprate_condition_type', 'shiprate_min_val', 'shiprate_max_val', 'psbs.psbs_user_id', 'product_id', 'shiploc_shipzone_id' ,'if(psbs_user_id > 0 or product_seller_id > 0, 1, 0) as shiippingBySeller', 'shipprofile_default', 'shop_id', 'shipprofile_name', 'shop_postalcode'));
        $srch->addCondition('shiprate_id', '!=', 'null');
        $srch->addGroupBy('selprod_id');
        $srch->addGroupBy('shiprate_id');
        $srch->addOrder('shiprate_cost');
        //$srch->addOrder('shiprate_condition_type', 'desc');
        $prodSrchRs = $srch->getResultSet();
        return FatApp::getDb()->fetchAll($prodSrchRs);
    }
    
    /**
     * fetchShippingRatesFromApi
     *
     * @param  array $shippingAddressDetail
     * @param  array $productInfo
     * @param  array $physicalSelProdIdArr
     * @return bool
     */
    private function fetchShippingRatesFromApi(array $shippingAddressDetail, array $productInfo, array &$physicalSelProdIdArr): bool
    {
        if (1 > $this->getPluginId()) {
            return false;
        }

        $carriers = $this->shippingApiObj->getCarriers();
        $this->shippingApiObj->setAddress($shippingAddressDetail['ua_name'], $shippingAddressDetail['ua_address1'], $shippingAddressDetail['ua_address2'], $shippingAddressDetail['ua_city'], $shippingAddressDetail['state_name'], $shippingAddressDetail['ua_zip'], $shippingAddressDetail['country_code'], $shippingAddressDetail['ua_phone']);
        
        $weightUnitsArr = applicationConstants::getWeightUnitsArr($this->langId);
        $dimensionUnits = ShippingPackage::getUnitTypes($this->langId);
        
        foreach ($this->selProdShipRates as $rateId => $rates) {
            $product = $productInfo[$rates['selprod_id']];
            $shippingLevel = self::LEVEL_PRODUCT;
            $this->shippedByArr[$shippingLevel]['products'][$rates['selprod_id']] = $product;

            $shippedBy = self::BY_ADMIN;
            $fromZipCode = FatApp::getConfig('CONF_ZIP_CODE', FatUtility::VAR_STRING, '');
            if (0 < $rates['shiippingBySeller']) {
                $shippedBy = self::BY_SHOP;
                $fromZipCode = $rates['shop_postalcode'];
            }

            if (empty($fromZipCode)) {
                /*  $user = self::BY_ADMIN == $shippedBy ? Labels::getLabel('MSG_ADMIN', $this->langId) : Labels::getLabel('MSG_SHOP', $this->langId);
                 $error = Labels::getLabel('MSG_UNABLE_TO_LOCATE_{USER}_POSTAL_CODE', $this->langId);
                 $this->error = CommonHelper::replaceStringData($error, ['{USER}' => $user]); */
                continue;
            }
            
            $prodWeight = $product['product_weight'] * $product['quantity'];
            $productWeightClass = isset($weightUnitsArr[$product['product_weight_unit']]) ? $weightUnitsArr[$product['product_weight_unit']] : '';
            $productWeightInOunce = static::convertWeightInOunce($prodWeight, $productWeightClass);
            
            $this->shippingApiObj->setWeight($productWeightInOunce);
            $this->shippingApiObj->setDimensions($product['shippack_length'], $product['shippack_width'], $product['shippack_height'], $dimensionUnits[$product['shippack_units']]);

            $cacheKeyArr = [
                $productWeightInOunce,
                $product['shippack_length'],
                $product['shippack_width'],
                $product['shippack_height'],
                $dimensionUnits[$product['shippack_units']]
            ];
            foreach ($carriers as $carrier) {
                $cacheKeyArr = array_merge($cacheKeyArr, [$carrier['code'], $fromZipCode, $this->langId]);
                $cacheKey = self::RATE_CACHE_KEY_NAME . md5(json_encode($cacheKeyArr));
                $shippingRates = FatCache::get($cacheKey, CONF_API_REQ_CACHE_TIME, '.txt');
                if ($shippingRates) {
                    $shippingRates = unserialize($shippingRates);
                } else {
                    $shippingRates = $this->shippingApiObj->getRates($carrier['code'], $fromZipCode);
                    if (!empty($shippingRates)) {
                        FatCache::set($cacheKey, serialize($shippingRates), '.txt');
                    }
                }

                if (false == $shippingRates || empty($shippingRates)) {
                    continue;
                }

                unset($physicalSelProdIdArr[$rates['selprod_id']]);
               
                foreach ($shippingRates as $key => $value) {
                    $shippingCost = [
                        'id' => $value['serviceCode'],
                        'code' => $rates['selprod_id'],
                        'title' => $value['serviceName'],
                        'cost' => $value['shipmentCost'] + $value['otherCost'],
                        'shiprate_condition_type' => '',
                        'shiprate_min_val' => 0,
                        'shiprate_max_val' => 0,
                        'shipping_level' => $shippingLevel,
                        'shipping_type' => $this->getPluginId(),
                        'carrier_code' => $carrier['code'],
                    ];
                    $this->shippedByArr[$shippingLevel]['rates'][$rates['selprod_id']][$carrier['code'] . '|' . $value['serviceName']] = $shippingCost;
                }
                /*If rates fetched from one shipment carriers then ignore for others */
                if (!empty($this->shippedByArr[$shippingLevel]['rates'][$rates['selprod_id']])) {
                    continue 2;
                }
            }
        }
        return true;
    }

    /**
     * fetchShippingRatesFromSystem
     *
     * @param  array $productInfo
     * @param  array $physicalSelProdIdArr
     * @return bool
     */
    private function fetchShippingRatesFromSystem(array $productInfo, array &$physicalSelProdIdArr): bool
    {
        foreach ($this->selProdShipRates as $rateId => $rates) {
            $product = $productInfo[$rates['selprod_id']];
            $shippedBy = self::BY_ADMIN;
            $shippingLevel = self::LEVEL_PRODUCT;

            if ($rates['shiippingBySeller']) {
                $shippedBy = self::BY_SHOP;
            }
            
            if ($rates['shipprofile_default']) {
                $shippingLevel = self::LEVEL_ORDER;
                if ($rates['shiippingBySeller']) {
                    $shippingLevel = self::LEVEL_SHOP;
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
                'shipping_type' => self::TYPE_MANUAL,
                /* 'shipprofile_key' => $rates['shipprofile_id'], */
                'carrier_code' => $rates['shipprofile_name'],
            ];
            unset($physicalSelProdIdArr[$rates['selprod_id']]);
            
            $this->shippedByArr[$shippingLevel]['products'][$rates['selprod_id']] = $product;

            switch ($shippingLevel) {
                case self::LEVEL_PRODUCT:
                    $this->shippedByArr[$shippingLevel]['shipping_options'][$rates['selprod_id']][] = $rates;
                   
                    if (isset($this->shippedByArr[$shippingLevel]['rates'][$rates['selprod_id']][$rates['shiprate_id']]) && $this->shippedByArr[$shippingLevel]['rates'][$rates['selprod_id']][$rates['shiprate_id']] != null) {
                        $this->setCost($this->shippedByArr[$shippingLevel]['rates'][$rates['selprod_id']][$rates['shiprate_id']], $shippingCost);
                    }
                    $this->shippedByArr[$shippingLevel]['rates'][$rates['selprod_id']][$rates['shiprate_id']] = $shippingCost;
                    break;
                case self::LEVEL_ORDER:
                case self::LEVEL_SHOP:
                    $this->shippedByArr[$shippingLevel]['shipping_options'][$rates['shiprate_id']] = $rates;
                    if (isset($this->shippedByArr[$shippingLevel]['rates'][$rates['shiprate_id']]) && $this->shippedByArr[$shippingLevel]['rates'][$rates['shiprate_id']] != null) {
                        $this->setCost($this->shippedByArr[$shippingLevel]['rates'][$rates['shiprate_id']], $shippingCost);
                    }
                    $this->shippedByArr[$shippingLevel]['rates'][$rates['shiprate_id']] = $shippingCost;
                    break;
            }
        }

        $this->setCombinedCharges();
        return true;
    }

    /**
     * calculateCharges
     *
     * @param  array $physicalSelProdIdArr
     * @param  array $shippingAddressDetail
     * @param  array $productInfo
     * @return array
     */
    public function calculateCharges(array $physicalSelProdIdArr, array $shippingAddressDetail, array $productInfo): array
    {
        if (!empty($shippingAddressDetail)) {
            $shipToCountryId = isset($shippingAddressDetail['ua_country_id']) ? $shippingAddressDetail['ua_country_id'] : 0;
        }

        if (!empty($shippingAddressDetail)) {
            $shipToStateId = isset($shippingAddressDetail['ua_state_id']) ? $shippingAddressDetail['ua_state_id'] : 0;
        }
       
        $this->selProdShipRates = $this->getSellerProductShippingRates($physicalSelProdIdArr, $shipToCountryId, $shipToStateId);
        
        if (false === $this->fetchShippingRatesFromApi($shippingAddressDetail, $productInfo, $physicalSelProdIdArr)) {
            $this->fetchShippingRatesFromSystem($productInfo, $physicalSelProdIdArr);
        }

        /*Include Physical products whose shipping rates not defined */
        foreach ($physicalSelProdIdArr as $selProdId) {
            $this->shippedByArr[self::LEVEL_PRODUCT]['products'][$selProdId] = $productInfo[$selProdId];
            $this->shippedByArr[self::LEVEL_PRODUCT]['shipping_options'][$selProdId] = [];
            $this->shippedByArr[self::LEVEL_PRODUCT]['rates'][$selProdId] = [];
        }
        
        return $this->formatOutput(applicationConstants::SUCCESS, $this->shippedByArr);
    }
    
    /**
     * setCost
     *
     * @param  array $item
     * @param  array $shippingCost
     */
    private function setCost(array &$item, array &$shippingCost): bool
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
        return true;
    }

    /**
     * setCombinedCharges
     *
     * @return bool
     */
    private function setCombinedCharges(): bool
    {
        $levels = array_keys($this->shippedByArr);
        $weightUnitsArr = applicationConstants::getWeightUnitsArr($this->langId);

        foreach ($levels as $level) {
            if ($level == self::LEVEL_PRODUCT) {
                foreach ($this->shippedByArr[$level]['products'] as $selProdId => $product) {
                    $prodCombinedWeight = 0;
                    $prodCombinedPrice = 0;

                    $prodWeight = $product['product_weight'] * $product['quantity'];
                    $productWeightClass = isset($weightUnitsArr[$product['product_weight_unit']]) ? $weightUnitsArr[$product['product_weight_unit']] : '';
                    $productWeightInOunce = static::convertWeightInOunce($prodWeight, $productWeightClass);
                    $prodCombinedWeight = $prodCombinedWeight + $productWeightInOunce;
    
                    $prodCombinedPrice = $prodCombinedPrice + ($product['theprice'] * $product['quantity']);
                    $this->filterShippingRates($this->shippedByArr[$level]['rates'][$selProdId], $prodCombinedWeight, $prodCombinedPrice);
                }
            } else {
                $prodCombinedWeight = 0;
                $prodCombinedPrice = 0;
                foreach ($this->shippedByArr[$level]['products'] as $product) {
                    $prodWeight = $product['product_weight'] * $product['quantity'];
                    $productWeightClass = isset($weightUnitsArr[$product['product_weight_unit']]) ? $weightUnitsArr[$product['product_weight_unit']] : '';
                    $productWeightInOunce = static::convertWeightInOunce($prodWeight, $productWeightClass);
                    $prodCombinedWeight = $prodCombinedWeight + $productWeightInOunce;
    
                    $prodCombinedPrice = $prodCombinedPrice + ($product['theprice'] * $product['quantity']);
                }

                $this->filterShippingRates($this->shippedByArr[$level]['rates'], $prodCombinedWeight, $prodCombinedPrice);
            }
        }
        return true;
    }

    /**
     * filterShippingRates
     *
     * @param  array $rates
     * @param  float $weight
     * @param  float $price
     * @return bool
     */
    private function filterShippingRates(array &$rates, float $weight = 0, float $price = 0): bool
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
        return true;
    }

    /**
     * convertWeightInOunce
     *
     * @param  float $productWeight
     * @param  string $productWeightClass
     * @return float
     */
    public static function convertWeightInOunce(float $productWeight, string $productWeightClass): float
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

        return Fatutility::float($productWeight * $coversionRate);
    }

    /**
     * convertLengthInCenti
     *
     * @param  float $productWeight
     * @param  string $productWeightClass
     * @return float
     */
    public static function convertLengthInCenti(float $productWeight, string $productWeightClass): float
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

        return Fatutility::float($productWeight * $coversionRate);
    }

    /**
     * loadDefaultPluginData
     *
     * @return bool
     */
    private function loadDefaultPluginData(): bool
    {
        $pluginObj = new Plugin();
        $plugindata = $pluginObj->getDefaultPluginData(Plugin::TYPE_SHIPPING_SERVICES);

        if (empty($plugindata) || 1 > $plugindata['plugin_active']) {
            $this->error = Labels::getLabel("MSG_NO_DEFAULT_SHIPPING_PLUGIN_FOUND", $this->langId);
            return false;
        }
        $keyName = $plugindata['plugin_code'];

        $directory = Plugin::getDirectory($plugindata['plugin_type']);
        if (false == $directory) {
            $this->error =  Labels::getLabel('MSG_INVALID_PLUGIN_TYPE', $this->langId);
            return false;
        }

        if (false === PluginHelper::includePlugin($keyName, $directory, $this->error, $this->langId, false)) {
            return false;
        }

        $this->shippingApiObj = new $keyName($this->langId);

        if (false === $this->shippingApiObj->init()) {
            $this->error = $this->shippingApiObj->getError();
            return false;
        }
        
        $this->pluginKey = $keyName;
        $this->pluginId = $plugindata['plugin_id'];
        return true;
    }

    /**
     * getPluginKey
     *
     * @return string
     */
    public function getPluginKey(): string
    {
        if (empty($this->pluginKey)) {
            $this->loadDefaultPluginData();
        }
        return $this->pluginKey;
    }

    /**
     * getPluginId
     *
     * @return int
     */
    public function getPluginId(): int
    {
        if (1 > $this->pluginId) {
            $this->loadDefaultPluginData();
        }
        return $this->pluginId;
    }

    /**
     * formatShippingRates
     *
     * @return array
     */
    public static function formatShippingRates(array $rates, int $langId): array
    {
        $rateOptions = [];
        if (!empty($rates)) {
            $rateOptions[] = Labels::getLabel('MSG_SELECT_SERVICE', $langId);
            foreach ($rates as $key => $value) {
                $code = $value['serviceCode'];
                $price = $value['shipmentCost'] + $value['otherCost'];
                $name = $value['serviceName'];
                $displayPrice = CommonHelper::displayMoneyFormat($price);

                $label = $name . " (" . $displayPrice . " )";
                $rateOptions[$code . "-" . $price] = $label;
            }
        }

        return $rateOptions;
    }
}