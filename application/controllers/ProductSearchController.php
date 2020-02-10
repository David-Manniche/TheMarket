<?php

class ProductSearchController extends MyAppController
{
	public function __construct($action)
    {
        parent::__construct($action);
    }

	public function search()
    {
        $this->productsData(__FUNCTION__);
    }

	public function filters()
    {
		$db = FatApp::getDb();
        $headerFormParamsAssocArr = FilterHelper::getParamsAssocArr();
		$categoryId = 0;
        if (array_key_exists('category', $headerFormParamsAssocArr)) {
            $categoryId = FatUtility::int($headerFormParamsAssocArr['category']);
        }
		$keyword = '';
        $langIdForKeywordSeach = 0;
        if (array_key_exists('keyword', $headerFormParamsAssocArr) && !empty($headerFormParamsAssocArr['keyword'])) {
            $keyword = $headerFormParamsAssocArr['keyword'];
            $langIdForKeywordSeach = $this->siteLangId;
        }

		/* Brand Filters Data[ */
			$brandsCheckedArr = FilterHelper::selectedBrands($headerFormParamsAssocArr);
			$brandWithAggregations = FullTextSearch::getSearchBrands($headerFormParamsAssocArr,$this->siteLangId);
			$brandsArr = $this->removeElasticSourceIndex($brandWithAggregations['hits'],'brand');
        /* ] */
		
		/* Categories Data[ */
        $categoriesArr = array();
        if (empty($keyword)) {            
            //$categoriesArr =  FilterHelper::getCategories($this->siteLangId, $categoryId, $prodSrchObj, $cacheKey);
			
        }
        /* ] */
		
		/* Price Filters [ */
		
			unset($headerFormParamsAssocArr['doNotJoinSpecialPrice']);
			$priceArr = array();
			$priceInFilter = false;
			$priceArr['minPrice'] = $brandWithAggregations['aggregations']['min_price']['value'];
			$priceArr['maxPrice'] = $brandWithAggregations['aggregations']['max_price']['value'];
			
			$filterDefaultMinValue = $brandWithAggregations['aggregations']['min_price']['value'];
			$filterDefaultMaxValue = $brandWithAggregations['aggregations']['max_price']['value'];
			
			if ($this->siteCurrencyId != FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1) || (array_key_exists('currency_id', $headerFormParamsAssocArr) && $headerFormParamsAssocArr['currency_id'] != $this->siteCurrencyId)) {
				$filterDefaultMinValue = CommonHelper::displayMoneyFormat($brandWithAggregations['aggregations']['min_price']['value'], false, false, false);
				$filterDefaultMaxValue = CommonHelper::displayMoneyFormat($brandWithAggregations['aggregations']['min_price']['value'], false, false, false);
				$priceArr['minPrice'] = $filterDefaultMinValue;
				$priceArr['maxPrice'] = $filterDefaultMaxValue;
			}
			
			if (array_key_exists('price-min-range', $headerFormParamsAssocArr) && array_key_exists('price-max-range', $headerFormParamsAssocArr)) {
				$priceArr['minPrice'] = $headerFormParamsAssocArr['price-min-range'];
				$priceArr['maxPrice'] = $headerFormParamsAssocArr['price-max-range'];
				$priceInFilter = true;
			}
			if (array_key_exists('currency_id', $headerFormParamsAssocArr) && $headerFormParamsAssocArr['currency_id'] != $this->siteCurrencyId) {
				$priceArr['minPrice'] = CommonHelper::convertExistingToOtherCurrency($headerFormParamsAssocArr['currency_id'], $headerFormParamsAssocArr['price-min-range'], $this->siteCurrencyId, false);
				$priceArr['maxPrice'] = CommonHelper::convertExistingToOtherCurrency($headerFormParamsAssocArr['currency_id'], $headerFormParamsAssocArr['price-max-range'], $this->siteCurrencyId, false);
			}
			
		/* Price Filters ] */
		
		

		$productFiltersArr = array();
		$shopCatFilters    = array();
		$prodcatArr        = array();
		$optionValueCheckedArr = array();
		$conditionsArr  = array();
		$conditionsCheckedArr  = array();
		$options = array();
		
		$availabilityArr = array();
		$availability = array();

        $this->set('productFiltersArr', $productFiltersArr);
        $this->set('headerFormParamsAssocArr', $headerFormParamsAssocArr);
        $this->set('categoriesArr', $categoriesArr);
        $this->set('shopCatFilters', $shopCatFilters);
        $this->set('prodcatArr', $prodcatArr);
        // $this->set('productCategories',$productCategories);
        $this->set('brandsArr', $brandsArr);
        $this->set('brandsCheckedArr', $brandsCheckedArr);
        $this->set('optionValueCheckedArr', $optionValueCheckedArr);
        $this->set('conditionsArr', $conditionsArr);
        $this->set('conditionsCheckedArr', $conditionsCheckedArr);
        $this->set('options', $options);
        $this->set('priceArr', $priceArr);
        $this->set('priceInFilter', $priceInFilter);
        $this->set('filterDefaultMinValue', $filterDefaultMinValue);
        $this->set('filterDefaultMaxValue', $filterDefaultMaxValue);
        $this->set('availability', $availability);
        $availabilityArr = (true ===  MOBILE_APP_API_CALL) ? array_values($availabilityArr) : $availabilityArr;
        $this->set('availabilityArr', $availabilityArr);

