$(document).ready(function(){
	searchFiles(document.frmSearch);
	$("input[name='user']").autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: fcom.makeUrl('UploadBulkImages', 'autoCompleteSellerJson'),
				data: {keyword: request},
				dataType: 'json',
				type: 'post',
				success: function(json) {
					response($.map(json, function(item) {
						var email = '';
						if( null !== item['credential_email'] ){
							email = ' ('+item['credential_email']+')';
						}
						return { label: item['seller'] + email,	value: item['product_seller_id']	};
					}));
				},
			});
		},
		'select': function(item) {
			$("input[name='user']").val( item['label'] );
			$("input[name='afile_record_id']").val( item['value'] );
		}
	});
});

(function() {
	var runningAjaxReq = false;

	searchFiles = function(frm){
		if( runningAjaxReq == true ){
			return;
		}
		runningAjaxReq = true;
		/*[ this block should be before dv.html('... anything here.....') otherwise it will through exception in ie due to form being removed from div 'dv' while putting html*/
		var data = '';
		if (frm) {
			data = fcom.frmData(frm);
		}
		/*]*/
		var dv = $('#listing');
		$(dv).html( fcom.getLoader() );

		fcom.ajax(fcom.makeUrl('UploadBulkImages','search'),data,function(res){
			runningAjaxReq = false;
			$("#listing").html(res);
		});
	};

	goToSearchPage = function(page) {
		if(typeof page==undefined || page == null){
			page =1;
		}
		var frm = document.frmSearchPaging;
			$(frm.page).val(page);
		searchFiles(frm);
	};

	uploadZip = function() {
        var data = new FormData();
        $.each($('#bulk_images')[0].files, function(i, file) {
            fcom.displayProcessing(langLbl.processing, ' ', true);
            data.append('bulk_images', file);
            $.ajax({
                url: fcom.makeUrl('UploadBulkImages', 'upload'),
                type: "POST",
                data: data,
                processData: false,
                contentType: false,
                success: function(t) {
					try {
                        var ans = $.parseJSON(t);
                        if (ans.status == 1) {
                            $(document).trigger('close.facebox');
                            $(document).trigger('close.mbsmessage');
                            fcom.displaySuccessMessage(ans.msg, 'alert--success', false);
							document.uploadBulkImages.reset();
							$("#uploadFileName").text('');
							searchFiles(document.frmSearch);
                        } else {
                            $(document).trigger('close.mbsmessage');
                            fcom.displayErrorMessage(ans.msg);
                        }
                    } catch (exc) {
                        $(document).trigger('close.mbsmessage');
                        fcom.displayErrorMessage(t);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error Occured.");
                }
            });
        });
    };

	uploadBulkMediaForm = function() {
        $.facebox(function() {
            uploadForm();
        });
    };

    uploadForm = function() {
        fcom.displayProcessing();
        fcom.ajax(fcom.makeUrl('UploadBulkImages', 'uploadForm'), '', function(t) {
            fcom.updateFaceboxContent(t, 'faceboxWidth');
        });
    };

	removeDir = function(dir) {
		if ( true == confirm( langLbl.confirmDelete ) ) {
	        fcom.displayProcessing();
	        fcom.ajax(fcom.makeUrl('UploadBulkImages', 'removeDir', [dir] ), '', function(t) {
				var ans = $.parseJSON(t);
				if (ans.status == 1) {
					$(document).trigger('close.facebox');
					$(document).trigger('close.mbsmessage');
					fcom.displaySuccessMessage(ans.msg, 'alert--success', false);
					setTimeout(function(){ location.reload(); }, 500);
				} else {
					$(document).trigger('close.mbsmessage');
					fcom.displayErrorMessage(ans.msg);
				}
	        });
		}
    };

	clearSearch = function(){
		document.frmSearch.reset();
		searchFiles(document.frmSearch);
	};
})();
