<?php
class PatchUpdateController extends AdminBaseController
{
    public function __construct($action)
    {
        parent::__construct($action);
        $this->objPrivilege->canEditPatch($this->admin_id);
        ini_set('memory_limit', '100M');
        set_time_limit(0);
    }

    public function updateTaxJarCat()
    {
        if (false === PluginHelper::includePlugin('TaxJarTax', 'tax', $error, $this->adminLangId)) {
            FatUtility::dieWithError($error);
        }
        
        $taxJarObj = new TaxJarTax($this->adminLangId); 
        $codesArr = $taxJarObj->getCodes(null, null, null, array(), false);

        $pluginId = Plugin::getAttributesByCode(TaxJarTax::KEY_NAME, 'plugin_id');
        $db = FatApp::getDb();
        $parentArr = [];
        foreach ($codesArr as $code) {
            $parentId = 0;
           
           $arr = [
               'taxcat_identifier' => ($code->name != '') ? $code->name : $code->product_tax_code,
               'taxcat_code' => $code->product_tax_code,
               'taxcat_parent' => $parentId,   
               'taxcat_plugin_id' => $pluginId,
               'taxcat_active' => applicationConstants::ACTIVE ,
               'taxcat_deleted' => applicationConstants::NO ,
               'taxcat_last_updated' => date('Y-m-d H:i:s')
           ];
           
           $db->insertFromArray(Tax::DB_TBL, $arr, false, array(), $arr);
           $taxCatId = $db->getInsertId();

           $data = array(
            'taxcatlang_taxcat_id' => $taxCatId,
            'taxcatlang_lang_id' => $this->adminLangId,
            'taxcat_name' => ($code->description != '') ? $code->description : $code->taxCode,
            );
    
            $taxObj = new Tax($taxCatId);
            $taxObj->updateLangData($this->adminLangId, $data);

        }
        echo 'Done';
    }

    public function updateAvalarataxCat()
    {
		$error = '';
		if (false === PluginHelper::includePlugin('AvalaraTax', 'tax', $error, $this->adminLangId)) {
            FatUtility::dieWithError($error);
		}
        $avalaraObj = new AvalaraTax($this->adminLangId); 
        $codesArr = $avalaraObj->getCodes(null, null, null, array('id ASC'), false);
        
        $pluginId = Plugin::getAttributesByCode(AvalaraTax::KEY_NAME, 'plugin_id');
        $db = FatApp::getDb();
        $parentArr = [];
        foreach ($codesArr->value as $code) {
            $parentId = 0;
            if (isset($code->parentTaxCode) && $code->parentTaxCode != '') {
                if (array_key_exists($code->parentTaxCode, $parentArr)) {
                    $parentId = $parentArr[$code->parentTaxCode];
                } else {
                    $parentId = Tax::getAttributesByCode($code->parentTaxCode, 'taxcat_id', $pluginId);
                    $parentArr[$code->parentTaxCode] = $parentId;
                }
            }    
            
           $arr = [
               'taxcat_identifier' => ($code->description != '') ? $code->description : $code->taxCode,
               'taxcat_code' => $code->taxCode,
               'taxcat_parent' => $parentId,   
               'taxcat_plugin_id' => $pluginId,
               'taxcat_active' => $code->isActive ? applicationConstants::ACTIVE : applicationConstants::INACTIVE ,
               'taxcat_deleted' => applicationConstants::NO ,
               'taxcat_last_updated' => date('Y-m-d H:i:s')
           ];
           
           $db->insertFromArray(Tax::DB_TBL, $arr, false, array(), $arr);
           $taxCatId = $db->getInsertId();

           $data = array(
            'taxcatlang_taxcat_id' => $taxCatId,
            'taxcatlang_lang_id' => $this->adminLangId,
            'taxcat_name' => ($code->description != '') ? $code->description : $code->taxCode,
            );
    
            $taxObj = new Tax($taxCatId);
            $taxObj->updateLangData($this->adminLangId, $data);

        }
        echo 'Done';
    }

