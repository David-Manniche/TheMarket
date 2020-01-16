<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$frmSearch->setFormTagAttribute('class', 'form');
$frmSearch->setFormTagAttribute('onsubmit', 'searchUpsellProducts(this); return(false);');
$frmSearch->developerTags['colClassPrefix'] = 'col-md-';
$frmSearch->developerTags['fld_default_col'] = 4;

$keywordFld = $frmSearch->getField('keyword');
if (0 < $selProd_id) {
    $keywordFld->setFieldTagAttribute('readonly', 'readonly');
}
$keywordFld->setWrapperAttribute('class', 'col-lg-4');
$keywordFld->addFieldTagAttribute('placeholder', Labels::getLabel('LBL_Search_by_keyword', $siteLangId));
$keywordFld->developerTags['col'] = 4;
$keywordFld->developerTags['noCaptionTag'] = true;

$submitBtnFld = $frmSearch->getField('btn_submit');
$submitBtnFld->setFieldTagAttribute('class', 'btn--block btn btn--primary');
$submitBtnFld->setWrapperAttribute('class', (0 < $selProd_id ? 'd-none' : ''));
$submitBtnFld->developerTags['col'] = 2;
$submitBtnFld->developerTags['noCaptionTag'] = true;

$cancelBtnFld = $frmSearch->getField('btn_clear');
$cancelBtnFld->setFieldTagAttribute('class', 'btn--block btn btn--primary-border ');
$cancelBtnFld->setFieldTagAttribute('onclick', 'clearSearch('.$selProd_id.');');
$cancelBtnFld->developerTags['col'] = 2;
$cancelBtnFld->developerTags['noCaptionTag'] = true;
?>
<?php $this->includeTemplate('_partial/seller/sellerDashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header  row justify-content-between mb-3">
            <div class="col-md-auto">
                <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Buy_Together_Products', $siteLangId); ?></h2>
            </div>
        </div>
        <div class="content-body">
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="cards">
                        <div class="cards-content pt-4 pl-4 pr-4 pb-0">
                            <?php $relProdFrm->setFormTagAttribute('onsubmit', 'setUpSellerProductLinks(this); return(false);');
                            $relProdFrm->setFormTagAttribute('class', 'form form--horizontal');
                            $prodFld = $relProdFrm->getField('product_name');
                            $prodFld->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Select_Product', $siteLangId));

                            $relProdFld = $relProdFrm->getField('products_upsell');
                            $relProdFld->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Add_Buy_Together_Products', $siteLangId));

                            $submitBtnFld = $relProdFrm->getField('btn_submit'); ?>
                            <?php echo $relProdFrm->getFormTag(); ?>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="field-set">
                                        <div class="field-wraper">
                                            <div class="field_cover">
                                                <?php echo $relProdFrm->getFieldHTML('product_name');?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field-set">
                                        <div class="field-wraper">
                                            <div class="field_cover custom-tagify">
                                                <?php echo $relProdFrm->getFieldHTML('products_upsell');?>
                                                <ul class="list-tags" id="upsell-products"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="field-set">
                                        <div class="field-wraper">
                                            <div class="field_cover">
                                                <?php echo $relProdFrm->getFieldHTML('btn_submit');?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo $relProdFrm->getFieldHTML('selprod_id'); ?>
                            </form>
                            <?php echo $relProdFrm->getExternalJS();?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="cards">
                        <div class="cards-content pt-2 pl-4 pr-4 pb-4">
                            <div class="row justify-content-between">
                                <div class="col-auto"></div>
                                <div class="col-auto">
                                    <div class="action">
                                        <a class="btn btn--primary-border btn--sm formActionBtn-js formActions-css" title="<?php echo Labels::getLabel('LBL_Remove_Volume_Discount', $siteLangId); ?>" onclick="deleteVolumeDiscountRows()"
                                            href="javascript:void(0)"><?php echo Labels::getLabel('LBL_Remove_Volume_Discount', $siteLangId); ?></a>
                                    </div>
                                </div>
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
