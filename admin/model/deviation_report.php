<?php 
class deviation_report extends dbclass{
	
	public function deviationdetail()
	{
		$sql = "SELECT invoice_no,invoice_id,custom_duty_deviation_per,GST_on_import_deviation_per,other_charges_deviation_per,clearing_charges_deviation_per,need_clarification,close_status FROM invoice  WHERE is_delete=0 AND transportation= 'c2Vh' AND ( custom_duty_deviation_per !=0 OR GST_on_import_deviation_per != 0 OR other_charges_deviation_per!= 0 OR clearing_charges_deviation_per!= 0 )";
		$data=$this->query($sql);
	//	printr($data);
		if($data->num_rows){
			return $data->rows;
		}
		else {
			return false;
		}
		
	}
	public function reportdetails ($invoice_id)
	{
		$sql = "SELECT invoice_no,custom_duty_deviation_per,GST_on_import_deviation_per,other_charges_deviation_per,clearing_charges_deviation_per,need_clarification,close_status FROM invoice  WHERE  is_delete=0 AND transportation= 'c2Vh' AND invoice_id = '".$invoice_id."' ";
		$data=$this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
		
		}
	
	public function updateclarification($invoice_id,$clarification)
	{
		//printr($data);
		
		$sql = "UPDATE `" . DB_PREFIX . "invoice` SET need_clarification ='".$clarification."' WHERE  invoice_id='".$invoice_id."'";	
		//echo $sql;
		$data=$this->query($sql);
	}
	public function changestatus($invoiceid)
	{
		$sql = "UPDATE `" . DB_PREFIX . "invoice` SET close_status= '1' WHERE  invoice_id='".$invoiceid."'";	
	    $data=$this->query($sql);
	
	}
	
}
?>
