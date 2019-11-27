<?php
//[sonu ]
class sales_invoice_report extends dbclass{
	public function get_sheet($f_date,$t_date,$m_value,$full_inv=0,$user='')
	{
		 //[kinjal] : updated function on 21-12-2016	
		if($user=='')
		{
    		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
    		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];	
		}
		else
		{
		    $explode=explode("=",$user);
		    $user_id = $explode[1];
    		$user_type_id = $explode[0];
		}
		$user_info = $this->getUser($user_id,$user_type_id);
	    if($m_value == 1)
		{ 
			$datef=date_create($f_date);		
			date_sub($datef,date_interval_create_from_date_string("1 month"));				
		  	$dateto=date_create($t_date);		
			date_sub($dateto,date_interval_create_from_date_string(" 1 month"));
			
		    $mdate="AND s2.invoice_date <= '".date_format($dateto,"Y-m-d")."' AND s2.invoice_date >= '".date_format($datef,"Y-m-d")."'";
				$dateyf=date_create($f_date);
				date_sub($dateyf,date_interval_create_from_date_string("12 month "));
				$dateyto=date_create($t_date);
				date_sub($dateyto,date_interval_create_from_date_string(" 12 month"));			
				$ydate="AND s1.invoice_date <= '".date_format($dateyto,"Y-m-d")."' AND s1.invoice_date >= '".date_format($dateyf,"Y-m-d")."'";
		}
		else if($m_value == 2)
		{
			$datef=date_create($f_date);
			date_sub($datef,date_interval_create_from_date_string("3 month"));			
		  	$dateto=date_create($t_date);
			date_sub($dateto,date_interval_create_from_date_string(" 3 month"));			
		    $mdate="AND s2.invoice_date <= '".date_format($dateto,"Y-m-d")."' AND s2.invoice_date >= '".date_format($datef,"Y-m-d")."'";
				$dateyf=date_create($f_date);
				date_sub($dateyf,date_interval_create_from_date_string("12 month "));		
				$dateyto=date_create($t_date);
				date_sub($dateyto,date_interval_create_from_date_string(" 12 month"));			
				$ydate="AND s1.invoice_date <= '".date_format($dateyto,"Y-m-d")."' AND s1.invoice_date >= '".date_format($dateyf,"Y-m-d")."'";
			
		}
		else if($m_value == 3)
		{
			$datef=date_create($f_date);
			date_sub($datef,date_interval_create_from_date_string(" 12 month"));		
		  	$dateto=date_create($t_date);
			date_sub($dateto,date_interval_create_from_date_string(" 12 month"));			
		    $mdate="AND s2.invoice_date <= '".date_format($dateto,"Y-m-d")."' AND s2.invoice_date >= '".date_format($datef,"Y-m-d")."'";
				$dateyf=date_create($f_date);
		    	date_sub($dateyf,date_interval_create_from_date_string("12 month "));			
				$dateyto=date_create($t_date);
				date_sub($dateyto,date_interval_create_from_date_string(" 12 month"));		
				$ydate="AND s1.invoice_date <= '".date_format($dateyto,"Y-m-d")."' AND s1.invoice_date >= '".date_format($dateyf,"Y-m-d")."'";
			
		}
		else if($m_value == 4)
		{
			$datef=date_create($f_date);
			date_sub($datef,date_interval_create_from_date_string("6 month"));		
		  	$dateto=date_create($t_date);
			date_sub($dateto,date_interval_create_from_date_string(" 6 month"));		
		    $mdate="AND s2.invoice_date <= '".date_format($dateto,"Y-m-d")."' AND s2.invoice_date >= '".date_format($datef,"Y-m-d")."'";
				$dateyf=date_create($f_date);
				date_sub($dateyf,date_interval_create_from_date_string("12 month "));		
				$dateyto=date_create($t_date);
				date_sub($dateyto,date_interval_create_from_date_string(" 12 month"));		
				$ydate="AND s1.invoice_date <= '".date_format($dateyto,"Y-m-d")."' AND s1.invoice_date >= '".date_format($dateyf,"Y-m-d")."'";
			
		}
		
		
		if($f_date != '')
		{
			$date = "AND s.invoice_date >= '".$f_date."' ";
		}
		if($t_date != '')
		{
			$date.= "AND  s.invoice_date <='".$t_date."'";
		}

	
		$admin_user_id='';
	    
