<?php
class SellerRequestsController extends SellerBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->userPrivilege->canViewSellerRequests(UserAuthentication::getLoggedUserId());
    }
    
    public function index()
    {
        /* $frmSearch = $this->getSearchForm();
        $this->set("frmSearch", $frmSearch); */
        $this->set('canEdit', $this->userPrivilege->canEditSellerRequests(0, true));
        $this->_template->render();
    }
    
    public function searchProductRequests()
    {
        $userId = UserAuthentication::getLoggedUserId();
        $this->userPrivilege->canViewSellerRequests($userId);
        $this->canAddCustomCatalogProduct();
        $frmSearchCustomCatalogProducts = $this->getCustomCatalogProductsSearchForm();
        $post = $frmSearchCustomCatalogProducts->getFormDataFromArray(FatApp::getPostedData());
        $page = (empty($post['page']) || $post['page'] <= 0) ? 1 : intval($post['page']);
        $pagesize = FatApp::getConfig('CONF_PAGE_SIZE', FatUtility::VAR_INT, 10);

        $srch = ProductRequest::getSearchObject($this->siteLangId);
        
        $userArr = User::getAuthenticUserIds($userId, $this->userParentId);
        $srch->addCondition('preq_user_id', 'in', $userArr);
        $srch->addCondition('preq_deleted', '=', applicationConstants::NO);
        
        $keyword = FatApp::getPostedData('keyword', null, '');
        if (!empty($keyword)) {
            $cnd = $srch->addCondition('preq_content', 'like', '%' . $keyword . '%');
            $cnd->attachCondition('preq_lang_data', 'like', '%' . $keyword . '%');
        }

        $srch->addOrder('preq_added_on', 'DESC');
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);

        $db = FatApp::getDb();
        $rs = $srch->getResultSet();
        $arr_listing = $db->fetchAll($rs);
        $jsonDecodedContent = array();
        foreach ($arr_listing as $key => $row) {
            $content = (!empty($row['preq_content'])) ? json_decode($row['preq_content'], true) : array();
            $langContent = (!empty($row['preq_lang_data'])) ? json_decode($row['preq_lang_data'], true) : array();

            $row = array_merge($row, $content);
            if (!empty($langContent)) {
                $row = array_merge($row, $langContent);
            }

            $arr = array(
                'preq_id' => $row['preq_id'],
                'preq_user_id' => $row['preq_user_id'],
                'preq_added_on' => $row['preq_added_on'],
                'preq_status' => $row['preq_status'],
                'product_identifier' => $row['product_identifier'],
                'product_name' => (!empty($row['product_name'])) ? $row['product_name'] : '',
            );
            $arr_listing[$key] = $arr;
        }
        $this->set('canEdit', $this->userPrivilege->canEditSellerRequests(UserAuthentication::getLoggedUserId(), true));
        $this->set("arr_listing", $arr_listing);
        $this->set('pageCount', $srch->pages());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->set('siteLangId', $this->siteLangId);
        $this->set('statusArr', ProductRequest::getStatusArr($this->siteLangId));
        $this->set('CONF_CUSTOM_PRODUCT_REQUIRE_ADMIN_APPROVAL', FatApp::getConfig("CONF_CUSTOM_PRODUCT_REQUIRE_ADMIN_APPROVAL", FatUtility::VAR_INT, 1));
        unset($post['page']);
        $frmSearchCustomCatalogProducts->fill($post);

        $this->set('frmSearchCatalogProducts', $frmSearchCustomCatalogProducts);
        $this->_template->render(false, false);
    }
	
	private function canAddCustomCatalogProduct($redirect = false)
    {
        if (!$this->isShopActive($this->userParentId, 0, true)) {
            if ($redirect) {
                FatApp::redirectUser(UrlHelper::generateUrl('Seller', 'shop'));
            }
            FatUtility::dieWithError(Labels::getLabel('MSG_Your_shop_is_inactive', $this->siteLangId));
        }

        if (!User::canAddCustomProductAvailableToAllSellers()) {
            if ($redirect) {
                Message::addErrorMessage(Labels::getLabel("MSG_Invalid_Access", $this->siteLangId));
                FatApp::redirectUser(UrlHelper::generateUrl('Seller', 'Packages'));
            }
            FatUtility::dieWithError(Labels::getLabel('MSG_Invalid_Access', $this->siteLangId));
        }

        if (!UserPrivilege::isUserHasValidSubsription($this->userParentId)) {
            if ($redirect) {
                Message::addInfo(Labels::getLabel("MSG_Please_buy_subscription", $this->siteLangId));
                FatApp::redirectUser(UrlHelper::generateUrl('Seller', 'catalog'));
            }
            FatUtility::dieWithError(Labels::getLabel('MSG_Please_buy_subscription', $this->siteLangId));
        }
    }
}
