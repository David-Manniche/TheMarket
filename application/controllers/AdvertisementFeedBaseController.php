<?php
class AdvertisementFeedBaseController extends SellerPluginBaseController
{

    public function __construct($action)
    {
        parent::__construct($action);
    }

    protected function redirectBack()
    {
        FatApp::redirectUser(CommonHelper::generateUrl(get_called_class()::KEY_NAME));
    }

    protected function updateMerchantAccountDetail($detail = [], $redirect = true)
    {
        if (!is_array($detail)) {
            FatUtility::dieJsonError(Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId));
        }
        $obj = new User(UserAuthentication::getLoggedUserId());
        foreach ($detail as $key => $value) {
            if (false === $obj->updateUserMeta($key, $value)) {
                Message::addErrorMessage($obj->getError());
                if (true === $redirect) {
                    $this->redirectBack();
                }
            }
        }
        Message::addMessage(Labels::getLabel("MSG_SUCCESSFULLY_UPDATED", $this->siteLangId));
        if (false === $redirect) {
            FatUtility::dieJsonSuccess(Message::getHtml());
        }
        $this->redirectBack();
    }

    protected function setErrorAndRedirect($message, $errRedirection = false)
    {
        if (false === $errRedirection) {
            LibHelper::dieJsonError($message);
        }

        Message::addErrorMessage($message);
        $this->redirectBack();
    }
}
