$(document).ready(function(){
	profileInfoForm();
});

(function() {
	var runningAjaxReq = false;
	var dv = '#profileInfoFrmBlock';
	var imgdv = '#profileImageFrmBlock';

	profileInfoForm = function(){
		$(dv).html(fcom.getLoader());
		$("#tab-myaccount").parents().children().removeClass("is-active");
		$("#tab-myaccount").addClass("is-active");
		fcom.ajax(fcom.makeUrl('Account', 'profileInfoForm'), '', function(t) {
			$(dv).html(t);
		});
	};

	profileImageForm = function(){
		$(imgdv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Account', 'profileImageForm'), '', function(t) {
            location.reload();
			/* $(imgdv).html(t); */
		});
	};

	updateProfileInfo = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Account', 'updateProfileInfo'), data, function(t) {
			profileInfoForm();
			$.mbsmessage.close();
		});
	};

	setPreferredDashboad = function (id){
		fcom.updateWithAjax(fcom.makeUrl('Account','setPrefferedDashboard',[id]),'',function(res){
		});
	};

	bankInfoForm = function(){
		$(dv).html(fcom.getLoader());
		$("#tab-bankaccount").parents().children().removeClass("is-active");
		$("#tab-bankaccount").addClass("is-active");
		fcom.ajax(fcom.makeUrl('Account','bankInfoForm'),'',function(t){
			$(dv).html(t);
		});
	};
	settingsForm = function(){
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Account','settingsInfo'),'',function(t){
			$(dv).html(t);
		});
	};
	setSettingsInfo = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Account', 'updateSettingsInfo'), data, function(t) {
			settingsForm();
		});
	};
	setBankInfo = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Account', 'updateBankInfo'), data, function(t) {
			bankInfoForm();
		});
	};

	removeProfileImage = function(){
		fcom.ajax(fcom.makeUrl('Account','removeProfileImage'),'',function(t){
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

	affiliatePaymentInfoForm = function(){
		$(dv).html(fcom.getLoader());
		$("#tab-paymentinfo").parents().children().removeClass("is-active");
		$("#tab-paymentinfo").addClass("is-active");
		fcom.ajax(fcom.makeUrl('Affiliate','paymentInfoForm'),'',function(t){
			$(dv).html(t);
		});
	}

	setUpAffiliatePaymentInfo = function( frm ){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Affiliate', 'setUpPaymentInfo'), data, function(t) {
			//returnAddressForm();
		});
	}

	popupImage = function(input){
		fcom.ajax(fcom.makeUrl('Cropper', 'index'), '', function(t) {
			$.facebox(t,'faceboxWidth fbminwidth');
			var container = document.querySelector('.img-container');
			var image = container.getElementsByTagName('img').item(0);
			cropImage(image);
		});
	};

	cropImage = function(image){
		var actions = document.getElementById('actions');
		var options = {
		aspectRatio: 1 / 1,
		preview: '.img-preview',
		crop: function (e) {
		  var data = e.detail;
		  console.log(e.type);
		}
	  };
	  var cropper = new Cropper(image, options);
	  var originalImageURL = image.src;
	  var uploadedImageType = 'image/jpeg';
	  var uploadedImageName = 'cropped.jpg';
	  var uploadedImageURL;

	  actions.querySelector('.docs-buttons').onclick = function (event) {
		var e = event || window.event;
		var target = e.target || e.srcElement;
		var cropped;
		var result;
		var input;
		var data;

		if (!cropper) {
		  return;
		}

		while (target !== this) {
		  if (target.getAttribute('data-method')) {
			break;
		  }

		  target = target.parentNode;
		}

		if (target === this || target.disabled || target.className.indexOf('disabled') > -1) {
		  return;
		}

		data = {
		  method: target.getAttribute('data-method'),
		  target: target.getAttribute('data-target'),
		  option: target.getAttribute('data-option') || undefined,
		  secondOption: target.getAttribute('data-second-option') || undefined
		};

		cropped = cropper.cropped;

		if (data.method) {
		  if (typeof data.target !== 'undefined') {
			input = document.querySelector(data.target);

			if (!target.hasAttribute('data-option') && data.target && input) {
			  try {
				data.option = JSON.parse(input.value);
			  } catch (e) {
				console.log(e.message);
			  }
			}
		  }

		  switch (data.method) {
			case 'rotate':
			  if (cropped && options.viewMode > 0) {
				cropper.clear();
			  }

			  break;

			case 'getCroppedCanvas':
			  try {
				data.option = JSON.parse(data.option);
			  } catch (e) {
				console.log(e.message);
			  }

			  if (uploadedImageType === 'image/jpeg') {
				if (!data.option) {
				  data.option = {};
				}

				data.option.fillColor = '#fff';
			  }

			  break;
		  }

		  result = cropper[data.method](data.option, data.secondOption);

		  switch (data.method) {
			case 'rotate':
			  if (cropped && options.viewMode > 0) {
				cropper.crop();
			  }

			  break;

			case 'scaleX':
			case 'scaleY':
			  target.setAttribute('data-option', -data.option);
			  break;

			case 'getCroppedCanvas':
			  if (result) {
				// Bootstrap's Modal
				$('#getCroppedCanvasModal').modal().find('.modal-body').html(result);

				if (!download.disabled) {
				  download.download = uploadedImageName;
				  download.href = result.toDataURL(uploadedImageType);
				}
			  }

			  break;

			case 'destroy':
			  cropper = null;

			  if (uploadedImageURL) {
				URL.revokeObjectURL(uploadedImageURL);
				uploadedImageURL = '';
				image.src = originalImageURL;
			  }

			  break;
		  }

		  if (typeof result === 'object' && result !== cropper && input) {
			try {
			  input.value = JSON.stringify(result);
			} catch (e) {
			  console.log(e.message);
			}
		  }
		}
		};

		// Import image
		  var inputImage = document.getElementById('inputImage');

		  if (URL) {
		    inputImage.onchange = function () {
		      var files = this.files;
		      var file;

		      if (cropper && files && files.length) {
		        file = files[0];

		        if (/^image\/\w+/.test(file.type)) {
		          uploadedImageType = file.type;
		          uploadedImageName = file.name;

		          if (uploadedImageURL) {
		            URL.revokeObjectURL(uploadedImageURL);
		          }

		          image.src = uploadedImageURL = URL.createObjectURL(file);
		          cropper.destroy();
		          cropper = new Cropper(image, options);
		          inputImage.value = null;
		        } else {
		          window.alert('Please choose an image file.');
		        }
		      }
		    };
		  } else {
		    inputImage.disabled = true;
		    inputImage.parentNode.className += ' disabled';
		  }

	}

	truncateDataRequestPopup = function(){
		$.facebox(function() {
			fcom.ajax(fcom.makeUrl('Account', 'truncateDataRequestPopup'), '', function(t) {
				$.facebox( t,'faceboxWidth');
			});
		});
	};

	sendTruncateRequest = function(){
		/* var agree = confirm( langLbl.confirmDeletePersonalInformation );
		if( !agree ){
			return false;
		} */
		fcom.updateWithAjax(fcom.makeUrl('Account', 'sendTruncateRequest'), '', function(t) {
			profileInfoForm();
			$(document).trigger('close.facebox');
		});
	};

	cancelTruncateRequest = function(){
		$(document).trigger('close.facebox');
	};

	requestData = function(){
		$.facebox(function() {
			fcom.ajax(fcom.makeUrl('Account', 'requestDataForm'), '', function(t) {
				$.facebox( t,'faceboxWidth');
			});
		});
	};

	setupRequestData = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Account', 'setupRequestData'), data, function(t) {
			$("#facebox .close").trigger('click');
		});
	};


})();

/* $(document).on('click','.userFile-Js',function(){
	var node = this;
	$('#form-upload').remove();
	var frm = '<form enctype="multipart/form-data" id="form-upload" style="position:absolute; top:-100px;" >';
	frm = frm.concat('<input type="file" name="file" />');
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
				url: fcom.makeUrl('Account', 'uploadProfileImage'),
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
						$('#dispMessage').html(ans.msg);
						profileInfoForm();
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
		}
	}, 500);
}); */
