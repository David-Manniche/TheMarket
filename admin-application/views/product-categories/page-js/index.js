$(document).ready(function(){
	searchProductCategories();
    getTotalBlock();
});

(function() {
	var currentPage = 1;
	var runningAjaxReq = false;
    var dv = "#listing";

    searchProductCategories = function(){
		var data = '';
		fcom.ajax(fcom.makeUrl('productCategories','search'),data,function(res){
			$(dv).html(res);
		});
	};

    getTotalBlock = function(){
		var data = '';
		fcom.ajax(fcom.makeUrl('productCategories','getTotalBlock'),data,function(res){
			$("#total-block").html(res);
		});
	};

    categoryForm = function(prodCatId){
        var data = '';
		fcom.ajax(fcom.makeUrl('productCategories','form', [prodCatId]),data,function(res){
            $(dv).html(res);
            if(prodCatId > 0){
                categoryImages(prodCatId,'icon',1);
                categoryImages(prodCatId,'banner',1);
            }
		});
    }

    setupCategory = function() {
        var frm = $('#frmProdCategory');
        var validator = $(frm).validation({errordisplay: 3});
        if (validator.validate() == false) {
            return false;
        }
        if (!$(frm).validate()) {
            return false;
        }
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('ProductCategories', 'setup'), data, function(t) {
            if(t.status == 1){
                searchProductCategories();
                getTotalBlock();
            }
        });
	};

    discardForm = function() {
        searchProductCategories();
        getTotalBlock();
    }

	deleteRecord = function(id){
		if(!confirm(langLbl.confirmDelete)){return;}
		data='id='+id;
		fcom.ajax(fcom.makeUrl('productCategories','deleteRecord'),data,function(res){
			var ans = $.parseJSON(res);
			if( ans.status == 1 ){
				fcom.displaySuccessMessage(ans.msg);
                searchProductCategories();
                getTotalBlock();
			} else {
				fcom.displayErrorMessage(ans.msg);
			}
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
		var prodCatId = parseInt(obj.value);
		if( prodCatId < 1 ){
			fcom.displayErrorMessage(langLbl.invalidRequest);
			return false;
		}
		data='prodCatId='+prodCatId;
		fcom.displayProcessing();
		fcom.ajax(fcom.makeUrl('productCategories','changeStatus'),data,function(res){
		var ans = $.parseJSON(res);
			if( ans.status == 1 ){
				$(obj).toggleClass("active");
				fcom.displaySuccessMessage(ans.msg);
                searchProductCategories();
                getTotalBlock();
			} else {
				fcom.displa(ans.msg);
			}
		});
		$.systemMessage.close();
	};

    displaySubCategories = function(obj, catId = 0){
        if(catId > 0 ){
            var prodCatId = catId;
        }else{
            var prodCatId = $(obj).parent().parent().parent().attr('id');
        }

        if($("#"+prodCatId).hasClass('no-children')){
            return false;
        }

        if($("#"+prodCatId+ ' ul li.child-category').length){
            $("#"+prodCatId+ ' ul').show();
            togglePlusMinus(prodCatId);
            return false;
        }

        fcom.ajax(fcom.makeUrl('productCategories','getSubCategories'), 'prodCatId='+prodCatId, function(res){
            $("#"+prodCatId).append('<ul>'+res+'</ul>');
            if(catId == 0){
                togglePlusMinus(prodCatId);
            }
        });
    }

   togglePlusMinus = function(prodCatId){
        $("#"+prodCatId).children( 'div' ).children( '.sortableListsOpener' ).remove();
        if($("#"+prodCatId).hasClass('sortableListsClosed')){
            $("#"+prodCatId).removeClass('sortableListsClosed').addClass('sortableListsOpen');
            $("#"+prodCatId).children( 'div' ).append('<span class="sortableListsOpener" ><i class="fa fa-minus clickable sort-icon" onClick="hideItems(this)"></i></span>');
        }else{
            $("#"+prodCatId).removeClass('sortableListsOpen').addClass('sortableListsClosed');
            $("#"+prodCatId).children( 'div' ).append('<span class="sortableListsOpener" ><i class="fa fa-plus c3 clickable sort-icon" onClick="displaySubCategories(this)"></i></span>');

        }
   }

   hideItems = function(obj){
        var prodCatId = $(obj).parent().parent().parent().attr('id');
        $("#"+prodCatId+ ' ul').hide();
        $("#"+prodCatId).removeClass('sortableListsOpen').addClass('sortableListsClosed');
        var icon = $("#"+prodCatId).children( 'div' ).children( '.sortableListsOpener' ).remove();
        $("#"+prodCatId).children( 'div' ).append('<span class="sortableListsOpener" ><i class="fa fa-plus c3 clickable sort-icon" onClick="displaySubCategories(this)"></i></span>');
   }

   categoryImages = function(prodCatId,imageType,slide_screen,lang_id){
		fcom.ajax(fcom.makeUrl('ProductCategories', 'images', [prodCatId,imageType,lang_id,slide_screen]), '', function(t) {
			if(imageType=='icon') {
				$('#icon-image-listing').html(t);
                var prodCatId = $("[name='prodcat_id']").val();
                if(prodCatId == 0){
                    var iconImageId = $("#icon-image-listing li").attr('id');
                    var selectedLangId = $(".icon-language-js").val();
                    $("[name='cat_icon_image_id["+selectedLangId+"]']").val(iconImageId);
                }
			} else if(imageType=='banner') {
				$('#banner-image-listing').html(t);
                var bannerImageId = $("#banner-image-listing li").attr('id');
                var selectedLangId = $(".banner-language-js").val();
                var screen = $(".prefDimensions-js").val();
                $("[name='cat_banner_image_id["+selectedLangId+"_"+screen+"]']").val(bannerImageId);
			}
		});
	};

    deleteImage = function(fileId, prodcatId, imageType, langId, slide_screen){
		if( !confirm(langLbl.confirmDeleteImage) ){ return; }
		fcom.updateWithAjax(fcom.makeUrl('productCategories', 'removeImage',[fileId,prodcatId,imageType,langId,slide_screen]), '', function(t) {
			//categoryImages( prodcatId, imageType, slide_screen, langId );
            if(imageType == 'icon') {
                $("#icon-image-listing").html('');
                $("[name='cat_icon_image_id["+langId+"]']").val('');
			} else if(imageType == 'banner') {
                $("#banner-image-listing").html('');
                $("[name='cat_banner_image_id["+langId+"_"+slide_screen+"]']").val('');
            }
		});
	};

    translateData = function(item){
        var autoTranslate = $("input[name='auto_update_other_langs_data']:checked").length;
        var defaultLang = $(item).attr('defaultLang');
        var catName = $("input[name='prodcat_name["+defaultLang+"]']").val();
        var toLangId = $(item).attr('language');
        var alreadyOpen = $('#collapse_'+toLangId).hasClass('active');
        if(autoTranslate == 0 || catName == "" || alreadyOpen == true){
            return false;
        }
        var data = "catName="+catName+"&toLangId="+toLangId ;
        fcom.updateWithAjax(fcom.makeUrl('ProductCategories', 'translatedCategoryData'), data, function(t) {
            if(t.status == 1){
                $("input[name='prodcat_name["+toLangId+"]']").val(t.prodCatName);
            }
        });
    }

})();



$(document).on('click','.catFile-Js',function(){
	var node = this;
	$('#form-upload').remove();
	var formName = $(node).attr('data-frm');
	var slide_screen = 0;
	if(formName == 'catIcon'){
		var lang_id = $("[name='icon_lang_id']").val();
		var prodcat_id = $("[name='prodcat_id']").val();
		var imageType = 'icon';
        var afile_id = $("#icon-image-listing li").attr('id');
	}else if(formName == 'catBanner'){
		var lang_id = $("[name='banner_lang_id']").val();
		var prodcat_id = $("[name='prodcat_id']").val();
		var slide_screen = $("[name='slide_screen']").val();
		var imageType = 'banner';
        var afile_id = $("#banner-image-listing li").attr('id');
	}

	var fileType = $(node).attr('data-file_type');


	var frm = '<form enctype="multipart/form-data" id="form-upload" style="position:absolute; top:-100px;" >';
	frm = frm.concat('<input type="file" name="file" />');
	frm = frm.concat('<input type="hidden" name="file_type" value="' + fileType + '">');
	frm = frm.concat('<input type="hidden" name="prodcat_id" value="' + prodcat_id + '">');
	frm = frm.concat('<input type="hidden" name="lang_id" value="' + lang_id + '">');
	frm = frm.concat('<input type="hidden" name="slide_screen" value="' + slide_screen + '">');
    frm = frm.concat('<input type="hidden" name="afile_id" value="' + afile_id + '">');
	frm = frm.concat('</form>');
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
				url: fcom.makeUrl('ProductCategories', 'setUpCatImages'),
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
					fcom.resetFaceboxHeight();

				},
				success: function(ans) {
						if(ans.status == 1){
							fcom.displaySuccessMessage(ans.msg);
							$('#form-upload').remove();
							categoryImages(prodcat_id,imageType,slide_screen,lang_id);
						}else{
							fcom.displayErrorMessage(ans.msg);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
		}
	}, 500);
});

