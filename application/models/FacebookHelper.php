<?php
class FacebookHelper
{   
    public static function getUserFacebookInfo($accessToken, $langId)
    {     
        $message = Labels::getLabel('MSG_Invalid_Token', $langId);
            LibHelper::dieJsonError($message);
            
        include_once CONF_INSTALLATION_PATH . 'library/facebook/facebook.php';
        $facebook = new Facebook(
            array(
            'appId' => FatApp::getConfig("CONF_FACEBOOK_APP_ID", FatUtility::VAR_STRING, ''),
            'secret' => FatApp::getConfig("CONF_FACEBOOK_APP_SECRET", FatUtility::VAR_STRING, ''),
            )
        );
        $facebook->setAccessToken($accessToken);
        $user = $facebook->getUser();
        if (!$user) {
            $message = Labels::getLabel('MSG_Invalid_Token', $langId);
            LibHelper::dieJsonError($message);
        }

        try {
            $userProfile = $facebook->api('/me?fields=id,name,email');
            return $userProfile; 
        } catch (FacebookApiException $e) {
            FatUtility::dieJsonError($e->getMessage());
        }        
    }
    
    
}