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
        $srch = new SearchBase(static::DB_TBL, 'ad');
        if ($isActive == true) {
            $srch->addCondition('ad.' . static::DB_TBL_PREFIX . 'active', '=', applicationConstants::ACTIVE);
        }
        if ($langId > 0) {
            $srch->joinTable(
                static::DB_TBL_LANG,
                'LEFT OUTER JOIN',
                'ad_l.pluginlang_' . static::DB_TBL_PREFIX . 'id = ad.' . static::DB_TBL_PREFIX . 'id and ad_l.pluginlang_lang_id = ' . $langId,
                'ad_l'
            );
        }

        if (true === $joinSettings) {
            $srch->joinTable(
                PluginSetting::DB_TBL,
                'LEFT OUTER JOIN',
                'ads.' . PluginSetting::DB_TBL_PREFIX . static::DB_TBL_PREFIX . 'id = ad.' . static::DB_TBL_PREFIX . 'id',
                'ads'
            );
        }
        $srch->addOrder('ad.' . static::DB_TBL_PREFIX . 'active', 'DESC');
        $srch->addOrder('ad.' . static::DB_TBL_PREFIX . 'display_order', 'ASC');
        return $srch;
    }


    public static function getAttributesByCode($code, $attr = null)
    {
        $srch = new SearchBase(static::DB_TBL, 'ad');
        $srch->addCondition('ad.' . static::DB_TBL_PREFIX . 'code', '=', $code);
        
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

    public static function getDataByType($typeId, $langId = 0, $assoc = false)
    {
        $typeId = FatUtility::int($typeId);
        if (1 > $typeId) {
            return false;
        }
        $srch = static::getSearchObject($langId);
        
        if (true == $assoc) {
            $srch->addMultipleFields(
                [
                    'ad.' . static::DB_TBL_PREFIX . 'id',
                    'COALESCE(ad_l.' . static::DB_TBL_PREFIX . 'name, ad.' . static::DB_TBL_PREFIX . 'identifier) as plugin_name'
                ]
            );
        } else {
            $srch->addFld('COALESCE(ad_l.' . static::DB_TBL_PREFIX . 'name, ad.' . static::DB_TBL_PREFIX . 'identifier) as plugin_name');
        }

        $srch->addCondition('ad.' . static::DB_TBL_PREFIX . 'type', '=', $typeId);
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
}
