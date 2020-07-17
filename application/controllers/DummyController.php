<?php

class DummyController extends MyAppController
{
    public function deleteUserUplaods()
    {
        $dirName = CONF_INSTALLATION_PATH . 'user-uploads';
        //CommonHelper::recursiveDelete( $dirName );
    }

    public function addToStore()
    {
        $product = Product::isAvailableForAddToStore(64, 11);
    }

    public function firstTimeDiscount()
    {
        $product = Cronjob::firstTimeBuyerDiscount(29, 'O1581657176');
    }

    public function createProcedures($printQuery = false)
    {
        $db = FatApp::getDb();
        $con = $db->getConnectionObject();
        $queries = array(
        "DROP FUNCTION IF EXISTS `GETBLOGCATCODE`",
        "CREATE FUNCTION `GETBLOGCATCODE`(`id` INT) RETURNS varchar(255) CHARSET utf8
			BEGIN
				DECLARE code VARCHAR(255);
				DECLARE catid INT(11);

				SET catid = id;
				SET code = '';
				WHILE catid > 0 DO
					SET code = CONCAT(RIGHT(CONCAT('000000', catid), 6), '_', code);
					SELECT bpcategory_parent INTO catid FROM tbl_blog_post_categories WHERE bpcategory_id = catid;
				END WHILE;
				RETURN code;
			END",
        "DROP FUNCTION IF EXISTS `GETCATCODE`",
        "CREATE FUNCTION `GETCATCODE`(`id` INT) RETURNS varchar(255) CHARSET utf8
			BEGIN
				DECLARE code VARCHAR(255);
				DECLARE catid INT(11);

				SET catid = id;
				SET code = '';
				WHILE catid > 0 DO
					SET code = CONCAT(RIGHT(CONCAT('000000', catid), 6), '_', code);
					SELECT prodcat_parent INTO catid FROM tbl_product_categories WHERE prodcat_id = catid;
				END WHILE;
				RETURN code;
			END",
        "DROP FUNCTION IF EXISTS `GETCATORDERCODE`",
        "CREATE FUNCTION `GETCATORDERCODE`(`id` INTEGER) RETURNS varchar(255) CHARSET utf8
			BEGIN
				DECLARE code VARCHAR(255);
				DECLARE catid INT(11);
				DECLARE myorder INT(11);
				SET catid = id;
				SET code = '';
				set myorder = 0;
				WHILE catid > 0 DO
					SELECT prodcat_parent, prodcat_display_order  INTO catid, myorder FROM tbl_product_categories WHERE prodcat_id = catid;
					SET code = CONCAT(RIGHT(CONCAT('000000', myorder), 6), code);
				END WHILE;
				RETURN code;
			END",
        "DROP FUNCTION IF EXISTS `GETBLOGCATORDERCODE`",
        "CREATE FUNCTION `GETBLOGCATORDERCODE`(`id` INT) RETURNS varchar(500) CHARSET utf8
			BEGIN
				DECLARE code VARCHAR(255);
				DECLARE catid INT(11);
				DECLARE myorder INT(11);
				SET catid = id;
				SET code = '';
				set myorder = 0;
				WHILE catid > 0 DO
					SELECT bpcategory_parent, bpcategory_display_order  INTO catid, myorder FROM tbl_blog_post_categories WHERE bpcategory_id = catid;
					SET code = CONCAT(RIGHT(CONCAT('000000', myorder), 6), code);
				END WHILE;
				RETURN code;
			END"
        );

        foreach ($queries as $qry) {
            if ($printQuery) {
                echo $qry . '<br><br>';
            } else {
                if (!$con->query($qry)) {
                    die($con->error);
                }
            }
        }
        //echo 'Created All the Procedures.';
    }

	public function cart()
    {
		$this->_template->render();
    }
	
	public function checkout()
    {
		$this->set('exculdeMainHeaderDiv', true);
		$this->_template->render(true, false);
    }
	
