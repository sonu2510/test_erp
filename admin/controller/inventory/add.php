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
	'href' 	=> $obj_general->link($rout,'mod=index&status=0', '',1),
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
if(isset($_GET['country_id']) && !empty($_GET['country_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$country_id = base64_decode($_GET['country_id']);
		$country = $obj_inventory->getCountry($country_id);
		$courier_data = $obj_inventory->getCouriers();
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
		
		page_redirect($obj_general->link($rout, 'mod=index&status=0', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		$country_id = $country['country_id'];
		$obj_inventory->updateCountry($country_id,$post);
		$obj_session->data['success'] = UPDATE;
		if(isset($obj_session->data['page'])){
			$pageString = '&page='.$obj_session->data['page'];
			unset($obj_session->data['page']);
		}else{
			$pageString = '';
		}
		page_redirect($obj_general->link($rout, $pageString.'&filter_edit='.$_GET['filter_edit'], '',1));
	}
	//$id=$_GET['indent_id'];
	//printr($id);
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
        
      <div class="col-sm-8" style="width:100%">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
     
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="inventory-form" enctype="multipart/form-data" >
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Company Name </label>
                <div class="col-lg-4">
         
                   <select name="vander" id="vander" class="form-control validate[required]" onchange="populateHidden(this)" >
                   <option value="">Select Company Name</option>
                  <?php $vanders= $obj_inventory->getVander();
				  
				      foreach($vanders as $vander)
					  {
					  ?>
                    <option value="<?php echo $vander['vander_id'];?>"><?php echo $vander['company_name'];?></option>
                     <?php 
					  }
					  ?>
                       </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Description</label>
                <div class="col-lg-4"> <textarea name="description"  id="description" class="form-control validate[required]"><?php echo isset($country['currency_code'])?$country['currency_code']:'';?></textarea>
              </div>
              </div>
         
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Delivery Date</label>
                <div class="col-lg-4">
                  <input type="text" name="due_date"  value="" placeholder="Delivery Date" class="span2 validate[required]" data-date-format="yyyy-mm-dd" readonly="readonly" id="input_ddate"/>
                </div>
              </div> 
              
             <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Reminder Date</label>
                <div class="col-lg-4">
                 <input type="text" name="reminder_date"  value="" placeholder="Reminder Date" class="span2 validate[required]" data-date-format="yyyy-mm-dd" readonly="readonly" id="input_rdate"  />
                </div>
              </div>
           
              <div class="form-group">
                <label class="col-lg-3 control-label">Items</label>
                <div class="col-lg-4">
                <select name="items" id="items" class="form-control validate[required]">
                <option value="">Select Item</option>
                 <optgroup label="Zipper">
                <?php $table = "product_zipper"; $list = $obj_inventory->getitemslist($table);
				//printr($list);
					foreach($list as $kk)
					{
						if($kk['zipper_name']!='No zip')
						{	$unit=$kk['zipper_unit'];
					?>
					  <option value="<?php echo $table.'-'.$kk['product_zipper_id'];?>"><?php echo $kk['zipper_name'];?></option> 
					<?php } }?> 
                
                </optgroup> 
                 <optgroup label="Spout">
                <?php $table = "product_spout"; $list = $obj_inventory->getitemslist($table);
					foreach($list as $kk)
					{
						if($kk['spout_name']!='No Spout')
						{
					?>
					  <option value="<?php echo $table.'-'.$kk['product_spout_id'];?>"><?php echo $kk['spout_name'];?></option> 
					<?php }
				}?> 
                
                </optgroup> 
                 <optgroup label="Accessorie">
                <?php $table = "product_accessorie"; $list = $obj_inventory->getitemslist($table);
					foreach($list as $kk)
					{
						if($kk['product_accessorie_name']!='No Accessorie')
						{
					?>
					  <option value="<?php echo $table.'-'.$kk['product_accessorie_id'];?>"><?php echo $kk['product_accessorie_name'];?></option> 
					<?php }
				}?> 
                
                </optgroup> 
                 <optgroup label="Material">
                <?php $table = "product_material"; $list = $obj_inventory->getitemslist($table);
					foreach($list as $kk)
					{	
						if($kk['material_name']!='No Material')
						{
					?>
								<option value="<?php echo $table.'-'.$kk['product_material_id'];?>"><?php echo $kk['material_name'];?></option> 
					<?php }
					}?> 
                
                </optgroup> 
                 <optgroup label="Adhesive">
                <?php $table = "adhesive"; $list = $obj_inventory->getitemsOfInk($table);
					foreach($list as $kk)
					{
					?>
					  <option value="<?php echo $table.'-'.$kk['adhesive_id'];?>"><?php echo $kk['make_name'];?></option> 
					<?php 
					}?> 
                
                </optgroup> 
                <optgroup label="Adhesive Solvent">
                <?php $table = "adhesive_solvent"; $list = $obj_inventory->getitemsOfInk($table);
				//printr($list);
					foreach($list as $kk)
					{
					?>
					  <option value="<?php echo $table.'-'.$kk['adhesive_solvent_id'];?>"><?php echo $kk['make_name'];?></option> 
					<?php 
					}?> 
                
                </optgroup> 
                 <optgroup label="Ink Master">
                <?php $table = "ink_master"; $list = $obj_inventory->getitemsOfInk($table);
					foreach($list as $kk)
					{
					?>
					  <option value="<?php echo $table.'-'.$kk['ink_master_id'];?>"><?php echo $kk['make_name'];?></option> 
					<?php
					}?> 
                
                </optgroup> 
                <optgroup label="Ink Solvent">
                <?php $table = "ink_solvent"; $list = $obj_inventory->getitemsOfInk($table);
					foreach($list as $kk)
					{
					?>
					  <option value="<?php echo $table.'-'.$kk['ink_solvent_id'];?>"><?php echo $kk['make_name'];?></option> 
					<?php
					}?> 
                
                </optgroup> 
              
                </select><input type="hidden" id="myHidden" name="myHidden" value=""  />
                </div>
                </div>

                <div class="form-group" id="product_value" style="display:none">
               	 <label class="col-lg-3 control-label"><span class="required">*</span>Enter Qty</label>
                	<div class="col-lg-1">
                 		<input type="text" name="itemsqty" pattern="^[0-9]+$" style="width:150px" class="form-control validate[required,custom[number]]" value="" id="itemsqty" onchange="" onkeypress=""/>
                	</div>
              			</div> 
                        <div><input type="hidden" name="indentid" id="indentid" value="" /></div>
                <div class="form-group" id="product_add">
                <div class="col-lg-9 col-lg-offset-3">
                 <input type="button" name="add"  value="ADD" class="btn btn-primary" id="btn-add-product"/>
                 </div>
                 
                </div>
                <div id="display-product">
                 </div>
              
              <div class="form-group" id="footer-div" style="display:none">
                                <div class="col-lg-9 col-lg-offset-3">
                                 <button type="submit" name="btn_save" class="btn btn-primary">Save</button>
                                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=index&status=0', '',1);?>">Cancel</a>  
                                </div>
                             </div>
            </form>
          </div>
        </section>
        
      </div>
    </div>
  </section>
</section>
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Title</h4>
            </div>
            <div class="modal-body">
                <p id="setmsg">Message</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="popbtncan" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" name="popbtnok" id="popbtnok" class="btn btn-primary">Ok</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->  
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<style>

ul {
    list-style-type:none;
    padding:0px;
    margin:0px;
}

.selected {
    background-color:#efefef;
}
</style>


<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>


<script>

$('#btn-add-product').click(function(){
	
			var option = $('#items :selected').text();
			var id = $('#items').val();
			$("#items option[value='"+id+"']").hide();
			$("#items option[value='']").attr("selected","selected");
			
	if($("#inventory-form").validationEngine('validate')){
		var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addProduct', '',1);?>");
		$('#vander').attr('disabled',false);
		$('#description').attr('readonly',false);
        $('#input_ddate').attr('disabled',false); 
        $('#input_rdate').attr('disabled',false);
		var str = $("form").serialize();
		//alert(str);
		$.ajax({
			url : add_product_url,
			method : 'post',
      		 data:{str:str},	
			success: function(response){
			//alert(response);
				var val = $.parseJSON(response);	
				   $('#display-product').html(val.response);
				   $('#indentid').val(val.result);
				   $('#vander').attr('disabled',true);
				  $('#description').attr('readonly',true);
                    $('#input_ddate').attr('disabled',true); 
              	 $('#input_rdate').attr('disabled',true);
				 $('#footer-div').show();
				 $("#items").val("");
			},
			error: function(){
				return false;
			}
		});
	}else{
		return false;
	}
});

function removeItem(purchase_indent_items_id,indent_id, removed_id, table_name )
{	

	$("#myModal"+removed_id).modal("show");
	$(".modal-title"+removed_id).html("Delete product".toUpperCase());
	$("#setmsg"+removed_id).html("Are you sure you want to delete ?");
	
	
	$("#popbtnok"+removed_id).click(function()
	{
		//alert(value_id);
		var remove_item_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeItem', '',1);?>");
		$.ajax({
			url : remove_item_url,
			method : 'post',
			data : {purchase_indent_items_id : purchase_indent_items_id},
			success: function(response)
			{	//alert(response);
			var value_id = table_name+'-'+removed_id;
			$("#items option[value='"+value_id+"']").show();
				$('#'+purchase_indent_items_id).hide();
				if(response == 0) 
				{
				//	alert(removed_id);
				//alert(value_id);
					$("#results").hide();
					$("#myModal"+removed_id).hide();
				}
				
			},
			error: function(){
					
				return false;	
				}
		});
	
		
		$("#myModal"+removed_id).hide();
		$("#myModal"+removed_id).modal("hide");
	});

}

   jQuery(document).ready(function(){
	   jQuery("#form").validationEngine();
	   $("#items").change(function()
	   {	
			$("#product_value").show();
			$("#product_add").show();
		});	

        // binds form submission and fields to the validation engine
	    var nowTemp = new Date();
		//alert(nowTemp);
	    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		//alert(now);
	    var checkin = $('#input_ddate').datepicker({
   			onRender: function(date) {
    		return date.valueOf() < now.valueOf() ? 'disabled' : '';
    		}
    	}).on('changeDate', function(ev) {
			if (ev.date.valueOf() > checkout.date.valueOf()) {
				var newDate = new Date(ev.date)
          		newDate.setDate(newDate.getDate() - 1);
    			checkout.setValue(newDate);
    		}
    		checkin.hide();
    		$('#input_rdate')[0].focus();
    	}).data('datepicker');
    	var checkout = $('#input_rdate').datepicker({
    		onRender: function(date) {
				if( (date.valueOf() > checkin.date.valueOf()) || ( date.valueOf() < now.valueOf()))
						return 'disabled';
					else
						return '';
				
    		}
    	}).on('changeDate', function(ev) {
    		checkout.hide();
    	}).data('datepicker');
	
	
	$('body').on('click', '#list1 li:not(:has(ul))', function() {
	
	
	   $(this).toggleClass('selected');
	});
	$('body').on('click', '.list2 li:not(:has(ul))', function() {
	   $(this).toggleClass('selected');
	});
	$('#move_left').click(function() {
		$('.list2 .selected').each(function(){
			var idval = $(this).attr('id');
			var ulid=idval.split('-');
				 $('#'+ulid[0]).append('<li id='+idval+' style="color:#3a5a7a;">'+$(this).text()+'</li>');	
				});
	   
		$('.list2 .selected').remove();
	});

	$('#move_right').click(function() {
		
		$("#qtyid").show();
		var length = $('.list1 .selected').size();
		$('.list1 .selected').each(function(){
				
				 var idval = $(this).attr('id');
				 $('.list2').append('<li style="padding-bottom: 10px;" id='+idval+'><label class="col-lg-3 control-label" style="width: 100px;  margin: 0px;padding: 0px;"><span >'+$(this).text()+'</span></label><input type="hidden" name="itemval[]" id="itemval[]" value='+idval+'>&nbsp;&nbsp;<input type="text" name="Qty['+idval+']" id="Qty['+idval+']" style="width:100px"></li>');
				
				});
	
		$('.list1 .selected').remove();
	});
	
	
});
</script> 
<!-- Close : validation script -->
<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>