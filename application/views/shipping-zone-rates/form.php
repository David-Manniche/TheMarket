<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('onsubmit', 'setupRate(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;

$nameFld = $frm->getField('shiprate_identifier');
$nameFld->htmlAfterField = "<span class='form-text text-muted'>".Labels::getLabel("LBL_Customers_will_see_this_at_checkout.", $siteLangId)."</span>";

$costFld = $frm->getField('shiprate_cost');
$costFld->htmlAfterField = "<div class='gap'></div><p class='add-condition--js'><a href='javascript:0;' onclick='modifyRateFields(1);'>".Labels::getLabel("LBL_Add_Condition", $siteLangId)."</a></p> <p class='remove-condition--js' style='display : none;'><a href='javascript:0;' onclick='modifyRateFields(0);'>".Labels::getLabel("LBL_Remove_Condition", $siteLangId)."</a></p>";
$extraClass = 'hide-extra-fields';
if (!empty($rateData) && $rateData['shiprate_condition_type'] > 0) {
    $extraClass = '';
}

$cndFld = $frm->getField('shiprate_condition_type');
$cndFld->setWrapperAttribute('class', 'condition-field--js '. $extraClass);

$minFld = $frm->getField('shiprate_min_val');
$minFld->setWrapperAttribute('class', 'condition-field--js '. $extraClass);

$maxFld = $frm->getField('shiprate_max_val');
$maxFld->setWrapperAttribute('class', 'condition-field--js '. $extraClass);

$cancelFld = $frm->getField('btn_cancel');
$cancelFld->setFieldTagAttribute('onClick', 'searchProductsSection($(\'input[name="profile_id"]\').val()); return false;');
?>
<div class="sectionhead mb-3">
	<h5 class="cards-title"><?php echo Labels::getLabel('LBL_Manage_Rates', $siteLangId); ?>
	</h5>
</div>
<div class="sectionbody space">
	<div class="row">
		<div class="col-sm-12">
			<div class="tabs">
				<ul class="tabs_nav-js">
					<li class="is-active">
						<a href="javascript:void(0)"
							onclick="addEditShipRates(<?php echo $zoneId ?>, <?php echo $rateId ?>);"><?php echo Labels::getLabel('LBL_General', $siteLangId); ?></a>
					</li>
					<?php foreach ($languages as $key => $langName) { ?>
					<li>
						<a href="javascript:void(0);" <?php if ($rateId > 0) { ?>
							onclick="editRateLangForm(<?php echo $zoneId ?>, <?php echo $rateId ?>, <?php echo $key;?>);" <?php } ?>><?php echo Labels::getLabel('LBL_'. $langName, $siteLangId);?></a>
					</li>
					<?php } ?>
				</ul>
			</div>
			<div class="tabs__content">
				<?php echo $frm->getFormHtml(); ?>
			</div>
		</div>
	</div>
</div>
<?php
if (!empty($rateData) && $rateData['shiprate_condition_type'] > 0) { ?>
<script>
	$(document).ready(function() {
		$('.add-condition--js').hide();
		$('.remove-condition--js').show();
	});
</script>
<?php }