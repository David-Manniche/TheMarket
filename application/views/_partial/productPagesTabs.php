 
    <div class="col-auto">
        <div class="tabs">
            <ul>
                <li class="<?php echo ($controllerName == 'seller' && $action == 'catalog') ? 'is-active' : ''; ?>">
                    <a href="<?php echo UrlHelper::generateUrl('seller', 'catalog');?>">
                        <?php echo Labels::getLabel('LBL_Marketplace_Products', $siteLangId); ?>
                    </a>
                    <a href="javascript:void(0)" onClick="productInstructions(<?php echo Extrapage::MARKETPLACE_PRODUCT_INSTRUCTIONS; ?>)"> <i class="fa fa-question-circle"></i></a>
                </li>
                <li class="<?php echo ($controllerName == 'seller' && $action == 'products') ? 'is-active' : ''; ?>">
                    <a href="<?php echo UrlHelper::generateUrl('seller', 'products');?>">
                        <?php echo Labels::getLabel('LBL_My_Inventory', $siteLangId); ?>
                    </a>
                    <a href="javascript:void(0)" onClick="productInstructions(<?php echo Extrapage::SELLER_INVENTORY_INSTRUCTIONS; ?>)"> <i class="fa fa-question-circle"></i></a>
                </li>
                <?php if (User::canAddCustomProductAvailableToAllSellers()) {?>
                    <li class="<?php echo ($controllerName == 'seller' && $action == 'customCatalogProducts') ? 'is-active' : '';?>">
                        <a href="<?php echo UrlHelper::generateUrl('seller', 'customCatalogProducts'); ?>">
                            <?php echo Labels::getLabel('LBL_Send_Products_Request', $siteLangId); ?>
                        </a>
                        <a href="javascript:void(0)" onClick="productInstructions(<?php echo Extrapage::PRODUCT_REQUEST_INSTRUCTIONS; ?>)"> <i class="fa fa-question-circle"></i></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php if($canEdit) { ?>
    <div class="col-auto">
        <div class="btn-group">
            <?php if (User::canAddCustomProduct() && $action == 'catalog') { ?>
            <a href="<?php echo UrlHelper::generateUrl('seller', 'customProductForm');?>" class="btn btn-outline-primary btn--sm"><?php echo Labels::getLabel('LBL_Add_New_Product', $siteLangId);?></a>
            <?php } ?>
            <!--<a href="<?php /* echo UrlHelper::generateUrl('seller','products');?>" class="btn btn--primary btn--sm "><?php echo Labels::getLabel( 'LBL_My_Inventory', $siteLangId) */?></a>-->
            <?php if ((isset($canAddCustomProduct) && $canAddCustomProduct==false) && (isset($canRequestProduct) && $canRequestProduct === true)) {?>
            <a href="<?php echo UrlHelper::generateUrl('Seller', 'requestedCatalog');?>" class="btn btn-outline-primary btn--sm"><?php echo Labels::getLabel('LBL_Request_A_Product', $siteLangId);?></a>
            <?php } ?>
            <?php if (User::canAddCustomProductAvailableToAllSellers() && $action == 'customCatalogProducts') {?>
            <a href="<?php echo UrlHelper::generateUrl('Seller', 'customCatalogProductForm');?>" class="btn btn-outline-primary btn--sm"><?php echo Labels::getLabel('LBL_Request_New_Product', $siteLangId);?></a>
            <?php }?>
        </div>
    </div>
    <?php } ?>
 
