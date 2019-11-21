CREATE TABLE `yokart`.`tbl_shop_specifics` ( `ss_shop_id` INT NOT NULL ,  `shop_return_age` INT NOT NULL COMMENT 'In Days' ,  `shop_cancellation_age` INT NOT NULL COMMENT 'In Days' ) ENGINE = InnoDB;
ALTER TABLE `tbl_shop_specifics` ADD PRIMARY KEY (`ss_shop_id`);

CREATE TABLE `yokart`.`tbl_seller_product_specifics` ( `sps_selprod_id` INT NOT NULL ,  `selprod_return_age` INT NOT NULL COMMENT 'In Days' ,  `selprod_cancellation_age` INT NOT NULL COMMENT 'In Days' ) ENGINE = InnoDB;
ALTER TABLE `tbl_seller_product_specifics` ADD PRIMARY KEY (`sps_selprod_id`);

CREATE TABLE `yokart`.`tbl_product_specifics` ( `ps_product_id` INT NOT NULL ,  `product_warranty` INT NOT NULL COMMENT 'In Days' ) ENGINE = InnoDB;
ALTER TABLE `tbl_product_specifics` ADD PRIMARY KEY (`ps_product_id`);