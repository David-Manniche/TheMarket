$(document).ready(function(){
	profileInfoForm();
});

(function() {
	var runningAjaxReq = false;
	var dv = '#profileInfoFrmBlock';
	var imgdv = '#profileImageFrmBlock';

	profileInfoForm = function(){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Profile', 'profileInfoForm'), '', function(t) {
			$(dv).html(t);

		});
	};

	profileImageForm = function(){
		$(imgdv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Profile', 'profileImageForm'), '', function(t) {
			$(imgdv).html(t);

		});
	};

	updateProfileInfo = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Profile', 'updateProfileInfo'), data, function(t) {
			//$.mbsmessage.close();
		});
	};

	removeProfileImage = function(){
		fcom.ajax(fcom.makeUrl('Profile','removeProfileImage'),'',function(t){
			profileImageForm();
		});
	};

	sumbmitProfileImage = function(){
		$("#frmProfile").ajaxSubmit({
			delegation: true,
			success: function(json){
				json = $.parseJSON(json);
				profileImageForm();
				$(document).trigger('close.facebox');
			}
		});
	};

	popupImage = function(){
		systemImgCropper(fcom.makeUrl('Profile', 'imgCropper'), '1', 'saveProfileImage');
	};

	saveProfileImage = function(formData){
		$.ajax({
			url: fcom.makeUrl('Profile', 'uploadProfileImage'),
			type: 'post',
			dataType: 'json',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function() {
				// $(node).val('Loading');
			},
			success: function(ans) {
					$('#dispMessage').html(ans.msg);
					profileInfoForm();
					$(document).trigger('close.facebox');
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
		});
	}

})();
