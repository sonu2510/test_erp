<?php
// mansi
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
$edit = '';

if($display_status) {
	
	if(isset($_POST['btn_save'])){
	$post = post($_POST);
	$insert_id = $obj_qty->addQty($post);
	$_SESSION['success'] = ADD;
	page_redirect($obj_general->link($rout, '', '',1));
	
} 

//edit
if(isset($_POST['btn_update'])){
	$post = post($_POST);
	$obj_qty->updateQty($post);
	$_SESSION['success'] = UPDATE;
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
          <header class="panel-heading bg-white"> Quantity Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" name="form" id="form" method="post" enctype="multipart/form-data">
              
              <table border="0"  width="100%" class="table  b-t text-small">
              	
                <tr>
                	<td><b>From Qty</b></td>
                	<td> <b>To Qty</b> </td>
                   	<!--<td><b>Price</b></td>-->
                </tr>
                
               	<tr>
                	<td colspan="6">
              			
                        <table class="tool-row table  b-t text-small" id="myTable">
                			<?php 
							// mansi 23-12-2015
							 $total_qty_price = $obj_qty->getTotalQty();
								 $total_qty_price_data = $obj_qty->getQtyPrice();	
								  if(isset($total_qty_price_data) && !empty($total_qty_price_data))
									{
									 $total_qty_price_data =$total_qty_price_data;
									}
								   else
									{
										$total_qty_price_data[]= array(
												'client_qty_id' => '',
												'from_qty' => '',
												'to_qty' => '',
												//'price' => '',
												'date_modify' =>'',
												'date_added' => '',
												'is_delete' =>'',
												'user_id' =>'',
												'user_type_id' =>''
											);
									}	
							  if(isset($total_qty_price_data) && !empty($total_qty_price_data)){
							   		$count = 0;
							   		foreach ($total_qty_price_data as $qtyRate)
							   		{
										$id = $qtyRate['client_qty_id'];
									?>	 
                                   
                                    <tr>
                                         	<input type="hidden" name="qty_master[<?php echo $count; ?>][client_qty_id]" id="client_qty_id_<?php echo $count; ?>" value="<?php echo $qtyRate['client_qty_id']; ?>" class="form-control validate[required,custom[number]]" >
                              			<td>
                           						<input type="text" name="qty_master[<?php echo $count; ?>][from_qty]" id="form_<?php echo $count; ?>" value="<?php echo $qtyRate['from_qty']; ?>" class="form-control validate[required,custom[number]]" placeholder="From Qty" />
                            				</td>
                           					
                                        <td>
                                        		<input type="text" name="qty_master[<?php echo $count; ?>][to_qty]" id="to_<?php echo $count; ?>" value="<?php echo $qtyRate['to_qty']; ?>" class="form-control validate[required,custom[number]]" placeholder="To Qty" />
                                    		</td>
                                    		
                                       <!-- <td>
                                    	 		<input type="text" name="qty_master[<?php //echo $count; ?>][price]" id="price_<?php //echo $count; ?>" value="<?php //echo $qtyRate['price']; ?>" class="form-control validate[required,custom[number]]" placeholder="Price" />
                         					</td>-->
                                    		
                                    <?php if($count==0){ ?>
                                        <td>
                                                <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add Profit" ><i class="fa fa-plus"></i></a>
                                       </td>
                                    <?php } else { ?>
                              			<td>
                                          <a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" onClick="remove_row(<?php echo $qtyRate['client_qty_id'];?>)"><i class="fa fa-minus"></i></a>
                                	</td>
                                    <?php } ?>
                              	</tr>
                               		<?php $count++; } } ?>
                                </table>
                    </td>
                 </tr>
                 
                <tr>
                            <td colspan="6"><div class="form-group">
                                <div class="col-lg-9 col-lg-offset-3">  
                                 <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add Profit" ><i class="fa fa-plus"></i></a>         
                                <?php if($id){?>
                                    <button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                                <?php } else { ?>
                                    <button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
                                <?php } ?>  
                                  <!--<a class="btn btn-default" href="<?php //echo $obj_general->link($rout, '', '',1);?>">Cancel</a>-->
                                </div>
                              </div>
                            </td>
                        </tr>
                        
              </table>
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
<!-- select2 --> <script src="<?php echo HTTP_SERVER;?>js/select2/select2.min.js"></script>
<script>
    jQuery(document).ready(function(){
	
	 jQuery("#form").validationEngine();
	 
	 });
	 
<?php //if($edit){?>	
//Start : zone	
$(document).on('click', ".addmore", function () {
	//alert("btn");
	more_price();
	
	$(".addnew").click(function(){
		var cnt = $(this).attr('id');
		var from_qty = $("#form_"+cnt).val();
		var to_qty = $("#to_"+cnt).val();
		var price = $("#price_"+cnt).val();
		//alert(cnt+"===="+from_qty+"==="+to_qty+"=="+price);return false;
		
		//var client_qty_id = '';
		//alert(client_qty_id);return false;
		if(from_qty.length > 0 && to_qty.length > 0 && price.length > 0){
			
			var add_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=addQtyPrice', '',1);?>");
			$.post(add_url,{client_qty_id:client_qty_id,fQty:from_qty,tQty:to_qty,price:price},function(result){
				var response = JSON.parse(result);
				if(typeof response.success != 'undefined'){
					set_alert_message(response.success,"alert-success","fa-check");
				}else{
					set_alert_message(response.warning,"alert-warning","fa-warning");
				}
			});
		}else{
			set_alert_message("insert valide input!","alert-warning","fa-warning");
		}
	});
});

$(document).on('click', ".removetPrice", function () {
    var id = $(this).attr("id");
	$(this).parent().closest(".form-group").remove();
});
function more_price(){
	//alert("hiii");
	var count = $('#myTable tr').length;
	var html = '';
	html +='<tr>';
	
	html +='<td>';
	  html += '<input type="text" name="qty_master['+count+'][from_qty]]" value="" class="form-control validate[required]" placeholder="From Qty">';
	html += '</td>';
	  
	html +='<td>';
	  html += '<input type="text" name="qty_master['+count+'][to_qty]" value="" class="form-control validate[required,custom[number]]" placeholder="To Qty">';
	html +='</td>';
	
	/*html +='<td>';
		html +='<input type="text" name="qty_master['+count+'][price]" value="" class="form-control validate[required,custom[number]]" placeholder="Price">';
	html +='</td>';*/
	
	html +='<td><input type="hidden" name="qty_master['+count+'][client_qty_id]" value="" class="form-control validate[required,custom[number]]" >';
		html +='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-minus"></i></a>';
	html +='</td></tr>';
	
	$("#myTable").append(html);
	
	$(' .remove').click(function(){
		$(this).parent().parent().remove();
	});
}

$(' .remove').click(function(){
		$(this).parent().parent().remove();
});

function remove_row(client_qty_id)
{
	var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=delQty', '',1);?>");
			$.ajax({
				url : remove_url,
				method : 'post',
				data : {client_qty_id : client_qty_id},
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