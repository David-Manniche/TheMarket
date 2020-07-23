<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $frm->setFormTagAttribute('class', 'form form--normal');
$frm->developerTags['colClassPrefix'] = 'col-lg-12 col-md-12 col-sm-';
$frm->developerTags['fld_default_col'] = 12;
$frm->setFormTagAttribute('onsubmit', 'confirmOrder(this); return(false);'); 

$pmethodName = $paymentMethod["plugin_name"];
$pmethodDescription = $paymentMethod["plugin_description"];
$pmethodCode = $paymentMethod["plugin_code"];

$submitFld = $frm->getField('btn_submit');
$submitFld->setFieldTagAttribute('class', "btn btn-primary");
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
