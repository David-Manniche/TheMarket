<?php
/* This Class is used for to list sellable products, sellable means,
	=> shop must be active
	=> Shop Display Status must be on
	=> Seller/User must be active
	=> Seller/User email must be verified.
	=> Seller/User must be supplier = 1
	=> Product Must be active
	=> Product must be approved
	=> Associated Brand must be active and not deleted.
	=> Product Category must be active and category must not be deleted.
	*/
class ProductSearch extends SearchBase
{
    private $langId;

    private $sellerProductsJoined = false;
    private $sellerUserJoined = false;
    private $commonLangId;
    private $sellerSubscriptionOrderJoined = false;
    
    public function __construct( $langId = 0, $otherTbl = null, $prodIdColumName = null, $isProductActive = true, $isProductApproved = true, $isProductDeleted = true) 
    {
        $this->langId = FatUtility::int($langId);
        $this->commonLangId = CommonHelper::getLangId();
        
        if($otherTbl == null ) {
            parent::__construct(Product::DB_TBL, 'p');
        } else {
            /* Same productsearch class used to fetch products under any batch/group, do not call setDefinedCriteria, call setBatchProductsCriteria().[ */
            parent::__construct($otherTbl, 'temp');
            $this->joinTable(SellerProduct::DB_TBL, 'INNER JOIN', 'temp.'.ProductGroup::DB_PRODUCT_TO_GROUP_PREFIX.'selprod_id = sp.selprod_id', 'sp');
            if($this->langId > 0 ) {
                $this->joinTable(SellerProduct::DB_LANG_TBL, 'LEFT OUTER JOIN', 'sp.selprod_id = sp_l.selprodlang_selprod_id AND selprodlang_lang_id = '.$this->langId, 'sp_l');
            }
            $this->joinTable(Product::DB_TBL, 'INNER JOIN', 'sp.selprod_product_id = p.product_id', 'p');
            /* ] */
            $this->addOrder('ptg_is_main_product', 'DESC');
            /* $this->addCondition('p.product_deleted','=',applicationConstants::NO); */
        }

        if ($this->langId > 0) {
            $this->joinTable(
                Product::DB_LANG_TBL, 'LEFT OUTER JOIN',
                'productlang_product_id = p.product_id
			AND productlang_lang_id = ' . $this->langId, 'tp_l'
            );
        }
        
        if($isProductActive ) {
            $this->addCondition('product_active', '=', applicationConstants::ACTIVE);
        }
        
        if($isProductDeleted ) {
            $this->addCondition('product_deleted', '=', applicationConstants::NO);
        }
        
        if($isProductApproved ) {
            $this->addCondition('product_approved', '=', PRODUCT::APPROVED);
        }
    }
    
    public function setDefinedCriteria($joinPrice = 0, $bySeller = 0,$criteria = array(),$checkAvailableFrom = true)
    {
        $joinPrice =  FatUtility::int($joinPrice);
        if($joinPrice ) {
            $this->joinForPrice('', $criteria, $checkAvailableFrom);
        } else {
            $this->joinSellerProducts($bySeller, '', $criteria, $checkAvailableFrom);
        }
        $this->joinSellers();
        
        $this->joinShops();
        $this->joinShopCountry();
        $this->joinShopState();
        $this->joinBrands();
    }
    
    public function setBatchProductsCriteria( $splPriceForDate = '' )
    {
        $now = FatDate::nowInTimezone(FatApp::getConfig('CONF_TIMEZONE'), 'Y-m-d');
        if ('' == $splPriceForDate ) {
            $splPriceForDate = $now;
        }
        $this->joinProductToCategory();
        $this->joinSellers();
        $this->joinShops();
        $this->joinShopCountry();
        $this->joinShopState();
        $this->joinBrands();
        $this->doNotCalculateRecords();
        $this->doNotLimitRecords();
        /* groupby added, beacouse if same product is linked with multiple categories, then showing in repeat for each category[ */
        $this->addGroupBy('selprod_id'); 
        /* ] */
        $this->joinTable(
            SellerProduct::DB_TBL_SELLER_PROD_SPCL_PRICE, 'LEFT OUTER JOIN',
            'splprice_selprod_id = selprod_id AND \'' . $splPriceForDate . '\' BETWEEN splprice_start_date AND splprice_end_date'
        );
    }

