$(document).ready(function(){
    $('.dvFocus-js').hide();
    searchRelatedProducts(document.frmSearch);
    $('#related-products').delegate('.remove_related', 'click', function() {
        $(this).parents('li').remove();
    });
    $("input[name='product_name']").trigger('click');
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
$(document).on('click', "input[name='product_name']", function(){
    var currObj = $(this);
    var parentForm = currObj.closest('form').attr('id');
    /*if('' != currObj.val()){
         if(currObj.val().length < 2){
            return;
        } */
        currObj.siblings('ul.dropdown-menu').remove();
        currObj.autocomplete({
                minLength : 0,
                'classes': {
                    "ui-autocomplete": "custom-ui-autocomplete"
                },
                'source': function(request, response) {
        		$.ajax({
        			url: fcom.makeUrl('Seller', 'autoCompleteProducts'),
        			data: {keyword: request['term'],fIsAjax:1,keyword:currObj.val()},
        			dataType: 'json',
        			type: 'post',
        			success: function(json) {
        				response($.map(json, function(item) {
        					return { label: item['name'], value: item['name'], id: item['id'] };
        				}));
        			},
        		});
        	},
        	select: function (event, ui) {
                $("#"+parentForm+" input[name='selprod_id']").val(ui.item.id);
                currObj.val(ui.item.label);
                fcom.ajax(fcom.makeUrl('Seller', 'getRelatedProductsList', [ui.item.id]), '', function(t) {
                    var ans = $.parseJSON(t);
                    $('#related-products').empty();
                    for (var key in ans.relatedProducts) {
                        $('#related-products').append(
                            "<li id=productRelated"+ans.relatedProducts[key]['selprod_id']+"><span>"+ans.relatedProducts[key]['selprod_title']+" ["+ans.relatedProducts[key]['product_identifier']+"]<i class=\"remove_related remove_param fas fa-times\"></i><input type=\"hidden\" name=\"selected_products[]\" value="+ans.relatedProducts[key]['selprod_id']+" /></span></li>"
                        );
                    }
                });
                $('.dvFocus-js').html(ui.item.label).show();
                currObj.hide();
                $("input[name='products_related']").trigger('click');
                $("input[name='products_related']").focus();
        	}
        }).focus(function() {
            currObj.autocomplete("search", currObj.val());
        });
    /*}else{
        $("#"+parentForm+" input[name='selprod_id']").val('');
    }*/
});

$(document).on('click', "input[name='products_related']", function(){
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
            minLength : 0,
            autoFocus: true,
            'classes': {
                "ui-autocomplete": "custom-ui-autocomplete"
            },
            'source': function(request, response) {
                $.ajax({
                    url: fcom.makeUrl('seller', 'autoCompleteProducts'),
                    data: {
                        keyword: request['term'],
                        fIsAjax: 1,
                        selprod_id: selprod_id,
                        selected_products: selected_products
                    },
                    dataType: 'json',
                    type: 'post',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return { label: item['name'] + '[' + item['product_identifier'] + ']', value: item['name'] + '[' + item['product_identifier'] + ']', id: item['id'] };
                        }));
                    },
                });
            },
            select: function (event, ui) {
                $('input[name=\'products_related\']').val('');
                $('#productRelated' + ui.item.id).remove();
                $('#related-products').append('<li id="productRelated' + ui.item.id + '"><span> ' + ui.item.label + '<i class="remove_related remove_param fas fa-times"></i><input type="hidden" name="selected_products[]" value="' +
                    ui.item.id + '" /></span></li>');
                // currObj.focus();
                return false;
            }
        }).focus(function() {
            currObj.autocomplete("search", currObj.val());
        });
    }
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
		var frm = document.frmSearchRelatedProductsPaging;
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
            searchRelatedProducts(document.frmRelatedSellerProduct);
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
		fcom.updateWithAjax(fcom.makeUrl('Seller', 'setupRelatedProduct'), data, function(t) {
            document.frmRelatedSellerProduct.reset();
            $("input[name='selprod_id']").val(''); 
            $('#related-products').empty();
            $(".dvFocus-js").trigger('click');
            searchRelatedProducts(document.frmRelatedSellerProduct);
		});
	};
})();

$(document).on('click', ".js-product-edit", function(){
    var selProdId = $(this).attr('row-id');
    var prodHtml = $(this).children('.js-prod-name').html(); 
    var prodName = prodHtml.split('<br>');
    
    fcom.ajax(fcom.makeUrl('Seller', 'getRelatedProductsList', [selProdId]), '', function(t) {
        var ans = $.parseJSON(t);
        $("input[name='selprod_id']").val(selProdId); 
        $("input[name='product_name']").val(prodName[0]); 
        $('#related-products').empty();
        for (var key in ans.relatedProducts) {
            $('#related-products').append(
                "<li id=productRelated"+ans.relatedProducts[key]['selprod_id']+"><span>"+ans.relatedProducts[key]['selprod_title']+" ["+ans.relatedProducts[key]['product_identifier']+"]<i class=\"remove_related remove_param fas fa-times\"></i><input type=\"hidden\" name=\"selected_products[]\" value="+ans.relatedProducts[key]['selprod_id']+" /></span></li>"
            );
        }
    });
});
