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
								<p><?php echo $address['addr_name'] ;?> 
								<p><?php echo $address['addr_address1'] ;?> 
								<?php if(strlen($address['addr_address2']) > 0) { 
									echo ", ".$address['addr_address2'] ;?> 
								<?php } ?>
								</p>   
								<p><?php echo $address['addr_city'].", ".$address['state_name'] ;?></p>    
								<p><?php echo $address['country_name'].", ".$address['addr_zip'] ;?></p>    
								<?php if(strlen($address['addr_phone']) > 0) { ?>
								<p class="phone-txt"><i class="fas fa-mobile-alt"></i><?php echo $address['addr_phone'] ;?></p>    
								<?php } ?>
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
        beforeShowDay: availableDates,
        onSelect: function() {  
            calendarSelectedDate = $.datepicker.formatDate('<?php echo $displayDateformat; ?>', $(this).datepicker('getDate'));
            displayDateSlots(false);
        }
    });
    
    displayCalendar();
});
    
displayCalendar = function(){
    var checkedAddrId = $('input[name="pickup_address"]:checked').val(); 
    //fcom.updateWithAjax(fcom.makeUrl('Addresses', 'slotDaysByAddr', [checkedAddrId]), '', function (rsp) {
    fcom.ajax(fcom.makeUrl('Addresses', 'slotDaysByAddr', [checkedAddrId]), '', function (rslt) {
        var rsp = JSON.parse(rslt); 
        if (rsp.status == 1) {
            needToSeeDaysOfWeek.splice(0,needToSeeDaysOfWeek.length); 
            $.each(rsp.slotDays, function( index, value ) {
                needToSeeDaysOfWeek.push(value);
            });
            $('.js-datepicker').datepicker('refresh');

            var pickUpAddrId = <?php echo $addrId; ?>;
            if(checkedAddrId == pickUpAddrId){ 
                $('.js-datepicker').datepicker("setDate", new Date("<?php echo $slotDate; ?>"));
                displayDateSlots(true);
            }else{ 
                if(rsp.activeDate == ''){
                    $(".js-time-slots").html('');
                    $('.js-datepicker').datepicker("setDate", null);
                }else{
                    $('.js-datepicker').datepicker('option', 'minDate', rsp.activeDate); 
                    $('.js-datepicker').datepicker("setDate", new Date(rsp.activeDate));
                    displayDateSlots(false);
                }
            }
        }
    });
}

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

availableDates = function(date){ 
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
    var html = addrHtml + '<p class="time-txt"><i class="fas fa-calendar-day"></i>'+calendarSelectedDate+' '+slot_time+'</p>';
    
    $(".js-slot-addr_" + level).html(html);
    $("#facebox .close").trigger('click');
}

</script>