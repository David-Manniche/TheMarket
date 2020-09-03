<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$rewardPoints = UserRewardBreakup::rewardPointBalance(UserAuthentication::getLoggedUserId());
?>
<main class="main__content">
    <div class="step active" role="step:4">
        
        <ul class="list-group review-block">
            <li class="list-group-item">
                <div class="review-block__label">
                    <?php 
                    if ($fulfillmentType == Shipping::FULFILMENT_PICKUP || $cartHasPhysicalProduct == false) { 
                        echo Labels::getLabel('LBL_Billing_to:', $siteLangId);
                        $address = $billingAddressArr;
                    }else{
                        echo Labels::getLabel('LBL_Shipping_to:', $siteLangId);
                        $address = $shippingAddressArr;
                    }
                    ?>
                </div>
                <div class="review-block__content" role="cell">  
                    <div class="delivery-address">             
                        <p><?php echo ( mb_strlen($address['addr_address1'] ) > 0 ) ? $address['addr_address1'] : '';?>
                        <?php echo ( mb_strlen($address['addr_address2'] ) > 0 ) ? $address['addr_address2'] . '<br>' : '';?>
                        <?php echo ( mb_strlen($address['addr_city']) > 0 ) ? $address['addr_city'] . ',' : '';?>
                        <?php echo ( mb_strlen($address['state_name']) > 0 ) ? $address['state_name'] . '<br>' : '';?>
                        <?php echo ( mb_strlen($address['country_name']) > 0 ) ? $address['country_name'] . ',' : '';?>
                        <?php echo ( mb_strlen($address['addr_zip']) > 0 ) ?  $address['addr_zip'] . '<br>' : '';?></p>
                        <p class="phone-txt"><?php echo ( mb_strlen($address['addr_phone']) > 0 ) ? $address['addr_phone'] . '' : '';?></p>
                    </div>
                </div>
                <div class="review-block__link" role="cell">
                    <?php 
                    if ($fulfillmentType == Shipping::FULFILMENT_PICKUP || $cartHasPhysicalProduct == false) {
                        $onclick = 'loadAddressDiv('.Address::ADDRESS_TYPE_BILLING.');';
                    }else{ 
                        $onclick = 'loadAddressDiv();';
                    } 
                    ?>
                    <a class="link" href="javascript:void(0);" onClick="<?php echo $onclick; ?>"><span><?php echo Labels::getLabel('LBL_Change_Address', $siteLangId); ?></span></a>
                </div>
            </li>
            
            <?php if ($fulfillmentType == Shipping::FULFILMENT_PICKUP && !empty($orderPickUpData)) { ?>
            <li class="list-group-item">
                <div class="review-block__label">
                <?php echo Labels::getLabel('LBL_Pickup_Address:', $siteLangId); ?>
                </div>
                <div class="review-block__content" role="cell">  
                    <div class="delivery-address"> 
                        <?php foreach($orderPickUpData as $address) { ?>
                            <p><strong><?php echo ($address['opshipping_by_seller_user_id'] > 0) ? $address['op_shop_name'] : FatApp::getConfig('CONF_WEBSITE_NAME_' . $siteLangId, null, ''); ?></strong></p>
                            <p><?php echo ( mb_strlen($address['oua_address1'] ) > 0 ) ? $address['oua_address1'] : '';?>
                            <?php echo ( mb_strlen($address['oua_address2'] ) > 0 ) ? $address['oua_address2'] . '<br>' : '';?>
                            <?php echo ( mb_strlen($address['oua_city']) > 0 ) ? $address['oua_city'] . ',' : '';?>
                            <?php echo ( mb_strlen($address['oua_state']) > 0 ) ? $address['oua_state'] . '<br>' : '';?>
                            <?php echo ( mb_strlen($address['oua_country']) > 0 ) ? $address['oua_country'] . ',' : '';?>
                            <?php echo ( mb_strlen($address['oua_zip']) > 0 ) ?  $address['oua_zip'] . '<br>' : '';?></p>
                            <p class="phone-txt"><?php echo ( mb_strlen($address['oua_phone']) > 0 ) ? $address['oua_phone'] . '' : '';?></p>
                            <?php 
                            $fromTime = date('H:i', strtotime($address["opshipping_time_slot_from"]));
                            $toTime = date('H:i', strtotime($address["opshipping_time_slot_to"]));
                            ?>
                            <p><?php echo "<strong>".FatDate::format($address["opshipping_date"]).' '.$fromTime.' - '.$toTime.'</strong>'; ?></p>
                            <?php if (count($orderPickUpData) > 1) { ?>
                            <a class="plus-more" href="javascript:void(0);" onClick="orderPickUpData('<?php echo $orderId; ?>')"><?php echo '+ '.Labels::getLabel('LBL_More', $siteLangId); ?></a>
                            <?php break; } ?>
                        <?php } ?>
                    </div>
                </div>
                <div class="review-block__link" role="cell">
                    <a class="link" href="javascript:void(0);" onClick="loadShippingSummaryDiv();"><span><?php echo Labels::getLabel('LBL_Change_Address', $siteLangId); ?></span></a>
                </div>
            </li>
            <?php } ?>
            
            <?php if ($fulfillmentType == Shipping::FULFILMENT_SHIP && !empty($orderShippingData)) { ?>
            <li class="list-group-item">
                <div class="review-block__label">
                    <?php echo Labels::getLabel('LBL_Shipping:', $siteLangId); ?>
                </div>
                <div class="review-block__content" role="cell">  
                    <div class="delivery-address"> 
                         <div class="product-profile__thumbnail">
                    <?php foreach($orderShippingData as $shipData) { ?>
                        <?php foreach($shipData as $data) { 
                            $productUrl = UrlHelper::generateUrl('Products', 'View', array($data['op_selprod_id']));
                        ?>
                            <a href="<?php echo $productUrl;?>">
                                <img class="img-fluid" data-ratio="3:4"
                                    src="<?php echo UrlHelper::getCachedUrl(UrlHelper::generateFileUrl('image', 'product', array($data['selprod_product_id'], "THUMB", $data['op_selprod_id'], 0, $siteLangId)), CONF_IMG_CACHE_TIME, '.jpg'); ?>" alt="<?php echo $data['op_selprod_title']; ?>" title="<?php echo $data['op_selprod_title']; ?>">
                            </a>
                        <?php } ?>
                             </div>
                        <div class="product-profile__data">
                            <div class="title"><?php echo $data['opshipping_label']; ?></div>
                        </div>
                        <?php if (count($orderShippingData) > 1) { ?>
                            <a class="plus-more" href="javascript:void(0);" onClick="orderShippingData('<?php echo $orderId; ?>')"><?php echo '+ '.Labels::getLabel('LBL_More', $siteLangId); ?></a>
                        <?php }                     
                    break; 
                    } 
                    ?>
                    </div>
                </div>
                <div class="review-block__link" role="cell">
                    <a class="link" href="javascript:void(0);" onClick="loadShippingSummaryDiv();"><span><?php echo Labels::getLabel('LBL_Change_Shipping', $siteLangId); ?></span></a>
                </div>
            </li>
            <?php } ?>
        </ul> 
        
        <div class="step__section">
            <div class="step__section__head">
                <h5 class="step__section__head__title"><?php echo Labels::getLabel('LBL_Payment_Summary', $siteLangId); ?></h5>
            </div>
            <?php 
            if ($fulfillmentType == Shipping::FULFILMENT_SHIP) {
                if ($shippingAddressId == $billingAddressId) { 
            ?>
                <label class="checkbox"><input onClick="billingAddress(this);" type="checkbox" checked='checked' name="isShippingSameAsBilling" value="1"><?php echo Labels::getLabel('LBL_MY_BILLING_IS_SAME_AS_SHIPPING_ADDRESS', $siteLangId); ?> <i class="input-helper"></i>
                </label>
            <?php }else{ ?>
                <ul class="list-group review-block">
                    <li class="list-group-item">
                        <div class="review-block__label">
                            <?php echo Labels::getLabel('LBL_Billing_to:', $siteLangId); ?>
                        </div>
                        <div class="review-block__content" role="cell">
                            <?php echo $billingAddressArr['addr_title']; ?>
                            <?php echo $billingAddressArr['addr_name']; ?>
                            <?php echo $billingAddressArr['addr_address1'] . '<br>';?>
                            <?php echo $billingAddressArr['addr_city'];?>,
                            <?php echo $billingAddressArr['state_name'];?>,
                            <?php echo (strlen($billingAddressArr['addr_zip']) > 0) ? Labels::getLabel('LBL_Zip:', $siteLangId) . ' ' . $billingAddressArr['addr_zip'] . ', ' : '';?>
                            <?php echo (strlen($billingAddressArr['addr_phone']) > 0) ? Labels::getLabel('LBL_Phone:', $siteLangId) . ' ' . $billingAddressArr['addr_phone'] . '<br>' : '';?>
                        </div>
                        <div class="review-block__link" role="cell">
                            <a class="link" href="javascript:void(0);" onClick="loadAddressDiv(<?php echo Address::ADDRESS_TYPE_BILLING; ?>)"><span><?php echo Labels::getLabel('LBL_Change_Address', $siteLangId); ?></span></a>
                        </div>
                    </li>
                </ul> 
            <?php } 
            }
            ?>
            
            <?php if (empty($cartSummary['cartRewardPoints'])) { ?>
                <?php if ($rewardPoints > 0) { ?>
                    <div class="rewards">
                        <div class="rewards__points">
                            <ul>
                                <li>
                                    <p><?php echo Labels::getLabel('LBL_AVAILABLE_REWARDS_POINTS', $siteLangId); ?></p>
                                    <span class="count"><?php echo $rewardPoints; ?></span>
                                </li>
                                <li>
                                    <p><?php echo Labels::getLabel('LBL_POINTS_WORTH', $siteLangId); ?></p>
                                    <span class="count"><?php echo CommonHelper::displayMoneyFormat(CommonHelper::convertRewardPointToCurrency($rewardPoints), true, false, true, false, true); ?></span>
                                </li>
                            </ul>
                        </div>
                        <div class="info">
                            <span>
                                <svg class="svg">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#info" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#info"></use>
                                </svg>
                                <?php $canBeUsed = min(min($rewardPoints, CommonHelper::convertCurrencyToRewardPoint($cartSummary['cartTotal'] - $cartSummary["cartDiscounts"]["coupon_discount_total"])), FatApp::getConfig('CONF_MAX_REWARD_POINT', FatUtility::VAR_INT, 0)); ?>
                                <?php $str = Labels::getLabel('LBL_MAXIMUM_{REWARDS}_REWARDS_POINT_REDEEM_FOR_THIS_ORDER', $siteLangId);
                                echo CommonHelper::replaceStringData($str, ['{REWARDS}' => $canBeUsed]); ?>
                            </span>
                        </div>
                        <?php
                        $redeemRewardFrm->setFormTagAttribute('class', 'form form-inline');
                        $redeemRewardFrm->setFormTagAttribute('onsubmit', 'useRewardPoints(this); return false;');
                        $redeemRewardFrm->setJsErrorDisplay('afterfield');
                        $fld = $redeemRewardFrm->getField('redeem_rewards');
                        $fld->setFieldTagAttribute('class', 'form-control');
                        $fld->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Use_Reward_Point', $siteLangId));
                        $fld = $redeemRewardFrm->getField('btn_submit');
                        $fld->setFieldTagAttribute('class', 'btn btn-submit');
                        echo $redeemRewardFrm->getFormTag();  ?>
                            <?php echo $redeemRewardFrm->getFieldHtml('redeem_rewards'); ?>
                            <?php echo $redeemRewardFrm->getFieldHtml('btn_submit'); ?>
                        </form>
                        <?php echo  $redeemRewardFrm->getExternalJs(); ?>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="info">
                    <span> <svg class="svg">
                            <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#info" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#info">
                            </use>
                        </svg> <?php echo Labels::getLabel('LBL_Reward_Points', $siteLangId); ?> <strong><?php echo $cartSummary['cartRewardPoints']; ?>
                            (<?php echo CommonHelper::displayMoneyFormat(CommonHelper::convertRewardPointToCurrency($cartSummary['cartRewardPoints']), true, false, true, false, true); ?>)</strong>
                        <?php echo Labels::getLabel('LBL_Successfully_Used', $siteLangId); ?></span>
                    <ul class="list-actions">
                        <li>
                            <a href="javascript:void(0)" onClick="removeRewardPoints()"><svg class="svg" width="24px" height="24px">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#remove" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#remove">
                                    </use>
                                </svg>
                            </a></li>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div> 
</main>
<?php if ($userWalletBalance > 0 && $cartSummary['orderNetAmount'] > 0 && $canUseWalletForPayment) { ?>
    <div class="wallet-balance">
         <label class="checkbox wallet">
            <input onChange="walletSelection(this)" type="checkbox" <?php echo ($cartSummary["cartWalletSelected"]) ? 'checked="checked"' : ''; ?> name="pay_from_wallet" id="pay_from_wallet" value="1">
            <i class="input-helper"></i>
            <span class="wallet__txt">           
                <svg class="svg">
                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#wallet" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#wallet">
                    </use>
                </svg>
                <div class="">
                    <p><?php echo Labels::getLabel('LBL_AVAILABLE_BALANCE', $siteLangId); ?></p>
                    <span class="currency-value" dir="ltr"><?php echo CommonHelper::displayMoneyFormat($userWalletBalance, true, false, true, false, true); ?></span>
                </div>
            </span>
        </label>
        <?php if ($cartSummary["cartWalletSelected"] && $userWalletBalance >= $cartSummary['orderNetAmount']) { 
            $btnSubmitFld = $WalletPaymentForm->getField('btn_submit');
            $btnSubmitFld->addFieldTagAttribute('class', 'btn btn-primary btn-wide');
            $btnSubmitFld->value = Labels::getLabel('LBL_PAY', $siteLangId) . ' ' . CommonHelper::displayMoneyFormat($cartSummary['orderNetAmount'], true, false, true, false, false);
            $WalletPaymentForm->developerTags['colClassPrefix'] = 'col-md-';
            $WalletPaymentForm->developerTags['fld_default_col'] = 12;
            echo $WalletPaymentForm->getFormHtml(); ?>
            <script type="text/javascript">
                function confirmOrder(frm) {
                    var data = fcom.frmData(frm);
                    var action = $(frm).attr('action');
                    fcom.updateWithAjax(fcom.makeUrl('Checkout', 'ConfirmOrder'), data, function(ans) {
                        $(location).attr("href", action);
                    });
                }
            </script>            
        <?php } else {?>
        <div class="wallet-balance_info">Lorem, ipsum dolor sit amet consectetur adipisicing elit.</div>
        <?php }?>

        <!-- <button class="btn btn-primary btn-wide" type="button">Pay $ 587.00</button> -->
    </div> 
<?php }?>
        <section id="payment" class="section-checkout">
            <div class="align-items-center mb-4">
                <?php /* if ($userWalletBalance > 0 && $cartSummary['orderNetAmount'] > 0) { ?>
                    <div>
                        <div id="wallet" class="wallet">
                            <?php if ($cartSummary["cartWalletSelected"]) { ?>
                                <div class="listing--grids">
                                    <ul>
                                        <li>
                                            <div class="boxwhite">
                                                <p><?php echo Labels::getLabel('LBL_Payment_to_be_made', $siteLangId); ?>
                                                </p>
                                                <h5><?php echo CommonHelper::displayMoneyFormat($cartSummary['orderNetAmount'], true, false, true, false, true); ?>
                                                </h5>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="boxwhite">
                                                <p><?php echo Labels::getLabel('LBL_Amount_in_your_wallet', $siteLangId); ?>
                                                </p>
                                                <h5><?php echo CommonHelper::displayMoneyFormat($userWalletBalance, true, false, true, false, true); ?>
                                                </h5>
                                            </div>
                                            <p class="note">
                                                <i>
                                                    <?php
                                                    $remainingWalletBalance = ($userWalletBalance - $cartSummary['orderNetAmount']);
                                                    $remainingWalletBalance = ($remainingWalletBalance < 0) ? 0 : $remainingWalletBalance;
                                                    echo Labels::getLabel('LBL_Remaining_wallet_balance', $siteLangId) . ' ' . CommonHelper::displayMoneyFormat($remainingWalletBalance, true, false, true, false, true); ?>
                                                </i>
                                            </p>
                                        </li>
                                       
                                        <?php if ($userWalletBalance >= $cartSummary['orderNetAmount']) { ?>
                                            <li>
                                                <?php $btnSubmitFld = $WalletPaymentForm->getField('btn_submit');
                                                $btnSubmitFld->addFieldTagAttribute('class', 'btn btn-outline-primary');

                                                $WalletPaymentForm->developerTags['colClassPrefix'] = 'col-md-';
                                                $WalletPaymentForm->developerTags['fld_default_col'] = 12;
                                                echo $WalletPaymentForm->getFormHtml(); ?>
                                            </li>
                                            <script type="text/javascript">
                                                function confirmOrder(frm) {
                                                    var data = fcom.frmData(frm);
                                                    var action = $(frm).attr('action');
                                                    fcom.updateWithAjax(fcom.makeUrl('Checkout', 'ConfirmOrder'), data, function(ans) {
                                                        $(location).attr("href", action);
                                                    });
                                                }
                                            </script>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } */ ?>
                <?php if ($cartSummary['orderNetAmount'] <= 0) { ?>
                    <div class="gap"></div>
                    <div id="wallet">
                        <h6><?php echo Labels::getLabel('LBL_Payment_to_be_made', $siteLangId); ?>
                            <strong><?php echo CommonHelper::displayMoneyFormat($cartSummary['orderNetAmount'], true, false, true, false, true); ?></strong>
                        </h6> <?php
                                $btnSubmitFld = $confirmForm->getField('btn_submit');
                                $btnSubmitFld->addFieldTagAttribute('class', 'btn btn-primary btn-sm');

                                $confirmForm->developerTags['colClassPrefix'] = 'col-md-';
                                $confirmForm->developerTags['fld_default_col'] = 12;
                                echo $confirmForm->getFormHtml(); ?>
                        <div class="gap"></div>
                    </div>
                <?php } ?>
            </div>
            <?php
            if ($cartSummary['orderPaymentGatewayCharges']) { ?>
                
                        <div class="payment-area" <?php echo ($cartSummary['orderPaymentGatewayCharges'] <= 0) ? 'is--disabled' : ''; ?>>
                            <?php if ($cartSummary['orderPaymentGatewayCharges'] && 0 < count($paymentMethods)) { ?>
                                <ul class="nav nav-payments <?php echo 1 == count($paymentMethods) ? 'd-none' : ''; ?>" role="tablist" id="payment_methods_tab">
                                    <?php foreach ($paymentMethods as $key => $val) {
                                        $pmethodCode = $val['plugin_code'];
                                        $pmethodId = $val['plugin_id'];
                                        $pmethodName = $val['plugin_name'];

                                        if (in_array($pmethodCode, $excludePaymentGatewaysArr[applicationConstants::CHECKOUT_PRODUCT])) {
                                            continue;
                                        }?>
                                        <li class="nav-item">
                                            <a class="nav-link" aria-selected="true" href="<?php echo UrlHelper::generateUrl('Checkout', 'PaymentTab', array($orderInfo['order_id'], $pmethodId)); ?>" data-paymentmethod="<?php echo $pmethodCode; ?>">
                                                <div class="payment-box">
                                                    <span><?php echo $pmethodName; ?></span>
                                                </div>
                                            </a>
                                        </li>
                                    <?php
                                    } ?>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" role="tabpanel" >
                                        <div id="tabs-container"></div>
                                    </div>
                                </div>
                            <?php } else {
                                echo $cartSummary['orderPaymentGatewayCharges']  . ' < ' . count($paymentMethods);
                                echo Labels::getLabel("LBL_PAYMENT_METHOD_IS_NOT_AVAILABLE._PLEASE_CONTACT_YOUR_ADMINISTRATOR.", $siteLangId);
                            } ?>
                        </div>
                
            <?php } ?>
        </section>
    
<script>
    var enableGcaptcha = false;
</script>
<?php
$siteKey = FatApp::getConfig('CONF_RECAPTCHA_SITEKEY', FatUtility::VAR_STRING, '');
$secretKey = FatApp::getConfig('CONF_RECAPTCHA_SECRETKEY', FatUtility::VAR_STRING, '');
$paymentMethods = new PaymentMethods();
if (!empty($siteKey) && !empty($secretKey) && true === $paymentMethods->cashOnDeliveryIsActive()) { ?>
    <script src='https://www.google.com/recaptcha/api.js?render=<?php echo $siteKey; ?>'></script>
    <script>
        var enableGcaptcha = true;
    </script>
<?php } ?>

<?php if ($cartSummary['orderPaymentGatewayCharges']) { ?>
    <script type="text/javascript">
        var tabsId = '#payment_methods_tab';
        $(document).ready(function() {
            if ($(tabsId + ' li a.is-active').length > 0) {
                loadTab($(tabsId + ' li a.is-active'));
            }
            $(tabsId + ' a').click(function() {
                if ($(this).hasClass('active')) {
                    return false;
                }
                $(tabsId + ' li a.active').removeClass('active');
                $(this).addClass('active');
                loadTab($(this));
                return false;
            });
        });

        function loadTab(tabObj) {
            if (isUserLogged() == 0) {
                loginPopUpBox();
                return false;
            }
            if (!tabObj || !tabObj.length) {
                return;
            }

            fcom.ajax(tabObj.attr('href'), '', function(response) {
                $('#tabs-container').html(response);
                var paymentMethod = tabObj.data('paymentmethod');
                if ('cashondelivery' == paymentMethod.toLowerCase()) {
                    if (true == enableGcaptcha) {
                        googleCaptcha();
                    }
                    $.mbsmessage.close();
                } else {
                    var form = '#tabs-container form';
                    if (0 < $(form).length) {
                        $('#tabs-container').append(fcom.getLoader());
                        if (0 < $(form + " input[type='submit']").length) {
                            $(form + " input[type='submit']").val(langLbl.requestProcessing);
                        }
                        setTimeout(function() {
                            $(form).submit()
                        }, 100);
                    }
                }
            });
        }
    </script>
<?php }
