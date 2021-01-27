ALTER TABLE `tbl_shop_specifics` ADD `shop_pickup_interval` TINYINT(1) NOT NULL COMMENT 'In Hours' AFTER `shop_invoice_codes`;
INSERT INTO `tbl_configurations` (`conf_name`, `conf_val`, `conf_common`) VALUES
("CONF_LOADER", 1, 1) ON DUPLICATE KEY UPDATE conf_common = 1;