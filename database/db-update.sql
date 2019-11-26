CREATE TABLE `tbl_shop_specifics` ( `ss_shop_id` INT NOT NULL ,  `shop_return_age` INT NOT NULL COMMENT 'In Days' ,  `shop_cancellation_age` INT NOT NULL COMMENT 'In Days' ) ENGINE = InnoDB;
ALTER TABLE `tbl_shop_specifics` ADD PRIMARY KEY (`ss_shop_id`);

CREATE TABLE `tbl_seller_product_specifics` ( `sps_selprod_id` INT NOT NULL ,  `selprod_return_age` INT NOT NULL COMMENT 'In Days' ,  `selprod_cancellation_age` INT NOT NULL COMMENT 'In Days' ) ENGINE = InnoDB;
ALTER TABLE `tbl_seller_product_specifics` ADD PRIMARY KEY (`sps_selprod_id`);

CREATE TABLE `tbl_product_specifics` ( `ps_product_id` INT NOT NULL ,  `product_warranty` INT NOT NULL COMMENT 'In Days' ) ENGINE = InnoDB;
ALTER TABLE `tbl_product_specifics` ADD PRIMARY KEY (`ps_product_id`);

CREATE TABLE `tbl_order_product_specifics` ( `ops_op_id` INT NOT NULL ,  `op_selprod_return_age` INT NOT NULL COMMENT 'In Days' ,  `op_selprod_cancellation_age` INT NOT NULL COMMENT 'In Days',  `op_product_warranty` INT NOT NULL COMMENT 'In Days' ) ENGINE = InnoDB;
ALTER TABLE `tbl_order_product_specifics` ADD PRIMARY KEY (`ops_op_id`);

INSERT INTO `tbl_cron_schedules` (`cron_id`, `cron_name`, `cron_command`, `cron_duration`, `cron_active`) VALUES (NULL, 'Automating the order completion process', 'Orders/changeOrderStatus', '1440', '1');