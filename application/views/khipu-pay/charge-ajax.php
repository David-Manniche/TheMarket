<?php defined('SYSTEM_INIT') or die('Invalid Usage');
if (!isset($error)) : ?>
    <?php echo  $frm->getFormHtml(); ?>
<?php else : ?>
    <div class="alert alert--danger"><?php echo $error ?></div>
<?php endif; ?>
<div id="ajax_message"></div>
<script type="text/javascript">
    window.onload = function() {
        document.forms['frmPaymentForm'].submit();
    }
</script>