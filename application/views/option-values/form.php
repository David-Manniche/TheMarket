<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
if (!empty($translatorSubscriptionKey)) { ?> 
    <div class="row justify-content-end"> 
        <div class="col-auto mb-4">
            <input class="btn btn-primary" 
                type="button" 
                value="<?php echo Labels::getLabel('LBL_AUTOFILL_LANGUAGE_DATA', $langId); ?>" 
                onClick="autofillLangData($(this), $('form#frmOptionValues'))"
                data-action="<?php echo CommonHelper::generateUrl('OptionValues', 'getTranslatedData'); ?>">
        </div>
    </div>
<?php }
$optionValueFrm->setFormTagAttribute('class', 'form form--horizontal');
$optionValueFrm->setFormTagAttribute('onsubmit', 'setUpOptionValues(this); return(false);');
$optionValueFrm->developerTags['colClassPrefix'] = 'col-md-';
$optionValueFrm->developerTags['fld_default_col'] = 6;
?><div class="box__head">
<h4><?php echo isset($optionName) ? Labels::getLabel('LBL_CONFIGURE_OPTION_VALUES_FOR', $langId).' '.$optionName : Labels::getLabel('LBL_CONFIGURE_OPTION_VALUES', $langId); ?></h4>
</div>
<div class="box__body">
    <div class="form__subcontent">
        <?php
        echo $optionValueFrm->getFormHtml();
        ?>
    </div>
</div>
