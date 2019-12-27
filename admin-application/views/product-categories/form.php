<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
$prodCatFrm->setFormTagAttribute('class', 'web_form');
$prodCatFrm->setFormTagAttribute('id', 'frmProdCategory');
$prodCatFrm->setFormTagAttribute('onsubmit', 'setupCategory(); return(false);');

$iconFld = $prodCatFrm->getField('cat_icon');
$iconFld->htmlAfterField = '<small class="text--small">'.sprintf(Labels::getLabel('LBL_This_will_be_displayed_in_%s_on_your_store', $adminLangId), '60*60').'</small><div id="icon-image-listing"></div>';

$bannerFld = $prodCatFrm->getField('cat_banner');
$bannerFld->htmlAfterField = '<div style="margin-top:15px;" class="preferredDimensions-js">'.sprintf(Labels::getLabel('LBL_Preferred_Dimensions_%s',$adminLangId),'2000 x 500').'</div><div id="banner-image-listing"></div>';

$btn = $prodCatFrm->getField('btn_submit');
$btn->setFieldTagAttribute('class', "themebtn btn-primary");

?>
<div class='page'>
    <div class='container container-fluid'>
        <div class="row">
            <div class="col-lg-12 col-md-12 space">
                <?php echo $prodCatFrm->getFormTag(); ?>
                    <div class="page__title">
                        <div class="row justify-content-between">
                            <div class="col--first col-lg-6">
                                <span class="page__icon"><i class="ion-android-star"></i></span>
                                <h5><?php echo Labels::getLabel('LBL_Category', $adminLangId); ?> </h5>
                                <?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
                            </div>
                            <div class="col-auto">
                                <?php echo $prodCatFrm->getFieldHtml('btn_submit'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="section">
                                <div class="sectionhead">
                                    <h4><?php echo Labels::getLabel('LBL_General', $adminLangId); ?></h4>
                                </div>
                                <div class="sectionbody space">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="field-set">
                                                <div class="caption-wraper">
                                                    <label class="field_label">
                                                    <?php
                                                        $fld = $prodCatFrm->getField('prodcat_name['.$siteDefaultLangId.']');
                                                        echo $fld->getCaption();
                                                    ?>
                                                    <span class="spn_must_field">*</span></label>
                                                </div>
                                                <div class="field-wraper">
                                                    <div class="field_cover">
                                                    <?php echo $prodCatFrm->getFieldHtml('prodcat_name['.$siteDefaultLangId.']'); ?>
                                                    <?php echo $prodCatFrm->getFieldHtml('parentCatId'); ?>
                                                    <?php echo $prodCatFrm->getFieldHtml('prodcat_id'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="field-set">
                                                <div class="caption-wraper">
                                                    <label class="field_label">
                                                    <?php  $fld = $prodCatFrm->getField('prodcat_parent');
                                                        echo $fld->getCaption();
                                                    ?></label>
                                                </div>
                                                <div class="field-wraper">
                                                    <div class="field_cover">
                                                        <?php echo $prodCatFrm->getFieldHtml('prodcat_parent'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="field-set d-flex align-items-center">
                                                <div class="caption-wraper w-auto pr-4">
                                                    <label class="field_label">
                                                    <?php  $fld = $prodCatFrm->getField('prodcat_active');
                                                        echo $fld->getCaption();
                                                    ?></label>
                                                </div>
                                                <div class="field-wraper w-auto">
                                                    <div class="field_cover">
                                                        <?php echo $prodCatFrm->getFieldHtml('prodcat_active'); ?>
                                                        <i class="input-helper"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php 
                                            $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
                                            if(!empty($translatorSubscriptionKey) && count($otherLangData) > 0){
                                        ?>
                                            <div class="col-md-6">
                                                <div class="field-set">
                                                    <?php echo $prodCatFrm->getFieldHtml('auto_update_other_langs_data'); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            
                            <?php foreach($otherLangData as $langId=>$data) { ?>
                                <div class="section" id="accordion-language_<?php echo $langId; ?>">
                                    <div class="sectionhead" data-toggle="collapse" data-target="#collapse_<?php echo $langId; ?>" aria-expanded="true" aria-controls="collapse_<?php echo $langId; ?>">
                                        <h4 class="accordion-head">
                                        <?php echo $data." "; echo Labels::getLabel('LBL_Language_Data', $adminLangId); ?></h4>
                                    </div>
                                    <div class="sectionbody space collapse"  id="collapse_<?php echo $langId; ?>" aria-labelledby="headingOne" data-parent="#accordion-language_<?php echo $langId; ?>"> 
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="field-set">
                                                    <div class="caption-wraper">
                                                        <label class="field_label">
                                                        <?php  $fld = $prodCatFrm->getField('prodcat_name['.$langId.']');
                                                            echo $fld->getCaption();
                                                        ?></label>
                                                    </div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover">
                                                        <?php echo $prodCatFrm->getFieldHtml('prodcat_name['.$langId.']'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="section">
                                <div class="sectionhead">
                                    <h4><?php echo Labels::getLabel('LBL_Media', $adminLangId); ?></h4>
                                </div>
                                <div class="sectionbody space">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h3 class="mb-4"><?php echo Labels::getLabel('LBL_Icon', $adminLangId); ?></h3>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="field-set">
                                                        <div class="caption-wraper"><label class="field_label">
                                                        <?php  $fld = $prodCatFrm->getField('icon_lang_id');
                                                            echo $fld->getCaption();
                                                        ?>
                                                        </label></div>
                                                        <div class="field-wraper">
                                                            <div class="field_cover">
                                                                 <?php echo $prodCatFrm->getFieldHtml('icon_lang_id'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="field-set">
                                                        <div class="caption-wraper"><label class="field_label">
                                                        <?php  $fld = $prodCatFrm->getField('cat_icon');
                                                            echo $fld->getCaption();
                                                        ?>
                                                        </label></div>
                                                        <div class="field-wraper">
                                                            <div class="field_cover">
                                                                <?php echo $prodCatFrm->getFieldHtml('cat_icon'); ?>
                                                                <?php echo $prodCatFrm->getFieldHtml('cat_icon_image_id'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h3 class="mb-4"><?php echo Labels::getLabel('LBL_Banner', $adminLangId); ?></h3>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="field-set">
                                                        <div class="caption-wraper"><label class="field_label">
                                                        <?php  $fld = $prodCatFrm->getField('banner_lang_id');
                                                            echo $fld->getCaption();
                                                        ?>
                                                        </label></div>
                                                        <div class="field-wraper">
                                                            <div class="field_cover">
                                                            <?php echo $prodCatFrm->getFieldHtml('banner_lang_id'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="field-set">
                                                        <div class="caption-wraper"><label class="field_label">
                                                        <?php  $fld = $prodCatFrm->getField('slide_screen');
                                                            echo $fld->getCaption();
                                                        ?>
                                                        </label></div>
                                                        <div class="field-wraper">
                                                            <div class="field_cover">
                                                            <?php echo $prodCatFrm->getFieldHtml('slide_screen'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="field-set">
                                                        <div class="caption-wraper"><label class="field_label">
                                                        <?php  $fld = $prodCatFrm->getField('cat_banner');
                                                            echo $fld->getCaption();
                                                        ?>
                                                        </label></div>
                                                        <div class="field-wraper">
                                                            <div class="field_cover">
                                                                <?php echo $prodCatFrm->getFieldHtml('cat_banner'); ?>
                                                                <?php echo $prodCatFrm->getFieldHtml('cat_banner_image_id'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <?php echo $prodCatFrm->getExternalJS(); ?>
            </div>
        </div>
    </div>
</div>