    public function updateDecimal()
    {
        $database = CONF_DB_NAME;
        $qry = FatApp::getDb()->query("SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . $database . "' AND DATA_TYPE = 'decimal'");
        while ($row = FatApp::getDb()->fetch($qry)) {
            //FatApp::getDb()->query("ALTER TABLE ".$row['TABLE_NAME']." MODIFY COLUMN ".$row['COLUMN_NAME']." decimal(12,4)");
            echo 'Done:- ' . $row['TABLE_NAME'] . ' - ' . $row['COLUMN_NAME'] . '<br>';
            //var_dump($row);
        }
    }


    public function testSmtp()
    {
        include_once CONF_INSTALLATION_PATH . 'library/PHPMailer/PHPMailerAutoload.php';
        $mail = new PHPMailer(true);
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->IsHTML(true);
        $mail->Host = 'mail.marketsanat.com';
        $mail->Port = 26;
        $mail->Username = 'test@marketsanat.com';
        $mail->Password = 'Test!!22';
        $mail->SMTPSecure = 'tls';
        $mail->SMTPDebug = true;
        $mail->SetFrom('test@marketsanat.com', 'test');
        $mail->addAddress('pooja.rani@ablysoft.com');
        $mail->Subject = 'test Headers test From marketsanat';
        $mail->AltBody = "This is text only alternative body.";
        $mail->MsgHTML('<b>Headers test</b><br><br>Port: 26, Secure: tls');
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            exit;
        }
        echo 'Message has been sent';
    }



