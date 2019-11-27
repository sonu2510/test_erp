<?php

class pro_invoice extends dbclass{

//[kinjal] -->
function convert_number_cent($numval,$currency_id=0){
    
				$moneystr = "";

				// handle the millions

				$milval = (integer)($numval / 1000000);
       
				if($milval > 0)  {

				  $moneystr = $this->getwords($milval) . " Million";

				  }

				 

				// handle the thousands

				$workval = $numval - ($milval * 1000000); // get rid of millions

				$thouval = (integer)($workval / 1000);

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
                $and='';
                if($currency_id=='17')
				    $moneystr .= " Dirham And ";
				else
				{
    			    if($currency_id=='3'){
    				    $moneystr .= " Euro ";
    				    $and=' And '; 
    			    }
    		    	elseif ($workval == 1){
    
    				  $moneystr .= " Dollar And ";
    
    				  }else{
    
    				  $moneystr .= " Dollars And ";
    
    				  }
				}

				 

				// do the cents - use printf so that we get the

				// same rounding as printf
			//[kinjal] done on [18-09-2018]
                $point_val = strcspn(strrev($numval), '.');//
                if($point_val=='2')
                {
    			    $workstr = sprintf("%3.2f",$numval); // convert to a string
    			    $intstr = substr($workstr,- 2, 2);
                }
                else if($point_val=='1')
                {
                    $workstr = sprintf("%3.1f",$numval); // convert to a string
    			   $intstr = substr($workstr,- 1, 1);
                }
    			else
    			{
    			   $workstr = sprintf("%3.3f",$numval); // convert to a string
    			   $intstr = substr($workstr,- 3, 3);
    			}
			//END [kinjal]
// printr(sprintf("%3.1f",$numval));
			//$intstr = substr($workstr,strlen() - 2, 2);

			//$intstr = substr($workstr,- 2, 2);

			$workint = (integer)($intstr);

			if ($workint == 0){
			   
			     if($currency_id=='3')
			       $moneystr .= "";
			      else
			        $moneystr .= "Zero";
    
			  }else{

			  $moneystr .= $and.''.$this->getwords($workint);

			  }
            if($currency_id=='17')
			
				$moneystr .= " Fills "; 
		   	
		   	elseif ($workint == 1){
			     $moneystr .= " Cent";
			  }else{
            	if ($workint == 0 && $currency_id=='3'){
			          $moneystr .= " ";
            	}else{
            	     $moneystr .= " Cents";
            	}
			  }
			 

			 

			// done 

			return $moneystr;

	}

		function getwords($workval)

		{

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

			if (($tensval < 20) && ($tensval > 0)){

			  $workstr = $numwords[$tensval];

			   // got to break out the units and tens

			  }else{

			  $tempval = ((integer)($tensval / 10)) * 10; // dump the units

			  if($tempval!=0)
			  {
			  	$workstr = $numwords[$tempval]; // get the tens
				//printr($workstr);
			  }
			  else
			  $workstr ='';

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

			return $retstr;

		}

		

		

		

		

		function convert_number($number) 

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

		public function getActiveProduct(){

			$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete = '0' ORDER BY product_name ASC";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->rows;

			}else{

				return false;

			}

		}

		public function getActiveColor(){

			$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_color` WHERE status='1' AND is_delete = '0' ORDER BY color  ASC";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->rows;

			}else{

				return false;

			}

		}

		public function getUserList($permission=0){
            if( $_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1')
            {
                $sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master ORDER BY user_name ASC";
            }
            else
            {
    			if($_SESSION['LOGIN_USER_TYPE'] == 2){
    
    				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
    
    				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id'],$permission);
    
    				$set_user_id = $parentdata->row['user_id'];
    
    				$set_user_type_id = $parentdata->row['user_type_id'];
    
    			}else{
    
    				$userEmployee = $this->getUserEmployeeIds($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
    
    				$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
    
    				$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
    
    			}
    
    			$str = '';
    
    			if($userEmployee){
    
    				$str = ' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 )';
    
    			}
    			$sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master WHERE (user_id = ".$set_user_id." && user_type_id=".$set_user_type_id." $str)ORDER BY user_name ASC";
            }
			//echo $sql;

			$data = $this->query($sql);
			
			return $data->rows;

		}

		//modify [kinjal]: (1-2-2016) in employee cond 

		public function getUser($user_id,$user_type_id)

		{	//echo $user_type_id;

			if($user_type_id == 1){

				$sql = "SELECT u.user_id,ib.company_address,ib.company_name,ib.bank_address,u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email, acc.commission FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) LEFT JOIN international_branch as ib ON u.user_id=ib.international_branch_id WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";

				//echo $sql;

			}elseif($user_type_id == 2){

				$sql = "SELECT ib.color_plate_price,ib.company_name,ib.foil_plate_price,e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id,ib.gst,ib.company_address,ib.bank_address, acc.commission FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";

				

			}elseif($user_type_id == 4){

				$sql = "SELECT ib.user_name,ib.company_name,ib.color_plate_price,ib.foil_plate_price,co.country_id,ib.gst,ib.company_address,ib.bank_address, co.country_name, ib.first_name, ib.last_name,ib.vat_no, ib.international_branch_id as user_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email, acc.email1, acc.commission FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";

			}else{

				

				$sql = "SELECT co.country_name,co.country_id, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";

			}
            
			$data = $this->query($sql);

			return $data->row;

		}

		public function getCurrency()

		{

			$sql = "SELECT *  FROM " . DB_PREFIX . "currency  WHERE is_delete = '0' ";	

			$data = $this->query($sql);

			$result = $data->rows;

			return $result;	

		}

		public function getCurrencyId($cuurr_id)

		{

			$sql = "SELECT currency_code  FROM " . DB_PREFIX . "currency  WHERE currency_id = '".$cuurr_id."' ";	

			$data = $this->query($sql);

			$result = $data->row;

			return $result;	

		}

		public function getCountry($con_id)

		{

			$sql = "SELECT country_name  FROM " . DB_PREFIX . "country  WHERE country_id = '".$con_id."'";	

			$data = $this->query($sql);

			$result = $data->row;

			return $result;	

		}

		public function getActiveProductAccessorie(){

			$data = $this->query("SELECT product_accessorie_id, product_accessorie_name, price FROM " . DB_PREFIX . "product_accessorie WHERE status = '1' AND is_delete = '0' ORDER BY price ASC");

			if($data->num_rows){

				return $data->rows;

			}else{

				return false;

			}

		}

		public function getActiveProductSpout(){

			$data = $this->query("SELECT product_spout_id, spout_name, price FROM " . DB_PREFIX . "product_spout WHERE status = '1' AND is_delete = '0' ORDER BY spout_name ASC");

			if($data->num_rows){

				return $data->rows;

			}else{

				return false;

			}

		}

		public function checkProductZipper($product_id){

			$data = $this->query("SELECT zipper_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");

			if($data->num_rows){

				return $data->row['zipper_available'];	

			}else{

				return false;

			}

		}

		

		public function checkProductTintie($product_id){

			$data = $this->query("SELECT tintie_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");

			if($data->num_rows){

				return $data->row['tintie_available'];	

			}else{

				return false;

			}

		}

	
		public function getActiveProductZippers(){

			$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_zipper` WHERE status='1' AND is_delete = '0' ORDER BY zipper_name ASC");

			if($data->num_rows){

				return $data->rows;

			}else{

				return false;

			}

		}

		public function checkProductGusset($product_id){

			$data = $this->query("SELECT gusset_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");

			if($data->num_rows){

				return $data->row['gusset_available'];	

			}else{

				return false;

			}

		}

		public function getProductSize($product_id,$zipper_id)

		{

			//echo $zipper_id;die;

			$sql  = "SELECT * FROM size_master WHERE product_id = '".$product_id."' AND product_zipper_id='".$zipper_id."'";


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

		public function ProductSize($size_master_id)

		{

			$sql  = "SELECT * FROM size_master WHERE size_master_id = '".$size_master_id."' "; 

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

		public function getProduct($product_id)

		{

			$sql  = "SELECT * FROM product WHERE product_id = '".$product_id."' "; 

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

		public function addProformaNew($post=array()) {

			$user_type_id = $_SESSION['LOGIN_USER_TYPE'];

			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

			if($user_type_id == 2){

					$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");

					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);

					$set_user_id = $parentdata->row['user_id'];					

					$set_user_type_id = $parentdata->row['user_type_id'];

					//echo "1 echio  ";

				}else{

					$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);

					$set_user_id = $user_id;

					//echo $set_user_id."2";

					$set_user_type_id = $user_type_id;

				}


				$user_info=$this->getUser($set_user_id,'4');

			
            //printr($post);
			if(isset($post['proforma_id'])) {

				$proforma_invoice_id['proforma_id'] = $post['proforma_id'];
				
				$up_sql="UPDATE proforma_product_code_wise SET freight_charges='".$post['freight']."'  WHERE proforma_id=".$post['proforma_id'];//
                $this->query($up_sql);
                
                
			} 

			else 

			{

				$userCountry = $this->getUserCountry($user_type_id,$user_id);

				if($userCountry){

					$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';					

				}else{

					$countryCode='IN';

				}
				
				$pi ='PI-';

				$new_pro_in_no = $this->generateProformaNumber();

				$pro_in_no = $pi.$countryCode.$new_pro_in_no;

				$taxation='';

				$tax_data='';

				$tax_name='';

				$tax_mode='';

				$final_tax_nm='';

				$excies_per=$state_india=0;

				$taxation_per=0;

				$taxation=0;

				$gst_tax = '0';
				
				$cgst = 0;
			
				$sgst = 0;
			
				$igst = 0;
                $state=$gst=$hst=$pst='0';
				//$tin_no = '';
				
				$packing_charges='0';
				$pro_remark='';
				$contact_name='';
				$delivery_charges='0';
				$other_charges_comments='';
				$other_charges='0';
			

			if (isset($post['country_id']) && !empty($post['country_id']) && $post['country_id'] == 111) 
			{
                if ($post['taxation'] != 'sez_no_tax')
                {
                        $tax_mode = $post['taxation'];
				
        				$taxation= $post['taxation'];
        
        				$tax_name.=' tax_name="'.$post['taxation'].'"';
        				
        				//$sql = "SELECT taxation_id,cgst,sgst,igst FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' AND ".$tax_name." ORDER BY taxation_id DESC LIMIT 1";
                        $sql = "SELECT taxation_id,cgst,sgst,igst FROM " . DB_PREFIX . "taxation WHERE status = '1' AND ".$tax_name." AND is_delete = 0 AND find_in_set(".$set_user_id.",admin_user_id) <> 0 ORDER BY taxation_id DESC LIMIT 1";
        				$data_tax = $this->query($sql);
        
        				$tax_data=$data_tax->row;
        				
        			///	printr($tax_data);
        				
        				$cgst = $tax_data['cgst'];
        					
        				$sgst = $tax_data['sgst']; 
        			
        				$igst = $tax_data['igst'];	                                                
                }
                /*if($set_user_id == '37' || $set_user_id == '38')
                {
                    $product_gst_details = $this->getProudctGSTDetails(11);
                    $cgst = $product_gst_details['cgst_percentage'];
        			$sgst = $product_gst_details['sgst_percentage']; 
    			    $igst = $product_gst_details['igst_percentage'];
                }*/
                //$tin_no = $post['tin_no'];
                $packing_charges = $post['packing'];
                $pro_remark = $post['pro_remark'];
               
                 //$tool_price = $post['tool'];
                $state_india = $post['slist'];
            }
            
				elseif(isset($post['country_id']) && !empty($post['country_id']) && $post['country_id'] != '111' && $post['country_id'] != '42')

					$gst_tax = $post['gst_tax'];

				

				/*if(isset($post['country_id']) && !empty($post['country_id']) && $post['country_id'] != '111')

				{*/
					
					if(isset($post['discount']) && !empty($post['discount']) )
					{
					$discount=$post['discount'];
					}else{
						$discount ='0';
						}
				/*}

				else

					$discount='0';*/
					$state=$gst=$hst=$pst=0;

				/*if(isset($post['country_id']) && $post['country_id'] == '42')

				{
				 	
					if(isset($post['state']) && !empty($post['state']) && ($post['gst']) && !empty($post['gst']) && ($post['pst']) && !empty($post['pst'])&& ($post['hst']) && !empty($post['hst']))
					{
						$state = $post['state'];	
						$gst = $post['gst'];	
						$pst = $post['pst'];	
						$hst = $post['hst'];
					}
				}*/
			/*if($post['country_id'] == '42')
    		{ 
    			    $state = $post['state'];	
    			if(isset($post['gst_checkbox']) && !empty($post['gst_checkbox']) )
    			{
    			
    				$gst=$post['gst'];
    			
    			}else{
    				$gst ='0';
    			
    			}
    														
    			if(isset($post['pst_checkbox']) && !empty($post['pst_checkbox']) )
    			{
    				$pst =$post['pst'];
    			}else{
    				$pst='0';
    				
    			}	
    			if(isset($post['hst_checkbox']) && !empty($post['hst_checkbox']) )
    			{
    				$hst =$post['hst'];
    			}else{
    				
    				$hst='0';
    			 }
    			 
    				
    		}*/
    		if($user_info['country_id']=='42')
    		{ 
    			$state = $post['state'];	
    			if(isset($post['gst_checkbox']) && !empty($post['gst_checkbox']) && $post['gst']!='')
    			{
    			    $gst=$post['gst'];
    			}
    			if(isset($post['pst_checkbox']) && !empty($post['pst_checkbox']) && $post['pst']!='')
    			{	
    			    $pst =$post['pst'];
    			}	
        		if(isset($post['hst_checkbox']) && !empty($post['hst_checkbox']) && $post['hst']!='')
    			{
    			    $hst =$post['hst'];
    			}
    			/*if(isset($post['gst']) && $post['gst']!='')
    			{
    			    $gst=$post['gst'];
    			}
    														
    			if(isset($post['pst']) && $post['pst']!='')
    			{
    				$pst =$post['pst'];
    			}	
    			if(isset($post['hst']) && $post['hst']!='')
    			{
    				$hst =$post['hst'];
    			}*/
    		}
		if(isset($post['qst_no']) && !empty($post['qst_no']) )
				{
					$qst_no=$post['qst_no'];
				}else{
						$qst_no ='0';
					 }
				
				
					//add sonu 13-4-2017
         $address_book_id = $post['address_book_id'];
			
				
			//sonu END 
	//	die;
                //[kinjal] : changed code on 23-6-2017
				$contacts = "SELECT email_1,address_book_id FROM company_address WHERE email_1='".$post['email']."' AND is_delete=0";
				$datacontacts= $this->query($contacts);
				//printr($datacontacts);
				if(!isset($datacontacts->row['email_1']) && empty($datacontacts->row['email_1']))
				{
						$sql1 = "INSERT INTO address_book_master SET status = '1', company_name = '".addslashes($post['customer_name'])."',vat_no='".$post['vat_no']."', user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."' ,user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."', date_added = NOW()";
						//echo $sql1;
						$datasql1=$this->query($sql1);
						$address_id = $this->getLastIdAddress();
						$address_book_id = $address_id['address_book_id'];
						//printr($address_book_id);
						$add_id = "SELECT address_book_id,company_address_id FROM company_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd= $this->query($add_id);
						//printr($dataadd);
						if($dataadd->num_rows)
						{
							$sql2 = "UPDATE company_address SET c_address = '".addslashes($post['clientaddress'])."',email_1 = '".$post['email']."',phone_no='".$post['contact_no']."', country= '".$post['country_id']."' WHERE company_address_id ='".$dataadd->row['company_address_id']."'";
							$datasql2=$this->query($sql2);
						}
						else
						{
							$sql2 = "INSERT INTO company_address SET  address_book_id = '".$address_book_id."',c_address = '".addslashes($post['clientaddress'])."', email_1 = '".$post['email']."',phone_no='".$post['contact_no']."', country= '".$post['country_id']."', date_added = NOW()";
							$datasql2=$this->query($sql2);
						}
						
						$add_id_fac = "SELECT address_book_id,factory_address_id FROM factory_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd_fac= $this->query($add_id_fac);
						//intr($dataadd_fac);
						if($dataadd_fac->num_rows)
						{
							//if($post['same_as_above'] != '1')
							//{	
								$sql3 = "UPDATE factory_address SET f_address = '".addslashes($post['client_del_address'])."', country= '".$post['country_id']."' WHERE factory_address_id ='".$dataadd_fac->row['factory_address_id']."'";
								$datasql3=$this->query($sql3);
							//}
						}
						else
						{
							$sql3 = "INSERT INTO factory_address SET  address_book_id = '".$address_book_id."',f_address = '".addslashes($post['client_del_address'])."', country= '".$post['country_id']."', date_added = NOW()";
							$datasql3=$this->query($sql3);
						}
						
				}
				else
				{	
						if($post['address_book_id']!='')
						    $address_book_id = $post['address_book_id'];
						else
						    $address_book_id = $datacontacts->row['address_book_id'];
						
						
						 
						$sql1 = "UPDATE address_book_master SET vat_no='".$post['vat_no']."', company_name = '".addslashes($post['customer_name'])."' WHERE address_book_id ='".$address_book_id."'";
						//echo $sql1;
						$datasql1=$this->query($sql1);
                        $add_id = "SELECT address_book_id,company_address_id FROM company_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd= $this->query($add_id);
						if($dataadd->num_rows)
						{			
								$sql2 = "UPDATE company_address SET c_address = '".addslashes($post['clientaddress'])."',email_1 = '".$post['email']."',phone_no='".$post['contact_no']."', country= '".$post['country_id']."' WHERE company_address_id ='".$dataadd->row['company_address_id']."'";
								$datasql2=$this->query($sql2);
								
						}
						else
						{
								$sql2 = "INSERT INTO company_address SET  address_book_id = '".$address_book_id."',c_address = '".addslashes($post['clientaddress'])."',phone_no='".$post['contact_no']."', email_1 = '".$post['email']."', country= '".$post['country_id']."', date_added = NOW()";
								$datasql2=$this->query($sql2);
						}
						$add_id_fac = "SELECT address_book_id,factory_address_id FROM factory_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd_fac= $this->query($add_id_fac);
						if($dataadd_fac->num_rows)
						{		
								$sql3 = "UPDATE factory_address SET f_address = '".addslashes($post['client_del_address'])."', country= '".$post['country_id']."' WHERE factory_address_id ='".$dataadd_fac->row['factory_address_id']."'";
								$datasql3=$this->query($sql3);
						}
						else
						{
								$sql3 = "INSERT INTO factory_address SET  address_book_id = '".$address_book_id."',f_address = '".addslashes($post['client_del_address'])."', country= '".$post['country_id']."', date_added = NOW()";
								$datasql3=$this->query($sql3);
						}
				}
	            if(!isset($post['same_as_above']))
					$post['same_as_above'] = '';
			
			     if(!isset($post['dis_qty']))
			       $post['dis_qty']='0';
                
                if(!isset($post['for_freight_charge']) || $post['for_freight_charge'] =='No')
                   $char_freight = 'No';
                else
                   $char_freight = 'Yes';
                    
                $terms_and_cond='';
                if(isset($post['terms_and_cond']))
                   $terms_and_cond = $post['terms_and_cond'];
                
                $proforma_title='';
                if(isset($post['proforma_title']))
                   $proforma_title = $post['proforma_title']; 
                if(isset($post['delivery_charges']))
                   $delivery_charges = $post['delivery_charges'];
                if(isset($post['other_charges_comments']))
                   $other_charges_comments = $post['other_charges_comments'];  
                 if(isset($post['other_charges']))
                   $other_charges = $post['other_charges'];  
                if(!isset($post['hsn_code']))
                    $post['hsn_code']=0;
                    
                if(isset($post['contact_name']) && !empty($post['contact_name']))
                    $contact_name=$post['contact_name'];
                
				$sql = "INSERT INTO proforma_product_code_wise set invoice_number = '".$post['invoiceno']."', pro_in_no = '".$pro_in_no."',proforma = '".$post['Proforma']."',proforma_title='".$proforma_title."',gen_pro_as = '".$post['gen_pro_as']."', customer_name = '".addslashes($post['customer_name'])."',contact_name = '".addslashes($contact_name)."',address_book_id = '" .$address_book_id. "', email = '".$post['email']."',contact_no = '".$post['contact_no']."', buyers_order_no = '".$post['buyersno']."', invoice_date = '".$post['invoicedate']."', goods_country = '".$post['country']."', buyers_date = '".$post['buyers_date']."', address_info = '".addslashes($post['clientaddress'])."',del_address_info = '" . addslashes($post['client_del_address']) . "',same_as_above='".$post['same_as_above']."',vat_no='".$post['vat_no']."',qst_no='".$qst_no."',delivery_info = '".addslashes($post['delivery'])."', currency_id = '".$post['currency']."', bank_id = '".$post['bank_id']."',customer_dispatch='".$post['customer_dispatch']."',hsn_code='".$post['hsn_code']."',customer_bank_detail='".$post['customer_bank_detail']."', payment_terms = '".$post['payment_terms']."', destination = '".$post['country_id']."', state = '".$state."',state_india = '".$state_india."',gst = '".$gst."',pst = '".$pst."',hst = '".$hst."', port_loading = '".$post['port_loading']."', transportation = '".encode($post['transport'])."', added_by_user_id = '".$user_id."', added_by_user_type_id = '".$user_type_id."', status = '1' , proforma_status = '1' , date_added = NOW(), date_modify = NOW(), is_delete = 0,tax_mode='".$tax_mode."',tax_form_name='".$final_tax_nm."',excies_per='".$excies_per."',cgst='".$cgst."',sgst='".$sgst."',igst='".$igst."',taxation='".$taxation."',taxation_per='".$taxation_per."',freight_charges='".$post['freight']."',for_freight_charge='".$char_freight."', packing_charges='".$packing_charges."', pro_remark='".$pro_remark."', delivery_charges='".$delivery_charges."', other_charges_comments='".$other_charges_comments."', other_charges='".$other_charges."',gst_tax='".$gst_tax."',discount='".$discount."',Mexico_dis_qty='".$post['dis_qty']."',terms_and_cond='".$terms_and_cond."'";
              /*  if($user_id=='211' ){ 
                 	printr($post);
                 	echo $sql;die;   
                }*/
                
				$data = $this->query($sql);

				$ProformaId = $this->getLastId();

				$proforma_invoice_id = $ProformaId;
                
                
				

				}

				if($post['product_code_id'] != '-1' && $post['product_code_id'] != '0')

				{

					$size = '';

					$mea = $post['measurement'];

					//$clr_txt = '';

				}

				else

				{

					$size = $post['size'];

					$mea = $post['measurement'];

					//$clr_txt = $post['color_text'];

				}
				$clr_txt = '';
				if(isset($post['color_text']) && $post['color_text']!='')
				    $clr_txt = addslashes($post['color_text']);
                
                if($post['product_id']=='31' || $post['product_id']=='16' || $post['product_id']=='50' || $post['product_id']=='1')
					$filling = $post['filling'];
			    else
			        $filling ='';
                
				$ex_rate = 0;
				if(isset($post['express_rate']) && $post['express_rate']!='0')
					$ex_rate = $post['express_rate'];
				$printing_option_type='0';			
				if(isset($post['printing_option_type']))
					 $printing_option_type =  $post['printing_option_type'];
				 $printing = '';
				 if(isset($post['printing']))
					 $printing = $post['printing'];
				$pedimento_mexico = '';
				 if(isset($post['pedimento_mexico']))
					 $pedimento_mexico = $post['pedimento_mexico'];
				$stock_print='';
				if(isset($post['stock_print']))
					 $stock_print = $post['stock_print'];
				$netweight = '';
				if(isset($post['netweight']))
					 $netweight = $post['netweight'];
				$stock_con='';
				if(isset($post['stock_con']) && $post['stock_print']=='Containers' )
					 $stock_con = $post['stock_con'];	 
				
				$plate=0;
					$plate_price=0;
				if(isset($post['plate']) && ($post['stock_print']=='Digital Print' || $post['stock_print']=='Foil Stamping')){
					 $plate = $post['plate'];
					 	if($post['stock_print']=='Foil Stamping')
					 		$plate_price= $user_info['foil_plate_price'];
					 	else
					 		$plate_price= $user_info['color_plate_price'];
					}
				$plus_minus=$customer_dispatch_p='0';
                if(isset($post['plus_minus_quantity']))
                   $plus_minus = $post['plus_minus_quantity']; 
                if(isset($post['customer_dispatch_p']))
                   $customer_dispatch_p = $post['customer_dispatch_p']; 
                   
				if(!isset($post['for_freight_charge']) || $post['for_freight_charge'] =='No')
				{
				
    				$sql = "INSERT INTO proforma_invoice_product_code_wise SET proforma_id = '".$proforma_invoice_id['proforma_id']."',added_by_user_id = '".$user_id."', added_by_user_type_id = '".$user_type_id."', invoice_number ='".$post['invoiceno']."', product_code_id = '".$post['product_code_id']."', product_name = '".$post['real_product_name']."',description = '".addslashes($post['description'])."',quantity = '".$post['qty']."',sales_qty='". $post['qty'] ."',gusset_printing_option='" .$printing_option_type . "',printing_option='" . $printing. "',stock_print='".$stock_print."',stock_con='".$stock_con."',plate='".$plate."',plate_price='".$plate_price."',pedimento_mexico='" . $pedimento_mexico . "',rate = '".$post['rate']."',netweight = '".$netweight."',express_rate='".$ex_rate."',color_text = '".$clr_txt."',  measurement='".$mea."',  size = '".$size."', date_added = NOW(), date_modify = NOW(), is_delete = 0,filling='".$filling."',tool_price='".$post['tool_price']."',plus_minus_quantity='".$plus_minus."',customer_dispatch_p='".$customer_dispatch_p."'";
    
    				//echo $sql;
    				$data = $this->query($sql);
    
    				$InvoiceId = $this->getLastId();
    
    				$ProInID = $this->getLastInvoiceId();
				}
				

				/*$returnArray = array(

						'proforma_invoice_id' => $proforma_invoice_id,

						'proforma_id' => $proforma_invoice_id						

				);
                $this->UpdateTotalInvoicePrice($proforma_invoice_id['proforma_id']);
				return $returnArray;*/
				$total_amt = $this->UpdateTotalInvoicePrice($proforma_invoice_id['proforma_id']);
				$returnArray = array(
                    	'total_amount' => $total_amt,
                    	'proforma_invoice_id' => $proforma_invoice_id,
                        'proforma_id' => $proforma_invoice_id,
						);
				
				return $returnArray;



		}

		public function getInvoice($proforma_id) {

			$sql = "select * from " . DB_PREFIX ."proforma_invoice_product_code_wise where proforma_id = '".$proforma_id."' AND is_delete = '0'";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->rows;

			}

			else {

				return false;

			}

		}
        public function getInvoicepacking($proforma_no) {

			$sql = "select pop.* from " . DB_PREFIX ."packing_order_product_code_wise as pop, packing_order as po where po.pro_in_no = '".$proforma_no."' AND pop.is_delete = '0' AND pop.packing_order_id = po.packing_order_id";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->rows;

			}

			else {

				return false;

			}

		}
		public function getSpout($product_spout_id) {

			$sql = "select * from " . DB_PREFIX ."product_spout where product_spout_id = '".$product_spout_id."'";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->row;

			}

			else {

				return false;

			}

		}

		public function getZipper($product_zipper_id) {

			$sql = "select * from " . DB_PREFIX ."product_zipper where product_zipper_id = '".$product_zipper_id."'";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->row;

			}

			else {

				return false;

			}

		}

		public function getAccessorie($product_accessorie_id) {

			$sql = "select * from " . DB_PREFIX ."product_accessorie where product_accessorie_id = '".$product_accessorie_id."'";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->row;

			}

			else {

				return false;

			}

		}

		public function getLastId() {

			$sql = "SELECT proforma_id FROM proforma_product_code_wise ORDER BY proforma_id DESC LIMIT 1";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->row;

			}

			else {

				return false;

			}

		}

		public function getProformaInvoice($proforma_id) {

			$sql = "SELECT * ,pc.product_code,p.description as prodes FROM  proforma_invoice_product_code_wise as p ,product_code as pc where proforma_id = '".$proforma_id."' AND  pc.product_code_id = p.product_code_id AND p.is_delete = '0'";
				//echo $sql;
			$data = $this->query($sql);

			if($data->num_rows){

				return $data->rows;

			}

			else {

				return false;

			}

		}

		public function getProInNo() {

			$sql = "SELECT pro_in_no FROM proforma_product_code_wise ORDER BY proforma_id DESC LIMIT 1";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->row;

			}

			else {

				return false;

			}

		}

		public function BankDetails($bank_id)

		{

			$sql = "SELECT * FROM bank_detail WHERE bank_detail_id='".$bank_id."'";

			$data=$this->query($sql);

			if($data->num_rows)

			{

				return $data->row;

			}

			else

			{	

				return false;

			}

		}

		public function removeInvoice($proforma_invoice_id,$proforma_id)

		{

			$sql = $this->query("DELETE FROM proforma_invoice_product_code_wise WHERE proforma_invoice_id = '".$proforma_invoice_id."' AND proforma_id='".$proforma_id."'");

			$updateTotalPrice = $this->UpdateTotalInvoicePrice($proforma_id,1);
			
			return $updateTotalPrice;

		}

		public function getLastInvoiceId() {

			$sql = "SELECT proforma_invoice_id FROM proforma_invoice_product_code_wise ORDER BY proforma_invoice_id DESC LIMIT 1";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->row;

			}

			else {

				return false;

			}

		}


		public function getTotalInvoice($filter_data=array(), $status, $proforma_status,$user_id,$user_type_id,$is_delete,$add_book_id='0',$permission=0,$customer_followups=0)

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
//printr ($sql);//die;
			$data = $this->query($sql);

			return $data->num_rows;

		}

		public function getInvoices($data,$filter_data=array(), $status, $proforma_status,$user_id,$user_type_id,$is_delete,$add_book_id = '0',$permission=0,$customer_followups=0){	
		
		
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

			//echo $sql;

			

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->rows;

			}else{

				return false;

			}

		}

		public function getUserEmployeeIds($user_type_id,$user_id,$permission = '0'){
            
            if($permission=='1')
                $per = " IN (6,37,38,39)";
            else
                $per = " = ".(int)$user_id;
        
			$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id ".$per;
            //echo $sql;
			$data = $this->query($sql);
            //printr($data->row['ids']);
			if($data->num_rows){

				return $data->row['ids'];

			}else{

				return false;

			}

		}

		public function updateProformaStatus($status,$data){

			if($status == 0 || $status == 1){

				$sql = "UPDATE `" . DB_PREFIX . "proforma_product_code_wise` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE proforma_id IN (" .implode(",",$data). ")";

				$this->query($sql);

			}elseif($status == 2){
                $by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');
				$sql = "UPDATE `" . DB_PREFIX . "proforma_product_code_wise` SET is_delete = '1', delete_by = '".$by."' ,date_modify = NOW() WHERE proforma_id IN (" .implode(",",$data). ")";

				$this->query($sql);

			}

		}

		public function updateProStatus($proforma_id,$status_value){

			$sql = "UPDATE " . DB_PREFIX . "proforma_product_code_wise SET status = '".$status_value."', date_modify = NOW() WHERE proforma_id = '" .(int)$proforma_id. "'";

			$this->query($sql);

		}

		public function getUserInfo($user_id) {

			$sql = "SELECT * from ".DB_PREFIX."user WHERE user_id = '".$user_id."'";

			$data = $this->query($sql);

			if($data->num_rows) {

				return $data->row;

			} else {

				return false;

			}

		}

		public function getProformaData($proforma_id){

			$sql = "SELECT p.*,b.* FROM " . DB_PREFIX . "proforma_product_code_wise as p,bank_detail as b WHERE b.bank_detail_id=p.bank_id AND p.proforma_id = '" .(int)$proforma_id. "'";

			//echo $sql;

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->row;

			}else{

				return false;

			}

		}

		public function getSingleInvoice($proforma_invoice_id) {

			$sql = "select * from " . DB_PREFIX ." proforma_invoice_product_code_wise where proforma_invoice_id = '".$proforma_invoice_id."'";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->row;

			}

			else {

				return false;

			}

		}

		public function getbankDetails($currency_code,$user_id='') {
             
            $cond='';
			if($user_id=='37')
			    $cond='AND 	bank_detail_id = 15';
			elseif($user_id=='38')
			    $cond='AND 	bank_detail_id = 16';
			elseif($user_id=='39')
			    $cond='AND 	bank_detail_id = 17';
		    elseif($user_id=='6')
		        $cond='AND 	bank_detail_id NOT IN (16,15,17)';
		    elseif($user_id=='29' || $user_id=='10')
		        $cond='AND 	for_user LIKE "%'.$user_id.'%"';
		        
			$sql = " SELECT * from bank_detail WHERE curr_code = '".$currency_code."' AND status=1 AND is_delete = '0' $cond ";
            //echo $sql; 
			$data = $this->query($sql);

			if($data->num_rows) {

				return $data->rows;

			} else {

				return false;

			}

		}

		public function updateInvoice($post) {

			

			$user_type_id = $_SESSION['LOGIN_USER_TYPE'];

			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

			
			if($user_type_id == 2){

					$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");

					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);

					$set_user_id = $parentdata->row['user_id'];					

					$set_user_type_id = $parentdata->row['user_type_id'];

			//echo "1 echio  ";

			}else{

				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);

				$set_user_id = $user_id;

				//echo $set_user_id."2";

				$set_user_type_id = $user_type_id;

			}


		    $user_info=$this->getUser($set_user_id,'4');
			
			
			$product_name = $this->getProduct($post['product_id']);

			

			if($post['product_code_id'] == '-1' || $post['product_code_id']=='0')

			{

				$size = $post['size'];

				$mea = $post['measurement'];

				//$clr_txt = $post['color_text'];

				if($post['product_code_id'] == '-1')

					$product_nm = 'Custom';

				else

					$product_nm = 'Cylinder';

			}

			else

			{

				$size = '';

				$mea = $post['measurement'];

				//$clr_txt = '';

				$product_nm = $product_name['product_name'];

			}
            $clr_txt = '';
			if(isset($post['color_text']) && $post['color_text']!='')
				$clr_txt = addslashes($post['color_text']);
			
			if($post['product_id']=='31' || $post['product_id']=='16' || $post['product_id']=='50'  || $post['product_id']=='1')
				$filling = $post['filling'];
			else
				$filling = '';
			
			$ex_rate = 0;
				if(isset($post['express_rate']) && $post['express_rate']!='0')
					$ex_rate = $post['express_rate'];
			
			$printing_option_type='0';			
			if(isset($post['printing_option_type']))
				 $printing_option_type =  $post['printing_option_type'];
			 $printing = '';
			 if(isset($post['printing']))
				 $printing = $post['printing'];
			 $pedimento_mexico = '';
				 if(isset($post['pedimento_mexico']))
					 $pedimento_mexico = $post['pedimento_mexico'];
			$netweight = '';
				if(isset($post['netweight']))
					 $netweight = $post['netweight'];
					 
			$stock_print='';
				if(isset($post['stock_print']))
					 $stock_print = $post['stock_print'];
            $stock_con='';
			if(isset($post['stock_con']) && $post['stock_print']=='Containers' )
				 $stock_con = $post['stock_con'];	 
			
			$plate=0;
			$plate_price=0;
			if(isset($post['plate']) && ($post['stock_print']=='Digital Print' || $post['stock_print']=='Foil Stamping')){
				 $plate = $post['plate'];
				 	if($post['stock_print']=='Foil Stamping')
				 		$plate_price= $user_info['foil_plate_price'];
				 	else
				 		$plate_price= $user_info['color_plate_price'];
				}
				 
				 
				 
			$plus_minus='0';
                if(isset($post['plus_minus_quantity']))
                   $plus_minus = $post['plus_minus_quantity'];
           	$customer_dispatch_p='0';
                if(isset($post['customer_dispatch_p']))
                   $customer_dispatch_p = $post['customer_dispatch_p'];
         $by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');      	 
		$sql = "UPDATE  proforma_invoice_product_code_wise SET proforma_id = '".$post['proforma_id']."',gusset_printing_option='" .$printing_option_type . "',printing_option='" . $printing . "',stock_print='".$stock_print."',stock_con='".$stock_con."',plate='".$plate."',plate_price='".$plate_price."', pedimento_mexico='" . $pedimento_mexico . "' ,invoice_number ='".$post['invoiceno']."', product_code_id = '".$post['product_code_id']."', product_name = '".$product_nm."',description = '".$post['description']."',quantity = '".$post['qty']."',sales_qty='". $post['qty'] ."',rate = '".$post['rate']."',netweight = '".$netweight."',express_rate = '".$ex_rate."',color_text = '".$clr_txt."', 	measurement = '".$mea."', size='".$size."' , filling='".$filling."',date_modify = NOW(),	is_delete = 0,tool_price='".$post['tool_price']."',plus_minus_quantity='".$plus_minus."',customer_dispatch_p='".$customer_dispatch_p."',edit_by = '".$by."' WHERE proforma_invoice_id = '".$post['pro_id']."'  ";
        /*if($_SESSION['ADMIN_LOGIN_SWISS']==1)
	        echo $sql;*/

			$data = $this->query($sql); 

			
     $this->UpdateTotalInvoicePrice($post['proforma_id']);

			return $data;
			


		}

		public function updateProforma ($post) {

			$user_type_id = $_SESSION['LOGIN_USER_TYPE'];

			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

			$taxation='';

			$tax_data='';

			$tax_name='';

			$final_tax_nm='';

			$excies_per=0;
			
			$cgst = $state_india= 0;
			
			$sgst = 0;
			
			$igst = 0;
		
            $user_info=$this->getUser($user_id,$user_type_id);
			$taxation_per=0;

			$tax_mode='';

			$freight='';

			//$tin_no ='';
			
			$packing_charges=0;
			$pro_remark='';
			$delivery_charges=0;
			$other_charges_comments='';
			$other_charges=0;
            $discount = $post['discount'];
			 if (isset($post['country_id']) && !empty($post['country_id']) && $post['country_id'] == 111) {

            //$tin_no = $post['tin_no'];

            $packing_charges = $post['packing'];
            $pro_remark = $post['pro_remark'];

          //  $tool_price = $post['tool'];
            
           // $discount = '0';
            
            //$tax_mode = $post['taxation'];
                if ($post['taxation'] != 'sez_no_tax')
                {
           	
    		  		$tax_mode = $post['taxation'];
    				
    				$taxation= $post['taxation'];
    
    				$tax_name.=' tax_name="'.$post['taxation'].'"';
    				
    				//$sql = "SELECT taxation_id,cgst,sgst,igst FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' AND ".$tax_name." ORDER BY taxation_id DESC LIMIT 1";
                    $sql = "SELECT taxation_id,cgst,sgst,igst FROM " . DB_PREFIX . "taxation WHERE status = '1' AND ".$tax_name." AND is_delete = 0 AND find_in_set(".$user_info['user_id'].",admin_user_id) <> 0 ORDER BY taxation_id DESC LIMIT 1";
    				$data_tax = $this->query($sql);
    
    				$tax_data=$data_tax->row;
    				
    				$cgst = $tax_data['cgst'];
    					
    				$sgst = $tax_data['sgst']; 
    			
    				$igst = $tax_data['igst'];	
                }	
				/*if($user_info['user_id'] == '37' || $user_info['user_id'] == '38')
                {
                    $product_gst_details = $this->getProudctGSTDetails(11);
                    $cgst = $product_gst_details['cgst_percentage'];
        			$sgst = $product_gst_details['sgst_percentage']; 
    			    $igst = $product_gst_details['igst_percentage'];
                }*/
    				
    		   $state_india = $post['slist'];
    		 
            } else
                $discount = $post['discount'];
            
				$state=$gst=$hst=$pst=0;
			/*if(isset($post['country_id']) && $post['country_id'] == '42')

			{
				$state = $post['state'];
			
			
			if(isset($post['gst_checkbox']) && !empty($post['gst_checkbox']) )
			{
    			$gst=$post['gst'];
    			//printr($gst);
			}else{
			        $gst ='0';
				
			}							
			if(isset($post['pst_checkbox']) && !empty($post['pst_checkbox']) )
			{	
			    $pst =$post['pst'];
			
			}else{
			    $pst='0';
			
			}	
			if(isset($post['hst_checkbox']) && !empty($post['hst_checkbox']) )
					{
					$hst =$post['hst'];
					
					}else{
					$hst='0';
					}
				


			}*/
			if($user_info['country_id']=='42')
    		{ 
    			$state = $post['state'];	
    			if(isset($post['gst_checkbox']) && !empty($post['gst_checkbox']) && $post['gst']!='')
    			{
    			    $gst=$post['gst'];
    			}
    			if(isset($post['pst_checkbox']) && !empty($post['pst_checkbox']) && $post['pst']!='')
    			{	
    			    $pst =$post['pst'];
    			
    			}	
        		if(isset($post['hst_checkbox']) && !empty($post['hst_checkbox']) && $post['hst']!='')
    			{
    			    $hst =$post['hst'];
    			}
    			/*if(isset($post['gst']) && $post['gst']!='')
    			{
    			    $gst=$post['gst'];
    			}
    														
    			if(isset($post['pst']) && $post['pst']!='')
    			{
    				$pst =$post['pst'];
    			}	
    			if(isset($post['hst']) && $post['hst']!='')
    			{
    				$hst =$post['hst'];
    			}*/
    		}
			$qst = '0';
			if(isset($post['qst_no']) && !empty($post['qst_no'])){
			   
			       $qst = $post['qst_no'];
			    }
		
		
		
                $freight=$post['freight'];
		
           if(!isset($post['same_as_above']))
				$post['same_as_above'] = '';
		    if(!isset($post['dis_qty']))
			   $post['dis_qty']='0';
		    
		    if(!isset($post['for_freight_charge']) || $post['for_freight_charge'] =='No')
                   $char_freight = 'No';
                else
                   $char_freight = 'Yes';
            
            $terms_and_cond='';
            if(isset($post['terms_and_cond']))
               $terms_and_cond = $post['terms_and_cond'];
            
            $proforma_title='';
            if(isset($post['proforma_title']))
               $proforma_title = $post['proforma_title']; 
             if(isset($post['delivery_charges']))
               $delivery_charges = $post['delivery_charges']; 
             if(isset($post['other_charges_comments']))
               $other_charges_comments = $post['other_charges_comments'];
             if(isset($post['other_charges']))
               $other_charges = $post['other_charges'];
               
               
               
            if(!isset($post['hsn_code']))
                    $post['hsn_code']=0;
            //[kinjal] : changed code on 23-6-2017
				$contacts = "SELECT email_1,address_book_id FROM company_address WHERE email_1='".$post['email']."' AND is_delete=0";
				$datacontacts= $this->query($contacts);
				//printr($datacontacts);
				if(!isset($datacontacts->row['email_1']) && empty($datacontacts->row['email_1']))
				{
						$sql1 = "INSERT INTO address_book_master SET status = '1', company_name = '".addslashes($post['customer_name'])."',vat_no='".$post['vat_no']."', user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."' ,user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."', date_added = NOW()";
						//echo $sql1;
						$datasql1=$this->query($sql1);
						$address_id = $this->getLastIdAddress();
						$address_book_id = $address_id['address_book_id'];
						//printr($address_book_id);
						$add_id = "SELECT address_book_id,company_address_id FROM company_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd= $this->query($add_id);
						//printr($dataadd);
						if($dataadd->num_rows)
						{
							$sql2 = "UPDATE company_address SET c_address = '".addslashes($post['clientaddress'])."',email_1 = '".$post['email']."',phone_no='".$post['contact_no']."', country= '".$post['country_id']."' WHERE company_address_id ='".$dataadd->row['company_address_id']."'";
							$datasql2=$this->query($sql2);
						}
						else
						{
							$sql2 = "INSERT INTO company_address SET  address_book_id = '".$address_book_id."',c_address = '".addslashes($post['clientaddress'])."', email_1 = '".$post['email']."',phone_no='".$post['contact_no']."', country= '".$post['country_id']."', date_added = NOW()";
							$datasql2=$this->query($sql2);
						}
						
						$add_id_fac = "SELECT address_book_id,factory_address_id FROM factory_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd_fac= $this->query($add_id_fac);
						//intr($dataadd_fac);
						if($dataadd_fac->num_rows)
						{
							//if($post['same_as_above'] != '1')
							//{	
								$sql3 = "UPDATE factory_address SET f_address = '".addslashes($post['client_del_address'])."', country= '".$post['country_id']."' WHERE factory_address_id ='".$dataadd_fac->row['factory_address_id']."'";
								$datasql3=$this->query($sql3);
							//}
						}
						else
						{
							$sql3 = "INSERT INTO factory_address SET  address_book_id = '".$address_book_id."',f_address = '".addslashes($post['client_del_address'])."', country= '".$post['country_id']."', date_added = NOW()";
							$datasql3=$this->query($sql3);
						}
						
				}
				else
				{	
						if($post['address_book_id']!='')
						    $address_book_id = $post['address_book_id'];
						else
						    $address_book_id = $datacontacts->row['address_book_id'];
						
						
						
						$sql1 = "UPDATE address_book_master SET vat_no='".$post['vat_no']."', company_name = '".addslashes($post['customer_name'])."' WHERE address_book_id ='".$address_book_id."'";
						//echo $sql1;
						$datasql1=$this->query($sql1);
						
                        $add_id = "SELECT address_book_id,company_address_id FROM company_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd= $this->query($add_id);
						if($dataadd->num_rows)
						{			
							$sql2 = "UPDATE company_address SET c_address = '".addslashes($post['clientaddress'])."',email_1 = '".$post['email']."',phone_no='".$post['contact_no']."', country= '".$post['country_id']."' WHERE company_address_id ='".$dataadd->row['company_address_id']."'";
							$datasql2=$this->query($sql2);
								
						}
						else
						{
						    $sql2 = "INSERT INTO company_address SET  address_book_id = '".$address_book_id."',c_address = '".addslashes($post['clientaddress'])."',phone_no='".$post['contact_no']."', email_1 = '".$post['email']."', country= '".$post['country_id']."', date_added = NOW()";
							$datasql2=$this->query($sql2);	
						}
						$add_id_fac = "SELECT address_book_id,factory_address_id FROM factory_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd_fac= $this->query($add_id_fac);
						if($dataadd_fac->num_rows)
						{		
								$sql3 = "UPDATE factory_address SET f_address = '".addslashes($post['client_del_address'])."', country= '".$post['country_id']."' WHERE factory_address_id ='".$dataadd_fac->row['factory_address_id']."'";
								$datasql3=$this->query($sql3);
						}
						else
						{
								$sql3 = "INSERT INTO factory_address SET  address_book_id = '".$address_book_id."',f_address = '".addslashes($post['client_del_address'])."', country= '".$post['country_id']."', date_added = NOW()";
								$datasql3=$this->query($sql3);
						}
				}
                /*if($user_type_id == 1)
                {
                    printr($sql1);
                    printr($sql2);
                    printr($sql3);die;
                    return 0;
                }*/
            
            
            $by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');
			$sql = "UPDATE `".DB_PREFIX."proforma_product_code_wise` SET invoice_number = '".$post['invoiceno']."', proforma = '".$post['Proforma']."',gen_pro_as = '".$post['gen_pro_as']."',proforma_title='".$proforma_title."', customer_name = '".addslashes($post['customer_name'])."', email = '".$post['email']."',contact_no = '".$post['contact_no']."', buyers_order_no = '".$post['buyersno']."', invoice_date = '".$post['invoicedate']."', goods_country = '".$post['country']."', buyers_date = '".$post['buyers_date']."', address_info = '".addslashes($post['clientaddress'])."',del_address_info = '" . addslashes($post['client_del_address']) . "',same_as_above='".$post['same_as_above']."',vat_no='".$post['vat_no']."',qst_no='".$qst."',delivery_info = '".addslashes($post['delivery'])."', currency_id = '".$post['currency']."', bank_id = '".$post['bank_id']."',customer_dispatch='".$post['customer_dispatch']."', customer_bank_detail='".$post['customer_bank_detail']."', payment_terms = '".$post['payment_terms']."', destination = '".$post['country_id']."',state = '".$state."',state_india = '".$state_india."',gst = '".$gst."',pst = '".$pst."',hst = '".$hst."', port_loading = '".$post['port_loading']."', transportation = '".encode($post['transport'])."',  date_modify = NOW(), is_delete = 0,tax_mode='".$tax_mode."',tax_form_name='".$final_tax_nm."',excies_per='".$excies_per."',gst_tax='".$post['gst_tax']."',cgst='".$cgst."',sgst='".$sgst."',igst='".$igst."',taxation='".$taxation."',freight_charges='".$freight."',for_freight_charge='".$char_freight."', taxation_per='".$taxation_per."',delivery_charges='".$delivery_charges."',other_charges_comments='".$other_charges_comments."',other_charges='".$other_charges."',pro_remark='".$pro_remark."',packing_charges='".$packing_charges."',discount='".$discount."',Mexico_dis_qty='".$post['dis_qty']."',terms_and_cond='".$terms_and_cond."',hsn_code='".$post['hsn_code']."',edit_by='".$by."' WHERE proforma_id = '".$post['invoiceno']."'	";

			//echo $sql;die;

			$data = $this->query($sql);

			

			$this->UpdateTotalInvoicePrice($post['invoiceno']);

			return $data;

			

		

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

		public function getViewToolprice($product_id)

		{

			$sql  = "SELECT pe.*,p.product_name FROM product_extra_tool_price as pe,product as p WHERE pe.product_id = p.product_id AND 

			pe.product_id = '".$product_id."'"; 

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

		public function getToolPrice($width,$gusset,$product_id){

			$cond = '';

			$cond1 ='';

			$sql1 = "SELECT price FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to = '".(int)$width."')

			AND gusset = '".$gusset."' LIMIT 1";

		    $data1 = $this->query($sql1);

		    if(!$data1->num_rows)

	 		{

				if(isset($gusset) && $gusset>0)

				{

					$cond = " ORDER BY gusset,width_to  ASC";

				}

				else

				{

					$cond1 = " ORDER BY width_to ASC LIMIT 1";

				}	

				$sql = "SELECT price FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND (width_to > '".(int)$width."'

				) ".$cond1."";

				$data = $this->query($sql);

	

				if($data->num_rows >1)

				{

					$sql = "SELECT price FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to > '".(int)$width."') ".$cond." LIMIT 1";

					$data = $this->query($sql);

				}

				if($data->num_rows){

					return $data->row['price'];

				}else{

					return false;

				}

			 }

			 else

	 		{

				return 0;

	 		}

		}

		public function getGussetSuggestion($width,$gusset,$product_id)

		{

			$result = '';

			if($gusset!='')

			{

				$result = "AND gusset = '".$gusset."'";	

			}

			$cond = '';

			$cond1 ='';

			$sql1 = "SELECT price,width_to,gusset FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to = '".(int)$width."') LIMIT 1";	

		 	$data1 = $this->query($sql1);

		 	if( $data1->num_rows > 0)

		 	{

				if($gusset!='')

			 	{

			 		if($data1->row['gusset']==$gusset)

			 		{

				 		return 0;

			 		}

					elseif($data1->row['width_to']==$width && $data1->row['gusset']!=$gusset)

			 		{

				 		//echo $gusset;

				 		return $data1->rows;

			 		}

			 	}

			 	else

			 	{

					return 0;

		 		}

	 		}

			else

			{

				if(!$data1->num_rows)

	 			{

					$cond1 = " LIMIT 1";

					$sql = "SELECT price,width_to,gusset FROM ( ( SELECT price,width_to,gusset,".$width."-width_to AS diff FROM product_extra_tool_price WHERE product_id = '" .(int)$product_id. "' AND width_to >'".$width."' ".$cond1." ) UNION ALL ( SELECT price,width_to,gusset,width_to-".$width." AS diff FROM product_extra_tool_price  WHERE product_id = '" .(int)$product_id. "' AND width_to <'".$width."' ".$cond1."  )) AS tmp ORDER BY diff LIMIT 2" ;

					$data = $this->query($sql);

					if($data->num_rows){

						return $data->rows;				

					}else{

						return false;

					}

	 			}

			}

		}

		public function getUserWiseCurrency($user_type_id,$user_id)

		{

			if($user_type_id==2){

				$parent_data = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX ."employee WHERE employee_id='".$user_id."'");

				if($parent_data->num_rows){

					if($parent_data->row['user_type_id']==4){

						$sql = "SELECT ib.product_rate,ib.cylinder_rate,ib.tool_rate,ib.default_curr,cn.currency_code,cn.currency_id FROM " . DB_PREFIX ."international_branch ib INNER JOIN " . DB_PREFIX ."country cn ON (ib.default_curr=cn.country_id) WHERE ib.international_branch_id = '".$parent_data->row['user_id']."' ";				

					}else if($parent_data->row['user_type_id']==5){

						$sql = "SELECT as.product_rate,as.cylinder_rate,as.default_curr,cn.currency_code,cn.currency_id FROM " . DB_PREFIX ."associate as INNER JOIN " . DB_PREFIX ."country cn ON (as.default_curr=cn.country_id) WHERE as.associate_id = '".$parent_data->row['user_id']."' ";	

					}	

				}

			}

			if($user_type_id==4){

				$sql = "SELECT ib.product_rate,ib.cylinder_rate,ib.tool_rate,ib.default_curr,cn.currency_code,cn.currency_id FROM " . DB_PREFIX ."international_branch ib INNER JOIN " . DB_PREFIX ."country cn ON (ib.default_curr=cn.country_id) WHERE ib.international_branch_id = '".$user_id."' ";		

			}			

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->row;

			}else{

				return false;

			}		

		}

		public function numberFormate($number,$decimalPoint=3){

			return number_format($number,$decimalPoint,".","");

		}

		public function generateProformaNumber(){

			$data = $this->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'proforma_product_code_wise'");

			$count = $data->row['AUTO_INCREMENT'];

			$strpad = str_pad($count,8,'0',STR_PAD_LEFT);

			return $strpad;

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

		public function saveProformaStatus($proforma_id){

			$sql = "UPDATE `" . DB_PREFIX . "proforma_product_code_wise` SET proforma_status = '0'  WHERE proforma_id =".$proforma_id;

			$data = $this->query($sql);

			return $data;

		}

		public function sendInvoiceEmail($proforma_id,$to_email,$url,$n='0')

		{	

			$html ='';

			$proforma=$this->getProformaData($proforma_id);
            $user_info  =$this->getUser($proforma['added_by_user_id'],$proforma['added_by_user_type_id']);
			$proforma_id=$proforma['proforma_id'];

			$proforma_inv=$this->getProformaInvoice($proforma_id);

			$html .='<style>

				/*.div_first{width: 100%;}*/

				.col-lg-3 {width: 15%;}

				#client {

				border-left: 6px solid #0087c3;

				float: left;

				padding-left: 6px;

				}	

				h1 {

					background:#333;

					border-bottom: 1px solid #5d6975;

					border-top: 1px solid #5d6975;

					color: #FFF;

					font-size:  12px;

					font-weight: normal;

					line-height: 1.4em;

					margin: 0 0 20px;

					text-align: center;

				}

				article, article address, table.meta, table.inventory { margin: 0 0 3em; }

				table.meta, table.balance { float: right; width: 50%; }

				table.meta:after, table.balance:after { clear: both; content: ""; display: table; }

			

			/* table meta */

			table.meta th { width: 40%;  font-size:  12px; }

			table.meta td { width: 60%;   font-size:  12px; }

			/* table items */

			table { font-size:  12px; table-layout: fixed; width: 100%; }

			table { border-collapse: separate; border-spacing: 1px; font-size:  12px;  }

			th, td { border-width: 1px;position: relative; text-align: left;  font-size:  12px;}

			th, td { border-radius: 0em; border-style: solid; font-size:  12px;  }

			th { background: #EEE; border-color: #BBB; font-size:  12px; }

			td { border-color: #DDD; font-size:  12px; }

			@font-face {

	

				font-family:IDAutomationHC39M;

				src:url("'.HTTP_SERVER.'css/Fonts/IDAutomationHC39M.ttf"),

			}

			.barcode{

				font-family:IDAutomationHC39M !important;	

			}</style>';
            if($n=='0')
            {
		    	/*if($user_info['country_id']==111 && $proforma['invoice_date']>='2019-10-02')
                    $html = $this->viewProformaInvoice_newGSTFormat($proforma['proforma_id']); 
                else*/                           
                    $html .= $this->viewProformaInvoice($proforma_id);
            }
		    else
		        $html .= $this->viewProformaInvoiceInSpanish($proforma_id);

			$addedByinfo=$this->getUser($proforma['added_by_user_id'],$proforma['added_by_user_type_id']);

			$subject = $proforma['pro_in_no'].' '.$proforma['customer_name'];  

			$email_temp[]=array('html'=>$html,'email'=>ADMIN_EMAIL);
			
			//if($addedByinfo['user_id']=='19')
			//	$email_temp[]=array('html'=>$html,'email'=>'sales@swisspac.ae');
				
			$email_temp[]=array('html'=>$html,'email'=>$addedByinfo['email']);

			$email_temp[]=array('html'=>$html,'email'=>$to_email);

		
            
            if($proforma['added_by_user_type_id']==2)
            {
                $admin_email=$this->getUser($addedByinfo['user_id'],'4');
                if($admin_email['email1']!='' && $admin_email['email1']!=$addedByinfo['email'])
                	$email_temp[]=array('html'=>$html,'email'=>$admin_email['email1']);
                	
            }
            //if($_SESSION['ADMIN_LOGIN_SWISS']==211 && $_SESSION['LOGIN_USER_TYPE']==2)
               // printr($email_temp);die;
			$form_email=$addedByinfo['email'];

			$obj_email = new email_template();

		

			$rws_email_template = $obj_email->get_email_template(5); 

			$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				

			$path = HTTP_SERVER."template/proforma_invoice.html";

			$output = file_get_contents($path);  

			$search  = array('{tag:header}','{tag:details}');

			$signature = 'Thanks.';
			//echo '<pre>';
			
            //printr($email_temp);
                    
               if($proforma['buyers_order_no']!='0') 
                    $proforma['pro_in_no']=$proforma['buyers_order_no'].'-'.$proforma['pro_in_no'];
                else
                    $proforma['pro_in_no']=$proforma['pro_in_no'];
			foreach($email_temp as $val)

			{
               //printr($val['email']);
				$toEmail =$form_email;

				$firstTimeemial = 1;								
           
        
				$subject =$proforma['pro_in_no'].' '.$proforma['customer_name'];  

				$message = '';

				

				if($val['html'])

				{

					$tag_val = array(

					"{{header}}"=>'Proforma Invoice Detail',

						"{{productDetail}}" =>$val['html'],

						"{{signature}}"	=> $signature,

					);

					if(!empty($tag_val))

					{

						$desc = $temp_desc;

						foreach($tag_val as $k=>$v)

						{

							@$desc = str_replace(trim($k),trim($v),trim($desc));

						} 

					}

					$replace = array($subject,$desc);

					$message = str_replace($search, $replace, $output);
                //printr($search);
	///printr($output);

				}
					//printr($val['email']);
				//printr($message);
				/*if($_SESSION['ADMIN_LOGIN_SWISS']==1 && $_SESSION['LOGIN_USER_TYPE']==1)
				{
				    send_email_new($val['email'],$form_email,$subject,$message,'',$url);die; //commented on 10-8-2019
				}
				else*/
				    send_email($val['email'],$form_email,$subject,$message,'',$url); //commented on 10-8-2019

			}
		    /*if($_SESSION['ADMIN_LOGIN_SWISS']==44 && $_SESSION['LOGIN_USER_TYPE']==2)
			    die;*/
			
		}

		public function getColors(){

			$sql = "SELECT * FROM  mailer_bag_color WHERE status='1' AND is_delete = '0' ";

			$sql .= " ORDER BY color";	

			$sql .= " ASC";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->rows;

			}else{

				return false;

			}

		}

		function viewProformaInvoice($proforma_id,$goods_status=0) {

			//echo $proforma_id.'kkkk';
            
            
			$html ='';

			$proforma=$this->getProformaData($proforma_id);

			//printr($proforma);
            
			$proforma_id=$proforma['proforma_id'];

			$proforma_inv=$this->getProformaInvoice($proforma_id);

			$user_name=$this->getUser($proforma['added_by_user_id'], $proforma['added_by_user_type_id']);

		//	printr($user_name);
            
            
			$show_vat='';
			$show_qst='';
			$qst_no = '';
			$admin_vat_no='';
			
		    if($proforma['contact_name']!='')
			    $contact_name='<b>Kind Attention :</b> '.$proforma['contact_name'];
            else
            	$contact_name='';
		

			if($proforma['added_by_user_id'] == '1' && $proforma['added_by_user_type_id'] =='1')

			{

				$image= HTTP_UPLOAD."admin/store_logo/logo.png";

				$img = '<img src="'.$image.'" alt="Image">';

			}

			else

			{

				

				if($proforma['added_by_user_type_id'] == 2){

					$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$proforma['added_by_user_id']."' ");

					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);

					$set_user_id = $parentdata->row['user_id'];

					

					$set_user_type_id = $parentdata->row['user_type_id'];

					//echo "1 echio  ";

				}else{

					$userEmployee = $this->getUserEmployeeIds($proforma['added_by_user_type_id'],$proforma['added_by_user_id']);

					$set_user_id = $proforma['added_by_user_id'];

					//echo $set_user_id."2";

					$set_user_type_id = $proforma['added_by_user_type_id'];

				}

				$user_info=$this->getUser($set_user_id,'4');
                
			
				$data=$this->query("SELECT logo,abn_no,termsandconditions_invoice,note_invoice FROM international_branch WHERE international_branch_id = '".$set_user_id."'");

				//echo "SELECT logo,abn_no,termsandconditions_invoice,note_invoice FROM international_branch WHERE international_branch_id = '".$set_user_id."'";

				//printr($data);

				if(isset($data->row['logo']) &&  $data->row['logo']!= '')

				{

					$image= HTTP_UPLOAD."admin/logo/200_".$data->row['logo'];

	
 
					//echo $image;
                    if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39'))
					    $img = '<img src="'.HTTP_UPLOAD."admin/logo/200_OXYMIST_1.jpg".'" alt="Image" width="65%" id="oxi_img" class="oxi_img">';
                    else
                        $img = '<img src="'.$image.'" alt="Image">';
				}

				else

				{

					$img ='';

				}			

			}
            
            //printr($img);
			
            if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='37' || $set_user_id=='38' || $set_user_id=='39' ))
            {
                $title='Consignor';
               //printr($proforma['destination']);
                $user=$this->getCompanyAdd($proforma['added_by_user_type_id'],$proforma['added_by_user_id']);
                
                $address=nl2br($user['company_address']);
                
                $sign=$user['company_name'];
                $admin_vat_no = 'GST No. : '.$user['vat_no'];
                $vat_no = $proforma['vat_no'];
                $show_vat = 'GST No. :'.$vat_no;
            }
            else
            {
    			if($proforma['destination']==111)
    
    			{
                    
    				$title='Consignor';
    
    				$address='SWISS PAC PVT LTD</b><br>Padra Jambusar National highway
    
    				<br>At Dabhasa village,Pin 391440<br>Taluka.Padra, Dist.Vadodara(State Gujarat) India ';
    
    				$sign='Swiss PAC PVT LTD';
    
    				$user=$this->getCompanyAdd($proforma['added_by_user_type_id'],$proforma['added_by_user_id']);
    
    				$vat_no = $proforma['vat_no'];
    
    				$admin_vat_no = 'GST No. : '.$user['vat_no'];
    
    				$show_vat = 'GST No. :'.$vat_no;
    
    				//$tin_no = '<br>Tin No. :'.$proforma['tin_no'];
    
    			}
    
    			else
    
    			{	
    
    				///$tin_no = '';
    
    				if($proforma['added_by_user_id']!='1' && $proforma['added_by_user_type_id'] != '1')
    
    				{	$title='From';
    
    					$user=$this->getCompanyAdd($proforma['added_by_user_type_id'],$proforma['added_by_user_id']);
                    
    					
    				
    					
    					$address=nl2br($user['company_address']);
                /*	if($proforma['destination']==251 || $proforma['destination']==209)
    					    $sign=$proforma['bank_accnt'];
    				else*/
    					    $sign=$user['company_name'];
                    
    					if($proforma['destination']==155)
    
    						$admin_vat_no = 'RFC No. : '.$user['vat_no'];
    
    					else if( isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='19'))
    					    $admin_vat_no = 'TRN  :'.$user['vat_no'];
    				    else if( isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='44')){
    					    //$admin_vat_no = 'GST/HSTNo.  : '.$user['vat_no'];
    					    if($proforma['state']==11){
        					    $admin_vat_no = 'GST/HSTNo.  : '.$user['vat_no'].'<br> QST No. : 1224569528TQ0001';
    					    }else{
    					        $admin_vat_no = 'GST/HSTNo.  : '.$user['vat_no'];
    					    }
    					    //printr($proforma);
    					    }
    					else
    
    						$admin_vat_no = 'Vat No. : '.$user['vat_no'];
    
    					
    
    					$vat_no = $proforma['vat_no'];
    
    					if($proforma['destination']==155)
    					{
    						$show_vat = 'RFC No. :'.$vat_no;
    					}
    					elseif($proforma['destination']==42)
    					{
    						$show_vat = 'Gst No. :'.$vat_no;
    						if($proforma['qst_no']==0)
    							$show_qst = '';
    						else
    							$show_qst = 'Qst No. :'.$proforma['qst_no'];
    					}
    				
    					else
    					{
    						$show_vat = 'Vat No. :'.$vat_no;
    					}
    			
    					if($user_info['country_id']==155)
    					{//printr($proforma['gen_pro_as']);die;
    					   if($proforma['gen_pro_as']=='1')
    					        $address=nl2br($user['company_address']);
    					   else
    					   {
    					        $address='SWISS PAC PVT LTD</b><br>Padra Jambusar National highway<br>At Dabhasa village,Pin 391440<br>Taluka.Padra, Dist.Vadodara(State Gujarat) India ';
    					        $admin_vat_no = 'Vat No. : 24AADCS2724B1ZY';
    					       
    					   }
    					}
    					
    					if($user_info['country_id']==11)
    					{//printr($proforma['gen_pro_as']);die;
    					   if($proforma['gen_pro_as']=='1')
    					        $address=nl2br($user['company_address']);
    					   else
    					   {
    					        $address='SWISS PAC PVT LTD</b><br>Padra Jambusar National highway<br>At Dabhasa village,Pin 391440<br>Taluka.Padra, Dist.Vadodara(State Gujarat) India ';
    					        $admin_vat_no = 'GST No. : 24AADCS2724B1ZY';
    					       
    					   }
    					}
    				}
    
    				else
    
    				{
    
    					$title='Consignor';
    
    					$address='SWISS PAC PVT LTD</b><br>Padra Jambusar National highway
    
    					<br>At Dabhasa village,Pin 391440<br>Taluka.Padra, Dist.Vadodara(State Gujarat) India ';
    
    					$sign='Swiss PAC PVT LTD';
    
    				}
    
    				
    
    			} 
            }
            if($user_info['country_id']==11)
    		{
    		    $show_vat='';
    		}
            
           
             if($goods_status==1){
               $title_pro='<b> SALES TAX INVOICE <span style="color:#f92c09" >( GOODS NOT DISPATCHED )</span></b>'; 
             }else{
                     if($proforma['proforma_title']!=''){
                        $title_pro = $proforma['proforma_title'];
                     }else{
                          $title_pro = 'PROFORMA INVOICE';
                     }
             } 
             $state_text='';
            if($proforma['state_india']!=0){
               $state_details=$this->getIndiaStateDetails($proforma['state_india']); 
               $state_text='State: '.$state_details['state'].' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    State Code: '.$state_details['state_code_in_no'];
            }
            
        //   printr($title_pro .'==='.$goods_status);
			$html .='<div class="width_div"><div style="text-align:center;border: 1px solid black;">'.$title_pro;

			if($proforma['discount']!='0') { $html.='<span style="float:right;font-size:14px;">'.($proforma['discount'] + 0).'</span>'; }

			$html.='</div>

						<div class="" style=" width: 100%;float: left;  border: 1px solid black;font-size: 18px;">

							<table style="width: 100%;" >

								<tr>

									<td style="vertical-align: top;width: 50%;">';
                                        $Consignee ='Consignee';$Delivery_add ='Delivery Address';
										if($user_name['country_id']=='14')

											$html .=$img.'<br><br>';

										if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='37' || $set_user_id=='38'))
										{
										    $html .=$img.'<br><br>';
										    $html .='<p><b>'.$title.'<br></p><p>'.$address.'<br></p>'.$admin_vat_no.'</b>';
										    
										    $Consignee = 'Buyer'; $Delivery_add='Consignee & Delivery Address';
										}
										elseif (isset($set_user_id) && !empty($set_user_id) && $set_user_id=='39' || isset($set_user_id) && !empty($set_user_id) && $set_user_id=='44')
										{
										    $html .=$img.'<br><br>';
										    $html .='<p><b>'.$title.'</b><br></p><p>'.$address.'<br></p>'.$admin_vat_no;
										}
										else
										{
									        $html .='<p><b>'.$title.'<br></p><p>'.$address.'<br></p>';
									        
									       $html .=$admin_vat_no; 
									      
									           
									       $html .='</b>';
										}
								$html .='	</td>

									<td style="padding: 0px;vertical-align: top;">

										<table style=" width: 100%;border: 1px solid black; border-spacing: 0px;" cellspacing="0px" cellpadding="10px"  >

											<tbody><tr>

												<td valign="top"><b>Invoice No.&amp; Date</b></td>

												<td>'.$proforma['pro_in_no'].' &nbsp;/&nbsp; '.dateFormat(4,$proforma['invoice_date']).'<br><br><br><span class="barcode" >'.$proforma['pro_in_no'].'</span><br><br></td>

												</tr>

											<tr>

												<td><b>Proforma :</b></td>

												<td>&nbsp;'.dateFormat(4,$proforma['proforma']).'</td>

											</tr>

											<tr>

												<td><b>Buyers Order No. &amp; Date:</b></td>

												<td>'.$proforma['buyers_order_no'].' &nbsp;/&nbsp; '.dateFormat(4,$proforma['buyers_date']).'</td>

											</tr>

											<tr>

												<td><b>Country of origin of goods:</b></td>

												<td>'.$proforma['goods_country'].'</td>

											</tr>

											</tbody>

										</table>

									</td>

								</tr>

							</table>

						</div>

						

						<div class="" style="width: 100%; float: left;  border: 1px solid black;font-size: 18px;">

						

							<table style="width: 100%;">

								<tr>

									<td style="vertical-align: top;">

									    <p><b>'.$Consignee.'</b></p>

										<p><b>'.$proforma['customer_name'].'</b><br/>'.nl2br($proforma['address_info']).'<br/>'.$state_text.'<br/>Email : '.$proforma['email'].'<br>Contact No. : ' . $proforma['contact_no'] . '<br>'.$show_vat.'<br>'.$show_qst.'<br>'.$contact_name.'</p>

									</td>';
									
									
									if($proforma['same_as_above']!='1')
									{
												
									
									$html .= '<td style="vertical-align: top;">
									
            									<p><b>'.$Delivery_add.'</b></p>';
            
            									if($user_name['country_id']=='111')
            									    $html .= '	<p>' . nl2br($proforma['del_address_info']) . '<br/>Email : ' . $proforma['email'] . '<br></p>';
            									else
            									    $html .= '	<p><b>' . $proforma['customer_name'] . '</b><br/>' . nl2br($proforma['del_address_info']) . '<br/>Email : ' . $proforma['email'] . '<br>' . $show_vat . '<br>' . $show_qst . '<br>'.$contact_name.'</p>';
								            $html .= '  </td>';
									}

									$html .= '<td style="padding: 0px;vertical-align: top;">

										<table cellspacing="0px" cellpadding="0px" style=" border-spacing: 0px; width: 100%;border: 1px solid black;padding: 0px;">

											<tbody><tr>

												<td style="text-align:center" colspan="2"> <b>Terms of Delivery &amp; Payment</b></td>

											</tr>

											<tr>

												<td><b>Delivery:</b></td>

												<td>'.$proforma['delivery_info'].'</td>

											</tr>';

											if($user_name['country_id']!='14')

											{

												$html.='<tr>
	
														<td><b>Mode Of Shipment:</b></td>
	
														<td>';
														
												if($user_name['country_id']=='42')
												{
													if(ucwords(decode($proforma['transportation']))=='Air')
														$html.='By Rush Order';
													else
													   	$html.='By Normal Order';
													
												}
												
												else
												{
													
													if(decode($proforma['transportation'])=='road')
													{
													  if($user_name['user_id']=='19')
												        $html.='Pickup From Warehouse';
												      else
													    $html.='By Pickup';
													}
													elseif(decode($proforma['transportation'])=='by road')
													    $html.='By Road';
													else
													    $html.='By '.ucwords(decode($proforma['transportation']));
													
												}
												$html.='</td>
												</tr>';

											}

									$html.='<tr>

												<td><b>Payment Terms:</b></td>

												<td>'.$proforma['payment_terms'].'</td>

											</tr>

									</tbody>

										</table>

										<table style=" border-spacing: 0px; width:100%;border: 1px solid black;"  width: 100%;>

											 <tbody><tr>

												<td><b>Port Of Loading:</b></td>';

												if($user_name['country_id']!='14')

													$html.='<td><b>Final Destination:</b></td>';

												else

													$html.='<td></td>';

										$html.='</tr>';

											 $con_id =$proforma['destination'];

											$countrys = $this->getCountry($con_id);

                                        

									$html.='<tr><td>'.$proforma['port_loading'].'</td>';

												if($user_name['country_id']!='14')

													$html.='<td>'.$countrys['country_name'].'</td>';

												else

													$html.='<td></td>';

										$html.='</tr>

											</tbody>

										</table>

									</td>

								</tr>

							</table>

							

						</div>';	
	
						

				$currency = $this->getCurrencyId($proforma['currency_id']);
                    
					$html .='<div class="" style="width: 100%; float: left;  border: 1px solid black;">

							<table cellspacing="0px" cellpadding="10px" border="1" style="width: 100%; border-spacing: 0px;font-size: 14px;">

								<tbody>

								<tr>

									<td width="5%"><div align="center"><b>Sr. No</b></div></td>

									<td width="60%"><div align="center"><b>Description of Goods ';
									
									if($user_name['country_id']=='155')
									{
									    if($con_id!=155)
									         $html .='<span style="float: right;">Código HS :  3923 2990</span>';
									}
									
									
									
									$html .='</b></div></td>

									<td width="10%"><b>Quantity In</b></td>

									<td width="15%"><b>Rate In &nbsp;'.$currency['currency_code'].'</b></td>

									<td width="10%"><b>Amount In &nbsp;'.$currency['currency_code'].'</b></td>

								</tr>';

								$total = 0;$total_rate=0; $final_total=0; $n=1;$total_excies_rate=0;$ex_per=array();$total_taxation_rate=0;$tax_per=array();$taxa=array();
								$total_igst_rate = 0;
								$total_sgst_rate = 0;
								$total_cgst_rate = 0;

								//[kinjal] : added on [22-8-2016]

								$custom_pro_id = 0;
                if(!empty($proforma_inv)){
					foreach($proforma_inv as $invoice_key=>$invoice){

							

									$product_code_data = $this->getProductCode($invoice['product_code_id']);
                                    $accessorie_second='';
								    if($product_code_data['accessorie_second']!='')
								    {
									    $accessorie_second = $this->getAccessorie($product_code_data['accessorie_second']);
									    $accessorie_second = ', '.$accessorie_second['product_accessorie_name'];
								    }
									//[kinjal] : added on [22-8-2016]

									/*if($product_code_data['product_code'] != '')

										$result = substr($product_code_data['product_code'], 0, 4);*/

										

									if(strrchr($product_code_data['product_code'],'CUST'))

										$custom_pro_id = 1;

									
                                    //commented by kinjal on 8-7-2017 told by bhaveshbhai
									$zipper_name=$spout_name=$acc_name=$valve_name='';

									
									if($product_code_data['valve']=='With Valve')

										$valve_name=$product_code_data['valve'];

									if($product_code_data['zipper_name']!='No zip')

										$zipper_name=$product_code_data['zipper_name'];

									if($product_code_data['spout_name']!='No Spout')

										$spout_name=$product_code_data['spout_name'];
									
									if($product_code_data['product_accessorie_name']!='No Accessorie')	
										
										$acc_name=$product_code_data['product_accessorie_name'];
                                    if($accessorie_second!='')
                                        $acc_name.=' '.$accessorie_second;
									if($invoice['product_code_id']!='-1' && $invoice['product_code_id'] !='0')

										$get_size = $this->getSizeDetail($product_code_data['product'],$product_code_data['zipper'],$product_code_data['volume'],$product_code_data['measurement']);

									else

										$get_size = array('size_master_id'=> '',

															'product_id'=>'',

															'product_zipper_id'=>'',

															'volume'=>'',

															'width'=>'',

															'height'=>'',

															'gusset'=>'',

															'weight'=>'');

										//printr($get_size);
                                    	if($product_code_data['product'] == 10)
                                            	    $mes_size='inch';
                                            	 else
                                            	     $mes_size='mm';
										if($product_code_data['product'] == 3 || $product_code_data['product'] == 8)

										{

											$gusset = floatval($get_size['gusset']).'+'.floatval($get_size['gusset']);

										}

										else

										{

											$gusset = floatval($get_size['gusset']);

										}

									$measure = $this->getMeasurementName($invoice['measurement']); 

									$html .='<tr><td>'.$n.'</td>';

										

										$clr_text='';
										if($invoice['product_code_id']=='-1')

										{

											$clr_nm = 'Custom';

											$custom_pro_id = 1;

											$clr_text = "(".$invoice['color_text'].")";

											$p_nm = 'Custom';

											$size_product = '</b> ('.$invoice['size'].' '.$measure['measurement'].')';

										}

										elseif($invoice['product_code_id']=='0')

										{

											$clr_nm = 'Cylinder';

											$p_nm = 'Cylinder';

											$size_product = '</b> ('.$invoice['size'].' '.$measure['measurement'].')';

										}

										else

										{

											$clr_nm = $product_code_data['color'];
                                            $p_nm = $product_code_data['product_name'];
                                            $haystack = strtoupper ($product_code_data['product_code']);
                                            $needle   = strtoupper ("cylinder");
                                            
                                            if( strpos( $haystack, $needle ) !== false ) {
                                                $p_nm = 'Cylinder';
                                            }
                                            
										

											$size_product = '</b>'.floatval($get_size['width']).' '.$mes_size.' &nbsp;Width &nbsp;X&nbsp;'.floatval($get_size['height']).' ' .$mes_size.' &nbsp;Height &nbsp;';
                                            
										}
                                        if($product_code_data['width']!=0 || $product_code_data['height']!=0)
                                        {
                                            
                                       
                                            
                                      //      echo $product_code_data['gusset'];
			                                if($product_code_data['gusset']!=0)
			                                {
			                                	if($product_code_data['product'] == 3)
			                                	{

											        $gusset = floatval($product_code_data['gusset']).'+'.floatval($product_code_data['gusset']);

										        }

        										else
        
        										{
        
        											$gusset = floatval($product_code_data['gusset']).'Gusset';;
        
        										}
			                                }
			                                else
			                                    $gusset=0;
			                                	$size_product = '</b>' . floatval($product_code_data['width']) . ''.$mes_size.' &nbsp;Width &nbsp;X&nbsp;' . floatval($product_code_data['height']) . ' '.$mes_size.' &nbsp;Height &nbsp;';
			                                	
                                        }
				                        
										if($invoice['color_text']!='')
                                                $clr_text = "(".$invoice['color_text'].")";
                                          //printr(//)
									    $pro_code = '';
									    //if($proforma['destination']==155)
									        $pro_code = $product_code_data['product_code'].'<br>';
									    
									   if($invoice['product_code_id']!='3254' && $invoice['product_code_id']!='1194') 
									   {
									        
    									    
    									       if(isset($set_user_id) && !empty($set_user_id) && (($set_user_id!='37')))
        								       {
        								         if($product_code_data['product'] == 6){
        								             if($product_code_data['width']>0)
            									        $html .='<td>'.$pro_code.'<b>Size : </b>'.floatval($product_code_data['width']).'mm &nbsp; Roll Width';
            									     else
            									       $html .='<td>'; 
        								         }
            									 else{
            									     if(($product_code_data['product'] != '37' && $product_code_data['product'] != '38') && (isset($set_user_id) && !empty($set_user_id) || ( $set_user_id!='19' && $product_code_data['product'] != '51')))
            									        $html .='<td>'.$pro_code.'<b>Size : '.$size_product;
            									     else
            									        $html .='<td>';
            									    }    
            											if($gusset>0)
            
            												$html .='X&nbsp;'.$gusset.' mm Gusset';
            
            											if($get_size['volume']>0 && $product_code_data['width']==0 && $product_code_data['height']==0 && $product_code_data['gusset']==0 ){
            											    
            											    // change by sonu 06-06-2018 told by gopidii
                    											    $e_volume="";
                                                            	   
                                                            	    if($get_size['volume']=='50-70 gm')
                                                            	        $e_volume="BIG";
                                                            	    if($get_size['volume']=='30-50 gm')
                                                            	        $e_volume="SMALL";
                                                            
                                                            
                                                     
            												if($product_code_data['product'] != '37' && $product_code_data['product'] != '38')
            												    $html .=' ('.$get_size['volume'].') '.$e_volume.' ';
            												         //end  
            											
            											}
        								       }
                                            else
                                            {
                                                $html .='<td>';
                                            }
    											
                                        if($product_code_data['product'] == 6)
    										$html .='<br><b>Make up of pouch :</b>'.$p_nm.'<b>&nbsp;<br>';
    									else
    									{
    									    if( strpos( $haystack, $needle ) !== false ) 
    									  {
    									      $html .='<br>';
    									  }
    									  else
    									  {
    									       if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id!='37'))
    									       {
        									        if($product_code_data['product'] != '37' && $product_code_data['product'] != '38')
        									            $html .='<br><b>Make up of pouch :</b>'.$p_nm.'<b>&nbsp;'.$zipper_name.'&nbsp;'.$valve_name.' &nbsp;'.$spout_name.' &nbsp;'.$acc_name.'</b><br>';
        									        else
        									           $html .='<br>'; 
    									       }
    									  }
    									}
    											//printr($clr_text);
                                          
                                        if($invoice['filling']!='')
                                        {
    											if($product_code_data['product']=='1')
    											    $html.='<br><b>Sealing Option: </b>'.$invoice['filling'].' - '.$invoice['prodes'].'<br>';
    											else
    											   $html.='<br><b>Filling Option: </b>'.$invoice['filling'].' - '.$invoice['prodes'].'<br>'; 
                                        }
    									 if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id!='37'))
    									 {
        									 if($product_code_data['product'] != '37' && $product_code_data['product'] != '38')
        									 {
            									 if( strpos( $haystack, $needle ) !== false ) 
            									    $html .='<b>'.$clr_text.'<br></b>';
            									 else
            									 {
            										if($invoice['stock_print']=='Digital Print')
            										    $html .='<b>Color : '.$clr_nm.' <br></b>';
            										else
            										    $html .='<b>Color : '.$clr_nm.'&nbsp;   '.$clr_text.'<br></b>';
            									 }
        									 } 
    									 }
        							  }
        							  else
        							     $html .='<td>';
        									   $des ='';
        						
                                       if($invoice['prodes']!='') 
                                            $des =  ' ( '.$invoice['prodes'].' )<br>';
										
										if($invoice['description']!='')
                                           
										{
										  //  printr($invoice['description']);
                                          if($product_code_data['product'] != '37' && $product_code_data['product'] != '38')
                                          {
										    if($invoice['stock_print']=='Digital Print')
										        $html .='<b>Description : </b>'.$invoice['color_text'];
										    else if($product_code_data['product'] == '51' && isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='19') )
										        $html .='<b>Description : </b> Custom Cylinders Per Colour';
										    else
										        $html .='<b>Material Description : </b>'.$invoice['description'];
                                          }
                                          else
                                            $html .='<b>Product Description : </b>'.$invoice['description'];
										}
                                      // printr($product_code_data['product_code']);
                                        if($invoice['prodes']!='')
                                        {
                                            if( isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='19')){
            									  if($product_code_data['product'] != '51')
            									       $html .='</br><b>Material : </b>'.$invoice['prodes'];
        									}
                                            else
                                                $html .='</br><b>Description : </b>'.$invoice['prodes'];
                                        }
                                        
										if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='37' || $set_user_id=='38'))
										{
										    if($product_code_data['product'] == 11)
										        $html .='<b><span style="float: right;">HSN Code : 39235090</span></b>';
										}  
										else if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39'))
										{
										    if($product_code_data['product'] == 37)
										        $html .='<b><span style="float: right;">HSN Code : 38249990</span></b>';
										    else if($product_code_data['product'] == 38)
										        $html .='<b><span style="float: right;">HSN Code : 38249025</span></b>';
										}
										else
										{
    										if($user_name['country_id']=='111' || $proforma['hsn_code']=='1')
    										{
    										    if($product_code_data['product'] == '51' ) 
    										         $html .='<b><span style="float: right;">HSN Code : 84425010</span></b>';
        									    else
        									    {
        									       if($product_code_data['product'] != '60')
        									            $html .='<b><span style="float: right;">HSN Code : 39232990</span></b>';
        									    }
    										}    
										}
									$cust = strtoupper ($product_code_data['product_code']);
                                    $code_cust   = strtoupper ("CUST");	
                                    $silica_nfg   = strtoupper ("NFG");	
                                    $silica_contg   = strtoupper ("CONTAINERG");	
									if( isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='19')){
									   
									    	
                                      
									  if( strpos( $cust, $code_cust ) !== false && $product_code_data['product'] != 51)
										{ 
										    
										    if($invoice['plus_minus_quantity']!=0)
										        $plus_minus_quantity=$invoice['plus_minus_quantity'];
										    else
										   	    $plus_minus_quantity = $this->getcalculatePlusMinusQuantity($invoice['quantity'],$product_code_data['product'],$product_code_data['height'],$product_code_data['width'],$product_code_data['gusset'],'',''); 
										   	    
									        $html .='<div> <b>Production variation</b> : For '.number_format($invoice['quantity'],"0", '.', '').' bags Plus or minus '.$plus_minus_quantity.' pieces';
										}
									}
									if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='10') || isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='19'))
									{
										/*$cust = strtoupper ($product_code_data['product_code']);
                                        $code_cust   = strtoupper ("CUST");*/
										if( strpos( $cust, $code_cust ) !== false )
										{ 	
										    
										        if($product_code_data['product'] != 51)
        										{
        										     $effect = $this->query("SELECT * FROM printing_effect WHERE printing_effect_id = '".$invoice['printing_option']."'");
        										    	if( isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='19')){
                    									  	$html .='<div><b>Printing Effect:</b> '.$effect->row['effect_name'].'+';
                    									  	 $html .='Gusset Printing Type: '.$invoice['gusset_printing_option'];
                    									}
        										    else{
        										    
        										    
                										   
                											$html .='<div>';
                											if(isset($effect->row['effect_name']))
                												$html .='Printing Effect: '.$effect->row['effect_name'].'<br>';
                									
                									        $html .='Gusset Printing Type: '.$invoice['gusset_printing_option'].'<br>
                											Variation: Plus or minus <b>4500</b> pouches<br>
                											
                											                <div >';
                											if($invoice['rate']!=0)
                											   $html .=' Normal Delivery: <b>Up to our warehouse. 14 weeks aprox</b>';
                											if($invoice['express_rate']!=0)
                												$html .='<br>Express Delivery: <b>Up to our warehouse. 6 - 8 weeks</b>';
                											
                											$html .='</div></div>';
        										    }
                							}
										}
									}
									
									if($invoice['plate']!=0 && $invoice['stock_print']=='Digital Print')
									   $html .='<br/><br/><br/><div style="vertical-align: bottom;"><b>Plates For Digital Printing</b> </div>';
									if($invoice['plate']!=0 && $invoice['stock_print']=='Foil Stamping')
									   $html .='<br/><br/><br/><div style="vertical-align: bottom;"><b>Plates For Foil Stamping</b> </div>';
									 if($invoice['tool_price']!='0')
										$html .='<br/><div style="vertical-align: bottom;"><b>Tool Price : </b><br></div>';
									//this condition mention stock items's box qty kinjal[29-8-2019] (Order by Prashant sir)
									/*if(strpos( $cust, $code_cust ) == false )
									{
									    if($user_name['country_id']!='111' )//$_SESSION['LOGIN_USER_TYPE']==1 &&  $user_name['country_id']!='214'
									    {
									        $box_qty = $this->getInvoiceProductWithBox($invoice['product_code_id']);
									        if(!empty($box_qty['quantity']))
									            $html .='<br/><div style="vertical-align: bottom;"><b>1 Box = </b>'.$box_qty['quantity'].' Quantity<br></div>';
									    }
									}*/
									
									$html .='</td><td><br><br><div align="center">';

										if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='10'))
										{
										    if($invoice['rate']!=0)
										        $total = $total+$invoice['quantity'] ;
										}
									    else
        										$total = $total+$invoice['quantity'] ;
        										
        								$total_qty = $invoice['quantity'];//printr($total);printr($total_qty);
                                        if($product_code_data['product'] == 6)
									    	$html .=$total_qty.'  Kgs</div><br>';
									    else
										{
											if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='10'))
											{
												if( strpos( $cust, $code_cust ) !== false )
												{
													if($product_code_data['product'] == 51)
                                                        $html .='<div id="express">'.number_format($total_qty,"0", '.', '');    										        
    										        else
    										        {
    													$html .='<div id="express" style="margin-bottom: -6cm;">';
    													
    													if($invoice['rate']!=0)
    													    $html .=number_format($total_qty,"0", '.', '');
    													if($invoice['express_rate']!=0)
    													{
    														$total = $total+$invoice['quantity'] ;
    														$html .='<br>'.number_format($total_qty,"0", '.', '');
    													}
    										        }
													$html .='</div>';
												}
												else
													$html .=number_format($total_qty,"0", '.', '');
												
												$html .='</div><br>';
											}
											else
											{
												if( strpos( $cust, $silica_nfg ) !== false || strpos( $cust, $silica_contg ) !== false)
											         $html .=number_format($total_qty,"0", '.', '').' Kgs</div><br>';
												else
												    $html .=number_format($total_qty,"0", '.', '').'</div><br>';
											}   
										}
									if($invoice['plate']!=0)
									{
									   $total = $total+$invoice['plate'] ;
									   $html .='<br/><div align="center" style="vertical-align: bottom;">'.$invoice['plate'].'</div>';
									}
									
									$html .='</td><td><br><br><div align="right">';

										$total_rate=$total_rate+$invoice['rate'];$total_rt = $invoice['rate'];
                                        
                                        if($product_code_data['product'] == 6)
										    $html .=$total_rt.' Per 1 Kgs</div><br>';
										else
										{
											if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='10'))
											{
												if( strpos( $cust, $code_cust ) !== false )
												{
													if($product_code_data['product'] == 51)
													    $html .='<div id="express">'.$total_rt;
													else
													{
    													$html .='<div id="express" style="margin-bottom: -6cm;">';
    													if($invoice['rate']!=0)
    													    $html .=$total_rt;
    													if($invoice['express_rate']!=0)
    														$html .='<br>'.$invoice['express_rate'];
													}
													$html .='</div>';
												
												}
												else
													$html .=$total_rt;
												
												$html .='</div><br>';
											}
											else
												$html .=$total_rt.'</div><br>';
										}
										
										    // $html .=$total_rt.'</div><br>';

									if($invoice['plate']!=0 && $invoice['stock_print']=='Digital Print')
									{
									   $total_rate=$total_rate+$user_info['color_plate_price'];
									   $html .='<br/><div align="right" style="vertical-align: bottom;">'.$user_info['color_plate_price'].' Per 1 Plate</div>';
									}
									if($invoice['plate']!=0 && $invoice['stock_print']=='Foil Stamping')
									{
									   $total_rate=$total_rate+$user_info['foil_plate_price'];
									   $html .='<br/><div align="right" style="vertical-align: bottom;">'.$user_info['foil_plate_price'].' Per 1 Plate</div>';
									}
									$html.='</td><td><br><br><div align="right">';

										$total_amnt = $invoice['quantity'] * $invoice['rate'];
										$ex_amt=0;
										if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='10'))
										{
											if( strpos( $cust, $code_cust ) !== false )
											{
												if($product_code_data['product'] == 51)
												    $html .='<div id="express" >'.$total_amnt;
												else
												{
    												$html .='<div id="express" style="margin-bottom: -6cm;">';
    												if($invoice['rate']!=0)
    													$html .=$total_amnt;
    												
    												if($invoice['express_rate']!=0)
    												{
    													$html .='<br>'.$invoice['quantity'] * $invoice['express_rate'];
    													$ex_amt = $invoice['quantity'] * $invoice['express_rate'];
    												}
												}
												$html .='</div>';
											}
											else
												$html .=$total_amnt;
											
											$html .='</div><br>';
										}
										else
											$html.= $total_amnt.'</div><br>';
										
									//	 printr($total_amnt);

										$final_total=$final_total+$total_amnt+$ex_amt;
                                        
                                    if($invoice['plate']!=0 && $invoice['stock_print']=='Digital Print')
									{
									   $final_total=$final_total+($user_info['color_plate_price']*$invoice['plate']);
									   $html .='<br/><div align="right" style="vertical-align: bottom;">'.$user_info['color_plate_price']*$invoice['plate'].'</div>';
									} 
                                    if($invoice['plate']!=0 && $invoice['stock_print']=='Foil Stamping')
									{
									   $final_total=$final_total+($user_info['foil_plate_price']*$invoice['plate']);
									   $html .='<br/><div align="right" style="vertical-align: bottom;">'.$user_info['foil_plate_price']*$invoice['plate'].'</div>';
									} 
                                    if($invoice['tool_price']!='0')
									{
										$html .='<br><div align="right" style="vertical-align: bottom;">'.$invoice['tool_price'].'</div>';
										$final_total=$final_total+$invoice['tool_price'];
									}
    									  
									$html .='</td></tr>';

									$n++;
					    
					}}
                               // $freight_charges=0;
                                $html.='<tr>

										<td></td>

										<td><div align="right"><strong>Sub Total</strong></div></td>

										<td>&nbsp;</td>

										<td>&nbsp;</td>

										<td>'.round($final_total,3).'</div></td>

									  </tr>';
									$dis_total=($final_total*$proforma['discount'])/100;
                                   
                                //    printr($dis_total);
                                
                                //Discount
									$final_total=$final_total-$dis_total;

									
 
									if($dis_total!=0)
									{
    									$html.='<tr>
    
    										<td></td>
    
    										<td><div align="right"><strong>Discount</strong>( '.($proforma['discount'] + 0).' % )</div></td>
    
    										<td>&nbsp;</td>
    
    										<td>&nbsp;</td>
    
    										<td><div align="right">'.round($dis_total,3).'</div></td>
    
    									  </tr>';
                                         $html.='<tr>

    										<td></td>
    
    										<td><div align="right"><strong>Sub Total</strong></div></td>
    
    										<td>&nbsp;</td>
    
    										<td>&nbsp;</td>
    
    										<td>'.round($final_total,3).'</div></td>
    
    									  </tr>';
									}

								if($proforma['freight_charges']!=0)

								{

									$freight_charges=round($proforma['freight_charges'],3);

										$final_total=$final_total+$freight_charges;

									

									$html .='<tr>

											<td></td>

												<td><div align="right">

														<strong>Freight Charges </strong>

														</div></td>

													 <td><b></b></td>

												<td></td>

												<td><p align="right">'.$freight_charges.'</p></td>

										  </tr>';

								//	}

								}

								else

								{
                                    	
									$final_total=$final_total;

								}
                               // printr($final_total);
								/*if($proforma['destination']!=111)

								{*/
                                    //printr($proforma['discount']);
                                
                                //added by kinjal on 24-9-2019  (mail by fagunbhai)
                                if($proforma['delivery_charges']!='0.00'){
                                       // printr($final_total);
                                    //    printr($total);
                                        $final_total=$final_total+$proforma['delivery_charges'];
                                        	$html.='<tr>
    
    													<td></td>
    
    													<td><div align="right"><strong>Delivery Charges</strong></div></td>
    
    													<td>&nbsp;</td>
    
    													<td>&nbsp;</td>
    
    													<td><div align="right">'.$proforma['delivery_charges'].'</div></td>
    
    												  </tr>';
                                }    
                                 
									 

								

								/*}*/

								$gst = 0;
									//add  	Indonesia -> $proforma['destination']!='112' sonu told by vijay sir    
								if($proforma['destination']!='111' && $proforma['destination']!='42'&& $proforma['destination']!='112' )

								{
								    	if($proforma['destination']=='209' || $proforma['destination']=='251'|| $proforma['destination']=='235' )
							                 $label="Vat Tax";
							             else
							                $label="Gst Tax";

									$gst = (($final_total*$proforma['gst_tax'])/100);

									$html.='<tr>

										<td></td>

										<td><div align="right"><strong>'.$label.' ('.$proforma['gst_tax'].') % </strong></div></td>

										<td>&nbsp;</td>

										<td>&nbsp;</td>';
										
                                         if($proforma['destination']=='14')
        									$html.='<td><div align="right">'.number_format($gst,2).'</div></td>';
    									else
                                            $html.='<td><div align="right">'.$gst.'</div></td>';
                                            
									  $html.='</tr>';

								}

								$tax_price = 0;

								if($proforma['destination']=='42')

								{
								    
                                   $pst_text=' Gst PST Tax';
                                    if($proforma['state']=='11')
                                        $pst_text='QST Tax';
								    

									if(($proforma['gst']!='0.000' || $proforma['pst']!='0.000') && $proforma['hst']=='0.000')

									{

	

											$tax_gst = number_format($final_total * ($proforma['gst'] / 100),2);  

	

											$tax_pst_price = number_format($final_total * ($proforma['pst'] / 100),2);                      					

	

											$tax_price=($final_total * ($proforma['gst'] / 100))+($final_total * ($proforma['pst'] / 100));

											 //   printr($proforma);

											$html.='<tr>

													<td></td>

													<td><div align="right"><strong>Gst Tax ('.$proforma['gst'].') % </strong></div></td>

													<td>&nbsp;</td>

													<td>&nbsp;</td>

													<td><div align="right">'.$tax_gst.'</div></td>

												  </tr>';

											$html.='<tr>

													<td></td>

													<td><div align="right"><strong> '.$pst_text.' ('.$proforma['pst'].') % </strong></div></td>

													<td>&nbsp;</td>

													<td>&nbsp;</td>

													<td><div align="right">'.$tax_pst_price.'</div></td>

												  </tr>';

									}

	

									else if($proforma['hst']!='0.000' && ($proforma['gst']=='0.000' && $proforma['pst']=='0.000'))

	

									{

	

											$tax_hst = number_format($final_total * ($proforma['hst'] / 100),2); 

	

											$tax_price = ($final_total * ($proforma['hst'] / 100));

											

											$html.='<tr>

													<td></td>

													<td><div align="right"><strong>HST Tax ('.$proforma['hst'].') % </strong></div></td>

													<td>&nbsp;</td>

													<td>&nbsp;</td>

													<td><div align="right">'.$tax_hst.'</div></td>

												  </tr>';

											

									}

	

									else if($proforma['hst']=='0.000' && $proforma['gst']=='0.000' && $proforma['pst']=='0.000')

	

									{

	

											$tax_gst = number_format($final_total * ($proforma['gst'] / 100),2);  

	

											$tax_pst_price = number_format($final_total * ($proforma['pst'] / 100),2); 

	

											$tax_price=($final_total * ($proforma['gst'] / 100))+($final_total * ($proforma['pst'] / 100));

	

											

											$html.='<tr>

													<td></td>

													<td><div align="right"><strong>GST Tax ('.$proforma['gst'].') % </strong></div></td>

													<td>&nbsp;</td>

													<td>&nbsp;</td>

													<td><div align="right">'.$tax_gst.'</div></td>

												  </tr>';

										

											$html.='<tr>

													<td></td>

													<td><div align="right"><strong> '.$pst_text.'  ('.$proforma['pst'].') % </strong></div></td>

													<td>&nbsp;</td>

													<td>&nbsp;</td>

													<td><div align="right">'.$tax_pst_price.'</div></td>

												  </tr>';

									}

								}
                                //printr($tax_price);
								$Total_price = $gst+$final_total+$tax_price;
                               	if( isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='19')){
                                    //commented by kinjal on 24-9-2019
                                    /*if($proforma['delivery_charges']!='0.00'){
                                       // 
                                    //    printr($total);
                                        $final_total=$final_total+$proforma['delivery_charges'];
                                        	$html.='<tr>
    
    													<td></td>
    
    													<td><div align="right"><strong>Delivery Charges</strong></div></td>
    
    													<td>&nbsp;</td>
    
    													<td>&nbsp;</td>
    
    													<td><div align="right">'.$proforma['delivery_charges'].'</div></td>
    
    												  </tr>';
                                    } */
                                    if($proforma['other_charges']!='0.00'){
                                       // printr($final_total);
                                    //    printr($total);
                                        $final_total=$final_total+$proforma['other_charges'];
                                        	$html.='<tr>
    
    													<td></td>
    
    													<td><div align="right"><strong>Other Charges('.$proforma['other_charges_comments'].')</strong></div></td>
    
    													<td>&nbsp;</td>
    
    													<td>&nbsp;</td>
    
    													<td><div align="right">'.$proforma['other_charges'].'</div></td>
    
    												  </tr>';
                                    }
                               	}
								$html.='<tr>

									<td></td>

									<td></td>

									<td>&nbsp;</td>

									<td>&nbsp;</td>

									<td>&nbsp;</td>

								  </tr><tr>

									<td></td>

									<td></td>

									<td><p align="center"><b>'.$total.'</b></p></td>

									<td><p align="right">Total('.$currency['currency_code'].')</p></td>';
                                          
                                    if($user_name['country_id']=='14' || $user_name['country_id']=='155'){
                                        $html.='<td><p align="right"><b>'.number_format ($gst+$final_total+$tax_price,2).'</b></p></td>';
                                        $amt2 = "UPDATE `" . DB_PREFIX . "proforma_product_code_wise` SET invoice_total = ".$this->numberFormate($gst+$final_total+$tax_price,2)." WHERE proforma_id=".$proforma_id;
                                        // printr(number_format ($gst+$final_total+$tax_price,2));
                                         $number = $this->convert_number_cent($this->numberFormate($gst+$final_total+$tax_price,2),$proforma['currency_id']);
                                   } else{
                                        //if($user_name['country_id']=='214')
                                            
									    $html.='<td><p align="right"><b>'.round($gst+$final_total+$tax_price,3).'</b></p></td>';
									    $amt2 = "UPDATE `" . DB_PREFIX . "proforma_product_code_wise` SET invoice_total = ".round($gst+$final_total+$tax_price,3)." WHERE proforma_id=".$proforma_id;
									    $ammt=$gst+$final_total+$tax_price;
									    if($user_name['country_id']=='214'){
									        $number = $this->convert_number_cent(round($gst+$final_total+$tax_price,2),$proforma['currency_id']);
									    }else{
									        $number = $this->convert_number_cent(round($gst+$final_total+$tax_price,2),$proforma['currency_id']);
									    }
									   
                                   }
                                   $this->query($amt2);
								  $html.='</tr>';

							if(isset($user_name['country_id']) && !empty($user_name['country_id']) && $user_name['country_id']==111)

					  		{

						 			
									 //$html.='<tr>';
									if($proforma['packing_charges']!=0)
									{
										$packing_charges=round($proforma['packing_charges'],3);
										if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']!=111)
										{
											$final_total=$final_total;
										}
										else
										{
											$final_total=$final_total+$packing_charges;
										
											$html .='<tr>
													<td></td>
														<td><div align="right">
																<strong>Packing Charges </strong>
																</div></td>
															 <td><b></b></td>
														<td></td>
														<td><p align="right">'.$packing_charges.'</p></td>
												  </tr>';
										}
									
									    
									}
									
									
									
									//printr($proforma);
									//[kinjal]add tintie product told by vivek 24-2-2017  ,removed 	storezo and mailerbag from including no tax cond. told by gopi dii on 15-7-2017	$product_code_data['product'] == '18' || $product_code_data['product'] == '10' ||  $product_code_data['product'] == '35' //remove this told by nidhidii
								/*	if($product_code_data['product'] == '11')

									{

										$total_excies_rate = $final_total;

										$total_taxation = $final_total*$proforma['taxation_per']/100;
                                       
                                       $total_igst_rate = $final_total;
									}

									else 

								  	{	  */
										//printr($proforma);	
                        				if ($proforma['taxation'] != 'sez_no_tax') {
                            					if($proforma['taxation'] == 'With in Gujarat'){
                            					//	printr($proforma);
                            						$html .= '<tr>
                            
                            								<td></td>
                            
                            								<td><div align="right">
                            
                            								<strong>SGST ' . $proforma['sgst'] . '%</strong>';
                            						$html .='</div></td>
                            
                            								<td><b></b></td>
                            
                            								<td></td>
                            
                            								<td><p align="right">' . round(($final_total * $proforma['sgst'] / 100), 3) . '</p></td>
                            
                            								</tr>';
                            							
                            						 $html .= '<tr>
                            
                            										<td></td>
                            
                            										<td><div align="right">
                            
                            										<strong>CGST ' . $proforma['cgst'] . '%</strong>';
                            							 $html .= '</div></td>
                            
                            										<td><b></b></td>
                            
                            										<td></td>
                            
                            									<td><p align="right">' . round(($final_total * $proforma['cgst'] / 100), 3) . '</p></td>
                            
                            								</tr>';
                            						
                            						$total_cgst_rate = $final_total +($final_total * $proforma['cgst'] / 100);
                            						$total_sgst_rate = ($final_total * $proforma['sgst'] / 100);
                            					}
                            					else
                            					{
                            						$html .= '<tr>
                            
                            										<td></td>
                            
                            										<td><div align="right">
                            
                            										<strong>IGST ' . $proforma['igst'] . '%</strong>';
                            							 $html .= '</div></td>
                            
                            										<td><b></b></td>
                            
                            										<td></td>
                            
                            									<td><p align="right">' . round(($final_total * $proforma['igst'] / 100), 3) . '</p></td>
                            
                            								</tr>';
                            								$total_igst_rate = $final_total +($final_total * $proforma['igst'] / 100);
                            					}
                        				}
                        				else
                        				{
                        				    $total_igst_rate = $final_total;
                        				}
                            					
                                    }

							 if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==111)

							 {

								  $html .='<tr>

													<td></td>

													<td><div align="right"><strong>Total </strong></div></td>

													<td></td>';

													//$Total_price=($total_excies_rate+$total_taxation+$proforma['freight_charges']);
                                                      $Total_price = ($total_cgst_rate+ $total_sgst_rate +$total_igst_rate);

                                                    
													$html.='<td></td><td><p align="right">'.round($Total_price).'</p></td>

												</tr>';
                                    $amt1 = "UPDATE `" . DB_PREFIX . "proforma_product_code_wise` SET invoice_total = ".round($Total_price)." WHERE proforma_id=".$proforma_id;
                                    $this->query($amt1);
                                    $number = $this->convert_number(round($Total_price));
							 }

							$html .='</tbody>

							</table>

						</div>';

    
						//printr($final_total);
					//	printr($Total_price);

						if(isset($Total_price) && $Total_price!=0){ 

 							/*$number = $this->convert_number(round($Total_price));
                            
							if($user_name['country_id']=='14')
							{
								$number = $this->convert_number_cent($Total_price);
								
							}*/	
                            
		
 						} else{
                           
 	 						$number = $this->convert_number(round($final_total)); //printr($number);
                            $amt = "UPDATE `" . DB_PREFIX . "proforma_product_code_wise` SET invoice_total = ".round($final_total)." WHERE proforma_id=".$proforma_id;
                            $number = $this->convert_number(round($final_total));
							if($user_name['country_id']=='14')
							{
								//$number = $this->convert_number_cent($Total_price);
                                $amt = "UPDATE `" . DB_PREFIX . "proforma_product_code_wise` SET invoice_total = ".$Total_price." WHERE proforma_id=".$proforma_id;
                                $number = $this->convert_number($Total_price);
                              //  printr($Total_price);
							}
							$this->query($amt);
  						}
                         
		                    
  						
            $html .='</div>';
									

			$html .='<div  class="" style=" width: 100%;float: left;  border: 1px solid black;font-size: 16px;">

							<table cellspacing="0px" cellpadding="10px" border="1" style=" width: 100%; border-spacing: 0px;">

								<tbody>';
							if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']!=155)
							{
    					        $html .='<tr>
                
                        		            <td colspan="2" valign="top"><strong>Amount Chargeable(In Words): '.$number.' {'.$currency['currency_code'].'}</strong></td>
                        
                        	        </tr>';
							}
        	$html .='<tr>'; 	   
        	foreach($proforma_inv as $pro_inv)
        	{
        		$string1=$pro_inv['product_code'];					
        		 $remark= substr($string1,0,4);
        		
        	}
	        if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==42)
	
			{
				//printr($proforma_inv);
				
				
						//printr($proforma);
						if($remark=='CUST')
						{
				
						 $html .=' <td valign="top" width="50%"><div><strong>Remarks:</strong><br>All Orders are subject to an over-run or under-run,(Variable quantity 10% to 20%)applicable <br>taxes and shipping and handling. Customers will be invoiced fullquantity of bags that are<br>delivered.Pouch Makers may use customers printed pouches as samples.<br>';
						 $html.='<br>'.$user['note_invoice'].'<br>';
						}
						else
						{
							 $html.='<td valign="top" width="50%"><div><br>'.$user['note_invoice'].'</b> </div>';

						}
				
				}
				elseif(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==214)
				{
				    $html .=' <td valign="top" width="50%"><div>';
				}
			else{	
					 $html .=' <td valign="top" width="50%"><div><strong>Declaration:</strong><br>We declare that this Invoice shows the actual price of the <br>goods described and that all particular are true and <br>correct.<br>';
						
				}
            	if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==14)
    		    {
    		         $html.='<br><br><b>Note: </b> Every delivery would be entitled to "Authority to Leave". Pouch Direct will not accept responsibility for the safety of parcels if the products get lost or damaged once delivered by the transport company.';
    		    }
    		
	
    		if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==214)
    
    		{
    		    $html.= $user['note_invoice'];
    		  //  printr($user);
    	/*		$html.= '<strong>Payment</strong><br />
                        All payments will issue Invoices and payment has the be made within 3 days. If payment is not received or payment method is declined, the buyer forfeits the ownership of any items purchased. If no payment is received, no items will be shipped or collected. Once payment has made, you have agree that all Details, Description, Quantity, Colour and Amount are as correct unless other wise.<br />
                        <br />
                        <strong>Refund/Return Policy</strong><br />
                        Items are not entitled to be refunded or returned. If an item is unsatisfactory, a written explanation is needed before the item may be considered for a replacement. Buyer must take into account the description of the item before requesting a refund. If the item matches the description by the seller and the buyer is unsatisfied, seller is not responsible for refund. Exchanges are granted on a case-by-case basis.<br />
                        <br />
                        <strong>Custom Bags : Target delivery date refers to the estimated delivery date at the point of sale and is not a guaranteed delivery date. Here&#39;s a few policies, when ordering custom printed bags.</strong><br />
                        1) 50% Advanced Deposit . Balance of the 50% Payment before Collection.<br />
                        2 ) Aware of the quantity variation.<br />
                        3) No Exchanges or Returns are Allowed. No Changes are Allowed to an order once it is Finalize.<br />
                        4) All orders have to be Collected within 5 - 7 days upon arrival of goods.<br />
                        <br />
                        I have read and agree to the terms and conditions.<br />
                        <br />
                      <div style="color: red;">  Authorised Signatory</b><br />
                        <br />
                        ___________________</div>';*/ 
    		
    		
    			// $html.='<p valign="top" width="50%"><div><b style="color:red"><strong>NOTE : </strong>Please double check the color and size of product(s) before approval of proforma invoice; Hence company will not responsible for any change.</b></p>';
    		}//printr($proforma['destination']);
    		if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==235)
    		{
    		    $html.= $user['termsandconditions_invoice'];
    		}
            if(isset($set_user_id) && ($set_user_id=='19'))
    		{   
    		    /*if($remark=='CUST' && ucwords(decode($proforma['transportation']))=='Air')
    			    $html.= 'Terms : 50 % Payment advance and 50% once the goods are ready to ship Delivery : Pouches will be ready in 30 days after the confirmation of order Transit time will be approximately 22 days by Sea to Jebel Ali sea port';
    			else if($remark=='CUST' && ucwords(decode($proforma['transportation']))=='Sea')
    			    $html.= 'Terms : 50 % Payment advance and 50% once the goods are ready to ship Delivery : Pouches will be ready in 30 days after the confirmation of order Transit time will be approximately 22 days by Sea to Jebel Ali sea port';
    			else*/
    			    $html.= '<p<span style="color:#FF0000">'.$proforma['terms_and_cond'].'</span></p>';
    		    
    		}
                        
    		if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==111)
    
    		{
                //printr($set_user_id);
    		    if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id!='37' && $set_user_id!='38' && $set_user_id!='39'))
    		    {
    		        $html .='<strong>Delivery schedule:</strong><br> All stock pouches will be ready in 10-15 days after the total invoice amount is transferred..<br> If the goods are ready we will send it asap. <br>Some colors or sizes can even take few days more for production.<br><b style="color:red"> NOTE : OUR ALL CONSIGNMENTS WILL COME "TO PAY"...<br>Please double check the color and size of product(s) before approval of proforma invoice; Hence company will not responsible for any change.</b></b>';
    
    			//[kinjal] : added on [22-8-2016] onlu for custom printed products
    
        			if($custom_pro_id=='1')
        
        			{
        
        				$html.='<br><br><b style="color:red">NOTE : Please note that in commercial production there can be variances in total output.  For a production run of 10,000 units, there can sometimes be a maximum over/under production variance of 4500 pouches per 10,000 bags. In such an event we will always provide a refund for any difference where we have a shortfall of pouches. However, in an instance where we have over-production upto the variance levels mentioned above, we ask that you kindly commit to pay for the additional volume. Please note that because of the commercial nature of the print process, sometimes slight shift registrations can occur.</b>';
        
        			}
    		    }
    		    else
    		    {
    		   //   printr($proforma['destination']);
    		       if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='37' || $set_user_id=='38'))
    		       {
    		           $html .='<strong>Terms & Condition : </strong><br> Our responsibility cease as soon as goods leaves our premises</br> Subject to Vadodara Jurisdiction</br> E. & O. E.<br>';
    		       }
    		       else
    		       {
    		           $html .='<br><b style="color:red"> NOTE : <b>GOODS ONCE SOLD WILL NOT BE ACCEPTED BACK.</b><br>
    		                                                     FRIEGHT CHARGES WILL BE PAID BY THE CUSTOMERS.<br>
                                                                  Please double check the size of product(s) before<br>
                                                                approval of proforma invoice. Hence company will not<br>
                                                                    responsible for any change.</b>';    
    		     
    		       }
    		       $html.='<br><br><b style="color:red">NOTE : This is system generated invoice so does not required Signature .</b>'; 
    		    }
    		}
    
    		$html .='</div></td>
    
    		<td valign="top" class="sign_td">
    
    		<table border="0" align="right"  cellspacing="0px" cellpadding="0px" style="width: 100%;border-spacing: 0px;" >
    
    		<tr>
    
    			<td width="50%"><br>For <strong>';//printr($proforma['gen_pro_as']);
    			
    			if(isset($set_user_id) && !empty($set_user_id)&&  $set_user_id=='10' &&  $proforma['gen_pro_as']=='2')
    			    $html .='Swiss PAC PVT LTD';
    			else
    			    $html.=$sign;
    			    
    			 $html.='</strong><br>
    
    			<p style="text-align:right;margin-top:20px;margin-bottom:0;padding:0px;">';
    			/*if(isset($set_user_id) && !empty($set_user_id) && $set_user_id=='44')
    			    $html.='Pouch Makers Canada Inc.';
    			else*/
    			    $html.=$user_name['first_name'].' '.$user_name['last_name'];
    			    
    			   $html.='</p><hr/>
    
    				<p id="prefix" style="text-align:right;float:right;" >Authorised Signatory</p>
    
    			</td>
    
    		</tr> 
    
    	</table></td>
    
    	</tr></tbody></table></div>';
        
        /* if( $_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1')
            {
               // printr($proforma);
            }*/
        
        $Beneficiary_add = '<tr>

							<td><b>Bank Address </b></td>

							<td >'.$proforma['benefry_add'].'</td>

						</tr>';
	    $IFSC = '<tr>
		
						<td><b>IFSC Code</b></td>

						<td>'.$proforma['swift_cd_hsbc'].'</td>

					</tr>';

		$MICR='		<tr>

						<td><b>MICR Code</b></td>

						<td>'.$proforma['micr_code'].'</td>

				</tr>';	
	    //printr($proforma);
    	if(isset($set_user_id) && !empty($set_user_id) && $set_user_id!='39')
    	{
            $Beneficiary_bank_add = '<tr>				<td><b>Beneficiary Bank Address</b></td>
        
        												<td>'.$proforma['benefry_bank_add'].'</td>
        
        											</tr>';
    	}
    	$Intermediary_Bank_Name='<tr>
    									<td><b>Intermediary Bank Name</b></td>
    
    									<td>'.$proforma['intery_bank_name'].'</td>
    
    								</tr>';	
    	$Intermediary_Bank ='<tr>
    
    								<td><b>Intermediary Bank</b></td>
    
    								<td>'.$proforma['hsbc_accnt_intery_bank'].'</td>
    
    								</tr>';
    	$Swift_Code_of_Intermediary_Bank ='<tr>
    
    								<td><b>Swift Code of Intermediary Bank</b></td>
    
    								<td>'.$proforma['swift_cd_intery_bank'].'</td>
    
    								</tr>';
     						
        $Intermediary_Bank_ABA_Routing_Number ='<tr>
    
    							<td><b>Intermediary Bank ABA Routing Number</b></td>
    
    							<td>'.$proforma['intery_aba_rout_no'].'</td>
    
    						</tr>';
        $swift_code='';
        if($user_name['country_id']=='155'  || $user_name['country_id']=='11' )
        {
            
          
            if($currency['currency_code']=='MXN')
                $Beneficiary_add = $IFSC=$MICR=$Beneficiary_bank_add=$Intermediary_Bank_Name=$Intermediary_Bank=$Swift_Code_of_Intermediary_Bank=$Intermediary_Bank_ABA_Routing_Number='';
            
           
            else
            {
                
                
            if($user_name['country_id']=='11'){
                 $swift_code='<tr>
    
    							<td><b>Swift Code of Beneficiary Bank</b></td>
    
    							<td>'.$proforma['swift_code'].'</td>
    
    						</tr>';
                }
                else{
                    $Beneficiary_add = $IFSC=$MICR=$Beneficiary_bank_add=$Intermediary_Bank='';
                     $swift_code='<tr>
                
                							<td><b>Swift Code of Beneficiary Bank</b></td>
                
                							<td>'.$proforma['swift_code'].'</td>
                
                						</tr>';
                   
        					//	printr($swift_code);
              }
          }
            
        }
        if($proforma['destination']==251 || $proforma['destination']==209|| $proforma['destination']==235){
        
                $Beneficiary_add =$MICR=$Intermediary_Bank='';
                	$IFSC = '<tr>
    		
    						<td><b>IBAN Number/IFSC Code</b></td>
    
    						<td>'.$proforma['swift_cd_hsbc'].'</td>
    
    					</tr>';
                
        }
        $payment_mode='';
        if($proforma['customer_bank_detail']=='0' )
        {/*<tr>
                    							<td><b>Paypal Detail</b></td>
                    
                    							<td>paypal.me/PouchMakersCanadaINC</td> 
                    						</tr>*/
            $page_style='';
            if($user['country_id']=='42'){
                $page_style='page-break-after: always;';
                  $payment_mode='<div class="" style=" width: 100%;float: left;  border: 1px solid black;font-size: 16px;">
            
            							<table cellspacing="0px" cellpadding="10px" border="1" style=" width:100%;">
            
            								<tbody><tr>
            
            									<td valign="top" colspan="2"><h1 align="center">E-Transfer Details </h1></td>
            
            								</tr><tr>
                        							<td><b>E-Transfer Details</b></td>
                        
                        							<td>info@pouchmakers.ca</td>
                        						</tr
                                                
                    						</tbody>
                    						</table>
                    						</div>';
                 
        }
        if(isset($set_user_id) && ($set_user_id=='19'))
            $curr_code = '<h4><b><center>'.$currency['currency_code'].'</center></b></h4>';
        else
            $curr_code = '<b>'.$currency['currency_code'].'</b>';
            
            $html .='<div class="" style=" width: 100%;float: left;  border: 1px solid black;page-break-before: always;font-size: 16px;'.$page_style.'">';
                    	$html .=	$payment_mode;
            						$html.='	<table cellspacing="0px" cellpadding="10px" border="1" style=" width:100%;">
            
            								<tbody><tr>
            
            									<td valign="top" colspan="2"><h1 align="center">BANK DETAIL</h1></td>
            
            								</tr>
            
            								<tr>
            
            	 								<td colspan="2">'.$curr_code.'</td>
            
            								</tr>
            
            								<tr>
            
            									<td><b>Beneficiary Name</b></td>
            
            									<td >'.$proforma['bank_accnt'].'</td>
            
            								</tr>
                                            <tr>
            
            									<td><b>Bank Name</b></td>
            
            									<td>'.$proforma['benefry_bank_name'].'</td>
            
            								</tr>';
            							    if($user_name['country_id']!='11' && $proforma['benefry_add']!='')
            								{
            							        	$html.=$Beneficiary_add;
            
            								}
                                            if($user_name['country_id']=='111')
            
            								{
                                               	$html .='<tr>
                
                									<td><b>Name Of The Branch</b></td>
                
                									<td>'.$proforma['branch_nm'].'</td>
                
                								</tr>';
                								
                                               	$html .='<tr>
                
                									<td><b>Type Of Account</b></td>
                
                									<td>'.$proforma['type_of_accnt'].'</td>
                
                								</tr>';
                                            }
            								if($user_name['country_id']=='14'|| $user_name['country_id']=='42' )
             
            								{
            
            									$html .='<tr>
            
            												<td><b>BSB</b></td>
            
            												<td>'.$proforma['bsb'].'</td>
            
            											</tr>';
                                                    
            
            								}
            
            								$html .='<tr>
            
            											<td><b>Account Number</b></td>
            
            											<td>'.$proforma['accnt_no'].'</td>
            
            										</tr>';
            
            
            								//$html .='</tr>';
            								
            								//printr($IFSC);
            								if($user_name['country_id']!='42' && $user['country_id']!='11' && ($proforma['swift_cd_hsbc']!='' || $proforma['micr_code']!=''))
            
            								{
            
            										$html .=$IFSC;
            										$html .=$MICR;
            								}
            
            								if($user_name['country_id']!='14' && $user['country_id']!='11' )
            
            								{
            
            									$html .=$Beneficiary_bank_add;
            
            								} 
            
            								
                                    if($user_name['country_id']=='155')
                                    {
                						if($currency['currency_code']=='MXN' || $currency['currency_code']=='USD'){ 
                
                						$html .='<tr>
                
                									<td><b>Clabe</b></td>
                
                									<td>'.$proforma['clabe'].'</td>
                
                								</tr>';
                
                						}
                                    }
            						if($proforma['intery_bank_name']!=''){ 
            
            						    $html .=$Intermediary_Bank_Name;
            
            						}
            
            						if($proforma['hsbc_accnt_intery_bank']!=''){ 
            
            						    $html .=$Intermediary_Bank;
            
            						}
            
            						if($proforma['swift_cd_intery_bank']!=''){ 
            
            						    $html .=$Swift_Code_of_Intermediary_Bank;
            
            						}
            					//	printr($swift_code);
                                    $html.= $swift_code;
            						if($proforma['hsbc_accnt_intery_bank']!=''  && $user['country_id']!='11' ){ 
            
            						    $html .=$Intermediary_Bank_ABA_Routing_Number;
            
            						}
            						
            						
                                	if($user_name['country_id']=='14')
            								{
            // add by sonu 24-10-2019 told by vatsalbhai
        									$html .='<tr>
        
        												<td><b>Customer Reference Number</b></td>
        
        												<td>'.$proforma_id.'</td>
        
        											</tr>';
                                                
            
            								}
            
            					$html .='</tbody></table>';
            			
            					$html .='</div>';
            
        }
	    if(isset($user['country_id']) && ( $user['country_id'] == '14'   || $user['country_id'] == '155'  || $user['country_id'] == '214' ||  $user['country_id'] == '42'))
    	{
    		//printr($user);die;
    	        if( $user['country_id'] == '42' )
    			{
    				/*foreach($proforma_inv as $pro_inv)
    						{
    							$string1=$pro_inv['product_code'];					
    							$remark= substr($string1,0,4);												
    						}
    				if($remark=='CUST')
    						{*/					
    							$html .='<div style="border: 1px solid black;font-size: 13px;">'.$user['termsandconditions_invoice'].'</div>';
    						//}
    			}
    			else if ($user['country_id'] == '155')
    			{ //
    				if($remark!='CUST')
    				{//printr($remark);
    					$html .='<div style=" border: 1px solid black;font-size: 16px;"><p><u><strong>T&Eacute;RMINOS &amp; CONDICIONES DE VENTA</strong></u></p>
    
                                    <ul>
                                    	<li>Los siguientes t&eacute;rminos aplican para todos los pedidos realizados con nosotros. Al realizar su pedido, usted&nbsp; reconoce que ha le&iacute;do, comprendido y aceptado estar obligado por estos t&eacute;rminos:&nbsp;</li>
                                    	<li>Todos los precios son en pesos mexicanos ';if($proforma['destination']=='155') $html.='(MXN, M.N.)</li>'; else  $html.='(USD)</li>';
                                    	$html.=$user['termsandconditions_invoice'].'</div>';
    				}
    		    }
    		    /*else if ($user['country_id'] == '214')
    			{
            			$html .='<div style=" border: 1px solid black;page-break-before: always;font-size: 14px;"><strong>Terms &amp; Conditions</strong><br />
            					All payments have to be made within 3 days. If payment is not received or payment method is declined, the buyer forfeits the ownership of any items purchased. If no payment is received, no items will be shipped or collected. Once payment has made, you have agree that all details are true.<br />
            					<br />
            					<strong>Refund/Return Policy</strong><br />
            					Items are not entitled to be refunded or returned. If an item is unsatisfactory, a written explanation is needed before the item may be considered for a replacement. If the item matches the description by the seller and the buyer is unsatisfied, seller is not responsible for refund/return.<br />
            					<br />
            					<strong>Custom Pouches :</strong><br />
            					<br />
            					1) 50% Advanced Deposit . Balance of 50% Payment before/upon Collection.<br />
            					2) Quantity Variation Applies.<br />
            					3) Once Finalize, No Exchanges or Returns are Allowed<br />
            					4) All orders have to be Collected within 10 days<br />
            					<br />
            					<br />
            					<strong>Custom Plastic Cups / Containers :</strong><br />
            					1) 100% Advance for Purchase below $500<br />
            					2) 50% Advanced Deposit for Purchase above $500. Balance of 50% Payment before/upon Collection.<br />
            					3) Once Finalize, No Exchanges or Returns are Allowed<br />
            					4) All orders have to be Collected within 10 days<br />
            					I have read and agree to the terms and conditions.<br />
            					<br />
            					<span style="color:#FF0000">Authorised Signatory</span><br />
            					<br />
            					  ___________________ 
            					<br><b>Sign here</b></div>';
            		}*/
    			else
    			{
    				$html .='<div style=" border: 1px solid black;page-break-before: always;font-size: 16px;">'.
    				
    				$user['termsandconditions_invoice'].'</div>';
    			}
    					
    
    	}
				//$html .="</div>";



		return $html ;

	}

		
		/*function oldviewProformaInvoice($proforma_id) {

			$html ='';

			$proforma=$this->getProformaData($proforma_id);

			$proforma_id=$proforma['proforma_id'];

			$proforma_inv=$this->getProformaInvoice($proforma_id);

			if($proforma['destination']==111)

				$title='Consignor';

			else

				$title='Exporter';

			$html .='<div class="panel-body font_medium">

			<table style="width:100%;cellpadding:0px;cellspacing:0px;" border="1">

			<tr>

				<th colspan="2" scope="col" style="font-size:20px;  text-align: center;">PROFORMA INVOICE</th>

			</tr>

			<tr>

				<td  valign="top" id="client">

					<b>'.$title.'<br>SWISS PAC PVT LTD</b><br>Padra Jambusar National highway<br />

					At Dabhasa village,Pin 391440<br />Taluka.Padra, Dist.Vadodara(State Gujarat) India 

				</td>';

			$html .='<td>

			<table  border="0" cellspacing="0px" cellpadding="10px" style=" border-spacing: 0px; width:100%;">

			<tr>

				<td valign="top"><b>Invoice No.&amp; Date</b></td>

				<td>'.$proforma['pro_in_no'].' &nbsp;/&nbsp; '.dateFormat(4,$proforma['invoice_date']).'<br><br><br><span class="barcode" >'.$proforma['pro_in_no'].'</span><br><br></td>

			</tr>

			<tr>

				<td><b>Proforma :</b></td>

				<td>&nbsp;'.dateFormat(4,$proforma['proforma']).'</td>

			</tr>

			<tr>

				<td ><b>Buyers Order No. &amp; Date:</b></td>

				<td>'.$proforma['buyers_order_no'].' &nbsp;/&nbsp; '.dateFormat(4,$proforma['buyers_date']).'</td>

			</tr>

			<tr>

				<td ><b>Country of origin of goods:</b></td>

				<td>'.$proforma['goods_country'].'</td>

			</tr>

			</table>

			</td>

			</tr>';

  		  $html .='<tr>

			<td rowspan="2" valign="top"><p><b>Consignee</b></p>

			<p>'.$proforma['customer_name'].'<br/>'.nl2br($proforma['address_info']).'<br/>Email : '.$proforma['email'].'</p>

			</td>

			<td >

			<table  border="0"  cellspacing="0px" cellpadding="10px" style=" border-spacing: 0px; width: 100%">

		<tr>

			<td colspan="2" style="text-align:center"> <b>Terms of Delivery &amp; Payment</b></td>

		</tr>

		<tr>

			<td ><b>Delivery:</b></td>

			<td>'.$proforma['delivery_info'].'</td>

		</tr>';

		
			
			
			$html.='<tr>
			<td><b>Mode Of Shipment:</b></td>
			<td> By '.ucwords(decode($proforma['transportation'])).'</td>
			
			
			
			</tr>';
		
		
		

		$html.='<tr>

			<td><b>Payment Terms:</b></td>

			<td>'.$proforma['payment_terms'].'</td>

		</tr>

		</table>

		</td>

		</tr>';

 	  $html .='<tr>

		<td ><table border="0" style=" border-spacing: 0px; width:100%;">

	 <tr>

		<td><b>Port Of Loading:</b></td>

		<td><b>Final Destination:</b></td>

	 </tr>

	 <tr>';

	$con_id =$proforma['destination'];

	$countrys = $this->getCountry($con_id);



	$html .='<td>'.$proforma['port_loading'].'</td>

		<td>'.$countrys['country_name'].'</td>

	</tr>

	</table></td>

	</tr>';

  

  $currency = $this->getCurrencyId($proforma['currency_id']);

  $html .='<tr>

		<td colspan="2" valign="top" style="border-color: white;"><table  border="1"  cellspacing="0px" cellpadding="10px" style=" border-spacing: 0px;">

	<tr>

		<td width="5%"><div align="center"><b>Sr. No</b></div></td>

		<td width="60%" ><div align="center"><b>Discription of Goods</b></div></td>

		<td width="10%"><b>Quantity In Units</b></td>

		<td width="15%"><b>Rate &nbsp;'.$currency['currency_code'].'</b></td>

		<td width="10%"><b>Amount &nbsp;'.$currency['currency_code'].'</b></td>

	</tr>';

	$total = 0;$total_rate=0; $final_total=0; $n=1;$total_excies_rate=0;$ex_per=array();$total_taxation_rate=0;$tax_per=array();$taxa=array();

	foreach($proforma_inv as $invoice_key=>$invoice){

	

		$getProductSpout = $this->getSpout(decode($invoice['spout']));

		$getProductZipper = $this->getZipper(decode($invoice['zipper']));

		$zipper_name='';

		$valve_name='';

		if($invoice['valve']=='With Valve')

			$valve_name=$invoice['valve'];

		if($getProductZipper['zipper_name']=='With zip')

			$zipper_name=$getProductZipper['zipper_name'];

		$getProductAccessorie = $this->getAccessorie(decode($invoice['accessorie']));

		if($invoice['product_id'] == 3 || $invoice['product_id'] == 8)

		{

			$gusset = floatval($invoice['gusset']).'+'.floatval($invoice['gusset']);

		}

		else

		{

			$gusset = floatval($invoice['gusset']);

		}

		$quantity = $this->getColorDetails($proforma['proforma_id'],$invoice['proforma_invoice_id']);

										

  		$html .='<tr>

			<td >'.$n.'</td>

			<td ><b>Size : </b>'.floatval($invoice['width']).' mm &nbsp;Width &nbsp;X&nbsp;'.floatval($invoice['height']).' mm &nbsp;Height &nbsp;';

				if($gusset>0)

					$html .='X&nbsp;'.$gusset.' mm';

			

				if($invoice['volume']>0)

					$html .=' ('.$invoice['volume'].')';

					$html .='<br><b>Make up of pouch :</b> '.$invoice['product_name'].'&nbsp<b>'.$zipper_name.'&nbsp'.$valve_name.'</b><br>';

				foreach($quantity as $quantity_val) {

					//$colorName = $this->getColorName($quantity_val['color']);

					$clr_text='';

					if($quantity_val['color']=='-1')

					{

						//$quantity_val['color_name']='Custom';

						if($quantity_val['color_text']!='')

						$clr_text = "(".$quantity_val['color_text'] .")";

					}


					$html .='<b>Color : '.$quantity_val['color_name'].' '.$clr_text.'<br>';

					if($quantity_val['description']!='')

					{					

					$html .='Material Description : </b>'.$quantity_val['description'].'<br>';

					}

				}

				$html .='</td>

			<td><br><br>';

		foreach($quantity as $quantity_val) {

			$total = $total+$quantity_val['quantity'] ;$total_qty = $quantity_val['quantity'];

			$html .=$total_qty.'<br>';

		}

	$html .='</td>

		<td><br><br>';

		

	foreach($quantity as $rate_val) { 

		$total_rate=$total_rate+$rate_val['rate'];$total_rt = $rate_val['rate'];

		$html .=$total_rt.'<br>';

		

	}

	$html .='</td>

		<td><br><br>';

	foreach($quantity as $rate_val) {

	

		$total_amnt = $rate_val['quantity'] * $rate_val['rate'];

		$html.= $total_amnt.'<br>'; 

		$final_total=$final_total+$total_amnt;

	}

	$html .='</td>

	</tr>';

	  

	  $n++;}//}

      $html .='<tr>

        <td></td>

        <td></td>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

        <td>&nbsp;</td>

      </tr>';

	  $html .='<tr>

        <td></td>

        <td></td>

        <td><p align="center"><b>'.$total.'</b></p></td>

        <td><p align="center">Total('.$currency['currency_code'].')</p></td>

        <td><p align="center"><b>'.$final_total.'</b></div></td>

      </tr>';

   	  if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==111)

	  {

		 $total_excies_rate = $final_total+($final_total*$proforma['excies_per']/100);

		 $total_taxation = $total_excies_rate*$proforma['taxation_per']/100;

		  

	  $html .='

	   <tr>

    	<td></td>

        <td><div align="right">

				<strong>Excies '.$proforma['excies_per'].' %</strong>';

				if($proforma['excies_per'] ==0)

		{

			$html .='<br><span>( '.str_replace("H Form,","",$proforma['tax_form_name']).' is given )</span>';

		}

				$html .='</div></td>

   			 <td><b></b></td>

        <td></td>

        <td><p align="center">';

					$html .= round(($final_total*$proforma['excies_per']/100),3).'</p></td>

  </tr>';

	 

  $html .='

  <tr>

    <td></td>

        <td><div align="right">

				<strong>Tax  '.$proforma['taxation_per'].' %</strong>';

				if($proforma['taxation_per'] ==0)

					$html .='<br><span>( H Form is given)</span>';

					else

					$html .='<br><span>( '.str_replace('_', ' ',$proforma['taxation']).' )</span>';	

				$html .='

				</div></td>

   			 <td><b></b></td>

        <td></td>

        <td><p align="center">';

					$html .=round($total_taxation).'</p></td>

  </tr>';

   $html .='

  <tr>

    <td></td>

        <td><div align="right">

				<strong>Freight Charges </strong>

				</div></td>

   			 <td><b></b></td>

        <td></td>

        <td><p align="center">';

					$html .=round($proforma['freight_charges'],3).'</p></td>

  </tr>';

	 

  $html .='

  <tr>

					<td></td>

					<td><div align="right"><strong>Total</strong></div></td>

					<td></td>

					<td></td>';

					$Total_price=($total_excies_rate+$total_taxation+$proforma['freight_charges']);

					$html .='<td><p align="center">'.round($Total_price).'</p></td>

				</tr>';

	}

				$html .='

    </table></td>

  </tr>';

 if(isset($Total_price) && $Total_price!=0){  

 	$number = $this->convert_number(round($Total_price));

 } else{

 	 $number = $this->convert_number(round($final_total));

  }

  $user_name=$this->getUser($proforma['added_by_user_id'], $proforma['added_by_user_type_id']);

 //printr($user_name);

  //die;

 $html .='<tr>

		<td colspan="2" valign="top"><strong>Amount Chargeable(In Words) : '.$number.'{'.$currency['currency_code'].'}</strong></td>

	</tr>';


	$htim .='<td valign="top" class="sign_td">

		<table border="0" align="right"  cellspacing="0px" cellpadding="10px" style=" border-spacing: 0px;" >

		<tr>

			<td width="50%"><p align="left">Signature &amp; Date:<br>For <strong>Swiss PAC PVT LTD</strong><br>

			<p style="text-align:right;margin-top:70px;margin-bottom:0;">'.$user_name['first_name'].' '.$user_name['last_name'].'</p><hr/>

				<p id="prefix" style="text-align:right;float:right;" >Authorised Signature</p><br />

			</td>

		</tr> 

	</table></td>

	</tr></tbody></table></div>';

  $html .='<div class="panel-body font_medium" ><table border="1" cellspacing="0px" cellpadding="10px" style=" width:100%;">

		<tr>

			<td colspan="2" valign="top"><h1 align="center">BANK DETAIL</h1></td>

		</tr>

		<tr>

			<td colspan="2"><b>'.$currency['currency_code'].'</b></td>

		</tr>

		<tr>

			<td><b>Beneficiary Name</b></td>

			<td >'.$proforma['bank_accnt'].'</td>

		</tr>

		<tr>

			<td><b>Beneficiary Address</b></td>

			<td>'.$proforma['benefry_add'].'</td>

		</tr>

		<tr>

			<td><b>Beneficiary Bank Name</b></td>

			<td>'.$proforma['benefry_bank_name'].'</td>

		</tr>

		<tr>

			<td><b>Account Number</b></td>

			<td>'.$proforma['accnt_no'].'</td>

		</tr>

		<tr>

			<td><b>IFSC Code</b></td>

			<td>'.$proforma['swift_cd_hsbc'].'</td>

		</tr>

		<tr>

			<td><b>MICR Code</b></td>

			<td>'.$proforma['micr_code'].'</td>

		</tr>

		<tr>

			<td><b>Beneficiary Bank Address</b></td>

			<td>'.$proforma['benefry_bank_add'].'</td>

		</tr>';

			if($proforma['intery_bank_name']!=''){ 

		$html .='<tr>

			<td><b>Intermediary Bank Name</b></td>

			<td>'.$proforma['intery_bank_name'].'</td>

		</tr>';

			}

		if($proforma['hsbc_accnt_intery_bank']!=''){ 

		$html .='<tr>

			<td><b>Intermediary Bank</b></td>

			<td>'.$proforma['hsbc_accnt_intery_bank'].'</td>

		</tr>';

		}

		if($proforma['swift_cd_intery_bank']!=''){ 

		$html .='<tr>

			<td><b>Swift Code of Intermediary Bank</b></td>

			<td>'.$proforma['swift_cd_intery_bank'].'</td>

		</tr>';

		}

		if($proforma['hsbc_accnt_intery_bank']!=''){ 

		$html .='<tr>

			<td><b>Intermediary Bank ABA Routing Number</b></td>

			<td>'.$proforma['intery_aba_rout_no'].'</td>

		</tr>';

		}

	$html .='</table></div>';

		return $html ;

	}*/




	public function getUserCurrencyInfo($user_type_id,$user_id){

            
             if( $_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1')
            {
                	$data = $this->query("SELECT *  FROM " . DB_PREFIX . "currency  WHERE is_delete = '0' ");
            }
            else
            {
    			if($_SESSION['LOGIN_USER_TYPE'] == 2){
    
    				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
    
    				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
    
    				$set_user_id = $parentdata->row['user_id'];
    
    				$set_user_type_id = $parentdata->row['user_type_id'];
    
    			}else{
    
    				$userEmployee = $this->getUserEmployeeIds($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
    
    				$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
    
    				$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
    
    			}
    			
    			//	$data=$this->query("SELECT c1.* FROM `country` as c,currency_setting as cur,currency as c1 WHERE c.country_id=cur.country_code AND cur.user_id='".$set_user_id."' AND cur.user_type_id='4' AND c1.currency_code=c.currency_code");
    		   
    		   
    		   $data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."'");
    		    //	$data1=$this->query("SELECT c1.* FROM `country` as c,currency as c1 WHERE c.country_id=cur.country_code AND cur.user_id='19' AND cur.user_type_id='4' AND c1.currency_code=c.currency_codeSELECT c1.* FROM `country` as c,currency as c1 ,international_branch as cur WHERE c.country_id=cur.default_curr AND cur.international_branch_id='19'  AND c1.currency_code=c.currency_code");
    	            
    	           // $sql1="SELECT c1.* FROM `country` as c,currency as c1 ,international_branch as cur WHERE c.country_id=cur.default_curr AND cur.international_branch_id='19'  AND c1.currency_code=c.currency_code";
                    //	$data_cur=$this->query($sql1);
                    	
                    //	printr($data_cur);
            }
    
            
            
/*
            $currency=array('user_currency'=>$data->rows,
                            'currency'=>$data_cur->row
                );
            */
			if($data->num_rows){
			    
			    return $data->rows;
               /*     foreach($data->rows as $row){
                         $currency[]=array('currency_id'=>$row['currency_id'],
                                'currency_code'=>$row['currency_code']
                             );
                        
                    }*/
		    	}else{
		    	    return false;
		    	}	
		  /*  if($data_cur->num_rows){
                
                         $currency[]=array('currency_id'=>$data_cur->row['currency_id'],
                                'currency_code'=>$data_cur->row['currency_code']
                             );
                        
                    
		    	}*/
				

		 return $currency;

		}

	public function approveordis($data)

	{

		$sql="INSERT INTO `" . DB_PREFIX . "proforma_history_product_code_wise` SET proforma_id='".$data['proforma_id']."',appr_disapp_status='".$data['val']."',description='".$data['description']."',app_dis_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',app_dis_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',app_dis_date='".$data['app_dis_date']."'";

		//echo $sql;

		$data=$this->query($sql);

		return $data; 

	

	}

	public function getappdisdata($proformaid,$limit)

	{

		$sql = "SELECT * FROM proforma_history_product_code_wise WHERE proforma_id='".$proformaid."' ORDER BY proforma_his_id DESC ".$limit;

		$data=$this->query($sql);

		if($data->num_rows)

			return $data->rows;

		else

			return false;

	

	}

	public function getCompanyAdd($user_type_id,$user_id)

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

		$sql="SELECT ib.company_address,ib.company_name,ib.vat_no,ib.termsandconditions_invoice,note_invoice,a.country_id FROM international_branch as ib,address as a WHERE ib.international_branch_id='".$set_user_id."' AND ib.is_delete='0' AND a.address_id=ib.address_id";

		$data=$this->query($sql);

		if($data->num_rows)

			return $data->row;

		else

			return false;

		

	}

	public function getProductCd($product_code)

	{

		$result=$this->query("SELECT pc.product_code, pc.product_code_id, pc.description, clr.color, pc.volume, pc.measurement,p.product_name,pc.product,pc.zipper FROM " . DB_PREFIX ."product_code as pc, pouch_color as clr, product as p WHERE pc.product_code LIKE '%".$product_code."%' AND pc.is_delete=0 AND pc.color=clr.pouch_color_id AND p.product_id = pc.product AND pc.status=1");

		//printr($result);

		return $result->rows;
		//[kinjal] : added on 11/7/2017
	

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

	public function getProductCode($product_code_id)

	{

		$sql = "SELECT pc.*,p.product_name,pm.make_name,c.color,tm.measurement,pz.zipper_name,ps.spout_name,pa.product_accessorie_name,pc.width,pc.Height,pc.gusset,p.product_name_spanish,pm.make_name_spanish,c.color_spanish,pz.zipper_name_spanish,ps.spout_name_spanish,pa.product_accessorie_name_spanish FROM `" . DB_PREFIX . "product_code` AS pc LEFT JOIN product AS p ON pc.product=p.product_id LEFT JOIN product_make pm ON pc.make_pouch=pm.make_id LEFT JOIN pouch_color AS c ON c.pouch_color_id=pc.color LEFT JOIN template_measurement AS tm ON pc.measurement=tm.product_id  LEFT JOIN product_zipper AS pz ON pc.zipper=pz.product_zipper_id LEFT JOIN product_spout AS ps ON ps.product_spout_id=pc.spout LEFT JOIN product_accessorie AS pa ON pa.product_accessorie_id=pc.accessorie WHERE pc.product_code_id='".$product_code_id."'";
        /*if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')
            printr($sql);*/
		$data = $this->query($sql);

		if($data->num_rows){

			return $data->row;

		}else{

			return false;

		}

	}

	//[kinjal] created (8-2-2016)

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

	public function getSizeDetail($product_id,$zipper_id,$volume,$mea)

	{

        if($mea=="gm with Euro hole")
	    	$size_volume = $volume.''.$mea;
	    else
	        $size_volume = $volume.' '.$mea;

		$sql = "SELECT * FROM `" . DB_PREFIX . "size_master` WHERE product_id = '".$product_id."' AND product_zipper_id=".$zipper_id." AND volume='".$size_volume."'";
        //echo $sql;
		$data = $this->query($sql);

		if($data->num_rows){

			return $data->row;

		}else{

			return false;

		}

	}

	public function UpdateTotalInvoicePrice($proforma_id,$n=0)
    {

		$total_igst_rate = 0;
		$total_sgst_rate = 0;
		$total_cgst_rate = 0;
		$pro = $this->getProforma($proforma_id);
		$getInvoices = $this->getProformaInvoice($proforma_id);		
		$sub_total = 0;
		$j=$k=$l ='';
		$tool_price=0;
		  
		  
		  if($pro['added_by_user_type_id'] == 2){

				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$pro['added_by_user_id']."' ");

				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);

				$set_user_id = $parentdata->row['user_id'];

				$set_user_type_id = $parentdata->row['user_type_id'];

			}else{

				$userEmployee = $this->getUserEmployeeIds($pro['added_by_user_type_id'],$pro['added_by_user_id']);

				$set_user_id = $pro['added_by_user_id'];

				$set_user_type_id = $pro['added_by_user_type_id'];

			}
		  
		  foreach($getInvoices as $invoice )
           {
			 	if($invoice['product'] == '18')
				 	$j = 18 ;
				if($invoice['product'] == '10')
					$k = 10 ;
				if($invoice['product'] == '11')
					$l = 11 ;
				
				$tool_price=$tool_price + $invoice['tool_price'];
				if($set_user_id==10)
				{
					$cust = strtoupper ($invoice['product_code']);
					$code_cust   = strtoupper ("CUST");
					if( strpos( $cust, $code_cust ) !== false )
					{
						if($invoice['express_rate']!=0)
						{
							$qty1 =  ($invoice['quantity'] * $invoice['express_rate']);
						}
						else
						    $qty1 = ($invoice['quantity'] * $invoice['rate']);
						    
						$sub_total = $sub_total + $qty1 ;	
					}
					else
						$sub_total = $sub_total + ($invoice['quantity'] * $invoice['rate']) ;
				}
				else
					$sub_total = $sub_total + ($invoice['quantity'] * $invoice['rate']) ;
		    }
		$sub_total = $sub_total +$tool_price;
		
		if($pro['freight_charges'] != '0')
        {
		 	$sub_total = $sub_total + $pro['freight_charges'];
		}	

		$gst =0;
        /*if($pro['destination']!=111 )
        {*/
		 	if($pro['discount'] != '0')
			{
				$dis_total=($sub_total*$pro['discount'])/100;
    			$sub_total=$sub_total-$dis_total;
		 	}
		    if($pro['destination']!=111 && $pro['destination']!=42)
			{
				$gst = (($sub_total*$pro['gst_tax'])/100);
			}
		//}	
	
		$tax_price = 0;
        if($pro['destination']==42)
        {   if(($pro['gst']!='0.000' || $pro['pst']!='0.000') && $pro['hst']=='0.000')
            {
    			$tax_gst = $sub_total * ($pro['gst'] / 100);  
    			$tax_pst_price = $sub_total * ($pro['pst'] / 100);  
    			$tax_price=$tax_gst+$tax_pst_price;
            }
            else if($pro['hst']!='0.000' && ($pro['gst']=='0.000' && $pro['pst']=='0.000'))
            {	
    			$tax_hst = $sub_total * ($pro['hst'] / 100); 
    			$tax_price = $tax_hst;
            }
        	else if($pro['hst']=='0.000' && $pro['gst']=='0.000' && $pro['pst']=='0.000')
            {
    			$tax_gst = $sub_total * ($pro['gst'] / 100); 
    			$tax_pst_price = $sub_total * ($pro['pst'] / 100); 
    			$tax_price=$tax_gst+$tax_pst_price;
        	}
            
        }
		if(isset($pro['destination']) && !empty($pro['destination']) && $pro['destination']==111)
        {
				if($pro['packing_charges']!=0)
				{
						$packing_charges=round($pro['packing_charges'],3);
							if(isset($pro['destination']) && !empty($pro['destination']) && $pro['destination']!=111)
							{
								$sub_total=$sub_total;
							}else{
								$sub_total=$sub_total+$packing_charges;
							}
				}
				if($pro['taxation'] == 'With in Gujarat'){
					$total_cgst_rate = $sub_total+($sub_total * $pro['cgst'] / 100);
					$total_sgst_rate = ($sub_total * $pro['sgst'] / 100);
				}
				else
				{
					$total_igst_rate = $sub_total +($sub_total * $pro['igst'] / 100);
				}
				if($pro['taxation']=='sez_no_tax')
				{
					$total_igst_rate = $sub_total;
				}
				
		}
	    if($pro['destination']==111)
		{
			$freight_charges = 0;
	        $Total_price = ($total_cgst_rate+ $total_sgst_rate +$total_igst_rate );
			$Total_price = round($Total_price);
		}else{
			$Total_price = $gst + $sub_total + $tax_price;
		}
		$sql = "UPDATE `" . DB_PREFIX . "proforma_product_code_wise` SET invoice_total = ".$Total_price." WHERE proforma_id=".$proforma_id;
		$this->query($sql);
        if($n==1)
            return $sub_total;
        else
            return $Total_price;
    }

//[kinjal] : (1-6-2016) maked fun 4 match proforma qty to sales stock qty

	public function getUserEmployeeIdsStock($user_type_id,$user_id)

	{

		$sql = "SELECT GROUP_CONCAT(employee_id) as ids,GROUP_CONCAT(2) as type_ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."'";

		$data = $this->query($sql);

		if($data->num_rows){

			return $data->row;

		}else{

			return false;

		}

	}


    public function getStockQty($product_code_id,$user_type_id,$user_id,$proforma_no)

	{
		//echo $product_code_id;
		if($user_type_id == '1' && $user_id == '1')

		{

			$return = array('remaining_qty' =>'5000',

							'rem_qty_display'=> '5000' );

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
				$str = ' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 )';
			}
			
			$admin_user_id=' AND pto.admin_user_id = '.$set_user_id;
			$sql = "SELECT * FROM `" . DB_PREFIX . "goods_master` WHERE is_delete = '0' AND (user_id='".(int)$set_user_id."' AND user_type_id='".(int)$set_user_type_id."' $str )";
			$data = $this->query($sql);
			$f_val=array();
			
				
			if($data->num_rows)
			{
				//$final_val=array();
				
				foreach($data->rows as $val)
				{
					//$final_val[]=$val['goods_master_id'];
					
					$sql2 = "SELECT sm.user_id,sm.user_type_id,sum(sm.qty)qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id) grouped_s_id,p.product_name ,gm.name,sm.row,sm.column_name,pc.product_code,sm.stock_id,gm.row as g_row,gm.column_name as g_col FROM stock_management as sm,product as p,product_code as pc,goods_master AS gm WHERE sm.is_delete=0 AND p.product_id=sm.product AND sm.goods_id = gm.goods_master_id AND  sm.goods_id='".$val['goods_master_id']."' AND pc.product_code_id = sm.product_code_id  AND sm.product_code_id='".(int)$product_code_id."' AND parent_id=0  AND gm.is_delete = '0'AND sm.qty!=0 AND sm.row!=0 AND sm.column_name!=0 GROUP BY sm.row,sm.column_name ";
					
					
					
					$data2 = $this->query($sql2);					
					foreach($data2->rows as $data_arr)
					{
				//	 echo $sql2;
					//	printr($data_arr);
						$f_val[]=$data_arr;
					
					
					}
				
				}    
				
			}
			$dis_qty_la = $r_qty = 0;
			foreach($f_val as $val)
			{
				$sql_dis= "SELECT sum(dispatch_qty) as total FROM stock_management WHERE is_delete=0 AND parent_id IN (" .$val['grouped_s_id']. ")" ;
				$data2_dis = $this->query($sql_dis);				
				$dis_qty_la +=$data2_dis->row['total'];
				$r_qty +=$val['qty'];
			}
			
			
			$sql4 = "SELECT tp.qty,t.transfer_invoice_id FROM transfer_invoice as t,transfer_invoice_product as tp WHERE t.transfer_invoice_id = tp.invoice_id AND t.proforma_no='".$proforma_no."' AND tp.product_code_id='".$product_code_id."' AND t.dis_or_warehouse='1'";
            //echo $sql4;
			$res4 = $this->query($sql4);
			
			if($res4->num_rows > 0)

			{

				$tran_qty=$res4->row['qty'];

				//$remaining_qty=$remaining_qty+$tran_qty;

			}

			else
				$tran_qty='0';
			
		//    printr($r_qty.'-'.$dis_qty_la.'+'.$tran_qty.'+'.$proforma_no);	
			$return = array('remaining_qty' =>$r_qty-$dis_qty_la+$tran_qty,);
			 
		}	
	
		return $return;
	}
	public function getInvoiceProductDetails($proforma_no){
	    $sql="SELECT p.*,t.measurement,pro.* FROM product_code as p,template_measurement as t,proforma_invoice_product_code_wise as pro  WHERE p.is_delete = '0' AND pro.customer_dispatch_p=0 AND p.product_code_id=pro.product_code_id AND  p.measurement=t.product_id AND pro.proforma_id='".$proforma_no."' AND ( product_code NOT LIKE 'CUST%' AND product_code NOT LIKE 'LBL%' AND product_code NOT LIKE 'CPBB' ) ORDER BY  p.product_code_id<>( product_code LIKE 'CUST%' OR product_code LIKE 'LBL%' OR product_code LIKE 'CPBB' ) ,( product_code NOT LIKE 'CUST%' AND product_code NOT LIKE 'LBL%' AND product_code NOT LIKE 'CPBB' ),product_code DESC";
  // echo $sql.'<br>';
    	$data2 = $this->query($sql); 
    	if($data2->num_rows){

			return $data2->rows;

		}else{

			return false;

		}

	    
	}
	public function checkSalesQty($proforma_id,$user_type_id,$user_id,$proforma_no,$admin_user_id=0)

	{
   // printr($admin_user_id);
		if($admin_user_id == '10')
        {
		    $proforma_inv = $this->getInvoicepacking($proforma_no);
        }
        else if($admin_user_id == '19')
        {
		    $proforma_inv = $this->getInvoiceProductDetails($proforma_id);
        }
        else
        {
            $proforma_inv = $this->getInvoice($proforma_id);
        }
		 
        
		$product_code=array(); 

		if(!empty($proforma_inv)){

			

			foreach($proforma_inv as $pro_inv)

			{
                /*if($admin_user_id == '6')
				    $sale_detail = $this->getStockQty($pro_inv['product_code_id'],$user_type_id,$user_id,$proforma_no);
				else*/
				    $sale_detail = $this->getStockQty($pro_inv['product_code_id'],$user_type_id,$user_id,$proforma_no);
				    
                /*if($proforma_id=='25222'){
        		   printr($sale_detail);printr($pro_inv['product_code_id']);
        		 }*/
			//	printr($sale_detail);

				//&& $sale_detail['rem_qty_display']!='0' && $sale_detail['rem_qty_display']>0 put this condition in below if once inventory remaining became regular

				if($pro_inv['quantity'] <= $sale_detail['remaining_qty'])

				{

					
                    /*if($proforma_id=='25222')   
    					echo "true";*/

				}

				else

				{
                    /*if($proforma_id=='25222')   
    					echo "hjhjh";*/
					$product_code[]=$pro_inv['product_code_id'];

				}

				

			}
          //  if($proforma_id=='36888')
			  //  printr($product_code);

		}

		return $product_code;

	}

//[kinjal end]

	public function getLastIdSales($str='') {

		if($str!='')
			$sql = "SELECT invoice_id,invoice_no  FROM sales_invoice WHERE $str AND YEAR(date_added) = YEAR(NOW()) ORDER BY invoice_id DESC LIMIT 1";
		else
			$sql = "SELECT invoice_id FROM sales_invoice ORDER BY invoice_id DESC LIMIT 1";
    
       //printr($sql);
		$data = $this->query($sql);

		if($data->num_rows){

			return $data->row;

		}

		else {

			return false;

		}

	}

	public function gen_sales_invoice($proforma_id)

	{
			
		$proforma_detail = $this->getProforma($proforma_id);

		$proforma_product_detail = $this->getProformaInvoice($proforma_id);
	    //printr($proforma_product_detail);
       // printr($proforma_product_detail);die;

	//add sonu for payment 18-7-2017
    
    	 $payment_detail = $this->Payment_detail($proforma_id);
    //end
		

		$tax_maxico = $proforma_detail['gst_tax'];

		$buyers_orderno = '';

		if($proforma_detail['destination'] != '155')

		{

			$buyers_orderno = $proforma_detail['buyers_order_no'];

		}

		$userCountry = $this->getUserCountry($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
   
		if($userCountry){

			$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';					
          //  echo 'hoo';
           // echo $userCountry['country_code'];
            
		}else{

			$countryCode='IN';
		//	echo'noo';

		}
		
	    if($_SESSION['LOGIN_USER_TYPE'] == 2){
           $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");

			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id'],'');
            
			
			$set_user_id = $parentdata->row['user_id'];

			$set_user_type_id = $parentdata->row['user_type_id'];
            
		}else{

			$userEmployee = $this->getUserEmployeeIds($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);

			$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

			$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
            
		}
        $addedByInfo = $this->getUser($set_user_id,$set_user_type_id);


		$str1 = '';

		if($userEmployee){

			$str1 = ' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 )';

		}
		$str ="(user_id='".(int)$set_user_id."' AND user_type_id='".(int)$set_user_type_id."' $str1 )";
		
        $LastInvoiceId = $this->getLastIdSales($str);
        //printr($LastInvoiceId);
        $thedate = explode("-", date('Y-m-d'));
	    $month = $thedate[1]; 
		$day = $thedate[2];
		$latter='';
		if($set_user_id=='24')
		    $latter = 'M';
		elseif($set_user_id=='33')
		    $latter = 'S';
		//printr($LastInvoiceId['invoice_no']);
		$year = date("Y", time());
		$sql_year = "SELECT YEAR(date_added) FROM sales_invoice where YEAR(date_added) = '".$year."' AND $str";
		$result = $this->query($sql_year);
		$count = $result->num_rows;
		//echo $count;
        if(empty($LastInvoiceId))
           $id=$latter.''.$countryCode.'00000000-'.date('Y');
        else
        {
    		//if($month.'-'.$day=='01-01')
    		if($count == '0')
    			$id=$latter.''.$countryCode.'00000000-'.date('Y');
    		else    
    			$id=$LastInvoiceId['invoice_no'];
        } 
       //printr($id);   	
       /*   if($_SESSION['ADMIN_LOGIN_SWISS']=='60' && $_SESSION['LOGIN_USER_TYPE']=='2'){
        	printr($id);
        	printr($latter);
        	printr($LastInvoiceId);
        	printr($invoice_no);
        	printr($countryCode);
        	printr($str);
            	die;
        }*/
       // $id=$LastInvoiceId['invoice_no'];
	//	printr($id);
		$inv_id = explode("-",$id);
		
		$invoice_no = preg_replace_callback("|(\d+)|", function($matches){ $length = '8'; return sprintf("%0".$length."d", ++$matches[1]);}, $inv_id[0]);
		
        $invoice_no = $invoice_no.'-'.date('Y');
        
        
        //if($_SESSION['ADMIN_LOGIN_SWISS']=='60' && $_SESSION['LOGIN_USER_TYPE']=='2')
           // printr($invoice_no.'==>'.$id.'==>'.$count);die;
            
//	printr($invoice_no);
//	die;
	//printr($invoice_no);
    		/*$LastInvoiceId = $this->getLastIdSales();
            
    		$id=$LastInvoiceId['invoice_id']+1;
    
    		$pur_id=str_pad($id,8,'0',STR_PAD_LEFT);
    
    		$invoice_no=$countryCode.$pur_id;*/

        //die;
        
        
      
		$state=$gst=$hst=$pst=0;

		if($set_user_id==44)

		{

			$state = $proforma_detail['state'];

			$gst = $proforma_detail['gst'];

			$pst = $proforma_detail['pst'];

			$hst = $proforma_detail['hst'];

		} 

//add sonu payment detail for india  18-7-2017 		
		
		if($proforma_detail['destination'] == '111'){
				$payment = ",tax_mode ='".$proforma_detail['tax_mode']."',cgst='".$proforma_detail['cgst']."',sgst ='".$proforma_detail['sgst']."',igst='".$proforma_detail['igst']."',date_of_payment_receipt='".$payment_detail['payment_receive_date']."',amount_paid='".$payment_detail['total']."',pay_type_maxico = '".$payment_detail['payment_type']."',detail_maxico='".$payment_detail['payment_detail']."',payment_maxico='".$payment_detail['payment_mode']."',curr_id='".$payment_detail['currency']."'";
		}else{
				$payment=",curr_id='".$proforma_detail['currency_id']."'";
		}

//end
		$vat = '';
		if($proforma_detail['destination'] == '155')
			$vat = '<br> RFC NO :'.$proforma_detail['vat_no'];
        if($set_user_id=='19' || $set_user_id=='24' || $set_user_id=='33' || $set_user_id=='44' || $set_user_id=='10')
            $gen_status = '1';
        else
            $gen_status = '0';
		
		$sql = "INSERT INTO `" . DB_PREFIX . "sales_invoice` SET invoice_no = '".$invoice_no."',invoice_date = NOW(),proforma_no = '" .$proforma_detail['pro_in_no']. "',exporter_orderno = '".$proforma_detail['buyers_order_no']."',buyers_orderno ='".$buyers_orderno."',consignee='".addslashes($proforma_detail['address_info']).''.$vat."',company_address='".addslashes($addedByInfo['company_address'])."',country_destination='".$proforma_detail['destination']."',customer_name = '".addslashes($proforma_detail['customer_name'])."',contact_name = '".addslashes($proforma_detail['contact_name'])."',address_book_id='".$proforma_detail['address_book_id']."', email = '".$proforma_detail['email']."',customer_dispatch='".$proforma_detail['customer_dispatch']."',final_destination='".$proforma_detail['destination']."', state = '".$state."',can_gst = '".$gst."',pst = '".$pst."',hst = '".$hst."', payment_terms='".$proforma_detail['invoice_total']."',discount = '".$proforma_detail['discount']."',status = '1',date_added = NOW(),date_modify = NOW(),delivery_charges='".$proforma_detail['delivery_charges']."',other_charges_comments='".$proforma_detail['other_charges_comments']."',other_charges='".$proforma_detail['other_charges']."',user_id='".$proforma_detail['added_by_user_id']."',user_type_id='".$proforma_detail['added_by_user_type_id']."' $payment ,is_delete=0,tax_maxico = '".$tax_maxico."', pay_type_maxico = 'full',payment_maxico = 'transfer',date_of_payment_receipt= NOW(),gen_status='".$gen_status."',final_total=0,amount_paid = ".$proforma_detail['invoice_total']."";
        //echo $sql;//die;
		$datasql=$this->query($sql);

		$invoice_id = $this->getLastIdSales();
        
		
        
		foreach ($proforma_product_detail as $pro_product)

		{
            //printr('hii');
			$product_code_id=$pro_product['product_code_id'];

			$sin_account_code ='';
            if($proforma_detail['destination'] == '155')
            {
                if($pro_product['express_rate']!='0')
                    $rate = $pro_product['express_rate'];
                else
                    $rate = $pro_product['rate'];
            }
            else
			    $rate = $pro_product['rate'];

			$qty = $pro_product['sales_qty'];
            
			$pro[] = $qty;
            if($qty!=0)
			{
    			$rack = ",rack_remaining_qty='".$qty."'";
    			if($product_code_id==0)
    			    $rack = ",rack_remaining_qty='0'";
    			if($set_user_id=='24' || $set_user_id=='33')
    			{
    			    $tra = "SELECT tp.qty,t.transfer_invoice_id FROM transfer_invoice as t,transfer_invoice_product as tp WHERE t.transfer_invoice_id = tp.invoice_id AND t.proforma_no='" .$proforma_detail['pro_in_no']. "' AND tp.product_code_id='".$product_code_id."' AND t.dis_or_warehouse='1'";
    			    $data_tra=$this->query($tra);
    			    $rem_qty = $qty;
    			    if($data_tra->row['qty']!='' || $data_tra->row['qty']!=0)
    			        $rem_qty = $qty - $data_tra->row['qty'];
    			    $rack = ",rack_remaining_qty='".$rem_qty."'";
    		    }
    			
    			
    			$sql2 = "Insert into sales_invoice_product Set invoice_id='".$invoice_id['invoice_id']."',product_code_id='".$product_code_id."',product_description ='".$pro_product['product_name']."', sin_account_code= '".$sin_account_code."', rate ='".$rate."', qty = '".$qty."', date_added = NOW(), date_modify = NOW(), is_delete = 0,pedimento_mexico = '".$pro_product['pedimento_mexico']."',stock_print = '".$pro_product['stock_print']."',stock_con = '".$pro_product['stock_con']."',plate = '".$pro_product['plate']."',tool_price=-'".$pro_product['tool_price']."',customer_dispatch_p='".$pro_product['customer_dispatch_p']."' $rack";  
                //echo $sql2;
    			$data2=$this->query($sql2);
			}
		}//printr($invoice_id['invoice_id']);
    //printr($pro);
		$count = count($pro);
		if(!in_array("0", $pro) && $count>=1)
		{
		   //echo 'hii'; 
		}
		else if(in_array("0",$pro) && $count>1)
		{
		    //echo 'else';
		}
		else
		{
		    $sqlr ="UPDATE sales_invoice SET rack_notify_status='1' WHERE invoice_id='".$invoice_id['invoice_id']."' ";
			$this->query($sqlr);
			//echo $sqlr;
		}
		//printr('sdsfsdf');
		if($proforma_detail['freight_charges']!='0.000')

		{

			$product_code_id='';

			$rate = $proforma_detail['freight_charges'];

			$qty = '1';//1

			$sin_account_code = '260';//260

			

			$sql2 = "Insert into sales_invoice_product Set invoice_id='".$invoice_id['invoice_id']."',product_code_id='".$product_code_id."',product_description ='".$pro_product['product_name']."', sin_account_code= '".$sin_account_code."', rate ='".$rate."', qty = '".$qty."', rack_remaining_qty='0',date_added = NOW(), date_modify = NOW(), is_delete = 0"; 

			$data2=$this->query($sql2);

			//echo $sql2;

		}
	//die;
	    //printr("UPDATE proforma_product_code_wise SET s_status='1' WHERE proforma_id='".$proforma_id."'");
        $this->query("UPDATE proforma_product_code_wise SET gen_sales_status='1' WHERE proforma_id='".$proforma_id."'");
        //printr($invoice_id['invoice_id']);
		return $invoice_id['invoice_id'];

	}

	

	public function getTaxationCanada()

	{

		$sql = "SELECT * FROM taxation_canada ORDER BY state ASC";

		$data = $this->query($sql);

		if($data->num_rows){

			return $data->rows;

		}

		else {

			return false;

		}

	}

	public function stateDetail($state_id)

	{

		$sql = "Select * From taxation_canada WHERE taxation_canada_id = '".$state_id."'";

		$data = $this->query($sql);

		if($data->num_rows) {

			return $data->row;

		} else {

			return false;

		}

	}

	

	public function getProductCdAll($product_id,$volume,$color)

	{	

		$result=$this->query("SELECT pc.product_code, pc.product_code_id, pc.description, clr.color, pc.volume, pc.measurement,p.product_name,pc.product,pc.zipper FROM " . DB_PREFIX ."product_code as pc, pouch_color as clr, product as p WHERE pc.is_delete=0 AND pc.color=clr.pouch_color_id AND pc.product=p.product_id AND pc.product = '".$product_id."' AND pc.volume = '".$volume."' AND pc.color = '".$color."' " );
		return $result->rows;

	}

	

	public function getCustomerDetail($customer_name)

	{   
	    $user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
        $str='';
		if($user_type_id == 2){

			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");

			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);

			$set_user_id = $parentdata->row['user_id'];
            
			$set_user_type_id = $parentdata->row['user_type_id'];
			$ib = $this->getUser($set_user_id,$set_user_type_id);
			//$e =array();
			if($ib['country_id'] == '111')
			{
			 
			  $ib_id = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "address WHERE user_type_id = '4' AND country_id = '".$ib['country_id']."'");
			  foreach($ib_id->rows as $key=>$ids)
			  {
			      
			      $emp = $this->getUserEmployeeIds($ids['user_type_id'],$ids['user_id']);
			      $e[] = $emp;
			      $i[]=$ids['user_id'];
			  }
			  $e_id = implode(",",$e);
			  $set_user_id = implode(",",$i);
			  if($e_id)
			    $str = ' OR ( aa.user_id IN ('.$e_id.') AND aa.user_type_id = 2 )';
			}
			else
			{
    			if($userEmployee)
    			    $str = ' OR ( aa.user_id IN ('.$userEmployee.') AND aa.user_type_id = 2 )';
			}
			$sql = "SELECT aa.address_book_id,aa.vat_no,cs.phone_no, aa.company_name,aa.contact_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.status=1 AND aa.company_name LIKE '%".$customer_name."%' AND ((aa.user_id IN (".$set_user_id .") AND aa.user_type_id='".$set_user_type_id ."') $str )  GROUP BY aa.address_book_id LIMIT 15";
            
		}
		else if($user_type_id == 4)
		{

			$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);

			$set_user_id = $user_id;

			$set_user_type_id = $user_type_id;
			$ib = $this->getUser($set_user_id,$set_user_type_id);
			
			if($ib['country_id'] == '111')
			{
			 
			  $ib_id = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "address WHERE user_type_id = '4' AND country_id = '".$ib['country_id']."'");
			  
			  foreach($ib_id->rows as $key=>$ids)
			  {
			      
			      $emp = $this->getUserEmployeeIds($ids['user_type_id'],$ids['user_id']);
			      $e[] = $emp;
			      $i[]=$ids['user_id'];
			  }
			  $e_id = implode(",",$e);
			  $set_user_id = implode(",",$i);
			  if($e_id)
			    $str = ' OR ( aa.user_id IN ('.$e_id.') AND aa.user_type_id = 2 )';
			}
			else
			{
    			if($userEmployee)
    			    $str = ' OR ( aa.user_id IN ('.$userEmployee.') AND aa.user_type_id = 2 )';
			}
				
			$sql = "SELECT aa.address_book_id,aa.vat_no,cs.phone_no, aa.company_name,aa.contact_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.status=1 AND aa.company_name LIKE '%".$customer_name."%' AND ((aa.user_id IN (".$set_user_id .") AND aa.user_type_id='".$set_user_type_id ."') $str  )  GROUP BY aa.address_book_id LIMIT 15";
           
		}

		else

		{

			$set_user_id = $user_id;

			$set_user_type_id  = $user_type_id;
			
			$sql = "SELECT aa.address_book_id,aa.vat_no,cs.phone_no, aa.company_name,aa.contact_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.status=1 AND aa.company_name LIKE '%".$customer_name."%' GROUP BY aa.address_book_id LIMIT 15";
		}
        
		$data = $this->query($sql);
		
		if($data->num_rows){

			return $data->rows;

		}else{

			return false;

		}		
	}
	
	//manirul 8-4-2017
	public function getmatchdata($proforma_id,$user_type_id,$user_id,$proforma_no,$admin_user_id='0')
	{	
		if($admin_user_id=='10')
        {
		    $proforma_inv = $this->getInvoicepacking($proforma_no);
        }
        else if($admin_user_id == '19')
        {
		    $proforma_inv = $this->getInvoiceProductDetails($proforma_id);
        }
        else
        {
            $proforma_inv = $this->getInvoice($proforma_id);
        }
		
		//$product_code=array();
		$html='';
		if(!empty($proforma_inv)){
			foreach($proforma_inv as $pro_inv)
			{
				$sale_detail = $this->getStockQty($pro_inv['product_code_id'],$user_type_id,$user_id,$proforma_no);
				if($sale_detail)
				{
						$all_details= "SELECT product_code FROM product_code WHERE product_code_id='".$pro_inv['product_code_id']."'";
						$data = $this->query($all_details);
						if($data->num_rows)
						{
							$html.='<tr>
										<td>'.$data->row['product_code'].'</td>
										<td>'.$pro_inv['quantity'].'</td>
										<td>';
											if($sale_detail['remaining_qty']=='0')
												$html.='<span style="color:red"><b>Not Available</b><span>';
											elseif($pro_inv['quantity'] > $sale_detail['remaining_qty'])
												$html.='<span style="color:red"><b>'.$sale_detail['remaining_qty'].'</b><span>';
											else
												$html.=$sale_detail['remaining_qty'];
										$html.='</td>
									</tr>';
						}
				}
			}
		}
		return $html;
	}
	//manirul END
	
    public function getLastIdAddress() {
        $sql = "SELECT address_book_id FROM  address_book_master ORDER BY address_book_id DESC LIMIT 1";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row;
        } else {
            return false;
        }
    }

    //[kinjal] : on 21-6-2017
	public function clone_proforma($proforma_id)
	{
		$main_data=$this->getProforma($proforma_id);
		//printr($main_data);//die;
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];

        $user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$userCountry = $this->getUserCountry($user_type_id, $user_id);
		if ($userCountry) {
			$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] ) ? $userCountry['country_code'] : 'IN';
		} else {
			$countryCode = 'IN';
		}
 
		$pi = 'PI-';
		$new_pro_in_no = $this->generateProformaNumber();
		$pro_in_no = $pi . $countryCode . $new_pro_in_no;
		
		
		$sql = "INSERT INTO proforma_product_code_wise set pro_in_no = '" . $pro_in_no . "',proforma = '" .date('Y-m-d'). "',gen_pro_as = '".$main_data['gen_pro_as']."', customer_name = '" . addslashes($main_data['customer_name']) . "',address_book_id = '" .$main_data['address_book_id']. "', email = '" . $main_data['email'] . "',contact_no = '" . $main_data['contact_no'] . "', buyers_order_no = '" . $main_data['buyers_order_no'] . "', invoice_date = '" . $main_data['invoice_date'] . "', goods_country = '" . $main_data['goods_country'] . "', buyers_date = '" . $main_data['buyers_date'] . "', address_info = '" . addslashes($main_data['address_info']) . "',del_address_info = '" . addslashes($main_data['del_address_info']) . "',same_as_above='".$main_data['same_as_above']."',vat_no='" . $main_data['vat_no'] . "',qst_no='" . $main_data['qst_no'] . "',delivery_info = '" . addslashes($main_data['delivery_info']) . "', currency_id = '" . $main_data['currency_id'] . "', bank_id = '" . $main_data['bank_id'] . "', customer_dispatch='".$main_data['customer_dispatch']."',customer_bank_detail='".$main_data['customer_bank_detail']."', payment_terms = '" . $main_data['payment_terms'] . "', destination = '" . $main_data['destination'] . "', state = '" . $main_data['state'] . "',gst = '" . $main_data['gst'] . "',pst = '" . $main_data['pst'] . "',hst = '" . $main_data['hst'] . "', port_loading = '" . $main_data['port_loading'] . "', transportation = '" .$main_data['transportation'] . "', added_by_user_id = '" . $user_id . "', added_by_user_type_id = '" . $user_type_id . "', status = '1' , proforma_status = '0' , date_added = NOW(), date_modify = NOW(), is_delete = 0,tax_mode='" . $main_data['tax_mode'] . "',tax_form_name='" . $main_data['tax_form_name'] . "',excies_per='" . $main_data['excies_per'] . "',cgst='" . $main_data['cgst'] . "',sgst='" . $main_data['sgst'] . "',igst='" . $main_data['igst'] . "',taxation='" . $main_data['taxation'] . "',taxation_per='" . $main_data['taxation_per'] . "',freight_charges='" . $main_data['freight_charges'] . "',delivery_charges='" . $main_data['delivery_charges'] . "',other_charges_comments='" . $main_data['other_charges_comments'] . "',other_charges='" . $main_data['other_charges'] . "',packing_charges='" . $main_data['packing_charges'] . "',pro_remark='" . $main_data['pro_remark'] . "',gst_tax='" . $main_data['gst_tax'] . "',discount='" . $main_data['discount'] . "',invoice_total='". $main_data['invoice_total']."'";
		$data = $this->query($sql);
		$ProformaId = $this->getLastId();
		//printr($ProformaId);die;
		$this->query("UPDATE proforma_product_code_wise SET invoice_number='".$ProformaId['proforma_id']."' WHERE proforma_id=".$ProformaId['proforma_id']."");
		
		$product_data=$this->getInvoice($proforma_id);
		
		
		
		foreach($product_data as $pro)
		{
			
			
			 
			$sql_insert = "INSERT INTO proforma_invoice_product_code_wise SET proforma_id = '" . $ProformaId['proforma_id']. "',added_by_user_id = '" . $user_id . "', added_by_user_type_id = '" . $user_type_id . "', invoice_number ='" .$ProformaId['proforma_id'] . "', product_code_id = '" . $pro['product_code_id'] . "', product_name = '" . $pro['product_name'] . "',description = '" .addslashes($pro['description']) . "',quantity = '" . $pro['quantity'] . "',rate = '" . $pro['rate'] . "',express_rate = '" . $pro['express_rate'] . "',color_text = '" .addslashes($pro['color_text']) . "',  measurement='" . $pro['measurement'] . "',gusset_printing_option='" . $pro['printing_option_type'] . "',printing_option='" . $pro['printing'] . "',  size = '" . $pro['size'] . "', date_added = NOW(), date_modify = NOW(), is_delete = 0,sales_qty='".$pro['quantity'] ."',tool_price='".$pro['tool_price']."',stock_print = '".$pro['stock_print']."',stock_con = '".$pro['stock_con']."',plate = '".$pro['plate']."',customer_dispatch_p='".$pro['customer_dispatch_p']."'";
			
			$data_insert = $this->query($sql_insert);

		}
		//printr($sql_insert);die;
		
		//die;
	}
	//[kinjal] on 28-6-2017
	public function viewSalesAnalytic($post)
	{
		//printr($post['f_date']);die;
		//$from_date = $post['f_date'];
        //$t_date = $post['t_date'];
		$con = '';
		if ($post['f_date'] != '') 
		{
            //$f_date = $from_date;
            $con = "AND pc.date_added >= '" .$post['f_date']. "' ";
        }
        if ($post['t_date'] != '')
		{
            //$to_date = $t_date;
            $con .= "AND  pc.date_added <='" .$post['t_date']. "'";
        }
		$sql="SELECT pc.pro_in_no,s.invoice_no,s.date_added as sales_date,pc.date_added as pro_date,s.proforma_no,pc.proforma_id	,s.invoice_id FROM proforma_product_code_wise as pc LEFT JOIN sales_invoice as s ON (s.proforma_no=pc.pro_in_no) WHERE pc.is_delete=0 AND pc.proforma_status=0 $con";
		$data = $this->query($sql);
		//echo $sql;
		$sales='';
		foreach($data->rows as $row)
		{
			
			
			if($row['pro_in_no']==$row['proforma_no'])
			{
				//$sales[$row['invoice_no']][]= array('invoice_no'=>$row['invoice_no'],
													//'sales_date'=>$row['sales_date']);
				$arr[$row['pro_in_no']][] = array('pro_date'=>$row['pro_date'],
												  'proforma_id'=>$row['proforma_id'],
												'sales_detail'=> array('invoice_no'=>$row['invoice_no'],
																	   'sales_date'=>$row['sales_date']),
																	   'invoice_id'=>$row['invoice_id']);

			}
		}
		//printr($arr);
	    $html='';
		
		$html .= "<div class='form-group'>
						<div class='table-responsive'>";
					 		if (!empty($post['f_date']) && !empty($post['t_date'])) {
								$html .= "&nbsp;&nbsp;&nbsp;&nbsp;<span>Searching Date From: <b>" . dateFormat(4, $post['f_date']) . "</b> To: <b>" .dateFormat(4, $post['t_date']). "</b></span><br></br>";
							}
							 $html .= "<table class='table table-striped b-t text-small' id='enquiry_report'>
										<thead>
											
												";
							if (isset($arr) && !empty($arr)) {
								   $html .= "<tr>
												  <th>Proforma Number</th>
												  <th>Sales Inv</th>
												  
											</tr>
                                		</thead>
                                		<tbody>";
										
											foreach($arr as $key=>$sales_data)
											{ //printr($sales_data[0]['proforma_id']);<a href='".HTTP_SERVER."admin/index.php?route=proforma_invoice_product_code_wise&mod=view&proforma_id=".encode($sales_data[0]['proforma_id'])."&is_delete=0'></a>
														$html .= "<tr>
																	<td href='".HTTP_SERVER."admin/index.php?route=proforma_invoice_product_code_wise&mod=view&proforma_id=".encode($sales_data[0]['proforma_id'])."&is_delete=0'>".$key. "<br><small class='text-muted'>".dateFormat(4,$sales_data[0]['pro_date'])."</small></td>
																	
																	<td>
																		<table class='table table-striped b-t text-small'>";												
																			foreach($sales_data as $sale)
																			{ //printr($sale);
																				$html .= "<tr>
																							<td>
																								" .$sale['sales_detail']['invoice_no']. "<br><small class='text-muted'>".dateFormat(4,$sale['sales_detail']['sales_date'])."</small>
																							</td>";																				
																				$html .="</tr>";
																			}
																		$html .="</table>
																	</td>
																	
																  <tr>";
											}
										}
										else
										{
											$html.= "<tr>No Records Found!!</tr>";
										}
							$html .= "</tbody>
									
								</table>
							</div>
						</div>";
			return $html;
	}
	
	  public function getproductrate($product_code_id, $color) {
		//  printr($color);
        $sql = "SELECT color_value FROM  pouch_color WHERE color LIKE '%" . $color . "%' AND color_value!=0";
      //  echo $sql;
		$data = $this->query($sql);
		//	printr($data);
        if ($data->row['color_value'] == '1') {
            $colorprice = 'all_clr_price';
        } elseif ($data->row['color_value'] == '2') {
            $colorprice = 'clear_price';
        } elseif ($data->row['color_value'] == '3') {
            $colorprice = 'biodegradable_price';
        } elseif ($data->row['color_value'] == '4') {
            $colorprice = 'ultra_clear_price';
        } elseif ($data->row['color_value'] == '5') {
            $colorprice = 'sup_zz_oval_window';
        } elseif ($data->row['color_value'] == '5') {
            $colorprice = 'sup_zz_oval_window';
        } elseif ($data->row['color_value'] == '6') {
            $colorprice = 'stripped_bkp_look_zz';
        } elseif ($data->row['color_value'] == '7') {
            $colorprice = 'sup_zz_jtk';
        } elseif ($data->row['color_value'] == '8') {
            $colorprice = 'sup_bkp_zz';
        } elseif ($data->row['color_value'] == '9') {
            $colorprice = 'sup_bkp_zz_oval_window';
        } elseif ($data->row['color_value'] == '10') {
            $colorprice = 'sup_bkp_whp_zz_full_rec_win';
        } elseif ($data->row['color_value'] == '11') {
            $colorprice = 'sup_zz_clear_bkp';
        } elseif ($data->row['color_value'] == '12') {
            $colorprice = 'sup_crystal_clear_price';
        } elseif ($data->row['color_value'] == '13') {
            $colorprice = 'sup_whp_zz';
        } elseif ($data->row['color_value'] == '14') {
            $colorprice = 'sup_gp_bp_zz';
        } elseif ($data->row['color_value'] == '15') {
            $colorprice = 'sup_gp_bp_zz_full_rect';
        } else {
            $colorprice = '';
        }

        $sql_rate = "SELECT p." . $colorprice . " as price,pc.* FROM product_price_list as p,product_code as pc,template_measurement as t WHERE t.product_id=pc.measurement AND p.product_id =pc.product AND p.accessorie_id=pc.accessorie AND p.zipper_id=pc.zipper AND p.spout_id =pc.spout AND p.volume= CONCAT(pc.volume,' ',t.measurement) AND pc.product_code_id='" . $product_code_id . "' AND p.is_delete=0";
	//echo $sql_rate;
	   $data1 = $this->query($sql_rate);
	 //  printr($data1);
		if($data1->num_rows) {
			
			if($data1->row['valve']=='With Valve')
			{
				$price = $data1->row['price']+3;
				return $price;
			}
			else
			    return $data1->row['price'];
		} else {
			return false;
		}
		
      
    }

	//end [kinjal]
	
	
	
	//add sonu payment 18-7-2017
	

	
	
	public function InsertPayment_detail($post,$admin_email) {
		
		$payment_status = $paid_amt=$edit_status=0;
		
		$user_type_id = $_SESSION['LOGIN_USER_TYPE']; 
        $user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		
		
		$payment_date=date("Y-m-d", strtotime($post['datetime']));
		$sql_payment = "Insert into proforma_payment_detail SET proforma_id='".$post['gen_invoice_id']."',payment_mode='".$post['payment']."',payment_amount='".$post['amt_maxico']."',currency='1',payment_detail='".$post['detail_maxico']."',payment_type='".$post['payment_type']."',irtgs='".$post['transfer_irtgs']."',neft='".$post['transfer_neft']."',payment_receive_date ='".date("Y-m-d", strtotime($post['datetime']))."', Remainder ='".date("Y-m-d", strtotime($post['remainder']))."',date_added = NOW(), date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."', is_delete = 0";
		$data = $this->query($sql_payment);
		$proforma_inv = $this->getProforma($post['gen_invoice_id']);
		
		$sql = "SELECT sum(payment_amount) as total FROM `proforma_payment_detail` WHERE `proforma_id` = '" . $post['gen_invoice_id'] . "' AND is_delete = 0 ORDER BY `payment_id` DESC ";
		$data_sel= $this->query($sql);
		if($data_sel->num_rows)
		{
		    $paid_amt =$data_sel->row['total'];
		}
		if($paid_amt == $post['invoice_total']){
		    $payment_status =1;$edit_status=1;
		}
		    
		$sql = "UPDATE proforma_product_code_wise SET payment_status='".$payment_status."'  ,edit_status ='".$edit_status."' WHERE proforma_id='".$post['gen_invoice_id']."'";
		$data = $this->query($sql);
		//$send_email_for_Add_payment = $this->send_email_for_Add_payment($post['gen_invoice_id'],$payment_date,$post['amt_maxico'],$user_id,$user_type_id,$admin_email);
       // printr($sql);
    }
	
	 public function Payment_detail($proforma_id) {
	
		
		
			    $sql = "SELECT *,sum(payment_amount) as total FROM `proforma_payment_detail` WHERE `proforma_id` = '" . $proforma_id . "' AND is_delete = 0 ORDER BY `payment_id` DESC ";
				$data = $this->query($sql);
				
			    $sql2 = "SELECT * FROM `proforma_payment_detail` WHERE `proforma_id` = '" . $proforma_id . "' AND is_delete = 0 ORDER BY   `payment_id` DESC ";
				$data2 = $this->query($sql2);
			
				$total=$payment_receive_date=$payment_id=$proforma_id=$payment_type=$payment_amount=$currency=$payment_detail=$payment_mode='';
				if($data->num_rows)
					$total=$data->row['total'];
					$payment_id=$data->row['payment_id'];
					$proforma_id=$data->row['proforma_id'];
					$payment_type=$data->row['payment_type'];
					$payment_amount=$data->row['payment_amount'];
					$currency=$data->row['currency'];
					$payment_detail=$data->row['payment_detail'];
					$payment_mode=$data->row['payment_mode'];
					$Remainder =$data->row['Remainder'];
					
				
				
				if($data2->num_rows)
					$payment_receive_date=$data2->row['payment_receive_date'];
				
			
				$data_return = array('total'=>$total,
									'payment_receive_date'=>$payment_receive_date,
									'payment_id'=>$payment_id,
									'proforma_id'=>$proforma_id,
									'payment_type'=>$payment_type,
									'payment_amount'=>$payment_amount,
									'currency'=>$currency,
									'payment_detail'=>$payment_detail,
									'payment_mode'=>$payment_mode,
									'Remainder'=>$Remainder
									
								);
				//printr($data_return);die;
				return $data_return;
		
       
    }
	//end
	
	
	 public function Payment_detail_edit($payment_id) {
		
		    $sql = "SELECT * FROM `proforma_payment_detail` WHERE `payment_id` = '" .$payment_id . "'  AND is_delete ='0' ORDER BY `payment_id` DESC ";
			$data = $this->query($sql);
			 if ($data->num_rows) {
			return $data->row;
			} else {
				return false;
			}
    }
	//end
	public function Payment_detail_for_Customer($proforma_id){
		 $sql = "SELECT * FROM `proforma_payment_detail` as pd ,proforma_product_code_wise as p  WHERE  pd.proforma_id= '" . $proforma_id . "' AND pd.proforma_id = p.proforma_id  AND p.is_delete = '0' AND pd.is_delete='0'";
		 $data = $this->query($sql);
		
		$html = '';	
			$html .= "<div class='form-group' style='font-size:28'>
						<div class='table-responsive'>";
						$html .= "<center  ><span  ><b id='lamination' >Payment Detail</b></span></center><br>";			
		
						$html .= '<table class="table b-t text-small table-hover" width="100%" >
								<thead>							
										<tr style="border:groove;">
										
												<th width="5%"><b>Sr.No</b></th>
												<th width="15%"><b>Proforma No</b></th>
												<th width="15%"><b>Customer Name</b></th>
												<th width="15%"><b>Customer Email</b> </th>
												<th width="5%"><b>Mode Of Payment</b></th>
												<th width="5%"><b>Payment Type</b></th>
												<th width="10%"><b>Payment Receipt No</b></th>
												<th width="10%"><b>Date of Payment Receipt</b></th>
												<th width="10%"><b>Amount</b></th>
												<th width="10%"><b>Action</b></th>
										</tr>
								
								';
						$html .='</thead>
									<tbody >';
								
									 if($data->rows){
									//	printr($data);//die;
										$n=1;
										foreach($data->rows as $inv) 
										{
										$currency = $this->getCurrencyId($inv['currency_id']);
									//	printr($currency);
								
										 $html.= '<tr>
														<td width="5%">'.$n.'</td>
														<td width="15%">'.$inv['pro_in_no'].'</td>
														<td width="15%">'.$inv['customer_name'].'</td>
														<td width="15%">'.$inv['email'].'</td>
														<td width="5%">'.$inv['payment_mode'].'</td>
														<td width="5%">'.$inv['payment_type'].'</td>
														<td width="10%">'.$inv['neft'].'</td>
														<td width="10%">'. dateFormat (4,$inv['payment_receive_date']).'</td>
														<td width="10%">'. $inv['payment_amount'].' '.$currency['currency_code'].'</td>
														<td width="10%">';
											
                                  		 $html.= '<a onclick="remove_payment('.$inv['payment_id'].','.$inv['proforma_id'].')"  data-original-title="Remove Payment" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title="">
											<i class="fa fa-trash-o"></i></a></td></tr>'; 
												$n++;
											}
										};
									
									
						$html .= "</tbody>
								</table>
							 </div>
						</div>";
		
			return $html;
	}
	
	function remove_payment($payment_id,$proforma_id){
		$sql = "UPDATE proforma_payment_detail SET is_delete='1'  ,date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."'  WHERE payment_id='".$payment_id."'";
		$data = $this->query($sql);
	
		/*$edit_status=1;
		$sql = "SELECT sum(payment_amount) as total FROM `proforma_payment_detail` WHERE `proforma_id` = '" .$proforma_id. "' AND is_delete = 0 ORDER BY `payment_id` DESC ";
		$data_sel= $this->query($sql);
		if($data_sel->num_rows)
		{
		    $paid_amt =$data_sel->row['total'];
		}
		if($paid_amt==0)
		    $edit_status =0;*/
		    
		$sql_pro = "UPDATE proforma_product_code_wise SET payment_status=0  ,edit_status ='0' WHERE proforma_id='".$proforma_id."'";
		$data_pro = $this->query($sql_pro);
		
	}
	function update_payment_detail($payment_id,$payment_amount,$payment_detail,$payment_mode,$payment_receive_date,$payment_type,$Remainder='0000-00-00'){
		
		
		$sql_payment = "UPDATE  proforma_payment_detail SET payment_mode='".$payment_mode."',payment_amount='".$payment_amount."',currency='1',payment_detail='".$payment_detail."',payment_type='".$payment_type."',payment_receive_date ='".date("Y-m-d", strtotime($payment_receive_date))."', Remainder ='".date("Y-m-d", strtotime($Remainder))."',date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."', is_delete = 0 WHERE payment_id='".$payment_id."' ";
	
	$data = $this->query($sql_payment);
	}
	  
	

    
	// add sonu for all country price 25-8-2017
	
	public function productrateAllCountry($product_code_id,$transportation,$qty,$country_id,$ib_user_id='0'){
		$admin_user_id ='';
        if($ib_user_id!=0)
            $admin_user_id = ' AND user_id ="'.$ib_user_id.'" AND user_type_id="4" ' ;
        $status = ' AND pp.price_status=0';
        if($country_id=='42')
            $status = ' AND pp.price_status=1';
            
		$sql ="SELECT * FROM `proforma_price_list` as pp ,pouch_color as pc , product_code as p WHERE pp.product_id =p.product AND pc.color_category =pp.category_id AND pp.`volume` = p.volume AND pp.`measurement`=p.measurement AND pp.accessorie_id=p.accessorie AND pp.zipper_id=p.zipper AND pp.spout_id =p.spout AND  pp.country_id ='".$country_id."' AND p.product_code_id='".$product_code_id."' AND p.color=pc.pouch_color_id $status  $admin_user_id group by p.product_code_id";
		$data = $this->query($sql);
        return $data->row;
	}
	
	
	
	//end sonu 
			
    function viewProformaInvoiceInSpanish($proforma_id) {
	

			//echo $proforma_id.'kkkk';

			$html ='';

			$proforma=$this->getProformaData($proforma_id);

			//printr($proforma);

			$proforma_id=$proforma['proforma_id'];

			$proforma_inv=$this->getProformaInvoice($proforma_id);

			$user_name=$this->getUser($proforma['added_by_user_id'], $proforma['added_by_user_type_id']);

			//printr($proforma_inv);

			$show_vat='';
			$show_qst='';
		
			$qst_no = '';
			$admin_vat_no='';
			
		
		
			if($proforma['added_by_user_id'] == '1' && $proforma['added_by_user_type_id'] =='1')

			{

				$image= HTTP_UPLOAD."admin/store_logo/logo.png";

				$img = '<img src="'.$image.'" alt="Image">';

			}

			else

			{

				

				if($proforma['added_by_user_type_id'] == 2){

					$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$proforma['added_by_user_id']."' ");

					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);

					$set_user_id = $parentdata->row['user_id'];

					

					$set_user_type_id = $parentdata->row['user_type_id'];

					//echo "1 echio  ";

				}else{

					$userEmployee = $this->getUserEmployeeIds($proforma['added_by_user_type_id'],$proforma['added_by_user_id']);

					$set_user_id = $proforma['added_by_user_id'];

					//echo $set_user_id."2";

					$set_user_type_id = $proforma['added_by_user_type_id'];

				}

				$user_info=$this->getUser($set_user_id,'4');

				//printr($user_info);

				//echo $set_user_id;

				$data=$this->query("SELECT logo,abn_no,termsandconditions_invoice,note_invoice FROM international_branch WHERE international_branch_id = '".$set_user_id."'");

				//echo "SELECT logo,abn_no,termsandconditions_invoice,note_invoice FROM international_branch WHERE international_branch_id = '".$set_user_id."'";

				//printr($data);

				if(isset($data->row['logo']) &&  $data->row['logo']!= '')

				{

					$image= HTTP_UPLOAD."admin/logo/200_".$data->row['logo'];

					//echo $image;

					$img = '<img src="'.$image.'" alt="Image">';

				}

				else

				{

					$img ='';

				}			

			}

			
			if($proforma['added_by_user_id']!='1' && $proforma['added_by_user_type_id'] != '1')

			{	$title='De';

				$user=$this->getCompanyAdd($proforma['added_by_user_type_id'],$proforma['added_by_user_id']);
			   
				$address=nl2br($user['company_address']);
        
				$sign=$user['company_name'];

				$admin_vat_no = 'RFC No. : '.$user['vat_no'];

				$vat_no = $proforma['vat_no'];

				
				$show_vat = 'RFC No. :'.$vat_no;
				//printr($user_info);die;
				if($user_info['country_id']==155)
				{//printr($proforma['gen_pro_as']);die;
				   if($proforma['gen_pro_as']=='1')
				        $address=nl2br($user['company_address']); 
				   else
				   {
				        $address='SWISS PAC PVT LTD</b><br>Padra Jambusar National highway<br>At Dabhasa village,Pin 391440<br>Taluka.Padra, Dist.Vadodara(State Gujarat) India ';
				        $admin_vat_no = 'Vat No. : 24AADCS2724B1ZY';
				   }
				   
				}
			}

			else

			{

				$title='Consignor';

				$address='SWISS PAC PVT LTD</b><br>Padra Jambusar National highway

				<br>At Dabhasa village,Pin 391440<br>Taluka.Padra, Dist.Vadodara(State Gujarat) India ';

				$sign='Swiss PAC PVT LTD';

			}

				

			

			$html .='<div class="width_div"><div style="text-align:center;border: 1px solid black;">FACTURA PROFORMA';

			if($proforma['discount']!='0') { $html.='<span style="float:right;font-size:14px;">'.($proforma['discount'] + 0).'</span>'; }

			$html.='</div>

						<div class="" style=" width: 100%;float: left;  border: 1px solid black;font-size: 18px;">

							<table style="width: 100%;" >

								<tr>

									<td style="vertical-align: top;width: 50%;">';

									$html .='<p><b>'.$title.'<br></p><p><br>'.$address.'<br></p>'.$admin_vat_no.' 

									</td>

									<td style="padding: 0px;vertical-align: top;">

										<table style=" width: 100%;border: 1px solid black; border-spacing: 0px;" cellspacing="0px" cellpadding="10px"  >

											<tbody><tr>

												<td valign="top"><b>Número y Fecha de Proforma</b></td>

												<td>'.$proforma['pro_in_no'].' &nbsp;/&nbsp; '.dateFormat(4,$proforma['invoice_date']).'<br><br><br><span class="barcode" >'.$proforma['pro_in_no'].'</span><br><br></td>

												</tr>

											<tr>

												<td><b>Proforma :</b></td>

												<td>&nbsp;'.dateFormat(4,$proforma['proforma']).'</td>

											</tr>

											<tr>

												<td><b>Número de Pedido y Fecha : </b></td>

												<td>'.$proforma['buyers_order_no'].' &nbsp;/&nbsp; '.dateFormat(4,$proforma['buyers_date']).'</td>

											</tr>

											<tr>

												<td><b>País de Origen del Producto:</b></td>

												<td>'.$proforma['goods_country'].'</td>

											</tr>

											</tbody>

										</table>

									</td>

								</tr>

							</table>

						</div>

						

						<div class="" style="width: 100%; float: left;  border: 1px solid black;font-size: 18px;">

						

							<table style="width: 100%;">

								<tr>

									<td style="vertical-align: top;">

									

										<p><b>Comprador</b></p>

										<p>'.$proforma['customer_name'].'<br/>'.nl2br($proforma['address_info']).'<br/>Email : '.$proforma['email'].'<br>'.$show_vat.'<br>'.$show_qst.'</p>

									</td>';
									
									
									if($proforma['same_as_above']!='1')
									{
												
									
									$html .= '<td style="vertical-align: top;">
									
            									<p><b>Dirección de entrega</b></p>';
            
            									
            									$html .= '	<p>' . $proforma['customer_name'] . '<br/>' . nl2br($proforma['del_address_info']) . '<br/>Email : ' . $proforma['email'] . '<br>' . $show_vat . '<br>' . $show_qst . '</p>';
								            $html .= '  </td>';
									}

									$html .= '<td style="padding: 0px;vertical-align: top;">

										<table cellspacing="0px" cellpadding="0px" style=" border-spacing: 0px; width: 100%;border: 1px solid black;padding: 0px;">

											<tbody><tr>

												<td style="text-align:center" colspan="2"> <b>Forma de Entrega y Pago</b></td>

											</tr>

											<tr>

												<td><b>Entrega:</b></td>

												<td>'.$proforma['delivery_info'].'</td>

											</tr>';

											if($user_name['country_id']!='14')

											{

												$html.='<tr>
	
														<td><b>Forma de Envío:</b></td>
	
														<td>';
														
														if(ucwords(decode($proforma['transportation']))=='By Air')
															$tra = 'No Disponible';
														else
															$tra = 'Disponible';
														
												$html.=$tra;
													
												
												$html.='</td>
												</tr>';

											}

									$html.='<tr>

												<td><b>Forma de Pago:</b></td>

												<td>'.$proforma['payment_terms'].'</td>

											</tr>

									</tbody>

										</table>

										<table style=" border-spacing: 0px; width:100%;border: 1px solid black;"  width: 100%;>

											 <tbody><tr>

												<td><b></b></td>';

												if($user_name['country_id']!='14')

													$html.='<td><b>Destino</b></td>';

												else

													$html.='<td></td>';

										$html.='</tr>';

											 $con_id =$proforma['destination'];

											$countrys = $this->getCountry($con_id);



									$html.='<tr><td></td>';

												if($user_name['country_id']!='14')

													$html.='<td>'.$countrys['country_name'].'</td>';

										$html.='</tr>

											</tbody>

										</table>

									</td>

								</tr>

							</table>

							

						</div>';	

						

						 $currency = $this->getCurrencyId($proforma['currency_id']);

					$html .='<div class="" style="width: 100%; float: left;  border: 1px solid black;">

							<table cellspacing="0px" cellpadding="10px" border="1" style="width: 100%; border-spacing: 0px;font-size: 14px;">

								<tbody>

								<tr>

									<td width="5%"><div align="center"><b>Sr. No</b></div></td>

									<td width="60%"><div align="center"><b>Descripción del Producto ';
									
									if($con_id!=155)
									  $html .='<span style="float: right;">Código HS :  3923 2990</span>';  
									
									$html .='</b></div></td>

									<td width="10%"><b>Cantidad</b></td>

									<td width="15%"><b>Costo Unitario</b></td>

									<td width="10%"><b>Total</b></td>

								</tr>';

								$total = 0;$total_rate=0; $final_total=0; $n=1;$total_excies_rate=0;$ex_per=array();$total_taxation_rate=0;$tax_per=array();$taxa=array();
								$total_igst_rate = 0;
								$total_sgst_rate = 0;
								$total_cgst_rate = 0;

								//[kinjal] : added on [22-8-2016]

								$custom_pro_id = 0;

								foreach($proforma_inv as $invoice_key=>$invoice){

							

									$product_code_data = $this->getProductCode($invoice['product_code_id']);
								
									
									

									if(strrchr($product_code_data['product_code'],'CUST'))

										$custom_pro_id = 1;

										if($product_code_data['valve']=='No Valve')
											$valve_name='Sin Válvula';
										else	
											$valve_name='Con válvula';
									
										$zipper_name=$product_code_data['zipper_name_spanish'];

								

										$spout_name=$product_code_data['spout_name_spanish'];
										
										$acc_name=$product_code_data['product_accessorie_name_spanish'];

									if($invoice['product_code_id']!='-1' && $invoice['product_code_id'] !='0')

										$get_size = $this->getSizeDetail($product_code_data['product'],$product_code_data['zipper'],$product_code_data['volume'],$product_code_data['measurement']);

									else

										$get_size = array('size_master_id'=> '',

															'product_id'=>'',

															'product_zipper_id'=>'',

															'volume'=>'',

															'width'=>'',

															'height'=>'',

															'gusset'=>'',

															'weight'=>'');

										

										if($product_code_data['product'] == 3)

										{

											$gusset = floatval($get_size['gusset']).'+'.floatval($get_size['gusset']);

										}

										else

										{

											$gusset = floatval($get_size['gusset']);

										}

									$measure = $this->getMeasurementName($invoice['measurement']); 

									$html .='<tr><td>'.$n.'</td>';

										

										$clr_text='';
                                       
										if($invoice['product_code_id']=='-1')

										{

											$clr_nm = 'Personalizadas';

											$custom_pro_id = 1;

											$clr_text = "(".$invoice['color_text'].")";

											$p_nm = 'Personalizadas';

											$size_product = '</b> ('.$invoice['size'].' '.$measure['measurement'].')';

										}

										elseif($invoice['product_code_id']=='0')

										{

											$clr_nm = 'Cilindro de Impresión';

											$p_nm = 'Cilindro de Impresión';

											$size_product = '</b> ('.$invoice['size'].' '.$measure['measurement'].')';

										}

										else

										{

											$clr_nm = $product_code_data['color_spanish'];
                                            $p_nm = $product_code_data['product_name_spanish'];
                                            $haystack = strtoupper ($product_code_data['product_code']);
                                            $needle   = strtoupper ("cylinder");
                                            
                                            if( strpos( $haystack, $needle ) !== false ) {
                                                $p_nm = 'Cilindro de Impresión';
                                            }
                                            
										

											$size_product = '</b>'.floatval($get_size['width']).' mm &nbsp;Ancho &nbsp;X&nbsp;'.floatval($get_size['height']).' mm &nbsp;Alto &nbsp;';
                                            
										}
                                        if($product_code_data['width']!=0 && $product_code_data['height']!=0)
                                        {
			                                if($product_code_data['gusset']!=0)
			                                {
			                                	if($product_code_data['product'] == 3)
			                                	{

											        $gusset = floatval($product_code_data['gusset']).'+'.floatval($product_code_data['gusset']);

										        }

        										else
        
        										{
        
        											$gusset = floatval($product_code_data['gusset']).'Fuelle';;
        
        										}
			                                }
			                                else
			                                    $gusset=0;
			                                	$size_product = '</b>' . floatval($product_code_data['width']) . ' mm &nbsp;Width &nbsp;X&nbsp;' . floatval($product_code_data['height']) . ' mm &nbsp;Height &nbsp;';
			                                	
                                        }
				                        
										if($invoice['color_text']!='')
                                                $clr_text = "(".$invoice['color_text'].")";
                                                
                                        //$pro_code = '';
									     $pro_code = '<b>Código de producto :</b> '.$product_code_data['product_code'].'<br>';        
									  
									    if($product_code_data['product'] == 6)
									        $html .='<td>'.$pro_code.'<b>Tamaño : </b>'.floatval($product_code_data['width']).'mm &nbsp; Rollos Ancho';
									    else
									        $html .='<td>'.$pro_code.'<b>Tamaño : '.$size_product;

											if($gusset>0)

												$html .='X&nbsp;'.$gusset.' mm Fuelle';

											if($get_size['volume']>0 && $product_code_data['width']==0 && $product_code_data['height']==0 && $product_code_data['gusset']==0 )

												$html .=' ('.$get_size['volume'].')';

											
                                    if($product_code_data['product'] == 6)
										$html .='<br><b>Tipo de Bolsa : :</b>'.$p_nm.'<b>&nbsp;<br>';
									else
									{
									  if( strpos( $haystack, $needle ) !== false ) 
									  {
									      $html .='<br>';
									  }
									  else
									    $html .='<br><b>Tipo de Bolsa : :</b>'.$p_nm.'<b>&nbsp;'.$zipper_name.'&nbsp;'.$valve_name.' &nbsp;'.$spout_name.' &nbsp;'.$acc_name.'</b><br>';
									}
											
                                    
                                    if($invoice['filling']!='')
									{
										if($invoice['filling']=='Filling from Top')
											$fill = 'Llenado por la parte superior de la bolsa';
										else
											$fill = 'Llenado por medio de la boquilla';
										$html.='<br><b>Opción de Llenado: </b>'.$fill.'<br>';
									}
											
                                    
									 if( strpos( $haystack, $needle ) !== false ) 
									    $html .='<b>'.$clr_text.'<br></b>';
									 else
										$html .='<b>Color : '.$clr_nm.'&nbsp;   '.$clr_text.'<br></b>';
                                       
                                       $des ='';
                                       if($invoice['prodes']!='')
                                            $des =  ' ( '.$invoice['prodes'].' )<br>';
										if($invoice['description']!='')
                                           
										{

											$html .='<b>Material:  </b>'.$invoice['description'];

										}
                                            
                                            
                                        if($invoice['tool_price']!='0')
											$html .='<b>Costo de Herramienta Adicional : </b><br>';
										
									
										$cust = strtoupper ($product_code_data['product_code']);
                                        $code_cust   = strtoupper ("CUST");
										if( strpos( $cust, $code_cust ) !== false )
										{
											if($product_code_data['product'] != 51)
    										{
    											$effect = $this->query("SELECT * FROM printing_effect WHERE printing_effect_id = '".$invoice['printing_option']."'");
    											$html .='<div>';
    											if(isset($effect->row['effect_name_spanish']))
    												$html .='Acabado: '.$effect->row['effect_name_spanish'].'<br>';
    											
    											
    											if($invoice['gusset_printing_option']=='Front & Back  +  No Gusset Printing')
    												$invoice['gusset_printing_option']= 'Frente, Reverso, Fuelle de fondo NO impreso';
    											elseif($invoice['gusset_printing_option']=='Front & Back + Side Gusset Printing')	
    												$invoice['gusset_printing_option']= 'Frente & Reverso + Fuelles Laterales';
    											elseif($invoice['gusset_printing_option']=='Front & Back + Bottom / Side Gusset Printing')
    												$invoice['gusset_printing_option']= 'Frente & Reverso + Fondo / Fuelles Laterales';
    											else
    												$invoice['gusset_printing_option']= 'Frente & Reverso + Fuelle de Fondo Impreso';
    											
    											
    											$html .='Impresión : '.$invoice['gusset_printing_option'].'<br>
    													 Variación: más menos <b>4500</b> bolsas';
    													 
    												if($invoice['destination'] == 155){	 
    													 if($invoice['rate']!=0)
            											   $html .='<br>Entregado en nuestra bodega en CDMX en 14 semanas aprox';
            											if($invoice['express_rate']!=0)
            												$html .='<br>Entregado en nuestra bodega en CDMX en 8 semanas';
    												}
    										
    											
    											$html .='</div>';
    										}
										}
										
									
									$html .='</td><td><br><br><div align="center">';
                                    if($invoice['rate']!=0)
										$total = $total+$invoice['quantity'] ;
										
										$total_qty = $invoice['quantity'];
                                        if($product_code_data['product'] == 6)
									    	$html .=$total_qty.'  Kgs</div><br>';
									    else
										{
											/*if($proforma['destination']==155)
											{*/
												if( strpos( $cust, $code_cust ) !== false )
												{
													if($product_code_data['product'] == 51)
                                                        $html .='<div id="express">'.number_format($total_qty,"0", '.', '');    										        
    										        else
    										        {
    													$html .='<div id="express" style="margin-bottom: -1cm;"  >';
    													if($invoice['rate']!=0)
    													   $html .= '<br>'.number_format($total_qty,"0", '.', '');
    													if($invoice['express_rate']!=0)
    													{
    														$total = $total+$invoice['quantity'] ;
    														$html .='<br>'.number_format($total_qty,"0", '.', '');
    													}
    										        }
													$html .='</div>';
												}
												else
													$html .=number_format($total_qty,"0", '.', '');
												
												$html .='</div><br>';
											/*}
											else
												$html .=number_format($total_qty,"0", '.', '').'</div><br>';*/
										}		

									$html .='</td><td><br><br><div align="right">';

										$total_rate=$total_rate+$invoice['rate'];$total_rt = $invoice['rate'];
                                        
                                        if($product_code_data['product'] == 6)
										    $html .=$total_rt.' Por 1 Kgs</div><br>';
										else
										{
											/*if($proforma['destination']==155)
											{*/
												if( strpos( $cust, $code_cust ) !== false )
												{
													if($product_code_data['product'] == 51)
													    $html .='<div id="express">'.$total_rt;
													else
													{
    													$html .='<div id="express" style="margin-bottom: -1cm;">';
    													
    													if($invoice['rate']!=0)
    													   $html .='<br>'. $total_rt;
    													if($invoice['express_rate']!=0)
    														$html .='<br>'.$invoice['express_rate'];
													}
													$html .='</div>';
												
												}
												else
													$html .=$total_rt;
												
												$html .='</div><br>';
											/*}
											else
												$html .=$total_rt.'</div><br>';*/
										}
										

									

									$html.='</td><td><br><br><div align="right">';

										$total_amnt = $invoice['quantity'] * $invoice['rate'];

										$ex_amt=0;
										/*if($proforma['destination']==155)
										{*/
											if( strpos( $cust, $code_cust ) !== false )
											{
											    if($product_code_data['product'] == 51)
												    $html .='<div id="express" >'.$total_amnt;
												else
												{
    												$html .='<div id="express" style="margin-bottom: -1cm;">';
    												if($invoice['rate']!=0)
    													   $html .='<br>'. $total_amnt;
    												if($invoice['express_rate']!=0)
    												{
    													$html .='<br>'.$invoice['quantity'] * $invoice['express_rate'];
    													$ex_amt = $invoice['quantity'] * $invoice['express_rate'];
    												}
												}
												$html .='</div>';
											}
											else
												$html .=$total_amnt;
											
											$html .='</div><br>';
									/*	}
										else
											$html.= $total_amnt.'</div><br>';*/
										
										 

										$final_total=$final_total+$total_amnt+$ex_amt;
                                        
                                        if($invoice['tool_price']!='0')
    									{
    										$html .='<br><div align="right">'.$invoice['tool_price'].'</div>';
    										$final_total=$final_total+$invoice['tool_price'];
    									}
                                        
                                        
									$html .='</td></tr>';

									$n++;}
                               
                                
								
                               
								/*if($proforma['destination']!=111)

								{*/

									$dis_total=($final_total*$proforma['discount'])/100;

									$final_total=$final_total-$dis_total;

									
                                      if($set_user_id=='10' && $dis_total!=0)
    									{
        									$html.='<tr>
        
        										<td></td>
        
        										<td><div align="right"><strong>Descuento</strong>( '.($proforma['discount'] + 0).' % )</div></td>
        
        										<td>&nbsp;</td>
        
        										<td>&nbsp;</td>
        
        										<td><div align="right">'.round($dis_total,3).'</div></td>
        
        									  </tr>';
    
    									}
									
									  $html.='<tr>

										<td></td>

										<td><div align="right"><strong>Sub Total</strong></div></td>

										<td>&nbsp;</td>

										<td>&nbsp;</td>

										<td><div align="right">'.round($final_total,3).'</div></td>

									  </tr>';

								

								/*}*/
                                if($proforma['freight_charges']!=0)

								{

									$freight_charges=round($proforma['freight_charges'],3);

									
									$final_total=$final_total+$freight_charges;

									

									$html .='<tr>

											<td></td>

												<td><div align="right">

														<strong>Costo de Envío </strong>

														</div></td>

													 <td><b></b></td>

												<td></td>

												<td><p align="right">'.$freight_charges.'</p></td>

										  </tr>';

								}

								else

								{
                                    	
									$final_total=$final_total;

								}
								$gst = 0;
									 
								if($proforma['destination']!='111' && $proforma['destination']!='42'&& $proforma['destination']!='112' )

								{

									$gst = (($final_total*$proforma['gst_tax'])/100);

									$html.='<tr>

										<td></td>

										<td><div align="right"><strong>IVA ('.$proforma['gst_tax'].') % </strong></div></td>

										<td>&nbsp;</td>

										<td>&nbsp;</td>';
										
                                        $html.='<td><div align="right">'.$gst.'</div></td>';
                                            
									  $html.='</tr>';

								}

								$tax_price = 0;


								$Total_price = $gst+$final_total+$tax_price;
                                
								$html.='<tr>

									<td></td>

									<td></td>

									<td>&nbsp;</td>

									<td>&nbsp;</td>

									<td>&nbsp;</td>

								  </tr><tr>

									<td></td>

									<td></td>

									<td><p align="center"><b>'.$total.'</b></p></td>

									<td><p align="right">Total('.$currency['currency_code'].')</p></td>';
                                            
                                    if($proforma['destination']=='14' || $proforma['destination']=='155'){
                                        $html.='<td><p align="right"><b>'.number_format ($gst+$final_total+$tax_price,2).'</b></p></td>';    
                                   } else{
									    $html.='<td><p align="right"><b>'.round ($gst+$final_total+$tax_price,3).'</b></p></td>';
                                   }
								  $html.='</tr>';

							$html .='</tbody>

							</table>

						</div>';

						

						if(isset($Total_price) && $Total_price!=0){ 

 							$number = $this->convert_number(round($Total_price));

							
 						} else{

 	 						$number = $this->convert_number(round($final_total));

							
  						}

  						

										

			$html .='<div  class="" style=" width: 100%;float: left;  border: 1px solid black;font-size: 16px;">

							<table cellspacing="0px" cellpadding="10px" border="1" style=" width: 100%; border-spacing: 0px;">

								<tbody>';
								
							if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']!=155)
							{
    					        $html .='<tr>
                
                        		            <td colspan="2" valign="top"><strong>Amount Chargeable(In Words): '.$number.' {'.$currency['currency_code'].'}</strong></td>
                        
                        	        </tr>';
							}
								$html .='

    	<tr>';
    		
    		$html .=' <td valign="top" width="50%"><div><strong>Nota:</strong></br>Declaramos que el valor de esta factura muestra el precio real de los productos descritos y que las caracteristicas con reales y correctas</br>';
    				
    					
                		
                		$html .='</div>
    		    </td>';
    		    
                
        	$html .='<td valign="top" class="sign_td">
        
            		<table border="0" align="right"  cellspacing="0px" cellpadding="0px" style="width: 100%;border-spacing: 0px;" >
            
                		<tr>
                
                			<td width="50%"><br>For <strong>';
                    			if(isset($set_user_id) && !empty($set_user_id)&&  $set_user_id=='10' &&  $proforma['gen_pro_as']=='2')
                    			    $html .='Swiss PAC PVT LTD';
                    			else
                    			    $html.=$sign;
                    			    
                    			 $html.='</strong><br>
                    
                    			<p style="text-align:right;margin-top:20px;margin-bottom:0;padding:0px;">'.$user_name['first_name'].' '.$user_name['last_name'].'</p><hr/>
                    
                    				<p id="prefix" style="text-align:right;float:right;" >Authorised Signature</p>
                
                			</td>
                
                		</tr> 
            
            	    </table>
                </td>
    
    	</tr>';
    	foreach($proforma_inv as $pro_inv)
        		{
        			$string1=$pro_inv['product_code'];					
        			 $remark= substr($string1,0,4);
        		}
        		
        		if($remark=='CUST'){
        		        $html .='<tr><td width="100%" colspan="2"><b>*Más Menos (+ -) :</b> Por favor note que en la producción comercial pueden existir variaciones en la producción final. En dicho caso reembolsamos su dinero si existe una diferencia donde las  bolsas sean menos a la cantidad de su pedido. Sin embargo, en situaciones en las  que la producción es mayor le solicitamos muy comedidamente que usted pague por el volumen adicional. En dicho caso si su pedido inicial es de 10.000 bolsas la producción final puede variar entre 14.500 bolsas y 6.500.<br>
        		                      <b>*Cilindros de Impresión* :</b> Para las bolsas con impresión personalizada hay un costo adicional la primera vez, este costo equivale al costo de los cilindros de impresión. Se necesita un cilindro por cadacolor que este incluido en su diseño. Así que si su diseño tiene 5 colores entonces se necesitarán 5 cilindros para la impresión de sus bolsas. El costo del cilindro depende del tamaño de la bolsa. El costo de cada cilindro varía de 4500 a 6000 pesos o incluso más. Por cada tamaño de .bolsa que usted requiera se necesita juegos de cilindros diferentes ya que un juego no es ajustable a todos los tamaños.
        		            <td></tr>';
        		}
	
	$html .='</tbody></table></div>';

			

$html .='<div class="" style=" width: 100%;float: left;  border: 1px solid black;page-break-before: always;font-size: 16px;">

							<table cellspacing="0px" cellpadding="10px" border="1" style=" width:100%;">

								<tbody><tr>

									<td valign="top" colspan="2"><h1 align="center">Datos Bancarios</h1></td>

								</tr>

								<tr>

	 								<td colspan="2"><b>'.$currency['currency_code'].'</b></td>

								</tr>

								<tr>

									<td><b>Nombre del Beneficiario</b></td>

									<td >'.$proforma['bank_accnt'].'</td>

								</tr>

								
								<tr>

									<td><b>Banco Beneficiario</b></td>

									<td>'.$proforma['benefry_bank_name'].'</td>

								</tr>

								';

								$html .='<tr>

											<td><b>Número de la Cuenta</b></td>

											<td>'.$proforma['accnt_no'].'</td>

										</tr>';


								//$html .='</tr>';
								
								
								

								

						if($currency['currency_code']=='MXN'){ 

						$html .='<tr>

									<td><b>Clabe</b></td>

									<td>'.$proforma['clabe'].'</td>

								</tr>';

						}

						
					$html .='</tbody></table></div>';
                    //if($remark!='CUST')	
					    $html .='<div style=" border: 1px solid black;font-size: 16px;">'.$user['termsandconditions_invoice'].'<div>';
							
					
				$html .="</div>";

		//printr($html);die;

		return $html ;

	}
	public function getPermissionForEdit($user_id,$user_type_id,$proforma_id)
	{
	   
	   	    if($_SESSION['LOGIN_USER_TYPE']==1 && $_SESSION['ADMIN_LOGIN_SWISS']==1)

			{

				$sql = "SELECT * FROM " . DB_PREFIX . "proforma_product_code_wise WHERE is_delete = '0'  " ;

				$num = 1;
                $data = $this->query($sql);
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

    			 
	            if($set_user_id=='37' || $set_user_id=='38')
	            {
    	           $sql = "SELECT * FROM " . DB_PREFIX . "proforma_product_code_wise WHERE is_delete = '0' AND added_by_user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND added_by_user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."' AND proforma_id='".$proforma_id."' " ;
    	          
    	           $data = $this->query($sql);
    	          // printr($sql);
    	           if($data->num_rows)
    	           {
    	               
    	               $num = 0;
    	           }
    	           else
	               {
	                    $num = 1;
	               }
	            }
	            
			}
        
        
    		return $num;
    
    
	}
    public function getLastIdPacking() {
        $sql = "SELECT packing_order_id FROM  packing_order ORDER BY packing_order_id DESC LIMIT 1";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row['packing_order_id'];
        } else {
            return false;
        }
    }
    public function gotopaid($proforma_id)
    {
        	$proforma=$this->getProformaData($proforma_id);
            $proforma_inv=$this->getProformaInvoice($proforma_id);
            
            if($proforma['gen_pro_as']==2)//swiss
            {
                $data = $this->query("SELECT `ref_order_no`as max_num FROM packing_order WHERE`is_delete`=0 AND ref_order_no LIKE 'INT%' ORDER BY `packing_order_id` DESC LIMIT 1");
                $num = preg_replace_callback("|(\d+)|", function($matches){ $length = '3'; return sprintf("%0".$length."d", ++$matches[1]);}, $data->row['max_num']);;
            }
            else //clifton
            {
                $data = $this->query("SELECT MAX(CAST(`ref_order_no` as SIGNED)) as max_num FROM packing_order WHERE`is_delete`=0 ");
                $num = $data->row['max_num']+1;
            }
           
           
            
           
            $data1 = $this->query("SELECT packing_order_id FROM `" . DB_PREFIX . "packing_order` WHERE is_delete = '0' ORDER BY packing_order_id DESC");
            $strpad = str_pad($data1->row['packing_order_id']+1,8,'0',STR_PAD_LEFT) ;
            $o_num = 'PACK'.$strpad;
			
			$user=$this->query('INSERT INTO packing_order SET order_no="'.$o_num.'",pro_in_no ="'.$proforma['pro_in_no'].'",cust_nm="'.stripslashes($proforma['customer_name']).'", email="'.$proforma['email'].'", rfc_no="'.$proforma['vat_no'].'",freight_charges="'.$proforma['freight_charges'].'",user_id="' .$proforma['added_by_user_id']. '",user_type_id="' .$proforma['added_by_user_type_id']. '",ref_order_no = "' .$num.'", order_date = NOW(),	payment_amount = "'.$proforma['invoice_total'].'",date_added=now(),date_modify=now(),delivery_address="'.addslashes($proforma['delivery_info']).'",billing_order_address="'.addslashes($proforma['address_info']).'",destination="'.$proforma['destination'].'",gen_pro_as ="'.$proforma['gen_pro_as'].'"');
			
			$packing_order_id = $this->getLastIdPacking();			
			foreach($proforma_inv as $pro)
			{
			    $this->query("INSERT INTO packing_order_product_code_wise SET packing_order_id = '".$packing_order_id."',added_by_user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."', added_by_user_type_id = '". $_SESSION['LOGIN_USER_TYPE'] ."', product_code_id = '".$pro['product_code_id']."', product_name = '".$pro['product_name']."',pedimento_mexico='".$pro['pedimento_mexico']."',description = '".addslashes($pro['description'])."',quantity = '".$pro['quantity']."',sales_qty='".$pro['quantity'] ."',rate = '".$pro['rate']."',express_rate='".$pro['express_rate']."',color_text = '".addslashes($pro['color_text'])."',  size = '".$pro['size']."', date_added = NOW(), date_modify = NOW(), is_delete = 0,filling='".$pro['filling']."',tool_price='".$pro['tool_price']."'");
  			}
  			
  			$this->query("UPDATE proforma_product_code_wise SET paid_status=1 WHERE proforma_id='".$proforma_id."'");
  		
    }
	public function getQuantityById($quantity_id){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."product_quantity WHERE product_quantity_id = '".$quantity_id."' LIMIT 1");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getActivePrintingEffectEnquiry(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "printing_effect` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY effect_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}	

	//[kinjal] made on (28-2-2018)
	public function productweight($product_code_id){
	//	echo "SELECT pw.weight FROM " . DB_PREFIX ."product_code as pc, pro_color_category as pcc,proforma_weight_list as pw,template_measurement as t  WHERE FIND_IN_SET(pc.color,pcc.color) AND pc.product_code_id = '".$product_code_id."' AND pc.product = pw.product_id AND pw.category_id = pcc.color_catagory_id AND t.product_id=pc.measurement AND pw.volume =CONCAT(pc.volume,' ',t.measurement) AND pc.zipper = pw.zipper_id LIMIT 1";

		$data = $this->query("SELECT pw.weight FROM " . DB_PREFIX ."product_code as pc, pro_color_category as pcc,proforma_weight_list as pw,template_measurement as t  WHERE FIND_IN_SET(pc.color,pcc.color) AND pc.product_code_id = '".$product_code_id."' AND pc.product = pw.product_id AND pw.category_id = pcc.color_catagory_id AND t.product_id=pc.measurement AND pw.volume =CONCAT(pc.volume,' ',t.measurement) AND pc.zipper = pw.zipper_id LIMIT 1");
		if($data->num_rows){
			return $data->row['weight'];
		}else{
			return false;
		}
	}
	//END [kinjal]
	//add sonu(03-05-2018)
	
	public function updatefreight($freight_char,$proforma_id){
	    
	   	$up_sql="UPDATE proforma_product_code_wise SET freight_charges='".$freight_char."' WHERE proforma_id=".$proforma_id;//
         $this->query($up_sql);
	}
    //[kinjal] made on (3-5-2018)
	public function getDigitalPrice($product_code_id,$quantity,$plate,$rate,$country_id){
		$sql1="SELECT pz.zipper_name,pc.valve,pm.volume,pc.color,pc.product FROM product_code as pc, pouch_volume as pm,product_zipper as pz WHERE pm.volume = (Select CONCAT(p.volume,' ',m.measurement) as volume from product_code as p, template_measurement as m WHERE p.measurement=m.product_id AND p.product_code_id='".$product_code_id."') AND pc.product_code_id='".$product_code_id."' AND pc.zipper=pz.product_zipper_id";
		$data1 = $this->query($sql1);
		$price=$rate;
		
		if($data1->num_rows)
		{    
			$sql2 = "SELECT * FROM `" . DB_PREFIX . "pouch_color` WHERE status='1' AND pouch_color_id IN(67,68,69,66,74,75) AND is_delete = '0' AND color_value = '".$plate."'";
			$data2 = $this->query($sql2);
			
			$sql = "SELECT pts.*,p.product_name,p.product_id,pt.user,pt.country,pt.digital_template_id FROM " . DB_PREFIX . "digital_template_size pts,product p,digital_template as
pt  WHERE  pt.product_name='".$data1->row['product']."' AND  pts.template_id=pt.digital_template_id AND pt.product_name=p.product_id AND pts.is_delete = '0' AND pt.status='0' AND REPLACE(pts.volume, ' ', '') = REPLACE('".$data1->row['volume']."', ' ', '') AND pts.color LIKE '%".$data2->row['pouch_color_id']."%' AND  pt.country LIKE '%".$country_id."%'";	

			$data = $this->query($sql);
			//print_r($data->row);die;
			$result = $data->rows;
			if($quantity < 200)
			{   //echo 'kinjal';
				$ink_qty_colname = 'quantity200';
			}
			else if($quantity >= 200 && $quantity < 500)
			{
				$ink_qty_colname = 'quantity200'; 
			} 
			else if($quantity >= 500 && $quantity < 1000)
			{
				$ink_qty_colname = 'quantity500'; 
			}
			else if($quantity >= 1000 && $quantity < 2000)
			{
				$ink_qty_colname = 'quantity1000';
			}
			else if($quantity >= 2000 && $quantity < 5000)
			{
				$ink_qty_colname = 'quantity1000';
			}
			else if($quantity >= 5000 && $quantity < 10000)
			{
				$ink_qty_colname = 'quantity1000';
			}
			else
			{
				$ink_qty_colname = 'quantity1000';
			}
			$price=$rate+$data->row[$ink_qty_colname];
			
		}
		return $price;
		
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
	public function getLastBuyerOrderNo() {
	    
	      if($_SESSION['LOGIN_USER_TYPE'] == 2){
    
    				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
    
    				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
    
    			
    				$set_user_id = $parentdata->row['user_id'];
    
    				$set_user_type_id = $parentdata->row['user_type_id'];
                    
                    
    			}else{
    
    				$userEmployee = $this->getUserEmployeeIds($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
    
    				$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
    
    				$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
    
    			} 
    
    			$str =$userEmployeeother =$other_id='';
                 if($set_user_id==33)
			        $other_id='24';
			    else 
			        $other_id='33';
			        
			  $userEmployeeother = $this->getUserEmployeeIds(4,$other_id); 
			  
    			if($userEmployee){ 
     
    				$str = ' OR ( added_by_user_id IN ('.$userEmployee.') AND added_by_user_type_id = 2 )';
    
    			} 
    			
    //	printr($set_user_id);
    		if($set_user_id==33 || $set_user_id==24){
                $no='(`buyers_order_no`) as  buyers_order_no'; 
                $usr_text="(added_by_user_id IN (24,33) AND added_by_user_type_id=4  $str) ";
                $str = ' OR ( added_by_user_id IN ('.$userEmployee.','.$userEmployeeother.') AND added_by_user_type_id = 2 )';	//printr('if');
    		}
            else{
                
                 $no=' MAX(CAST(`buyers_order_no` as SIGNED)) as buyers_order_no'; 
                 $usr_text="(added_by_user_id = ".$set_user_id." AND added_by_user_type_id=4 $str) ";
                 //printr('else');
            }
                
        $sql = "SELECT $no FROM  proforma_product_code_wise  WHERE is_delete=0 AND $usr_text ORDER BY proforma_id DESC LIMIT 1";
     //echo $sql;//die;
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row['buyers_order_no'];
        } else {
            return false;
        }
        
        
    }
    
    /*public function getLastBuyerOrderNo() {
	    
	      if($_SESSION['LOGIN_USER_TYPE'] == 2){
    
    				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
    
    				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
    
    				$set_user_id = $parentdata->row['user_id'];
    
    				$set_user_type_id = $parentdata->row['user_type_id'];
    
    			}else{
    
    				$userEmployee = $this->getUserEmployeeIds($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
    
    				$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
    
    				$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
    
    			} 
    
    			$str = '';
    
    			if($userEmployee){
    
    				$str = ' OR ( added_by_user_id IN ('.$userEmployee.') AND added_by_user_type_id = 2 )';
    
    			}
           
        $sql = "SELECT MAX(CAST(`buyers_order_no` as SIGNED)) as buyers_order_no FROM  proforma_product_code_wise  WHERE is_delete=0 AND  (added_by_user_id = ".$set_user_id." AND added_by_user_type_id=".$set_user_type_id." $str) ORDER BY proforma_id DESC LIMIT 1";
     //  echo $sql; die;
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row['buyers_order_no'];
        } else {
            return false;
        }
        
        
    }*/
    
    
    public function getcalculatePlusMinusQuantity($quantity,$product_id,$height,$width,$gusset,$type,$qty_type=''){
		$product = $this->getProduct($product_id);
		$productGusset = explode(',',$product['gusset']);
		$size = 0;
		if(!empty($productGusset)){
			if(in_array('no_gusset_height',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$size = ($height * $width);		
			}
			
			elseif(in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_height',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$size = ($height * $width);
			}
			elseif(in_array('side_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$size = ((($gusset*2) + $height)* $width); 
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset))
			{
				$size = ((($gusset*1)+$height)*$width);
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && in_array('side_gusset',$productGusset))
			{
				$size = ((($gusset*3) + $height) * $width);
			}
		}
		if($qty_type=='')
		{
    		$qunatityRow = $this->query("SELECT product_quantity_id FROM " . DB_PREFIX . "product_quantity WHERE quantity = '".$quantity."'");
		    $quantity_id = $qunatityRow->row['product_quantity_id'];
		    $data = $this->query("SELECT plus_minus_quantity	FROM " . DB_PREFIX . "product_profit WHERE product_id ='".$product_id."' AND 	quantity_id = '".$quantity_id."' AND size_from <= '".$size."' AND 	size_to >= '".$size."'");
		}
		else
		{
		    $data = $this->query("SELECT plus_minus_quantity FROM " . DB_PREFIX . "roll_quantity WHERE quantity = '".$quantity."' AND quantity_type = '".$qty_type."'");
		}

		if($data->num_rows){
			return $data->row['plus_minus_quantity'];
		}else{
			return false;
		}
	}
	public function gettotaldisSalesInvList()
	{
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "sales_invoice` WHERE status='1' AND rack_notify_status=0 AND is_delete=0 AND user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id='".$_SESSION['LOGIN_USER_TYPE']."' AND gen_status='0' AND date_added >='2017-01-02' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['total'];
		}else{
			return false;
		}
	}
	
	public function viewProformaPaymentDetail($proforma_id){
	    
			$html ='';

			$proforma=$this->getProformaData($proforma_id);

		
            
			$proforma_id=$proforma['proforma_id'];

			$proforma_inv=$this->getProformaInvoice($proforma_id);

			$user_name=$this->getUser($proforma['added_by_user_id'], $proforma['added_by_user_type_id']);
			$show_vat='';
			$show_qst='';
			$qst_no = '';
			$admin_vat_no='';
			if($proforma['added_by_user_id'] == '1' && $proforma['added_by_user_type_id'] =='1')

			{
				$image= HTTP_UPLOAD."admin/store_logo/logo.png";
				$img = '<img src="'.$image.'" alt="Image">';
			}
			else
			{
    				if($proforma['added_by_user_type_id'] == 2){
    
    					$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$proforma['added_by_user_id']."' ");
    
    					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
    
    					$set_user_id = $parentdata->row['user_id'];
    					$set_user_type_id = $parentdata->row['user_type_id'];
    				}else{
    					$userEmployee = $this->getUserEmployeeIds($proforma['added_by_user_type_id'],$proforma['added_by_user_id']);
    					$set_user_id = $proforma['added_by_user_id'];
    					$set_user_type_id = $proforma['added_by_user_type_id'];
    				}
    				$user_info=$this->getUser($set_user_id,'4');
    				$data=$this->query("SELECT logo,abn_no,termsandconditions_invoice,note_invoice,bank_address FROM international_branch WHERE international_branch_id = '".$set_user_id."'");
    
    				if(isset($data->row['logo']) &&  $data->row['logo']!= '')
    
    				{
    
    					$image= HTTP_UPLOAD."admin/logo/200_".$data->row['logo'];
    
    					//echo $image;
                        if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39'))
    					    $img = '<img src="'.$image.'" alt="Image" width="65%">';
                        else
                            $img = '<img src="'.$image.'" alt="Image">';
    				}
    
    				else
    
    				{
    					$img ='';
    				}	

			}
            if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='37' || $set_user_id=='38' || $set_user_id=='39' ))
            {
                $title='Consignor';
                $user=$this->getCompanyAdd($proforma['added_by_user_type_id'],$proforma['added_by_user_id']);
                $address=nl2br($user['company_address']);
                $sign=$user['company_name'];
                $admin_vat_no = 'GST No. : '.$user['vat_no'];
                $vat_no = $proforma['vat_no'];
                $show_vat = 'GST No. :'.$vat_no;
            }
            else
            {
    			if($proforma['destination']==111)
    			{
    				$title='Consignor';
    				$address='SWISS PAC PVT LTD</b><br>Padra Jambusar National highway
    				<br>At Dabhasa village,Pin 391440<br>Taluka.Padra, Dist.Vadodara(State Gujarat) India ';
    				$sign='Swiss PAC PVT LTD';
    				$user=$this->getCompanyAdd($proforma['added_by_user_type_id'],$proforma['added_by_user_id']);
    				$vat_no = $proforma['vat_no'];
    				$admin_vat_no = 'GST No. : '.$user['vat_no'];
    				$show_vat = 'GST No. :'.$vat_no;
    			}
    			else
    			{
    				if($proforma['added_by_user_id']!='1' && $proforma['added_by_user_type_id'] != '1')
    				{	
    				    $title='From';
    					$user=$this->getCompanyAdd($proforma['added_by_user_type_id'],$proforma['added_by_user_id']);
    					$address=nl2br($user['company_address']);
    					    $sign=$user['company_name'];
    					if($proforma['destination']==155)
    						$admin_vat_no = 'RFC No. : '.$user['vat_no'];
    					else if( isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='19'))
    					    $admin_vat_no = 'TRN  :'.$user['vat_no'];
                        else if( isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='44')){
    					    //$admin_vat_no = 'GST/HSTNo.  : '.$user['vat_no'];
    					    //printr($proforma);
    					    if($proforma['state']==11){
        					     $admin_vat_no = 'GST/HSTNo.  : '.$user['vat_no'].'<br> QST No. : 1224569528TQ0001';
    					    }else{
    					        $admin_vat_no = 'GST/HSTNo.  : '.$user['vat_no'];
    					    }
                        }
    					else
    					    $admin_vat_no = 'Vat No. : '.$user['vat_no'];
    					$vat_no = $proforma['vat_no'];
    
    					if($proforma['destination']==155)
    					{
    						$show_vat = 'RFC No. :'.$vat_no;
    					}
    					elseif($proforma['destination']==42)
    					{
    						$show_vat = 'Gst No. :'.$vat_no;
    						if($proforma['qst_no']==0)
    							$show_qst = '';
    						else
    							$show_qst = 'Qst No. :'.$proforma['qst_no'];
    					}
    				
    					else
    					{
    						$show_vat = 'Vat No. :'.$vat_no;
    					}
    			
    					if($user_info['country_id']==155)
    					{
    					   if($proforma['gen_pro_as']=='1')
    					        $address=nl2br($user['company_address']);
    					   else
    					   {
    					        $address='SWISS PAC PVT LTD</b><br>Padra Jambusar National highway<br>At Dabhasa village,Pin 391440<br>Taluka.Padra, Dist.Vadodara(State Gujarat) India ';
    					        $admin_vat_no = 'Vat No. : 24AADCS2724B1ZY';
    					       
    					   }
    					}
    					
    					if($user_info['country_id']==11)
    					{//printr($proforma['gen_pro_as']);die;
    					   if($proforma['gen_pro_as']=='1')
    					        $address=nl2br($user['company_address']);
    					   else
    					   {
    					        $address='SWISS PAC PVT LTD</b><br>Padra Jambusar National highway<br>At Dabhasa village,Pin 391440<br>Taluka.Padra, Dist.Vadodara(State Gujarat) India ';
    					        $admin_vat_no = 'GST No. : 24AADCS2724B1ZY';
    					       
    					   }
    					}
    				}
    
    				else
    				{
    					$title='Consignor';
    					$address='SWISS PAC PVT LTD</b><br>Padra Jambusar National highway
    					<br>At Dabhasa village,Pin 391440<br>Taluka.Padra, Dist.Vadodara(State Gujarat) India ';
    					$sign='Swiss PAC PVT LTD';
    				}
    
    				
    
    			} 
            }
            if($user_info['country_id']==11)
    		{
    		    $show_vat='';
    		}
         
			$html .='<div class="width_div"><div style="text-align:center;border: 1px solid black;"><b>Payment Details</b>';

			if($proforma['discount']!='0') { 
			    $html.='<span style="float:right;font-size:14px;">'.($proforma['discount'] + 0).'</span>';
			}
			
			$html.='</div>
					
						<div class="" style=" width: 100%;float: left;  border: 1px solid black;font-size: 12px;">

							<table style="width: 100%;" >

								<tr>

									<td style="vertical-align: top;width: 50%;">';
                                        $Consignee ='Consignee';$Delivery_add ='Delivery Address';
										if($user_name['country_id']=='14')

											$html .=$img.'<br><br>';

										if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='37' || $set_user_id=='38'))
										{
										    $html .=$img.'<br><br>';
										    $html .='<p><b>'.$title.'<br></p><p>'.$address.'<br></p>'.$admin_vat_no.'</b>';
										    
										    $Consignee = 'Buyer'; $Delivery_add='Consignee & Delivery Address';
										}
										elseif (isset($set_user_id) && !empty($set_user_id) && $set_user_id=='39' || isset($set_user_id) && !empty($set_user_id) && $set_user_id=='44')
										{
										    $html .=$img.'<br><br>';
										    $html .='<p><b>'.$title.'</b><br></p><p>'.$address.'<br></p>'.$admin_vat_no;
										}
										else
										{
									        $html .='<p><b>'.$title.'<br></p><p>'.$address.'<br></p>';
									        
									       $html .=$admin_vat_no; 
									      
									           
									       $html .='</b>';
										}
								$html .='	</td>

									<td style="padding: 0px;vertical-align: top;">

										<table style=" width: 100%;border: 1px solid black; border-spacing: 0px;" cellspacing="0px" cellpadding="10px"  >

											<tbody><tr>

												<td valign="top"><b>Invoice No.&amp; Date</b></td>

												<td>'.$proforma['pro_in_no'].' &nbsp;/&nbsp; '.dateFormat(4,$proforma['invoice_date']).'<br><br><br><span class="barcode" >'.$proforma['pro_in_no'].'</span><br><br></td>

												</tr>

											<tr>

												<td><b>Proforma :</b></td>

												<td>&nbsp;'.dateFormat(4,$proforma['proforma']).'</td>

											</tr>

											<tr>

												<td><b>Buyers Order No. &amp; Date:</b></td>

												<td>'.$proforma['buyers_order_no'].' &nbsp;/&nbsp; '.dateFormat(4,$proforma['buyers_date']).'</td>

											</tr>

											<tr>

												<td><b>Country of origin of goods:</b></td>

												<td>'.$proforma['goods_country'].'</td>

											</tr>

											</tbody>

										</table>

									</td>

								</tr>

							</table>

						</div>

						

						<div class="" style="width: 100%; float: left;  border: 1px solid black;font-size: 12px;">

						

							<table style="width: 100%;">

								<tr>

									<td style="vertical-align: top;" colspan="2">

										<p><b>'.$Consignee.'</b></p>
										<p><b>'.$proforma['customer_name'].'</b><br/>'.nl2br($proforma['address_info']).'<br/>Email : '.$proforma['email'].'<br>Contact No. : ' . $proforma['contact_no'] . '<br>'.$show_vat.'<br>'.$show_qst.'</p>
									</td>';
							$html .= '</tr>
							</table>
						</div>';
						

					$currency = $this->getCurrencyId($proforma['currency_id']);
					$payment_data = $this->Payment_detail_customer($proforma_id);
					$html .='<div class="" style="width: 100%; float: left;  border: 1px solid black;">
							<table cellspacing="0px" cellpadding="10px" border="1" style="width: 100%; border-spacing: 0px;font-size: 12px;">
								<tbody><tr>
											
												<td><b>Mode Of Payment</b></td>
												<td><b>Payment Type</b></td>
												<td><b>Payment Receipt No</b></td>
												<td><b>Date of Payment Receipt</b></td>
												<td></td>
												<td><b>Amount</b></td>
										</tr>';
								    if(!empty($payment_data)){
								        $srno=1;$subtotal=$amount_due=0;
								        foreach($payment_data as $payment){
								           $html.=' <tr>
											        	
        												<td>'.$payment['payment_mode'].'</td>
														<td>'.$payment['payment_type'].'</td>
														<td>'.$payment['neft'].'</td>
														<td>'. dateFormat (4,$payment['payment_receive_date']).'</td>
														<td></td>
														<td>'. $payment['payment_amount'].'</td>
        									    	</tr>'; 
								            $srno++;
								            $subtotal=$subtotal+$payment['payment_amount'];
								            
								        }
								         $html.=' <tr>
											        	<td colspan="4"></td>
														<td><b>Subtotal</b></td>
														<td>'.number_format($subtotal,3).'</td>
        									    	</tr>'; 
        							    $html.=' <tr>
											        	<td colspan="4"></td>
														<td><b>Invoice Total Amount '.$currency['currency_code'].'</b></td>
														<td>'.$proforma['invoice_total'].'</td>
        									    	</tr>'; 
    								   $html.=' <tr >
										        	<td colspan="4"></td>
													<td style="border-bottom: 3px solid black;"><b>Advance Received '.$currency['currency_code'].' </b></td>
													<td style="border-bottom: 3px solid black;">'.number_format($subtotal,3).'</td>
    									    	</tr>';
    									   $amount_due=$proforma['invoice_total']-$subtotal;
    								  $html.=' <tr>
										        	<td colspan="4"></td>
													<td><b>Amount Due</b></td> 
													<td>'.number_format(($proforma['invoice_total']-$subtotal),3).'</td>
    									      </tr>'; 
								    }
								     
								    
							$html .='</tbody> 
							</table>
				    	</div>';
						
					$html .='<div class="" style="width: 100%; float: left;  border: 1px solid black;">
							 <table cellspacing="0px" cellpadding="10px" border="1" style="width: 100%; border-spacing: 0px;font-size: 12px;">
								<tbody>
    					        <td style="vertical-align: top;"  colspan="2">';
    							    $html.=nl2br($data->row['bank_address']);
    							    $html.='<br> Customer Reference Number : '.$proforma_id;
    							    
                             $html .='</td>
                         </tr>
                         </tbody></table></div>';
                	$html.='<div class="" style="width: 100%; float: left;  border: 1px solid black;">
							<table cellspacing="0px" cellpadding="10px" border="1" style="width: 100%; border-spacing: 0px;font-size: 12px;">
							<tbody>
				  
				             <td style="vertical-align: top;"><b>';
							         $html.=($address);
                                $html .='</b>
                              </td>';
				
						  
    				$html.='<td>
    				            <p>Amount Due &nbsp;&nbsp;'.$this->numberFormate($amount_due,3).'</p><br>';
    					         $html.='<p>Due Date &nbsp;&nbsp; <br>';
    					         $html.=' <p>	Amount Enclosed	<p style="border-bottom: 3px solid black;"></p>
    						  	<p>Enter the amount you are paying above</p>
    				    </td>
		        	</tr></tbody>';
           $html .='</table></div>';
	   	return $html ;

	  
	}
	public function Payment_detail_customer($proforma_id){
		 $sql = "SELECT * FROM `proforma_payment_detail` as pd ,proforma_product_code_wise as p  WHERE  pd.proforma_id= '" . $proforma_id . "' AND pd.proforma_id = p.proforma_id  AND p.is_delete = '0' AND pd.is_delete='0'";
		 $data = $this->query($sql);
		   if ($data->num_rows) {
			return $data->rows;
			} else {
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
	//	$ib=explode('=',$ib_id);
		$userEmployee = $this->getUserEmployeeIds('4', $ib_id);
		$cond='';
		if($ib_id=='6' || $ib_id=='37' || $ib_id=='38' || $ib_id=='39')
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
	public function getreport($post,$n=0)
	{
        
        $date = " AND p.payment_receive_date >= '".$post['f_date']."' AND p.payment_receive_date <='".$post['t_date']."' ";
        $user_ids=$html=$user_name=$excel='';
        if($post['emp_name']!='')
		{
		    $user = explode("=",$post['emp_name']);
		    $user_ids=" AND pi.added_by_user_id = '".$user[1]."' AND pi.added_by_user_type_id = '".$user[0]."'";
		    $user_ids_kath=" AND po.user_id = '".$user[1]."' AND po.user_type_id = '".$user[0]."'";
		    $user_name = $this->getUser($user[1],$user[0]);
		    $user_name['name'] = $user_name['name'].'&#39s';
		    $ib_user_id= $user_name['user_id'];
		}
		else
		{   $str =$str_kath = ''; 
		    if($_SESSION['LOGIN_USER_TYPE'] == 1){
		        $ib_user_id=$set_user_id=$post['user_name'];
		            $userEmployee = $this->getUserEmployeeIds(4,$ib_user_id);
		    }else{
		        if($_SESSION['LOGIN_USER_TYPE'] == 2){
    
    				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
    
    				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id'],$permission);
    
    				$set_user_id = $parentdata->row['user_id'];
    
    				$set_user_type_id = $parentdata->row['user_type_id'];
    
    			}else{
    
    				$userEmployee = $this->getUserEmployeeIds($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
    
    				$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
    
    				$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
    
    			}
    		 } 
		    if($userEmployee){
                    $str = ' OR ( pi.added_by_user_id IN ('.$userEmployee.') AND pi.added_by_user_type_id = 2 )';
                    $str_kath = ' OR ( po.user_id IN ('.$userEmployee.') AND po.user_type_id = 2 )';
                }
                $user_ids=" AND (pi.added_by_user_id = ".$set_user_id." && pi.added_by_user_type_id='4' ".$str.")";
                $user_ids_kath=" AND (po.user_id = ".$set_user_id." && po.user_type_id='4' ".$str_kath.")";
                $user_name = $this->getUser($set_user_id,'4');$user_name['name']='';
                $ib_user_id= $set_user_id;

		}
	    if($ib_user_id=='10')
		   $sql="SELECT *,po.date_added as date FROM  packing_order as po ,account_master as am,proforma_product_code_wise as pp  WHERE  am.user_id=po.user_id AND am.user_type_id=po.user_type_id AND po.packing_order_id!=0 AND po.is_delete=0 AND po.order_date >= '".$post['f_date']."' AND po.order_date <='".$post['t_date']."' $user_ids_kath AND po.pro_in_no=pp.pro_in_no AND pp.gen_pro_as=1 ORDER BY po.order_date,po.packing_order_id ASC";
    	else
    	   $sql="SELECT pi.email,pi.customer_name as cust_nm,pi.proforma_id,pi.pro_in_no,pi.invoice_date,p.payment_receive_date,pi.invoice_total,SUM(p.payment_amount) as paid_amount,pi.currency_id,pi.gst_tax,pi.freight_charges,pi.added_by_user_id,pi.added_by_user_type_id FROM proforma_payment_detail as p, proforma_product_code_wise as pi WHERE pi.proforma_id=p.proforma_id AND p.is_delete=0 AND pi.status=1 AND pi.is_delete=0 AND pi.payment_status = 1 AND pi.gen_sales_status=1 $user_ids $date GROUP BY p.proforma_id";
	
		//printr($sql);die;
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
            $html .='   <table class="table tool-row b-t text-small" border="1">
                                <thead>';
                                if($n==1)
			                        $excel= '<span class="text-muted m-l-small pull-right"><a class="label bg-success" href="javascript:void(0);" id="excel_link" onclick="get_report()"><i class="fa fa-print"></i> Excel</a></span>';
                                    
                             $html .='
                                    <tr><td colspan="7"> <b style="color: red;">'.$user_name['name'].'</b> Commission Calculation Report From : <b>'.dateFormat(4,$post['f_date']).'</b>  To :  <b>'.dateFormat(4,$post['t_date']).'.</b>'.$excel.'</td></tr>
                                    <tr  style="background-color: black;color: white;">
                                        <th>Sr. No.</th>
                                        <th><center>PI-Number/ Date</center></th>
                                        <th><center>SI-Number/ Date</center></th>
                                        <th><center>Customer Name</center></th>
                                        <th><center>Total Amount Of Order</center></th>
                                        <th><center>Total Amount Received</center></th>
                                        <th><center>Net Amount Without Tax / Freight / Cylinder / Plate Charges</center></th>
                                        <th><center>Commission Amount</center></th>';//<th><center>Commission</center></th><th><center>Amount with Commisssion</center></th>
                                        
                              if($ib_user_id!='10'){
                                  $html .='<th><center>Invoice Owner </center></th>
                                                <th><center>Lead Owner</center></th>';
                              }
                                        
                                        if($post['emp_name']=='')
                                            $html.='<th><center>Attention By</center></th>';
                           $html.='</tr>
                                </thead>
                                <tbody>';
                                    $sr=1;
                                    $i = $comm_amt = $wout_amt = $paid_amt = $inv_amt = 0;
                                    foreach($data->rows as $row)
                                    {   
                                         $cyli_total = $this->query("SELECT SUM(pi.quantity*pi.rate) as cyli_total,pi.proforma_id from " . DB_PREFIX ."proforma_invoice_product_code_wise as pi,product_code as pc where pi.proforma_id = '".$row['proforma_id']."' AND pi.is_delete = '0' AND pi.product_code_id = pc.product_code_id AND pc.product IN (51,52)");
                                         //printr($cyli_total);
                                         $user = $this->getUser($row['added_by_user_id'],$row['added_by_user_type_id']);
                                         $paid = $this->query("SELECT SUM(payment_amount) as paid_amount FROM proforma_payment_detail WHERE proforma_id = '".$row['proforma_id']."' AND is_delete=0");
                                        $style ="style='background-color: aliceblue;'";
                                        if($i%2==0)
                                            $style ="style='background-color: antiquewhite;'";
                                         $without_tax=((($row['invoice_total']*100)/(100+$row['gst_tax']))-round($row['freight_charges'],3))-round($cyli_total->row['cyli_total'],3);
                                         $currency = $this->getCurrencyId($row['currency_id']);
                                         $sales = "SELECT invoice_no,invoice_date FROM sales_invoice WHERE proforma_no = '".$row['pro_in_no']."' AND is_delete=0 AND status=1";
                                         $sales_data = $this->query($sales);
                                         $sale='';
                                         if($ib_user_id!='10'){
                                                 $getusername=$this->getUser($row['added_by_user_id'],$row['added_by_user_type_id']);
                                                 $getLeadsdata=$this->get_lead_customer($row['email']);
                                                 $getLeadusername=$this->getUser($getLeadsdata['user_id'],$getLeadsdata['user_type_id']);
                                                 //printr($getLeadsdata);
                                                 $leaduser_name='';
                                                 $style_red='';
                                                if($getLeadsdata != '0' && ($getLeadsdata['user_id'] == 238 || $getLeadsdata['user_id'] == 239 || $getLeadsdata['user_id'] == 240 || $getLeadsdata['user_id'] == 227 || $getLeadsdata['user_id'] == 246 || $getLeadsdata['user_id'] == 256) ){
                                                $leaduser_name=$getLeadusername['user_name'];
                                                 }else{
                                                //$style_red='color:red';
                                                $leaduser_name=$getusername['user_name'];   
                                                 }
                                        
                                             
                                         }
                                         $getRepeatCustomer=$this->get_Repeat_Customers($row['email']);
                                         $new_customer='';
                                         if($ib_user_id=='10'){
                                             if(isset($getRepeatCustomer) && count($getRepeatCustomer) <= 1)
                                             {
                                                 $new_customer='<b style="color:red;">===>[New Buyer]<b>';
                                             }
                                            $sale = '<b>'.$row['order_no'].'</b> / '.dateFormat(4,$row['date']);
                                         }else
                                         {
                                            if($sales_data->rows)
                                                $sale = '<b>'.$sales_data->row['invoice_no'].'</b> / '.dateFormat(4,$sales_data->row['invoice_date']);
                                         }
                                         $html .='<tr '.$style.'>
                                                      <td><b>'.$sr.'</td>
                                                      <td><b>'.$row['pro_in_no'].'</b> / '.dateFormat(4,$row['invoice_date']).'</td>
                                                      <td>'.$sale.'</td>
                                                      <td>'.$row['cust_nm'].'-'.$new_customer.'</td>
                                                      <td style="text-align: right;">'.$row['invoice_total'].' '.$currency['currency_code'].'</td>
                                                      <td style="text-align: right;">'.$paid->row['paid_amount'].' '.$currency['currency_code'].'</td>
                                                      <td style="text-align: right;">'.round($without_tax,2).' '.$currency['currency_code'].'</td>
                                                      <td style="text-align: right;">'.round(($without_tax*$user_name['commission']/100),2).' '.$currency['currency_code'].'</td>';
                                                      
                                        if($ib_user_id!='10'){
                                              $html .='<td style="text-align: right;">'.$getusername['user_name'].'</td>
                                                      <td style="text-align: right;">'.$leaduser_name.'</td>';
                                        }
                                                      //<td style="text-align: right;">'.$user_name['commission'].' % </td><td style="text-align: right;">'.round((($without_tax*$user_name['commission'])/100),3).' '.$currency['currency_code'].'</td>
                                                      if($post['emp_name']=='')
                                                            $html.='<th>'.$user['name'].'</th>';
                                        $html .=' </tr>';
                                        $inv_amt+=$row['invoice_total'];
                                        $paid_amt+=$paid->row['paid_amount'];
                                        $wout_amt+=$without_tax;
                                        
                                        if($ib_user_id!='10' && ($getusername['user_name']==$leaduser_name)){
                                        $comm_amt+=round((($without_tax*$user_name['commission'])/100),3);
                                        }else if($ib_user_id=='10'){
                                        $comm_amt+=round((($without_tax*$user_name['commission'])/100),3);  
                                        }
                                         //$comm_amt+=round((($without_tax*$user_name['commission'])/100),3);   
                                        $i++;$sr++;
                                    }
                                    $html .='<tr style="">
                                                  <th colspan="4">Total</th>
                                                  <td style="text-align: right;">'.round($inv_amt,3).'</td>
                                                  <td style="text-align: right;">'.round($paid_amt,3).'</td>
                                                  <td style="text-align: right;">'.round($wout_amt,3).'</td>
                                                  <td style="text-align: right;"></td>';
                                        if($ib_user_id!='10'){          
                                                  $html .='<td style="text-align: right;"></td>
                                                           <td style="text-align: right;"></td>';
                                        } 
                                      $html .='</tr>';//<td></td><td style="text-align: right;">'.round($comm_amt,3).'</td>
                                    $html .='<tr style="">
                                                  <th colspan="6">Comission ('.$user_name['commission'].' %) </th>';
                                       if($ib_user_id=='10'){
                                           $html .=' <td colspan="2" style="text-align: right;">'.round(($wout_amt*$user_name['commission'])/100,2).'</td>';
                                       }else{
                                           $html .=' <td colspan="2" style="text-align: right;"><b>Total Commission Amount(After Exclduing):</b>'.round($comm_amt,2).'<br><small><b>Total Commission Amount:</b>'.round(($wout_amt*$user_name['commission'])/100,2).'</small></td>';
                                       }            
                                     
                                    if($ib_user_id!='10'){          
                                                  $html .='<td style="text-align: right;"></td>
                                                           <td style="text-align: right;"></td>';
                                        }               
                                                  
                                    $html .='</tr>';
                        $html .='</tbody>
                            </table>';
        }
        return $html;
    }
	//END [kinjal]
	
    function send_mail_customer($data,$n=0){
    		
	
		$proforma=$this->getProformaData($data['proforma_id_send']);
		$html='';
		if($n==0)
		{  
			$html.= '<p>'.$data['customerinfo'].'</p>'; 
		}
		else
		{
			$html.= $data['message'];
			$email_temp[]=array('html'=>$html,'email'=>$data['emailform']);
		}
		    
		$email_temp[]=array('html'=>$html,'email'=>$data['toemail']);
		
		if($data['bccemail']=='')
		    $data['bccemail'] = $data['admin'];
		else
		    $data['bccemail'] .=','.$data['admin'];
		    
		$email_id=$this->getUser($proforma['added_by_user_id'],$proforma['added_by_user_type_id']);
		$signature = '<br> Thank you <br> kind Regards, <br>'.$email_id['first_name'].' '.$email_id['last_name'];
		$subject =$data['subject']; 
		
		
		$form_email=$email_id['email'];
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(7); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				
		$path = HTTP_SERVER."template/proforma_invoice.html";
		$output = file_get_contents($path);  
		$search  = array('{tag:header}','{tag:details}');
		$to_email_ids=implode(",", array_column($email_temp,"email")); 
		
		
		$toEmail =$form_email;
		$firstTimeemial = 1;								
		$message = '';
		if($html)
		{
			$tag_val = array(
			"{{header}}"=>$subject,
				"{{PouchMakersDetail}}" =>$html,
				"{{signature}}"	=> $signature,
			);
			if(!empty($tag_val))
			{
				$desc = $temp_desc;
				foreach($tag_val as $k=>$v)
				{
					@$desc = str_replace(trim($k),trim($v),trim($desc));
				} 
			}
			$replace = array($subject,$desc);
			$message = str_replace($search, $replace, $output);
		}
		/*if($n==0) //from the proforma followup page
		    send_email_test($to_email_ids,$form_email,$subject,$message,'',$data['url'],'',$data['bccemail'],$data['ccemail']); 
		else // from the proforma add page*/
		    send_email_test($to_email_ids,$form_email,$subject,$message,'',$data['url'],'',$data['bccemail'],$data['ccemail']); 
	}
	public function check_customer($email)
	{
	    $contacts = "SELECT ca.email_1,ab.user_id,ab.user_type_id FROM company_address as ca,address_book_master as ab WHERE ca.email_1='".$email."' AND ca.is_delete=0 AND ab.is_delete=0 AND ab.address_book_id =ca.address_book_id";
		$datacontacts= $this->query($contacts);
		if($datacontacts->num_rows) {
			return $datacontacts->row;
		} else {
			return 0;
		}
	}
	public function get_lead_customer($email)
	{
	    
    	    $contacts = "SELECT * FROM enquiry  WHERE email='".$email."' AND is_delete=0 AND status=1 ";
    		$datacontacts= $this->query($contacts);
    		if($datacontacts->num_rows) {
    			return $datacontacts->row;
    		} else {
    			return 0;
    		}
	   
	}
	public function get_Repeat_Customers($email)
	{
	    
    	    $sales_contacts = "SELECT * FROM sales_invoice  WHERE email='".$email."' AND is_delete=0 AND status=1 ";
    		$data_salescontacts= $this->query($sales_contacts);
    		if($data_salescontacts->num_rows) {
    			return $data_salescontacts->rows;
    		} else {
    			return 0;
    		}
	   
	}
	/*public function GetLeadOwner($email)
	{
	    $contacts = "SELECT ca.email_1,ab.user_id,ab.user_type_id FROM company_address as ca,address_book_master as ab WHERE ca.email_1='".$email."' AND ca.is_delete=0 AND ab.is_delete=0 AND ab.address_book_id =ca.address_book_id";
		$datacontacts= $this->query($contacts);
		if($datacontacts->num_rows) {
			return $datacontacts->row;
		} else {
			return 0;
		}
	}*/
	/*public function getTotalInvoiceCustomerFollowups($filter_data=array(), $status, $proforma_status,$user_id,$user_type_id,$is_delete,$add_book_id='0',$permission=0,$customer_followups=0,$interval)

		{
			
			//add sonu
				$add_id='';
				if($add_book_id!=0)
				$add_id = "AND p.address_book_id='". $add_book_id."'";
			    $customer_follow='';
			    
			    
			       //printr($interval);
		    	if($customer_followups!=0) 
			     	$customer_follow = "  AND government_sales_status=0  AND gen_sales_status=0  AND p.invoice_date BETWEEN (NOW()-INTERVAL ".$interval." DAY) AND NOW() ";
				
			
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
    
    			//	$str = ' OR ( p.added_by_user_id IN ('.$userEmployee.') AND p.added_by_user_type_id = 2 )';
    
    			}
    
    				
    
    				$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c WHERE c.country_id=p.destination AND p.is_delete = 0  AND p.added_by_user_id ='".$user_id."' AND  p.added_by_user_type_id = '".$user_type_id."' AND p.is_delete = '".$is_delete."' $add_id  $customer_follow" ;
    
    				 if(!empty($filter_data['product_code']))
					       $sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c,proforma_invoice_product_code_wise as pi WHERE c.country_id=p.destination AND p.proforma_id= pi.proforma_id AND p.is_delete = 0  AND p.added_by_user_id ='".$user_id."' AND  p.added_by_user_type_id = '".$user_type_id."' AND p.is_delete = '".$is_delete."' $add_id $customer_follow " ;

    
    			}

				if($status >= '0') {

				$sql .= " AND p.status ='".$status."' ";

			}

			if($proforma_status >= '0') {

				$sql .= " AND proforma_status ='".$proforma_status."' ";

			}

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

		}*/

	public function getInvoicesCustomerFollowups($data=array(),$filter_data=array(), $status, $proforma_status,$user_id,$user_type_id,$is_delete,$add_book_id = '0',$permission=0,$customer_followups=0,$interval,$admin_user_id=0){	
		
		    $add_id='';
			if($add_book_id!=0)
				$add_id = "AND p.address_book_id='". $add_book_id."'";
			$customer_follow='';
		    
		    
		    	
			if($user_type_id==1 && $user_id==1)
            {
                if($customer_followups!=0) 
			    	$customer_follow = "  AND government_sales_status=0  AND 	gen_sales_status=0   ";
			    	
				$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c WHERE c.country_id=p.destination AND p.is_delete = '".$is_delete."' $add_id $customer_follow " ;
                if(!empty($filter_data['product_code']))
					$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c,proforma_invoice_product_code_wise as pi WHERE c.country_id=p.destination AND p.proforma_id = pi.proforma_id AND p.is_delete = '".$is_delete."' $add_id  $customer_follow" ;
            }
            else
            {
                    if($customer_followups!=0) 
                    {
			    	    if($admin_user_id==6)
			    	        $customer_follow = "  AND government_sales_status=0  AND gen_sales_status=0 ";
			    	    else    
			    	        $customer_follow = "  AND gen_sales_status=0 ";
                    }
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
    				    $per = " p.added_by_user_id='".$set_user_id."'";
    
    			}else{
    
    				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
    
    				$set_user_id = $user_id;
    
    				$set_user_type_id = $user_type_id;
                    
                    $per = "p.added_by_user_id='".$set_user_id."'";
    			}
    
    			$str = '';
    
    			if($admin_user_id!=6)
    			{
        			if($userEmployee){
                        $str = " OR (p.added_by_user_id IN (".$userEmployee.") AND  p.added_by_user_type_id = '2' )";
                    }
    			}
    				$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c WHERE c.country_id=p.destination AND (p.added_by_user_id ='".$user_id."' AND  p.added_by_user_type_id = '".$user_type_id."' $str )AND p.is_delete = '".$is_delete."' $add_id $customer_follow " ;
                    if(!empty($filter_data['product_code']))
					       $sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c,proforma_invoice_product_code_wise as pi WHERE c.country_id=p.destination AND p.proforma_id= pi.proforma_id AND p.is_delete = 0  AND ( p.added_by_user_id ='".$user_id."' AND  p.added_by_user_type_id = '".$user_type_id."' $str )AND p.is_delete = '".$is_delete."' $add_id $customer_follow " ;

    		}
            if($interval!='')
			    $sql.=" AND p.invoice_date BETWEEN ((NOW()-INTERVAL ".$interval." DAY)- INTERVAL ".$interval." DAY) AND (NOW()-INTERVAL 7 DAY)  ";
			 //   $sql.=" AND p.invoice_date BETWEEN (NOW()-INTERVAL ".$interval." DAY) AND NOW() ";
			    
			if($status >= '0') { 

				$sql .= " AND p.status ='".$status."' ";

			}

			if($proforma_status >= '0') {

				$sql .= " AND proforma_status ='".$proforma_status."' ";

			}

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

				$sql .= " ORDER BY p.invoice_date";	

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
//echo $sql;

			$data = $this->query($sql);
        $pro_data=array();
			if($data->num_rows){ 
			    
			     foreach($data->rows as $pro)
    			  {
    			      $payment_data = $this->Payment_detail_customer($pro['proforma_id']);
    			      if(empty($payment_data)){
    			          $pro_data[]=$pro;
    			      }
    			    
    			  }

				return $pro_data;

			}else{

				return false;

			}

		}
	public function getInvoiceProduct($invoice_id)
	{
		$sql = "SELECT ip.* FROM `" . DB_PREFIX . "sales_invoice_product` as ip,product_code as pc WHERE pc.product_code_id=ip.product_code_id AND invoice_id = '" .(int)$invoice_id. "' AND ip.is_delete=0 AND pc.product NOT IN (51,52) AND ip.customer_dispatch_p=0";
	    $data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}		
	}
	public function getInvoiceNetData($invoice_id)
	{ 
		$sql = "SELECT  i.*,ip.* FROM  " . DB_PREFIX . "sales_invoice as i,sales_invoice_product as ip WHERE i.invoice_id =ip.invoice_id AND i.invoice_id = '" .(int)$invoice_id. "'  AND i.is_delete=0 AND i.customer_dispatch = 0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
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
 public function getprepaymentreport($post,$n)
	{
       // printr($post);//die;
        $date = " AND p.payment_receive_date >= '".$post['f_date']."' AND p.payment_receive_date <='".$post['t_date']."' ";
        $user_ids=$html=$user_name=$excel='';
        if($post['emp_name']!='')
		{
		    $user = explode("=",$post['emp_name']);
		    $user_ids=" AND pi.added_by_user_id = '".$user[1]."' AND pi.added_by_user_type_id = '".$user[0]."'";
		 //   $user_ids_kath=" AND po.user_id = '".$user[1]."' AND po.user_type_id = '".$user[0]."'";
		    $user_name = $this->getUser($user[1],$user[0]);
		    $user_name['name'] = $user_name['name'].'&#39s';
		    $ib_user_id= $user_name['user_id'];
		}
		else
		{ $str =$str_kath = ''; 
		    if($_SESSION['LOGIN_USER_TYPE'] == 1){
		        $ib_user_id=$set_user_id=$post['user_name'];
		            $userEmployee = $this->getUserEmployeeIds(4,$ib_user_id);
		    }else{
		    if($_SESSION['LOGIN_USER_TYPE'] == 2){
    
    				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
    
    				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id'],$permission);
    
    				$set_user_id = $parentdata->row['user_id'];
    
    				$set_user_type_id = $parentdata->row['user_type_id'];
    
    			}else{
    
    				$userEmployee = $this->getUserEmployeeIds($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
    
    				$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
    
    				$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
    
    			}
    			
    			// $userEmployee = $this->getUserEmployeeIds('4',$post['user_name']);
    		    
                
		    } 
		    if($userEmployee){
                    $str = ' OR ( pi.added_by_user_id IN ('.$userEmployee.') AND pi.added_by_user_type_id = 2 )';
                 //   $str_kath = ' OR ( po.user_id IN ('.$userEmployee.') AND po.user_type_id = 2 )';
                }
                $user_ids=" AND (pi.added_by_user_id = ".$set_user_id." && pi.added_by_user_type_id='4' ".$str.")";
             //   $user_ids_kath=" AND (po.user_id = ".$set_user_id." && po.user_type_id='4' ".$str_kath.")";
                $user_name = $this->getUser($set_user_id,'4');$user_name['name']='';
                $ib_user_id= $set_user_id;

		} 
	
	//	if($ib_user_id=='10')
	//	   $sql="SELECT *,po.date_added as date FROM  packing_order as po ,account_master as am,proforma_product_code_wise as pp  WHERE  am.user_id=po.user_id AND am.user_type_id=po.user_type_id AND po.packing_order_id!=0 AND po.is_delete=0 AND po.order_date >= '".$post['f_date']."' AND po.order_date <='".$post['t_date']."' $user_ids_kath AND po.pro_in_no=pp.pro_in_no AND pp.gen_pro_as=1 ORDER BY po.order_date,po.packing_order_id ASC";
    //	else
        $payment_type="";
	    if($post['payment_type']!='')
	       $payment_type="AND p.`payment_type` LIKE '%".$payment_type."%'";
	       
    	   $sql="SELECT pi.*,SUM(p.payment_amount) as paid_amount,p.payment_receive_date,p.payment_type  FROM proforma_payment_detail as p, proforma_product_code_wise as pi WHERE pi.proforma_id=p.proforma_id AND p.is_delete=0 AND pi.status=1 AND pi.is_delete=0 AND p.`payment_type`='advance' AND pi.gen_sales_status=0    $user_ids $date GROUP BY p.payment_id";
	
	//	printr($sql);die;
		$data = $this->query($sql);
		if($data->num_rows){
            $html .='   <table class="table tool-row b-t text-small" border="1">
                                <thead>';
                                if($n==1)
			                        $excel= '<span class="text-muted m-l-small pull-right"><a class="label bg-success" href="javascript:void(0);" id="excel_link" onclick="get_report()"><i class="fa fa-print"></i> Excel</a></span>';
                                    
                             $html .='
                                    <tr><td colspan="8"> <b style="color: red;">'.$user_name['name'].'</b> Prepayment Report From : <b>'.dateFormat(4,$post['f_date']).'</b>  To :  <b>'.dateFormat(4,$post['t_date']).'.</b>'.$excel.'</td></tr>
                                    <tr  style="background-color: black;color: white;">
                                        <th>Sr. No.</th>
                                        <th><center>PI-Number /Date</center></th>
                                        <th><center> Advance Received  Date</center></th>
                                        <th><center>Customer Name/ Email</center></th>
                                        <th><center>Total Amount Of Invoice</center></th>
                                        <th><center>Advance Amount </center></th>
                                        <th><center>Due Amount</center></th>';//<th><center>Commission</center></th><th><center>Amount with Commisssion</center></th>
                                        if($post['emp_name']=='')
                                            $html.='<th><center>Attention By</center></th>';
                           $html.='</tr>
                                </thead>
                                <tbody>';
                                    $sr=1;
                                    $i = $comm_amt = $wout_amt = $paid_amt = $due_amt = $inv_amt = 0;
                                    foreach($data->rows as $row)
                                    {
                                       // printr($row);
                                       
                                         $user = $this->getUser($row['added_by_user_id'],$row['added_by_user_type_id']);
                                         $style ="style='background-color: aliceblue;'";
                                         $currency = $this->getCurrencyId($row['currency_id']);
                                         $html .='<tr '.$style.'>
                                                      <td><b>'.$sr.'</td>
                                                      <td><b>'.$row['pro_in_no'].'</b> / '.dateFormat(4,$row['invoice_date']).'</td>
                                                      <td><b>'.dateFormat(4,$row['payment_receive_date']).'</td>
                                                      <td>'.$row['customer_name'].' /'.$row['email'].'</td>
                                                      <td style="text-align: right;">'.$row['invoice_total'].' '.$currency['currency_code'].'</td>
                                                      <td style="text-align: right;">'.$row['paid_amount'].' '.$currency['currency_code'].'</td>
                                                      <td style="text-align: right;">'.round(($row['invoice_total']-$row['paid_amount']),3).' '.$currency['currency_code'].'</td>';
                                                      
                                                      if($post['emp_name']=='')
                                                            $html.='<th>'.$user['name'].'</th>';
                                        $html .=' </tr>';
                                        $inv_amt+=$row['invoice_total'];
                                        $paid_amt+=$row['paid_amount'];
                                        $due_amt+=round(($row['invoice_total']-$row['paid_amount']),3);
                                      
                                        $i++;$sr++;
                                    }
                                    $html .='<tr style="">
                                                  <th colspan="4">Total</th>
                                                  <td style="text-align: right;">'.round($inv_amt,3).'</td>
                                                  <td style="text-align: right;">'.round($paid_amt,3).'</td>
                                                  <td style="text-align: right;">'.round($due_amt,3).'</td>
                                                  <th ></th>
                                             </tr>';
                                  
                        $html .='</tbody>
                            </table>';
        }
        return $html;
    }
    public function getQtyRange($qty,$ib_user_id)
    {
        $data =$this->query("SELECT * FROM proforma_price_qty_master WHERE 	admin_user_id='".$ib_user_id."' AND from_qty <= '".$qty."' AND to_qty >= '".$qty."' AND is_delete=0 ");
        if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
    } public function getUserCommisionData()
    {
        $data =$this->query("SELECT * FROM `account_master` WHERE `status` = 1 AND commission!=0 ");
        if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
    }
    public function getInvoiceProductWithBox($product_code_id)
	{
		$sql = "SELECT bm.quantity,pc.product_code FROM box_master as bm LEFT JOIN product_code as pc ON (bm.product_id = pc.product AND bm.valve=pc.valve AND FROM_base64(bm.zipper)=pc.zipper AND FROM_base64(bm.spout)=pc.spout AND FROM_base64(bm.accessorie)=pc.accessorie AND bm.make_pouch=pc.make_pouch AND bm.pouch_volume = pc.volume AND bm.pouch_volume_type=pc.measurement AND FROM_base64(bm.transportation) = 'sea' ) WHERE pc.product_code_id = '".$product_code_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}		
	} 
	 public function getpaymentreport($post,$n)
	{
       // printr($post);//die;
        $date = " AND p.payment_receive_date >= '".$post['f_date']."' AND p.payment_receive_date <='".$post['t_date']."' ";
        $user_ids=$html=$user_name=$excel='';
        if($post['emp_name']!='')
		{
		    $user = explode("=",$post['emp_name']);
		    $user_ids=" AND pi.added_by_user_id = '".$user[1]."' AND pi.added_by_user_type_id = '".$user[0]."'";
		 //   $user_ids_kath=" AND po.user_id = '".$user[1]."' AND po.user_type_id = '".$user[0]."'";
		    $user_name = $this->getUser($user[1],$user[0]);
		    $user_name['name'] = $user_name['name'].'&#39s';
		    $ib_user_id= $user_name['user_id'];
		}
		else
		{ $str =$str_kath = ''; 
		    if($_SESSION['LOGIN_USER_TYPE'] == 1){
		        $ib_user_id=$set_user_id=$post['user_name'];
		            $userEmployee = $this->getUserEmployeeIds(4,$ib_user_id);
		    }else{
		    if($_SESSION['LOGIN_USER_TYPE'] == 2){
    
    				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
    
    				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id'],$permission);
    
    				$set_user_id = $parentdata->row['user_id'];
    
    				$set_user_type_id = $parentdata->row['user_type_id'];
    
    			}else{
    
    				$userEmployee = $this->getUserEmployeeIds($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
    
    				$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
    
    				$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
    
    			}
    			
    			// $userEmployee = $this->getUserEmployeeIds('4',$post['user_name']);
    		    
                
		    } 
		    if($userEmployee){
                    $str = ' OR ( pi.added_by_user_id IN ('.$userEmployee.') AND pi.added_by_user_type_id = 2 )';
                 //   $str_kath = ' OR ( po.user_id IN ('.$userEmployee.') AND po.user_type_id = 2 )';
                }
                $user_ids=" AND (pi.added_by_user_id = ".$set_user_id." && pi.added_by_user_type_id='4' ".$str.")";
             //   $user_ids_kath=" AND (po.user_id = ".$set_user_id." && po.user_type_id='4' ".$str_kath.")";
                $user_name = $this->getUser($set_user_id,'4');$user_name['name']='';
                $ib_user_id= $set_user_id;

		} 
	
	//	if($ib_user_id=='10')
	//	   $sql="SELECT *,po.date_added as date FROM  packing_order as po ,account_master as am,proforma_product_code_wise as pp  WHERE  am.user_id=po.user_id AND am.user_type_id=po.user_type_id AND po.packing_order_id!=0 AND po.is_delete=0 AND po.order_date >= '".$post['f_date']."' AND po.order_date <='".$post['t_date']."' $user_ids_kath AND po.pro_in_no=pp.pro_in_no AND pp.gen_pro_as=1 ORDER BY po.order_date,po.packing_order_id ASC";
    //	else
        $payment_type="";
	 /*   if($post['payment_type']!='')
	       $payment_type="AND p.`payment_type` LIKE '%".$payment_type."%'";*/
	       
    	   $sql="SELECT pi.*,SUM(p.payment_amount) as paid_amount,p.payment_receive_date,p.payment_type,p.payment_mode,pi.buyers_order_no  FROM proforma_payment_detail as p, proforma_product_code_wise as pi WHERE pi.proforma_id=p.proforma_id AND p.is_delete=0 AND pi.status=1 AND pi.is_delete=0  $user_ids $date GROUP BY p.payment_id";
//	printr($sql);//die; 
		$data = $this->query($sql);
		if($data->num_rows){
            $html .='   <table class="table tool-row b-t text-small" border="1">
                                <thead>';
                                if($n==1)
			                        $excel= '<span class="text-muted m-l-small pull-right"><a class="label bg-success" href="javascript:void(0);" id="excel_link" onclick="get_report()"><i class="fa fa-print"></i> Excel</a></span>';
                                     
                             $html .=' 
                                    <tr><td colspan="10"><center><h4> <b style="color: red;">'.$user_name['name'].' Payment Report From : '.dateFormat(4,$post['f_date']).'  To :  '.dateFormat(4,$post['t_date']).'.</b>'.$excel.'</h4></center></td></tr>
                                    <tr  style="background-color: black;color: white;">
                                        <th>Sr. No.</th>
                                        <th><center>PD-Number</center></th>
                                        <th><center>PI-Number /Date</center></th>
                                        <th><center>Customer Name</center></th>
                                        <th><center>Email</center></th>
                                        <th><center>Payment Type /Payment mode</center></th>
                                        <th><center> Payment Received  Date</center></th>
                                        <th><center> Amount </center></th>';
                                        if($post['emp_name']=='')
                                            $html.='<th><center>Attention By</center></th>';
                           $html.='</tr>
                                </thead>
                                <tbody>';  
                                    $sr=1;
                                    $i = $comm_amt = $wout_amt = $paid_amt = $due_amt = $inv_amt = 0;
                                    foreach($data->rows as $row)
                                    {
                                        //printr($row);die;
                                       
                                         $user = $this->getUser($row['added_by_user_id'],$row['added_by_user_type_id']);
                                         $style ="style='background-color: aliceblue;'";
                                         $currency = $this->getCurrencyId($row['currency_id']);
                                         $html .='<tr '.$style.'>
                                                      <td><b>'.$sr.'</td>
                                                      <td><b>'.$row['buyers_order_no'].'</b></td>
                                                      <td><b>'.$row['pro_in_no'].'</b> / '.dateFormat(4,$row['invoice_date']).'</td>
                                                      <td><b>'.$row['customer_name'].'</b> </td>
                                                      <td><b>'.$row['email'].'</b> </td>
                                                      <td><b>'.$row['payment_type'].' </b> / <b> '.$row['payment_mode'].'</b></td>
                                                      <td><b>'.dateFormat(4,$row['payment_receive_date']).'</td>
                                                      <td style="text-align: right;">'.$row['paid_amount'].' '.$currency['currency_code'].'</td>';
                                                      if($post['emp_name']=='')
                                                            $html.='<th>'.$user['name'].'</th>';
                                        $html .=' </tr>';
                                        $inv_amt+=$row['invoice_total'];
                                        $paid_amt+=$row['paid_amount'];
                                        $due_amt+=round(($row['invoice_total']-$row['paid_amount']),3);
                                      
                                        $i++;$sr++;
                                    }
                                    $html .='<tr style="">
                                                  <th colspan="7">Total</th>
                                                 
                                                  <td style="text-align: right;">'.round($paid_amt,3).' '.$currency['currency_code'].'</td>
                                                 
                                                  <th ></th>
                                             </tr>';
                                  
                        $html .='</tbody>
                            </table>';
        } 
        return $html;
    }
   
   function viewProformaInvoice_newGSTFormat($proforma_id,$goods_status=0) {
           
			$html ='';

			$proforma=$this->getProformaData($proforma_id);

		    $proforma_id=$proforma['proforma_id'];

			$proforma_inv=$this->getProformaInvoice($proforma_id);

			$user_name=$this->getUser($proforma['added_by_user_id'], $proforma['added_by_user_type_id']);

		    $show_vat='';
			$show_qst='';
			$qst_no = '';
			$admin_vat_no='';
			
		    if($proforma['contact_name']!='')
			    $contact_name='<b>Kind Attention :</b> '.$proforma['contact_name'];
            else
            	$contact_name='';
		

			if($proforma['added_by_user_id'] == '1' && $proforma['added_by_user_type_id'] =='1')

			{

				$image= HTTP_UPLOAD."admin/store_logo/logo.png";

				$img = '<img src="'.$image.'" alt="Image">';

			}

			else

			{

				

				if($proforma['added_by_user_type_id'] == 2){

					$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$proforma['added_by_user_id']."' ");

					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);

					$set_user_id = $parentdata->row['user_id'];

					

					$set_user_type_id = $parentdata->row['user_type_id'];

					//echo "1 echio  ";

				}else{

					$userEmployee = $this->getUserEmployeeIds($proforma['added_by_user_type_id'],$proforma['added_by_user_id']);

					$set_user_id = $proforma['added_by_user_id'];

					//echo $set_user_id."2";

					$set_user_type_id = $proforma['added_by_user_type_id'];

				}

				$user_info=$this->getUser($set_user_id,'4');
                
			
				$data=$this->query("SELECT logo,abn_no,termsandconditions_invoice,note_invoice FROM international_branch WHERE international_branch_id = '".$set_user_id."'");

				if(isset($data->row['logo']) &&  $data->row['logo']!= '')

				{

					$image= HTTP_UPLOAD."admin/logo/200_".$data->row['logo'];

	
 
					//echo $image;
                    if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='39'))
					    $img = '<img src="'.HTTP_UPLOAD."admin/logo/200_OXYMIST_1.jpg".'" alt="Image" width="65%" id="oxi_img" class="oxi_img">';
                    else
                        $img = '<img src="'.$image.'" alt="Image">';
				}

				else

				{

					$img ='';

				}			

			}
            
            if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='37' || $set_user_id=='38' || $set_user_id=='39' ))
            {
                $title='Consignor';
               //printr($proforma['destination']);
                $user=$this->getCompanyAdd($proforma['added_by_user_type_id'],$proforma['added_by_user_id']);
                
                $address=nl2br($user['company_address']);
                
                $sign=$user['company_name'];
                $admin_vat_no = 'GST No. : '.$user['vat_no'];
                $vat_no = $proforma['vat_no'];
                $show_vat = 'GST No. :'.$vat_no;
            }
            else
            {
    			$title='Consignor';

				$address='SWISS PAC PVT LTD</b><br>Padra Jambusar National highway

				<br>At Dabhasa village,Pin 391440<br>Taluka.Padra, Dist.Vadodara(State Gujarat) India ';

				$sign='Swiss PAC PVT LTD';

				$user=$this->getCompanyAdd($proforma['added_by_user_type_id'],$proforma['added_by_user_id']);

				$vat_no = $proforma['vat_no'];

				$admin_vat_no = 'GST No. : '.$user['vat_no'];

				$show_vat = 'GST No. :'.$vat_no;
    
    		}
            
             if($goods_status==1){
               $title_pro='<b> SALES TAX INVOICE <span style="color:#f92c09" >( GOODS NOT DISPATCHED )</span></b>'; 
             }else{
                     if($proforma['proforma_title']!=''){
                        $title_pro = $proforma['proforma_title'];
                     }else{
                          $title_pro = 'PROFORMA INVOICE';
                     }
             } 
             $state_text='';
            if($proforma['state_india']!=0){
               $state_details=$this->getIndiaStateDetails($proforma['state_india']); 
               $state_text='State: '.$state_details['state'].' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    State Code: '.$state_details['state_code_in_no'];
            }
            
        
			$html .='<div class="width_div"><div style="text-align:center;border: 1px solid black;">'.$title_pro;

			if($proforma['discount']!='0') { $html.='<span style="float:right;font-size:14px;">'.($proforma['discount'] + 0).'</span>'; }

			$html.='</div>

						<div class="" style=" width: 100%;float: left;  border: 1px solid black;font-size: 18px;">

							<table style="width: 100%;" >

								<tr>

									<td style="vertical-align: top;">';
                                        $Consignee ='Consignee';$Delivery_add ='Delivery Address';
										if($user_name['country_id']=='14')

											$html .=$img.'<br><br>';

										if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='37' || $set_user_id=='38'))
										{
										    $html .=$img.'<br><br>';
										    $html .='<p><b>'.$title.'<br></p><p>'.$address.'<br></p>'.$admin_vat_no.'</b>';
										    
										    $Consignee = 'Buyer'; $Delivery_add='Consignee & Delivery Address';
										}
										elseif (isset($set_user_id) && !empty($set_user_id) && $set_user_id=='39' || isset($set_user_id) && !empty($set_user_id) && $set_user_id=='44')
										{
										    $html .=$img.'<br><br>';
										    $html .='<p><b>'.$title.'</b><br></p><p>'.$address.'<br></p>'.$admin_vat_no;
										}
										else
										{
									        $html .='<p><b>'.$title.'<br></p><p>'.$address.'<br></p>';
									        
									       $html .=$admin_vat_no; 
									      
									           
									       $html .='</b>';
										}
								$html .='	</td>

									<td style="padding: 0px;vertical-align: top;">

										<table style=" width: 100%;border: 1px solid black; border-spacing: 0px;" cellspacing="0px" cellpadding="10px"  >

											<tbody><tr>

												<td valign="top"><b>Invoice No.&amp; Date</b></td>

												<td>'.$proforma['pro_in_no'].' &nbsp;/&nbsp; '.dateFormat(4,$proforma['invoice_date']).'<br><br><br><span class="barcode" >'.$proforma['pro_in_no'].'</span><br><br></td>

												</tr>

											<tr>

												<td><b>Proforma :</b></td>

												<td>&nbsp;'.dateFormat(4,$proforma['proforma']).'</td>

											</tr>

											<tr>

												<td><b>Buyers Order No. &amp; Date:</b></td>

												<td>'.$proforma['buyers_order_no'].' &nbsp;/&nbsp; '.dateFormat(4,$proforma['buyers_date']).'</td>

											</tr>

											<tr>

												<td><b>Country of origin of goods:</b></td>

												<td>'.$proforma['goods_country'].'</td>

											</tr>

											</tbody>

										</table>

									</td>

								</tr>

							</table>

						</div>

						

						<div class="" style="width: 100%; float: left;  border: 1px solid black;font-size: 18px;">

						

							<table style="width: 100%;">

								<tr>

									<td style="vertical-align: top;">

									    <p><b>'.$Consignee.'</b></p>

										<p><b>'.$proforma['customer_name'].'</b><br/>'.nl2br($proforma['address_info']).'<br/>'.$state_text.'<br/>Email : '.$proforma['email'].'<br>Contact No. : ' . $proforma['contact_no'] . '<br>'.$show_vat.'<br>'.$show_qst.'<br>'.$contact_name.'</p>

									</td>';
									
									
									if($proforma['same_as_above']!='1')
									{
										$html .= '<td style="vertical-align: top;">
								        	<p><b>'.$Delivery_add.'</b></p>';
                                            $html .= '	<p>' . nl2br($proforma['del_address_info']) . '<br/>Email : ' . $proforma['email'] . '<br></p>';
        								$html .= '  </td>';
									}

									$html .= '<td style="padding: 0px;vertical-align: top;">

										<table cellspacing="0px" cellpadding="0px" style=" border-spacing: 0px; width: 100%;border: 1px solid black;padding: 0px;">

											<tbody><tr>

												<td style="text-align:center" colspan="2"> <b>Terms of Delivery &amp; Payment</b></td>

											</tr>

											<tr>

												<td><b>Delivery:</b></td>

												<td>'.$proforma['delivery_info'].'</td>

											</tr>';
                                    $html.='<tr>
	
												<td><b>Mode Of Shipment:</b></td>

												<td>';
        										if(decode($proforma['transportation'])=='road')
        										{
        										    $html.='By Pickup';
        										}
        										elseif(decode($proforma['transportation'])=='by road')
        										    $html.='By Road';
        										else
        										    $html.='By '.ucwords(decode($proforma['transportation']));
        											
        									
        										$html.='</td>
										</tr>';

										

									$html.='<tr>

												<td><b>Payment Terms:</b></td>

												<td>'.$proforma['payment_terms'].'</td>

											</tr>

									</tbody>

										</table>

										<table style=" border-spacing: 0px; width:100%;border: 1px solid black;"  width: 100%;>

											 <tbody><tr>

												<td><b>Port Of Loading:</b></td>';

												

													$html.='<td><b>Final Destination:</b></td>';

											

										$html.='</tr>';

											 $con_id =$proforma['destination'];

											$countrys = $this->getCountry($con_id);

                                        

									$html.='<tr><td>'.$proforma['port_loading'].'</td>';

												

													$html.='<td>'.$countrys['country_name'].'</td>';


										$html.='</tr>

											</tbody>

										</table>

									</td>

								</tr>

							</table>

							

						</div>';	
	
						

				$currency = $this->getCurrencyId($proforma['currency_id']);
                    
					$html .='<div class="" style="width: 100%; float: left;  border: 1px solid black;">

							<table cellspacing="0px" cellpadding="10px" border="1" style="width: 100%; border-spacing: 0px;font-size: 14px;">

								<tbody>

								<tr>

									<td width="5%"><div align="center"><b>Sr. No</b></div></td>

									<td width="40%"><div align="center"><b>Description of Goods </b></div></td>

									<td width="10%"><b>Quantity In</b></td>

									<td width="7%"><b>Rate In &nbsp;'.$currency['currency_code'].'</b></td>

									<td width="10%"><b>Basic Amount In &nbsp;'.$currency['currency_code'].'</b></td>';
									if ($proforma['taxation'] != 'sez_no_tax') {
                    					if($proforma['taxation'] == 'With in Gujarat'){
                    					    $html.='<td  width="10%" colspan="2"><div align="center"><b>SGST </b></div></td>';
                    					    $html.='<td  width="10%" colspan="2"><div align="center"><b>CGST </b></div></td>';
                    					}
									    else
									        $html.='<td  width="10%" colspan="2"><div align="center"><b>IGST </b></div></td>';
									}
									$html.='<td width="10%"><div align="center"><b>Total Amount &nbsp;'.$currency['currency_code'].'</b></div></td>';
							$html.='</tr>';
                                if ($proforma['taxation'] != 'sez_no_tax') {
                                    $html .='<tr>
                                                <td colspan="5"></td>';
                                                if($proforma['taxation'] == 'With in Gujarat'){
                                                    $html .='<td width="5%" ><div align="center"><b>Rate</b></div></td>
                                                            <td width="10%" ><div align="center"><b>Tax Amount</b></div></td>
                                                            <td width="5%" ><div align="center"><b>Rate</b></div></td>
                                                            <td width="10%" ><div align="center"><b>Tax Amount</b></div></td>';
                                                }
                                                else
                                                {
                                                    $html .='<td width="5%" ><div align="center"><b>Rate</b></div></td>
                                                            <td width="10%" ><div align="center"><b>Tax Amount</b></div></td>';
                                                }
                                        $html .='<td></td>';
                                        $html .='</tr>';
                                }
								$total = 0;$total_rate=0; $final_total=0; $n=1;$total_excies_rate=0;$ex_per=array();$total_taxation_rate=0;$tax_per=array();$taxa=array();
								$total_igst_rate = 0;
								$total_sgst_rate = $tax_cgst=$tax_sgst = 0;
								$total_cgst_rate =$total_tax_price= $amount_with_tax=$tax_price = $total_amount_with_tax=0;

								$custom_pro_id = 0;
                                if(!empty($proforma_inv)){
                					foreach($proforma_inv as $invoice_key=>$invoice){
                
                									$product_code_data = $this->getProductCode($invoice['product_code_id']);
                                                    $product_gst_details = $this->getProudctGSTDetails($product_code_data['product']);
                                                    $accessorie_second='';
                								    if($product_code_data['accessorie_second']!='')
                								    {
                									    $accessorie_second = $this->getAccessorie($product_code_data['accessorie_second']);
                									    $accessorie_second = ', '.$accessorie_second['product_accessorie_name'];
                								    }
                									
                									if(strrchr($product_code_data['product_code'],'CUST'))
                
                										$custom_pro_id = 1;
                
                									
                                                    //commented by kinjal on 8-7-2017 told by bhaveshbhai
                									$zipper_name=$spout_name=$acc_name=$valve_name='';
                
                									
                									if($product_code_data['valve']=='With Valve')
                
                										$valve_name=$product_code_data['valve'];
                
                									if($product_code_data['zipper_name']!='No zip')
                
                										$zipper_name=$product_code_data['zipper_name'];
                
                									if($product_code_data['spout_name']!='No Spout')
                
                										$spout_name=$product_code_data['spout_name'];
                									
                									if($product_code_data['product_accessorie_name']!='No Accessorie')	
                										
                										$acc_name=$product_code_data['product_accessorie_name'];
                                                    if($accessorie_second!='')
                                                        $acc_name.=' '.$accessorie_second;
                									if($invoice['product_code_id']!='-1' && $invoice['product_code_id'] !='0')
                
                										$get_size = $this->getSizeDetail($product_code_data['product'],$product_code_data['zipper'],$product_code_data['volume'],$product_code_data['measurement']);
                
                									else
                
                										$get_size = array('size_master_id'=> '',
                
                															'product_id'=>'',
                
                															'product_zipper_id'=>'',
                
                															'volume'=>'',
                
                															'width'=>'',
                
                															'height'=>'',
                
                															'gusset'=>'',
                
                															'weight'=>'');
                
                										//printr($get_size);
                                                    	if($product_code_data['product'] == 10)
                                                            	    $mes_size='inch';
                                                            	 else
                                                            	     $mes_size='mm';
                										if($product_code_data['product'] == 3 || $product_code_data['product'] == 8)
                
                										{
                
                											$gusset = floatval($get_size['gusset']).'+'.floatval($get_size['gusset']);
                
                										}
                
                										else
                
                										{
                
                											$gusset = floatval($get_size['gusset']);
                
                										}
                
                									$measure = $this->getMeasurementName($invoice['measurement']); 
                
                									$html .='<tr><td>'.$n.'</td>';
                
                										
                
                										$clr_text='';
                										if($invoice['product_code_id']=='-1')
                
                										{
                
                											$clr_nm = 'Custom';
                
                											$custom_pro_id = 1;
                
                											$clr_text = "(".$invoice['color_text'].")";
                
                											$p_nm = 'Custom';
                
                											$size_product = '</b> ('.$invoice['size'].' '.$measure['measurement'].')';
                
                										}
                
                										elseif($invoice['product_code_id']=='0')
                
                										{
                
                											$clr_nm = 'Cylinder';
                
                											$p_nm = 'Cylinder';
                
                											$size_product = '</b> ('.$invoice['size'].' '.$measure['measurement'].')';
                
                										}
                
                										else
                
                										{
                
                											$clr_nm = $product_code_data['color'];
                                                            $p_nm = $product_code_data['product_name'];
                                                            $haystack = strtoupper ($product_code_data['product_code']);
                                                            $needle   = strtoupper ("cylinder");
                                                            
                                                            if( strpos( $haystack, $needle ) !== false ) {
                                                                $p_nm = 'Cylinder';
                                                            }
                                                            
                										
                
                											$size_product = '</b>'.floatval($get_size['width']).' '.$mes_size.' &nbsp;Width &nbsp;X&nbsp;'.floatval($get_size['height']).' ' .$mes_size.' &nbsp;Height &nbsp;';
                                                            
                										}
                                                        if($product_code_data['width']!=0 || $product_code_data['height']!=0)
                                                        {
                                                            
                                                       
                                                            
                                                      //      echo $product_code_data['gusset'];
                			                                if($product_code_data['gusset']!=0)
                			                                {
                			                                	if($product_code_data['product'] == 3)
                			                                	{
                
                											        $gusset = floatval($product_code_data['gusset']).'+'.floatval($product_code_data['gusset']);
                
                										        }
                
                        										else
                        
                        										{
                        
                        											$gusset = floatval($product_code_data['gusset']).'Gusset';;
                        
                        										}
                			                                }
                			                                else
                			                                    $gusset=0;
                			                                	$size_product = '</b>' . floatval($product_code_data['width']) . ''.$mes_size.' &nbsp;Width &nbsp;X&nbsp;' . floatval($product_code_data['height']) . ' '.$mes_size.' &nbsp;Height &nbsp;';
                			                                	
                                                        }
                				                        
                										if($invoice['color_text']!='')
                                                                $clr_text = "(".$invoice['color_text'].")";
                                                        
                									    $pro_code = '';
                									    
                									        $pro_code = $product_code_data['product_code'].'<br>';
                									    
                									   if($invoice['product_code_id']!='3254' && $invoice['product_code_id']!='1194') 
                									   {
                									        
                    									    
                    									       if(isset($set_user_id) && !empty($set_user_id) && (($set_user_id!='37')))
                        								       {
                        								         if($product_code_data['product'] == 6){
                        								             if($product_code_data['width']>0)
                            									        $html .='<td>'.$pro_code.'<b>Size : </b>'.floatval($product_code_data['width']).'mm &nbsp; Roll Width';
                            									     else
                            									       $html .='<td>'; 
                        								         }
                            									 else{
                            									     if(($product_code_data['product'] != '37' && $product_code_data['product'] != '38') && (isset($set_user_id) && !empty($set_user_id) || ( $set_user_id!='19' && $product_code_data['product'] != '51')))
                            									        $html .='<td>'.$pro_code.'<b>Size : '.$size_product;
                            									     else
                            									        $html .='<td>';
                            									    }    
                            											if($gusset>0)
                            
                            												$html .='X&nbsp;'.$gusset.' mm Gusset';
                            
                            											if($get_size['volume']>0 && $product_code_data['width']==0 && $product_code_data['height']==0 && $product_code_data['gusset']==0 ){
                            											    
                            											    // change by sonu 06-06-2018 told by gopidii
                                    											    $e_volume="";
                                                                            	   
                                                                            	    if($get_size['volume']=='50-70 gm')
                                                                            	        $e_volume="BIG";
                                                                            	    if($get_size['volume']=='30-50 gm')
                                                                            	        $e_volume="SMALL";
                                                                            
                                                                            
                                                                     
                            												if($product_code_data['product'] != '37' && $product_code_data['product'] != '38')
                            												    $html .=' ('.$get_size['volume'].') '.$e_volume.' ';
                            												         //end  
                            											
                            											}
                        								       }
                                                            else
                                                            {
                                                                $html .='<td>';
                                                            }
                    											
                                                        if($product_code_data['product'] == 6)
                    										$html .='<br><b>Make up of pouch :</b>'.$p_nm.'<b>&nbsp;<br>';
                    									else
                    									{
                    									    if( strpos( $haystack, $needle ) !== false ) 
                    									  {
                    									      $html .='<br>';
                    									  }
                    									  else
                    									  {
                    									       if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id!='37'))
                    									       {
                        									        if($product_code_data['product'] != '37' && $product_code_data['product'] != '38')
                        									            $html .='<br><b>Make up of pouch :</b>'.$p_nm.'<b>&nbsp;'.$zipper_name.'&nbsp;'.$valve_name.' &nbsp;'.$spout_name.' &nbsp;'.$acc_name.'</b><br>';
                        									        else
                        									           $html .='<br>'; 
                    									       }
                    									  }
                    									}
                    											//printr($clr_text);
                                                          
                                                        if($invoice['filling']!='')
                                                        {
                    											if($product_code_data['product']=='1')
                    											    $html.='<br><b>Sealing Option: </b>'.$invoice['filling'].' - '.$invoice['prodes'].'<br>';
                    											else
                    											   $html.='<br><b>Filling Option: </b>'.$invoice['filling'].' - '.$invoice['prodes'].'<br>'; 
                                                        }
                    									 if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id!='37'))
                    									 {
                        									 if($product_code_data['product'] != '37' && $product_code_data['product'] != '38')
                        									 {
                            									 if( strpos( $haystack, $needle ) !== false ) 
                            									    $html .='<b>'.$clr_text.'<br></b>';
                            									 else
                            									 {
                            										if($invoice['stock_print']=='Digital Print')
                            										    $html .='<b>Color : '.$clr_nm.' <br></b>';
                            										else
                            										    $html .='<b>Color : '.$clr_nm.'&nbsp;   '.$clr_text.'<br></b>';
                            									 }
                        									 } 
                    									 }
                        							  }
                        							  else
                        							     $html .='<td>';
                        									   $des ='';
                        						
                                                       if($invoice['prodes']!='') 
                                                            $des =  ' ( '.$invoice['prodes'].' )<br>';
                										
                										if($invoice['description']!='')
                                                           
                										{
                										  //  printr($invoice['description']);
                                                          if($product_code_data['product'] != '37' && $product_code_data['product'] != '38')
                                                          {
                										    if($invoice['stock_print']=='Digital Print')
                										        $html .='<b>Description : </b>'.$invoice['color_text'];
                										    else
                										        $html .='<b>Material Description : </b>'.$invoice['description'];
                                                          }
                                                          else
                                                            $html .='<b>Product Description : </b>'.$invoice['description'];
                										}
                                                        if($invoice['prodes']!='')
                                                        {
                                                            if( isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='19')){
                            									  if($product_code_data['product'] != '51')
                            									       $html .='</br><b>Material : </b>'.$invoice['prodes'];
                        									}
                                                            else
                                                                $html .='</br><b>Description : </b>'.$invoice['prodes'];
                                                        }
                                                        
                										$html .='</br><b><span style="float: right;">HSN Code : '.$product_gst_details['hsn_code'].'</span></b>';
                										
                									$cust = strtoupper ($product_code_data['product_code']);
                                                    $code_cust   = strtoupper ("CUST");	
                                                    $silica_nfg   = strtoupper ("NFG");	
                                                    $silica_contg   = strtoupper ("CONTAINERG");	
                									if($invoice['plate']!=0 && $invoice['stock_print']=='Digital Print')
                									   $html .='<br/><br/><br/><div style="vertical-align: bottom;"><b>Plates For Digital Printing</b> </div>';
                									if($invoice['plate']!=0 && $invoice['stock_print']=='Foil Stamping')
                									   $html .='<br/><br/><br/><div style="vertical-align: bottom;"><b>Plates For Foil Stamping</b> </div>';
                									 if($invoice['tool_price']!='0')
                										$html .='<br/><div style="vertical-align: bottom;"><b>Tool Price : </b><br></div>';
                									
                									$html .='</td><td><br><br><div align="center">';
                									$total = $total+$invoice['quantity'] ;
                        							$total_qty = $invoice['quantity'];
                                                    if($product_code_data['product'] == 6)
            									    	$html .=$total_qty.'  Kgs</div><br>';
            									    else
            										{   if( strpos( $cust, $silica_nfg ) !== false || strpos( $cust, $silica_contg ) !== false)
            											         $html .=number_format($total_qty,"0", '.', '').' Kgs</div><br>';
            												else
            												    $html .=number_format($total_qty,"0", '.', '').'</div><br>';
            										}
                									if($invoice['plate']!=0)
                									{
                									   $total = $total+$invoice['plate'] ;
                									   $html .='<br/><div align="center" style="vertical-align: bottom;">'.$invoice['plate'].'</div>';
                									}
                									
                									$html .='</td><td><br><br><div align="right">';
                
                										$total_rate=$total_rate+$invoice['rate'];$total_rt = $invoice['rate'];
                                                        
                                                        if($product_code_data['product'] == 6)
                										    $html .=$total_rt.' Per 1 Kgs</div><br>';
                										else
                										    $html .=$total_rt.'</div><br>';
                									
                									if($invoice['plate']!=0 && $invoice['stock_print']=='Digital Print')
                									{
                									   $total_rate=$total_rate+$user_info['color_plate_price'];
                									   $html .='<br/><div align="right" style="vertical-align: bottom;">'.$user_info['color_plate_price'].' Per 1 Plate</div>';
                									}
                									if($invoice['plate']!=0 && $invoice['stock_print']=='Foil Stamping')
                									{
                									   $total_rate=$total_rate+$user_info['foil_plate_price'];
                									   $html .='<br/><div align="right" style="vertical-align: bottom;">'.$user_info['foil_plate_price'].' Per 1 Plate</div>';
                									}
                									$html.='</td><td><br><br><div align="right">';
                
                										$total_amnt = $invoice['quantity'] * $invoice['rate'];
                										$ex_amt=0;
                										
                										$html.= $total_amnt.'</div><br>';
                										$final_total=$final_total+$total_amnt+$ex_amt;
                                                        
                                                    if($invoice['plate']!=0 && $invoice['stock_print']=='Digital Print')
                									{
                									   $final_total=$final_total+($user_info['color_plate_price']*$invoice['plate']);
                									   $html .='<br/><div align="right" style="vertical-align: bottom;">'.$user_info['color_plate_price']*$invoice['plate'].'</div>';
                									} 
                                                    if($invoice['plate']!=0 && $invoice['stock_print']=='Foil Stamping')
                									{
                									   $final_total=$final_total+($user_info['foil_plate_price']*$invoice['plate']);
                									   $html .='<br/><div align="right" style="vertical-align: bottom;">'.$user_info['foil_plate_price']*$invoice['plate'].'</div>';
                									} 
                                                    if($invoice['tool_price']!='0')
                									{
                										$html .='<br><div align="right" style="vertical-align: bottom;">'.$invoice['tool_price'].'</div>';
                										$final_total=$final_total+$invoice['tool_price'];
                									}
                    							    $html .='</td>';
                								    if ($proforma['taxation'] != 'sez_no_tax') {
                                                        
                                                                if($proforma['taxation'] == 'With in Gujarat'){
                                                                    $sgst_amt = (($total_amnt *$product_gst_details['sgst_percentage'])/100 );
                                                                    $cgst_amt = (($total_amnt *$product_gst_details['cgst_percentage'])/100 );
                                                                    $html .='<td><div align="center">'.$product_gst_details['sgst_percentage'].' %</div></td>
                                                                            <td><div align="center">'.(($total_amnt *$product_gst_details['sgst_percentage'])/100 ).'</div></td>
                                                                            <td><div align="center">'.$product_gst_details['cgst_percentage'].' %</div></td>
                                                                            <td><div align="center">'.(($total_amnt *$product_gst_details['cgst_percentage'])/100 ).'</div></td>';
                                                                    //printr($sgst_amt);
                                                                    //printr($cgst_amt);
                                                                    $tax_sgst += $sgst_amt;
                                                                    $tax_cgst += $cgst_amt;
                                                                    $tax_price = $sgst_amt+ $cgst_amt;
                                                                }
                                                                else
                                                                {
                                                                    $igst_amt = (($total_amnt *$product_gst_details['igst_percentage'])/100 );
                                                                    $html .='<td><div align="center">'.$product_gst_details['igst_percentage'].' %</div></td>
                                                                            <td><div align="center">'.(($total_amnt *$product_gst_details['igst_percentage'])/100 ).'</div></td>';
                                                                    $tax_price =$igst_amt; 
                                                                }
                                                        $amount_with_tax = $tax_price+$total_amnt;
                							            $html .='<td><div align="center">'.$amount_with_tax.'</td>';
                							            $total_amount_with_tax += $amount_with_tax;
                                                    }
                							
                							$html.='</tr>';
                                                
                                                $total_tax_price += $tax_price;
                									$n++;
                					    
                					}
                                   // printr($tax_sgst);
                                } //printr($tax_sgst);
                                $freight_total_amount_with_tax = $freight_sgst_amt = $freight_cgst_amt = $freight_tax_price=$freight_igst_amt=$freight_amount_with_tax=$freight_tax_cgst=$freight_tax_sgst = 0;0;
                                if($proforma['freight_charges']!=0)
                                {
                                    $freight_charges=round($proforma['freight_charges'],3);

										$final_total=$final_total+$freight_charges;
                                    $freight_gst_details = $this->getProudctGSTDetails(0);
									//printr($freight_gst_details);

									$html .='<tr>

											<td></td>

												<td><strong>Freight Charges </strong><b><span style="float: right;">HSN Code : '.$freight_gst_details['hsn_code'].'</span></b></td>
                                                <td><b></b></td>
                                                <td></td>

												<td><p align="right">'.$freight_charges.'</p></td>';
												if ($proforma['taxation'] != 'sez_no_tax') {
                                                        
                                                    if($proforma['taxation'] == 'With in Gujarat'){
                                                        $freight_sgst_amt = (($proforma['freight_charges'] *$freight_gst_details['sgst_percentage'])/100 );
                                                        $freight_cgst_amt = (($proforma['freight_charges'] *$freight_gst_details['cgst_percentage'])/100 );
                                                        $html .='<td><div align="center">'.$freight_gst_details['sgst_percentage'].' %</div></td>
                                                                <td><div align="center">'.(($proforma['freight_charges'] *$freight_gst_details['sgst_percentage'])/100 ).'</div></td>
                                                                <td><div align="center">'.$freight_gst_details['cgst_percentage'].' %</div></td>
                                                                <td><div align="center">'.(($proforma['freight_charges'] *$freight_gst_details['cgst_percentage'])/100 ).'</div></td>';
                                                        $freight_tax_price = $freight_sgst_amt+ $freight_cgst_amt;
                                                        $freight_tax_sgst +=$freight_sgst_amt;
                                                        $freight_tax_cgst +=$freight_cgst_amt;
                                                    }
                                                    else
                                                    {
                                                        $freight_igst_amt = (($proforma['freight_charges'] *$freight_gst_details['igst_percentage'])/100 );
                                                        $html .='<td><div align="center">'.$freight_gst_details['igst_percentage'].' %</div></td>
                                                                <td><div align="center">'.(($proforma['freight_charges'] *$freight_gst_details['igst_percentage'])/100 ).'</div></td>';
                                                        $freight_tax_price =$freight_igst_amt; 
                                                    }
                                                    $freight_amount_with_tax = $freight_tax_price+$proforma['freight_charges'];
            							            $html .='<td><div align="right">'.$freight_amount_with_tax.'</td>';
            							            $freight_total_amount_with_tax += $freight_amount_with_tax;
                                                }

									$html .='</tr>';
									$tax_sgst +=$freight_tax_sgst;
									$tax_cgst +=$freight_tax_cgst;
                                }
                            $total_amount_with_tax +=$freight_total_amount_with_tax;
                			$tax_td ='';
                              if ($proforma['taxation'] != 'sez_no_tax') {
							    if($proforma['taxation'] == 'With in Gujarat'){
                                    $tax_td = '<td></td>
                                            <td>'.$tax_sgst.'</td>
                                            <td></td>
                                            <td>'.$tax_cgst.'</td>';
                                }
                                else
                                {
                                    $tax_td ='<td></td>
                                            <td>'.$total_tax_price.'</td>';
                                }
                                $tax_td .='<td><div align="right">'.round($total_amount_with_tax).'</div></td>';
		                    }
                                $html.='<tr>

										<td></td>

										<td><div align="right"><strong>Sub Total</strong></div></td>

										<td>&nbsp;</td>

										<td>&nbsp;</td>

										<td><div align="right">'.round($final_total,3).'</div></td>';
										
                                            $html .=$tax_td;
					                    

									  $html .='</tr>';
									$dis_total=($total_amount_with_tax*$proforma['discount'])/100;
                                    $final_total=$total_amount_with_tax-$dis_total;

									
 
									if($dis_total!=0)
									{
    									$html.='<tr>
    
    										<td></td>
    
    										<td><div align="right"><strong>Discount</strong>( '.($proforma['discount'] + 0).' % )</div></td>
    
    										<td>&nbsp;</td>
    
    										<td>&nbsp;</td>
    
    										<td><div align="right">'.round($dis_total,3).'</div></td>
    
    									  </tr>';
                                         $html.='<tr>

    										<td></td>
    
    										<td><div align="right"><strong>Sub Total</strong></div></td>
    
    										<td>&nbsp;</td>
    
    										<td>&nbsp;</td>
    
    										<td>'.round($final_total,3).'</div></td>
    
    									  </tr>';
									}

								$html.='<tr>

									<td></td>

									<td></td>

									<td><p align="center"><b>'.$total.'</b></p></td>

									<td colspan="2"><p align="right">Total('.$currency['currency_code'].')</p></td>';
                                          
                                    if ($proforma['taxation'] != 'sez_no_tax') {
        							    if($proforma['taxation'] == 'With in Gujarat'){
                                            $html.='<td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>';
                                        }
                                        else
                                        {
                                            $html.='<td></td>
                                                    <td></td>';
                                        }
                                        $html.='<td><div align="right"><b>'.round($total_amount_with_tax).'</b></div></td>';
        		                    }
                                        
                                  $html.='</tr>';

							
								if($proforma['packing_charges']!=0)
								{
									$packing_charges=round($proforma['packing_charges'],3);
									if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']!=111)
									{
										$final_total=$final_total;
									}
									else
									{
										$final_total=$final_total+$packing_charges;
									
										$html .='<tr>
												<td></td>
													<td><div align="right">
															<strong>Packing Charges </strong>
															</div></td>
														 <td><b></b></td>
													<td></td>
													<td><p align="right">'.$packing_charges.'</p></td>
											  </tr>';
									}
								
								    
								}
							$html .='</tbody>

							</table>

						</div>';

 	 						$number = $this->convert_number(round($final_total)); //printr($number);
                            $amt = "UPDATE `" . DB_PREFIX . "proforma_product_code_wise` SET invoice_total = ".round($final_total)." WHERE proforma_id=".$proforma_id;
                            $number = $this->convert_number(round($final_total));
							$this->query($amt);
  						
                         
		                    
  						
            $html .='</div>';
									

			$html .='<div  class="" style=" width: 100%;float: left;  border: 1px solid black;font-size: 16px;">

							<table cellspacing="0px" cellpadding="10px" border="1" style=" width: 100%; border-spacing: 0px;">

								<tbody>';
							$html .='<tr><td colspan="2" valign="top"><strong>Amount Chargeable(In Words): '.$number.' {'.$currency['currency_code'].'}</strong></td></tr>';
							
        	$html .='<tr>'; 	   
        	foreach($proforma_inv as $pro_inv)
        	{
        		$string1=$pro_inv['product_code'];					
        		 $remark= substr($string1,0,4);
        		
        	}
	        $html .='<td valign="top" width="50%"><div><strong>Declaration:</strong><br>We declare that this Invoice shows the actual price of the <br>goods described and that all particular are true and <br>correct.<br>';
			
    	
    		
    		    if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id!='37' && $set_user_id!='38' && $set_user_id!='39'))
    		    {
    		        $html .='<strong>Delivery schedule:</strong><br> All stock pouches will be ready in 10-15 days after the total invoice amount is transferred..<br> If the goods are ready we will send it asap. <br>Some colors or sizes can even take few days more for production.<br><b style="color:red"> NOTE : OUR ALL CONSIGNMENTS WILL COME "TO PAY"...<br>Please double check the color and size of product(s) before approval of proforma invoice; Hence company will not responsible for any change.</b></b>';
        			if($custom_pro_id=='1')
                	{
                        $html.='<br><br><b style="color:red">NOTE : Please note that in commercial production there can be variances in total output.  For a production run of 10,000 units, there can sometimes be a maximum over/under production variance of 4500 pouches per 10,000 bags. In such an event we will always provide a refund for any difference where we have a shortfall of pouches. However, in an instance where we have over-production upto the variance levels mentioned above, we ask that you kindly commit to pay for the additional volume. Please note that because of the commercial nature of the print process, sometimes slight shift registrations can occur.</b>';
                    }
    		    }
    		    else
    		    {
    		       if(isset($set_user_id) && !empty($set_user_id) && ($set_user_id=='37' || $set_user_id=='38'))
    		       {
    		           $html .='<strong>Terms & Condition : </strong><br> Our responsibility cease as soon as goods leaves our premises</br> Subject to Vadodara Jurisdiction</br> E. & O. E.<br>';
    		       }
    		       else
    		       {
    		           $html .='<br><b style="color:red"> NOTE : <b>GOODS ONCE SOLD WILL NOT BE ACCEPTED BACK.</b><br>
    		                                                     FRIEGHT CHARGES WILL BE PAID BY THE CUSTOMERS.<br>
                                                                  Please double check the size of product(s) before<br>
                                                                approval of proforma invoice. Hence company will not<br>
                                                                    responsible for any change.</b>';    
    		        }
    		       $html.='<br><br><b style="color:red">NOTE : This is system generated invoice so does not required Signature .</b>'; 
    		    }
        	$html .='</div></td>
    
    		<td valign="top" class="sign_td">
    
    		<table border="0" align="right"  cellspacing="0px" cellpadding="0px" style="width: 100%;border-spacing: 0px;" >
    
    		<tr>
    
    			<td width="50%"><br>For <strong>';//printr($proforma['gen_pro_as']);
    			
    			$html.=$sign;
    			$html.='</strong><br>
    
    			<p style="text-align:right;margin-top:20px;margin-bottom:0;padding:0px;">';
    			
    			$html.=$user_name['first_name'].' '.$user_name['last_name'];
    			    
    			   $html.='</p><hr/>
    
    				<p id="prefix" style="text-align:right;float:right;" >Authorised Signatory</p>
    
    			</td>
    
    		</tr> 
    
    	</table></td>
    
    	</tr></tbody></table></div>';
        
       
        $Beneficiary_add = '<tr>

							<td><b>Bank Address </b></td>

							<td >'.$proforma['benefry_add'].'</td>

						</tr>';
	    $IFSC = '<tr>
		
						<td><b>IFSC Code</b></td>

						<td>'.$proforma['swift_cd_hsbc'].'</td>

					</tr>';

		$MICR='		<tr>

						<td><b>MICR Code</b></td>

						<td>'.$proforma['micr_code'].'</td>

				</tr>';	
	    if(isset($set_user_id) && !empty($set_user_id) && $set_user_id!='39')
    	{
            $Beneficiary_bank_add = '<tr>				<td><b>Beneficiary Bank Address</b></td>
        
        												<td>'.$proforma['benefry_bank_add'].'</td>
        
        											</tr>';
    	}
    	$Intermediary_Bank_Name='<tr>
    									<td><b>Intermediary Bank Name</b></td>
    
    									<td>'.$proforma['intery_bank_name'].'</td>
    
    								</tr>';	
    	$Intermediary_Bank ='<tr>
    
    								<td><b>Intermediary Bank</b></td>
    
    								<td>'.$proforma['hsbc_accnt_intery_bank'].'</td>
    
    								</tr>';
    	$Swift_Code_of_Intermediary_Bank ='<tr>
    
    								<td><b>Swift Code of Intermediary Bank</b></td>
    
    								<td>'.$proforma['swift_cd_intery_bank'].'</td>
    
    								</tr>';
     						
        $Intermediary_Bank_ABA_Routing_Number ='<tr>
    
    							<td><b>Intermediary Bank ABA Routing Number</b></td>
    
    							<td>'.$proforma['intery_aba_rout_no'].'</td>
    
    						</tr>';
        $swift_code='';
        
        $payment_mode='';
        if($proforma['customer_bank_detail']=='0' )
        {   $page_style='';
        
            $curr_code = '<b>'.$currency['currency_code'].'</b>';
            
            $html .='<div class="" style=" width: 100%;float: left;  border: 1px solid black;page-break-before: always;font-size: 16px;'.$page_style.'">';
                    	$html .=	$payment_mode;
            						$html.='	<table cellspacing="0px" cellpadding="10px" border="1" style=" width:100%;">
            
            								<tbody><tr>
            
            									<td valign="top" colspan="2"><h1 align="center">BANK DETAIL</h1></td>
            
            								</tr>
            
            								<tr>
            
            	 								<td colspan="2">'.$curr_code.'</td>
            
            								</tr>
            
            								<tr>
            
            									<td><b>Beneficiary Name</b></td>
            
            									<td >'.$proforma['bank_accnt'].'</td>
            
            								</tr>
                                            <tr>
            
            									<td><b>Bank Name</b></td>
            
            									<td>'.$proforma['benefry_bank_name'].'</td>
            
            								</tr>';
            						
            							        	$html.=$Beneficiary_add;
            
            								
                                            
                                               	$html .='<tr>
                
                									<td><b>Name Of The Branch</b></td>
                
                									<td>'.$proforma['branch_nm'].'</td>
                
                								</tr>';
                								
                                               	$html .='<tr>
                
                									<td><b>Type Of Account</b></td>
                
                									<td>'.$proforma['type_of_accnt'].'</td>
                
                								</tr>';
                                          
            								
            								$html .='<tr>
            
            											<td><b>Account Number</b></td>
            
            											<td>'.$proforma['accnt_no'].'</td>
            
            										</tr>';
            
            
            								//$html .='</tr>';
            								
            								
            								
            
            										$html .=$IFSC;
            										$html .=$MICR;
            								
            									$html .=$Beneficiary_bank_add;
            
            							 
            
            								
                                    
            						if($proforma['intery_bank_name']!=''){ 
            
            						    $html .=$Intermediary_Bank_Name;
            
            						}
            
            						if($proforma['hsbc_accnt_intery_bank']!=''){ 
            
            						    $html .=$Intermediary_Bank;
            
            						}
            
            						if($proforma['swift_cd_intery_bank']!=''){ 
            
            						    $html .=$Swift_Code_of_Intermediary_Bank;
            
            						}
            					
                                    $html.= $swift_code;
            						if($proforma['hsbc_accnt_intery_bank']!=''  && $user['country_id']!='11' ){ 
            
            						    $html .=$Intermediary_Bank_ABA_Routing_Number;
            
            						}
            
            					$html .='</tbody></table>';
            			
            					$html .='</div>';
            
        }
	    
		return $html ;
 
	}
    public function getProudctGSTDetails($product_id)
    {   $data =$this->query("SELECT * FROM product_gst_master WHERE find_in_set(".$product_id.",product_id) <> 0");
        if($data->num_rows){
            return $data->row;
        }else{
            return false;
        }
    }
    public function getSalesInvoiceDetails($pro_no)
    {  
      	$sql="SELECT * FROM sales_invoice WHERE proforma_no='".$pro_no."' AND is_delete=0 AND status=1";
		$data = $this->query($sql);
		if($data->num_rows){
            return $data->row;
        }else{
            return false;
        }
    }
    public function getUserPermission($menu_id,$n=0)
	{
		$menu = implode('|',$menu_id);
		
		$sql = "SELECT email,user_name,user_type_id,user_id FROM " . DB_PREFIX ."account_master WHERE add_permission REGEXP '".$menu."' OR edit_permission REGEXP '".$menu."' OR delete_permission REGEXP '".$menu."' OR view_permission REGEXP '".$menu."'";
		$data = $this->query($sql);
		return $data->rows;
	}
	function send_commission_report($data,$url)
	{
	    $html = 'Please find attached Commission Report.';
    	$to_email=array('html'=>$html,'email'=>$data['email']);
	    
	    $signature = '<br> Thank you <br> kind Regards, <br> SWISS PAC PVT LTD';
		$subject ='Commission Calculation Report From : '.dateFormat(4,$data['f_date']).'  To :  '.dateFormat(4,$data['t_date']); 
		
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(7); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				
		$path = HTTP_SERVER."template/proforma_invoice.html";
		$output = file_get_contents($path);  
		$search  = array('{tag:header}','{tag:details}');
		
		$message = '';
		if($html)
		{
			$tag_val = array(
			"{{header}}"=>$subject,
				"{{PouchMakersDetail}}" =>$html,
				"{{signature}}"	=> $signature,
			);
			if(!empty($tag_val))
			{
				$desc = $temp_desc;
				foreach($tag_val as $k=>$v)
				{
					@$desc = str_replace(trim($k),trim($v),trim($desc));
				} 
			}
			$replace = array($subject,$desc);
			$message = str_replace($search, $replace, $output);
		}
		//printr($message);
		//printr($to_email);
		send_email_test($to_email,ADMIN_EMAIL_QUO,$subject,$message,'',$url,'','','','xls');
	}
} ?>