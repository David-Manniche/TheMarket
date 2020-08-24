<?php
if(!empty($bannerListing)){
	foreach($bannerListing as $val){
		$uploadedTime = $uploadedTime = AttachedFile::setTimeParam($val['banner_updated_on']);
		?>
		<div class="grids__item"><a href="<?php echo $val['banner_url'];?>" target="<?php echo $val['banner_target'];?>" title="<?php echo $val['banner_title'];?>" class="advertise__block"><img src="<?php echo UrlHelper::getCachedUrl(UrlHelper::generateFileUrl('Banner', 'BrandPage', array($val['banner_id'],$siteLangId)).$uploadedTime, CONF_IMG_CACHE_TIME, '.jpg');?>" alt="<?php echo $val['banner_title'];?>"></a></div>
		<?php
	}
}
?>
