ALTER TABLE `tbl_currency` DROP `currency_is_default`;

DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Google_Plus_Login';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'MSG_LoggedIn_SUCCESSFULLY';

ALTER TABLE `tbl_users` DROP `user_facebook_id`, DROP `user_googleplus_id`, DROP `user_apple_id`;

CREATE TABLE `tbl_user_meta` ( `usermeta_user_id` INT NOT NULL , `usermeta_key` VARCHAR(255) NOT NULL , `usermeta_value` VARCHAR(255) NOT NULL ) ENGINE = InnoDB;
ALTER TABLE tbl_user_meta ADD PRIMARY KEY (usermeta_user_id, usermeta_key);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_plugins`
--

CREATE TABLE `tbl_plugins` (
  `plugin_id` int(11) NOT NULL,
  `plugin_identifier` varchar(50) NOT NULL,
  `plugin_type` int(11) NOT NULL,
  `plugin_code` varchar(100) NOT NULL,
  `plugin_active` tinyint(1) NOT NULL,
  `plugin_display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `tbl_plugins`
--
ALTER TABLE `tbl_plugins`
  ADD PRIMARY KEY (`plugin_id`),
  ADD UNIQUE KEY `plugin_identifier` (`plugin_identifier`),
  ADD UNIQUE KEY `plugin_code` (`plugin_code`);

