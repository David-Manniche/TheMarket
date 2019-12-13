CREATE TABLE `tbl_cart_history` (
  `carthistory_user_id` int(11) NOT NULL,
  `carthistory_selprod_id` int(11) NOT NULL,
  `carthistory_type` tinyint(1) NOT NULL COMMENT 'Defined in model	',
  `carthistory_qty` int(11) NOT NULL,
  `carthistory_action` tinyint(1) NOT NULL COMMENT 'Defined in model',
  `carthistory_email_count` int(11) NOT NULL,
  `carthistory_discount_notification` tinyint(1) NOT NULL,
  `carthistory_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tbl_cart_history`
  ADD PRIMARY KEY (`carthistory_user_id`,`carthistory_selprod_id`);
  
INSERT INTO `tbl_cron_schedules` (`cron_id`, `cron_name`, `cron_command`, `cron_duration`, `cron_active`) VALUES (NULL, 'Abandoned Cart Reminder Email', 'CartHistory/sendReminderAbandonedCart', '600', '1');