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

	clearSearch = function(){
		document.frmSearch.reset();
		searchProductCategories(document.frmSearch);
	};
    
    toggleStatus = function(obj){
		if( !confirm(langLbl.confirmUpdateStatus) ){ return; }
		var prodCatId = parseInt(obj.value);
		if( prodCatId < 1 ){
			fcom.displayErrorMessage(langLbl.invalidRequest);
			return false;
		}
        
		data='prodCatId='+prodCatId;
		fcom.displayProcessing();
		fcom.ajax(fcom.makeUrl('productCategories','changeRequestStatus'),data,function(res){
		var ans = $.parseJSON(res);
			if( ans.status == 1 ){
				$(obj).toggleClass("active");
				fcom.displaySuccessMessage(ans.msg);
                searchProductCategories();
			} else {
                fcom.displayErrorMessage(ans.msg);
			}
		});
		$.systemMessage.close();
	};

})();
