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
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

$click = '';
if(isset($_GET['indent_id']) && !empty($_GET['indent_id'])){
	
	if(!$obj_general->hasPermission('view',$menuId)){
		$display_status = false;
	}else{
		$indent_id = base64_decode($_GET['indent_id']);
		$cond='AND pi.status=0 ORDER BY pending_qty ASC ';
		//$order='';
		$inventory = $obj_inventory->getInven($indent_id,$cond);
		//printr($inventory);
		$click = 1;
	}
	
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>
        <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
		  	<span><?php echo $display_name;?> Listing</span>
          	<span class="text-muted m-l-small pull-right">
            	
                
            </span>
          </header>
        
               
          <?php if($inventory)
		  {
			  ?>     
	<div class="panel-body">
    <div class="well m-t">
    	 <div class="row"> 
         	<div class="col-xs-6">
            <strong>Vender Name:</strong>
           	  <h4><?php echo $inventory[0]['vander_first_name'].' '.$inventory[0]['vander_last_name'];?></h4>
                    	 <p> <?php echo $inventory[0]['address'];?>
                         <br><?php echo $inventory[0]['email_id'];?> <br> </p> 
       	   </div> 
              
            <div class="col-xs-6  text-right">
           <?php
					$addedByData = $obj_inventory->getUser($inventory[0]['added_by_id'],$inventory[0]['added_by_type_id']);
					
					$addedByImage = $obj_general->getUserProfileImage($inventory[0]['added_by_id'],$inventory[0]['added_by_type_id'],'100_');
					$addedByInfo = '';
					$addedByInfo .= '<div class="row">';
					$addedByInfo .= '<div class="col-lg-3"><img src="'.$addedByImage.'"></div>';
					$addedByInfo .= '<div class="col-lg-9">';
									if($addedByData['city']){ $addedByInfo .= $addedByData['city'].', '; }
									if($addedByData['state']){ $addedByInfo .= $addedByData['state'].' '; }
									if(isset($addedByData['postcode'])){ $addedByInfo .= $addedByData['postcode']; }
									$addedByInfo .= '<br>Telephone : '.$addedByData['telephone'].'</div>';
								$addedByInfo .= '</div>';
								$addedByName = $addedByData['first_name'].' '.$addedByData['last_name'];
								str_replace("'","\'",$addedByName);
							?>
								<a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $addedByInfo;?>' title="" data-original-title="<b><?php echo $addedByName;?></b>"><?php echo $addedByData['user_name'];?></a>
       	   </div> 
                            	
                                
</div></div>
                                 <p class="m-t m-b">Receivable date: 
                                 <strong><?php echo dateFormat(4,$inventory[0]['due_date']);?></strong>    <i class="fa fa-clock-o"></i>  
                                 <br>
                                 </p> 
                                 <div class="line"></div>
                           <table class="table"> 
                           <thead>
                           <tr>
                            <th>S.NO</th>
                             <th>ITEMS</th> 
                             <th>Purchased By</th>
                             <th>Date</th>
                              <th>Order QTY</th>
                              <th>Received QTY</th>
                              <th>Pending QTY</th>
                              </tr> 
                              </thead> 
                              <tbody> 
                              
                              <?php $n = 1; $total=0; foreach($inventory as $items)  {?>
                              <tr>
                              
                              <td><?php echo $n;?></td>
                              <td>
                              <?php 
							  $item_id = $items['item_id'];
							
							  if($items['table_name'] == 'product_zipper')
							  {
								$cond = 'product_zipper_id ='.$item_id;
								$inv = $obj_inventory->getName($cond,$items['table_name']);
						    echo '<b>Zipper-</b>'.$inv['zipper_name'];
								$product_unit = $inv['zipper_unit'];
								//echo $product_unit;
							  }
							  
							   if($items['table_name'] == 'product_spout')
							  {
								$cond = 'product_spout_id ='.$item_id;
								$inv = $obj_inventory->getName($cond,$items['table_name']);
								    echo '<b>Spout-</b>'.$inv['spout_name'];
									$product_unit = $inv['spout_unit'];
							  }
							  
							   if($items['table_name'] == 'product_accessorie')
							  {
								$cond = 'product_accessorie_id ='.$item_id;
								$inv = $obj_inventory->getName($cond,$items['table_name']);
								    echo '<b>Accessorie-</b>'.$inv['product_accessorie_name'];
									$product_unit = $inv['product_accessorie_unit'];
							  }
							  
							   if($items['table_name'] == 'product_material')
							  {
								$cond = 'product_material_id ='.$item_id;
								$inv = $obj_inventory->getName($cond,$items['table_name']);
								    echo '<b>Material-</b>'.$inv['material_name'];
									$product_unit = $inv['material_unit'];
							  }
							   if($items['table_name'] == 'ink_master')
							  {
								$cond = 'ink_master_id ='.$item_id;
								$inv = $obj_inventory->getNameR($cond,$items['table_name']);
								    echo '<b>Ink-</b>'.$inv['make_name'];
									$product_unit = $inv['ink_master_unit'];
							  }
							   if($items['table_name'] == 'ink_solvent')
							  {
								$cond = 'ink_solvent_id ='.$item_id;
								$inv = $obj_inventory->getNameR($cond,$items['table_name']);
								    echo '<b>Ink Solvent-</b>'.$inv['make_name'];
									$product_unit = $inv['ink_solvent_unit'];
							  }
							   if($items['table_name'] == 'adhesive')
							  {
								$cond = 'adhesive_id ='.$item_id;
								$inv = $obj_inventory->getNameR($cond,$items['table_name']);
								    echo '<b>Adhesive-</b>'.$inv['make_name'];
									$product_unit = $inv['adhesive_unit'];
							  }
							   if($items['table_name'] == 'adhesive_solvent')
							  {
								$cond = 'adhesive_solvent_id ='.$item_id;
								$inv = $obj_inventory->getNameR($cond,$items['table_name']);
								    echo '<b>Adhesive Solvent-</b>'.$inv['make_name'];
									$product_unit = $inv['adhesive_solvent_unit'];
							  }
							  ?>
                            
                              </td>
                             <td>
                             <?php 
							 $process_by = $obj_inventory->getUser($inventory[0]['added_by_id'],$inventory[0]['added_by_type_id']);
							 if(isset($inventory[0]['added_by_id']))
													echo '<span style="color:#26B756">'.$process_by['user_name'].'</span>  </div>';
													?>
                             </td>
                             <?php 
							// printr($inv);
							 //$prodcut_unit=$items['measurement'];
							   //	$unit = $obj_inventory->getProduct($prodcut_unit); 
							 	?>
                             <td> <span> <?php echo dateFormat(4,$inventory[0]['added_date']); ?></span></td>
                             <td><?php echo $items['total_qty']; $total=$total+$items['total_qty'];?>&nbsp;<?php echo $product_unit; ?>&nbsp;&nbsp;</td>
                              <?PHP 
								//$prodcut_unit=$items['measurement'];
							   //	$unit = $obj_inventory->getProduct($prodcut_unit); 
								$pen=$items['purchase_indent_items_id'];
								$cond='AND status=0';
								$sta=$obj_inventory->getRec($pen,$cond);
								//printr($sta);
								if($sta['receive_qty']==0)
								{
									$receive=0;
									$k='';
								}
								else
								{
									$receive=$sta['receive_qty'];
									$k=$product_unit;
								}
								?>
                               
                              <td><?php echo $receive;?>&nbsp;&nbsp;<?php echo $k;?>&nbsp;&nbsp;
                              <?php
							  if($obj_general->hasPermission('add',$menuId)){
							  if($obj_general->hasPermission('edit',$menuId)){
							   if($receive==0) 
							  		{
										
									}
									else
									{?>
                              <input type="button" name="approve<?php echo $n;?>" id="approve<?php echo $n;?>" value="Approve" style="border:0px" class="bg-success" onclick="approve(<?php echo $n;?>,<?php echo $items['purchase_indent_items_id']?>,<?php echo $items['indent_id']?>,<?php echo $items['total_qty']?>,<?php echo $sta['receive_qty']?>,<?php echo $items['pending_qty']?>,<?php echo $items['history_id']?>) "/>
                      <input type="button" name="decline<?php echo $n;?>" id="decline<?php echo $n;?>" value="Decline" style="border:0px" class="bg-danger" onclick="decline(<?php echo $n;?>,<?php echo $items['purchase_indent_items_id']?>,<?php echo $items['indent_id']?>,<?php echo $items['total_qty']?>,<?php echo $sta['receive_qty']?>,<?php echo $items['pending_qty']?>,<?php echo $items['history_id']?>)" />
                             <?php }} }?>
                              </td>
                              <?Php 
							  if($items['pending_qty']==0)
								{
									$penqty=0;
									$k='';
								}
								else
								{
									$penqty=$items['pending_qty'];
									$k=$product_unit;
								}
							  ?>
                              <td><?php echo $items['pending_qty'];?>&nbsp;&nbsp;<?php echo $k;?>&nbsp;&nbsp;
                               <?php 
							   if($obj_general->hasPermission('add',$menuId)){
							   if($obj_general->hasPermission('edit',$menuId)){
							   if($items['pending_qty']==0) 
							  		{
										
									}
									else
									{?>
                               <input type="button" name="rec<?php echo $n;?>" id="rec<?php echo $n;?>" value="Receive" style="border:0px" class="bg-success" onclick="rec(<?php echo $n;?>,<?php echo $items['purchase_indent_items_id']?>,<?php echo $items['indent_id']?>,<?php echo $items['total_qty']?>,<?php echo $items['rec_qty']?>,<?php echo $items['pending_qty']?>,<?php echo $items['history_id']?>)" />
                               <input type="button" name="cancel<?php echo $n;?>" id="cancel<?php echo $n;?>" value="Decline" style="border:0px" class="bg-danger" onclick="cancel(<?php echo $n;?>,<?php echo $items['purchase_indent_items_id']?>,<?php echo $items['indent_id']?>,<?php echo $items['total_qty']?>,<?php echo $items['rec_qty']?>,<?php echo $items['pending_qty']?>,<?php echo $items['history_id']?>)" />
                                 <?php } }} ?>
                               </td>
                               <?php $pen=$items['purchase_indent_items_id'];
							 $cond='AND (pending_qty!=0 OR rec_qty!=0 OR approve_qty!=0 OR cancle_qty!=0)';
							 $can=$obj_inventory->getHistory($pen,$cond);?>
                              </tr>
                              
							  <?php
							   $n++;}?>
                              <tr> <td colspan="4" class="text-right no-border"><strong>Total</strong></td>
                                  <td><strong><?php echo $total;?></strong></td>
                                  <td><strong><?php ?></strong></td>
                                  <td><strong><?php ?></strong></td>
                                  </tr> 
                                  </tbody> 
                                  </table>
                                <p class="m-t m-b">Discription: 
                                 <strong><?php echo isset($inventory[0]['description'])?$inventory[0]['description']:'';?></strong>
                                 <br> Reminder Date: 
                                 <span class="label bg-success"><?php echo dateFormat(4,$inventory[0]['reminder_date']);?></span>
                                                                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout,'mod=index&status='."0", '',1);?>" style="float: right;">Cancel</a>

                                 </p> 
                                 
                      </div>           
              </section>
              </div>    
              </div>
            <?php }
			else
			echo '<div>NO RECORD FOUND...!</div>';  ?>
                           
