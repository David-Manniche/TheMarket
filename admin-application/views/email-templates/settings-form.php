<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$settingFrm->setFormTagAttribute('class', 'web_form layout--'.$formLayout);
$settingFrm->setFormTagAttribute('onsubmit', 'setupSettings(this); return(false);');
$settingFrm->developerTags['colClassPrefix'] = 'col-md-';
$settingFrm->developerTags['fld_default_col'] = 12;

$langFld = $settingFrm->getField('lang_id');
$langFld->setfieldTagAttribute('onChange', "editSettingsForm(this.value);");
?>
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
