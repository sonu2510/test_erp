<?php
include("mode_setting.php");

$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.'',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

$class = 'collapse';
$data='';
if($display_status) {
    if(isset($_POST['btn_pro'])){
		$post = post($_POST);
       // printr($post);die;
		$data = $obj_invoice->viewDailyStockRegisterReport($post);
		
//	printr($data);die;
			
}

?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white">
                 <span> Sales Report Detail</span>
               
                 <span class="text-muted m-l-small pull-right">
                     	<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=index_opening_balance', '',1);?>"><i class="fa fa-plus"></i> Add Opening Balance </a> &nbsp;
                 		 <a class="label bg-success" href="javascript:void(0);" id="excel_link"><i class="fa fa-print"></i> Excel</a>
                 </span>
                </header>
          
          <div class="panel-body"></div>
			 <form class="form-horizontal" method="post" name="frm_add" id="frm_add" enctype="multipart/form-data" action="">
         
			   <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Month</label>
                <div class="col-lg-3">
                    <select name="f_date" class="form-control validate[required]">
                      <option>Select Month</option>
                      <?php for($m=1;$m<=12;$m++)
                            { ?>
                                <option value=" <?php echo $m; ?> " <?php if(date('m')==$m) { echo 'selected=selected';} ?> ><?php echo DateTime::createFromFormat('!m', $m)->format('F'); ?></option>
                      <?php }?>
                    </select>	
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Year</label>
                <div class="col-lg-3">
                    <select name="t_date" class="form-control validate[required]">
                      <option>Select Year</option>
                      <?php for($m=2018;$m<=2025;$m++)
                            { ?>
                                <option value='<?php echo $m;?>'  <?php if(date('Y')==$m) { echo 'selected=selected';} ?> ><?php echo $m;?></option>
                      <?php }?>
                    </select>	
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Product</label>
                <div class="col-lg-3">
                     <select name="product" id="product" class="form-control " >
                            <option value="0">Pouch</option> </option>
                            <?php 
                             $product_details=$obj_invoice-> getActiveProductReport();
                                foreach($product_details as $product)
                                { ?>
                                    <option value="<?php echo $product['product_id']; ?>" id="option" ><?php echo $product['product_name']; ?></option>
                           <?php } ?>                                                      
                         </select>
                </div>
              </div>
              
              
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
               	<button type="submit" name="btn_pro" id="btn_pro" class="btn btn-primary">Proceed</button>	
                </div>
              </div>
              
              	<div class="panel-body">
              <div class="form-group report_div">
              	<div class="col-lg-12 ">
                
                	     <input type="hidden" id="post_data"  name ="post_data" value='<?php echo json_encode($_POST);?>' />
                    	<div class="table-responsive" id="print_data">
                    	    
                    	    <?php //echo $data;
                    	    
                    	     //DateTime::createFromFormat('!m', $post['f_date'])->format('F')
        	   if (!empty($data)) {
				echo '<style>
                    table, th, td {
                        border: 1px solid black;
                  }
                    
                    </style>';		 
			echo'<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
    				<div class="panel-body font_medium "  id="print_div" style="font-size: 20px; page-break-before: always;overflow-y:hidden;overflow-x:scroll;" >';
	       
		     echo '<div class="table-responsive" style=" width: 100%;float: left;  font-size: 12px;">';
				echo '<table class="table table-striped b-t text-small" style=" width: 100%; border:1; font-size: 14px;" >
        					<thead>
        					    <tr>
            						<th colspan="6">Name Of the Factory with Address And Registration no:</th>
            						<th rowspan="2" colspan="20" style="vertical-align: top;"><center>Daily Stock Register<br>For The Month of '.date("F", mktime(0, 0, 0, $post['f_date'], 10)).' - '.$post['t_date'].'-'.($post['t_date']+1).'<br>Finished Good Name '.$product.'</center></th>
            					</tr>
        						<tr>
            						<th colspan="2" style="vertical-align: top;">Name</th>
            						<th colspan="4">M/s_SWISS PAC PVT LTD,<br>VADODARA-JUMBUSER NATIONAL HIGHWAY,<br>NEAR PADRA AT DABHASA,
            						                            <br>DIST VADODARA 391440 GUJRAT</th>
            					</tr>
            					<tr>
            						
            						<th  colspan="2">E.C.CODE NO.</th>
            						<th  colspan="'.$col1.'">PH:+91-2662-244057,244466</th>
            			    	</tr>
            					<tr>
                						<th rowspan="3">sr.no</th>
                						<th rowspan="3">Date</th>
                						<th rowspan="3">Opening Balance</th>';
                						if($post['product']==0 || $post['product']==6 || $post['product']==64){
                    						echo '<th rowspan="3">Quantity Manufactured</th>
                    						<th rowspan="3">Total</th>';
                						}
                						
                					echo '<th colspan="6"><center>Quantity And Value Of Goods Removed on Which Duty is Required to be Paid</th>
                						<th colspan="6"><center>Quantity And Value Of Goods Delivered Without Payment Of Duty</th>
                						<th colspan="4"><center>Duty</center></th>';
                						 if($post['product']==0 || $post['product']==6 || $post['product']==64){
                						    echo '<th>Closing Balance</th>';
                						 }
                						echo '<th rowspan="3" style="background-color: #fbafaf;">Invoice No</th>
                						<th rowspan="3" style="background-color: #fbafaf;">EXPORT INVOICE NO</th>
                						<th rowspan="3">REMARKS</th>
                						<th rowspan="3">Signature of the Assessee Or his Agent</th>
            					</tr>
            					<tr>
            						<th colspan="3" style="background-color: #f7ddc6;">For Home Use</th>
            						<th colspan="3" style="background-color: #b7e4f3;">For Export Under Claim For Rebate Of Duty</th>
            						<th rowspan="2" style="background-color: #f7ddc6;" >For Export Under Bond</th>
            						<th rowspan="2" style="background-color: #f7ddc6;">Basic Value</th>
            						<th rowspan="2" style="background-color: #f7ddc6;">Freight Value</th>
            						<th colspan="3" style="background-color: #b7e4f3;">For Other Purposes</th>
            						<th rowspan="2">Rate</th>
            						<th>Amount of Duty Paid or payable</th>
            						<th>Amount of Duty Paid or payable</th>
            						<th></th>';
            						 if($post['product']==0 || $post['product']==6 || $post['product']==64){
            						    echo '<th rowspan="2">In Finishing Room</th>';
            						 }
            					
            					echo '</tr>
            					<tr>
            					
            					
            						<th style="background-color: #f7ddc6;">Quantity</th>
            						<th style="background-color: #f7ddc6;">Basic Value</th>
            						<th style="background-color: #f7ddc6;">Freight Value</th>
            						
            						<th style="background-color: #b7e4f3;">For Export Under Bond</th>
            						<th style="background-color: #b7e4f3;">Basic Value</th>
            						<th style="background-color: #b7e4f3;">Freight Value</th>
            						<th style="background-color: #b7e4f3;">Purpose</th>
            						<th style="background-color: #b7e4f3;">AMT RS.</th>
            						<th style="background-color: #b7e4f3;">FREIGHT T RS.</th>
            						<th style="background-color: #d8f3cd;">IGST</th>
            						<th style="background-color: #d8f3cd;">CGST</th>
            						<th style="background-color: #d8f3cd;">SGST</th>
            					
            					
            					</tr>
        						<tr>';
                					if($post['product']==0){
                					    $r = '26';
                				}	else{
                					    $r = '23';
                				}   
                					for($i=1; $i<=$r; $i++){
                					   echo '<th>'.$i.'</th>'; 
                					}
        				    
        					echo '</tr>';
        				 
        			echo '</thead>
					<tbody>';
				 
					$srno=1;
					$date='';$freight=$daily_opening=$net_road=$net_sea=$net_air=0;
					$sub_road=$sub_sea=$final_basic_amt=$sub_air=0;$fre_road=$fre_sea=$fre_air=0;$t_igst=$t_cgst=$t_sgst=0;
				      $d['cgst']=$d['sgst']=$igst=0;
				    foreach ($data as $d) {
				       //printr($d);
				    
				        $invoice_inv_data = $obj_invoice->getInvoiceNetData($d['invoice_id']);
				        $invoice_product_details=$obj_invoice->getTotalProductDetail($d['sales_invoice_id'],$post['product']);
				        $invoice_total_amt=$obj_invoice->getInvoiceTotalData($d['invoice_id']);
				        
				        $basic_amt_other_product = $obj_invoice->getSalesInvoiceProduct($d['sales_invoice_id'],2);
				        $basic_amt_all_product_= $obj_invoice->getSalesInvoiceProduct($d['sales_invoice_id'],3);
				        
				         if($d['invoice_status'] == '0'){
        			           if($post['product']=='0'){
        			                $final_basic_amt=$basic_amt_all_product_[0]['basic_amt']-$basic_amt_other_product[0]['basic_amt'];
    				           }else{
    				                $final_basic_amt= $invoice_product_details['basic_amt'];
    				           }
    				      }
				        
				        
				        
				     //  printr($invoice_total_amt);
				      // printr($final_basic_amt.'============'.$d['invoice_no']);
				     //   printr($basic_amt_all_product_[0]['basic_amt'].'============'.$d['invoice_no']);
				      // printr($basic_amt_other_product[0]['basic_amt'].'============'.$d['invoice_no']);
				//      printr($d['invoice_no']);
				        $product_detail = $obj_invoice->getSalesInvoiceProduct($d['sales_invoice_id']);
				        $first='false';
				        $open = 0;
				        if($d['status']=='0' || $d['status']=='2'){
				              if($d['status']=='2')
				              {
				                    $open = $invoice_product_details['identification_marks'];
				              }
				              $invoice_product_details['identification_marks']=0;
				              $invoice_product_details['basic_amt']=0;
				              $final_basic_amt=$d['cylinder_charges']=0;
				              $d['tran_charges']=0;
				              $d['sgst']=$d['cgst']=$d['igst']=0;
				        }
				        
				        //printr($d['sales_invoice_id']);
				        
				        $allproduct = $obj_invoice->getSalesInvoiceProduct($d['sales_invoice_id'],1);
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
			                    if($first=='false'){
			                        $freight = 1;$first = 'true';
			                    }
			                }
			                else if($details['product_id']=='6')
			                {
			                    if($first=='false'){
			                        $freight = 2;$first = 'true';}
			                }
			                else if($details['product_id']=='10')
			                {
			                    if($first=='false'){
			                        $freight = 3;$first = 'true';}
			                }
			                else if($details['product_id']=='23')
			                {
			                    if($first=='false'){
			                        $freight = 4;$first = 'true';}
			                }
			                else if($details['product_id']=='18')
			                {
			                    if($first=='false'){
			                        $freight = 5;$first = 'true';}
			                }
			                else if($details['product_id']=='34')
			                {
			                    if($first=='false'){
			                        $freight = 6;$first = 'true';}
			                }
			                else if($details['product_id']=='47')
			                {
			                    if($first=='false'){
			                        $freight = 7;$first = 'true';}
			                }
			                else if($details['product_id']=='48')
			                {
			                    if($first=='false'){
			                        $freight = 8;$first = 'true';}
			                }
			                else if($details['product_id']=='72')
			                {
			                    if($first=='false'){
			                        $freight = 8;$first = 'true';}
			                }
			                else if($details['product_id']=='35')
			                {
			                    if($first=='false'){
			                        $freight = 10;$first = 'true';}
			                } else if($details['product_id']=='62')
			                {
			                    if($first=='false'){
			                        $freight = 11;$first = 'true';}
			                }else if($details['product_id']=='61')
			                {
			                    if($first=='false'){
			                        $freight = 12;$first = 'true';}
			                }
			                else if($details['product_id']!='11' && $details['product_id']!='6' && $details['product_id']!='10' && $details['product_id']!='23' && $details['product_id']!='18' && $details['product_id']!='34' && $details['product_id']!='47' && $details['product_id']!='48' && $details['product_id']!='63'&& $details['product_id']!='72'&& $details['product_id']!='62'&& $details['product_id']!='61'&& $details['product_id']!='35')
			                {
			                    if($first=='false'){
			                        $freight = 9;$first = 'true';}
			                }
				        }
				       // printr($freight);
				      //  printr($fre_charge);
				        
				        $insurance=$insu='0';
						 if(($d['country_id']=='252' || $invoice_inv_data['order_user_id']==2 ) && ($d['transport'])=='air')
						 {
							$tran_charges_tot=$invoice_product_details['basic_amt'];
					
							$insurance= number_Format(((($tran_charges_tot*110/100+$tran_charges_tot)*0.07)/100),2);

						 }
				        
				        $fre_charge = $igst=$cgst=$sgst=$f_charge=0;
				      
				        if($post['product']=='11')
				        {
				            if($freight==1){
				                $fre_charge = $d['tran_charges'];$extra_tran_charges=$d['extra_tran_charges'];$insu =$insurance;$igst=$d['igst'];$sgst=$d['sgst'];$cgst=$d['cgst']; } 
				        }
				        else if($post['product']=='6')
				        {
				            if($freight==2){
				                $fre_charge = $d['tran_charges'];$extra_tran_charges=$d['extra_tran_charges']; $insu =$insurance;$igst=$d['igst'];$sgst=$d['sgst'];$cgst=$d['cgst'];}
				        }
				        else if($post['product']=='10')
				        {
				            if($freight==3){
				                $fre_charge = $d['tran_charges'];$extra_tran_charges=$d['extra_tran_charges'];$insu =$insurance;$igst=$d['igst'];$sgst=$d['sgst'];$cgst=$d['cgst']; }
				        }
				        else if($post['product']=='23')
				        {
				            if($freight==4){
				                $fre_charge = $d['tran_charges'];$extra_tran_charges=$d['extra_tran_charges'];$insu =$insurance;$igst=$d['igst'];$sgst=$d['sgst'];$cgst=$d['cgst']; }
				        }
				        else if($post['product']=='18')
				        {
				            if($freight==5){
				                $fre_charge = $d['tran_charges'];$extra_tran_charges=$d['extra_tran_charges'];$insu =$insurance;$igst=$d['igst'];$sgst=$d['sgst'];$cgst=$d['cgst'];} 
				        }
				        else if($post['product']=='34')
				        {
				            if($freight==6){
				                $fre_charge = $d['tran_charges'];$extra_tran_charges=$d['extra_tran_charges'];$insu =$insurance;$igst=$d['igst'];$sgst=$d['sgst'];$cgst=$d['cgst'];} 
				        }
				        else if($post['product']=='47')
				        {
				            if($freight==7){
				                $fre_charge = $d['tran_charges'];$extra_tran_charges=$d['extra_tran_charges'];$insu =$insurance;$igst=$d['igst'];$sgst=$d['sgst'];$cgst=$d['cgst']; }
				        }
				        else if($post['product']=='48')
				        {
				            if($freight==8){
				                $fre_charge = $d['tran_charges'];$extra_tran_charges=$d['extra_tran_charges'];$insu =$insurance;$igst=$d['igst'];$sgst=$d['sgst'];$cgst=$d['cgst']; }
				        } else if($post['product']=='35')
				        {
				            if($freight==10){
				                $fre_charge = $d['tran_charges'];$extra_tran_charges=$d['extra_tran_charges'];$insu =$insurance;$igst=$d['igst'];$sgst=$d['sgst'];$cgst=$d['cgst']; }
				        } else if($post['product']=='62')
				        {
				            if($freight==11){
				                $fre_charge = $d['tran_charges'];$extra_tran_charges=$d['extra_tran_charges'];$insu =$insurance;$igst=$d['igst'];$sgst=$d['sgst'];$cgst=$d['cgst']; }
				        } else if($post['product']=='61')
				        {
				            if($freight==12){
				                $fre_charge = $d['tran_charges'];$extra_tran_charges=$d['extra_tran_charges'];$insu =$insurance;$igst=$d['igst'];$sgst=$d['sgst'];$cgst=$d['cgst']; }
				        }
				        else if($post['product']!='11' && $post['product']!='6' && $post['product']!='10' && $post['product']!='23' && $post['product']!='18' && $post['product']!='34' && $post['product']!='47' && $post['product']!='35' && $post['product']!='62' && $post['product']!='61' && $post['product']!='63')
				        {
				            if($freight==9){
				                $fre_charge = $d['tran_charges'];$extra_tran_charges=$d['extra_tran_charges'];$insu =$insurance;$igst=$d['igst'];$sgst=$d['sgst'];$cgst=$d['cgst']; }
				        }
				        $opening_data=$obj_invoice->getOpening_Balance(date('Y',strtotime($d['invoice_date'])),date('m',strtotime($d['invoice_date'])),$d['invoice_date']);
				        
				        $opening = $closing_blan;
				        $menu='0';
				        
				         //printr($fre_charge.'='.$insu);
                		  
				        if($date!=$d['invoice_date'])
				        {
				            if($post['product']=='0'){
				                $menu = $menu1 =$opening_data[0]['quantity_manufactured'];
				            }else if($post['product']=='6'){
				                $menu = $menu1 =$opening_data[0]['quantity_manufactured_roll'];
				           } else if($post['product']=='64'){
				                $menu = $menu1 =$opening_data[0]['quantity_manufactured_roll'];
				            }else{
				               $menu = $menu1 =0; 
				            }
				        }
				        if($srno=='1')
				        {
				            if($post['product']=='0'){
				                $opening = $opening_data[0]['month_opening'];
				           } else if($post['product']=='6'){
				                $opening = $opening_data[0]['month_opening_roll'];
				           } else if($post['product']=='64'){
				                $opening = $opening_data[0]['month_opening_scrap'];
				            }else{
				                $opening = 0;}
				            
				        }
				        if($open!=0)
				        {
				            $menu = $menu1= $open;
				        }
				            
				        if($post['product']=='0' || $post['product']=='6' || $post['product']=='64'){
			                $invoice_product_details['identification_marks'] = $invoice_product_details['identification_marks'];
			                $d['cylinder_charges']=$d['cylinder_charges'];
			           } else {
			                $invoice_product_details['identification_marks'] = $d['total_qty'];
			                 $d['cylinder_charges']=0;
			           }
				        if($menu==0){
				            $menu1='';
				        }
				        //  printr($invoice_product_details['basic_amt'].'=='.$d['invoice_no'].'=='.$fre_charge);
				      //    printr($final_basic_amt.'=='.$d['invoice_no'].'=='.$fre_charge);
				        
				      
				        $date = $d['invoice_date'];
        				echo '<tr>
        				         <td>'.$srno.'</td>
        				         <td>'.$d['invoice_date'].'</td>'; 
        				        echo '<td>'.$opening.'</td>';
        				        
        				        if($post['product']==0 || $post['product']==6 || $post['product']==64){
        				                echo ' <td>'.$menu1.'</td><td>'.($opening+$menu).'</td>';$daily_opening +=$menu1;
        				                }
        				         $sri_charge=0;
        				         if($d['sales_invoice_id']=='1247'){
        				            $sri_charge='1200';
        				         } 
        				            
        				        if(($d['transport'] == 'road' || strtolower($d['transport']) == 'by road') && ($d['country_id']!='26' && $d['country_id']!='27' && $d['country_id']!='169'))
        				        {
        				         // if($d['country_id']!='26' &&  $d['country_id']!='27'){
            				       
            				            echo '<td style="background-color: #f7ddc6;">'.$invoice_product_details['identification_marks'].'</td>';
                				        echo '<td style="background-color: #f7ddc6;">'.number_Format($invoice_product_details['basic_amt'],2).'</td>';
                				        echo '<td style="background-color: #f7ddc6;">'.number_Format($fre_charge,2).'</td>';
                				        $net_road +=$invoice_product_details['identification_marks'];
                				        $sub_road +=$invoice_product_details['basic_amt'];
                				        $fre_road +=$fre_charge;
                				        if($post['product']!=0){
                				            $invoice_product_details['basic_amt']+=$fre_charge;
                				        }else{
                				             $basic_amt_all_product_[0]['basic_amt']+=$fre_charge;}
        				       // }else
        				           //echo '<td></td><td></td><td></td>';
        				            
        				        }
        				        else{
        				            echo '<td style="background-color: #f7ddc6;"></td><td style="background-color: #f7ddc6;"></td><td style="background-color: #f7ddc6;"></td>';
        				        
        				        } 
        				        if($d['igst_status']==1){
        				            
        				         if($d['transport'] == 'air' && ($d['country_id']!='26' || $d['country_id']!='27' || $d['country_id']!='169'))
        				          {
        				              $igst=18;
            				        if($invoice_inv_data['invoice_date']<'2018-10-12'){	
            				           if($invoice_inv_data['invoice_id']!='1899'){
                				          	if($invoice_inv_data['country_destination']==='172'   ||$invoice_inv_data['country_destination']==='253')//||  $d['country_id']=='125'
                                    		{
                                    		    $f_charge=$fre_charge;
                                    		}
            				           }
            				    	}
            				           // printr($final_basic_amt.'=='.$extra_tran_charges.'=='.$f_charge.'===='.$d['invoice_no']);
            				            echo '<td style="background-color: #b4e89f;">'.$invoice_product_details['identification_marks'].'</td>';
                				        echo '<td style="background-color: #b4e89f;">'.number_Format(((($final_basic_amt+$f_charge)-$fre_charge)+$d['cylinder_charges']+$sri_charge)* $d['currency_rate'],2).'</td>';
                				        echo '<td style="background-color: #b4e89f;">'.number_Format(($fre_charge+$extra_tran_charges+$insu)* $d['currency_rate'],2).'</td>';
                				        $net_air +=$invoice_product_details['identification_marks'];
                				        $sub_air +=((($final_basic_amt+$f_charge)-$fre_charge)+$d['cylinder_charges'])* $d['currency_rate'];
                				        $fre_air +=($fre_charge+$extra_tran_charges+$insu)* $d['currency_rate'];
                				          //$d['cgst']=$d['sgst']=$igst=0;
        				          } 
        				        
        				       else if($d['transport'] == 'sea' && ($d['country_id']!='26' || $d['country_id']!='27' || $d['country_id']!='169'))
        				        {
        				            echo '<td style="background-color: #b7e4f3;">'.$invoice_product_details['identification_marks'].'</td>';
            				        echo '<td style="background-color: #b7e4f3;">'.number_Format(($final_basic_amt+$d['cylinder_charges']) * $d['currency_rate'],2).'</td>';
            				        echo '<td style="background-color: #b7e4f3;">'.number_Format($fre_charge* $d['currency_rate'],2).'</td>';
            				        $net_sea +=$invoice_product_details['identification_marks'];
            				        $sub_sea +=($final_basic_amt+$d['cylinder_charges']) * $d['currency_rate'];
            				        $fre_sea +=$fre_charge* $d['currency_rate'];
            				       
            				        //$igst='18';$d['cgst']=$d['sgst']=0;
        				        }
        				        else{
        				            echo '<td style="background-color: #b7e4f3;"></td><td style="background-color: #b7e4f3;"></td><td style="background-color: #b7e4f3;"></td>';
        				        }
        				      
        				      
        				      
        				        }else{
        				             if($d['transport'] == 'sea' && ($d['country_id']!='26' || $d['country_id']!='27' || $d['country_id']!='169'))
                				        {
                				            echo '<td style="background-color: #b7e4f3;">'.$invoice_product_details['identification_marks'].'</td>';
                    				        echo '<td style="background-color: #b7e4f3;">'.number_Format(($final_basic_amt+$d['cylinder_charges']) * $d['currency_rate'],2).'</td>';
                    				        echo '<td style="background-color: #b7e4f3;">'.number_Format($fre_charge* $d['currency_rate'],2).'</td>';
                    				        $net_sea +=$invoice_product_details['identification_marks'];
                    				        $sub_sea +=($final_basic_amt+$d['cylinder_charges']) * $d['currency_rate'];
                    				        $fre_sea +=$fre_charge* $d['currency_rate'];
                    				       
                    				        //$igst='18';$d['cgst']=$d['sgst']=0;
                				        }
            				        else{
            				            echo '<td style="background-color: #b7e4f3;"></td><td style="background-color: #b7e4f3;"></td><td style="background-color: #b7e4f3;"></td>';
            				        }
            				      
        				         }
        				        if($d['transport'] == 'air'  && $d['igst_status']==0 && ($d['country_id']!='26' || $d['country_id']!='27' || $d['country_id']!='169'))
        				        { 
        				            
        				            $igst=0;
        				          	if($invoice_inv_data['invoice_date']<'2018-10-12'){	
        				           if($invoice_inv_data['invoice_id']!='1899'){
        				          	if($invoice_inv_data['country_destination']==='172'   ||$invoice_inv_data['country_destination']==='253')//||  $d['country_id']=='125'
                            		{
                            		    $f_charge=$fre_charge;
                            		}
        				           }
        				          	}
        				           // printr($final_basic_amt.'=='.$extra_tran_charges.'=='.$f_charge.'===='.$d['invoice_no']);
        				            echo '<td style="background-color: #f7ddc6;">'.$invoice_product_details['identification_marks'].'</td>';
            				        echo '<td style="background-color: #f7ddc6;">'.number_Format(((($final_basic_amt+$f_charge)-$fre_charge)+$d['cylinder_charges']+$sri_charge)* $d['currency_rate'],2).'</td>';
            				        echo '<td style="background-color: #f7ddc6;">'.number_Format(($fre_charge+$extra_tran_charges+$insu)* $d['currency_rate'],2).'</td>';
            				        $net_air +=$invoice_product_details['identification_marks'];
            				        $sub_air +=((($final_basic_amt+$f_charge)-$fre_charge)+$d['cylinder_charges'])* $d['currency_rate'];
            				        $fre_air +=($fre_charge+$extra_tran_charges+$insu)* $d['currency_rate'];
            				          //$d['cgst']=$d['sgst']=$igst=0;
        				        }
        				        else{
        				            echo '<td style="background-color: #f7ddc6;"></td><td style="background-color: #f7ddc6;"></td><td style="background-color: #f7ddc6;"></td>';
        				        }  
        				    
        				    
        				    
        				    
        				    
        				      if($d['taxation']=='SEZ Unit No Tax' && $d['igst']=='0.00' && ($d['country_id']=='26' || $d['country_id']=='27' || $d['country_id']=='169'))
        				            
        				             { 
        				                	if($invoice_inv_data['invoice_date']<'2018-10-12'){	
            				            	if($invoice_inv_data['country_destination']==='172' || $invoice_inv_data['country_destination']==='253')//||  $d['country_id']=='125'
                                        		{
                                        		    $f_charge=$fre_charge;
                                        		}
        				                	}
        				                  echo '<td style="background-color: #b7e4f3;">'.$invoice_product_details['identification_marks'].'</td>';
                    				        echo '<td style="background-color: #b7e4f3;">'.number_Format(((($final_basic_amt+$f_charge)-$fre_charge)+$d['cylinder_charges'])* $d['currency_rate'],1).'</td>';
                    				        echo '<td style="background-color: #b7e4f3;">'.number_Format(($fre_charge+$extra_tran_charges+$insu)* $d['currency_rate'],2).'</td>';
                    				        $net_air +=$invoice_product_details['identification_marks'];
                    				        $sub_air +=number_Format(((($final_basic_amt+$f_charge)-$fre_charge)+$d['cylinder_charges'])* $d['currency_rate'],1);
                    				        $fre_air +=($fre_charge+$extra_tran_charges+$insu)* $d['currency_rate'];
            				          //$d['cgst']=$d['sgst']=$igst=0;
        				              } else{
        				                    echo '<td style="background-color: #b7e4f3;"></td><td style="background-color: #b7e4f3;"></td><td style="background-color: #b7e4f3;"></td>';
        				      
        				              }
        				      
        				      
        				      
        				      
        				      
        				      
        				      
        				      
        				      
        				        echo '<td>18%</td>';
            			/*	 if($d['igst_status']==1){
            				    if($d['transport'] == 'air')
                				        {
                				             // number_Format(((($final_basic_amt+$f_charge)-$fre_charge)+$d['cylinder_charges']+$sri_charge)* $d['currency_rate'],2)
                				             
                				            // $igst_amt=number_Format(((($final_basic_amt+$f_charge)-$fre_charge)+$d['cylinder_charges']+$sri_charge),2);
                				          //   printr();
                				            echo '<td style="background-color: #d8f3cd;">'.number_Format((((((($final_basic_amt+$f_charge)-$fre_charge)+$d['cylinder_charges']+$sri_charge) *$igst )* $d['currency_rate']) /100),2).'</td>';
                				            $t_igst+=number_Format((((((($final_basic_amt+$f_charge)-$fre_charge)+$d['cylinder_charges']+$sri_charge) *$igst )* $d['currency_rate']) /100),2);
                				           
                				            
                				        }else{
                				            */
                				            
                				            
                				             if($d['transport'] == 'air' && $d['igst_status']==1)
                        				        {
                        				            echo '<td style="background-color: #d8f3cd;">'.number_Format((((((($final_basic_amt+$f_charge)-$fre_charge)+$d['cylinder_charges']+$sri_charge) *$igst )* $d['currency_rate']) /100),2).'</td>';
                        				            $t_igst+=number_Format((((((($final_basic_amt+$f_charge)-$fre_charge)+$d['cylinder_charges']+$sri_charge) *$igst )* $d['currency_rate']) /100),2);
                        				       }
                				            
                				            else  if($d['transport'] == 'sea')
                        				        {
                        				             //  printr($basic_amt_all_product_[0]['basic_amt'].'==='.$d['invoice_no'].'=='.$igst.'=='.$d['currency_rate']);
                        				            echo '<td style="background-color: #d8f3cd;">'.number_Format((((($basic_amt_all_product_[0]['basic_amt']+$d['cylinder_charges']) *$igst )* $d['currency_rate']) /100),2).'</td>';
                        				            $t_igst+=number_Format((((($basic_amt_all_product_[0]['basic_amt']+$d['cylinder_charges']) *$igst )* $d['currency_rate']) /100),2);
                        				        }
                        				        else
                        				        {
                        				              if($d['transport'] != 'air' && $d['igst_status']!=1){
                            				            echo '<td style="background-color: #d8f3cd;">'.(($basic_amt_all_product_[0]['basic_amt'] * $igst ) /100).'</td>';
                            				            $t_igst+=(($basic_amt_all_product_[0]['basic_amt'] * $igst ) /100);
                        				              }else{
                        				                  
                        				                   echo '<td style="background-color: #d8f3cd;">'.number_Format((((((($final_basic_amt+$f_charge)-$fre_charge)+$d['cylinder_charges']+$sri_charge) *$igst )* $d['currency_rate']) /100),2).'</td>';
                        				                   $t_igst+=number_Format((((((($final_basic_amt+$f_charge)-$fre_charge)+$d['cylinder_charges']+$sri_charge) *$igst )* $d['currency_rate']) /100),2);
                        				     
                        				              }
                        				        } 
                        			
                    				        echo '<td style="background-color: #d8f3cd;">'.(($basic_amt_all_product_[0]['basic_amt'] * $cgst ) /100).'</td>
                    				                <td style="background-color: #d8f3cd;">'.(($basic_amt_all_product_[0]['basic_amt'] * $sgst ) /100).'</td>';
                    				        
                    				        $t_cgst+=(($basic_amt_all_product_[0]['basic_amt'] * $cgst ) /100);
                    				        $t_sgst+=(($basic_amt_all_product_[0]['basic_amt'] * $sgst ) /100);
        				                
                				      //  } 
            			/*	 } else{	
                    			  if($d['transport'] == 'sea')
                                				        {
                                				             //  printr($basic_amt_all_product_[0]['basic_amt'].'==='.$d['invoice_no'].'=='.$igst.'=='.$d['currency_rate']);
                                				            echo '<td style="background-color: #d8f3cd;">'.number_Format((((($basic_amt_all_product_[0]['basic_amt']+$d['cylinder_charges']) *$igst )* $d['currency_rate']) /100),2).'</td>';
                                				            $t_igst+=number_Format((((($basic_amt_all_product_[0]['basic_amt']+$d['cylinder_charges']) *$igst )* $d['currency_rate']) /100),2);
                                				        }
                                				        else
                                				        {
                                				            
                                				            echo '<td style="background-color: #d8f3cd;">'.(($basic_amt_all_product_[0]['basic_amt'] * $igst ) /100).'</td>';
                                				            $t_igst+=(($basic_amt_all_product_[0]['basic_amt'] * $igst ) /100);
                                				        } 
                                			
                            				        echo '<td style="background-color: #d8f3cd;">'.(($basic_amt_all_product_[0]['basic_amt'] * $cgst ) /100).'</td>
                            				                <td style="background-color: #d8f3cd;">'.(($basic_amt_all_product_[0]['basic_amt'] * $sgst ) /100).'</td>';
                            				        
                            				        $t_cgst+=(($basic_amt_all_product_[0]['basic_amt'] * $cgst ) /100);
                            				        $t_sgst+=(($basic_amt_all_product_[0]['basic_amt'] * $sgst ) /100);	 
        				        
				                }*/
            				       
        				        $closing_blan = (($opening+$menu)-$invoice_product_details['identification_marks']);
        				        if($post['product']==0 || $post['product']==6 || $post['product']==64){
        				            echo '<td>'.$closing_blan.'</td>';
        				       } else{
        				            $closing_blan = 0;
        				       }
        				        echo '<td style="background-color: #fbafaf;">'.$d['invoice_no'].'</td>';
        				        echo '<td style="background-color: #fbafaf;"> ';
        				        if($d['invoice_status'] == '0'){
        				                $html.= $d['invoice_no'];
        				     }   echo '</td>';
        				        echo '<td>'.$d['remark'].'</td><td></td>
				        
				        </tr>';
                        
                        				     
				        		$srno++;
				    }
    				echo'<tr>
    				            <td><b>Total</b></td><td></td><td></td>';
    				          if($post['product']==0 || $post['product']==6 || $post['product']==64) {
    				                echo'<td>'.$daily_opening.'</td><td></td>';
    				          }
    				          echo '<td>'.$net_road.'</td><td>'.$sub_road.'</td><td>'.$fre_road.'</td>
    				                  <td>'.$net_sea.'</td><td>'.$sub_sea.'</td><td>'.$fre_sea.'</td>
    				                  <td>'.$net_air.'</td><td>'.$sub_air.'</td><td>'.$fre_air.'</td>
    				                  <td></td><td></td><td></td><td></td>
    				                  <td>'.$t_igst.'</td><td>'.$t_cgst.'</td><td>'.$t_sgst.'</td>';
    				          if($post['product']==0 || $post['product']==6 || $post['product']==64){
    				                echo'<td></td>';}
    				               
    				          echo'<td></td><td></td><td></td><td></td>';
    				echo'</tr>';			         
    	    	echo '</tbody></table>';
				echo '</div></div></form>';
        	   }
			
                    	    ?>
                        </div>
                        
                    </div> 
                    
                </div>
              
              </div>
            </form>
                    
         
        </section>
      </div>
    </div>
  </section>
