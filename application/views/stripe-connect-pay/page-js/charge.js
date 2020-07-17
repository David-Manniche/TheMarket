$(document).on("click", ".selectCard-js", function () {
    var cardId = $(this).data("cardid");
    $("input[name='card_id']").val(cardId);
});

(function() {
    var controller = 'StripeConnectPay';
    var paymentForm = '.payment-from';
    doPayment = function (frm, orderId){
		if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl(controller, 'charge', [orderId]), data, function(t) {
			if(t.redirectUrl){
				window.location = t.redirectUrl;
			}
		});
    };
    
    addNewCard = function (orderId){
        $(paymentForm).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl(controller, 'addCardForm', [orderId]), '', function(t) {
			$(paymentForm).html(t);
		});
    };
    
    removeCard = function (cardId){
        if( !confirm( langLbl.confirmDelete ) ){ return false; };
        var data = 'cardId=' + cardId; 
		fcom.ajax(fcom.makeUrl(controller, 'removeCard', []), data, function(t) {
            t = $.parseJSON(t);
            if(1 > t.status){
                $.systemMessage(t.msg,'alert--danger', false);
                return false;
            }
            $.systemMessage(t.msg,'alert--success', false);
            location.reload();
		});
	};
})();