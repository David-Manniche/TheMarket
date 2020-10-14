<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
if (isset($collection['products']) && count($collection['products']) > 0) { ?>
    <section class="section">
        <div class="container">
            <div class="section-head">
                <div class="section__heading">
                    <h2><?php echo ($collection['collection_name'] != '') ?  $collection['collection_name'] : ''; ?></h2>
                </div>
                <?php if ($collection['totProducts'] > $collection['collection_primary_records']) { ?>
                    <div class="section__action"><a href="<?php echo UrlHelper::generateUrl('Collections', 'View', array($collection['collection_id']));?>" class="link"><?php echo Labels::getLabel('LBL_View_More', $siteLangId); ?></a> </div>
                <?php } ?>
            </div>
            <div class="ft-products">
                <div class="row">
                    <?php foreach ($collection['products'] as $product) { ?>
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                        <?php $layoutClass = 'products--layout';
                        include('product-layout-1-list.php'); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
<?php }
