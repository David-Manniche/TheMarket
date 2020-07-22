<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<div class="wrapper">
    <header id="header-checkout" class="header-checkout" role="header-checkout">
	<?php if (FatApp::getConfig('CONF_AUTO_RESTORE_ON', FatUtility::VAR_INT, 1) && CommonHelper::demoUrl()) { 
        $this->includeTemplate('restore-system/top-header.php');   
        $this->includeTemplate('restore-system/page-content.php'); 
    } ?>
    <section class="checkout">
    <header class="header-checkout" data-header="" role="header">
        <?php
        $fileData = AttachedFile::getAttachment(AttachedFile::FILETYPE_FRONT_LOGO, 0, 0, $siteLangId, false);
        $aspectRatioArr = AttachedFile::getRatioTypeArray($siteLangId);
        ?>
        <a class="logo-checkout-main" href="<?php echo UrlHelper::generateUrl(); ?>"><img <?php if ($fileData['afile_aspect_ratio'] > 0) { ?>
                            data-ratio= "<?php echo $aspectRatioArr[$fileData['afile_aspect_ratio']]; ?>"
                            <?php } ?> src="<?php echo UrlHelper::generateFullUrl('Image', 'siteLogo', array($siteLangId), CONF_WEBROOT_FRONT_URL); ?>"
                            alt="<?php echo FatApp::getConfig('CONF_WEBSITE_NAME_'.$siteLangId) ?>"
                            title="<?php echo FatApp::getConfig('CONF_WEBSITE_NAME_'.$siteLangId) ?>"></a>
        <?php /* $backUrl = UrlHelper::generateUrl('Home');
        if ($controllerName == 'subscriptioncheckout') {
            $backUrl = UrlHelper::generateUrl('Seller', 'Packages');
        } elseif ($controllerName == 'walletpay') {
            $backUrl = UrlHelper::generateUrl('Account', 'Credits');
        }  */?>
        <?php if ($controllerName == 'checkout' || $controllerName == 'subscriptioncheckout') {   ?>
        <div class="checkout-progress">
            <div class="progress-track"></div>
            <?php if ($controllerName == 'checkout') {  ?>
                <div id="step1" class="progress-step checkoutNav-js billing-js"><?php echo Labels::getLabel('LBL_Billing', $siteLangId); ?></div>
                <div id="step2" class="progress-step checkoutNav-js shipping-js"><?php echo Labels::getLabel('LBL_Shipping', $siteLangId); ?></div>
                <div id="step3" class="progress-step checkoutNav-js payment-js"><?php echo Labels::getLabel('LBL_Payment', $siteLangId); ?></div>
            <?php } else { ?>
                <div id="step1" class="progress-step checkoutNav-js billing-js"><?php echo Labels::getLabel('LBL_Billing', $siteLangId); ?></div>
                <div id="step2" class="progress-step checkoutNav-js payment-js"><?php echo Labels::getLabel('LBL_Payment', $siteLangId); ?></div>            
            <?php } ?>            
        </div>
        <?php }?>
    </header>
    <div class="after-checkout-header"></div>


