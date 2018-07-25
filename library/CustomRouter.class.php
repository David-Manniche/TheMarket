<?php
class CustomRouter{	
	static function setRoute(&$controller, &$action, &$queryString){		
		$userType = null;	
		
		if ('mobile-app-api' == $controller) {			
			define('MOBILE_APP_API_CALL', true);
            define('MOBILE_APP_API_VERSION', 'v1');
		}else if ('app-api' == $controller) {
			$controller = 'mobile-app-api';
			define('MOBILE_APP_API_CALL', true);
			define('MOBILE_APP_API_VERSION', $action);
			
			if (!array_key_exists(0, $queryString)) {
                $queryString[0] = '';
            }
            if (!array_key_exists(1, $queryString)) {
                $queryString[1] = '';
            }
			
            $action = $queryString[0];            
            if ($controller != '' && $action == '') 
            { 
                $action = 'index';
            }
            array_shift($queryString);	
			
			$token = null;
            
			if(array_key_exists('HTTP_X_USER_TYPE',$_SERVER)){
				$userType = intval($_SERVER['HTTP_X_USER_TYPE']);
			}			
		}else {
            define('MOBILE_APP_API_CALL', false);
            define('MOBILE_APP_API_VERSION', '');
        }
		
		define('MOBILE_APP_USER_TYPE', $userType);
		
		if(defined('SYSTEM_FRONT') && SYSTEM_FRONT === true && !FatUtility::isAjaxCall()){				
			$url = $_SERVER['REQUEST_URI'];			
			
			/* [ Check url rewritten by the system and "/" discarded in url rewrite*/
			$customUrl = substr($url, strlen(CONF_WEBROOT_URL));
			$customUrl = rtrim($customUrl, '/');
			$customUrl = explode('/',$customUrl);
			
			$srch = UrlRewrite::getSearchObject();
			$srch->addCondition(UrlRewrite::DB_TBL_PREFIX . 'custom', '=', $customUrl[0]);
			$rs = $srch->getResultSet();
			if (!$row = FatApp::getDb()->fetch($rs)) {											
				return;
			}
			/*]*/			
			
			$url = $row['urlrewrite_original'];
			$arr = explode('/', $url);
			
			$controller = (isset($arr[0]))?$arr[0]:'';
			array_shift($arr);			
			
			$action = (isset($arr[0]))?$arr[0]:'';
			array_shift($arr);
			
			$queryString = $arr;			
			/* [ used in case of filters when passed through url*/
			array_shift($customUrl);
			if(!empty($customUrl)){
				$queryString = array_merge($queryString,$customUrl);			
			}			
			/* ]*/
			
            if ($controller != '' && $action == '') 
            { 
                $action = 'index';
            }
			
			if ($controller == '') { $controller = 'Content'; }
			
			if ($action == ''){ $action = 'error404'; }			
		}
	}	
}	