$(document).on('change','.icon-language-js',function(){
	var lang_id = $(this).val();
	var prodcat_id = $("input[name='prodcat_id']").val();
    var imageId = $("[name='cat_icon_image_id["+lang_id+"]']").val();
    if(prodcat_id == 0){
        if(imageId > 0){
            categoryImages(prodcat_id,'icon',0,lang_id);
        }else{
            $("#icon-image-listing").html('');
        }
    }else{
        categoryImages(prodcat_id,'icon',0,lang_id);
    }

});
$(document).on('change','.banner-language-js',function(){
	var lang_id = $(this).val();
	var prodcat_id = $("input[name='prodcat_id']").val();
	var slide_screen = $(".prefDimensions-js").val();
    var imageId = $("[name='cat_banner_image_id["+lang_id+"_"+slide_screen+"]']").val();
    if(prodcat_id == 0){
        if(imageId > 0 ){
            categoryImages(prodcat_id,'banner',slide_screen,lang_id);
        }else{
            $("#banner-image-listing").html('');
        }
    }else{
        categoryImages(prodcat_id,'banner',slide_screen,lang_id);
    }


});
$(document).on('change','.prefDimensions-js',function(){
	var slide_screen = $(this).val();
	var prodcat_id = $("input[name='prodcat_id']").val();
	var lang_id = $(".banner-language-js").val();
    var imageId = $("[name='cat_banner_image_id["+lang_id+"_"+slide_screen+"]']").val();
    if(prodcat_id == 0){
        if(imageId > 0 ){
            categoryImages(prodcat_id,'banner',slide_screen,lang_id);
        }else{
            $("#banner-image-listing").html('');
        }
    }else{
        categoryImages(prodcat_id,'banner',slide_screen,lang_id);
    }
});
