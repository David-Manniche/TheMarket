var keyName = 'StripeConnect';

(function() {    
	requiredFieldsForm = function(){
        $.facebox(function() {
            fcom.ajax(fcom.makeUrl(keyName, 'requiredFieldsForm'),'',function(res){
                $.facebox(res,'faceboxWidth');
            });
		});
    };
    
    clearForm = function() {
        fcom.ajax(fcom.makeUrl(keyName, 'requiredFieldsForm'),'',function(res){
            $.facebox(res,'faceboxWidth');
        });
    };

	setupRequiredFields = function (frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl(keyName, 'setupRequiredFields'), data, function(t) {});
    }

    financialInfoForm = function (frm) {
        $.facebox(function() {
            fcom.ajax(fcom.makeUrl(keyName, 'financialInfoForm'),'',function(res){
                $.facebox(res,'faceboxWidth');
            });
		});
    }

    setupFinancialInfo = function (frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl(keyName, 'setupFinancialInfo'), data, function(t) {});
    }

    clearFinancialInfoForm = function() {
        fcom.ajax(fcom.makeUrl(keyName, 'financialInfoForm'),'',function(res){
            $.facebox(res,'faceboxWidth');
        });
    };
})();