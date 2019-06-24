<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$this->includeTemplate('_partial/dashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header  row justify-content-between mb-3">
            <div class="col-md-auto">
                <h2 class="content-header-title"><?php echo Labels::getLabel('Lbl_Update_Products_Inventory', $siteLangId);?></h2>
            </div>
        </div>
        <div class="content-body">
            <div class="cards">
                <div class="cards-content pl-4 pr-4 pb-4">
                    <div class="box__body">
                        <div class="tabs__content">
                            <div class="col-md-12">
                                <span class="gap"></span>
                                <div id="productInventory"> <?php echo Labels::getLabel('LBL_Loading..', $siteLangId); ?> </div>
                                <span class="gap"></span>
                            </div>
                            <div class="col-md-12">
                                <?php if (!empty($pageData['epage_content'])) { ?>
                                    <h2><?php echo $pageData['epage_label']; ?></h2>
                                    <?php echo FatUtility::decodeHtmlEntities($pageData['epage_content']);
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
