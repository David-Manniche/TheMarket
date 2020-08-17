<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$paymentIntendId = isset($paymentIntendId) ? $paymentIntendId : '';
?>
<div class="payment-page">
    <div class="cc-payment">
        <?php $this->includeTemplate('_partial/paymentPageLogo.php', array('siteLangId' => $siteLangId)); ?>
        <div class="reff row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <p class="">
                    <?php echo Labels::getLabel('LBL_Payable_Amount', $siteLangId); ?>
                    : <strong><?php echo CommonHelper::displayMoneyFormat($paymentAmount) ?></strong>
                </p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <p class=""><?php echo Labels::getLabel('LBL_Order_Invoice', $siteLangId); ?>:
                    <strong><?php echo $orderInfo["invoice"]; ?></strong>
                </p>
            </div>
        </div>
        <div class="payment-from paymentFrom-js">
            <?php
            $frm->setFormTagAttribute('onsubmit', 'doPayment(this, "' . $orderInfo["id"] . '"); return(false);');
            $frm->setFormTagAttribute('class', 'form form--normal');
            ?>
            <?php echo $frm->getFormTag(); ?>
                <div class="row">
                    <div class="col-md-12">
                        <ul class="list-group payment-card payment-card-view">
                            <?php
                            foreach ($savedCards as $cardDetail) { ?>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-auto">
                                            <label class="radio">
                                                <input name="card_id" type="radio" value="<?php echo $cardDetail['id']; ?>" <?php echo $defaultSource == $cardDetail['id'] ? "checked" : ""; ?>>
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                        <div class="col">
                                            <div class="payment-card__photo">
                                                <svg class="svg">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#<?php echo strtolower($cardDetail['brand']); ?>" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#remove">
                                                    </use>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="payment-card__number">
                                                <?php echo Labels::getLabel('LBL_ENDING_IN', $siteLangId); ?>
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
                                                        <a href="javascript:void(0)" onClick="removeCard('<?php echo $cardDetail['id']; ?>');">
                                                            <svg class="svg" width="24px" height="24px">
                                                                <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#remove" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#remove">
                                                                </use>
                                                            </svg>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                        <div class="my-3 text-right">
                            <a class="link-text" href="javascript:void(0);" onclick="addNewCard('<?php echo $orderInfo['id']; ?>')">
                                <i class="icn"> 
                                    <svg class="svg">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#add" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#add">
                                        </use>
                                    </svg> 
                                </i>
                                <?php echo Labels::getLabel('LBL_ADD_NEW_CARD', $siteLangId); ?>
                            </a>
                        </div>
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
                                        echo $frm->getFieldHtml('btn_submit');
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
                <?php } ?>
            </form>
            <?php echo $frm->getExternalJs(); ?>
        </div>
        <div class="d-none paymentIntent-js"></div>
    </div>
</div>
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

<script>
    $(document).on("click", ".selectCard-js", function() {
        var cardId = $(this).data("cardid");
        $("input[name='card_id']").val(cardId);
    });

    (function() {
        var controller = 'StripeConnectPay';
        var paymentForm = '.paymentFrom-js';
        doPayment = function(frm, orderId) {
            if (!$(frm).validate()) return;
            var data = fcom.frmData(frm);
            fcom.ajax(fcom.makeUrl(controller, 'charge', [orderId]), data, function(t) {
                if ('undefined' != typeof t.redirectUrl) {
                    window.location = t.redirectUrl;
                } else {
                    $(".paymentIntent-js").html(t.html);
                }
            });
        };

        addNewCard = function(orderId) {
            $(paymentForm).html(fcom.getLoader());
            fcom.ajax(fcom.makeUrl(controller, 'addCardForm', [orderId]), '', function(t) {
                $(paymentForm).html(t);
            });
        };

        removeCard = function(cardId) {
            if (!confirm(langLbl.confirmDelete)) {
                return false;
            };
            var data = 'cardId=' + cardId;
            fcom.ajax(fcom.makeUrl(controller, 'removeCard', []), data, function(t) {
                t = $.parseJSON(t);
                if (1 > t.status) {
                    $.systemMessage(t.msg, 'alert--danger', false);
                    return false;
                }
                $.systemMessage(t.msg, 'alert--success', false);
                location.reload();
            });
        };
    })();
    $(document).ready(function() {
        <?php if (empty($savedCards)) { ?>
            addNewCard('<?php echo $orderInfo["id"]; ?>');
        <?php } ?>
        $(document).on("click", ".cancelCardForm-js", function(e){
            e.preventDefault();
            location.reload();
        });
    });
</script>