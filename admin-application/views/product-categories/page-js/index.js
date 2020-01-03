$(document).ready(function(){
	searchProductCategories(document.frmSearch);
});

(function() {
	var currentPage = 1;
	var runningAjaxReq = false;

	goToSearchPage = function(page) {
		if(typeof page==undefined || page == null){
			page =1;
		}
		var frm = document.frmCatSearchPaging;
		$(frm.page).val(page);
		searchProductCategories(frm);
	}

	reloadList = function() {
		var frm = document.frmCatSearchPaging;
		searchProductCategories(frm);
	}
    
	searchProductCategories = function(form){
		var data = '';
		if ( form ) {
			data = fcom.frmData(form);
		}

		$("#listing").html( fcom.getLoader() );
		fcom.ajax(fcom.makeUrl('productCategories','search'),data,function(res){
			$("#listing").html(res);
		});
	};

	subcat_list=function(parent){
		var frm = document.frmCatSearchPaging;
		$(frm.prodcat_parent).val(parent);
		reloadList();
	};

	deleteRecord = function(id){
		if(!confirm(langLbl.confirmDelete)){return;}
		data='id='+id;
		fcom.ajax(fcom.makeUrl('productCategories','deleteRecord'),data,function(res){
			var ans = $.parseJSON(res);
			if( ans.status == 1 ){
				fcom.displaySuccessMessage(ans.msg);
				reloadList();
			} else {
				fcom.displayErrorMessage(ans.msg);
			}
		});
	};

	toggleStatus = function(e,obj,canEdit){
		if(canEdit == 0){
			e.preventDefault();
			return;
		}
		if(!confirm(langLbl.confirmUpdateStatus)){
			e.preventDefault();
			return;
		}
		var prodcatId = parseInt(obj.value);
		if( prodcatId < 1 ){
			fcom.displayErrorMessage(langLbl.invalidRequest);
			return false;
		}
		data='prodcatId='+prodcatId;
		fcom.displayProcessing();
		fcom.ajax(fcom.makeUrl('productCategories','changeStatus'),data,function(res){
		var ans = $.parseJSON(res);
			if( ans.status == 1 ){
				$(obj).toggleClass("active");

				fcom.displaySuccessMessage(ans.msg);
				/* setTimeout(function(){
					reloadList();
				}, 1000); */
			} else {
				alert("Danger");
				fcom.displa(ans.msg);
			}
		});
		$.systemMessage.close();
	};

	toggleBulkStatues = function(status){
		if(!confirm(langLbl.confirmUpdateStatus)){
			return false;
		}
		$("#frmProdCatListing input[name='status']").val(status);
		$("#frmProdCatListing").submit();
	};

	deleteSelected = function(){
		if(!confirm(langLbl.confirmDelete)){
			return false;
		}
		$("#frmProdCatListing").attr("action",fcom.makeUrl('ProductCategories','deleteSelected')).submit();
	};

	clearSearch = function(){
		document.frmSearch.reset();
		searchProductCategories(document.frmSearch);
	};
    
    displaySubCategories = function(prodCatParent, anchorTag){
        if($("."+prodCatParent+"-subcategory").length){
            $("."+prodCatParent+"-subcategory").remove();
            $(anchorTag).children().removeClass('ion-chevron-down');
            $(anchorTag).children().addClass('ion-chevron-right');
            return false;
        }
        var data = 'prodCatParent='+prodCatParent;		
        fcom.ajax(fcom.makeUrl('productCategories','prodSubCategories'),data,function(res){
            $("."+prodCatParent+"-subcategory").remove();
            $("#"+prodCatParent).after(res);
            $(anchorTag).children().removeClass('ion-chevron-right');
            $(anchorTag).children().addClass('ion-chevron-down');
        });
    }

})();


