$(document).ready(function(){
    $('.dvFocus-js').hide();
    searchUpsellProducts(document.frmSearch);
    $('#upsell-products').delegate('.remove_upsell', 'click', function() {
        $(this).parent().remove();
    });
});
$(document).on('mouseover', "ul.list-tags li span i", function(){
    $(this).parents('li').addClass("hover");
});
$(document).on('mouseout', "ul.list-tags li span i", function(){
    $(this).parents('li').removeClass("hover");
});
$(document).on('click', ".dvFocus-js", function(){
   var input = $("input[name='product_name']");
    input.show().focus();
    var tmpStr = input.val();
    input.val('');
    input.val(tmpStr);
    $('.dvFocus-js').hide();
    $(this).html('');
});
$(document).on('keyup', "input[name='product_name']", function(){
    var currObj = $(this);
    var parentForm = currObj.closest('form').attr('id');
    if('' != currObj.val()){
        currObj.siblings('ul.dropdown-menu').remove();
        currObj.autocomplete({'source': function(request, response) {
        		$.ajax({
        			url: fcom.makeUrl('SellerProducts', 'autoCompleteProducts'),
        			data: {keyword: request,fIsAjax:1,keyword:currObj.val()},
        			dataType: 'json',
        			type: 'post',
        			success: function(json) {
        				response($.map(json, function(item) {
        					return { label: item['name'], value: item['id']	};
        				}));
        			},
        		});
        	},
        	'select': function(item) {
                $("#"+parentForm+" input[name='selprod_id']").val(item['value']);
                currObj.val( item['label'] );
                fcom.ajax(fcom.makeUrl('SellerProducts', 'getUpsellProductsList', [item['value']]), '', function(t) {
                    var ans = $.parseJSON(t);
                    $('#upsell-products').empty();
                    for (var key in ans.upsellProducts) {
                        $("#upsell-products").append(
                            "<li id=productUpsell"+ans.upsellProducts[key]['selprod_id']+"><span>"+ans.upsellProducts[key]['selprod_title']+" ["+ans.upsellProducts[key]['product_identifier']+"]<i class=\"remove_upsell remove_param fal fa-times\"></i></span><input type=\"hidden\" name=\"selected_products[]\" value="+ans.upsellProducts[key]['selprod_id']+" /></li>"
                        );
                    }
                });
                $('.dvFocus-js').html(item['label']).show();
                currObj.hide();
        	}
        });
    }else{
        $("#"+parentForm+" input[name='selprod_id']").val('');
    }
});

$(document).on('keyup', "input[name='products_upsell']", function(){
    var currObj = $(this);
    var parentForm = currObj.closest('form').attr('id');
    var selprod_id = $("#"+parentForm+" input[name='selprod_id']").val();
    var selected_products = [];
    $('input[name="selected_products[]"]').each(function() {
        selected_products.push($(this).val());
    });
    if(selprod_id != 0) {
        currObj.siblings('ul.dropdown-menu').remove();
        currObj.autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: fcom.makeUrl('SellerProducts', 'autoCompleteProducts'),
                    data: {
                        keyword: request,
                        fIsAjax: 1,
                        selprod_id: selprod_id,
                        selected_products: selected_products
                    },
                    dataType: 'json',
                    type: 'post',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['name'] + '[' + item['product_identifier'] + ']',
                                value: item['id']
                            };
                        }));
                    },
                });
            },
            'select': function(item) {
                if(selprod_id == 0){
                    return;
                }
                $('input[name=\'products_upsell\']').val('');
                $('#productUpsell' + item['value']).remove();
                $('#upsell-products').append('<li id="productUpsell' + item['value'] + '"><span> ' + item['label'] + '<i class="remove_upsell remove_param fal fa-times"></i></span><input type="hidden" name="selected_products[]" value="' +
                    item['value'] + '" /></li>');
            }
        });
    }
});

$(document).on('click', 'table.volDiscountList-js tr td .js--editCol', function(){
    $(this).hide();
    var input = $(this).siblings('input[type="text"]');
    var value = input.val();
    input.removeClass('hidden');
    input.val('').focus().val(value);
});