    public function pushTest()
    {
        $firebase_push_notification_server_key = "AAAAc5bAbbg:APA91bE67wf1PrijhzCWRmb0vBcAEciA7-x-X_QrDUblDnbT1ij95hr619flMF2c4MFlfTOPU0g9usWaPPex0ho2W5bDxCGeKC0jlpBkmZEhXj0avb3MJ-NsTpwmEp-T7yQBq-e9MEHR";
        //$firebase_push_notification_server_key = "AIzaSyDqigFC0880hWtyGChS6TlZi3Vm_I4Q4Qk";
        //$deviceToken = "c8T6nDKFl68:APA91bEWa0IYJGeWK7m89vxQErP8hR69INX3NgkZ75GfadIa282oWLd4EsGCv9lcYVRM0KvuPu78KZnCRuxtWOyKly-zii85jbi5XYIPCDmURJx11FKj5-80xK-m4b26i3yQigjSe44E";
        $deviceToken = "f36lUmAdj1w:APA91bEMS-oLPX7UDItO1cglzYN0MBDfAfJ3AYIRKRfgWSbnbgDaQV_1EW3OjamTINuIM_2tB6Gt-o-GI6ZZcS-SBG3D45wrIIIKuBTmhhcIb7Dp8UdqmZ8sZ6OcTIcKrlIk6Kqap4Gl";
        $url = 'https://fcm.googleapis.com/fcm/send';
        //$url = 'https://gcm-http.googleapis.com/gcm/send';
        //https://android.googleapis.com/gcm/send'
        $fcmKey = $firebase_push_notification_server_key;


        $headers = array(
        'Authorization: key=' . $fcmKey,
        'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //$data = array('title'=>'Yocabs Notification Title', 'message'=>'Yocabs Notification Message Body');
        $msg = array(
        'message' => 'here is a message. message',
        'title' => 'This is a title. title',
        /* 'subtitle'    => 'This is a subtitle. subtitle',
        'id'    => 12,
        'tickerText'    => 'Ticker text here...Ticker text here...Ticker text here',
        'vibrate'    => 1,
        'sound'        => 1,
        'largeIcon'    => 'large_icon',
        'smallIcon'    => 'small_icon' */
        );

        $post = array(
                    'to' => $deviceToken,
                    'data' => $msg
        );

        /* print_r($post);
        die(); */
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        $result = curl_exec($ch);
        $response = '';

        if (curl_errno($ch)) {
            $response .= 'Error ' . curl_error($ch) . print_r($post, true);
            echo $response;
            return false;
        }

        $objResult = json_decode($result, true);


        curl_close($ch);
        echo $result;
    }

    public function pushNotificaton()
    {
        // API access key from Google API's Console
        $API_ACCESS_KEY = 'AAAAZA6vRK8:APA91bHlfYreFEpCK18CSBahNCe7e4pU-3c3925duLwhxXvxAGbWF5m4K7U4oMKWht_BBCAZ6VC6v8dGIBnR14_X-lNxJQwiORNUgeM3Djm9ZvUQJRk_n3hjkuAG2D8-iVAqtN2IC1GU';
        $registrationIds = 'c8T6nDKFl68:APA91bEWa0IYJGeWK7m89vxQErP8hR69INX3NgkZ75GfadIa282oWLd4EsGCv9lcYVRM0KvuPu78KZnCRuxtWOyKly-zii85jbi5XYIPCDmURJx11FKj5-80xK-m4b26i3yQigjSe44E';
        // prep the bundle
        $msg = array(
        'message' => 'here is a message. message',
        'title' => 'This is a title. title',
        'subtitle' => 'This is a subtitle. subtitle',
        'tickerText' => 'Ticker text here...Ticker text here...Ticker text here',
        'vibrate' => 1,
        'sound' => 1,
        'largeIcon' => 'large_icon',
        'smallIcon' => 'small_icon'
        );
        $fields = array(
        'registration_ids' => $registrationIds,
        'data' => $msg
        );

        $headers = array(
        'Authorization: key=' . $API_ACCESS_KEY,
        'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        echo $result;
    }

    public function downloadImages()
    {
        $res = Cronjob::autoDownloadProductImage();
        if ($res) {
            echo "No Record Found";
        } else {
            echo "Done";
        }
        exit;
    }

    public function format($number)
    {
        $prefixes = 'KMGTPEZY';
        if ($number >= 1000) {
            for ($i = -1; $number >= 1000; ++$i) {
                $number = $number / 1000;
            }
            return floor($number) . $prefixes[$i];
        }
        return $number;
    }

    public function index()
    {
       /*  $address = new Address();
        $lat = '37.4238253802915';
        $lng = '-122.0829009197085';
        
        $response = $address->getGeoData($lat, $lng); */
        $langId = 1;
        $countryId = 99;
        $stateId = 0;

        $srch = ShippingProfileProduct::getUserSearchObject();
        $srch->joinTable(ShippingProfile::DB_TBL, 'INNER JOIN', 'sppro.shippro_shipprofile_id = spprof.shipprofile_id and spprof.shipprofile_active = ' . applicationConstants::YES, 'spprof');
        $srch->joinTable(ShippingProfileZone::DB_TBL, 'INNER JOIN', 'shippz.shipprozone_shipprofile_id = spprof.shipprofile_id', 'shippz');
        $srch->joinTable(ShippingZone::DB_TBL, 'INNER JOIN', 'shipz.shipzone_id = shippz.shipprozone_shipzone_id and shipz.shipzone_active = ' . applicationConstants::YES, 'shipz');

        $tempSrch = ShippingZone::getZoneLocationSearchObject($langId);
        $tempSrch->addDirectCondition("(shiploc_country_id = '-1' or (shiploc_country_id = '" . $countryId. "' and (shiploc_state_id = '-1' or shiploc_state_id = '" . $stateId . "')) )");
        $tempSrch->doNotCalculateRecords();
        $tempSrch->doNotLimitRecords();
        
        $srch->joinTable('(' . $tempSrch->getQuery() . ')', 'INNER JOIN', 'shiploc.shiploc_shipzone_id = shippz.shipprozone_shipzone_id', 'shiploc');
        echo $srch->getQuery();


    }


    public function test()
    {
    }

    private function getShopInfo($shop_id)
    {
        $db = FatApp::getDb();
        $shop_id = FatUtility::int($shop_id);
        $srch = new ShopSearch($this->siteLangId);
        $srch->setDefinedCriteria($this->siteLangId);
        $srch->doNotCalculateRecords();

        $srch->addMultipleFields(
            array( 'shop_id', 'shop_user_id', 'shop_ltemplate_id', 'shop_created_on', 'shop_name', 'shop_description',
            'shop_country_l.country_name as shop_country_name', 'shop_state_l.state_name as shop_state_name', 'shop_city' )
        );
        $srch->addCondition('shop_id', '=', $shop_id);
        $shopRs = $srch->getResultSet();
        return $shop = $db->fetch($shopRs);
    }

    public function whoFavoritesShop($shop_id)
    {
        $db = FatApp::getDb();
        $shop_id = FatUtility::int($shop_id);

        $shopData = $this->getShopInfo($shop_id);
        if (!$shopData) {
            Message::addErrorMessage(Labels::getLabel('LBL_Invalid_Request', $this->siteLangId));
            FatApp::redirectUser(CommonHelper::generateUrl('Home'));
        }

        $srch = new UserFavoriteShopSearch($this->siteLangId);
        $srch->joinWhosFavouriteUser();

        /* $srch->setDefinedCriteria();
        $srch->joinWhosFavoritesUser(); */

        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addCondition('ufs_shop_id', '=', $shop_id);

        //$srch->addMultipleFields(array( 'ufs_user_id','s.shop_id', 'IFNULL(s.shop_name, s.shop_identifier) as shop_name', 'u.user_name as shop_owner_name','uf.user_name as favorite_user_name', ));
        $rs = $srch->getResultSet();
        $shops = $db->fetchAll($rs);

        $totalProductsToShow = 4;
        if ($shops) {
            $prodSrchObj = new ProductSearch($this->siteLangId);
            $prodSrchObj->setDefinedCriteria(0);
            $prodSrchObj->setPageNumber(1);
            $prodSrchObj->setPageSize($totalProductsToShow);
            foreach ($shops as &$shop) {
                $prodSrch = clone $prodSrchObj;
                $prodSrch->addShopIdCondition($shop['shop_id']);
                $prodSrch->addMultipleFields(
                    array( 'selprod_id', 'product_id', 'IFNULL(shop_name, shop_identifier) as shop_name',
                    'IFNULL(product_name, product_identifier) as product_name',
                    'IF(selprod_stock > 0, 1, 0) AS in_stock')
                );
                $prodRs = $prodSrch->getResultSet();
                $shop['totalProducts'] = $prodSrch->recordCount();
                $shop['products'] = $db->fetchAll($prodRs);
            }
        }

        $this->set('totalProductsToShow', $totalProductsToShow);
        $this->set('shops', $shops);
        $this->set('shopData', $shopData);
        $this->_template->render();
    }


    /* function updateCountries(){
    // Get table from open cart
    $srch = new SearchBase('oc_country');
    $srch->doNotCalculateRecords();
    $srch->doNotLimitRecords();
    $rs = $srch->getResultSet();
    $records = FatApp::getDb()->fetchAll($rs,'country_id');
    foreach($records as $country){
    $assignValues = array(
                'country_id' => $country['country_id'],
                'country_code' => $country['iso_code_2'],
                'country_active' => applicationConstants::ACTIVE,
    );
    FatApp::getDb()->insertFromArray('tbl_countries',$assignValues,false,array(),$assignValues);

    $assignData = array(
                'countrylang_country_id' => $country['country_id'],
                'country_name' => $country['name'],
                'countrylang_lang_id' => 1,
    );
    FatApp::getDb()->insertFromArray('tbl_countries_lang',$assignData,false,array(),$assignData);
    }
    } */

    public function getCountries()
    {
        $srch = new SearchBase('tbl_countries', 'c');
        $srch->joinTable('tbl_countries_lang', 'INNER JOIN', 'c_l.countrylang_country_id = c.country_id and c_l.countrylang_lang_id = 1', 'c_l');
        $srch->joinTable('tbl_countries_temp', 'LEFT OUTER JOIN', 't.country_temp_name = c_l.country_name', 't');

        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $srch->addFld(array('country_id', 'country_code', 'country_name', 'country_temp_id', 'country_temp_name'));

        $rs = $srch->getResultSet();
        $records = FatApp::getDb()->fetchAll($rs);
        $arr = array();
        foreach ($records as $country) {
            $arr[$country['country_temp_id']] = $country['country_id'];
        }
        //$this->getStates($arr);
        echo count($arr);
        echo "<pre>";
        print_r($arr);
        //var_dump($records);
    }

    public function cookie()
    {
        $isAffiliateCookieSet = false;
        $isReferrerCookieSet = false;

        if (isset($_COOKIE['affiliate_referrer_code_signup']) && $_COOKIE['affiliate_referrer_code_signup'] != '') {
            $isAffiliateCookieSet = true;
        }

        if (isset($_COOKIE['referrer_code_signup']) && $_COOKIE['referrer_code_signup'] != '') {
            $isReferrerCookieSet = true;
        }

        /* prioritize only when, both cookies are set, then credit on the basis of latest cookie set. [ */
        if ($isAffiliateCookieSet && $isReferrerCookieSet) {
            $affiliateReferrerCookieArr = unserialize($_COOKIE['affiliate_referrer_code_signup']);
            $referrerCookieArr = unserialize($_COOKIE['referrer_code_signup']);
            if ($affiliateReferrerCookieArr['creation_time'] > $referrerCookieArr['creation_time']) {
                $isReferrerCookieSet = false;
            } else {
                $isAffiliateCookieSet = false;
            }
        }
        /* ] */
    }

    public function reviewReminder()
    {
        Cronjob::remindBuyerForPendingReviews();
    }
    public function autoRenewSubscription()
    {
        Cronjob::autoRenewSubscription();
    }

    public function get_category_structure()
    {
        $categoriesDataArr = ProductCategory::getProdCatParentChildWiseArr($this->siteLangId, 0, false);
        commonhelper::printarray($categoriesDataArr);
        die();
    }

    public function testCache()
    {
        $collectionCache = FatCache::get('testcache', 1000, '.txt');

        if (!$collectionCache) {
            die;
            FatCache::set('testcache', 'testing the cache', '.txt');
        }
        echo FatCache::getCachedUrl('testcache', 100000, '.txt');
    }

    public function sendMail()
    {
        $headers = "From: developer@4demo.biz" . "\r\n" .
        "CC: anup.rawat@ablysoft.com";

        if (!mail("manpreet.kaur@fatbit.in", "testing", "Hello Manpreet Kaur", $headers)) {
            die("mail has not been sent");
        } else {
            die("mail has been sent successfully");
        }
    }


    public function testOrder()
    {
        $db = FatApp::getDb();
        $linkData = array();
        $sellerProduct = SellerProduct::getAttributesById(231, array('selprod_downloadable_link'));
        $downlodableLinks = preg_split("/\n|,/", $sellerProduct['selprod_downloadable_link']);
        /* CommonHelper::printArray($downlodableLinks);die; */
        foreach ($downlodableLinks as $link) {
            $linkData['opddl_op_id'] = 945;
            $linkData['opddl_downloadable_link'] = $link;
            if (!$db->insertFromArray(OrderProductDigitalLinks::DB_TBL, $linkData)) {
                $db->rollbackTransaction();
                $this->error = $opLangRecordObj->getError();
                return false;
            }
        }
    }

    public function checkEmailTemplate()
    {
        $selprod_id = array(109, 141, 148, 59, 66);
        $prodSrch = new ProductSearch(1);
        $prodSrch->setDefinedCriteria(0, 0, array(), false);
        $prodSrch->joinProductToCategory();
        $prodSrch->joinSellerSubscription();
        $prodSrch->addSubscriptionValidCondition();
        $prodSrch->doNotCalculateRecords();
        $prodSrch->addCondition('selprod_id', 'IN', $selprod_id);
        $prodSrch->addCondition('selprod_deleted', '=', applicationConstants::NO);
        $prodSrch->doNotLimitRecords();
        $prodSrch->addMultipleFields(
            array(
            'product_id', 'product_identifier', 'IFNULL(product_name,product_identifier) as product_name', 'product_seller_id', 'product_model', 'product_type', 'prodcat_id', 'IFNULL(prodcat_name,prodcat_identifier) as prodcat_name', 'product_upc', 'product_isbn',
            'selprod_id', 'selprod_user_id', 'selprod_condition', 'selprod_price', 'special_price_found', 'IFNULL(selprod_title  ,IFNULL(product_name, product_identifier)) as selprod_title',
            'theprice', 'selprod_stock', 'selprod_threshold_stock_level', 'IF(selprod_stock > 0, 1, 0) AS in_stock', 'brand_id', 'IFNULL(brand_name, brand_identifier) as brand_name', 'user_name',
            'shop_id', 'shop_name',
            'splprice_display_dis_type', 'splprice_display_dis_val', 'splprice_display_list_price')
        );
        $productRs = $prodSrch->getResultSet();
        $products = FatApp::getDb()->fetchAll($productRs);

        $this->set('products', $products);
        $this->_template->render(false, false, '_partial/products-in-cart-email.php');
    }

    public static function orderProduct($orderId = 'O1530169223', $opId = '428', $isRefunded = true, $isCancelled = true)
    {

        /* $op = new Orders();
        $childOrderInfo = $op->getOrderProductsByOpId($op_id,1);
        CommonHelper::printArray($childOrderInfo); */
        $opSrch = OrderProduct::getSearchObject();
        $opSrch->doNotCalculateRecords();
        $opSrch->doNotLimitRecords();
        $opSrch->addMultipleFields(array('op_id', 'op_selprod_id', 'op_selprod_user_id', 'op_unit_price', 'op_qty', 'op_actual_shipping_charges'));
        $opSrch->addCondition('op_order_id', '=', $orderId);
        if ($opId) {
            $opSrch->addCondition('op_id', '!=', $opId);
        }
        if ($isRefunded) {
            $opSrch->addCondition(OrderProduct::DB_TBL_PREFIX . 'refund_qty', '=', 0);
        }
        if ($isCancelled) {
            $opSrch->joinTable(OrderCancelRequest::DB_TBL, 'LEFT OUTER JOIN', 'ocr.' . OrderCancelRequest::DB_TBL_PREFIX . 'op_id = op.op_id', 'ocr');
            $cnd = $opSrch->addCondition(OrderCancelRequest::DB_TBL_PREFIX . 'status', '!=', 1);
            $cnd->attachCondition(OrderCancelRequest::DB_TBL_PREFIX . 'status', 'IS', 'mysql_func_null', 'OR', true);
        }
        echo $opSrch->getQuery();
        $rs = $opSrch->getResultSet();
        $row = FatApp::getDb()->fetchAll($rs);
        CommonHelper::printArray($row);
        die;
    }



    public function changeCustomUrl1()
    {
        $urlSrch = UrlRewrite::getSearchObject();
        $urlSrch->doNotCalculateRecords();
        $urlSrch->addMultipleFields(array('urlrewrite_id', 'urlrewrite_original', 'urlrewrite_custom'));
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

    public function testEmail()
    {
        AbandonedCart::sendReminderAbandonedCart();
    }

    public function testTaxjar()
    {
        require_once CONF_PLUGIN_DIR . '/tax/taxjartax/TaxJarTax.php';
        $itemsArr = [];
        
        $item = [
              'amount' => 100,
              'quantity' => 2,
              'itemCode' => 100,
              'taxCode' => '20010',
        ];
        array_push($itemsArr, $item);
        
        $shippingItems = [];
      
        $shippingItem = [
            'amount' => 12,
            'quantity' => 1,
            'itemCode' => 'S-100',
            'taxCode' => 'FR',
        ];
        array_push($shippingItems, $shippingItem);
        
       
        $fromAddress = array(
            'line1' => '9500 Gilman Drive',
            'line2' => '',
            'city' => 'La Jolla',
            'state' => 'CA',
            'postalCode' => '92093',
            'country' => 'US',
        );

        $toAddress = array(
            'line1' => '123 Palm Grove Ln',
            'line2' => '',
            'city' =>'Los Angeles',
            'state' => 'CA',
            'postalCode' => '90002',
            'country' => 'US',
        );
        
        
        $avalaraObj = new TaxJarTax(1, $fromAddress, $toAddress);
        $txRates = $avalaraObj->getRates($itemsArr, $shippingItems, 1);
        CommonHelper::printArray($txRates);
        exit;
    }
    
    public function testavalaratax()
    {
        require_once CONF_PLUGIN_DIR . '/tax/avalaratax/AvalaraTax.php';
        
        $itemsArr = [];
        
        $item = [
              'amount' => 200,
              'quantity' => 1,
              'itemCode' => 7,
              'taxCode' => 'PC030100',
        ];
        array_push($itemsArr, $item);
        
        $shippingItems = [];
      
        $shippingItem = [
            'amount' => 12,
            'quantity' => 1,
            'itemCode' => 'S-100',
            'taxCode' => 'FR',
        ];
        array_push($shippingItems, $shippingItem);
        
       
        $fromAddress = array(
            'line1' => '123 Main Street',
            'line2' => '',
            'city' => 'CA',
            'state' => 'CA',
            'stateCode' => 'CA',
            'postalCode' => '92615',
            'country' => 'US',
            'countryCode' => 'US',
        );

        $toAddress = array(
            'line1' => '1500 Broadway',
            'line2' => '',
            'city' =>'New York',
            'state' => 'NY',
            'stateCode' => 'NY',
            'postalCode' => '10019',
            'country' => 'US',
            'countryCode' => 'US',
        );
        
        
        $avalaraObj = new AvalaraTax(1, $fromAddress, $toAddress);
        $txRates = $avalaraObj->getRates($itemsArr, $shippingItems, 1);
        //$txRates = $avalaraObj->getCodes();
        //print_r($avalaraObj->getTaxApiActualResponse());
        CommonHelper::printArray($txRates);
//        die();
        
        //$taxRates1 = $avalaraObj->createInvoice($fromAddress , $toAddress,$itemsArr ,$shippingItems,100,'2019-10-11','S-1000');
     
        // echo('<pre>' . json_encode($txRates, JSON_PRETTY_PRINT) . '</pre>');
        // echo('<pre>' . json_encode($taxRates1, JSON_PRETTY_PRINT) . '</pre>');
        die();
        
//        CA STATE TAX
//        CA COUNTY TAX
//        CA CITY TAX
//        CA SPECIAL TAX
    }
    
    

    public function send()
    {
        $error = '';
        PushNotification::send($error);
        echo $error;
    }
}
