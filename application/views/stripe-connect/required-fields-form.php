<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
// $onSubmit = !empty($fieldType) && 'external_account' == $fieldType ? 'setupFinancialInfo(this)' : 'setupRequiredFields(this)';
$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('onsubmit', 'setupRequiredFields(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 6;

$btnFld = $frm->getField('btn_submit');
$btnFld->addFieldTagAttribute('class', 'btn btn--primary');

$btnFld = $frm->getField('btn_clear');
if (null != $btnFld) {
    $btnFld->addFieldTagAttribute('class', 'btn btn-outline-primary');
    $btnFld->addFieldTagAttribute('onClick', 'clearForm();');
} ?>

<div class="container">
	<div class="row">
		<div class="col-md-12 align--center">
			<div class="width--narrow">
				<h2><?php echo $pageTitle; ?></h2>
			</div>
		</div>
	</div>
	<div class="panel panel--centered clearfix">
		<div class="clearfix">
			<div class="section__body">
				<div class="box box--white">
					<?php echo $frm->getFormHtml(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
