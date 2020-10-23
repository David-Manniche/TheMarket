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
("APP_EXPLORE", 1, "Explore", 2);

--
-- ShipStation Plugin
--

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

-- Not working need to update library --
-- INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Twocheckout', '13', 'Twocheckout', '0', '1'); --
-- Not working need to update library --

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