<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<script>
    var data = {
        value: <?php echo $orderInfo['order_net_amount']; ?> ,
        currency : '<?php echo $orderInfo['order_currency_code']; ?>'
    };
    events.purchase(data);
</script>
<?php 
    $products = $orderInfo['orderProducts'];
    $shippingMethod = '';
    foreach ($products as $op) {
        $shippingMethod .= '<li>' . $op['opshipping_label'] . '</li>';
    }
?>
<div id="body" class="body">
    <div class="section">
        <div class="order-completed">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-9">
                        <div class="thanks-screen text-center">
                            <!-- Icon -->
                            <div class="success-animation">
                                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"></circle>
                                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path>
                                </svg>
                            </div>
                            <h2><?php echo Labels::getLabel('LBL_THANK_YOU!', $siteLangId); ?></h2>
                            <h3>
                                <?php 
                                    $msg = Labels::getLabel('LBL_YOUR_ORDER_#{ORDER-ID}_HAS_BEEN_PLACED!', $siteLangId);
                                    $msg = CommonHelper::replaceStringData($msg, ['{ORDER-ID}' => $orderInfo['order_id']]);
                                    echo $msg;
                                ?> 
                            </h3>

                            <p>
                                <?php if (!CommonHelper::isAppUser()) { ?>
                                    <p><?php echo CommonHelper::renderHtml($textMessage); ?>
                                    </p>
                                <?php } ?>
                            </p>
                            <p> 
                                <svg class="svg" width="22px" height="22px">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#TimePlaced" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#TimePlaced">
                                    </use>
                                </svg> 
                                <?php 
                                    $replace = [
                                        '{TIME-PLACED}' => '<strong>' . Labels::getLabel('LBL_TIME_PLACED', $siteLangId) . '</strong>',
                                        '{DATE-TIME}' => $orderInfo['order_date_added'],
                                    ];
                                    $msg = Labels::getLabel('LBL_{TIME-PLACED}:_{DATE-TIME}', $siteLangId);
                                    $msg = CommonHelper::replaceStringData($msg, $replace);
                                    echo $msg;
                                ?>
                                &nbsp;&nbsp;&nbsp;
                                <span class="no-print">
                                    
                                    <a class="btn btn-link" href="<?php echo UrlHelper::generateUrl('Custom', 'PaymentSuccess', [$orderInfo['order_id'], 'print']);?>"> <svg class="svg" width="22px" height="22px">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#print" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#print">
                                        </use>
                                    </svg> <?php echo Labels::getLabel("LBL_PRINT", $siteLangId); ?></a>
                                </span>
                            </p>
                        </div>

                        <ul class="completed-detail">
                            <?php if (!empty($orderInfo['shippingAddress'])) { 
                                $shippingAddress = $orderInfo['shippingAddress']; ?>
                                <li>
                                    <h4>
                                        <svg class="svg" width="22px" height="22px">
                                            <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#shipping" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#shipping">
                                            </use>
                                        </svg>
                                        <?php echo Labels::getLabel("LBL_SHIPPING_ADDRESS", $siteLangId); ?>
                                    </h4>
                                    <p>
                                        <strong><?php echo $shippingAddress['oua_name']; ?></strong><br>
                                        <?php 
                                            echo $shippingAddress['oua_address1']; 
                                            if (!empty($shippingAddress['oua_address2'])) {
                                                echo ', ' . $shippingAddress['oua_address2'];
                                            }
                                            echo '<br>' . $shippingAddress['oua_city'] . ', '. $shippingAddress['oua_state_code'] . ' ' . $shippingAddress['oua_zip'];
                                            echo '<br>' . $shippingAddress['oua_country_code'];
                                            echo '<br>' . $shippingAddress['oua_phone'];
                                        ?>
                                    </p>
                                </li>
                            <?php }
                            if (!empty($shippingMethod)) { ?>
                                <li>
                                    <h4> 
                                        <svg class="svg" width="22px" height="22px">
                                            <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#shipping-method" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#shipping-method">
                                            </use>
                                        </svg> Shipping Method </h4>
                                        <p>Preferred Method: <br>
                                        <ol class="preferred-shipping-list">
                                            <?php echo $shippingMethod; ?>
                                        </ol>
                                    </p>
                                </li>
                            <?php } ?>
                            <li>
                                <?php $billingAddress = $orderInfo['billingAddress']; ?>
                                <h4>
                                    <svg class="svg" width="22px" height="22px">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#billing-detail" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#billing-detail">
                                        </use>
                                    </svg>
                                    <?php echo Labels::getLabel("LBL_BILLING_ADDRESS", $siteLangId); ?>
                                </h4>
                                <p>
                                    <strong><?php echo $billingAddress['oua_name']; ?></strong><br>
                                    <?php 
                                        echo $billingAddress['oua_address1']; 
                                        if (!empty($billingAddress['oua_address2'])) {
                                            echo ', ' . $billingAddress['oua_address2'];
                                        }
                                        echo '<br>' . $billingAddress['oua_city'] . ', '. $billingAddress['oua_state_code'] . ' ' . $billingAddress['oua_zip'];
                                        echo '<br>' . $billingAddress['oua_country_code'];
                                        echo '<br>' . $billingAddress['oua_phone'];
                                    ?>
                                </p>
                            </li>
                        </ul>

                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="completed-cart">
                                    <div class="row justify-content-between">
                                        <div class="col-md-7">
                                            <h5><?php Labels::getLabel('LBL_ORDER_DETAIL', $siteLangId); ?></h5>
                                            <ul class="list-group list-group-flush-x list-group-flush-y  list-cart list-cart-checkout">
                                                <?php 
                                                $shippingCharges = $subTotal = 0;
                                                foreach ($products as $key => $product) { 
                                                    $productUrl = UrlHelper::generateUrl('Products', 'View', array($product['op_selprod_id']));
                                                    $shopUrl = UrlHelper::generateUrl('Shops', 'View', array($product['op_shop_id']));
                                                    $imageUrl = UrlHelper::getCachedUrl(UrlHelper::generateFileUrl('image', 'product', array($product['selprod_product_id'], "THUMB",$product['op_selprod_id'], 0, $siteLangId)), CONF_IMG_CACHE_TIME, '.jpg');
                                                    $productTitle =  ($product['op_selprod_title']) ? $product['op_selprod_title'] : $product['op_product_name'];
                                                ?>
                                                    <li class="list-group-item">
                                                        <div class="product-profile">
                                                            <div class="product-profile__thumbnail">
                                                                <a href="<?php echo $productUrl; ?>">
                                                                    <img class="img-fluid" data-ratio="3:4" src="<?php echo $imageUrl; ?>" alt="<?php echo $product['op_product_name']; ?>" title="<?php echo $product['op_product_name']; ?>">
                                                                </a>
                                                                <span class="product-qty"><?php echo $product['op_qty']; ?></span>
                                                            </div>
                                                            <div class="product-profile__data">
                                                                <div class="title"><a class="" href="<?php echo $productUrl; ?>"><?php echo $productTitle;?></a> </div>
                                                                <div class="options">
                                                                <p class=""> <?php echo $product['op_selprod_options']; ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="product-price">
                                                            <?php 
                                                                $subTotal += $txnAmount = ($product["op_unit_price"] * $product["op_qty"]);
                                                                echo CommonHelper::displayMoneyFormat($txnAmount);

                                                                $shippingCharges += $product['op_actual_shipping_charges'];
                                                            ?>
                                                        </div>
                                                    </li>
                                                <?php } ?> 
                                            </ul>
                                        </div>
                                        <div class="col-md-4">
                                            <h5><?php echo Labels::getLabel('LBL_ORDER_SUMMARY', $siteLangId); ?></h5>
                                            <div class="cart-total mt-5">
                                                <ul class="list-group list-group-flush-x list-group-flush-y">
                                                    <li class="list-group-item">
                                                        <span class="label">
                                                            <?php echo Labels::getLabel('LBL_Sub_Total', $siteLangId); ?>
                                                        </span> 
                                                        <span class="ml-auto">
                                                            <?php echo CommonHelper::displayMoneyFormat($subTotal); ?>
                                                        </span>
                                                    </li>
                                                    <?php if (!empty($orderInfo['order_reward_point_value']) || !empty($orderInfo['order_discount_total']) || !empty($orderInfo['order_volume_discount_total'])) { 
                                                            $msg = "LBL_REWARD_POINTS";
                                                            $totalDiscount = $orderInfo['order_reward_point_value'];
                                                            if (!empty($orderInfo['order_discount_total']) && 0 < $orderInfo['order_discount_total']) {
                                                                $msg .= "_&_DISCOUNT";
                                                                $totalDiscount += $orderInfo['order_discount_total'];
                                                            }
                                                            if (!empty($orderInfo['order_volume_discount_total']) && 0 < $orderInfo['order_volume_discount_total']) {
                                                                $totalDiscount += $orderInfo['order_volume_discount_total'];
                                                            }
                                                        ?>
                                                        <li class="list-group-item ">
                                                        <span class="label"><?php echo Labels::getLabel($msg, $siteLangId); ?></span>
                                                        <span class="ml-auto"><?php echo CommonHelper::displayMoneyFormat($totalDiscount); ?></span>
                                                        </li>
                                                    <?php } ?>  
                                                    <?php if ($orderInfo['order_tax_charged']){ ?>
                                                        <li class="list-group-item ">
                                                            <span class="label"><?php echo Labels::getLabel('LBL_TAX', $siteLangId); ?></span>
                                                            <span class="ml-auto"><?php echo CommonHelper::displayMoneyFormat($orderInfo['order_tax_charged']); ?></span>
                                                        </li>
                                                    <?php } ?>    
                                                    <?php if (0 < $shippingCharges) { ?>
                                                        <li class="list-group-item ">
                                                        <span class="label"><?php echo Labels::getLabel('LBL_Delivery_Charges', $siteLangId); ?></span>
                                                        <span class="ml-auto"><?php echo CommonHelper::displayMoneyFormat($shippingCharges); ?></span>
                                                        </li>
                                                    <?php  } ?>
                                                    <li class="list-group-item hightlighted">
                                                        <span class="label"><?php echo Labels::getLabel('LBL_NET_AMOUNT', $siteLangId); ?></span> 
                                                        <span class="ml-auto"><?php echo CommonHelper::displayMoneyFormat($orderInfo['order_net_amount']); ?></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (true === $print) { ?>    
    <script>
        $(document).ready(function(){
            setTimeout(() => {
                window.print();
            }, 1000);
            window.onafterprint = function() {
                location.href = history.back();
            }
        });
    </script>
<?php } ?>    