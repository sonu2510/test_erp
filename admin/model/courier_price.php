<?php
class courier_price extends dbclass{

	//modified by jayashree
	
	public function getdefaultcurrency()
	{
		$sql = "SELECT * FROM country where status = 1 and country_code!='' and currency_code!='' group by currency_code";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}
		
	}
	
	/*jaya */
	
	public function numberFormate($number,$decimalPoint=3){
		return number_format($number,$decimalPoint,".","");
	}
	
	
	public function getCountryCourierCharge($country_id,$courier_id,$weight){
	
		$weight = $this->numberFormate(($weight),"2");
		$zdata = $this->query("SELECT courier_zone_id FROM " . DB_PREFIX . "cust_courier_zone_country WHERE country_id = '".$country_id."' AND courier_id = '".$courier_id."'");		
		if(isset($zdata->row['courier_zone_id']) && $zdata->row['courier_zone_id']){
			$courier_zone_id = $zdata->row['courier_zone_id'];
		}else{
			$courier_zone_id = 1;
		}
		$data = $this->query("SELECT czp.price,czp.from_kg,czp.to_kg,c.fuel_surcharge,c.service_tax,c.handling_charge,c.courier_id,c.courier_name,cz.zone FROM " . DB_PREFIX . "cust_courier_zone_price as czp,cust_courier as c,courier_zone as cz WHERE czp.courier_id = '".$courier_id."' AND czp.courier_zone_id = '".$courier_zone_id."' AND czp.from_kg <= '".$weight."' AND czp.to_kg >= '".$weight."' AND czp.courier_id=c.courier_id AND cz.courier_zone_id='".$courier_zone_id."' ");	
		//printr($data);
		if(isset($data->row['price']) && $data->row['price'])
		{
			$price = $data->row['price'];
			$baseKg = $data->row['to_kg'];
			$perKgPrice =$data->row['price']/$data->row['to_kg'];
		}
		else
		{
			$data = $this->query("SELECT czp.to_kg, czp.price,c.fuel_surcharge,c.service_tax,c.handling_charge,c.courier_name FROM " . DB_PREFIX . "cust_courier_zone_price as czp,cust_courier as c WHERE czp.courier_id = '".$courier_id."' AND czp.courier_zone_id = '".$courier_zone_id."' AND czp.courier_id=c.courier_id ORDER BY czp.to_kg DESC LIMIT 0,1 ");
			$baseKg = $data->row['to_kg'];
			$basePrice = $data->row['price'];
			$perKgPrice = ($basePrice / $baseKg);
			$price = ($weight * $perKgPrice);
		}
		$price=array('price'=>$price,
					'to_kg'=>$baseKg,
					'PerKgPrice'=>$perKgPrice,
					'fuel_charge'=>$data->row['fuel_surcharge'],
					'service_tax'=>$data->row['service_tax'],
					'handling_charge'=>$data->row['handling_charge'],
					'courier_name'=>$data->row['courier_name'],
					'courier_zone'=>$data->row['zone']);
		//printr($price);
		return $price;
	}
	
	public function getCountryAllCourier($country_id)
	{
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "cust_courier_zone_country WHERE country_id = '".$country_id."'");		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	
	}
	
	
	public function get_price($data)
	{
		$countryCourierData = $this->getCountryAllCourier($data['country_id']);
		$price_data=array();
		if(!empty($countryCourierData))
		{
			foreach($countryCourierData as $ctr_courier)
			{
				$courierChargeBaseZipper = $this->getCountryCourierCharge($data['country_id'],$ctr_courier['courier_id'],$data['weight']);
				$price_data[$courierChargeBaseZipper['courier_name']]=$courierChargeBaseZipper;
			}
		}
		
		return $price_data;
	}
	//end 
}
?>