</section></section>

<!--start rec qty-->
<div class="modal fade" id="approve" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="width: 400px;">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="app_purchase_indent_items_id" id="app_purchase_indent_items_id" value="" />
                 <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                <input type="hidden" name="app_indent_id" id="app_indent_id" value=""/>
                 <input type="hidden" name="app_pen_qty" id="app_pen_qty" value=""/>
                 <input type="hidden" name="app_rec_qty" id="app_rec_qty" value=""/>
                 <input type="hidden" name="app_h_id" id="app_h_id" value=""/>
                <h4 class="modal-title" id="myModalLabel"> Approve Qty For Purchase Indent</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Approve Qty</label>
                        <div class="col-lg-8">
                             <input type="text" name="appqty" id="appqty" placeholder="Qty" value=""
                              class="form-control validate[required,custom[onlyNumberSp]]" />
                        </div>
                     </div> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="updateindentstatus('appqty',0)" name="btn_decline" class="btn btn-danger">Approve</button>
              </div>
   		</form>   
    </div>
  </div>
</div>
<div class="modal fade" id="decline" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="dec_purchase_indent_items_id" id="dec_purchase_indent_items_id" value="" />
                 <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                <input type="hidden" name="dec_indent_id" id="dec_indent_id" value=""/>
                <input type="hidden" name="dec_pen_qty" id="dec_pen_qty" value=""/>
                 <input type="hidden" name="dec_rec_qty" id="dec_rec_qty" value=""/>
                 <input type="hidden" name="dec_h_id" id="dec_h_id" value=""/>
              
                <h4 class="modal-title" id="myModalLabel">Review For Decline Purchase Indent</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                   <label class="col-lg-3 control-label">Cancel Qty</label>
                   <div class="col-lg-8">
                    <input type="text" name="cancelqty" id="cancelqty" placeholder="Qty" value="" class="form-control validate[required,custom[onlyNumberSp]]" />
                    </div><br />
                    <div class="col-lg-8"></div><br />
                    <div class="col-lg-8"></div><br />
                        <label class="col-lg-3 control-label">Review</label>
                        <div class="col-lg-8">
                             <textarea name="review" id="review" placeholder="Review" value="" class="form-control validate[required]"></textarea>
                        </div>
                     </div> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="updateindentstatus('review',1)" name="btn_decline" class="btn btn-danger">Decline</button>
              </div>
   		</form>   
    </div>
  </div>
