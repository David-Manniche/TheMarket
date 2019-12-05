$(document).ready(function(){
    listCustomNotification(document.frmSearch);	
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
		listCustomNotification(frm);
    };	

    clearSearch = function(){
        document.frmSearch.reset();
        listCustomNotification(document.frmSearch);
    };
	
	listCustomNotification = function(form, page){
		if (!page) {
			page = currentPage;
		}
		currentPage = page;	
		
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
			
		$("#listing").html(fcom.getLoader());
		
		fcom.ajax(fcom.makeUrl('CustomNotifications','search'),data,function(res){
			$("#listing").html(res);
		});
		$('.check-all').prop('checked', false);
    };
    
    addNotificationForm = function(cNotificationId){
        $.facebox(function() {
            fcom.ajax(fcom.makeUrl('CustomNotifications', 'addNotificationForm', [cNotificationId]), '', function(t) {
                $.facebox(t,'faceboxWidth');
                $('.date_js').datepicker('option', {minDate: new Date()});
            });
        });
    };

    usersAutoComplete = function(){
        var userSelector = "input[name='users']";
        $(userSelector).autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: fcom.makeUrl('Users', 'autoCompleteJson'),
                    data: {
                        keyword: request,
                        fIsAjax: 1
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
        fcom.updateWithAjax(fcom.makeUrl('CustomNotifications', 'setup'), data, function(t) {
            if(t.status) {
                $.systemMessage(t.msg,'alert--success',true);
                listCustomNotification(document.frmSearch);
                addNotificationForm(t.recordId);
            } else {
                $.systemMessage(t.msg,'alert--danger',true);
                $(document).trigger('close.facebox');
            }
        });
    };

    addSelectedUsersForm = function(cNotificationId){
        $.facebox(function() {
            fcom.ajax(fcom.makeUrl('CustomNotifications', 'addSelectedUsersForm', [cNotificationId]), '', function(t) {
                $.facebox(t,'faceboxWidth');
                usersAutoComplete();
            });
        });
    };

    setupNotificationToUsers = function(userId) {
        var cNotificationId = $("input[name='cnotification_id']").val();
        if (cNotificationId == '' || 1 > cNotificationId) {
            $.systemMessage(langLbl.invalidRequest,'alert--danger',true);
            return false;
        }
        fcom.ajax(fcom.makeUrl('CustomNotifications', 'setupNotificationToUsers', [cNotificationId, userId]), '', function(res) {});
    };
    removeFromNotificationUsers = function(userId) {
        var cNotificationId = $("input[name='cnotification_id']").val();
        if (cNotificationId == '' || 1 > cNotificationId) {
            $.systemMessage(langLbl.invalidRequest,'alert--danger',true);
            return false;
        }
        fcom.ajax(fcom.makeUrl('CustomNotifications', 'removeFromNotificationUsers', [cNotificationId, userId]), '', function(res) {});
    };
})();