<?php
include("mode_setting.php");
//[kinjal]:
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

$class ='collapse';
$filter_data=array();

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
	$filter_product_code = $obj_session->data['filter_data']['product_code'];
	$filter_volume = $obj_session->data['filter_data']['volume'];
	$filter_product = $obj_session->data['filter_data']['product'];
	$filter_color = $obj_session->data['filter_data']['color'];
	$filter_box = $obj_session->data['filter_data']['box_no'];
	$class = '';
	
	$filter_data=array(
		'product_code' => $filter_product_code,
		'volume' => $filter_volume, 
		'product' => $filter_product,
		'color' => $filter_color,
		'box_no'=> $filter_box,		
	);
}
if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='product_code_id';
}

if(isset($_GET['sort_by'])){
	$sort_by_product = $_GET['sort_by'];
}else{
	$sort_by_product='';
}


if(isset($_GET['order'])){
	$sort_order = $_GET['order']; 
}else{
	$sort_order = 'DESC';
}



if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class ='';
		
	if(isset($_POST['filter_product_code'])){
		$filter_product_code=$_POST['filter_product_code'];		
	}else{
		$filter_product_code='';
	}
	
	if(isset($_POST['filter_volume'])){
		$filter_volume=$_POST['filter_volume'];		
	}else{
		$filter_volume='';
	}

	if(isset($_POST['filter_product'])){
		$filter_product=$_POST['filter_product'];
	}else{
		$filter_product='';
	}
	
	if(isset($_POST['filter_color'])){
		$filter_color=$_POST['filter_color'];
	}else{
		$filter_color='';
	}
	
	if(isset($_POST['filter_box'])){
		$filter_box=$_POST['filter_box'];
	}else{
		$filter_box='';
	}

	$filter_data=array(
		'product_code' => $filter_product_code,
		'volume' => $filter_volume, 
		'product' => $filter_product,
		'color' => $filter_color,
		'box_no'=> $filter_box,		
	);
	
	//printr($filter_data);
	//die;
	
	$obj_session->data['filter_data'] = $filter_data;	
}
if($display_status) {

	//active inactive delete
	if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
	{
		if(!$obj_general->hasPermission('edit',$menuId)){
			$display_status = false;
		} else {
			$status = 0;
			if($_POST['action'] == "active"){
				$status = 1;
			}
			$obj_product_code->updateStatus($status,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			//printr($_POST['post']);die;
			$obj_product_code->updateStatus(2,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}
	

$total_product_code= $obj_product_code->getTotalProductCode($sort_by_product,$filter_data);
$pagination_data = '';
 $default_country=$obj_product_code->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
//printr($default_country);
$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

$s_by ='';
if(isset($_GET['sort_by']))
	$s_by = '&sort_by='.$_GET['sort_by'].'&';

//$s_by ='';
if(isset($_GET['order']))
	$s_by = '&sort='.$_GET['sort'].'&order='.$_GET['order'].'&';
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
		  	<span style="margin-left: -37%;"><?php echo $display_name;?> Listing</span>
          	<span style="margin-left:20%">
				<a href="<?php echo HTTP_SERVER."admin/controller/product_code/tutorial/tutorial.doc";?>" target="_blank" class="a-btn" style=" margin: inherit;">
						<span class="a-btn-text">Product Code Tutorial</span> 
						<span class="a-btn-slide-text">Click Here For Download</span>
						<span class="a-btn-icon-right"><span><img src="<?php echo HTTP_SERVER.'admin/controller/product_code/arrow_right.png';?>" /></span></span>
					</a>
			</span>
			
            <span class="text-muted m-l-small pull-right">
            	
                <?php if($user_type_id==1 && $user_id==1)
					 {?>
					 		<a class="label bg-info" onclick="CloneData()"><i class="fa fa-copy"></i> Clone Model</a>
				<?php }
					if($obj_general->hasPermission('add',$menuId)){ ?>
							<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Product Code</a>
					<?php }?>
                    <a class="label bg-info" href="javascript:void(0);" onclick="csvlink('post[]')"> <i class="fa fa-print"></i> CSV Export</a>
                    <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                     <?php }
                    if($obj_general->hasPermission('delete',$menuId)){ ?>       
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
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
                                <label class="col-lg-5 control-label">Product Code</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_product_code" value="<?php echo isset($filter_product_code) ? $filter_product_code : '' ; ?>" placeholder="Product Code" id="input-name" class="form-control" />
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Volume</label>
                                <div class="col-lg-7">                                                                                           
                                 <input type="text" name="filter_volume" value="<?php echo isset($filter_volume) ? $filter_volume : '' ; ?>" placeholder="Volume" id="input-name" class="form-control" />                                 
                                </div>
                              </div>
                              
                               <?php if($default_country['country_id']=='111')
								{ ?>
                               <div class="form-group">
                                <label class="col-lg-5 control-label">Box No</label>
                                <div class="col-lg-7">                                                                                           
                                 <input type="text" name="filter_box" value="<?php echo isset($filter_box) ? $filter_box : '' ; ?>" placeholder="Box No" id="input-name" class="form-control" />                                 
                                </div>
                              </div>
                              <?php } ?>
                              
                        </div>
                        
                        <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label"><span class="required">*</span> Select Product</label>
                     		   <div class="col-lg-7">
									<?php $products = $obj_product_code->getActiveProduct(); ?>
                                    <select name="filter_product" class="form-control">
                                    <option value="">Select Product</option>
                                        <?php  foreach($products as $product){
											if(isset($filter_product) && !empty($filter_product) && $filter_product == $product['product_id']) {
                                   ?>
                                                <option value="<?php echo $product['product_id']; ?>" selected="selected" ><?php echo $product['product_name']; ?></option>
                                            <?php }else{ ?>
                                                <option value="<?php echo $product['product_id']; ?>"> <?php echo $product['product_name']; ?></option>
                                            <?php }
                                        } ?>
                                         <?php if(isset($filter_product))
                                            $id=$filter_product;
                                        elseif(isset($product['product_id']))
                                            $id=$product['product_id'];
                                        ?>
                                    <option value="11" <?php if(isset($id) && ($id  == '11')) echo  'selected="selected"';?>>Plastic Scoop</option>
                                    </select>
                       			 
                                 </div>
                              </div>
                              
                      <div class="form-group">
                                     <label class="col-lg-5 control-label"><span class="required">*</span> Select Color</label>
                        <div class="col-lg-7">
                            <?php $colors = $obj_product_code->getColor(); ?>
                            <select name="filter_color" id="filter_color" class="form-control validate[required]">
                            <option value="">Select Color</option>
                                <?php  foreach($colors as $color){
                                    if(isset($filter_color) && !empty($filter_color) && $filter_color == $color['pouch_color_id']){ ?>
                                        <option value="<?php echo $color['pouch_color_id']; ?>" selected="selected" ><?php echo $color['color']; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $color['pouch_color_id']; ?>" <?php if(isset($product_code_id) && ($product_code['color'] == $color['pouch_color_id'])) { echo 'selected="selected"';}?> > <?php echo $color['color']; ?></option>
                                    <?php }
                                } ?>
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
                         <a href="<?php echo $obj_general->link($rout, '', '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                      </div> 
                   </div>
                </footer>                                  
              </section>
          </form>    
			  
              <div class="row">
              	<div class="col-lg-3 pull-left">
                    <a href="<?php echo  $obj_general->link($rout, 'sort_by=STOCK', '',1);?>"  name="" class="btn btn-info btn-xs">Stock Products</a>
                    <a href="<?php echo  $obj_general->link($rout, 'sort_by=CUST', '',1); ?>"  name="" class="btn btn-info btn-xs">Custom Products</a>
                    <a href="<?php echo  $obj_general->link($rout, '', '',1); ?>"  name="" class="btn btn-info btn-xs">Reset</a>
             	</div>
                <div class="col-lg-3 pull-right">	
                    <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                     <option value="<?php echo $obj_general->link($rout, '', '',1);?>" selected="selected">--Select--</option>
                    	<?php 
							$limit_array = getLimit(); 
							foreach($limit_array as $display_limit) {
								if($limit == $display_limit) {	 
						?>
                        		<option value="<?php echo $obj_general->link($rout, $s_by.'limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>			
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, $s_by.'limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
                        <?php } ?>
                        
                        <?php } ?>
                           	<option value="<?php echo $obj_general->link($rout, $s_by.'limit=all', '',1);?>" <?php if($limit=='all') {echo 'selected=selected';}?> >All</option>

                    </select>
                </div>
                <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>	
              </div>                 
          </div>
          

          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table  id="example"  class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox"></th>                     
                     <!-- <th>Product</th>-->
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> "> Product Code
                             <span class="th-sort">
                                <a href="<?php echo $obj_general->link($rout,  $s_by.'sort=product_code'.'&order=ASC', '',1);?>">
                                <i class="fa fa-sort-down text"></i>
                                <a href="<?php echo $obj_general->link($rout,  $s_by.'sort=product_code'.'&order=DESC', '',1);?>">
                                <i class="fa fa-sort-up text-active"></i>
                          <i class="fa fa-sort"></i></span>
                     </th>
                      <th>Description</th>
                      <th>Volume</th>
                      <th>Color</th>
                      <th>Product Description</th>
                      <th>Status</th>
                      <?php if($obj_general->hasPermission('edit',$menuId)){  ?>
                      <th>Action</th>
                       <?php } ?>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  if($total_product_code){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
						$obj_session->data['page'] = $page;
                      //option use for limit or and sorting function	
                      $option = array(
                           'sort_by' => $sort_by_product,
						   'sort'  => $sort_name,
                           'order' => $sort_order,
                           'start' => ($page - 1) * $limit,
                           'limit' => $limit
                      );	
					  //printr($option);
                      $product_code = $obj_product_code->getProductCode($option,$filter_data);
				//	  printr($product_code);
					 $i=1;
                      foreach($product_code as $product){  
					  $product_Total=$obj_product_code->getTotal($product['product_code_id']);
					  $acc_name=$obj_product_code->getAccessorie($product['accessorie_second']);
					 // printr( $product_Total);
                        ?>
                        <tr <?php echo ($product['status']==0) ? 'style="background-color:#FADADF" ' : '' ; ?>>
                          <td><input type="checkbox" name="post[]" value="<?php echo $product['product_code_id'];?>"></td>
                         <?php /*?> <td><?php $product_name = $obj_product_code->getProductName($product['product']);
						 			 echo $product_name['product_name'];?></td><?php */?>
                          <td><?php echo $product['product_code'];?></td>
                          <td><?php echo $product['description'];?></td>
                          <td><?php echo $product['volume'].' '.$product['measurement'];?></td>
                          <td><?php $color_name = $obj_product_code->getColorName($product['color']);
					 			 if($product['color']=='-1')
					 			    echo 'Custom';
					 			 else
					 			    echo $color_name['color'];?></td>
                               
                             <td>
                                 <?php echo $product['product_name'].'<br>( Makepouch:- '.$product['make_name'].' , '.$product['zipper_name'].' , '.$product['valve'].' , '.$product['spout_name'].' , '.$product['product_accessorie_name'].', '.$acc_name['product_accessorie_name'].' )';?>
                                 
                             </td>
                              <td>
                           		<div data-toggle="buttons" class="btn-group">
                                	<label class="btn btn-xs btn-success <?php echo ($product['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="1" id="<?php echo $product['product_code_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>
                                     
                                	<label class="btn btn-xs btn-danger <?php echo ($product['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="0" id="<?php echo $product['product_code_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                                </div>
                          
                           </td>
                           <?php if($obj_general->hasPermission('edit',$menuId)){  ?>
                          <td> 
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&product_code_id='.encode($product['product_code_id']).'&filter_edit='.$filter_edit, '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>

                           </td>
                         <?php } ?>
                        </tr>
                        <?php
                   $i++;   }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_product_code;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, $s_by.'page={page}&limit='.$limit.'&filter_edit=1', '',1);
                        $pagination_data = $pagination->render();
                    } else{ 
                      echo "<tr><td colspan='5'>No record found !</td></tr>";
                  } ?>
                  </tbody>
                </table>
              </div>
          </form>
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-3 hidden-xs"> </div>
              <?php if($limit!='all')
			  echo $pagination_data;?>
             
            </div>
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>

<!-- modal -->
<!--added by mansi-->
<div class="modal fade" id="stock_mang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header title">
               <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Stock Management : <span id="pro"></span></h4>
              </div>
           
              <div class="modal-body">
                   <div class="form-group">
                          <input type="hidden" name="product_code" id="product_code" value="" />
                          <input type="hidden" name="product_code_id" id="product_code_id" value="" />
                		<label class="col-lg-3 control-label"  style="width:15%"  >Proforma No</label>
                        <div class="col-lg-3" style="width:120px">
                            <input type="text" name="proforma_no" id="proforma_no" class="form-control validate"   style="width: 100px;">
                        </div>
                        
                        <label class="col-lg-3 control-label" style="width:15%" id="purchase_invoice_no" >Purchase Invoice No</label>
                        <div class="col-lg-3" style="width:120px">
                            <input type="text" name="invoice_no" id="invoice_no" class="form-control validate"  style="width: 100px;">
                        </div>
                        
                        <label class="col-lg-3 control-label" style="width:15%"  >Order No</label>
                        <div class="col-lg-3" style="width:120px">
                            <input type="text" name="orderno" id="orderno" class="form-control validate"  style="width: 100px;">
                        </div>
             		</div>                    
                    
              </div>
           
              <div class="modal-body">
                   <div class="form-group">
                    <label class="col-lg-3 control-label" style="width:15%" id="comp_name"> From Company Name</label>
                        <div class="col-lg-3" style="width:20%">
                            <input type="text" name="company_name" id="company_name" class="form-control validate">
                        </div>
                        
                         <label class="col-lg-3 control-label" style="width:5%;padding-left: 0px;"  > Date </label>
                        <div class="col-lg-3" style="width:30%">
               			 <input type="text" name="date" id="date" value="<?php echo date("Y-m-d");?>"  data-format="YYYY-MM-DD"  data-template="D MMM YYYY" 
                         placeholder="Date"  class="combodate form-control"/>
                		</div>
                        
                         <div id="description">
                         <label class="col-lg-3 control-label" style="width:10%;padding-left: 0px;"><span class="required">*</span>Description</label>
                     <div class="col-lg-3" style="width: 17%;">
                     <select name="description" id="description" class="form-control validate[required]" >
                      <option value="">Select</option>
                      <option value="1" id="sto">Store</option>
                      <option value="2" id="good">Goods Returned</option>
                      <option value="3" id="good">Dispatch</option>		         
                    </select>
                    </div>
                    </div>
                    
             		</div>
              </div>
             
				 <div class="modal-body">
              		
                   		<div class="modal-body">
                   		   <div class="form-group">
                            <label class="col-lg-3 control-label" style="width:15%"><span class="required">*</span>Quantity</label>
                            <div class="col-lg-3"  style="width:10%">
                                <input type="text" name="qty" id="qty" placeholder="Qty" class="form-control validate[required],custom[number]">
                            </div>      
                                <input type="hidden" name="status" id="status" value=""/>
                             </div>                    
                        </div>
          	</div>
            <!--	<center><div id="capacity"></div></center>-->
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="code_stock()" name="btn_submit1" class="btn btn-warning">save</button>
              </div>
   		</form>   
    </div>
  </div>
</div>



<!-- Close : validation script -->
<!-- Clone Model -->

<div class="modal fade" id="clone_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="clone_form" id="clone_form" style="margin-bottom:0px;">
              <div class="modal-header title">
               <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Clone Model : <span id="pro"></span></h4>
              </div>
           
              <div class="modal-body">
                   <div class="form-group">
                                  <!--<input type="hidden" name="product_code" id="product_code" value="" />
                                  <input type="hidden" name="product_code_id" id="product_code_id" value="" />-->
                		<label class="col-lg-3 control-label"  style="width:15%"  >Select Product</label>
                       <div class="col-lg-8">
                            <?php $products = $obj_product_code->getActiveProduct(); ?>
                            <select name="product" id="product" class="form-control validate[required]">
                            <option value="">Select Product</option>
                                <?php  foreach($products as $product){
                                    if(isset($product_code['product']) && $product_code['product'] == $product['product_id']){ ?>
                                        <option value="<?php echo $product['product_id']; ?>" selected="selected" ><?php echo $product['product_name']; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $product['product_id']; ?>" <?php if(isset($product_code['product']) && ($product_code['product'] == $product['product_id'])) { echo 'selected="selected"';}?> > <?php echo $product['product_name']; ?></option>
                                    <?php }
                                } ?>
                                 <?php if(isset($product_code['product']))
									$id=$product_code['product'];
								elseif(isset($product['product_id']))
									$id=$product['product_id'];
								?>
                            <?php /*?><option value="11" <?php if(isset($id) && ($id  == '11')) echo  'selected="selected"';?>>Plastic Scoop</option><?php */?>
                            </select>
                        </div><br>
                        
                    </div>
                 </div>       
                        
                 <div class="modal-body">
                   <div class="form-group">       
                        
                       <label class="col-lg-1 control-label" id="volume_from_label" >Copy From:</label> <label class="col-lg-1 control-label" id="volume_from_label" >Volume:</label>
                        <div class="col-lg-3" >
              		 	<input type="text" name="volume_from" style="margin-left: 20px;" id="volume_from" class="form-control validate" >
                        </div>
                        
                        <label class="col-lg-3 control-label" >Measurement:</label>
                        <div class="col-lg-3">
                            <?php $measurement = $obj_product_code->getMeasurement(); ?>
                            <select name="measurement_from" id="measurement_from" class="form-control validate[required]">
                            <option value="">Select Measurement</option>
                                <?php  foreach($measurement as $mea){
                                    if(isset($post['product']) && $post['product'] == $mea['product_id']){ ?>
                                        <option value="<?php echo $mea['product_id']; ?>" selected="selected" ><?php echo $mea['measuremant']; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $mea['product_id']; ?>" <?php if(isset($product_code_id) && ($mea['product_id'] == $product_code['measurement'])) { echo 'selected="selected"';}?> > <?php echo $mea['measurement']; ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
             		</div>                    
                    
              </div>
           
              <div class="modal-body">
                   <div class="form-group">
                   
                    <label class="col-lg-1 control-label" id="volume_from_label" >Copy To:</label><label class="col-lg-1 control-label" id="volume_from_label" >Volume:</label>
                        <div class="col-lg-3">
              		 	<input type="text" name="volume_to" style="margin-left: 20px;" id="volume_to" class="form-control validate" >
                        </div>
                        
                        <label class="col-lg-3 control-label" >Measurement:</label>
                        <div class="col-lg-3">
                            <?php $measurement = $obj_product_code->getMeasurement(); ?>
                            <select name="measurement_to" id="measurement_to" class="form-control validate[required]">
                            <option value="">Select Measurement</option>
                                <?php  foreach($measurement as $mea){
                                    if(isset($post['product']) && $post['product'] == $mea['product_id']){ ?>
                                        <option value="<?php echo $mea['product_id']; ?>" selected="selected" ><?php echo $mea['measuremant']; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $mea['product_id']; ?>" <?php if(isset($product_code_id) && ($mea['product_id'] == $product_code['measurement'])) { echo 'selected="selected"';}?> > <?php echo $mea['measurement']; ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    
             		</div>
              </div>
             
            <!--	<center><div id="capacity"></div></center>-->
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="clone_product_code()" name="btn_submit1" class="btn btn-warning">Clone</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<style>

.a-btn{
	background: #fecc5f;
    background: linear-gradient(top, #feda71 0%,#febb4a 100%);
	border: 1px solid #f5b74e;
    border-color: #f5b74e #e5a73e #d6982f;
    box-shadow: 0 1px 1px #d3d3d3, inset 0 1px 0 #fee395;  
    padding: 0px 80px 0px 10px;
	height: 38px;
	display: inline-block;
	position: relative;
	border-radius: 4px;
	float: left;
	margin: 10px;
	overflow: hidden;
	transition: all 0.3s linear;
}
.a-btn-text{
	padding-top: 5px;
	display: block;
	font-size: 18px;
	white-space: nowrap;
	color: #996633;
    text-shadow: 0 1px 0 #fedd9b;
	transition: all 0.3s linear;
}
.a-btn-slide-text{
	position:absolute;
	top: 35px;
	left: 0px;
	width: auto;
	right: 52px;
	height: 0px;
	background: #fff;
	color: #996633;
	font-size: 13px;
	white-space: nowrap;
	font-family: Georgia, serif;
	font-style: italic;
	text-indent: 15px;
	overflow: hidden;
	line-height: 30px;
	box-shadow: 
		-1px 0px 1px rgba(255,255,255,0.4), 
		1px 1px 1px rgba(0,0,0,0.5) inset;
	transition: height 0.3s linear;
}
.a-btn-icon-right{
	position: absolute;
	right: 0px;
	top: 0px;
	height: 100%;
	width: 52px;
	border-left: 1px solid #f5b74e;
	box-shadow: 1px 0px 1px rgba(255,255,255,0.4) inset;
}
.a-btn-icon-right span{
	width: 38px;
	height: 38px;
	opacity: 0.7;
	position: absolute;
	left: 65%;
	top: 70%;
	margin: -20px 0px 0px -20px;
	/*background: transparent url(../images/arrow_right.png) no-repeat 50% 55%;*/
    transition: all 0.3s linear;
}
.a-btn:hover{
	height: 65px;
	box-shadow: 0px 1px 1px rgba(255,255,255,0.8) inset, 1px 1px 5px rgba(0,0,0,0.4); 
}
.a-btn:hover .a-btn-text{
	text-shadow: 0px 1px 1px rgba(0,0,0,0.2);
	color: #fff;
}
.a-btn:hover .a-btn-slide-text{
	height: 30px;
}
.a-btn:hover .a-btn-icon-right span{
	opacity: 1;
	transform: rotate(-45deg);
}
.a-btn:active {
	position:relative;
	top:1px;
	background: linear-gradient(top, #fec354 0%,#fecd61 100%); /* W3C */
    border-color: #d29a3a #cc9436 #c89133;
    text-shadow: 0 1px 0 #fee1a0;
    box-shadow: 0 1px 1px #d4d4d4, inset 0 1px 0 #fed17e;  
}
/*.a-btn{
	background: #80a9da;
    background: linear-gradient(top, #80a9da 0%,#6f97c5 100%);
    padding-left: 20px;
    padding-right: 80px;
    height: 38px;
    display: inline-block;
    position: relative;
    border: 1px solid #5d81ab;
    box-shadow: 
		0px 1px 1px rgba(255,255,255,0.8) inset, 
		1px 1px 3px rgba(0,0,0,0.2), 
		0px 0px 0px 4px rgba(188,188,188,0.5);
    border-radius: 20px;
    float: left;
    clear: both;
    margin: 10px 0px;
    overflow: hidden;
    transition: all 0.3s linear;
}
.a-btn-text{
    padding-top: 5px;
    display: block;
    font-size: 18px;
    white-space: nowrap;
    text-shadow: 0px 1px 1px rgba(255,255,255,0.3);
    color: #446388;
    transition: all 0.2s linear;
}
.a-btn-slide-text{
    position:absolute;
    height: 100%;
    top: 0px;
    right: 52px;
    width: 0px;
    background: #63707e;
    text-shadow: 0px -1px 1px #363f49;
    color: #fff;
    font-size: 18px;
    white-space: nowrap;
    text-transform: uppercase;
    text-align: left;
    text-indent: 10px;
    overflow: hidden;
    line-height: 38px;
    box-shadow: 
		-1px 0px 1px rgba(255,255,255,0.4), 
		1px 1px 2px rgba(0,0,0,0.2) inset;
    transition: width 0.3s linear;
}
.a-btn-icon-right{
    position: absolute;
    right: 0px;
    top: 0px;
    height: 100%;
    width: 52px;
    border-left: 1px solid #5d81ab;
    box-shadow: 1px 0px 1px rgba(255,255,255,0.4) inset;
}
.a-btn-icon-right span{
    width: 38px;
    height: 38px;
    opacity: 0.7;
    position: absolute;
    left: 50%;
    top: 50%;
    margin: -20px 0px 0px -20px;
    background: transparent url(../images/arrow_right.png) no-repeat 50% 55%;
    transition: all 0.3s linear;
}
.a-btn:hover{
    padding-right: 180px;
    box-shadow: 0px 1px 1px rgba(255,255,255,0.8) inset, 1px 1px 3px rgba(0,0,0,0.2);
}
.a-btn:hover .a-btn-text{
    text-shadow: 0px 1px 1px #5d81ab;
    color: #fff;
}
.a-btn:hover .a-btn-slide-text{
    width: 100px;
}
.a-btn:hover .a-btn-icon-right span{
    opacity: 1;
}
.a-btn:active {
    position: relative;
    top: 1px;
    background: #5d81ab;
    box-shadow: 1px 1px 2px rgba(0,0,0,0.4) inset;
    border-color: #80a9da;
}*/
</style>


<script type="application/javascript">
/*function download()
{
	//alert("jjiji");
}*/


function store(i,product_code,status)
{
	$("#stock_mang").modal("show");
	var p_code = $("#p_code_"+i).val();
	//alert(p_code);
	$("#pro").html(p_code);
	
	
	$('#product_code').val(p_code);
	$('#product_code_id').val(product_code);
	$('#status').val(status);
	if(status==0)
	{
		$("#description option[value='3']").hide();
		$("#description option[value='1']").show();
		$("#description option[value='2']").show();	
		$("#purchase_invoice_no").html('Purchase Invoice No');
		$("#comp_name").html('From Company Name');
	}
	else
	{		
		$("#description option[value='3']").show();
		$("#description option[value='1']").hide();
		$("#description option[value='2']").hide();	
		$("#purchase_invoice_no").html('Sales Invoice No');
		$("#comp_name").html('To Company Name');
	}
}
function csvlink(elemName){
		elem = document.getElementsByName(elemName);
		var flg = false;
		for(i=0;i<elem.length;i++){
			if(elem[i].checked)
			{
				flg = true;
				break;
			}
		}
	if(flg)
	{
		var csv_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=csvInvoice', '',1);?>");
		var formData = $("#form_list").serialize();	
		$.ajax({
				url : csv_url,
				type :'post',
				data :{formData:formData},
				success: function(re){
				//console.log(re);
			//$('#test').html(re);
				  csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(re);	
				 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'Product_code_list.csv',
							'href': csvData,
							'target': '_blank'
					}).appendTo('body');
					$('#downloadFile').ready(function() {
						$('#downloadFile').get(0).click();
					});
					
				},
				error:function(){
					//set_alert_message('Error During Updation',"alert-warning","fa-warning");          
				}						
			});		
	}
	else
	{
		//alert("Please select atlease one record");
		$(".modal-title").html("WARNING");
		$("#setmsg").html('Please select atlease one record');
		$("#popbtnok").hide();
		$("#myModal").modal("show");
	}
	
}
	$('input[type=radio][name=status]').change(function() {
	
		var product_code_id=$(this).attr('id');
		var status_value = this.value;
		
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateProductStatus', '',1);?>");
        $.ajax({
			
			url : status_url,
			type :'post',
			data :{product_code_id:product_code_id,status_value:status_value},
			success: function(){
				set_alert_message('Successfully Updated',"alert-success","fa-check");
				window.setTimeout(function(){location.reload()},100)
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");
				window.setTimeout(function(){location.reload()},100)
			}
			
		});
    });

		//mansi
	function code_stock()
	{
			
			var formData = $("#sform").serialize();
			//alert(formData);
				var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=code_stock', '',1);?>");
				$.ajax({
					url : product_code_url,
					method : 'post',
					data : {formData : formData},
					success: function(response){
					//$('#sform').reset();
					//alert(response);
					set_alert_message('Successfully Added',"alert-success","fa-check");
					window.setTimeout(function(){location.reload()},100)
					},
					error: function(){
						return false;	
					}
				});
		
	}	
	function CloneData() 
	{
		$("#clone_data").modal('show');
	}
	function clone_product_code()
	{
			
			var formData = $("#clone_form").serialize();
			//alert(formData);
				var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=clone_Product_code', '',1);?>");
				$.ajax({
					url : product_code_url,
					method : 'post',
					data : {formData : formData},
					success: function(response){
						//alert(response);
						set_alert_message('Your Data Is Successfully Cloned',"alert-success","fa-check");
						window.setTimeout(function(){location.reload()},100)
					},
					error: function(){
						return false;	
					}
				});
		
	}
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>