<?php

trait SellerUsers
{
    protected function getUserSearchForm($user_id = 0)
    {
        $frm = new Form('frmSearch');
        $frm->addTextBox('', 'keyword', '', array('id' => 'keyword'));
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Search', $this->siteLangId));
        $frm->addButton('', "btn_clear", Labels::getLabel("LBL_Clear", $this->siteLangId), array('onclick' => 'clearSearch();'));
        $frm->addHiddenField('', 'page', 1);
        return $frm;
    }

    public function users($user_id = 0)
    {
        $user_id = FatUtility::int($user_id);

        $this->set('frmSearch', $this->getUserSearchForm($user_id));
        $this->set('user_id', $user_id);
        $this->_template->render(true, true);
    }

    public function searchUsers($user_id = 0)
    {
        $user_id = FatUtility::int($user_id);
        if (0 < $user_id) {
            $row = User::getAttributesById($user_id, array('user_id'));
            if (!$row) {
                FatUtility::dieWithError(Labels::getLabel('MSG_Invalid_Request', $this->siteLangId));
            }
        }
        $keyword = FatApp::getPostedData('keyword');

        $srch = User::getSearchObject(true);
        $srch->addCondition('user_parent', '=', UserAuthentication::getLoggedUserId());
        if ($user_id) {
            $srch->addCondition('user_id', '=', $user_id);
            $srch->doNotCalculateRecords();
            $srch->doNotLimitRecords();
        } else {
            $pageSize = FatApp::getConfig('CONF_PAGE_SIZE');
            $post = FatApp::getPostedData();
            $page = (empty($post['page']) || $post['page'] <= 0) ? 1 : $post['page'];
            $page = (empty($page) || $page <= 0) ? 1 : $page;
            $page = FatUtility::int($page);

            $srch->setPageNumber($page);
            $srch->setPageSize($pageSize);
        }

        $db = FatApp::getDb();

        $rs = $srch->getResultSet();
        $arrListing = $db->fetchAll($rs);


        $this->set("arrListing", $arrListing);
        $this->set('user_id', $user_id);

        if (!$user_id) {
            $this->set('page', $page);
            $this->set('pageCount', $srch->pages());
            $this->set('pageSize', $pageSize);
            $this->set('postedData', $post);
            $this->set('recordCount', $srch->recordCount());
        }
        $this->_template->render(false, false);
    }

    public function changeUserStatus()
    {
        $userId = FatApp::getPostedData('userId', FatUtility::VAR_INT, 0);

        $user = new User($userId);
        $srch = $user->getUserSearchObj();
        $rs = $srch->getResultSet();
        $userData = FatApp::getDb()->fetch($rs);
        if (!$userData || $userData['user_parent'] != UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Labels::getLabel('MSG_Invalid_Request', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }

        $status = ($userData['credential_active'] == applicationConstants::ACTIVE) ? applicationConstants::INACTIVE : applicationConstants::ACTIVE;

        $this->updateUserStatus($userId, $status);

        $this->set('msg', Labels::getLabel('MSG_Status_changed_Successfully', $this->siteLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function toggleSellerUserBulkStatuses()
    {
        $this->objPrivilege->canEditUsers();

        $status = FatApp::getPostedData('status', FatUtility::VAR_INT, -1);
        $userIdsArr = FatUtility::int(FatApp::getPostedData('user_ids'));
        if (empty($userIdsArr) || -1 == $status) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId)
            );
        }

        foreach ($userIdsArr as $userId) {
            if (1 > $userId) {
                continue;
            }

            $this->updateUserStatus($userId, $status);
        }
        $this->set('msg', $this->str_update_record);
        $this->_template->render(false, false, 'json-success.php');
    }

    private function updateUserStatus($userId, $status)
    {
        $status = FatUtility::int($status);
        $userId = FatUtility::int($userId);
        if (1 > $userId || -1 == $status) {
            FatUtility::dieWithError(
                Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId)
            );
        }

        $userObj = new User($userId);

        if (!$userObj->activateAccount($status)) {
            Message::addErrorMessage($userObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
    }
}
