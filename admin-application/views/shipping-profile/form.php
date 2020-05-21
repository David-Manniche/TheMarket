<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$frm->setFormTagAttribute('class', 'web_form form_horizontal');
$frm->setFormTagAttribute('onsubmit', 'setupProfile(this); return(false);');
?>

<div class="page">
    <div class="container container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 space">
                <div class="page__title mb-5">
                    <div class="row">
                        <div class="col--first col-lg-6">
                            <span class="page__icon"><i class="ion-android-star"></i></span>
                            <h5><?php echo Labels::getLabel('LBL_Shipping_Management', $adminLangId); ?>
                            </h5> <?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <!--shipping from--->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="portlet">
                                    <div class="portlet__head">
                                        <div class="portlet__head-label">
                                            <h3 class="portlet__head-title"><?php echo Labels::getLabel('LBL_Name', $adminLangId); ?>
                                            </h3>
                                        </div>
                                        <div class="portlet__head-toolbar">
                                            <div class="portlet__head-actions"></div>
                                        </div>
                                    </div>
                                    <div class="portlet__body">
                                        <?php echo $frm->getFormTag();
                                        $pNameFld = $frm->getField('shipprofile_name');
                                        $pNameFld->htmlAfterField = "<span class='form-text text-muted'>".Labels::getLabel("LBL_Customers_will_not_see_this", $adminLangId)."</span>";
                                        
                                        //$pNameFld->addFieldTagAttribute('placeholder', 'P');
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
                                <!-- products section  -->
                                <?php if (empty($profileData) || ((isset($profileData['shipprofile_default']) && $profileData['shipprofile_default'] != 1))) { ?>
                                <div class="portlet" id="product-section--js"></div>
                                <?php } ?>
                                <!---->
                                <!---->
                                <div class="portlet">
                                    <div class="portlet__head">
                                        <div class="portlet__head-label">
                                            <h3 class="portlet__head-title"><?php echo Labels::getLabel('LBL_Shipping_to', $adminLangId); ?>
                                            </h3>
                                        </div>
                                        <div class="portlet__head-toolbar">
                                            <div class="portlet__head-actions">
                                                <a href="javascript:0;"
                                                    onClick="zoneForm(<?php echo $profile_id;?>, 0)"
                                                    class="link font-bolder"><?php echo Labels::getLabel('LBL_Create_Shipping_Zone', $adminLangId); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="profile_id"
                                        value="<?php echo $profile_id;?>">
                                    <div id="listing-zones" class="portlet__body"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="portlet">
                                    <div class="portlet__body">
                                        <div class="alert alert-elevate fade show alert-custom " role="alert">
                                            <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
                                            <div class="alert-text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>