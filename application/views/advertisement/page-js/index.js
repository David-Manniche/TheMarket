$(document).ready(function() {
    form();
    search();
});

(function() {
	var dv = '#listing';
	var batchSetup = '#batchSetup';
    
	search = function(){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Advertisement','search'),'',function(res){
			$(dv).html(res);
		});
    };
    
	form = function(adsBatchId = 0){
        $(batchSetup).html(fcom.getLoader());
        $('html, body').animate({scrollTop: $(batchSetup).offset().top - 150 }, 'slow');
		fcom.ajax(fcom.makeUrl('Advertisement','form', [adsBatchId]),'',function(res){
            $(batchSetup).html(res);
            $('.date_js').datepicker('option', {
                minDate: new Date()
            });
		});
    };
    
	pluginForm = function(){
        $.facebox(function() {
            var btn = "#userAccInfoBtn";
            fcom.ajax(fcom.makeUrl('Advertisement','getPluginForm'),'',function(res){
                $.facebox(res,'faceboxWidth medium-fb-width');
            });
		});
    };
    
    clearForm = function() {
        form();
    };


	setup = function (frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Advertisement', 'setup'), data, function(t) {
            form();
            search();
		});
    }
    
    deleteBatch = function (adsBatchId){
		var agree = confirm(langLbl.confirmDelete);
		if( !agree ){
			return false;
		}
		fcom.updateWithAjax(fcom.makeUrl('Advertisement', 'deleteBatch', [adsBatchId]), '', function(t) {
            search();
		});
    }

    setuppluginform = function (frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Advertisement', 'setupPluginForm'), data, function(t) {});
    }
})();