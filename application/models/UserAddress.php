<?php class UserAddress extends MyAppModel { 
	const DB_TBL = 'tbl_user_address';
	const DB_TBL_PREFIX = 'ua_';
	
	public function __construct( $ua_id = 0 ) {
		parent::__construct ( static::DB_TBL, static::DB_TBL_PREFIX . 'id', $ua_id );		
		$this->objMainTableRecord->setSensitiveFields ( array() );
	}
	
	public static function getSearchObject( $userId = 0 ) {
		$srch = new SearchBase(static::DB_TBL, 'ua');
		
		if( $userId ){
			$srch->addCondition( static::tblFld('user_id'), '=', $userId ) ;
		}
		return $srch;
	}
	
	public static function getUserAddresses( $user_id = 0, $lang_id = 0, $ua_is_default = 0, $ua_id = 0  ){
		$ua_id = FatUtility::int($ua_id);
		$lang_id = FatUtility::int($lang_id);
		$user_id = FatUtility::int($user_id);
		$ua_is_default = FatUtility::int($ua_is_default);
		
		$srch = static::getSearchObject( $user_id );
		$srch->joinTable( Countries::DB_TBL, 'LEFT OUTER JOIN', 'c.country_id = ua.ua_country_id', 'c' );
		$srch->addCondition( 'country_active','=', applicationConstants::ACTIVE );
		
		$srch->joinTable( States::DB_TBL, 'LEFT OUTER JOIN', 's.state_id = ua.ua_state_id', 's' );
		$srch->addCondition( 'state_active','=', applicationConstants::ACTIVE );
		
		$srch->addMultipleFields( array('ua.*','state_code','country_code') );
		if( $lang_id ){
			$srch->joinTable( Countries::DB_TBL_LANG, 'LEFT OUTER JOIN', 'c.country_id = c_l.countrylang_country_id AND countrylang_lang_id = '.$lang_id, 'c_l' );
			$srch->addFld( 'IFNULL(country_name, country_code) as country_name' );
			
			$srch->joinTable( States::DB_TBL_LANG, 'LEFT OUTER JOIN', 's.state_id = s_l.statelang_state_id AND s_l.statelang_lang_id = ' . $lang_id, 's_l' );
			$srch->addFld( 'IFNULL(state_name, state_identifier) as state_name' );
		}
		$srch->doNotCalculateRecords();
		$srch->doNotLimitRecords();
		
		if( $ua_id ){
			$srch->addCondition( static::tblFld('id'), '=', $ua_id );
		}
		if( $ua_is_default ){
			$srch->addCondition( static::tblFld('is_default'), '=', 1 );
		}
		$rs = $srch->getResultSet();
		
		if( $ua_id ){
			return FatApp::getDb()->fetch( $rs );
		}
		return FatApp::getDb()->fetchAll( $rs );
	}
	
} ?>