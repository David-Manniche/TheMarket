<?php
class TaxRuleLocation extends MyAppModel
{
    const DB_TBL = 'tbl_tax_rule_locations';
    const DB_TBL_PREFIX = 'taxruleloc_';

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
    }

    public static function getSearchObject()
    {
		$srch = new SearchBase(static::DB_TBL, 'taxRuleLoc');
		return $srch;
    }
	
	public function updateLocations($data)
	{
		if (!FatApp::getDb()->insertFromArray(self::DB_TBL, $data, true, array(), $data)) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }
		return true;
	}
	
	public function deleteLocations($taxCatId)
	{
		if (!FatApp::getDb()->deleteRecords(
			self::DB_TBL, array(
				'smt'=> self::DB_TBL_PREFIX .'taxcat_id=? ', 
				'vals'=>array($taxCatId)
		))) {
			$this->error = FatApp::getDb()->getError();
            return false;
		}
		return true;
	}
}