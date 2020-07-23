<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<main class="main__content">        
    <div class="step active" role="step:3">
        <ul class="list-group review-block">
            <li class="list-group-item">
                <div class="review-block__label">
                    <?php if ($hasPhysicalProd) {
                    echo Labels::getLabel('LBL_Shipping_to:', $siteLangId);
                } else {
                    echo Labels::getLabel('LBL_Billing_to:', $siteLangId);
                } ?>
                </div>
                <div class="review-block__content" role="cell">
                    <?php echo $addresses['addr_title']; ?>
                    <?php echo $addresses['addr_name']; ?>
                    <?php echo $addresses['addr_address1'] . '<br>';?>
                    <?php echo $addresses['addr_city'];?>,
                    <?php echo $addresses['state_name'];?>,
                    <?php echo (strlen($addresses['addr_zip']) > 0) ? Labels::getLabel('LBL_Zip:', $siteLangId) . ' ' . $addresses['addr_zip'] . ', ' : '';?>
                    <?php echo (strlen($addresses['addr_phone']) > 0) ? Labels::getLabel('LBL_Phone:', $siteLangId) . ' ' . $addresses['addr_phone'] . '<br>' : '';?>
                </div>
                <div class="review-block__link" role="cell">
                    <a class="link" href="#" onClick="showAddressList()"><span><?php echo Labels::getLabel('LBL_Change_Address', $siteLangId); ?></span></a>
                </div>
            </li>
        </ul>
        <div class="step__section">
            <div class="step__head">
                <h5 class="step-title"><?php echo Labels::getLabel('LBL_Shipping_Summary', $siteLangId); ?>
                </h5>
            </div>
            <?php
            if (array_key_exists(Shipping::BY_ADMIN, $shippingRates)) {
                ksort($shippingRates);
            }

            foreach ($shippingRates as $level => $levelItems) { ?>
            <ul class="list-group list-cart list-shippings">
                <?php foreach ($levelItems['products'] as $product) {
                    $productUrl = !$isAppUser ? UrlHelper::generateUrl('Products', 'View', array($product['selprod_id'])) : 'javascript:void(0)';
                    $shopUrl = !$isAppUser ? UrlHelper::generateUrl('Shops', 'View', array($product['shop_id'])) : 'javascript:void(0)';
                    $imageUrl = UrlHelper::getCachedUrl(UrlHelper::generateFileUrl('image', 'product', array($product['product_id'], "THUMB", $product['selprod_id'], 0, $siteLangId)), CONF_IMG_CACHE_TIME, '.jpg'); ?>
                    
                <li class="list-group-item">
                    <div class="product-profile">
                        <div class="product-profile__thumbnail">
                            <a href="<?php echo $productUrl; ?>">
                                <img class="img-fluid" data-ratio="3:4" src="<?php echo $imageUrl; ?>"
                                    alt="<?php echo $product['product_name']; ?>" title="<?php echo $product['product_name']; ?>">
                            </a></div>                                
                        <div class="product-profile__data">
                            <div class="title"><a class="" href="<?php echo $productUrl; ?>"><?php echo ($product['selprod_title']) ? $product['selprod_title'] : $product['product_name']; ?></a></div>
                            <div class="options">
                                <p class=""> <?php if (isset($product['options']) && count($product['options'])) {
                                    $optionStr = '';
                                    foreach ($product['options'] as $option) {
                                        $optionStr .= $option['optionvalue_name'] . '|';
                                    }
                                    echo rtrim($optionStr, '|');
                                } ?> | <?php echo $product['quantity'] ;?></p>
                            </div>
                        </div>
                    </div>
                    <div class="product-price"><?php echo CommonHelper::displayMoneyFormat($product['theprice'] * $product['quantity']); ?> 
                    <?php if ($product['special_price_found']) { ?>
                        <del><?php echo CommonHelper::showProductDiscountedText($product, $siteLangId); ?></del>
                    <?php }?>
                    </div>
                    <div class="product-action">
                        <ul class="list-actions">
                            <li>
                                <a href="#" onclick="cart.remove('<?php echo md5($product['key']); ?>','checkout')">
                                <svg class="svg" width="24px" height="24px"><use xlink:href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#remove"
                                            href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#remove">
                                        </use>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php }?>
                <li class="list-group-item shipping-select">
                    <?php 
                    if ($level != Shipping::LEVEL_PRODUCT) {
                        $priceListCount = count($levelItems['rates']);
                        if ($priceListCount == 1 && current($levelItems['rates'])['cost'] == 0) {
                            echo Labels::getLabel('LBL_Free_Shipping', $siteLangId) ;
                        } else {
                            if (count($levelItems['rates']) > 0) {
                                $name = current($levelItems['rates'])['code'];
                                echo '<select class="form-control custom-select" name="shipping_services[' . $name . ']">';
                                foreach ($levelItems['rates'] as $key => $shippingRate) {
                                    echo '<option value="' . $key . '">' . $shippingRate['title'] .' ( ' . $shippingRate['cost'] . ' ) </option>';
                                }
                                echo '</select>';
                            } elseif ($product['product_type'] == Product::PRODUCT_TYPE_PHYSICAL) {
                                echo Labels::getLabel('MSG_Product_is_not_available_for_shipping', $siteLangId);
                            }
                        }
                    }

                    if ($level == Shipping::LEVEL_PRODUCT && isset($levelItems['rates'][$product['selprod_id']])) {
                        $priceListCount = count($levelItems['rates'][$product['selprod_id']]);
                        if ($priceListCount == 1 && current($levelItems['rates'][$product['selprod_id']])['cost'] == 0) {
                            echo Labels::getLabel('LBL_Free_Shipping', $siteLangId) ;
                        } else {
                            if ($priceListCount > 0) {
                                $name = current($levelItems['rates'][$product['selprod_id']])['code'];
                                echo '<select class="form-control custom-select" name="shipping_services[' . $name . ']">';
                                    foreach ($levelItems['rates'][$product['selprod_id']] as $key => $shippingRate) {
                                        echo '<option value="' . $key . '">' . $shippingRate['title'] .' ( ' . $shippingRate['cost'] . ' ) </option>';
                                    }
                                    echo '</select>';
                            } elseif ($product['product_type'] == Product::PRODUCT_TYPE_PHYSICAL) {
                                echo Labels::getLabel('MSG_Product_is_not_available_for_shipping', $siteLangId);
                            }
                        }
                    }
                    ?>   
                </li>
                <?php if ($level == Shipping::LEVEL_PRODUCT) { ?>
                </ul>
                <ul class="list-group list-cart list-shippings">
                <?php }?> 
            </ul>
            <?php }?>
        </div>
        <div class="step__footer">
            <a class="btn btn-link" href="javascript:void(0)" onclick="showAddressList();">
                <i class="arrow">
                    <svg class="svg">
                        <use xlink:href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#arrow-left"
                            href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#arrow-left">
                        </use>
                    </svg></i>
                <span class=""><?php echo Labels::getLabel('LBL_Back', $siteLangId); ?></span></a>
            <a class="btn btn-primary btn-wide " onClick="setUpShippingMethod();" href="javascript:void(0)"><?php echo Labels::getLabel('LBL_Continue', $siteLangId); ?></a>
        </div>
    </div>
</main>