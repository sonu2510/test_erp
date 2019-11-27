<?php
//priya (25-4-2015)for view of perticular rack detail.
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
$class = 'collapse';
//Start : edit
$filter_data=array();
$edit = '';
$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

if(isset($_GET['sort'])){
	$sort = $_GET['sort'];	
}else{
	$sort= 'date_added';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'ASC';	
}

if(isset($_GET['goods_id']) && !empty($_GET['goods_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$goods_id = base64_decode($_GET['goods_id']);
		$arr=explode('-',$_GET['data']);
		$user_id=$obj_session->data['ADMIN_LOGIN_SWISS'];
		$user_type_id=$obj_session->data['LOGIN_USER_TYPE'];
		$goods_data = $obj_goods_master->getGoodsData($goods_id);
		
		//printr($stock_data);
		//die;
		
	}
}
if(isset($obj_session->data['filter_data'])){
	$proforma_no = $obj_session->data['filter_data']['proforma_no'];
	$invoice_no = $obj_session->data['filter_data']['invoice_no'];
	$orderno = $obj_session->data['filter_data']['orderno'];
	$company_name = $obj_session->data['filter_data']['company_name'];
	$date = $obj_session->data['filter_data']['date'];
	$valve = $obj_session->data['filter_data']['valve'];
	
	$zipper = $obj_session->data['filter_data']['zipper'];
	$spout = $obj_session->data['filter_data']['spout'];
	$accessorie = $obj_session->data['filter_data']['accessorie'];
	$make = $obj_session->data['filter_data']['make'];
	$color = $obj_session->data['filter_data']['color'];
	$volume = $obj_session->data['filter_data']['volume'];
	$class = '';
	
		$filter_data=array(
		'proforma_no' => $proforma_no,
		'invoice_no' => $invoice_no, 
		'orderno' => $orderno,
		'company_name' => $company_name,
		'date' => $date,
		'valve' => $valve,	
		
		'zipper' => $zipper,
		'spout' => $spout,
		'accessorie' => $accessorie,
		'make' => $make,
		
		'color' => $color,
		'volume' => $volume,
		
	);
}
if(isset($_POST['btn_filter'])){
	$filter_edit = 1;
	$class = '';	
	if(isset($_POST['proforma_no'])){
		$proforma_no=$_POST['proforma_no'];		
	}else{
		$proforma_no='';
	}
	if(isset($_POST['invoice_no'])){
		$invoice_no=$_POST['invoice_no'];		
	}else{
		$invoice_no='';
	}
	if(isset($_POST['orderno'])){
		$orderno=$_POST['orderno'];
	}else{
		$orderno='';
	}	
	if(isset($_POST['company_name'])){
		$company_name=$_POST['company_name'];
	}else{
		$company_name='';
	}
	if(isset($_POST['date'])){
		$date=$_POST['date'];
	}else{
		$date='';
	}
	if(isset($_POST['valve']))
	{
		$valve = $_POST['valve'];
	}else{
		$valve='';
	}
	if(isset($_POST['zipper'])){
		$zipper=$_POST['zipper'];
	}else{
		$zipper='';
	}
	if(isset($_POST['spout']))
	{
		$spout = $_POST['spout'];
	}else{
		$spout='';
	}
	if(isset($_POST['accessorie'])){
		$accessorie=$_POST['accessorie'];
	}else{
		$accessorie='';
	}
	if(isset($_POST['make']))
	{
		$make = $_POST['make'];
	}else{
		$make='';
	}
	if(isset($_POST['color'])){
		$color=$_POST['color'];
	}else{
		$color='';
	}
	if(isset($_POST['volume']))
	{
		$volume = $_POST['volume'];
	}else{
		$volume='';
	}
	
	
	$filter_data=array(
		'proforma_no' => $proforma_no,
		'invoice_no' => $invoice_no, 
		'orderno' => $orderno,
		'company_name' => $company_name,
		'date' => $date,
		'valve' => $valve,	
		
		'zipper' => $zipper,
		'spout' => $spout,
		'accessorie' => $accessorie,
		'make' => $make,
		
		'color' => $color,
		'volume' => $volume,
		
	);
	$obj_session->data['filter_data'] = $filter_data;		
}
if(!isset($_GET['filter_edit']) || $_GET['filter_edit']==0){
	if(isset($obj_session->data['filter_data'])){
		unset($obj_session->data['filter_data']);	
	}
}
$edit = '';
if(isset($_GET['stock_id']) && !empty($_GET['stock_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$stock_id = base64_decode($_GET['stock_id']);
		
		$user_id=$obj_session->data['ADMIN_LOGIN_SWISS'];
		$user_type_id=$obj_session->data['LOGIN_USER_TYPE'];
	
		//printr($stock_id);
		//die;
		
	}
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
		<div class="col-sm-12">
        
            <section class="panel">  
            
                <header class="panel-heading bg-white">
                    <span> Rack Detail</span> 
                 </header>
                 <div class="panel-body">
              <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '&mod=view_dispatch&stock_id='.$_GET['stock_id'], '',1); ?>">
                <section class="panel pos-rlt clearfix">
                  <header class="panel-heading">
                    <ul class="nav nav-pills pull-right">
                      <li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> </li>
                    </ul>
                    <i class="fa fa-search"></i> Search
                  </header>
              
              
                 <div class="panel-body clearfix <?php echo $class; ?>">        
                      <div class="row">
                      <div class="col-lg-4">
                              <div class="form-group">
                               <label class="col-lg-4 control-label">Proforma No</label>
                        		<div class="col-lg-6">
                            	<input type="text" name="proforma_no" id="proforma_no" class="form-control validate">
                        		</div>
                              </div>                             
                          
                              <div class="form-group">
                                <label class="col-lg-4 control-label" >Invoice No</label>
                                <div class="col-lg-6">
                                    <input type="text" name="invoice_no" id="invoice_no" class="form-control validate">
                                </div>
                              </div>   
                           </div>
                          
                          <div class="col-lg-4">                              
                               <div class="form-group">
                                 <label class="col-lg-4 control-label">Order No</label>
                        		<div class="col-lg-6">
                           		 <input type="text" name="orderno" id="orderno" class="form-control validate">
                        		</div>  
                              </div>
                               <div class="form-group">
                                 <label class="col-lg-4 control-label">Company Name</label>
                                <div class="col-lg-6">
                                    <input type="text" name="company_name" id="company_name" class="form-control validate">
                                </div>
                              </div>
                          </div>    
                          
                             <div class="col-lg-4">                              
                               <div class="form-group">
                                  <label class="col-lg-4 control-label"> Date </label>
                                    <div class="col-lg-6">
                                     <input type="text" name="date"  id="date" readonly="readonly" data-date-format="yyyy-mm-dd" 
                                     value="" placeholder="Date" class="input-sm form-control datepicker" />
                                    </div>
                              </div>
                               <div class="form-group">
                                 <label class="col-lg-4 control-label">Valve</label>
                                <div class="col-lg-6">
                                <select name="valve" id="valve" class="form-control validate">
                                	<option value="">Select</option>
                                    <option value="No Valve">No Valve</option>
                                    <option value="With Valve">With Valve</option>
                                
                                </select>
                                </div>
                              </div>
                          </div> 
                          </div>
                          <div class="row">
                          
                          
                             <div class="col-lg-4">                              
                               <div class="form-group">
                                <label class="col-lg-4 control-label">Zipper</label>
                                <div class="col-lg-6">
								 <select name="zipper" id="zipper" class="form-control validate" >
                                          <option value="">Select</option>
                                           <?php $zippers = $obj_rack_master->getActiveProductZippers(); 
                                                foreach($zippers as $zipper){?>
                                                   <option value="<?php echo $zipper['product_zipper_id'];?>"><?php echo $zipper['zipper_name'];?></option>
                                            <?php }?>
                                        </select>
								 </div>
                              </div>
                               <div class="form-group">
                                 <?php $spouts = $obj_rack_master->getActiveProductSpout();?>
              
                                     <label class="col-lg-4 control-label">Spout</label>
                                      <div class="col-lg-6">
                                       <select name="spout" id="spout" class="form-control validate" >
                                          <option value="">Select</option>
                                           <?php 
                                                foreach($spouts as $spout){?>
                                                   <option value="<?php echo $spout['product_spout_id'];?>"><?php echo $spout['spout_name'];?></option>
                                            <?php }?>
                                        </select>
                                     </div>           
                              </div>
                          </div> 
                          
                           <div class="col-lg-4">                              
                               <div class="form-group">
								<?php $accessories = $obj_rack_master->getActiveProductAccessorie();?>
                                 <label class="col-lg-4 control-label">Accessorie</label>
                                  <div class="col-lg-6">
                                  <select name="accessorie" id="accessorie" class="form-control validate" >
                                  <option value="">Select</option>
                                   <?php 
                                        foreach($accessories as $accessorie){?>
                                           <option value="<?php echo $accessorie['product_accessorie_id'];?>"><?php echo $accessorie['product_accessorie_name'];?></option>
                                    <?php }?>
                                </select>
                                </div>
                              </div>
                               <div class="form-group">
                                   <label class="col-lg-4 control-label">Make Pouch</label>
                                    <div class="col-lg-6">
                                    	<select name="make" id="make" class="form-control validate" >
                                          <option value="">Select</option>
                                           <?php $makes = $obj_rack_master->getActiveMake();
                                                foreach($makes as $make){?>
                                                   <option value="<?php echo $make['make_id'];?>"><?php echo $make['make_name'];?></option>
                                            <?php }?>
                                        </select>
                                     </div>
                              </div>
                          </div> 
                          
                          
                           <div class="col-lg-4">                              
                               <div class="form-group">
                                  <label class="col-lg-4 control-label">Color</label>
                                 <div class="col-lg-6">
                                 <select name="color" id="color" class="form-control validate" >
                                  <option value="">Select</option>
                                   <?php $colors = $obj_rack_master->getPouchColor();
                                        foreach($colors as $color){?>
                                           <option value="<?php echo $color['pouch_color_id'];?>"><?php echo $color['color'];?></option>
                                    <?php }?>
                                </select>
                    		</div>
                              </div>
                               <div class="form-group">
                                  <label class="col-lg-4 control-label">Volume</label>
                             		<div class="col-lg-6">
                             			<select name="volume" id="volume" class="form-control validate" >
                              			<option value="">Select</option>
									   <?php $volumes = $obj_rack_master->getPouchVolume();
                                                foreach($volumes as $volume){?>
                                                  <option value="<?php echo $volume['pouch_volume_id'];?>"><?php echo $volume['volume'];?></option>
                                        <?php }?>
                                        </select>
                           			 </div>
                              </div>
                          </div> 
                          
                                          
                      </div>
            	</div>
                
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, '&mod=view_dispatch&stock_id='.$_GET['stock_id'], '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>  
                                                  
              </section>
         	</form>
          
            <div class="row">
             <div class="col-lg-3 pull-right">	
                 <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                 <option value="<?php echo $obj_general->link($rout, '&mod=view_dispatch&stock_id='.$_GET['stock_id'], '',1);?>" selected="selected">--Select--</option>
                    	<?php 
							$limit_array = getLimit(); 
							foreach($limit_array as $display_limit) {
								if($limit == $display_limit) {	 
						?>
                  <option value="<?php echo $obj_general->link($rout, '&mod=view_dispatch&stock_id='.$_GET['stock_id'].'&limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, '&mod=view_dispatch&stock_id='.$_GET['stock_id'].'&limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
                        <?php } ?>
                        <?php } ?>
                 </select>
             </div>
                <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>	
            </div>   
          </div>
                 	<div class="panel-body">
                		<form name="form_list" id="form_list" method="post">
                           <input type="hidden" id="action" name="action" value="" />
                            <div class="table-responsive">
                                <table id="quotation-row" class="table b-t text-small table-hover">
                                  <thead>
                                    <tr>
                                      <th>Sr. No.</th> 
                                      <th>Product Name</th>
                                      <th>Color</th>
                                      <th>Volume</th>        
                                      <th>Company Name</th>
                                       <th>Invoice No</th>      
                                      <th>Proforma No</th>                    
                                      <th>Descripton</th>
                                      <th>Qty</th>
                                      <th>Date</th>
                                      <th>Posted By</th>
                                   	</tr>
                                  </thead>
                                  <tbody>

                    	    
						 <?php 
						$pagination_data = '';
						  $total_records='';
						   $total_stock_data = $obj_rack_master->getdispatchdetail($user_id,$user_type_id,$stock_id,'',$filter_data);
						   if(!empty($total_stock_data))
						   {
						   	$total_records=count($total_stock_data);
						   }
						   //echo $total_records;
							if($total_records!=0){
							if (isset($_GET['page'])) {
								$page = (int)$_GET['page'];
							} else {
								$page = 1;
							}
							 $start_num =((($page*$limit)-$limit)+1);
					  		$f = 0;
					  		$slNo = $f+$start_num;
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => $sort,
                            'order' => $sort_order,
                            'start' => ($page - 1) * $limit,
                            'limit' => $limit,
							
                      );	
						 $stock_dis_data = $obj_rack_master->getdispatchdetail($user_id,$user_type_id,$stock_id,$option,$filter_data);
						 if(isset($stock_dis_data) && !empty($stock_dis_data)){
							 foreach($stock_dis_data as $stock){ 							 
							 $dispatch_qty=$obj_rack_master->gettotaldispatchChild($stock['stock_id'],$filter_data);
							 $zipper_name=$obj_rack_master->getzipper($stock['zipper_id']);
							 $spout_name=$obj_rack_master->getSpout($stock['spout_id']);
								$accessorie_name=$obj_rack_master->getAccessorie($stock['accessorie_id']);
								$make_name=$obj_rack_master->getMake($stock['make_id']);
								$color_name=$obj_rack_master->getColor($stock['color_id']);
								$size_name=$obj_rack_master->getSize($stock['size_id']);
						
									if($stock['description']==2){$des="Dispatched";$qty=$stock['dispatch_qty'];}
									elseif($stock['description']==1){$des="Store";$qty=$stock['qty'];}
									else{$des="Goods Return";$qty=$stock['qty'];}
								
							   			$user_id=$stock['user_id'];
										$user_type_id=$stock['user_type_id'];
							   			$postedByData=$obj_rack_master->getUser($user_id,$user_type_id);
									$addedByImage = $obj_general->getUserProfileImage($user_type_id,$user_id,'100_');
									$postedByInfo = '';
									$postedByInfo .= '<div class="row">';
										$postedByInfo .= '<div class="col-lg-3"><img src="'.$addedByImage.'"></div>';
										$postedByInfo .= '<div class="col-lg-9">';
										if($postedByData['city']){ $postedByInfo .= $postedByData['city'].', '; }
										if($postedByData['state']){ $postedByInfo .= $postedByData['state'].' '; }
										if(isset($postedByData['postcode'])){ $postedByInfo .= $postedByData['postcode']; }
										$postedByInfo .= '<br>Telephone : '.$postedByData['telephone'].'</div>';
									$postedByInfo .= '</div>';
									$postedByName = $postedByData['first_name'].' '.$postedByData['last_name'];
									str_replace("'","\'",$postedByName);
							 
							 ?>
								 
							<tr>
							  <td width="1%"><?php echo $slNo++;?></td>
                               <td width="20%">
								<?php echo '<b>'.$stock['product_name'].'</b><br> ( '.$stock['valve'].' , '.$zipper_name['zipper_name'].' , '.$spout_name['spout_name'].' , '.$accessorie_name['product_accessorie_name'].'  , '.$make_name['make_name'].' , '.$color_name['color'].' , '.$size_name['volume'].' )'; ?> 
							  </td>
                               <td><?php echo $color_name['color'];?></td>
                              <td><?php echo $size_name['volume'];?></td>
                              <td>
                              	<?php echo $stock['company_name'];?>
                              </td>
                               <td>
                              	<?php echo $stock['invoice_no'];?>
                              </td>
                              <td>
                              	<?php echo $stock['proforma_no'];?>
                              </td>
							  <td>
								<?php echo $des; ?> 
							  </td>
                              
							 <td>
                 				<?php echo $qty ; ?>	
                              </td>
                           	<td>
                              	<?php echo dateFormat(4,$stock['date_added']); ?>
							  </td>
							  <td> 
                             
								<a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $postedByInfo;?>' title="" data-original-title="<b><?php echo $postedByName;?></b>"><?php echo $postedByData['user_name'];?></a>
							  <?php //echo $postedByData['user_name'];//$postedByData['first_name'].' '.$postedByData['last_name'];?></td>
                              
							</tr>
								 
							<?php	
							
							
							  $slNoC=1;
							  if(isset($dispatch_qty) && !empty($dispatch_qty))
							  {
							  foreach($dispatch_qty as $child)
							  {//printr( $child);
							 	if($child['description']==2){$desc="Dispatched";$qtyc=$child['dispatch_qty'];}
									elseif($child['description']==1){$desc="Store";$qtyc=$child['qty'];}
									else{$desc="Goods Return";$qtyc=$child['qty'];}
									
								
							   			$user_id_child=$child['user_id'];
										$user_type_id_child=$child['user_type_id'];
							   			$postedByDataChild=$obj_rack_master->getUser($user_id_child,$user_type_id_child);
									$addedByImageChild = $obj_general->getUserProfileImage($user_type_id_child,$user_id_child,'100_');
									$postedByInfoChild = '';
									$postedByInfoChild .= '<div class="row">';
										$postedByInfoChild .= '<div class="col-lg-3"><img src="'.$addedByImageChild.'"></div>';
										$postedByInfoChild .= '<div class="col-lg-9">';
										if($postedByDataChild['city']){ $postedByInfoChild .= $postedByDataChild['city'].', '; }
										if($postedByDataChild['state']){ $postedByInfoChild .= $postedByDataChild['state'].' '; }
										if(isset($postedByDataChild['postcode'])){ $postedByInfoChild .= $postedByDataChild['postcode']; }
										$postedByInfoChild .= '<br>Telephone : '.$postedByDataChild['telephone'].'</div>';
									$postedByInfoChild .= '</div>';
									$postedByNameChild = $postedByDataChild['first_name'].' '.$postedByDataChild['last_name'];
									str_replace("'","\'",$postedByNameChild);
							 
							 ?>
								 
							<tr>
							  <td width="1%"><?php //echo $slNoC++;?></td>
                               <td width="20%">
								
							  </td>
                              <td >
								
							  </td>
                              <td >
								
							  </td>
                              <td>
                              	<?php echo $child['company_name'];?>
                              </td>
                              <td>
                              	<?php echo $child['invoice_no'];?>
                              </td>
                              <td>
                              	<?php echo $child['proforma_no'];?>
                              </td>
							  <td>
								<?php echo $desc; ?> 
							  </td>
                              
							 <td>
                 				<?php echo $qtyc ; ?>	
                              </td>
                           	<td>
                              	<?php echo dateFormat(4,$child['date_added']); ?>
							  </td>
							  <td> 
                             
								<a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $postedByInfoChild;?>' title="" data-original-title="<b><?php echo $postedByNameChild;?></b>"><?php echo $postedByDataChild['user_name'];?></a>
							  <?php //echo $postedByData['user_name'];//$postedByData['first_name'].' '.$postedByData['last_name'];?></td>
                              
							</tr>
							<?php	
							} 
							 }
							 }
							 }
                        	$pagination = new Pagination();
                        $pagination->total = $total_records;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout,'&mod=view_dispatch&stock_id='.$_GET['stock_id'].'&page={page}&limit='.$limit.'&filter_edit=1', '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
                        $pagination_data = $pagination->render();
						}
                         else{
						 echo "No record Found";
						 }
                        ?>
                        </tbody>
                             </table>
                           
                       </div>
                 </form>
                </div>
             </section>
         </div>
      </div>
     </section>
</section>

<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="application/javascript">


</script>


<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>
