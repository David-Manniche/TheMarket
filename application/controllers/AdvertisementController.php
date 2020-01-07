<?php
class AdvertisementController extends LoggedUserController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function index()
    {
        $obj = new Plugin();
        $keyName = $obj->getDefaultPluginKeyName(Plugin::TYPE_ADVERTISEMENT_FEED_API);
        if (false === $keyName) {
            Message::addMessage($obj->getError());
            FatApp::redirectUser(CommonHelper::generateUrl('Advertisement'));
        }

        $merchantId = User::getUserMeta(UserAuthentication::getLoggedUserId(), $keyName . '_merchantId');
        $this->set('merchantId', $merchantId);
        $this->set('keyName', $keyName);
        $this->_template->render();
    }

    public function searchAdvertisementBatches()
    {
        $userId = UserAuthentication::getLoggedUserId();
        $post = FatApp::getPostedData();
        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        $selProdId = FatApp::getPostedData('selprod_id', FatUtility::VAR_INT, 0);
        $keyword = FatApp::getPostedData('keyword', FatUtility::VAR_STRING, '');

        $srch = SellerProduct::searchSpecialPriceProductsObj($this->siteLangId, $selProdId, $keyword, $userId);
        $srch->setPageNumber($page);

        $db = FatApp::getDb();
        $rs = $srch->getResultSet();
        $arrListing = $db->fetchAll($rs);

        $this->set("arrListing", $arrListing);

        $this->set('page', $page);
        $this->set('pageCount', $srch->pages());
        $this->set('postedData', $post);
        $this->set('recordCount', $srch->recordCount());
        $this->set('pageSize', FatApp::getConfig('CONF_PAGE_SIZE', FatUtility::VAR_INT, 10));
        $this->_template->render(false, false);
    }
}
