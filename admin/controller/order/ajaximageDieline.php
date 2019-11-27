<?php
include('mode_setting.php');
if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' && $_FILES['file']['error'] == 0){
	
	
	$validateImageExt = validateUploadImage($_FILES['file']);
	
	if($validateImageExt){
		
		require_once(DIR_SYSTEM . 'library/resize-class.php');
		
		$upload_path = DIR_UPLOAD.'admin/dieline/';
		
		/*if(file_exists($upload_path.'50_'.$_FILES['file']['name'])){
			unlink($upload_path.'50_'.$_FILES['file']['name']);
			unlink($upload_path.'100_'.$_FILES['file']['name']);
			unlink($upload_path.'200_'.$_FILES['file']['name']);
		}*/
		
		$file_name = $_FILES['file']['name'];
		$filetemp = $_FILES['file']['tmp_name'];
		$upload_image_path = $upload_path.$file_name;
		//echo $validateImageExt;die;

		if(file_exists($upload_image_path)) 
		{
			$file_name = rand().'_'.$file_name;
			
			//Origional Image
			//move_uploaded_file($filetemp,$upload_path.$file_name);
			
			//Re-sizing image.
			$widthArray = array(1000,500,200,100,50); //You can change dimension here.
			foreach($widthArray as $newwidth)
			{
				compressImage($validateImageExt,$filetemp,$upload_path,$file_name,$newwidth);
			}
		}else{
			
			//Origional Image
			//move_uploaded_file($filetemp,$upload_image_path);
			
			//Re-sizing image. 
			$widthArray = array(1000,500,200,100,50); //You can change dimension here.
			foreach($widthArray as $newwidth)
			{
				compressImage($validateImageExt,$filetemp,$upload_path,$file_name,$newwidth);
			}
		}
		
		$_SESSION['product_die_line'][$_POST['die_id']]=array(
			'die_name' => $file_name,
			'die_id' => $_POST['die_id'],
			'order_id' => $_POST['order_id']
		);
		
		//$obj_order->insertProductImage($_POST['product_id'],$file_name);
		
		echo json_encode(HTTP_UPLOAD.'admin/dieline/100_' .$file_name);

	}
	
}else{
	echo 0;	
}

	
?>