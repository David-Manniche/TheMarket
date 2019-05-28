<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frmSrch->setFormTagAttribute('onSubmit', 'searchCredits(this); return false;');
$frmSrch->setFormTagAttribute('class', 'form');
$frmSrch->developerTags['colClassPrefix'] = 'col-md-';
$frmSrch->developerTags['fld_default_col'] = 12;

$keyFld = $frmSrch->getField('keyword');
$keyFld->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Keyword', $siteLangId));
$keyFld->setWrapperAttribute('class', 'col-lg-6');
$keyFld->developerTags['col'] = 6;

$keyFld = $frmSrch->getField('debit_credit_type');
$keyFld->setWrapperAttribute('class', 'col-lg-6');
$keyFld->developerTags['col'] = 6;

$keyFld = $frmSrch->getField('date_from');
$keyFld->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_From_Date', $siteLangId));
$keyFld->setWrapperAttribute('class', 'col-lg-4');
$keyFld->developerTags['col'] = 4;

$keyFld = $frmSrch->getField('date_to');
$keyFld->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_To_Date', $siteLangId));
$keyFld->setWrapperAttribute('class', 'col-lg-4');
$keyFld->developerTags['col'] = 4;

/* $keyFld = $frmSrch->getField('date_order');
$keyFld->setWrapperAttribute('class','col-lg-6');
$keyFld->developerTags['col'] = 6; */

$submitBtnFld = $frmSrch->getField('btn_submit');
$submitBtnFld->setFieldTagAttribute('class', 'btn--block');
$submitBtnFld->setWrapperAttribute('class', 'col-lg-2');
$submitBtnFld->developerTags['col'] = 2;

