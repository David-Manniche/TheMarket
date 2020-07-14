<?php

class DummyController extends AdminBaseController
{
    public function index()
    {
        $address =  new UserCollection(4, UserCollection::TYPE_SAVED_FOR_LATER);
        $addresses = $address->deleteRecords(4);
        var_dump($addresses);
    }

    public function test123()
    {
        $langId = 1;
        $spreviewId = 1;
        $schObj = new SelProdReviewSearch($langId);
        $schObj->joinUser();
        $schObj->joinProducts($langId);
        $schObj->joinSellerProducts($langId);
        $schObj->addCondition('spreview_id', '=', $spreviewId);
        $schObj->addCondition('spreview_status', '!=', SelProdReview::STATUS_PENDING);
        $schObj->addMultipleFields(array('spreview_selprod_id', 'spreview_status', 'product_name', 'selprod_title', 'user_name', 'credential_email', ));
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
}
