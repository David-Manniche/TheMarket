<?php

class ProductCategoriesController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewProductCategories();
    }

    public function index()
    {
        $canEdit = $this->objPrivilege->canEditProductCategories(0, true);
        $this->set("canEdit", $canEdit);
        $this->_template->addJs(array('js/cropper.js', 'js/cropper-main.js', 'js/jquery-sortable-lists.js'));
        $this->_template->addCss('css/cropper.css');
        $this->_template->render();
    }

    public function search()
    {
        $records = array();
        $prodCat = new ProductCategory();
        $records = $prodCat->getCategories();
        $canEdit = $this->objPrivilege->canEditProductCategories(0, true);
        $this->set("arr_listing", $records);
        $this->set("canEdit", $canEdit);
        $this->_template->render(false, false);
    }

    public function getTotalBlock()
    {
        $totProds = Product::getProductsCount();
        $activeCategories = ProductCategory::getActiveInactiveCategoriesCount(applicationConstants::ACTIVE);
        $inactiveCategories = ProductCategory::getActiveInactiveCategoriesCount(applicationConstants::INACTIVE);
        $this->set("totProds", $totProds);
        $this->set("activeCategories", $activeCategories);
        $this->set("inactiveCategories", $inactiveCategories);
        $this->_template->render(false, false);
    }

    public function getSubCategories()
    {
        $canEdit = $this->objPrivilege->canEditProductCategories(0, true);
        $prodCatId = FatApp::getPostedData('prodCatId', FatUtility::VAR_INT, 0);
        $level = FatApp::getPostedData('level', FatUtility::VAR_INT, 0);
        $prodCat = new ProductCategory($prodCatId);
        $childCategories = $prodCat->getCategories();
        $this->set("childCategories", $childCategories);
        $this->set("level", $level);
        $this->set("canEdit", $canEdit);
        $this->_template->render(false, false);
    }

    public function updateOrder()
    {
        $this->objPrivilege->canEditProductCategories();
        $prodCatId = FatApp::getPostedData('catId', FatUtility::VAR_INT, 0);
        $parentCatId = FatApp::getPostedData('parentCatId', FatUtility::VAR_INT, 0);
        $catOrderArr = json_decode(FatApp::getPostedData('catOrder'));
        if ($prodCatId < 1 || count($catOrderArr) < 1) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $prodCat = new ProductCategory($prodCatId);
        $prodCat->updateCatParent($parentCatId);
        $prodCat->updateCatCode();
        if (!$prodCat->updateOrder($catOrderArr)) {
            Message::addErrorMessage($prodCat->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        ProductCategory::updateCatOrderCode();

        $this->set('msg', Labels::getLabel('LBL_Record_Updated_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function form($prodCatId = 0)
    {
        $this->objPrivilege->canEditProductCategories();
        $siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
        $prodCatId = FatUtility::int($prodCatId);
        $prodCatFrm = $this->getCategoryForm($prodCatId);
        if (0 < $prodCatId) {
            $data = ProductCategory::getAttributesById($prodCatId);
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $langData = ProductCategory::getLangDataArr($prodCatId, array(ProductCategory::DB_TBL_LANG_PREFIX . 'lang_id', ProductCategory::DB_TBL_PREFIX . 'name'));
            $catNameArr = array();
            foreach ($langData as $value) {
                $catNameArr[ProductCategory::DB_TBL_PREFIX . 'name'][$value[ProductCategory::DB_TBL_LANG_PREFIX . 'lang_id']] = $value[ProductCategory::DB_TBL_PREFIX . 'name'];
            }
            $data = array_merge($data, $catNameArr);
            $prodCatFrm->fill($data);
        }
        $mediaLanguages = applicationConstants::bannerTypeArr();
        $screenArr = applicationConstants::getDisplaysArr($this->adminLangId);
        $langData = Language::getAllNames();
        unset($langData[$siteDefaultLangId]);
        $this->set('prodCatFrm', $prodCatFrm);
        $this->set('mediaLanguages', $mediaLanguages);
        $this->set('screenArr', $screenArr);
        $this->set('otherLangData', $langData);
        $this->_template->render(false, false);
    }

    private function getCategoryForm($prodCatId = 0)
    {
        $prodCatId = FatUtility::int($prodCatId);
        $siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
        $frm = new Form('frmProdCategory');
        $frm->addHiddenField('', 'prodcat_id', $prodCatId);
        $frm->addRequiredField(Labels::getLabel('LBL_Category_Name', $this->adminLangId), 'prodcat_name[' . $siteDefaultLangId . ']');

        $prodCat = new ProductCategory();
        $categoriesArr = $prodCat->getCategoriesForSelectBox($this->adminLangId, $prodCatId);
        $categories = array(0 => Labels::getLabel('LBL_Root_Category', $this->adminLangId)) + $prodCat->makeAssociativeArray($categoriesArr);
        $frm->addSelectBox(Labels::getLabel('LBL_Parent_Category', $this->adminLangId), 'prodcat_parent', $categories, '', array(), '');

        $yesNoArr = applicationConstants::getYesNoArr($this->adminLangId);
        $frm->addRadioButtons(Labels::getLabel('LBL_Publish', $this->adminLangId), 'prodcat_active', $yesNoArr, '1', array());

        $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
        $langData = Language::getAllNames();
        unset($langData[$siteDefaultLangId]);
        if (!empty($translatorSubscriptionKey) && count($langData) > 0) {
            $frm->addCheckBox(Labels::getLabel('LBL_Translate_To_Other_Languages', $this->adminLangId), 'auto_update_other_langs_data', 1, array(), false, 0);
        }
        foreach ($langData as $langId => $data) {
            $frm->addTextBox(Labels::getLabel('LBL_Category_Name', $this->adminLangId), 'prodcat_name[' . $langId . ']');
        }

        $mediaLanguages = applicationConstants::bannerTypeArr();
        $frm->addSelectBox(Labels::getLabel('LBL_Language', $this->adminLangId), 'icon_lang_id', $mediaLanguages, '', array(), '');
        $frm->addHiddenField('', 'icon_file_type', AttachedFile::FILETYPE_CATEGORY_ICON);
        $frm->addHiddenField('', 'logo_min_width');
        $frm->addHiddenField('', 'logo_min_height');
        $frm->addFileUpload(Labels::getLabel('LBL_Upload', $this->adminLangId), 'cat_icon', array('accept' => 'image/*', 'data-frm' => 'frmCategoryIcon'));
        foreach ($mediaLanguages as $key => $data) {
            $frm->addHiddenField('', 'cat_icon_image_id[' . $key . ']');
        }

        $frm->addSelectBox(Labels::getLabel('LBL_Language', $this->adminLangId), 'banner_lang_id', $mediaLanguages, '', array(), '');
        $screenArr = applicationConstants::getDisplaysArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel("LBL_Device", $this->adminLangId), 'slide_screen', $screenArr, '', array(), '');
        $frm->addHiddenField('', 'banner_file_type', AttachedFile::FILETYPE_CATEGORY_BANNER);
        $frm->addHiddenField('', 'banner_min_width');
        $frm->addHiddenField('', 'banner_min_height');
        $frm->addFileUpload(Labels::getLabel('LBL_Upload', $this->adminLangId), 'cat_banner', array('accept' => 'image/*', 'data-frm' => 'frmCategoryBanner'));
        foreach ($mediaLanguages as $key => $data) {
            foreach ($screenArr as $key1 => $screen) {
                $frm->addHiddenField('', 'cat_banner_image_id[' . $key . '_' . $key1 . ']');
            }
        }

        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save', $this->adminLangId));
        $frm->addButton('', 'btn_discard', Labels::getLabel('LBL_Discard', $this->adminLangId));
        return $frm;
    }

    public function setup()
    {
        $this->objPrivilege->canEditProductCategories();
        $frm = $this->getCategoryForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $prodCatId = FatUtility::int($post['prodcat_id']);
        $post['prodcat_status'] = ProductCategory::REQUEST_APPROVED;
        $productCategory = new ProductCategory($prodCatId);
        if (!$productCategory->saveCategoryData($post)) {
            Message::addErrorMessage($productCategory->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('msg', Labels::getLabel('LBL_Category_Setup_Successful', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function translatedCategoryData()
    {
        $catName = FatApp::getPostedData('catName', FatUtility::VAR_STRING, '');
        $toLangId = FatApp::getPostedData('toLangId', FatUtility::VAR_INT, 0);
        $data['prodcat_name'] = $catName;
        $productCategory = new ProductCategory();
        $translatedData = $productCategory->getTranslatedCategoryData($data, $toLangId);
        if (!$translatedData) {
            Message::addErrorMessage($productCategory->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('prodCatName', $translatedData[$toLangId]['prodcat_name']);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function images($prodcat_id, $imageType = '', $lang_id = 0, $slide_screen = 0)
    {
        $canEdit = $this->objPrivilege->canEditProductCategories(0, true);
        $prodcat_id = FatUtility::int($prodcat_id);
        $lang_id = FatUtility::int($lang_id);
        $catIcons = $catBanners = array();
        if ($imageType=='icon') {
            $catIcons = AttachedFile::getAttachment(AttachedFile::FILETYPE_CATEGORY_ICON, $prodcat_id, 0, $lang_id, false);
            $this->set('images', $catIcons);
            $this->set('imageFunction', 'icon');
        } elseif ($imageType=='banner') {
            $catBanners = AttachedFile::getAttachment(AttachedFile::FILETYPE_CATEGORY_BANNER, $prodcat_id, 0, $lang_id, false, $slide_screen);
            $this->set('images', $catBanners);
            $this->set('imageFunction', 'banner');
        }
        $this->set('imageType', $imageType);
        $this->set('languages', Language::getAllNames());
        $this->set('canEdit', $canEdit);
        $this->_template->render(false, false);
    }

    public function setUpCatImages()
    {
        $this->objPrivilege->canEditProductCategories();
        $file_type = FatApp::getPostedData('file_type', FatUtility::VAR_INT, 0);
        $prodcat_id = FatApp::getPostedData('prodcat_id', FatUtility::VAR_INT, 0);
        $lang_id = FatApp::getPostedData('lang_id', FatUtility::VAR_INT, 0);
        $slide_screen = FatApp::getPostedData('slide_screen', FatUtility::VAR_INT, 0);
        $afileId = FatApp::getPostedData('afile_id', FatUtility::VAR_INT, 0);
        if (!$file_type) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $allowedFileTypeArr = array(AttachedFile::FILETYPE_CATEGORY_IMAGE, AttachedFile::FILETYPE_CATEGORY_ICON, AttachedFile::FILETYPE_CATEGORY_BANNER);

        if (!in_array($file_type, $allowedFileTypeArr)) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }

        if (!is_uploaded_file($_FILES['cropped_image']['tmp_name'])) {
            Message::addErrorMessage(Labels::getLabel('LBL_Please_Select_A_File', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }

        ProductCategory:: deleteImagesWithOutCategoryId($file_type);

        $fileHandlerObj = new AttachedFile($afileId);
        if (!$res = $fileHandlerObj->saveImage(
            $_FILES['cropped_image']['tmp_name'],
            $file_type,
            $prodcat_id,
            0,
            $_FILES['cropped_image']['name'],
            -1,
            $unique_record = false,
            $lang_id,
            $_FILES['cropped_image']['type'],
            $slide_screen
        )) {
            Message::addErrorMessage($fileHandlerObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        ProductCategory::setImageUpdatedOn($prodcat_id);
        $this->set('file', $_FILES['cropped_image']['name']);
        $this->set('prodcat_id', $prodcat_id);
        $this->set('msg', $_FILES['cropped_image']['name'].' '.Labels::getLabel('LBL_Uploaded_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function removeImage($afileId, $prodCatId, $imageType = '', $langId = 0, $slide_screen = 0)
    {
        $this->objPrivilege->canEditProductCategories();
        $afileId = FatUtility::int($afileId);
        $prodCatId = FatUtility::int($prodCatId);
        $langId = FatUtility::int($langId);
        if (!$afileId) {
            Message::addErrorMessage(Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        if ($imageType=='icon') {
            $fileType = AttachedFile::FILETYPE_CATEGORY_ICON;
        } elseif ($imageType=='banner') {
            $fileType = AttachedFile::FILETYPE_CATEGORY_BANNER;
        }
        $fileHandlerObj = new AttachedFile();
        if (!$fileHandlerObj->deleteFile($fileType, $prodCatId, $afileId, 0, $langId, $slide_screen)) {
            Message::addErrorMessage($fileHandlerObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        ProductCategory::setImageUpdatedOn($prodCatId);
        $this->set('imageType', $imageType);
        $this->set('msg', Labels::getLabel('MSG_Image_deleted_successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function changeStatus()
    {
        $this->objPrivilege->canEditProductCategories();
        $prodCatId = FatApp::getPostedData('prodCatId', FatUtility::VAR_INT, 0);
        if ($prodCatId < 1) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $catData = ProductCategory::getAttributesById($prodCatId, array('prodcat_active'));
        if (!$catData) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $status = ($catData['prodcat_active'] == applicationConstants::ACTIVE) ? applicationConstants::INACTIVE : applicationConstants::ACTIVE;
        $prodCat = new ProductCategory($prodCatId);
        if (!$prodCat->changeStatus($status)) {
            Message::addErrorMessage($prodCat->getError());
            FatUtility::dieWithError(Message::getHtml());
        }

        Product::updateMinPrices();
        $this->set('msg', $this->str_update_record);
        $this->_template->render(false, false, 'json-success.php');
    }
    
    public function changeRequestStatus()
    {
        $this->objPrivilege->canEditProductCategories();
        $prodCatId = FatApp::getPostedData('prodCatId', FatUtility::VAR_INT, 0);
        
        if ($prodCatId < 1) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        
        $catData = ProductCategory::getAttributesById($prodCatId, array('prodcat_status'));
        if (!$catData) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $prodCat = new ProductCategory($prodCatId);
        $prodCat->assignValues(
            array(
                ProductCategory::tblFld('status') => ProductCategory::REQUEST_APPROVED,
                ProductCategory::tblFld('active') => applicationConstants::ACTIVE,
                ProductCategory::tblFld('updated_on') => date('Y-m-d H:i:s')
            )
        );
        if (!$prodCat->save()) {
            Message::addErrorMessage($prodCat->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $this->set('msg', $this->str_update_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function deleteRecord()
    {
        $this->objPrivilege->canEditProductCategories();
        $prodcat_id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if ($prodcat_id < 1) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $prodCateObj = new ProductCategory($prodcat_id);
        if (!$prodCateObj->canRecordMarkDelete($prodcat_id)) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        
        /* Sub-Categories have products[ */
        if (true === $prodCateObj->haveProducts()) {
            FatUtility::dieJsonError(Labels::getLabel('LBL_Products_are_associated_with_its_category/sub-categories_so_we_are_not_able_to_delete_this_category', $this->adminLangId));
        }
        /* ] */

        $prodCateObj->assignValues(array(ProductCategory::tblFld('deleted') => 1));
        if (!$prodCateObj->save()) {
            FatUtility::dieJsonError($prodCateObj->getError());
        }

        Product::updateMinPrices();
        $this->set("msg", $this->str_delete_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function getBreadcrumbNodes($action)
    {
        $nodes = array();
        switch ($action) {
            case 'index':
            case 'form':
                $nodes[] = array('title' => Labels::getLabel('LBL_Categories', $this->adminLangId), 'href' => UrlHelper::generateUrl('ProductCategories'));
        }
        return $nodes;
    }

    public function autocomplete()
    {
        $search_keyword = FatApp::getPostedData('keyword', FatUtility::VAR_STRING, '');
        $search_keyword = urldecode($search_keyword);
        $prodCateObj = new ProductCategory();
        $categories = $prodCateObj->getProdCatAutoSuggest($search_keyword, 10, $this->adminLangId);
        $json = array();
        $matches = $categories;
        foreach ($matches as $key => $val) {
            $json[] = array(
            'prodcat_id' => $key,
            'prodcat_identifier' => strip_tags(html_entity_decode($val, ENT_QUOTES, 'UTF-8'))
            );
        }
        echo json_encode($json);
    }

    public function links_autocomplete()
    {
        $keyword = FatApp::getPostedData('keyword', FatUtility::VAR_STRING, '');
        $prodCatObj = new ProductCategory();
        $arr_options = $prodCatObj->getProdCatTreeStructureSearch(0, $this->adminLangId, $keyword);
        $json = array();
        foreach ($arr_options as $key => $product) {
            $json[] = array(
            'id' => $key,
            'name' => strip_tags(html_entity_decode($product, ENT_QUOTES, 'UTF-8'))
            );
        }
        die(json_encode($json));
    }
    
    public function requests()
    {
        $this->objPrivilege->canViewProductCategories();
        $search = $this->getSearchForm(true);
        $data = FatApp::getPostedData();
        if ($data) {
            $data['prodcat_id'] = $data['id'];
            unset($data['id']);
            $search->fill($data);
        }
        $this->_template->addCss('css/cropper.css');
        $this->_template->addJs('js/cropper.js');
        $this->_template->addJs('js/cropper-main.js');
        $this->set("search", $search);
        $this->_template->render();
    }
    
    private function getSearchForm($request = false)
    {
        $frm = new Form('frmSearch', array('id' => 'frmSearch'));
        $f1 = $frm->addTextBox(Labels::getLabel('LBL_Keyword', $this->adminLangId), 'keyword', '', array('class' => 'search-input'));
        if ($request) {
            $frm->addTextBox(Labels::getLabel('LBL_Seller_Name_Or_Email', $this->adminLangId), 'user_name', '', array('id' => 'keyword', 'autocomplete' => 'off'));
            $frm->addHiddenField('', 'user_id');
        }
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear_Search', $this->adminLangId), array('onclick' => 'clearSearch();'));
        $frm->addHiddenField('', 'prodcat_id');
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }
    
    public function searchRequests()
    {
        $canEdit = $this->objPrivilege->canEditProductCategories(0, true);

        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        $searchForm = $this->getSearchForm(true);
        $data = FatApp::getPostedData();
        $page = (empty($data['page']) || $data['page'] <= 0) ? 1 : $data['page'];
        $post = $searchForm->getFormDataFromArray($data);
        
        $srch = ProductCategory::getSearchObject(false, $this->adminLangId, false, ProductCategory::REQUEST_PENDING);
        $srch->joinTable(User::DB_TBL, 'LEFT OUTER JOIN', 'u.user_id = prodcat_seller_id', 'u');
        $srch->joinTable(Shop::DB_TBL, 'LEFT OUTER JOIN', 'shop_user_id = if(u.user_parent > 0, user_parent, u.user_id)', 'shop');
        $srch->joinTable(Shop::DB_TBL_LANG, 'LEFT OUTER JOIN', 'shop.shop_id = s_l.shoplang_shop_id AND shoplang_lang_id = ' . $this->adminLangId, 's_l');
        $srch->addMultipleFields(array('m.*','prodcat_name', 'u.user_name', 'ifnull(shop_name, shop_identifier) as shop_name'));
        $srch->addCondition('prodcat_seller_id', '>', 0);
        $srch->addOrder('prodcat_id', 'desc');
        if (!empty($post['keyword'])) {
            $condition = $srch->addCondition('prodcat_identifier', 'like', '%' . $post['keyword'] . '%');
            $condition->attachCondition('prodcat_name', 'like', '%' . $post['keyword'] . '%', 'OR');
        }
        if (!empty($post['prodcat_id'])) {
            $srch->addCondition('prodcat_id', '=', $post['prodcat_id']);
        }
        $user_id = FatApp::getPostedData('user_id', FatUtility::VAR_INT, 0);
        if ($user_id > 0) {
            $srch->addCondition('prodcat_seller_id', '=', $user_id);
        }
        $page = (empty($page) || $page <= 0) ? 1 : $page;
        $page = FatUtility::int($page);
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
        
        $this->set("arr_listing", $records);
        $this->set("canEdit", $canEdit);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('postedData', $post);
        $this->_template->render(false, false);
    }

    public function toggleBulkStatuses()
    {
        $this->objPrivilege->canEditBrands();

        $status = FatApp::getPostedData('status', FatUtility::VAR_INT, -1);
        $brandIdsArr = FatUtility::int(FatApp::getPostedData('brandIds'));
        if (empty($brandIdsArr) || -1 == $status) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }

        foreach ($brandIdsArr as $brandId) {
            if (1 > $brandId) {
                continue;
            }

            $this->updateBrandStatus($brandId, $status);
        }
        Product::updateMinPrices();
        $this->set('msg', $this->str_update_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    private function updateCategoryStatus($brandId, $status)
    {
        $status = FatUtility::int($status);
        $brandId = FatUtility::int($brandId);
        if (1 > $brandId || -1 == $status) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }

        $brandObj = new Brand($brandId);
        if (!$brandObj->changeStatus($status)) {
            Message::addErrorMessage($brandObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
    }
}
