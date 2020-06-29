<?php

class Tax extends MyAppModel
{
    public const DB_TBL = 'tbl_tax_categories';
    public const DB_TBL_PREFIX = 'taxcat_';

    public const DB_TBL_LANG = 'tbl_tax_categories_lang';
    public const DB_TBL_LANG_PREFIX = 'taxcatlang_';

    public const DB_TBL_VALUES = 'tbl_tax_values';
    public const DB_TBL_VALUES_PREFIX = 'taxval_';

    public const DB_TBL_PRODUCT_TO_TAX = 'tbl_product_to_tax';
    public const DB_TBL_PRODUCT_TO_TAX_PREFIX = 'ptt_';

    private const TAX_RATE_CACHE_KEY_NAME = "taxRateCache_";

    private $db;

    public const TYPE_PERCENTAGE = 1;
    public const TYPE_FIXED = 0;

    public const TAX_TYPE_COMBINED = 1;

    public function __construct(int $id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
    }

    /**
    * getFieldTypeArr
    *
    * @param  int $langId
    * @return array
    */
    public static function getFieldTypeArr(int $langId): array
    {
        $langId = FatUtility::int($langId);
        if ($langId == 0) {
            trigger_error(Labels::getLabel('MSG_Language_Id_not_specified.', $langId), E_USER_ERROR);
        }
        $arr = array(
        static::TYPE_PERCENTAGE => Labels::getLabel('LBL_PERCENTAGE', $langId),
        static::TYPE_FIXED => Labels::getLabel('LBL_FIXED', $langId),
        );
        return $arr;
    }

    /**
    * getStructureArr
    *
    * @param  int $langId
    * @return array
    */
    public static function getStructureArr(int $langId): array
    {
        $langId = FatUtility::int($langId);
        if ($langId == 0) {
            trigger_error(Labels::getLabel('MSG_Language_Id_not_specified.', $langId), E_USER_ERROR);
        }
        $arr = array(
        static::STRUCTURE_VAT => Labels::getLabel('LBL_VAT/SINGLE_TAX_SYSTEM', $langId),
        static::STRUCTURE_GST => Labels::getLabel('LBL_GST', $langId),
        static::STRUCTURE_COMBINED => Labels::getLabel('LBL_COMBINED', $langId),
        );
        return $arr;
    }

    /**
    * getSearchObject
    *
    * @return object
    */
    public static function getSearchObject(int $langId = 0, bool $isActive = true): object
    {
        $langId = FatUtility::int($langId);
        $srch = new SearchBase(static::DB_TBL, 't');

        if ($isActive == true) {
            $srch->addCondition('t.' . static::DB_TBL_PREFIX . 'active', '=', applicationConstants::ACTIVE);
        }

        if ($langId > 0) {
            $srch->joinTable(
                static::DB_TBL_LANG,
                'LEFT OUTER JOIN',
                't_l.' . static::DB_TBL_LANG_PREFIX . 'taxcat_id = t.' . static::tblFld('id') . ' and
            t_l.' . static::DB_TBL_LANG_PREFIX . 'lang_id = ' . $langId,
                't_l'
            );
        }
        return $srch;
    }

    /**
    * getSaleTaxCatArr
    *
    * @param  int $langId
    * @param  bool $isActive
    * @return array
    */
    public static function getSaleTaxCatArr(int $langId, bool $isActive = true): array
    {
        $langId = FatUtility::int($langId);

        $srch = static::getSearchObject($langId, $isActive);
        $srch->addCondition('taxcat_deleted', '=', 0);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();

        $activatedTaxServiceId = Tax::getActivatedServiceId();

        $srch->addFld('taxcat_id');
        if ($activatedTaxServiceId) {
            $srch->addFld('concat(IFNULL(taxcat_name,taxcat_identifier), " (",taxcat_code,")")as taxcat_name');
        } else {
            $srch->addFld('IFNULL(taxcat_name,taxcat_identifier)as taxcat_name');
        }
        $srch->addCondition('taxcat_plugin_id', '=', $activatedTaxServiceId);

        $res = $srch->getResultSet();
        if (!$res) {
            return array();
        }
        return FatApp::getDb()->fetchAllAssoc($res);
    }

