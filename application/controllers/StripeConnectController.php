<?php

class StripeConnectController extends PaymentMethodBaseController
{
    public const KEY_NAME = 'StripeConnect';
    private $stripeConnect;

    private $businessTypeForm = false;
    private $externalForm = false;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->init();
    }

    public function init()
    {
        $error = '';
        if (false === PluginHelper::includePlugin(self::KEY_NAME, 'payment-methods', $this->siteLangId, $error)) {
            Message::addErrorMessage($error);
            $this->redirectBack();
        }

        $this->stripeConnect = new StripeConnect($this->siteLangId);

        if (false === $this->stripeConnect->init()) {
            Message::addErrorMessage($this->stripeConnect->getError());
            $this->redirectBack();
        }

        if (!empty($this->stripeConnect->getError())) {
            Message::addErrorMessage($this->stripeConnect->getError());
            $this->redirectBack();
        }

        $this->settings = $this->stripeConnect->getKeys();
    }

    public function index()
    {
        $this->set('accountId', $this->stripeConnect->getAccountId());
        $this->set('requiredFields', $this->stripeConnect->getRequiredFields());
        $this->set('isFinancialInfoRequired', $this->stripeConnect->isFinancialInfoRequired());
        $this->set('userData', $this->getUserMeta());
        $this->set('keyName', self::KEY_NAME);
        $this->set('pluginName', $this->getPluginData('plugin_name'));
        $this->set('publishableKey', $this->settings['publishable_key']);
        $this->_template->render();
    }

    public function connect()
    {
        if (false === $this->stripeConnect->connect()) {
            Message::addErrorMessage($this->stripeConnect->getError());
        }
        $this->redirectBack(self::KEY_NAME);        
    }

    public function requiredFieldsForm()
    {
        $frm = $this->getRequiredFieldsForm();

        $fieldType = '';
        $pageTitle = Labels::getLabel('LBL_USER_DETAIL', $this->siteLangId);
        if (true === $this->businessTypeForm) {
            $pageTitle = Labels::getLabel('LBL_BUSINESS_TYPE', $this->siteLangId);
        } else if (true === $this->externalForm) {
            $pageTitle = Labels::getLabel('LBL_FINANCIAL_INFORMATION', $this->siteLangId);
            $fieldType = 'external_account';
        }

        $this->set('fieldType', $fieldType);    
        $this->set('pageTitle', $pageTitle);    
        $this->set('frm', $frm);
        $this->set('keyName', self::KEY_NAME);
        $this->_template->render(false, false);
    }

    public function setupRequiredFields()
    {
        $post = array_filter(FatApp::getPostedData());
        unset($post['fOutMode'], $post['fIsAjax']);
        if (false === $this->stripeConnect->updateRequiredFields($post)) {
            FatUtility::dieJsonError($this->stripeConnect->getError());
        }
        FatUtility::dieJsonSuccess(Labels::getLabel('MSG_SUCCESS', $this->siteLangId));
    }

    public function setupFinancialInfo()
    {
        $frm = $this->getFinancialInfoForm();
        $post = array_filter($frm->getFormDataFromArray(FatApp::getPostedData()));
        if (false === $this->stripeConnect->updateFinancialInfo($post)) {
            FatUtility::dieJsonError($this->stripeConnect->getError());
        }
        FatUtility::dieJsonSuccess(Labels::getLabel('MSG_SUCCESS', $this->siteLangId));
    }

    private function getRequiredFieldsForm()
    {
        $frm = new Form('frm' . self::KEY_NAME);
        
        $fieldsData = $this->stripeConnect->getRequiredFields();
        foreach ($fieldsData as $field) {
            if ('business_type' == $field) {
                return $this->getBusinessTypeForm($field);
            } else if ('external_account' == $field) {
                return $this->getFinancialInfoForm($field);
            }

            $name = $label = $field;
            if (0 < strpos($field, ".")) {
                $labelParts = explode(".", $field);
                $label = implode(" ", $labelParts);
                $name = $labelParts[0];
                foreach($labelParts as $i => $nameVal) {
                    if (0 == $i) {
                        continue;
                    }
                    $name .= '[' . $nameVal . ']';
                }
            }

            $relationshipBoolFields = [];
            if ('relationship' === $labelParts[0]) {
                $relationshipBoolFields = [
                    'director',
                    'executive',
                    'owner',
                    'representative'
                ];
            } else if (false !== strpos($label, 'person_')) {
                $personId = $this->getUserMeta('stripe_person_id');
                $label = str_replace($personId, "Person", $label);
            }

            $label = ucwords(str_replace("_", " ", $label));

            if (in_array(end($labelParts), $relationshipBoolFields)) {
                $options = [
                    0 => Labels::getLabel('LBL_NO', $this->siteLangId),
                    1 => Labels::getLabel('LBL_YES', $this->siteLangId)
                ];
                $fld = $frm->addSelectBox($label, $name, $options);
            } else {
                $fld = $frm->addTextBox($label, $name);
            }
            $fld->requirement->setRequired(true);
        }
        $submitBtn = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->siteLangId));
        $cancelButton = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear', $this->siteLangId), array('onclick' => 'clearForm();'));
        $submitBtn->attachField($cancelButton);
        return $frm;
    }

    private function getBusinessTypeForm(string $type)
    {
        $this->businessTypeForm = true;
        $frm = new Form('frm' . self::KEY_NAME);
        $frm->addHiddenField('', 'action_type', $type);
        $options = [
            'individual' => Labels::getLabel('LBL_INDIVIDUAL', $this->siteLangId),
            'company' => Labels::getLabel('LBL_COMPANY', $this->siteLangId),
            'non_profit' => Labels::getLabel('LBL_NON_PROFIT', $this->siteLangId)
        ];
        $defultCountryId = FatApp::getConfig('CONF_COUNTRY', FatUtility::VAR_INT, 0);
        $countryCode = Countries::getAttributesById($defultCountryId, 'country_code');
        if ('US' == strtoupper($countryCode)) {
            $options['government_entity'] = Labels::getLabel('LBL_GOVERNMENT_ENTITY', $this->siteLangId);
        }

        $fld = $frm->addSelectBox(Labels::getLabel('LBL_SELECT_BUSINESS_TYPE', $this->siteLangId), 'business_type', $options);
        $fld->requirement->setRequired(true);
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->siteLangId));
        return $frm;
    }

    private function getFinancialInfoForm(string $type)
    {
        $this->externalForm = true;

        $frm = new Form('frm' . self::KEY_NAME);
        $frm->addHiddenField('', 'action_type', $type);
        $frm->addRequiredField(Labels::getLabel('LBL_ACCOUNT_HOLDER_NAME', $this->siteLangId), 'account_holder_name');
        $frm->addRequiredField(Labels::getLabel('LBL_ACCOUNT_NUMBER', $this->siteLangId), 'account_number');

        $fld = $frm->addRequiredField(Labels::getLabel('LBL_ROUTING_NUMBER_(_IFSC_CODE_)', $this->siteLangId), 'routing_number');
        $submitBtn = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->siteLangId));
        $cancelButton = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear', $this->siteLangId), array('onclick' => 'clearForm();'));
        $submitBtn->attachField($cancelButton);
        return $frm;
    }
}
