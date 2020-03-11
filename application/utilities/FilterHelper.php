<?php

class FilterHelper extends FatUtility
{
    public static function getParamsAssocArr()
    {
        $post = FatApp::getPostedData();
        $get = FatApp::getParameters();
        $headerFormParamsAssocArr = Product::convertArrToSrchFiltersAssocArr($get);
        return array_merge($headerFormParamsAssocArr, $post);
    }

    public static function getCacheKey($langId, $post)
    {
        $cacheKey = $langId;
               
        if (array_key_exists('category', $post)) {
            $cacheKey .= '-' . FatUtility::int($post['category']);
        }
        
        if (array_key_exists('shop_id', $post)) {
            $cacheKey .= '-' . $post['shop_id'];
        }
        
        if (array_key_exists('top_products', $post)) {
            $cacheKey .= '-tp';
        }
        
        if (array_key_exists('brand_id', $post)) {
            $cacheKey .= '-' . $post['brand_id'];
        }
       
        if (array_key_exists('featured', $post)) {
            $cacheKey .= '-f';
        }

        if (array_key_exists('keyword', $post) && !empty($post['keyword'])) {
            $cacheKey .= '-' . urlencode($post['keyword']);
        }
        
        return $cacheKey;
    }

    public static function selectedBrands($post)
    {
        if (array_key_exists('brand', $post)) {
            if (true === MOBILE_APP_API_CALL) {
                $post['brand'] = json_decode($post['brand'], true);
            }
            
            if (is_array($post['brand'])) {
                return $post['brand'];
            }

            return explode(',', $post['brand']);
        }
        return array();
    }

    public static function brands($prodSrchObj, $langId, $post, $doNotLimitRecord = false, $includePriority = false)
    {
        $brandId = 0;
        if (array_key_exists('brand_id', $post)) {
            $brandId = FatUtility::int($post['brand_id']);
        }

        $brandsCheckedArr = array();
        if (true == $includePriority) {
            $brandsCheckedArr = static::selectedBrands($post);
        }

        $brandSrch = clone $prodSrchObj;
        if (true == $doNotLimitRecord) {
            $brandSrch->doNotLimitRecords();
        } else {
            $pageSize = max(count($brandsCheckedArr), 10);
            $brandSrch->setPageSize($pageSize);
        }

        if (0 == $langId) {
            $brandSrch->joinBrandsLang(CommonHelper::getLangId());
        }
        $brandSrch->addGroupBy('brand.brand_id');
        $brandSrch->addMultipleFields(array( 'brand.brand_id', 'COALESCE(tb_l.brand_name,brand.brand_identifier) as brand_name'));
        if ($brandId) {
            $brandSrch->addCondition('brand_id', '=', $brandId);
            $brandsCheckedArr = array($brandId);
        }
    
        if (!empty($brandsCheckedArr) && true == $includePriority) {
            $brandSrch->addFld('IF(FIND_IN_SET(brand.brand_id, "' . implode(',', $brandsCheckedArr) . '"), 1, 0) as priority');
            $brandSrch->addOrder('priority', 'desc');
        } else {
            $brandSrch->addFld('0 as priority');
        }
        $brandSrch->addOrder('tb_l.brand_name');
        /* if needs to show product counts under brands[ */
        //$brandSrch->addFld('count(selprod_id) as totalProducts');
        /* ] */
        $brandRs = $brandSrch->getResultSet();
        $brands = FatApp::getDb()->fetchAll($brandRs);
        
        if (count($brands) > 0 && !FatApp::getConfig('CONF_PRODUCT_BRAND_MANDATORY', FatUtility::VAR_INT, 1) && in_array(null, array_column($brands, 'brand_id'))) {
            array_push($brands, array(
                'brand_id' => '-1',
                'brand_name' => Labels::getLabel('LBL_Unbranded', CommonHelper::getLangId()),
                'priority' => 9999
            ));
        }
        return $brands;
    }

    public static function getCategories($langId, $categoryId, $prodSrchObj, $cacheKey)
    {
        $cacheKey .= (true === MOBILE_APP_API_CALL) ? $cacheKey . '-m' : $cacheKey;
        /* $catFilter =  FatCache::get('catFilter' . $cacheKey, CONF_FILTER_CACHE_TIME, '.txt');
        if (!$catFilter) { */
        $catSrch = clone $prodSrchObj;
        $catSrch->doNotLimitRecords();
        $catSrch->joinProductToCategoryLang($langId);
        $catSrch->addGroupBy('c.prodcat_id');
        $excludeCatHavingNoProducts = true;
        if (!empty($keyword)) {
            $excludeCatHavingNoProducts = false;
        }
        $categoriesArr = ProductCategory::getTreeArr($langId, $categoryId, false, $catSrch, $excludeCatHavingNoProducts);
        $categoriesArr = (true === MOBILE_APP_API_CALL) ? array_values($categoriesArr) : $categoriesArr;
        FatCache::set('catFilter' . $cacheKey, serialize($categoriesArr), '.txt');
        return $categoriesArr;
        /*  } */
        return unserialize($catFilter);
    }

    public static function getOptions($langId, $categoryId, $prodSrchObj)
    {
        $options = FatCache::get('options' . $categoryId . '-' . $langId, CONF_FILTER_CACHE_TIME, '.txt');
        if (!$options) {
            $options = array();
            if ($categoryId && ProductCategory::isLastChildCategory($categoryId)) {
                $selProdCodeSrch = clone $prodSrchObj;
                $selProdCodeSrch->doNotLimitRecords();
                /*Removed Group by as taking time for huge data. handled in fetch all second param*/
                //$selProdCodeSrch->addGroupBy('selprod_code');
                $selProdCodeSrch->addMultipleFields(array('product_id', 'selprod_code'));
                $selProdCodeRs = $selProdCodeSrch->getResultSet();
                $selProdCodeArr = FatApp::getDb()->fetchAll($selProdCodeRs, 'selprod_code');

                if (!empty($selProdCodeArr)) {
                    foreach ($selProdCodeArr as $val) {
                        $optionsVal = SellerProduct::getSellerProductOptionsBySelProdCode($val['selprod_code'], $langId, true);
                        $options = $options + $optionsVal;
                    }
                }
            }

            usort(
                $options,
                function ($a, $b) {
                    if ($a['optionvalue_id'] == $b['optionvalue_id']) {
                        return 0;
                    }
                    return ($a['optionvalue_id'] < $b['optionvalue_id']) ? -1 : 1;
                }
            );
            FatCache::set('options ' . $categoryId . '-' . $langId, serialize($options), '.txt');
            return $options;
        }
        return unserialize($options);
    }
}
