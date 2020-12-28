-- [Avalara Tax API--------
INSERT INTO `tbl_plugins` (`plugin_id`, `plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES (NULL, 'Avalara Tax', '10', 'AvalaraTax', '1', '9');

INSERT INTO `tbl_plugins_lang` (`pluginlang_plugin_id`, `pluginlang_lang_id`, `plugin_name`, `plugin_description`) VALUES (11, 1, 'Avalara Tax', '<a href=\"https://developer.avalara.com/api-reference/avatax/rest/v2/\">https://developer.avalara.com/api-reference/avatax/rest/v2/</a>'),
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

INSERT IGNORE INTO `tbl_zones` (`zone_id`, `zone_identifier`, `zone_active`) VALUES
(1, 'Africa', 1),
(2, 'Asia', 1),
(3, 'Central America', 1),
(4, 'Europe', 1),
(5, 'Middle East', 1),
(6, 'North America', 1),
(7, 'Oceania', 1),
(8, 'South America', 1),
(9, 'The Caribbean', 1),
(10, 'Antarctica', 1);


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

-- Tax Upgrade-----
DROP TABLE `tbl_tax_structure`;
DROP TABLE `tbl_tax_structure_lang`;

CREATE TABLE `tbl_tax_rules` (
  `taxrule_id` int(11) NOT NULL,
  `taxrule_taxcat_id` int(11) NOT NULL,
  `taxrule_name` varchar(255) NOT NULL,
  `taxrule_rate` decimal(10,2) NOT NULL,
  `taxrule_is_combined` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_tax_rule_details` (
  `taxruledet_id` int(11) NOT NULL,
  `taxruledet_taxrule_id` int(11) NOT NULL,
  `taxruledet_identifier` varchar(255) NOT NULL,
  `taxruledet_rate` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_tax_rule_details_lang` (
  `taxruledetlang_taxruledet_id` int(11) NOT NULL,
  `taxruledetlang_lang_id` int(11) NOT NULL,
  `taxruledet_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_tax_rule_locations` (
  `taxruleloc_taxcat_id` int(11) NOT NULL,
  `taxruleloc_taxrule_id` int(11) NOT NULL,
  `taxruleloc_country_id` int(11) NOT NULL,
  `taxruleloc_state_id` int(11) NOT NULL,
  `taxruleloc_type` int(11) DEFAULT NULL COMMENT 'including or excluding',
  `taxruleloc_unique` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tbl_tax_rules`
  ADD PRIMARY KEY (`taxrule_id`);

ALTER TABLE `tbl_tax_rule_details`
  ADD PRIMARY KEY (`taxruledet_id`);

ALTER TABLE `tbl_tax_rule_details_lang`
  ADD PRIMARY KEY (`taxruledetlang_taxruledet_id`,`taxruledetlang_lang_id`);

ALTER TABLE `tbl_tax_rule_locations`
  ADD UNIQUE KEY `taxruleloc_taxcat_id` (`taxruleloc_taxcat_id`,`taxruleloc_country_id`,`taxruleloc_state_id`,`taxruleloc_type`,`taxruleloc_unique`);

ALTER TABLE `tbl_tax_rules`
  MODIFY `taxrule_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_tax_rule_details`
  MODIFY `taxruledet_id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `tbl_order_prod_charges_logs` (
  `opchargelog_id` int(11) NOT NULL,
  `opchargelog_op_id` int(11) NOT NULL,
  `opchargelog_type` int(11) NOT NULL,
  `opchargelog_identifier` varchar(255) NOT NULL,
  `opchargelog_value` decimal(10,2) NOT NULL,
  `opchargelog_is_percent` tinyint(4) NOT NULL,
  `opchargelog_percentvalue` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `tbl_order_prod_charges_logs`
  ADD PRIMARY KEY (`opchargelog_id`);
ALTER TABLE `tbl_order_prod_charges_logs`
  MODIFY `opchargelog_id` int(11) NOT NULL AUTO_INCREMENT;
CREATE TABLE `tbl_order_prod_charges_logs_lang` (
  `opchargeloglang_opchargelog_id` int(11) NOT NULL,
  `opchargeloglang_op_id` int(11) NOT NULL,
  `opchargeloglang_lang_id` int(11) NOT NULL,
  `opchargelog_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- Tax Upgrade End-----

ALTER TABLE `tbl_attached_files` ADD `afile_attribute_title` VARCHAR(250) NOT NULL AFTER `afile_name`, ADD `afile_attribute_alt` VARCHAR(250) NOT NULL AFTER `afile_attribute_title`;

--
-- Stripe Connect Plugin
--

INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Stripe Connect', 11, 'StripeConnect', 0, 1);

ALTER TABLE `tbl_order_return_requests` CHANGE `orrequest_refund_in_wallet` `orrequest_refund_in_wallet` TINYINT(1) NOT NULL COMMENT 'Defined In PaymentMethods Model';
ALTER TABLE `tbl_order_return_requests` ADD `orrequest_payment_gateway_req_id` VARCHAR(255) NOT NULL AFTER `orrequest_status`;

ALTER TABLE `tbl_order_cancel_requests` CHANGE `ocrequest_refund_in_wallet` `ocrequest_refund_in_wallet` TINYINT(1) NOT NULL COMMENT 'Defined In PaymentMethods Model';
ALTER TABLE `tbl_order_cancel_requests` ADD `ocrequest_payment_gateway_req_id` VARCHAR(255) NOT NULL AFTER `ocrequest_status`;

ALTER TABLE `tbl_user_transactions`  ADD `utxn_gateway_txn_id` VARCHAR(150) NOT NULL  AFTER `utxn_debit`;

-- Stripe Connect Module End-----

INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) VALUES
("APP_VOICE_SEARCH_TXT", 1, "Tap Here On Mic And Say Something To Search!", 2),
("APP_RESEND_OTP", 1, "Resend OTP", 2),
("APP_CLICK_HERE", 1, "Click Here", 2),
("APP_PLEASE_ENTER_VALID_OTP", 1, "Please Enter Valid OTP", 2),
("APP_SHOW_MORE", 1, "Show More", 2),
("APP_I_AM_LISTENING", 1, "Say Something I Am Listening", 2),
("APP_VOICE_SEARCH", 1, "Voice Search", 2),
("APP_EXPLORE", 1, "Explore", 2)
ON DUPLICATE KEY UPDATE label_caption = VALUES(label_caption);

--
-- ShipStation Plugin
--

  INSERT IGNORE INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Ship Station', '8', 'ShipStationShipping', '0', '1');
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

-- ShipStation Module End-----

-- auto detect location search
ALTER TABLE `tbl_shops` ADD `shop_lat` VARCHAR(100) NOT NULL AFTER `shop_free_ship_upto`, ADD `shop_lng` VARCHAR(100) NOT NULL AFTER `shop_lat`;
-- auto detect location


-- Moving Regular Payment Methods To Plugins --

INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Stripe', '13', 'Stripe', '0', '1');
INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Amazon', '13', 'Amazon', '0', '1');
INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Authorize Aim', '13', 'AuthorizeAim', '0', '1');
INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Braintree', '13', 'Braintree', '0', '1');
INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Cash On Delivery', '13', 'CashOnDelivery', '0', '1');
INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Ccavenue', '13', 'Ccavenue', '0', '1');
INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Citrus', '13', 'Citrus', '0', '1');
INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Ebs', '13', 'Ebs', '0', '1');
INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Khipu', '13', 'Khipu', '0', '1');
INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Omise', '13', 'Omise', '0', '1');
INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('PayFort', '13', 'PayFort', '0', '1');

-- PayFort Start not required --
-- INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('PayFortStart', '13', 'PayFortStart', '0', '1'); --
-- PayFort Start not required --

INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Paypal Standard', '13', 'PaypalStandard', '0', '1');
INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Paytm', '13', 'Paytm', '0', '1');
INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('PayuIndia', '13', 'PayuIndia', '0', '1');
INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('PayuMoney', '13', 'PayuMoney', '0', '1');
INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Razorpay', '13', 'Razorpay', '0', '1');

INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Twocheckout', '13', 'Twocheckout', '0', '1');

INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Transfer Bank', '13', 'TransferBank', '0', '1');

UPDATE tbl_orders o
INNER JOIN tbl_payment_methods pm ON pm.pmethod_id = o.order_pmethod_id
INNER JOIN tbl_plugins p ON p.plugin_code = pm.pmethod_code
SET o.order_pmethod_id = p.plugin_id
WHERE o.order_pmethod_id > 0;

DROP TABLE `tbl_payment_methods`;
DROP TABLE `tbl_payment_methods_lang`;
DROP TABLE `tbl_payment_method_settings`;
-- End --

-- Shipstation Shipping API --
ALTER TABLE `tbl_order_product_shipment` CHANGE `opship_response` `opship_response` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
-- End --

ALTER TABLE `tbl_url_rewrite` ADD `urlrewrite_lang_id` INT(11) NOT NULL DEFAULT '1' AFTER `urlrewrite_custom`;
ALTER TABLE `tbl_url_rewrite` DROP INDEX `url_rewrite_original`;
ALTER TABLE `tbl_url_rewrite` ADD UNIQUE( `urlrewrite_original`, `urlrewrite_lang_id`);
ALTER TABLE `tbl_url_rewrite` DROP INDEX `url_rewrite_custom`;
ALTER TABLE `tbl_url_rewrite` ADD UNIQUE( `urlrewrite_custom`, `urlrewrite_lang_id`);


-- Hot Fixes TV-9.2.0 --

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('primary_order_bank_transfer_payment_status_admin', 1, 'Admin - Primary Order Payment Status', 'Payment Status at {website_name}', '<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tr>\r\n        <td style=\"background:#ff3a59;\">\r\n            <!--\r\n            page title start here\r\n            -->\r\n               \r\n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n                            <h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\">Order Placed</h4>\r\n                            <h2 style=\"margin:0; font-size:34px; padding:0;\">{order_payment_method}</h2></td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--\r\n            page title end here\r\n            -->\r\n               </td>\r\n    </tr>\r\n    <tr>\r\n        <td>\r\n            <!--\r\n            page body start here\r\n            -->\r\n               \r\n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n                            <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                                <tbody>\r\n                                    <tr>\r\n                                        <td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear Admin </strong><br />\r\n                                            order has been placed to {order_payment_method} corresponding to Order Invoice Number - {invoice_number} at <a href=\"{website_url}\">{website_name}</a>.</td>\r\n                                    </tr>\r\n                                    \r\n                                </tbody>\r\n                            </table></td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--\r\n            page body end here\r\n            -->\r\n               </td>\r\n    </tr>\r\n</table>', '{user_full_name} - Name of the email receiver.<br/>\r\n{website_name} Name of our website<br>\r\n{order_payment_method} Order payment method (Bank Transfer) <br>\r\n{invoice_number} Invoice Number of the order<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1);

INSERT INTO `tbl_sms_templates` (`stpl_code`, `stpl_lang_id`, `stpl_name`, `stpl_body`, `stpl_replacements`, `stpl_status`) VALUES
('primary_order_bank_transfer_payment_status_admin', 1, 'Bank Transfer Order Payment Status', 'Hello Admin,\r\n{order_payment_method} order has been placed with Order Invoice Number - {invoice_number}.\r\n\r\n{SITE_NAME} Team', '[{\"title\":\"Payment Method\", \"variable\":\"{order_payment_method}\"},{\"title\":\"Invoice Number\", \"variable\":\"{invoice_number}\"}, {\"title\":\"Website Name\", \"variable\":\"{SITE_NAME}\"}]', 1);

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('primary_order_bank_transfer_payment_status_buyer', 1, 'Buyers - Primary Order Payment Status', 'Order Payment Status at {website_name}', '<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tr>\r\n        <td style=\"background:#ff3a59;\">\r\n            <!--\r\n            page title start here\r\n            -->\r\n               \r\n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n                            <h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\">Order Placed</h4>\r\n                            <h2 style=\"margin:0; font-size:34px; padding:0;\">{order_payment_method}</h2></td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--\r\n            page title end here\r\n            -->\r\n               </td>\r\n    </tr>\r\n    <tr>\r\n        <td>\r\n            <!--\r\n            page body start here\r\n            -->\r\n               \r\n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n                            <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                                <tbody>\r\n                                    <tr>\r\n                                        <td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear {user_full_name} </strong><br />\r\n                                            Your order has been placed to {order_payment_method} corresponding to Order Invoice Number - {invoice_number} at <a href=\"{website_url}\">{website_name}</a>.</td>\r\n                                    </tr>\r\n                                    \r\n                                </tbody>\r\n                            </table></td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--\r\n            page body end here\r\n            -->\r\n               </td>\r\n    </tr>\r\n</table>', '{user_full_name} - Name of the email receiver.<br/>\r\n{website_name} Name of our website<br>\r\n{order_payment_method} Order payment method (Bank Transfer) <br>\r\n{invoice_number} Invoice Number of the order<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1);

INSERT INTO `tbl_sms_templates` (`stpl_code`, `stpl_lang_id`, `stpl_name`, `stpl_body`, `stpl_replacements`, `stpl_status`) VALUES
('primary_order_bank_transfer_payment_status_buyer', 1, 'Bank Transfer', 'Hello {user_full_name},\r\nOrder #{invoice_number} has been placed with payment status as Bank Transfer on {SITE_NAME}.\r\n\r\n{SITE_NAME} Team', '[{\"title\":\"User Full Name\", \"variable\":\"{user_full_name}\"},{\"title\":\"Invoice Number\", \"variable\":\"{invoice_number}\"}, {\"title\":\"Website Name\", \"variable\":\"{SITE_NAME}\"}]', 1);

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('vendor_bank_transfer_order_email', 1, 'Vendor Bank Transfer Order Email', 'Order Received From {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333; line-height:26px;\">\r\n <tbody>\r\n   <tr>\r\n      <td style=\"background:#ff3a59;padding:30px 0 10px;\">\r\n        <!--\r\n        header start here\r\n       -->\r\n          \r\n       <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n         <tbody>\r\n           <tr>\r\n              <td><a href=\"{website_url}\">{Company_Logo}</a></td>\r\n             <td style=\"text-align:right;\">{social_media_icons}</td>\r\n           </tr>\r\n         </tbody>\r\n        </table>\r\n        <!--\r\n        header end here\r\n       -->\r\n          </td>\r\n    </tr>\r\n   <tr>\r\n      <td style=\"background:#ff3a59;\">\r\n        <!--\r\n        page title start here\r\n       -->\r\n          \r\n       <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n         <tbody>\r\n           <tr>\r\n              <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n                <h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\">Order Placed</h4>\r\n                <h2 style=\"margin:0; font-size:34px; padding:0;\">Bank Transfer</h2></td>\r\n            </tr>\r\n         </tbody>\r\n        </table>\r\n        <!--\r\n        page title end here\r\n       -->\r\n          </td>\r\n    </tr>\r\n   <tr>\r\n      <td>\r\n        <!--\r\n        page body start here\r\n        -->\r\n          \r\n       <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n         <tbody>\r\n           <tr>\r\n              <td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n                <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                  <tbody>\r\n                   <tr>\r\n                      <td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear {vendor_name} </strong><br />\r\n                        An order has been placed for your product(s) at <a href=\"{website_url}\">{website_name}</a>.<br />\r\n                       Order details &amp; Shipping information are given below:</td>\r\n                    </tr>\r\n                   <tr>\r\n                      <td style=\"padding:5px 0 30px;\">{order_items_table_format}</td>\r\n                   </tr>\r\n                   <!--\r\n                    section footer\r\n                    -->\r\n                      \r\n                   <tr>\r\n                      <td style=\"padding:30px 0;border-top:1px solid #ddd;\">Get in touch in you have any questions regarding our Services.<br />\r\n                        Feel free to contact us 24/7. We are here to help.<br />\r\n                        <br />\r\n                        All the best,<br />\r\n                       The {website_name} Team<br />\r\n                       </td>\r\n                   </tr>\r\n                   <!--\r\n                    section footer\r\n                    -->\r\n                      \r\n                 </tbody>\r\n                </table></td>\r\n           </tr>\r\n         </tbody>\r\n        </table>\r\n        <!--\r\n        page body end here\r\n        -->\r\n          </td>\r\n    </tr>\r\n   <tr>\r\n      <td>\r\n        <!--\r\n        page footer start here\r\n        -->\r\n          \r\n       <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n         <tbody>\r\n           <tr>\r\n              <td style=\"height:30px;\"></td>\r\n            </tr>\r\n           <tr>\r\n              <td style=\"background:rgba(0,0,0,0.04);padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n                <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                  <tbody>\r\n                   <tr>\r\n                      <td style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n                        <a href=\"{contact_us_url}\" style=\"color:#ff3a59;\">We are here, ready to talk</a></td>\r\n                    </tr>\r\n                 </tbody>\r\n                </table></td>\r\n           </tr>\r\n           <tr>\r\n              <td style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n                <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                  <tbody>\r\n                   <tr>\r\n                      <td style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\">{website_name} Inc.\r\n                       <!--\r\n                        if these emails get annoying, please feel free to  <a href=\"#\" style=\"text-decoration:underline; color:#666;\">unsubscribe</a>.\r\n                        -->\r\n                       </td>\r\n                   </tr>\r\n                 </tbody>\r\n                </table></td>\r\n           </tr>\r\n           <tr>\r\n              <td style=\"padding:0; height:50px;\"></td>\r\n           </tr>\r\n         </tbody>\r\n        </table>\r\n        <!--\r\n        page footer end here\r\n        -->\r\n          </td>\r\n    </tr>\r\n </tbody>\r\n</table>', '{vendor_name} Name of the vendor<br/>\r\n{website_name} Name of our website<br>\r\n{website_url} URL of our website<br>\r\n{order_items_table_format} Order items in Tabular Format.<br>\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1);

-- End --

-- Addresses Start--------------------------
--
-- Table structure for table `tbl_addresses`
--

CREATE TABLE `tbl_addresses` (
  `addr_id` int(11) NOT NULL,
  `addr_type` int(11) NOT NULL,
  `addr_record_id` int(11) NOT NULL,
  `addr_added_by` int(11) NOT NULL,
  `addr_lang_id` int(11) NOT NULL,
  `addr_title` varchar(255) NOT NULL,
  `addr_name` varchar(255) NOT NULL,
  `addr_address1` varchar(255) NOT NULL,
  `addr_address2` varchar(255) NOT NULL,
  `addr_city` varchar(255) NOT NULL,
  `addr_state_id` int(11) NOT NULL,
  `addr_country_id` int(11) NOT NULL,
  `addr_phone` varchar(100) NOT NULL,
  `addr_zip` varchar(20) NOT NULL,
  `addr_lat` varchar(150) NOT NULL,
  `addr_lng` varchar(150) NOT NULL,
  `addr_is_default` tinyint(1) NOT NULL,
  `addr_deleted` tinyint(1) NOT NULL,
  `addr_updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_addresses`
--
ALTER TABLE `tbl_addresses`
  ADD PRIMARY KEY (`addr_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_addresses`
--
ALTER TABLE `tbl_addresses`
  MODIFY `addr_id` int(11) NOT NULL AUTO_INCREMENT;

INSERT into `tbl_addresses` (`addr_type`, `addr_record_id`, `addr_lang_id`, `addr_title`, `addr_name`, `addr_address1`, `addr_address2`, `addr_city`, `addr_state_id`, `addr_country_id`, `addr_phone`, `addr_zip`, `addr_is_default`, `addr_deleted`) select * from (SELECT 1 as addr_type, `ua_user_id`, 1 as addr_lang_id, `ua_identifier`, `ua_name`, `ua_address1`, `ua_address2`, `ua_city`, `ua_state_id`, `ua_country_id`, `ua_phone`, `ua_zip`, `ua_is_default`, `ua_deleted` from `tbl_user_address`) as temp;
DROP TABLE tbl_user_address;
-- Addresses End--------------------------

-- Pickup Location start-----------------

--
-- Table structure for table `tbl_user_collections`
--

CREATE TABLE `tbl_user_collections` (
  `uc_user_id` int(11) NOT NULL,
  `uc_type` int(11) NOT NULL,
  `uc_record_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_user_collections`
--
ALTER TABLE `tbl_user_collections`
  ADD PRIMARY KEY (`uc_user_id`,`uc_type`,`uc_record_id`);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_time_slots`
--

CREATE TABLE `tbl_time_slots` (
  `tslot_id` int(11) NOT NULL,
  `tslot_type` int(11) NOT NULL,
  `tslot_record_id` int(11) NOT NULL,
  `tslot_subrecord_id` int(11) NOT NULL,
  `tslot_day` int(11) NOT NULL,
  `tslot_from_time` time NOT NULL,
  `tslot_to_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_time_slots`
--
ALTER TABLE `tbl_time_slots`
  ADD UNIQUE KEY `tslot_type` (`tslot_type`,`tslot_record_id`,`tslot_subrecord_id`,`tslot_day`,`tslot_from_time`,`tslot_to_time`);

ALTER TABLE `tbl_products` ADD `product_pickup_enabled` TINYINT(1) NOT NULL AFTER `product_cod_enabled`;
ALTER TABLE `tbl_seller_products` ADD `selprod_pickup_enabled` TINYINT(1) NOT NULL AFTER `selprod_cod_enabled`;
ALTER TABLE `tbl_shops` ADD `shop_pickup_enabled` TINYINT(1) NOT NULL AFTER `shop_lng`;
ALTER TABLE `tbl_products` DROP `product_pickup_enabled`;
ALTER TABLE `tbl_seller_products` CHANGE `selprod_pickup_enabled` `selprod_fulfillment_type` TINYINT(4) NOT NULL;
ALTER TABLE `tbl_shops` CHANGE `shop_pickup_enabled` `shop_fulfillment_type` TINYINT(4) NOT NULL;
-- pickup location end


DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Affiliate_Registeration';

ALTER TABLE `tbl_user_wish_lists` ADD `uwlist_type` INT NOT NULL AFTER `uwlist_id`;
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'MSG_PAYFORT_INVALID_REQUEST';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'PAYFORT_Invalid_request_parameters';
UPDATE `tbl_user_wish_lists` SET `uwlist_type`= 3 WHERE `uwlist_default` = 1;
ALTER TABLE `tbl_user_wish_lists` DROP `uwlist_default`;

-- PayPal --
INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Paypal', '13', 'Paypal', '0', '1');
-- PayPal --

ALTER TABLE `tbl_product_categories` ADD `prodcat_seller_id` INT NOT NULL AFTER `prodcat_parent`;
ALTER TABLE `tbl_product_categories` ADD `prodcat_status` TINYINT NOT NULL COMMENT 'Defined in productCategory Model' AFTER `prodcat_active`;
UPDATE `tbl_product_categories` SET `prodcat_status`= 1 WHERE 1;

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES
('seller_category_request_admin_email', 1, 'Seller - Category request', 'New Product Category Requested at {website_name}', '<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tr>\r\n        <td style=\"background:#ff3a59;\">\r\n            <!--\r\n            page title start here\r\n            -->\r\n               \r\n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n                            <h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"><br />\r\n                                </h4>\r\n                            <h2 style=\"margin:0; font-size:34px; padding:0;\">New Product Category Request</h2></td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--\r\n            page title end here\r\n            -->\r\n               </td>\r\n    </tr>\r\n    <tr>\r\n        <td>\r\n            <!--\r\n            page body start here\r\n            -->\r\n               \r\n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n                            <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                                <tbody>\r\n                                    <tr>\r\n                                        <td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear Admin</strong><br />\r\n                                            New Product Category has been requested by Seller {user_full_name}- {prodcat_name}</td>\r\n                                    </tr>\r\n                                    \r\n                                       \r\n                                </tbody>\r\n                            </table></td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--\r\n            page body end here\r\n            -->\r\n               </td>\r\n    </tr>\r\n</table>', '{user_full_name} - Name of the email receiver.<br/>\r\n{website_name} Name of our website<br>\r\n{prodcat_name} Product Category Name <br>\r\n\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1);

ALTER TABLE `tbl_time_slots` ADD PRIMARY KEY( `tslot_id`);
ALTER TABLE `tbl_time_slots` CHANGE `tslot_id` `tslot_id` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_order_user_address` ADD `oua_op_id` INT(11) NOT NULL AFTER `oua_order_id`;
ALTER TABLE `tbl_order_user_address` DROP PRIMARY KEY;
ALTER TABLE `tbl_order_user_address` ADD PRIMARY KEY( `oua_order_id`, `oua_op_id`, `oua_type`);



ALTER TABLE `tbl_order_product_shipping` ADD `opshipping_type` INT(11) NOT NULL DEFAULT '1' COMMENT 'Defined in model' AFTER `opshipping_op_id`;
ALTER TABLE `tbl_order_product_shipping` ADD `opshipping_date` DATE NOT NULL AFTER `opshipping_service_code`, ADD `opshipping_time_slot_from` TIME NOT NULL AFTER `opshipping_date`, ADD `opshipping_time_slot_to` TIME NOT NULL AFTER `opshipping_time_slot_from`;

update `tbl_seller_products` set selprod_fulfillment_type = 2;

-- ShipStation --
ALTER TABLE `tbl_order_product_shipment` CHANGE `opship_order_id` `opship_orderid` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'From third party';
ALTER TABLE `tbl_order_product_shipment` ADD `opship_order_number` VARCHAR(150) NOT NULL COMMENT 'From third party' AFTER `opship_orderid`;
-- ShipStation --

-- AfterShip --
INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('AfterShip Shipment', '14', 'AfterShipShipment', '0', '1');
ALTER TABLE `tbl_orders_status_history` ADD `oshistory_courier` VARCHAR(255) NOT NULL AFTER `oshistory_tracking_number`;

CREATE TABLE `tbl_tracking_courier_code_relation` (
  `tccr_shipapi_plugin_id` int(11) NOT NULL,
  `tccr_shipapi_courier_code` varchar(255) NOT NULL,
  `tccr_tracking_plugin_id` int(11) NOT NULL,
  `tccr_tracking_courier_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tbl_tracking_courier_code_relation`
  ADD UNIQUE KEY `UNIQUE` (`tccr_shipapi_plugin_id`,`tccr_shipapi_courier_code`,`tccr_tracking_plugin_id`);
-- AfterShip --

-- Payment Success Page --
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'MSG_CUSTOMER_SUCCESS_ORDER_{ACCOUNT}_{HISTORY}_{CONTACTUS}';
INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) VALUES
("MSG_CUSTOMER_SUCCESS_ORDER_{BUYER-EMAIL}", 1, "We sent an email to {BUYER-EMAIL} with your order confirmation and receipt. If the email hasn't arrived within two minutes, please check your spam folder to see if the email was routed there.", 1),
("MSG_CUSTOMER_SUCCESS_ORDER_{BUYER-EMAIL}", 2, "لقد أرسلنا بريدًا إلكترونيًا إلى {BUYER-EMAIL} مع تأكيد الطلب والإيصال. إذا لم يصل البريد الإلكتروني في غضون دقيقتين ، فيرجى التحقق من مجلد الرسائل غير المرغوب فيها لمعرفة ما إذا كان البريد الإلكتروني قد تم توجيهه هناك.", 1);
-- Payment Success Page --

-- Manual Shipping --
ALTER TABLE `tbl_order_product_shipment` ADD `opship_tracking_url` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL AFTER `opship_tracking_number`;
-- Manual Shipping --

ALTER TABLE `tbl_order_product_shipping` ADD `opshipping_pickup_addr_id` INT(11) NOT NULL AFTER `opshipping_service_code`;

DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Seller_Products';
INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) VALUES
("LBL_Seller_Products", 1, "My Products", 1),
("LBL_Seller_Products", 2, "My Products", 1);


DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'MSG_SUCCESS_SELLER_SIGNUP_VERIFIED';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'MSG_SUCCESS_SELLER_SIGNUP';

INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) 
VALUES ('LBL_IFSC_/_MICR', 1, 'IFSC / MICR', 1) 
ON DUPLICATE KEY UPDATE `label_caption` = 'IFSC / MICR';

INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) 
VALUES ('LBL_OTP_VERIFICATION', 1, 'OTP Verification', 1) 
ON DUPLICATE KEY UPDATE `label_caption` = 'OTP Verification';

-- COD Process --
INSERT INTO `tbl_sms_templates` (`stpl_code`, `stpl_lang_id`, `stpl_name`, `stpl_body`, `stpl_replacements`, `stpl_status`) VALUES ('COD_OTP_VERIFICATION', '1', 'COD OTP Verification', 'Hello {USER_NAME},\r\n{OTP} is the OTP for cash on delivery order verification.\r\n\r\n{SITE_NAME} Team', '[{\"title\":\"Name\", \"variable\":\"{USER_NAME}\"},{\"title\":\"OTP\", \"variable\":\"{OTP}\"},{\"title\":\"Site Name\", \"variable\":\"{SITE_NAME}\"}]', '1');

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES ('COD_OTP_VERIFICATION', '1', 'COD OTP Verification', 'COD OTP Verification', '<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tr>\r\n        <td style=\"background:#ff3a59;\">\r\n            <!--\r\n            page title start here\r\n            -->\r\n\r\n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n                            <h4\r\n                                style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\">\r\n                            </h4>\r\n                            <h2 style=\"margin:0; font-size:34px; padding:0;\">COD OTP Verification</h2>\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--\r\n            page title end here\r\n            -->\r\n        </td>\r\n    </tr>\r\n    <tr>\r\n        <td>\r\n            <!--\r\n            page body start here\r\n            -->\r\n\r\n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n                            <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                                <tbody>\r\n                                    <tr>\r\n                                        <td style=\"padding:20px 0 30px;\">\r\n                                            <strong style=\"font-size:18px;color:#333;\">Dear\r\n                                                {user_name}\r\n                                            </strong><br />\r\n                                            {OTP} is the OTP for cash on delivery order verification.<br />\r\n                                            <a href=\"{website_url}\">{website_name}</a>\r\n                                        </td>\r\n                                    </tr>\r\n                                </tbody>\r\n                            </table>\r\n                        </td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--\r\n            page body end here\r\n            -->\r\n        </td>\r\n    </tr>\r\n</table>', '{user_name} Name of the email receiver.<br>\r\n{OTP} - One Time Password<br>\r\n{website_name} - Name of the website.\r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', '1');
-- COD Process --
-- ----------------- TV-9.1.3.20200820 -----------------------


-- Collections Management --

CREATE TABLE `tbl_collection_to_records` (
  `ctr_collection_id` int(11) NOT NULL,
  `ctr_record_id` int(11) NOT NULL,
  `ctr_display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `tbl_collection_to_records`
  ADD PRIMARY KEY (`ctr_collection_id`,`ctr_record_id`);

INSERT INTO tbl_collection_to_records ( ctr_collection_id, ctr_record_id , ctr_display_order ) SELECT ctb_collection_id, ctb_post_id, ctb_display_order FROM tbl_collection_to_blogs ORDER BY ctb_collection_id ASC;

INSERT INTO tbl_collection_to_records ( ctr_collection_id, ctr_record_id , ctr_display_order ) SELECT ctpb_collection_id, ctpb_brand_id, ctpb_display_order FROM tbl_collection_to_brands ORDER BY ctpb_collection_id ASC;

INSERT INTO tbl_collection_to_records ( ctr_collection_id, ctr_record_id , ctr_display_order ) SELECT ctpc_collection_id, ctpc_prodcat_id, ctpc_display_order FROM tbl_collection_to_product_categories ORDER BY ctpc_collection_id ASC;

INSERT INTO tbl_collection_to_records ( ctr_collection_id, ctr_record_id , ctr_display_order ) SELECT ctsp_collection_id, ctsp_selprod_id, ctsp_display_order FROM tbl_collection_to_seller_products ORDER BY ctsp_collection_id ASC;

INSERT INTO tbl_collection_to_records ( ctr_collection_id, ctr_record_id , ctr_display_order ) SELECT ctps_collection_id, ctps_shop_id, ctps_display_order FROM tbl_collection_to_shops ORDER BY ctps_collection_id ASC;

DROP TABLE `tbl_collection_to_brands`;
DROP TABLE `tbl_collection_to_product_categories`;
DROP TABLE `tbl_collection_to_seller_products`;
DROP TABLE `tbl_collection_to_shops`;
DROP TABLE `tbl_collection_to_blogs`;


DROP TABLE `tbl_banner_locations`;
DROP TABLE `tbl_banner_location_dimensions`;

CREATE TABLE `tbl_banner_locations` (
  `blocation_id` int(11) NOT NULL,
  `blocation_identifier` varchar(255) NOT NULL,
  `blocation_collection_id` int(11) NOT NULL,
  `blocation_banner_count` int(11) NOT NULL,
  `blocation_promotion_cost` decimal(10,4) NOT NULL,
  `blocation_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tbl_banner_locations` (`blocation_id`, `blocation_identifier`, `blocation_collection_id`, `blocation_banner_count`, `blocation_promotion_cost`, `blocation_active`) VALUES
(1, 'Product Detail page banner', 0, 2, '3.0000', 1);
ALTER TABLE `tbl_banner_locations`
  ADD PRIMARY KEY (`blocation_id`);
ALTER TABLE `tbl_banner_locations`
  MODIFY `blocation_id` int(11) NOT NULL AUTO_INCREMENT;
  
CREATE TABLE `tbl_banner_location_dimensions` (
  `bldimension_blocation_id` int(11) NOT NULL,
  `bldimension_device_type` int(11) NOT NULL,
  `blocation_banner_width` decimal(10,0) NOT NULL,
  `blocation_banner_height` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `tbl_banner_location_dimensions` (`bldimension_blocation_id`, `bldimension_device_type`, `blocation_banner_width`, `blocation_banner_height`) VALUES
(1, 1, '660', '198'),
(1, 2, '660', '198'),
(1, 3, '640', '360');
ALTER TABLE `tbl_banner_location_dimensions`
  ADD PRIMARY KEY (`bldimension_blocation_id`,`bldimension_device_type`);

TRUNCATE `tbl_banners`;
TRUNCATE `tbl_banners_lang`;
DELETE FROM `tbl_attached_files` WHERE `afile_type` = 18;

ALTER TABLE `tbl_banners` CHANGE `banner_img_updated_on` `banner_updated_on` DATETIME NOT NULL;

DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Shipping_Api';

DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Shipping_Api';

ALTER TABLE `tbl_orders` CHANGE `order_is_paid` `order_payment_status` TINYINT(1) NOT NULL COMMENT 'defined in order model';

/* Transfer Bank Payment Status From Buyer */
ALTER TABLE `tbl_order_payments` ADD `opayment_txn_status` TINYINT NOT NULL AFTER `opayment_amount`;
UPDATE `tbl_order_payments` SET `opayment_txn_status` = '1';
/* Transfer Bank Payment Status From Buyer */

UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'conf_lang_specific_url';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_DEFAULT_SITE_LANG';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_ENABLE_301';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_ENABLE_GEO_LOCATION';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_ALLOW_REVIEWS';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_CURRENCY';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_MAINTENANCE';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_RECAPTCHA_SITEKEY';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_FRONT_THEME';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_USE_SSL';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_TIMEZONE';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_AUTO_RESTORE_ON';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_TWITTER_USERNAME';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` like 'CONF_WEBSITE_NAME_';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_AUTO_CLOSE_SYSTEM_MESSAGES';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_ENABLE_ENGAGESPOT_PUSH_NOTIFICATION';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_GOOGLE_TAG_MANAGER_HEAD_SCRIPT';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_HOTJAR_HEAD_SCRIPT';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_DEFAULT_SCHEMA_CODES_SCRIPT';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_GOOGLE_TAG_MANAGER_BODY_SCRIPT';
UPDATE `tbl_configurations` SET `conf_common` = '1' WHERE `tbl_configurations`.`conf_name` = 'CONF_PRODUCT_BRAND_MANDATORY';
ALTER TABLE `tbl_product_categories` ADD INDEX( `prodcat_code`);

ALTER TABLE `tbl_product_requests` ADD `preq_requested_on` DATETIME NOT NULL AFTER `preq_added_on`, ADD `preq_status_updated_on` DATETIME NOT NULL AFTER `preq_requested_on`;
ALTER TABLE `tbl_brands` ADD `brand_requested_on` DATETIME NOT NULL AFTER `brand_updated_on`, ADD `brand_status_updated_on` DATETIME NOT NULL AFTER `brand_requested_on`;
ALTER TABLE `tbl_product_categories` ADD `prodcat_requested_on` DATETIME NOT NULL AFTER `prodcat_updated_on`, ADD `prodcat_status_updated_on` DATETIME NOT NULL AFTER `prodcat_requested_on`;
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Catalogs';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Product_Catalogs';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Catalog_Options';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Catalog_Tags';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Catalog_Specification';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Catalog_Shipping';

DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_ORDER_PLACED._PAYMENT_ON_HOLD_TO_CAPTURE_LATER.';

ALTER TABLE `tbl_orders_status` CHANGE `orderstatus_color_code` `orderstatus_color_class` TINYINT(4) NULL DEFAULT NULL COMMENT 'Defined in applicationConstant';
-- ----------------- TV-9.1.3.20200903 -----------------------
UPDATE `tbl_language_labels` SET `label_caption` = 'Seller Products' WHERE `label_key` LIKE 'LBL_Seller_Products'; 
UPDATE `tbl_language_labels` SET `label_caption` = 'My Products' WHERE `label_key` LIKE 'LBL_MY_PRODUCTS';  

/* Transfer Bank */
INSERT INTO `tbl_sms_templates` (`stpl_code`, `stpl_lang_id`, `stpl_name`, `stpl_body`, `stpl_replacements`, `stpl_status`) VALUES ('ADMIN_ORDER_PAYMENT_TRANSFERRED_TO_BANK', '1', 'Order Payment Transferred To Bank', 'Hello Admin,\r\n\r\nOrder Payment Detail Submitted BY {USER_NAME}\r\nFor #{ORDER_ID}.\r\n\r\n{SITE_NAME} Team', '[{\"title\":\"User Name\", \"variable\":\"{USER_NAME}\"},{\"title\":\"Order Id\", \"variable\":\"{ORDER_ID}\"}, {\"title\":\"Website Name\", \"variable\":\"{SITE_NAME}\"}]', '1');

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`, `etpl_replacements`, `etpl_status`) VALUES ('ADMIN_ORDER_PAYMENT_TRANSFERRED_TO_BANK', '1', 'Order Payment Transferred To Bank', 'Order #{ORDER_ID} Payment Transferred To Bank', '<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tr>\r\n        <td style=\"background:#ff3a59;\">\r\n            <!--\r\n            page title start here\r\n            -->\r\n               \r\n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n                            <h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\r\n                            <h2 style=\"margin:0; font-size:34px; padding:0;\">Bank s</h2></td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--\r\n            page title end here\r\n            -->\r\n               </td>\r\n    </tr>\r\n    <tr>\r\n        <td>\r\n            <!--\r\n            page body start here\r\n            -->\r\n               \r\n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                <tbody>\r\n                    <tr>\r\n                        <td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\r\n                            <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                                <tbody>\r\n                                    <tr>\r\n                                        <td style=\"padding:20px 0 30px;\" colspan=\"2\">\r\n                                            <strong style=\"font-size:18px;color:#333;\">Dear Admin </strong><br />\r\n                                            Order Payment Detail Submitted BY {USER_NAME} For #{ORDER_ID}. <br />\r\n                                            Please find the transfer information below.\r\n                                        </td>\r\n                                    </tr>\r\n                                    <tr>\r\n                                        <td style=\"padding:0 0 30px;\">Payment Method</td>\r\n                                        <td style=\"padding:0 0 30px;\">{PAYMENT_METHOD}</td>\r\n                                    </tr>\r\n                                    <tr>\r\n                                        <td style=\"padding:0 0 30px;\">Transaction Id</td>\r\n                                        <td style=\"padding:0 0 30px;\">{TRANSACTION_ID}</td>\r\n                                    </tr>\r\n                                    <tr>\r\n                                        <td style=\"padding:0 0 30px;\">Amount</td>\r\n                                        <td style=\"padding:0 0 30px;\">{AMOUNT}</td>\r\n                                    </tr>\r\n                                    <tr>\r\n                                        <td style=\"padding:0 0 30px;\">Comments</td>\r\n                                        <td style=\"padding:0 0 30px;\">{COMMENTS}</td>\r\n                                    </tr>\r\n                                    \r\n                                </tbody>\r\n                            </table></td>\r\n                    </tr>\r\n                </tbody>\r\n            </table>\r\n            <!--\r\n            page body end here\r\n            -->\r\n               </td>\r\n    </tr>\r\n</table>', '{USER_NAME} - Name of the User.<br>\r\n{ORDER_ID} - Order Id.<br>\r\n{PAYMENT_METHOD} - Payment Method Used By Buyer.<br>\r\n{TRANSACTION_ID} - Transaction Id<br>\r\n{AMOUNT} - Amount<br>\r\n{COMMENTS} - Comments.<br>', '1');
/* Transfer Bank */


DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_MOVE_TO_ADMIN_WALLET';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_MOVE_TO_CUSTOMER_WALLET';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_MOVE_TO_CUSTOMER_CARD';
-- -----------------TV-9.2.1.20200905------------------
ALTER TABLE `tbl_seller_products` CHANGE `selprod_fulfillment_type` `selprod_fulfillment_type` TINYINT(4) NOT NULL DEFAULT '-1';
ALTER TABLE `tbl_languages` CHANGE `language_flag` `language_country_code` VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

UPDATE `tbl_languages` SET `language_country_code` = 'US' WHERE `tbl_languages`.`language_code` = 'EN';
UPDATE `tbl_languages` SET `language_country_code` = 'AE' WHERE `tbl_languages`.`language_code` = 'AR';

CREATE TABLE `tbl_tax_structure` (
  `taxstr_id` int(11) NOT NULL,
  `taxstr_identifier` varchar(255) NOT NULL,
  `taxstr_parent` int(11) NOT NULL,
  `taxstr_is_combined` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tbl_tax_structure`
  ADD PRIMARY KEY (`taxstr_id`);
  
ALTER TABLE `tbl_tax_structure`
  MODIFY `taxstr_id` int(11) NOT NULL AUTO_INCREMENT;
  
CREATE TABLE `tbl_tax_structure_lang` (
  `taxstrlang_taxstr_id` int(11) NOT NULL,
  `taxstrlang_lang_id` int(11) NOT NULL,
  `taxstr_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tbl_tax_structure_lang`
  ADD PRIMARY KEY (`taxstrlang_taxstr_id`,`taxstrlang_lang_id`);

 DROP TABLE `tbl_tax_structure_options`;
 DROP TABLE `tbl_tax_structure_options_lang`;
  
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Sales_Tax';


ALTER TABLE `tbl_tax_rules` ADD `taxrule_taxstr_id` INT NOT NULL AFTER `taxrule_taxcat_id`;
ALTER TABLE `tbl_tax_rule_details` ADD `taxruledet_taxstr_id` INT NOT NULL AFTER `taxruledet_taxrule_id`;
ALTER TABLE `tbl_tax_rule_details` DROP `taxruledet_identifier`;
DROP TABLE `tbl_tax_rule_details_lang`;
ALTER TABLE `tbl_tax_rules` DROP `taxrule_is_combined`;

UPDATE `tbl_cron_schedules` SET `cron_command` = 'AbandonedCart/sendReminderAbandonedCart' WHERE `cron_command` = 'CartHistory/sendReminderAbandonedCart';
-- ------------- TV-9.2.1.20200916-----------

UPDATE `tbl_email_templates` SET `etpl_body` = '<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">    \r\n	<tbody>\r\n		<tr>        \r\n			<td style=\"background:#ff3a59;\">            \r\n				<!--\r\n				page title start here\r\n				-->\r\n				            \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                \r\n					<tbody>                    \r\n						<tr>                        \r\n							<td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">                            \r\n								<h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>                            \r\n								<h2 style=\"margin:0; font-size:34px; padding:0;\">New Account Created!</h2></td>                    \r\n						</tr>                \r\n					</tbody>            \r\n				</table>            \r\n				<!--\r\n				page title end here\r\n				-->\r\n				               </td>    \r\n		</tr>    \r\n		<tr>        \r\n			<td>            \r\n				<!--\r\n				page body start here\r\n				-->\r\n				            \r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                \r\n					<tbody>                    \r\n						<tr>                        \r\n							<td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">                            \r\n								<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">                                \r\n									<tbody>                                    \r\n										<tr>                                        \r\n											<td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear Admin </strong><br />\r\n												                                            We have received a new registration on <a href=\"{website_url}\">{website_name}</a>. Please find the details below:</td>                                    \r\n										</tr>                                    \r\n										<tr>                                        \r\n											<td style=\"padding:20px 0 30px;\">                                            \r\n												<table style=\"border:1px solid #ddd; border-collapse:collapse;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">                                                \r\n													<tbody>                                                    \r\n														<tr>                                                        \r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Username</td>                                                        \r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{username}</td>                                                    \r\n														</tr>                                                    \r\n														<tr>                                                        \r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Email<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>                                                        \r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{email}</td>                                                    \r\n														</tr>                                                    \r\n														<tr>                                                        \r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Phone<span class=\"Apple-tab-span\" style=\"white-space:pre\"></span></td>                                                        \r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{phone}</td>                                                    \r\n														</tr>                                                    \r\n														<tr>                                                        \r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Name</td>                                                        \r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{name}</td>                                                    \r\n														</tr>                                                    \r\n														<tr>                                                        \r\n															<td style=\"padding:10px;font-size:13px;border:1px solid #ddd; color:#333; font-weight:bold;\" width=\"153\">Type</td>                                                        \r\n															<td style=\"padding:10px;font-size:13px; color:#333;border:1px solid #ddd;\" width=\"620\">{user_type}</td>                                                    \r\n														</tr>                                                \r\n													</tbody>                                            \r\n												</table></td>                                    \r\n										</tr>                                \r\n									</tbody>                            \r\n								</table></td>                    \r\n						</tr>                \r\n					</tbody>            \r\n				</table>            \r\n				<!--\r\n				page body end here\r\n				-->\r\n				               </td>    \r\n		</tr>\r\n	</tbody>\r\n</table>' WHERE `tbl_email_templates`.`etpl_code` = 'new_registration_admin' AND `tbl_email_templates`.`etpl_lang_id` = 1;

DROP TABLE `tbl_tax_values`;
 
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_This_is_the_application_ID_used_in_login_and_post';
-- Category Relation Management --
CREATE TABLE `tbl_product_category_relations` (
  `pcr_prodcat_id` int(11) NOT NULL,
  `pcr_parent_id` int(11) NOT NULL,
  `pcr_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `tbl_product_category_relations` ADD PRIMARY KEY( `pcr_prodcat_id`, `pcr_parent_id`);
-- Category Relation Management --
-- ---------------TV-9.2.1.20200925------------------------
ALTER TABLE `tbl_products_min_price` ADD `pmp_max_price` DECIMAL(10,2) NOT NULL AFTER `pmp_min_price`;
-- -----------TV-9.2.1.20200930------------------------
ALTER TABLE tbl_countries ENGINE=InnoDB;
ALTER TABLE tbl_email_templates ENGINE=InnoDB;
ALTER TABLE tbl_order_product_settings ENGINE=InnoDB;
ALTER TABLE tbl_sms_templates ENGINE=InnoDB;

-- Replace PayPal Standard --
SET @paypalStandardId := (SELECT plugin_id FROM tbl_plugins WHERE plugin_code = 'PaypalStandard');
SET @paypalId := (SELECT plugin_id FROM tbl_plugins WHERE plugin_code = 'Paypal');
DELETE FROM `tbl_plugins` WHERE `plugin_id` = @paypalStandardId;
UPDATE `tbl_plugins` SET `plugin_id`= @paypalStandardId WHERE `plugin_id` = @paypalId;
DELETE FROM `tbl_plugin_settings` WHERE `pluginsetting_plugin_id` = @paypalStandardId;
UPDATE `tbl_plugin_settings` SET `pluginsetting_plugin_id`= @paypalStandardId WHERE `pluginsetting_plugin_id` = @paypalId;
-- Replace PayPal Standard --

ALTER TABLE `tbl_time_slots` ADD `tslot_availability` TINYINT(1) NOT NULL AFTER `tslot_id`;
UPDATE `tbl_time_slots` SET `tslot_availability` = '1' WHERE `tslot_availability` = 0;

DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Cancellation_Request_Status_Pending';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Cancellation_Request_Status_Approved';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Cancellation_Request_Status_Declined';

ALTER TABLE `tbl_order_product_shipping` CHANGE `opshipping_type` `opshipping_fulfillment_type` TINYINT(4) NOT NULL DEFAULT '1' COMMENT 'Defined in model';
ALTER TABLE `tbl_order_product_shipping` CHANGE `opshipping_fulfillment_type` `opshipping_fulfillment_type` TINYINT(4) NOT NULL DEFAULT '2' COMMENT 'Defined in model';
UPDATE tbl_order_product_shipping SET opshipping_fulfillment_type = (CASE opshipping_fulfillment_type WHEN '1' THEN '2' WHEN '2' THEN '1' ELSE opshipping_fulfillment_type END);

ALTER TABLE `tbl_countries` CHANGE `country_region_id` `country_zone_id` INT(11) NOT NULL;


ALTER TABLE `tbl_shipping_rates` CHANGE `shiprate_cost` `shiprate_cost` DECIMAL(10,2) NOT NULL;
ALTER TABLE `tbl_shipping_rates` CHANGE `shiprate_min_val` `shiprate_min_val` DECIMAL(10,2) NOT NULL DEFAULT '0.0000', CHANGE `shiprate_max_val` `shiprate_max_val` DECIMAL(10,2) NOT NULL DEFAULT '0.0000';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_This_is_the_application_ID_used_in_login_and_post';

ALTER TABLE `tbl_shops` ADD `shop_invoice_prefix` VARCHAR(20) NOT NULL AFTER `shop_phone`, ADD `shop_invoice_suffix` BIGINT(15) NOT NULL AFTER `shop_invoice_prefix`;
ALTER TABLE `tbl_shop_specifics` ADD `shop_invoice_codes` VARCHAR(255) NOT NULL AFTER `shop_cancellation_age`;

-- -------------TV-9.2.1.20201008-------------------

UPDATE `tbl_shops` SET `shop_fulfillment_type`=2 WHERE `shop_fulfillment_type` = 0;
-- ---------------TV-9.2.1.20201013-----------------

DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Based_on_item_weight';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Based_on_item_price';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Sr._No';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Sr_no.';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Sr.';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Sr_No';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Sr._no.';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_SrNo.';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Sr._no.';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Sr';
-- -----------------TV-9.2.1.20201014------------------
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_S.No.';
-- --------------TV-9.2.1.20201015---------------------
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_Product's_Dimensions";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_Product_Inclusive_Tax";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_Tax_code_for_categories";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_Product_Category_Request_Approval";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_Product's_Brand_Mandatory";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_Brand_Request_Approval";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_On_enabling_this_feature,_dimensions_of_the_product_will_be_required_to_be_filled._Dimensions_are_required_in_case_of_Shipstation_API_(If_Enabled)_for_Live_Shipping_Charges";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_Product's_SKU_Mandatory";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_Product's_Model_Mandatory";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_This_will_make_Product's_model_mandatory";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_ALLOW_SELLERS_TO_REQUEST_PRODUCTS_WHICH_IS_AVAILABLE_TO_ALL_SELLERS";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_Activate_Administrator_Approval_on_Products";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_On_enabling_this_feature,_Products_required_admin_approval_to_display";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_Allow_Seller_to_add_products";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_On_enabling_this_feature,_Products_option_will_enabled_for_seller_dashboard";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_Add_LANGUAGE_CODE_IN_URLS";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_Product_Category_Name";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "LBL_ADD_LANGUAGE_CODE_TO_SITE_URLS";
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE "MSG_Your_Cancellation_Request_Approved";

INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) 
VALUES ('LBL_LANGUAGE_CODE_TO_SITE_URLS_EXAMPLES', 1, 'For example www.domain.com/en for English and www.domain.com/ar for Arabic. Language code will not show for default site language', 1) 
ON DUPLICATE KEY UPDATE `label_caption` = 'For example www.domain.com/en for English and www.domain.com/ar for Arabic. Language code will not show for default site language';

INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) 
VALUES ('MSG_Your_Account_verification_is_pending_{clickhere}', 1, 'Your account verification is pending. {clickhere} to resend verification link.', 1) 
ON DUPLICATE KEY UPDATE `label_caption` = 'Your account verification is pending. {clickhere} to resend verification link.';

INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) 
VALUES ('LBL_Generate_requests_using_buttons_below', 1, 'Categories, brands and products have to be requested from the site admin. Please generate requests using buttons below.', 1) 
ON DUPLICATE KEY UPDATE `label_caption` = 'Categories, brands and products have to be requested from the site admin. Please generate requests using buttons below.';
-- -------------------TV-9.2.2.20201019------------------------

UPDATE tbl_content_pages_block_lang SET cpblocklang_text = REPLACE(cpblocklang_text, 'btn btn--primary btn--custom', 'btn btn-brand') WHERE cpblocklang_text LIKE '%btn btn--primary btn--custom%';

UPDATE tbl_content_pages_block_lang SET cpblocklang_text = REPLACE(cpblocklang_text, 'btn btn--secondary', 'btn btn-brand') WHERE cpblocklang_text LIKE '%btn btn--secondary%';

UPDATE tbl_extra_pages_lang SET epage_content = REPLACE(epage_content, 'fa-thumbs-o-up', 'fa-thumbs-up') WHERE epage_content LIKE '%fa-thumbs-o-up%';
-- --------------------TV-9.2.2.20201020--------------
ALTER TABLE `tbl_products` ADD `product_fulfillment_type` INT(11) NOT NULL AFTER `product_approved`;

-- ---Change All Digital Products Fulfillment Type Both---- --
UPDATE tbl_seller_products tsp
INNER JOIN tbl_products tp ON tp.product_id = tsp.selprod_product_id
SET tsp.selprod_fulfillment_type = -1
WHERE tp.product_type = 2;
-- ---Change All Digital Products Fulfillment Type Both---- --
-- -------------------TV-9.2.2.20201026---------------------

DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'MSG_{rewardpoint}_reward_point_used._which_will_not_credit_back_automatically';
INSERT INTO `tbl_language_labels` (`label_id`, `label_key`, `label_lang_id`, `label_caption`, `label_type`) VALUES (NULL, 'LBL_SUBMIT_FORGOT_PASSWORD', '1', 'Submit', '1') ON DUPLICATE KEY UPDATE `label_caption` = 'LBL_SUBMIT_FORGOT_PASSWORD';
-- ----------------TV-9.2.2.20201029----------------
DELETE ep, epl
FROM tbl_extra_pages ep
INNER JOIN tbl_extra_pages_lang epl ON epl.epagelang_epage_id=ep.epage_id
WHERE epage_type = 13 OR epage_type = 14;
-- ------------------TV-9.2.2.20201102--------------

-- -----------------TV-9.2.2.20201111-------------------
INSERT INTO `tbl_orders_status` (`orderstatus_id`, `orderstatus_identifier`, `orderstatus_color_class`, `orderstatus_type`, `orderstatus_priority`, `orderstatus_is_active`, `orderstatus_is_digital`) VALUES (NULL, 'Pay At Store', NULL, '1', '2', '1', '0');
INSERT INTO `tbl_plugins` (`plugin_id`, `plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES (NULL, 'Pay At Store', '13', 'PayAtStore', '0', '1');
-- ------------------TV-9.2.3.20201111-------------------------------

UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =1;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =2;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =3;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =4;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =5;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =6;
UPDATE `tbl_countries` SET `country_zone_id`=9, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =7;
UPDATE `tbl_countries` SET `country_zone_id`= 10, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =8;
UPDATE `tbl_countries` SET `country_zone_id`=9, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =9;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =10;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =11;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =12;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =13;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =14;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =15;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =16;
UPDATE `tbl_countries` SET `country_zone_id`=5, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =17;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =18;
UPDATE `tbl_countries` SET `country_zone_id`=9, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =19;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =20;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =21;
UPDATE `tbl_countries` SET `country_zone_id`=3, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =22;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =23;
UPDATE `tbl_countries` SET `country_zone_id`=9, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =24;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =25;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =26;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =27;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =28;
UPDATE `tbl_countries` SET `country_zone_id`= 10, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =29;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =30;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =31;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =32;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =33;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =34;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =35;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =36;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =37;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =38;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =39;
UPDATE `tbl_countries` SET `country_zone_id`=9, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =40;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =41;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =42;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =43;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =44;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =45;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =46;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =47;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =48;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =49;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =50;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =51;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =52;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =53;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =54;
UPDATE `tbl_countries` SET `country_zone_id`=5, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =55;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =56;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =57;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =58;
UPDATE `tbl_countries` SET `country_zone_id`=9, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =59;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =60;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =61;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =62;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =63;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =64;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =65;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =66;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =67;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =68;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =69;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =70;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =71;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =72;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =74;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =75;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =76;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =77;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =78;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =79;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =80;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =81;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =82;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =83;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =84;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =85;
UPDATE `tbl_countries` SET `country_zone_id`=9, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =86;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =87;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =88;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =89;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =90;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =91;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =92;
UPDATE `tbl_countries` SET `country_zone_id`=9, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =93;
UPDATE `tbl_countries` SET `country_zone_id`= 10, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =94;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =95;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =96;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =97;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =98;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =99;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =100;
UPDATE `tbl_countries` SET `country_zone_id`=5, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =101;
UPDATE `tbl_countries` SET `country_zone_id`=5, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =102;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =103;
UPDATE `tbl_countries` SET `country_zone_id`=5, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =104;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =105;
UPDATE `tbl_countries` SET `country_zone_id`=9, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =106;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =107;
UPDATE `tbl_countries` SET `country_zone_id`=5, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =108;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =109;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =110;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =111;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =112;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =113;
UPDATE `tbl_countries` SET `country_zone_id`=5, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =114;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =115;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =116;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =117;
UPDATE `tbl_countries` SET `country_zone_id`=5, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =118;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =119;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =120;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =121;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =122;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =123;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =124;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =125;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =126;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =127;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =128;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =129;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =130;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =131;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =132;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =133;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =134;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =135;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =136;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =137;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =138;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =139;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =140;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =141;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =142;
UPDATE `tbl_countries` SET `country_zone_id`=9, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =143;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =144;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =145;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =146;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =147;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =148;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =149;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =150;
UPDATE `tbl_countries` SET `country_zone_id`=9, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =151;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =152;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =153;
UPDATE `tbl_countries` SET `country_zone_id`=3, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =154;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =155;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =156;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =157;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =158;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =159;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =160;
UPDATE `tbl_countries` SET `country_zone_id`=5, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =161;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =162;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =163;
UPDATE `tbl_countries` SET `country_zone_id`=3, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =164;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =165;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =166;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =167;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =168;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =169;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =170;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =171;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =172;
UPDATE `tbl_countries` SET `country_zone_id`=5, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =173;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =174;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =175;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =176;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =177;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =178;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =179;
UPDATE `tbl_countries` SET `country_zone_id`=9, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =180;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =181;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =182;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =183;
UPDATE `tbl_countries` SET `country_zone_id`=5, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =184;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =185;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =186;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =187;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =188;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =189;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =190;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =191;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =192;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =193;
UPDATE `tbl_countries` SET `country_zone_id`= 10, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =194;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =195;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =196;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =197;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =198;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =199;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =200;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =201;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =202;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =203;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =204;
UPDATE `tbl_countries` SET `country_zone_id`=5, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =205;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =206;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =207;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =208;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =209;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =210;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =211;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =212;
UPDATE `tbl_countries` SET `country_zone_id`=9, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =213;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =214;
UPDATE `tbl_countries` SET `country_zone_id`=5, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =215;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =216;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =217;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =218;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =219;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =220;
UPDATE `tbl_countries` SET `country_zone_id`=5, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =221;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =222;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =223;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =224;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =225;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =226;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =227;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =228;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =229;
UPDATE `tbl_countries` SET `country_zone_id`=2, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =230;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =231;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =232;
UPDATE `tbl_countries` SET `country_zone_id`=7, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =233;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =234;
UPDATE `tbl_countries` SET `country_zone_id`=5, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =235;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =237;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =238;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =239;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =240;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =241;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =242;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =243;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =244;
UPDATE `tbl_countries` SET `country_zone_id`=9, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =245;
UPDATE `tbl_countries` SET `country_zone_id`=8, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =246;
UPDATE `tbl_countries` SET `country_zone_id`=5, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =247;
UPDATE `tbl_countries` SET `country_zone_id`=1, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =248;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =249;
UPDATE `tbl_countries` SET `country_zone_id`=6, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =250;
UPDATE `tbl_countries` SET `country_zone_id`=4, `country_updated_on`='2020-11-12 00:00:00' WHERE country_id =251;

INSERT INTO `tbl_zones_lang`(`zonelang_zone_id`, `zonelang_lang_id`, `zone_name`) VALUES 
(1, 1, 'Africa'),
(2, 1, 'Asia'),
(3, 1, 'Central America'),
(4, 1, 'Europe'),
(5, 1, 'Middle East'),
(6, 1, 'North America'),
(7, 1, 'Oceania'),
(8, 1, 'South America'),
(9, 1, 'The Caribbean'),
(10, 1, 'Antarctica')
ON DUPLICATE KEY UPDATE zone_name = VALUES(zone_name);


--
-- Table structure for table `tbl_transactions_failure_log`
--

CREATE TABLE `tbl_transactions_failure_log` (
  `txnlog_id` bigint(20) NOT NULL,
  `txnlog_type` int(11) NOT NULL COMMENT 'Defined In Transaction Failure Log Model',
  `txnlog_record_id` varchar(150) NOT NULL,
  `txnlog_response` text NOT NULL,
  `txnlog_updated_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `txnlog_added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_transactions_failure_log`
--
ALTER TABLE `tbl_transactions_failure_log`
  ADD PRIMARY KEY (`txnlog_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_transactions_failure_log`
--
ALTER TABLE `tbl_transactions_failure_log`
  MODIFY `txnlog_id` bigint(20) NOT NULL AUTO_INCREMENT;
-- -----------------TV-9.2.3.20201116------------------  

DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'MSG_MAXIMUM_OF_{LIMIT}_{PLUGIN-TYPE}_CAN_BE_ACTIVATED_SIMULTANEOUSLY';
-- ------------------TV-9.2.3.20201117-----------------
-- ---Rounding off with order---- --
ALTER TABLE `tbl_order_products` ADD `op_rounding_off` DECIMAL(4,2) NOT NULL AFTER `op_tax_code`;
ALTER TABLE `tbl_orders` ADD `order_rounding_off` DECIMAL(4,2) NOT NULL AFTER `order_deleted`;
-- ------------------TV-9.2.3.20201118----------------------


CREATE TABLE `tbl_coupon_to_shops` (
  `cts_shop_id` int(11) NOT NULL,
  `cts_coupon_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `tbl_coupon_to_shops`
  ADD PRIMARY KEY (`cts_shop_id`,`cts_coupon_id`);


CREATE TABLE `tbl_coupon_to_brands` (
  `ctb_brand_id` int(11) NOT NULL,
  `ctb_coupon_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
ALTER TABLE `tbl_coupon_to_brands`
  ADD UNIQUE KEY `ctp_brand_id` (`ctb_brand_id`,`ctb_coupon_id`);


DELETE FROM `tbl_language_labels` WHERE label_key = 'LBL_Multi-vendor_Ecommerce_Marketplace_Solution';
-- ---------------------TV-9.2.3.20201124------------------
update `tbl_products` set `product_fulfillment_type` = 2 WHERE `product_fulfillment_type` = 0; 


-- --- Mpesa Payment Gateway--- --
INSERT IGNORE INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Mpesa', '13', 'Mpesa', '0', '9');
INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) VALUES
("LBL_MPESA_ACCOUNT_REFERENCE_DESCRIPTION", 1, "This is an Alpha-Numeric parameter that is defined by admin as an Identifier of the transaction for CustomerPayBillOnline transaction type. Along with the business name, this value is also displayed to the customer in the STK Pin Prompt message. Maximum of 12 characters.", 1),
("LBL_MSISDN_12_DIGITS_MOBILE_NUMBER", 1, "MSISDN (12 digits Mobile Number) e.g. 2547XXXXXXXX", 1)
ON DUPLICATE KEY UPDATE label_caption = VALUES(label_caption);
-- --- Mpesa Payment Gateway--- --

INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) VALUES
("MSG_THIS_TXN_NOT_YET_CAPTURED/_COMPLETED", 1, "This Txn Not Yet Captured/Completed", 1)
ON DUPLICATE KEY UPDATE label_caption = VALUES(label_caption);
ALTER TABLE `tbl_countries` CHANGE `country_id` `country_id` INT(11) NOT NULL AUTO_INCREMENT;

UPDATE `tbl_plugins` SET `plugin_identifier` = '2Checkout' WHERE `plugin_code` = 'Twocheckout';
ALTER TABLE `tbl_coupons_hold_pending_order` CHANGE `ochold_order_id` `ochold_order_id` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `tbl_notifications` CHANGE `notification_user_id` `notification_user_id` INT(11) NOT NULL;
-- ---------------------TV-9.2.3.20201201------------------
UPDATE IGNORE `tbl_language_labels` SET `label_key` = 'LBL_PLUGIN_ICON' WHERE `tbl_language_labels`.`label_key` = 'PLUGIN_ICON';
UPDATE IGNORE `tbl_language_labels` SET `label_key` = 'INV_{PRODUCT}_ORDER_{ORDERID}_HAS_BEEN_PLACED' WHERE `tbl_language_labels`.`label_key` = 'SAPP_{PRODUCT}_ORDER_{ORDERID}_HAS_BEEN_PLACED';
UPDATE IGNORE `tbl_language_labels` SET `label_key` = 'INV_RECEIVED_CANCELLATION_FOR_INVOICE_{invoicenumber}' WHERE `tbl_language_labels`.`label_key` = 'SAPP_RECEIVED_CANCELLATION_FOR_INVOICE_{invoicenumber}';
UPDATE IGNORE `tbl_language_labels` SET `label_key` = 'INV_RECEIVED_RETURN_FROM_{username}_WITH_REFERENCE_NUMBER_{returnrequestid}' WHERE `tbl_language_labels`.`label_key` = 'SAPP_RECEIVED_RETURN_FROM_{username}_WITH_REFERENCE_NUMBER_{returnrequestid}';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'ADDTAXFORM';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = '0';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'ADMIN_ABUSIVE_REVIEW_POSTED_NOTIFICATION';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'ADMIN_PRODUCT_REVIEW_NOTIFICATION';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'ADMIN_ORDER_PAYMENT_STATUS_CHANGE_NOTIFICATION';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'ADMIN_ORDER_RETURN_REQUEST_MESSAGE_TO_USER_NOTIFICATION';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'ADMIN_ORDER_EMAIL_NOTIFICATION';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'ADMIN_CUSTOM_CATALOG_REQUEST_NOTIFICATION';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'BUYER_RETURN_REQUEST_STATUS_CHANGE_NOTIFICATION';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'GUEST_ADVISER_REGISTRATION_NOTIFICATION';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'SUPPLIER_REGISTRATION_NOTIFICATION';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'HOME_PAGE_BOTTOM_BANNER';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'HOME_PAGE_TOP_BANNER';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'PRODUCT_DETAIL_PAGE_BANNER';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'NOP';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'TEST';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'USER_PROFILE';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'INACTIVE';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'ACTIVE';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'SHIPPING_USERS_LIST';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'MANAGE_SHIPPING_USERS';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'IDENTIFIER';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'PLEASE_TRY_DIFFERENT_URL,_URL_ALREADY_USED_FOR_ANOTHER_RECORD';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'CLONE';
UPDATE IGNORE `tbl_language_labels` SET `label_key` = 'API_AMAZON_INVALID_PAYMENT_GATEWAY_SETUP_ERROR' WHERE `tbl_language_labels`.`label_key` = 'AMAZON_INVALID_PAYMENT_GATEWAY_SETUP_ERROR';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'USER_REGISTRATION_NOTIFICATION';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'PLEASE_TRY_DIFFERENT_URL,_URL_ALREADY_USED_FOR_ANOTHER_RECORD.';
UPDATE IGNORE `tbl_language_labels` SET `label_key` = 'API_AMAZON_PAYMENT_COMPLETE' WHERE `tbl_language_labels`.`label_key` = 'AMAZON_PAYMENT_COMPLETE';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'USER_ORDER_PLACED_NOTIFICATION';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'M_ORDER_PAYMENT_GATEWAY_DESCRIPTION';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'M_CARD_HOLDER_NAME';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'M_PROMOTION_ADDED_UPDATED';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'M_NO_SHIPPING_SET';
UPDATE IGNORE `tbl_language_labels` SET `label_key` = 'LBL_COMPANY' WHERE `tbl_language_labels`.`label_key` = 'L_Company';
UPDATE IGNORE `tbl_language_labels` SET `label_key` = 'LBL_BRIEF_PROFILE' WHERE `tbl_language_labels`.`label_key` = 'L_Brief_Profile';
UPDATE IGNORE `tbl_language_labels` SET `label_key` = 'LBL_PLEASE_TELL_US_SOMETHING_ABOUT_YOURSELF' WHERE `tbl_language_labels`.`label_key` = 'L_Please_tell_us_something_about_yourself';
UPDATE IGNORE `tbl_language_labels` SET `label_key` = 'LBL_WHAT_KIND_PRODUCTS_SERVICES_ADVERTISE' WHERE `tbl_language_labels`.`label_key` = 'L_What_kind_products_services_advertise';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'TOTAL';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'PAYFORT_INVALID_PAYMENT_GATEWAY_SETUP_ERROR';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'LAYOUT2_(COLLECTION_ALONG_WITH_PRODUCTS-FULL-WIDTH)';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'PAYFORT_INVALID_REQUEST';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'LAYOUT3_(COLLECTION_ALONG_WITH_PRODUCTS)';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'OFFER';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'SELECT';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'SELLER_BRAND_REQUEST_NOTIFICATION';
DELETE FROM `tbl_language_labels` WHERE `tbl_language_labels`.`label_key` = 'PAYFORT_INVALID_REQUEST_PARAMETERS';
DELETE FROM `tbl_language_labels` WHERE label_key = 'LBL_What_Clients_Say';

INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) VALUES
("LBL_NEWSLETTER_SIGNUP_AWEBER", 1, "Newsletter Signup", 1)
ON DUPLICATE KEY UPDATE label_caption = VALUES(label_caption);
-- -----------------------TV-9.2.3.20201204-------------------------

-- --- Dpo Payment Gateway--- --
INSERT IGNORE INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Dpo', '13', 'Dpo', '0', '21');
ALTER TABLE `tbl_countries` ADD `country_code_alpha3` VARCHAR(3) NOT NULL AFTER `country_code`;
ALTER TABLE `tbl_order_user_address` ADD `oua_country_code_alpha3` VARCHAR(3) NOT NULL AFTER `oua_country_code`;

UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ARG' WHERE tcl.country_name LIKE "Argentina";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BRA' WHERE tcl.country_name LIKE "Brazil";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'CHL' WHERE tcl.country_name LIKE "Chile";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'KEN' WHERE tcl.country_name LIKE "Kenya";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MEX' WHERE tcl.country_name LIKE "Mexico";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GBR' WHERE tcl.country_name LIKE "United Kingdom";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'USA' WHERE tcl.country_name LIKE "United States";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ZAF' WHERE tcl.country_name LIKE "South Africa";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'AFG' WHERE tcl.country_name LIKE "Afghanistan";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ALB' WHERE tcl.country_name LIKE "Albania";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'DZA' WHERE tcl.country_name LIKE "Algeria";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ASM' WHERE tcl.country_name LIKE "American Samoa";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'AND' WHERE tcl.country_name LIKE "Andorra";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'AGO' WHERE tcl.country_name LIKE "Angola";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'AIA' WHERE tcl.country_name LIKE "Anguilla";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ATA' WHERE tcl.country_name LIKE "Antarctica";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ATG' WHERE tcl.country_name LIKE "Antigua and Barbuda";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ARG' WHERE tcl.country_name LIKE "Argentina";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ARM' WHERE tcl.country_name LIKE "Armenia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ABW' WHERE tcl.country_name LIKE "Aruba";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'AUS' WHERE tcl.country_name LIKE "Australia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'AUT' WHERE tcl.country_name LIKE "Austria";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'AZE' WHERE tcl.country_name LIKE "Azerbaijan";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BHS' WHERE tcl.country_name LIKE "Bahamas";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BHR' WHERE tcl.country_name LIKE "Bahrain";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BGD' WHERE tcl.country_name LIKE "Bangladesh";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BRB' WHERE tcl.country_name LIKE "Barbados";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BLR' WHERE tcl.country_name LIKE "Belarus";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BEL' WHERE tcl.country_name LIKE "Belgium";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BLZ' WHERE tcl.country_name LIKE "Belize";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BEN' WHERE tcl.country_name LIKE "Benin";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BMU' WHERE tcl.country_name LIKE "Bermuda";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BTN' WHERE tcl.country_name LIKE "Bhutan";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BOL' WHERE tcl.country_name LIKE "Bolivia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BIH' WHERE tcl.country_name LIKE "Bosnia and Herzegovina";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BWA' WHERE tcl.country_name LIKE "Botswana";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BVT' WHERE tcl.country_name LIKE "Bouvet Island";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BRA' WHERE tcl.country_name LIKE "Brazil";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'IOT' WHERE tcl.country_name LIKE "British Indian Ocean Territory";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'VGB' WHERE tcl.country_name LIKE "British Virgin Islands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BRN' WHERE tcl.country_name LIKE "Brunei Darussalam";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BGR' WHERE tcl.country_name LIKE "Bulgaria";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BFA' WHERE tcl.country_name LIKE "Burkina Faso";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BDI' WHERE tcl.country_name LIKE "Burundi";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'KHM' WHERE tcl.country_name LIKE "Cambodia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'CMR' WHERE tcl.country_name LIKE "Cameroon";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'CAN' WHERE tcl.country_name LIKE "Canada";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'CPV' WHERE tcl.country_name LIKE "Cape Verde";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'CYM' WHERE tcl.country_name LIKE "Cayman Islands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'CAF' WHERE tcl.country_name LIKE "Central African Republic";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'TCD' WHERE tcl.country_name LIKE "Chad";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'CHL' WHERE tcl.country_name LIKE "Chile";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'CHN' WHERE tcl.country_name LIKE "China";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'CXR' WHERE tcl.country_name LIKE "Christmas Island";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'CCK' WHERE tcl.country_name LIKE "Cocos (Keeling) Islands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'COL' WHERE tcl.country_name LIKE "Colombia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'COL' WHERE tcl.country_name LIKE "Comoros";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'COG' WHERE tcl.country_name LIKE "Congo";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'COD' WHERE tcl.country_name LIKE "Congo, The Democratic Republic of The";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'COK' WHERE tcl.country_name LIKE "Cook Islands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'CRI' WHERE tcl.country_name LIKE "Costa Rica";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'CIV' WHERE tcl.country_name LIKE "Cote D\'ivoire";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'CHRV'WHERE tcl.country_name LIKE "Croatia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'CUB' WHERE tcl.country_name LIKE "Cuba";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'CYP' WHERE tcl.country_name LIKE "Cyprus";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'CZE' WHERE tcl.country_name LIKE "Czech Republic";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'DNK' WHERE tcl.country_name LIKE "Denmark";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'DJI' WHERE tcl.country_name LIKE "Djibouti";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'DMA' WHERE tcl.country_name LIKE "Dominica";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'DOM' WHERE tcl.country_name LIKE "Dominican Republic";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ECU' WHERE tcl.country_name LIKE "Ecuador";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'EGY' WHERE tcl.country_name LIKE "Egypt";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SLV' WHERE tcl.country_name LIKE "El Salvador";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GNQ' WHERE tcl.country_name LIKE "Equatorial Guinea";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ERI' WHERE tcl.country_name LIKE "Eritrea";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'EST' WHERE tcl.country_name LIKE "Estonia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ETH' WHERE tcl.country_name LIKE "Ethiopia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'FLK' WHERE tcl.country_name LIKE "Falkland Islands (Malvinas)";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'FRO' WHERE tcl.country_name LIKE "Faroe Islands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'FJI' WHERE tcl.country_name LIKE "Fiji";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'FIN' WHERE tcl.country_name LIKE "Finland";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'FRA' WHERE tcl.country_name LIKE "France";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'FXX' WHERE tcl.country_name LIKE "French Metropolitan";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GUF' WHERE tcl.country_name LIKE "French Guiana";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'PYF' WHERE tcl.country_name LIKE "French Polynesia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ATF' WHERE tcl.country_name LIKE "French Southern Territories";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GAB' WHERE tcl.country_name LIKE "Gabon";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GMB' WHERE tcl.country_name LIKE "Gambia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GEO' WHERE tcl.country_name LIKE "Georgia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'DEU' WHERE tcl.country_name LIKE "Germany";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GHA' WHERE tcl.country_name LIKE "Ghana";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GIB' WHERE tcl.country_name LIKE "Gibraltar";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GRC' WHERE tcl.country_name LIKE "Greece";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GRL' WHERE tcl.country_name LIKE "Greenland";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GRD' WHERE tcl.country_name LIKE "Grenada";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GLP' WHERE tcl.country_name LIKE "Guadeloupe";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GUM' WHERE tcl.country_name LIKE "Guam";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GTM' WHERE tcl.country_name LIKE "Guatemala";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GIN' WHERE tcl.country_name LIKE "Guinea";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GNB' WHERE tcl.country_name LIKE "Guinea-bissau";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GUY' WHERE tcl.country_name LIKE "Guyana";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'HTI' WHERE tcl.country_name LIKE "Haiti";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'HMD' WHERE tcl.country_name LIKE "Heard Island and Mcdonald Islands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'VAT' WHERE tcl.country_name LIKE "Holy See (Vatican City State)";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'HND' WHERE tcl.country_name LIKE "Honduras";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'HKG' WHERE tcl.country_name LIKE "Hong Kong";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'HUN' WHERE tcl.country_name LIKE "Hungary";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ISL' WHERE tcl.country_name LIKE "Iceland";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'IND' WHERE tcl.country_name LIKE "India";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'IDN' WHERE tcl.country_name LIKE "Indonesia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'IRN' WHERE tcl.country_name LIKE "Iran, Islamic Republic of";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'IRQ' WHERE tcl.country_name LIKE "Iraq";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'IRL' WHERE tcl.country_name LIKE "Ireland";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ISR' WHERE tcl.country_name LIKE "Israel";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ITA' WHERE tcl.country_name LIKE "Italy";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'JAM' WHERE tcl.country_name LIKE "Jamaica";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'JPN' WHERE tcl.country_name LIKE "Japan";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'JOR' WHERE tcl.country_name LIKE "Jordan";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'KAZ' WHERE tcl.country_name LIKE "Kazakhstan";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'KEN' WHERE tcl.country_name LIKE "Kenya";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'KIR' WHERE tcl.country_name LIKE "Kiribati";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'PRK' WHERE tcl.country_name LIKE "Korea, Democratic People\'s Republic of";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'KOR' WHERE tcl.country_name LIKE "Korea, Republic of";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'KWT' WHERE tcl.country_name LIKE "Kuwait";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'KGZ' WHERE tcl.country_name LIKE "Kyrgyzstan";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'LAO' WHERE tcl.country_name LIKE "Lao People\'s Democratic Republic";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'LVA' WHERE tcl.country_name LIKE "Latvia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'LBN' WHERE tcl.country_name LIKE "Lebanon";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'LSO' WHERE tcl.country_name LIKE "Lesotho";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'LBR' WHERE tcl.country_name LIKE "Liberia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'LBY' WHERE tcl.country_name LIKE "Libyan Arab Jamahiriya";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'LIE' WHERE tcl.country_name LIKE "Liechtenstein";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'LTU' WHERE tcl.country_name LIKE "Lithuania";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'LUX' WHERE tcl.country_name LIKE "Luxembourg";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MAC' WHERE tcl.country_name LIKE "Macau China";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MKD' WHERE tcl.country_name LIKE "Macedonia, The Former Yugoslav Republic of";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MDG' WHERE tcl.country_name LIKE "Madagascar";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MWI' WHERE tcl.country_name LIKE "Malawi";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MYS' WHERE tcl.country_name LIKE "Malaysia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MDV' WHERE tcl.country_name LIKE "Maldives";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MLI' WHERE tcl.country_name LIKE "Mali";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MLT' WHERE tcl.country_name LIKE "Malta";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MHL' WHERE tcl.country_name LIKE "Marshall Islands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MTQ' WHERE tcl.country_name LIKE "Martinique";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MRT' WHERE tcl.country_name LIKE "Mauritania";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MUS' WHERE tcl.country_name LIKE "Mauritius";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MYT' WHERE tcl.country_name LIKE "Mayotte";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MEX' WHERE tcl.country_name LIKE "Mexico";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'FSM' WHERE tcl.country_name LIKE "Micronesia, Federated States of";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MDA' WHERE tcl.country_name LIKE "Moldova, Republic of";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MCO' WHERE tcl.country_name LIKE "Monaco";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MNG' WHERE tcl.country_name LIKE "Mongolia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MSR' WHERE tcl.country_name LIKE "Montserrat";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MAR' WHERE tcl.country_name LIKE "Morocco";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MOZ' WHERE tcl.country_name LIKE "Mozambique";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MMR' WHERE tcl.country_name LIKE "Myanmar";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'NAM' WHERE tcl.country_name LIKE "Namibia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'NRU' WHERE tcl.country_name LIKE "Nauru";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'NPL' WHERE tcl.country_name LIKE "Nepal";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'NLD' WHERE tcl.country_name LIKE "Netherlands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ANT' WHERE tcl.country_name LIKE "Netherlands Antilles";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'NCL' WHERE tcl.country_name LIKE "New Caledonia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'NZL' WHERE tcl.country_name LIKE "New Zealand";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'NIC' WHERE tcl.country_name LIKE "Nicaragua";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'NER' WHERE tcl.country_name LIKE "Niger";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'NGA' WHERE tcl.country_name LIKE "Nigeria";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'NIU' WHERE tcl.country_name LIKE "Niue";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'NFK' WHERE tcl.country_name LIKE "Norfolk Island";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MNP' WHERE tcl.country_name LIKE "Northern Mariana Islands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'NOR' WHERE tcl.country_name LIKE "Norway";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'OMN' WHERE tcl.country_name LIKE "Oman";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'PAK' WHERE tcl.country_name LIKE "Pakistan";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'PLW' WHERE tcl.country_name LIKE "Palau";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'PAN' WHERE tcl.country_name LIKE "Panama";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'PNG' WHERE tcl.country_name LIKE "Papua New Guinea";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'PRY' WHERE tcl.country_name LIKE "Paraguay";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'PER' WHERE tcl.country_name LIKE "Peru";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'PHL' WHERE tcl.country_name LIKE "Philippines";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'PCN' WHERE tcl.country_name LIKE "Pitcairn";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'POL' WHERE tcl.country_name LIKE "Poland";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'PRT' WHERE tcl.country_name LIKE "Portugal";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'PRI' WHERE tcl.country_name LIKE "Puerto Rico";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'QAT' WHERE tcl.country_name LIKE "Qatar";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'REU' WHERE tcl.country_name LIKE "Reunion";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ROM' WHERE tcl.country_name LIKE "Romania";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'RUS' WHERE tcl.country_name LIKE "Russian Federation";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'RWA' WHERE tcl.country_name LIKE "Rwanda";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SHN' WHERE tcl.country_name LIKE "Saint Helena";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'KNA' WHERE tcl.country_name LIKE "Saint Kitts and Nevis";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'LCA' WHERE tcl.country_name LIKE "Saint Lucia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SPM' WHERE tcl.country_name LIKE "Saint Pierre and Miquelon";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'VCT' WHERE tcl.country_name LIKE "Saint Vincent and The Grenadines";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'WSM' WHERE tcl.country_name LIKE "Samoa";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SMR' WHERE tcl.country_name LIKE "San Marino";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'STP' WHERE tcl.country_name LIKE "Sao Tome and Principe";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SAU' WHERE tcl.country_name LIKE "Saudi Arabia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SEN' WHERE tcl.country_name LIKE "Senegal";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SYC' WHERE tcl.country_name LIKE "Seychelles";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SLE' WHERE tcl.country_name LIKE "Sierra Leone";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SGP' WHERE tcl.country_name LIKE "Singapore";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SVK' WHERE tcl.country_name LIKE "Slovakia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SVN' WHERE tcl.country_name LIKE "Slovenia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SLB' WHERE tcl.country_name LIKE "Solomon Islands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SOM' WHERE tcl.country_name LIKE "Somalia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ZAF' WHERE tcl.country_name LIKE "South Africa";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SGS' WHERE tcl.country_name LIKE "South Georgia and The South Sandwich Islands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ESP' WHERE tcl.country_name LIKE "Spain";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'LKA' WHERE tcl.country_name LIKE "Sri Lanka";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SDN' WHERE tcl.country_name LIKE "Sudan";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SUR' WHERE tcl.country_name LIKE "Suriname";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SJM' WHERE tcl.country_name LIKE "Svalbard and Jan Mayen";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SWZ' WHERE tcl.country_name LIKE "Swaziland";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SWE' WHERE tcl.country_name LIKE "Sweden";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'CHE' WHERE tcl.country_name LIKE "Switzerland";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SYR' WHERE tcl.country_name LIKE "Syrian Arab Republic";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'TWN' WHERE tcl.country_name LIKE "Taiwan, Province of China";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'TJK' WHERE tcl.country_name LIKE "Tajikistan";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'TZA' WHERE tcl.country_name LIKE "Tanzania, United Republic of";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'THA' WHERE tcl.country_name LIKE "Thailand";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'TGO' WHERE tcl.country_name LIKE "Togo";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'TKL' WHERE tcl.country_name LIKE "Tokelau";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'TON' WHERE tcl.country_name LIKE "Tonga";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'TTO' WHERE tcl.country_name LIKE "Trinidad and Tobago";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'TUN' WHERE tcl.country_name LIKE "Tunisia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'TUR' WHERE tcl.country_name LIKE "Turkey";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'TKM' WHERE tcl.country_name LIKE "Turkmenistan";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'TCA' WHERE tcl.country_name LIKE "Turks and Caicos Islands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'TUV' WHERE tcl.country_name LIKE "Tuvalu";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'UGA' WHERE tcl.country_name LIKE "Uganda";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'UKR' WHERE tcl.country_name LIKE "Ukraine";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ARE' WHERE tcl.country_name LIKE "United Arab Emirates";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GBR' WHERE tcl.country_name LIKE "United Kingdom";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'USA' WHERE tcl.country_name LIKE "United States";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'UMI' WHERE tcl.country_name LIKE "United States Minor Outlying Islands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'VIR' WHERE tcl.country_name LIKE "U.S. Virgin Islands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'URY' WHERE tcl.country_name LIKE "Uruguay";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'UZB' WHERE tcl.country_name LIKE "Uzbekistan";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'VUT' WHERE tcl.country_name LIKE "Vanuatu";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'VEN' WHERE tcl.country_name LIKE "Venezuela";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'VNM' WHERE tcl.country_name LIKE "Vietnam";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'WLF' WHERE tcl.country_name LIKE "Wallis and Futuna";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ESH' WHERE tcl.country_name LIKE "Western Sahara";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'YEM' WHERE tcl.country_name LIKE "Yemen";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'YUG' WHERE tcl.country_name LIKE "Yugoslavia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ZMB' WHERE tcl.country_name LIKE "Zambia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'ZWE' WHERE tcl.country_name LIKE "Zimbabwe";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'THA' WHERE tcl.country_name LIKE "East Timor";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'FRA' WHERE tcl.country_name LIKE "France, Metropolitan";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'HMD' WHERE tcl.country_name LIKE "Heard and Mc Donald Islands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'IRN' WHERE tcl.country_name LIKE "Iran (Islamic Republic of)";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'KOR' WHERE tcl.country_name LIKE "North Korea";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SVK' WHERE tcl.country_name LIKE "Slovak Republic";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SGS' WHERE tcl.country_name LIKE "South Georgia &amp; South Sandwich Islands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SHN' WHERE tcl.country_name LIKE "St. Helena";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SPM' WHERE tcl.country_name LIKE "St. Pierre and Miquelon";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SJM' WHERE tcl.country_name LIKE "Svalbard and Jan Mayen Islands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'TWN' WHERE tcl.country_name LIKE "Taiwan";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'VAT' WHERE tcl.country_name LIKE "Vatican City State (Holy See)";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'VNM' WHERE tcl.country_name LIKE "Viet Nam";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'VGB' WHERE tcl.country_name LIKE "Virgin Islands (British)";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'VIR' WHERE tcl.country_name LIKE "Virgin Islands (U.S.)";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'WLF' WHERE tcl.country_name LIKE "Wallis and Futuna Islands";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'COD' WHERE tcl.country_name LIKE "Democratic Republic of Congo";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'JEY' WHERE tcl.country_name LIKE "Jersey";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'GGY' WHERE tcl.country_name LIKE "Guernsey";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MNE' WHERE tcl.country_name LIKE "Montenegro";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SRB' WHERE tcl.country_name LIKE "Serbia";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BES' WHERE tcl.country_name LIKE "Bonaire, Sint Eustatius and Saba";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'SSD' WHERE tcl.country_name LIKE "South Sudan";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'BLM' WHERE tcl.country_name LIKE "St. Barthelemy";
UPDATE `tbl_countries` tc INNER JOIN `tbl_countries_lang` tcl ON tcl.countrylang_country_id = tc.country_id AND tcl.countrylang_lang_id = 1 SET tc.country_code_alpha3 = 'MAF' WHERE tcl.country_name LIKE "St. Martin (French part)";

-- --- Dpo Payment Gateway--- --
ALTER TABLE `tbl_user_withdrawal_requests` CHANGE `withdrawal_comments` `withdrawal_instructions` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `tbl_user_withdrawal_requests` ADD `withdrawal_comments` TEXT NOT NULL AFTER `withdrawal_paypal_email_id`;

UPDATE `tbl_email_templates` SET `etpl_body` = '<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n    <tr>\n        <td style=\"background:#ff3a59;\">\n            <!--\n            page title start here\n            -->\n               \n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                <tbody>\n                    <tr>\n                        <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\n                            <h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\n                            <h2 style=\"margin:0; font-size:34px; padding:0;\">Abandoned Cart Deleted</h2></td>\n                    </tr>\n                </tbody>\n            </table>\n            <!--\n            page title end here\n            -->\n               </td>\n    </tr>\n    <tr>\n        <td>\n            <!--\n            page body start here\n            -->\n               \n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                <tbody>\n                    <tr>\n                        <td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\n                            <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                                <tbody>\n                                    <tr>\n                                        <td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear {user_full_name} </strong></td>\n                                    </tr>\n                                    <tr>\n                                        <td style=\"padding:0 10px; line-height:1.3; text-align:center; color:#333333;vertical-align:top; font-size: 30px;\">We noticed you removed <span style=\"text-decoration: underline;\">{product_name}</span> from your cart.</td>\n                                    </tr> \n                                               \n                                    <tr>\n                                    <td style=\"padding:20px 0 10px 10px; line-height:1.3; text-align:center; color:#999999;vertical-align:top; font-size:16px;\">Just for you : Get <span style=\"color:#333; font-weight: bold;\">{discount}</span> off on your order with code <span style=\"color:#333; font-weight: bold;\">{coupon_code}.</span>\n                                                   \n                                        <a href=\"{checkout_now}\" style=\"background: #ff3a59;border:none; border-radius: 4px; color: #fff; cursor: pointer;margin:10px 0 0 0;width: auto; font-weight: normal; padding: 10px 20px; display: inline-block; \">Check out now </a></td>\n                                    </tr>\n                                    \n                                </tbody>\n                            </table></td>\n                    </tr>\n                </tbody>\n            </table>\n            <!--\n            page body end here\n            -->\n        </td>\n    </tr>\n</table>' WHERE `tbl_email_templates`.`etpl_code` = 'abandoned_cart_deleted_discount_notification' AND `tbl_email_templates`.`etpl_lang_id` = 1;
UPDATE `tbl_email_templates` SET `etpl_body` = '<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n    <tr>\n        <td style=\"background:#ff3a59;\">\n            <!--\n            page title start here\n            -->\n               \n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                <tbody>\n                    <tr>\n                        <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\n                            <h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\n                            <h2 style=\"margin:0; font-size:34px; padding:0;\">Abandoned Cart Discount</h2></td>\n                    </tr>\n                </tbody>\n            </table>\n            <!--\n            page title end here\n            -->\n               </td>\n    </tr>\n    <tr>\n        <td>\n            <!--\n            page body start here\n            -->\n               \n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                <tbody>\n                    <tr>\n                        <td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\n                            <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                                <tbody>\n                                    \n                                    <tr>\n                                        <td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear {user_full_name} </strong></td>\n                                    </tr>\n                                    \n                                    <tr>\n                                                <td style=\"padding:0 10px; line-height:1.3; text-align:center; color:#333333;vertical-align:top; font-size: 30px;\">Finish your order before your items sell out!</td>\n                                    </tr> \n\n                                    <tr>\n                                        <td style=\"padding:20px 0 10px 10px; line-height:1.3; text-align:center; color:#999999;vertical-align:top; font-size:16px;\">Just for you : Get <span style=\"color:#333; font-weight: bold;\">{discount} OFF</span> off on your order with code <span style=\"color:#333; font-weight: bold;\">{coupon_code}.</span>\n\n                                        <a href=\"{checkout_now}\" style=\"background: #ff3a59;border:none; border-radius: 4px; color: #fff; cursor: pointer;margin:10px 0 0 0;width: auto; font-weight: normal; padding: 10px 20px; display: inline-block;\">Check out now </a></td>\n                                    </tr>\n                                    <tr>\n                                        <td style=\"padding:30px 0;\">\n                                            <table>\n                                                <tr>\n                                                    <td style=\"padding-right: 25px;\"><img style=\"border: solid 1px #ececec; padding: 10px; border-radius: 4px;\" src=\"{product_image}\"></td>\n                                                    <td style=\"text-align: left;\">\n                                                        <span style=\"font-size: 20px; font-weight:normal; color:#999999; \">{product_name}</span>\n                                                         <span style=\"font-size: 14px; font-weight: bold; color:#000000; display: block; padding: 20px 0;\">{product_price}</span>\n                                                    </td>\n                                                </tr>\n                                            </table>\n\n                                        </td>\n                                    </tr>\n                                    \n                                </tbody>\n                            </table></td>\n                    </tr>\n                </tbody>\n            </table>\n            <!--\n            page body end here\n            -->\n        </td>\n    </tr>\n</table>' WHERE `tbl_email_templates`.`etpl_code` = 'abandoned_cart_discount_notification' AND `tbl_email_templates`.`etpl_lang_id` = 1;
UPDATE `tbl_email_templates` SET `etpl_body` = '<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n    <tr>\n        <td style=\"background:#ff3a59;\">\n            <!--\n            page title start here\n            -->\n               \n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                <tbody>\n                    <tr>\n                        <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\n                            <h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\n                            <h2 style=\"margin:0; font-size:34px; padding:0;\">Abandoned Cart</h2></td>\n                    </tr>\n                </tbody>\n            </table>\n            <!--\n            page title end here\n            -->\n               </td>\n    </tr>\n    <tr>\n        <td>\n            <!--\n            page body start here\n            -->\n               \n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                <tbody>\n                    <tr>\n                        <td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\n                            <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                                <tbody>\n                                    <tr>\n                                        <td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear {user_full_name} </strong></td>\n                                    </tr>\n                                    <tr>\n                                        <td style=\"padding:0 10px; line-height:1.3; text-align:center; color:#333333;vertical-align:top; font-size: 30px;\">We noticed you left something behind!</td>\n                                    </tr>\n                                    <tr>\n                                        <td style=\"padding:30px 0;\">\n                                        <table>{product_detail_table}</table>\n                                        </td>\n                                    </tr>\n                                    \n                                </tbody>\n                            </table></td>\n                    </tr>\n                </tbody>\n            </table>\n            <!--\n            page body end here\n            -->\n        </td>\n    </tr>\n</table>' WHERE `tbl_email_templates`.`etpl_code` = 'abandoned_cart_email' AND `tbl_email_templates`.`etpl_lang_id` = 1;


-- --- Stripe Connect Fields Description Labels --- ---
INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) VALUES
('API_THE_BUSINESS_PUBLICLY_AVAILABLE_WEBSITE', 1, 'The business’s publicly available website.', 1),
('API_A_PUBLICLY_AVAILABLE_WEBSITE_FOR_HANDLING_SUPPORT_ISSUES', 1, 'A publicly available website for handling support issues.', 1),
('API_THE_CUSTOMER_FACING_BUSINESS_NAME', 1, 'The customer-facing business name.', 1),
('API_A_PUBLICLY_AVAILABLE_PHONE_NUMBER_TO_CALL_WITH_SUPPORT_ISSUES', 1, 'A publicly available phone number to call with support issues.', 1),
('API_A_PUBLICLY_AVAILABLE_EMAIL_ADDRESS_FOR_SENDING_SUPPORT_ISSUES_TO', 1, 'A publicly available email address for sending support issues to.', 1),
('API_ADDRESS_LINE_1', 1, 'Address line 1 (e.g., street, PO Box, or company name).', 1),
('API_ADDRESS_LINE_2', 1, 'Address line 2 (e.g., apartment, suite, unit, or building).', 1),
('API_ZIP_OR_POSTAL_CODE', 1, 'ZIP or postal code.', 1),
('API_CITY_DISTRICT_SUBURB_TOWN_OR_VILLAGE', 1, 'City, district, suburb, town, or village.', 1),
('API_TWO_LETTER_COUNTRY_CODE', 1, 'Two-letter country code (ISO 3166-1 alpha-2).', 1),
('API_STATE_COUNTY_PROVINCE_OR_REGION', 1, 'State, county, province, or region.', 1),
('API_THE_GOVERNMENT_ISSUED_ID_NUMBER', 1, 'The government-issued ID number of the individual, as appropriate for the representative’s country. (Examples are a Social Security Number in the U.S., or a Social Insurance Number in Canada).', 1),
('API_THE_INDIVIDUAL_FIRST_NAME', 1, 'The individual’s first name.', 1),
('API_THE_INDIVIDUAL_LAST_NAME', 1, 'The individual’s last name.', 1),
('API_THE_INDIVIDUAL_EMAIL_ADDRESS', 1, 'The individual’s email address.', 1),
('API_THE_INDIVIDUAL_PHONE_NUMBER', 1, 'The individual’s phone number.', 1),
('API_THE_MONTH_OF_BIRTH_BETWEEN_1_AND_12', 1, 'The month of birth, between 1 and 12.', 1),
('API_THE_DAY_OF_BIRTH_BETWEEN_1_AND_31', 1, 'The day of birth, between 1 and 31.', 1),
('API_THE_FOUR_DIGIT_YEAR_OF_BIRTH', 1, 'The four-digit year of birth.', 1),
('API_THE_COMPANY_LEGAL_NAME', 1, 'The company’s legal name.', 1),
('API_THE_COMPANY_PHONE_NUMBER', 1, 'The company’s phone number (used for verification).', 1),
('API_THE_BUSINESS_ID_NUMBER', 1, 'The business ID number of the company, as appropriate for the company’s country. (Examples are an Employer ID Number in the U.S., a Business Number in Canada, or a Company Number in the UK.)', 1),
('API_THE_RELATIONSHIP_PERSON_EMAIL_ADDRESS', 1, 'The relationship person’s email address.', 1),
('API_THE_RELATIONSHIP_PERSON_FIRST_NAME', 1, 'The relationship person’s first name.', 1),
('API_THE_RELATIONSHIP_PERSON_LAST_NAME', 1, 'The relationship person’s last name.', 1),
('API_THE_RELATIONSHIP_PERSON_PHONE_NUMBER', 1, 'The relationship person’s phone number.', 1),
('API_THE_RELATIONSHIP_PERSON_SOCIAL_SECURITY_NUMBER', 1, 'The relationship person’s social security number.', 1),
('API_THE_PERSON_TITLE', 1, 'The person’s title (e.g., CEO, Support Engineer).', 1),
('API_WHETHER_THE_PERSON_IS_AN_OWNER_OF_THE_ACCOUNT_LEGAL_ENTITY', 1, 'Whether the person is an owner of the account’s legal entity.', 1),
('API_THE_MERCHANT_CATEGORY_CODE', 1, 'The merchant category code for the account. MCCs are used to classify businesses based on the goods or services they provide.', 1),
('API_THE_NAME_OF_THE_PERSON_OR_BUSINESS_THAT_OWNS_THE_BANK_ACCOUNT', 1, 'The name of the person or business that owns the bank account. This field is required when attaching the bank account to a Customer object.', 1),
('API_THE_BANK_ACCOUNT_NUMBER', 1, 'The account number for the bank account, in string form. Must be a checking account.', 1),
('API_THE_ROUTING_NUMBER', 1, 'The routing number, sort code, or other country-appropriate institution number for the bank account. For US bank accounts, this is required and should be the ACH routing number, not the wire routing number. If you are providing an IBAN for account_number, this field is not required.', 1)
ON DUPLICATE KEY UPDATE label_caption = VALUES(label_caption);
-- --- Stripe Connect Fields Description Labels --- ---

INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) VALUES
('API_CONNECT_FLEXIBLE_SET_OF_FEATURES_INCLUDES', 1, 'Connect’s flexible set of features includes:', 1),
('API_ROUTE_FUNDS_TO_YOUR_RECIPIENTS', 1, 'Route funds to your recipients’ bank accounts and debit card flexibly and programmatically.', 1),
('API_DRIVE_REVENUE_FOR_YOUR_BUSINESS', 1, 'Drive revenue for your business by collecting fees for your services.', 1),
('API_ONBOARDING', 1, 'Onboarding.', 1),
('API_MOBILE_FRIENDLY_AND_CONVERSION_OPTIMIZED_UI', 1, 'Collect any information through your own flow and let Stripe take care of the rest through its mobile friendly and conversion-optimized UI.', 1)
ON DUPLICATE KEY UPDATE label_caption = VALUES(label_caption);

INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) VALUES
('LBL_COUNTRY_ALPHA3_CODE', 1, 'Alpha-3 Code', 1)
ON DUPLICATE KEY UPDATE label_caption = VALUES(label_caption);
-- -----------------------------TV-9.2.3.20201211-------------------

UPDATE `tbl_email_templates` SET `etpl_body` = '<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n    <tr>\n        <td style=\"background:#ff3a59;\">\n            <!--\n            page title start here\n            -->\n               \n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                <tbody>\n                    <tr>\n                        <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\n                            <h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\n                            <h2 style=\"margin:0; font-size:34px; padding:0;\">TaxApi Order Creation Failure</h2></td>\n                    </tr>\n                </tbody>\n            </table>\n            <!--\n            page title end here\n            -->\n               </td>\n    </tr>\n    <tr>\n        <td>\n            <!--\n            page body start here\n            -->\n               \n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                <tbody>\n                    <tr>\n                        <td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\n                            <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                                <tbody>\n                                    <tr>\n                                        <td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear Admin </strong><br />\n                                                System has tried to create an order/transaction on TaxApi after order is marked as completed by admin, but not able to create an Order/Transaction on TaxApi due to below Error on your site <a href=\"{website_url}\">{website_name}</a> with Yokart Order Invoice Number {invoice_number}.<br />\n                                                Please find the TaxApi Error information below.\n                                        </td>\n                                    </tr>\n                                    <tr>\n                                            <td style=\"padding:0 0 30px;\">{error_message}</td>\n                                    </tr>\n                                    \n                                </tbody>\n                            </table></td>\n                    </tr>\n                </tbody>\n            </table>\n            <!--\n            page body end here\n            -->\n        </td>\n    </tr>\n</table>' WHERE `tbl_email_templates`.`etpl_code` = 'taxapi_order_creation_failure' AND `tbl_email_templates`.`etpl_lang_id` = 1;
UPDATE `tbl_email_templates` SET `etpl_body` = '<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n    <tr>\n        <td style=\"background:#ff3a59;\">\n            <!--\n            page title start here\n            -->\n               \n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                <tbody>\n                    <tr>\n                        <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\n                            <h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\">Order Placed</h4>\n                            <h2 style=\"margin:0; font-size:34px; padding:0;\">Bank Transfer</h2></td>\n                    </tr>\n                </tbody>\n            </table>\n            <!--\n            page title end here\n            -->\n               </td>\n    </tr>\n    <tr>\n        <td>\n            <!--\n            page body start here\n            -->\n               \n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                <tbody>\n                    <tr>\n                        <td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\n                            <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                                <tbody>\n                                    <tr>\n                                    <td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear {vendor_name} </strong><br />\n                                      An order has been placed for your product(s) at <a href=\"{website_url}\">{website_name}</a>.<br />\n                                     Order details &amp; Shipping information are given below:</td>\n                                  </tr>\n                                 <tr>\n                                    <td style=\"padding:5px 0 30px;\">{order_items_table_format}</td>\n                                 </tr>                                    \n                                </tbody>\n                            </table></td>\n                    </tr>\n                </tbody>\n            </table>\n            <!--\n            page body end here\n            -->\n        </td>\n    </tr>\n</table>' WHERE `tbl_email_templates`.`etpl_code` = 'vendor_bank_transfer_order_email' AND `tbl_email_templates`.`etpl_lang_id` = 1;
UPDATE `tbl_email_templates` SET `etpl_body` = '<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n    <tr>\n        <td style=\"background:#ff3a59;\">\n            <!--\n            page title start here\n            -->\n               \n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                <tbody>\n                    <tr>\n                        <td style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\n                            <h4 style=\"font-weight:normal; text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\">Order Placed</h4>\n                            <h2 style=\"margin:0; font-size:34px; padding:0;\">Cash On Delivery</h2></td>\n                    </tr>\n                </tbody>\n            </table>\n            <!--\n            page title end here\n            -->\n               </td>\n    </tr>\n    <tr>\n        <td>\n            <!--\n            page body start here\n            -->\n               \n            <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                <tbody>\n                    <tr>\n                        <td style=\"background:#fff;padding:0 30px; text-align:center; color:#999;vertical-align:top;\">\n                            <table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                                <tbody>\n                                    <tr>\n                                        <td style=\"padding:20px 0 30px;\"><strong style=\"font-size:18px;color:#333;\">Dear {vendor_name} </strong><br />\n                                                An order has been placed for your product(s) at <a href=\"{website_url}\">{website_name}</a>.<br />\n                                                Order details &amp; Shipping information are given below:</td>\n                                    </tr>\n                                    <tr>\n                                            <td style=\"padding:5px 0 30px;\">{order_items_table_format}</td>\n                                    </tr>\n                                    \n                                </tbody>\n                            </table></td>\n                    </tr>\n                </tbody>\n            </table>\n            <!--\n            page body end here\n            -->\n        </td>\n    </tr>\n</table>' WHERE `tbl_email_templates`.`etpl_code` = 'vendor_cod_order_email' AND `tbl_email_templates`.`etpl_lang_id` = 1;

INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) VALUES
('MSG_INVALID_FATBIT_USERNAME', 1, 'Username Must Start With A Letter And Can Contain Only Alphanumeric Characters. Length Must Be Between 4 To 20 Characters', 1)
ON DUPLICATE KEY UPDATE label_caption = VALUES(label_caption);

-- --- Paynow Payment Gateway--- --
INSERT IGNORE INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Paynow', '13', 'Paynow', '0', '22');
-- --- Paynow Payment Gateway--- --

-- --- Paystack Payment Gateway--- --
INSERT IGNORE INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Paystack', '13', 'Paystack', '0', '23');
-- --- Paystack Payment Gateway--- --

-- --- Authorize Aim Payment Gateway Code Deprecated And No Update For Those Fixes --- --
DELETE tp, tpl, tps
FROM tbl_plugins tp
LEFT JOIN tbl_plugins_lang tpl ON tpl.pluginlang_plugin_id = tp.plugin_id
LEFT JOIN tbl_plugin_settings tps ON tps.pluginsetting_plugin_id = tp.plugin_id
WHERE tp.plugin_code = 'AuthorizeAim';
-- --- Authorize Aim Payment Gateway Code Deprecated And No Update For Those Fixes --- --

-- --- Remove Invalid Country Code Rows --- --
DELETE tc, tcl, ts, tsl FROM `tbl_countries` tc
LEFT JOIN tbl_countries_lang tcl ON tcl.countrylang_country_id = tc.country_id
LEFT JOIN tbl_states ts ON ts.state_country_id = tc.country_id
LEFT JOIN tbl_states_lang tsl ON tsl.statelang_state_id = ts.state_id
WHERE `country_code` = 'ZZ' OR country_code = 'AA' OR country_code = 'YY' OR country_code = 'qw';
-- --- Remove Invalid Country Code Rows --- --

-- ---- Currency Value Size ---- -- 
ALTER TABLE `tbl_currency` CHANGE `currency_value` `currency_value` DECIMAL(20,8) NOT NULL;
-- ---- Currency Value Size ---- -- 

-- ---- IOS NEW LANG LABELS ---- --
INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) VALUES
("APP_QTY", 1, "Qty", 2),
("APP_PICKUP_ORDER", 1, "Pickup Order", 2),
("APP_ORDER_DETAILS", 1, "Order Details", 2),
("APP_SOLD_BY", 1, "Sold By", 2),
("APP_SAVE_FOR_LATER", 1, "Save for Later", 2),
("APP_YOU_HAVE_TOTAL", 1, "You have total", 2),
("APP_ENTER_POINTS_TO_REDEEM", 1, "Enter points to redeem", 2),
("APP_POINTS", 1, "Points", 2),
("APP_BUT_YOU_CAN_REDEEM", 1, "but you can redeem", 2),
("APP_AT_ONE_TIME", 1, "at one time", 2),
("APP_AVAILABLE_BALANCE", 1, "AVAILABLE BALANCE", 2),
("APP_PAYMENT_DETAILS", 1, "Payment Details", 2),
("APP_ADD_A_NEW_CARD", 1, "Add a new Card", 2),
("APP_MAKE_DEFAULT", 1, "MAKE DEFAULT", 2),
("APP_REMOVE", 1, "REMOVE", 2),
("APP_CARD_HOLDER_NAME", 1, "CARD HOLDER NAME", 2),
("APP_VALIDITY", 1, "VALIDITY", 2),
("APP_PLEASE_ENTER_BANK_ADDRESS", 1, "Please enter bank address", 2),
("APP_PLEASE_ENTER_OTHER_INFO", 1, "Please enter comments/ other info", 2),
("APP_HOME", 1, "Home", 2),
("APP_WORK", 1, "Work", 2),
("APP_OTHERS", 1, "Others", 2),
("APP_SHIPPING_ADDRESS", 1, "Shipping Address", 2),
("APP_BILLING_ADDRESS", 1, "Billing Address", 2),
("APP_ORDERID", 1, "OrderID", 2),
("APP_ORDER_STATUS", 1, "Order Status", 2),
("APP_PLEASE_SELECT_TIME_SLOT", 1, "Please select time slot", 2),
("APP_ADD_NEW_WISHLIST", 1, "Add New WishList", 2),
("APP_BRAND_LIST", 1, "Brand List", 2),
("APP_ALL", 1, "All", 2),
("APP_BANK_WITHDRAW", 1, "Bank Withdraw", 2),
("APP_PAYPAL_WITHDRAW", 1, "PayPal Withdraw", 2),
("APP_PLEASE_SELECT_BILLING_ADDRESS", 1, "Please select billing address", 2),
("APP_CARD_LIST", 1, "Card List", 2),
("APP_PLEASE_ADD_YOUR_CARD_DETAILS_FOR_FURTHER_PROCESS", 1, "Please add your card details for further process", 2),
("APP_LOGIN_REQUIRED", 1, "Login Required", 2),
("APP_ITEM_NOT_AVAILABLE_FOR_SHIPPING_PICKUP_ENTIRE_ORDER", 1, "Item not available for shipping Pickup entire order", 2),
("APP_PLEASE_SELECT_COUPON_CODE", 1, "Please select coupon code", 2),
("APP_CARD_HOLDER", 1, "Card Holder", 2),
("APP_PLEASE_SELECT_PICKUP_ADDRESS", 1, "Please select pickup address", 2),
("APP_SAVE_CARD_FOR_FUTURE_PAYMENTS", 1, "Save card for future payments", 2),
("APP_ADD_CARD", 1, "Add Card", 2),
("APP_PAY_NOW", 1, "Pay Now", 2),
("APP_TRACKING_NO", 1, "Tracking No", 2),
("APP_SEARCH_FOR_AREA_LANDMARK_LOCALITY", 1, "Search for area landmark,locality", 2),
("APP_CURRENT_LOCATION", 1, "Current Location", 2),
("APP_USING_GPS", 1, "Using GPS", 2),
("APP_SEARCH_LOCATION", 1, "Search Location", 2),
("APP_LOCATION_NOT_FOUND", 1, "Location not found", 2),
("APP_ORDER_TRACKING", 1, "Order Tracking", 2),
("APP_BUSSINESS_NAME", 1, "Bussiness name", 2),
("APP_BRANCH", 1, "Branch", 2),
("APP_BANK_TRANSFER", 1, "Bank Transfer", 2),
("APP_PLEASE_ENTER_PAYMENT_METHOD_NAME", 1, "Please enter payment method name", 2),
("APP_PLEASE_ENTER_TRANSCATION_ID", 1, "Please enter transaction Id", 2),
("APP_PAYMENT_METHOD", 1, "Payment Method", 2),
("APP_TRANSACTION_ID", 1, "Transaction Id", 2),
("APP_EDIT_ADDRESS", 1, "Edit Address", 2),
("APP_ENTER_YOUR_FIRST_ADDRESS", 1, "Please enter your first address", 2),
("APP_PLEASE_ENTER_YOUR_POSTAL_CODE", 1, "Please enter your postal code", 2),
("APP_SELECT_COUPON_CODE", 1, "Select coupon code", 2),
("APP_PICKUP_DETAILS", 1, "Pickup Details", 2),
("APP_PICKUP_DATE_AND_TIME", 1, "Pickup Date & Time", 2),
("APP_LIST_NAME", 1, "List Name", 2),
("APP_PLEASE_ENTER_LIST_NAME", 1, "Please enter list name", 2),
("APP_CREATE_WISHLIST", 1, "Create WishList", 2),
("APP_CREATE", 1, "Create", 2),
("APP_APPLY_COUPON", 1, "Apply Coupon", 2),
("APP_PROCEED_TO_CHECKOUT", 1, "Proceed to Checkout", 2),
("APP_PROCEED_TO_SHIPPING", 1, "Proceed to Shipping", 2),
("APP_PROCEED_TO_PAYMENT", 1, "Proceed to Payment", 2),
("APP_REVIEW_ORDER", 1, "Review Order", 2),
("APP_YOU_SELECTED", 1, "You selected", 2),
("APP_ITEMS_IN_YOUR_CART", 1, "items in your cart", 2),
("APP_PROCEED_TO_PICKUP", 1, "Proceed to Pickup", 2),
("APP_CHANGE", 1, "Change", 2),
("APP_POINTS_BUT_YOU_CAN_REDEEM", 1, "points but you can redeem", 2),
("APP_GO_TO_CART", 1, "Go to Cart", 2),
("APP_YOU_HAVE_NO_ITEMS_WISHLIST", 1, "You have no items in your wishlist", 2),
("APP_SHIP_MY_ORDER", 1, "Ship My Order", 2),
("APP_PICKUP_MY_ORDER", 1, "Pickup My Order", 2)
ON DUPLICATE KEY UPDATE label_caption = VALUES(label_caption);
-- ---- IOS NEW LANG LABELS ---- --

-- ---- ANDROID NEW LANG LABELS ---- --
INSERT INTO `tbl_language_labels` (`label_key`, `label_lang_id`, `label_caption`, `label_type`) VALUES
("APP_BROWSE", 1, "Browse", 2),
("APP_SHOPS", 1, "Shops", 2),
("APP_CATEGORIES", 1, "Categories", 2),
("APP_BLOG", 1, "Blogs", 2),
("APP_BY", 1, "By", 2),
("APP_ON", 1, "On", 2),
("APP_WITH_PHN_NO", 1, "With phone number?", 2),
("APP_WITH_EMAIL", 1, "With Email?", 2),
("APP_MOBILE_VERIFICATION", 1, "Mobile Verification", 2),
("APP_VERIFICATION", 1, "Verification", 2),
("APP_VERIFICATION_TEXT", 1, "Please enter the verification code sent to", 2),
("APP_SENDOTP_THEN_VERIFY_OLD_NUMBER", 1, "Please get otp and then verify old phone number ", 2),
("APP_VERIFY", 1, "Verify", 2),
("APP_CHANGE_PHONE", 1, "Change Phone Number", 2),
("APP_UPDATE_PHONE", 1, "Update Phone Number", 2),
("APP_GET_OTP", 1, "Get Otp", 2),
("APP_RESEND", 1, "Resend", 2),
("APP_NOT_RECEIVE_VERIFICATION_CODE", 1, "If you didn\'t receive verification code", 2),
("APP_UPDATE_EMAIL", 1, "Update Email", 2),
("APP_CONFIGURE_DETAIL", 1, "Configure Detail", 2),
("APP_SEND_OTP_IN", 1, "Send Otp In", 2),
("APP_ALERTS", 1, "Alerts", 2),
("APP_OFFERS", 1, "Offers", 2),
("APP_USE_PHONE_NUMBER_INSTEAD", 1, "Use Phone Number Instead", 2),
("APP_USE_EMAIL_INSTEAD", 1, "Use Email Instead", 2),
("APP_READ_NOTIFICATION", 1, "Read All", 2),
("APP_PLEASE_ENTER_YOUR_NEW_PHONE_NUMBER_A_VERIFICATION_CODE_WILL_BE_SENT_TO_THE_NEW_NUMBER", 1, "Please enter your new Phone number. A verification code will be sent to the new number", 2),
("APP_PLEASE_CHECK_YOUR_CURRENT_PHONE_NUMBER_AND_GET_OTP", 1, "Please check your current phone number and Get Otp", 2),
("APP_DELIVER_TO", 1, "DELIVER TO", 2),
("APP_ADD_NEW_ADDRESS", 1, "Add New Address", 2),
("APP_FIRST_NAME", 1, "First Name", 2),
("APP_LAST_NAME", 1, "Last Name", 2),
("APP_EMAIL_ADDRESS", 1, "Email Address", 2),
("APP_SELECT_COUNTRY", 1, "Select Country", 2),
("APP_SELECT_STATE", 1, "Select State", 2),
("APP_SELECT_CITY", 1, "Select City", 2),
("APP_APARTMENT", 1, "Apartment,Suit ,etc", 2),
("APP_PIN_CODE", 1, "Pin Code", 2),
("APP_USE_THIS_AS_MY_DEFAULT_ADDRESS", 1, "Use this as my default address", 2),
("APP_I_AGREE", 1, "I agree to the <font color='#5CBCE6'><b>Terms &amp; Conditions</b></font>", 2),
("APP_DEFAULT_ADDRESS", 1, "Default Address", 2),
("APP_ADD_NEW_ADDRESS_PLUS", 1, "<u>+ Add New Address</u>", 2),
("APP_OTHER_ADDRESS", 1, "Other Address", 2),
("APP_SAVE_FOR_LATER", 1, "Save For Later", 2),
("APP_SHIP_MY_ORDER", 1, "Ship My Order", 2),
("APP_PICK_UP_IN_STORE", 1, "Pick Up In Store", 2),
("APP_WITHDRAW_REQUEST_TITLE", 1, "Withdraw Request", 2),
("APP_MONEY_IN_YOUR_WALLET", 1, "Money in your wallet", 2),
("APP_FILL_INFORMATION", 1, "Fill information", 2),
("APP_ENTER_AMOUNT", 1, "Enter Amount", 2),
("APP_BANK_NAME_HINT", 1, "Bank Name", 2),
("APP_ACCOUNT_HOLDER_NAME_HINT", 1, "Account Holder Name", 2),
("APP_IFSC_CODE_HINT", 1, "IFSC/Swift Code", 2),
("APP_OTHER_INFO", 1, "Other Info Structure", 2),
("APP_PROCEED", 1, "Proceed", 2),
("APP_ACCOUNT_NUMBER", 1, "Account Number", 2),
("APP_BANK_ADDRESS", 1, "Bank Address", 2),
("APP_ADD_MONEY_TITLE", 1, "Add Money", 2),
("APP_AVAILABLE_BALANCE", 1, "Available Balance", 2),
("APP_AVAILABLE_BALANCE_", 1, "AVAILABLE BALANCE %s", 2),
("APP_ADD_MONEY_TO_WALLET", 1, "Add Money to wallet", 2),
("APP_REWARD_AND_OFFERS", 1, "<![CDATA[Rewards & Offers]]>", 2),
("APP_REWARD_POINTS", 1, "Reward Points", 2),
("APP_EXPIRE_ON", 1, "Expires on", 2),
("APP_TERMS_ANDCONDITIONS_APPLY", 1, "Terms and conditions apply*", 2),
("APP_TOTAL_AMOUNT", 1, "Total Amount", 2),
("APP_CHANGE", 1, "Change", 2),
("APP_PICKUP_ADDRESS_TITLE", 1, "Pick Address", 2),
("APP_ENTER_POINTS_TO_REDEEM", 1, "Enter points to redeem", 2),
("APP_YOU_HAVE_TOTAL_POINTS", 1, "You have total %s points", 2),
("APP_MONTH", 1, "Month", 2),
("APP_YOURS_TOTAL_POINTS", 1, "You have total %s points but you can redeem %s at one time.", 2),
("APP_ENTER_POINTS_REDEEM", 1, "Enter points to redeem", 2),
("APP_WISHLIST", 1, "Wishlist", 2),
("APP_YOUR_LIST", 1, "Your List", 2),
("APP_WISHLIST_NAME", 1, "Shopping List", 2),
("APP_CHOOSE_PAYMENT_METHOD", 1, "Choose Payment Method", 2),
("APP_BILLING_ADDRESS", 1, "Billing Address", 2),
("APP_SHIPPING_ADDRESS", 1, "Shipping Address", 2),
("APP_SHIPPING_METHOD", 1, "Shipping Method", 2),
("APP_SHIPPING_PROVIDED", 1, "Shipping Provided", 2),
("APP_CONFIRM", 1, "Confirm", 2),
("APP_FOLLOW_US", 1, "Follow us", 2),
("APP_HELPFUL", 1, "Helpful", 2),
("APP_HELPFUL_NOT", 1, "Not Helpful", 2),
("APP_ENTER_YOUR_SUBJECT", 1, "Enter your subject", 2),
("APP_YOUR_SUBJECT", 1, "Your subject", 2),
("APP_YOUR_MESAGE", 1, "Your Message", 2),
("APP_OTHER", 1, "Other", 2),
("APP_SEE_ALL_PRODUCTS", 1, "See All Products", 2),
("APP_ADD_NEW_CARD", 1, "<u>+ Add new card</u>", 2),
("APP_SAVED_CARDS", 1, "Saved Cards", 2),
("APP_NO_CARD_ADDED", 1, "No Card Added", 2),
("APP_ADD", 1, "ADD", 2),
("APP_ADD_CARD", 1, "Add a New Card", 2),
("APP_CARD_NUMBER", 1, "CARD NUMBER", 2),
("APP_CARD_EXPIRY", 1, "CARD EXPIRY", 2),
("APP_CVV", 1, "CVV", 2),
("APP_CREDIT_CARD", 1, "Credit Card", 2),
("APP_DEBIT_CARD", 1, "Debit Card", 2),
("APP_CARD_HOLDER_NAME", 1, "Card Holder Name", 2),
("APP_VALIDITY", 1, "Validity", 2),
("APP_MAKE_DEFAULT", 1, "Make Default", 2),
("APP_ERROR_CARD_DETAILS", 1, "Please add card details", 2),
("APP_CARD_ADDED_SUCCESSFULLY", 1, "Card Added Successfully", 2),
("APP_INVALID_CUSTOMER_ID", 1, "Invalid Customer Id", 2),
("APP_SELECT_CARD", 1, "Select Card", 2),
("APP_PAY", 1, "Pay", 2),
("APP_SAVE_CARD_FOR_FUTURE_PAYMENTS", 1, "Save Card for future payments", 2),
("APP_CARD_DETAILS", 1, "Card Details", 2),
("APP_INVALID_CARD_NUMBER", 1, "Invalid Card Number", 2),
("APP_INVALID_EXPIRY_DATE", 1, "Invalid Expiry date", 2),
("APP_INVALID_CVV", 1, "Invalid CVV", 2),
("APP_INVALID_CARD_HOLDER_NAME", 1, "Invalid Card Holder Name", 2),
("APP_NO_CARD_SELECTED", 1, "No card selected for payment.", 2),
("APP_ADDRESS_TYPE", 1, "Address Type", 2),
("APP_ADDRESS_NAME", 1, "Full Name", 2),
("APP_ADDRESS", 1, "Address", 2),
("APP_ADDRESS_CITY", 1, "City", 2),
("APP_ERROR_OTP", 1, "Please enter otp", 2),
("APP_SEARCH_ANY_PRODUCT", 1, "Search Any Product...", 2),
("APP_SELECT_ADDRESS_TYPE", 1, "Please select address type", 2),
("APP_ADDRESS_TYPE_HOME", 1, "Home", 2),
("APP_ADDRESS_TYPE_OTHER", 1, "Other", 2),
("APP_ADDRESS_TYPE_WORK", 1, "Work", 2),
("APP_CHOOSE_PICKUP_ADDRESS", 1, "Choose Pickup Address", 2),
("APP_SELECT_SHIPPING_METHOD", 1, "Select Shipping Method", 2),
("APP_PICK_UP_ADDRESS", 1, "Select PickUp Address", 2),
("APP_SELECT", 1, "Select", 2),
("APP_TIME_SLOT", 1, "Time Slots", 2),
("APP_SCHEDULE_PICKUP", 1, "Schedule Pickup", 2),
("APP_SHOW", 1, "Show", 2),
("APP_SAVED_FOR_LATER_LABEL", 1, "You have %s items saved for later", 2),
("APP_CART_SELECTED_ITEMS", 1, "You selected '%s items in the cart", 2),
("APP_SHOP_DETAILS", 1, "Shop Details", 2),
("APP_SAVED_FOR_LATER_ITEMS", 1, "Saved For Later (%s Item)", 2),
("APP_SOLD_BY_LABEL", 1, "Sold by", 2),
("APP_REMOVE_CART_ITEM_LABEL", 1, "Are you sure want to remove this?", 2),
("APP_ENTER_COUPON_CODE", 1, "Apply Coupon", 2),
("APP_CART_SHIPPING_ERROR_MSG", 1, "Some of your items are not available for shipping", 2),
("APP_CART_PICKUP_ERROR_MSG", 1, "Some of your items are not available for pickup", 2),
("APP_CART_PICK_ORDER", 1, "Pickup Entire Order", 2),
("APP_CART_SHIP_ORDER", 1, "Ship Entire Order", 2),
("APP_COUPON_CODE", 1, "Coupon Code", 2),
("APP_REWARD_POINTS_APPLIED_MSG", 1, "Reward Points %s Successfully Used", 2),
("APP_WISHLIST_CREATE_NEW", 1, "Create new", 2),
("APP_JAN", 1, "January", 2),
("APP_FEB", 1, "February", 2),
("APP_MAR", 1, "March", 2),
("APP_APR", 1, "April", 2),
("APP_MAY", 1, "May", 2),
("APP_JUN", 1, "June", 2),
("APP_JUL", 1, "July", 2),
("APP_AUG", 1, "August", 2),
("APP_SEP", 1, "September", 2),
("APP_OCT", 1, "October", 2),
("APP_NOV", 1, "November", 2),
("APP_DEC", 1, "December", 2),
("APP_SELECT_SHIPPING_ERROR", 1, "Please select shipping", 2),
("APP_REMOVE_APPLIED_REWARD_POINTS", 1, "Remove Applied Reward Points", 2),
("APP_ERROR_SELECT_PAYMENT_METHOD", 1, "Please select payment method", 2),
("APP_CREDIT_CARD_NUMBER", 1, "Credit Card Number", 2),
("APP_MM_YY", 1, "MM/YY", 2),
("APP_NAME_ON_CARD", 1, "Name On Card", 2),
("APP_SAVE_CARD_LABEL", 1, "Save Card for future payments", 2),
("APP_PAYMENTS_SHOW_NAME", 1, "Successfully received payment data for %s!", 2),
("APP_ERROR_SELECT_COUPON", 1, "Please select coupon", 2),
("APP_MIN_ORDER_QTY", 1, "Min Order qty is", 2),
("APP_SHIPPING_NOT_AVAILABLE_MSG", 1, "Product Is Not Available For Shipping", 2),
("APP_DIGITAL_PRODUCT_SHIPPING_MSG", 1, "Digital Product Is Not Available For Shipping", 2),
("APP_DIGITAL_PRODUCT_PICKUP_MSG", 1, "Digital Product Is Not Available For Pickup", 2),
("APP_ERROR_SELECT_TIME_SLOT", 1, "Please select pickup time slot.", 2),
("APP_ERROR_SELECT_PICKUP_DATE", 1, "Please select pickup date.", 2),
("APP_GOOGLE_PAY", 1, "Google Pay", 2),
("APP_BUSSINESS_NAME", 1, "Bussiness Name", 2),
("APP_BANK_NAME", 1, "Bank Name", 2),
("APP_BANK_BRANCH", 1, "Bank Branch", 2),
("APP_ACCOUNT_", 1, "Account #", 2),
("APP_IFSC_MICR", 1, "IFSC/MICR", 2),
("APP_ROUTING", 1, "Routing #", 2),
("APP_TRANSACTION_ID", 1, "Transaction id", 2),
("APP_COMMENTS", 1, "Comments", 2),
("APP_CONFIRM_ORDER", 1, "Confirm Order", 2),
("APP_TRANSFER_DETAILS", 1, "Transfer Details", 2),
("APP_ERROR_PICKUP_ADDRESS_SELECT", 1, "Please select %s pickup address", 2),
("APP_ALL", 1, "All", 2),
("APP_SEND", 1, "Debit", 2),
("APP_RECEIVE", 1, "Credit", 2),
("APP_ERROR_WISHLIST_NAME", 1, "Please enter list name", 2),
("APP_PAYMENT", 1, "Payment", 2),
("APP_RATE_PURCHASE", 1, "Rate Your Purchase", 2),
("APP_USE_PHONE", 1, "Use Phone", 2),
("APP_USE_EMAIL", 1, "Use Email", 2),
("APP_WELCOME_BACK", 1, "Welcome Back", 2),
("APP_SIGN_IN_TO_CONTINUE", 1, "Sign in to continue", 2),
("APP_OR_CONNECT_USING", 1, "Or Connect using", 2),
("APP_LOGIN_WITH_SOCIAL", 1, "Login with Social", 2),
("APP_DON_T_YOU_HAVE_AN_ACCOUNT", 1, "Don\'t you have an account?", 2),
("APP_REGISTER", 1, "Register", 2),
("APP_FORGOT_YOUR_PASSWORD", 1, "Forgot your password?", 2),
("APP_ENTER_YOUR_EMAIL_ADDRESS_OR_PHONE_NUMBER_TO_RECOVER", 1, "Enter your email address or phone number to recover", 2),
("APP_LOGIN_REQUIRED", 1, "Login Required", 2),
("APP_LOGIN_WITH_FACEBOOK", 1, "Login with Facebook", 2),
("APP_LOGIN_WITH_GOOGLE", 1, "Sign in with Google", 2),
("APP_LOGIN_WITH_INSTAGRAM", 1, "Login with Instagram", 2),
("APP_ERROR_NOT_SELECTED_PAYMENT_METHOD", 1, "Payment method not selected.", 2),
("APP_ORDER_STATUS", 1, "Order Status", 2),
("APP_ORDER_ID", 1, "Order ID", 2),
("APP_GOTO_CART", 1, "Goto Cart", 2),
("APP_APP_NOT_FOUND", 1, "No app found for open file", 2),
("APP_BANK_PAYOUT", 1, "Bank Withdraw", 2),
("APP_PAYPAL_PAYOUT", 1, "Paypal Withdraw", 2),
("APP_HINT_PAYPAL_ID", 1, "Paypal ID", 2),
("APP_ERROR_ADD_MONEY_FAILED", 1, "Add Money failed, please try again.", 2),
("APP_ADD_WISHLIST_MSG", 1, "Add this item in following list", 2),
("APP_CONTINUE", 1, "Continue", 2),
("APP_PICKUP_ADDRESS", 1, "Pickup Address", 2),
("APP_INVALID_WITHDRAW_AMOUNT", 1, "Invalid Withdraw Amount", 2),
("APP_LOCATION_MSG", 1, "We need to access your Location to provide you better experience of the product listing.\nWe only use the location when you are actively using the app.", 2),
("APP_STORAGE_MSG", 1, "To allow you to update profile picture, we need to have access to the device Storage.", 2),
("APP_SPONSERD", 1, "Sponsored", 2),
("APP_I_AGREE_TO_THE", 1, "I Agree to the", 2),
("APP_AND", 1, "and", 2),
("APP_BY_SIGNING_IN_YOU_AGREE_TO_OUR", 1, "By Signing in, you agree to our", 2),
("APP_BILLING_SHIPPING_SAME_MSG", 1, "Billing address same as Shipping address", 2),
("APP_CHOOSE_BILLING_ADDRESS", 1, "Choose Billing Address", 2),
("APP_ERROR_SELECT_OPTION_VALUES", 1, "Please select option values", 2),
("APP_ERROR_SELECT_BILLING_ADDRESS", 1, "Please select billing address", 2),
("APP_USING_GPS", 1, "Using GPS", 2),
("APP_CURRENT_LOCATION", 1, "Current Location", 2),
("APP_SAVED_ADDRESSES", 1, "Saved Addresses", 2),
("APP_LOCATION_NOT_FOUND", 1, "Location not found", 2),
("APP_NOT_AVAIALBLE", 1, "Not available for your location", 2),
("APP_ORDER_TRACKING", 1, "Order Tracking", 2),
("APP_INCLUSIVE_TAX", 1, "Inclusive All Taxes", 2),
("APP_EXPLORE", 1, "Explore", 2),
("APP_DATE_EXPIRED", 1, "Date Expired", 2),
("APP_CREATE_WISTLIST", 1, "Create WistList", 2),
("APP_LIST_NAME", 1, "List Name", 2),
("APP_CREATE", 1, "Create", 2),
("APP_VALUE", 1, "Value", 2),
("APP_COUPON_PLACEHOLDER", 1, "%s off upto %s on minimum purchase of %s.", 2),
("APP_APPLY", 1, "Apply", 2),
("APP_MY_SHOP", 1, "My Shop", 2),
("APP_OTHER_SELLERS", 1, "Other Sellers", 2),
("APP_VIEW_MORE_INFO", 1, "<u>View More Info</u>", 2),
("APP_CARD_HOLDER_NAME_SAMPLE", 1, "NAME", 2),
("APP_CARD_HOLDER", 1, "CARD HOLDER", 2),
("APP_EXPIRY", 1, "EXPIRY", 2),
("APP_MM_YY", 1, "MM/YY", 2),
("APP_CARD_MM_YY", 1, "MM/YY", 2),
("APP_HINT_CVV_FRONT", 1, "0000", 2),
("APP_ERROR_INVALID_MONTH", 1, "Invalid month", 2),
("APP_ERROR_CARD_EXPIRED", 1, "Card expired", 2),
("APP_ERROR_SELECT_PICKUP_ADDRESS", 1, "Please select pickup address", 2),
("APP_MATERIAL_CALENDAR_TODAY_BUTTON", 1, "Today", 2),
("APP_MATERIAL_CALENDAR_POSITIVE_BUTTON", 1, "OK", 2),
("APP_MATERIAL_CALENDAR_NEGATIVE_BUTTON", 1, "Cancel", 2),
("APP_ERROR_ENTER_REWARD_POINTS", 1, "Please enter reward points", 2),
("APP_ERROR_INVALID_REWARD_POINTS", 1, "Please enter valid reward points", 2),
("APP_READ_MORE", 1, "show more", 2),
("APP_READ_LESS", 1, "show less", 2),
("APP_MATERIAL_CALENDAR_MONDAY", 1, "M", 2),
("APP_MATERIAL_CALENDAR_TUESDAY", 1, "T", 2),
("APP_MATERIAL_CALENDAR_WEDNESDAY", 1, "W", 2),
("APP_MATERIAL_CALENDAR_THURSDAY", 1, "T", 2),
("APP_MATERIAL_CALENDAR_FRIDAY", 1, "F", 2),
("APP_MATERIAL_CALENDAR_SATURDAY", 1, "S", 2),
("APP_MATERIAL_CALENDAR_SUNDAY", 1, "S", 2)
ON DUPLICATE KEY UPDATE label_caption = VALUES(label_caption);
-- ---- ANDROID NEW LANG LABELS ---- --
-- ----------------------TV-9.2.3.20201222--------------------------

DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'MSG_Setup_Successfull';
-- --------------------------------------------------------

--
-- Table structure for table `tbl_system_logs`
--

CREATE TABLE `tbl_system_logs` (
  `slog_id` int(11) NOT NULL,
  `slog_type` int(11) NOT NULL,
  `slog_details` text NOT NULL,
  `slog_created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_system_logs`
--
ALTER TABLE `tbl_system_logs`
  ADD PRIMARY KEY (`slog_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_system_logs`
--
ALTER TABLE `tbl_system_logs`
  MODIFY `slog_id` int(11) NOT NULL AUTO_INCREMENT;