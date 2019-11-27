<?php
//start session
session_start();

//config database
define('SERVER_NAME','localhost');
define('USER_NAME','root');
define('PASSWORD','');
define('DATABASE_NAME','swisspac');
define('DB_PREFIX','');

//DISPLAY LISTING RECORD LIMIT FOR ONE PAGE
define('LISTING_LIMIT',2);


//URL
define('SERVER_URL','http://192.168.1.250/erp/swisspac/');
define('ADMIN_URL','http://192.168.1.250/erp/swisspac/admin/');

include('../startup.php');
include('model/function.php');
include('model/dbclass.php');
?>