<?php

class OrderProductChargelog extends MyAppModel
{
    public const DB_TBL = 'tbl_order_prod_charges_logs';
    public const DB_TBL_PREFIX = 'opchargelog_';

    public const DB_TBL_LANG = 'tbl_order_prod_charges_logs_lang';
    public const DB_TBL_LANG_PREFIX = 'opchargeloglang_';

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    public static function getSearchObject($langId = 0)
    {
        $srch = new SearchBase(static::DB_TBL, 'opcl');

        if ($langId > 0) {
            $srch->joinTable(
                static::DB_TBL_LANG,
                'LEFT OUTER JOIN',
                'opcl_l.opchargeloglang_opchargelog_id = opchargelog_id
			AND opcl_l.opchargeloglang_lang_id = ' . $langId,
                'opcl_l'
            );
        }

        return $srch;
    }
}
