<?php
//echo "hiii";die;
//printr($_FILES);die;
include('mode_setting.php');
$fun=$_GET['fun'];
if($fun=='ajaximage')
{
	$fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'zip', 'xlsx', 'cad', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'pps', 'ppsx', 'odt', 'xls', 'xlsx', '.mp3', 'm4a', 'ogg', 'wav', 'mp4', 'm4v', 'mov', 'wmv', 'csv', 'txt' );
	if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' && $_FILES['file']['error'] == 0){
	
	$validateImageExt = validateUploadImage($_FILES['file']);
	//$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	$userfile_extn = explode(".", strtolower($_FILES['file']['name']));
	//printr($userfile_extn);//die;
		if (in_array(strtolower($userfile_extn[1]), $fileTypes)) {
			//echo "get";die;
			require_once(DIR_SYSTEM . 'library/resize-class.php');
		
			$upload_path = DIR_UPLOAD.'admin/tax_img/';
		
			$file_name = $_FILES['file']['name'];
			$filetemp = $_FILES['file']['tmp_name'];
			$upload_image_path = $upload_path.$file_name;
		
			if(file_exists($upload_image_path)) 
			{
				$file_name = rand().'_'.$file_name;
				if($userfile_extn[1]=="jpg" || $userfile_extn[1]=="jpeg" || $userfile_extn[1]=="png" || $userfile_extn[1]=="gif" || $userfile_extn[1]=="bmp")
				//Origional Image
				{
					compressImage($validateImageExt,$filetemp,$upload_path,$file_name,100);
				}
				else
				{	
					$file_name = '100_'.$file_name;
					move_uploaded_file($filetemp,$upload_path.$file_name);
					$file_name = $_FILES['file']['name'];
				}
			
			}else{
			
				if($userfile_extn[1]=="jpg" || $userfile_extn[1]=="jpeg" || $userfile_extn[1]=="png" || $userfile_extn[1]=="gif" || $userfile_extn[1]=="bmp")
				{
					compressImage($validateImageExt,$filetemp,$upload_path,$file_name,100);
				}
				else
				{
					$file_name = '100_'.$file_name;
					move_uploaded_file($filetemp,$upload_path.$file_name);
					$file_name = $_FILES['file']['name'];
				}
			//move_uploaded_file($filetemp,$upload_image_path);
				//compressImage($validateImageExt,$filetemp,$upload_path,$file_name,100);
			
				}
			
				
				//$user_name = $obj_tax_calender->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
				//printr($user_name);die;
			//	$img = array('img_nm' => $file_name,'date' => date("Y-m-d"),'user_name' => $user_name['user_name'],'img_path' => HTTP_UPLOAD.'admin/tax_img/100_' .$file_name);
				
				echo  json_encode($file_name);
			}
		
	}else{
	echo 0;	
	}
}
if($fun=='updateImagestatus')
{
	$tax_id = $_POST['tax_id'];
	$status = $_POST['status'];
	$obj_tax_calender->$fun($tax_id,$status);

}
	
?>
