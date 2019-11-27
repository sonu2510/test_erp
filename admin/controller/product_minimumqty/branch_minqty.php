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
	'text' 	=> 'Branch List',
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
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
	//printr($post);die;
	$obj_qty->updateQty($post);
	$_SESSION['success'] = UPDATE;
	page_redirect($obj_general->link($rout, '', '',1));
}
//printr($obj_qty->sendminiqtyMail());
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
                	<td></td>
                	<td><b>Product Codes</b></td>
                	<td><b>Description</b></td>
                    <td> <b>Minimum Qty</b> </td>
                    <td> <b>Rack Qty</b> </td>
                   	<!--<td><b>Price</b></td>-->
                </tr>
                
               	<tr>
                	<td colspan="6">
              			
                        <table class="tool-row table  b-t text-small" id="myTable">
                			<?php 
							// mansi 23-12-2015
							 
								 $total_qty_price_data = $obj_qty->getQtyPrice(decode($_GET['branch_id']));	
								 
								 
								 $productcodes = $obj_qty->getproductcodes();
								    if(isset($total_qty_price_data) && !empty($total_qty_price_data))
									{
										$total_qty_price_data =$total_qty_price_data;
										
									}
								    else
									{
										$total_qty_price_data[]= array(
												'min_qty_id' => '',
												'product_code_id' => '',
												'description' => '',
												'min_qty' => '',
												'rack_qty' => '',
												'qty' => '',
												'date_modify' =>'',
												'date_added' => '',
												'is_delete' =>'',
												'user_id' =>'',
												'user_type_id' =>'',
												'product_code'=>''
											);
										
										
									}	
							  if(isset($total_qty_price_data) && !empty($total_qty_price_data)){
							   		$count = 0;
							   		foreach ($total_qty_price_data as $qtyRate)
							   		{
										$id = $qtyRate['min_qty_id'];
										
										$fan='';
										
										$rack_qty = $obj_qty->get_disc($qtyRate['product_code_id'],decode($_GET['branch_id']));
												$dis_qty_la = $r_qty = 0;
											if($qtyRate['product_code']!=''){
												
												foreach($rack_qty as $dis_qty)
												{
													$dispatch_qty=$obj_qty->gettotaldispatchSales($dis_qty['grouped_s_id']);
													$dis_qty_la +=$dispatch_qty['total'];
													$r_qty +=$dis_qty['qty'];
												}
											}
										
										$remaining_qty = $r_qty-$dis_qty_la;
										//printr($qtyRate['min_qty']);
									?>	 
                                   
                                   
                                    <tr>
                                    		<input type="hidden" id="min_arr" value='<?php echo json_encode($productcodes);?>' />
                                         	<input type="hidden" name="qty_master[<?php echo $count; ?>][min_qty_id]" id="min_qty_id_<?php echo $count; ?>" value="<?php if(isset($qtyRate['min_qty_id'])&&!empty($qtyRate['min_qty_id'])){ echo $qtyRate['min_qty_id']; }else{ echo "0";}?>" class="form-control validate[required,custom[number]]" >
                              			<td>
                                       <?php
												//if(!empty($rack_qty)){
														if($remaining_qty <	 $qtyRate['min_qty']){?>
													  
															<marquee behavior="alternate">
															  <img src="<?php echo HTTP_SERVER.'admin/controller/product_minimumqty/red_icon.png';?>" style="width: 35px;height: 35px;">
															</marquee>
												<?php }//}
																								?>
                                        </td>
                                        <td>
                           					<div onclick="open_tab(<?php echo $count; ?>)">
                                                <select style="width:190px;" name="qty_master[<?php echo $count; ?>][product_keyword]" id="product_keyword<?php echo $count; ?>" class="form-control validate[required] chosen_data" onchange="getdesc(<?php echo $count;?>)">
                                                            <option value="">Select Product</option>
                                                            <?php  foreach($productcodes as $code)
                                                               {
                                                                   if($code['product_code_id']==$qtyRate['product_code_id'])
                                                                    echo '<option value="'.$code['product_code_id'].'" selected="selected">'.$code['product_code'].'</option>';
                                                                   else
                                                                        echo '<option value="'.$code['product_code_id'].'">'.$code['product_code'].'</option>';
                                                               }
                                                               ?>
                                                        </select>
                                                   </div>
                                                </td>  
                                                <td>   
                                                       <input type="text" name="qty_master[<?php echo $count;?>][product_name]" id="product_name_<?php echo $count; ?>" value="<?php echo $qtyRate['description'];?>"  readonly="readonly" class="form-control validate" style="width:400px"/>
                                               </td>
                           				 <td>
                                        		<input type="text" name="qty_master[<?php echo $count; ?>][min_qty]" id="min_<?php echo $count; ?>" value="<?php echo $qtyRate['min_qty']; ?>" class="form-control validate[required,custom[number]]" placeholder="Min Qty" />
                                    	</td>
                                    		
                                        <td>
                                        		<input type="text" name="qty_master[<?php echo $count; ?>][rack_qty]" id="rack_<?php echo $count; ?>" value="<?php  if(isset($remaining_qty)&& !empty($remaining_qty)){echo $remaining_qty;}else{echo '0';}; ?>" class="form-control validate" readonly placeholder="Rack Qty" />
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
                                          <a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" onClick="remove_row(<?php echo $qtyRate['min_qty_id'];?>)"><i class="fa fa-minus"></i></a>
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
                                <?php
								if($obj_session->data['ADMIN_LOGIN_SWISS']!= '1' && $obj_session->data['LOGIN_USER_TYPE'] != '1') 
								{
									if($id){?>
                                    <button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                                <?php } else { ?>
                                    <button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
                                <?php } 
								}?>  
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
<style type="text/css" id="z">

a.fancybox img {

	border: none;

	box-shadow: 0 1px 7px rgba(0,0,0,0.6);

	-o-transform: scale(1,1); -ms-transform: scale(1,1); -moz-transform: scale(1,1); -webkit-transform: scale(1,1); transform: scale(1,1); -o-transition: all 0.2s ease-in-out; -ms-transition: all 0.2s ease-in-out; -moz-transition: all 0.2s ease-in-out; -webkit-transition: all 0.2s ease-in-out; transition: all 0.2s ease-in-out;

} 

a.fancybox:hover img {

	position: relative; z-index: 999; -o-transform: scale(1.03,1.03); -ms-transform: scale(1.03,1.03); -moz-transform: scale(1.03,1.03); -webkit-transform: scale(1.03,1.03); transform: scale(1.03,1.03);

}
	
div #fancybox-content
{
	border-width: 1px !important;
}
</style>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<!-- select2 --> <script src="<?php echo HTTP_SERVER;?>js/select2/select2.min.js"></script>

<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href=" https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 


<script>
    jQuery(document).ready(function(){
	
	 	jQuery("#form").validationEngine();
	 	
		 $(".chosen_data").chosen();
	 });
	 
//Start : zone	
$(document).on('click', ".addmore", function () {
	more_price();
});

$(document).on('click', ".removetPrice", function () {
    var id = $(this).attr("id");
	$(this).parent().closest(".form-group").remove();
});
function more_price(){
	var count = $('#myTable tr').length;
	var arr = jQuery.parseJSON($('#min_arr').val());
	var html = '';
	
	html +='<tr><td></td><td><div onclick="open_tab('+count+')">';
	  html += '<select name="qty_master['+count+'][product_keyword]" id="product_keyword'+count+'" class="form-control validate[required] chosen-select" onchange="getdesc('+count+')"><option value="">Select Product</option>';
	  for(var i=0;i<arr.length;i++)
	  {
	 html += '<option value="'+arr[i].product_code_id+'">'+arr[i].product_code+'</option>';
	}
	  html +=  '</select></div></td><td>';							
	html += '<input type="text" name="qty_master['+count+'][product_name]" id="product_name_'+count+'" value=""  readonly="readonly" class="form-control validate" style="width:400px"/></td>';
	
	html +='<td>';
	  html += '<input type="text" name="qty_master['+count+'][min_qty]]" value="" class="form-control validate[required]" placeholder="Min Qty">';
	html += '</td>';
	  
	html +='<td>';
	  html += '<input type="text" name="qty_master['+count+'][rack_qty]" id="rack_'+count+'" value="" class="form-control validate" placeholder="Rack Qty" readonly="readonly">';
	html +='</td>';
	
	html +='<td><input type="hidden" name="qty_master['+count+'][min_qty_id]" value="" class="form-control validate[required,custom[number]]" >';
		html +='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-minus"></i></a>';
	html +='</td></tr>';
	
	$("#myTable").append(html);
	
	$(".chosen-select").chosen();
	
	$(' .remove').click(function(){
		$(this).parent().parent().remove();
	});
}

$(' .remove').click(function(){
		$(this).parent().parent().remove();
});

function remove_row(min_qty_id)
{
	var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=delQty', '',1);?>");
			$.ajax({
				url : remove_url,
				method : 'post',
				data : {min_qty_id : min_qty_id},
				success: function(response){
					
				},
				error: function(){
					return false;	
				}
		});
}
function getdesc(count)
{
	var product_code_id = $("#product_keyword"+count).val();
		var getdesc_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=product_desc', '',1);?>");
			$.ajax({
				url : getdesc_url,
				method : 'post',
				data : {product_code_id : product_code_id},
				success: function(response){
					if(response!='')
					{
						$("#product_name_"+count).removeAttr('readonly','readonly');
						$("#product_name_"+count).val(response);
						$("#product_name_"+count).attr('readonly','readonly');
					}
				},
				error: function(){
					return false;	
				}
		});
	
}
function open_tab(n)
{
	//alert("hii");
	$(document).on('mouseover keyup keydown keypress', '#product_keyword'+n+' + .chosen-container .chosen-results li', function() {
    var productCode_text = $(this).text();
	//alert(productCode_text);
		if(productCode_text!='Select Product')
		{
			var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=get_disc', '',1);?>");
					$.ajax({
						url : url,
						method : 'post',
						data : {productCode_text : productCode_text},
						success: function(response){
						//	alert(response);
								var value = $.parseJSON(response);
								//console.log(value.remaining_qty);
								$("#product_name_"+n).val(value.description);
								$("#rack_"+n).val(value.remaining_qty);				
							},
						error: function(){
							return false;	
						}
					});
		}
	});
	

} 
</script> 
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>