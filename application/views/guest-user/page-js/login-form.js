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
                    startOtpInterval();
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
        $.mbsmessage(langLbl.processing, false, 'alert--process');
		fcom.ajax(fcom.makeUrl( 'GuestUser', 'resendOtp', [userId, getOtpOnly]), '', function(t) {
            t = $.parseJSON(t);
            if(typeof t.status != 'undefined' &&  1 > t.status){
                $.mbsmessage(t.msg, false, 'alert--danger');
                return false
            }
            $.mbsmessage(t.msg, false, 'alert--success');
            var parent = 0 < $('#facebox .loginpopup').length ? '.loginpopup' : '';
            startOtpInterval(parent);
        });
        return false;
	};
})();