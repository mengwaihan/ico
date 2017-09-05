<?php


require_once (dirname(__FILE__) . '/OKCoin/OKCoin.php');
require_once ('sql.php');
require_once ('private.php');
require_once ('icoModel.php');

!defined('API_KEY') &&  define('API_KEY', '');
!defined('SECRET_KEY') &&  define('SECRET_KEY', '');

!defined('DBNAME') &&  define('DBNAME', '');
!defined('HOSTNAME') &&  define('HOSTNAME', '');
!defined('DBUSER') &&  define('DBUSER', '');
!defined('DBPASS') &&  define('DBPASS', '');

$_GLOBAL['db'] = new Db();
$_GLOBAL['ico'] = new OKCoin(new OKCoin_ApiKeyAuthentication(API_KEY, SECRET_KEY));
$_GLOBAL['icoModel'] = new iocModel();

