<?php
defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="shiporpickup">
            <ul>
                <li><input class="control-input" type="radio" id="shipping" name="shippingType" checked="">
                    <label class="control-label" for="shipping">
                        <svg class="svg">
                            <use xlink:href="../images/retina/sprite.svg#shipping" href="../images/retina/sprite.svg#shipping">
                            </use>
                        </svg> <?php echo Labels::getLabel('LBL_SHIP_MY_ORDER', $siteLangId);?>
                    </label>

                </li>
                <li class="disabled"><input class="control-input" type="radio" id="pickup" name="shippingType">
                    <label class="control-label" for="pickup">
                        <svg class="svg">
                            <use xlink:href="../images/retina/sprite.svg#pickup" href="../images/retina/sprite.svg#pickup">
                            </use>
                        </svg> <?php echo Labels::getLabel('LBL_PICKUP_IN_STORE', $siteLangId);?> </label>

                </li>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="cart-blocks">            
            <?php if (count($products)) { ?>
            <ul class="list-group list-cart">
                <?php foreach ($products as $product) { 
                    $productUrl = UrlHelper::generateUrl('Products', 'View', array($product['selprod_id']));
                    $shopUrl = UrlHelper::generateUrl('Shops', 'View', array($product['shop_id']));
                    $imageUrl = UrlHelper::getCachedUrl(UrlHelper::generateFileUrl('image', 'product', array($product['product_id'], "THUMB",$product['selprod_id'], 0, $siteLangId)), CONF_IMG_CACHE_TIME, '.jpg');               
                    $productTitle =  ($product['selprod_title']) ? $product['selprod_title'] : $product['product_name'];
                ?>
                <li class="list-group-item <?php echo md5($product['key']); ?> <?php echo (!$product['in_stock']) ? 'disabled' : ''; ?>">
                    <div class="product-profile">
                        <div class="product-profile__thumbnail">
                            <a href="<?php echo $productUrl; ?>">
                                <img class="img-fluid" data-ratio="3:4" src="<?php echo $imageUrl; ?>" alt="<?php echo $product['product_name']; ?>" title="<?php echo $product['product_name']; ?>">
                            </a></div>
                        <div class="product-profile__data">
                            <div class="title"><a class="" href="<?php echo $productUrl; ?>"><?php echo $productTitle;?></a></div>
                            <div class="options">
                                <p class=""> <?php 
                                if (isset($product['options']) && count($product['options'])) {
                                    foreach ($product['options'] as $key => $option) {
                                        if (0 < $key){
                                            echo ' | ';
                                        }
                                        echo $option['option_name'].':'; ?> <span class="text--dark"><?php echo $option['optionvalue_name']; ?></span>
                                        <?php }
                                } ?></p>
                            </div>
                            <p class="save-later">
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
                                / <a href="javascript:void(0)" class="" onClick="moveToSaveForLater( '<?php echo md5($product['key']); ?>',<?php echo $product['selprod_id']; ?> );" title="<?php echo Labels::getLabel('LBL_Move_to_wishlist', $siteLangId); ?>"><?php echo Labels::getLabel('LBL_Save_For_later', $siteLangId); ?></a>                                                                                           
                            </p>
                        </div>
                    </div>
                    <div class="product-quantity">
                        <div class="quantity" data-stock="<?php echo $product['selprod_stock']; ?>">
                            <span class="decrease decrease-js <?php echo ($product['quantity']<=$product['selprod_min_order_qty']) ? 'not-allowed' : '' ;?>"><i class="fas fa-minus"></i></span>
                            <div class="qty-input-wrapper" data-stock="<?php echo $product['selprod_stock']; ?>">
                                <input name="qty_<?php echo md5($product['key']); ?>" data-key="<?php echo md5($product['key']); ?>" class="qty-input cartQtyTextBox productQty-js" value="<?php echo $product['quantity']; ?>" type="text" />
                            </div>
                            <span class="increase increase-js <?php echo ($product['selprod_stock'] <= $product['quantity']) ? 'not-allowed' : '';?>"><i class="fas fa-plus"></i></span>
                        </div>                       
                    </div>

                    <div class="product-price"><?php echo CommonHelper::displayMoneyFormat($product['theprice']); ?></div>
                    <div class="product-action">
                        <ul class="list-actions">
                            <li>
                                <a href="javascript:void(0)" onclick="cart.remove('<?php echo md5($product['key']); ?>','cart')"><svg class="svg" width="24px" height="24px" title="<?php echo Labels::getLabel('LBL_Remove', $siteLangId); ?>">
                                        <use xlink:href="../images/retina/sprite.svg#remove" href="../images/retina/sprite.svg#remove">
                                        </use>
                                    </svg>
                                </a></li>
                        </ul>
                    </div>
                </li>
                <?php }?>

            </ul>
            <?php } ?> 
            <?php if(0 < count($saveForLaterProducts)) { ?>
            <h5 class="cart-title"><?php echo Labels::getLabel('LBL_Save_For_later', $siteLangId); ?> (<?php echo count($saveForLaterProducts); ?>)</h5>                
            <ul class="list-group list-cart">
                <?php foreach ($saveForLaterProducts as $product) {
                    $productUrl = UrlHelper::generateUrl('Products', 'View', array($product['selprod_id']));
                    $imageUrl = UrlHelper::getCachedUrl(UrlHelper::generateFileUrl('image', 'product', array($product['product_id'], "THUMB",$product['selprod_id'], 0, $siteLangId)), CONF_IMG_CACHE_TIME, '.jpg');               
                    $productTitle =  ($product['selprod_title']) ? $product['selprod_title'] : $product['product_name'];
                ?>
                <li class="list-group-item <?php echo md5($product['key']); ?> <?php echo (!$product['in_stock']) ? 'disabled' : ''; ?>">
                    <div class="product-profile">
                        <div class="product-profile__thumbnail">
                            <a href="<?php echo $productUrl; ?>">
                                <img class="img-fluid" data-ratio="3:4" src="<?php echo $imageUrl; ?>" alt="<?php echo $product['product_name']; ?>" title="<?php echo $product['product_name']; ?>">
                            </a></div>
                        <div class="product-profile__data">
                            <div class="title"><a class="" href="<?php echo $productUrl; ?>"><?php echo $productTitle;?></a></div>
                            <div class="options">
                                <p class=""> <?php 
                                if (isset($product['options']) && count($product['options'])) {
                                    foreach ($product['options'] as $key => $option) {
                                        if (0 < $key){
                                            echo ' | ';
                                        }
                                        echo $option['option_name'].':'; ?> <span class="text--dark"><?php echo $option['optionvalue_name']; ?></span>
                                        <?php }
                                } ?></p>
                            </div>
                            <button class="btn btn-outline-primary btn-sm product-profile__btn" type="button" onclick="moveToCart(<?php echo $product['selprod_id']; ?>, <?php echo $product['uwlp_uwlist_id']; ?>, event)"><?php echo Labels::getLabel('LBL_Move_To_Bag', $siteLangId);?></button>
                        </div>
                    </div>
                    <div class="product-price"><?php echo CommonHelper::displayMoneyFormat($product['theprice']); ?></div>
                    <div class="product-action">
                        <ul class="list-actions">
                            <li>
                                <a href="javascript:void(0)" onclick="removeFromWishlist(<?php echo $product['selprod_id']; ?>, <?php echo $product['uwlp_uwlist_id']; ?>, event)"><svg class="svg" width="24px" height="24px" title="<?php echo Labels::getLabel('LBL_Remove', $siteLangId); ?>">
                                        <use xlink:href="../images/retina/sprite.svg#remove" href="../images/retina/sprite.svg#remove">
                                        </use>
                                    </svg>
                                </a></li>
                        </ul>
                    </div>
                </li>
                <?php }?>
            </ul>
            <?php } ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="sticky-summary">
            <div class="card">
                <div class="card__section">
                    <div class="cart-total">                        
                        <?php if (!empty($cartSummary['cartDiscounts']['coupon_code'])) { ?>
                        <div class="coupons-applied">
                            <div class="">
                                <h6><?php echo $cartSummary['cartDiscounts']['coupon_code']; ?></h6>
                                <p><?php echo Labels::getLabel("LBL_Applied", $siteLangId); ?> </p>
                            </div>
                            <button class="btn btn-outline-primary btn-sm" data-toggle="modal" href="#modalcoupons">Edit</button>

                        </div>
                        <?php } else {?>   
                            <div class="coupons">
                            <button class="btn btn-outline-primary btn-block" data-toggle="modal" href="#modalcoupons" onclick="getPromoCode()"> <?php echo Labels::getLabel('LBL_I_have_a_coupon', $siteLangId); ?></button>

                        </div>
                        <?php }?>

                        <ul class="list-group list-group-flush list-group-flush-x">
                            <li class="list-group-item border-0">
                                <span class="label"><?php echo Labels::getLabel('LBL_Total', $siteLangId); ?></span> <span class="ml-auto"><?php echo CommonHelper::displayMoneyFormat($cartSummary['cartTotal']); ?></span>
                            </li>
                            <?php if ($cartSummary['cartVolumeDiscount']) { ?>
                                <li class="list-group-item ">
                                    <span class="label"><?php echo Labels::getLabel('LBL_Volume_Discount', $siteLangId); ?></span> <span class="ml-auto txt-success"><?php echo CommonHelper::displayMoneyFormat($cartSummary['cartVolumeDiscount']); ?></span>
                                </li>
                            <?php }?>
                            
                            <?php if (FatApp::getConfig('CONF_TAX_AFTER_DISOCUNT', FatUtility::VAR_INT, 0) && !empty($cartSummary['cartDiscounts'])) { ?>
                                <li class="list-group-item ">
                                    <span class="label"><?php echo Labels::getLabel('LBL_Discount', $siteLangId); ?></span> <span class="ml-auto"><?php echo CommonHelper::displayMoneyFormat($cartSummary['cartDiscounts']['coupon_discount_total']); ?></span>
                                </li>
                            <?php }?>   
                            <?php $netChargeAmt = $cartSummary['cartTotal'] + $cartSummary['cartTaxTotal'] - ((0 < $cartSummary['cartVolumeDiscount'])?$cartSummary['cartVolumeDiscount']:0);
                            $netChargeAmt = $netChargeAmt - ((isset($cartSummary['cartDiscounts']['coupon_discount_total']) && 0 < $cartSummary['cartDiscounts']['coupon_discount_total'])?$cartSummary['cartDiscounts']['coupon_discount_total']:0);
                            if (isset($cartSummary['taxOptions']) && !empty($cartSummary['taxOptions'])) { 
                                foreach($cartSummary['taxOptions'] as $taxName => $taxVal){
                            ?> 
                             <li class="list-group-item ">
                                <span class="label"><?php echo $taxVal['title']; ?></span> <span class="ml-auto"><?php echo CommonHelper::displayMoneyFormat($taxVal['value']); ?></span>
                            </li>
                            <?php   }
                            }?>

                            <?php if (!FatApp::getConfig('CONF_TAX_AFTER_DISOCUNT', FatUtility::VAR_INT, 0) && !empty($cartSummary['cartDiscounts'])) { ?>
                            <li class="list-group-item ">
                                <span class="label"><?php echo Labels::getLabel('LBL_Discount', $siteLangId); ?></span> <span class="ml-auto"><?php echo CommonHelper::displayMoneyFormat($cartSummary['cartDiscounts']['coupon_discount_total']); ?></span>
                            </li>
                            <?php }?>

                            <li class="list-group-item ">
                                <span class="label"><?php echo Labels::getLabel('LBL_Net_Payable', $siteLangId); ?></span> <span class="ml-auto txt-success"><?php echo CommonHelper::displayMoneyFormat($netChargeAmt); ?></span>
                            </li>


                        </ul>

                        <?php if (CommonHelper::getCurrencyId() != FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1)) { ?>
                       
                        <p class="included"><?php echo CommonHelper::currencyDisclaimer($siteLangId, $cartSummary['orderNetAmount']); ?> </p>
                        
                        <?php } ?>
                        <?php /*?><p class="included">Tax included. Shipping calculated at checkout</p><?php */?>

                        <div class="buttons-group">
                            <a class="btn btn-primary" href="<?php echo UrlHelper::generateUrl(); ?>"><?php echo Labels::getLabel('LBL_Shop_More', $siteLangId); ?></a>
                            <a class="btn btn-outline-primary" href="javascript:void(0)" onclick="goToCheckout()"><?php echo Labels::getLabel('LBL_Checkout', $siteLangId); ?></a>
                        </div>

                    </div>

                </div>
            </div>

            <div class="secure">
                <p><i class="fas fa-lock"></i><?php echo Labels::getLabel('LBL_100%_SECURE_PAYMENT', $siteLangId); ?></p>
                <div class="payment-list justify-content-center">
                    <svg class="payment-list__item" viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" role="img" width="38" height="24" aria-labelledby="pi-visa">
                        <title id="pi-visa">Visa</title>
                        <path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z">
                        </path>
                        <path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32">
                        </path>
                        <path d="M28.3 10.1H28c-.4 1-.7 1.5-1 3h1.9c-.3-1.5-.3-2.2-.6-3zm2.9 5.9h-1.7c-.1 0-.1 0-.2-.1l-.2-.9-.1-.2h-2.4c-.1 0-.2 0-.2.2l-.3.9c0 .1-.1.1-.1.1h-2.1l.2-.5L27 8.7c0-.5.3-.7.8-.7h1.5c.1 0 .2 0 .2.2l1.4 6.5c.1.4.2.7.2 1.1.1.1.1.1.1.2zm-13.4-.3l.4-1.8c.1 0 .2.1.2.1.7.3 1.4.5 2.1.4.2 0 .5-.1.7-.2.5-.2.5-.7.1-1.1-.2-.2-.5-.3-.8-.5-.4-.2-.8-.4-1.1-.7-1.2-1-.8-2.4-.1-3.1.6-.4.9-.8 1.7-.8 1.2 0 2.5 0 3.1.2h.1c-.1.6-.2 1.1-.4 1.7-.5-.2-1-.4-1.5-.4-.3 0-.6 0-.9.1-.2 0-.3.1-.4.2-.2.2-.2.5 0 .7l.5.4c.4.2.8.4 1.1.6.5.3 1 .8 1.1 1.4.2.9-.1 1.7-.9 2.3-.5.4-.7.6-1.4.6-1.4 0-2.5.1-3.4-.2-.1.2-.1.2-.2.1zm-3.5.3c.1-.7.1-.7.2-1 .5-2.2 1-4.5 1.4-6.7.1-.2.1-.3.3-.3H18c-.2 1.2-.4 2.1-.7 3.2-.3 1.5-.6 3-1 4.5 0 .2-.1.2-.3.2M5 8.2c0-.1.2-.2.3-.2h3.4c.5 0 .9.3 1 .8l.9 4.4c0 .1 0 .1.1.2 0-.1.1-.1.1-.1l2.1-5.1c-.1-.1 0-.2.1-.2h2.1c0 .1 0 .1-.1.2l-3.1 7.3c-.1.2-.1.3-.2.4-.1.1-.3 0-.5 0H9.7c-.1 0-.2 0-.2-.2L7.9 9.5c-.2-.2-.5-.5-.9-.6-.6-.3-1.7-.5-1.9-.5L5 8.2z" fill="#142688"></path>
                    </svg>

                    <svg class="payment-list__item" viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" role="img" width="38" height="24" aria-labelledby="pi-master">
                        <title id="pi-master"><?php echo Labels::getLabel('LBL_MASTERCARD', $siteLangId); ?></title>
                        <path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z">
                        </path>
                        <path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32">
                        </path>
                        <circle fill="#EB001B" cx="15" cy="12" r="7"></circle>
                        <circle fill="#F79E1B" cx="23" cy="12" r="7"></circle>
                        <path fill="#FF5F00" d="M22 12c0-2.4-1.2-4.5-3-5.7-1.8 1.3-3 3.4-3 5.7s1.2 4.5 3 5.7c1.8-1.2 3-3.3 3-5.7z">
                        </path>
                    </svg>

                    <svg class="payment-list__item" xmlns="http://www.w3.org/2000/svg" role="img" viewBox="0 0 38 24" width="38" height="24" aria-labelledby="pi-american_express">
                        <title id="pi-american_express"><?php echo Labels::getLabel('LBL_AMERICAN_EXPRESS', $siteLangId); ?></title>
                        <g fill="none">
                            <path fill="#000" d="M35,0 L3,0 C1.3,0 0,1.3 0,3 L0,21 C0,22.7 1.4,24 3,24 L35,24 C36.7,24 38,22.7 38,21 L38,3 C38,1.3 36.6,0 35,0 Z" opacity=".07"></path>
                            <path fill="#006FCF" d="M35,1 C36.1,1 37,1.9 37,3 L37,21 C37,22.1 36.1,23 35,23 L3,23 C1.9,23 1,22.1 1,21 L1,3 C1,1.9 1.9,1 3,1 L35,1">
                            </path>
                            <path fill="#FFF" d="M8.971,10.268 L9.745,12.144 L8.203,12.144 L8.971,10.268 Z M25.046,10.346 L22.069,10.346 L22.069,11.173 L24.998,11.173 L24.998,12.412 L22.075,12.412 L22.075,13.334 L25.052,13.334 L25.052,14.073 L27.129,11.828 L25.052,9.488 L25.046,10.346 L25.046,10.346 Z M10.983,8.006 L14.978,8.006 L15.865,9.941 L16.687,8 L27.057,8 L28.135,9.19 L29.25,8 L34.013,8 L30.494,11.852 L33.977,15.68 L29.143,15.68 L28.065,14.49 L26.94,15.68 L10.03,15.68 L9.536,14.49 L8.406,14.49 L7.911,15.68 L4,15.68 L7.286,8 L10.716,8 L10.983,8.006 Z M19.646,9.084 L17.407,9.084 L15.907,12.62 L14.282,9.084 L12.06,9.084 L12.06,13.894 L10,9.084 L8.007,9.084 L5.625,14.596 L7.18,14.596 L7.674,13.406 L10.27,13.406 L10.764,14.596 L13.484,14.596 L13.484,10.661 L15.235,14.602 L16.425,14.602 L18.165,10.673 L18.165,14.603 L19.623,14.603 L19.647,9.083 L19.646,9.084 Z M28.986,11.852 L31.517,9.084 L29.695,9.084 L28.094,10.81 L26.546,9.084 L20.652,9.084 L20.652,14.602 L26.462,14.602 L28.076,12.864 L29.624,14.602 L31.499,14.602 L28.987,11.852 L28.986,11.852 Z">
                            </path>
                        </g>
                    </svg>


                    <svg class="payment-list__item" viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" width="38" height="24" role="img" aria-labelledby="pi-paypal">
                        <title id="pi-paypal"><?php echo Labels::getLabel('LBL_PAYPAL', $siteLangId); ?></title>
                        <path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z">
                        </path>
                        <path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32">
                        </path>
                        <path fill="#003087" d="M23.9 8.3c.2-1 0-1.7-.6-2.3-.6-.7-1.7-1-3.1-1h-4.1c-.3 0-.5.2-.6.5L14 15.6c0 .2.1.4.3.4H17l.4-3.4 1.8-2.2 4.7-2.1z">
                        </path>
                        <path fill="#3086C8" d="M23.9 8.3l-.2.2c-.5 2.8-2.2 3.8-4.6 3.8H18c-.3 0-.5.2-.6.5l-.6 3.9-.2 1c0 .2.1.4.3.4H19c.3 0 .5-.2.5-.4v-.1l.4-2.4v-.1c0-.2.3-.4.5-.4h.3c2.1 0 3.7-.8 4.1-3.2.2-1 .1-1.8-.4-2.4-.1-.5-.3-.7-.5-.8z">
                        </path>
                        <path fill="#012169" d="M23.3 8.1c-.1-.1-.2-.1-.3-.1-.1 0-.2 0-.3-.1-.3-.1-.7-.1-1.1-.1h-3c-.1 0-.2 0-.2.1-.2.1-.3.2-.3.4l-.7 4.4v.1c0-.3.3-.5.6-.5h1.3c2.5 0 4.1-1 4.6-3.8v-.2c-.1-.1-.3-.2-.5-.2h-.1z">
                        </path>
                    </svg>

                    <svg class="payment-list__item" viewBox="0 0 38 24" xmlns="http://www.w3.org/2000/svg" role="img" width="38" height="24" aria-labelledby="pi-diners_club">
                        <title id="pi-diners_club"><?php echo Labels::getLabel('LBL_DINERS_CLUB', $siteLangId); ?></title>
                        <path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z">
                        </path>
                        <path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32">
                        </path>
                        <path d="M12 12v3.7c0 .3-.2.3-.5.2-1.9-.8-3-3.3-2.3-5.4.4-1.1 1.2-2 2.3-2.4.4-.2.5-.1.5.2V12zm2 0V8.3c0-.3 0-.3.3-.2 2.1.8 3.2 3.3 2.4 5.4-.4 1.1-1.2 2-2.3 2.4-.4.2-.4.1-.4-.2V12zm7.2-7H13c3.8 0 6.8 3.1 6.8 7s-3 7-6.8 7h8.2c3.8 0 6.8-3.1 6.8-7s-3-7-6.8-7z" fill="#3086C8"></path>
                    </svg>

                    <svg class="payment-list__item" xmlns="http://www.w3.org/2000/svg" role="img" viewBox="0 0 38 24" width="38" height="24" aria-labelledby="pi-discover">
                        <title id="pi-discover"><?php echo Labels::getLabel('LBL_DISCOVER', $siteLangId); ?></title>
                        <path d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z" fill="#000" opacity=".07"></path>
                        <path d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32" fill="#FFF"></path>
                        <path d="M37 16.95V21c0 1.1-.9 2-2 2H23.228c7.896-1.815 12.043-4.601 13.772-6.05z" fill="#EDA024"></path>
                        <path fill="#494949" d="M9 11h20v2H9z"></path>
                        <path d="M22 12c0 1.7-1.3 3-3 3s-3-1.4-3-3 1.4-3 3-3c1.7 0 3 1.3 3 3z" fill="#EDA024"></path>
                    </svg>
                </div>
            </div>
        </div>

    </div>
</div>


