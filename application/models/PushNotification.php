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

    public const DEVICE_TOKENS_LIMIT = 1000;

    public function __construct($pushNotificationId = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $pushNotificationId);
    }

    public static function getSearchObject($joinNotificationUsers = false)
    {
        $srch = new SearchBase(static::DB_TBL, 'cn');

        if (true === $joinNotificationUsers) {
            $srch->joinTable(static::DB_TBL_NOTIFICATION_TO_USER, 'LEFT OUTER JOIN', 'cnu.cntu_pnotification_id = cn.' . static::DB_TBL_PREFIX . 'id', 'cnu');
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

    public static function getNotifyToArr($langId)
    {
        return [
            Labels::getLabel('LBL_BUYERS', $langId),
            Labels::getLabel('LBL_SELLERS', $langId),
        ];
    }

    public function notify($deviceTokens, $title, $message)
    {
        $google_push_notification_api_key = FatApp::getConfig("CONF_GOOGLE_PUSH_NOTIFICATION_API_KEY", FatUtility::VAR_STRING, '');
        if (empty($google_push_notification_api_key)) {
            $this->error = Labels::getLabel('LBL_NO_SERVER_KEY_DEFINED_FOR_PUSH_NOTIFICATION', CommonHelper::getLangId());
            return false;
        }

        if (!is_array($deviceTokens) || empty($deviceTokens) || 1000 < count($deviceTokens)) {
            $this->error = Labels::getLabel('LBL_ARRAY_MUST_CONTAIN_AT_LEAST_1_AND_AT_MOST_1000_REGISTRATION_TOKENS', CommonHelper::getLangId());
            return false;
        }

        if (empty($title) || empty($message)) {
            $this->error = Labels::getLabel('LBL_INVALID_REQUEST_PARAMETERS', CommonHelper::getLangId());
            return false;
        }

        $msg = [
            'title' => $title,
            'message' => $message
        ];
        $fields = [
            'registration_ids' => $deviceTokens,
            'data' => $msg
        ];

        $headers = [
            'Authorization: key=' . $google_push_notification_api_key,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public static function getDeviceTokens($limit = 0)
    {
        $limit = FatUtility::int($limit);
        $limit = 1 > $limit ? static::DEVICE_TOKENS_LIMIT : $limit;
        $srch = User::getSearchObject(true, true);
        $srch->joinTable(UserAuthentication::DB_TBL_USER_AUTH, 'LEFT OUTER JOIN', 'uauth.uauth_user_id = u.user_id', 'uauth');
        $srch->addCondition('uc.' . User::DB_TBL_CRED_PREFIX . 'active', '=', 1);
        $srch->addCondition('uc.' . User::DB_TBL_CRED_PREFIX . 'verified', '=', 1);
        $srch->addCondition('uauth_fcm_id', '!=', '');
        $srch->addCondition('uauth_last_access', '>=', date('Y-m-d H:i:s', strtotime("-7 DAYS")));
        $srch->addMultipleFields(['uauth_user_id', 'uauth_fcm_id']);
        $srch->setPageSize($limit);
        $rs = $srch->getResultSet();
        return FatApp::getDb()->fetchAllAssoc($rs);
    }
}
