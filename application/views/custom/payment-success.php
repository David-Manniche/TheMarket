<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<script>
var data = {
    value: < ? php echo $orderInfo['order_net_amount']; ? > ,
    currency : '<?php echo $orderInfo['
    order_currency_code '];?>'
};
events.purchase(data);
</script>
<div id="body" class="body">
    <div class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="message message--success cms">
                        <i class="fa fa-check-circle"></i>
                        <div class="section-head  section--head--center">
                            <div class="section__heading">
                                <h2><?php echo Labels::getLabel('LBL_Congratulations', $siteLangId);?>
                                </h2>
                            </div>
                        </div>


                        <?php if (!CommonHelper::isAppUser()) { ?>
                        <p><?php echo CommonHelper::renderHtml($textMessage); ?>
                        </p>
                        <?php } ?>
                        <span class="gap"></span>
                    </div>
                </div>
            </div>
        </div>

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

                            <!-- Heading -->
                            <h2>Thank You! </h2>
                            <h3>Your order #1560788996 has been placed! </h3>

                            <!-- Text -->
                            <p>
                                We sent an email to <strong>pawan1985chd@gmail.com</strong> with your order confirmation
                                and receipt.
                                If the email hasn't arrived within two minutes, please check your spam folder to see if
                                the
                                email was routed there.
                            </p>
                            <p> <svg class="svg" width="22px" height="22px">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL;?>/images/retina/sprite.svg#TimePlaced"
                                        href="<?php echo CONF_WEBROOT_URL;?>/images/retina/sprite.svg#TimePlaced">
                                    </use>
                                </svg> <strong>Time Placed:</strong> 16/00/2013 13:35 CEST
                                &nbsp;&nbsp;&nbsp;
                                <svg class="svg" width="22px" height="22px">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL;?>/images/retina/sprite.svg#print"
                                        href="<?php echo CONF_WEBROOT_URL;?>/images/retina/sprite.svg#print">
                                    </use>
                                </svg> <a href="#" class="link">Print</a></p>
                        </div>

                        <ul class="completed-detail">
                            <li>
                                <h4> <svg class="svg" width="22px" height="22px">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL;?>/images/retina/sprite.svg#shipping"
                                            href="<?php echo CONF_WEBROOT_URL;?>/images/retina/sprite.svg#shipping">
                                        </use>
                                    </svg> Shipping Address </h4>
                                <p> <strong>John Newman</strong> <br> Ches. str <br>Sacramento, CA 54203 <br> US
                                    <br>.17687654332 </p>
                            </li>



                            <li>
                                <h4> <svg class="svg" width="22px" height="22px">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL;?>/images/retina/sprite.svg#shipping-method"
                                            href="<?php echo CONF_WEBROOT_URL;?>/images/retina/sprite.svg#shipping-method">
                                        </use>
                                    </svg> Shipping Method </h4>
                                <p>Preferred Method: <br> Standard (normally 45 business days, unless Mei-wise noted/
                                </p>
                            </li>
                            <li>
                                <h4> <svg class="svg" width="22px" height="22px">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL;?>/images/retina/sprite.svg#billing-detail"
                                            href="<?php echo CONF_WEBROOT_URL;?>/images/retina/sprite.svg#billing-detail">
                                        </use>
                                    </svg> Billing Details</h4>
                                <p> <strong>John Newman</strong> <br> Ches. str <br>Sacramento, CA 54203 <br> US
                                    <br>.17687654332 </p>
                            </li>
                            <li>
                                <h4> <svg class="svg" width="22px" height="22px">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL;?>/images/retina/sprite.svg#payment-detail"
                                            href="<?php echo CONF_WEBROOT_URL;?>/images/retina/sprite.svg#payment-detail">
                                        </use>
                                    </svg> Payment Details </h4>

                                <p> <strong>Payment type:</strong> MasterCard</p>
                                <p> <strong>Card Number:</strong> **** **** **** 4586 <small> (Partial number displayed
                                        for security purposes)</small> </p>
                            </li>
                        </ul>

                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="completed-cart">
                                    <div class="row justify-content-between">
                                        <div class="col-md-7">
                                            <h5>Order List</h5>
                                            <ul
                                                class="list-group list-group-flush-x list-group-flush-y  list-cart list-cart-checkout">
                                                <li class="list-group-item">
                                                    <div class="product-profile">
                                                        <div class="product-profile__thumbnail">
                                                            <a href="#">
                                                                <img class="img-fluid" data-ratio="3:4"
                                                                    src="<?php echo CONF_WEBROOT_URL;?>/imagesproducts/pro-1.jpg" alt="...">
                                                            </a>
                                                            <span class="product-qty">2</span>
                                                        </div>
                                                        <div class="product-profile__data">
                                                            <div class="title"><a class="" href="product.html">Mini-Max
                                                                    Mini
                                                                    Bean Bag Plush - Big Hero 6: The Series</a></div>
                                                            <div class="options">
                                                                <p class="">Medium | red</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="product-price">$40.00</div>

                                                </li>
                                                <li class="list-group-item">
                                                    <div class="product-profile">
                                                        <div class="product-profile__thumbnail">
                                                            <a href="#">
                                                                <img class="img-fluid" data-ratio="3:4"
                                                                    src="<?php echo CONF_WEBROOT_URL;?>/imagesproducts/pro-1.jpg" alt="...">
                                                            </a>
                                                            <span class="product-qty">2</span>
                                                        </div>
                                                        <div class="product-profile__data">
                                                            <div class="title"><a class="" href="product.html">Mini-Max
                                                                    Mini
                                                                    Bean Bag Plush - Big Hero 6: The Series</a></div>
                                                            <div class="options">
                                                                <p class="">Medium | red</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="product-price">$40.00</div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-4">
                                            <h5>Order Summary</h5>
                                            <div class="cart-total mt-5">
                                                <ul class="list-group list-group-flush-x list-group-flush-y">
                                                    <li class="list-group-item">
                                                        <span class="label">Subtotal</span> <span
                                                            class="ml-auto">$89.00</span>
                                                    </li>
                                                    <li class="list-group-item ">
                                                        <span class="label">Estimated Tax</span> <span
                                                            class="ml-auto">$00.00</span>
                                                    </li>
                                                    <li class="list-group-item hightlighted">
                                                        <span class="label">Total</span> <span
                                                            class="ml-auto">$89.00</span>
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
<script>
/*window.setTimeout(function() {
    window.location.href = fcom.makeUrl('Home');
}, 15000);*/
</script>