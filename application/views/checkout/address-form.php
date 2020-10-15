<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
    $addressFrm->developerTags['fld_default_col'] = 12;
    $addressFrm->developerTags['colClassPrefix'] = 'col-md-';
    $addressFrm->setFormTagAttribute('class', 'form form--normal');
    $addressFrm->setFormTagAttribute('onsubmit', 'setUpAddress(this, '.$addressType.'); return(false);');

    $addr_titleFld = $addressFrm->getField('addr_title');
    $addr_titleFld->developerTags['col'] = 6;

    $ua_nameFld = $addressFrm->getField('addr_name');
    $ua_nameFld->developerTags['col'] = 6;

    $countryFld = $addressFrm->getField('addr_country_id');
    $countryFld->developerTags['col'] = 6;
    $countryFld->setFieldTagAttribute('id','addr_country_id');
    $countryFld->setFieldTagAttribute('onChange','getCountryStates(this.value, 0 ,\'#addr_state_id\')');

    $stateFld = $addressFrm->getField('addr_state_id');
    $stateFld->developerTags['col'] = 6;
    $stateFld->setFieldTagAttribute('id','addr_state_id');

    $zipFld = $addressFrm->getField('addr_zip');
    $zipFld->developerTags['col'] = 6;

    $phoneFld = $addressFrm->getField('addr_phone');
    $phoneFld->developerTags['col'] = 6;

    $submitFld = $addressFrm->getField('btn_submit');
    $submitFld->developerTags['col'] = 2;
    $submitFld->addFieldTagAttribute('class', 'btn btn-brand');

    $cancelFld = $addressFrm->getField('btn_cancel');  
    $cancelFld->developerTags['col'] = 2;  
    $cancelFld->setFieldTagAttribute('class','btn btn-outline-brand');
    $cancelFld->setFieldTagAttribute('onclick','resetAddress('.$addressType.')');
?>
<div class="step active" role="step:2">
    <div class="step__section">
        <div class="step__section__head">
            <h5 class="step__section__head__title"><?php echo Labels::getLabel('LBL_ADDRESS_DETAILS', $siteLangId);?></h5>            
        </div>
    </div>
    <?php echo $addressFrm->getFormHtml(); ?>        
</div>
<script language="javascript">
    $(document).ready(function() {
        getCountryStates($("#addr_country_id").val(), <?php echo $stateId ;?>, '#addr_state_id');
    });
</script>
