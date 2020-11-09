<?php

class DummyController extends AdminBaseController
{
    public function index()
    {
<<<<<<< Updated upstream
        $countryId = '223';
        $stateId = '2998';
        $langId = 1;

        $srch = ShippingZone::getZoneLocationSearchObject($langId);
        $srch->joinTable(ShippingProfileZone::DB_TBL, 'INNER JOIN', 'spz.shipprozone_shipzone_id = sloc.shiploc_shipzone_id', 'spz');
        $srch->addDirectCondition("(sloc.shiploc_country_id = '-1' or (sloc.shiploc_country_id = '" . $countryId . "' and (sloc.shiploc_state_id = '-1' or sloc.shiploc_state_id = '" . $stateId . "')) )");
        $srch->addFld('spz.*');
       // $srch->addFld('CASE WHEN country_id IS NULL THEN 0 ELSE 1 END');
        $srch->addCondition('spz.shipprozone_shipprofile_id', '=', 4);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        //echo $srch->getQuery();
        
        $obj = clone $srch;
        //$obj->joinTable('(' . $srch->getQuery() . ')', 'INNER JOIN', 'tmp.shipprozone_shipprofile_id = spz.shipprozone_shipprofile_id and sc.country_id > 0', 'tmp');
        $obj->joinTable('(' . $srch->getQuery() . ')', 'INNER JOIN', 'tmp.shipprozone_shipprofile_id = spz.shipprozone_shipprofile_id and sc.country_id > 0', 'tmp');
        $obj->joinTable('(' . $srch->getQuery() . ')', 'INNER JOIN', 'tmp1.shipprozone_shipprofile_id = spz.shipprozone_shipprofile_id and sc.country_id > 0', 'tmp1');
        echo $obj->getQuery();
        /* $obj = clone $srch;
        $obj->addOrder('country_id', 'dsc');
        $obj->setPageSize(1);
        $obj->addCondition('') */

       /*  $obj = clone $srch;
        $obj->addMultipleFields(array('tmp.*'));
        $obj->joinTable('(' . $srch->getQuery() . ')', 'LEFT OUTER JOIN', 'tmp.shipprozone_shipprofile_id = spz.shipprozone_shipprofile_id and (sloc.shiploc_country_id = null or (sloc.shiploc_country_id = tmp.shiploc_country_id))', 'tmp');
        $obj->addGroupBy('spz.shipprozone_shipprofile_id');
        $obj->addOrder('spz.shipprozone_shipprofile_id'); */
        //$obj->addDirectCondition('sloc.shiploc_shipzone_id is null');
        //echo $obj->getQuery();
=======
>>>>>>> Stashed changes
    }

    public function test123()
    {
        $criteria = array('max_price' => true);
        $srch = new ProductSearch();
        $srch->setDefinedCriteria(1, 0, $criteria, true, false);
        $srch->joinProductToCategory();
        $srch->joinSellerSubscription(0, false, true);
        $srch->addSubscriptionValidCondition();
        $srch->addCondition('selprod_deleted', '=', applicationConstants::NO);
        $srch->addMultipleFields(array('product_id', 'selprod_id', 'theprice', 'maxprice', 'IFNULL(splprice_id, 0) as splprice_id'));
        $srch->doNotLimitRecords();
        $srch->doNotCalculateRecords();
        $srch->addGroupBy('product_id');
        if (!empty($shop) && array_key_exists('shop_id', $shop)) {
            $srch->addCondition('shop_id', '=', $shop['shop_id']);
        }

        if (0 < $productId) {
            $srch->addCondition('product_id', '=', $productId);
        }

        $tmpQry = $srch->getQuery();

        $tmpQry = $srch->getQuery();

        echo $qry = "INSERT INTO " . static::DB_PRODUCT_MIN_PRICE . " (pmp_product_id, pmp_selprod_id, pmp_min_price, pmp_splprice_id) SELECT * FROM (" . $tmpQry . ") AS t ON DUPLICATE KEY UPDATE pmp_selprod_id = t.selprod_id, pmp_min_price = t.theprice, pmp_splprice_id = t.splprice_id";

        echo "<br>";
        //FatApp::getDb()->query($qry);
        echo $query = "DELETE m FROM " . static::DB_PRODUCT_MIN_PRICE . " m LEFT OUTER JOIN (" . $tmpQry . ") ON pmp_product_id = selprod_product_id WHERE m.pmp_product_id IS NULL";

        die('dsdsdsdsdsd');

        $langId = 1;
        $spreviewId = 1;
        $schObj = new SelProdReviewSearch($langId);
        $schObj->joinUser();
        $schObj->joinProducts($langId);
        $schObj->joinSellerProducts($langId);
        $schObj->addCondition('spreview_id', '=', $spreviewId);
        $schObj->addCondition('spreview_status', '!=', SelProdReview::STATUS_PENDING);
        $schObj->addMultipleFields(array('spreview_selprod_id', 'spreview_status', 'product_name', 'selprod_title', 'user_name', 'credential_email',));
        $spreviewData = FatApp::getDb()->fetch($schObj->getResultSet());
        $productUrl = UrlHelper::generateFullUrl('Products', 'View', array($spreviewData["spreview_selprod_id"]), CONF_WEBROOT_FRONT_URL);
        echo $prodTitleAnchor = "<a href='" . $productUrl . "'>" . $spreviewData['selprod_title'] . "</a>";
        CommonHelper::printArray($prodTitleAnchor);
        die;
    }

    public function query()
    {
        $query = PaymentMethods::getSearchObject();
        echo $query->getQuery();
    }

    public function buyerEmail()
    {
        $this->_template->render(true, true);
    }
}
