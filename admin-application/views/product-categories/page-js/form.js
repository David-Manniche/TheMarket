$(function() {		$('input[name=\'path\']').autocomplete({		'source': function(request, response) {			$.ajax({				url: fcom.makeUrl('productCategories', 'autocomplete',[1]),				data: {keyword: encodeURIComponent(request),fIsAjax:1 },				dataType: 'json',				type: 'post',				success: function(json) {					json.unshift({						prodcat_id: 0,						prodcat_identifier: '--- None ---'					});					response($.map(json, function(item) {						return {							label: item['prodcat_identifier'],							value: item['prodcat_id']						}					}));				}			});		},		'select': function(item) {			$('input[name=\'path\']').val(item['label']);			$('input[name=\'prodcat_parent\']').val(item['value']);		}	});             setupCategory = function() {        var frm = $('#frmProdCategory');        var validator = $(frm).validation({errordisplay: 3});        if (validator.validate() == false) {            return false;        }                   if (!$(frm).validate()) {            return false;         }        var data = fcom.frmData(frm);          fcom.updateWithAjax(fcom.makeUrl('ProductCategories', 'setup'), data, function(t) {            if(t.status == 1){                window.location.href = fcom.makeUrl('ProductCategories');            }        });        	};        categoryImages = function(prodCatId,imageType,slide_screen,lang_id){		fcom.ajax(fcom.makeUrl('ProductCategories', 'images', [prodCatId,imageType,lang_id,slide_screen]), '', function(t) {			if(imageType=='icon') {				$('#icon-image-listing').html(t);                var prodCatId = $("[name='prodcat_id']").val();                if(prodCatId == 0){                    var iconImageId = $("#icon-image-listing li").attr('id');                      var selectedLangId = $(".icon-language-js").val();                    $("[name='cat_icon_image_id["+selectedLangId+"]']").val(iconImageId);                }			} else if(imageType=='banner') {				$('#banner-image-listing').html(t);                 var bannerImageId = $("#banner-image-listing li").attr('id');                 var selectedLangId = $(".banner-language-js").val();                var screen = $(".prefDimensions-js").val();                $("[name='cat_banner_image_id["+selectedLangId+"_"+screen+"]']").val(bannerImageId);			}		});	};        deleteImage = function(fileId, prodcatId, imageType, langId, slide_screen){		if( !confirm(langLbl.confirmDeleteImage) ){ return; }		fcom.updateWithAjax(fcom.makeUrl('productCategories', 'removeImage',[fileId,prodcatId,imageType,langId,slide_screen]), '', function(t) {			categoryImages( prodcatId, imageType, slide_screen, langId );		});	};        translateData = function(item){        var autoTranslate = $("input[name='auto_update_other_langs_data']:checked").length;                       var defaultLang = $(item).attr('defaultLang');        var catName = $("input[name='prodcat_name["+defaultLang+"]']").val();        var selectedLangId = $(item).attr('language');        var alreadyOpen = $('#collapse_'+selectedLangId).hasClass('show');                if(autoTranslate == 0 || catName == "" || alreadyOpen == true){            return false;        }                        var data = "catName="+catName+"&selectedLangId="+selectedLangId ;        fcom.updateWithAjax(fcom.makeUrl('ProductCategories', 'translatedCategoryData'), data, function(t) {            if(t.status == 1){                $("input[name='prodcat_name["+selectedLangId+"]']").val(t.prodCatName);            }        });     }    });$(document).ready(function(){    var prodCatId = $("[name='prodcat_id']").val();    if(prodCatId > 0 ){        categoryImages(prodCatId,'icon',1);        categoryImages(prodCatId,'banner',1);    }});$(document).on('click','.catFile-Js',function(){	var node = this;	$('#form-upload').remove();	var formName = $(node).attr('data-frm');	var slide_screen = 0;	if(formName == 'catIcon'){		var lang_id = $("[name='icon_lang_id']").val();		var prodcat_id = $("[name='prodcat_id']").val();		var imageType = 'icon';	}else if(formName == 'catBanner'){		var lang_id = $("input[name='banner_lang_id']").val();		var prodcat_id = $("[name='prodcat_id']").val();		slide_screen = $("[name='slide_screen']").val();		var imageType = 'banner';	}	var fileType = $(node).attr('data-file_type');	var frm = '<form enctype="multipart/form-data" id="form-upload" style="position:absolute; top:-100px;" >';	frm = frm.concat('<input type="file" name="file" />');	frm = frm.concat('<input type="hidden" name="file_type" value="' + fileType + '">');	frm = frm.concat('<input type="hidden" name="prodcat_id" value="' + prodcat_id + '">');	frm = frm.concat('<input type="hidden" name="lang_id" value="' + lang_id + '">');	frm = frm.concat('<input type="hidden" name="slide_screen" value="' + slide_screen + '">');	frm = frm.concat('</form>');	$('body').prepend(frm);	$('#form-upload input[name=\'file\']').trigger('click');	if (typeof timer != 'undefined') {		clearInterval(timer);	}	timer = setInterval(function() {		if ($('#form-upload input[name=\'file\']').val() != '') {			clearInterval(timer);			$val = $(node).val();			$.ajax({				url: fcom.makeUrl('ProductCategories', 'setUpCatImages'),				type: 'post',				dataType: 'json',				data: new FormData($('#form-upload')[0]),				cache: false,				contentType: false,				processData: false,				beforeSend: function() {					$(node).val('Loading');				},				complete: function() {					$(node).val($val);					fcom.resetFaceboxHeight();				},				success: function(ans) {						if(ans.status == 1){							fcom.displaySuccessMessage(ans.msg);							$('#form-upload').remove();							categoryImages(prodcat_id,imageType,slide_screen,lang_id);						}else{							fcom.displayErrorMessage(ans.msg);						}					},					error: function(xhr, ajaxOptions, thrownError) {						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);					}				});		}	}, 500);});$(document).on('change','.icon-language-js',function(){	var lang_id = $(this).val();	var prodcat_id = $("input[name='prodcat_id']").val();	categoryImages(prodcat_id,'icon',0,lang_id);});$(document).on('change','.banner-language-js',function(){	var lang_id = $(this).val();	var prodcat_id = $("input[name='prodcat_id']").val();	var slide_screen = $(".prefDimensions-js").val();	categoryImages(prodcat_id,'banner',slide_screen,lang_id);});$(document).on('change','.prefDimensions-js',function(){	var slide_screen = $(this).val();	var prodcat_id = $("input[name='prodcat_id']").val();	var lang_id = $(".banner-language-js").val();	categoryImages(prodcat_id,'banner',slide_screen,lang_id);});