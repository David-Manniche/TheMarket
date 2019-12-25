(function() {
systemImgCropper = function(url, aspectRatio, callback, inputImage){
	fcom.ajax(url, '', function(t) {
		$.facebox(t,'faceboxWidth fbminwidth');
		var container = document.querySelector('.img-container');
		var image = container.getElementsByTagName('img').item(0);
		var options = {
		aspectRatio: aspectRatio,
		preview: '.img-preview',
		crop: function (e) {
		  var data = e.detail;
		}
	  };
	  return cropImage(image, options, callback, inputImage);
	});
};

cropImage = function(image, options, callback, inputImage){
  var actions = document.getElementById('actions');
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
	var orgImageBlob;
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
			var formData = new FormData();
			var canvas;
			canvas = cropper.clear().getCroppedCanvas();
			canvas.toBlob(function (blob) {
				formData.append('org_image', blob, '_org.png');
			});
			result.toBlob(function (blob) {
                formData.append('cropped_image', blob, '_crop.png');
                formData.append("action", "avatar");
				if(inputImage){
					var frmName = $(inputImage).attr('data-frm')
					formData.append("frmName", frmName);
				}
				window[callback](formData);
			});
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
	  if(inputImage === undefined){
		var inputImage = document.getElementById('inputImage');
	  }
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
})();
