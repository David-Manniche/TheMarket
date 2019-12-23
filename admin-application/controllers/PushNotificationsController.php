<?php
class PushNotificationsController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewPushNotification();
        $this->admin_id = AdminAuthentication::getLoggedAdminId();
    }

    private function isMediaUploaded($pNotificationId)
    {
        if ($attachment = AttachedFile::getAttachment(AttachedFile::FILETYPE_PUSH_NOTIFICATION_IMAGE, $pNotificationId, 0)) {
            return true;
        }
        return false;
    }

    public function index()
    {
        $this->canEdit = $this->objPrivilege->canEditPushNotification($this->admin_id, true);
        $frmSearch = $this->getSearchForm();
        $this->set("canEdit", $this->canEdit);
        $this->set("frmSearch", $frmSearch);
        $this->_template->addJs(array('js/jquery.datetimepicker.js'), false);
        $this->_template->addCss(array('css/jquery.datetimepicker.css'), false);
        $this->_template->render();
    }

    public function search()
    {
        $this->canEdit = $this->objPrivilege->canEditPushNotification($this->admin_id, true);

        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);

        
        $srchFrm = $this->getSearchForm();
        $post = $srchFrm->getFormDataFromArray(FatApp::getPostedData());
        
        $page = $post['page'];
        if ($page < 2) {
            $page = 1;
        }
        
        $srch = PushNotification::getSearchObject($this->adminLangId);

        $keyword = $post['keyword'];
        if (!empty($keyword)) {
            $srch->addCondition('pnotification_title', 'LIKE', '%' . $keyword . '%');
        }

        $pNotificationType = $post['pnotification_type'];
        if (0 < $pNotificationType) {
            $srch->addCondition('pnotification_type', '=', $pNotificationType);
        }

        $status = $post['pnotification_active'];
        if (-1 < $status) {
            $srch->addCondition('pnotification_active', '=', $status);
        }

        $notifyTo = $post['notify_to'];
        if (0 < $notifyTo) {
            switch ($notifyTo) {
                case 1:
                    $srch->addCondition('pnotification_for_buyer', '=', 1);
                    break;
                case 2:
                    $srch->addCondition('pnotification_for_seller', '=', 1);
                    break;
            }
        }
        
        $srch->addOrder('pn.pnotification_added_on', 'DESC');
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
        // echo $srch->getError();
        $records = FatApp::getDb()->fetchAll($rs);
        $typeArr = PushNotification::getTypeArr($this->adminLangId);
        $statusArr = PushNotification::getStatusArr($this->adminLangId);

        $this->set('arr_listing', $records);
        $this->set('pageCount', $srch->pages());
        $this->set('page', $page);
        $this->set('pageSize', $pagesize);
        $this->set('recordCount', $srch->recordCount());
        $this->set("canEdit", $this->canEdit);
        $this->set("typeArr", $typeArr);
        $this->set("statusArr", $statusArr);
        $this->_template->render(false, false);
    }

    private function getSearchForm()
    {
        $frm = new Form('frmSearch', array('id' => 'frmSearch'));
        $frm->setRequiredStarWith('caption');
        $frm->addTextBox(Labels::getLabel('LBL_Keyword', $this->adminLangId), 'keyword');

        $typeArr = [-1 => Labels::getLabel('LBL_DOES_NOT_MATTER', $this->adminLangId)] + PushNotification::getTypeArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel('LBL_TYPE', $this->adminLangId), 'pnotification_type', $typeArr, '', array(), '');
        
        $statusArr = [-1 => Labels::getLabel('LBL_DOES_NOT_MATTER', $this->adminLangId)] + PushNotification::getStatusArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel('LBL_STATUS', $this->adminLangId), 'pnotification_active', $statusArr, '', array(), '');
        
        $notifyToArr = array_merge([Labels::getLabel('LBL_DOES_NOT_MATTER', $this->adminLangId)], PushNotification::getUserTypeArr($this->adminLangId));
        $frm->addSelectBox(Labels::getLabel('LBL_NOTIFY_TO', $this->adminLangId), 'notify_to', $notifyToArr, '', array(), '');
        
        $frm->addHiddenField('', 'page');
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear_Search', $this->adminLangId), ['onclick' => 'clearSearch();']);
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }
    
    public function form()
    {
        $frm = new Form('PushNotificationForm', array('id' => 'PushNotificationForm'));
        $frm->addHiddenField('', 'pnotification_id');
        $typeArr = PushNotification::getTypeArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel('LBL_TYPE', $this->adminLangId), 'pnotification_type', $typeArr, '', array(), '');
        
        $frm->addDateTimeField(Labels::getLabel('LBL_SCHEDULE_DATE', $this->adminLangId), 'pnotification_notified_on', '', ['readonly' => 'readonly','class' => 'small dateTimeFld field--calender date_js']);
                
        $frm->addCheckBox(Labels::getLabel('LBL_NOTIFY_TO_BUYERS', $this->adminLangId), 'pnotification_for_buyer', 1, [], false, 0);
        $frm->addCheckBox(Labels::getLabel('LBL_NOTIFY_TO_SELLER', $this->adminLangId), 'pnotification_for_seller', 1, [], false, 0);
        
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->adminLangId));
        return $frm;
    }

    public function getLangForm($langId, $pNotificationId)
    {
        $frm = new Form('PushNotificationLangForm');
        $frm->addHiddenField('', 'pnotification_id', $pNotificationId);
        $frm->addSelectBox(Labels::getLabel('LBL_LANGUAGE', $langId), 'lang_id', Language::getAllNames(), $langId, array(), '');
        
        $frm->addRequiredField(Labels::getLabel('LBL_TITLE', $langId), 'pnotification_title');
        $fld = $frm->addTextArea(Labels::getLabel('LBL_BODY', $langId), 'pnotification_description');
        $fld->requirements()->setRequired(true);

        $frm->addTextBox(Labels::getLabel('LBL_URL', $langId), 'pnotification_url');
        
        $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');

        if (!empty($translatorSubscriptionKey) && $langId == $this->adminLangId) {
            $frm->addCheckBox(Labels::getLabel('LBL_UPDATE_OTHER_LANGUAGES_DATA', $langId), 'auto_update_other_langs_data', 1, array(), false, 0);
        }

        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $langId));
        return $frm;
    }

    public function getMediaForm($langId, $pNotificationId)
    {
        $frm = new Form('frmPushNotificationMedia');
        $frm->addHiddenField('', 'pnotification_id', $pNotificationId);
        $frm->addSelectBox(Labels::getLabel('LBL_Language', $langId), 'lang_id', Language::getAllNames(), $langId, array(), '');
        $ul = $frm->addHtml('', 'MediaGrids', '<ul class="grids--onethird">');

        $ul->htmlAfterField .= '<li>' . Labels::getLabel('LBL_PUSH_NOTIFICATION_IMAGE', $langId) . '<div class="logoWrap"><div class="uploaded--image">';

        if (AttachedFile::getAttachment(AttachedFile::FILETYPE_PUSH_NOTIFICATION_IMAGE, $pNotificationId, 0, $langId)) {
            $ul->htmlAfterField .= '<img src="' . CommonHelper::generateFullUrl('Image', 'pushNotificationImage', [$langId, $pNotificationId], CONF_WEBROOT_FRONT_URL) . '"> <a  class="remove--img" href="javascript:void(0);" onclick="removePushNotificationImage(' . $langId . ', ' . $pNotificationId . ')" ><i class="ion-close-round"></i></a>';
        }

        $ul->htmlAfterField .= ' </div></div><input type="button" name="app_push_notification_image" class="uploadFile-Js btn-xs" id="app_push_notification_image" data-file_type=' . AttachedFile::FILETYPE_PUSH_NOTIFICATION_IMAGE . ' data-pnotification_id = ' . $pNotificationId . ' value="Upload file"><small>' . Labels::getLabel('LBL_SIZE_MUST_BE_LESS_THAN_300KB', $langId) . '</small></li>';
        return $frm;
    }
    
    
    public function selectedUsersform()
    {
        $frm = new Form('PushNotificationForm', array('id' => 'PushNotificationForm'));
        $frm->addHiddenField('', 'pnotification_id');
        
        $userFld = $frm->addTextBox(Labels::getLabel('LBL_SELECT_USER', $this->adminLangId), 'users', '', ['placeholder' => Labels::getLabel('LBL_Search...', $this->adminLangId)]);
        $userFld->htmlAfterField = '<small>' . Labels::getLabel('LBL_SELECTED_USER_LIST_WILL_BE_DISPLAYED_HERE', $this->adminLangId) . '</small><div class="box--scroller"><ul class="columlist list--vertical" id="selectedUsersList-js"></ul></div>';
        return $frm;
    }

    public function addNotificationForm($pNotificationId = 0)
    {
        $frm = $this->form();
        $pNotificationId = FatUtility::int($pNotificationId);
        if (0 < $pNotificationId) {
            $data = PushNotification::getAttributesById($pNotificationId);
            $frm->fill($data);
        }
        $this->set('pNotificationId', $pNotificationId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function langForm($langId, $pNotificationId)
    {
        $langId = FatUtility::int($langId);
        $pNotificationId = FatUtility::int($pNotificationId);
        
        $frm = $this->getLangForm($langId, $pNotificationId);
        if (0 < $pNotificationId) {
            $data = PushNotification::getAttributesByLangId($langId, $pNotificationId);
            $frm->fill($data);
        }

        $this->set('languages', Language::getAllNames());
        $this->set('frm', $frm);
        $this->set('pNotificationId', $pNotificationId);
        $this->set('lang_id', $langId);
        $this->set('formLayout', Language::getLayoutDirection($langId));
        $this->_template->render(false, false);
    }

    public function addMediaForm($langId, $pNotificationId)
    {
        $langId = FatUtility::int($langId);
        $pNotificationId = FatUtility::int($pNotificationId);
        if (1 > $pNotificationId || 1 > $langId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }

        $this->objPrivilege->canEditPushNotification();
        $mediaFrm = $this->getMediaForm($langId, $pNotificationId);
        $this->set('lang_id', $langId);
        $this->set('languages', Language::getAllNames());
        $this->set('pNotificationId', $pNotificationId);
        $this->set('formLayout', Language::getLayoutDirection($langId));
        $this->set('frm', $mediaFrm);
        $this->_template->render(false, false);
    }

    public function setup()
    {
        $frm = $this->form();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        unset($post['btn_submit']);
        $db = FatApp::getDb();
        if (!$db->insertFromArray(PushNotification::DB_TBL, $post, true, array(), $post)) {
            FatUtility::dieJsonError($db->getError());
        }
        $json['msg'] = Labels::getLabel("LBL_SETUP_SUCCESSFULLY", $this->adminLangId);
        $json['status'] = true;
        $json['recordId'] = !empty($post['pnotification_id']) ? $post['pnotification_id'] : $db->getInsertId();
        $json['langId'] = $this->adminLangId;
        FatUtility::dieJsonSuccess($json);
    }

    public function langSetup()
    {
        $this->objPrivilege->canEditBrands();
        $post = FatApp::getPostedData();
        
        $langId = $post['lang_id'];
        $pNotificationId = $post['pnotification_id'];
        $frm = $this->getLangForm($langId, $pNotificationId);
        $post = $frm->getFormDataFromArray($post);


        if (1 > $pNotificationId || 1 > $langId) {
            Message::addErrorMessage($this->str_invalid_request_id);
            FatUtility::dieWithError(Message::getHtml());
        }

        $data = array(
            'pnotificationlang_pnotification_id' => $pNotificationId,
            'pnotificationlang_lang_id' => $langId,
            'pnotification_title' => $post['pnotification_title'],
            'pnotification_description' => $post['pnotification_description'],
            'pnotification_url' => $post['pnotification_url'],
        );
        $pushNotificationObj = new PushNotification($pNotificationId);
        if (!$pushNotificationObj->updateLangData($langId, $data)) {
            Message::addErrorMessage($pushNotificationObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        
        $autoUpdateOtherLangsData = FatApp::getPostedData('auto_update_other_langs_data', FatUtility::VAR_INT, 0);
        if (0 < $autoUpdateOtherLangsData) {
            $updateLangDataobj = new TranslateLangData(PushNotification::DB_TBL_LANG);
            if (false === $updateLangDataobj->updateTranslatedData($pNotificationId)) {
                Message::addErrorMessage($updateLangDataobj->getError());
                FatUtility::dieWithError(Message::getHtml());
            }
        }

        $newTabLangId = 0;
        $languages = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            if (!$row = PushNotification::getAttributesByLangId($langId, $pNotificationId)) {
                $newTabLangId = $langId;
                break;
            }
        }

        if ($newTabLangId == 0 && !$this->isMediaUploaded($pNotificationId)) {
            $this->set('openMediaForm', true);
        }

        $this->set('msg', Labels::getLabel('MSG_PUSH_NOTIFICATION_SETUP_SUCCESSFUL', $this->adminLangId));
        $this->set('pNotificationId', $pNotificationId);
        $this->set('langId', $newTabLangId);
        $this->set('tabLangId', $this->adminLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function addSelectedUsersForm($pNotificationId)
    {
        $this->objPrivilege->canEditPushNotification();
        $pNotificationId = FatUtility::int($pNotificationId);
        if (1 > $pNotificationId) {
            FatUtility::dieJsonError(Labels::getLabel("LBL_INVALID_REQUEST", $this->adminLangId));
        }
        $frm = $this->selectedUsersform();
        $frm->fill(['pnotification_id' => $pNotificationId]);
        $srch = PushNotification::getSearchObject($this->adminLangId, true);
        $srch->addMultipleFields(['pnotification_id', 'pntu_user_id', 'user_name', 'credential_username']);
        $srch->joinTable('tbl_users', 'INNER JOIN', 'pntu_user_id = tu.user_id', 'tu');
        $srch->joinTable('tbl_user_credentials', 'INNER JOIN', 'tu.user_id = tuc.credential_user_id', 'tuc');
        $srch->addCondition('pnotification_id', "=", $pNotificationId);
        $rs = $srch->getResultSet();
        echo $srch->getError();
        $records = FatApp::getDb()->fetchAll($rs);
        if (!empty($records) && 0 < count($records)) {
            $this->set('data', $records);
        }
        $this->set('notifyTo', PushNotification::getAttributesById($pNotificationId, ['pnotification_for_buyer', 'pnotification_for_seller']));
        $this->set('pNotificationId', $pNotificationId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setupNotificationToUsers($pNotificationId, $userId)
    {
        $this->objPrivilege->canEditPushNotification();
        $pNotificationId = FatUtility::int($pNotificationId);
        $userId = FatUtility::int($userId);
        if (1 > $pNotificationId || 1 > $userId) {
            FatUtility::dieJsonError(Labels::getLabel("LBL_INVALID_REQUEST", $this->adminLangId));
        }
        $PushNotificationData = [
            'pntu_pnotification_id' => $pNotificationId,
            'pntu_user_id' =>  $userId
        ];
        $db = FatApp::getDb();
        if ($db->insertFromArray(PushNotification::DB_TBL_NOTIFICATION_TO_USER, $PushNotificationData, true, array(), $PushNotificationData)) {
            FatUtility::dieJsonError($db->getError());
        }
    }

    public function removeFromNotificationUsers($pNotificationId, $userId)
    {
        $this->objPrivilege->canEditPushNotification();
        $pNotificationId = FatUtility::int($pNotificationId);
        $userId = FatUtility::int($userId);
        if (1 > $pNotificationId || 1 > $userId) {
            FatUtility::dieJsonError(Labels::getLabel("LBL_INVALID_REQUEST", $this->adminLangId));
        }
        $db = FatApp::getDb();
        if ($db->deleteRecords(PushNotification::DB_TBL_NOTIFICATION_TO_USER, ['smt' => 'pntu_pnotification_id = ? AND pntu_user_id = ?', 'vals' => [$pNotificationId, $userId]])) {
            FatUtility::dieJsonError($db->getError());
        }
    }

    public function removePushNotificationImage($lang_id = 0)
    {
        $this->objPrivilege->canEditPushNotification();
        $lang_id = FatUtility::int($lang_id);

        $fileHandlerObj = new AttachedFile();
        if (!$fileHandlerObj->deleteFile(AttachedFile::FILETYPE_PUSH_NOTIFICATION_IMAGE, 0, 0, 0, $lang_id)) {
            Message::addErrorMessage($fileHandlerObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $this->set('msg', Labels::getLabel('MSG_Deleted_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function uploadMedia()
    {
        $this->objPrivilege->canEditPushNotification();
        $post = FatApp::getPostedData();

        if (empty($post)) {
            Message::addErrorMessage(Labels::getLabel('LBL_Invalid_Request_Or_File_not_supported', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $file_type = FatApp::getPostedData('file_type', FatUtility::VAR_INT, 0);
        $lang_id = FatApp::getPostedData('lang_id', FatUtility::VAR_INT, 0);

        if (!$file_type) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }

        if ($file_type != AttachedFile::FILETYPE_PUSH_NOTIFICATION_IMAGE) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }

        if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
            Message::addErrorMessage(Labels::getLabel('MSG_Please_Select_A_File', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $fileHandlerObj = new AttachedFile();
        if (!$res = $fileHandlerObj->saveImage($_FILES['file']['tmp_name'], $file_type, $post['pnotification_id'], 0, $_FILES['file']['name'], -1, true, $lang_id)) {
            Message::addErrorMessage($fileHandlerObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $this->set('file', $_FILES['file']['name']);
        $this->set('msg', $_FILES['file']['name'] . Labels::getLabel('MSG_Uploaded_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }
}
