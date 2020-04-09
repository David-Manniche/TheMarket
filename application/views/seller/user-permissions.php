<?php $this->includeTemplate('_partial/seller/sellerDashboardNavigation.php');
$allAccessfrm->setFormTagAttribute('class', 'form');
$allAccessfrm->developerTags['colClassPrefix'] = 'col-md-';
$allAccessfrm->developerTags['fld_default_col'] = 4;
?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header row">
            <div class="col">
                <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Manage_Permissions_for', $siteLangId); ?> <?php echo $userData['user_name'];?></h2>
                <?php echo $frm->getFormHtml();?>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="cards">
                        <div class="cards-content">
                            <?php echo $allAccessfrm->getFormHtml(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="listing">
                <?php echo Labels::getLabel('LBL_Loading..', $siteLangId); ?>
            </div>
        </div>
    </div>
</main>
