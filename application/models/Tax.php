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

    private $db;

    public const TYPE_PERCENTAGE = 1;
    public const TYPE_FIXED = 0;

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
    }

    public static function getFieldTypeArr($langId)
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

    public static function getStructureArr($langId)
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

    public static function getSearchObject($langId = 0, $isActive = true)
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

    public static function getSaleTaxCatArr($langId, $isActive = true)
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

        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetchAllAssoc($rs);

        if (!is_array($row)) {
            return false;
        }
        return $row;
    }

    public static function getTaxCatObjByProductId($productId, $langId = 0)
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

    public function getTaxValuesByCatId($taxcat_id, $userId = 0, $defaultValue = false)
    {
        $taxcat_id = FatUtility::int($taxcat_id);
        $userId = FatUtility::int($userId);

        $srch = new SearchBase(static::DB_TBL_VALUES);
        $srch->addCondition('taxval_taxcat_id', '=', $taxcat_id);

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

    public function addUpdateTaxValues($data = array(), $onDuplicateUpdateData = array())
    {
        if (!FatApp::getDb()->insertFromArray(static::DB_TBL_VALUES, $data, false, array(), $onDuplicateUpdateData)) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }
        return true;
    }

    public function addUpdateProductTaxCat($data)
    {
        if (!FatApp::getDb()->insertFromArray(static::DB_TBL_PRODUCT_TO_TAX, $data, false, array(), $data)) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }
        return true;
    }

    public function addUpdateData($data)
    {
        unset($data['taxcat_id']);
        $assignValues = array(
        'taxcat_identifier' => $data['taxcat_identifier'],
        'taxcat_active' => $data['taxcat_active'],
        'taxcat_deleted' => 0,
        'taxcat_last_updated' => date('Y-m-d H:i:s'),
        'taxcat_code' => $data['taxcat_code'],
        'taxcat_plugin_id' => $data['taxcat_plugin_id'],
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

    public function canRecordMarkDelete($id)
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

    public function getTaxRates($productId, $userId, $langId)
    {
        $productId = Fatutility::int($productId);
        $userId = Fatutility::int($userId);
        $langId = Fatutility::int($langId);

        $taxRates = array();
        $taxObj = self::getTaxCatObjByProductId($productId, $langId);
        $taxObj->addMultipleFields(array('IFNULL(taxcat_name,taxcat_identifier) as taxcat_name', 'taxcat_code', 'ptt_seller_user_id', 'ptt_taxcat_id', 'ptt_product_id', 'taxval_is_percent', 'taxval_value', 'taxval_options'));
        $taxObj->doNotCalculateRecords();
        $taxObj->setPageSize(1);

        $cnd = $taxObj->addCondition('ptt_seller_user_id', '=', 0);
        $cnd->attachCondition('ptt_seller_user_id', '=', $userId, 'OR');

        if (false == static::getActivatedServiceId()) {
            $cnd = $taxObj->addCondition('taxval_seller_user_id', '=', 0);
            $cnd->attachCondition('taxval_seller_user_id', '=', $userId, 'OR');
        }   
        
        if (FatApp::getConfig('CONF_TAX_COLLECTED_BY_SELLER', FatUtility::VAR_INT, 0)) {
            $taxObj->addOrder('taxval_seller_user_id', 'DESC');
            $taxObj->addOrder('ptt_seller_user_id', 'DESC');
        } else {
            $taxObj->addOrder('taxval_seller_user_id', 'ASC');
            $taxObj->addOrder('ptt_seller_user_id', 'ASC');
        }

        $rs = $taxObj->getResultSet();
        return FatApp::getDb()->fetch($rs);
    }

    private function formatAddress($address, $shop = false)
    {
        $postalCode = '';
        $line1 = '';
        $line2 = '';
        $city = '';
        $state = '';
        $stateCode = '';

        if (true == $shop) {           
            $postalCode = array_key_exists('shop_postalcode', $address) ? $address['shop_postalcode'] : $postalCode;
            $line1 = array_key_exists('shop_address_line_1', $address) ? $address['shop_address_line_1'] : $line1;
            $line2 = array_key_exists('shop_address_line_2', $address) ? $address['shop_address_line_2'] : $line2;
            $city = array_key_exists('shop_city', $address) ? $address['shop_city'] : $city;
            $state = array_key_exists('state_name', $address) ? $address['state_name'] : $state;
            $stateCode = array_key_exists('state_code', $address) ? $address['state_code'] : $stateCode;
        } else {           
            $postalCode = array_key_exists('ua_zip', $address) ? $address['ua_zip'] : $postalCode;
            $line1 = array_key_exists('ua_address1', $address) ? $address['ua_address1'] : $line1;
            $line2 = array_key_exists('ua_address2', $address) ? $address['ua_address2'] : $line2;
            $city = array_key_exists('city', $address) ? $address['city'] : $city;
            $city = array_key_exists('ua_city', $address) ? $address['ua_city'] : $city;
            $state = array_key_exists('state', $address) ? $address['state'] : $state;
            $state = array_key_exists('state_name', $address) ? $address['state_name'] : $state;
            $stateCode = array_key_exists('state_code', $address) ? $address['state_code'] : $stateCode;
        }

        return [
            'line1' => $line1,
            'line2' => $line2,
            'city' => $city,
            'state' => empty($state) ? $stateCode : $state,
            'state_code'=>empty($stateCode) ? $state : $stateCode,
            'postalCode' => $postalCode,
            'country' => isset($address['country']) ? $address['country'] : $address['country_code'],
            'country_code' => $address['country_code'],
        ];
    }
    
    public function calculateTaxRates($productId, $prodPrice, $sellerId, $langId, $qty = 1, $extraInfo = array(), $useCache = false)
    {
        $tax = 0;
        $taxCategoryRow = $this->getTaxRates($productId, $sellerId, $langId);
        if (empty($taxCategoryRow)) {
            return $data = [
                'status' => false,
                'msg' => Labels::getLabel('MSG_INVALID_TAX_CATEGORY', $langId),
                'tax' => $tax,
                'options' => []
            ];
        }

        $activatedTaxServiceId = static::getActivatedServiceId();
        $confTaxStructure = FatApp::getConfig('CONF_TAX_STRUCTURE', FatUtility::VAR_FLOAT, 0);

        $shipFromStateId = 0;
        $shipToStateId = 0;
        
        if (isset($extraInfo['shipping_address']['ua_state_id'])) {
            $shipToStateId = FatUtility::int($extraInfo['shipping_address']['ua_state_id']);
        }

        if (array_key_exists('shippingAddress', $extraInfo)) {
            $shopInfo = Shop::getAttributesByUserId($sellerId, array('shop_state_id', 'shop_id'));
            $shipFromStateId = $shopInfo['shop_state_id'];
        }
        global $taxRates;
        if (0 < $activatedTaxServiceId && !empty($extraInfo) && $extraInfo['shippingAddress'] != '') {
            $cacheKey = $langId . '-' . $shipFromStateId . '-' . $shipToStateId . '-' . $productId . '-' . $qty . '-' . $sellerId;
            if (true == $useCache) {
                $rates = FatCache::get('taxCharges' . $cacheKey, CONF_API_REQ_CACHE_TIME, '.txt');
                if ($rates) {
                    return unserialize($rates);
                }
            }
           
            if (isset($taxRates['values'])) {
                return $taxRates['values'];
            }
            
            $pluginKey = Plugin::getAttributesById($activatedTaxServiceId, 'plugin_code');

            $error = '';
            if (false === PluginHelper::includePlugin($pluginKey, 'tax', $langId, $error)) {
                return $data = [
                    'status' => false,
                    'msg' => $error,
                    'tax' => $tax,
                    'options' => []
                ];
            }

            $toAddress = $this->formatAddress($extraInfo['shippingAddress']);
            
            if ($extraInfo['shippedBySeller']) {
                /* @todo check to get with seller_address */
                $address = Shop::getShopAddress($shopInfo['shop_id'], true, $langId);
                $fromAddress = $this->formatAddress($extraInfo['shippingAddress'], true);
            } else {
                $fromAddress = CommonHelper::getAdminAddress($langId);
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

            $taxApi = new $pluginKey($langId);
            
            $taxRates = $taxApi->getRates($fromAddress, $toAddress, $itemsArr, $shippingItems, $extraInfo['buyerId']);
            
            if (false == $taxRates['status']) {
                //@todo Log Errors
                $data = [
                    'status' => false,
                    'msg' => $taxRates['msg'],
                    'tax' => $tax,
                    'options' => []
                ];
                $taxRates['values'] = $data;
                FatCache::set('taxCharges' . $cacheKey, serialize($data), '.txt');
                return  $data;
            }

            $data = [
                'status' => true,
                'tax' => 0,
                'options' => []
            ];
           
            if ($confTaxStructure == TaxStructure::TYPE_COMBINED) {
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
                    $data['options'][-1]['name'] = Labels::getLabel('LBL_Tax', $langId);
                    $data['options'][-1]['inPercentage'] = TAX::TYPE_FIXED;
                    $data['options'][-1]['percentageValue'] = 0;
                    if (isset($data['options'][-1]['value'])) {
                        $data['options'][-1]['value'] = $data['options'][-1]['value'] + $rate['tax'];
                    } else {
                        $data['options'][-1]['value'] = $rate['tax'];
                    }
                }
            }
            $taxRates['values'] = $data;
            FatCache::set('taxCharges' . $cacheKey, serialize($data), '.txt');
            return $data;
        }

        if ($taxCategoryRow['taxval_is_percent'] == static::TYPE_PERCENTAGE) {
            $tax = round((($prodPrice * $qty) * $taxCategoryRow['taxval_value']) / 100, 2);
        } else {
            $tax = $taxCategoryRow['taxval_value'] * $qty;
        }
        

        if (0 < $activatedTaxServiceId) {
            return $data = [
                'status' => true,
                'tax' => $tax,
                'options' => [
                    '-1' => [
                        'name' => Labels::getLabel('LBL_Tax', $langId),
                        'inPercentage' => $taxCategoryRow['taxval_is_percent'],
                        'percentageValue' => $taxCategoryRow['taxval_value'],
                        'value' => $tax
                    ]
                ]
            ];
        }
        
        $data['tax'] = $tax;
             
        if ($confTaxStructure == TaxStructure::TYPE_COMBINED) {
            $shipFromStateId = FatUtility::int($shipFromStateId);
           
            $shipFromStateId = (1 > $shipFromStateId) ? FatApp::getConfig('CONF_STATE', FatUtility::VAR_INT, 0) : $shipFromStateId;

            $taxOptions = json_decode($taxCategoryRow['taxval_options'], true);
            $taxStructure = new TaxStructure($confTaxStructure);
            $options = $taxStructure->getOptions($langId);
            foreach ($options as $optionVal) {
                $taxOptionVal = isset($taxOptions[$optionVal['taxstro_id']]) ? $taxOptions[$optionVal['taxstro_id']] : 0;
                if ($shipFromStateId != $shipToStateId && $optionVal['taxstro_interstate'] == applicationConstants::YES) {
                    $data['options'][$optionVal['taxstro_id']]['name'] = $optionVal['taxstro_name'];
                    $data['options'][$optionVal['taxstro_id']]['percentageValue'] = $taxOptionVal;
                    $data['options'][$optionVal['taxstro_id']]['inPercentage'] = $taxCategoryRow['taxval_is_percent'];
                    if ($taxCategoryRow['taxval_is_percent'] == static::TYPE_PERCENTAGE) {
                        $data['options'][$optionVal['taxstro_id']]['value'] = round((($prodPrice * $qty) * $taxOptionVal) / 100, 2);
                    } else {
                        $data['options'][$optionVal['taxstro_id']][$optionVal['taxstro_name']] = $taxOptionVal * $qty;
                    }
                } elseif ($shipFromStateId == $shipToStateId && $optionVal['taxstro_interstate'] == applicationConstants::NO) {
                    $data['options'][$optionVal['taxstro_id']]['name'] = $optionVal['taxstro_name'];
                    $data['options'][$optionVal['taxstro_id']]['percentageValue'] = $taxOptionVal;
                    $data['options'][$optionVal['taxstro_id']]['inPercentage'] = $taxCategoryRow['taxval_is_percent'];
                    if ($taxCategoryRow['taxval_is_percent'] == static::TYPE_PERCENTAGE) {
                        $data['options'][$optionVal['taxstro_id']]['value'] = round((($prodPrice * $qty) * $taxOptionVal) / 100, 2);
                    } else {
                        $data['options'][$optionVal['taxstro_id']]['value'] = $taxOptionVal * $qty;
                    }
                }
            }
        } else {
            $taxStructure = new TaxStructure($confTaxStructure);
            $structureName = $taxStructure->getName($langId);
            $data['options'][-1]['name'] = Labels::getLabel('LBL_Tax', $langId);
            $data['options'][-1]['inPercentage'] = $taxCategoryRow['taxval_is_percent'];
            $data['options'][-1]['percentageValue'] = $taxCategoryRow['taxval_value'];
            if (array_key_exists('taxstr_name', $structureName) && $structureName['taxstr_name'] != '') {
                $data['options'][-1]['name'] = $structureName['taxstr_name'];
            }
            $data['options'][-1]['value'] = $tax;
        }
        $data['status'] = true;
        return $data;
    }

    public static function getTaxCatByProductId($productId = 0, $userId = 0, $langId = 0, $fields = array())
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

    public function removeTaxSetByAdmin($productId = 0)
    {
        FatApp::getDb()->deleteRecords(static::DB_TBL_PRODUCT_TO_TAX, array('smt' => 'ptt_seller_user_id = ? and ptt_product_id = ?', 'vals' => array(0, $productId)));
    }

    public static function validatePostOptions($langId)
    {
        if (!FatApp::getConfig('CONF_TAX_STRUCTURE', FatUtility::VAR_FLOAT, 0) == TaxStructure::TYPE_COMBINED) {
            return true;
        }

        $taxStructure = new TaxStructure(FatApp::getConfig('CONF_TAX_STRUCTURE', FatUtility::VAR_FLOAT, 0));
        $options = $taxStructure->getOptions($langId);
        $post = FatApp::getPostedData();

        $sameStateSum = 0;
        $interStateSum = 0;

        $havingSameStateValue = false;
        $havingInterStateValue = false;

        foreach ($options as $optionVal) {
            if ($optionVal['taxstro_interstate'] == applicationConstants::YES) {
                $interStateSum += $post[$optionVal['taxstro_id']];
                $havingInterStateValue = true;
            } else {
                $sameStateSum += $post[$optionVal['taxstro_id']];
                $havingSameStateValue = true;
            }
        }

        if ($havingSameStateValue == true && $sameStateSum != $post['taxval_value']) {
            return false;
        }

        if ($havingInterStateValue == true && $interStateSum != $post['taxval_value']) {
            return false;
        }

        return true;
    }

    public static function getActivatedServiceId()
    {
        $defaultTaxApi = FatApp::getConfig('CONF_DEFAULT_PLUGIN_' . Plugin::TYPE_TAX_SERVICES, FatUtility::VAR_INT, 0);
        $defaultTaxApiIsActive = 0;
        if (0 < $defaultTaxApi) {
            $defaultTaxApiIsActive = Plugin::getAttributesById($defaultTaxApi, 'plugin_active');
        }
        return  $defaultTaxApi;
    }

    public static function getAttributesByCode($code, $attr = null, $plugInId = 0)
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

        $rs = $srch->getResultSet();
        $row = $db->fetch($rs);

        if (!is_array($row)) {
            return false;
        }

        if (is_string($attr)) {
            return $row[$attr];
        }

        return $row;
    }
}
