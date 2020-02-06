<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $this->includeTemplate('_partial/dashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header row">
            <div class="col">
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Social_Platforms', $siteLangId);?></h2>
            </div>
            <div class="col-auto">
               
                    <div class="btn-group">
                        <a href="javascript:void(0)" class="btn btn--primary btn--sm btn-back d-none" onclick="reloadList()"><?php echo Labels::getLabel('LBL_Back', $siteLangId);?></a>
                        <a href="javascript:void(0)" class="btn btn--primary btn--sm" onclick="addForm(0)"><?php echo Labels::getLabel('LBL_Add_Social_Platform', $siteLangId);?></a>
                    </div>
                
            </div>
        </div>
        <div class="content-body">
            <div class="cards">
                <div id="listing"><?php echo Labels::getLabel('LBL_Loading..', $siteLangId); ?></div>
            </div>
        </div>
    </div>
</main>
