<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
if( isset($collection['products']) && count($collection['products']) ) { ?>
<section class="section bg-second">
	<div class="container">
		<div class="section-head section--white--head">
			<div class="section__heading">
				<h2><?php echo ($collection['collection_name'] != '') ? $collection['collection_name'] : ''; ?></h2>
			</div>
			<?php if( $collection['totProducts'] > 6 ){ ?>
				<div class="section__action"><a href="<?php echo UrlHelper::generateUrl('Collections', 'View', array($collection['collection_id']));?>" class="link"><?php echo Labels::getLabel('LBL_View_More',$siteLangId); ?></a> </div>
			<?php }  ?>
		</div>
		<div class="js-collection-corner collection-corner product-listing" dir="<?php echo CommonHelper::getLayoutDirection();?>">
			<?php foreach( $collection['products'] as $product ){ ?>
				<?php include('product-layout-1-list.php'); ?>
			<?php } ?>
		</div>
	</div>
</section>
<?php } ?>