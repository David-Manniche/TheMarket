<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$pmethodName = $paymentMethod["plugin_name"];
$pmethodDescription = $paymentMethod["plugin_description"];
$pmethodCode = $paymentMethod["plugin_code"];

$frm->setFormTagAttribute('class', 'form');
if ('cashondelivery' != strtolower($pmethodCode)) {
    $frm->developerTags['colClassPrefix'] = 'col-lg-12 col-md-12 col-sm-';
    $frm->developerTags['fld_default_col'] = 12;
    $frm->setFormTagAttribute('onsubmit', 'confirmOrder(this); return(false);');
}

$submitFld = $frm->getField('btn_submit');
$submitFld->setFieldTagAttribute('class', "btn btn-primary btn-wide");

if ('cashondelivery' == strtolower($pmethodCode)) { ?>
    <div class="otp-block otpBlock-js">
        <div class="otp-block__head">
            <h5><?php echo Labels::getLabel('LBL_OTP_VERIFICATION', $siteLangId); ?></h5>
            <p>
                <?php
                if (true == $canSendSms) {
                    $userDialCode = $userData['user_dial_code'];
                    $phone = $userData['user_phone'];
                    $msg = Labels::getLabel('LBL_ENTER_OTP_SENT_TO_{PHONE}', $siteLangId);
                    echo CommonHelper::replaceStringData($msg, ['{PHONE}' => '<strong>' . $userDialCode . $phone . '</strong>']);
                } else {
                    $msg = Labels::getLabel('LBL_ENTER_OTP_SENT_TO_{EMAIL}', $siteLangId);
                    echo CommonHelper::replaceStringData($msg, ['{EMAIL}' => '<strong>' . $userData['credential_email'] . '</strong>']);
                }
                ?>
            </p>
        </div>
        <div class="otp-block__body">
            <div class="otp-enter">
                <div class="otp-inputs">
                    <?php
                    $frm->setFormTagAttribute('class', 'form');
                    $frm->setFormTagAttribute('onsubmit', 'validateOtp(this); return(false);');

                    for ($i = 0; $i < User::OTP_LENGTH; $i++) {
                        $fld = $frm->getField('upv_otp[' . $i . ']');
                        $fld->setFieldTagAttribute('class', 'field-otp otpVal-js');
                        $fld->developerTags['noCaptionTag'] = true;
                        $fld->setWrapperAttribute('class', 'otpCol-js');
                    }
                    
                    $submitFld->developerTags['noCaptionTag'] = true;
                    $submitFld->developerTags['col'] = 12;
                    echo $frm->getFormHtml();
                    ?>
                </div>
            </div>
        </div>
        <div class="otp-block__footer text-center">
            <span>
                <p>
                    <?php
                    $msg = Labels::getLabel('LBL_OTP_EXPIRES_IN_{TIMER}_SECONDS', $siteLangId);
                    $htm = '<span class="txt-success font-weight-bold intervalTimer-js">
                                        ' . User::OTP_INTERVAL . '
                                    </span>';
                    echo CommonHelper::replaceStringData($msg, ['{TIMER}' => $htm]);
                    ?>
                </p>
            </span>
            <p class="d-none resendOtpDiv-js">
                <?php echo Labels::getLabel("LBL_DIDN'T_GET_OTP.", $siteLangId); ?>
                <a href="javaScript:void(0)" class="txt-success font-weight-bold resendOtp-js" onClick="resendOtp()">
                    <?php echo Labels::getLabel('LBL_RESEND_?', $siteLangId); ?>
                </a>
            </p>
        </div>
    </div>
    <div class="otp-block successOtp-js d-none">
        <div class="otp-success">
            <img class="img" src="<?php echo CONF_WEBROOT_URL; ?>images/retina/otp-complete.svg" alt="">
            <h5><?php echo Labels::getLabel('LBL_SUCCESS', $siteLangId); ?></h5>
            <p>Lorem ipsum dolor sit amet consectetur </p>
        </div>
    </div>
<?php } else { ?>
    <div class="text-center paymentForm-js d-none">
        <?php if (!isset($error)) {
            echo $frm->getFormHtml();
        }
        ?>
    </div>
<?php } ?>
<script type="text/javascript">
    $("document").ready(function() {
        <?php if (isset($error)) { ?>
            $.mbsmessage(<?php echo $error; ?>, true, 'alert--danger');
        <?php } ?>
        <?php if ('cashondelivery' == strtolower($pmethodCode)) { ?>
            startOtpInterval('', "showElements");
        <?php } ?>
    });

    function confirmOrder(frm) {
        var data = fcom.frmData(frm);
        var action = $(frm).attr('action')
        var getExternalLibraryUrl = $(frm).data('external');
        $.mbsmessage(langLbl.processing, false, 'alert--process alert');
        fcom.ajax(fcom.makeUrl('Checkout', 'ConfirmOrder'), data, function(res) {
            if ('undefined' != typeof getExternalLibraryUrl) {
                fcom.ajax(getExternalLibraryUrl, '', function(t) {
                    var json = $.parseJSON(t);
                    if (1 > json.status) {
                        $("#tabs-container form input[type='submit']").val(langLbl.confirmPayment);
                        $.mbsmessage(json.msg, true, 'alert--danger');
                        return;
                    }

                    if (0 < (json.libraries).length) {
                        $.each(json.libraries, function(key, src) {
                            loadScript(src, loadChargeForm, [action]);
                        });
                    } else {
                        loadChargeForm(action);
                    }
                });
            } else {
                loadChargeForm(action);
            }
        });
    }

    function loadChargeForm(action) {
        fcom.ajax(action, '', function(t) {
            $.mbsmessage.close();
            try {
                var ans = $.parseJSON(t);
                if (1 > ans.status) {
                    $.mbsmessage(ans.msg, true, 'alert--danger');
                    return false;
                } else if ('undefined' != typeof ans.redirect) {
                    location.href = ans.redirect;
                } else {
                    $('#tabs-container').html(ans.html);
                }
            } catch (e) {
                // console.log(e);
            }
        });
    }

    function showElements() {
        $(".resendOtpDiv-js").removeClass("d-none");
        // $(".intervalTimer-js").parent().parent().show();
    }
</script>