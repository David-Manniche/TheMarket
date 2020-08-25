$(document).ready(function(){
	$(".trackingDiv-js").hide();		
	
	$("select[name='op_status_id']").change(function(){
		var data = 'val='+$(this).val();
		fcom.ajax(fcom.makeUrl('Seller', 'checkIsShippingMode'), data, function(t) {			
			var response = $.parseJSON(t);
			if (response["shipping"]){
				$(".trackingDiv-js").show();				
			}else{
				$(".trackingDiv-js").hide();				
			}			
		});
	});
});

(function() {
	updateStatus = function(frm){
        if ( !$(frm).validate() ) return;
        var op_id = $(frm.op_id).val();	
        var manualShipping = 0;
        var orderStatusId = $(frm.op_status_id).val();
        if (0 < $("input.manualShipping-js").length) {
            manualShipping = $("input.manualShipping-js:checked").val();	
        }
        
        var data = fcom.frmData(frm);
        if (0 < canShipByPlugin && 1 != manualShipping && orderShippedStatus == orderStatusId) {
            proceedToShipment(op_id);
        } else {
            fcom.updateWithAjax(fcom.makeUrl('Seller', 'changeOrderStatus'), data, function(t) {
                setTimeout("pageRedirect("+op_id+")", 1000);
            });
        }
	};	
    
    trackOrder = function(trackingNumber, courier){
        $.facebox(function() {
            fcom.ajax(fcom.makeUrl('Seller','orderTrackingInfo', [trackingNumber, courier]), '', function(res){
                $.facebox( res,'faceboxWidth');
            });
        });
    };

    /* ShipStation */
    generateLabel = function (orderId, opId) {
        fcom.updateWithAjax(fcom.makeUrl('ShippingServices', 'generateLabel', [orderId, opId]), '', function (t) {
            window.location.reload();
        });
    }

    proceedToShipment = function (opId) {
        $.mbsmessage(langLbl.processing, false,'alert--process');
        if ('' == $(".shippingUser-js").val()){
            $.mbsmessage(langLbl.shippingUser, false,'alert--danger');
            return;
        }
        fcom.ajax(fcom.makeUrl('ShippingServices', 'proceedToShipment', [opId]), '', function (t) {
            $.mbsmessage.close();
            t = $.parseJSON(t);
            $.mbsmessage(t.msg, false, 'alert--success');
            if(1 > t.status){
                $.mbsmessage(t.msg, false, 'alert--danger');
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

function pageRedirect(op_id) {
	window.location.replace(fcom.makeUrl('Seller', 'viewOrder',[op_id]));
}