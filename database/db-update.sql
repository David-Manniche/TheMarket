CREATE TABLE `tbl_custom_notifications`(
    `cnotification_id` INT NOT NULL AUTO_INCREMENT,
    `cnotification_type` TINYINT(1) NOT NULL,
    `cnotification_title` VARCHAR(200) NOT NULL,
    `cnotification_description` VARCHAR(255) NOT NULL,
    `cnotification_notified_on` DATETIME NOT NULL,
    `cnotification_for_buyer` TINYINT(1) NOT NULL,
    `cnotification_for_seller` TINYINT(1) NOT NULL,
    `cnotification_active` TINYINT(1) NOT NULL,
    `cnotification_added_on` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`cnotification_id`)
) ENGINE = InnoDB;

CREATE TABLE `tbl_custom_notification_to_users` ( `cntu_cnotification_id` INT NOT NULL ,  `cntu_user_id` INT NOT NULL ) ENGINE = InnoDB;
ALTER TABLE  `tbl_custom_notification_to_users` ADD PRIMARY KEY (`cntu_cnotification_id`, `cntu_user_id`);
DELETE FROM `tbl_language_labels` WHERE `label_key` = 'LBL_Title';
DELETE FROM `tbl_language_labels` WHERE `label_key` = 'LBL_S.No';
DELETE FROM `tbl_language_labels` WHERE `label_key` = 'LBL_Does_not_Matter';