<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>

<?php ($imageType!='banner')? $count = 1 : ''; foreach($images as $img){?>
<div class="<?php echo ($imageType!='banner')? 'col-md-6' : 'row';?>">
	<div class="profile__pic">
		<img src="<?php echo CommonHelper::generateUrl('Image',$imageFunction,array($img['afile_record_id'],$img['afile_lang_id'],'PREVIEW',$img['afile_id'])).'?'.time();?>" alt="<?php echo Labels::getLabel('LBL_Shop_Banner', $siteLangId);?>">
	</div>
	<small class="text--small"><?php echo $languages[$img['afile_lang_id']];?></small>
	<div class="btngroup--fix">
		<a class = "btn btn--secondary btn--sm" href="javascript:void(0);" onClick="removeShopImage(<?php echo $img['afile_id']; ?>,<?php echo $img['afile_lang_id']; ?>,'<?php echo $imageType; ?>')"><?php echo Labels::getLabel('LBL_Remove',$siteLangId);?></a>
	</div>
</div>
<?php if($imageType!='banner') {
	if($count == 2) { $count = 1; echo "<span class='gap'></span>"; }
	} else {
		echo "<span class='gap'></span>";
	}
?>
<?php } ?>