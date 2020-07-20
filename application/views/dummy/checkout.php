<section class="checkout">
    <header class="header-checkout" data-header="" role="header">
        <a class="logo-checkout-main" href="index.php"><img src="../images/logo.png" alt=""></a>
        <div class="checkout-progress">
            <div class="progress-track"></div>
            <div id="step1" class="progress-step is-complete">
                Step One
            </div>
            <div id="step2" class="progress-step is-active">
                Step Two
            </div>
            <div id="step3" class="progress-step"> 
                Step Three
            </div>
            <div id="step4" class="progress-step">
                Complete
            </div>
        </div>
    </header>
    <aside role="complementary">
        <button class="order-summary-toggle" data-trigger="order-summary">
            <div class="container">
                <span class="order-summary-toggle__inner">
                    <span class="order-summary-toggle__icon-wrapper mr-2">
                        <svg width="20" height="19" xmlns="http://www.w3.org/2000/svg"
                            class="order-summary-toggle__icon">
                            <path
                                d="M17.178 13.088H5.453c-.454 0-.91-.364-.91-.818L3.727 1.818H0V0h4.544c.455 0 .91.364.91.818l.09 1.272h13.45c.274 0 .547.09.73.364.18.182.27.454.18.727l-1.817 9.18c-.09.455-.455.728-.91.728zM6.27 11.27h10.09l1.454-7.362H5.634l.637 7.362zm.092 7.715c1.004 0 1.818-.813 1.818-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817zm9.18 0c1.004 0 1.817-.813 1.817-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817z">
                            </path>
                        </svg>
                    </span>
                    <span class="order-summary-toggle__text">
                        <span>Order Summary <i class="arrow-right">
                                <svg class="svg">
                                    <use xlink:href="../images/retina/sprite.svg#arrow-right"
                                        href="../images/retina/sprite.svg#arrow-right"></use>
                                </svg>

                            </i></span>
                    </span>
                    <span class="order-summary-toggle__total-recap total-recap">
                        <span class="total-recap__final-price">$226.15</span>
                    </span>
                </span>
            </div>
        </button>
    </aside>
    <div class="content" data-content="">
        <div class="container">
            <div class="main">

                <main class="main__content">

                    <!-- begin::Step -->
                    <div class="step active" role="step:1">
                        <div class="step__section">
                            <p class="pb-5">Already have an account ? <a class="link" data-toggle="collapse"
                                    href="#login-quick">Click here </a> <i class="fa fa-question-circle"
                                    data-container="body" data-toggle="popover" data-placement="top"
                                    data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus."></i>
                            </p>

                            <div class="collapse" id="login-quick">
                                <div class="row justify-content-between">
                                    <div class="col-md-6">
                                        <form id="login-form" method="post" class="form    form-floating">
                                            <p class="my-2">Returning Customer <i class="fa fa-question-circle"
                                                    data-container="body" data-toggle="popover" data-placement="top"
                                                    data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus."></i>
                                            </p>

                                            <div class="form-group form-floating__group">
                                                <input type="email"
                                                    class="form-control form-floating__field form-floating__field"
                                                    id="email">
                                                <label class="form-floating__label">Email Address</label>
                                            </div>
                                            <div class="form-group form-floating__group">
                                                <input type="password"
                                                    class="form-control form-floating__field form-floating__field"
                                                    id="password">
                                                <label class="form-floating__label">Password</label>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <a href="#"><span class="small">Forgot password?</span></a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <button type="submit"
                                                            class="btn btn-primary btn-block btn-submit">Log in <i
                                                                class="arrow la la-long-arrow-right"></i></button></div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="v-divider"></div>
                                    </div>
                                    <div class="col-md-5">
                                        <p class="my-2">Guest Checkout</p>

                                        <form id="login-form" method="post" class="form form-login ">
                                            <button type="submit" class="btn btn-primary btn-block btn-submit">Log in <i
                                                    class="arrow la la-long-arrow-right"></i></button>
                                            <div class="divider">
                                                <span>Or</span>
                                            </div>
                                            <div class="button-wrap">

                                                <button type="button" class="btn btn-social btn-facebook">
                                                    <i class="icn">
                                                        <svg class="svg">
                                                            <use xlink:href="../images/retina/sprite.svg#facebook"
                                                                href="../images/retina/sprite.svg#facebook">
                                                            </use>
                                                        </svg></i></button>

                                                <button type="button" class="btn btn-social btn-twitter">
                                                    <i class="icn">
                                                        <svg class="svg">
                                                            <use xlink:href="../images/retina/sprite.svg#twitter"
                                                                href="../images/retina/sprite.svg#twitter">
                                                            </use>
                                                        </svg></i></button>


                                                <button type="button" class="btn btn-social btn-google">
                                                    <i class="icn">
                                                        <svg class="svg">
                                                            <use xlink:href="../images/retina/sprite.svg#google"
                                                                href="../images/retina/sprite.svg#google">
                                                            </use>
                                                        </svg></i></button>


                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end::Step -->
                    <!-- begin::Step -->
                    <div class="step active" role="step:2">

                        <form class="form form form-floating">
                            <div class="step__section">
                                <div class="step__head">
                                    <h5 class="step-title">Delivery detail</h5>
                                </div>
                                <ul class="list-group list-addresses list-addresses-view">
                                    <li class="list-group-item">
                                        <div class="tags">
                                            <div class="tags__inner">
                                                <span class="tag address_lable">Home</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-auto"><label class="checkbox"><input title=""
                                                        type="checkbox" value="1"><i class="input-helper"></i>
                                                </label></div>

                                            <div class="col">
                                                <address class="">Plot 268, First Floor, <br>Sector 82, JLPL
                                                    Industrial Area, Punjab 140308</address>
                                            </div>

                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="tags">
                                            <div class="tags__inner">
                                                <span class="tag address_lable">Office</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-auto"><label class="checkbox"><input title=""
                                                        type="checkbox" value="1" checked=""><i
                                                        class="input-helper"></i>
                                                </label></div>

                                            <div class="col">
                                                <address class="">Plot 268, First Floor, <br>Sector 82, JLPL
                                                    Industrial Area, Punjab 140308</address>
                                                <ul class="list-actions">
                                                    <li>
                                                        <a href="#"><svg class="svg">
                                                                <use xlink:href="../images/retina/sprite.svg#edit"
                                                                    href="../images/retina/sprite.svg#edit">
                                                                </use>
                                                            </svg>
                                                        </a></li>
                                                    <li>
                                                        <a href="#"><svg class="svg">
                                                                <use xlink:href="../images/retina/sprite.svg#remove"
                                                                    href="../images/retina/sprite.svg#remove">
                                                                </use>
                                                            </svg>
                                                        </a></li>
                                                </ul>
                                            </div>

                                        </div>
                                    </li>
                                </ul>
                                <div class="my-3 text-right">
                                    <a class="link-text" href="">
                                        <i class="icn"> <svg class="svg">
                                                <use xlink:href="../images/retina/sprite.svg#add"
                                                    href="../images/retina/sprite.svg#add">
                                                </use>
                                            </svg> </i> Add a new address</a>

                                </div>



                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-floating__group">
                                            <input type="email" class="form-control form-floating__field">
                                            <label class="form-floating__label">Email</label>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-floating__group">
                                            <input type="text" class="form-control form-floating__field" placeholder="">
                                            <label class="form-floating__label">First name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-floating__group">
                                            <input type="text" class="form-control form-floating__field" placeholder="">
                                            <label class="form-floating__label">Last name</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-floating__group">
                                            <input type="text" class="form-control form-floating__field" placeholder="">
                                            <label class="form-floating__label">Address</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group form-floating__group">
                                            <input type="text" class="form-control form-floating__field" placeholder="">
                                            <label class="form-floating__label">Apartment, suite, etc.l</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-floating__group">
                                            <input type="text" class="form-control form-floating__field" placeholder="">
                                            <label class="form-floating__label">City</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-floating__group">
                                            <input type="text" class="form-control form-floating__field" placeholder="">
                                            <label class="form-floating__label">Phone</label>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-floating__group">
                                            <select class="form-control form-floating__field" autocomplete="">
                                                <option data-code="IL" value="Israel">Israel</option>
                                                <option disabled="disabled" value="---">---</option>
                                                <option data-code="AF" value="Afghanistan">Afghanistan</option>
                                                <option data-code="AX" value="Aland Islands">Åland Islands</option>

                                            </select>
                                            <label class="form-floating__label">shipping country</label>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-floating__group">
                                            <select class="form-control form-floating__field" placeholder="">
                                                <option disabled="">State</option>
                                                <option data-alternate-values="[&quot;Andaman and Nicobar&quot;]"
                                                    value="AN">Andaman and Nicobar</option>

                                            </select>
                                            <label class="form-floating__label">State</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-floating__group">
                                            <input type="text" class="form-control form-floating__field" placeholder="">
                                            <label class="form-floating__label">PIN code</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="step__footer">
                                <a class="btn btn-link" href="#">
                                    <i class="arrow">
                                        <svg class="svg">
                                            <use xlink:href="../images/retina/sprite.svg#arrow-left"
                                                href="../images/retina/sprite.svg#arrow-left">
                                            </use>
                                        </svg></i>
                                    <span class="">Back </span></a>

                                <button name="button" type="button" class="btn btn-primary btn-wide">Continue</button>

                            </div>
                        </form>
                    </div>
                    <!-- end::Step -->


                    <!-- begin::Step -->
                    <div class="step active" role="step:3">
                        <div class="step__section">
                            <div class="step__head">
                                <h5 class="step-title">Shipping</h5>
                            </div>
                            <div class="shipping-section">
                                <div class="shipping-option">
                                    <ul class="media-more media-more-sm show">
                                        <li><span class="circle" data-toggle="tooltip" data-placement="top" title=""
                                                data-original-title="product name"><img
                                                    src="../images//products/product-thumb.jpg" alt=""></span></li>
                                        <li><span class="circle" data-toggle="tooltip" data-placement="top" title=""
                                                data-original-title="product name"><img
                                                    src="../images//products/product-thumb.jpg" alt=""></span></li>
                                        <li><span class="circle" data-toggle="tooltip" data-placement="top" title=""
                                                data-original-title="product name"><img
                                                    src="../images//products/product-thumb.jpg" alt=""></span></li>
                                        <li><span class="circle" data-toggle="tooltip" data-placement="top" title=""
                                                data-original-title="product name"><img
                                                    src="../images//products/product-thumb.jpg" alt=""></span></li>

                                        <li> <span class="circle plus-more">+5</span></li>
                                    </ul>

                                    <select class=" form-control custom-select YK-selectedShipping" name="" id="">
                                        <option value="">Option</option>
                                        <option value="">Option</option>
                                        <option value="">Option</option>
                                        <option value="">Option</option>
                                        <option value="">Option</option>
                                    </select>
                                </div>
                            </div>
                            <div class="shipping-section">
                                <div class="shipping-option">
                                    <ul class="media-more media-more-sm show">
                                        <li><span class="circle" data-toggle="tooltip" data-placement="top" title=""
                                                data-original-title="product name"><img
                                                    src="../images//products/product-thumb.jpg" alt=""></span></li>

                                    </ul>
                                    <select class=" form-control custom-select YK-selectedShipping" name="" id="">
                                        <option value="">Option</option>
                                        <option value="">Option</option>
                                        <option value="">Option</option>
                                        <option value="">Option</option>
                                        <option value="">Option</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end::Step -->
                    <div class="step active" role="step:3">
                        <div class="step__section">
                            <div class="step__head">
                                <h5 class="step-title">Pick Up</h5>
                            </div>
                            <div class="pick-section">
                                <div class="pickup-option">
                                    <ul class="pickup-option__list">
                                        <li class=""><label class="radio"><input title="" type="radio" value="1"> <i
                                                    class="input-helper"></i> <span class="lb-txt"> Block 2012, sector
                                                    32/c, Homeland apartments, 160030 Chandigarh UT
                                                    India</span></label></li>
                                        <li class=""><label class="radio"><input title="" type="radio" value="1"> <i
                                                    class="input-helper"></i> <span class="lb-txt"> Block 2012, sector
                                                    32/c, Homeland apartments, 160030 Chandigarh UT
                                                    India</span></label></li>
                                        <li class=""><label class="radio"><input title="" type="radio" value="1"> <i
                                                    class="input-helper"></i> <span class="lb-txt"> Block 2012, sector
                                                    32/c, Homeland apartments, 160030 Chandigarh UT
                                                    India</span></label></li>
                                        <li class=""><label class="radio"><input title="" type="radio" value="1"> <i
                                                    class="input-helper"></i> <span class="lb-txt"> Block 2012, sector
                                                    32/c, Homeland apartments, 160030 Chandigarh UT
                                                    India</span></label></li>
                                        <li class=""><label class="radio"><input title="" type="radio" value="1"> <i
                                                    class="input-helper"></i> <span class="lb-txt"> Block 2012, sector
                                                    32/c, Homeland apartments, 160030 Chandigarh UT
                                                    India</span></label></li>
                                    </ul>

                                    <div class="pickup-time">
                                        <div class="calendar"><img src="../images/calendar.jpg" alt=""></div>
                                        <ul class="time-slot">
                                            <li class=""> <input type="checkbox" class="control-input" name=""
                                                    id="time-1" value=""><label class="control-label" for="time-1"><span
                                                        class="time">09:00 - 10:00 </span></label></li>

                                            <li class=""> <input type="checkbox" class="control-input" name=""
                                                    id="time-2" value=""><label class="control-label" for="time-2"><span
                                                        class="time">09:00 - 10:00 </span></label></li>
                                            <li class=""> <input type="checkbox" class="control-input" name=""
                                                    id="time-3" value=""><label class="control-label" for="time-3"><span
                                                        class="time">09:00 - 10:00 </span></label></li>
                                            <li class=""> <input type="checkbox" class="control-input" name=""
                                                    id="time-4" value=""><label class="control-label" for="time-4"><span
                                                        class="time">09:00 - 10:00 </span></label></li>
                                            <li class=""> <input type="checkbox" class="control-input" name=""
                                                    id="time-5" value=""><label class="control-label" for="time-5"><span
                                                        class="time">09:00 - 10:00 </span></label></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <ul class="list-group review-block">
                        <li class="list-group-item">
                            <div class="review-block__label">Contact Info</div>
                            <div class="review-block__content" role="cell">Block 2012, sector 32/c, Homeland
                                apartments, 160030 Chandigarh UT India</div>
                            <div class="review-block__link" role="cell">
                                <a class="link" href="#"><span>Change</span></a>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="review-block__label">Shipping</div>
                            <div class="review-block__content" role="cell">
                                <ul class="media-more media-more-sm show">
                                    <li><span class="circle" data-toggle="tooltip" data-placement="top" title=""
                                            data-original-title="product name"><img
                                                src="../images//products/product-thumb.jpg" alt=""></span></li>
                                    <li><span class="circle" data-toggle="tooltip" data-placement="top" title=""
                                            data-original-title="product name"><img
                                                src="../images//products/product-thumb.jpg" alt=""></span></li>
                                    <li><span class="circle" data-toggle="tooltip" data-placement="top" title=""
                                            data-original-title="product name"><img
                                                src="../images//products/product-thumb.jpg" alt=""></span></li>
                                    <li><span class="circle" data-toggle="tooltip" data-placement="top" title=""
                                            data-original-title="product name"><img
                                                src="../images//products/product-thumb.jpg" alt=""></span></li>

                                    <li> <span class="circle plus-more">+5 more</span></li>
                                </ul>
                            </div>
                            <div class="review-block__link" role="cell">
                                <a class="link" href="#"><span>Change</span></a>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="review-block__label">Pick up address</div>
                            <div class="review-block__content" role="cell">Golden apartments, 160030 Chandigarh UT India
                                <span class="selected-slot">Tuesday 9th july 2020 <br>
                                    09:00-10:00</span>
                            </div>
                            <div class="review-block__link" role="cell">
                                <a class="link" href="#"><span>Change</span></a>
                            </div>
                        </li>
                    </ul>
                    <!-- begin::Step -->
                    <div class="step active" role="step:4">
                        <div class="step__section">
                            <div class="step__head">
                                <h5 class="step-title">Payment & Billing </h5>
                            </div>
                            <label class="checkbox"><input title="" type="checkbox" value="1">My billing address is the
                                same as my delivery address <i class="input-helper"></i>
                            </label>

                            <div class="rewards">
                                <div class="rewards__points">
                                    <ul>
                                        <li>
                                            <p>Available rewards points</p>
                                            <span class="count">50</span>
                                        </li>
                                        <li>
                                            <p>Points worth</p>
                                            <span class="count">$150</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="info">
                                    <span> <svg class="svg">
                                            <use xlink:href="../images/retina/sprite.svg#info"
                                                href="../images/retina/sprite.svg#info">
                                            </use>
                                        </svg> Minimum 100 reward points redeem at a time</span></div>

                                <form class="form form-floating">
                                    <div class="row form-row">
                                        <div class="col">
                                            <div class="form-group form-floating__group">
                                                <input class="form-control form-floating__field" id="" type="text"
                                                    placeholder="">
                                                <label class="form-floating__label">Enter points to redeem</label>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <!-- Button -->
                                            <button class="btn btn-primary btn-wide" type="submit">
                                                Redeem
                                            </button>

                                        </div>
                                    </div>
                                </form>
                            </div>



                            <div class="payment-area">
                                <ul class="nav nav-payments" id="" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="credit-tab" data-toggle="tab" href="#credit"
                                            role="tab" aria-controls="credit" aria-selected="true">Credit Card</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="paypal-tab" data-toggle="tab" href="#paypal" role="tab"
                                            aria-controls="paypal" aria-selected="false">Paypal</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="paycash-tab" data-toggle="tab" href="#paycash"
                                            role="tab" aria-controls="paycash" aria-selected="false">Paycash</a>
                                    </li>
                                </ul>



                                <div class="tab-content" id="">
                                    <div class="tab-pane fade show active" id="credit" role="tabpanel"
                                        aria-labelledby="credit-tab">

                                        <ul class="list-group payment-card payment-card-view">
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-auto"><label class="checkbox"><input title=""
                                                                type="checkbox" value="1" checked><i
                                                                class="input-helper"></i>
                                                        </label></div>
                                                    <div class="col">
                                                        <div class="payment-card__photo">
                                                            <svg class="svg payment-list__item" viewBox="0 0 38 24"
                                                                xmlns="http://www.w3.org/2000/svg" role="img" width="38"
                                                                height="24" aria-labelledby="pi-visa">
                                                                <title id="pi-visa">Visa</title>
                                                                <path opacity=".07"
                                                                    d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z">
                                                                </path>
                                                                <path fill="#fff"
                                                                    d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32">
                                                                </path>
                                                                <path
                                                                    d="M28.3 10.1H28c-.4 1-.7 1.5-1 3h1.9c-.3-1.5-.3-2.2-.6-3zm2.9 5.9h-1.7c-.1 0-.1 0-.2-.1l-.2-.9-.1-.2h-2.4c-.1 0-.2 0-.2.2l-.3.9c0 .1-.1.1-.1.1h-2.1l.2-.5L27 8.7c0-.5.3-.7.8-.7h1.5c.1 0 .2 0 .2.2l1.4 6.5c.1.4.2.7.2 1.1.1.1.1.1.1.2zm-13.4-.3l.4-1.8c.1 0 .2.1.2.1.7.3 1.4.5 2.1.4.2 0 .5-.1.7-.2.5-.2.5-.7.1-1.1-.2-.2-.5-.3-.8-.5-.4-.2-.8-.4-1.1-.7-1.2-1-.8-2.4-.1-3.1.6-.4.9-.8 1.7-.8 1.2 0 2.5 0 3.1.2h.1c-.1.6-.2 1.1-.4 1.7-.5-.2-1-.4-1.5-.4-.3 0-.6 0-.9.1-.2 0-.3.1-.4.2-.2.2-.2.5 0 .7l.5.4c.4.2.8.4 1.1.6.5.3 1 .8 1.1 1.4.2.9-.1 1.7-.9 2.3-.5.4-.7.6-1.4.6-1.4 0-2.5.1-3.4-.2-.1.2-.1.2-.2.1zm-3.5.3c.1-.7.1-.7.2-1 .5-2.2 1-4.5 1.4-6.7.1-.2.1-.3.3-.3H18c-.2 1.2-.4 2.1-.7 3.2-.3 1.5-.6 3-1 4.5 0 .2-.1.2-.3.2M5 8.2c0-.1.2-.2.3-.2h3.4c.5 0 .9.3 1 .8l.9 4.4c0 .1 0 .1.1.2 0-.1.1-.1.1-.1l2.1-5.1c-.1-.1 0-.2.1-.2h2.1c0 .1 0 .1-.1.2l-3.1 7.3c-.1.2-.1.3-.2.4-.1.1-.3 0-.5 0H9.7c-.1 0-.2 0-.2-.2L7.9 9.5c-.2-.2-.5-.5-.9-.6-.6-.3-1.7-.5-1.9-.5L5 8.2z"
                                                                    fill="#142688"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="payment-card__number">Ending in
                                                            <strong>4506</strong></div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="payment-card__name">Pawan kumar</div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="payment-card__expiry">Expiry
                                                            <strong>02/2023</strong></div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="payment-card__actions">
                                                            <ul class="list-actions">
                                                                <li>
                                                                    <a href="#"><svg class="svg" width="24px"
                                                                            height="24px">
                                                                            <use xlink:href="../images/retina/sprite.svg#remove"
                                                                                href="../images/retina/sprite.svg#remove">
                                                                            </use>
                                                                        </svg>
                                                                    </a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-auto"><label class="checkbox"><input title=""
                                                                type="checkbox" value="1"><i class="input-helper"></i>
                                                        </label></div>
                                                    <div class="col">
                                                        <div class="payment-card__photo">
                                                            <svg class="svg payment-list__item" viewBox="0 0 38 24"
                                                                xmlns="http://www.w3.org/2000/svg" role="img" width="38"
                                                                height="24" aria-labelledby="pi-visa">
                                                                <title id="pi-visa">Visa</title>
                                                                <path opacity=".07"
                                                                    d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z">
                                                                </path>
                                                                <path fill="#fff"
                                                                    d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32">
                                                                </path>
                                                                <path
                                                                    d="M28.3 10.1H28c-.4 1-.7 1.5-1 3h1.9c-.3-1.5-.3-2.2-.6-3zm2.9 5.9h-1.7c-.1 0-.1 0-.2-.1l-.2-.9-.1-.2h-2.4c-.1 0-.2 0-.2.2l-.3.9c0 .1-.1.1-.1.1h-2.1l.2-.5L27 8.7c0-.5.3-.7.8-.7h1.5c.1 0 .2 0 .2.2l1.4 6.5c.1.4.2.7.2 1.1.1.1.1.1.1.2zm-13.4-.3l.4-1.8c.1 0 .2.1.2.1.7.3 1.4.5 2.1.4.2 0 .5-.1.7-.2.5-.2.5-.7.1-1.1-.2-.2-.5-.3-.8-.5-.4-.2-.8-.4-1.1-.7-1.2-1-.8-2.4-.1-3.1.6-.4.9-.8 1.7-.8 1.2 0 2.5 0 3.1.2h.1c-.1.6-.2 1.1-.4 1.7-.5-.2-1-.4-1.5-.4-.3 0-.6 0-.9.1-.2 0-.3.1-.4.2-.2.2-.2.5 0 .7l.5.4c.4.2.8.4 1.1.6.5.3 1 .8 1.1 1.4.2.9-.1 1.7-.9 2.3-.5.4-.7.6-1.4.6-1.4 0-2.5.1-3.4-.2-.1.2-.1.2-.2.1zm-3.5.3c.1-.7.1-.7.2-1 .5-2.2 1-4.5 1.4-6.7.1-.2.1-.3.3-.3H18c-.2 1.2-.4 2.1-.7 3.2-.3 1.5-.6 3-1 4.5 0 .2-.1.2-.3.2M5 8.2c0-.1.2-.2.3-.2h3.4c.5 0 .9.3 1 .8l.9 4.4c0 .1 0 .1.1.2 0-.1.1-.1.1-.1l2.1-5.1c-.1-.1 0-.2.1-.2h2.1c0 .1 0 .1-.1.2l-3.1 7.3c-.1.2-.1.3-.2.4-.1.1-.3 0-.5 0H9.7c-.1 0-.2 0-.2-.2L7.9 9.5c-.2-.2-.5-.5-.9-.6-.6-.3-1.7-.5-1.9-.5L5 8.2z"
                                                                    fill="#142688"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="payment-card__number">Ending in
                                                            <strong>4506</strong></div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="payment-card__name">Pawan kumar</div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="payment-card__expiry">Expiry
                                                            <strong>02/2023</strong></div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="payment-card__actions ">
                                                        <ul class="list-actions">
                                                                <li>
                                                                    <a href="#"><svg class="svg" width="24px"
                                                                            height="24px">
                                                                            <use xlink:href="../images/retina/sprite.svg#remove"
                                                                                href="../images/retina/sprite.svg#remove">
                                                                            </use>
                                                                        </svg>
                                                                    </a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                        </ul>
                                        <div class="my-3 text-right">
                                            <a class="link-text" href="">
                                                <i class="icn"> <svg class="svg">
                                                        <use xlink:href="../images/retina/sprite.svg#add"
                                                            href="../images/retina/sprite.svg#add">
                                                        </use>
                                                    </svg> </i> Add a new card</a>

                                        </div>


                                        <div class="bg-gray p-4">
                                            <form class="form form form-floating" action="">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group form-floating__group">
                                                            <input type="text" class="form-control form-floating__field"
                                                                placeholder="" id="">
                                                            <label class="form-floating__label">Card number</label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group form-floating__group">
                                                            <input type="text" class="form-control form-floating__field"
                                                                placeholder="" id="">
                                                            <label class="form-floating__label">Name on card</label>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group form-floating__group">
                                                            <input type="text" class="form-control form-floating__field"
                                                                placeholder="" id="">
                                                            <label class="form-floating__label">Expiration date (MM /
                                                                YY)</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group form-floating__group">
                                                            <input type="text" class="form-control form-floating__field"
                                                                placeholder="" id="">
                                                            <label class="form-floating__label">Security code</label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="paypal" role="tabpanel" aria-labelledby="paypal-tab">

                                        <div class="paypal-data">
                                            <img src="../images//paypal.png" alt="">
                                            <p>You'll return to yokart.com to review and place your order.</p>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="paycash" role="tabpanel"
                                        aria-labelledby="paycash-tab">

                                        <form class="form form form-floating" action="">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group form-floating__group">
                                                        <input type="text" class="form-control form-floating__field"
                                                            placeholder="" id="">
                                                        <label class="form-floating__label">Enter OTP</label>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <label class="checkbox"><input title="" type="checkbox" value="1">Send me electronic or mail
                                offers from Nordstrom. You may opt out at any time.<i class="input-helper"></i>
                            </label>
                        </div>
                    </div>
                    <!-- end::Step -->
                </main>
            </div>
            <aside class="aside" role="complementary">
                <div class="aside__content">
                    <div id="order-summary" class="order-summary">
                        <h5 class="mb-2"> Order Summary</h5>
                        <div class="order-summary__sections">
                            <div class="order-summary__section order-summary__section--total-lines">
                                <!-- Total -->
                                <div class="cart-total my-3">
                                    <div class="">
                                        <ul class="list-group list-group-flush list-group-flush-x">
                                            <li class="list-group-item border-0">
                                                <span class="label">Subtotal</span> <span class="ml-auto">$89.00</span>
                                            </li>
                                            <li class="list-group-item ">
                                                <span class="label">Estimated Tax</span> <span
                                                    class="ml-auto">$00.00</span>
                                            </li>
                                            <li class="list-group-item hightlighted border-0">
                                                <span class="label">Total</span> <span class="ml-auto">$89.00</span>
                                            </li>
                                        </ul>
                                        <p class="earn-points"><svg class="svg" width="20px" height="20px">
                                                <use xlink:href="../images/retina/sprite.svg#rewards"
                                                    href="../images/retina/sprite.svg#rewards">
                                                </use>
                                            </svg> You will earn 575 points </p>

                                    </div>
                                </div>
                            </div>
                            <div class="order-summary__section order-summary__section--product-list">
                                <div class="order-summary__section__content scroll">
                                    <!-- List group -->

                                    <ul class="list-group list-cart list-cart-checkout">
                                        <li class="list-group-item">
                                            <div class="product-profile">
                                                <div class="product-profile__thumbnail">
                                                    <a href="#">
                                                        <img class="img-fluid" data-ratio="3:4"
                                                            src="../images//products/product-thumb.jpg" alt="...">
                                                    </a>
                                                    <span class="product-qty">2</span>
                                                </div>
                                                <div class="product-profile__data">
                                                    <div class="title"><a class="" href="product.html">Cotton
                                                            floral print</a></div>
                                                    <div class="options">
                                                        <p class="">Medium | red</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="product-price">$40.00 <del>$50.00</del></div>

                                        </li>

                                        <li class="list-group-item">
                                            <div class="product-profile">
                                                <div class="product-profile__thumbnail">
                                                    <a href="#">
                                                        <img class="img-fluid" data-ratio="3:4"
                                                            src="../images//products/product-thumb.jpg" alt="...">
                                                    </a>
                                                    <span class="product-qty">2</span>
                                                </div>
                                                <div class="product-profile__data">
                                                    <div class="title"><a class="" href="product.html">Cotton
                                                            floral print</a></div>
                                                    <div class="options">
                                                        <p class="">Medium | red</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="product-price">$40.00 <del>$50.00</del></div>

                                        </li>
                                        <li class="list-group-item">
                                            <div class="product-profile">
                                                <div class="product-profile__thumbnail">
                                                    <a href="#">
                                                        <img class="img-fluid" data-ratio="3:4"
                                                            src="../images//products/product-thumb.jpg" alt="...">
                                                    </a>
                                                    <span class="product-qty">2</span>
                                                </div>
                                                <div class="product-profile__data">
                                                    <div class="title"><a class="" href="product.html">Cotton
                                                            floral print</a></div>
                                                    <div class="options">
                                                        <p class="">Medium | red</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="product-price">$40.00 <del>$50.00</del></div>

                                        </li>
                                        <li class="list-group-item">
                                            <div class="product-profile">
                                                <div class="product-profile__thumbnail">
                                                    <a href="#">
                                                        <img class="img-fluid" data-ratio="3:4"
                                                            src="../images//products/product-thumb.jpg" alt="...">
                                                    </a>
                                                    <span class="product-qty">2</span>
                                                </div>
                                                <div class="product-profile__data">
                                                    <div class="title"><a class="" href="product.html">Cotton
                                                            floral print</a></div>
                                                    <div class="options">
                                                        <p class="">Medium | red</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="product-price">$40.00 <del>$50.00</del></div>

                                        </li>


                                    </ul>




                                </div>
                            </div>
                            <div class="place-order">
                                <p>By placing an order, you agree to Yokart.com's <a href=""> Terms & Conditions</a> and
                                    <a href=""> Privacy Policy </a></p>
                                <button class="btn btn-primary btn-lg btn-block"></span>Place Order</button>
                            </div>

                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>