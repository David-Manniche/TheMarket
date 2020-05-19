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
        $accountId = $this->stripeConnect->getAccountId();
        if (!empty($accountId) && true === $this->stripeConnect->isUserAccountRejected()) {
            Message::addErrorMessage($this->stripeConnect->getError());
            $this->redirectBack(self::KEY_NAME);
        }

        if (false === $this->stripeConnect->verifyInitialSetup()) {
            $this->redirectBack(self::KEY_NAME, 'initialSetup');
        }

        $this->set('accountId', $this->stripeConnect->getAccountId());
        $this->set('requiredFields', $requiredFields);
        $this->set('isFinancialInfoRequired', $this->stripeConnect->isFinancialInfoRequired());
        $this->set('userData', $this->getUserMeta());
        $this->set('keyName', self::KEY_NAME);
        $this->set('pluginName', $this->getPluginData('plugin_name'));
        $this->set('publishableKey', $this->settings['publishable_key']);
        $this->_template->render();
    }

    public function register()
    {
        if (false === $this->stripeConnect->register()) {
            Message::addErrorMessage($this->stripeConnect->getError());
        }
        $this->redirectBack(self::KEY_NAME);
    }

    public function login()
    {
        FatApp::redirectUser($this->stripeConnect->getRedirectUri());   
    }

    public function callback()
    {
        $code = FatApp::getQueryStringData('code');
        if (false == $this->stripeConnect->accessAccountId($code)) {
            Message::addErrorMessage($this->stripeConnect->getError());
        }
        $this->redirectBack(self::KEY_NAME);
    }

    public function initialSetup()
    {
        $this->stripeConnect->verifyInitialSetup();
    }

    private function initialSetupForm()
    {
        $frm = new Form('frm' . self::KEY_NAME);

        $url = 'https://satbir.yokartv8.4livedemo.com' . CommonHelper::generateUrl('shops', 'view', [$this->userData['shop_id']]);

        $frm->addHiddenField('', 'business_profile[url]', $url);
        $frm->addHiddenField('', 'business_profile[support_url]', $url);
        $frm->addRequiredField(Labels::getLabel('LBL_SHOP_NAME', $this->siteLangId), 'business_profile[name]');
        $frm->addRequiredField(Labels::getLabel('LBL_SUPPORT_PHONE', $this->siteLangId), 'business_profile[support_phone]');
        $frm->addRequiredField(Labels::getLabel('LBL_SUPPORT_EMAIL', $this->siteLangId), 'business_profile[support_email]');
        $frm->addRequiredField(Labels::getLabel('LBL_SUPPORT_ADDRESS_CITY', $this->siteLangId), 'business_profile[support_address][city]');
        $fld = $frm->addRequiredField(Labels::getLabel('LBL_SUPPORT_ADDRESS_COUNTRY', $this->siteLangId), 'business_profile[support_address][country]');
        $fld->htmlAfterField = Labels::getLabel('LBL_USE_COUNTRY_CODE_INSTEAD_OF_FULL_NAME', $this->siteLangId);

        $frm->addRequiredField(Labels::getLabel('LBL_SUPPORT_ADDRESS_LINE_1', $this->siteLangId), 'business_profile[support_address][line1]');
        $frm->addRequiredField(Labels::getLabel('LBL_SUPPORT_ADDRESS_LINE_2', $this->siteLangId), 'business_profile[support_address][line2]');
        $frm->addRequiredField(Labels::getLabel('LBL_SUPPORT_ADDRESS_POSTAL_CODE', $this->siteLangId), 'business_profile[support_address][postal_code]');

        $fld = $frm->addRequiredField(Labels::getLabel('LBL_SUPPORT_ADDRESS_STATE', $this->siteLangId), 'business_profile[support_address][state]');
        $fld->htmlAfterField = Labels::getLabel('LBL_USE_STATE_CODE_INSTEAD_OF_FULL_NAME', $this->siteLangId);

        $fld = $frm->addCheckBox(Labels::getLabel('LBL_I_AGREE_TO_THE_TERMS_OF_SERVICES', $this->siteLangId), 'tos_acceptance', 1);
        $fld->requirement->setRequired(true);
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->siteLangId));
        return $frm;
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

    private function validateResponse($resp)
    {
        if (false === $resp) {
            Message::addErrorMessage($this->stripeConnect->getError());
            $this->redirectBack(self::KEY_NAME);
        }
        return true;
    }

    public function setupRequiredFields()
    {
        $post = array_filter(FatApp::getPostedData());
        if (isset($post['fIsAjax'])) {
            unset($post['fOutMode'], $post['fIsAjax']);
        }

        $redirect = false;
        if (array_key_exists('verification', $_FILES)) {
            $redirect = true;
            foreach ($_FILES['verification']['tmp_name']['document'] as $side => $filePath) {
                $resp = $this->stripeConnect->uploadVerificationFile($filePath);
                $this->validateResponse($resp);

                $resp = $this->stripeConnect->updateVericationDocument($side);
                $this->validateResponse($resp);
            }
        }

        if (false === $this->stripeConnect->updateRequiredFields($post)) {
            $msg = $this->stripeConnect->getError();
            if (true === $redirect) {
                Message::addErrorMessage($msg);
                $this->redirectBack(self::KEY_NAME);
            }
            FatUtility::dieJsonError($msg);
        }
        $msg = Labels::getLabel('MSG_SUCCESS', $this->siteLangId);
        if (true === $redirect) {
            Message::addMessage($msg);
            $this->redirectBack(self::KEY_NAME);
        }
        FatUtility::dieJsonSuccess($msg);
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
            if (false !== strpos($field, ".")) {
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
            if (false !== strpos($field, "relationship")) {
                $relationshipBoolFields = [
                    'director',
                    'executive',
                    'owner',
                    'representative'
                ];
            }

            if (false !== strpos($label, 'person_')) {
                $personId = $this->getUserMeta('stripe_person_id');
                $label = str_replace($personId, "Person", $label);
            }

            if ('individual' === $labelParts[0] && 'id_number' == end($labelParts)) {
                continue;
            }

            $htmlAfterField = '';
            if (false !== strpos($label, 'state') || false !== strpos($label, 'country')) {
                $htmlAfterField = Labels::getLabel('LBL_USE_COUNTRY/STATE_CODE_INSTEAD_OF_FULL_NAME', $this->siteLangId);
            }

            $label = ucwords(str_replace("_", " ", $label));
            if (in_array(end($labelParts), $relationshipBoolFields)) {
                $options = [
                    0 => Labels::getLabel('LBL_NO', $this->siteLangId),
                    1 => Labels::getLabel('LBL_YES', $this->siteLangId)
                ];
                $fld = $frm->addSelectBox($label, $name, $options);
            } else if (false !== strpos($field, 'verification.document')) {
                $lbl = Labels::getLabel("LBL_IDENTIFYING_DOCUMENT,_EITHER_A_PASSPORT_OR_LOCAL_ID_CARD", $this->siteLangId);
                $lblFront = $lbl . ' ' . Labels::getLabel("LBL_FRONT", $this->siteLangId);
                $lblBack = $lbl . ' ' . Labels::getLabel("LBL_BACK", $this->siteLangId);
                $htmlAfterField = Labels::getLabel("LBL_THE_UPLOADED_FILE_NEEDS_TO_BE_A_COLOR_IMAGE_(SMALLER_THAN_8,000PX_BY_8,000px),_IN_JPG,_PNG,_OR_PDF_FORMAT,_AND_LESS_THAN_10_MB_IN_SIZE.", $this->siteLangId);

                $fld = $frm->addFileUpload($lblFront, 'verification[document][front]');
                $fld2 = $frm->addFileUpload($lblBack, 'verification[document][back]');
                $fld2->requirement->setRequired(true);
                $fld2->htmlAfterField = '<p class="note">' . $htmlAfterField . '</p>';

                $frm->addFormTagAttribute('enctype', 'multipart/form-data');
            } else {
                $fld = $frm->addTextBox($label, $name);
            }
            $fld->requirement->setRequired(true);
            if (!empty($htmlAfterField)) {
                $fld->htmlAfterField = '<p class="note">' . $htmlAfterField . '</p>';
            }
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
            'company' => Labels::getLabel('LBL_COMPANY', $this->siteLangId)
        ];

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
