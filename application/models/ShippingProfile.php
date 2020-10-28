<?php
class ShippingProfile extends MyAppModel
{
    const DB_TBL = 'tbl_shipping_profile';
    const DB_TBL_PREFIX = 'shipprofile_';

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
    }

    public static function getSearchObject($isActive = false)
    {
        $srch = new SearchBase(static::DB_TBL, 'sprofile');
        if ($isActive == true) {
            $srch->addCondition('sprofile.' . static::DB_TBL_PREFIX . 'active', '=', applicationConstants::ACTIVE);
        }
        return $srch;
    }

    public static function getProfileArr($userId, $assoc = true, $isActive = false, $default = false)
    {
        $srch = self::getSearchObject($isActive);
        if (FatApp::getConfig('CONF_SHIPPED_BY_ADMIN_ONLY', FatUtility::VAR_INT, 0)) {
            $srch->addCondition('shipprofile_user_id', '=', 0);
        } else {
            $srch->addCondition('shipprofile_user_id', '=', $userId);
        }
        $srch->addMultipleFields(array('shipprofile_id', 'shipprofile_name'));
        $srch->addOrder('shipprofile_default', 'DESC');
        $srch->addOrder('shipprofile_id', 'ASC');
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();

        if (true == $default) {
            $srch->addCondition('shipprofile_default', '=', applicationConstants::YES);
        }

        if ($assoc) {
            return FatApp::getDb()->fetchAllAssoc($srch->getResultSet());
        } else {
            return FatApp::getDb()->fetchAll($srch->getResultSet(), static::tblFld('id'));
        }
    }

    public static function getShipProfileIdByName($profileName, $userId = 0)
    {
        $srch = self::getSearchObject();
        $srch->addCondition('shipprofile_name', '=', trim($profileName));
        $srch->addCondition('shipprofile_user_id', '=', $userId);
        $srch->addFld('shipprofile_id');
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        if (!empty($row)) {
            return $row['shipprofile_id'];
        }
        return 0;
    }

    public static function getDefaultProfileId($userId)
    {
        $srch = self::getSearchObject();
        $srch->addCondition('shipprofile_user_id', '=', $userId);
        $srch->addCondition('shipprofile_default', '=', 1);
        $srch->addFld('shipprofile_id');
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        if (empty($row)) {
            //return $row['shipprofile_id'];
            /* [ CREATE DEFAULT SHIPPING PROFILE */
            $dataToInsert = array(
                'shipprofile_user_id' => $userId,
                'shipprofile_name' => Labels::getLabel('LBL_ORDER_LEVEL_SHIPPING', CommonHelper::getLangId()),
                'shipprofile_active' => 1,
                'shipprofile_default' => 1
            );

            $shippingProfile = new ShippingProfile();
            $shippingProfile->assignValues($dataToInsert);

            if (!$shippingProfile->save()) {
                Message::addErrorMessage($shippingProfile->getError());
            }
            $shippingProfileId = $shippingProfile->getMainTableRecordId();

            $zoneData = [
                'shipzone_user_id' => $userId,
                'shipzone_active' => applicationConstants::ACTIVE,
                'shipzone_name' => Labels::getLabel('LBL_Standard', CommonHelper::getLangId()) . '-' . $shippingProfileId
            ];
            $shippingZone = new ShippingZone();
            $shippingZone->assignValues($zoneData);
            if (!$shippingZone->save()) {
                Message::addErrorMessage($shippingZone->getError());
            }
            $shipZoneId = $shippingZone->getMainTableRecordId();

            if ($shipZoneId) {
                $location = [
                    'shiploc_zone_id' => -1,
                    'shiploc_country_id' => -1,
                    'shiploc_state_id' => -1,
                    'shiploc_shipzone_id' => $shipZoneId,
                ];
                $shippingZone->updateLocations($location);
            }

            $shipProZoneId = 0;
            if ($shippingProfileId && $shipZoneId) {
                $data = array(
                    'shipprozone_shipprofile_id' => $shippingProfileId,
                    'shipprozone_shipzone_id' => $shipZoneId
                );
                $shippingProfileZone = new ShippingProfileZone();
                $shippingProfileZone->assignValues($data);
                if (!$shippingProfileZone->save($data)) {
                    Message::addErrorMessage($shippingProfileZone->getError());
                }
                $shipProZoneId = $shippingProfileZone->getMainTableRecordId();
            }

            $rates = [
                'shiprate_shipprozone_id' => $shipProZoneId,
                'shiprate_identifier' => Labels::getLabel('LBL_Standard', CommonHelper::getLangId()) . '-' . $shippingProfileId,
                'shiprate_condition_type' => 0,
                'shiprate_min_val' => 0,
                'shiprate_max_val' => 0,
            ];

            $shippingRate = new ShippingRate();
            $shippingRate->assignValues($rates);
            if (!$shippingRate->save()) {
                Message::addErrorMessage($shippingRate->getError());
            }

            if ($shippingProfileId) {
                $srch = new ProductSearch(CommonHelper::getLangId(), null, null, false, false);
                $srch->joinProductShippedBySeller($userId);
                if (User::canAddCustomProduct()) {
                    $srch->addDirectCondition('((product_seller_id = 0 AND product_added_by_admin_id = ' . applicationConstants::YES . ' and psbs.psbs_user_id = ' . $userId . ') OR product_seller_id = ' . $userId . ')');
                } else {
                    $cnd = $srch->addCondition('psbs.psbs_user_id', '=', $userId);
                    $cnd->attachCondition('product_added_by_admin_id', '=', applicationConstants::YES, 'AND');
                }

                $srch->addCondition('product_deleted', '=', applicationConstants::NO);
                if (FatApp::getConfig('CONF_ENABLED_SELLER_CUSTOM_PRODUCT')) {
                    $is_custom_or_catalog = FatApp::getPostedData('type', FatUtility::VAR_INT, -1);
                    if ($is_custom_or_catalog > -1) {
                        if ($is_custom_or_catalog > 0) {
                            $srch->addCondition('product_seller_id', '>', 0);
                        } else {
                            $srch->addCondition('product_seller_id', '=', 0);
                        }
                    }
                }
                $srch->addMultipleFields(array($userId . ' as user_id', $shippingProfileId . ' as shipprofile_id', 'product_id'));
                $srch->doNotCalculateRecords();
                $srch->doNotLimitRecords();
                $srch->addGroupBy('product_id');
                $tmpQry = $srch->getQuery();

                $qry = "INSERT INTO " . ShippingProfileProduct::DB_TBL . " (shippro_user_id, shippro_shipprofile_id, shippro_product_id) SELECT * FROM (" . $tmpQry . ") AS t ON DUPLICATE KEY UPDATE shippro_user_id = t.user_id, shippro_shipprofile_id = t.shipprofile_id, shippro_product_id = t.product_id";

                FatApp::getDb()->query($qry);
            }
            return $shippingProfileId;
            /* ] */
        }
        return $row['shipprofile_id'];
    }
}
