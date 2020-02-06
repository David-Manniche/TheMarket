<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
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
                    <a href="<?php echo CommonHelper::generateUrl('seller', 'customCatalogProducts'); ?>" class="btn btn--primary btn--sm"><?php echo Labels::getLabel('LBL_Back_to_Product_Requests', $siteLangId); ?></a>
                </div>
            </div>
        </div>
        <div class="content-body" id="listing"></div>
    </div>
</main>
<script>
    $(document).ready(function() {
        <?php if ($preqId) {?>
        customCatalogProductForm(<?php echo $preqId;?>, <?php echo $preqCatId;?>);
        <?php } else {?>
        customCatalogProductForm();
        <?php }?>
    });
</script>