    /**
    * getTaxCatObjByProductId
    *
    * @param  int $productId
    * @param  int $langId
    * @return object
    */
    public static function getTaxCatObjByProductId(int $productId, int $langId = 0): object
    {
        $srch = static::getSearchObject($langId);
        $srch->joinTable(
            static::DB_TBL_VALUES,
            'LEFT OUTER JOIN',
            'tv.taxval_taxcat_id = t.taxcat_id',
            'tv'
        );
        $srch->joinTable(
            static::DB_TBL_PRODUCT_TO_TAX,
            'LEFT OUTER JOIN',
            'ptt.ptt_taxcat_id = t.taxcat_id',
            'ptt'
        );

        $srch->addCondition('taxcat_deleted', '=', 0);
        $srch->addCondition('ptt_product_id', '=', FatUtility::int($productId));
        return $srch;
    }

    /**
    * getTaxValuesByCatId
    *
    * @param  int $taxcatId
    * @param  int $userId
    * @param  bool $defaultValue
    * @return array
    */
    public function getTaxValuesByCatId(int $taxcatId, int $userId = 0, bool $defaultValue = false): array
    {
        $taxcatId = FatUtility::int($taxcatId);
        $userId = FatUtility::int($userId);

        $srch = new SearchBase(static::DB_TBL_VALUES);
        $srch->addCondition('taxval_taxcat_id', '=', $taxcatId);

        if ($defaultValue) {
            $srch->addOrder('taxval_seller_user_id', 'ASC');
        } else {
            $srch->addOrder('taxval_seller_user_id', 'DESC');
        }

        if ($userId > 0) {
            $cnd = $srch->addCondition('taxval_seller_user_id', '=', $userId);
            $cnd->attachCondition('taxval_seller_user_id', '=', 0, 'OR');
        }

        $srch->setPageSize(1);
        $srch->doNotCalculateRecords();

        $rs = $srch->getResultSet();
        $row = array();

        $row = FatApp::getDb()->fetch($rs);
        if (!empty($row)) {
            return $row;
        }
        return array();
    }

