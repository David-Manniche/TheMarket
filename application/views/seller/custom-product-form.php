<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<script type="text/javascript">
var  productId  =  <?php echo $prodId ;?>;
var  productCatId  =  <?php echo $prodCatId ;?>;
</script>
<?php $this->includeTemplate('_partial/dashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header row">
            <div class="col">
                <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Custom_Product_Setup', $siteLangId); ?></h2>
            </div>
            <div class="col-auto">
                <div class="btn-group">
                    <a href="<?php echo CommonHelper::generateUrl('seller', 'catalog'); ?>" class="btn btn--primary btn--sm"><?php echo Labels::getLabel('LBL_Back_to_Products', $siteLangId); ?></a>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div id="listing"></div>
        </div>
    </div>
</main>
<script>
var ratioTypeSquare = <?php echo AttachedFile::RATIO_TYPE_SQUARE; ?>;
var ratioTypeRectangular = <?php echo AttachedFile::RATIO_TYPE_RECTANGULAR; ?>;
$(document).ready(function(){
    <?php if ($prodId) {?>
    customProductForm(<?php echo $prodId;?>,<?php echo $prodCatId;?>);
    <?php } else {?>
    customProductForm();
    <?php }?>
});
</script>
