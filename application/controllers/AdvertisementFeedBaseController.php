<?php
class AdvertisementFeedBaseController extends PluginBaseController
{

    public function __construct($action)
    {
        parent::__construct($action);
    }

    protected function redirectBack()
    {
        FatApp::redirectUser(CommonHelper::generateUrl('Advertisement'));
    }

    protected function updateMerchantAccountDetail($detail = [])
    {
        if (!is_array($detail)) {
            FatUtility::dieJsonError(Labels::getLabel('MSG_INVALID_REQUEST', $this->siteLangId));
        }
        $obj = new User(UserAuthentication::getLoggedUserId());
        foreach ($detail as $key => $value) {
            if (false === $obj->updateUserMeta($key, $value)) {
                Message::addErrorMessage($obj->getError());
                $this->redirectBack();
            }
        }
        Message::addMessage(Labels::getLabel("MSG_SUCCESSFULLY_UPDATED", $this->siteLangId));
        $this->redirectBack();
    }
}
