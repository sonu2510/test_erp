<?php
class government_sales_invoice extends dbclass{	
	

	public function getLable($invoice_id,$table)
	{
		$data = $this->query("SELECT * FROM " .$table." WHERE invoice_id=".$invoice_id." AND status = '0' AND is_delete = '0' ORDER BY box_no ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getInvoiceProductWithBox($invoice_id)
	{
		//printr($invoice_id);
		//$sql ="SELECT * FROM invoice AS i LEFT JOIN invoice_product AS ip ON (i.invoice_id=ip.invoice_id) LEFT JOIN invoice_color AS ic ON (i.invoice_id=ic.invoice_id AND ip.invoice_product_id=ic.invoice_product_id) LEFT JOIN box_master AS bm ON (bm.pouch_volume = ic.size AND bm.pouch_volume_type=ic.measurement AND bm.product_id = ip.product_id AND bm.zipper=ip.zipper AND bm.valve=ip.valve AND bm.spout=ip.spout AND bm.accessorie=ip.accessorie AND bm.make_pouch=ip.make_pouch AND bm.transportation=i.transportation AND bm.is_delete=0) WHERE i.invoice_id=".$invoice_id." ORDER BY ip.product_id ASC,bm.pouch_volume DESC,ic.qty DESC";
		
		$sql ="SELECT * FROM invoice_test AS i LEFT JOIN invoice_product_test AS ip ON (i.invoice_id=ip.invoice_id) LEFT JOIN invoice_color_test AS ic ON (i.invoice_id=ic.invoice_id AND ip.invoice_product_id=ic.invoice_product_id) LEFT JOIN box_master AS bm ON (bm.pouch_volume = ic.size AND bm.pouch_volume_type=ic.measurement AND bm.product_id = ip.product_id AND bm.zipper=ip.zipper AND bm.valve=ip.valve AND bm.spout=ip.spout AND bm.accessorie=ip.accessorie AND bm.make_pouch=ip.make_pouch AND bm.transportation=i.transportation AND bm.is_delete=0) WHERE i.invoice_id=".$invoice_id." ORDER BY ip.invoice_product_id ASC";
		
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}		
	}
	
	public function in_gen_box_uni_id()
	{
		$data = $this->query("SELECT Max(box_unique_id) as box_uni_no FROM in_gen_invoice_test WHERE is_delete=0");
		$box_uni = $data->row['box_uni_no'];
		return $box_uni;
	}
	
	
	public function out_gen_box_uni_id()
	{
		$data = $this->query("SELECT Max(box_unique_id) as box_uni_no FROM out_lable_invoice");
		$box_uni = $data->row['box_uni_no'];
		return $box_uni;
	}
	

	
	public function getInvoiceNetData($invoice_id)
	{
		$sql = "SELECT SUM(ip.net_weight) as net_weight,SUM(ip.gross_weight) as gross_weight, i.* FROM  " . DB_PREFIX . "invoice_test as i,invoice_product_test as ip WHERE i.invoice_id =ip.invoice_id  AND i.invoice_id = '" .(int)$invoice_id. "' AND i.is_delete=0";
		//echo $sql;
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getInvoiceTotalData($invoice_id)
	{
		$sql = "SELECT SUM(ic.qty) as total_qty,SUM(ic.rate) as total_rate,ic.rate,SUM(ic.qty*ic.rate) as tot FROM  " . DB_PREFIX . "invoice_test as i,invoice_color_test as ic WHERE i.invoice_id = '" .(int)$invoice_id. "' 
		AND i.is_delete=0 AND i.invoice_id=ic.invoice_id ";
		$data = $this->query($sql);
     //   echo $sql;
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	 
	public function getInvoiceProductId($invoice_product_id)
	{
		$sql = "SELECT * FROM  government_sales_invoice_product WHERE sales_invoice_product_id = '" .(int)$invoice_product_id. "'  AND is_delete=0 ";
			//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}		
	}
	
	public function getUser($user_id,$user_type_id)
	{
	   // printr($user_type_id.'=='.$user_id);
		if($user_type_id == 1){
			$sql = "SELECT u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 2){
			/*$sql = "SELECT e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";*/
			$sql = "SELECT e.user_name, e.user_id,co.country_id, e.multi_quotation_price,ib.company_address,ib.company_name,ib.default_curr,co.country_name, e.first_name, e.last_name,ib.international_branch_id, ib.email_confirm,e.email_signature, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.international_branch_id as user_id,ib.user_name,co.country_id,ib.gst,ib.company_address,ib.default_curr,ib.company_name,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			
			$sql = "SELECT e.user_name, e.user_id,co.country_id, e.multi_quotation_price,ib.company_address,ib.company_name,ib.default_curr,co.country_name, e.first_name, e.last_name,ib.international_branch_id, ib.email_confirm,e.email_signature, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getCityName()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "invoice_city` WHERE is_delete = '0' ORDER BY invoice_city_id ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getCountry()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "country` WHERE is_delete = '0'  ORDER BY country_id ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getMeasurement()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "template_measurement` WHERE is_delete = '0' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getColor()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_color` WHERE is_delete = '0' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getCurrency()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "currency` WHERE is_delete = '0' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProduct()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND  product_id IN(6,64,67,68,69,51) AND is_delete = '0' ORDER BY product_name ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getALLProduct()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY product_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
		
	public function getActiveProductReport()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND  product_id IN (10,23,11,18,6,34,47,48,63,37,38,61,35,64,62,72) AND is_delete = '0' ";
		$sql .= " ORDER BY product_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductZippers()
	{
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_zipper` WHERE status='1' AND is_delete = '0' ORDER BY zipper_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductSpout()
	{
		$data = $this->query("SELECT product_spout_id, spout_name, price FROM " . DB_PREFIX . "product_spout WHERE status = '1' AND is_delete = '0' ORDER BY spout_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductAccessorie()
	{
		$data = $this->query("SELECT product_accessorie_id, product_accessorie_name, price FROM " . DB_PREFIX . "product_accessorie WHERE status = '1' AND is_delete = '0' ORDER BY price ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveMake()
	{
		$sql = "SELECT make_id,make_name FROM `" . DB_PREFIX . "product_make` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY make_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getColorDetails($invoice_id,$invoice_product_id) 
	{	
		$sql = "select ic.*,tm.measurement,pc.color from `".DB_PREFIX."invoice_color_test` as ic,template_measurement as tm,pouch_color as pc WHERE invoice_id='".$invoice_id."' AND invoice_product_id = '".$invoice_product_id."' AND ic.measurement=tm.product_id AND ic.color=pc.pouch_color_id ";
	//	echo $sql;
		$data = $this->query($sql);
		if($data->num_rows) {
			return $data->rows;
		}
		else {
			return false;
		}
	}
	public function getColordesc($invoice_id) 
	{	
		//$sql="select ic.*,tm.measurement,pc.color,p.product_name from invoice_color as ic,template_measurement as tm,pouch_color as pc,invoice_product as ip,product as p WHERE ic.invoice_id='".$invoice_id."' AND ic.measurement=tm.product_id AND ic.color=pc.pouch_color_id AND ic.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id";
		//$sql="select ic.qty,ig.qty as genqty,ic.qty-ig.qty from (select qty ,invoice_color_id,invoice_id from invoice_color) as ic left join (select qty,invoice_color_id from in_gen_invoice GROUP BY invoice_color_id) as ig on (ic.invoice_color_id=ig.invoice_color_id) where ic.invoice_id='".$invoice_id."'";
		
		
		
		
		$sql="select ic.qty,ig.qty as genqty,ic.color,ic.dimension,ic.invoice_color_id,ic.color_text,ic.net_weight,ic.size,ic.measurement,ic.invoice_product_id,ic.product_name,ifnull(ic.qty-ig.qty,ic.qty)  as remaingqty from (select ii.dimension,ii.qty,ii.size,ii.color_text,tm.measurement,ii.invoice_product_id,p.product_name ,ii.invoice_color_id,ii.invoice_id,c.color,ii.net_weight from invoice_color_test as ii,pouch_color as c,template_measurement  as tm,invoice_product_test as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id) as ic left join (select sum(qty) as qty,invoice_color_id from in_gen_invoice_test GROUP BY invoice_color_id) as ig on (ic.invoice_color_id=ig.invoice_color_id) where ic.invoice_id='".$invoice_id."'";
		$data = $this->query($sql);
		
		//[kinjal] made for custom on 7-4-2017
		$color = $this->query("SELECT * FROM invoice_color_test WHERE invoice_id='".$invoice_id."' AND color='-1'");
		if($color->num_rows)
		{
			$sql1="select ic.qty,ig.qty as genqty,ic.dimension,ic.color,ic.invoice_color_id,ic.color_text,ic.net_weight,ic.size,ic.measurement,ic.invoice_product_id,ic.product_name,ifnull(ic.qty-ig.qty,ic.qty)  as remaingqty from (select ii.dimension,ii.qty,ii.size,ii.color_text,tm.measurement,ii.invoice_product_id,p.product_name ,ii.invoice_color_id,ii.invoice_id,ii.color,ii.net_weight from invoice_color_test as ii,template_measurement  as tm,invoice_product_test as ip,product as p where tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id AND ii.color='-1' ) as ic left join (select sum(qty) as qty,invoice_color_id from in_gen_invoice_test GROUP BY invoice_color_id) as ig on (ic.invoice_color_id=ig.invoice_color_id) where ic.invoice_id='".$invoice_id."'";
			$data1 = $this->query($sql1);
			foreach($data1 as $clr)
			{
				array_push($data->rows,$clr);
			}
		}
		
		if($data->num_rows) {
			return $data->rows;
		}
		else {
			return false;
		}
	}
	public function in_gen_box_no($invoice_id)
	{
		$data = $this->query("SELECT box_no FROM in_gen_invoice_test WHERE invoice_id='".$invoice_id."' AND is_delete=0  ORDER BY box_no DESC LIMIT 1");
		//printr("SELECT box_no FROM in_gen_invoice WHERE invoice_id='".$invoice_id."' ORDER BY box_no DESC LIMIT 1");
		
		if($data->num_rows)
			$box_no = $data->row['box_no'];
		else
			$box_no='';

		return $box_no;
	}
	public function savelabeldetail($postdata)
	{
		/*$data=$this->getInvoiceColorlable($postdata['detail']);
		printr($data);
		die;
	*/
		//printr($postdata);//die;
		if(isset($postdata['per_net_weight']))
		    $postdata['net_weight'] = $postdata['per_net_weight'];
		$total_box=$postdata['total_box'];
		$boxno=$this->in_gen_box_no($postdata['invoice_id']);
		if(!empty($boxno))
			$box_no=$boxno+1;
		else
			$box_no=1;
			
		if($postdata['in_gen_id']!=0)
			$box_no='';
		
		for($i=1;$i<=$total_box;$i++)
		{
			$box_unique_id=0;$box_unique_number='';
			if($postdata['in_gen_id']==0)
			{
				$box_uid=$this->in_gen_box_uni_id();
				$box_unique_id=$box_uid+1;
				$box_unique_number='BX'.sprintf("%014s",$box_unique_id);
			}
			//printr($box_no);
			$sql= "INSERT INTO in_gen_invoice_test SET invoice_id='".$postdata['invoice_id']."',invoice_color_id='".$postdata['detail']."',qty='".$postdata['per_qty']."', box_weight ='".$postdata['per_box_weight']."', net_weight ='".$postdata['net_weight']."',box_unique_id='".$box_unique_id."',parent_id='".$postdata['in_gen_id']."',box_unique_number='".$box_unique_number."',date_added = NOW(),date_modify = NOW(),box_no='".$box_no."',invoice_product_id='".$postdata['in_product_id']."',is_delete = 0"; 	
			//printr($sql.'<br>');
			//echo 'hi in for loop';
			$data=$this->query($sql);
			$box_no++;
		}
		//die;
	}
	
	public function savePallet($postdata)
	{
		$data = $this->query("SELECT pallet_no FROM invoice_pallet_test WHERE invoice_id='".$postdata['invoice_no']."' ORDER BY pallet_id DESC LIMIT 1");
		$start = $data->row['pallet_no'];	
		for($i=1;$i<=$postdata['total_pallet'];$i++)
		{
			$data=$this->query("INSERT INTO invoice_pallet_test SET pallet_no='".($start+$i)."', invoice_id='".$postdata['invoice_no']."'");
		}
	}
	
	public function deletePallet($postdata)
	{
		$data=$this->query("DELETE  FROM invoice_pallet_test WHERE pallet_id='".$postdata['pallet_id']."'");
		$data1=$this->query("UPDATE in_gen_invoice_test SET  pallet_id='0' WHERE pallet_id='".$postdata['pallet_id']."' AND invoice_id='".$postdata['invoice_id']."'");
		return $data;
	}
	
	public function savePalletdetail($postdata)
	{
		$total_box=count($postdata['detail']);
		$sql1= "UPDATE in_gen_invoice_test SET pallet_id='0' WHERE pallet_id='".$postdata['pallet_id']."' AND invoice_id='".$postdata['invoice_id']."'"; 	
		$data1=$this->query($sql1);
		foreach($postdata['detail'] as $in_gen_invoice_id)
		{
			$sql= "UPDATE in_gen_invoice_test SET pallet_id='".$postdata['pallet_id']."' WHERE in_gen_invoice_id='".$in_gen_invoice_id."'"; 	
			$data=$this->query($sql);
		}
		return $data;
	}
	
	public function getPallet($invoice_id)
	{
		$sql1= "SELECT * FROM invoice_pallet_test WHERE invoice_id='".$invoice_id."' ORDER BY pallet_no ASC";
		$result=$this->query($sql1);
		return $result->rows;
	}
	public function getPalletS($invoice_id)
	{
		$sql1= "SELECT ip.*,count(ig.pallet_id) as tot FROM invoice_pallet_test AS ip , in_gen_invoice_test AS ig WHERE ip.invoice_id='".$invoice_id."' AND ip.pallet_id=ig.pallet_id GROUP BY ip.pallet_id ORDER BY ip.pallet_no ASC";
		$result=$this->query($sql1);
		return $result->rows;
	}
	public function getPalletDetailstotal($invoice_id) 
	{	
		$sql1= "SELECT * FROM in_gen_invoice_test WHERE invoice_id='".$invoice_id."' AND pallet_id=0";
		$result=$this->query($sql1);
		return $result->num_rows;
	}
	public function getColorDetailstotal($invoice_id) 
	{	
		$sql1= "Select * from in_gen_invoice_test where invoice_id='".$invoice_id."'";
		$result=$this->query($sql1);
		//printr($result);
	//	die;
		if($result->num_rows==0)
		{
			$sql = "select sum(qty) as total from invoice_color_test where invoice_id='".$invoice_id."' ";
			$data=$this->query($sql);
			//echo $sql;
			if($data->num_rows)
			{
				return $data->row['total'];
			}
			else
			{
				return false;
			}
		}
		else
		{
			$sql = "select ifnull(ic.qty-ig.genqty,ic.qty) as total,ic.qty,ig.genqty from (select sum(qty) as qty,invoice_color_id,invoice_id from invoice_color_test group by invoice_id ) as ic	left join (select  sum(qty) as genqty,invoice_color_id from  in_gen_invoice_test group by invoice_id) as ig on (  ig.invoice_color_id=ic.invoice_color_id)  WHERE ic.invoice_id='".$invoice_id."'";	
			//echo $sql;	die;
			$data = $this->query($sql);
			//printr($data);
			if($data->num_rows) {
				
				//[kinjal] made if condition for when i get genqty null on 9-12-2016
				if($data->row['genqty']=='')
				{
					$box_qty='select sum(qty) as genqty,invoice_color_id from  in_gen_invoice_test WHERE invoice_id="'.$invoice_id.'"group by invoice_id';
					$data_qty = $this->query($box_qty);
					return $data->row['qty']-$data_qty->row['genqty'];
				}
				else
					return $data->row['total'];
			}
			else {
				return false;
			}
		} 
	//	echo $sql;die;
	}
	//select ic.qty,ig.titalqty,ic.qty from (select qty ,invoice_color_id from invoice_color) ic left join (select total_qty,invoice_color_id from in_gen_invoice GROUP BY invoice_color_id) 
	public function consol_list($invoice_id)
	{
		$sql="SELECT ip.invoice_product_id,ig.in_gen_invoice_id, COUNT(DISTINCT ig.in_gen_invoice_id) AS total_boxes, GROUP_CONCAT(ig.box_no) AS grouped_box_no, SUM(ig.qty) AS qty,SUM(ig.box_weight+ig.net_weight) AS gross_weight,SUM(ig.net_weight) AS net_weight  ,SUM(ic.rate) AS rate,ip.item_no,ip.zipper,ip.valve,p.product_name,CONCAT(ic.size,' ',tm.measurement) AS size,pc.color,ic.color_text,ic.dimension,ip.ref_no,SUM(ic.rate*ic.qty) AS cost FROM in_gen_invoice_test AS ig , invoice_product_test AS ip,product AS p,invoice_color_test AS ic,template_measurement AS tm,pouch_color AS pc WHERE ig.invoice_id='".$invoice_id."' AND ig.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id AND ig.invoice_color_id=ic.invoice_color_id AND ic.measurement=tm.product_id AND ic.color=pc.pouch_color_id GROUP BY ig.invoice_color_id,ig.invoice_product_id";
	//	echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false; 
		}
	}
	
	public function colordetails($invoice_id,$parent_id=0,$str='',$pallet='',$limit='',$option=array())
	{//	printr($option);
			//printr($parent_id);
			//printr($str);
			
		//if($parent_id==0)
		//{
		   // $sql="select ic.dis_rate,ic.ref_no,ig.qty as genqty,ic.dimension,ic.color,ic.invoice_color_id,ic.rate,ic.valve,ic.buyers_o_no,ic.item_no,ig.in_gen_invoice_id,ic.zipper,ic.size,ic.net_weight,ig.box_no,ig.box_unique_number,ig.box_weight,ig.pallet_id,ic.measurement,ic.invoice_product_id,ic.product_name,ic.product_id,ic.color_text,ic.pouch_color_id from (select ii.qty,ii.size,ii.rate,tm.measurement,ii.invoice_product_id,p.product_name,p.product_id,ii.invoice_color_id,ip.valve,ip.zipper,ip.buyers_o_no,ip.ref_no,ip.item_no,ii.net_weight,ii.invoice_id,ii.dis_rate,ii.dimension,c.color,ii.color_text,c.pouch_color_id from invoice_color_test as ii,pouch_color as c,template_measurement as tm,invoice_product_test as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id ) as ic right join (select qty,in_gen_invoice_id,invoice_color_id,box_weight,box_no,box_unique_number,pallet_id from in_gen_invoice_test WHERE parent_id='".$parent_id."' ".$str." ".$pallet." AND box_no BETWEEN '142' AND '260') as ig on (ic.invoice_color_id=ig.invoice_color_id ) where ic.invoice_id='".$invoice_id."' ORDER BY ig.box_no ASC ".$limit." "; 
		//}
		//else
		//{
		  $sql="select ic.dis_rate,ic.ref_no,ig.qty as genqty,ic.dimension,ic.color,ic.invoice_color_id,ic.rate,ic.valve,ic.filling_details,ic.buyers_o_no,ic.item_no,ig.in_gen_invoice_id,ic.zipper,ic.size,ig.net_weight,ig.box_no,ig.box_unique_number,ig.box_weight,ig.pallet_id,ic.measurement,ic.invoice_product_id,ic.product_name,ic.product_id,ic.color_text,ic.pouch_color_id from (select ii.qty,ii.size,ii.rate,tm.measurement,ii.invoice_product_id,p.product_name,p.product_id,ii.invoice_color_id,ip.valve,ip.zipper,ip.buyers_o_no,ip.filling_details,ip.ref_no,ip.item_no,ii.net_weight,ii.invoice_id,ii.dis_rate,ii.dimension,c.color,ii.color_text,c.pouch_color_id from invoice_color_test as ii,pouch_color as c,template_measurement as tm,invoice_product_test as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id ) as ic right join (select qty,in_gen_invoice_id,invoice_color_id,box_weight,box_no,box_unique_number,pallet_id,net_weight from in_gen_invoice_test WHERE parent_id='".$parent_id."' ".$str." ".$pallet.") as ig on (ic.invoice_color_id=ig.invoice_color_id ) where ic.invoice_id='".$invoice_id."' "; 
//}
	    
	    if (isset($option['sort'])) {
			$sql .= " ORDER BY " .$option['sort'];	
		} else {
			$sql .= " ORDER BY ig.box_no";	
		}

		if (isset($option['order']) && ($option['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($option['start']) || isset($option['limit'])) {
			if ($option['start'] < 0) {
				$option['start'] = 0;
			}	
			if ($option['limit'] < 1) {
				$option['limit'] = 20;
			}	
			$sql .= " LIMIT " . (int)$option['start'] . "," . (int)$option['limit'];
		}
		else
		    $sql.=$limit;
		  
		//  echo $sql.'<br><br>';
		  $data = $this->query($sql);	
	//	printr($sql);
		
		//printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getcount($invoice_id,$parent_id=0,$str='',$pallet='',$limit='',$buyers_no)
	{	
		$sql="select ig.qty as genqty,ic.dimension,ic.color,ic.invoice_color_id,ic.rate,ic.valve,count(ic.buyers_o_no) as ct_buy,ic.item_no,ig.in_gen_invoice_id,ic.zipper,ic.size,ic.net_weight,ig.box_no,ig.box_unique_number,ig.box_weight,ig.pallet_id,ic.measurement,ic.invoice_product_id,ic.product_name,ic.product_id,ic.color_text,ic.pouch_color_id from (select ii.qty,ii.size,ii.rate,tm.measurement,ii.invoice_product_id,p.product_name,
		p.product_id,ii.invoice_color_id,ip.valve,ip.zipper,ip.buyers_o_no,ip.item_no,ii.net_weight,ii.invoice_id,ii.dimension,c.color,ii.color_text,c.pouch_color_id from invoice_color_test as ii,pouch_color as c,template_measurement as tm,invoice_product_test as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id) as ic right join (select qty,in_gen_invoice_id,invoice_color_id,box_weight,box_no,box_unique_number,pallet_id from in_gen_invoice WHERE parent_id='".$parent_id."' ".$str." ".$pallet.") as ig on (ic.invoice_color_id=ig.invoice_color_id ) where ic.invoice_id='".$invoice_id."' AND ic.buyers_o_no='".$buyers_no."' ORDER BY ig.box_no ASC ".$limit." "; 
	//echo $sql.'<br><br>';//die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function gettotalboxweight($invoice_id,$n=0)
	{
		if($n=='1')
			$parent_id='AND parent_id!=0';
		else
			$parent_id='AND parent_id=0';
			
		$sql = "SELECT SUM(box_weight) as total_box_weight, SUM(net_weight) as total_net_weight, SUM(box_weight+net_weight) as total_gross_weight FROM `" . DB_PREFIX . "in_gen_invoice_test` WHERE invoice_id = '".$invoice_id."' AND is_delete=0 $parent_id";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getMeasurementName($product_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "template_measurement` WHERE product_id = '".$product_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	
	
	public function checkInvoiceNo($invoice_no)
	{
		$sql = "SELECT invoice_no FROM `" . DB_PREFIX . "invoice_test` WHERE invoice_no = '" .(int)$invoice_no. "' AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{ 
			return false;
		}
	}
	
	public function removeInvoice($sales_invoice_product_id,$sales_invoice_id)
	{
	
		$sql = $this->query("DELETE FROM government_sales_invoice_product  WHERE `sales_invoice_product_id` = '".$sales_invoice_product_id."' AND sales_invoice_id='".$sales_invoice_id."'");
		
	}
	
	public function checkProductZipper($product_id)
	{
		$data = $this->query("SELECT zipper_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");
		if($data->num_rows){
			return $data->row['zipper_available'];	
		}else{
			return false;
		}
	}
	




	public function getCountryName($country_id)
	{
		$sql = "SELECT country_name FROM `" . DB_PREFIX . "country` WHERE country_id='".$country_id."' ";
		$sql .= " ORDER BY country_id";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getCityNameAgain($city_name)
	{
		$sql = "SELECT city_name FROM `" . DB_PREFIX . "invoice_city` WHERE invoice_city_id='".$city_name."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getCurrencyName($curr_id)
	{
		$sql = "SELECT currency_code,price FROM `" . DB_PREFIX . "currency` WHERE currency_id = '".$curr_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalBoxold($invoice_id)
	{
		$sql = "SELECT count(in_gen_invoice_id) as tot FROM " . DB_PREFIX . "in_lable_invoice_test WHERE invoice_id = '".$invoice_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getTotalBox($invoice_id)
	{
		$sql = "SELECT count(in_gen_invoice_id) as tot FROM " . DB_PREFIX . "in_gen_invoice_test WHERE invoice_id = '".$invoice_id."' AND parent_id=0 ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;

		}
	}
	public function getPlasticScoopQty($product_id,$invoice_product_id,$invoice_no,$sales_invoice_id='')
	{
		$sql="SELECT SUM(qty) as total,SUM(rate) as tot_rate,rate,(SUM(qty)*SUM(rate)) as tot_amt FROM government_sales_invoice_product WHERE invoice_id='".$invoice_no."' AND invoice_product_id='".$invoice_product_id."' AND sales_invoice_id='".$sales_invoice_id."'";
		$data=$this->query($sql);
		//echo $sql;
		if($data->num_rows)
			return $data->row;
		else
			return false;
	}
	public function getProductdeatils($invoice_no)
	{
		$sql="SELECT * FROM invoice_product_test WHERE invoice_id='".$invoice_no."'";
		
		//[kinjal] query Upadated on 24-12-2016
		//$sql="SELECT * FROM invoice_product WHERE invoice_id='".$invoice_no."' AND product_id IN (10,23,11,18,6,34)";
		//echo $sql;
		$data=$this->query($sql);
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	}
	
	public function viewInvoice($statu=0,$invoice_no,$copy_status,$pdf,$list=0)
	{	
		
		/*if($_SESSION['ADMIN_LOGIN_SWISS']==1 && $_SESSION['LOGIN_USER_TYPE']==1)
		{
		    $data=$this->query("SELECT * FROM sample_request WHERE request_id='1179'");
		    
		    print_r(str_replace('\r\n',' <br>',$data->row['address']));
		}*/
		$invoice = $this->getSalesInvoiceData($invoice_no);
		$invoice_inv_data = $this->getInvoiceNetData($invoice['invoice_id']);
		$pro_inv_data = $this->getProforma($invoice['invoice_id']);
		
	
	    $invoice_product_second = $this->getSalesInvoiceProduct($invoice_no);
		//printr($invoice);die;
		$pallet=$this->getPalletS($invoice['invoice_id']);
		//printr($pallet);
		$total_pallet=count($pallet);
		//$total_pallet=20;
		$total_pallet_box=0;
		foreach($pallet as $p)
		{
			$total_pallet_box=$p['tot']+$total_pallet_box;
		}
	
		//$total_pallet_weight=$total_pallet*23;
		//[kinjal] on 20-2-2017 told by pinank
		$total_pallet_weight=$total_pallet*12;
		$invoice_qty=$this->getInvoiceTotalData($invoice['invoice_id']);
	//	printr($invoice_qty);//die;
		$box_detail=$this->gettotalboxweight($invoice['invoice_id']);
		//printr($box_detail);
		$box_det=$this->gettotalboxweight($invoice['invoice_id'],'1');
		
		$alldetails=$this->getSalesInvoiceProduct($invoice_no);
		//$tot_qty_scoop=0;
	
		//$tot_qty_scoop=0;
		//$flag1 = array();
		$scoop_no = $roll_no = $mailer_no = $sealer_no = $storezo_no = $paper_box_no  = $con_no =$gls_no = $val_no= $chair_no =$silica_gel_no =$oxygen_absorbers_no= 0;
		
		//sonu add 15-5-2017
		$scoop_box_no = $roll_box_no = $mailer_box_no = $sealer_box_no = $storezo_box_no = $paper_box_no= $pouch_box_no = $con_box_no = $gls_box_no =$chair_box_no = $oxygen_absorbers_box_no= $silica_gel_box_no= $val_box_no =0;
		$scoop_name = $roll_name = $mailer_name = $sealer_name = $storezo_name = $paper_box_name=$pouch_name = $con_box_name=$gls_box_name=$chair_box_name=$oxygen_absorbers_box_name=$silica_gel_box_name=$val_box_name='';
		//end
		$scoop_series = $roll_series = $mailer_series = $sealer_series = $storezo_series = $paper_series= $chair_series= $oxygen_absorbers_series= $silica_gel_series = '';		
		$total_amt_scoop = $total_amt_roll = $total_amt_mailer = $total_amt_sealer = $total_amt_storezo = $total_amt_paper= $total_amt_chair =$total_amt_oxygen_absorbers =$total_amt_silica_gel =$total_amt_con=$total_amt_gls=$total_amt_valve= 0;
		//sonu add 15-5-2017
		$total_net_w_scoop =$total_gross_w_scoop = $total_net_w_roll=$total_gross_w_roll= $total_net_w_p =$total_gross_w_p  = $total_net_w_m =$total_gross_w_m= $total_net_w_s =$total_gross_w_s =$total_net_w_str =$total_gross_w_str =$total_net_w_pouch=$total_net_w_con=$total_net_w_gls=$total_net_w_chair=$total_net_w_oxygen_absorbers=$total_net_w_silica_gel=$total_net_w_val=0;
		//end
		$tot_scoop_qty = $tot_roll_qty = $tot_mailer_qty = $tot_sealer_qty = $tot_storezo_qty =$tot_con_qty- $tot_paper_chair = $tot_paper_qty = $tot_paper_qty = $tot_gls_qty =$tot_con_qty= $total_qty_val=$tot_chair_qty=$tot_oxygen_absorbers_qty=$tot_silica_gel_qty=0;
		$tot_scoop_rate = $tot_roll_rate = $tot_mailer_rate = $tot_sealer_rate = $tot_storezo_rate = $tot_chair_rate= $tot_paper_rate =$tot_con_rate=$tot_gls_rate=$tot_val_rate=$tot_oxygen_absorbers_rate=$tot_silica_gel_rate= $tot_val_qty=0;
		$total_net_amt_scp= $total_net_amt_rol=$total_net_amt_m=$total_net_amt_pouch=$total_net_amt_s=$total_net_amt_str=$total_net_amt_chair=$total_net_amt_oxygen_absorbers=$total_net_amt_silica_gel=$total_net_amt_ppr=0;
	    $scoop_no_of_package=$roll_no_of_package=$mailer_no_of_package= $sealer_no_of_package=$storezo_no_of_package=$paper_no_of_package=$pouch_no_of_package=$con_no_of_package=$gls_no_of_package=$chair_no_of_package=$oxygen_absorbers_no_of_package=$silica_gel_no_of_package=$val_no_of_package=$total_no_of_package=0;
	    $scoop_identification_marks=$roll_identification_marks=$mailer_identification_marks= $sealer_identification_marks=$storezo_identification_marks=$paper_identification_marks=$pouch_identification_marks=$con_identification_marks=$gls_identification_marks=$chair_identification_marks=$oxygen_absorbers_identification_marks=$silica_gel_identification_marks=$val_identification_marks= $total_identification_marks=0;
	
	    $no_of_package=' BOXES';
	    $identification_marks=' KGS';
		$abcd = 'A';
		//sonu add 15-5-2017
		$f_p=$f_scoop=$f_roll=$f_mailer=$f_sealer=$f_storezo=$f_box=$f_con=$f_gls=$f_chair=$f_val=true;
		//end
		$first='false';$air_f = 0;
		
	 
	 $allproduct = $this->getSalesInvoiceProduct($invoice_no,1);
	// printr($allproduct);
	       if(!empty($allproduct))
        		{
        		   if($first=='false')
        			{
        			     $air_f = 0;
	                    $first = 'true';
        			} 
        		}
        		
    //    printr($allproduct);
		foreach($alldetails as $details)
		{ 
		
			
		
			if($details['product_id']=='11')
			{//printr($details);
				$tot_qty_scoop=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
			//	printr($tot_qty_scoop);
				$tot_scoop_qty = $tot_scoop_qty + $tot_qty_scoop['total']; 
				$tot_scoop_rate = $tot_scoop_rate + $tot_qty_scoop['rate'];
				$total_amt_scoop = $total_amt_scoop + $tot_qty_scoop['tot_amt'];
				$net_pouches_scoop = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_scoop = $net_pouches_scoop ['n_wt'];
			//	printr($net_pouches_scoop);
				//sonu add 15-5-2017
				$total_gross_w_scoop = $net_pouches_scoop ['g_wt'];
				$scoop_box_no =  $net_pouches_scoop ['total_box'];
			    $group_scoop_id = $net_pouches_scoop['group_id'];
			    $scoop_no_of_package=$details['no_of_packages'];
			    $scoop_identification_marks=$details['identification_marks'];
				$scoop_name = 'SCOOPS';
				//end
				$total_net_amt_scp = $net_pouches_scoop['total_amt'];
				$scoop_no = '1';
				$scoop_series = $abcd;
				if($first=='false')
				{
				    $air_f = 1;
				    $first = 'true';
				}
			}
			else if($details['product_id']=='6' )
			{   
				$tot_qty_roll=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_roll_qty = $tot_roll_qty + $tot_qty_roll['total']; 
				$tot_roll_rate = $tot_roll_rate + $tot_qty_roll['rate'];
				//$total_amt_roll = $total_amt_roll + $tot_qty_roll['tot_amt'];
				$net_pouches_roll = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_roll = $net_pouches_roll['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_roll = $net_pouches_roll['g_wt'];
				$roll_box_no = $net_pouches_roll['total_box'];
				$group_roll_id = $net_pouches_roll['group_id'];
				 $roll_no_of_package=$details['no_of_packages'];
			    $roll_identification_marks=$details['identification_marks'];
				$roll_name = 'ROLL';
				//end 
				
			//	$net_pouches_roll['total_amt']=$net_pouches_roll['total_amt']- ($net_pouches_roll['qty']*$tot_qty_roll['rate']);
				
				$roll_price=$net_pouches_roll['qty']*$tot_qty_roll['rate'];
				$total_amt_roll = $net_pouches_roll['total_amt'];
				$total_net_amt_rol = $net_pouches_roll['total_amt'];
				$roll_no = '1';
				$roll_series = $abcd;
				if($first=='false')
				{
				    $air_f = 2;
				     $first = 'true';
				}
			}/*	else if( $details['product_id']=='67' )
			{   
				$tot_qty_roll=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_roll_qty = $tot_roll_qty + $tot_qty_roll['total']; 
				$tot_roll_rate = $tot_roll_rate + $tot_qty_roll['rate'];
				$total_amt_roll = $total_amt_roll + $tot_qty_roll['tot_amt'];
				$net_pouches_roll = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_roll = $net_pouches_roll['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_roll = $net_pouches_roll['g_wt'];
				$roll_box_no = $net_pouches_roll['total_box'];
				$group_roll_id = $net_pouches_roll['group_id'];
				 $roll_no_of_package=$details['no_of_packages'];
			    $roll_identification_marks=$details['identification_marks'];
				$roll_name = 'ROLL';
				//end 
				$total_net_amt_rol = $net_pouches_roll['total_amt'];
				$roll_no = '1';
				$roll_series = $abcd;
				if($first=='false')
				{
				    $air_f = 2;
				     $first = 'true';
				}
			}*/
			else if($details['product_id']=='10')
			{
				$tot_qty_mailer=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_mailer_qty = $tot_mailer_qty + $tot_qty_mailer['total']; 
				$tot_mailer_rate = $tot_mailer_rate + $tot_qty_mailer['rate'];
				$total_amt_mailer = $total_amt_mailer + $tot_qty_mailer['tot_amt'];
				$net_pouches_m = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				//printr($net_pouches_m);
				$total_net_amt_m = $net_pouches_m['total_amt'];
				$total_net_w_m = $net_pouches_m['n_wt'];
				
				//sonu add 15-5-2017	
				$total_gross_w_m = $net_pouches_m['g_wt'];
				$mailer_box_no = $net_pouches_m['total_box'];
				$group_mail_id = $net_pouches_m['group_id'];
				$mailer_no_of_package=$details['no_of_packages'];
			    $mailer_identification_marks=$details['identification_marks'];
				$mailer_name = 'MAILER BAGS';		
				//end
				$mailer_no = '1';
				$mailer_series = $abcd;
				if($first=='false')
				{
				    $air_f = 3;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='23')
			{
				$tot_qty_sealer=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_sealer_qty = $tot_sealer_qty + $tot_qty_sealer['total']; 
				$tot_sealer_rate = $tot_sealer_rate + $tot_qty_sealer['rate'];
				$total_amt_sealer = $total_amt_sealer + $tot_qty_sealer['tot_amt'];
				$net_pouches_s = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_s = $net_pouches_s['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_s = $net_pouches_s['g_wt'];
				$sealer_box_no = $net_pouches_s['total_box'];
				$group_sealer_id=$net_pouches_s['group_id'];
				 $sealer_no_of_package=$details['no_of_packages'];
			    $sealer_identification_marks=$details['identification_marks'];
				$sealer_name ='SEALER MACHINE';
				//end
				$total_net_amt_s = $net_pouches_s['total_amt'];
				$sealer_no = '1';
				$sealer_series = $abcd;
				if($first=='false')
				{
				    $air_f = 4;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='18')
			{
				$tot_qty_storezo=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
			//	printr($tot_qty_storezo);
				$tot_storezo_qty = $tot_storezo_qty + $tot_qty_storezo['total']; 
				$tot_storezo_rate = $tot_storezo_rate + $tot_qty_storezo['rate'];
				$total_amt_storezo = $total_amt_storezo + $tot_qty_storezo['tot_amt'];
				$net_pouches_str = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
			//	printr($net_pouches_str);
				$total_net_w_str = $net_pouches_str['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_str = $net_pouches_str['g_wt'];
				$storezo_box_no = $net_pouches_str['total_box']; 
				$storezo_name = 'STOREZO';
				//end
				$total_net_amt_str = $net_pouches_str['total_amt'];
				$group_str_id = $net_pouches_str['group_id'];
				$storezo_no_of_package=$details['no_of_packages'];
			    $storezo_identification_marks=$details['identification_marks'];
				$storezo_no = '1';
				$storezo_series = $abcd;
				if($first=='false')
				{
				    $air_f = 5;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='34')
			{
				$tot_qty_paper=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_paper_qty = $tot_paper_qty + $tot_qty_paper['total']; 
				$tot_paper_rate = $tot_paper_rate + $tot_qty_paper['rate'];
				$total_amt_paper = $total_amt_paper + $tot_qty_paper['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_p = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_p = $net_pouches_p['g_wt'];
				$paper_box_no = $net_pouches_p['total_box'];
				$group_paper_id = $net_pouches_p['group_id'];
				 $paper_no_of_package=$details['no_of_packages'];
			    $paper_identification_marks=$details['identification_marks'];
				$paper_box_name = 'PAPER BOX';
				//end
				
				
				$total_net_amt_ppr = $net_pouches_p['total_amt'];
				$paper_box_no = '1';
				$paper_series = $abcd;
				if($first=='false')
				{
				    $air_f = 6;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='47')
			{
				$tot_qty_con=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				
				
			//	printr($details);
			//	printr($tot_qty_con);
				$tot_con_qty = $tot_con_qty + $tot_qty_con['total']; 
				$tot_con_rate = $tot_con_rate + $tot_qty_con['rate'];
				$total_amt_con = $total_amt_con + $tot_qty_con['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_con = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_con = $net_pouches_p['g_wt'];
				$con_box_no = $net_pouches_p['total_box'];
				$con_box_name = 'PLASTIC DISPOSABLE LID / CONTAINER';
				//end
				$total_net_amt_con = $net_pouches_p['total_amt'];
				$con_no_of_package=$details['no_of_packages'];
			    $con_identification_marks=$details['identification_marks'];
				$con_no = '1';
				$con_series = $abcd;
				if($first=='false')
				{
				    $air_f = 7;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='48')
			{
				$tot_qty_gls=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_gls_qty = $tot_gls_qty + $tot_qty_gls['total']; 
				$tot_gls_rate = $tot_gls_rate + $tot_qty_gls['rate'];
				$total_amt_gls = $total_amt_gls + $tot_qty_gls['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_gls = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_gls = $net_pouches_p['g_wt'];
				$gls_box_no = $net_pouches_p['total_box'];
				$gls_box_name = 'PLASTIC GLASSES';
				//end
				$total_net_amt_gls = $net_pouches_p['total_amt'];
				$gls_no_of_package=$details['no_of_packages'];
			    $gls_identification_marks=$details['identification_marks'];
				$gls_no = '1';
				$gls_series = $abcd;
				if($first=='false')
				{
				    $air_f = 8;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='72')
			{
				$tot_qty_chair=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_chair_qty = $tot_chair_qty + $tot_qty_chair['total']; 
				$tot_chair_rate = $tot_chair_rate + $tot_qty_chair['rate'];
				$total_amt_chair = $total_amt_chair + $tot_qty_chair['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_chair = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_chair = $net_pouches_p['g_wt'];
				$chair_box_no = $net_pouches_p['total_box'];
				$chair_box_name = 'CHAIR';
				//end
				$total_net_amt_gls = $net_pouches_p['total_amt'];
				$chair_no_of_package=$details['no_of_packages'];
			    $chair_identification_marks=$details['identification_marks'];
				$chair_no = '1';
				$chair_series = 'B';
				if($first=='false')
				{
				    $air_f = 10;
				     $first = 'true';
				}
			}
				else if($details['product_id']=='37')
			{
				$tot_qty_oxygen_absorbers=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_oxygen_absorbers_qty = $tot_oxygen_absorbers_qty + $tot_qty_oxygen_absorbers['total']; 
				$tot_oxygen_absorbers_rate = $tot_oxygen_absorbers_rate + $tot_qty_oxygen_absorbers['rate'];
				$total_amt_oxygen_absorbers = $total_amt_oxygen_absorbers + $tot_qty_oxygen_absorbers['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_oxygen_absorbers = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_oxygen_absorbers = $net_pouches_p['g_wt'];
				$oxygen_absorbers_box_no = $net_pouches_p['total_box'];
				$oxygen_absorbers_box_name = 'Oxygen absorbers';
				//end
				$total_net_amt_oxygen_absorbers = $net_pouches_p['total_amt'];
				$oxygen_absorbers_no_of_package=$details['no_of_packages'];
			    $oxygen_absorbers_identification_marks=$details['identification_marks'];
				$oxygen_absorbers_no = '1';
				$oxygen_absorbers_series = 'B';
		//	  printr($details);
			 //  printr($oxygen_absorbers_no_of_package);
			
				if($first=='false')
				{
				    $air_f = 10;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='38')
			{
				$tot_qty_silica_gel=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_silica_gel_qty = $tot_silica_gel_qty + $tot_qty_silica_gel['total']; 
				$tot_silica_gel_rate = $tot_silica_gel_rate + $tot_qty_silica_gel['rate'];
				$total_amt_silica_gel = $total_amt_silica_gel + $tot_qty_silica_gel['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_silica_gel = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_silica_gel = $net_pouches_p['g_wt'];
				$silica_gel_box_no = $net_pouches_p['total_box'];
				$silica_gel_box_name = 'Silica Gel';
				//end 
				$total_net_amt_silica_gel = $net_pouches_p['total_amt'];
				$silica_gel_no_of_package=$details['no_of_packages'];
			    $silica_gel_identification_marks=$details['identification_marks'];
				$silica_gel_no = '1';
				$silica_gel_series = 'B';
				if($first=='false')
				{
				    $air_f = 10;
				     $first = 'true';
				}
			}
			
			else if($details['product_id']=='63')
			{
				$tot_qty_val=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_val_qty = $tot_val_qty + $tot_qty_val['total']; 
				$tot_val_rate = $tot_val_rate + $tot_qty_val['rate'];
				$total_amt_valve = $total_amt_valve + $tot_qty_val['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_val = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_val = $net_pouches_p['g_wt'];
				$val_box_no = $net_pouches_p['total_box'];
				$val_box_name = 'PLASTIC CAP';
				//end
			//	printr($details);
				$total_net_amt_val = $net_pouches_p['total_amt'];
				$val_no_of_package=$details['no_of_packages'];
			    $val_identification_marks=$details['identification_marks'];
				$val_no = '1';
				$val_series = $abcd;
				if($first=='false')
				{
				    $air_f = 9;
				     $first = 'true';
				}
			}
			else if($details['product_id']!='11' && $details['product_id']!='6' && $details['product_id']!='10' && $details['product_id']!='23' && $details['product_id']!='18' && $details['product_id']!='34'&& $details['product_id']!='47'&& $details['product_id']!='48'&& $details['product_id']!='72'&& $details['product_id']!='63'&& $details['product_id']!='37'&& $details['product_id']!='38')
			{
				$net_pouches_pouch = $this->getIngenBox($invoice['invoice_id'],2,0,$invoice_no);
				$total_net_w_pouch =$net_pouches_pouch['n_wt'];
				$total_net_amt_pouch = $net_pouches_pouch['total_amt'];
				//sonu add 15-5-2017
				$total_gross_w_p = $net_pouches_pouch['g_wt'];
				$pouch_box_no = $net_pouches_pouch['total_box'];
				$pouch_name = 'POUCHES';	
				$group_pouch_id = $net_pouches_pouch['group_id'];
				$pouch_no_of_package=$details['no_of_packages'];
			    $pouch_identification_marks=$details['identification_marks'];
			//	printr($total_net_amt_pouch);
				//sonu end	
				if($first=='false')
				{
				    $air_f = 0;
				     $first = 'true';
				}
			}
			$abcd++;
		
		
			
		}
		 
		 
        	 $time_s=new DateTime($invoice['date_added']);
            $t=$time_s->format('H:i:s');
            if($t!='00:00:00'){
              $time= '&nbsp;'.$t;
            }else{
               $time='';
            }


             $state_text='';
            if($invoice['state_id']!=0){
               $state_details=$this->getIndiaStateDetails($invoice['state_id']); 
               $state_text='State: '.$state_details['state'].' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    State Code: '.$state_details['state_code_in_no'];
            }
        

		  $total_identification_marks=	$total_net_w_scoop+$total_net_w_roll+$total_net_w_p+ $total_net_w_m + $total_net_w_s +$total_net_w_str +$total_net_w_pouch +$total_net_w_con +$total_net_w_gls+$total_net_w_chair+$total_net_w_oxygen_absorbers+$total_net_w_silica_gel +$total_net_w_val;
	      $total_no_of_package=$scoop_box_no + $roll_box_no + $mailer_box_no + $sealer_box_no+$storezo_box_no +$paper_box_no+ $pouch_box_no+ $con_box_no+ $gls_box_no+ $oxygen_absorbers_box_no+ $silica_gel_box_no+ $chair_box_no+ $val_box_no;
	     $no_of_package='BOXES';
	    $identification_marks='KGS';
		
	
		$totgross_weight=$box_detail['total_net_weight']+$box_detail['total_box_weight']+$box_det['total_net_weight']; 
	//	printr($box_detail['total_net_weight'].'+'.$box_detail['total_box_weight'].'+'.$box_det['total_net_weight']);
		$taxation=$invoice['taxation'];
		  
		if($invoice['added_user_type_id'] == 2){
			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$invoice['added_user_id']."' ");
			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
			$set_user_id = $parentdata->row['user_id'];
			$set_user_type_id = $parentdata->row['user_type_id'];
		}else{
			$userEmployee = $this->getUserEmployeeIds($invoice['added_user_type_id'],$invoice['added_user_id']);
			$set_user_id = $invoice['added_user_id'];
			$set_user_type_id = $invoice['added_user_type_id'];
		}
		/*if($_SESSION['ADMIN_LOGIN_SWISS']=='1')
		{
		    //printr($invoice['added_user_type_id']);
		}*/
		$data_logo=$this->query("SELECT logo,abn_no,termsandconditions_invoice,note_invoice FROM international_branch WHERE international_branch_id = '".$set_user_id."'"); 
//	$width='';
	  
		if(isset($data_logo->row['logo']) &&  $data_logo->row['logo']!= '') 
		{
			$image= HTTP_UPLOAD."admin/logo/200_".$data_logo->row['logo'];
            if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39'))
			    $img = '<img src="'.$image.'" alt="Image" width="100%" height="7%" id="oxi_img" class="oxi_img" />';//printr($data_logo);//printr($image);//
            else
                $img = '<img src="'. HTTP_SERVER.'admin/controller/government_sales_invoice/invoice_logo.png" width="70%" />';
		}
		else
		   $img = '<img src="'. HTTP_SERVER.'admin/controller/government_sales_invoice/invoice_logo.png" width="70%" />';
		   
	    $currency=$this->getCurrencyName($invoice['currency']);
		$html='';
		   $font="";
		    if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39'))
                { 
                     $font="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;";
                }
		if($list==0) 
    	{ 
    	       
    	    if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39'))
        	    $html.='<div class="panel-body" id="print_div" style="font-family: Calibri;padding-top: 0px;width:754px font-size=10Px">';
               	  else
          		$html.='<div class="panel-body print_div123" id="print_div" style="padding-top: 0px;width:754px font-size=10Px">';
          		
      		$html.='<div class="">
    					 <div class="form-group ">';
          	$fixdata = $this->getFixmaster(); 
          	// <div class="col-sm-1 img" style="padding: 0px;top: 13px;margin-right:  0px;"><img src="'. HTTP_SERVER.'admin/controller/government_sales_invoice/logo.png" width="80%"  ></div>
          	/*if($copy_status=='1')
          	    $copy = 'Original For Buyer';
          	else if($copy_status=='2')
          	    $copy = 'Duplicate For Buyer';
          	else*/
          	   $copy = 'Original For Buyer <br>Duplicate For Transporter<br>Triplicate for Assesse';
            
          	    //<h4> SWISS PAC PVT.LTD.</h4>Vadodara-Jumbusar National Highway,Near Padra At Dabhasa<br>DIST.VADODARA 391440, State: GUJARAT CODE : [24]<br>Phone:+91-2662-244057, 244466, Fax : +91-2662-244058<br>Email:info@swisspack.co.in  Web:www.swisspack.co.in
          
                if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39'))// vivekbhai's Change
                {
                    $html.=' 
                        <table style="cellpadding:0px;cellspacing:0px;" class="table_tag" ><tr> 
                                    <td>
                                        '.$img.'
                                    </td>
                                    <td width="30%" valign="top" style="'.$font.'"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Original For Buyer <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Duplicate For Transporter<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Triplicate for Assesse</td>
                                </tr>';
                }else{
                     $html.=' 
                            <table class="table_tag"><tr> 
                                        <td>
                                            '.$img.'
                                        </td>
                                        <td width="30%" valign="top"  >'.$copy.'</td>
                                    </tr>';
                    
                }
                                if(($invoice['transport'] == 'air' || $invoice['transport'] == 'road') && ($invoice['invoice_status'] == '0')){
                                         $transport_line ='LUT WITHOUT PAYMENT OF IGST';
                                         
                                         if($invoice['invoice_date']>='2019-04-01'){
                                              $value='LETTER OF UNDERTAKING APPLICATION REFERENCE NUMBER-AA2403180293296';
                                         }else{
                                              $value='LETTER OF UNDERTAKING APPLICATION REFERENCE NUMBER-AD240319004221P(ARN)';
                                             }
                                         
                                           if($invoice['transport'] == 'air'  && $invoice['igst_status'] == 1 ){
                                				 $transport_line ='EXPORT AGAINST PAYMENT OF IGST';
                                				 $value='';
                            				}
                                          
                                          
                                   }else{
                                             $transport_line ='EXPORT AGAINST PAYMENT OF IGST';
                                           
                                           if($invoice['invoice_date']>'2018-10-21'){
    							                 $value='THIS SHIPMENT IS TAKEN UNDER THE EPCG LICENCE LICENCE NO.3430003005 DATED 23.01.2017';
    							             }
                                           else  if($invoice['invoice_date']>'2018-07-06'){
    								                $value='THIS SHIPMENT IS TAKEN UNDER THE EPCG LICENCE LICENCE NO.3430002776 DATED 01.12.2015';
    								            }
    							             
                             }
            $html.='  
           <tr>';
             if($invoice['invoice_status']!='1' && $invoice['invoice_status']!='2'){ 
                    $html.=' <td  align="left" width="60%"><b>'.$transport_line.'</b></td>';
                     $html.='<td align="left"  width="50%"><b> TAX INVOICE</b></td>';
            }else{
                
                if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39'))
                {  
                    $html.='<td  width="60%"><br><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> TAX INVOICE</b></center><br></td>';
                }else{
                    $html.='<td  width="60%"><center> <b> TAX INVOICE</b></center></td>';
                }
                 
                  $html.='<td align="left" width="40%"></td>';
             }
            $html.='</tr></table> ';
            $challan = $mark= $mark_value = $td= $mark_td = $colspan='';$border=0;
            if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's Change
            {
                $row=count($alldetails);
                
                $num_data = '<span style="font-size: 14px;text-align: left;style="">E 201, Akshar Paradise, Behind Narayanwadi,<br>Atladara,Vadodara-390012, State: Gujarat, India<br>Mob : +91 8140128717, +91 9687659456
                            <br>Email: info@oxymist.co.in,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;website: www.oxymist.co.in</span><br>
                            <b style="font-size: 13px;" >GSTIN No :</b> <span style="font-size: 13px;font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;""> 24AAEFO8974D1Z3 </span> <br>
                            <b style="font-size: 13px;" >STATE CODE: </b> <span style="font-size: 13px;"> 24</span><br>';
                /*$num_data = '<a style="font-size: 15px;"><center>E 201, Akshar Paradise, Behind Narayanwadi Restaurant,<br>Atladara,Vadodara-390012, State: Gujarat, India<br>Mob : +91 8140128717, +91 9687659456
                            <br>Email : info@oxymist.co.in,&nbsp;&nbsp;&nbsp;&nbsp;Website: www.oxymist.co.in</center></a><br>
                            <a style="font-size: 13px;"><b>GSTIN No :</b>24AAEFO8974D1Z3 </a> <br>
                            <a style="font-size: 13px;"><b>STATE CODE: </b> 24</a><br>';*/
                            
                            $style_oxi ='font-size: 12px;';
                       
                if($_SESSION['ADMIN_LOGIN_SWISS']=='1' || (isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')))
            	{
                    $padding = 12/$row;//first here we set 20
                    $style_oxi ='vertical-align: top;font-size: 13px; padding-bottom:'.$padding.'%;';
            	}
                $desc ='';
                $table_tr ='<tr ><td style=""><strong><u>Declaration:</u></strong></td></tr>
                            <tr ><td style="">We declare that this Invoice shows the actual price of the</td></tr>
                            <tr ><td style="">Subject to Vadodara Jurisdiction E. & O. E.</td></tr>
                            <tr><td style="">Our responsibility ceases as soon as goods leaves our premises</td></tr>
                            <tr><td style="border-bottom: 1px solid #333;">Goods once sold will not bt accepted back.</td></tr>';
                $bank_detail =$this->query("SELECT b.* FROM " . DB_PREFIX . "proforma_product_code_wise as p,bank_detail as b WHERE b.bank_detail_id=17 AND p.proforma_id = '" .(int)$invoice['invoice_id']. "'");
                //printr($bank_detail);
                $table_desc ='<tr><td style="border-bottom: 1px solid #333;"><strong>OUR BANK DETAILS</strong></td><td style="border-bottom: 1px solid #333;"></td></tr>
                              <tr><td style=""><b>Beneficiary Name : </b>'.$bank_detail->row['bank_accnt'].'</td><td style="font-size: 12px;"><strong>For, OXY-MIST ABSORBERS</strong></td></tr>
                              <tr><td style=""><b>Bank Name : </b>'.$bank_detail->row['benefry_bank_name'].'</td><td></td></tr>
                              <tr><td style=""><b>Account No : </b>'.$bank_detail->row['accnt_no'].'</td><td></td></tr>
                              <tr><td style=""><b>Bank Address : </b>'.$bank_detail->row['benefry_bank_add'].'</td><td></td></tr>
                              <tr><td style=""><b>IFSC Code : </b>'.$bank_detail->row['swift_cd_hsbc'].'</td><td></td></tr>';
                $colspan = 'colspan="2"';
               
            }
            else
            {
                
                $style_oxi ='';
            
                $num_data = '<b>GSTIN No :</b> 24AADCS2724B1ZY <br>
                             <b>PAN No  :</b> AADCS2724B<br>
                             <b>RC No.  :</b> 051/AR VI/PDR/DIV.II/BRD<br> 
                             <b>CIN No. :</b> U36998GJ1992PTC018408<br>';
                $challan = '<tr><td style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;" ><b>CHALLAN NO. </b> '.$invoice['challan_no'].'</td><td style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;"  ><b>DATE</b> '.date("d-m-Y",strtotime($invoice['challan_date'])).'</td></tr>';
                $desc ='Description Manufacturing of Printed Polyester Roll,Pouch/Scrap,Waste,others<br>HSN Code No: 39232990,39201012,39239090,39235090,39232100,39231090';
                $mark = '<th>IDENTIFICATION MARKS</th>';
                $proforma_kgs="'#proforma_kgs'";
                if($pdf!=0)
                    $mark_value='<td class="no_border" align="center" >'.$alldetails[0]['identification_marks'].''.$identification_marks.'</td>';
                else
                    $mark_value='<td class="no_border"  ><input type="text" name="proforma_kgs" onchange="change_qty_per_kg('.$invoice_no.',1,'.$proforma_kgs.','.$invoice['invoice_status'].')" value="'.$alldetails[0]['identification_marks'].''.$identification_marks.'"   id="proforma_kgs"></td>';
                
                $td ='<td class="no_border" ></td>';
                 if($invoice['transport'] == 'air'  && $invoice['igst_status'] == 1 ){
                   $invoice['tran_desc']='';  
                }
             //   printr($alldetails);
                $mark_td= '<td  align="center" >'.$alldetails[0]['identification_marks'].''.$identification_marks.'</td>';
                $table_tr ='<tr><td><strong><u>'.$invoice['tran_desc'].'</u></strong></td></tr>
                 			    <tr><td><strong><u>'.$invoice['remark'].'</u></strong></td></tr>
                 			    <tr><td><b>DISPATCH</b>:'.$invoice['despatch'].'<br><b>LR NO./DT.</b>:'.$invoice['lr_no'].'<br><b>VEHICLE NO.</b>:'.$invoice['vehicle_no'].'</td></tr>';
                $table_desc = '<tr style="font-size: 12px;"><td>
                 		        Cerfified that particulars given are true & correct & the amount indicated represents<br> the price actually charges & that thereis no flow additional consideration
                 		        directly or <br>indirectly from the buyer OR certified that the particulars guveb above are true and <br>correct and the amount indicated is provisionals additional
                 		        consideration will be received<br> from the buyer of account of..<br>
                 		        <b>Subject to VADODARA Jurisdiction'.str_repeat('&nbsp;', 35).'E.&.O.E</b>
                 		        
                 		        </td>
                 		        <td>
                             		<p><strong>For : SWISS PAC PVT . LTD.</strong></p><br><br>
                             		    <strong>SIGNATURE OF THE REGISTRED<br>PERSON OR HIS AUTHORISED AGENT</strong>
                 		        </td>
                 		     </tr>';
                $border=1;
            }
            $width_table='';
            if($pdf!=0)
                $width_table='width: 100%; ';

           $html.='<table style="cellpadding:0px;cellspacing:0px;font-size: 10px;'.$width_table.'" border="1" cellpadding="0" cellspacing="0" >
    	   	 
    
          	     <tr style="font-size: 12px;">
          	       		<td colspan="2"  style="vertical-align: top; padding: 3px;">'.$num_data.'</td>
                   		<td colspan="6"  style="vertical-align: top; padding: 3px;"><table style=" width: 100%; ">';
    					 $html.='<tr><td width="50%" style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;" ><b >INVOICE NO. : </b> '.$invoice['invoice_no'].'</td><td  width="50%" style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;"><b >DATE :</b> '.date("d-m-Y",strtotime($invoice['invoice_date'])).'</td></tr>';
    					 $html.=$challan;
    					 $html.='<tr><td style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;">';if($invoice['invoice_status']!='1'){
    					 	 $html.='<b style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;">EXP INVOICE NO. : </b> '.$invoice['exp_inv_no'].'</td>';}
    					  $html.='<td></td></tr>';
    					 //$html.='<tr><td style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;"><b style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;">ORDER NO. :  </b> </td><td  style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;"><b >DATE :</b></td></tr>';
    					 $html.='<tr><td style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;"><b style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;"></td><td  style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;"><b >TIME :</b> '.$time.'</td></tr>';
    					 if($invoice['buyers_orderno']!=''){
    					    	 $html.='<tr><td style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;font-size: 14px;"><b style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;">BUYERS ORDER NO: </b>'.$invoice['buyers_orderno'].'</td><td style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;"><b >DATE: </b>'.date("d-m-Y",strtotime($invoice['buyers_order_date'])).'</td></tr>'; 
    					 }
    					 $html.='<tr><td  width="60%" style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;"><b >PAYMENT : </b>Immediate </td><td width="40%"></td></tr>';
    				$html.='</table></td></tr>';
                
    		//if($status==1){
    		if($invoice['invoice_status']=='0' ){
               			    //invoice
                 $html .= ' <tr>
               			        <td colspan="2" style="vertical-align: top;font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;"><strong> NAME & ADDRESS OF CONSIGNEE:</strong></td>
               			        <td colspan="6" style="vertical-align: top;font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;"><strong> NAME & ADDRESS OF BUYER OTHER THAN CONSIGNEE:</strong></td>
                        	</tr>';
                      $gstno='';  
                      	  // 	$customer_name='<b>'.$invoice['customer_name'].'</b>';
                      	  	$customer_name='';
                      	  	$invoice['other_buyer']= $invoice['other_buyer'];
                      	  
              }else{ 
                  //proforma
                      $pro_detail =$this->query("SELECT p.contact_no,b.* FROM " . DB_PREFIX . "proforma_product_code_wise as p,bank_detail as b WHERE b.bank_detail_id=17 AND p.proforma_id = '" .(int)$invoice['invoice_id']. "'");
                        //printr($pro_detail);
                  
              //    printr($invoice['invoice_status']);
               
                        if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's Change
                        {
                               	$customer_name='<b style=" font-weight: 900;font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;">'.$invoice['customer_name'].'</b><br>';
                                     $html .= '<tr>
                                   			        <th colspan="2" style="vertical-align: top;font-size:12px;text-align:center;'.$font.'"><strong> BILLING ADDRESS<br></strong></th>
                                   			        <th colspan="6" style="vertical-align: top;font-size:12px;text-align:center;'.$font.'"><strong> DELIVERY ADDRESS<br></strong></th>
                                            	</tr>';
                                                 $gstno='<br><b style=" font-weight: 900;font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;">GSTIN No :  </b>'.$invoice['gst_no'].'</b>';
                                       
                                                 $invoice['other_buyer']= nl2br($invoice['other_buyer']);  
                                              //   $font="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;";
                        }else{ 
                               	$customer_name='<b>'.$invoice['customer_name'].'</b><br>';
                                     $html .= '<tr>
                                   			        <td colspan="2" style="vertical-align: top;"><strong> BILLING ADDRESS:</strong></td>
                                   			        <td colspan="6" style="vertical-align: top;"><strong> DELIVERY ADDRESS:</strong></td>
                                            	</tr>';
                                                 $gstno='<br><b>GSTIN NO :</b>'.$invoice['gst_no'].'</b>';
                                       
                                                 $invoice['other_buyer']= nl2br($invoice['other_buyer']);
                        }             
                    } 
                
                   
                    if($invoice['same_as_above']!='1'){
                         
                        $invoice['other_buyer']=$invoice['other_buyer'];
                    }else{
                       $invoice['other_buyer']="";
                    }
                    //made this cond. by [kinjal] on 5-9-2018
                    /*if($pdf!=0)
                        $invoice['consignee'] = utf8_encode($invoice['consignee']);
                    else*/
                        $invoice['consignee'] = $invoice['consignee'];
                        
           		 $contact_no='';
                    if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's Change
                        {
                            $html .='<tr style="font-size: 14px;">
              			 <td colspan="2" style="vertical-align: top;'.$font.'">'.$customer_name.''.nl2br($invoice['consignee']).'<br>'.$state_text.'<br>Contact No. : '.$pro_detail->row['contact_no'].'<br>'.$gstno.'</td>
              			 <td colspan="6" style="vertical-align: top;'.$font.'" >'.$invoice['other_buyer'].'</td>
           		  </tr>';
                        }else{
                           // echo 'hii';
                         //  printr($pro_inv_data); 
                           	if($invoice['invoice_status']=='1'){
                               	    if($pro_inv_data['contact_no']!=0){
                                      $contact_no='<br>Contact No. : '.$pro_inv_data['contact_no'].'';
                               	    }
                            } 
                            $html .='<tr style="font-size: 11px;">
              			 <td colspan="2" style="vertical-align: top;'.$font.'">'.$customer_name.''.nl2br($invoice['consignee']).'<br>'.$state_text.' <br>'.$contact_no.'<br>'.$gstno.'</td>
              			 <td colspan="6" style="vertical-align: top;'.$font.'" >'.$invoice['other_buyer'].'</td>
           		  </tr>';
                            
                        }
           		  
           		  $html .='<tr>
              			 <td colspan="8"   style="vertical-align: top;'.$font.'">'.$desc.'</td>
              			 
           		  </tr>';
    			
    		
    		 $currency=$this->getCurrencyName($invoice['currency']);
    		 $total_no_of_box=$this->getTotalBox($invoice['invoice_id']);
    		 $child_net = 0;
    		 //$measurement=$this->getMeasurementName($invoice['measurement']);
    	
    		 
             	$html.='<tr style="vertical-align: top;" style="font-size: 12px;">';  
    		 	//printr($currency);
    				 
               	if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's Change
                { 
        			$html.='<th colspan="3" style="vertical-align: middle !important;'.$font.'"><center>DESCRIPTION OF GOODS</center></th>
        					 <th '.$colspan.' style="vertical-align: middle !important;'.$font.'"><center>NO OF<br> PACKAGES</center></th>
        					 '.$mark.'
        					 <th align="center" style="'.$font.'"><center>QUANTITY<br />(In Pcs.)</center></th>
        					 <th align="center" style="'.$font.'"><center>RATE</center></th>
        					 <th align="center" style="'.$font.'"><center>AMOUNT INR<br /> <img src="https://swissonline.in/upload/admin/u20B9.png" alt="Image" width="15px" /></center></th>';//<img src="https://swissonline.in/upload/admin/u20B9.png" alt="Image" width="65%"><span style="font-family: calibri, DejaVu Sans, sans-serif;">₹</span>
                }else{
                         
        			$html.='<th colspan="3">DESCRIPTION OF GOODS</th>
        					 <th '.$colspan.'>NO OF PACKAGES</th>
        					 '.$mark.'
        					 <th>QUANTITY</th>
        					 <th>RATE</th>
        					 <th>AMOUNT <br />INR <span style="font-family: DejaVu Sans; sans-serif;">&#x20B9;</span></th>';
                }
               
    
                     
    				 $html.='</tr>';
    				
    				// added by sonu 21-05-2018
    				   
    				
    				
    				    
    				
    				 
    				 if($box_det['total_net_weight']!='')
    				 	$child_net= $box_det['total_net_weight'];
    				
    				//printr($box_detail);
    		}	
		//kinjal end ==0	
		
		if($list == '1' || $list == '0') 
		{	 $igst=$cgst=$sgst=0;
        	if($invoice['invoice_status']!='1' && $invoice['invoice_status']!='2'){	
        	    
        	    //invoice
                                             
                                     		 			if($pouch_name=='POUCHES'){
                                						
                                						  
                                						       $cond_net_weight = number_format($box_detail['total_net_weight']+$child_net,3);
                                						      	 
                                						        
                                     		 		        
                            						       }
                            						       
                            						       	if($air_f=='0'){
                                								    		$total_amt_val=$invoice_qty['tot'];
                                            							 if($invoice_inv_data['invoice_date']<'2018-03-18'){
                                            							            	$rate_per = number_format((($total_amt_val-($invoice['tran_charges']+$roll_price+$total_amt_paper+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel+$total_amt_valve))/$total_qty_val),8);
                                            					                         $amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_paper+$roll_price+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel+$total_amt_valve));
                                            							    }else{
                                            							                	$rate_per = number_format((($total_amt_val-($invoice['tran_charges']+$total_amt_roll+$total_amt_paper+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel+$total_amt_valve))/$total_qty_val),8);
                                            					                             $amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_oxygen_absorbers+$total_amt_silica_gel+$total_amt_chair+$total_amt_valve));
                                            					             }
                                         
                                                                        	if($invoice_inv_data['invoice_date']<'2018-10-12'){	
                                                    						    if($invoice_inv_data['invoice_id']!='1899'){
                                                    								if($invoice_inv_data['country_destination']=='172'  || $invoice_inv_data['country_destination']=='253')//|| $invoice_inv_data['country_destination']=='125'
                                                    								{   
                                                    								    $rate_per = number_format((($total_amt_val-($total_amt_paper+$roll_price+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_oxygen_absorbers+$total_amt_silica_gel+$total_amt_chair+$total_amt_valve))/$total_qty_val),8);
                                                    								    $amt = ($total_amt_val-($total_amt_paper+$roll_price+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel+$total_amt_valve));
                                                    								}
                                                    								
                                                    						    }
                                                                        	} 
                                								      
                                							     //   printr($total_amt_scoop);
                                								     	if($amt<$invoice['tran_charges']){
                                                                                if($air_f==0 && $total_amt_scoop>$invoice['tran_charges']){
                                                                                    $air_f=5;
                                                                                }
                                                                                else if($total_amt_roll>$invoice['tran_charges']){
                                                                                    $air_f=2; 
                                                                                }
                                                                                else{
                                                                                     $air_f=1;
                                                                                }
                                                                         }  
    								
                                                                     
                                                                     
                                								
                                								    
                                								}
                                								 
                                								  
                            						       	if($invoice['invoice_date']>'2019-09-06')
                                                                {
                                                                    // add by sonu 06-09-2019
                                                                    $air_f=$invoice_inv_data['air_f_status'];
                                                                }
                                								
                                								
                                								
                                							//	printr($total_amt_val);
                            						       
                            						       
                            						        
                            								if($scoop_no == '1')
                            								{	
                            									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
                            									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_oxygen_absorbers_rate+$tot_silica_gel_rate+$tot_chair_rate );
                            									$total_amt_val=$invoice_qty['tot'];
                            									if($air_f=='1')
                            									       $total_amt_scoop = ($total_amt_scoop - $invoice['tran_charges']);
                            								}
                            								if($roll_no == '1')
                            								{ 
                            									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
                            									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate+$tot_oxygen_absorbers_rate+$tot_silica_gel_rate+$tot_chair_rate );
                            								//	printr($total_amt_roll.'pppppp');
                            								//	printr($invoice['cylinder_charges'].'pppppp56666');
                            								//	printr($total_amt_roll - $invoice['tran_charges']);
                            						
                            									
                            									$total_amt_val=($invoice_qty['tot']-$roll_price)+$total_amt_roll;
                            									if($air_f=='2')
                            									       $total_amt_roll = ($total_amt_roll - $invoice['tran_charges']);
                            									       
                            								//	printr($total_amt_roll.'qbwibgwierg'); 
                            								}
                            								if($mailer_no == '1')
                            								{
                            									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
                            									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate+$tot_oxygen_absorbers_rate+$tot_silica_gel_rate+$tot_chair_rate );
                            									//$total_rate_val=$tot_mailer_rate;
                            									$total_amt_val=$invoice_qty['tot'];
                            									if($air_f=='3')
                            									       $total_amt_mailer = ($total_amt_mailer - $invoice['tran_charges']);
                            								}
                            								if($sealer_no == '1')
                            								{
                            									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
                            									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate+$tot_oxygen_absorbers_rate+$tot_silica_gel_rate+$tot_chair_rate );
                            									//$total_rate_val=$tot_sealer_rate;
                            									$total_amt_val=$invoice_qty['tot'];
                            									if($air_f=='4')
                            									       $total_amt_sealer = ($total_amt_sealer - $invoice['tran_charges']);
                            								}
                            								if($storezo_no == '1')
                            								{
                            									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
                            									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate+$tot_chair_rate+$tot_oxygen_absorbers_rate+$tot_silica_gel_rate );
                            									//$total_rate_val=$tot_storezo_rate;
                            									
                            									    $total_amt_val=$invoice_qty['tot'];
                            									    if($air_f=='5')
                            									        $total_amt_storezo = ($total_amt_storezo - $invoice['tran_charges']);
                            									
                            								}
                            								if($paper_box_no == '1')
                            								{
                            									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
                            									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_chair_rate +$tot_oxygen_absorbers_rate +$tot_silica_gel_rate );
                            								
                            									$total_amt_val=$invoice_qty['tot'];
                            									if($air_f=='6')
                            									        $total_amt_paper = ($total_amt_paper - $invoice['tran_charges']);
                            									
                            								}
                            								if($con_no == '1')
        														{
        															$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
        															$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_chair_rate+$tot_oxygen_absorbers_rate+$tot_silica_gel_rate);
        															//$total_rate_val=$tot_paper_rate;
        															$total_amt_val=$invoice_qty['tot'];
        															if($air_f=='7')
        															        $total_amt_con = ($total_amt_con - $invoice['tran_charges']);
        															
        														}
        														if($gls_no == '1')
        														{
        															$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
        															$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_chair_rate+$tot_oxygen_absorbers_rate+$tot_silica_gel_rate);
        															//$total_rate_val=$tot_paper_rate;
        															$total_amt_val=$invoice_qty['tot'];
        															if($air_f=='8')
        															        $total_amt_gls = ($total_amt_gls - $invoice['tran_charges']);
        															
        														}
        														if($chair_no == '1')
        														{
        															$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
        															$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_oxygen_absorbers_rate+$tot_val_rate+$tot_silica_gel_rate);
        															//$total_rate_val=$tot_paper_rate;
        															$total_amt_val=$invoice_qty['tot'];
        															if($air_f=='8')
        															        $total_amt_chair = ($total_amt_chair - $invoice['tran_charges']);
        														}
        														if($oxygen_absorbers_no == '1')
        														{
        															$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_silica_gel_qty);
        															$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_silica_gel_rate);
        															//$total_rate_val=$tot_paper_rate;
        															$total_amt_val=$invoice_qty['tot'];
        															if($air_f=='11')
        															        $total_amt_oxygen_absorbers = ($total_amt_oxygen_absorbers - $invoice['tran_charges']);
        														}
        														if($silica_gel_no == '1')
        														{
        															$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_oxygen_absorbers_gel_qty);
        															$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_oxygen_absorbers_rate);
        															//$total_rate_val=$tot_paper_rate;
        															$total_amt_val=$invoice_qty['tot'];
        															if($air_f=='12')
        															        $total_amt_silica_gel = ($total_amt_silica_gel - $invoice['tran_charges']);
        														}
        													
        														if($val_no == '1')
        														{
        															$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
        															$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_oxygen_absorbers_rate+$tot_silica_gel_rate+$tot_chair_rate);
        															//$total_rate_val=$tot_paper_rate;
        															$total_amt_val=$invoice_qty['tot'];
        															if($air_f=='9')
        															        $total_amt_valve = ($total_amt_valve - $invoice['tran_charges']);
        															
        														}
                            								else
                            								{
                            								  	if($pouch_name=='POUCHES'){
                                									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
                                									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate+$tot_con_rate+$tot_gls_rate+$tot_val_rate +$tot_oxygen_absorbers_rate+$tot_silica_gel_rate+$tot_chair_rate );
                                								    //printr($invoice_qty['tot']);
                        							            	//$total_rate_val=$invoice_qty['rate'];
                        								        	//$total_amt_val=$total_qty_val*$invoice_qty['rate'];
                            								         if($roll_no == '1')
                                								  	        $total_amt_val=$total_amt_val;
                                								  	     else
                                								  	        $total_amt_val=$invoice_qty['tot'];
                                								  	}else{
                                								  	    $total_qty_val=$invoice_qty['total_qty'];
                                								  	    $total_rate_val=$invoice_qty['total_rate'];
                                								  	     if($roll_no == '1')
                                    								  	        $total_amt_val=$total_amt_val;
                                    								  	     else
                                    								  	        $total_amt_val=$invoice_qty['tot'];
                                								  	}
                                                    		}
                             
                             
                                      // printr($total_amt_val);
                            					//	if($invoice['transport']=='sea')
                            						//	{
                            							  
                            					         $total_amt_val=$total_amt_val+$invoice['cylinder_charges'];
                            					         
                            					          $total_amt_val=$total_amt_val+$invoice['tool_cost'];
                            					     
                            					         //printr($invoice);
                            					         if($invoice['sales_invoice_id']=='1247')
                            					            $total_amt_val+=1200;
                            					
                            				    //	}
                            				    
                            				    
                            				    
                            				    
                            				 //   printr($invoice['cylinder_charges']);		
                            				//	printr($total_amt_val);	
                            						
                            						
                            						
                            						/*	if(ucwords(decode($invoice['transportation']))=='Sea')
                            							{
                            							
                            							    $total_amt_val=$total_amt_val+$invoice['cylinder_charges'];
                            							 
                            						
                            						
                            								$amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo));
                            									///	$rate_per = number_format(($total_amt_val/$total_net_w_pouch),8); comment by sonu  18-3-2017  
                            										$rate_per = number_format(( $amt/$total_net_w_pouch),8); 
                            						
                            								
                            							}
                            							else
                            							{*/
                            								
                            					//	printr($total_amt_roll); 
                            						//	printr($total_amt_scoop);
                            						
                            							
                            							    if($invoice_inv_data['invoice_date']<'2018-03-18'){	
                            							
                            							            	$rate_per = number_format((($total_amt_val-($invoice['tran_charges']+$roll_price+$total_amt_paper+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel))/$total_qty_val),8);
                            					                         $amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_paper+$roll_price+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel));
                            							    }else{
                            							                	$rate_per = number_format((($total_amt_val-($invoice['tran_charges']+$total_amt_roll+$total_amt_paper+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel))/$total_qty_val),8);
                            					                             $amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel));
                            							    }
                          //    printr($total_amt_val.'=='.$amt);
                          
                          
                          
                          
                          
                                                        	if($invoice_inv_data['invoice_date']<'2018-10-12'){	
                                    						    if($invoice_inv_data['invoice_id']!='1899'){
                                    								if($invoice_inv_data['country_destination']=='172'  || $invoice_inv_data['country_destination']=='253')//|| $invoice_inv_data['country_destination']=='125'
                                    								{
                                    								    $rate_per = number_format((($total_amt_val-($total_amt_paper+$roll_price+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel))/$total_qty_val),8);
                                    								    $amt = ($total_amt_val-($total_amt_paper+$roll_price+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel));
                                    								}
                                    								
                                    						    }
                                                        	} 
                            						//	printr($rate_per.'==='.printr($amt));
                            								$insurance='0';
        													 if(($invoice['country_id']=='252' || $invoice_inv_data['order_user_id']==2) && ($invoice['transport'])=='air')
        													  {
        													      $amt1=$amt-$invoice['cylinder_charges'];
        														    $tran_charges_tot=$invoice['tran_charges']+$amt1;
        												
        														$insurance= number_Format(((($tran_charges_tot*110/100+$tran_charges_tot)*0.07)/100),2);
        												
        													
        													  }
                            						
                            								 
                            						//	}	
                            								
                            				//	printr($amt);
                            						$air_rate = $amt * $currency['price'];
                            						//	printr($amt);
                            				/*	if($invoice['transport']=='air')
                            							{
                            							  $amt=  $amt+$invoice['cylinder_charges'];
                            							}*/
                            				//	printr($amt);
                            				//	printr($total_amt_scoop);
                            						//	printr($rate_per);
                            						
                            			
                            			if($pouch_name=='POUCHES'){
                            			    
                            			        $html.='<tr style="font-size: 12px;">';
                            					
                                                 		 $html.='<td colspan="3" class="no_border"><b>'.$invoice_inv_data['pouch_type'].' <br>HSN NO.39232990</b><br>'.$invoice_inv_data['pouch_type'].'</td>';
                            			        
                            				    	    $p_id = "'".$group_pouch_id."'";
                            				    	    $pouch_box="'#pouch_box'";
                            				    	    $pouch_kgs="'#pouch_kgs'";
                            				    	    if($pdf!=0){
                                    				    	    $html.='<td class="no_border" align="center"  >'. $pouch_box_no.' '.$no_of_package.'</td>'; 
                                    				    	   $html.='<td class="no_border"  align="center" >'. $total_net_w_pouch.' '.$identification_marks.'</td>';
                            				    	   
                                       				
                            			            }else{
                            			                
                            			                 $html.='<td  valign="top" class="no_border"><input type="text" id="pouch_box" name="pouch_box" onchange="change_qty_per_kg('.$p_id.',0,'.$pouch_box.','.$invoice['invoice_status'].')" value='.$pouch_box_no.''.$no_of_package.'  ></td>';
                            			                	$html.='<td class="no_border" valign="top"><input type="text" id="pouch_kgs" name="pouch_kgs" onchange="change_qty_per_kg('.$p_id.',1,'.$pouch_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_pouch.''.$identification_marks.'" ></td>';
                            			                
                            			            }
                                       					$html.='<td class="no_border" valign="top"><p align="center">'.$total_qty_val.' NOS</p></td>';
                                       				
                                              
                                				
                                		          		$html.='<td class="no_border" valign="top"><p align="center">'.($rate_per*$invoice['currency_rate']).'</p></td>
                                          						<td class="no_border" valign="top"><p align="center">'.number_format(($amt*$invoice['currency_rate']),2).'</p>';
                                				
                            			}else{
                            			    $html.='<tr>
                            			    <td class="no_border" colspan="6" valign="top"></td></tr>';
                            			} 
                            				$html.='</tr>';
                            				
                            							if($invoice['tool_cost'] !='0.00')
                        								    { 
                        								        
                            								/*	$html.='<tr style="font-size: 12px;">
                            											
                            												<td colspan="3" class="no_border"><strong>Set Up Cost </td>';
                            										
                                                				      
                                                				    	    $html.='<td class="no_border" align="center" ></td>'; 
                                                				    	   $html.='<td class="no_border" align="center" ></td>';
                                                           			
                            										    	$html.='<td class="no_border" valign="top"></td>';
                            									
                            												$html.='<td class="no_border" valign="top"><p align="center"></td>';
                            									
                            												$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($invoice['tool_cost']),2).'</p></td>';
                            									
                            									$html.='</tr>';*/
                        							    	}
                            						
                            								if($scoop_no == '1')
                            								{
                            								    
                            								    	if($invoice_inv_data['country_destination']=='238')
                                                                			$text = " Plastic Stoppers,Lids,Cap & Other Closures";
                                                                		    else
                                                                		  $text = " Plastic Scoop "; 
                            								
                            								$html.='<tr style="font-size: 12px;">
                            											
                            												<td colspan="3" class="no_border"><strong>A)'.$text.'  </strong><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39235090<br>
                            </td>';
                            											$s_id = "'".$group_scoop_id."'";
                            											$scoop_box="'#scoop_box'";
                            				    	                    $scoop_kgs="'#scoop_kgs'";
                                                				      if($pdf!=0){
                                                				    	    $html.='<td class="no_border" align="center" >'. $scoop_box_no.' '.$no_of_package.'</td>'; 
                                                				    	   $html.='<td class="no_border" align="center" >'.$total_net_w_scoop.' '.$identification_marks.'</td>';
                                                           				
                                                			            }else{
                            			                
                            											$html.='<td class="no_border" valign="top"><input type="text" name="scoop_box" onchange="change_qty_per_kg('.$s_id.',0,'.$scoop_box.','.$invoice['invoice_status'].')" value='.$scoop_box_no.''.$no_of_package.'   id="scoop_box"></td>';
                                       					                $html.='<td class="no_border" valign="top"><input type="text" name="scoop_kgs" onchange="change_qty_per_kg('.$s_id.',1,'.$scoop_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_scoop.''.$identification_marks.'"   id="scoop_kgs"></td>';
                                                			            }
                            											$html.='<td class="no_border" valign="top"><p align="center">'.$tot_scoop_qty.' NOS</p></td>';
                            										
                            									
                            												$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_scoop/$tot_scoop_qty)*$invoice['currency_rate']),8).'</p></td>';
                            									
                            												$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_scoop*$invoice['currency_rate']),2).'</p></td>';
                            									
                            									$html.='</tr>';
                            								}
                            							
                            								if($roll_no == '1')
                            								{ //printr($details['product_id']);
                            								
                            									
							                    	//$total_amt_val=$total_amt_val+$total_amt_roll;
                            									$html.='<tr style="font-size: 12px;">
                            											
                            												<td colspan="3" class="no_border"><strong>'.$roll_series.') Printed or Unprinted Flexible Packaging Material of Rolls</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>Printed Polyester Rolls : 39201012
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>HS CODE : 39201012 <br>
                            </td>';
                            											
                            											$r_id = "'".$group_roll_id."'";
                            											$roll_box="'#roll_box'";
                            				    	                    $roll_kgs="'#roll_kgs'";
                            				    	                     if($pdf!=0){
                                                				    	    $html.='<td class="no_border" align="center" >'. $roll_box_no.' '.$no_of_package.'</td>'; 
                                                				    	   $html.='<td class="no_border" align="center" >'. $total_net_w_roll.' '.$identification_marks.'</td>';
                                                           				
                                                			            }else{
                            											$html.='<td class="no_border" valign="top"><input type="text" name="roll_box" onchange="change_qty_per_kg('.$r_id.',0,'.$roll_box.','.$invoice['invoice_status'].')" value='.$roll_box_no.''.$no_of_package.'  id="roll_box"></td>';
                                       					                $html.='<td class="no_border" valign="top"><input type="text" name="roll_kgs" onchange="change_qty_per_kg('.$r_id.',1,'.$roll_kgs.','.$invoice['invoice_status'].')" value="'. $total_net_w_roll.''.$identification_marks.'"  id="roll_kgs"></td>';
                                                			            }
                            											$html.='<td class="no_border" valign="top"><p align="center">'.$roll_identification_marks.' Kgs</p></td>';
                                    								/*	if(ucwords(decode($invoice['transportation']))=='Sea')
                            											{
                            												$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_roll/$total_net_w_roll)*$invoice['currency_rate']),8).'</p></td>';
                            											}else{*/
                            												$html.='<td class="no_border" valign="top"><p align="center">'.number_format(((($total_amt_roll)/$roll_identification_marks)*$invoice['currency_rate']),8).'</p></td>';
                            										/*	}*/
                            										
                            											$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_roll)*$invoice['currency_rate']),2).'</p></td>';
                            								 
                            										
                            									$html.='</tr>';
                            								}
                            								if($mailer_no == '1')
                            								{
                            									$html.='<tr style="font-size: 12px;">
                            											
                            												<td colspan="3" class="no_border"><strong>'.$mailer_series.') Mailer Bag</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39232990<br></td>';
                            											
                            													$m_id = "'".$group_mail_id."'";
                            													$mail_box="'#mail_box'";
                            				    	                            $mail_kgs="'#mail_kgs'";
                            				    	                             if($pdf!=0){
                                                        				    	    $html.='<td class="no_border" align="center"  >'.$mailer_box_no.' '.$no_of_package.'</td>'; 
                                                        				    	   $html.='<td class="no_border" align="center"  >'. $total_net_w_m.' '.$identification_marks.'</td>';
                                                           				
                                                			                   }else{
                                    										        	$html.='<td class="no_border" valign="top"><input type="text" name="mail_box" onchange="change_qty_per_kg('.$m_id.',0,'.$mail_box.','.$invoice['invoice_status'].')" value='.$mailer_box_no.''.$no_of_package.'  id="mail_box"></td>';
                                               					                        $html.='<td class="no_border" valign="top"><input type="text" name="mail_kgs" onchange="change_qty_per_kg('.$m_id.',1,'.$mail_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_m.''.$identification_marks.'"   id="mail_kgs"></td>';
                                                			                     }
                            													$html.='<td class="no_border" valign="top"><p align="center">'.$tot_mailer_qty.' NOS </p></td>';
                            									
                                    										/*	if(ucwords(decode($invoice['transportation']))=='Sea')
                                    											{
                                    												$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_mailer/$total_net_w_m)*$invoice['currency_rate']),8).'</p></td>';
                                    											}else{*/
                                    												$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_mailer/$tot_mailer_qty)*$invoice['currency_rate']),8).'</p></td>';
                                    										/*	}*/
                            										
                            											$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_mailer*$invoice['currency_rate']),2).'</p></td>';
                            								
                            								
                            										
                            									$html.='</tr>';
                            								}
                            							/*	if($sealer_no == '1') 
                            								{
                            									$html.='<tr>
                            												<td colspan="3" class="no_border"></td>
                            												<td colspan="3" class="no_border"><div><strong>'.$sealer_series.') Sealer Machine</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 84223000<br>
                            </div></td>';
                            											    $sealer_id = "'".$group_sealer_id."'";
                            											    	$sealer_box="'#sealer_box'";
                            				    	                            $sealer_kgs="'#sealer_kgs'";
                            												   	$html.='<td class="no_border" valign="top"><input type="text" name="sealer_box" onchange="change_qty_per_kg('.$sealer_id.',0,'.$sealer_box.')" value="" id="str_box"></td>';
                                               					                $html.='<td class="no_border" valign="top"><input type="text" name="sealer_kgs" onchange="change_qty_per_kg('.$sealer_id.',1,'.$sealer_kgs.')" value="" id="str_kgs"></td>';
                            												    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_sealer_qty.'</p></td>';
                            								
                            												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_sealer/$tot_sealer_qty,8).'</p></td>';
                            											
                            											$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_sealer,2).'</p></td>';
                            								
                            										
                            									$html.='</tr>';
                            								}*/
                            								if($storezo_no == '1')
                            								{
                            									$html.='<tr style="font-size: 12px;">
                            											
                            												<td colspan="3" class="no_border"><strong>'.$storezo_series.') Storezo High barrier Bag</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39232100<br><br>';
                                                                            
                                                                            $html.='</td>';
                            											
                            													$str_id = "'".$group_str_id."'";
                            													$str_box="'#str_box'";
                            				    	                            $str_kgs="'#str_kgs'";
                            				    	                             if($pdf!=0){
                                                            				    	    $html.='<td class="no_border" align="center" >'. $storezo_box_no.' '.$no_of_package.'</td>'; 
                                                            				    	   $html.='<td class="no_border" align="center" >'.$total_net_w_str.' '.$identification_marks.'</td>';
                                                           				
                                                			                     }else{
                                            											$html.='<td class="no_border" valign="top"><input type="text" name="str_box" onchange="change_qty_per_kg('.$str_id.',0,'.$str_box.','.$invoice['invoice_status'].')" value='.$storezo_box_no.''.$no_of_package.'  id="str_box"></td>';
                                                       					                $html.='<td class="no_border" valign="top"><input type="text" name="str_kgs" onchange="change_qty_per_kg('.$str_id.',1,'.$str_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_str.$identification_marks.'"  id="str_kgs"></td>';
                                                        			                  }
                            													$html.='<td class="no_border" valign="top"><p align="center">'.$tot_storezo_qty.' NOS</p></td>';
                            													//$html.='<td class="no_border" valign="top"><p align="center">'.$tot_storezo_qty.'</p></td>';
                            							
                                    										/*	if(ucwords(decode($invoice['transportation']))=='Sea')
                                    											{
                                    												$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_storezo/$total_net_w_str)*$invoice['currency_rate']),8).'</p></td>';
                                    											}else{*/
                                    												$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_storezo/$tot_storezo_qty)*$invoice['currency_rate']),8).'</p></td>';
                                    										/*	}*/	
                            										
                            											$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_storezo*$invoice['currency_rate']),2).'</p></td>';
                            								
                            										
                            									$html.='</tr>';
                            								}
                            								if($paper_box_no == '1')
                            								{
                            								    	$paper_id = "'".$group_paper_id."'";
                            								    	$paper_box="'#paper_box'";
                            				    	               $paper_kgs="'#paper_kgs'";
                            									$html.='<tr style="font-size: 12px;">
                            											
                            												<td colspan="3" class="no_border"><strong>'.$paper_series.') Paper Board Boxes</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 48191010<br> </td>';               
                                                                         if($pdf!=0){
                                                				    	           $html.='<td class="no_border" align="center" >'. $paper_box_no.' '.$no_of_package.'</td>'; 
                                                				    	           $html.='<td class="no_border" align="center" >'. $total_net_w_p.' '.$identification_marks.'</td>';
                                                           				
                                                			            }else{
                                									        $html.='<td class="no_border" valign="top"><input type="text" name="paper_box" onchange="change_qty_per_kg('.$paper_id.',0,'.$paper_box.','.$invoice['invoice_status'].')" value='.$paper_box_no.''.$no_of_package.'  id="str_box"></td>';
                                       					                    $html.='<td class="no_border" valign="top"><input type="text" name="paper_kgs" onchange="change_qty_per_kg('.$paper_id.',1,'.$paper_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_p.''.$identification_marks.'"  id="str_kgs"></td>';
                                                			            }
                                									    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_paper_qty.' NOS</p></td>';
                                								  
                            												$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_paper/$tot_paper_qty)*$invoice['currency_rate']),8).'</p></td>';
                            									
                                							
                                							        	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_paper*$invoice['currency_rate']),2).'</p></td>';
                            								
                            										
                            									$html.='</tr>';
                            								}
                            								
                            							if($con_no == '1')
        													{
        
        															$con_id = "'".$group_con_id."'";
                            								    	$con_box="'#con_box'";
                            				    	               $con_kgs="'#con_kgs'";
        														$html.='<tr style="font-size: 12px;">
        																	
        																	<td colspan="3" class="no_border"><div><strong>'.$gls_series.') Plastic Disposable Lid / Container</strong><br>
        																		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39241090<br>
        																		</div></td>';
        																		   if($pdf!=0){
                                                				    	           $html.='<td class="no_border" align="center"  >'. $con_box_no.' '.$no_of_package.'</td>'; 
                                                				    	           $html.='<td class="no_border" align="center" > '. $total_net_w_con.' '.$identification_marks.'</td>';
                                                           				
        			                                        			            }else{
        			                        									        $html.='<td class="no_border" valign="top"><input type="text" name="con_box" onchange="change_qty_per_kg('.$con_id.',0,'.$con_box.','.$invoice['invoice_status'].')" value='.$con_box_no.''.$no_of_package.'  id="con_box"></td>';
        			                               					                    $html.='<td class="no_border" valign="top"><input type="text" name="paper_kgs" onchange="change_qty_per_kg('.$con_id.',1,'.$con_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_con.''.$identification_marks.'"  id="str_kgs"></td>';
        			                                        			            }
        																	/*if(ucwords(decode($invoice['transportation']))=='Sea')
        																		$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_con .'<br>'.$tot_con_qty.' Nos </p></td>';
        																	else*/
        																	    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_con_qty.' NOS</p></td>';
        														
        																/*if(ucwords(decode($invoice['transportation']))=='Sea')
        																{
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_con/$total_net_w_con,8).'</p></td>';
        																}else{*/
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_con/$tot_con_qty)*$invoice['currency_rate'],8).'</p></td>';
        																//}	
        																$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_con*$invoice['currency_rate'],2).'</p></td>';
        														
        														
        															
        														$html.='</tr>';
        													}
        													if($gls_no == '1')
        													{
        
        														$gls_id = "'".$group_gls_id."'";
                            								    	$gls_box="'#gls_box'";
                            				    	               $con_kgs="'#gls_kgs'";
        														$html.='<tr style="font-size: 12px;">
        																
        																	<td colspan="3" class="no_border"><strong>'.$gls_series.') Plastic Glasses</strong><br>
        																	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39241090<br>
        																	</td>';
        
        																	   if($pdf!=0){
                                                				    	           $html.='<td class="no_border" align="center" >'. $gls_box_no.' '.$no_of_package.'</td>'; 
                                                				    	           $html.='<td class="no_border" align="center" >'. $total_net_w_gls.' '.$identification_marks.'</td>';
                                                           				
        			                                        			            }else{
        			                        									        $html.='<td class="no_border" valign="top"><input type="text" name="gls_box" onchange="change_qty_per_kg('.$gls_id.',0,'.$gls_box.','.$invoice['invoice_status'].')" value='.$gls_box_no.''.$no_of_package.'  id="gls_box"></td>';
        			                               					                    $html.='<td class="no_border" valign="top"><input type="text" name="gls_kgs" onchange="change_qty_per_kg('.$gls_id.',1,'.$con_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_gls.''.$identification_marks.'"  id="gls_kgs"></td>';
        			                                        			            }
        																	/*if(ucwords(decode($invoice['transportation']))=='Sea')
        																		$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_gls .'<br>'.$tot_gls_qty.' Nos </p></td>';
        																	else*/
        																	    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_gls_qty.' NOS</p></td>';
        														/*if($status==1)
        														{	
        																if(ucwords(decode($invoice['transportation']))=='Sea')
        																{
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_gls/$total_net_w_gls,8).'</p></td>';
        																}else{*/
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_gls/$tot_gls_qty)*$invoice['currency_rate'],8).'</p></td>';
        																//}	
        																$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_gls*$invoice['currency_rate'],2).'</p></td>';
        														
        															
        														$html.='</tr>';
        													}
        													if($chair_no == '1')
        													{
        
        														$chair_id = "'".$group_chair_id."'";
                            								    	$chair_box="'#gls_box'";
                            				    	               $chair_kgs="'#gls_kgs'";
        														$html.='<tr style="font-size: 12px;">
        																
        																	<td colspan="3" class="no_border"><strong>'.$chair_series.') CHAIR</strong><br>
        																	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 94036000<br>
        																	</td>';
        
        																	   if($pdf!=0){
                                                				    	           $html.='<td class="no_border" align="center" >'. $chair_box_no.' '.$no_of_package.'</td>'; 
                                                				    	           $html.='<td class="no_border" align="center" >'. $total_net_w_chair.' '.$identification_marks.'</td>';
                                                           				
        			                                        			            }else{
        			                        									        $html.='<td class="no_border" valign="top"><input type="text" name="gls_box" onchange="change_qty_per_kg('.$gls_id.',0,'.$gls_box.','.$invoice['invoice_status'].')" value='.$chair_box_no.''.$no_of_package.'  id="chair_box"></td>';
        			                               					                    $html.='<td class="no_border" valign="top"><input type="text" name="gls_kgs" onchange="change_qty_per_kg('.$gls_id.',1,'.$con_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_chair.''.$identification_marks.'"  id="chair_kgs"></td>';
        			                                        			            }
        																	/*if(ucwords(decode($invoice['transportation']))=='Sea')
        																		$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_gls .'<br>'.$tot_gls_qty.' Nos </p></td>';
        																	else*/
        																	    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_chair_qty.' NOS</p></td>';
        														/*if($status==1)
        														{	
        																if(ucwords(decode($invoice['transportation']))=='Sea')
        																{
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_gls/$total_net_w_gls,8).'</p></td>';
        																}else{*/
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_chair/$tot_chair_qty)*$invoice['currency_rate'],8).'</p></td>';
        																//}	
        																$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_chair*$invoice['currency_rate'],2).'</p></td>';
        														
        															
        														$html.='</tr>';
        													}	
        													if($oxygen_absorbers_no == '1')
        													{
        
        														$oxygen_absorbers_id = "'".$group_oxygen_absorbers_id."'";
                            								    	$oxygen_absorbers_box="'#oxygen_absorbers_box'";
                            				    	               $oxygen_absorbers_kgs="'#oxygen_absorbers_kgs'";
        														$html.='<tr style="font-size: 12px;">
        																
        																	<td colspan="3" class="no_border"><strong>'.$oxygen_absorbers_series.') OXYGEN ABSORBERS </strong><br>
        																	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 38249990<br>
        																	</td>';
        
        																	   if($pdf!=0){
                                                				    	           $html.='<td class="no_border" align="center" >'. $oxygen_absorbers_box_no.' '.$no_of_package.'</td>'; 
                                                				    	           $html.='<td class="no_border" align="center" >'. $oxygen_absorbers_identification_marks.' '.$identification_marks.'</td>';
                                                           				
        			                                        			            }else{
        			                        									        $html.='<td class="no_border" valign="top"><input type="text" name="oxygen_absorbers_box" onchange="change_qty_per_kg('.$gls_id.',0,'.$gls_box.','.$invoice['invoice_status'].')" value='.$oxygen_absorbers_box_no.''.$no_of_package.'  id="oxygen_absorbersoxygen_absorbersx"></td>';
        			                               					                    $html.='<td class="no_border" valign="top"><input type="text" name="oxygen_absorbers_kgs" onchange="change_qty_per_kg('.$gls_id.',1,'.$con_kgs.','.$invoice['invoice_status'].')" value="'.$oxygen_absorbers_identification_marks.''.$identification_marks.'"  id="oxygen_absorbersoxygen_absorberss"></td>';
        			                                        			            }
        																	/*if(ucwords(decode($invoice['transportation']))=='Sea')
        																		$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_gls .'<br>'.$tot_gls_qty.' Nos </p></td>';
        																	else*/
        																	    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_oxygen_absorbers_qty.' NOS</p></td>';
        														/*if($status==1)
        														{	
        																if(ucwords(decode($invoice['transportation']))=='Sea')
        																{
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_gls/$total_net_w_gls,8).'</p></td>';
        																}else{*/
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_oxygen_absorbers/$tot_oxygen_absorbers_qty)*$invoice['currency_rate'],8).'</p></td>';
        																//}	
        																$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_oxygen_absorbers*$invoice['currency_rate'],2).'</p></td>';
        														
        															
        														$html.='</tr>';
        													}
        													if($silica_gel_no == '1')
        													{
        
        														$silica_gel_id = "'".$group_silica_gel_id."'";
                            								    	$silica_gel_box="'#oxygen_absorbers_box'";
                            				    	               $silica_gel_kgs="'#oxygen_absorbers_kgs'";
        														$html.='<tr style="font-size: 12px;">
        																
        																	<td colspan="3" class="no_border"><strong>'.$silica_gel_series.') SILICA GEL </strong><br>
        																	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 38249025<br>
        																	</td>';
        
        																	   if($pdf!=0){
                                                				    	           $html.='<td class="no_border" align="center" >'. $silica_gel_no_of_package.' '.$no_of_package.'</td>'; 
                                                				    	           $html.='<td class="no_border" align="center" >'. $silica_gel_identification_marks.' '.$identification_marks.'</td>';
                                                           				
        			                                        			            }else{
        			                        									        $html.='<td class="no_border" valign="top"><input type="text" name="oxygen_absorbers_box" onchange="change_qty_per_kg('.$silica_gel_id.',0,'.$silica_gel_box_no.','.$invoice['invoice_status'].')" value='.$silica_gel_box_no.''.$no_of_package.'  id="silica_gel"></td>';
        			                               					                    $html.='<td class="no_border" valign="top"><input type="text" name="oxygen_absorbers_kgs" onchange="change_qty_per_kg('.$silica_gel_id.',1,'.$total_net_w_silica_gel.','.$invoice['invoice_status'].')" value="'.$total_net_w_silica_gel.''.$identification_marks.'"  id="silica_gel"></td>';
        			                                        			            }
        																	/*if(ucwords(decode($invoice['transportation']))=='Sea')
        																		$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_gls .'<br>'.$tot_gls_qty.' Nos </p></td>';
        																	else*/
        																	    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_silica_gel_qty.' NOS</p></td>';
        														/*if($status==1)
        														{	
        																if(ucwords(decode($invoice['transportation']))=='Sea')
        																{
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_gls/$total_net_w_gls,8).'</p></td>';
        																}else{*/
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_silica_gel/$tot_silica_gel_qty)*$invoice['currency_rate'],8).'</p></td>';
        																//}	
        																$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_silica_gel*$invoice['currency_rate'],2).'</p></td>';
        														
        														
        														$html.='</tr>';
        													}
        													if($val_no == '1')
        													{
        
        															$val_id = "'".$group_val_id."'";
                            								    	$val_box="'#val_box'";
                            				    	               $val_kgs="'#val_kgs'";
        														$html.='<tr>
        																	
        																	<td colspan="3" class="no_border"><div><strong>'.$val_series.') Plastic Cap</strong><br>
        																	    V-105 <br>
        																		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39232100<br>
        																		</div></td>';
        
        
        																	   if($pdf!=0){
                                            				    	           $html.='<td class="no_border" align="center" >'. $val_no_of_package.' '.$no_of_package.'</td>'; 
                                            				    	           $html.='<td class="no_border" align="center" >'. $val_identification_marks.' '.$identification_marks.'</td>';
                                                       				
        		                                        			            }else{
        		                        									        $html.='<td class="no_border" valign="top"><input type="text" name="val_box" onchange="change_qty_per_kg('.$val_id.',0,'.$val_box.','.$invoice['invoice_status'].')" value='.$val_box_no.''.$no_of_package.'  id="val_box"></td>';
        		                               					                    $html.='<td class="no_border" valign="top"><input type="text" name="val_kgs" onchange="change_qty_per_kg('.$val_id.',1,'.$val_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_val.''.$identification_marks.'"  id="val_kgs"></td>';
        		                                        			            }
        																/*	if(ucwords(decode($invoice['transportation']))=='Sea')
        																		$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_val .'<br>'.$tot_val_qty.' Nos </p></td>';
        																	else*/
        																	    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_val_qty.' NOS</p></td>';
        														/*	if(ucwords(decode($invoice['transportation']))=='Sea')
        																{
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_valve/$total_net_w_val,8).'</p></td>';
        																}else{*/
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_valve/$tot_val_qty)*$invoice['currency_rate'],8).'</p></td>';
        															//	}	
        																$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_valve*$invoice['currency_rate'],2).'</p></td>';
        													
        															
        														$html.='</tr>';
        													}
        													 
                            						
                            						
                            						
                                     $html.='</tr>';
                                     	if($pouch_name=='POUCHES'){ 
                                     	$html.=' <tr id="one_tr" style="font-size: 12px;">';
                            							
                            											 
                            						 $html.='<td colspan="3"  class="no_border">
                            						                
                            						         Printed Flexible packaging material of one layer or printed or unprinted 
                            						         adhesive laminated/ extrusion laminated flexible packaging material of multilayers 
                            						         of relevant substrate with or without hotmel in the form of Rolls/strips/sheets/
                            						         labels/wrapers or in Pouch from (for pouch).';

                            									
                            									$html.='</td>
                            										<td class="no_border"></td>'; 
                            						 
                            						
                            								 $html.='<td class="no_border"></td> 
                            								         <td class="no_border"></td>
                            								         <td class="no_border"></td>
                            								       
                            										 <td class="no_border"></td></tr>';
                            									
                            		 }
                            		 	$html.=' <tr id="one_tr" style="font-size: 12px;"> 
                            					';
                            					
                            					
                            				
                            				
                            					$scoop=$roll=$mailer=$storezo=$paper=$con=$gls=$valve =$cylinder= $freight=$oxygen_absorbers=$silica_gel=$set_up_cost='';
                            					
                            				
                            					if($total_amt_scoop!='0.00'){
                            					    $scoop=',SCOOP '.$currency['currency_code'].' :'.number_Format($total_amt_scoop,2);
                            					}
                            				
                            				
                            					if($total_amt_roll!='0.00'){
                            					    $roll=',ROLL '.$currency['currency_code'].':'.number_Format($total_amt_roll,2);
                            					}
                            					if($total_amt_mailer!='0.00'){
                            					    $mailer=',MAILER BAG '.$currency['currency_code'].' :'.number_Format($total_amt_mailer,2);
                            					}
                            					if($total_amt_storezo!='0.00'){
                            					    $storezo=',STOREZO BAG '.$currency['currency_code'].' :'.number_Format($total_amt_storezo,2);
                            					}
                            					if($total_amt_paper!='0.00'){ 
                            					    $paper=', PAPER '.$currency['currency_code'].':'.number_Format($total_amt_paper,2);
                            					}
                            					if($total_amt_con!=''){
                            					    $con=', LID/CONTAINER '.$currency['currency_code'].':'.number_Format($total_amt_con,2);
                            					}
                            					if($total_amt_gls!=''){
                            					    $gls=',PLASTIC GLASSES '.$currency['currency_code'].':'.number_Format($total_amt_gls,2);
                            					}
                            					if($total_amt_valve!=''){
                            					    $valve=', PLASTIC CAP '.$currency['currency_code'].':'.number_Format($total_amt_valve,2);
                            					}
                            					if($total_amt_chair!=''){
                            					    $chair=', CHAIR '.$currency['currency_code'].':'.number_Format($total_amt_chair,2);
                            					}
                            						//printr($total_amt_silica_gel);
                            					if($total_amt_silica_gel!=''){
                            					      $silica_gel=', SILICA GEL '.$currency['currency_code'].':'.number_Format($total_amt_silica_gel,2);
                            					} 
                            			    	if($total_amt_oxygen_absorbers!=''){
                            					    $oxygen_absorbers=', OXYGEN ABSORBERS '.$currency['currency_code'].':'.number_Format($total_amt_oxygen_absorbers,2);
                            					}
                            				
                            	          
                            		        	  if($air_f=='2' && $amt=='0'){ //only for  roll 
                                					          $cylinder='('.$currency['currency_code'].' '.number_Format($total_amt_roll-$invoice['cylinder_charges'],2).' ROLL+ CYLINDER '.$currency['currency_code'].' :'.number_Format($invoice['cylinder_charges'],2).''.$string.')';
                                			                  $roll="";$amt=$total_amt_roll;
                            			            
                            			            	}else{
                            			            	//	printr($invoice['transport']);
                            			            	//	printr($invoice['cylinder_charges']); 
                                        					if($invoice['cylinder_charges']!='0.00'  && ($invoice['transport']=='air' || $invoice['transport']=='road')){
                                        					       $f_amt=$amt-($invoice['cylinder_charges']+$invoice['tool_cost']);$string='';
                                        					    
                                        					    
                                        					    	if($invoice['tool_cost']!='0.00'){
                                        					              $set_up_cost=', + SET UP COST '.$currency['currency_code'].' :'.number_Format($invoice['tool_cost'],2);
                                        				            	}
                                        					   
                                        					  
                                        					     if($invoice['sales_invoice_id']=='1247')
                                        					     {
                                        					        $f_amt = $f_amt-1200;
                                        					        $string = ',+ DESIGN CHARGES '.$currency['currency_code'].' :1200.00)';
                                        					     }
                                        					     //printr($f_amt);
                                        					      
                                        					      $cylinder='('.$currency['currency_code'].' '.number_Format($f_amt,2).',+ CYLINDER '.$currency['currency_code'].' :'.number_Format($invoice['cylinder_charges'],2).''.$string.' '.$set_up_cost.')';
                                        					}
                                                					
                            			            	}
                            			            
                            					if($invoice['invoice_id']=='2177'){
                            					    $invoice['tran_charges']=$invoice['tran_charges']-$insurance;
                            					}
                            			        
                            			        
                            			    
                            					if($invoice['tran_charges']!='0.00' && $insurance!='0.00' ){
                            					    $freight=', FREIGHT  '.$currency['currency_code'].' :'.number_Format(($invoice['tran_charges']+$invoice['extra_tran_charges']+$insurance),2).'(FREIGHT '.$currency['currency_code'].':'.number_Format($invoice['tran_charges'],2).'+INSURANCE '.$currency['currency_code'].':'.number_Format($insurance,2).')';
                            					} else if($invoice['tran_charges']!='0.00'){
                            					     $freight=', FREIGHT  '.$currency['currency_code'].' :'.number_Format($invoice['tran_charges']+$invoice['extra_tran_charges']+$insurance,2);
                            					}else if($invoice['tran_charges']='0.00' && $invoice['extra_tran_charges']!='0.00'){
                            					    $insurance_data='';
                            					    if($insurance!='0.00')
                            					       $insurance_data= '(FREIGHT '.$currency['currency_code'].':'.number_Format($invoice['tran_charges'],2).'+INSURANCE '.$currency['currency_code'].':'.number_Format($insurance,2).')';
                            				
                            				          $freight=', FREIGHT  '.$currency['currency_code'].' :'.number_Format($invoice['extra_tran_charges']+$insurance,2).''.$insurance_data.')';
                            					}
                            				//	printr($amt);
                            			           
                            					 $html.='<td colspan="3"  class="no_border">
                            					  
                            					        <b><br>'.$currency['currency_code'].':'.number_Format($invoice['currency_rate'],2).' RS.,'.$currency['currency_code'].' : '.number_Format($amt,2).' '.$cylinder.' '.$freight.' '.$scoop.' '.$roll.''.$mailer.' '.$storezo.' '.$paper.' '.$con.' '.$gls.' '.$valve.' '.$oxygen_absorbers.' '.$silica_gel.' '.$chair;
                            							    
                            									$html.='</b></td>
                            										<td class="no_border"></td>'; 
                            						 
                            						
                            								 $html.='<td class="no_border"></td> 
                            								         <td class="no_border"></td>
                            								         <td class="no_border"></td>
                            								       
                            										 <td class="no_border"></td></tr>';	$html.=' <tr id="one_tr">
                            								 ';
                            								$container_no=$seal_no=$rfid_no='';	  
                            						 $html.='<td colspan="3"  class="no_border"><b>
                            						    G.W- '.number_Format($totgross_weight,3).'KGS<br>N-W-'.number_Format($total_identification_marks,3).'KGS</b>';
                            						     if(str_replace(',', '', $cond_net_weight) >'200' && $invoice['invoice_date']>'2018-03-31'  &&  $invoice_inv_data['country_destination']!='169')
                            						             $html.='<br>SHIPMENT UNDER DUTY DRAWBACK SCHEME';
                            						        
                            							    	if($invoice['transport']!='air')
        							                                {
        							                                    if($invoice['container_no']!=''){
        							                                       $container_no='<br><b>CONTAINER NO :'.$invoice['container_no'].'<br>';
        							                                    }
        							                                    if($invoice['container_no']!=''){
        							                                       $seal_no='<b>SEAL NO :'.$invoice['seal_no'].'<br>';
        							                                    }
        							                                    if($invoice['rfid_no']!=''){
        							                                       $rfid_no='<b>RFID E SEAL NO-'.$invoice['rfid_no'].'<br>';
        							                                    }
                                    							  	$html.='<br><b>'.$invoice['pallet_detail'].'</b>'.$container_no.''.$seal_no.''.$rfid_no;
        							                                }else if($invoice_inv_data['show_pallet']=='1'){
        							                                    	$html.='<br><b>'.$invoice['pallet_detail'].'</b>';
        							                                }
                            								$html.='</td>
                            										<td class="no_border"></td>'; 
                            						 
                            						
                            								 $html.='<td class="no_border"></td> 
                            								         <td class="no_border"></td>
                            								         <td class="no_border"></td>
                            								       
                            										 <td class="no_border"></td></tr>';
                            	
                            	
                            	   /* if($invoice['transport']=='air')
        							{
        							  $total_amt_val=  $total_amt_val+$invoice['cylinder_charges'];
        							}*/
        							
        						//	printr($total_amt_val);
        					 if($invoice['tran_charges']!='0.000')
        					    $invoice['tran_charges']=$invoice['tran_charges'];
        					  else
        					    $invoice['tran_charges']=0; 
                            
                        
                                if((($invoice_inv_data['country_destination']=='172'   || $invoice_inv_data['country_destination']=='253') )&&($invoice_inv_data['invoice_id']!='1899')&& ($invoice['transport']=='air')){//|| $invoice_inv_data['country_destination']=='125'
                            	    if($invoice_inv_data['invoice_date']<'2018-10-12'){	
                            	   
                            	         $final_amount=$total_amt_val;
                            	  
                            	    }else{
                            	        $final_amount=$total_amt_val-$invoice['tran_charges'];
                            	    }
                                }
                        
                                 
                               else{
                                   
                                        $final_amount=$total_amt_val-$invoice['tran_charges'];
                                        
                                      }
                                      
                                   
                           
                               
                            		$excies = '';
                            		$tax ='';
                            			
                            
                            		if($currency['currency_code'] == 'INR') {
                            			$Total_price = round($Total_price);
                            		} 
                            
                            
                            
                        
                            if($invoice['invoice_id']=='2177'){
                            					    $final_amount=$final_amount-$insurance;
                            }
                            	
                                         if($invoice['tran_charges']=='0.00' && $invoice['extra_tran_charges']!='0.00')
                                 		        {
                                 		            $Total_price = $final_amount + $invoice['extra_tran_charges']+$insurance;
                                 		        }else{
                                 		            $Total_price = $final_amount + $invoice['tran_charges']+$invoice['extra_tran_charges']+$insurance;
                                 		        }
                            											
                            		
                            	   //	printr($insurance);
                            	$Total_price=$Total_price*$invoice['currency_rate'];
                                   
                                if($invoice['transport'] == 'air' && $invoice['igst_status']==1)
                                	{
                                		 $Total_price =  $Total_price+((($final_amount*$invoice['currency_rate'])*18)/100);   
                                	}
                            	 if($invoice['transport'] == 'sea')
                         		        {
                         		     $Total_price =  $Total_price+((($final_amount*$invoice['currency_rate'])*18)/100);     
                         		        }
                               
                                     
                                    
                                     $html .='<tr>
                            				
                            					<td  colspan="3"><div align="right"><strong>Total..</strong></div></td>
                            					<td  align="center" >'.$total_no_of_package.' BOXES</td>
                            					<td align="center" >'.$total_identification_marks.' KGS</td>
                            					<td align="center" >'.$invoice_qty['total_qty'].'</td>
                            					<td ></td>
                            					<td ></td>
                            				
                            					</tr>';						
                            				
                                           
                                    if($invoice['transport'] == 'air' || $invoice['transport'] == 'road'){
                                        
                                         $transport_line ='LUT WITHOUT PAYMENT OF IGST';
                                      	if($invoice['invoice_date']>='2019-04-01'){
                                             $invoice['tran_desc']='"LETTER OF UNDERTAKING APPLICATION REFERENCE NUMBER-AA2403180293296(ARN)';
                                         }else{
                                             $invoice['tran_desc']='"LETTER OF UNDERTAKING APPLICATION REFERENCE NUMBER-AD240319004221P(ARN)';
                                         }
                                       }else{
                                                 $transport_line ='EXPORT AGAINST PAYMENT OF IGST';
                                                $invoice['tran_desc']=$invoice['tran_desc'];
                                      }
                                    
                                    
                
                        }
                        
      elseif($invoice['invoice_status']=='2' ){ 
                            
                            
                        // printr($invoice_product_second[0]['product_name']);
            					
                     $html.='<tr style="font-size: 12px;">';
                            $html.='</td>';
                            $html.='<td colspan="3" class="no_border"><b>HSN NO.'.$invoice['hscode'].'</b></td>';
                            	$p_roll_id = "'".$invoice_product_second[0]['sales_invoice_id']."'";
                            	$p_roll_box="'#p_roll_box'";
                                $p_roll_kgs="'#p_roll_kgs'";
                             // $marks=$package="";
                              	$first='false';
                            if(is_numeric($invoice_product_second[0]['identification_marks']))
    								{
    								    foreach($invoice_product_second as $product){ 
    								          $mes_name=$this->getMeasurementName($product['measurement']);
    								        //  printr($mes_name);
    								       
                                                   if($product['product_id']!='68')
                		                    	    { 
                		                    	        
                                					   if($first=='false')
                                            				{
                                            				   $marks='KGS';
                                            				}
                                				    }else{
                                				       
                                				          $marks= $mes_name['measurement'];
                                				    }
                                             }
                                
    								}else{
    								      $marks=$package="";
    								}
                                
                                
                               
                  		        if($pdf!=0){
        			    	        $html.='<td class="no_border" >'. $invoice_product_second[0]['no_of_packages'].' '.$package.'</td>'; 
        			    	        $html.='<td class="no_border" >'. $invoice_product_second[0]['identification_marks'].' '.$marks.'</td>';
               				
                		            }else{
                					$html.='<td class="no_border" valign="top"><input type="text" name="roll_box" onchange="change_qty_per_kg('.$p_roll_id.',0,'.$p_roll_box.','.$invoice['invoice_status'].')" value="'.$invoice_product_second[0]['no_of_packages'].''.$package.'"  id="p_roll_box"></td>';
                   	                $html.='<td class="no_border" valign="top"><input type="text" name="roll_kgs" onchange="change_qty_per_kg('.$p_roll_id.',1,'.$p_roll_kgs.','.$invoice['invoice_status'].')" value="'. $invoice_product_second[0]['identification_marks'].''.$marks.'"  id="p_roll_kgs"></td>';
                		            }
                			$html.='<td class="no_border" ></td>
        					<td class="no_border" ></td>
        					<td class="no_border" ></td>';
        			$html.='</tr>';
                    $total_qty=$sub_total=0;
                    foreach($invoice_product_second as $product){
                        $mes_name=$this->getMeasurementName($product['measurement']);
                        
                        if($product['product_id']==67)
                             $mes_name['measurement']='KGS';
                          
                          $total_qty=$total_qty+$product['qty'];
                        $sub_total=$sub_total+$product['qty']*$product['rate'];
                    	$html.='<tr style="font-size: 12px; " >
                            											
        				<td colspan="3" class="no_border"><strong>'.$product['product_name'].' '.$product['color_text'].' (SIZE: '.$product['size'].' '.$mes_name['measurement'].') </strong><br></td>';
        					$p_roll_id = "'".$invoice_product_second[0]['sales_invoice_id']."'";
        					$p_roll_box="'#p_roll_box'";
                            $p_roll_kgs="'#p_roll_kgs'";
                            $no_of_package='ROLL';
        			      	$html.='<td class="no_border" ></td>
            				    	<td class="no_border" ></td>';
        					$html.='<td class="no_border" valign="top"><p align="center">'.$product['qty'].' '.$mes_name['measurement'].'</p></td>';
        					$html.='<td class="no_border" valign="top"><p align="center">'.number_format($product['rate'],2).'</p></td>';
        					$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($product['qty']*$product['rate']),2).'</p></td>';
        			     
        			$html.='</tr>';
                    }
                     
                        
                    $Total_price=$sub_total+$invoice['tran_charges'];
                  
            		
                     $html .='<tr >  
                				
                					<td  colspan="3"><div align="right"><strong>Total..</strong></div></td>
                					<td >'.$invoice_product_second[0]['no_of_packages'].'</td>
                					<td >-</td>
                					<td >'.$total_qty.'</td>
                					<td ></td>
                					<td ></td>
                				
                					</tr>';	
                	
                				
                	if($invoice['taxation']=='Out Of Gujarat'){
                	    //igst 
                	    $igst=(($Total_price*$invoice['igst'])/100);
                	}
                
                	else if($invoice['taxation']=='SEZ Unit No Tax'){
                         $igst=$cgst=$sgst=0;
                	}else{
                	    //cgst sgst
                	    
                	   
                	        $cgst=(($Total_price*$invoice['cgst'])/100);
                	        $sgst=(($Total_price*$invoice['sgst'])/100);
                	        
                	      
                	}
                	$sub_total1=$Total_price; 
                	$Total_price=$Total_price+$igst+$cgst+$sgst;
                
               //     printr($Total_price);
                }
                else{
                    //proforma
              
              $row=count($alldetails);
            //  printr($alldetails[0]['no_of_packages']);
              	$first='false';
                 $proforma_box1="'#proforma_box'";
        		 $total_qty=0;
        		 $sub_total=$sub_total1=0;
        		 
        		 //rowspan="'.$row.'"
        		 $Total_price=0;
                    /*$html.='<tr style="font-size: 12px;" >';
                            //$html.='</td>';
                            $html.='<td colspan="3" class="no_border"></td>';
                  		     if($pdf!=0){
                  		        $html.='<td class="no_border" align="center" '.$colspan.' >'.$alldetails[0]['no_of_packages'].'</td>';
                  		        $html.=$mark_value;
                  		    }else{
                  		        	$html.='<td class="no_border" '.$colspan.' ><input type="text" name="proforma_box" onchange="change_qty_per_kg('.$invoice_no.',0,'.$proforma_box.','.$invoice['invoice_status'].')" value="'.$alldetails[0]['no_of_packages'].'" id="proforma_box"></td>';
                                     $html.=$mark_value;
                  		    }
                			$html.='<td class="no_border" ></td>
        					<td class="no_border" ></td>
        					<td class="no_border" ></td>';
        			$html.='</tr>';*/
        			$no='1';	
                    foreach($alldetails as $details){
                         $product_name=$color_text=$dimension='';  
                   //  printr($details); 
                   $proforma_kgs="'#proforma_kgs_".$details['sales_invoice_product_id']."'";
                     $proforma_box="'#proforma_box_".$details['sales_invoice_product_id']."'";
                      $product_code_details=$this->product_code_details($details['product_code_id']);
                        $total_qty=$total_qty+$details['qty'];
                        $sub_total=$sub_total+$details['qty']*$details['rate'];
                        $sub_total1=$sub_total1+$details['qty']*$details['rate'];
                           $html .='<tr class="pro_div" >
            				
            					<td  colspan="3" style="'.$style_oxi.'" >';
            			    	if($details['product_id']!='11' && $details['product_id']!='6' && $details['product_id']!='51' &&  $details['product_id']!='60' && $details['product_id']!='10' && $details['product_id']!='23' && $details['product_id']!='18' && $details['product_id']!='34'&& $details['product_id']!='47'&& $details['product_id']!='48' && $details['product_id']!='63'&& $details['product_id']!='72' && $details['product_id']!='37' && $details['product_id']!='38')
		                    	{
            					   if($first=='false')
                        				{
                        				     $html.='<b>PRINTED OR UNPRINTED FLEXIBLE PACKAGING MATERIAL OF POUCHES <br>HSN NO.39232990</b><br><br>';
                        				     $first = 'true';
                        				}
            					}
            					
            				if($details['product_id']=='6')
            			    	$kg='KGS';
            			    elseif($details['product_id']=='37' || $details['product_id']=='38' ) //VivekBhai's changes
            			        $kg='Pcs';
            				else
            				    $kg='NOS';
            			
            				$html.='<b>';  
            					if($details['product_id']=='11')
            					    $html.='Plastic Scoop- 39235090';
            					elseif($details['product_id']=='18')
            				    	$html.='Storezo - 39232100';
            				    elseif($details['product_id']=='10')
            				    	$html.='Mailer Bag - 39232990'; 
            				    elseif($details['product_id']=='35')
            				    	$html.='Tintie- 39232990';  
            				    elseif($details['product_id']=='6')
            				    	$html.='Supply in Rolls - 39201012';
            				    elseif($details['product_id']=='37')
            				    	$html.='<center>Oxygen Absorbers - 38249990 </center>';
            				    elseif($details['product_id']=='38')
            				    	$html.='<center>Silica Gel / Moisture Absorbers- 38249025 </center>';
            				    elseif($details['product_id']=='51')
            				    	$html.=' CYLINDER- 84425010'; 
            				    elseif($details['product_id']=='60')
            				    	$html.='DESIGN SERVICE- 998399 <br>';
            				     
            			$valve_name=$zipper_name=$acc_name=$spout_name='';    /*	
        				 if($details['valve']=='With Valve')
									$valve_name=$details['valve'];
						if($details['zipper_name']!='No zip')
					    	$zipper_name=$details['zipper_name'];
						if($details['spout_name']!='No Spout')
							$spout_name=$details['spout_name'];
					   if($details['product_accessorie_name']!='No Accessorie')	
							$acc_name=$details['product_accessorie_name'];*/
									
									
									
            				if($details['color_text']!='')
            					   $color_text='<b>'.$details['color_text'].'</b>';
            				/*else if($details['color_text']!='')
            				     $color_text='<b>'.$details['color_text'].'</b>';   $product_name='<b>Make of Pouch: </b>'.$details['product_name'].'<b>&nbsp;'.$zipper_name.'&nbsp;'.$valve_name.' &nbsp;'.$spout_name.' &nbsp;'.$acc_name.'</b><br>';*/
            				if($details['product_id']=='7' && $set_user_id=='6')
            				    $details['product_name']='Standing Pouch';
            			    if($details['product_name']!='' && $set_user_id!='39')
            			        $product_name='<b>Make of Pouch: </b>'.$details['product_name'].' <b>'.$valve_name.'</b><br>';
            			    
            				if($details['dimension']!='0.000x0.000x0.000')
            					   $dimension='<b>SIZE:</b>'.$details['dimension'].'<br>';
            			if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's changes
                        {
                            	$html .='<center></b>'.$product_name.''.$dimension.'<b>Description: </b>'.$details['description'].'  '.$color_text.'</center></td>
            					';
                        }else{
                            	$html .='</b>'.$product_name.''.$dimension.'<b>Description: </b>'.$details['description'].'  '.$color_text.'</td>
            					';
                        }
                        
            		
            				
            			/*	if($_SESSION['ADMIN_LOGIN_SWISS']=='1')
	                    	{
            				     if($pdf!=0){
                          		        $html.='<td class="no_border" align="center"  >'.$alldetails[0]['no_of_packages'].'</td>';
                          		        $html.='<td class="no_border" align="center" >'.$alldetails[0]['identification_marks'].''.$identification_marks.'</td>';
                          		    }else{
                          		        	$html.='<td class="no_border"  ><input type="text" name="proforma_box" onchange="change_qty_per_kg('.$details['sales_invoice_product_id'].',0,'.$proforma_box.',0)" value="'.$details['no_of_packages'].'" id="proforma_box_'.$details['sales_invoice_product_id'].'"></td>';
                                             $html.='<td class="no_border"  ><input type="text" name="proforma_kgs" onchange="change_qty_per_kg('.$details['sales_invoice_product_id'].',0,'.$proforma_kgs.',0)" value='.$details['identification_marks'].''.$identification_marks.'   id="proforma_kgs_'.$details['sales_invoice_product_id'].'"></td>';
                          		    }
	                    	}else{*/
	                    	    //$html .=$td.'<td '.$colspan.' class="no_border" style="'.$style_oxi.'" ></td>';
	                    	    if($no==1)
	                    	    {
    	                    	    if($pdf!=0){
                          		        $html.='<td class="no_border" align="center" style="'.$style_oxi.'"'.$colspan.' ><br>'.$alldetails[0]['no_of_packages'].'</td>';
                          		        $html.=$mark_value;
                          		    }else{
                          		        	$html.='<td class="no_border" '.$colspan.' align="center" style="'.$style_oxi.'" ><input type="text" name="proforma_box" onchange="change_qty_per_kg('.$invoice_no.',0,'.$proforma_box1.','.$invoice['invoice_status'].')" value="'.$alldetails[0]['no_of_packages'].'" id="proforma_box"></td>';
                                             $html.=$mark_value;
                          		    }
	                    	    }
	                    	    else
	                    	        $html .=$td.'<td '.$colspan.' class="no_border" style="'.$style_oxi.'" ></td>';
	                    /*	}*/
	                    	if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's changes
                            {
                                $html.='<td class="no_border" align="center" style="'.$style_oxi.' '.$font.'" ><br>'.number_format($details['qty'],'0').'</td>
            					<td class="no_border" align="center" style="'.$style_oxi.''.$font.'"  ><br>'.number_format($details['rate'],'2').'</td>
            					<td  class="no_border" align="center"style="'.$style_oxi.''.$font.'" ><br>'.number_format($details['qty']*$details['rate'],'2').'</td>
            				
            					</tr>';	
                            }else{
                                $html.='<td class="no_border" align="center" style="'.$style_oxi.'" >'.$details['qty'].' '.$kg.'</td>
            					<td class="no_border" align="center" style="'.$style_oxi.'"  >'.$details['rate'].'</td>
            					<td  class="no_border" align="center"style="'.$style_oxi.'" >'.round($details['qty']*$details['rate']).'</td>
            				
            					</tr>';	
                            }
            	    	$no++;		 	
                    }  
                    //printr(($sub_total*$invoice['discount'])/100);
                    if($invoice['discount']!='0')
                    {
                        $discount = ($sub_total*$invoice['discount'])/100;
                        $sub_total = $sub_total - $discount;
                    }
                      
                   //printr($sub_total);
                        $Total_price=$sub_total+$invoice['tran_charges'];
                        //echo $Total_price;
                       if($invoice['taxation']=='SEZ Unit No Tax'){
                         $html .='<tr >  
                    				
                    					<td class="no_border" colspan="3"><div align="right"><strong>"SUPPLY MENT FOR SEZ UNDERTACKING WITHOUT PAYMENT OF INTEGRATED TAX"'.$invoice['pallet_detail'].'</strong></div></td>
                    				    	<td  class="no_border" ></td>
                    				    	<td  class="no_border" ></td>
                    				    	<td  class="no_border" ></td>
                    				    	<td  class="no_border" ></td>
                    				    	<td  class="no_border" ></td>
                    				
                    					</tr>';	 
                       }
                    		
                    	if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's changes
                        {
                            $html .='<tr >  
                    				
                    					<td  colspan="3" style="font-size:13px;'.$font.'"><div align="right"><strong >TOTAL&nbsp;&nbsp;&nbsp;&nbsp;</strong></div></td>
                    					<td  align="center"  style="font-size:13px;'.$font.'" '.$colspan.' >'.$alldetails[0]['no_of_packages'].'</td>
                    					'.$mark_td.'
                    					<td  align="center" style="font-size:13px;'.$font.'" >'.$total_qty.' '.$kg.'</td>
                    					<td ></td>
                    					<td ></td>
                    				
                    					</tr>';
                        }else{
                            $html .='<tr >  
                    				
                    					<td  colspan="3"><div align="right"><strong>Total..</strong></div></td>
                    					<td  align="center" '.$colspan.' >'.$alldetails[0]['no_of_packages'].'</td>
                    					'.$mark_td.'
                    					<td  align="center" >'.$total_qty.' '.$kg.'</td>
                    					<td ></td>
                    					<td ></td>
                    				
                    					</tr>';
                        }
                    					
                    	if($invoice['taxation']=='Out Of Gujarat'){
                    	    //igst 
                    	    $igst=(($Total_price*$invoice['igst'])/100);
                    	}	
                    	else if($invoice['taxation']=='SEZ Unit No Tax'){
                         $igst=$cgst=$sgst=0;
                    	}else{
                    	    //cgst sgst
                    	        $cgst=(($Total_price*$invoice['cgst'])/100);
                    	        $sgst=(($Total_price*$invoice['sgst'])/100);
                    	}
                    	
                    	$Total_price=$Total_price+$igst+$cgst+$sgst;
                      
                    } 
            } //end
            if($list == '0')
            {
                //printr($Total_price);
               /*  if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39'))
                    {
                        
                    }*/
                
                  if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's changes
                  {
                       $html.='<tr style="font-size: 11px;" >
                 			<td colspan="5" rowspan="1" valign="top">
                 			<table border="'.$border.'" width="100%" >
                 			    '.$table_tr.'
                 			    <tr><td style="'.$font.'"><b>'.$number=$this->convert_number_new(round($Total_price)).' Only.</b></td></tr>
                 			</table>';
                  }else{ 
                       $html.='<tr style="font-size: 11px;" >
                 			<td colspan="3" rowspan="1" valign="top">
                 			<table border="'.$border.'" width="100%" >
                 			    '.$table_tr.'
                 			    <tr><td ><b>'.$number=$this->convert_number_new(round($Total_price)).' Only.</b></td></tr>
                 			</table>';
                  }			 
        			
                    if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's changes
                    {
                        $html.='</td>
                   			<td colspan="3" rowspan="1" valign="top" ></p>';
                    }else{
                        $html.='</td>
                   			<td colspan="5" rowspan="1" valign="top"></p>';
                    } 
                               			if($invoice['invoice_status']!='1' && $invoice['invoice_status']!='2'){
                               			    //invoice
                                 		 	$html.='  <table style=" width: 100%; ">
                                 		        <tr>
                                     		        <th valign="top" style="'.$font.'"><div align="left">SUB TOTAL</b></th>
                                     		        <th valign="top"></th>
                                     		        <th valign="top"style="'.$font.'"><div align="right">'.number_Format ($final_amount*$invoice['currency_rate'],2).'</th>
                                 		        </tr>';
                                 		        //printr($invoice['discount']);
                                 		        
                                 		     if($invoice['transport'] == 'air' && $invoice['igst_status']==1){
                                     		      	 $html.='<tr>
                                             		            <td><div align="left">IGST</td>
                                                 		        <td>18%</td>
                                                 		        <td><div align="right">'.number_Format(((($final_amount*$invoice['currency_rate'])*18)/100),2).'</td>
                                             		        </tr>';
                                 		      }

                                 		        
                                 		        if($invoice['tran_charges']!='0')
                                 		        {
                                 		            
                                     		       $html.=' <tr>
                                         		            <td ><div align="left">FREIGHT CH.</td>
                                             		        <td></td>
                                             		        <td><div align="right">'.number_Format(($invoice['tran_charges']+$invoice['extra_tran_charges']+$insurance)*$invoice['currency_rate'],2).'</td>
                                         		        </tr>';
                                 		        }else  if($invoice['tran_charges']=='0.00' && $invoice['extra_tran_charges']!='0.00')
                                 		        {
                                     		       $html.=' <tr>
                                         		            <td ><div align="left">FREIGHT CH.</td>
                                             		        <td></td>
                                             		        <td><div align="right">'.number_Format(($invoice['extra_tran_charges']+$insurance)*$invoice['currency_rate'],2).'</td>
                                         		        </tr>';
                                 		        }
                                 		        if($invoice['transport'] == 'sea')
                                 		        {
                                 		             $html.='<tr>
                                         		            <td><div align="left">IGST</td>
                                             		        <td>18%</td>
                                             		        <td><div align="right">'.number_Format(((($final_amount*$invoice['currency_rate'])*18)/100),2).'</td>
                                         		        </tr>';
                                 		        }
                                 		        $html.='<tr>
                                     		            <th><div align="left">ROUND OFF</th>
                                         		        <th></th>
                                         		        <th><div align="right">'.number_Format((round($Total_price) - $Total_price),2).'</th>
                                     		        </tr>';
                                     		        
                             		             $html.='<tr>
                                     		            <th width="50%"><div align="left">GRAND TOTAL</th>
                                         		        <th></th>
                                         		        <th width="50%"><div align="right">'.round($Total_price).'</th>
                                     		        </tr>';
                                 		        
                                 		   	$html.=' </table>';
                               			}else{
                               			    //proforma
                               			    if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's Change
                                            {
                                                    $html.=' <table style=" width: 100%;">
                                     		        <tr>
                                         		        <td valign="top" style="font-size:12px;'.$font.'"><div align="left">SUB TOTAL</b></td>
                                         		        <td valign="top" style="'.$font.'"></td>
                                         		        <td valign="top"style="font-size:12px;'.$font.'"><div align="right">'.number_Format($sub_total,2).'</td>
                                     		        </tr>';
                                     		        
                                 		     
                                     		        if($invoice['tran_charges']!='0')
                                     		        {
                                         		       $html.=' <tr>
                                             		            <td ><div align="left" style="font-size:12px;'.$font.'">FREIGHT CH.</td>
                                                 		        <td></td>
                                                 		        <td><div align="right" style="font-size:12px;'.$font.'">'.number_Format($invoice['tran_charges'],2).'</td>
                                             		        </tr>';
                                     		        }
                                     		        
                                     		        if($invoice['taxation']=='Out Of Gujarat'){
                                     		         $html.='<tr>
                                             		            <td><div align="left" style="font-size:12px;'.$font.'">IGST @ '.number_format($invoice['igst'],2).'%</td>
                                                 		        <td></td>
                                                 		        <td><div align="right" style="font-size:12px;'.$font.'">'.number_Format($igst,2).'</td>
                                             		        </tr>';
                                     		        }else if($invoice['taxation']=='SEZ Unit No Tax'){
                                                             $igst=$cgst=$sgst=0;
                                                    	}else{
                                     		                 $html.='<tr>
                                             		            <td><div align="left" style="font-size:12px;'.$font.'">CGST @ 9%</td>
                                                 		        <td></td>
                                                 		        <td><div align="right" style="font-size:12px;'.$font.'">'.number_Format($cgst,2).'</td>
                                             		        </tr>';
                                             		             $html.='<tr>
                                             		            <td><div align="left" style="font-size:12px;'.$font.'">SGST @ 9%</td>
                                                 		        <td></td>
                                                 		        <td><div align="right" style="font-size:12px;'.$font.'">'.number_Format($sgst,2).'</td>
                                             		        </tr>';
                                     		         }
                                     		         
                                     		         $html.='<tr>
                                         		            <td><div align="left" style="font-size:12px;'.$font.'">ROUND OFF</td>
                                             		        <td></td>
                                             		        <td><div align="right" style="font-size:12px;'.$font.'">'.number_Format((round($Total_price) - $Total_price),2).'</td>
                                         		        </tr>';
                                 		             $html.='<tr>
                                         		            <td width="60%" style="font-size:12px;'.$font.'"><div align="left">GRAND TOTAL</td> 
                                             		        <td></td>
                                             		        <td width="40%" style="font-size:12px;'.$font.'"><div align="right">'.round($Total_price).'</td>
                                         		        </tr>';
                                     		        
                                     		   	$html.=' </table>';
                                            }else{
                                                    $html.=' <table style=" width: 100%; ">
                                     		        <tr> 
                                         		        <th valign="top"><div align="left">SUB TOTAL</b></th>
                                         		        <th valign="top"></th>
                                         		        <th valign="top"><div align="right">'.number_Format($sub_total1,2).'</th>
                                     		        </tr>';
                                     		        if($invoice['discount']!='0')
                                     		        {
                                     		            //$discount = ($sub_total*$invoice['discount'])/100;
                                         		       $html.=' <tr>
                                             		            <td ><div align="left">Discount ('.($invoice['discount'] + 0).' %)</td>
                                                 		        <td></td>
                                                 		        <td><div align="right">'.number_Format($discount,2).'</td>
                                             		        </tr>
                                             		        <tr>
                                                 		        <th valign="top"><div align="left">SUB TOTAL</b></th>
                                                 		        <th valign="top"></th>
                                                 		        <th valign="top"><div align="right">'.number_Format($sub_total,2).'</th>
                                             		        </tr>';
                                             		    
                                     		        }
                                     		        if($invoice['tran_charges']!='0')
                                     		        {
                                         		       $html.=' <tr>
                                             		            <td ><div align="left">FREIGHT CH.</td>
                                                 		        <td></td>
                                                 		        <td><div align="right">'.number_Format($invoice['tran_charges'],2).'</td>
                                             		        </tr>';
                                     		        }
                                     		        
                                     		        if($invoice['taxation']=='Out Of Gujarat'){
                                     		         $html.='<tr>
                                             		            <td><div align="left">IGST</td>
                                                 		        <td>'.$invoice['igst'].'%</td>
                                                 		        <td><div align="right">'.number_Format($igst,2).'</td>
                                             		        </tr>';
                                     		        }else if($invoice['taxation']=='SEZ Unit No Tax'){
                                                             $igst=$cgst=$sgst=0;
                                                    	}else{
                                     		                 $html.='<tr>
                                             		            <td><div align="left">CGST</td>
                                                 		        <td>'.$invoice['cgst'].'%</td>
                                                 		        <td><div align="right">'.number_Format($cgst,2).'</td>
                                             		        </tr>';
                                             		             $html.='<tr>
                                             		            <td><div align="left">SGST</td>
                                                 		        <td>'.$invoice['sgst'].'%</td>
                                                 		        <td><div align="right">'.number_Format($sgst,2).'</td>
                                             		        </tr>';
                                     		         }
                                     		         
                                     		         $html.='<tr>
                                         		            <th><div align="left">ROUND OFF</th>
                                             		        <th></th>
                                             		        <th><div align="right">'.number_Format((round($Total_price) - $Total_price),2).'</th>
                                         		        </tr>';
                                 		             $html.='<tr>
                                         		            <th><div align="left">GRAND TOTAL</th> 
                                             		        <th></th>
                                             		        <th><div align="right">'.round($Total_price).'</th>
                                         		        </tr>';
                                     		        
                                     		   	$html.=' </table>';
                                            }
                               			    
                               			}
        				$html.='</td>
                 		</tr>';
                 		$html.='<tr style="font-size: 12px;"><td colspan="8">
                 		
                 		<table style=" width: 100%; ">
                         '.$table_desc.'
                 		 </table>
                 		 </td>
        						
                     			
                    			
        						
        				</tr>
        		   </table>
        			 <div class="form-group">
        			 <div class="col-lg-9 col-lg-offset-3">';
        			 $html.='</div>
        			 </div>
        		 </div>
        		</div>
        	  </div>';
        	  
        	  
	    }
	    
	        	$sql = "UPDATE " . DB_PREFIX . "government_sales_invoice SET invoice_total_amount = '" .round($Total_price). "'WHERE sales_invoice_id='".$invoice_no."'";
	        	$data=$this->query($sql);
	        //	printr($html);die;
		return $html;
		
		
	}
	
	public function getActiveProductName($product_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE product_id='".$product_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
		
	public function getZipper($product_zipper_id) 
	{
		$sql = "select * from " . DB_PREFIX ."product_zipper where product_zipper_id = '".$product_zipper_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
	
	public function getColorName($color_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_color` WHERE pouch_color_id='".$color_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	

	
	




	//changed by jaya on 8-12-2016
	function convert_number_old($number) 
	{ 
		if (($number < 0) || ($number > 999999999)) 
		{ 
		throw new Exception("Number is out of range");
		} 
	
		$Gn = floor($number / 1000000);  /* Millions (giga) */ 
		$number -= $Gn * 1000000; 
		$kn = floor($number / 1000);     /* Thousands (kilo) */ 
		$number -= $kn * 1000; 
		$Hn = floor($number / 100);      /* Hundreds (hecto) */ 
		$number -= $Hn * 100; 
		$Dn = floor($number / 10);       /* Tens (deca) */ 
		$n = $number % 10;               /* Ones */ 
	
		$res = ""; 
	
		if ($Gn) 
		{ 
			$res .= $this->convert_number($Gn) . " Million"; 
		} 
	
		if ($kn) 
		{ 
			$res .= (empty($res) ? "" : " ") . 
			   $this->convert_number($kn) . " Thousand"; 
		} 
	
		if ($Hn) 
		{ 
			$res .= (empty($res) ? "" : " ") . 
				$this->convert_number($Hn) . " Hundred"; 
		} 
	
		$ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 
			"Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
			"Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", 
			"Nineteen"); 
		$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", 
			"Seventy", "Eigthy", "Ninety"); 
	
		if ($Dn || $n) 
		{ 
			if (!empty($res)) 
			{ 
				$res .= " and "; 
			} 
	
			if ($Dn < 2) 
			{ 
				$res .= $ones[$Dn * 10 + $n]; 
			} 
			else 
			{ 
				$res .= $tens[$Dn]; 
	
				if ($n) 
				{ 
					$res .= "-" . $ones[$n]; 
				} 
			} 
		} 
	
		if (empty($res)) 
		{ 
			$res = "zero"; 
		} 
	
		return $res; 
	}
	
	function convert_number_new($number) 

		{ 

    		if (($number < 0) || ($number > 999999999)) 

    		{ 

    			throw new Exception("Number is out of range");

    		} 

		    $Gn = floor($number / 100000);  /* Lacs (giga) */ 

    		$number -= $Gn * 100000; 

    		$kn = floor($number / 1000);     /* Thousands (kilo) */ 

    		$number -= $kn * 1000; 

    		$Hn = floor($number / 100);      /* Hundreds (hecto) */ 

    		$number -= $Hn * 100; 

    		$Dn = floor($number / 10);       /* Tens (deca) */ 

    		$n = $number % 10;               /* Ones */ 



    		$res = ""; 

		    if ($Gn) 

    		{ 

        		$res .= $this->convert_number_new($Gn) . " Lacs"; 

    		} 

		    if ($kn) 

    		{ 

        		$res .= (empty($res) ? "" : " ") . 

           		$this->convert_number_new($kn) . " Thousand"; 

    		} 

		    if ($Hn) 

    		{ 

        		$res .= (empty($res) ? "" : " ") . 

            	$this->convert_number_new($Hn) . " Hundred"; 

    		} 

		    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 

        	"Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 

        	"Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", 

        	"Nineteen"); 

    		$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", 

        	"Seventy", "Eigthy", "Ninety"); 

		    if ($Dn || $n) 

    		{ 

        		if (!empty($res)) 

        		{ 

            		$res .= " and "; 

        		} 

		        if ($Dn < 2) 

       			{ 

            		$res .= $ones[$Dn * 10 + $n]; 

        		} 

        		else 

        		{ 

            		$res .= $tens[$Dn]; 

	            	if ($n) 

            		{ 

                		$res .= "-" . $ones[$n]; 

            		} 

        		} 

    		} 

		    if (empty($res)) 

    		{ 

        		$res = "zero"; 

    		} 

		    return $res; 

		} 
	function convert_number($numval){
			   error_reporting(0);
				$moneystr = "";
				//printr($numval);
				// handle the millions
				//$numval = '4200.1';
				$milval = (integer)($numval / 1000000);
				//printr($milval);
				if($milval > 0)  {

				  $moneystr = $this->getwords($milval) . " Million";

				  }

				 

				// handle the thousands

				$workval = $numval - ($milval * 1000000); // get rid of millions

				$thouval = (integer)($workval / 1000);
	//printr($thouval);
				if($thouval > 0)  {

				  $workword = $this->getwords($thouval);

				  if ($moneystr == "")    {

					$moneystr = $workword . " Thousand";

					}else{

					$moneystr .= " " . $workword . " Thousand";

					}

				  }

				

				// handle all the rest of the dollars

				$workval = $workval - ($thouval * 1000); // get rid of thousands

				$tensval = (integer)($workval);

				if ($moneystr == ""){

				  if ($tensval > 0){

					$moneystr = $this->getwords($tensval);

					}else{

					$moneystr = "Zero";

					}

				  }else // non zero values in hundreds and up

				  {

				  $workword = $this->getwords($tensval);

				  $moneystr .= " " . $workword;

				  }

				 

				// plural or singular 'dollar'

				$workval = (integer)($numval);

				if ($workval == 1){

				  $moneystr .= "  & ";

				  }else{

				  $moneystr .= " & ";

				  }

				 

				// do the cents - use printf so that we get the

				// same rounding as printf

			$workstr = sprintf("%3.2f",$numval); // convert to a string

			//$intstr = substr($workstr,strlen() - 2, 2);

			$intstr = substr($workstr,- 2, 2);

			$workint = (integer)($intstr);

			if ($workint == 0){

			  $moneystr .= "Zero";

			  }else{

			  $moneystr .= $this->getwords($workint);

			  }

			if ($workint == 1){

			  $moneystr .= " Cent";

			  }else{

			  $moneystr .= " Cents";

			  }

			 

			// done 
				
			return $moneystr;

	}

		function getwords($workval)

		{
			//printr($workval);
			$numwords = array(

			  1 => "One",

			  2 => "Two",

			  3 => "Three",

			  4 => "Four",

			  5 => "Five",

			  6 => "Six",

			  7 => "Seven",

			  8 => "Eight",

			  9 => "Nine",

			  10 => "Ten",

			  11 => "Eleven",

			  12 => "Twelve",

			  13 => "Thirteen",

			  14 => "Fourteen",

			  15 => "Fifteen",

			  16 => "Sixteen",

			  17 => "Seventeen",

			  18 => "Eighteen",

			  19 => "Nineteen",

			  20 => "Twenty",

			  30 => "Thirty",

			  40 => "Forty",

			  50 => "Fifty",

			  60 => "Sixty",

			  70 => "Seventy",

			  80 => "Eighty",

			  90 => "Ninety");

			 

			// handle the 100's

			$retstr = "";

			$hundval = (integer)($workval / 100);

			if ($hundval > 0){

			  $retstr = $numwords[$hundval] . " Hundred";

			  }

			 

			// handle units and teens

			$workstr = "";

			$tensval = $workval - ($hundval * 100); // dump the 100's

			 

			// do the teens

			//printr($tensval);
			if (($tensval < 20) && ($tensval > 0)){

			  $workstr = $numwords[$tensval];
			   // got to break out the units and tens

			  }else{

			  $tempval = ((integer)($tensval / 10)) * 10; // dump the units

			  $workstr = $numwords[$tempval]; // get the tens
			 // echo '$workstr';

			  $unitval = $tensval - $tempval; // get the unit value

			  if ($unitval > 0){

				$workstr .= " " . $numwords[$unitval];

				}

			  }

			 

			// join the parts together 

			if ($workstr != ""){

			  if ($retstr != "")

			  {

				$retstr .= " " . $workstr;

				}else{

				$retstr = $workstr;

				}

			  }
		//printr($retstr);
			return $retstr;

		}
	
	public function getSpout($product_spout_id) 
	{
		$sql = "select * from " . DB_PREFIX ."product_spout where product_spout_id = '".$product_spout_id."'";
		
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
	
	public function getInvoice($user_type_id,$user_id,$data=array(),$filter_data=array(),$inv_status=0,$admin_user_id='',$invoice_status=0)
	{
	     $i_status='';
	    if($invoice_status=='0')
	        $i_status=" AND inv.invoice_status='0'";
	   else if($invoice_status=='1')
	          $i_status=" AND (inv.invoice_status='1' OR inv.invoice_status='2' )";
	    else if($invoice_status=='3')
	        $i_status=" AND inv.invoice_status='1' AND (inv.added_user_id='39'  AND inv.added_user_type_id='4'  OR ( inv.added_user_id IN (175,176,177,178,179,180,191,206,210,214) AND inv.added_user_type_id = 2 )) ";
	   else
		     $i_status=" AND (inv.invoice_status='1' OR inv.invoice_status='2' )";
		    
		if(!empty($filter_data)){
		    $i_status=" AND (inv.invoice_status='1' OR inv.invoice_status='2' OR inv.invoice_status='0')";		
		}
		
		if($user_type_id == 1 && $user_id == 1){

		        //$sql = "SELECT inv.* FROM " . DB_PREFIX . "government_sales_invoice as inv,	government_sales_invoice_product as gp  WHERE  inv.sales_invoice_id=gp.sales_invoice_id AND inv.is_delete = 0 ".$i_status ;
		        $sql = "SELECT inv.* FROM " . DB_PREFIX . "government_sales_invoice as inv  WHERE inv.is_delete = 0 ".$i_status ;
		        if(!empty($filter_data['filter_product']))
		            $sql = "SELECT inv.* FROM `" . DB_PREFIX . "government_sales_invoice` as inv,government_sales_invoice_product as gi  WHERE inv.sales_invoice_id=gi.sales_invoice_id AND inv.is_delete = 0".$i_status;
   
		} 
		else if(($user_id=='52' || $user_id=='204' || $user_id=='145' || $user_id=='91') &&  $user_type_id=='2')
		{
		     $sql = "SELECT inv.* FROM " . DB_PREFIX . "government_sales_invoice as inv  WHERE inv.is_delete = 0 ".$i_status ;
		        if(!empty($filter_data['filter_product']))
		            $sql = "SELECT inv.* FROM `" . DB_PREFIX . "government_sales_invoice` as inv,government_sales_invoice_product as gi  WHERE inv.sales_invoice_id=gi.sales_invoice_id AND inv.is_delete = 0".$i_status;
		}
		
		else {
		
		if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			$str = '';
			
			
			
			if($userEmployee){
				$str = ' OR ( inv.added_user_id IN ('.$userEmployee.') AND inv.added_user_type_id = 2 )';
			}
			$status_cond='';
			if($inv_status!='2' && $inv_status!='3')
				$status_cond="(inv.added_user_id = '".(int)$set_user_id."' AND inv.added_user_type_id = '".(int)$set_user_type_id."' $str ) AND";

		
			  //  $sql = "SELECT inv.* FROM " . DB_PREFIX . "government_sales_invoice as inv ,government_sales_invoice_product as gp WHERE inv.sales_invoice_id=gp.sales_invoice_id AND $status_cond   inv.is_delete = 0 ".$i_status ;
			    $sql = "SELECT inv.* FROM " . DB_PREFIX . "government_sales_invoice as inv  WHERE  $status_cond   inv.is_delete = 0 ".$i_status ;
		        if(!empty($filter_data['filter_product']))
		            $sql = "SELECT inv.* FROM `" . DB_PREFIX . "government_sales_invoice` as inv,government_sales_invoice_product as gi  WHERE  $status_cond inv.sales_invoice_id=gi.sales_invoice_id AND inv.is_delete = 0".$i_status;
 		}
		
		if(!empty($filter_data)){
			if(!empty($filter_data['invoice_no'])){				
			$sql .= " AND inv.invoice_no = '".$filter_data['invoice_no']."' ";		
			}
			if(!empty($filter_data['country_id'])){
				$sql .= " AND inv.country_id = '".$filter_data['country_id']."' ";		
			}
			if(!empty($filter_data['customer_name'])){
				$sql .= " AND inv.customer_name LIKE '%".$filter_data['customer_name']."%' ";		
			}if(!empty($filter_data['filter_shipment'])){
				$sql .= " AND inv.transport LIKE '%".$filter_data['filter_shipment']."%' ";
				//printr($sql);		
			}
		    if(!empty($filter_data['filter_product'])){
				$sql .= " AND gi.product_id = '".$filter_data['filter_product']."'";
				//printr($sql);		
			}
		}
		$sql .=' GROUP BY inv.sales_invoice_id ';
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " .$data['sort'];	
		} else {
			$sql .= " ORDER BY inv.sales_invoice_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}	
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		if($limit!='')
		   $sql .= " LIMIT ".$limit;
	
	//echo $sql;
		 
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getAccessorie($product_accessorie_id) 
	{
		$sql = "select * from " . DB_PREFIX ."product_accessorie where product_accessorie_id = '".$product_accessorie_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
	
	public function getFixmaster()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "invoice_fixmaster` WHERE is_delete = '0' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalInvoice($user_type_id,$user_id,$filter_data=array(),$inv_status,$admin_user_id='',$invoice_status)
	{ 
	    $i_status='';
	    if($invoice_status=='0')
	        $i_status=" AND g.invoice_status='0'";
	    else if($invoice_status=='1')
	        $i_status=" AND (g.invoice_status='1' OR g.invoice_status='2' )";
		else if($invoice_status=='3')
	        $i_status=" AND g.invoice_status='1' AND (g.added_user_id='39'  AND g.added_user_type_id='4'  OR ( g.added_user_id IN (175,176,177,178,179,180,191,206,210,214) AND g.added_user_type_id = 2 )) ";
		else
		    $i_status=" AND (g.invoice_status='1' OR g.invoice_status='2' )";
		
		if(!empty($filter_data)){
	
		    $i_status=" AND (g.invoice_status='1' OR g.invoice_status='2' OR g.invoice_status='0')";		
		}
		
		if($user_type_id == 1 && $user_id == 1){
		//	$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "government_sales_invoice` as g,	government_sales_invoice_product as gp WHERE  g.sales_invoice_id=gp.sales_invoice_id AND g.is_delete = 0".$i_status;
			$sql = "SELECT *  FROM `" . DB_PREFIX . "government_sales_invoice` as g  WHERE  g.is_delete = 0".$i_status;
		    if(!empty($filter_data['filter_product']))
		        $sql = "SELECT *  FROM `" . DB_PREFIX . "government_sales_invoice` as g,government_sales_invoice_product as gi  WHERE   g.sales_invoice_id=gi.sales_invoice_id AND g.is_delete = 0".$i_status;
   
		    
		} 
		else if(($user_id=='52' || $user_id=='204' || $user_id=='145' || $user_id=='91') &&  $user_type_id=='2')
		{//echo 'hi';
		     $sql = "SELECT *  FROM `" . DB_PREFIX . "government_sales_invoice` as g  WHERE  g.is_delete = 0".$i_status;
		    if(!empty($filter_data['filter_product']))
		        $sql = "SELECT *  FROM `" . DB_PREFIX . "government_sales_invoice` as g,government_sales_invoice_product as gi  WHERE   g.sales_invoice_id=gi.sales_invoice_id AND g.is_delete = 0".$i_status;
		 //echo $sql; 
		}
		else 
		{
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( g.added_user_id IN ('.$userEmployee.') AND g.added_user_type_id = 2 )';
			}
			$status_cond='';
			if($inv_status!='2' && $inv_status!='3')
				$status_cond="( g.added_user_id = '".(int)$set_user_id."' AND g.added_user_type_id = '".(int)$set_user_type_id."' $str) AND";
			$sql = "SELECT *  FROM `" . DB_PREFIX . "government_sales_invoice` as g WHERE  $status_cond  g.is_delete = 0 ".$i_status ;
			if(!empty($filter_data['filter_product']))
		        $sql = "SELECT *  FROM `" . DB_PREFIX . "government_sales_invoice` as g,government_sales_invoice_product as gi  WHERE  $status_cond g.sales_invoice_id=gi.sales_invoice_id AND g.is_delete = 0".$i_status;
   
		}
		
		if(!empty($filter_data)){
			if(!empty($filter_data['invoice_no'])){
				
			$sql .= " AND g.invoice_no = '".$filter_data['invoice_no']."' ";		
			}
			if(!empty($filter_data['country_id'])){
				$sql .= " AND g.country_id = '".$filter_data['country_id']."' ";		
			}
			if(!empty($filter_data['customer_name'])){
				$sql .= " AND g.customer_name LIKE '%".$filter_data['customer_name']."%' ";
				//printr($sql);		
			}	
			if(!empty($filter_data['filter_shipment'])){
				$sql .= " AND g.transport LIKE '%".$filter_data['filter_shipment']."%' ";
				//printr($sql);		
			}
			if(!empty($filter_data['filter_product'])){
				$sql .= " AND gi.product_id = '".$filter_data['filter_product']."' GROUP BY g.sales_invoice_id ";
				//printr($sql);		
			}
			
		}
		
//		$sql .=' GROUP BY inv.sales_invoice_id ';
      /*if($_SESSION['ADMIN_LOGIN_SWISS']=='39' || $_SESSION['LOGIN_USER_TYPE']=='4'){
       printr($i_status);
        echo $sql;
      }*/
      //echo $sql;
      
   
		$data = $this->query($sql);
		 //  printr($data);
		return $data->num_rows;
	}
	
	public function updateInvoiceStatus($status,$data)
	{
	//printr($data);
		//printr($status);die;
		if($status == 0 || $status == 1 || $status == 2){
			$sql = "UPDATE " . DB_PREFIX . "government_sales_invoice SET status = '" .(int)$status. "',  date_modify = NOW() WHERE sales_invoice_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 3){
		      $by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');      	 
			$sql = "UPDATE " . DB_PREFIX . "government_sales_invoice SET is_delete = '1', delete_by='".$by."', date_modify = NOW() WHERE sales_invoice_id IN (" .implode(",",$data). ")";
		
			$this->query($sql);
		}
	//	echo $sql;die;
	}
	
	public function updateInvoice($invoice_no,$status_value)
	{
		$sql = "UPDATE " . DB_PREFIX . "government_sales_invoice SET status = '".$status_value."', date_modify = NOW()  WHERE sales_invoice_id = '" .(int)$invoice_no. "'";
	//	echo $sql;die;
		$this->query($sql);
	}
	
	public function getInvoiceProduct($invoice_id)
	{
		$sql = "SELECT ip.*,p.product_name FROM `" . DB_PREFIX . "invoice_product_test` as ip,product p WHERE invoice_id = '" .(int)$invoice_id. "' AND ip.is_delete=0 AND ip.product_id=p.product_id";
		$data = $this->query($sql);

		//printr($data);die;
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}		
	}
	
	public function getUserEmployeeIds($user_type_id,$user_id,$permission=0)
	{
		if($permission=='1')
            $per = " IN (6,37,38,39)";
        else
            $per = " = ".(int)$user_id;
    
		$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id ".$per;
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['ids'];
		}else{
			return false;
		}
	} 
	
	public function getInvoiceColorlable($invoice_color_id)
	{
		$sql ="SELECT * FROM invoice_color AS ic LEFT JOIN  invoice_product_test AS ip ON (ic.invoice_product_id=ip.invoice_product_id)  WHERE ic.invoice_color_id=".$invoice_color_id."";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}		
	}
	
	public function getCustomId($custom_order_number)
	{
		$custom_order=explode(",", $custom_order_number);
		$result = "'" . implode ( "', '", $custom_order ) . "'";
		//$data = $this->query("SELECT multi_custom_order_id FROM " . DB_PREFIX ."multi_custom_order_id WHERE multi_custom_order_number = '".$custom_order_number."'");
		$data = $this->query("SELECT multi_custom_order_id FROM " . DB_PREFIX ."multi_custom_order_id WHERE multi_custom_order_number IN (".$result.")");
		if($data->num_rows)
		{
			//return $data->row['multi_custom_order_id'];
			return $data->rows;
		}
		else
			return false;
	}
	
	public function getCustomOrder($cust_cond='',$getData = '*',$user_type_id='',$user_id='',$orders_user_id='',$upload_time='')
	{
		
		$date = date("d-m-Y");
		
		if($upload_time!='')
			//$date_cond = '%"currdate":"'.date("d-m-Y", strtotime($upload_time) ).'"%'; commet by sonu  24-2-2017
			$date_cond = '%"currdate":"'.date("d-m-Y", strtotime($date) ).'"%';
		else
			$date_cond = '%"currdate":"'.$date.'"%';
			
		if($user_type_id && $user_id){
			if($user_id == 1 && $user_type_id == 1){
				$sql = "SELECT $getData,cn.country_name,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode  FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."country cn ON (mco.shipment_country_id=cn.country_id) INNER JOIN multi_custom_order_id as mcoi ON(mco.multi_custom_order_id=mcoi.multi_custom_order_id) INNER JOIN `" . DB_PREFIX ."address` adr ON (mcoi.shipping_address_id=adr.address_id) WHERE (".$cust_cond.") AND mco.dispach_by LIKE '".$date_cond."' ";
			}else{
				if($user_type_id == 2){
					/*$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
					$set_user_id = $parentdata->row['user_id'];
					$set_user_type_id = $parentdata->row['user_type_id'];*/
					$userEmployee = $this->getUserEmployeeIds('4',$orders_user_id);
					//$set_user_id = $user_id;
					//$set_user_type_id = $user_type_id;
					$set_user_id = $orders_user_id;
					$set_user_type_id = '4';
				}else{
					$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
					$set_user_id = $user_id;
					$set_user_type_id = $user_type_id;
				}
				$str = '';
				if($userEmployee){
					$str = ' OR ( mco.added_by_user_id IN ('.$userEmployee.') AND mco.added_by_user_type_id = 2 ) ';
				}
				//$str .= ' ) ';
				$sql = "SELECT $getData,cn.country_name,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode  FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."country cn ON (mco.shipment_country_id=cn.country_id) INNER JOIN " . DB_PREFIX ."multi_custom_order_id mcoi ON (mco.multi_custom_order_id=mcoi.multi_custom_order_id) INNER JOIN `" . DB_PREFIX ."address` adr ON (mcoi.shipping_address_id=adr.address_id) WHERE  (".$cust_cond.") AND (mco.added_by_user_id = '".(int)$set_user_id."' AND mco.added_by_user_type_id = '".(int)$set_user_type_id."') $str AND mco.done_status ='0'  AND mco.dispach_by LIKE '".$date_cond."'";
				//echo $sql;
			}
		}else{
			$sql = "SELECT $getData,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode FROM " . DB_PREFIX ."multi_custom_order mco,multi_custom_order_id mcoi,address adr WHERE
			 mco.multi_custom_order_id=mcoi.multi_custom_order_id AND mco.done_status ='0' AND (".$cust_cond.") AND mcoi.shipping_address_id=adr.address_id AND mco.dispach_by LIKE '".$date_cond."'";
		}
	//	echo $sql;
	//	die;
		$data = $this->query($sql);
		//printr($data);
		
		return $data->rows;
	}
	



	public function numberFormate($number,$decimalPoint=3){
		return number_format($number,$decimalPoint,".","");
	}
	


	public function getMenuPermission($menu_id,$user_id,$user_type_id)
	{
		$cond ='add_permission LIKE "%'.$menu_id.'%" AND edit_permission LIKE "%'.$menu_id.'%" AND delete_permission LIKE "%'.$menu_id.'%" AND view_permission LIKE "%'.$menu_id.'%"';
		$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$cond." AND user_type_id = '".$user_type_id."' 
		AND user_id ='".$user_id."'";
		$data = $this->query($sql);
		return $data->rows;
	}

	public function userOrderData($user_id,$user_type_id,$order_type='')
	{
		$date = date("d-m-Y");
		//printr($date);
		//printr("===================================");
		$date_cond = '%"currdate":"'.$date.'"%';
		
		$order='';
		if($order_type!='')
			$order = ' AND t.order_type = "'.$order_type.'"';
			
		if($user_type_id==2)
		{
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'";
			$dataadmin = $this->query($sqladmin);
			$con =  'AND st.admin_user_id = "'.$dataadmin->row['user_id'].'"';
			$cust_con =  'AND mcoi.admin_user_id = "'.$dataadmin->row['user_id'].'"';
			$admin_user_id=$dataadmin->row['user_id'];
		}
		elseif($user_type_id==4)
		{
			$con =  "AND st.admin_user_id = '".$user_id."'";
			$cust_con =  "AND mcoi.admin_user_id = '".$user_id."'";
			$admin_user_id= $user_id;
		}
		
		$stock_sql = "SELECT st.gen_order_id as order_num FROM template_order_test t,stock_order_test st,stock_order_dispatch_history_test as sodh WHERE t.is_delete = 0 AND ( (t.template_order_id=sodh.template_order_id AND t.product_template_order_id = sodh.product_template_order_id AND sodh.status=0)) AND t.status=1 AND st.stock_order_id=t.stock_order_id AND st.client_id=t.client_id ".$con." ".$order." AND sodh.dispach_by LIKE '".$date_cond."'  GROUP BY st.stock_order_id, st.admin_user_id";
		$stock_data = $this->query($stock_sql);
	//echo $stock_sql;
		$cust_data=array();
		//$cust_data->num_rows=0;
		//if($order_type!='sample')
		//{
			$cust_sql = "SELECT mcoi.multi_custom_order_number as order_num FROM multi_custom_order mco,multi_custom_order_id as mcoi WHERE mco.multi_custom_order_id=mcoi.multi_custom_order_id ".$cust_con." AND mco.dispach_by LIKE '".$date_cond."' AND done_status=0 GROUP BY mcoi.multi_custom_order_id, mcoi.admin_user_id";
			//echo $cust_sql;
			$cust_data = $this->query($cust_sql);
		//}
		$custom_imp = '';
		$stock_imp='';
		$custom_ar='';
		//printr($cust_sql);
		if(!empty($cust_data) && $cust_data->num_rows>0)
		{
			foreach($cust_data->rows as $cust)
			{
				$custom_ar[] = $cust['order_num'];
			}
			$custom_imp = implode(",",$custom_ar);
		}

		if($stock_data->num_rows>0)
		{
				foreach($stock_data->rows as $stock)
				{
					$stock_ar[] = $stock['order_num'];
				}
				$stock_imp = implode(",",$stock_ar);
				$o_no = array('stock_order_no' => $stock_imp,
							  'custom_order_no' => $custom_imp,
							  'order_user_id' =>$admin_user_id);
				return $o_no;
		}
		else
		{
			if(!empty($cust_data))
			{
				$o_no = array('stock_order_no' => '',
							  'custom_order_no' => $custom_imp,
							  'order_user_id' =>$admin_user_id);
				return $o_no;
			}
			else
				return false;
		}
	
	}
	public function getLastIdInvoice() {
		$sql = "SELECT invoice_id FROM invoice_test ORDER BY invoice_id DESC LIMIT 1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
	public function getUserCountry($user_tyep_id,$user_id){
			$sql = "SELECT co.country_id, co.country_code, co.currency_id,co.currency_code FROM " . DB_PREFIX ."address ad LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) WHERE ad.user_id = '".(int)$user_id."' AND ad.user_type_id = '".(int)$user_tyep_id."' AND ad.address_type_id = '0'";
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}else{
				return false;
			}
	}
	

	
	public function getUserCurrencyByUser($curr_id)
	{
		$sql="SELECT cu.currency_id FROM country as co, currency as cu WHERE co.country_id='".$curr_id."' AND cu.currency_code=co.currency_code";
		$data = $this->query($sql);
		return $data->row['currency_id'];	
	}
	
	public function getProductdeatilsForSample($invoice_no)
	{
		$sql="SELECT ip.*,ic.*,p.product_name FROM invoice_product_test as ip,invoice_color_test as ic,product as p WHERE ip.invoice_id='".$invoice_no."' AND ip.invoice_product_id=ic.invoice_product_id AND p.product_id=ip.product_id";
		$data=$this->query($sql);
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	}
	public function change_qty_per_kg($inv_product_id,$value,$n,$invoice_status)
	{ 
		if($n==0)
			$cond = "no_of_packages='".$value."'";
		else if($n==1) 
			$cond = "identification_marks='".$value."'";
			
		$ex= explode("A",$inv_product_id);
		$im = implode(",",$ex);
		if($invoice_status!=1 && $invoice_status!=2){
		    //invoice 
		    $sql = "UPDATE `" . DB_PREFIX . "government_sales_invoice_product` SET $cond WHERE 	sales_invoice_product_id IN (".$im.")";
		}else{
		    //proforma //roll
		    $sql = "UPDATE `" . DB_PREFIX . "government_sales_invoice_product` SET $cond WHERE 	sales_invoice_id =".$inv_product_id;
		}
	//echo $sql;	die;
		$data = $this->query($sql);	
	}
	



	public function getIngenBox($invoice_id,$product_id=0,$charge=0,$sales_invoice_id=0)
	{
		
		if($product_id!='2')
			$pro_id= " AND ip.product_id = '".$product_id."'";
		else if($product_id=='2')
			$pro_id= " AND ip.product_id NOT IN (10,23,11,18,6,34,47,48,63,72,38,37)";
				
		//$sql_pro="SELECT SUM(ic.qty) as total_qty,sum(ic.rate) as total_rate,sum(ic.rate*ic.qty) as total,ic.qty_in_kgs,ic.rate_in_kgs,GROUP_CONCAT(DISTINCT ic.invoice_color_id) as group_id_color FROM `invoice_product_test`as ip,invoice_color_test as ic WHERE ip.invoice_id='".$invoice_id."' AND ic.invoice_id='".$invoice_id."' AND ip.`invoice_product_id`=ic.`invoice_product_id` $pro_id " ;
		//$data_pro = $this->query($sql_pro);
	    
	    $sql_pro="SELECT SUM(ip.qty) as total_qty,sum(ip.rate) as total_rate,sum(ip.rate*ip.qty) as total,ip.qty_in_kgs,ip.rate_in_kgs,GROUP_CONCAT(DISTINCT ip.sales_invoice_product_id) as group_id_color FROM `government_sales_invoice_product`as ip WHERE  ip.sales_invoice_id='".$sales_invoice_id."' AND ip.invoice_id='".$invoice_id."' $pro_id " ;
		$data_pro = $this->query($sql_pro);
		
		$sql="SELECT COUNT(*) as total_box,SUM(ig.net_weight) as net, SUM(ig.net_weight+ig.box_weight) as gross FROM in_gen_invoice_test as ig,invoice_product_test as ip WHERE ig.invoice_id='".$invoice_id."' AND ig.invoice_product_id =ip.invoice_product_id AND ig.is_delete='0' $pro_id AND ip.invoice_id='".$invoice_id."'  AND ig.parent_id='0'";//
		$data = $this->query($sql);
		
		$sql1="SELECT COUNT(*) as total_box,SUM(ig.net_weight) as net, SUM(ig.net_weight+ig.box_weight) as gross FROM in_gen_invoice_test as ig,invoice_product_test as ip WHERE ig.invoice_id='".$invoice_id."' AND ig.invoice_product_id =ip.invoice_product_id AND ig.is_delete='0' $pro_id AND ip.invoice_id='".$invoice_id."'";//
		$data1 = $this->query($sql1);
	
//	printr($sql1);
		//printr($sql_pro); 
		//$sql_count="";
		
		if($data->num_rows)
		{
			//return $data->row;
			
			 $count=1;
		    if($product_id==6){
		    	$count=count(explode(',',$data_pro->row['group_id_color']));
		    	$data_pro->row['total_rate']=($data_pro->row['total_rate']/$count);
		    }
			$d = array( 'total_box'=>$data->row['total_box'],
						 'qty' => $data_pro->row['total_qty'],
						 'g_wt' => $data1->row['gross'],
						 'n_wt' => $data1->row['net'],
						 'total_amt' =>$data_pro->row['total_rate']*$data->row['net'],
						 'total' =>$data_pro->row['total'],
						 'qty_in_kgs'=>$data_pro->row['qty_in_kgs'],
						 'rate_in_kgs'=>$data_pro->row['rate_in_kgs'],
						 'group_id'=>$data_pro->row['group_id_color']
						 );
						 	 //   printr($d);
			return $d;

		}
		else
			return false;		
	}
	
	public function getProductCode($invoice_product_id)
	{
	//	printr($invoice_product_id);
		$sql="SELECT ip.*,ic.* FROM invoice_product_test as ip , invoice_color_test as ic WHERE ip.invoice_product_id='".$invoice_product_id."' AND ic. invoice_product_id ='".$invoice_product_id."'";
		
		$data=$this->query($sql);
	//	printr($data);
		
	//	$sql_detail ="SELECT product_code,description FROM product_code WHERE product ='".$data->row['product_id']."' AND valve ='".$data->row['valve']."'AND zipper ='".decode($data->row['zipper'])."'AND spout ='".decode($data->row['spout'])."'AND accessorie ='".decode($data->row['accessorie'])."'AND make_pouch ='".$data->row['make_pouch']."'  AND	volume ='".$data->row['size']."' AND measurement ='".$data->row['measurement']."' AND color = '".$data->row['color']."'" ; 
		//echo $sql_detail ;//die;
		$sql_detail = "SELECT product_code,description,product_code_id FROM product_code WHERE product_code_id = '".$data->row['product_code_id']."'";
		$data2=$this->query($sql_detail);		
		//	printr($data2);
			if($data2->num_rows)
			{
				return $data2->row;
			}
			else
			{
				return false;
			}
		
	}
	//sonu add 23-2-2017
	public function addremark($data)
	{
		
		//printr($data);die;
		$sql = "UPDATE `".DB_PREFIX."invoice_test` SET remarks='".$data['remarks']."' , add_remark_status='1'WHERE invoice_id='".$data['invoice_id']."'";			
		$data=$this->query($sql);		
			
			
	}
	
	
	public function getInternational_branch_detail($user_id,$user_type_id)
	{
		//printr($user_id.'==='.$user_type_id);
		if($user_id == 1 && $user_type_id==1)
		{
			//$sql = "SELECT * FROM international_branch WHERE is_delete = 0";
			return false;	
		}
		else
		
		{
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
		
			
		
		
				$sql = "SELECT * FROM international_branch WHERE is_delete = 0 AND international_branch_id = '".$set_user_id."'  ";
				//echo $sql;
				//printr($data);
				$data=$this->query($sql);
			//	printr($data);
					if($data->num_rows){
						return $data->row;
					}else{
						return false;
					}
			}
	}
	

	

	
		
	

	


	public function getProductCdDetails($product_code,$n=0)

	{

		$result=$this->query("SELECT pc.product_code,pc.valve,pc.spout,pc.product_code_id, pc.description, clr.color,clr.pouch_color_id,pc.accessorie,pc.make_pouch, pc.volume, pc.measurement,p.product_name,pc.product,pc.zipper FROM " . DB_PREFIX ."product_code as pc, pouch_color as clr, product as p WHERE pc.product_code LIKE '%".$product_code."%' AND pc.is_delete=0 AND pc.color=clr.pouch_color_id AND p.product_id = pc.product");

	//	printr($result);
        if($n==1)
    	   return $result->row;
        else
           return $result->rows; 
	}
	public function product_code_details($product_code_id)

	{

		$result=$this->query("SELECT pc.product_code,pc.valve,pc.spout,pc.product_code_id, pc.description, clr.color,clr.pouch_color_id,pc.accessorie,pc.make_pouch, pc.volume, pc.measurement,p.product_name,pc.product,pc.zipper FROM " . DB_PREFIX ."product_code as pc, pouch_color as clr, product as p WHERE pc.product_code_id = '".$product_code_id."' AND pc.is_delete=0 AND pc.color=clr.pouch_color_id AND p.product_id = pc.product");

	//	printr($result);

	return $result->row;
 
	}
	public function getInvoiceProductColorId($invoice_product_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "invoice_color_test` WHERE invoice_product_id = '" .(int)$invoice_product_id. "'  AND is_delete=0 ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}		
	}
	
	public function GetdigitalColorName($pouch_color_id){
	    
	  $arr=explode("==",$pouch_color_id);
	    	$sql = "SELECT  color FROM  pouch_color WHERE is_delete = '0' AND pouch_color_id='".$arr[0]."'";
//		echo $sql;die;
		$data = $this->query($sql);
		return $data->row['color'];
	}
	public function getsizeForUS($volume){
	    
	  
	   	$sql = "SELECT  volume_us FROM  pouch_volume WHERE is_delete = '0'AND status='1' AND volume='".$volume."'";
//		echo $sql;die;
		$data = $this->query($sql);
		return $data->row['volume_us'];
	}
	
	public function getInvoiceData($invoice_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "invoice_test  WHERE invoice_id = '" .(int)$invoice_id. "' AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getProforma($proforma_id) {

			$sql = "select * from ".DB_PREFIX."proforma_product_code_wise where proforma_id = '".$proforma_id."'";

			$data = $this->query($sql); 

			if($data->num_rows) {

				return $data->row;

			} else {

				return false;

			}

		} 
	public function getProformaInvoice($proforma_id) {

			$sql = "SELECT * ,pc.product_code,p.description as prodes FROM  proforma_invoice_product_code_wise as p ,product_code as pc where proforma_id = '".$proforma_id."' AND  pc.product_code_id = p.product_code_id AND p.is_delete = '0' ORDER BY p.proforma_invoice_id ASC";
				//echo $sql;
			$data = $this->query($sql);

			if($data->num_rows){

				return $data->rows;

			}

			else {

				return false;

			}

		}

	public function getSalesInvoiceData($sales_invoice_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "government_sales_invoice  WHERE sales_invoice_id = '" .(int)$sales_invoice_id. "' AND is_delete=0";
		$data = $this->query($sql);
	//	printr($data);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
		public function getSalesInvoiceProduct($sales_invoice_id,$n=0)
	{
		
		//ip.color=pouch.pouch_color_id pc.product_code_id=ip.product_code_id
			
	$sql = "SELECT ip.*,p.product_name,p.product_id FROM `" . DB_PREFIX . "government_sales_invoice_product` as ip,product p WHERE ip.product_id=p.product_id AND sales_invoice_id = '" .(int)$sales_invoice_id. "' AND ip.is_delete=0   ";
	    if($n==1)
	    	$sql = "SELECT ip.*,p.product_name,p.product_id FROM `" . DB_PREFIX . "government_sales_invoice_product` as ip,product p WHERE ip.product_id=p.product_id AND sales_invoice_id = '" .(int)$sales_invoice_id. "' AND ip.is_delete=0  AND ip.product_id NOT IN (10,23,11,18,6,34,47,48,63,72)  ";
        else if($n==2)
	    	$sql = "SELECT SUM(ip.qty*ip.rate) as basic_amt FROM `" . DB_PREFIX . "government_sales_invoice_product` as ip,product p WHERE ip.product_id=p.product_id AND sales_invoice_id = '" .(int)$sales_invoice_id. "' AND ip.is_delete=0  AND ip.product_id  IN (10,23,11,18,6,34,47,48,63,72)  ";
        else if($n==3)
	    	$sql = "SELECT SUM(ip.qty*ip.rate) as basic_amt FROM `" . DB_PREFIX . "government_sales_invoice_product` as ip,product p WHERE ip.product_id=p.product_id AND sales_invoice_id = '" .(int)$sales_invoice_id. "' AND ip.is_delete=0  ";
	//	echo $sql;
		$data = $this->query($sql);
	//	printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false; 
		}		
	} 

	public function add_sales_invoice($invoice_id,$n=0){
		     //printr($invoice_id);
		   // printr($n);
		
		    //0=invoice & 1 =proforma  
		 
		  
		 if($n!='1'){  
		     
	                $invoice=$this->getInvoiceData($invoice_id);
	                $sql_update_invoice= "UPDATE " . DB_PREFIX . "invoice_test SET government_sales_status = '1' WHERE 	invoice_id='".$invoice_id."'";
		          	$data_invoce_update = $this->query($sql_update_invoice);
	               
	            	$sql = "INSERT INTO `" . DB_PREFIX . "government_sales_invoice` SET invoice_id='".$invoice_id."',invoice_no = '".$invoice['invoice_no']."',challan_no = '".$invoice['invoice_no']."',exp_inv_no =  '".$invoice['invoice_no']."',invoice_date = NOW(),challan_date = NOW(),exp_date = NOW(),buyers_order_date = NOW(), buyers_orderno ='',consignee='".addslashes($invoice['consignee'])."',other_buyer='".$invoice['buyer']."',uk_ref_no='".$invoice['uk_ref_no']."',country_id='".$invoice['final_destination']."',customer_name = '".addslashes($invoice['customer_name'])."',hscode='".$invoice['HS_CODE']."',printedpouches='".$invoice['pouch_type']."',tran_desc='".$invoice['tran_desc']."',tool_cost='".$invoice['tool_cost']."',extra_tran_charges='".$invoice['extra_tran_charges']."',tran_charges='".$invoice['tran_charges']."',container_no='".addslashes($invoice['container_no'])."',show_pallet='".$invoice['show_pallet']."', seal_no='".$invoice['seal_no']."',cylinder_charges='".$invoice['cylinder_charges']."',igst='18',account_code = '".$invoice['account_code']."',invoice_status = '0',air_f_status = '".$invoice['air_f_status']."',transport='".decode($invoice['transportation'])."',currency='".$invoice['curr_id']."',	date_added = '".date('Y-m-d H:i:s')."',date_modify = '".date('Y-m-d H:i:s')."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',status=1,is_delete=0";
	            // echo $sql;die;
	                $data = $this->query($sql);
	                
	            	$sales_invoice_id = $this->getLastId();
        	   	        $invoice_product=$this->getProductdeatils($invoice_id);
        	   	        if(!empty($invoice_product)){
        	   	            foreach($invoice_product as $inv_product){
        	   	                
        	   	                	if($inv_product['product_id']=='11')
                            			{
                            				
                            				$net_pouches_scoop = $this->getIngenBox($invoice_id,$inv_product['product_id'],0,$sales_invoice_id);
                            			     $no_of_package=$net_pouches_scoop['total_box'];
                            			    $identification_marks=$net_pouches_scoop['n_wt'];
                            			}
                            			else if($inv_product['product_id']=='6')
                            			{
                            			    
                            				$net_pouches_roll = $this->getIngenBox($invoice_id,$inv_product['product_id'],0,$sales_invoice_id);
                            			    $no_of_package=$net_pouches_roll['total_box'];
                            			    $identification_marks=$net_pouches_roll['n_wt'];
                            			}
                            			else if($inv_product['product_id']=='10')
                            			{
                            			    $net_pouches_m = $this->getIngenBox($invoice_id,$inv_product['product_id'],0,$sales_invoice_id);
                            			    $no_of_package=$net_pouches_m['total_box'];
                            			    $identification_marks=$net_pouches_m['n_wt'];
                            			
                            			}
                            			else if($details['product_id']=='23')
                            			{
                            			
                            				$net_pouches_s = $this->getIngenBox($invoice_id,$inv_product['product_id'],0,$sales_invoice_id);
                            				 $no_of_package=$net_pouches_s['total_box'];
                            			    $identification_marks=$net_pouches_s['n_wt'];
                            			}
                            			else if($inv_product['product_id']=='18')
                            			{
                            				
                            				$net_pouches_str = $this->getIngenBox($invoice_id,$inv_product['product_id'],0,$sales_invoice_id);
                            		        $no_of_package=$net_pouches_str['total_box'];
                            			    $identification_marks=$net_pouches_str['n_wt'];
                            			}
                            			else if($inv_product['product_id']=='34')
                            			{
                            			
                            				$net_pouches_p = $this->getIngenBox($invoice_id,$inv_product['product_id'],0,$sales_invoice_id);
                            				$no_of_package=$net_pouches_p['total_box'];
                            			    $identification_marks=$net_pouches_p['n_wt'];
                            			
                            			}
                            			else if($inv_product['product_id']=='47')
                            			{
                            			
                            				$net_pouches_p = $this->getIngenBox($invoice_id,$inv_product['product_id'],0,$sales_invoice_id);
                            				$no_of_package=$net_pouches_p['total_box'];
                            			    $identification_marks=$net_pouches_p['n_wt'];
                            			}
                            			else if($inv_product['product_id']=='48')
                            			{
                            			
                            				$net_pouches_p = $this->getIngenBox($invoice_id,$inv_product['product_id'],0,$sales_invoice_id);
                            				$no_of_package=$net_pouches_p['total_box'];
                            			    $identification_marks=$net_pouches_p['n_wt'];
                            			}
                            			else if($inv_product['product_id']=='72')
                            			{
                            			
                            				$net_pouches_p = $this->getIngenBox($invoice_id,$inv_product['product_id'],0,$sales_invoice_id);
                            				$no_of_package=$net_pouches_p['total_box'];
                            			    $identification_marks=$net_pouches_p['n_wt'];
                            			}
                            			else if($inv_product['product_id']=='37')
                            			{
                            			
                            				$net_pouches_p = $this->getIngenBox($invoice_id,$inv_product['product_id'],0,$sales_invoice_id);
                            				$no_of_package=$net_pouches_p['total_box'];
                            			    $identification_marks=$net_pouches_p['n_wt'];
                            			}
                            			else if($inv_product['product_id']=='38')
                            			{
                            			
                            				$net_pouches_p = $this->getIngenBox($invoice_id,$inv_product['product_id'],0,$sales_invoice_id);
                            				$no_of_package=$net_pouches_p['total_box'];
                            			    $identification_marks=$net_pouches_p['n_wt'];
                            			}
                            			else if($inv_product['product_id']=='63')
                            			{
                            			
                            				$net_pouches_p = $this->getIngenBox($invoice_id,$inv_product['product_id'],0,$sales_invoice_id);
                            				$no_of_package=$net_pouches_p['total_box'];
                            			    $identification_marks=$net_pouches_p['n_wt'];
                            			
                            			}
                            			else if($inv_product['product_id']!='11' && $inv_product['product_id']!='6' && $inv_product['product_id']!='10' && $inv_product['product_id']!='23' && $inv_product['product_id']!='18' && $inv_product['product_id']!='34'&& $inv_product['product_id']!='47'&& $inv_product['product_id']!='48'&& $inv_product['product_id']!='63'&& $inv_product['product_id']!='72')
                            			{
                            				$net_pouches_pouch = $this->getIngenBox($invoice_id,2,0,$sales_invoice_id);
                            		    	$no_of_package=$net_pouches_pouch['total_box'];
                            			    $identification_marks=$net_pouches_pouch['n_wt'];
                            			}
                                    	   	                
        	   	             //  printr($no_of_package);
        	   	                //printr($identification_marks);
        	   	                
        	   	                 
        	   	                $invoice_color=$this->getInvoiceProductColorId($inv_product['invoice_product_id']);
        	   	                
        	   	            
        	   	             
        	   	             	$sql_product = "INSERT INTO `" . DB_PREFIX . "government_sales_invoice_product` SET sales_invoice_id='".$sales_invoice_id."', invoice_id='".$invoice_id."',invoice_product_id='".$inv_product['invoice_product_id']."',product_id = '".$inv_product['product_id']."',product_code_id = '".$inv_product['product_code_id']."',valve = '".$inv_product['valve']."',zipper = '" .$inv_product['zipper']. "',spout = '" .$inv_product['spout']. "',accessorie = '" .$inv_product['accessorie']. "', order_id ='".$inv_product['order_id']."',order_size_id='".$inv_product['order_size_id']."',ref_no='".$inv_product['ref_no']."',buyers_o_no='".$inv_product['buyers_o_no']."',filling_details = '".$inv_product['filling_details']."',item_no='".$inv_product['item_no']."',make_pouch='".$inv_product['make_pouch']."',digital_print_color='".$inv_product['digital_print_color']."',gross_weight='".$inv_product['gross_weight']."',measurement_two='".$inv_product['measurement_two']."',color = '".$invoice_color['color']."',color_text = '".addslashes($invoice_color['color_text'])."',qty = '".$invoice_color['qty']."',qty_in_kgs = '".$invoice_color['qty_in_kgs']."',rate = '".$invoice_color['rate']."',dis_rate = '".$invoice_color['dis_rate']."',rate_with_proportion = '".$invoice_color['rate_with_proportion']."',total_import_charges = '".$invoice_color['total_import_charges']."',size = '".$invoice_color['size']."',net_weight = '".$invoice_color['net_weight']."',identification_marks='".$identification_marks."',no_of_packages='".$no_of_package."',measurement = '".$invoice_color['measurement']."',dimension = '".$invoice_color['dimension']."',date_added = '".date('Y-m-d H:i:s')."',date_modify = '".date('Y-m-d H:i:s')."',is_delete=0";
        	   	            
        	   	              	$data = $this->query($sql_product);
        	   	              	
        	   	              //	die;
        	   	            }
        	   	            
        	   	        }
	   	       
		 }else if($n=='1'){
		      
		      // printr($invoice_id);
		     //printr($n);
		        
		       
		            
		        
		            $sql_update= "UPDATE " . DB_PREFIX . "proforma_product_code_wise SET government_sales_status = '1' WHERE 	proforma_id='".$invoice_id."'";
		          	$data_proforma_update = $this->query($sql_update);
		          	
		          	
		           $proforma_detail = $this->getProforma($invoice_id);
		           $last_inv_no = $this->getLastIdSalesInvoice($proforma_detail['added_by_user_type_id'],$proforma_detail['added_by_user_id']);
		           
		           
		           if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1' ){
		             //   printr($last_inv_no);
		             }
		       
		           
		           $admin_user_id=$this->getUser($proforma_detail['added_by_user_id'],$proforma_detail['added_by_user_type_id']);
		           $proforma_detail['pro_in_no']=$last_inv_no+1;
		         //  	$sql1 = "SELECT taxation_id,cgst,sgst,igst FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' AND tax_name='".$proforma_detail['taxation']."' ORDER BY taxation_id DESC LIMIT 1";
	            //	$data_tax = $this->query($sql1);
    	          //  $tax_data=$data_tax->row;
		         //  printr($proforma_detail);die;
		           $str='';
		           if($admin_user_id['user_id']=='39')
		                $str=', dis_status=1';
		                
		        	$sql_proforma = "INSERT INTO `" . DB_PREFIX . "government_sales_invoice` SET invoice_id='".$invoice_id."',invoice_no = '".$proforma_detail['pro_in_no']."',challan_no = '".$proforma_detail['pro_in_no']."',exp_inv_no = '".$proforma_detail['pro_in_no']."',invoice_date = NOW(),challan_date = NOW(),exp_date = NOW(),buyers_order_date = NOW(), consignee='".addslashes($proforma_detail['address_info'])."',other_buyer='".addslashes($proforma_detail['del_address_info'])."',same_as_above='".$proforma_detail['same_as_above']."',country_id='".$proforma_detail['destination']."',customer_name = '".addslashes($proforma_detail['customer_name'])."',tran_charges='".$proforma_detail['freight_charges']."',invoice_status = '1',transport='".decode($proforma_detail['transportation'])."',gst_no='".$proforma_detail['vat_no']."',cgst='".$proforma_detail['cgst']."',sgst='".$proforma_detail['sgst']."',igst='".$proforma_detail['igst']."',taxation='".$proforma_detail['taxation']."',taxation_per='".$proforma_detail['taxation_per']."',currency='".$proforma_detail['currency_id']."',state_id='".$proforma_detail['state_india']."',discount='".$proforma_detail['discount']."',added_user_id='".$proforma_detail['added_by_user_id']."',added_user_type_id='".$proforma_detail['added_by_user_type_id']."',date_added ='".date('Y-m-d H:i:s')."',date_modify = '".date('Y-m-d H:i:s')."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',status=1,is_delete=0 $str";
                 	//echo $sql_proforma;die;
                 	$data_proforma = $this->query($sql_proforma);
              //
                    $sales_invoice_id = $this->getLastId();
                   
		            $proforma_product_detail = $this->getProformaInvoice($invoice_id);
		      
		          
		          if(!empty($proforma_product_detail)){
	   	            foreach($proforma_product_detail as $inv_product){
	   	                     $product_gst_details=$this->getProductGST($inv_product['product']);
	   	             
                    		 if($proforma_detail['taxation']=='Out Of Gujarat'){
                    				       $igst_tax=((($inv_product['quantity']*$inv_product['rate'])*$product_gst_details['igst_percentage'])/100);
                				           $t_igst_tax=$t_igst_tax+$igst_tax;
                				           $igst_per=$product_gst_details['igst_percentage'];
                				   }else if($proforma_detail['taxation']=='With in Gujarat'){
                				          $cgst_tax=((($inv_product['quantity']*$inv_product['rate'])*$product_gst_details['cgst_percentage'])/100);
                				         $sgst_tax=((($inv_product['quantity']*$inv_product['rate'])*$product_gst_details['sgst_percentage'])/100);
                				                  $cgst_per=$product_gst_details['cgst_percentage'];
                				                  $sgst_per=$product_gst_details['sgst_percentage'];
                				              
                				             $t_cgst_tax=$t_cgst_tax+$cgst_tax;
                				            $t_sgst_tax=$t_sgst_tax+$sgst_tax;
                				         
                				   }
	   	                    $dimension=$inv_product['width'].'x'.$inv_product['height'].'x'.$inv_product['gusset'];
	   	                	$sql_product = "INSERT INTO `" . DB_PREFIX . "government_sales_invoice_product` SET sales_invoice_id='".$sales_invoice_id."', invoice_id='".$invoice_id."',invoice_product_id='".$inv_product['proforma_invoice_id']."',product_id = '".$inv_product['product']."',product_code_id = '".$inv_product['product_code_id']."',valve = '".$inv_product['valve']."',zipper = '" .encode($inv_product['zipper']). "',spout = '" .encode($inv_product['spout']). "',accessorie = '" .encode($inv_product['accessorie']). "', filling_details = '".$inv_product['filling']."',make_pouch='".$inv_product['make_pouch']."',color = '".$inv_product['color']."',color_text = '".addslashes($inv_product['color_text'])."',qty = '".$inv_product['quantity']."',rate = '".$inv_product['rate']."',tool_price = '".$inv_product['tool_price']."',express_rate = '".$inv_product['express_rate']."',product_name = '".$inv_product['product_name']."',gusset_printing_option = '".$inv_product['gusset_printing_option']."',printing_option = '".$inv_product['printing_option']."',size = '".$inv_product['size']."',net_weight = '".$inv_product['netweight']."',description = '".$inv_product['description']."',measurement = '".$inv_product['measurement']."',dimension = '".$dimension."',amt_igst = '".$igst_tax."',amt_cgst = '".$cgst_tax."',amt_sgst = '".$sgst_tax."',igst = '".$igst_per."',cgst = '".$cgst_per."',sgst = '".$sgst_per."',taxation = '".$proforma_detail['taxation']."',date_added = '".date('Y-m-d H:i:s')."',date_modify = '".date('Y-m-d H:i:s')."',is_delete=0";
	   	            
	   	              	$data = $this->query($sql_product);
	   	                
	   	            } 
		              
		          }
		 
	//	die;
		    
		 }
	//	$total_amt=$this->viewInvoice('',$sales_invoice_id,'','','0');
		 
	
	return $sales_invoice_id;
	}
    
    
     
    public function addRollsalesinvoice($data){
	            
	              //  printr($data);//die;
	                
	            //	$sql1 = "SELECT taxation_id,cgst,sgst,igst FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' AND tax_name='".$data['taxation']."' ORDER BY taxation_id DESC LIMIT 1";
	            
	            $user_id =$admin_user_id= $_SESSION['ADMIN_LOGIN_SWISS'];
    			$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
        	
        		if($user_type_id == 2){
                    	$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");        		
                    	$admin_user_id = $parentdata->row['user_id'];          		
                    		
                  	}else{            			
                    	$admin_user_id = $user_id;          			
                  	}
          
	            
	            	$sql1 = "SELECT taxation_id,cgst,sgst,igst FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' AND tax_name='".$data['taxation']."' AND find_in_set(".$admin_user_id.",admin_user_id) <> 0 ORDER BY taxation_id DESC LIMIT 1";
	            
	                
	            	$data_tax = $this->query($sql1);
    	            $tax_data=$data_tax->row;
	           
	    //    printr($sql1);  
	         // printr($data_tax);  
	       //   die;
		         $sql = "INSERT INTO  `" . DB_PREFIX . "government_sales_invoice` SET invoice_no = '".$data['invoiceno']."',challan_no = '".$data['challan_no']."',exp_inv_no = '".$data['exp_inv_no']."',invoice_date = '" .$data['invoicedate']. "',challan_date = '" .$data['challan_date']. "',exp_date = '" .$data['exp_date']. "', buyers_orderno ='".$data['buyers_orderno']."',buyers_order_date ='".$data['buyers_order_date']."',consignee='".addslashes($data['consignee'])."',other_buyer='".$data['other_buyer']."',country_id='".$data['country_id']."',customer_name = '".addslashes($data['customer_name'])."',hscode='".$data['hscode']."',printedpouches='".$data['printedpouches']."',tran_desc='".$data['tran_desc']."',tran_charges='".$data['tran_charges']."',transport='road',cylinder_charges='".$data['cylinder_charges']."',invoice_status = '".$data['invoice_status']."',currency='".$data['currency']."',currency_rate ='".$data['currency_rate']."',lr_no ='".$data['lr_no']."',vehicle_no ='".$data['vehicle_no']."',container_no='".$data['container_no']."',seal_no='".$data['seal_no']."',gst_no='".$data['gst_no']."',pallet_detail='".$data['pallet_detail']."',remark='".$data['remark']."',rfid_no='".$data['rfid_no']."',despatch ='".$data['despatch']."',taxation='".$data['taxation']."',cgst='".$tax_data['cgst']."',sgst='".$tax_data['sgst']."',igst='".$tax_data['igst']."',product_option='".$data['pro_option']."',date_added ='".date('Y-m-d H:i:s')."',date_modify ='".date('Y-m-d H:i:s')."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',status=1,is_delete=0 ";
                
             
                	$data_detail = $this->query($sql);
                      // echo $sql_proforma;//die;
                    $sales_invoice_id = $this->getLastId();
                    
                    
                    	foreach($data['addroll'] as $Roll)
						{
						    
						  //   printr($Roll); 
					
						/*     if($data['pro_option']==1)
	                             $Roll['product']=$Roll['product'];
	                         else
	                            $Roll['product']=$Roll['product_id'];*/
	                            
	                            
						     if(isset($Roll['keyword']) && $Roll['keyword']!='')
						        $Roll['keyword'] = $Roll['keyword'];
						     else
						        $Roll['keyword'] = '0';
						        
						 //  printr($Roll);    die;
						        
    		              $sql_product = "INSERT INTO `" . DB_PREFIX . "government_sales_invoice_product` SET sales_invoice_id='".$sales_invoice_id."',product_id = '".$Roll['product']."',product_code_id = '".$Roll['keyword']."',valve = 'No Valve',zipper = 'Mg==',spout ='MQ==',accessorie = 'NA==' ,make_pouch='MQ==',color = '-1',color_text = '".addslashes($Roll['description'])."',qty = '".$Roll['qty']."',rate = '".$Roll['rate']."',size = '".$Roll['size']."',net_weight = '".$Roll['net_weight']."',description = '".$Roll['description']."',measurement = '".$Roll['measurement']."',date_added = '".date('Y-m-d H:i:s')."',date_modify = '".date('Y-m-d H:i:s')."',is_delete=0";
    	   	           //echo $sql_product;die;
    	   	              	$data_product = $this->query($sql_product);
						}
	   	           
	//	$total_amt=$this->viewInvoice('',$sales_invoice_id,'','','0'); 
	    //die;
	 
	    return $sales_invoice_id;
	    
	}
    
    
   
    public function updatesalesinvoice($data,$sales_invoice_id)
    {
        
      $by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');      	 
	
	
	     $invoice = $this->getSalesInvoiceData($sales_invoice_id);
	     
	//     printr($invoice);
	     
	     if($invoice['invoice_status']==2){
	        $user_id=$invoice['user_id'];
	        $user_type_id =$invoice['user_type_id'];
	     }else{
      		$user_id =$admin_user_id= $invoice['added_user_id'];
			$user_type_id =$invoice['added_user_type_id'];
	     }
		if($user_type_id == 2){
            	$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");        		
            	$admin_user_id = $parentdata->row['user_id'];        		
            		
          	}else{            			
            	$admin_user_id = $user_id;          			
          	} 
          

	   	$sql1 = "SELECT taxation_id,cgst,sgst,igst FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' AND tax_name='".$data['taxation']."' AND find_in_set(".$admin_user_id.",admin_user_id) <> 0 ORDER BY taxation_id DESC LIMIT 1";
	
	
	
	//	$sql1 = "SELECT taxation_id,cgst,sgst,igst FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' AND tax_name='".$data['taxation']."' ORDER BY taxation_id DESC LIMIT 1";
	
	
	
	
	
	
		$data_tax = $this->query($sql1);
    	$tax_data=$data_tax->row;
    	
    //	printr($sql1);
    //	printr($sql1);
    //	printr($tax_data);
   // die;
    	if($data['transport']=='sea')
        	$tax_data['igst']='18';
    	else
    	  $tax_data['igst']=$tax_data['igst']; 
    	 if(isset($data['igst_status']))
 			$data['igst_status']=$data['igst_status'];
 		else
 			$data['igst_status']='';
 			
 		if(isset($data['same_as_above']))
 			$data['same_as_above']=$data['same_as_above'];
 		else
 			$data['same_as_above']='0';
 
    
    $sql = "UPDATE `" . DB_PREFIX . "government_sales_invoice` SET invoice_no = '".$data['invoiceno']."',challan_no = '".$data['challan_no']."',exp_inv_no = '".$data['exp_inv_no']."',invoice_date = '" .$data['invoicedate']. "',challan_date = '" .$data['challan_date']. "',exp_date = '" .$data['exp_date']. "', buyers_orderno ='".$data['buyers_orderno']."',buyers_order_date ='".$data['buyers_order_date']."',consignee='".addslashes($data['consignee'])."',other_buyer='".addslashes($data['other_buyer'])."',country_id='".$data['country_id']."',state_id='".$data['state_id']."',customer_name = '".addslashes($data['customer_name'])."',hscode='".$data['hscode']."',printedpouches='".$data['printedpouches']."',tran_desc='".$data['tran_desc']."',tran_charges='".$data['tran_charges']."',cylinder_charges='".$data['cylinder_charges']."',same_as_above='".$data['same_as_above']."',invoice_status = '".$data['invoice_status']."',currency='".$data['currency']."',currency_rate ='".$data['currency_rate']."',lr_no ='".$data['lr_no']."',vehicle_no ='".$data['vehicle_no']."',container_no='".addslashes($data['container_no'])."',seal_no='".$data['seal_no']."',gst_no='".$data['gst_no']."',pallet_detail='".$data['pallet_detail']."',remark='".$data['remark']."',rfid_no='".$data['rfid_no']."',despatch ='".$data['despatch']."',taxation='".$data['taxation']."',cgst='".$tax_data['cgst']."',sgst='".$tax_data['sgst']."',igst='".$tax_data['igst']."',product_option='".$data['pro_option']."',date_added = '".date('Y-m-d H:i:s')."',date_modify = '".date('Y-m-d H:i:s')."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',is_delete=0,igst_status='".$data['igst_status']."',edit_by='".$by."' WHERE sales_invoice_id='".$sales_invoice_id."'";
	 // printr($sql);die;
		$data = $this->query($sql);	
	
		return $sales_invoice_id;
    } 
    public function updatesalesinvoiceRoll($data,$sales_invoice_id){
    
    
      $invoice = $this->getSalesInvoiceData($sales_invoice_id);
      		$user_id =$admin_user_id= $invoice['added_user_id'];
			$user_type_id =$invoice['added_user_type_id'];
    		if($user_type_id == 2){
            	$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");        		
            	$admin_user_id = $parentdata->row['user_id'];        		
            		
          	}else{            			
            	$admin_user_id = $user_id;          			
          	} 
          

	   	$sql1 = "SELECT taxation_id,cgst,sgst,igst FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' AND tax_name='".$data['taxation']."' AND find_in_set(".$admin_user_id.",admin_user_id) <> 0 ORDER BY taxation_id DESC LIMIT 1";
	
		$data_tax = $this->query($sql1);
    	$tax_data=$data_tax->row; 
    	
    //	printr($tax_data);
        
      $by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');   
         $sql = "UPDATE `" . DB_PREFIX . "government_sales_invoice` SET taxation='".$data['taxation']."',cgst='".$tax_data['cgst']."',sgst='".$tax_data['sgst']."',igst='".$tax_data['igst']."',date_added = '".date('Y-m-d H:i:s')."',date_modify = '".date('Y-m-d H:i:s')."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',is_delete=0,igst_status='".$data['igst_status']."',edit_by='".$by."' WHERE sales_invoice_id='".$sales_invoice_id."'";
        
     //   echo $sql;
        	$data = $this->query($sql);	    
      
       
                    	foreach($data['addroll'] as $Roll)
						{
						      
						  if($data['pro_option']==1)
                             $Roll['product']=$Roll['product'];
                         else
                            $Roll['product']=$Roll['product_id'];
					     if(isset($Roll['keyword']) && $Roll['keyword']!='')
					        $Roll['keyword'] = $Roll['keyword'];
					     else
					        $Roll['keyword'] = '0';
    		              $sql_product = "UPDATE`" . DB_PREFIX . "government_sales_invoice_product` SET color_text = '".addslashes($Roll['description'])."',product_id = '".$Roll['product']."',product_code_id = '".$Roll['keyword']."',qty = '".$Roll['qty']."',rate = '".$Roll['rate']."',size = '".$Roll['size']."',net_weight = '".$Roll['net_weight']."',description = '".$Roll['description']."',measurement = '".$Roll['measurement']."',date_modify = NOW() ,edit_by='".$by."'WHERE  sales_invoice_product_id='".$Roll['sales_invoice_product_id']."'";
    	   	          //echo $sql_product;die;
    	   	              	$data_product = $this->query($sql_product);
						}
	   	           
		//$total_amt=$this->viewInvoice('',$sales_invoice_id,'','','0');
	
	return $sales_invoice_id;
		
    }
	public function getLastIdSalesInvoice($user_type_id,$user_id) {
	    
	    $sql_year='';
	    // if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1' ){
	           	$year = date("Y", time());
	           	$month = date("m", time());
                  $sql_year=" AND ( MONTH(invoice_date) > '3' AND YEAR(invoice_date) ='".$year."')OR ( MONTH(invoice_date) < '4' AND YEAR(invoice_date) ='".($year+1)."')";
		  //   }
	    if($user_type_id==''){
	  
	          $user_type_id=$_SESSION['LOGIN_USER_TYPE'];
	          $user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
	    }
	    //updated by [kinjal] on 30-11-2018
	   
		if($user_type_id=='1' && $user_id=='1'){
		     $sql = "SELECT max(invoice_no+0) as invoice_no FROM government_sales_invoice WHERE invoice_status ='1' AND is_delete='0' $sql_year ORDER BY sales_invoice_id DESC LIMIT 1";
		}else{
		      if($user_type_id == 2){
            			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
            			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
            			$set_user_id = $parentdata->row['user_id'];
            			$set_user_type_id = $parentdata->row['user_type_id'];
            		}else{
            			$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
            			$set_user_id = $user_id;
            			$set_user_type_id = $user_type_id;
            		}
            		$str = '';
            		if($userEmployee){
            			$str = ' OR ( added_user_id IN ('.$userEmployee.') AND added_user_type_id = 2 )';
            		}
            		 
              $sql = "SELECT max(invoice_no+0) as invoice_no  FROM government_sales_invoice WHERE invoice_status ='1' AND is_delete='0' AND (added_user_id = '".(int)$set_user_id."' AND added_user_type_id = '".(int)$set_user_type_id."' $str ) $sql_year ORDER BY sales_invoice_id DESC LIMIT 1";
		
		    //SELECT max(invoice_no) as invoice_no FROM government_sales_invoice WHERE invoice_status ='1' AND is_delete='0' AND (added_user_id = '".(int)$set_user_id."' AND added_user_type_id = '".(int)$set_user_type_id."' $str ) $sql_year  AND invoice_date=(SELECT max(invoice_date) FROM government_sales_invoice WHERE is_delete=0 group by sales_invoice_id ORDER BY `sales_invoice_id` DESC LIMIT 1)
		 //  $sql2 ="SELECT max(invoice_no) as invoice_no FROM government_sales_invoice WHERE invoice_status ='1' AND is_delete='0' AND (added_user_id = '".(int)$set_user_id."' AND added_user_type_id = '".(int)$set_user_type_id."' $str ) $sql_year  AND invoice_date=(SELECT max(invoice_date) FROM government_sales_invoice WHERE is_delete=0 group by sales_invoice_id ORDER BY `sales_invoice_id` DESC LIMIT 1)";
		    
		}
	
		    
		    
		 if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1' ){
      //   printr($set_user_id);  
      //  printr($sql);  
		 }
	//	 printr($sql);  
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['invoice_no'];
		}
		else {
			return false;
		}
	}
    public function GetproductcodeDetail($product_code_id){ 
        
        $sql="SELECT * FROM `product_code` WHERE `product_code_id` = '".$product_code_id."'";
        $data = $this->query($sql);	
    //echo $sql;die;
    	if($data->num_rows){
    		return $data->row;
    	}else{
    		return false; 
    	} 
    }public function GetColorDetail($pouch_color_id){
        
        $sql="SELECT * FROM `pouch_color` WHERE `pouch_color_id` = '".$pouch_color_id."'";
        $data = $this->query($sql);	
    //echo $sql;die;
    	if($data->num_rows){
    		return $data->row['color'];
    	}else{
    		return false; 
    	} 
    }
    public function change_invoice_qty($invoice_product_id,$qty,$n){
        if($n==0)
            $s="qty = ".$qty;
        else
            $s="rate = ".$qty;
        	$sql = "UPDATE `" . DB_PREFIX . "government_sales_invoice_product` SET ". $s." WHERE sales_invoice_product_id='".$invoice_product_id."'";
         //   echo $sql;die;
             $data = $this->query($sql);	
    }
 public function getUserListIndia($permission=0)
	{
			$userEmployee = $this->getUserEmployeeIds('4','6',$permission);//printr($userEmployee);
			$sql = "SELECT e.employee_id,CONCAT(e.first_name,' ',e.last_name,' ==> ',ib.company_name) as name,acc.user_name,acc.email FROM " . DB_PREFIX . "employee e , account_master acc,international_branch as ib WHERE acc.user_name=e.user_name AND acc.user_type_id = '2' AND acc.user_id IN (" .$userEmployee . ") AND e.user_type='20' AND e.user_id = ib.international_branch_id AND e.status='1' ORDER BY acc.user_name ASC";
			$data=$this->query($sql);
			if ($data->num_rows) {
				return $data->rows;
			} else {
				return false;
			}
	}
    public function viewDailyStockSalesReport($post){
        //printr($post);
      
        	$to_date = $post['t_date'];
            $f_date = $post['f_date'];
          
            $user=$post['user_id'];
            $user_sql=$user_name='';
             $arr=explode('=',$user);
              if($post['user_id']!=''){
                  $user_sql = "AND inv.added_user_id = '" . $arr[1] . "' AND  inv.added_user_type_id ='" . $arr[0] . "' ";
                  $user_name=$arr[2];
              }
              
        
           
          //$sql = "SELECT inv.*, CONCAT(e.first_name,' ',e.last_name) as name FROM " . DB_PREFIX . "government_sales_invoice as inv ,employee e  WHERE inv.is_delete = 0 AND inv.invoice_status='1'  AND inv.added_user_id =e.employee_id AND inv.date_added >= '" . $f_date . "' AND  inv.date_added <='" . $to_date . "'".$user_sql ;
          $sql = "SELECT inv.* FROM " . DB_PREFIX . "government_sales_invoice as inv   WHERE inv.is_delete = 0 AND inv.invoice_status='1' AND status=1 AND inv.invoice_date >= '" . $f_date . "' AND  inv.invoice_date <='" . $to_date . "'".$user_sql ;
          $data = $this->query($sql);	
       //printr($sql);
        $html='';
        	if ($data->num_rows) {
        	  
		
			$html='	<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
    				<div class="panel-body font_medium" id="print_div" style="font-size: 20px; page-break-before: always;" >';
	        // $html .='<div class="width_div"><div style="text-align:center;border: 1px solid black;"><b><h4> JOB REPORT</h4></b>'; 
	         $html .='<div style="text-align:center; font-size: 18px;"><b>SALES REPORT </b></div>';
	         $html .='<div style="text-align:center; font-size: 18px;"><b><span><h4>Searching Date From: <b>' . dateFormat(4, $f_date) . '</b> To: <b>' . dateFormat(4, $to_date) . '</b></h4><br><b>'.$user_name.'</b></span></b>';
		     $html.='</div>';
		     $html.='<div class="table-responsive" style=" width: 100%;float: left;  font-size: 12px;">';
				$html.='<table class="table table-striped b-t text-small" style=" width: 100%; border:1; font-size: 14px;" >
					<thead>
					<tr>
						<th><b>INVOICE NO</b></th>
						<th><b>DATE</b></th>
						<th><b>PARTY NAME</b></th>
						<th><b>BASIC AMT.RS.</b></th>
						<th><b>TOTAL AMT.RS.</b></th>
						<th><b>CYLINDER</b></th>';
						 if($post['user_id']==''){
						     $html.='	<th><b>SALES PERSON</b></th>';
						 }
					
				$html.='</tr>
					</thead>
					<tbody>';
					$Total_basic_price=$Total_invoice_total_amount=$Total_cylinder_charges=0;
                		 foreach ($data->rows as $d) {
                		       $cylinder=$this->getTotalcylinderDetail($d['sales_invoice_id']);  
                		     
                		     
                		//  printr($cylinder);
                		        $user_name = "SELECT * FROM account_master WHERE user_id = ".$d['added_user_id']." AND user_type_id = ".$d['added_user_type_id']."";
                		        $user = $this->query($user_name);
                		  $igst=$cgst=$sgst=0;
                		  
                		 
                		  	if($d['taxation']=='Out Of Gujarat'){	
                        	    //igst 
                        	    $igst=(($d['invoice_total_amount']*18)/118);
                        	}else if($d['taxation']=='SEZ Unit No Tax'){
                        	    $igst=$cgst=$sgst=0;
                        	}
                        	else{
                        	    //cgst sgst
                        	       $igst=(($d['invoice_total_amount']*18)/118);
                        	       
                        	}
                        	
                        
                    	$basic_price=$d['invoice_total_amount']-$igst-$d['tran_charges'];
                    	if($cylinder!='')
                    	    $cylinder_r=number_Format($cylinder,2);
                    	 else
                    	   $cylinder_r='';
                		  	$html.='<tr>';
            						$html.='<td>'.$d['invoice_no'].'</td>
            						        <td align="center">'.$d['invoice_date'].'</td>
            						        <td>'.$d['customer_name'].'</td>
            						        <td><center>'.round($basic_price).' </center></td>
            						        <td><center>'.$d['invoice_total_amount'].'</center></td>
            						        <td><center>'.$cylinder_r.'</center></td>
                    						';
                    			if($post['user_id']==''){
						               $html.='	<td><b>'.$user->row['user_name'].'</b></td>';
						 }
            					
            				$html.='</tr>';
                		    $Total_basic_price=$Total_basic_price+$basic_price;
                		    $Total_invoice_total_amount=$Total_invoice_total_amount+$d['invoice_total_amount'];
                		    $Total_cylinder_charges=$Total_cylinder_charges+$cylinder;
                		 }
                		 	$html.='<tr>';
                		 	
                        	    
                        	    
            						$html.='<td colspan="3"><b>TOTAL</b></td>
            						        <td><center><b>'.number_Format($Total_basic_price,2).' </b></center></td>
            						        <td><center><b>'.number_Format($Total_invoice_total_amount,2).'</b></center></td>
            						        <td><center><b>'.number_Format($Total_cylinder_charges,2).'</b></center></td>
                    						';
    							         
    	    	$html.=' </tr></tbody></table>';
				$html.='</div></div></form>';
			}
		//	printr($html);
			return $html;
    } 
    
    public function viewDailyStockRegisterReport($post){
       // printr($post);
        
            $col ='4';  $col1='24'; $col2='6'; 
        	if($post['product']=='0')
        	    $product = 'Pouch';
        	else
        	{
        	    $pro = $this->getActiveProductName($post['product']);
        	    $product = $pro['product_name'];
        	    if($post['product']==6 || $post['product']==64){
        	        $col ='2';$col1='22';$col2='4';}
        	}
        	$to_date = $post['t_date'];
            $f_date = $post['f_date'];
          
            $user=$post['user_id'];
            $user_sql=$user_name='';
             $arr=explode('=',$user);
              if($post['user_id']!=''){
                  $user_sql = "AND inv.added_user_id = '" . $arr[1] . "' AND  inv.added_user_type_id ='" . $arr[0] . "' ";
                  $user_name=$arr[2];
              }
              
          if($post['product']=='0')
            $sql = "SELECT inv.*FROM " . DB_PREFIX . "government_sales_invoice as inv ,government_sales_invoice_product as ip  WHERE  ip.sales_invoice_id=inv.sales_invoice_id AND inv.is_delete = 0 AND YEAR(inv.invoice_date) = '" . $to_date . "' AND  MONTH(inv.invoice_date) ='" . $f_date . "'".$user_sql." AND ip.product_id NOT IN (10,23,11,18,6,34,47,48,63,72,37,38,61,35,64,62,51)  GROUP by inv.sales_invoice_id ORDER BY inv.invoice_date ASC,inv.invoice_no ASC"  ;//,inv.sales_invoice_id
          else
            $sql = "SELECT inv.*,SUM(gp.qty) as total_qty,gp.*FROM " . DB_PREFIX . "government_sales_invoice as inv,government_sales_invoice_product as gp  WHERE inv.is_delete = 0  AND gp.sales_invoice_id = inv.sales_invoice_id AND gp.is_delete=0  AND YEAR(inv.invoice_date) = '" . $to_date . "' AND  MONTH(inv.invoice_date) ='" . $f_date . "'".$user_sql." AND product_id = '".$post['product']."' GROUP BY inv.sales_invoice_id ORDER BY inv.invoice_date ASC,inv.invoice_no ASC"  ;//,inv.sales_invoice_id

       // echo $sql;CAST(inv.invoice_no AS unsigned)
            $data = $this->query($sql);	
       
        $html='';
        	if ($data->num_rows) {
        	    	return $data->rows;
        	}else{
			return false;
		}

			
    }
    public function getTotalcylinderDetail($sales_invoice_id){
        
       
             $sql = "SELECT SUM(ip.qty*ip.rate) as cylinder_rate FROM  " . DB_PREFIX . "government_sales_invoice as i,government_sales_invoice_product as ip WHERE i.sales_invoice_id = '" .(int)$sales_invoice_id. "' 
    	            	AND i.is_delete=0 AND i.sales_invoice_id=ip.sales_invoice_id AND ip.product_id  ='51'";
       
        // printr($sql);
		$data = $this->query($sql);

		if($data->num_rows){
			return $data->row['cylinder_rate'];
		}else{
			return false;
		}
    } public function getTotalProductDetail($sales_invoice_id,$product_id='0'){
        
         if($product_id=='0')
         {
             $sql = "SELECT SUM(ip.qty*ip.rate) as basic_amt,ip.identification_marks  FROM  " . DB_PREFIX . "government_sales_invoice as i,government_sales_invoice_product as ip WHERE i.sales_invoice_id = '" .(int)$sales_invoice_id. "' 
    	            	AND i.is_delete=0 AND i.sales_invoice_id=ip.sales_invoice_id AND ip.product_id NOT IN (10,23,11,18,6,34,47,48,63,37,38,61,35,64,62)";
         }
         else
         {
              $sql = "SELECT SUM(ip.qty*ip.rate) as basic_amt,ip.identification_marks,ip.product_id  FROM  " . DB_PREFIX . "government_sales_invoice as i,government_sales_invoice_product as ip WHERE i.sales_invoice_id = '" .(int)$sales_invoice_id. "' 
    	            	AND i.is_delete=0 AND i.sales_invoice_id=ip.sales_invoice_id AND ip.product_id IN (".$product_id.",51)";
         }
		$data = $this->query($sql);

		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
    }
    
    public function AddOpening_Balance($post){
        
         $sql = "INSERT INTO `" . DB_PREFIX . "daily_stock_opening_balance` SET month='".$post['month']."',manufactured_date = '".$post['manufactured_date']."',month_opening = '".$post['month_opening']."',quantity_manufactured = '".$post['quantity_manufactured']."',month_opening_roll = '".$post['month_opening_roll']."',quantity_manufactured_roll = '".$post['quantity_manufactured_roll']."',month_opening_scrap = '".$post['month_opening_scrap']."',quantity_manufactured_scrap = '".$post['quantity_manufactured_scrap']."',date_added = NOW(),is_delete=0";
    
        $data = $this->query($sql);
    }
    public function UpdateOpening_Balance($daily_stock_opening_balance_id,$post){
        
         $sql = "UPDATE`" . DB_PREFIX . "daily_stock_opening_balance` SET month='".$post['month']."',month_opening = '".$post['month_opening']."',manufactured_date = '".$post['manufactured_date']."',quantity_manufactured = '".$post['quantity_manufactured']."',month_opening_roll = '".$post['month_opening_roll']."',quantity_manufactured_roll = '".$post['quantity_manufactured_roll']."',month_opening_scrap = '".$post['month_opening_scrap']."',quantity_manufactured_scrap = '".$post['quantity_manufactured_scrap']."',date_added = NOW(),is_delete=0 WHERE daily_stock_opening_balance_id ='".$daily_stock_opening_balance_id."'";
      
        $data = $this->query($sql);
    }  
    
    
     public function GetOpening_Balance_detail($daily_stock_opening_balance_id){
        
         $sql = "SELECT * FROM `daily_stock_opening_balance` WHERE `daily_stock_opening_balance_id` ='".$daily_stock_opening_balance_id."'";
      
        $data = $this->query($sql);
        if($data->num_rows){
        	    return $data->row;
        	}else{
        		return false; 
        	} 
    }
   
    public function getOpening_Balance($year='',$month='',$date = ''){
        $d_sql='';
        
     
        if($year!='' && $month==''){
            $d_sql=" AND  YEAR(manufactured_date) ='".$year."' GROUP BY month ";
        }
        if($month!=''){
            $d_sql=" AND  YEAR(manufactured_date) ='".$year."' AND  MONTH(manufactured_date) ='".$month."' ";
        }
        if($date!='')
             $d_sql.=" AND 	manufactured_date = '".$date."' ";
             
       $sql="SELECT * FROM `daily_stock_opening_balance` WHERE `is_delete` = '0' ".$d_sql;
       
        $data = $this->query($sql);
         
        	if($data->num_rows){
        	    return $data->rows;
        	}else{
        		return false; 
        	} 
    }
    
    
    public function government_sales_report($post){
       // printr($post);
        
          
        	$to_date = $post['t_date'];
            $f_date = $post['f_date'];
          
            $user=$post['user_id'];
            $user_sql=$user_name='';
             $arr=explode('=',$user);
              if($post['user_id']!=''){
                  $user_sql = "AND inv.added_user_id = '" . $arr[1] . "' AND  inv.added_user_type_id ='" . $arr[0] . "' ";
                  $user_name=$arr[2];
              }
              
          if($post['product']=='0')
            $sql = "SELECT inv.*FROM " . DB_PREFIX . "government_sales_invoice as inv ,government_sales_invoice_product as ip  WHERE  ip.sales_invoice_id=inv.sales_invoice_id AND inv.is_delete = 0 AND YEAR(inv.invoice_date) = '" . $to_date . "' AND  MONTH(inv.invoice_date) ='" . $f_date . "'".$user_sql." AND ip.product_id NOT IN (10,23,11,18,6,34,47,48,63,37,38,61,35,64,62)  GROUP by inv.sales_invoice_id ORDER BY inv.invoice_date ASC ,inv.invoice_no ASC"  ;//,inv.sales_invoice_id
          else
            $sql = "SELECT inv.*,SUM(gp.qty) as total_qty,gp.*FROM " . DB_PREFIX . "government_sales_invoice as inv,government_sales_invoice_product as gp  WHERE inv.is_delete = 0  AND gp.sales_invoice_id = inv.sales_invoice_id AND gp.is_delete=0  AND YEAR(inv.invoice_date) = '" . $to_date . "' AND  MONTH(inv.invoice_date) ='" . $f_date . "'".$user_sql." AND gp.product_id = '".$post['product']."' GROUP BY inv.sales_invoice_id ORDER BY inv.invoice_date ASC,inv.invoice_no ASC"  ;//,inv.sales_invoice_id

       //echo $sql;
            $data = $this->query($sql);	
       
        $html='';
        	if ($data->num_rows) { //DateTime::createFromFormat('!m', $post['f_date'])->format('F')
        	   
				$html.='<style>
                    table, th, td {
                        border: 1px solid black;
                    }
                    </style>';		
			$html='	<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
    				<div class="panel-body font_medium" id="print_div" style="font-size: 20px; page-break-before: always;" >';
	       
		     
			        $arr=array();
				    foreach ($data->rows as $d) {
				      // printr($d);
				        $invoice_product_details=$this->getTotalProductDetail($d['sales_invoice_id'],$post['product']);
				        $product_detail = $this->getSalesInvoiceProduct($d['sales_invoice_id']);
				        
				        $first='false';
				        
				          $allproduct = $this->getSalesInvoiceProduct($d['sales_invoice_id'],1);
				       if(!empty($allproduct))
                    		{
                    		   if($first=='false')
                    			{
                    			    $freight = 9;$first = 'true';
                    			} 
                    		}
                    				        
				        foreach($product_detail as $details)
				        {
				            if($details['product_id']=='11')
			                {
			                    if($first=='false')
			                        $freight = 1;$first = 'true';
			                }
			                else if($details['product_id']=='6')
			                {
			                    if($first=='false')
			                        $freight = 2;$first = 'true';
			                }
			                else if($details['product_id']=='10')
			                {
			                    if($first=='false')
			                        $freight = 3;$first = 'true';
			                }
			                else if($details['product_id']=='23')
			                {
			                    if($first=='false')
			                        $freight = 4;$first = 'true';
			                }
			                else if($details['product_id']=='18')
			                {
			                    if($first=='false')
			                        $freight = 5;$first = 'true';
			                }
			                else if($details['product_id']=='34')
			                {
			                    if($first=='false')
			                        $freight = 6;$first = 'true';
			                }
			                else if($details['product_id']=='47')
			                {
			                    if($first=='false')
			                        $freight = 7;$first = 'true';
			                }
			                else if($details['product_id']=='48')
			                {
			                    if($first=='false')
			                        $freight = 8;$first = 'true';
			                }
			                else if($details['product_id']!='11' && $details['product_id']!='6' && $details['product_id']!='10' && $details['product_id']!='23' && $details['product_id']!='18' && $details['product_id']!='34' && $details['product_id']!='47' && $details['product_id']!='48' && $details['product_id']!='63')
			                {
			                    if($first=='false')
			                        $freight = 9;$first = 'true';
			                }
				        }
				        //printr($freight);
				        $fre_charge = 0;
				       
				        if($post['product']=='11')
				        {
				            if($freight==1)
				                $fre_charge = $d['tran_charges']; 
				        }
				        else if($post['product']=='6')
				        {
				            if($freight==2)
				                $fre_charge = $d['tran_charges']; 
				        }
				        else if($post['product']=='10')
				        {
				            if($freight==3)
				                $fre_charge = $d['tran_charges']; 
				        }
				        else if($post['product']=='23')
				        {
				            if($freight==4)
				                $fre_charge = $d['tran_charges']; 
				        }
				        else if($post['product']=='18')
				        {
				            if($freight==5)
				                $fre_charge = $d['tran_charges']; 
				        }
				        else if($post['product']=='34')
				        {
				            if($freight==6)
				                $fre_charge = $d['tran_charges']; 
				        }
				        else if($post['product']=='47')
				        {
				            if($freight==7)
				                $fre_charge = $d['tran_charges']; 
				        }
				        else if($post['product']=='48')
				        {
				            if($freight==8)
				                $fre_charge = $d['tran_charges']; 
				        }
				        else if($post['product']!='11' && $post['product']!='6' && $post['product']!='10' && $post['product']!='23' && $post['product']!='18' && $post['product']!='34' && $post['product']!='47' && $post['product']!='48' && $post['product']!='63')
				        {
				            if($freight==9)
				                $fre_charge = $d['tran_charges']; 
				        }
				        
				        
				        
				        
				        
				        
				    //  printr($d);
				        
				    if($d['taxation']=='SEZ Unit No Tax' && $d['igst']=='0.00' && ($d['country_id']=='26' || $d['country_id']=='27' || $d['country_id']=='169')){
				        $d['invoice_status']=3;
				    }
				     if($d['status']=='2'){
				         
				        $d['invoice_status']=4;
				    }
				      if($d['status']=='0'){
				        $d['invoice_status']=5;
				    }
				        
				        $arr[$d['invoice_status']][]=array(
				                                    'invoice_no'=>$d['invoice_no'],
				                                    'invoice_date'=>$d['invoice_date'],
				                                    'weight'=>$invoice_product_details['identification_marks'],
				                                    'basic_amt'=>$invoice_product_details['basic_amt'],
				                                    'frieght'=>$fre_charge,
				                                    'igst'=>$d['igst'],
				                                    'sgst'=>$d['sgst'],
				                                    'cgst'=>$d['cgst'],
				                                    'taxation'=>$d['taxation'],
				                                    'product'=>$post['product'],
				                                    'country_id'=>$post['country_id'],
				                                    'transport'=>$d['transport']
				            );
				        	
				    }
			 
				// printr($arr);
				 $html.='<div class="row">';
				 //pick up
			
				 foreach($arr as $key=>$arr1){
				     
				     if($key=='0'){
				        $title='EXPORT';$class="col-lg-4";
				   }  else if($key=='1'){
				        $title='LOCAL';$class="col-lg-5";
				     } else if($key=='4'){
				        $title='RETURN INVOICE';$class="col-lg-4";
				     } else if($key=='5'){ 
				        $title='CANCEL INVOICE';$class="col-lg-4";
				     }else{
				         $title='NEPAL EXPORT';$class="col-lg-4";
				     }
        				 $html.='<div class='.$class.'> <div class="table-responsive" style=" width: 100%;float: left;  font-size: 18px;">';
        				    $html.='<table class="table table-striped b-t text-small" style=" width: 100%; border:1; font-size: 18px;" >
                					<thead>
                					<tr><th colspan="8"><center><b> '.$title.'</b></center></th></tr>
                				        	<tr>
                    					        <th>Invoice No</th>
                        						<th>Date</th>
                        						<th>Weight</th>
                        						<th>Basic AMT</th>
                        						<th>FREIGHT T RS.</th>';
                        						if($title=='LOCAL'){
                            					$html.='<th>IGST</th>
                            						<th>CGST</th>
                            						<th>SGST</th>';
                            						}
                    				    $html.='</tr>';
                					
                        			$html.='</thead>
                					<tbody>';
                					 $total_bastic_amr=$t_weight=$t_frieght=$t_igst=$t_cgst=$t_sgst=0;
                					 foreach($arr1 as $road){
                					   	if($title=='LOCAL'){
                					  //   printr($road);
                					    // printr($road['taxation']);
                					   
                					     	}
                					     	  	$cgst=$sgst=$igst=0; 
                			         $html.='<tr>
                        					        <td>'.$road['invoice_no'].'</td>
                            						<td>'.$road['invoice_date'].'</td>
                            						<td>'.$road['weight'].'</td>
                            						<td>'.$this->numberFormate($road['basic_amt'],3).'</td>
                            						<td>'.$road['frieght'].'</td>';
                            						if($title=='LOCAL'){
                            						    
                            						  if($road['taxation']=='Out Of Gujarat'){
                            						      $igst=round(($road['basic_amt']+$road['frieght'])*$road['igst']/100);
                            						  }else if($road['taxation']=='With in Gujarat'){
                            						     $cgst= round(($road['basic_amt']+$road['frieght'])*$road['cgst']/100);
                            						     $sgst= round(($road['basic_amt']+$road['frieght'])*$road['sgst']/100);
                            						  }
                            					$html.='<td>'.$igst.'</td>
                            						<td>'.$cgst.'</td>
                            						<td>'.$sgst.'</td>';
                            						}	
                            						
                        				    $html.='</tr>';
            			          $total_bastic_amr+=$road['basic_amt'];
            			          $t_igst+=$igst;
            			          $t_cgst+=$cgst; 
            			          $t_sgst+=$sgst;
            			          $t_weight+=$road['weight'];
            			          $t_frieght+=$road['frieght'];
            			      }   
            			       $html.='<tr>
                					        <td colspan="2"><b>TOTAL:</b></td>
                    					
                    						<td>'.$t_weight.'</td>
                    						<td>'.$total_bastic_amr.'</td>
                    						<td>'.$t_frieght.'</td>';  
                    						if($title=='LOCAL'){
                    						 	$html.='<td>'.$t_igst.'</td>
                            						<td>'.$t_cgst.'</td>
                            						<td>'.$t_sgst.'</td>';
                            						}	
                            						    
            	    	$html.='</tbody></table></div></div>';
            	    	
            
			
                	}
				
				$html.='</div></div></form>';
			}

			return $html;
    }
    public function getIndiaState()
	{ 
	    $data =$this->query("SELECT * FROM india_state WHERE status=1 AND is_delete=0");
	    if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
    public function getActiveProductCode()

	{

		$sql = "SELECT * FROM `" . DB_PREFIX . "product_code` WHERE is_delete=0 AND status=1 ";

		$data = $this->query($sql);

		if($data->num_rows){

			return $data->rows;

		}else{

			return false;

		}

	}
    public function dispatch_product($invoice_id)
    {
        $gov_data=$this->getSalesInvoiceData($invoice_id);
        
        $pro = "SELECT pro_in_no FROM  proforma_product_code_wise WHERE proforma_id = '" .(int)$gov_data['invoice_id']. "'  AND is_delete=0";
		$data_pro = $this->query($pro);
        
        $sql = "SELECT * FROM  government_sales_invoice_product WHERE sales_invoice_id = '" .(int)$invoice_id. "'  AND is_delete=0";
		$data = $this->query($sql);
	    
	    if($_SESSION['LOGIN_USER_TYPE'] == 2){
			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
			$set_user_id = $parentdata->row['user_id'];
			$set_user_type_id = $parentdata->row['user_type_id'];
		}else{
			$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
			$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		}
        
		if($data->num_rows){
		    foreach($data->rows as $row)
		    {
    		    $select="SELECT * FROM stock_management WHERE product_code_id='".$row['product_code_id']."' AND goods_id='43'";
    		    $data1 = $this->query($select);
    		    if($data1->num_rows)
    		    {
        		    $insert="INSERT INTO stock_management SET proforma_no='".$data_pro->row['pro_in_no']."',invoice_no='".$gov_data['invoice_no']."',dispatch_qty='".$row['qty']."',parent_id='".$data1->row['stock_id']."',product='".$row['product_id']."',goods_id='".$data1->row['goods_id']."' , row='".$data1->row['row']."' ,column_name='".$data1->row['column_name']."',company_name='".addslashes($gov_data['company_name'])."',description=2,status=1,date_added=NOW(),date_modify=NOW(), added_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',added_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."', user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',product_code_id='".$row['product_code_id']."'";
        			$data2=$this->query($insert);
    		    }
		    }
		}else{
			return false;
		}
		$data3=$this->query("UPDATE government_sales_invoice SET dis_status='0' WHERE sales_invoice_id = '" .(int)$invoice_id. "'");
    }
    
    public function getIndiaStateDetails($state_id)
	{
	    $data =$this->query("SELECT * FROM india_state WHERE status=1 AND is_delete=0 AND state_id='".$state_id."'");
	    if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	 public function viewDailyStockSalesReportStateWise($post){
        //printr($post);
      
        	$to_date = $post['t_date'];
            $f_date = $post['f_date'];
            $state_sql='';
            $state_id=$post['state_id'];
            if(!empty($post['state_id'])){
                $state_sql=" AND g.state_id='".$post['state_id']."' ";
            }
           
          //$sql = "SELECT inv.*, CONCAT(e.first_name,' ',e.last_name) as name FROM " . DB_PREFIX . "government_sales_invoice as inv ,employee e  WHERE inv.is_delete = 0 AND inv.invoice_status='1'  AND inv.added_user_id =e.employee_id AND inv.date_added >= '" . $f_date . "' AND  inv.date_added <='" . $to_date . "'".$user_sql ;
       
       
         //$sql = " SELECT * FROM government_sales_invoice_product as gs, government_sales_invoice as g, india_state as s WHERE g.country_id = 111 AND g.invoice_status!=0 AND g.state_id != 0 AND gs.sales_invoice_id=g.sales_invoice_id AND g.invoice_date > '2018-08-31' AND g.is_delete=0   AND g.invoice_date BETWEEN '".$f_date."' AND '".$to_date."' AND g.invoice_status='1' AND (g.added_user_id !='39'  AND g.added_user_type_id!='4'  OR ( g.added_user_id NOT IN (175,176,177,178,179,180,191,206,210,214) AND g.added_user_type_id != 2 ))   AND  g.state_id=s.state_id  $state_sql GROUP By g.sales_invoice_id ORDER BY s.state" ;
         $sql = " SELECT * FROM government_sales_invoice_product as gs, government_sales_invoice as g, india_state as s WHERE g.country_id = 111 AND g.invoice_status!=0 AND g.state_id != 0 AND gs.sales_invoice_id=g.sales_invoice_id  AND g.is_delete=0   AND g.invoice_date BETWEEN '".$f_date."' AND '".$to_date."' AND gs.product_name NOT LIKE 'Oxygen Absorbers%' AND gs.product_name NOT LIKE 'Silica Gel / Moisture Absorbers%'  AND  g.state_id=s.state_id  $state_sql GROUP By g.sales_invoice_id ORDER BY s.state" ;
        // printr( $sql);
         
         
         // $sql1 = " SELECT GROUP_CONCAT(sales_invoice_id) as id,s.state FROM government_sales_invoice as g, india_state as s WHERE g.country_id = 111 AND g.invoice_status!=0 AND g.state_id != 0 AND g.invoice_date > '2018-08-31'   AND g.invoice_date BETWEEN '".$f_date."' AND '".$to_date."' AND g.state_id=s.state_id   GROUP by g.state_id" ;
       //   $sql = " SELECT GROUP_CONCAT(sales_invoice_id) as id,s.state FROM government_sales_invoice as g, india_state as s WHERE g.country_id = 111 AND g.invoice_status!=0 AND g.state_id != 0 AND g.invoice_date > '2018-08-31'   AND  //YEAR(inv.invoice_date) = '" . $to_date . "' AND  MONTH(inv.invoice_date) ='" . $f_date . "' AND g.state_id=s.state_id   GROUP by g.state_id" ;
         
         
           $data = $this->query($sql);	
           // printr($data);
   
   
   
   
   
        $html='';
        	if ($data->num_rows) {
		
			$html='	<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
    				<div class="panel-body font_medium" id="print_div" style="font-size: 20px; page-break-before: always;" >';
	         $html .='<div style="text-align:center; font-size: 18px;"><b>SALES REPORT STATE WISE </b></div>';
	         $html .='<div style="text-align:center; font-size: 18px;"><b><span><h4>Searching Date From: <b>' . dateFormat(4, $f_date) . '</b> To: <b>' . dateFormat(4, $to_date) . '</b></h4><br><b>'.$user_name.'</b></span></b>';
		     $html.='</div>';
		     $html.='<div class="table-responsive" style=" width: 100%;float: left;  font-size: 12px;">';
				$html.='<table class="table table-striped b-t text-small" style=" width: 100%; border:1; font-size: 14px;" >
					<thead>
					<tr>
						<th><b>Sr no </b></th>
						<th><b>Party Name</b></th>
						<th><b>State</b></th>
						<th><b>Billing Address</b></th>
						<th><b>Delivery Address</b></th>
						<th>GST No.</th>
						<th>Weight</th>
						<th><b>Bill No.</b></th>
						<th>Bill Date</th>';
				$html.='</tr>
					</thead>
					<tbody>';
					$Total_basic_price=$Total_invoice_total_amount=$Total_cylinder_charges=0;
					$i=1;
                /*		 foreach ($data->rows as $d) {
                		     
                		     $net_weight=0;
                    	     //  printr($d);//die;
                    	       $arr=explode(',',$d['id']);
                    	       foreach($arr as $a){
                    	             $data = $this->query("SELECT identification_marks FROM government_sales_invoice_product WHERE sales_invoice_id='".$a."'");
                    	          //  printr($data);
                    	            $net_weight=$net_weight+$data->row['identification_marks'];
                    	       } 
                		  	$html.='<tr>'; 
            						$html.='<td>'.$i.'</td>';
            						$html.='<td>'.$d['state'].'</td>';
            						
            				        $html.='<td>'.$net_weight.' KGS</td>
            				        
                    						';
                    				$html.='</tr>';
                        		 $marks=$marks+$net_weight;
					    $i++;	 }
            					
                    		 	$html.='<tr>';
            						$html.='
            						        <td colspan="2"><b>Total</b></td>
            						        <td><b>'.number_Format($marks,2).' </b></td>
            						        
                    						';*/
                    						
            		 foreach ($data->rows as $d) {
                		     
                		  	$html.='<tr>'; 
            						$html.='<td>'.$i.'</td>';
            						$html.='<td>'.$d['customer_name'].'</td>';
            						$html.='<td>'.$d['state'].'</td>';
            						$html.='<td>'.$d['consignee'].'</td>';
            						$html.='<td>'.$d['other_buyer'].'</td>';
            						$html.='<td>'.$d['gst_no'].'</td>';
            						$html.='<td>'.$d['identification_marks'].'  KGS</td>';
            						$html.='<td>'.$d['invoice_no'].'</td>';
            						$html.='<td>'.$d['invoice_date'].'</td>'; 
            			    $html.='</tr>';
                        		
					    $i++;	 
            		      $marks=$marks+$d['identification_marks'];
            		 }
            			$html.='<tr>';
            						$html.='
            						        <td colspan="6"><b>Total Kgs</b></td>
            						        <td><b>'.number_Format($marks,2).' KGS</b></td></tr>';		
                    	
    							         
    	    	$html.='</tbody></table>';
				$html.='</div></div></form>';
			}
		//	printr($html);
			return $html;
    } 
    
    public function viewInvoice_taxation($statu=0,$invoice_no,$copy_status,$pdf,$list=0,$tax=0){
	
	//	printr('hiohigweg');
		/*if($_SESSION['ADMIN_LOGIN_SWISS']==1 && $_SESSION['LOGIN_USER_TYPE']==1)
		{
		    $data=$this->query("SELECT * FROM sample_request WHERE request_id='1179'");
		    
		    print_r(str_replace('\r\n',' <br>',$data->row['address']));
		}*/
		$invoice = $this->getSalesInvoiceData($invoice_no);
		$invoice_inv_data = $this->getInvoiceNetData($invoice['invoice_id']);
		$pro_inv_data = $this->getProforma($invoice['invoice_id']);
		
	
	    $invoice_product_second = $this->getSalesInvoiceProduct($invoice_no);
		//printr($invoice);die;
		$pallet=$this->getPalletS($invoice['invoice_id']);
		//printr($pallet);
		$total_pallet=count($pallet);
		//$total_pallet=20;
		$total_pallet_box=0;
		foreach($pallet as $p)
		{
			$total_pallet_box=$p['tot']+$total_pallet_box;
		}
	
		//$total_pallet_weight=$total_pallet*23;
		//[kinjal] on 20-2-2017 told by pinank
		$total_pallet_weight=$total_pallet*12;
		$invoice_qty=$this->getInvoiceTotalData($invoice['invoice_id']);
	//	printr($invoice_qty);//die;
		$box_detail=$this->gettotalboxweight($invoice['invoice_id']);
		//printr($box_detail);
		$box_det=$this->gettotalboxweight($invoice['invoice_id'],'1');
		
		$alldetails=$this->getSalesInvoiceProduct($invoice_no);
		//$tot_qty_scoop=0;
	
		//$tot_qty_scoop=0;
		//$flag1 = array();
		$scoop_no = $roll_no = $mailer_no = $sealer_no = $storezo_no = $paper_box_no  = $con_no =$gls_no = $val_no= $chair_no =$silica_gel_no =$oxygen_absorbers_no= 0;
		
		//sonu add 15-5-2017
		$scoop_box_no = $roll_box_no = $mailer_box_no = $sealer_box_no = $storezo_box_no = $paper_box_no= $pouch_box_no = $con_box_no = $gls_box_no =$chair_box_no = $oxygen_absorbers_box_no= $silica_gel_box_no= $val_box_no =0;
		$scoop_name = $roll_name = $mailer_name = $sealer_name = $storezo_name = $paper_box_name=$pouch_name = $con_box_name=$gls_box_name=$chair_box_name=$oxygen_absorbers_box_name=$silica_gel_box_name=$val_box_name='';
		//end
	
	
	    $roll_amt_with_tax=$scoop_amt_with_tax=$final_amt_with_tax=$pouch_amt_with_tax=$mailer_amt_with_tax=$strezo_amt_with_tax=$paper_amt_with_tax=$con_amt_with_tax	=$gls_amt_with_tax=$chair_amt_with_tax=$oxy_amt_with_tax=$silica_amt_with_tax=$val_amt_with_tax	=0;
		$igst_tax=$igst_tax_scoop=$igst_tax_roll=$igst_tax_mailer=$igst_tax_strezo=$igst_tax_paper=$igst_tax_con=$igst_tax_gls=$igst_tax_chair=$igst_tax_oxy=$igst_tax_silica=$igst_tax_val=$final_amt_with_tax=$total_with_tax=0;
	
		$scoop_series = $roll_series = $mailer_series = $sealer_series = $storezo_series = $paper_series= $chair_series= $oxygen_absorbers_series= $silica_gel_series = '';		
		$total_amt_scoop = $total_amt_roll = $total_amt_mailer = $total_amt_sealer = $total_amt_storezo = $total_amt_paper= $total_amt_chair =$total_amt_oxygen_absorbers =$total_amt_silica_gel =$total_amt_con=$total_amt_gls=$total_amt_valve= 0;
		//sonu add 15-5-2017
		$total_net_w_scoop =$total_gross_w_scoop = $total_net_w_roll=$total_gross_w_roll= $total_net_w_p =$total_gross_w_p  = $total_net_w_m =$total_gross_w_m= $total_net_w_s =$total_gross_w_s =$total_net_w_str =$total_gross_w_str =$total_net_w_pouch=$total_net_w_con=$total_net_w_gls=$total_net_w_chair=$total_net_w_oxygen_absorbers=$total_net_w_silica_gel=$total_net_w_val=0;
		//end
		$tot_scoop_qty = $tot_roll_qty = $tot_mailer_qty = $tot_sealer_qty = $tot_storezo_qty =$tot_con_qty- $tot_paper_chair = $tot_paper_qty = $tot_paper_qty = $tot_gls_qty =$tot_con_qty= $total_qty_val=$tot_chair_qty=$tot_oxygen_absorbers_qty=$tot_silica_gel_qty=0;
		$tot_scoop_rate = $tot_roll_rate = $tot_mailer_rate = $tot_sealer_rate = $tot_storezo_rate = $tot_chair_rate= $tot_paper_rate =$tot_con_rate=$tot_gls_rate=$tot_val_rate=$tot_oxygen_absorbers_rate=$tot_silica_gel_rate= $tot_val_qty=0;
		$total_net_amt_scp= $total_net_amt_rol=$total_net_amt_m=$total_net_amt_pouch=$total_net_amt_s=$total_net_amt_str=$total_net_amt_chair=$total_net_amt_oxygen_absorbers=$total_net_amt_silica_gel=$total_net_amt_ppr=0;
	    $scoop_no_of_package=$roll_no_of_package=$mailer_no_of_package= $sealer_no_of_package=$storezo_no_of_package=$paper_no_of_package=$pouch_no_of_package=$con_no_of_package=$gls_no_of_package=$chair_no_of_package=$oxygen_absorbers_no_of_package=$silica_gel_no_of_package=$val_no_of_package=$total_no_of_package=0;
	    $scoop_identification_marks=$roll_identification_marks=$mailer_identification_marks= $sealer_identification_marks=$storezo_identification_marks=$paper_identification_marks=$pouch_identification_marks=$con_identification_marks=$gls_identification_marks=$chair_identification_marks=$oxygen_absorbers_identification_marks=$silica_gel_identification_marks=$val_identification_marks= $total_identification_marks=0;
	
	    $no_of_package=' BOXES';
	    $identification_marks=' KGS';
		$abcd = 'A';
		//sonu add 15-5-2017
		$f_p=$f_scoop=$f_roll=$f_mailer=$f_sealer=$f_storezo=$f_box=$f_con=$f_gls=$f_chair=$f_val=true;
		//end
		$first='false';$air_f = 0;
		
	 
	 $allproduct = $this->getSalesInvoiceProduct($invoice_no,1);
	// printr($allproduct);
	       if(!empty($allproduct))
        		{
        		   if($first=='false')
        			{
        			     $air_f = 0;
	                    $first = 'true';
        			} 
        		}
        		
    //    printr($allproduct);
		foreach($alldetails as $details)
		{ 
		
			
		
			if($details['product_id']=='11')
			{//printr($details);
				$tot_qty_scoop=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
			//	printr($tot_qty_scoop);
				$tot_scoop_qty = $tot_scoop_qty + $tot_qty_scoop['total']; 
				$tot_scoop_rate = $tot_scoop_rate + $tot_qty_scoop['rate'];
				$total_amt_scoop = $total_amt_scoop + $tot_qty_scoop['tot_amt'];
				$net_pouches_scoop = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_scoop = $net_pouches_scoop ['n_wt'];
			//	printr($net_pouches_scoop);
				//sonu add 15-5-2017
				$total_gross_w_scoop = $net_pouches_scoop ['g_wt'];
				$scoop_box_no =  $net_pouches_scoop ['total_box'];
			    $group_scoop_id = $net_pouches_scoop['group_id'];
			    $scoop_no_of_package=$details['no_of_packages'];
			    $scoop_identification_marks=$details['identification_marks'];
				$scoop_name = 'SCOOPS';
				//end
				$total_net_amt_scp = $net_pouches_scoop['total_amt'];
				$scoop_no = '1';
				$scoop_series = $abcd;
				
			     $scoop_gst_details=$this->getProductGST($details['product_id']);
			
				if($first=='false')
				{
				    $air_f = 1;
				    $first = 'true';
				}
			}
			else if($details['product_id']=='6' )
			{   
				$tot_qty_roll=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_roll_qty = $tot_roll_qty + $tot_qty_roll['total']; 
				$tot_roll_rate = $tot_roll_rate + $tot_qty_roll['rate'];
				//$total_amt_roll = $total_amt_roll + $tot_qty_roll['tot_amt'];
				$net_pouches_roll = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_roll = $net_pouches_roll['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_roll = $net_pouches_roll['g_wt'];
				$roll_box_no = $net_pouches_roll['total_box'];
				$group_roll_id = $net_pouches_roll['group_id'];
				 $roll_no_of_package=$details['no_of_packages'];
			    $roll_identification_marks=$details['identification_marks'];
				$roll_name = 'ROLL';
				//end 
				
			//	$net_pouches_roll['total_amt']=$net_pouches_roll['total_amt']- ($net_pouches_roll['qty']*$tot_qty_roll['rate']);
				   $roll_gst_details=$this->getProductGST($details['product_id']);
				$roll_price=$net_pouches_roll['qty']*$tot_qty_roll['rate'];
				$total_amt_roll = $net_pouches_roll['total_amt'];
				$total_net_amt_rol = $net_pouches_roll['total_amt'];
				$roll_no = '1';
				$roll_series = $abcd;
				if($first=='false')
				{
				    $air_f = 2;
				     $first = 'true';
				}
			}/*	else if( $details['product_id']=='67' )
			{   
				$tot_qty_roll=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_roll_qty = $tot_roll_qty + $tot_qty_roll['total']; 
				$tot_roll_rate = $tot_roll_rate + $tot_qty_roll['rate'];
				$total_amt_roll = $total_amt_roll + $tot_qty_roll['tot_amt'];
				$net_pouches_roll = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_roll = $net_pouches_roll['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_roll = $net_pouches_roll['g_wt'];
				$roll_box_no = $net_pouches_roll['total_box'];
				$group_roll_id = $net_pouches_roll['group_id'];
				 $roll_no_of_package=$details['no_of_packages'];
			    $roll_identification_marks=$details['identification_marks'];
				$roll_name = 'ROLL';
				//end 
				$total_net_amt_rol = $net_pouches_roll['total_amt'];
				$roll_no = '1';
				$roll_series = $abcd;
				if($first=='false')
				{
				    $air_f = 2;
				     $first = 'true';
				}
			}*/
			else if($details['product_id']=='10')
			{
				$tot_qty_mailer=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_mailer_qty = $tot_mailer_qty + $tot_qty_mailer['total']; 
				$tot_mailer_rate = $tot_mailer_rate + $tot_qty_mailer['rate'];
				$total_amt_mailer = $total_amt_mailer + $tot_qty_mailer['tot_amt'];
				$net_pouches_m = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				//printr($net_pouches_m);
				$total_net_amt_m = $net_pouches_m['total_amt'];
				$total_net_w_m = $net_pouches_m['n_wt'];
				
				//sonu add 15-5-2017	
				$total_gross_w_m = $net_pouches_m['g_wt'];
				$mailer_box_no = $net_pouches_m['total_box'];
				$group_mail_id = $net_pouches_m['group_id'];
				$mailer_no_of_package=$details['no_of_packages'];
			    $mailer_identification_marks=$details['identification_marks'];
				$mailer_name = 'MAILER BAGS';		
				//end
				   $mailer_gst_details=$this->getProductGST($details['product_id']);
				$mailer_no = '1';
				$mailer_series = $abcd;
				if($first=='false')
				{
				    $air_f = 3;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='23')
			{
				$tot_qty_sealer=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_sealer_qty = $tot_sealer_qty + $tot_qty_sealer['total']; 
				$tot_sealer_rate = $tot_sealer_rate + $tot_qty_sealer['rate'];
				$total_amt_sealer = $total_amt_sealer + $tot_qty_sealer['tot_amt'];
				$net_pouches_s = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_s = $net_pouches_s['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_s = $net_pouches_s['g_wt'];
				$sealer_box_no = $net_pouches_s['total_box'];
				$group_sealer_id=$net_pouches_s['group_id'];
				 $sealer_no_of_package=$details['no_of_packages'];
			    $sealer_identification_marks=$details['identification_marks'];
				$sealer_name ='SEALER MACHINE';
				//end
				$total_net_amt_s = $net_pouches_s['total_amt'];
			   $sealer_gst_details=$this->getProductGST($details['product_id']);
				$sealer_no = '1';
				$sealer_series = $abcd;
				if($first=='false')
				{
				    $air_f = 4;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='18')
			{
				$tot_qty_storezo=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
			//	printr($tot_qty_storezo);
				$tot_storezo_qty = $tot_storezo_qty + $tot_qty_storezo['total']; 
				$tot_storezo_rate = $tot_storezo_rate + $tot_qty_storezo['rate'];
				$total_amt_storezo = $total_amt_storezo + $tot_qty_storezo['tot_amt'];
				$net_pouches_str = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
			//	printr($net_pouches_str);
				$total_net_w_str = $net_pouches_str['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_str = $net_pouches_str['g_wt'];
				$storezo_box_no = $net_pouches_str['total_box']; 
				$storezo_name = 'STOREZO';
				//end
			     $storezo_gst_details=$this->getProductGST($details['product_id']);
				$total_net_amt_str = $net_pouches_str['total_amt'];
				$group_str_id = $net_pouches_str['group_id'];
				$storezo_no_of_package=$details['no_of_packages'];
			    $storezo_identification_marks=$details['identification_marks'];
				$storezo_no = '1';
				$storezo_series = $abcd;
				if($first=='false')
				{
				    $air_f = 5;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='34')
			{
				$tot_qty_paper=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_paper_qty = $tot_paper_qty + $tot_qty_paper['total']; 
				$tot_paper_rate = $tot_paper_rate + $tot_qty_paper['rate'];
				$total_amt_paper = $total_amt_paper + $tot_qty_paper['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_p = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_p = $net_pouches_p['g_wt'];
				$paper_box_no = $net_pouches_p['total_box'];
				$group_paper_id = $net_pouches_p['group_id'];
				 $paper_no_of_package=$details['no_of_packages'];
			    $paper_identification_marks=$details['identification_marks'];
				$paper_box_name = 'PAPER BOX';
				//end
				
			    $paper_gst_details=$this->getProductGST($details['product_id']);
				$total_net_amt_ppr = $net_pouches_p['total_amt'];
				$paper_box_no = '1';
				$paper_series = $abcd;
				if($first=='false')
				{
				    $air_f = 6;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='47')
			{
				$tot_qty_con=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				
				
			//	printr($details);
			//	printr($tot_qty_con);
				$tot_con_qty = $tot_con_qty + $tot_qty_con['total']; 
				$tot_con_rate = $tot_con_rate + $tot_qty_con['rate'];
				$total_amt_con = $total_amt_con + $tot_qty_con['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_con = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_con = $net_pouches_p['g_wt'];
				$con_box_no = $net_pouches_p['total_box'];
				$con_box_name = 'PLASTIC DISPOSABLE LID / CONTAINER';
				//end
				
				$con_gst_details=$this->getProductGST($details['product_id']);
				$total_net_amt_con = $net_pouches_p['total_amt'];
				$con_no_of_package=$details['no_of_packages'];
			    $con_identification_marks=$details['identification_marks'];
				$con_no = '1';
				$con_series = $abcd;
				if($first=='false')
				{
				    $air_f = 7;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='48')
			{
				$tot_qty_gls=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_gls_qty = $tot_gls_qty + $tot_qty_gls['total']; 
				$tot_gls_rate = $tot_gls_rate + $tot_qty_gls['rate'];
				$total_amt_gls = $total_amt_gls + $tot_qty_gls['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_gls = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_gls = $net_pouches_p['g_wt'];
				$gls_box_no = $net_pouches_p['total_box'];
				$gls_box_name = 'PLASTIC GLASSES';
				//end
				$gls_gst_details=$this->getProductGST($details['product_id']);
				$total_net_amt_gls = $net_pouches_p['total_amt'];
				$gls_no_of_package=$details['no_of_packages'];
			    $gls_identification_marks=$details['identification_marks'];
				$gls_no = '1';
				$gls_series = $abcd;
				if($first=='false')
				{
				    $air_f = 8;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='72')
			{
				$tot_qty_chair=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_chair_qty = $tot_chair_qty + $tot_qty_chair['total']; 
				$tot_chair_rate = $tot_chair_rate + $tot_qty_chair['rate'];
				$total_amt_chair = $total_amt_chair + $tot_qty_chair['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_chair = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_chair = $net_pouches_p['g_wt'];
				$chair_box_no = $net_pouches_p['total_box'];
				$chair_box_name = 'CHAIR';
				//end
				$total_net_amt_gls = $net_pouches_p['total_amt'];
				$chair_gst_details=$this->getProductGST($details['product_id']);
				$chair_no_of_package=$details['no_of_packages'];
			    $chair_identification_marks=$details['identification_marks'];
				$chair_no = '1';
				$chair_series = 'B';
				if($first=='false')
				{
				    $air_f = 10;
				     $first = 'true';
				}
			}
				else if($details['product_id']=='37')
			{
				$tot_qty_oxygen_absorbers=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_oxygen_absorbers_qty = $tot_oxygen_absorbers_qty + $tot_qty_oxygen_absorbers['total']; 
				$tot_oxygen_absorbers_rate = $tot_oxygen_absorbers_rate + $tot_qty_oxygen_absorbers['rate'];
				$total_amt_oxygen_absorbers = $total_amt_oxygen_absorbers + $tot_qty_oxygen_absorbers['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_oxygen_absorbers = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_oxygen_absorbers = $net_pouches_p['g_wt'];
				$oxygen_absorbers_box_no = $net_pouches_p['total_box'];
				$oxygen_absorbers_box_name = 'Oxygen absorbers';
				//end
			     $oxygen_gst_details=$this->getProductGST($details['product_id']);
				$total_net_amt_oxygen_absorbers = $net_pouches_p['total_amt'];
				$oxygen_absorbers_no_of_package=$details['no_of_packages'];
			    $oxygen_absorbers_identification_marks=$details['identification_marks'];
				$oxygen_absorbers_no = '1';
				$oxygen_absorbers_series = 'B';
		//	  printr($details);
			 //  printr($oxygen_absorbers_no_of_package);
			
				if($first=='false')
				{
				    $air_f = 10;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='38')
			{
				$tot_qty_silica_gel=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_silica_gel_qty = $tot_silica_gel_qty + $tot_qty_silica_gel['total']; 
				$tot_silica_gel_rate = $tot_silica_gel_rate + $tot_qty_silica_gel['rate'];
				$total_amt_silica_gel = $total_amt_silica_gel + $tot_qty_silica_gel['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_silica_gel = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_silica_gel = $net_pouches_p['g_wt'];
				$silica_gel_box_no = $net_pouches_p['total_box'];
				$silica_gel_box_name = 'Silica Gel';
				//end 
				$total_net_amt_silica_gel = $net_pouches_p['total_amt'];
			    $silica_gst_details=$this->getProductGST($details['product_id']);
				$silica_gel_no_of_package=$details['no_of_packages'];
			    $silica_gel_identification_marks=$details['identification_marks'];
				$silica_gel_no = '1';
				$silica_gel_series = 'B';
				if($first=='false')
				{
				    $air_f = 10;
				     $first = 'true';
				}
			}
			
			else if($details['product_id']=='63')
			{
				$tot_qty_val=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice['invoice_id'],$invoice['sales_invoice_id']);
				$tot_val_qty = $tot_val_qty + $tot_qty_val['total']; 
				$tot_val_rate = $tot_val_rate + $tot_qty_val['rate'];
				$total_amt_valve = $total_amt_valve + $tot_qty_val['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice['invoice_id'],$details['product_id'],0,$invoice_no);
				$total_net_w_val = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_val = $net_pouches_p['g_wt'];
				$val_box_no = $net_pouches_p['total_box'];
				$val_box_name = 'PLASTIC CAP';
				//end
			//	printr($details);
				$val_gst_details=$this->getProductGST($details['product_id']);
				$total_net_amt_val = $net_pouches_p['total_amt'];
				$val_no_of_package=$details['no_of_packages'];
			    $val_identification_marks=$details['identification_marks'];
				$val_no = '1';
				$val_series = $abcd;
				if($first=='false')
				{
				    $air_f = 9;
				     $first = 'true';
				}
			}
			else if($details['product_id']!='11' && $details['product_id']!='6' && $details['product_id']!='10' && $details['product_id']!='23' && $details['product_id']!='18' && $details['product_id']!='34'&& $details['product_id']!='47'&& $details['product_id']!='48'&& $details['product_id']!='72'&& $details['product_id']!='63'&& $details['product_id']!='37'&& $details['product_id']!='38')
			{
				$net_pouches_pouch = $this->getIngenBox($invoice['invoice_id'],2,0,$invoice_no);
				$total_net_w_pouch =$net_pouches_pouch['n_wt'];
				$total_net_amt_pouch = $net_pouches_pouch['total_amt'];
				//sonu add 15-5-2017
				$total_gross_w_p = $net_pouches_pouch['g_wt'];
				$pouch_box_no = $net_pouches_pouch['total_box'];
				$pouch_name = 'POUCHES';	
				$group_pouch_id = $net_pouches_pouch['group_id'];
				$pouch_no_of_package=$details['no_of_packages'];
			    $pouch_identification_marks=$details['identification_marks'];
			//	printr($total_net_amt_pouch);
				//sonu end	
			    
				 $pouch_gst_details=$this->getProductGST(3);//add pouch product id
				if($first=='false')
				{
				    $air_f = 0;
				     $first = 'true';
				}
			}
			$abcd++;
		
		
			
		}
		 
		 
        	 $time_s=new DateTime($invoice['date_added']);
            $t=$time_s->format('H:i:s');
            if($t!='00:00:00'){
              $time= '&nbsp;'.$t;
            }else{
               $time='';
            }


             $state_text='';
            if($invoice['state_id']!=0){
               $state_details=$this->getIndiaStateDetails($invoice['state_id']); 
               $state_text='State: '.$state_details['state'].' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    State Code: '.$state_details['state_code_in_no'];
            }
        

		  $total_identification_marks=	$total_net_w_scoop+$total_net_w_roll+$total_net_w_p+ $total_net_w_m + $total_net_w_s +$total_net_w_str +$total_net_w_pouch +$total_net_w_con +$total_net_w_gls+$total_net_w_chair+$total_net_w_oxygen_absorbers+$total_net_w_silica_gel +$total_net_w_val;
	      $total_no_of_package=$scoop_box_no + $roll_box_no + $mailer_box_no + $sealer_box_no+$storezo_box_no +$paper_box_no+ $pouch_box_no+ $con_box_no+ $gls_box_no+ $oxygen_absorbers_box_no+ $silica_gel_box_no+ $chair_box_no+ $val_box_no;
	     $no_of_package='BOXES';
	    $identification_marks='KGS';
		
	
		$totgross_weight=$box_detail['total_net_weight']+$box_detail['total_box_weight']+$box_det['total_net_weight']; 
	//	printr($box_detail['total_net_weight'].'+'.$box_detail['total_box_weight'].'+'.$box_det['total_net_weight']);
		$taxation=$invoice['taxation'];
		  
		if($invoice['added_user_type_id'] == 2){
			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$invoice['added_user_id']."' ");
			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
			$set_user_id = $parentdata->row['user_id'];
			$set_user_type_id = $parentdata->row['user_type_id'];
		}else{
			$userEmployee = $this->getUserEmployeeIds($invoice['added_user_type_id'],$invoice['added_user_id']);
			$set_user_id = $invoice['added_user_id'];
			$set_user_type_id = $invoice['added_user_type_id'];
		}
		/*if($_SESSION['ADMIN_LOGIN_SWISS']=='1')
		{
		    //printr($invoice['added_user_type_id']);
		}*/
		$data_logo=$this->query("SELECT logo,abn_no,termsandconditions_invoice,note_invoice FROM international_branch WHERE international_branch_id = '".$set_user_id."'"); 
//	$width='';
	  
		if(isset($data_logo->row['logo']) &&  $data_logo->row['logo']!= '') 
		{
			$image= HTTP_UPLOAD."admin/logo/200_".$data_logo->row['logo'];
            if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39'))
			    $img = '<img src="'.$image.'" alt="Image" width="100%" height="7%" id="oxi_img" class="oxi_img" />';//printr($data_logo);//printr($image);//
            else
                $img = '<img src="'. HTTP_SERVER.'admin/controller/government_sales_invoice/invoice_logo.png" width="70%" />';
		}
		else
		   $img = '<img src="'. HTTP_SERVER.'admin/controller/government_sales_invoice/invoice_logo.png" width="70%" />';
		   
	    $currency=$this->getCurrencyName($invoice['currency']);
		$html='';
		   $font="";
		    if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39'))
                { 
                     $font="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;";
                }
		if($list==0) 
    	{ 
    	       
    	    if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39'))
        	    $html.='<div class="panel-body" id="print_div" style="font-family: Calibri;padding-top: 0px;width:754px font-size=10Px">';
               	  else
          		$html.='<div class="panel-body print_div123" id="print_div" style="padding-top: 0px;width:754px font-size=10Px">';
          		
      		$html.='<div class="">
    					 <div class="form-group ">';
          	$fixdata = $this->getFixmaster(); 
          
          	   $copy = 'Original For Buyer <br>Duplicate For Transporter<br>Triplicate for Assesse';
            
          	   
                if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39'))// vivekbhai's Change
                {
                    $html.=' 
                        <table style="cellpadding:0px;cellspacing:0px;" class="table_tag" ><tr> 
                                    <td>
                                        '.$img.'
                                    </td>
                                    <td width="30%" valign="top" style="'.$font.'"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Original For Buyer <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Duplicate For Transporter<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Triplicate for Assesse</td>
                                </tr>';
                }else{
                     $html.=' 
                            <table class="table_tag"><tr> 
                                        <td>
                                            '.$img.'
                                        </td>
                                        <td width="30%" valign="top"  >'.$copy.'</td>
                                    </tr>';
                    
                }
                                if(($invoice['transport'] == 'air' || $invoice['transport'] == 'road') && ($invoice['invoice_status'] == '0')){
                                         $transport_line ='LUT WITHOUT PAYMENT OF IGST';
                                         
                                         if($invoice['invoice_date']>='2019-04-01'){
                                              $value='LETTER OF UNDERTAKING APPLICATION REFERENCE NUMBER-AA2403180293296';
                                         }else{
                                              $value='LETTER OF UNDERTAKING APPLICATION REFERENCE NUMBER-AD240319004221P(ARN)';
                                             }
                                         
                                           if($invoice['transport'] == 'air'  && $invoice['igst_status'] == 1 ){
                                				 $transport_line ='EXPORT AGAINST PAYMENT OF IGST';
                                				 $value='';
                            				}
                                          
                                          
                                   }else{
                                             $transport_line ='EXPORT AGAINST PAYMENT OF IGST';
                                           
                                           if($invoice['invoice_date']>'2018-10-21'){
    							                 $value='THIS SHIPMENT IS TAKEN UNDER THE EPCG LICENCE LICENCE NO.3430003005 DATED 23.01.2017';
    							             }
                                           else  if($invoice['invoice_date']>'2018-07-06'){
    								                $value='THIS SHIPMENT IS TAKEN UNDER THE EPCG LICENCE LICENCE NO.3430002776 DATED 01.12.2015';
    								            }
    							             
                             }
            $html.='  
           <tr>';
             if($invoice['invoice_status']!='1' && $invoice['invoice_status']!='2'){ 
                    $html.=' <td  align="left" width="60%"><b>'.$transport_line.'</b></td>';
                     $html.='<td align="left"  width="50%"><b> TAX INVOICE</b></td>';
            }else{
                
                if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39'))
                {  
                    $html.='<td  width="60%"><br><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> TAX INVOICE</b></center><br></td>';
                }else{
                    $html.='<td  width="60%"><center> <b> TAX INVOICE</b></center></td>';
                }
                 
                  $html.='<td align="left" width="40%"></td>';
             }
            $html.='</tr></table> ';
            $challan = $mark= $mark_value = $td= $mark_td = $colspan='';$border=0;
            if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's Change
            {
                $row=count($alldetails);
                
                $num_data = '<span style="font-size: 14px;text-align: left;style="">E 201, Akshar Paradise, Behind Narayanwadi,<br>Atladara,Vadodara-390012, State: Gujarat, India<br>Mob : +91 8140128717, +91 9687659456
                            <br>Email: info@oxymist.co.in,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;website: www.oxymist.co.in</span><br>
                            <b style="font-size: 13px;" >GSTIN No :</b> <span style="font-size: 13px;font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;""> 24AAEFO8974D1Z3 </span> <br>
                            <b style="font-size: 13px;" >STATE CODE: </b> <span style="font-size: 13px;"> 24</span><br>';
                /*$num_data = '<a style="font-size: 15px;"><center>E 201, Akshar Paradise, Behind Narayanwadi Restaurant,<br>Atladara,Vadodara-390012, State: Gujarat, India<br>Mob : +91 8140128717, +91 9687659456
                            <br>Email : info@oxymist.co.in,&nbsp;&nbsp;&nbsp;&nbsp;Website: www.oxymist.co.in</center></a><br>
                            <a style="font-size: 13px;"><b>GSTIN No :</b>24AAEFO8974D1Z3 </a> <br>
                            <a style="font-size: 13px;"><b>STATE CODE: </b> 24</a><br>';*/
                            
                            $style_oxi ='font-size: 12px;';
                       
                if($_SESSION['ADMIN_LOGIN_SWISS']=='1' || (isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')))
            	{
                    $padding = 12/$row;//first here we set 20
                    $style_oxi ='vertical-align: top;font-size: 13px; padding-bottom:'.$padding.'%;';
            	}
                $desc ='';
                $table_tr ='<tr ><td style=""><strong><u>Declaration:</u></strong></td></tr>
                            <tr ><td style="">We declare that this Invoice shows the actual price of the</td></tr>
                            <tr ><td style="">Subject to Vadodara Jurisdiction E. & O. E.</td></tr>
                            <tr><td style="">Our responsibility ceases as soon as goods leaves our premises</td></tr>
                            <tr><td style="border-bottom: 1px solid #333;">Goods once sold will not bt accepted back.</td></tr>';
                $bank_detail =$this->query("SELECT b.* FROM " . DB_PREFIX . "proforma_product_code_wise as p,bank_detail as b WHERE b.bank_detail_id=17 AND p.proforma_id = '" .(int)$invoice['invoice_id']. "'");
                //printr($bank_detail);
                $table_desc ='<tr><td style="border-bottom: 1px solid #333;"><strong>OUR BANK DETAILS</strong></td><td style="border-bottom: 1px solid #333;"></td></tr>
                              <tr><td style=""><b>Beneficiary Name : </b>'.$bank_detail->row['bank_accnt'].'</td><td style="font-size: 12px;"><strong>For, OXY-MIST ABSORBERS</strong></td></tr>
                              <tr><td style=""><b>Bank Name : </b>'.$bank_detail->row['benefry_bank_name'].'</td><td></td></tr>
                              <tr><td style=""><b>Account No : </b>'.$bank_detail->row['accnt_no'].'</td><td></td></tr>
                              <tr><td style=""><b>Bank Address : </b>'.$bank_detail->row['benefry_bank_add'].'</td><td></td></tr>
                              <tr><td style=""><b>IFSC Code : </b>'.$bank_detail->row['swift_cd_hsbc'].'</td><td></td></tr>';
                $colspan = 'colspan="2"';
               
            }
            else
            {
                
                $style_oxi ='';
            
                $num_data = '<b>GSTIN No :</b> 24AADCS2724B1ZY <br>
                             <b>PAN No  :</b> AADCS2724B<br>
                             <b>RC No.  :</b> 051/AR VI/PDR/DIV.II/BRD<br> 
                             <b>CIN No. :</b> U36998GJ1992PTC018408<br>';
                $challan = '<tr><td style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;" ><b>CHALLAN NO. </b> '.$invoice['challan_no'].'</td><td style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;"  ><b>DATE</b> '.date("d-m-Y",strtotime($invoice['challan_date'])).'</td></tr>';
                $desc ='Description Manufacturing of Printed Polyester Roll,Pouch/Scrap,Waste,others<br>HSN Code No: 39232990,39201012,39239090,39235090,39232100,39231090';
                $mark = '<th>IDENTIFICATION MARKS</th>';
                $proforma_kgs="'#proforma_kgs'";
                if($pdf!=0)
                    $mark_value='<td class="no_border" align="center" >'.$alldetails[0]['identification_marks'].''.$identification_marks.'</td>';
                else
                    $mark_value='<td class="no_border"  ><input type="text" name="proforma_kgs" onchange="change_qty_per_kg('.$invoice_no.',1,'.$proforma_kgs.','.$invoice['invoice_status'].')" value="'.$alldetails[0]['identification_marks'].''.$identification_marks.'"   id="proforma_kgs"></td>';
                
                $td ='<td class="no_border" ></td>';
                 if($invoice['transport'] == 'air'  && $invoice['igst_status'] == 1 ){
                   $invoice['tran_desc']='';  
                }
             //   printr($alldetails);
                $mark_td= '<td  align="center" >'.$alldetails[0]['identification_marks'].''.$identification_marks.'</td>';
                $table_tr ='<tr><td><strong><u>'.$invoice['tran_desc'].'</u></strong></td></tr>
                 			    <tr><td><strong><u>'.$invoice['remark'].'</u></strong></td></tr>
                 			    <tr><td><b>DISPATCH</b>:'.$invoice['despatch'].'<br><b>LR NO./DT.</b>:'.$invoice['lr_no'].'<br><b>VEHICLE NO.</b>:'.$invoice['vehicle_no'].'</td></tr>';
                $table_desc = '<tr style="font-size: 12px;"><td>
                 		        Cerfified that particulars given are true & correct & the amount indicated represents<br> the price actually charges & that thereis no flow additional consideration
                 		        directly or <br>indirectly from the buyer OR certified that the particulars guveb above are true and <br>correct and the amount indicated is provisionals additional
                 		        consideration will be received<br> from the buyer of account of..<br>
                 		        <b>Subject to VADODARA Jurisdiction'.str_repeat('&nbsp;', 35).'E.&.O.E</b>
                 		        
                 		        </td>
                 		        <td>
                             		<p><strong>For : SWISS PAC PVT . LTD.</strong></p><br><br>
                             		    <strong>SIGNATURE OF THE REGISTRED<br>PERSON OR HIS AUTHORISED AGENT</strong>
                 		        </td>
                 		     </tr>';
                $border=1;
            }
            $width_table='';
            if($pdf!=0)
                $width_table='width: 100%; ';

          	  if(($invoice['taxation']=='Out Of Gujarat' ) || (($invoice['invoice_status']==0 && $invoice['transport'] == 'sea')||($invoice['transport'] == 'air' && $invoice['igst_status']==1) ) )	
            	   $td_colspan='3';
			   else if($invoice['taxation']=='With in Gujarat')
			        $td_colspan='5';
			   else if ($invoice['invoice_status']==0 && $invoice['transport'] == 'air')
			        $td_colspan='0';
		    	 else
			       $td_colspan='1';
			   
        				  
            
           $html.='<table style="cellpadding:0px;cellspacing:0px;font-size: 10px;'.$width_table.'" border="1" cellpadding="0" cellspacing="0" >
    	   	 
    
          	     <tr style="font-size: 12px;">
          	       		<td colspan="2"  style="vertical-align: top; padding: 3px;">'.$num_data.'</td>
                   		<td colspan="'. ($td_colspan+6).'"  style="vertical-align: top; padding: 3px;">
                   		<table style=" width: 100%; ">';
    					 $html.='<tr><td width="50%" style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;" ><b >INVOICE NO. : </b> '.$invoice['invoice_no'].'</td><td  width="50%" style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;"><b >DATE :</b> '.date("d-m-Y",strtotime($invoice['invoice_date'])).'</td></tr>';
    					 $html.=$challan;
    					 $html.='<tr><td style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;">';if($invoice['invoice_status']!='1'){
    					 	 $html.='<b style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;">EXP INVOICE NO. : </b> '.$invoice['exp_inv_no'].'</td>';}
    					  $html.='<td></td></tr>';
    					 //$html.='<tr><td style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;"><b style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;">ORDER NO. :  </b> </td><td  style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;"><b >DATE :</b></td></tr>';
    					 $html.='<tr><td style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;"><b style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;"></td><td  style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;"><b >TIME :</b> '.$time.'</td></tr>';
    					 if($invoice['buyers_orderno']!=''){
    					    	 $html.='<tr><td style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;font-size: 14px;"><b style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;">BUYERS ORDER NO: </b>'.$invoice['buyers_orderno'].'</td><td style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;"><b >DATE: </b>'.date("d-m-Y",strtotime($invoice['buyers_order_date'])).'</td></tr>'; 
    					 }
    					 
    					 $html.='<tr><td  width="60%" style="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;font-size: 14px;"><b >PAYMENT : </b>Immediate </td><td width="40%"></td></tr>';
    				$html.='</table></td></tr>';
                
    		//if($status==1){
    		if($invoice['invoice_status']=='0' ){
               			    //invoice
                 $html .= ' <tr>
               			        <td colspan="2" style="vertical-align: top;font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;"><strong> NAME & ADDRESS OF CONSIGNEE:</strong></td>
               			        <td colspan="'. ($td_colspan+6).'" style="vertical-align: top;font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;"><strong> NAME & ADDRESS OF BUYER OTHER THAN CONSIGNEE:</strong></td>
                        	</tr>';
                      $gstno='';  
                      	  // 	$customer_name='<b>'.$invoice['customer_name'].'</b>';
                      	  	$customer_name='';
                      	  	$invoice['other_buyer']= $invoice['other_buyer'];
                      	  
              }else{ 
                  //proforma
                      $pro_detail =$this->query("SELECT p.contact_no,b.* FROM " . DB_PREFIX . "proforma_product_code_wise as p,bank_detail as b WHERE b.bank_detail_id=17 AND p.proforma_id = '" .(int)$invoice['invoice_id']. "'");
                        //printr($pro_detail);
                  
              //    printr($invoice['invoice_status']);
               
                        if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's Change
                        {
                               	$customer_name='<b style=" font-weight: 900;font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;">'.$invoice['customer_name'].'</b><br>';
                                     $html .= '<tr>
                                   			        <th colspan="2" style="vertical-align: top;font-size:12px;text-align:center;'.$font.'"><strong> BILLING ADDRESS<br></strong></th>
                                   			        <th colspan="'. ($td_colspan+6).'" style="vertical-align: top;font-size:12px;text-align:center;'.$font.'"><strong> DELIVERY ADDRESS<br></strong></th>
                                            	</tr>';
                                                 $gstno='<br><b style=" font-weight: 900;font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;">GSTIN No :  </b>'.$invoice['gst_no'].'</b>';
                                       
                                                 $invoice['other_buyer']= nl2br($invoice['other_buyer']);  
                                              //   $font="font-family:CALIBRIB,Calibri, DejaVu Sans, sans-serif;";
                        }else{ 
                               	$customer_name='<b>'.$invoice['customer_name'].'</b><br>';
                                     $html .= '<tr>
                                   			        <td colspan="2" style="vertical-align: top;"><strong> BILLING ADDRESS:</strong></td>
                                   			        <td colspan="'. ($td_colspan+6).'" style="vertical-align: top;"><strong> DELIVERY ADDRESS:</strong></td>
                                            	</tr>';
                                                 $gstno='<br><b>GSTIN NO :</b>'.$invoice['gst_no'].'</b>';
                                       
                                                 $invoice['other_buyer']= nl2br($invoice['other_buyer']);
                        }             
                    } 
                
                   
                    if($invoice['same_as_above']!='1'){
                         
                        $invoice['other_buyer']=$invoice['other_buyer'];
                    }else{
                       $invoice['other_buyer']="";
                    }
                    //made this cond. by [kinjal] on 5-9-2018
                    /*if($pdf!=0)
                        $invoice['consignee'] = utf8_encode($invoice['consignee']);
                    else*/
                        $invoice['consignee'] = $invoice['consignee'];
                        
           		 $contact_no='';
                    if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's Change
                        {
                            $html .='<tr style="font-size: 14px;">
              			 <td colspan="2" style="vertical-align: top;'.$font.'">'.$customer_name.''.nl2br($invoice['consignee']).'<br>'.$state_text.'<br>Contact No. : '.$pro_detail->row['contact_no'].'<br>'.$gstno.'</td>
              			 <td colspan="'. ($td_colspan+6).'" style="vertical-align: top;'.$font.'" >'.$invoice['other_buyer'].'</td>
           		  </tr>';
                        }else{
                           // echo 'hii';
                         //  printr($pro_inv_data); 
                           	if($invoice['invoice_status']=='1'){
                               	    if($pro_inv_data['contact_no']!=0){
                                      $contact_no='<br>Contact No. : '.$pro_inv_data['contact_no'].'';
                               	    }
                            } 
                            $html .='<tr style="font-size: 11px;">
              			 <td colspan="2" style="vertical-align: top;'.$font.'">'.$customer_name.''.nl2br($invoice['consignee']).'<br>'.$state_text.' <br>'.$contact_no.'<br>'.$gstno.'</td>
              			 <td colspan="'. ($td_colspan+6).'" style="vertical-align: top;'.$font.'" >'.$invoice['other_buyer'].'</td>
           		  </tr>';
                            
                        }
           		  
           		  $html .='<tr>
              			 <td colspan="'. ($td_colspan+8).'"   style="vertical-align: top;'.$font.'">'.$desc.'</td>
              			 
           		  </tr>';
    			
    		
    		 $currency=$this->getCurrencyName($invoice['currency']);
    		 $total_no_of_box=$this->getTotalBox($invoice['invoice_id']);
    		 $child_net = 0;
    		 //$measurement=$this->getMeasurementName($invoice['measurement']);
    	
    		 
             	$html.='<tr style="vertical-align: top;" style="font-size: 12px;">';   
    		 	//printr($currency);
    				 
                    	if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's Change
                { 
        			$html.='<th colspan="3" style="vertical-align: middle !important;'.$font.'"><center>DESCRIPTION OF GOODS</center></th>
        					 <th '.$colspan.' style="vertical-align: middle !important;'.$font.'"><center>NO OF<br> PACKAGES</center></th>
        					 '.$mark.'
        					 <th align="center" style="'.$font.'"><center>QUANTITY<br />(In Pcs.)</center></th>
        					 <th align="center" style="'.$font.'"><center>RATE</center></th>
        					 <th >TAXABLE VALUE</th>';
        				  if($invoice['taxation']=='Out Of Gujarat'){
        				       $html.='<th colspan="2">IGST</th>';
        				   }else if($invoice['taxation']=='With in Gujarat'){
        				        $html.='<th colspan="2">CGST</th>';
        				        $html.='<th colspan="2">SGST</th>'; 
        				   }	 
        					 	 
        				$html.='<th align="center" style="'.$font.'"><center>AMOUNT INR<br /> <img src="https://swissonline.in/upload/admin/u20B9.png" alt="Image" width="15px" /></center></th>';//<img src="https://swissonline.in/upload/admin/u20B9.png" alt="Image" width="65%"><span style="font-family: calibri, DejaVu Sans, sans-serif;">₹</span>
                }else{
                         
        			$html.='<th colspan="3">DESCRIPTION OF GOODS</th>
        					 <th '.$colspan.'>NO OF PACKAGES</th>
        					 '.$mark.'
        					 <th>QUANTITY</th>
        					 <th>RATE</th>';
        					 
        					 //printr($invoice['transport'].'=========='.$invoice['igst_status'].'i_status    '.$invoice['invoice_status']);
        				 if(((($invoice['transport'] == 'air' && $invoice['igst_status']==1) || ($invoice['transport'] == 'sea' && $invoice['igst_status']==0)) || $invoice['invoice_status']!=0 )){
        					$html.=' <th>TAXABLE VALUE</th>';
        					 if(($invoice['taxation']=='Out Of Gujarat' ) || (($invoice['invoice_status']==0 && $invoice['transport'] == 'sea')||($invoice['transport'] == 'air' && $invoice['igst_status']==1) ) ){
        		
        				       $html.='<th colspan="2">IGST</th>';
        				   }else if($invoice['taxation']=='With in Gujarat'){
        				        $html.='<th colspan="2">CGST</th>';
        				        $html.='<th colspan="2">SGST</th>';
        				   }
        				   
        				 }
        				 $html.='<th>AMOUNT <br />INR <span style="font-family: DejaVu Sans; sans-serif;">&#x20B9;</span></th>';
        		//	 printr($invoice['transport']);
        			       
        				  
        		 	    
        					 
                    
                    
                }
               
    
                     
    				 $html.='</tr>';
    				 $html.='<tr>';
    			//	 printr($invoice['taxation']);
    		    	 if(($invoice['taxation']!='' )){	
    				  	 	  if((($invoice['transport'] == 'air' && $invoice['igst_status']==1) || ($invoice['transport'] == 'sea' && $invoice['igst_status']==0)) || $invoice['invoice_status']!=0 )
                                $html.=' <th colspan="8"></th>';
                             else
                                $html.=' <th colspan="7"></th>';
    				  	 	 if(($invoice['taxation']=='Out Of Gujarat' ) || (($invoice['invoice_status']==0 && $invoice['transport'] == 'sea')||($invoice['transport'] == 'air' && $invoice['igst_status']==1) ) )	{
        				           $html.='<th >RATE(%)</th>';
        				           $html.='<th >AMOUNT</th>';
            				   }else if($invoice['taxation']=='With in Gujarat'){
            				           $html.='<th >RATE(%)</th>';
            				           $html.='<th >AMOUNT</th>';
            				           $html.='<th >RATE(%)</th>';
            				           $html.='<th >AMOUNT</th>';
            				   }
    				  	 	  $html.=' <th ></th>';
    			 }else{
    			     $html.=' <th colspan="8"></th>';
    			 }
    			        
    			         	  /*if(($invoice['taxation']=='Out Of Gujarat' ) || (($invoice['invoice_status']==0 && $invoice['transport'] == 'sea')||($invoice['transport'] == 'air' && $invoice['igst_status']==1) ) )	{
        				           $html.='<th >RATE</th>';
        				           $html.='<th >AMOUNT</th>';
        				   }else if($invoice['taxation']=='With in Gujarat'){
        				           $html.='<th >RATE</th>';
        				           $html.='<th >AMOUNT</th>';
        				           $html.='<th >RATE</th>';
        				           $html.='<th >AMOUNT</th>';
        				   }*/
        				    
    				 $html.='</tr>';
    				
    				// added by sonu 21-05-2018
    				   
    				
    				
    				    
    				
    				 
    				 if($box_det['total_net_weight']!='')
    				 	$child_net= $box_det['total_net_weight'];
    				
    				//printr($box_detail);
    		}	
		//kinjal end ==0	
		
		if($list == '1' || $list == '0') 
		{	 $igst=$cgst=$sgst=0;
        	if($invoice['invoice_status']!='1' && $invoice['invoice_status']!='2'){	
        	    
        	    //invoice
                                             
                                     		 			if($pouch_name=='POUCHES'){
                                						
                                						  
                                						       $cond_net_weight = number_format($box_detail['total_net_weight']+$child_net,3);
                                						      	 
                                						        
                                     		 		        
                            						       }
                            						       
                            						       	if($air_f=='0'){
                                								    		$total_amt_val=$invoice_qty['tot'];
                                            							 if($invoice_inv_data['invoice_date']<'2018-03-18'){
                                            							            	$rate_per = number_format((($total_amt_val-($invoice['tran_charges']+$roll_price+$total_amt_paper+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel+$total_amt_valve))/$total_qty_val),8);
                                            					                         $amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_paper+$roll_price+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel+$total_amt_valve));
                                            							    }else{
                                            							                	$rate_per = number_format((($total_amt_val-($invoice['tran_charges']+$total_amt_roll+$total_amt_paper+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel+$total_amt_valve))/$total_qty_val),8);
                                            					                             $amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_oxygen_absorbers+$total_amt_silica_gel+$total_amt_chair+$total_amt_valve));
                                            					             }
                                         
                                                                        	if($invoice_inv_data['invoice_date']<'2018-10-12'){	
                                                    						    if($invoice_inv_data['invoice_id']!='1899'){
                                                    								if($invoice_inv_data['country_destination']=='172'  || $invoice_inv_data['country_destination']=='253')//|| $invoice_inv_data['country_destination']=='125'
                                                    								{   
                                                    								    $rate_per = number_format((($total_amt_val-($total_amt_paper+$roll_price+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_oxygen_absorbers+$total_amt_silica_gel+$total_amt_chair+$total_amt_valve))/$total_qty_val),8);
                                                    								    $amt = ($total_amt_val-($total_amt_paper+$roll_price+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel+$total_amt_valve));
                                                    								}
                                                    								
                                                    						    }
                                                                        	} 
                                								      
                                							     //   printr($total_amt_scoop);
                                								     	if($amt<$invoice['tran_charges']){
                                                                                if($air_f==0 && $total_amt_scoop>$invoice['tran_charges']){
                                                                                    $air_f=5;
                                                                                }
                                                                                else if($total_amt_roll>$invoice['tran_charges']){
                                                                                    $air_f=2; 
                                                                                }
                                                                                else{
                                                                                     $air_f=1;
                                                                                }
                                                                         }  
    								
                                                                     
                                                                     
                                								
                                								    
                                								}
                                								 
                                								  
                            						       	if($invoice['invoice_date']>'2019-09-06')
                                                                {
                                                                    // add by sonu 06-09-2019
                                                                    $air_f=$invoice_inv_data['air_f_status'];
                                                                }
                                								
                                								
                                								
                                							//	printr($total_amt_val);
                            						       
                            						       
                            						        
                            								if($scoop_no == '1')
                            								{	
                            									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
                            									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_oxygen_absorbers_rate+$tot_silica_gel_rate+$tot_chair_rate );
                            									$total_amt_val=$invoice_qty['tot'];
                            									if($air_f=='1')
                            									       $total_amt_scoop = ($total_amt_scoop - $invoice['tran_charges']);
                            								}
                            								if($roll_no == '1')
                            								{ 
                            									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
                            									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate+$tot_oxygen_absorbers_rate+$tot_silica_gel_rate+$tot_chair_rate );
                            								//	printr($total_amt_roll.'pppppp');
                            								//	printr($invoice['cylinder_charges'].'pppppp56666');
                            								//	printr($total_amt_roll - $invoice['tran_charges']);
                            						
                            									
                            									$total_amt_val=($invoice_qty['tot']-$roll_price)+$total_amt_roll;
                            									if($air_f=='2')
                            									       $total_amt_roll = ($total_amt_roll - $invoice['tran_charges']);
                            									       
                            								//	printr($total_amt_roll.'qbwibgwierg'); 
                            								}
                            								if($mailer_no == '1')
                            								{
                            									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
                            									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate+$tot_oxygen_absorbers_rate+$tot_silica_gel_rate+$tot_chair_rate );
                            									//$total_rate_val=$tot_mailer_rate;
                            									$total_amt_val=$invoice_qty['tot'];
                            									if($air_f=='3')
                            									       $total_amt_mailer = ($total_amt_mailer - $invoice['tran_charges']);
                            								}
                            								if($sealer_no == '1')
                            								{
                            									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
                            									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate+$tot_oxygen_absorbers_rate+$tot_silica_gel_rate+$tot_chair_rate );
                            									//$total_rate_val=$tot_sealer_rate;
                            									$total_amt_val=$invoice_qty['tot'];
                            									if($air_f=='4')
                            									       $total_amt_sealer = ($total_amt_sealer - $invoice['tran_charges']);
                            								}
                            								if($storezo_no == '1')
                            								{
                            									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
                            									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate+$tot_chair_rate+$tot_oxygen_absorbers_rate+$tot_silica_gel_rate );
                            									//$total_rate_val=$tot_storezo_rate;
                            									
                            									    $total_amt_val=$invoice_qty['tot'];
                            									    if($air_f=='5')
                            									        $total_amt_storezo = ($total_amt_storezo - $invoice['tran_charges']);
                            									
                            								}
                            								if($paper_box_no == '1')
                            								{
                            									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
                            									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_chair_rate +$tot_oxygen_absorbers_rate +$tot_silica_gel_rate );
                            								
                            									$total_amt_val=$invoice_qty['tot'];
                            									if($air_f=='6')
                            									        $total_amt_paper = ($total_amt_paper - $invoice['tran_charges']);
                            									
                            								}
                            								if($con_no == '1')
        														{
        															$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
        															$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_chair_rate+$tot_oxygen_absorbers_rate+$tot_silica_gel_rate);
        															//$total_rate_val=$tot_paper_rate;
        															$total_amt_val=$invoice_qty['tot'];
        															if($air_f=='7')
        															        $total_amt_con = ($total_amt_con - $invoice['tran_charges']);
        															
        														}
        														if($gls_no == '1')
        														{
        															$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
        															$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_chair_rate+$tot_oxygen_absorbers_rate+$tot_silica_gel_rate);
        															//$total_rate_val=$tot_paper_rate;
        															$total_amt_val=$invoice_qty['tot'];
        															if($air_f=='8')
        															        $total_amt_gls = ($total_amt_gls - $invoice['tran_charges']);
        															
        														}
        														if($chair_no == '1')
        														{
        															$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
        															$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_oxygen_absorbers_rate+$tot_val_rate+$tot_silica_gel_rate);
        															//$total_rate_val=$tot_paper_rate;
        															$total_amt_val=$invoice_qty['tot'];
        															if($air_f=='8')
        															        $total_amt_chair = ($total_amt_chair - $invoice['tran_charges']);
        														}
        														if($oxygen_absorbers_no == '1')
        														{
        															$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_silica_gel_qty);
        															$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_silica_gel_rate);
        															//$total_rate_val=$tot_paper_rate;
        															$total_amt_val=$invoice_qty['tot'];
        															if($air_f=='11')
        															        $total_amt_oxygen_absorbers = ($total_amt_oxygen_absorbers - $invoice['tran_charges']);
        														}
        														if($silica_gel_no == '1')
        														{
        															$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_oxygen_absorbers_gel_qty);
        															$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_oxygen_absorbers_rate);
        															//$total_rate_val=$tot_paper_rate;
        															$total_amt_val=$invoice_qty['tot'];
        															if($air_f=='12')
        															        $total_amt_silica_gel = ($total_amt_silica_gel - $invoice['tran_charges']);
        														}
        													
        														if($val_no == '1')
        														{
        															$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
        															$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_oxygen_absorbers_rate+$tot_silica_gel_rate+$tot_chair_rate);
        															//$total_rate_val=$tot_paper_rate;
        															$total_amt_val=$invoice_qty['tot'];
        															if($air_f=='9')
        															        $total_amt_valve = ($total_amt_valve - $invoice['tran_charges']);
        															
        														}
                            								else
                            								{
                            								  	if($pouch_name=='POUCHES'){
                                									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_chair_qty+$tot_oxygen_absorbers_qty+$tot_silica_gel_qty);
                                									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate+$tot_con_rate+$tot_gls_rate+$tot_val_rate +$tot_oxygen_absorbers_rate+$tot_silica_gel_rate+$tot_chair_rate );
                                								    //printr($invoice_qty['tot']);
                        							            	//$total_rate_val=$invoice_qty['rate'];
                        								        	//$total_amt_val=$total_qty_val*$invoice_qty['rate'];
                            								         if($roll_no == '1')
                                								  	        $total_amt_val=$total_amt_val;
                                								  	     else
                                								  	        $total_amt_val=$invoice_qty['tot'];
                                								  	}else{
                                								  	    $total_qty_val=$invoice_qty['total_qty'];
                                								  	    $total_rate_val=$invoice_qty['total_rate'];
                                								  	     if($roll_no == '1')
                                    								  	        $total_amt_val=$total_amt_val;
                                    								  	     else
                                    								  	        $total_amt_val=$invoice_qty['tot'];
                                								  	}
                                                    		}
                             
                             
                                      // printr($total_amt_val);
                            					//	if($invoice['transport']=='sea')
                            						//	{
                            							  
                            					         $total_amt_val=$total_amt_val+$invoice['cylinder_charges'];
                            					         
                            					          $total_amt_val=$total_amt_val+$invoice['tool_cost'];
                            					     
                            					         //printr($invoice);
                            					         if($invoice['sales_invoice_id']=='1247')
                            					            $total_amt_val+=1200;
                            					
                            				    //	}
                            				    
                            				    
                            				    
                            				    
                            				 //   printr($invoice['cylinder_charges']);		
                            				//	printr($total_amt_val);	
                            						
                            						
                            						
                            						/*	if(ucwords(decode($invoice['transportation']))=='Sea')
                            							{
                            							
                            							    $total_amt_val=$total_amt_val+$invoice['cylinder_charges'];
                            							 
                            						
                            						
                            								$amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo));
                            									///	$rate_per = number_format(($total_amt_val/$total_net_w_pouch),8); comment by sonu  18-3-2017  
                            										$rate_per = number_format(( $amt/$total_net_w_pouch),8); 
                            						
                            								
                            							}
                            							else
                            							{*/
                            								
                            					//	printr($total_amt_roll); 
                            						//	printr($total_amt_scoop);
                            						
                            							
                            							    if($invoice_inv_data['invoice_date']<'2018-03-18'){	
                            							
                            							            	$rate_per = number_format((($total_amt_val-($invoice['tran_charges']+$roll_price+$total_amt_paper+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel))/$total_qty_val),8);
                            					                         $amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_paper+$roll_price+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel));
                            							    }else{
                            							                	$rate_per = number_format((($total_amt_val-($invoice['tran_charges']+$total_amt_roll+$total_amt_paper+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel))/$total_qty_val),8);
                            					                             $amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel));
                            							    }
                          //    printr($total_amt_val.'=='.$amt);
                          
                          
                          
                          
                          
                                                        	if($invoice_inv_data['invoice_date']<'2018-10-12'){	
                                    						    if($invoice_inv_data['invoice_id']!='1899'){
                                    								if($invoice_inv_data['country_destination']=='172'  || $invoice_inv_data['country_destination']=='253')//|| $invoice_inv_data['country_destination']=='125'
                                    								{
                                    								    $rate_per = number_format((($total_amt_val-($total_amt_paper+$roll_price+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel))/$total_qty_val),8);
                                    								    $amt = ($total_amt_val-($total_amt_paper+$roll_price+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel));
                                    								}
                                    								
                                    						    }
                                                        	} 
                            						//	printr($rate_per.'==='.printr($amt));
                            								$insurance='0';
        													 if(($invoice['country_id']=='252' || $invoice_inv_data['order_user_id']==2) && ($invoice['transport'])=='air')
        													  {
        													      $amt1=$amt-$invoice['cylinder_charges'];
        														    $tran_charges_tot=$invoice['tran_charges']+$amt1;
        												
        														$insurance= number_Format(((($tran_charges_tot*110/100+$tran_charges_tot)*0.07)/100),2);
        												
        													
        													  }
                            						
                            								 
                            						//	}	
                            								
                            				//	printr($amt);
                            						$air_rate = $amt * $currency['price'];
                            						//	printr($amt);
                            				/*	if($invoice['transport']=='air')
                            							{
                            							  $amt=  $amt+$invoice['cylinder_charges'];
                            							}*/
                            				//	printr($amt);
                            				//	printr($total_amt_scoop);
                            						//	printr($rate_per);
                            						
                            			
                            			if($pouch_name=='POUCHES'){
                            			    
                            			        $html.='<tr style="font-size: 12px;">';
                            					
                                                 		 $html.='<td colspan="3" class="no_border"><b>'.$invoice_inv_data['pouch_type'].' <br>HSN NO.39232990</b><br>'.$invoice_inv_data['pouch_type'].'</td>';
                            			        
                            				    	    $p_id = "'".$group_pouch_id."'";
                            				    	    $pouch_box="'#pouch_box'";
                            				    	    $pouch_kgs="'#pouch_kgs'";
                            				    	    if($pdf!=0){
                                    				    	    $html.='<td class="no_border" align="center"  >'. $pouch_box_no.' '.$no_of_package.'</td>'; 
                                    				    	   $html.='<td class="no_border"  align="center" >'. $total_net_w_pouch.' '.$identification_marks.'</td>';
                            				    	   
                                       				
                                			            }else{
                                			                
                                			                 $html.='<td  valign="top" class="no_border"><input type="text" id="pouch_box" name="pouch_box" onchange="change_qty_per_kg('.$p_id.',0,'.$pouch_box.','.$invoice['invoice_status'].')" value='.$pouch_box_no.''.$no_of_package.'  ></td>';
                                			                	$html.='<td class="no_border" valign="top"><input type="text" id="pouch_kgs" name="pouch_kgs" onchange="change_qty_per_kg('.$p_id.',1,'.$pouch_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_pouch.''.$identification_marks.'" ></td>';
                                			                
                                			            }
                                       					$html.='<td class="no_border" valign="top"><p align="center">'.$total_qty_val.' NOS</p></td>';
                                       				
                                               
                                				
                                		          		$html.='<td class="no_border" valign="top"><p align="center">'.($rate_per*$invoice['currency_rate']).'</p></td>
                                          					';
                                          			  
                                          				if(($invoice['transport'] == 'sea' )|| ($invoice['transport'] == 'air' && $invoice['igst_status']==1)){
                                          				    
                                          				    
                                          				        $igst_tax=((($amt*$invoice['currency_rate'])*$pouch_gst_details['igst_percentage'])/100);
                                          				        $pouch_amt_with_tax=(($amt*$invoice['currency_rate'])+$igst_tax);
                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($amt*$invoice['currency_rate']),2).'% </p></td>';
                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.$pouch_gst_details['igst_percentage'].'% </p></td>';
                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($igst_tax),2).'</p></td>';
                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($amt*$invoice['currency_rate'])+$igst_tax),2).' </p></td>';
                                          				    $total_with_tax=$total_with_tax+$igst_tax; 	
                                          				    
                                          			
                                          				    $final_amt_with_tax=$final_amt_with_tax+$pouch_amt_with_tax;
                                          				    	    
                                          				    	   // printr($pouch_amt_with_tax.'=='.$final_amt_with_tax);
                                          				}else{
                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($amt*$invoice['currency_rate']),2).'</p></td>';
                                          				}
                                          				
                                				
                            			}else{
                            			    $html.='<tr>
                            			    <td class="no_border" colspan="6" valign="top"></td></tr>';
                            			} 
                            				$html.='</tr>';
                            				
                            							if($invoice['tool_cost'] !='0.00')
                        								    { 
                        								        
                            								/*	$html.='<tr style="font-size: 12px;">
                            											
                            												<td colspan="3" class="no_border"><strong>Set Up Cost </td>';
                            										
                                                				      
                                                				    	    $html.='<td class="no_border" align="center" ></td>'; 
                                                				    	   $html.='<td class="no_border" align="center" ></td>';
                                                           			
                            										    	$html.='<td class="no_border" valign="top"></td>';
                            									
                            												$html.='<td class="no_border" valign="top"><p align="center"></td>';
                            									
                            												$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($invoice['tool_cost']),2).'</p></td>';
                            									
                            									$html.='</tr>';*/
                        							    	}
                            						
                            								if($scoop_no == '1')
                            								{
                            								    
                            								    	if($invoice_inv_data['country_destination']=='238')
                                                                			$text = " Plastic Stoppers,Lids,Cap & Other Closures";
                                                                		    else
                                                                		  $text = " Plastic Scoop "; 
                            								
                            								$html.='<tr style="font-size: 12px;">
                            											
                            												<td colspan="3" class="no_border"><strong>A)'.$text.'  </strong><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39235090<br>
                            </td>';
                            											$s_id = "'".$group_scoop_id."'";
                            											$scoop_box="'#scoop_box'";
                            				    	                    $scoop_kgs="'#scoop_kgs'";
                                                				      if($pdf!=0){
                                                				    	    $html.='<td class="no_border" align="center" >'. $scoop_box_no.' '.$no_of_package.'</td>'; 
                                                				    	   $html.='<td class="no_border" align="center" >'.$total_net_w_scoop.' '.$identification_marks.'</td>';
                                                           				
                                                			            }else{
                            			                
                            											$html.='<td class="no_border" valign="top"><input type="text" name="scoop_box" onchange="change_qty_per_kg('.$s_id.',0,'.$scoop_box.','.$invoice['invoice_status'].')" value='.$scoop_box_no.''.$no_of_package.'   id="scoop_box"></td>';
                                       					                $html.='<td class="no_border" valign="top"><input type="text" name="scoop_kgs" onchange="change_qty_per_kg('.$s_id.',1,'.$scoop_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_scoop.''.$identification_marks.'"   id="scoop_kgs"></td>';
                                                			            }
                            											$html.='<td class="no_border" valign="top"><p align="center">'.$tot_scoop_qty.' NOS</p></td>';
                            										
                            									
                            												$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_scoop/$tot_scoop_qty)*$invoice['currency_rate']),8).'</p></td>';
                            									
                            										
                            												if(($invoice['transport'] == 'sea' )|| ($invoice['transport'] == 'air' && $invoice['igst_status']==1)){
                                                          				        $igst_tax_scoop=((($total_amt_scoop*$invoice['currency_rate'])*$scoop_gst_details['igst_percentage'])/100); 
                                                          				        $scoop_amt_with_tax=(($total_amt_scoop*$invoice['currency_rate'])+$igst_tax_scoop); 
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_scoop*$invoice['currency_rate']),2).' </p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.$scoop_gst_details['igst_percentage'].' %</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($igst_tax_scoop),2).'</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_scoop*$invoice['currency_rate'])+$igst_tax_scoop),2).' </p></td>';
                                                          			            $total_with_tax=$total_with_tax+$igst_tax_scoop; 
                                                          			            $final_amt_with_tax=$final_amt_with_tax+$scoop_amt_with_tax;
                                                          		
                                                          				}else{
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_scoop*$invoice['currency_rate']),2).'</p></td>';
                                                          				}
                            									
                            									$html.='</tr>';
                            								}   
                            							
                            								if($roll_no == '1')
                            								{ 
                            								    
                            								    //printr($total_amt_roll);
                            								
                            									
							                    	//$total_amt_val=$total_amt_val+$total_amt_roll;
                            									$html.='<tr style="font-size: 12px;">
                            											
                            												<td colspan="3" class="no_border"><strong>'.$roll_series.') Printed or Unprinted Flexible Packaging Material of Rolls</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>Printed Polyester Rolls : 39201012
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>HS CODE : 39201012 <br>
                            </td>';
                            											
                            											$r_id = "'".$group_roll_id."'";
                            											$roll_box="'#roll_box'";
                            				    	                    $roll_kgs="'#roll_kgs'";
                            				    	                     if($pdf!=0){
                                                				    	    $html.='<td class="no_border" align="center" >'. $roll_box_no.' '.$no_of_package.'</td>'; 
                                                				    	   $html.='<td class="no_border" align="center" >'. $total_net_w_roll.' '.$identification_marks.'</td>';
                                                           				
                                                			            }else{
                            											$html.='<td class="no_border" valign="top"><input type="text" name="roll_box" onchange="change_qty_per_kg('.$r_id.',0,'.$roll_box.','.$invoice['invoice_status'].')" value='.$roll_box_no.''.$no_of_package.'  id="roll_box"></td>';
                                       					                $html.='<td class="no_border" valign="top"><input type="text" name="roll_kgs" onchange="change_qty_per_kg('.$r_id.',1,'.$roll_kgs.','.$invoice['invoice_status'].')" value="'. $total_net_w_roll.''.$identification_marks.'"  id="roll_kgs"></td>';
                                                			            }
                            											$html.='<td class="no_border" valign="top"><p align="center">'.$roll_identification_marks.' Kgs</p></td>';
                                    								/*	if(ucwords(decode($invoice['transportation']))=='Sea')
                            											{
                            												$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_roll/$total_net_w_roll)*$invoice['currency_rate']),8).'</p></td>';
                            											}else{*/
                            												$html.='<td class="no_border" valign="top"><p align="center">'.number_format(((($total_amt_roll)/$roll_identification_marks)*$invoice['currency_rate']),8).'</p></td>';
                            										/*	}*/
                            										
                            										
                            										
                            											if(($invoice['transport'] == 'sea' )|| ($invoice['transport'] == 'air' && $invoice['igst_status']==1)){
                                                          				        $igst_tax_roll=((($total_amt_roll*$invoice['currency_rate'])*$roll_gst_details['igst_percentage'])/100); 
                                                          				        $roll_amt_with_tax=(($total_amt_roll*$invoice['currency_rate'])+$igst_tax_roll); 
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_roll*$invoice['currency_rate']),2).' </p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.$roll_gst_details['igst_percentage'].' %</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($igst_tax_roll),2).'</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_roll*$invoice['currency_rate'])+$igst_tax_roll),2).' </p></td>';
                                                          				
                                                                                    $final_amt_with_tax=$final_amt_with_tax+$roll_amt_with_tax;      
                                                                                    $total_with_tax=$total_with_tax+$igst_tax_roll; 
                            											    
                            											    
                            											}else{
                                                          				        $html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_roll)*$invoice['currency_rate']),2).'</p></td>';
                                                          				}
                            											
                            								 
                            										
                            									$html.='</tr>';
                            								}
                            								if($mailer_no == '1')
                            								{
                            									$html.='<tr style="font-size: 12px;">
                            											
                            												<td colspan="3" class="no_border"><strong>'.$mailer_series.') Mailer Bag</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39232990<br></td>';
                            											
                            													$m_id = "'".$group_mail_id."'";
                            													$mail_box="'#mail_box'";
                            				    	                            $mail_kgs="'#mail_kgs'";
                            				    	                             if($pdf!=0){
                                                        				    	    $html.='<td class="no_border" align="center"  >'.$mailer_box_no.' '.$no_of_package.'</td>'; 
                                                        				    	   $html.='<td class="no_border" align="center"  >'. $total_net_w_m.' '.$identification_marks.'</td>';
                                                           				
                                                			                   }else{
                                    										        	$html.='<td class="no_border" valign="top"><input type="text" name="mail_box" onchange="change_qty_per_kg('.$m_id.',0,'.$mail_box.','.$invoice['invoice_status'].')" value='.$mailer_box_no.''.$no_of_package.'  id="mail_box"></td>';
                                               					                        $html.='<td class="no_border" valign="top"><input type="text" name="mail_kgs" onchange="change_qty_per_kg('.$m_id.',1,'.$mail_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_m.''.$identification_marks.'"   id="mail_kgs"></td>';
                                                			                     }
                            													$html.='<td class="no_border" valign="top"><p align="center">'.$tot_mailer_qty.' NOS </p></td>';
                            									
                                    										/*	if(ucwords(decode($invoice['transportation']))=='Sea')
                                    											{
                                    												$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_mailer/$total_net_w_m)*$invoice['currency_rate']),8).'</p></td>';
                                    											}else{*/
                                    												$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_mailer/$tot_mailer_qty)*$invoice['currency_rate']),8).'</p></td>';
                                    										/*	}*/
                            										
                            										
                            											if(($invoice['transport'] == 'sea' )|| ($invoice['transport'] == 'air' && $invoice['igst_status']==1)){
                                                          				        $igst_tax_mailer=((($total_amt_mailer*$invoice['currency_rate'])*$mailer_gst_details['igst_percentage'])/100); 
                                                          				        $mailer_amt_with_tax=(($total_amt_mailer*$invoice['currency_rate'])+$igst_tax_mailer); 
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_mailer*$invoice['currency_rate']),2).' </p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.$mailer_gst_details['igst_percentage'].' %</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($igst_tax_mailer),2).'</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_mailer*$invoice['currency_rate'])+$igst_tax_mailer),2).' </p></td>';
                                                          			          
                                                          			           $total_with_tax=$total_with_tax+$igst_tax_mailer; 
                                                          			          
                                                          			           $final_amt_with_tax=$final_amt_with_tax+$mailer_amt_with_tax;  
                                                          				}else{
                                                          				      	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_mailer*$invoice['currency_rate']),2).'</p></td>';
                                                          				}
                            											
                            										
                            								
                            								
                            										
                            									$html.='</tr>';
                            								}
                            							/*	if($sealer_no == '1') 
                            								{
                            									$html.='<tr>
                            												<td colspan="3" class="no_border"></td>
                            												<td colspan="3" class="no_border"><div><strong>'.$sealer_series.') Sealer Machine</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 84223000<br>
                            </div></td>';
                            											    $sealer_id = "'".$group_sealer_id."'";
                            											    	$sealer_box="'#sealer_box'";
                            				    	                            $sealer_kgs="'#sealer_kgs'";
                            												   	$html.='<td class="no_border" valign="top"><input type="text" name="sealer_box" onchange="change_qty_per_kg('.$sealer_id.',0,'.$sealer_box.')" value="" id="str_box"></td>';
                                               					                $html.='<td class="no_border" valign="top"><input type="text" name="sealer_kgs" onchange="change_qty_per_kg('.$sealer_id.',1,'.$sealer_kgs.')" value="" id="str_kgs"></td>';
                            												    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_sealer_qty.'</p></td>';
                            								
                            												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_sealer/$tot_sealer_qty,8).'</p></td>';
                            											
                            											$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_sealer,2).'</p></td>';
                            								
                            										
                            									$html.='</tr>';
                            								}*/
                            								if($storezo_no == '1')
                            								{
                            									$html.='<tr style="font-size: 12px;">
                            											
                            												<td colspan="3" class="no_border"><strong>'.$storezo_series.') Storezo High barrier Bag</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39232100<br><br>';
                                                                            
                                                                            $html.='</td>';
                            											
                            													$str_id = "'".$group_str_id."'";
                            													$str_box="'#str_box'";
                            				    	                            $str_kgs="'#str_kgs'";
                            				    	                             if($pdf!=0){
                                                            				    	    $html.='<td class="no_border" align="center" >'. $storezo_box_no.' '.$no_of_package.'</td>'; 
                                                            				    	   $html.='<td class="no_border" align="center" >'.$total_net_w_str.' '.$identification_marks.'</td>';
                                                           				
                                                			                     }else{
                                            											$html.='<td class="no_border" valign="top"><input type="text" name="str_box" onchange="change_qty_per_kg('.$str_id.',0,'.$str_box.','.$invoice['invoice_status'].')" value='.$storezo_box_no.''.$no_of_package.'  id="str_box"></td>';
                                                       					                $html.='<td class="no_border" valign="top"><input type="text" name="str_kgs" onchange="change_qty_per_kg('.$str_id.',1,'.$str_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_str.$identification_marks.'"  id="str_kgs"></td>';
                                                        			                  }
                            													$html.='<td class="no_border" valign="top"><p align="center">'.$tot_storezo_qty.' NOS</p></td>';
                            													//$html.='<td class="no_border" valign="top"><p align="center">'.$tot_storezo_qty.'</p></td>';
                            							
                                    										/*	if(ucwords(decode($invoice['transportation']))=='Sea')
                                    											{
                                    												$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_storezo/$total_net_w_str)*$invoice['currency_rate']),8).'</p></td>';
                                    											}else{*/
                                    												$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_storezo/$tot_storezo_qty)*$invoice['currency_rate']),8).'</p></td>';
                                    										/*	}*/	
                            										
                            											if(($invoice['transport'] == 'sea' )|| ($invoice['transport'] == 'air' && $invoice['igst_status']==1)){
                                                          				        $igst_tax_strezo=((($total_amt_storezo*$invoice['currency_rate'])*$strezo_gst_details['igst_percentage'])/100); 
                                                          				        $strezo_amt_with_tax=(($total_amt_storezo*$invoice['currency_rate'])+$igst_tax_strezo); 
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_storezo*$invoice['currency_rate']),2).' </p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.$strezo_gst_details['igst_percentage'].' % </p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($igst_tax_strezo),2).'</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_storezo*$invoice['currency_rate'])+$igst_tax_strezo),2).' </p></td>';
                                                          			         
                                                          			          $total_with_tax=$total_with_tax+$igst_tax_strezo; 
                                                          			          $final_amt_with_tax=$final_amt_with_tax+$strezo_amt_with_tax;  
                                                          			
                                                          				}else{
                                                          				       $html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_storezo*$invoice['currency_rate']),2).'</p></td>';
                                                          				}
                            											
                            											
                            								
                            										
                            									$html.='</tr>';
                            								}
                            								if($paper_box_no == '1')
                            								{
                            								    	$paper_id = "'".$group_paper_id."'";
                            								    	$paper_box="'#paper_box'";
                            				    	               $paper_kgs="'#paper_kgs'";
                            									$html.='<tr style="font-size: 12px;">
                            											
                            												<td colspan="3" class="no_border"><strong>'.$paper_series.') Paper Board Boxes</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 48191010<br> </td>';               
                                                                         if($pdf!=0){
                                                				    	           $html.='<td class="no_border" align="center" >'. $paper_box_no.' '.$no_of_package.'</td>'; 
                                                				    	           $html.='<td class="no_border" align="center" >'. $total_net_w_p.' '.$identification_marks.'</td>';
                                                           				
                                                			            }else{
                                									        $html.='<td class="no_border" valign="top"><input type="text" name="paper_box" onchange="change_qty_per_kg('.$paper_id.',0,'.$paper_box.','.$invoice['invoice_status'].')" value='.$paper_box_no.''.$no_of_package.'  id="str_box"></td>';
                                       					                    $html.='<td class="no_border" valign="top"><input type="text" name="paper_kgs" onchange="change_qty_per_kg('.$paper_id.',1,'.$paper_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_p.''.$identification_marks.'"  id="str_kgs"></td>';
                                                			            }
                                									    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_paper_qty.' NOS</p></td>';
                                								  
                            												$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_paper/$tot_paper_qty)*$invoice['currency_rate']),8).'</p></td>';
                            									
                                							
                                							        	
                                							       	if(($invoice['transport'] == 'sea' )|| ($invoice['transport'] == 'air' && $invoice['igst_status']==1)){
                                                          				        $igst_tax_paper=((($total_amt_paper*$invoice['currency_rate'])*$paper_gst_details['igst_percentage'])/100); 
                                                          				        $paper_amt_with_tax=(($total_amt_paper*$invoice['currency_rate'])+$igst_tax_paper); 
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_paper*$invoice['currency_rate']),2).' </p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.$paper_gst_details['igst_percentage'].'%</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($igst_tax_paper),2).'</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_paper*$invoice['currency_rate'])+$igst_tax_paper),2).' </p></td>';
                                                          				    		  $total_with_tax=$total_with_tax+$igst_tax_paper; 
                                                          				    	    $final_amt_with_tax=$final_amt_with_tax+$paper_amt_with_tax;  
                                                          				}else{
                                                          				      	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_paper*$invoice['currency_rate']),2).'</p></td>';
                                                          				}
                            											
                                							        
                            								
                            										
                            									$html.='</tr>';
                            								}
                            								
                            							if($con_no == '1')
        													{
        
        															$con_id = "'".$group_con_id."'";
                            								    	$con_box="'#con_box'";
                            				    	               $con_kgs="'#con_kgs'";
        														$html.='<tr style="font-size: 12px;">
        																	
        																	<td colspan="3" class="no_border"><div><strong>'.$gls_series.') Plastic Disposable Lid / Container</strong><br>
        																		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39241090<br>
        																		</div></td>';
        																		   if($pdf!=0){
                                                				    	           $html.='<td class="no_border" align="center"  >'. $con_box_no.' '.$no_of_package.'</td>'; 
                                                				    	           $html.='<td class="no_border" align="center" > '. $total_net_w_con.' '.$identification_marks.'</td>';
                                                           				
        			                                        			            }else{
        			                        									        $html.='<td class="no_border" valign="top"><input type="text" name="con_box" onchange="change_qty_per_kg('.$con_id.',0,'.$con_box.','.$invoice['invoice_status'].')" value='.$con_box_no.''.$no_of_package.'  id="con_box"></td>';
        			                               					                    $html.='<td class="no_border" valign="top"><input type="text" name="paper_kgs" onchange="change_qty_per_kg('.$con_id.',1,'.$con_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_con.''.$identification_marks.'"  id="str_kgs"></td>';
        			                                        			            }
        																	/*if(ucwords(decode($invoice['transportation']))=='Sea')
        																		$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_con .'<br>'.$tot_con_qty.' Nos </p></td>';
        																	else*/
        																	    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_con_qty.' NOS</p></td>';
        														
        																/*if(ucwords(decode($invoice['transportation']))=='Sea')
        																{
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_con/$total_net_w_con,8).'</p></td>';
        																}else{*/
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_con/$tot_con_qty)*$invoice['currency_rate'],8).'</p></td>';
        																//}	
        															
        															
        															   	if(($invoice['transport'] == 'sea' )|| ($invoice['transport'] == 'air' && $invoice['igst_status']==1)){
                                                          				        $igst_tax_con=((($total_amt_con*$invoice['currency_rate'])*$con_gst_details['igst_percentage'])/100); 
                                                          				        $con_amt_with_tax=(($total_amt_con*$invoice['currency_rate'])+$igst_tax_con); 
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_con*$invoice['currency_rate']),2).' </p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.$con_gst_details['igst_percentage'].' %</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($igst_tax_con),2).'</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_con*$invoice['currency_rate'])+$igst_tax_con),2).' </p></td>';
                                                          				    	  $final_amt_with_tax=$final_amt_with_tax+$con_amt_with_tax;  
                                                          				    	  $total_with_tax=$total_with_tax+$igst_tax_con; 
                                                          				}else{
                                                          				      	$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_con*$invoice['currency_rate'],2).'</p></td>';
                                                          				}
        															
        															
        														
        														
        															
        														$html.='</tr>';
        													}
        													if($gls_no == '1')
        													{
        
        														$gls_id = "'".$group_gls_id."'";
                            								    	$gls_box="'#gls_box'";
                            				    	               $con_kgs="'#gls_kgs'";
        														$html.='<tr style="font-size: 12px;">
        																
        																	<td colspan="3" class="no_border"><strong>'.$gls_series.') Plastic Glasses</strong><br>
        																	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39241090<br>
        																	</td>';
        
        																	   if($pdf!=0){
                                                				    	           $html.='<td class="no_border" align="center" >'. $gls_box_no.' '.$no_of_package.'</td>'; 
                                                				    	           $html.='<td class="no_border" align="center" >'. $total_net_w_gls.' '.$identification_marks.'</td>';
                                                           				
        			                                        			            }else{
        			                        									        $html.='<td class="no_border" valign="top"><input type="text" name="gls_box" onchange="change_qty_per_kg('.$gls_id.',0,'.$gls_box.','.$invoice['invoice_status'].')" value='.$gls_box_no.''.$no_of_package.'  id="gls_box"></td>';
        			                               					                    $html.='<td class="no_border" valign="top"><input type="text" name="gls_kgs" onchange="change_qty_per_kg('.$gls_id.',1,'.$con_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_gls.''.$identification_marks.'"  id="gls_kgs"></td>';
        			                                        			            }
        																	/*if(ucwords(decode($invoice['transportation']))=='Sea')
        																		$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_gls .'<br>'.$tot_gls_qty.' Nos </p></td>';
        																	else*/
        																	    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_gls_qty.' NOS</p></td>';
        														/*if($status==1)
        														{	
        																if(ucwords(decode($invoice['transportation']))=='Sea')
        																{
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_gls/$total_net_w_gls,8).'</p></td>';
        																}else{*/
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_gls/$tot_gls_qty)*$invoice['currency_rate'],8).'</p></td>';
        																//}	
        															
        															
        															
        																  	if(($invoice['transport'] == 'sea' )|| ($invoice['transport'] == 'air' && $invoice['igst_status']==1)){
                                                          				        $igst_tax_gls=((($total_amt_gls*$invoice['currency_rate'])*$gls_gst_details['igst_percentage'])/100); 
                                                          				        $gls_amt_with_tax=(($total_amt_gls*$invoice['currency_rate'])+$igst_tax_gls);
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_gls*$invoice['currency_rate']),2).' </p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.$gls_gst_details['igst_percentage'].' %</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($igst_tax_gls),2).'</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_gls*$invoice['currency_rate'])+$igst_tax_gls),2).' </p></td>';
                                                              				   $total_with_tax=$total_with_tax+$igst_tax_gls; 
                                                              				     $final_amt_with_tax=$final_amt_with_tax+$gls_amt_with_tax;  
        																   	    
        																   	    
        																   	}else{
                                                              				      	$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_gls*$invoice['currency_rate'],2).'</p></td>';
                                                              				}
        															
        														
        															
        														$html.='</tr>';
        													}
        													if($chair_no == '1')
        													{
        
        														$chair_id = "'".$group_chair_id."'";
                            								    	$chair_box="'#gls_box'";
                            				    	               $chair_kgs="'#gls_kgs'";
        														$html.='<tr style="font-size: 12px;">
        																
        																	<td colspan="3" class="no_border"><strong>'.$chair_series.') CHAIR</strong><br>
        																	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 94036000<br>
        																	</td>';
        
        																	   if($pdf!=0){
                                                				    	           $html.='<td class="no_border" align="center" >'. $chair_box_no.' '.$no_of_package.'</td>'; 
                                                				    	           $html.='<td class="no_border" align="center" >'. $total_net_w_chair.' '.$identification_marks.'</td>';
                                                           				
        			                                        			            }else{
        			                        									        $html.='<td class="no_border" valign="top"><input type="text" name="gls_box" onchange="change_qty_per_kg('.$gls_id.',0,'.$gls_box.','.$invoice['invoice_status'].')" value='.$chair_box_no.''.$no_of_package.'  id="chair_box"></td>';
        			                               					                    $html.='<td class="no_border" valign="top"><input type="text" name="gls_kgs" onchange="change_qty_per_kg('.$gls_id.',1,'.$con_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_chair.''.$identification_marks.'"  id="chair_kgs"></td>';
        			                                        			            }
        																	/*if(ucwords(decode($invoice['transportation']))=='Sea')
        																		$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_gls .'<br>'.$tot_gls_qty.' Nos </p></td>';
        																	else*/
        																	    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_chair_qty.' NOS</p></td>';
        														/*if($status==1)
        														{	
        																if(ucwords(decode($invoice['transportation']))=='Sea')
        																{
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_gls/$total_net_w_gls,8).'</p></td>';
        																}else{*/
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_chair/$tot_chair_qty)*$invoice['currency_rate'],8).'</p></td>';
        																//}	
        															
        															 	if(($invoice['transport'] == 'sea' )|| ($invoice['transport'] == 'air' && $invoice['igst_status']==1)){
                                                          				        $igst_tax_chair=((($total_amt_chair*$invoice['currency_rate'])*$chair_gst_details['igst_percentage'])/100); 
                                                          				        $chair_amt_with_tax=(($total_amt_chair*$invoice['currency_rate'])+$igst_tax_chair);
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_chair*$invoice['currency_rate']),2).' </p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.$chair_gst_details['igst_percentage'].' %</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($igst_tax_chair),2).'</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_chair*$invoice['currency_rate'])+$igst_tax_chair),2).' </p></td>';
                                                              			      $total_with_tax=$total_with_tax+$igst_tax_chair; 
                                                              			         $final_amt_with_tax=$final_amt_with_tax+$chair_amt_with_tax;  
                                                              			
                                                              				}else{
                                                              				      	$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_chair*$invoice['currency_rate'],2).'</p></td>';
                                                              				}
        															
        															
        															
        														
        															
        														$html.='</tr>';
        													}	
        													if($oxygen_absorbers_no == '1')
        													{
        
        														$oxygen_absorbers_id = "'".$group_oxygen_absorbers_id."'";
                            								    	$oxygen_absorbers_box="'#oxygen_absorbers_box'";
                            				    	               $oxygen_absorbers_kgs="'#oxygen_absorbers_kgs'";
        														$html.='<tr style="font-size: 12px;">
        																
        																	<td colspan="3" class="no_border"><strong>'.$oxygen_absorbers_series.') OXYGEN ABSORBERS </strong><br>
        																	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 38249990<br>
        																	</td>';
        
        																	   if($pdf!=0){
                                                				    	           $html.='<td class="no_border" align="center" >'. $oxygen_absorbers_box_no.' '.$no_of_package.'</td>'; 
                                                				    	           $html.='<td class="no_border" align="center" >'. $oxygen_absorbers_identification_marks.' '.$identification_marks.'</td>';
                                                           				
        			                                        			            }else{
        			                        									        $html.='<td class="no_border" valign="top"><input type="text" name="oxygen_absorbers_box" onchange="change_qty_per_kg('.$gls_id.',0,'.$gls_box.','.$invoice['invoice_status'].')" value='.$oxygen_absorbers_box_no.''.$no_of_package.'  id="oxygen_absorbersoxygen_absorbersx"></td>';
        			                               					                    $html.='<td class="no_border" valign="top"><input type="text" name="oxygen_absorbers_kgs" onchange="change_qty_per_kg('.$gls_id.',1,'.$con_kgs.','.$invoice['invoice_status'].')" value="'.$oxygen_absorbers_identification_marks.''.$identification_marks.'"  id="oxygen_absorbersoxygen_absorberss"></td>';
        			                                        			            }
        																	/*if(ucwords(decode($invoice['transportation']))=='Sea')
        																		$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_gls .'<br>'.$tot_gls_qty.' Nos </p></td>';
        																	else*/
        																	    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_oxygen_absorbers_qty.' NOS</p></td>';
        														/*if($status==1)
        														{	
        																if(ucwords(decode($invoice['transportation']))=='Sea')
        																{
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_gls/$total_net_w_gls,8).'</p></td>';
        																}else{*/
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_oxygen_absorbers/$tot_oxygen_absorbers_qty)*$invoice['currency_rate'],8).'</p></td>';
        																//}	
        															
        															
        																if(($invoice['transport'] == 'sea' )|| ($invoice['transport'] == 'air' && $invoice['igst_status']==1)){
                                                          				        $igst_tax_oxy=((($total_amt_oxygen_absorbers*$invoice['currency_rate'])*$oxygen_gst_details['igst_percentage'])/100); 
                                                          				        $oxy_amt_with_tax=(($total_amt_oxygen_absorbers*$invoice['currency_rate'])+$igst_tax_oxy); 
                                                          				    
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_oxygen_absorbers*$invoice['currency_rate']),2).' </p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.$oxygen_gst_details['igst_percentage'].' %</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($igst_tax_oxy),2).'</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_oxygen_absorbers*$invoice['currency_rate'])+$igst_tax_oxy),2).' </p></td>';
                                                              			              $total_with_tax=$total_with_tax+$igst_tax_oxy; 
                                                              			        
                                                              			          $final_amt_with_tax=$final_amt_with_tax+$oxy_amt_with_tax;  
                                                              			
                                                              				}else{
                                                              				  $html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_oxygen_absorbers*$invoice['currency_rate'],2).'</p></td>';
                                                              				}
        															
        															
        															
        															
        															
        														
        															
        														$html.='</tr>';
        													}
        													if($silica_gel_no == '1')
        													{
        
        														$silica_gel_id = "'".$group_silica_gel_id."'";
                            								    	$silica_gel_box="'#oxygen_absorbers_box'";
                            				    	               $silica_gel_kgs="'#oxygen_absorbers_kgs'";
        														$html.='<tr style="font-size: 12px;">
        																
        																	<td colspan="3" class="no_border"><strong>'.$silica_gel_series.') SILICA GEL </strong><br>
        																	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 38249025<br>
        																	</td>';
        
        																	   if($pdf!=0){
                                                				    	           $html.='<td class="no_border" align="center" >'. $silica_gel_no_of_package.' '.$no_of_package.'</td>'; 
                                                				    	           $html.='<td class="no_border" align="center" >'. $silica_gel_identification_marks.' '.$identification_marks.'</td>';
                                                           				
        			                                        			            }else{
        			                        									        $html.='<td class="no_border" valign="top"><input type="text" name="oxygen_absorbers_box" onchange="change_qty_per_kg('.$silica_gel_id.',0,'.$silica_gel_box_no.','.$invoice['invoice_status'].')" value='.$silica_gel_box_no.''.$no_of_package.'  id="silica_gel"></td>';
        			                               					                    $html.='<td class="no_border" valign="top"><input type="text" name="oxygen_absorbers_kgs" onchange="change_qty_per_kg('.$silica_gel_id.',1,'.$total_net_w_silica_gel.','.$invoice['invoice_status'].')" value="'.$total_net_w_silica_gel.''.$identification_marks.'"  id="silica_gel"></td>';
        			                                        			            }
        																	/*if(ucwords(decode($invoice['transportation']))=='Sea')
        																		$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_gls .'<br>'.$tot_gls_qty.' Nos </p></td>';
        																	else*/
        																	    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_silica_gel_qty.' NOS</p></td>';
        														/*if($status==1)
        														{	
        																if(ucwords(decode($invoice['transportation']))=='Sea')
        																{
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_gls/$total_net_w_gls,8).'</p></td>';
        																}else{*/
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_silica_gel/$tot_silica_gel_qty)*$invoice['currency_rate'],8).'</p></td>';
        																//}	
        															
        															
        																	if(($invoice['transport'] == 'sea' )|| ($invoice['transport'] == 'air' && $invoice['igst_status']==1)){
                                                          				        $igst_tax_silica=((($total_amt_silica_gel*$invoice['currency_rate'])*$silica_gst_details['igst_percentage'])/100); 
                                                          				        $silica_amt_with_tax=(($total_amt_silica_gel*$invoice['currency_rate'])+$igst_tax_silica); 
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_silica_gel*$invoice['currency_rate']),2).' </p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.$silica_gst_details['igst_percentage'].' %</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($igst_tax_silica),2).'</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_silica_gel*$invoice['currency_rate'])+$igst_tax_silica),2).'</p></td>';
                                                          			           $final_amt_with_tax=$final_amt_with_tax+$silica_amt_with_tax;  
                                                          			             $total_with_tax=$total_with_tax+$igst_tax_silica; 
                                                          				}else{
                                                          			    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_silica_gel*$invoice['currency_rate'],2).'</p></td>';
                                                          				}
        															
        															
        															
        															
        															
        														
        														
        														$html.='</tr>';
        													}
        													if($val_no == '1')
        													{
        
        															$val_id = "'".$group_val_id."'";
                            								    	$val_box="'#val_box'";
                            				    	               $val_kgs="'#val_kgs'";
        														$html.='<tr>
        																	
        																	<td colspan="3" class="no_border"><div><strong>'.$val_series.') Plastic Cap</strong><br>
        																	    V-105 <br>
        																		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39232100<br>
        																		</div></td>';
        
        
        																	   if($pdf!=0){
                                            				    	           $html.='<td class="no_border" align="center" >'. $val_no_of_package.' '.$no_of_package.'</td>'; 
                                            				    	           $html.='<td class="no_border" align="center" >'. $val_identification_marks.' '.$identification_marks.'</td>';
                                                       				
        		                                        			            }else{
        		                        									        $html.='<td class="no_border" valign="top"><input type="text" name="val_box" onchange="change_qty_per_kg('.$val_id.',0,'.$val_box.','.$invoice['invoice_status'].')" value='.$val_box_no.''.$no_of_package.'  id="val_box"></td>';
        		                               					                    $html.='<td class="no_border" valign="top"><input type="text" name="val_kgs" onchange="change_qty_per_kg('.$val_id.',1,'.$val_kgs.','.$invoice['invoice_status'].')" value="'.$total_net_w_val.''.$identification_marks.'"  id="val_kgs"></td>';
        		                                        			            }
        																/*	if(ucwords(decode($invoice['transportation']))=='Sea')
        																		$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_val .'<br>'.$tot_val_qty.' Nos </p></td>';
        																	else*/
        																	    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_val_qty.' NOS</p></td>';
        														/*	if(ucwords(decode($invoice['transportation']))=='Sea')
        																{
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_valve/$total_net_w_val,8).'</p></td>';
        																}else{*/
        																	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_valve/$tot_val_qty)*$invoice['currency_rate'],8).'</p></td>';
        															//	}	
        															
        																if(($invoice['transport'] == 'sea' )|| ($invoice['transport'] == 'air' && $invoice['igst_status']==1)){
                                                          				        $igst_tax_val=((($total_amt_valve*$invoice['currency_rate'])*$val_gst_details['igst_percentage'])/100); 
                                                          				        $val_amt_with_tax=(($total_amt_valve*$invoice['currency_rate'])+$igst_tax_val); 
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_valve*$invoice['currency_rate']),2).' </p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.$val_gst_details['igst_percentage'].' %</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($igst_tax_val),2).'</p></td>';
                                                          				    	$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($total_amt_valve*$invoice['currency_rate'])+$igst_tax_val),2).' </p></td>';
                                                          			            
                                                          			            $final_amt_with_tax=$final_amt_with_tax+$val_amt_with_tax;  
                                                          			            $total_with_tax=$total_with_tax+$igst_tax_val;  
                                                          			
                                                          				}else{
                                                          			        	$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_valve*$invoice['currency_rate'],2).'</p></td>';
                                                          				}
        															
        															
        															
        													
        															
        														$html.='</tr>';
        													}
        													 
                            						
                            						
                            						
                                     $html.='</tr>';
                                     	if($pouch_name=='POUCHES'){ 
                                     	$html.=' <tr id="one_tr" style="font-size: 12px;">';
                            							
                            											 
                            						 $html.='<td colspan="3"  class="no_border">
                            						                
                            						         Printed Flexible packaging material of one layer or printed or unprinted 
                            						         adhesive laminated/ extrusion laminated flexible packaging material of multilayers 
                            						         of relevant substrate with or without hotmel in the form of Rolls/strips/sheets/
                            						         labels/wrapers or in Pouch from (for pouch).';

                            									
                            									$html.='</td>
                            										<td class="no_border"></td>'; 
                            						 
                            						
                            								 $html.='<td class="no_border"></td> 
                            								         <td class="no_border"></td>
                            								         <td class="no_border"></td>';
                            								       
                            										 	if(($invoice['transport'] == 'sea' )|| ($invoice['transport'] == 'air' && $invoice['igst_status']==1)){
                                                      			
                                                      				    	$html.='<td class="no_border"></td>';
                                                      				    	$html.='<td class="no_border"></td>';
                                                      				    	$html.='<td class="no_border"></td>';
                                                      				    	$html.='<td class="no_border"></td>';
                                                          				}else{
                                                          				   	$html.='<td class="no_border"></td>';
                                                          				}
                                          				
                            										 
                            										 
                            										 
                            										 
                            										 
                            										 $html.=' </tr>';
                            									
                            		 }
                            		 	$html.=' <tr id="one_tr" style="font-size: 12px;"> 
                            					';
                            					
                            					
                            				
                            				
                            					$scoop=$roll=$mailer=$storezo=$paper=$con=$gls=$valve =$cylinder= $freight=$oxygen_absorbers=$silica_gel=$set_up_cost='';
                            					
                            				
                            					if($total_amt_scoop!='0.00'){
                            					    $scoop=',SCOOP '.$currency['currency_code'].' :'.number_Format($total_amt_scoop,2);
                            					}
                            				
                            				
                            					if($total_amt_roll!='0.00'){
                            					    $roll=',ROLL '.$currency['currency_code'].':'.number_Format($total_amt_roll,2);
                            					}
                            					if($total_amt_mailer!='0.00'){
                            					    $mailer=',MAILER BAG '.$currency['currency_code'].' :'.number_Format($total_amt_mailer,2);
                            					}
                            					if($total_amt_storezo!='0.00'){
                            					    $storezo=',STOREZO BAG '.$currency['currency_code'].' :'.number_Format($total_amt_storezo,2);
                            					}
                            					if($total_amt_paper!='0.00'){ 
                            					    $paper=', PAPER '.$currency['currency_code'].':'.number_Format($total_amt_paper,2);
                            					}
                            					if($total_amt_con!=''){
                            					    $con=', LID/CONTAINER '.$currency['currency_code'].':'.number_Format($total_amt_con,2);
                            					}
                            					if($total_amt_gls!=''){
                            					    $gls=',PLASTIC GLASSES '.$currency['currency_code'].':'.number_Format($total_amt_gls,2);
                            					}
                            					if($total_amt_valve!=''){
                            					    $valve=', PLASTIC CAP '.$currency['currency_code'].':'.number_Format($total_amt_valve,2);
                            					}
                            					if($total_amt_chair!=''){
                            					    $chair=', CHAIR '.$currency['currency_code'].':'.number_Format($total_amt_chair,2);
                            					}
                            						//printr($total_amt_silica_gel);
                            					if($total_amt_silica_gel!=''){
                            					      $silica_gel=', SILICA GEL '.$currency['currency_code'].':'.number_Format($total_amt_silica_gel,2);
                            					} 
                            			    	if($total_amt_oxygen_absorbers!=''){
                            					    $oxygen_absorbers=', OXYGEN ABSORBERS '.$currency['currency_code'].':'.number_Format($total_amt_oxygen_absorbers,2);
                            					}
                            				
                            	          
                            		        	  if($air_f=='2' && $amt=='0'){ //only for  roll 
                                					          $cylinder='('.$currency['currency_code'].' '.number_Format($total_amt_roll-$invoice['cylinder_charges'],2).' ROLL+ CYLINDER '.$currency['currency_code'].' :'.number_Format($invoice['cylinder_charges'],2).''.$string.')';
                                			                  $roll="";$amt=$total_amt_roll;
                            			            
                            			            	}else{
                            			            	//	printr($invoice['transport']);
                            			            	//	printr($invoice['cylinder_charges']); 
                                        					if($invoice['cylinder_charges']!='0.00'  && $invoice['transport']=='air'){
                                        					       $f_amt=$amt-($invoice['cylinder_charges']+$invoice['tool_cost']);$string='';
                                        					    
                                        					    
                                        					    	if($invoice['tool_cost']!='0.00'){
                                        					              $set_up_cost=', + SET UP COST '.$currency['currency_code'].' :'.number_Format($invoice['tool_cost'],2);
                                        				            	}
                                        					   
                                        					  
                                        					     if($invoice['sales_invoice_id']=='1247')
                                        					     {
                                        					        $f_amt = $f_amt-1200;
                                        					        $string = ',+ DESIGN CHARGES '.$currency['currency_code'].' :1200.00)';
                                        					     }
                                        					     //printr($f_amt);
                                        					      
                                        					      $cylinder='('.$currency['currency_code'].' '.number_Format($f_amt,2).',+ CYLINDER '.$currency['currency_code'].' :'.number_Format($invoice['cylinder_charges'],2).''.$string.' '.$set_up_cost.')';
                                        					}
                                                					
                            			            	}
                            			            
                            					if($invoice['invoice_id']=='2177'){
                            					    $invoice['tran_charges']=$invoice['tran_charges']-$insurance;
                            					}
                            			        
                            			        
                            			    
                            					if($invoice['tran_charges']!='0.00' && $insurance!='0.00' ){
                            					    $freight=', FREIGHT  '.$currency['currency_code'].' :'.number_Format(($invoice['tran_charges']+$invoice['extra_tran_charges']+$insurance),2).'(FREIGHT '.$currency['currency_code'].':'.number_Format($invoice['tran_charges'],2).'+INSURANCE '.$currency['currency_code'].':'.number_Format($insurance,2).')';
                            					} else if($invoice['tran_charges']!='0.00'){
                            					     $freight=', FREIGHT  '.$currency['currency_code'].' :'.number_Format($invoice['tran_charges']+$invoice['extra_tran_charges']+$insurance,2);
                            					}else if($invoice['tran_charges']='0.00' && $invoice['extra_tran_charges']!='0.00'){
                            					    $insurance_data='';
                            					    if($insurance!='0.00')
                            					       $insurance_data= '(FREIGHT '.$currency['currency_code'].':'.number_Format($invoice['tran_charges'],2).'+INSURANCE '.$currency['currency_code'].':'.number_Format($insurance,2).')';
                            				
                            				          $freight=', FREIGHT  '.$currency['currency_code'].' :'.number_Format($invoice['extra_tran_charges']+$insurance,2).''.$insurance_data.')';
                            					}
                            				//	printr($amt);
                            			           
                            					 $html.='<td colspan="3"  class="no_border">
                            					  
                            					        <b><br>'.$currency['currency_code'].':'.number_Format($invoice['currency_rate'],2).' RS.,'.$currency['currency_code'].' : '.number_Format($amt,2).' '.$cylinder.' '.$freight.' '.$scoop.' '.$roll.''.$mailer.' '.$storezo.' '.$paper.' '.$con.' '.$gls.' '.$valve.' '.$oxygen_absorbers.' '.$silica_gel.' '.$chair;
                            							    
                            									$html.='</b></td>
                            										<td class="no_border"></td>'; 
                            						 
                            						
                            								 $html.='<td class="no_border"></td> 
                            								         <td class="no_border"></td>
                            								         <td class="no_border"></td>';
                            								  	  	if(($invoice['transport'] == 'sea' )|| ($invoice['transport'] == 'air' && $invoice['igst_status']==1)){
                                                      		    	
                                                      				    	$html.='<td class="no_border"></td>';
                                                      				    	$html.='<td class="no_border"></td>';
                                                      				    	$html.='<td class="no_border"></td>';
                                                      				    	$html.='<td class="no_border"></td>';
                                                          				}else{
                                                          				   	$html.='<td class="no_border"></td>';
                                                          		}     
                            										
                            										 
                            										 
                            										 
                            									$html.='</tr>';
                            										 
                            										 
                            										 
                            										 
                            							$html.=' <tr id="one_tr">
                            								 ';
                            								$container_no=$seal_no=$rfid_no='';	  
                            						 $html.='<td colspan="3"  class="no_border"><b>
                            						    G.W- '.number_Format($totgross_weight,3).'KGS<br>N-W-'.number_Format($total_identification_marks,3).'KGS</b>';
                            						     if(str_replace(',', '', $cond_net_weight) >'200' && $invoice['invoice_date']>'2018-03-31'  &&  $invoice_inv_data['country_destination']!='169')
                            						             $html.='<br>SHIPMENT UNDER DUTY DRAWBACK SCHEME';
                            						        
                            							    	if($invoice['transport']!='air')
        							                                {
        							                                    if($invoice['container_no']!=''){
        							                                       $container_no='<br><b>CONTAINER NO :'.$invoice['container_no'].'<br>';
        							                                    }
        							                                    if($invoice['container_no']!=''){
        							                                       $seal_no='<b>SEAL NO :'.$invoice['seal_no'].'<br>';
        							                                    }
        							                                    if($invoice['rfid_no']!=''){
        							                                       $rfid_no='<b>RFID E SEAL NO-'.$invoice['rfid_no'].'<br>';
        							                                    }
                                    							  	$html.='<br><b>'.$invoice['pallet_detail'].'</b>'.$container_no.''.$seal_no.''.$rfid_no;
        							                                }else if($invoice_inv_data['show_pallet']=='1'){
        							                                    	$html.='<br><b>'.$invoice['pallet_detail'].'</b>';
        							                                }
                            								$html.='</td>
                            										<td class="no_border"></td>'; 
                            						 
                            						
                            								 $html.='<td class="no_border"></td> 
                            								         <td class="no_border"></td>
                            								         <td class="no_border"></td>';
                            								       
                        									  	if(($invoice['transport'] == 'sea' )|| ($invoice['transport'] == 'air' && $invoice['igst_status']==1)){
                                                  			
                                                  				    	$html.='<td class="no_border"></td>';
                                                  				    	$html.='<td class="no_border"></td>';
                                                  				    	$html.='<td class="no_border"></td>';
                                                  				    	$html.='<td class="no_border"></td>';
                                                      				}else{
                                                      				   	$html.='<td class="no_border"></td>';
                                                      				}
                        										 
                            										 $html.='</tr>';
                            	
                            	
                            
        				
        					 if($invoice['tran_charges']!='0.000')
        					    $invoice['tran_charges']=$invoice['tran_charges'];
        					  else
        					    $invoice['tran_charges']=0; 
                            
                        
                         //   printr($invoice['tran_charges']);
                        //    printr($total_amt_val);
                            
                                if((($invoice_inv_data['country_destination']=='172'   || $invoice_inv_data['country_destination']=='253') )&&($invoice_inv_data['invoice_id']!='1899')&& ($invoice['transport']=='air')){//|| $invoice_inv_data['country_destination']=='125'
                            	   
                            	        $final_amount=$total_amt_val-$invoice['tran_charges'];
                            	   
                                 }
                               else{
                                        $final_amount=$total_amt_val-$invoice['tran_charges'];
                                        
                                   }
                                      
                                 //  printr($final_amount);
                           
                               
                            
                            			
                            
                            		if($currency['currency_code'] == 'INR') {
                            			$Total_price = round($Total_price);
                            		} 
                            
                            
                            
                        
                                        if ($invoice['transport'] == 'air' && $invoice['igst_status']==0){
                            	
                                         if($invoice['tran_charges']=='0.00' && $invoice['extra_tran_charges']!='0.00')
                                 		        {
                                 		            $Total_price = $final_amount + $invoice['extra_tran_charges']+$insurance;
                                 		        }else{
                                 		            $Total_price = $final_amount + $invoice['tran_charges']+$invoice['extra_tran_charges']+$insurance;
                                 		        }
                            											
                                         }else{
                                              $Total_price = $final_amount ;
                                         }
                            	  
                            
                            
                            
                            	$Total_price=$Total_price*$invoice['currency_rate'];
                        //printr($Total_price);
                       
                       
                           if($invoice['transport'] == 'air' &&  $invoice['igst_status']==0)
                             $final_amt_with_tax=$Total_price;
                          else  
                            $final_amt_with_tax=$final_amt_with_tax;
                         
                        // printr($final_amt_with_tax);
                         
                                   
                         /*      if($invoice['transport'] == 'air' && $invoice['igst_status']==1)
                                	{
                                		 $Total_price =  $Total_price+((($final_amount*$invoice['currency_rate'])*18)/100);   
                                	}*/
                            /*	 if($invoice['transport'] == 'sea') 
                         		        {
                         		     $Total_price =  $Total_price+((($final_amount*$invoice['currency_rate'])*18)/100);     
                         		        }
                               */
                                     
                                    
                                     $html .='<tr>
                            				
                            					<td  colspan="3"><div align="right"><strong>Total..</strong></div></td>
                            					<td  align="center" >'.$total_no_of_package.' BOXES</td>
                            					<td align="center" >'.$total_identification_marks.' KGS</td>
                            					<td align="center" >'.$invoice_qty['total_qty'].'</td>
                            					<td ></td>
                            				'; 
                            					if(($invoice['transport'] == 'sea' )|| ($invoice['transport'] == 'air' && $invoice['igst_status']==1)){
                              			
                              				     	$html.='	<td >'.number_Format ($final_amount*$invoice['currency_rate'],2).'</td>';
                              				    	$html.='	<td ></td>';
                              				     	$html.='	<td >'.number_format($total_with_tax,'2').'</td>';
                              				     	$html.='	<td >'.number_format($final_amt_with_tax,'2').'</td>';
                                  				}else{
                                  				   	$html.='	<td ></td>';
                                  				}
                            				$html.'</tr>';						
                            				
                                           
                                    if($invoice['transport'] == 'air' || $invoice['transport'] == 'road'){
                                        
                                         $transport_line ='LUT WITHOUT PAYMENT OF IGST';
                                      	if($invoice['invoice_date']>='2019-04-01'){
                                             $invoice['tran_desc']='"LETTER OF UNDERTAKING APPLICATION REFERENCE NUMBER-AA2403180293296(ARN)';
                                         }else{
                                             $invoice['tran_desc']='"LETTER OF UNDERTAKING APPLICATION REFERENCE NUMBER-AD240319004221P(ARN)';
                                         }
                                       }else{
                                                 $transport_line ='EXPORT AGAINST PAYMENT OF IGST';
                                                $invoice['tran_desc']=$invoice['tran_desc'];
                                      }
                                    
                                    
                
                        }
                        
      elseif($invoice['invoice_status']=='2' ){ 
                            
                            
                        // printr($invoice_product_second[0]['product_name']);
            					
                     $html.='<tr style="font-size: 12px;">';
                            $html.='</td>';
                            $html.='<td colspan="3" class="no_border"><b>HSN NO.'.$invoice['hscode'].'</b></td>';
                            	$p_roll_id = "'".$invoice_product_second[0]['sales_invoice_id']."'";
                            	$p_roll_box="'#p_roll_box'";
                                $p_roll_kgs="'#p_roll_kgs'";
                             // $marks=$package="";
                              	$first='false';
                                $product_gst_details=$this->getProductGST($invoice_product_second[0]['product_id']);
    								          //printr($product_gst_details);
                            if(is_numeric($invoice_product_second[0]['identification_marks']))
    								{
    								    foreach($invoice_product_second as $product){ 
    								          $mes_name=$this->getMeasurementName($product['measurement']);
    								      
    								       
                                                   if($product['product_id']!='68')
                		                    	    { 
                		                    	        
                                					   if($first=='false')
                                            				{
                                            				   $marks='KGS';
                                            				}
                                				    }else{
                                				       
                                				          $marks= $mes_name['measurement'];
                                				    }
                                             }
                                
    								}else{
    								      $marks=$package="";
    								}
                                
                                
                               
                  		        if($pdf!=0){
        			    	        $html.='<td class="no_border" >'. $invoice_product_second[0]['no_of_packages'].' '.$package.'</td>'; 
        			    	        $html.='<td class="no_border" >'. $invoice_product_second[0]['identification_marks'].' '.$marks.'</td>';
               				
                		            }else{
                					$html.='<td class="no_border" valign="top"><input type="text" name="roll_box" onchange="change_qty_per_kg('.$p_roll_id.',0,'.$p_roll_box.','.$invoice['invoice_status'].')" value="'.$invoice_product_second[0]['no_of_packages'].''.$package.'"  id="p_roll_box"></td>';
                   	                $html.='<td class="no_border" valign="top"><input type="text" name="roll_kgs" onchange="change_qty_per_kg('.$p_roll_id.',1,'.$p_roll_kgs.','.$invoice['invoice_status'].')" value="'. $invoice_product_second[0]['identification_marks'].''.$marks.'"  id="p_roll_kgs"></td>';
                		            }
                			$html.='<td class="no_border" ></td>';
                			$html.='<td class="no_border" ></td>';
                			$html.='<td class="no_border" ></td>';
        					    	 if($invoice['taxation']=='Out Of Gujarat'){
            		                	$html.='td class="no_border " colspan="2" valign="top"></td>';
            		                	$html.='td class="no_border " colspan="2" valign="top"></td>';
                    				   }else if($invoice['taxation']=='With in Gujarat'){
                    				       $html.='	<td class="no_border "  valign="top"></td>';
                    				       $html.='	<td class="no_border "  valign="top"></td>';
                    				       $html.='	<td class="no_border "  valign="top"></td>';
                    				       $html.='	<td class="no_border "  valign="top"></td>';
                    				      
                    				   }
        				$html.='<td class="no_border" ></td>';
        			$html.='</tr>';
                     $cgst_tax=$sgst_tax=$igst_tax=0;
	                    $t_cgst_tax=$t_sgst_tax=$t_igst_tax=$sub_total_with_gst=$total_with_tax=0;
                    foreach($invoice_product_second as $product){
                        $mes_name=$this->getMeasurementName($product['measurement']);
                        
                        if($product['product_id']==67)
                             $mes_name['measurement']='KGS';
                          
                          $total_qty=$total_qty+$product['qty'];
                        $sub_total=$sub_total+$product['qty']*$product['rate'];
                    	$html.='<tr style="font-size: 12px; " >
                            											
        				<td colspan="3" class="no_border"><strong>'.$product['product_name'].' '.$product['color_text'].' (SIZE: '.$product['size'].' '.$mes_name['measurement'].') </strong><br></td>';
        					$p_roll_id = "'".$invoice_product_second[0]['sales_invoice_id']."'";
        					$p_roll_box="'#p_roll_box'";
                            $p_roll_kgs="'#p_roll_kgs'";
                            $no_of_package='ROLL';
        			      	$html.='<td class="no_border" ></td>
            				    	<td class="no_border" ></td>';
        					$html.='<td class="no_border" valign="top"><p align="center">'.$product['qty'].' '.$mes_name['measurement'].'</p></td>';
        					$html.='<td class="no_border" valign="top"><p align="center">'.number_format($product['rate'],2).'</p></td>';
        					$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($product['qty']*$product['rate']),2).'</p></td>';
        				 if($invoice['taxation']=='Out Of Gujarat'){
            				     
            				 $igst_tax=((($product['qty']*$product['rate'])*$product_gst_details['igst_percentage'])/100);
            				 $t_igst_tax=$t_igst_tax+$igst_tax;
                				           $html.='	<td class="no_border" valign="top"><p align="center">'.$product_gst_details['igst_percentage'].'%</td>';
                				           $html.='	<td class="no_border" valign="top"><p align="center">'.$igst_tax.'</td>';
        				   }else if($invoice['taxation']=='With in Gujarat'){
        				         $cgst_tax=((($product['qty']*$product['rate'])*$product_gst_details['cgst_percentage'])/100);
        				         $t_cgst_tax=$t_cgst_tax+$cgst_tax;
        				         $sgst_tax=((($product['qty']*$product['rate'])*$product_gst_details['sgst_percentage'])/100);
        				         $t_sgst_tax=$t_sgst_tax+$sgst_tax;
        				           $html.='	<td class="no_border" valign="top"><p align="center">'.$product_gst_details['cgst_percentage'].'</td>';
        				           $html.='	<td class="no_border" valign="top"><p align="center">'.$cgst_tax.'</td>';
        				           $html.='<td class="no_border" valign="top"><p align="center">'.$product_gst_details['sgst_percentage'].'</td>';
        				           $html.='<td class="no_border" valign="top"><p align="center">'.$cgst_tax.'</td>';
        				   }
            				 
            			        $total_with_tax=(($product['qty']*$product['rate'])+$igst_tax+$cgst_tax+$sgst_tax);
            			        $sub_total_with_gst=$sub_total_with_gst+$total_with_tax;
            			    
            		//	printr($sub_total_with_gst.'=='.(($product['qty']*$product['rate'])+$igst_tax+$cgst_tax+$sgst_tax));
        				
        					$html.='<td class="no_border" valign="top"><p align="center">'.number_format((($product['qty']*$product['rate'])+$igst_tax+$cgst_tax+$sgst_tax),2).'</p></td>';
        			     
        			$html.='</tr>';
                    }
                      
                        
                    $Total_price=$sub_total+$invoice['tran_charges'];
                   
            		
                     $html .='<tr >  
                				
                					<td  colspan="3"><div align="right"><strong>Total..</strong></div></td>
                					<td >'.$invoice_product_second[0]['no_of_packages'].'</td>
                					<td >-</td>
                					<td >'.$total_qty.'</td>
                					<td ></td>
                					<td >'.$sub_total.'</td>';
                					 if($invoice['taxation']=='Out Of Gujarat'){
                				           $html.='	<td  align="center"  ></td>';
                				           $html.='	<td align="center"  >'.$t_igst_tax.'</td>';
                    				   }else if($invoice['taxation']=='With in Gujarat'){
                    				           $html.='	<td align="center"  ></td>';
                    				           $html.='	<td align="center"  >'.$t_cgst_tax.'</td>';
                    				           $html.='	<td  align="center"  ></td>';
                    				           $html.='<td  align="center"  >'.$t_sgst_tax.'</td>';
                    				   }
                				 $html .='	<td >'.$sub_total_with_gst.'</td>';
                				
                				
                				$html .='</tr>';	
                	
                				
                
                
                	$Total_price=$sub_total1=$sub_total_with_gst;
                     if($invoice['tran_charges']!='0')
                          {
                              $freight_with_tax=number_Format(((($freight_with_tax)*18)/100),2);
                              $Total_price=$freight_with_tax+$invoice['tran_charges'];
                          }
            
                }
                else{
                    //proforma
              
              $row=count($alldetails);
            //  printr($alldetails[0]['no_of_packages']);
              	$first='false';
                 $proforma_box1="'#proforma_box'";
        		 $total_qty=0;
        		 $sub_total=$sub_total1=0;
        		 
        		 //rowspan="'.$row.'"
        		 $Total_price=0;
              
        			$no='1';	
                    $cgst_tax=$sgst_tax=$igst_tax=0;
	                    $t_cgst_tax=$t_sgst_tax=$t_igst_tax=$sub_total_with_gst=$total_with_tax=0;
                    foreach($alldetails as $details){
                         $product_name=$color_text=$dimension='';  
                  //   printr($details); 
                   $proforma_kgs="'#proforma_kgs_".$details['sales_invoice_product_id']."'";
                     $proforma_box="'#proforma_box_".$details['sales_invoice_product_id']."'";
                    
                    
                      $product_code_details=$this->product_code_details($details['product_code_id']);
                      $product_gst_details=$this->getProductGST($details['product_id']);
                     
                 //    printr($details['product_id']);
                 //    printr($product_gst_details);
                      
                        $total_qty=$total_qty+$details['qty'];
                        $sub_total=$sub_total+$details['qty']*$details['rate'];
                  
                        $sub_total1=$sub_total1+$details['qty']*$details['rate'];
                           $html .='<tr class="pro_div" >
            				
            					<td  colspan="3" style="'.$style_oxi.'" >';
            			    	if($details['product_id']!='11' && $details['product_id']!='6' && $details['product_id']!='51' &&  $details['product_id']!='60' && $details['product_id']!='10' && $details['product_id']!='23' && $details['product_id']!='18' && $details['product_id']!='34'&& $details['product_id']!='47'&& $details['product_id']!='48' && $details['product_id']!='63'&& $details['product_id']!='72' && $details['product_id']!='37' && $details['product_id']!='38')
		                    	{
            					   if($first=='false')
                        				{
                        				     $html.='<b>PRINTED OR UNPRINTED FLEXIBLE PACKAGING MATERIAL OF POUCHES <br>HSN NO.39232990</b><br><br>';
                        				     $first = 'true';
                        				}
            					}
            					
            				if($details['product_id']=='6')
            			    	$kg='KGS';
            			    elseif($details['product_id']=='37' || $details['product_id']=='38' ) //VivekBhai's changes
            			        $kg='Pcs';
            				else
            				    $kg='NOS';
            			
            				$html.='<b>';  
            				if($details['product_id']=='11')
            					    $html.='Plastic Scoop- 39235090';
            					elseif($details['product_id']=='18')
            				    	$html.='Storezo - 39232100';
            				    elseif($details['product_id']=='10')
            				    	$html.='Mailer Bag - 39232990'; 
            				    elseif($details['product_id']=='35')
            				    	$html.='Tintie- 39232990';  
            				    elseif($details['product_id']=='6')
            				    	$html.='Supply in Rolls - 39201012';
            				    elseif($details['product_id']=='37')
            				    	$html.='<center>Oxygen Absorbers - 38249990 </center>';
            				    elseif($details['product_id']=='38')
            				    	$html.='<center>Silica Gel / Moisture Absorbers- 38249025 </center>';
            				    elseif($details['product_id']=='51')
            				    	$html.=' CYLINDER- 84425010'; 
            				    elseif($details['product_id']=='60')
            				    	$html.='DESIGN SERVICE- 998399 <br>';
            				     
            			$valve_name=$zipper_name=$acc_name=$spout_name='';    /*	
        				 if($details['valve']=='With Valve')
									$valve_name=$details['valve'];
						if($details['zipper_name']!='No zip')
					    	$zipper_name=$details['zipper_name'];
						if($details['spout_name']!='No Spout')
							$spout_name=$details['spout_name'];
					   if($details['product_accessorie_name']!='No Accessorie')	
							$acc_name=$details['product_accessorie_name'];*/
									
									
									
            				if($details['color_text']!='')
            					   $color_text='<b>'.$details['color_text'].'</b>';
            				/*else if($details['color_text']!='')
            				     $color_text='<b>'.$details['color_text'].'</b>';   $product_name='<b>Make of Pouch: </b>'.$details['product_name'].'<b>&nbsp;'.$zipper_name.'&nbsp;'.$valve_name.' &nbsp;'.$spout_name.' &nbsp;'.$acc_name.'</b><br>';*/
            				if($details['product_id']=='7' && $set_user_id=='6')
            				    $details['product_name']='Standing Pouch';
            			    if($details['product_name']!='' && $set_user_id!='39')
            			        $product_name='<b>Make of Pouch: </b>'.$details['product_name'].' <b>'.$valve_name.'</b><br>';
            			    
            				if($details['dimension']!='0.000x0.000x0.000')
            					   $dimension='<b>SIZE:</b>'.$details['dimension'].'<br>';
            			if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's changes
                        {
                            	$html .='<center></b>'.$product_name.''.$dimension.'<b>Description: </b>'.$details['description'].'  '.$color_text.'</center></td>
            					';
                        }else{
                            	$html .='</b>'.$product_name.''.$dimension.'<b>Description: </b>'.$details['description'].'  '.$color_text.'</td>
            					';
                        }
                        
            		
            				
            			/*	if($_SESSION['ADMIN_LOGIN_SWISS']=='1')
	                    	{
            				     if($pdf!=0){
                          		        $html.='<td class="no_border" align="center"  >'.$alldetails[0]['no_of_packages'].'</td>';
                          		        $html.='<td class="no_border" align="center" >'.$alldetails[0]['identification_marks'].''.$identification_marks.'</td>';
                          		    }else{
                          		        	$html.='<td class="no_border"  ><input type="text" name="proforma_box" onchange="change_qty_per_kg('.$details['sales_invoice_product_id'].',0,'.$proforma_box.',0)" value="'.$details['no_of_packages'].'" id="proforma_box_'.$details['sales_invoice_product_id'].'"></td>';
                                             $html.='<td class="no_border"  ><input type="text" name="proforma_kgs" onchange="change_qty_per_kg('.$details['sales_invoice_product_id'].',0,'.$proforma_kgs.',0)" value='.$details['identification_marks'].''.$identification_marks.'   id="proforma_kgs_'.$details['sales_invoice_product_id'].'"></td>';
                          		    }
	                    	}else{*/
	                    	    //$html .=$td.'<td '.$colspan.' class="no_border" style="'.$style_oxi.'" ></td>';
	                    	    if($no==1)
	                    	    {
    	                    	    if($pdf!=0){
                          		        $html.='<td class="no_border" align="center" style="'.$style_oxi.'"'.$colspan.' ><br>'.$alldetails[0]['no_of_packages'].'</td>';
                          		        $html.=$mark_value;
                          		    }else{
                          		        	$html.='<td class="no_border" '.$colspan.' align="center" style="'.$style_oxi.'" ><input type="text" name="proforma_box" onchange="change_qty_per_kg('.$invoice_no.',0,'.$proforma_box1.','.$invoice['invoice_status'].')" value="'.$alldetails[0]['no_of_packages'].'" id="proforma_box"></td>';
                                             $html.=$mark_value;
                          		    }
	                    	    }
	                    	    else
	                    	        $html .=$td.'<td '.$colspan.' class="no_border" style="'.$style_oxi.'" ></td>';
	                    /*	}*/
	                    
	                    
	                  
	                if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's changes
                            {
                                $html.='<td class="no_border" align="center" style="'.$style_oxi.' '.$font.'" ><br>'.number_format($details['qty'],'0').'</td>
            					<td class="no_border" align="center" style="'.$style_oxi.''.$font.'"  ><br>'.number_format($details['rate'],'2').'</td>
            					<td class="no_border" align="center" style="'.$style_oxi.''.$font.'"  ><br>'.number_format($details['qty']*$details['rate'],'2').'</td>';
                    			
                    			
                    				 if($invoice['taxation']=='Out Of Gujarat'){
                    				       $igst_tax=((($details['qty']*$details['rate'])*$product_gst_details['igst_percentage'])/100);
                				           $t_igst_tax=$t_igst_tax+$igst_tax;
                				           $html.='	<td class="no_border" align="center" style="'.$style_oxi.''.$font.'"  >'.$product_gst_details['igst_percentage'].'%</td>';
                				           $html.='	<td class="no_border" align="center" style="'.$style_oxi.''.$font.'"  >'.$igst_tax.'</td>';
                				   }else if($invoice['taxation']=='With in Gujarat'){
                				          $cgst_tax=((($details['qty']*$details['rate'])*$product_gst_details['cgst_percentage'])/100);
                				         $sgst_tax=((($details['qty']*$details['rate'])*$product_gst_details['sgst_percentage'])/100);
                				             $t_cgst_tax=$t_cgst_tax+$cgst_tax;
                				            $t_sgst_tax=$t_sgst_tax+$sgst_tax;
                				           $html.='	<td class="no_border" align="center" style="'.$style_oxi.''.$font.'"  >'.$product_gst_details['cgst_percentage'].'</td>';
                				           $html.='	<td class="no_border" align="center" style="'.$style_oxi.''.$font.'"  >'.$cgst_tax.'</td>';
                				           $html.='	<td class="no_border" align="center" style="'.$style_oxi.''.$font.'"  >'.$product_gst_details['sgst_percentage'].'</td>';
                				           $html.='	<td class="no_border" align="center" style="'.$style_oxi.''.$font.'"  >'.$sgst_tax.'</td>';
                				   }
            				 
            				
            				  $total_with_tax=(($details['qty']*$details['rate'])+$igst_tax+$cgst_tax+$sgst_tax);
            			        $sub_total_with_gst=$sub_total_with_gst+$total_with_tax;
            				 $html.='	<td  class="no_border" align="center"style="'.$style_oxi.''.$font.'" ><br>'.number_format((($details['qty']*$details['rate'])+$igst_tax+$cgst_tax+$sgst_tax),'2').'</td>
            				
            					</tr>';	
                            }else{
                                $html.='<td class="no_border" align="center" style="'.$style_oxi.'" >'.$details['qty'].' '.$kg.'</td>
            					<td class="no_border" align="center" style="'.$style_oxi.'"  >'.$details['rate'].'</td>
            					<td class="no_border" align="center" style="'.$style_oxi.'"  >'.round($details['qty']*$details['rate']).'</td>';
            			
            			
            				 if($invoice['taxation']=='Out Of Gujarat'){
            				     
            				       $igst_tax=((($details['qty']*$details['rate'])*$product_gst_details['igst_percentage'])/100);
            				     //  printr($igst_tax.'=='.$t_igst_tax);
            				       $t_igst_tax=$t_igst_tax+$igst_tax;
                				           $html.='	<td class="no_border" align="center" style="'.$style_oxi.'"  >'.$product_gst_details['igst_percentage'].'(%)</td>';
                				           $html.='	<td class="no_border" align="center" style="'.$style_oxi.'"  >'.$igst_tax.'</td>';
                				   }else if($invoice['taxation']=='With in Gujarat'){
                				         $cgst_tax=((($details['qty']*$details['rate'])*$product_gst_details['cgst_percentage'])/100);
                				         $t_cgst_tax=$t_cgst_tax+$cgst_tax;
                				         $sgst_tax=((($details['qty']*$details['rate'])*$product_gst_details['sgst_percentage'])/100);
                				         $t_sgst_tax=$t_sgst_tax+$sgst_tax;
                				           $html.='	<td class="no_border" align="center" style="'.$style_oxi.'"  >'.$product_gst_details['cgst_percentage'].'(%)</td>';
                				           $html.='	<td class="no_border" align="center" style="'.$style_oxi.'"  >'.$cgst_tax.'</td>';
                				           $html.='	<td class="no_border" align="center" style="'.$style_oxi.'"  >'.$product_gst_details['sgst_percentage'].'(%)</td>';
                				           $html.='<td class="no_border" align="center" style="'.$style_oxi.'"  >'.$cgst_tax.'</td>';
                				   }
            				
            			        $total_with_tax=(($details['qty']*$details['rate'])+$igst_tax+$cgst_tax+$sgst_tax);
            			      
            			  //      $t=(($details['qty']*$details['rate'])+$igst_tax+$cgst_tax+$sgst_tax);
            			     //   printr($t.'========='.number_format($t,3));
            			        $sub_total_with_gst=$sub_total_with_gst+$total_with_tax;
            			     
            			
            				 $html.='	<td  class="no_border" align="center"style="'.$style_oxi.'" >'.number_format((($details['qty']*$details['rate'])+$igst_tax+$cgst_tax+$sgst_tax),2).'</td>
            				
            					</tr>';	
                            }
            	    	$no++;		 	
                    }  
                    //printr(($sub_total*$invoice['discount'])/100);
                    if($invoice['discount']!='0')
                    {
                        $discount = ($sub_total*$invoice['discount'])/100;
                        $sub_total = $sub_total - $discount;
                    }
                      
                   //printr($sub_total);
                        $Total_price=$sub_total+$invoice['tran_charges'];
                        //echo $Total_price;
                       if($invoice['taxation']=='SEZ Unit No Tax'){
                         $html .='<tr >  
                    				
                    					<td class="no_border" colspan="3"><div align="right"><strong>"SUPPLY MENT FOR SEZ UNDERTACKING WITHOUT PAYMENT OF INTEGRATED TAX"'.$invoice['pallet_detail'].'</strong></div></td>
                    				    	<td  class="no_border" ></td>
                    				    	<td  class="no_border" ></td>
                    				    	<td  class="no_border" ></td>
                    				    	<td  class="no_border" ></td>
                    				    	<td  class="no_border" ></td>
                    				
                    					</tr>';	 
                       }
                       $freight_tax_cgst=$freight_tax_sgst=$freight_igst_tax=0;
                         if($invoice['tran_charges']!='0')
                          {
                              
                             
                    	  $html .='<tr >   
                    				 
                    					<td  colspan="3"><div  style="align:left;"><strong >FREIGHT  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HSN CODE: 996799  </strong></div></td>
                    					<td  align="center" '.$colspan.' ></td>
                    					
                    					<td  align="center" ></td>
                    					<td  align="center" ></td>';
                    					 if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='6')) // vivekbhai's changes
                                            {
                                    			 $html .='<td  align="center" ></td>';
                                            }
                    				  $html .='	<td align="center"  >'.$invoice['tran_charges'].'</td>';
                    				
                    				
                    					 if($invoice['taxation']=='Out Of Gujarat'){
                    					     $freight_igst_tax=number_Format(((($invoice['tran_charges'])*18)/100),2);  
                    					     $t_igst_tax=$t_igst_tax+$freight_igst_tax;  
                				           $html.='	<td  align="center"  >18</td>';
                				           $html.='	<td align="center"  >'.number_Format(((($invoice['tran_charges'])*18)/100),2).'</td>';
                    				   }else if($invoice['taxation']=='With in Gujarat'){
                    				      
                    				      
                    				         $freight_tax_cgst=number_Format(((($invoice['tran_charges'])*9)/100),2);
                    				         $freight_tax_sgst=number_Format(((($invoice['tran_charges'])*9)/100),2);
                    				        $t_cgst_tax=$t_cgst_tax+$freight_tax_cgst;
                    				        $t_sgst_tax=$t_sgst_tax+$freight_tax_sgst;
                    				         
                    				         
                    				         
                    				           $html.='	<td align="center"  >9</td>';
                    				           $html.='	<td align="center"  >'.$freight_tax_cgst.'</td>';
                    				           $html.='	<td  align="center"  >9</td>';
                    				           $html.='<td  align="center"  >'.$freight_tax_sgst.'</td>';
                    				   }
                    				 
                			            
                			           $html .='<td align="center" >'.round($invoice['tran_charges']+$freight_tax_cgst+$freight_tax_sgst+$freight_igst_tax).'</td>
                    				 
                    					</tr>';	 
                                       $sub_total_with_gst=$sub_total_with_gst+round($invoice['tran_charges']+$freight_tax_cgst+$freight_tax_sgst+$freight_igst_tax);
                     
                          }
                       
                    		
                    	if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's changes
                        {
                            $html .='<tr >  
                    				
                    					<td  colspan="3" style="font-size:13px;'.$font.'"><div align="right"><strong >TOTAL&nbsp;&nbsp;&nbsp;&nbsp;</strong></div></td>
                    					<td  align="center"  style="font-size:13px;'.$font.'" '.$colspan.' >'.$alldetails[0]['no_of_packages'].'</td>
                    					'.$mark_td.'
                    					<td  align="center" style="font-size:13px;'.$font.'" >'.$total_qty.' '.$kg.'</td>
                    					<td ></td>
                    					<td ></td>';
                    				if($invoice['taxation']=='Out Of Gujarat'){
                				           $html.='	<td  align="center" style="'.$style_oxi.'"  ></td>';
                				           $html.='	<td  align="center" style="'.$style_oxi.'"  >'.$t_igst_tax.'</td>';
                				   }else if($invoice['taxation']=='With in Gujarat'){
                				           $html.='	<td  align="center" style="'.$style_oxi.'"  ></td>';
                				           $html.='	<td  align="center" style="'.$style_oxi.'"  >'.$t_cgst_tax.'</td>';
                				           $html.='	<td  align="center" style="'.$style_oxi.'"  ></td>';
                				           $html.='<td " align="center" style="'.$style_oxi.'"  >'.$t_sgst_tax.'</td>';
                				   }
                    				 $html .='	<td  align="center" >'.$sub_total_with_gst.'</td>
                    				
                    					</tr>';
                        }else{
                            $html .='<tr >  
                    				
                    					<td  colspan="3"><div align="right"><strong>Total..</strong></div></td>
                    					<td  align="center" '.$colspan.' >'.$alldetails[0]['no_of_packages'].'</td>
                    					'.$mark_td.'
                    					<td  align="center" >'.$total_qty.' '.$kg.'</td>
                    					<td ></td>
                    					<td align="center"  >'.($sub_total+$invoice['tran_charges']).'</td>';
                    					 if($invoice['taxation']=='Out Of Gujarat'){
                				           $html.='	<td  align="center"  ></td>';
                				           $html.='	<td align="center"  >'.$t_igst_tax.'</td>';
                    				   }else if($invoice['taxation']=='With in Gujarat'){
                    				           $html.='	<td align="center"  ></td>';
                    				           $html.='	<td align="center"  >'.$t_cgst_tax.'</td>';
                    				           $html.='	<td  align="center"  ></td>';
                    				           $html.='<td  align="center"  >'.$t_sgst_tax.'</td>';
                    				   }
                    			 $html .='<td align="center"  >'.$sub_total_with_gst.'</td>
                    				 
                    					</tr>';
                        }
                    	
                  
                    	$Total_price=$sub_total_with_gst;
                      
                    } 
            } //end
            if($list == '0')
            {
                //printr($Total_price);
               /*  if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39'))
                    {
                        
                    }*/
                
                  if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's changes
                  {
                       $html.='<tr style="font-size: 11px;" >
                 			<td colspan="5" rowspan="1" valign="top">
                 			<table border="'.$border.'" width="100%" >
                 			    '.$table_tr.'
                 			    <tr><td style="'.$font.'"><b>'.$number=$this->convert_number_new(round($Total_price)).' Only.</b></td></tr>
                 			</table>';
                  }else{ 
                       $html.='<tr style="font-size: 9px;" >
                 			<td colspan="3" rowspan="1" valign="top">
                 			<table border="'.$border.'" width="100%" >
                 			    '.$table_tr.'
                 			    <tr><td ><b>'.$number=$this->convert_number_new(round($Total_price)).' Only.</b></td></tr>
                 			</table>';
                  }			 
        			
                    if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's changes
                    {
                        $html.='</td>
                   			<td colspan="'. ($td_colspan+3).'" rowspan="1" valign="top" ></p>';
                    }else{
                        $html.='</td>
                   			<td colspan="'. ($td_colspan+5).'" rowspan="1" valign="top"></p>';
                    } 
                                    $tran_charges=0; 
                    
                               			if($invoice['invoice_status']!='1' && $invoice['invoice_status']!='2'){
                               			     	if(($invoice['transport'] == 'sea' ) || ($invoice['transport'] == 'air' && $invoice['igst_status']==1)){
                               			     	    
                                 		          $final_amount_t=$final_amt_with_tax;  
                                 		             if($invoice['tran_charges']=='0.00' && $invoice['extra_tran_charges']!='0.00')
                                         		        {
                                         		            $tran_charges = ($invoice['tran_charges']+$invoice['extra_tran_charges']+$insurance)*$invoice['currency_rate'];
                                         		        }else{
                                         		            $tran_charges= ($invoice['tran_charges']+$invoice['extra_tran_charges']+$insurance)*$invoice['currency_rate'];
                                         		        }
                                    											
                                               
                                 		        }else{
                                 		            
                                 		         //   printr($final_amount);
                                 		          $final_amount_t=($final_amount*$invoice['currency_rate']);   
                                 		        }
                               			    //invoice
                                 		 	$html.='  <table style=" width: 100%; ">
                                 		        <tr>
                                     		        <th valign="top" style="'.$font.'"><div align="left">SUB TOTAL</b></th>
                                     		        <th valign="top"></th>
                                     		        <th valign="top"style="'.$font.'"><div align="right">'.number_Format ($final_amount_t,2).'</th>
                                 		        </tr>';
                                 		        //printr($invoice['discount']);
                                 		        
                                 		    /* if($invoice['transport'] == 'air' && $invoice['igst_status']==1){
                                     		      	 $html.='<tr>
                                             		            <td><div align="left">IGST</td>
                                                 		        <td>18%</td>
                                                 		        <td><div align="right">'.number_Format(((($final_amount*$invoice['currency_rate'])*12)/100),2).'</td>
                                             		        </tr>';
                                 		      }
*/
                                 		        
                                 		        if($invoice['tran_charges']!='0')
                                 		        {
                                 		            
                                     		       $html.=' <tr>
                                         		            <td ><div align="left">FREIGHT CH.</td>
                                             		        <td></td>
                                             		        <td><div align="right">'.number_Format(($invoice['tran_charges']+$invoice['extra_tran_charges']+$insurance)*$invoice['currency_rate'],2).'</td>
                                         		        </tr>';
                                 		        }else  if($invoice['tran_charges']=='0.00' && $invoice['extra_tran_charges']!='0.00')
                                 		        {
                                     		       $html.=' <tr>
                                         		            <td ><div align="left">FREIGHT CH.</td>
                                             		        <td></td>
                                             		        <td><div align="right">'.number_Format(($invoice['extra_tran_charges']+$insurance)*$invoice['currency_rate'],2).'</td>
                                         		        </tr>';
                                 		        }
                                 		        
                                 		        
                                 		      /*  if($invoice['transport'] == 'sea')
                                 		        {
                                 		             $html.='<tr>
                                         		            <td><div align="left">IGST</td>
                                             		        <td>18%</td>
                                             		        <td><div align="right">'.number_Format(((($final_amount*$invoice['currency_rate'])*12)/100),2).'</td>
                                         		        </tr>';
                                 		        }*/
                                 		        $html.='<tr>
                                     		            <th><div align="left">ROUND OFF</th>
                                         		        <th></th>
                                         		        <th><div align="right">'.number_Format((round($final_amt_with_tax+$tran_charges) - ($final_amt_with_tax+$tran_charges)),2).'</th>
                                     		        </tr>';
                                     		        
                             		             $html.='<tr>
                                     		            <th width="50%"><div align="left">GRAND TOTAL</th>
                                         		        <th></th>
                                         		        <th width="50%"><div align="right">'.round($final_amt_with_tax+$tran_charges).'</th>
                                     		        </tr>';
                                 		        
                                 		   	$html.=' </table>';
                                 		   	$Total_price=$final_amt_with_tax+$tran_charges;
                               			}else{ 
                               			    //proforma
                               			    if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39')) // vivekbhai's Change
                                            {
                                                    $html.=' <table style=" width: 100%;">
                                     		        <tr>
                                         		        <td valign="top" style="font-size:12px;'.$font.'"><div align="left">SUB TOTAL</b></td>
                                         		        <td valign="top" style="'.$font.'"></td>
                                         		        <td valign="top"style="font-size:12px;'.$font.'"><div align="right">'.number_Format($Total_price,2).'</td>
                                     		        </tr>';
                                     		        
                                 		     
                                     		       
                                     		       
                                     		         $html.='<tr>
                                         		            <td><div align="left" style="font-size:12px;'.$font.'">ROUND OFF</td>
                                             		        <td></td>
                                             		        <td><div align="right" style="font-size:12px;'.$font.'">'.number_Format((round($Total_price) - $Total_price),2).'</td>
                                         		        </tr>';
                                 		             $html.='<tr>
                                         		            <td width="60%" style="font-size:12px;'.$font.'"><div align="left">GRAND TOTAL</td> 
                                             		        <td></td>
                                             		        <td width="40%" style="font-size:12px;'.$font.'"><div align="right">'.round($Total_price).'</td>
                                         		        </tr>';
                                     		        
                                     		   	$html.=' </table>';
                                            }else{
                                                    $html.=' <table style=" width: 100%; ">
                                     		        <tr> 
                                         		        <th valign="top"><div align="left">SUB TOTAL</b></th>
                                         		        <th valign="top"></th>
                                         		        <th valign="top"><div align="right">'.number_Format($sub_total_with_gst,2).'</th>
                                     		        </tr>';
                                     		        if($invoice['discount']!='0')
                                     		        {
                                     		            //$discount = ($sub_total*$invoice['discount'])/100;
                                         		       $html.=' <tr>
                                             		            <td ><div align="left">Discount ('.($invoice['discount'] + 0).' %)</td>
                                                 		        <td></td>
                                                 		        <td><div align="right">'.number_Format($discount,2).'</td>
                                             		        </tr>
                                             		        <tr>
                                                 		        <th valign="top"><div align="left">SUB TOTAL</b></th>
                                                 		        <th valign="top"></th>
                                                 		        <th valign="top"><div align="right">'.number_Format($sub_total,2).'</th>
                                             		        </tr>';
                                             		    
                                     		        }
                                     		       
                                     		         
                                     		         $html.='<tr>
                                         		            <th><div align="left">ROUND OFF</th>
                                             		        <th></th>
                                             		        <th><div align="right">'.number_Format((round($Total_price) - $Total_price),2).'</th>
                                         		        </tr>';
                                 		             $html.='<tr>
                                         		            <th><div align="left">GRAND TOTAL</th> 
                                             		        <th></th>
                                             		        <th><div align="right">'.round($Total_price).'</th>
                                         		        </tr>';
                                     		        
                                     		   	$html.=' </table>';
                                            }
                               			    
                               			}
        				$html.='</td>
                 		</tr>';
                 		
                 	if($invoice['invoice_status']!=0)	{ 
                 	 $product_total_gst_details=$this->getTotalGSTdetails($invoice_no,$invoice['taxation']);
                 	 
                 //	 printr($product_total_gst_details[0]);
                 	    if($product_total_gst_details[0]['total_gst_amt']!='0.00'){
          
            	$html.='<tr style="font-size: 12px;">
                 		<td colspan="'. ($td_colspan+8).'">
                 		
                 		<table style=" width: 100%; " border="1">
                                <tr>
                                       <th  align="right" rowspan="2" style="vertical-align: top;">Sr no</th>
                                        <th   rowspan="2" style="vertical-align: top;">Taxable Value</th>';
                                        if($invoice['taxation']=='Out Of Gujarat'){
                    				       $html.='<th colspan="2" style="vertical-align: top;">IGST</th>';
                        				 }else if($invoice['taxation']=='With in Gujarat'){
                        				        $html.='<th colspan="2" style="vertical-align: top;">CGST</th>';
                        				        $html.='<th colspan="2" style="vertical-align: top;">SGST</th>'; 
                        			    }
                                        $html.=' <th   rowspan="2" style="vertical-align: top;">Total Tax Amount</th>
                                </tr>   
                                
                                <tr>';
                              
                                   
                                    if($invoice['taxation']=='Out Of Gujarat'){
                				       $html.='<th style="vertical-align: top;">Rate (%)</th>';
                				       $html.='<th style="vertical-align: top;" >Amount</th>';
                				   }else if($invoice['taxation']=='With in Gujarat'){
                				          $html.='<th style="vertical-align: top;">Rate (%)</th>';
                				          $html.='<th style="vertical-align: top;">Amount</th>';
                				          $html.='<th style="vertical-align: top;" >Rate (%)</th>';
                				          $html.='<th style="vertical-align: top;">Amount</th>';
                				   }	
                          
                             $html.='</tr>';
                     
                    
                            $total_with_tax_amt=$total_sgst=$total_igst=$total_cgst=$total_basic_amt= $fre_tax_sgst=$fre_tax_cgst=$fre_tax_igst=$fre_tax_amt=0;
                           $i=1;
                            foreach($product_total_gst_details as $total_gst){
                              
                           //   $hsn_code=$this->getHSNCodeDetails($total_gst['product'],$total_gst['igst'],$total_gst['cgst']);
                            
                         //   printr($total_gst);
                            
                               $html.='<tr>'; 
                         
                        
                            
                            
                                 $html.='<td align="right" style="vertical-align: top;">'.$i.'</td>';
                                  $html.='<td style="vertical-align: top;">'.($total_gst['basic_amt']+$fre_tax_amt).'</td>';
                                 
                                    if($invoice['taxation']=='Out Of Gujarat'){
                                        
                                   
                                                
                                                
                                                  $total_with_tax=$total_gst['basic_amt']+$total_gst['total_gst_amt']+$fre_tax_igst;
                                       
                                       
                                     
                				       $html.='<td style="vertical-align: top;">'.$total_gst['igst'].'</td>';
                				       $html.='<td style="vertical-align: top;" >'.($total_gst['total_gst_amt']+$fre_tax_igst).'</td>';
                				       
                				         $total_igst=$total_igst+($total_gst['total_gst_amt']+$fre_tax_cgst);    
                				         $total_with_tax_amt=$total_with_tax_amt+$fre_tax_amt+$total_with_tax;
                				         $total_basic_amt=$total_basic_amt+$total_gst['basic_amt'];
                				   
                                        
                                        
                                    }else if($invoice['taxation']=='With in Gujarat'){
                				    
                                                $total_with_tax=$total_gst['basic_amt']+$total_gst['total_gst_amt']+$total_gst['total_gst_amt']+$fre_tax_sgst+$fre_tax_cgst;   
                                           
                                      
                				       
                				          $html.='<td style="vertical-align: top;">'.$total_gst['cgst'].'</td>';
                				          $html.='<td style="vertical-align: top;">'.($total_gst['total_gst_amt']+$fre_tax_cgst).'</td>';
                				          $html.='<td style="vertical-align: top;" >'.$total_gst['sgst'].'</td>';
                				          $html.='<td style="vertical-align: top;">'.($total_gst['total_gst_amt']+$fre_tax_sgst).'</td>';
                				           
                				           
                				                  $total_cgst=+$total_cgst+($total_gst['total_gst_amt']+$fre_tax_cgst);
                                                  $total_sgst=+$total_cgst+($total_gst['total_gst_amt']+$fre_tax_sgst);
                				                  $total_basic_amt=$total_basic_amt+$total_gst['basic_amt'];
                				                  $total_with_tax_amt=$total_with_tax_amt+$total_with_tax;
                				   }	
                                $html.='<td>'.number_Format($total_with_tax,'2').'</td>';
                             $html.='</tr>'; 
                           $i++;
                            }
                               if($invoice['tran_charges']!=0){
                                     $fre_tax_igst=((($invoice['tran_charges'])*18)/100);
                              $html.='<tr>
                                         <td align="right"><b>FREIGHT  HSN CODE: 996799:</b></td>
                                         <td>'.$invoice['tran_charges'].'</td>';
                                       if($invoice['taxation']=='Out Of Gujarat'){
                                           
                                             
                				            $html.='<td style="vertical-align: top;">18</td>';
                				            $html.='<td style="vertical-align: top;" >'.$fre_tax_igst.'</td>';
                				             $total_igst=$total_igst+$fre_tax_igst;   
                				               $total_with_tax_amt=$total_with_tax_amt+$invoice['tran_charges']+$fre_tax_igst;              
                				     }else if($invoice['taxation']=='With in Gujarat'){
                				           $fre_tax_cgst=((($invoice['tran_charges'])*9)/100);
                                                  $fre_tax_sgst=((($invoice['tran_charges'])*9)/100);
                                                  
                                                  $total_cgst=+$total_cgst+$fre_tax_sgst;
                                                  $total_sgst=+$total_cgst+$fre_tax_sgst;
                				          $html.='<td style="vertical-align: top;">9</td>';
                				          $html.='<td style="vertical-align: top;">'.$fre_tax_cgst.'</td>';
                				          $html.='<td style="vertical-align: top;" >9</td>';
                				          $html.='<td style="vertical-align: top;">'.$fre_tax_sgst.'</td>';
                			                    
                			         $total_with_tax_amt=$total_with_tax_amt+$invoice['tran_charges']+$fre_tax_sgst+$fre_tax_cgst;             
                			
                				   }
                				   
                				 
                				     $total_basic_amt=$total_basic_amt+$invoice['tran_charges'];
                				     
                				 
                				    
                                $html.='<td>'.number_Format(($invoice['tran_charges']+$fre_tax_sgst+$fre_tax_cgst),'2').'</td>';
                                
                              
                             $html.='</tr>';  
                               }
                            $html.='<tr>
                                         <td align="right"><b>Total:</b></td>
                                         <td>'.$total_basic_amt.'</td>';
                                       if($invoice['taxation']=='Out Of Gujarat'){
                				            $html.='<td style="vertical-align: top;"></td>';
                				            $html.='<td style="vertical-align: top;" >'.$total_igst.'</td>';
                				     }else if($invoice['taxation']=='With in Gujarat'){
                				          $html.='<td style="vertical-align: top;"></td>';
                				          $html.='<td style="vertical-align: top;">'.$total_cgst.'</td>';
                				          $html.='<td style="vertical-align: top;" ></td>';
                				          $html.='<td style="vertical-align: top;">'.$total_cgst.'</td>';
                				   }	
                                $html.='<td>'.number_Format($total_with_tax_amt,'2').'</td>';
                             $html.='</tr>'; 
                            
                 	  $html.='	 </table>
                 		 </td>
        						
                     			
                    			
        						
        				</tr>';
        				
                 	   }
                 	}
                 		$html.='<tr style="font-size: 12px;">
                 		<td colspan="'. ($td_colspan+8).'">
                 		
                 		<table style=" width: 100%; ">
                         '.$table_desc.'
                 		 </table>
                 		 </td>
        						
                     			
                    			
        						
        				</tr>
        				
        	    
        		   </table>
        			 <div class="form-group">
        			 <div class="col-lg-9 col-lg-offset-3">';
        			 $html.='</div>
        			 </div>
        		 </div>
        		</div>
        	  </div>';
        	  
        	  
	    }
	    
	        	$sql = "UPDATE " . DB_PREFIX . "government_sales_invoice SET invoice_total_amount = '" .round($Total_price). "' WHERE sales_invoice_id='".$invoice_no."'";
	        	$data=$this->query($sql);
	        //	printr($html);die;
		return $html;
		
		
    }
    public function getProductGST($product_id){
 //    printr("SELECT * FROM  product_gst_master WHERE product_id LIKE '%".$invoice_id."%'  AND is_delete=0  AND status=1");
        $data = $this->query("SELECT * FROM  product_gst_master WHERE FIND_IN_SET('".$product_id."',product_id) AND is_delete=0  AND status=1");
	
		if($data->num_rows){
		
			return $data->row;
		}else{
			return false;
		}
        
    }  
    public function getHSNCodeDetails($pro_id,$igst,$cgst){
   
   
   
   //  printr("SELECT GROUP_CONCAT(hsn_code)as code FROM  product_gst_master WHERE  is_delete=0 AND( igst_percentage='".$igst."'OR cgst_percentage='".$cgst."') AND status=1");die;
     
      
        $data = $this->query("SELECT GROUP_CONCAT(hsn_code)as code FROM  product_gst_master WHERE  is_delete=0 AND( igst_percentage='".$igst."'OR cgst_percentage='".$cgst."') AND status=1");
	
		if($data->num_rows){
		
			return $data->row['code'];
		}else{
			return false;
		}
        
    } 
    public function getTotalGSTdetails($sales_invoice_id,$taxation){
         
      //   printr($taxation);
         if($taxation=='Out Of Gujarat'){ 
               $sql="SELECT sum(amt_igst) as total_gst_amt,SUM(qty*rate) as basic_amt,igst,sgst,cgst,taxation,GROUP_CONCAT(product_id)as product FROM `government_sales_invoice_product` WHERE `sales_invoice_id` = '".$sales_invoice_id."' AND is_delete=0 GROUP By igst";
		   }else if($taxation=='With in Gujarat'){
		       $sql="SELECT sum(amt_cgst) as total_gst_amt,SUM(qty*rate) as basic_amt,cgst,igst,sgst,taxation,GROUP_CONCAT(product_id)as product FROM `government_sales_invoice_product` WHERE `sales_invoice_id` = '".$sales_invoice_id."' AND is_delete=0 GROUP By sgst";
		         
		   }
		    
	//	 printr($sql);die; 

		$data = $this->query($sql);
      
      	//printr($data);
        if($data->num_rows){
		
			return $data->rows; 
		}else{
			return false;
		}
       
    }
    
    
	public function getTotalProformaInvoice($filter_data=array(), $status, $proforma_status,$user_id,$user_type_id,$is_delete,$add_book_id='0',$permission=0,$customer_followups=0)

		{
			
			//add sonu
				$add_id='';
				if($add_book_id!=0)
				$add_id = "AND p.address_book_id='". $add_book_id."'";
			    $customer_follow='';
				if($customer_followups!=0)
				$customer_follow = " AND government_sales_status=0 AND 	gen_sales_status=0 AND p.invoice_date <= NOW() - INTERVAL 20 DAY  ";
			
			
			//end
		    //printr($filter_data);die;
            
            
			if($user_type_id==1 && $user_id==1)

			{

				$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c WHERE c.country_id=p.destination AND p.is_delete = '".$is_delete."' $add_id $customer_follow " ;
                
                if(!empty($filter_data['product_code']))
					$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c,proforma_invoice_product_code_wise as pi WHERE c.country_id=p.destination AND p.proforma_id = pi.proforma_id AND p.is_delete = '".$is_delete."' $add_id $customer_follow " ;


			}

			else

    		{
                
    			if($user_type_id == 2){
                
                    
                    $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
    
    				if($user_type_id==2 && ($user_id=='52' || $user_id=='204' || $user_id=='145' || $user_id=='91'))
    				    $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id'],$permission);
                    else
                       $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']); 
    				
    				$set_user_id = $parentdata->row['user_id'];
    
    				$set_user_type_id = $parentdata->row['user_type_id'];
                    
                    if($permission==1)
    				    $per = " p.added_by_user_id IN (6,37,38,39) ";
    				else
    				    $per = "p.added_by_user_id='".$set_user_id."'";
                    
    			}else{
    
    				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
    
    				$set_user_id = $user_id;
    
    				$set_user_type_id = $user_type_id;
                    
                    
                    $per = "p.added_by_user_id='".$set_user_id."'";
    			}
    
    			$str = '';
    
    			if($userEmployee){
    
    				$str = ' OR ( p.added_by_user_id IN ('.$userEmployee.') AND p.added_by_user_type_id = 2 )';
    
    			}
    
    				
    
    				$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c WHERE c.country_id=p.destination   AND ( $per  AND p.added_by_user_type_id='".$set_user_type_id."' $str) AND p.is_delete = '".$is_delete."' $add_id  $customer_follow" ;
    
    				 if(!empty($filter_data['product_code']))
					       $sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c,proforma_invoice_product_code_wise as pi WHERE c.country_id=p.destination AND p.proforma_id= pi.proforma_id  AND ( $per  AND p.added_by_user_type_id='".$set_user_type_id."' $str) AND p.is_delete = '".$is_delete."' $add_id $customer_follow " ;

    
    			}

				if($status >= '0') {

				$sql .= " AND p.status ='".$status."' ";

			}

			if($proforma_status >= '0') {

				$sql .= " AND proforma_status ='".$proforma_status."' ";

			}
            if(empty($filter_data))
                $sql .= " AND YEAR(p.invoice_date) = '2019' ";
         //AND p.gen_sales_status='0'
            
			if(!empty($filter_data)){
                
                
                
				if(!empty($filter_data['customer_name'])){

					$sql .= " AND customer_name LIKE '%".addslashes($filter_data['customer_name'])."%' ";		

				}

				if(!empty($filter_data['email'])){

					$sql .= " AND email LIKE '%".$filter_data['email']."%' ";		

				}

				if(!empty($filter_data['invoice_number'])){

					$sql .= " AND pro_in_no LIKE '%".$filter_data['invoice_number']."%' ";		

				}
				if(!empty($filter_data['invoice_amount'])){

					$sql .= " AND invoice_total LIKE '%".$filter_data['invoice_amount']."%' ";		

				}
				if(!empty($filter_data['contact_no'])){

					$sql .= " AND ( contact_no LIKE '%".$filter_data['contact_no']."%' OR address_info  LIKE '%".$filter_data['contact_no']."%' ) ";		

				}
				
				if(!empty($filter_data['postedby']))

				{

					$spitdata = explode("=",$filter_data['postedby']);

					$sql .="AND p.added_by_user_type_id = '".$spitdata[0]."' AND p.added_by_user_id = '".$spitdata[1]."'";

				}
                if(!empty($filter_data['buyers_no'])){

					$sql .= " AND p.buyers_order_no = '".$filter_data['buyers_no']."' ";		

				}
                if(!empty($filter_data['product_code'])){

					$sql .= " AND pi.product_code_id = '".$filter_data['product_code']."' GROUP By p.proforma_id ";		

				}
	        
			}
				$data = $this->query($sql);

			return $data->num_rows;
//printr ($sql);//die;
		

		}
		public function getProformaInvoices($data,$filter_data=array(), $status, $proforma_status,$user_id,$user_type_id,$is_delete,$add_book_id = '0',$permission=0,$customer_followups=0){	
		
		
		//sonu add 21-4-2017
			$add_id='';
			if($add_book_id!=0)
				$add_id = "AND p.address_book_id='". $add_book_id."'";
			//END	
		
	    	$customer_follow='';
				if($customer_followups!=0)
				$customer_follow = "  AND government_sales_status=0  AND 	gen_sales_status=0 AND p.invoice_date <= NOW() - INTERVAL 20 DAY  ";
		
		

			if($user_type_id==1 && $user_id==1)

			{

				$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c WHERE c.country_id=p.destination AND p.is_delete = '".$is_delete."' $add_id $customer_follow " ;
                if(!empty($filter_data['product_code']))
					$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c,proforma_invoice_product_code_wise as pi WHERE c.country_id=p.destination AND p.proforma_id = pi.proforma_id AND p.is_delete = '".$is_delete."' $add_id  $customer_follow" ;

			}

			else

    		{
    
    				if($user_type_id == 2){
    
    				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
    
    				if($user_type_id==2 && ($user_id=='52' || $user_id=='204' || $user_id=='145' || $user_id=='91'))
    				    $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id'],$permission);
                    else
                       $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
    
    				$set_user_id = $parentdata->row['user_id'];
    
    				$set_user_type_id = $parentdata->row['user_type_id'];
    				
    				if($permission==1)
    				    $per = " p.added_by_user_id IN (6,37,38,39) ";
    				else
    				    $per = "p.added_by_user_id='".$set_user_id."'";
    
    			}else{
    
    				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
    
    				$set_user_id = $user_id;
    
    				$set_user_type_id = $user_type_id;
                    
                    $per = "p.added_by_user_id='".$set_user_id."'";
    			}
    
    			$str = '';
    
    			if($userEmployee){
    
    				$str = ' OR ( p.added_by_user_id IN ('.$userEmployee.') AND  p.added_by_user_type_id = 2 )';
    
    			}
    
    				$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c WHERE c.country_id=p.destination AND ( $per AND p.added_by_user_type_id = '".$set_user_type_id."' $str) AND p.is_delete = '".$is_delete."' $add_id $customer_follow " ;
                    if(!empty($filter_data['product_code']))
					       $sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c,proforma_invoice_product_code_wise as pi WHERE c.country_id=p.destination AND p.proforma_id= pi.proforma_id AND ( $per  AND p.added_by_user_type_id='".$set_user_type_id."' $str) AND p.is_delete = '".$is_delete."' $add_id $customer_follow " ;

    		}

			if($status >= '0') {

				$sql .= " AND p.status ='".$status."' ";

			}

			if($proforma_status >= '0') {

				$sql .= " AND proforma_status ='".$proforma_status."' ";

			}
         if(empty($filter_data))
        	$sql .= " AND YEAR(p.invoice_date) = '2019'  ";
            	//AND p.gen_sales_status='0'
    
			if(!empty($filter_data)){

				if(!empty($filter_data['customer_name'])){

					$sql .= " AND customer_name LIKE  '%".addslashes($filter_data['customer_name'])."%'";		

				}

				if(!empty($filter_data['email'])){

					$sql .= " AND email LIKE '%".$filter_data['email']."%' ";		

				}

				if(!empty($filter_data['invoice_number'])){

					$sql .= " AND pro_in_no LIKE '%".$filter_data['invoice_number']."%' ";		

				}
				if(!empty($filter_data['invoice_amount'])){

					$sql .= " AND invoice_total LIKE '%".$filter_data['invoice_amount']."%' ";		

				}
				if(!empty($filter_data['contact_no'])){

					$sql .= " AND ( contact_no LIKE '%".$filter_data['contact_no']."%' OR address_info  LIKE '%".$filter_data['contact_no']."%' ) ";		

				}

				if(!empty($filter_data['postedby']))

				{

					$spitdata = explode("=",$filter_data['postedby']);

					$sql .="AND p.added_by_user_type_id = '".$spitdata[0]."' AND p.added_by_user_id = '".$spitdata[1]."'";

				}
				if(!empty($filter_data['buyers_no'])){

					$sql .= " AND p.buyers_order_no = '".$filter_data['buyers_no']."' ";		

				}
				if(!empty($filter_data['product_code'])){

					$sql .= " AND pi.product_code_id = '".$filter_data['product_code']."' ";		

				}

			}
            if(!empty($filter_data['product_code']))
                $sql .= " GROUP By p.proforma_id ";
                
			if (isset($data['sort'])) {

				$sql .= " ORDER BY " . $data['sort'];	

			} else {

				$sql .= " ORDER BY proforma_id";	

			}

			if (isset($data['order']) && ($data['order'] == 'DESC')) {

				$sql .= " DESC";

			} else {

				$sql .= " ASC";

			}

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
   
}


  
?>


