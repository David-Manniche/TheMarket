<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    var stripe = Stripe("<?php echo $publishableKey; ?>");
    connectStripeCheckout(stripe, "<?php echo $sessionId; ?>");
</script>