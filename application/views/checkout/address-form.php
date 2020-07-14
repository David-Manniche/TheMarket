<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
    $addressFrm->developerTags['fld_default_col'] = 12;
    $addressFrm->developerTags['colClassPrefix'] = 'col-md-';
    $addressFrm->setFormTagAttribute('class', 'form form--normal');
    $addressFrm->setFormTagAttribute('onsubmit', 'setUpAddress(this); return(false);');

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
    $cancelFld = $addressFrm->getField('btn_cancel');
    $cancelFld->setFieldTagAttribute('class','btn btn-outline-primary');
    $cancelFld->setFieldTagAttribute('onclick','resetAddress()');
?>
<div class="section-head">
	<div class="section__heading">
		<h2><?php
        $heading = Labels::getLabel('LBL_Billing_Address', $siteLangId);
        if ($cartHasPhysicalProduct) {
            $heading = Labels::getLabel('LBL_Billing/Delivery_Address', $siteLangId);
        }
        echo $heading; ?></h2>
	</div>
</div>
<div class="box box--white box--radius p-4">
    <section id="billing" class="section-checkout">
        <div class="section-head">
    		<div class="section__heading">
    			<h6><?php echo $labelHeading; ?></h6>
    		</div>
    	</div>
    </section>
    <?php echo $addressFrm->getFormHtml(); ?>
</div>
<script language="javascript">
    $(document).ready(function() {
        getCountryStates($("#addr_country_id").val(), <?php echo $stateId ;?>, '#addr_state_id');
    });
</script>
