<?php

class ProductsController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewProducts();
    }

    public function index()
    {
        $data = FatApp::getPostedData();
        $srchFrm = $this->getSearchForm();
        if ($data) {
            $data['product_id'] = $data['id'];
            unset($data['id']);
            $srchFrm->fill($data);
        }

        $this->set("frmSearch", $srchFrm);
        $this->_template->addJs('js/jscolor.js');
        $this->_template->addJs('js/import-export.js');
        $this->set('canEdit', $this->objPrivilege->canEditProducts(0, true));
        $this->_template->render();
    }

    public function search()
    {
        $db = FatApp::getDb();
        $srchFrm = $this->getSearchForm();

        $post = $srchFrm->getFormDataFromArray(FatApp::getPostedData());
        $page = (empty($post['page']) || $post['page'] <= 0) ? 1 : intval($post['page']);
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);

        $srch = Product::getSearchObject($this->adminLangId);
        $srch->joinTable(AttributeGroup::DB_TBL, 'LEFT OUTER JOIN', 'product_attrgrp_id = attrgrp_id', 'attrgrp');
        $srch->joinTable(User::DB_TBL, 'LEFT OUTER JOIN', 'product_seller_id = user_id', 'tu');
        $keyword = FatApp::getPostedData('keyword', null, '');
        if (!empty($keyword)) {
            $cnd = $srch->addCondition('product_name', 'like', '%' . $keyword . '%');
            $cnd->attachCondition('product_model', 'like', '%' . $keyword . '%', 'OR');
            $cnd->attachCondition('product_identifier', 'like', '%' . $keyword . '%', 'OR');
        }

        $active = FatApp::getPostedData('active', FatUtility::VAR_INT, -1);
        if ($active > -1) {
            $srch->addCondition('product_active', '=', $active);
        }

        $product_approved = FatApp::getPostedData('product_approved', FatUtility::VAR_INT, -1);
        if ($product_approved > -1) {
            $srch->addCondition('product_approved', '=', $product_approved);
        }

        $product_seller_id = FatApp::getPostedData('product_seller_id', FatUtility::VAR_INT, 0);

        if (FatApp::getConfig('CONF_ENABLED_SELLER_CUSTOM_PRODUCT')) {
            $is_custom_or_catalog = FatApp::getPostedData('is_custom_or_catalog', FatUtility::VAR_INT, -1);
            if ($is_custom_or_catalog > -1) {
                if ($is_custom_or_catalog > 0) {
                    if (0 < $product_seller_id) {
                        $srch->addCondition('product_seller_id', '=', $product_seller_id);
                    } else {
                        $srch->addCondition('product_seller_id', '>', 0);
                    }
                } else {
                    $srch->addCondition('product_seller_id', '=', 0);
                }
            } else {
                if (0 < $product_seller_id) {
                    $srch->addCondition('product_seller_id', '=', $product_seller_id);
                }
            }
        } else {
            if (0 < $product_seller_id) {
                $srch->addCondition('product_seller_id', '=', $product_seller_id);
            }
        }

        $product_attrgrp_id = FatApp::getPostedData('product_attrgrp_id', FatUtility::VAR_INT, -1);
        if ($product_attrgrp_id > -1) {
            $srch->addCondition('product_attrgrp_id', '=', $product_attrgrp_id);
        }

        $prodcat_id = FatApp::getPostedData('prodcat_id', FatUtility::VAR_INT, -1);
        if ($prodcat_id > -1) {
            $srch->joinTable(Product::DB_TBL_PRODUCT_TO_CATEGORY, 'LEFT OUTER JOIN', 'product_id = ptc_product_id', 'ptcat');
            $srch->addCondition('ptcat.ptc_prodcat_id', '=', $prodcat_id);
        }

        $product_type = FatApp::getPostedData('product_type', FatUtility::VAR_INT, 0);
        if ($product_type > 0) {
            $srch->addCondition('product_type', '=', $product_type);
        }

        $date_from = FatApp::getPostedData('date_from', FatUtility::VAR_DATE, '');
        if (!empty($date_from)) {
            $srch->addCondition('tp.product_added_on', '>=', $date_from . ' 00:00:00');
        }

        $date_to = FatApp::getPostedData('date_to', FatUtility::VAR_DATE, '');
        if (!empty($date_to)) {
            $srch->addCondition('tp.product_added_on', '<=', $date_to . ' 23:59:59');
        }

        $product_id = FatApp::getPostedData('product_id', FatUtility::VAR_INT, '');
        if (!empty($product_id)) {
            $srch->addCondition('product_id', '=', $product_id);
        }

        $srch->addMultipleFields(
            array('product_id', 'product_attrgrp_id',
            'product_identifier', 'product_approved', 'product_active', 'product_seller_id', 'product_added_on',
            'product_name', 'attrgrp_name', 'user_name')
        );

        $srch->addOrder('product_added_on', 'DESC');

        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);

        $rs = $srch->getResultSet();

        $arr_listing = $db->fetchAll($rs);

        $this->set("arr_listing", $arr_listing);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->set('canViewUsers', $this->objPrivilege->canViewUsers($this->admin_id, true));
        $this->set('canEdit', $this->objPrivilege->canEditProducts(AdminAuthentication::getLoggedAdminId(), true));
        $this->_template->render(false, false);
    }

    public function productAttributeGroupForm()
    {
        $this->set('productAttributeGroupForm', $this->getProductAttributeGroupForm());
        $this->_template->render(false, false);
    }

    private function getProductAttributeGroupForm()
    {
        $groupsArr = AttributeGroup::getAllNames();
        $frm = new Form('frmProductAttributeGroup');
        $frm->addSelectBox(Labels::getLabel('LBL_Seller_Attribute_Group', $this->adminLangId), 'attrgrp_id', $groupsArr, '', array(), '-None-');
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Next', $this->adminLangId));
        return $frm;
    }

    public function updateProductOption()
    {
        $this->objPrivilege->canEditProducts();
        $post = FatApp::getPostedData();
        if (false === $post) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $product_id = FatUtility::int($post['product_id']);
        $option_id = FatUtility::int($post['option_id']);
        if (!$product_id || !$option_id) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $prodObj = new Product();
        if (!$prodObj->addUpdateProductOption($product_id, $option_id)) {
            Message::addErrorMessage(Labels::getLabel($prodObj->getError(), $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        Product::updateMinPrices($product_id);
        $this->set('msg', Labels::getLabel('LBL_Record_Updated_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function removeProductOption()
    {
        $this->objPrivilege->canEditProducts();
        $post = FatApp::getPostedData();
        if (false === $post) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $product_id = FatUtility::int($post['product_id']);
        $option_id = FatUtility::int($post['option_id']);
        if (!$product_id || !$option_id) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }

        /* Get Linked Products [ */
        $srch = SellerProduct::getSearchObject();
        $srch->joinTable(SellerProduct::DB_TBL_SELLER_PROD_OPTIONS, 'LEFT OUTER JOIN', 'selprod_id = selprodoption_selprod_id', 'tspo');
        $srch->addCondition('selprod_product_id', '=', $product_id);
        $srch->addCondition('tspo.selprodoption_option_id', '=', $option_id);
        $srch->addCondition('selprod_deleted', '=', applicationConstants::NO);
        $srch->addFld(array('selprod_id'));
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        if (!empty($row)) {
            Message::addErrorMessage(Labels::getLabel('LBL_Option_is_linked_with_seller_inventory', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        /* ] */

        $prodObj = new Product();
        if (!$prodObj->removeProductOption($product_id, $option_id)) {
            Message::addErrorMessage(Labels::getLabel($prodObj->getError(), $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        $this->set('msg', Labels::getLabel('MSG_Option_Removed_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function updateProductTag()
    {
        $this->objPrivilege->canEditProducts();
        $productId = FatApp::getPostedData('product_id', FatUtility::VAR_INT, 0);
        $tagId = FatApp::getPostedData('tag_id', FatUtility::VAR_INT, 0);
        if ($productId < 1 || $tagId < 1) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $prod = new Product();
        if (!$prod->addUpdateProductTag($productId, $tagId)) {
            Message::addErrorMessage(Labels::getLabel($prod->getError(), $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }

        Tag::updateProductTagString($productId);

        $this->set('msg', Labels::getLabel('LBL_Record_Updated_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function removeProductTag()
    {
        $this->objPrivilege->canEditProducts();
        $productId = FatApp::getPostedData('product_id', FatUtility::VAR_INT, 0);
        $tagId = FatApp::getPostedData('tag_id', FatUtility::VAR_INT, 0);
        if ($productId < 1 || $tagId < 1) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }

        $prod = new Product();
        if (!$prod->removeProductTag($productId, $tagId)) {
            Message::addErrorMessage(Labels::getLabel($prod->getError(), $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }

        Tag::updateProductTagString($productId);

        $this->set('msg', Labels::getLabel('LBL_Tag_Removed_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function autoComplete()
    {
        $srch = Product::getSearchObject($this->adminLangId);
        $post = FatApp::getPostedData();
        if (!empty($post['keyword'])) {
            $srch->addCondition('product_name', 'LIKE', '%' . $post['keyword'] . '%');
        }
        $srch->setPageSize(FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10));
        $srch->addMultipleFields(array('product_id', 'product_name', 'product_identifier' ));
        $rs = $srch->getResultSet();
        $db = FatApp::getDb();
        $products = $db->fetchAll($rs, 'product_id');
        $json = array();
        foreach ($products as $key => $product) {
            $product['product_name'] = empty($product['product_name']) ? $product['product_identifier'] : $product['product_name'];
            $json[] = array(
            'id' => $key,
            'name' => strip_tags(html_entity_decode($product['product_name'], ENT_QUOTES, 'UTF-8'))
            );
        }
        die(json_encode($json));
    }

    private function getSeparateImageOptions($product_id, $lang_id)
    {
        $imgTypesArr = array( 0 => Labels::getLabel('LBL_For_All_Options', $this->adminLangId) );
        $productOptions = Product::getProductOptions($product_id, $lang_id, true, 1);

        foreach ($productOptions as $val) {
            if (!empty($val['optionValues'])) {
                foreach ($val['optionValues'] as $k => $v) {
                    $option_name = (isset($val['option_name']) && $val['option_name']) ? $val['option_name'] : $val['option_identifier'];
                    $imgTypesArr[$k] = $v;
                }
            }
        }
        return $imgTypesArr;
    }

    public function countries_autocomplete()
    {
        $pagesize = 10;
        $post = FatApp::getPostedData();
        $srch = Countries::getSearchObject(true, $this->adminLangId);
        $srch->addOrder('country_name');

        $srch->addMultipleFields(array('country_id, country_name'));

        if (!empty($post['keyword'])) {
            $cnd = $srch->addCondition('country_name', 'LIKE', '%' . $post['keyword'] . '%');
        }

        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
        $db = FatApp::getDb();

        $countries = $db->fetchAll($rs, 'country_id');
        if (isset($post['includeEverywhere']) && $post['includeEverywhere']) {
            $everyWhereArr = array('country_id' => '-1', 'country_name' => Labels::getLabel('LBL_Everywhere_Else', $this->adminLangId));
            $countries[] = $everyWhereArr;
        }

        $json = array();
        foreach ($countries as $key => $country) {
            $json[] = array(
            'id' => $country['country_id'],
            'name' => strip_tags(html_entity_decode(isset($country['country_name']) ? $country['country_name'] : '', ENT_QUOTES, 'UTF-8')),

            );
        }
        die(json_encode($json));
    }

    public function getShippingTab()
    {
        $post = FatApp::getPostedData();
        $product_id = $post['product_id'];
        $userId = 0;
        if ($product_id) {
            $product = Product::getAttributesById($product_id);
            if ($product['product_seller_id'] > 0) {
                $userId = $product['product_seller_id'];
            }
        }

        $this->set('adminLangId', $this->adminLangId);
        $shipping_rates = array();
        $shipping_rates = Product::getProductShippingRates($product_id, $this->adminLangId, 0, $userId);
        $this->set('adminLangId', $this->adminLangId);
        $this->set('product_id', $product_id);
        $this->set('shipping_rates', $shipping_rates);
        $this->_template->render(false, false);
    }

    public function shippingMethodsAutocomplete()
    {
        $pagesize = 10;
        $post = FatApp::getPostedData();
        $srch = ShippingApi::getSearchObject(true, $this->adminLangId);
        $srch->addOrder('shippingapi_name');

        $srch->addMultipleFields(array('shippingapi_id, shippingapi_name'));

        if (!empty($post['keyword'])) {
            $cnd = $srch->addCondition('shippingapi_name', 'LIKE', '%' . $post['keyword'] . '%');
        }

        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
        $db = FatApp::getDb();

        $shippingMethods = $db->fetchAll($rs, 'shippingapi_id');


        $json = array();
        foreach ($shippingMethods as $key => $sMethod) {
            $json[] = array(
            'id' => $key,
            'name' => strip_tags(html_entity_decode($sMethod['shippingapi_name'], ENT_QUOTES, 'UTF-8')),

            );
        }
        die(json_encode($json));
    }

    public function shippingMethodDurationAutocomplete()
    {
        $pagesize = 10;
        $db = FatApp::getDb();
        $post = FatApp::getPostedData();
        $srch = ShippingDurations::getSearchObject($this->adminLangId, true);
        $srch->addOrder('sduration_name');

        $srch->addMultipleFields(array('sduration_id, IFNULL(sduration_name, sduration_identifier) as sduration_name', 'sduration_from', 'sduration_to', 'sduration_days_or_weeks'));

        if (!empty($post['keyword'])) {
            $srch->addDirectCondition("(sduration_identifier like " . $db->quoteVariable('%' . $post['keyword'] . '%') . " OR sduration_name like " . $db->quoteVariable('%' . $post['keyword'] . '%') . ")");
        }

        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();

        $shipDurations = $db->fetchAll($rs, 'sduration_id');
        $json = array();
        foreach ($shipDurations as $key => $shipDuration) {
            $json[] = array(
            'id' => $key,
            'name' => strip_tags(html_entity_decode($shipDuration['sduration_name'], ENT_QUOTES, 'UTF-8')),
            'duraion' => ShippingDurations::getShippingDurationTitle($shipDuration, $this->adminLangId),

            );
        }
        die(json_encode($json));
    }

    private function getSearchForm()
    {
        $frm = new Form('frmSearch', array('id' => 'frmSearch'));
        $frm->setRequiredStarWith('caption');
        $frm->addTextBox(Labels::getLabel('LBL_Keyword', $this->adminLangId), 'keyword');

        if (FatApp::getConfig('CONF_ENABLED_SELLER_CUSTOM_PRODUCT')) {
            $frm->addSelectBox(Labels::getLabel('LBL_Product', $this->adminLangId), 'is_custom_or_catalog', array( -1 => Labels::getLabel('LBL_All', $this->adminLangId)) + applicationConstants::getCatalogTypeArr($this->adminLangId), -1, array(), '');
        }

        $frm->addTextBox(Labels::getLabel('LBL_User', $this->adminLangId), 'product_seller', '');
        $prodCatObj = new ProductCategory();
        $arrCategories = $prodCatObj->getCategoriesForSelectBox($this->adminLangId);
        $categories = $prodCatObj->makeAssociativeArray($arrCategories);

        $frm->addSelectBox(Labels::getLabel('LBL_category', $this->adminLangId), 'prodcat_id', array( -1 => Labels::getLabel('LBL_Does_not_Matter', $this->adminLangId) ) + $categories, '', array(), '');
        $activeInactiveArr = applicationConstants::getActiveInactiveArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel('LBL_Active', $this->adminLangId), 'active', array( -1 => Labels::getLabel('LBL_Does_not_Matter', $this->adminLangId) ) + $activeInactiveArr, '', array(), '');

        $approveUnApproveArr = Product::getApproveUnApproveArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel('LBL_Approval_Status', $this->adminLangId), 'product_approved', array( -1 => Labels::getLabel('LBL_Does_not_Matter', $this->adminLangId) ) + $approveUnApproveArr, '', array(), '');

        $frm->addSelectBox(Labels::getLabel('LBL_Product_Type', $this->adminLangId), 'product_type', Product::getProductTypes($this->adminLangId), array());

        $frm->addDateField(Labels::getLabel('LBL_Date_From', $this->adminLangId), 'date_from', '', array('readonly' => 'readonly', 'class' => 'small dateTimeFld field--calender' ));
        $frm->addDateField(Labels::getLabel('LBL_Date_To', $this->adminLangId), 'date_to', '', array('readonly' => 'readonly', 'class' => 'small dateTimeFld field--calender'));
        $frm->addHiddenField('', 'page');
        $frm->addHiddenField('', 'product_id');
        $frm->addHiddenField('', 'product_seller_id');
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear_Search', $this->adminLangId), array('onclick' => 'clearSearch();'));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    public function sellerCatalog()
    {
        $srchFrm = $this->getSearchForm();
        $this->set("frmSearch", $srchFrm);
        $this->_template->render();
    }

    public function removeProductShippingRates($product_id, $userId)
    {
        return Product::removeProductShippingRates($product_id, $userId);
    }

    public function addUpdateProductShippingRates($product_id, $data, $userId = 0)
    {
        return Product::addUpdateProductShippingRates($product_id, $data, $userId);
    }

    public function shippingCompanyAutocomplete()
    {
        $pagesize = 10;
        $post = FatApp::getPostedData();

        $srch = ShippingCompanies::getSearchObject(true, $this->adminLangId);
        $srch->addOrder('scompany_name');

        $srch->addMultipleFields(array('scompany_id, scompany_name'));

        if (!empty($post['keyword'])) {
            $cnd = $srch->addCondition('scompany_name', 'LIKE', '%' . $post['keyword'] . '%');
        }

        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
        $db = FatApp::getDb();

        $shippingCompanies = $db->fetchAll($rs, 'scompany_id');


        $json = array();
        foreach ($shippingCompanies as $key => $sCompany) {
            $json[] = array(
            'id' => $key,
            'name' => strip_tags(html_entity_decode($sCompany['scompany_name'], ENT_QUOTES, 'UTF-8')),

            );
        }
        die(json_encode($json));
    }


    public function customProductSpecifications($product_id)
    {
        $this->objPrivilege->canEditProducts();
        $hideListBox = false;
        if (0 < $product_id) {
            $productObj = new Product();
            $data = $productObj->getProductSpecifications($product_id, $this->adminLangId);

            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }

            if (empty($data)) {
                $hideListBox = true;
            }
        }
        $this->set('product_id', $product_id);
        $this->set('hideListBox', $hideListBox);
        $languages = Language::getAllNames();
        $this->set('languages', $languages);
        $this->set('activeTab', 'SPECIFICATIONS');
        $this->set('adminLangId', $this->adminLangId);
        $this->_template->render(false, false);
    }

    public function addUpdateProductSellerShipping($product_id, $data_to_be_save, $userId)
    {
        return Product::addUpdateProductSellerShipping($product_id, $data_to_be_save, $userId);
    }

    public function changeStatus()
    {
        $this->objPrivilege->canEditProducts();
        $productId = FatApp::getPostedData('productId', FatUtility::VAR_INT, 0);
        if (0 >= $productId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }

        $productData = Product::getAttributesById($productId, array('product_active'));
        if (false == $productData) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }

        $status = ($productData['product_active'] == applicationConstants::ACTIVE) ? applicationConstants::INACTIVE : applicationConstants::ACTIVE;

        $this->updateProductStatus($productId, $status);
        Product::updateMinPrices($productId);
        $this->set("msg", $this->str_update_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function toggleBulkStatuses()
    {
        $this->objPrivilege->canEditProducts();

        $status = FatApp::getPostedData('status', FatUtility::VAR_INT, -1);
        $productIdsArr = FatUtility::int(FatApp::getPostedData('product_ids'));
        if (empty($productIdsArr) || -1 == $status) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }

        foreach ($productIdsArr as $productId) {
            if (1 > $productId) {
                continue;
            }

            $this->updateProductStatus($productId, $status);
        }
        $this->set('msg', $this->str_update_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    private function updateProductStatus($productId, $status)
    {
        $status = FatUtility::int($status);
        $productId = FatUtility::int($productId);
        if (1 > $productId || -1 == $status) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }

        $productObj = new Product($productId);

        if (!$productObj->changeStatus($status)) {
            Message::addErrorMessage($productObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
    }

    public function deleteProduct()
    {
        $this->objPrivilege->canEditProducts();
        $productId = FatApp::getPostedData('productId', FatUtility::VAR_INT, 0);
        if (1 > $productId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }

        $this->markAsDeleted($productId);
        Product::updateMinPrices($productId);
        $this->set("msg", $this->str_delete_record);
        FatUtility::dieJsonSuccess($this->str_delete_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function deleteSelected()
    {
        $this->objPrivilege->canEditProducts();
        $productIdsArr = FatUtility::int(FatApp::getPostedData('product_ids'));

        if (empty($productIdsArr)) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }

        foreach ($productIdsArr as $productId) {
            if (1 > $productId) {
                continue;
            }
            $this->markAsDeleted($productId);
        }
        $this->set('msg', $this->str_delete_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    private function markAsDeleted($productId)
    {
        $productId = FatUtility::int($productId);
        if (1 > $productId) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }
        $productObj = new Product($productId);

        if (!$productObj->deleteProduct()) {
            Message::addErrorMessage($productObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
    }

    public function updateUpc($product_id = 0)
    {
        $this->objPrivilege->canEditProducts();
        $product_id = FatUtility::int($product_id);
        if (!$product_id) {
            FatUtility::dieWithError($this->str_invalid_request);
        }

        $post = FatApp::getPostedData();
        if (false === $post || $post['code'] == '') {
            Message::addErrorMessage(Labels::getLabel('MSG_Please_fill_UPC/EAN_code', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        $options = str_replace('|', ',', $post['optionValueId']);

        $srch = UpcCode::getSearchObject();
        $srch->addCondition('upc_product_id', '!=', $product_id);
        $srch->addCondition('upc_code', '=', $post['code']);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);

        if ($row && $row['upc_product_id'] != $product_id) {
            Message::addErrorMessage(Labels::getLabel('MSG_This_UPC/EAN_code_already_assigned_to_another_product', $this->adminLangId));
            FatUtility::dieWithError(Message::getHtml());
        }

        $srch = UpcCode::getSearchObject();
        $srch->addCondition('upc_product_id', '=', $product_id);
        $srch->addCondition('upc_options', '=', $options);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);

        $data = array(
        'upc_code' => $post['code'],
        'upc_product_id' => $product_id,
        'upc_options' => $options,
        );

        if ($row && $row['upc_product_id'] == $product_id && $row['upc_options'] == $options) {
            $upcObj = new UpcCode($row['upc_code_id']);
        } else {
            $upcObj = new UpcCode();
        }

        $upcObj->assignValues($data);
        if (!$upcObj->save()) {
            Message::addErrorMessage($upcObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }

        $this->set('msg', Labels::getLabel('LBL_Record_Updated_Successfully', $this->adminLangId));
        $this->set('product_id', $product_id);
        $this->set('lang_id', FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG', FatUtility::VAR_INT, 1));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function autoCompleteSellerJson()
    {
        $pagesize = applicationConstants::PAGE_SIZE;
        $post = FatApp::getPostedData();
        $srch = User::getSearchObject(true);
        $srch->addCondition('user_is_supplier', '=', applicationConstants::YES);
        $srch->addCondition('credential_active', '=', applicationConstants::ACTIVE);

        $srch->addMultipleFields(array('credential_user_id', 'credential_username', 'credential_email' ));

        if ('' != $post['keyword']) {
            $srch->addCondition('credential_username', 'like', '%' . $post['keyword'] . '%');
            $srch->addCondition('credential_email', 'like', '%' . $post['keyword'] . '%', 'OR');
        }
        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
        $sellers = FatApp::getDb()->fetchAll($rs, 'credential_user_id');

        die(json_encode($sellers));
    }

    public function getTranslatedSpecData()
    {
        $siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
        $prodSpecName = FatApp::getPostedData('prod_spec_name');
        $prodSpecValue = FatApp::getPostedData('prod_spec_value');

        if (empty($prodSpecName) || empty($prodSpecValue)) {
            FatUtility::dieJsonError(Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId));
        }

        $translatedText = $this->translateLangFields(ProdSpecification::DB_TBL_LANG, ['prod_spec_name' => $prodSpecName[$siteDefaultLangId], 'prod_spec_value' => $prodSpecValue[$siteDefaultLangId]]);
        $data = [];
        foreach ($translatedText as $langId => $value) {
            $data[$langId]['prod_spec_name[' . $langId . ']'] = $value['prod_spec_name'];
            $data[$langId]['prod_spec_value[' . $langId . ']'] = $value['prod_spec_value'];
        }
        CommonHelper::jsonEncodeUnicode($data, true);
    }

    public function imagesForm($productId)
    {
        $productId = FatUtility::int($productId);
        if ($productId < 1) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        if (!$row = Product::getAttributesById($productId)) {
            Message::addErrorMessage($this->str_no_record);
            FatUtility::dieWithError(Message::getHtml());
        }
        $imagesFrm = $this->getImagesFrm($productId, $this->adminLangId);
        $this->set('imagesFrm', $imagesFrm);
        $this->_template->render(false, false);
    }

    private function getImagesFrm($productId)
    {
        $imgTypesArr = $this->getSeparateImageOptions($productId, $this->adminLangId);
        $frm = new Form('imageFrm');
        $frm->addSelectBox(Labels::getLabel('LBL_Image_File_Type', $this->adminLangId), 'option_id', $imgTypesArr, 0, array(), '');
        $languagesAssocArr = Language::getAllNames();
        $frm->addSelectBox(Labels::getLabel('LBL_Language', $this->adminLangId), 'lang_id', array( 0 => Labels::getLabel('LBL_All_Languages', $this->adminLangId) ) + $languagesAssocArr, '', array(), '');
        $frm->addHiddenField('', 'min_width', 500);
        $frm->addHiddenField('', 'min_height', 500);
        $frm->addFileUpload(Labels::getLabel('LBL_Upload', $this->adminLangId), 'prod_image', array('id' => 'prod_image'));
        $frm->addHiddenField('', 'product_id', $productId);
        return $frm;
    }

    public function images($productId, $option_id = 0, $lang_id = 0)
    {
        $productId = FatUtility::int($productId);
        if ($productId < 1) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        if (!$row = Product::getAttributesById($productId)) {
            Message::addErrorMessage($this->str_no_record);
            FatUtility::dieWithError(Message::getHtml());
        }
        $productImages = AttachedFile::getMultipleAttachments(AttachedFile::FILETYPE_PRODUCT_IMAGE, $productId, $option_id, $lang_id, false, 0, 0, true);
        $imgTypesArr = $this->getSeparateImageOptions($productId, $this->adminLangId);

        $this->set('images', $productImages);
        $this->set('product_id', $productId);
        $this->set('imgTypesArr', $imgTypesArr);
        $this->set('languages', Language::getAllNames());
        $this->set('canEdit', $this->objPrivilege->canEditProducts(0, true));
        $this->_template->render(false, false);
    }

    public function setImageOrder()
    {
        $this->objPrivilege->canEditProducts();
        $post = FatApp::getPostedData();
        $productId = FatUtility::int($post['product_id']);
        $imageIds = explode('-', $post['ids']);
        $count = 1;
        foreach ($imageIds as $row) {
            $order[$count] = $row;
            $count++;
        }
        $product = new Product();
        if (!$product->updateProdImagesOrder($productId, $order)) {
            Message::addErrorMessage($product->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        $this->set("msg", Labels::getLabel('LBL_Ordered_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function uploadProductImages()
    {
        $this->objPrivilege->canEditProducts();
        $post = FatApp::getPostedData();
        if (empty($post)) {
            Message::addErrorMessage(Labels::getLabel('LBL_Invalid_Request_Or_File_not_supported', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        if (!is_uploaded_file($_FILES['cropped_image']['tmp_name'])) {
            Message::addErrorMessage(Labels::getLabel('LBL_Please_Select_A_File', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $productId = FatUtility::int($post['product_id']);
        $option_id = FatUtility::int($post['option_id']);
        $lang_id = FatUtility::int($post['lang_id']);
        $fileHandlerObj = new AttachedFile();
        if (!$res = $fileHandlerObj->saveImage($_FILES['cropped_image']['tmp_name'], AttachedFile::FILETYPE_PRODUCT_IMAGE, $productId, $option_id, $_FILES['cropped_image']['name'], -1, $unique_record = false, $lang_id)
        ) {
            Message::addErrorMessage($fileHandlerObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        FatApp::getDb()->updateFromArray('tbl_products', array('product_image_updated_on' => date('Y-m-d H:i:s')), array('smt' => 'product_id = ?', 'vals' => array($productId)));

        $this->set("msg", Labels::getLabel('LBL_Image_Uploaded_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function deleteImage($productId, $imageId)
    {
        $this->objPrivilege->canEditProducts();
        $productId = FatUtility :: int($productId);
        $imageId = FatUtility :: int($imageId);
        if ($imageId < 1 || $productId < 1) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $productObj = new Product();
        if (!$productObj->deleteProductImage($productId, $imageId)) {
            Message::addErrorMessage($productObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        FatApp::getDb()->updateFromArray('tbl_products', array('product_image_updated_on' => date('Y-m-d H:i:s')), array('smt' => 'product_id = ?', 'vals' => array($productId)));

        $this->set("msg", Labels::getLabel('LBL_Image_Removed_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function form($productId = 0)
    {
        $this->objPrivilege->canEditProducts();
        $productId = FatUtility::int($productId);
        $productType = Product::getAttributesById($productId, 'product_type');
        $this->set('productId', $productId);
        $this->set('productType', $productType);
        $this->_template->addJs(array('js/cropper.js', 'js/cropper-main.js', 'js/jquery-sortable-lists.js', 'js/tagify.min.js', 'js/tagify.polyfills.min.js'));
        $this->_template->addCss(array('css/cropper.css', 'css/tagify.css'));
        $this->set("includeEditor", true);
        $this->_template->render();
    }

    public function productInitialSetUpFrm($productId)
    {
        $this->objPrivilege->canEditProducts();
        $productId = FatUtility::int($productId);
        $siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
        $languages = Language::getAllNames();
        $productFrm = $this->getProductIntialSetUpFrm($productId);

        if ($productId > 0) {
            $prodData = Product::getAttributesById($productId);
            foreach ($languages as $langId => $data) {
                $prod = new Product();
                $productLangData = $prod->getAttributesByLangId($langId, $productId);
                if (!empty($productLangData)) {
                    $prodData['product_name'][$langId] = $productLangData['product_name'];
                    $prodData['product_youtube_video'][$langId] = $productLangData['product_youtube_video'];
                    $prodData['product_description_' . $langId] = $productLangData['product_description'];
                }
            }

            $taxData = array();
            $tax = Tax::getTaxCatObjByProductId($productId, $this->adminLangId);
            if ($prodData['product_seller_id'] > 0) {
                $tax->addCondition('ptt_seller_user_id', '=', $prodData['product_seller_id']);
            } else {
                $tax->addCondition('ptt_seller_user_id', '=', 0);
            }
            $tax->addMultipleFields(array('ptt_taxcat_id'));
            $tax->doNotCalculateRecords();
            $tax->setPageSize(1);
            $tax->addOrder('ptt_seller_user_id', 'ASC');
            $rs = $tax->getResultSet();
            $taxData = FatApp::getDb()->fetch($rs);
            if (!empty($taxData)) {
                $prodData['ptt_taxcat_id'] = $taxData['ptt_taxcat_id'];
            }

            $srch = Product::getSearchObject($this->adminLangId);
            $srch->joinTable(Brand::DB_TBL, 'LEFT OUTER JOIN', 'tp.product_brand_id = brand.brand_id', 'brand');
            $srch->joinTable(Brand::DB_TBL_LANG, 'LEFT OUTER JOIN', 'brandlang_brand_id = brand.brand_id AND brandlang_lang_id = ' . $this->adminLangId);
            $srch->addMultipleFields(array('product_brand_id', 'IFNULL(brand_name,brand_identifier) as brand_name', 'IFNULL(brand.brand_active,1) AS brand_active', 'IFNULL(brand.brand_deleted,0) AS brand_deleted'));
            $srch->addCondition('product_id', '=', $productId);
            $srch->addHaving('brand_active', '=', applicationConstants::YES);
            $srch->addHaving('brand_deleted', '=', applicationConstants::NO);
            $rs = $srch->getResultSet();
            $brandData = FatApp::getDb()->fetch($rs);
            if (!empty($brandData)) {
                $prodData['product_brand_id'] = $brandData['product_brand_id'];
                $prodData['brand_name'] = $brandData['brand_name'];
            }

            $prod = new Product();
            $productCategories = $prod->getProductCategories($productId);
            if (!empty($productCategories)) {
                $selectedCat = array_keys($productCategories);
                $prodCat = new ProductCategory();
                $selectedCatName = $prodCat->getParentTreeStructure($selectedCat[0], 0, '', $this->adminLangId);
                $prodData['category_name'] = html_entity_decode($selectedCatName);
                $prodData['ptc_prodcat_id'] = $selectedCat[0];
            }

            $productFrm->fill($prodData);
        }

        unset($languages[$siteDefaultLangId]);
        $this->set('productFrm', $productFrm);
        $this->set('siteDefaultLangId', $siteDefaultLangId);
        $this->set('otherLanguages', $languages);
        $this->_template->render(false, false, 'products/product-initial-setup-frm.php');
    }

    private function getProductIntialSetUpFrm($productId)
    {
        $frm = new Form('frmProductIntialSetUp');
        $frm->addRequiredField(Labels::getLabel('LBL_Product_Identifier', $this->adminLangId), 'product_identifier');
        $frm->addSelectBox(Labels::getLabel('LBL_Product_Type', $this->adminLangId), 'product_type', Product::getProductTypes($this->adminLangId), Product::PRODUCT_TYPE_PHYSICAL, array(), '');
        $frm->addRequiredField(Labels::getLabel('LBL_Brand', $this->adminLangId), 'brand_name');
        $frm->addRequiredField(Labels::getLabel('LBL_Category', $this->adminLangId), 'category_name');

        $siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
        $languages = Language::getAllNames();
        foreach ($languages as $langId => $lang) {
            if ($langId == $siteDefaultLangId) {
                $frm->addRequiredField(Labels::getLabel('LBL_Product_Name', $this->adminLangId), 'product_name[' . $langId . ']');
            } else {
                $frm->addTextBox(Labels::getLabel('LBL_Product_Name', $this->adminLangId), 'product_name[' . $langId . ']');
            }
            //$frm->addTextArea(Labels::getLabel('LBL_Description', $this->adminLangId), 'product_description['.$langId.']');
            $frm->addHtmlEditor(Labels::getLabel('LBL_Description', $this->adminLangId), 'product_description_' . $langId);
            $frm->addTextBox(Labels::getLabel('LBL_Youtube_Video_Url', $this->adminLangId), 'product_youtube_video[' . $langId . ']');
        }

        $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
        unset($languages[$siteDefaultLangId]);
        if (!empty($translatorSubscriptionKey) && count($languages) > 0) {
            $frm->addCheckBox(Labels::getLabel('LBL_Translate_For_Other_Languages', $this->adminLangId), 'auto_update_other_langs_data', 1, array(), false, 0);
        }

        $taxCategories = Tax::getSaleTaxCatArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel('LBL_Tax_Category', $this->adminLangId), 'ptt_taxcat_id', $taxCategories, '', array(), Labels::getLabel('LBL_Select', $this->adminLangId))->requirements()->setRequired(true);

        $fldMinSelPrice = $frm->addFloatField(Labels::getLabel('LBL_Minimum_Selling_Price', $this->adminLangId) . ' [' . CommonHelper::getCurrencySymbol(true) . ']', 'product_min_selling_price', '');
        $fldMinSelPrice->requirements()->setPositive();

        $approveUnApproveArr = Product::getApproveUnApproveArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel('LBL_Approval_Status', $this->adminLangId), 'product_approved', $approveUnApproveArr, Product::APPROVED, array(), '');

        $activeInactiveArr = applicationConstants::getActiveInactiveArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel('LBL_Status', $this->adminLangId), 'product_active', $activeInactiveArr, applicationConstants::YES, array(), '');
        $frm->addHiddenField('', 'product_id', $productId);
        $frm->addHiddenField('', 'product_brand_id');
        $frm->addHiddenField('', 'ptc_prodcat_id');
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_And_Next', $this->adminLangId));
        return $frm;
    }

    public function setUpProduct()
    {
        $this->objPrivilege->canEditProducts();
        $productId = FatApp::getPostedData('product_id', FatUtility::VAR_INT, 0);
        $frm = $this->getProductIntialSetUpFrm($productId);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieWithError(Message::getHtml());
        }

        $prod = new Product($productId);
        if (!$prod->saveProductData($post)) {
            Message::addErrorMessage($prod->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        Product::updateMinPrices($productId);

        if (!$prod->saveProductLangData($post)) {
            Message::addErrorMessage($prod->getError());
            FatUtility::dieWithError(Message::getHtml());
        }

        if (!$prod->saveProductCategory($post['ptc_prodcat_id'])) {
            Message::addErrorMessage($prod->getError());
            FatUtility::dieWithError(Message::getHtml());
        }

        if (!$prod->saveProductTax($post['ptt_taxcat_id'])) {
            Message::addErrorMessage($prod->getError());
            FatUtility::dieWithError(Message::getHtml());
        }

        $this->set('msg', Labels::getLabel('LBL_Product_Setup_Successful', $this->adminLangId));
        $this->set('productId', $prod->getMainTableRecordId());
        $this->set('productType', $post['product_type']);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function productAttributeAndSpecificationsFrm($productId)
    {
        $this->objPrivilege->canEditProducts();
        $productId = FatUtility::int($productId);
        if ($productId < 1) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $productFrm = $this->getProductAttributeAndSpecificationsFrm($productId);

        $productData = Product::getAttributesById($productId);
        $prodShippingDetails = Product::getProductShippingDetails($productId, $this->adminLangId, $productData['product_seller_id']);
        $productData['ps_free'] = $prodShippingDetails['ps_free'];
        if ($productData['product_seller_id'] > 0) {
            $userShopName = User::getUserShopName($productData['product_seller_id']);
            $productData['selprod_user_shop_name'] = $userShopName['user_name'] . ' - ' . $userShopName['shop_identifier'];
        } else {
            $productData['selprod_user_shop_name'] = 'Admin';
        }
        $prodSpecificsDetails = Product::getProductSpecificsDetails($productId);
        $productData['product_warranty'] = $prodSpecificsDetails['product_warranty'];
        $productFrm->fill($productData);

        $totalProducts = Product::getCatalogProductCount($productId);
        $siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
        $languages = Language::getAllNames();
        unset($languages[$siteDefaultLangId]);

        $this->set('productFrm', $productFrm);
        $this->set('productData', $productData);
        $this->set('totalProducts', $totalProducts);
        $this->set('siteDefaultLangId', $siteDefaultLangId);
        $this->set('otherLanguages', $languages);
        $this->_template->render(false, false, 'products/product-attribute-and-specifications-frm.php');
    }

    private function getProductAttributeAndSpecificationsFrm($productId)
    {
        $frm = new Form('frmProductAttributeAndSpecifications');
        $frm->addTextBox(Labels::getLabel('LBL_User', $this->adminLangId), 'selprod_user_shop_name');
        $fldModel = $frm->addTextBox(Labels::getLabel('LBL_Model', $this->adminLangId), 'product_model');
        if (FatApp::getConfig("CONF_PRODUCT_MODEL_MANDATORY", FatUtility::VAR_INT, 1)) {
            $fldModel->requirements()->setRequired();
        }
        $warrantyFld = $frm->addRequiredField(Labels::getLabel('LBL_PRODUCT_WARRANTY', $this->adminLangId), 'product_warranty');
        $warrantyFld->requirements()->setInt();
        $warrantyFld->requirements()->setPositive();
        $frm->addCheckBox(Labels::getLabel('LBL_Mark_This_Product_As_Featured?', $this->adminLangId), 'product_featured', 1, array(), false, 0);

        $productType = Product::getAttributesById($productId, 'product_type');
        if ($productType == Product::PRODUCT_TYPE_PHYSICAL) {
            $frm->addCheckBox(Labels::getLabel('LBL_Product_Is_Eligible_For_Free_Shipping?', $this->adminLangId), 'ps_free', 1, array(), false, 0);
            $codFld = $frm->addCheckBox(Labels::getLabel('LBL_Product_Is_Available_for_Cash_on_Delivery_(COD)?', $this->adminLangId), 'product_cod_enabled', 1, array(), false, 0);
            $paymentMethod = new PaymentMethods();
            if (!$paymentMethod->cashOnDeliveryIsActive()) {
                $codFld->addFieldTagAttribute('disabled', 'disabled');
                $codFld->htmlAfterField = '<br/><small>' . Labels::getLabel('LBL_COD_option_is_disabled_in_payment_gateway_settings', $this->adminLangId) . '</small>';
            }
        }
        $frm->addHiddenField('', 'product_seller_id');
        $frm->addHiddenField('', 'product_id', $productId);
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_And_Next', $this->adminLangId));
        return $frm;
    }

    public function setUpProductAttributes()
    {
        $this->objPrivilege->canEditProducts();
        $productId = FatApp::getPostedData('product_id', FatUtility::VAR_INT, 0);
        $frm = $this->getProductAttributeAndSpecificationsFrm($productId);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieWithError(Message::getHtml());
        }

        $prod = new Product($productId);
        if (!$prod->saveProductData($post)) {
            Message::addErrorMessage($prod->getError());
            FatUtility::dieWithError(Message::getHtml());
        }

        $post['ps_product_id'] = $productId;
        $productSpecifics = new ProductSpecifics($productId);
        $productSpecifics->assignValues($post);
        $data = $productSpecifics->getFlds();
        if (!$productSpecifics->addNew(array(), $data)) {
            Message::addErrorMessage($productSpecifics->getError());
            FatUtility::dieWithError(Message::getHtml());
        }

        $productType = Product::getAttributesById($productId, 'product_type');
        if ($productType == Product::PRODUCT_TYPE_PHYSICAL) {
            $psFree = isset($post['ps_free']) ? $post['ps_free'] : 0;
            $psFromCountryId = 0;
            $prodShippingDetails = Product::getProductShippingDetails($productId, $this->adminLangId, $post['product_seller_id']);
            if (!empty($prodShippingDetails)) {
                $psFromCountryId = $prodShippingDetails['ps_from_country_id'];
            }
            if (!$prod->saveProductSellerShipping($post['product_seller_id'], $psFree, $psFromCountryId)) {
                Message::addErrorMessage($prod->getError());
                FatUtility::dieWithError(Message::getHtml());
            }
        }

        if ($post['product_seller_id'] > 0) {
            $taxData = Tax::getTaxCatByProductId($productId);
            $prod->saveProductTax($taxData['ptt_taxcat_id'], $post['product_seller_id']);
        }
        $this->set('msg', Labels::getLabel('LBL_Product_Attributes_Setup_Successful', $this->adminLangId));
        $this->set('productId', $prod->getMainTableRecordId());
        $this->_template->render(false, false, 'json-success.php');
    }

    public function prodSpecificationFrm($productId)
    {
        $this->objPrivilege->canEditProducts();
        $productId = FatUtility::int($productId);
        $langId = FatApp::getPostedData('langId', FatUtility::VAR_INT, 0);
        $prodSpecId = FatApp::getPostedData('prodSpecId', FatUtility::VAR_INT, 0);
        if ($productId < 1 || $langId < 1) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }

        $prodSpecData = array();
        if ($prodSpecId > 0) {
            $prodSpec = new ProdSpecification();
            $prodSpecData = $prodSpec->getProdSpecification($prodSpecId, $productId, $langId, false);
        }

        $this->set('langId', $langId);
        $this->set('prodSpecData', $prodSpecData);
        $this->_template->render(false, false, 'products/prod-specification-form.php');
    }

    public function prodSpecificationsByLangId()
    {
        $productId = FatApp::getPostedData('product_id', FatUtility::VAR_INT, 0);
        $langId = FatApp::getPostedData('langId', FatUtility::VAR_INT, 0);
        $prod = new Product($productId);
        $productSpecifications = $prod->getProdSpecificationsByLangId($langId);
        if ($productSpecifications === false) {
            Message::addErrorMessage($prod->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        $this->set('productSpecifications', $productSpecifications);
        $this->set('langId', $langId);
        $this->_template->render(false, false, 'products/product-specifications.php');
    }

    public function setUpProductSpecifications()
    {
        $this->objPrivilege->canEditProducts();
        $post = FatApp::getPostedData();
        $productId = FatApp::getPostedData('product_id', FatUtility::VAR_INT, 0);
        if ($productId < 1) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $prod = new Product($productId);
        if (!$prod->saveProductSpecifications($post['prodSpecId'], $post['langId'], $post['prodspec_name'], $post['prodspec_value'], $post['prodspec_group'])) {
            Message::addErrorMessage($prod->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('msg', Labels::getLabel('LBL_Specification_added_successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function deleteProdSpec()
    {
        $this->objPrivilege->canEditProducts();
        $prodSpecId = FatApp::getPostedData('prodSpecId', FatUtility::VAR_INT, 0);
        if ($prodSpecId < 1) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $prodSpec = new ProdSpecification($prodSpecId);
        if (!$prodSpec->deleteRecord(true)) {
            Message::addErrorMessage($prodSpec->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('msg', Labels::getLabel('LBL_Specification_deleted_successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function productOptionsAndTag($productId)
    {
        $this->objPrivilege->canEditProducts();
        $productId = FatUtility::int($productId);
        if ($productId < 1) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $productTags = Product::getProductTags($productId);
        $productOptions = Product::getProductOptions($productId, $this->adminLangId);
        $productType = Product::getAttributesById($productId, 'product_type');
        $this->set('productTags', $productTags);
        $this->set('productOptions', $productOptions);
        $this->set('productId', $productId);
        $this->set('productType', $productType);
        $this->_template->render(false, false, 'products/product-options-and-tag.php');
    }

    public function upcListing($productId)
    {
        $productId = FatUtility::int($productId);
        if ($productId < 1) {
            FatUtility::dieWithError($this->str_invalid_request);
        }

        $srch = UpcCode::getSearchObject();
        $srch->addCondition('upc_product_id', '=', $productId);
        $srch->doNotCalculateRecords();
        $rs = $srch->getResultSet();
        $upcCodeData = FatApp::getDb()->fetchAll($rs, 'upc_options');
        $productOptions = Product::getProductOptions($productId, $this->adminLangId, true);
        $optionCombinations = CommonHelper::combinationOfElementsOfArr($productOptions, 'optionValues', '_');

        $this->set('productOptions', $productOptions);
        $this->set('optionCombinations', $optionCombinations);
        $this->set('upcCodeData', $upcCodeData);
        $this->set('productId', $productId);
        $this->_template->render(false, false);
    }

    public function productShippingFrm($productId)
    {
        $this->objPrivilege->canEditProducts();
        $productId = FatUtility::int($productId);
        if ($productId < 1) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $productFrm = $this->getProductShippingFrm($productId);
        $productData = Product::getAttributesById($productId);
        $prodShippingDetails = Product::getProductShippingDetails($productId, $this->adminLangId, $productData['product_seller_id']);
        if (isset($prodShippingDetails['ps_from_country_id'])) {
            $productData['shipping_country'] = Countries::getCountryById($prodShippingDetails['ps_from_country_id'], $this->adminLangId, 'country_name');
            $productData['ps_from_country_id'] = $prodShippingDetails['ps_from_country_id'];
        }
        $productFrm->fill($productData);
        $this->set('productFrm', $productFrm);
        $this->_template->render(false, false, 'products/product-shipping-frm.php');
    }

    private function getProductShippingFrm($productId)
    {
        $frm = new Form('frmProductShipping');
        $productType = Product::getAttributesById($productId, 'product_type');

        if ($productType == Product::PRODUCT_TYPE_PHYSICAL && FatApp::getConfig("CONF_PRODUCT_DIMENSIONS_ENABLE", FatUtility::VAR_INT, 1)) {
            $lengthUnitsArr = applicationConstants::getLengthUnitsArr($this->adminLangId);
            $frm->addSelectBox(Labels::getLabel('LBL_Dimensions_Unit', $this->adminLangId), 'product_dimension_unit', $lengthUnitsArr)->requirements()->setRequired();

            $lengthFld = $frm->addFloatField(Labels::getLabel('LBL_Length', $this->adminLangId), 'product_length', '0.00');
            $lengthFld->requirements()->setRequired(true);
            $lengthFld->requirements()->setFloatPositive();
            $lengthFld->requirements()->setRange('0.00001', '9999999999');

            $widthFld = $frm->addFloatField(Labels::getLabel('LBL_Width', $this->adminLangId), 'product_width', '0.00');
            $widthFld->requirements()->setRequired(true);
            $widthFld->requirements()->setFloatPositive();
            $widthFld->requirements()->setRange('0.00001', '9999999999');

            $heightFld = $frm->addFloatField(Labels::getLabel('LBL_Height', $this->adminLangId), 'product_height', '0.00');
            $heightFld->requirements()->setRequired(true);
            $heightFld->requirements()->setFloatPositive();
            $heightFld->requirements()->setRange('0.00001', '9999999999');

            $weightUnitsArr = applicationConstants::getWeightUnitsArr($this->adminLangId);
            $frm->addSelectBox(Labels::getLabel('LBL_Weight_Unit', $this->adminLangId), 'product_weight_unit', $weightUnitsArr)->requirements()->setRequired();

            $weightFld = $frm->addFloatField(Labels::getLabel('LBL_Weight', $this->adminLangId), 'product_weight', '0.00');
            $weightFld->requirements()->setRequired(true);
            $weightFld->requirements()->setFloatPositive();
            $weightFld->requirements()->setRange('0.01', '9999999999');
            /* ] */
        }
        $frm->addTextBox(Labels::getLabel('LBL_Country_the_Product_is_being_shipped_from', $this->adminLangId), 'shipping_country');
        $frm->addHtml('', '', '<table id="tab_shipping" width="100%" class="table table-bordered"></table><div class="gap"></div>');

        $frm->addHiddenField('', 'ps_from_country_id');
        $frm->addHiddenField('', 'product_id', $productId);
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_And_Next', $this->adminLangId));
        return $frm;
    }

    public function setUpProductShipping()
    {
        $this->objPrivilege->canEditProducts();
        $productId = FatApp::getPostedData('product_id', FatUtility::VAR_INT, 0);
        $frm = $this->getProductShippingFrm($productId);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieWithError(Message::getHtml());
        }

        $prod = new Product($productId);
        if (!$prod->saveProductData($post)) {
            Message::addErrorMessage($prod->getError());
            FatUtility::dieWithError(Message::getHtml());
        }

        $prodSellerId = Product::getAttributesById($productId, 'product_seller_id');
        $psFree = 0;
        $prodShippingDetails = Product::getProductShippingDetails($productId, $this->adminLangId, $prodSellerId);
        if (!empty($prodShippingDetails)) {
            $psFree = $prodShippingDetails['ps_free'];
        }
        if (!$prod->saveProductSellerShipping($prodSellerId, $psFree, $post['ps_from_country_id'])) {
            Message::addErrorMessage($prod->getError());
            FatUtility::dieWithError(Message::getHtml());
        }

        $productShiping = FatApp::getPostedData('product_shipping');
        if (!empty($productShiping)) {
            Product::addUpdateProductShippingRates($productId, $productShiping, $prodSellerId);
        }
        $this->set('msg', Labels::getLabel('LBL_Product_Shipping_Setup_Successful', $this->adminLangId));
        $this->set('productId', $prod->getMainTableRecordId());
        $this->_template->render(false, false, 'json-success.php');
    }

    public function translatedProductData()
    {
        $prodName = FatApp::getPostedData('product_name', FatUtility::VAR_STRING, '');
        $prodDesc = FatApp::getPostedData('product_description', FatUtility::VAR_STRING, '');
        $toLangId = FatApp::getPostedData('toLangId', FatUtility::VAR_INT, 0);
        $data = array(
            'product_name' => $prodName,
            'product_description' => $prodDesc,
        );
        $product = new Product();
        $translatedData = $product->getTranslatedProductData($data, $toLangId);
        if (!$translatedData) {
            Message::addErrorMessage($product->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('productName', $translatedData[$toLangId]['product_name']);
        $this->set('productDesc', $translatedData[$toLangId]['product_description']);
        $this->set('msg', Labels::getLabel('LBL_Product_Data_Translated_Successful', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function prodSpecGroupAutoComplete()
    {
        $post = FatApp::getPostedData();
        $srch = ProdSpecification::getSearchObject($post['langId'], false);
        if (!empty($post['keyword'])) {
            $srch->addCondition('prodspec_group', 'LIKE', '%' . $post['keyword'] . '%');
        }
        $srch->setPageSize(FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10));
        $srch->addMultipleFields(array('DISTINCT(prodspec_group)'));
        $rs = $srch->getResultSet();
        $prodSpecGroup = FatApp::getDb()->fetchAll($rs);
        $json = array();
        foreach ($prodSpecGroup as $key => $group) {
            $json[] = array(
            'name' => strip_tags(html_entity_decode($group['prodspec_group'], ENT_QUOTES, 'UTF-8'))
            );
        }
        die(json_encode($json));
    }
}
