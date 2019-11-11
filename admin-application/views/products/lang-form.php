<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$productLangFrm->setFormTagAttribute('class', 'web_form layout--' . $formLayout);
$productLangFrm->setFormTagAttribute('onsubmit', 'setupProductLang(document.frmProductLang); return(false);');
    
$productLangFrm->developerTags['colClassPrefix'] = 'col-md-';
$productLangFrm->developerTags['fld_default_col'] = 12;
/* $product_short_description_fld = $productLangFrm->getField('product_short_description');
$product_short_description_fld->htmlAfterField = 'Enter Data Separated By New Line. Shown on Products Listing Page.'; */

$langFld = $productLangFrm->getField('lang_id');
$langFld->setfieldTagAttribute('onChange', "productLangForm(" . $product_id . ", this.value);");

?>
<section class="section">
    <div class="sectionhead">
        <h4>
            <?php echo Labels::getLabel('LBL_Product_Setup', $adminLangId); ?>
        </h4>
    </div>
    <div class="sectionbody space">
        <div class="row">
            <div class="col-sm-12">
                <div class="tabs_nav_container responsive flat">
                    <ul class="tabs_nav">
                        <li><a href="javascript:void(0);" onclick="productForm(<?php echo $product_id ?>, 0);">
                                <?php echo Labels::getLabel('LBL_General', $adminLangId); ?></a>
                        </li>
                        <li class="<?php echo (!$product_id) ? 'fat-inactive' : ''; ?>">
                            <a class="active" href="javascript:void(0);">
                                <?php echo Labels::getLabel('LBL_Language_Data', $adminLangId); ?>
                            </a>
                        </li>
                    </ul>
                    <div class="tabs_panel_wrap">
                        <?php
                        $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
                        $siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
                        if (!empty($translatorSubscriptionKey) && $product_lang_id != $siteDefaultLangId) { ?> 
                            <div class="row justify-content-end"> 
                                <div class="col-auto mb-4">
                                    <input class="btn btn-primary" 
                                        type="button" 
                                        value="<?php echo Labels::getLabel('LBL_AUTOFILL_LANGUAGE_DATA', $adminLangId); ?>" 
                                        onClick="productLangForm(<?php echo $product_id; ?>, <?php echo $product_lang_id; ?>, 1)">
                                </div>
                            </div>
                        <?php } ?> 
                        <div class="tabs_panel">
                            <?php
                                echo $productLangFrm->getFormTag();
                                echo $productLangFrm->getFormHtml(false);
                                echo '</form>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>