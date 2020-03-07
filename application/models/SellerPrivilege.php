<?php

class SellerPrivilege
{
    public const SECTION_SHOP = 1;
    public const SECTION_PRODUCTS = 2;
    public const SECTION_PRODUCT_TAGS = 3;
    public const SECTION_IMPORT_EXPORT = 4;
    public const SECTION_META_TAGS = 5;
    public const SECTION_URL_REWRITING = 6;
    public const SECTION_SPECIAL_PRICE = 7;
    public const SECTION_VOLUME_DISCOUNT = 8;
    public const SECTION_BUY_TOGETHER_PRODUCTS = 9;
    public const SECTION_RELATED_PRODUCTS = 10;
    public const SECTION_SALES = 11;
    public const SECTION_CANCELLATION_REQUESTS = 12;
    public const SECTION_RETURN_REQUESTS =14;
    public const SECTION_TAX_CATEGORY = 15;
    public const SECTION_PRODUCT_OPTIONS = 16;
    public const SECTION_SOCIAL_PLATFORMS = 17;
    public const SECTION_MESSAGES = 18;
    public const SECTION_CREDITS = 19;
    public const SECTION_SALES_REPORT = 20;
    public const SECTION_PERFORMANCE_REPORT = 21;
    public const SECTION_INVENTORY_REPORT = 22;
    public const SECTION_SELLER_DASHBOARD = 23;
    public const SECTION_SELLER_PERMISSIONS = 24;
    public const SECTION_UPLOAD_BULK_IMAGES = 25;
    public const SECTION_PROMOTIONS = 26;
    public const SECTION_PROMOTION_CHARGES = 27;

    public const PRIVILEGE_NONE = 0;
    public const PRIVILEGE_READ = 1;
    public const PRIVILEGE_WRITE = 2;

    private static $instance = null;
    private $loadedPermissions = array();

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function isUserSuperSeller($sellerId)
    {
        $user = new User($sellerId);
        $srch = $user->getUserSearchObj();
        $rs = $srch->getResultSet();
        $userData = FatApp::getDb()->fetch($rs);
        return ($userData['user_parent'] == 0) ? true : false;
    }

    public static function getPermissionArr($siteLangId)
    {
        $arr = array(
        static::PRIVILEGE_NONE => Labels::getLabel('LBL_None', $siteLangId),
        static::PRIVILEGE_READ => Labels::getLabel('LBL_Read_Only', $siteLangId),
        static::PRIVILEGE_WRITE => Labels::getLabel('LBL_Read_and_Write', $siteLangId)
        );
        return $arr;
    }

