$(document).ready(function(){
    listPushNotification(document.frmSearch);
});

$(document).on("click", "ul#selectedUsersList-js .ion-close-round", function(){
    unlinkUser($(this).siblings('.userId').val(), this);
});

$(document).on('click','.uploadFile-Js',function(){
	var node = this;
	$('#form-upload').remove();

    var fileType = $(node).attr('data-file_type');
    var pNotificationId = $(node).attr('data-pnotification_id');

	var frm = '<form enctype="multipart/form-data" id="form-upload" style="position:absolute; top:-100px;" >';
	frm = frm.concat('<input type="file" name="file" />');
	frm = frm.concat('<input type="hidden" name="pnotification_id" value="' + pNotificationId + '"/>');
    frm = frm.concat('<input type="hidden" name="file_type" value="' + fileType + '">');
	frm = frm.concat('</form>');
	$( 'body' ).prepend( frm );
	$('#form-upload input[name=\'file\']').trigger('click');
	if ( typeof timer != 'undefined' ) {
		clearInterval(timer);
	}
	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);
			$val = $(node).val();
			$.ajax({
				url: fcom.makeUrl('PushNotifications', 'uploadMedia'),
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
						$('.text-danger').remove();
						$('#input-field').html(ans.msg);
						if( ans.status == true ){
							$('#input-field').removeClass('text-danger');
							$('#input-field').addClass('text-success');
							$('#form-upload').remove();
                            getMediaForm(pNotificationId);
						}else{
							$('#input-field').removeClass('text-success');
                            $('#input-field').addClass('text-danger');
                            $.systemMessage(ans.msg,'alert--danger',true);
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
	var currentPage = 1;
	
	goToSearchPage = function(page) {	
		if(typeof page == undefined || page == null){
			page = 1;
		}		
		var frm = document.frmSearchPaging;		
		$(frm.page).val(page);
		listPushNotification(frm);
    };	

    clearSearch = function(){
        document.frmSearch.reset();
        listPushNotification(document.frmSearch);
    };
	
	listPushNotification = function(form, page){
		if (!page) {
			page = currentPage;
		}
		currentPage = page;	
		
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
			
		$("#listing").html(fcom.getLoader());
		
		fcom.ajax(fcom.makeUrl('PushNotifications','search'),data,function(res){
			$("#listing").html(res);
		});
		$('.check-all').prop('checked', false);
    };
    
    brandForm = function(id) {
		fcom.displayProcessing();
		var frm = document.frmBrandSearchPaging;
        fcom.ajax(fcom.makeUrl('brands', 'form', [id]), '', function(t) {
            fcom.updateFaceboxContent(t);
        });
    };
    
    addNotificationForm = function(pNotificationId){
        fcom.ajax(fcom.makeUrl('PushNotifications', 'addNotificationForm', [pNotificationId]), '', function(t) {
            fcom.updateFaceboxContent(t);
            // $('.date_js').datepicker('option', {minDate: new Date()});
            $('.date_js').datetimepicker({
                minDate: new Date(),
                format: 'Y-m-d H:00'
           });
        });
    };
    
    clone = function(pNotificationId){
        if(!confirm(langLbl.cloneNotification)){
            return false;
        }
        fcom.ajax(fcom.makeUrl('PushNotifications', 'clone', [pNotificationId]), '', function(t) {
            listPushNotification(document.frmSearch);
            fcom.updateFaceboxContent(t);
            $.systemMessage(langLbl.clonedNotification,'alert--success',true);
            $('.date_js').datetimepicker({
                minDate: new Date(),
                format: 'Y-m-d H:00'
           });
        });
    };
    
    getMediaForm = function(pNotificationId){
        fcom.ajax(fcom.makeUrl('PushNotifications', 'addMediaForm', [pNotificationId]), '', function(t) {
            fcom.updateFaceboxContent(t);
        });
    };

    addSelectedUsersForm = function(pNotificationId){
        fcom.ajax(fcom.makeUrl('PushNotifications', 'addSelectedUsersForm', [pNotificationId]), '', function(t) {
            fcom.updateFaceboxContent(t);
            usersAutoComplete();
        });
    };

    usersAutoComplete = function(){
        var userSelector = "input[name='users']";
        var buyers = $(userSelector).data("buyers");
        var sellers = $(userSelector).data("sellers");
        $(userSelector).autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: fcom.makeUrl('Users', 'autoCompleteJson'),
                    data: {
                        keyword: request['term'],
                        fIsAjax: 1,
                        user_is_buyer : buyers,
                        user_is_supplier : sellers,
                    },
                    dataType: 'json',
                    type: 'post',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['name'] + '(' + item['username'] + ')',
                                value: item['username'],
                                id: item['id']
                            };
                        }));
                    },
                });
            },
            select: function(event, ui) {
                $(userSelector).val('');
                var listSelector = 'ul#selectedUsersList-js';
                $(listSelector + ' #selectedUser-js-' + ui.item.id).remove();
                $(listSelector).append('<li id="selectedUser-js-' + ui.item.id + '"><i class=" icon ion-close-round"></i> ' + ui.item.label + '<input type="hidden" name="pntu_user_id[]" class="userId" value="' + ui.item.id + '" /></li>');
                bindUser(ui.item.id);
                return false;
            }
        });
    }

    setup = function(frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('PushNotifications', 'setup'), data, function(t) {
            if(t.status) {
                $.systemMessage(t.msg,'alert--success',true);
                listPushNotification(document.frmSearch);
                addNotificationForm(t.recordId);
            } else {
                $.systemMessage(t.msg,'alert--danger',true);
                $(document).trigger('close.facebox');
            }
        });
    };

    bindUser = function(userId) {
        var pNotificationId = $("input[name='pnotification_id']").val();
        if (pNotificationId == '' || 1 > pNotificationId) {
            $.systemMessage(langLbl.invalidRequest,'alert--danger',true);
            return false;
        }
        fcom.ajax(fcom.makeUrl('PushNotifications', 'bindUser', [pNotificationId, userId]), '', function(res) {});
    };
    unlinkUser = function(userId, obj = '') {
        var pNotificationId = $("input[name='pnotification_id']").val();
        if (pNotificationId == '' || 1 > pNotificationId) {
            $.systemMessage(langLbl.invalidRequest,'alert--danger',true);
            return false;
        }
        fcom.ajax(fcom.makeUrl('PushNotifications', 'unlinkUser', [pNotificationId, userId]), '', function(res) {
            res = $.parseJSON(res);
            if (0 == res.status) {
                $.systemMessage(res.msg,'alert--danger',true);
                return false;
            }
            $(obj).parent().remove();
        });
    };
    
    removeImage = function(pNotificationId) {
        if (!confirm(langLbl.confirmDeleteImage)) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('PushNotifications', 'removeImage', [pNotificationId]), '', function(t) {
            getMediaForm(pNotificationId);
        });
    };
})();