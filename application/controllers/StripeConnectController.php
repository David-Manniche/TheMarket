<?php

class StripeConnectController extends PaymentMethodBaseController
{
    public const KEY_NAME = 'StripeConnect';
    private $stripeConnect;
    
    /**
     * __construct
     *
     * @param  mixed $action
     * @return void
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
        $this->init();
    }
    
    /**
     * init
     *
     * @return void
     */
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
    
    /**
     * setError
     *
     * @param  mixed $msg
     * @return void
     */
    private function setError(string $msg = "")
    {
        $msg = !empty($msg) ? $msg : $this->stripeConnect->getError();
        LibHelper::exitWithError($msg, true);
    }

    
    /**
     * index
     *
     * @return void
     */
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
    
    /**
     * register
     *
     * @return void
     */
    public function register()
    {
        if (false === $this->stripeConnect->register()) {
            $this->setError();
        }
        $msg = Labels::getLabel('MSG_SETUP_SUCCESSFULLY', $this->siteLangId);
        FatUtility::dieJsonSuccess($msg);
    }
    
    /**
     * login
     *
     * @return void
     */
    public function login()
    {
        FatApp::redirectUser($this->stripeConnect->getRedirectUri());
    }
    
    /**
     * callback
     *
     * @return void
     */
    public function callback()
    {
        $error = FatApp::getQueryStringData('error');
        $errorDescription = FatApp::getQueryStringData('error_description');
        if (!empty($error)) {
            $msg = $error . ' : ' . $errorDescription; 
            Message::addErrorMessage($msg);
        } else {
            $code = FatApp::getQueryStringData('code');
            if (false == $this->stripeConnect->accessAccountId($code)) {
                $this->setError();
            }
        }
        FatApp::redirectUser(UrlHelper::generateUrl('seller', 'shop', [self::KEY_NAME]));
    }
    
    /**
     * initialSetup
     *
     * @return void
     */
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
    
    /**
     * getInitialSetupForm
     *
     * @return void
     */
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
        $stateCode = isset($initialFieldsValue['business_profile']['support_address']['state']) ? $initialFieldsValue['business_profile']['support_address']['state'] : '';

        $errors = $this->stripeConnect->getErrorWhileUpdate();
        $this->set('errors', $errors);

