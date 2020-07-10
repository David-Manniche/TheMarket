<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('onsubmit', 'setupRequiredFields(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 6;

$btnFld = $frm->getField('btn_submit');
$btnFld->addFieldTagAttribute('class', 'btn btn-primary');

$btnFld = $frm->getField('btn_clear');
if (null != $btnFld) {
    $btnFld->addFieldTagAttribute('class', 'btn btn-outline-primary');
    $btnFld->addFieldTagAttribute('onClick', 'clearForm();');
} ?>

<hr>
<div class="section__body">
    <?php $this->includeTemplate('stripe-connect/fieldsErrors.php', ['errors' => $errors]); ?>
    <?php echo $frm->getFormHtml(); ?>
</div>