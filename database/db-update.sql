CREATE TABLE `tbl_cart_history` (
  `carthistory_user_id` int(11) NOT NULL,
  `carthistory_selprod_id` int(11) NOT NULL,
  `carthistory_type` tinyint(1) NOT NULL COMMENT 'Defined in model	',
  `carthistory_qty` int(11) NOT NULL,
  `carthistory_action` tinyint(1) NOT NULL COMMENT 'Defined in model',
  `carthistory_email_count` int(11) NOT NULL,
  `carthistory_discount_notification` tinyint(1) NOT NULL,
  `carthistory_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tbl_cart_history`
  ADD PRIMARY KEY (`carthistory_user_id`,`carthistory_selprod_id`);
  
INSERT INTO `tbl_cron_schedules` (`cron_id`, `cron_name`, `cron_command`, `cron_duration`, `cron_active`) VALUES (NULL, 'Abandoned Cart Reminder Email', 'CartHistory/sendReminderAbandonedCart', '600', '1');


INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES ('abandoned_cart_email', '1', 'Abandoned Cart Email', 'Abandoned Cart Email', '<div style=\"margin:0; padding:0;background: #ecf0f1;\">\r\n	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n					<!--\r\n					header start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n								<td style=\"text-align:right;\">{social_media_icons}</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					header end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n            \r\n            <tr>\r\n                <td style=\"background:#ff3a59;\">\r\n                    <!--\r\n                    page title start here\r\n                    -->\r\n\r\n                    <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n                                    <h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\r\n                                    <h2 style=\"margin:0; color: #999999; font-size:16px; text-transform: uppercase;padding:0;\">Dear {user_full_name}</h2>\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                    <!--\r\n                    page title end here\r\n                    -->\r\n                </td>\r\n            </tr>\r\n            \r\n            <tr>\r\n                <td>\r\n                    <!--\r\n                    page body start here\r\n                    -->\r\n\r\n                    <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"background:#fff;padding:0 20px; text-align:center; color:#999;vertical-align:top;\">\r\n                                    <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                                        <tbody>\r\n                                            <tr>\r\n                                                <td style=\"padding:0 10px; line-height:1.3; text-align:center; color:#333333;vertical-align:top; font-size: 30px;\">We noticed you left something behind!</td>\r\n                                            </tr>\r\n                                            <tr>\r\n                                                <td style=\"padding:30px 0;\">\r\n                                                <table>{product_detail_table}</table>\r\n                                                </td>\r\n                                            </tr>\r\n                                            <!--\r\n                                            section footer\r\n                                            -->\r\n\r\n                                            <tr>\r\n                                                <td style=\"padding:30px 0;border-top:1px solid #ddd;\">Get in touch in you have any questions regarding our Services.<br />\r\n													Feel free to contact us 24/7. We are here to help.<br />\r\n													<br />\r\n													All the best,<br />\r\n													The {website_name} Team<br />\r\n                                                </td>\r\n                                            </tr>\r\n                                            <!--\r\n                                            section footer\r\n                                            -->\r\n\r\n                                        </tbody>\r\n                                    </table>\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                    <!--\r\n                    page body end here\r\n                    -->\r\n                </td>\r\n            </tr>\r\n\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page footer start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"height:30px;\"></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n													 <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">We are here, ready to talk</a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\"><br />\r\n													<br />\r\n													{website_name} Inc.\r\n													<!--\r\n													if these emails get annoying, please feel free to  <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n													-->\r\n													</td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; height:50px;\"></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page footer end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n		</tbody>\r\n	</table></div>', '{user_full_name} Name of the email receiver<br>\r\n{website_name} Name of our website<br>\r\n{website_url} URL of our website<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>\r\n{product_detail_table} <br/>', '1');

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES ('abandoned_cart_discount_notification', '1', 'Abandoned Cart Discount Notification', 'Abandoned Cart Discount Notification', '<div style=\"margin:0; padding:0;background: #ecf0f1;\">\r\n	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n					<!--\r\n					header start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n								<td style=\"text-align:right;\">{social_media_icons}</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					header end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n            \r\n             <tr>\r\n                <td style=\"background:#ff3a59;\">\r\n                    <!--\r\n                    page title start here\r\n                    -->\r\n                    <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n                                    <h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\r\n                                    <h2 style=\"margin:0; color: #999999; font-size:16px; text-transform: uppercase;padding:0;\">Dear {user_full_name}</h2>\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                    <!--\r\n                    page title end here\r\n                    -->\r\n                </td>\r\n            </tr>\r\n            \r\n            <tr>\r\n                <td>\r\n                    <!--\r\n                    page body start here\r\n                    -->\r\n\r\n                    <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"background:#fff;padding:0 20px; text-align:center; color:#999;vertical-align:top;\">\r\n                                    <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                                        <tbody>\r\n                                            <tr>\r\n                                                <td style=\"padding:0 10px; line-height:1.3; text-align:center; color:#333333;vertical-align:top; font-size: 30px;\">Finish your order before your items sell out!</td>\r\n                                            </tr> \r\n                                               \r\n                                               <tr>\r\n                                                   <td style=\"padding:20px 0 10px 10px; line-height:1.3; text-align:center; color:#999999;vertical-align:top; font-size:16px;\">Just for you : Get <span style=\"color:#333; font-weight: bold;\">{discount} OFF</span> off on your order with code <span style=\"color:#333; font-weight: bold;\">{coupon_code}.</span>\r\n                                                   \r\n                                                       <a href=\"{checkout_now}\" style=\"background: #ff3a59;border:none; border-radius: 4px; color: #fff; cursor: pointer;margin:10px 0 0 0;width: auto; font-weight: normal; padding: 10px 20px; \">Check out now </a></td>\r\n                                            </tr>\r\n                                            <tr>\r\n                                                <td style=\"padding:30px 0;\">\r\n                                                    <table>\r\n                                                        <tr>\r\n                                                            <td style=\"padding-right: 25px;\"><img style=\"border: solid 1px #ececec; padding: 10px; border-radius: 4px;\" src=\"{product_image}\"></td>\r\n                                                            <td>\r\n                                                                <span style=\"font-size: 20px; font-weight:normal; color:#999999; \">{product_name}</span>\r\n                                                                 <span style=\"font-size: 14px; font-weight: bold; color:#000000; display: block; padding: 20px 0;\">{product_price}</span>\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </table>\r\n\r\n                                                </td>\r\n                                            </tr>\r\n                                            <!--\r\n                                            section footer\r\n                                            -->\r\n\r\n                                            <tr>\r\n                                                <td style=\"padding:30px 0;border-top:1px solid #ddd;\">Get in touch in you have any questions regarding our Services.<br />\r\n													Feel free to contact us 24/7. We are here to help.<br />\r\n													<br />\r\n													All the best,<br />\r\n													The {website_name} Team<br />\r\n                                                </td>\r\n                                            </tr>\r\n                                            <!--\r\n                                            section footer\r\n                                            -->\r\n\r\n                                        </tbody>\r\n                                    </table>\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                    <!--\r\n                    page body end here\r\n                    -->\r\n                </td>\r\n            </tr>\r\n        \r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page footer start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"height:30px;\"></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n													 <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">We are here, ready to talk</a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\"><br />\r\n													<br />\r\n													{website_name} Inc.\r\n													<!--\r\n													if these emails get annoying, please feel free to  <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n													-->\r\n													</td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; height:50px;\"></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page footer end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n		</tbody>\r\n	</table></div>', '{user_full_name} Name of the email receiver<br>\r\n{website_name} Name of our website<br>\r\n{website_url} URL of our website<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>\r\n{discount} <br>\r\n{coupon_code} <br>\r\n{checkout_now} <br>\r\n{product_image} <br>\r\n{product_name} <br>\r\n{product_price}<br>', '1');

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES ('abandoned_cart_deleted_discount_notification', '1', 'Abandoned Cart Deleted Discount Notification', 'Abandoned Cart Deleted Discount Notification', '<div style=\"margin:0; padding:0;background: #ecf0f1;\">\r\n	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n					<!--\r\n					header start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n								<td style=\"text-align:right;\">{social_media_icons}</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					header end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n            \r\n            <tr>\r\n                <td style=\"background:#ff3a59;\">\r\n                    <!--\r\n                    page title start here\r\n                    -->\r\n\r\n                    <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n                                    <h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\r\n                                    <h2 style=\"margin:0; color: #999999; font-size:16px; text-transform: uppercase;padding:0;\">Dear {user_full_name}</h2>\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                    <!--\r\n                    page title end here\r\n                    -->\r\n                </td>\r\n            </tr>\r\n            \r\n            <tr>\r\n                <td>\r\n                    <!--\r\n                    page body start here\r\n                    -->\r\n\r\n                    <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"background:#fff;padding:0 20px; text-align:center; color:#999;vertical-align:top;\">\r\n                                    <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                                        <tbody>\r\n                                            <tr>\r\n                                                <td style=\"padding:0 10px; line-height:1.3; text-align:center; color:#333333;vertical-align:top; font-size: 30px;\">We noticed you removed <span style=\"text-decoration: underline;\">{product_name}</span> from your cart.</td>\r\n                                            </tr> \r\n                                               \r\n                                               <tr>\r\n                                                   <td style=\"padding:20px 0 10px 10px; line-height:1.3; text-align:center; color:#999999;vertical-align:top; font-size:16px;\">Just for you : Get <span style=\"color:#333; font-weight: bold;\">{discount}</span> off on your order with code <span style=\"color:#333; font-weight: bold;\">{coupon_code}.</span>\r\n                                                   \r\n                                                       <a href=\"{checkout_now}\" style=\"background: #ff3a59;border:none; border-radius: 4px; color: #fff; cursor: pointer;margin:10px 0 0 0;width: auto; font-weight: normal; padding: 10px 20px; \">Check out now </a></td>\r\n                                            </tr>                                       \r\n                                            <!--\r\n                                            section footer\r\n                                            -->\r\n\r\n                                            <tr>\r\n                                                <td style=\"padding:30px 0;border-top:1px solid #ddd; \">Get in touch in you have any questions regarding our Services.<br />\r\n													Feel free to contact us 24/7. We are here to help.<br />\r\n													<br />\r\n													All the best,<br />\r\n													The {website_name} Team<br />\r\n													</td>\r\n                                            </tr>\r\n                                            <!--\r\n                                            section footer\r\n                                            -->\r\n\r\n                                        </tbody>\r\n                                    </table>\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                    <!--\r\n                    page body end here\r\n                    -->\r\n                </td>\r\n            </tr>\r\n		\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page footer start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"height:30px;\"></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n													 <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">We are here, ready to talk</a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\"><br />\r\n													<br />\r\n													{website_name} Inc.\r\n													<!--\r\n													if these emails get annoying, please feel free to  <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n													-->\r\n													</td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; height:50px;\"></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page footer end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n		</tbody>\r\n	</table></div>', '{user_full_name} Name of the email receiver<br>\r\n{website_name} Name of our website<br>\r\n{website_url} URL of our website<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>\r\n{discount} <br>\r\n{coupon_code} <br>\r\n{checkout_now} <br>', '1');