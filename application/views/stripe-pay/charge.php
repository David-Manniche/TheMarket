<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

if (isset($stripe)) {
    if (isset($stripe['secret_key']) && isset($stripe['publishable_key'])) {
        if (!empty($stripe['secret_key']) && !empty($stripe['publishable_key'])) { ?>
            <?php if (!FatUtility::isAjaxCall()) { ?>
                    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
                    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
            <?php } ?>
            <script type="text/javascript">
                var publishable_key = '<?php echo $stripe['publishable_key']; ?>';
            </script>
    <?php }
    }
}
if (isset($client_secret)) { ?>
    <script type="text/javascript">
        function loadCardConfirmation() {
            var stripe = Stripe(publishable_key);
            var clientSecret = '<?php echo $client_secret; ?>';
            stripe.confirmCardPayment(clientSecret, {
                payment_method: '<?php echo $payment_method_id; ?>'
            }).then(function(result) {
                // console.log(result);
                if (result.error) {
                    // PaymentIntent client secret was invalid
                    location.href = '<?php echo $cancelBtnUrl; ?>';
                } else {
                    if (result.paymentIntent.status === 'succeeded') {

                        var data = 'order_id=<?php echo $order_id ?>&payment_intent_id=<?php echo $payment_intent_id ?>&is_ajax_request=yes';

                        $.ajax({
                            type: "POST",
                            url: '<?php echo UrlHelper::generateUrl('StripePay', 'StripeSuccess') ?>',
                            data: data,
                            success: function(data) {
                                location.href = '<?php echo UrlHelper::generateUrl('custom', 'paymentSuccess', array($order_id), CONF_WEBROOT_URL); ?>';
                            }
                        });

                    } else if (result.paymentIntent.status === 'requires_payment_method') {
                        // Authentication failed, prompt the customer to enter another payment method
                        location.href = '<?php echo UrlHelper::generateUrl('custom', 'paymentFailed'); ?>';
                    }
                }
            });
        }
        $(document).ready(function() {
            $('.cc-payment').addClass('payment-load');
            loadCardConfirmation();
        });
    </script>
<?php exit;
} else { ?>
    <script>
        (function($) {
            var _this = false;
            var _subText = false;

            loadStripe = function() {
                try {
                    if (typeof publishable_key != typeof undefined) {
                        // this identifies your website in the createToken call below
                        Stripe.setPublishableKey(publishable_key);
                        // console.log(Stripe);
                        function stripeResponseHandler(status, response) {
                            $('#stripeCharge').find(":submit").attr('disabled', 'disabled');
                            $submit = true;
                            if (_this && _subText) {
                                _this.find('input[type=submit]').val(_subText);
                            }

                            if (response.error) {
                                $("#stripeCharge").prepend('<div class="alert alert--danger">' + response.error.message + '</div>');
                                $("#stripeCharge").find(":submit").removeAttr('disabled');
                            } else {

                                var form = $("#stripeCharge");
                                // token contains id, last4, and card type
                                var token = response['id'];
                                // insert the token into the form so it gets submitted to the server
                                form.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                                form.attr('onsubmit', 'sendPayment(this, ".payment-from"); return(false);');
                                form.submit();
                            }

                        }
                        $submit = true;
                        $(document).on("submit", "#stripeCharge", function(event) {
                            event.preventDefault();
                            var stripeToken = $("input[name='stripeToken']").val();
                            if ('' != stripeToken && 'undefined' != typeof stripeToken) {
                                return;
                            }

                            // prop('disabled', true);
                            $('.alert--danger').remove();

                            _this = $(this);
                            var _numberWrap = $('#cc_number');
                            var _cvvWrap = $('#cc_cvv');
                            var _expMonthWrap = $('#cc_expire_date_month');
                            var _expYearWrap = $('#cc_expire_date_year');
                            _subText = _this.find('input[type=submit]').val();


                            if ($submit && _numberWrap.length > 0 && _cvvWrap.length > 0 && _expMonthWrap.length > 0 && _expYearWrap.length > 0) {

                                var _numberValue = _numberWrap.val().trim();
                                var _cvvValue = _cvvWrap.val().trim();
                                var _expMonthValue = _expMonthWrap.val().trim();
                                var _expYearValue = _expYearWrap.val().trim();

                                if (_numberValue != '' && _cvvValue != '' && _expMonthValue != '' && _expYearValue != '') {
                                    $submit = false;
                                    _this.find('input[type=submit]').val(_this.find('input[type=submit]').data('processing-text'));

                                    Stripe.createToken({
                                        number: _numberValue,
                                        cvc: _cvvValue,
                                        exp_month: _expMonthValue,
                                        exp_year: _expYearValue
                                    }, stripeResponseHandler);
                                }

                            }
                            return $submit; // submit from callback
                        });

                    }

                } catch (e) {
                    // console.log(e.message);
                    setTimeout(function() {
                        loadStripe();
                    }, 500);
                }
            }
            loadStripe();
        })(jQuery);
    </script>
<?php } ?>

