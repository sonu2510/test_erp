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
$class = 'collapse';
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

	if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			//printr($_POST['post']);die;
			$obj_rack_master->updatestock($_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}
	
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
                    <span class="text-muted m-l-small pull-right">
            			 <?php
					if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1'){ ?>       
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>
                    </span>
                 </header>
                 <div class="panel-body">
              <form class="form-horizontal" method="post" data-validate="parsley" id="s-form">
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
                                <input type="hidden" name="stock_id" id="stock_id" value="<?php echo $stock_id;?>" class="form-control validate">
                              </div>
                              </div>                             
                          
                              <div class="form-group">
                                <label class="col-lg-4 control-label" >Purchase Invoice No</label>
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
                                 <label class="col-lg-4 control-label">From Company Name</label>
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
                                 <label class="col-lg-4 control-label">Product Code</label>
                                <div class="col-lg-6">
                                	<input type="text" name="product_code" id="product_code" class="form-control validate">
                                </div>
                              </div>
                          </div> 
                          </div>
                      
                
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                       <a href="#"class="btn btn-primary btn-sm pull-right ml5" onclick="searchRecords()" ><i class="fa fa-search"></i> Search</a>
                        <a href="<?php echo $obj_general->link($rout, '&mod=view_dispatch&stock_id='.$_GET['stock_id'], '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                                    
              </section>
         	</form>
          </div>
                 	<div class="panel-body" id="result_div">
                		<form name="form_list" id="form_list" method="post">
                           <input type="hidden" id="action" name="action" value="" />
                            <div class="table-responsive">
                                <table id="quotation-row" class="table b-t text-small table-hover">
                                  <thead>
                                    <tr>
                                    <?php if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1') { ?>
                                      <th></th>
                                     <?php } ?>
                                      <th>Sr. No.</th> 
                                      <th>Product Name</th>
                                      <th>Company Name</th>
                                       <th>Invoice No</th>      
                                      <th>Proforma No</th>                    
                                      <th>Descripton</th>
                                      <th>Qty</th>
                                      <?php
									   if(isset($obj_session->data['LOGIN_USER_TYPE']) && ($obj_session->data['LOGIN_USER_TYPE']==4 || 
									  $obj_session->data['LOGIN_USER_TYPE']==1))
									  {
									  	echo '<th>Price</th>';
									  }?>             
                            
                                      <th>Date</th>
                                      <th>Posted By</th>
                                   	</tr>
                                  </thead>
                                  <tbody>

                    	    
						 <?php 
					$slNo=1;$i=1;
						 $stock_dis_data = $obj_rack_master->getdispatchdetail($user_id,$user_type_id,$stock_id);
						 //printr($stock_dis_data);
						 //die;
						 if(isset($stock_dis_data) && !empty($stock_dis_data)){
							 foreach($stock_dis_data as $stock){ 
							 //printr($i);
							 $desc = $obj_rack_master->getProductCode($stock['product_code_id']);
							//printr($stock['stock_id']);				 							 
							 $dispatch_qty=$obj_rack_master->gettotaldispatchChild($stock['stock_id']);
							 //printr($dispatch_qty);
							/*$zipper_name=$obj_rack_master->getzipper($stock['zipper_id']);
							 $spout_name=$obj_rack_master->getSpout($stock['spout_id']);
								$accessorie_name=$obj_rack_master->getAccessorie($stock['accessorie_id']);
								$make_name=$obj_rack_master->getMake($stock['make_id']);
								$color_name=$obj_rack_master->getColor($stock['color_id']);
								$size_name=$obj_rack_master->getSize($stock['size_id']);*/
						
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
                              <?php if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1') { ?>
                               <td><input type="checkbox" name="post[]" value="<?php echo $stock['stock_id'];?>"></td>
                                <?php } ?>
                               <td width="1%"><?php echo $slNo++;?></td>
                               <td width="20%">
								<?php echo $desc['product_code'].'<b><br>'.$desc['description'].'</b>'; ?> 
							  </td>
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
                                <?php if(isset($obj_session->data['LOGIN_USER_TYPE']) && ($obj_session->data['LOGIN_USER_TYPE']==4 || $obj_session->data['LOGIN_USER_TYPE']==1))
									  {
									 	 echo '<td><input type="text" name="price_'.$i.'" id="price_'.$i.'" value="'.$stock['price'].'" class="form-control" onblur="edit_price('. $i.')" style="  width: 70px; "><input type="hidden" name="stock_id_'.$i.'" id="stock_id_'.$i.'" value="'.$stock['stock_id'].'" class="form-control" ></td>';
										 $i++;
									  }?>
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
                            
                            <?php if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1') { ?>
							 <td><input type="checkbox" name="post[]" value="<?php echo $child['stock_id'];?>"></td>
                             <?php } ?>
                             <td width="1%"><?php //echo $slNoC++;?></td>
                             <td width="20%">
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
                               <?php if(isset($obj_session->data['LOGIN_USER_TYPE']) && ($obj_session->data['LOGIN_USER_TYPE']==4 || $obj_session->data['LOGIN_USER_TYPE']==1))
									  {
                            echo '<td></td>';
							  }?>
                           	<td>
                              	<?php echo dateFormat(4,$child['date_added']); ?>
							  </td>
							  <td> 
                             
								<a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $postedByInfoChild;?>' title="" data-original-title="<b><?php echo $postedByNameChild;?></b>"><?php echo $postedByDataChild['user_name'];?></a>
							  <?php //echo $postedByData['user_name'];//$postedByData['first_name'].' '.$postedByData['last_name'];?></td>
                              
							</tr>
							<?php }
							 }
							 }
							 }
                         else{
						 echo "No record Found";
						 }
                        ?>
                        </tbody>
                             </table>
                             <div class="form-group">
                            <div class="col-lg-9 col-lg-offset-3">  
                            <?php 
							//printr($stock_dis_data[0]['row']);
							$rowcol=$stock_dis_data[0]['row'].'-'.$stock_dis_data[0]['column_name'];
								  $goods_id=$stock_dis_data[0]['goods_id'];
							?>            
                                <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '&mod=rack_detail&data='.$rowcol.'&goods_id='.encode($goods_id), '',1);?>">Cancel</a>
                            </div>
                        </div>
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

function searchRecords()
{
	var label_url = getUrl("<?php  echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=searchRecords', '',1);?>");
	var formData = $("#s-form").serialize();
	$.ajax({
		type: "POST",
		url: label_url,
		data:{formData : formData}, 
		success: function(response) {
		//	alert(response);
			$('#result_div').html(response);
		}
	});
}
function edit_price(id)
{
	var  postArray = {};
	postArray['price'] = $("input[type=text][id=price_"+id+"]").val();
	postArray['stock_id'] = $("#stock_id_"+id).val();
	var price_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=update_price', '',1);?>");
		$.ajax({
			url : price_url,
			method : 'post',
			data : {postArray : postArray},
			success: function(response){//alert(response);
			set_alert_message('Successfully Updated',"alert-success","fa-check");
			 window.setTimeout(function(){location.reload()},1000)
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