    public static function getPermissionModulesArr($siteLangId)
    {
        $arr = array(
            static::SECTION_SELLER_DASHBOARD => Labels::getLabel('LBL_Seller_Dashboard', $siteLangId),
            static::SECTION_SHOP => Labels::getLabel('LBL_Shop', $siteLangId),
            static::SECTION_PRODUCTS => Labels::getLabel('LBL_Products', $siteLangId),
            static::SECTION_PRODUCT_TAGS => Labels::getLabel('LBL_Product_Tags', $siteLangId),
            static::SECTION_IMPORT_EXPORT => Labels::getLabel('LBL_Import_Export', $siteLangId),
            static::SECTION_META_TAGS => Labels::getLabel('LBL_Meta_Tags', $siteLangId),
            static::SECTION_URL_REWRITING => Labels::getLabel('LBL_Url_Rewriting', $siteLangId),
            static::SECTION_SPECIAL_PRICE => Labels::getLabel('LBL_Special_Price', $siteLangId),
            static::SECTION_VOLUME_DISCOUNT => Labels::getLabel('LBL_Volume_Discount', $siteLangId),
            static::SECTION_BUY_TOGETHER_PRODUCTS => Labels::getLabel('LBL_Buy_Together_Products', $siteLangId),
            static::SECTION_RELATED_PRODUCTS => Labels::getLabel('LBL_Related_Products', $siteLangId),
            static::SECTION_SALES => Labels::getLabel('LBL_Sales', $siteLangId),
            static::SECTION_CANCELLATION_REQUESTS => Labels::getLabel('LBL_Cancellation_Requests', $siteLangId),
            static::SECTION_RETURN_REQUESTS => Labels::getLabel('LBL_Return_Requests', $siteLangId),
            static::SECTION_TAX_CATEGORY => Labels::getLabel('LBL_Tax_Category', $siteLangId),
            static::SECTION_PRODUCT_OPTIONS => Labels::getLabel('LBL_Product_Options', $siteLangId),
            static::SECTION_SOCIAL_PLATFORMS => Labels::getLabel('LBL_Manage_Social_Platforms', $siteLangId),
            static::SECTION_MESSAGES => Labels::getLabel('LBL_Messages', $siteLangId),
            static::SECTION_CREDITS => Labels::getLabel('LBL_Credits', $siteLangId),
            static::SECTION_SALES_REPORT => Labels::getLabel('LBL_Sales_Report', $siteLangId),
            static::SECTION_PERFORMANCE_REPORT => Labels::getLabel('LBL_Product_Performance_Report', $siteLangId),
            static::SECTION_INVENTORY_REPORT => Labels::getLabel('LBL_Inventory_Report', $siteLangId),
            static::SECTION_UPLOAD_BULK_IMAGES => Labels::getLabel('LBL_Upload_Bulk_Images', $siteLangId),
            static:: SECTION_PROMOTIONS => Labels::getLabel('LBL_Promotions', $siteLangId),
            static:: SECTION_PROMOTION_CHARGES => Labels::getLabel('LBL_Promotion_Charges', $siteLangId)
        );
        return $arr;
    }

    public static function getModuleSpecificPermissionArr($siteLangId)
    {
        $arr = array(
            Labels::getLabel('LBL_Shop', $siteLangId) =>
                array(
                    static::SECTION_SHOP => Labels::getLabel('LBL_Shop', $siteLangId),
                    static::SECTION_PRODUCTS => Labels::getLabel('LBL_Products', $siteLangId),
                    static::SECTION_PRODUCT_TAGS => Labels::getLabel('LBL_Product_Tags', $siteLangId),
                    static::SECTION_PRODUCT_OPTIONS => Labels::getLabel('LBL_Product_Options', $siteLangId),
                    static::SECTION_TAX_CATEGORY => Labels::getLabel('LBL_Tax_Categories', $siteLangId),
                ),
            Labels::getLabel('LBL_PROMOTIONS', $siteLangId) =>
                array(
                    static::SECTION_SPECIAL_PRICE => Labels::getLabel('LBL_Special_Price', $siteLangId),
                    static::SECTION_VOLUME_DISCOUNT => Labels::getLabel('LBL_Volume_Discount', $siteLangId),
                    static::SECTION_BUY_TOGETHER_PRODUCTS => Labels::getLabel('LBL_Buy_Together_Products', $siteLangId),
                    static::SECTION_RELATED_PRODUCTS => Labels::getLabel('LBL_Related_Products', $siteLangId)
                ),
            Labels::getLabel('LBL_ORDERS', $siteLangId) =>
                array(
                    static::SECTION_SALES => Labels::getLabel('LBL_Sales', $siteLangId),
                    static::SECTION_CANCELLATION_REQUESTS => Labels::getLabel('LBL_Cancellation_Requests', $siteLangId),
                    static::SECTION_RETURN_REQUESTS => Labels::getLabel('LBL_Return_Requests', $siteLangId),
                ),
            Labels::getLabel('LBL_SEO', $siteLangId) =>
                array(
                    static::SECTION_META_TAGS => Labels::getLabel('LBL_Meta_Tags', $siteLangId),
                    static::SECTION_URL_REWRITING => Labels::getLabel('LBL_Url_Rewriting', $siteLangId)
                ),
            Labels::getLabel('LBL_REPORTS', $siteLangId) =>
                array(
                    static::SECTION_SALES_REPORT => Labels::getLabel('LBL_Sales_Report', $siteLangId),
                    static::SECTION_PERFORMANCE_REPORT => Labels::getLabel('LBL_Product_Performance_Report', $siteLangId),
                    static::SECTION_INVENTORY_REPORT => Labels::getLabel('LBL_Inventory_Report', $siteLangId),
                ),
            Labels::getLabel('LBL_ACCOUNT', $siteLangId) =>
                array(
                    static::SECTION_MESSAGES => Labels::getLabel('LBL_Messages', $siteLangId),
                    static::SECTION_CREDITS => Labels::getLabel('LBL_Credits', $siteLangId)
                ),
            Labels::getLabel('LBL_Advertisement', $siteLangId) =>
                array(
                    static::SECTION_PROMOTIONS => Labels::getLabel('LBL_Promotions', $siteLangId),
                    static::SECTION_PROMOTION_CHARGES => Labels::getLabel('LBL_Promotion_Charges', $siteLangId)
                ),
            Labels::getLabel('LBL_IMPORT_EXPORT', $siteLangId) =>
                array(
                    static::SECTION_IMPORT_EXPORT => Labels::getLabel('LBL_Import_Export', $siteLangId),
                ),
            );
        return $arr;
    }

