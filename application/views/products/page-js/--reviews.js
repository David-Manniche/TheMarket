$("document").ready(function(){
	reviews(document.frmReviewSearch);
});

function getSortedReviews(elm){
	if($(elm).length){
		var sortBy = $(elm).data('sort');
		if(sortBy){
			document.frmReviewSearch.orderBy.value = $(elm).data('sort');
			$(elm).parent().siblings().removeClass('is-active');
			$(elm).parent().addClass('is-active');
		}
	}
	reviews(document.frmReviewSearch);
}

function reviewAbuse(reviewId){
	if(reviewId){
		$.facebox(function() {
			fcom.ajax(fcom.makeUrl('Products', 'reviewAbuse', [reviewId]), '', function(t) {
				$.facebox(t,'faceboxWidth');
			});
		});
	}
}

function setupReviewAbuse(frm){
	if (!$(frm).validate()) return;
	var data = fcom.frmData(frm);
	fcom.updateWithAjax(fcom.makeUrl('Products', 'setupReviewAbuse'), data, function(t) {
		$(document).trigger('close.facebox');
	});
	return false;
}

function productAttributes( product_id ){
	$("#itemSpecifications").html(fcom.getLoader());
	
	fcom.ajax( fcom.makeUrl('Products','productAttributes',[product_id]),'',function(ans){
		$("#itemSpecifications").html(ans);
	});
}

(function() {
	
	/* reviews section[ */
	
	var dv = '#itemRatings .listing__all';
	var currPage = 1;
	
	reviews = function(frm, append){
		
		if(typeof append == undefined || append == null){
			append = 0;
		}
		
		var data = fcom.frmData(frm);
		if( append == 1 ){
			$(dv).prepend(fcom.getLoader());
		} else {
			$(dv).html(fcom.getLoader());
		}
		
		fcom.updateWithAjax(fcom.makeUrl('Products','searchReviews'), data, function(ans){
			
			$.mbsmessage.close();
			
			if(ans.totalRecords){
				$('#reviews-pagination-strip--js').show();
			}			
			if( append == 1 ){ 
				$(dv).find('.loader-yk').remove();
				$(dv).find('form[name="frmSearchReviewsPaging"]').remove();
				$(dv).append(ans.html);
				
				$('#reviewEndIndex').html(( Number($('#reviewEndIndex').html()) + ans.recordsToDisplay));
			} else {
				$(dv).html(ans.html);
				$('#reviewStartIndex').html(ans.startRecord);
				$('#reviewEndIndex').html(ans.recordsToDisplay);
			}
			$('#reviewsTotal').html(ans.totalRecords);
			
			$("#loadMoreReviewsBtnDiv").html( ans.loadMoreBtnHtml );
		}); 
	};
	
	goToLoadMoreReviews = function(page){
		if(typeof page == undefined || page == null){
			page = 1;
		}
		currPage = page;
		var frm = document.frmSearchReviewsPaging;		
		$(frm.page).val(page);
		reviews(frm,1);
	};
	
	/*] */
	
	markReviewHelpful = function(reviewId , isHelpful){
		isHelpful = (isHelpful) ? isHelpful : 0;
		var data = 'reviewId='+reviewId+'&isHelpful=' + isHelpful;
		fcom.updateWithAjax(fcom.makeUrl('Products','markReviewHelpful'), data, function(ans){
			$.mbsmessage.close();
			reviews(document.frmReviewSearch);
			/* if(isHelpful == 1){
				
			} else {
				
			} */
		});
	}
	
})();