        $this->set('frm', $frm);
        $this->set('stateCode', $stateCode);
        $this->set('pageTitle', $pageTitle);
        $this->set('keyName', self::KEY_NAME);
        $this->set('termAndConditionsUrl', $this->stripeConnect::TERMS_AND_SERVICES_URI);
        $json['status'] = 1;
        $json['html'] = $this->_template->render(false, false, 'stripe-connect/get-initial-setup-form.php', true, false);
        FatUtility::dieJsonSuccess($json);
    }
    
    /**
     * initialSetupForm
     *
     * @return object
     */
    private function initialSetupForm(): object
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

            $stateFldClass = 'state-' . time();
            $countryObj = new Countries();
            $countriesArr = $countryObj->getCountriesArr($this->siteLangId, true, 'country_code');
            $frm->addSelectBox(Labels::getLabel('LBL_SUPPORT_ADDRESS_COUNTRY', $this->siteLangId), 'business_profile[support_address][country]', $countriesArr, '', ['class' => 'country', 'data-statefield' => $stateFldClass])->requirement->setRequired(true);

            $frm->addSelectBox(Labels::getLabel('LBL_SUPPORT_ADDRESS_STATE', $this->siteLangId), 'business_profile[support_address][state]', [], '', ['class' => 'state ' . $stateFldClass, 'disabled' => 'disabled'])->requirement->setRequired(true);
        }

        if (in_array('relationship.representative', $initialFields)) {
            $fld = $frm->addSelectBox(Labels::getLabel('LBL_RELATIONSHIP_REPRESENTATIVE', $this->siteLangId), 'relationship[representative]', applicationConstants::getYesNoArr($this->siteLangId));
            $fld->requirement->setRequired(true);
        }

        if (in_array('tos_acceptance', $initialFields)) {
            $fld = $frm->addCheckBox('', 'tos_acceptance', 1);
            $fld->requirement->setRequired(true);
            // $fld->htmlAfterField = '<a href="' . $this->stripeConnect::TERMS_AND_SERVICES_URI . '" target="_blank" class="tosLink-js">' . $label = Labels::getLabel('LBL_I_AGREE_TO_THE_TERMS_OF_SERVICE', $this->siteLangId) . '</a>';
        }

        if (0 < count($initialFields)) {
            $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->siteLangId));
            return $frm;
        }

        return false;
    }
    
    /**
     * requiredFieldsForm
     *
     * @return void
     */
    public function requiredFieldsForm()
    {
        $frm = $this->getRequiredFieldsForm();
        if (false === $frm) {
            FatUtility::dieJsonSuccess($this->msg);
        }

        $errors = $this->stripeConnect->getErrorWhileUpdate();
        $this->set('errors', $errors);
        $this->set('frm', $frm);
        $this->set('keyName', self::KEY_NAME);
        $json['status'] = 0;
        $json['html'] = $this->_template->render(false, false, 'stripe-connect/required-fields-form.php', true, false);
        FatUtility::dieJsonSuccess($json);
    }
    
    /**
     * validateResponse
     *
     * @param  mixed $resp
     * @return void
     */
    private function validateResponse($resp)
    {
        if (false === $resp) {
            Message::addErrorMessage($this->stripeConnect->getError());
            FatApp::redirectUser(UrlHelper::generateUrl('seller', 'shop', [self::KEY_NAME]));
        }
        return true;
    }
    
    /**
     * setupRequiredFields
     *
     * @return void
     */
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

        $businessType = $this->getUserMeta('stripe_business_type');
        $updateSubmittedFormFlag = empty($businessType) ? 0 : 1;

        if (false === $this->stripeConnect->updateRequiredFields($post, $updateSubmittedFormFlag)) {
            $msg = $this->stripeConnect->getError();
            if (true === $redirect) {
                Message::addErrorMessage($msg);
                FatApp::redirectUser(UrlHelper::generateUrl('seller', 'shop', [self::KEY_NAME]));
            }
            FatUtility::dieJsonError($msg);
        }
        $msg = Labels::getLabel('MSG_SUCCESS', $this->siteLangId);
        if (true === $redirect) {
            Message::addMessage($msg);
            FatApp::redirectUser(UrlHelper::generateUrl('seller', 'shop', [self::KEY_NAME]));
        }

        FatUtility::dieJsonSuccess($msg);
    }
    
    /**
     * getRequiredFieldsForm
     *
     * @return object
     */
    private function getRequiredFieldsForm(): object
    {
        $fieldsData = $this->stripeConnect->getRequiredFields();
        if (empty($fieldsData)) {
            $this->msg = Labels::getLabel('MSG_SUCCESSFULLY_SUBMITTED_TO_REVIEW', $this->siteLangId);
            return false;
        }
        $frm = new Form('frm' . self::KEY_NAME);
        $stateFldClass = '';
        foreach ($fieldsData as $field) {
            if ('business_type' == $field) {
                return $this->getBusinessTypeForm($field);
            }

            $name = $label = $field;
            $labelParts = [];
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

            if (false !== strpos($label, 'person_')) {
                $personId = $this->getUserMeta('stripe_person_id');
                $label = str_replace($personId, "Person", $label);
            }

            if (isset($labelParts[0]) && 'individual' === $labelParts[0] && 'id_number' == end($labelParts)) {
                continue;
            }

            $labelStr = ucwords(str_replace("_", " ", $label));

            if (in_array(end($labelParts), $this->stripeConnect->boolParams)) {
                $options = [
                    0 => Labels::getLabel('LBL_NO', $this->siteLangId),
                    1 => Labels::getLabel('LBL_YES', $this->siteLangId)
                ];
                $fld = $frm->addSelectBox($labelStr, $name, $options);
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
            } elseif (false !== strpos($field, 'state')) {
                $frm->addSelectBox($labelStr, $name, [], '', ['class' => $stateFldClass, 'disabled' => 'disabled'])->requirement->setRequired(true);
            } elseif (false !== strpos($field, 'country')) {
                $stateFldClass = md5($name);
                $countryObj = new Countries();
                $countriesArr = $countryObj->getCountriesArr($this->siteLangId, true, 'country_code');
                $frm->addSelectBox($labelStr, $name, $countriesArr, '', ['class' => 'country', 'data-statefield' => $stateFldClass])->requirement->setRequired(true);
            } else {
                $fld = $frm->addTextBox($labelStr, $name);
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
    
    /**
     * getBusinessTypeForm
     *
     * @param  string $type
     * @return object
     */
    private function getBusinessTypeForm(string $type): object
    {
        $frm = new Form('frm' . self::KEY_NAME);
        $frm->addHiddenField('', 'action_type', $type);
        $options = [
            'individual' => Labels::getLabel('LBL_INDIVIDUAL', $this->siteLangId),
            'company' => Labels::getLabel('LBL_COMPANY', $this->siteLangId),
            'non_profit' => Labels::getLabel('LBL_NON_PROFIT', $this->siteLangId),
            'government_entity' => Labels::getLabel('LBL_GOVERNMENT_ENTITY_(_US_ONLY_)', $this->siteLangId)
        ];

        $fld = $frm->addSelectBox(Labels::getLabel('LBL_SELECT_BUSINESS_TYPE', $this->siteLangId), 'business_type', $options);
        $fld->requirement->setRequired(true);
        $frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_SAVE', $this->siteLangId));
        return $frm;
    }
    
    /**
     * deleteAccount
     *
     * @return void
     */
    public function deleteAccount()
    {
        if (false === $this->stripeConnect->deleteAccount()) {
            $this->setError();
        }
        FatUtility::dieJsonSuccess(Labels::getLabel('MSG_DELETED_SUCCESSFULLY', $this->siteLangId));
    }
}
 