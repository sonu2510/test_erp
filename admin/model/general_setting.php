<?php
class generalSetting extends dbclass{
	
	public function getAllSettings(){
		
		$sql = "SELECT * FROM " . DB_PREFIX . "general_setting WHERE is_delete = 0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}				
	}
	
	public function uploadLogoImage($data){
		//printr($data);die;
		
		if(isset($data['name']) && $data['name'] != '' && $data['error'] == 0){
			
			$validateImageExt = false;
			
			$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP","ico");
			$imagename = $data['name'];
			if(strlen($imagename))
			{
				$ext = strtolower(getExtension($imagename));
				if(in_array($ext,$valid_formats)){
					$validateImageExt = true;
				}
			}
			
			if($validateImageExt){
				require_once(DIR_SYSTEM . 'library/resize-class.php');
				
				$upload_path = DIR_UPLOAD.'admin/slogo/';
				
				$file_name = $data["name"];
				$filetemp = $data["tmp_name"];
				$upload_image_path = $upload_path."/".$file_name;
				
				if(file_exists($upload_image_path)) 
				{
					$file_name = rand().'_'.$file_name;
					uploadfile($filetemp,$file_name,$upload_path);
				}else{
					uploadfile($filetemp,$file_name,$upload_path);
				}
				
				if($file_name){
					return $file_name;
				}else{
					return false;
				}
			}
		}
	}
	
	public function addSetting($serialize_data){
		
		$sql = "INSERT INTO `" . DB_PREFIX . "general_setting` SET setting_details = '".$serialize_data."', status = '1',date_added=NOW(),date_modify=NOW(),is_delete=0";
		
		$this->query($sql);			
	}
	
	public function updateSetting($serialize_data,$id){
		$sql = "UPDATE `" . DB_PREFIX . "general_setting` SET setting_details = '".$serialize_data."',status = '1',date_modify=NOW(),is_delete=0 WHERE general_setting_id='".$id."'";
		
		$this->query($sql);				
	}
	
}
?>