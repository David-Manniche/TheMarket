$(document).ready(function() {
    searchAddresses();
});


(function() {
    var runningAjaxReq = false;
    var dv = '#listing';

    searchAddresses = function() {
        var data = '';
        $(dv).html(fcom.getLoader());
        fcom.ajax(fcom.makeUrl('PickupAddresses', 'search'), data, function(res) {
            $(dv).html(res);
        });
    };
    
    addAddressForm = function(id, langId) {
        $.facebox(function() {
            addressForm(id, langId);
        });

    };

    addressForm = function(id, langId) {
        fcom.displayProcessing();
        fcom.ajax(fcom.makeUrl('PickupAddresses', 'form', [id, langId]), '', function(t) {
            $.facebox(t, 'faceboxWidth');
            fcom.updateFaceboxContent(t);
        });
    };

    setup= function(frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('PickupAddresses', 'setup'), data, function(t) {
            searchAddresses();
//            if (t.langId > 0) {
//                editAddressLangForm(t.addressId, t.langId);
//                return;
//            }
            $(document).trigger('close.facebox');
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
    
    addTimeSlots = function(addressId){
        $.facebox(function() {
            fcom.ajax(fcom.makeUrl('PickupAddresses', 'timeSlotForm', [addressId]), '', function(ans) {
                $.facebox(ans, 'faceboxWidth');
            });
        });
    }
    
    setUpTimeSlot= function(frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('PickupAddresses', 'setUpTimeSlot'), data, function(t) {
            searchAddresses();
            $(document).trigger('close.facebox');
        });
    };
    
    addTimeSlotRow = function(){
        var row  = $( "#frm_fat_id_frmTimeSlot .row" ).first().clone().find("select").val("").end();
        $( "#frm_fat_id_frmTimeSlot .row" ).last().before(row);     
        $( "#frm_fat_id_frmTimeSlot .js-to-time" ).last().after('<div class="col-md-2"><button class="js-remove-slot">x</button></div>');
    }  
    
})();

$(document).on('click', '.js-remove-slot', function(){
    $(this).parent().parent('.row').remove();
})
