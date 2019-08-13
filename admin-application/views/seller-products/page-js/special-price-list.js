$(document).ready(function(){
});
(function() {
    sellerProductSpecialPriceForm = function( selprod_id, splprice_id ){
		if(typeof splprice_id==undefined || splprice_id == null){
			splprice_id = 0;
		}
		$.facebox(function() {
			fcom.ajax(fcom.makeUrl('SellerProducts', 'sellerProductSpecialPriceForm', [selprod_id, splprice_id ]), '', function(t) {
				$.facebox(t,'faceboxWidth');
			});
		});
	};
    deleteSellerProductSpecialPrice = function( splprice_id ){
		var agree = confirm(langLbl.confirmDelete);
		if( !agree ){ return false; }
		fcom.updateWithAjax(fcom.makeUrl('SellerProducts', 'deleteSellerProductSpecialPrice'), 'splprice_id=' + splprice_id, function(t) {
            location.reload();
		});
	};
    setUpSellerProductSpecialPrice = function(frm){
		if (!$(frm).validate()) return;

		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('SellerProducts', 'setUpSellerProductSpecialPrice'), data, function(t) {
			$(document).trigger('close.facebox');
            location.reload();
		});
		return false;
	};
    updateSpecialPrice = function(){
        if (typeof $(".selectItem--js:checked").val() === 'undefined') {
	        $.systemMessage(langLbl.atleastOneRecord, 'alert--danger');
	        return false;
	    }
		$("#frmSplPriceListing").attr('action', fcom.makeUrl('SellerProducts','addSpecialPrice')).submit();
	};
    removeSpecialPrice = function(){
        if (typeof $(".selectItem--js:checked").val() === 'undefined') {
	        $.systemMessage(langLbl.atleastOneRecord, 'alert--danger');
	        return false;
	    }
        var agree = confirm(langLbl.confirmDelete);
		if( !agree ){ return false; }
        var data = fcom.frmData(document.getElementById('frmSplPriceListing'));
        fcom.ajax(fcom.makeUrl('SellerProducts', 'removeSpecialPriceArr'), data, function(t) {
            var ans = $.parseJSON(t);
            if( ans.status == 1 ){
                fcom.displaySuccessMessage(ans.msg);
            } else {
                fcom.displayErrorMessage(ans.msg);
            }
            location.reload();
        });
	};
})();
