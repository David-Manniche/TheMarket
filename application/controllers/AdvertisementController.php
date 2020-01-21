<?php
class AdvertisementController extends LoggedUserController
{
    private $keyName;
    private $userId;

    public function __construct($action)
    {
        parent::__construct($action);
        $obj = new Plugin();
        $this->keyName = $obj->getDefaultPluginKeyName(Plugin::TYPE_ADVERTISEMENT_FEED_API);
        if (empty($this->keyName)) {
            Message::addErrorMessage(Labels::getLabel('MSG_NO_ADVERTISEMENT_PLUGIN_FOUND', $this->siteLangId));
            FatApp::redirectUser(CommonHelper::generateUrl('Seller'));
        }
        $isActive = Plugin::isActive($this->keyName);
        if (1 > $isActive) {
            Message::addErrorMessage(Labels::getLabel('MSG_NO_ADVERTISEMENT_PLUGIN_ACTIVE', $this->siteLangId));
            FatApp::redirectUser(CommonHelper::generateUrl('Seller'));
        }
        $this->userId = UserAuthentication::getLoggedUserId();
        require_once(CONF_PLUGIN_DIR . 'advertisement-feed/' . strtolower($this->keyName) . '/' . $this->keyName . '.php');
    }

    private function getForm()
    {
        $frm = new Form('frmAdsBatch');
        $frm->addHiddenField('', 'adsbatch_id');
        $frm->addRequiredField(Labels::getLabel('LBL_BATCH_NAME', $this->siteLangId), 'adsbatch_name');
        $fld = $frm->addSelectBox(Labels::getLabel('LBL_LANGUAGE', $this->siteLangId), 'adsbatch_lang_id', Language::getAllNames());
        $fld->requirement->setRequired(true);

        $countryObj = new Countries();
        $countriesArr = $countryObj->getCountriesArr($this->siteLangId);
        $fld = $frm->addSelectBox(Labels::getLabel('LBL_TARGET_COUNTRY', $this->siteLangId), 'adsbatch_target_country_id', $countriesArr);
        $fld->requirement->setRequired(true);
        
        $frm->addDateField(Labels::getLabel('LBL_EXPIRY_DATE', $this->siteLangId), 'adsbatch_expired_on', '', array('readonly' => 'readonly'));

        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->siteLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear', $this->siteLangId), array('onclick' => 'clearForm();'));
        // $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    private function getBindProductsFrm()
    {
        $frm = new Form('frm');
        $frm->addHiddenField('', 'abprod_selprod_id');
        $frm->addHiddenField('', 'abprod_cat_id');
        $frm->addHiddenField('', 'abprod_adsbatch_id');
        $fld = $frm->addTextBox(Labels::getLabel('LBL_PRODUCT', $this->siteLangId), 'product_name');
        $fld->requirement->setRequired(true);
        $fld = $frm->addTextBox(Labels::getLabel('LBL_GOOGLE_PRODUCT_CATEGORY', $this->siteLangId), 'google_product_category');
        $fld->requirement->setRequired(true);

        $fld = $frm->addSelectBox(Labels::getLabel('LBL_AGE_GROUP', $this->siteLangId), 'abprod_age_group', $this->keyName::ageGroup($this->siteLangId));
        $fld->requirement->setRequired(true);

        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->siteLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear', $this->siteLangId));
        // $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    public function getPluginForm()
    {
        try {
            if (true == method_exists($this->keyName, 'form')) {
                $data = User::getUserMeta($this->userId);
                $frm = $this->keyName::form($this->siteLangId);
                if (!empty($data) && 0 < count($data)) {
                    $frm->fill($data);
                }
                $this->set('frm', $frm);
                $this->_template->render(false, false);
            }
        } catch (\Error $e) {
            FatUtility::dieWithError('ERR - ' . $e->getMessage());
        }
        return false;
    }

    private function validateBatchRequest($adsBatchId)
    {
        $recordData = AdsBatch::getBatchesByUserId($this->userId, $adsBatchId);
        $status = AdsBatch::getAttributesById($adsBatchId, 'adsbatch_status');
        if (1 > $adsBatchId || empty($recordData) || AdsBatch::STATUS_PENDING != $status) {
            $this->error = Labels::getLabel("LBL_INVALID_REQUEST", $this->siteLangId);
            return false;
        }
        return true;
    }

    public function index()
    {
        $pluginName = Plugin::getAttributesByCode($this->keyName, 'plugin_identifier');
        $userData = User::getUserMeta($this->userId);
        $this->set('havePluginFrm', method_exists($this->keyName, 'form'));
        $this->set('userData', $userData);
        $this->set('keyName', $this->keyName);
        $this->set('pluginName', $pluginName);
        $this->_template->render();
    }
    
    public function form($adsBatchId = 0)
    {
        $adsBatchId = FatUtility::int($adsBatchId);
        $prodBatchAdsFrm = $this->getForm($adsBatchId);

        if (0 < $adsBatchId) {
            $data = AdsBatch::getAttributesById($adsBatchId);
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $prodBatchAdsFrm->fill($data);
        }

        $this->set('frm', $prodBatchAdsFrm);
        $this->_template->render(false, false);
    }

    public function bindProducts($adsBatchId)
    {
        $adsBatchId = FatUtility::int($adsBatchId);
        $this->set('adsBatchId', $adsBatchId);
        $this->_template->render();
    }

    public function bindProductForm($adsBatchId, $selProdId = 0)
    {
        $adsBatchId = FatUtility::int($adsBatchId);
        $selProdId = FatUtility::int($selProdId);
        if (false === $this->validateBatchRequest($adsBatchId)) {
            Message::addErrorMessage($this->error);
            FatApp::redirectUser(CommonHelper::generateUrl('Advertisement'));
        }

        $frm = $this->getBindProductsFrm();
        $data = ['abprod_adsbatch_id' => $adsBatchId];
        if (1 < $selProdId) {
            $data = AdsBatch::getBatchProdDetail($adsBatchId, $selProdId);
            $categoryArr = $this->getProductCategory(true);
            $selProdData = SellerProduct::getSelProdDataById($selProdId, $this->siteLangId);
            $data['google_product_category'] = $categoryArr[$data['abprod_cat_id']];
            $data['product_name'] = $selProdData['selprod_title'];
        }
        $frm->fill($data);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setup()
    {
        $frm = $this->getForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());

        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $adsBatchId = $post['adsbatch_id'];
        if (0 < $adsBatchId) {
            if (false === $this->validateBatchRequest($adsBatchId)) {
                FatUtility::dieJsonError($this->error);
            }
        }
        unset($post['adsbatch_id']);
        $post['adsbatch_user_id'] = UserAuthentication::getLoggedUserId();
        $adsBatchObj = new AdsBatch($adsBatchId);
        $adsBatchObj->assignValues($post);

        if (!$adsBatchObj->save()) {
            Message::addErrorMessage($adsBatchObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        FatUtility::dieJsonSuccess(Labels::getLabel('MSG_ADS_BATCH_SETUP_SUCCESSFULLY', $this->siteLangId));
    }

    public function setupPluginForm()
    {
        try {
            if (true == method_exists($this->keyName, 'form')) {
                $frm = $this->keyName::form($this->siteLangId);
                $post = $frm->getFormDataFromArray(FatApp::getPostedData());
                unset($post['btn_submit']);
                $uObj = new User(UserAuthentication::getLoggedUserId());
                foreach ($post as $key => $value) {
                    $uObj->updateUserMeta($key, trim($value));
                }
                FatUtility::dieJsonSuccess(Labels::getLabel('MSG_UPDATED_SUCCESSFULLY', $this->siteLangId));
            }
        } catch (\Error $e) {
            FatUtility::dieWithError('ERR - ' . $e->getMessage());
        }
        FatUtility::dieJsonError(Labels::getLabel("MSG_UNABLE_TO_UPDATE", $this->siteLangId));
    }

    public function setupProductsToBatch()
    {
        $frm = $this->getBindProductsFrm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());

        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        
        $productId = SellerProduct::getAttributesById($post['abprod_selprod_id'], 'selprod_product_id');
        $productIdentifier = strtoupper(Product::getAttributesById($productId, 'product_identifier'));
        $productIdentifier = explode(' ', $productIdentifier) ;
        $post['abprod_item_group_identifier'] = $productIdentifier[0] . $productId;

        unset($post['btn_submit'], $post['product_name'], $post['btn_clear'], $post['google_product_category']);
        $db = FatApp::getDb();
        if (!$db->insertFromArray(AdsBatch::DB_TBL_BATCH_PRODS, $post, false, array(), $post)) {
            FatUtility::dieJsonError($db->getError());
        }

        FatUtility::dieJsonSuccess(Labels::getLabel('MSG_ADS_BATCH_SETUP_SUCCESSFULLY', $this->siteLangId));
    }

    public function search()
    {
        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        $keyword = FatApp::getPostedData('keyword', FatUtility::VAR_STRING, '');

        $srch = AdsBatch::getSearchObject();
        $attr = [
            'adsbatch_id',
            'adsbatch_name',
            'adsbatch_lang_id',
            'adsbatch_target_country_id',
            'adsbatch_expired_on',
            'adsbatch_synced_on',
            'adsbatch_status',
        ];
        $srch->addMultipleFields($attr);
        $srch->addCondition(AdsBatch::DB_TBL_PREFIX . 'user_id', '=', $this->userId);
        $srch->addCondition(AdsBatch::DB_TBL_PREFIX . 'status', '!=', AdsBatch::STATUS_DELETED);
        if (!empty($keyword)) {
            $srch->addCondition(AdsBatch::DB_TBL_PREFIX . 'name', 'LIKE', '%' . $keyword . '%');
        }
        $srch->setPageNumber($page);

        $db = FatApp::getDb();
        $rs = $srch->getResultSet();
        $arrListing = $db->fetchAll($rs);
        $this->set("arrListing", $arrListing);

        $this->set('page', $page);
        $this->set('pageCount', $srch->pages());
        $this->set('postedData', FatApp::getPostedData());
        $this->set('recordCount', $srch->recordCount());
        $this->set('pageSize', FatApp::getConfig('CONF_PAGE_SIZE', FatUtility::VAR_INT, 10));
        $this->_template->render(false, false);
    }

    public function deleteBatch($adsBatchId)
    {
        $adsBatchId = FatUtility::int($adsBatchId);

        if (false === $this->validateBatchRequest($adsBatchId)) {
            LibHelper::dieJsonError($this->error);
        }

        $adsBatchObj = new adsBatch($adsBatchId);
        $adsBatchObj->assignValues(['adsbatch_status' => AdsBatch::STATUS_DELETED]);

        if (!$adsBatchObj->save()) {
            Message::addErrorMessage($adsBatchObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        FatUtility::dieJsonSuccess(Labels::getLabel('MSG_SUCCESSFULLY_DELETED', $this->siteLangId));
    }

    private function getBatchProductsObj($adsBatchId)
    {
        $srch = AdsBatch::getSearchObject(true);
        $srch->addCondition(AdsBatch::DB_TBL_BATCH_PRODS_PREFIX . 'adsbatch_id', '=', $adsBatchId);
        $srch->addCondition(AdsBatch::DB_TBL_PREFIX . 'user_id', '=', $this->userId);
        return $srch;
    }

    public function searchProducts($adsBatchId)
    {
        $adsBatchId = FatUtility::int($adsBatchId);
        if (1 > $adsBatchId) {
            LibHelper::dieJsonError(Labels::getLabel('LBL_INVALID_REQUEST', $this->siteLangId));
        }

        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        $keyword = FatApp::getPostedData('keyword', FatUtility::VAR_STRING, '');

        $attr = [
            'abprod_adsbatch_id',
            'abprod_selprod_id',
            'IFNULL(selprod_title  ,IFNULL(product_name, product_identifier)) as selprod_title',
            'product_identifier',
            'adsbatch_name',
            'abprod_cat_id',
            'abprod_age_group',
            'abprod_item_group_identifier',
        ];

        $srch = $this->getBatchProductsObj($adsBatchId);
        $srch->addMultipleFields($attr);

        if (!empty($keyword)) {
            $srch->addCondition(AdsBatch::DB_TBL_PREFIX . 'name', 'LIKE', '%' . $keyword . '%');
        }
        $srch->setPageNumber($page);

        $db = FatApp::getDb();
        $rs = $srch->getResultSet();
        $arrListing = $db->fetchAll($rs);
        $this->set("arrListing", $arrListing);

        $this->set('page', $page);
        $this->set('pageCount', $srch->pages());
        $this->set('postedData', FatApp::getPostedData());
        $this->set('recordCount', $srch->recordCount());
        $this->set('pageSize', FatApp::getConfig('CONF_PAGE_SIZE', FatUtility::VAR_INT, 10));
        $this->set('catIdArr', $this->getProductCategory(true));
        $this->_template->render(false, false);
    }

    public function unlinkProduct($adsBatchId, $selProdId, $return = false)
    {
        $adsBatchId = FatUtility::int($adsBatchId);
        $selProdId = FatUtility::int($selProdId);

        if (false === $this->validateBatchRequest($adsBatchId)) {
            LibHelper::dieJsonError($this->error);
        }

        $db = FatApp::getDb();
        if (!$db->deleteRecords(AdsBatch::DB_TBL_BATCH_PRODS, ['smt' => 'abprod_adsbatch_id = ? AND abprod_selprod_id = ?', 'vals' => [$adsBatchId, $selProdId]])) {
            LibHelper::dieJsonError($db->getError());
        }

        if (true == $return) {
            return true;
        }
        FatUtility::dieJsonSuccess(Labels::getLabel('MSG_SUCCESSFULLY_DELETED', $this->siteLangId));
    }

    public function unlinkProducts($adsBatchId)
    {
        $adsBatchId = FatUtility::int($adsBatchId);
        $sellerProducts = FatApp::getPostedData('selprod_ids');
        if (1 > $adsBatchId || !is_array($sellerProducts) || 1 > count($sellerProducts)) {
            LibHelper::dieJsonError(Labels::getLabel("LBL_INVALID_REQUEST", $this->siteLangId));
        }

        foreach ($sellerProducts as $selProdId) {
            $this->unlinkProduct($adsBatchId, $selProdId, true);
        }
        FatUtility::dieJsonSuccess(Labels::getLabel('MSG_SUCCESSFULLY_DELETED', $this->siteLangId));
    }

    public function getProductCategory($returnFullArray = false)
    {
        $keyword = FatApp::getPostedData('keyword', FatUtility::VAR_STRING, '');
        try {
            $obj = new $this->keyName();
            $data = $obj->getProductCategory($keyword, $returnFullArray);
        } catch (\Error $e) {
            $message = 'ERR - ' . $e->getMessage();
            LibHelper::dieJsonError($message);
        }
        if (true === $returnFullArray) {
            return $data;
        }
        echo json_encode($data);
        exit;
    }

    public function publishBatch($adsBatchId)
    {
        $adsBatchId = FatUtility::int($adsBatchId);
        if (false === $this->validateBatchRequest($adsBatchId)) {
            Message::addErrorMessage($this->error);
            FatApp::redirectUser(CommonHelper::generateUrl('Advertisement'));
        }

        $db = FatApp::getDb();
        $srch = $this->getBatchProductsObj($adsBatchId);
        $rs = $srch->getResultSet();
        $productData = $db->fetchAll($rs);
        if (empty($productData)) {
            Message::addErrorMessage(Labels::getLabel("MSG_PLEASE_ADD_ATLEAST_ONE_PRODUCT_TO_THE_BATCH", $this->siteLangId));
            FatApp::redirectUser(CommonHelper::generateUrl('Advertisement'));
        }

        foreach ($productData as &$prodDetail) {
            $srch = new SearchBase(SellerProduct::DB_TBL_SELLER_PROD_OPTIONS, 'spo');
            $srch->joinTable(OptionValue::DB_TBL, 'INNER JOIN', 'spo.selprodoption_optionvalue_id = ov.optionvalue_id', 'ov');
            $srch->joinTable(OptionValue::DB_TBL . '_lang', 'LEFT OUTER JOIN', 'ov_lang.optionvaluelang_optionvalue_id = ov.optionvalue_id AND ov_lang.optionvaluelang_lang_id = ' . $this->siteLangId, 'ov_lang');
            $srch->joinTable(Option::DB_TBL, 'INNER JOIN', 'o.option_id = ov.optionvalue_option_id', 'o');
            $srch->joinTable(Option::DB_TBL . '_lang', 'LEFT OUTER JOIN', 'o.option_id = o_lang.optionlang_option_id AND o_lang.optionlang_lang_id = ' . $this->siteLangId, 'o_lang');
            $srch->addMultipleFields(array('optionvalue_identifier', 'option_is_color', 'option_name'));
            $srch->addCondition('selprodoption_selprod_id', '=', $prodDetail['selprod_id']);
            $rs = $srch->getResultSet();
            $prodDetail['optionsData'] = $db->fetchAll($rs);
        }

        try {
            $obj = new $this->keyName();
            $data = [
                'batchId' => $adsBatchId,
                'siteLangId' => $this->siteLangId,
                'siteCurrencyId' => $this->siteCurrencyId,
                'data' => $productData
            ];
            $response = $obj->publishBatch($data);
            if (false === $response) {
                Message::addErrorMessage($obj->getError());
                FatApp::redirectUser(CommonHelper::generateUrl('Advertisement'));
            }
        } catch (\Error $e) {
            $message = 'ERR - ' . $e->getMessage();
            LibHelper::dieJsonError($message);
        }
        $dataToUpdate = [
            'adsbatch_status' => AdsBatch::STATUS_PUBLISHED,
            'adsbatch_synced_on' => date('Y-m-d H:i:s')
        ];
        if (false === AdsBatch::updateDetail($adsBatchId, $dataToUpdate)) {
            Message::addErrorMessage(Labels::getLabel("MSG_UNABLE_TO_UPDATE", $this->siteLangId));
        } else {
            Message::addMessage($response['msg']);
        }
        FatApp::redirectUser(CommonHelper::generateUrl('Advertisement'));
    }
}
