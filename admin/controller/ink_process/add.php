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

if(isset($_GET['ink_process']) && !empty($_GET['ink_process'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$ink_process_id = base64_decode($_GET['ink_process']);
		$ink_detail = $obj_ink_process->getInkProcesDetail($ink_process_id);
         //     printr($ink_detail);//die;
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
//     printr($post);
		$insert_id = $obj_ink_process->addInkProcess($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
	//printr($post);die;
		$obj_ink_process->updateInkProcess($post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	$job_latest_id=0;
//	$latest_job_id = $obj_ink_process->getlatestjobid();
	if(!empty($latest_job_id))
		$job_latest_id=$latest_job_id;
	
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
                 <label class="col-lg-2 control-label">Job Date</label>
                <div class="col-lg-2">
                  	<input type="text" name="job_date" id="job_date" data-date-format="yyyy-mm-dd" value="<?php if(isset($ink_detail['date'])){ echo $ink_detail['date']; }else{ echo date("Y-m-d"); }  ?>" class="form-control  datepicker">
                </div>  
                <label class="col-lg-2 control-label">Shift</label>
                <div class="col-lg-2">
                  	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="shift" value="1" checked="checked" <?php if(isset($ink_detail) && ($ink_detail['shift'] == '1')) { echo 'checked=checked'; } ?>/> Day
                              </label>
                            
                                <label style="font-weight: normal;">
                                  	<input type="radio" name="shift" value="0" <?php if(isset($ink_detail) && ($ink_detail['shift'] == '0')) { echo 'checked=checked'; }?> />Night
                              </label>
                      </div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-2 control-label"><span class="required">*</span>Job No</label>
                <div class="col-lg-3">
                  	<input type="text"name="job_name_text" id="job_name_text" value="<?php echo isset($ink_detail['job_name_text'])?$ink_detail['job_name_text']:'';?>" class="form-control validate[required]">
                    <input type="hidden" name="job_id" id="job_id" value="<?php echo isset($ink_detail['job_id'])?$ink_detail['job_id']:'';?>" />
                    <div id="ajax_response"></div>
                </div>
				<div class="col-lg-4">
                  	<input type="text" class="form-control " readonly name="job_name" id="job_name" value="<?php echo isset($ink_detail['job_name'])?$ink_detail['job_name']:'';?>"/>
                </div>
              </div>
                <div class="form-group">
                        <label class="col-lg-2 control-label"><span class="required">*</span>Operator Name</label>
                        <div class="col-lg-3">
                            <?php  $operators = $obj_ink_process->getOperatorName();
//                            printr($operators);?>
                            <select name="operator_id" id="machine_id" class="form-control validate[required]">
                                <option value="">Select Operator Name</option>
                                <?php foreach ($operators as $operator) { ?>
                                    <option value="<?php echo $operator['employee_id']; ?>"<?php echo(isset($ink_detail['operator_id'])&& $ink_detail['operator_id']==$operator['employee_id'])?'selected':'';?>> <?php echo $operator['user_name']; ?></option>
                                        <?php } ?> 
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><span class="required">*</span>Chemist Name</label>
                        <div class="col-lg-3">
                            <?php $camists = $obj_ink_process->getCamistName();  ?>
                            <select name="camist_id" id="machine_id" class="form-control validate[required]">
                                <option value="">Select Chemist Name</option>
                                <?php foreach ($camists as $camist) { ?>
                                    <option value="<?php echo $camist['employee_id']; ?>"<?php echo(isset($ink_detail['chemist_id'])&& $ink_detail['chemist_id']==$camist['employee_id'])?'selected':'';?>> <?php echo $camist['user_name']; ?></option>
                                        <?php } ?> 
                            </select>
                        </div>
                    </div>
                
              <div class="form-group">
					<label class="col-lg-2 control-label"></label> 
                    <div class="col-lg-9">
                        <section class="panel">
                          <div class="table-responsive">
                            <table class="tool-row table-striped  b-t text-small " id="myTable" width="100%">
                              <thead>
                                  <tr>
                                        <th><span class="required"></span>Sr. No</th>
                                        <th><span class="required">*</span>Ink Name</th>
                                        <th><span class="required">*</span>Issue</th>
                                        <th><span class="required">*</span>Return</th>
                                        <th><span class="required">*</span>Use</th>
                                        <th>Remark</th>
                                  </tr>
                              </thead>
                              <?php 
//                              printr($ink_detail);
                              if($edit=='1' && $ink_detail['ink_detail']){
                                  $ink_data = $ink_detail['ink_detail'];
//                                  printr($ink_data);
                              }else{
                                  $ink_array=array();
                                  $ink_data[] = array(
                                          'ink_process_id' =>'' ,
                                          'ink_process_detail_id'=>'',
                                          'ink_name' => '',
                                          'ink_issue' => '',
                                          'ink_return' => '',
                                          'ink_use' => '',
                                          'remark' => '',
                                          'date_added' => '',
                                          'date_modify' => '',
                                          'is_delete' => '',
                                          'ink_detail' => $ink_array,
                                      );
                              }
                              ?>
                               <?php 
                               $inks = $obj_ink_process->getInk();
						//		printr(decode($_GET['ink_process']));
								 if(!empty($ink_data)){
                                    $inner_count = 1;
                                      foreach($ink_data as $ink){ ?>
                              <tbody>                                    
                                <tr class="multiplerows-<?php echo $inner_count; ?>">
                                    <td><input type="text" name="multiplerows[<?php echo $inner_count; ?>][number]" value="<?php echo $inner_count ?>" disabled class="form-control validate[required]"></td>
                                    <input type="hidden" name="ink_id" value="<?php echo isset($ink)?$ink['ink_process_id']:'';?>">
                                    <input type="hidden" name="multiplerows[<?php echo $inner_count; ?>][ink_process_detail_id]" value="<?php echo(isset($ink))?$ink['ink_process_detail_id']:'';?>">
                                    <td> <input type="hidden" id="min_arr" value='<?php echo json_encode($inks);?>' />
										<div>
                                          <select name="multiplerows[<?php echo $inner_count; ?>][ink_name]" id="multiplerows[ink_name][<?php echo $inner_count;?>]" class="form-control validate[required] chosen_data">
                                                        <option value="">Select INK</option>
                                                        <?php  foreach($inks as $code)
                                                           {
                                                               if($code['product_item_id']==$ink['ink_name'])
                                                                echo '<option value="'.$code['product_item_id'].'" selected="selected">'.$code['product_name'].'</option>';
                                                               else
                                                                    echo '<option value="'.$code['product_item_id'].'">'.$code['product_name'].'</option>';
                                                           }
                                                           ?>
                                                    </select>
                                            </div>
										
										</div>
                                    </td>
                                    <td><input type="text" name="multiplerows[<?php echo $inner_count ;?>][issue]" value="<?php echo(isset($ink))?$ink['ink_issue']:'';?>" placeholder="kg" class="form-control validate[required,custom[number]]"></td>
                                    <td><input type="text" name="multiplerows[<?php  echo $inner_count ;?>][return]" value="<?php echo(isset($ink))?$ink['ink_return']:'';?>" placeholder="kg" class="form-control validate[required,custom[number]]"></td>
                                    <td><input type="text" name="multiplerows[<?php echo $inner_count ;?>][use]" value="<?php echo(isset($ink))?$ink['ink_use']:'';?>" placeholder="kg" class="form-control validate[required,custom[number]]"></td>
                                    <td><textarea class="form-control " name="multiplerows[<?php echo $inner_count; ?>][remark]"><?php echo(isset($ink))?$ink['remark']:'';?></textarea></td>
                                    <?php if($inner_count==1){ ?>
                                    <td>
                                        <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Profit"><i class="fa fa-plus"></i></a>
                                    </td>
                                    <?php }else{
                                     ?>
                                    <td>
                                        <a onclick="remove_ink(<?php echo  $inner_count.','. $ink['ink_process_detail_id'];?>)" data-original-title="Remove" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title=""> 
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
			 //$(".chosen_data").chosen({"disable_search": true});
 });      
$(".chosen-select").chosen();
jQuery(document).ready(function(){
	 jQuery("#form").validationEngine();
	 $("#job_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
});
//onkeyup="get_pre('+count+')"
$('.addmore').click(function(){
    var count = $('#myTable tr').length;
    var arr = $.parseJSON($('#min_arr').val());
    var html = '';
    html +='<tr class="multiplerows-['+count+']">';
    html += '<td><input type="text" name="multiplerows['+count+'][number]" value='+count+' disabled class="form-control"></td>';
   //tml +='<td><input type="text" name="multiplerows['+count+'][ink_name]" placeholder="Ink Name" class="form-control"  id="multiplerows[ink_name]['+count+']" onkeyup="get_pre('+count+')"></td>';
	
	 html +='<td><div>';
	  html += '<select name="multiplerows['+count+'][ink_name]" id="multiplerows[ink_name]['+count+']" class="form-control validate[required] chosen-select "><option value="">Select Ink</option>';
			  for(var i=0;i<arr.length;i++)
			  {
				 html += '<option value="'+arr[i].product_item_id+'">'+arr[i].product_name+'</option>';
			  }
	 html +=  '</select></div></td>';
	
    html +='<td><input type="text" name="multiplerows['+count+'][issue]" placeholder="kg" class="form-control validate[required,custom[number]]"></td>';
    html +='<td><input type="text" name="multiplerows['+count+'][return]" placeholder="kg" class="form-control validate[required,custom[number]]"></td>';
    html +='<td><input type="text" name="multiplerows['+count+'][use]" placeholder="kg" class="form-control validate[required,custom[number]]"></td>';
    html +='<td><textarea class="form-control" name="multiplerows['+count+'][remark]"></textarea></td>';
    html +='<td><a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="" data-original-title="remove"><i class="fa fa-minus"></i></a></td>';
    html +='</tr>';
    $('#myTable tr:last').after(html);
	$(".chosen-select").chosen();
	
	$(' .remove').click(function(){
			$("#addmore").show();
			$(this).parent().parent().remove();
		});
	
});

$("#job_name_text").focus();
	var offset = $("#product_item_id").offset();
	var width = $("#holder").width();
	$("#ajax_response").css("width",width);
	
	$("#job_name_text").keyup(function(event){		
		 var keyword = $("#job_name_text").val();
		 if(keyword.length)
		 {	
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {
				 var product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=job_detail', '',1);?>");
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: product_url,
				   data: "job="+keyword,
				   success: function(msg){	
				 var msg = $.parseJSON(msg);
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{ 	
						for(var i=0;i<msg.length;i++)
						{	
							div =div+'<li><a href=\'javascript:void(0);\' job_no ="'+msg[i].job_no+'" id="'+msg[i].job_id+'" job_name="'+msg[i].job_name+'" ><span class="bold" >'+msg[i].job_no+'</span></a></li>';
						}
					}
					
					div=div+'</ul>';
				
					if(msg != 0)
					  $("#ajax_response").fadeIn("slow").html(div);
					else
					{
						$("#ajax_response").fadeIn("slow");	
						$("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
				  		$("#job_id").val('');
				  		$("#job_name").val('');
					}
					$("#loading").css("visibility","hidden");
				   }
				 });
			 }
			 else
			 {				
				switch (event.keyCode)
				{
				 case 40:
				 {
					  found = 0;
					  $(".list li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $(".list li[class='selected']");
						sel.next().addClass("selected");
						sel.removeClass("selected");										
					  }
					  else
						$(".list li:first").addClass("selected");
						if($(".list li[class='selected'] a").text()!='')
						{
							$("#job_name_text").val($(".list li[class='selected'] a").text());
							$("#job_id").val($(".list li[class='selected'] a").attr("id"));
							$("#job_name").val($(".list li[class='selected'] a").attr("job_name"));
						}
				}
				 break;
				 case 38:
				 {
					  found = 0;
					  $(".list li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $(".list li[class='selected']");
						sel.prev().addClass("selected");
						sel.removeClass("selected");
					  }
					  else
						$(".list li:last").addClass("selected");
						if($(".list li[class='selected'] a").text()!='')
						{
							$("#job_name_text").val($(".list li[class='selected'] a").text());
							$("#job_id").val($(".list li[class='selected'] a").attr("id"));
							$("#job_name").val($(".list li[class='selected'] a").attr("job_name"));
						}
				 }
				 break;				 
				}
			 }
		 }
		 else
		 {
			$("#ajax_response").fadeOut('slow');
			$("#ajax_response").html("");
		 }
	});
	
	$('#job_name_text').keydown( function(e) {
		if (e.keyCode == 9) {
			 $("#ajax_response").fadeOut('slow');
			 $("#ajax_response").html("");
		}
	});

	$("#ajax_response").mouseover(function(){
				$(this).find(".list li a:first-child").mouseover(function () {
					  $("#job_id").val($(this).attr("id"));
					  $("#job_name").val($(this).attr("job_name"));
					  $(this).addClass("selected");
				});
				$(this).find(".list li a:first-child").mouseout(function () {
					  $(this).removeClass("selected");
					  $("#job_id").val('');
					  $("#job_name").val('');
				});
				$(this).find(".list li a:first-child").click(function () {
					  $("#job_id").val($(this).attr("id"));
					  $("#job_name").val($(this).attr("job_name"));
					  
					  $("#job_name_text").val($(this).text());
					 $("#ajax_response").fadeOut('slow');
					  $("#ajax_response").html("");
					  
					
				});
				
			});
    function remove_ink(count,ink_id){
		$('.multiplerows-'+count).remove();
		var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=remove_ink', '',1);?>");
			$.ajax({
				url : remove_url,
				method : 'post',
				data : {ink_id : ink_id},
				success: function(response){
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