<?php

class AdvertisementFeedBaseController extends SellerPluginBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);

        $class = get_called_class();
        if (!defined($class . '::KEY_NAME')) {
            Message::addErrorMessage(Labels::getLabel('MSG_INVALID_PLUGIN', $this->siteLangId));
            FatApp::redirectUser(UrlHelper::generateUrl('Seller'));
        }
        $this->keyName = $class::KEY_NAME;
        if (false === Plugin::isActive($this->keyName)) {
            Message::addErrorMessage(Labels::getLabel('MSG_NO_ADVERTISEMENT_PLUGIN_ACTIVE', $this->siteLangId));
            FatApp::redirectUser(UrlHelper::generateUrl('Seller'));
        }
    }

    protected function redirectBack()
    {
        FatApp::redirectUser(UrlHelper::generateUrl($this->keyName));
    }

    protected function updateMerchantInfo($detail = [], $redirect = true)
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

    protected function getPluginData($attr = '')
    {
        $attr = empty($attr) ? Plugin::ATTRS : $attr;
        return Plugin::getAttributesByCode($this->keyName, $attr, $this->siteLangId);
    }

    protected function getUserMeta($key = '')
    {
        return User::getUserMeta(UserAuthentication::getLoggedUserId(), $key);
    }
}
