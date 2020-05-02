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
        $this->set('businessProfile', $this->stripeConnect->getBusinessProfile());
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

    public function businessProfileForm()
    {
        $frm = $this->getBusinessProfileForm();
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

    private function getBusinessProfileForm()
    {
        $frm = new Form('frm' . self::KEY_NAME);

        $fieldsData = $this->stripeConnect->getBusinessProfile();
        foreach ($fieldsData as $field =>$value) {
            $name = $label = $field;
            $label = ucwords(str_replace("_", " ", $label));
            $fld = $frm->addTextBox($label, $name, $value);
            $fld->requirement->setRequired(true);
        }
        $submitBtn = $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->siteLangId));
        $cancelButton = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear', $this->siteLangId), array('onclick' => 'clearForm();'));
        $submitBtn->attachField($cancelButton);
        return $frm;
    }
}
