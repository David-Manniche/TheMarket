<?php require_once('sellerProductSeoTop.php');?>
<div class="form__subcontent">
    <?php
        $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
        $siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
        if (!empty($translatorSubscriptionKey) && $selprod_lang_id != $siteDefaultLangId) { ?> 
            <div class="row justify-content-end"> 
                <div class="col-auto mb-4">
                    <input class="btn btn-primary" 
                        type="button" 
                        value="<?php echo Labels::getLabel('LBL_AUTOFILL_LANGUAGE_DATA', $siteLangId); ?>" 
                        onClick="editProductMetaTagLangForm(<?php echo $metaId; ?>, <?php echo $selprod_lang_id; ?>, 1)">
                </div>
            </div>
        <?php } ?>
    <?php                        
        $productSeoLangForm->setFormTagAttribute('class', 'form form--horizontal layout--'.$formLayout);
        $productSeoLangForm->setFormTagAttribute('onsubmit', 'setupProductLangMetaTag(this); return(false);');
        $productSeoLangForm->developerTags['colClassPrefix'] = 'col-lg-6 col-md-';
        $productSeoLangForm->developerTags['fld_default_col'] = 6;
        $langFld = $productSeoLangForm->getField('lang_id');
        $langFld->setfieldTagAttribute('onChange', "editProductMetaTagLangForm(" . $metaId . ", this.value);");
        echo $productSeoLangForm->getFormHtml(); ?>
</div>