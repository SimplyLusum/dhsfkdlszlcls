<?
// ===[const area]==============================
// include the ADODB Library
define('ADODB_DIR','../lib/adodb/');
include(ADODB_DIR . 'adodb.inc.php');
include('../lib/phpmailer/class.phpmailer.php');
include ("thumbnail.inc.php");

define('DBServer','imanex.mysql5.webhost.co.nz');
define('DBType','mysqlt');
define('DBName','ong_onlinekitcen');
define('DBUsername','onlinekitcen');
define('DBPassword','onlinekitcen1@#$');
define('PAGE_ITEMS', 50);
define('MAIL_SERVER', 'localhost');
define('SITE_NAME', 'Online Kitchens Website Administration System');


// start session.
session_start();



require('functions.php');

// ===[code area]==============================

$g_dbconn = ADONewConnection(DBType);
$g_dbconn->Connect(DBServer,DBUsername,DBPassword,DBName);

include ("../class/db.class.php");
include ("../class/dataface.class.php");



?>
