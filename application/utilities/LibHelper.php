<?php

class LibHelper extends FatUtility
{
    public static function dieJsonError($message)
    {
        if (true === MOBILE_APP_API_CALL) {
            $message = strip_tags($message);
        }
        FatUtility::dieJsonError($message);
    }

    public static function dieWithError($message)
    {
        FatUtility::dieWithError($message);
    }
    
    public static function exitWithError($message, $json = false, $redirect = false)
    {
        if (true === MOBILE_APP_API_CALL) {
            $message = strip_tags($message);
            FatUtility::dieJsonError($message);
        }
       
        if (true === $json) {
            FatUtility::dieJsonError($message);
        }
       
        if (FatUtility::isAjaxCall() || $redirect === false) {
            FatUtility::dieWithError($message);
        }
        
        if (true === $redirect) {
            Message::addErrorMessage($message);
        }
    }

    public static function getCommonReplacementVarsArr($langId)
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = CommonHelper::getLangId();
        }
        return array(
            '{SITE_NAME}' => FatApp::getConfig("CONF_WEBSITE_NAME_$langId"),
            '{SITE_URL}' => CommonHelper::generateFullUrl(),
        );
    }
}
