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
                                <h4></h4>
                                <?php
                                    $ul = new HtmlElement("ul", array("class"=>"actions actions--centered"));
                                    $li = $ul->appendElement("li", array('class'=>'droplink'));
                                    $li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Labels::getLabel('LBL_Edit', $adminLangId)), '<i class="ion-android-more-horizontal icon"></i>', true);
                                    $innerDiv=$li->appendElement('div', array('class'=>'dropwrap'));
                                    $innerUl=$innerDiv->appendElement('ul', array('class'=>'linksvertical'));

                                    if (FatApp::getConfig('CONF_ENABLE_IMPORT_EXPORT', FatUtility::VAR_INT, 0) && $canEdit) {
                                        $innerLiExport=$innerUl->appendElement('li');
                                        $innerLiExport->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green','title'=>Labels::getLabel('LBL_Export', $adminLangId),"onclick"=>"addExportForm(".Importexport::TYPE_CATEGORIES.")"), Labels::getLabel('LBL_Export', $adminLangId), true);
                                    }
                                    if (FatApp::getConfig('CONF_ENABLE_IMPORT_EXPORT', FatUtility::VAR_INT, 0) && $canEdit) {
                                        $innerLiImport=$innerUl->appendElement('li');
                                        $innerLiImport->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green','title'=>Labels::getLabel('LBL_Import', $adminLangId),"onclick"=>"addImportForm(". Importexport::TYPE_CATEGORIES.")"), Labels::getLabel('LBL_Import', $adminLangId), true);
                                    }
                                    if ($canEdit) {
                                        $innerLiAddCat=$innerUl->appendElement('li');
                                        $innerLiAddCat->appendElement('a', array('href'=>commonHelper::generateUrl('ProductCategories', 'form'), 'class'=>'button small green redirect--js','title'=>Labels::getLabel('LBL_Add_Category', $adminLangId)), Labels::getLabel('LBL_Add_Category', $adminLangId), true);

                                        $innerLi=$innerUl->appendElement('li');
                                        $innerLi->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green d-none display-link-js','title'=>Labels::getLabel('LBL_Activate', $adminLangId),"onclick"=>"toggleBulkStatues(1)"), Labels::getLabel('LBL_Activate', $adminLangId), true);

                                        $innerLi=$innerUl->appendElement('li');
                                        $innerLi->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green d-none display-link-js','title'=>Labels::getLabel('LBL_Deactivate', $adminLangId),"onclick"=>"toggleBulkStatues(0)"), Labels::getLabel('LBL_Deactivate', $adminLangId), true);

                                        $innerLi=$innerUl->appendElement('li');
                                        $innerLi->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green d-none display-link-js','title'=>Labels::getLabel('LBL_Delete', $adminLangId),"onclick"=>"deleteSelected()"), Labels::getLabel('LBL_Delete', $adminLangId), true);
                                    }
                                     echo $ul->getHtml();?>
                            </div>
                            <div class="sectionbody">
                                <div class="tablewrap" >
                                    <div id="listing"> <?php echo Labels::getLabel('LBL_Processing...', $adminLangId); ?></div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
