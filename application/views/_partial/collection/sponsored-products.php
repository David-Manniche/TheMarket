<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if (isset($collection['products']) && count($collection['products']) > 0) { ?>
    <section class="section" role="sponsored products">
        <div class="container">
            <div class="section-head">
                <div class="section__heading">
                    <h2><?php echo ($collection['collection_name'] != '') ? $collection['collection_name'] : ''; ?></h2>
                </div>
                <?php /* if ($collection['totProducts'] > $collection['collection_primary_records']) { ?>
                <div class="section__action"><a href="<?php echo UrlHelper::generateUrl('Collections', 'View', array($collection['collection_id']));?>" class="link"><?php echo Labels::getLabel('LBL_View_More', $siteLangId); ?></a> </div>
                <?php } */ ?>
            </div>
            <div class="product-items" dir="<?php echo CommonHelper::getLayoutDirection(); ?>">
                <?php foreach ($collection['products'] as $product) { ?>
                <div class="items">
                    <?php include('product-layout-1-list.php'); ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <hr class="m-0">
<?php }