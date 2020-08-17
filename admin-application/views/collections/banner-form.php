<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'web_form form_horizontal');
$frm->setFormTagAttribute('onsubmit', 'setupBanners(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;

$extUrlField = $frm->getField('banner_url');
$extUrlField->addFieldTagAttribute('placeholder', 'http://');

$fld = $frm->getField('auto_update_other_langs_data');
$fld->developerTags['cbLabelAttributes'] = array('class' => 'checkbox');
$fld->developerTags['cbHtmlAfterCheckbox'] = '<i class="input-helper"></i>';

$siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
?>
<section class="section">
    <div class="sectionbody space">
        <div class="row">
            <div class="col-sm-12">
                <div class="tabs_nav_container responsive flat">
                    <ul class="tabs_nav">
                        <li><a href="javascript:void(0)"
                                onclick="collectionForm(<?php echo $collection_type ?>, <?php echo $collection_layout_type ?>, <?php echo $collection_id ?>, 0);">
                                <?php echo Labels::getLabel('LBL_General', $adminLangId);?></a>
                        </li>
						<li><a class="active"
                                href="javascript:void(0)"
                                <?php if($collection_id > 0){?> onclick="bannerForm(<?php echo $collection_id ?>);" <?php } ?>>
                                <?php echo Labels::getLabel('LBL_Banners', $adminLangId);?></a>
                        </li>
                    </ul>
                    <div class="tabs_panel_wrap">
                        <div class="tabs_panel">
                            <?php echo $frm->getFormTag(); ?>
                            <div class="row">
								<div class="col-md-6">
                                    <div class="field-set">
                                        <div class="caption-wraper">
                                            <label class="field_label">
											<?php
                                                $fld = $frm->getField('banner_title['.$siteDefaultLangId.']');
                                                echo $fld->getCaption();
                                            ?>
                                            <span class="spn_must_field">*</span></label>
                                        </div>
                                        <div class="field-wraper">
                                            <div class="field_cover">
                                            <?php echo $frm->getFieldHtml('banner_title['.$siteDefaultLangId.']'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="field-set">
                                        <div class="caption-wraper">
                                            <label class="field_label">
                                            <?php
                                                $fld = $frm->getField('banner_url');
                                                echo $fld->getCaption();
                                            ?>
                                            <span class="spn_must_field">*</span></label>
                                        </div>
                                        <div class="field-wraper">
                                            <div class="field_cover">
                                            <?php echo $frm->getFieldHtml('banner_url'); ?>
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
                                            <?php
                                                $fld = $frm->getField('banner_target');
                                                echo $fld->getCaption();
                                            ?>
                                            <span class="spn_must_field">*</span></label>
                                        </div>
                                        <div class="field-wraper">
                                            <div class="field_cover">
                                            <?php echo $frm->getFieldHtml('banner_target'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
                                    if(!empty($translatorSubscriptionKey) && count($otherLangData) > 0){
                                ?>
                                <div class="col-md-6">
                                    <div class="field-set d-flex align-items-center">
                                        <div class="field-wraper w-auto">
                                            <div class="field_cover">
                                                <?php echo $frm->getFieldHtml('auto_update_other_langs_data'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <?php if(!empty($otherLangData)){
                            foreach($otherLangData as $langId=>$data) { 
                            ?>
                            <div class="accordians_container accordians_container-categories" defaultLang= "<?php echo $siteDefaultLangId; ?>" language="<?php echo $langId; ?>" id="accordion-language_<?php echo $langId; ?>" onClick="translateBannerData(this)">
                                 <div class="accordian_panel">
                                     <span class="accordian_title accordianhead accordian_title" id="collapse_<?php echo $langId; ?>">
                                     <?php echo $data." "; echo Labels::getLabel('LBL_Language_Data', $adminLangId); ?>
                                     </span>
                                     <div class="accordian_body accordiancontent" style="display: none;">
                                         <div class="row">
                                            <div class="col-md-12">
                                                <div class="field-set">
                                                    <div class="caption-wraper">
                                                        <label class="field_label">
                                                        <?php  $fld = $frm->getField('banner_title['.$langId.']');
                                                            echo $fld->getCaption(); ?>
                                                        </label>
                                                    </div>
                                                    <div class="field-wraper">
                                                        <div class="field_cover">
                                                        <?php echo $frm->getFieldHtml('banner_title['.$langId.']'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                     </div>
                                 </div>
                             </div>
                            <?php } 
                            }
                            ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field-set d-flex align-items-center">
                                        <div class="field-wraper w-auto">
                                            <div class="field_cover">
                                                <?php echo $frm->getFieldHtml('btn_submit'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo $frm->getFieldHtml('collection_id'); ?>
                            <?php echo $frm->getFieldHtml('banner_id'); ?>
                            </form>
                            <?php echo $frm->getExternalJS(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>