<div class="wrapper">
	<header id="header-dashboard" class="header-dashboard no-print" role="header-dashboard">
		<?php  /* if((User::canViewSupplierTab() && User::canViewBuyerTab()) || (User::canViewSupplierTab() && User::canViewAdvertiserTab()) || (User::canViewBuyerTab() && User::canViewAdvertiserTab())){ ?>
		<div class="dropdown dropdown--arrow user-type">
			<a href="javascript:void(0)" class="dropdown__trigger dropdown__trigger-js">
				<span><?php if($activeTab == 'S') echo Labels::getLabel('Lbl_Seller',$siteLangId); elseif($activeTab == 'B') echo Labels::getLabel('Lbl_Buyer',$siteLangId); else echo Labels::getLabel('Lbl_Advertiser',$siteLangId); ?></span>
				<i class="icn icn--language">
					<svg class="svg">
						<use xlink:href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#chevron-down" href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#chevron-down"></use>
					</svg>
				</i>
			</a>
			<div class="dropdown__target dropdown__target-lang dropdown__target-js">
				<div class="dropdown__target-space">
					<span class="expand-heading">Select Language</span>
					<ul class="list-vertical list-vertical--tick">
						<?php if( User::canViewSupplierTab() ){ ?>
						<li <?php if($activeTab == 'S'){ echo 'class="is-active"';}?>><a href="<?php echo CommonHelper::generateUrl('Seller'); ?>"><?php echo Labels::getLabel('Lbl_Seller',$siteLangId);?></a></li>
						<?php }?>
						<?php if( User::canViewBuyerTab() ){ ?>
						<li <?php if($activeTab == 'B'){ echo 'class="is-active"';}?>><a href="<?php echo CommonHelper::generateUrl('Buyer'); ?>"><?php echo Labels::getLabel('Lbl_Buyer',$siteLangId);?></a></li>
						<?php }?>
						<?php if( User::canViewAdvertiserTab() ){ ?>
						<li <?php if($activeTab == 'Ad'){ echo 'class="is-active"';}?>><a href="<?php echo CommonHelper::generateUrl('Advertiser'); ?>"><?php echo Labels::getLabel('Lbl_Advertiser',$siteLangId);?></a></li>
						<?php }?>
					</ul>
				</div>
			</div>
		</div>
	<?php } */ ?>
		<div class="header-icons-group">
			<div class="c-header-icon messages">
                <?php $getOrgUrl = (CONF_DEVELOPMENT_MODE) ? true : false; ?>
				<a data-org-url="<?php echo CommonHelper::generateUrl('Account','Messages',array(),'',null,false,$getOrgUrl); ?>" href="<?php echo CommonHelper::generateUrl('Account','Messages'); ?>">
					<i class="icn"><svg class="svg">
							<use xlink:href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#message" href="<?php echo CONF_WEBROOT_URL;?>images/retina/sprite.svg#message"></use>
						</svg>
					</i>
					<?php if($todayUnreadMessageCount > 0) { ?>
					<span class="h-badge"><span class="heartbit"></span><?php echo ($todayUnreadMessageCount < 9) ? $todayUnreadMessageCount : '9+' ; ?></span></a>
					<?php } ?>
			</div>
			<div class="short-links">
				<ul>
				<?php $this->includeTemplate('_partial/headerLanguageArea.php'); ?>
				<?php $this->includeTemplate('_partial/headerUserArea.php',array('isUserDashboard'=>$isUserDashboard)); ?>
				</ul>
			</div>
		</div>
	</header>
