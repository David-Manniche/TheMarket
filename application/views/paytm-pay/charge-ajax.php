<?php defined('SYSTEM_INIT') or die('Invalid Usage'); ?>
<?php if (!isset($error)) { ?>
    <p><?php echo Labels::getLabel('MSG_We_are_redirecting_payment_page', $siteLangId) ?>:</p>
    <?php echo $frm->getFormHtml() ?>
<?php } else { ?>
    <div class="alert alert--danger">
        <?php echo $error; ?>
    </div>
<?php } ?>
<script type="text/javascript">
    $(function() {
        setTimeout(function() {
            $('form[name="frmPaytm"]').submit()
        }, 2000);
    });
</script>