<?php
include("mode_setting.php");
$fun = $_GET['fun'];

if($fun == 'product_name')
{
	$product_name = $_POST['product_name'];
	$volume = $_POST['volume'];
	$color = $_POST['color'];
	$result =  $obj_productstatus->getProductCdAll($_POST['product_name'],$_POST['volume'],$_POST['color']);
	echo json_encode($result);
}
if($fun == 'product_code'){
	//echo "hi";
	$product_code = $_POST['product_code'];
	$result = $obj_productstatus->getProductCd($product_code);
	echo json_encode($result);
} 

if($fun == 'productstatus_id')
{
	$response = $obj_productstatus->getproduct_status($_POST['product_code_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
	$decoded_res=json_decode($response);
    //printr($decoded_res->order);
	//die;
	//printr($response);die;
  
 
	$html = '';
	if($decoded_res->rack!='')
	{
	 $html .= '<label class="col-lg-3 control-label">ERP Rack System</label>';
	 $html .= '<div class="panel-body">';
	 $html .= '<div class="table-responsive">';
	 $html .= '<table class="tool-row table-striped  b-t text-small" id="myTable">';
		 $html .= ' <thead>';
    		 $html .= ' <tr>';
        		 $html .= ' <th>Product name</th>';
        		 $html .= ' <th>Rack name</th>';
        		 $html .= ' <th>Rack no</th>';
        		 $html .= ' <th>Qty</th>';	
    		 $html .= ' </tr>';
		 $html .= ' </thead>';
	     $html .= '<tbody id="myTbody">';
    	     $id=0;
    		 foreach($decoded_res->rack as $key=>$val)
    		 {
    				$sy = '<tr><td colspan="4"><b style="color:red;">Sydney Stock</b></td></tr>';
    				$ml = '<tr><td colspan="4"><b style="color:red;">Melbourne Stock</b></td></tr>';
    				
    				if($id==0 && ($val->user_id=='33'))
    				    $html .= $sy;
    				if($id==0 && ($val->user_id=='24'))
    				    $html .= $ml;
    				if($id!=$val->user_id && $id!=0)
    				{
    				    $html .= '<tr><td colspan="4"><div class="line line-dashed m-t-large"></div></td></tr>';
    				    if($val->user_id=='33')
    				        $html .= $sy;
    				    if($val->user_id=='24')
    				        $html .= $ml;
    				}
    				$d=1;
    				$rc = $val->row.'@'.$val->column_name;
    					for($i=1;$i<=$val->g_row;$i++)
    					{
    						for($r=1;$r<=$val->g_col;$r++) 
    							{
    								$n = $i.'@'.$r;
    								if($rc==$n)
    								{
    									$col_row = $rc;
    									$k=$d;							
    								}
    								$d++;
    							}
    					}
    					if($val->user_id== '44'){
    				        $lable = $obj_productstatus->getRackLabelCanada($col_row,$val->goods_master_id);
    				    
    				    }else{
    				         $lable = $obj_productstatus->getLabel($col_row,$val->goods_master_id);
    				    }
    				    
    				    $l =$k;
    					if($lable!='')
    					    $l = $lable;
            				 $html .= '<tr>';
            				    $html .= '<td> '.$val->product_code.'</td>';
            				    $html .= '<td> '.$val->name.'</td>';
            				    $html .= '<td>'.$l.'</td>';
            				    $dispatch_qty=$obj_productstatus->gettotaldispatchSales($val->grouped_s_id);
            				    $rm_qty = $val->qty - $dispatch_qty['total'] ;
            				    $html .= '<td> '.$rm_qty.'</td>';
            			    $html .= '</tr>';
    				  $a[]=$k;
    				  $id=$val->user_id;
    		}
    		$html .= ' </tbody >';
    	    $html .= ' </table>';
    		$html .= ' </div>';
    		$html .= ' </div>';
	}
	else
	{
		echo "<center><b> There are no record available</b></center>";		
	}
	
	$ht = '';
	if($decoded_res->order!='')
	{
	 $ht .= '<label class="col-lg-3 control-label">Goods in transit</label>';
	 $ht .= '<div class="panel-body">';
	 $ht .= '<div class="table-responsive">';
	 $ht .= '<table class="tool-row table-striped  b-t text-small" id="myTable">';
		 $ht .= ' <thead>';
    		 $ht .= ' <tr>';
        		 $ht .= ' <th>Export Invoice No.</th>';
        		 $ht .= ' <th>Product Code</th>';
        		 $ht .= ' <th>Quantity</th>';
        		 $ht .= ' <th>Tacking No.</th>';
        		 $ht .= ' <th>Tracking Info.</th>';	
        		 $ht .= ' <th>Transportation By</th>';	
    		 $ht .= ' </tr>';
		 $ht .= ' </thead>';
	     $ht .= '<tbody id="myTbody">';
    	     $id=0;
    		 foreach($decoded_res->order as $key=>$val1)
    		 { 
    				$ht .= '<tr>';
    				    $ht .= '<td> '.$val1->invoice_no.'</td>';
    				    $ht .= '<td> '.$val1->product_code.'</td>';
    				    $ht .= '<td> '.$val1->qty.'</td>';
    				    $ht .= '<td>'.$val1->tracking_no.'</td>';
    				    $ht .= '<td> '.$val1->trackinfo.'</td>';
    				    $ht .= '<td> '.$val1->transportation.'</td>';
    			    $ht .= '</tr>';
    		}
    	    $ht .= ' </tbody >';
    	   $ht .= ' </table>';
    	$ht .= ' </div>';
    $ht .= ' </div>';
	}
	else
	{
		echo "<center><b> There are no record available</b></center>";		
	}
	  
	
	 $htm = '';
	 if($decoded_res->stock!='')
	 {
	 
	     $htm .= '<label class="col-lg-3 control-label">In Order</label>';
		 $htm .= '<div class="panel-body">';
		 $htm .= '<div class="table-responsive">';
		    $htm .= '<table class="tool-row table-striped  b-t text-small" id="myTable">';
    		 $htm .= ' <thead>';
        		 $htm .= ' <tr>';
            		 $htm .= ' <th>ORDER No.</th>';
            		 $htm .= ' <th>Product Code</th>';
            		 $htm .= ' <th>Expected Delivery Date </th>';
            		 $htm .= ' <th> Order Qty </th>';	
            		 $htm .= ' <th> Dispatch Qty </th>';	
            		 $htm .= ' <th> Remaining Qty </th>';	
        		 $htm .= ' </tr>';
    		 $htm .= ' </thead>';
		       $htm .= '<tbody id="myTbody">';
    		 foreach($decoded_res->stock as $val_data)
    		 {
    			 $htm .= '<tr>
                			          <td> '.$val_data->gen_order_id.'</td>
                			          <td> '.$val_data->product_code.'</td>';
                			 $htm .= '<td> '.dateFormat(4,$val_data->expected_ddate).'</td>';												  
                			 $htm .= '<td>'.$val_data->quantity.'</td>';
                			// printr($val_data->total_dis_qty);
                			 $htm .= '<td>'.$val_data->total_dis_qty .'</td>';
                			 $rm_qty = $val_data->quantity -$val_data->total_dis_qty  ;
                			 //printr($rm_qty);
                		     $htm .= '<td>'.$rm_qty.'</td>
        		         </tr>';
    			 
    		}
		 
		    $htm .= ' </tbody>';
		 $htm .= ' </table>';
		$htm .= ' </div>';
	 $htm .= ' </div>';
		 	
	}
	
	else
	{
		echo "<center><b> There are no record available</b></center>";		
	}
	  
	$pro_arr=array();
    $pro_arr['rack']=$html;
	$pro_arr['order']=$ht;
	$pro_arr['stock']=$htm;
	
	echo json_encode($pro_arr);	
	 
	  

	
	
}
if($fun == 'compare_stock'){
	//echo "hi";
	$data = $_POST['post_arr'];
	$stock_data=json_decode($data);
//	printr($stock_data);die;
    $html='';
     if(!empty($stock_data))    { 
        $html.='<style>
                    table, th, td {
                        border: 1px solid black;
                    }
                    </style>';
                   $html.=' <div class="form-group">';
                       $html.=' <div class="col-lg-9 col-lg-offset-3">';
                           $html.='  <div class="table-responsive">';
                              $html.='  <table class="table table-striped m-b-none text-small" border="1">';
                                    		      $html.='<thead>';
                                            		  $html.='<tr>';
                                            		      $html.='<th colspan="5" style="font-size: 18px;"><center><b>Compare Stock ERP VS XERO</b></center></th>';
                                            	  $html.='</tr>';
                                            	  $html.='<tr>';
                                            		      $html.='<th>Product code</th>';
                                                		  $html.='<th>product decription</th>';
                                                		  $html.=' <th>Xero Qty</th>';
                                                		  $html.=' <th>ERP Stock Qty</th>';
                                                		  $html.=' <th>Difference Qty</th>';
                                            	  $html.='</tr>';
                                            	 $html.='</thead>';
                	                 	         $html.=' <tbody>';
                	                 	                    foreach($stock_data as $key=>$csv)
                	                 	                          { 
                	                 	                          $html.='  <tr>';
                                                            		  $html.='<td>'. $key.'></td>';
                                                            		  $html.='<td>'. $csv[0]->pro_description.'</td>';
                                                            		  $html.='<td>'.$csv[0]->xero_qty.'</td>';
                                                            		  $html.='<td>'. $csv[0]->qty.'</td>';
                                                            		  $html.='<td>'.($csv[0]->xero_qty-$csv[0]->qty).'</td>';
                                                                 $html.='</tr> ';
                	                 	                    }
                	                 	         $html.='</tbody>';
                	                 	  $html.='</table>';
                	                 $html.='</div>';
                                 $html.='</div>';    
                          $html.='</div> ';
    }	else
	{
	   $html.='center><b> There are no record available</b></center>';		
	}

 echo $html;
}

?>