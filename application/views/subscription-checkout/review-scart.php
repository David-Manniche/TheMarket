<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$cartTotal = isset($scartSummary['cartTotal']) ? $scartSummary['cartTotal'] : 0;
$cartAdjustableAmount = isset($scartSummary['cartAdjustableAmount']) ? $scartSummary['cartAdjustableAmount'] : 0;
$discountTotal = isset($scartSummary['cartDiscounts']) && isset($scartSummary['cartDiscounts']['coupon_discount_total']) ? $scartSummary['cartDiscounts']['coupon_discount_total'] : 0;
?>
<div class="main">
    <main class="main__content">
        <div class="step active">
            <div class="step__section">
                <div class="step__section__head"><?php echo Labels::getLabel('LBL_Review_Order', $siteLangId); ?></div>
                <?php if (count($subscriptions)) { ?>
    <ul class="list-group list-cart list-cart-page list-shippings">
        <?php foreach ($subscriptions as $subscription) { ?>
        <li class="list-group-item">
            <div class="product-profile">
                <div class="product-profile__data">
                    <div class="title">
                        <?php
                    $spackageName = isset($subscription['spackage_name']) ? $subscription['spackage_name'] : '';
                    $spackagePrice = isset($subscription[SellerPackagePlans::DB_TBL_PREFIX . 'price']) ? $subscription[SellerPackagePlans::DB_TBL_PREFIX . 'price'] : '';
                    $interval = isset($subscription[SellerPackagePlans::DB_TBL_PREFIX . 'trial_interval']) ? $subscription[SellerPackagePlans::DB_TBL_PREFIX . 'trial_interval'] : 0;
                    echo  $spackageName; ?>
                    </div>
                    <div class="options">
                        <p class=""> <?php echo SellerPackagePlans::getPlanPeriod($subscription, $spackagePrice);?> </p>
                    </div>

                </div>
            </div>
            <div class="wrap-qty-price">
                <div class="product-price"><?php echo CommonHelper::displayMoneyFormat($spackagePrice); ?> </div>
            </div>

            <div class="product-action">
                <ul class="list-actions">
                    <li>
                        <a href="javascript::void(0)"
                            onclick="subscription.remove('<?php echo md5($subscription['key']); ?>')"
                            title="<?php echo Labels::getLabel('LBL_Remove', $siteLangId); ?>">
                            <svg class="svg" width="24px" height="24px">
                                <use xlink:href="/yokart/images/retina/sprite.svg#remove"
                                    href="/yokart/images/retina/sprite.svg#remove">
                                </use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <?php }?>
    </ul>
    <div class="step__footer">
        <?php 
        $amount = CommonHelper::displayMoneyFormat($cartTotal - $cartAdjustableAmount - $discountTotal, true, false, true, false, true);
        if ($amount > 0) {
                $paymentText = Labels::getLabel('LBL_Proceed_To_Pay', $siteLangId);
            } else {
                $paymentText = Labels::getLabel('LBL_Proceed_To_Confirm', $siteLangId);
            } ?>
        <a href="javascript:void(0)"
            class="btn btn-brand ripplelink block-on-mobile confirmReview"><?php echo $paymentText ;?></a>
        <?php }?>
    </div>
            </div>
        </div>
    </main> 

</div>
<aside class="sidebar" role="complementary">
    <div class="sidebar__content">
        <div id="order-summary" class="order-summary summary-listing-js">
            <h5 class="mb-2">Order Summary - <?php echo count($subscriptions);?> Item(s)</h5>
            <div class="order-summary__sections">
                <div class="order-summary__section order-summary__section--total-lines">
                    <div class="cart-total my-3">
                        <div class="">
                            <ul class="list-group list-group-flush list-group-flush-x">
                                <li class="list-group-item">
                                    <span
                                        class="label"><?php echo Labels::getLabel('LBL_Sub_Total', $siteLangId); ?></span>
                                    <span
                                        class="mleft-auto"><?php echo CommonHelper::displayMoneyFormat($cartTotal, true, false, true, false, true); ?></span>
                                </li>
                                <?php if ($cartAdjustableAmount > 0) { ?>
                                <li class="list-group-item ">
                                    <span
                                        class="label"><?php echo Labels::getLabel('LBL_Adjusted_Amount', $siteLangId); ?></span>
                                    <span
                                        class="mleft-auto"><?php echo CommonHelper::displayMoneyFormat($cartAdjustableAmount, true, false, true, false, true); ?></span>
                                </li>
                                <?php }?>
                                <?php if ($discountTotal > 0) { ?>
                                <li class="list-group-item ">
                                    <span
                                        class="label"><?php echo Labels::getLabel('LBL_Discount', $siteLangId); ?></span>
                                    <span class="mleft-auto">
                                        <?php echo CommonHelper::displayMoneyFormat($discountTotal, true, false, true, false, true); ?></span>
                                </li>
                                <?php }?>
                                <li class="list-group-item hightlighted">
                                    <span
                                        class="label"><?php echo Labels::getLabel('LBL_You_Pay', $siteLangId); ?></span>
                                    <span class="mleft-auto">
                                        <?php echo $amount ; ?></span>
                                </li>
                            </ul>

                        </div>
                    </div>

                </div>
            </div>

        </div>
        <?php //echo FatUtility::decodeHtmlEntities($pageData['epage_content']);?>
    </div>
</aside>