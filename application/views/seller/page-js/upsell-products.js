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
                currObj.val( item['label'] );
                fcom.ajax(fcom.makeUrl('Seller', 'getUpsellProductsList', [item['value']]), '', function(t) {
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

		fcom.ajax(fcom.makeUrl('Seller','searchUpsellProducts'),data,function(res){
			$("#listing").html(res);
		});
	};

    clearSearch = function(selProd_id){
        if (0 < selProd_id) {
            location.href = fcom.makeUrl('Seller','upsellProducts');
        } else {
    		document.frmSearch.reset();
    		searchUpsellProducts(document.frmSearch);
        }
	};

    goToSearchPage = function(page) {
		if(typeof page==undefined || page == null){
			page =1;
		}
		var frm = document.frmSearchUpsellProductsPaging;
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
		fcom.updateWithAjax(fcom.makeUrl('Seller', 'deleteSelprodUpsellProduct', [selProdId, relProdId] ), '', function(t) {
            searchUpsellProducts(document.frmUpsellSellerProduct);
		});
	}

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
		fcom.updateWithAjax(fcom.makeUrl('Seller', 'setupUpsellProduct'), data, function(t) {
            document.frmUpsellSellerProduct.reset();
            $('#upsell-products').empty();
            $(".dvFocus-js").trigger('click');
            searchUpsellProducts(document.frmUpsellSellerProduct);
		});
	};
})();