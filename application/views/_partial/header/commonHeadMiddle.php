<link rel="shortcut icon"
        href="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'favicon', array($siteLangId)), CONF_IMG_CACHE_TIME, '.png'); ?>">
<link rel="apple-touch-icon"
    href="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'appleTouchIcon', array($siteLangId)), CONF_IMG_CACHE_TIME, '.png'); ?>">
<link rel="apple-touch-icon" sizes="57x57"
    href="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'appleTouchIcon', array($siteLangId, '57-57')), CONF_IMG_CACHE_TIME, '.png'); ?>">
<link rel="apple-touch-icon" sizes="60x60"
    href="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'appleTouchIcon', array($siteLangId, '60-60')), CONF_IMG_CACHE_TIME, '.png'); ?>">
<link rel="apple-touch-icon" sizes="72x72"
    href="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'appleTouchIcon', array($siteLangId, '72-72')), CONF_IMG_CACHE_TIME, '.png'); ?>">
<link rel="apple-touch-icon" sizes="76x76"
    href="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'appleTouchIcon', array($siteLangId, '76-76')), CONF_IMG_CACHE_TIME, '.png'); ?>">
<link rel="apple-touch-icon" sizes="114x114"
    href="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'appleTouchIcon', array($siteLangId, '114-114')), CONF_IMG_CACHE_TIME, '.png'); ?>">
<link rel="apple-touch-icon" sizes="120x120"
    href="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'appleTouchIcon', array($siteLangId, '120-120')), CONF_IMG_CACHE_TIME, '.png'); ?>">
<link rel="apple-touch-icon" sizes="144x144"
    href="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'appleTouchIcon', array($siteLangId, '144-144')), CONF_IMG_CACHE_TIME, '.png'); ?>">
<link rel="apple-touch-icon" sizes="152x152"
    href="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'appleTouchIcon', array($siteLangId, '152-152')), CONF_IMG_CACHE_TIME, '.png'); ?>">
<link rel="apple-touch-icon" sizes="180x180"
    href="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'appleTouchIcon', array($siteLangId, '180-180')), CONF_IMG_CACHE_TIME, '.png'); ?>">        
<link rel="icon" type="image/png" sizes="192x192" href="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'appleTouchIcon', array($siteLangId, '192-192')), CONF_IMG_CACHE_TIME, '.png'); ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'appleTouchIcon', array($siteLangId, '32-32')), CONF_IMG_CACHE_TIME, '.png'); ?>">
<link rel="icon" type="image/png" sizes="96x96" href="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'appleTouchIcon', array($siteLangId, '96-96')), CONF_IMG_CACHE_TIME, '.png'); ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo FatCache::getCachedUrl(CommonHelper::generateUrl('Image', 'appleTouchIcon', array($siteLangId, '16-16')), CONF_IMG_CACHE_TIME, '.png'); ?>">
<link rel="manifest" href="<?php echo CommonHelper::generateUrl('Home', 'pwaManifest'); ?>">
<?php
if ($canonicalUrl == '') {
    $canonicalUrl = CommonHelper::generateFullUrl($controllerName, FatApp::getAction(), !empty(FatApp::getParameters())?FatApp::getParameters():array());
}
?>
<link rel="canonical" href="<?php echo $canonicalUrl;?>" />        
<style>
    :root {
        --first-color: #<?php echo $themeDetail['tcolor_first_color']; ?>;
        --second-color: #<?php echo $themeDetail['tcolor_second_color']; ?>;
        --third-color: #<?php echo $themeDetail['tcolor_third_color']; ?>;
        --txt-color: #<?php echo $themeDetail['tcolor_text_color']; ?>;
        --txt-color-light: #<?php echo $themeDetail['tcolor_text_light_color']; ?>;
        --border-color: #<?php echo $themeDetail['tcolor_border_first_color']; ?>;
        --border-color-second: #<?php echo $themeDetail['tcolor_border_second_color'];?>;
        --second-btn-color: #<?php echo $themeDetail['tcolor_second_btn_color'];  ?>;
        --header-txt-color: #<?php echo $themeDetail['tcolor_header_text_color']; ?>;
        --body-color: #525252;
        --gray-light: #f8f8f8;
    }
</style>
<script type="text/javascript">
<?php
echo $str = 'var langLbl = ' . FatUtility::convertToJson($jsVariables, JSON_UNESCAPED_UNICODE) . ';
    var CONF_AUTO_CLOSE_SYSTEM_MESSAGES = ' . FatApp::getConfig("CONF_AUTO_CLOSE_SYSTEM_MESSAGES", FatUtility::VAR_INT, 0) . ';
    var CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES = ' . FatApp::getConfig("CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES", FatUtility::VAR_INT, 3) . ';
    var extendEditorJs = ' . $extendEditorJs . ';
    var themeActive = ' . $themeActive . ';
    var currencySymbolLeft = "' . $currencySymbolLeft . '";
    var currencySymbolRight = "' . $currencySymbolRight . '";
    if( CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES <= 0  ){
        CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES = 3;
    }';
if (FatApp::getConfig("CONF_ENABLE_ENGAGESPOT_PUSH_NOTIFICATION", FatUtility::VAR_STRING, '')) {
    echo FatApp::getConfig("CONF_ENGAGESPOT_PUSH_NOTIFICATION_CODE", FatUtility::VAR_STRING, '');
    if (UserAuthentication::getLoggedUserId(true) > 0) { ?>
    Engagespot.init()
    Engagespot.identifyUser('YT_<?php echo UserAuthentication::getLoggedUserId(); ?>');
    <?php }
} 

if (Message::getMessageCount() || Message::getErrorCount() || Message::getDialogCount() || Message::getInfoCount()) { ?>
    $("document").ready(function() {
        if (CONF_AUTO_CLOSE_SYSTEM_MESSAGES == 1) {
            var time = CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES * 1000;
            setTimeout(function() {
                $.systemMessage.close();
            }, time);
        }
    });
<?php } ?>
</script>
<?php
if (FatApp::getConfig("CONF_GOOGLE_TAG_MANAGER_HEAD_SCRIPT", FatUtility::VAR_STRING, '')) {
    echo FatApp::getConfig("CONF_GOOGLE_TAG_MANAGER_HEAD_SCRIPT", FatUtility::VAR_STRING, '');
}
if (isset($layoutTemplate) && $layoutTemplate != '') { ?>
<link rel="stylesheet"
    href="<?php echo CommonHelper::generateUrl('ThemeColor', $layoutTemplate, array($layoutRecordId));?>">
<?php }