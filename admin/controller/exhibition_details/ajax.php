<?php
// Start: Building System
include("mode_setting.php");
$fun = $_GET['fun'];
// End: Building System
if($_GET['fun']=='UserNameAlreadyExsist') {
if(isset($_POST['name']) && $_POST['name'] != ''){
	$user_name = $_POST['name'];
	//echo $material_name;die;
	$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "account_master` WHERE user_name = '".$user_name."'";
	$data = $obj_db->query($sql);
	//echo $data->row['total'];die;
	if($data->row['total'] > 0){
		echo 1;
	}else{
		echo 0;
	}
}else{
	echo 0;
}
}

if($fun == 'remove'){
	$person_id= $_POST['person_id'];
	$obj_exhibition->remove_person($person_id);
}

if($_GET['fun']=='production_data')
 { 
 		//printr($_POST);die;
     $data= json_decode($_POST['post_arr']);
	 $post = array(
				'f_date'=>$data->f_date,
				't_date'=>$data->t_date);
			
	  $html =$obj_exhibition->get_sheet($post);
	 
	
	 echo $html;
 }
if($fun == 'get_sheet') {
	 $data= json_decode($_POST['post_arr']);
	 	 $post = array(
				'f_date'=>$data->f_date,
				't_date'=>$data->t_date,
				'exhibition_name'=>$data->exhibition_name,
				'country_id'=>$data->country_id);
	$html =$obj_exhibition->get_sheet($post);
	
 echo $html;
		
	
}
if($fun=='ExibitionNameExsist')
{ 
	if(isset($_POST['name']) && $_POST['name'] != ''){
		$exibition_name = $_POST['name'];
			$sql = "SELECT COUNT(*) as total FROM exhibition_details  WHERE  exhibition_name = '".$exibition_name."' AND  is_delete = '0' " ;
	
		$data = $obj_db->query($sql);
		if($data->row['total'] > 0){
			echo 1;
		}else{
			echo 0;
		}
	}else{
		echo 0;
	}
}
?>