<?php

class ThemeColor extends MyAppModel
{
    public const DB_TBL = 'tbl_theme';
    public const DB_TBL_COLORS = 'tbl_theme_colors';
    public const DB_TBL_PREFIX = 'theme_';
    public const DB_TBL_COLORS_PREFIX = 'tcolor_';

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    public static function getSearchObject($joinColors = false)
    {
        $srch = new SearchBase(static::DB_TBL, 't');

        if ($joinColors) {
            $srch->joinTable(
                static::DB_TBL_COLORS,
                'LEFT OUTER JOIN',
                'tcolor_theme_id = theme_id'
            );
        }
        return $srch;
    }

    public static function getThemeColorsById($themeId)
    {
        $srch = static::getSearchObject(true);
        $srch->addMultipleFields(array('tcolor_key', 'tcolor_value'));
        $srch->addCondition('theme_id', '=', $themeId);
        $rs = $srch->getResultSet();
        return FatApp::getDb()->fetchAll($rs);
    }
}
