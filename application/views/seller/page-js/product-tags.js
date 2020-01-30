$(document).ready(function(){
    searchCatalogProducts(document.frmSearchCatalogProduct);
});

$(document).on('keyup', "input[name='keyword']", function(){
    var parentForm = $(this).closest('form');
    parentForm.submit();
});

(function() {
	var dv = '#listing';
	searchCatalogProducts = function(frm){

		/*[ this block should be before dv.html('... anything here.....') otherwise it will through exception in ie due to form being removed from div 'dv' while putting html*/
		var data = '';
		if (frm) {
			data = fcom.frmData(frm);
		}
		/*]*/
		var dv = $('#listing');
		$(dv).html( fcom.getLoader() );

		fcom.ajax(fcom.makeUrl('Seller','searchCatalogProduct', [true]), data, function(res){
			$("#listing").html(res);
		});
	};
    clearSearch = function(selProd_id){
        if (0 < selProd_id) {
            location.href = fcom.makeUrl('Seller','volumeDiscount');
        } else {
    		document.frmSearchCatalogProduct.reset();
    		searchCatalogProducts(document.frmSearchCatalogProduct);
        }
	};
    goToCatalogProductSearchPage = function(page){
		if(typeof page==undefined || page == null){
			page = 1;
		}
		var frm = document.frmCatalogProductSearchPaging;
		$(frm.page).val(page);
		searchCatalogProducts(frm);
	}

	reloadList = function() {
		var frm = document.frmSearchCatalogProduct;
		searchCatalogProducts(frm);
	}

	/* getProductSeoGeneralForm = function (selprod_id){
		fcom.ajax(fcom.makeUrl('Seller', 'productSeoGeneralForm'), 'selprod_id='+selprod_id, function(t) {
			$("#dvForm").html(t);
		});
	} */

    editTagsLangForm = function(selprod_id, langId){
			fcom.ajax(fcom.makeUrl('seller', 'productSeoLangForm', [selprod_id, langId]), '', function(t) {
				$("#dvForm").html(t).show();
                $("#dvAlert").hide();
			});

	};

	setupProductLangMetaTag = function (frm, exit){
		if (!$(frm).validate()) return;
		var data = fcom.frmData(frm);
		fcom.updateWithAjax(fcom.makeUrl('seller', 'setupProdMetaLang'), data, function(t) {
			if (!exit && t.langId > 0) {
				editProductMetaTagLangForm(t.metaRecordId, t.langId);
				return ;
			} else {
                $("#dvForm").hide();
                $("#dvAlert").show();
            }
		});
	}

})();
