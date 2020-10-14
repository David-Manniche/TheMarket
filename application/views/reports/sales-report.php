<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
    $frmSrch->setFormTagAttribute('onSubmit', 'searchSalesReport(this); return false;');
    $frmSrch->setFormTagAttribute('class', 'form');
    $frmSrch->developerTags['colClassPrefix'] = 'col-lg-2 col-md-';
    $frmSrch->developerTags['fld_default_col'] = 2;
?>

<?php $this->includeTemplate('_partial/seller/sellerDashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header row">
            <div class="col">
                <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Sales_Report', $siteLangId);?></h2>
            </div>
        </div>
        <div class="content-body">
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="cards">
                        <div class="cards-header">
                            <h5 class="cards-title"><?php echo Labels::getLabel('LBL_Sales_Report', $siteLangId);?></h5>
                            <div class="action"><?php echo '<a href="javascript:void(0)" onClick="exportSalesReport()" class="btn btn-secondary btn-block btn-sm">'.Labels::getLabel('LBL_Export', $siteLangId).'</a>'; ?></div>
                        </div>
                        <div class="cards-content">
                            <?php if (empty($orderDate)) { ?>
                                <div class="replaced">
                                    <?php
                                        $dateFrm = $frmSrch->getField('date_from');
                                        $dateFrm->developerTags['noCaptionTag'] = true;
                                    
                                        $dateTo = $frmSrch->getField('date_to');
                                        $dateTo->developerTags['noCaptionTag'] = true;

                                        $submitFld = $frmSrch->getField('btn_submit');
                                        $submitFld->developerTags['noCaptionTag'] = true;
                                        $submitFld->setFieldTagAttribute('class', 'btn btn-brand btn-block ');

                                        $fldClear = $frmSrch->getField('btn_clear');
                                        $fldClear->setFieldTagAttribute('class', 'btn btn-outline-primary btn-block');
                                        $fldClear->developerTags['noCaptionTag'] = true;
                                        echo $frmSrch->getFormHtml();
                                    ?>
                                </div>
                            <?php  } else {
                                echo  $frmSrch->getFormHtml();
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="cards">
                        <div class="cards-content">
                            <div class="listing-tbl" id="listingDiv"> <?php echo Labels::getLabel('LBL_Loading..', $siteLangId); ?> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
