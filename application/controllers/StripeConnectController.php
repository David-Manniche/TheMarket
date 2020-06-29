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
        $this->stripeConnect = PluginHelper::callPlugin(self::KEY_NAME, [$this->siteLangId], $error, $this->siteLangId);
        if (false === $this->stripeConnect) {
            FatUtility::dieJsonError($error);
        }

        $userId = UserAuthentication::getLoggedUserId(true);
        if (1 > $userId) {
            $msg = Labels::getLabel('MSG_INVALID_USER', $this->siteLangId);
            FatUtility::dieJsonError($msg);
        }

        if (false === User::isSeller()) {
            $msg = Labels::getLabel('MSG_LOGGED_USED_MUST_BE_SELLER_TYPE', $this->siteLangId);
            FatUtility::dieJsonError($msg);
        }

        if (false === $this->stripeConnect->init($userId)) {
            $this->setError();
        }

        if (!empty($this->stripeConnect->getError())) {
            $this->setError();
        }
    }

    private function setError(string $msg = "")
    {
        $msg = !empty($msg) ? $msg : $this->stripeConnect->getError();
        LibHelper::exitWithError($msg, true);
    }


    public function index()
    {
        $accountId = $this->stripeConnect->getAccountId();
        
        if (!empty($accountId)) {
            if (true === $this->stripeConnect->isUserAccountRejected()) {
                $this->setError();
            }

            if (false === $this->stripeConnect->verifyInitialSetup()) {
                $this->getInitialSetupForm();
            }
        }
        $requiredFields = $this->stripeConnect->getRequiredFields();
        // This will return url only for ExpressAccount connected to admin account.
        $this->stripeConnect->createLoginLink();

        $this->set('loginUrl', $this->stripeConnect->getLoginUrl());
        $this->set('accountId', $this->stripeConnect->getAccountId());
        $this->set('requiredFields', $requiredFields);
        $this->set('keyName', self::KEY_NAME);
        $this->set('pluginName', $this->getPluginData()['plugin_name']);
        $this->set('stripeAccountType', $this->stripeConnect->getAccountType());
        $json['status'] = 1;
        $json['html'] = $this->_template->render(false, false, 'stripe-connect/index.php', true, false);
        FatUtility::dieJsonSuccess($json);
    }

    public function register()
    {
        if (false === $this->stripeConnect->register()) {
            $this->setError();
        }
        $msg = Labels::getLabel('MSG_SETUP_SUCCESSFULLY', $this->siteLangId);
        FatUtility::dieJsonSuccess($msg);
    }

    public function login()
    {
        FatApp::redirectUser($this->stripeConnect->getRedirectUri());
    }

    public function callback()
    {
        $code = FatApp::getQueryStringData('code');
        if (false == $this->stripeConnect->accessAccountId($code)) {
            $this->setError();
        }
        FatApp::redirectUser(CommonHelper::generateUrl('seller', 'shop', [self::KEY_NAME]));
    }

    public function initialSetup()
    {
        $post = array_filter(FatApp::getPostedData());
        if (isset($post['fIsAjax'])) {
            unset($post['fOutMode'], $post['fIsAjax']);
        }

        if (false === $this->stripeConnect->initialFieldsSetup($post)) {
            $this->setError();
        }
        $msg = Labels::getLabel('MSG_SETUP_SUCCESSFULLY', $this->siteLangId);
        FatUtility::dieJsonSuccess($msg);
    }

    private function getInitialSetupForm()
    {
        $initialFieldsStatus = $this->stripeConnect->verifyInitialSetup();

        if (false === $initialFieldsStatus && !empty($this->stripeConnect->getError())) {
            $this->setError();
        }

        if (true === $this->stripeConnect->verifyInitialSetup()) {
            $msg = Labels::getLabel('MSG_NO_MORE_INITIAL_FIELDS_PENDING', $this->siteLangId);
            $this->setError($msg);
        }

        $initialFieldsValue = $this->stripeConnect->initialFieldsValue();
        $frm = $this->initialSetupForm();
        $frm->fill($initialFieldsValue);
        
        $pageTitle = Labels::getLabel('LBL_INITIAL_ACCOUNT_SETUP', $this->siteLangId);
        $stateCode = isset($initialFieldsValue['business_profile']['state']) ? $initialFieldsValue['business_profile']['state'] : 0;

        $errors = $this->stripeConnect->getErrorWhileUpdate();
        $this->set('errors', $errors);

        $this->set('frm', $frm);
        $this->set('stateCode', $stateCode);
        $this->set('pageTitle', $pageTitle);
        $this->set('keyName', self::KEY_NAME);
        $json['status'] = 1;
        $json['html'] = $this->_template->render(false, false, 'stripe-connect/get-initial-setup-form.php', true, false);
        FatUtility::dieJsonSuccess($json);
    }

    private function initialSetupForm()
    {
        $initialFields = $this->stripeConnect->getInitialPendingFields();
        $frm = new Form('frm' . self::KEY_NAME);

        if (in_array('email', $initialFields)) {
            $fld = $frm->addRequiredField(Labels::getLabel('LBL_EMAIL', $this->siteLangId), 'email');
            $fld->requirement->setRequired(true);
        }

        if (in_array('business_profile', $initialFields)) {
            $frm->addHiddenField('', 'business_profile[url]');
            $frm->addHiddenField('', 'business_profile[support_url]');
            $frm->addRequiredField(Labels::getLabel('LBL_SHOP_NAME', $this->siteLangId), 'business_profile[name]');
            $frm->addRequiredField(Labels::getLabel('LBL_SUPPORT_PHONE', $this->siteLangId), 'business_profile[support_phone]');
            $frm->addRequiredField(Labels::getLabel('LBL_SUPPORT_EMAIL', $this->siteLangId), 'business_profile[support_email]');

            $frm->addRequiredField(Labels::getLabel('LBL_SUPPORT_ADDRESS_LINE_1', $this->siteLangId), 'business_profile[support_address][line1]');
            $frm->addRequiredField(Labels::getLabel('LBL_SUPPORT_ADDRESS_LINE_2', $this->siteLangId), 'business_profile[support_address][line2]');
            $frm->addRequiredField(Labels::getLabel('LBL_SUPPORT_ADDRESS_POSTAL_CODE', $this->siteLangId), 'business_profile[support_address][postal_code]');

            $frm->addRequiredField(Labels::getLabel('LBL_SUPPORT_ADDRESS_CITY', $this->siteLangId), 'business_profile[support_address][city]');

            $countryObj = new Countries();
            $countriesArr = $countryObj->getCountriesArr($this->siteLangId, true, 'country_code');
            $frm->addSelectBox(Labels::getLabel('LBL_SUPPORT_ADDRESS_COUNTRY', $this->siteLangId), 'business_profile[support_address][country]', $countriesArr, '', ['id' => 'country'])->requirement->setRequired(true);

            $frm->addSelectBox(Labels::getLabel('LBL_SUPPORT_ADDRESS_STATE', $this->siteLangId), 'business_profile[support_address][state]', [], '', ['id' => 'state', 'disabled' => 'disabled'])->requirement->setRequired(true);
        }

        if (in_array('tos_acceptance', $initialFields)) {
            $fld = $frm->addCheckBox(Labels::getLabel('LBL_I_AGREE', $this->siteLangId), 'tos_acceptance', 1);
            $fld->requirement->setRequired(true);
            $fld->htmlAfterField = ' <a href="' . $this->stripeConnect::TERMS_AND_SERVICES_URI . '" target="_blank">' . $label = Labels::getLabel('LBL_TERMS_OF_SERVICE', $this->siteLangId) . '</a>';
        }

        if (0 < count($initialFields)) {
            $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->siteLangId));
            return $frm;
        }

        return false;
    }

    public function requiredFieldsForm()
    {
        $frm = $this->getRequiredFieldsForm();
        if (false === $frm) {
            FatUtility::dieJsonSuccess($this->msg);
        }

        $fieldType = '';
        $pageTitle = Labels::getLabel('LBL_USER_DETAIL', $this->siteLangId);
        if (true === $this->businessTypeForm) {
            $pageTitle = Labels::getLabel('LBL_BUSINESS_TYPE', $this->siteLangId);
        } elseif (true === $this->externalForm) {
            $pageTitle = Labels::getLabel('LBL_FINANCIAL_INFORMATION', $this->siteLangId);
            $fieldType = 'external_account';
        }

        $errors = $this->stripeConnect->getErrorWhileUpdate();
        $this->set('errors', $errors);
        $this->set('pageTitle', $pageTitle);
        $this->set('frm', $frm);
        $this->set('keyName', self::KEY_NAME);
        $json['status'] = 0;
        $json['html'] = $this->_template->render(false, false, 'stripe-connect/required-fields-form.php', true, false);
        FatUtility::dieJsonSuccess($json);
    }

    private function validateResponse($resp)
    {
        if (false === $resp) {
            Message::addErrorMessage($this->stripeConnect->getError());
            FatApp::redirectUser(CommonHelper::generateUrl('seller', 'shop', [self::KEY_NAME]));
        }
        return true;
    }

    public function setupRequiredFields()
    {
        $post = array_filter(FatApp::getPostedData());
        
        $redirect = true;
        if (isset($post['fIsAjax'])) {
            $redirect = false;
            unset($post['fOutMode'], $post['fIsAjax']);
        }

        if (array_key_exists('verification', $_FILES) && !empty($this->stripeConnect->getRelationshipPersonId())) {
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
                FatApp::redirectUser(CommonHelper::generateUrl('seller', 'shop', [self::KEY_NAME]));
            }
            FatUtility::dieJsonError($msg);
        }
        $msg = Labels::getLabel('MSG_SUCCESS', $this->siteLangId);
        if (true === $redirect) {
            Message::addMessage($msg);
            FatApp::redirectUser(CommonHelper::generateUrl('seller', 'shop', [self::KEY_NAME]));
        }

        FatUtility::dieJsonSuccess($msg);
    }

    public function setupFinancialInfo()
    {
        $frm = $this->getFinancialInfoForm();
        $post = array_filter($frm->getFormDataFromArray(FatApp::getPostedData()));
        if (false === $this->stripeConnect->updateFinancialInfo($post)) {
            $this->setError();
        }
        FatUtility::dieJsonSuccess(Labels::getLabel('MSG_SUCCESS', $this->siteLangId));
    }

    private function getRequiredFieldsForm()
    {
        $fieldsData = $this->stripeConnect->getRequiredFields();
        if (empty($fieldsData)) {
            $this->msg = Labels::getLabel('MSG_SUCCESSFULLY_SUBMITTED_TO_REVIEW', $this->siteLangId);
            return false;
        }
        $frm = new Form('frm' . self::KEY_NAME);
        foreach ($fieldsData as $field) {
            if ('business_type' == $field) {
                return $this->getBusinessTypeForm($field);
            } elseif ('external_account' == $field) {
                return $this->getFinancialInfoForm($field);
            }

            $name = $label = $field;
            if (false !== strpos($field, ".")) {
                $labelParts = explode(".", $field);
                $label = implode(" ", $labelParts);
                $name = $labelParts[0];
                foreach ($labelParts as $i => $nameVal) {
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
            } elseif (false !== strpos($field, 'verification.document')) {
                if (empty($this->stripeConnect->getRelationshipPersonId())) {
                    continue;
                }

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

    public function deleteAccount()
    {
        if (false === $this->stripeConnect->deleteAccount()) {
            $this->setError();
        }
        FatUtility::dieJsonSuccess(Labels::getLabel('MSG_DELETED_SUCCESSFULLY', $this->siteLangId));
    }
}
