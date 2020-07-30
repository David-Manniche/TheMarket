<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php 
    $btn = $frm->getField('btn_submit'); 
    $btn->addFieldTagAttribute('class', 'btn btn-primary btn-wide');
    $btn->addFieldTagAttribute('data-processing-text', Labels::getLabel('LBL_PLEASE_WAIT..', $siteLangId));
    $cancelBtn = $frm->getField('btn_cancel'); 
    $cancelBtn->addFieldTagAttribute('class', 'btn btn-outline-primary btn-wide');
    $cancelBtn->addFieldTagAttribute('onclick', 'cancel();');
?>
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
            <?php if (!isset($error)) { ?>
                <p>
                    <?php echo Labels::getLabel('MSG_PROCEED_TO_PAYMENT_PAGE_?', $siteLangId); ?>
                </p>
            <?php echo  $frm->getFormHtml();
            } else { ?>
                <div class="alert alert--danger"> <?php echo $error; ?></div>
            <?php } ?>
        </div>
    </div>
</div>
<script>
    function cancel() {
        <?php if (FatUtility::isAjaxCall()) { ?>
            loadPaymentSummary();
        <?php } else { ?>
            location.href = "<?php echo $cancelBtnUrl; ?>";
        <?php } ?>
    }
</script>