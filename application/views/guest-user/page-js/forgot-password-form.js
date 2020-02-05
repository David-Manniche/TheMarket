(function() {
	forgot = function(frm, v) {
		v.validate();
		if (!v.isValid()) return;		
		fcom.updateWithAjax(fcom.makeUrl('GuestUser', 'forgotPassword'), fcom.frmData(frm), function(t) {
			if( t.status == 1){
				location.href = fcom.makeUrl('GuestUser', 'loginForm');
			}else{
				$.systemMessage(t.msg,'alert--danger');				
			}
			$.mbsmessage.close();
			return;
		});
    };
    forgotPwdForm = function(withPhone = 0) {
        fcom.ajax(fcom.makeUrl( 'GuestUser', 'forgotPasswordForm', [withPhone, 0]), '', function(t) {
            $('.forgotPwForm').html(t);
		});
    };

    getOtpForm = function (frm){
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        $.systemMessage(langLbl.processing,'alert--process', false);
		fcom.ajax(frm.action, data, function(t) {
            try{
				t = $.parseJSON(t);
				if(typeof t.status != 'undefined' &&  1 > t.status){
                    $.systemMessage(t.msg,'alert--danger', false);
                    return false;
                }
			}
			catch(exc){
                $('#otpFom').html(t);
                $.systemMessage.close();
			}
        });
        return false;
	};
})();