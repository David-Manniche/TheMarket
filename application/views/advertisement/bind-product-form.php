<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('id', 'bindProducts');
$frm->setFormTagAttribute('onsubmit', 'setupProductsToBatch(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 3;

$btnFld = $frm->getField('btn_submit');
$btnFld->addFieldTagAttribute('class', 'btn btn--primary');

$btnFld = $frm->getField('btn_clear');
$btnFld->addFieldTagAttribute('class', 'btn btn--primary-border');
$btnFld->addFieldTagAttribute('onClick', 'clearForm();');

/* $prodCatFld = $frm->getField('google_product_category');
$prodCatFld->setWrapperAttribute('class', 'col-lg-4');
$prodCatFld->developerTags['col'] = 4; */
echo $frm->getFormHtml();
