<?php
class client_quantity_master extends dbclass{
		
		public function addQty($post){
			foreach($post['qty_master'] as $qty){
				$sql = "INSERT INTO client_quantity_master SET from_qty = '".$qty['from_qty']."',to_qty = '".$qty['to_qty']."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',date_added = NOW(),is_delete=0 ";
				$data = $this->query($sql);
			
			}
		}
		
		public function updateQty($post) {
			 foreach($post['qty_master'] as $UpdateQty){
			 // mansi 23-12-2015
			 if(isset($UpdateQty['client_qty_id']) && $UpdateQty['client_qty_id']!=''){
			 
				$sql = "UPDATE client_quantity_master SET from_qty = '".$UpdateQty['from_qty']."',to_qty = '".$UpdateQty['to_qty']."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."', date_modify = NOW() WHERE client_qty_id = '" .$UpdateQty['client_qty_id']. "' ";
				$this->query($sql);
				
				/*$sql2="UPDATE client_product_quantity_master SET from_qty = '".$UpdateQty['from_qty']."',to_qty = '".$UpdateQty['to_qty']."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."', date_modify = NOW() WHERE client_qty_id = '" .$UpdateQty['client_qty_id']. "' ";
				$this->query($sql2);*/
				//echo $sql;
			 }
			 else
			 {
			 
			 	$sql = "INSERT INTO client_quantity_master SET from_qty = '".$UpdateQty['from_qty']."',to_qty = '".$UpdateQty['to_qty']."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',date_added = NOW(),date_modify = NOW(),is_delete=0 ";
			 	$this->query($sql);
				//$last_inserted_id = $this->getLastId();
				/*$select_id = $last_inserted_id - 1;
				$sql_select = "SELECT * FROM client_product_quantity_master WHERE client_qty_id = '" .$select_id. "'";
				$sel = $this->query($sql_select);*/
				//printr($sel);
				//$sql = "INSERT INTO client_quantity_master SET from_qty = '".$UpdateQty['from_qty']."',to_qty = '".$UpdateQty['to_qty']."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',date_added = NOW(),date_modify = NOW(),is_delete=0 "
				//echo $sql;
			 }
			
			}
			//die;			
		}
	
		public function getQty($client_qty_id){
		
			$sql = "SELECT * FROM  client_quantity_master WHERE client_qty_id='".(int)$client_qty_id."'";
		
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}else{
				return false;
			}
	   }
	   
	   public function getTotalQty(){
	   
	   		$sql = "SELECT COUNT(*) as total FROM client_quantity_master WHERE  is_delete = '0'";
		
			$data=$this->query($sql);	
			
			return $data->row['total'];
	   
	   }
	
	   public function getQtyPrice(){
		$sql = "SELECT * FROM client_quantity_master WHERE  is_delete = '0' ORDER BY from_qty ASC";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function deleteQty($id){
	
		$this->query("DELETE FROM " . DB_PREFIX . "client_quantity_master WHERE client_qty_id = '".(int)$id."'");	
	}
	
	public function delQty($client_qty_id){
	
		$this->query("DELETE FROM " . DB_PREFIX . "client_quantity_master WHERE client_qty_id = '".(int)$client_qty_id."' AND is_delete=0 ");
	}
	
}

	
