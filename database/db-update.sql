ALTER TABLE `tbl_currency` DROP `currency_is_default`;

DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'LBL_Google_Plus_Login';
DELETE FROM `tbl_language_labels` WHERE `label_key` LIKE 'MSG_LoggedIn_SUCCESSFULLY';

ALTER TABLE `tbl_users` DROP `user_facebook_id`, DROP `user_googleplus_id`, DROP `user_apple_id`;

CREATE TABLE `tbl_user_meta` ( `usermeta_user_id` INT NOT NULL , `usermeta_key` VARCHAR(255) NOT NULL , `usermeta_value` VARCHAR(255) NOT NULL ) ENGINE = InnoDB;
ALTER TABLE tbl_user_meta ADD PRIMARY KEY (usermeta_user_id, usermeta_key);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_plugins`
--

CREATE TABLE `tbl_plugins` (
  `plugin_id` int(11) NOT NULL,
  `plugin_identifier` varchar(50) NOT NULL,
  `plugin_type` int(11) NOT NULL,
  `plugin_code` varchar(100) NOT NULL,
  `plugin_active` tinyint(1) NOT NULL,
  `plugin_display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `tbl_plugins`
--
ALTER TABLE `tbl_plugins`
  ADD PRIMARY KEY (`plugin_id`),
  ADD UNIQUE KEY `plugin_identifier` (`plugin_identifier`),
  ADD UNIQUE KEY `plugin_code` (`plugin_code`);

--
-- AUTO_INCREMENT for table `tbl_plugins`
--
ALTER TABLE `tbl_plugins`
  MODIFY `plugin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Dumping data for table `tbl_plugins`
--

INSERT INTO `tbl_plugins` (`plugin_id`, `plugin_identifier`, `plugin_type`, `plugin_code`, `plugin_active`, `plugin_display_order`) VALUES
(1, 'Fixer Currency Converter API', 1, 'FixerCurrencyConverter', 1, 1),
(2, 'Currency Converter API', 1, 'CurrencyConverter', 1, 2),
(3, 'Apple Sign In', 2, 'AppleLogin', 1, 3),
(4, 'Facebook Login', 2, 'FacebookLogin', 1, 4),
(5, 'Google Login', 2, 'GoogleLogin', 1, 5),
(6, 'Instagram Login', 2, 'InstagramLogin', 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_plugins_lang`
--

CREATE TABLE `tbl_plugins_lang` (
  `pluginlang_plugin_id` int(11) NOT NULL,
  `pluginlang_lang_id` int(11) NOT NULL,
  `plugin_name` varchar(200) NOT NULL,
  `plugin_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `tbl_plugins_lang`
--
ALTER TABLE `tbl_plugins_lang`
  ADD PRIMARY KEY (`pluginlang_plugin_id`,`pluginlang_lang_id`);

--
-- Dumping data for table `tbl_plugins_lang`
--

INSERT INTO `tbl_plugins_lang` (`pluginlang_plugin_id`, `pluginlang_lang_id`, `plugin_name`, `plugin_description`) VALUES
(1, 1, 'Fixer Currency Converted API', 'Reference : https://data.fixer.io'),
(1, 2, 'مثبت العملة API المحولة', 'Reference : https://data.fixer.io'),
(2, 1, 'Currency Converter API', 'Reference : https://www.currencyconverterapi.com'),
(2, 2, 'محول العملات API', 'المرجع: https://www.currencyconverterapi.com'),
(3, 1, 'Apple Sign In', ''),
(3, 2, 'أبل تسجيل الدخول', ''),
(4, 1, 'Facebook Login', ''),
(4, 2, 'تسجيل الدخول الى الفيسبوك', ''),
(5, 1, 'Google Login', ''),
(5, 2, 'تسجيل الدخول جوجل', ''),
(6, 1, 'Instagram Login', ''),
(6, 2, 'تسجيل الدخول إلى Instagram', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_plugin_settings`
--

CREATE TABLE `tbl_plugin_settings` (
  `pluginsetting_plugin_id` int(11) NOT NULL,
  `pluginsetting_key` varchar(100) NOT NULL,
  `pluginsetting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `tbl_plugin_settings`
--
ALTER TABLE `tbl_plugin_settings`
  ADD PRIMARY KEY (`pluginsetting_plugin_id`,`pluginsetting_key`);