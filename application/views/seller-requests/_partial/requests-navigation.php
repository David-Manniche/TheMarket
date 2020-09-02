<?php  defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="tabs">                    
	<ul class="tabs_nav-js">
		<?php if (FatApp::getConfig('CONF_SELLER_CAN_REQUEST_CUSTOM_PRODUCT', FatUtility::VAR_INT, 0)) { ?>
			<li class="<?php echo !empty($action) && $action == 'searchCustomCatalogProducts' ? 'is-active' : '';?>">
				<a class="tabs_001" rel="tabs_001" href="javascript:void(0)" onClick="searchCustomCatalogProducts()">
				<?php echo Labels::getLabel('LBL_Products_Requests', $siteLangId); ?> <i class="fa fa-question-circle" onClick="productInstructions(<?php echo Extrapage::PRODUCT_REQUEST_INSTRUCTIONS; ?>)"></i></a>
			</li>
		<?php } ?>
		<li class="<?php echo !empty($action) && $action == 'searchBrandRequests' ? 'is-active' : '';?>">
			<a class="tabs_002" rel="tabs_002" href="javascript:void(0)" onClick="searchBrandRequests()">
			 <?php echo Labels::getLabel('LBL_Brand_Requests', $siteLangId); ?></a>
		</li>
		<li class="<?php echo !empty($action) && $action == 'searchProdCategoryRequests' ? 'is-active' : '';?>">
			<a class="tabs_003" rel="tabs_003" href="javascript:void(0)" onClick="searchProdCategoryRequests()">
			 <?php echo Labels::getLabel('LBL_Category_Requests', $siteLangId); ?></a>
		</li>
	</ul>
</div>