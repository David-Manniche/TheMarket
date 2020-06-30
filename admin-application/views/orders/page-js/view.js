$(document).ready(function(){
	$(document).on('click','ul.linksvertical li a.redirect--js',function(event){
		event.stopPropagation();
	});	
});
(function() {
	updatePayment = function (frm){
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Orders', 'updatePayment'), data, function(t) {			
			window.location.reload();
		});
	};

	generateLabel = function (orderId, opId) {
		fcom.updateWithAjax(fcom.makeUrl('ShippingServices', 'generateLabel', [orderId, opId]), '', function(t) {			
			window.location.reload();
		});
	}
})();