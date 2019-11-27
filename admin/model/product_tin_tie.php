<?php
class product_tin_tie extends dbclass {
	
	public function getvalue(){
		$data = $this->query("SELECT * FROM product_tintie ORDER BY tintie_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getcount()
	{
		$data = $this->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "product_tintie");
		return $data->row['total'];
	}
	
	public function addTintie($post){
		$name=$post['name'];
		$unit=$post['unit'];
		$price=$post['price'];
		//$byair=$post['by_air'];
		//$bysea=$post['by_sea'];
		$status=$post['status'];
		$min_qty=$post['min_qty'];
		$wastage=$post['wastage'];
		$sql = "INSERT INTO product_tintie(product_tintie_id,tintie_name,tintie_unit,price,tintie_min_qty,status,date_added,date_modify,is_delete,wastage) VALUES('','$name','$unit','$price','$min_qty','$status',NOW(),NOW(),'','$wastage')";
		
		$this->query($sql);
	}
	
	public function getTintie($product_tintie_id){
		$tintie_id=$product_tintie_id;
		$sql = "SELECT * FROM product_tintie where product_tintie_id=$tintie_id";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row;
		}
		else
		{
			return false;
		}
	}
	
	public function updateTintie($product_tintie_id,$post)
	{
		$price=$post['price'];
		$status=$post['status'];
	    $sql = "UPDATE product_tintie SET tintie_name='".$post['name']."',tintie_unit='".$post['unit']."',tintie_min_qty='".$post['min_qty']."',price='".$post['price']."',status='".$post['status']."',wastage='".$post['wastage']."'
		where product_tintie_id='".$product_tintie_id."'";	
		$data = $this->query($sql);
		
	}
	public function UpdateTintieStatus($product_tintie_id,$status){
		$sql = "UPDATE product_tintie SET status = '".$status."' WHERE product_tintie_id = '".$product_tintie_id."'";	
		$data = $this->query($sql);
	}
	
}
?>
