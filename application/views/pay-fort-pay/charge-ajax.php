<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$btn = $frm->getField('btn_submit');
$btn->addFieldTagAttribute('class', 'btn btn-secondary');
$btn->addFieldTagAttribute('data-processing-text', Labels::getLabel('LBL_PLEASE_WAIT..', $siteLangId));
$cancelBtn = $frm->getField('btn_cancel');
$cancelBtn->addFieldTagAttribute('class', 'btn btn-outline-brand');
$cancelBtn->addFieldTagAttribute('onclick', 'cancel();');

if (!isset($error)) { ?>
    <p><?php echo Labels::getLabel('MSG_CONFIRM_TO_PROCEED_FOR_PAYMENT_?', $siteLangId); ?></p>
    <?php echo  $frm->getFormHtml();
} else { ?>
    <div class="alert alert--danger"> <?php echo $error; ?></div>
<?php } ?>
<script>
    function cancel() {
        location.href = "<?php echo $cancelBtnUrl; ?>";
    }
</script>