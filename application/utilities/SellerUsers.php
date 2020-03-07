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
        $srch->addMultipleFields(array('user_id', 'user_name', 'credential_username', 'credential_email', 'credential_active'));
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
        $frm->addRequiredField(Labels::getLabel('LBL_Full_Name', $this->siteLangId), 'user_name');
        $frm->addRequiredField(Labels::getLabel('LBL_Username', $this->siteLangId), 'user_username');
        $fld = $frm->addEmailField(Labels::getLabel('LBL_User_Email', $this->siteLangId), 'user_email', '');
        $fld->setUnique('tbl_user_credentials', 'credential_email', 'credential_user_id', 'user_id', 'user_id');
        $frm->addRequiredField(Labels::getLabel('LBL_Phone', $this->siteLangId), 'user_phone', '', array('class' => 'phone-js ltr-right', 'placeholder' => ValidateElement::PHONE_NO_FORMAT, 'maxlength' => ValidateElement::PHONE_NO_LENGTH));
        
		if ($user_id == 0) {
			$fld = $frm->addPasswordField(Labels::getLabel('LBL_PASSWORD', $this->siteLangId), 'user_password');
			$fld->requirements()->setRequired();
			$fld->requirements()->setRegularExpressionToValidate(ValidateElement::PASSWORD_REGEX);
			$fld->requirements()->setCustomErrorMessage(Labels::getLabel('MSG_PASSWORD_MUST_BE_EIGHT_CHARACTERS_LONG_AND_ALPHANUMERIC', $this->siteLangId));
			$fld1 = $frm->addPasswordField(Labels::getLabel('LBL_CONFIRM_PASSWORD', $this->siteLangId), 'password1');
			$fld1->requirements()->setRequired();
			$fld1->requirements()->setCompareWith('user_password', 'eq', Labels::getLabel('LBL_PASSWORD', $this->siteLangId));
		}
		
		$activeInactiveArr = applicationConstants::getActiveInactiveArr($this->siteLangId);
        $frm->addSelectBox(Labels::getLabel('Lbl_Status', $this->siteLangId), 'user_active', $activeInactiveArr, '', array(), '');
        $countryObj = new Countries();
        $countriesArr = $countryObj->getCountriesArr($this->siteLangId);
        $fld = $frm->addSelectBox(Labels::getLabel('LBL_Country', $this->siteLangId), 'user_country_id', $countriesArr, FatApp::getConfig('CONF_COUNTRY', FatUtility::VAR_INT, 0), array(), Labels::getLabel('LBL_Select', $this->siteLangId));
        $fld->requirement->setRequired(true);

        $frm->addSelectBox(Labels::getLabel('LBL_State', $this->siteLangId), 'user_state_id', array(), '', array(), Labels::getLabel('LBL_Select', $this->siteLangId))->requirement->setRequired(true);
        $frm->addTextBox(Labels::getLabel('LBL_City', $this->siteLangId), 'user_city');
        $fld1 = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $this->siteLangId));

        return $frm;
    }

    public function addSubUserForm($userId = 0)
    {
        $userId = FatUtility::int($userId);
        $frm = $this->getSubUserForm($userId);
		$stateId = 0;
        if (0 < $userId) {
            $srch = User::getSearchObject(true);
            $srch->addMultipleFields(array('user_id', 'user_parent', 'user_name', 'user_phone', 'user_country_id', 'user_state_id', 'user_city', 'credential_username', 'credential_email', 'credential_active'));
            $srch->addCondition('user_parent', '=', UserAuthentication::getLoggedUserId());
            $srch->addCondition('user_id', '=', $userId);
            $srch->addCondition('user_deleted', '=', applicationConstants::NO);
            $rs = $srch->getResultSet();
            $data = FatApp::getDb()->fetch($rs);
            if ($data === false || $data['user_parent'] != UserAuthentication::getLoggedUserId()) {
                FatUtility::dieWithError(Labels::getLabel("LBL_INVALID_REQUEST", $this->siteLangId));
            }
            $data['user_username'] = $data['credential_username'];
            $data['user_email'] = $data['credential_email'];
            $data['user_active'] = $data['credential_active'];
            $frm->fill($data);
			$stateId = $data['user_state_id'];
        }

        $this->set('frm', $frm);
		$this->set('stateId', $stateId);
        $this->set('siteLangId', $this->siteLangId);
        $this->set('language', Language::getAllNames());
        $this->_template->render(false, false);
    }

    public function setupSubUser()
    {
		$post = FatApp::getPostedData();
		
		$userId = $post['user_id'];
		$user_state_id = FatUtility::int($post['user_state_id']);
        $frm = $this->getSubUserForm($userId);

        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieWithError(Message::getHtml());
        }
        if (0 < $userId) {
            $user = new User($userId);
            $srch = $user->getUserSearchObj();
            $rs = $srch->getResultSet();
            $userData = FatApp::getDb()->fetch($rs);
            if (empty($userData) || $userData['user_parent'] != UserAuthentication::getLoggedUserId()) {
                Message::addErrorMessage(Labels::getLabel('MSG_Invalid_Request', $this->siteLangId));
                FatUtility::dieWithError(Message::getHtml());
            }
        }

        if ($post == false) {
            $message = Labels::getLabel(current($frm->getValidationErrors()), $this->siteLangId);
            Message::addErrorMessage($message);
            FatUtility::dieWithError(Message::getHtml());
        }

        $dialCode = FatApp::getPostedData('user_dial_code', FatUtility::VAR_STRING, '');
        $countryIso = FatApp::getPostedData('user_country_iso', FatUtility::VAR_STRING, '');

        $post['user_dial_code'] = $dialCode;
        $post['user_phone'] = isset($post['user_phone']) ? FatUtility::int(str_replace($post['user_dial_code'], "", $post['user_phone'])) : null;
        $post['user_preferred_dashboard'] = User::USER_SELLER_DASHBOARD;
        $post['user_registered_initially_for'] = User::USER_TYPE_SELLER;
        $post['user_is_supplier'] = 1;
        $post['user_is_advertiser'] = 1;
        $post['user_active'] = 1;
        $post['user_verify'] = 1;
        $post['user_parent'] = UserAuthentication::getLoggedUserId();
		$post['user_state_id'] = $user_state_id;
        
		$db = FatApp::getDb();
        $db->startTransaction();
        $userObj = new User($userId);
        $userObj->assignValues($post);
        if (!$userObj->save()) {
            $db->rollbackTransaction();
            $message = Labels::getLabel($userObj->getError(), $this->siteLangId);
            FatUtility::dieWithError($message);
        }
		
		$password = (0 < $userId) ? null : $post['user_password'];
        if (!$userObj->setLoginCredentials($post['user_username'], $post['user_email'], $password, $post['user_active'], $post['user_verify'])) {
            $db->rollbackTransaction();
            $message = Labels::getLabel($userObj->getError(), $this->siteLangId);
            FatUtility::dieWithError($message);
        }

        $db->commitTransaction();

        if (false === $userObj->updateUserMeta('user_country_iso', $countryIso)) {
            LibHelper::exitWithError($user->getError(), false, true);
            FatApp::redirectUser(CommonHelper::generateUrl('GuestUser', 'loginForm'));
        }
        $this->set('msg', Labels::getLabel('LBL_Setup_Successful', $this->siteLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function changeUserStatus()
    {
        $userId = FatApp::getPostedData('userId', FatUtility::VAR_INT, 0);

        $user = new User($userId);
        $srch = $user->getUserSearchObj();
        $rs = $srch->getResultSet();
        $userData = FatApp::getDb()->fetch($rs);
        if (empty($userData) || $userData['user_parent'] != UserAuthentication::getLoggedUserId()) {
            Message::addErrorMessage(Labels::getLabel('MSG_Invalid_Request', $this->siteLangId));
            FatUtility::dieWithError(Message::getHtml());
        }

        $status = ($userData['credential_active'] == applicationConstants::ACTIVE) ? applicationConstants::INACTIVE : applicationConstants::ACTIVE;

        $this->updateUserStatus($userId, $status);

        $this->set('msg', Labels::getLabel('MSG_Status_changed_Successfully', $this->siteLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function toggleSellerUserStatus()
    {
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
        $this->set('msg', Labels::getLabel('MSG_Status_changed_Successfully', $this->siteLangId));
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

    public function subUserPasswordForm($userId = 0)
    {
        $userId = FatUtility::int($userId);
        $frm = $this->getChangePasswordForm($userId);

        $userObj = new User($userId);
        $srch = $userObj->getUserSearchObj(array('user_id'));
        $srch->addCondition('user_parent', '=', UserAuthentication::getLoggedUserId());
        $rs = $srch->getResultSet();

        $data = FatApp::getDb()->fetch($rs, 'user_id');

        if ($data === false) {
            $message = Labels::getLabel('MSG_Invalid_User', $this->siteLangId);
            FatUtility::dieJsonError($message);
        }

        $this->set('frm', $frm);
        $this->_template->render(false, false);
    }

    private function getChangePasswordForm($userId)
    {
        $frm = new Form('changePwdFrm');
        $frm->addHiddenField('', 'user_id', $userId);
        $newPwd = $frm->addPasswordField(
            Labels::getLabel('LBL_NEW_PASSWORD', $this->siteLangId),
            'new_password'
        );
        $newPwd->htmlAfterField = '<span class="text--small">' . sprintf(Labels::getLabel('LBL_Example_password', $this->siteLangId), 'User@123') . '</span>';
        $newPwd->requirements()->setRequired();
        $newPwd->requirements()->setRegularExpressionToValidate(ValidateElement::PASSWORD_REGEX);
        $newPwd->requirements()->setCustomErrorMessage(Labels::getLabel('MSG_PASSWORD_MUST_BE_ATLEAST_EIGHT_CHARACTERS_LONG_AND_ALPHANUMERIC', $this->siteLangId));
        $conNewPwd = $frm->addPasswordField(
            Labels::getLabel('LBL_CONFIRM_NEW_PASSWORD', $this->siteLangId),
            'conf_new_password'
        );
        $conNewPwdReq = $conNewPwd->requirements();
        $conNewPwdReq->setRequired();
        $conNewPwdReq->setCompareWith('new_password', 'eq');
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->siteLangId));
        return $frm;
    }
	
	public function updateUserPassword()
    {
		$post = FatApp::getPostedData();
		$userId = $post['user_id'];
        $frm = $this->getChangePasswordForm($userId);

        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieWithError(Message::getHtml());
        }
		
        $user = new User($userId);
		$srch = $user->getUserSearchObj();
		$rs = $srch->getResultSet();
		$userData = FatApp::getDb()->fetch($rs);
		if (empty($userData) || $userData['user_parent'] != UserAuthentication::getLoggedUserId()) {
			Message::addErrorMessage(Labels::getLabel('MSG_Invalid_Request', $this->siteLangId));
			FatUtility::dieWithError(Message::getHtml());
		}
		
        $password = $post['new_password'];
        $encryptedPassword = UserAuthentication::encryptPassword($password);
		
		$arrFlds['credential_password'] = $encryptedPassword;
		
		$record = new TableRecord(User::DB_TBL_CRED);
		$record->setFldValue('credential_user_id', $userId);
        $record->assignValues($arrFlds);
        if (!$record->addNew(array(), $arrFlds)) {
            Message::addErrorMessage($record->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
		
        $this->set('msg', Labels::getLabel('LBL_Password_Updated_Successful', $this->siteLangId));
        $this->_template->render(false, false, 'json-success.php');
    }
}
