$(document).ready(function(){
	searchAbandonedCart(document.frmAbandonedCartSearch);
	
	$('input[name=\'user_name\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: fcom.makeUrl('Users', 'autoCompleteJson'),
				data: {keyword: request, fIsAjax:1},
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
			$("input[name='carthistory_user_id']").val( item['value'] );
			$("input[name='user_name']").val( item['label'] );
		}
	});
	
	$('input[name=\'user_name\']').keyup(function(){
		if( $(this).val() == "" ){
			$("input[name='carthistory_user_id']").val( "" );
		}
	});
    
    $('input[name=\'seller_product\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: fcom.makeUrl('sellerProducts', 'autoComplete'),
				data: {keyword: request, fIsAjax:1},
				dataType: 'json',
				type: 'post',
				success: function(json) {
					response($.map(json, function(item) {
						return { label: item['name'] ,	value: item['id']	};
					}));
				},
			});
		},
		'select': function(item) {
			$("input[name='carthistory_selprod_id']").val( item['value'] );
			$("input[name='seller_product']").val( item['label'] );
		}
	});
	
	$('input[name=\'seller_product\']').keyup(function(){
		if( $(this).val() == "" ){
			$("input[name='carthistory_selprod_id']").val( "" );
		}
	});
    
});


(function() {
	var currentPage = 1;
	
	searchAbandonedCart = function(form,page){
		if (!page) {
			page = currentPage;
		}
		currentPage = page;	
		var dv = $('#abandonedCartListing');		
		var data = '';
		if (form) {
			data = fcom.frmData(form);
		}
		dv.html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('AbandonedCart','search'),data,function(res){
			dv.html(res);
		});
	};
        
    goToSearchPage = function(page) {
		if(typeof page==undefined || page == null){
			page = 1;
		}		
		var frm = document.frmAbandonedCartSearch;		
		$(frm.page).val(page);
		searchAbandonedCart(frm);
	}    
    
	clearAbandonedCartSearch = function(){
        document.frmAbandonedCartSearch.carthistory_user_id.value = '';
		document.frmAbandonedCartSearch.carthistory_selprod_id.value = '';
        document.frmAbandonedCartSearch.reset();
		searchAbandonedCart(document.frmAbandonedCartSearch);
	};
    
})();