<div class="payment-page">
    <div class="cc-payment">
        <?php $this->includeTemplate('_partial/paymentPageLogo.php', array('siteLangId' => $siteLangId)); ?>
        <div class="reff row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <p class=""><?php echo Labels::getLabel('LBL_Payable_Amount', $siteLangId); ?> : <strong><?php echo CommonHelper::displayMoneyFormat($paymentAmount) ?></strong> </p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <p class=""><?php echo Labels::getLabel('LBL_Order_Invoice', $siteLangId); ?>: <strong><?php echo $orderInfo["invoice"]; ?></strong></p>
            </div>
        </div>
        <div class="payment-from">
            <?php if (!isset($error)) :
                // $frm->setFormTagAttribute('onsubmit', 'sendPayment(this); return(false);');
                $frm->setFormTagAttribute('id', 'stripeCharge');
                $fld = $frm->getField('cc_number');
                $fld->addFieldTagAttribute('class', 'p-cards');
                $fld->addFieldTagAttribute('id', 'cc_number');
                $fld = $frm->getField('cc_owner');
                $fld->addFieldTagAttribute('id', 'cc_owner');
                $fld = $frm->getField('cc_cvv');
                $fld->addFieldTagAttribute('id', 'cc_cvv'); ?>
                <?php echo $frm->getFormTag(); ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-set">
                            <div class="caption-wraper">
                                <label class="field_label"><?php echo Labels::getLabel('LBL_ENTER_CREDIT_CARD_NUMBER', $siteLangId); ?></label>
                            </div>
                            <div class="field-wraper">
                                <div class="field_cover">
                                    <?php echo $frm->getFieldHtml('cc_number'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-set">
                            <div class="caption-wraper">
                                <label class="field_label"><?php echo Labels::getLabel('LBL_CARD_HOLDER_NAME', $siteLangId); ?></label>
                            </div>
                            <div class="field-wraper">
                                <div class="field_cover">
                                    <?php echo $frm->getFieldHtml('cc_owner'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="caption-wraper">
                            <label class="field_label"> <?php echo Labels::getLabel('LBL_ENTER_CREDIT_CARD_NUMBER', $siteLangId); ?> </label>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="field-set">
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                            <?php
                                            $fld = $frm->getField('cc_expire_date_month');
                                            $fld->addFieldTagAttribute('id', 'cc_expire_date_month');
                                            $fld->addFieldTagAttribute('class', 'ccExpMonth  combobox required');
                                            echo $fld->getHtml(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="field-set">
                                    <div class="field-wraper">
                                        <div class="field_cover">
                                            <?php
                                            $fld = $frm->getField('cc_expire_date_year');
                                            $fld->addFieldTagAttribute('id', 'cc_expire_date_year');
                                            $fld->addFieldTagAttribute('class', 'ccExpYear combobox required');
                                            echo $fld->getHtml(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-set">
                            <div class="caption-wraper">
                                <label class="field_label"><?php echo Labels::getLabel('LBL_CVV_SECURITY_CODE', $siteLangId); ?></label>
                            </div>
                            <div class="field-wraper">
                                <div class="field_cover">
                                    <?php echo $frm->getFieldHtml('cc_cvv'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php /* 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="field-set">
                                <div class="caption-wraper">
                                    <label class="field_label"></label>
                                </div>
                                <div class="field-wraper">
                                    <div class="field_cover">
                                        <label class="checkbox">
                                            <?php
                                                $fld = $frm->getField('cc_save_card');
                                                $fld->addFieldTagAttribute('onclick','alert("|SAVE THIS CARD| Not Functional!");return false;');
                                                $fldHtml = $fld->getHTML();
                                                $fldHtml = str_replace("<label >","",$fldHtml);
                                                $fldHtml = str_replace("</label>","",$fldHtml);
                                                echo $fldHtml;
                                                ?>
                                            <i class="input-helper"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    */ ?>
                <div class="total-pay"><?php echo CommonHelper::displayMoneyFormat($paymentAmount) ?> <small>(<?php echo Labels::getLabel('LBL_Total_Payable', $siteLangId); ?>)</small> </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="field-set">
                            <div class="caption-wraper">
                                <label class="field_label"></label>
                            </div>
                            <div class="field-wraper">
                                <div class="field_cover">
                                    <?php $frm->getField('btn_submit')->addFieldTagAttribute('data-processing-text', Labels::getLabel('L_Please_Wait..', $siteLangId));
                                    echo $frm->getFieldHtml('btn_submit'); ?>
                                    <?php /* <a href="<?php echo $cancelBtnUrl; ?>" class="link link--normal"><?php echo Labels::getLabel('LBL_Cancel', $siteLangId); ?></a> */ ?>
                                    <a href="javascript:void(0);" onclick="loadPaymentSummary()" class="link link--normal"><?php echo Labels::getLabel('LBL_Cancel', $siteLangId); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
                <?php echo $frm->getExternalJs(); ?>
            <?php else : ?>
                <div class="alert alert--danger"><?php echo $error ?></div>
            <?php endif; ?>
            <div id="ajax_message"></div>
        </div>
    </div>
</div>