<?php
defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="row">
    <div class="col-xl-9 col-lg-8 mb-3 mb-lg-0">
        <div class="box box--white box--radius box--space">
            <?php if (count($products)) { ?>
            <ul class="cart-list">
                <?php foreach ($products as $product) { 
                        $productUrl = CommonHelper::generateUrl('Products', 'View', array($product['selprod_id']));
                        $shopUrl = CommonHelper::generateUrl('Shops', 'View', array($product['shop_id']));
                        $imageUrl = UrlHelper::getCachedUrl(UrlHelper::generateFileUrl('image', 'product', array($product['product_id'], "THUMB", $product['selprod_id'], 0, $siteLangId)), CONF_IMG_CACHE_TIME, '.jpg');               
               ?>
                <li class="cart-list-item <?php echo md5($product['key']); ?> <?php echo (!$product['in_stock']) ? 'disabled' : ''; ?>">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <!-- Image -->
                            <div class="item__pic"><a href="<?php echo $productUrl; ?>"><img src="<?php echo $imageUrl; ?>" alt="<?php echo $product['product_name']; ?>" title="<?php echo $product['product_name']; ?>"></a></div>
                        </div>
                        <div class="col">
                           <div class="cart-list-detail">
                            <div class="base-detail">
                                <div class="item__description">
                                    <div class="item__category"><?php echo Labels::getLabel('LBL_Brand', $siteLangId).': '; ?><span class="text--dark"><?php echo $product['brand_name']; ?></span></div>
                                    <div class="item__title"><a title="<?php echo ($product['selprod_title']) ? $product['selprod_title'] : $product['product_name']; ?>" href="<?php echo $productUrl; ?>"><?php echo ($product['selprod_title']) ? $product['selprod_title'] : $product['product_name']; ?></a></div>
                                    <div class="item__specification">
                                        <?php 
                                        if (isset($product['options']) && count($product['options'])) {
                                            foreach ($product['options'] as $key => $option) {
                                                if (0 < $key){
                                                    echo ' | ';
                                                }
                                                echo $option['option_name'].':'; ?> <span class="text--dark"><?php echo $option['optionvalue_name']; ?></span>
                                                <?php }
                                        } ?>
                                    </div>
                                </div>
                                <div class="qty-wrapper qty-wrapper-sm">
                                    <div class="quantity" data-stock="<?php echo $product['selprod_stock']; ?>">
                                        <span class="decrease decrease-js <?php echo ($product['quantity']<=$product['selprod_min_order_qty']) ? 'not-allowed' : '' ;?>">-</span>
                                        <div class="qty-input-wrapper" data-stock="<?php echo $product['selprod_stock']; ?>">
                                            <input name="qty_<?php echo md5($product['key']); ?>" data-key="<?php echo md5($product['key']); ?>" class="qty-input cartQtyTextBox productQty-js" value="<?php echo $product['quantity']; ?>" type="text" />
                                        </div>
                                        <span class="increase increase-js <?php echo ($product['selprod_stock'] <= $product['quantity']) ? 'not-allowed' : '';?>">+</span>
                                    </div>
                                </div>

                            </div>
                            <div class="base-price">
                                <div class="base-price-line"><span><?php echo Labels::getLabel('LBL_Selling_Price', $siteLangId); ?>:</span><span class="item__price_selling"><?php echo CommonHelper::displayMoneyFormat($product['theprice']); ?></span>
                                </div>
                                <div class="base-price-line"><span><?php echo Labels::getLabel('LBL_Subtotal', $siteLangId); ?>: </span> <span class="item__price_total"><?php echo CommonHelper::displayMoneyFormat($product['total']); ?> </span></div>
                            </div></div>
                        </div>
                    </div>
                    <div class="cart-list-footer">
                        <div class="row">
                            <div class="col-auto">
                                <a href="javascript:void(0)" class="" onclick="cart.remove('<?php echo md5($product['key']); ?>','cart')" title="<?php echo Labels::getLabel('LBL_Remove', $siteLangId); ?>"><?php echo Labels::getLabel('LBL_Remove', $siteLangId); ?></a></div>

                            <div class="col">
                                <?php
                                $showAddToFavorite = true;
                                if (UserAuthentication::isUserLogged() && (!User::isBuyer())) {
                                    $showAddToFavorite = false;
                                }

                                if ($showAddToFavorite) { ?>

                                <?php if (FatApp::getConfig('CONF_ADD_FAVORITES_TO_WISHLIST', FatUtility::VAR_INT, 1) == applicationConstants::NO) {
                                        if (empty($product['ufp_id'])) {  ?>
                                <a href="javascript:void(0)" class="" onClick="addToFavourite( '<?php echo md5($product['key']); ?>',<?php echo $product['selprod_id']; ?> );" title="<?php echo Labels::getLabel('LBL_Move_to_wishlist', $siteLangId); ?>"><?php echo Labels::getLabel('LBL_Move_to_favourites', $siteLangId); ?></a>
                                <?php } else {
                                            echo Labels::getLabel('LBL_Already_marked_as_favourites.', $siteLangId);
                                        }
                                    } else {
                                        if (empty($product['is_in_any_wishlist'])) { ?>
                                <a href="javascript:void(0)" class="" onClick="moveToWishlist( <?php echo $product['selprod_id']; ?>, event, '<?php echo md5($product['key']); ?>' );" title="<?php echo Labels::getLabel('LBL_Move_to_wishlist', $siteLangId); ?>"><?php echo Labels::getLabel('LBL_Move_to_wishlist', $siteLangId); ?></a>
                                <?php  } else {

                                            echo Labels::getLabel('LBL_Already_added_to_your_wishlist.', $siteLangId);
                                        }
                                    }
                                } ?>

                            </div>
                        </div>
                    </div>

                </li>
                <?php } ?>
            </ul>
            <?php } ?>  
        </div>
    </div>
    <div class="col-xl-3 col-lg-4">
        <div class="box box--white box--radius box--space cart-footer">
            <?php if (!empty($cartSummary['cartDiscounts']['coupon_code'])) { ?>
                <div class="applied-coupon">
                    <span>
                        <?php echo Labels::getLabel("LBL_Coupon", $siteLangId); ?> "<strong><?php echo $cartSummary['cartDiscounts']['coupon_code']; ?></strong>" <?php echo Labels::getLabel("LBL_Applied", $siteLangId); ?>
                    </span>
                    <a href="javascript:void(0)" onClick="removePromoCode()" class="btn btn-primary btn-sm">
                        <?php echo Labels::getLabel("LBL_Remove", $siteLangId); ?>
                    </a>
                </div>
                <?php } else { ?>
                    <div class="coupon">
                        <a class="coupon-input btn btn-primary btn-block" href="javascript:void(0)" onclick="getPromoCode()"><?php echo Labels::getLabel('LBL_I_have_a_coupon', $siteLangId); ?></a>
                    </div>
                <?php } ?>

            <div class="cartdetail__footer">
                <table class="table--justify">
                    <tbody>
                        <tr>
                            <td><?php echo Labels::getLabel('LBL_Total', $siteLangId); ?></td>
                            <td><?php echo CommonHelper::displayMoneyFormat($cartSummary['cartTotal']); ?></td>
                        </tr>
                        <?php if ($cartSummary['cartVolumeDiscount']) { ?>
                        <tr>
                            <td><?php echo Labels::getLabel('LBL_Volume_Discount', $siteLangId); ?></td>
                            <td><?php echo CommonHelper::displayMoneyFormat($cartSummary['cartVolumeDiscount']); ?></td>
                        </tr>
                        <?php  } ?>
                        <?php if (FatApp::getConfig('CONF_TAX_AFTER_DISOCUNT', FatUtility::VAR_INT, 0) && !empty($cartSummary['cartDiscounts'])) { ?>
                        <tr>
                            <td><?php echo Labels::getLabel('LBL_Discount', $siteLangId); ?></td>
                            <td><?php echo CommonHelper::displayMoneyFormat($cartSummary['cartDiscounts']['coupon_discount_total']); ?></td>
                        </tr>
                        <?php }?>
                        <?php $netChargeAmt = $cartSummary['cartTotal'] + $cartSummary['cartTaxTotal'] - ((0 < $cartSummary['cartVolumeDiscount'])?$cartSummary['cartVolumeDiscount']:0);?>
                        <?php $netChargeAmt = $netChargeAmt - ((0 < $cartSummary['cartDiscounts']['coupon_discount_total'])?$cartSummary['cartDiscounts']['coupon_discount_total']:0);?>
                        <?php if (isset($cartSummary['taxOptions']) && !empty($cartSummary['taxOptions'])) { 
                        foreach($cartSummary['taxOptions'] as $taxName => $taxVal){ ?>
                        <tr>
                            <td><?php echo $taxVal['title']; ?></td>
                            <td><?php echo CommonHelper::displayMoneyFormat($taxVal['value']); ?></td>
                        </tr>
                        <?php   }
                     }?>
                        <?php if (!FatApp::getConfig('CONF_TAX_AFTER_DISOCUNT', FatUtility::VAR_INT, 0) && !empty($cartSummary['cartDiscounts'])) { ?>
                        <tr>
                            <td><?php echo Labels::getLabel('LBL_Discount', $siteLangId); ?></td>
                            <td><?php echo CommonHelper::displayMoneyFormat($cartSummary['cartDiscounts']['coupon_discount_total']); ?></td>
                        </tr>
                        <?php }?>
                        <tr>
                            <td class="hightlighted"><?php echo Labels::getLabel('LBL_Net_Payable', $siteLangId); ?></td>
                            <td class="hightlighted"><?php echo CommonHelper::displayMoneyFormat($netChargeAmt); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="buy-group">
                                    <a class="btn btn-primary" href="<?php echo CommonHelper::generateUrl(); ?>"><?php echo Labels::getLabel('LBL_Shop_More', $siteLangId); ?></a>
                                    <a class="btn btn-outline-primary" href="javascript:void(0)" onclick="goToCheckout()"><?php echo Labels::getLabel('LBL_Checkout', $siteLangId); ?></a>

                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php if (CommonHelper::getCurrencyId() != FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1)) { ?>
            <div class="summary__row">
                <p class="note align--right"><?php echo CommonHelper::currencyDisclaimer($siteLangId, $cartSummary['orderNetAmount']); ?> </p>
            </div>
            <?php } ?>
            <div class="cart-advices">
                <div class="row">
                    <div class="col-lg-6 mb-sm-2">
                        <div class="advices-icons"><i class="icn"><img src="<?php echo CONF_WEBROOT_URL; ?>images/retina/icn-safe.svg"></i>
                            <h6> <?php echo Labels::getLabel('LBL_Safe_&_Secure', $siteLangId);?></h6>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="advices-icons"><i class="icn"><img src="<?php echo CONF_WEBROOT_URL; ?>images/retina/icn-protection.svg"></i>
                            <h6><?php echo Labels::getLabel('LBL_Payment_Protection', $siteLangId);?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>