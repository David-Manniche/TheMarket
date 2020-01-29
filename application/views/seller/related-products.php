<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$this->includeTemplate('_partial/seller/sellerDashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header  row justify-content-between mb-3">
            <div class="col-md-auto">
                <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Related_Products', $siteLangId); ?></h2>
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

                            $relProdFld = $relProdFrm->getField('products_related');
                            $relProdFld->setFieldTagAttribute('placeholder', Labels::getLabel('LBL_Add_Related_Products', $siteLangId));

                            $submitBtnFld = $relProdFrm->getField('btn_submit');
                            $submitBtnFld->setFieldTagAttribute('class', 'btn--block btn btn--primary'); ?>
                            <?php echo $relProdFrm->getFormTag(); ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="field-set">
                                        <div class="field-wraper">
                                            <div class="field_cover">
                                                <?php echo $relProdFrm->getFieldHTML('product_name');?>
                                                <div class='dvFocus-js form-control focused-div'></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="field-set">
                                        <div class="field-wraper">
                                            <div class="field_cover custom-tagify">
                                                <?php echo $relProdFrm->getFieldHTML('products_related');?>
                                                <div class="list-tag-wrapper"><ul class="list-tags" id="related-products"></ul></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
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
