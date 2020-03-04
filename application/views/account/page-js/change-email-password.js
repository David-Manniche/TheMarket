$(document).ready(function(){
	changePasswordForm();
	changeEmailForm();
    changePhoneNumberForm();
});

(function() {
	var runningAjaxReq = false;
	var passdv = '#changePassFrmBlock';
	var emaildv = '#changeEmailFrmBlock';
	var phoneNumberdv = '#changePhoneNumberFrmBlock';

	checkRunningAjax = function(){
		if( runningAjaxReq == true ){
			console.log(runningAjaxMsg);
			return;
		}
		runningAjaxReq = true;
	};

	changePasswordForm = function(){
		$(passdv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Account', 'changePasswordForm'), '', function(t) {
			$(passdv).html(t);
		});
	};

	changeEmailForm = function(){
		$(emaildv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Account', 'changeEmailForm'), '', function(t) {
			$(emaildv).html(t);
		});
    };
    
	updatePassword = function (frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Account', 'updatePassword'), data, function(t) {
			changePasswordForm();
		});
	};

	updateEmail = function (frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Account', 'updateEmail'), data, function(t) {
			changeEmailForm();
		});
    };

    changePhoneNumberForm = function(){
		$(phoneNumberdv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Account', 'changePhoneForm'), '', function(t) {
            $(phoneNumberdv).html(t);            
            stylePhoneNumberFld();
		});
	};
    
	getOtp = function (frm, updateToDbFrm = 0){
		if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        $(frm.btn_submit).attr('disabled', 'disabled');
        $.systemMessage(langLbl.processing,'alert--process', false);
		fcom.ajax(fcom.makeUrl( 'Account', 'getOtp', [updateToDbFrm]), data, function(t) {                  
            $.systemMessage.close();
            var lastFormElement = phoneNumberdv + ' form:last';
            var resendOtpElement = lastFormElement + " .resendOtp-js";
            $(lastFormElement + ' [name="btn_submit"]').closest("div.row").remove();
            var countryIso = $(lastFormElement + " input[name='user_country_iso']").val();
            var dialCode = $(lastFormElement + " input[name='user_dial_code']").val();
            var phoneNumber = $(lastFormElement + " input[name='user_phone']").val();
            
            if (0 < updateToDbFrm) {
                $(lastFormElement + " input[name='user_phone']").attr('readonly', 'readonly');
            }
            $(lastFormElement).after(t);
            $(".otpForm-js .form-side").removeClass('form-side');
            $('.formTitle').remove();

            var userId = $(lastFormElement + " input[name='user_id']").val();
            var resendFunction = 'resendOtp(' + userId + ')';
            if (0 < updateToDbFrm) {
                $(phoneNumberdv + " form:last").attr('onsubmit', 'return validateOtp(this, 0);');

                var resendOtpElement = lastFormElement + " .resendOtp-js";
                resendFunction = 'resendOtp(' + userId + ', "' + countryIso + '", "' + dialCode + '","' + phoneNumber + '")';
            }
            $(resendOtpElement).removeAttr('onclick').attr('onclick', resendFunction);
            startOtpInterval();
        });
        return false;
    };
    
    resendOtp = function (userId, countryIso = '', dialCode = '',phone = ''){
        var postparam = (1 == phone) ? '' : "user_country_iso="+countryIso+"&user_dial_code="+dialCode+"&user_phone=" + phone;
        $.systemMessage(langLbl.processing, 'alert--process', false);
		fcom.ajax(fcom.makeUrl('Account', 'resendOtp'), postparam, function(t) {
            startOtpInterval();
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
        $(frm.btn_submit).attr('disabled', 'disabled');
        $.systemMessage(langLbl.processing,'alert--process', false);
		fcom.ajax(fcom.makeUrl( 'Account', 'validateOtp', [updateToDbFrm]), data, function(t) {
            try{
				t = $.parseJSON(t);
				if(typeof t.status != 'undefined' &&  1 > t.status){
                    $.systemMessage(t.msg,'alert--danger', false);
                    $(frm.btn_submit).removeAttr('disabled');
                } else {
                    $.systemMessage(t.msg,'alert--success', false);
                    changePhoneNumberForm();
                }
                return false;
			}
			catch(exc){
                $.systemMessage.close();
                $(phoneNumberdv + " .otpForm-js").remove();
                var lastFormElement = phoneNumberdv + ' form:last';
                $(lastFormElement).after(t);
                stylePhoneNumberFld();
			}
        });
        return false;
    };

})();
