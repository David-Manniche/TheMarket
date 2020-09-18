<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$langFrm->setFormTagAttribute('class', 'form');
$langFrm->setFormTagAttribute('onsubmit', 'setupLangRate(this); return(false);');
$langFrm->developerTags['colClassPrefix'] = 'col-sm-4 col-md-';
$langFrm->developerTags['fld_default_col'] = 12;

$cancelFld = $langFrm->getField('btn_cancel');
$cancelFld->setFieldTagAttribute('onClick', 'clearForm(); return false;');
$cancelFld->developerTags['col'] = 3;
$cancelFld->setFieldTagAttribute('class', 'btn btn-outline-primary btn-block');
$cancelFld->developerTags['noCaptionTag'] = true;

$btnSubmit = $langFrm->getField('btn_submit');
$btnSubmit->developerTags['col'] = 3;
$btnSubmit->setFieldTagAttribute('class', "btn btn-primary btn-block");
$btnSubmit->developerTags['noCaptionTag'] = true;

?>
<div >
	<div class="cards-header">
		<h5 class="cards-title"><?php echo Labels::getLabel('LBL_Manage_Rates', $siteLangId); ?></h5>
	</div>
	<div class="cards-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="tabs">
					<ul class="tabs_nav-js">
						<li>
							<a href="javascript:void(0);" onclick="addEditShipRates(<?php echo $zoneId ?>, <?php echo $rateId ?>);"><?php echo Labels::getLabel('LBL_General', $siteLangId); ?></a>
						</li>
						<?php
						foreach ($languages as $key => $langName) {
							$class = ($langId == $key) ? 'is-active' : ''; ?>
							<li class="<?php echo $class; ?>">
								<a href="javascript:void(0);" <?php if ($rateId > 0) { ?> onclick="editRateLangForm(<?php echo $zoneId ?>, <?php echo $rateId ?>, <?php echo $key; ?>);" <?php } ?>><?php echo $langName; ?></a>
							</li>
						<?php
						} ?>
					</ul>
				</div>
				<div class="tabs__content" dir="<?php echo $formLayout; ?>">
					<?php echo $langFrm->getFormHtml(); ?>
				</div>
			</div>
		</div>
	</div>
</div>