$(document).ready(function(){
	changeEmailForm();		
	configurePhoneForm();		
});

(function() {
	var runningAjaxReq = false;
	var dv = '#changeEmailFrmBlock';
	var phoneNumberdv = '#changePhoneFrmBlock';
	
	checkRunningAjax = function(){
		if( runningAjaxReq == true ){
			console.log(runningAjaxMsg);
			return;
		}
		runningAjaxReq = true;
	};
	
	changeEmailForm = function(){				
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('GuestUser', 'changeEmailForm'), '', function(t) {			
			$(dv).html(t);
		});
    };
    
	configurePhoneForm = function(){				
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('GuestUser', 'configurePhoneForm'), '', function(t) {			
            $(phoneNumberdv).html(t);
            stylePhoneNumberFld();
		});
	};
	
	updateEmail = function (frm){
		if (!$(frm).validate()) return;	
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('GuestUser', 'updateEmail'), data, function(t) {						
			changeEmailForm();			
		});	
    };
    
    getOtp = function (frm, updateToDbFrm = 0){
		if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        $.systemMessage(langLbl.processing,'alert--process', false);
		fcom.ajax(fcom.makeUrl( 'Account', 'getOtp', [updateToDbFrm]), data, function(t) {
            try{
				t = $.parseJSON(t);
				if(typeof t.status != 'undefined' &&  1 > t.status){
                    $.systemMessage(t.msg,'alert--danger', false);
                }
                return false;
			}
			catch(exc){
                $.systemMessage.close();
                var lastFormElement = phoneNumberdv + ' form:last';
                var resendOtpElement = lastFormElement + " .resendOtp-js";
                $(lastFormElement + ' [name="btn_submit"]').closest("div.row").remove();
                var dialCode = $(lastFormElement + " input[name='user_dial_code']").val();
                var phoneNumber = $(lastFormElement + " input[name='user_phone']").val();

                $(lastFormElement).after(t);
                $('.formTitle').remove();

                var userId = $(lastFormElement + " input[name='user_id']").val();
                var resendFunction = 'resendOtp(' + userId + ')';
                if (0 < updateToDbFrm) {
                    $(phoneNumberdv + " form").attr('onsubmit', 'return validateOtp(this, 0);');
                    var resendOtpElement = lastFormElement + " .resendOtp-js";
                    resendFunction = 'resendOtp(' + userId + ', "' + dialCode + '","' + phoneNumber + '")';
                }
                $(resendOtpElement).removeAttr('onclick').attr('onclick', resendFunction);
			}
        });
        return false;
    };
    
    resendOtp = function (userId, dialCode = '', phone = ''){
        var postparam = (1 == phone) ? '' : "user_dial_code="+dialCode+"&user_phone=" + phone;
        $.systemMessage(langLbl.processing, 'alert--process', false);
		fcom.ajax(fcom.makeUrl('Account', 'resendOtp', [userId]), postparam, function(t) {
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
			}
        });
        return false;
    };

    validateOtp = function (frm, updateToDbFrm = 1){
		if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        $.systemMessage(langLbl.processing,'alert--process', false);
		fcom.ajax(fcom.makeUrl( 'Account', 'validateOtp', [updateToDbFrm]), data, function(t) {
            try{
				t = $.parseJSON(t);
				if(typeof t.status != 'undefined' &&  1 > t.status){
                    $.systemMessage(t.msg,'alert--danger', false);
                } else {
                    $.systemMessage(t.msg,'alert--success', false);
                    changePhoneNumberForm();
                }
                return false;
			}
			catch(exc){
                $.systemMessage.close();
                location.href = fcom.makeUrl();
			}
        });
        return false;
    };
	
})();