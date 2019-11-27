<?php
include('mode_setting.php');
/*if($_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1')
    printr($_FILES);die;*/
if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' && $_FILES['file']['error'] == 0){
		
		$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		$validateImageExt = validateUploadImage($_FILES['file']);
		if($ext == 'pdf'){
			$validateImageExt = validateUploadPdf($_FILES['file']);			
			$upload_path = DIR_UPLOAD.'admin/dielineForJob/';				
			$file_name = $_FILES['file']['name'];
			$filetemp = $_FILES['file']['tmp_name'];	
			$upload_file_path = $upload_path.$file_name;				
			if(file_exists($upload_file_path)) 
			{
				$file_name = rand().'_'.$file_name;
				move_uploaded_file($filetemp,$upload_path.$file_name);
			}else{
				move_uploaded_file($filetemp,$upload_file_path);
			}
			
			$_SESSION['product_die_line'][$_POST['die_id']]=array(
				'die_name' => $file_name,
				'die_id' => $_POST['die_id'],
				'die_ext' => 'pdf'
			);
			$return_array = array(
				'ext' => 'pdf',
				'name' => HTTP_UPLOAD.'admin/dielineForJob/' .$file_name 
			);			
			echo json_encode($return_array);
		}else if($validateImageExt){
			$upload_path = DIR_UPLOAD.'admin/dielineForJob/';				
			$file_name = $_FILES['file']['name'];
			$filetemp = $_FILES['file']['tmp_name'];
			$upload_file_path = $upload_path.$file_name;		
			require_once(DIR_SYSTEM . 'library/resize-class.php');			
			if(file_exists($upload_file_path)) 
			{
				$file_name = rand().'_'.$file_name;
				if(file_exists($upload_file_path)) 
				{
					move_uploaded_file($filetemp,$upload_path.$file_name);
				}else{
					move_uploaded_file($filetemp,$upload_file_path);
				}
				$widthArray = array(500,100); //You can change dimension here.
				foreach($widthArray as $newwidth){
					compressImage($validateImageExt,$filetemp,$upload_path,$file_name,$newwidth);
				}
			}else{
				$widthArray = array(500,100); //You can change dimension here.
				foreach($widthArray as $newwidth){
					compressImage($validateImageExt,$filetemp,$upload_path,$file_name,$newwidth);
				}
				if(file_exists($upload_file_path)) 
				{
					move_uploaded_file($filetemp,$upload_path.$file_name);
				}else{
					move_uploaded_file($filetemp,$upload_file_path);
				}				
			}
			$_SESSION['product_die_line'][$_POST['die_id']]=array(
				'die_name' => $file_name,
				'die_id' => $_POST['die_id'],
				'die_ext' => 'img'
			);
			$return_array = array(
				'ext' => 'img',
				'name' => HTTP_UPLOAD.'admin/dielineForJob/100_' .$file_name 
			);
			echo json_encode($return_array);			
		}else{
			echo 0;
		}	
}else{
	echo 0;	
}

	
?>