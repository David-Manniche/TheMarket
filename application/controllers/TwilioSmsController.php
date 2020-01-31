<?php
class TwilioSmsController extends SmsNotificationBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function callback($keyName)
    {
        $data = FatApp::getPostedData();
        
        if (empty($data)) {
            Message::addErrorMessage(Labels::getLabel('LBL_INVALID_REQUEST', CommonHelper::getLangId()));
            FatUtility::dieWithError(Message::getHtml());
        }

        if (empty($data['MessageSid'])) {
            Message::addErrorMessage(Labels::getLabel('MSG_INVALID_RESPONSE', CommonHelper::getLangId()));
            FatUtility::dieWithError(Message::getHtml());
        }
        
        $this->updateArchiveSms($data);
    }
}