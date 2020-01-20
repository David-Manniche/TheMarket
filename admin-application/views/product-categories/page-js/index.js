$(document).ready(function(){
	searchProductCategories(document.frmSearch);
});

(function() {
	var currentPage = 1;
	var runningAjaxReq = false;
    
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
                window.location.href = fcom.makeUrl('productCategories');
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
		var prodCatId = parseInt(obj.value);
		if( prodCatId < 1 ){
			fcom.displayErrorMessage(langLbl.invalidRequest);
			return false;
		}
		data='prodCatId='+prodCatId;
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
 
    displaySubCategories = function(obj, catId = 0){
        if(catId > 0 ){
            var prodCatId = catId; 
        }else{
            var prodCatId = $(obj).parent().parent().parent().attr('id'); 
        }
        
        if($("#"+prodCatId).hasClass('no-children')){
            return false;
        } 
        
        if($("#"+prodCatId+ ' ul li.child-category').length){
            $("#"+prodCatId+ ' ul').show();
            togglePlusMinus(prodCatId);
            return false;
        }
        
        fcom.ajax(fcom.makeUrl('productCategories','getSubCategories'), 'prodCatId='+prodCatId, function(res){            
            $("#"+prodCatId).append('<ul>'+res+'</ul>');
            if(catId == 0){
                togglePlusMinus(prodCatId);
            }
        }); 
    }
   
   togglePlusMinus = function(prodCatId){
        $("#"+prodCatId).children( 'div' ).children( '.sortableListsOpener' ).remove();
        if($("#"+prodCatId).hasClass('sortableListsClosed')){
            $("#"+prodCatId).removeClass('sortableListsClosed').addClass('sortableListsOpen');
            $("#"+prodCatId).children( 'div' ).append('<span class="sortableListsOpener" ><i class="fa fa-minus clickable sort-icon" onClick="hideItems(this)"></i></span>');                
        }else{
            $("#"+prodCatId).removeClass('sortableListsOpen').addClass('sortableListsClosed');
            $("#"+prodCatId).children( 'div' ).append('<span class="sortableListsOpener" ><i class="fa fa-plus c3 clickable sort-icon" onClick="displaySubCategories(this)"></i></span>');
            
        }       
   }
   
   hideItems = function(obj){
        var prodCatId = $(obj).parent().parent().parent().attr('id');
        $("#"+prodCatId+ ' ul').hide();
        $("#"+prodCatId).removeClass('sortableListsOpen').addClass('sortableListsClosed');
        var icon = $("#"+prodCatId).children( 'div' ).children( '.sortableListsOpener' ).remove();
        $("#"+prodCatId).children( 'div' ).append('<span class="sortableListsOpener" ><i class="fa fa-plus c3 clickable sort-icon" onClick="displaySubCategories(this)"></i></span>');
   }

})();





