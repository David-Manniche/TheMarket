<?php
class PushNotificationsController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewPushNotification();
        $this->admin_id = AdminAuthentication::getLoggedAdminId();
    }

    public function index()
    {
        $this->canEdit = $this->objPrivilege->canEditPushNotification($this->admin_id, true);
        $frmSearch = $this->getSearchForm();
        $this->set("canEdit", $this->canEdit);
        $this->set("frmSearch", $frmSearch);
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
        
        $srch = PushNotification::getSearchObject();

        $keyword = $post['keyword'];
        if (!empty($keyword)) {
            $srch->addCondition('pnotification_title', 'LIKE', '%' . $keyword . '%');
        }

        $cnotificationType = $post['pnotification_type'];
        if (0 < $cnotificationType) {
            $srch->addCondition('pnotification_type', '=', $cnotificationType);
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
        
        $srch->addOrder('cn.pnotification_added_on', 'DESC');
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
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
        
        $notifyToArr = array_merge([Labels::getLabel('LBL_DOES_NOT_MATTER', $this->adminLangId)], PushNotification::getNotifyToArr($this->adminLangId));
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
        $frm->addRequiredField(Labels::getLabel('LBL_TITLE', $this->adminLangId), 'pnotification_title');
        $fld = $frm->addTextArea(Labels::getLabel('LBL_BODY', $this->adminLangId), 'pnotification_description');
        $fld->requirements()->setRequired(true);

        $typeArr = PushNotification::getTypeArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel('LBL_TYPE', $this->adminLangId), 'pnotification_type', $typeArr, '', array(), '');
        
        $frm->addDateField(Labels::getLabel('LBL_SCHEDULE_DATE', $this->adminLangId), 'pnotification_notified_on', date('Y-m-d'), ['readonly' => 'readonly','class' => 'small dateTimeFld field--calender date_js']);
                
        $frm->addCheckBox(Labels::getLabel('LBL_NOTIFY_TO_BUYERS', $this->adminLangId), 'pnotification_for_buyer', 1, [], false, 0);
        $frm->addCheckBox(Labels::getLabel('LBL_NOTIFY_TO_SELLER', $this->adminLangId), 'pnotification_for_seller', 1, [], false, 0);
        
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->adminLangId));
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

    public function addNotificationForm($cNotificationId = 0)
    {
        $frm = $this->form();
        $cNotificationId = FatUtility::int($cNotificationId);
        if (0 < $cNotificationId) {
            $data = PushNotification::getAttributesById($cNotificationId);
            $frm->fill($data);
        }
        $this->set('cNotificationId', $cNotificationId);
        $this->set('frm', $frm);
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
        FatUtility::dieJsonSuccess($json);
    }

    public function addSelectedUsersForm($cNotificationId)
    {
        $cNotificationId = FatUtility::int($cNotificationId);
        if (1 > $cNotificationId) {
            FatUtility::dieJsonError(Labels::getLabel("LBL_INVALID_REQUEST", $this->adminLangId));
        }
        $frm = $this->selectedUsersform();
        $frm->fill(['pnotification_id' => $cNotificationId]);
        $srch = PushNotification::getSearchObject(true);
        $srch->addMultipleFields(['pnotification_id', 'cntu_user_id', 'user_name', 'credential_username']);
        $srch->joinTable('tbl_users', 'INNER JOIN', 'cntu_user_id = tu.user_id', 'tu');
        $srch->joinTable('tbl_user_credentials', 'INNER JOIN', 'tu.user_id = tuc.credential_user_id', 'tuc');
        $srch->addCondition('pnotification_id', "=", $cNotificationId);
        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
        if (!empty($records) && 0 < count($records)) {
            $this->set('data', $records);
        }
        $this->set('notifyTo', PushNotification::getAttributesById($cNotificationId, ['pnotification_for_buyer', 'pnotification_for_seller']));
        $this->set('cNotificationId', $cNotificationId);
        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    public function setupNotificationToUsers($cNotificationId, $userId)
    {
        $cNotificationId = FatUtility::int($cNotificationId);
        $userId = FatUtility::int($userId);
        if (1 > $cNotificationId || 1 > $userId) {
            FatUtility::dieJsonError(Labels::getLabel("LBL_INVALID_REQUEST", $this->adminLangId));
        }
        $PushNotificationData = [
            'cntu_pnotification_id' => $cNotificationId,
            'cntu_user_id' =>  $userId
        ];
        $db = FatApp::getDb();
        if ($db->insertFromArray(PushNotification::DB_TBL_NOTIFICATION_TO_USER, $PushNotificationData, true, array(), $PushNotificationData)) {
            FatUtility::dieJsonError($db->getError());
        }
    }

    public function removeFromNotificationUsers($cNotificationId, $userId)
    {
        $cNotificationId = FatUtility::int($cNotificationId);
        $userId = FatUtility::int($userId);
        if (1 > $cNotificationId || 1 > $userId) {
            FatUtility::dieJsonError(Labels::getLabel("LBL_INVALID_REQUEST", $this->adminLangId));
        }
        $db = FatApp::getDb();
        if ($db->deleteRecords(PushNotification::DB_TBL_NOTIFICATION_TO_USER, ['smt' => 'cntu_pnotification_id = ? AND cntu_user_id = ?', 'vals' => [$cNotificationId, $userId]])) {
            FatUtility::dieJsonError($db->getError());
        }
    }

    /* public function sendNotification()
    {
        $srch = PushNotification::getSearchObject(true);
        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
        CommonHelper::printArray($records, true);
    } */
}