</div>
<!--end rec qty-->

<!--start pen qty-->
<div class="modal fade" id="rec" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="width: 400px;">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="rec_purchase_indent_items_id" id="rec_purchase_indent_items_id" value="" />
                 <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                <input type="hidden" name="rec_indent_id" id="rec_indent_id" value=""/>
                <input type="hidden" name="rec_h_id" id="rec_h_id" value=""/>
                <input type="hidden" name="rec_app_qty" id="rec_app_qty" value=""/>
                 <input type="hidden" name="rec_rec_qty" id="rec_rec_qty" value=""/>
                <input type="hidden" name="rec_pen_qty" id="rec_pen_qty" value=""/>
                <h4 class="modal-title" id="myModalLabel"> Receive Qty For Purchase Indent</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Receive Qty</label>
                        <div class="col-lg-8">
                             <input type="text" name="receiveqty" id="receiveqty" placeholder="Qty" value="" 
                             class="form-control validate[required,custom[onlyNumberSp]]" />
                        </div>
                     </div> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="updateindentstatus('receiveqty',2)" name="btn_decline" class="btn btn-danger">Receive</button>
              </div>
   		</form>   
    </div>
  </div>
</div>
<div class="modal fade" id="cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="can_purchase_indent_items_id" id="can_purchase_indent_items_id" value="" />
                 <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                <input type="hidden" name="can_indent_id" id="can_indent_id" value=""/>
                <input type="hidden" name="can_app_qty" id="can_app_qty" value=""/>
                 <input type="hidden" name="can_rec_qty" id="can_rec_qty" value=""/>
                 <input type="hidden" name="can_h_id" id="can_h_id" value=""/>
                <input type="hidden" name="can_pen_qty" id="can_pen_qty" value=""/>
                <h4 class="modal-title" id="myModalLabel">Review For Decline Purchase Indent</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Review</label>
                        <div class="col-lg-8">
                             <textarea name="can" id="can" placeholder="Review" value="" class="form-control validate[required]"></textarea>
                        </div>
                     </div> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="updateindentstatus('cancel',3)" name="btn_decline" class="btn btn-danger">Decline</button>
              </div>
   		</form>   
    </div>
  </div>
