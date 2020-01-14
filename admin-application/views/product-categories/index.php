<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class='page'>
    <div class='container container-fluid'>
        <div class="row">
            <div class="col-lg-12 col-md-12 space">
                <div class="page__title">
                    <div class="row">
                        <div class="col--first col-lg-6">
                            <span class="page__icon"><i class="ion-android-star"></i></span>
                            <h5><?php echo Labels::getLabel('LBL_Categories', $adminLangId); ?> </h5>
                            <?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <section class="section">
                            <div class="sectionhead">
                                <?php if ($canEdit) { ?>
                                        <a href="<?php echo commonHelper::generateUrl('ProductCategories', 'form'); ?>" title="<?php echo  Labels::getLabel('LBL_Add_Category', $adminLangId); ?>" class="btn btn-clean btn-sm btn-icon"><i class="fas fa-plus clickable"></i></a>
                                <?php }?>
                            </div>
                            <div class="sectionbody space">
                                <div class="accordion-categories" id="listing" >
                                    <?php echo Labels::getLabel('LBL_Processing...', $adminLangId); ?>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-md-3">
                        <section class="section">
                            <div class="sectionhead">
                                <h4><?php echo Labels::getLabel('LBL_Total_', $adminLangId); ?></h4>
                            </div>
                            <div class="sectionbody">
                                <ul class=" list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo Labels::getLabel('LBL_Categories', $adminLangId); ?> <span class="badge badge-secondary badge-pill"><?php echo $activeCategories['categories_count'] + $inactiveCategories['categories_count'] ; ?></span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo Labels::getLabel('LBL_Products', $adminLangId); ?> <span class="badge badge-secondary badge-pill"><?php echo $totProds['total_products']; ?></span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo Labels::getLabel('LBL_Active_Categories', $adminLangId); ?> <span class="badge badge-secondary badge-pill"><?php echo $activeCategories['categories_count']; ?></span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo Labels::getLabel('LBL_Disabled_Categories', $adminLangId); ?> <span class="badge badge-secondary badge-pill"><?php echo $inactiveCategories['categories_count']; ?></span></li>
                                </ul>
                            </div>
                        </section>
                        <section class="section">
                             <div class="sectionhead">
                                 <h4><?php echo  Labels::getLabel('LBL_Category_Message_Title', $adminLangId); ?></h4>
                             </div>
                             <div class="sectionbody space ">
                                 <div class="note">
                                     <div class="note-icon">
                                       <i class="fas fa-info-circle"></i>
                                     </div>
                                     <div class="note-text"><?php echo  Labels::getLabel('LBL_Category_Message_Info', $adminLangId); ?></div>
                                 </div>


                             </div>
                         </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
