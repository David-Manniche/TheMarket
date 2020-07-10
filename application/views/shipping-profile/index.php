<?php  defined('SYSTEM_INIT') or die('Invalid Usage.');
$this->includeTemplate('_partial/seller/sellerDashboardNavigation.php'); 
$searchFrm->setFormTagAttribute('onSubmit', 'searchProfile(this); return false;');
$searchFrm->setFormTagAttribute('class', 'form ');
$searchFrm->developerTags['colClassPrefix'] = 'col-md-';
$searchFrm->developerTags['fld_default_col'] = 6;
?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header row justify-content-between mb-3">
            <div class="col-md-auto">
                <h2 class="cards-title"><?php echo Labels::getLabel('LBL_Shipping_Profiles', $siteLangId);?>
                </h2>
            </div>
            <div class="col-auto">
                <div class="content-header-right">
                    <a href="<?php echo CommonHelper::generateUrl('shippingProfile', 'form',[0]);?>"
                        class="btn btn-outline-primary btn--sm"><?php echo Labels::getLabel('LBL_Create_Profile', $siteLangId);?></a>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="cards">
                        <div class="cards-content">
                            <div class="replaced">

                                <?php
                                $submitFld = $searchFrm->getField('btn_submit');
                                $submitFld->setFieldTagAttribute('class', 'btn btn-primary');
								$submitFld->developerTags['col'] = 3;

                                $fldClear= $searchFrm->getField('btn_clear');
                                $fldClear->setFieldTagAttribute('class', 'btn  btn-primary ml-2');
                                $fldClear->setFieldTagAttribute('onclick', 'clearSearch()');
								$submitFld->developerTags['col'] = 3;
                                echo $searchFrm->getFormHtml();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="cards">
                        <div class="cards-content">
                            <div id="profilesListing">
                                <hr />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>