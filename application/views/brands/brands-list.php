<div class="cg-main">
	<?php if(!empty($brandsArr)){
	foreach($brandsArr[Collections::COLLECTION_LAYOUT7_TYPE]  as $allBrands){
	?>
	<div class="">
	<?php if(!empty($allBrands['brands'])){
			$firstCharacter = '';
			foreach($allBrands['brands'] as $brands){
			$str = substr(strtolower($brands['brand_name']), 0, 1);

			if(is_numeric($str)){
				$str = '0-9';
			}

			if($str != $firstCharacter){
				if($firstCharacter!=''){ echo "</ul></div>"; }
				$firstCharacter = $str;
	?>
  <div class="listingbox">
	<h5><?php echo $firstCharacter;?></h5>
	<ul class="listing--onefifth">
	  <?php } ?>
	  <li><a href="<?php echo CommonHelper::generateUrl('Brands','view',array($brands['brand_id']));?>"><?php echo $brands['brand_name'];?></a></li>
	  <?php } ?>
	</ul>
  </div>
  <?php } ?>
  <?php } ?>
  <?php } ?>
</div>
