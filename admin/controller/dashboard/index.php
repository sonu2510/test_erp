<?php

	//send_email('gandhi.deep.153@gmail.com','gandhi.deep.153@gmail.com','hello','This is test','');
    
	include("mode_setting.php");
	//printr($display_status);die;	
	if($display_status) {
	
	
	
//	$total_quotation = $obj_dashboard->getTotalQuotation($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
	
//	$total_active_quotation = $obj_dashboard->getTotalActiveQuotation($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
	
//	$total_inactive_quotation = $obj_dashboard->getTotalInactiveQuotation($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
	
/*	include('model/template_order.php');
	$obj_template = new templateorder;
	
	
	include('model/invoice_test.php');
	$obj_invoice = new invoice;
	// comment by sonu add in mode setting 15-11-2019
	*/
	
	
	
	
	
	//printr($_SESSION);die;
	//jaya 7-12-2015
	//printr(is_loginAdmin);die;
	$rout='multi_product_quotation';
	$menuId = $obj_general->getMenuId($rout);
	
	$rout_digi='digital_quotation';
	$digi_Id = $obj_general->getMenuId($rout_digi);
	
	$rout_stock = 'template_order_test';
	$stock_id = $obj_general->getMenuId($rout_stock);
	
	$rout_custom = 'custom_order';
	$custom_id = $obj_general->getMenuId($rout_custom);
	
	$rout_enquiry = 'enquiry';
	$enquiry_id = $obj_general->getMenuId($rout_enquiry);
	
	$rout_sales = 'sales_invoice';
	$sales_id = $obj_general->getMenuId($rout_sales);
	
	$rout_gove = 'government_sales_invoice';
	$gov_id = $obj_general->getMenuId($rout_gove);

	$rout_proforma = 'proforma_invoice_product_code_wise';
	$proforma_id = $obj_general->getMenuId($rout_proforma);
	
	$rout_domestic_stock = 'domestic_stock';
	$domestic_stock_id = $obj_general->getMenuId($rout_domestic_stock);
	
	$rout_status = 'product_status';
	$product_status = $obj_general->getMenuId($rout_status);
	
	$rout_inventry = 'invoice_inventory';
	$inventry = $obj_general->getMenuId($rout_inventry);
	
	$rout_tax = 'maxico_tax_calender';
	$tax_id = $obj_general->getMenuId($rout_tax);
	
	$sin_tax = 'singapore_tax_calender';
	$sin_id = $obj_general->getMenuId($sin_tax);
	
	$aus_tax = 'australia_tax_calender';
	$aus_id = $obj_general->getMenuId($aus_tax);
	
	$printing_job = 'printing_process'; 
	$printing_job_id = $obj_general->getMenuId($printing_job);
	$job_master = 'job_master';
	$job_master_id = $obj_general->getMenuId($job_master);


	$lamination = 'lamination_process';
	$lamination = $obj_general->getMenuId($lamination);

	$slitting = 'slitting_process';
	$slitting = $obj_general->getMenuId($slitting);
	
	$pouching = 'pouching_process';
	$pouching = $obj_general->getMenuId($pouching);
	
//	$pouching = 'Show Stock Permission';
	$show_stock = $obj_general->getMenuId(311);
//	printr($inventry);
	
	
	$proforma_pro_wise_id = $obj_general->getMenuId('proforma_invoice_product_code_wise');
//	printr($proforma_pro_wise_id);
	$true=0;
	if($obj_general->hasPermission('add',$tax_id )){
		$table_name='maxico_tax_calender';
		$order_id='tax_calender_id';
		$true=1;
		
	}elseif($obj_general->hasPermission('add',$sin_id ))
	{
		$table_name='singapore_tax_calender';
		$order_id='tax_sin_calender_id';
		$true=1;
	
	}elseif($obj_general->hasPermission('add',$aus_id ))
	{
		$table_name='australia_tax_calender';
		$order_id='tax_aus_calender_id';
		$true=1;
	}
	if($true=='1')
		$max_data = $obj_dashboard->getlatestReminderDates($table_name,$order_id);
	
	
	$get_user=$obj_invoice->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
	
	//menu_id for Goods-in-transit on 22-11-2016 by jaya
	$menuId_goods = 214;//online 168
	$val_true=0;
	if($obj_general->hasPermission('view',$menuId_goods )){
		$val_true=1;
		$tablename='invoice_test';
		
		$data = array ('sort'=>'invoice_id',
		                'order'=>'DESC');
		
		if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')
			$get_user['user_id']='';
		$goods_data = $obj_invoice->getInvoice($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],$data,'',2,$get_user['user_id'],5);
		//printr($goods_data);
	}
	//printr($get_user);
	//printr($goods_data); 
	
	
	if(isset($_POST['btn_generate'])){
		
		$obj_invoice->saveImportCharges($_POST);
		page_redirect($obj_general->link('dashboard', '', '',1));
		//die;
	}
	if(isset($_POST['btn_convert'])){
		
		$obj_invoice->convertInPurchase($_POST);
		page_redirect($obj_general->link('dashboard', '', '',1));
	}
	//if($_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1')
	   //echo phpinfo();
