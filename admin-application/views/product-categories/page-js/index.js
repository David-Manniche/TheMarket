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
			} else {
				fcom.displa(ans.msg);
			}
		});
		$.systemMessage.close();
	};

	clearSearch = function(){
		document.frmSearch.reset();
		reloadList();
	};
    
    displaySubCategories = function(obj){
        var prodCatId = $(obj).parent().parent().parent().attr('id'); 
        var data = 'prodCatId='+prodCatId;		
        fcom.ajax(fcom.makeUrl('productCategories','getSubCategories'),data,function(res){
            $("#"+prodCatId+ ' ul').remove();
            $("#"+prodCatId).append(res);             
            if($("#"+prodCatId).hasClass('sortableListsClosed')){
                $("#"+prodCatId).removeClass('sortableListsClosed');
                $("#"+prodCatId).addClass('sortableListsOpen');
                $("#"+prodCatId).children().children('.sortableListsOpener').html('');
                $("#"+prodCatId).children().children('.sortableListsOpener').html('<i class="fa fa-minus clickable" onClick="hideItems(this)"></i>');
            }else{
                $("#"+prodCatId).removeClass('sortableListsOpen');
                $("#"+prodCatId).addClass('sortableListsClosed');
                $("#"+prodCatId+" .sortableListsOpener").html('');
                $("#"+prodCatId+" .sortableListsOpener").html('<i class="fa fa-plus clickable" onClick="displaySubCategories(this)"></i>');
            }
        });
    }
        
   hideItems = function(obj){
        var prodCatId = $(obj).parent().parent().parent().attr('id');
        $("#"+prodCatId+ ' ul').remove();
        $("#"+prodCatId).removeClass('sortableListsOpen');
        $("#"+prodCatId).addClass('sortableListsClosed');
        $("#"+prodCatId).children().children('.sortableListsOpener').html('');
        $("#"+prodCatId).children().children('.sortableListsOpener').html('<i class="fa fa-plus clickable" onClick="displaySubCategories(this)"></i>');
   }

})();





