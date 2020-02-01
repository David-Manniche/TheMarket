<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 

$frm->setFormTagAttribute('class', 'form form--normal');
$frm->developerTags['colClassPrefix'] = 'col-lg-12 col-md-12 col-sm-';
$frm->developerTags['fld_default_col'] = 12;

$frm->setFormTagAttribute('class', 'form form-otp');
$frm->setFormTagAttribute('name', 'frmGuestLoginOtp');
$frm->setFormTagAttribute('id', 'frmGuestLoginOtp');
$frm->setFormTagAttribute('onsubmit', 'return validateOtp(this);');

$btnFld = $frm->getField('btn_submit');
$btnFld->setFieldTagAttribute('class', 'btn--block');

$frmFld = $frm->getField('upv_otp');
$frmFld->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_OTP*', $siteLangId));
$frmFld->developerTags['noCaptionTag'] = true;

$frmFld = $frm->getField('btn_submit');
$frmFld->developerTags['noCaptionTag'] = true;
?>

<div class="form-side-inner">
    <div class="section-head">
        <div class="section__heading">
            <h2><?php echo Labels::getLabel('LBL_ONE_TYPE_PASSWORD?', $siteLangId);?></h2>
            <p class="note"><?php echo Labels::getLabel('LBL_ENTER_THE_OTP_YOU_RECEIVED_ON_YOUR_PHONE_NUMBER', $siteLangId);?></p>
        </div>
    </div>
    <?php echo $frm->getFormHtml(); ?>
</div>