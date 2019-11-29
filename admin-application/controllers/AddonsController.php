<?php
class AddonsController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->admin_id = AdminAuthentication::getLoggedAdminId();
        $this->objPrivilege->canViewAddons($this->admin_id);
    }

    public function index()
    {
        $this->canEdit = $this->objPrivilege->canEditAddons($this->admin_id, true);
        $srchFrm = $this->getSearchForm();
        $this->set("srchFrm", $srchFrm);
        $this->set("canEdit", $this->canEdit);
        $this->_template->render();
    }

    public function search()
    {
        $srchFrm = $this->getSearchForm();
        $post = $srchFrm->getFormDataFromArray(FatApp::getPostedData());

        $srch = Addon::getSearchObject($this->adminLangId, false);
        
        if (!empty($post['addon_type']) && 0 < $post['addon_type']) {
            $srch->addCondition('addon_type', '=', $post['addon_type']);
        }

        if (!empty($post['keyword'])) {
            $srch->addCondition('addon_identifier', 'LIKE', '%' . $post['keyword'] . '%');
        }

        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
        $this->canEdit = $this->objPrivilege->canEditAddons($this->admin_id, true);
        $addonTypes = Addon::getTypeArr($this->adminLangId);
        
        $this->set("canEdit", $this->canEdit);
        $this->set("addonTypes", $addonTypes);
        $this->set("arr_listing", $records);
        $this->set('activeInactiveArr', applicationConstants::getActiveInactiveArr($this->adminLangId));
        $this->_template->render(false, false);
    }

    private function getSearchForm()
    {
        $frm = new Form('frmAddonSearch', ['id' => 'frmAddonSearch']);
        $frm->addTextBox(Labels::getLabel('LBL_Keyword', $this->adminLangId), 'keyword');
        
        $addonTypes = Addon::getTypeArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel('LBL_Type', $this->adminLangId), 'addon_type', [-1 => Labels::getLabel('LBL_Does_not_Matter', $this->adminLangId)] + $addonTypes, '', array(), '');

        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear_Search', $this->adminLangId), array('onclick'=>'clearSearch();'));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    public function form($addonId)
    {
        $addonId =  FatUtility::int($addonId);
        $frm = $this->getForm($addonId);
        if (0 < $addonId) {
            $data = Addon::getAttributesById($addonId, ['addon_id','addon_identifier','addon_active']);
            if ($data === false) {
                FatUtility::dieJsonError($this->str_invalid_request);
            }
            $frm->fill($data);
        }

        $this->set('languages', Language::getAllNames());
        $this->set('addonId', $addonId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setup()
    {
        $this->objPrivilege->canEditAddons();
        $frm = $this->getForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $addonId = $post['addon_id'];
        unset($post['addon_id']);
        
        if (1 > $addonId) {
            FatUtility::dieWithError($this->str_invalid_request);
        }

        if (0 < $addonId) {
            $addonId = Addon::getAttributesById($addonId, 'addon_id');
            if ($addonId === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
        }

        $record = new Addon($addonId);
        $record->assignValues($post);
        if (!$record->save()) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $newTabLangId = 0;
        if ($addonId > 0) {
            $languages = Language::getAllNames();
            foreach ($languages as $langId => $langName) {
                if (!$row = Addon::getAttributesByLangId($langId, $addonId)) {
                    $newTabLangId = $langId;
                    break;
                }
            }
        } else {
            $addonId = $record->getMainTableRecordId();
            $newTabLangId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG', FatUtility::VAR_INT, 1);
        }
        $this->set('msg', $this->str_setup_successful);
        $this->set('addonId', $addonId);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function langForm($addonId = 0, $lang_id = 0, $autoFillLangData = 0)
    {
        $addonId = FatUtility::int($addonId);
        $lang_id = FatUtility::int($lang_id);

        if ($addonId == 0 || $lang_id == 0) {
            FatUtility::dieWithError($this->str_invalid_request);
        }

        $langFrm = $this->getLangForm($addonId, $lang_id);
        if (0 < $autoFillLangData) {
            $updateLangDataobj = new TranslateLangData(Addon::DB_TBL_LANG);
            $translatedData = $updateLangDataobj->getTranslatedData($addonId, $lang_id);
            if (false === $translatedData) {
                Message::addErrorMessage($updateLangDataobj->getError());
                FatUtility::dieWithError(Message::getHtml());
            }
            $langData = current($translatedData);
        } else {
            $langData = Addon::getAttributesByLangId($lang_id, $addonId);
        }
        if ($langData) {
            $langFrm->fill($langData);
        }

        $this->set('languages', Language::getAllNames());
        $this->set('addonId', $addonId);
        $this->set('lang_id', $lang_id);
        $this->set('langFrm', $langFrm);
        $this->set('formLayout', Language::getLayoutDirection($lang_id));
        $this->_template->render(false, false);
    }

    public function langSetup()
    {
        $this->objPrivilege->canEditAddons();
        $post = FatApp::getPostedData();

        $addonId = $post['addon_id'];
        $lang_id = $post['lang_id'];

        if ($addonId == 0 || $lang_id == 0) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }

        $frm = $this->getLangForm($addonId, $lang_id);
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        unset($post['addon_id']);
        unset($post['lang_id']);

        $data = array(
        'addonlang_lang_id' => $lang_id,
        'addonlang_addon_id' => $addonId,
        'addon_name' => $post['addon_name'],
        'addon_description' => $post['addon_description'],
        );

        $addonObj = new Addon($addonId);

        if (!$addonObj->updateLangData($lang_id, $data)) {
            Message::addErrorMessage($addonObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $autoUpdateOtherLangsData = FatApp::getPostedData('auto_update_other_langs_data', FatUtility::VAR_INT, 0);
        if (0 < $autoUpdateOtherLangsData) {
            $updateLangDataobj = new TranslateLangData(Addon::DB_TBL_LANG);
            if (false === $updateLangDataobj->updateTranslatedData($addonId)) {
                Message::addErrorMessage($updateLangDataobj->getError());
                FatUtility::dieWithError(Message::getHtml());
            }
        }

        $newTabLangId = 0;
        $languages = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            if (!$row = Addon::getAttributesByLangId($langId, $addonId)) {
                $newTabLangId = $langId;
                break;
            }
        }

        $this->set('msg', $this->str_setup_successful);
        $this->set('addonId', $addonId);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function uploadIcon($addon_id)
    {
        $this->objPrivilege->canEditAddons();

        $addon_id = FatUtility::int($addon_id);

        if (1 > $addon_id) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $post = FatApp::getPostedData();

        if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
            Message::addErrorMessage(Labels::getLabel('MSG_Please_select_a_file', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $fileHandlerObj = new AttachedFile();

        if (!$res = $fileHandlerObj->saveAttachment(
            $_FILES['file']['tmp_name'],
            AttachedFile::FILETYPE_ADDON_LOGO,
            $addon_id,
            0,
            $_FILES['file']['name'],
            -1,
            $unique_record = true
        )
        ) {
            Message::addErrorMessage($fileHandlerObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $this->set('addonId', $addon_id);
        $this->set('file', $_FILES['file']['name']);
        $this->set('msg', $_FILES['file']['name'] . ' ' . Labels::getLabel('LBL_File_Uploaded_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function updateOrder()
    {
        $this->objPrivilege->canEditAddons();

        $post = FatApp::getPostedData();

        if (!empty($post)) {
            $addonObj = new Addon();
            if (!$addonObj->updateOrder($post['addon'])) {
                Message::addErrorMessage($addonObj->getError());
                FatUtility::dieJsonError(Message::getHtml());
            }

            $this->set('msg', Labels::getLabel('LBL_Order_Updated_Successfully', $this->adminLangId));
            $this->_template->render(false, false, 'json-success.php');
        }
    }

    public function changeStatus()
    {
        $this->objPrivilege->canEditAddons();
        $addonId = FatApp::getPostedData('addonId', FatUtility::VAR_INT, 0);
        if (0 >= $addonId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }

        $data = Addon::getAttributesById($addonId, array('addon_id', 'addon_active'));

        if ($data == false) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieWithError(Message::getHtml());
        }

        $status = ($data['addon_active'] == applicationConstants::ACTIVE) ? applicationConstants::INACTIVE : applicationConstants::ACTIVE;

        $this->updateAddonStatus($addonId, $status);

        $this->set('msg', $this->str_update_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    private function getForm($addonId = 0)
    {
        $addonId =  FatUtility::int($addonId);

        $frm = new Form('frmAddon');
        $frm->addHiddenField('', 'addon_id', $addonId);
        $frm->addRequiredField(Labels::getLabel('LBL_Addon_Identifier', $this->adminLangId), 'addon_identifier');

        $activeInactiveArr = applicationConstants::getActiveInactiveArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel('LBL_Status', $this->adminLangId), 'addon_active', $activeInactiveArr, '', array(), '');

        /*$fld = $frm->addButton(
            'Icon',
            'addon_icon',
            Labels::getLabel('LBL_Upload_File', $this->adminLangId),
            array('class'=>'uploadFile-Js','id'=>'addon_icon','data-addon_id' => $addonId)
        );
        $fld->htmlAfterField='<span id="addon_icon"></span>
        <div class="uploaded--image"><img src="'.CommonHelper::generateUrl('Image', 'addon', array($addonId,'MEDIUM'), CONF_WEBROOT_FRONT_URL).'"></div>';*/

        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    private function getLangForm($addonId = 0, $lang_id = 0)
    {
        $frm = new Form('frmAddonLang');
        $frm->addHiddenField('', 'addon_id', $addonId);
        $frm->addSelectBox(Labels::getLabel('LBL_LANGUAGE', $this->adminLangId), 'lang_id', Language::getAllNames(), $lang_id, array(), '');
        $frm->addRequiredField(Labels::getLabel('LBL_Addon_Name', $this->adminLangId), 'addon_name');
        $frm->addTextarea(Labels::getLabel('LBL_Details', $this->adminLangId), 'addon_description');

        $siteLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
        $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');

        if (!empty($translatorSubscriptionKey) && $lang_id == $siteLangId) {
            $frm->addCheckBox(Labels::getLabel('LBL_UPDATE_OTHER_LANGUAGES_DATA', $this->adminLangId), 'auto_update_other_langs_data', 1, array(), false, 0);
        }

        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    private function updateAddonStatus($addonId, $status)
    {
        $status = FatUtility::int($status);
        $addonId = FatUtility::int($addonId);
        if (1 > $addonId || -1 == $status) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }

        $obj = new Addon($addonId);
        if (!$obj->changeStatus($status)) {
            Message::addErrorMessage($obj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
    }

    public function toggleBulkStatuses()
    {
        $this->objPrivilege->canEditAddons();

        $status = FatApp::getPostedData('status', FatUtility::VAR_INT, -1);
        $addonIdsArr = FatUtility::int(FatApp::getPostedData('addon_ids'));
        if (empty($addonIdsArr) || -1 == $status) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->adminLangId)
            );
        }

        foreach ($addonIdsArr as $addonId) {
            if (1 > $addonId) {
                continue;
            }

            $this->updateAddonStatus($addonId, $status);
        }
        $this->set('msg', $this->str_update_record);
        $this->_template->render(false, false, 'json-success.php');
    }

}
