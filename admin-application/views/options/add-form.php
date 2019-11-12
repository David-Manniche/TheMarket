<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<?php
$translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
if (!empty($translatorSubscriptionKey)) { ?> 
    <div class="row justify-content-end"> 
        <div class="col-auto mb-4">
            <input class="btn btn-primary" 
                type="button" 
                value="<?php echo Labels::getLabel('LBL_AUTOFILL_LANGUAGE_DATA', $adminLangId); ?>" 
                onClick="addOptionForm(<?php echo $option_id; ?>, 1)">
        </div>
    </div>
<?php } ?> 
                        
<?php
$frmOptions->setFormTagAttribute('class', 'web_form');
$frmOptions->setFormTagAttribute('onsubmit', 'submitOptionForm(this); return(false);');
$frmOptions->developerTags['colClassPrefix'] = 'col-md-';
$frmOptions->developerTags['fld_default_col'] = 6;
echo $frmOptions->getFormHtml();
