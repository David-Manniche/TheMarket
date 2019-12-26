<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$prodCatFrm->setFormTagAttribute('class', 'web_form');
$prodCatFrm->setFormTagAttribute('id', 'frmProdCategory');
$prodCatFrm->setFormTagAttribute('onsubmit', 'setupCategory(); return(false);');
        
$identifierFld = $prodCatFrm->getField('prodcat_identifier');
$identifierFld->setFieldTagAttribute('onkeyup', "Slugify(this.value,'urlrewrite_custom','parentCatId');getSlugUrl($(\"#urlrewrite_custom\"),$(\"#urlrewrite_custom\").val(),'" . $parentUrl . "','pre',true)");

$parentCatFld = $prodCatFrm->getField('parentCatId');
$parentCatFld->setFieldTagAttribute('id', "parentCatId");

$urlFld = $prodCatFrm->getField('urlrewrite_custom');
$urlFld->setFieldTagAttribute('id', "urlrewrite_custom");
$urlFld->htmlAfterField = "<small class='text--small'>" . CommonHelper::generateFullUrl('Category', 'View', array($prodCatId), CONF_WEBROOT_FRONT_URL) . '</small>';
$urlFld->setFieldTagAttribute('onkeyup', "getSlugUrl(this,this.value)");

$descFld = $prodCatFrm->getField('prodcat_description');
$descFld->htmlAfterField = '<small>'.Labels::getLabel('LBL_First_100_characters_will_be_shown_in_home_page_collections.', $adminLangId).'</small>';

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
                                                    <?php  $fld = $prodCatFrm->getField('prodcat_identifier');
                                                        echo $fld->getCaption();
                                                    ?>
                                                    <span class="spn_must_field">*</span></label>
                                                </div>
                                                <div class="field-wraper">
                                                    <div class="field_cover">
                                                    <?php echo $prodCatFrm->getFieldHtml('prodcat_identifier'); ?>
                                                    <?php echo $prodCatFrm->getFieldHtml('parentCatId'); ?>
                                                    <?php echo $prodCatFrm->getFieldHtml('prodCatId'); ?>
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
                                                    ?>
                                                    <span class="spn_must_field">*</span></label>
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
                                            <div class="field-set">
                                                <div class="caption-wraper">
                                                    <label class="field_label">
                                                    <?php  $fld = $prodCatFrm->getField('prodcat_active');
                                                        echo $fld->getCaption();
                                                    ?>
                                                    <span class="spn_must_field">*</span></label>
                                                </div>
                                                <div class="field-wraper">
                                                    <div class="field_cover">
                                                    <?php echo $prodCatFrm->getFieldHtml('prodcat_active'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="field-set">
                                                <div class="caption-wraper">
                                                    <label class="field_label">
                                                    <?php  $fld = $prodCatFrm->getField('urlrewrite_custom');
                                                        echo $fld->getCaption();
                                                    ?>
                                                    <span class="spn_must_field">*</span></label>
                                                </div>
                                                <div class="field-wraper">
                                                    <?php echo $prodCatFrm->getFieldHtml('urlrewrite_custom'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="section">
                                <div class="sectionhead">
                                    <h4><?php echo Labels::getLabel('LBL_Language_Data', $adminLangId); ?></h4>
                                </div>
                                <div class="sectionbody space">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="field-set">
                                                <div class="caption-wraper">
                                                    <label class="field_label">
                                                    <?php  $fld = $prodCatFrm->getField('lang_id');
                                                        echo $fld->getCaption();
                                                    ?>
                                                    <span class="spn_must_field">*</span></label>
                                                </div>
                                                <div class="field-wraper">
                                                    <div class="field_cover">
                                                    <?php echo $prodCatFrm->getFieldHtml('lang_id'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="field-set">
                                                <div class="caption-wraper">
                                                    <label class="field_label">
                                                    <?php  $fld = $prodCatFrm->getField('prodcat_name');
                                                        echo $fld->getCaption();
                                                    ?>
                                                    <span class="spn_must_field">*</span></label>
                                                </div>
                                                <div class="field-wraper">
                                                    <div class="field_cover">
                                                    <?php echo $prodCatFrm->getFieldHtml('prodcat_name'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="field-set">
                                                <div class="caption-wraper">
                                                    <label class="field_label"></label>
                                                </div>
                                                <div class="field-wraper">
                                                    <div class="field_cover">
                                                    <?php echo $prodCatFrm->getFieldHtml('auto_update_other_langs_data'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="field-set">
                                                <div class="caption-wraper">
                                                    <label class="field_label">
                                                    <?php  $fld = $prodCatFrm->getField('prodcat_description');
                                                        echo $fld->getCaption();
                                                    ?>
                                                    <span class="spn_must_field">*</span></label>
                                                </div>
                                                <div class="field-wraper">
                                                    <div class="field_cover">
                                                        <?php echo $prodCatFrm->getFieldHtml('prodcat_description'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


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