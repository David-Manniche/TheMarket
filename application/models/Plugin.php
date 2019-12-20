<?php
class Plugin extends MyAppModel
{
    public const DB_TBL = 'tbl_plugins';
    public const DB_TBL_LANG = 'tbl_plugins_lang';
    public const DB_TBL_PREFIX = 'plugin_';

    public const TYPE_CURRENCY_API = 1;
    public const TYPE_SOCIAL_LOGIN_API = 2;

    private $db;
    
    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
        $this->objMainTableRecord->setSensitiveFields(
            array('plugin_code')
        );
    }

    public static function getSearchObject($langId = 0, $isActive = true, $joinSettings = false)
    {
        $langId = FatUtility::int($langId);
        $srch = new SearchBase(static::DB_TBL, 'plg');
        if ($isActive == true) {
            $srch->addCondition('plg.' . static::DB_TBL_PREFIX . 'active', '=', applicationConstants::ACTIVE);
        }
        if ($langId > 0) {
            $srch->joinTable(
                static::DB_TBL_LANG,
                'LEFT OUTER JOIN',
                'plg_l.pluginlang_' . static::DB_TBL_PREFIX . 'id = plg.' . static::DB_TBL_PREFIX . 'id and plg_l.pluginlang_lang_id = ' . $langId,
                'plg_l'
            );
        }

        if (true === $joinSettings) {
            $srch->joinTable(
                PluginSetting::DB_TBL,
                'LEFT OUTER JOIN',
                'plgs.' . PluginSetting::DB_TBL_PREFIX . static::DB_TBL_PREFIX . 'id = plg.' . static::DB_TBL_PREFIX . 'id',
                'plgs'
            );
        }
        $srch->addOrder('plg.' . static::DB_TBL_PREFIX . 'active', 'DESC');
        $srch->addOrder('plg.' . static::DB_TBL_PREFIX . 'display_order', 'ASC');
        return $srch;
    }


    public static function getAttributesByCode($code, $attr = null)
    {
        $srch = new SearchBase(static::DB_TBL, 'plg');
        $srch->addCondition('plg.' . static::DB_TBL_PREFIX . 'code', '=', $code);
        
        if (null != $attr) {
            if (is_array($attr)) {
                $srch->addMultipleFields($attr);
            } elseif (is_string($attr)) {
                $srch->addFld($attr);
            }
        }

        $rs = $srch->getResultSet();
        $row = FatApp::getDb()->fetch($rs);
        if (empty($row) || !is_array($row)) {
            return false;
        }

        if (is_string($attr)) {
            return $row[$attr];
        }
        return $row;
    }

    public static function getTypeArr($langId)
    {
        return [
            static::TYPE_CURRENCY_API => Labels::getLabel('LBL_CURRENCY_API', $langId),
            static::TYPE_SOCIAL_LOGIN_API => Labels::getLabel('LBL_SOCIAL_LOGIN_API', $langId),
        ];
    }

    private static function pluginTypeSrchObj($typeId, $langId, $assoc = true, $active = false)
    {
        $srch = static::getSearchObject($langId, $active);
        if (false === $assoc) {
            $srch->addMultipleFields(
                [
                    static::DB_TBL_PREFIX . 'id',
                    static::DB_TBL_PREFIX . 'code',
                    static::DB_TBL_PREFIX . 'description',
                    'COALESCE(plg_l.' . static::DB_TBL_PREFIX . 'name, plg.' . static::DB_TBL_PREFIX . 'identifier) as plugin_name',
                    static::DB_TBL_PREFIX . 'active',
                ]
            );
        }

        $srch->addCondition('plg.' . static::DB_TBL_PREFIX . 'type', '=', $typeId);
        return $srch;
    }

    public static function getDataByType($typeId, $langId = 0, $assoc = false, $active = true)
    {
        $typeId = FatUtility::int($typeId);
        if (1 > $typeId) {
            return false;
        }

        $srch = static::pluginTypeSrchObj($typeId, $langId, $assoc, $active);

        if (true == $assoc) {
            $srch->addMultipleFields(
                [
                    'plg.' . static::DB_TBL_PREFIX . 'id',
                    'COALESCE(plg_l.' . static::DB_TBL_PREFIX . 'name, plg.' . static::DB_TBL_PREFIX . 'identifier) as plugin_name'
                ]
            );
        }

        $rs = $srch->getResultSet();
        
        $db = FatApp::getDb();
        if (true == $assoc) {
            return $db->fetchAllAssoc($rs);
        }

        return $db->fetchAll($rs, static::DB_TBL_PREFIX . 'id');
    }

    public static function getNamesByType($typeId, $langId)
    {
        $typeId = FatUtility::int($typeId);
        $langId = FatUtility::int($langId);
        if (1 > $typeId && 1 > $langId) {
            return false;
        }
        return $pluginsTypeArr = static::getDataByType($typeId, $langId, true);
    }

    public static function getSocialLoginPluginsStatus($langId)
    {
        $srch = static::pluginTypeSrchObj(static::TYPE_SOCIAL_LOGIN_API, $langId);
        $srch->addMultipleFields(
            [
                'plg.' . static::DB_TBL_PREFIX . 'code',
                'plg.' . static::DB_TBL_PREFIX . 'active'
            ]
        );
        $rs = $srch->getResultSet();
        
        return FatApp::getDb()->fetchAllAssoc($rs);
    }
}
