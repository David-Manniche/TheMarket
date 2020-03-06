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

    public function users()
    {
        $this->set('frmSearch', $this->getUserSearchForm());
        $this->_template->render(true, true);
    }

    public function searchUsers()
    {
        $keyword = FatApp::getPostedData('keyword');
        $srch = User::getSearchObject(true);
        $srch->addCondition('user_parent', '=', UserAuthentication::getLoggedUserId());
        $pageSize = FatApp::getConfig('CONF_PAGE_SIZE');
        $post = FatApp::getPostedData();
        $page = (empty($post['page']) || $post['page'] <= 0) ? 1 : $post['page'];
        $page = (empty($page) || $page <= 0) ? 1 : $page;
        $page = FatUtility::int($page);

        $srch->setPageNumber($page);
        $srch->setPageSize($pageSize);

        $db = FatApp::getDb();

        $rs = $srch->getResultSet();
        $arrListing = $db->fetchAll($rs);

        $this->set("arrListing", $arrListing);
        $this->set('page', $page);
        $this->set('pageCount', $srch->pages());
        $this->set('pageSize', $pageSize);
        $this->set('postedData', $post);
        $this->set('recordCount', $srch->recordCount());
        $this->_template->render(false, false);
    }

    private function getSubUserForm($user_id = 0)
    {
        $frm = new Form('frmSocialPlatform');
        $frm->addHiddenField('', 'user_id', $user_id);
        $frm->addRequiredField(Labels::getLabel('LBL_User_Name', $this->siteLangId), 'user_name');
        $frm->addRequiredField(Labels::getLabel('LBL_Username', $this->siteLangId), 'username');
        $fld = $frm->addEmailField(Labels::getLabel('LBL_User_Email', $this->siteLangId), 'user_email', '');
        $fld->setUnique('tbl_user_credentials', 'credential_email', 'credential_user_id', 'user_id', 'user_id');
        $fld = $frm->addPasswordField(Labels::getLabel('LBL_PASSWORD', $this->siteLangId), 'user_password');
        $fld->requirements()->setRequired();
        $fld->requirements()->setRegularExpressionToValidate(ValidateElement::PASSWORD_REGEX);
        $fld->requirements()->setCustomErrorMessage(Labels::getLabel('MSG_PASSWORD_MUST_BE_EIGHT_CHARACTERS_LONG_AND_ALPHANUMERIC', $this->siteLangId));
        $fld1 = $frm->addPasswordField(Labels::getLabel('LBL_CONFIRM_PASSWORD', $this->siteLangId), 'password1');
        $fld1->requirements()->setRequired();
        $fld1->requirements()->setCompareWith('user_password', 'eq', Labels::getLabel('LBL_PASSWORD', $this->siteLangId));
        $activeInactiveArr = applicationConstants::getActiveInactiveArr($this->siteLangId);
        $frm->addSelectBox(Labels::getLabel('Lbl_Status', $this->siteLangId), 'credential_active', $activeInactiveArr, '', array(), '');

        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $this->siteLangId));
        return $frm;
    }

    public function addSubUserForm($user_id = 0)
    {
        $user_id = FatUtility::int($user_id);
        if (!$this->isUserValid($userId)) {
            Message::addErrorMessage(Labels::getLabel('MSG_Invalid_Request', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }
        $frm = $this->getSubUserForm($user_id);

        if (0 < $user_id) {
            $srch = User::getSearchObject(true);
            $srch->addCondition('user_parent', '=', UserAuthentication::getLoggedUserId());
            $srch->addCondition('user_id', '=', $user_id);
            $srch->addCondition('user_deleted', '=', applicationConstants::NO);
            $srch->addMultipleFields(array('user_id', 'user_name'));
            $rs = $srch->getResultSet();
            $data = $db->fetch($rs);
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $frm->fill($data);
        }

        $this->set('frm', $frm);
        $this->set('siteLangId', $this->siteLangId);
        $this->set('language', Language::getAllNames());
        $this->_template->render(false, false);
    }

    public function socialPlatformSetup()
    {
        $frm = $this->getSocialPlatformForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());

        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $splatform_id = $post['splatform_id'];
        unset($post['splatform_id']);
        $data_to_be_save = $post;
        $data_to_be_save['splatform_user_id'] = UserAuthentication::getLoggedUserId();

        $recordObj = new SocialPlatform($splatform_id);
        $recordObj->assignValues($data_to_be_save, true);
        if (!$recordObj->save()) {
            Message::addErrorMessage($recordObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }

        $splatform_id = $recordObj->getMainTableRecordId();

        $newTabLangId = 0;
        $languages = Language::getAllNames();
        foreach ($languages as $langId => $langName) {
            if (!$row = SocialPlatform::getAttributesByLangId($langId, $splatform_id)) {
                $newTabLangId = $langId;
                break;
            }
        }

        $this->set('msg', Labels::getLabel('LBL_Setup_Successful', $this->siteLangId));
        $this->set('splatformId', $splatform_id);
        $this->set('langId', $newTabLangId);
        $this->_template->render(false, false, 'json-success.php');
    }

    public function isUserValid($user_id)
    {
        $user_id = FatUtility::int($user_id);
        $user = new User($userId);
        $srch = $user->getUserSearchObj();
        $rs = $srch->getResultSet();
        $userData = FatApp::getDb()->fetch($rs);
        if (!$userData || $userData['user_parent'] != UserAuthentication::getLoggedUserId()) {
            return false;
        }
        return true;
    }

    public function changeUserStatus()
    {
        $userId = FatApp::getPostedData('userId', FatUtility::VAR_INT, 0);

        if (!$this->isUserValid($userId)) {
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
