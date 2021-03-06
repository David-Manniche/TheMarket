<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $this->includeTemplate('_partial/dashboardNavigation.php'); ?>  
<main id="main-area" class="main"   >
	<div class="content-wrapper content-space">
		<div class="content-header row">
			<div class="col-md-auto">
				<?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
				<h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Requested_Catalog',$siteLangId); ?></h2>
			</div>
		</div>
		<div class="content-body">
			<div class="card">
				<div class="card-body ">
					<div id="listing">
						<?php echo Labels::getLabel('LBL_Loading..',$siteLangId); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>