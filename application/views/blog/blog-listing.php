<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
if(!empty($postList)){ ?>
	<?php if(isset($keyword) || isset($bpCategoryId)){
		$innerListcount = 1;
		foreach($postList as $blogPost ){ ?>
			<section class="section">
				<div class="container">
					<?php if($innerListcount==1) { ?><p class="results-message"><?php echo Labels::getLabel('LBL_Displaying', $siteLangId); ?> <span id="start_record" ></span>-<span id="end_record"></span> <?php echo Labels::getLabel('LBL_of', $siteLangId); ?> <span id="total_records"></span></p>
					<?php } ?>
					<div class="post-list">
						<article class="post-repeated <?php echo ($innerListcount%2==0) ? "odd" : ""; ?>">
							<div class="posted-media">
								<div class="posted-media-inner"><a href="<?php echo CommonHelper::generateUrl('Blog','postDetail',array($blogPost['post_id'])); ?>"><img src="<?php echo CommonHelper::generateUrl('image','blogPostFront', array($blogPost['post_id'],$siteLangId, "BANNER"),CONF_WEBROOT_URL); ?>" alt="<?php echo $blogPost['post_title']?>"></a></div>
							</div>
							<div class="posted-data-side">
								<div class="posted-data">
									<div class="posted-by"><span class="auther"><?php echo Labels::getLabel('Lbl_By',$siteLangId)." "; ?> <?php echo CommonHelper::displayName($blogPost['post_author_name']); ?></span> <span class="time"><?php echo FatDate::format($blogPost['post_published_on']); ?></span></div>
									<h2><a href="<?php echo CommonHelper::generateUrl('Blog','postDetail',array($blogPost['post_id'])); ?>"><?php echo $blogPost['post_title']?></a></h2>
									<p><?php $desLen = mb_strlen($blogPost['post_short_description']); if($desLen > 250){echo mb_substr($blogPost['post_short_description'],0,250).'...'; } else { echo $blogPost['post_short_description']; } ?></p>
									<a href="<?php echo CommonHelper::generateUrl('Blog','postDetail',array($blogPost['post_id'])); ?>" class="links"><?php echo Labels::getLabel('Lbl_Read_More',$siteLangId); ?></a>
									<div class="share-this">
                                        <span><i class="icn share"><svg class="svg">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#share" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#share"></use>
                                                </svg></i><?php echo Labels::getLabel('LBL_Share',$siteLangId); ?></span>
                                        <a class="social-link st-custom-button" data-network="facebook">
                                            <i class="icn"><svg class="svg">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#fb" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#fb"></use>
                                                </svg></i>
                                        </a>
                                        <a class="social-link st-custom-button" data-network="twitter">
                                            <i class="icn"><svg class="svg">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#tw" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#tw"></use>
                                                </svg></i>
                                        </a>
                                        <a class="social-link st-custom-button" data-network="pinterest">
                                            <i class="icn"><svg class="svg">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#pt" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#pt"></use>
                                                </svg></i>
                                        </a>
                                        <a class="social-link st-custom-button" data-network="email">
                                            <i class="icn"><svg class="svg">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#envelope" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#envelope"></use>
                                                </svg></i>
                                        </a>
                                    </div>
								</div>
							</div>
						</article>
					</div>
				</div>
			</section>

		<?php $innerListcount++; }

	} else { ?>

	<?php $outerSectionCount=1; $innerListcount = 1;
	foreach($postList as $blogPost ){
		if($outerSectionCount%2!=0) {
			if($innerListcount == 1){ ?>
				<section class="section">
					<div class="container">
						<div class="post-list">
			<?php }?>
			<article class="post-repeated <?php echo ($innerListcount%2==0) ? "odd" : ""; ?>">
				<div class="posted-media">
					<div class="posted-media-inner"><a href="<?php echo CommonHelper::generateUrl('Blog','postDetail',array($blogPost['post_id'])); ?>"><img src="<?php echo CommonHelper::generateUrl('image','blogPostFront', array($blogPost['post_id'],$siteLangId, "BANNER"),CONF_WEBROOT_URL); ?>" alt="<?php echo $blogPost['post_title']?>"></a></div>
				</div>
				<div class="posted-data-side">
					<div class="posted-data">
						<div class="posted-by"><span class="auther"><?php echo Labels::getLabel('Lbl_By',$siteLangId)." "; ?> <?php echo CommonHelper::displayName($blogPost['post_author_name']); ?></span> <span class="time"><?php echo FatDate::format($blogPost['post_published_on']); ?></span></div>
						<h2><a href="<?php echo CommonHelper::generateUrl('Blog','postDetail',array($blogPost['post_id'])); ?>"><?php echo $blogPost['post_title']?></a></h2>
						<p><?php $desLen = mb_strlen($blogPost['post_short_description']); if($desLen > 250){echo mb_substr($blogPost['post_short_description'],0,250).'...'; } else { echo $blogPost['post_short_description']; } ?></p>
						<a href="<?php echo CommonHelper::generateUrl('Blog','postDetail',array($blogPost['post_id'])); ?>" class="links"><?php echo Labels::getLabel('Lbl_Read_More',$siteLangId); ?></a>
						<div class="share-this">
                            <span><i class="icn share"><svg class="svg">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#share" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#share"></use>
                                    </svg></i><?php echo Labels::getLabel('LBL_Share',$siteLangId); ?></span>
                            <a class="social-link st-custom-button" data-network="facebook">
                                <i class="icn"><svg class="svg">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#fb" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#fb"></use>
                                    </svg></i>
                            </a>
                            <a class="social-link st-custom-button" data-network="twitter">
                                <i class="icn"><svg class="svg">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#tw" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#tw"></use>
                                    </svg></i>
                            </a>
                            <a class="social-link st-custom-button" data-network="pinterest">
                                <i class="icn"><svg class="svg">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#pt" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#pt"></use>
                                    </svg></i>
                            </a>
                            <a class="social-link st-custom-button" data-network="email">
                                <i class="icn"><svg class="svg">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#envelope" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#envelope"></use>
                                    </svg></i>
                            </a>
                        </div>
					</div>
				</div>
			</article>
			<?php  if($innerListcount == 2){?>
				</div>
			</div>
		</section><?php $outerSectionCount++; }
		$innerListcount++;
		} else {
			if($innerListcount == 3){?>
			<section class="bg-pattern">
				<div class="container">
					<div class="row">
				<?php }?>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						<div class="recent-posts">
							<div class="posted-media">
								<div class="posted-media-inner"><a href="<?php echo CommonHelper::generateUrl('Blog','postDetail',array($blogPost['post_id'])); ?>"><img src="<?php echo CommonHelper::generateUrl('image','blogPostFront', array($blogPost['post_id'],$siteLangId, "BANNER"),CONF_WEBROOT_URL); ?>" alt="<?php echo $blogPost['post_title']?>"></a></div>
							</div>
							<div class="posted-data-side">
								<div class="posted-data">
									<div class="posted-by"><span class="auther"><?php echo Labels::getLabel('Lbl_By',$siteLangId)." "; ?> <?php echo CommonHelper::displayName($blogPost['post_author_name']); ?></span> <span class="time"><?php echo FatDate::format($blogPost['post_published_on']); ?></span></div>
									<h2><a href="<?php echo CommonHelper::generateUrl('Blog','postDetail',array($blogPost['post_id'])); ?>"><?php
									$strLen = mb_strlen($blogPost['post_title']);
									if($strLen > 50){
											echo mb_substr($blogPost['post_title'],0,50).'...';}
									else{	echo $blogPost['post_title'];
									}?></a></h2>
									<p><?php $desLen = mb_strlen($blogPost['post_short_description']); if($desLen > 250){echo mb_substr($blogPost['post_short_description'],0,250).'...'; } else { echo $blogPost['post_short_description']; } ?></p>
									<a href="<?php echo CommonHelper::generateUrl('Blog','postDetail',array($blogPost['post_id'])); ?>" class="links"><?php echo Labels::getLabel('Lbl_Read_More',$siteLangId); ?></a>
									<div class="share-this">
                                        <span><i class="icn share"><svg class="svg">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#share" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#share"></use>
                                                </svg></i><?php echo Labels::getLabel('LBL_Share',$siteLangId); ?></span>
                                        <a class="social-link st-custom-button" data-network="facebook">
                                            <i class="icn"><svg class="svg">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#fb" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#fb"></use>
                                                </svg></i>
                                        </a>
                                        <a class="social-link st-custom-button" data-network="twitter">
                                            <i class="icn"><svg class="svg">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#tw" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#tw"></use>
                                                </svg></i>
                                        </a>
                                        <a class="social-link st-custom-button" data-network="pinterest">
                                            <i class="icn"><svg class="svg">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#pt" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#pt"></use>
                                                </svg></i>
                                        </a>
                                        <a class="social-link st-custom-button" data-network="email">
                                            <i class="icn"><svg class="svg">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#envelope" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#envelope"></use>
                                                </svg></i>
                                        </a>
                                    </div>
								</div>
							</div>
						</div>
					</div>
				<?php if($innerListcount >= 5){?>
					</div>
				</div>
			</section>
		<?php $innerListcount = 1;
				$outerSectionCount++;
				}else{
				$innerListcount++;
			}
		}

	}
	}?>


	<?php
	$postedData['page'] = $page;
	echo FatUtility::createHiddenFormFromData ( $postedData, array ('name' => 'frmBlogSearchPaging') );
	$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'recordCount'=>$recordCount, 'callBackJsFunc' => 'goToSearchPage');
	$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
	?>

<?php } else {
	?>
	<div class="post box box--white">
		<?php
		$this->includeTemplate('_partial/no-record-found.php',array('siteLangId'=>$siteLangId),false);
		?>
	</div>
	<?php
} ?>
