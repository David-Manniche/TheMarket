<?php

class StripeConnectController extends PaymentMethodBaseController
{
    public const KEY_NAME = 'StripeConnect';
    private $stripeConnect;
    public $requiredKeys = [
        'publishable_key',
        'secret_key'
    ];

    public function __construct($action)
    {
        parent::__construct($action);
        $error = '';
        if (false === PluginHelper::includePlugin(self::KEY_NAME, 'payment-methods', $this->siteLangId, $error)) {
            Message::addErrorMessage($error);
            $this->redirectBack();
        }
        $this->stripeConnect = new StripeConnect($this->siteLangId);
        if (false === $this->stripeConnect->isUserValid() && !empty($this->stripeConnect->getError())) {
            Message::addErrorMessage($this->stripeConnect->getError());
            $this->redirectBack();
        }
    }

    public function index()
    {
        $this->set('accountId', $this->stripeConnect->getAccountId());
        $this->set('requiredFields', $this->stripeConnect->getRequiredFields());
        $this->set('isFinancialInfoRequired', $this->stripeConnect->isFinancialInfoRequired());
        $this->set('userData', $this->getUserMeta());
        $this->set('keyName', self::KEY_NAME);
        $this->set('pluginName', $this->getPluginData('plugin_name'));
        $this->_template->render();
    }

    public function requiredFieldsForm()
    {
        $frm = $this->getRequiredFieldsForm();
        $this->set('frm', $frm);
        $this->set('keyName', self::KEY_NAME);
        $this->_template->render(false, false);
    }

    public function financialInfoForm()
    {
        $frm = $this->getFinancialInfoForm();
        $this->set('frm', $frm);
        $this->set('keyName', self::KEY_NAME);
        $this->_template->render(false, false);
    }

    public function setupRequiredFields()
    {
        $frm = $this->getRequiredFieldsForm();
        $post = array_filter($frm->getFormDataFromArray(FatApp::getPostedData()));
        $this->stripeConnect->updateRequiredFields($post, false);
    }

    
    public function setupFinancialInfo()
    {
        $frm = $this->getFinancialInfoForm();
        $post = array_filter($frm->getFormDataFromArray(FatApp::getPostedData()));
        unset($post['btn_submit']);
        // $this->stripeConnect->updateFinancialInfo($post);
    }

    private function getRequiredFieldsForm()
    {
        $frm = new Form('frm' . self::KEY_NAME);

        $fieldsData = $this->stripeConnect->getRequiredFields();
        foreach ($fieldsData as $field) {
            if ('business_type' == $field) {
                return $this->getBusinessTypeForm();
            } else if ('external_account' == $field) {
                return $this->getFinancialInfoForm();
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
            $label = ucwords(str_replace("_", " ", $label));
            $fld = $frm->addTextBox($label, $name);
            $fld->requirement->setRequired(true);
        }
        $submitBtn = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->siteLangId));
        $cancelButton = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear', $this->siteLangId), array('onclick' => 'clearForm();'));
        $submitBtn->attachField($cancelButton);
        return $frm;
    }

    private function getBusinessTypeForm()
    {
        $frm = new Form('frm' . self::KEY_NAME);

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

        $fld = $frm->addSelectBox(Labels::getLabel('LBL_BUSINESS_TYPE', $this->siteLangId), 'business_type', $options);
        $fld->requirement->setRequired(true);
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->siteLangId));
        return $frm;
    }

    private function getFinancialInfoForm()
    {
        $frm = new Form('frm' . self::KEY_NAME);
        $frm->addRequiredField(Labels::getLabel('LBL_ACCOUNT_HOLDER_NAME', $this->siteLangId), 'account_holder_name');
        $frm->addRequiredField(Labels::getLabel('LBL_ACCOUNT_NUMBER', $this->siteLangId), 'account_number');

        $options = [
            'individual' => Labels::getLabel('LBL_INDIVIDUAL', $this->siteLangId),
            'company' => Labels::getLabel('LBL_COMPANY', $this->siteLangId),
        ];
        $fld = $frm->addSelectBox(Labels::getLabel('LBL_ACCOUNT_HOLDER_TYPE', $this->siteLangId), 'account_holder_type', $options);
        $fld->requirement->setRequired(true);
        $submitBtn = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->siteLangId));
        $cancelButton = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear', $this->siteLangId), array('onclick' => 'clearForm();'));
        $submitBtn->attachField($cancelButton);
        return $frm;
    }
}
