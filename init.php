<?php


require_once (dirname(__FILE__) . '/OKCoin/OKCoin.php');

if (file_exists(dirname(__FILE__) . '/private.php')) {
    require_once(dirname(__FILE__) . '/private.php');
}


!defined('API_KEY') &&  define('API_KEY', '');
!defined('SECRET_KEY') &&  define('SECRET_KEY', '');

!defined('DBNAME') &&  define('DBNAME', '');
!defined('HOSTNAME') &&  define('HOSTNAME', '');
!defined('DBUSER') &&  define('DBUSER', '');
!defined('DBPASS') &&  define('DBPASS', '');

require_once ('sql.php');
require_once ('icoModel.php');
$GLOBALS['db'] = new Db();
$GLOBALS['ico'] = new OKCoin(new OKCoin_ApiKeyAuthentication(API_KEY, SECRET_KEY));
$GLOBALS['icoModel'] = new iocModel();

