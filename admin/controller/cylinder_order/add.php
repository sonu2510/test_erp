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
	'href' 	=> $obj_general->link($rout, 'mod=index&status=0', '',1),
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

//Start : edit

//Close : edit
if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
				
		//printr($post);die;
		$_POST['order_id']=decode($_GET['order_id']);
		$post = post($_POST);
		//printr($_POST);
		//die;
		$insert_id = $obj_cylinder->addCylinder($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	if(isset($_POST['btn_generate'])){
		page_redirect($obj_general->link($rout, '&mod=index&status=0', '',1));
	}
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		$order_no = $cylinder['order_id'];
		$obj_cylinder->updateCylinder($order_id,$post);
		$obj_session->data['success'] = UPDATE;
		if(isset($obj_session->data['page'])){
			$pageString = '&page='.$obj_session->data['page'];
			unset($obj_session->data['page']);
		}else{
			$pageString = '';
		}
		page_redirect($obj_general->link($rout, $pageString.'&filter_edit='.$_GET['filter_edit'], '',1));
	}
		$cond='AND status!=2';
	  $cylinderdetails = $obj_cylinder->cylinderdetails(decode($_GET['order_id']),$cond);
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
        
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
           	<?php if(!empty($cylinderdetails))
			  $cond = 'AND';
			  else
			  $cond = 'OR';
			  $size= $obj_cylinder->getOrderProducts(decode($_GET['order_id']),$cond);
			 // printr($size); die;?>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Order No </label>
                <div class="col-lg-4">
               		<input type="text" name="order_nodis" value="<?php echo $size[0]['order_number'];?>" class="form-control validate[required]" disabled="disabled">
                   </div>
              </div>
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Company Name </label>
                <div class="col-lg-4">
                  	<input type="text" name="company_namedis" value="<?php echo isset($size[0]['company_name'])?$size[0]['company_name']:'';?>" class="form-control validate[required]" disabled="disabled">
                    <input type="hidden" name="company_name" value="<?php echo isset($size[0]['company_name'])?$size[0]['company_name']:'';?>"  />
                    <input type="hidden" name="order_no" value="<?php echo decode($_GET['order_id'])?>" class="form-control validate[required]">
                </div>
              </div>
              <div class="form-group">
              <?php /*foreach($cylinderdetails as $cylinder)
			  		{
						/*if($cylinder['width']!=$value['width'])
							$width = $value['width'];
						if($cylinder['height']!=$value['height'])
							$height = $value['height'];
						if($cylinder['gusset']!=$value['gusset'])
							$gusset = $value['gusset'];
*/					?>
						
						
                <label class="col-lg-3 control-label"><span class="required">*</span>Size</label>
                <div class="col-lg-4">
                   <select  name="size" id="size" class="form-control validate[required]">
                   <option value="">Select Dimension</option>
                   <?php foreach($size as $key=>$value)
				   {?>
					 <option value="<?php echo $value['width'].' X '.$value['height'].' X '.$value['gusset']; ?>"> <?php echo 
					 $value['width'].' X '.$value['height'].' X '.$value['gusset'];?></option>
				   <?php }?>
                 
                 </select>
                </div>
              </div>
             	<div class="form-group">
               	<label class="col-lg-3 control-label">Description</label>
               	<div class="col-lg-7">
                <textarea name="discription" placeholder="description" id="input-name2" class="form-control"><?php echo isset($cylinder['discription'])?$cylinder['discription']:'';?></textarea>
               
               </div>
               </div>
              
              
                <div class="form-group">
                <label class="col-lg-3 control-label">Posting Date</label>
                <div class="col-lg-7">
                  <input type="text" name="cylinder_date" value="" 
                  class="input-sm form-control " data-date-format="yyyy-mm-dd" readonly="readonly"  id="cylinder_date"/>
                </div>
                </div>
                
                <div class="form-group">
            <label class="col-lg-3 control-label">Estimated Receive Date</label>
            <div class="col-lg-7">
              <input type="text" name="receive_date"  value="" class="input-sm form-control " data-date-format="yyyy-mm-dd" readonly="readonly" id="receive_date"/>
            </div>  
            </div>
            
                 <?php $vanders= $obj_cylinder->getVanderName(); //($vanders);?>
                  <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Vender Name</label>
                <div class="col-lg-7">
                  <select name="vander_name" id="vander_name" class="form-control validate[required]">
                  <option>Select Vender Name</option>
				  <?php foreach($vanders as $key=>$value)
				   {?>
					 <option value="<?php echo $value['vander_id']; ?>"><?php echo $value['vander_first_name'].' '.$value['vander_last_name'];?></option>
                   <?php }?>
                  
                  </select>
                </div>
              </div>
                <?php /*?>  <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-7">  <select class="form-control" name="status">
                        <option value="1" <?php echo (isset($cylinder['status']) && $cylinder['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($cylinder['status']) && $cylinder['status'] == 0)?'selected':'';?>> Inactive</option>
                        </select>
                </div>  
            </div><?php */?>
            
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
               
                	<button type="button" name="btn_add" id="btn_add" class="btn btn-primary" onclick="enablegenerate()">Add </button>	
              
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '&mod=index&status=0', '',1);?>">Cancel</a>
                </div>
              </div>
              <div id="result"> 
              <div class="form-group">
								<div class="col-lg-12">
									<?php  if($cylinderdetails)
										  {?>
                                          <section class="panel">
									  <div class="table-responsive">
                                      
										<table class="table table-striped b-t text-small">
										  <thead>
											  <tr>
                                                <th>Company name</th>
                                             <th>Dimension </th><th>Description</th>
                                              <th>Cylinder Date</th>
                                                <th>Vender Name</th>
                                                <th>Receive Date </th>
                                                 <th></th></tr>
										  </thead>
                                          <tbody>
                                          <?php 
										foreach($cylinderdetails as $k=>$cylinder_data)
										{
                                          	//printr($cylinder_data);
											//die;
											?>
											
                                                 <tr><td><?php echo $cylinder_data['company_name'];?></td>
                                                     <td><?php echo $cylinder_data['width'].'X'.$cylinder_data['height'].'X'.
																$cylinder_data['gusset'];?>
                                                                <input type="hidden" name="dimension" id="dimension" value="<?php echo $cylinder_data['width'].'X'.$cylinder_data['height'].'X'.$cylinder_data['gusset'];?>" /></td>
                                                     <td><?php echo $cylinder_data['discription'];?></td>
													<td><?php echo $cylinder_data['cylinder_date'];?></td>
                                                   <?php $vender_name=$obj_cylinder->getvander($cylinder_data['vander_name']);?>
													<td><?php echo $vender_name['vander_first_name'].' '.$vender_name['vander_last_name'];?></td>
													<td><?php echo $cylinder_data['receive_date'];?></td>
															<?php /*?><?php
																if($cylinder_data['status']==1)
																$status='Active';
																else
																$status='Inactive';
															?>
															<td><?php echo $status;?></td><?php */?>
                                                            <td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeProduct(<?php echo $cylinder_data['order_id'];?>)"><i class="fa fa-trash-o"></i></a></td>
                                                            
							                                                                              
                                                    <div class="modal fade" id="myModal<?php echo $cylinder_data['order_id'];?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title<?php echo $cylinder_data['order_id'];?>">Title</h4>
            </div>
            <div class="modal-body">
                <p id="setmsg<?php echo $cylinder_data['order_id'];?>">Message</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="popbtncan<?php echo $cylinder_data['order_id'];?>" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" name="popbtnok" id="popbtnok<?php echo $cylinder_data['order_id'];?>" class="btn btn-primary">Ok</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div></tr>
                                              <?php                                                     
                                                }
												?>
                                          
                                            </tbody>
										</table>
                                      </div>
                                        
                                       
									</section>  <?php }?>
								</div>
                           
							  </div>
                 		</div>
                                 <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3" id="cylinder_generate" style="display:none">
                         <button type="submit" name="btn_generate" id="btn_generate" class="btn btn-primary">Generate </button>	
                          </div>
                      </div>
            </form>
          </div>
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
jQuery(document).ready(function(){
 // binds form submission and fields to the validation engine
	    var nowTemp = new Date();
		//alert(nowTemp);
	    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		//alert(now);
	    var checkin = $('#cylinder_date').datepicker({
   			onRender: function(date) {
    		return date.valueOf() < now.valueOf() ? '' : '';
    		}
    	}).on('changeDate', function(ev) {
			if (ev.date.valueOf() > checkout.date.valueOf()) {
				var newDate = new Date(ev.date)
				//alert(now);
          				newDate.setDate(newDate.getDate() + 1);
    			checkout.setValue(newDate);
    		}
    		checkin.hide();
    		$('#receive_date')[0].focus();
    	}).data('datepicker');
    	var checkout = $('#receive_date').datepicker({
    		onRender: function(date) {
				if( (date.valueOf() <= checkin.date.valueOf()) )
						return 'disabled';
					else
						return '';
				
    		}
    	}).on('changeDate', function(ev) {
    		checkout.hide();
    	}).data('datepicker');
});
	$("#btn_add").click(function(){
		//alert("hi");
		
			if($("#form").validationEngine('validate')){
			
			//alert($("#dimension").val());
			//alert($("#size").val());
			var formData = $("#form").serialize();
			//alert($('#size').val().hide());
			var size =$('#size').val();
			$("#size option[value='"+size+"']").hide();
			$("#size option[value='']").attr("selected","selected");
			var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=addCylinderOrder', '',1);?>");			
			$.ajax({
					method: "POST",					
					url: url,
					data : {formData : formData},
					success: function(response){												
						//alert(response);	
						$("#result").html(response);	
						$("#qoute_generate").show();					
					},
					error: function(){
							return false;	
					}
				});
			}
			else
			{
				return false;
			}
		});

  	$("#receive_date").on('changeDate', function(ev){
		//alert($("#c_date"));
		$("#receive_date").datepicker('hide');
		});
	
	
	$("#cylinder_date").on('changeDate', function(ev){
		//alert($("#c_date"));
		$("#cylinder_date").datepicker('hide');
		});
	
	function removeProduct(order_product_id){
		
		
$("#myModal"+order_product_id).modal("show");

$(".modal-title"+order_product_id).html("Delete product".toUpperCase());

$("#setmsg"+order_product_id).html("Are you sure you want to delete ?");
$("#popbtnok"+order_product_id).click(function(){
//	var con = confirm("Are you sure you want to delete ?");
//alert("hi");

		alert(order_product_id);
	var remove_product_url = getUrl("<?php  echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeProduct', '',1);?>");
	$.ajax({
		url : remove_product_url,
		method : 'post',
		data : {order_product_id : order_product_id},
		success: function(response){
			alert(response);
		if(response == 0) 
		{
			$("#myModal"+order_product_id).hide();
		}		
		reloadPage();
		},
		error: function(){
			return false;	
		}
	});
$("#myModal"+order_product_id).hide();
$("#myModal"+order_product_id).modal("hide");
 });
}
function reloadPage(){
	location.reload();
}
function enablegenerate(){
	$("#cylinder_generate").show();
}

</script> 


<!-- Close : validation script -->
<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>