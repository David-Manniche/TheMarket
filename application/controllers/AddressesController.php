<?php
class AddressesController extends LoggedUserController
{
    public function __construct($action)
    {
        parent::__construct($action);
        //$this->set('bodyClass','is--dashboard');
    }

    public function setUpAddress()
    {
        $frm = $this->getUserAddressForm($this->siteLangId);
        $post = FatApp::getPostedData();
        if (empty($post)) {
            $message = Labels::getLabel('MSG_Invalid_Access', $this->siteLangId);
            if (true ===  MOBILE_APP_API_CALL) {
                FatUtility::dieJsonError(strip_tags($message));
            }
            Message::addErrorMessage($message);
            FatUtility::dieWithError(Message::getHtml());
        }

        $ua_state_id = FatUtility::int($post['ua_state_id']);
        $post = $frm->getFormDataFromArray($post);
        if (false === $post) {
            if (true ===  MOBILE_APP_API_CALL) {
                FatUtility::dieJsonError(strip_tags(current($frm->getValidationErrors())));
            }
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieWithError(Message::getHtml());
        }
        $post['ua_state_id'] = $ua_state_id;

        $ua_id = FatUtility::int($post['ua_id']);
        unset($post['ua_id']);

        $addressObj = new UserAddress($ua_id);

        $data_to_be_save = $post;
        $data_to_be_save['ua_user_id'] = UserAuthentication::getLoggedUserId();

        $addressObj->assignValues($data_to_be_save, true);
        if (!$addressObj->save()) {
            if (true ===  MOBILE_APP_API_CALL) {
                FatUtility::dieJsonError(strip_tags($addressObj->getError()));
            }
            Message::addErrorMessage($addressObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        if (0 <= $ua_id) {
            $ua_id = $addressObj->getMainTableRecordId();
        }

        $this->set('ua_id', $ua_id);

        if (true ===  MOBILE_APP_API_CALL) {
            $this->_template->render();
        }
        $this->set('msg', Labels::getLabel('LBL_Setup_Successful', $this->siteLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function setDefault()
    {
        $post = FatApp::getPostedData();
        if (empty($post)) {
            $message = Labels::getLabel('MSG_Invalid_Access', $this->siteLangId);
            if (true ===  MOBILE_APP_API_CALL) {
                FatUtility::dieJsonError(strip_tags($message));
            }
            Message::addErrorMessage($message);
            FatUtility::dieWithError(Message::getHtml());
        }

        $ua_id = FatUtility::int($post['id']);
        if (1 > $ua_id) {
            $message = Labels::getLabel('MSG_Invalid_Access', $this->siteLangId);
            if (true ===  MOBILE_APP_API_CALL) {
                FatUtility::dieJsonError(strip_tags($message));
            }
            Message::addErrorMessage($message);
            FatUtility::dieWithError(Message::getHtml());
        }

        $addressDetail = UserAddress::getUserAddresses(UserAuthentication::getLoggedUserId(), 0, 0, $ua_id);

        if (empty($addressDetail)) {
            $message = Labels::getLabel('MSG_Invalid_request', $this->siteLangId);
            if (true ===  MOBILE_APP_API_CALL) {
                FatUtility::dieJsonError(strip_tags($message));
            }
            Message::addErrorMessage($message);
            FatUtility::dieWithError(Message::getHtml());
        }

        $updateArray = array('ua_is_default'=>0);
        $whr = array('smt'=>'ua_user_id = ?', 'vals'=>array(UserAuthentication::getLoggedUserId()));

        if (!FatApp::getDb()->updateFromArray(UserAddress::DB_TBL, $updateArray, $whr)) {
            $message = Labels::getLabel('MSG_Invalid_Access', $this->siteLangId);
            if (true ===  MOBILE_APP_API_CALL) {
                FatUtility::dieJsonError(strip_tags($message));
            }
            Message::addErrorMessage($message);
            FatUtility::dieWithError(Message::getHtml());
        }

        $addressObj = new UserAddress($ua_id);
        $data = array(
        'ua_id'=>$ua_id,
        'ua_is_default'=>1,
        'ua_user_id'=>UserAuthentication::getLoggedUserId(),
        );

        $addressObj->assignValues($data, true);
        if (!$addressObj->save()) {
            if (true ===  MOBILE_APP_API_CALL) {
                FatUtility::dieJsonError(strip_tags($addressObj->getError()));
            }
            Message::addErrorMessage($addressObj->getError());
            FatUtility::dieWithError(Message::getHtml());
        }
        if (true ===  MOBILE_APP_API_CALL) {
            $this->_template->render();
        }
        $this->set('msg', Labels::getLabel('LBL_Setup_Successful', $this->siteLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function deleteRecord()
    {
        $post = FatApp::getPostedData();
        if (empty($post)) {
            $message = Labels::getLabel('MSG_Invalid_Access', $this->siteLangId);
            if (true ===  MOBILE_APP_API_CALL) {
                FatUtility::dieJsonError(strip_tags($message));
            }
            Message::addErrorMessage($message);
            FatUtility::dieWithError(Message::getHtml());
        }

        $ua_id = FatUtility::int($post['id']);
        if (1 > $ua_id) {
            $message = Labels::getLabel('MSG_Invalid_Access', $this->siteLangId);
            if (true ===  MOBILE_APP_API_CALL) {
                FatUtility::dieJsonError(strip_tags($message));
            }
            Message::addErrorMessage($message);
            FatUtility::dieWithError(Message::getHtml());
        }

        $data =  UserAddress::getUserAddresses(UserAuthentication::getLoggedUserId(), $this->siteLangId, 0, $ua_id);
        if (empty($data)) {
            $message = Labels::getLabel('MSG_Invalid_request', $this->siteLangId);
            if (true ===  MOBILE_APP_API_CALL) {
                FatUtility::dieJsonError(strip_tags($message));
            }
            Message::addErrorMessage($message);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $addressObj = new UserAddress($ua_id);
        if (!$addressObj->deleteRecord()) {
            if (true ===  MOBILE_APP_API_CALL) {
                FatUtility::dieJsonError(strip_tags($addressObj->getError()));
            }
            Message::addErrorMessage($addressObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        if (true ===  MOBILE_APP_API_CALL) {
            $this->_template->render();
        }
        FatUtility::dieJsonSuccess(Labels::getLabel('MSG_Deleted_successfully', $this->siteLangId));
    }
}
