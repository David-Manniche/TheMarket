<?php

class Admin extends MyAppModel
{
    public static $admin_dashboard_layouts = array(0 => 'default', 1 => 'switch_layout');

    public const SUPER = 1;
    public const DB_TBL = 'tbl_admin';
    public const DB_TBL_PREFIX = 'admin_';
    public function __construct($userId = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $userId);
        $this->objMainTableRecord->setSensitiveFields(array());
    }
}