    /* Only used for product listing page for Home page, categories or search page*/
    public function joinForPrice( $splPriceForDate = '' ,$criteria = array(),$checkAvailableFrom = true  ) 
    {
        if ($this->sellerProductsJoined) {
            trigger_error(Labels::getLabel('ERR_SellerProducts_can_be_joined_only_once.', $this->commonLangId), E_USER_ERROR);
        }
        $this->sellerProductsJoined = true;

        $now = FatDate::nowInTimezone(FatApp::getConfig('CONF_TIMEZONE'), 'Y-m-d');
        if ('' == $splPriceForDate ) {
            $splPriceForDate = $now;
        }
        
        $this->joinTable(SellerProduct::DB_TBL, 'LEFT OUTER JOIN', 'msellprod.selprod_product_id = p.product_id and selprod_deleted = '.applicationConstants::NO.' and selprod_active = '.applicationConstants::ACTIVE, 'msellprod');
        if(isset($criteria['optionvalue']) && $criteria['optionvalue'] !='') { 
            $this->addOptionCondition($criteria['optionvalue']);
        }
        
        if(isset($criteria['collection_product_id']) && $criteria['collection_product_id'] >0) {
            $this->joinTable(
                Collections::DB_TBL_COLLECTION_TO_SELPROD, 'INNER JOIN',
                Collections::DB_TBL_COLLECTION_TO_SELPROD_PREFIX.'selprod_id = selprod_id and '.Collections::DB_TBL_COLLECTION_TO_SELPROD_PREFIX.'collection_id = '.$criteria['collection_product_id']
            );    
        }
        
        if($this->langId ) {
            $this->joinTable(SellerProduct::DB_LANG_TBL, 'LEFT OUTER JOIN', 'msellprod.selprod_id = sprods_l.selprodlang_selprod_id AND sprods_l.selprodlang_lang_id = '.$this->langId, 'sprods_l');
            $fields2 = array('selprod_title','selprod_warranty','selprod_return_policy','sprods_l.selprod_comments as selprodComments',);
        }    
        $this->joinTable(SellerProduct::DB_TBL_SELLER_PROD_SPCL_PRICE, 'LEFT OUTER JOIN', 'msplpric.splprice_selprod_id = msellprod.selprod_id AND \'' . $splPriceForDate . '\' BETWEEN msplpric.splprice_start_date AND msplpric.splprice_end_date AND msplpric.splprice_price < msellprod.selprod_price', 'msplpric');
        if(isset($criteria['optionvalue']) && $criteria['optionvalue'] !='') { 
            $this->addOptionCondition($criteria['optionvalue']);
        } 
        
        $srch = new SearchBase(SellerProduct::DB_TBL, 'sprods');
        
        if(isset($criteria['collection_product_id']) && $criteria['collection_product_id'] >0) {
            $srch->joinTable(
                Collections::DB_TBL_COLLECTION_TO_SELPROD, 'INNER JOIN',
                Collections::DB_TBL_COLLECTION_TO_SELPROD_PREFIX.'selprod_id = selprod_id and '.Collections::DB_TBL_COLLECTION_TO_SELPROD_PREFIX.'collection_id = '.$criteria['collection_product_id']
            );    
        }
        
        $shopCondition = '';
        
        if(array_key_exists('shop_id', $criteria) && $criteria['shop_id'] > 0) { 
            $shopId = FatUtility::int($criteria['shop_id']);
            $shopCondition = ' and shop_id = '.FatApp::getDb()->quoteVariable($shopId); 
        }    
                
        $srch->joinTable(Product::DB_TBL, 'INNER JOIN', 'product_id = selprod_product_id');
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'selprod_user_id = user_id AND user_is_supplier = '.applicationConstants::YES);
        $srch->joinTable(User::DB_TBL_CRED, 'INNER JOIN', 'credential_user_id = user_id and credential_active = '.applicationConstants::ACTIVE.' and credential_verified = '.applicationConstants::YES);
        $srch->joinTable(Shop::DB_TBL, 'INNER JOIN', 'user_id = shop_user_id and shop_active = '.applicationConstants::YES.' AND shop_supplier_display_status = '.applicationConstants::YES . $shopCondition);
        $srch->joinTable(Countries::DB_TBL, 'INNER JOIN', 'shop_country_id = country_id and country_active = '.applicationConstants::YES);
        $srch->joinTable(States::DB_TBL, 'INNER JOIN', 'shop_state_id = state_id and state_active = '.applicationConstants::YES);
        $srch->joinTable(Brand::DB_TBL, 'INNER JOIN', 'product_brand_id = brand_id and brand_active = '.applicationConstants::YES.' and brand_deleted = '.applicationConstants::NO);
        $srch->joinTable(Product::DB_TBL_PRODUCT_TO_CATEGORY, 'LEFT OUTER JOIN', 'ptc_product_id = product_id');
        $srch->joinTable(ProductCategory::DB_TBL, 'INNER JOIN', 'prodcat_id = ptc_prodcat_id and prodcat_active = '.applicationConstants::YES.' and prodcat_deleted = '.applicationConstants::NO);
        $srch->addMultipleFields(array('selprod_product_id','MIN(IFNULL(splprice_price, selprod_price)) AS theprice','CASE WHEN splprice_selprod_id IS NULL THEN 0 ELSE 1 END AS special_price_found'));
        $srch->joinTable(
            SellerProduct::DB_TBL_SELLER_PROD_SPCL_PRICE, 'LEFT OUTER JOIN',
            'splprice_selprod_id = selprod_id AND \'' . $splPriceForDate . '\' BETWEEN splprice_start_date AND splprice_end_date and splprice_price < selprod_price'
        );
        $srch->addCondition('sprods.selprod_active', '=', applicationConstants::ACTIVE);
        $srch->addCondition('sprods.selprod_deleted', '=', applicationConstants::NO);
                
        if(isset($criteria['optionvalue']) && $criteria['optionvalue'] !='') { 
            $this->addOptionCondition($criteria['optionvalue'], $srch);
        } 
        
