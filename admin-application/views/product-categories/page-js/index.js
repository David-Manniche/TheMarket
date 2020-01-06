$(document).ready(function(){
	searchProductCategories(document.frmSearch);
});
$(document).on('change','.icon-language-js',function(){
	var lang_id = $(this).val();
	var prodcat_id = $("input[name='prodcat_id']").val();
	categoryImages(prodcat_id,'icon',0,lang_id);

});
$(document).on('change','.banner-language-js',function(){
	var lang_id = $(this).val();
	var prodcat_id = $("input[name='prodcat_id']").val();
	var slide_screen = $(".prefDimensions-js").val();
	categoryImages(prodcat_id,'banner',slide_screen,lang_id);
});
$(document).on('change','.prefDimensions-js',function(){
	var slide_screen = $(this).val();
	var prodcat_id = $("input[name='prodcat_id']").val();
	var lang_id = $(".banner-language-js").val();
	categoryImages(prodcat_id,'banner',slide_screen,lang_id);
});
(function() {
	var currentPage = 1;
	var runningAjaxReq = false;

	goToSearchPage = function(page) {
		if(typeof page==undefined || page == null){
			page =1;
		}
		var frm = document.frmCatSearchPaging;
		$(frm.page).val(page);
		searchProductCategories(frm);
	}

	reloadList = function() {
		var frm = document.frmCatSearchPaging;
		searchProductCategories(frm);
	}

	addCategoryForm= function (id) {

	$.facebox(function(){categoryForm(id);});

	// body...
}
	categoryForm = function(id) {
		fcom.displayProcessing();
		var frm = document.frmCatSearchPaging;

		var parent=$(frm.prodcat_parent).val();
		fcom.displayProcessing();

		//var frm = document.frmCatSearchPaging;
		if(typeof parent==undefined || parent == null){
			parent =0;
		}

		fcom.ajax(fcom.makeUrl('ProductCategories', 'form', [id,parent]), '', function(t) {
			fcom.updateFaceboxContent(t);
			fcom.resetEditorInstance();
		});

	};

	categoryImages = function(prodCatId,imageType,slide_screen,lang_id){
		fcom.ajax(fcom.makeUrl('ProductCategories', 'images', [prodCatId,imageType,lang_id,slide_screen]), '', function(t) {
			if(imageType=='icon') {
				$('#icon-image-listing').html(t);
			} else if(imageType=='banner') {
				$('#banner-image-listing').html(t);
			}
			fcom.resetFaceboxHeight();
		});
	};

	setupCategory = function(frm) {
		if (!$(frm).validate()) return;
		var addingNew = ( $(frm.prodcat_id).val() == 0 );
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('ProductCategories', 'setup'), data, function(t) {
			reloadList();
			if ( t.langId > 0 ) {
				categoryLangForm(t.catId, t.langId);
				return ;
			}
			if ( addingNew ) {
				categoryLangForm(t.catId, t.langId);
				return ;
			}
			if ( t.openMediaForm ){
				categoryMediaForm( t.catId );
				return;
			}
			fcom.resetEditorInstance();
			$(document).trigger('close.facebox');
		});
	};

	categoryLangForm = function(catId, langId) {

		fcom.resetEditorInstance();
		//$.facebox(function() {
			fcom.displayProcessing();
			fcom.ajax(fcom.makeUrl('ProductCategories', 'langForm', [catId, langId]), '', function(t) {
				//fcom.updateFaceboxContent(t);
				//$.facebox(t);
				fcom.updateFaceboxContent(t);
				fcom.setEditorLayout(langId);
				var frm = $('#facebox form')[0];
				var validator = $(frm).validation({errordisplay: 3});
				$(frm).submit(function(e) {
					e.preventDefault();
					if (validator.validate() == false) {
						return ;
					}
					var data = fcom.frmData(frm);
					if (!$(frm).validate()) return;

					fcom.updateWithAjax(fcom.makeUrl('ProductCategories', 'langSetup'), data, function(t) {
						fcom.resetEditorInstance();
						reloadList();
						if (t.langId > 0) {
							categoryLangForm(t.catId, t.langId);
							return;
						}
						if (t.openMediaForm){
							categoryMediaForm(t.catId);
							return;
						}
						$(document).trigger('close.facebox');
					});

				});
			});
		//});
	};

	searchProductCategories = function(form){
		var data = '';
		if ( form ) {
			data = fcom.frmData(form);
		}

		$("#listing").html( fcom.getLoader() );
		fcom.ajax(fcom.makeUrl('productCategories','search'),data,function(res){
			$("#listing").html(res);
		});
	};

	subcat_list=function(parent){
		var frm = document.frmCatSearchPaging;
		$(frm.prodcat_parent).val(parent);
		reloadList();
	};

	categoryMediaForm = function(prodCatId){
		fcom.displayProcessing();
		fcom.ajax(fcom.makeUrl('productCategories','mediaForm',[prodCatId]),'',function(t){
			categoryImages(prodCatId,'icon',1);
			categoryImages(prodCatId,'banner',1);
			fcom.updateFaceboxContent(t);
			setTimeout(  fcom.resetFaceboxHeight(),5000);
		});
	};

	deleteRecord = function(id){
		if(!confirm(langLbl.confirmDelete)){return;}
		data='id='+id;
		fcom.ajax(fcom.makeUrl('productCategories','deleteRecord'),data,function(res){
			var ans = $.parseJSON(res);
			if( ans.status == 1 ){
				fcom.displaySuccessMessage(ans.msg);
				reloadList();
			} else {
				fcom.displayErrorMessage(ans.msg);
			}
		});
	};

	deleteImage = function(fileId, prodcatId, imageType, langId, slide_screen){
		if( !confirm(langLbl.confirmDeleteImage) ){ return; }
		fcom.updateWithAjax(fcom.makeUrl('productCategories', 'removeImage',[fileId,prodcatId,imageType,langId,slide_screen]), '', function(t) {
			categoryImages( prodcatId, imageType, slide_screen, langId );
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
		var prodcatId = parseInt(obj.value);
		if( prodcatId < 1 ){
			fcom.displayErrorMessage(langLbl.invalidRequest);
			return false;
		}
		data='prodcatId='+prodcatId;
		fcom.displayProcessing();
		fcom.ajax(fcom.makeUrl('productCategories','changeStatus'),data,function(res){
		var ans = $.parseJSON(res);
			if( ans.status == 1 ){
				$(obj).toggleClass("active");

				fcom.displaySuccessMessage(ans.msg);
				/* setTimeout(function(){
					reloadList();
				}, 1000); */
			} else {
				alert("Danger");
				fcom.displa(ans.msg);
			}
		});
		$.systemMessage.close();
	};

	toggleBulkStatues = function(status){
		if(!confirm(langLbl.confirmUpdateStatus)){
			return false;
		}
		$("#frmProdCatListing input[name='status']").val(status);
		$("#frmProdCatListing").submit();
	};

	deleteSelected = function(){
		if(!confirm(langLbl.confirmDelete)){
			return false;
		}
		$("#frmProdCatListing").attr("action",fcom.makeUrl('ProductCategories','deleteSelected')).submit();
	};

	clearSearch = function(){
		document.frmSearch.reset();
		searchProductCategories(document.frmSearch);
	};

	bannerPopupImage = function(inputBtn){
		if (inputBtn.files && inputBtn.files[0]) {
	        fcom.ajax(fcom.makeUrl('Shops', 'imgCropper'), '', function(t) {
				$('#cropperBox-js').html(t);
				$("#mediaForm-js").css("display", "none");
				var container = document.querySelector('.img-container');
                var file = inputBtn.files[0];
                $('#new-img').attr('src', URL.createObjectURL(file));
	    		var image = container.getElementsByTagName('img').item(0);
	            var minWidth = document.frmCategoryBanner.banner_min_width.value;
	            var minHeight = document.frmCategoryBanner.banner_min_height.value;
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
		    	return cropImage(image, options, 'uploadCatImages', inputBtn);
	    	});
		}
	};

    iconPopupImage = function(inputBtn){
		if (inputBtn.files && inputBtn.files[0]) {
	        fcom.ajax(fcom.makeUrl('Shops', 'imgCropper'), '', function(t) {
				$('#cropperBox-js').html(t);
				$("#mediaForm-js").css("display", "none");
				var container = document.querySelector('.img-container');
                var file = inputBtn.files[0];
                $('#new-img').attr('src', URL.createObjectURL(file));
	    		var image = container.getElementsByTagName('img').item(0);
	            var minWidth = document.frmCategoryIcon.logo_min_width.value;
	            var minHeight = document.frmCategoryIcon.logo_min_height.value;
	    		var options = {
	                aspectRatio: 1 / 1,
	                data: {
	                    width: minWidth,
	                    height: minHeight,
	                },
	                minCropBoxWidth: minWidth,
	                minCropBoxHeight: minHeight,
	                toggleDragModeOnDblclick: false,
		        };
				$(inputBtn).val('');
    	  		return cropImage(image, options, 'uploadCatImages', inputBtn);
	    	});
		}
	};

	uploadCatImages = function(formData){
        var node = this;
        $('#form-upload').remove();
        var frmName = formData.get("frmName");
		var slideScreen = 0;
		if(frmName == 'frmCategoryIcon'){
			var langId = document.frmCategoryIcon.lang_id.value;
			var prodcatId = document.frmCategoryIcon.prodcat_id.value;
			var fileType = document.frmCategoryIcon.file_type.value;
			var imageType = 'icon';
		} else {
			var langId = document.frmCategoryBanner.lang_id.value;
			var prodcatId = document.frmCategoryBanner.prodcat_id.value;
			var fileType = document.frmCategoryBanner.file_type.value;
			slideScreen = document.frmCategoryBanner.slide_screen.value;
			var imageType = 'banner';
		}
		formData.append('prodcat_id', prodcatId);
        formData.append('slide_screen', slideScreen);
        formData.append('lang_id', langId);
        formData.append('file_type', fileType);
        /* $val = $(node).val(); */
        $.ajax({
            url: fcom.makeUrl('ProductCategories', 'setUpCatImages'),
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
				if(ans.status == 1){
					fcom.displaySuccessMessage(ans.msg);
					$('#form-upload').remove();
					categoryMediaForm(prodcatId);
					categoryImages(prodcatId, imageType, slideScreen, langId);
				}else{
					fcom.displayErrorMessage(ans.msg);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
        });
	}

})();
