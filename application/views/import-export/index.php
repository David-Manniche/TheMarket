<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $this->includeTemplate('_partial/dashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
 	<div class="content-wrapper content-space">
 		<div class="content-header row justify-content-between mb-3">
 			<div class="col-md-auto">
 				<?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
 				<h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Import_Export',$siteLangId);?></h2>
 			</div>
 		</div>
 		<div class="content-body">
 			<div class="cards">
 				<div class="cards-header p-3">
 					<h5 class="cards-title"><?php echo Labels::getLabel('LBL_Import_Export',$siteLangId);?></h5>
                    <div class="note-messages"><strong><?php echo Labels::getLabel('LBL_User_ID',$siteLangId); ?>: <?php echo UserAuthentication::getLoggedUserId(); ?></strong></div>
                </div>
 				<div class="cards-content p-3">
					<div id="importExportBlock">
						<?php echo Labels::getLabel('LBL_Loading..',$siteLangId); ?>
					</div>
 				</div>
 			</div>
 		</div>
 	</div>
</main>