        if($checkAvailableFrom) {
            $srch->addCondition('sprods.selprod_available_from', '<=', $now);
        }
        $srch->doNotLimitRecords();
        $srch->doNotCalculateRecords();
        if(isset($criteria['collection_product_id']) && $criteria['collection_product_id'] >0) {
            $srch->addGroupBy('selprod_id');
        }else{
            $srch->addGroupBy('selprod_product_id');
        }    
        $tmpQry = $srch->getQuery();        
        
        if(!empty($criteria['keyword'])) {
            $this->joinTable('(' . $tmpQry . ')', 'INNER JOIN', '((pricetbl.selprod_product_id = msellprod.selprod_product_id AND (splprice_price = theprice OR selprod_price = theprice)) or (selprod_title LIKE '.FatApp::getDb()->quoteVariable('%'.$criteria['keyword'].'%').'))', 'pricetbl');
        }else{
            $this->joinTable('(' . $tmpQry . ')', 'INNER JOIN', 'pricetbl.selprod_product_id = msellprod.selprod_product_id AND (splprice_price = theprice OR selprod_price = theprice)', 'pricetbl');
        }
        /* $this->joinTable('(' . $tmpQry . ')', 'INNER JOIN', 'pricetbl.selprod_product_id = msellprod.selprod_product_id AND (splprice_price = theprice OR selprod_price = theprice)', 'pricetbl'); */ 
        
    }

    public function joinSellerProducts( $bySeller = 0, $splPriceForDate = '', $criteria = array(), $checkAvailableFrom = true  ) 
    {
        if ($this->sellerProductsJoined) {
            trigger_error(Labels::getLabel('ERR_SellerProducts_can_be_joined_only_once.', $this->commonLangId), E_USER_ERROR);
        }
        $this->sellerProductsJoined = true;

        $now = FatDate::nowInTimezone(FatApp::getConfig('CONF_TIMEZONE'), 'Y-m-d');
        if ('' == $splPriceForDate ) {
            $splPriceForDate = $now;
        }
        $bySeller = FatUtility::int($bySeller);
        $srch = new SearchBase(SellerProduct::DB_TBL, 'sprods');

        $fields2 = array();
        if($this->langId ) {
            $srch->joinTable(SellerProduct::DB_LANG_TBL, 'LEFT OUTER JOIN', 'sprods.selprod_id = sprods_l.selprodlang_selprod_id AND sprods_l.selprodlang_lang_id = '.$this->langId, 'sprods_l');
            $fields2 = array('selprod_title','selprod_warranty','selprod_return_policy','sprods_l.selprod_comments as selprodComments',);
        }

        $srch->joinTable(
            SellerProduct::DB_TBL_SELLER_PROD_SPCL_PRICE, 'LEFT OUTER JOIN',
            'm.splprice_selprod_id = selprod_id AND \'' . $splPriceForDate . '\' BETWEEN m.splprice_start_date AND m.splprice_end_date', 'm'
        );

        $srch->joinTable(
            SellerProduct::DB_TBL_SELLER_PROD_SPCL_PRICE, 'LEFT OUTER JOIN',
            's.splprice_selprod_id = selprod_id AND s.splprice_price < m.splprice_price
             AND \'' . $splPriceForDate . '\' BETWEEN s.splprice_start_date AND s.splprice_end_date', 's'
        );

        $srch->addCondition('s.splprice_selprod_id', 'IS', 'mysql_func_NULL', 'AND', true);

        $srch->addCondition('selprod_active', '=', applicationConstants::ACTIVE);
        $srch->addCondition('selprod_deleted', '=', applicationConstants::NO);
        if($checkAvailableFrom) {
            $srch->addCondition('selprod_available_from', '<=', $now);
        }

        $fields1 = array('sprods.*', 'm.*',
        'CASE WHEN m.splprice_selprod_id IS NULL THEN 0 ELSE 1 END AS special_price_found',
        'IFNULL(m.splprice_price, selprod_price) AS theprice');
        $srch->addMultipleFields(array_merge($fields1, $fields2));

        $srch->doNotLimitRecords();
        $srch->doNotCalculateRecords();

        $this->joinTable('(' . $srch->getQuery() . ')', 'LEFT OUTER JOIN', 'p.product_id = pricetbl.selprod_product_id', 'pricetbl');
        if (0 < $bySeller) {
            $this->addCondition('selprod_user_id', '=', $bySeller);
        } else {
            $this->addCondition('selprod_user_id', '>', 0);
        }
    }

    /* public function joinProductVariantOptions(){
    $this->joinTable( SellerProduct::DB_TBL_SELLER_PROD_OPTIONS, 'LEFT OUTER JOIN', 'pricetbl.selprod_id = tspo.selprodoption_selprod_id', 'tspo');
    $this->joinTable( OptionValue::DB_TBL, 'LEFT OUTER JOIN', 'tspo.selprodoption_optionvalue_id = opval.optionvalue_id', 'opval' );
    $this->joinTable( Option::DB_TBL, 'LEFT OUTER JOIN', 'opval.optionvalue_option_id = op.option_id', 'op' );
    $this->addGroupBy('tspo.selprodoption_selprod_id');

    // $this->addMultipleFields(array('GROUP_CONCAT( selprodoption_option_id ) as option_ids', 'GROUP_CONCAT( selprodoption_optionvalue_id ) as option_value_ids'));
    // 'GROUP_CONCAT( option_name ) as option_names', 'GROUP_CONCAT( optionvalue_name ) as option_value_names'

    if( $this->langId ){
    $this->joinTable( Option::DB_TBL.'_lang', 'LEFT OUTER JOIN', 'op.option_id = op_l.optionlang_option_id AND op_l.optionlang_lang_id = '. $this->langId, 'op_l' );
    $this->joinTable( OptionValue::DB_TBL.'_lang', 'LEFT OUTER JOIN', 'opval.optionvalue_id = opval_l.optionvaluelang_optionvalue_id AND opval_l.optionvaluelang_lang_id = '. $this->langId, 'opval_l' );
    }
    } */

    public function joinSellers()
    {
        $this->sellerUserJoined = true;
        $this->joinTable(User::DB_TBL, 'INNER JOIN', 'selprod_user_id = seller_user.user_id and seller_user.user_is_supplier = '.applicationConstants::YES.' AND seller_user.user_deleted = '.applicationConstants::NO, 'seller_user');
        $this->joinTable(User::DB_TBL_CRED, 'INNER JOIN', 'credential_user_id = seller_user.user_id and credential_active = '.applicationConstants::ACTIVE.' and credential_verified = '.applicationConstants::YES, 'seller_user_cred');        
    }

    public function joinProductShippedBySeller($sellerId = 0)
    {
        $sellerId = FatUtility::int($sellerId);
        $this->joinTable(Product::DB_PRODUCT_SHIPPED_BY_SELLER, 'LEFT OUTER JOIN', 'psbs.psbs_product_id = p.product_id and psbs.psbs_user_id = '.$sellerId, 'psbs');
    }
    
    public function joinProductShippedBy()
    {
    
        $this->joinTable(Product::DB_PRODUCT_SHIPPED_BY_SELLER, 'LEFT OUTER JOIN', 'psbs.psbs_product_id = p.product_id and psbs.psbs_user_id = selprod_user_id', 'psbs');
    }
    public function joinProductFreeShipping()
    {
    
        $this->joinTable(Product::DB_TBL_PRODUCT_SHIPPING, 'LEFT OUTER JOIN', 'ps.ps_product_id = p.product_id and ps.ps_user_id = selprod_user_id', 'ps');
    }
        
    public function joinShops( $langId = 0, $isActive = true, $isDisplayStatus = true )
    {
        if(!$this->sellerUserJoined ) {
            trigger_error(Labels::getLabel('ERR_joinShops_cannot_be_joined,_unless_joinSellers_is_not_applied.', $this->commonLangId), E_USER_ERROR);
        }
        $langId = FatUtility::int($langId);
        if ($this->langId && 1 > $langId) {
            $langId = $this->langId;
        }
                
        $activeShopCondition = '';
        if($isActive ) {
            $activeShopCondition = ' and shop.shop_active = '.applicationConstants::ACTIVE;
            $this->addCondition('shop.shop_active', '=', applicationConstants::ACTIVE);
        }
        
        $shopDisplayStatus = '';
        if($isDisplayStatus ) {
            $shopDisplayStatus = ' and shop.shop_supplier_display_status = '.applicationConstants::ON;
            $this->addCondition('shop.shop_supplier_display_status', '=', applicationConstants::ON);
        }
        
        $this->joinTable(Shop::DB_TBL, 'INNER JOIN', 'seller_user.user_id = shop.shop_user_id '.$activeShopCondition.' '.$shopDisplayStatus, 'shop');

        if($langId ) {
            $this->joinTable(Shop::DB_TBL_LANG, 'LEFT OUTER JOIN', 'shop.shop_id = s_l.shoplang_shop_id AND shoplang_lang_id = '. $langId, 's_l');
        }
    }

    public function joinShopCountry( $langId = 0, $isActive = true )
    {
        $langId = FatUtility::int($langId);
        if ($this->langId && 1 > $langId) {
            $langId = $this->langId;
        }
        
        $countryActiveCondition = '';
        if($isActive ) {
            $countryActiveCondition = 'and shop_country.country_active = '.applicationConstants::ACTIVE;
            $this->addCondition('shop_country.country_active', '=', applicationConstants::ACTIVE);
        }
        
        $this->joinTable(Countries::DB_TBL, 'INNER JOIN', 'shop.shop_country_id = shop_country.country_id '.$countryActiveCondition, 'shop_country');
        
        if($langId ) {
            $this->joinTable(Countries::DB_TBL_LANG, 'LEFT OUTER JOIN', 'shop_country.country_id = shop_country_l.countrylang_country_id AND shop_country_l.countrylang_lang_id = '.$langId, 'shop_country_l');
        }        
    }

    public function joinShopState( $langId = 0, $isActive = true )
    {
        $langId = FatUtility::int($langId);
        if ($this->langId && 1 > $langId) {
            $langId = $this->langId;
        }
        
        $stateActiveCondition = '';
        if($isActive ) {
            $stateActiveCondition = 'and shop_state.state_active = '.applicationConstants::ACTIVE;
            $this->addCondition('shop_state.state_active', '=', applicationConstants::ACTIVE);
        }
        
        $this->joinTable(States::DB_TBL, 'INNER JOIN', 'shop.shop_state_id = shop_state.state_id '.$stateActiveCondition, 'shop_state');

        if($langId ) {
            $this->joinTable(States::DB_TBL_LANG, 'LEFT OUTER JOIN', 'shop_state.state_id = shop_state_l.statelang_state_id AND shop_state_l.statelang_lang_id = '.$langId, 'shop_state_l');
        }
        
    }

    public function joinBrands( $langId = 0, $isActive = true, $isDeleted = true, $useInnerJoin = true )
    {
        $langId = FatUtility::int($langId);
        if ($this->langId && 1 > $langId) {
            $langId = $this->langId;
        }
        $join = ($useInnerJoin) ? 'INNER JOIN' : 'LEFT OUTER JOIN';
        
        $brandActiveCondition = '';
        if($isActive ) {
            $brandActiveCondition = 'and brand.brand_active = '.applicationConstants::ACTIVE;
            $this->addCondition('brand.brand_active', '=', applicationConstants::ACTIVE);
        }
        
        $brandDeletedCondition = '';
        if($isDeleted ) {
            $brandDeletedCondition = 'and brand.brand_deleted = '.applicationConstants::NO;
            $this->addCondition('brand.brand_deleted', '=', applicationConstants::NO);
        }
        
        $this->joinTable(Brand::DB_TBL, $join, 'p.product_brand_id = brand.brand_id '.$brandActiveCondition.' '.$brandDeletedCondition, 'brand');        

        if($langId ) {
            $this->joinTable(Brand::DB_LANG_TBL, 'LEFT OUTER JOIN', 'brand.brand_id = tb_l.brandlang_brand_id AND brandlang_lang_id = '.$langId, 'tb_l');
        }
    }

    public function joinProductToCategory( $langId = 0, $isActive = true, $isDeleted = true, $useInnerJoin = true )
    {
        $langId = FatUtility::int($langId);
        if ($this->langId && 1 > $langId) {
            $langId = $this->langId;
        }
        $join = ($useInnerJoin) ? 'INNER JOIN' : 'LEFT OUTER JOIN';
        $this->joinTable(Product::DB_TBL_PRODUCT_TO_CATEGORY, $join, 'ptc.ptc_product_id = p.product_id', 'ptc');
                
        $categoryActiveCondition = '';
        if($isActive) {
            $categoryActiveCondition = 'and c.prodcat_active = '.applicationConstants::ACTIVE;
            $this->addCondition('c.prodcat_active', '=', applicationConstants::ACTIVE);
        }
        
        $categoryDeletedCondition = '';
        if($isDeleted) {
            $categoryDeletedCondition = 'and c.prodcat_deleted = '.applicationConstants::NO;
            $this->addCondition('c.prodcat_deleted', '=', applicationConstants::NO);
        }
        
        $this->joinTable(ProductCategory::DB_TBL, $join, 'c.prodcat_id = ptc.ptc_prodcat_id '.$categoryActiveCondition.' '.$categoryDeletedCondition, 'c');

        if($langId ) {
            $this->joinTable(ProductCategory::DB_LANG_TBL, 'LEFT OUTER JOIN', 'c_l.prodcatlang_prodcat_id = c.prodcat_id AND prodcatlang_lang_id = '.$langId, 'c_l');
        }
    }
    
    public function joinFavouriteProducts($user_id )
    {
        
        $this->joinTable(Product::DB_TBL_PRODUCT_FAVORITE, 'LEFT OUTER JOIN', 'ufp.ufp_selprod_id = selprod_id and ufp.ufp_user_id = '.$user_id, 'ufp');
    }
    
    public function joinUserWishListProducts($user_id)
    {
        
        $wislistPSrchObj = new UserWishListProductSearch();
        $wislistPSrchObj->joinWishLists();
        $wislistPSrchObj->doNotCalculateRecords();
        $wislistPSrchObj->addCondition('uwlist_user_id', '=', $user_id);
        $wishListSubQuery = $wislistPSrchObj->getQuery();

        $this->joinTable('(' . $wishListSubQuery . ')', 'LEFT OUTER JOIN', 'uwlp.uwlp_selprod_id = selprod_id', 'uwlp');
    }
    
    public function addCategoryCondition($category)
    {
        
        if(is_numeric($category) ) {
            $category_id = FatUtility::int($category);
            if(!$category_id ) {
                return;
            }
            /* $this->addCondition('GETCATCODE(`prodcat_id`)', 'LIKE', '%' . str_pad($category_id, 6, '0', STR_PAD_LEFT ) . '%', 'AND', true); */
            $this->addCondition('c.prodcat_code', 'LIKE', '%' . str_pad($category_id, 6, '0', STR_PAD_LEFT) . '%', 'AND', true);
        }else{
            /* $category = explode(",", $category);
            $category = FatUtility::int($category);
            $this->addCondition('prodcat_id', 'IN', $category ); */
            $categories = explode(",", $category);
            $condition= '(';
            foreach($categories as $catId){
                $condition .= " c.prodcat_code LIKE '%".str_pad($catId, 6, '0', STR_PAD_LEFT) ."%' OR";
                
            }
            $condition = substr($condition, 0, -2);
            $condition .= ')';
            $this->addDirectCondition($condition); 
            
        }
    }
    
    public function addProductShippedBySellerCondition($sellerId = 0)
    {
        $sellerId = FatUtility::int($sellerId);    
        $this->addDirectCondition(" ( isnull(psbs.psbs_user_id) or  psbs.psbs_user_id ='".$sellerId."')");
    }

    public function addKeywordSearch( $keyword )
    {

        if($keyword == '' ) {
            return;
        }
        
        $cnd = $this->addCondition('product_isbn', 'LIKE', '%' . $keyword . '%');
        $cnd->attachCondition('product_upc', 'LIKE', '%' . $keyword . '%');
        $cnd->attachCondition('selprod_title', 'LIKE', '%' . $keyword . '%');
        $cnd->attachCondition('product_name', 'LIKE', '%' . $keyword . '%');
        $cnd->attachCondition('brand_name', 'LIKE', '%' . $keyword . '%');
        $cnd->attachCondition('prodcat_name', 'LIKE', '%' . $keyword . '%'); 
        $cnd->attachCondition('product_short_description', 'LIKE', '%' . $keyword . '%');
        $cnd->attachCondition('product_description', 'LIKE', '%' . $keyword . '%');

        $arr = explode(' ', $keyword);
        $arr_keywords = array();
        foreach ($arr as $value) {
            $value = trim($value);
            if (strlen($value) < 3) {
                continue;
            }
            $arr_keywords[] = $value;
        }

        foreach ( $arr_keywords as $value) {
            $cnd->attachCondition('product_tags_string', 'LIKE', '%' . $value . '%');
            $cnd->attachCondition('selprod_title', 'LIKE', '%' . $value . '%');
            $cnd->attachCondition('product_name', 'LIKE', '%' . $value . '%');
            $cnd->attachCondition('brand_name', 'LIKE', '%' . $value . '%');
            $cnd->attachCondition('prodcat_name', 'LIKE', '%' . $value . '%'); 
        }

        $strKeyword = FatApp::getDb()->quoteVariable('%' . $keyword . '%');
        $this->addFld(
            "
		IF(product_isbn LIKE $strKeyword OR product_upc LIKE $strKeyword, 15, 0)
		+ IF(selprod_title LIKE $strKeyword, 4, 0)
		+ IF(product_name LIKE $strKeyword, 4, 0)
		+ IF(product_tags_string LIKE $strKeyword, 4, 0)
		AS keyword_relevancy"
        );

        /* search in product tags[ */
        /* $srchTag = new SearchBase( Product::DB_PRODUCT_TO_TAG, 'ptt' );
        $srchTag->joinTable( Tag::DB_TBL, 'LEFT OUTER JOIN', 'ptt.ptt_tag_id = t.tag_id', 't' );
        $srchTag->joinTable( Tag::DB_TBL_LANG, 'LEFT OUTER JOIN', 't.tag_id = t_l.taglang_tag_id AND taglang_lang_id = '. CommonHelper::getLangId(), 't_l' );
        $srchTag->doNotCalculateRecords();
        $srchTag->doNotLimitRecords();
        $srchTag->addGroupBy('ptt.ptt_product_id');
        $srchTag->addMultipleFields( array('ptt.ptt_product_id','GROUP_CONCAT(IFNULL( t_l.`tag_name`, t.tag_identifier )) as product_tags') );
        $qry_tag_products = $srchTag->getQuery();

        $this->joinTable('(' . $qry_tag_products . ')', 'LEFT OUTER JOIN', 'product_id = tqtp.ptt_product_id', 'tqtp'); */
        /* ] */
        /* $keyword = urldecode( $keyword );
        $arr_keywords = explode( " ", $keyword );
        $arr_columns = array(
        'product_name',
        'selprod_title',
        'product_model',
        'prodcat_name',
        'tqtp.product_tags',
        'brand_name',
        'product_upc',
        'product_isbn',
        );

        $strKeys = "";
        foreach($arr_columns as $column){
        foreach( $arr_keywords as $keyword ){
        $keyword = mysqli_real_escape_string( FatApp::getDb()->getConnectionObject(), $keyword);
        $strKeys .= (($strKeys=="")?"":" + ")." (case when INSTR(".$column.",'".trim($keyword)."')>0 then 1 else 0 END) ";
        }
        }
        $this->addDirectCondition( $strKeys );
        $this->addFld( $strKeys.' as Rank' ); */

        
        
        //$this->addOrder( 'Rank', 'desc' );
        /* $cnd = $this->addCondition('product_name', 'like', '%' . $keyword . '%');
        $cnd->attachCondition('selprod_title', 'like', '%' . $keyword . '%','OR');
        $cnd->attachCondition('product_model', 'like', '%' . $keyword . '%','OR');
        $cnd->attachCondition('prodcat_name', 'like', '%' . $keyword . '%','OR');
        $cnd->attachCondition('brand_name', 'like', '%' . $keyword . '%','OR'); */
    }
    
    public function addProductIdCondition( $product_id )
    {
        if(!$product_id ) {
            trigger_error(Labels::getLabel('ERR_Product_Id_not_Passed!', $this->commonLangId), E_USER_ERROR);
        }
        $this->addCondition('product_id', '=', $product_id);
    }

    public function addBrandCondition($brand)
    {
        if(is_numeric($brand) ) {
            $brand = FatUtility::int($brand);
            $this->addCondition('brand_id', '=', $brand);
        } else {
            $brand = explode(",", $brand);
            $brand = FatUtility::int($brand);
            $this->addCondition('brand_id', 'IN', $brand);
        }
    }

    public function addOptionCondition($optionValue, $obj = false , $alias = '')
    {
        if($obj === false) {
            $obj = $this;
        }
        
        if($alias!='') {
            $alias.= '.';
        }
        
        if(strpos($optionValue, ",")=== false ) {
            if(strpos($optionValue, "_")=== false ) {
                $opVal = $optionValue;
            }else{                    
                $opVal = substr($optionValue, strpos($optionValue, "_") + 1);
            }
            
            $opVal = FatUtility::int($opVal);            
            $obj->addDirectCondition(" (".$alias."selprod_code like '%_".$opVal."_%' or ".$alias."selprod_code like '%_".$opVal."') ");            
        }else{
            $optionValueArr  = explode(",", $optionValue);
            sort($optionValueArr);
            $opValArr = array();
            foreach($optionValueArr as $val){
                $opVal =  explode("_", $val);
                $opValArr[$opVal[0]][] = $opVal[1];
            }
            $str = '( ';
            $orCnd = '';
            $andCnd = '';
            foreach($opValArr as $row){
                $str.= $andCnd;
                foreach($row as $val){
                    if(1 > FatUtility::int($val)) { continue;
                    }
                    $str.= $orCnd." ".$alias."selprod_code like '%_".$val."_%' or ".$alias."selprod_code like '%_".$val."'";
                    $orCnd = 'or';
                }
                $orCnd = "";
                $andCnd = ") and (";
            }
            $str.= " )";
            $obj->addDirectCondition($str);
        }        
    }
    
    /* public function addUPCCondition($upc){
    $this->addCondition('product_upc', 'like', $upc );
    }

    public function addISBNCondition($isbn){
    $this->addCondition('product_isbn', 'like', $isbn );
    } */

    public function addShopIdCondition($shop_id)
    {
        $shop_id = FatUtility::int($shop_id);
        if(!$shop_id ) {
            trigger_error(Labels::getLabel('ERR_Shop_Id_not_Passed', $this->commonLangId), E_USER_ERROR);
        }
        $this->addCondition('shop_id', '=', $shop_id);
    }
    
    public function addCollectionIdCondition($collection_id)
    {
        $collection_id = FatUtility::int($collection_id);
        if(!$collection_id ) {
            trigger_error(Labels::getLabel('ERR_Collection_Id_not_Passed', $this->commonLangId), E_USER_ERROR);
            
        }
        
        $this->joinTable(ShopCollection::DB_TBL_SHOP_COLLECTION_PRODUCTS, 'INNER JOIN', ShopCollection::DB_SELLER_PRODUCTS_PREFIX.'id = '.ShopCollection::DB_TBL_SHOP_COLLECTION_PRODUCTS_PREFIX.'selprod_id and '.ShopCollection::DB_TBL_SHOP_COLLECTION_PRODUCTS_PREFIX . 'scollection_id = '.$collection_id);
        //return $srch;
    }


    public function addConditionCondition($condition)
    {
        if(is_numeric($condition) ) {
            $condition = FatUtility::int($condition);
            $this->addCondition('selprod_condition', '=', $condition);
        } else {
            $condition = explode(",", $condition);
            $condition = FatUtility::int($condition);    
            $this->addCondition('selprod_condition', 'IN', $condition);
        }
    }

    public function addMoreSellerCriteria( $productCode, $sellerId = 0 )
    {
        $sellerId = FatUtility::int($sellerId);
        if($productCode == '' ) {
            trigger_error(Labels::getLabel('ERR_Invalid_Argument_Passed', $this->commonLangId), E_USER_ERROR);
        }

        //$this->setDefinedCriteria();
        $this->joinSellerProducts();
        $this->joinSellers();
        $this->joinShops();
        $this->joinShopCountry();
        $this->joinShopState();
        $this->joinBrands();
        $this->    joinSellerSubscription();
        $this->    addSubscriptionValidCondition();
        $this->joinProductToCategory();
        $this->doNotCalculateRecords();
        $this->doNotLimitRecords();
        $this->addCondition('selprod_deleted', '=', applicationConstants::NO);
        if($sellerId > 0) {
            $this->addCondition('selprod_user_id', '!=', $sellerId);
        }
        $this->addCondition('selprod_code', '=', $productCode);
    }

    public function excludeOutOfStockProducts()
    {
        $this->addCondition('selprod_stock', '>', 0);
    }

    public function addAttributesCriteria( $product_id, $lang_id )
    {
        $product_id = FatUtility::int($product_id);
        $lang_id = FatUtility::int($lang_id);
        if(!$product_id || !$lang_id ) {
            trigger_error(Labels::getLabel('ERR_Invalid_Argument_Passed', $this->commonLangId), E_USER_ERROR);
        }

        $this->joinTable(AttributeGroup::DB_TBL_ATTRIBUTES, 'INNER JOIN', 'p.product_attrgrp_id = attr.attr_attrgrp_id', 'attr');
        $this->joinTable(AttributeGroup::DB_TBL_ATTRIBUTES.'_lang', 'LEFT OUTER JOIN', 'attr.attr_id = attr_l.attrlang_attr_id AND attr_l.attrlang_lang_id = '. $lang_id, 'attr_l');
        $this->addProductIdCondition($product_id);
    }
    
    public function joinProductRating()
    {
        $selProdReviewObj = new SelProdReviewSearch();
        $selProdReviewObj->joinSellerProducts();
        $selProdReviewObj->joinSelProdRating();
        $selProdReviewObj->addCondition('sprating_rating_type', '=', SelProdRating::TYPE_PRODUCT);
        $selProdReviewObj->doNotCalculateRecords();
        $selProdReviewObj->doNotLimitRecords();
        $selProdReviewObj->addGroupBy('spr.spreview_product_id');
        $selProdReviewObj->addCondition('spr.spreview_status', '=', SelProdReview::STATUS_APPROVED);
        $selProdReviewObj->addMultipleFields(array('spr.spreview_selprod_id',"ROUND(AVG(sprating_rating),2) as prod_rating","count(spreview_id) as totReviews"));
        $selProdRviewSubQuery = $selProdReviewObj->getQuery();
        $this->joinTable('(' . $selProdRviewSubQuery . ')', 'LEFT OUTER JOIN', 'sq_sprating.spreview_selprod_id = selprod_id', 'sq_sprating');
    }
    
    public function joinSellerOrder($langId = 0)
    {
        if(!$this->sellerUserJoined) {
            trigger_error(Labels::getLabel('ERR_Seller_must_joined.', CommonHelper::getLangId()), E_USER_ERROR);
        }
        $this->sellerSubscriptionOrderJoined = true;
        if(FatApp::getConfig('CONF_ENABLE_SELLER_SUBSCRIPTION_MODULE', FatUtility::VAR_INT, 0)) { 
            $this->joinTable(Orders::DB_TBL, 'INNER JOIN', 'o.order_user_id=seller_user.user_id AND o.order_type='.ORDERS::ORDER_SUBSCRIPTION.' AND o.order_is_paid =1', 'o'); 
        
            
        }
    }
    
    public function joinSellerOrderSubscription( $langId = 0 )
    {
        $langId = FatUtility::int($langId);
        
        if(!$this->sellerSubscriptionOrderJoined) {
            trigger_error(Labels::getLabel('ERR_Seller_Subscription_Order_must_joined.', $this->commonLangId), E_USER_ERROR);
        }
        if(FatApp::getConfig('CONF_ENABLE_SELLER_SUBSCRIPTION_MODULE')) {
            $this->joinTable(OrderSubscription::DB_TBL, 'INNER JOIN', 'o.order_id = oss.ossubs_order_id and oss.ossubs_status_id='.FatApp::getConfig('CONF_DEFAULT_SUBSCRIPTION_PAID_ORDER_STATUS'), 'oss'); 
            if($langId > 0 ) {
                $this->joinTable(OrderSubscription::DB_TBL_LANG, 'LEFT OUTER JOIN', 'oss.ossubs_id = ossl.'.OrderSubscription::DB_TBL_LANG_PREFIX.'ossubs_id AND ossubslang_lang_id = ' . $langId, 'ossl');
            }
        }
    }
    
    public function joinSellerSubscription($langId= 0 ,$joinSeller = false)
    {
        $langId = FatUtility::int($langId);
        if ($this->langId && 1 > $langId) {
            $langId = $this->langId;
        }
            
        if($joinSeller) {
            $this->joinSellers();
        }
        $this->joinSellerOrder();
        $this->joinSellerOrderSubscription($langId);
        
        //$this->addSubscriptionValidCondition();
    }
    
    public function addSubscriptionValidCondition($date='')
    {
        if($date =='') {
            $date = date("Y-m-d");
        }
        if(FatApp::getConfig('CONF_ENABLE_SELLER_SUBSCRIPTION_MODULE')) { 
            $this->addCondition('oss.ossubs_till_date', '>=', $date);
            $this->addCondition('ossubs_status_id', 'IN ', Orders::getActiveSubscriptionStatusArr());
        }
    }
    
    /* public function joinSellerProductOptionsWithSelProdCode($langId = 0){
    $langId = FatUtility::int( $langId );
    if ($this->langId && 1 > $langId) {
            $langId = $this->langId;
        }
		
    $this->joinTable( OptionValue::DB_TBL, 'LEFT OUTER JOIN', "selprod_code LIKE CONCAT('%_', ov.optionvalue_id)" , 'ov' );
    $this->joinTable( Option::DB_TBL, 'INNER JOIN', 'spo.option_id = ov.optionvalue_option_id', 'spo' );
		
    if( $langId ){
    $this->joinTable( OptionValue::DB_TBL . '_lang', 'LEFT OUTER JOIN', 'ov_lang.optionvaluelang_optionvalue_id = ov.optionvalue_id AND ov_lang.optionvaluelang_lang_id = '.$langId, 'ov_lang' );
			
    $this->joinTable( Option::DB_TBL . '_lang', 'LEFT OUTER JOIN', 'spo.option_id = spo_lang.optionlang_option_id AND spo_lang.optionlang_lang_id = '.$langId, 'spo_lang' );
    }
    } */
    
}
