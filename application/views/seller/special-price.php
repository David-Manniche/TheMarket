<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
    $frmSearch->setFormTagAttribute('class', 'form');
    $frmSearch->setFormTagAttribute('onsubmit', 'searchSpecialPriceProducts(this); return(false);');
    $frmSearch->developerTags['colClassPrefix'] = 'col-md-';
    //$frmSearch->developerTags['fld_default_col'] = 8;

    $keywordFld = $frmSearch->getField('keyword');
    $keywordFld->developerTags['col'] = 8;
    $keywordFld->developerTags['noCaptionTag'] = true;

	if (0 < $selProd_id) {
		$keywordFld->setFieldTagAttribute('readonly', 'readonly');
	}
    $submitBtnFld = $frmSearch->getField('btn_submit');
    $submitBtnFld->setFieldTagAttribute('class', 'btn--block btn btn--primary');
    $submitBtnFld->setWrapperAttribute('class', (0 < $selProd_id ? ' d-none' : ''));
    $submitBtnFld->setWrapperAttribute('class', 'col-6');
    $submitBtnFld->developerTags['col'] = 2;
    $submitBtnFld->developerTags['noCaptionTag'] = true;

    $cancelBtnFld = $frmSearch->getField('btn_clear');
    $cancelBtnFld->setFieldTagAttribute('onclick', 'clearSearch(' . $selProd_id . ');');
    $cancelBtnFld->setFieldTagAttribute('class', 'btn--block btn btn-outline-primary');
    $cancelBtnFld->setWrapperAttribute('class', 'col-6');
    $cancelBtnFld->developerTags['col'] = 2;
    $cancelBtnFld->developerTags['noCaptionTag'] = true;
?>
<?php $this->includeTemplate('_partial/seller/sellerDashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header row">
            <div class="col">
                <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Seller_Products_Special_Price_List', $siteLangId); ?></h2>
            </div>
        </div>
        <div class="content-body">
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="cards">
                        <div class="cards-content">
                            <div class="replaced">
                                <?php echo $frmSearch->getFormHtml(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="cards">
                        <?php
						if($canEdit){
							foreach ($dataToEdit as $data) {
								$data['addMultiple'] = (1 > $selProd_id) ? 1 : 0;
								$this->includeTemplate('seller/add-special-price-form.php', array('siteLangId' => $siteLangId, 'data' => $data), false);
							}
							if (1 > $selProd_id) {
								$this->includeTemplate('seller/add-special-price-form.php', array('siteLangId' => $siteLangId), false);
							}
						}                        
                        ?>
                        <div class="cards-content">
                            <div class="row justify-content-between">
                                <div class="col-auto"></div>
                                 <div class="col-auto">
                                    <div class="btn-group">
                                        <a class="btn btn--primary btn--sm formActionBtn-js formActions-css" title="<?php echo Labels::getLabel('LBL_Delete_Special_Price', $siteLangId); ?>" onclick="deleteSpecialPriceRows()" href="javascript:void(0)">							
										<i class="fa fa-trash"></i>
										</a>
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
