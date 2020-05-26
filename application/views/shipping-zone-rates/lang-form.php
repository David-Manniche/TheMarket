<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$langFrm->setFormTagAttribute('class', 'form');
$langFrm->setFormTagAttribute('onsubmit', 'setupLangRate(this); return(false);');
$langFrm->developerTags['colClassPrefix'] = 'col-md-';
$langFrm->developerTags['fld_default_col'] = 12;

$cancelFld = $langFrm->getField('btn_cancel');
$cancelFld->setFieldTagAttribute('onClick', 'searchProductsSection($(\'input[name="profile_id"]\').val()); return false;');

?>
<div dir="<?php echo $formLayout; ?>">
	<div class="sectionhead mb-3">
		<h5 class="cards-title"><?php echo Labels::getLabel('LBL_Manage_Rates', $siteLangId); ?>
		</h5>
	</div>
	<div class="sectionbody space">
		<div class="row">
			<div class="col-sm-12">
				<div class="tabs">
					<ul class="tabs_nav-js">
						<li>
							<a href="javascript:void(0);"
								onclick="addEditShipRates(<?php echo $zoneId ?>, <?php echo $rateId ?>);"><?php echo Labels::getLabel('LBL_General', $siteLangId); ?></a>
						</li>
						<?php
                        foreach ($languages as $key => $langName) {
                            $class = ($langId == $key) ? 'is-active' : ''; ?>
						<li class="<?php echo $class; ?>">
							<a href="javascript:void(0);" <?php if ($rateId > 0) { ?>
								onclick="editRateLangForm(<?php echo $zoneId ?>, <?php echo $rateId ?>, <?php echo $key;?>);" <?php } ?>><?php echo Labels::getLabel('LBL_'. $langName, $siteLangId); ?></a>
						</li>
						<?php
                        } ?>
					</ul>
				</div>
				<div class="tabs__content">
					<?php echo $langFrm->getFormHtml(); ?>
				</div>
			</div>
		</div>
	</div>
</div>