$cancelBtnFld = $frmSrch->getField('btn_clear');
$cancelBtnFld->setFieldTagAttribute('class', 'btn--block');
$cancelBtnFld->setWrapperAttribute('class', 'col-lg-2');
$cancelBtnFld->developerTags['col'] = 2;
?> <?php $this->includeTemplate('_partial/dashboardNavigation.php'); ?> <main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header row justify-content-between mb-3">
            <div class="col-md-auto"> <?php $this->includeTemplate('_partial/dashboardTop.php'); ?> <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_My_Account', $siteLangId);?></h2>
            </div>
        </div>
        <div class="content-body">
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="cards">
                        <div id="withdrawalReqForm"></div>
                        <div class="cards-content pl-4 pr-4 ">
                            <!-- wallet balance[ -->
                            <?php
                            $balanceTotalBlocksDisplayed = 1;

                            $showTotalBalanceAvailableDiv = false;
                            if ($userTotalWalletBalance != $userWalletBalance) {
                                $showTotalBalanceAvailableDiv = true;
                                $balanceTotalBlocksDisplayed ++;
                            }

                            $showPromotionWalletChargedDiv = false;
                            if ($promotionWalletToBeCharged) {
                                $showPromotionWalletChargedDiv = true;
                                $balanceTotalBlocksDisplayed ++;
                            }


                            $showWithdrawalRequestAmountDiv = false;
                            if ($withdrawlRequestAmount) {
                                $showWithdrawalRequestAmountDiv = true;
                                $balanceTotalBlocksDisplayed ++;
                            }

                            $totalWidth = 12;
                            $tobeShownWidth = intval($totalWidth/$balanceTotalBlocksDisplayed);
                            $totalBalanceAvailableDiv = $tobeShownWidth;
                            $promotionWalletChargedDiv = $tobeShownWidth;
                            $withdrawalRequestAmountDiv = $tobeShownWidth;
                            $currentBalanceDiv = $tobeShownWidth;
                            ?>
                            <div class="row">
                                <?php if ($showTotalBalanceAvailableDiv) { ?>
                                    <div class="col-lg-<?php echo $totalBalanceAvailableDiv; ?> col-md-<?php echo $totalBalanceAvailableDiv; ?> mb-3 mb-md-0">
                                        <div class="balancebox" id="rechargeWalletDiv">
                                            <p><?php echo Labels::getLabel('LBL_Total_Balance_Available', $siteLangId); ?>: </p>
                                            <h2><strong><?php echo CommonHelper::displayMoneyFormat($userTotalWalletBalance); ?></strong></h2>
                                            <?php if (CommonHelper::getCurrencyId() != FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1)) { ?>
                                                \
                                                <small>
                                                    <?php echo Labels::getLabel('LBL_Approx.', $siteLangId); ?>
                                                    <?php echo CommonHelper::displayMoneyFormat($userTotalWalletBalance, true, true); ?>
                                                </small>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php }
                                if ($showPromotionWalletChargedDiv) { ?>
                                    <div class="col-lg-<?php echo $promotionWalletChargedDiv; ?> col-md-<?php echo $promotionWalletChargedDiv; ?>  mb-3 mb-md-0 ">
                                        <div class="balancebox">
                                            <p><?php echo Labels::getLabel('LBL_Promotion_Wallet_To_Be_Charged', $siteLangId); ?>: </p>
                                            <h2><strong>
                                                <?php echo CommonHelper::displayMoneyFormat($promotionWalletToBeCharged); ?></strong></h2>
                                                <?php if (CommonHelper::getCurrencyId() != FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1)) { ?>
                                                    <small>
                                                        <?php echo Labels::getLabel('LBL_Approx.', $siteLangId);
                                                        echo CommonHelper::displayMoneyFormat($promotionWalletToBeCharged, true, true); ?>
                                                    </small>
                                                <?php } ?>
                                        </div>
                                    </div>
                                <?php }
                                if ($showWithdrawalRequestAmountDiv) {
                                    ?> <div class="col-lg-<?php echo $withdrawalRequestAmountDiv; ?> col-md-<?php echo $withdrawalRequestAmountDiv; ?> ">
                                        <div class="balancebox">
                                            <p><?php echo Labels::getLabel('LBL_Withdrawl_Request_Amount', $siteLangId); ?>: </p>
                                            <h2><strong>
                                            <?php echo CommonHelper::displayMoneyFormat($withdrawlRequestAmount); ?></strong></h2>
                                            <?php if (CommonHelper::getCurrencyId() != FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1)) { ?>
                                                <small><?php echo Labels::getLabel('LBL_Approx.', $siteLangId); ?> <?php echo CommonHelper::displayMoneyFormat($withdrawlRequestAmount, true, true); ?></small>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="col-lg-<?php echo $currentBalanceDiv; ?> col-md-<?php echo $currentBalanceDiv; ?> mt-4">
                                        <div class="balancebox">
                                            <p><?php echo Labels::getLabel('LBL_Current_Balance', $siteLangId);?>: </p>
                                            <h2><strong>
                                                <?php echo CommonHelper::displayMoneyFormat($userWalletBalance);?></strong></h2>
                                                <?php if (CommonHelper::getCurrencyId() != FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1)) { ?>
                                                    <small><?php echo Labels::getLabel('LBL_Approx.', $siteLangId); ?> <?php echo CommonHelper::displayMoneyFormat($userWalletBalance, true, true); ?></small>
                                                <?php } ?>
                                                <a href="javascript:void(0)" onClick="withdrawalReqForm()" class="btn btn--primary btn--sm"><?php echo Labels::getLabel('LBL_Request_Withdrawal', $siteLangId); ?></a>
                                        </div>
                                    </div>
                            </div>
                            <div class="gap"></div>
                            <?php //echo $balanceTotalBlocksDisplayed;?>
                            <?php $srchFormDivWidth = $canAddMoneyToWallet ? '8' : 12; ?>
                            <div class="row">
                                <div class="col-lg-<?php echo $srchFormDivWidth; ?> col-md-<?php echo $srchFormDivWidth; ?> ">
                                    <div class="bg-gray-light p-3 pb-0">
                                        <h5 class="cards-title"><?php echo Labels::getLabel('LBL_Search_Transactions', $siteLangId);?></h5>
                                        <?php
                                        $submitFld = $frmSrch->getField('btn_submit');
                                        $submitFld->setFieldTagAttribute('class', 'btn--block btn btn--primary');

                                        $fldClear= $frmSrch->getField('btn_clear');
                                        $fldClear->setFieldTagAttribute('class', 'btn--block btn btn--primary-border');
                                        echo $frmSrch->getFormHtml();
                                        ?>
                                    </div>
                                </div>
                                <?php if ($canAddMoneyToWallet) { ?>
                                    <div class="col-lg-4 col-md-4 float--right ">
                                        <div class="bg-gray-light p-3 pb-0">
                                            <h5 class="cards-title">
                                            <?php echo Labels::getLabel('LBL_Enter_amount_to_be_Added'.'_['.CommonHelper::getDefaultCurrencySymbol().']', $siteLangId); ?></h5>
                                            <div id="rechargeWalletDiv" class="cellright nopadding--bottom">
                                                <?php
                                                $frmRechargeWallet->setFormTagAttribute('onSubmit', 'setUpWalletRecharge(this); return false;');
                                                $frmRechargeWallet->setFormTagAttribute('class', 'form');
                                                $frmRechargeWallet->developerTags['colClassPrefix'] = 'col-md-';
                                                $frmRechargeWallet->developerTags['fld_default_col'] = 12;
                                                $frmRechargeWallet->setRequiredStarPosition(Form::FORM_REQUIRED_STAR_WITH_NONE);

                                                $amountFld = $frmRechargeWallet->getField('amount');
                                                $amountFld->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Enter_amount_to_be_Added', $siteLangId));
                                                $buttonFld = $frmRechargeWallet->getField('btn_submit');
                                                $buttonFld->setFieldTagAttribute('class', 'btn--block block-on-mobile');
                                                echo $frmRechargeWallet->getFormHtml(); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="gap"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="cards">
                        <div class="cards-header p-4">
                            <h5 class="cards-title">Data heading goes here</h5>
                        </div>
                        <div class="cards-content pl-4 pr-4 ">
                            <div id="creditListing"><?php echo Labels::getLabel('LBL_Loading..', $siteLangId); ?></div>
                            <span class="gap"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
