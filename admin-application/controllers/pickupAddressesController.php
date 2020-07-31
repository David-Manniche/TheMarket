<?php

class pickupAddressesController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewPickupAddresses();
    }
    
    public function index()
    {
        $this->set('canEdit', $this->objPrivilege->canEditPickupAddresses($this->admin_id, true));
        $this->_template->render();
    }
    
    public function search()
    {
        $address = new Address(0, $this->adminLangId);
        $addresses = $address->getData(Address::TYPE_ADMIN_PICKUP, 0);
        $this->set('arr_listing', $addresses);
        $this->set('canEdit', $this->objPrivilege->canEditPickupAddresses($this->admin_id, true));
        $this->_template->render(false, false);
    }
    
    public function form($addressId = 0, $langId = 0)
    {
        $this->objPrivilege->canEditPickupAddresses();
        $stateId = 0;
        $langId = FatUtility::int($langId);
        if($langId == 0){
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG', FatUtility::VAR_INT, 1);
        }        
        $addressId = FatUtility::int($addressId);
        $frm = $this->getForm($addressId, $langId);
        if (0 < $addressId) {
            $address = new Address($addressId, $langId);  
            $data = $address->getData(Address::TYPE_ADMIN_PICKUP, 0);
            if ($data === false) {
                FatUtility::dieWithError($this->str_invalid_request);
            }
            $stateId = $data['addr_state_id'];
            $frm->fill($data);
        }

        $this->set('addressId', $addressId);
        $this->set('frm', $frm);
        $this->set('stateId', $stateId);
        $this->set('langId', $langId);
        $this->set('formLayout', Language::getLayoutDirection($langId));
        $this->_template->render(false, false);
    }
    
    private function getForm($addressId = 0, $langId)
    {
        $addressId = FatUtility::int($addressId);
        $frm = new Form('frmAddress');
        $frm->addHiddenField('', 'addr_id', $addressId);
        $frm->addSelectBox(Labels::getLabel('LBL_LANGUAGE', $langId), 'lang_id', Language::getAllNames(), $langId, array(), '');
        $frm->addTextBox(Labels::getLabel('LBL_Address_Label', $langId), 'addr_title');
        $frm->addRequiredField(Labels::getLabel('LBL_Name', $langId), 'addr_name');
        $frm->addRequiredField(Labels::getLabel('LBL_Address_Line1', $langId), 'addr_address1');
        $frm->addTextBox(Labels::getLabel('LBL_Address_Line2', $langId), 'addr_address2');

        $countryObj = new Countries();
        $countriesArr = $countryObj->getCountriesArr($langId);
        $frm->addSelectBox(Labels::getLabel('LBL_Country', $langId), 'addr_country_id', $countriesArr, '', array(), Labels::getLabel('LBL_Select', $this->adminLangId))->requirement->setRequired(true);;

        $frm->addSelectBox(Labels::getLabel('LBL_State', $langId), 'addr_state_id', array(), '', array(), Labels::getLabel('LBL_Select', $this->adminLangId))->requirement->setRequired(true);
        $frm->addRequiredField(Labels::getLabel('LBL_City', $langId), 'addr_city');

        $zipFld = $frm->addRequiredField(Labels::getLabel('LBL_Postalcode', $langId), 'addr_zip');
        /* $zipFld->requirements()->setRegularExpressionToValidate(ValidateElement::ZIP_REGEX);
        $zipFld->requirements()->setCustomErrorMessage(Labels::getLabel('LBL_Only_alphanumeric_value_is_allowed.', $langId)); */

        $phnFld = $frm->addRequiredField(Labels::getLabel('LBL_Phone', $langId), 'addr_phone', '', array('class' => 'phone-js ltr-right', 'placeholder' => ValidateElement::PHONE_NO_FORMAT, 'maxlength' => ValidateElement::PHONE_NO_LENGTH));
        $phnFld->requirements()->setRegularExpressionToValidate(ValidateElement::PHONE_REGEX);        
        $phnFld->requirements()->setCustomErrorMessage(Labels::getLabel('LBL_Please_enter_valid_phone_number_format.', $langId));

        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $langId));
        return $frm;
    }
    
    public function setup()
    {
        $this->objPrivilege->canEditPickupAddresses();
        $post = FatApp::getPostedData();
        $post['addr_phone'] = !empty($post['addr_phone']) ? ValidateElement::convertPhone($post['addr_phone']) : '';
        $addrStateId = FatUtility::int($post['addr_state_id']);
        $frm = $this->getForm($post['addr_id'], $post['lang_id']);
        $post = $frm->getFormDataFromArray($post);
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }
        $addressId = $post['addr_id'];
        unset($post['addr_id']);
        
		$address = new Address($addressId);
        $data = $post;
        $data['addr_state_id'] = $addrStateId;
        $data['addr_lang_id'] = $post['lang_id'];
        $data['addr_type'] = Address::TYPE_ADMIN_PICKUP;
        $address->assignValues($data);
        if (!$address->save()) {
            Message::addErrorMessage($address->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $this->set('msg', Labels::getLabel('LBL_Updated_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }
    
    public function deleteRecord()
    {
        $this->objPrivilege->canEditPickupAddresses();
        $addressId = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if ($addressId < 1) {
            FatUtility::dieJsonError($this->str_invalid_request_id);
        }
        if (!FatApp::getDb()->deleteRecords(Address::DB_TBL, array('smt' => 'addr_type = ? AND addr_id = ?', 'vals' => array(Address::TYPE_ADMIN_PICKUP, $addressId)))) {
            Message::addErrorMessage(FatApp::getDb()->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }
        $this->set('msg', $this->str_delete_record);
        $this->_template->render(false, false, 'json-success.php');
    }
}
