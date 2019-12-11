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

	$(document).on('click', '[data-method]', function () {
		var data = $(this).data(),
          $target,
          result;

      if (data.method) {
        data = $.extend({}, data); // Clone a new one
        if (typeof data.target !== 'undefined') {
          $target = $(data.target);
          if (typeof data.option === 'undefined') {
            try {
              data.option = JSON.parse($target.val());
            } catch (e) {
              console.log(e.message);
            }
          }
        }
        result = $image.cropper(data.method, data.option);
		if (data.method === 'getCroppedCanvas') {
          $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);
        }

        if ($.isPlainObject(result) && $target) {
          try {
            $target.val(JSON.stringify(result));
          } catch (e) {
            console.log(e.message);
          }
        }

      }
    });

	function getRoundedCanvas(sourceCanvas) {
      var canvas = document.createElement('canvas');
      var context = canvas.getContext('2d');
      var width = sourceCanvas.width;
      var height = sourceCanvas.height;

      canvas.width = width;
      canvas.height = height;
      context.imageSmoothingEnabled = true;
      context.drawImage(sourceCanvas, 0, 0, width, height);
      context.globalCompositeOperation = 'destination-in';
      context.beginPath();
      context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI, true);
      context.fill();
      return canvas;
    }

	cropImage = function(obj){
	  // var image = document.getElementById('new-img');
	  var image = obj;
      var button = document.getElementById('updateBtn-js');
	  var dataX = document.getElementById('dataX');
	  var dataY = document.getElementById('dataY');
	  var dataHeight = document.getElementById('dataHeight');
	  var dataWidth = document.getElementById('dataWidth');
	  var dataRotate = document.getElementById('dataRotate');
	  var dataScaleX = document.getElementById('dataScaleX');
	  var dataScaleY = document.getElementById('dataScaleY');

      var cropper = new Cropper(image, {
        aspectRatio: 16 / 9,
        viewMode: 1,
        ready: function () {
          croppable = true;
        },
      });
	  var options = {
	    aspectRatio: 16 / 9,
	    crop: function (e) {
	      var data = e.detail;
	      console.log(e.type);
	      dataX.value = Math.round(data.x);
	      dataY.value = Math.round(data.y);
	      dataHeight.value = Math.round(data.height);
	      dataWidth.value = Math.round(data.width);
	      dataRotate.value = typeof data.rotate !== 'undefined' ? data.rotate : '';
	      dataScaleX.value = typeof data.scaleX !== 'undefined' ? data.scaleX : '';
	      dataScaleY.value = typeof data.scaleY !== 'undefined' ? data.scaleY : '';
	    }
  	  };

	  var cropper = new Cropper(image, options);

      button.onclick = function () {
        var croppedCanvas;
        var roundedCanvas;
        var roundedImage;

        if (!croppable) {
          return;
        }

        // Crop
        croppedCanvas = cropper.getCroppedCanvas();

        // Round
        roundedCanvas = getRoundedCanvas(croppedCanvas);

        // Show
        roundedImage = document.createElement('img');
        roundedImage.src = roundedCanvas.toDataURL();


		/*var json = [
					'{"x":' + cropper.getData().x,
					'"y":' + cropper.getData().y,
					'"height":' + cropper.getData().height,
					'"width":' + e.width,
					'"rotate":' + e.rotate + '}'
					].join();
				$("#img_data").val(json);*/
		/*result.innerHTML = '';
        result.appendChild(roundedImage);*/
		// sumbmitProfileImage()
      };
	};

	popupOrgImage = function(input){
		$.facebox('<div class="popup__body"><div id="loader" class="loader">'+fcom.getLoader()+'</div></div>','faceboxWidth fbminwidth');

		wid = $(window).width();
		if(wid > 767){
			wid = 500;
		}else{
			wid = 280;
		}
		$("#avatar-action").val("avatar");
		var fn = "sumbmitProfileImage();";

		$.facebox('<div class="popup__body"><div class="img-container "><img alt="Picture" src="" class="img_responsive" id="new-img" /></div><span class="gap"></span><div class="align--center rotator-actions"><a href="javascript:void(0)" class="btn btn--primary btn--sm" title="'+$("#rotate_left").val()+'" data-option="-90" data-method="rotate">'+$("#rotate_left").val()+'</a>&nbsp;<a onclick='+fn+' href="javascript:void(0)" class="btn btn--primary btn--sm">'+$("#update_profile_img").val()+'</a>&nbsp;<a href="javascript:void(0)" class="btn btn--primary btn--sm" title="'+$("#rotate_right").val()+'" data-option="90" data-method="rotate">'+$("#rotate_right").val()+'</a></div></div>','faceboxWidth');
		orgImg = $("#org-img").val();
		$('#new-img').attr('src', orgImg);
		$('#new-img').width(wid);
		cropImage(document.getElementById('new-img'));
	};

	popupImage = function(input){
		$.facebox('<div class="popup__body"><div id="loader" class="loader">'+fcom.getLoader()+'</div></div>','faceboxWidth fbminwidth');

		wid = $(window).width();
		if(wid > 767){
			wid = 500;
		}else{
			wid = 280;
		}

		var defaultform = "#frmProfile";
		$("#avatar-action").val("demo_avatar");
		$(defaultform).ajaxSubmit({
			delegation: true,
			success: function(json){
				json = $.parseJSON(json);
				if(json.status == 1){
					$("#avatar-action").val("avatar");
					var fn = "sumbmitProfileImage();";

					$.facebox('<div class="popup__body"><div class="img-container "><img alt="Picture" src="" class="img_responsive" id="new-img" /></div><span class="gap"></span><div class="align--center rotator-actions"><a href="javascript:void(0)" class="btn btn--primary btn--sm" title="'+$("#rotate_left").val()+'" data-option="-90" data-method="rotate"><span class="fa fa-undo-alt"></span></a>&nbsp;<a href="javascript:void(0)" class="btn btn--primary btn--sm" title="'+$("#rotate_right").val()+'" data-option="90" data-method="rotate"><span class="fa fa-redo-alt"></span></a>&nbsp;<a id="updateBtn-js" onclick='+fn+' href="javascript:void(0)" class="btn btn--primary btn--sm">'+$("#update_profile_img").val()+'</a>&nbsp;<a href="javascript:void(0)" class="btn btn--primary btn--sm" title="'+$("#flip_horizontal").val()+'" data-option="-1" data-method="scaleX"><span class="fa fa-arrows-alt-h"></a>&nbsp;<a href="javascript:void(0)" class="btn btn--primary btn--sm" title="'+$("#flip_vertical").val()+'" data-option="-1" data-method="scaleY"><span class="fa fa-arrows-alt-v"></span></a></div></div>','faceboxWidth');
					$('#new-img').attr('src', json.file);
					$('#new-img').width(wid);
					cropImage(document.getElementById('new-img'));
				}else{
					$.facebox('<div class="popup__body"><div class="img-container marginTop20">'+json.msg+'</div></div>');
				}
			}
		});
	};

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
