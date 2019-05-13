<div class="wrapper">
    <header id="header-dashboard" class="header-dashboard no-print" role="header-dashboard">
        <div class="row">
            <div class="col-lg-4 col-xs-6">
                <?php if ((User::canViewSupplierTab() && User::canViewBuyerTab()) || (User::canViewSupplierTab() && User::canViewAdvertiserTab()) || (User::canViewBuyerTab() && User::canViewAdvertiserTab())) { ?>
                <div class="dashboard-types no-print">
                    <ul>
                        <?php if (User::canViewSupplierTab()) { ?>
                        <li <?php if ($activeTab == 'S') {
                             echo 'class="is-active"';
                            } ?>>
                            <a href="<?php echo CommonHelper::generateUrl('Seller'); ?>"><?php echo Labels::getLabel('Lbl_Seller', $siteLangId);?></a></li>
                        <?php }?>
                        <?php if (User::canViewBuyerTab()) { ?>
                        <li <?php if ($activeTab == 'B') {
                            echo 'class="is-active"';
                            } ?>>
                            <a href="<?php echo CommonHelper::generateUrl('Buyer'); ?>"><?php echo Labels::getLabel('Lbl_Buyer', $siteLangId);?></a></li>
                        <?php }?>
                        <?php if (User::canViewAdvertiserTab()) { ?>
                        <li <?php if ($activeTab == 'Ad') {
                            echo 'class="is-active"';
                            } ?>>
                            <a href="<?php echo CommonHelper::generateUrl('Advertiser'); ?>"><?php echo Labels::getLabel('Lbl_Advertiser', $siteLangId);?></a></li>
                        <?php }?>
                    </ul>
                </div>
                <?php } ?>
            </div>
            <div class="col-lg-8 col-xs-12">
                <div class="header-icons-group">
                    <?php $getOrgUrl = (CONF_DEVELOPMENT_MODE) ? true : false; ?>
                    <div class="c-header-icon shop">
                        <a data-org-url="<?php echo CommonHelper::generateUrl('home', 'index', array(), '', null, false, $getOrgUrl); ?>" href="<?php echo CommonHelper::generateUrl('Home'); ?>" title="<?php echo Labels::getLabel('LBL_Home', $siteLangId);?>">
                            <i class="icn"><svg class="svg">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#home" href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#home"></use>
                                </svg>
                            </i>
                    </div>
                    <div class="c-header-icon shop">
                        <a data-org-url="<?php echo CommonHelper::generateUrl('Account', 'Messages', array(), '', null, false, $getOrgUrl); ?>" href="<?php echo CommonHelper::generateUrl('Account', 'Messages'); ?>" title="<?php echo Labels::getLabel('LBL_Messages', $siteLangId);?>">
                            <i class="icn"><svg class="svg">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#message" href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#message"></use>
                                </svg>
                            </i>
                            <?php if ($todayUnreadMessageCount > 0) { ?>
                            <span class="h-badge"><span class="heartbit"></span><?php echo ($todayUnreadMessageCount < 9) ? $todayUnreadMessageCount : '9+' ; ?></span></a>
                            <?php } ?>
                    </div>
                    <?php if ($isShopActive && $shop_id > 0) { ?>
                    <div class="c-header-icon messages">
                        <a data-org-url="<?php echo CommonHelper::generateUrl('Shops', 'view', array($shop_id), '', null, false, $getOrgUrl); ?>" title="<?php echo Labels::getLabel('LBL_View_Shop', $siteLangId);?>" target="_blank"
                            href="<?php echo CommonHelper::generateUrl('Shops', 'view', array($shop_id)); ?>">
                            <i class="icn"><svg class="svg">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#view-shop-header" href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#view-shop-header"></use>
                                </svg>
                            </i>
                    </div>
                    <?php } ?>
                    <div class="short-links">
                        <ul>
                            <?php $this->includeTemplate('_partial/headerLanguageArea.php'); ?>
                            <?php $this->includeTemplate('_partial/headerUserArea.php', array('isUserDashboard' => $isUserDashboard)); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
