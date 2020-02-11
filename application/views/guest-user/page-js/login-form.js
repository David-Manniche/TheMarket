$(document).on('keyup', 'input.otpVal', function(e){
    var element = '';
   
    /* 
    # e.which = 8(Backspace)
    */
    if (8 != e.which && '' != $(this).val()) {
        element = $(this).parent().nextAll();
    } else {
        element = $(this).parent().prevAll();
    }
    element.children("input.otpVal").eq(0).focus();
});
(function() {
	signUpWithPhone = function() {
        fcom.ajax(fcom.makeUrl( 'GuestUser', 'signUpWithPhone'), '', function(t) {
            $('#sign-up').html(t);
            stylePhoneNumberFld();
		});
    };
    
	signUpWithEmail = function() {
        fcom.ajax(fcom.makeUrl( 'GuestUser', 'signUpWithEmail'), '', function(t) {
            $('#sign-up').html(t);
		});
    };

    registerWithPhone = function (frm){
		if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('GuestUser', 'register'), data, function(t) {
            if (1 == t.status) {
                fcom.ajax(fcom.makeUrl( 'GuestUser', 'otpForm'), '', function(t) {
                    $('#sign-up').html(t);
                });
            }
        });	
        return false;
    };

    validateOtp = function (frm){
		if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('GuestUser', 'validateOtp'), data, function(t) {						
            if (1 == t.status) {
                window.location.href = t.redirectUrl;
            }
        });	
        return false;
    };
    
    resendOtp = function (userId, getOtpOnly = 0){
        $.systemMessage(langLbl.processing,'alert--process', false);
		fcom.ajax(fcom.makeUrl( 'GuestUser', 'resendOtp', [userId, getOtpOnly]), '', function(t) {
            try{
				t = $.parseJSON(t);
				if(typeof t.status != 'undefined' &&  1 > t.status){
                    $.systemMessage(t.msg,'alert--danger', false);
                } else {
                    $.systemMessage(t.msg,'alert--success', false);
                }
                return false;
			}
			catch(exc){
                $.systemMessage.close();
                $('#sign-up').html(t);
			}
        });
        return false;
	};
})();