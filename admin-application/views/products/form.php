<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$productFrm->setFormTagAttribute('class', 'web_form');
$productFrm->setFormTagAttribute('onsubmit', 'setupProduct(this); return(false);');

$productFrm->developerTags['colClassPrefix'] = 'col-md-';
$productFrm->developerTags['fld_default_col'] = 6;
?>
<div class="page">
    <div class="container container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 space">
                <div class="tabs_nav_container vertical wizard-tabs-vertical">
                    
                    <ul class="tabs_nav">
                         <li><a class="active" rel="tabs_001" href="javascript:void(0)">
                                 <i class="tabs-icon fa fa-globe"></i>
                                 <div class="tabs-head">
                                     <div class="tabs-title">Initial Setup<span>Setup Basic Product Details</span></div>
                                 </div>

                             </a></li>
                         <li><a rel="tabs_002" href="javascript:void(0)"> <i class="tabs-icon fa fa-globe"></i>
                                 <div class="tabs-head">
                                     <div class="tabs-title">Product Options &amp; Specifications<span>Add Product Specification &amp; Option details</span></div>

                                 </div>
                             </a></li>
                         <li><a rel="tabs_003" href="javascript:void(0)"> <i class="tabs-icon fa fa-globe"></i>
                                 <div class="tabs-head">
                                     <div class="tabs-title">Product Attribute<span>Add Product Related Specifications</span></div>
                                 </div>
                             </a></li>

                         <li><a rel="tabs_004" href="javascript:void(0)"> <i class="tabs-icon fa fa-globe"></i>
                                 <div class="tabs-head">
                                     <div class="tabs-title">Shipping Information<span>Setup Product Dimentions &amp; Shipping Information</span></div>
                                 </div>
                             </a></li>
                         <li><a rel="tabs_005" href="javascript:void(0)"> <i class="tabs-icon fa fa-globe"></i>
                                 <div class="tabs-head">
                                     <div class="tabs-title"> Product Media<span>Add Option Based Product Media</span></div>
                                 </div>
                             </a></li>

                     </ul>
                     
                     <div class="tabs_panel_wrap">
                        <div id="tabs_001" class="tabs_panel" style="display: block;">
                             <div class="row justify-content-center">
                                 <div class="col-md-9">
                                     <?php echo $productFrm->getFormHtml(); ?>
                                 </div>
                             </div>
                         </div>
                     </div>
                     
                </div>
            </div>
        </div>
    </div>
</div>



                    <ul class="tabs_nav">
                        <li>
                            <a class="active" href="javascript:void(0);">
                                <?php echo Labels::getLabel('LBL_General', $adminLangId); ?>
                            </a>
                        </li>
                        <li class="<?php echo (0 == $product_id) ? 'fat-inactive' : ''; ?>">
                            <a href="javascript:void(0);" <?php echo (0 < $product_id) ? "onclick='productLangForm(" . $product_id . "," . FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1) . ");'" : ""; ?>>
                                <?php echo Labels::getLabel('LBL_Language_Data', $adminLangId); ?>
                            </a>
                        </li>
                    </ul>
                   
                

