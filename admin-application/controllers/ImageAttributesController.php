<?php

class ImageAttributesController extends AdminBaseController
{
    private $canView;
    private $canEdit;
    public function __construct($action)
    {
        $ajaxCallArray = array('deleteRecord', 'form', 'search', 'setup');
        if (!FatUtility::isAjaxCall() && in_array($action, $ajaxCallArray)) {
            die($this->str_invalid_Action);
        }
        parent::__construct($action);
        $this->admin_id = AdminAuthentication::getLoggedAdminId();
        $this->canView = $this->objPrivilege->canViewImageAttributes($this->admin_id, true);
        $this->canEdit = $this->objPrivilege->canEditImageAttributes($this->admin_id, true);
        $this->set("canView", $this->canView);
        $this->set("canEdit", $this->canEdit);
    }

    public function index()
    {
        $this->objPrivilege->canViewImageAttributes();
        $srchFrm = $this->getSearchForm();
        $this->set("srchFrm", $srchFrm);
        $this->_template->render();
    }

    public function search()
    {
        $this->objPrivilege->canViewImageAttributes();

        $pageSize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);

        $searchForm = $this->getSearchForm();
        $data = FatApp::getPostedData();
        $page = (empty($data['page']) || $data['page'] <= 0) ? 1 : $data['page'];
        $post = $searchForm->getFormDataFromArray($data);

        $srch = AttachedFile::getSearchObject();
		
		if (!empty($post['select_module'])) {
            $cnd = $srch->addCondition('afile_type', '=', $post['select_module']);
        } else {
			$cnd = $srch->addCondition('afile_type', '=', AttachedFile::FILETYPE_PRODUCT_IMAGE);
		}
		
		switch ($post['select_module']) {
            case AttachedFile::FILETYPE_PRODUCT_IMAGE:
                $srch->joinTable(Product::DB_TBL, 'INNER JOIN', 'product_id = afile_record_id', 'p');
				$srch->joinTable(Product::DB_TBL_LANG, 'LEFT OUTER JOIN', 'p.product_id = p_l.productlang_product_id AND p_l.productlang_lang_id = ' . $this->adminLangId, 'p_l');
				$srch->addMultipleFields(
					array('product_id as record_id', 'IFNULL(product_name, product_identifier) as record_name', 'product_identifier as record_identifier', 'afile_type')
				);
                break;
			case AttachedFile::FILETYPE_BRAND_IMAGE:
                $srch->joinTable(Brand::DB_TBL, 'LEFT OUTER JOIN', 'product_id = afile_record_id', 'b');
				$srch->joinTable(Brand::DB_TBL_LANG, 'LEFT OUTER JOIN', 'b.brand_id = b_l.brandlang_brand_id AND b_l.brandlang_lang_id = ' . $this->adminLangId, 'b_l');
				$srch->addMultipleFields(
					array('brand_id as record_id', 'IFNULL(brand_name, brand_identifier) as record_name', 'afile_type')
				);
                break;				
            default:
				$srch->joinTable(ProductCategory::DB_TBL, 'LEFT OUTER JOIN', 'product_id = afile_record_id', 'pc');
				$srch->joinTable(ProductCategory::DB_TBL_LANG, 'LEFT OUTER JOIN', 'pc.prodcat_id = pc_l.prodcatlang_prodcat_id AND pc_l.prodcatlang_lang_id = ' . $this->adminLangId, 'pc_l');
				$srch->addMultipleFields(
					array('prodcat_id as record_id', 'IFNULL(prodcat_name, prodcat_identifier) as record_name', 'afile_type')
				);
				break;
        }
		if (!empty($post['keyword'])) {
			$cnd = $srch->addCondition('record_name', 'like', '%' . $post['keyword'] . '%');
			$cnd->attachCondition('record_identifier', 'like', '%' . $post['keyword'] . '%');
		}
        $srch->addGroupBy('record_id');
		$srch->addOrder('afile_id', 'DESC');
        $page = FatApp::getPostedData('page', FatUtility::VAR_INT, 1);
        if ($page < 2) {
            $page = 1;
        }
        $srch->setPageNumber($page);
        $srch->setPageSize($pageSize);

