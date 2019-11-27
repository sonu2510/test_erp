<?php
// Start: Building System
include_once("../ps-config.php");
include_once("../ps-define-path.php");
include_once("/home/standuppouches/public_html/admin/model/australia_tax_calender.php");
include_once("/home/standuppouches/public_html/admin/model/singapore_tax_calender.php");
include_once("/home/standuppouches/public_html/admin/model/maxico_tax_calender.php");
//include_once("/home/standuppouches/public_html/admin/index.php");

//Cron Job Mail For Australia
$obj_tax_aus = new australiaTaxCalender;
$aus_data = $obj_tax_aus->getTodayReminderDatesAus();

if($aus_data)
{
	foreach($aus_data as $i=>$aus)
	{
		$aus_msg = '';
		$aus_msg .= '<b>Description :</b> ' .$aus['description']."<br/>";
		$aus_msg .= '<b>Due Date : </b>' .dateFormat(4,$aus['date'])."<br/>";
		
		//offline menu_id
		//$menu_id = array('149');
		
		//online menu_id
		$menu_id = array('141');
		
		$permissionData = '';
			if($menu_id >0)
				$permissionData = $obj_tax_aus->getUserPermission($menu_id);
		
		
		if(!empty($permissionData))
		{
			foreach($permissionData as $email_id)
			{
				$toEmail[$email_id['user_name']] = $email_id['email'];	
			}
		}
		$toEmail['swisspac'] = 'tech@swisspack.co.in';
		//$toEmail['swisspac'] = ADMIN_EMAIL;
		
		
		$subject = 'Notification : Australia Tax Calender @ '.dateFormat(4,$aus['date']);
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(3); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
			
		//$path = HTTP_SERVER."template/proforma_invoice.html";
		$path ="/home/standuppouches/public_html/template/proforma_invoice.html";
		$output = file_get_contents($path);  
			
		
		$search  = array('{tag:header}','{tag:details}');
				
		$tag_val = array(
				"{{taxDetail}}" => $aus_msg);

		if(!empty($tag_val))
		{
			$desc = $temp_desc;
			foreach($tag_val as $k=>$v)
			{	
				@$desc = str_replace(trim($k),trim($v),trim($desc));
			} 
		}
		
		$replace = array('',$desc);
		$message = str_replace($search, $replace, $output);
		foreach($toEmail as $toemail)
		{
			$response = send_email('p.kju99@yahoo.in','tech@swisspack.co.in',$subject,$message,'');
		}
		
	}
	
}

//Cron Job Mail For Singapore
//include(DIR_ADMIN_MODEL.'singapore_tax_calender.php');
$obj_tax_sin = new singaporeTaxCalender;
$sin_data = $obj_tax_sin->getTodayReminderDatesSin();
//echo $sin_data;
if($sin_data)
{
	foreach($sin_data as $i=>$sin)
	{
		$sin_msg = '';
		$sin_msg .= '<b>Description :</b> ' .$sin['description']."<br/>";
		$sin_msg .= '<b>Due Date : </b>' .dateFormat(4,$sin['date'])."<br/>";
		
		//offline menu_id
		//$menu_id = array('148');
		
		$sin_insert = $obj_tax_sin->insertdata($sin['description'],$sin['date']);
		//printr($sin_insert);
		
		//online menu_id
		$menu_id = array('140');
		
		$permissionData = '';
			if($menu_id >0)
				$permissionData = $obj_tax_sin->getUserPermission($menu_id);
		
		
		if(!empty($permissionData))
		{
			foreach($permissionData as $email_id)
			{
				$toEmail[$email_id['user_name']] = $email_id['email'];	
			}
		}
		$toEmail['swisspac'] = 'tech@swisspack.co.in';
		//$toEmail['swisspac'] = 'p.kju99@yahoo.in';

		//$toEmail['swisspac'] = ADMIN_EMAIL;
		
		$subject = 'Notification : Singapore Tax Calender @ '.dateFormat(4,$sin['date']);
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(3); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
			
		//$path = HTTP_SERVER."template/proforma_invoice.html";
		$path ="/home/standuppouches/public_html/template/proforma_invoice.html";
		$output = file_get_contents($path);  
			
		
		$search  = array('{tag:header}','{tag:details}');
				
		$tag_val = array(
				"{{taxDetail}}" => $sin_msg);

		if(!empty($tag_val))
		{
			$desc = $temp_desc;
			foreach($tag_val as $k=>$v)
			{	
				@$desc = str_replace(trim($k),trim($v),trim($desc));
			} 
		}
		
		$replace = array('',$desc);
		$message = str_replace($search, $replace, $output);
		foreach($toEmail as $toemail)
		{
			
			$response = send_email('p.kju99@yahoo.in','tech@swisspack.co.in',$subject,$message,'');
		
		}
		//$response = send_email('p.kju99@yahoo.in','p.kju99@yahoo.in',$subject,$message,'');
		
	}
	
}


//Cron Job Mail For Maxico
//include(DIR_ADMIN_MODEL.'maxico_tax_calender.php');
$obj_tax_max = new maxicoTaxCalender;
$max_data = $obj_tax_max->getTodayReminderDatesMax();

if($max_data)
{
	foreach($max_data as $i=>$max)
	{
		$max_msg = '';
		$max_msg .= '<b>Description :</b> ' .$max['description']."<br/>";
		$max_msg .= '<b>Due Date : </b>' .dateFormat(4,$max['date'])."<br/>";
		
		//offline menu_id
		//$menu_id = array('147');
		
		//online menu_id
		$menu_id = array('139');
		
		$permissionData = '';
			if($menu_id >0)
				$permissionData = $obj_tax_max->getUserPermission($menu_id);
		
		
		if(!empty($permissionData))
		{
			foreach($permissionData as $email_id)
			{
				$toEmail[$email_id['user_name']] = $email_id['email'];	
			}
		}
		$toEmail['swisspac'] = 'tech@swisspack.co.in';
		//$toEmail['swisspac'] = ADMIN_EMAIL;
		
		
		$subject = 'Notification : Maxico Tax Calender @ '.dateFormat(4,$max['date']);
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(3); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
			
		//$path = HTTP_SERVER."template/proforma_invoice.html";
		$path ="/home/standuppouches/public_html/template/proforma_invoice.html";
		$output = file_get_contents($path);  
			
		
		$search  = array('{tag:header}','{tag:details}');
				
		$tag_val = array(
				"{{taxDetail}}" => $max_msg);

		if(!empty($tag_val))
		{
			$desc = $temp_desc;
			foreach($tag_val as $k=>$v)
			{	
				@$desc = str_replace(trim($k),trim($v),trim($desc));
			} 
		}
		
		$replace = array('',$desc);
		$message = str_replace($search, $replace, $output);
		foreach($toEmail as $toemail)
		{
			
			$response = send_email('p.kju99@yahoo.in','tech@swisspack.co.in',$subject,$message,'');
		
		}

		
	}
	
}
//echo "done";
?>
