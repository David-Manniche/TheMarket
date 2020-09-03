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
                            <input name="pickup_address" <?php echo (($key == 0 && $addrId == 0) || $addrId == $address['addr_id']) ? 'checked=checked': ''; ?> onclick="displayCalendar();" type="radio" value="<?php echo $address['addr_id']; ?>"> 
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
<?php } 
$displayDateformat = FatDate::convertDateFormatFromPhp(
    FatApp::getConfig('CONF_DATE_FORMAT', FatUtility::VAR_STRING, 'Y-m-d'),
    FatDate::FORMAT_JQUERY_UI
); 

?>

<script>
var needToSeeDaysOfWeek = new Array();
$(document).ready(function(){  
    
    $('.js-datepicker').datepicker({
        minDate: new Date(),
        dateFormat: 'yy-mm-dd',
        gotoCurrent : false,
        beforeShowDay: enableDaysWithSlots,
        onSelect: function() {  
            calendarSelectedDate = $.datepicker.formatDate('<?php echo $displayDateformat; ?>', $(this).datepicker('getDate'));
            displayDateSlots(false);
        }
    });
    
    displayCalendar();
});

displayDateSlots = function(displaySlotSelected){
    $('input[name="timeSlot"]').prop("checked", displaySlotSelected);
    var selectedDate = $('.js-datepicker').val();
    var addressId = $('input[name="pickup_address"]:checked').val();
    var level = <?php echo $level; ?>;
    if(addressId != 'undefined' && selectedDate != ''){ 
        var data = 'addressId='+addressId+'&selectedDate='+selectedDate+'&level='+level;
        if(displaySlotSelected == true){
            data = data +'&selectedSlot=<?php echo $slotId;?>';
        }
        fcom.ajax(fcom.makeUrl('Addresses', 'getTimeSlotsByAddressAndDate'), data, function (rsp) {
            $(".js-time-slots").html(rsp);
        });
    }
}
    
displayCalendar = function(){
    var checkedAddrId = $('input[name="pickup_address"]:checked').val(); 
    fcom.updateWithAjax(fcom.makeUrl('Addresses', 'slotDaysByAddr', [checkedAddrId]), '', function (rsp) {
        needToSeeDaysOfWeek.splice(0,needToSeeDaysOfWeek.length);  
        for(i=0; i< rsp.slotDays.length; i++){  
            needToSeeDaysOfWeek.push(rsp.slotDays[i]);
        }
        $('.js-datepicker').datepicker('refresh');
        
        var pickUpAddrId = <?php echo $addrId; ?>;
        if(checkedAddrId == pickUpAddrId){ 
            $('.js-datepicker').datepicker("setDate", new Date("<?php echo $slotDate; ?>"));
            displayDateSlots(true);
        }else{
            $('.js-datepicker').datepicker("setDate", null);
            $(".js-time-slots").html('');
        }
    });
}
    
enableDaysWithSlots = function(date){ 
    var day = date.getDay();   
    for(var i=0;i<needToSeeDaysOfWeek.length;i++){ 
         if(day == needToSeeDaysOfWeek[i]){
                 return [true];
         }
    }   
    return [false];
}

selectTimeSlot = function (ele, level) {
    var slot_id = $(ele).attr('id');
    var slot_date = $('.js-datepicker').val();
    var addr_id = $("input[name='pickup_address']:checked").val();
    $("input[name='slot_id[" + level + "]']").val(slot_id);
    $("input[name='slot_date[" + level + "]']").val(slot_date);
    $(".js-slot-addr-"+level).attr('data-addr-id', addr_id);

    var slot_time = $(ele).next().children('.time').html();
    var addrHtml = $("input[name='pickup_address']:checked").next().next('.js-addr').html();
    var html = "<div>" + addrHtml + "<br/><strong>" + calendarSelectedDate + ' ' + slot_time + "</strong></div>";
    
    $(".js-slot-addr_" + level).html(html);
    $("#facebox .close").trigger('click');
}

</script>