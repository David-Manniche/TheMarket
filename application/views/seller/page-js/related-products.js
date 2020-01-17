$(document).ready(function(){
    searchRelatedProducts(document.frmSearch);
    $('#related-products').delegate('.remove_related', 'click', function() {
        $(this).parents('li').remove();
    });
});
$(document).on('mouseover', "ul.list-tags li span i", function(){
    $(this).parents('li').addClass("hover");
});
$(document).on('mouseout', "ul.list-tags li span i", function(){
    $(this).parents('li').removeClass("hover");
});
$(document).on('click', ".dvFocus-js", function(){
    $("input[name='product_name']").show().focus();
    $(this).html('');
});
$(document).on('keyup', "input[name='product_name']", function(){
    var currObj = $(this);
    var parentForm = currObj.closest('form').attr('id');
    if('' != currObj.val()){
        currObj.siblings('ul.dropdown-menu').remove();
        currObj.autocomplete({'source': function(request, response) {
        		$.ajax({
        			url: fcom.makeUrl('Seller', 'autoCompleteProducts'),
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
                currObj.val(item['label']);
                fcom.ajax(fcom.makeUrl('Seller', 'getRelatedProductsList', [item['value']]), '', function(t) {
                    var ans = $.parseJSON(t);
                    $('#related-products').empty();
                    for (var key in ans.relatedProducts) {
                        $('#related-products').append(
                            "<li id=productRelated"+ans.relatedProducts[key]['selprod_id']+"><span>"+ans.relatedProducts[key]['selprod_title']+" ["+ans.relatedProducts[key]['product_identifier']+"]<i class=\"remove_related remove_param fal fa-times\"></i></span><input type=\"hidden\" name=\"selected_products[]\" value="+ans.relatedProducts[key]['selprod_id']+" /></li>"
                        );
                    }
                });
                $('.dvFocus-js').html(item['label']);
                currObj.hide();
        	}
        });
    }else{
        $("#"+parentForm+" input[name='selprod_id']").val('');
    }
});

$(document).on('keyup', "input[name='products_related']", function(){
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
            autoFocus: true,
            'source': function(request, response) {
                $.ajax({
                    url: fcom.makeUrl('seller', 'autoCompleteProducts'),
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
                $('input[name=\'products_related\']').val('');
                $('#productRelated' + item['value']).remove();
                $('#related-products').append('<li id="productRelated' + item['value'] + '"><span> ' + item['label'] + '<i class="remove_related remove_param fal fa-times"></i></span><input type="hidden" name="selected_products[]" value="' +
                    item['value'] + '" /></li>');
                // currObj.focus();
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
        fcom.ajax(fcom.makeUrl('Seller', 'updateRelatedProductColValue'), data, function(t) {
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
	searchRelatedProducts = function(frm){

		/*[ this block should be before dv.html('... anything here.....') otherwise it will through exception in ie due to form being removed from div 'dv' while putting html*/
		var data = '';
		if (frm) {
			data = fcom.frmData(frm);
		}
		/*]*/
		var dv = $('#listing');
		$(dv).html( fcom.getLoader() );

		fcom.ajax(fcom.makeUrl('Seller','searchRelatedProducts'),data,function(res){
			$("#listing").html(res);
		});
	};
    clearSearch = function(selProd_id){
        if (0 < selProd_id) {
            location.href = fcom.makeUrl('Seller','relatedProducts');
        } else {
    		document.frmSearch.reset();
    		searchRelatedProducts(document.frmSearch);
        }
	};

    goToSearchPage = function(page) {
		if(typeof page==undefined || page == null){
			page =1;
		}
		var frm = document.frmSearchSpecialPricePaging;
		$(frm.page).val(page);
		searchRelatedProducts(frm);
	}

	reloadList = function() {
		var frm = document.frmRelatedSellerProduct;
		searchRelatedProducts(frm);
	}

    deleteSelprodRelatedProduct = function( selProdId, relProdId ){
		var agree = confirm(langLbl.confirmDelete);
		if( !agree ){
			return false;
		}
		fcom.updateWithAjax(fcom.makeUrl('Seller', 'deleteSelprodRelatedProduct', [selProdId, relProdId] ), '', function(t) {
            /* $('form#frmVolDiscountListing table tr#row-'+voldiscount_id).remove();
            if (1 > $('form#frmVolDiscountListing table tbody tr').length) {
                searchRelatedProducts(document.frmSearch);
            } */
            // $('#related-products').empty();
            searchRelatedProducts(document.frmRelatedSellerProduct);
		});
	}

    updateRelatedProductsRow = function(frm, selProd_id){
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('Seller', 'updateRelatedProductsRow'), data, function(t) {
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
		fcom.updateWithAjax(fcom.makeUrl('Seller', 'setupRelatedProduct'), data, function(t) {
            document.frmRelatedSellerProduct.reset();
            $('#related-products').empty();
            searchRelatedProducts(document.frmRelatedSellerProduct);
		});
	};
})();
