<?php
class TaxRuleCombined extends MyAppModel
{
    const DB_TBL = 'tbl_tax_rule_details';
    const DB_TBL_PREFIX = 'taxruledet_';

    const DB_TBL_LANG = 'tbl_tax_rule_details_lang';
    const DB_TBL_LANG_PREFIX = 'taxruledetlang_';

    public function __construct(int $id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
    }

    /**
    * getSearchObject
    *
    * @param  int $langId
    * @return object
    */
    public static function getSearchObject(int $langId = 0): object
    {
        $langId = FatUtility::int($langId);
        $srch = new SearchBase(static::DB_TBL, 'taxCom');
        if ($langId > 0) {
            $srch->joinTable(
                static::DB_TBL_LANG,
                'LEFT OUTER JOIN',
                'taxCom_l.' . static::DB_TBL_LANG_PREFIX . 'taxruledet_id = taxCom.' . static::tblFld('id') . ' and
            taxCom_l.' . static::DB_TBL_LANG_PREFIX . 'lang_id = ' . $langId,
                'taxCom_l'
            );
        }
        return $srch;
    }
}
