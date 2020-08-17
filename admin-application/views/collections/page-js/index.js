$(document).ready(function() {
    searchCollection(document.frmSearch);
    $(document).on("click", ".language-js", function(){
        $(".CollectionImages-js li").addClass('d-none');
        $('#Image-'+$(this).val()).removeClass('d-none');
    });
    $(document).on("click", ".bgLanguage-js", function(){
        $(".bgCollectionImages-js li").addClass('d-none');
        $('#bgImage-'+$(this).val()).removeClass('d-none');
    });
});

(function() {
    var runningAjaxReq = false;
    var dv = '#listing';

    reloadList = function() {
        var frm = document.frmSearch;
        searchCollection(frm);
    };

    goToSearchPage = function(page) {
        if (typeof page == undefined || page == null) {
            page = 1;
        }
        var frm = document.frmCollectionSearchPaging;
        $(frm.page).val(page);
        searchCollection(frm);
    };
    getCollectionTypeLayout = function(frm, collectionType, searchForm) {


        callCollectionTypePopulate(collectionType);


        fcom.ajax(fcom.makeUrl('Collections', 'getCollectionTypeLayout', [collectionType, searchForm]), '', function(t) {
            $("#" + frm + " [name=collection_layout_type]").html(t);
        });
    }
    searchCollection = function(form) {
        /*[ this block should be before dv.html('... anything here.....') otherwise it will through exception in ie due to form being removed from div 'dv' while putting html*/
        var data = '';
        if (form) {
            data = fcom.frmData(form);
        }
        /*]*/
        $(dv).html(fcom.getLoader());

        fcom.ajax(fcom.makeUrl('Collections', 'search'), data, function(res) {
            $(dv).html(res);
        });
    };

    collectionForm = function(type, layoutType, id) {
        fcom.displayProcessing();
        fcom.ajax(fcom.makeUrl('Collections', 'form', [type, layoutType, id]), '', function(t) {
            fcom.updateFaceboxContent(t);
        });
    };

    collectionLayouts = function() {
        fcom.ajax(fcom.makeUrl('Collections', 'layouts'), '', function(t) {
            fcom.updateFaceboxContent(t, 'content fbminwidth faceboxWidth');
        });
    };

    setupCollection = function(frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('Collections', 'setup'), data, function(t) {
            reloadList();
			if(t.openBannersForm) {
            	bannerForm(t.collectionId);
            	return;
            }
            if(t.openRecordForm) {
            	recordForm(t.collectionId, t.collectionType);
            	return;
            }
            if(t.openMediaForm) {
            	collectionMediaForm(t.collectionId);
            	return;
            }
            $(document).trigger('close.facebox');
        });
    }

    deleteRecord = function(id) {
        if (!confirm(langLbl.confirmDelete)) {
            return;
        }
        data = 'collectionId=' + id;
        fcom.updateWithAjax(fcom.makeUrl('Collections', 'deleteRecord'), data, function(res) {
            reloadList();
        });
    };

    toggleStatus = function(e, obj, canEdit) {
        if (canEdit == 0) {
            e.preventDefault();
            return;
        }
        if (!confirm(langLbl.confirmUpdateStatus)) {
            e.preventDefault();
            return;
        }
        var collectionId = parseInt(obj.value);
        if (collectionId < 1) {
            fcom.displayErrorMessage(langLbl.invalidRequest);
            return false;
        }
        data = 'collectionId=' + collectionId;
        fcom.ajax(fcom.makeUrl('Collections', 'changeStatus'), data, function(res) {
            var ans = $.parseJSON(res);
            if (ans.status == 1) {
                fcom.displaySuccessMessage(ans.msg);
            } else {
                fcom.displayErrorMessage(ans.msg);
            }
        });
    };

    recordForm = function(id, type) {
        $.facebox(function() {
            fcom.ajax(fcom.makeUrl('Collections', 'recordForm', [id, type]), '', function(t) {
                $.facebox(t, 'faceboxWidth');
                reloadRecordsList(id, type);
            });
        });
    };
	
	bannerForm = function(collection_id) {
        $.facebox(function() {
            fcom.ajax(fcom.makeUrl('Collections', 'bannerForm', [collection_id]), '', function(t) {
                $.facebox(t, 'faceboxWidth');
                reloadRecordsList(t.collection_id, t.collection_type);
            });
        });
    };

    reloadRecordsList = function(collection_id, collection_type) {
        $("#records_list").html(fcom.getLoader());
        fcom.ajax(fcom.makeUrl('Collections', 'collectionRecords', [collection_id, collection_type]), '', function(t) {
            $("#records_list").html(t);
        });
    };


    updateRecord = function(collection_id, record_id) {
        fcom.updateWithAjax(fcom.makeUrl('Collections', 'updateCollectionRecords'), 'collection_id=' + collection_id + '&record_id=' + record_id, function(t) {
            reloadRecordsList(t.collection_id, t.collection_type);
        });
    };

    removeCollectionRecord = function(collection_id, record_id) {
        var agree = confirm(langLbl.confirmRemoveProduct);
        if (!agree) {
            return false;
        }
        fcom.updateWithAjax(fcom.makeUrl('Collections', 'removeCollectionRecord'), 'collection_id=' + collection_id + '&record_id=' + record_id, function(t) {
            reloadRecordsList(collection_id, t.collection_type);
        });
    };

    collectionMediaForm = function(collectionId) {
        fcom.ajax(fcom.makeUrl('Collections', 'mediaForm', [collectionId]), '', function(t) {
            $.facebox(t);
            var parentSiblings = $(".displayMediaOnly--js").closest("div.row").siblings('div.row:not(:first)');
            if (0 < $(".displayMediaOnly--js:checked").val()) {
                parentSiblings.show();
            } else {
                parentSiblings.hide();
            }
        });
    };

    removeCollectionImage = function(collectionId, langId) {
        if (!confirm(langLbl.confirmDeleteImage)) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('Collections', 'removeImage', [collectionId, langId]), '', function(t) {
            collectionMediaForm(collectionId);
        });
    };

    removeCollectionBGImage = function(collectionId, langId) {
        if (!confirm(langLbl.confirmDeleteImage)) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('Collections', 'removeBgImage', [collectionId, langId]), '', function(t) {
            collectionMediaForm(collectionId);
        });
    };

    clearSearch = function() {
        document.frmSearch.reset();
        searchCollection(document.frmSearch);
        var collectionType = 0;
        fcom.ajax(fcom.makeUrl('Collections', 'getCollectionTypeLayout', [collectionType, 1]), '', function(t) {
            $("[name=collection_layout_type]").html(t);
        });
    };
    callCollectionTypePopulate = function(val) {
        if (val == 1) {
            $("#collection_criteria_div").show();
        } else {
            $("#collection_criteria_div").hide();
        }
    };

    deleteSelected = function(){
        if(!confirm(langLbl.confirmDelete)){
            return false;
        }
        $("#frmCollectionListing").attr("action",fcom.makeUrl('Collections','deleteSelected')).submit();
    };

    displayMediaOnly = function(collectionId, obj) {
        var parentSiblings = $(obj).closest("div.row").siblings('div.row:not(:first)');
        var value = (obj.checked) ? 1 : 0;
        fcom.ajax(fcom.makeUrl('Collections', 'displayMediaOnly', [collectionId, value]), '', function(t) {
			var ans = $.parseJSON(t);
            if(0 == ans.status){
                $.systemMessage(ans.msg,'alert--danger');
                $(obj).prop('checked', false);
                return false
            } else{
                (0 < value) ? parentSiblings.show() : parentSiblings.hide();
            }
		});
    };

    popupImage = function(inputBtn){
        if (inputBtn.files && inputBtn.files[0]) {
            fcom.ajax(fcom.makeUrl('Collections', 'imgCropper'), '', function(t) {
    			$('#cropperBox-js').html(t);
    			$("#mediaForm-js").css("display", "none");
                var file = inputBtn.files[0];
                var minWidth = document.frmCollectionMedia.min_width.value;
                var minHeight = document.frmCollectionMedia.min_height.value;
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
                return cropImage(file, options, 'uploadImages', inputBtn);
        	});
        }
	};

	uploadImages = function(formData){
        var collection_id = document.frmCollectionMedia.collection_id.value;
        var langId = document.frmCollectionMedia.image_lang_id.value;
        var fileType = document.frmCollectionMedia.file_type.value;

        formData.append('collection_id', collection_id);
        formData.append('file_type', fileType);
        formData.append('lang_id', langId);
        $.ajax({
            url: fcom.makeUrl('Collections', 'uploadImage'),
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
                if(0 == ans.status){
                    $.mbsmessage.close();
                    $.systemMessage(ans.msg,'alert--danger');
                } else {
                    collectionMediaForm(ans.collection_id);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
	}
    
    translateData = function(item){
        var autoTranslate = $("input[name='auto_update_other_langs_data']:checked").length;
        var defaultLang = $(item).attr('defaultLang');
        var catName = $("input[name='collection_name["+defaultLang+"]']").val();
        var toLangId = $(item).attr('language');
        var alreadyOpen = $('#collapse_'+toLangId).hasClass('active');
        if(autoTranslate == 0 || catName == "" || alreadyOpen == true){
            return false;
        }
        var data = "collectionName="+catName+"&toLangId="+toLangId ;
        fcom.updateWithAjax(fcom.makeUrl('Collections', 'translatedData'), data, function(t) {
            if(t.status == 1){
                $("input[name='collection_name["+toLangId+"]']").val(t.collectionName);
            }
        });
    }
    
    translateBannerData = function(item){
        var autoTranslate = $("input[name='auto_update_other_langs_data']:checked").length;
        var defaultLang = $(item).attr('defaultLang');
        var title = $("input[name='banner_title["+defaultLang+"]']").val();
        var toLangId = $(item).attr('language');
        var alreadyOpen = $('#collapse_'+toLangId).hasClass('active');
        if(autoTranslate == 0 || title == "" || alreadyOpen == true){
            return false;
        }
        var data = "collectionName="+title+"&toLangId="+toLangId ;
        fcom.updateWithAjax(fcom.makeUrl('Collections', 'translatedData'), data, function(t) {
            if(t.status == 1){
                $("input[name='banner_title["+toLangId+"]']").val(t.collectionName);
            }
        });
    }

})();

$(document).on('click', '.File-Js', function() {
    var node = this;
    $('#form-upload').remove();
    var fileType = $(node).attr('data-file_type');
    var collection_id = $(node).attr('data-collection_id');

    if (fileType == FILETYPE_COLLECTION_IMAGE) {
        var langId = document.frmCollectionMedia.image_lang_id.value;
    } else if (fileType == FILETYPE_COLLECTION_BG_IMAGE) {
        var langId = document.frmCollectionMedia.bg_image_lang_id.value;
    }

    var frm = '<form enctype="multipart/form-data" id="form-upload" style="position:absolute; top:-100px;" >';
    frm = frm.concat('<input type="file" name="file" />');
    frm = frm.concat('<input type="hidden" name="file_type" value="' + fileType + '">');
    frm = frm.concat('<input type="hidden" name="collection_id" value="' + collection_id + '">');
    frm = frm.concat('<input type="hidden" name="lang_id" value="' + langId + '">');
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
                url: fcom.makeUrl('Collections', 'uploadImage'),
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
                    if(0 == ans.status){
            			$.mbsmessage.close();
            			$.systemMessage(ans.msg,'alert--danger');
            		} else {
                        collectionMediaForm(ans.collection_id);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    }, 500);
});

(function() {
    displayImageInFacebox = function(str) {
        $.facebox('<img class="mx-auto d-block" width="800px;" src="' + str + '">');
    }
})();
