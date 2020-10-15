<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="section-head">
    <div class="section__heading">
        <h2><?php echo Labels::getLabel('LBL_Payment_Summary', $siteLangId); ?></h2>
    </div>
</div>
<?php $rewardPoints = UserRewardBreakup::rewardPointBalance(UserAuthentication::getLoggedUserId()); ?>
<div class="box box--white box--radius p-4">
    <section id="payment" class="section-checkout">
        <div class="align-items-center mb-4">
            <?php if ($userWalletBalance > 0 && $cartSummary['orderNetAmount'] > 0 && $canUseWalletForPayment) { ?>
                <div>
                    <div id="wallet" class="wallet">
                        <label class="checkbox brand" id="brand_95">
                            <input onChange="walletSelection(this)" type="checkbox" <?php echo ($cartSummary["cartWalletSelected"]) ? 'checked="checked"' : ''; ?> name="pay_from_wallet" id="pay_from_wallet" />
                            <i class="input-helper"></i>
                            <?php if ($cartSummary["cartWalletSelected"] && $userWalletBalance >= $cartSummary['orderNetAmount']) {
                                echo '<strong>'.Labels::getLabel('LBL_Sufficient_balance_in_your_wallet', $siteLangId).'</strong>'; //';
                            } else {
                                echo '<strong>'.Labels::getLabel('MSG_Use_My_Wallet_Credits', $siteLangId)?>: (<?php echo CommonHelper::displayMoneyFormat($userWalletBalance, true, false, true, false, true)?>)</strong>
                            <?php } ?>
                        </label>

                        <?php if ($cartSummary["cartWalletSelected"]) { ?>
                            <div class="listing--grids">
                                <ul>
                                    <li>
                                        <div class="boxwhite">
                                            <p><?php echo Labels::getLabel('LBL_Payment_to_be_made', $siteLangId); ?></p>
                                            <h5><?php echo CommonHelper::displayMoneyFormat($cartSummary['orderNetAmount'], true, false, true, false, true); ?></h5>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="boxwhite">
                                            <p><?php echo Labels::getLabel('LBL_Amount_in_your_wallet', $siteLangId); ?></p>
                                            <h5><?php echo CommonHelper::displayMoneyFormat($userWalletBalance, true, false, true, false, true); ?></h5>
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
                                            $btnSubmitFld->addFieldTagAttribute('class', 'btn btn-outline-brand');

                                            $WalletPaymentForm->developerTags['colClassPrefix'] = 'col-md-';
                                            $WalletPaymentForm->developerTags['fld_default_col'] = 12;
                                            echo $WalletPaymentForm->getFormHtml(); ?>
                                        </li>
                                        <script type="text/javascript">
                                            function confirmOrder(frm) {
                                                var data = fcom.frmData(frm);
                                                var action = $(frm).attr('action')
                                                fcom.updateWithAjax(fcom.makeUrl('SubscriptionCheckout', 'ConfirmOrder'), data, function(ans) {
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
            <?php } 
            
            if ($subscriptionType == SellerPackages::PAID_TYPE && $canUseWalletForPayment) { ?>
                    <p class="note"><?php echo Labels::getLabel('LBL_Note_Please_Maintain_Wallet_Balance_for_further_auto_renewal_payments', $siteLangId); ?></p>
                    <div class="gap"></div>
            <?php }
            
            if ($cartSummary['orderNetAmount'] <= 0) { ?>
                <div class="gap"></div>
                <div>
                    <h6><?php echo Labels::getLabel('LBL_Payment_to_be_made', $siteLangId); ?> <strong>
                        <?php
                        $btnSubmitFld = $confirmPaymentFrm->getField('btn_submit');
                        $btnSubmitFld->addFieldTagAttribute('class', 'btn btn-brand');

                        $confirmPaymentFrm->developerTags['colClassPrefix'] = 'col-md-';
                        $confirmPaymentFrm->developerTags['fld_default_col'] = 12;
                        echo $confirmPaymentFrm->getFormHtml(); ?>
                        <div class="gap"></div>
                        <script type="text/javascript">
                            function confirmOrder(frm) {
                                var data = fcom.frmData(frm);
                                var action = $(frm).attr('action')
                                fcom.updateWithAjax(fcom.makeUrl('SubscriptionCheckout', 'ConfirmOrder'), data, function(ans) {
                                    $(location).attr("href", action);
                                });
                            }
                        </script>
                </div>
            <?php } ?>
        </div>
        <?php
        $gatewayCount=0;
        foreach ($paymentMethods as $key => $val) {
            if (in_array($val['plugin_code'], $excludePaymentGatewaysArr[applicationConstants::CHECKOUT_SUBSCRIPTION])) {
                continue;
            }
            $gatewayCount++;
        }
        if ($cartSummary['orderPaymentGatewayCharges']) { ?>
            <div class="align-items-center mb-4">
                <div class="gap"></div>
                <h6>
                    <?php echo Labels::getLabel('LBL_Net_Payable', $siteLangId); ?> 
                    <strong>
                        <?php echo CommonHelper::displayMoneyFormat($cartSummary['orderPaymentGatewayCharges'], true, false, true, false, true); ?>
                        <?php if (CommonHelper::getCurrencyId() != FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1)) { ?>
                            <p>
                                <?php echo CommonHelper::currencyDisclaimer($siteLangId, $cartSummary['orderPaymentGatewayCharges']); ?>
                            </p>
                        <?php } ?>
                    </strong>
                </h6>
                <div class="gap"></div>
            </div>
            <div class="payment-area" <?php echo ($cartSummary['orderPaymentGatewayCharges'] <= 0) ? 'is--disabled' : ''; ?>>
                <?php if ($cartSummary['orderPaymentGatewayCharges'] && 0 < $gatewayCount && 0 < count($paymentMethods)) { ?>
                    <?php if ($paymentMethods) { ?>
                        <ul class="nav nav-payments <?php echo 1 == count($paymentMethods) ? 'd-none' : ''; ?>" role="tablist" id="payment_methods_tab">
                            <?php foreach ($paymentMethods as $key => $val) {
                                if (in_array($val['plugin_code'], $excludePaymentGatewaysArr[applicationConstants::CHECKOUT_SUBSCRIPTION])) {
                                    continue;
                                }
                                $pmethodCode = $val['plugin_code'];
                                $pmethodId = $val['plugin_id'];
                                $pmethodName = $val['plugin_name'];
                                if(strtolower($val['plugin_code']) == 'cashondelivery' && $fulfillmentType == Shipping::FULFILMENT_PICKUP){
                                    $pmethodName = Labels::getLabel('LBL_Pay_on_pickup', $siteLangId);
                                }

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
                                <div class="tabs-container" id="tabs-container"></div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else {
                    echo Labels::getLabel("LBL_Payment_method_is_not_available._Please_contact_your_administrator.", $siteLangId);
                } ?>
                    </div>
        <?php } ?>
    </section>
</div>

<?php if ($cartSummary['orderPaymentGatewayCharges']) { ?>
    <script type="text/javascript">
        var tabsId = '#payment_methods_tab';
        $(document).ready(function() {
            $(tabsId + " li:first a").addClass('active');
            if ($(tabsId + ' li a.active').length > 0) {
                loadTab($(tabsId + ' li a.active'));
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
            });
        }
    </script>
<?php }