</div>
<!--end pen qty-->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="application/javascript">

function approve(n,purchase_indent_items_id,indent_id,tot_qty,rec_qty,pending_qty,history_id)
{	//alert(rec_qty);
		$(".note-error").remove();
		$("#approve").modal('show');
		$("#app_purchase_indent_items_id").val(purchase_indent_items_id);
		$("#app_indent_id").val(indent_id);
		$("#app_rec_qty").val(rec_qty);
		$("#app_h_id").val(history_id);
		$("#app_pen_qty").val(pending_qty);
}
function decline(n,purchase_indent_items_id,indent_id,tot_qty,rec_qty,pending_qty,history_id)
{	//alert(rec_qty);
	$(".note-error").remove();
	$("#decline").modal('show');
	$("#dec_purchase_indent_items_id").val(purchase_indent_items_id);
	$("#dec_indent_id").val(indent_id);
	$("#dec_rec_qty").val(rec_qty);
	$("#dec_h_id").val(history_id);
	$("#dec_pen_qty").val(pending_qty);
}
function rec(n,purchase_indent_items_id,indent_id,tot_qty,rec_qty,pending_qty,history_id)
{		//alert(pending_qty);
		$(".note-error").remove();
		$("#rec").modal('show');
		$("#rec_purchase_indent_items_id").val(purchase_indent_items_id);
		$("#rec_indent_id").val(indent_id);
		$("#rec_pen_qty").val(pending_qty);
		$("#rec_rec_qty").val(rec_qty);
		$("#rec_h_id").val(history_id);
}
function cancel(n,purchase_indent_items_id,indent_id,tot_qty,rec_qty,pending_qty,history_id)
{		//alert(tot_qty);
		$(".note-error").remove();
		$("#cancel").modal('show');
		$("#can_purchase_indent_items_id").val(purchase_indent_items_id);
		$("#can_indent_id").val(indent_id);
		$("#can_pen_qty").val(pending_qty);
		$("#can_h_id").val(history_id);
		$("#can_rec_qty").val(tot_qty);
	
}
function updateindentstatus(id,status)
	{ 	
		var review=$("#review").val();
		$("#reveiw").val('');
		
		var can=$("#can").val();
		$("#can").val(''); 
		
		if(status == 2)
		{	
			if($("#receiveqty").val()=='')
			{
				$(".note-error").remove();
				alert('Please Give Qty');
				return false;
			}
			if(/^[0-9 ]*$/.test($("#receiveqty").val()) == false) {
				$(".note-error").remove();
				alert("Enter Only Numbers");
				return false;
			}
		}
		if(status == 0)
		{
			if($("#appqty").val()=='')
			{	
				$(".note-error").remove();
				alert('Please Give Qty');
				return false;
			}
			if(/^[0-9 ]*$/.test($("#appqty").val()) == false) {
				$(".note-error").remove();
				alert("Enter Only Numbers");
				return false;
			}
		}
		if(status == 1)
		{
			if($("#cancelqty").val()=='')
			{	
				$(".note-error").remove();
				alert('Please Give Qty');
				return false;
			}
			if(/^[0-9 ]*$/.test($("#cancelqty").val()) == false) {
				$(".note-error").remove();
				alert("Enter Only Numbers");
				return false;
			}
			
		}
			
			var receiveqty = $("#receiveqty").val();
			$("#receiveqty").val('');
			
			var appqty = $("#appqty").val();
			$("#appqty").val('');
			
			var cancelqty = $("#cancelqty").val();
			$("#cancelqty").val('');
			
			var pending = $("#can_rec_qty").val();
			$("#can_rec_qty").val('');
			
			var postArray = {};
			if(status == 1)
			{
				var newid = 'dec_';
				
			}
			else if(status == 2)
			{
				var newid = 'rec_';		
			}
			if(status == 3)
			{
				var newid = 'can_';
				
			}
			else if(status == 0)
			{
				var newid = 'app_';		
			}
			postArray['purchase_indent_items_id'] = $("#"+newid+"purchase_indent_items_id").val();
			postArray['indent_id'] = $("#"+newid+"indent_id").val();
			postArray['rec_qty']=$("#"+newid+"rec_qty").val();
			postArray['receiveqty'] = receiveqty;
			postArray['review'] = review;
			postArray['cancel'] = can;
			postArray['status'] =status;
			postArray['h_qty'] = $("#"+newid+"h_id").val();
			postArray['pen_qty'] = $("#"+newid+"pen_qty").val();
			postArray['appqty'] = appqty;
			postArray['pending'] = pending;
			postArray['cancelqty'] = cancelqty;
			if(parseInt(postArray['pen_qty']) < parseInt(postArray['receiveqty']))
			{	
				$(".note-error").remove();
				alert('Please Give Qty Less than '+ postArray['pen_qty']);
				return false;
			}
			else if(parseInt(postArray['rec_qty']) < parseInt(postArray['appqty']))
			{	
				$(".note-error").remove();
				alert('Please Give Qty Less than '+ postArray['rec_qty']);
				return false;
			}
			else if(parseInt(postArray['rec_qty']) < parseInt(postArray['cancelqty']))
			{	
				$(".note-error").remove();
				alert('Please Give Qty Less than '+ postArray['rec_qty']);
				return false;
			}
			else
			{
				$("#approve").modal('hide');	
				$("#rec").modal('hide');
			var d = new Date();
			
    var curr_date = d.getDate();
    var curr_month = d.getMonth();
    curr_month++;   // need to add 1 – as it’s zero based !
    var curr_year = d.getFullYear(); 
    var formattedDate = curr_date + "-" + curr_month + "-" + curr_year; 
		postArray['currdate'] = formattedDate;
	
			var indent_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateindentstatus', '',1);?>");
			
		$.ajax({
			url : indent_url,
			method : 'POST',
				data:{postArray:postArray},
				success: function(response){
					//alert(response);
				set_alert_message('Successfully Updated',"alert-success","fa-check");
				  window.setTimeout(function(){location.reload()},1000)
				},
				error: function(){
					return false;	
				}
				});
			}
	}

</script>