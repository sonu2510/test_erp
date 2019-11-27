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
	'text' 	=> $display_name.' List',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

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
	$sort_order = 'DESC';	
}

$class = 'collapse';
//printr($obj_session->data);
//printr($_POST);
$filter_data=array();
$status = '';
if(!isset($_GET['filter_edit'])){
	$filter_edit = 0;
}else{
	$filter_edit = $_GET['filter_edit'];
}

if(!isset($_GET['filter_edit']) || $_GET['filter_edit']==0){
	if(isset($obj_session->data['filter_data'])){
		unset($obj_session->data['filter_data']);	
	}
}

if(isset($obj_session->data['filter_data'])){
	$filter_transport = $obj_session->data['filter_data']['transport'];
	$filter_zipper = $obj_session->data['filter_data']['zipper'];
	$filter_spout = $obj_session->data['filter_data']['spout'];
	$filter_product_name = $obj_session->data['filter_data']['product_name'];
	$filter_country = $obj_session->data['filter_data']['country'];
	$filter_user_name = $obj_session->data['filter_data']['user'];
	$filter_accessorie = $obj_session->data['filter_data']['accessorie'];
	$filter_valve = $obj_session->data['filter_data']['valve'];
	$class = '';
	
	$filter_data=array(
		'transport' => $filter_transport,
		'zipper' => $filter_zipper, 
		'spout' => $filter_spout,
		'product_name' => $filter_product_name,
		'country' => $filter_country,
		'user' => $filter_user_name,
		'accessorie' => $filter_accessorie,
		'valve' => $filter_valve,
	);
}
//printr($obj_session->data);
if(isset($_POST['btn_filter'])){
	$filter_edit = 1;
	$class = '';	
	if(isset($_POST['filter_transport'])){
		$filter_transport=$_POST['filter_transport'];		
	}else{
		$filter_transport='';
	}
	if(isset($_POST['filter_zipper'])){
		$filter_zipper=$_POST['filter_zipper'];		
	}else{
		$filter_zipper='';
	}
	if(isset($_POST['filter_spout'])){
		$filter_spout=$_POST['filter_spout'];
	}else{
		$filter_spout='';
	}	
	if(isset($_POST['filter_product_name'])){
		$filter_product_name=$_POST['filter_product_name'];
	}else{
		$filter_product_name='';
	}
	if(isset($_POST['country_id'])){
		$filter_country=$_POST['country_id'];
	}else{
		$filter_country='';
	}
	if(isset($_POST['filter_user_name']))
	{
		$filter_user_name = $_POST['filter_user_name'];
	}else{
		$filter_user_name='';
	}
	if(isset($_POST['filter_accessorie']))
	{
		$filter_accessorie = $_POST['filter_accessorie'];
	}else{
		$filter_accessorie='';
	}
	if(isset($_POST['filter_valve']))
	{
		$filter_valve = $_POST['filter_valve'];
	}else{
		$filter_valve='';
	}
	
	$filter_data=array(
		'transport' => $filter_transport,
		'zipper' => $filter_zipper, 
		'spout' => $filter_spout,
		'product_name' => $filter_product_name,
		'country' => $filter_country,
		'user' => $filter_user_name,
		'accessorie' => $filter_accessorie,
		'valve' => $filter_valve,
	);
	//printr($filter_data);
	//die;
	$obj_session->data['filter_data'] = $filter_data;		
}
if(isset($_GET['page'])){
	if(isset($_SESSION['filter_data']) && !empty($_SESSION['filter_data'])) {
	$filter_data = ($_SESSION['filter_data']);
	}
}
if($display_status) {
	$curl ='';
	if(isset($_GET['limit']) && isset($_GET['page']) && isset($_GET['filter_edit']))
		$curl ='&page='.$_GET['page'].'&limit='.$_GET['limit'].'&filter_edit='.$_GET['filter_edit'];
	//active inactive delete
	if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
	{
		if(!$obj_general->hasPermission('edit',$menuId)){
			$display_status = false;
		} else {
			$status = 0;
			if($_POST['action'] == "inactive"){
				$status = 1;
			}
			$obj_template->updateStatus($status,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, $curl, '',1));
		}
	}
	if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		//printr($_POST['post']);die;
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			//printr($_POST['post']);die;
			foreach($_POST['post'] as $template_id){
				$obj_template->deleteTemplate($template_id);
			}
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, $curl, '',1));
		}
	}
	if(isset($_POST['action']) && $_POST['action'] == "clone" && isset($_POST['post']) && !empty($_POST['post'])){
		foreach($_POST['post'] as $template_id){
				$obj_template->DuplicateMySQLRecord($template_id);
			}
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, $curl, '',1));
    }
	$tran =$tra='';
	if(isset($_POST['sea']) || isset($_GET['sea']))
	{		
		$tran ='By Sea';$tra = '&sea=0';
	}
	elseif(isset($_POST['air']) || isset($_GET['air'])){
		$tran ='By Air';$tra = '&air=0';
	}
	elseif(isset($_POST['pickup']) || isset($_GET['pickup']))
	{
	    $tran ='By Pickup';$tra = '&pickup=0';
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
		  	<span><?php echo $display_name;?> Listing </span>
          	<span class="text-muted m-l-small pull-right">
            	<?php //if($obj_general->hasPermission('add',$menuId)){ ?>
   					<!--<a class="label bg-primary" href="<?php // echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Product Template </a>-->
                <?php // } 	
				if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                        <a class="label btn-inverse" onclick="formsubmitsetaction('form_list','clone','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-copy"></i> Clone</a>
                     <?php } if($obj_general->hasPermission('delete',$menuId)){ ?>   
                       <a class="label bg-danger" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                <?php } ?>      
                            
            </span>
          </header>
          <div class="panel-body">
            
            <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '', '',1); ?>">
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
                                <label class="col-lg-5 control-label">Product</label>
                                <?php							
									$products = $obj_template->getActiveProduct();
								?>
                                <div class="col-lg-7">
                                	<select class="form-control" name="filter_product_name">
                                    	<option value="">Please Select</option>
                                    	<?php foreach($products as $product) { ?>
                                        	<?php if(isset($filter_product_name) && !empty($filter_product_name) && $filter_product_name == $product['product_id']) { ?>
                                    			<option value="<?php echo $product['product_id']; ?>" selected="selected"><?php echo $product['product_name']; ?></option>
                                            <?php } else { ?>
                                            	<option value="<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>                                       
                                    </select>
                                </div>
                              </div> 
                              
                                <div class="form-group">
                                <label class="col-lg-5 control-label">Country</label>
                                <div class="col-lg-7">
                                	<?php
										$sel_country = (isset($filter_country))?$filter_country:''; 
										$countrys = $obj_general->getCountryCombo($sel_country);
										echo $countrys;                   
									?>	             	
                                </div>
                              </div>

                              
                          </div>
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-4 control-label">Transport</label>
                                <div class="col-lg-7">
                                <select name="filter_transport" id="transport" class="form-control">
                               	<option value="">Please Select</option>
                                <option value="By Air" <?php if(isset($filter_transport) && $filter_transport == "By Air") { echo 'selected=selected';}?>>By Air</option>
                                  <option value="By Sea" <?php if(isset($filter_transport) && $filter_transport == "By Sea") { echo 'selected=selected';}?>>By Sea</option>
                                    <option value="By Pickup" <?php if(isset($filter_transport) && $filter_transport == "By Pickup") { echo 'selected=selected';}?>>By Pickup</option>
                                   </select>
                                </div>
                              </div>        
                              
                               <div class="form-group">
                                <label class="col-lg-4 control-label">For User</label>
                                <?php							
									$userlist = $obj_template->getInternational();
								?>
                                <div class="col-lg-7">
                                	<select class="form-control" name="filter_user_name">
                                    	<option value="">Please Select</option>
                                    	<?php foreach($userlist as $user) { ?>
                                        	<?php if(isset($filter_user_name) && !empty($filter_user_name) && $filter_user_name == $user['international_branch_id']) { ?>
                                            
                                    			<option value="<?php echo $user['international_branch_id']; ?>" selected="selected"><?php echo $user['first_name'].' '.$user['last_name']; ?></option>
                                            <?php } else { ?>
                                            	<option value="<?php echo $user['international_branch_id']; ?>"><?php echo $user['first_name'].' '.$user['last_name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>                                       
                                    </select>
                                </div>
                              </div>            
                          </div>
                          
                          <div class="col-lg-4">
                          <div class="form-group">
                                <label class="col-lg-4 control-label">Zipper</label>
                                <?php 
									$zipperlist = $obj_template->getActiveProductZippers();
								?>
                                <div class="col-lg-7">
                                <select class="form-control" name="filter_zipper">
                                    	<option value="">Please Select</option>
                                    	<?php foreach($zipperlist as $zipper) { ?>
                                        	<?php if(isset($filter_zipper) && !empty($filter_zipper) && $filter_zipper == $zipper['zipper_name']) { ?>
                                            
                                    			<option value="<?php echo $zipper['zipper_name']; ?>" selected="selected"><?php echo $zipper['zipper_name']; ?></option>
                                            <?php } else { ?>
                                            	<option value="<?php echo $zipper['zipper_name']; ?>"><?php echo $zipper['zipper_name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>                                       
                                    </select>
                                </div>
                              </div>        
                              <div class="form-group">
                                <label class="col-lg-4 control-label">Spout</label>
                                <?php 
									$spoutlist = $obj_template->getActiveProductSpout();
								?>
                                <div class="col-lg-7">
                                <select class="form-control" name="filter_spout">
                                    	<option value="">Please Select</option>
                                    	<?php foreach($spoutlist as $spout) { ?>
                                        	<?php if(isset($filter_spout) && !empty($filter_spout) && $filter_spout == $spout['spout_name']) { ?>
                                            
                                    			<option value="<?php echo $spout['spout_name']; ?>" selected="selected"><?php echo $spout['spout_name']; ?></option>
                                            <?php } else { ?>
                                            	<option value="<?php echo $spout['spout_name']; ?>"><?php echo $spout['spout_name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>                                       
                                    </select>
                                </div>
                              </div>        
                              
                                              
                          </div>
                          
                           <div class="col-lg-4">
                           <div class="form-group">
                                <label class="col-lg-5 control-label">Accessorie</label>
                                 <?php 
									$accessorielist = $obj_template->getActiveProductAccessorie();
								?>
                                <div class="col-lg-7">
                                <select class="form-control" name="filter_accessorie">
                                    	<option value="">Please Select</option>
                                    	<?php foreach($accessorielist as $access) { ?>
                                        	<?php if(isset($filter_accessorie) && !empty($filter_accessorie) && $filter_accessorie == $access['product_accessorie_name']) { ?>
                                            
                                    			<option value="<?php echo $access['product_accessorie_name']; ?>" selected="selected"><?php echo $access['product_accessorie_name']; ?></option>
                                            <?php } else { ?>
                                            	<option value="<?php echo $access['product_accessorie_name']; ?>"><?php echo $access['product_accessorie_name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>                                       
                                    </select>
                                </div>
                              </div>        
                             
                                                
                          </div>
                          <div class="col-lg-4">
                           <div class="form-group">
                                <label class="col-lg-4 control-label">Valve</label>
                                <div class="col-lg-7">
                                <select name="filter_valve" id="filter_valve" class="form-control">
                                <option value="">Please Select</option>
                                <option value="No Valve" <?php if(isset($filter_valve) && $filter_valve == "No Valve") { echo 'selected=selected';}?>>No Valve</option>
                                  <option value="With Valve" <?php if(isset($filter_valve) && $filter_valve == "With Valve") { echo 'selected=selected';}?>>With Valve</option>
                                 </select>
                                </div>
                              </div>        
                              
                      </div>
                </div>
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                        <input type="hidden" value="<?php echo $status;?>" id="status" name="status" />
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, '', '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
           </form>
           
           <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, 'mod=index', '',1); ?>">
                       <div class=" pull-left">
                       	 	<div class="panel-body text-muted l-h-2x">
                                 <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="sea" style="background-color:#CBC6AB"><i></i> Sea</button>
                                 <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="air" style="background-color:#f4c414"><i></i> Air</button>
                                 <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="pickup" style="background-color:#81C267"><i></i> Pickup</button>
                            </div>
                       </div> 
                       
                       </form>
           
            <div class="col-lg-3 pull-right">	
                <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                <option value="<?php echo $obj_general->link($rout, '', '',1);?>" selected="selected">--Select--</option>	
					<?php 
                        $limit_array = getLimit(); 
                        foreach($limit_array as $display_limit) {
                            if($limit == $display_limit) {	 
                    ?>
                       		 
                            <option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
                    <?php } else { ?>
                            <option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
                    <?php } ?>
                    <?php } ?>
                 </select>
           </div>
             <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>             
          </div>  
          <div class="panel-body">
            
       
          
          <form name="form_list" id="form_list" method="post">
          <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table id="quotation-row" class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox" ></th>
                      <th>Id</th>
                       <th>Title</th>
                      <th>Product Name</th>
                      <th>Dispatch Country</th>
                      <th>Transpotation</th>
                      <?php if($obj_session->data['LOGIN_USER_TYPE']==1){?>
                      <th>For User</th>
                    <?php }?>
                	  <th>Status</th>
                        <?php  if($obj_general->hasPermission('add',$menuId)){ ?>
                      <th>Action</th>
                      <?php }?>
                      </tr>
                  </thead>
                  <tbody>
                  <?php
                  $total_quotation = $obj_template->getTotalTemplate($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$filter_data,$tran);
				  //printr($total_quotation);
				  //die;
				  $pagination_data = '';
                  if($total_quotation!=0){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => 'pt.product_template_id',
                            'order' => 'DESC',
                            'start' => ($page - 1) * $limit,
                            'limit' => $limit,
                      );	
					 
					  if($total_quotation)
					  {
                      $quotationslist = $obj_template->getTemplates($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$option,$filter_data,$tran);	
					 //printr($quotationslist);
					 if(isset($quotationslist) && !empty($quotationslist))
					 {
					  foreach($quotationslist as $quotations)
					  {
						    $template_no = $obj_template->gettemplateNo($quotations['product_template_id']);
					?>  
                     <tr>
                     <td><input type="checkbox" name="post[]" value="<?php echo $quotations['product_template_id'];?>" /></td>
					  <td>
                              <a href="<?php echo $obj_general->link($rout, '&mod=view&template_id='.encode($quotations['product_template_id']), '',1);?>">
        					  <?php echo $quotations['product_template_id'];?>
        					  <br><small class="text-muted"><?php echo $template_no['multi_quotation_number'];?></small>
        					  <br><small class="text-muted"><?php echo dateFormat(4,$quotations['date_added']);?></small></a>
                      </td>
                      <td>
                          <a href="<?php echo $obj_general->link($rout, '&mod=view&template_id='.encode($quotations['product_template_id']), '',1);?>">
    					  <?php echo $quotations['title'];?></a>
                      </td>
                      <td>
    					  <a href="<?php echo $obj_general->link($rout, '&mod=view&template_id='.encode($quotations['product_template_id']), '',1);?>">
    					  <?php echo $quotations['product_name'];?></a></td>
                      <td>
                          <?php $countries=json_decode($quotations['country']);
    					  $str='';
    					  foreach($countries as $country)
    					  {
    						  $str .= "country_id = ".$country." OR ";
    					  }
    					  $countryval = substr($str,0,-3);
    					 $country_name = $obj_template->getmultiplecountry($countryval);
    					  
    					  ?>
                          <a href="<?php echo $obj_general->link($rout, '&mod=view&template_id='.encode($quotations['product_template_id']), '',1);?>">
    					  <?php echo $country_name;?></a>
    				  </td>
                      <td>
                          <a href="<?php echo $obj_general->link($rout, '&mod=view&template_id='.encode($quotations['product_template_id']), '',1);?>">
    					  <?php 	//[kinjal] modify on (28/3/2016)
    					  		if($quotations['transportation_type'] == '')
    					  			echo 'By Pickup';
    							else
    						  		echo $quotations['transportation_type'];?></a>
    				    </td>
                      <?php  if($obj_session->data['LOGIN_USER_TYPE']==1){?>
                      <td>
					  <a href="<?php echo $obj_general->link($rout, '&mod=view&template_id='.encode($quotations['product_template_id']), '',1);?>">
					  <?php echo $quotations['first_name'].' '.$quotations['last_name'];?></a></td>
                      <?php }?>
                      <td>
                          	<a href="<?php echo $obj_general->link($rout, '&mod=view&template_id='.encode($quotations['product_template_id']), '',1);?>">
                           <div data-toggle="buttons" class="btn-group">
                           <?php if($quotations['status'] == 0)
						   		{
									
							?>
                                	<label class="label label-success"> Active</label>
                             <?php 
								}
								else
								{
								?>
                                	<label class="label label-danger">Inactive</label> 
                                    <?php 
								}
								?>
                            </div></a>
                          
                           </td>
                          <td>
                        <?php  if($obj_general->hasPermission('add',$menuId)){ ?>
                          <a href="<?php echo $obj_general->link($rout, 'mod=add&template_id='.encode($quotations['product_template_id']).'&product_id='.$quotations['product_id'],'',1);?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                           <a href="javascript:void(0);" onclick="cloneTemplate(<?php echo $quotations['product_template_id'];?>)"  name="btn_clone" class="btn btn-info btn-xs">Clone</a>
                          <?php
						}
						 /*  if($quotations['status'] == 0)
						   	{*/
						?>
								<!-- <a href="<?php //echo $obj_general->link($rout, 'mod=cart&template_id='.encode($quotations['product_template_id']).'&product_id='.$quotations['product_id'],'',1);?>"  name="btn_edit" class="btn btn-info btn-xs">Cart</a>	-->
						<?php	//}
							?>
                          </td>
                      </tr>
                      
                      <?php
					  }
					  }
					  }
					  ?>
                      
                      <?php	
				
                        //pagination
                       $pagination = new Pagination();
                        $pagination->total = $total_quotation;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout,'&page={page}&limit='.$limit.'&filter_edit=1'.$tra, '',1);
                        $pagination_data = $pagination->render();
                        //echo $pagination_data;die;
                  } else{ 
                      echo "<tr><td colspan='5'>No record found !</td></tr>";
                  } ?>
                  </tbody>
                </table>
              </div>
          </form>
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-2 hidden-xs"> </div>
              <?php echo $pagination_data;?>
            </div>
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>


<script type="application/javascript">

	function cloneTemplate(template_id){
	//alert("hui");
	var clone_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=cloneTemplate', '',1);?>");
	//alert(gusset_url);
	$.ajax({
		method : 'post',
		url: clone_url,
		data: {template_id:template_id},
		success: function(response) {
			//alert(response);
				location.reload();
		}
		,
		error: function(){
			return false;	
		}
	});
}
	$('.delete-quot a').click(function(){
		var con = confirm("Are you sure you want to delete ?");
	
		if(con){
			var template_id=$(this).attr('id');
			var del_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=deleteTemplate', '',1);?>");
			$('#loading').show();
			$.ajax({
				url : del_url,
				type :'post',
				data :{template_id:template_id},
				success: function(response){
					if(response==1){
						$('#quotation-row-'+template_id).remove();
						set_alert_message('Successfully Deleted',"alert-success","fa-check");	
					}
					$('#loading').hide();								
				},
				error:function(){
					set_alert_message('Error!',"alert-warning","fa-warning");          
				}			
			});
		}
	});
	
	
	$('input[type=radio][name=status]').change(function() {
	
		//alert($(this).attr('id'));
		var product_template_id=$(this).attr('id');
		var status_value = this.value;
		//alert(product_template_id);
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateQuotationStatus', '',1);?>");
        $.ajax({
			url : status_url,
			type :'post',
			data :{product_template_id:product_template_id,status_value:status_value},
			success: function(response){
				
					set_alert_message('Successfully Updated',"alert-success","fa-check");	
											
			},
			error:function(){
				set_alert_message('sda',"alert-warning","fa-warning");          
			}			
		});
    });
   

</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>