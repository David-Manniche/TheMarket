$(document).ready(function() {
    searchPlugin(1);
});

(function() {
	var dv = '#pluginsListing';

	reloadList = function() {
		$activeFormType = $('.tabs_nav_container ul.tabs_nav li a.active').data('formtype')
		searchPlugin($activeFormType);
    };
    
	searchPlugin = function(type){
		$(dv).html(fcom.getLoader());

		fcom.ajax(fcom.makeUrl('Plugins','search', [type]),'',function(res){
			$(dv).html(res);
		});
	};

	editPluginForm = function(pluginType, pluginId){
		$.facebox(function() {
            fcom.ajax(fcom.makeUrl('Plugins', 'form', [pluginType, pluginId]), '', function(t) {
                fcom.updateFaceboxContent(t);
            });
		});
	};


	setupPlugin = function (frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Plugins', 'setup'), data, function(t) {
			reloadList();
			if (t.langId>0) {
				editPluginLangForm(t.pluginId, t.langId);
				return ;
			}
			$(document).trigger('close.facebox');
		});
	}

	editPluginLangForm = function(pluginId, langId, autoFillLangData = 0){
		fcom.displayProcessing();
		fcom.ajax(fcom.makeUrl('Plugins', 'langForm', [pluginId,langId, autoFillLangData]), '', function(t) {
			fcom.updateFaceboxContent(t);
		});
	};

	setupLangPlugin = function (frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Plugins', 'langSetup'), data, function(t) {
			reloadList();
			if (t.langId>0) {
				editPluginLangForm(t.pluginId, t.langId);
				return ;
			}
			$(document).trigger('close.facebox');
		});
	};

	editSettingForm = function (keyName){
        fcom.displayProcessing();
        var data = 'keyName=' + keyName;
		fcom.ajax(fcom.makeUrl(keyName + 'Settings'), data, function(t) {
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

	setupPluginsSettings = function (frm){
		if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        var keyName = frm.keyName.value;
		fcom.updateWithAjax(fcom.makeUrl(keyName + 'Settings', 'setup'), data, function(t) {
			$(document).trigger('close.facebox');
		});
	};

	toggleStatus = function( obj ){
		if( !confirm(langLbl.confirmUpdateStatus) ){ return; }
		var pluginId = parseInt(obj.id);
		if( pluginId < 1 ){
			fcom.displayErrorMessage(langLbl.invalidRequest);
			return false;
		}
		data = 'pluginId='+pluginId;
		fcom.ajax(fcom.makeUrl('Plugins','changeStatus'),data,function(res){
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
        $("#frmPluginListing input[name='status']").val(status);
        $("#frmPluginListing").submit();
    };
})();

$(document).on('click','.uploadFile-Js',function(){
	var node = this;
	$('#form-upload').remove();
	var pluginId = $(node).attr('data-pluginId');
	var frm = '<form enctype="multipart/form-data" id="form-upload" style="position:absolute; top:-100px;" >';
	frm = frm.concat('<input type="file" name="file" />');
	frm = frm.concat('<input type="hidden" name="pluginId" value="'+pluginId+'"/>');
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
				url: fcom.makeUrl('Plugins', 'uploadIcon',[$('#form-upload input[name=\'pluginId\']').val()]),
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
						$('#Plugin_icon').html(ans.msg);
						if(ans.status == true){
							$('#Plugin_icon').removeClass('text-danger');
							$('#Plugin_icon').addClass('text-success');
							//editPluginForm(ans.pluginId);
						}else{
							$('#Plugin_icon').removeClass('text-success');
							$('#Plugin_icon').addClass('text-danger');
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
		}
	}, 500);
});
