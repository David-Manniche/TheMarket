$(document).ready(function(){
	searchProductBrands(document.frmSearch);
});

$(document).on('change','.logo-language-js',function(){
	var lang_id = $(this).val();
	var brand_id = $(this).closest("form").find('input[name="brand_id"]').val();
	brandImages(brand_id, 'logo', 0, lang_id);
});
$(document).on('change','.image-language-js',function(){
	var lang_id = $(this).val();
	var brand_id = $(this).closest("form").find('input[name="brand_id"]').val();
	var slide_screen = $(".prefDimensions-js").val();
	brandImages(brand_id, 'image', slide_screen, lang_id);
});
$(document).on('change','.prefDimensions-js',function(){
	var slide_screen = $(this).val();
	var brand_id = $(this).closest("form").find('input[name="brand_id"]').val();
	var lang_id = $(".image-language-js").val();
	brandImages(brand_id, 'image', slide_screen, lang_id);
});
(function() {
	var currentPage = 1;
	var runningAjaxReq = false;

	goToSearchPage = function(page) {
		if(typeof page==undefined || page == null){
			page =1;
		}
		var frm = document.frmBrandSearchPaging;
		$(frm.page).val(page);
		searchProductBrands(frm);
	}

	reloadList = function() {
		var frm = document.frmBrandSearchPaging;
		searchProductBrands(frm);
	}

	addBrandForm= function(id){
		$.facebox(function() {brandForm(id); });
	};

	brandForm = function(id) {
		fcom.displayProcessing();
		var frm = document.frmBrandSearchPaging;
			fcom.ajax(fcom.makeUrl('brands', 'form', [id]), '', function(t) {
			fcom.updateFaceboxContent(t);

			});
	};

	setupBrand = function(frm) {
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('brands', 'setup'), data, function(t) {
			reloadList();
			if (t.langId>0) {
				brandLangForm(t.brandId, t.langId);
				return ;
			}
			if (t.openMediaForm)
			{
				brandMediaForm(t.brandId);
				return;
			}
			$(document).trigger('close.facebox');
		});
	};

	brandLangForm = function(brandId, langId) {
		fcom.displayProcessing();
		fcom.ajax(fcom.makeUrl('brands', 'langForm', [brandId, langId]), '', function(t) {
			fcom.updateFaceboxContent(t);
		});
	};

	setupBrandLang=function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Brands', 'langSetup'), data, function(t) {
			reloadList();
			if (t.langId>0) {
				brandLangForm(t.brandId, t.langId);
				return ;
			}
			if (t.openMediaForm)
			{
				brandMediaForm(t.brandId);
				return;
			}
			$(document).trigger('close.facebox');
		});
	};

	searchProductBrands = function(form){
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
		$("#listing").html( fcom.getLoader() );
		fcom.ajax(fcom.makeUrl('Brands','Search'),data,function(res){
			$("#listing").html(res);
		});
	};

	brandImages = function(brandId, fileType, slide_screen, langId){
		fcom.ajax(fcom.makeUrl('Brands', 'images', [brandId, fileType, langId, slide_screen]), '', function(t) {
			if(fileType=='logo') {
				$('#logo-listing').html(t);
			} else {
				$('#image-listing').html(t);
			}
			fcom.resetFaceboxHeight();
		});
	};

	brandMediaForm = function(brandId){
		fcom.displayProcessing();
        fcom.ajax(fcom.makeUrl('Brands', 'media', [brandId]), '', function(t) {
            brandImages(brandId, 'logo', 1);
            brandImages(brandId, 'image', 1);
            fcom.updateFaceboxContent(t);
        });
	};

	deleteRecord = function(id){
		if(!confirm(langLbl.confirmDelete)){return;}
		data='id='+id;
		fcom.updateWithAjax(fcom.makeUrl('brands','deleteRecord'),data,function(res){
			reloadList();
		});
	};

	clearSearch = function(){
		document.frmSearch.reset();
		searchProductBrands(document.frmSearch);
	};

	deleteMedia = function( brandId, fileType, langId, slide_screen ){
		if(!confirm(langLbl.confirmDelete)){return;}
		fcom.updateWithAjax(fcom.makeUrl('brands', 'removeBrandMedia',[brandId, fileType, langId, slide_screen]), '', function(t) {
			brandImages(brandId,fileType,slide_screen,langId);
			reloadList();
		});
	};

	toggleStatus = function(e,obj,canEdit){
		if(canEdit == 0){
			e.preventDefault();
			return;
		}
		if(!confirm(langLbl.confirmUpdateStatus)){
			e.preventDefault();
			return;
		}
		var brandId = parseInt(obj.value);
		if( brandId < 1 ){
			fcom.displayErrorMessage(langLbl.invalidRequest);
			return false;
		}
		data='brandId='+brandId;
		fcom.ajax(fcom.makeUrl('Brands','changeStatus'),data,function(res){
		var ans = $.parseJSON(res);
			if( ans.status == 1 ){
				fcom.displaySuccessMessage(ans.msg);
				$(obj).toggleClass("active");
			}else{
				fcom.displayErrorMessage(ans.msg);
			}
		});
	};

	toggleBulkStatues = function(status){
		if(!confirm(langLbl.confirmUpdateStatus)){
			return false;
		}
		$("#frmBrandListing input[name='status']").val(status);
		$("#frmBrandListing").submit();
	};

	deleteSelected = function(){
		if(!confirm(langLbl.confirmDelete)){
			return false;
		}
		$("#frmBrandListing").attr("action",fcom.makeUrl('Brands','deleteSelected')).submit();
	};

	bannerPopupImage = function(inputBtn){
		if (inputBtn.files && inputBtn.files[0]) {
	        fcom.ajax(fcom.makeUrl('Brands', 'imgCropper'), '', function(t) {
				$('#cropperBox-js').html(t);
				$("#mediaForm-js").css("display", "none");
				var container = document.querySelector('.img-container');
                var file = inputBtn.files[0];
                $('#new-img').attr('src', URL.createObjectURL(file));
	    		var image = container.getElementsByTagName('img').item(0);
	            var minWidth = document.frmBrandImage.banner_min_width.value;
	            var minHeight = document.frmBrandImage.banner_min_height.value;
	    		var options = {
	                aspectRatio: aspectRatio,
	                data: {
	                    width: minWidth,
	                    height: minHeight,
	                },
	                minCropBoxWidth: minWidth,
	                minCropBoxHeight: minHeight,
	                toggleDragModeOnDblclick: false,
		        };
				$(inputBtn).val('');
    	  		return cropImage(image, options, 'uploadBrandImages', inputBtn);
	    	});
		}
	};

    logoPopupImage = function(inputBtn){
		if (inputBtn.files && inputBtn.files[0]) {
	        fcom.ajax(fcom.makeUrl('Brands', 'imgCropper'), '', function(t) {
				$('#cropperBox-js').html(t);
				$("#mediaForm-js").css("display", "none");
				var container = document.querySelector('.img-container');
                var file = inputBtn.files[0];
                $('#new-img').attr('src', URL.createObjectURL(file));
	    		var image = container.getElementsByTagName('img').item(0);
	            var minWidth = document.frmBrandLogo.logo_min_width.value;
	            var minHeight = document.frmBrandLogo.logo_min_height.value;
				if(minWidth == minHeight){
					var aspectRatio = 1 / 1
				} else {
	                var aspectRatio = 16 / 9;
	            }
	    		var options = {
	                aspectRatio: aspectRatio,
	                data: {
	                    width: minWidth,
	                    height: minHeight,
	                },
	                minCropBoxWidth: minWidth,
	                minCropBoxHeight: minHeight,
	                toggleDragModeOnDblclick: false,
		        };
				$(inputBtn).val('');
    	  		return cropImage(image, options, 'uploadBrandImages', inputBtn);
	    	});
		}
	};

	uploadBrandImages = function(formData){
        var node = this;
        $('#form-upload').remove();
        var frmName = formData.get("frmName");
        if ('frmBrandLogo' == frmName) {
			var brandId = document.frmBrandLogo.brand_id.value;
            var langId = document.frmBrandLogo.lang_id.value;
            var fileType = document.frmBrandLogo.file_type.value;
            var imageType = 'logo';
        } else {
			var brandId = document.frmBrandImage.brand_id.value;
            var langId = document.frmBrandImage.lang_id.value;
            var slideScreen = document.frmBrandImage.slide_screen.value;
            var fileType = document.frmBrandImage.file_type.value;
            var imageType = 'banner';
        }

		formData.append('brand_id', brandId);
        formData.append('slide_screen', slideScreen);
        formData.append('lang_id', langId);
        formData.append('file_type', fileType);
        /* $val = $(node).val(); */
        $.ajax({
            url: fcom.makeUrl('Brands', 'uploadMedia'),
            type: 'post',
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $(node).val('Loading');
            },
            complete: function() {
                /* $(node).val($val); */
            },
			success: function(ans) {
				$('.text-danger').remove();
				$('#input-field').html(ans.msg);
				if(ans.status==1)
				{
					fcom.displaySuccessMessage(ans.msg);
					$('#form-upload').remove();
					brandMediaForm(ans.brandId);
					brandImages(ans.brandId, imageType, slideScreen, langId);
					reloadList();
				}else{
					fcom.displayErrorMessage(ans.msg,'');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
        });
	}
})();
