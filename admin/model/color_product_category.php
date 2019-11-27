<?php 
class color_product_category extends dbclass{
	
	public function all_product()
	{
		 $sql = "SELECT * FROM product WHERE is_delete = 0 AND status = 1 ORDER BY product_name ASC";
		
		 $data = $this->query($sql);
		
		 if($data->num_rows){
			return $data->rows; 
			}
			else{
				return false;
			}
	}
	
	public function all_volume()
	{
		$sql = "SELECT * FROM pouch_volume WHERE is_delete = 0 AND status = 1 ORDER BY volume ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;	
		}
		else{
			return false;
		}	
	}
	
	public function all_colors()
	{
		$sql = "SELECT * FROM pouch_color  WHERE is_delete = 0 AND status='1' ORDER BY color ASC";

		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}
		else{
				return false;
		}	
	}
	
	public function getColor_detail($color_id){
		//printr($color_id);
		$sql = "SELECT * FROM pouch_color WHERE is_delete = '0' AND status='1'AND pouch_color_id='".$color_id."' ";
		$data = $this->query($sql);
		//echo $sql;die;
		if($data->num_rows)
		{
			return $data->row;
		}
		else
		{
				return false;
		}	
	}
	
	
	public function selected_color($product,$volume)
	{
		$sql = "SELECT * FROM   pouch_color p LEFT OUTER JOIN volume_wise_color v ON p.`pouch_color_id` = v.color_id AND v.product_id = '".$product."' and v.volume_id='".$volume."' WHERE p.is_delete = 0 AND p.status='1' and v.color_id IS NULL ORDER BY color ASC;";
		//echo $sql."----------> SELCTED COLOR QUERY";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}
		else{
			return false;
		}	
	}
	
	public function available($product,$volume)
	{
		$sql = "select * from pouch_color p join volume_wise_color v on p.pouch_color_id=v.color_id where product_id='".$product."' and volume_id='".$volume."' and p.is_delete = 0 AND p.status='1' group by v.color_id ORDER BY color ASC";
		//echo '<br>'.$sql."----------> AVAILABLE COLOR QUERY";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}
		else{
			return false;
		}	
	}

		public function update_color($product,$volume,$color_id)
		{  	//printr($product);die;
			$sql = "INSERT INTO  volume_wise_color SET product_id='".$product."', volume_id='".$volume."',color_id='".$color_id."', date_added = NOW(),date_modify = NOW() ";
			//echo $sql;die;
			$this->query($sql);	
		}
		
		public function delete_color($product,$volume,$color_id)
		{  	//printr($product);die;
			$sql = "DELETE FROM volume_wise_color WHERE color_v_id='".$color_id."'";
			//echo $sql;die;
			$this->query($sql);	
		}
}
?>