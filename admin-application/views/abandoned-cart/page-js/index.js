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
    
    
    sendDiscountNotification = function(user_id, product_id){
        addCouponForm(0);
        userId = user_id;
        productId = product_id;
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
			sendDiscountNotification(userId, t.couponId);
            if (t.langId>0) {
				addCouponLangForm(t.couponId, t.langId);
				return ;
			}
			$(document).trigger('close.facebox');
		});
	};
    
    
    addCouponLangForm = function(couponId, langId, autoFillLangData = 0) {	
		fcom.displayProcessing();	
			fcom.ajax(fcom.makeUrl('DiscountCoupons', 'langForm', [couponId, langId, autoFillLangData]), '', function(t) {
				fcom.updateFaceboxContent(t);
			});
	};
    
    setupCouponLang = function(frm){ 
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);		
		fcom.updateWithAjax(fcom.makeUrl('DiscountCoupons', 'langSetup'), data, function(t) {		
			if (t.langId>0) {
				addCouponLangForm(t.couponId, t.langId);
				return ;
			}
			if(t.openMediaForm)
			{
				couponMediaForm(t.couponId);
				return;
			}
			$(document).trigger('close.facebox');
		});
	};
    
    couponMediaForm = function(couponId){
		fcom.displayProcessing();
        fcom.ajax(fcom.makeUrl('DiscountCoupons', 'media', [couponId]), '', function(t) {
            couponImages(couponId);
            fcom.updateFaceboxContent(t);
        });
	};
    
    couponImages = function(couponId,lang_id){
		fcom.ajax(fcom.makeUrl('DiscountCoupons', 'images', [couponId,lang_id]), '', function(t) {
			$('#image-listing').html(t);
			fcom.resetFaceboxHeight();
		});
	};
    
    deleteImage = function(couponId, langId){
		var agree = confirm(langLbl.confirmDeleteImage);
		if(!agree){ return false; }
		fcom.updateWithAjax(fcom.makeUrl('DiscountCoupons', 'removeCouponImage'), 'coupon_id='+couponId+'&lang_id='+langId, function(t) {
			couponImages(couponId,langId);
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
    
    sendDiscountNotification = function(userId,couponId){
        var data = 'userId='+userId+'&couponId='+couponId;
        fcom.updateWithAjax(fcom.makeUrl('AbandonedCart', 'sendDiscountNotification'), data, function(t) {		
		});
    }

})();

$(document).on('change','.language-js',function(){
/* $(document).delegate('.language-js','change',function(){ */
	var lang_id = $(this).val();
	var coupon_id = $("input[name='coupon_id']").val();
	couponImages(coupon_id,lang_id);
});

$(document).on('click','.couponFile-Js',function(){
	var node = this;
	$('#form-upload').remove();	
	var coupon_id = document.frmCouponMedia.coupon_id.value;
	var lang_id = document.frmCouponMedia.lang_id.value;
	var frm = '<form enctype="multipart/form-data" id="form-upload" style="position:absolute; top:-100px;" >';
	frm = frm.concat('<input type="file" name="file" />'); 
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
				url: fcom.makeUrl('DiscountCoupons', 'uploadImage',[coupon_id, lang_id]),
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).val('Loading..');
				},
				complete: function() {
					$(node).val($val);
				},
				success: function( ans ) {
					if( !ans.status ){
						fcom.displayErrorMessage(ans.msg);
						//$.systemMessage( ans.msg, 'alert--danger' );
						return;
					}
					fcom.displaySuccessMessage(ans.msg);
					//$.systemMessage( ans.msg, 'alert--success' );
					$('#form-upload').remove();
					couponImages( ans.coupon_id, lang_id );
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});			
		}
	}, 500);
});