    public static function getWriteOnlyPermissionModulesArr()
    {
        return array(
            static::SECTION_UPLOAD_BULK_IMAGES,
        );
    }

    public static function getSellerPermissionLevel($sellerId, $sectionId = 0)
    {
        $db = FatApp::getDb();
        $sellerId = FatUtility::int($sellerId);

        /* Are you looking for permissions of seller [ */
        if (static::isUserSuperSeller($sellerId)) {
            $arrLevels = array();
            if ($sectionId > 0) {
                $arrLevels[$sectionId] = static::PRIVILEGE_WRITE;
            } else {
                for ($i = 0; $i <= 2; $i++) {
                    $arrLevels [$i] = static::PRIVILEGE_WRITE;
                }
            }
            return $arrLevels;
        }
        /* ] */

        $srch = new SearchBase('tbl_seller_permissions');
        $srch->addCondition('selperm_user_id', '=', $sellerId);
        if (0 < $sectionId) {
            $srch->addCondition('selperm_section_id', '=', $sectionId);
        }

        $srch->addMultipleFields(array('selperm_section_id', 'selperm_value'));
        $rs = $srch->getResultSet();
        $arr = $db->fetchAllAssoc($rs);

        return $arr;
    }

    private function cacheLoadedPermission($sellerId, $secId, $level)
    {
        if (!isset($this->loadedPermissions[$sellerId])) {
            $this->loadedPermissions[$sellerId] = array();
        }
        $this->loadedPermissions[$sellerId][$secId] = $level;
    }

    private function checkPermission($sellerId, $secId, $level, $siteLangId, $returnResult = false)
    {
        $db = FatApp::getDb();

        if (!in_array($level, array(1, 2))) {
            trigger_error(Labels::getLabel('LBL_Invalid_permission_level_checked', $siteLangId) . ' ' . $level, E_USER_ERROR);
        }

        $sellerId = FatUtility::convertToType($sellerId, FatUtility::VAR_INT);
        if (0 == $sellerId) {
            $sellerId = UserAuthentication::getLoggedUserId();
        }

        if (isset($this->loadedPermissions[$sellerId][$secId])) {
            if ($level <= $this->loadedPermissions[$sellerId][$secId]) {
                return true;
            }
            return $this->returnFalseOrDie($returnResult, $siteLangId);
        }

        if ($this->isUserSuperSeller($sellerId)) {
            return true;
        }

        $user = new User($userId);
        $srch = $user->getUserSearchObj();
        $rs = $srch->getResultSet();
        $userData = FatApp::getDb()->fetch($rs);
        if (empty($userData) || $userData['credential_active'] != applicationConstants::ACTIVE) {
            FatUtility::dieWithError(Labels::getLabel('LBL_Invalid_Request', $this->siteLangId));
        }

        $rs = $db->query(
            "SELECT selperm_value FROM tbl_seller_permissions WHERE
				selperm_user_id = " . $sellerId . " AND selperm_section_id = " . $secId
        );
        if (!$row = $db->fetch($rs)) {
            $this->cacheLoadedPermission($sellerId, $secId, static::PRIVILEGE_NONE);
            return $this->returnFalseOrDie($returnResult, $siteLangId);
        }

        $permissionLevel = $row['selperm_value'];

        $this->cacheLoadedPermission($sellerId, $secId, $permissionLevel);

        if ($level > $permissionLevel) {
            return $this->returnFalseOrDie($returnResult, $siteLangId);
        }

        return (true);
    }

