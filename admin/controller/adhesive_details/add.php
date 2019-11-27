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

//Start : edit
$edit = '';

if(isset($_GET['adhesive_id']) && !empty($_GET['adhesive_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$adhesive_id = base64_decode($_GET['adhesive_id']);
		$adhesive_details_all = $obj_adhesive->getAdhesive_All($adhesive_id);
	
		$adhesive_details = $obj_adhesive->AdhesiveDetails($adhesive_id,0);
		$hardner_details = $obj_adhesive->AdhesiveDetails($adhesive_id,1);
		$ethyle_details = $obj_adhesive->AdhesiveDetails($adhesive_id,2);
   
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);	
		$insert_id = $obj_adhesive->addAdhesive($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);

		$adhesive_id = base64_decode($_GET['adhesive_id']);
		$obj_adhesive->updateAdhesive($post,$adhesive_id);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	$job_latest_id=0;
	$latest_lamination_id = $obj_adhesive->getlatestAdhesiveid();
	//printr($latest_lamination_id);
	if(!empty($latest_lamination_id))
		$job_latest_id=$latest_lamination_id;
	
	
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
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              <div class="form-group">
			    <label class="col-lg-2 control-label"><span class="required">*</span>Adhesive No</label>
                <div class="col-lg-2">
                  	 <input type="hidden" name="edit_value" id="edit_value" value="<?php echo $edit;?>" />
               
                  	<input type="text" name="adhesive_no" readonly="readonly" value="<?php echo isset($adhesive_details_all['adhesive_no'])?$adhesive_details_all['adhesive_no']:$job_latest_id+1;?>" class="form-control validate[required]">
                </div>
                 <label class="col-lg-2 control-label">Job Date</label>
					<div class="col-lg-2">
						<input type="text" name="job_date" id="job_date" data-date-format="yyyy-mm-dd" value="<?php if(isset($adhesive_details_all['date'])){ echo $adhesive_details_all['date']; }else{ echo date("Y-m-d"); }  ?>" class="form-control  datepicker">
					</div>  
                <label class="col-lg-2 control-label">Shift</label>
                <div class="col-lg-2">
                  	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="shift" value="Day" checked="checked" <?php if(isset($adhesive_details_all) && ($adhesive_details_all['shift'] == '1')) { echo 'checked=checked'; } ?>/> Day
                              </label>
                            
                                <label style="font-weight: normal;">
                                  	<input type="radio" name="shift" value="Night" <?php if(isset($adhesive_details_all) && ($adhesive_details_all['shift'] == '0')) { echo 'checked=checked'; }?> />Night
                              </label>
                      </div>
                </div>
              </div>
              
               <div class="form-group">
            
				
				 <label class="col-lg-2 control-label"><span class="required">*</span>Machine Name</label>
					<div class="col-lg-3">
						<?php $machines = $obj_adhesive->getMachine();?>
						<select name="machine_id" id="machine_id" class="form-control validate[required]">
							
								<?php
								//printr( $adhesive_details_all['machine_id']);
								foreach($machines as $machine){ ?>
									<option value="<?php echo $machine['machine_id']; ?>"
									<?php if(isset($adhesive_details_all) && ($machine['machine_id'] == $adhesive_details_all['machine_id'])) { echo 'selected="selected"';}?> > <?php echo $machine['machine_name']; ?></option>
									<?php } ?> 
						 </select>
					</div>
				  
					
				  </div>
              
                <div class="form-group">
                        <label class="col-lg-2 control-label"><span class="required">*</span>Operator Name</label>
                        <div class="col-lg-3">
                            <?php  $operators = $obj_adhesive->getOperatorName();
//                            printr($operators);?>
                            <select name="operator_id" id="machine_id" class="form-control validate[required]">
                                <option value="">Select Operator Name</option>
                                <?php foreach ($operators as $operator) { ?>
                                    <option value="<?php echo $operator['employee_id']; ?>"<?php echo(isset($adhesive_details_all['operator_id'])&& $adhesive_details_all['operator_id']==$operator['employee_id'])?'selected':'';?>> <?php echo $operator['user_name']; ?></option>
                                        <?php } ?> 
                            </select>
                        </div>
						
					
                    </div>
                 
            
						  
			  
			  <div class="form-group">
			  	<div class="line m-t-large"></div>		
					<label class="col-lg-2 control-label">Adhesive Details</label> 
                    <div class="col-lg-4">
                        <section class="panel">
                          <div class="table-responsive">
                            <table class="tool-row table-striped  b-t text-small " id="adhesive" width="100%">
                              <thead>
                                  <tr>
                                      
                                        <th><span class="required">*</span>Product Name</th>                                      
                                        <th><span class="required">*</span>Use</th>
                                   
                                  </tr>
                              </thead>
                              <?php 
//                              printr($adhesive_details);
                              if($edit=='1' && $adhesive_details){
                                  $adhesive_data = $adhesive_details;
//                                  printr($adhesive_data);
                              }else{
                                  $ink_array=array();
                                  $adhesive_data[] = array(
                                          'ink_process_id' =>'' ,
                                          'ink_process_detail_id'=>'',
                                          'product_item_id' => '',                                        
                                          'used' => '',
                                          'remark' => '', 
										  'm_status'=>'0'
                                      );
                              }
                              ?>
                               <?php 
                               $inks = $obj_adhesive->getAdhesive_details();
						//		printr(decode($_GET['ink_process']));
								 if(!empty($adhesive_data)){
                                    $inner_count = 1;
                                      foreach($adhesive_data as $ad){ 
									//  printr($ad);?>
									  
                              <tbody>                                    
                                <tr class="adhesive_details-<?php echo $inner_count; ?>"id="adhesive_details-<?php echo $inner_count; ?>">
                                   
                                    <input type="hidden" name="adhesive_id" value="<?php echo isset($ad)?$ad['adhesive_id']:'';?>">
                                   <input type="hidden" name="adhesive_details[<?php echo $inner_count; ?>][m_status]"   id="m_status"  value="<?php echo(isset($har))?$har['m_status']:'0';?>">
                                    <input type="hidden" name="adhesive_details[<?php echo $inner_count; ?>][adhesive_material_id]" value="<?php echo(isset($ad))?$ad['adhesive_material_id']:'';?>">
                                    <td> <input type="hidden" id="min_arr" value='<?php echo json_encode($inks);?>' />
										<div>
                                          <select name="adhesive_details[<?php echo $inner_count; ?>][product_item_id]" id="adhesive_details[product_item_id][<?php echo $inner_count;?>]" class="form-control validate[required] chosen_data">
                                                        <option value="">Select Adhesive</option>
                                                        <?php  foreach($inks as $code)
                                                           {
                                                               if($code['product_item_id']==$ad['product_item_id'])
                                                                echo '<option value="'.$code['product_item_id'].'" selected="selected">'.$code['product_name'].'</option>';
                                                               else
                                                                    echo '<option value="'.$code['product_item_id'].'">'.$code['product_name'].'</option>';
                                                           }
                                                           ?>
                                                           ?>
                                                    </select>
                                            </div>
										
										</div>
                                    </td>
                                   
                                    <td><input type="text" name="adhesive_details[<?php echo $inner_count ;?>][use]"  id="ad_used_<?php echo $inner_count ;?>" value="<?php echo(isset($ad))?$ad['used']:'';?>" placeholder="kg" onchange="get_total_details(<?php echo $inner_count;?>,0)" class="form-control validate[required,custom[number]]"></td>
                                   
                                    <?php if($inner_count==1){ ?>
                                    <td>
                                        <a class="btn btn-success btn-xs btn-circle addmore_adhesive" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Profit"><i class="fa fa-plus"></i></a>
                                    </td>
                                    <?php }else{
                                     ?>
                                    <td>
                                        <a onclick="remove_row(<?php echo  $inner_count.','. $ad['adhesive_material_id'];?>,0)" data-original-title="Remove" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title=""> 
                                             <i class="fa fa-minus"></i>
                                        </a>
                                    </td>    
                                    <?php
                                    } ?>
                                </tr>
                                <?php $inner_count++; }} ?>
                               </tbody>
                             </table>
                            </div>
						
                           </section>
                </div>
						
			  </div>
			<div class="form-group">
				<div class="line m-t-large"></div>		
					<label class="col-lg-2 control-label">Hardner Details</label> 
                    <div class="col-lg-4">
                        <section class="panel">
                          <div class="table-responsive">
                            <table class="tool-row table-striped  b-t text-small " id="hardner" width="100%">
                              <thead>
                                  <tr>
                                       
                                        <th><span class="required">*</span>Product Name</th>
                                  
                                        <th><span class="required">*</span>Use</th>
                                     
                                  </tr>
                              </thead>
                              <?php 
//                              printr($adhesive_details);
                              if($edit=='1' && $hardner_details){
                                  $hardner_details = $hardner_details;
//                                  printr($adhesive_data);
                              }else{
                                  $hardner_details=array();
                                  $hardner_details[] = array(
                                          'ink_process_id' =>'' ,
                                          'ink_process_detail_id'=>'',
                                          'product_item_id' => '',                                        
                                          'used' => '',
                                          'remark' => '',
										   'm_status'=>'1'
                                      );
                              }
                              ?>
                               <?php 
                               $inks = $obj_adhesive->getHardner_details();
						//		printr(decode($_GET['ink_process']));
								 if(!empty($hardner_details)){
                                    $inner_count = 1;
                                      foreach($hardner_details as $har){ ?>
                              <tbody>                                    
                                <tr class="hardner_details-<?php echo $inner_count; ?>"id="hardner_details-<?php echo $inner_count; ?>">
                                   
                                    <input type="hidden" name="adhesive_id" value="<?php echo isset($har)?$har['adhesive_id']:'';?>">
									<input type="hidden" name="hardner_details[<?php echo $inner_count; ?>][m_status]" id="m_status"   value="<?php echo(isset($har))?$har['m_status']:'1';?>">
                                    <input type="hidden" name="hardner_details[<?php echo $inner_count; ?>][adhesive_material_id]" value="<?php echo(isset($har))?$har['adhesive_material_id']:'';?>">
                                    <td> <input type="hidden" id="min_arr" value='<?php echo json_encode($inks);?>' />
										<div>
                                          <select name="hardner_details[<?php echo $inner_count; ?>][product_item_id]" id="hardner_details[product_item_id][<?php echo $inner_count;?>]" class="form-control validate[required] chosen_data">
                                                        <option value="">Select Hardner</option>
                                                        <?php  foreach($inks as $code)
                                                           {
                                                               if($code['product_item_id']==$har['product_item_id'])
                                                                echo '<option value="'.$code['product_item_id'].'" selected="selected">'.$code['product_name'].'</option>';
                                                               else
                                                                    echo '<option value="'.$code['product_item_id'].'">'.$code['product_name'].'</option>';
                                                           }
                                                           ?>
                                                    </select>
                                            </div>
										
										</div>
                                    </td>
                                   
                                    <td><input type="text" name="hardner_details[<?php echo $inner_count ;?>][use]" value="<?php echo(isset($har))?$har['used']:'';?>" id="har_used_<?php echo $inner_count; ?>" placeholder="kg"  onchange="get_total_details(<?php echo $inner_count ;?>,1)" class="form-control validate[required,custom[number]]"></td>
                                    
                                    <?php if($inner_count==1){ ?>
                                    <td>
                                        <a class="btn btn-success btn-xs btn-circle addmore_hardner" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Profit"><i class="fa fa-plus"></i></a>
                                    </td>
                                    <?php }else{
                                     ?>
                                    <td>
                                        <a onclick="remove_row(<?php echo  $inner_count.','. $har['ink_process_detail_id'];?>,1)" data-original-title="Remove" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title=""> 
                                             <i class="fa fa-minus"></i>
                                        </a>
                                    </td>    
                                    <?php
                                    } ?>
                                </tr>
                                <?php $inner_count++; }} ?>
                               </tbody>
                             </table>
                            </div>
					
                           </section>
                   </div>
				  
	  </div>
			<div class="form-group">
					<div class="line m-t-large"></div>	   
					<label class="col-lg-2 control-label">Product  Details</label> 
                    <div class="col-lg-4">
                        <section class="panel">
                          <div class="table-responsive">
                            <table class="tool-row table-striped  b-t text-small " id="ethyle" width="100%">
                              <thead>
                                  <tr>
                                      
                                        <th><span class="required">*</span>Ethyle  Name</th>
                                       
                                        <th><span class="required">*</span>Use</th>
                                      
                                  </tr>
                              </thead>
                              <?php 
//                              printr($adhesive_details);
                              if($edit=='1' && $ethyle_details){
                                  $ethyle_data = $ethyle_details;
//                                  printr($adhesive_data);
                              }else{
                                  $ink_array=array();
                                  $ethyle_data[] = array(
                                         'ink_process_id' =>'' ,
                                          'ink_process_detail_id'=>'',
                                          'product_item_id' => '',                                        
                                          'used' => '',
                                          'remark' => '',
										  'm_status'=>'2'
										 
                                      );
                              }
                              ?>
                               <?php 
                               $inks = $obj_adhesive->getEthyle_details();
						//		printr(decode($_GET['ink_process']));
								 if(!empty($ethyle_data)){
                                    $inner_count = 1;
                                      foreach($ethyle_data as $eth){ ?>
                              <tbody>                                    
                                <tr class="ethyle_details-<?php echo $inner_count; ?>" id="ethyle_details-<?php echo $inner_count; ?>">
                                  
                                    <input type="hidden" name="adhesive_id" value="<?php echo isset($eth)?$eth['adhesive_id']:'';?>">
									<input type="hidden" name="ethyle_details[<?php echo $inner_count; ?>][m_status]" id="m_status" value="<?php echo(isset($eth))?$eth['m_status']:'2';?>">                                   
								   <input type="hidden" name="ethyle_details[<?php echo $inner_count; ?>][adhesive_material_id]" value="<?php echo(isset($eth))?$eth['adhesive_material_id']:'';?>">
                                    <td> <input type="hidden" id="min_arr" value='<?php echo json_encode($inks);?>' />
										<div>
                                          <select name="ethyle_details[<?php echo $inner_count; ?>][product_item_id]" id="ethyle_details[product_item_id][<?php echo $inner_count;?>]" class="form-control validate[required] chosen_data">
                                                        <option value="">Select Ethyle</option>
                                                        <?php  foreach($inks as $code)
                                                           {
                                                               if($code['product_item_id']==$eth['product_item_id'])
                                                                echo '<option value="'.$code['product_item_id'].'" selected="selected">'.$code['product_name'].'</option>';
                                                               else
                                                                    echo '<option value="'.$code['product_item_id'].'">'.$code['product_name'].'</option>';
                                                           }
                                                           ?>
                                                    </select>
                                            </div>
										
										</div>
                                    </td>
                                   
                                    
                                    <td><input type="text" name="ethyle_details[<?php echo $inner_count ;?>][use]"  id="eth_used_<?php echo $inner_count; ?>"  value="<?php echo(isset($eth))?$eth['used']:'';?>" placeholder="kg"  onchange="get_total_details(<?php echo $inner_count ;?>,2)"class="form-control validate[required,custom[number]]"></td>
                                   
                                    <?php if($inner_count==1){ ?>
                                    <td>
                                        <a class="btn btn-success btn-xs btn-circle addmore_ethyle" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Profit"><i class="fa fa-plus"></i></a>
                                    </td>
                                    <?php }else{
                                     ?>
                                    <td>
                                        <a onclick="remove_row(<?php echo  $inner_count.','. $eth['adhesive_material_id'];?>,2)" data-original-title="Remove" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title=""> 
                                             <i class="fa fa-minus"></i>
                                        </a>
                                    </td>    
                                    <?php
                                    } ?>
                                </tr>
                                <?php $inner_count++; }} ?>
                               </tbody>
                             </table>
                            </div>
							
							
						  </section>
                          </div>
						
			  		  
						  	  
             	 </div>
				 			  
				<div class="line m-t-large"></div>	

			<div class="form-group">
			    <label class="col-lg-2 control-label"><span class="required">*</span>Adhesive Used(kgs)</label>
                <div class="col-lg-2">
                 
                  	<input type="text" name="adhesive_used" id="adhesive_used" value="<?php echo isset($adhesive_details_all['adhesive_used'])?$adhesive_details_all['adhesive_used']:'';?>" class="form-control">
                </div>
                 <label class="col-lg-1 control-label">Hardner Used(kgs)</label>
					<div class="col-lg-2">
					<input type="text" name="hardner_used" id="hardner_used"  value="<?php echo isset($adhesive_details_all['hardner_used'])?$adhesive_details_all['hardner_used']:'';?>" class="form-control ">
					</div>  
                <label class="col-lg-1 control-label">Ethyle Used(kgs)</label>
                <div class="col-lg-2">
                  	<input type="text" name="ethyle_used" id="ethyle_used" value="<?php echo isset($adhesive_details_all['ethyle_used'])?$adhesive_details_all['ethyle_used']:'';?>" class="form-control ">      
					</div>
              </div>

			<div class="form-group">
			    <label class="col-lg-2 control-label"><span class="required">*</span>Total Used(kgs)</label>
                <div class="col-lg-2">
                 
                  	<input type="text" name="total_used" id ="total_used" value="<?php echo isset($adhesive_details_all['total_used'])?$adhesive_details_all['total_used']:'';?>" class="form-control ">
                </div>
               
              </div>



				
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                <?php if($edit){?>
                  	<button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                <?php } else { ?>
                	<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
                <?php } ?>  
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
                </div>
              </div>
            </form>
          </div>
        </section>
          </div>	
      </div>
    </div>
  </section>
</section>
<!-- Start : validation script -->
<!-- Start : validation script -->
<style type="text/css">
#ajax_response, #ajax_res,#ajax_return{
	border : 1px solid #13c4a5;
	background : #FFFFFF;
	position:relative;
	display:none;
	padding:2px 2px;
	top:auto;
	border-radius: 4px;
}
#holder{
	width : 350px;
}
.list {
	padding:0px 0px;
	margin:0px;
	list-style : none;
}
.list li a{
	text-align : left;
	padding:2px;
	cursor:pointer;
	display:block;
	text-decoration : none;
	color:#000000;
}
.selected{
	background : #13c4a5;
}
.bold{
	font-weight:bold;
	color: #227442;
	
}
.select_choose{
	width:100px;
}
</style>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href=" https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
$(document).ready(function(){
            $(".chosen_data").chosen();
			
 });      
