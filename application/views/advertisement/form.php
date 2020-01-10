<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('id', 'adsBatchForm');
$frm->setFormTagAttribute('onsubmit', 'setup(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 2;

$adsbatch_name = $frm->getField('adsbatch_name');
$adsbatch_name->setWrapperAttribute('class', 'col-lg-3');
$adsbatch_name->developerTags['col'] = 3;

$btnFld = $frm->getField('btn_submit');
$btnFld->addFieldTagAttribute('class', 'btn--block btn btn--primary');
$btnFld->setWrapperAttribute('class', 'col-lg-3');
$btnFld->developerTags['col'] = 3;

$fld = $frm->getField('adsbatch_expired_on');
$fld->addFieldTagAttribute('class', 'date_js');

echo $frm->getFormHtml();
