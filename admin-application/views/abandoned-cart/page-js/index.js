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
    
    $(document).on('click','ul.linksvertical li a.redirect--js',function(event){
		event.stopPropagation();
	});
    
});


(function() {
	var currentPage = 1;
	var userId = 0;
    var productId = 0;
    var selProdId = 0;
    var action = 0;
    
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
    
    
    discountNotification = function(user_id, action_type, product_id, selprod_id){
        addCouponForm(0);
        userId = user_id;
        action = action_type
        productId = product_id;
        selProdId = selprod_id;
    }
    
    
    addCouponForm = function(id) {			
		$.facebox(function() {
			fcom.displayProcessing();		
			fcom.ajax(fcom.makeUrl('DiscountCoupons', 'form', [id]), '', function(t) { 
				fcom.updateFaceboxContent(t);
			});
		});
	};
    
    callCouponTypePopulate = function(val){
		if( val == 1 ){
			$("#coupon_minorder_div").show();
			$("#coupon_validfor_div").hide();
			
		}if( val == 3 ){
			$("#coupon_minorder_div").hide();
			$("#coupon_validfor_div").show();
		}
	};

    callCouponDiscountIn = function(val){
        if( val == DISCOUNT_IN_PERCENTAGE ){
            $("#coupon_max_discount_value_div").show();
        }
        if( val == DISCOUNT_IN_FLAT ){
            $("#coupon_max_discount_value_div").hide();
        }
    }
    
    setupCoupon = function(frm) { 
		if (!$(frm).validate()) return;		
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('DiscountCoupons', 'setup'), data, function(t) {  
            updateCouponUser(t.couponId, userId);
            updateCouponProduct(t.couponId, productId);
			sendDiscountNotification(t.couponId, action, userId, selProdId);
            $(document).trigger('close.facebox');
		});
	};

    updateCouponUser = function(couponId,userId){
		var data = 'coupon_id='+couponId+'&user_id='+userId;
		fcom.updateWithAjax(fcom.makeUrl('DiscountCoupons', 'updateCouponUser'), data, function(t) {		
		});
	};
    
    updateCouponProduct = function(couponId,productId){
		var data = 'coupon_id='+couponId+'&product_id='+productId;
		fcom.updateWithAjax(fcom.makeUrl('DiscountCoupons', 'updateCouponProduct'), data, function(t) {		
		});
	};

    sendDiscountNotification = function(couponId, action, userId, selProdId){
        var data = 'couponId='+couponId+'&action='+action+'&userId='+userId+'&selProdId='+selProdId;
        fcom.updateWithAjax(fcom.makeUrl('AbandonedCart', 'discountNotification'), data, function(t) {            
            searchAbandonedCart(document.frmAbandonedCartSearch);
        });
    } 

})();