</section>
 <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-4 hidden-xs"> </div>
             
             
            </div>
          </footer>
<style>
	 .report_div{zoom : 68%; }
</style>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
  
 
		
	
 
  jQuery(document).ready(function(){
	   jQuery("#frm_add").validationEngine();
	   
	   var nowTemp = new Date();
		//alert(nowTemp);
	    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		//alert(now);
	    var checkin = $('#f_date').datepicker({
   			onRender: function(date) {
    		return date.valueOf() < now.valueOf() ? '' : '';
    		}
    	}).on('changeDate', function(ev) {
			if (ev.date.valueOf() <= checkout.date.valueOf()) {
				var newDate = new Date(ev.date);
          		newDate.setDate(newDate.getDate());
    			checkout.setValue(newDate);
    		}
    		checkin.hide();
    		$('#t_date')[0].focus();
    	}).data('datepicker');
    	var checkout = $('#t_date').datepicker({
    		onRender: function(date) {
				if(checkin.date.valueOf() > date.valueOf())
						return 'disabled';
					else
						return '';
				
    		}
    	}).on('changeDate', function(ev) {
    		checkout.hide();
    	}).data('datepicker');
});
 

$("#excel_link").click(function(){
/*	var url = getUrl("<?php //echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=viewDailyStockRegisterReport', '',1);?>");
	var post_arr = $('#post_data').val();
	 $.ajax({
        url: url, // the url of the php file that will generate the excel file
       	data : {post_arr : post_arr},
		method : 'post',
        success: function(response){*/
         //   alert(response);
         var html='';
        html+= '<style>  table, th, td {   border: 1px solid black; }</style>'; 
        html+= $('#print_data').html(); 
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(html);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'Daily-stock-report.xls',
							'href': excelData,
							'target': '_blank'
					}).appendTo('body');
					$('#downloadFile').ready(function() { 
						$('#downloadFile').get(0).click();
					});
      /*  }
		
    });*/


});	
</script>
          
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>