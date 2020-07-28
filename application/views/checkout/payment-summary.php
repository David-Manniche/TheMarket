<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$rewardPoints = UserRewardBreakup::rewardPointBalance(UserAuthentication::getLoggedUserId());
?>
<main class="main__content">   
    <div class="step active" role="step:4">
        <div class="step__section">
             <div class="step__section__head">
                <h5 class="step__section__head__title"><?php echo Labels::getLabel('LBL_Payment_Summary', $siteLangId); ?></h5>
            </div>
            <label class="checkbox"><input type="checkbox" checked='checked' name="isShippingSameAsBilling" value="1"><?php echo Labels::getLabel('LBL_MY_BILLING_IS_SAME_AS_SHIPPING_ADDRESS', $siteLangId); ?> <i class="input-helper"></i>
            </label>
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
                    <span> <svg class="svg">
                            <use xlink:href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#info"
                                href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#info">
                            </use>
                        </svg> <?php $canBeUsed = min(min($rewardPoints, CommonHelper::convertCurrencyToRewardPoint($cartSummary['cartTotal']-$cartSummary["cartDiscounts"]["coupon_discount_total"])), FatApp::getConfig('CONF_MAX_REWARD_POINT', FatUtility::VAR_INT, 0));?> <?php
                        $str = Labels::getLabel('LBL_MAXIMUM_{REWARDS}_REWARDS_POINT_REDEEM_FOR_THIS_ORDER', $siteLangId);
                        echo CommonHelper::replaceStringData($str, ['{REWARDS}' => $canBeUsed]);
                        ?> </span>
                    </div>
                    <?php
                    $redeemRewardFrm->setFormTagAttribute('class', 'form form-floating');
                    $redeemRewardFrm->setFormTagAttribute('onsubmit', 'useRewardPoints(this); return false;');
                    $redeemRewardFrm->setJsErrorDisplay('afterfield');
                    $fld = $redeemRewardFrm->getField('redeem_rewards');
                    $fld->setFieldTagAttribute('class', 'form-control form-floating__field');
                    $fld = $redeemRewardFrm->getField('btn_submit');
                    $fld->setFieldTagAttribute('class', 'btn btn-primary btn-wide');
                    echo $redeemRewardFrm->getFormTag();  ?>                    
                        <div class="row form-row">
                            <div class="col">
                                <div class="form-group form-floating__group">
                                    <?php echo $redeemRewardFrm->getFieldHtml('redeem_rewards');?>                                    
                                    <label class="form-floating__label"><?php echo Labels::getLabel('LBL_Use_Reward_Point', $siteLangId);?></label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <!-- Button -->
                                <?php echo $redeemRewardFrm->getFieldHtml('btn_submit');?>
                            </div>
                        </div>
                    </form>
                    <?php echo  $redeemRewardFrm->getExternalJs();?>
                </div>
            <?php }?> 
            <?php } else {?>  
                <div class="info">
                    <span> <svg class="svg">
                            <use xlink:href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#info" href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#info">
                            </use>
                        </svg> <?php echo Labels::getLabel('LBL_Reward_Points', $siteLangId); ?> <strong><?php echo $cartSummary['cartRewardPoints']; ?>
                            (<?php echo CommonHelper::displayMoneyFormat(CommonHelper::convertRewardPointToCurrency($cartSummary['cartRewardPoints']), true, false, true, false, true); ?>)</strong>
                        <?php echo Labels::getLabel('LBL_Successfully_Used', $siteLangId); ?></span>
                    <ul class="list-actions">
                        <li>
                        <a href="javascript:void(0)" onClick="removeRewardPoints()"><svg class="svg" width="24px" height="24px">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#remove" href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#remove">
                                </use>
                            </svg>
                        </a></li>
                    </ul>
                </div>                 
            <?php }?>
        </div>
    </div>
