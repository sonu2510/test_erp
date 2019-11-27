<?php
include('mode_setting.php');
$fun=$_GET['fun'];
if($fun=='ajaximage')
{
	if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' && $_FILES['file']['error'] == 0){
	
	$validateImageExt = validateUploadImage($_FILES['file']);
	
		if($validateImageExt){
		
			require_once(DIR_SYSTEM . 'library/resize-class.php');
		
			$upload_path = DIR_UPLOAD.'admin/product_img/';
		
			$file_name = $_FILES['file']['name'];
			$filetemp = $_FILES['file']['tmp_name'];
			$upload_image_path = $upload_path.$file_name;
		
			if(file_exists($upload_image_path)) 
			{
				$file_name = rand().'_'.$file_name;
			
			//Origional Image
			//move_uploaded_file($filetemp,$upload_path.$file_name);
				compressImage($validateImageExt,$filetemp,$upload_path,$file_name,100);
			
			}else{
			
			//move_uploaded_file($filetemp,$upload_image_path);
				compressImage($validateImageExt,$filetemp,$upload_path,$file_name,100);
			
				}
				echo json_encode(HTTP_UPLOAD.'admin/product_img/100_' .$file_name);
			}
	
	}else{
	echo 0;	
	}
}
if($fun=='updateImagestatus')
{
	$product_image_id = $_POST['product_image_id'];
	$status = $_POST['status_value'];
	$obj_product_img->$fun($product_image_id,$status);

}
	
?>