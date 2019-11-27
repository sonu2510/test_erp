<?php

//jayashree
include("mode_setting.php");
$fun = $_GET['fun'];
if($_GET['fun']=='checkcurrencyname'){


$currency_name =$_POST['currency_name'];
	
	$response = $obj_currency->$fun($currency_name);
	if($response){
		echo 1;
	}else{
		echo 0;
	}

}
if($_GET['fun']=='checkcurrencycode'){


$currency_code =$_POST['currency_code'];
	
	$response = $obj_currency->$fun($currency_code);
	if($response){
		echo 1;
	}else{
		echo 0;
	}

}
?>