<?php
/**
 *
 * General configurations
 */

define('CONF_DEVELOPMENT_MODE', (strpos(($_SERVER['SERVER_NAME']), '4demo.biz')>0)?true:true);
//define('CONF_DEVELOPMENT_MODE', false);
define('CONF_LIB_HALDLE_ERROR_IN_PRODUCTION', true);
define('CONF_URL_REWRITING_ENABLED', true);
define('PASSWORD_SALT', 'ewoiruqojfklajreajflfdsaf');
define('CONF_INSTALLATION_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
define('CONF_UPLOADS_PATH', CONF_INSTALLATION_PATH . 'user-uploads' . DIRECTORY_SEPARATOR);
//define('CONF_UPLOADS_PATH',  's3://');
define('CONF_FRONT_END_APPLICATION_DIR', 'application/');
define('CONF_PLUGIN_DIR', CONF_INSTALLATION_PATH . 'library/plugins/');

if (strpos($_SERVER ['SERVER_NAME'], '.4demo.biz') !== false) {
    define('CONF_CORE_LIB_PATH', '/etc/fatlib/');
} elseif (strpos($_SERVER ['SERVER_NAME'], '.4livedemo.com') !== false) {
    define('CONF_CORE_LIB_PATH', '/home/fatlib/v2.0/');
} else {
    define('CONF_CORE_LIB_PATH', CONF_INSTALLATION_PATH . 'library/core/');
}
define('CONF_USE_FAT_CACHE', (strpos(($_SERVER['SERVER_NAME']), '4demo.biz')>0) ? false : false);
define('CONF_DEF_CACHE_TIME', 2592000); // in seconds (2592000 = 30 days)
define('CONF_IMG_CACHE_TIME', 14400); // in seconds (1400 = 4 hours)
define('CONF_HOME_PAGE_CACHE_TIME', 28800); // in seconds (28800 = 8 hours)
define('CONF_FILTER_CACHE_TIME', 14400); // in seconds (1400 = 4 hours)
define('CONF_API_REQ_CACHE_TIME', 108000); // in seconds (108000 = 30 min)

define('S3_KEY', '');
define('S3_SECRET', '');
define('S3_REGION', 'us-east-2');

define('CONF_TEST_CASES_DIR', CONF_INSTALLATION_PATH . 'testcases/');
