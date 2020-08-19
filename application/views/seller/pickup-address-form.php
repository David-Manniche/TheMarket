<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('id', 'pickupAddressFrm');
$frm->setFormTagAttribute('class', 'form form--horizontal');
$frm->developerTags['colClassPrefix'] = 'col-sm-4 col-md-';
$frm->developerTags['fld_default_col'] = 4;
$frm->setFormTagAttribute('onsubmit', 'setPickupAddress(this); return(false);');

$countryFld = $frm->getField('addr_country_id');
$countryFld->setFieldTagAttribute('id', 'addr_country_id');
$countryFld->setFieldTagAttribute('onChange', 'getCountryStates(this.value,'.$stateId.',\'#addr_state_id\')');

$stateFld = $frm->getField('addr_state_id');
$stateFld->setFieldTagAttribute('id', 'addr_state_id');

$cancelFld = $frm->getField('btn_cancel');
$cancelFld->setFieldTagAttribute('class', 'btn btn-outline-primary btn-block');
$cancelFld->developerTags['col'] = 2;
$cancelFld->developerTags['noCaptionTag'] = true;

$btnSubmit = $frm->getField('btn_submit');
$btnSubmit->setFieldTagAttribute('class', "btn btn-primary btn-block");
$btnSubmit->developerTags['col'] = 2;
$btnSubmit->developerTags['noCaptionTag'] = true;

$variables= array('language'=>$language,'siteLangId'=>$siteLangId,'shop_id'=>$shop_id,'action'=>$action);
$this->includeTemplate('seller/_partial/shop-navigation.php', $variables, false); ?>
<div class="cards">
    <div class="cards-content ">    
        <div class="tabs__content form">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="content-header row">
                        <div class="col">
                            <h5 class="cards-title"><?php echo Labels::getLabel('LBL_Shop_Pickup_Addresses', $siteLangId); ?></h5>
                        </div>
                        <div class="content-header-right col-auto">
                            <div class="btn-group">
                                <a href="javascript:void(0)" onClick="pickupAddress()" class="btn btn-outline-primary btn-sm  btn-sm"><?php echo Labels::getLabel('LBL_Back', $siteLangId);?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <?php echo $frm->getFormHtml();?>
                </div>
            </div>
        </div>        
    </div>
</div>
<script language="javascript">
    $(document).ready(function() {
        getCountryStates($("#addr_country_id").val(), <?php echo ($stateId) ? $stateId : 0 ;?>, '#addr_state_id');
    });
</script>
