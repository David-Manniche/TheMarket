<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frmSearch->setFormTagAttribute('class', 'form');
$frmSearch->setFormTagAttribute('onsubmit', 'searchSeoProducts(this); return(false);');
$frmSearch->developerTags['colClassPrefix'] = 'col-md-';
$frmSearch->developerTags['fld_default_col'] = 4;

$keywordFld = $frmSearch->getField('keyword');
$keywordFld->setWrapperAttribute('class', 'col-lg-4');
$keywordFld->addFieldTagAttribute('placeholder', Labels::getLabel('LBL_Search_Product', $siteLangId));
$keywordFld->developerTags['col'] = 4;
$keywordFld->developerTags['noCaptionTag'] = true;
?>
<?php $this->includeTemplate('_partial/seller/sellerDashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header row">
            <div class="col">
                <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Meta_Tags', $siteLangId); ?></h2>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="cards">
                        <div class="cards-content p-4">
                            <div>
                                <?php echo $frmSearch->getFormTag(); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="field-set">
                                            <div class="field-wraper">
                                                <div class="field_cover">
                                                    <?php echo $frmSearch->getFieldHTML('keyword');?>
                                                    <div class='dvFocus-js'></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                                <?php echo $frmSearch->getExternalJS();?>
                            </div>
                            <div id="listing">
                                <?php echo Labels::getLabel('LBL_Loading..', $siteLangId); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="cards">
                        <div class="cards-content p-4">
                            <div id="dvForm"></div>
                            <div id="dvAlert">
                                <div class="cards-message" role="alert">
                                    <div class="cards-message-icon"><i class="fas fa-exclamation-triangle"></i></div>
                                    <div class="cards-message-text"><?php echo Labels::getLabel('LBL_Select_a_product_to_add_/_edit_meta_tags_data', $siteLangId); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