$(document).on('blur', ".js--volDiscountCol", function(){
    var currObj = $(this);
    var value = currObj.val();
    var oldValue = currObj.attr('data-oldval');
    var attribute = currObj.attr('name');
    var id = currObj.data('id');
    var selProdId = currObj.data('selprodid');
    if ('' != value && parseFloat(value) != parseFloat(oldValue)) {
        var data = 'attribute='+attribute+"&voldiscount_id="+id+"&selProdId="+selProdId+"&value="+value;
        fcom.ajax(fcom.makeUrl('SellerProducts', 'updateUpsellProductColValue'), data, function(t) {
            var ans = $.parseJSON(t);
            if( ans.status != 1 ){
                $.systemMessage(ans.msg, 'alert--danger', true);
                value = updatedValue = oldValue;
            } else {
                updatedValue = ans.data.value;
                currObj.attr('data-oldval', value);
            }
            currObj.val(value);
            showElement(currObj, updatedValue);
        });
    } else {
        showElement(currObj);
        currObj.val(oldValue);
    }
    return false;
});

(function() {
	var dv = '#listing';
	searchUpsellProducts = function(frm){

		/*[ this block should be before dv.html('... anything here.....') otherwise it will through exception in ie due to form being removed from div 'dv' while putting html*/
		var data = '';
		if (frm) {
			data = fcom.frmData(frm);
		}
		/*]*/
		var dv = $('#listing');
		$(dv).html( fcom.getLoader() );

		fcom.ajax(fcom.makeUrl('SellerProducts','searchUpsellProducts'),data,function(res){
			$("#listing").html(res);
		});
	};

    clearSearch = function(selProd_id){
        if (0 < selProd_id) {
            location.href = fcom.makeUrl('SellerProducts','upsellProducts');
        } else {
    		document.frmSearch.reset();
    		searchUpsellProducts(document.frmSearch);
        }
	};

    goToSearchPage = function(page) {
		if(typeof page==undefined || page == null){
			page =1;
		}
		var frm = document.frmSearchSpecialPricePaging;
		$(frm.page).val(page);
		searchUpsellProducts(frm);
	}

	reloadList = function() {
		var frm = document.frmUpsellSellerProduct;
		searchUpsellProducts(frm);
	}

    deleteSelprodUpsellProduct = function( selProdId, relProdId ){
		var agree = confirm(langLbl.confirmDelete);
		if( !agree ){
			return false;
		}
		fcom.updateWithAjax(fcom.makeUrl('SellerProducts', 'deleteSelprodUpsellProduct', [selProdId, relProdId] ), '', function(t) {
            /* $('form#frmVolDiscountListing table tr#row-'+voldiscount_id).remove();
            if (1 > $('form#frmVolDiscountListing table tbody tr').length) {
                searchUpsellProducts(document.frmSearch);
            } */
            // $('#upsell-products').empty();
            searchUpsellProducts(document.frmUpsellSellerProduct);
		});
	}

    updateUpsellProductsRow = function(frm, selProd_id){
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('SellerProducts', 'updateUpsellProductsRow'), data, function(t) {
            if(t.status == true){
                if ((1 > frm.addMultiple.value) || 0 < selProd_id) {
                    if (1 > selProd_id) {
                        frm.elements["selprod_id"].value = '';
                    }
                    frm.reset();
                }
                document.getElementById('frmVolDiscountListing').reset()
                $('table.volDiscountList-js tbody').prepend(t.data);
                if (0 < $('.noResult--js').length) {
                    $('.noResult--js').remove();
                }
            }
			$(document).trigger('close.facebox');
            if (0 < frm.addMultiple.value) {
                var volDisRow = $("#"+frm.id).parent().parent();
                volDisRow.siblings('.divider:first').remove();
                volDisRow.remove();
            }
		});
		return false;
	};
    showElement = function(currObj, value){
        var sibling = currObj.siblings('div');
        if ('' != value){
            sibling.text(value);
        }
        sibling.fadeIn();
        currObj.addClass('hidden');
    };

    setUpSellerProductLinks = function(frm){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('SellerProducts', 'setupUpsellProduct'), data, function(t) {
            document.frmUpsellSellerProduct.reset();
            $('#upsell-products').empty();
            $(".dvFocus-js").trigger('click');
            searchUpsellProducts(document.frmUpsellSellerProduct);
		});
	};
})();