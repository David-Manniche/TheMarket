<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<?php $variables= array( 'language'=>$language,'siteLangId'=>$siteLangId,'shop_id'=>$shop_id,'action'=>$action);
	$this->includeTemplate('seller/_partial/shop-navigation.php',$variables,false); ?>
<div class="col-md-12">
	<div class="">
		<div class="tabs tabs-sm tabs--scroll clearfix">
			<ul>
				<li ><a onclick="getShopCollectionGeneralForm();" href="javascript:void(0)"><?php echo Labels::getLabel('TXT_Basic', $siteLangId);?></a></li>
				<?php
				foreach($language as $lang_id => $langName){?>
				<li class=""><a href="javascript:void(0)" onClick="editShopCollectionLangForm(<?php echo $scollection_id ?>, <?php echo $lang_id;?>)">
				<?php echo $langName;?></a></li>
				<?php } ?>
				<li class="is-active">
					<a onclick="sellerCollectionProducts(<?php echo $scollection_id ?>)" href="javascript:void(0);"> <?php echo Labels::getLabel('TXT_LINK', $siteLangId);?> </a>
				</li>
			</ul>
		</div>
	</div>

	<div class="form__subcontent">
		<?php
		$sellerCollectionproductLinkFrm->setFormTagAttribute('onsubmit','setUpSellerCollectionProductLinks(this); return(false);');
		$sellerCollectionproductLinkFrm->setFormTagAttribute('class','form form--horizontal');
		$sellerCollectionproductLinkFrm->developerTags['colClassPrefix'] = 'col-lg-8 ';
		$sellerCollectionproductLinkFrm->developerTags['fld_default_col'] = 8;

	echo $sellerCollectionproductLinkFrm->getFormHtml(); ?>
	</div>
</div>

<script type="text/javascript">
$("document").ready(function(){

	$('#selprod-products').delegate('.remove_link', 'click', function() {

		$(this).parent().remove();
	});

});




	<?php
	if(isset($products)&& !empty($products)){
		foreach($products as $key => $val){
		?>
		$('#selprod-products ul').append("<li id=\"selprod-products<?php echo $val['selprod_id'];?>\"><i class=\"remove_param fa fa-remove remove_link\"></i> <?php echo $val['product_name'];?>[<?php echo $val['product_identifier'];?>]<input type=\"hidden\" name=\"product_ids[]\" value=\"<?php echo $val['selprod_id'];?>\" /></li>");
  	<?php }
	}?>


</script>
