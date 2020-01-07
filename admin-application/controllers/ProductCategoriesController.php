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
        $frmSearch = $this->getSearchForm();
        $totProds = Product::getProductsCount();
        $activeCategories = ProductCategory::getActiveInactiveCategoriesCount(applicationConstants::ACTIVE);
        $inactiveCategories = ProductCategory::getActiveInactiveCategoriesCount(applicationConstants::INACTIVE);
        $canEdit = $this->objPrivilege->canEditProductCategories(0, true); 
        $this->set("frmSearch", $frmSearch);
        $this->set("totProds", $totProds);
        $this->set("activeCategories", $activeCategories);
        $this->set("inactiveCategories", $inactiveCategories);
        $this->set("canEdit", $canEdit);
        $this->_template->addJs('js/import-export.js');
        $this->_template->render();
    }
    
    private function getSearchForm()
    {
        $frm = new Form('frmSearch');
        $frm->addTextBox(Labels::getLabel('LBL_Keyword', $this->adminLangId), 'prodcat_identifier');
        $fldSubmit = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Search', $this->adminLangId));
        $fldCancel = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear', $this->adminLangId));
        $fldSubmit->attachField($fldCancel);
        return $frm;
    }

    public function search()
    {
        $records = array();
        $keyword = FatApp::getPostedData('prodcat_identifier', null, '');
        $prodCat = new ProductCategory();
        $records = $prodCat->getCategories(true, $keyword);
        $canEdit = $this->objPrivilege->canEditProductCategories(0, true);
        $this->set("arr_listing", $records);
        $this->set("canEdit", $canEdit);
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
    
    public function updateDisplayOrder()
    {
        $this->objPrivilege->canEditProductCategories();
        $prodCatId = FatApp::getPostedData('prodCatId', FatUtility::VAR_INT, 0); 
        $displayOrder = FatApp::getPostedData('displayOrder', FatUtility::VAR_INT, 0);
        if( $prodCatId < 1 ){
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }
        $orderData[$displayOrder] = $prodCatId;
        $prodCat = new ProductCategory();
        if (!$prodCat->updateOrder($orderData)) {
            Message::addErrorMessage($prodCat->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        ProductCategory::updateCatOrderCode($prodCatId);
        $this->set('msg', Labels::getLabel('LBL_Order_Updated_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function form($prodCatId = 0, $parentCatId = 0)
    {
        $this->objPrivilege->canEditProductCategories();
        $siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
        $prodCatId = FatUtility::int($prodCatId);
        $parentCatId = FatUtility::int($parentCatId);
        $prodCatFrm = $this->getCategoryForm($prodCatId);      
        if (0 < $prodCatId) {
            $data = ProductCategory::getAttributesById($prodCatId);
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $langData = ProductCategory::getLangDataArr($prodCatId, array(ProductCategory::DB_TBL_LANG_PREFIX.'lang_id', ProductCategory::DB_TBL_PREFIX.'name'));            
            $catNameArr = array();
            foreach($langData as $value){
                $catNameArr[ProductCategory::DB_TBL_PREFIX.'name'][$value[ProductCategory::DB_TBL_LANG_PREFIX.'lang_id']] = $value[ProductCategory::DB_TBL_PREFIX.'name'];                              
            }
            $data = array_merge($data, $catNameArr); 
        }         
        $data['parentCatId'] = $parentCatId;
        $prodCatFrm->fill($data);
        $mediaLanguages = applicationConstants::bannerTypeArr();
        $screenArr = applicationConstants::getDisplaysArr($this->adminLangId);
        $langData = Language::getAllNames();
        unset($langData[$siteDefaultLangId]);
        $this->set('prodCatFrm', $prodCatFrm);
        $this->set('mediaLanguages', $mediaLanguages);
        $this->set('screenArr', $screenArr);
        $this->set('otherLangData', $langData);
        $this->_template->render();
    }
    
    private function getCategoryForm( $prodCatId = 0 )
    {
        $prodCatId = FatUtility::int($prodCatId);
        $siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
        $frm = new Form('frmProdCategory');
        $frm->addHiddenField('', 'parentCatId', 0);
        $frm->addHiddenField('', 'prodcat_id', $prodCatId);
        $frm->addRequiredField(Labels::getLabel('LBL_Category_Name', $this->adminLangId), 'prodcat_name['.$siteDefaultLangId.']');
        
        $prodCat = new ProductCategory();
        $categoriesArr = $prodCat->getCategoriesForSelectBox($this->adminLangId, $prodCatId);
        $categories = array(0 => Labels::getLabel('LBL_Root_Category', $this->adminLangId)) + $prodCat->makeAssociativeArray($categoriesArr);
        $frm->addSelectBox(Labels::getLabel('LBL_Category_Parent', $this->adminLangId), 'prodcat_parent', $categories, '', array(), '');
        
        $activeInactiveArr = applicationConstants::getActiveInactiveArr($this->adminLangId);        
        $frm->addRadioButtons(Labels::getLabel('LBL_Status', $this->adminLangId), 'prodcat_active', $activeInactiveArr, '1', array());
        
        $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
        $langData = Language::getAllNames();
        unset($langData[$siteDefaultLangId]);
        if (!empty($translatorSubscriptionKey) && count($langData) > 0) {
            $frm->addCheckBox(Labels::getLabel('LBL_Translate_For_Other_Languages', $this->adminLangId), 'auto_update_other_langs_data', 1, array(), false, 0);
        }
        foreach($langData as $langId=>$data) {
            $frm->addTextBox(Labels::getLabel('LBL_Category_Name', $this->adminLangId), 'prodcat_name['.$langId.']');
        }
        
        $mediaLanguages = applicationConstants::bannerTypeArr();
        $frm->addSelectBox(Labels::getLabel('LBL_Language', $this->adminLangId), 'icon_lang_id', $mediaLanguages, '', array(), '');
        $frm->addButton(Labels::getLabel('LBL_Icon', $this->adminLangId), 'cat_icon', Labels::getLabel('LBL_Upload', $this->adminLangId),array('class'=>'catFile-Js', 'data-file_type'=>AttachedFile::FILETYPE_CATEGORY_ICON, 'data-frm'=>'catIcon'));
        foreach($mediaLanguages as $key=>$data){
            $frm->addHiddenField('', 'cat_icon_image_id['.$key.']');
        }
        
        $frm->addSelectBox(Labels::getLabel('LBL_Language', $this->adminLangId), 'banner_lang_id', $mediaLanguages, '', array(), '');
        $screenArr = applicationConstants::getDisplaysArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel("LBL_Display_For", $this->adminLangId), 'slide_screen', $screenArr, '', array(), '');
        $frm->addButton(Labels::getLabel('LBL_Banner', $this->adminLangId),'cat_banner', Labels::getLabel('LBL_Upload', $this->adminLangId), array('class'=>'catFile-Js', 'data-file_type'=>AttachedFile::FILETYPE_CATEGORY_BANNER,'data-frm'=> 'catBanner')
        );
        foreach($mediaLanguages as $key=>$data){
            foreach($screenArr as $key1=>$screen){
                $frm->addHiddenField('', 'cat_banner_image_id['.$key.'_'.$key1.']');
            }
        }
        
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Create', $this->adminLangId));
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
        $productCategory = new ProductCategory($prodCatId);
        if(!$productCategory->saveCategoryData($post)){
            Message::addErrorMessage($productCategory->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('msg', Labels::getLabel('LBL_Category_Setup_Successful', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }
    
    public function translatedCategoryData()
    {
        $catName = FatApp::getPostedData('catName', FatUtility::VAR_STRING, '');
        $selectedLangId = FatApp::getPostedData('selectedLangId', FatUtility::VAR_INT, 0);
        $data['prodcat_name'] = $catName;
        $productCategory = new ProductCategory(); 
        $translatedData = $productCategory->getTranslatedCategoryData($data, $selectedLangId);
        if(!$translatedData){
            Message::addErrorMessage($productCategory->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('prodCatName', $translatedData[$selectedLangId]['prodcat_name']);
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
            $this->set('screenTypeArr', $this->getDisplayScreenName());
            $this->set('imageFunction', 'banner');
        } 
        $this->set('imageType', $imageType);
        $this->set('languages', Language::getAllNames());
        $this->set('canEdit', $canEdit);
        $this->_template->render(false, false);
    }

    public function setUpCatImages()
    {
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

        if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
            Message::addErrorMessage(Labels::getLabel('LBL_Please_Select_A_File', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        
        ProductCategory:: deleteImagesWithOutCategoryId($file_type);
        
        $fileHandlerObj = new AttachedFile($afileId);
        if (!$res = $fileHandlerObj->saveImage(
            $_FILES['file']['tmp_name'],
            $file_type,
            $prodcat_id,
            0,
            $_FILES['file']['name'],
            -1,
            $unique_record = false,
            $lang_id,
            $_FILES['file']['type'],
            $slide_screen
        )) {
            Message::addErrorMessage($fileHandlerObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        ProductCategory::setImageUpdatedOn($prodcat_id);
        $this->set('file', $_FILES['file']['name']);
        $this->set('prodcat_id', $prodcat_id);
        $this->set('msg', $_FILES['file']['name'].' '.Labels::getLabel('LBL_Uploaded_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function removeImage($afileId, $prodCatId, $imageType = '', $langId = 0, $slide_screen = 0)
    {
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
        $prodcatId = FatApp::getPostedData('prodcatId', FatUtility::VAR_INT, 0);
        if ($prodcatId < 1) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }
        $catData = ProductCategory::getAttributesById($prodcatId, array('prodcat_active'));
        if (!$catData) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $status = ($catData['prodcat_active'] == applicationConstants::ACTIVE) ? applicationConstants::INACTIVE : applicationConstants::ACTIVE;
        $this->updateProductCategoryStatus($prodcatId, $status);
        Product::updateMinPrices();
        $this->set('msg', $this->str_update_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function toggleBulkStatuses()
    {
        $this->objPrivilege->canEditProductCategories();
        $status = FatApp::getPostedData('status', FatUtility::VAR_INT, -1);
        $prodcatIdsArr = FatUtility::int(FatApp::getPostedData('prodcat_ids'));
        if (empty($prodcatIdsArr) || -1 == $status) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }

        foreach ($prodcatIdsArr as $prodcatId) {
            if (1 > $prodcatId) {
                continue;
            }
            $this->updateProductCategoryStatus($prodcatId, $status);
        }
        $this->set('msg', $this->str_update_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    private function updateProductCategoryStatus($prodcatId, $status)
    {
        $prodCatObj = new ProductCategory($prodcatId);
        $status = FatUtility::int($status);
        $prodcatId = FatUtility::int($prodcatId);

        if (1 > $prodcatId || -1 == $status) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }

        if (!$prodCatObj->changeStatus($status)) {
            Message::addErrorMessage($prodCatObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
    }

    public function deleteRecord()
    {
        $this->objPrivilege->canEditProductCategories();

        $prodcat_id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if ($prodcat_id < 1) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $this->markAsDeleted($prodcat_id);
        Product::updateMinPrices();
        $this->set("msg", $this->str_delete_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function deleteSelected()
    {
        $this->objPrivilege->canEditProductCategories();
        $prodcatIdsArr = FatUtility::int(FatApp::getPostedData('prodcat_ids'));
        if (empty($prodcatIdsArr)) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }
        foreach ($prodcatIdsArr as $prodcatId) {
            if (1 > $prodcatId) {
                continue;
            }
            $this->markAsDeleted($prodcatId);
        }
        $this->set('msg', $this->str_delete_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    private function markAsDeleted($prodcat_id)
    {
        $prodcat_id = FatUtility::int($prodcat_id);
        if (1 > $prodcat_id) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }

        $prodCateObj = new ProductCategory($prodcat_id);
        if (!$prodCateObj->canRecordMarkDelete($prodcat_id)) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }

        /* Sub-Categories have products[ */
        $categoriesHaveProducts = $prodCateObj->categoriesHaveProducts($this->adminLangId);

        $srch = ProductCategory::getSearchObject(true, $this->adminLangId, false);
        $srch->addCondition('m.prodcat_parent', '=', $prodcat_id);
        $srch->addCondition('m.prodcat_deleted', '=', 0);
        $srch->addMultipleFields(array("m.prodcat_id"));
        if ($categoriesHaveProducts) {
            $srch->addCondition('m.prodcat_id', 'in', $categoriesHaveProducts);
        }
        $rs = $srch->getResultSet();
        if ($srch->recordCount() > 0) {
            FatUtility::dieJsonError(Labels::getLabel('LBL_Products_are_associated_with_its_sub-categories_so_we_are_not_able_to_delete_this_category', $this->adminLangId));
        }
        /* ] */

        $prodCateObj->assignValues(array(ProductCategory::tblFld('deleted') => 1));
        if (!$prodCateObj->save()) {
            FatUtility::dieJsonError($prodCateObj->getError());
        }
    }

    public function autocomplete()
    {
        if (!FatUtility::isAjaxCall()) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $post=FatApp::getPostedData();
        $search_keyword='';
        if (!empty($post["keyword"])) {
            $search_keyword=urldecode($post["keyword"]);
        }

        $prodCateObj = new ProductCategory();
        $categories = $prodCateObj->getProdCatAutoSuggest($search_keyword, 10, $this->adminLangId);

        $json = array();
        $matches=$categories;
        foreach ($matches as $key => $val) {
            $json[] = array(
            'prodcat_id' => $key,
            'prodcat_identifier'      => strip_tags(html_entity_decode($val, ENT_QUOTES, 'UTF-8'))
            );
        }
        echo json_encode($json);
    }

    public function getBreadcrumbNodes($action)
    {
        $nodes = array();
        switch ($action) {
            case 'index':
            case 'form':
                $nodes[] = array('title'=>Labels::getLabel('LBL_Categories', $this->adminLangId), 'href'=>CommonHelper::generateUrl('ProductCategories'));
        }
        return $nodes;
    }

    public function links_autocomplete()
    {
        $prodCatObj = new ProductCategory();
        $post = FatApp::getPostedData();
        $arr_options = $prodCatObj->getProdCatTreeStructureSearch(0, $this->adminLangId, $post['keyword']);
        $json = array();
        foreach ($arr_options as $key => $product) {
            $json[] = array(
            'id'     => $key,
            'name'  => strip_tags(html_entity_decode($product, ENT_QUOTES, 'UTF-8'))
            );
        }
        die(json_encode($json));
    }

    private function getDisplayScreenName()
    {
        $screenTypesArr = applicationConstants::getDisplaysArr($this->adminLangId);
        return array( 0 => '' ) + $screenTypesArr;
    }
    
    public function demoSetup()
    {
        $this->_template->render();
    }

    
    /* public function langForm($catId = 0, $lang_id = 0, $autoFillLangData = 0)
    {
        $this->objPrivilege->canEditProductCategories();

        $prodcat_id = FatUtility::int($catId);
        $lang_id = FatUtility::int($lang_id);

        if ($prodcat_id==0 || $lang_id==0) {
            FatUtility::dieWithError($this->str_invalid_request);
        }

        $prodCatLangFrm = $this->getLangForm($prodcat_id, $lang_id);
        if (0 < $autoFillLangData) {
            $updateLangDataobj = new TranslateLangData(ProductCategory::DB_TBL_LANG);
            $translatedData = $updateLangDataobj->getTranslatedData($prodcat_id, $lang_id);
            if (false === $translatedData) {
                Message::addErrorMessage($updateLangDataobj->getError());
                FatUtility::dieWithError(Message::getHtml());
            }
            $langData = current($translatedData);
        } else {
            $langData = ProductCategory::getAttributesByLangId($lang_id, $prodcat_id);
        }

        if ($langData) {
            $prodCatLangFrm->fill($langData);
        }

        $this->set('languages', Language::getAllNames());
        $this->set('prodcat_id', $prodcat_id);
        $this->set('prodcat_lang_id', $lang_id);
        $this->set('prodCatLangFrm', $prodCatLangFrm);
        $this->set('formLayout', Language::getLayoutDirection($lang_id));
        $this->_template->render(false, false);
    } */

    /* public function mediaForm($prodcat_id = 0)
    {
        $prodcat_id = FatUtility::int($prodcat_id);
        $prodCatIconFrm = $this->getCategoryIconForm($prodcat_id);
        $prodCatImageFrm = $this->getCategoryImageForm($prodcat_id);
        $prodCatBannerFrm = $this->getCategoryBannerForm($prodcat_id);
        $bannerTypeArr = applicationConstants::bannerTypeArr();

        $this->set('prodcat_id', $prodcat_id);
        $this->set('prodCatIconFrm', $prodCatIconFrm);
        $this->set('prodCatImageFrm', $prodCatImageFrm);
        $this->set('prodCatBannerFrm', $prodCatBannerFrm);
        $this->set('bannerTypeArr', $bannerTypeArr);
        $this->set('languages', Language::getAllNames());
        $this->_template->render(false, false);
    } */

    /* public function removeBanner($prodcat_id = 0, $langId = 0)
    {
        $prodcat_id = FatUtility::int($prodcat_id);
        $langId = FatUtility::int($langId);
        if (!$prodcat_id) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $fileHandlerObj = new AttachedFile();
        if (!$fileHandlerObj->deleteFile(AttachedFile::FILETYPE_CATEGORY_BANNER, $prodcat_id, 0, 0, $langId)) {
            Message::addErrorMessage($fileHandlerObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $this->set('msg', Labels::getLabel('LBL_Deleted_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    } */

    /* public function contentBlock($prodcat_id = 0){
        $this->objPrivilege->canEditProductCategories();
        $prodcat_id = FatUtility::int($prodcat_id);

        if($prodcat_id == 0){
        FatUtility::dieWithError($this->str_invalid_request);
        }

        $blockCatFrm = $this->getContentBlockForm($prodcat_id);
        $data = array();

        if($data){
        $blockCatFrm->fill($data);
        }

        $this->set('languages', Language::getAllNames());
        $this->set('prodcat_id', $prodcat_id);
        $this->set('blockCatFrm', $blockCatFrm);
        $this->_template->render(false, false);
    } */

    /* private function getForm($prodcat_id = 0)
    {
        $prodcat_id = FatUtility::int($prodcat_id);
        $prodCatObj = new ProductCategory();
        $arrCategories = $prodCatObj->getCategoriesForSelectBox($this->adminLangId, $prodcat_id);
        $categories = $prodCatObj->makeAssociativeArray($arrCategories);

        $frm = new Form('frmProdCategory', array( 'id' => 'frmProdCategory'));
        $frm->addHiddenField('', 'parentCatId', 0);
        $frm->addRequiredField(Labels::getLabel('LBL_Category_Identifier', $this->adminLangId), 'prodcat_identifier');
        $frm->addSelectBox(Labels::getLabel('LBL_Category_Parent', $this->adminLangId), 'prodcat_parent', array( 0 => Labels::getLabel('LBL_Root_Category', $this->adminLangId) ) + $categories, '', array(), '');
        $activeInactiveArr = applicationConstants::getActiveInactiveArr($this->adminLangId);
        //$frm->addSelectBox(Labels::getLabel('LBL_Category_Status', $this->adminLangId), 'prodcat_active', $activeInactiveArr, '', array(), '');
        $frm->addRadioButtons(Labels::getLabel('LBL_Category_Status',$this->adminLangId), 'prodcat_active', $activeInactiveArr, '', array('class' => 'list-inline'));   
        $fld = $frm->addTextBox(Labels::getLabel('LBL_Category_SEO_Friendly_URL', $this->adminLangId), 'urlrewrite_custom');
        $fld->requirements()->setRequired();
        
        //$frm->addCheckBox(Labels::getLabel('LBL_Featured',$this->adminLangId), 'prodcat_featured', 1, array(),false,0); 
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $this->adminLangId));

        return $frm;
    } */

    /* private function getLangForm($prodcat_id = 0, $lang_id = 0)
    {
        $prodcat_id = FatUtility::int($prodcat_id);

        $srch = ProductCategory::getSearchObject(true);
        $srch->addCondition('m.prodcat_id', '=', $prodcat_id);
        $srch->addCondition('m.prodcat_parent', '=', 0);

        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        $frm = new Form('frmProdCatLang', array('id'=>'frmProdCatLang'));
        $frm->addHiddenField('', 'prodcat_id', $prodcat_id);
        $frm->addSelectBox(Labels::getLabel('LBL_LANGUAGE', $this->adminLangId), 'lang_id', Language::getAllNames(), $lang_id, array(), '');
        $frm->addRequiredField(Labels::getLabel('LBL_Category_Name', $this->adminLangId), 'prodcat_name');

        $siteLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
        $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');

        if (!empty($translatorSubscriptionKey) && $lang_id == $siteLangId) {
            $frm->addCheckBox(Labels::getLabel('LBL_UPDATE_OTHER_LANGUAGES_DATA', $this->adminLangId), 'auto_update_other_langs_data', 1, array(), false, 0);
        }
        
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Update', $this->adminLangId));
        return $frm;
    } */

    /* private function getContentBlockForm($prodcat_id)
    {
        $frm = new Form('frmProdContentBlock');
        $frm->addHiddenField('', 'prodcat_id', $prodcat_id);

        $srch = ContentPage::getListingObj($this->adminLangId, array('cpage_id'));
        $rs = $srch->getResultSet();
        $data = FatApp::getDb()->fetchAllAssoc($rs);

        $cmsPages = $data;
        $frm->addSelectBox(Labels::getLabel('LBL_Content_Block', $this->adminLangId), 'active', $cmsPages, '', array(), '');

        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Update', $this->adminLangId));
        return $frm;
    } */

    /* private function getMediaForm( $prodcat_id = 0 ){
        $frm = new Form('frmCatMedia');
        $frm->addHiddenField('','prodcat_id',$prodcat_id);

        $fld = $frm->addButton( 'Category Image', 'cat_image', 'Upload File', array('class'=>'catFile-Js', 'id' => 'cat_image', 'data-file_type' => AttachedFile::FILETYPE_CATEGORY_IMAGE, 'data-prodcat_id'=>$prodcat_id ));

        $fld1 =  $frm->addButton('Icon','cat_icon','Upload file',array('class'=>'catFile-Js','id'=>'cat_icon','data-file_type'=>AttachedFile::FILETYPE_CATEGORY_ICON,'data-prodcat_id'=>$prodcat_id));

        $fld2 =  $frm->addButton('Banner','cat_banner','Upload file',array('class'=>'catFile-Js','id'=>'cat_banner','data-file_type'=>AttachedFile::FILETYPE_CATEGORY_BANNER,'data-prodcat_id'=>$prodcat_id));
        return $frm;
        } */

        /* private function getMediaForm( $prodcat_id = 0 ){
        $frm = new Form('frmCatMedia');
        $frm->addHTML( '', 'cat_image_heading', '' );
        $languagesAssocArr = Language::getAllNames();

        foreach( $languagesAssocArr as $lang_id => $lang_name ){
        if( $this->canEdit ){
                    $frm->addButton('Image'.' ('.$lang_name.')', 'cat_image_'.$lang_id,'Upload',
                        array('class'=>'catFile-Js','id'=>'cat_image','data-file_type'=>AttachedFile::FILETYPE_CATEGORY_IMAGE,'lang_id' =>$lang_id, 'prodcat_id' =>$prodcat_id));
        } else {
                    $frm->addHtml('','cat_image_'.$lang_id, 'Category Image ('. $lang_name .')');
        }
        $frm->addHtml('','cat_image_display_div_'.$lang_id, '');
        }

        $frm->addHTML( '', 'cat_icon_heading', '' );
        foreach( $languagesAssocArr as $lang_id => $lang_name ){
        if( $this->canEdit ){
                    $frm->addButton('Icon'.' ('. $lang_name .')','cat_icon_'.$lang_id,'Upload Icon',array('class'=>'catFile-Js','id'=>'cat_icon','data-file_type'=>AttachedFile::FILETYPE_CATEGORY_ICON,'lang_id' =>$lang_id,'prodcat_id'=>$prodcat_id));
        } else {
                    $frm->addHtml('','cat_icon_'.$lang_id, 'Category Icon ('. $lang_name .')');
        }
        $frm->addHtml('','cat_icon_display_div_'.$lang_id, '');
        }

        $frm->addHTML( '', 'cat_banner_heading', '' );
        foreach( $languagesAssocArr as $lang_id => $lang_name ){
        if( $this->canEdit ){
                    $frm->addButton('Banner'.' ('. $lang_name .')','cat_banner_'.$lang_id,'Upload Banner',array('class'=>'catFile-Js','id'=>'cat_banner','data-file_type'=>AttachedFile::FILETYPE_CATEGORY_BANNER,'lang_id' =>$lang_id,'prodcat_id'=>$prodcat_id));
        } else {
                    $frm->addHtml('','cat_banner_'.$lang_id, 'Category Banner ('. $lang_name .')');
        }
        $frm->addHtml('','cat_banner_display_div_'.$lang_id, '');
        }
        return $frm;
    } */

    /* private function getCategoryImageForm($prodcat_id = 0, $lang_id = 0)
    {
        $prodcat_id = FatUtility::int($prodcat_id);
        $lang_id = FatUtility::int($lang_id);
        $frm = new Form('frmCategoryImage');
        $frm->addHTML('', Labels::getLabel('LBL_Image', $this->adminLangId), '<h3>'.Labels::getLabel('LBL_Image', $this->adminLangId).'</h3>');
        $frm->addHiddenField('', 'prodcat_id', $prodcat_id);
        $bannerTypeArr = applicationConstants::bannerTypeArr();
        $frm->addSelectBox(Labels::getLabel('LBL_Language', $this->adminLangId), 'lang_id', $bannerTypeArr, '', array(), '');
        $fld = $frm->addButton(
            Labels::getLabel('LBL_Image', $this->adminLangId),
            'cat_image',
            Labels::getLabel('LBL_Upload', $this->adminLangId),
            array('class'=>'catFile-Js','id'=>'shop_logo','data-file_type'=>AttachedFile::FILETYPE_CATEGORY_IMAGE,'data-frm'=>'frmCategoryImage')
        );
        return $frm;
    } */

    /* private function getCategoryIconForm($prodcat_id = 0, $lang_id = 0)
    {
        $prodcat_id = FatUtility::int($prodcat_id);
        $lang_id = FatUtility::int($lang_id);
        $frm = new Form('frmCategoryIcon');
        $frm->addHTML('', Labels::getLabel('LBL_Icon', $this->adminLangId), '<h3>'.Labels::getLabel('LBL_Icon', $this->adminLangId).'</h3>');
        $frm->addHiddenField('', 'prodcat_id', $prodcat_id);
        $bannerTypeArr = applicationConstants::bannerTypeArr();
        $frm->addSelectBox(Labels::getLabel('LBL_Language', $this->adminLangId), 'lang_id', $bannerTypeArr, '', array(), '');
        $fld = $frm->addButton(
            Labels::getLabel('LBL_Icon', $this->adminLangId),
            'cat_icon',
            Labels::getLabel('LBL_Upload', $this->adminLangId),
            array('class'=>'catFile-Js','id'=>'shop_logo','data-file_type'=>AttachedFile::FILETYPE_CATEGORY_ICON,'data-frm'=>'frmCategoryIcon')
        );
        return $frm;
    } */

    /* private function getCategoryBannerForm($prodcat_id = 0, $lang_id = 0)
    {
        $prodcat_id = FatUtility::int($prodcat_id);
        $lang_id = FatUtility::int($lang_id);
        $frm = new Form('frmCategoryBanner');
        $frm->addHTML('', Labels::getLabel('LBL_Banner', $this->adminLangId), '<h3>'.Labels::getLabel('LBL_Banner', $this->adminLangId).'</h3>');
        $frm->addHiddenField('', 'prodcat_id', $prodcat_id);
        $bannerTypeArr = applicationConstants::bannerTypeArr();
        $frm->addSelectBox(Labels::getLabel('LBL_Language', $this->adminLangId), 'lang_id', $bannerTypeArr, '', array(), '');
        $screenArr = applicationConstants::getDisplaysArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel("LBL_Display_For", $this->adminLangId), 'slide_screen', $screenArr, '', array(), '');
        $fld = $frm->addButton(
            Labels::getLabel('LBL_Banner', $this->adminLangId),
            'cat_banner',
            Labels::getLabel('LBL_Upload', $this->adminLangId),
            array('class'=>'catFile-Js','id'=>'shop_logo','data-file_type'=>AttachedFile::FILETYPE_CATEGORY_BANNER,'data-frm'=>'frmCategoryBanner')
        );
        return $frm;
    } */

    /* public function langSetup()
    {
        $this->objPrivilege->canEditProductCategories();
        $post=FatApp::getPostedData();
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $prodcat_id = $post['prodcat_id'];
        $lang_id = $post['lang_id'];

        if ($prodcat_id==0 || $lang_id==0) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }

        $frm = $this->getLangForm($prodcat_id, $lang_id);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        unset($post['prodcat_id']);
        unset($post['lang_id']);
        $data = array(
        'prodcatlang_lang_id'=>$lang_id,
        'prodcatlang_prodcat_id'=>$prodcat_id,
        'prodcat_name'=> $post['prodcat_name'],
        //'prodcat_description'=>$post['prodcat_description'],
        );

        if (isset($post['prodcat_content_block'])) {
            $data['prodcat_content_block'] = $post['prodcat_content_block'];
        }

        $prodCatObj=new ProductCategory($prodcat_id);
        if (!$prodCatObj->updateLangData($lang_id, $data)) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        
        $autoUpdateOtherLangsData = FatApp::getPostedData('auto_update_other_langs_data', FatUtility::VAR_INT, 0);
        if (0 < $autoUpdateOtherLangsData) {
            $updateLangDataobj = new TranslateLangData(ProductCategory::DB_TBL_LANG);
            if (false === $updateLangDataobj->updateTranslatedData($prodcat_id)) {
                Message::addErrorMessage($updateLangDataobj->getError());
                FatUtility::dieWithError(Message::getHtml());
            }
        }

        $newTabLangId=0;
        $languages=Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            if (!$row=ProductCategory::getAttributesByLangId($langId, $prodcat_id)) {
                $newTabLangId = $langId;
                break;
            }
        }
        if ($newTabLangId == 0 && !$this->isMediaUploaded($prodcat_id)) {
            $this->set('openMediaForm', true);
        }
        $this->set('msg', Labels::getLabel('LBL_Category_Setup_Successful', $this->adminLangId));
        $this->set('catId', $prodcat_id);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    } */

    /* private function isMediaUploaded($prodcat_id)
    {
        $banner = AttachedFile::getAttachment(AttachedFile::FILETYPE_CATEGORY_BANNER, $prodcat_id, 0);
        $icon = AttachedFile::getAttachment(AttachedFile::FILETYPE_CATEGORY_ICON, $prodcat_id, 0);

        if ($banner && $icon) {
            return true;
        }

        return false;
    } */
    
     /* public function updateOrder()
    {
        $this->objPrivilege->canEditProductCategories();

        $post=FatApp::getPostedData();
        if (!empty($post)) {
            $prodCateObj = new ProductCategory();
            if (!$prodCateObj->updateOrder($post['prodcat'])) {
                Message::addErrorMessage($prodCateObj->getError());
                FatUtility::dieJsonError(Message::getHtml());
            }
            ProductCategory::updateCatOrderCode();
            $this->set('msg', Labels::getLabel('LBL_Order_Updated_Successfully', $this->adminLangId));
            $this->_template->render(false, false, 'json-success.php');
        }
    } */

}
