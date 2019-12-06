<?php
class CustomNotificationsController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewCustomNotification();
        $this->admin_id = AdminAuthentication::getLoggedAdminId();
    }

    public function index()
    {
        $this->canEdit = $this->objPrivilege->canEditCustomNotification($this->admin_id, true);
        $frmSearch = $this->getSearchForm();
        $this->set("canEdit", $this->canEdit);
        $this->set("frmSearch", $frmSearch);
        $this->_template->render();
    }

    public function search()
    {
        $this->canEdit = $this->objPrivilege->canEditCustomNotification($this->admin_id, true);

        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);

        
        $srchFrm = $this->getSearchForm();
        $post = $srchFrm->getFormDataFromArray(FatApp::getPostedData());
        
        $page = $post['page'];
        if ($page < 2) {
            $page = 1;
        }
        
        $srch = CustomNotification::getSearchObject();

        $keyword = $post['keyword'];
        if (!empty($keyword)) {
            $srch->addCondition('cnotification_title', 'LIKE', '%' . $keyword . '%');
        }

        $cnotificationType = $post['cnotification_type'];
        if (0 < $cnotificationType) {
            $srch->addCondition('cnotification_type', '=', $cnotificationType);
        }

        $status = $post['cnotification_active'];
        if (-1 < $status) {
            $srch->addCondition('cnotification_active', '=', $status);
        }

        $notifyTo = $post['notify_to'];
        if (0 < $notifyTo) {
            switch ($notifyTo) {
                case 1:
                    $srch->addCondition('cnotification_for_buyer', '=', 1);
                    break;
                case 2:
                    $srch->addCondition('cnotification_for_seller', '=', 1);
                    break;
                case 3:
                    $srch->addCondition('cnotification_for_buyer', '=', 1);
                    $srch->addCondition('cnotification_for_seller', '=', 1);
                    break;
            }
        }
        
        $srch->addOrder('cn.cnotification_added_on', 'DESC');
        $srch->setPageNumber($page);
        $srch->setPageSize($pagesize);
        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
        
        $typeArr = CustomNotification::getTypeArr($this->adminLangId);
        $statusArr = CustomNotification::getStatusArr($this->adminLangId);

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

        $typeArr = CustomNotification::getTypeArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel('LBL_TYPE', $this->adminLangId), 'cnotification_type', array( -1 => Labels::getLabel('LBL_DOES_NOT_MATTER', $this->adminLangId) ) + $typeArr, '', array(), '');
        
        $statusArr = CustomNotification::getStatusArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel('LBL_STATUS', $this->adminLangId), 'cnotification_active', array( -1 => Labels::getLabel('LBL_DOES_NOT_MATTER', $this->adminLangId) ) + $statusArr, '', array(), '');
        
        $notifyToArr = [
            Labels::getLabel('LBL_DOES_NOT_MATTER', $this->adminLangId),
            Labels::getLabel('LBL_BUYERS', $this->adminLangId),
            Labels::getLabel('LBL_SELLERS', $this->adminLangId),
            Labels::getLabel('LBL_BOTH', $this->adminLangId),
        ];

        $frm->addSelectBox(Labels::getLabel('LBL_NOTIFY_TO', $this->adminLangId), 'notify_to', $notifyToArr, '', array(), '');
        
        $frm->addHiddenField('', 'page');
        $fld_submit = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear_Search', $this->adminLangId), ['onclick' => 'clearSearch();']);
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }

    public function form()
    {
        $frm = new Form('customNotificationForm', array('id' => 'customNotificationForm'));
        $frm->addHiddenField('', 'cnotification_id');
        $frm->addRequiredField(Labels::getLabel('LBL_TITLE', $this->adminLangId), 'cnotification_title');
        $fld = $frm->addTextArea(Labels::getLabel('LBL_BODY', $this->adminLangId), 'cnotification_description');
        $fld->requirements()->setRequired(true);

        $typeArr = CustomNotification::getTypeArr($this->adminLangId);
        $frm->addSelectBox(Labels::getLabel('LBL_TYPE', $this->adminLangId), 'cnotification_type', $typeArr, '', array(), '');
        
        $frm->addDateField(Labels::getLabel('LBL_SCHEDULE_DATE', $this->adminLangId), 'cnotification_notified_on', date('Y-m-d'), ['readonly' => 'readonly','class' => 'small dateTimeFld field--calender date_js']);
                
        $frm->addCheckBox(Labels::getLabel('LBL_NOTIFY_TO_BUYERS', $this->adminLangId), 'cnotification_for_buyer', 1, [], false, 0);
        $frm->addCheckBox(Labels::getLabel('LBL_NOTIFY_TO_SELLER', $this->adminLangId), 'cnotification_for_seller', 1, [], false, 0);
        
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->adminLangId));
        return $frm;
    }

    public function selectedUsersform()
    {
        $frm = new Form('customNotificationForm', array('id' => 'customNotificationForm'));
        $frm->addHiddenField('', 'cnotification_id');
        
        $userFld = $frm->addTextBox(Labels::getLabel('LBL_SELECT_USER', $this->adminLangId), 'users', '', ['placeholder' => Labels::getLabel('LBL_Search...', $this->adminLangId)]);
        $userFld->htmlAfterField = '<small>' . Labels::getLabel('LBL_SELECTED_USER_LIST_WILL_BE_DISPLAYED_HERE', $this->adminLangId) . '</small><div class="box--scroller"><ul class="columlist list--vertical" id="selectedUsersList-js"></ul></div>';
        return $frm;
    }

    public function addNotificationForm($cNotificationId = 0)
    {
        $frm = $this->form();
        $cNotificationId = FatUtility::int($cNotificationId);
        if (0 < $cNotificationId) {
            $data = CustomNotification::getAttributesById($cNotificationId);
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
        if (!$db->insertFromArray(CustomNotification::DB_TBL, $post, true, array(), $post)) {
            FatUtility::dieJsonError($db->getError());
        }
        $json['msg'] = Labels::getLabel("LBL_SETUP_SUCCESSFULLY", $this->adminLangId);
        $json['status'] = true;
        $json['recordId'] = !empty($post['cnotification_id']) ? $post['cnotification_id'] : $db->getInsertId();
        FatUtility::dieJsonSuccess($json);
    }

    public function addSelectedUsersForm($cNotificationId)
    {
        $cNotificationId = FatUtility::int($cNotificationId);
        if (1 > $cNotificationId) {
            FatUtility::dieJsonError(Labels::getLabel("LBL_INVALID_REQUEST", $this->adminLangId));
        }
        $frm = $this->selectedUsersform();
        $frm->fill(['cnotification_id' => $cNotificationId]);
        $srch = CustomNotification::getSearchObject(true);
        $srch->addMultipleFields(['cnotification_id', 'cntu_user_id', 'user_name', 'credential_username']);
        $srch->joinTable('tbl_users', 'INNER JOIN', 'cntu_user_id = tu.user_id', 'tu');
        $srch->joinTable('tbl_user_credentials', 'INNER JOIN', 'tu.user_id = tuc.credential_user_id', 'tuc');
        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
        if (!empty($records) && 0 < count($records)) {
            $this->set('data', $records);
        }
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
        $customNotificationData = [
            'cntu_cnotification_id' => $cNotificationId,
            'cntu_user_id' =>  $userId
        ];
        $db = FatApp::getDb();
        if ($db->insertFromArray(CustomNotification::DB_TBL_NOTIFICATION_TO_USER, $customNotificationData, true, array(), $customNotificationData)) {
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
        if ($db->deleteRecords(CustomNotification::DB_TBL_NOTIFICATION_TO_USER, ['smt' => 'cntu_cnotification_id = ? AND cntu_user_id = ?', 'vals' => [$cNotificationId, $userId]])) {
            FatUtility::dieJsonError($db->getError());
        }
    }

    /* public function sendNotification()
    {
        $srch = CustomNotification::getSearchObject(true);
        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
        CommonHelper::printArray($records, true);
    } */
}