        $srch->addOrder('afile_id', 'DESC');

        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
        $this->set("arr_listing", $records);
        $this->set('moduleType', (isset($post['select_module'])) ? $post['select_module'] : AttachedFile::FILETYPE_PRODUCT_IMAGE);
        $this->set('pageCount', $srch->pages());
        $this->set('recordCount', $srch->recordCount());
        $this->set('page', $page);
        $this->set('pageSize', $pageSize);
        $this->set('postedData', $post);
        $this->_template->render(false, false);
    }

    public function form($urlrewrite_id = 0)
    {
        $this->objPrivilege->canViewImageAttributes();
        $urlrewrite_id = FatUtility::int($urlrewrite_id);

        $frm = $this->getForm();
        $frm->fill(array('urlrewrite_id' => $urlrewrite_id));

        if (0 < $urlrewrite_id) {
            $srch = UrlRewrite::getSearchObject();
            $srch->addCondition('urlrewrite_id', '=', $urlrewrite_id);
            $rs = $srch->getResultSet();
            $data = FatApp::getDb()->fetch($rs);
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $urlRewriteData = UrlRewrite::getAttributesById($urlrewrite_id);
            // $customUrl  = explode("/", $urlRewriteData['urlrewrite_custom']);
            $data['urlrewrite_custom'] = $urlRewriteData['urlrewrite_custom'];
            $frm->fill($data);
        }

        $this->set('languages', Language::getAllNames());
        $this->set('urlrewrite_id', $urlrewrite_id);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }
	
	public function attributeForm($recordId, $moduleType)
	{
		echo $moduleType;
		$this->_template->render(false, false);
	}

    public function setup()
    {
        $this->objPrivilege->canEditImageAttributes();

        $frm = $this->getForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());

        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $urlrewrite_id = FatUtility::int($post['urlrewrite_id']);
        unset($post['urlrewrite_id']);

        $url = ltrim(FatApp::getPostedData('urlrewrite_custom', FatUtility::VAR_STRING), '/');
        $post['urlrewrite_custom'] = CommonHelper::seoUrl($url);

        $url = FatApp::getPostedData('urlrewrite_original', FatUtility::VAR_STRING);
        $post['urlrewrite_original'] = trim($url, '/\\');

        /* if ($urlrewrite_id>0) {
            $urlRewriteData =  UrlRewrite::getAttributesById($urlrewrite_id);
            $customUrl  = explode("/", $urlRewriteData['urlrewrite_custom']);
            $attachId = isset($customUrl[1])? $customUrl[1] : '';
            if ($attachId) {
                $post['urlrewrite_custom'].='/'.$attachId;
            }
        } */
        
        $record = new UrlRewrite($urlrewrite_id);
        $record->assignValues($post);

        if (!$record->save()) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $this->set('msg', $this->str_setup_successful);
        $this->set('urlrewrite_id', $urlrewrite_id);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function deleteRecord()
    {
        $this->objPrivilege->canEditImageAttributes();

        $urlrewrite_id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if ($urlrewrite_id < 1) {
            FatUtility::dieJsonError($this->str_invalid_request_id);
        }

        $res = UrlRewrite::getAttributesById($urlrewrite_id, array('urlrewrite_id'));
        if ($res == false) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $this->markAsDeleted($urlrewrite_id);

        FatUtility::dieJsonSuccess($this->str_delete_record);
    }

    public function deleteSelected()
    {
        $this->objPrivilege->canEditImageAttributes();
        $urlrewriteIdsArr = FatUtility::int(FatApp::getPostedData('urlrewrite_ids'));

        if (empty($urlrewriteIdsArr)) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }

        foreach ($urlrewriteIdsArr as $urlrewriteId) {
            if (1 > $urlrewriteId) {
                continue;
            }
            $this->markAsDeleted($urlrewriteId);
        }
        $this->set('msg', $this->str_delete_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    private function markAsDeleted($urlrewriteId)
    {
        $urlrewriteId = FatUtility::int($urlrewriteId);
        if (1 > $urlrewriteId) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }
        $obj = new UrlRewrite($urlrewriteId);
        if (!$obj->deleteRecord(false)) {
            Message::addErrorMessage($obj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
    }

    private function getSearchForm()
    {
        $frm = new Form('frmSearch');
		$attachedFile = new AttachedFile();
        $attachementArr = $attachedFile->getImgAttrTypeArray($this->adminLangId);
		$frm->addSelectBox(Labels::getLabel('LBL_Select_Module', $this->adminLangId), 'select_module', $attachementArr, AttachedFile::FILETYPE_PRODUCT_IMAGE, $attachementArr, Labels::getLabel('LBL_Select', $this->adminLangId));
        $f1 = $frm->addTextBox(Labels::getLabel('LBL_Keyword', $this->adminLangId), 'keyword');
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear_Search', $this->adminLangId), array('onclick' => 'clearSearch();'));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    private function getForm($urlrewrite_id = 0)
    {
        $this->objPrivilege->canViewImageAttributes();
        $urlrewrite_id = FatUtility::int($urlrewrite_id);

        $frm = new Form('frmUrlRewrite');
        $frm->addHiddenField('', 'urlrewrite_id');
        $frm->addRequiredField(Labels::getLabel('LBL_Original_URL', $this->adminLangId), 'urlrewrite_original');
        $fld = $frm->addRequiredField(Labels::getLabel('LBL_Custom_URL', $this->adminLangId), 'urlrewrite_custom');
        $fld->htmlAfterField = '<small>' . Labels::getLabel('LBL_Example:_Custom_URL_Example', $this->adminLangId) . '</small>';
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }
}
