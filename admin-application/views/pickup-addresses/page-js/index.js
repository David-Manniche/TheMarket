$(document).ready(function() {
    searchAddresses();
});


(function() {
    var runningAjaxReq = false;
    var dv = '#listing';

    searchAddresses = function() {
        var data = '';
        $(dv).html(fcom.getLoader());
        fcom.ajax(fcom.makeUrl('pickupAddresses', 'search'), data, function(res) {
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
        fcom.ajax(fcom.makeUrl('pickupAddresses', 'form', [id, langId]), '', function(t) {
            $.facebox(t, 'faceboxWidth');
            fcom.updateFaceboxContent(t);
        });
    };

    setup= function(frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('pickupAddresses', 'setup'), data, function(t) {
            searchAddresses();
            if (t.langId > 0) {
                editAddressLangForm(t.addressId, t.langId);
                return;
            }
            $(document).trigger('close.facebox');
        });
    };
    
    deleteRecord = function(id){
		if(!confirm(langLbl.confirmDelete)){return;}
		data='id='+id;
		fcom.updateWithAjax(fcom.makeUrl('pickupAddresses','deleteRecord'),data,function(res){
			searchAddresses();
		});
	};
    
    getCountryStates = function(countryId, stateId, div, langId){
		fcom.ajax(fcom.makeUrl('Shops','getStates',[countryId,stateId, langId]),'',function(res){
			$(div).empty();
			$(div).append(res);
		});
	};

})();
