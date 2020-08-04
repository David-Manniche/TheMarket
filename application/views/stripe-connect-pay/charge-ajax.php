<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$paymentIntendId = isset($paymentIntendId) ? $paymentIntendId : '';
$frm->setFormTagAttribute('onsubmit', 'doPayment(this, "' . $orderInfo["id"] . '"); return(false);');
$frm->setFormTagAttribute('class', 'form form--normal');
$fld = $frm->getField('btn_addnew');
if (null != $fld) {
    $fld->addFieldTagAttribute('onclick', "addNewCard('" . $orderInfo["id"] . "')");
}

echo $frm->getFormTag(); ?>
    <div class="row">
        <div class="col-md-12">
            <ul class="list-group payment-card">
                <?php
                $savedCards = isset($savedCards['data']) ? $savedCards['data'] : [];
                foreach ($savedCards as $cardDetail) { ?>
                    <li class="list-group-item ribbon ribbon--danger ribbon--right ribbon--round selectCard-js" data-cardid="<?php echo $cardDetail['id']; ?>">
                        <div class="ribbon__target" style="top: 22px; left: -2px;">
                            <i class="fa fa-star"></i>
                        </div>
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="payment-card__photo">
                                    <svg class="svg payment-list__item" viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" role="img" width="38" height="24" aria-labelledby="pi-visa">
                                        <title id="pi-visa"><?php echo $cardDetail['brand']; ?></title>
                                        <path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z">
                                        </path>
                                        <path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32">
                                        </path>
                                        <path d="M28.3 10.1H28c-.4 1-.7 1.5-1 3h1.9c-.3-1.5-.3-2.2-.6-3zm2.9 5.9h-1.7c-.1 0-.1 0-.2-.1l-.2-.9-.1-.2h-2.4c-.1 0-.2 0-.2.2l-.3.9c0 .1-.1.1-.1.1h-2.1l.2-.5L27 8.7c0-.5.3-.7.8-.7h1.5c.1 0 .2 0 .2.2l1.4 6.5c.1.4.2.7.2 1.1.1.1.1.1.1.2zm-13.4-.3l.4-1.8c.1 0 .2.1.2.1.7.3 1.4.5 2.1.4.2 0 .5-.1.7-.2.5-.2.5-.7.1-1.1-.2-.2-.5-.3-.8-.5-.4-.2-.8-.4-1.1-.7-1.2-1-.8-2.4-.1-3.1.6-.4.9-.8 1.7-.8 1.2 0 2.5 0 3.1.2h.1c-.1.6-.2 1.1-.4 1.7-.5-.2-1-.4-1.5-.4-.3 0-.6 0-.9.1-.2 0-.3.1-.4.2-.2.2-.2.5 0 .7l.5.4c.4.2.8.4 1.1.6.5.3 1 .8 1.1 1.4.2.9-.1 1.7-.9 2.3-.5.4-.7.6-1.4.6-1.4 0-2.5.1-3.4-.2-.1.2-.1.2-.2.1zm-3.5.3c.1-.7.1-.7.2-1 .5-2.2 1-4.5 1.4-6.7.1-.2.1-.3.3-.3H18c-.2 1.2-.4 2.1-.7 3.2-.3 1.5-.6 3-1 4.5 0 .2-.1.2-.3.2M5 8.2c0-.1.2-.2.3-.2h3.4c.5 0 .9.3 1 .8l.9 4.4c0 .1 0 .1.1.2 0-.1.1-.1.1-.1l2.1-5.1c-.1-.1 0-.2.1-.2h2.1c0 .1 0 .1-.1.2l-3.1 7.3c-.1.2-.1.3-.2.4-.1.1-.3 0-.5 0H9.7c-.1 0-.2 0-.2-.2L7.9 9.5c-.2-.2-.5-.5-.9-.6-.6-.3-1.7-.5-1.9-.5L5 8.2z" fill="#142688"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="col">
                                <div class="payment-card__number"><?php echo Labels::getLabel('LBL_ENDING_IN', $siteLangId); ?>
                                    <strong><?php echo $cardDetail['last4']; ?></strong>
                                </div>
                            </div>
                            <div class="col">
                                <div class="payment-card__name"><?php echo $cardDetail['name']; ?></div>
                            </div>
                            <div class="col">
                                <div class="payment-card__expiry"><?php echo Labels::getLabel('LBL_EXPIRY', $siteLangId); ?>
                                    <strong><?php echo $cardDetail['exp_month'] . '/' . $cardDetail['exp_year']; ?></strong></div>
                            </div>
                            <div class="col-auto">
                                <div class="payment-card__actions">
                                    <ul class="list-actions">
                                        <li>
                                            <a href="javascript:void(0)" onClick="removeCard('<?php echo $cardDetail['id']; ?>');"><i class='fa fa-trash'></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <?php echo $frm->getFieldHtml('btn_addnew'); ?>
        </div>
    </div>
    <?php if (!empty($savedCards)) { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label"></label>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <?php
                            $btn = $frm->getField('btn_submit');
                            $btn->addFieldTagAttribute('class', 'btn btn-primary');
                            $btn->addFieldTagAttribute('data-processing-text', Labels::getLabel('LBL_PLEASE_WAIT..', $siteLangId));
                            ?>
                            <?php if (FatUtility::isAjaxCall()) { ?>
                                <a href="javascript:void(0);" onclick="loadPaymentSummary()" class="btn btn-outline-primary">
                                    <?php echo Labels::getLabel('LBL_Cancel', $siteLangId); ?>
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo $cancelBtnUrl; ?>" class="btn btn-outline-primary"><?php echo Labels::getLabel('LBL_Cancel', $siteLangId); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        echo $frm->getFieldHtml('btn_submit');
        echo $frm->getFieldHtml('card_id');
        ?>
    <?php } ?>
</form>
<?php echo $frm->getExternalJs(); ?>
<script src="https://js.stripe.com/v3/"></script>
<?php if (!empty($paymentIntendId)) { ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.cc-payment').addClass('payment-load');
        });
        var publishable_key = '<?php echo $settings['publishable_key']; ?>';
        var stripe = Stripe(publishable_key);

        var clientSecret = '<?php echo $clientSecret; ?>';

        stripe.confirmCardPayment(clientSecret, {
            payment_method: $("input[name='card_id']").val()
        }).then(function(result) {
            if (result.error) {
                // PaymentIntent client secret was invalid
                location.href = '<?php echo $cancelBtnUrl; ?>';
            } else {
                if (result.paymentIntent.status === 'succeeded') {
                    location.href = '<?php echo UrlHelper::generateUrl('StripeConnectPay', 'distribute', [$orderId, $paymentIntendId]); ?>';
                } else if (result.paymentIntent.status === 'requires_payment_method') {
                    // Authentication failed, prompt the customer to enter another payment method
                    location.href = '<?php echo UrlHelper::generateUrl('custom', 'paymentFailed'); ?>';
                }
            }
        });
    </script>
<?php } ?>