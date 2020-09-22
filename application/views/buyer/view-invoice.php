<?php defined('SYSTEM_INIT') or die('Invalid Usage . '); ?>
<table width="100%" cellspacing="0" cellpadding="20" border="0" style="font-size: 14px;background: #f2f2f2;font-family: Arial, sans-serif;">
	<tbody><tr>
		<td>
			<table width="1100px" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;border:2px solid #ddd;background-color: #fff;">
				<tbody><tr>
					<td>
						<!--main Start-->
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tbody><tr>
								<td style="font-size: 32px;font-weight: 700;color: #000;padding: 15px;text-align:center;border-bottom: 1px solid #ddd;"><?php echo Labels::getlabel('LBL_Tax_Invoice', $siteLangId);?></td>
							</tr>
						</tbody></table>
						<?php foreach ($childOrderDetail as $childOrder) { ?> 
							<table width="100%" border="0" cellpadding="0" cellspacing="0">      
								<tbody>
									<tr>
										<td style="padding:15px;border-bottom: 1px solid #ddd;">
											<h4 style="margin:0;font-size:18px;font-weight:bold;padding-bottom: 5px;"><?php echo Labels::getLabel('LBL_Sold_By', $siteLangId); ?>: <?php echo $childOrder['op_shop_name']; ?> ,</h4>
											<p style="margin:0;padding-bottom: 15px;"><?php echo Labels::getLabel('LBL_Shop_Address', $siteLangId); ?>: <?php echo $childOrder['shop_city'] .' ,'. $childOrder['shop_state_name'] .' ,'.$childOrder['shop_country_name'] .' - '.$childOrder['shop_postalcode']; ?></p>
											<table width="100%" border="0" cellpadding="0" cellspacing="0">                                                             
												<tbody><tr>
													<td style="font-weight: 700;">GSTIN - 33AAXCS0655F1Z5</td>
													<td style="text-align: right;font-weight: 700;">CIN - U52399DL2016PTC299716</td>
												</tr>
											</tbody></table>                                        
										</td>
									</tr>
								</tbody>
							</table>
							<?php if ($childOrder['op_product_type'] != Product::PRODUCT_TYPE_DIGITAL) { ?>
							<table width="100%" border="0" cellpadding="0" cellspacing="0">        
								<tbody>
									<tr>
										<td style="border-bottom: 1px solid #ddd;">                                       
											<table width="100%" border="0" cellpadding="0" cellspacing="0">                           
												<tbody>
													<tr>
													<td style="padding:15px;">
															<h4 style="margin:0;font-size:18px;font-weight:bold;padding-bottom: 5px;"><?php echo Labels::getLabel('LBL_Bill_to', $siteLangId); ?></h4>
															<p style="margin:0;padding-bottom: 15px;">
																<?php $billingAddress = $orderDetail['billingAddress']['oua_name'] . '<br>';
																if ($orderDetail['billingAddress']['oua_address1'] != '') {
																	$billingAddress .= $orderDetail['billingAddress']['oua_address1'] . '<br>';
																}

																if ($orderDetail['billingAddress']['oua_address2'] != '') {
																	$billingAddress .= $orderDetail['billingAddress']['oua_address2'] . '<br>';
																}

																if ($orderDetail['billingAddress']['oua_city'] != '') {
																	$billingAddress .= $orderDetail['billingAddress']['oua_city'] . ', ';
																}

																if ($orderDetail['billingAddress']['oua_state'] != '') {
																	$billingAddress .= $orderDetail['billingAddress']['oua_state'] . ', ';
																}
																
																if ($orderDetail['billingAddress']['oua_country'] != '') {
																	$billingAddress .= $orderDetail['billingAddress']['oua_country'];
																}

																if ($orderDetail['billingAddress']['oua_zip'] != '') {
																	$billingAddress  .= '-' . $orderDetail['billingAddress']['oua_zip'];
																}

																if ($orderDetail['billingAddress']['oua_phone'] != '') {
																	$billingAddress  .= '<br>' . $orderDetail['billingAddress']['oua_phone'];
																}
																?>
																<?php echo $billingAddress; ?>
															</p>                                                  
														</td>
														<?php if (!empty($orderDetail['shippingAddress'])) {  ?>
														<td style="padding:15px;border-right: 1px solid #ddd;">
															<h4 style="margin:0;font-size:18px;font-weight:bold;padding-bottom: 5px;"><?php echo Labels::getLabel('LBL_Ship_to', $siteLangId); ?></h4>
															<p style="margin:0;padding-bottom: 15px;">
																<?php $shippingAddress = $orderDetail['shippingAddress']['oua_name'] . '<br>';
																if ($orderDetail['shippingAddress']['oua_address1'] != '') {
																	$shippingAddress .= $orderDetail['shippingAddress']['oua_address1'] . '<br>';
																}

																if ($orderDetail['shippingAddress']['oua_address2'] != '') {
																	$shippingAddress .= $orderDetail['shippingAddress']['oua_address2'] . '<br>';
																}

																if ($orderDetail['shippingAddress']['oua_city'] != '') {
																	$shippingAddress .= $orderDetail['shippingAddress']['oua_city'] . ',';
																}

																if ($orderDetail['shippingAddress']['oua_state'] != '') {
																	$shippingAddress .= $orderDetail['shippingAddress']['oua_state'] . ', ';
																}
																
																if ($orderDetail['shippingAddress']['oua_country'] != '') {
																	$shippingAddress .= $orderDetail['shippingAddress']['oua_country'];
																}

																if ($orderDetail['shippingAddress']['oua_zip'] != '') {
																	$shippingAddress .= '-' . $orderDetail['shippingAddress']['oua_zip'];
																}

																if ($orderDetail['shippingAddress']['oua_phone'] != '') {
																	$shippingAddress .= '<br>' . $orderDetail['shippingAddress']['oua_phone'];
																} ?>
																<?php echo $shippingAddress; ?>
															</p>
														</td>
														<?php } ?>
														<?php if (!empty($orderDetail['pickupAddress'])) { ?>
														<td style="padding:15px;">
															<h4 style="margin:0;font-size:18px;font-weight:bold;padding-bottom: 5px;"><?php echo Labels::getLabel('LBL_Pickup_Details', $siteLangId); ?></h4>
															<p style="margin:0;padding-bottom: 15px;">
															<?php $pickUpAddress = $orderDetail['pickupAddress']['oua_name'] . '<br>';
																if ($orderDetail['pickupAddress']['oua_address1'] != '') {
																	$pickUpAddress .= $orderDetail['pickupAddress']['oua_address1'] . '<br>';
																}

																if ($orderDetail['pickupAddress']['oua_address2'] != '') {
																	$pickUpAddress .= $orderDetail['pickupAddress']['oua_address2'] . '<br>';
																}

																if ($orderDetail['pickupAddress']['oua_city'] != '') {
																	$pickUpAddress .= $orderDetail['pickupAddress']['oua_city'] . ',';
																}

																if ($orderDetail['pickupAddress']['oua_zip'] != '') {
																	$pickUpAddress .= $orderDetail['pickupAddress']['oua_state'];
																}

																if ($orderDetail['pickupAddress']['oua_zip'] != '') {
																	$pickUpAddress .= '-' . $orderDetail['pickupAddress']['oua_zip'];
																}

																if ($orderDetail['pickupAddress']['oua_phone'] != '') {
																	$pickUpAddress .= '<br>' . $orderDetail['pickupAddress']['oua_phone'];
																} ?>
																<?php echo $pickUpAddress; ?>
															</p>                                                  
														</td>
														<?php } ?>
													</tr>
												</tbody>
											</table>                                        
										</td>
									</tr>
								</tbody>
							</table> 
							<?php } ?>

						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tbody>
								<tr>
									<td style="border-bottom: 1px solid #ddd;">                                       
										<table width="100%" border="0" cellpadding="0" cellspacing="0">
											<tbody><tr>
												<td style="padding:15px;">
												<p><strong><?php echo Labels::getLabel('LBL_Order', $siteLangId);?>:</strong>  <?php echo $childOrder['op_order_id']; ?> </p>
												<p><strong><?php echo Labels::getLabel('LBL_Invoice_Number', $siteLangId);?>:</strong>  <?php echo $childOrder['op_invoice_number']; ?></p>
												<p><strong><?php echo Labels::getLabel('LBL_Payment_Method', $siteLangId);?>:</strong> POSTPAID </p>
												<p><strong><?php echo Labels::getLabel('LBL_Total_Items', $siteLangId);?>: 5 </strong></p><strong>
												</strong></td>
												<td style="padding:15px;">
													<p><strong><?php echo Labels::getLabel('LBL_Order_Date', $siteLangId);?>:</strong>   07-02-2020</p>
													<p><strong><?php echo Labels::getLabel('LBL_Invoice_Date', $siteLangId);?>:</strong>  09-02-2020</p>
													<p><strong><?php echo Labels::getLabel('LBL_Tracking_ID', $siteLangId);?>:</strong>  GROC0008702522 </p>
													<p><strong><?php echo Labels::getLabel('LBL_Tote-Id', $siteLangId);?>: </strong>  071-19078 </p>
												</td>
											</tr>
										</tbody></table>                                        
									</td>
								</tr>
							</tbody>
						</table> 
					<?php } ?>


						<table width="100%" border="0" cellpadding="0" cellspacing="0">                                                             
							<tbody><tr>
								<td style="border-bottom: 1px solid #ddd;">                                       
									<table width="100%" border="0" cellpadding="0" cellspacing="0">                                                             
										<tbody><tr>
											<th style="padding:10px 15px;text-align: left;">S.No.</th>                                                
											<th style="padding:10px 15px;text-align: left;">Item</th>                                                
											<th style="padding:10px 15px;text-align: center;">HSN (Tax%)</th>                                                
											<th style="padding:10px 15px;text-align: center;">Qty</th>                                                
											<th style="padding:10px 15px;text-align: center;">MRP (Rs)</th>                                                
											<th style="padding:10px 15px;text-align: center;">Savings (Rs)</th>                                                
											<th style="padding:10px 15px;text-align: center;">Total Amt (Rs)</th>                                                
										</tr>
										<tr>
											<th colspan="7" style="padding:10px 15px;text-align: left;background-color: #f0f0f0;font-size: 18px;">FOOD ITEMS</th>                                             
										</tr>
										<tr>
											<td style="padding:10px 15px;text-align: left;">1</td>                                             
											<td style="padding:10px 15px;text-align: left;">Budweiser Non-Alcoholic Can, 330 ml </td>                                             
											<td style="padding:10px 15px;text-align: center;">22029090 (18.0)</td>                                             
											<td style="padding:10px 15px;text-align: center;">1</td>                                             
											<td style="padding:10px 15px;text-align: center;">80.00</td>                                             
											<td style="padding:10px 15px;text-align: center;">79.00</td>                                             
											<td style="padding:10px 15px;text-align: center;">1.00</td>                                             
										</tr>
										<tr>
											<td style="padding:10px 15px;text-align: left;">2</td>                                             
											<td style="padding:10px 15px;text-align: left;">Toor Dal, 500 g </td>                                             
											<td style="padding:10px 15px;text-align: center;">22029090 (18.0)</td>                                             
											<td style="padding:10px 15px;text-align: center;">1</td>                                             
											<td style="padding:10px 15px;text-align: center;">80.00</td>                                             
											<td style="padding:10px 15px;text-align: center;">79.00</td>                                             
											<td style="padding:10px 15px;text-align: center;">1.00</td>                                             
										</tr>
										<tr>
											<td style="padding:10px 15px;text-align: left;">3</td>                                             
											<td style="padding:10px 15px;text-align: left;">aavin Ghee 1 L Carton </td>                                             
											<td style="padding:10px 15px;text-align: center;">22029090 (18.0)</td>                                             
											<td style="padding:10px 15px;text-align: center;">1</td>                                             
											<td style="padding:10px 15px;text-align: center;">80.00</td>                                             
											<td style="padding:10px 15px;text-align: center;">79.00</td>                                             
											<td style="padding:10px 15px;text-align: center;">1.00</td>                                             
										</tr>
										<tr>
											<td style="padding:10px 15px;text-align: left;">4</td>                                             
											<td style="padding:10px 15px;text-align: left;">Sunland Refined Sunflower Oil Pouch, 500 ml  </td>                                             
											<td style="padding:10px 15px;text-align: center;">22029090 (18.0)</td>                                             
											<td style="padding:10px 15px;text-align: center;">1</td>                                             
											<td style="padding:10px 15px;text-align: center;">80.00</td>                                             
											<td style="padding:10px 15px;text-align: center;">79.00</td>                                             
											<td style="padding:10px 15px;text-align: center;">1.00</td>                                             
										</tr>
										<tr>
											<td style="padding:10px 15px;text-align: left;">5</td>                                             
											<td style="padding:10px 15px;text-align: left;">BRU Green Label Roast &amp; Ground Coffee, 500 g  </td>                                             
											<td style="padding:10px 15px;text-align: center;">22029090 (18.0)</td>                                             
											<td style="padding:10px 15px;text-align: center;">1</td>                                             
											<td style="padding:10px 15px;text-align: center;">80.00</td>                                             
											<td style="padding:10px 15px;text-align: center;">79.00</td>                                             
											<td style="padding:10px 15px;text-align: center;">1.00</td>                                             
										</tr>
										<tr>                                           
											<td style="padding:10px 15px;font-size:18px;text-align: left;font-weight:700;background-color: #f0f0f0;" colspan="3">Summary </td>                                     
											<td style="padding:10px 15px;text-align: center;background-color: #f0f0f0;font-size: 16px;"><strong>5</strong></td>                                             
											<td style="padding:10px 15px;text-align: center;background-color: #f0f0f0;font-size: 16px;"><strong>876.00</strong></td>                                             
											<td style="padding:10px 15px;text-align: center;background-color: #f0f0f0;font-size: 16px;"><strong>243.00</strong></td> 
											<td style="padding:10px 15px;text-align: center;background-color: #f0f0f0;font-size: 16px;"><strong>633.00</strong></td>                                             
										</tr>
										<tr>                                          
											<td style="padding:15px 15px;font-size:20px;text-align: left;font-weight:700; vertical-align: top;" colspan="4" rowspan="4">You have SAVED Rs. 243.00 on this order. </td>                                     
											<td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="2">Total Amount (Food)</td>                    
											<td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="1">633.00</td>                                           
										</tr>
										<tr>                                                                            
											<td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="2">Total Amount(NonFood)</td>                    
											<td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="1">0.00</td>                                           
										</tr>
										<tr>                                                                              
											<td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="2">Delivery Charges</td>                    
											<td style="padding:10px 15px;text-align: center;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="1">50.00</td>                                           
										</tr>
										<tr>                                                                         
											<td style="padding:10px 15px;text-align: center;font-weight:700;font-size: 18px;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="2"><strong>GRAND TOTAL</strong> </td>                    
											<td style="padding:10px 15px;text-align: center;font-weight:700;font-size: 18px;border:1px solid #ddd;border-right:0;border-bottom:0;" colspan="1"><strong>683.00</strong></td>                                           
										</tr>
									</tbody></table>                                        
								</td>
							</tr>
						</tbody></table>



						<table width="100%" border="0" cellpadding="0" cellspacing="0">                                                             
							<tbody><tr>
								<td style="padding:15px;vertical-align: top;">
									<h2 style="font-size: 20px;text-align: center;">Shreyash Retail Private Limited</h2>
									<span style="padding-top: 150px;display: block;text-align: center;">Authorized Signatory </span>
								</td>
								<td style="text-align: center;">                                       
									<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">                                                             
										<tbody><tr>
											<th style="padding:15px;background-color: #f0f0f0;border:1px solid #ddd;border-right:none;border-top: 0;" colspan="4">Tax break-up</th>                                                
										</tr>
										<tr>
											<th style="padding:10px 15px;border:1px solid #ddd;">GST%</th>                                                
											<th style="padding:10px 15px;border:1px solid #ddd;">Taxable Amount</th>                                                
											<th style="padding:10px 15px;border:1px solid #ddd;">SGST</th>                                                
											<th style="padding:10px 15px;border:1px solid #ddd;border-right:none;">CGST</th>                                                
										</tr>
										<tr>
											<td style="padding:10px 15px;border:1px solid #ddd;">0.00 </td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;">1.00 </td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;">0.00 </td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;border-right:none;">0.00 </td>                                            
										</tr>
										<tr>
											<td style="padding:10px 15px;border:1px solid #ddd;">12.00  </td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;">416.08 </td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;">24.96 </td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;border-right:none;">24.96 </td>                                            
										</tr>
										<tr>
											<td style="padding:10px 15px;border:1px solid #ddd;">12.00  </td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;">416.08 </td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;">24.96 </td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;border-right:none;">24.96 </td>                                            
										</tr>
										<tr>
											<td style="padding:10px 15px;border:1px solid #ddd;">5.00  </td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;">157.16 </td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;">3.92 </td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;border-right:none;">3.92 </td>                                            
										</tr>
										<tr>
											<td style="padding:10px 15px;font-size: 12px;border:1px solid #ddd;">Delivery Charges* </td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;">157.16 </td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;">3.92 </td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;border-right:none;">3.92 </td>                                            
										</tr>
										<tr>
											<td style="padding:10px 15px;border:1px solid #ddd;font-size:16px;"><strong>Grand Total</strong> </td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;font-size:16px;"><strong>157.16 </strong></td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;font-size:16px;"><strong>3.92</strong> </td>                                            
											<td style="padding:10px 15px;border:1px solid #ddd;border-right:none;font-size:16px;"><strong>3.92</strong> </td>                                            
										</tr>
										<tr>
											<td style="padding:10px 15px;text-align: left;border:1px solid #ddd;border-bottom:none;border-right:none;font-size: 12px;background-color: #f0f0f0;" colspan="4">*Appropriated product-wise and Rate applicable thereunder.</td>                                            
										</tr>
									</tbody></table>                                        
								</td>
							</tr>
						</tbody></table> 

						<table width="100%" border="0" cellpadding="0" cellspacing="0">                                                             
							<tbody><tr>
								<td style="padding:20px 15px;border-top:1px solid #ddd">                                       
									<p><strong>Return Policy:</strong> If the item is defective or not as described, you may return it during delivery directly or you may request for return within 10 days of delivery
										for items that are defective or are different from what you ordered. Items must be complete (including freebies), free from damages and for items returned
										for being different from what you ordered, they must be unopened as well.
										</p><p>The goods sold as are intended for end user consumption and not for re-sale.</p>  
										<p><strong>Regd. office:</strong> Shreyash Retail Private Limited , A-285, Main Bhisham Pitamaha Marg, Defence Colony, New Delh, New Delh - 110024</p>  
										<p><strong>Contact Flipkart:</strong> 1800 208 9898 || www.flipkart.com/helpcentre</p>                                    
								</td>
							</tr>
						</tbody></table> 

						<!--main End-->                                                         
					</td>
				</tr>                     
			</tbody></table>
		</td>
	</tr>       
</tbody></table> 
<?php if ($print) { ?>
    <script>
        $(".sidebar-is-expanded").addClass('sidebar-is-reduced').removeClass('sidebar-is-expanded');
        /*window.print();
        window.onafterprint = function() {
        location.href = history.back();
        }*/
    </script>
<?php } ?>
<script>
    function increaseDownloadedCount(linkId, opId) {
        fcom.ajax(fcom.makeUrl('buyer', 'downloadDigitalProductFromLink', [linkId, opId]), '', function(t) {
            var ans = $.parseJSON(t);
            if (ans.status == 0) {
                $.systemMessage(ans.msg, 'alert--danger');
                return false;
            }
            /* var dataLink = $(this).attr('data-link');
            window.location.href= dataLink; */
            location.reload();
            return true;
        });
    }

    trackOrder = function(trackingNumber, courier, orderNumber) {
        $.facebox(function() {
            fcom.ajax(fcom.makeUrl('Buyer', 'orderTrackingInfo', [trackingNumber, courier, orderNumber]), '', function(res) {
                $.facebox(res, 'medium-fb-width');
            });
        });
    };
</script>