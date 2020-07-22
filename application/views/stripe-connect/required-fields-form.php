<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('onsubmit', 'setupRequiredFields(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 6;

$btnFld = $frm->getField('btn_submit');
$btnFld->addFieldTagAttribute('class', 'btn btn-primary');

$btnFld = $frm->getField('btn_clear');
if (null != $btnFld) {
    $btnFld->addFieldTagAttribute('class', 'btn btn-outline-primary');
    $btnFld->addFieldTagAttribute('onClick', 'clearForm();');
} 

$termFld = $frm->getField('tos_acceptance');
if (null != $termFld) {
    $termFld->addFieldTagAttribute('class', 'tosCheckbox-js');
    $termFld->htmlAfterField = '<a href="' . $termAndConditionsUrl . '" target="_blank" class="tosLink-js">' . Labels::getLabel('LBL_I_AGREE_TO_THE_TERMS_OF_SERVICE', $siteLangId) . '</a>';
}
?>

<hr>
<div class="section__body">
    <?php $this->includeTemplate('stripe-connect/fieldsErrors.php', ['errors' => $errors]); ?>
    <?php echo $frm->getFormHtml(); ?>
</div>
<script language="javascript">
    $(document).ready(function() {
        $(".country").change();
        // getStatesByCountryCode($(".country").val(), '<?php echo $stateCode ;?>', '.state', 'state_code');
        
        if (0 < $(".tosLink-js").length && 0 < $(".tosCheckbox-js").length) {
            $(".tosLink-js").siblings('label').children('span').append(function() {
                return $(this).parent().siblings(".tosLink-js");
            });
        }
    });
</script>