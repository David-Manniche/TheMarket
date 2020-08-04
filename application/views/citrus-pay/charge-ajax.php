<?php defined('SYSTEM_INIT') or die('Invalid Usage');
if (!isset($error)) { ?>
    <p><?php echo Labels::getLabel('LBL_We_are_redirecting_payment_page', $siteLangId) ?>:</p>
    <?php echo  $frm->getFormHtml() ?>
<?php } else { ?>
    <div class="alert alert--danger"><?php echo $error ?></div>
<?php } ?>
<script type="text/javascript">
    $(function() {
        setTimeout(function() {
            $('form[name="frm-citrus-payment"]').submit()
        }, 2000);
    })
</script>