</main>    
<div class="box box--white box--radius p-4">
    <section id="payment" class="section-checkout">
       
        <div class="align-items-center mb-4">
            <?php if ($userWalletBalance > 0 && $cartSummary['orderNetAmount'] > 0) { ?>
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
                            <?php /* if( $userWalletBalance < $cartSummary['orderNetAmount'] ){ ?> <li>
                                        <div class="boxwhite">
                                            <p>Select an Option to pay balance</p>
                                            <h5><?php echo CommonHelper::displayMoneyFormat($cartSummary['orderPaymentGatewayCharges']); ?></h5>
                                        </div>
                                    </li> <?php } */ ?>
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
            <?php } ?>
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
        $gatewayCount=0;
        foreach ($paymentMethods as $key => $val) {
            $pmethodCode = $val['plugin_code'];

            if (in_array($pmethodCode, $excludePaymentGatewaysArr[applicationConstants::CHECKOUT_PRODUCT])) {
                continue;
            }
            $gatewayCount++;
        }
        if ($cartSummary['orderPaymentGatewayCharges']) { ?>
        <div class="row">
            <div class="col-md-4">
                <div class="payment_methods_list" <?php echo ($cartSummary['orderPaymentGatewayCharges'] <= 0) ? 'is--disabled' : ''; ?>>
                    <?php if ($cartSummary['orderPaymentGatewayCharges'] && 0 < $gatewayCount && 0 < count($paymentMethods)) { ?>
                    <ul id="payment_methods_tab" class="" data-simplebar>
                        <?php $count = 0;
                        foreach ($paymentMethods as $key => $val) {
                            $pmethodCode = $val['plugin_code'];
                            $pmethodId = $val['plugin_id'];
                            $pmethodName = $val['plugin_name'];

                            if (in_array($pmethodCode, $excludePaymentGatewaysArr[applicationConstants::CHECKOUT_PRODUCT])) {
                                continue;
                            }
                            $count++; ?>
                            <li>
                                <a href="<?php echo UrlHelper::generateUrl('Checkout', 'PaymentTab', array($orderInfo['order_id'], $pmethodId)); ?>"
                                    data-paymentmethod="<?php echo $pmethodCode; ?>">
                                    <div class="payment-box">
                                        <i class="payment-icn">
                                            <?php
                                            if (isset($val['plugin_id'])) {
                                                $fileData = AttachedFile::getAttachment(AttachedFile::FILETYPE_PLUGIN_LOGO, $pmethodId);
                                                $uploadedTime = AttachedFile::setTimeParam($fileData['afile_updated_at']);
                                                $imageUrl = UrlHelper::getCachedUrl(UrlHelper::generateFileUrl('Image', 'plugin', array($pmethodId, 'SMALL')) . $uploadedTime, CONF_IMG_CACHE_TIME, '.jpg');
                                            } else {
                                                $fileData = AttachedFile::getAttachment(AttachedFile::FILETYPE_PAYMENT_METHOD, $pmethodId, 0, 0, false);
                                                $imageUrl = UrlHelper::generateUrl('Image', 'paymentMethod', array($pmethodId,'SMALL'));
                                            }
                                            $aspectRatioArr = AttachedFile::getRatioTypeArray($siteLangId); ?>
                                            <img <?php if ($fileData['afile_aspect_ratio'] > 0) { ?>
                                                data-ratio= "<?php echo $aspectRatioArr[$fileData['afile_aspect_ratio']]; ?>"
                                                <?php } ?> src="<?php echo $imageUrl; ?>" alt="">
                                        </i>
                                        <span><?php echo $pmethodName; ?></span>
                                    </div>
                                </a>
                            </li>
                            <?php
                        } ?>
                    </ul>
                    <?php } else {
                        echo Labels::getLabel("LBL_Payment_method_is_not_available._Please_contact_your_administrator.", $siteLangId);
                    } ?>
                </div>
            </div>
            <div class="col-md-8">
                <div class="payment-from">
                    <div class="you-pay">
                        <?php echo Labels::getLabel('LBL_Net_Payable', $siteLangId); ?>
                        :
                        <?php echo CommonHelper::displayMoneyFormat($cartSummary['orderPaymentGatewayCharges'], true, false, true, false, true); ?>
                        <?php if (CommonHelper::getCurrencyId() != FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1)) { ?>
                        <p><?php echo CommonHelper::currencyDisclaimer($siteLangId, $cartSummary['orderPaymentGatewayCharges']); ?>
                        </p>
                        <?php } ?>
                    </div>
                    <div class="gap"></div>
                    <div id="tabs-container"></div>
                    <?php } ?>
    </section>
</div>
</div>
</div>
<script>
    var enableGcaptcha = false;
</script>
<?php
$siteKey = FatApp::getConfig('CONF_RECAPTCHA_SITEKEY', FatUtility::VAR_STRING, '');
$secretKey = FatApp::getConfig('CONF_RECAPTCHA_SECRETKEY', FatUtility::VAR_STRING, '');
if (!empty($siteKey) && !empty($secretKey)) {?>
<script
    src='https://www.google.com/recaptcha/api.js?render=<?php echo $siteKey; ?>'>
</script>
<script>
    var enableGcaptcha = true;
</script>
<?php } ?>

<?php if ($cartSummary['orderPaymentGatewayCharges']) { ?>
<script type="text/javascript">
    var containerId = '#tabs-container';
    var tabsId = '#payment_methods_tab';
    $(document).ready(function() {
        if ($(tabsId + ' li a.is-active').length > 0) {
            loadTab($(tabsId + ' li a.is-active'));
        }
        $(tabsId + ' A').click(function() {
            if ($(this).hasClass('is-active')) {
                return false;
            }
            $(tabsId + ' li a.is-active').removeClass('is-active');
            $('li').removeClass('is-active');
            $(this).parent().addClass('is-active');
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
        $(containerId).html(fcom.getLoader());
        fcom.ajax(tabObj.attr('href'), '', function(response) {
            $(containerId).html(response);
            var paymentMethod = tabObj.data('paymentmethod');
            if ('cashondelivery' == paymentMethod.toLowerCase() && true == enableGcaptcha) {
                googleCaptcha();
            }
        });
    }
</script>
<?php }
