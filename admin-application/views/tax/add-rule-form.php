<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'web_form');
/*$frm->setFormTagAttribute('onsubmit', 'setupTaxRule(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;*/
$combTaxCount = 0;
?>
<div class="tax-rule-form--js tax-rule-form-<?php echo $index; ?>" data-index="<?php echo $index; ?>">
    <div class="p-4">
        <div class="row mb-4">
            <div class="col-sm-12">
                <?php if ($index > 1) { ?>
                <a href="javascript:void(0);" class="themebtn btn-primary remove-tax-rule--js"><?php echo Labels::getLabel("LBL_Delete_Tax_Rule", $adminLangId);?></a>
                <?php } ?>
            </div>
        </div>
        <div class="row">
            <?php $taxruleNameFld = $frm->getField("taxrule_name[".$adminLangId."][]");
              $taxruleRateFld = $frm->getField("taxrule_rate[]");
              echo $frm->getFieldHtml('taxrule_id[]');
            ?>
            <div class="col-lg-6">
                <div class="border rounded p-4 h-100">
                    <div class="form-group">
                        <label for="example-text-input" class="">
                            <?php echo $taxruleNameFld->getCaption();?>
                        </label>
                        <?php echo $frm->getFieldHtml("taxrule_name[".$adminLangId."][]"); ?></div>
                    <div class="form-group">
                        <label for="example-text-input">
                            <?php echo $taxruleRateFld->getCaption();?>
                        </label>
                        <?php echo $frm->getFieldHtml("taxrule_rate[]"); ?>
                    </div>
                    <?php
                    $countryFld = $frm->getField("taxruleloc_country_id[]");
                    $countryFld->setFieldTagAttribute("id", "ua_country_id");
                    $countryFld->setFieldTagAttribute("onChange", "getCountryStatesTaxInTaxForm(this, this.value,0)");
                    $typeFld=$frm->getField("taxruleloc_type[]");
                    $stateFld=$frm->getField("taxruleloc_state_id[]");
                    $stateFld->addFieldTagAttribute("multiple", "true");
                    $stateFld->addFieldTagAttribute("id", "ua_state_id");
                    $stateFld->addFieldTagAttribute("class", "selectpicker");
                    $stateFld->addFieldTagAttribute("data-style", "bg-white rounded-pill px-4 py-2 shadow-sm");
                    $comFld = $frm->getField("taxrule_is_combined[]");
                    $comFld->developerTags['cbLabelAttributes'] = array('class' => 'checkbox');
                    $comFld->developerTags['cbHtmlAfterCheckbox'] = '<i class="input-helper"></i>';
                    ?>
                    <div class="form-group">
                        <label for="example-text-input" class="">
                            <?php echo $countryFld->getCaption();?></label>
                        <?php echo $frm->getFieldHtml("taxruleloc_country_id[]");?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="border rounded p-4 h-100">
                    <div class="form-group">
                        <label for="example-text-input" class="">
                            <?php echo $typeFld->getCaption();?></label>
                        <?php echo $frm->getFieldHtml("taxruleloc_type[]");?>
                    </div>
                    <div class="form-group">
                        <label for="example-text-input" class="">
                            <?php echo $stateFld->getCaption();?>
                        </label>
                        <?php echo $frm->getFieldHtml("taxruleloc_state_id[]");?>
                    </div>
                    <div class="form-group">
                        <?php echo $frm->getFieldHtml("taxrule_is_combined[]");?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row taxrule-lang-form--js">
            <div class="col-md-6">
                <?php
                if (!empty($otherLanguages)) {
                    foreach ($otherLanguages as $langId => $data) {
                        $layout = Language::getLayoutDirection($langId); ?>
                     <div class="accordians_container accordians_container-categories mt-4" defaultLang= "<?php echo $siteDefaultLangId; ?>" language="<?php echo $langId; ?>" id="accordion-language_<?php echo $langId; ?>" onClick="translateData(this)">
                         <div class="accordian_panel">
                             <span class="accordian_title accordianhead accordian_title mb-0" id="collapse_<?php echo $langId; ?>">
                             <?php echo $data." "; echo Labels::getLabel('LBL_Language_Data', $adminLangId); ?>
                             </span>
                             <div class="accordian_body accordiancontent" style="display: none;">
                                 <div class="row">
                                    <div class="col-md-12">
                                        <div class="field-set">
                                            <div class="caption-wraper">
                                                <label class="field_label">
                                                <?php  $fld = $frm->getField('taxrule_name['.$langId.'][]');
                                                    echo $fld->getCaption(); ?>
                                                </label>
                                            </div>
                                            <div class="field-wraper">
                                                <div class="field_cover">
                                                <?php echo $frm->getFieldHtml('taxrule_name['.$langId.'][]'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>
                         </div>
                     </div>
                    <?php }
                } ?>
            </div>
        </div>
        <div class="row combined-tax-details--js" style="display:none;">
            <div class="col-md-6">
                <table class="table table-bordered table-hover table-edited my-4">
                    <thead>
                        <tr>
                            <th width="60%">
                                <?php echo Labels::getLabel("LBL_Name", $adminLangId)?>
                            </th>
                            <th width="30%">
                                <?php echo Labels::getLabel("LBL_Tax_Rate", $adminLangId)?>
                            </th>
                            <th width="10%">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="rule-detail-row--js rule-detail-row<?php echo $combTaxCount; ?>">
                            <td scope="row">
                                <?php $detIdsFld=$frm->getField("taxruledet_id[]");
                                $detNameFld=$frm->getField("taxruledet_name[".$adminLangId."][]");
                                $detRateFld=$frm->getField("taxruledet_rate[]");
                                echo $frm->getFieldHtml("taxruledet_id[]");
                                echo $frm->getFieldHtml("taxruledet_name[".$adminLangId."][]");?></td>
                            <td>
                                <?php echo $frm->getFieldHtml("taxruledet_rate[]");?></td>
                            <td>
                                <button type="button" class="btn btn--secondary ripplelink remove-combined-form--js" title="<?php echo Labels::getLabel('LBL_Remove', $adminLangId); ?>"><i class="ion-minus-round"></i></button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                            </td>
                            <td>
                                <button type="button" class="btn btn--secondary ripplelink add-combined-form--js" title="<?php echo Labels::getLabel('LBL_Add', $adminLangId); ?>"><i class="ion-plus-round"></i></button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div id="tax-lang-form--js" class="col-md-6">
                <?php
                if (!empty($otherLanguages)) {
                    foreach ($otherLanguages as $langId => $data) {
                        $layout = Language::getLayoutDirection($langId); ?>
                        <div class="accordians_container accordians_container-categories mt-4" defaultLang= "<?php echo $siteDefaultLangId; ?>" language="<?php echo $langId; ?>" id="accordion-language_<?php echo $langId; ?>" onClick="translateData(this)">
                            <div class="accordian_panel">
                                <span class="accordian_title accordianhead accordian_title mb-0" id="collapse_<?php echo $langId; ?>">
                                <?php echo $data." "; echo Labels::getLabel('LBL_Language_Data', $adminLangId); ?>
                                </span>
                                <div class="accordian_body accordiancontent" style="display: none;">
                                    <div class="row">
                                       <div class="col-md-12 combined-tax-lang-details--js<?php echo $langId; ?>">
                                           <div class="field-set rule-detail-row<?php echo $combTaxCount; ?>">
                                               <div class="caption-wraper">
                                                   <label class="field_label">
                                                   <?php  $fld = $frm->getField('taxruledet_name['.$langId.'][]');
                                                       echo $fld->getCaption(); ?>
                                                   </label>
                                               </div>
                                               <div class="field-wraper">
                                                   <div class="field_cover">
                                                   <?php echo $frm->getFieldHtml('taxruledet_name['.$langId.'][]'); ?>
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                } ?>
            </div>
        </div>
    </div>
</div>
