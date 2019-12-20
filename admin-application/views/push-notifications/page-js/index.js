$(document).ready(function(){
    listPushNotification(document.frmSearch);	
});

$(document).on("click", "ul#selectedUsersList-js .ion-close-round", function(){
    $(this).parent().remove();
    removeFromNotificationUsers($(this).siblings('.userId').val());
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
    
    addNotificationForm = function(pNotificationId){
        $.facebox(function() {
            fcom.ajax(fcom.makeUrl('PushNotifications', 'addNotificationForm', [pNotificationId]), '', function(t) {
                $.facebox(t,'faceboxWidth');
                $('.date_js').datepicker('option', {minDate: new Date()});
            });
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
                        keyword: request,
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
                                value: item['id'],
                                name: item['username']
                            };
                        }));
                    },
                });
            },
            'select': function(item) {
                $(userSelector).val('');
                var listSelector = 'ul#selectedUsersList-js';
                $(listSelector + ' #selectedUser-js-' + item['value']).remove();
                $(listSelector).append('<li id="selectedUser-js-' + item['value'] + '"><i class=" icon ion-close-round"></i> ' + item['label'] + '<input type="hidden" name="cntu_user_id[]" class="userId" value="' + item['value'] + '" /></li>');
                setupNotificationToUsers(item['value']);
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

    addSelectedUsersForm = function(pNotificationId){
        $.facebox(function() {
            fcom.ajax(fcom.makeUrl('PushNotifications', 'addSelectedUsersForm', [pNotificationId]), '', function(t) {
                $.facebox(t,'faceboxWidth');
                usersAutoComplete();
            });
        });
    };

    setupNotificationToUsers = function(userId) {
        var pNotificationId = $("input[name='pnotification_id']").val();
        if (pNotificationId == '' || 1 > pNotificationId) {
            $.systemMessage(langLbl.invalidRequest,'alert--danger',true);
            return false;
        }
        fcom.ajax(fcom.makeUrl('PushNotifications', 'setupNotificationToUsers', [pNotificationId, userId]), '', function(res) {});
    };
    removeFromNotificationUsers = function(userId) {
        var pNotificationId = $("input[name='pnotification_id']").val();
        if (pNotificationId == '' || 1 > pNotificationId) {
            $.systemMessage(langLbl.invalidRequest,'alert--danger',true);
            return false;
        }
        fcom.ajax(fcom.makeUrl('PushNotifications', 'removeFromNotificationUsers', [pNotificationId, userId]), '', function(res) {});
    };
})();