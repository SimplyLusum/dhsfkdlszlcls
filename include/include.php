<?
// ===[const area]==============================
// include the ADODB Library
define('ADODB_DIR','lib/adodb/');
//include(ADODB_DIR . 'adodb.inc.php');
//include('lib/phpmailer/class.phpmailer.php');

define('DBServer','localhost');
define('DBType','mysqlt');
define('DBName','ong_onlinekitcen');
define('DBUsername','root');
define('DBPassword','root');
define('PAGE_ITEMS', 50);
define('MAIL_SERVER', 'localhost');





//require('functions.php');


// ===[code area]==============================

$g_dbconn = ADONewConnection(DBType);
$g_dbconn->Connect(DBServer,DBUsername,DBPassword,DBName);

//include ("class/db.class.php");
include ("class/setting.class.php");
include("class/page.class.php");
//include("class/cart.class.php");

// start session.
session_start();

//if (!isset($_SESSION['cart'])) { $_SESSION['cart']=new Cart(); }


?>