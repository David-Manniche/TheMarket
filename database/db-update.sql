-- [Avalara Tax API--------
INSERT INTO `tbl_plugins` (`plugin_id`, `plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES (NULL, 'Avalara Tax', '10', 'AvalaraTax', '1', '9');
INSERT INTO `tbl_plugins_lang` (`pluginlang_plugin_id`, `pluginlang_lang_id`, `plugin_name`, `plugin_description`) VALUES
(11, 1, 'Avalara Tax', '<a href=\"https://developer.avalara.com/api-reference/avatax/rest/v2/\">https://developer.avalara.com/api-reference/avatax/rest/v2/</a>'),
(11, 2, 'ضريبة أفالارا', '<a href=\"https://developer.avalara.com/api-reference/avatax/rest/v2/\">https://developer.avalara.com/api-reference/avatax/rest/v2/</a>');

ALTER TABLE `tbl_tax_categories` ADD `taxcat_code` VARCHAR(50) NOT NULL AFTER `taxcat_identifier`;
ALTER TABLE `tbl_tax_categories` ADD `taxcat_plugin_id` INT NOT NULL AFTER `taxcat_code`;

ALTER TABLE `tbl_tax_categories` DROP INDEX `saletaxcat_identifier`;
ALTER TABLE `tbl_tax_categories` DROP INDEX `taxcat_identifier`;
ALTER TABLE `tbl_tax_categories` ADD UNIQUE( `taxcat_identifier`, `taxcat_plugin_id`);
ALTER TABLE `tbl_tax_categories` ADD `taxcat_parent` INT(11) NOT NULL AFTER `taxcat_code`;
INSERT INTO `tbl_plugins` (`plugin_id`, `plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES (NULL, 'TaxJar', '10', 'TaxJarTax', '0', '11');
ALTER TABLE `tbl_order_products_lang` CHANGE `op_product_tax_options` `op_product_tax_options` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `tbl_order_products` ADD `op_tax_code` VARCHAR(150) NOT NULL AFTER `op_actual_shipping_charges`;
ALTER TABLE `tbl_order_user_address` ADD `oua_state_code` VARCHAR(100) NOT NULL AFTER `oua_state`;

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('taxapi_order_creation_failure', 1, 'TaxApi Order Creation Failure Email', 'TaxApi Order Creation Failed at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n				<!--\r\n				header start here\r\n				-->\r\n\r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n					<tbody>\r\n						<tr>\r\n							<td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n							<td style=\"text-align:right;\">{social_media_icons}</td>\r\n						</tr>\r\n					</tbody>\r\n				</table>\r\n				<!--\r\n				header end here\r\n				-->\r\n				   </td>\r\n		</tr>\r\n		<tr>\r\n			<td style=\"background:#ff3a59;\">\r\n				<!--\r\n				page title start here\r\n				-->\r\n\r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n					<tbody>\r\n						<tr>\r\n							<td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n								<h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\r\n								<h2 style=\"margin:0; font-size:34px; padding:0;\">TaxApi Order Creation Failure</h2></td>\r\n						</tr>\r\n					</tbody>\r\n				</table>\r\n				<!--\r\n				page title end here\r\n				-->\r\n				   </td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				<!--\r\n				page body start here\r\n				-->\r\n\r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n					<tbody>\r\n						<tr>\r\n							<td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear Admin </strong><br />\r\n												System has tried to create an order/transaction on TaxApi after order is marked as completed by admin, but not able to create an Order/Transaction on TaxApi due to below Error on your site <a href=\"{website_url}\">{website_name}</a> with Yokart Order Invoice Number {invoice_number}.<br />\r\n												Please find the TaxApi Error information below.</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:0 0 30px;\">{error_message}</td>\r\n										</tr>\r\n										<!--\r\n										section footer\r\n										-->\r\n\r\n										<tr>\r\n											<td style=\"padding:30px 0;border-top:1px solid #ddd;\">Get in touch in you have any questions regarding our Services.<br />\r\n												Feel free to contact us 24/7. We are here to help.<br />\r\n												<br />\r\n												All the best,<br />\r\n												The {website_name} Team<br />\r\n												</td>\r\n										</tr>\r\n										<!--\r\n										section footer\r\n										-->\r\n\r\n									</tbody>\r\n								</table></td>\r\n						</tr>\r\n					</tbody>\r\n				</table>\r\n				<!--\r\n				page body end here\r\n				-->\r\n				   </td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				<!--\r\n				page footer start here\r\n				-->\r\n\r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n					<tbody>\r\n						<tr>\r\n							<td style=\"height:30px;\"></td>\r\n						</tr>\r\n						<tr>\r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n												 <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">We are here, ready to talk</a></td>\r\n										</tr>\r\n									</tbody>\r\n								</table></td>\r\n						</tr>\r\n						<tr>\r\n							<td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\">{website_name} Inc.\r\n												<!--\r\n												if these emails get annoying, please feel free to  <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n												-->\r\n												</td>\r\n										</tr>\r\n									</tbody>\r\n								</table></td>\r\n						</tr>\r\n						<tr>\r\n							<td style=\"padding:0; height:50px;\"></td>\r\n						</tr>\r\n					</tbody>\r\n				</table>\r\n				<!--\r\n				page footer end here\r\n				-->\r\n				   </td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n', '{invoice_number} - Yokart Order Invoice Number.<br/>\r\n{website_name} Name of our website<br>\r\n{website_url} URL of our website<br>\r\n{error_message} -  Error Message received from TaxApi while creating order \r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1);

-- Shippping Module Start-----
ALTER TABLE `tbl_countries` ADD `country_region_id` INT(11) NOT NULL AFTER `country_active`;

CREATE TABLE `tbl_zones` (
  `zone_id` int(11) NOT NULL,
  `zone_identifier` varchar(255) NOT NULL,
  `zone_active` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tbl_zones` (`zone_id`, `zone_identifier`, `zone_active`) VALUES
(1, 'Africa', 1),
(2, 'Asia', 1),
(3, 'Central America', 1),
(4, 'Europe', 1),
(5, 'Middle East', 1),
(6, 'North America', 1),
(7, 'Oceania', 1),
(8, 'South America', 1),
(9, 'The Caribbean', 1);

ALTER TABLE `tbl_zones`
ADD PRIMARY KEY (`zone_id`),
ADD UNIQUE KEY `zone_identifier` (`zone_identifier`);

ALTER TABLE `tbl_zones`
MODIFY `zone_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

CREATE TABLE `tbl_zones_lang` (
  `zonelang_zone_id` int(11) NOT NULL,
  `zonelang_lang_id` int(11) NOT NULL,
  `zone_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tbl_zones_lang`
  ADD PRIMARY KEY (`zonelang_zone_id`,`zonelang_lang_id`),
  ADD UNIQUE KEY `zonelang_lang_id` (`zonelang_lang_id`,`zone_name`);


CREATE TABLE `tbl_shipping_packages` (
  `shippack_id` int(11) NOT NULL,
  `shippack_name` varchar(255) NOT NULL,
  `shippack_length` decimal(10,2) NOT NULL,
  `shippack_width` decimal(10,2) NOT NULL,
  `shippack_height` decimal(10,2) NOT NULL,
  `shippack_units` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tbl_shipping_packages`
  ADD PRIMARY KEY (`shippack_id`),
  ADD UNIQUE KEY `shippack_name` (`shippack_name`);

ALTER TABLE `tbl_shipping_packages`
  MODIFY `shippack_id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `tbl_shipping_profile` (
  `shipprofile_id` int(11) NOT NULL,
  `shipprofile_user_id` int(11) NOT NULL,
  `shipprofile_name` varchar(255) NOT NULL,
  `shipprofile_active` tinyint(1) NOT NULL DEFAULT '1',
  `shipprofile_default` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tbl_shipping_profile` (`shipprofile_id`, `shipprofile_user_id`, `shipprofile_name`, `shipprofile_active`, `shipprofile_default`) VALUES
(1, 0, 'Order Level Shipping', 1, 1);

ALTER TABLE `tbl_shipping_profile`
  ADD PRIMARY KEY (`shipprofile_id`),
  ADD UNIQUE KEY `shipprofile_name` (`shipprofile_name`,`shipprofile_user_id`);
  
ALTER TABLE `tbl_shipping_profile`
  MODIFY `shipprofile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

CREATE TABLE `tbl_shipping_profile_products` (
  `shippro_shipprofile_id` int(11) NOT NULL,
  `shippro_product_id` int(11) NOT NULL,
  `shippro_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tbl_shipping_profile_products`
  ADD PRIMARY KEY (`shippro_product_id`,`shippro_user_id`);

CREATE TABLE `tbl_shipping_profile_zones` (
  `shipprozone_id` int(11) NOT NULL,
  `shipprozone_shipprofile_id` int(11) NOT NULL,
  `shipprozone_shipzone_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tbl_shipping_profile_zones`
  ADD PRIMARY KEY (`shipprozone_id`),
  ADD UNIQUE KEY `shipprozone_shipzone_id` (`shipprozone_shipzone_id`,`shipprozone_shipprofile_id`);
  
ALTER TABLE `tbl_shipping_profile_zones`
  MODIFY `shipprozone_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

  CREATE TABLE `tbl_shipping_rates` (
  `shiprate_id` int(11) NOT NULL,
  `shiprate_shipprozone_id` int(255) NOT NULL,
  `shiprate_identifier` varchar(255) NOT NULL,
  `shiprate_cost` decimal(10,4) NOT NULL,
  `shiprate_condition_type` int(11) NOT NULL DEFAULT '0',
  `shiprate_min_val` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `shiprate_max_val` decimal(10,4) NOT NULL DEFAULT '0.0000'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tbl_shipping_rates`
  ADD PRIMARY KEY (`shiprate_id`);
  
ALTER TABLE `tbl_shipping_rates`
  MODIFY `shiprate_id` int(11) NOT NULL AUTO_INCREMENT;


CREATE TABLE `tbl_shipping_rates_lang` (
  `shipratelang_shiprate_id` int(11) NOT NULL,
  `shipratelang_lang_id` int(11) NOT NULL,
  `shiprate_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tbl_shipping_rates_lang`
  ADD PRIMARY KEY (`shipratelang_shiprate_id`,`shipratelang_lang_id`),
  ADD UNIQUE KEY `ratelang_lang_id` (`shipratelang_lang_id`,`shiprate_name`);


CREATE TABLE `tbl_shipping_zone` (
  `shipzone_id` int(11) NOT NULL,
  `shipzone_user_id` int(11) NOT NULL,
  `shipzone_name` varchar(255) NOT NULL,
  `shipzone_active` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tbl_shipping_zone`
  ADD PRIMARY KEY (`shipzone_id`),
  ADD UNIQUE KEY `shipzone_name` (`shipzone_name`,`shipzone_user_id`);

ALTER TABLE `tbl_shipping_zone`
  MODIFY `shipzone_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `tbl_products` ADD `product_ship_package` INT(11) NOT NULL AFTER `product_deleted`;

CREATE TABLE `tbl_shipping_locations` ( 
  `shiploc_shipzone_id` int(11) NOT NULL,
  `shiploc_zone_id` int(11) NOT NULL,
  `shiploc_country_id` int(11) NOT NULL,
  `shiploc_state_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tbl_shipping_locations`
  ADD UNIQUE KEY `shiploc_shipzone_id` (`shiploc_shipzone_id`,`shiploc_zone_id`,`shiploc_country_id`,`shiploc_state_id`);

ALTER TABLE `tbl_order_product_shipping` ADD `opshipping_level` INT(4) NOT NULL AFTER `opshipping_by_seller_user_id`;  
ALTER TABLE `tbl_order_product_shipping` CHANGE `opshipping_duration_id` `opshipping_rate_id` INT(11) NOT NULL;
ALTER TABLE `tbl_order_product_shipping` DROP `opshipping_max_duration`;
ALTER TABLE `tbl_order_product_shipping` CHANGE `opshipping_pship_id` `opshipping_code` VARCHAR(255) NOT NULL; 
ALTER TABLE `tbl_order_product_shipping` DROP `opshipping_company_id`;
ALTER TABLE `tbl_order_product_shipping` DROP `opshipping_method_id`; 
ALTER TABLE `tbl_order_product_shipping` ADD `opshipping_label` VARCHAR(255) NOT NULL AFTER `opshipping_level`;
ALTER TABLE `tbl_order_product_shipping` ADD `opshipping_carrier_code` VARCHAR(150) NOT NULL AFTER `opshipping_label`, ADD `opshipping_service_code` VARCHAR(150) NOT NULL AFTER `opshipping_carrier_code`;
ALTER TABLE `tbl_order_product_shipping_lang` CHANGE `opshipping_carrier` `opshipping_title` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
-- Shippping Module End-----
ALTER TABLE `tbl_tax_structure_lang` CHANGE `taxstr_name` `taxstr_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) VALUES
("APP_VOICE_SEARCH_TXT", 1, "Tap Here On Mic And Say Something To Search!", 2),
("APP_RESEND_OTP", 1, "Resend OTP", 2),
("APP_CLICK_HERE", 1, "Click Here", 2),
("APP_PLEASE_ENTER_VALID_OTP", 1, "Please Enter Valid OTP", 2),
("APP_SHOW_MORE", 1, "Show More", 2),
("APP_I_AM_LISTENING", 1, "Say Something I Am Listening", 2),
("APP_VOICE_SEARCH", 1, "Voice Search", 2),
("APP_EXPLORE", 1, "Explore", 2);

INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Ship Station', '8', 'ShipStationShipping', '0', '1');
UPDATE `tbl_shipping_apis` SET `shippingapi_identifier` = 'Shipping Services' WHERE `tbl_shipping_apis`.`shippingapi_id` = 2;
UPDATE `tbl_shipping_apis_lang` SET `shippingapi_name` = 'Shipping Services' WHERE `tbl_shipping_apis_lang`.`shippingapilang_shippingapi_id` = 2 AND `tbl_shipping_apis_lang`.`shippingapilang_lang_id` = 1;
UPDATE `tbl_shipping_apis_lang` SET `shippingapi_name` = 'خدمات الشحن' WHERE `tbl_shipping_apis_lang`.`shippingapilang_shippingapi_id` = 2 AND `tbl_shipping_apis_lang`.`shippingapilang_lang_id` = 2;

CREATE TABLE `tbl_order_product_shipment`(
    `opship_op_id` INT(11) NOT NULL,
    `opship_order_id` VARCHAR(150) NOT NULL COMMENT 'From third party',
    `opship_shipment_id` VARCHAR(150) NOT NULL,
    `opship_tracking_number` VARCHAR(150) NOT NULL,
    `opship_response` TEXT NOT NULL
) ENGINE = InnoDB;

ALTER TABLE `tbl_order_product_shipment`
  ADD PRIMARY KEY (`opship_op_id`);
