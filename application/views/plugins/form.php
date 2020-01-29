<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'frmPlugins');
$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('onsubmit', 'setupPluginForm(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 3;

$submitBtnFld = $frm->getField('btn_submit');

$frm->addButton("", "btn_cancel", Labels::getLabel("LBL_Cancel", $siteLangId));
$cancelBtnFld = $frm->getField('btn_cancel');
$cancelBtnFld->setFieldTagAttribute('onClick', 'closeForm()');
$cancelBtnFld->setFieldTagAttribute('class', 'btn--primary-border');
$submitBtnFld->attachField($cancelBtnFld);
?>
<div class="cards-header p-4">
    <h5 class="cards-title"><?php echo $identifier ?> <?php echo Labels::getLabel('LBL_Form', $siteLangId); ?></h5>
</div>
<div class="cards-content pl-4 pr-4 ">
    <?php echo $frm->getFormHtml(); ?>
</div>