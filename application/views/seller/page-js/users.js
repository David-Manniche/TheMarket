$(document).ready(function(){
	searchUsers();
});

(function() {
	var runningAjaxReq = false;
	var dv = '#listing';

	reloadList = function() {
		searchUsers();
	};

	searchUsers = function (form){
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Seller','searchUsers'),data,function(res){
			$('.btn-back').addClass('d-none');
			$(dv).html(res);
		});
	};

	addForm = function( id ) {
		fcom.ajax(fcom.makeUrl('Seller', 'socialPlatformForm', [id]), '', function(t) {
			$('.btn-back').removeClass('d-none');
			$(dv).html(t);
		});
	};

	setup = function( frm ) {
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Seller', 'socialPlatformSetup'), data, function(t) {
			$.mbsmessage.close();
			reloadList();
			if ( t.langId > 0 ) {
				addLangForm( t.splatformId, t.langId );
				return ;
			}

		});
	};

	addLangForm = function( splatformId, langId, autoFillLangData = 0 ){
        $(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Seller', 'socialPlatformLangForm', [splatformId, langId, autoFillLangData]), '', function(t) {
			$(dv).html(t);
		});
	};

	setupLang = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Seller', 'socialPlatformLangSetup'), data, function(t) {
			$.mbsmessage.close();
			reloadList();
			if ( t.langId > 0 ) {
				addLangForm(t.splatformId, t.langId);
				return ;
			}
		});
	};

	deleteRecord = function(id){
		if(!confirm(langLbl.confirmDelete)){ return; }
		data='splatformId='+id;
		fcom.updateWithAjax(fcom.makeUrl('Seller', 'deleteSocialPlatform'),data,function(res){
			reloadList();
		});
	};

	cancelForm = function(frm){
		reloadList();
		$(dv).html('');
	};

	toggleBulkStatues = function(status){
        if(!confirm(langLbl.confirmUpdateStatus)){
            return false;
        }
        $("#frmSellerUsersListing input[name='status']").val(status);
        $("#frmSellerUsersListing").submit();
    };

	toggleSellerUserStatus = function(e,obj){
		if(!confirm(langLbl.confirmUpdateStatus)){
			e.preventDefault();
			return;
		}
		var userId = parseInt(obj.value);
		if( userId < 1 ){
			return false;
		}
		data='userId='+userId;
		fcom.ajax(fcom.makeUrl('Seller','changeUserStatus'),data,function(res){
			var ans = $.parseJSON(res);
			if( ans.status == 1 ){
				$.mbsmessage(ans.msg, true, 'alert--success');
			} else {
				$.mbsmessage(ans.msg, true, 'alert--danger');
			}
		});
	};

})();
