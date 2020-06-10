<div class="section-head">
    <div class="section__heading">
        <h2><?php echo Labels::getLabel('LBL_Shipping_Summary', $siteLangId); ?>
        </h2>
    </div>
</div>
<div class="box box--white box--radius p-4">
    <section id="shipping-summary" class="section-checkout">
        <div class="review-wrapper step__body">
        <?php
            if (array_key_exists(Shipping::BY_ADMIN, $shippingRates)) {
                    ksort($shippingRates);
                    foreach ($shippingRates as $level => $levelItems) { ?>
                    <div class="short-detail">
                    <?php if (count($levelItems['products']) > 1 && $level != Shipping::LEVEL_PRODUCT) {  ?>
                    <div class="shipping-seller">
                            <div class="row  justify-content-between">
                                <div class="col-auto">
                                    <div class="shipping-seller-title"></div>
                                </div>
                                <div class="col-auto">
                                    <ul class="shipping-selectors">
                                    <?php
                                       $priceListCount = count($levelItems['rates']);
                                       if ($priceListCount == 1 && current($levelItems['rates'])['cost'] == 0) {
                                           echo '<li class="info-message">' . Labels::getLabel('LBL_Free_Shipping', $siteLangId) . '</li>';
                                       } else {
                                            if(count($levelItems['rates']) > 0) {
                                                $name = current($levelItems['rates'])['code'];
                                               echo '<li><select name="shipping_services[' . $name . ']">';
                                               foreach ($levelItems['rates'] as $key => $shippingRate) { 
                                                   echo '<option value="' . $key . '">' . $shippingRate['title'] .' ( ' . $shippingRate['cost'] . ' ) </option>';
                                               }
                                               echo '</select></li>';
                                           } else if ($product['product_type'] == Product::PRODUCT_TYPE_PHYSICAL) {
                                               echo '<li class="info-message">' . Labels::getLabel('MSG_Product_is_not_available_for_shipping', $siteLangId) . '</li>';
                                           }
                                       } 
                                    ?>
                                    </ul>                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }
                    foreach ($levelItems['products'] as $product) {
                        $productUrl = !$isAppUser ? CommonHelper::generateUrl('Products', 'View', array($product['selprod_id'])) : 'javascript:void(0)';
                        $shopUrl = !$isAppUser ? CommonHelper::generateUrl('Shops', 'View', array($product['shop_id'])) : 'javascript:void(0)';
                        $imageUrl = FatCache::getCachedUrl(CommonHelper::generateUrl('image', 'product', array($product['product_id'], "THUMB", $product['selprod_id'], 0, $siteLangId)), CONF_IMG_CACHE_TIME, '.jpg'); ?>
            
            <table class="table table-shipping">
                <tbody>
                    <tr class="<?php echo (!$product['in_stock']) ? 'disabled' : ''; ?>">
                        <td width="10%">
                            <figure class="item__pic"><a
                                    href="<?php echo $productUrl; ?>"><img
                                        src="<?php echo $imageUrl; ?>"
                                        alt="<?php echo $product['product_name']; ?>"
                                        title="<?php echo $product['product_name']; ?>"></a>
                            </figure>
                        </td>
                        <td width="30%">
                        <div class="item__description">
                                <div class="item__category"><?php echo Labels::getLabel('LBL_Shop', $siteLangId) ?>: <span class="text-dark"><?php echo $product['shop_name']; ?></span></div>
                                <div class="item__title"><a title="<?php echo ($product['selprod_title']) ? $product['selprod_title'] : $product['product_name']; ?>" href="<?php echo $productUrl; ?>"><?php echo ($product['selprod_title']) ? $product['selprod_title'] : $product['product_name']; ?></a></div>
                                <div class="item__specification">
                                <?php if (isset($product['options']) && count($product['options'])) {
                            foreach ($product['options'] as $option) {
                                echo ' | ' . $option['option_name'].':'; ?>
                                    <span class="text-dark"><?php echo $option['optionvalue_name']; ?></span>
                                    <?php
                            }
                        } ?>
                                    | <?php echo Labels::getLabel('LBL_Quantity', $siteLangId) ?> <?php echo $product['quantity']; ?>
                                    <?php /* if (($product['shop_eligible_for_free_shipping'] > 0 || ($product['shop_free_ship_upto'] > 0 && $product['shop_free_ship_upto'] > $product['totalPrice']))  && $product['psbs_user_id'] == 0 && $product['product_type'] == Product::PRODUCT_TYPE_PHYSICAL) { ?>
                                    <div class="item-yk-head-specification note-messages">
                                        <?php echo Labels::getLabel('LBL_This_product_is_not_eligible_for_free_shipping', $siteLangId);    ?>
                                    </div>
                                    <?php } */ ?>
                                </div>
                            </div>
                        </td>
                        <td width="40%">
                        <ul class="shipping-selectors">
                        <?php 
                        if (count($levelItems['products']) == 1 && count($levelItems['rates']) > 0 && $level != Shipping::LEVEL_PRODUCT) {
                            $priceListCount = count($levelItems['rates']);
                            if ($priceListCount == 1 && current($levelItems['rates'])['cost'] == 0) {
                                echo '<li class="info-message">' . Labels::getLabel('LBL_Free_Shipping', $siteLangId) . '</li>';
                            } else {
                                 if(count($levelItems['rates']) > 0) {
                                     $name = current($levelItems['rates'])['code'];
                                    echo '<li><select name="shipping_services[' . $name . ']">';
                                    foreach ($levelItems['rates'] as $key => $shippingRate) { 
                                        echo '<option value="' . $key . '">' . $shippingRate['title'] .' ( ' . $shippingRate['cost'] . ' ) </option>';
                                    }
                                    echo '</select></li>';
                                } else if ($product['product_type'] == Product::PRODUCT_TYPE_PHYSICAL) {
                                    echo '<li class="info-message">' . Labels::getLabel('MSG_Product_is_not_available_for_shipping', $siteLangId) . '</li>';
                                }
                            } 
                        }
                        
                        if ($level == Shipping::LEVEL_PRODUCT && isset($levelItems['rates'][$product['selprod_id']])) {
                                $priceListCount = count($levelItems['rates'][$product['selprod_id']]);
                                if ($priceListCount == 1 && current($levelItems['rates'][$product['selprod_id']])['cost'] == 0) {
                                    echo '<li class="info-message">' . Labels::getLabel('LBL_Free_Shipping', $siteLangId) . '</li>';
                                } else {
                                    if ($priceListCount > 0) {
                                        $name = current($levelItems['rates'][$product['selprod_id']])['code'];
                                        echo '<li><select name="shipping_services[' . $name . ']">';
                                        foreach ($levelItems['rates'][$product['selprod_id']] as $key => $shippingRate) {
                                            echo '<option value="' . $key . '">' . $shippingRate['title'] .' ( ' . $shippingRate['cost'] . ' ) </option>';
                                        }
                                        echo '</select></li>';
                                    } elseif ($product['product_type'] == Product::PRODUCT_TYPE_PHYSICAL) {
                                        echo '<li class="info-message">' . Labels::getLabel('MSG_Product_is_not_available_for_shipping', $siteLangId) . '</li>';
                                    }
                                }
                            } ?>
                        </ul>
                        </td>
                        <td width="10%">
							<span class="item__price"><?php echo CommonHelper::displayMoneyFormat($product['theprice'] * $product['quantity']); ?> </span>
                            <?php if ($product['special_price_found']) { ?>
                            <span class="item__price_off text-nowrap"><?php echo CommonHelper::showProductDiscountedText($product, $siteLangId); ?></span>
                            <?php } ?>
                            </td>
                        <td width="10%">
                            <a href="javascript:void(0)"
                                onclick="cart.remove('<?php echo md5($product['key']); ?>','checkout')"
                                class="icons-wrapper"><i class="icn"><svg class="svg">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#bin"
                                            href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#bin">
                                        </use>
                                    </svg></i></a>
                        </td>
                    <tr>

                </tbody>
            </table>
            
            <?php if($level == Shipping::LEVEL_PRODUCT) {?>
                </div>
                <div class="short-detail">
            <?php } 
            }

        }
    }
?>

            <div>
    </section>
    <div class="row align-items-center justify-content-between mt-4">
        <div class="col"><a class="btn btn-outline-primary" onclick="showAddressList();"
                href="javascript:void(0)"><?php echo Labels::getLabel('LBL_Back', $siteLangId); ?></a>
        </div>
        <div class="col-auto">
            <a class="btn btn--primary " onClick="setUpShippingMethod();" href="javascript:void(0)"><?php echo Labels::getLabel('LBL_Continue', $siteLangId); ?></a>
        </div>
    </div>
</div>