<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$this->includeTemplate('_partial/seller/sellerDashboardNavigation.php');

$frm->setFormTagAttribute('onSubmit', 'setupProfile(this); return false;');
$frm->setFormTagAttribute('class', 'form ');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 6;

$submitBtnFld = $frm->getField('btn_submit');
$submitBtnFld->setFieldTagAttribute('class', 'btn btn-brand');
$submitBtnFld->setWrapperAttribute('class', 'col-lg-2');
$submitBtnFld->developerTags['col'] = 2;
$submitBtnFld->developerTags['noCaptionTag'] = true;
?>
<main id="main-area" class="main"   >
    <div class="content-wrapper content-space">
        <div class="content-header row">
            <div class="col">
                <h5 class="content-header-title"><?php echo Labels::getLabel('LBL_Shipping_Profiles', $siteLangId); ?>
                </h5>
            </div>
            <div class="col-auto">
                <div class="content-header-right">
                    <a href="<?php echo UrlHelper::generateUrl('shippingProfile'); ?>" class="btn btn-outline-brand btn-sm"><?php echo Labels::getLabel('LBL_back', $siteLangId); ?></a>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body pt-4 pl-4 pr-4 pb-4">
                            <?php echo $frm->getFormTag();
                            $pNameFld = $frm->getField('shipprofile_name');
                            $pNameFld->htmlAfterField = "<span class='form-text text-muted'>" . Labels::getLabel("LBL_Customers_won't_see_this", $siteLangId) . "</span>";
                            $pNameFld->addFieldTagAttribute('class', 'form-control');
                            ?>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group mb-0">
                                        <?php
                                        if (!empty($profileData) && $profileData['shipprofile_default'] == 1) {
                                            $pNameFld->addFieldTagAttribute('readonly', 'true');
                                            $pNameFld->addFieldTagAttribute('disabled', 'true');
                                        }

                                        echo $frm->getFieldHtml('shipprofile_name'); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <?php
                                        echo $frm->getFieldHtml('shipprofile_id');
                                        echo $frm->getFieldHtml('shipprofile_user_id');
                                        if (empty($profileData) || ((isset($profileData['shipprofile_default']) && $profileData['shipprofile_default'] != 1))) {
                                            echo $frm->getFieldHtml('btn_submit');
                                        }

                                        ?>
                                    </div>
                                </div>
                            </div>
                            </form>
                            <?php echo $frm->getExternalJs(); ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="card" id="product-section--js"> </div>
                </div>
            </div>
            <?php if (empty($profileData) || ((isset($profileData['shipprofile_default'])))) { ?>
                <div class="row mb-4">
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title"><?php echo Labels::getLabel('LBL_Shipping_to', $siteLangId); ?>
                                </h5>
                                <div class="action">
                                    <?php if ($canEdit) { ?>
                                        <a class="btn btn-outline-brand btn-sm" href="javascript:void(0);" onClick="zoneForm(<?php echo $profile_id; ?>, 0)" title="<?php echo Labels::getLabel('LBL_ADD_ZONE', $siteLangId);?>"><i class="fa fa-plus"></i> <?php echo Labels::getLabel('LBL_ADD', $siteLangId);?>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="profile_id" value="<?php echo $profile_id; ?>">
                                <div id="listing-zones"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!--<div class="col-md-5 mb-4">
                        <div class="card">
                            <div id="ship-section--js"></div>
                        </div>
                    </div>-->
                </div>
            <?php } ?>
        </div>
    </div>
</main>