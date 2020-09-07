<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'web_form form_horizontal');
$frm->setFormTagAttribute('onsubmit', 'setupTaxStructure(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;

$fld = $frm->getField('auto_update_other_langs_data');
$fld->developerTags['cbLabelAttributes'] = array('class' => 'checkbox');
$fld->developerTags['cbHtmlAfterCheckbox'] = '<i class="input-helper"></i>';

$fld = $frm->getField('taxstr_is_combined');
$fld->setOptionListTagAttribute('class', 'list-inline-checkboxes'); 
$fld->developerTags['cbLabelAttributes'] = array('class' => 'checkbox');
$fld->developerTags['cbHtmlAfterCheckbox'] = '<i class="input-helper"></i>';

?>
<section class="section">
	<div class="sectionhead">
		<h4><?php echo Labels::getLabel('LBL_Tax_Structure_Setup', $adminLangId); ?></h4>
	</div>
	<div class="sectionbody space">
		<div class="tabs_nav_container responsive flat">
			<div class="tabs_panel_wrap">
				<div class="tabs_panel">
					<?php echo $frm->getFormTag(); ?>
					<div class="row">
						<div class="col-md-12">
							<div class="field-set">
								<div class="caption-wraper">
									<label class="field_label">
									<?php $fld = $frm->getField('taxstr_name['.$siteDefaultLangId.']');
										echo $fld->getCaption(); ?>
									<span class="spn_must_field">*</span></label>
								</div>
								<div class="field-wraper">
									<div class="field_cover">
									<?php echo $frm->getFieldHtml('taxstr_name['.$siteDefaultLangId.']'); ?>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="field-set">
								<div class="caption-wraper">
								</div>
								<div class="field-wraper">
									<div class="field_cover">
									<?php echo $frm->getFieldHtml('taxstr_is_combined'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row" id="combinedTax-js" <?php echo (!array_key_exists('taxstr_is_combined', $taxStrData) || (!$taxStrData['taxstr_is_combined'])) ? 'style="display:none"' : '';?>>
						<div class="col-md-10">
							<div class="field-set">
								<div class="caption-wraper">
									<label class="field_label">
									<?php
										$fld = $frm->getField('taxstr_component_name['.$siteDefaultLangId.'][]');
										echo $fld->getCaption();
									?>
									<span class="spn_must_field">*</span></label>
								</div>
								<div class="field-wraper">
									<div class="field_cover">
									<?php echo $frm->getFieldHtml('taxstr_component_name['.$siteDefaultLangId.'][]'); ?>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-1">
							<button type="button" class="btn btn--secondary ripplelink remove-combined-form--js" title="<?php echo Labels::getLabel('LBL_Remove', $adminLangId); ?>"><i class="ion-minus-round"></i></button>
						</div>
						<div class="col-md-1">
							<button type="button" class="btn btn--secondary ripplelink add-combined-form--js" title="<?php echo Labels::getLabel('LBL_Add', $adminLangId); ?>"><i class="ion-plus-round"></i></button>
						</div>
					</div>
					<?php 
						$translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
						if(!empty($translatorSubscriptionKey) && count($otherLanguages) > 0){
					?>
					<div class="row">
						<div class="col-md-12">
							<div class="field-set mb-0">
								<div class="caption-wraper"></div>
								<div class="field-wraper">
									<div class="field_cover"> 
									<?php echo $productFrm->getFieldHtml('auto_update_other_langs_data'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
			
					<?php if(!empty($otherLangData)){
					foreach($otherLangData as $langId=>$data) { 
					?>
					<div class="accordians_container accordians_container-categories" defaultLang= "<?php echo $siteDefaultLangId; ?>" language="<?php echo $langId; ?>" id="accordion-language_<?php echo $langId; ?>" onClick="translateData(this)">
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
												<?php  $fld = $frm->getField('taxstr_name['.$langId.']');
													echo $fld->getCaption(); ?>
												</label>
											</div>
											<div class="field-wraper">
												<div class="field_cover">
												<?php echo $frm->getFieldHtml('taxstr_name['.$langId.']'); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-12" id="combinedTaxLang-js" <?php echo (!array_key_exists('taxstr_is_combined', $taxStrData) || (!$taxStrData['taxstr_is_combined'])) ? 'style="display:none"' : '';?>>
										<div class="field-set">
											<div class="caption-wraper">
												<label class="field_label">
												<?php  $fld = $frm->getField('taxstr_component_name['.$langId.'][]');
													echo $fld->getCaption(); ?>
												</label>
											</div>
											<div class="field-wraper">
												<div class="field_cover">
												<?php echo $frm->getFieldHtml('taxstr_component_name['.$langId.'][]'); ?>
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
					<?php  echo $frm->getFieldHtml('taxstr_id'); ?>
					</form>
					<?php echo $frm->getExternalJS(); ?>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
    $(document).ready(function(){
        var combTaxCount = <?php echo $combTaxCount; ?>;
        $('body').on('click', '.add-combined-form--js', function(){
            combTaxCount++;
            var parentIndex = $(this).parents('.tax-rule-form--js').data('index');
            var rowHtml = '<tr class="rule-detail-row--js rule-detail-row'+combTaxCount+'"><td scope="row"> <?php $idFld = $frm->getField('taxruledet_id[]'); $idFld->value = ""; $nameFld = $frm->getField('taxruledet_name['.$adminLangId.'][]'); $nameFld->value = ""; $rateFld = $frm->getField('taxruledet_rate[]'); $rateFld->value = ""; echo $frm->getFieldHtml('taxruledet_id[]'); echo $frm->getFieldHtml('taxruledet_name['.$adminLangId.'][]');?></td><td> <?php echo $frm->getFieldHtml('taxruledet_rate[]');?></td><td> <button type="button" class="btn btn--secondary ripplelink remove-combined-form--js" title="<?php echo Labels::getLabel('LBL_Remove', $adminLangId); ?>"><i class="ion-minus-round"></i></button></td></tr>';
            $('.tax-rule-form-'+ parentIndex +' .combined-tax-details--js tbody').append(rowHtml);

            <?php foreach ($otherLanguages as $langId => $data) { ?>
                var langRowHtml = '<div class="field-set rule-detail-row'+combTaxCount+'"><div class="caption-wraper"><label class="field_label"><?php echo Labels::getLabel('LBL_Tax_Name', $adminLangId);?><span class="replaceText--js"></span></label></div><div class="field-wraper"><div class="field_cover"><?php $nameFld = $frm->getField('taxruledet_name['.$langId.'][]'); $nameFld->value = ""; echo $frm->getFieldHtml('taxruledet_name['.$langId.'][]'); ?></div></div></div>';
                $('.tax-rule-form-'+ parentIndex +' .combined-tax-lang-details--js'+<?php echo $langId; ?>).append(langRowHtml);
            <?php } ?>
            //$("table tbody").append(markup);
        });
        // Find and remove selected table rows
        $('body').on('click', '.remove-combined-form--js', function(){
            var rowCount = $(this).parents('tbody').find('tr').length;
            if (rowCount > 1) {
                var className = $(this).parents('tr').attr('class').split(' ').pop();
                $('.'+className).remove();
                $(this).parents('tr').remove();
            }
        });
    });
</script>