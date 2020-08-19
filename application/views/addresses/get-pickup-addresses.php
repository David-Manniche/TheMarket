<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
if(!empty($addresses)){
?>

        <div class="pop-up-title"><?php echo Labels::getLabel('LBL_Pick_Up', $siteLangId); ?></div>
        <div class="pick-section">
            <div class="pickup-option">
                <ul class="pickup-option__list">
                    <?php foreach($addresses as $key=>$address) { ?>
                    <li>
                        <label class="radio">
                            <input name="pickup_address" <?php echo ($key == 0) ? 'checked=checked': ''; ?> onclick="displayDateSlots();" type="radio" value="<?php echo $address['addr_id']; ?>"> 
                            <i class="input-helper"></i> 
                            <span class="lb-txt js-addr">  
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
                        <div class="js-datepicker calendar-pickup"></div>
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
    $('.js-datepicker').datepicker({
        minDate: new Date(),
        onSelect: function() {
            displayDateSlots();
        }
    }).datepicker("show");

    displayDateSlots = function(){
        var selectedDate = $('.js-datepicker').val();
        var addressId = $('input[name="pickup_address"]:checked').val();
        if(addressId != 'undefined' && selectedDate != ''){ 
            var data = 'addressId='+addressId+'&selectedDate='+selectedDate+'&level='+level;
            fcom.ajax(fcom.makeUrl('Addresses', 'getTimeSlotsByAddressAndDate'), data, function (rsp) {
                $(".js-time-slots").html(rsp);
            });
        }
    }
    
    displayDateSlots(); 
});

</script>