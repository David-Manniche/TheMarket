<?php
class UrlHelper extends FatUtility
{
    public static function getCachedUrl(string $key, int $expiry = null, string $extension = '')
    {
        $url = FatCache::getCachedUrl($key, $expiry, $extension);
        
        if (CDN_DOMAIN_URL != '') {
            if (strpos($url, CDN_DOMAIN_URL) !== false) {
                return $url;
            }
            return rtrim(CDN_DOMAIN_URL, '/') . '/' . ltrim($url, '/');
        }

        return $url;
    }

    public static function getAsFileUrl(string $key, int $expiry = null, string $extension = '')
    {
        return FatCache::getAsFileUrl($key, $expiry, $extension);
    }

    public static function generateFileUrl($controller = '', $action = '', $queryData = array(), $use_root_url = '', $url_rewriting = null, $encodeUrl = false, $getOriginalUrl = false)
    {
        $url = CommonHelper::generateUrl($controller, $action, $queryData, $use_root_url, $url_rewriting, $encodeUrl, $getOriginalUrl);
        
        if (CDN_DOMAIN_URL != '') {
            return rtrim(CDN_DOMAIN_URL, '/') . '/' . ltrim($url, '/');
        }

        return $url;
    }

    public static function generateFullFileUrl($controller = '', $action = '', $queryData = array(), $use_root_url = '', $url_rewriting = null, $encodeUrl = false)
    {
        $url = CommonHelper::generateUrl($controller, $action, $queryData, $use_root_url, $url_rewriting);
        if ($encodeUrl) {
            $url = urlencode($url);
        }
        
        if (CDN_DOMAIN_URL != '') {
            return rtrim(CDN_DOMAIN_URL, '/') . '/' . ltrim($url, '/');
        }

        $protocol = (FatApp::getConfig('CONF_USE_SSL') == 1) ? 'https://' : 'http://';
        return $protocol . $_SERVER['SERVER_NAME'] . $url;
    }

    public static function staticContentProvider($controller, $action)
    {
        if (in_array($controller, array('js-css','image','fonts','images', 'js', 'img', 'innovas','assetmanager'))) {
            return true;
        }

        $arr = [
            'banner' => [
                'home-page-banner-top-layout',
                'home-page-banner-middle-layout',
                'home-page-banner-bottom-layout',
                'product-detail-page-banner',
                'thumb',
                'show-banner',
                'show-original-banner',
                'product-page',
                'blog',
                'brand-page',
            ],
            'category' => [
                'banner',
                'seller-banner',
                'image',
                'icon',
                'banner'
            ],
            'custom' => [
                'update-screen-resolution'
            ],
            'Account' => [
                'user-profile-image'
            ],
            'home' => [
                'pwa-manifest',
                'get-url-segments-detail',
                'splash-screen-data',
                'get-image',
                'get-all-sponsored-products'
            ]
        ];

        if (array_key_exists($controller, $arr) && in_array($action, $arr[$controller])) {
            return true;
        }

        return false;
    }
}
