<?php
class Addon extends MyAppModel
{
    public const DB_TBL = 'tbl_addons';
    public const DB_TBL_LANG = 'tbl_addons_lang';
    public const DB_TBL_PREFIX = 'addon_';

    public const TYPE_CURRENCY_API = 1;

    private $db;
    
    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
        $this->objMainTableRecord->setSensitiveFields(
            array('addon_code')
        );
    }

    public static function getSearchObject($langId = 0, $isActive = true)
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
                'ad_l.addonlang_' . static::DB_TBL_PREFIX . 'id = ad.' . static::DB_TBL_PREFIX . 'id and ad_l.addonlang_lang_id = ' . $langId,
                'ad_l'
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
            static::TYPE_CURRENCY_API => Labels::getLabel('LBL_CURRENCY_API', $langId)
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
                    'COALESCE(ad_l.' . static::DB_TBL_PREFIX . 'name, ad.' . static::DB_TBL_PREFIX . 'identifier) as addon_name'
                ]
            );
        } else {
            $srch->addFld('COALESCE(ad_l.' . static::DB_TBL_PREFIX . 'name, ad.' . static::DB_TBL_PREFIX . 'identifier) as addon_name');
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
        return $addonsTypeArr = static::getDataByType($typeId, $langId, true);
    }
}
