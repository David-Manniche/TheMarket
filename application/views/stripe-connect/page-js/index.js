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
        var attr = $(frm).attr('enctype');
        if (typeof attr !== typeof undefined && attr !== false) {
            $(frm).attr('action', fcom.makeUrl(keyName, 'setupRequiredFields')).removeAttr("onsubmit").submit();
            return false;
        }
		fcom.updateWithAjax(fcom.makeUrl(keyName, 'setupRequiredFields'), data, function(t) {
            $("#facebox .close").trigger('click');
            location.reload();
        });
    }
})();