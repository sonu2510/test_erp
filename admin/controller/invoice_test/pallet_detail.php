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
	'href' 	=>  $obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'], '',1),
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
if(isset($_GET['invoice_no']) && !empty($_GET['invoice_no'])){	
		$invoice_no = decode($_GET['invoice_no']);
		//echo $invoice_no;die;
	//	$invoice = $obj_invoice->getInvoiceData($invoice_no);
		//printr($invoice);
		//die;
		$invoice_id = $invoice_no;
		$edit = 1;
}
if($display_status){	
$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
//$userCurrency = $obj_invoice->getUserCurrencyInfo($user_type_id,$user_id);
$addedByInfo = $obj_invoice->getUser($user_id,$user_type_id);
//printr($addedByInfo);
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
	          <header class="panel-heading bg-white"> Pallet Detail </header>
    	      <div class="panel-body">
        	    <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
            	<div id="invoice_results">
           <?php if(isset($invoice_no) && !empty($invoice_no)) {
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
    					<?php $colors = $obj_invoice->getPalletDetailstotal($invoice_id); 
						//printr($colors);
						//die;
						if($colors!=0)
						{
						?>
                         <tr>
							<td>Total Boxes</td>
                             <td><?php echo $colors;?></td>
							<td>
                            <?php $gen_id=0;?>
                                <a href="#" onclick="add_pallet(<?php echo $invoice_id;?>)" id="btn_edit"  name="btn_edit" class="btn btn-info btn-xs btn_edit">Add Pallet</a>
                            </td>
						</tr>
                        <?php  } ?>	
                        </tbody>
                         </table>							
                        <?php $total_pallet = $obj_invoice->getPallet($invoice_id); 
						if($total_pallet)
						{ 
							echo '<table class="table b-t table-striped text-small table-hover">
							<tr><th>Pallet Name</th><th>Action</th></tr>';
						$i=1;
						foreach($total_pallet as $pallet)
						{?>
                        <tr>
          					<td>Pallet Sheet No. <input type="text" class="form-control validate[required]" name="invoice_pallet_no_<?php echo $i;?>" onblur="edit_pallet_no(<?php echo $i;?>)" id="invoice_pallet_no_<?php echo $i;?>" value="<?php echo $pallet['pallet_no'];?>" style="  width: 50px;  display: inline;"  />
						 <input type="hidden" name="invoice_pallet_id_<?php echo $i;?>" id="invoice_pallet_id_<?php echo $i;?>" value="<?php echo $pallet['pallet_id'];?>"/></td>
                            <td> <a href="#" onclick="add_pallet_box(<?php echo $pallet['invoice_id'].','.$pallet['pallet_id'].','.$pallet['pallet_no'];?>)" id="btn_edit"  name="btn_edit" class="btn btn-info btn-xs btn_edit">Add Box</a>
                            <a class="btn btn-danger btn-sm" onclick="delete_pallet(<?php echo $pallet['invoice_id'].','.$pallet['pallet_id'];?>)" href="javascript:void(0);"><i class="fa fa-trash-o"></i></a>
                            </td>
         				 </tr>
                         <?php $i++; }
						 echo '</table>';
						 }?>
          				<?php } ?>                       
			    		</div> 
                  </form>
    	      </div>
        	</section>
	    </div>
    </div>
  </section>
</section>
<div class="modal fade" id="smail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-dialog" style="width:60%;height:50%">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="pallet_head"></h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Box No , Product & Color</label>
                        <div class="col-lg-8" id="comb">
                       </div>
                     </div> 
                  
              </div>
              <div><input type="hidden" name="invoice_id" id="invoice_id" value="">
              <input type="hidden" name="pallet_id" id="pallet_id" value="">
          	</div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="savePalletdetail()" name="btn_decline" class="btn btn-warning">Save</button>
              </div>
   		</form>   
    </div>
  </div>
</div>


<div class="modal fade" id="pallet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-dialog" style="width:40%;height:50%">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="pform" id="pform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Pallets</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Total No Of Pallets</label>
                        <div class="col-lg-8">
                       <input type="text" name="total_pallet" id="total_pallet" value="" class="form-control validate[required]">
                       </div>
                     </div> 
              </div>
           <div><input type="hidden" name="invoice_no" id="invoice_no" value=""></div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="savepallet()" name="btn_save" class="btn btn-warning">Save</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/multiple-select.css" />
<script src="<?php echo HTTP_SERVER;?>js/jquery.multiple.select.js"></script>


<script type="application/javascript">
function delete_pallet(invoice_id,pallet_id){
		var con = confirm("Are you sure you want to delete ?");
		if(con){
			var del_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=deletePallet', '',1);?>");
			$('#loading').show();
			$.ajax({
				url : del_url,
				type :'post',
				data :{invoice_id:invoice_id,pallet_id:pallet_id},
				success: function(response){
					set_alert_message('Successfully Deleted',"alert-success","fa-check");	
					 window.setTimeout(function(){location.reload()},1000)
					$('#loading').hide();								
				},
				error:function(){
					set_alert_message('Error!',"alert-warning","fa-warning");          
				}			
			});
		}
}
function edit_pallet_no(id)
{
	var  postArray = {};
	postArray['pallet_no'] = $("input[type=text][id=invoice_pallet_no_"+id+"]").val();
	postArray['pallet_id'] = $("#invoice_pallet_id_"+id).val();
	var pallet_no_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=update_pallet_no', '',1);?>");
		$.ajax({
			url : pallet_no_url,
			method : 'post',
			data : {postArray : postArray},
			success: function(response){
			set_alert_message('Successfully Updated',"alert-success","fa-check");
			 window.setTimeout(function(){location.reload()},1000)
			},
			error: function(){
				return false;	
			}
			});
}
	function add_pallet_box(invoice_id,pallet_id,pallet_no)
	{
		$("#smail").modal('show');
		$("#invoice_id").val(invoice_id);
		$("#pallet_id").val(pallet_id);
		$('#pallet_head').html('Pallet Sheet No. '+pallet_no);
		var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getComb', '',1);?>");
			$.ajax({
				type: "POST",
				url: url,
				data:{invoice_id : invoice_id,pallet_id:pallet_id}, 
					success: function(response) {
					//alert(response);
					$('#comb').html(response);
					}
			});
	}
	function add_pallet(invoice_id)
	{	
		$("#pallet").modal('show');
		$("#invoice_no").val(invoice_id);
	}

	function savepallet(){
		var total_pallet=parseInt($("#total_pallet").val());
		if(total_pallet>0)
		{
			var label_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=savePallet', '',1);?>");
			var formData = $("#pform").serialize();
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
			alert("Please Enter Proper Pallet"); 	
		}
	}
	
	function savePalletdetail(){
		var detail =$('#detail').val();
		if(detail)
		{	
			var label_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=savePalletdetail', '',1);?>");
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
			alert("Please Enter Box No."); 	
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