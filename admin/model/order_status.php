<?php
class orderstatus extends dbclass
{
	public function getvalue()
	{
		$sql = "SELECT * FROM order_status ORDER BY status_name";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function geteditvalue($orderid)
	{
		$sql = "SELECT * FROM order_status where order_status_id = '".$orderid."'";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row;
		}else
		{
			return false;
		}
	}
	public function getcountstatus(){
		$data = $this->query("SELECT COUNT(*) as total FROM order_status");
		return $data->row['total'];
	}
		
	public function insertorder($post)
	{
		//printr($post);
		//die;
		$sql = "INSERT INTO order_status(status_name, status, date_added, is_delete) VALUES('".$post['name']."', '".$post['status']."', NOW(), '1')";
		$this->query($sql);
	}
	
	public function OrderUpdatestatus($orderid,$post)
	{
		//printr($post);
		//die;
		$sql = "UPDATE order_status SET status_name = '".$post['name']."', status = '".$post['status']."', date_modify = NOW(), is_delete = '0' WHERE order_status_id='".$orderid."'";
		$data = $this->query($sql);	
		
	}
		
	public function UpdateOrderStatus($orderid,$orderstatus)
	{
		$sql = "UPDATE order_status SET status='".$orderstatus."', date_modify = NOW(), WHERE order_status_id = '".$orderid."'";
		$data = $this->query($sql);
	}
	
	
}
?>