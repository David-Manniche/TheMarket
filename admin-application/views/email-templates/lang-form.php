<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$langFrm->setFormTagAttribute('class', 'web_form layout--' . $formLayout);
$langFrm->setFormTagAttribute('onsubmit', 'setupEtplLang(this); return(false);');
$langFrm->developerTags['colClassPrefix'] = 'col-md-';
$langFrm->developerTags['fld_default_col'] = 12;

$testEmailTemplate = $langFrm->getField('test_email');
$testEmailTemplate->setfieldTagAttribute('onClick', "sendTestEmail(document.getElementById('frm_fat_id_frmEtplLang'), '" . $etplCode . "');");

$langFld = $langFrm->getField('lang_id');
$langFld->setfieldTagAttribute('onChange', "editLangForm('" . $etplCode . "', this.value);");
?>
<section class="section">
    <div class="sectionhead">
        <h4><?php echo Labels::getLabel('LBL_Email_Template_Setup', $adminLangId); ?>
        </h4>
    </div>
    <div class="sectionbody space">
        <div class="tabs_nav_container responsive flat">
            <ul class="tabs_nav">
                <li
                    class="<?php echo (empty($etplCode)) ? 'fat-inactive' : ''; ?>">
                    <a class="active" href="javascript:void(0);">
                        <?php echo Labels::getLabel('LBL_Language_Data', $adminLangId); ?>
                    </a>
                </li>
            </ul>
            <div class="tabs_panel_wrap">
                <?php
                        $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
                        $siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
                        if (!empty($translatorSubscriptionKey) && $lang_id != $siteDefaultLangId) {
                            ?>
                <div class="row justify-content-end">
                    <div class="col-auto mb-4">
                        <input class="btn btn-primary" type="button"
                            value="<?php echo Labels::getLabel('LBL_AUTOFILL_LANGUAGE_DATA', $adminLangId); ?>"
                            onClick="editLangForm('<?php echo $etplCode; ?>', <?php echo $lang_id; ?>, 1)">
                    </div>
                </div>
                <?php
                        } ?>
                <div class="tabs_panel">
                    <?php echo $langFrm->getFormHtml(); ?>
                </div>
            </div>
        </div>
    </div>
</section>