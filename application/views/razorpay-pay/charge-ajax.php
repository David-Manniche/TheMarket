<?php defined('SYSTEM_INIT') or die('Invalid Usage');

$button_confirm = Labels::getLabel('LBL_CONFIRM', $siteLangId);
if (!isset($error)) { ?>
    <p><?php echo Labels::getLabel('MSG_CONFIRM_TO_PROCEED_FOR_PAYMENT_?', $siteLangId); ?></p>
    <?php echo $frm->getFormTag(); ?>
    <?php echo $frm->getFieldHtml('razorpay_payment_id'); ?>
    <?php echo $frm->getFieldHtml('merchant_order_id'); ?>
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
                        $btn->addFieldTagAttribute('onclick', 'razorpaySubmit(this)');
                        $btn->addFieldTagAttribute('class', 'btn btn-secondary');
                        $btn->addFieldTagAttribute('data-processing-text', Labels::getLabel('LBL_PLEASE_WAIT..', $siteLangId));
                        echo $frm->getFieldHtml('btn_submit'); ?>
                        <a href="<?php echo $cancelBtnUrl; ?>" class="btn btn-outline-brand"><?php echo Labels::getLabel('LBL_Cancel', $siteLangId); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
<?php
} else { ?>
    <div class="alert alert--danger"><?php echo $error; ?></div>
<?php }

if (!FatUtility::isAjaxCall()) { ?>
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
            system_order_id: "<?php echo $orderInfo["id"]; ?>"
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