<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $frm->setFormTagAttribute('class', 'form form--normal');
$frm->developerTags['colClassPrefix'] = 'col-lg-12 col-md-12 col-sm-';
$frm->developerTags['fld_default_col'] = 12;
$frm->setFormTagAttribute('onsubmit', 'confirmOrder(this); return(false);'); 

$pmethodName = isset($paymentMethod["plugin_name"]) ? $paymentMethod["plugin_name"] : $paymentMethod["pmethod_name"];
$pmethodDescription = isset($paymentMethod["plugin_description"]) ? $paymentMethod["plugin_description"] : $paymentMethod["pmethod_description"];
$pmethodCode = isset($paymentMethod["plugin_code"]) ? $paymentMethod["plugin_code"] : $paymentMethod["pmethod_code"];
?>
<div class="">
    <p><strong><?php echo sprintf(Labels::getLabel('LBL_Pay_using_Payment_Method', $siteLangId), $pmethodName)?>:</strong></p><br />
    <p><?php echo $pmethodDescription; ?></p><br />
    <?php if (!isset($error)) {
        echo $frm->getFormHtml();
    }
    ?>
</div>
<script type="text/javascript">
    $("document").ready(function() {
        <?php if (isset($error)) { ?>
        $.systemMessage(<?php echo $error; ?>);
        <?php } ?>
    });

    function confirmOrder(frm) {
        var data = fcom.frmData(frm);
        var action = $(frm).attr('action')
        fcom.updateWithAjax(fcom.makeUrl('Checkout', 'ConfirmOrder'), data, function(ans) {
            $(location).attr("href", action);
        });
    }
</script>
<?php 
$siteKey = FatApp::getConfig('CONF_RECAPTCHA_SITEKEY', FatUtility::VAR_STRING, '');
$secretKey = FatApp::getConfig('CONF_RECAPTCHA_SECRETKEY', FatUtility::VAR_STRING, '');
if (!empty($siteKey) && !empty($secretKey) && 'cashondelivery' == strtolower($pmethodCode)) {?>
    <script src='https://www.google.com/recaptcha/api.js?render=<?php echo $siteKey; ?>'></script>
    <script>
        googleCaptcha();
    </script>
<?php } ?>
