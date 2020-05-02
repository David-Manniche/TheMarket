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
    
    deleteBatch = function (adsBatchId){
		var agree = confirm(langLbl.confirmDelete);
		if( !agree ){
			return false;
		}
		fcom.updateWithAjax(fcom.makeUrl(keyName, 'deleteBatch', [adsBatchId]), '', function(t) {
            search();
		});
    }

    setuppluginform = function (frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl(keyName, 'setupServiceAccountForm'), data, function(t) {
            $(document).trigger('close.facebox');
            location.reload();
        });
    }

    publishBatch = function (adsBatchId) {
        $.mbsmessage(langLbl.processing,true,'alert--process alert');   
		fcom.updateWithAjax(fcom.makeUrl(keyName, 'publishBatch', [adsBatchId]), '', function(t) {
            if( t.status == 1 ){
				$.mbsmessage(t.msg, true, 'alert--success');
			} else {
                $.mbsmessage(t.msg, true, 'alert--danger');
            }
            search();
        });
    }
})();