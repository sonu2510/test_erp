<?php
class spout extends dbclass {
	
	public function getvalue(){
		$data = $this->query("SELECT * FROM product_spout ORDER BY spout_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getcount()
	{
		$data = $this->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "product_spout");
		return $data->row['total'];
	}
	
	public function addspout($post){
		$name=$post['name'];
		$abbrevation=$post['abbrevation'];
		$unit=$post['unit'];
		$price=$post['price'];
		$byair=$post['by_air'];
		$bysea=$post['by_sea'];
		$weight=$post['weight'];
		$additional=$post['additional_packaging_price'];
		$additional_pouch=$post['additional_profit_pouch'];
		$serial_no=$post['serial_no'];
		$status=$post['status'];
		$min_qty=$post['min_qty'];
		$wastage=$post['wastage'];
		$weight_temp=$post['weight_temp'];
		//$addeddate=NOW();
		$sql = "INSERT INTO product_spout(product_spout_id,spout_name,spout_abbr,spout_unit,price,spout_min_qty,by_air,by_sea,wastage,weight,additional_packaging_price,additional_profit_pouch,serial_no,weight_temp,status,date_added,date_modify,is_delete) VALUES('','$name','$abbrevation','$unit','$price','$min_qty','$byair','$bysea','$wastage','$weight','$additional','$additional_pouch','$serial_no','$weight_temp','$status',NOW(),NOW(),'')";
		$this->query($sql);
	}
	
	public function getSpout($spout_id){
		$spoutid=$spout_id;
		$sql = "SELECT * FROM product_spout where product_spout_id=$spoutid";
	   //echo $sql;
		//die;
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
	
	public function updatespout($spout_id,$post)
	{
		$price=$post['price'];
		$status=$post['status'];
	    $sql = "UPDATE product_spout SET spout_name='".$post['name']."',spout_abbr='".$post['abbrevation']."',spout_unit='".$post['unit']."',spout_min_qty='".$post['min_qty']."',price='".$post['price']."',by_air='".$post['by_air']."',by_sea='".$post['by_sea']."',serial_no='".$post['serial_no']."',status='".$post['status']."',wastage='".$post['wastage']."',weight='".$post['weight']."',additional_packaging_price='".$post['additional_packaging_price']."',additional_profit_pouch='".$post['additional_profit_pouch']."',weight_temp='".$post['weight_temp']."' where product_spout_id='".$spout_id."'";	
		$data = $this->query($sql);
		
	}
	public function UpdateSpoutStatus($spout_id,$status){
		$sql = "UPDATE product_spout SET status = '".$status."' WHERE product_spout_id = '".$spout_id."'";	
		$data = $this->query($sql);
	}
	
}
?>
