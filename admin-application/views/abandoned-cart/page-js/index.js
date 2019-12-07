$(document).ready(function(){
	searchAbandonedCart(document.frmAbandonedCartSearch);
	
	$('input[name=\'buyer\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: fcom.makeUrl('Users', 'autoCompleteJson'),
				data: {keyword: request, user_is_buyer: 1, fIsAjax:1},
				dataType: 'json',
				type: 'post',
				success: function(json) {
					response($.map(json, function(item) {
						return { label: item['credential_email']+' ('+item['username']+')' ,	value: item['id']	};
					}));
				},
			});
		},
		'select': function(item) {
			$("input[name='user_id']").val( item['value'] );
			$("input[name='buyer']").val( item['label'] );
		}
	});
	
	$('input[name=\'buyer\']').keyup(function(){
		if( $(this).val() == "" ){
			$("input[name='user_id']").val( "" );
		}
	});
	
	$(document).on('click','ul.linksvertical li a.redirect--js',function(event){
		event.stopPropagation();
	});	

});
(function() {
	var currentPage = 1;
	goToSearchPage = function(page) {
		if(typeof page==undefined || page == null){
			page = 1;
		}		
		var frm = document.frmOrderSearchPaging;		
		$(frm.page).val(page);
		searchOrders(frm);
	}
	
	frmAbandonedCartSearch = function(form,page){
		if (!page) {
			page = currentPage;
		}
		currentPage = page;	
		var dv = $('#ordersListing');		
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
		dv.html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('Orders','search'),data,function(res){
			dv.html(res);
		});
	};
		
	reloadOrderList = function() {
		searchOrders(document.frmOrderSearchPaging, currentPage);
	};
	
	clearOrderSearch = function(){
		document.frmAbandonedCartSearch.user_id.value = '';
		document.frmAbandonedCartSearch.reset();
		searchOrders(document.frmAbandonedCartSearch);
	};
})();