		if($user_type_id == 1 && $user_id == 1)
		{
			if($user_info['country_id']=='111')
			{
			    /*$sql="SELECT SUM(s.invoice_total_amount) as total_amount,s.sales_invoice_id,sUM(sp.qty) as total_qty,sp.product_code_id,p.product_name ,pc2.description,pc2.product_code,(SELECT sum(s1.invoice_total_amount) as total_amount FROM government_sales_invoice as s1,government_sales_invoice_product as sp1 WHERE  sp1.sales_invoice_id =s1.sales_invoice_id AND sp1.product_code_id=pc2.product_code_id AND s1.is_delete =0 $ydate ) as corresponding_amount,(SELECT sum(sp1.qty) as total_amount_qty FROM government_sales_invoice as s1,government_sales_invoice_product as sp1 WHERE  sp1.sales_invoice_id =s1.sales_invoice_id AND sp1.product_code_id=pc2.product_code_id AND s1.is_delete =0 $ydate ) as corresponding_qty,(SELECT sum(s2.invoice_total_amount) as total_amount FROM government_sales_invoice as s2,government_sales_invoice_product as sp2 WHERE  sp2.sales_invoice_id =s2.sales_invoice_id AND sp2.product_code_id=pc2.product_code_id AND s2.is_delete =0 $mdate) as previous_amount,(SELECT sum(sp2.qty) as total_qty FROM government_sales_invoice as s2,government_sales_invoice_product as sp2 WHERE  sp2.sales_invoice_id =s2.sales_invoice_id AND sp2.product_code_id=pc2.product_code_id AND s2.is_delete =0 $mdate) as previous_qty FROM government_sales_invoice as s,product_code as pc2,government_sales_invoice_product as sp ,product as p WHERE p.product_id=pc2.product AND sp.sales_invoice_id =s.sales_invoice_id AND sp.product_code_id=pc2.product_code_id AND s.is_delete =0 $date GROUP BY sp.product_code_id";
    		    if($full_inv==1)
    		      $sql="SELECT * FROM government_sales_invoice WHERE invoice_date >= '".$f_date."' AND invoice_date <='".$t_date."'";*/
			}
			else
			{
    			$sql="SELECT s.gst,SUM(sp.qty*sp.rate) as total_amount,s.invoice_id,sUM(sp.qty) as total_qty,sp.product_code_id,p.product_name ,pc2.description,pc2.product_code,(SELECT sum(sp1.qty*sp1.rate) as total_amount FROM sales_invoice as s1,sales_invoice_product as sp1 WHERE  sp1.invoice_id =s1.invoice_id AND sp1.product_code_id=pc2.product_code_id AND s1.is_delete =0 $ydate ) as corresponding_amount,(SELECT sum(sp1.qty) as total_amount_qty FROM sales_invoice as s1,sales_invoice_product as sp1 WHERE  sp1.invoice_id =s1.invoice_id AND sp1.product_code_id=pc2.product_code_id AND s1.is_delete =0 $ydate ) as corresponding_qty,(SELECT sum(sp2.qty*sp2.rate) as total_amount FROM sales_invoice as s2,sales_invoice_product as sp2 WHERE  sp2.invoice_id =s2.invoice_id AND sp2.product_code_id=pc2.product_code_id AND s2.is_delete =0 $mdate) as previous_amount,(SELECT sum(sp2.qty) as total_qty FROM sales_invoice as s2,sales_invoice_product as sp2 WHERE  sp2.invoice_id =s2.invoice_id AND sp2.product_code_id=pc2.product_code_id AND s2.is_delete =0 $mdate) as previous_qty FROM sales_invoice as s,product_code as pc2,sales_invoice_product as sp ,product as p WHERE p.product_id=pc2.product AND sp.invoice_id =s.invoice_id AND sp.product_code_id=pc2.product_code_id AND s.is_delete =0 $date GROUP BY sp.product_code_id";
    		    if($full_inv==1)
    		      $sql="SELECT * FROM sales_invoice WHERE invoice_date >= '".$f_date."' AND invoice_date <='".$t_date."'";
			}
		}
		else
		{
		    $str = $str1 = $str2 ='';
			if($user=='')
			{   
			    if($user_type_id == 2)
    			{
    				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
    				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
    				$set_user_id = $parentdata->row['user_id'];
    				$set_user_type_id = $parentdata->row['user_type_id'];
    				$admin_id = $parentdata->row['user_id'];
    			}
    			else
    			{
    				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
    				$set_user_id = $admin_id = $user_id;
    				$set_user_type_id = $user_type_id;
    			}
			    if($userEmployee)
				{
					$str = ' OR ( s.user_id IN ('.$userEmployee.') AND s.user_type_id = 2 )';
					$str1 = ' OR ( s1.user_id IN ('.$userEmployee.') AND s1.user_type_id = 2 )';
					$str2 = ' OR ( s2.user_id IN ('.$userEmployee.') AND s2.user_type_id = 2 )';
				}
			}
			else
			{
			    $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
			    $admin_id = $parentdata->row['user_id'];
			    $set_user_id = $user_id;
		        $set_user_type_id = $user_type_id;
			}
			if($user_info['country_id']=='111')
			{
			   /*$sql="SELECT s.gst,SUM(s.invoice_total_amount) as total_amount,s.sales_invoice_id,sUM(sp.qty) as total_qty,sp.product_code_id,p.product_name ,pc2.description,pc2.product_code,(SELECT sum(sp1.qty*sp1.rate) as total_amount FROM government_sales_invoice as s1,government_sales_invoice_product as sp1 WHERE  sp1.sales_invoice_id =s1.sales_invoice_id AND sp1.product_code_id=pc2.product_code_id AND s1.is_delete =0 $ydate  AND ( ( s1.user_id = '".(int)$set_user_id."' AND  s1.user_type_id = '".(int)$set_user_type_id."' ) $str1 )) as corresponding_amount,(SELECT sum(sp1.qty) as total_amount_qty FROM government_sales_invoice as s1,government_sales_invoice_product as sp1 WHERE  sp1.sales_invoice_id =s1.sales_invoice_id AND sp1.product_code_id=pc2.product_code_id AND s1.is_delete =0 $ydate AND ( ( s1.user_id = '".(int)$set_user_id."' AND  s1.user_type_id = '".(int)$set_user_type_id."' ) $str1 )) as corresponding_qty,(SELECT sum(sp2.qty*sp2.rate) as total_amount FROM government_sales_invoice as s2,government_sales_invoice_product as sp2 WHERE  sp2.sales_invoice_id =s2.sales_invoice_id AND sp2.product_code_id=pc2.product_code_id AND s2.is_delete =0 $mdate AND ( ( s2.user_id = '".(int)$set_user_id."' AND  s2.user_type_id = '".(int)$set_user_type_id."' ) $str2 )) as previous_amount,(SELECT sum(sp2.qty) as total_qty FROM government_sales_invoice as s2,government_sales_invoice_product as sp2 WHERE  sp2.sales_invoice_id =s2.sales_invoice_id AND sp2.product_code_id=pc2.product_code_id AND s2.is_delete =0 $mdate AND ( ( s2.user_id = '".(int)$set_user_id."' AND  s2.user_type_id = '".(int)$set_user_type_id."' ) $str2 )) as previous_qty FROM government_sales_invoice as s,product_code as pc2,government_sales_invoice_product as sp ,product as p WHERE p.product_id=pc2.product AND sp.sales_invoice_id =s.sales_invoice_id AND sp.product_code_id=pc2.product_code_id AND s.is_delete =0 $date  AND ( ( s.user_id = '".(int)$set_user_id."' AND  s.user_type_id = '".(int)$set_user_type_id."' ) $str ) GROUP BY sp.product_code_id  ORDER BY total_qty DESC";
    			if($full_inv==1)
    	            $sql="SELECT * FROM government_sales_invoice as s WHERE s.invoice_date >= '".$f_date."' AND s.invoice_date <='".$t_date."' AND ( ( s.user_id = '".(int)$set_user_id."' AND  s.user_type_id = '".(int)$set_user_type_id."' ) $str )";*/			
			}
			else
			{
    			if($admin_id=='10')
    			{
    			    $sql="SELECT pp.gst_tax  as gst,SUM(sp. quantity *sp.rate) as total_amount,s.packing_order_id,sUM(sp. quantity) as total_qty,sp.product_code_id,p.product_name ,pc2.description,pc2.product_code, (SELECT sum(sp1. quantity *sp1.rate) as total_amount FROM packing_order as s1,packing_order_product_code_wise as sp1 WHERE  sp1.packing_order_id =s1.packing_order_id AND sp1.product_code_id=pc2.product_code_id AND s1.is_delete =0 $ydate  AND ( ( s1.user_id = '".(int)$set_user_id."' AND  s1.user_type_id = '".(int)$set_user_type_id."' ) $str1 )) as corresponding_amount, (SELECT sum(sp1. quantity) as total_amount_qty FROM packing_order as s1, packing_order_product_code_wise as sp1 WHERE  sp1. packing_order_id =s1. packing_order_id AND sp1.product_code_id=pc2.product_code_id AND s1.is_delete =0 $ydate AND ( ( s1.user_id = '".(int)$set_user_id."' AND  s1.user_type_id = '".(int)$set_user_type_id."' ) $str1 )) as corresponding_qty, (SELECT sum(sp2. quantity *sp2.rate) as total_amount FROM packing_order as s2, packing_order_product_code_wise as sp2 WHERE  sp2. packing_order_id =s2. packing_order_id AND sp2.product_code_id=pc2.product_code_id AND s2.is_delete =0 $mdate AND ( ( s2.user_id = '".(int)$set_user_id."' AND  s2.user_type_id = '".(int)$set_user_type_id."' ) $str2 )) as previous_amount,(SELECT sum(sp2. quantity) as total_qty FROM packing_order as s2, packing_order_product_code_wise as sp2 WHERE  sp2. packing_order_id =s2. packing_order_id AND sp2.product_code_id=pc2.product_code_id AND s2.is_delete =0 $mdate AND ( ( s2.user_id = '".(int)$set_user_id."' AND  s2.user_type_id = '".(int)$set_user_type_id."' ) $str2 )) as previous_qty FROM packing_order as s,product_code as pc2, packing_order_product_code_wise as sp ,product as p ,proforma_product_code_wise as pp WHERE pp.pro_in_no = s.pro_in_no AND  p.product_id=pc2.product AND sp. packing_order_id =s. packing_order_id AND sp.product_code_id=pc2.product_code_id AND s.is_delete =0 $date  AND ( ( s.user_id = '".(int)$set_user_id."' AND  s.user_type_id = '".(int)$set_user_type_id."' ) $str ) GROUP BY sp.product_code_id  ORDER BY total_qty DESC";
    				if($full_inv==1)
    	                $sql="SELECT * FROM sales_invoice as s WHERE s.invoice_date >= '".$f_date."' AND s.invoice_date <='".$t_date."' AND ( ( s.user_id = '".(int)$set_user_id."' AND  s.user_type_id = '".(int)$set_user_type_id."' ) $str )";			
    	
    			}
    			else
    			{
    				$sql="SELECT s.gst,SUM(sp.qty*sp.rate) as total_amount,s.invoice_id,sUM(sp.qty) as total_qty,sp.product_code_id,p.product_name ,pc2.description,pc2.product_code,(SELECT sum(sp1.qty*sp1.rate) as total_amount FROM sales_invoice as s1,sales_invoice_product as sp1 WHERE  sp1.invoice_id =s1.invoice_id AND sp1.product_code_id=pc2.product_code_id AND s1.is_delete =0 $ydate  AND ( ( s1.user_id = '".(int)$set_user_id."' AND  s1.user_type_id = '".(int)$set_user_type_id."' ) $str1 )) as corresponding_amount,(SELECT sum(sp1.qty) as total_amount_qty FROM sales_invoice as s1,sales_invoice_product as sp1 WHERE  sp1.invoice_id =s1.invoice_id AND sp1.product_code_id=pc2.product_code_id AND s1.is_delete =0 $ydate AND ( ( s1.user_id = '".(int)$set_user_id."' AND  s1.user_type_id = '".(int)$set_user_type_id."' ) $str1 )) as corresponding_qty,(SELECT sum(sp2.qty*sp2.rate) as total_amount FROM sales_invoice as s2,sales_invoice_product as sp2 WHERE  sp2.invoice_id =s2.invoice_id AND sp2.product_code_id=pc2.product_code_id AND s2.is_delete =0 $mdate AND ( ( s2.user_id = '".(int)$set_user_id."' AND  s2.user_type_id = '".(int)$set_user_type_id."' ) $str2 )) as previous_amount,(SELECT sum(sp2.qty) as total_qty FROM sales_invoice as s2,sales_invoice_product as sp2 WHERE  sp2.invoice_id =s2.invoice_id AND sp2.product_code_id=pc2.product_code_id AND s2.is_delete =0 $mdate AND ( ( s2.user_id = '".(int)$set_user_id."' AND  s2.user_type_id = '".(int)$set_user_type_id."' ) $str2 )) as previous_qty FROM sales_invoice as s,product_code as pc2,sales_invoice_product as sp ,product as p WHERE p.product_id=pc2.product AND sp.invoice_id =s.invoice_id AND sp.product_code_id=pc2.product_code_id AND s.is_delete =0 $date  AND ( ( s.user_id = '".(int)$set_user_id."' AND  s.user_type_id = '".(int)$set_user_type_id."' ) $str ) GROUP BY sp.product_code_id  ORDER BY total_qty DESC";
    				if($full_inv==1)
    	                $sql="SELECT * FROM sales_invoice as s WHERE s.invoice_date >= '".$f_date."' AND s.invoice_date <='".$t_date."' AND ( ( s.user_id = '".(int)$set_user_id."' AND  s.user_type_id = '".(int)$set_user_type_id."' ) $str )";			
    	            //$sql = "SELECT *,SUM(amount_paid) as invoice_amt FROM sales_invoice as s WHERE s.invoice_date >= '".$f_date."' AND s.invoice_date <='".$t_date."' AND ( ( s.user_id = '".(int)$set_user_id."' AND  s.user_type_id = '".(int)$set_user_type_id."' ) $str ) AND s.is_delete=0 AND s.status=1 AND s.gen_status=0 GROUP BY s.email ORDER BY invoice_amt DESC";";// ORDER BY amount_paid DESC
    			}
			}
		}
		// echo $sql;
		 $data=$this->query($sql);					
		 if($data->num_rows){
			 return  $data->rows;							
		 }else{
			return false;
		 }
	}
			
	public function getUserEmployeeIds($user_type_id,$user_id)
	{
		$cond = '';
		if($user_id=='6')
		    $cond=' AND user_type= 20';
		$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."' $cond";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['ids'];
		}else{
			return false;
		}
	}
	public function get_report($f_date,$t_date,$check)
	{
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		
		$datef=date_create($f_date);		
		date_sub($datef,date_interval_create_from_date_string("1 month"));				
	  	$dateto=date_create($t_date);		
		date_sub($dateto,date_interval_create_from_date_string(" 1 month"));
	    $mdate=" invoice_date <= '".date_format($dateto,"Y-m-d")."' AND invoice_date >= '".date_format($datef,"Y-m-d")."'";
		
		$dateyf=date_create($f_date);
		date_sub($dateyf,date_interval_create_from_date_string("12 month "));
		$dateyto=date_create($t_date);
		date_sub($dateyto,date_interval_create_from_date_string(" 12 month"));			
		$ydate=" invoice_date <= '".date_format($dateyto,"Y-m-d")."' AND invoice_date >= '".date_format($dateyf,"Y-m-d")."'";
		
	
		$datec = "AND invoice_date >= '".$f_date."' ";
		$datec.= "AND  invoice_date <='".$t_date."'";
		
      
        $sql= "SELECT * FROM taxation_canada";
        $data = $this->query($sql);
    

        $arr=array();
        $this->query("SET SESSION group_concat_max_len = 1000000");
        if($data->num_rows)
        {

        	
          if($check!='2')
          {
            
               $total = $total_pre = $total_cor = 0;
    			foreach($data->rows as $row)
    			{
    			    if($user_type_id == 1 && $user_id == 1)
    			    {
    			       $s="SELECT COUNT(DISTINCT email) as total_cust,SUM(amount_paid) as total_amt,invoice_date,email,(select SUM(amount_paid) from sales_invoice WHere ".$ydate." AND is_delete=0 AND tax_type LIKE '".$row['abbreviation']."%') as corresponding_amount,(select COUNT(DISTINCT email) from sales_invoice WHere ".$ydate." AND is_delete=0 AND tax_type LIKE '".$row['abbreviation']."%') as corresponding_cust,(select SUM(amount_paid) from sales_invoice WHere ".$mdate." AND is_delete=0 AND tax_type LIKE '".$row['abbreviation']."%') as previous_amount,(select COUNT(DISTINCT email) from sales_invoice WHere ".$mdate." AND is_delete=0 AND tax_type LIKE '".$row['abbreviation']."%') as previous_cust FROM sales_invoice  WHERE is_delete=0 AND tax_type LIKE '".$row['abbreviation']."%'  ".$datec." GROUP By  tax_type";
    			       $sl = "SELECT COUNT(email) as total,GROUP_CONCAT(DISTINCT email,'') as email,
(SELECT GROUP_CONCAT(DISTINCT email,'') FROM sales_invoice where is_delete=0 AND tax_type LIKE '".$row['abbreviation']."%' AND ".$ydate.") as corresponding_email,
(SELECT GROUP_CONCAT(DISTINCT email,'') FROM sales_invoice where is_delete=0 AND tax_type LIKE '".$row['abbreviation']."%' AND ".$mdate." ) as previous_email FROM sales_invoice WHERE is_delete=0 AND tax_type LIKE '".$row['abbreviation']."%'  ".$datec." GROUP By  tax_type ";
    			        
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
    					$str ='';
    					if($userEmployee)
    					{
    						$str = ' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 )';
    					}
                        $s="SELECT COUNT(DISTINCT email) as total_cust,SUM(amount_paid) as total_amt,invoice_date,email,(select SUM(amount_paid) from sales_invoice WHere ".$ydate." AND is_delete=0 AND tax_type LIKE '".$row['abbreviation']."%' AND ( ( user_id = '".(int)$set_user_id."' AND  user_type_id = '".(int)$set_user_type_id."' ) $str )) as corresponding_amount,(select COUNT(DISTINCT email) from sales_invoice WHere ".$ydate." AND is_delete=0 AND tax_type LIKE '".$row['abbreviation']."%' AND ( ( user_id = '".(int)$set_user_id."' AND  user_type_id = '".(int)$set_user_type_id."' ) $str )) as corresponding_cust,(select SUM(amount_paid) from sales_invoice WHere ".$mdate." AND is_delete=0 AND tax_type LIKE '".$row['abbreviation']."%' AND ( ( user_id = '".(int)$set_user_id."' AND  user_type_id = '".(int)$set_user_type_id."' ) $str ) ) as previous_amount,(select COUNT(DISTINCT email) from sales_invoice WHere ".$mdate." AND is_delete=0 AND tax_type LIKE '".$row['abbreviation']."%' AND ( ( user_id = '".(int)$set_user_id."' AND  user_type_id = '".(int)$set_user_type_id."' ) $str )) as previous_cust FROM sales_invoice  WHERE is_delete=0 AND tax_type LIKE '".$row['abbreviation']."%'  ".$datec." AND ( ( user_id = '".(int)$set_user_id."' AND  user_type_id = '".(int)$set_user_type_id."' ) $str ) GROUP By  tax_type";
                        
        			    $sl = "SELECT COUNT(email) as total,GROUP_CONCAT(DISTINCT email,'') as email,
(SELECT GROUP_CONCAT(DISTINCT email,'') FROM sales_invoice where is_delete=0 AND tax_type LIKE '".$row['abbreviation']."%' AND ( ( user_id = '".(int)$set_user_id."' AND  user_type_id = '".(int)$set_user_type_id."' ) $str ) AND ".$ydate.") as corresponding_email,
(SELECT GROUP_CONCAT(DISTINCT email,'') FROM sales_invoice where is_delete=0 AND tax_type LIKE '".$row['abbreviation']."%' AND ".$mdate." AND ( ( user_id = '".(int)$set_user_id."' AND  user_type_id = '".(int)$set_user_type_id."' ) $str ) ) as previous_email FROM sales_invoice WHERE is_delete=0 AND tax_type LIKE '".$row['abbreviation']."%' AND ( ( user_id = '".(int)$set_user_id."' AND  user_type_id = '".(int)$set_user_type_id."' ) $str ) ".$datec." GROUP By  tax_type ";
    			          
    			        
    			    }

    			    $d = $this->query($s);
    			    $dt = $this->query($sl);
    			    $total += $d->row['total_amt'];
    			    $total_pre += $d->row['previous_amount'];
    			    $total_cor += $d->row['corresponding_amount'];
    			    
    			    if($dt->num_rows)
    			    {
    			        foreach($dt->rows as $r)
    			        {
    			                    
    			            $arr['email'][$row['state']]= $r;
    			        }
    			    }
    			    
    			    if($d->num_rows)
    			    {
    			        $arr[$row['state']] = $d->row;
    			        $arr[$row['state']]['abbreviation'] = $row['abbreviation'];
    			    }
    			    
    			}
    			
    	
    		$arr['Total'] = $total;
    		$arr['Total_pre'] = $total_pre;
    		$arr['Total_cor'] = $total_cor;
          }
          else
          {
             foreach($data->rows as $row)
    		 {
    		     if($user_type_id == 1 && $user_id == 1)
    			 {
    		        $sl = "SELECT GROUP_CONCAT(sm.segments) as current_seg,GROUP_CONCAT(s.amount_paid) as current_amt,(SELECT GROUP_CONCAT(s.amount_paid) FROM sales_invoice as s,stock_management as sm where s.is_delete=0 AND s.tax_type LIKE '".$row['abbreviation']."%' AND ".$ydate." AND sm.invoice_no = s.invoice_no) as corresponding_amt,(SELECT GROUP_CONCAT(sm.segments) FROM sales_invoice as s,stock_management as sm where s.is_delete=0 AND s.tax_type LIKE '".$row['abbreviation']."%' AND ".$ydate." AND sm.invoice_no = s.invoice_no ) as corresponding_seg,(SELECT GROUP_CONCAT(s.amount_paid) FROM sales_invoice as s,stock_management as sm where s.is_delete=0 AND s.tax_type LIKE '".$row['abbreviation']."%' AND ".$mdate." AND sm.invoice_no = s.invoice_no) as previous_amt,(SELECT GROUP_CONCAT(sm.segments) FROM sales_invoice as s,stock_management as sm where s.is_delete=0 AND s.tax_type LIKE '".$row['abbreviation']."%' AND ".$mdate." AND sm.invoice_no = s.invoice_no) as previous_seg FROM sales_invoice as s,stock_management as sm WHERE s.is_delete=0 AND s.tax_type LIKE '".$row['abbreviation']."%'  ".$datec." AND sm.invoice_no = s.invoice_no GROUP By sm.segments  ";
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
					$str ='';
					if($userEmployee)
					{
						$str = ' OR ( s.user_id IN ('.$userEmployee.') AND s.user_type_id = 2 )';
					}
    		        $sl = "SELECT GROUP_CONCAT(sm.segments) as current_seg,GROUP_CONCAT(s.amount_paid) as current_amt,(SELECT GROUP_CONCAT(s.amount_paid) FROM sales_invoice as s,stock_management as sm where s.is_delete=0 AND s.tax_type LIKE '".$row['abbreviation']."%' AND ".$ydate." AND sm.invoice_no = s.invoice_no) as corresponding_amt,(SELECT GROUP_CONCAT(sm.segments) FROM sales_invoice as s,stock_management as sm where s.is_delete=0 AND s.tax_type LIKE '".$row['abbreviation']."%' AND ".$ydate." AND sm.invoice_no = s.invoice_no) as corresponding_seg,(SELECT GROUP_CONCAT(s.amount_paid) FROM sales_invoice as s,stock_management as sm where s.is_delete=0 AND s.tax_type LIKE '".$row['abbreviation']."%' AND ".$mdate." AND sm.invoice_no = s.invoice_no) as previous_amt,(SELECT GROUP_CONCAT(sm.segments) FROM sales_invoice as s,stock_management as sm where s.is_delete=0 AND s.tax_type LIKE '".$row['abbreviation']."%' AND ".$ydate." AND sm.invoice_no = s.invoice_no) as corresponding_seg,(SELECT GROUP_CONCAT(sm.segments) FROM sales_invoice as s,stock_management as sm where s.is_delete=0 AND s.tax_type LIKE '".$row['abbreviation']."%' AND ".$mdate." AND sm.invoice_no = s.invoice_no) as previous_seg FROM sales_invoice as s,stock_management as sm WHERE s.is_delete=0 AND s.tax_type LIKE '".$row['abbreviation']."%'  ".$datec." AND sm.invoice_no = s.invoice_no GROUP By sm.segments  ";
    			 }
    			 //printr($sl);
    			 $dt = $this->query($sl);
    			 if($dt->num_rows)
			     {
		            foreach($dt->rows as $r)
			        {
			              $curr_seg = explode(',',$r['current_seg']);
			              $curr_amt = explode(',',$r['current_amt']); 
			              $corr_seg = explode(',',$r['corresponding_seg']); 
			              $corr_amt = explode(',',$r['corresponding_amt']); 
			              $pre_seg = explode(',',$r['previous_seg']); 
			              $pre_amt = explode(',',$r['previous_amt']);
			              
    		                $current = $corr = $prev = array();
                            foreach ($curr_seg as $key => $val1) {
                              $current[$val1] +=$curr_amt[$key];
                            }
                            foreach ($corr_seg as $key => $val1) {
                              $corr[$val1] +=$corr_amt[$key];
                            }
                            foreach ($pre_seg as $key => $val1) {
                              $prev[$val1] += $pre_amt[$key];
                            }
                            $arr[$row['state']]=array('current'=>$current,
                                                      'previous'=>$prev,
                                                      'corresponding'=>$corr);
			        }
			     }
    		 }
          
              
          }
    		
        }
		return $arr;
	}
	
	public function gettotalamount($data,$date,$abbreviation)
	{
	    $d = date_parse_from_format("Y-m-d", $date);
	    $sql = "SELECT SUM(amount_paid) as total_amt FROM sales_invoice WHERE MONTH(invoice_date) = ".$d['month']." AND YEAR(invoice_date)= ".$d["year"]." AND is_delete=0 AND tax_type LIKE '".$abbreviation."%' AND email IN('".$data."')";
	    $data = $this->query($sql);
	    if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getIBList() {
        $sql = "SELECT international_branch_id,address_id,CONCAT(first_name,' ',last_name) as user_name FROM international_branch";
        $data = $this->query($sql);
        return $data->rows;
    }
	public function getEmpList($ib_id)
	{
		$ib=explode('=',$ib_id);
		$userEmployee = $this->getUserEmployeeIds('4', $ib[1]);
		$cond='';
		if($ib[1]=='6' || $ib[1]=='37' || $ib[1]=='38' || $ib[1]=='39')
		    $cond=' AND user_type = 20';
		$sql = "SELECT CONCAT(first_name,' ',last_name) as user_name,employee_id FROM employee WHERE employee_id IN (".$userEmployee.") AND is_delete=0 $cond";
		$data = $this->query($sql);
        if($data->num_rows){
            return $data->rows;
        }
        else{
            return false;
        }
	}
	public function getUserList($user_id,$type_id)
	{
		
		if($user_id==1 && $type_id==1)
		{
		    $sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM account_master ORDER BY user_name ASC";
		}
		else
		{
		    $userEmployee = $this->getUserEmployeeIds($type_id, $user_id);
		    $sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM account_master WHERE ((user_id='".$user_id."' AND user_type_id='".$type_id."') OR (user_type_id='2' AND user_id IN (".$userEmployee.")))ORDER BY user_name ASC";
		}
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}
		else{
			return false;
		}
	
	}
	public function getreport($post)
	{
	    
	    
		$ib=explode('=',$post['user_name']);
		$this->query("SET SESSION group_concat_max_len = 1000000");
		$userEmployee = $this->getUserEmployeeIds('4', $ib[1]);
		
		if($post['Diff']=='2')
		    $post['emp_name'] = $post['emp_name'];
		else
		    $post['emp_name'] = '';
		$order = '';
		if($post['Diff']=='4')
		{
		    $group = "pi.state_india";
		}
		else if($post['Diff']=='3')
		{
		    $group = "si.added_user_id";$order=' ';
		}
	    if($ib[1]=='10')
		    $date = "AND si.date_added >= '".$post['f_date']."' AND si.date_added <='".$post['t_date']."' ";
		else
		    $date = "AND si.invoice_date >= '".$post['f_date']."' AND si.invoice_date <='".$post['t_date']."' ";
		    
		$cond ='GROUP BY pi.email';
		$user_india=" AND (si.added_user_id = ".$ib[1]." AND si.added_user_type_id = '4' OR (si.added_user_id IN (".$userEmployee.") AND si.added_user_type_id='2'))";
		$user_other=" AND (pi.user_id = ".$ib[1]." AND pi.user_type_id = '4' OR (pi.user_id IN (".$userEmployee.") AND pi.user_type_id='2'))";
		if($post['emp_name']!='')
		{
		    $cond = 'GROUP BY pi.email ';
		    $user_india=" AND si.added_user_id = ".$post['emp_name']." AND si.added_user_type_id = '2'";
		    $user_other=" AND pi.user_id = ".$post['emp_name']." AND pi.user_type_id = '2'";
		    
            if (strpos($post['emp_name'], '=') !== false) {
                $user_india=" AND si.added_user_id = ".$ib[1]." AND si.added_user_type_id = '4'";
		        $user_other=" AND pi.user_id = ".$ib[1]." AND pi.user_type_id = '4'";
            }
		    
		    
		}
		if($_SESSION['LOGIN_USER_TYPE'] == 1 && $_SESSION['ADMIN_LOGIN_SWISS'] == 1)
        {
		    if($ib[1]=='6' || $ib[1]=='37' || $ib[1]=='38' || $ib[1]=='39')
		    {
		       if($post['Diff']=='0')
		       {
		            $sql="SELECT si.added_user_id as user_id,si.added_user_type_id as user_type_id,COUNT(DISTINCT pi.email) as current_cust,SUM(si.invoice_total_amount) as current_amt,GROUP_CONCAT(DISTINCT pi.email,'') as curr_email,
     		          (SELECT GROUP_CONCAT(DISTINCT pi.email,'') FROM government_sales_invoice as si,proforma_product_code_wise as pi where si.status=1 AND si.invoice_id = pi.proforma_id AND si.is_delete=0 AND si.invoice_status='1' AND (si.added_user_id = ".$ib[1]." AND added_user_type_id = '4' OR (si.added_user_id IN (".$userEmployee.") AND si.added_user_type_id='2')) AND si.invoice_date <= '".$post['f_date']."') as previous
     		          From government_sales_invoice as si,proforma_product_code_wise as pi WHERE si.status=1 AND si.invoice_id = pi.proforma_id AND si.is_delete=0 AND si.invoice_status='1' AND (si.added_user_id = ".$ib[1]." AND added_user_type_id = '4' OR (si.added_user_id IN (".$userEmployee.") AND si.added_user_type_id='2')) $date GROUP BY si.added_user_id"; 
		       }
		       else if($post['Diff']=='2')
		       {
		             $sql="SELECT si.added_user_id as user_id,si.added_user_type_id as user_type_id,pi.email,pi.customer_name,SUM(si.invoice_total_amount) as current_amt
     		          From government_sales_invoice as si,proforma_product_code_wise as pi WHERE si.status=1 AND si.invoice_id = pi.proforma_id AND si.is_delete=0 AND si.invoice_status='1' $date $user_india $cond  ORDER BY current_amt DESC Limit 10"; 
		       }
		       else if($post['Diff']=='3' || $post['Diff']=='4')
		       {
		             $sql="SELECT si.added_user_id as user_id,si.added_user_type_id as user_type_id,COUNT(DISTINCT pi.email) as email,pi.state_india
     		          From government_sales_invoice as si,proforma_product_code_wise as pi WHERE si.status=1 AND si.invoice_id = pi.proforma_id AND si.is_delete=0 AND si.invoice_status='1' $date $user_india GROUP BY $group ORDER BY email DESC"; 
		       }
		    }
		    else if($ib[1]=='10')
		    {
		       if($post['Diff']=='0')
		       {
		            $sql="SELECT si.user_id,si.user_type_id,COUNT(DISTINCT si.email) as current_cust,GROUP_CONCAT(DISTINCT si.email,'') as curr_email,SUM(si.payment_amount) as current_amt,GROUP_CONCAT(DISTINCT si.email,'') as curr_email,
     		          (SELECT GROUP_CONCAT(DISTINCT si.email,'') FROM packing_order as si where si.is_delete=0 AND (si.user_id = ".$ib[1]." AND user_type_id = '4' OR (si.user_id IN (".$userEmployee.") AND si.user_type_id='2')) AND si.date_added <= '".$post['f_date']."') as previous
     		          From packing_order as si WHERE si.is_delete=0 AND (si.user_id = ".$ib[1]." AND user_type_id = '4' OR (si.user_id IN (".$userEmployee.") AND si.user_type_id='2')) $date GROUP BY si.user_id"; 
		       }
		       else if($post['Diff']=='2')
		       {
		             $sql="SELECT pi.user_id,pi.user_type_id,pi.email,pi.cust_nm as customer_name,SUM(pi.payment_amount) as current_amt From packing_order as pi WHERE pi.is_delete=0 AND pi.date_added >= '".$post['f_date']."' AND pi.date_added <='".$post['t_date']."' $user_other $cond  ORDER BY current_amt DESC Limit 10"; 
		       }
		       else if($post['Diff']=='3')
		       {
		             $sql="SELECT pi.user_id,pi.user_type_id,COUNT(DISTINCT pi.email) as email From packing_order as pi WHERE pi.is_delete=0 AND pi.date_added >= '".$post['f_date']."' AND pi.date_added <='".$post['t_date']."' $user_other GROUP BY pi.user_id ORDER BY email DESC"; 
		       }
		    }
		    else
		    {   
		        if($post['Diff']=='0')
		        {
	                $sql = "SELECT si.user_id,si.user_type_id,COUNT(DISTINCT si.email) as current_cust,GROUP_CONCAT(DISTINCT si.email,'') as curr_email,SUM(si.amount_paid) as current_amt,
                       (SELECT GROUP_CONCAT(DISTINCT si.email,'') FROM sales_invoice as si where si.status=1 AND si.is_delete=0 AND (si.user_id = ".$ib[1]." AND user_type_id = '4' OR (si.user_id IN (".$userEmployee.") AND si.user_type_id='2')) AND si.invoice_date <= '".$post['f_date']."' ) as previous FROM sales_invoice as si WHERE si.status=1 AND si.is_delete=0 AND (si.user_id = ".$ib[1]." AND si.user_type_id = '4' OR (si.user_id IN (".$userEmployee.") AND si.user_type_id='2')) $date GROUP BY si.user_id ";
		        }
		        else if($post['Diff']=='2')
		        {
		            $sql = "SELECT pi.user_id,pi.user_type_id,pi.email,pi.customer_name,SUM(pi.amount_paid) as current_amt FROM sales_invoice as pi WHERE pi.status=1 AND pi.is_delete=0 AND pi.invoice_date >= '".$post['f_date']."' AND pi.invoice_date <='".$post['t_date']."' $user_other $cond  ORDER BY current_amt DESC Limit 10";
		        }
		        else if($post['Diff']=='3')
		        {
		            $sql = "SELECT pi.user_id,pi.user_type_id,COUNT(DISTINCT pi.email) as email FROM sales_invoice as pi WHERE pi.status=1 AND pi.is_delete=0 AND pi.invoice_date >= '".$post['f_date']."' AND pi.invoice_date <='".$post['t_date']."' $user_other  GROUP BY pi.user_id ORDER BY email DESC";
		        }
		    }
		}
		//printr($sql);
		$data = $this->query($sql);
	    if($data->num_rows){
            return $data->rows;
        }
        else{
            return false;
        }
	   
	}
	
	public function getTotalRepeatamount($repeated_email,$post)
	{
	    $ib=explode('=',$post['user_name']);
	    $date = "AND si.invoice_date >= '".$post['f_date']."' AND si.invoice_date <='".$post['t_date']."' ";
	    if($ib[1]=='6' || $ib[1]=='37' || $ib[1]=='38' || $ib[1]=='39')
	        $sql="SELECT SUM(si.invoice_total_amount) as current_amt  From government_sales_invoice as si,proforma_product_code_wise as pi WHERE si.invoice_id = pi.proforma_id AND si.is_delete=0 AND si.invoice_status='1' AND email IN (".$repeated_email.") $date ";
	    else if($ib[1]=='10')
	    {
	        $date = "AND si.date_added >= '".$post['f_date']."' AND si.date_added <='".$post['t_date']."' ";
	        $sql="SELECT SUM(si.payment_amount) as current_amt  From packing_order as si WHERE si.is_delete=0 AND si.email IN (".$repeated_email.") $date ";
	    }
	    else
	        $sql="SELECT SUM(si.amount_paid) as current_amt  From sales_invoice as si WHERE si.is_delete=0 AND  si.email IN (".$repeated_email.") $date ";

	    $data = $this->query($sql);
	    if($data->num_rows){
            return $data->row;
        }
        else{
            return false;
        }
	}
	public function getUser($user_id,$user_type_id)
	{
		if($user_type_id == 1){
			$sql = "SELECT u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 2){
			$sql = "SELECT e.user_name, e.user_id,co.country_id, e.multi_quotation_price,ib.company_address,ib.company_name,ib.default_curr,co.country_name, e.first_name, e.last_name,ib.international_branch_id, ib.email_confirm,e.email_signature, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.international_branch_id as user_id,ib.user_name,co.country_id,ib.gst,ib.company_address,ib.default_curr,ib.company_name,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			$sql = "SELECT co.country_name,co.country_id, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";
		}
		$data = $this->query($sql);
		return $data->row;
	}
	public function getTotalQty($email,$post)
	{
	    $ib=explode('=',$post['user_name']);
	    $date = "AND si.invoice_date >= '".$post['f_date']."' AND si.invoice_date <='".$post['t_date']."' ";
	    if($ib[1]=='6' || $ib[1]=='37' || $ib[1]=='38' || $ib[1]=='39')
	        $sql="SELECT SUM(gi.qty) as qty  From government_sales_invoice as si,government_sales_invoice_product as gi,proforma_product_code_wise as pi  WHERE si.invoice_id = pi.proforma_id AND si.sales_invoice_id = gi.sales_invoice_id AND si.is_delete=0 AND si.invoice_status='1' AND si.status='1' AND pi.email = '".$email."' $date ";
	    else if($ib[1]=='10')
	    {
	        $date = "AND si.date_added >= '".$post['f_date']."' AND si.date_added <='".$post['t_date']."' ";
	        $sql="SELECT SUM(gi.quantity) as qty  From packing_order as si,packing_order_product_code_wise as gi WHERE si.packing_order_id = gi.packing_order_id AND si.is_delete=0 AND si.email = '".$email."' $date ";
	    }
	    else
	        $sql="SELECT SUM(sp.qty) as qty  From sales_invoice as si,sales_invoice_product as sp WHERE si.invoice_id = sp.invoice_id AND si.is_delete=0 AND si.email = '".$email."' AND si.amount_paid!=0 $date ";

	    $data = $this->query($sql);
	    if($data->num_rows){
            return $data->row;
        }
        else{
            return false;
        }
	}
	public function getIndiaState($state_id)
	{
	    $data =$this->query("SELECT state FROM india_state WHERE status=1 AND is_delete=0 AND state_id='".$state_id."'");
	    if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
}
?>