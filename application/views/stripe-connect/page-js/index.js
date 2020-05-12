var keyName = 'StripeConnect';

(function() {    
	requiredFieldsForm = function(){
        $.facebox(function() {
            fcom.ajax(fcom.makeUrl(keyName, 'requiredFieldsForm'),'',function(res){
                $.facebox(res,'faceboxWidth medium-fb-width');
            });
		});
    };
    
    clearForm = function() {
        fcom.ajax(fcom.makeUrl(keyName, 'requiredFieldsForm'),'',function(res){
            $.facebox(res,'faceboxWidth medium-fb-width');
        });
    };

	setupRequiredFields = function (frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl(keyName, 'setupRequiredFields'), data, function(t) {
            $("#facebox .close").trigger('click');
            location.reload();
        });
    }

    /*setupFinancialInfo = function (frm){
		if (!$(frm).validate()) return;
		// var dataQueryString = fcom.frmData(frm);
        var data = $(frm).serializeArray().reduce(function(obj, item) {
                        obj[item.name] = item.value;
                        return obj;
                    }, {}); 

		// fcom.updateWithAjax(fcom.makeUrl(keyName, 'setupFinancialInfo'), data, function(t) {});
        stripe.createToken('bank_account', data).then(function(result) {
            // Handle result.error or result.token
            console.log(result);
            if (result.error) {
                console.log(result.error);
                $.mbsmessage( result.error.message, '', 'alert--danger');
            } else {
                var newData = "token=" . result.token;
                fcom.updateWithAjax(fcom.makeUrl(keyName, 'setupFinancialInfo'), newData, function(t) {
                    // $("#facebox .close").trigger('click');
                    // location.reload();
                });
            }
        });
    }*/
})();