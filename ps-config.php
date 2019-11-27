<?php 

//print_r($_SESSION);die;
//print_r($session_start);die;
if(!isset($session_start)){
    //ini_set(session.gc_maxlifetime, 300);
	session_start();
	//ini_set(session.gc_maxlifetime, 300);
//	print_r("hhii");
} 

define( 'DB_HOSTNAME', 'localhost' ); // set database host
define( 'DB_USERNAME', 'root' ); // set database user
define( 'DB_PASSWORD', '' ); // set database password
define( 'DB_DATABASE', 'swissin_swissonline' ); // set database name
define('DB_DRIVER', 'MySql');
define( 'DB_PREFIX','');
define( 'ST_DB_PREFIX','st_');
define( 'SEND_ERRORS_TO', '' ); //set email notification email address
define( 'DISPLAY_DEBUG', true ); //display db errors?

//LOCAL ORDER ACCEPT MENU ID
//define('ORDER_ACCEPT_ID',91);
//LIVE ORDER ACCEPT MENU ID
define('ORDER_ACCEPT_ID',79);

//LOCAL ORDER INPROCESS(DISPATCHED) MENU ID
//define('ORDER_INPROCESS_ID',92);
//LIVE ORDER INPROCESS(DISPATCHED) MENU ID
define('ORDER_INPROCESS_ID',80);
define('PERMISSION_ACCEPT_DECLINE',79);
//LOCAL ORDER MAin Dispatched MENU ID
//define('ORDER_DISPATCHED_ID',89);
//LIVE ORDER MAin Dispatched MENU ID

//define('ORDER_DISPATCHED_ID',77);TEST
define('ORDER_DISPATCHED_ID',205);

//LOCAL ORDER new ORDER MENU ID
//define('ORDER_NEWORDER_ID',83);
//LIVE ORDER NEWORDER MENU ID

//define('ORDER_NEWORDER_ID',75);TEST
define('ORDER_NEWORDER_ID',203);

//LOCAL ORDER STOCK ORDER PRICE UPDATE MENU ID
//define('ORDER_PRICEEDIT_ID',93);

//LIVE ORDER STOCK ORDER PRICE UPDATE MENU ID
define('ORDER_PRICEEDIT_ID',81);
//define('ORDER_DECLINE_ID',78);
define('ORDER_DECLINE_ID',206);

//define('DISCOUNT_RATE_PERMISSION',193); local
//add sonu 8-4-2017
define('DISCOUNT_RATE_PERMISSION',177);
define('GRESS_PRICE_PERMISSION',151);
//add sonu 15-6-2019 FOR EXPORT TEAM TO SEND MAIL DIRECT TO CUSTOMER
define('DIRECT_DISPATCHED_TRACKING_PERMISSION',329);

define('LEAD_LIST_PERMISSION',232);
define('SHOW_CHART_PERMISSION',233);
define('PHYSICAL_STOCK_PERMISSION',325);

define('SHOW_THICKNESS_PERMISSION',252);

require_once("ps-define-path.php");

require_once("ps-define-path.php");
 

//DISPLAY LISTING RECORD LIMIT FOR ONE PAGE
//define('LISTING_LIMIT',20);

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Session
$obj_session = new Session();

//session store
$obj_session->data['SES_DIR_SERVER'] = DIR_SERVER;

//DB object
$obj_db = new dbclass;


date_default_timezone_set("Asia/Calcutta");

//include general function
include_once(DIR_SERVER."function.php");



//create database object for use general purpose
/*include_once(DIR_SYSTEM."database/mysql.php");
$db  = new DBMySQL(DB_HOST,DB_USER,DB_PASS,DB_NAME);*/



//Get All Data From General Settings... 
/*$returnarr = $obj_conn->Select("generalsetting", $where='', $orderBy='', $limit="", $like='', $operand='',$cols='*',1);
for($i=0;$i<count($returnarr);$i++)
{
	if($returnarr[$i]['controlname'] != "")
	{
		define($returnarr[$i]["controlname"],$returnarr[$i]['value']);			 
	}
} 
*/
?>