    private function returnFalseOrDie($returnResult, $siteLangId, $msg = '')
    {
        if ($returnResult) {
            return (false);
        }
        Message::addErrorMessage(Labels::getLabel('LBL_Unauthorized_Access!', $siteLangId));
        if ($msg == '') {
            $msg = Message::getHtml();
        }
        FatUtility::dieWithError($msg);
    }

    public function clearPermissionCache($sellerId)
    {
        if (isset($this->loadedPermissions[$sellerId])) {
            unset($this->loadedPermissions[$sellerId]);
        }
    }

    public function canViewShop($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_SHOP, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditShop($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_SHOP, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewProducts($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_PRODUCTS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditProducts($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_PRODUCTS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewProductTags($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_PRODUCT_TAGS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditProductTags($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_PRODUCT_TAGS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewImportExport($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_IMPORT_EXPORT, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditImportExport($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_IMPORT_EXPORT, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewMetaTags($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_META_TAGS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditMetaTags($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_META_TAGS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewUrlRewriting($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_URL_REWRITING, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditUrlRewriting($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_URL_REWRITING, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewSpecialPrice($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_SPECIAL_PRICE, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditSpecialPrice($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_SPECIAL_PRICE, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewVolumeDiscount($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_VOLUME_DISCOUNT, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditVolumeDiscount($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_VOLUME_DISCOUNT, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewBuyTogetherProducts($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_BUY_TOGETHER_PRODUCTS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditBuyTogetherProducts($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_BUY_TOGETHER_PRODUCTS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewRelatedProducts($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_RELATED_PRODUCTS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditRelatedProducts($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_RELATED_PRODUCTS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewSales($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_SALES, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditSales($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_SALES, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewCancellationRequests($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_CANCELLATION_REQUESTS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditCancellationRequests($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_CANCELLATION_REQUESTS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewReturnRequests($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_RETURN_REQUESTS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditReturnRequests($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_RETURN_REQUESTS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewTaxCategory($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_TAX_CATEGORY, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditTaxCategory($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_TAX_CATEGORY, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewProductOptions($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_PRODUCT_OPTIONS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditProductOptions($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_PRODUCT_OPTIONS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewSocialPlatforms($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_SOCIAL_PLATFORMS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditSocialPlatforms($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_SOCIAL_PLATFORMS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewMessages($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_MESSAGES, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditMessages($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_MESSAGES, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewCredits($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_CREDITS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditCredits($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_CREDITS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewSalesReport($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_SALES_REPORT, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditSalesReport($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_SALES_REPORT, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewPerformanceReport($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_PERFORMANCE_REPORT, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditPerformanceReport($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_PERFORMANCE_REPORT, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewInventoryReport($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_INVENTORY_REPORT, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditInventoryReport($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_INVENTORY_REPORT, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewSellerDashboard($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_SELLER_DASHBOARD, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditSellerDashboard($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_SELLER_DASHBOARD, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewSellerPermissions($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_SELLER_PERMISSIONS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditSellerPermissions($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_SELLER_PERMISSIONS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewPromotions($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_PROMOTIONS, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditPromotions($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_PROMOTIONS, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canViewPromotionCharges($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_PROMOTION_CHARGES, static::PRIVILEGE_READ, $returnResult);
    }

    public function canEditPromotionCharges($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_PROMOTION_CHARGES, static::PRIVILEGE_WRITE, $returnResult);
    }

    public function canUploadBulkImages($sellerId = 0, $returnResult = false)
    {
        return $this->checkPermission($sellerId, static::SECTION_UPLOAD_BULK_IMAGES, static::PRIVILEGE_WRITE, $returnResult);
    }
}
