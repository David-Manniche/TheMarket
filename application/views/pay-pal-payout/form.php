<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'payPalFrm');
$frm->setFormTagAttribute('class', 'form');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 6;
$fld = $frm->getField('ub_bank_address');
$fld->developerTags['col'] = 12;
$fld = $frm->getField('btn_submit');
$fld->developerTags['col'] = 12;
$frm->setFormTagAttribute('onsubmit', 'setupPluginForm(this); return(false);');
?>
<div class="row">
    <div class="col-md-8">
        <?php echo $frm->getFormHtml();?>
    </div>
</div>
