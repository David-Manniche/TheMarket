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
            $srch->addCondition('sprofile.'. static::DB_TBL_PREFIX .'active', '=', applicationConstants::ACTIVE);
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
                
            $spObj = new ShippingProfile();
            $spObj->assignValues($dataToInsert);

            if (!$spObj->save()) {
                Message::addErrorMessage($spObj->getError());
                FatUtility::dieJsonError(Message::getHtml());
            }
            return $spObj->getMainTableRecordId();
            /* ] */
        }
        return $row['shipprofile_id'];
    }
}
