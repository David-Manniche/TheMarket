<?php defined('SYSTEM_INIT') or die('Invalid Usage'); ?>
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
        <div class="payment-from container">
            <?php $button_confirm = Labels::getLabel('LBL_CONFIRM', $siteLangId); ?>
            <?php if (!isset($error)) : ?>
                <p><?php echo Labels::getLabel('MSG_CONFIRM_TO_PROCEED_FOR_PAYMENT_?', $siteLangId); ?></p>
                <?php echo $frm->getFormHtml(); ?>
                <div class="gap"></div>
                <input type="submit" onclick="razorpaySubmit(this);" value="<?php echo $button_confirm; ?>" data-processing-text='<?php echo Labels::getLabel('LBL_PLEASE_WAIT..', $siteLangId); ?>' class="btn btn-primary" />
                <?php if (FatUtility::isAjaxCall()) { ?>
                    <a href="javascript:void(0);" onclick="loadPaymentSummary()" class="btn btn-outline-primary">
                        <?php echo Labels::getLabel('LBL_Cancel', $siteLangId); ?>
                    </a>
                <?php } else { ?>
                    <a href="<?php echo $cancelBtnUrl; ?>" class="btn btn-outline-primary"><?php echo Labels::getLabel('LBL_Cancel', $siteLangId); ?></a>
                <?php } ?>
            <?php else : ?>
                <div class="alert alert--danger"><?php echo $error; ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php if (!FatUtility::isAjaxCall()) { ?>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<?php } ?>
<script>
    var razorpay_options = {
        key: "<?php echo $paymentSettings['merchant_key_id']; ?>",
        amount: "<?php echo $paymentAmount * 100; ?>",
        name: "<?php echo $orderInfo["site_system_name"]; ?>",
        description: "<?php echo sprintf(Labels::getLabel('MSG_Order_Payment_Gateway_Description', $siteLangId), $orderInfo["site_system_name"], $orderInfo['invoice']) ?>",
        netbanking: true,
        currency: "<?php echo $systemCurrencyCode; ?>",
        prefill: {
            name: "<?php echo $orderInfo["customer_name"]; ?>",
            email: "<?php echo $orderInfo["customer_email"]; ?>",
            contact: "<?php echo $orderInfo["customer_phone"]; ?>"
        },
        notes: {
            system_order_id: "<?php echo $orderInfo["id"];; ?>"
        },
        handler: function(transaction) {
            document.getElementById('razorpay_payment_id').value = transaction.razorpay_payment_id;
            document.getElementById('razorpay-form').submit();
        }
    };
    var razorpay_submit_btn, razorpay_instance;

    function razorpaySubmit(el) {
        if (typeof Razorpay == 'undefined') {
            setTimeout(razorpaySubmit, 200);
            if (!razorpay_submit_btn && el) {
                razorpay_submit_btn = el;
                el.disabled = true;
                el.value = 'Please wait...';
            }
        } else {
            if (!razorpay_instance) {
                razorpay_instance = new Razorpay(razorpay_options);
                if (razorpay_submit_btn) {
                    razorpay_submit_btn.disabled = false;
                    razorpay_submit_btn.value = "<?php echo $button_confirm; ?>";
                }
            }
            razorpay_instance.open();
        }
    }
</script>