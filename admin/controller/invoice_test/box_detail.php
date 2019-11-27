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
	'href' 	=> $obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'], '',1),
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
/*if(isset($_GET['invoice_no']) && !empty($_GET['invoice_no'])){	
		$invoice_no = decode($_GET['invoice_no']);
		if($invoice_no=='1746')
            $limit = '100';
        else
        $limit = '20';
}*/
 $limit = '20';
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}
if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='ig.box_no';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order']; 
}else{
	$sort_order = 'ASC';
}
//edit user
$edit = '';
if(isset($_GET['invoice_no']) && !empty($_GET['invoice_no'])){	
		$invoice_no = decode($_GET['invoice_no']);
		//echo $invoice_no;
		$invoice = $obj_invoice->getInvoiceData($invoice_no);
		//printr($invoice);
		//die;
		$invoice_id = $invoice['invoice_id'];
		$edit = 1;
}
if(isset($_GET['invoice_product_id']) && !empty($_GET['invoice_product_id'])){
	$invoice_product_id = decode($_GET['invoice_product_id']);
	$invoice_product = $obj_invoice->getInvoiceProductId($invoice_product_id);
}

if($display_status){	
$colors = $obj_invoice->getColor();
$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
//$userCurrency = $obj_invoice->getUserCurrencyInfo($user_type_id,$user_id);
$addedByInfo = $obj_invoice->getUser($user_id,$user_type_id);
//printr($invoice_no);
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> Invoice</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>
        <div class="col-sm-12">
        	<section class="panel">
	          <header class="panel-heading bg-white"> Invoice Detail  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;   
              <?php if($invoice['generate_status']=='0') {?>
              	<a class="btn btn-primary" href="<?php echo $obj_general->link($rout, 'mod=add&invoice_no='.$_GET['invoice_no'].'&inv_status='.$_GET['inv_status'],'',1); ?>">Generate Invoice</a> 
                <?php } ?>
                </header>
    	      <div class="panel-body">
        	    <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
            	<?php if(!isset($_GET['show_status'])) { ?>
            	<div id="invoice_results">
           <?php 
           if(isset($invoice_no) && !empty($invoice_no)) {
			    $invoice_product_second = $obj_invoice->getInvoiceProduct($invoice_no); 
			    ?>
                    <table class="table table-bordered"> 
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>                                
                                             
                            </tr>
                        </thead>
                        <tbody>
    					<?php $colors = $obj_invoice->getColorDetailstotal($invoice_no); 
						//printr($colors);//$option
						//die;
						if($colors!=0)
    					{         
						?>
                         <tr>
							<td>Total</td>
                             <td><?php echo $colors;?></td>
							<td>
                            <?php $gen_id=0;?>
                                <a href="#" onclick="add_box(<?php echo $invoice_id.','.$colors.','.$gen_id.','.$gen_id;?>)" id="btn_edit"  name="btn_edit" class="btn btn-info btn-xs btn_edit">Add Box</a>
                            </td>
						</tr>
                        <?php  } 
                        if (isset($_GET['page'])) {
    						 			$page = (int)$_GET['page'];
    								} else {
    									$page = 1;
    								}
    								$obj_session->data['page'] = $page;
                         			//option use for limit or and sorting function	
    							  $option = array(
    								   'sort'  => $sort_name,
    								   'order' => $sort_order,
    								   'start' => ($page - 1) * $limit,
    								   'limit' => $limit
    							  );
    							 //printr($option);
                        ?>	
                        <tr>
                        	<td></td>
                        	<td></td>
                        	<td><a onclick="set_series(<?php echo $invoice_id;?>)" id="btn_edit"  name="btn_edit" class="btn btn-info btn-xs btn_edit">In Series</a>     
                        	   <a id="btn_edit"  target="_blank"  href="<?php echo $obj_general->link($rout, 'mod=box_detail&invoice_no='.$_GET['invoice_no'].'&show_status=1','',1); ?>" name="btn_edit" class="label bg-success">Show Box Detail</a></td></tr>								
                        <tr>		
                        <tr>
          					<div>
								<?php $html =$obj_invoice->viewDetails($invoice_no,'','','',$option);
                                 echo $html['html'];?>                    
           					</div>
         				 </tr>
         				 
         				 <?php //pagination  https://swissonline.in/admin/index.php?route=invoice_test&mod=box_detail&invoice_no=MTIwOQ==&status=1&inv_status=0
								$pagination = new Pagination();
								$pagination->total = $html['total_box'];
								$pagination->page = $page;
								$pagination->limit = $limit;
								$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
								$pagination->url = $obj_general->link($rout, 't&mod=box_detail&invoice_no='.$_GET['invoice_no'].'&page={page}&limit='.$limit.'&inv_status='.$_GET['inv_status'], '',1);
								$pagination_data = $pagination->render();
								?>
                          </tbody>
                         </table>
          				<?php } ?>
                        
    		</div> 
               
                <?php }
                     else
                     {?>
                        <div id="">
                        <table class="table table-bordered"> 
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>                                
                                                 
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                  					<div>
        								<?php $html =$obj_invoice->viewDetails($invoice_no,'','','',$option,$_GET['show_status']);
                                         echo $html['html'];?>                    
                   					</div>
         				        </tr>
                            </tbody>
                        </div>
			
           	    <?php } ?>
	            </form>
	            <footer class="panel-footer">
                <div class="row">
                  <div class="col-sm-3 hidden-xs"> </div>
                  <?php echo $pagination_data;?>            
                </div>
              </footer>
    	      </div>
        	</section>
	    </div>
    </div>
  </section>
</section>
<div class="modal fade" id="smail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-dialog" style="width:40%;height:50%">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">BOX</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Product & Color</label>
                        <div class="col-lg-8">
                        <?php $colors = $obj_invoice->getColordesc($invoice_id);
						//printr($colors);
						
						//printr($colors);
						?>
						<select name="detail" id="detail" class="form-control validate[required]" onchange="checkvalue()">
                        <option value="">Please Select</option>
						<?php	foreach($colors as $colorss) {  //printr($colorss['remaingqty']); 
						if(isset($colorss['remaingqty']) && $colorss['remaingqty']!=0)
						{?>
                          <option value="<?php echo $colorss['invoice_color_id'].':'.$colorss['remaingqty'].':'.$colorss['invoice_product_id'].':'.$colorss['net_weight'];?>"><?php echo $colorss['product_name'].'-'.$colorss['color'].'-'.$colorss['size'].' '.$colorss['measurement'].'-'.$colorss['color_text'];?></option>	
                         
						<?php }
						}?>
                        </select>
                       </div>
                     </div> 
              </div>
            <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label"><span class="required">*</span> Remaing Qty</label> 
                <div class="col-lg-8">
                <input type="text" name="remaing_qty" id="remaing_qty" value="0" class="form-control validate[required]"/ disabled="disabled">
                </div>
           </div>
           </div>
            <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label"><span class="required">*</span> Box Qty</label> 
                <div class="col-lg-8">
                <input type="text" name="per_qty" id="per_qty" value="" placeholder="Per Box Qty"  class="form-control validate[required]"/>
                </div>
           </div>
           </div>
          
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Box Weight </label>
                        <div class="col-lg-8">
               			 <input type="text" name="per_box_weight" id="per_box_weight" value="" placeholder="Per Box Weight"  class="form-control validate[required]"/>
                		</div>
                     </div> 
              </div>
              
               <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Net Weight </label>
                        <div class="col-lg-8">
               			 <input type="text" name="per_net_weight" id="per_net_weight" value="" placeholder="Net Weight"  class="form-control validate[required]"/>
                		</div>
                     </div> 
              </div>
              
               <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Total No of Boxes </label>
                        <div class="col-lg-8">
               			 <input type="text" name="total_box" id="total_box" value="" placeholder="Total Box"  class="form-control validate[required]"/>
                		</div>
                     </div> 
              </div>
              
             
              <div><input type="hidden" name="invoice_id" id="invoice_id" value="">
        		 <input type="hidden" name="in_gen_id" id="in_gen_id" value="">
                 <input type="hidden" name="net_weight" id="net_weight" value="">
                 <input type="hidden" name="qty" id="qty" value="">
                 <input type="hidden" name="in_product_id" id="in_product_id" value="" />
          	</div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="savelabeldetail()" name="btn_decline" class="btn btn-warning">Save</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="application/javascript">
$('.btn-danger ').click(function(){
		var con = confirm("Are you sure you want to delete ?");
		if(con){
			var in_gen_invoice_id=$(this).attr('id');
			var del_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=deleteBox', '',1);?>");
			//$('#loading').show();
			$.ajax({
				url : del_url,
				type :'post',
				data :{in_gen_invoice_id:in_gen_invoice_id},
				success: function(response){
					//alert(response);
					if(response==1){
						set_alert_message('Successfully Deleted',"alert-success","fa-check");	
						 window.setTimeout(function(){location.reload()},1000)
					}
					//$('#loading').hide();								
				},
				error:function(){
					set_alert_message('Error!',"alert-warning","fa-warning");          
				}			
			});
		}
	});
function edit_box_no(id,pageno)
{
	//alert(pageno);	
	//$("input[type=text][id=gen_id"+id+"]").focusout(function(){
	//alert("hi");
		var  postArray = {};
		postArray['box_no'] = $("input[type=text][id=gen_id"+id+"_page_no"+pageno+"]").val();
		postArray['gen_unique_id'] = $("#gen_unique_id"+id+"_page_no"+pageno).val();
		//alert($("input[type=text][id=gen_id"+id+"]"));
	//alert(postArray['box_no']);
	
   		var boxno_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=update_boxno', '',1);?>");
			$.ajax({
				url : boxno_url,
				method : 'post',
				data : {postArray : postArray},
				success: function(response){
				//alert(response);
				set_alert_message('Successfully Updated',"alert-success","fa-check");
				 window.setTimeout(function(){location.reload()},1000)
				},
				error: function(){
					return false;	
				}
				});
		//});
	
}
	function add_box(invoice_id,qty,in_gen_id,box_weight)
	{
		//alert(box_weight);
		
		$("#smail").modal('show');
		$("#invoice_id").val(invoice_id);
		$("#in_gen_id").val(in_gen_id);
		if(in_gen_id!=0)
		{
			$("#per_box_weight").val(box_weight);
			$("#per_box_weight").attr("disabled","disabled");
		}
		else
			$("#per_box_weight").val('');
		
				
	}
	function checkvalue()
	{
		var detail=$("#detail").val();
		var fin=detail.split(':');
		//alert(fin);
		$("#qty").val(fin[1]);
		//(fin[2]);
		$("#remaing_qty").val(fin[1]);
		$("#in_product_id").val(fin[2]);
		$("#net_weight").val(fin[3]);
		$("#per_net_weight").val(fin[3]);
	}
	function savelabeldetail(){
		var per_qty=parseInt($("#per_qty").val());
		var qty=parseInt($("#qty").val());
		var total_box=parseInt($("#total_box").val());
		//alert(total_box);
		if(qty>=per_qty)
		{
			var label_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=savelabeldetail', '',1);?>");
			var product_id = $('#product').val();
			var formData = $("#sform").serialize();
			$.ajax({
				type: "POST",
				url: label_url,
				data:{formData : formData}, 
					success: function(response) {
						set_alert_message('Successfully Added',"alert-success","fa-check");
					 window.setTimeout(function(){location.reload()},1000)
					}
			});
		}
		else
		{
			alert("Please Enter Proper Quantity"); 	
		}
}
function set_series(inv_id)
{
	var label_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=set_series', '',1);?>");
	//var inv_id = $('#product').val();
			var formData = $("#sform").serialize();
			$.ajax({
				type: "POST",
				url: label_url,
				data:{inv_id : inv_id}, 
					success: function(response) {
						//alert(response);
						set_alert_message('Successfully Updated',"alert-success","fa-check");
					 window.setTimeout(function(){location.reload()},1000)
					}
			});
}
jQuery(document).ready(function(){
		   jQuery("#sform").validationEngine();
});
	</script>
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>