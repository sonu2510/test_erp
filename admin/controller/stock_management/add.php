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

//edit user
$edit = '';
$stock='';
if(isset($_GET['stock_id']) && !empty($_GET['stock_id'])){
	
		$stock_id = decode($_GET['stock_id']);
		//echo $stock_id;
		$stock = $obj_stock->getStockData($stock_id);
		//printr($stock);
		//die;
		$edit = 1;
}
/*if(isset($_GET['invoice_product_id']) && !empty($_GET['invoice_product_id'])){
	
	$invoice_product_id = decode($_GET['invoice_product_id']);
	echo $invoice_product_id;
	
	$invoice_product = $obj_invoice->getInvoiceProductId($invoice_product_id);
	
}*/
if($display_status){
	
	if(isset($_POST['btn_save'])){
	$post = post($_POST);
	//printr($post);
	//die;
	$insert_id = $obj_stock->addstock($post);
	$obj_session->data['success'] = ADD;
	page_redirect($obj_general->link($rout, '', '',1));
	}
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//$stock_id = $stock_id['stock_id'];
		$obj_stock->updatestock($stock_id,$post);
		//die;
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout,'', '',1));
	}
	
?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-user"></i> User</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>
        
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> Stock Management Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
            
             <div class="form-group">
                <label class="col-lg-3 control-label">Order No</label>
              	<div class="col-lg-3">
           			<input type="text" name="orderno" id="orderno" value="<?php echo isset($stock['order_no']) ? $stock['order_no'] : '' ; ?>"  class="form-control validate[required]">
            	</div>
             </div>
             <div class="form-group">
             <label class="col-lg-3 control-label">Description</label>
             <div class="col-lg-3">
             <select name="description" id="description" class="form-control validate[required]" >
              <option value="">Select</option>
              <option value="1" <?php echo (isset($stock['description']) && $stock['description'] == 1)?'selected':'';?>>Store</option>
              <option value="2" <?php echo (isset($stock['description']) && $stock['description'] == 2)?'selected':'';?>>Dispatched</option>
              <option value="3" <?php echo (isset($stock['description']) && $stock['description'] == 3)?'selected':'';?>>Goods Returned</option>		         
            </select>
            </div>
          </div>

              <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Product</label>
                        <div class="col-lg-6">
                            <?php
                            $products = $obj_stock->getActiveProduct();//echo $invoice_product_id;
                            ?>
                            <select name="product" id="product" class="form-control validate[required]">
                            <option value="">Select Product</option>
                                <?php
								//$post['product'] = 3;
                                foreach($products as $product){
                                    if(isset($stock['product']) && $stock['product'] == $product['product_id']){ ?>
                                        <option value="<?php echo $product['product_id']; ?>" selected="selected" ><?php echo $product['product_name']; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $product['product_id']; ?>"> <?php echo $product['product_name']; ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
              </div>
            <div class="form-group">
            	<label class="col-lg-3 control-label">Rack Name</label>
                <div class="col-lg-3">
                    <select name="goods_id" id="name" class="form-control validate[required]" >
                        <option value="">Select Name</option>
                        <?php $rows= $obj_stock->getRowColumn();              
                        	foreach($rows as $row) { ?>                   
                                <option value="<?php echo $row['goods_master_id']; ?>"
                                <?php
								if(isset($_GET['stock_id']) && $stock['goods_id'] == $row['goods_master_id']) echo 'selected="selected"';
								?>
                                ><?php echo $row['name']; ?></option>                   
                        <?php } ?>
                    </select>
                </div>
            </div>
             <div class="form-group">
                <label class="col-lg-3 control-label">Quantity</label>
                <div class="col-lg-3">
                  <input type="text" name="qty" id="qty" value="<?php echo (isset($stock['qty']))?$stock['qty']:'';?>" placeholder="Qty" class="form-control">
                </div>
             </div>
                            
            <div class="form-group">
             <label class="col-lg-3 control-label">Row</label>
             
             <div class="col-lg-3" id="row_result">
             <?php
			 if(isset($_GET['stock_id']) && !empty($_GET['stock_id'])) {				 
			 ?>
             <select name="row" id="row" class="form-control validate[required]" >
                <option value="">Select Row</option>
                <?php
                $row= $obj_stock->getGoodsRowColumn($stock['goods_id']);
                for($i=0;$i<=$row['row'];$i++) {		?>
                    <option value="<?php echo $i; ?>"
                    <?php
						if($stock['row'] == $i) echo 'selected="selected"';
					?> > <?php echo ($i); ?></option>
                <?php } ?>
            </select><?php } else {?>
				<span class="btn btn-danger btn-xs" class="form-control validate[required]">Please select Name for Row option.</span>
            <?php } ?>
            </div>
            
          </div>
              
              <div class="form-group">
                 <label class="col-lg-3 control-label">Column</label>
                 <div class="col-lg-3" id="column_result">
                 <?php
					 if(isset($_GET['stock_id']) && !empty($_GET['stock_id'])) {				 
					 ?>
					 <select name="column_name" id="column" class="form-control validate[required]" >
						<option value="">Select Row</option>
						<?php
						$row= $obj_stock->getGoodsRowColumn($stock['goods_id']);
						for($i=1;$i<=$row['column_name'];$i++) {		?>
							<option value="<?php echo $i; ?>"
                            <?php
							if($stock['column_name'] == $i) echo 'selected="selected"';
							?> > <?php echo $i; ?></option>
						<?php } ?>
					</select><?php } else {?>
                 	<span class="btn btn-danger btn-xs" class="form-control validate[required]">Please select Name for Column option.</span>
                    <?php } ?>
                </div>
              </div>
             
               <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
             	<select name="status" id="status" class="form-control">
                  <option value="1" <?php echo (isset($stock['status']) && $stock['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($stock['status']) && $stock['status'] == 0)?'selected':'';?>> Inactive</option>
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
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a> </div>
              </div>
              
            </form>
          </div>
        </section>
        
      </div>
    </div>
  </section>
</section>
<style type="text/css">
.btn-on.active {
    background: none repeat scroll 0 0 #3fcf7f;
}
.btn-off.active{
	background: none repeat scroll 0 0 #3fcf7f;
	border: 1px solid #767676;
	color: #fff;
}

</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>ckeditor3/ckeditor.js"></script>
<style type="text/css">
@media (max-width: 400px) {
  .chunk {
    width: 100% !important;
  }
}
</style>

<script>
       jQuery(document).ready(function(){	
			checkZipper();
	   });
$("#name").change(function() {
	var id=$(this).val();
	
	if(id != ''){
		//get row
		var row_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getRow', '',1);?>");
		$.ajax({
			url : row_url,
			method : 'post',
			data: {id : id},
			success: function(response){
				if(response != '') {
					$("#row_result").html(response);
				} else {
					$msg = '<span class="btn btn-danger btn-xs">Please Change Name for Row option.</span>';
					$("#row_result").html(msg);
				}
				//alert(response);
			}
		});
		//get columnn
		var column_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getColumn', '',1);?>");
		$.ajax({
			url : column_url,
			method : 'post',
			data: {id : id},
			success: function(response){
				if(response != '') {
					$("#column_result").html(response);
				} else {
					$msg = '<span class="btn btn-danger btn-xs">Please Change Name for Column option.</span>';
					$("#column_result").html(msg);
				}
				//alert(response);
			}
		});
	} else {
		$row_msg = '<span class="btn btn-danger btn-xs">Please Change Name for Row option.</span>';
		$("#row_result").html(row_msg);
		$column_msg = '<span class="btn btn-danger btn-xs">Please Change Name for Column option.</span>';
		$("#column_result").html(column_msg);
		
	}
});	
$("#btn_save").click(function(){
	if($("#form").validationEngine('validate')){
		return true;
	} else {
		return false;
	}
});
$("#btn_update").click(function(){
	if($("#form").validationEngine('validate')){
		return true;
	} else {
		return false;
	}
});
	   
/*$("#invoiceno").change(function() {
      var checknum=$(this).val();
	  //alert(checknum);
	  if(checknum==0)
	  {
		  alert("Please Enter Valid Number");
		  $("#invoiceno").val(""); 
	  }
	  
      if(checknum!=''){
		var check_invoice_no = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=checkInvoiceNo', '',1);?>");
         $.ajax({
                url : check_invoice_no,
				method : 'post',
                data: {checknum:checknum},
                success: function(response){
                    if(response == $('#invoiceno').val()){
                        alert('Your entered Invoice No is already Added'); 
						 $("#invoiceno").val("");                         
                    } 
                }
            });
      }
   });  	   
	   
function removeInvoice(invoice_product_id)
{
$("#alertbox_"+invoice_product_id).modal("show");
$(".modal-title").html("Delete order".toUpperCase());
$("#setmsg").html("Are you sure you want to delete ?");
$("#popbtnok_"+invoice_product_id).click(function(){
	var remove_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeInvoice', '',1);?>");
	$.ajax({
		url : remove_invoice_url,
		method : 'post',
		data : {invoice_product_id : invoice_product_id},
		success: function(response){
			if(response == 0) {
			$("#alertbox_"+invoice_product_id).hide();
		}
			$("#alertbox_"+invoice_product_id).hide();
			$("#alertbox_"+invoice_product_id).modal("hide");
			$('#invoice_product_id_'+invoice_product_id).html('');
			set_alert_message('Order successfully deleted','alert-success','fa fa-check');
			},
		error: function(){
			return false;	
		}
	});
	
		$("#alertbox_"+invoice_product_id).hide();
		$("#alertbox_"+invoice_product_id).modal("hide");
 });
 
}

	   
	$(document).ready(function() {
	 $("#invoicedate").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
		});			
	
	$("#product").change(function(){
			var val = $(this).val();
			var text = $("#product option[value='"+val+"']").text().toLowerCase();
			if(val==10)
			{
				$("#wv").prop("disabled",true);
				$("#nv").prop('checked', 'checked');
				jQuery("input[name='spout[]']").each(function(i) {
					if($(this).val()!='MQ==')
						jQuery(this).prop("disabled",true);
					else
						$(this).prop('checked', 'checked');
				});
				jQuery("input[name='accessorie[]']").each(function(i) {
					if($(this).val()!='NA==')
						jQuery(this).prop("disabled",true);
					else
						$(this).prop('checked', 'checked');
				});
				$("#layer option").hide();
				$("#layer option[value='MQ==']").show();
				$("#layer option[value='MQ==']").attr("selected","selected");
			}
			else
			{
				$("#wv").prop("disabled",false);
				$("#layer option").show();
				jQuery("input[name='spout[]']").each(function(i) {
					jQuery(this).prop("disabled",false);
					if($(this).val()=='MQ==')
						$(this).prop('checked', 'checked');
				});
				jQuery("input[name='accessorie[]']").each(function(i) {
					jQuery(this).prop("disabled",false);
					if($(this).val()=='NA==')
						$(this).prop('checked', 'checked');
				});
			}
			
			checkZipper();
			$('#effectdiv').html('');
			var zipper_id=$("input[class='zipper']:checked").val();
		});
function checkZipper(){
	var gusset_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkProductZipper', '',1);?>");
	var product_id = $('#product').val();
	$.ajax({
		type: "POST",
		url: gusset_url,
		dataType: 'json',
		data:{product_id:product_id}, 
		success: function(response) {
			$('#zipper_div').html(response);	
		}
	});
}

$("#invoice_update").click(function() {
	if($("#form").validationEngine('validate')){
	var update_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateInvoiceProduct', '',1);?>");
			var formData = $("#form").serialize();
			
			$.ajax({
				url : update_invoice_url,
				method : 'post',		
				data : {formData : formData},
				success: function(response){
					if(response != 0){
						$("#display-invoice").html("");
						$("#display-invoice").html(response);
						$('#invoice_update').hide(); //TO hide
						$('#btn_save2').show();
						$("#product").val('');
						$("#netweight").val('');	
						$("#size").val('');
						$("#color_0").val('');
						$("#measurement").val('');
						$("#qty").val('');
						$("#rate").val('');
						$('#myTable tr').not(':eq(0)').not(':eq(0)').remove();
						$("#measurement").val("");
					}
				},
				error: function(){
		
					return false;
				}
			
			});
	}
	
});

$(document).ready(function() {
	var selbox = $('#myTable tr').length;
	var totalsel = (selbox-1);
	
	for ( var i = 0; i < totalsel; i++ ) {
		var k = (+i+1);
		var option = $("#color_"+i+" option:selected").text();
		var id = $('#color_'+i+'').val();
	
		$("#color_"+k+" option[value='"+id+"']").remove();
		var j =(+k+1);
		var id2 = $('#color_'+k+'').val();
		$("#color_"+j+" option[value='"+id+"']").remove();
	}
});

$("#btn_update").click(function() {
	var update_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateInvoiceRecord', '',1);?>");
			var formData = $("#form").serialize();
		
			$.ajax({
				url : update_invoice_url,
				method : 'post',		
				data : {formData : formData},
				success: function(response){
					if(response != 0){
					   window.location = "<?php echo $obj_general->link($rout, '', '',1); ?>";
					}
				},
				error: function(){		
					return false;
				}
			
			});
});

$("#btn_save").click(function(){
	if($("#form").validationEngine('validate')){
			var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addInvoice', '',1);?>");
				var formData = $("#form").serialize();
				$.ajax({
					url : add_product_url,
					method : 'post',		
					data : {formData : formData},
					success: function(response){
	
						if(response != 0){
							$("#invoiceno").prop('disabled', true);
							$("#invoicedate").prop('disabled', true);
							$("#ref_no").prop('disabled', true);
							$("#buyersno").prop('disabled', true);
							$("#other_ref").prop('disabled', true);
							$("#country_final").prop('disabled', true);
							$("#pre_carrier").prop('disabled', true);
							$("#consignee").prop('disabled', true);
							$("#other_buyer").prop('disabled', true);
							$("#other_buyer").prop('disabled', true);
							$("#portofload").prop('disabled', true);
							$("#currency").prop('disabled', true);
							$("#vessel_name").prop('disabled', true);
							$("#port_of_dis").prop('disabled', true);
							$("#final_destination").prop('disabled', true);
							$("#tran1").prop('disabled', true);
							$("#trans2").prop('disabled', true);
							$("#payment_terms").prop('disabled', true);
							$("#hscode").prop('disabled', true);
							$("#delivery").prop('disabled', true);
							$("#printedpouches").prop('disabled', true);
							$("#pouch_desc").prop('disabled', true);
							$("#tran_desc").prop('disabled', true);
							$("#tran_charges").prop('disabled', true);
							$("#netweight").val('');							
							$("#product").val('');
							$("#size").val('');
							$("#color_0").val('');
							$("#measurement").val('');
							$("#qty").val('');
							$("#rate").val('');
							$('#myTable tr').not(':eq(0)').not(':eq(0)').remove();
							$("#measurement").val("");
							$("#invoice_results").html(response)
							set_alert_message('Invoice successfully added','alert-success','fa fa-check');
						}
					},
					error: function(){
			
						return false;
					}
				
				});
		}
		else {
			return false;
		}
	
});
$("#btn_save2").click(function(){
	if($("#form").validationEngine('validate')){
			var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addInvoice-second', '',1);?>");
				var formData = $("#form").serialize();
				$.ajax({
					url : add_product_url,
					method : 'post',		
					data : {formData : formData},
					success: function(response){
	
						if(response != 0){
						$("#netweight").val('');
							$("#measurement").val('');
							$("#product").val('');
							$("#size").val('');
							$("#color_0").val('');
							$("#measurement").val('');
							$("#qty").val('');
							$("#rate").val('');
							$('#myTable tr').not(':eq(0)').not(':eq(0)').remove();
							$("#display-invoice").html(response)
							set_alert_message('Invoice successfully added','alert-success','fa fa-check');
						}
					},
					error: function(){
			
						return false;
					}
				
				});
		}
		else {
			return false;
		}
	
});
function add_invoices() {
	$("#product").prop('disabled', true);
	$("#size").prop('disabled', true);
	$("#color_0").prop('disabled', true);
	$("#qty").prop('disabled', true);
	$("#rate").prop('disabled', true);
	$("#netweight").prop('disabled', true);
	$("#measurement").prop('disabled', true);
}
function remove_tr(tr_id) {
	$("#tr_"+tr_id).remove();
}
$(' .addmore').click(function(){
		var html = '';

		var count = $('#myTable tr').length;
		var color = (count-1);
		//var size_id = (count+1);
		var size_id = (color-1);
		var arrm = jQuery.parseJSON($('#mea_arr').val());
		var countAddmore = $('select#color_0 option#option').length;
		var size = $("#color_"+size_id).html();
			html +='<tr><td>';
			html += '<select id="color_'+color+'" name="color['+color+'][color]" class="form-control validate[required]" >';
			html += size;
			html +=  '</select>';							
			html += '</td>';			
			html +='<td>';
			html += '<input type="text" name="color['+color+'][qty]" value="" class="form-control validate[required,custom[number],min[1]]" placeholder="Qty">';
			html += '</td>';			
			html +='<td>';
			html += '<input type="text" name="color['+color+'][rate]" value="" class="form-control validate[required,custom[number],min[0.001]]" placeholder="Rate">';
			html +='</td>';	
				html +='<td>';
				 html += '<input type="text" name="color['+color+'][size]" value="" class="form-control validate[required,custom[number]]" placeholder="Size">';
				html +='</td>';
				
				var mea = $("#measurement").html();
				html +='<td>';
				html += '<select id="color'+measurement+'" name="color['+color+'][measurement]" class="form-control validate[required]" >';
				html += mea;
				 html +=  '</select>';							
				html += '</td>';
			
			html +='<td>';
			   html +='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" ><i class="fa fa-minus"></i></a>';
			html +='</td></tr>';

		$('.tool-row').append(html);
		var color_id = (color-1);
		var option = $("#color_"+color_id+" option:selected").text();
		var id = $('#color_'+color_id+'').val();
		if(count >= countAddmore) {
			$("#addmore").hide();
		}
		var new_id = +id+1;
		$("#color_"+color+" option[value='"+id+"']").remove();
		
		$('.remove').click(function(){
			$("#addmore").show();
			$(this).parent().parent().remove();
		});
		
});*/



</script> 
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>