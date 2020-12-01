-- ----Not included lang id as as foreign key constraint------------

-- --------tbl_abandoned_cart---------
ALTER TABLE `tbl_abandoned_cart` ADD CONSTRAINT `abandonedcart_user_id` FOREIGN KEY (`abandonedcart_user_id`) REFERENCES `tbl_users`(`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_abandoned_cart` ADD CONSTRAINT `abandonedcart_selprod_id` FOREIGN KEY (`abandonedcart_selprod_id`) REFERENCES `tbl_seller_products`(`selprod_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- --------tbl_addresses---------
ALTER TABLE `tbl_addresses` ADD CONSTRAINT `addr_state_id` FOREIGN KEY (`addr_state_id`) REFERENCES `tbl_states`(`state_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_addresses` ADD CONSTRAINT `addr_country_id` FOREIGN KEY (`addr_country_id`) REFERENCES `tbl_countries`(`country_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ----------tbl_admin_auth_token------------------
ALTER TABLE `tbl_admin_auth_token` ADD CONSTRAINT `admrm_admin_id` FOREIGN KEY (`admauth_admin_id`) REFERENCES `tbl_admin`(`admin_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- -----------tbl_admin_password_reset_requests-------------------------------
ALTER TABLE `tbl_admin_password_reset_requests` ADD CONSTRAINT `aprr_admin_id` FOREIGN KEY (`aprr_admin_id`) REFERENCES `tbl_admin`(`admin_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- -----------tbl_admin_permissions-------------------------------
ALTER TABLE `tbl_admin_permissions` ADD CONSTRAINT `admperm_admin_id` FOREIGN KEY (`admperm_admin_id`) REFERENCES `tbl_admin`(`admin_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ---------------tbl_ads_batches--------------------------
ALTER TABLE `tbl_ads_batches` ADD CONSTRAINT `adsbatch_user_id` FOREIGN KEY (`adsbatch_user_id`) REFERENCES `tbl_users`(`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_ads_batches` ADD CONSTRAINT `adsbatch_target_country_id` FOREIGN KEY (`adsbatch_target_country_id`) REFERENCES `tbl_countries`(`country_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ----------------tbl_ads_batch_products-------------------
ALTER TABLE `tbl_ads_batch_products` ADD CONSTRAINT `abprod_selprod_id` FOREIGN KEY (`abprod_selprod_id`) REFERENCES `tbl_seller_products`(`selprod_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ----------------tbl_affiliate_commission_settings-------------------
ALTER TABLE `tbl_affiliate_commission_settings` ADD CONSTRAINT `afcommsetting_prodcat_id` FOREIGN KEY (`afcommsetting_prodcat_id`) REFERENCES `tbl_product_categories`(`prodcat_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_affiliate_commission_settings` ADD CONSTRAINT `afcommsetting_user_id` FOREIGN KEY (`afcommsetting_user_id`) REFERENCES `tbl_users`(`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ----------------tbl_affiliate_commission_setting_history-------------------
ALTER TABLE `tbl_affiliate_commission_setting_history` ADD CONSTRAINT `acsh_afcommsetting_id` FOREIGN KEY (`acsh_afcommsetting_id`) REFERENCES `tbl_affiliate_commission_settings`(`afcommsetting_id`) ON DELETE RESTRICT ON UPDATE RESTRICT; 
ALTER TABLE `tbl_affiliate_commission_setting_history` ADD CONSTRAINT `acsh_afcommsetting_prodcat_id` FOREIGN KEY (`acsh_afcommsetting_prodcat_id`) REFERENCES `tbl_product_categories`(`prodcat_id`) ON DELETE RESTRICT ON UPDATE RESTRICT; 
ALTER TABLE `tbl_affiliate_commission_setting_history` ADD CONSTRAINT `acsh_afcommsetting_user_id` FOREIGN KEY (`acsh_afcommsetting_user_id`) REFERENCES `tbl_users`(`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- --------------tbl_attribute_group_attributes-------------
ALTER TABLE `tbl_attribute_group_attributes` ADD CONSTRAINT `attr_attrgrp_id` FOREIGN KEY (`attr_attrgrp_id`) REFERENCES `tbl_attribute_groups`(`attrgrp_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ------------------tbl_banners------------------------
ALTER TABLE `tbl_banners` ADD CONSTRAINT `banner_blocation_id` FOREIGN KEY (`banner_blocation_id`) REFERENCES `tbl_banner_locations`(`blocation_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ------------------tbl_banners_clicks------------------------
ALTER TABLE `tbl_banners_clicks` ADD CONSTRAINT `bclick_banner_id` FOREIGN KEY (`bclick_banner_id`) REFERENCES `tbl_banners`(`banner_id`) ON DELETE RESTRICT ON UPDATE RESTRICT; 
ALTER TABLE `tbl_banners_clicks` ADD CONSTRAINT `bclick_user_id` FOREIGN KEY (`bclick_user_id`) REFERENCES `tbl_users`(`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ----------------tbl_banners_logs---------------------
ALTER TABLE `tbl_banners_logs` ADD CONSTRAINT `lbanner_banner_id` FOREIGN KEY (`lbanner_banner_id`) REFERENCES `tbl_banners`(`banner_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- -----------------tbl_banner_locations----------------
ALTER TABLE `tbl_banner_locations` ADD CONSTRAINT `blocation_collection_id` FOREIGN KEY (`blocation_collection_id`) REFERENCES `tbl_collections`(`collection_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;