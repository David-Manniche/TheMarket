<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$settingFrm->setFormTagAttribute('class', 'web_form layout--'.$formLayout);
$settingFrm->setFormTagAttribute('onsubmit', 'setupSettings(this); return(false);');
$settingFrm->developerTags['colClassPrefix'] = 'col-md-';
$settingFrm->developerTags['fld_default_col'] = 6;

$colorFld = $settingFrm->getField('CONF_EMAIL_TEMPLATE_COLOR_CODE'.$adminLangId);
$langFld = $settingFrm->getField('lang_id');
$langFld->setfieldTagAttribute('onChange', "editSettingsForm(this.value);");

$edFld = $settingFrm->getField('CONF_EMAIL_TEMPLATE_FOOTER_HTML'.$adminLangId);
$edFld->htmlBeforeField = '<br/><a class="themebtn btn-primary" onClick="resetToDefaultContent();" href="javascript:void(0)">Reset Editor Content to default</a>';
$edFld->developerTags['col'] = 12;

$fld = $settingFrm->getField('email_logo');
$fld->addFieldTagAttribute('class', 'btn btn--primary btn--sm');

$htmlAfterField = '';
if (!empty($logoImage)) {
    $htmlAfterField .= '<ul class="image-listing grids--onethird">';
    $htmlAfterField .= '<li><div class="uploaded--image"><img src="' . CommonHelper::generateFullUrl('image', 'emailLogo', array($logoImage['afile_lang_id']), CONF_WEBROOT_FRONT_URL) . '"> <a  class="remove--img" href="javascript:void(0);" onclick="removeEmailLogo('.$logoImage['afile_lang_id'].')" ><i class="ion-close-round"></i></a></div>';
    $htmlAfterField .= '</li></ul>';
} else {
    $htmlAfterField .= '<div class="temp-hide"><ul class="image-listing grids--onethird"><li><div class="uploaded--image"></div></li></ul></div>';
}
$fld->htmlAfterField = $htmlAfterField;
?>
<div id="editor_default_content" style="display:none;">
    <?php $this->includeTemplate('_partial/emails/email-footer.php'); ?>
</div>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Labels::getLabel('LBL_Email_Template_Setup', $adminLangId); ?></h4>
    </div>
    <div class="sectionbody space">
        <div class="tabs_nav_container responsive flat">
            <ul class="tabs_nav">
                <li>
                    <a class="active" href="javascript:void(0);">
                        <?php echo Labels::getLabel('LBL_Language_Data', $adminLangId); ?>
                    </a>
                </li>
            </ul>
            <div class="tabs_panel_wrap">
                <?php
                        $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
                        $siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
                        if (!empty($translatorSubscriptionKey) && $lang_id != $siteDefaultLangId) { ?>
                <div class="row justify-content-end">
                    <div class="col-auto mb-4">
                        <input class="btn btn-primary" type="button" value="<?php echo Labels::getLabel('LBL_AUTOFILL_LANGUAGE_DATA', $adminLangId); ?>" onClick="editLangForm(<?php echo $lang_id; ?>, 1)">
                    </div>
                </div>
                <?php } ?>
                <div class="tabs_panel">
                    <?php echo $settingFrm->getFormHtml(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
