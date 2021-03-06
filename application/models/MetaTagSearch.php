<?php

class MetaTagSearch extends SearchBase
{
    public function __construct($langId = 0)
    {
        parent::__construct(MetaTag::DB_TBL, 'mt');
        $langId = FatUtility::int($langId);

        if ($langId > 0) {
            $this->joinTable(
                MetaTag::DB_TBL_LANG,
                'LEFT OUTER JOIN',
                'mt_l.' . MetaTag::DB_TBL_LANG_PREFIX . 'meta_id = mt.meta_id
			AND mt_l.' . MetaTag::DB_TBL_LANG_PREFIX . 'lang_id = ' . $langId,
                'mt_l'
            );
        }
    }
}
