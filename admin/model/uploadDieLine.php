<?php
include('mode_setting.php');
if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' && $_FILES['file']['error'] == 0){
	
	
		//printr($_FILES['file']);die;
		$validateImageExt = validateUploadImage($_FILES['file']);
		
		$upload_path = DIR_UPLOAD.'admin/dieline/';
			
		$file_name = $_FILES['file']['name'];
		$filetemp = $_FILES['file']['tmp_name'];

		$upload_file_path = $upload_path.$file_name;
		
		$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		if($ext == 'pdf'){
			
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
			
			//$obj_order->insertProductImage($_POST['product_id'],$file_name);
			
			//echo "uploaded";die;
			$return_array = array(
				'ext' => 'pdf',
				'name' => $file_name 
			);
			
			echo json_encode($return_array);
			
		}else if($validateImageExt){
			
			require_once(DIR_SYSTEM . 'library/resize-class.php');
			
			if(file_exists($upload_file_path)) 
			{
				$file_name = rand().'_'.$file_name;
			
				$widthArray = array(1000,500,200,100,50); //You can change dimension here.
				foreach($widthArray as $newwidth){
					compressImage($validateImageExt,$filetemp,$upload_path,$file_name,$newwidth);
				}
				
			}else{
				$widthArray = array(1000,500,200,100,50); //You can change dimension here.
				foreach($widthArray as $newwidth){
					compressImage($validateImageExt,$filetemp,$upload_path,$file_name,$newwidth);
				}
			}
			
			$_SESSION['product_die_line'][$_POST['die_id']]=array(
				'die_name' => $file_name,
				'die_id' => $_POST['die_id'],
				'die_ext' => 'img'
			);
			
			$return_array = array(
				'ext' => 'img',
				'name' => HTTP_UPLOAD.'admin/dieline/100_' .$file_name 
			);
			
			echo json_encode($return_array);
			
		}else{
			echo 0;
		}
	
}else{
	echo 0;	
}

	
?>