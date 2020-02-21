<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frmSearch->setFormTagAttribute('class', 'form');
$frmSearch->setFormTagAttribute('onsubmit', 'searchCatalogProducts(this); return(false);');
$frmSearch->developerTags['colClassPrefix'] = 'col-md-';
$frmSearch->developerTags['fld_default_col'] = 4;

$keywordFld = $frmSearch->getField('keyword');
$keywordFld->setWrapperAttribute('class', 'col-lg-4');
$keywordFld->addFieldTagAttribute('placeholder', Labels::getLabel('LBL_Search_Products', $siteLangId));
$keywordFld->developerTags['col'] = 4;
$keywordFld->developerTags['noCaptionTag'] = true;

$clearFld = $frmSearch->getField('btn_clear');
$clearFld->setFieldTagAttribute('onclick', 'clearSearch()');
?>
<?php $this->includeTemplate('_partial/seller/sellerDashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header  row justify-content-between mb-3">
            <div class="col-md-auto">
                <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Product_Tags', $siteLangId); ?><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="<?php echo Labels::getLabel('LBL_Tags_can_only_be_added_for_private_products', $siteLangId); ?>"></i></h2>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="cards">
                        <div class="cards-content">
                            <div>
                                <?php echo $frmSearch->getFormTag(); ?>
                                <div class="field-set">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <?php echo $frmSearch->getFieldHTML('keyword');?>
                                        </div>
                                        <div class="col-md-2">
                                            <?php 
                                                echo $frmSearch->getFieldHTML('btn_submit');
                                                echo $frmSearch->getFieldHTML('btn_clear');
                                            ?>
                                        </div>
                                    </div>
                                    <div class='dvFocus-js'></div>
                                </div>
                                </form>
                                <?php echo $frmSearch->getExternalJS(); ?>
                            </div>
                            <div id="listing">
                                <?php echo Labels::getLabel('LBL_Loading..', $siteLangId); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
