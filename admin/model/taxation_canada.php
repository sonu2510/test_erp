<?php
class taxation_canada extends dbclass{

	public function getLabel(){
	
		$sql = "SELECT * FROM taxation_canada WHERE is_delete = '0'";
		//echo $sql;
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->rows;
		}
		else {
			return false;
		}
	}	
	
	public function TaxUpdate($taxation_canada_id,$tax_gst,$tax_rst,$tax_hst,$price,$price_e,$abb)
	{
		$sql = "UPDATE taxation_canada SET gst='".$tax_gst."',rst='".$tax_rst."',hst='".$tax_hst."',price_normal_shipping='".$price."',price_express_shipping='".$price_e."',abbreviation = '".$abb."',date_modify = NOW() WHERE taxation_canada_id = '" .(int)$taxation_canada_id. "'";
		$data = $this->query($sql);
			
	}
	
	public function getCouriers(){
		
		$sql = "SELECT * FROM " . DB_PREFIX . "courier WHERE is_delete = 0 AND status=1";
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}	
	}

} 
?>