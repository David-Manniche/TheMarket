<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<ul class=" list-group list-group-flush">
    <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo Labels::getLabel('LBL_Categories', $adminLangId); ?> <span class="badge badge-secondary badge-pill"><?php echo $activeCategories['categories_count'] + $inactiveCategories['categories_count'] ; ?></span></li>
    <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo Labels::getLabel('LBL_Products', $adminLangId); ?> <span class="badge badge-secondary badge-pill"><?php echo $totProds['total_products']; ?></span></li>
    <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo Labels::getLabel('LBL_Active_Categories', $adminLangId); ?> <span class="badge badge-secondary badge-pill"><?php echo $activeCategories['categories_count']; ?></span></li>
    <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo Labels::getLabel('LBL_Disabled_Categories', $adminLangId); ?> <span class="badge badge-secondary badge-pill"><?php echo $inactiveCategories['categories_count']; ?></span></li>
</ul>