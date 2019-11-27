<?php
include("mode_setting.php");
//[kinjal]:
$fun = $_GET['fun'];
//echo $fun;
$json=array();

 if($_GET['fun']=='enquiry_data')
 { 
     $data= json_decode($_POST['post_arr']);
	//printr($data-> user_name);die;
	//$user_name
	$post = array('user_name'=>$data-> user_name,
				'product' => $data->product,
				'f_date'=>$data->f_date,
				't_date'=>$data->t_date);
	//printr($post);die;
	$enquiry_data = $obj_enquiry->getenquiryReport($post);
	
	$html =$obj_enquiry->viewenquiryReport($enquiry_data);
	
	echo $html;
	/*$enquiry = $obj_enquiry->getenquiryReport($post);
	if($enquiry != 0)
	{
		return json_encode($enquiry);
	}
	else
	{
		return 0;
	}*/
	
 }
 if($_GET['fun']=='getEmpList')
 {
     $ib = $_POST['ib'];
     $sel = $_POST['sel'];
     $getEmplist = $obj_enquiry->getEmpList($ib,$sel);
     $html = '';
     $html.= '<option value="">Select User</option>';
     foreach($getEmplist as $list){
          $html .='<option value="'.$list['employee_id'].'">'.$list['user_name'].'</option>';
     }
     echo $html;
 }
if($_GET['fun']=='getEmpList_new')
 {
	
	$ib_id=explode('=',$_POST['ib']);
	$data =$obj_enquiry->getEmpList($_POST['ib'],$_POST['sel']);
	$ib_data =$obj_enquiry->getUser($ib_id[1],'4');
	$html = '';
	$html .='<option value="">Select User</option>';
	$html.="<option value='4=".$ib_id[1]."'>".$ib_data['user_name']."</option>";
	if(isset($data))
	{
		foreach($data as $d)
		{
			$html.="<option value='2=".$d['employee_id']."'>".$d['user_name']."</option>";
		}
	}
	echo $html;//die;
	
 }
?>