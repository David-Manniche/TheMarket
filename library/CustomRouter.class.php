<?php
class CustomRouter
{
    public static function setRoute(&$controller, &$action, &$queryString)
    {
        $userType = null;
        if ('mobile-app-api' == $controller) {
            define('MOBILE_APP_API_CALL', true);
            define('MOBILE_APP_API_VERSION', '1.0');
        } elseif ('app-api' == $controller) {
            define('MOBILE_APP_API_CALL', true);
            define('MOBILE_APP_API_VERSION', str_replace('v', '', $action));

            if (MOBILE_APP_API_VERSION <= '1.2') {
                $controller = 'mobile-app-api';
                if (!array_key_exists(0, $queryString)) {
                    $queryString[0] = '';
                }
                if (!array_key_exists(1, $queryString)) {
                    $queryString[1] = '';
                }
            } else {
                if (!array_key_exists(0, $queryString)) {
                    $arr = array('status'=>-1,'msg'=>"Invalid Request");
                    die(json_encode($arr));
                }

                $controller = $queryString[0];
                array_shift($queryString);

                if (!array_key_exists(0, $queryString)) {
                    $queryString[0] = '';
                }
            }

            $action = $queryString[0];
            if ($controller != '' && $action == '') {
                $action = 'index';
            }

            array_shift($queryString);

            $token = null;

            if (array_key_exists('HTTP_X_USER_TYPE', $_SERVER)) {
                $userType = intval($_SERVER['HTTP_X_USER_TYPE']);
            }
        } else {
            define('MOBILE_APP_API_CALL', false);
            define('MOBILE_APP_API_VERSION', '');
        }
        define('MOBILE_APP_USER_TYPE', $userType);

        if (defined('SYSTEM_FRONT') && SYSTEM_FRONT === true/*  && !FatUtility::isAjaxCall() */) {
            $url = $_SERVER['REQUEST_URI'];
                                   
            $customUrl = substr($url, strlen(CONF_WEBROOT_URL));
            $customUrl = rtrim($customUrl, '/');
            $customUrl = explode('?yk-f', $customUrl);
     
            /* [ Check url rewritten by the system or system url with query parameter*/
            $srch = UrlRewrite::getSearchObject();
            $srch->doNotCalculateRecords();
            $srch->addMultipleFields(array('urlrewrite_custom','urlrewrite_original'));
            $srch->setPageSize(1);
            $srch->addCondition(UrlRewrite::DB_TBL_PREFIX . 'custom', '=', $customUrl[0]);
            $rs = $srch->getResultSet();
            $row = FatApp::getDb()->fetch($rs);            
            if (!$row && (!isset($customUrl[1]) || (isset($customUrl[1]) && strpos($customUrl[1], 'yk-f') === false))) {
                return;
            }
            /*]*/

            $url = $row['urlrewrite_original'];
            if (!$row && isset($customUrl[1])) {
                $url = $customUrl[0];
            }
           
            $arr = explode('/', $url);

            $controller = (isset($arr[0]))?$arr[0]:'';
            array_shift($arr);

            $action = (isset($arr[0]))?$arr[0]:'';
            array_shift($arr);

            $queryString = $arr;
            /* [ used in case of filters when passed through url*/
            //array_shift($customUrl);
            if (isset($customUrl[1]) && !empty($customUrl[1])) {
                $customUrl = explode('&', $customUrl[1]);
                $queryString = array_merge($queryString, $customUrl);
            }
            
            /* ]*/

            if ($controller != '' && $action == '') {
                $action = 'index';
            }

            if ($controller == '') {
                $controller = 'Content';
            }
            
            if ($action == '') {
                $action = 'error404';
            }
        }
    }
}
