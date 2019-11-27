<?php
// Get Path Details 
define("DIR_SERVER",str_replace('\\','/',dirname(__FILE__)). '/');
//$_SESSION['SES_SERVER_PATH'] = SERVER_PATH;
define("DIR_ADMIN",DIR_SERVER.'admin/');
define("DIR_STOREADMIN",DIR_SERVER.'storeadmin/');
define('DIR_ADMIN_MODEL',DIR_ADMIN.'model/');
define('DIR_SYSTEM',DIR_SERVER.'system/');
define('DIR_DATABASE',DIR_SYSTEM.'database/');
define('DIR_UPLOAD',DIR_SERVER.'upload/');
define('HTTP_SERVER','http://192.168.1.191/erp/swisspac/');
define('HTTP_ADMIN',HTTP_SERVER.'admin/');
define('HTTP_STOREADMIN',HTTP_SERVER.'storeadmin/');
define('HTTP_ADMIN_CONTROLLER',HTTP_ADMIN.'controller/');
define('HTTP_ADMIN_MODEL',HTTP_ADMIN.'model/');
define('HTTP_UPLOAD',HTTP_SERVER.'upload/');

//Date Formate
define('DATE_FORMATE1','d-m-Y');
define('DATE_FORMATE2','m-d-Y');
define('DATE_FORMATE3','M-d-Y');
define('DATE_FORMATE4','F d, Y');
define('DATE_FORMATE5','F l d, Y');
?>