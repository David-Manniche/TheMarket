<?php defined('SYSTEM_INIT') or die('Invalid usage');
/* reviews processing */

$totReviews = FatUtility::int($reviews['totReviews']);
$avgRating = FatUtility::convertToType($reviews['avg_seller_rating'],FatUtility::VAR_FLOAT);
$rated_1 = FatUtility::int($reviews['rated_1']);
$rated_2 = FatUtility::int($reviews['rated_2']);
$rated_3 = FatUtility::int($reviews['rated_3']);
$rated_4 = FatUtility::int($reviews['rated_4']);
$rated_5 = FatUtility::int($reviews['rated_5']);

$pixelToFillRight = $avgRating/5*160;
$pixelToFillRight = FatUtility::convertToType($pixelToFillRight,FatUtility::VAR_FLOAT);

$rate_5_width = $rate_4_width =$rate_3_width= $rate_2_width= $rate_1_width = 0;

if($totReviews){
	$rate_5_width = round(FatUtility::convertToType($rated_5/$totReviews*100,FatUtility::VAR_FLOAT),2);
	$rate_4_width = round(FatUtility::convertToType($rated_4/$totReviews*100,FatUtility::VAR_FLOAT),2);
	$rate_3_width = round(FatUtility::convertToType($rated_3/$totReviews*100,FatUtility::VAR_FLOAT),2);
	$rate_2_width = round(FatUtility::convertToType($rated_2/$totReviews*100,FatUtility::VAR_FLOAT),2);
	$rate_1_width = round(FatUtility::convertToType($rated_1/$totReviews*100,FatUtility::VAR_FLOAT),2);
}
?>
<div class="listings">
	<div class="listings__head">
		<div class="row">
			<div class="col-md-8">
				<div class="ratings--overall">
					<div class="row">
						<div class="col-md-5 col-sm-5">
							<div class="progress progress--radial">
								<div class="progress__circle">
									<div class="progress__mask left">
										<div class="progress__fill" style="/* transform: rotate(106deg); */"></div>
										<span class="progress__count"><?php echo round($avgRating,1); ?></span>
									</div>
								</div>
							</div>
							<p class="align--center"><?php echo Labels::getLabel('Lbl_Based_on',$siteLangId) ,' ', $totReviews ,' ',Labels::getLabel('Lbl_ratings',$siteLangId);?></p>
						</div>
						<div class="col-md-7 col-sm-7">
							<ul class="listing--progress">
								<li>
									<span class="grid--left">5 <?php echo Labels::getLabel('Lbl_Star',$siteLangId); ?></span>
									<div class="grid--right">
									<div class="progress progress--horizontal">
									<div title="<?php echo $rate_5_width,'% ',Labels::getLabel('LBL_Number_of_reviews_have_5_stars',$siteLangId); ?>" style="width: <?php echo $rate_5_width; ?>%" role="progressbar" class="progress__bar"></div>
									</div>
									</div>
								</li>
								<li>
									<span class="grid--left">4 <?php echo Labels::getLabel('Lbl_Star',$siteLangId); ?></span>
									<div class="grid--right">
									<div class="progress progress--horizontal">
									<div title="<?php echo $rate_4_width,'% ',Labels::getLabel('LBL_Number_of_reviews_have_4_stars',$siteLangId); ?>" style="width: <?php echo $rate_4_width; ?>%" role="progressbar" class="progress__bar"></div>
									</div>
									</div>
								</li>
								<li>
									<span class="grid--left">3 <?php echo Labels::getLabel('Lbl_Star',$siteLangId); ?></span>
									<div class="grid--right">
									<div class="progress progress--horizontal">
									<div title="<?php echo $rate_3_width,'% ',Labels::getLabel('LBL_Number_of_reviews_have_3_stars',$siteLangId); ?>" style="width: <?php echo $rate_3_width; ?>%" role="progressbar" class="progress__bar"></div>
									</div>
									</div>
								</li>
								<li>
									<span class="grid--left">2 <?php echo Labels::getLabel('Lbl_Star',$siteLangId); ?></span>
									<div class="grid--right">
									<div class="progress progress--horizontal">
									<div title="<?php echo $rate_2_width,'% ',Labels::getLabel('LBL_Number_of_reviews_have_2_stars',$siteLangId); ?>" style="width: <?php echo $rate_2_width; ?>%" role="progressbar" class="progress__bar"></div>
									</div>
									</div>
								</li>
								<li>
									<span class="grid--left">1 <?php echo Labels::getLabel('Lbl_Star',$siteLangId); ?></span>
									<div class="grid--right">
									<div class="progress progress--horizontal">
									<div title="<?php echo $rate_1_width,'% ',Labels::getLabel('LBL_Number_of_reviews_have_1_stars',$siteLangId); ?>" style="width: <?php echo $rate_1_width; ?>%" role="progressbar" class="progress__bar"></div>
									</div>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<?php
			/* <div class="col-md-4 border--left">
				<h4><?php echo Labels::getLabel('Lbl_Share_your_thoughts',$siteLangId); ?></h4>
				<h6><?php echo Labels::getLabel('Lbl_With_other_customers',$siteLangId); ?></h6>
				<a class="btn btn--primary btn--h-large" href="<?php echo CommonHelper::generateUrl('Reviews','write',array($shop_id)); ?>"><?php echo Labels::getLabel('Lbl_Write_a_Review',$siteLangId); ?></a>
			</div> */
			?>
		</div>
	</div>

	<div class="listings__body">
		<div class="listings__sort">
		<div class="col-md-6 col-sm-6"><span id='reviews-pagination-strip--js' hidden><?php echo Labels::getLabel('Lbl_Displaying_Reviews',$siteLangId); ?>  <span id='reviewStartIndex'>XX</span>-<span id='reviewEndIndex'>XX</span> <?php echo Labels::getLabel('Lbl_of',$siteLangId); ?> <span id='reviewsTotal'>XX</span></span></div>
		<div class="col-md-6 col-sm-6">
			<ul class="links--inline align--right">
				<li><?php echo Labels::getLabel('Lbl_Sort_By',$siteLangId); ?>:</li>
				<li><a href='javascript:void(0);' data-sort='most_helpful' onclick="getSortedReviews(this);return false;"><?php echo Labels::getLabel('Lbl_Most_Helpful',$siteLangId); ?></a></li>
				<li class="is-active"><a href="javascript:void(0);" data-sort='most_recent' onclick="getSortedReviews(this);return false;"><?php echo Labels::getLabel('Lbl_Most_Recent',$siteLangId); ?> </a></li>
			</ul>
		</div>
		</div>

		<div class="listing__all">
		
		</div>
		<div id="loadMoreReviewsBtnDiv">
		</div>
		<!--<a class="loadmore text--uppercase" href="javascript:alert('Pending');">Load More</a>-->
	</div>
</div>
<script>

var $linkMoreText = '<?php echo Labels::getLabel('Lbl_SHOW_MORE',$siteLangId); ?>';
var $linkLessText = '<?php echo Labels::getLabel('Lbl_SHOW_LESS',$siteLangId); ?>';

$('#itemRatings div.progress__fill').css({'clip':'rect(0px, <?php echo $pixelToFillRight; ?>px, 160px, 0px)'});
</script>