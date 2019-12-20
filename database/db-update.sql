CREATE TABLE `tbl_push_notifications`(
    `pnotification_id` INT NOT NULL AUTO_INCREMENT,
    `pnotification_type` TINYINT(1) NOT NULL,
    `pnotification_title` VARCHAR(200) NOT NULL,
    `pnotification_description` VARCHAR(255) NOT NULL,
    `pnotification_notified_on` DATETIME NOT NULL,
    `pnotification_for_buyer` TINYINT(1) NOT NULL,
    `pnotification_for_seller` TINYINT(1) NOT NULL,
    `pnotification_active` TINYINT(1) NOT NULL,
    `pnotification_added_on` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`pnotification_id`)
) ENGINE = InnoDB;

CREATE TABLE `tbl_push_notification_to_users` ( `cntu_pnotification_id` INT NOT NULL ,  `cntu_user_id` INT NOT NULL ) ENGINE = InnoDB;
ALTER TABLE  `tbl_push_notification_to_users` ADD PRIMARY KEY (`cntu_pnotification_id`, `cntu_user_id`);
DELETE FROM `tbl_language_labels` WHERE `label_key` = 'LBL_Title';
DELETE FROM `tbl_language_labels` WHERE `label_key` = 'LBL_S.No';
DELETE FROM `tbl_language_labels` WHERE `label_key` = 'LBL_Does_not_Matter';