    public function resetFullTextSearchData()
    {
        $date = date('Y-m-d H:i:s');
        $srch = new ProductSearch();
        $srch->addMultipleFields(array('product_id', '0 as subrecord_id' , UpdatedRecordLog::TYPE_PRODUCT . ' as record_type', "'" . $date . "' as updated_time"));
        $srch->doNotLimitRecords();
        $srch->doNotCalculateRecords();
        //$srch->addGroupBy('product_id');
        $tmpQry = $srch->getQuery();

        $qry = "INSERT INTO " . UpdatedRecordLog::DB_TBL . " (" . UpdatedRecordLog::DB_TBL_PREFIX . "record_id, " . UpdatedRecordLog::DB_TBL_PREFIX . "subrecord_id, ". UpdatedRecordLog::DB_TBL_PREFIX . "record_type, ". UpdatedRecordLog::DB_TBL_PREFIX . "added_on ) SELECT * FROM (" . $tmpQry . ") AS t ON DUPLICATE KEY UPDATE ". UpdatedRecordLog::DB_TBL_PREFIX . "record_id = t.product_id, " . UpdatedRecordLog::DB_TBL_PREFIX ."subrecord_id = t.subrecord_id, " . UpdatedRecordLog::DB_TBL_PREFIX . "record_type = t.record_type, " . UpdatedRecordLog::DB_TBL_PREFIX . "added_on = t.updated_time";

        FatApp::getDb()->query($qry);
        echo "Done";
    }

    public function fullTextSearch()
    {
        $languages = Language::getAllNames();

        if (empty($languages)) {
            echo "Language not found";
            die;
        }

        foreach ($languages as $langId => $language) {
            /* [ Reset Product data for full text search*/
            $srch = new ProductSearch();
            $srch->addMultipleFields(array('product_id', '0', $langId));
            $srch->doNotLimitRecords();
            $srch->doNotCalculateRecords();
            //$srch->addGroupBy('product_id');
            $tmpQry = $srch->getQuery();

            $qry = "INSERT INTO " . Product::DB_PRODUCT_EXTERNAL_RELATIONS . " (" . Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX . "product_id, " . Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX . "indexed_for_search, ". Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX . "lang_id ) SELECT * FROM (" . $tmpQry . ") AS t ON DUPLICATE KEY UPDATE ". Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX . "product_id = t.product_id, " . Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX ."indexed_for_search = 0, " . Product::DB_PRODUCT_EXTERNAL_RELATIONS_PREFIX . "lang_id = t." . $langId;

            FatApp::getDb()->query($qry);
            /* ]*/

            /* [ Reset Seller Product data for full text search*/
            /*$srch = new ProductSearch();
            $srch->joinTable(SellerProduct::DB_TBL, 'INNER JOIN', 'p.product_id = sprods.selprod_product_id and selprod_active = ' . applicationConstants::ACTIVE .' and selprod_deleted = ' . applicationConstants::NO, 'sprods');
            $srch->addMultipleFields(array('selprod_id',$langId,'selprod_product_id','0',));
            $srch->addCondition('selprod_deleted', '=', applicationConstants::NO);
            $srch->doNotLimitRecords();
            $srch->doNotCalculateRecords();
            $tmpQry = $srch->getQuery();

            $qry = "INSERT INTO " . SellerProduct::DB_TBL_EXTERNAL_RELATIONS . " (" . SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX . "selprod_id, " . SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX . "lang_id," . SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX . "product_id," . SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX . "indexed_for_search ) SELECT * FROM (" . $tmpQry . ") AS t ON DUPLICATE KEY UPDATE ". SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX . "selprod_id = t.selprod_id, " . SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX . " lang_id = t. " . $langId . "," . SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX . "product_id = t.selprod_product_id," . SellerProduct::DB_TBL_EXTERNAL_RELATIONS_PREFIX . "indexed_for_search = 0";

            FatApp::getDb()->query($qry);*/
            /* ]*/
        }
        echo "Done";
    }

