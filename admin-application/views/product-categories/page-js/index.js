$(document).ready(function(){
	searchProductCategories(document.frmSearch);
});

(function() {
	var currentPage = 1;
	var runningAjaxReq = false;

	reloadList = function() {
		searchProductCategories(document.frmSearch);
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

	deleteRecord = function(id){
		if(!confirm(langLbl.confirmDelete)){return;}
		data='id='+id;
		fcom.ajax(fcom.makeUrl('productCategories','deleteRecord'),data,function(res){
			var ans = $.parseJSON(res);
			if( ans.status == 1 ){
				fcom.displaySuccessMessage(ans.msg);
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
			} else {
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
		reloadList();
	};
    
    displaySubCategories = function(anchorTag, level){
        var prodCatId = $(anchorTag).parent().parent().attr('id');
        if($(anchorTag).children().hasClass('ion-chevron-down')){
            $("."+prodCatId).each(function(){
                var rootCat = $(this).attr('id');
                $("."+rootCat).remove();
            });
            $("."+prodCatId).remove();
            $(anchorTag).children().removeClass('ion-chevron-down');
            $(anchorTag).children().addClass('ion-chevron-right');
            return false;
        }
        var data = 'prodCatId='+prodCatId+'&level='+level;		
        fcom.ajax(fcom.makeUrl('productCategories','getSubCategories'),data,function(res){
            $("."+prodCatId).remove();
            $("#"+prodCatId).after(res);
            $(anchorTag).children().removeClass('ion-chevron-right');
            $(anchorTag).children().addClass('ion-chevron-down');
        });
    }

})();

$(document).on('click', 'input[type="checkbox"]', function(){
    if($(this).prop("checked") == true){
        $('.display-link-js').removeClass('d-none');
    }else{
        $('.display-link-js').addClass('d-none');
    } 
});



