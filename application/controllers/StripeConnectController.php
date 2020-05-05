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
        $post = FatApp::getPostedData();
        CommonHelper::printArray($post, true);
        $this->stripeConnect->updateRequiredFields($post, false);
    }

    
    public function setupFinancialInfo()
    {
        $post = FatApp::getPostedData();
        CommonHelper::printArray($post, true);
        $this->stripeConnect->updateFinancialInfo($post, false);
    }

    private function getRequiredFieldsForm()
    {
        $frm = new Form('frm' . self::KEY_NAME);

        $fieldsData = $this->stripeConnect->getRequiredFields();
        foreach ($fieldsData as $field) {
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

    private function getFinancialInfoForm()
    {
        $frm = new Form('frm' . self::KEY_NAME);
        $frm->addRequiredField(Labels::getLabel('LBL_ACCOUNT_HOLDER_NAME', $this->siteLangId), 'account_holder_name');
        $frm->addRequiredField(Labels::getLabel('LBL_ACCOUNT_NUMBER', $this->siteLangId), 'account_number');

        $options = [
            'individual' => Labels::getLabel('LBL_INDIVIDUAL', $this->siteLangId),
            'company' => Labels::getLabel('LBL_COMPANY', $this->siteLangId),
        ];
        $fld = $frm->addSelectBox(Labels::getLabel('LBL_ACCOUNT_HOLDER_TYPE', $this->siteLangId), 'account_holder_type');
        $fld->requirement->setRequired(true);
        $submitBtn = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->siteLangId));
        $cancelButton = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear', $this->siteLangId), array('onclick' => 'clearForm();'));
        $submitBtn->attachField($cancelButton);
        return $frm;
    }
}
