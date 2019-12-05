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
}
