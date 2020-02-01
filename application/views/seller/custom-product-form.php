<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<script type="text/javascript">
/* var  productId  =  <?php echo $prodId ;?>;
var  productCatId  =  <?php echo $prodCatId ;?>; */ 
</script>
<?php $this->includeTemplate('_partial/dashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header row justify-content-between mb-3">
            <div class="col-md-auto">
                <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Custom_Product_Setup', $siteLangId); ?></h2>
            </div>
            <div class="col-md-auto">
                <div class="actions">
                    <a href="<?php echo CommonHelper::generateUrl('seller', 'catalog'); ?>" class="btn btn--primary btn--sm"><?php echo Labels::getLabel('LBL_Back_to_Products', $siteLangId); ?></a>
                </div>
            </div>
        </div>
        <div class="content-body">
            
            <div class="tabs_nav_container wizard-tabs-vertical">                    
                <ul class="tabs_nav">
                     <li><a class="active tabs_001" rel="tabs_001" href="javascript:void(0)">
                             <i class="tabs-icon fa fa-globe"></i>
                             <div class="tabs-head">
                                 <div class="tabs-title"><?php echo Labels::getLabel('LBL_Initial_Setup', $siteLangId); ?><span><?php echo Labels::getLabel('LBL_Setup_Basic_Product_Details', $siteLangId); ?></span></div>
                             </div>

                         </a>
                     </li>
                     <li><a rel="tabs_002" class="tabs_002" href="javascript:void(0)"> <i class="tabs-icon fa fa-globe"></i>
                             <div class="tabs-head">
                                 <div class="tabs-title"><?php echo Labels::getLabel('LBL_Product_Attribute_&_Specifications',$siteLangId); ?><span><?php echo Labels::getLabel('LBL_Add_Product_Attribute_&_Specifications', $siteLangId); ?></span></div>

                             </div>
                         </a></li>
                     <li><a rel="tabs_003" class="tabs_003" href="javascript:void(0)"> <i class="tabs-icon fa fa-globe"></i>
                             <div class="tabs-head">
                                 <div class="tabs-title"><?php echo Labels::getLabel('LBL_Product_Options_And_Tags', $siteLangId); ?><span><?php echo Labels::getLabel('LBL_Add_Product_Options_And_Tags', $siteLangId); ?></span></div>
                             </div>
                         </a></li>

                     <li><a rel="tabs_004" class="tabs_004" href="javascript:void(0)"> <i class="tabs-icon fa fa-globe"></i>
                             <div class="tabs-head">
                                 <div class="tabs-title"><?php echo Labels::getLabel('LBL_Shipping_Information', $siteLangId); ?><span><?php echo Labels::getLabel('LBL_Setup_Product_Dimentions_And_Shipping_Information', $siteLangId); ?></span></div>
                             </div>
                         </a></li>
                     <li><a rel="tabs_005" class="tabs_005" href="javascript:void(0)"> <i class="tabs-icon fa fa-globe"></i>
                             <div class="tabs-head">
                                 <div class="tabs-title"> <?php echo Labels::getLabel('LBL_Product_Media', $siteLangId); ?><span><?php echo Labels::getLabel('LBL_Add_Option_Based_Product_Media', $siteLangId); ?></span></div>
                             </div>
                         </a></li>
                 </ul>
                 
                 <div class="tabs_panel_wrap">
                    <div id="tabs_001" class="tabs_panel" style="display: block;"></div>
                    <div id="tabs_002" class="tabs_panel" style="display: none;"> </div>
                    <div id="tabs_003" class="tabs_panel" style="display: none;"></div>
                    <div id="tabs_004" class="tabs_panel" style="display: none;"></div>
                    <div id="tabs_005" class="tabs_panel" style="display: none;"></div>
                 </div>
            </div>                       
        </div>
    </div>
</main>
<script>
$(document).ready(function(){
    <?php /* if ($prodId) { ?>
    customProductForm(<?php echo $prodId;?>,<?php echo $prodCatId;?>);
    <?php } else {?>
    customProductForm();
    <?php } */ ?>
    
    customProductForm('<?php echo $productId ;?>');
    hideShippingTab('<?php echo $productType; ?>', '<?php echo Product::PRODUCT_TYPE_DIGITAL; ?>');
    
});
</script>

