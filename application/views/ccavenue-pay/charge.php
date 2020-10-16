<?php defined('SYSTEM_INIT') or die('Invalid Usage'); ?>
<script>
    window.onload = function() {
        var d = new Date().getTime();
        if (document.getElementById("tid")) {
            document.getElementById("tid").value = d;
        }
    };
</script>
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
            <?php if (!isset($error)) : ?>
                <p><?php echo Labels::getLabel('LBL_WE_ARE_REDIRECTING_PAYMENT_PAGE', $siteLangId) ?>:</p>
                <?php echo $frm->getFormHtml() ?>
            <?php else : ?>
                <div class="alert alert--danger"><?php echo $error ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        setTimeout(function() {
            $('form[name="frm-ccavenue"]').submit()
        }, 2000);
    });
</script>