$(".chosen-select").chosen();
jQuery(document).ready(function(){
	 jQuery("#form").validationEngine();
	 $("#job_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
});
//onkeyup="get_pre('+count+')"
$('.addmore_adhesive').click(function(){
	
    var tab=$('#adhesive tr:last').attr('id');		
	var a=tab.split('-');	
	var count=(parseInt(a[1])+1);
	
	
    var arr = $.parseJSON($('#min_arr').val());
    var html = '';
    html +='<tr class="adhesive_details-'+count+'"id="adhesive_details-'+count+'">';
   
	 html +='<td><input type="hidden" name="adhesive_details['+count+'][m_status]" id="m_status" value="0"><div>';
	  html += '<select name="adhesive_details['+count+'][product_item_id]" id="adhesive_details[product_item_id]['+count+']" class="form-control validate[required] chosen-select "><option value="">Select Adhesive</option>';
			  for(var i=0;i<arr.length;i++)
			  {
				 html += '<option value="'+arr[i].product_item_id+'">'+arr[i].product_name+'</option>';
			  }
	 html +=  '</select></div></td>';
	
 
    html +='<td><input type="text" name="adhesive_details['+count+'][use]" id="ad_used_'+count+'" placeholder="kg" onchange="get_total_details('+count+',0)"  class="form-control validate[required,custom[number]]"></td>';
 
    html +='<td><a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="" data-original-title="remove"><i class="fa fa-minus"></i></a></td>';
    html +='</tr>';
    $('#adhesive tr:last').after(html);
	$(".chosen-select").chosen();
	
	$(' .remove').click(function(){
			$("#addmore").show();
			$(this).parent().parent().remove();
			get_total_details(1,0);
		
		});
	
});
$('.addmore_hardner').click(function(){
	
//    var count = $('#hardner tr').length;
	    var tab=$('#hardner tr:last').attr('id');
		
			var a=tab.split('-');
		
			var count=(parseInt(a[1])+1);
	
    var arr = $.parseJSON($('#min_arr').val());
    var html = '';
    html +='<tr class="hardner_details-'+count+'"id="hardner_details-'+count+'">';
   
	 html +='<td><input type="hidden" name="hardner_details['+count+'][m_status]" id="m_status" value="1"><div>';
	  html += '<select name="hardner_details['+count+'][product_item_id]" id="hardner_details[product_item_id]['+count+']" class="form-control validate[required] chosen-select "><option value="">Select Hardner</option>';
			  for(var i=0;i<arr.length;i++)
			  {
				 html += '<option value="'+arr[i].product_item_id+'">'+arr[i].product_name+'</option>';
			  }
	 html +=  '</select></div></td>';
	
 
    html +='<td><input type="text" name="hardner_details['+count+'][use]" id="har_used_'+count+'" placeholder="kg" onchange="get_total_details('+count+',1)"  class="form-control validate[required,custom[number]]"></td>';
 
    html +='<td><a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="" data-original-title="remove"><i class="fa fa-minus"></i></a></td>';
    html +='</tr>';
    $('#hardner tr:last').after(html);
	$(".chosen-select").chosen();
	
	$(' .remove').click(function(){
			$("#addmore").show();
			$(this).parent().parent().remove();
			
			get_total_details(1,1);
			
		});
	
});
$('.addmore_ethyle').click(function(){
	
 //   var count = $('#ethyle tr').length;
	    var tab=$('#ethyle tr:last').attr('id');
			var a=tab.split('-');
			//alert(a[1]);
			var count=(parseInt(a[1])+1);
	
	
    var arr = $.parseJSON($('#min_arr').val());
    var html = '';
    html +='<tr class="ethyle_details-'+count+'"id="ethyle_details-'+count+'">';
   
	 html +='<td><input type="hidden" name="ethyle_details['+count+'][m_status]" id="m_status" value="2"><div>';
	  html += '<select name="ethyle_details['+count+'][product_item_id]" id="ethyle_details[product_item_id]['+count+']" class="form-control validate[required] chosen-select "><option value="">Select Ethyle</option>';
			  for(var i=0;i<arr.length;i++)
			  {
				 html += '<option value="'+arr[i].product_item_id+'">'+arr[i].product_name+'</option>';
			  }
	 html +=  '</select></div></td>';
	
 
    html +='<td><input type="text" name="ethyle_details['+count+'][use]" id="eth_used_'+count+'" placeholder="kg" onchange="get_total_details('+count+',2)" class="form-control validate[required,custom[number]]"></td>';
 
    html +='<td><a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="" data-original-title="remove"><i class="fa fa-minus"></i></a></td>';
    html +='</tr>';
    $('#ethyle tr:last').after(html);
	$(".chosen-select").chosen();
	
	$(' .remove').click(function(){
			$("#addmore").show();
			$(this).parent().parent().remove();
			
			get_total_details(1,2);
		});
	
});


    function remove_row(count,adhesive_material_id,m_status){
		if(m_status==0){
			$('.adhesive_details-'+count).remove();
		}else if(m_status==1){
			$('.hardner_details-'+count).remove();
		}else if(m_status==2){
			$('.ethyle_details-'+count).remove();
		}

		
			get_total_details(1,0);
			get_total_details(1,1);
			get_total_details(1,2);
		var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=remove_adhesive', '',1);?>");
			$.ajax({
				url : remove_url,
				method : 'post',
				data : {adhesive_material_id : adhesive_material_id},
				success: function(response){
				},
				error: function(){
					return false;	
				}
		});	
	}

  function  get_total_details(count,m_status){
	
	  	if(m_status==0){
		
		//	var adhesive_count = $("#adhesive tr").length-1;
			var tab=$('#adhesive tr:last').attr('id');
			var a=tab.split('-');
			//alert(a[1]);
			var adhesive_count=(parseInt(a[1]));
			var adhesive_sum = 0;
			for(var i =1; i<=adhesive_count; i++){
			if ($('#ad_used_'+i).length)	
				var adhesive_total = $('#ad_used_'+i).val();		
			//	alert(adhesive_total);
			if (adhesive_total.length > 0)				
				adhesive_sum += parseFloat(adhesive_total); 
				
			}
		
			$("#adhesive_used").val(adhesive_sum);

			
		}else if(m_status==1){
			//var hardner_count = $("#hardner tr").length-1;	
			var tab=$('#hardner tr:last').attr('id');
			var a=tab.split('-');
			//alert(a[1]);
			var hardner_count=(parseInt(a[1]));
		
			var hardner_sum = 0;
			for(var i =1; i<=hardner_count; i++){
			if ($('#har_used_'+i).length)	
				var hardner_total = $('#har_used_'+i).val();				
			if (hardner_total.length > 0)				
				hardner_sum += parseFloat(hardner_total); 				
			}
			$("#hardner_used").val(hardner_sum);
			
		}else if(m_status==2){
		//	var ethyle_count = $("#ethyle tr").length-1;
			 var tab=$('#ethyle tr:last').attr('id');
			var a=tab.split('-');
			//alert(a[1]);
			var ethyle_count=(parseInt(a[1]));
		
			var ethyle_sum = 0;
			for(var i =1; i<=ethyle_count; i++){
			if ($('#eth_used_'+i).length)	
				var ethyle_total = $('#eth_used_'+i).val();				
			if (ethyle_total.length > 0)				
				ethyle_sum += parseFloat(ethyle_total); 				
			}
			$("#ethyle_used").val(ethyle_sum)
	
		}
	  
	  var hardner_used=$("#hardner_used").val();
	  var adhesive_used=$("#adhesive_used").val();
	  var ethyle_used=$("#ethyle_used").val();
	 

	if(hardner_used!='' ||adhesive_used!=''||ethyle_used!='')
	 $("#total_used").val(parseFloat(hardner_used)+parseFloat(adhesive_used)+parseFloat(ethyle_used));
  }
	
	
	

</script>
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>