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