    /**
    * addUpdateProductTaxCat
    *
    * @param  array $data
    * @return bool
    */
    public function addUpdateProductTaxCat(array $data): bool
    {
        if (0 >= Fatutility::int($data['ptt_product_id']) || 0 >= Fatutility::int($data['ptt_taxcat_id']) || 0 >= Fatutility::int($data['ptt_seller_user_id'])) {
            return false;
        }
        if (!FatApp::getDb()->insertFromArray(static::DB_TBL_PRODUCT_TO_TAX, $data, false, array(), $data)) {
            $this->error = FatApp::getDb()->getError();
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
        unset($data['taxcat_id']);
        $assignValues = array(
        'taxcat_identifier' => $data['taxcat_identifier'],
        'taxcat_active' => $data['taxcat_active'],
        'taxcat_deleted' => 0,
        'taxcat_last_updated' => date('Y-m-d H:i:s'),
        'taxcat_code' => array_key_exists('taxcat_code', $data) ? $data['taxcat_code'] : '',
        'taxcat_plugin_id' => array_key_exists('taxcat_plugin_id', $data) ? $data['taxcat_plugin_id'] : 0,
        );

        if ($this->mainTableRecordId > 0) {
            $assignValues['taxcat_id'] = $this->mainTableRecordId;
        }

        $record = new TableRecord(self::DB_TBL);
        $record->assignValues($assignValues);

        if (!$record->addNew(array(), $assignValues)) {
            $this->error = $record->getError();
            return false;
        }

        $this->mainTableRecordId = $record->getId();
        return true;
    }

    /**
    * canRecordMarkDelete
    *
    * @param  int $id
    * @return bool
    */
    public function canRecordMarkDelete(int $id): bool
    {
        $srch = $this->getSearchObject(0, false);
        $srch->addCondition('t.' . static::DB_TBL_PREFIX . 'id', '=', $id);
        $srch->addFld('t.' . static::DB_TBL_PREFIX . 'id');
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        if (!empty($row) && $row[static::DB_TBL_PREFIX . 'id'] == $id) {
            return true;
        }
        return false;
    }

    /**
    * getTaxRates
    *
    * @param  int $productId
    * @param  int $userId
    * @param  int $langId
    * @param  int $userCountry
    * @param  int $userState
    * @return array
    */
    public function getTaxRates(int $productId, int $userId, int $langId, int $userCountry = 0, int $userState = 0): array
    {
        $productId = Fatutility::int($productId);
        $userId = Fatutility::int($userId);
        $langId = Fatutility::int($langId);
        $activatedTaxServiceId = static::getActivatedServiceId();

        $taxRates = array();
        $srch = self::getTaxCatObjByProductId($productId, $langId);
        $srch->addCondition('ptt_product_id', '=', $productId, 'AND');
        if (0 == $activatedTaxServiceId) {
            $srch->joinTable(TaxRuleLocation::DB_TBL, 'LEFT JOIN', 'taxLoc.taxruleloc_taxcat_id = ptt_taxcat_id', 'taxLoc');
            $srch->joinTable(TaxRule::DB_TBL, 'LEFT JOIN', 'taxRule.taxrule_id = taxLoc.taxruleloc_taxrule_id', 'taxRule');

            if ($userCountry > 0) {
                $srch->addCondition('taxruleloc_country_id', '=', $userCountry, 'AND');
            }
            if ($userState > 0) {
                $srch->addDirectCondition('((taxruleloc_type = '. TaxRule::TYPE_INCLUDE_STATES .' AND taxruleloc_state_id= '. $userState .') OR (taxruleloc_type = '. TaxRule::TYPE_ALL_STATES .' AND taxruleloc_state_id = -1) OR (taxruleloc_type = '. TaxRule::TYPE_EXCLUDE_STATES .' AND taxruleloc_state_id != '. $userState .'))', 'AND');
            }
            $srch->addOrder('taxrule_id', 'ASC');
        }

        $res = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($res);
        if (!is_array($row)) {
            return array();
        }
        return array();
    }

    /**
    * formatAddress
    *
    * @param  array $address
    * @param  string $type
    * @return array
    */
    private function formatAddress(array $address, string $type = ''): array
    {
        $postalCode = '';
        $line1 = '';
        $line2 = '';
        $city = '';
        $state = '';
        $stateCode = '';
        $country = '';
        $countryCode ='' ;

        switch (strtolower($type)) {
            case 'order':
                $postalCode = array_key_exists('oua_zip', $address) ? $address['oua_zip'] : $postalCode;
                $line1 = array_key_exists('oua_address1', $address) ? $address['oua_address1'] : $line1;
                $line2 = array_key_exists('oua_address2', $address) ? $address['oua_address2'] : $line2;
                $city = array_key_exists('oua_city', $address) ? $address['oua_city'] : $city;
                $state = array_key_exists('oua_state', $address) ? $address['oua_state'] : $state;
                $country = array_key_exists('oua_country', $address) ? $address['oua_country'] : $country;
                $stateCode = array_key_exists('oua_state_code', $address) ? $address['oua_state_code'] : $stateCode;
                $countryCode = array_key_exists('oua_country_code', $address) ? $address['oua_country_code'] : $countryCode;
                break;
            case 'shop':
                $postalCode = array_key_exists('shop_postalcode', $address) ? $address['shop_postalcode'] : $postalCode;
                $line1 = array_key_exists('shop_address_line_1', $address) ? $address['shop_address_line_1'] : $line1;
                $line2 = array_key_exists('shop_address_line_2', $address) ? $address['shop_address_line_2'] : $line2;
                $city = array_key_exists('shop_city', $address) ? $address['shop_city'] : $city;
                $state = array_key_exists('state_name', $address) ? $address['state_name'] : $state;
                $stateCode = array_key_exists('state_code', $address) ? $address['state_code'] : $stateCode;
                $country = array_key_exists('country', $address) ? $address['country'] : $country;
                $countryCode = array_key_exists('country_code', $address) ? $address['country_code'] : $countryCode;
                break;
            default :
                $postalCode = array_key_exists('ua_zip', $address) ? $address['ua_zip'] : $postalCode;
                $line1 = array_key_exists('ua_address1', $address) ? $address['ua_address1'] : $line1;
                $line2 = array_key_exists('ua_address2', $address) ? $address['ua_address2'] : $line2;
                $city = array_key_exists('city', $address) ? $address['city'] : $city;
                $city = array_key_exists('ua_city', $address) ? $address['ua_city'] : $city;
                $state = array_key_exists('state', $address) ? $address['state'] : $state;
                $state = array_key_exists('state_name', $address) ? $address['state_name'] : $state;
                $stateCode = array_key_exists('state_code', $address) ? $address['state_code'] : $stateCode;
                $country = array_key_exists('country', $address) ? $address['country'] : $country;
                $country = array_key_exists('country_name', $address) ? $address['country_name'] : $country;
                $countryCode = array_key_exists('country_code', $address) ? $address['country_code'] : $countryCode;
                break;
        }

        return [
            'line1' => $line1,
            'line2' => $line2,
            'city' => $city,
            'state' => empty($state) ? $stateCode : $state,
            'stateCode'=>empty($stateCode) ? $state : $stateCode,
            'postalCode' => $postalCode,
            'country' => isset($country) ? $country : $countryCode,
            'countryCode' => $countryCode,
        ];
    }

    /**
    * calculateTaxRates
    *
    * @param int $productId
    * @param float $prodPrice
    * @param int $sellerId
    * @param int $langId
    * @param int $qty
    * @param int $type
    * @param array $extraInfo
    * @param bool $useCache
    * @return array
    */
    public function calculateTaxRates(int $productId, float $prodPrice, int $sellerId, int $langId, int $qty = 1, array $extraInfo = array(), bool $useCache = false): array
    {
        $tax = 0;
        $defaultTaxName = Labels::getLabel('LBL_Tax', $langId);

        $activatedTaxServiceId = static::getActivatedServiceId();

        $shipFromStateId = 0;
        $shipToStateId = 0;
        $shipToCountryId = 0;

        if (isset($extraInfo['shippingAddress']['ua_country_id'])) {
            $shipToCountryId = FatUtility::int($extraInfo['shippingAddress']['ua_country_id']);
        }

        if (isset($extraInfo['shippingAddress']['ua_state_id'])) {
            $shipToStateId = FatUtility::int($extraInfo['shippingAddress']['ua_state_id']);
        }

        if (array_key_exists('shippingAddress', $extraInfo)) {
            $shopInfo = Shop::getAttributesByUserId($sellerId, array('shop_state_id', 'shop_id'));
            $shipFromStateId = $shopInfo['shop_state_id'];
        }

        $taxCategoryRow = $this->getTaxRates($productId, $sellerId, $langId, $shipToCountryId, $shipToStateId);
        if (empty($taxCategoryRow)) {
            return $data = [
                'status' => false,
                'msg' => Labels::getLabel('MSG_INVALID_TAX_CATEGORY', $langId),
                'tax' => $tax,
                'taxCode' => '',
                'options' => []
            ];
        }

        $arr  = [
            'productId' => $productId,
            'prodPrice' => $prodPrice,
            'sellerId' => $sellerId,
            'langId' =>   $langId,
            'qty' => $qty,
            'shipFromStateId' => $shipFromStateId,
            'shipToStateId' => $shipToStateId,
            'extraInfo' => $extraInfo,
            'taxCategoryRow' => $taxCategoryRow
        ];
        $cacheKey = self::TAX_RATE_CACHE_KEY_NAME . md5(json_encode($arr));

        global $taxRatesArr;
        if (0 < $activatedTaxServiceId && !empty($extraInfo) && $extraInfo['shippingAddress'] != '') {

            if (true == $useCache) {
                $rates = FatCache::get('taxCharges' . $cacheKey, CONF_API_REQ_CACHE_TIME, '.txt');
                if ($rates) {
                    return unserialize($rates);
                }
            }

            if (isset($taxRatesArr[$cacheKey]['values'])) {
                return $taxRatesArr[$cacheKey]['values'];
            }

            $pluginKey = Plugin::getAttributesById($activatedTaxServiceId, 'plugin_code');

            $error = '';
            if (false === PluginHelper::includePlugin($pluginKey, 'tax', $error, $langId)) {
                return $data = [
                    'status' => false,
                    'msg' => $error,
                    'tax' => $tax,
                    'taxCode' => $taxCategoryRow['taxcat_code'],
                    'options' => []
                ];
            }

            $toAddress = $this->formatAddress($extraInfo['shippingAddress']);

            if ($extraInfo['shippedBySeller']) {
                /* @todo check to get with seller_address */
                $fields = array('shop_postalcode', 'shop_address_line_1' ,'shop_address_line_2', 'shop_city', 'state_name', 'state_code', 'country_code');
                $address = Shop::getShopAddress($shopInfo['shop_id'], true, $langId, $fields);
                $fromAddress = $this->formatAddress($address, 'shop');
            } else {
                $fromAddress = Admin::getAddress($langId);
                $shipFromStateId = FatApp::getConfig('CONF_STATE', FatUtility::VAR_INT, 0);
            }
            $toAddress = $this->formatAddress($extraInfo['shippingAddress']);

            $itemsArr = [];
            $item = [
                'amount' => $prodPrice,
                'quantity' => $qty,
                'itemCode' => $productId,
                'taxCode' => $taxCategoryRow['taxcat_code'],
            ];
            array_push($itemsArr, $item);

            $shippingItems = [];
            $shippingItem = [
                'amount' => $extraInfo['shippingCost'],
                'quantity' => 1,
                'itemCode' => 'S-' . $productId,
                'taxCode' => $taxCategoryRow['taxcat_code'],
            ];
            array_push($shippingItems, $shippingItem);

            $taxApi = new $pluginKey($langId, $fromAddress, $toAddress);

            $taxRates = $taxApi->getRates($itemsArr, $shippingItems, $extraInfo['buyerId']);

            if (false == $taxRates['status']) {
                //@todo Log Errors
                $data = [
                    'status' => false,
                    'msg' => $taxRates['msg'],
                    'tax' => $tax,
                    'taxCode' => $taxCategoryRow['taxcat_code'],
                    'options' => []
                ];
                $taxRatesArr[$cacheKey]['values'] = $data;
                FatCache::set('taxCharges' . $cacheKey, serialize($data), '.txt');
                return  $data;
            }

            $data = [
                'status' => true,
                'tax' => 0,
                'taxCode' => $taxCategoryRow['taxcat_code'],
                'options' => []
            ];

            if ($taxCategoryRow['taxrule_is_combined'] == static::TAX_TYPE_COMBINED) {
                foreach ($taxRates['data'] as $code => $rate) {
                    $data['tax'] = $data['tax'] + $rate['tax'];
                    foreach ($rate['taxDetails'] as $name => $val) {
                        $data['options'][$name]['name'] = $val['name'];
                        $data['options'][$name]['percentageValue'] = 0;
                        $data['options'][$name]['inPercentage'] = TAX::TYPE_FIXED;
                        if (isset($data['options'][$name]['value'])) {
                            $data['options'][$name]['value'] = $data['options'][$name]['value'] + $val['value'];
                        } else {
                            $data['options'][$name]['value'] = $val['value'];
                        }
                    }
                }
            } else {
                foreach ($taxRates['data'] as $rate) {
                    $data['tax'] = $data['tax'] + $rate['tax'];
                    $data['options'][$defaultTaxName]['name'] = Labels::getLabel('LBL_Tax', $langId);
                    $data['options'][$defaultTaxName]['inPercentage'] = TAX::TYPE_FIXED;
                    $data['options'][$defaultTaxName]['percentageValue'] = 0;
                    if (isset($data['options'][$defaultTaxName]['value'])) {
                        $data['options'][$defaultTaxName]['value'] = $data['options'][$defaultTaxName]['value'] + $rate['tax'];
                    } else {
                        $data['options'][$defaultTaxName]['value'] = $rate['tax'];
                    }
                }
            }
            $taxRatesArr[$cacheKey]['values'] = $data;
            FatCache::set('taxCharges' . $cacheKey, serialize($data), '.txt');

            return $data;
        }

        if ($taxCategoryRow['taxval_is_percent'] == static::TYPE_PERCENTAGE) {
            $tax = round((($prodPrice * $qty) * $taxCategoryRow['taxrule_rate']) / 100, 2);
        } else {
            $tax = $taxCategoryRow['taxrule_rate'] * $qty;
        }


        if (0 < $activatedTaxServiceId) {
            return $data = [
                'status' => true,
                'tax' => $tax,
                'taxCode' => $taxCategoryRow['taxcat_code'],
                'options' => [
                    $defaultTaxName => [
                        'name' => Labels::getLabel('LBL_Tax', $langId),
                        'inPercentage' => $taxCategoryRow['taxval_is_percent'],
                        'percentageValue' => $taxCategoryRow['taxrule_rate'],
                        'value' => $tax
                    ]
                ]
            ];
        }

        $data['tax'] = $tax;
        $data['taxCode'] = $taxCategoryRow['taxcat_code'];

        $srch = TaxRuleCombined::getSearchObject($langId);
        $srch->addCondition('taxruledet_taxrule_id', '=', $taxCategoryRow['taxrule_id']);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $combinedData = FatApp::getDb()->fetchAll($srch->getResultSet());
        if (!empty($combinedData)) {
            foreach ($combinedData as $comData) {
                $data['options'][$comData['taxruledet_id']]['name'] = $comData['taxruledet_name'];
                $data['options'][$comData['taxruledet_id']]['percentageValue'] = $comData['taxruledet_rate'];
                $data['options'][$comData['taxruledet_id']]['inPercentage'] = 1;
                $data['options'][$comData['taxruledet_id']]['value'] = round((($prodPrice * $qty) * $comData['taxruledet_rate']) / 100, 2);
            }
        }

        $data['status'] = true;
        return $data;
    }

    /**
    * createInvoice
    *
    * @param  array $childOrderInfo
    * @return bool
    */
    public function createInvoice(array $childOrderInfo): bool
    {
        $activatedTaxServiceId = static::getActivatedServiceId();
        if (!$activatedTaxServiceId) {
            return true;
        }
        $langId = $childOrderInfo['oplang_lang_id'];
        $pluginKey = Plugin::getAttributesById($activatedTaxServiceId, 'plugin_code');

        $error = '';
        if (false === PluginHelper::includePlugin($pluginKey, 'tax', $error, $langId)) {
            $this->error = $error;
            return false;
        }

        if (0 < $childOrderInfo['opshipping_by_seller_user_id']) {
            /* @todo check to get with seller_address */
            $fields = array('shop_postalcode', 'shop_address_line_1' ,'shop_address_line_2', 'shop_city', 'state_name', 'state_code', 'country_code');
            $address = Shop::getShopAddress($childOrderInfo['op_shop_id'], true, $langId, $fields);
            $fromAddress = $this->formatAddress($address, 'shop');
        } else {
            $fromAddress = Admin::getAddress($langId);
        }

        $orderObj = new Orders();
        $addresses = $orderObj->getOrderAddresses($childOrderInfo['order_id']);

        $toAddress = (!empty($addresses[Orders::SHIPPING_ADDRESS_TYPE])) ? $addresses[Orders::SHIPPING_ADDRESS_TYPE] : $addresses[Orders::BILLING_ADDRESS_TYPE];
        $toAddress = $this->formatAddress($toAddress, 'order');

        $couponDiscount = isset($childOrderInfo['charges'][OrderProduct::CHARGE_TYPE_DISCOUNT]) ? $childOrderInfo['charges'][OrderProduct::CHARGE_TYPE_DISCOUNT]['opcharge_amount'] : 0;
        $volumeDiscount = isset($childOrderInfo['charges'][OrderProduct::CHARGE_TYPE_VOLUME_DISCOUNT]) ? $childOrderInfo['charges'][OrderProduct::CHARGE_TYPE_VOLUME_DISCOUNT]['opcharge_amount'] : 0;
        $rewardPointDiscount = isset($childOrderInfo['charges'][OrderProduct::CHARGE_TYPE_REWARD_POINT_DISCOUNT]) ? $childOrderInfo['charges'][OrderProduct::CHARGE_TYPE_REWARD_POINT_DISCOUNT]['opcharge_amount'] : 0;

        $discount = abs($couponDiscount) + abs($rewardPointDiscount) + abs($volumeDiscount);
        if (0 < $childOrderInfo['op_refund_qty']) {
            $discountPerQauntity = $discount / $childOrderInfo['op_qty'];
            $discount = $discountPerQauntity * ($childOrderInfo['op_qty'] - $childOrderInfo['op_refund_qty']);
        }

        $quantity = $childOrderInfo['op_qty'] - $childOrderInfo['op_refund_qty'];

        $salesTax = isset($childOrderInfo['charges'][OrderProduct::CHARGE_TYPE_TAX]) ? $childOrderInfo['charges'][OrderProduct::CHARGE_TYPE_TAX]['opcharge_amount'] : 0;
        if (0 < $childOrderInfo['op_refund_qty']) {
            $salesTaxPerQuantity = $salesTax / $childOrderInfo['op_qty'];
            $salesTax = $salesTaxPerQuantity * $quantity;
        }

        $shippingAmount = CommonHelper::orderProductAmount($childOrderInfo, 'SHIPPING') - $childOrderInfo['op_refund_shipping'];

        $itemsArr = [];
        $item = [
            'amount' => $childOrderInfo['op_unit_price'],
            'quantity' => $quantity,
            'productName' => $childOrderInfo['op_selprod_title'],
            'description' => $childOrderInfo['op_product_name'],
            'itemCode' => $childOrderInfo['op_id'],
            'taxCode' => $childOrderInfo['op_tax_code'],
            'salesTax' => $salesTax,
            'discount' => abs($discount)
        ];
        array_push($itemsArr, $item);

        $shippingItems = [];
        $shippingItem = [
            'amount' => $shippingAmount,
            'quantity' => 1,
            'itemCode' => 'S-' . $childOrderInfo['op_id'],
            'taxCode' => $childOrderInfo['op_tax_code'],
        ];
        array_push($shippingItems, $shippingItem);

        $taxApi = new $pluginKey($langId, $fromAddress, $toAddress);

        $taxRates = $taxApi->createInvoice($itemsArr, $shippingItems, $childOrderInfo['op_selprod_user_id'], $childOrderInfo['order_date_added'], $childOrderInfo['op_invoice_number']);

        if (false == $taxRates['status']){
            $this->error = $taxRates['msg'];
            return false;
        }

        return true;
    }

    /**
    * getTaxCatByProductId
    *
    * @param  int $productId
    * @param  int $userId
    * @param  int $langId
    * @param  array $fields
    * @return array
    */
    public static function getTaxCatByProductId(int $productId = 0, int $userId = 0, int $langId = 0, array $fields = array()): array
    {
        $taxData = array();
        $taxObj = static::getTaxCatObjByProductId($productId, $langId);
        $taxObj->addCondition('ptt_seller_user_id', '=', $userId);
        if ($fields) {
            $taxObj->addMultipleFields($fields);
        }
        $taxObj->doNotCalculateRecords();
        $taxObj->doNotLimitRecords();
        $rs = $taxObj->getResultSet();
        $taxData = FatApp::getDb()->fetch($rs);
        return $taxData;
    }

    /**
    * removeTaxSetByAdmin
    *
    * @param  int $productId
    * @return bool
    */
    public function removeTaxSetByAdmin(int $productId = 0): bool
    {
        return FatApp::getDb()->deleteRecords(static::DB_TBL_PRODUCT_TO_TAX, array('smt' => 'ptt_seller_user_id = ? and ptt_product_id = ?', 'vals' => array(0, $productId)));
    }

    /**
    * getActivatedServiceId
    *
    * @return int
    */
    public static function getActivatedServiceId(): int
    {
        $defaultTaxApi = FatApp::getConfig('CONF_DEFAULT_PLUGIN_' . Plugin::TYPE_TAX_SERVICES, FatUtility::VAR_INT, 0);
        $defaultTaxApiIsActive = 0;
        if (0 < $defaultTaxApi) {
            $defaultTaxApiIsActive = Plugin::getAttributesById($defaultTaxApi, 'plugin_active');
        }
        if (0 < $defaultTaxApiIsActive) {
            return  $defaultTaxApi;
        }
        return  $defaultTaxApiIsActive;
    }

    /**
    * getAttributesByCode
    *
    * @param  string $code
    * @param  array $attr
    * @param  int $plugInId
    * @return array
    */
    public static function getAttributesByCode(string $code, array $attr = null, int $plugInId = 0): array
    {
        $code = FatUtility::convertToType($code, FatUtility::VAR_STRING);
        $plugInId = FatUtility::convertToType($plugInId, FatUtility::VAR_INT);
        $db = FatApp::getDb();

        $srch = new SearchBase(static::DB_TBL);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addCondition(static::tblFld('code'), '=', $code);
        $srch->addCondition(static::tblFld('plugin_id'), '=', $plugInId);

        if (null != $attr) {
            if (is_array($attr)) {
                $srch->addMultipleFields($attr);
            } elseif (is_string($attr)) {
                $srch->addFld($attr);
            }
        }

        $res = $srch->getResultSet();
        $row = $db->fetch($res);

        if (!is_array($row)) {
            return array();
        }

        if (is_string($attr)) {
            return $row[$attr];
        }

        return $row;
    }
}
