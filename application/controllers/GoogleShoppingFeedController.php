<?php

include_once CONF_INSTALLATION_PATH . 'library/GoogleAPI/vendor/autoload.php';

class GoogleShoppingFeedController extends AdvertisementFeedBaseController
{
    public const KEY_NAME = 'GoogleShoppingFeed';
    public const SCOPE = 'https://www.googleapis.com/auth/content';

    private $client;
    private $pluginData;

    public $requiredKeys = [
        'client_id',
        'client_secret',
        'developer_key'
    ];

    public function __construct($action)
    {
        parent::__construct($action);
        $error = '';
        if (false === PluginHelper::includePlugin(self::KEY_NAME, 'advertisement-feed', $this->siteLangId, $error)) {
            Message::addErrorMessage($error);
            $this->redirectBack();
        }
    }

    public function index()
    {
        $this->set('userData', $this->getUserMeta());
        $this->set('keyName', self::KEY_NAME);
        $this->set('pluginName', $this->getPluginData('plugin_name'));
        $this->_template->render();
    }

    private function setupConfiguration()
    {
        if (false == $this->validateSettings($this->siteLangId)) {
            $this->redirectBack();
            return false;
        }
        
        $this->client = new Google_Client();
        $this->client->setApplicationName(FatApp::getConfig('CONF_WEBSITE_NAME_' . $this->siteLangId)); // Set your application name
        $this->client->setScopes(self::SCOPE);
        $this->client->setClientId($this->settings['client_id']);
        $this->client->setClientSecret($this->settings['client_secret']);
        $this->client->setRedirectUri(CommonHelper::generateFullUrl(static::KEY_NAME, 'getAccessToken', [], '', false));
        $this->client->setDeveloperKey($this->settings['developer_key']);
        $this->client->setAccessType('offline');
        $this->client->setApprovalPrompt('force');
    }

    public function getAccessToken()
    {
        $this->setupConfiguration();

        $get = FatApp::getQueryStringData();
        if (isset($get['code'])) {
            $this->client->authenticate($get['code']);
            $accessToken = $this->client->getAccessToken();
            $merchantId = $this->getUserMeta(self::KEY_NAME . '_merchantId');
            if (!empty($accessToken)) {
                $this->setupMerchantDetail($accessToken);
            }
            CommonHelper::redirectUserReferer();
        }
        $authUrl = $this->client->createAuthUrl();
        FatApp::redirectUser($authUrl);
    }

    private function setupMerchantDetail($accessToken)
    {
        $this->client->setAccessToken($accessToken);
        $service = new Google_Service_ShoppingContent($this->client);
        $authDetail = $service->accounts->authinfo();
        $accountDetail = $authDetail->accountIdentifiers;
        if (empty($accountDetail)) {
            Message::addErrorMessage(Labels::getLabel("MSG_MERCHANT_ACCOUNT_DETAIL_NOT_FOUND", $this->siteLangId));
            $this->redirectBack();
        }
        $merchantId = array_shift($accountDetail)->merchantId;
        $this->updateMerchantInfo([self::KEY_NAME . '_merchantId' => $merchantId]);
    }

