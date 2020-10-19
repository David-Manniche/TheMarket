<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frmSearch->setFormTagAttribute('class', 'form');
$frmSearch->setFormTagAttribute('onsubmit', 'searchUrlRewritingProducts(this); return(false);');
$frmSearch->developerTags['colClassPrefix'] = 'col-md-';
$frmSearch->developerTags['fld_default_col'] = 4;

$keywordFld = $frmSearch->getField('keyword');
$keywordFld->setWrapperAttribute('class', 'col-lg-4');
$keywordFld->addFieldTagAttribute('placeholder', Labels::getLabel('LBL_Search_Product', $siteLangId));
$keywordFld->developerTags['col'] = 4;
$keywordFld->developerTags['noCaptionTag'] = true;

$submitFld = $frmSearch->getField('btn_submit');
$submitFld->setFieldTagAttribute('class', 'btn btn-brand btn-block');

$clearFld = $frmSearch->getField('btn_clear');
$clearFld->setFieldTagAttribute('onclick', 'clearSearch()');
$clearFld->setFieldTagAttribute('class', 'btn btn-outline-brand btn-block');
?>
<?php $this->includeTemplate('_partial/seller/sellerDashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header  row justify-content-between mb-3">
            <div class="col-md-auto">
                <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_URL_Rewriting', $siteLangId); ?></h2>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <?php echo $frmSearch->getFormTag(); ?>
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="field-set"><?php echo $frmSearch->getFieldHTML('keyword');?></div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="field-set"><?php echo $frmSearch->getFieldHTML('btn_submit'); ?></div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="field-set"><?php echo $frmSearch->getFieldHTML('btn_clear');?></div>
                                        </div>
                                    </div>
                                    <div class='dvFocus-js'></div>
                                </form>
                                <?php echo $frmSearch->getExternalJS(); ?>
                            </div>
                            <div id="listing">
                                <?php echo Labels::getLabel('LBL_Loading..', $siteLangId); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body h-100">
                            <div id="dvForm">
                             <div class="cards-message" role="alert">
                                    <div class="cards-message-icon"><i class="fas fa-exclamation-triangle"></i></div>
                                    <div class="cards-message-text"><?php echo Labels::getLabel('LBL_Select_a_product_to_update_url', $siteLangId); ?></div>
                                </div>
                            </div>                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
