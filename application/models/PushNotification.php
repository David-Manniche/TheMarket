<?php
class PushNotification extends MyAppModel
{
    public const DB_TBL = 'tbl_push_notifications';
    public const DB_TBL_PREFIX = 'pnotification_';
    public const DB_TBL_NOTIFICATION_TO_USER = 'tbl_push_notification_to_users';

    public const TYPE_APP = 1;
    public const TYPE_WEB = 2;
    public const TYPE_BOTH = 3;

    public const STATUS_PENDING = 0;
    public const STATUS_SENT = 1;

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

    public static function getTypeArr($langId)
    {
        return [
            static::TYPE_APP => Labels::getLabel('LBL_APP', $langId),
            static::TYPE_WEB => Labels::getLabel('LBL_WEB', $langId),
        ];
    }

    public static function getStatusArr($langId)
    {
        return [
            static::STATUS_PENDING => Labels::getLabel('LBL_PENDING', $langId),
            static::STATUS_SENT => Labels::getLabel('LBL_SENT', $langId)
        ];
    }

    public static function getUserTypeArr($langId)
    {
        return [
            static::NOTIFY_TO_BUYER  => Labels::getLabel('LBL_BUYERS', $langId),
            static::NOTIFY_TO_SELLER  => Labels::getLabel('LBL_SELLERS', $langId),
        ];
    }

    private function joinNotifyUsers($obj, $buyers, $sellers)
    {
        $buyers = FatUtility::int($buyers);
        $sellers = FatUtility::int($sellers);

        if (1 > $buyers && 1 > $sellers) {
            return false;
        }
        
        $obj->joinTable(UserAuthentication::DB_TBL_USER_AUTH, 'LEFT OUTER JOIN', 'uauth.uauth_user_id = u.user_id', 'uauth');
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

        $obj->addCondition('uauth_fcm_id', '!=', '');
        $obj->addCondition('uauth_last_access', '>=', date('Y-m-d H:i:s', strtotime("-7 DAYS")));
        $obj->addMultipleFields(['uauth_user_id', 'uauth_fcm_id']);
        return $obj;
    }

    private function getDeviceTokens($buyers, $sellers, $limit)
    {
        $srch = User::getSearchObject(true, true);
        $srch = $this->joinNotifyUsers($srch, $buyers, $sellers);
        $srch->setPageSize($limit);
        $rs = $srch->getResultSet();
        return FatApp::getDb()->fetchAllAssoc($rs);
    }

    public function send()
    {
        $defaultPushNotiAPI = FatApp::getConfig('CONF_DEFAULT_PLUGIN_' . PLUGIN::TYPE_PUSH_NOTIFICATION_API, FatUtility::VAR_INT, 0);
        if (empty($defaultPushNotiAPI)) {
            $this->error = Labels::getLabel('MSG_DEFAULT_PUSH_NOTIFICATION_API_NOT_SET', CommonHelper::getLangId());
            return false;
        }

        $keyName = Plugin::getAttributesById($defaultPushNotiAPI, 'plugin_code');
        $limit = $keyName::LIMIT;

        $notificationDetail = static::getAttributesById($this->mainTableRecordId);
        
        if (static::STATUS_SENT == $notificationDetail['pnotification_active']) {
            $this->error = Labels::getLabel('MSG_ALREADY_SENT', CommonHelper::getLangId());
            return false;
        }

        if (strtotime($notificationDetail['pnotification_notified_on']) > time()) {
            $this->error = Labels::getLabel('MSG_SCHEDULE_DATE_TIME_NOT_MATCHED', CommonHelper::getLangId());
            return false;
        }

        $buyers = $notificationDetail['pnotification_for_buyer'];
        $sellers = $notificationDetail['pnotification_for_seller'];

        $notifyObj = static::getSearchObject(true);
        $notifyObj->joinTable(User::DB_TBL, 'INNER JOIN', 'pnu.pntu_user_id = u.user_id', 'u');
        $notifyObj->joinTable(User::DB_TBL_CRED, 'LEFT OUTER JOIN', 'uc.' . User::DB_TBL_CRED_PREFIX . 'user_id = u.user_id', 'uc');
        $notifyObj->addCondition(static::DB_TBL_PREFIX . 'id', '=', $this->mainTableRecordId);
        $notifyObj = $this->joinNotifyUsers($notifyObj, $buyers, $sellers);
        $rs = $notifyObj->getResultSet();
        $deviceTokens = FatApp::getDb()->fetchAllAssoc($rs);
        if (empty($deviceTokens)) {
            $deviceTokens = $this->getDeviceTokens($buyers, $sellers, $limit);
        }

        if (empty($deviceTokens) || 1 > count($deviceTokens)) {
            $this->error = Labels::getLabel('MSG_NO_DEVICE_TOKEN_FOUND', CommonHelper::getLangId());
            return false;
        }

        try {
            $obj = new $keyName();
            $data = [
                'title' => $notificationDetail['pnotification_title'],
                'message' => $notificationDetail['pnotification_description'],
                'image' => CommonHelper::generateFullUrl('Image', 'pushNotificationImage', [CommonHelper::getLangId(), $this->mainTableRecordId], CONF_WEBROOT_FRONT_URL),
                'extra' => [
                    'lang_id' => $notificationDetail['pnotification_lang_id'],
                    'urlDetail' => !empty($notificationDetail['pnotification_url']) ? CommonHelper::getUrlTypeData($notificationDetail['pnotification_url']) : [],
                ]
            ];
            $response = $obj->notify(array_values($deviceTokens), $data);
            if (false === $response) {
                $this->error = $obj->getError();
                return false;
            }
        } catch (\Error $e) {
            $this->error = 'ERR - ' . $e->getMessage();
            return false;
        }
        return $response;
    }
}