    public function updateCategoryTable()
    {
        $srch = ProductCategory::getSearchObject();
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addCondition('prodcat_parent', '=', 0);
        $rs = $srch->getResultSet();
        $result = FatApp::getDb()->fetchAll($rs);
        foreach ($result as $row) {
            $productCategory = new ProductCategory($row['prodcat_id']);
            $productCategory->updateCatCode();
        }
        echo "Done";
    }

    public function updateCatOrderCode()
    {
        ProductCategory::updateCatOrderCode();
    }

    public function updateOrderProdSetting()
    {
        $srch = new SearchBase(OrderProduct::DB_TBL);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addMultipleFields(array('op_id','op_tax_collected_by_seller'));
        $rs = $srch->getResultSet();
        $urlRows = FatApp::getDb()->fetchAll($rs);
        $db = FatApp::getDb();
        foreach ($urlRows as $row) {
            $data = array(
            'opsetting_op_id'=>$row['op_id'],
            'op_tax_collected_by_seller'=>$row['op_tax_collected_by_seller'],
            'op_commission_include_tax'=>FatApp::getConfig('CONF_COMMISSION_INCLUDING_SHIPPING', FatUtility::VAR_INT, 0),
            'op_commission_include_shipping'=>FatApp::getConfig('CONF_COMMISSION_INCLUDING_TAX', FatUtility::VAR_INT, 0),
            );

            if (!$db->insertFromArray(OrderProduct::DB_TBL_SETTINGS, $data, false, array(), $data)) {
                echo "Error with ".$row['op_id'].':'.$db->getError() .'<br>';
            }
        }
        echo "Done";
    }

    public function changeCustomUrl()
    {
        $urlSrch = UrlRewrite::getSearchObject();
        $urlSrch->doNotCalculateRecords();
        $urlSrch->doNotLimitRecords();
        $urlSrch->addMultipleFields(array('urlrewrite_id','urlrewrite_original','urlrewrite_custom'));
        $rs = $urlSrch->getResultSet();
        $urlRows = FatApp::getDb()->fetchAll($rs);
        $db = FatApp::getDb();
        foreach ($urlRows as $row) {
            $url = str_replace("/", "-", $row['urlrewrite_custom']);
            if ($db->updateFromArray(UrlRewrite::DB_TBL, array('urlrewrite_custom' => $url), array('smt' => 'urlrewrite_id = ?', 'vals' => array($row['urlrewrite_id'])))) {
                echo $row['urlrewrite_id']."<br>";
            }
        }
    }

    public function updateCharset()
    {
        $database = CONF_DB_NAME;
        FatApp::getDb()->query("ALTER DATABASE ".$database." CHARACTER SET utf8 COLLATE utf8mb4_unicode_ci");
        $qry = FatApp::getDb()->query("show tables");
        $res = FatApp::getDb()->fetchAll($qry);
        foreach ($res as $val) {
            FatApp::getDb()->query("ALTER TABLE ".$val['Tables_in_'.$database]." CONVERT TO CHARACTER SET utf8 COLLATE utf8mb4_unicode_ci");
            echo 'Done:- '.$val['Tables_in_'.$database].'<br>';
        }
        // ALTER TABLE tbl_affiliate_commission_settings MODIFY COLUMN afcommsetting_fees decimal(12,4)
    }

