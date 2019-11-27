<?php
class test extends dbclass{
	
	public function getTemplate($volume){
		$sql = "SELECT product_template_size_id FROM `product_template` as p, product_template_size as ps WHERE p.product_name=3 and p.product_template_id=ps.template_id AND ps.spout='No Spout' AND volume='".$volume."' AND p.is_delete = '0' ";
		$data = $this->query($sql);
	//	printr($data);die;
		if($data->num_rows > 0){
			return $data->rows;
		}else{
			return 0;
		}
	}	
	
	public function setTemplate($gusset,$id){
		$sql = "UPDATE product_template_size SET gusset=".$gusset." WHERE product_template_size_id=".$id." ";
		$data = $this->query($sql);
	}
	
	public function getUser(){
		$sql = "SELECT international_branch_id FROM `" . DB_PREFIX . "international_branch` WHERE is_delete = '0' ";
		$data = $this->query($sql);
		//printr($data);die;
		if($data->num_rows > 0){
			return $data->rows;
		}else{
			return 0;
		}
	}	
	public function getpermission($user_id,$user_type_id){
		$sql = "SELECT delete_permission FROM " . DB_PREFIX . "account_master WHERE user_type_id = '".$user_type_id."' AND user_id='".$user_id."'";
		$data = $this->query($sql);
		//printr(unserialize($data->row['view_permission']));die;
		if($data->num_rows > 0){
			return $data->row['delete_permission'];
		}else{
			return 0;
		}
	}
	public function updatepermission($user_id,$permission,$user_type_id){
		$sql = "UPDATE account_master account_master SET delete_permission = '".$permission."' WHERE user_type_id = '".$user_type_id."' AND user_id='".$user_id."'";
		//echo $sql.'<br>';
		$data = $this->query($sql);
	}
	
	public function getemp($user_id){
		$sql = "SELECT employee_id FROM " . DB_PREFIX . "employee WHERE is_delete = '0' AND user_type_id = '4' AND user_id='".$user_id."'";
		$data = $this->query($sql);
		//printr($data);die;
		if($data->num_rows > 0){
			return $data->rows;
		}else{
			return 0;
		}
	}
}
?>