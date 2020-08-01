$(document).ready(function(){
	searchProductCategories(document.frmSearch);

	$('input[name=\'user_name\']').autocomplete({
        'classes': {
            "ui-autocomplete": "custom-ui-autocomplete"
        },
		'source': function(request, response) {
			$.ajax({
				url: fcom.makeUrl('Users', 'autoCompleteJson'),
				data: {keyword: request['term'], fIsAjax:1},
				dataType: 'json',
				type: 'post',
				success: function(json) {
					response($.map(json, function(item) {
						return { label: item['name'] +'(' + item['username'] + ')', value: item['name'] +'(' + item['username'] + ')', id: item['id'] };
					}));
				},
			});
		},
		select: function(event, ul) {
			$("input[name='user_id']").val( ul.item.id );
		}
	});
});
/* $(document).on('change','.logo-language-js',function(){
	var lang_id = $(this).val();
	var category_id = $(this).closest("form").find('input[name="prodcat_id"]').val();
	categoryImages(category_id, 'logo', 0, lang_id);
});
$(document).on('change','.image-language-js',function(){
	var lang_id = $(this).val();
	var category_id = $(this).closest("form").find('input[name="category_id"]').val();
	var slide_screen = $(".prefDimensions-js").val();
	categoryImages(category_id, 'image', slide_screen, lang_id);
});
$(document).on('change','.prefDimensions-js',function(){
	var slide_screen = $(this).val();
	var category_id = $(this).closest("form").find('input[name="category_id"]').val();
	var lang_id = $(".image-language-js").val();
	categoryImages(category_id, 'image', slide_screen, lang_id);
}); */
(function() {
	var currentPage = 1;
	var runningAjaxReq = false;

	goToSearchPage = function(page) {
		if(typeof page==undefined || page == null){
			page =1;
		}
		var frm = document.frmCategorySearchPaging;
		$(frm.page).val(page);
		searchProductCategories(frm);
	}

	reloadList = function() {
		var frm = document.frmCategorySearchPaging;
		searchProductCategories(frm);
	}

	setupCategory = function(frm) {
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('ProductCategories', 'setupRequest'), data, function(t) {
			reloadList();
			if (t.openMediaForm) {
				categoryRequestMediaForm(t.categoryId);
				return;
			}
			/* $(document).trigger('close.facebox'); */
		});
	};

	categoryRequestLangForm = function(categoryId, langId, autoFillLangData = 0) {
        fcom.displayProcessing();
        fcom.ajax(fcom.makeUrl('ProductCategories', 'requestLangForm', [categoryId, langId, autoFillLangData]), '', function(t) {
            fcom.updateFaceboxContent(t);
        });
    };

	searchProductCategories = function(form){
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
		$("#listing").html('Loading....');
		fcom.ajax(fcom.makeUrl('ProductCategories', 'searchRequests'),data,function(res){
			$("#listing").html(res);
		});
	};

    categoryRequestMediaForm = function(categoryId){
		fcom.displayProcessing();
        fcom.ajax(fcom.makeUrl('ProductCategories', 'requestMedia', [categoryId]), '', function(t) {
            categoryImages(categoryId, 'logo', 1);
            categoryImages(categoryId, 'image', 1);
            fcom.updateFaceboxContent(t);
        });
	};

	categoryImages = function(categoryId, fileType, slide_screen, langId){
		fcom.ajax(fcom.makeUrl('ProductCategories', 'images', [categoryId, fileType, langId, slide_screen]), '', function(t) {
			if(fileType=='logo') {
				$('#logo-listing').html(t);
			} else {
				$('#image-listing').html(t);
			}
			fcom.resetFaceboxHeight();
		});
	};

	deleteRecord = function(id){
		if(!confirm(langLbl.confirmDelete)){return;}
		data='id='+id;
		fcom.ajax(fcom.makeUrl('ProductCategories','deleteRecord'),data,function(res){
			reloadList();
		});
	};

	clearSearch = function(){
		document.frmSearch.reset();
		searchProductCategories(document.frmSearch);
	};

	deleteMedia = function( categoryId, fileType, langId, slide_screen ){
		if(!confirm(langLbl.confirmDelete)){return;}
		fcom.updateWithAjax(fcom.makeUrl('ProductCategories', 'removeBrandMedia',[categoryId, fileType, langId, slide_screen]), '', function(t) {
			categoryImages(categoryId,fileType,slide_screen,langId);
			reloadList();
		});
	};

	addBrandRequestForm= function(id){

		$.facebox(function() {categoryRequestForm(id)

		});
	}
	categoryRequestForm = function(id) {
		fcom.displayProcessing();
		var frm = document.frmCategorySearchPaging;
			fcom.ajax(fcom.makeUrl('ProductCategories', 'requestForm', [id]), '', function(t) {
				fcom.updateFaceboxContent(t);
		});
	};

	showHideCommentBox = function(val){
		if(val == 2){
			$('#div_comments_box').removeClass('hide');
		}else{
			$('#div_comments_box').addClass('hide');
		}
	};

	bannerPopupImage = function(inputBtn){
		if (inputBtn.files && inputBtn.files[0]) {
	        fcom.ajax(fcom.makeUrl('ProductCategories', 'imgCropper'), '', function(t) {
				$('#cropperBox-js').html(t);
				$("#mediaForm-js").css("display", "none");
                var file = inputBtn.files[0];
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
    	  		return cropImage(file, options, 'uploadBrandImages', inputBtn);
	    	});
		}
	};

    logoPopupImage = function(inputBtn){
		if (inputBtn.files && inputBtn.files[0]) {
	        fcom.ajax(fcom.makeUrl('ProductCategories', 'imgCropper'), '', function(t) {
				$('#cropperBox-js').html(t);
				$("#mediaForm-js").css("display", "none");
                var file = inputBtn.files[0];
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
    	  		return cropImage(file, options, 'uploadBrandImages', inputBtn);
	    	});
		}
	};

	uploadBrandImages = function(formData){
        var frmName = formData.get("frmName");
        if ('frmBrandLogo' == frmName) {
			var categoryId = document.frmBrandLogo.category_id.value;
            var langId = document.frmBrandLogo.lang_id.value;
            var fileType = document.frmBrandLogo.file_type.value;
            var imageType = 'logo';
        } else {
			var categoryId = document.frmBrandImage.category_id.value;
            var langId = document.frmBrandImage.lang_id.value;
            var slideScreen = document.frmBrandImage.slide_screen.value;
            var fileType = document.frmBrandImage.file_type.value;
            var imageType = 'banner';
        }

		formData.append('category_id', categoryId);
        formData.append('slide_screen', slideScreen);
        formData.append('lang_id', langId);
        formData.append('file_type', fileType);
        $.ajax({
            url: fcom.makeUrl('ProductCategories', 'uploadMedia'),
            type: 'post',
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                 $('#loader-js').html(fcom.getLoader());
            },
            complete: function() {
                 $('#loader-js').html(fcom.getLoader());
            },
			success: function(ans) {
				$('.text-danger').remove();
				$('#input-field').html(ans.msg);
				if( ans.status == true ){
					$('#input-field').removeClass('text-danger');
					$('#input-field').addClass('text-success');
					$('#form-upload').remove();
					categoryRequestMediaForm(ans.categoryId);
					categoryImages(ans.categoryId,imageType,langId);
				}else{
					$('#input-field').removeClass('text-success');
					$('#input-field').addClass('text-danger');
				}
				reloadList();
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
        });
	}

})();
