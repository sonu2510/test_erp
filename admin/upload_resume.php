<?php

if(isset($_FILES['attachments']['name']) && $_FILES['attachments']['name'] != '' && $_FILES['attachments']['error'] == 0){
		
		$ext = pathinfo($_FILES['attachments']['name'], PATHINFO_EXTENSION);

			$upload_path = DIR_UPLOAD.'admin/career_resume/';	
			
			$file_name = $_FILES['attachments']['name'];
			$filetemp = $_FILES['attachments']['tmp_name'];	
			$upload_file_path = $upload_path.$file_name;				
			if(file_exists($upload_file_path)) 
			{
				$file_name = rand().'_'.$file_name;
				move_uploaded_file($filetemp,$upload_path.$file_name);
			}else{
				move_uploaded_file($filetemp,$upload_file_path);
			}
			
			
			$return_array = array(
				'ext' => $ext,
				'name' => HTTP_UPLOAD.'admin/career_resume/' .$file_name 
			);			
			echo json_encode($return_array);
			
}else{
	echo 0;	
}

	
?>