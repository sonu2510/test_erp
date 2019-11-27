<?php
  require_once('../class.phpmailer.php');

		$b='';

		$b =  $b ."You have got the Inquiry from<br>";

		$b =  $b . "\n"."Name : " . $post['name']."<br>";

		$b =  $b . "\n"."Company Name : " . $post['company_name']."<br>";

		$b =  $b . "\n"."Address: " . $post['address']."<br>";

		$b =  $b . "\n"."country: " . $post['country']."<br>";
		
		$b =  $b . "\n"."state: " . $post['state']."<br>";

		$b =  $b . "\n"."Phone : " . $post['phone_no']."<br>";

		$b =  $b . "\n"."Email : " . $post['email']."<br>";
		
		//$b =  $b . "\n"."Website Name : ".$post['domain_name']."<br>";

		if(isset($post['product_name']) && isset($post['weight']) && isset($post['number_bags']))

		{

			$b =  $b . "\n"."Name of the product to be filled inside the bags : " . $post['product_name']."<br>";

			$b =  $b . "\n"."Weight to be filled in each bags : " . $post['weight']."<br>";

			$b =  $b . "\n"."Number of bags / rolls required  : " . $post['number_bags']."<br>";

		}

		$b =  $b . "\n"."Remarks / Requirements  : " . $post['message']."<br>";
	
		$string = explode ('.', $post['domain_name'], 3);
		
		
		$url = preg_replace('#^www\.(.+\.)#i', '$1', $post['domain_name'])." - " .date("d/m/y");
		$to = $post['to_email'];

		//$subject = "Inquiry from ".$post['domain_name']." - " .date("d/m/y");
		//$subject = "Inquiry from pouchmakers.com - " .date("d/m/y");

		$message = $b;		

		$from =$post['email'];

		//$headers = "From: ".$from;

	$mailid=new PHPMailer(); // defaults to using php "mail()"
	$mailibcc = 'swisspack1@gmail.com';
	$mailid->SetFrom($post['email']);
	
	$mailadd=$to;
	$mailid->AddAddress($mailadd);
	
	$mailid->AddBCC($mailibcc);
	
	$mailid->Subject= "Inquiry from ".$url;
	$mailid->AltBody= "View the mail"; // Alt
	
	$mailid->MsgHTML($message);
		
	if(!$mailid->Send()) {
	  //echo "Mailer Error: ".$mailid->ErrorInfo;
	  return false;
	} else {
	  //echo $message;
	// printr($mailadd);
	  return true;
	 // die;
	}
	//die;
?>