$(document).ready(function(){
	
	$(".div_tracking_number").hide();		
	
	$("select[name='op_status_id']").change(function(){
		var data = 'val='+$(this).val();
		fcom.ajax(fcom.makeUrl('SellerOrders', 'checkIsShippingMode'), data, function(t) {			
			var response = $.parseJSON(t);
			if (response["shipping"]){
				$(".div_tracking_number").show();				
			}else{
				$(".div_tracking_number").hide();				
			}			
		});
	});
	
	$(document).on('click','ul.linksvertical li a.redirect--js',function(event){
		event.stopPropagation();
	});		
	
});
function pageRedirect(op_id) {
	window.location.replace(fcom.makeUrl('SellerOrders', 'view',[op_id]));
}
(function() {
	updateStatus = function(frm){		
		if (!$(frm).validate()) return;
		var op_id = $(frm.op_id).val();		
        var data = fcom.frmData(frm);

        if ('' == $(".shippingUser-js").val()){
            $.systemMessage(langLbl.shippingUser,'alert--danger', false);
            return;
        }

        if (0 < canShipByPlugin) {
            proceedToShipment(op_id);
        } else {
            fcom.updateWithAjax(fcom.makeUrl('SellerOrders', 'changeOrderStatus'), data, function(t) {
                setTimeout("pageRedirect("+op_id+")", 1000);
            });
        }
	};	
	
	updateShippingCompany = function(frm){
		var data = fcom.frmData(frm);	
		var op_id = $(frm.op_id).val();				
		if (!$(frm).validate()) return;
		fcom.updateWithAjax(fcom.makeUrl('SellerOrders', 'updateShippingCompany'), data, function(t) {
			setTimeout("pageRedirect("+op_id+")", 1000);
		});
    };
    
    /* ShipStation */
    generateLabel = function (orderId, opId) {
        fcom.updateWithAjax(fcom.makeUrl('ShippingServices', 'generateLabel', [orderId, opId]), '', function (t) {
            window.location.reload();
        });
    }

    proceedToShipment = function (opId) {
        $.systemMessage(langLbl.processing,'alert--process', false);
        if ('' == $(".shippingUser-js").val()){
            $.systemMessage(langLbl.shippingUser,'alert--danger', false);
            return;
        }
        fcom.ajax(fcom.makeUrl('ShippingServices', 'proceedToShipment', [opId]), '', function (t) {
            $.systemMessage.close();
            t = $.parseJSON(t);
            $.systemMessage(t.msg, 'alert--success', false);
            if(1 > t.status){
                $.systemMessage(t.msg, 'alert--danger', false);
                return;
            }

            var form = "form.markAsShipped-js";
            if (0 < $(form).length) {
                $(form + " .status-js").val(orderShippedStatus).change();
                $(form + " .notifyCustomer-js").val(1);
                canShipByPlugin = 0;
                setTimeout(function(){ $(form).submit(); }, 200);
            } else {
                window.location.reload();
            }
        });
    }
    /* ShipStation */
})();