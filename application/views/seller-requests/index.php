<?php  defined('SYSTEM_INIT') or die('Invalid Usage.');
$this->includeTemplate('_partial/seller/sellerDashboardNavigation.php');
?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header row ">
            <div class="col">
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Seller_Requests', $siteLangId); ?>
                </h2>
			</div>
			<?php if ($canEdit) { ?>
			<div class="col-auto">
				<div class="dropdown dashboard-user">
				  <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dashboardDropdown" data-toggle="dropdown"  data-display="static"  aria-haspopup="true" aria-expanded="false" >
					<?php echo Labels::getLabel('LBL_New_Request', $siteLangId); ?>
				  </button>
				  <div class="dropdown-menu dropdown-menu-fit dropdown-menu-anim" aria-labelledby="dashboardDropdown">
				  <ul class="nav nav-block">
						<li class="nav__item">
							<a class="dropdown-item nav__link" href="<?php echo UrlHelper::generateUrl('Seller', 'customCatalogProductForm'); ?>"><?php echo Labels::getLabel('LBL_Product', $siteLangId);?></a>
						</li>
						<li class="nav__item">
							<a class="dropdown-item nav__link" href="javascript:void(0);" onClick="addBrandReqForm(0)"><?php echo Labels::getLabel('LBL_Brand', $siteLangId);?></a>
						</li>
						<li class="nav__item">
							<a class="dropdown-item nav__link" href="javascript:void(0);" onClick="addCategoryReqForm(0)"><?php echo Labels::getLabel('LBL_Category', $siteLangId);?></a>
						</li>
					</ul>
				  </div>
				</div>
            </div>
			<?php } ?>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="cards">
                        <div class="cards-content">
                            <div id="listing"> <?php echo Labels::getLabel('LBL_Processing...', $siteLangId); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
	var ratioTypeSquare = <?php echo AttachedFile::RATIO_TYPE_SQUARE; ?>;
	var ratioTypeRectangular = <?php echo AttachedFile::RATIO_TYPE_RECTANGULAR; ?>;
</script>

