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
$edit = '';
$reminder_status = 0;

if(isset($_GET['tax_calender_id']) && !empty($_GET['tax_calender_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$tax_calender_id = base64_decode($_GET['tax_calender_id']);
		$tax_record = $obj_tax_calender->getTaxRecord($tax_calender_id);
		$tax_record_img = $obj_tax_calender->getTaxImgRecord($tax_calender_id);
		$reminder_status = $tax_record['reminder'];
	//	echo $tax_calender_id;
		//printr($tax_record);
		//die;
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}

/*if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}*/

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

if($display_status){

	if(isset($_POST['btn_save'])){
		$post = post($_POST);
		if(!isset($post['reminder'])) {
			$post['reminder']= $reminder_status;
		}		
		//printr($post);die;
		$insert_id = $obj_tax_calender->addTax($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		
		
		$post = post($_POST);
		if(!isset($post['reminder'])) {
			$post['reminder']= $reminder_status;
		}	
		//printr($post);
		//die;
		$tax_calender_id = $tax_record['tax_calender_id'];
		$obj_tax_calender->updateTax($tax_calender_id,$post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
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
     
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Date</label>
                <div class="col-lg-4">
                  <input type="text" class="form-control validate[required]" name="date" value="<?php echo isset($tax_record['date']) ? $tax_record['date'] : '';?>" placeholder="Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="date"/>
                    </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Description</label>
                <div class="col-lg-4">
                 
                 <textarea name="description" id="description" placeholder="Description" class="form-control validate[required]"><?php echo isset($tax_record['description']) ? $tax_record['description'] : '';?></textarea>
                </div>
              </div> 
              
               <?php // for upload image and document ?>
              
              <div class="form-group">
					<label class="col-lg-3 control-label">Upload Document</label>
                       <div class="col-lg-9">
                        <div class="media-body">
                            <input type="file" name="art_image" id="art-image"  class="custom-file-input" multiple>
                         </div>
                         <br/>
                        <div class="file-preview-thumbnails">
                        	
                                 <section class="panel">
                                 <div class="table-responsive">
                                        <table class="table table-striped b-t text-small ">
                                             <thead>
                                                <tr>
                                                  
                                                  <th>Date</th>
                                                  <th>Image Name</th>
                                                  <th>User</th>
                                                  <th>Action</th>
                                                </tr>   
                                             </thead>
         
                                             <tbody class="tbody">
                                                <?php $i=1;
													if(isset($tax_record_img) && !empty($tax_record_img))
													{
                                                        foreach($tax_record_img as $img_result){
														$user_name = $obj_tax_calender->getUser($img_result['user_id'],$img_result['user_type_id']);
                                                  ?>
                                                  <tr>
                                                      <!--<td> <?php ///echo $i; ?> </td>-->
                                                      <td> <?php echo dateFormat(4,$img_result['date_added']); ?> </td>
                                                      <td><a href="<?php echo HTTP_UPLOAD.'admin/tax_img/100_'.$img_result['image_name']; ?>" target="_blank"><?php echo $img_result['image_name']; ?> </a></td>
                                                      <td> <?php echo $user_name['user_name']; ?></td><input type="hidden" name="img_nm[<?php echo $i; ?>]" value="<?php ?>" />
                                                      <td> <a class="label bg-danger remove" style="margin-left:4px;" onclick="remove_record(<?php echo $img_result['max_image_id'];?>,<?php echo $i; ?>)"><i class="fa fa-trash-o"></i> Remove</a></td>
                                                  </tr>             
                                                       <?php $i++; 
													   }
													  } ?>     
                                             </tbody>    
                                        </table>
                                 </div>
                                 </section>
                       
                         </div>
                         <div class="file-preview" style="margin-top: 10px; <?php  if(!isset($tax_record['product_image_url'])){ ?>display:none <?php } ?>">
                           
                </div>
              </div>
              
              <!-- <div class="form-group">
                <label class="col-lg-3 control-label">Uploaded Done</label>
                <div class="col-lg-3">
                	<input type="checkbox" name="upload_done"  value="1"  id="upload_done"/>
                    </div>
              </div>-->
              
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Reminder Date </label>
                <div class="col-lg-4">
                 <input type="text" class="form-control validate[required]" name="remainder_date" value="<?php echo isset($tax_record['remainder_date']) ? $tax_record['remainder_date'] : '';?>" placeholder="Reminder Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="remainder_date"/>
                </div>
              </div>
				
                <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Last Reminder Date </label>
                <div class="col-lg-4">
                 <input type="text" class="form-control validate[required]" name="last_remainder_date" value="<?php echo isset($tax_record['last_remainder_date']) ? $tax_record['last_remainder_date'] : '';?>" placeholder="Last Reminder Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="last_remainder_date"/>
                </div>
              </div>

				 <div class="form-group">
                            <label class="col-lg-3 control-label"> Reminder ?</label>
                            <div class="col-lg-4">
                              <div data-toggle="buttons" class="btn-group m-t-n-mini m-r-n-mini"> 
                                <!-- m-t-n-mini m-r-n-mini-->
                                <?php // echo $reminder_status;?>
                                <label class="btn btn-sm btn-white <?php echo ($reminder_status=='1') ? 'btn-on active' : 'on';?>"><input type="radio" id="option1" name="reminder" value="1"> ON </label> 
                                <label class="btn btn-sm btn-white <?php echo ($reminder_status=='0') ? 'btn-on active' : 'off';?>"><input type="radio" id="option2" name="reminder" value="0"> OFF </label> 
                              </div>
                            </div>
                         </div>
				
				<div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($tax_record['status']) && $tax_record['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($tax_record['status']) && $tax_record['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
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

<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
jQuery(document).ready(function(){
	   jQuery("#form").validationEngine();
	   
	   $('#date').datepicker({format:'yyyy-mm-dd',}).on('changeDate',function(e){$(this).datepicker('hide');});
	   
	   
	    var nowTemp = new Date();
		//alert(nowTemp);
	    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		 //var minus_10 = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate()-10, 0, 0, 0, 0);
	    var checkin = $('#date').datepicker({
   			onRender: function(date) {
    		return date.valueOf() < now.valueOf() ? '' : '';
    		}
    	}).on('changeDate', function(ev) {
			var minu=ev.date.valueOf();
			var newDate = new Date(minu);
			var secDate = new Date(minu);
				newDate.setDate(newDate.getDate()-10);
				checkout.setValue(newDate);
				newDate.setDate(secDate.getDate()-3);
				lastcheckout.setValue(newDate);
				checkin.hide();
    		
    	}).data('datepicker');
		
    	var checkout = $('#remainder_date').datepicker({
    		onRender: function(date) {
			}}).on('changeDate', function(ev) {
			
    		
    		checkout.hide();
    	}).data('datepicker');
		
		var lastcheckout=$('#last_remainder_date').datepicker({
    		onRender: function(date) {
			
			/*if(checkout.date.valueOf() > date.valueOf())
						return 'disabled';
					else
						return '';*/
			}
    	}).on('changeDate', function(ev) {
    		lastcheckout.hide();
    	}).data('datepicker');
});

   jQuery(document).ready(function(){
	 jQuery("#form").validationEngine();		
    });

$(document).on("change","input[name^='art_image']", function(e){
	
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajaximage&fun=ajaximage', '',1);?>");
	
	var img_html = '';
	var file_data = $("#art-image").prop("files")[0];          // Getting the properties of file from file field
	$('#loading').show();
	var form_data = new FormData();                            // Creating object of FormData class
		form_data.append("file", file_data); // Appending parameter named file with properties of file_field to form_data
			$.ajax({
				url: url,
				dataType: 'script',
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         // Setting the data attribute of ajax with file_data
				type: 'post',
				success : function(response){
					$('#loading').remove();
					var len = $(".table-responsive .table .tbody tr").length;
					var val=JSON.parse(response);
					<?php $user_name = $obj_tax_calender->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']); ?>
						
						img_html +='<tr class="tr_'+(parseInt(len)+1)+'">';
								//img_html += '<td>'+(parseInt(len)+1)+'</td>';
								img_html += '<td><?php echo dateFormat("4",date("Y-m-d"))?></td>';
								img_html += '<td><a href=<?php echo HTTP_UPLOAD."admin/tax_img/100_" ?>'+val+' target="_blank">'+val+'</a></td>';
								img_html += '<td><?php echo $user_name['user_name'];?></td>';
								img_html += '<input type="hidden" name="img_nm['+(parseInt(len)+1)+']" id="img_nm" value="'+val+'"/>';
								img_html += '<td><a class="label bg-danger remove" style="margin-left:4px;"><i class="fa fa-trash-o"></i> Remove</a></td>';
						img_html +='</tr>';
						
					
					$('.file-preview-thumbnails .table-responsive .table').append(img_html);
					
					$('.remove').click(function(){
						$(this).parent().parent().remove();
					});
				}
			});
});

$(' .remove').click(function(){
	//alert("remove");
	$(this).parent().parent().remove();
});
function remove_record(max_image_id,tr_no)
{
	if(max_image_id=='0')
	{
		alert("hii");
	}
	else
	{
		var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=removeAusUploadRecord', '',1);?>");
			$.ajax({
			 	url : remove_url,
			 	type : 'post',				
			 	data : {max_image_id : max_image_id},
			 	success : function(response){	
					
				}
		  	});
	}
	
}



</script>
<?php 
 } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>




