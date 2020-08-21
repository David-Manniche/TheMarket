<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $frm->setFormTagAttribute('class', 'form');
$frm->developerTags['colClassPrefix'] = 'col-lg-12 col-md-12 col-sm-';
$frm->developerTags['fld_default_col'] = 12;
$frm->setFormTagAttribute('onsubmit', 'confirmOrder(this); return(false);');

$pmethodName = $paymentMethod["plugin_name"];
$pmethodDescription = $paymentMethod["plugin_description"];
$pmethodCode = $paymentMethod["plugin_code"];

$submitFld = $frm->getField('btn_submit');
$submitFld->setFieldTagAttribute('class', "btn btn-primary");

if ('cashondelivery' == strtolower($pmethodCode)) { ?>
    <div class="otp-block">
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
                        $otpForm->setFormTagAttribute('class', 'form');
                        $frm->setFormTagAttribute('onsubmit', 'verifyOtp(this); return(false);');

                        for ($i = 0; $i < User::OTP_LENGTH; $i++) {
                            $fld = $otpForm->getField('upv_otp[' . $i . ']');
                            $fld->developerTags['noCaptionTag'] = true;
                        }

                        $btn = $otpForm->getField("btn_submit");
                        $btn->setFieldTagAttribute('class', 'btn btn-primary btn-wide');
                        $btn->developerTags['noCaptionTag'] = true;
                        $btn->developerTags['col'] = 12;
                        echo $otpForm->getFormHtml();
                    ?>
                </div>
            </div>
        </div>
        <div class="otp-block__footer">

            <div class="row">
                <div class="col">
                    <p class="">Code Expire in:<span class="txt-success font-weight-bold">
                            00:50</span></p>
                </div>
                <div class="col-auto">
                    <p class="">Didn’t get code <a class="txt-success font-weight-bold" href="">
                            RESEND!</a> </p>
                </div>
            </div>
        </div>
    </div>
    <div class="otp-block">
        <div class="otp-success">
            <img class="img" src="<?php echo CONF_WEBROOT_URL; ?>images/retina/otp-complete.svg" alt="">
            <h5>Success</h5>
            <p>Lorem ipsum dolor sit amet consectetur </p>

        </div>
    </div>
<?php } else { ?>
    <div class="text-center d-none">
        <?php 
            /* <p><strong><?php echo sprintf(Labels::getLabel('LBL_PAY_USING_PAYMENT_METHOD', $siteLangId), $pmethodName) ?>:</strong></p>
            <p><?php echo $pmethodDescription; ?></p>  */
        ?>

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
</script>