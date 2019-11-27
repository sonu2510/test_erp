<?php
include("mode_setting.php");

//Start : bradcums
$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
if(isset($_GET['status'])){
	$price_status = $_GET['status'];	
}else{
	$price_status= 0;
}
//Start : edit
$edit = '';
if(isset($_GET['template_id']) && !empty($_GET['template_id'])){
	if(!$obj_general->hasPermission('view',$menuId)){
		$display_status = false;
	}else{
		$template_id = base64_decode($_GET['template_id']);
		//echo $template_id;
		//die;
		
		$data = $obj_template->getTempalte($template_id);
//		printr($data);
		//die;
		//$currency_data = $obj_order->getOrderCurrency($order_id);
		//printr($data);die;
	}
}
if(isset($_POST['btn_save'])){
		//echo "Sdada";die;
		$order_id = $obj_template->addTemplate($template_id);
		page_redirect($obj_general->link($rout, 'index&status='.$price_status, '',1));
	}
	
if($display_status){	
?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
        <div class="col-lg-12">
           <?php include("common/breadcrumb.php");?>	
        </div> 
        
        <div class="col-lg-10">
        	<section class="panel">
              <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
              <form class="form-horizontal" method="post" name="form" id="order-form" enctype="multipart/form-data">
                    <div class="panel-body">
                    	<div class="col-lg-6" style="width:100%">
                           <h4><i class="fa fa-edit"></i> General Details</h4>
                           <span class="text-muted m-l-small pull-right">  <a class="label bg-success" href="javascript:void(0);" onclick="excellink('<?php echo rawurlencode($_GET['template_id']);?>')"><i class="fa fa-print"></i> Excel</a></span>
                           <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                       
                           <div class="form-group">
                                <label class="col-lg-3 control-label">Title</label>
                                <div class="col-lg-4">
                                    <label class="control-label normal-font">
                                    <?php echo $data[0]['title'];?>
                                    </label>
                                </div>
                              </div>
                        <div class="form-group">
								<label class="col-lg-3 control-label">Product</label>
								<div class="col-lg-9 row">
                                	<div class="col-lg-4">
                                         <label class="control-label normal-font">
                            <?php echo $data[0]['product_name'];?>
                            </label>
                                    </div>
                                  
                                        
								</div>
							  </div>  
                          <div class ="form-group">
                           <label class="col-lg-3 control-label">Shipment Country</label>
                                    <div class="col-lg-9 row">
                                    <div class="col-lg-10">
                                      
                            <?php
							
							$countries=json_decode($data[0]['country']);
					  $str='';
					  foreach($countries as $country)
					  {
						  $str .= "country_id = ".$country." OR ";
					  }
					  $countryval = substr($str,0,-3);
					 $country_name = $obj_template->getmultiplecountry($countryval);
					   echo $country_name;?>
                           
                                    </div>
                                    </div>
                                    </div>
                      
                        <div class="form-group">
								<label class="col-lg-3 control-label">User</label>
								<div class="col-lg-9 row">
                                	<div class="col-lg-4">
                                        <label class="control-label normal-font">
                            <?php echo $data[0]['first_name'].' '.$data[0]['last_name'];?>
                            </label>
                                    </div>
                                   <label class="col-lg-3 control-label">Currency</label>
                                    
                                    <div class="col-lg-4">
                                        <label class="control-label normal-font">
                             <?php echo $data[0]['currency_code'];?>
                            </label> 
                                    </div>
								</div>
						 </div>       
                   
                                                        
                      <div class="col-lg-12" id="add-product-div">
                           <h4><i class="fa fa-plus-circle"></i> Add Product</h4>
                        
                            
                           <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                        
                           <div class="form-group">
                                    
                                    <div class="table-responsive">
                                     <section class="panel">
                                     <table class="table table-striped b-t text-small">
              						<thead>
                 					 <tr>
                                       
                                        <th width="auto" >Size</th>
                                         <th width="auto">Dimension<br />WxLxG</th>
									
                                             <th width="auto" >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                           Qty200+
                                            </th>
                                             <th  width="auto">Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                           Qty500+
                                            </th>
                                             <th width="auto" >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                            Qty1000+
                                        </th>  <th width="auto" >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                            Qty2000+
                                        </th>
                                        
                                       
                                         <th width="auto" >
                                       Color
                                        </th>
                                      </tr>
                                        </thead>
                                        <tbody>
                                            
                                             <?php 
											 foreach($data as $dataval)
												{
													//printr($dataval);
													
													
														$qty200 = $dataval['quantity200'];
														$qty500 = $dataval['quantity500'];
														$qty1000 = $dataval['quantity1000'];
														$qty2000 = $dataval['quantity2000'];
													
												?>  <tr> 
												
                                              
                                                 <td width="auto"><?php echo $dataval['volume']; ?></td> 
                                                  <td width="auto"> <?php echo $dataval['width'].'X'.$dataval['height'].'X'.$dataval['gusset']; ?></td> 
                                                   
                                               
                                                     <td width="auto"><?php echo $qty200; ?></td> 
                                                      <td width="auto"> <?php echo $qty500; ?></td> 
                                                       <td width="auto"><?php echo $qty1000; ?></td> 
                                                       <td width="auto"><?php echo $qty2000; ?></td> 
                                                      
                                                      <td width="auto"> 	<?php $color_detail = ''; $colorval=json_decode($dataval['color']); //printr($dataval); ?>
                                                        
                                                    <select  name="color" id="color" class="form-control" width="1000px">
														<?php foreach($colorval as $va)
														{
															
															$color_detail=$obj_template->getColor($va);
															// printr($color_detail); 
														?>
															<option value="<?php echo $color_detail[0]['pouch_color_id'];?>"><?php echo $color_detail[0]['color'];?></option>	
													<?php	}
													?>
													</select></td>
                                                  						
		 
                                                            </tr>
                                                           <?php
														  
													}
													
													?>
                                        </tbody>
                                     </table>
                                     </section>
                                    </div>
                                  </div>
                                 <?php /*?> <div>
									<?php printr($obj_session->data['clonecolor']); ?>
	                             </div><?php */?>
                             <div class="form-group" id="footer-div" >
                                <div class="col-lg-9 col-lg-offset-3">
                                 <button type="submit" name="btn_save" class="btn btn-primary">Save</button>
                                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'index&status='.$price_status, '',1);?>">Cancel</a> 
                                 
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

<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

            

<script>
function excellink(id){
		var url = '<?php echo HTTP_SERVER.'word/product_temp_excel.php?mod='.encode('invoice').'&ext='.md5('php');?>&token='+id;
		window.open(url, '_blank');
	return false;
}
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>