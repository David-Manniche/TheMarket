$(document).ready(function() {
    searchAddresses();
});

(function() {
    var dv = '#listing';

    searchAddresses = function() {
        var data = '';
        $(dv).html(fcom.getLoader());
        fcom.ajax(fcom.makeUrl('PickupAddresses', 'search'), data, function(res) {
            $(dv).html(res);
            $(".js-pickup-addr").addClass('d-none');
            $(".js-add-pickup-addr").removeClass('d-none');
        });
    };
    
    addAddressForm = function(id, langId) {
        var data = 'langId='+langId;
        fcom.ajax(fcom.makeUrl('PickupAddresses', 'form', [id, langId]), data, function(res) {
            $(dv).html(res);
            $(".js-add-pickup-addr").addClass('d-none');
            $(".js-pickup-addr").removeClass('d-none');
			setTimeout(function(){$('.fromTime-js').change();}, 500);
        });

    };
    
    setup= function(frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('PickupAddresses', 'setup'), data, function(t) {
            searchAddresses();
        });
    };
    
    deleteRecord = function(id){
		if(!confirm(langLbl.confirmDelete)){return;}
		data='id='+id;
		fcom.updateWithAjax(fcom.makeUrl('PickupAddresses','deleteRecord'),data,function(res){
			searchAddresses();
		});
	};
    
    getCountryStates = function(countryId, stateId, div, langId){
		fcom.ajax(fcom.makeUrl('Shops','getStates',[countryId,stateId, langId]),'',function(res){
			$(div).empty();
			$(div).append(res);
		});
	};

    
    addTimeSlotRow = function(day){
        var fromTimeHtml = $(".js-from_time_"+day).html();
        var toTimeHtml = $(".js-to_time_"+day).html();
        var html = "<div class='row js-added-rows-"+day+"'><div class='col-md-2'></div><div class='col-md-4 js-from_time_"+day+"'>"+fromTimeHtml+"</div><div class='col-md-4 js-to_time_"+day+"'>"+toTimeHtml+"</div><div class='col-md-2'><input class='mt-4' type='button' name='btn_remove_row' value='x'></div></div>";
        $(".js-from_time_"+day).last().parent().after(html);
        $('.js-slot-from-'+day).last().val('');
        $('.js-slot-to-'+day).last().val('');
    }  
    
    displayFields = function(day, ele){
       if($(ele).prop("checked") == true){
            $(".js-slot-from-"+day).removeAttr('disabled');
            $(".js-slot-to-"+day).removeAttr('disabled');        
            displayAddRowField(day);
       }else{
            $(".js-slot-from-"+day).attr('disabled', 'true');
            $(".js-slot-to-"+day).attr('disabled', 'true');
            $(".js-slot-add-"+day).addClass('d-none');
            $(".js-added-rows-"+day).remove();
       }  
    }
    
    displayAddRowField = function(day){
        var from_time = $(".js-slot-from-"+day).children("option:selected").val();
        var to_time = $(".js-slot-to-"+day).children("option:selected").val();
		
        if(to_time != '' && to_time <= from_time){
            $(".js-slot-to-"+day).val('').addClass('error');
            var to_time = $(".js-slot-to-"+day).children("option:selected").val();
        }else{
            $(".js-slot-to-"+day).removeClass('error');
        }
		
		$(".js-slot-to-"+day+" option").removeClass('d-none');
		$(".js-slot-to-"+day+" option").each(function(){
			var toVal = $(this).val();
			if(toVal != '' && toVal <= from_time){
				$(this).addClass('d-none');
			}
		});
        
        if(from_time != ''  && to_time != ''){
            $(".js-slot-add-"+day).removeClass('d-none');
        }else{
            $(".js-slot-add-"+day).addClass('d-none');
        }        

    }
    
    displaySlotTimings = function(ele){
        var selectedVal = $(ele).val(); 
        if(selectedVal == 2){
            $('.js-slot-individual').addClass('d-none');
            $('.js-slot-all').removeClass('d-none');
        }else{
            $('.js-slot-all').addClass('d-none');
            $('.js-slot-individual').removeClass('d-none');
        }
    }
    
    validateTimeFields = function(){
        var from_time = $("[name='tslot_from_all']").children("option:selected").val();
        var to_time = $("[name='tslot_to_all']").children("option:selected").val();
		
		$("[name='tslot_to_all'] option").removeClass('d-none');
		$("[name='tslot_to_all'] option").each(function(){
			var toVal = $(this).val();
			if(toVal != '' && toVal <= from_time){
				$(this).addClass('d-none');
			}
		});
		
        if(to_time != '' && to_time <= from_time){
            $("[name='tslot_to_all']").val('').addClass('error');
        }else{
            $("[name='tslot_to_all']").removeClass('error');
        }
    }
    
})();

$(document).on("click", "[name='btn_remove_row']", function(){
    $(this).parent().parent('.row').remove();
})