--
-- AUTO_INCREMENT for table `tbl_plugins`
--
ALTER TABLE `tbl_plugins`
  MODIFY `plugin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Dumping data for table `tbl_plugins`
--

INSERT INTO `tbl_plugins` (`plugin_id`, `plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES
(1, 'Fixer Currency Converter API', 1, 'FixerCurrencyConverter', 1, 1),
(2, 'Currency Converter API', 1, 'CurrencyConverter', 1, 2),
(3, 'Apple Sign In', 2, 'AppleLogin', 1, 3),
(4, 'Facebook Login', 2, 'FacebookLogin', 1, 4),
(5, 'Google Login', 2, 'GoogleLogin', 1, 5),
(6, 'Instagram Login', 2, 'InstagramLogin', 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_plugins_lang`
--

CREATE TABLE `tbl_plugins_lang` (
  `pluginlang_plugin_id` int(11) NOT NULL,
  `pluginlang_lang_id` int(11) NOT NULL,
  `plugin_name` varchar(200) NOT NULL,
  `plugin_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `tbl_plugins_lang`
--
ALTER TABLE `tbl_plugins_lang`
  ADD PRIMARY KEY (`pluginlang_plugin_id`,`pluginlang_lang_id`);

--
-- Dumping data for table `tbl_plugins_lang`
--

INSERT INTO `tbl_plugins_lang` (`pluginlang_plugin_id`, `pluginlang_lang_id`, `plugin_name`, `plugin_description`) VALUES
(1, 1, 'Fixer Currency Converted API', 'Reference : https://data.fixer.io'),
(1, 2, 'مثبت العملة API المحولة', 'Reference : https://data.fixer.io'),
(2, 1, 'Currency Converter API', 'Reference : https://www.currencyconverterapi.com'),
(2, 2, 'محول العملات API', 'المرجع: https://www.currencyconverterapi.com'),
(3, 1, 'Apple Sign In', ''),
(3, 2, 'أبل تسجيل الدخول', ''),
(4, 1, 'Facebook Login', ''),
(4, 2, 'تسجيل الدخول الى الفيسبوك', ''),
(5, 1, 'Google Login', ''),
(5, 2, 'تسجيل الدخول جوجل', ''),
(6, 1, 'Instagram Login', ''),
(6, 2, 'تسجيل الدخول إلى Instagram', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_plugin_settings`
--

CREATE TABLE `tbl_plugin_settings` (
  `pluginsetting_plugin_id` int(11) NOT NULL,
  `pluginsetting_key` varchar(100) NOT NULL,
  `pluginsetting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `tbl_plugin_settings`
--
ALTER TABLE `tbl_plugin_settings`
  ADD PRIMARY KEY (`pluginsetting_plugin_id`,`pluginsetting_key`);
CREATE TABLE `tbl_shop_specifics` ( `ss_shop_id` INT NOT NULL ,  `shop_return_age` INT NOT NULL COMMENT 'In Days' ,  `shop_cancellation_age` INT NOT NULL COMMENT 'In Days' ) ENGINE = InnoDB;
ALTER TABLE `tbl_shop_specifics` ADD PRIMARY KEY (`ss_shop_id`);

CREATE TABLE `tbl_seller_product_specifics` ( `sps_selprod_id` INT NOT NULL ,  `selprod_return_age` INT NOT NULL COMMENT 'In Days' ,  `selprod_cancellation_age` INT NOT NULL COMMENT 'In Days' ) ENGINE = InnoDB;
ALTER TABLE `tbl_seller_product_specifics` ADD PRIMARY KEY (`sps_selprod_id`);

CREATE TABLE `tbl_product_specifics` ( `ps_product_id` INT NOT NULL ,  `product_warranty` INT NOT NULL COMMENT 'In Days' ) ENGINE = InnoDB;
ALTER TABLE `tbl_product_specifics` ADD PRIMARY KEY (`ps_product_id`);

CREATE TABLE `tbl_order_product_specifics` ( `ops_op_id` INT NOT NULL ,  `op_selprod_return_age` INT NOT NULL COMMENT 'In Days' ,  `op_selprod_cancellation_age` INT NOT NULL COMMENT 'In Days',  `op_product_warranty` INT NOT NULL COMMENT 'In Days' ) ENGINE = InnoDB;
ALTER TABLE `tbl_order_product_specifics` ADD PRIMARY KEY (`ops_op_id`);

INSERT INTO `tbl_cron_schedules` (`cron_id`, `cron_name`, `cron_command`, `cron_duration`, `cron_active`) VALUES (NULL, 'Automating the order completion process', 'Orders/changeOrderStatus', '1440', '1');

CREATE TABLE `tbl_abandoned_cart` (
  `abandonedcart_id` int(11) NOT NULL,
  `abandonedcart_user_id` int(11) NOT NULL,
  `abandonedcart_selprod_id` int(11) NOT NULL,
  `abandonedcart_type` tinyint(1) NOT NULL COMMENT 'Defined in model	',
  `abandonedcart_qty` int(11) NOT NULL,
  `abandonedcart_amount` decimal(10,2) NOT NULL,
  `abandonedcart_action` tinyint(1) NOT NULL COMMENT 'Defined in model',
  `abandonedcart_email_count` int(11) NOT NULL,
  `abandonedcart_discount_notification` tinyint(1) NOT NULL,
  `abandonedcart_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tbl_abandoned_cart`
  ADD PRIMARY KEY (`abandonedcart_id`);

ALTER TABLE `tbl_abandoned_cart`
  MODIFY `abandonedcart_id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `tbl_cron_schedules` (`cron_id`, `cron_name`, `cron_command`, `cron_duration`, `cron_active`) VALUES (NULL, 'Abandoned Cart Reminder Email', 'CartHistory/sendReminderAbandonedCart', '600', '1');

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES ('abandoned_cart_email', '1', 'Abandoned Cart Email', 'Abandoned Cart Email', '<div style=\"margin:0; padding:0;background: #ecf0f1;\">\r\n	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n					<!--\r\n					header start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n								<td style=\"text-align:right;\">{social_media_icons}</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					header end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n            \r\n            <tr>\r\n                <td style=\"background:#ff3a59;\">\r\n                    <!--\r\n                    page title start here\r\n                    -->\r\n\r\n                    <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n                                    <h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\r\n                                    <h2 style=\"margin:0; color: #999999; font-size:16px; text-transform: uppercase;padding:0;\">Dear {user_full_name}</h2>\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                    <!--\r\n                    page title end here\r\n                    -->\r\n                </td>\r\n            </tr>\r\n            \r\n            <tr>\r\n                <td>\r\n                    <!--\r\n                    page body start here\r\n                    -->\r\n\r\n                    <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"background:#fff;padding:0 20px; text-align:center; color:#999;vertical-align:top;\">\r\n                                    <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                                        <tbody>\r\n                                            <tr>\r\n                                                <td style=\"padding:0 10px; line-height:1.3; text-align:center; color:#333333;vertical-align:top; font-size: 30px;\">We noticed you left something behind!</td>\r\n                                            </tr>\r\n                                            <tr>\r\n                                                <td style=\"padding:30px 0;\">\r\n                                                <table>{product_detail_table}</table>\r\n                                                </td>\r\n                                            </tr>\r\n                                            <!--\r\n                                            section footer\r\n                                            -->\r\n\r\n                                            <tr>\r\n                                                <td style=\"padding:30px 0;border-top:1px solid #ddd;\">Get in touch in you have any questions regarding our Services.<br />\r\n													Feel free to contact us 24/7. We are here to help.<br />\r\n													<br />\r\n													All the best,<br />\r\n													The {website_name} Team<br />\r\n                                                </td>\r\n                                            </tr>\r\n                                            <!--\r\n                                            section footer\r\n                                            -->\r\n\r\n                                        </tbody>\r\n                                    </table>\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                    <!--\r\n                    page body end here\r\n                    -->\r\n                </td>\r\n            </tr>\r\n\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page footer start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"height:30px;\"></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n													 <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">We are here, ready to talk</a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\"><br />\r\n													<br />\r\n													{website_name} Inc.\r\n													<!--\r\n													if these emails get annoying, please feel free to  <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n													-->\r\n													</td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; height:50px;\"></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page footer end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n		</tbody>\r\n	</table></div>', '{user_full_name} Name of the email receiver<br>\r\n{website_name} Name of our website<br>\r\n{website_url} URL of our website<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>\r\n{product_detail_table} <br/>', '1');

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES ('abandoned_cart_discount_notification', '1', 'Abandoned Cart Discount Notification', 'Abandoned Cart Discount Notification', '<div style=\"margin:0; padding:0;background: #ecf0f1;\">\r\n	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n					<!--\r\n					header start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n								<td style=\"text-align:right;\">{social_media_icons}</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					header end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n            \r\n             <tr>\r\n                <td style=\"background:#ff3a59;\">\r\n                    <!--\r\n                    page title start here\r\n                    -->\r\n                    <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n                                    <h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\r\n                                    <h2 style=\"margin:0; color: #999999; font-size:16px; text-transform: uppercase;padding:0;\">Dear {user_full_name}</h2>\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                    <!--\r\n                    page title end here\r\n                    -->\r\n                </td>\r\n            </tr>\r\n            \r\n            <tr>\r\n                <td>\r\n                    <!--\r\n                    page body start here\r\n                    -->\r\n\r\n                    <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"background:#fff;padding:0 20px; text-align:center; color:#999;vertical-align:top;\">\r\n                                    <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                                        <tbody>\r\n                                            <tr>\r\n                                                <td style=\"padding:0 10px; line-height:1.3; text-align:center; color:#333333;vertical-align:top; font-size: 30px;\">Finish your order before your items sell out!</td>\r\n                                            </tr> \r\n                                               \r\n                                               <tr>\r\n                                                   <td style=\"padding:20px 0 10px 10px; line-height:1.3; text-align:center; color:#999999;vertical-align:top; font-size:16px;\">Just for you : Get <span style=\"color:#333; font-weight: bold;\">{discount} OFF</span> off on your order with code <span style=\"color:#333; font-weight: bold;\">{coupon_code}.</span>\r\n                                                   \r\n                                                       <a href=\"{checkout_now}\" style=\"background: #ff3a59;border:none; border-radius: 4px; color: #fff; cursor: pointer;margin:10px 0 0 0;width: auto; font-weight: normal; padding: 10px 20px; display: inline-block;\">Check out now </a></td>\r\n                                            </tr>\r\n                                            <tr>\r\n                                                <td style=\"padding:30px 0;\">\r\n                                                    <table>\r\n                                                        <tr>\r\n                                                            <td style=\"padding-right: 25px;\"><img style=\"border: solid 1px #ececec; padding: 10px; border-radius: 4px;\" src=\"{product_image}\"></td>\r\n                                                            <td style="text-align: left;">\r\n                                                                <span style=\"font-size: 20px; font-weight:normal; color:#999999; \">{product_name}</span>\r\n                                                                 <span style=\"font-size: 14px; font-weight: bold; color:#000000; display: block; padding: 20px 0;\">{product_price}</span>\r\n                                                            </td>\r\n                                                        </tr>\r\n                                                    </table>\r\n\r\n                                                </td>\r\n                                            </tr>\r\n                                            <!--\r\n                                            section footer\r\n                                            -->\r\n\r\n                                            <tr>\r\n                                                <td style=\"padding:30px 0;border-top:1px solid #ddd;\">Get in touch in you have any questions regarding our Services.<br />\r\n													Feel free to contact us 24/7. We are here to help.<br />\r\n													<br />\r\n													All the best,<br />\r\n													The {website_name} Team<br />\r\n                                                </td>\r\n                                            </tr>\r\n                                            <!--\r\n                                            section footer\r\n                                            -->\r\n\r\n                                        </tbody>\r\n                                    </table>\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                    <!--\r\n                    page body end here\r\n                    -->\r\n                </td>\r\n            </tr>\r\n        \r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page footer start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"height:30px;\"></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n													 <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">We are here, ready to talk</a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\"><br />\r\n													<br />\r\n													{website_name} Inc.\r\n													<!--\r\n													if these emails get annoying, please feel free to  <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n													-->\r\n													</td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; height:50px;\"></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page footer end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n		</tbody>\r\n	</table></div>', '{user_full_name} Name of the email receiver<br>\r\n{website_name} Name of our website<br>\r\n{website_url} URL of our website<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>\r\n{discount} <br>\r\n{coupon_code} <br>\r\n{checkout_now} <br>\r\n{product_image} <br>\r\n{product_name} <br>\r\n{product_price}<br>', '1');

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES ('abandoned_cart_deleted_discount_notification', '1', 'Abandoned Cart Deleted Discount Notification', 'Abandoned Cart Deleted Discount Notification', '<div style=\"margin:0; padding:0;background: #ecf0f1;\">\r\n	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n		<tbody>\r\n			<tr>\r\n				<td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n					<!--\r\n					header start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n								<td style=\"text-align:right;\">{social_media_icons}</td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					header end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n            \r\n            <tr>\r\n                <td style=\"background:#ff3a59;\">\r\n                    <!--\r\n                    page title start here\r\n                    -->\r\n\r\n                    <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n                                    <h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\r\n                                    <h2 style=\"margin:0; color: #999999; font-size:16px; text-transform: uppercase;padding:0;\">Dear {user_full_name}</h2>\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                    <!--\r\n                    page title end here\r\n                    -->\r\n                </td>\r\n            </tr>\r\n            \r\n            <tr>\r\n                <td>\r\n                    <!--\r\n                    page body start here\r\n                    -->\r\n\r\n                    <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                        <tbody>\r\n                            <tr>\r\n                                <td style=\"background:#fff;padding:0 20px; text-align:center; color:#999;vertical-align:top;\">\r\n                                    <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                                        <tbody>\r\n                                            <tr>\r\n                                                <td style=\"padding:0 10px; line-height:1.3; text-align:center; color:#333333;vertical-align:top; font-size: 30px;\">We noticed you removed <span style=\"text-decoration: underline;\">{product_name}</span> from your cart.</td>\r\n                                            </tr> \r\n                                               \r\n                                               <tr>\r\n                                                   <td style=\"padding:20px 0 10px 10px; line-height:1.3; text-align:center; color:#999999;vertical-align:top; font-size:16px;\">Just for you : Get <span style=\"color:#333; font-weight: bold;\">{discount}</span> off on your order with code <span style=\"color:#333; font-weight: bold;\">{coupon_code}.</span>\r\n                                                   \r\n                                                       <a href=\"{checkout_now}\" style=\"background: #ff3a59;border:none; border-radius: 4px; color: #fff; cursor: pointer;margin:10px 0 0 0;width: auto; font-weight: normal; padding: 10px 20px; display: inline-block; \">Check out now </a></td>\r\n                                            </tr>                                       \r\n                                            <!--\r\n                                            section footer\r\n                                            -->\r\n\r\n                                            <tr>\r\n                                                <td style=\"padding:30px 0;border-top:1px solid #ddd; \">Get in touch in you have any questions regarding our Services.<br />\r\n													Feel free to contact us 24/7. We are here to help.<br />\r\n													<br />\r\n													All the best,<br />\r\n													The {website_name} Team<br />\r\n													</td>\r\n                                            </tr>\r\n                                            <!--\r\n                                            section footer\r\n                                            -->\r\n\r\n                                        </tbody>\r\n                                    </table>\r\n                                </td>\r\n                            </tr>\r\n                        </tbody>\r\n                    </table>\r\n                    <!--\r\n                    page body end here\r\n                    -->\r\n                </td>\r\n            </tr>\r\n		\r\n			<tr>\r\n				<td>\r\n					<!--\r\n					page footer start here\r\n					-->\r\n					   \r\n					<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n						<tbody>\r\n							<tr>\r\n								<td style=\"height:30px;\"></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n													 <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">We are here, ready to talk</a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n									<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n										<tbody>\r\n											<tr>\r\n												<td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\"><br />\r\n													<br />\r\n													{website_name} Inc.\r\n													<!--\r\n													if these emails get annoying, please feel free to  <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n													-->\r\n													</td>\r\n											</tr>\r\n										</tbody>\r\n									</table></td>\r\n							</tr>\r\n							<tr>\r\n								<td style=\"padding:0; height:50px;\"></td>\r\n							</tr>\r\n						</tbody>\r\n					</table>\r\n					<!--\r\n					page footer end here\r\n					-->\r\n					   </td>\r\n			</tr>\r\n		</tbody>\r\n	</table></div>', '{user_full_name} Name of the email receiver<br>\r\n{website_name} Name of our website<br>\r\n{website_url} URL of our website<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>\r\n{discount} <br>\r\n{coupon_code} <br>\r\n{checkout_now} <br>', '1');

CREATE TABLE `tbl_push_notifications` (
  `pnotification_id` int(11) NOT NULL,
  `pnotification_type` tinyint(1) NOT NULL,
  `pnotification_lang_id` tinyint(4) NOT NULL,
  `pnotification_title` varchar(200) CHARACTER SET utf8 NOT NULL,
  `pnotification_description` varchar(255) CHARACTER SET utf8 NOT NULL,
  `pnotification_url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `pnotification_notified_on` datetime NOT NULL,
  `pnotification_for_buyer` tinyint(1) NOT NULL,
  `pnotification_for_seller` tinyint(1) NOT NULL,
  `pnotification_till_user_id` int(11) NOT NULL,
  `pnotification_status` tinyint(1) NOT NULL,
  `pnotification_added_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `tbl_push_notification_to_users` ( `pntu_pnotification_id` INT NOT NULL ,  `pntu_user_id` INT NOT NULL ) ENGINE = InnoDB;
ALTER TABLE  `tbl_push_notification_to_users` ADD PRIMARY KEY (`pntu_pnotification_id`, `pntu_user_id`);
DELETE FROM `tbl_language_labels` WHERE `label_key` = 'LBL_Title';
DELETE FROM `tbl_language_labels` WHERE `label_key` = 'LBL_S.No';
DELETE FROM `tbl_language_labels` WHERE `label_key` = 'LBL_Does_not_Matter';

INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('FCM Push Notification', '3', 'FcmPushNotification', '1', '7');

ALTER TABLE `tbl_push_notifications`
  ADD PRIMARY KEY (`pnotification_id`);
ALTER TABLE `tbl_push_notifications`
  MODIFY `pnotification_id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `tbl_cron_schedules` (`cron_id`, `cron_name`, `cron_command`, `cron_duration`, `cron_active`) VALUES (NULL, 'Send FCM Push Notifications', 'PushNotification/send', '15', '1');
DELETE FROM `tbl_attached_files` WHERE `afile_type` = 12 and `afile_screen` = 0;
DELETE FROM `tbl_attached_files` WHERE `afile_type` = 52 and `afile_screen` = 0;

INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Google Shopping Feed', '5', 'GoogleShoppingFeed', '1', '1');

CREATE TABLE `tbl_ads_batches` (
  `adsbatch_id` int(11) NOT NULL AUTO_INCREMENT,
  `adsbatch_user_id` int(11) NOT NULL,
  `adsbatch_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `adsbatch_lang_id` tinyint(2) NOT NULL,
  `adsbatch_target_country_id` tinyint(2) NOT NULL,
  `adsbatch_expired_on` datetime NOT NULL,
  `adsbatch_synced_on` datetime NOT NULL,
  `adsbatch_status` tinyint(2) NOT NULL,
  `adsbatch_added_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(`adsbatch_id`)
) ENGINE=InnoDB;

CREATE TABLE `tbl_ads_batch_products`(
   `abprod_adsbatch_id` INT NOT NULL,
   `abprod_selprod_id` INT NOT NULL,
   `abprod_cat_id` INT NOT NULL COMMENT 'Google Product Category',
   `abprod_age_group` VARCHAR(15) NOT NULL,
   `abprod_item_group_identifier` varchar(100) NOT NULL,
   `abprod_product_info` TEXT NOT NULL,
   PRIMARY KEY(`abprod_adsbatch_id`, `abprod_selprod_id`)
) ENGINE = InnoDB;

DELETE FROM `tbl_language_labels` WHERE `label_key` = 'LBL_EAN/UPC_code';

ALTER TABLE `tbl_user_meta` CHANGE `usermeta_value` `usermeta_value` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;