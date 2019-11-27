<?php
class pdf_pricelist extends dbclass{	


public function getcountry($data)
	{
		
		$sql = "SELECT country_name,country_id  FROM `" . DB_PREFIX . "country` WHERE country_id=14 OR country_id=42 OR country_id= 214 ";
		
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
			$sql = "SELECT country_name,country_id,currency_code FROM `" . DB_PREFIX . "country` WHERE country_id='".$country_id."'";			
			$data = $this->query($sql);
			
					if($data->num_rows){
						return $data->rows;
					}else{
						return false;
					}
					
}
	
public function getTotalcountry()
	{
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "country` WHERE country_id=14 OR country_id=42  OR country_id= 214 ";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	//public function getSelectedCurrency($country_id)
//	{
//		$sql = "SELECT currency_code  FROM " . DB_PREFIX . "currency   WHERE is_delete = '0' LIMIT 1";	
//		$data = $this->query($sql);
//		
//		$result =$data->row;
//		
//		
//		return $result;
//		
//	}

public function getProductTemplateDetails($color_category,$product_name,$zipper,$volume,$height,$width,$gusset,$accesories,$spout='')
{
 
	if(isset($accesories) && $accesories=='Die cut Handle' )
	{
    	$accesories= 'Die cut Handle';
	}
	else if($accesories=='Euro hole or Round')
	{
		$accesories='Euro hole or Round';	
	}
	else
	{
	   $accesories='No Accessorie';
	}
	$and='';
    if(isset($spout) && $spout=='Spout 10mm' )
	{
	    
	    $and="AND ptz.spout='Spout 10mm' ";
	}
	else
	{
	     $and="AND ptz.spout='No Spout' ";
	}
	
		

	
$sql2="	SELECT ptz.*,pt.* FROM product_template_size as ptz,product_template as pt WHERE pt.product_name='".$product_name."' AND ptz.zipper ='".$zipper."' AND ptz.valve='no Valve' AND ptz.accessorie ='".$accesories."' AND ptz.volume='".$volume."' AND ptz.width='".$width."'  AND ptz.height='".$height."'AND ptz.gusset='".$gusset."' AND pt.transportation_type='By Air' AND pt.user='7' AND pt.product_template_id=ptz.template_id AND color_category_id ='".$color_category."'$and";

   
	$data = $this->query($sql2);
	 //printr($sql2);  
//    printr($data);//die;
		 if($data->num_rows){
					return $data->row;
			
			}else{
					return false;
				 }
		   
}

public function getValvePrice()
{ 
    $sql="SELECT valve_fitting_price,international_branch_id FROM international_branch WHERE international_branch_id=7 OR international_branch_id=24 OR international_branch_id=27  AND is_delete=0 ";
    $data=$this->query($sql);
    
     if($data->num_rows)
     {
	    return $data->row['valve_fitting_price'];
	}else{
		return false;
	}

}

 public function viewPdfData($country_id)
	{
	
		$html=''; 
		$note='';
		$currency=$this->getcountry_name($country_id);
		$valve_price=$this->getValvePrice();
	    $note='Please add '.$currency[0]['currency_code'].'$ '.$valve_price.' extra on above price for VALVE fitting for Coffee Packaging';

		//$data=getProductTemplateDetails($country_id,$product_name,$zipper,$volume,$height,$width,$gusset);
		//$html.='<link rel="stylesheet" type="text/css" href="style.css"> ';                  
  		
		$html.='<div class="panel-body" id="" style="padding-top: 0px;width:100%">
				<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/page-0.jpg"" style="width:100%;">
				</div>';
				//$html .='<p style="page-break-before:always;">';
		$html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/page-1.jpg"" style="width:100%;">
				</div>';

		$html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/2.1.jpg"" style="width:100%;">
					<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:13px" class="">
						<tr align="center">
							<th rowspan="3" colspan="2" style="font-size:15px"><strong>SUP Pouch With <br>Normal Zipper</strong></th>
							<th rowspan="3" style="font-size:13px"><strong>Dimension<br>W X L X G (mm)</strong></th>
							<th colspan="4" style="font-size:13px"><strong>Price Per</strong></th>
						</tr>';
					
		            $html.='<tr align="center">
            					<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            					<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            					<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            					<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
			            	</tr>
			            	
						    <tr align="center">
        						<td style="color: #d72a35;"><strong>Qty +10000</strong></td>
        						<td style="color: #d72a35;"><strong>Qty +5000</strong></td>
        						<td style=" color: #d72a35;"><strong>Qty +2000</strong></td>
        						<td style="color: #d72a35;"><strong>Qty +1000</strong></td>
						    </tr>';
									
					$html.='<tr align="center">';
					//$row1 = $this->getProductTemplateDetails(214,3,'With Zipper','28 gm',130,80,25);
					       $row1 = $this->getProductTemplateDetails(2,3,'No zip','28 gm',130,80,25,'No Accessorie','No Spout');
					       $html.='<td style="font-size:12px"><strong>SUP Pouch With No Zipper</strong></td>
    							   <td><strong>28gms</strong></td>
        						   <td><strong>80 x 130 x 50</strong></td>
        						   <td>'.$row1['quantity1000'].'</td>
        						   <td>'.$row1['quantity2000'].'</td>
        						   <td>'.$row1['quantity5000'].'</td>
        						   <td>'.$row1['quantity10000'].'</td>
						    </tr>';
						    
					$html.='<tr align="center">';
				            $row2 = $this->getProductTemplateDetails(2,3,'With Zipper','28 gm',130,80,25,'No Accessorie','No Spout');
    					    $html.='<td rowspan="10" align="center" style="font-size:15px"><strong>SUP Pouch <br>With Zipper</strong></td>
        							<td><strong>28gms</strong></td>
        							<td><strong>80 X 130 X 50</strong></td>
        							<td>'.$row2['quantity1000'].'</td>
        							<td>'.$row2['quantity2000'].'</td>
        							<td>'.$row2['quantity5000'].'</td>
        							<td>'.$row2['quantity10000'].'</td>
						    </tr>';
						
					$html.='<tr align="center">';
				        	$row3 = $this->getProductTemplateDetails(2,3,'With Zipper','50 gm',150,95,30,'No Accessorie','No Spout');
        					$html.='<td><strong>50gms</strong></td>
        							<td><strong>95 X 150 X 60</strong></td>
        							<td>'.$row3['quantity1000'].'</td>
        							<td>'.$row3['quantity2000'].'</td>
        							<td>'.$row3['quantity5000'].'</td>
        							<td>'.$row3['quantity10000'].'</td>
						   </tr>';
						   
					$html.='<tr align="center">';
						    $row4 = $this->getProductTemplateDetails(2,3,'With Zipper','70 gm',170,110,35,'No Accessorie','No Spout');		
        					$html.='<td><strong>70gms</strong></td>
        							<td><strong>110 X 170 X 70</strong></td>
        							<td>'.$row4['quantity1000'].'</td>
        							<td>'.$row4['quantity2000'].'</td>
        							<td>'.$row4['quantity5000'].'</td>
        							<td>'.$row4['quantity10000'].'</td>
						  </tr>';
					$html.='<tr align="center">	';
						    $row5 = $this->getProductTemplateDetails(2,3,'With Zipper','100 gm',200,120,40,'No Accessorie','No Spout');		
							$html.='<td><strong>100gms</strong></td>
        							<td><strong>120 X 200 X 80</strong></td>
        							<td>'.$row5['quantity1000'].'</td>
        							<td>'.$row5['quantity2000'].'</td>
        							<td>'.$row5['quantity5000'].'</td>
        							<td>'.$row5['quantity10000'].'</td>
					    	</tr>';
					    	
					$html.='<tr align="center">';				
						    $row6 = $this->getProductTemplateDetails(2,3,'With Zipper','150 gm',210,130,40,'No Accessorie','No Spout');		
							$html.='<td><strong>150gms</strong></td>
        							<td><strong>130 X 210 X 80</strong></td>
        							<td>'.$row6['quantity1000'].'</td>
        							<td>'.$row6['quantity2000'].'</td>
        							<td>'.$row6['quantity5000'].'</td>
        							<td>'.$row6['quantity10000'].'</td>
						    </tr>';
						    
					$html.='<tr align="center">';	
						    $row7 = $this->getProductTemplateDetails(2,3,'With Zipper','250 gm',230,160,45,'No Accessorie','No Spout');		
							$html.='<td><strong>250gms</strong></td>
        							<td><strong>160 X 230 X 90</strong></td>
        							<td>'.$row7['quantity1000'].'</td>
        							<td>'.$row7['quantity2000'].'</td>
        							<td>'.$row7['quantity5000'].'</td>
        							<td>'.$row7['quantity10000'].'</td>
						   </tr>';
						   
					$html.='<tr align="center">';	   
						    $row8 = $this->getProductTemplateDetails(2,3,'With Zipper','350 gm',250,170,45,'No Accessorie','No Spout');
							$html.='<td><strong>350gms</strong></td>
        							<td><strong>170 X 250 X 90</strong></td>
        							<td>'.$row8['quantity1000'].'</td>
        							<td>'.$row8['quantity2000'].'</td>
        							<td>'.$row8['quantity5000'].'</td>
        							<td>'.$row8['quantity10000'].'</td>
						    </tr>';
						    
					$html.='<tr align="center">	';		
						    $row9 = $this->getProductTemplateDetails(2,3,'With Zipper','500 gm',260,190,55,'No Accessorie','No Spout');		
							$html.='<td><strong>500gms</strong></td>
        							<td><strong>190 X 260 X 110</strong></td>
        							<td>'.$row9['quantity1000'].'</td>
        							<td>'.$row9['quantity2000'].'</td>
        							<td>'.$row9['quantity5000'].'</td>
        							<td>'.$row9['quantity10000'].'</td>
						   </tr>';
					$html.='<tr align="center">';	   
						    $row10 = $this->getProductTemplateDetails(2,3,'With Zipper','750 gm',310,210,55,'No Accessorie','No Spout');	
							$html.='<td><strong>750gms</strong></td>
        							<td><strong>210 X 310 X 110</strong></td>
        							<td>'.$row10['quantity1000'].'</td>
        							<td>'.$row10['quantity2000'].'</td>
        							<td>'.$row10['quantity5000'].'</td>
        							<td>'.$row10['quantity10000'].'</td>
						   </tr>';
						   
					$html.='<tr align="center">	';		
					    	$row11 = $this->getProductTemplateDetails(2,3,'With Zipper','1 kg',335,235,60,'No Accessorie','No Spout');		
							$html.='<td><strong>1 kg</strong></td>
        							<td><strong>235 X 345 X 120</strong></td>
        							<td>'.$row11['quantity1000'].'</td>
        							<td>'.$row11['quantity2000'].'</td>
        							<td>'.$row11['quantity5000'].'</td>
        							<td>'.$row11['quantity10000'].'</td>
						    </tr>';
						    
					$html.='<tr align="center">';			
						    $row12 = $this->getProductTemplateDetails(2,3,'With Zipper','3 kg',500,300,75,'No Accessorie','No Spout');		
							$html.='<td rowspan="2" style="font-size:12px"><strong>SUP Pouch With & without<br/>Die Cut Handle</strong></td>
        							<td><strong>3 kg</strong></td>
        							<td><strong>300 X 500 X 140</strong></td>
        							<td>'.$row12['quantity1000'].'</td>
        							<td>'.$row12['quantity2000'].'</td>
        							<td>'.$row12['quantity5000'].'</td>
        							<td>'.$row12['quantity10000'].'</td>
						    </tr>';
						    
					$html.='<tr align="center">';
			           	    $row13 = $this->getProductTemplateDetails(2,3,'With Zipper','5 kg',550,380,90,'Die cut Handle','No Spout');
							$html.='<td><strong>4 kg</strong></td>
        							<td><strong>380 X 550 X 180</strong></td>
        							<td>'.$row13['quantity1000'].'</td>
        							<td>'.$row13['quantity2000'].'</td>
        							<td>'.$row13['quantity5000'].'</td>
        							<td>'.$row13['quantity10000'].'</td>
						   </tr>
					</table> <br>';
				        $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div>';
					 	$html.='<img src="../upload/admin/template_price_image/pricelist_pdf/demo3.jpg"" style="width:100%;">
				</div> ';
					
		        $html.='<div class="panel-body">
					        <img src="../upload/admin/template_price_image/pricelist_pdf/page-3.jpg"" style="width:100%;">
				        </div>';
				
		        $html.='<div class="panel-body">
					        <img src="../upload/admin/template_price_image/pricelist_pdf/4.2.jpg"" style="width:100%;">
						    <table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:14px" class="">						
						        <tbody>
							        <tr>
                                        <th rowspan="2" colspan="2" ><strong>Three Side Seal With Zipper</strong></th>
                                        <th rowspan="2"><strong>Dimension<br>W X L X G (mm)</strong></th>
                                        <th><strong>Price Per </strong>Pouch In'.' '.' '.$currency[0]['currency_code'].'</th>
                                    </tr>
                            
							        <tr style="text-align:center">
								        <td style="color: #d72a35;">Qty+5000</td>
							        </tr>';
							$html.='<tr style="text-align:center">';
								    $row1 = $this->getProductTemplateDetails(2,4,'With Zipper','1 gm',75,59,0,'No Accessorie','No Spout');
									$html.='<td rowspan="2" style="font-size:12px;" ><strong>Three Side Seal With Zipper</strong></td>
            								<td><strong>1 gm</strong></td>
            								<td><strong>59mm X 75mm</strong></td>
            								<td>'.$row1['quantity5000'].'</td>
							       </tr>';
							$html.='<tr style="text-align:center">';
					            	$row2 = $this->getProductTemplateDetails(2,4,'With Zipper','3 gm',99,72,0,'No Accessorie','No Spout');
									$html.='<td><strong>3 gm</strong></td>
            								<td><strong>72mm X 99mm</strong></td>
            								<td>'.$row2['quantity5000'].'</td>
							       </tr>';
							$html.='<tr style="text-align:center">';      
					        	    $row3 = $this->getProductTemplateDetails(2,4,'With Zipper','3 gm',115,80,0,'Euro hole or Round','No Spout');
								    $html.='<td style="font-size:12px;" ><strong>With Euro Slot</strong></td>
            								<td><strong>3 gm</strong></td>
            								<td><strong>80mm X 115mm</strong></td>
            								<td>'.$row3['quantity5000'].'</td>
							       </tr>
						    </tbody>
					   </table><br><br>';
						    $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/4.1.jpg"" style="width:100%;">
				    </div>';
				
		        $html.='<div class="panel-body">
					    <img src="../upload/admin/template_price_image/pricelist_pdf/5.1.jpg"" style="width:100%;">
					        <table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:14px" class="">
						        <tbody>
							        <tr>
        								<th rowspan="3" colspan="2" style="font-size:15px;" ><strong>Crystal Clear (SUP) <br> With Zipper With Euro Slot</strong></th>
        								<th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
							       </tr>';
							
						$html.='<tr align="center">
        							<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        							<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        							<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        							<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
						        </tr>
							
							   <tr style="text-align:center">
    								<td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
    								<td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
    								<td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
    								<td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
							   </tr>';
					    $html.='<tr style="text-align:center">';
							    $row1 = $this->getProductTemplateDetails(3,27,'No zip','28 gm',130,80,25,'No Accessorie','No Spout');			
								$html.='<td rowspan="5" style="font-size:12px;"><strong>Crystal Clear (SUP) <br> With Zipper With <br> Euro Slot</strong></td>
        								<td width="20%"><strong>28gms (No Zipeer)</strong></td>
        								<td><strong>80 X 130 X 50</strong></td>
        								<td>'.$row1['quantity1000'].'</td>
        						    	<td>'.$row1['quantity2000'].'</td>
        							    <td>'.$row1['quantity5000'].'</td>
        							    <td>'.$row1['quantity10000'].'</td>
							   </tr>';
							   
					    $html.='<tr style="text-align:center">';
							    $row2 = $this->getProductTemplateDetails(3,27,'With Zipper','28 gm',130,80,25,'No Accessorie');			
								$html.='<td><strong>28gms</strong></td>
        								<td><strong>80 X 130 X 50</strong></td>
        								<td>'.$row2['quantity5000'].'</td>
        							    <td>'.$row2['quantity5000'].'</td>
        								<td>'.$row2['quantity5000'].'</td>
        								<td>'.$row2['quantity5000'].'</td>
							    </tr>';
						$html.='<tr style="text-align:center">';	
							    $row3 = $this->getProductTemplateDetails(3,27,'With Zipper','70 gm',170,110,35,'No Accessorie','No Spout');				
								$html.='<td><strong>70gms</strong></td>
        								<td><strong>110 X 160 X 70</strong></td>
        								<td>'.$row3['quantity1000'].'</td>
        						    	<td>'.$row3['quantity2000'].'</td>
        							    <td>'.$row3['quantity5000'].'</td>
        							    <td>'.$row3['quantity10000'].'</td>
							    </tr>';
							    
						$html.='<tr style="text-align:center">';	    
							    $row4 = $this->getProductTemplateDetails(3,27,'With Zipper','150 gm',210,130,40,'No Accessorie','No Spout');		
								$html.='<td><strong>150gms</strong></td>
        								<td><strong>130 X 210 X 80</strong></td>
        								<td>'.$row4['quantity1000'].'</td>
        						    	<td>'.$row4['quantity2000'].'</td>
        							    <td>'.$row4['quantity5000'].'</td>
        							    <td>'.$row4['quantity10000'].'</td>
							   </tr>';
							   
						$html.='<tr style="text-align:center">';	   
							    $row5 = $this->getProductTemplateDetails(3,27,'With Zipper','250 gm',230,160,45,'No Accessorie','No Spout');	
								$html.='<td><strong>250gms</strong></td>
        								<td><strong>160 X 230 X 90</strong></td>
        								<td>'.$row5['quantity1000'].'</td>
        						    	<td>'.$row5['quantity2000'].'</td>
        							    <td>'.$row5['quantity5000'].'</td>
        							    <td>'.$row5['quantity10000'].'</td>
							    </tr>
						</tbody>
					</table><br>';
					    $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br>';
						$html.='<img src="../upload/admin/template_price_image/pricelist_pdf/5.2.jpg"" style="width:100%">
				</div>';
			
		        $html.='<div class="panel-body">
					    <img src="../upload/admin/template_price_image/pricelist_pdf/6.1.jpg"" style="width:100%;">
					        <table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:14px" class="">
						        <tbody>
							        <tr>
        								<th rowspan="3" colspan="2"  style="font-size:15px;"><strong>Crystal Clear Three Side Seal <br> With Zipper WIth Euro Slot</strong></th>					
        								<th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
							       </tr>';
							
						   	$html.='<tr align="center">
            							<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            							<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            							<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            							<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
						            </tr>
							
							        <tr style="text-align:center">
        								<td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
        								<td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
        								<td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
        								<td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
							        </tr>';
						    $html.='<tr style="text-align:center"> ';
							        $row1 = $this->getProductTemplateDetails(3,24,'With Zipper','40 gms',165,100,0,'No Accessorie','No Spout');
								    $html.='<td rowspan="4" style="font-size:12px;" ><strong>Crystal Clear Three   <br>Side Seal <br> With Zipper <br> WIth Euro Slot</strong></td>
            								<td><strong>40gms</strong></td>
            								<td><strong>100 X 165</strong></td>
            								<td>'.$row1['quantity1000'].'</td>
            						    	<td>'.$row1['quantity2000'].'</td>
            							    <td>'.$row1['quantity5000'].'</td>
            							    <td>'.$row1['quantity10000'].'</td>
							        </tr>';
						   $html.='<tr style="text-align:center"> ';   
							       $row2 = $this->getProductTemplateDetails(3,24,'With Zipper','120 gms',210,130,0,'No Accessorie','No Spout');				
								   $html.='<td><strong>120gms</strong></td>
        								   <td><strong>130 X 210</strong></td>
        							       <td>'.$row2['quantity1000'].'</td>
        						    	   <td>'.$row2['quantity2000'].'</td>
        							       <td>'.$row2['quantity5000'].'</td>
        							       <td>'.$row2['quantity10000'].'</td>
							       </tr>';
							       
						   $html.='<tr style="text-align:center">';	
						           $row3 = $this->getProductTemplateDetails(3,24,'With Zipper','200 gms',230,160,0,'No Accessorie','No Spout');				
								   $html.='<td><strong>200gms</strong></td>
        								  <td><strong>160 X 230</strong></td>
        							  	  <td>'.$row3['quantity1000'].'</td>
        						    	  <td>'.$row3['quantity2000'].'</td>
        							      <td>'.$row3['quantity5000'].'</td>
        							      <td>'.$row3['quantity10000'].'</td>
							      </tr>';
						$html.='<tr style="text-align:center">';
							    $row4 = $this->getProductTemplateDetails(3,24,'With Zipper','350 gm',265,190,0,'No Accessorie','No Spout');				
								$html.='<td><strong>350gms</strong></td>
        								<td><strong>190 X 260</strong></td>
        								<td>'.$row4['quantity1000'].'</td>
        						    	<td>'.$row4['quantity2000'].'</td>
        							    <td>'.$row4['quantity5000'].'</td>
        							    <td>'.$row4['quantity10000'].'</td>
						        </tr>
						</tbody>
					</table>
						<img src="../upload/admin/template_price_image/pricelist_pdf/6.2.jpg"" style="width:100%;">
				</div>';
			
	    	$html.='<div class="panel-body">
					    <img src="../upload/admin/template_price_image/pricelist_pdf/7.1.jpg"" style="width:100%; height:550px;">
					        <table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:14px" class="">
						        <tbody>
							        <tr>
        								<th rowspan="3" colspan="2" style="font-size:14px" ><strong>Clear / Clear<br> SUP With Zipper</strong></th>
        								<th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
						            </tr>';
							
						$html.='<tr align="center">
        							<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        							<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        							<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        							<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
						      </tr>
							
							 <tr style="text-align:center">
								<td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
								<td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
								<td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
								<td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
							 </tr>';
							
							$row1 = $this->getProductTemplateDetails(3,3,'No zip','28 gm',130,80,25,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        								<td style="font-size:12px;" ><strong>SUP Pouch No Zipper</strong></td>
        								<td><strong>28gms</strong></td>
        								<td><strong>80 X 130 X 50</strong></td>
        								<td>'.$row1['quantity1000'].'</td>
        						    	<td>'.$row1['quantity2000'].'</td>
        							    <td>'.$row1['quantity5000'].'</td>
        							    <td>'.$row1['quantity10000'].'</td>
							       </tr>';
							       
							$row2 = $this->getProductTemplateDetails(3,3,'With Zipper','28 gm',130,80,25,'No Accessorie','No Spout');				
						    $html.='<tr style="text-align:center">
        								<td rowspan="10" style="font-size:15px;" ><strong>SUP Pouch<br>With Zipper</strong></td>
        								<td><strong>28gms</strong></td>
        								<td><strong>80 X 130 X 50</strong></td>
        								<td>'.$row2['quantity1000'].'</td>
        						    	<td>'.$row2['quantity2000'].'</td>
        							    <td>'.$row2['quantity5000'].'</td>
        							    <td>'.$row2['quantity10000'].'</td>
							        </tr>';
							
							$row3 = $this->getProductTemplateDetails(3,3,'With Zipper','50 gm',150,95,30,'No Accessorie','No Spout');					
							$html.='<tr style="text-align:center">
        								<td><strong>50gms</strong></td>
        								<td><strong>95 X 150 X 60</strong></td>
        								<td>'.$row3['quantity1000'].'</td>
        						    	<td>'.$row3['quantity2000'].'</td>
        							    <td>'.$row3['quantity5000'].'</td>
        							    <td>'.$row3['quantity10000'].'</td>
							        </tr>';
							
							 $row4 = $this->getProductTemplateDetails(3,3,'With Zipper','70 gm',170,110,35,'No Accessorie','No Spout');
							 $html.='<tr style="text-align:center">
        								<td><strong>70gms</strong></td>
        								<td><strong>110 X 170 X 70</strong></td>
        								<td>'.$row4['quantity1000'].'</td>
        						    	<td>'.$row4['quantity2000'].'</td>
        							    <td>'.$row4['quantity5000'].'</td>
        							    <td>'.$row4['quantity10000'].'</td>
						        	 </tr>';
							
							 $row5 = $this->getProductTemplateDetails(3,3,'With Zipper','100 gm',200,120,40,'No Accessorie','No Spout');				
							 $html.='<tr style="text-align:center">
        								<td><strong>100gms</strong></td>
        								<td><strong>120 X 200 X 80</strong></td>
        								<td>'.$row5['quantity1000'].'</td>
        						    	<td>'.$row5['quantity2000'].'</td>
        							    <td>'.$row5['quantity5000'].'</td>
							            <td>'.$row5['quantity10000'].'</td>
							         </tr>';
							
							 $row6 = $this->getProductTemplateDetails(3,3,'With Zipper','150 gm',210,130,40,'No Accessorie','No Spout');				
							 $html.='<tr style="text-align:center">
        								<td><strong>150gms</strong></td>
        								<td><strong>130 X 210 X 80</strong></td>
        								<td>'.$row6['quantity1000'].'</td>
        						    	<td>'.$row6['quantity2000'].'</td>
        							    <td>'.$row6['quantity5000'].'</td>
        							    <td>'.$row6['quantity10000'].'</td>
							        </tr>';
							        
							 $row7 = $this->getProductTemplateDetails(3,3,'With Zipper','250 gm',230,160,45,'No Accessorie','No Spout');				
							 $html.='<tr style="text-align:center">
        								<td><strong>250gms</strong></td>
        								<td><strong>160 X 230 X 90</strong></td>
        								<td>'.$row7['quantity1000'].'</td>
        						    	<td>'.$row7['quantity2000'].'</td>
        							    <td>'.$row7['quantity5000'].'</td>
        							    <td>'.$row7['quantity10000'].'</td>
							         </tr>';
							         
							 $row8 = $this->getProductTemplateDetails(3,3,'With Zipper','350 gm',250,170,45,'No Accessorie','No Spout');					
							 $html.='<tr style="text-align:center">
        								<td><strong>350gms</strong></td>
        								<td><strong>170 X 250 X 90</strong></td>
        								<td>'.$row8['quantity1000'].'</td>
        						    	<td>'.$row8['quantity2000'].'</td>
        							    <td>'.$row8['quantity5000'].'</td>
        							    <td>'.$row8['quantity10000'].'</td>
							          </tr>';
							          
							 $row9 = $this->getProductTemplateDetails(3,3,'With Zipper','500 gm',260,190,55,'No Accessorie','No Spout');					
							 $html.='<tr style="text-align:center">
        								<td><strong>500gms</strong></td>
        								<td><strong>190 X 260 X 110</strong></td>
        								<td>'.$row9['quantity1000'].'</td>
        						    	<td>'.$row9['quantity2000'].'</td>
        							    <td>'.$row9['quantity5000'].'</td>
        							    <td>'.$row9['quantity10000'].'</td>
							         </tr>';
							         
							 $row10 = $this->getProductTemplateDetails(3,3,'With Zipper','150 gm',210,130,40,'No Accessorie','No Spout');	
							 $html.='<tr style="text-align:center">
        								<td><strong>750gms</strong></td>
        								<td><strong>210 X 310 X 110</strong></td>
        							    <td>'.$row10['quantity1000'].'</td>
        						    	<td>'.$row10['quantity2000'].'</td>
        							    <td>'.$row10['quantity5000'].'</td>
        							    <td>'.$row10['quantity10000'].'</td>
							        </tr>';
							        
						    $row11 = $this->getProductTemplateDetails(3,3,'With Zipper','1 kg',335,235,60,'No Accessorie','No Spout');	
						    $html.='<tr style="text-align:center">
        								<td><strong>1kg</strong></td>
        								<td><strong>235 X 345 X 120</strong></td>
        								<td>'.$row11['quantity1000'].'</td>
        						    	<td>'.$row11['quantity2000'].'</td>
        							    <td>'.$row11['quantity5000'].'</td>
        							    <td>'.$row11['quantity10000'].'</td>
							       </tr>
						</tbody>
					</table><br>';
					     $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br>';
						 $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/7.2.jpg"" style="width:100%;">
				</div>';
				
		    $html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/page-8.jpg" style="width:100%;">
				   </div>';

		    $html.='<div class="panel-body">
					    <img src="../upload/admin/template_price_image/pricelist_pdf/9.1.jpg"" style="width:100%;">
					        <table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:14px" class="">
						        <tbody>
							        <tr>
        								<th rowspan="3"  style="font-size:15px" ><strong>SUP Economy<br>Series-Matt Silver</strong></th>
        								<th rowspan="3"><strong>Size</strong></th>
        								<th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
						            </tr>
							
							        <tr style="height:20px">
        								<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        								<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
							        </tr>
							
							        <tr style="text-align:center">
        								<td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
        								<td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
        								<td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
        								<td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
							        </tr>';
							$row1 = $this->getProductTemplateDetails(2,36,'With Zipper','150 gm',210,130,40,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        								<td rowspan="3" style="font-size:13px;" ><strong>SUP Economy<br>Series-Matt <br>Silver</strong></td>
        								<td><strong>150gms</strong></td>
        								<td><strong>130 X 210 X 80</strong></td>
        						    	<td>'.$row1['quantity1000'].'</td>
        						    	<td>'.$row1['quantity2000'].'</td>
        							    <td>'.$row1['quantity5000'].'</td>
        							    <td>'.$row1['quantity10000'].'</td>
						        	</tr>';	
							$row2 = $this->getProductTemplateDetails(2,36,'With Zipper','250 gm',230,160,45,'No Accessorie','No Spout');
						    $html.='<tr style="text-align:center">
        								<td><strong>250gms</strong></td>
        								<td><strong>160 X 230 X 90</strong></td>
        								<td>'.$row2['quantity1000'].'</td>
        						    	<td>'.$row2['quantity2000'].'</td>
        							    <td>'.$row2['quantity5000'].'</td>
        							    <td>'.$row2['quantity10000'].'</td>
							       </tr>';
							
							$row3 = $this->getProductTemplateDetails(2,36,'With Zipper','500 gm',260,190,55,'No Accessorie','No Spout');
						    $html.='<tr style="text-align:center">
        								<td><strong>500gms</strong></td>
        								<td><strong>190 X 260 X 100</strong></td>
        								<td>'.$row3['quantity1000'].'</td>
        						    	<td>'.$row3['quantity2000'].'</td>
        							    <td>'.$row3['quantity5000'].'</td>
        							    <td>'.$row3['quantity10000'].'</td>
							       </tr>
						</tbody>
					</table>
					<img src="../upload/admin/template_price_image/pricelist_pdf/9.2.jpg"" style="width:100%; height:700px">
				</div>';
					
		    $html.='<div class="panel-body">
					    <img src="../upload/admin/template_price_image/pricelist_pdf/10.1.jpg"" style="width:100%;height:400px">
				            <table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:14px" class="">
						        <tbody>
						            <tr>
            							<th rowspan="3" colspan="2" style="font-size:15px" ><strong>Biodegradable<br> SUP Pouch With Zipper</strong></th>
            							<th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
            							<th><strong>Price Per</strong></th>
                						<th><strong>Price Per</strong></th>
                						<th><strong>Price Per</strong></th>
                						<th><strong>Price Per</strong></th>
					               </tr>
						
						           <tr style="height:20px">
            							<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            						    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            						    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            							<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
					               </tr>
						
						           <tr style="text-align:center">
            							<td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
            							<td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
            							<td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
            							<td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
						           </tr>';
						$row1 = $this->getProductTemplateDetails(5,3,'No zip','28 gm',130,80,25,'No Accessorie','No Spout');
						$html.='<tr style="text-align:center">
        							<td width="15%" style="font-size:12px;" ><strong>SUP Pouch No Zipper</strong></td>
        							<td><strong>28gms</strong></td>
        							<td><strong>80 X 130 X 50</strong></td>
        							<td>'.$row1['quantity1000'].'</td>
        						    <td>'.$row1['quantity2000'].'</td>
        						    <td>'.$row1['quantity5000'].'</td>
        							<td>'.$row1['quantity10000'].'</td>
						        </tr>';
						        
						$row2 = $this->getProductTemplateDetails(5,3,'With Zipper','28 gm',130,80,25,'No Accessorie','No Spout');
						$html.='<tr style="text-align:center">
        							<td rowspan="10" style="font-size:13px;" ><strong>SUP Pouch<br>With Zipper</strong></td>
        							<td><strong>28gms</strong></td>
        							<td><strong>80 X 130 X 50</strong></td>
        							<td>'.$row2['quantity1000'].'</td>
        						    <td>'.$row2['quantity2000'].'</td>
        						    <td>'.$row2['quantity5000'].'</td>
        							<td>'.$row2['quantity10000'].'</td>
						       </tr>';
						       
						$row3 = $this->getProductTemplateDetails(5,3,'With Zipper','50 gm',150,95,30,'No Accessorie','No Spout');					
						$html.='<tr style="text-align:center">
        							<td><strong>50gms</strong></td>
        							<td><strong>95 X 150 X 60</strong></td>
        							<td>'.$row3['quantity1000'].'</td>
        						    <td>'.$row3['quantity2000'].'</td>
        						    <td>'.$row3['quantity5000'].'</td>
        							<td>'.$row3['quantity10000'].'</td>
						       </tr>';
						       
						$row4 = $this->getProductTemplateDetails(5,3,'With Zipper','70 gm',170,110,35,'No Accessorie','No Spout');	
						$html.='<tr style="text-align:center">
        							<td><strong>70gms</strong></td>
        							<td><strong>110 X 170 X 70</strong></td>
        						    <td>'.$row4['quantity1000'].'</td>
        						    <td>'.$row4['quantity2000'].'</td>
        						    <td>'.$row4['quantity5000'].'</td>
        							<td>'.$row4['quantity10000'].'</td>
						       </tr>';
						       
						$row5 = $this->getProductTemplateDetails(5,3,'With Zipper','100 gm',200,120,40,'No Accessorie','No Spout');					
						$html.='<tr style="text-align:center">
        							<td><strong>100gms</strong></td>
        							<td><strong>120 X 200 X 80</strong></td>
        							<td>'.$row5['quantity1000'].'</td>
        						    <td>'.$row5['quantity2000'].'</td>
        						    <td>'.$row5['quantity5000'].'</td>
        							<td>'.$row5['quantity10000'].'</td>
					           </tr>';
					           
						$row6 = $this->getProductTemplateDetails(5,3,'With Zipper','150 gm',210,130,40,'No Accessorie','No Spout');					
						$html.='<tr style="text-align:center">
        							<td><strong>150gms</strong></td>
        							<td><strong>130 X 210 X 80</strong></td>
        						    <td>'.$row6['quantity1000'].'</td>
        						    <td>'.$row6['quantity2000'].'</td>
        						    <td>'.$row6['quantity5000'].'</td>
        							<td>'.$row6['quantity10000'].'</td>
						       </tr>';
						       
						$row7 = $this->getProductTemplateDetails(5,3,'With Zipper','250 gm',230,160,45,'No Accessorie','No Spout');
						$html.='<tr style="text-align:center">
        							<td><strong>250gms</strong></td>
        							<td><strong>160 X 230 X 90</strong></td>
        						    <td>'.$row7['quantity1000'].'</td>
        						    <td>'.$row7['quantity2000'].'</td>
        						    <td>'.$row7['quantity5000'].'</td>
        							<td>'.$row7['quantity10000'].'</td>
						        </tr>';
						        
						$row8 = $this->getProductTemplateDetails(5,3,'With Zipper','350 gm',150,95,30,'No Accessorie','No Spout');					
						$html.='<tr style="text-align:center">
        							<td><strong>350gms</strong></td>
        							<td><strong>170 X 250 X 90</strong></td>
        							<td>'.$row3['quantity1000'].'</td>
        						    <td>'.$row3['quantity2000'].'</td>
        						    <td>'.$row3['quantity5000'].'</td>
        							<td>'.$row3['quantity10000'].'</td>
					           </tr>';
                        $row9 = $this->getProductTemplateDetails(5,3,'With Zipper','500 gm',260,190,55,'No Accessorie','No Spout');
						$html.='<tr style="text-align:center">
        							<td><strong>500gms</strong></td>
        							<td><strong>190 X 260 X 110</strong></td>
        							<td>'.$row3['quantity1000'].'</td>
        						    <td>'.$row3['quantity2000'].'</td>
        						    <td>'.$row3['quantity5000'].'</td>
        							<td>'.$row3['quantity10000'].'</td>
						       </tr>';
						       
						$row10 = $this->getProductTemplateDetails(5,3,'With Zipper','750 gm',310,210,55,'No Accessorie','No Spout');					
						$html.='<tr style="text-align:center">
        							<td><strong>750gms</strong></td>
        							<td><strong>210 X 310 X 110</strong></td>
        							<td>'.$row10['quantity1000'].'</td>
        						    <td>'.$row10['quantity2000'].'</td>
        						    <td>'.$row10['quantity5000'].'</td>
        							<td>'.$row10['quantity10000'].'</td>
					        	</tr>';
					        	
						$row11 = $this->getProductTemplateDetails(5,3,'With Zipper','1 kg',335,235,60,'No Accessorie','No Spout');					
						$html.='<tr style="text-align:center">
        							<td><strong>1kg</strong></td>
        							<td><strong>235 X 345 X 120</strong></td>
        							<td>'.$row11['quantity1000'].'</td>
        						    <td>'.$row11['quantity2000'].'</td>
        						    <td>'.$row11['quantity5000'].'</td>
        							<td>'.$row11['quantity10000'].'</td>
						        </tr>
					</tbody>
				</table><br><br>';
					    $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br>';
				        $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/10.2.jpg"" style="width:100%;height:250px">
			   </div>';
					
		    $html.='<div class="panel-body">
					    <img src="../upload/admin/template_price_image/pricelist_pdf/11.1.jpg"" style="width:100%">
				            <table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:14px" class="">
						        <tbody>
							        <tr>
        								<th rowspan="3" colspan="2" style="font-size:15px" ><strong>Kraft Paper Look<br> SUP Pouch With Zipper</strong></th>
        								<th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
							       </tr>
							       
							       <tr style="height:20px">
            							<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            						    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            						    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            							<td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
					               </tr>
					               
							       <tr style="text-align:center">
        								<td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
        								<td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
        								<td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
        								<td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
							       </tr>';
							       
							$row1 = $this->getProductTemplateDetails(2,3,'With Zipper','70 gm',170,110,35,'No Accessorie','No Spout');
						    $html.='<tr style="text-align:center">
        							   <td rowspan="5" style="font-size:13px;" ><strong>SUP Pouch<br>With Zipper</strong></td>
        							   <td><strong>70gms</strong></td>
        							   <td><strong>110 X 170 X 70</strong></td>
        							   <td>'.$row1['quantity1000'].'</td>
        						       <td>'.$row1['quantity2000'].'</td>
        						       <td>'.$row1['quantity5000'].'</td>
        							   <td>'.$row1['quantity10000'].'</td>
							        </tr>';
							        
							$row2 = $this->getProductTemplateDetails(2,3,'With Zipper','150 gm',210,130,40,'No Accessorie','No Spout');	
							$html.='<tr style="text-align:center">
        							   <td><strong>150gms</strong></td>
        							   <td><strong>130 X 210 X 80</strong></td>
        							   <td>'.$row2['quantity1000'].'</td>
        						       <td>'.$row2['quantity2000'].'</td>
        						       <td>'.$row2['quantity5000'].'</td>
        							   <td>'.$row2['quantity10000'].'</td>
							        </tr>';
							        
							$row3 = $this->getProductTemplateDetails(2,3,'With Zipper','250 gm',230,160,45,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							   <td><strong>250gms</strong></td>
        							   <td><strong>160 X 230 X 90</strong></td>
        							   <td>'.$row3['quantity1000'].'</td>
        						       <td>'.$row3['quantity2000'].'</td>
        						       <td>'.$row3['quantity5000'].'</td>
        							   <td>'.$row3['quantity10000'].'</td>
							        </tr>';
							        
							$row4 = $this->getProductTemplateDetails(2,3,'With Zipper','500 gm',260,190,55,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							   <td><strong>500gms</strong></td>
        							   <td><strong>190 X 260 X 110</strong></td>
        							   <td>'.$row4['quantity1000'].'</td>
        						       <td>'.$row4['quantity2000'].'</td>
        						       <td>'.$row4['quantity5000'].'</td>
        							   <td>'.$row4['quantity10000'].'</td>
							       </tr>';
							       
							$row5 = $this->getProductTemplateDetails(2,3,'With Zipper','1 kg',335,235,60,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							   <td><strong>1kg</strong></td>
        							   <td><strong>235 X 345 X 120</strong></td>
        							   <td>'.$row5['quantity1000'].'</td>
        						       <td>'.$row5['quantity2000'].'</td>
        						       <td>'.$row5['quantity5000'].'</td>
        							   <td>'.$row5['quantity10000'].'</td>
							       </tr>
						</tbody>
					</table><br>';
					    $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br>';
				    	$html.='<img src="../upload/admin/template_price_image/pricelist_pdf/11.2.jpg"" style="width:100%;height:150px">
				</div>';
	
		    $html.='<div class="panel-body">
   					    <img src="../upload/admin/template_price_image/pricelist_pdf/12.1.jpg"" style="width:100%">
   				        	<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:14px" class="">
      					        <tbody>
							        <tr>
        								<th rowspan="3" colspan="2" style="font-size:15px" ><strong>SUP Pouch With Zipper</strong></th>
        								<th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
        								<th><strong>Price Per</strong></th>
							       </tr>
							 
							       <tr style="height:20px">
        							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
					               </tr>
							 
							       <tr style="text-align:center">
        								<td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
        								<td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
        								<td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
        								<td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
							      </tr>';
							      
							 $row1 = $this->getProductTemplateDetails(2,3,'With Zipper','70 gm',170,110,35,'No Accessorie','No Spout');
							 $html.='<tr style="text-align:center">
        								<td rowspan="5" style="font-size:13px;" ><strong>SUP Pouch<br>With Zipper</strong></td>
        								<td><strong>70gms</strong></td>
        								<td><strong>110 X 170 X 70</strong></td>
        							    <td>'.$row1['quantity1000'].'</td>
        						        <td>'.$row1['quantity2000'].'</td>
        						        <td>'.$row1['quantity5000'].'</td>
        							    <td>'.$row1['quantity10000'].'</td>
							        </tr>';
							        
							 $row2 = $this->getProductTemplateDetails(2,3,'With Zipper','150 gm',210,130,40,'No Accessorie','No Spout');
							 $html.='<tr style="text-align:center">
        								<td><strong>150gms</strong></td>
        								<td><strong>130 X 210 X 80</strong></td>
        							    <td>'.$row2['quantity1000'].'</td>
        						        <td>'.$row2['quantity2000'].'</td>
        						        <td>'.$row2['quantity5000'].'</td>
        							    <td>'.$row2['quantity10000'].'</td>
							        </tr>';
							        
							 $row3 = $this->getProductTemplateDetails(2,3,'With Zipper','250 gm',230,160,45,'No Accessorie','No Spout');
							 $html.='<tr style="text-align:center">
        								<td><strong>250gms</strong></td>
        								<td><strong>160 X 230 X 90</strong></td>
        								<td>'.$row3['quantity1000'].'</td>
        						        <td>'.$row3['quantity2000'].'</td>
        						        <td>'.$row3['quantity5000'].'</td>
        							    <td>'.$row3['quantity10000'].'</td>
							        </tr>';
							        
							 $row4 = $this->getProductTemplateDetails(2,3,'With Zipper','500 gm',260,190,55,'No Accessorie','No Spout');
							 $html.='<tr style="text-align:center">
        								<td><strong>500gms</strong></td>
        								<td><strong>190 X 260 X 110</strong></td>
        								<td>'.$row4['quantity1000'].'</td>
        						        <td>'.$row4['quantity2000'].'</td>
        						        <td>'.$row4['quantity5000'].'</td>
        							    <td>'.$row4['quantity10000'].'</td>
							        </tr>';
							        
							 $row5 = $this->getProductTemplateDetails(2,3,'With Zipper','1 kg',335,235,60,'No Accessorie','No Spout');
							 $html.='<tr style="text-align:center">
        								<td><strong>1kg</strong></td>
        								<td><strong>235 X 345 X 120</strong></td>
        								<td>'.$row5['quantity1000'].'</td>
        						        <td>'.$row5['quantity2000'].'</td>
        						        <td>'.$row5['quantity5000'].'</td>
        							    <td>'.$row5['quantity10000'].'</td>
							        </tr>
						   </tbody>
   					</table><br>';
					        $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br>';
   					        $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/12.2.jpg"" style="width:100%;height:150px">
				</div>';

					
		    $html.='<div class="panel-body">
					    <img src="../upload/admin/template_price_image/pricelist_pdf/13.1.jpg"" style="width:100%; height:520px">
					        <table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
						        <tbody>
						            <tr>
        							  <th rowspan="3" colspan="2" style="font-size:18px" ><strong>Brown Paper (SUP)<br>Pouch With Zipper</strong></th>
        							  <th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
						           </tr>
						   
						           <tr style="height:20px">
        							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
					               </tr>
						   
        						   <tr style="text-align:center">
        							  <td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
        							  <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
        							  <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
        							  <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
						           </tr>';
						           
						    $row1 = $this->getProductTemplateDetails(5,3,'No zip','28 gm',130,80,25,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
            							<td ><strong>SUP Pouch With No Zipper</strong></td>
            							<td><strong>28gms</strong></td>
            							<td><strong>80 X 130 X 50</strong></td>
            							<td>'.$row1['quantity1000'].'</td>
            					        <td>'.$row1['quantity2000'].'</td>
            					        <td>'.$row1['quantity5000'].'</td>
            						    <td>'.$row1['quantity10000'].'</td>
						           </tr>';
							
						    $row2 = $this->getProductTemplateDetails(5,3,'With Zipper','28 gm',130,80,25,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>28gms</strong></td>
        							  <td><strong>80 X 130 X 50</strong></td>
        							  <td>'.$row2['quantity1000'].'</td>
        					          <td>'.$row2['quantity2000'].'</td>
        					          <td>'.$row2['quantity5000'].'</td>
        						      <td>'.$row2['quantity10000'].'</td>
						           </tr>';
						           
						    $row3 = $this->getProductTemplateDetails(5,3,'With Zipper','50 gm',150,95,30,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>50gms</strong></td>
        							  <td><strong>95 X 150 X 60</strong></td>
        							  <td>'.$row3['quantity1000'].'</td>
        					          <td>'.$row3['quantity2000'].'</td>
        					          <td>'.$row3['quantity5000'].'</td>
        						      <td>'.$row3['quantity10000'].'</td>
						           </tr>';
						   
						    $row4 = $this->getProductTemplateDetails(5,3,'With Zipper','70 gm',170,110,35,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>70gms</strong></td>
        							  <td><strong>110 X 170 X 70</strong></td>
        							  <td>'.$row4['quantity1000'].'</td>
        					          <td>'.$row4['quantity2000'].'</td>
        					          <td>'.$row4['quantity5000'].'</td>
        						      <td>'.$row4['quantity10000'].'</td>
						           </tr>';
						   
						    $row5 = $this->getProductTemplateDetails(5,3,'With Zipper','100 gm',200,120,40,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>100gms</strong></td>
        							  <td><strong>120 X 200 X 80</strong></td>
        							  <td>'.$row5['quantity1000'].'</td>
        					          <td>'.$row5['quantity2000'].'</td>
        					          <td>'.$row5['quantity5000'].'</td>
        						      <td>'.$row5['quantity10000'].'</td>
						            </tr>';
						   
						    $row6 = $this->getProductTemplateDetails(5,3,'With Zipper','150 gm',210,130,40,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>150gms</strong></td>
        							  <td><strong>130 X 210 X 80</strong></td>
        							  <td>'.$row6['quantity1000'].'</td>
        					          <td>'.$row6['quantity2000'].'</td>
        					          <td>'.$row6['quantity5000'].'</td>
        						      <td>'.$row6['quantity10000'].'</td>
						           </tr>';
						   
						    $row7 = $this->getProductTemplateDetails(5,3,'With Zipper','250 gm',230,160,45,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>250gms</strong></td>
        							  <td><strong>160 X 230 X 90</strong></td>
        							  <td>'.$row7['quantity1000'].'</td>
        					          <td>'.$row7['quantity2000'].'</td>
        					          <td>'.$row7['quantity5000'].'</td>
        						      <td>'.$row7['quantity10000'].'</td>
						           </tr>';
						   
						    $row8 = $this->getProductTemplateDetails(5,3,'With Zipper','350 gm',250,170,45,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>350gms</strong></td>
        							  <td><strong>170 X 250 X 90</strong></td>
        							  <td>'.$row8['quantity1000'].'</td>
        					          <td>'.$row8['quantity2000'].'</td>
        					          <td>'.$row8['quantity5000'].'</td>
        						      <td>'.$row8['quantity10000'].'</td>
						           </tr>';
						   
						    $row9 = $this->getProductTemplateDetails(5,3,'With Zipper','500 gm',260,190,55,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>500gms</strong></td>
        							  <td><strong>190 X 260 X 110</strong></td>
        							  <td>'.$row9['quantity1000'].'</td>
        					          <td>'.$row9['quantity2000'].'</td>
        					          <td>'.$row9['quantity5000'].'</td>
        						      <td>'.$row9['quantity10000'].'</td>
						           </tr>';
						   
						    $row10 = $this->getProductTemplateDetails(5,3,'With Zipper','750 gm',310,210,55,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>750gms</strong></td>
        							  <td><strong>210 X 310 X 110</strong></td>
        							  <td>'.$row10['quantity1000'].'</td>
        					          <td>'.$row10['quantity2000'].'</td>
        					          <td>'.$row10['quantity5000'].'</td>
        						      <td>'.$row10['quantity10000'].'</td>
						           </tr>';
						   
						    $row11 = $this->getProductTemplateDetails(5,3,'With Zipper','1 kg',335,235,60,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>1kg</strong></td>
        							  <td><strong>235 X 345 X 120</strong></td>
        							  <td>'.$row11['quantity1000'].'</td>
        					          <td>'.$row11['quantity2000'].'</td>
        					          <td>'.$row11['quantity5000'].'</td>
        						      <td>'.$row11['quantity10000'].'</td>
						           </tr>
						</tbody>
					</table><br>';
					        $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br>';
					        $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/13.2.jpg"" style="width:100%;height:150px;">
				</div>';
				
				
		    $html.='<div class="panel-body">
					    <img src="../upload/admin/template_price_image/pricelist_pdf/14.1.jpg"" style="width:100%; height:520px">
					        <table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
						        <tbody>
						            <tr>
        							  <th rowspan="3" colspan="2" style="font-size:18px" ><strong>White Paper (SUP)<br>Pouch With Zipper</strong></th>
        							  <th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
        							  <th><strong>Price Per</strong></th>
            						  <th><strong>Price Per</strong></th>
            						  <th><strong>Price Per</strong></th>
            						  <th><strong>Price Per</strong></th>
						           </tr>
						   
						           <tr style="height:20px">
        							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
					               </tr>
						   
						           <tr style="text-align:center">
        							  <td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
        							  <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
        							  <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
        							  <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
						           </tr>';
						   
						    $row1 = $this->getProductTemplateDetails(5,3,'No zip','28 gm',130,80,25,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							  <td ><strong>SUP Pouch With No Zipper</strong></td>
        							  <td><strong>28gms</strong></td>
        							  <td><strong>80 X 130 X 50</strong></td>
        							  <td>'.$row1['quantity1000'].'</td>
        					          <td>'.$row1['quantity2000'].'</td>
        					          <td>'.$row1['quantity5000'].'</td>
        						      <td>'.$row1['quantity10000'].'</td>
						           </tr>';
						   
						    $row2 = $this->getProductTemplateDetails(5,3,'With Zipper','28 gm',130,80,25,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>28gms</strong></td>
        							  <td><strong>80 X 130 X 50</strong></td>
        							  <td>'.$row2['quantity1000'].'</td>
        					          <td>'.$row2['quantity2000'].'</td>
        					          <td>'.$row2['quantity5000'].'</td>
        						      <td>'.$row2['quantity10000'].'</td>
						           </tr>';
						   
						    $row3 = $this->getProductTemplateDetails(5,3,'With Zipper','50 gm',150,95,30,'No Accessorie');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>50gms</strong></td>
        							  <td><strong>95 X 150 X 60</strong></td>
        							  <td>'.$row3['quantity1000'].'</td>
        					          <td>'.$row3['quantity2000'].'</td>
        					          <td>'.$row3['quantity5000'].'</td>
        						      <td>'.$row3['quantity10000'].'</td>
						           </tr>';
						   
						    $row4 = $this->getProductTemplateDetails(5,3,'With Zipper','70 gm',170,110,35,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>70gms</strong></td>
        							  <td><strong>110 X 170 X 70</strong></td>
        							  <td>'.$row4['quantity1000'].'</td>
        					          <td>'.$row4['quantity2000'].'</td>
        					          <td>'.$row4['quantity5000'].'</td>
        						      <td>'.$row4['quantity10000'].'</td>
						           </tr>';
						   
						     $row5 = $this->getProductTemplateDetails(5,3,'With Zipper','100 gm',200,120,40,'No Accessorie','No Spout');
							 $html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>100gms</strong></td>
        							  <td><strong>120 X 200 X 80</strong></td>
        							  <td>'.$row5['quantity1000'].'</td>
        					          <td>'.$row5['quantity2000'].'</td>
        					          <td>'.$row5['quantity5000'].'</td>
        						      <td>'.$row5['quantity10000'].'</td>
						             </tr>';
						   
						    $row6 = $this->getProductTemplateDetails(5,3,'With Zipper','150 gm',210,130,40,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>150gms</strong></td>
        							  <td><strong>130 X 210 X 80</strong></td>
        							  <td>'.$row6['quantity1000'].'</td>
        					          <td>'.$row6['quantity2000'].'</td>
        					          <td>'.$row6['quantity5000'].'</td>
        						      <td>'.$row6['quantity10000'].'</td>
						            </tr>';
						   
						     $row7 = $this->getProductTemplateDetails(5,3,'With Zipper','250 gm',230,160,45,'No Accessorie','No Spout');
							 $html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>250gms</strong></td>
        							  <td><strong>160 X 230 X 90</strong></td>
        							  <td>'.$row7['quantity1000'].'</td>
        					          <td>'.$row7['quantity2000'].'</td>
        					          <td>'.$row7['quantity5000'].'</td>
        						      <td>'.$row7['quantity10000'].'</td>
						            </tr>';
						   
						    $row8 = $this->getProductTemplateDetails(5,3,'With Zipper','350 gm',250,170,45,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>350gms</strong></td>
        							  <td><strong>170 X 250 X 90</strong></td>
        							  <td>'.$row8['quantity1000'].'</td>
        					          <td>'.$row8['quantity2000'].'</td>
        					          <td>'.$row8['quantity5000'].'</td>
        						      <td>'.$row8['quantity10000'].'</td>
						            </tr>';
						   
						    $row9 = $this->getProductTemplateDetails(5,3,'With Zipper','500 gm',260,190,55,'No Accessorie','No Spout');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>500gms</strong></td>
        							  <td><strong>190 X 260 X 110</strong></td>
        							  <td>'.$row9['quantity1000'].'</td>
        					          <td>'.$row9['quantity2000'].'</td>
        					          <td>'.$row9['quantity5000'].'</td>
        						      <td>'.$row9['quantity10000'].'</td>
						           </tr>';
						   
						    $row10 = $this->getProductTemplateDetails(5,3,'With Zipper','750 gm',310,210,55,'No Accessorie');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>750gms</strong></td>
        							  <td><strong>210 X 310 X 110</strong></td>
        							  <td>'.$row10['quantity1000'].'</td>
        					          <td>'.$row10['quantity2000'].'</td>
        					          <td>'.$row10['quantity5000'].'</td>
        						      <td>'.$row10['quantity10000'].'</td>
						            </tr>';
						   
						    $row11 = $this->getProductTemplateDetails(5,3,'With Zipper','1 kg',335,235,60,'No Accessorie');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>1kg</strong></td>
        							  <td><strong>235 X 345 X 120</strong></td>
        							  <td>'.$row11['quantity1000'].'</td>
        					          <td>'.$row11['quantity2000'].'</td>
        					          <td>'.$row11['quantity5000'].'</td>
        						      <td>'.$row11['quantity10000'].'</td>
						           </tr>
						</tbody>
					</table><br>';
					        $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br>';
				        	$html.='<img src="../upload/admin/template_price_image/pricelist_pdf/14.2.jpg"" style="width:100%;height:170px;">
				</div>';
			
					
		    $html.='<div class="panel-body">
					    <img src="../upload/admin/template_price_image/pricelist_pdf/15.1.jpg"" style="width:100%; height:520px">
					        <table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
						        <tbody>
						            <tr>
        							  <th rowspan="3" colspan="2" style="font-size:18px" ><strong>Black, Green Paper (SUP)<br>Pouch With Zipper</strong></th>
        							  <th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
						           </tr>
						   
						           <tr style="height:20px">
        							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
					              </tr>
						   
						        <tr style="text-align:center">
        							  <td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
        							  <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
        							  <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
        							  <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
						        </tr>
						   
						        <tr style="text-align:center">
    							  <td ><strong>SUP Pouch With No Zipper</strong></td>
    							  <td><strong>28gms</strong></td>
    							  <td><strong>80 X 130 X 50</strong></td>
    							  <td></td>
    							  <td></td>
    							  <td></td>
    							  <td></td>
						       </tr>
						   
						        <tr style="text-align:center">
    							  <td><strong>SUP Pouch With Zipper</strong></td>
    							  <td><strong>28gms</strong></td>
    							  <td><strong>80 X 130 X 50</strong></td>
    							  <td></td>
    							  <td></td>
    							  <td></td>
    							  <td></td>
						       </tr>
						   
						   <tr style="text-align:center">
							  <td><strong>SUP Pouch With Zipper</strong></td>
							  <td><strong>50gms</strong></td>
							  <td><strong>95 X 150 X 60</strong></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
						   </tr>';
						   
						    $row4 = $this->getProductTemplateDetails(5,3,'With Zipper','70 gm',170,110,35,'No Accessorie');
						    $html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>70gms</strong></td>
        							  <td><strong>110 X 170 X 70</strong></td>
        							  <td>'.$row4['quantity1000'].'</td>
        					          <td>'.$row4['quantity2000'].'</td>
        					          <td>'.$row4['quantity5000'].'</td>
        						      <td>'.$row4['quantity10000'].'</td>
						           </tr>
						   
        						   <tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>100gms</strong></td>
        							  <td><strong>120 X 200 X 80</strong></td>
        							  <td></td>
        							  <td></td>
        							  <td></td>
        							  <td></td>
        						   </tr>';
						   
						    $row6 = $this->getProductTemplateDetails(5,3,'With Zipper','150 gm',210,130,40,'No Accessorie');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>150gms</strong></td>
        							  <td><strong>130 X 210 X 80</strong></td>
        							  <td>'.$row6['quantity1000'].'</td>
        					          <td>'.$row6['quantity2000'].'</td>
        					          <td>'.$row6['quantity5000'].'</td>
        						      <td>'.$row6['quantity10000'].'</td>
        						   </tr>';
						   
						     $row7 = $this->getProductTemplateDetails(5,3,'With Zipper','250 gm',230,160,45,'No Accessorie');
							 $html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>250gms</strong></td>
        							  <td><strong>160 X 230 X 90</strong></td>
        							  <td>'.$row7['quantity1000'].'</td>
        					          <td>'.$row7['quantity2000'].'</td>
        					          <td>'.$row7['quantity5000'].'</td>
        						      <td>'.$row7['quantity10000'].'</td>
        						   </tr>
						   
        						   <tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>350gms</strong></td>
        							  <td><strong>170 X 250 X 90</strong></td>
        							  <td></td>
        							  <td></td>
        							  <td></td>
        							  <td></td>
        						   </tr>';
						   
						    $row9 = $this->getProductTemplateDetails(5,3,'With Zipper','500 gm',260,190,55,'No Accessorie');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>500gms</strong></td>
        							  <td><strong>190 X 260 X 110</strong></td>
        							  <td>'.$row9['quantity1000'].'</td>
        					          <td>'.$row9['quantity2000'].'</td>
        					          <td>'.$row9['quantity5000'].'</td>
        						      <td>'.$row9['quantity10000'].'</td>
        						   </tr>
						   
        						   <tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>750gms</strong></td>
        							  <td><strong>210 X 310 X 110</strong></td>
        							  <td></td>
        							  <td></td>
        							  <td></td>
        							  <td></td>
        						   </tr>';
						   
						    $row11 = $this->getProductTemplateDetails(5,3,'With Zipper','1 kg',335,235,60,'No Accessorie');
							$html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>1kg</strong></td>
        							  <td><strong>235 X 345 X 120</strong></td>
        							  <td>'.$row11['quantity1000'].'</td>
        					          <td>'.$row11['quantity2000'].'</td>
        					          <td>'.$row11['quantity5000'].'</td>
        						      <td>'.$row11['quantity10000'].'</td>
        						   </tr>
						</tbody>
					</table><br>';
					        $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br>';
					        $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/15.2.jpg"" style="width:100%;height:170px;">
				</div>';
				
	    	$html.='<div class="panel-body">
					    <img src="../upload/admin/template_price_image/pricelist_pdf/16.1.jpg"" style="width:100%;height:650px;">
					        <table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
						        <tbody>
						            <tr>
        							  <th rowspan="3" colspan="2" style="font-size:16px" ><strong>SUP Pouch With Zipper<br> (Brown Paper) bags<br />With Oval Window</strong></th>
        							  <th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
						           </tr>
						   
        						    <tr style="height:20px">
        							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        					         </tr>
        						   
        						    <tr style="text-align:center">
        							  <td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
        							  <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
        							  <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
        							  <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
        						   </tr>';
						   
						    $row4 = $this->getProductTemplateDetails(5,3,'With Zipper','70 gm',170,110,35,'No Accessorie');
						    $html.='<tr style="text-align:center">
        							  <td rowspan="4" style="font-size:13px;" ><strong>SUP Pouch With Zipper<br> (Brown Paper) bags<br />With Oval Window</strong></td>
        							  <td><strong>70gms</strong></td>
        							  <td><strong>110 X 170 X 70</strong></td>
        							  <td>'.$row4['quantity1000'].'</td>
        					          <td>'.$row4['quantity2000'].'</td>
        					          <td>'.$row4['quantity5000'].'</td>
        						      <td>'.$row4['quantity10000'].'</td>
						            </tr>';
						   
						    $row6 = $this->getProductTemplateDetails(5,3,'With Zipper','150 gm',210,130,40,'No Accessorie');
							$html.='<tr style="text-align:center">
            						  <td><strong>150gms</strong></td>
            						  <td><strong>130 X 210 X 80</strong></td>
            						  <td>'.$row6['quantity1000'].'</td>
            				          <td>'.$row6['quantity2000'].'</td>
            				          <td>'.$row6['quantity5000'].'</td>
            					      <td>'.$row6['quantity10000'].'</td>
            					   </tr>';
						   
						     $row7 = $this->getProductTemplateDetails(5,3,'With Zipper','250 gm',230,160,45,'No Accessorie');
							 $html.='<tr style="text-align:center">
        							  <td><strong>250gms</strong></td>
        							  <td><strong>160 X 230 X 90</strong></td>
        							  <td>'.$row7['quantity1000'].'</td>
        					          <td>'.$row7['quantity2000'].'</td>
        					          <td>'.$row7['quantity5000'].'</td>
        						      <td>'.$row7['quantity10000'].'</td>
        						   </tr>';
						   
						    $row9 = $this->getProductTemplateDetails(5,3,'With Zipper','500 gm',260,190,55,'No Accessorie');
							$html.='<tr style="text-align:center">
        							  <td><strong>500gms</strong></td>
        							  <td><strong>190 X 260 X 110</strong></td>
        							  <td>'.$row9['quantity1000'].'</td>
        					          <td>'.$row9['quantity2000'].'</td>
        					          <td>'.$row9['quantity5000'].'</td>
        						      <td>'.$row9['quantity10000'].'</td>
        						   </tr>
						</tbody>
					</table><br>';
					        $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br><br>';
					        $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/16.2.jpg"" style="width:100%">
				</div>';
			
		    $html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/page-17.jpg"" style="width:100%">
				   </div>	';
					
            $html.='<div class="panel-body">
					    <img src="../upload/admin/template_price_image/pricelist_pdf/18.1.jpg"" style="width:100%; height:500px;">
					        <table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:13px" class="">
						        <tbody>
						            <tr>
        							  <th rowspan="3" colspan="2" style="font-size:15px" ><strong>Brown Paper & White Paper<br>SUP Pouch With Zipper</strong></th>
        							  <th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
						           </tr>
						   
						          <tr style="height:20px">
    							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
    						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
    						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
    							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
					             </tr>
						   
						         <tr style="text-align:center">
        							  <td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
        							  <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
        							  <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
        							  <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
						        </tr>';
						   
						    $row1 = $this->getProductTemplateDetails(5,3,'With Zipper','50 gm',150,95,30,'No Accessorie');
						    $html.='<tr style="text-align:center">
            							  <td style="width:140px;"><strong>SUP Pouch With Zipper</strong></td>
            							  <td><strong>50gms</strong></td>
            							  <td><strong>95 X 150 X 60</strong></td>
            							  <td>'.$row1['quantity1000'].'</td>
            					          <td>'.$row1['quantity2000'].'</td>
            					          <td>'.$row1['quantity5000'].'</td>
            						      <td>'.$row1['quantity10000'].'</td>
            						</tr>';
						   
						    $row2 = $this->getProductTemplateDetails(5,3,'With Zipper','70 gm',170,110,35,'No Accessorie');
						    $html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>70gms</strong></td>
        							  <td><strong>110 X 170 X 70</strong></td>
        							  <td>'.$row2['quantity1000'].'</td>
        					          <td>'.$row2['quantity2000'].'</td>
        					          <td>'.$row2['quantity5000'].'</td>
        						      <td>'.$row2['quantity10000'].'</td>
						            </tr>';
						   
						    $row3 = $this->getProductTemplateDetails(5,3,'With Zipper','100 gm',200,120,40,'No Accessorie');
						    $html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>100gms</strong></td>
        							  <td><strong>120 X 200 X 80</strong></td>
        							  <td>'.$row3['quantity1000'].'</td>
        					          <td>'.$row3['quantity2000'].'</td>
        					          <td>'.$row3['quantity5000'].'</td>
        						      <td>'.$row3['quantity10000'].'</td>
						           </tr>';
						   
						    $row4 = $this->getProductTemplateDetails(5,3,'With Zipper','150 gm',210,130,40,'No Accessorie');
						    $html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>150gms</strong></td>
        							  <td><strong>130 X 210 X 80</strong></td>
        							  <td>'.$row4['quantity1000'].'</td>
        					          <td>'.$row4['quantity2000'].'</td>
        					          <td>'.$row4['quantity5000'].'</td>
        						      <td>'.$row4['quantity10000'].'</td>
						            </tr>';
						   
						    $row5 = $this->getProductTemplateDetails(5,3,'With Zipper','250 gm',230,160,45,'No Accessorie');
						    $html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>250gms</strong></td>
        							  <td><strong>160 X 230 X 90</strong></td>
        							  <td>'.$row5['quantity1000'].'</td>
        					          <td>'.$row5['quantity2000'].'</td>
        					          <td>'.$row5['quantity5000'].'</td>
        						      <td>'.$row5['quantity10000'].'</td>
        						   </tr>';
						   
						    $row6 = $this->getProductTemplateDetails(5,3,'With Zipper','350 gm',250,170,45,'No Accessorie');
						    $html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>350gms</strong></td>
        							  <td><strong>170 X 250 X 90</strong></td>
        							  <td>'.$row6['quantity1000'].'</td>
        					          <td>'.$row6['quantity2000'].'</td>
        					          <td>'.$row6['quantity5000'].'</td>
        						      <td>'.$row6['quantity10000'].'</td>
        						   </tr>';
						   
						    $row7 = $this->getProductTemplateDetails(5,3,'With Zipper','500 gm',260,190,55,'No Accessorie');
						    $html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>500gms</strong></td>
        							  <td><strong>190 X 260 X 110</strong></td>
        							  <td>'.$row7['quantity1000'].'</td>
        					          <td>'.$row7['quantity2000'].'</td>
        					          <td>'.$row7['quantity5000'].'</td>
        						      <td>'.$row7['quantity10000'].'</td>
        						   </tr>';
						   
						    $row8 = $this->getProductTemplateDetails(5,3,'With Zipper','750 gm',310,210,55,'No Accessorie');
						    $html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>750gms</strong></td>
        							  <td><strong>210 X 310 X 110</strong></td>
        							  <td>'.$row8['quantity1000'].'</td>
        					          <td>'.$row8['quantity2000'].'</td>
        					          <td>'.$row8['quantity5000'].'</td>
        						      <td>'.$row8['quantity10000'].'</td>
        						   </tr>';
						   
						    $row9 = $this->getProductTemplateDetails(5,3,'With Zipper','1 kg',335,235,60,'No Accessorie');
						    $html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>1kg</strong></td>
        							  <td><strong>235 X 345 X 120</strong></td>
        							  <td>'.$row9['quantity1000'].'</td>
        					          <td>'.$row9['quantity2000'].'</td>
        					          <td>'.$row9['quantity5000'].'</td>
        						      <td>'.$row9['quantity10000'].'</td>
						            </tr>
						</tbody>
					</table><br>';
					        $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br><br>';
					        $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/18.2.jpg"" style="width:100%;height:200px">
				</div>';
							
		    $html.='<div class="panel-body">
					    <img src="../upload/admin/template_price_image/pricelist_pdf/19.1.jpg"" style="width:100%; height:530px;">
						    <table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:13px" class="">
						        <tbody>
						            <tr>
        							  <th rowspan="3" colspan="2" style="font-size:15px" ><strong>Black Paper & Green Paper<br>SUP Pouch With Zipper</strong></th>
        							  <th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
						            </tr>
						   
        						    <tr style="height:20px">
        							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        					         </tr>
						   
            						   <tr style="text-align:center">
            							  <td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
            							  <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
            							  <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
            							  <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
            						   </tr>
						   
            						   <tr style="text-align:center">
            							  <td style="width:140px;"><strong>SUP Pouch With Zipper</strong></td>
            							  <td><strong>50gms</strong></td>
            							  <td><strong>95 X 150 X 60</strong></td>
            							  <td></td>
            							  <td></td>
            							  <td></td>
            							  <td></td>
            						   </tr>';
						   
						        $row2 = $this->getProductTemplateDetails(5,3,'With Zipper','70 gm',170,110,35,'No Accessorie');
						        $html.='<tr style="text-align:center">
                							  <td><strong>SUP Pouch With Zipper</strong></td>
                							  <td><strong>70gms</strong></td>
                							  <td><strong>110 X 170 X 70</strong></td>
                							  <td>'.$row2['quantity1000'].'</td>
                					          <td>'.$row2['quantity2000'].'</td>
                					          <td>'.$row2['quantity5000'].'</td>
                						      <td>'.$row2['quantity10000'].'</td>
                						 </tr>
						   
            						   <tr style="text-align:center">
            							  <td><strong>SUP Pouch With Zipper</strong></td>
            							  <td><strong>100gms</strong></td>
            							  <td><strong>120 X 200 X 80</strong></td>
            							  <td></td>
            							  <td></td>
            							  <td></td>
            							  <td></td>
            						   </tr>';
						   
						    $row3 = $this->getProductTemplateDetails(5,3,'With Zipper','150 gm',210,130,40,'No Accessorie');
						    $html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>150gms</strong></td>
        							  <td><strong>130 X 210 X 80</strong></td>
        							  <td>'.$row3['quantity1000'].'</td>
        					          <td>'.$row3['quantity2000'].'</td>
        					          <td>'.$row3['quantity5000'].'</td>
        						      <td>'.$row3['quantity10000'].'</td>
        						   </tr>';
						   
						    $row4 = $this->getProductTemplateDetails(5,3,'With Zipper','250 gm',230,160,45,'No Accessorie');
						    $html.='<tr style="text-align:center">
        							 <td><strong>SUP Pouch With Zipper</strong></td>
        							 <td><strong>250gms</strong></td>
        							 <td><strong>160 X 230 X 90</strong></td>
        							 <td>'.$row4['quantity1000'].'</td>
        					         <td>'.$row4['quantity2000'].'</td>
        					         <td>'.$row4['quantity5000'].'</td>
        						     <td>'.$row4['quantity10000'].'</td>
        						   </tr>
						   
        						   <tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>350gms</strong></td>
        							  <td><strong>170 X 250 X 90</strong></td>
        							  <td></td>
        							  <td></td>
        							  <td></td>
        							  <td></td>
        						   </tr>';
						   
						     $row5 = $this->getProductTemplateDetails(5,3,'With Zipper','500 gm',260,190,55,'No Accessorie');
						     $html.='<tr style="text-align:center">
            							 <td><strong>SUP Pouch With Zipper</strong></td>
            							 <td><strong>500gms</strong></td>
            							 <td><strong>190 X 260 X 110</strong></td>
            							 <td>'.$row5['quantity1000'].'</td>
            					         <td>'.$row5['quantity2000'].'</td>
            					         <td>'.$row5['quantity5000'].'</td>
            						     <td>'.$row5['quantity10000'].'</td>
            						  </tr>
						   
            						   <tr style="text-align:center">
            							  <td><strong>SUP Pouch With Zipper</strong></td>
            							  <td><strong>750gms</strong></td>
            							  <td><strong>210 X 310 X 110</strong></td>
            							  <td></td>
            							  <td></td>
            							  <td></td>
            							  <td></td>
            						   </tr>';
						   
						    $row6 = $this->getProductTemplateDetails(5,3,'With Zipper','1 kg',335,235,60,'No Accessorie');
						    $html.='<tr style="text-align:center">
            							 <td><strong>SUP Pouch With Zipper</strong></td>
            							 <td><strong>1kg</strong></td>
            							 <td><strong>235 X 345 X 120</strong></td>
            							 <td>'.$row6['quantity1000'].'</td>
            					         <td>'.$row6['quantity2000'].'</td>
            					         <td>'.$row6['quantity5000'].'</td>
            						     <td>'.$row6['quantity10000'].'</td>
        						   </tr>
						</tbody>
					</table><br>';
					        $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br>';
					        $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/19.2.jpg"" style="width:100%;height:200px">
				</div>	';
			
		    $html.='<div class="panel-body">
					    <img src="../upload/admin/template_price_image/pricelist_pdf/20.1.jpg"" style="width:100%; height:470px;">
						    <table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:13px" class="">
						        <tbody>
						            <tr>
        							  <th rowspan="3" colspan="2" style="font-size:14px" ><strong>One Side Brown Paper & One<br />Side Clear SUP<br>Pouch With Zipper</strong></th>
        							  <th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
        							  <th><strong>Price Per</strong></th>
						            </tr>
						   
            						   <tr style="height:20px">
            							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            					         </tr>
            					         
                        				   <tr style="text-align:center">
                        					  <td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
                        					  <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
                        					  <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
                        					  <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
                        				   </tr>';
						   
						        $row1 = $this->getProductTemplateDetails(5,3,'No zip','28 gm',130,80,25,'No Accessorie');
						        $html.='<tr style="text-align:center">
                							 <td style="width:140px;"><strong>SUP Pouch With No Zipper</strong></td>
                							 <td><strong>28gms</strong></td>
                							 <td><strong>80 X 130 X 50</strong></td>
                							 <td>'.$row1['quantity1000'].'</td>
                					         <td>'.$row1['quantity2000'].'</td>
                					         <td>'.$row1['quantity5000'].'</td>
                						     <td>'.$row1['quantity10000'].'</td>
						                </tr>';
						   
						        $row2 = $this->getProductTemplateDetails(5,3,'With Zipper','28 gm',130,80,25,'No Accessorie');
						        $html.='<tr style="text-align:center">
                							 <td><strong>SUP Pouch With Zipper</strong></td>
                							 <td><strong>28gms</strong></td>
                							 <td><strong>80 X 130 X 50</strong></td>
                							 <td>'.$row2['quantity1000'].'</td>
                					         <td>'.$row2['quantity2000'].'</td>
                					         <td>'.$row2['quantity5000'].'</td>
                						     <td>'.$row2['quantity10000'].'</td>
                						 </tr>';
						   
						       $row3 = $this->getProductTemplateDetails(5,3,'With Zipper','50 gm',150,95,30,'No Accessorie');
						       $html.='<tr style="text-align:center">
            							 <td><strong>SUP Pouch With Zipper</strong></td>
            							 <td><strong>50gms</strong></td>
            							 <td><strong>95 X 150 X 60</strong></td>
            							 <td>'.$row3['quantity1000'].'</td>
            					         <td>'.$row3['quantity2000'].'</td>
            					         <td>'.$row3['quantity5000'].'</td>
            						     <td>'.$row3['quantity10000'].'</td>
            						   </tr>';
						   
						      $row4 = $this->getProductTemplateDetails(5,3,'With Zipper','70 gm',170,110,35,'No Accessorie');
						      $html.='<tr style="text-align:center">
            							  <td><strong>SUP Pouch With Zipper</strong></td>
            							  <td><strong>70gms</strong></td>
            							  <td><strong>110 X 170 X 70</strong></td>
            							  <td>'.$row4['quantity1000'].'</td>
            					          <td>'.$row4['quantity2000'].'</td>
            					          <td>'.$row4['quantity5000'].'</td>
            						      <td>'.$row4['quantity10000'].'</td>
            						   </tr>';
						   
						    $row5 = $this->getProductTemplateDetails(5,3,'With Zipper','100 gm',200,120,40,'No Accessorie');
						    $html.='<tr style="text-align:center">
            							 <td><strong>SUP Pouch With Zipper</strong></td>
            							 <td><strong>100gms</strong></td>
            							 <td><strong>120 X 200 X 80</strong></td>
            							 <td>'.$row5['quantity1000'].'</td>
            					         <td>'.$row5['quantity2000'].'</td>
            					         <td>'.$row5['quantity5000'].'</td>
            						     <td>'.$row5['quantity10000'].'</td>
						             </tr>';
						   
						    $row6 = $this->getProductTemplateDetails(5,3,'With Zipper','150 gm',210,130,40,'No Accessorie');
						    $html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>150gms</strong></td>
        							  <td><strong>130 X 210 X 80</strong></td>
        							  <td>'.$row6['quantity1000'].'</td>
        					          <td>'.$row6['quantity2000'].'</td>
        					          <td>'.$row6['quantity5000'].'</td>
        						      <td>'.$row6['quantity10000'].'</td>
        						   </tr>';
						   
						    $row7 = $this->getProductTemplateDetails(5,3,'With Zipper','250 gm',230,160,45,'No Accessorie');
						    $html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>250gms</strong></td>
        							  <td><strong>160 X 230 X 90</strong></td>
        							  <td>'.$row7['quantity1000'].'</td>
        					          <td>'.$row7['quantity2000'].'</td>
        					          <td>'.$row7['quantity5000'].'</td>
        						      <td>'.$row7['quantity10000'].'</td>
        						   </tr>';
						   
						    $row8 = $this->getProductTemplateDetails(5,3,'With Zipper','350 gm',250,170,45,'No Accessorie');
						    $html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>350gms</strong></td>
        							  <td><strong>170 X 250 X 90</strong></td>
        							  <td>'.$row8['quantity1000'].'</td>
        					          <td>'.$row8['quantity2000'].'</td>
        					          <td>'.$row8['quantity5000'].'</td>
        						      <td>'.$row8['quantity10000'].'</td>
        						   </tr>';
						   
						    $row9 = $this->getProductTemplateDetails(5,3,'With Zipper','500 gm',260,190,55,'No Accessorie');
						    $html.='<tr style="text-align:center">
            							 <td><strong>SUP Pouch With Zipper</strong></td>
            							 <td><strong>500gms</strong></td>
            							 <td><strong>190 X 260 X 110</strong></td>
            							 <td>'.$row9['quantity1000'].'</td>
            					         <td>'.$row9['quantity2000'].'</td>
            					         <td>'.$row9['quantity5000'].'</td>
            						     <td>'.$row9['quantity10000'].'</td>
            						 </tr>';
						   
						    $row10 = $this->getProductTemplateDetails(5,3,'With Zipper','750 gm',310,210,55,'No Accessorie');
						    $html.='<tr style="text-align:center">
        							  <td><strong>SUP Pouch With Zipper</strong></td>
        							  <td><strong>750gms</strong></td>
        							  <td><strong>210 X 310 X 110</strong></td>
        							  <td>'.$row10['quantity1000'].'</td>
        					          <td>'.$row10['quantity2000'].'</td>
        					          <td>'.$row10['quantity5000'].'</td>
        						      <td>'.$row10['quantity10000'].'</td>
        						   </tr>';
						   
						    $row11 = $this->getProductTemplateDetails(5,3,'With Zipper','1 kg',335,235,60,'No Accessorie');
						    $html.='<tr style="text-align:center">
            							 <td><strong>SUP Pouch With Zipper</strong></td>
            							 <td><strong>1kg</strong></td>
            							 <td><strong>235 X 345 X 120</strong></td>
            							 <td>'.$row11['quantity1000'].'</td>
            					         <td>'.$row11['quantity2000'].'</td>
            					         <td>'.$row11['quantity5000'].'</td>
            						     <td>'.$row11['quantity10000'].'</td>
						            </tr>
						</tbody>
					</table><br>';
					        $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br><br><br>';
					        $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/20.2.jpg"" style="width:100%">
				</div>	';
					
		$html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/21.1.jpg"" style="width:100%; height:500px;">
						<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
						<tbody>
						   <tr>
							  <th rowspan="3" colspan="2" style="font-size:18px" ><strong>Side Gusset Bags</strong></th>
							  <th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
						   </tr>
						   
						   <tr style="height:20px">
							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
					         </tr>
						   
						   <tr style="text-align:center">
							  <td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
						   </tr>';
						   
						    $row1 = $this->getProductTemplateDetails(2,1,'No zip','100 gm',210,70,15,'No Accessorie');
						    $html.='<tr style="text-align:center">
							  <td ><strong>Side Gusset Bags</strong></td>
							  <td><strong>100gms</strong></td>
							  <td><strong>70 X 210 X 30</strong></td>
							  <td>'.$row1['quantity1000'].'</td>
					         <td>'.$row1['quantity2000'].'</td>
					         <td>'.$row1['quantity5000'].'</td>
						     <td>'.$row1['quantity10000'].'</td>
						   </tr>';
						   
						    $row2 = $this->getProductTemplateDetails(2,1,'No zip','250 gm',260,80,25,'No Accessorie');
						    $html.='<tr style="text-align:center">
							  <td ><strong>Side Gusset Bags</strong></td>
							  <td><strong>250gms</strong></td>
							  <td><strong>80 X 260 X 50</strong></td>
							  <td>'.$row2['quantity1000'].'</td>
					         <td>'.$row2['quantity2000'].'</td>
					         <td>'.$row2['quantity5000'].'</td>
						     <td>'.$row2['quantity10000'].'</td>
						   </tr>';
						   
						    $row3 = $this->getProductTemplateDetails(2,1,'No zip','500 gm',370,85,30,'No Accessorie');
						    $html.='<tr style="text-align:center">
							  <td ><strong>Side Gusset Bags</strong></td>
							  <td><strong>500gms</strong></td>
							  <td><strong>85 X 360 X 60</strong></td>
							  <td>'.$row3['quantity1000'].'</td>
					         <td>'.$row3['quantity2000'].'</td>
					         <td>'.$row3['quantity5000'].'</td>
						     <td>'.$row3['quantity10000'].'</td>
						   </tr>';
						   
						    $row4 = $this->getProductTemplateDetails(2,1,'No zip','1 kg',400,135,40,'No Accessorie');
						    $html.='<tr style="text-align:center">
							  <td ><strong>Side Gusset Bags</strong></td>
							  <td><strong>1kg</strong></td>
							  <td><strong>135 X 390 X 70</strong></td>
							 <td>'.$row4['quantity1000'].'</td>
					         <td>'.$row4['quantity2000'].'</td>
					         <td>'.$row4['quantity5000'].'</td>
						     <td>'.$row4['quantity10000'].'</td>
						   </tr>';
						   
						    $row5 = $this->getProductTemplateDetails(2,1,'No zip','2 kg',495,170,55,'No Accessorie');
						    $html.='<tr style="text-align:center">
							  <td ><strong>Side Gusset Bags</strong></td>
							  <td><strong>2kg</strong></td>
							  <td><strong>170 X 495 X 110</strong></td>
							 <td>'.$row5['quantity1000'].'</td>
					         <td>'.$row5['quantity2000'].'</td>
					         <td>'.$row5['quantity5000'].'</td>
						     <td>'.$row5['quantity10000'].'</td>
						   </tr>';
						   
						    $row6 = $this->getProductTemplateDetails(2,1,'No zip','3 kg',510,210,55,'No Accessorie');
						    $html.='<tr style="text-align:center">
							  <td ><strong>Side Gusset Bags</strong></td>
							  <td><strong>3kg</strong></td>
							  <td><strong>210 X 510 X 110</strong></td>
							  <td>'.$row6['quantity1000'].'</td>
					         <td>'.$row6['quantity2000'].'</td>
					         <td>'.$row6['quantity5000'].'</td>
						     <td>'.$row6['quantity10000'].'</td>
						   </tr>
						</tbody>
					</table>
					<br><br><br>
					<img src="../upload/admin/template_price_image/pricelist_pdf/21.2.jpg"" style="width:100%">
				</div>';
					
		$html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/22.1.jpg"" style="width:100%">
						<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
						<tbody>
						   <tr>
							  <th rowspan="3" colspan="2" style="font-size:18px" ><strong>Side Gusset Bags</strong></th>
							  <th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
						   </tr>
						   
						   <tr style="height:20px">
							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
						        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
							    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
					         </tr>
						   
						   <tr style="text-align:center">
							  <td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
						   </tr>';
						   
						   $row1 = $this->getProductTemplateDetails(5,1,'No zip','250 gm',260,80,25,'No Accessorie');
						    $html.='<tr style="text-align:center">
							  <td ><strong>Side Gusset Bags</strong></td>
							  <td><strong>250gms</strong></td>
							  <td><strong>80 X 260 X 50</strong></td>
							  <td>'.$row1['quantity1000'].'</td>
					          <td>'.$row1['quantity2000'].'</td>
					          <td>'.$row1['quantity5000'].'</td>
						      <td>'.$row1['quantity10000'].'</td>
						   </tr>';
						   
						   $row2 = $this->getProductTemplateDetails(5,1,'No zip','500 gm',370,85,30,'No Accessorie');
						    $html.='<tr style="text-align:center">
							  <td ><strong>Side Gusset Bags</strong></td>
							  <td><strong>500gms</strong></td>
							  <td><strong>85 X 360 X 60</strong></td>
							  <td>'.$row2['quantity1000'].'</td>
					          <td>'.$row2['quantity2000'].'</td>
					          <td>'.$row2['quantity5000'].'</td>
						      <td>'.$row2['quantity10000'].'</td>
						   </tr>';
						   
						    $row3 = $this->getProductTemplateDetails(5,1,'No zip','1 kg',400,135,40,'No Accessorie');
						    $html.='<tr style="text-align:center">
							  <td ><strong>Side Gusset Bags</strong></td>
							  <td><strong>1kg</strong></td>
							  <td><strong>135 X 390 X 70</strong></td>
							  <td>'.$row3['quantity1000'].'</td>
					          <td>'.$row3['quantity2000'].'</td>
					          <td>'.$row3['quantity5000'].'</td>
						      <td>'.$row3['quantity10000'].'</td>
						   </tr>
						</tbody>	
					</table><br>';
					        $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br><br>';
					        $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/22.2.jpg"" style="width:100%">
				</div>';
						
		$html.='<div class="panel-body">
				<img src="../upload/admin/template_price_image/pricelist_pdf/23.1.jpg"" style="width:100%; height:620px;">
					<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
					<tbody>
					   <tr>
						  <th rowspan="3"  style="font-size:18px" ><strong>Size</strong></th>
						  <th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
						  <th><strong>Price Per</strong></th>
					      <th><strong>Price Per</strong></th>
						  <th><strong>Price Per</strong></th>
						  <th><strong>Price Per</strong></th>
					   </tr>
					   
					   <tr style="height:20px">
						    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
					        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
					        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
						    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
					   </tr>
					   
					   <tr style="text-align:center">
						  <td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
						  <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
						  <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
						  <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
					   </tr>';
					   
					    $row1 = $this->getProductTemplateDetails(3,12,'No zip','100 ml',140,100,30,'No Accessorie');
					    $html.='<tr style="text-align:center">
        						  <td ><strong>50gms/100ml</strong></td>
        						  <td><strong>100 X 140 X 60</strong></td>
        						  <td>'.$row1['quantity1000'].'</td>
        					      <td>'.$row1['quantity2000'].'</td>
        					      <td>'.$row1['quantity5000'].'</td>
        						  <td>'.$row1['quantity10000'].'</td>
					            </tr>';
					   
					    $row2 = $this->getProductTemplateDetails(3,12,'No zip','200 ml',170,110,35,'No Accessorie');
					    $html.='<tr style="text-align:center">
        						  <td ><strong>70gms/200ml</strong></td>
        						  <td><strong>110 X 170 X 80</strong></td>
        						  <td>'.$row2['quantity1000'].'</td>
        					      <td>'.$row2['quantity2000'].'</td>
        					      <td>'.$row2['quantity5000'].'</td>
        						  <td>'.$row2['quantity10000'].'</td>
        					   </tr>';
					   
					    $row3 = $this->getProductTemplateDetails(3,12,'No zip','350 ml',200,120,40,'No Accessorie');
					    $html.='<tr style="text-align:center">
        						  <td ><strong>100gms/350ml</strong></td>
        						  <td><strong>120 X 200 X 80</strong></td>
        						  <td>'.$row3['quantity1000'].'</td>
        					      <td>'.$row3['quantity2000'].'</td>
        					      <td>'.$row3['quantity5000'].'</td>
        						  <td>'.$row3['quantity10000'].'</td>
					            </tr>';
					   
					    $row4 = $this->getProductTemplateDetails(3,12,'No zip','500 ml',210,140,40,'No Accessorie');
					    $html.='<tr style="text-align:center">
        						  <td ><strong>150gms/500ml</strong></td>
        						  <td><strong>140 X 210 X 80</strong></td>
        						  <td>'.$row4['quantity1000'].'</td>
        					      <td>'.$row4['quantity2000'].'</td>
        					      <td>'.$row4['quantity5000'].'</td>
        						  <td>'.$row4['quantity10000'].'</td>
        					   </tr>';
					   
					    $row5 = $this->getProductTemplateDetails(3,12,'No zip','750 ml',230,160,45,'No Accessorie');
					    $html.='<tr style="text-align:center">
        						  <td ><strong>250gms/750ml</strong></td>
        						  <td><strong>160 X 230 X 90</strong></td>
        						  <td>'.$row5['quantity1000'].'</td>
        					      <td>'.$row5['quantity2000'].'</td>
        					      <td>'.$row5['quantity5000'].'</td>
        						  <td>'.$row5['quantity10000'].'</td>
        					   </tr>';
					   
					    $row6 = $this->getProductTemplateDetails(3,12,'No zip','1 ltr',260,190,55,'No Accessorie');
					    $html.='<tr style="text-align:center">
        						  <td ><strong>500gms/1Ltr</strong></td>
        						  <td><strong>190 X 260 X 110</strong></td>
        						  <td>'.$row6['quantity1000'].'</td>
        					      <td>'.$row6['quantity2000'].'</td>
        					      <td>'.$row6['quantity5000'].'</td>
        						  <td>'.$row6['quantity10000'].'</td>
        					   </tr>
					</tbody>
				</table><br>';
					    $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div>';
				        $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/23.2.jpg"" style="width:100%">
			</div>	';
		
	         $html.='<div class="panel-body">
			        <img src="../upload/admin/template_price_image/pricelist_pdf/24.1.jpg"" style="width:100%; height:600px">
				        <table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
			            	<tbody>
            				   <tr>
            					  <th rowspan="3"  style="font-size:18px" ><strong>Size</strong></th>
            					  <th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
            					  <th><strong>Price Per</strong></th>
            					  <th><strong>Price Per</strong></th>
            					  <th><strong>Price Per</strong></th>
            					  <th><strong>Price Per</strong></th>
            		           </tr>
				   
            				    <tr style="height:20px">
            					    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            				        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            				        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            					    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            				   </tr>

            				   <tr style="text-align:center">
            					  <td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
            					  <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
            					  <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
            					  <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
            				   </tr>';
				   
    				     $row1 = $this->getProductTemplateDetails(2,42,'No zip','50 ml',110,80,25,'No Accessorie');
    					 $html.='<tr style="text-align:center">
            					  <td ><strong>50ml</strong></td>
            					  <td><strong>80 X 110 X 50</strong></td>
            					  <td>'.$row1['quantity1000'].'</td>
            				      <td>'.$row1['quantity2000'].'</td>
            				      <td>'.$row1['quantity5000'].'</td>
            					  <td>'.$row1['quantity10000'].'</td>
            				   </tr>';
				   
    				     $row2 = $this->getProductTemplateDetails(2,42,'No zip','100 ml',130,90,25,'No Accessorie');
    					 $html.='<tr style="text-align:center">
            					  <td ><strong>100ml</strong></td>
            					  <td><strong>90 X 130 X 50</strong></td>
            					  <td>'.$row2['quantity1000'].'</td>
            				      <td>'.$row2['quantity2000'].'</td>
            				      <td>'.$row2['quantity5000'].'</td>
            					  <td>'.$row2['quantity10000'].'</td>
    				         </tr>';
				   
    				     $row3 = $this->getProductTemplateDetails(2,42,'No zip','250 ml',170,110,35,'No Accessorie');
    					 $html.='<tr style="text-align:center">
            					  <td ><strong>250ml</strong></td>
            					  <td><strong>120 X 170 X 80</strong></td>
            					  <td>'.$row3['quantity1000'].'</td>
            				      <td>'.$row3['quantity2000'].'</td>
            				      <td>'.$row3['quantity5000'].'</td>
            					  <td>'.$row3['quantity10000'].'</td>
    				            </tr>';
				   
    				     $row4 = $this->getProductTemplateDetails(2,42,'No zip','500 ml',200,130,40,'No Accessorie');
    					 $html.='<tr style="text-align:center">
            					  <td ><strong>500ml</strong></td>
            					  <td><strong>140 X 210 X 80</strong></td>
            					  <td>'.$row4['quantity1000'].'</td>
            				      <td>'.$row4['quantity2000'].'</td>
            				      <td>'.$row4['quantity5000'].'</td>
            					  <td>'.$row4['quantity10000'].'</td>
    				            </tr>';
				   
        				    $row5 = $this->getProductTemplateDetails(2,42,'No zip','1 ltr',230,160,45,'No Accessorie');
        					$html.=' <tr style="text-align:center">
                					  <td ><strong>1Ltr</strong></td>
                					  <td><strong>170 X 265 X 90</strong></td>
                					  <td>'.$row5['quantity1000'].'</td>
                				      <td>'.$row5['quantity2000'].'</td>
                				      <td>'.$row5['quantity5000'].'</td>
                					  <td>'.$row5['quantity10000'].'</td>
                				   </tr>';
				   
				     $row5 = $this->getProductTemplateDetails(2,42,'No zip','750 ml',230,160,45,'No Accessorie');
					 $html.='<tr style="text-align:center">
					  <td ><strong>2Ltr</strong></td>
					  <td><strong>220 X 330 X 110</strong></td>
					  <td>'.$row5['quantity1000'].'</td>
				      <td>'.$row5['quantity2000'].'</td>
				      <td>'.$row5['quantity5000'].'</td>
					  <td>'.$row5['quantity10000'].'</td>
				   </tr>
				</tbody>
			</table><br>';
					    $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div>';
			            $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/24.2.jpg"" style="width:100%">
		</div>';
		
		$html.='<div class="panel-body">
				<img src="../upload/admin/template_price_image/pricelist_pdf/25.1.jpg"" style="width:100%; height:600px;">
					<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
					<tbody>
					   <tr>
						  <th rowspan="3" colspan="3" style="font-size:16px" ><strong>Chocolate Bar Packaging,<br />Energy Bar Packaging</strong></th>
						  <th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
						  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
					   </tr>
					   
					  <tr style="height:20px">
					    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
				        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
				        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
					    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
				     </tr>
					   
					   <tr style="text-align:center">
						  <td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
						  <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
						  <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
						  <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
					   </tr>';
					   
					 $row1 = $this->getProductTemplateDetails(3,20,'No zip','30-50 gms',130,70,0,'No Accessorie');
					 $html.='<tr style="text-align:center">
						  <td rowspan="4" ><strong>Chocolate Bar Packaging,<br />Energy Bar Packaging</strong></td>
						  <td rowspan="2"><strong>Crystal <br />Clear</strong></td>
						  <td ><strong>50gms</strong></td>
						  <td><strong>70 X 130 </strong></td>
						  <td>'.$row1['quantity1000'].'</td>
    				      <td>'.$row1['quantity2000'].'</td>
    				      <td>'.$row1['quantity5000'].'</td>
    					  <td>'.$row1['quantity10000'].'</td>
					   </tr>';
					   
					 $row2 = $this->getProductTemplateDetails(3,20,'No zip','50-70 gms',155,80,0,'No Accessorie');
					 $html.='<tr style="text-align:center">
						  <td ><strong>70gms</strong></td>
						  <td><strong>80 X 155</strong></td>
						  <td>'.$row2['quantity1000'].'</td>
    				      <td>'.$row2['quantity2000'].'</td>
    				      <td>'.$row2['quantity5000'].'</td>
    					  <td>'.$row2['quantity10000'].'</td>
					   </tr>';
					   
					  $row3 = $this->getProductTemplateDetails(5,20,'No zip','30-50 gms',130,70,0,'No Accessorie');
					 $html.='<tr style="text-align:center">
						  <td rowspan="2"><strong>Brown <br />Craft<br />Paper</strong></td>
						  <td ><strong>50gms</strong></td>
						  <td><strong>70 X 130</strong></td>
						  <td>'.$row3['quantity1000'].'</td>
    				      <td>'.$row3['quantity2000'].'</td>
    				      <td>'.$row3['quantity5000'].'</td>
    					  <td>'.$row3['quantity10000'].'</td>
					   </tr>';
					   
					  $row4 = $this->getProductTemplateDetails(5,20,'No zip','50-70 gms',155,80,0,'No Accessorie');
					 $html.='<tr style="text-align:center">
						  <td ><strong>70gms</strong></td>
						  <td><strong>80 X 155</strong></td>
						  <td>'.$row4['quantity1000'].'</td>
    				      <td>'.$row4['quantity2000'].'</td>
    				      <td>'.$row4['quantity5000'].'</td>
    					  <td>'.$row4['quantity10000'].'</td>
					   </tr>
					</tbody>
				</table>
				<br><br>
				<img src="../upload/admin/template_price_image/pricelist_pdf/25.2.jpg"" style="width:100%">
			</div>';
			 		
		$html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/26.1.jpg"" style="width:100%; height:550px;">
					<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
						<tbody>
						   <tr>
							  <th rowspan="3" colspan="2" style="font-size:16px" ><strong>Jerky Packaging /<br />Dried Meat Packaging</strong></th>
							  <th rowspan="3"><strong>Dimension<br>Width X Height <br /> (mm)</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
						   </tr>
						   
						 <tr style="height:20px">
        				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
				         </tr>
					   
						   
						   <tr style="text-align:center">
							  <td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
						   </tr>';
						   
						     $row1 = $this->getProductTemplateDetails(3,19,'No zip','60 gm',180,125,0,'No Accessorie');
					         $html.='<tr style="text-align:center">
							  <td rowspan="2" ><strong>Jerky Packaging /<br />Dried Meat <br />Packaging</strong></td>
							  <td ><strong>30-60gms <br /> No Zipper</strong></td>
							  <td ><strong>125 X 180</strong></td>
							  <td>'.$row1['quantity1000'].'</td>
        				      <td>'.$row1['quantity2000'].'</td>
        				      <td>'.$row1['quantity5000'].'</td>
        					  <td>'.$row1['quantity10000'].'</td>
						   </tr>';
						   
						     $row2 = $this->getProductTemplateDetails(3,19,'With Zipper','90 gm',225,172,0,'No Accessorie');
					         $html.='<tr style="text-align:center">
							  <td ><strong>60-90gms <br /> With Zipper</strong></td>
							  <td ><strong>175 X 225</strong></td>
							  <td>'.$row2['quantity1000'].'</td>
        				      <td>'.$row2['quantity2000'].'</td>
        				      <td>'.$row2['quantity5000'].'</td>
        					  <td>'.$row2['quantity10000'].'</td>
						   </tr>
						</tbody>
					</table>
					<br><br><br>
					<img src="../upload/admin/template_price_image/pricelist_pdf/26.2.jpg"" style="width:100%">
				</div>';
					
		$html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/27.1.jpg"" style="width:100%;">
					<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
						<tbody>
						   <tr>
							  <th rowspan="3"  style="font-size:16px" ><strong>Palstic Mailing <br />Envelopes</strong></th>
							  <th rowspan="3"><strong>Dimension<br>W  X H X Flap </strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
						   </tr>
						   
					    <tr style="height:20px">
        				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
			            </tr>
						   
						   <tr style="text-align:center">
							  <td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
						   </tr>';
						   
				         $row1 = $this->getProductTemplateDetails(2,10,'No zip','6 inch',9,6,2,'No Accessorie');
				         $html.='<tr style="text-align:center">
							  <td rowspan="5" ><strong>Palstic Mailing <br />Envelopes</strong></td>
							  <td ><strong>150 X 235 X 50</strong></td>
							  <td>'.$row1['quantity1000'].'</td>
        				      <td>'.$row1['quantity2000'].'</td>
        				      <td>'.$row1['quantity5000'].'</td>
        					  <td>'.$row1['quantity10000'].'</td>
						   </tr>';
						   
					     $row2 = $this->getProductTemplateDetails(2,10,'No zip','7.5 inch',11,8,2,'No Accessorie');
				         $html.='<tr style="text-align:center">
							  <td ><strong>190 X 265 X 50</strong></td>
							  <td>'.$row2['quantity1000'].'</td>
        				      <td>'.$row2['quantity2000'].'</td>
        				      <td>'.$row2['quantity5000'].'</td>
        					  <td>'.$row2['quantity10000'].'</td>
        					  </tr>';
						   
				        $row3 = $this->getProductTemplateDetails(2,10,'No zip','10 inch',13,10,2,'No Accessorie');
				        $html.='<tr style="text-align:center">
							  <td ><strong>255 X 335 X 50</strong></td>
							  <td>'.$row3['quantity1000'].'</td>
        				      <td>'.$row3['quantity2000'].'</td>
        				      <td>'.$row3['quantity5000'].'</td>
        					  <td>'.$row3['quantity10000'].'</td>
						   </tr>';
						   
				         $row4 = $this->getProductTemplateDetails(2,10,'No zip','12 inch',16,12,2,'No Accessorie');
				         $html.='<tr style="text-align:center">
							  <td ><strong>300 X 395 X 50</strong></td>
							  <td>'.$row4['quantity1000'].'</td>
        				      <td>'.$row4['quantity2000'].'</td>
        				      <td>'.$row4['quantity5000'].'</td>
        					  <td>'.$row4['quantity10000'].'</td>
						   </tr>';
						   
				         $row5 = $this->getProductTemplateDetails(2,10,'No zip','14 inch',19,14,2,'No Accessorie');
				         $html.='<tr style="text-align:center">
							  <td ><strong>355 X 490 X 50</strong></td>
							  <td>'.$row5['quantity1000'].'</td>
        				      <td>'.$row5['quantity2000'].'</td>
        				      <td>'.$row5['quantity5000'].'</td>
        					  <td>'.$row5['quantity10000'].'</td>
						   </tr>
						</tbody>
					</table>
					<br><br>
					<img src="../upload/admin/template_price_image/pricelist_pdf/27.2.jpg"" style="width:100%">
				</div>';
							
		$html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/28.1.jpg"" style="width:100%;">
					<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
						<tbody>
						   <tr>
							  <th rowspan="3"  style="font-size:16px" ><strong>Storezo Bag<br />Also Bulk Packaging Bag</strong></th>
							  <th rowspan="3" style="font-size:16px"><strong>Size</strong></th>
							  <th rowspan="3"><strong>Dimension<br>W  X H <br />(mm)</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
						   </tr>
						   
						  <tr style="height:20px">
        				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
			             </tr>
						   
						   <tr style="text-align:center">
							  <td style="color: #d72a35;"><strong>Qty "+5000</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
						   </tr>';
						   
						 $row1 = $this->getProductTemplateDetails(2,18,'No zip','30 kg',1100,650,0,'No Accessorie');
				         $html.='<tr style="text-align:center">
							  <td rowspan="3" ><strong>Storezo Bag<br />Also Bulk Packaging Bag</strong></td>
							  <td ><strong>30Kgs</strong></td>
							  <td ><strong>650 X 1100</strong></td>
							  <td>'.$row1['quantity1000'].'</td>
        				      <td>'.$row1['quantity2000'].'</td>
        				      <td>'.$row1['quantity5000'].'</td>
        					  <td>'.$row1['quantity10000'].'</td>
						   </tr>';
						   
						 $row2 = $this->getProductTemplateDetails(2,18,'No zip','75 kg',1300,750,0,'No Accessorie');
				         $html.='<tr style="text-align:center">
							  <td ><strong>75Kgs</strong></td>
							  <td ><strong>750 X 1300</strong></td>
							  <td>'.$row2['quantity1000'].'</td>
        				      <td>'.$row2['quantity2000'].'</td>
        				      <td>'.$row2['quantity5000'].'</td>
        					  <td>'.$row2['quantity10000'].'</td>
						   </tr>';
						   
						 $row3 = $this->getProductTemplateDetails(2,18,'No zip','100 kg',1500,750,0,'No Accessorie');
				         $html.='<tr style="text-align:center">
							  <td ><strong>100Kgs</strong></td>
							  <td ><strong>750 X 1500</strong></td>
							  <td>'.$row3['quantity1000'].'</td>
        				      <td>'.$row3['quantity2000'].'</td>
        				      <td>'.$row3['quantity5000'].'</td>
        					  <td>'.$row3['quantity10000'].'</td>
						   </tr>
						</tbody>								
					</table>
					<br><br>
					<img src="../upload/admin/template_price_image/pricelist_pdf/28.2.jpg"" style="width:100%">
				</div>';
					
		 $html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/30.1.jpg"" style="width:100%; height:600px">
					<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
						<tbody>
							<tr>
							   <th rowspan="3" colspan="2" style="font-size:14px" ><strong>Flat Bottom Pouch <br />No Zipper(Brown Paper)</strong></th>
							   <th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
							   <th><strong>Price Per</strong></th>
							   <th><strong>Price Per</strong></th>
							   <th><strong>Price Per</strong></th>
							   <th><strong>Price Per</strong></th>
							</tr>
							
						<tr style="height:20px">
        				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
			           </tr>
							
							<tr style="text-align:center">
							   <td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
							   <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
							   <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
							   <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
							</tr>';
							
						 $row1 = $this->getProductTemplateDetails(2,7,'No zip','114 gm',185,95,30,'No Accessorie');
				         $html.='<tr style="text-align:center">
							   <td style="width:160px;"><strong>Flat Bottom Pouch NO Zipper</strong></td>
							   <td><strong>114gms</strong></td>
							   <td><strong>95 X 185 X 60</strong></td>
							   <td>'.$row1['quantity1000'].'</td>
        				       <td>'.$row1['quantity2000'].'</td>
        				       <td>'.$row1['quantity5000'].'</td>
        					   <td>'.$row1['quantity10000'].'</td>
							</tr>';
							
					     $row2 = $this->getProductTemplateDetails(2,7,'No zip','250 gm',230,95,35,'No Accessorie');
				         $html.='<tr style="text-align:center">
							   <td ><strong>Flat Bottom Pouch NO Zipper</strong></td>
							   <td><strong>250gms</strong></td>
							   <td><strong>95 X 230 X 70</strong></td>
							    <td>'.$row2['quantity1000'].'</td>
        				       <td>'.$row2['quantity2000'].'</td>
        				       <td>'.$row2['quantity5000'].'</td>
        					   <td>'.$row2['quantity10000'].'</td>
							</tr>';
							
						 $row3 = $this->getProductTemplateDetails(2,7,'No zip','340 gm',270,100,35,'No Accessorie');
				         $html.='<tr style="text-align:center">
							   <td ><strong>Flat Bottom Pouch NO Zipper</strong></td>
							   <td><strong>340gms</strong></td>
							   <td><strong>100 X 270 X 70</strong></td>
							   <td>'.$row3['quantity1000'].'</td>
        				       <td>'.$row3['quantity2000'].'</td>
        				       <td>'.$row3['quantity5000'].'</td>
        					   <td>'.$row3['quantity10000'].'</td>
							</tr>';
							
						$row4 = $this->getProductTemplateDetails(2,7,'No zip','500 gm',280,110,40,'No Accessorie');
				         $html.='<tr style="text-align:center">
							   <td ><strong>Flat Bottom Pouch NO Zipper</strong></td>
							   <td><strong>500gms</strong></td>
							   <td><strong>110 X 280 X 80</strong></td>
							    <td>'.$row4['quantity1000'].'</td>
        				       <td>'.$row4['quantity2000'].'</td>
        				       <td>'.$row4['quantity5000'].'</td>
        					   <td>'.$row4['quantity10000'].'</td>
							</tr>';
							
						$row5 = $this->getProductTemplateDetails(2,7,'No zip','1 kg',350,140,48,'No Accessorie');
				         $html.='<tr style="text-align:center">
							   <td ><strong>Flat Bottom Pouch NO Zipper</strong></td>
							   <td><strong>1kg</strong></td>
							   <td><strong>140 X 350 X 95</strong></td>
							   <td>'.$row5['quantity1000'].'</td>
        				       <td>'.$row5['quantity2000'].'</td>
        				       <td>'.$row5['quantity5000'].'</td>
        					   <td>'.$row5['quantity10000'].'</td>
							</tr>
						</tbody>
					</table><br>';
					    $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br><br>';
					    $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/30.2.jpg"" style="width:100%">
				</div>';
						
		$html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/31.1.jpg"" style="width:100%; height:550px">
					<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
						<tbody>
						   <tr>
							  <th rowspan="3" colspan="2" style="font-size:14px" ><strong>Flat Bottom Pouch <br />With Normal Zipper</strong></th>
							  <th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
						   </tr>
						   
						<tr style="height:20px">
        				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
			           </tr>
						   
						   <tr style="text-align:center">
							  <td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
							  <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
						   </tr>';
						   
						 $row1 = $this->getProductTemplateDetails(2,7,'With Zipper','250 gm',210,140,35,'No Accessorie');
				         $html.='<tr style="text-align:center">
							  <td rowspan="4" ><strong>Flat Bottom Pouch <br />With <br />Normal Zipper</strong></td>
							  <td><strong>250gms</strong></td>
							  <td><strong>140 X 210 X 70</strong></td>
							   <td>'.$row1['quantity1000'].'</td>
        				       <td>'.$row1['quantity2000'].'</td>
        				       <td>'.$row1['quantity5000'].'</td>
        					   <td>'.$row1['quantity10000'].'</td>
						   </tr>';
						   
						  $row2 = $this->getProductTemplateDetails(2,7,'With Zipper','340 gm',230,140,35,'No Accessorie');
				         $html.='<tr style="text-align:center">
							  <td><strong>340gms</strong></td>
							  <td><strong>140 X 230 X 70</strong></td>
						       <td>'.$row2['quantity1000'].'</td>
        				       <td>'.$row2['quantity2000'].'</td>
        				       <td>'.$row2['quantity5000'].'</td>
        					   <td>'.$row2['quantity10000'].'</td>
						   </tr>';
						   
						   $row3 = $this->getProductTemplateDetails(2,7,'With Zipper','500 gm',255,150,40,'No Accessorie');
				           $html.='<tr style="text-align:center">
							  <td><strong>500gms</strong></td>
							  <td><strong>150 X 255 X 80</strong></td>
							  <td>'.$row3['quantity1000'].'</td>
        				       <td>'.$row3['quantity2000'].'</td>
        				       <td>'.$row3['quantity5000'].'</td>
        					   <td>'.$row3['quantity10000'].'</td>
						   </tr>';
						   
						  $row4 = $this->getProductTemplateDetails(2,7,'With Zipper','1 kg',295,195,45,'No Accessorie');
				         $html.=' <tr style="text-align:center">
							  <td><strong>1kg</strong></td>
							  <td><strong>195 X 295 X 90</strong></td>
							   <td>'.$row4['quantity1000'].'</td>
        				       <td>'.$row4['quantity2000'].'</td>
        				       <td>'.$row4['quantity5000'].'</td>
        					   <td>'.$row4['quantity10000'].'</td>
						   </tr>
						</tbody>
					</table><br>';
					    $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br>';
					    $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/31.2.jpg"" style="width:100%">
				</div>';
										
		$html.='<div class="panel-body">
						<img src="../upload/admin/template_price_image/pricelist_pdf/32.1.jpg"" style="width:100%; height:600px">
						<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
							<tbody>
								<tr>
									<th rowspan="3" colspan="2" style="font-size:14px" ><strong>Flat Bottom Pouch <br />With Normal Zipper(Brown Paper)</strong></th>
									<th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
									<th><strong>Price Per</strong></th>
									  <th><strong>Price Per</strong></th>
									  <th><strong>Price Per</strong></th>
									  <th><strong>Price Per</strong></th>
								</tr>
				
								<tr style="height:20px">
                				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
                			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
                			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
                				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
			                    </tr>
				
								<tr style="text-align:center">
									<td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
									<td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
									<td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
									<td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
								</tr>';
				
								 $row1 = $this->getProductTemplateDetails(5,7,'With Zipper','250 gm',210,140,35,'No Accessorie');
				                 $html.='<tr style="text-align:center">
									<td rowspan="4"><strong>Flat Bottom Pouch <br />With <br />Normal Zipper</strong></td>
									<td><strong>250gms</strong></td>
									<td><strong>140 X 210 X 70</strong></td>
									<td>'.$row1['quantity1000'].'</td>
            				        <td>'.$row1['quantity2000'].'</td>
            				        <td>'.$row1['quantity5000'].'</td>
            					    <td>'.$row1['quantity10000'].'</td>
								</tr>';
				
								 $row2 = $this->getProductTemplateDetails(5,7,'With Zipper','340 gm',230,140,35,'No Accessorie');
				                 $html.='<tr style="text-align:center">
									<td><strong>340gms</strong></td>
									<td><strong>140 X 230 X 70</strong></td>
								    <td>'.$row2['quantity1000'].'</td>
            				        <td>'.$row2['quantity2000'].'</td>
            				        <td>'.$row2['quantity5000'].'</td>
            					    <td>'.$row2['quantity10000'].'</td>
								</tr>';
					
								 $row3 = $this->getProductTemplateDetails(5,7,'With Zipper','500 gm',255,150,40,'No Accessorie');
				                 $html.='<tr style="text-align:center">
									<td><strong>500gms</strong></td>
									<td><strong>150 X 255 X 80</strong></td>
									<td>'.$row3['quantity1000'].'</td>
            				        <td>'.$row3['quantity2000'].'</td>
            				        <td>'.$row3['quantity5000'].'</td>
            					    <td>'.$row3['quantity10000'].'</td>
								</tr>';
				
								 $row4 = $this->getProductTemplateDetails(5,7,'With Zipper','1 kg',295,195,45,'No Accessorie');
				                 $html.='<tr style="text-align:center">
        									<td><strong>1kg</strong></td>
        									<td><strong>195 X 295 X 90</strong></td>
        									<td>'.$row4['quantity1000'].'</td>
                    				        <td>'.$row4['quantity2000'].'</td>
                    				        <td>'.$row4['quantity5000'].'</td>
                    					    <td>'.$row4['quantity10000'].'</td>
								        </tr>
							</tbody>
						</table>
						<br>';
					        $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br><br>';
						    $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/32.2.jpg"" style="width:100%">
				</div>';
										
		$html.='<div class="panel-body">
				<img src="../upload/admin/template_price_image/pricelist_pdf/33.1.jpg"" style="width:100%; height:550px">
				<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
					<tbody>
						<tr>
							<th rowspan="3" colspan="2" style="font-size:14px" ><strong>Flat Bottom Pouch <br />With Tear Off Zipper</strong></th>
							<th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
							  <th><strong>Price Per</strong></th>
    						  <th><strong>Price Per</strong></th>
    						  <th><strong>Price Per</strong></th>
    						  <th><strong>Price Per</strong></th>
						</tr>
						
						<tr style="height:20px">
        				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
        				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
			            </tr>
						
						<tr style="text-align:center">
							<td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
							<td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
							<td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
							<td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
						</tr>';
						
						 $row1 = $this->getProductTemplateDetails(2,7,'with Tear Off Zipper','114 gm',185,95,30,'No Accessorie');
				         $html.='<tr style="text-align:center">
							<td rowspan="5"><strong>Flat Bottom Pouch <br />With Tear <br />Off Zipper</strong></td>
							<td><strong>114gms</strong></td>
							<td><strong>95 X 185 X 60</strong></td>
							<td>'.$row1['quantity1000'].'</td>
    				        <td>'.$row1['quantity2000'].'</td>
    				        <td>'.$row1['quantity5000'].'</td>
    					    <td>'.$row1['quantity10000'].'</td>
						</tr>';
					
						 $row2 = $this->getProductTemplateDetails(2,7,'with Tear Off Zipper','250 gm',245,95,35,'No Accessorie');
				         $html.='<tr style="text-align:center">
							<td><strong>250gms</strong></td>
							<td><strong>95 X 245 X 70</strong></td>
							<td>'.$row2['quantity1000'].'</td>
    				        <td>'.$row2['quantity2000'].'</td>
    				        <td>'.$row2['quantity5000'].'</td>
    					    <td>'.$row2['quantity10000'].'</td>
						</tr>';
						
						 $row3 = $this->getProductTemplateDetails(2,7,'with Tear Off Zipper','340 gm',280,110,40,'No Accessorie');
				         $html.='<tr style="text-align:center">
							<td><strong>340gms</strong></td>
							<td><strong>100 X 280 X 70</strong></td>
							<td>'.$row3['quantity1000'].'</td>
    				        <td>'.$row3['quantity2000'].'</td>
    				        <td>'.$row3['quantity5000'].'</td>
    					    <td>'.$row3['quantity10000'].'</td>
						</tr>';
						
						 $row4 = $this->getProductTemplateDetails(2,7,'with Tear Off Zipper','500 gm',300,110,40,'No Accessorie');
				         $html.='<tr style="text-align:center">
							<td><strong>500gms</strong></td>
							<td><strong>110 X 300 X 80</strong></td>
							<td>'.$row4['quantity1000'].'</td>
    				        <td>'.$row4['quantity2000'].'</td>
    				        <td>'.$row4['quantity5000'].'</td>
    					    <td>'.$row4['quantity10000'].'</td>
						</tr>';
					
						 $row5 = $this->getProductTemplateDetails(2,7,'with Tear Off Zipper','1 kg',360,140,48,'No Accessorie');
				         $html.='<tr style="text-align:center">
							<td><strong>1kg</strong></td>
							<td><strong>140 X 360 X 95</strong></td>
							<td>'.$row5['quantity1000'].'</td>
    				        <td>'.$row5['quantity2000'].'</td>
    				        <td>'.$row5['quantity5000'].'</td>
    					    <td>'.$row5['quantity10000'].'</td>
				</tr>
					</tbody>
				</table><br>';
					 $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br>';
				    $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/33.2.jpg"" style="width:100%">
				</div>';
											
		$html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/34.1.jpg"" style="width:100%; height:600px">
					<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
						<tbody>
							<tr>
							<th rowspan="3" colspan="2" style="font-size:14px" ><strong>Flat Bottom Pouch <br />With Tear Off Zipper(Brown Paper)</strong></th>
							<th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
							<th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							</tr>
	
							<tr style="height:20px">
            				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
			                </tr>
							
							<tr style="text-align:center">
								<td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
								<td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
								<td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
								<td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
							</tr>';
	
							$row1 = $this->getProductTemplateDetails(5,7,'with Tear Off Zipper','114 gm',185,95,30,'No Accessorie');
				            $html.='<tr style="text-align:center">
								<td rowspan="5"><strong>Flat Bottom Pouch <br />With Tear <br />Off Zipper</strong></td>
								<td><strong>114gms</strong></td>
								<td><strong>95 X 185 X 60</strong></td>
								<td>'.$row1['quantity1000'].'</td>
        				        <td>'.$row1['quantity2000'].'</td>
        				        <td>'.$row1['quantity5000'].'</td>
        					    <td>'.$row1['quantity10000'].'</td>
							</tr>';
					
							 $row2 = $this->getProductTemplateDetails(5,7,'with Tear Off Zipper','250 gm',245,95,35,'No Accessorie');
				            $html.='<tr style="text-align:center">
								<td><strong>250gms</strong></td>
								<td><strong>95 X 245 X 70</strong></td>
								<td>'.$row2['quantity1000'].'</td>
        				        <td>'.$row2['quantity2000'].'</td>
        				        <td>'.$row2['quantity5000'].'</td>
        					    <td>'.$row2['quantity10000'].'</td>
							</tr>';
					
							 $row3 = $this->getProductTemplateDetails(5,7,'with Tear Off Zipper','340 gm',280,110,40,'No Accessorie');
				             $html.='<tr style="text-align:center">
								<td><strong>340gms</strong></td>
								<td><strong>100 X 280 X 70</strong></td>
								<td>'.$row3['quantity1000'].'</td>
        				        <td>'.$row3['quantity2000'].'</td>
        				        <td>'.$row3['quantity5000'].'</td>
        					    <td>'.$row3['quantity10000'].'</td>
							</tr>';
					
						 $row4 = $this->getProductTemplateDetails(5,7,'with Tear Off Zipper','500 gm',300,110,40,'No Accessorie');
				         $html.='<tr style="text-align:center">
								<td><strong>500gms</strong></td>
								<td><strong>110 X 300 X 80</strong></td>
								<td>'.$row4['quantity1000'].'</td>
        				        <td>'.$row4['quantity2000'].'</td>
        				        <td>'.$row4['quantity5000'].'</td>
        					    <td>'.$row4['quantity10000'].'</td>
							</tr>';
					
						$row5 = $this->getProductTemplateDetails(5,7,'with Tear Off Zipper','1 kg',360,140,48,'No Accessorie');
				         $html.='<tr style="text-align:center">
								<td><strong>1kg</strong></td>
								<td><strong>140 X 360 X 95</strong></td>
								<td>'.$row5['quantity1000'].'</td>
        				        <td>'.$row5['quantity2000'].'</td>
        				        <td>'.$row5['quantity5000'].'</td>
        					    <td>'.$row5['quantity10000'].'</td>
							</tr>
						</tbody>
					</table><br>';
					        $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br><br>';
					        $html.='<img src="../upload/admin/template_price_image/pricelist_pdf/34.2.jpg" style="width:100%">
			</div>';
			
		$html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/page-35.jpg" style="width:100%">
				</div>';
				
		$html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/page-36.jpg" style="width:100%">
				</div>';
				
		$html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/37.1.jpg" style="width:100%; height:450px">
					<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
   						<tbody>
						  <tr>
							 <th rowspan="3"  style="font-size:16px" ><strong>Aluminum Foil <br />Stand Up Pouch <br /> With 10mm Spout</strong></th>
							 <th rowspan="3"style="font-size:16px" ><strong>Size</strong></th>
							 <th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
							<th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
						  </tr>
						  
						  	<tr style="height:20px">
            				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
			                </tr>
						  
						  <tr style="text-align:center">
							 <td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
							 <td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
							 <td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
							 <td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
						  </tr>';
						  
						 $row_new = $this->getProductTemplateDetails(2,30,'No zip','50 ml',110,80,25,'No Accessorie','Spout 10mm');
				         $html.='<tr style="text-align:center">
							 <td style="font-size:14px" rowspan="6"><strong>Matt Silver /<br />Shiny Black</strong></td>
							 <td><strong>50ml</strong></td>
							 <td><strong>80 X 110 X 50</strong></td>
							 <td>'.$row_new['quantity1000'].'</td>
    				         <td>'.$row_new['quantity2000'].'</td>
    				         <td>'.$row_new['quantity5000'].'</td>
    					     <td>'.$row_new['quantity10000'].'</td>
						  </tr>';
						  
						 $row_one = $this->getProductTemplateDetails(2,30,'No zip','100 ml',130,90,25,'No Accessorie','Spout 10mm');
				         $html.=' <tr style="text-align:center">
							 <td><strong>100ml</strong></td>
							 <td><strong>90 X 130 X 50</strong></td>
							 <td>'.$row_one['quantity1000'].'</td>
    				         <td>'.$row_one['quantity2000'].'</td>
    				         <td>'.$row_one['quantity5000'].'</td>
    					     <td>'.$row_one['quantity10000'].'</td>
						  </tr>';
						  
						 $row_two = $this->getProductTemplateDetails(2,31,'No zip','250 ml',170,120,40,'No Accessorie','Spout 10mm');
				         $html.='<tr style="text-align:center">
							 <td><strong>250ml</strong></td>
							 <td><strong>120 X 170 X 80</strong></td>
							 <td>'.$row_two['quantity1000'].'</td>
    				         <td>'.$row_two['quantity2000'].'</td>
    				         <td>'.$row_two['quantity5000'].'</td>
    					     <td>'.$row_two['quantity10000'].'</td>
						  </tr>';
						  
						 $row_three = $this->getProductTemplateDetails(2,31,'No zip','500 ml',210,140,40,'No Accessorie','Spout 10mm');
				         $html.='<tr style="text-align:center">
							 <td><strong>500ml</strong></td>
							 <td><strong>140 X 210 X 80</strong></td>
							 <td>'.$row_three['quantity1000'].'</td>
    				         <td>'.$row_three['quantity2000'].'</td>
    				         <td>'.$row_three['quantity5000'].'</td>
    					     <td>'.$row_three['quantity10000'].'</td>
						  </tr>';
						  
						 $row_four = $this->getProductTemplateDetails(2,31,'No zip','1 ltr',265,170,45,'No Accessorie','Spout 10mm');
				         $html.='<tr style="text-align:center">
							 <td><strong>1Liter</strong></td>
							 <td><strong>170 X 265 X 90</strong></td>
							 <td>'.$row_four['quantity1000'].'</td>
    				         <td>'.$row_four['quantity2000'].'</td>
    				         <td>'.$row_four['quantity5000'].'</td>
    					     <td>'.$row_four['quantity10000'].'</td>
						  </tr>
						  
						  <tr style="text-align:center">
							 <td><strong>2Liter</strong></td>
							 <td><strong>220 X 330 X 110</strong></td>
							 <td></td>
							 <td></td>
							 <td></td>
							 <td></td>
						  </tr>
					   </tbody>
					</table>
					<br><br><br>
					<img src="../upload/admin/template_price_image/pricelist_pdf/37.2.jpg" style="width:100%">
				</div>';
				
							
		$html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/38.1.jpg" style="width:100%;">
					
											
						<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
						<tbody>
							<tr>
								<th rowspan="3" style="font-size:13px" ><strong>Clear Stand Up Pouch <br>With 10mm Spout</strong></th>
								<th rowspan="3" style="font-size:13px"><strong>Size</strong></th>
								<th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
								<th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							</tr>
						
							<tr style="height:20px">
            				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            			        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            				    <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
			                </tr>
						
							<tr style="text-align:center">
								<td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
								<td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
								<td style=" color: #d72a35;"><strong>Qty "+1000"</strong></td>
								<td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
							</tr>';
							
					
						 $row_f = $this->getProductTemplateDetails(3,13,'No zip','100 ml',140,100,30,'No Accessorie','Spout 10mm');
				         $html.='<tr style="text-align:center">
								<td rowspan="6" style="font-size:13px;" ><strong>Clear Stand Up Pouch <br>With 10mm <br>Spout</strong></td>
								<td><strong>100ml</strong></td>
								<td><strong>100 X 140 X 60</strong></td>
								 <td>'.$row_f['quantity1000'].'</td>
    				             <td>'.$row_f['quantity2000'].'</td>
    				             <td>'.$row_f['quantity5000'].'</td>
    					         <td>'.$row_f['quantity10000'].'</td>
							</tr>';
						
						 $row_s = $this->getProductTemplateDetails(3,13,'No zip','200 ml',170,110,35,'No Accessorie','Spout 10mm');
				         $html.='<tr style="text-align:center;">
							    <td><strong>200ml</strong></td>
								<td><strong>110 X 170 X 80</strong></td>
								 <td>'.$row_s['quantity1000'].'</td>
    				             <td>'.$row_s['quantity2000'].'</td>
    				             <td>'.$row_s['quantity5000'].'</td>
    					         <td>'.$row_s['quantity10000'].'</td>
							</tr>';
						
						 $row_t = $this->getProductTemplateDetails(3,13,'No zip','350 ml',200,120,40,'No Accessorie','Spout 10mm');
				         $html.='<tr style="text-align:center;">
							    <td><strong>350ml</strong></td>
								<td><strong>120 X 200 X 80</strong></td>
								 <td>'.$row_t['quantity1000'].'</td>
    				             <td>'.$row_t['quantity2000'].'</td>
    				             <td>'.$row_t['quantity5000'].'</td>
    					         <td>'.$row_t['quantity10000'].'</td>
							</tr>';
						
					     $row_f = $this->getProductTemplateDetails(3,13,'No zip','500 ml',210,140,40,'No Accessorie','Spout 10mm');
				         $html.='<tr style="text-align:center;">
							    <td><strong>500ml</strong></td>
								<td><strong>140 X 210 X 80</strong></td>
								 <td>'.$row_f['quantity1000'].'</td>
    				             <td>'.$row_f['quantity2000'].'</td>
    				             <td>'.$row_f['quantity5000'].'</td>
    					         <td>'.$row_f['quantity10000'].'</td>
							</tr>';
							
						 $row_ff = $this->getProductTemplateDetails(3,13,'No zip','750 ml',230,160,45,'No Accessorie','Spout 10mm');
				         $html.='<tr style="text-align:center;">
							    <td><strong>750ml</strong></td>
								<td><strong>160 X 230 X 90</strong></td>
							     <td>'.$row_ff['quantity1000'].'</td>
    				             <td>'.$row_ff['quantity2000'].'</td>
    				             <td>'.$row_ff['quantity5000'].'</td>
    					         <td>'.$row_ff['quantity10000'].'</td>
							</tr>
						
							<tr style="text-align:center;">
							
								<td><strong>1 Ltr</strong></td>
								<td><strong>190 X 260 X 110</strong></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						
						</tbody>
					</table>
					
					<br>
					
					<img src="../upload/admin/template_price_image/pricelist_pdf/38.2.jpg" style="width:100%">
				</div>';
									
		$html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/39.1.jpg" style="width:100%;">
					
												
							<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
								<tbody>
									<tr>
										<th rowspan="3" colspan="2"><strong>SUP Pouch With Zipper <br /> Oval Window</strong></th>
										<th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
										<th><strong>Price Per</strong></th>
									  <th><strong>Price Per</strong></th>
									  <th><strong>Price Per</strong></th>
									  <th><strong>Price Per</strong></th>
									</tr>
							
								<tr style="height:20px">
            				        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            			            <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            			            <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            				        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
			                   </tr>
							
									<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
										<td style="color: #d72a35;">Qty "+5000"</td>
										<td style="color: #d72a35;">Qty "+2000"</td>
										<td style=" color: #d72a35;">Qty "+1000"</td>
										<td style="color: #d72a35;">Qty "+100"</td>
									</tr>';
							
						    $row_m = $this->getProductTemplateDetails(2,3,'With Zipper','70 gm',170,110,35,'No Accessorie');
				            $html.='<tr style="text-align:center;">
										<td width="20%"><strong>SUP Pouch With Zipper</strong></td>
										<td><strong>70gms</strong></td>
										<td><strong>110 x 170 x 70</strong></td>
										<td>'.$row_m['quantity1000'].'</td>
    				                    <td>'.$row_m['quantity2000'].'</td>
    				                    <td>'.$row_m['quantity5000'].'</td>
    					                <td>'.$row_m['quantity10000'].'</td>
    					            </tr>';
							
						    $row_n = $this->getProductTemplateDetails(2,3,'With Zipper','150 gm',210,130,40,'No Accessorie');
				            $html.='<tr style="text-align:center;">
										<td width="20%"><strong>SUP Pouch With Zipper</strong></td>
										<td><strong>150gms</strong></td>
										<td><strong>130 x 210 x 80</strong></td>
										<td>'.$row_n['quantity1000'].'</td>
    				                    <td>'.$row_n['quantity2000'].'</td>
    				                    <td>'.$row_n['quantity5000'].'</td>
    					                <td>'.$row_n['quantity10000'].'</td>
    					           </tr>';
							
							 $row_s = $this->getProductTemplateDetails(2,3,'With Zipper','250 gm',230,160,45,'No Accessorie');
				            $html.='<tr style="text-align:center;">
										<td width="20%"><strong>SUP Pouch With Zipper</strong></td>
										<td><strong>250gms</strong></td>
										<td><strong>160 x 230 x 90</strong></td>
										<td>'.$row_s['quantity1000'].'</td>
    				                    <td>'.$row_s['quantity2000'].'</td>
    				                    <td>'.$row_s['quantity5000'].'</td>
    					                <td>'.$row_s['quantity10000'].'</td>
    					           </tr>';
							
						    $row_ss = $this->getProductTemplateDetails(2,3,'With Zipper','500 gm',260,190,55,'No Accessorie');
				            $html.='<tr style="text-align:center;">
										<td width="20%"><strong>SUP Pouch With Zipper</strong></td>
										<td><strong>500gms</strong></td>
										<td><strong>190 x 260 x 110</strong></td>
										<td>'.$row_ss['quantity1000'].'</td>
    				                    <td>'.$row_ss['quantity2000'].'</td>
    				                    <td>'.$row_ss['quantity5000'].'</td>
    					                <td>'.$row_ss['quantity10000'].'</td>
									</tr>
							
								</tbody>
							</table><br>';
					        $html.='<div align="center" width="50%"><b style="color:grey; font-family:Verdana, Geneva, sans-serif">'.$note.'</b></div><br>';
							$html.='<img src="../upload/admin/template_price_image/pricelist_pdf/39.2.jpg" style="width:100%;height:110px">
							</div>';
							
					
				$html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/page-40.jpg" style="width:100%;">
					</div>';
				$html.='<div class="panel-body">
					<img src="../upload/admin/template_price_image/pricelist_pdf/41.1.jpg" style="width:100%;">
					
					
						<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
							<tbody>
								<tr>
									<th rowspan="4"><strong>Juice / Cocktails <br>(SUP With Zipper <br> With Handle)</strong></th>
									<th rowspan="3" style="font-size:13px"><strong>Size</strong></th>
									<th rowspan="3"><strong>Dimension<br>W X L X G (mm)</strong></th>
									<th><strong>Price Per</strong></th>
									  <th><strong>Price Per</strong></th>
									  <th><strong>Price Per</strong></th>
									  <th><strong>Price Per</strong></th>
								</tr>
						
								<tr style="height:20px">
            				        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            			            <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            			            <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            				        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
			                   </tr>
						
								<tr style="text-align:center">
									<td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
									<td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
									<td style=" color: #d72a35;"><strong>Qty "+1000"</strong></td>
									<td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
								</tr>';
						
							 $row_juice = $this->getProductTemplateDetails(3,26,'With Zipper','600 ml',255,160,45,'Die cut Handle');
				            $html.='<tr style="text-align:center">
								    <td><strong>600 ml</strong></td>
									<td><strong>160  x  255  x  45</strong></td>
							    	<td>'.$row_juice['quantity1000'].'</td>
    				                <td>'.$row_juice['quantity2000'].'</td>
    				                <td>'.$row_juice['quantity5000'].'</td>
    					            <td>'.$row_juice['quantity10000'].'</td>
								</tr>
							</tbody>
						</table>
					
					
						<img src="../upload/admin/template_price_image/pricelist_pdf/41.2.jpg" style="width:100%;">
						</div>';
						
					$html.='<div class="panel-body">
							<img src="../upload/admin/template_price_image/pricelist_pdf/42.2.jpg" style="width:100%;">
					
						<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
							<tbody>
								<tr>
									<th rowspan="6" style="font-size:18px"><strong>Tintie Without<br /> Fitting</strong></th>
									<th rowspan="3" style="font-size:15px"><strong>Size</strong></th>
									<th rowspan="3" style="font-size:15px"><strong>Color</strong></th>
									<th><strong>Price Per</strong></th>
								    <th><strong>Price Per</strong></th>
								    <th><strong>Price Per</strong></th>
								    <th><strong>Price Per</strong></th>
								</tr>
						
								<tr style="height:20px">
            				        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            			            <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            			            <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
            				        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
			                   </tr>
						
								<tr style="text-align:center">
									<td style="color: #d72a35;"><strong>Qty "+5000"</strong></td>
									<td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
									<td style=" color: #d72a35;"><strong>Qty "+1000"</strong></td>
									<td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
								</tr>';
						
							$row_tt_one = $this->getProductTemplateDetails(2,35,'TinTie 120mm / 70mm adhesive - Black','120 mm',70,120,0,'No Spout');
				            $html.='<tr style="text-align:center">
								    <td><strong>120mm Width / 70mm Adhesive area</strong></td>
									<td rowspan="3"><strong>Black , White & <br />	Craft Color</strong></td>
									<td>'.$row_tt_one['quantity1000'].'</td>
    				                <td>'.$row_tt_one['quantity2000'].'</td>
    				                <td>'.$row_tt_one['quantity5000'].'</td>
    					            <td>'.$row_tt_one['quantity10000'].'</td>
    					            </tr>';
    					            
							$row_tt_two = $this->getProductTemplateDetails(2,35,'TinTie 140mm / 88mm adhesive - Black','140 mm',88,140,0,'No Spout');
				            $html.='<tr style="text-align:center">
								    <td><strong>140mm Width / 80mm Adhesive area</strong></td>
								    <td>'.$row_tt_two['quantity1000'].'</td>
    				                <td>'.$row_tt_two['quantity2000'].'</td>
    				                <td>'.$row_tt_two['quantity5000'].'</td>
    					            <td>'.$row_tt_two['quantity10000'].'</td>
    					            </tr>';
    					            
							$row_tt_three = $this->getProductTemplateDetails(2,35,'TinTie 177mm / 125mm adhesive - Black','177 mm',125,177,0,'No Spout');
				            $html.='<tr style="text-align:center">
								    <td><strong>177mm Width / 125mm Adhesive area</strong></td>
								    <td>'.$row_tt_three['quantity1000'].'</td>
    				                <td>'.$row_tt_three['quantity2000'].'</td>
    				                <td>'.$row_tt_three['quantity5000'].'</td>
    					            <td>'.$row_tt_three['quantity10000'].'</td>
						
								</tr>
							</tbody>
						</table>
												
						<img src="../upload/admin/template_price_image/pricelist_pdf/42.1.jpg" style="width:100%; height:700px">
						</div>';
					
					$html.='<div class="panel-body">
							<img src="../upload/admin/template_price_image/pricelist_pdf/43.1.jpg" style="width:100%;">		
					
					<table border="1" width="100%" cellpadding="0" cellspacing="0"  style="font-size:12px" class="">
						<tbody>
							<tr>
								<th rowspan="2" colspan="2" style="font-size:26px"><strong>Measuring Spoon</strong></th>
					
								<th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							  <th><strong>Price Per</strong></th>
							</tr>
					
						<tr style="height:20px">
    				        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
    			            <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
    			            <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
    				        <td>Pouch In'.' '.' '.$currency[0]['currency_code'].'</td>
			            </tr>
					
							<tr style="text-align:center">
								<td rowspan="19"><strong style="font-size:18px">Color Available : <br />
										Clear Transparent, <br />
										Blue Color <br /></strong>
									<div style="font-size:18px">
										Use for
										<br /> Protein Powder,
										<br /> Baking Powder,
										<br /> Chocolate Powder,
										<br /> Ice Cream Powder,
										<br /> Liquide Form Of Detergent,
										<br /> Pharmaceutical Products,
										<br /> Powder Form of Detergent,
										<br /> Pet Food Products etc..
									</div>
								</td>
								<td></td>
								<td style=" color: #d72a35;"><strong>Qty "+5000"</strong></td>
								<td style="color: #d72a35;"><strong>Qty "+2000"</strong></td>
								<td style="color: #d72a35;"><strong>Qty "+1000"</strong></td>
								<td style="color: #d72a35;"><strong>Qty "+100"</strong></td>
							</tr>';
					
							$row_sc_one = $this->getProductTemplateDetails(2,11,'No zip','1 ml',43,19,0,'No Accessorie');
				            $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
								    <td><strong>1 ml</strong></td>
                                    <td>'.$row_sc_one['quantity1000'].'</td>
    				                <td>'.$row_sc_one['quantity2000'].'</td>
    				                <td>'.$row_sc_one['quantity5000'].'</td>
    					            <td>'.$row_sc_one['quantity10000'].'</td>
						        	</tr>';
						        	
							$row_sc_two = $this->getProductTemplateDetails(2,11,'No zip','2.5 ml',51,25,0,'No Accessorie');
				            $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
								    <td><strong>2.5 ml</strong></td>
							    	<td>'.$row_sc_two['quantity1000'].'</td>
    				                <td>'.$row_sc_two['quantity2000'].'</td>
    				                <td>'.$row_sc_two['quantity5000'].'</td>
    					            <td>'.$row_sc_two['quantity10000'].'</td>
							       </tr>';
					
							$row_sc_three = $this->getProductTemplateDetails(2,11,'No zip','5 ml',61,31,0,'No Accessorie');
				            $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
								    <td><strong>5 ml</strong></td>
							        <td>'.$row_sc_three['quantity1000'].'</td>
    				                <td>'.$row_sc_three['quantity2000'].'</td>
    				                <td>'.$row_sc_three['quantity5000'].'</td>
    					            <td>'.$row_sc_three['quantity10000'].'</td>
							        </tr>';
					
							$row_sc_four = $this->getProductTemplateDetails(2,11,'No zip','7.5 ml',65,30,0,'No Accessorie');
				            $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
								    <td><strong>7.5 ml</strong></td>
							        <td>'.$row_sc_four['quantity1000'].'</td>
    				                <td>'.$row_sc_four['quantity2000'].'</td>
    				                <td>'.$row_sc_four['quantity5000'].'</td>
    					            <td>'.$row_sc_four['quantity10000'].'</td>
						            </tr>';
					
							$row_sc_five = $this->getProductTemplateDetails(2,11,'No zip','10 ml',73,38,0,'No Accessorie');
				            $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
								    <td><strong>10 ml</strong></td>
								    <td>'.$row_sc_five['quantity1000'].'</td>
    				                <td>'.$row_sc_five['quantity2000'].'</td>
    				                <td>'.$row_sc_five['quantity5000'].'</td>
    					            <td>'.$row_sc_five['quantity10000'].'</td>
							        </tr>';
					
							$row_sc_six = $this->getProductTemplateDetails(2,11,'No zip','15 ml',84,43,0,'No Accessorie');
				            $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
								    <td><strong>15 ml</strong></td>
							    	<td>'.$row_sc_six['quantity1000'].'</td>
    				                <td>'.$row_sc_six['quantity2000'].'</td>
    				                <td>'.$row_sc_six['quantity5000'].'</td>
    					            <td>'.$row_sc_six['quantity10000'].'</td>
							        </tr>';
					
							$row_sc_seven = $this->getProductTemplateDetails(2,11,'No zip','20 ml',88,48,0,'No Accessorie');
				            $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
								    <td><strong>20 ml</strong></td>
								    <td>'.$row_sc_seven['quantity1000'].'</td>
    				                <td>'.$row_sc_seven['quantity2000'].'</td>
    				                <td>'.$row_sc_seven['quantity5000'].'</td>
    					            <td>'.$row_sc_seven['quantity10000'].'</td>
							        </tr>';
					
							$row_sc_eight = $this->getProductTemplateDetails(2,11,'No zip','25 ml',97,51,0,'No Accessorie');
				            $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
								    <td><strong>25 ml</strong></td>
							        <td>'.$row_sc_eight['quantity1000'].'</td>
    				                <td>'.$row_sc_eight['quantity2000'].'</td>
    				                <td>'.$row_sc_eight['quantity5000'].'</td>
    					            <td>'.$row_sc_eight['quantity10000'].'</td>
							        </tr>';
					
							$row_sc_nine = $this->getProductTemplateDetails(2,11,'No zip','30 ml',111,54,0,'No Accessorie');
				            $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
								    <td><strong>30 ml</strong></td>
							        <td>'.$row_sc_nine['quantity1000'].'</td>
    				                <td>'.$row_sc_nine['quantity2000'].'</td>
    				                <td>'.$row_sc_nine['quantity5000'].'</td>
    					            <td>'.$row_sc_nine['quantity10000'].'</td>
						        	</tr>';
					
							//$row_sc_ten = $this->getProductTemplateDetails(2,11,'No zip','1 ml',43,19,0,'No Accessorie');
				                    $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
							    	<td><strong>35 ml</strong></td>
							    	<td></td>
    				                <td></td>
    				                <td></td>
    					            <td></td>
							</tr>';
					
							$row_sc_ele = $this->getProductTemplateDetails(2,11,'No zip','50 ml',114,64,0,'No Accessorie');
				            $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
								    <td><strong>50 ml</strong></td>
						        	<td>'.$row_sc_ele['quantity1000'].'</td>
    				                <td>'.$row_sc_ele['quantity2000'].'</td>
    				                <td>'.$row_sc_ele['quantity5000'].'</td>
    					            <td>'.$row_sc_ele['quantity10000'].'</td>
						        	</tr>';
					
							$row_sc_twe = $this->getProductTemplateDetails(2,11,'No zip','60 ml',100,57,0,'No Accessorie');
				            $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
								    <td><strong>60 ml</strong></td>
							        <td>'.$row_sc_twe['quantity1000'].'</td>
    				                <td>'.$row_sc_twe['quantity2000'].'</td>
    				                <td>'.$row_sc_twe['quantity5000'].'</td>
    					            <td>'.$row_sc_twe['quantity10000'].'</td>
						        	</tr>';
					
							//$row_sc_thirtin = $this->getProductTemplateDetails(2,11,'No zip','1 ml',43,19,0,'No Accessorie');
				            $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
								    <td><strong>70 ml</strong></td>
							        <td></td>
    				                <td></td>
    				                <td></td>
    					            <td></td>
							</tr>';
					
							$row_sc_fourtin = $this->getProductTemplateDetails(2,11,'No zip','75 ml',106,61,0,'No Accessorie');
				            $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
								    <td><strong>75 ml</strong></td>
							        <td>'.$row_sc_fourtin['quantity1000'].'</td>
    				                <td>'.$row_sc_fourtin['quantity2000'].'</td>
    				                <td>'.$row_sc_fourtin['quantity5000'].'</td>
    					            <td>'.$row_sc_fourtin['quantity10000'].'</td>
						        	</tr>';
					
							$row_sc_fiftin = $this->getProductTemplateDetails(2,11,'No zip','80 ml',75,54,0,'No Accessorie');
				            $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
								    <td><strong>80 ml</strong></td>
								    <td>'.$row_sc_fiftin['quantity1000'].'</td>
    				                <td>'.$row_sc_fiftin['quantity2000'].'</td>
    				                <td>'.$row_sc_fiftin['quantity5000'].'</td>
    					            <td>'.$row_sc_fiftin['quantity10000'].'</td>
							        </tr>';
					
							$row_sc_sixteen = $this->getProductTemplateDetails(2,11,'No zip','100 ml',113,68,0,'No Accessorie');
				            $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
								    <td><strong>100 ml</strong></td>
							        <td>'.$row_sc_sixteen['quantity1000'].'</td>
    				                <td>'.$row_sc_sixteen['quantity2000'].'</td>
    				                <td>'.$row_sc_sixteen['quantity5000'].'</td>
    					            <td>'.$row_sc_sixteen['quantity10000'].'</td>
							        </tr>';
					
						    $row_sc_seventeen = $this->getProductTemplateDetails(2,11,'No zip','150 ml',121,76,0,'No Accessorie');
				            $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
								    <td><strong>150ml</strong></td>
								    <td>'.$row_sc_seventeen['quantity1000'].'</td>
    				                <td>'.$row_sc_seventeen['quantity2000'].'</td>
    				                <td>'.$row_sc_seventeen['quantity5000'].'</td>
    					            <td>'.$row_sc_seventeen['quantity10000'].'</td>
							        </tr>';
					
							//$row_sc_one = $this->getProductTemplateDetails(2,11,'No zip','1 ml',43,19,0,'No Accessorie');
				            $html.='<tr style="text-align:center;font-family:" Arial Black ", Gadget, sans-serif">
								    <td><strong>250 ml</strong></td>
								    <td></td>
    				                <td></td>
    				                <td></td>
    					            <td></td>
						        	</tr>
						    </tbody>
				    	</table><br>
					<img src="../upload/admin/template_price_image/pricelist_pdf/43.2.jpg" style="width:100%; height:500px">
					</div>';
				
					$html.='<div class="panel-body">
							<img src="../upload/admin/template_price_image/pricelist_pdf/page-44.jpg" style="width:100%;">		
							</div>';
					
					$html.='<div class="panel-body">
							<img src="../upload/admin/template_price_image/pricelist_pdf/page-45.jpg" style="width:100%;">		
							</div>';
					
					$html.='<div class="panel-body">
							<img src="../upload/admin/template_price_image/pricelist_pdf/page-46.jpg" style="width:100%;">		
							</div>';
					
					$html.='<div class="panel-body">
							<img src="../upload/admin/template_price_image/pricelist_pdf/page-47.jpg" style="width:100%;">		
							</div>';
					
					$html.='<div class="panel-body">
							<img src="../upload/admin/template_price_image/pricelist_pdf/page-48.jpg" style="width:100%;">		
							</div>';
					
					$html.='<div class="panel-body">
							<img src="../upload/admin/template_price_image/pricelist_pdf/page-49.jpg" style="width:100%;">		
							</div>';
					
					$html.='<div class="panel-body">
							<img src="../upload/admin/template_price_image/pricelist_pdf/page-50.jpg" style="width:100%;">		
							</div>';
					
					$html.='<div class="panel-body">
							<img src="../upload/admin/template_price_image/pricelist_pdf/page-51.jpg" style="width:100%;">		
							</div>';
					
					$html.='<div class="panel-body">
							<img src="../upload/admin/template_price_image/pricelist_pdf/page-52.jpg" style="width:100%;">		
							</div>';
					
					$html.='</div> ';			
		//	printr($html);									
	//die;											
		return $html;
					
	}
}
?>