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


INSERT INTO `tbl_plugins` (`plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES ('Stripe Connect', 11, 'StripeConnect', 1, 1); 
ALTER TABLE `tbl_orders` ADD `order_pmethod_type` TINYINT(2) NOT NULL COMMENT 'Default Or Plugin In PaymentMethods' AFTER `order_pmethod_id`;
UPDATE `tbl_orders` SET `order_pmethod_type` = '1' WHERE order_pmethod_type != 2;

ALTER TABLE `tbl_tax_categories` DROP INDEX `saletaxcat_identifier`; 
ALTER TABLE `tbl_tax_categories` DROP INDEX `taxcat_identifier`; 
ALTER TABLE `tbl_tax_categories` ADD UNIQUE( `taxcat_identifier`, `taxcat_plugin_id`); 
ALTER TABLE `tbl_tax_categories` ADD `taxcat_parent` INT(11) NOT NULL AFTER `taxcat_code`; 
INSERT INTO `tbl_plugins` (`plugin_id`, `plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES (NULL, 'TaxJar', '10', 'TaxJarTax', '0', '11'); 
ALTER TABLE `tbl_order_products_lang` CHANGE `op_product_tax_options` `op_product_tax_options` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL; 
ALTER TABLE `tbl_order_products` ADD `op_tax_code` VARCHAR(150) NOT NULL AFTER `op_actual_shipping_charges`; 
ALTER TABLE `tbl_order_user_address` ADD `oua_state_code` VARCHAR(100) NOT NULL AFTER `oua_state`;

INSERT INTO `tbl_email_templates` (`etpl_code`, `etpl_lang_id`, `etpl_name`, `etpl_subject`, `etpl_body`,
`etpl_replacements`, `etpl_status`) VALUES ('taxapi_order_creation_failure', 1, 'TaxApi Order Creation Failure Email',
'TaxApi Order Creation Failed at {website_name}', '<table width=\"100%\" border=\"0\" cellpadding=\"0\"
cellspacing=\"0\" bgcolor=\"#ecf0f1\" style=\"font-family:Arial; color:#333;
line-height:26px;\">\r\n	<tbody>\r\n		<tr>\r\n			<td style=\"background:#ff3a59;padding:30px 0
10px;\">\r\n				<!--\r\n				header start here\r\n				-->\r\n\r\n				<table width=\"600\" border=\"0\" align=\"center\"
cellpadding=\"0\" cellspacing=\"0\">\r\n					<tbody>\r\n						<tr>\r\n							<td><a
href=\"{website_url}\">{Company_Logo}</a></td>\r\n							<td
style=\"text-align:right;\">{social_media_icons}</td>\r\n						</tr>\r\n					</tbody>\r\n				</table>\r\n				<!--\r\n				header
end here\r\n				-->\r\n				   </td>\r\n		</tr>\r\n		<tr>\r\n			<td
style=\"background:#ff3a59;\">\r\n				<!--\r\n				page title start here\r\n				-->\r\n\r\n				<table width=\"600\"
border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n					<tbody>\r\n						<tr>\r\n							<td
style=\"background:#fff;padding:20px 0 10px; text-align:center;\">\r\n								<h4 style=\"font-weight:normal;
text-transform:uppercase; color:#999;margin:0; padding:10px 0; font-size:18px;\"></h4>\r\n								<h2 style=\"margin:0;
font-size:34px; padding:0;\">TaxApi Order Creation
Failure</h2></td>\r\n						</tr>\r\n					</tbody>\r\n				</table>\r\n				<!--\r\n				page title end
here\r\n				-->\r\n				   </td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				<!--\r\n				page body start
here\r\n				-->\r\n\r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\"
cellspacing=\"0\">\r\n					<tbody>\r\n						<tr>\r\n							<td style=\"background:#fff;padding:0 30px;
text-align:center; color:#999;vertical-align:top;\">\r\n								<table width=\"100%\" border=\"0\" align=\"center\"
cellpadding=\"0\" cellspacing=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td style=\"padding:20px 0
30px;\"><strong style=\"font-size:18px;color:#333;\">Dear Admin </strong><br />\r\n												System has tried to
create an order/transaction on TaxApi after order is marked as completed by admin, but not able to create an
Order/Transaction on TaxApi due to below Error on your site <a href=\"{website_url}\">{website_name}</a> with Yokart
Order Invoice Number {invoice_number}.<br />\r\n												Please find the TaxApi Error information
below.</td>\r\n										</tr>\r\n										<tr>\r\n											<td style=\"padding:0 0
30px;\">{error_message}</td>\r\n										</tr>\r\n										<!--\r\n										section
footer\r\n										-->\r\n\r\n										<tr>\r\n											<td style=\"padding:30px 0;border-top:1px solid #ddd;\">Get
in touch in you have any questions regarding our Services.<br />\r\n												Feel free to contact us 24/7. We are
here to help.<br />\r\n												<br />\r\n												All the best,<br />\r\n												The {website_name} Team<br
/>\r\n												</td>\r\n										</tr>\r\n										<!--\r\n										section
footer\r\n										-->\r\n\r\n									</tbody>\r\n								</table></td>\r\n						</tr>\r\n					</tbody>\r\n				</table>\r\n				<!--\r\n				page
body end here\r\n				-->\r\n				   </td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				<!--\r\n				page footer start
here\r\n				-->\r\n\r\n				<table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\"
cellspacing=\"0\">\r\n					<tbody>\r\n						<tr>\r\n							<td
style=\"height:30px;\"></td>\r\n						</tr>\r\n						<tr>\r\n							<td style=\"background:rgba(0,0,0,0.04);padding:0
30px; text-align:center; color:#999;vertical-align:top;\">\r\n								<table width=\"100%\" border=\"0\"
align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td
style=\"padding:30px 0; font-size:20px; color:#000;\">Need more help?<br />\r\n												 <a href=\"{contact_us_url}\"
style=\"color:#ff3a59;\">We are here, ready to
talk</a></td>\r\n										</tr>\r\n									</tbody>\r\n								</table></td>\r\n						</tr>\r\n						<tr>\r\n							<td
style=\"padding:0; color:#999;vertical-align:top; line-height:20px;\">\r\n								<table width=\"100%\" border=\"0\"
align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td
style=\"padding:20px 0 30px; text-align:center; font-size:13px; color:#999;\">{website_name}
Inc.\r\n												<!--\r\n												if these emails get annoying, please feel free to  <a href=\"#\"
style=\"text-decoration:underline;
color:#666;\">unsubscribe</a>.\r\n												-->\r\n												</td>\r\n										</tr>\r\n									</tbody>\r\n								</table></td>\r\n						</tr>\r\n						<tr>\r\n							<td
style=\"padding:0; height:50px;\"></td>\r\n						</tr>\r\n					</tbody>\r\n				</table>\r\n				<!--\r\n				page footer
end here\r\n				-->\r\n				   </td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n', '{invoice_number} - Yokart Order Invoice
Number.<br/>\r\n{website_name} Name of our website<br>\r\n{website_url} URL of our website<br>\r\n{error_message} - 
Error Message received from TaxApi while creating order \r\n{social_media_icons} <br>\r\n{contact_us_url} <br>', 1);
