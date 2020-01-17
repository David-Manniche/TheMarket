<?php
class PushNotification extends MyAppModel
{
    public const DB_TBL = 'tbl_push_notifications';
    public const DB_TBL_PREFIX = 'pnotification_';
    public const DB_TBL_NOTIFICATION_TO_USER = 'tbl_push_notification_to_users';

    public const TYPE_APP = 1;

    public const STATUS_PENDING = 0;
    public const STATUS_PROCESSING = 1;
    public const STATUS_COMPLETED = 2;

    public const DEVICE_TOKENS_LIMIT = 3000;

    public const NOTIFY_TO_BUYER = 1;
    public const NOTIFY_TO_SELLER = 2;

    public function __construct($pushNotificationId = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $pushNotificationId);
    }

    public static function getSearchObject($joinNotificationUsers = false)
    {
        $srch = new SearchBase(static::DB_TBL, 'pn');

        if (true === $joinNotificationUsers) {
            $srch->joinTable(static::DB_TBL_NOTIFICATION_TO_USER, 'LEFT OUTER JOIN', 'pnu.pntu_pnotification_id = pn.' . static::DB_TBL_PREFIX . 'id', 'pnu');
        }

        return $srch;
    }

    public static function getStatusArr($langId)
    {
        return [
            static::STATUS_PENDING => Labels::getLabel('LBL_PENDING', $langId),
            static::STATUS_PROCESSING => Labels::getLabel('LBL_PROCESSING', $langId),
            static::STATUS_COMPLETED => Labels::getLabel('LBL_COMPLETED', $langId)
        ];
    }

    public static function getUserTypeArr($langId)
    {
        return [
            static::NOTIFY_TO_BUYER  => Labels::getLabel('LBL_BUYERS', $langId),
            static::NOTIFY_TO_SELLER  => Labels::getLabel('LBL_SELLERS', $langId),
        ];
    }

    private static function getDeviceTokensData($recordId, $buyers, $sellers, $joinNotificationUsers = true)
    {
        $buyers = FatUtility::int($buyers);
        $sellers = FatUtility::int($sellers);

        if (1 > $buyers && 1 > $sellers) {
            return false;
        }
        $obj = static::getSearchObject($joinNotificationUsers);
        $obj->doNotCalculateRecords();
        if (true === $joinNotificationUsers) {
            $joinUsers = 'pnu.pntu_user_id = u.user_id';
        } else {
            $joinBuyers = 'pn.pnotification_for_buyer = u.user_is_buyer AND pn.pnotification_for_buyer = 1';
            $joinSellers = 'pn.pnotification_for_seller = u.user_is_supplier and pn.pnotification_for_seller = 1';
            if (0 < $buyers && 0 < $sellers) {
                $joinUsers =  '((' . $joinBuyers . ') OR (' . $joinSellers . '))';
            } elseif (0 < $sellers) {
                $joinUsers = $joinSellers;
            } elseif (0 < $buyers) {
                $joinUsers = $joinBuyers;
            }
        }

        $obj->joinTable(User::DB_TBL, 'INNER JOIN', $joinUsers, 'u');
        $obj->joinTable(User::DB_TBL_CRED, 'INNER JOIN', 'uc.' . User::DB_TBL_CRED_PREFIX . 'user_id = u.user_id', 'uc');
        $obj->joinTable(UserAuthentication::DB_TBL_USER_AUTH, 'INNER JOIN', 'uauth.uauth_user_id = u.user_id', 'uauth');

        $obj->addCondition(static::DB_TBL_PREFIX . 'id', '=', $recordId);
        $obj->addCondition('uc.' . User::DB_TBL_CRED_PREFIX . 'active', '=', applicationConstants::YES);
        $obj->addCondition('uc.' . User::DB_TBL_CRED_PREFIX . 'verified', '=', applicationConstants::YES);
        
        if (0 < $buyers) {
            $cnd = $obj->addCondition('u.' . User::DB_TBL_PREFIX . 'is_buyer', '=', applicationConstants::YES);
        }
        
        if (0 < $sellers) {
            if (0 < $buyers) {
                $cnd->attachCondition('u.' . User::DB_TBL_PREFIX . 'is_supplier', '=', applicationConstants::YES);
            } else {
                $obj->addCondition('u.' . User::DB_TBL_PREFIX . 'is_supplier', '=', applicationConstants::YES);
            }
        }

        $obj->addDirectCondition("IF(
                pnotification_device_os = " . User::DEVICE_OS_BOTH . ",
                (uauth.`uauth_device_os` = " . User::DEVICE_OS_ANDROID . " OR uauth.`uauth_device_os` = " . User::DEVICE_OS_IOS . "),
                (uauth.`uauth_device_os` = pnotification_device_os)
            )");

        $obj->addCondition('uauth_fcm_id', '!=', '');
        $obj->addCondition('uauth_last_access', '>=', date('Y-m-d H:i:s', strtotime("-7 DAYS")));
        $obj->addCondition('uauth_user_id', '>', 'mysql_func_pnotification_till_user_id', 'AND', true);

        $obj->addMultipleFields(['uauth_user_id', 'uauth_fcm_id', 'uauth_device_os']);
        $obj->addOrder('uauth_user_id', 'ASC');
        $rs = $obj->getResultSet();
        $tokenData = FatApp::getDb()->fetchAll($rs);
        $lastToken   = end($tokenData);
        $lastUserId = $lastToken['uauth_user_id'];
        
        $deviceTokens = [];
        foreach ($tokenData as $data) {
            $deviceTokens[$data['uauth_device_os']][] = $data['uauth_fcm_id'];
        }
        return [
            'lastUserId' => $lastUserId,
            'deviceTokens' => $deviceTokens
        ];
    }

    private static function updateDetail($recordId, $status, $lastExecutedUserId)
    {
        $dataToSave = [
            'pnotification_id' => $recordId,
            'pnotification_status' => $status,
            'pnotification_till_user_id' => $lastExecutedUserId
        ];

        $dataToUpdateOnDuplicate = $dataToSave;
        unset($dataToUpdateOnDuplicate['pnotification_id']);
        if (!FatApp::getDb()->insertFromArray(static::DB_TBL, $dataToSave, false, array(), $dataToUpdateOnDuplicate)) {
            // $this->error = Labels::getLabel("MSG_UNABLE_TO_UPDATE!", CommonHelper::getLangId());
            return false;
        }
    }

    public static function send()
    {
        $defaultPushNotiAPI = FatApp::getConfig('CONF_DEFAULT_PLUGIN_' . Plugin::TYPE_PUSH_NOTIFICATION_API, FatUtility::VAR_INT, 0);
        if (empty($defaultPushNotiAPI)) {
            // $this->error =  Labels::getLabel('MSG_DEFAULT_PUSH_NOTIFICATION_API_NOT_SET', CommonHelper::getLangId());
            return false;
        }

        $keyName = Plugin::getAttributesById($defaultPushNotiAPI, 'plugin_code');
        if (1 > Plugin::isActive($keyName)) {
            // $this->error =  Labels::getLabel('MSG_PLUGIN_IS_NOT_ACTIVE', CommonHelper::getLangId());
            return false;
        }
        require_once CONF_PLUGIN_DIR . '/push-notification/' . $keyName . '.php';

        $limit = $keyName::LIMIT;

        $srchU = new SearchBase(static::DB_TBL_NOTIFICATION_TO_USER, 'pnu');
        $srchU->doNotCalculateRecords();
        $srchU->addFld('pntu_pnotification_id');
        $srchU->addCondition('pntu_pnotification_id', '=', 'mysql_func_pnotification_id', 'AND', true);
        $srchU->setPageSize(1);

        $srch = static::getSearchObject();
        $srch->addMultipleFields(['*', '(CASE WHEN (' . $srchU->getQuery() . ') > 0 THEN 1 ELSE 0 END) AS pnotification_user_linked']);
        $srch->addCondition(static::DB_TBL_PREFIX . 'status', '!=', static::STATUS_COMPLETED);
        $srch->addCondition(static::DB_TBL_PREFIX . 'till_user_id', '!=', -1);
        $srch->addCondition('pnotification_notified_on', '<', 'mysql_func_NOW()', 'AND', true);
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        $rs = $srch->getResultSet();
        $notificationList = FatApp::getDb()->fetchAll($rs);
        if (1 > count($notificationList)) {
            // $this->error = Labels::getLabel('MSG_NO_RECORD_FOUND', CommonHelper::getLangId());
            return false;
        }
        foreach ($notificationList as $notificationDetail) {
            $recordId = $notificationDetail[static::DB_TBL_PREFIX . 'id'];
            $buyers = $notificationDetail['pnotification_for_buyer'];
            $sellers = $notificationDetail['pnotification_for_seller'];

            $joinNotificationUsers = (0 < $notificationDetail['pnotification_user_linked']) ? true : false;
            
            $data = static::getDeviceTokensData($recordId, $buyers, $sellers, $joinNotificationUsers);
            $deviceTokens = $data['deviceTokens'];
            $lastUserId = $data['lastUserId'];
            
            if (empty($deviceTokens) || 1 > count($deviceTokens)) {
                static::updateDetail($recordId, static::STATUS_COMPLETED, -1);
                continue;
            }

            try {
                $imageUrl = '';
                if ($imgData = AttachedFile::getAttachment(AttachedFile::FILETYPE_PUSH_NOTIFICATION_IMAGE, $recordId)) {
                    $uploadedTime = AttachedFile::setTimeParam($imgData['afile_updated_at']);
                    $imageUrl = FatCache::getCachedUrl(CommonHelper::generateFullUrl('Image', 'pushNotificationImage', [$recordId], CONF_WEBROOT_FRONT_URL) . $uploadedTime, CONF_IMG_CACHE_TIME, '.jpg');
                }

                $data = [
                    'image' => $imageUrl,
                    'customData' => [
                        'isCustomPushNotification' => 1,
                        'lang_id' => $notificationDetail['pnotification_lang_id'],
                        'urlDetail' => !empty($notificationDetail['pnotification_url']) ? CommonHelper::getUrlTypeData($notificationDetail['pnotification_url']) : (object)array(),
                    ]
                ];

                foreach ($deviceTokens as $os => $dtokens) {
                    $obj = new $keyName($dtokens);
                    $response = $obj->notify($notificationDetail['pnotification_title'], $notificationDetail['pnotification_description'], $os, $data);
                    if (false === $response) {
                        /* $this->error =  $obj->getError(); */
                    }
                }
            } catch (\Error $e) {
                /* $this->error =  'ERR - ' . $e->getMessage(); */
            }

            if (true === $joinNotificationUsers) {
                static::updateDetail($recordId, static::STATUS_COMPLETED, -1);
            } else {
                static::updateDetail($recordId, static::STATUS_PROCESSING, $lastUserId);
            }
            // return $response;
        }
    }
}