        if (true ===  MOBILE_APP_API_CALL) {
            $this->_template->render();
        }

        echo $this->_template->render(false, false, 'productSearch/filters.php', true);
        exit;
	}

	private function productsData($method)
    {
        $db = FatApp::getDb();
        $get = Product::convertArrToSrchFiltersAssocArr(FatApp::getParameters());
        $includeKeywordRelevancy = false;
        $keyword = '';
        if (array_key_exists('keyword', $get)) {
            $includeKeywordRelevancy = true;
            $keyword = $get['keyword'];
        }

        $frm = $this->getProductSearchForm($includeKeywordRelevancy);

        $arr = array();

        switch ($method) {
            case 'index':
                $arr = array(
                    'pageTitle' => Labels::getLabel('LBL_All_PRODUCTS', $this->siteLangId),
                    'canonicalUrl' => CommonHelper::generateFullUrl('Products', 'index'),
                    'productSearchPageType' => SavedSearchProduct::PAGE_PRODUCT_INDEX,
                    'bannerListigUrl' => CommonHelper::generateFullUrl('Banner', 'allProducts'),
                );
                break;
            case 'search':
                $arr = array(
                    'pageTitle'=> Labels::getLabel('LBL_Search_results_for', $this->siteLangId),
                    'canonicalUrl'=>CommonHelper::generateFullUrl('Products', 'search'),
                    'productSearchPageType'=>SavedSearchProduct::PAGE_PRODUCT,
                    'bannerListigUrl'=>CommonHelper::generateFullUrl('Banner', 'searchListing'),
                    'keyword' => $keyword,
                );
                break;
            case 'featured':
                $arr = array(
                    'pageTitle' => Labels::getLabel('LBL_FEATURED_PRODUCTS', $this->siteLangId),
                    'canonicalUrl' => CommonHelper::generateFullUrl('Products', 'featured'),
                    'productSearchPageType' => SavedSearchProduct::PAGE_FEATURED_PRODUCT,
                    'bannerListigUrl' => CommonHelper::generateFullUrl('Banner', 'searchListing'),
                );
                $get['featured'] = 1;
                break;
        }

        $frm->fill($get);
		$get['join_price'] = 1;

        $data = $this->getListingData($get);

        $common = array(
            'frmProductSearch' => $frm,
            'recordId' => 0,
            'showBreadcrumb' => false
        );

        $data = array_merge($data, $common, $arr);
        if (FatUtility::isAjaxCall()) {
            $this->set('products', $data['products']);
            $this->set('page', $data['page']);
            $this->set('pageCount', $data['pageCount']);
            $this->set('postedData', $get);
            $this->set('recordCount', $data['recordCount']);
            $this->set('siteLangId', $this->siteLangId);
            echo $this->_template->render(false, false, 'productSearch/products-list.php', true);
            exit;
        }

        $this->set('data', $data);

        $this->includeProductPageJsCss();
        $this->_template->addJs('js/slick.min.js');
        $this->_template->render(true, true, 'productSearch/index.php');
    }

	private function removeElasticSourceIndex($dataValues,$filterKey=null)
	{
		$returnData = array();
		foreach($dataValues['hits'] as $key => $value)
		{
			if(empty($filterKey))
			{
				$returnData[$key] = $value['_source'];
			}
			else
			{
				$returnData[$key] = $value['_source'][$filterKey];
			}

		}
		return $returnData;
	}

	private function getListingData($get)
    {
		$categoryId = null;
		$category = array();
		$page = 1;
		if (array_key_exists('page', $get)) {
            $page = FatUtility::int($get['page']);
            if ($page < 2) {
                $page = 1;
            }
        }

		$response = FullTextSearch::search($get,1,$page);

		$total = FatUtility::int($response['total']['value']);

		$products = $this->removeElasticSourceIndex($response);

		$pageSize = FatApp::getConfig('CONF_ITEMS_PER_PAGE_CATALOG', FatUtility::VAR_INT, 10);

		if (array_key_exists('pageSize', $get))
		{
            $pageSize = FatUtility::int($get['pageSize']);
            if (0 >= $pageSize) {
                $pageSize = FatApp::getConfig('CONF_ITEMS_PER_PAGE_CATALOG', FatUtility::VAR_INT, 10);
            }
        }



		$pageCount = $this->totalPagesCount($total,$pageSize);

		$data = array(
            'products' => $products,
            'category' => $category,
            'categoryId' => $categoryId,
            'postedData' => $get,
            'page' => $page,
            'pageCount' => $pageCount,
            'pageSize' =>  $pageSize,
            'recordCount'=> $total,
            'siteLangId'=> $this->siteLangId
        );
		return $data;
	}

	private function totalPagesCount($total,$pageSize)
	{
		return ceil($total/$pageSize);
	}


}
