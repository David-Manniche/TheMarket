<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<main class="main__content">        
    <div id="shipping-summary" class="step active" role="step:3">
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
                    <div class="delivery-address">             
                        <p><?php echo $addresses['addr_address1'] ;?> 
						<?php if(strlen($addresses['addr_address2']) > 0) { 
							echo ", ".$addresses['addr_address2'] ;?> 
						<?php } ?>
						</p>   
						<p><?php echo $addresses['addr_city'].", ".$addresses['state_name'].", ".$addresses['country_name'].", ".$addresses['addr_zip'] ;?></p>    
  
						<?php if(strlen($addresses['addr_phone']) > 0) { ?>
						<p class="phone-txt"><i class="fas fa-mobile-alt"></i><?php echo $addresses['addr_phone'] ;?></p>    
						<?php } ?>
                    </div>
                </div>
                <div class="review-block__link" role="cell">
                    <a class="link" href="javascript:void(0);" onClick="showAddressList()"><span><?php echo Labels::getLabel('LBL_Edit', $siteLangId); ?></span></a>
                </div>
            </li>
        </ul>   

        <div class="step__section">
            <div class="step__section__head">
                <h5 class="step__section__head__title"><?php echo Labels::getLabel('LBL_Shipping_Summary', $siteLangId); ?>
                </h5>
            </div>
            <?php
            if (array_key_exists(Shipping::BY_ADMIN, $shippingRates)) {
                ksort($shippingRates);
            }

            foreach ($shippingRates as $level => $levelItems) { ?>
            <ul class="list-group list-cart list-shippings">
            <?php if (isset($levelItems['products']) && count($levelItems['products']) > 1 && $level != Shipping::LEVEL_PRODUCT) { 
                $productData = current($levelItems['products']);
                ?>
                <li class="list-group-item shipping-select">
                    <div class="shop-name"><?php echo ($level == Shipping::LEVEL_SHOP) ? $productData['shop_name'] : FatApp::getConfig('CONF_WEBSITE_NAME_' . $siteLangId, null, ''); ?></div>
                    <div class="shipping-method">
                        <?php 
                        if ($level != Shipping::LEVEL_PRODUCT) {
                            if (count($levelItems['rates']) > 0) {
                                $name = current($levelItems['rates'])['code'];
                                echo '<select class="form-control custom-select" name="shipping_services[' . $name . ']">';
                                foreach ($levelItems['rates'] as $key => $shippingRate) {                                    
                                    $selected = ''; 
                                    if(!empty($orderShippingData)){
                                        foreach($orderShippingData as $shipdata){
                                            if($shipdata['opshipping_code'] == $name && ($key == $shipdata['opshipping_carrier_code']."|".$shipdata['opshipping_label'] || $key == $shipdata['opshipping_rate_id'])){
                                                $selected = 'selected=selected';  
                                                break;
                                            }
                                        }
                                    }
                                    echo '<option '.$selected.' value="' . $key . '">' . $shippingRate['title'] .' ( ' . CommonHelper::displayMoneyFormat($shippingRate['cost']) . ' ) </option>';
                                }
                                echo '</select>';
                            } else {
                                echo Labels::getLabel('MSG_Product_is_not_available_for_shipping', $siteLangId);
                            }
                        }
                    ?> 
                    </div>
                </li> 
            <?php } ?>    
            <?php if(isset($levelItems['products'])) { 
                foreach ($levelItems['products'] as $product) {
                    $productUrl = !$isAppUser ? UrlHelper::generateUrl('Products', 'View', array($product['selprod_id'])) : 'javascript:void(0)';
                    $shopUrl = !$isAppUser ? UrlHelper::generateUrl('Shops', 'View', array($product['shop_id'])) : 'javascript:void(0)';
                    $imageUrl = UrlHelper::getCachedUrl(UrlHelper::generateFileUrl('image', 'product', array($product['product_id'], "THUMB", $product['selprod_id'], 0, $siteLangId)), CONF_IMG_CACHE_TIME, '.jpg'); ?>
                <?php if (count($levelItems['products']) == 1 && count($levelItems['rates']) > 0 && $level != Shipping::LEVEL_PRODUCT) { ?>
                    <li class="list-group-item shipping-select">
                        <div class="shop-name"><?php echo $product['shop_name']; ?></div>
                        <div class="shipping-method">
                        <?php if (count($levelItems['rates']) > 0) {
                                $name = current($levelItems['rates'])['code'];
                                echo '<select class="form-control custom-select" name="shipping_services[' . $name . ']">';
                                foreach ($levelItems['rates'] as $key => $shippingRate) {                                   
                                    $selected = '';
                                    if(!empty($orderShippingData)){
                                        foreach($orderShippingData as $shipdata){
                                            if($shipdata['opshipping_code'] == $name && ($key == $shipdata['opshipping_carrier_code']."|".$shipdata['opshipping_label'] || $key == $shipdata['opshipping_rate_id'])){
                                                $selected = 'selected=selected';  
                                                break;
                                            }
                                        }
                                    }
                                    echo '<option '.$selected.' value="' . $key . '">' . $shippingRate['title'] .' ( ' . CommonHelper::displayMoneyFormat($shippingRate['cost']) . ' ) </option>';
                                }
                                echo '</select>';
                            } elseif ($product['product_type'] == Product::PRODUCT_TYPE_PHYSICAL) {
                                echo Labels::getLabel('MSG_Product_is_not_available_for_shipping', $siteLangId);
                            } ?>
                        </div>
                    </li> 
                <?php }?>    

                <?php if ($level == Shipping::LEVEL_PRODUCT && isset($levelItems['rates'][$product['selprod_id']])) {  ?>
                </ul><ul class="list-group list-cart list-shippings">
                    <li class="list-group-item shipping-select">
                    <div class="shop-name"><?php echo $product['shop_name']; ?></div>
                    <div class="shipping-method">
                        <?php $priceListCount = count($levelItems['rates'][$product['selprod_id']]);
                            if ($priceListCount > 0) {
                                $name = current($levelItems['rates'][$product['selprod_id']])['code'];
                                echo '<select class="form-control custom-select" name="shipping_services[' . $name . ']">';
                                    foreach ($levelItems['rates'][$product['selprod_id']] as $key => $shippingRate) {                                       
                                        $selected = '';  
                                        if(!empty($orderShippingData)){
                                            foreach($orderShippingData as $shipdata){
                                                if($shipdata['opshipping_code'] == $name && ($key == $shipdata['opshipping_carrier_code']."|".$shipdata['opshipping_label'] || $key == $shipdata['opshipping_rate_id'])){
                                                    $selected = 'selected=selected';  
                                                    break;
                                                }
                                            }
                                        }
                                        echo '<option '.$selected.' value="' . $key . '">' . $shippingRate['title'] .' ( ' . CommonHelper::displayMoneyFormat($shippingRate['cost']) . ' ) </option>';
                                    }
                                    echo '</select>';
                            } elseif ($product['product_type'] == Product::PRODUCT_TYPE_PHYSICAL) {
                                echo Labels::getLabel('MSG_Product_is_not_available_for_shipping', $siteLangId);
                            }
                       
                    ?> 
                    </div>
                </li> 
            <?php }?>                    
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
                                } ?></p>
                            </div>
                            <div class="quantity quantity-2">
                                <span class="decrease decrease-js"><i class="fas fa-minus"></i></span>
                                <input class="qty-input no-focus cartQtyTextBox productQty-js" title="<?php echo Labels::getLabel('LBL_Quantity', $siteLangId) ?>" data-page="checkout"  type="text" name="qty_<?php echo md5($product['key']); ?>" data-key="<?php echo md5($product['key']); ?>" value="<?php echo $product['quantity']; ?>">
                                <span class="increase increase-js"><i class="fas fa-plus"></i></span>
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
                <?php if (isset($levelItems['products']) && count($levelItems['products']) == 1) { ?> </ul> <?php }?> 
                <?php }
                } ?> 

                <?php if (isset($levelItems['products']) && count($levelItems['products']) > 1) { ?>
                    </ul>
                <?php }?>                                                             
                
                <?php if (isset($levelItems['digital_products']) && count($levelItems['digital_products']) > 0) { ?>
                <ul class="list-group list-cart list-shippings">
                <?php   $count = 0; 
                foreach ($levelItems['digital_products'] as $product) { 
                    $productUrl = !$isAppUser ? UrlHelper::generateUrl('Products', 'View', array($product['selprod_id'])) : 'javascript:void(0)';
                    $shopUrl = !$isAppUser ? UrlHelper::generateUrl('Shops', 'View', array($product['shop_id'])) : 'javascript:void(0)';
                    $imageUrl = UrlHelper::getCachedUrl(UrlHelper::generateFileUrl('image', 'product', array($product['product_id'], "THUMB", $product['selprod_id'], 0, $siteLangId)), CONF_IMG_CACHE_TIME, '.jpg'); 
                    if($count == 0) {
                ?>  
                    <li class="list-group-item shipping-select">
                        <div class="shop-name"><?php echo $product['shop_name']; ?></div>
                    </li>
                    <?php } ?>
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
                                    } ?></p>
                                </div>
                                <div class="quantity quantity-2">
                                    <span class="decrease decrease-js"><i class="fas fa-minus"></i></span>
                                    <input class="qty-input no-focus cartQtyTextBox productQty-js" title="<?php echo Labels::getLabel('LBL_Quantity', $siteLangId) ?>" data-page="checkout"  type="text" name="qty_<?php echo md5($product['key']); ?>" data-key="<?php echo md5($product['key']); ?>" value="<?php echo $product['quantity']; ?>">
                                    <span class="increase increase-js"><i class="fas fa-plus"></i></span>
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
                                    <a href="javascript:void(0);" onclick="cart.remove('<?php echo md5($product['key']); ?>','checkout')">
                                    <svg class="svg" width="24px" height="24px"><use xlink:href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#remove"
                                                href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#remove">
                                            </use>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php $count++;  } ?>
                </ul>
                <?php   } ?>
            
            <?php }?>            
        </div>
        <div class="step__footer">
            <a class="btn btn-outline-primary btn-wide" href="javascript:void(0)" onclick="showAddressList();">
                <?php echo Labels::getLabel('LBL_Back', $siteLangId); ?>
            </a>
            <?php if($hasPhysicalProd){ ?>
            <a class="btn btn-primary btn-wide " onClick="setUpShippingMethod();" href="javascript:void(0)">
                <?php echo Labels::getLabel('LBL_Continue', $siteLangId); ?>
            </a>
            <?php }else{ ?>
            <a class="btn btn-primary btn-wide " onClick="loadPaymentSummary();" href="javascript:void(0)">
                <?php echo Labels::getLabel('LBL_Continue', $siteLangId); ?>
            </a>
            <?php } ?>
        </div>
    </div>
</main>