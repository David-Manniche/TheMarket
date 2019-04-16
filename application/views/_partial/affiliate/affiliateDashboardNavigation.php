<?php
$controller = strtolower($controller);
$action = strtolower($action);
?>
<div class="sidebar no-print">
	<div class="logo-wrapper">
		<?php
		if( CommonHelper::isThemePreview() && isset($_SESSION['preview_theme'] ) ){
			$logoUrl = CommonHelper::generateUrl('home','index');
		}else{
			$logoUrl = CommonHelper::generateUrl();
		}
		?>
		<div class="logo-dashboard"><a href="<?php echo $logoUrl; ?>"><img src="<?php echo CommonHelper::generateFullUrl('Image','siteLogo',array($siteLangId), CONF_WEBROOT_FRONT_URL); ?>" alt="<?php echo FatApp::getConfig('CONF_WEBSITE_NAME_'.$siteLangId) ?>" title="<?php echo FatApp::getConfig('CONF_WEBSITE_NAME_'.$siteLangId) ?>"></a></div>
		<div class="js-hamburger hamburger-toggle"><span class="bar-top"></span><span class="bar-mid"></span><span class="bar-bot"></span></div>
	</div>
	<div class="sidebar__content custom-scrollbar">
		<nav class="dashboard-menu">
			<ul>
				<?php if(User::canViewAffiliateTab()) { ?>
					<?php if(FatApp::getConfig('CONF_FACEBOOK_APP_ID',FatUtility::VAR_STRING,'') && FatApp::getConfig('CONF_FACEBOOK_APP_SECRET',FatUtility::VAR_STRING,'')) { ?>
					<li class="menu__item">
						<div class="menu__item__inner"> <span class="menu-head"><?php echo Labels::getLabel("LBL_Sharing",$siteLangId); ?></span></div>
					</li>
					<li class="menu__item <?php echo ($controller == 'affiliate' && $action == 'sharing') ? 'is-active' : ''; ?>"><div class="menu__item__inner"><a title="<?php echo Labels::getLabel("LBL_Sharing",$siteLangId); ?>" href="<?php echo CommonHelper::generateUrl('Affiliate','sharing'); ?>">
					<i class="icn shop"><svg class="svg"><use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-share-earn" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-share-earn"></use></svg>
					</i><span class="menu-item__title"><?php echo Labels::getLabel("LBL_Sharing",$siteLangId); ?></span></a></div></li>
					<li class="divider"></li>
					<?php } ?>
				<?php }?>

				<?php if( User::canViewBuyerTab() ) { ?>
				<li class="menu__item">
					<div class="menu__item__inner"> <span class="menu-head"><?php echo Labels::getLabel("LBL_Orders",$siteLangId); ?></span></div>
				</li>
				<li class="menu__item <?php echo ($controller == 'buyer' && ($action == 'orders' || $action == 'vieworder')) ? 'is-active' : ''; ?>"><div class="menu__item__inner"><a title="<?php echo Labels::getLabel("LBL_My_Orders",$siteLangId); ?>" href="<?php echo CommonHelper::generateUrl('Buyer','Orders'); ?>">
				<i class="icn shop"><svg class="svg"><use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-order" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-order"></use></svg>
				</i><span class="menu-item__title"><?php echo Labels::getLabel("LBL_My_Orders",$siteLangId); ?></span></a></div></li>
				<li class="menu__item <?php echo ($controller == 'buyer' && $action == 'ordercancellationrequests') ? 'is-active' : ''; ?>"><div class="menu__item__inner"><a title="<?php echo Labels::getLabel("LBL_Order_Cancellation_Requests",$siteLangId); ?>" href="<?php echo CommonHelper::generateUrl('Buyer','orderCancellationRequests'); ?>">
				<i class="icn shop"><svg class="svg"><use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-cancellation-reques" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-cancellation-reques"></use></svg>
				</i><span class="menu-item__title"><?php echo Labels::getLabel('LBL_Order_Cancellation_Requests',$siteLangId);?></span></a></div></li>
				<li class="menu__item <?php echo ($controller == 'buyer' && ($action == 'orderreturnrequests' || $action == 'vieworderreturnrequest') ) ? 'is-active' : ''; ?>"><div class="menu__item__inner"><a href="<?php echo CommonHelper::generateUrl('Buyer','orderReturnRequests'); ?>">
				<i class="icn shop"><svg class="svg"><use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-return-request" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-return-request"></use></svg>
				</i><span class="menu-item__title"><?php echo Labels::getLabel('LBL_Order_Return_Requests',$siteLangId);?></span></a></div></li>
				<li class="divider"></li>

				<li class="menu__item">
					<div class="menu__item__inner"> <span class="menu-head"><?php echo Labels::getLabel("LBL_Addresses",$siteLangId); ?></span></div>
				</li>
				<li class="menu__item <?php echo ($controller == 'buyer' && $action == 'myaddresses') ? 'is-active' : ''; ?>"><div class="menu__item__inner"><a title="<?php echo Labels::getLabel("LBL_My_Addresses",$siteLangId); ?>" href="<?php echo CommonHelper::generateUrl('Buyer','myAddresses'); ?>">
				<i class="icn shop"><svg class="svg"><use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-my-address" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-my-address"></use></svg>
				</i><span class="menu-item__title"><?php echo Labels::getLabel("LBL_My_Addresses",$siteLangId); ?></span></a></div></li>
				<li class="divider"></li>

				<li class="menu__item">
					<div class="menu__item__inner"> <span class="menu-head"><?php echo Labels::getLabel("LBL_Rewards",$siteLangId); ?></span></div>
				</li>
				<li class="menu__item <?php echo ($controller == 'buyer' && $action == 'rewardPoints') ? 'is-active' : ''; ?>"><div class="menu__item__inner"><a title="<?php echo Labels::getLabel("LBL_Reward_Points",$siteLangId); ?>" href="<?php echo CommonHelper::generateUrl('Buyer','rewardPoints'); ?>">
				<i class="icn shop"><svg class="svg"><use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-reward-points" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-reward-points"></use></svg>
				</i><span class="menu-item__title"><?php echo Labels::getLabel("LBL_Reward_Points",$siteLangId); ?></span></a></div></li>
				<?php if(FatApp::getConfig('CONF_ENABLE_REFERRER_MODULE')){?>
				<li class="menu__item <?php echo ($controller == 'buyer' && $action == 'shareEarn') ? 'is-active' : ''; ?>"><div class="menu__item__inner"><a title="<?php echo Labels::getLabel("LBL_Share_and_Earn",$siteLangId); ?>" href="<?php echo CommonHelper::generateUrl('Buyer','shareEarn'); ?>">
				<i class="icn shop"><svg class="svg"><use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-share-earn" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-share-earn"></use></svg>
				</i><span class="menu-item__title"><?php echo Labels::getLabel("LBL_Share_and_Earn",$siteLangId); ?></span></a></div></li>
				<?php } ?>
				<li class="menu__item <?php echo ($controller == 'buyer' && $action == 'offers') ? 'is-active' : ''; ?>"><div class="menu__item__inner"><a title="<?php echo Labels::getLabel("LBL_My_Offers",$siteLangId); ?>" href="<?php echo CommonHelper::generateUrl('Buyer','offers'); ?>">
				<i class="icn shop"><svg class="svg"><use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-offers" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-offers"></use></svg>
				</i><span class="menu-item__title"><?php echo Labels::getLabel("LBL_My_Offers",$siteLangId); ?></span></a></div></li>
				<li class="divider"></li>
				<?php } ?>

				<li class="menu__item">
					<div class="menu__item__inner"> <span class="menu-head"><?php echo Labels::getLabel('LBL_Profile',$siteLangId);?></span></div>
				</li>
				<li class="menu__item <?php echo ($controller == 'account' && $action == 'profileinfo') ? 'is-active' : ''; ?>"><div class="menu__item__inner"><a title="<?php echo Labels::getLabel("LBL_My_Account",$siteLangId); ?>" href="<?php echo CommonHelper::generateUrl('Account','ProfileInfo'); ?>">
				<i class="icn shop"><svg class="svg"><use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-account" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-account"></use></svg>
				</i><span class="menu-item__title"><?php echo Labels::getLabel("LBL_My_Account",$siteLangId); ?></span></a></div></li>
				<?php if( !User::canViewAffiliateTab() ) { ?>
				<li class="menu__item <?php echo ($controller == 'account' && $action == 'messages') ? 'is-active' : ''; ?>"><div class="menu__item__inner"><a title="<?php echo Labels::getLabel("LBL_Messages",$siteLangId); ?>" href="<?php echo CommonHelper::generateUrl('Account','Messages'); ?>">
				<i class="icn shop"><svg class="svg"><use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-messages" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svgdash-messages"></use></svg>
				</i><span class="menu-item__title"><?php echo Labels::getLabel("LBL_Messages",$siteLangId); ?></span></a></div></li>
				<?php } ?>
				<li class="menu__item <?php echo ($controller == 'account' && $action == 'credits') ? 'is-active' : ''; ?>"><div class="menu__item__inner"><a title="<?php echo Labels::getLabel("LBL_My_Credits",$siteLangId); ?>" href="<?php echo CommonHelper::generateUrl('Account','credits');?>">
				<i class="icn shop"><svg class="svg"><use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-credits" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-credits"></use></svg>
				</i><span class="menu-item__title"><?php echo Labels::getLabel('LBL_My_Credits',$siteLangId);?></span></a></div></li>
				<?php if( !User::canViewAffiliateTab() ) { ?>
				<li class="menu__item <?php echo ($controller == 'account' && $action == 'wishlist') ? 'is-active' : ''; ?>"><div class="menu__item__inner"><a title="<?php echo Labels::getLabel("LBL_Wishlist/Favorites",$siteLangId); ?>" href="<?php echo CommonHelper::generateUrl('Account','wishlist');?>">
				<i class="icn shop"><svg class="svg"><use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-wishlist-favorite" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-wishlist-favorite"></use></svg>
				</i><span class="menu-item__title"><?php echo Labels::getLabel('LBL_Wishlist/Favorites',$siteLangId);?></span></a></div></li>
				<?php } ?>
				<li class="menu__item <?php echo ($controller == 'account' && $action == 'changepassword') ? 'is-active' : ''; ?>"><div class="menu__item__inner"><a title="<?php echo Labels::getLabel("LBL_Change_Password",$siteLangId); ?>" href="<?php echo CommonHelper::generateUrl('Account','changePassword');?>">
				<i class="icn shop"><svg class="svg"><use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-change-password" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-change-password"></use></svg>
				</i><span class="menu-item__title"><?php echo Labels::getLabel('LBL_Change_Password',$siteLangId);?></span></a></div></li>
				<li class="menu__item <?php echo ($controller == 'account' && $action == 'changeemail') ? 'is-active' : ''; ?>"><div class="menu__item__inner"><a title="<?php echo Labels::getLabel("LBL_Change_Email",$siteLangId); ?>" href="<?php echo CommonHelper::generateUrl('Account','changeEmail');?>">
				<i class="icn shop"><svg class="svg"><use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-change-email" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#dash-change-email"></use></svg>
				</i><span class="menu-item__title"><?php echo Labels::getLabel('LBL_Change_Email',$siteLangId);?></span></a></div></li>
				<li class="divider"></li>
			</ul>
		</nav>
	</div>
<script>

var Dashboard = function () {

	var menuChangeActive = function menuChangeActive(el) {
		var hasSubmenu = $(el).hasClass("has-submenu");
		$(global.menuClass + " .is-active").removeClass("is-active");
		$(el).addClass("is-active");


	};

	var sidebarChangeWidth = function sidebarChangeWidth() {
		var $menuItemsTitle = $("li .menu-item__title");

		$("body").toggleClass("sidebar-is-reduced sidebar-is-expanded");
		$(".hamburger-toggle").toggleClass("is-opened");



	};

	return {
		init: function init() {
			$(".js-hamburger").on("click", sidebarChangeWidth);

			$(".js-menu li").on("click", function (e) {
				menuChangeActive(e.currentTarget);
			});


		}
	};

}();

Dashboard.init();
</script>
</div>
