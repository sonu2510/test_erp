<?php
class councharge extends dbclass{	


public function getcountry($data)
	{
		
		$sql = "SELECT country_name,country_id  FROM `" . DB_PREFIX . "country` WHERE country_id=14 OR country_id=42 OR country_id=155 OR country_id= 214 ";
		
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}			
	
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
		$data = $this->query($sql);
		if($data->num_rows){
				return $data->rows;
			}else{
				return false;
		}
	}
public function getcountry_name($country_id)
{		
			$sql = "SELECT country_name,country_id FROM `" . DB_PREFIX . "country` WHERE country_id='".$country_id."'";			
			$data = $this->query($sql);
					if($data->num_rows){
						return $data->row;
					}else{
						return false;
					}
}
	
public function getTotalcountry()
	{
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "country` WHERE country_id=14 OR country_id=42 OR country_id=155 OR country_id= 214 ";
		$data = $this->query($sql);
		return $data->row['total'];
	}
public function adddata($agentname,$country_id,$agentadress,$mailid,$abnno,$cifamount,$fobamount,$customduty,$voti,$gstonimport,$othercharges,$clearingcharges)
	{
		$sql="INSERT INTO import_charges set agent_name='".$agentname."',country_id='".$country_id."',agent_address='".$agentadress."',email_id ='".$mailid."',ABN_no='".$abnno."',CIF_amount='".$cifamount."',
		FOB_amount='".$fobamount."',custom_duty='".$customduty."',voti='".$voti."',Gst_on_import='".$gstonimport."',other_charges='".$othercharges."',clearing_charges='".$clearingcharges."',status = 1,date_added = NOW(),date_modify = NOW(),is_delete=0";		
		//echo $sql;
		$data = $this->query($sql);
		
	}
public function getcountry_details($country_id)
	  {
		  $sql="SELECT * FROM import_charges WHERE status = 1 AND is_delete=0  AND country_id='".$country_id."' ";
		  $data = $this->query($sql);
		  //printr($data);
			  if($data->num_rows){
					return $data->rows;
				}else{
					return false;
				}
		 
	  }
public function getcountry_data($Agent_id,$country_id)
	  {
		  $sql="SELECT * FROM import_charges WHERE status = 1 AND is_delete=0  AND Agent_id='".$Agent_id."' AND country_id='".$country_id."' ";
		  $data = $this->query($sql);
		  //printr($data);
		  if($data->num_rows){
				return $data->row;
			}else{
				return false;
			}
		 
	  }
}
?>