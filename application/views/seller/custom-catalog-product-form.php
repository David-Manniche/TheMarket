<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $this->includeTemplate('_partial/dashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header row justify-content-between mb-3">
            <div class="col-md-auto">
                <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Custom_Product_Setup', $siteLangId); ?></h2>
            </div>
            <div class="col-md-auto">
                <div class="action">
                    <a href="<?php echo CommonHelper::generateUrl('seller', 'customCatalogProducts'); ?>" class="btn btn--primary btn--sm"><?php echo Labels::getLabel('LBL_Back_to_Product_Requests', $siteLangId); ?></a>
                </div>
            </div>
        </div>
        <div class="content-body">            
            <div class="tabs">                    
                <ul class="tabs_nav-js">
                    <li>
                        <a class="tabs_001" rel="tabs_001" href="javascript:void(0)">
                            <?php echo Labels::getLabel('LBL_Initial_Setup', $siteLangId); ?> <i class="tabs-icon fa fa-info-circle"  data-toggle="tooltip" data-placement="top" title="<?php echo Labels::getLabel('LBL_Setup_Basic_Product_Details', $siteLangId); ?>">
                            </i>
                        </a>
                    </li>
                    <li><a rel="tabs_002" class="tabs_002" href="javascript:void(0)">
                            <?php echo Labels::getLabel('LBL_Product_Attribute_&_Specifications', $siteLangId); ?> 
                            <i class="tabs-icon fa fa-info-circle"  data-toggle="tooltip" data-placement="top" title="<?php echo Labels::getLabel('LBL_Add_Product_Attribute_&_Specifications', $siteLangId); ?>"></i></a>
                    </li>
                    <li><a rel="tabs_003" class="tabs_003" href="javascript:void(0)">
                            <?php echo Labels::getLabel('LBL_Product_Options_And_Tags', $siteLangId); ?>
                            <i class="tabs-icon fa fa-info-circle"  data-toggle="tooltip" data-placement="top" title="<?php echo Labels::getLabel('LBL_Add_Product_Options_And_Tags', $siteLangId); ?>"></i>

                        </a>
                    </li>

                    <li><a rel="tabs_004" class="tabs_004" href="javascript:void(0)"><?php echo Labels::getLabel('LBL_Shipping_Information', $siteLangId); ?>
                            <i class="tabs-icon fa fa-info-circle"  data-toggle="tooltip" data-placement="top" title="<?php echo Labels::getLabel('LBL_Setup_Product_Dimentions_And_Shipping_Information', $siteLangId); ?>"></i>

                        </a>
                    </li>
                    <li><a rel="tabs_005" class="tabs_005" href="javascript:void(0)"> <?php echo Labels::getLabel('LBL_Product_Media', $siteLangId); ?>
                            <i class="tabs-icon fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="<?php echo Labels::getLabel('LBL_Add_Option_Based_Product_Media', $siteLangId); ?>"></i>

                        </a>
                    </li>
                </ul>
            </div>   

            <div class="cards">
                <div class="cards-content p-3">
                    <div class="tabs__content">
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
</main>
<script>
    $(document).ready(function () {
        customCatalogProductForm(<?php echo $preqId; ?>);
    });
</script>




