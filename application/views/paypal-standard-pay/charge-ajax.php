<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if (!isset($error)) { ?>
    <p><?php echo Labels::getLabel('MSG_WE_ARE_REDIRECTING_TO_PAYMENT_PAGE', $siteLangId); ?></p>
    <?php echo  $frm->getFormHtml() ?>
<?php } else { ?>
    <div class="alert alert--danger"><?php echo $error ?><div>
<?php } ?>
<script type="text/javascript">
    $(function() {
        setTimeout(function() {
            $('form[name="frmPayPalStandard"]').submit()
        }, 2000);
    });
</script>