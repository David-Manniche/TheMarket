<?php  defined('SYSTEM_INIT') or die('Invalid Usage.');
$this->includeTemplate('_partial/seller/sellerDashboardNavigation.php');

$frm->setFormTagAttribute('onSubmit', 'setupProfile(this); return false;');
$frm->setFormTagAttribute('class', 'form ');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 6;
?>
<main id="main-area" class="main" role="main">
	<div class="content-wrapper content-space">
		<div class="content-header row justify-content-between mb-3">
			<div class="col-md-auto">
				<h5 class="cards-title"><?php echo Labels::getLabel('LBL_Shipping_Profiles', $siteLangId);?>
				</h5>
			</div>
			<div class="col-auto">
				<div class="content-header-right">
					<a href="<?php echo CommonHelper::generateUrl('shippingProfile');?>"
						class="btn btn--secondary btn--sm"><?php echo Labels::getLabel('LBL_back', $siteLangId);?></a>
				</div>
			</div>
		</div>
		<div class="content-body">
			<div class="row mb-4">
				<div class="col-lg-12">
					<div class="cards">
						<div class="cards-content pt-4 pl-4 pr-4 pb-4">

							<?php echo $frm->getFormTag();
                                    $pNameFld = $frm->getField('shipprofile_name');
                                    $pNameFld->htmlAfterField = "<span class='form-text text-muted'>".Labels::getLabel("LBL_Customers_won't_see_this", $siteLangId)."</span>";
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
			<?php if (empty($profileData) || ((isset($profileData['shipprofile_default']) && $profileData['shipprofile_default'] != 1))) { ?>
			<div class="row mb-4">
				<div class="col-lg-7">
					<div class="cards">
						<div class="cards-content pt-4 pl-4 pr-4 pb-4">
							<div class="portlet">
								<div class="portlet__body">
									<div class="row justify-content-between">
										<div class="col">
											<div class="row align-items-center">
												<div class="col-auto">
													<h5 class="cards-title"><?php echo Labels::getLabel('LBL_Shipping_to', $siteLangId); ?>
													</h5>
												</div>
											</div>
										</div>
										<div class="col-auto">
										<?php if($canEdit) {?>
											<a href="javascript:0;"
												onClick="zoneForm(<?php echo $profile_id;?>, 0)"
												class="link font-bolder"><i class="fa fa-plus-square"></i>
											</a>
										<?php }?>
										</div>
									</div>
								</div>
								<input type="hidden" name="profile_id"
									value="<?php echo $profile_id;?>">
								<div id="listing-zones" class="portlet__body"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-5">
					<div class="cards">
						<div class="cards-content pt-4 pl-4 pr-4 pb-4">
							<div id="product-section--js">
								<hr />
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
			<div class="row">

			</div>
		</div>
	</div>
</main>