?>
 
    <section id="content">
      <section class="main padder">
      	<div class="clearfix">
          <h4><i class="fa fa-dashboard"></i> Dashboard &nbsp;&nbsp;&nbsp; 
          <?php //printr(getenv('HTTP_CLIENT_IP'));
         // if($_SESSION['LOGIN_USER_TYPE']!='1' && $_SESSION['ADMIN_LOGIN_SWISS']!='1')
		  //{
          if($obj_general->hasPermission('add',$menuId)){ ?>
          <span class="text-muted m-l-small pull-right">   <a href="<?php echo $obj_general->link('multi_product_quotation', 'mod=add', '',1);?>" class="btn btn-inverse btn-circle"><i class="fa fa-pencil-square-o"></i><b>New Custom Quoation </b></a></span>
          <?php } 
          if($obj_general->hasPermission('add',$digi_Id)){ ?>
          <span class="text-muted m-l-small pull-right">   <a href="<?php echo $obj_general->link('digital_quotation', 'mod=add', '',1);?>" class="btn btn-inverse btn-circle"><i class="fa fa-pencil-square-o" style="background-color:#ec0f38;"></i><b>New Digital Quoation </b></a></span>
          <?php }
		  
		  	if($obj_general->hasPermission('add',$custom_id )){?>
           &nbsp;&nbsp;&nbsp;<span class="text-muted m-l-small pull-right">   <a href="<?php echo $obj_general->link('custom_order', 'mod=add', '',1);?>" class="btn btn-circle btn-twitter"><i class="fa fa-shopping-cart"></i><b>New Custom Order</b></a></span>
           <?php }
			 if($obj_general->hasPermission('add',$stock_id ) && $_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1'){ ?>
           &nbsp;&nbsp;&nbsp;<span class="text-muted m-l-small pull-right">   <a href="<?php echo $obj_general->link('template_order_test', 'mod=add&s_no=MQ==&status=0', '',1);?>" class="btn btn-danger btn-circle"><i class="fa fa-shopping-cart"></i><b>New Stock Order</b></a></span>
           <?php }  if($obj_general->hasPermission('add',$enquiry_id )){?>
            &nbsp;&nbsp;&nbsp;<span class="text-muted m-l-small pull-right">   <a href="<?php echo $obj_general->link('enquiry', 'mod=add', '',1);?>" class="btn btn-circle btn-success"><i class="fa fa-question-circle"></i><b>New Enquiry</b></a></span>
            <?php } if($obj_general->hasPermission('add',$sales_id )){?>
            <!--&nbsp;&nbsp;&nbsp;<span class="text-muted m-l-small pull-right">   <a href="<?php //echo $obj_general->link('sales_invoice', 'mod=add&is_delete=0', '',1);?>" class="btn btn-facebook btn-circle"><i class="fa fa-truck"></i><b>New Sales Invoice</b></a></span>-->
            <?php } if($obj_general->hasPermission('add',$proforma_id )){ ?>
            &nbsp;&nbsp;&nbsp;<span class="text-muted m-l-small pull-right">   <a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', 'mod=add&is_delete=0', '',1);?>" class="btn btn-warning btn-circle"><i class="fa fa-cloud"></i><b>New Proforma</b></a></span>
            <?php } //if($obj_general->hasPermission('add',$tax_id )){
				if($obj_general->hasPermission('view',$product_status )){?>
           &nbsp;&nbsp;&nbsp;<span class="text-muted m-l-small pull-right">   <a href="<?php echo $obj_general->link('product_status', '', '',1);?>" class=" btn btn-info btn-circle active">
           <i class="fa fa fa-tasks"></i><b>Product Status</b></a></span>
           <?php } 
           	if($obj_general->hasPermission('view',$inventry )){?>
           &nbsp;&nbsp;&nbsp;<span class="text-muted m-l-small pull-right">   <a href="<?php echo $obj_general->link('invoice_inventory', '', '',1);?>" class="btn btn-circle btn btn-primary ">
           <i class="fa fa-briefcase"></i><b>Inventory</b></a></span>
           <?php } ?>
           	<?php  if(($obj_general->hasPermission('view','311' ))  ||  $_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1'){   //printr('hiiiiiiiiiiii');     
           	 ?>
           &nbsp;&nbsp;&nbsp;<span class="text-muted m-l-small pull-right">   <a href="<?php echo $obj_general->link('domestic_stock', 'mod=list_product', '',1);?>" class="btn btn-info btn-circle active"><i class="fa fa-shopping-cart"></i><b>Show Swisspac Stock</b></a></span>
          <?php }?>
            </h4> 
          
          
        </div>
       <div class="row">
		  <?php 
              if($obj_general->hasPermission('view','311' ))
    		  {     
    		    ?>
    		      <!--<div class="col-md-6">
						   <section class="panel">
						       <header class="panel-heading bg bg-inverse">
						            <center><b>Swisspac Stock Management <?php echo date("M").' - '.date("Y") ;?></b></center>
						       </header>
        						  <div class="panel-body">      
                                    <div class="form-group">
        						        <label class="col-lg-2 control-label">Product Code</label>
                                        <?php //$productcodes = $obj_dashboard->getActiveProductCode();?> 
                                            <div class="col-lg-4">
                                                <select name="filter_product_code" class="form-control chosen_data" id="product_code_id" onchange="getdomesticStock()">
                                                    <option value="">Select Product</option> 
                                                    <?php /* foreach($productcodes as $code)
                                                       {
                                                              echo '<option value="'.$code['product_code_id'].'">'.$code['product_code'].'</option>';
                                                       }*/
                                                       ?>
                                                </select>
                                            </div>
                                    <label class="col-lg-2 control-label">Stock Qty</label>
                                        
                                            <div class="col-lg-4">
                                                   <input type="text" name="stock_qty" id="stock_qty" value="" class="form-control" readonly/>
                                            </div>           
        						  
        						        </div>
        						    </div>
						    </section>
					   </div>-->
			     
			   
    	   <?php }?>
    	   
    	       <div class="col-md-6">
						   <section class="panel">
						       <header class="panel-heading bg bg-inverse">
						            <center>  <i class="fa fa-search"></i> <b>   Search </b></center>
						       </header>
        						  <div class="panel-body">      
                                    	 <form class="form-horizontal" method="post"  target="_blank"  name="frm_add" id="frm_add" enctype="multipart/form-data" action="<?php echo $obj_general->link('dashboard', 'mod=search_details', '',1);?>">
                                    <div class="form-group">
        						        <label class="col-lg-1 control-label"> Select</label>
                                      
                                            <div class="col-lg-4">
                                                <select name="filter_module" class="form-control " id="search_data" >
                                                    <option value=""> Select Module</option> 
                                                    <option value="1=<?php echo $proforma_pro_wise_id;?>">Proforma Invoice No</option> 
                                                    <option value="2=<?php echo $sales_id;?>">Sales Invoice No</option> 
                                                    <option value="3=<?php echo $proforma_id;?>">Buyer's Order No</option> 
                                                    <option value="4=<?php echo $proforma_id;?>"> Proforma Payment Amount</option> 
                                                    <option value="5=<?php echo $custom_id;?>">Custom Order Number</option> 
                                                    <option value="6=<?php echo $stock_id;?>">Stock Order Number</option> 
                                                    <option value="7=<?php echo $digi_Id;?>">Digital Order Number</option> 
                                                    <option value="8=<?php echo $menuId;?>">Multi Quotation Number</option> 
                                                    <option value="9=<?php echo $digi_Id;?>">Digital Quotation Number</option>
                                                    <option value="10=<?php echo $enquiry_id;?>">Enquiry Number or Customer Name</option> 
                                             
                                                </select>
                                            </div>
                                   		 <label class="col-lg-1 control-label">Name Or Number</label>                                       
                                            <div class="col-lg-3">
                                                   <input type="text" name="fillter_data" id="fillter_data" value="" class="form-control" />
                                                 
                                            </div>   
                                            <label class="col-lg-1 control-label"></label>                                       
                                            <div class="col-lg-2">
                                                 	<button type="submit" name="btn_search" id="btn_search" class="btn btn-primary">Serach</button>	
                                            </div>           
        						  
        						        </div>
        						    </div>
        						</form>
						    </section>
					   </div>
    	   
    	   
    	   
    	   
    	   
    	   <?php  if($_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1')
    		  {    $rate = $obj_dashboard->getbounceRecord();   ?>
    		       
					   <div class="col-md-6" style="display:none;">
						   <section class="panel">
						       <header class="panel-heading bg-white">
						            <center><b><?php echo date("M").' - '.date("Y") ;?></b></center>
						       </header>
						       <table class="table b-t text-small table-hover">
                                 <thead>
                                    <tr>
                                      <th style="width: 80%;">Proforma Bounce Rate (%)</th>
                                      <th>Employee Name</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                     <?php foreach($rate as $rt) 
                                           {?>
                                             <tr>
                                                 <td>
                                                     <div class="progress progress-small progress-striped active">
                                                        <div class="progress-bar progress-bar-danger" data-toggle="tooltip" data-original-title="<?php echo number_format($rt['bounce_inv'],2).'%'; ?>" style="width: <?php echo floor($rt['bounce_inv']).'%'; ?>"><?php echo number_format($rt['bounce_inv'],2).'%'; ?></div>
                                                        <span  class="popOver" data-toggle="sucess_tooltip" data-placement="top" title="<?php echo number_format($rt['sucess_inv'],2).'%'; ?>"> </span>
                                                     </div>
                                                 </td>
                                                 <th>
                                                     <?php echo $rt['employee'];?>
                                                 </th>
                                             </tr>
                                    <?php } ?>
                                 </tbody>
                                </table>
						    </section>
					   </div>
				<?php }   ?>	   
				    </div>
		    
               
           				<?php
					if($obj_general->hasPermission('view','233' ))
					{//online : 233 & offline=227
				
					 ?>
						<script src="<?php echo HTTP_SERVER;?>js/Chart.bundle.js"></script>
						
						<section class="panel">
								<div class="carousel slide auto panel-body" id="c-slide">
									<ol class="carousel-indicators out">										
											<?php 	if($obj_general->hasPermission('add',$sales_id ) || $obj_general->hasPermission('add',$gov_id ))
													{ 
													  ?>
														<li data-target="#c-slide" data-slide-to="0" class="active"></li>
													<?php if(isset($get_user['international_branch_id']) &&  $get_user['international_branch_id']=='10')
														{?>
														 <li data-target="#c-slide" data-slide-to="1" class=""></li>
														 <li data-target="#c-slide" data-slide-to="2" class=""></li>
														 <li data-target="#c-slide" data-slide-to="3" class=""></li>
													<?php } 
													} ?>
												<?php if(($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1') OR isset($get_user['international_branch_id']) &&  $get_user['international_branch_id']!='10')
    												  {
    													if($obj_general->hasPermission('add',$proforma_pro_wise_id ))
    													{?>
														    <li data-target="#c-slide" data-slide-to="1" class=""></li>
											   <?php    }
											          }?>
									</ol>
    									<div class="carousel-inner">
										<div class="item active">
											 <?php 	if($obj_general->hasPermission('add',$sales_id ) || $obj_general->hasPermission('add',$gov_id ))
												{?>
													<div class="row">
													   <div class="col-md-12">
													   <?php $sales = $obj_dashboard->GetTotalSales('');?>
																<input type="hidden" id="arr" value="<?php echo json_encode($sales);?>">	
																<div style="font-size:16px;"><b>SALES CHART <?php echo date("Y");?></b></div>
																<canvas id="myChart" height="50"></canvas>					
														</div>
													   </div>
															<script>
															var ctx = document.getElementById("myChart").getContext('2d');
															var myChart = new Chart(ctx, {
																type: 'bar',
																/*animationEnabled: true,*/
																/*exportEnabled : true,*/
																data: {
																	labels: ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"],
																	//var color = Chart.helpers.color;
																	datasets: [
																	<?php //printr($sales);
																				if(isset($sales) && !empty($sales))
																				{ 
																					foreach($sales as $key =>$s)
																					{//printr($s);
																						if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')
																						{
																							echo $view=$obj_dashboard->getChartView($s,'','',$key);
																						}
																						else
																						{											
																							foreach($s as $month)
																							{ //printr($month);
																								$country = 'rgba('.rand(0,255).','.rand(0,255).','.rand(0,255).','.rand(0,255).')';
																								$con_curr = $month['user_name'];
																								$arr[$month['user_name']][]=array(//'country'=>$country,
																										  'con_curr'=>$con_curr,
																										  'Final_Total'=>$month['Final_Total'],
																										  'month'=>$month['month'],
																										  'user_name'=>$month['user_name']);
																							}
																							//printr($arr);
																							foreach($arr as $key1=>$sal)
																							{ $Jan=$Feb=$Mar=$Apr=$May=$June=$July=$Aug=$Sept=$Oct=$Nov=$Dec="0";
																								
																								
																								
																								foreach($sal as $month)
																								{ 	if(strtoupper($month['month'])==strtoupper('January'))
																									{
																										$Jan=$month['Final_Total'];
																									}
																									elseif(strtoupper($month['month'])==strtoupper('February'))
																									{
																										$Feb=$month['Final_Total'];
																									}
																									elseif(strtoupper($month['month'])==strtoupper('March'))
																									{
																										$Mar=$month['Final_Total'];
																									}
																									elseif(strtoupper($month['month'])==strtoupper('April'))
																									{
																										$Apr=$month['Final_Total'];
																									}
																									elseif(strtoupper($month['month'])==strtoupper('May'))
																									{
																										$May=$month['Final_Total'];
																									}
																									elseif(strtoupper($month['month'])==strtoupper('June'))
																									{
																										$June=$month['Final_Total'];
																									}
																									elseif(strtoupper($month['month'])==strtoupper('July'))
																									{
																										$July=$month['Final_Total'];
																									}
																									elseif(strtoupper($month['month'])==strtoupper('August'))
																									{
																										$Aug=$month['Final_Total'];
																									}
																									elseif(strtoupper($month['month'])==strtoupper('September'))
																									{
																										$Sept=$month['Final_Total'];
																									}
																									elseif(strtoupper($month['month'])==strtoupper('October'))
																									{
																										$Oct=$month['Final_Total'];
																									}
																									elseif(strtoupper($month['month'])==strtoupper('November'))
																									{
																										$Nov=$month['Final_Total'];
																									}
																									else
																									{	
																										$Dec=$month['Final_Total'];
																									}
							
																																					
																								}
																								//printr($clr);
																								$color='rgba('.rand(0,255).','.rand(0,255).','.rand(0,255).','.rand(0,255).')';
																									//$color_series = implode(",",$clr);
																									echo  "{
																												label: '".$key1."',
																												data: [".$Jan.",".$Feb.",".$Mar.",".$Apr.",".$May.",".$June.",".$July.",".$Aug.",".$Sept.",".$Oct.",".$Nov.",".$Dec."],
																												backgroundColor: ['".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."'],
																												borderColor: [
																														'rgba(255,99,132,1)',
																														'rgba(255,99,132,1)',
																														'rgba(255,99,132,1)',
																														'rgba(255,99,132,1)',
																														'rgba(255,99,132,1)',
																														'rgba(255,99,132,1)',
																														'rgba(255,99,132,1)',
																														'rgba(255,99,132,1)',
																														'rgba(255,99,132,1)',
																														'rgba(255,99,132,1)',
																														'rgba(255,99,132,1)',
																														'rgba(255,99,132,1)',
																														],
																																borderWidth: 1
																																},	";
																							}
																						}	
																							
																						
																							
																					}
																				}
																			?>
																			]
																},
																options: {
																	scales: {
																		yAxes: [{
																			ticks: {
																				beginAtZero:true
																			}
																		}]
																	}
																}
															});
												</script>
											
										</div>
										<?php if(isset($get_user['international_branch_id']) &&  $get_user['international_branch_id']=='10')
											{?>
												<div class="item">
														<div class="row">
														   <div class="col-md-12">
														   <?php $sales1 = $obj_dashboard->GetTotalSales('USD'); ?>
																	<input type="hidden" id="arr1" value="<?php echo json_encode($sales);?>">	
																	<div style="font-size:16px;"><b>SALES CHART (USD) <?php echo date("Y");?></b></div>
																	<canvas id="myChartsale" height="50"></canvas>					
															</div>
														

														   </div>
															<script>
																var ctx = document.getElementById("myChartsale").getContext('2d');
																var myChartsale = new Chart(ctx, {
																	/*exportEnabled : true,*/
																	type: 'bar',
																	/*animationEnabled: true,*/
																	
																	data: {
																		labels: ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"],
																		
																		//var color = Chart.helpers.color;
																		datasets: [
																		<?php //printr($sales);
																					if(isset($sales1) && !empty($sales1))
																					{ 
																						foreach($sales1 as $key =>$s1)
																						{//printr($s);
																						
																								foreach($s1 as $month)
																								{ //printr($month);
																									$country = 'rgba('.rand(0,255).','.rand(0,255).','.rand(0,255).','.rand(0,255).')';
																									$con_curr = $month['user_name'];
																									$arr1[$month['user_name']][]=array(//'country'=>$country,
																											  'con_curr'=>$con_curr,
																											  'Final_Total'=>$month['Final_Total'],
																											  'month'=>$month['month'],
																											  'user_name'=>$month['user_name']);
																								}
																							//	printr($arr);
																								foreach($arr1 as $key1=>$sal1)
																								{ $Jan=$Feb=$Mar=$Apr=$May=$June=$July=$Aug=$Sept=$Oct=$Nov=$Dec="0";
																									
																									
																									
																									foreach($sal1 as $month)
																									{ 	if(strtoupper($month['month'])==strtoupper('January'))
																										{
																											$Jan=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('February'))
																										{
																											$Feb=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('March'))
																										{
																											$Mar=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('April'))
																										{
																											$Apr=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('May'))
																										{
																											$May=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('June'))
																										{
																											$June=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('July'))
																										{
																											$July=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('August'))
																										{
																											$Aug=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('September'))
																										{
																											$Sept=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('October'))
																										{
																											$Oct=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('November'))
																										{
																											$Nov=$month['Final_Total'];
																										}
																										else
																										{	
																											$Dec=$month['Final_Total'];
																										}

																																						
																									}
																									//printr($clr);
																									$color='rgba('.rand(0,255).','.rand(0,255).','.rand(0,255).','.rand(0,255).')';
																										//$color_series = implode(",",$clr);
																										echo  "{
																													label: '".$key1."',
																													data: [".$Jan.",".$Feb.",".$Mar.",".$Apr.",".$May.",".$June.",".$July.",".$Aug.",".$Sept.",".$Oct.",".$Nov.",".$Dec."],
																													backgroundColor: ['".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."'],
																													borderColor: [
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															],
																																	borderWidth: 1
																																	},	";
																								}
																								
																								
																							
																								
																						}
																					}
																				?>
																				]
																	},
																	options: {
																		scales: {
																			yAxes: [{
																				ticks: {
																					beginAtZero:true
																				}
																			}]
																		}
																	}
																});
													</script>
												</div>
												<div class="item">
														<div class="row">
														   <div class="col-md-12">
														   <?php    //$pre_year =date("Y",strtotime("-1 year"));
														            
														            $sales_pre = $obj_dashboard->GetTotalSales('USD',date("Y",strtotime("-1 year"))); ?>
																	<input type="hidden" id="arr_pre" value="<?php echo json_encode($sales_pre);?>">	
																	<div style="font-size:16px;"><b>SALES CHART (USD) <?php echo date("Y",strtotime("-1 year"));?></b></div>
																	<canvas id="myChartsale_pre" height="50"></canvas>					
															</div>
														

														   </div>
															<script>
																var ctx = document.getElementById("myChartsale_pre").getContext('2d');
																var myChartsale_pre = new Chart(ctx, {
																	/*exportEnabled : true,*/
																	type: 'bar',
																	/*animationEnabled: true,*/
																	
																	data: {
																		labels: ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"],
																		
																		//var color = Chart.helpers.color;
																		datasets: [
																		<?php //printr($sales);
																					if(isset($sales_pre) && !empty($sales_pre))
																					{ 
																						foreach($sales_pre as $key =>$s1)
																						{//printr($s);
																						
																								foreach($s1 as $month)
																								{ //printr($month);
																									$country = 'rgba('.rand(0,255).','.rand(0,255).','.rand(0,255).','.rand(0,255).')';
																									$con_curr = $month['user_name'];
																									$arr1[$month['user_name']][]=array(//'country'=>$country,
																											  'con_curr'=>$con_curr,
																											  'Final_Total'=>$month['Final_Total'],
																											  'month'=>$month['month'],
																											  'user_name'=>$month['user_name']);
																								}
																							//	printr($arr);
																								foreach($arr1 as $key1=>$sal1)
																								{ $Jan=$Feb=$Mar=$Apr=$May=$June=$July=$Aug=$Sept=$Oct=$Nov=$Dec="0";
																									
																									
																									
																									foreach($sal1 as $month)
																									{ 	if(strtoupper($month['month'])==strtoupper('January'))
																										{
																											$Jan=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('February'))
																										{
																											$Feb=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('March'))
																										{
																											$Mar=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('April'))
																										{
																											$Apr=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('May'))
																										{
																											$May=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('June'))
																										{
																											$June=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('July'))
																										{
																											$July=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('August'))
																										{
																											$Aug=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('September'))
																										{
																											$Sept=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('October'))
																										{
																											$Oct=$month['Final_Total'];
																										}
																										elseif(strtoupper($month['month'])==strtoupper('November'))
																										{
																											$Nov=$month['Final_Total'];
																										}
																										else
																										{	
																											$Dec=$month['Final_Total'];
																										}

																																						
																									}
																									//printr($clr);
																									$color='rgba('.rand(0,255).','.rand(0,255).','.rand(0,255).','.rand(0,255).')';
																										//$color_series = implode(",",$clr);
																										echo  "{
																													label: '".$key1."',
																													data: [".$Jan.",".$Feb.",".$Mar.",".$Apr.",".$May.",".$June.",".$July.",".$Aug.",".$Sept.",".$Oct.",".$Nov.",".$Dec."],
																													backgroundColor: ['".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."'],
																													borderColor: [
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															'rgba(255,99,132,1)',
																															],
																																	borderWidth: 1
																																	},	";
																								}
																								
																								
																							
																								
																						}
																					}
																				?>
																				]
																	},
																	options: {
																		scales: {
																			yAxes: [{
																				ticks: {
																					beginAtZero:true
																				}
																			}]
																		}
																	}
																});
													</script>
												</div>
												<div class="item">
    												<div class="row">
    													   <div class="col-md-12">
    													   <?php $sales_pre1 = $obj_dashboard->GetTotalSales('',date("Y",strtotime("-1 year")));?>
    																<input type="hidden" id="arr_pre1" value="<?php echo json_encode($sales_pre1);?>">	
    																<div style="font-size:16px;"><b>SALES CHART (MXN) <?php echo date("Y",strtotime("-1 year"));?></b></div>
    																<canvas id="myChart_pre1" height="50"></canvas>					
    														</div>
    													   </div>
    															<script>
    															var ctx = document.getElementById("myChart_pre1").getContext('2d');
    															var myChart_pre1 = new Chart(ctx, {
    																type: 'bar',
    																/*animationEnabled: true,*/
    																/*exportEnabled : true,*/
    																data: {
    																	labels: ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"],
    																	//var color = Chart.helpers.color;
    																	datasets: [
    																	<?php //printr($sales);
    																				if(isset($sales_pre1) && !empty($sales_pre1))
    																				{ 
    																					foreach($sales_pre1 as $key =>$s)
    																					{//printr($s);
    																						if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')
    																						{
    																							
    																							
    																							/*if($key=='7')
    																							{
    																								$country = 'rgba(255,0,0,0.3)';
    																								$con_curr = 'Singapore (SGD)';
    																							}
    																							else if($key=='10')
    																							{
    																								$country = 'rgba(0,255,0,0.3)';
    																								$con_curr = 'Mexico (MXN)';
    																							}
    																							else if($key=='44')
    																							{
    																								$country = 'rgba(0,0,255,0.3)';
    																								$con_curr = 'Canada (CAD)';
    																							}
    																							else if($key=='24')
    																							{
    																								$country = 'rgba(192,192,192,0.3)';
    																								$con_curr = 'Melbourne (AUD)';
    																							}
    																							else if($key=='33')
    																							{
    																								$country = 'rgba(255,255,0,0.3)';
    																								$con_curr = 'Sydeny (AUD)';
    																							}
    																							else
    																							{
    																								$country = 'rgba(255,0,255,0.3)';
    																								$con_curr = 'India (INR)';
    																							}*/
    																							echo $view=$obj_dashboard->getChartView($s,'','',$key);
    																						}
    																						else
    																						{											
    																							foreach($s as $month)
    																							{ //printr($month);
    																								$country = 'rgba('.rand(0,255).','.rand(0,255).','.rand(0,255).','.rand(0,255).')';
    																								$con_curr = $month['user_name'];
    																								$arr[$month['user_name']][]=array(//'country'=>$country,
    																										  'con_curr'=>$con_curr,
    																										  'Final_Total'=>$month['Final_Total'],
    																										  'month'=>$month['month'],
    																										  'user_name'=>$month['user_name']);
    																							}
    																							//printr($arr);
    																							foreach($arr as $key1=>$sal)
    																							{ $Jan=$Feb=$Mar=$Apr=$May=$June=$July=$Aug=$Sept=$Oct=$Nov=$Dec="0";
    																								
    																								
    																								
    																								foreach($sal as $month)
    																								{ 	if(strtoupper($month['month'])==strtoupper('January'))
    																									{
    																										$Jan=$month['Final_Total'];
    																									}
    																									elseif(strtoupper($month['month'])==strtoupper('February'))
    																									{
    																										$Feb=$month['Final_Total'];
    																									}
    																									elseif(strtoupper($month['month'])==strtoupper('March'))
    																									{
    																										$Mar=$month['Final_Total'];
    																									}
    																									elseif(strtoupper($month['month'])==strtoupper('April'))
    																									{
    																										$Apr=$month['Final_Total'];
    																									}
    																									elseif(strtoupper($month['month'])==strtoupper('May'))
    																									{
    																										$May=$month['Final_Total'];
    																									}
    																									elseif(strtoupper($month['month'])==strtoupper('June'))
    																									{
    																										$June=$month['Final_Total'];
    																									}
    																									elseif(strtoupper($month['month'])==strtoupper('July'))
    																									{
    																										$July=$month['Final_Total'];
    																									}
    																									elseif(strtoupper($month['month'])==strtoupper('August'))
    																									{
    																										$Aug=$month['Final_Total'];
    																									}
    																									elseif(strtoupper($month['month'])==strtoupper('September'))
    																									{
    																										$Sept=$month['Final_Total'];
    																									}
    																									elseif(strtoupper($month['month'])==strtoupper('October'))
    																									{
    																										$Oct=$month['Final_Total'];
    																									}
    																									elseif(strtoupper($month['month'])==strtoupper('November'))
    																									{
    																										$Nov=$month['Final_Total'];
    																									}
    																									else
    																									{	
    																										$Dec=$month['Final_Total'];
    																									}
    							
    																																					
    																								}
    																								//printr($clr);
    																								$color='rgba('.rand(0,255).','.rand(0,255).','.rand(0,255).','.rand(0,255).')';
    																									//$color_series = implode(",",$clr);
    																									echo  "{
    																												label: '".$key1."',
    																												data: [".$Jan.",".$Feb.",".$Mar.",".$Apr.",".$May.",".$June.",".$July.",".$Aug.",".$Sept.",".$Oct.",".$Nov.",".$Dec."],
    																												backgroundColor: ['".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."'],
    																												borderColor: [
    																														'rgba(255,99,132,1)',
    																														'rgba(255,99,132,1)',
    																														'rgba(255,99,132,1)',
    																														'rgba(255,99,132,1)',
    																														'rgba(255,99,132,1)',
    																														'rgba(255,99,132,1)',
    																														'rgba(255,99,132,1)',
    																														'rgba(255,99,132,1)',
    																														'rgba(255,99,132,1)',
    																														'rgba(255,99,132,1)',
    																														'rgba(255,99,132,1)',
    																														'rgba(255,99,132,1)',
    																														],
    																																borderWidth: 1
    																																},	";
    																							}
    																						}	
    																							
    																						
    																							
    																					}
    																				}
    																			?>
    																			]
    																},
    																options: {
    																	scales: {
    																		yAxes: [{
    																			ticks: {
    																				beginAtZero:true
    																			}
    																		}]
    																	}
    																}
    															});
    												</script>
    											
    										</div>
    									
											<?php } 
										 } ?>
										<?php     
												if(($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1') OR (isset($get_user['international_branch_id']) &&  $get_user['international_branch_id']!='10'))
												{
													if($obj_general->hasPermission('add',$proforma_pro_wise_id ))
													{ ?>
														<div class="item">
															<div class="row">
																 <div class="col-md-12">
																   <?php $proformadataProductCodeWise = $obj_dashboard->GetTotalProformaProductCodeWise();//printr($proformadata);?>
																			<input type="hidden" id="arr" value="<?php echo json_encode($proformadataProductCodeWise);?>">	
																			<div style="font-size:16px;"><b>PROFORMA CHART <?php echo date("Y");?></b></div>
																			<canvas id="myChartProCode" height="50"></canvas>
																	</div>
																

																   </div>
																   <script>
																	var ctx = document.getElementById("myChartProCode").getContext('2d');
																	var myChart = new Chart(ctx, {
																		type: 'bar',
																		/*animationEnabled: true,*/
																	    /*exportEnabled: true,*/
																		data: {
																			labels: ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"],
																			//var color = Chart.helpers.color;
																			datasets: [
																			<?php //printr($proformadata);
																						if(isset($proformadataProductCodeWise))
																						{ 
																							foreach($proformadataProductCodeWise as $key =>$s)
																							{//printr($s);
																								if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')
																								{
																									/*if($key=='7')
																									{//|| ($_SESSION['ADMIN_LOGIN_SWISS']=='7' && $_SESSION['LOGIN_USER_TYPE']=='4')
																										$country = 'rgba(255,0,0,0.3)';
																										$con_curr = 'Singapore (SGD)';
																										
																									}
																									else if($key=='10')
																									{
																										$country = 'rgba(0,255,0,0.3)';
																										$con_curr = 'Mexico (MXN)';
																									}
																									else if($key=='44')
																									{
																										$country = 'rgba(0,0,255,0.3)';
																										$con_curr = 'Canada (CAD)';
																									}
																									else if($key=='24')
																									{
																										$country = 'rgba(192,192,192,0.3)';
																										$con_curr = 'Melbourne (AUD)';
																									}
																									else if($key=='33')
																									{
																										$country = 'rgba(255,255,0,0.3)';
																										$con_curr = 'Sydeny (AUD)';
																									}
																									else if($key=='19')
																									{
																										$country = 'rgba(19,160,165,1)';
																										$con_curr = 'Dubai (AED)';
																									}
																									else
																									{
																										$country = 'rgba(255,0,255,0.3)';
																										$con_curr = 'India (INR)';
																									}*/
																									echo $view=$obj_dashboard->getChartView($s,'','',$key);
																								}
																								else
																								{											
																									foreach($s as $month)
																									{
																										$country = 'rgba('.rand(0,255).','.rand(0,255).','.rand(0,255).','.rand(0,255).')';
																										$con_curr = $month['user_name'];
																										$arr[$month['user_name']][]=array(
																												  'con_curr'=>$con_curr,
																												  'Final_Total'=>$month['Final_Total'],
																												  'month'=>$month['month'],
																												  'user_name'=>$month['user_name']);
																									}
																									
																									foreach($arr as $key=>$sal)
																									{ $Jan=$Feb=$Mar=$Apr=$May=$June=$July=$Aug=$Sept=$Oct=$Nov=$Dec="0";
																										
																										
																										
																										foreach($sal as $month)
																										{ 	if(strtoupper($month['month'])==strtoupper('January'))
																											{
																												$Jan=$month['Final_Total'];
																											}
																											elseif(strtoupper($month['month'])==strtoupper('February'))
																											{
																												$Feb=$month['Final_Total'];
																											}
																											elseif(strtoupper($month['month'])==strtoupper('March'))
																											{
																												$Mar=$month['Final_Total'];
																											}
																											elseif(strtoupper($month['month'])==strtoupper('April'))
																											{
																												$Apr=$month['Final_Total'];
																											}
																											elseif(strtoupper($month['month'])==strtoupper('May'))
																											{
																												$May=$month['Final_Total'];
																											}
																											elseif(strtoupper($month['month'])==strtoupper('June'))
																											{
																												$June=$month['Final_Total'];
																											}
																											elseif(strtoupper($month['month'])==strtoupper('July'))
																											{
																												$July=$month['Final_Total'];
																											}
																											elseif(strtoupper($month['month'])==strtoupper('August'))
																											{
																												$Aug=$month['Final_Total'];
																											}
																											elseif(strtoupper($month['month'])==strtoupper('September'))
																											{
																												$Sept=$month['Final_Total'];
																											}
																											elseif(strtoupper($month['month'])==strtoupper('October'))
																											{
																												$Oct=$month['Final_Total'];
																											}
																											elseif(strtoupper($month['month'])==strtoupper('November'))
																											{
																												$Nov=$month['Final_Total'];
																											}
																											else
																											{	
																												$Dec=$month['Final_Total'];
																											}

																																							
																										}
																										
																										$color='rgba('.rand(0,255).','.rand(0,255).','.rand(0,255).','.rand(0,255).')';
																											echo  "{
																														label: '".$key."',
																														data: [".$Jan.",".$Feb.",".$Mar.",".$Apr.",".$May.",".$June.",".$July.",".$Aug.",".$Sept.",".$Oct.",".$Nov.",".$Dec."],
																														backgroundColor: ['".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."','".$color."'],
																														borderColor: [
																																'rgba(255,99,132,1)',
																																'rgba(255,99,132,1)',
																																'rgba(255,99,132,1)',
																																'rgba(255,99,132,1)',
																																'rgba(255,99,132,1)',
																																'rgba(255,99,132,1)',
																																'rgba(255,99,132,1)',
																																'rgba(255,99,132,1)',
																																'rgba(255,99,132,1)',
																																'rgba(255,99,132,1)',
																																'rgba(255,99,132,1)',
																																'rgba(255,99,132,1)',
																																],
																																		borderWidth: 1
																																		},	";
																									}
																								}	
																									
																								
																									
																							}
																						}
																					?>
																					]
																		},
																		options: {
																			scales: {
																				yAxes: [{
																					ticks: {
																						beginAtZero:true
																					}
																				}]
																			}
																		}
																	});
														</script>
														</div>
										<?php 		}
												}?>
									</div>
									<a class="left carousel-control" href="#c-slide" data-slide="prev"> <i class="fa fa-chevron-left"></i> </a>
									<a class="right carousel-control" href="#c-slide" data-slide="next"> <i class="fa fa-chevron-right"></i> </a>
								</div>
							</section>
							
					<?php } ?>
		   
		   
		<div class="row">
         <?php if($obj_general->hasPermission('view',$menuId ))
		 	{?>
          <div class="col-lg-6">
          
             <!-- easypiechart -->
                 <section class="panel">
                 
                  <header class="panel-heading bg-white">
                     <span><b>Latest 5 Quotation</b></span>
                     <span class="text-muted m-l-small pull-right">
                           <a class="label bg-primary" href="<?php echo $obj_general->link('multi_product_quotation', 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Quotation</a>
                           <a class="label bg-info" href="<?php echo $obj_general->link('multi_product_quotation', '', '',1);?>"><i class="fa fa-list"></i> View All</a>
                     </span>
                  </header>
          
                  <table id="quotation-row" class="table b-t text-small table-hover">
                     <thead>
                        <tr>
                          <th>Quotation No. / Date</th>
                          <th>Customer Name</th>
                          <th>Product</th>                          
                        </tr>
                     </thead>	
                     
                     <tbody>
                     	  <?php $latest_quotations = $obj_dashboard->getLatestQuotation($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
						  if(isset($latest_quotations) && !empty($latest_quotations)){ 
						  		foreach($latest_quotations as $quotation) { 
							
								if($quotation['quotation_status']==0){
									?>
									 <tr  style="background-color:#f2dede" data-href="<?php echo $obj_general->link('multi_product_quotation', '&mod=view&quotation_id='.encode($quotation['multi_product_quotation_id']), '',1);?>">
									<?php
								}else{
									?>
									 <tr data-href="<?php echo $obj_general->link('multi_product_quotation', '&mod=view&quotation_id='.encode($quotation['multi_product_quotation_id']), '',1);?>" <?php echo ($quotation['status']==0) ? 'style="background-color:#fcf8e3" ' : '' ; ?>> 
									<?php
								}
								?>
                                    <td><?php echo $quotation['multi_quotation_number']; ?><br/>
                                        <small class="text-muted"><?php echo dateFormat(4,$quotation['date_added']);?></small>
                                    </td>
                                    
                                    <td><?php echo $quotation['customer_name']; ?><br/>
                                        <small class="text-muted"><?php echo $quotation['country_name']; ?></small>
                                    </td>
                                    <td>
                                        <a href="<?php echo $obj_general->link('multi_product_quotation', '&mod=view&quotation_id='.encode($quotation['multi_product_quotation_id']), '',1);?>"><?php echo $quotation['product_name'];?></a><br />
                                    <small class="text-muted"><?php echo $quotation['layer'].' Layer';?></small><br />
                                    
                                    </td>
                                </tr>    
                                <?php
                                }
						  }else{
							  echo "<tr> No record found! </tr>";
						  }
						  	?>
                            
                     </tbody>   
                  </table>
			</section>
          </div>
          
          <?php }  ?>
          		   <?php if($obj_general->hasPermission('view',$stock_id ))
			{
		  ?>
         <div class="col-lg-6"> 
                <section class="panel">
                  <header class="panel-heading bg-white">  
                    <span><b>Latest 5 New Stock Order</b></span>
                    <span class="text-muted m-l-small pull-left">
                    	<?php 
						$checkNewCartPermission = $obj_template->checkNewCartPermission($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
						$orderLimit = $obj_template->orderLimit($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
						$permission = '';
						for($i=1;$i<$orderLimit;$i++)
						{
							if($checkNewCartPermission[0]['order_s_no'] == $i)
							{
								$permission =$i+1;
							}		
						}
						if($checkNewCartPermission[0]['order_s_no'] == '')
						{
							$permission =1;
						}
						
						if($obj_general->hasPermission('add','75')){ 
							if($obj_session->data['LOGIN_USER_TYPE'] != 1){
								?>
                        <a class="label bg-primary" href="<?php echo $obj_general->link('template_order_test', 'mod=add&s_no='.encode($permission).'&status=0', '',1);?>"><i class="fa fa-plus"></i> New Stock Order</a>
                        <?php } }?>
                        <a class="label bg-info" href="<?php echo $obj_general->link('template_order_test', '&mod=cartlist_view&status=0', '',1);?>"><i class="fa fa-list"></i> View All</a>
                    </span>                
                  </header>
                  
                  <table id="stock-row" class="table b-t text-small table-hover">
                      <thead>
                         <tr>
                           <th>Sr No.</th>
                           <th>Date</th>
                           <th>Stock No.</th>
                           <th>Client</th>
                           <th>Qty</th>
                           <th>Amount</th>
                           <th>Posted By</th>                          
                         </tr>
                      </thead>
                     
                      <tbody>
                        <?php 
						
						$new_stock = $obj_dashboard->getLatestNewStock();
						//printr($new_stock);
                        if($new_stock) {
						$i=1;
						foreach($new_stock as $stock) {
                        ?>
                       <?php /*?> <tr data-href="<?php echo $obj_general->link('enquiry', '&mod=view&enquiry_id='.encode($enquiry['enquiry_id']), '',1);?>"><?php */?>
                        <tr data-href="<?php echo $obj_general->link('template_order_test', '&mod=index&client_id='.encode($stock['client_id']).'&stock_order_id='.encode($stock['stock_order_id']), '',1);?>">
                          <td><?php echo $i;?></td>
                          <td><?php echo dateFormat(4,$stock['date_added']);?></td>
                          <td><?php echo $stock['gen_order_id'];?></td>
                          <td><?php echo $stock['client_name']; ?></td>
                          <td><?php echo $stock['total_qty'];?></td>
                          <td><?php echo $stock['total_price'].' '.$stock['currency_code']; ?></td>
                          <td><?php echo $stock['user_name'];?></td>
                        </tr>
                        <?php $i++; } ?>
                        <?php 
						  }else{
							  echo "<tr> No record found! </tr>";
						  }?>
                     </tbody>
                  </table>             
              </section>
		   </div>
           
           <?php } ?>
          
        </div>
       
          
        
        <div class="row"> 
        <?php 
          if($_SESSION['LOGIN_USER_TYPE']!='1' && $_SESSION['ADMIN_LOGIN_SWISS']!='1')
		  {
		  	if($obj_general->hasPermission('view',$enquiry_id ))
			{
		  ?>
          
          <!-- Enquiry -->
            <div class="col-lg-6"> 
                <section class="panel">
                  <header class="panel-heading bg-white">  
                    <span><b>Latest 5 Enquiry</b></span>
                    <span class="text-muted m-l-small pull-right">
                        <a class="label bg-primary" href="<?php echo $obj_general->link('enquiry', 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New enquiry</a>
                        <a class="label bg-info" href="<?php echo $obj_general->link('enquiry', '', '',1);?>"><i class="fa fa-list"></i> View All</a>
                    </span>                
                  </header>
                  
                  
                  <table id="enquiry-row" class="table b-t text-small table-hover">
                      <thead>
                         <tr>
                           <th>Enquiry No.</th>
                           <th>Customer Name</th>
                           <th>Email/Phone</th>
                           <th>Created By</th>                          
                         </tr>
                      </thead>
                     
                      <tbody>
                        <?php 
                        $enquiries = $obj_dashboard->getLatestEnquiries();
						if($enquiries) {
						foreach($enquiries as $enquiry) {
                        ?>
                        <tr data-href="<?php echo $obj_general->link('enquiry', '&mod=view&enquiry_id='.encode($enquiry['enquiry_id']), '',1);?>">
                          <td><?php echo $enquiry['enquiry_number']; ?></td>
                          <td><?php echo $enquiry['name']; ?></td>
                          <td>
                            <?php echo $enquiry['email']; ?><br/>
                            <small class="text-muted"><?php echo $enquiry['mobile_number']; ?></small>
                          </td>
                          <td><?php echo $enquiry['user_name']; ?></td>
                        </tr>
                        <?php } ?>
                        <?php
						  }else{
							  echo "<tr> No record found! </tr>";
						  }?>
                     </tbody>
                  </table>             
              </section>
		   </div> 
           <?php } 
           /*}
            if($_SESSION['LOGIN_USER_TYPE']!='1' && $_SESSION['ADMIN_LOGIN_SWISS']!='1')
		  {*/
		   if($obj_general->hasPermission('view',$enquiry_id ))
			{
		    ?>
          <!-- Followup -->
           <div class="col-lg-6 pull-right"> 
            <section class="panel">
              <header class="panel-heading"> 
              	<span><b>Upcoming Followup's</b></span> 
              </header>
              
              <table id="follows-row" class="table b-t text-small table-hover">
                 <thead>
                    <tr>
                      <th>Enquiry No.</th>
                      <th>Customer Name</th>
                      <th>Followup Date</th>
                      <th>Created By</th>                          
                    </tr>
                 </thead>
                 
                 <tbody>
                    <?php 
                    $follow_ups = $obj_dashboard->getUpcomingFollowup();
                    if($follow_ups){
					foreach($follow_ups as $follow_up) {
                    ?>
                    <tr data-href="<?php echo $obj_general->link('enquiry', '&mod=view&enquiry_id='.encode($follow_up['enquiry_id']), '',1);?>">
                      <td><?php echo $follow_up['enquiry_number']; ?></td>
                      <td><?php echo $follow_up['name']; ?></td>
                      <td><?php echo date("d-M-y",strtotime($follow_up['followup_date'])); ?></td>     
                      <td><?php echo $follow_up['user_name']; ?></td>
                    </tr>
                    <?php } ?>
					<?php 
						  }else{
							  echo "<tr> No record found! </tr>";
						  }?>
                 </tbody>
               </table>
            </section>
         </div>
         <?php }?>
         </div>
            
        <div class="row">
        
          <!--Proforma -->
         <?php  if($obj_general->hasPermission('view',$proforma_id ))
    			{
    		    ?>
               
                 <div class="col-lg-6">
              
                 <!-- easypiechart -->
                     <section class="panel">
                     
                      <header class="panel-heading bg-white">
                         <span><b>Latest 5 Proforma Invoice</b></span>
                         <span class="text-muted m-l-small pull-right">
                        	<?php 
    						$proforma = $obj_dashboard->getLatestProforma();?>
                            <a class="label bg-primary" href="https://swissonline.in/admin/index.php?route=proforma_invoice_product_code_wise&mod=add&is_delete=0"><i class="fa fa-plus"></i> New Proforma</a>
                            <a class="label bg-info" href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', '&mod=index&is_delete=0', '',1);?>"><i class="fa fa-list"></i> View All</a>
                        </span>                
                      </header>
                      
                      <table class="table b-t text-small table-hover">
                          <thead>
                             <tr>
                               <th>Sr No.</th>
                               <th>Proforma Invoice Number <br><small class="text-muted">Generated Date</small></th>
                               <th>Buyer Order number</th>
                               <th>Customer Name<br><small class="text-muted">Email</small></th>
                               <th>Action</th>
                               <th>Posted By</th>                          
                             </tr>
                          </thead>
                         
                          <tbody>
                            <?php 
    						
    						if($proforma) 
        					{
        						$i=1;
        						foreach($proforma as $pro) 
        						{   $proforma_user = $obj_pro_invoice->getUser($pro['added_by_user_id'],$pro['added_by_user_type_id']);
        						    $check_sales_qty = $obj_pro_invoice->checkSalesQty($pro['proforma_id'], $pro['added_by_user_type_id'], $pro['added_by_user_id'], $pro['pro_in_no'],$proforma_user['user_id']);
        						    $from = strtotime($pro['invoice_date']);
									$today = strtotime(date('Y-m-d'));
									$difference = $today - $from;
								    $diff = floor($difference / 86400); 
        						    ?>
                                    <tr>
                                      <td><a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', '&mod=view&proforma_id='.encode($pro['proforma_id']).'&is_delete=0', '',1);?>"><?php echo $i;?></a></td>
                                      <td><a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', '&mod=view&proforma_id='.encode($pro['proforma_id']).'&is_delete=0', '',1);?>"><?php echo $pro['pro_in_no'];?><br><small class="text-muted"><?php echo dateFormat(4,$pro['date_added']);?></small></a></td>
                                      <td><a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', '&mod=view&proforma_id='.encode($pro['proforma_id']).'&is_delete=0', '',1);?>"><?php echo $pro['buyers_order_no'];?></a></td>
                                      <td><a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', '&mod=view&proforma_id='.encode($pro['proforma_id']).'&is_delete=0', '',1);?>"><?php echo $pro['customer_name']; ?><br><small class="text-muted"><?php echo dateFormat(4,$pro['email']);?></small></a></td>
                                      <td>
                                          <?php if (empty($check_sales_qty) && $pro['sales_status']==0) {
                                                    if($diff>='30')
														$clr='style="background-color:#EAA7A7"';
													else
														$clr='style="background-color:#81C267"';
														?>
                                                    <a class="btn btn-sm" <?php echo $clr;?>  onclick="generate_sales_inv(<?php echo $pro['proforma_id']; ?>)" >Generate Invoice</a>
                                          <?php } 
                                                else if($pro['gen_sales_status']=='1')
												{?>
												    <a class="btn btn-warning btn-sm"  onclick="clone_proforma(<?php echo $pro['proforma_id']; ?>)" >Generate Duplicate</a>
										  <?php }
										        else
										        {
										            if($pro['customer_dispatch']!='1')  { ?>
															<a class="btn btn-primary btn-sm" onclick="check_stock_qty(<?php echo $pro['proforma_id']; ?>, '<?php echo $pro['pro_in_no']; ?>',<?php echo $pro['added_by_user_type_id']; ?>,<?php echo $pro['added_by_user_id']; ?>,<?php echo $proforma_user['user_id'];?>)">Check Stock</a>
													 <?php }else{?> 
													        <a class="btn btn-sm" <?php echo $clr;?>  onclick="generate_sales_inv(<?php echo $pro['proforma_id']; ?>)" >Generate Invoice</a>
													<?php }
												} ?>
                                      </td>
                                      <td><a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', '&mod=view&proforma_id='.encode($pro['proforma_id']).'&is_delete=0', '',1);?>"><?php echo $pro['user_name'];?></a></td>
                                    </tr>
                                    <?php $i++;
        						}
    						  }else{
							    echo "<tr> No record found! </tr>";
    						  }?>
                         </tbody>
                      </table>             
                  </section>
               </div>
              <?php } if($obj_general->hasPermission('view',$sales_id ))
    			{ ?>
                      <!--Sales Invoice-->
                      <div id="stock-row" class="col-lg-6" pull-right> 
                            <section class="panel">
                              <header class="panel-heading bg-white">  
                                <span><b>Latest 5 Sales Invoice</b></span>
                                <span class="text-muted m-l-small pull-right">
                                    <a class="label bg-info" href="<?php echo $obj_general->link('sales_invoice', '&mod=index&is_delete=0', '',1);?>"><i class="fa fa-list"></i> View All</a>
                                </span>                
                              </header>
                             
                              <table class="table b-t text-small table-hover">
                                  <thead>
                                     <tr>
                                       <th>Sr No.</th>
                                       <th>Sales Invoice No. <br><small class="text-muted">Generated Date</small></th>
                                       <th>Buyer Order number<br><small class="text-muted">Proforma Invoice Number</small></th>
                                       <th>Customer Name<br><small class="text-muted">Email</small></th>
                                       <th>Posted By</th>                          
                                     </tr>
                                  </thead>
                                 
                                  <tbody>
                                    <?php $sales =$obj_dashboard->getLatestSalesInvoice();
            								if($sales) {
                								$i=1;
                								foreach($sales as $sale) {	?>
                        								<tr data-href="<?php echo $obj_general->link('sales_invoice', '&mod=view&invoice_no='.encode($sale['invoice_id']).'&status=1&is_delete=0', '',1);?>">
                        								  <td><?php echo $i;?></td>
                        								  <td><?php echo $sale['invoice_no'];?><br><small class="text-muted"><?php echo dateFormat(4,$sale['date_added']);?></small></td>
                        								  <td><?php echo $sale['buyers_orderno'];?><br><small class="text-muted"><?php echo $sale['proforma_no'];?></small></td>
                        								  <td><?php echo $sale['customer_name']; ?><br><small class="text-muted"><?php echo dateFormat(4,$sale['email']);?></small></td>
                        								  <td><?php echo $sale['user_name'];?></td>
                        								</tr>
                        					    <?php $i++; } 
            									  }else{
            										  echo "<tr> No record found! </tr>";
            									  }?>
                                 </tbody>
                              </table>             
                          </section>
            		   </div> 
            <?php } //} ?>
          </div>
        
        
        
        
        <div class="row">
        
          <!--New Stock -->
         
           <?php if($_SESSION['ADMIN_LOGIN_SWISS']!='144' && $_SESSION['LOGIN_USER_TYPE']!='2')
           {
		   if($obj_general->hasPermission('view',$stock_id ))
			{
		    ?>
           
             <div class="col-lg-6">
          
             <!-- easypiechart -->
                 <section class="panel">
                 
                  <header class="panel-heading bg-white">
                     <span><b>Latest 5 Dispatched Order</b></span>
                     <span class="text-muted m-l-small pull-right">
                    	<?php 
						$checkNewCartPermission = $obj_template->checkNewCartPermission($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
						$orderLimit = $obj_template->orderLimit($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
						$permission = '';
						for($i=1;$i<$orderLimit;$i++)
						{
							if($checkNewCartPermission[0]['order_s_no'] == $i)
							{
								$permission =$i+1;
							}		
						}
						if($checkNewCartPermission[0]['order_s_no'] == '')
						{
							$permission =1;
						}
						
						
						
						
						//http://192.168.1.250/erp/swisspac/admin/index.php?route=template_order&mod=cartlist_view&status=3?>
                        <a class="label bg-info" href="<?php echo $obj_general->link('template_order_test', '&mod=cartlist_view&status=3', '',1);?>"><i class="fa fa-list"></i> View All</a>
                    </span>                
                  </header>
                  
                  
                  <table id="stock-row" class="table b-t text-small table-hover">
                      <thead>
                         <tr>
                           <th>Sr No.</th>
                           <th>Date</th>
                           <th>Stock No.</th>
                           <th>Client</th>
                           <th>Qty</th>
                           <th>Amount</th>
                           <th>Posted By</th>                          
                         </tr>
                      </thead>
                     
                      <tbody>
                        <?php 
						
						$cond= ' AND ((t.template_order_id=sodh.template_order_id ANd t.product_template_order_id=sodh.product_template_order_id AND sodh.status=0))';
						$table=', stock_order_dispatch_history_test as sodh';
						
						$select = ' sum(sodh.dis_qty) as new_qty,sum(sodh.dis_qty*t.price) as dis_total_price,';
						
						$new_stock = $obj_dashboard->getLatestNewStock($cond,$table,$select);
						if($new_stock) {
						$i=1;
						foreach($new_stock as $stock) {
                        ?>
                       <?php /*?> <tr data-href="<?php echo $obj_general->link('enquiry', '&mod=view&enquiry_id='.encode($enquiry['enquiry_id']), '',1);?>"><?php */?>
                        <tr data-href="<?php echo $obj_general->link('template_order_test', '&mod=dispatch&client_id='.encode($stock['client_id']).'&stock_order_id='.encode($stock['stock_order_id']), '',1);?>">
                          <td><?php echo $i;?></td>
                          <td><?php echo dateFormat(4,$stock['date_added']);?></td>
                          <td><?php echo $stock['gen_order_id'];?></td>
                          <td><?php echo $stock['client_name']; ?></td>
                          <td><?php echo $stock['new_qty'];?></td>
                          <td><?php echo $stock['dis_total_price'].' '.$stock['currency_code']; ?></td>
                          <td><?php echo $stock['user_name'];?></td>
                        </tr>
                        <?php $i++; } ?>
                        <?php 
						  }else{
							  echo "<tr> No record found! </tr>";
						  }?>
                     </tbody>
                  </table>             
              </section>
           </div>
          
        
         
           <!--Delay Dispatch-->
          <div class="col-lg-6" pull-right> 
                <section class="panel">
                  <header class="panel-heading bg-white">  
                    <span><b>Latest 5 Delay Stock Order</b></span>
                    <span class="text-muted m-l-small pull-right">
                        <a class="label bg-info" href="<?php echo $obj_general->link('template_order_test', '&mod=cartlist_view&status=1', '',1);?>"><i class="fa fa-list"></i> View All</a>
                    </span>                
                  </header>
                  
                  
                  <table id="delay-row" class="table b-t text-small table-hover">
                      <thead>
                         <tr>
                           <th>Sr No.</th>
                           <th>Date</th>
                           <th>Days Left</th>
						   <th>Stock No.</th>
                           <th>Client</th>
                           <th>Qty</th>
                           <th>Amount</th>
                           <th>Edited By</th>                          
                         </tr>
                      </thead>
                     
                      <tbody>
                        <?php $delay_history =$obj_dashboard->getUpdatedDelayDateHistory();
								if($delay_history) {
								$i=1;
								foreach($delay_history as $history) {
									//printr($history);
									$today_date = date("Y-m-d");
									$days_left =  strtotime($history['new_final_ddate']) - strtotime($today_date);
									$day_left = floor($days_left/(60*60*24));
									if($history['s'] == '1')
									{
										$mod = 'in_process';
									}
									else
									{
										$mod ='dispatch';
									}
								?>
								<tr data-href="<?php echo $obj_general->link('template_order_test', '&mod='.$mod.'&client_id='.encode($history['client_id']).'&stock_order_id='.encode($history['stock_order_id']), '',1);?>">
								  <td><?php echo $i;?></td>
								  <td><?php echo dateFormat(4,$history['new_final_ddate']);?></td>
								  <td><?php echo $day_left;?></td>
								  <td><?php echo $history['gen_order_id'];?></td>
								  <td><?php echo $history['client_name']; ?></td>
								  <td><?php echo $history['quantity'];?></td>
								  <td><?php echo $history['total_price'].' '.$history['currency_code']; ?></td>
								  <td><?php echo $history['user_name'];?></td>
								</tr>
								<?php $i++; } ?>
								<?php 
									  }else{
										  echo "<tr> No record found! </tr>";
									  }?>
                     </tbody>
                  </table>             
              </section>
		   </div> 
             <?php } }?>
          </div>
           
 			         
             <div class="row">
             <?php if($true=='1') { ?> 
            <?php if($obj_session->data['LOGIN_USER_TYPE']!=1 && $obj_session->data['ADMIN_LOGIN_SWISS']!=1)
				 { ?>
  		         <div class="col-lg-6">
          
             <section class="panel">
                 
                  <header class="panel-heading bg-white">
                     <span><b>Latest 5 Upcoming Tax Event</b></span>
                     <span class="text-muted m-l-small pull-right">
                    	<a class="label bg-info" href="<?php echo $obj_general->link($table_name, '', '',1);?>"><i class="fa fa-list"></i> View All</a>
                    </span>                
                  </header>
                  
                  
                  <table id="stock-row" class="table b-t text-small table-hover">
                      <thead>
                         <tr>
                           <th>Sr No.</th>
                           <th>Description</th>
                           <th>Reminder Date</th>
                           <th>Last Reminder Date</th>
                         </tr>
                      </thead>
                     
                      <tbody>
                        <?php 
					//echo $table_name;
						if($max_data) {
						$i=1;
						foreach($max_data as $data) {
						//printr($data[$order_id]);
                        ?>
                        <tr data-href="<?php echo $obj_general->link($table_name, '&mod=add&'.$order_id.'='.encode($data[$order_id]), '',1);?>">
                        <?php /*?><tr data-href="<?php echo $obj_general->link('template_order', '&mod=dispatch&client_id='.encode($stock['client_id']).'&stock_order_id='.encode($stock['stock_order_id']), '',1);?>"><?php */?>
                          <td><?php echo $i;?></td>
                          <td><?php echo $data['description'];?></td>
                          <td><?php echo dateFormat(4,$data['remainder_date']);?></td>
                          <td><?php echo dateFormat(4,$data['last_remainder_date']); ?></td>
                        </tr>
                        <?php $i++; } ?>
                        <?php 
						  }else{
							  echo "<tr> No record found! </tr>";
						  }?>
                     </tbody>
                  </table>             
              </section>
        </div>
        	<?php } ?>
        	<?php } ?>
        
        <?php  if($val_true=='1') { ?> 
        <?php if($obj_session->data['LOGIN_USER_TYPE']!=1 && $obj_session->data['ADMIN_LOGIN_SWISS']!=1) { ?>
           <div class="col-lg-6">
          
             <section class="panel">
                 
                  <header class="panel-heading bg-white">
                     <span><b>Latest 5 Goods-in-transit</b></span>
                     <span class="text-muted m-l-small pull-right">
                    	<a class="label bg-info" href="<?php echo $obj_general->link($tablename, 'mod=index&inv_status=2', '',1);?>"><i class="fa fa-list"></i> View All</a>
                    </span>                
                  </header>
                  
                  
                  <table id="goods-row" class="table b-t text-small table-hover">
                      <thead>
                         <tr>
                           <th>Sr No.</th>
                           <th>Invoice No</th>
                           <th>Customer Name</th>
                           <th>Email</th>
                           <th>Order Type</th>
                           <th>Transportation</th>
                           <th></th>
                         </tr>
                      </thead>
                     
                      <tbody>
                        <?php //printr($invocie);
						if($goods_data) {
						$i=1;
						foreach($goods_data as $invoice){ 
						//printr($data[$order_id]);
                        ?>
                        <tr>
                        <?php /*?><tr data-href="<?php echo $obj_general->link('template_order', '&mod=dispatch&client_id='.encode($stock['client_id']).'&stock_order_id='.encode($stock['stock_order_id']), '',1);?>"><?php */?>
                   <td  data-href="<?php echo $obj_general->link($tablename, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status=2','',1); ?>"><?php echo $i;?></td>
                          <td  data-href="<?php echo $obj_general->link($tablename, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status=2','',1); ?>"><?php echo $invoice['invoice_no'];?></td>
                          <td  data-href="<?php echo $obj_general->link($tablename, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status=2','',1); ?>"><?php echo "Swiss Pac Pvt Ltd." ?></td>
                          <td  data-href="<?php echo $obj_general->link($tablename, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status=2','',1); ?>"><?php echo $invoice['email']; ?></td>
                          <td  data-href="<?php echo $obj_general->link($tablename, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status=2','',1); ?>"><?php echo ucwords($invoice['order_type']);?></td>
                          <td  data-href="<?php echo $obj_general->link($tablename, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status=2','',1); ?>"><?php echo ucwords(decode($invoice['transportation']));?></td>
                          <?php if(decode($invoice['transportation'])=='sea' && $invoice['import_status']=='0')
						  {
						 ?>
                          <td><a onclick="gen_credit_note(<?php echo $invoice['invoice_id'];?>,'<?php echo $invoice['invoice_no'];?>',<?php echo $invoice['invoice_total_amount'];?>,<?php echo $invoice['tran_charges'];?>,'<?php echo $invoice['final_destination'];?>','<?php echo $invoice['email'];?>')"  name="btn_edit" class="btn btn-info btn-xs">Import Charges</a></td>
                         <?php } 
						 if($invoice['import_status']=='1')
						 {?>
                        	 <td><a onclick="convert_invoice(<?php echo $invoice['invoice_id'];?>,'<?php echo $invoice['invoice_no'];?>')"  name="btn_edit_one" class="btn btn-info btn-xs">Convert To Purchase</a></td>
                         <?php } ?>
                        </tr>
                        <?php $i++; } ?>
                        <?php 
						  }else{
							  echo "<tr> No record found! </tr>";
						  }?>
                     </tbody>
                  </table>             
              </section>
        </div> 
        <?php } ?>
        <?php } ?>
        </div>
  
	<div class="row">
	    	<?php 
		   if($obj_general->hasPermission('view',$job_master_id ))
			{
		    ?>
			<div class="col-lg-6">
          
             <section class="panel">
                 <header class="panel-heading bg bg-inverse"> 
                     <span><b>Latest 5  Job Cart List </b></span>
                     <span class="text-muted m-l-small pull-right">
                    	<a class="label bg-info" href="<?php echo $obj_general->link('job_master', '', '',1);?>"><i class="fa fa-list"></i> View All</a>
                    </span>                
                  </header>
                  
                  
                  <table id="stock-row" class="table b-t text-small table-hover">
                      <thead>
                         <tr>
                           <th>Job Number</th>
                           <th>Job Date</th>
                           <th>Job Name</th>
                           <th>Job Type</th>
                           <th>Layer</th>
                           <th>Product Name</th>
                           <th>Action</th>
                         </tr>
                      </thead>
                     
                      <tbody>
                        <?php 
					$job_data = $obj_dashboard->getLatestJobMasterdata();
				//	printr($job_data);
						if($job_data) {
						foreach($job_data as $data) {
                        ?>
                        <tr data-href="<?php echo $obj_general->link('job_master', '&mod=view&job_id='.encode($data['job_id']), '',1);?>">
                          <td>Job No-<?php echo $data['job_no'];?></td>
                          <td><?php echo dateFormat(4,$data['job_date']);?></td>
                          <td><?php echo $data['job_name'];?></td>
                          <td><?php echo $data['pouch_type'];?></td>
                          <td><?php echo $data['layers'];?></td>
                          <td><?php echo $data['product_name'];?></td>
                          <td><?php if($data['status'] == 0) echo 'Rejected';?></td>
                        </tr>
                        <?php } ?>
                        <?php 
						  }else{
							  echo "<tr> No record found! </tr>";
						  }?>
                     </tbody>
                  </table>             
              </section>
        </div><?php }?>
        	<?php 
		   if($obj_general->hasPermission('view',$printing_job_id ))
			{
		    ?>
            <div class="col-lg-6">
          
             <section class="panel">
                 
                <header class="panel-heading bg bg-inverse"> 
                     <span><b>Latest 5 Printing Job List </b></span>
                     <span class="text-muted m-l-small pull-right">
                    	<a class="label bg-info" href="<?php echo $obj_general->link('printing_process', '', '',1);?>"><i class="fa fa-list"></i> View All</a>
                    </span>                
                  </header>
                  
                   
                  <table id="stock-row" class="table b-t text-small table-hover">
                      <thead>
                         <tr>
                            <th>Printing Job Number</th>
                           <th>Job Number</th>
                           <th>Job Date</th>
                           <th>Job Name</th>
                           <th>Job Type</th>
                           <th>Roll Code</th>
                           <th>Action</th>
                         </tr>
                      </thead>
                     
                      <tbody>
                        <?php 
					$job_data = $obj_dashboard->getLatestJobdata();
				//	printr($job_data);
						if($job_data) {
						foreach($job_data as $data) { 
                        ?>
                        <tr data-href="<?php echo $obj_general->link('printing_process', '&mod=view&printing_id='.encode($data['job_id']), '',1);?>">
                          <td>Printing No-<?php echo $data['job_no'];?></td>
                          <td><?php echo $data['job_name_text'];?></td>
                          <td><?php echo dateFormat(4,$data['job_date']);?></td>
                          <td><?php echo $data['job_name'];?></td>
                          <td><?php echo $data['job_type'];?></td>
                          <td><?php echo $data['roll_code'];?></td>
                          <td><?php if($data['status'] == 0) echo 'Rejected';?></td>
                        </tr>
                        <?php } ?>
                        <?php 
						  }else{
							  echo "<tr> No record found! </tr>";
						  }?>
                     </tbody>
                  </table>             
              </section>
        </div>
            <?php } }?>
			
		</div>																
																						
	
             <div class="row">
		 	<?php 
		   if($obj_general->hasPermission('view',$lamination ))
    			{
    		    ?>
                <div class="col-lg-6">
              
                 <section class="panel">
                     
                      <header class="panel-heading bg bg-inverse"> 
                         <span><b>Latest 5 Lamination List</b></span>
                         <span class="text-muted m-l-small pull-right">
                        	<a class="label bg-info" href="<?php echo $obj_general->link('lamination_process', '', '',1);?>"><i class="fa fa-list"></i> View All</a>
                        </span>                
                      </header>
                      
                      
                      <table id="stock-row" class="table b-t text-small table-hover">
                          <thead>
                             <tr>
                            <th>Lamination Number</th> 
                               <th>Job Number</th>
                               <th>Job Date</th>
                               <th>Job Name</th>
                               <th>Roll Code</th>
                               <th>Status</th>
                             </tr>
                          </thead>
                         
                          <tbody>
                            <?php 
    					$job_data = $obj_dashboard->getLatestLaminationdata();
    						if($job_data) {
    						foreach($job_data as $data) {
    							//printr($data);
    								$layer = $obj_dashboard->getLatestLaminationLayerData($data['lamination_id']);
    						//	printr($layer);
                            ?>
                            <tr data-href="<?php echo $obj_general->link('lamination_process', '&mod=view&lamination_id='.encode($data['lamination_id']), '',1);?>">
                              <td>Lamination No-<?php echo $data['lamination_no'];?></td>
                              <td><?php echo $data['job_no'];?></td>
                              <td><?php echo dateFormat(4,$layer[1]['layer_date']);?></td>
                              <td><?php echo $data['job_name'];?></td>
                              <td><?php echo $data['roll_code'];?></td>
                               <td><?php if($data['status'] == 0) echo 'Rejected'; else echo 'Active';?></td>
                            </tr>
                            <?php } ?>
                            <?php 
    						  }else{
    							  echo "<tr> No record found! </tr>";
    						  }?>
                         </tbody>
                      </table>             
                  </section>
            </div>
                <?php } ?>
		
        
        	<?php 
		   if($obj_general->hasPermission('view',$slitting ))
			{
		    ?>
            <div class="col-lg-6">
          
             <section class="panel">
                 
                 <header class="panel-heading bg bg-inverse"> 
                     <span><b>Latest 5 slitting List</b></span>
                     <span class="text-muted m-l-small pull-right">
                    	<a class="label bg-info" href="<?php echo $obj_general->link('slitting_process', '', '',1);?>"><i class="fa fa-list"></i> View All</a>
                    </span>                
                  </header>
                  
                  <?php $job_data = $obj_dashboard->getLatestSlittingdata();?>
                  <table id="stock-row" class="table b-t text-small table-hover">
                      <thead>
                         <tr>
							<th>Slitting Number</th>
							<th>Slitting Date</th>            
							<th>Roll no/ Roll Code  Details <br> Size </th>    
							<th>Operator Name</th>
							<th>Machine Name</th>
                         </tr>
                      </thead>
                     
                      <tbody>
                        <?php 
					
					
					
						if($job_data) {
						foreach($job_data as $slit) {
							if($slit['slitting_status']==0){
								$printing_details = $obj_slitting->getPrintingDetails($slit['roll_code_id']);
								$roll_code=$printing_details['roll_code'];
								$roll_size=$printing_details['roll_size'];
								$label='Printing Roll';
							}else if($slit['slitting_status']==1){
								$lamination_details = $obj_slitting->getLamination_details($slit['roll_code_id']);
								$roll_code=$lamination_details['roll_code'];
								$roll_size=$lamination_details['roll_size'];
								$label='Lamination Roll';
							}else{
								$roll_details = $obj_slitting->getRoll_details($slit['roll_code_id']);
								$roll_code= $roll_details['roll_no'];
								$roll_size= $roll_details['inward_size'];
								$label='Inward Roll';
							}
                        ?>
                        <tr data-href="<?php echo $obj_general->link('slitting_process', '&mod=view&job_id='.encode($slit['slitting_id']), '',1);?>">
                         
                      
                          <td>NO - <?php echo $slit['slitting_no'];  ?></td> 
						  <td><?php echo dateFormat(4,$slit['slitting_date']);  ?></td>
						  <td> <b> <?php echo $label;?></b> <br><span style="color:#f92c09" ><?php echo $roll_code;  ?></span>	</td>
                          <td><?php echo $slit['operator_name'];  ?></td>
                           <td><?php echo $slit['machine_name'];  ?></td>                       
                         
                        </tr>
                        <?php } ?>
                        <?php 
						  }else{
							  echo "<tr> No record found! </tr>";
						  }?>
                     </tbody>
                  </table>             
              </section>
        </div>
            <?php } ?>
			
		 	<?php 
		   if($obj_general->hasPermission('view',$pouching ))
			{
		    ?>
            <div class="col-lg-6">
          
             <section class="panel">
                 
                 <header class="panel-heading bg bg-inverse"> 
                     <span><b>Latest 5 Pouching List</b></span>
                     <span class="text-muted m-l-small pull-right">
                    	<a class="label bg-info" href="<?php echo $obj_general->link('pouching_process', '', '',1);?>"><i class="fa fa-list"></i> View All</a>
                    </span>                
                  </header>
                  
                  
                  <table id="stock-row" class="table b-t text-small table-hover">
                      <thead>
                         <tr>
						 <th >Pouching No</th>
						 <th >Job No</th>
						 <th >pouching Date</th> 
						 <th >Job Name</th> 
                         <th >Operator Name</th>
                          <th>Machine Name</th>
                          <th></th>
                         </tr>
                      </thead>
                     
                      <tbody>
                        <?php 
					$job_data = $obj_dashboard->getLatestPouchingdata();
						if($job_data) {
						foreach($job_data as $data) {
							//printr($data);
                        ?>
                        <tr data-href="<?php echo $obj_general->link('pouching_process', '&mod=view&pouching_id='.encode($data['pouching_id']), '',1);?>">
                          <td> Pouching No :- <?php echo $data['pouching_no'];?></td>
                          <td><?php echo $data['job_no'];?></td>
                          <td><?php echo dateFormat(4,$data['pouching_date']);?></td>
                          <td><?php echo $data['job_name'];?></td>
                          <td><?php echo $data['operator_name']; ?></td>
                          <td><?php echo $data['machine_name']; ?></td>
                          <td><?php if($data['status'] == 1) echo 'Rejected';?></td>
                        </tr>
                        <?php } ?>
                        <?php 
						  }else{
							  echo "<tr> No record found! </tr>";
						  }?>
                     </tbody>
                  </table>             
              </section>
        </div>
            <?php } ?>
		
        </div>
      </section>
    </section>
    
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>   
<!-- .modal -->

<div id="modal" class="modal fade">
  <form class="m-b-none">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
          <h4 class="modal-title" id="myModalLabel">Post your first idea</h4>
        </div>
        <div class="modal-body">
          <div class="block">
            <label class="control-label">Title</label>
            <input type="text" class="form-control" placeholder="Post title">
          </div>
          <div class="block">
            <label class="control-label">Content</label>
            <textarea class="form-control" placeholder="Content" rows="5"></textarea>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox">
              Share with all memebers of first </label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Save</button>
          <button type="button" class="btn btn-sm btn-primary" data-loading-text="Publishing...">Publish</button>
        </div>
      </div>
      <!-- /.modal-content --> </div>
  </form>
</div>

<!--import Model -->
<div class="modal fade" id="gen_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:55%;">
    <div class="modal-content">
    
    	<form class="form-horizontal" method="post" name="import_charges" id="import_charges" style="margin-bottom:0px;">
              <div class="modal-header">
                   	<h4 class="dispatch" id="myModalLabel">IMPORT CHARGES FOR INVOICE NO : <span id="span_inv_no"></span></h4>
                  	 <input type="hidden" name="gen_invoice_id" id="gen_invoice_id" value=""  />
                     <input type="hidden" name="gen_country_id" id="gen_country_id" value=""  />
                     <input type="hidden" name="gen_email_address" id="gen_email_address" value=""  />
                     <input type="hidden" name="admin_email" id="admin_email" value="<?php echo ADMIN_EMAIL;?>" />
                    <input type="hidden" name="gen_inv_number" id="gen_inv_number" value=""  /> 
              </div>
              <!--sonu 6/12/2016-->
              <div class="panel-body">
                <label class="col-lg-3 control-label">Agent Name</label>
                <div class="col-lg-3">
                    <input type="text" name="agentname" value="" id="agentname" class="form-control"/>
           	 	</div>
              </div>
               <div class="panel-body">
                    <label class="col-lg-3 control-label">Agent Adress</label>
                    <div class="col-lg-3">
                        <input type="text" name="agentaddress" value="" id="agentaddress" class="form-control" />
                    </div>
              </div>
               <div class="panel-body">
                    <label class="col-lg-3 control-label">E-Mail Id</label>
                    <div class="col-lg-3">
                        <input type="text" name="mailid" value="" id="mailid" class="form-control" />
                    </div>
               </div>
               <div class="panel-body">
                    <label class="col-lg-3 control-label">ABN No</label>
                    <div class="col-lg-3">
                        <input type="text" name="abnno" value="" id="abnno" class="form-control"/>
                    </div>
              </div>
               <div class="panel-body">
                    <label class="col-lg-3 control-label">CIF Amount</label>
                    <div class="col-lg-3">
                        <input type="text" name="cifamount" value="" id="cifamount" class="form-control" readonly="readonly"/>
                    </div>
              </div>
               <div class="panel-body">
                    <label class="col-lg-3 control-label">FOB Amount</label>
                    <div class="col-lg-3">
                        <input type="text" name="fobamount" value="" id="fobamount" class="form-control" readonly="readonly"/>
                    </div>
              </div>
               <div class="panel-body">
                    <label class="col-lg-3 control-label">Custom Duty</label>
                    <div class="col-lg-3">
                        <input type="text" name="customduty" value="" id="customduty" class="form-control" readonly="readonly"/>
                    </div>
              </div>
               <div class="panel-body" style="display:none;">
                    <label class="col-lg-3 control-label">VOTI</label>
                    <div class="col-lg-3">
                        <input type="text" name="voti" value="" id="voti" class="form-control" readonly="readonly"/>
                    </div>
              </div>
              <div class="panel-body">
                    <label class="col-lg-3 control-label">GST On Import</label>
                    <div class="col-lg-3">
                        <input type="text" name="gst" value="" id="gst" class="form-control" readonly="readonly"/>
                    </div>
              </div>
             <div class="panel-body">
                    <label class="col-lg-3 control-label">Other Charges</label>
                    <div class="col-lg-3">
                        <input type="text" name="othercharges" value="" id="othercharges" class="form-control"/>
                    </div>
              </div>
              <div class="panel-body">
                    <label class="col-lg-3 control-label">Clearing Charges</label>
                    <div class="col-lg-3">
                        <input type="text" name="clearingcharges" value="" id="clearingcharges" class="form-control"/>
                    </div>
              </div>
      		<div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="submit"  name="btn_generate" class="btn btn-warning">Save</button>
              </div>
   		</form>   
    </div>
  </div>
</div>
<!-- End import Model -->

<!--Convert Model [kinjal] on 7-12-2016-->
<div class="modal fade" id="con_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:46%;">
    <div class="modal-content">
   	 <form class="form-horizontal" method="post" name="convert_inv" id="convert_inv" style="margin-bottom:0px;">
              <div class="modal-header">
                   	<h4 class="dispatch" id="myModalLabel">Convert To It <span id="con_inv_no"></span> ?</h4>
                  	 <input type="hidden" name="con_invoice_id" id="con_invoice_id" value=""  />
              </div>
               <div class="panel-body">
                <label class="col-lg-5 control-label">Do you want to Convert it in Purchase Invoice ?</label>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">No</button>
                <button type="submit"  name="btn_convert" class="btn btn-warning">Yes</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<!--End Convert Model -->
<!--Sonu add model 20/12/2016 alert import charge after login -->
   <?php $importchargedetail = $obj_invoice->getimportchargedetail($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
				 //  printr($importchargedetail);
				   if(!empty($importchargedetail))
				  {
					?>	<div id="modal_import" class=" col-lg-10  modal fade" >
                  
						
							  <div class=" modal-dialog" style="width:46%; ">
								 <div class="modal-content">
									<div class="modal-header">
                                       <form class="form-horizontal" method="post" name="import" id="import" style="margin-bottom:0px;">								 
                                          <h4 class="modal-title" id="myModalLabel"> <i class="fa fa-warning" style="color:#FF0000"  ><b> WARNING</b></i></h4>
                                        </div>
                                        <div class=" col-lg-12 modal-body">
                                         <div class="form-group">
                                              <div class="panel-body">
                                       				 <label class="col-lg-12">Please Insert Import Charge</label>
								 			</div>
                                           </div>
									  
									  </div>
							   
								<div class="modal-footer">
								
                                 <button type="button"  id="close"class="btn btn-default btn-sm" data-dismiss="modal" >Close</button>
								</div>
							  </div>   
							 </div>
						  </form>
						</div>
     <?php }?>
  <!--Sonu end
    [kinjal] made on 25-1-2018-->
       <div class="modal fade" id="form_con" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
         	 <div class="modal-content">
            	<form class="form-horizontal" method="post" name="form" id="conform_form" style="margin-bottom:0px;">
                	<div class="modal-header title">
                        <h4 class="modal-title" id="myModalLabel"><span id="pro"></span></h4>
                      </div>
                    <div class="modal-body">
                    	<input name="pro_detail_id" id="pro_detail_id" value=""  type="hidden"/>
                        <h4 class="streamlined_title"> Sure !!! <br /><br />
                        						Do you want to generate Sales Invoice ?</h4>
                    </div> 
                     <div class="modal-footer">
                        <button type="button" name="btn_submit1" class="btn btn-primary" onclick="generate_sales()">Yes</button>
                         <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                      </div>
                </form>
             </div>
            </div>
    </div>
    <div class="modal fade" id="form_con1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width:46%;">
        <div class="modal-content">
       	 <form class="form-horizontal" method="post" name="form" id="ckeck_stock" style="margin-bottom:0px;">
                  <div class="modal-header">
                       	 	<h4 class="dispatch" id="myModalLabel">Stock Details For<span id="pr_no" style=""></span></h4>
                  </div>
                   <div class="modal-body">
                   <input name="stock_detail_id" id="stock_detail_id" value=""  type="hidden"/>             
                        <div class="table-responsive">                      
                           	
            			<table class="table table-striped m-b-none text-small">
            				<thead>
            					<tr>
            					<th>Product Code</th>
            					<th>Proforma Qty</th>
            					<th>Stock Qty</th>
            					</tr>
            				</thead>
                           <tbody id="stock_data">
            
                           </tbody>
                            </table>
                    </div>
                 </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
                   
                  </div>
       		</form>   
        </div>
      </div>
    </div>
<!--end [kinjal]-->
<style>
	#quotation-row tbody tr, #enquiry-row tbody tr,
	#follow-row tbody tr, #stock-row tbody tr ,#delay-row tbody tr{
		cursor: pointer;
	}
</style>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 
<script>
$( document ).ready(function() {
     $(".chosen_data").chosen();
		$("#flot-color").remove();					 
        var warning = <?php echo isset($obj_session->data['show_warning'])?$obj_session->data['show_warning']:'0';?>;
		//alert(warning);
		if(warning != '0')
		{
			$("#modal_import").modal("show");
			<?php unset($_SESSION['show_warning']);?>;
		}
		
		
    // $('[data-toggle="sucess_tooltip"]').tooltip({
    //     placement : 'top'
    // });
	 //$('[data-toggle="sucess_tooltip"]').tooltip({trigger: 'manual'}).tooltip('show');
		
});

$('#quotation-row tbody tr').click(function(){
		window.location = $(this).data('href');
        return false;
 });
 
 $('#enquiry-row tbody tr,#stock-row tbody tr,#delay-row tbody tr,#follow-row tbody tr').click(function(){
	 if($(this).data('href')!=''){
        window.location = $(this).data('href');
        return false;
 	}
 });
function gen_credit_note(invoice_id,invoice_no,invoice_total_amount,tran_charges,country_id,email_id)
{		
		$("#span_inv_no").html(invoice_no);
		$("#gen_invoice_id").val(invoice_id);
		//$("#gen_inv_total_amt").val(invoice_total_amount);
		$("#cifamount").val(invoice_total_amount);
		var fob_amount = invoice_total_amount-tran_charges;
		var custom_duty = ((fob_amount*5)/100);
		var voti = invoice_total_amount + custom_duty;
		var gst_on_import = ((voti*10)/100);
		$("#fobamount").val(fob_amount);
		$("#customduty").val(custom_duty);
		$("#voti").val(voti);
		$("#gst").val(gst_on_import);
		$("#gen_country_id").val(country_id);
		$("#gen_email_address").val(email_id);
		$("#gen_inv_number").val(invoice_no);
		//alert(invoice_id);
		$("#gen_modal").modal("show");
		/*var data_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=creditNote', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {invoice_id : invoice_id},
			success: function(response){
				
				$(".table_data").html(response);
				
			},
			error:function(){
			}	
		});
		$("#gen_modal").modal("show");
		*/
			
}
    function convert_invoice(invoice_id,invoice_no)
    {
    	//console.log(invoice_no);
    	$("#con_inv_no").html(invoice_no);
    	$("#con_invoice_id").val(invoice_id);
    	$("#con_model").modal("show");
    }
    //[kinjal] on 25/1/2019
    function check_stock_qty(proforma_id,pr_no,user_type_id,user_id,admin_user_id)
    {
    	$("#form_con1").modal('show');
    	$("#pr_no").html(pr_no);
    	var stk_url = getUrl("<?php echo $obj_general->ajaxLink($rout_proforma, '&mod=ajax&fun=checkStock', '',1);?>");
    	$.ajax({			
    		url : stk_url,
    		type :'post',
    		data :{proforma_id:proforma_id,pr_no:pr_no,user_type_id:user_type_id,user_id:user_id,admin_user_id:admin_user_id},
    		success: function(response){
    				$('#stock_data').html(response);
    		},
    	});
    }
    function generate_sales_inv(proforma_id)
	{
 		$(".note-error").remove();
		$("#pro_detail_id").val(proforma_id);
		$("#form_con").modal("show");
	}
    function generate_sales()
	{
		$("#form_con").modal("hide");
		var proforma_id = $("#pro_detail_id").val();
		var gen_url = getUrl("<?php echo $obj_general->ajaxLink($rout_proforma, '&mod=ajax&fun=gen_sales', '',1);?>");
		$.ajax({			
			url : gen_url,
			type :'post',
			data :{proforma_id:proforma_id},
			success: function(response){
					window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=sales_invoice&mod=add&invoice_no='+response+'&is_delete=0';
			},
		});
	}  
	function clone_proforma(pro_id)
	{
		var url = getUrl("<?php echo $obj_general->ajaxLink($rout_proforma, '&mod=ajax&fun=clone_proforma', '', 1); ?>");
		$.ajax({			
			url : url,
			type :'post',
			data :{pro_id:pro_id},
			success: function(response){
			    window.location.href='<?php echo HTTP_SERVER;?>/admin/index.php?route=proforma_invoice_product_code_wise&mod=index&is_delete=0';
			},
		});
	}
	function getdomesticStock()
	{
	    	//alert('stock');
		var product_code_id = $("#product_code_id").val();
		
	//	alert(product_code_id);
		var gen_url = getUrl("<?php echo $obj_general->ajaxLink($rout_domestic_stock, '&mod=ajax&fun=getdomesticStock', '',1);?>");
		$.ajax({			
			url : gen_url,
			type :'post',
			data :{product_code_id:product_code_id},
			success: function(response){
			    if(response!='')
				    $('#stock_qty').val(response);
				else
				     $('#stock_qty').val('0');
			},
		});
	}
</script>
<!-- / .modal -->
 