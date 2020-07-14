<?php

class DummyController extends AdminBaseController
{
    public function index()
    {
        $address =  new Address(1, $this->adminLangId);
        $addresses = $address->getData(Address::TYPE_USER, 7);
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
