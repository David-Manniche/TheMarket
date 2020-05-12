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
})();