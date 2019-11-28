$(document).ready(function() {
    searchAddon(document.frmAddonSearch);
});

(function() {
	var dv = '#addonsListing';

	reloadList = function() {
		var frm = document.frmAddonSearch;
		searchAddon(frm);
	};

	searchAddon = function(form){
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
		$(dv).html(fcom.getLoader());

		fcom.ajax(fcom.makeUrl('Addons','search'),data,function(res){
			$(dv).html(res);
		});
	};

	editAddonForm = function(addonId){
		$.facebox(function() {
			addonForm(addonId);
		});
	};
    
	addonForm = function(addonId){
		fcom.displayProcessing();
		fcom.ajax(fcom.makeUrl('Addons', 'form', [addonId]), '', function(t) {
			fcom.updateFaceboxContent(t);
		});
	}


	setupAddon = function (frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Addons', 'setup'), data, function(t) {
			reloadList();
			if (t.langId>0) {
				editAddonLangForm(t.addonId, t.langId);
				return ;
			}
			$(document).trigger('close.facebox');
		});
	}

	editAddonLangForm = function(addonId,langId, autoFillLangData = 0){
		fcom.displayProcessing();
		fcom.ajax(fcom.makeUrl('Addons', 'langForm', [addonId,langId, autoFillLangData]), '', function(t) {
			fcom.updateFaceboxContent(t);
		});
	};

	setupLangAddon = function (frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Addons', 'langSetup'), data, function(t) {
			reloadList();
			if (t.langId>0) {
				editAddonLangForm(t.addonId, t.langId);
				return ;
			}
			$(document).trigger('close.facebox');
		});
	};

	editSettingForm = function (code){
        fcom.displayProcessing();
        var data = 'keyName=' + code;
		fcom.ajax(fcom.makeUrl('AddonSetting'), data, function(t) {
            var res = isJson(t);
            if (res && res.status == 0) {
                fcom.displayErrorMessage(res.msg);
            } else {
				$.facebox(function() {
                    fcom.updateFaceboxContent(t);
                });
			}
		});
	};

	setupAddonsSettings = function (frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('AddonSetting', 'setup'), data, function(t) {
			$(document).trigger('close.facebox');
		});
	};

	toggleStatus = function( obj ){
		if( !confirm(langLbl.confirmUpdateStatus) ){ return; }
		var addonId = parseInt(obj.id);
		if( addonId < 1 ){
			fcom.displayErrorMessage(langLbl.invalidRequest);
			return false;
		}
		data = 'addonId='+addonId;
		fcom.ajax(fcom.makeUrl('Addons','changeStatus'),data,function(res){
			var ans =$.parseJSON(res);
			if(ans.status == 1){
				fcom.displaySuccessMessage(ans.msg);
				$(obj).toggleClass("active");
				setTimeout(function(){ reloadList(); }, 1000);
			}else{
				fcom.displayErrorMessage(ans.msg);
			}
		});
    };
    
    toggleBulkStatues = function(status){
        if(!confirm(langLbl.confirmUpdateStatus)){
            return false;
        }
        $("#frmAddonListing input[name='status']").val(status);
        $("#frmAddonListing").submit();
    };
})();

$(document).on('click','.uploadFile-Js',function(){
	var node = this;
	$('#form-upload').remove();
	var addonId = $(node).attr('data-addonId');
	var frm = '<form enctype="multipart/form-data" id="form-upload" style="position:absolute; top:-100px;" >';
	frm = frm.concat('<input type="file" name="file" />');
	frm = frm.concat('<input type="hidden" name="addonId" value="'+addonId+'"/>');
	$('body').prepend(frm);
	$('#form-upload input[name=\'file\']').trigger('click');
	if (typeof timer != 'undefined') {
		clearInterval(timer);
	}
	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);
			$val = $(node).val();
			$.ajax({
				url: fcom.makeUrl('Addons', 'uploadIcon',[$('#form-upload input[name=\'addonId\']').val()]),
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).val('Loading');
				},
				complete: function() {
					$(node).val($val);
				},
				success: function(ans) {
						$('.text-danger').remove();
						$('#Addon_icon').html(ans.msg);
						if(ans.status == true){
							$('#Addon_icon').removeClass('text-danger');
							$('#Addon_icon').addClass('text-success');
							//editAddonForm(ans.addonId);
						}else{
							$('#Addon_icon').removeClass('text-success');
							$('#Addon_icon').addClass('text-danger');
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
		}
	}, 500);
});