    public function truncateTables($type = 'orders')
    {
        if ($type == 'orders') {
            $tables = array('tbl_orders','tbl_orders_lang','tbl_orders_status_history','tbl_order_cancel_requests','tbl_order_extras','tbl_order_payments','tbl_order_products','tbl_order_products_lang','tbl_order_product_charges','tbl_order_product_charges_lang','tbl_order_product_digital_download_links','tbl_order_product_shipping','tbl_order_product_shipping_lang','tbl_order_product_to_shipping_users','tbl_order_return_requests','tbl_order_return_request_messages','tbl_order_seller_subscriptions','tbl_order_seller_subscriptions_lang','tbl_order_user_address','tbl_user_reward_points','tbl_user_reward_point_breakup','tbl_rewards_on_purchase','tbl_user_transactions','tbl_coupons_history','tbl_coupons_hold','tbl_user_cart','tbl_order_product_settings');
            FatApp::getDb()->query('UPDATE `tbl_seller_products` SET `selprod_sold_count` = 0 WHERE 1');
        } elseif ($type == 'all') {
            $tables = array('tbl_abusive_words','tbl_admin_auth_token','tbl_admin_password_reset_requests','tbl_admin_permissions','tbl_affiliate_commission_setting_history','tbl_affiliate_commission_settings','tbl_attached_files_temp','tbl_attribute_group_attributes','tbl_attribute_group_attributes_lang','tbl_attribute_groups','tbl_banner_locations_lang','tbl_banners','tbl_banners_clicks','tbl_banners_lang','tbl_banners_logs','tbl_blog_contributions','tbl_blog_post','tbl_blog_post_categories','tbl_blog_post_categories_lang','tbl_blog_post_comments','tbl_blog_post_lang','tbl_blog_post_to_category','tbl_brands','tbl_brands_lang','tbl_catalog_request_messages','tbl_collection_to_product_categories','tbl_collection_to_seller_products','tbl_collection_to_shops','tbl_collections','tbl_collections_lang','tbl_commission_setting_history','tbl_content_block_to_category','tbl_coupon_to_category','tbl_coupon_to_plan','tbl_coupon_to_products','tbl_coupon_to_seller','tbl_coupon_to_users','tbl_coupons','tbl_coupons_history','tbl_coupons_hold','tbl_coupons_lang','tbl_cron_log','tbl_email_archives','tbl_extra_attribute_groups','tbl_extra_attribute_groups_lang','tbl_extra_attributes','tbl_extra_attributes_lang','tbl_failed_login_attempts','tbl_faq_categories','tbl_faq_categories_lang','tbl_faqs','tbl_faqs_lang','tbl_filter_groups','tbl_filter_groups_lang','tbl_filters','tbl_filters_lang','tbl_import_export_settings','tbl_manual_shipping_api','tbl_manual_shipping_api_lang','tbl_meta_tags_lang','tbl_notifications','tbl_option_values','tbl_option_values_lang','tbl_options','tbl_options_lang','tbl_order_cancel_reasons_lang','tbl_order_cancel_requests','tbl_order_extras','tbl_order_payments','tbl_order_product_charges','tbl_order_product_charges_lang','tbl_order_product_shipping','tbl_order_product_shipping_lang','tbl_order_product_to_shipping_users','tbl_order_products','tbl_order_products_lang','tbl_order_return_request_messages','tbl_order_return_requests','tbl_order_seller_subscriptions','tbl_order_seller_subscriptions_lang','tbl_order_seller_subscriptions_lang_old','tbl_order_user_address','tbl_orders','tbl_orders_lang','tbl_orders_status_history','tbl_orders_status_lang','tbl_policy_points','tbl_policy_points_lang','tbl_polling','tbl_polling_feedback','tbl_polling_lang','tbl_polling_to_category','tbl_polling_to_products','tbl_product_categories','tbl_product_categories_lang','tbl_product_groups','tbl_product_groups_lang','tbl_product_numeric_attributes','tbl_product_product_recommendation','tbl_product_shipping_rates','tbl_product_special_prices','tbl_product_specifications','tbl_product_specifications_lang','tbl_product_stock_hold','tbl_product_text_attributes','tbl_product_to_category','tbl_product_to_groups','tbl_product_to_options','tbl_product_to_tags','tbl_product_to_tax','tbl_product_volume_discount','tbl_products','tbl_products_browsing_history','tbl_products_lang','tbl_products_shipped_by_seller','tbl_products_shipping','tbl_products_temp_ids','tbl_promotion_item_charges','tbl_promotions','tbl_promotions_charges','tbl_promotions_clicks','tbl_promotions_lang','tbl_promotions_logs','tbl_promotions_old','tbl_question_banks','tbl_question_banks_lang','tbl_question_to_answers','tbl_questionnaire_feedback','tbl_questionnaires','tbl_questionnaires_lang','tbl_questionnaires_to_question','tbl_questions','tbl_questions_lang','tbl_recommendation_activity_browsing','tbl_related_products','tbl_rewards_on_purchase','tbl_search_items','tbl_seller_brand_requests','tbl_seller_brand_requests_lang','tbl_seller_catalog_requests','tbl_seller_packages','tbl_seller_packages_lang','tbl_seller_packages_plan','tbl_seller_product_options','tbl_seller_product_policies','tbl_seller_product_rating','tbl_seller_product_reviews','tbl_seller_product_reviews_abuse','tbl_seller_product_reviews_helpful','tbl_seller_products','tbl_seller_products_lang','tbl_seller_products_temp_ids','tbl_shipping_company','tbl_shipping_company_lang','tbl_shipping_durations','tbl_shipping_durations_lang','tbl_shippingapi_settings','tbl_shop_collection_products','tbl_shop_collections','tbl_shop_collections_lang','tbl_shop_reports','tbl_shops','tbl_shops_lang','tbl_shops_to_theme','tbl_smart_log_actions','tbl_smart_products_weightage','tbl_smart_remommended_products','tbl_smart_user_activity_browsing','tbl_smart_weightage_settings','tbl_social_platforms','tbl_social_platforms_lang','tbl_success_stories','tbl_success_stories_lang','tbl_tag_product_recommendation','tbl_tags','tbl_tags_lang','tbl_tax_categories','tbl_tax_categories_lang','tbl_tax_values','tbl_testimonials','tbl_testimonials_lang','tbl_theme','tbl_theme_lang','tbl_thread_messages','tbl_threads','tbl_tool_tips','tbl_tool_tips_lang','tbl_upsell_products','tbl_url_rewrite','tbl_user_address','tbl_user_auth_token','tbl_user_bank_details','tbl_user_cart','tbl_user_credentials','tbl_user_email_verification','tbl_user_extras','tbl_user_favourite_products','tbl_user_favourite_shops','tbl_user_password_reset_requests','tbl_user_product_recommendation','tbl_user_return_address','tbl_user_return_address_lang','tbl_user_reward_point_breakup','tbl_user_reward_points','tbl_user_supplier_request_values','tbl_user_supplier_request_values_lang','tbl_user_supplier_requests','tbl_user_transactions','tbl_user_wish_list_products','tbl_user_wish_lists','tbl_user_withdrawal_requests','tbl_users','tbl_order_product_settings','tbl_user_requests_history');
            /* DELETE FROM `tbl_attached_files` WHERE `afile_type` in (1,2,3,4,5,7,9,10,11,12,13,14,22,23,24,25,26,27,28,29,30,32,33,41,42,43,48)
            */
            /*
            Delete FROM `tbl_navigation_links` where nlink_nav_id != 1
            */
        }

        foreach ($tables as $table) {
            $result = FatApp::getDb()->query("TRUNCATE TABLE `".$table."`");

            if ($result) {
                echo 'Done: '.$table.' <br>';
            } else {
                echo 'Error in: '.$table.' <br>';
            }
        }
    }

    public function changeCustomUrl1()
    {
        $urlSrch = UrlRewrite::getSearchObject();
        $urlSrch->doNotCalculateRecords();
        $urlSrch->addMultipleFields(array('urlrewrite_id','urlrewrite_original','urlrewrite_custom'));
        $rs = $urlSrch->getResultSet();
        $urlRows = FatApp::getDb()->fetchAll($rs);
        $db = FatApp::getDb();
        foreach ($urlRows as $row) {
            $url = str_replace("/", "-", $row['urlrewrite_custom']);
            if ($db->updateFromArray(UrlRewrite::DB_TBL, array('urlrewrite_custom' => $url), array('smt' => 'urlrewrite_id = ?', 'vals' => array($row['urlrewrite_id'])))) {
                echo $row['urlrewrite_id'] . "<br>";
            }
        }
    }
}
