<?php  defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div id="body" class="body bg--gray">
    <section class="dashboard">
		<?php $this->includeTemplate('_partial/dashboardTop.php'); ?>  
		<div class="fixed-container">
			<div class="row">
				<?php $this->includeTemplate('_partial/dashboardNavigation.php'); ?>   
				<div class="col-md-10 panel__right--full" >
					<div class="cols--group">
						<div class="panel__head no-print">
							<h2><?php echo Labels::getLabel('LBL_View_Order_Return_Request', $siteLangId).': ' . $request['orrequest_reference'] /* CommonHelper::formatOrderReturnRequestNumber($request['orrequest_id']) */; ?></h2>
						</div>
						<div class="panel__body">
							<div class="box box--white box--space">
								<div class="box__head no-print">
									<h4><?php echo Labels::getLabel('LBL_Request_Details', $siteLangId); ?></h4>
									<div class="group--btns"><a href="<?php echo CommonHelper::generateUrl('Buyer', 'orderReturnRequests'); ?>" class="btn btn--secondary btn--sm"><?php echo Labels::getLabel('LBL_Back_To_Return_Requests', $siteLangId); ?></a></div>
								</div>
								<div class="box__body">
									<div class="grids--offset">
										<div class="grid-layout">
											<div class="row">
												<div class="col-lg-6 col-md-6 col-sm-6">
													<h5><?php echo Labels::getLabel( 'LBL_Vendor_Return_Address', $siteLangId ); ?></h5>
													<?php echo ($vendorReturnAddress['ura_name'] != NULL ) ? '<h6>'.$vendorReturnAddress['ura_name'].'</h6>' : '';?>
													<p>
													<?php echo (strlen($vendorReturnAddress['ura_address_line_1']) > 0) ? $vendorReturnAddress['ura_address_line_1'].'<br/>' : '';?>
													<?php echo (strlen($vendorReturnAddress['ura_address_line_2'])>0)?$vendorReturnAddress['ura_address_line_2'].'<br>':'';?>
													<?php echo (strlen($vendorReturnAddress['ura_city'])>0)?$vendorReturnAddress['ura_city'].',':'';?>
													<?php echo (strlen($vendorReturnAddress['state_name'])>0)?$vendorReturnAddress['state_name'].'<br>':'';?>
													<?php echo (strlen($vendorReturnAddress['country_name'])>0)?$vendorReturnAddress['country_name'].'<br>':'';?>
													<?php echo (strlen($vendorReturnAddress['ura_zip'])>0) ? Labels::getLabel('LBL_Zip:', $siteLangId).$vendorReturnAddress['ura_zip'].'<br>':'';?>
													<?php echo (strlen($vendorReturnAddress['ura_phone'])>0) ? Labels::getLabel('LBL_Phone:', $siteLangId).$vendorReturnAddress['ura_phone'].'<br>':''; ?>
													</p>
												</div>
												<div class="col-lg-6 col-md-6 col-sm-6">
													<div class="info--order">
														<h5><?php echo Labels::getLabel('LBL_Vendor_Detail', $siteLangId); ?></h5>
														<p>
														<?php echo ($request['op_shop_owner_name'] != '' ) ? '<strong>'.Labels::getLabel('LBL_Vendor_Name:', $siteLangId).':</strong>'.$request['op_shop_owner_name'] : ''; ?></p>	
														<p>
														<?php 
														$vendorShopUrl = CommonHelper::generateUrl( 'Shops', 'View', array($request['op_shop_id']) );
														echo ( $request['op_shop_name'] != '' ) ? '<strong>'.Labels::getLabel('LBL_Shop_Name', $siteLangId).':</strong><a href="'.$vendorShopUrl.'">'.$request['op_shop_name'].'</a><br/>' : ''; ?>
														</p>
														<span class="gap"></span>
														<a href="javascript:window.print();" class="btn btn--primary no-print"><?php echo Labels::getLabel('LBL_Print',$siteLangId);?></a>
													</div>
												</div>
											</div>
									<?php if( $canEscalateRequest ){ ?>
									<a class="btn btn--primary no-print" onClick="javascript: return confirm('<?php echo Labels::getLabel('MSG_Do_you_want_to_proceed?', $siteLangId); ?>')" href="<?php echo CommonHelper::generateUrl('Account','EscalateOrderReturnRequest', array($request['orrequest_id'])); ?>"><?php echo str_replace("{website_name}", FatApp::getConfig('CONF_WEBSITE_NAME_'.$siteLangId), Labels::getLabel('LBL_Escalate_to', $siteLangId)); ?></a>
									<?php } ?>
									
									<?php if( $canWithdrawRequest ){ ?>
									<a class="btn btn--primary no-print" onClick="javascript: return confirm('<?php echo Labels::getLabel('MSG_Do_you_want_to_proceed?', $siteLangId); ?>')" href="<?php echo CommonHelper::generateUrl('Buyer','WithdrawOrderReturnRequest', array($request['orrequest_id'])); ?>"><?php echo Labels::getLabel('LBL_Withdraw_Request', $siteLangId); ?></a>
									<?php } ?>
										</div>
									</div>							
									
									<?php if( !empty($request) ){ ?>
									<table class="table table--orders">
										<tbody>
											<tr class="">
												<th width="15%"><?php echo Labels::getLabel('LBL_ID', $siteLangId); ?></th>
												<th width="20%"><?php echo Labels::getLabel('LBL_Order_Id/Invoice_Number', $siteLangId); ?></th>
												<th><?php echo Labels::getLabel( 'LBL_Product', $siteLangId ); ?></th>
												<th width="15%"><?php echo Labels::getLabel( 'LBL_Return_Qty', $siteLangId ); ?></th>
												<th width="15%"><?php echo Labels::getLabel( 'LBL_Request_Type', $siteLangId ); ?></th>
											</tr>
											<tr>
												<td><span class="caption--td"><?php echo Labels::getLabel('LBL_ID', $siteLangId); ?></span><?php echo $request['orrequest_reference'] /* CommonHelper::formatOrderReturnRequestNumber($request['orrequest_id']) */; ?></td>
												<td><span class="caption--td"><?php echo Labels::getLabel('LBL_Order_Id/Invoice_Number', $siteLangId); ?></span><?php echo $request['op_invoice_number']; ?>
												</td>
												<td><span class="caption--td"><?php echo Labels::getLabel( 'LBL_Product', $siteLangId ); ?></span>
													<div class="item__description">
														<?php if($request['op_selprod_title'] != ''){ ?> 
															<div class="item-yk-head-title" title="<?php echo $request['op_selprod_title']; ?>"><?php echo $request['op_selprod_title']; ?></div>
															<div class="item-yk-head-sub-title"><?php echo $request['op_product_name']; ?></div>
															<?php } else { ?>
															<div class="item-yk-head-title" title="<?php echo $request['op_product_name']; ?>"><?php echo $request['op_product_name']; ?></div>
															<?php } ?>
														<div class="item-yk-head-brand"><?php echo Labels::getLabel('LBL_Brand', $siteLangId); ?>: <?php echo $request['op_brand_name']; ?></div>
														<?php 
														if( $request['op_selprod_options'] != '' ){ ?>
															<div class="item-yk-head-specification"><?php echo $request['op_selprod_options']; ?></div>
														<?php }	?>
														
														<?php if( $request['op_selprod_sku'] != '' ){ ?>
															<div class="item-yk-head-sku"><?php echo Labels::getLabel('LBL_SKU', $siteLangId).':  ' . $request['op_selprod_sku']; ?> </div>
														<?php } ?>
														
														<?php if( $request['op_product_model'] != '' ){ ?>
															<div class="item-yk-head-model"><?php echo Labels::getLabel('LBL_Model', $siteLangId).':  ' . $request['op_product_model']; ?></div>
														<?php }	?>
													</div>
												</td>
												<td><span class="caption--td"><?php echo Labels::getLabel( 'LBL_Return_Qty', $siteLangId ); ?></span><?php echo $request['orrequest_qty']; ?></td>
												<td><span class="caption--td"><?php echo Labels::getLabel( 'LBL_Request_Type', $siteLangId ); ?></span> <?php echo $returnRequestTypeArr[$request['orrequest_type']]; ?></td>
											</tr>
										</tbody>
									</table>						
									
									<table class="table table--orders">
										<tbody>
											<tr class="">
												<th width="15%"><?php echo Labels::getLabel('LBL_Reason', $siteLangId); ?></th>
												<th><?php echo Labels::getLabel( 'LBL_Date', $siteLangId ); ?></th>
												<th width="15%"><?php echo Labels::getLabel( 'LBL_Status', $siteLangId ); ?></th>
												<th width="15%"><?php echo Labels::getLabel( 'LBL_Amount', $siteLangId ); ?></th>
											</tr>
											<tr>
												<td><span class="caption--td"><?php echo Labels::getLabel('LBL_Reason', $siteLangId); ?></span><?php echo $request['orreason_title']; ?></td>
												<td><span class="caption--td"><?php echo Labels::getLabel( 'LBL_Date', $siteLangId ); ?></span>
													<div class="item__description">
														<span class=""><?php echo FatDate::format($request['orrequest_date']); ?></span>
													</div>
												</td>
												<td><span class="caption--td"><?php echo Labels::getLabel( 'LBL_Status', $siteLangId ); ?></span><?php echo $requestRequestStatusArr[$request['orrequest_status']]; ?></td>
												<td><span class="caption--td"><?php echo Labels::getLabel( 'LBL_Amount', $siteLangId ); ?></span><?php 
												$returnDataArr = CommonHelper::getOrderProductRefundAmtArr($request);
												/* $priceTotalPerItem = CommonHelper::orderProductAmount($request,'netamount',true);

												$price = 0;	
												if($request['orrequest_status'] != OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED){
													if(FatApp::getConfig('CONF_RETURN_SHIPPING_CHARGES_TO_CUSTOMER',FatUtility::VAR_INT,0)){
														$shipCharges = isset($request['charges'][OrderProduct::CHARGE_TYPE_SHIPPING][OrderProduct::DB_TBL_CHARGES_PREFIX.'amount'])?$request['charges'][OrderProduct::CHARGE_TYPE_SHIPPING][OrderProduct::DB_TBL_CHARGES_PREFIX.'amount']:0;
														$unitShipCharges = round(($shipCharges / $request['op_qty']),2);
														$priceTotalPerItem = $priceTotalPerItem + $unitShipCharges;		
														$price = $priceTotalPerItem * $request['orrequest_qty'];
													}	
												}												
												if(!$price){
													$price = $priceTotalPerItem * $request['orrequest_qty'];
													$price = $price + $request['op_refund_shipping'];
												} */
												echo CommonHelper::displayMoneyFormat($returnDataArr['op_refund_amount'], true, false); ?></td>
											</tr>
										</tbody>
									</table>
									<?php }	?>
									<div class="no-print">
									<?php echo $returnRequestMsgsSrchForm->getFormHtml(); ?>
									<div class="gap"></div>
									<h5><?php echo Labels::getLabel('LBL_Return_Request_Messages', $siteLangId); ?> </h5>
									<div id="loadMoreBtnDiv"></div>
									<ul class="media media--details" id="messagesList">
									</ul>
									
									<?php if( $request && ($request['orrequest_status'] != OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED && $request['orrequest_status'] != OrderReturnRequest::RETURN_REQUEST_STATUS_WITHDRAWN ) ){

									$frmMsg->setFormTagAttribute('onSubmit','setUpReturnOrderRequestMessage(this); return false;');
									$frmMsg->setFormTagAttribute('class', 'form'); 
									$frmMsg->developerTags['colClassPrefix'] = 'col-md-';
									$frmMsg->developerTags['fld_default_col'] = 12;
									?>
									<ul class="media media--details">
										<li>
											<div class="grid grid--first">
												<div class="avtar"><img src="<?php echo CommonHelper::generateUrl('Image', 'user', array($logged_user_id, 'THUMB', 1)); ?>" alt="<?php echo $logged_user_name; ?>" title="<?php echo $logged_user_name; ?>"></div>
											</div>
											<div class="grid grid--second">
												<span class="media__title"><?php echo $logged_user_name; ?></span>
											</div>
											<div class="grid grid--third">
												<div class="form__cover">
												<?php echo $frmMsg->getFormHtml(); ?>
												</div>
											</div>
										</li>
									</ul>
									<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div class="gap"></div>
</div>