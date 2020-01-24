<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<div class="page">
    <div class="container container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 space">
                <div class="page__title">
                     <div class="row justify-content-between">
                         <div class="col--first col-lg-6">
                             <span class="page__icon"><i class="ion-android-star"></i></span>
                             <h5><?php echo Labels::getLabel('LBL_Manage_Catalog', $adminLangId); ?>  </h5>
                             <?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
                         </div>
                     </div>
                 </div>
                <div class="tabs_nav_container vertical wizard-tabs-vertical">                    
                    
                    <ul class="tabs_nav">
                         <li><a class="active tabs_001" rel="tabs_001" href="javascript:void(0)">
                                 <i class="tabs-icon fa fa-globe"></i>
                                 <div class="tabs-head">
                                     <div class="tabs-title"><?php echo Labels::getLabel('LBL_Initial_Setup', $adminLangId); ?><span><?php echo Labels::getLabel('LBL_Setup_Basic_Product_Details', $adminLangId); ?></span></div>
                                 </div>

                             </a>
                         </li>
                         <li><a rel="tabs_002" class="tabs_002" href="javascript:void(0)"> <i class="tabs-icon fa fa-globe"></i>
                                 <div class="tabs-head">
                                     <div class="tabs-title"><?php echo Labels::getLabel('LBL_Product_Attribute_&_Specifications',$adminLangId); ?><span><?php echo Labels::getLabel('LBL_Add_Product_Attribute_&_Specifications', $adminLangId); ?></span></div>

                                 </div>
                             </a></li>
                         <li><a rel="tabs_003" class="tabs_003" href="javascript:void(0)"> <i class="tabs-icon fa fa-globe"></i>
                                 <div class="tabs-head">
                                     <div class="tabs-title"><?php echo Labels::getLabel('LBL_Product_Options_And_Tags', $adminLangId); ?><span><?php echo Labels::getLabel('LBL_Add_Product_Options_And_Tags', $adminLangId); ?></span></div>
                                 </div>
                             </a></li>

                         <li><a rel="tabs_004" class="tabs_004" href="javascript:void(0)"> <i class="tabs-icon fa fa-globe"></i>
                                 <div class="tabs-head">
                                     <div class="tabs-title"><?php echo Labels::getLabel('LBL_Shipping_Information', $adminLangId); ?><span><?php echo Labels::getLabel('LBL_Setup_Product_Dimentions_And_Shipping_Information', $adminLangId); ?></span></div>
                                 </div>
                             </a></li>
                         <li><a rel="tabs_005" href="javascript:void(0)"> <i class="tabs-icon fa fa-globe"></i>
                                 <div class="tabs-head">
                                     <div class="tabs-title"> <?php echo Labels::getLabel('LBL_Product_Media', $adminLangId); ?><span><?php echo Labels::getLabel('LBL_Add_Option_Based_Product_Media', $adminLangId); ?></span></div>
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
    </div>
</div>
<script>
 productInitialSetUpFrm(<?php echo $productId; ?>);
</script>



                

