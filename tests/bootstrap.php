<?php 
/* ./vendor/phpunit/phpunit/phpunit --filter testLogin --bootstrap ./tests/bootstrap.php ./tests/testcases/UserAuthenticationTest */
/* ./vendor/bin/phpunit --filter testLogin --bootstrap ./tests/bootstrap.php ./tests/testcases/UserAuthenticationTest */

$_SERVER['REDIRECT_REDIRECT_STATUS'] = '200';
$_SERVER['REDIRECT_STATUS'] = '200';
$_SERVER['HTTP_HOST'] = 'yokart.local.4livedemo.com';
$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:60.0) Gecko/20100101 Firefox/60.0';
$_SERVER['HTTP_ACCEPT'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
$_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'en-US,en;q=0.5';
$_SERVER['HTTP_ACCEPT_ENCODING'] = 'gzip, deflate';
$_SERVER['HTTP_COOKIE'] = '__unam=ec11dfb-162d251a0d3-24516594-126; _ga=GA1.2.1780498620.1523957710; PHPSESSID=vnokvmt2ofsm86bo42jh0m83q6';
$_SERVER['HTTP_CONNECTION'] = 'keep-alive';
$_SERVER['HTTP_UPGRADE_INSECURE_REQUESTS'] = '1';
$_SERVER['PATH'] = '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games:/root/.fzf/bin';
$_SERVER['SERVER_SIGNATURE'] = 'Apache/2.4.18 (Ubuntu) Server at yokart.local.4livedemo.com Port 80';
$_SERVER['SERVER_SOFTWARE'] = 'Apache/2.4.18 (Ubuntu)';
$_SERVER['SERVER_NAME'] = 'yokart.local.4livedemo.com';
$_SERVER['SERVER_ADDR'] = '127.0.0.1';
$_SERVER['SERVER_PORT'] = '80';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['DOCUMENT_ROOT'] = 'yokart';
$_SERVER['REQUEST_SCHEME'] = 'http';
$_SERVER['CONTEXT_PREFIX'] = '';
$_SERVER['CONTEXT_DOCUMENT_ROOT'] = 'yokart';
$_SERVER['SERVER_ADMIN'] = 'webmaster@localhost';
$_SERVER['SCRIPT_FILENAME'] = 'yokart/public/index.php';
$_SERVER['REMOTE_PORT'] = '52664';
$_SERVER['REDIRECT_URL'] = '';
$_SERVER['REDIRECT_QUERY_STRING'] = 'url=';
$_SERVER['GATEWAY_INTERFACE'] = 'CGI/1.1';
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['QUERY_STRING'] = 'url=';
$_SERVER['REQUEST_URI'] = '';
$_SERVER['SCRIPT_NAME'] = '/public/index.php';
$_SERVER['PHP_SELF'] = '/public/index.php';
$_SERVER['REQUEST_TIME_FLOAT'] = time();
define('UNIT_TESTING_MODE', true);
define('CONF_DEVELOPMENT_MODE', true);
define('PASSWORD_SALT', 'ewoiruqojfklajreajflfdsaf');
define('CONF_INSTALLATION_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
// define( 'CONF_FRONT_END_APPLICATION_DIR', 'application/' );
define('CONF_ADMIN_END_APPLICATION_DIR', 'admin-application/');

//define('CONF_CORE_LIB_PATH', '/etc/fatlib/2.0/core/');
define('CONF_CORE_LIB_PATH', CONF_INSTALLATION_PATH . 'library/core/');


// define('CONF_APPLICATION_PATH', CONF_INSTALLATION_PATH . CONF_FRONT_END_APPLICATION_DIR);
define('CONF_APPLICATION_PATH', CONF_INSTALLATION_PATH . CONF_ADMIN_END_APPLICATION_DIR);

define('CONF_URL_REWRITING_ENABLED', true);

define('CONF_UPLOADS_PATH', CONF_INSTALLATION_PATH . 'user-uploads' . DIRECTORY_SEPARATOR);

define('CONF_WEBROOT_FRONT_URL', '/');
define('CONF_WEBROOT_BACKEND_URL', '/admin/');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

define('CONF_APP_URL', $_SERVER['SERVER_NAME']);
define('CONF_BASE_URL', $protocol . $_SERVER['SERVER_NAME']);


define('CONF_DB_SERVER', 'localhost');
define('CONF_DB_USER', 'root');
define('CONF_DB_PASS', '');
define('CONF_DB_NAME', 'yokart');

define('CONF_WEBROOT_FRONTEND', '/'); 
define('CONF_WEBROOT_URL', CONF_WEBROOT_FRONTEND);
define('MOBILE_APP_API_CALL', false);

define('CONF_PLUGIN_DIR', CONF_INSTALLATION_PATH . 'library/plugins/');

require_once CONF_INSTALLATION_PATH . 'library/autoloader.php';
