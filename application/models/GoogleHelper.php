<?php
class GoogleHelper
{   
    public static function getUserGoogleInfo($langId)
    {     
        include_once CONF_INSTALLATION_PATH . 'library/GoogleAPI/vendor/autoload.php'; 
        $client = new Google_Client();
        $client->setApplicationName(FatApp::getConfig('CONF_WEBSITE_NAME_' . $langId));
        $client->setScopes(['email']); 
        $client->setClientId(FatApp::getConfig("CONF_GOOGLEPLUS_CLIENT_ID")); 
        $client->setClientSecret(FatApp::getConfig("CONF_GOOGLEPLUS_CLIENT_SECRET"));
        $currentPageUri = CommonHelper::generateFullUrl('GuestUser', 'loginGoogle', array(), '', false);        
        $client->setRedirectUri($currentPageUri);
        $client->setDeveloperKey(FatApp::getConfig("CONF_GOOGLEPLUS_DEVELOPER_KEY"));
        $oauth2 = new Google_Service_Oauth2($client); 
        
        if (false ===  MOBILE_APP_API_CALL) { 
            $get = FatApp::getQueryStringData(); 
            $accessToken = false;
            if (isset($get['code'])) {
                $client->authenticate($get['code']); 
                $accessToken = $client->getAccessToken();
            }
            if (false === $accessToken) {
                $authUrl = $client->createAuthUrl();
                FatApp::redirectUser($authUrl);
            }
        } else {
            $accessToken = FatApp::getPostedData('accessToken');
        }

        $client->setAccessToken($accessToken);
        $user = $oauth2->userinfo->get();        
        return $user;
    }
    
    
}