    private function getServiceAccountForm()
    {
        $frm = new Form('frmServiceAccount');
        $privateKey = $frm->addTextArea(Labels::getLabel('LBL_SERVICE_ACCOUNT_DETAIL', $this->siteLangId), 'service_account');
        $privateKey->requirements()->setRequired();
        $privateKey->htmlAfterField = $this->getPluginData('plugin_description');
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $this->siteLangId));
        return $frm;
    }

    public function serviceAccountForm()
    {
        $data = $this->getUserMeta();
        $frm = $this->getServiceAccountForm();
        if (!empty($data) && 0 < count($data)) {
            $frm->fill($data);
        }
        $this->set('frm', $frm);
        $this->set('keyName', self::KEY_NAME);
        $this->_template->render(false, false);
    }

    public function setupServiceAccountForm()
    {
        $frm = $this->getServiceAccountForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        unset($post['btn_submit']);
        $this->updateMerchantInfo($post, false);
    }

    private function validateBatchRequest($adsBatchId)
    {
        $recordData = AdsBatch::getBatchesByUserId(UserAuthentication::getLoggedUserId(), $adsBatchId);
        $status = AdsBatch::getAttributesById($adsBatchId, 'adsbatch_status');
        if (1 > $adsBatchId || empty($recordData) || AdsBatch::STATUS_PENDING != $status) {
            $this->error = Labels::getLabel("LBL_INVALID_REQUEST", $this->siteLangId);
            return false;
        }
        return true;
    }

    private function getBatchForm()
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

        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->siteLangId));
        $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear', $this->siteLangId), array('onclick' => 'clearForm();'));
        return $frm;
    }

    private function getBindProductForm()
    {
        $frm = new Form('frm');
        $frm->addHiddenField('', 'abprod_selprod_id');
        $frm->addHiddenField('', 'abprod_cat_id');
        $frm->addHiddenField('', 'abprod_adsbatch_id');
        $fld = $frm->addTextBox(Labels::getLabel('LBL_PRODUCT', $this->siteLangId), 'product_name');
        $fld->requirement->setRequired(true);
        $fld = $frm->addTextBox(Labels::getLabel('LBL_GOOGLE_PRODUCT_CATEGORY', $this->siteLangId), 'google_product_category');
        $fld->requirement->setRequired(true);

        $fld = $frm->addSelectBox(Labels::getLabel('LBL_AGE_GROUP', $this->siteLangId), 'abprod_age_group', (self::KEY_NAME)::ageGroup($this->siteLangId));
        $fld->requirement->setRequired(true);

        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->siteLangId));
        $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear', $this->siteLangId));
        return $frm;
    }
    
    public function batchForm($adsBatchId = 0)
    {
        $adsBatchId = FatUtility::int($adsBatchId);
        $prodBatchAdsFrm = $this->getBatchForm($adsBatchId);

        if (0 < $adsBatchId) {
            $data = AdsBatch::getAttributesById($adsBatchId);
            if ($data === false) {
                LibHelper::dieJsonError($this->str_invalid_request);
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
            $this->redirectBack();
        }

        $frm = $this->getBindProductForm();
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

    public function setupBatch()
    {
        $frm = $this->getBatchForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());

        if (false === $post) {
            LibHelper::dieJsonError(current($frm->getValidationErrors()));
        }

        $adsBatchId = $post['adsbatch_id'];
        if (0 < $adsBatchId) {
            if (false === $this->validateBatchRequest($adsBatchId)) {
                LibHelper::dieJsonError($this->error);
            }
        }
        unset($post['adsbatch_id']);
        $post['adsbatch_user_id'] = UserAuthentication::getLoggedUserId();
        $adsBatchObj = new AdsBatch($adsBatchId);
        $adsBatchObj->assignValues($post);

        if (!$adsBatchObj->save()) {
            LibHelper::dieJsonError($adsBatchObj->getError());
        }

        FatUtility::dieJsonSuccess(Labels::getLabel('MSG_ADS_BATCH_SETUP_SUCCESSFULLY', $this->siteLangId));
    }

    public function setupProductsToBatch()
    {
        $frm = $this->getBindProductForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());

        if (false === $post) {
            LibHelper::dieJsonError(current($frm->getValidationErrors()));
        }
        
        $productId = SellerProduct::getAttributesById($post['abprod_selprod_id'], 'selprod_product_id');
        $productIdentifier = strtoupper(Product::getAttributesById($productId, 'product_identifier'));
        $productIdentifier = explode(' ', $productIdentifier);
        $post['abprod_item_group_identifier'] = $productIdentifier[0] . $productId;

        unset($post['btn_submit'], $post['product_name'], $post['btn_clear'], $post['google_product_category']);
        $db = FatApp::getDb();
        if (!$db->insertFromArray(AdsBatch::DB_TBL_BATCH_PRODS, $post, false, array(), $post)) {
            LibHelper::dieJsonError($db->getError());
        }

        FatUtility::dieJsonSuccess(Labels::getLabel('MSG_ADS_BATCH_SETUP_SUCCESSFULLY', $this->siteLangId));
    }

    public function search()
    {
        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);

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
        $srch->addCondition(AdsBatch::DB_TBL_PREFIX . 'user_id', '=', UserAuthentication::getLoggedUserId());
        $srch->addCondition(AdsBatch::DB_TBL_PREFIX . 'status', '!=', AdsBatch::STATUS_DELETED);
        $srch->setPageNumber($page);

        $db = FatApp::getDb();
        $rs = $srch->getResultSet();
        $arrListing = $db->fetchAll($rs);
        $this->set("arrListing", $arrListing);

        $this->set('keyName', self::KEY_NAME);
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
            LibHelper::dieJsonError($adsBatchObj->getError());
        }

        FatUtility::dieJsonSuccess(Labels::getLabel('MSG_SUCCESSFULLY_DELETED', $this->siteLangId));
    }

    private function getBatchProductsObj($adsBatchId)
    {
        $srch = AdsBatch::getSearchObject(true);
        $srch->addCondition(AdsBatch::DB_TBL_BATCH_PRODS_PREFIX . 'adsbatch_id', '=', $adsBatchId);
        $srch->addCondition(AdsBatch::DB_TBL_PREFIX . 'user_id', '=', UserAuthentication::getLoggedUserId());
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
            $class = self::KEY_NAME;
            $obj = new $class();
            $data = $obj->getProductCategory($keyword, $returnFullArray);
        } catch (\Error $e) {
            LibHelper::dieJsonError($e->getMessage());
        }
        if (true === $returnFullArray) {
            return $data;
        }
        echo json_encode($data);
        exit;
    }

    private function getData($adsBatchId)
    {
        $db = FatApp::getDb();
        $srch = $this->getBatchProductsObj($adsBatchId);
        
        $srch->addMultipleFields(
            [
                'selprod_id', 'selprod_title', 'selprod_stock', 'selprod_condition', 'selprod_price', 'selprod_available_from', 'product_id', 'product_description', 'product_upc', 'language_code', 'country_code', 'IFNULL(brand_name, brand_identifier) as brand_name', 'abprod_item_group_identifier', 'adsbatch_expired_on', 'abprod_cat_id'
            ]
        );
        $rs = $srch->getResultSet();
        $productData = $db->fetchAll($rs);
        if (empty($productData)) {
            LibHelper::dieJsonError(Labels::getLabel("MSG_PLEASE_ADD_ATLEAST_ONE_PRODUCT_TO_THE_BATCH", $this->siteLangId));
        }

        foreach ($productData as &$prodDetail) {
            $srch = new SearchBase(SellerProduct::DB_TBL_SELLER_PROD_OPTIONS, 'spo');
            $srch->joinTable(OptionValue::DB_TBL, 'INNER JOIN', 'spo.selprodoption_optionvalue_id = ov.optionvalue_id', 'ov');
            $srch->joinTable(OptionValue::DB_TBL . '_lang', 'LEFT OUTER JOIN', 'ov_lang.optionvaluelang_optionvalue_id = ov.optionvalue_id AND ov_lang.optionvaluelang_lang_id = ' . $this->siteLangId, 'ov_lang');
            $srch->joinTable(Option::DB_TBL, 'INNER JOIN', 'o.option_id = ov.optionvalue_option_id', 'o');
            $srch->joinTable(Option::DB_TBL . '_lang', 'LEFT OUTER JOIN', 'o.option_id = o_lang.optionlang_option_id AND o_lang.optionlang_lang_id = ' . $this->siteLangId, 'o_lang');
            $srch->addMultipleFields(['optionvalue_identifier', 'option_is_color', 'option_name']);
            $srch->addCondition('selprodoption_selprod_id', '=', $prodDetail['selprod_id']);
            $rs = $srch->getResultSet();
            $prodDetail['optionsData'] = $db->fetchAll($rs);
            $prodDetail['selprod_condition'] = (Product::getConditionArr($this->siteLangId))[$prodDetail['selprod_condition']];
            $prodDetail['selprod_stock'] = (0 < $prodDetail['selprod_stock'] ? "in stock" : 'out of stock');
        }
        return $productData;
    }

    public function publishBatch($adsBatchId)
    {
        $adsBatchId = FatUtility::int($adsBatchId);
        if (false === $this->validateBatchRequest($adsBatchId)) {
            LibHelper::dieJsonError($this->error);
        }

        $productData = $this->getData($adsBatchId);
        try {
            $class = self::KEY_NAME;
            $obj = new $class();
            $data = [
                'batchId' => $adsBatchId,
                'currency_code' => strtoupper(Currency::getAttributesById($this->siteCurrencyId, 'currency_code')),
                'data' => $productData
            ];
            $response = $obj->publishBatch($data);
            if (false === $response['status']) {
                LibHelper::dieJsonError($obj->getError());
            }
        } catch (\Error $e) {
            LibHelper::dieJsonError($e->getMessage());
        }
        $dataToUpdate = [
            'adsbatch_status' => AdsBatch::STATUS_PUBLISHED,
            'adsbatch_synced_on' => date('Y-m-d H:i:s')
        ];
        if (false === AdsBatch::updateDetail($adsBatchId, $dataToUpdate)) {
            LibHelper::dieJsonError(Labels::getLabel("MSG_UNABLE_TO_UPDATE", $this->siteLangId));
        }

        FatUtility::dieJsonSuccess($response['msg']);
    }
}
