<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
if(!empty($addresses)){
    
    $frm->setFormTagAttribute('class', 'form');
    $frm->developerTags['colClassPrefix'] = 'col-md-';
    $frm->developerTags['fld_default_col'] = 12;

    $dateFld = $frm->getField('slot_date');
    $dateFld->setFieldTagAttribute('class', 'js-datepicker');
    $dateFld->setFieldTagAttribute('readonly', 'readonly');
    $dateFld->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Choose_Time_Slot_Date', $siteLangId));
    
?>

        <div class="pop-up-title"><?php echo Labels::getLabel('LBL_Pick_Up', $siteLangId); ?></div>
        <div class="pick-section">
            <div class="pickup-option">
                <ul class="pickup-option__list">
                    <?php foreach($addresses as $address) { ?>
                    <li class="">
                        <label class="radio">
                            <input name="pickup_address" onclick="hidedateAndSlots();" type="radio" value="<?php echo $address['addr_id']; ?>"> 
                            <i class="input-helper"></i> 
                            <span class="lb-txt">  
                                <?php echo $address['addr_address1']; ?>
                                <?php echo (strlen($address['addr_address2'])>0)? ", ".$address['addr_address2']:''; ?><br> 
                                <?php echo (strlen($address['addr_city'])>0)?$address['addr_city'].',':''; ?> 
                                <?php echo (strlen($address['state_name'])>0)?$address['state_name'].',':''; ?>
                                <?php echo (strlen($address['country_name'])>0)?$address['country_name'].'<br>':''; ?> 
                                <?php echo (strlen($address['addr_zip'])>0) ? Labels::getLabel('LBL_Zip:', $siteLangId).$address['addr_zip'].',':''; ?>
                                <?php echo (strlen($address['addr_phone'])>0) ? Labels::getLabel('LBL_Phone:', $siteLangId).$address['addr_phone']:''; ?>
                            </span>
                        </label>
                    </li>
                    <?php } ?>
                </ul>

                <div class="pickup-time">
                    <div class="calendar">
                    <?php echo $frm->getFormHtml(); ?>
                    </div>
                    <ul class="time-slot js-time-slots">
                    </ul>
                </div>
            </div>
        </div>
 
<?php }else{ ?>
<h5 class="step-title"><?php echo Labels::getLabel('LBL_No_Pick_Up_address_added', $siteLangId); ?></h5>
<?php } ?>

<script>
$(document).ready(function(){
    var level = <?php echo $level; ?>;
    $('.js-datepicker').datepicker('option', {
        minDate: new Date(),
        onSelect: function() {
            var selectedDate = $(this).val(); 
            var addressId = $('input[name="pickup_address"]:checked').val();
            if(addressId != 'undefined' && selectedDate != ''){
                var data = 'addressId='+addressId+'&selectedDate='+selectedDate+'&level='+level;
                fcom.ajax(fcom.makeUrl('Addresses', 'timeSlotsByAddressIdAndDate'), data, function (rsp) {
                    $(".js-time-slots").html(rsp);
                });
            }
        }
    });
    
    hidedateAndSlots = function(){
        $('.js-datepicker').val('');
        $('.js-time-slots').html('');
    }
    
});

</script>