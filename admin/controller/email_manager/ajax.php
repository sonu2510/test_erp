<?php
// Start: Building System
include("mode_setting.php");
$fun = $_GET['fun'];

// End: Building System
if($_GET['fun']=='getUserEmail') {
	if(isset($_POST['user_type_id']) && $_POST['user_type_id'] != ''){
		$user_type_id = $_POST['user_type_id'];
		$data = $obj_useremail->getUserEmail($user_type_id);
	
		if(!empty($data)){
				foreach($data as $data){
					//printr($data);
					echo '<div class="checkbox">';
					echo '	<label class="">';
					echo '	<input class="checkbox1" type="checkbox" name="to[]" id="'.$data['user_id'].'" value="'.$data['email'].'" />';
					echo '	<i class=""></i> '.$data['name'].' ('.$data['email'].') </label>';
					echo '</div>';
				
					if(isset($data['user_type_id']) || !empty($data['user_type_id'])){
							$data1 = $obj_useremail->getempemail($data['user_type_id'],$data['user_id']);
							if(!empty($data1)){
								foreach($data1 as $data2){
									echo  '<div class="col-lg-2"><span class="required"></span></div>';
									echo '<div class="checkbox">';
									echo '	<label class="">';
									echo '	<input class="checkbox1" type="checkbox" name="to[]" id="'.$data2['user_id'].'" value="'.$data2['email'].'" />';
									echo '	<i class=""></i> '.$data2['name'].' ('.$data2['email'].') </label>';
									echo '</div>';
								
								}
							}
					 }
				
       		 	}
			}
		}
	}

if($_GET['fun']=='imageupload'){
	if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' && $_FILES['file']['error'] == 0){
	
	$filetemp = $_FILES['file']['name'];
		$attachment= array();
		if(isset($filetemp) && !empty($filetemp)){
			
			$upload_path = DIR_UPLOAD;
			$file_name = $_FILES["file"]["name"];
			$file_temp = $_FILES["file"]["tmp_name"];
			
			$upload_image_path = $upload_path."/".$file_name;
			if(file_exists($upload_image_path)){
				$file_name = rand().'_'.$file_name;
				uploadfile($file_temp,$file_name,$upload_path);
				$attachment[] = $upload_image_path; 
			}else{
				uploadfile($file_temp,$file_name,$upload_path);
				$attachment[] = $upload_image_path; 
			}
			
			$_SESSION['image'][$_POST['image_id']] = array(
				'image_name'=>$file_name,
				'image_id'=>$_POST['image_id'],
				'image_path'=>$upload_image_path
			);
			
			//printr($_SESSION['image']);die;
		}
		
		
		
		echo json_encode($file_name);
	
	}else{
		echo "No File Found";	

	}
}

if($fun == "removefile"){
	//printr($_SESSION['image']);die;
	if(isset($_SESSION['image'])){
		foreach($_SESSION['image'] as $img){
			if($img['image_id']==$_POST['image_id']){
				$upload_path = DIR_UPLOAD;
				$upload_image_path = $upload_path."/".$img['image_name'];
				unset($_SESSION['image'][$img['image_id']]);
				unlink($upload_path.$img['image_name']);
				//printr($_SESSION['image']);die;
				//unset($_SESSION['attachment']);
			
			}
		}
	}
}
?>