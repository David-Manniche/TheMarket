<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 

$userIdFld = $frm->getField('user_id');
$userId = $userIdFld->value;

$frm->setFormTagAttribute('class', 'form form--normal');
$frm->developerTags['fld_default_col'] = 2;

$frm->setFormTagAttribute('class', 'form form-otp');
$frm->setFormTagAttribute('name', 'frmGuestLoginOtp');
$frm->setFormTagAttribute('id', 'frmGuestLoginOtp');
$frm->setFormTagAttribute('onsubmit', 'return validateOtp(this);');

$btnFld = $frm->getField('btn_submit');
$btnFld->setFieldTagAttribute('class', 'btn--block');
?>

<div class="form-side-inner">
    <div class="section-head">
        <div class="section__heading">
            <h2><?php echo Labels::getLabel('LBL_ONE_TYPE_PASSWORD?', $siteLangId);?></h2>
            <p class="note"><?php echo Labels::getLabel('LBL_ENTER_THE_OTP_YOU_RECEIVED_ON_YOUR_PHONE_NUMBER', $siteLangId);?></p>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <?php echo $frm->getFormTag(); ?>
                    <div class="otp-row">
                        <?php for ($i = 0; $i < User::OTP_LENGTH; $i++) { ?>
                            <div class="otp-col">
                                <?php
                                $fld = $frm->getField('upv_otp[' . $i . ']');
                                $fld->setFieldTagAttribute('class', 'otpVal');
                                echo $frm->getFieldHtml('upv_otp[' . $i . ']'); ?>
                                <?php if ($i < (User::OTP_LENGTH - 1)) { ?>
                                    <span>-</span>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <span class="note mb-2">
                        <a href="javaScript:void(0)" onClick="resendOtp(<?php echo $userId; ?>, <?php echo applicationConstants::YES; ?>)">
                            <?php echo Labels::getLabel('LBL_RESEND_OTP?', $siteLangId); ?>
                        </a>
                    </span>
                    <?php 
                        echo $frm->getFieldHtml('user_id');
                        echo $frm->getFieldHtml('btn_submit');
                    ?>
                </form>
                <?php echo $frm->getExternalJs(); ?>
            </div>
        </div>
    </div>
</div>