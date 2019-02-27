<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $this->includeTemplate('_partial/dashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
 <div class="content-wrapper content-space">
	<div class="row justify-content-between mb-3">
		<div class="col-md-auto">
			<?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
			<h2 class="content-header-title"><?php echo Labels::getLabel('LBL_My_Addresses',$siteLangId);?></h2>
		</div>
	</div>
	<div class="content-body">
		<div class="cards">
			<div class="cards-header p-3">
				<h5 class="cards-title"><?php echo Labels::getLabel('LBL_My_Addresses',$siteLangId);?></h5>
			</div>
			<div class="cards-content p-3">
				<div class="col-md-12" id="listing">
					<?php echo Labels::getLabel('LBL_Loading..',$siteLangId); ?>
				</div>
			</div>
		</div>
	</div>
  </div>
</main>