<?php
class CustomNotification extends MyAppModel
{
    public const DB_TBL = 'tbl_custom_notifications';
    public const DB_TBL_PREFIX = 'cnotification_';
    public const DB_TBL_NOTIFICATION_TO_USER = 'tbl_custom_notification_to_users';

    public const TYPE_APP = 1;
    public const TYPE_WEB = 2;
    public const TYPE_BOTH = 3;

    public const STATUS_PENDING = 0;
    public const STATUS_SENT = 1;

    public const PAGE_SIZE = 1000;

    public function __construct($customNotificationId = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $customNotificationId);
    }

    public static function getSearchObject($joinNotificationUsers = false)
    {
        $srch = new SearchBase(static::DB_TBL, 'cn');

        if (true === $joinNotificationUsers) {
            $srch->joinTable(static::DB_TBL_NOTIFICATION_TO_USER, 'LEFT OUTER JOIN', 'cnu.cntu_cnotification_id = cn.' . static::DB_TBL_PREFIX . 'id', 'cnu');
        }
        return $srch;
    }

    public static function getTypeArr($langId)
    {
        return [
            static::TYPE_APP => Labels::getLabel('LBL_APP', $langId),
            static::TYPE_WEB => Labels::getLabel('LBL_WEB', $langId),
            static::TYPE_BOTH => Labels::getLabel('LBL_BOTH', $langId),
        ];
    }

    public static function getStatusArr($langId)
    {
        return [
            static::STATUS_PENDING => Labels::getLabel('LBL_PENDING', $langId),
            static::STATUS_SENT => Labels::getLabel('LBL_SENT', $langId)
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
}
