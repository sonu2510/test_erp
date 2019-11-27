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
if(isset($_GET['packing_order_id']) && !empty($_GET['packing_order_id'])){
	$packing_order_id = decode($_GET['packing_order_id']);
	$packing = $obj_source->getProforma($packing_order_id);
	$edit = 1;
//	printr($packing_order_id);
//	printr($packing);
}
if(isset($_GET['proforma_packing_order_id']) && !empty($_GET['proforma_packing_order_id'])){
	$proforma_packing_order_id = decode($_GET['proforma_packing_order_id']);
	$invoice_detail = $obj_source-> getInvoice_data($proforma_packing_order_id);
//	printr($invoice_detail);
}
	
//Close : edit


if($display_status){
	//insert 
	//if(isset($_POST['btn_save'])){
		//$post = post($_POST);		
		//printr($post);die;
		//$insert_id = $obj_source->add_packing_order($post);
		//$obj_session->data['success'] = ADD;
		//page_redirect($obj_general->link($rout, '', '',1));
//	}
	
	//edit

	 $latest_no=0;
	$packing_order_id = $obj_source->getOrderNo();
	if(!empty($packing_order_id))
		$latest_no=$packing_order_id;
	
	$strpad = str_pad($latest_no+1,8,'0',STR_PAD_LEFT) 
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
            <form class="form-horizontal" method="post" name="form" id="frm_add" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Order No. </label>
                <div class="col-lg-3">
				   <input type="hidden" name="edit" id="edit" value="<?php echo $edit; ?>"  />
                  	<input type="text" readonly="readonly" name="order_no" id="order_no" value="<?php echo isset($packing['order_no'])?$packing['order_no']:'PACK'.$strpad;?>" class="form-control validtae[required],custom[number]" />
					<input type="hidden" name="packing_order_id" value="<?php echo isset($packing['packing_order_id'])?$packing['packing_order_id']:'';?>"  />
			  </div>
              </div>
			  
			  <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Mexico Refrence No. </label>
                <div class="col-lg-3">
                  	<input type="text"  name="ref_order_no" id="ref_order_no" value="<?php echo isset($packing['ref_order_no'])?$packing['ref_order_no']:'';?>"   class="form-control validtae[required],custom[number]" />
                </div>
              </div>
             
			   
			    <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Order Date</label>
                <div class="col-lg-3">
                	<input type="text" name="order_date" id="date" value="<?php echo isset($packing['order_date'])?$packing['order_date']:date('Y-m-d');?>" class="form-control validtae[required],custom[number]" />
                    </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Customer Name</label>
                <div class="col-lg-3">
                	<input type="text" name="customer_name" id="customer_name" value="<?php echo isset($packing['cust_nm'])?$packing['cust_nm']:'';?>" class="form-control validtae[required]" />
                    </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Email</label>
                <div class="col-lg-3">
                	<input type="text" name="email" id="email" value="<?php echo isset($packing['email'])?$packing['email']:'';?>" class="form-control validtae[required]" />
                    </div>
              </div>
            
            <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Billing Address</label>
                <div class="col-lg-6">
                  	<textarea name="billing_order_address" id="" value="" class="form-control validate[required]" rows="5" ><?php echo isset($packing['billing_order_address'])?utf8_encode($packing['billing_order_address']):'';?></textarea>
                </div>
              </div>
			  
			  <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Delivery Address</label>
                <div class="col-lg-6">
                  	<textarea name="delivery_address" id="" value="" class="form-control validate[required]" rows="5" ><?php echo isset($packing['delivery_address'])?utf8_encode($packing['delivery_address']):'';?></textarea>
                </div>
              </div>
		   <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>RFC No.</label>
                <div class="col-lg-3">
                	<input type="text" name="rfc" id="rfc" value="<?php echo isset($packing['rfc_no'])?$packing['rfc_no']:'';?>" class="form-control validtae[required]" />
                    </div>
              </div>
              
		   <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Dispatched Date</label>
                <div class="col-lg-3">
                	<input type="text" name="dispatched_date" id="date1" value="<?php echo isset($packing['dispatched_date'])?$packing['dispatched_date']:date('Y-m-d');?>" class="form-control validtae[required],custom[number]" />
                    </div>
              </div>
		   
		   <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Courier Detail		 </label>
                <div class="col-lg-6">
                  	<input type="text" name="courier" id="task_name" placeholder="	" value="<?php echo isset($packing['courier'])?$packing['courier']:'';?>" class="form-control validate[required]">
                </div>
              </div>
             
			 <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Tracking Detail		 </label>
                <div class="col-lg-6">
                  	<input type="text" name="tracking_details" id="task_name" placeholder="	" value="<?php echo isset($packing['tracking_details'])?$packing['tracking_details']:'';?>" class="form-control validate[required]">
                </div>
              </div>
		    <div class="form-group">
					<label class="col-lg-3 control-label"><span class="required">*</span>Shipping Cost</label>
					<div class="col-lg-6">
						<input type="text" name="ship_cost" id="ship_cost" placeholder="Shipping Cost" value="<?php echo isset($packing['freight_charges'])?$packing['freight_charges']:'';?>" class="form-control validate[required]">
					</div>
              </div>
             
             
			  
			  
			    <div class="line line-dashed m-t-large"></div>
                  
              
              	<div class="form-group">
                        	<label class="col-lg-3 control-label">Product Name</label>
                        	<div class="col-lg-3">
								<?php
                                $products = $obj_source->getActiveProduct();
                                ?>
                                <select name="product" id="product" class="form-control validate" onchange="color_chng()">
                                <option value="">Select Product</option>
                                    <?php
                                    
                                    foreach($products as $product){
                                        if(isset($post['product']) && $post['product'] == $product['product_id']){
                                            echo '<option value="'.$product['product_id'].'" selected="selected" >'.$product['product_name'].'</option>';
                                        }else{
                                            echo '<option value="'.$product['product_id'].'">'.$product['product_name'].'</option>';
                                        }
                                    } ?>
                                </select>
                        	</div>
                           
                            
                       </div>
					<div class="form-group">
                         <label class="col-lg-3 control-label">Volume <br />  <b style="color:#ff0000"> <?php echo "Size Like : 200 , 70 only in numbers";?> </b></label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="volume" value="" id="volume" class="form-control" onchange="color_chng()"/>
                        </div>
                  </div>   
            
                
              	<div class="form-group">
              	<label class="col-lg-3 control-label">Colour</label>
                        	<div class="col-lg-3">
                             <?php $colors = $obj_source->getActiveColor();?>
                            <select name="color" id="color" class="form-control validate" onchange="color_chng()">
                                    <option value="">Select Color</option>
                                     <?php foreach($colors as $colors2){ ?>
										<option value="<?php echo $colors2['pouch_color_id']; ?>" id="option"
                                        <?php //if($clr['color'] == $colors2['pouch_color_id']) { echo 'selected="selected"'; } ?>> 
										<?php echo $colors2['color']; ?></option>
                                        <?php } ?>  
                                                                           
                                  </select>
                            </div>
                    </div>
							 
							 <div class="form-group">
							<label class="col-lg-3 control-label">Tool Price</label>
                        	<div class="col-lg-2">
							
							<input type="text" name="tool_price" value="<?php if(isset($_GET['proforma_packing_order_id'])){ echo $invoice_detail['tool_price'];}  ?>" id="tool_price" class="form-control"/>
                            
                            </div>
                             </div>
							 
							 
              		<div class="form-group option">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Product Code</label>
                        <div class="col-lg-4" id="holder">
                        	<?php $product_codes=$obj_source->getActiveProductCode(); 
									//printr($product_codes);
									
									if(isset($proforma_packing_order_id)) { 
										
										$user_type_id=$_SESSION['LOGIN_USER_TYPE'];
										$user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
										
										$product_code= $obj_source->getProductCode($invoice_detail['product_code_id']);
										
									}?>
                                     <input type="hidden" id="product_code_id" name="product_code_id" value="<?php if(isset($_GET['proforma_packing_order_id']) && ($invoice_detail['product_code_id'] != '-1' && $invoice_detail['product_code_id'] != '0')){ echo $product_code['product_code_id'];} else if(isset($invoice_detail) && $invoice_detail['product_code_id'] == '-1') { echo '-1'; } else { echo '0';} ?>">
                               <input type="text" id="keyword" class="form-control validate[required]"  autocomplete="off" value="<?php  if(isset($_GET['proforma_packing_order_id']) && ($invoice_detail['product_code_id'] == '0')){ echo 'Cylinder';} else if( isset($invoice_detail) && $invoice_detail['product_code_id'] == '-1') { echo 'Custom'; } else {  echo isset($product_code) ? $product_code['product_code'] : ''; } ?>">
                               <input type="hidden" name="real_product_name" id="real_product_name" value="<?php echo isset($product_code['product_name'])?$product_code['product_name']:''?>" />
                                <input type="hidden" name="product_id" id="product_id" value="<?php echo isset($product_code['product'])?$product_code['product']:''?>" />
                                <input type="hidden" name="zipper_id" id="zipper_id" value="" />
                               <div id="ajax_response"></div>
                         
                        </div>
                        <div class="col-lg-4" id="product_div"> 
                               <input type="text" name="product_name" id="product_name"  value="<?php echo isset($_GET['proforma_packing_order_id'])?$product_code['description']:'';?>" disabled="disabled" class="form-control validate" style="width:500px"/>
             	 	 	</div>
             	 	 	
                </div>
				
					<div  class="form-group" id="filling_div" <?php if(isset($product_code['product']) && ($product_code['product']==31 || $product_code['product']=='16')){ ?> style="display:block" <?php }else{ echo 'style="display:none"';} ?>>
							 <label class="col-lg-3 control-label">Filling Selection</label>
								<div class="col-lg-9">
									<div  style="float:left;width: 200px;">
										<label  style="font-weight: normal;">
										  <input type="radio" name="filling" id="from_top" value="Filling from Top" checked="checked"  class="valve"
										  <?php if(isset($invoice_detail['filling']) && ($invoice_detail['filling'] == 'Filling from Top')) {
											  echo 'checked="checked"'; } ?>/>
											Filling from Top
									  </label>
									
										<label  style="font-weight: normal;">
											<input type="radio" name="filling" id="from_spout" value="Filling from Spout" class="valve"
											<?php if(isset($invoice_detail['filling']) && ($invoice_detail['filling'] == 'Filling from Spout')) {
											  echo 'checked="checked"'; }?> />
										Filling from Spout
									  </label>
								  </div> 
								</div>
							</div>	

				
                   <div class="form-group">
								<label class="col-lg-3 control-label"></label> 
								<div class="col-lg-7">
									<section class="panel">
									  <div class="table-responsive">
										<table class="tool-row table-striped  b-t text-small" id="myTable">
										  <thead>
											  <tr>
                                                <th><span class="required">*</span>Color</th>
                                                <th></th>
                                                <th><span class="required">*</span>Qty</th>
                                               
											    <th><span class="required">*</span>Rate </th>
                                                <th>Size</th>
                                                <th>Measurement</th>
                                                <th>Description</th>
                                              	<?php if(isset($invoice_detail['product_id']) && $invoice_detail['product_id']!=0){ ?>
                                                	<th></th>
                                                <?php } ?>
                                              </tr>
										  </thead>
                                          <tbody id="myTbody">
                                             
                             <?php $colors = $obj_source->getActiveColor();?>
                               <input type="hidden" id="color_arr" value='<?php echo json_encode($colors);?>' />       
                            <?php if(isset($proforma_packing_order_id)) {
								//printr($invoice_detail);
							//$color = $obj_pro_invoice->getColorDetails($proforma_id,$proforma_in_id); 
							$color = $obj_source->getProductCode($invoice_detail['product_code_id']);
								//printr($color);
							}else{
								$color=array('id' =>'',
												'proforma_id' => '',
												'proforma_invoice_id' =>'',
												'color' =>'',
												'color_text' =>'', 
												'rate' =>'',
												'quantity' =>'',
												'volume' => '',
												'description' =>'', 
												 'color_name' =>''
												);
							
							}
							?>
                            <input type="hidden" name="cyl" id="cyl" value="<?php echo isset($color['color'])?$color['color']:'';?>"/>
                            <tr id="tr_<?php //echo $i; ?>">  
                            
							<td> 
                            	<input type="text" id="color_product" name="color_product" value="<?php if(isset($invoice_detail) && $invoice_detail['product_code_id'] == '-1') { echo 'Custom'; }else if(isset($invoice_detail) && $invoice_detail['product_code_id'] == '0') { echo 'Cylinder'; } else { echo $color['color']; }?>" readonly="readonly" class="form-control" />
                             </td>
                            
                             <td>
                              		<input type="text" name="color_text" value="<?php echo isset($invoice_detail['color_text'] ) ? $invoice_detail['color_text'] : ''; ?>" id="color_txt" class="form-control" <?php if(isset($invoice_detail) && $invoice_detail['color_text'] != '') {?>  style="display:block" <?php } else { ?> style="display:none" <?php } ?> />
                         	  </td>
                                
                               <td><input type="text" name="qty"  value="<?php echo isset($invoice_detail) ? $invoice_detail['quantity'] : ''; ?>" id="qty" class="form-control validate[required,custom[number],min[1]]" placeholder="Qty"></td>
                                

							   <td><input type="text" name="rate" value="<?php echo isset($invoice_detail) ? $invoice_detail['rate'] : ''; ?>" id="rate" class="form-control validate[required,custom[number],min[0.001]]" placeholder="Rate"></td>
                               
                               <td><input type="text" name="size" value="<?php if(isset($invoice_detail) && ($invoice_detail['product_code_id'] == '-1' || $invoice_detail['product_code_id'] == '0')) { echo $invoice_detail['size'];} else { echo $color['volume']; } ?>" class="form-control" placeholder="Size" id="size" <?php if(isset($invoice_detail) && $invoice_detail['product_code_id'] != '-1' && $invoice_detail['product_code_id'] != '0') { ?> readonly="readonly" <?php } ?> ></td>
                               
                               <td> <?php $measurement = $obj_source->getMeasurement(); ?>
                                    <select name="measurement" id="measurement" class="form-control" <?php if(isset($invoice_detail) && ($invoice_detail['product_code_id'] != '-1' && $invoice_detail['product_code_id'] != '0') ) { ?>readonly="readonly" <?php } ?>>
                                       <option value="">Select Measurement</option>
                                        <?php foreach($measurement as $meas){ ?>
                                                <option value="<?php echo $meas['product_id']; ?>"
                                                <?php if(isset($_GET['proforma_packing_order_id'])) {
                                                
                                                if(($color['measurement'] == $meas['measurement']) || isset($invoice_detail) && ($invoice_detail['product_code_id'] == '-1' || $invoice_detail['product_code_id'] == '0'  )&& ($invoice_detail['measurement'] == $meas['product_id'])) { ?> selected="selected" <?php }} ?>
                                                 ><?php echo $meas['measurement']; ?></option>
                                      <?php   }  ?>
                               		 </select>
                                </td>
                                
                                
                                <td><input type="text" name="description" value="<?php echo isset($invoice_detail) ? $invoice_detail['description'] :''; ?>" id="description" class="form-control" placeholder="Description">
                             
                                </td>
                               
                                
                                </tr>
							
                                </tbody>
								</table>
								</div>
                               </section> 
								</div>
			  </div>
               <div class="form-group">
                     <div class="col-lg-9 col-lg-offset-3">
                      
                      <?php if($_SESSION['LOGIN_USER_TYPE']=='4' && $_SESSION['ADMIN_LOGIN_SWISS']=='10') { ?>
                            <button type="button"  name="btn_save" id="btn_save" class="btn btn-primary" <?php if(isset($_GET['proforma_packing_order_id'])){ ?> style="display:none" <?php }?> onclick="displaygenerate();">Add Product</button> 
                        <?php } ?>
                      <?php if(isset($packing_order_id) && isset($proforma_packing_order_id)) {?>
							<input type="hidden" name="pro_id" value="<?php echo $proforma_packing_order_id;?>" id="pro_id" />
                  
							<button type="button" name="proforma_update" id="proforma_update" class="btn btn-primary">Update Product</button> 
                     <?php } ?>	
					
           	 </div>
           	 </div>
			<div id="invoice_results">
   		   <?php 
	
			if(isset($packing['packing_order_id'])){
			?>
        	<table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Size</th>
                    <th>Color : Quantity</th>
                    <th>Rate</th>
                    <th>Option</th>
                   <?php if($_SESSION['LOGIN_USER_TYPE']=='4' && $_SESSION['ADMIN_LOGIN_SWISS']=='10') 
                            echo '<th>Action</th>';?>
                                    
                </tr>
            </thead>
            <tbody>
        <?php
   		$pro = $obj_source->getProforma($packing['packing_order_id']);
	   	$getInvoices = $obj_source->getInvoice($packing['packing_order_id']);
	   	if(isset($getInvoices) && !empty($getInvoices)) {
            foreach($getInvoices as $invoice ) { 
		//	printr($packing['packing_order_id']);
				$product_code_data = $obj_source->getProductCode($invoice['product_code_id']);?>
                
                <tr id="packingoice_id_<?php echo $invoice['proforma_packing_order_id']; ?>">

				  <td><b><?php echo $product_code_data['product_code']; ?></b><br /><?php echo $invoice['product_name']; ?></td>
                 	
                     <td><?php $measure = $obj_source->getMeasurementName($invoice['measurement']); 
								if($invoice['product_code_id']=='-1' || $invoice['product_code_id']=='0')
									 echo $invoice['size'].'&nbsp;'.$measure['measurement'].'<br>';
								else
									 echo $product_code_data['volume'].'&nbsp;'.$product_code_data['measurement'].'<br>';
									 ?>
					   </td>
                     
				
				  <td>
				  <?php 
				  	$clr_text='';
					if($invoice['product_code_id']=='-1')
					{
						$clr_nm = 'Custom';
						$clr_text = "(".$invoice['color_text'].")";
					}
					elseif($invoice['product_code_id']=='0')
					{
						$clr_nm = 'Cylinder';
					}
					else
					{
						$clr_nm = $product_code_data['color'];
					}
					  
					  
						echo $clr_nm.''.$clr_text.' : '.$invoice['quantity'].'<br>';				 
				  //} ?>
				  </td>
                  
				  <td> <?php echo $invoice['rate'];?></td>
				  <td><?php echo ucwords($product_code_data['spout_name']).' '.$product_code_data['valve'].'<br>'.$product_code_data['zipper_name'].' '. ucwords($product_code_data['product_accessorie_name']); ?></td>
				<?php if($_SESSION['LOGIN_USER_TYPE']=='4' && $_SESSION['ADMIN_LOGIN_SWISS']=='10') { ?>
				  <td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice(<?php echo $invoice['proforma_packing_order_id'].','.$invoice['packing_order_id'];; ?>)"><i class="fa fa-trash-o"></i></a>
                		 <a href="<?php echo $obj_general->link($rout, 'mod=add_cust_detail&packing_order_id='.$_GET['packing_order_id'].'&proforma_packing_order_id='.encode($invoice['proforma_packing_order_id']).'&is_delete=0','',1); ?>" id="btn_edit"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                  </td>
                  <?php } ?>
				  </tr>
				  
				  
                  <!-- CONFIRMATION ALERT BOX -->
                    <div class="modal fade" id="alertbox_<?php echo $invoice['proforma_packing_order_id']; ?>">
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
                                    <button type="button" name="popbtnok" id="popbtnok_<?php echo $invoice['proforma_packing_order_id']; ?>" 
                                    class="btn btn-primary">Ok</button>
                                 
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>
                    <!-- END OF CONFIRMATION BOX-->
<?php } } else { ?>
    <tr>
    	<td colspan="7">No Records Found!!!</td>  
    </tr>
    <?php } }?>
		  </tbody></table>
	</div>
			<?php if($edit){?>
			<div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                    <button type="button" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                    <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=index&is_delete=0', '',1);?>">Cancel</a>
                </div>
            </div>
                <?php }  ?>
                <div class="col-lg-9 col-lg-offset-3">
          <?php //if(!($edit)) {?>
         	<button type="button" id="generate_invoice"  class="btn btn-primary" name="generate_invoice" onClick="add_invoices()" style="display:none" >Generate Proforma Invoice</button>
			<?php //} ?>
            </form>
          </div>
        </section>
        
      </div>
    </div>
  </section>
</section>
<!-- Start : validation script -->
<style type="text/css">
.btn-on.active {
    background: none repeat scroll 0 0 #3fcf7f;
}
.btn-off.active{
	background: none repeat scroll 0 0 #3fcf7f;
	border: 1px solid #767676;
	color: #fff;
}
@media (max-width: 400px) {
  .chunk {
    width: 100% !important;
  }
}
#ajax_response,#ajax_return{
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
.about{
	text-align:right;
	font-size:10px;
	margin : 10px 4px;
}
.about a{
	color:#BCBCBC;
	text-decoration : none;
}
.about a:hover{
	color:#575757;
	cursor : default;
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
	   jQuery("#form_stock").validationEngine();
	   
	    $("#date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
		$("#date1").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});

		var checkin = $('#date').datepicker({
   			onRender: function(date) {
    		return date.valueOf() < now.valueOf() ? '' : '';
    		}
    	}).on('changeDate', function(ev) {
			if (ev.date.valueOf() <= checkout.date.valueOf()) {
				var newDate = new Date(ev.date);
          		newDate.setDate(newDate.getDate());
    			checkout.setValue(newDate);
    		}
    		checkin.hide();
    		$('#date')[0].focus();
    	}).data('datepicker');
	
});	

</script>


<script>

    jQuery(document).ready(function(){
		
        jQuery("#frm_add").validationEngine();
		$('#customSize').hide();
		$("#cy_option").css("display","none");
		var product_id=$("#product").val();
		if(product_id==0)
			$(".addmore").hide();
		
//[kinjal] : (8-6-2016) for get tax value edit time 
		var edit=$("#edit").val();	
		if(edit != '')
			$( "#state" ).change();
		else
		    $('select[name=currency]').change();
		
	
	
function add_invoices() {
	$("#product").prop('disabled', true);
	$("#size").prop('disabled', true);
	$("#keyword").prop('disabled', true);
	$("#measurement").prop('disabled', true);
	$("#qty").prop('disabled', true);
	$("#rate").prop('disabled', true);
	var packing_order_id = $("#packing_order_id").val();
	var update_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=generateInvoice', '',1);?>");
	$.ajax({
			url : update_invoice_url,
			method : 'post',		
			data : {packing_order_id : packing_order_id},
			success: function(response){
				
				
				set_alert_message('Invoice successfully Added!',"alert-success","fa-check");					
				window.location.href='<?php echo $obj_general->link($rout, '', '',1); ?>';
			}
			
		});
	
	
	
}
function color(n)
{
	var val = $("#color_"+n).val();
	$("#color_txt_"+n).val('');
	$("#color_txt_"+n).hide();
	if(val == -1)
	{ 	
		$("#color_txt_"+n).show();
	}
}
	
$("#customSize").hide();
if($('#size_pro').val() == 0) {
	$("#customSize").show();
}
function customSize()
		{
			if($('#size_pro').val()==0)
			{
				$("#customSize").show();	
			}
			else
			{
				$("#customSize").hide();
				$("#width").val('');
				$("#height").val('');
				$("#gusset").val('');
				color_chng();
			}
			
			//blankselectedvalue();
		}


  
$("#proforma_update").click(function() {
	if($("#frm_add").validationEngine('validate')){
	var update_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateInvoice', '',1);?>");
			var formData = $("#frm_add").serialize();
			$.ajax({
				url : update_invoice_url,
				method : 'post',		
				data : {formData : formData},
				success: function(response){
					if(response != 0){
						$("#invoice_results").html("");
						$("#invoice_results").html(response);
						$('#proforma_update').hide();
						$('#product').val('');
						$('#customSize').hide();
						$("#color_0").val('');
						$("#color_txt_0").hide();
						$("#qty").val('');
						$("#rem_qty").val('');
						$("#rate").val('');
						$("#product_desc").val('');
						$('#myTable tr').not(':eq(0)').not(':eq(0)').remove();
						$('#size').val('');
						$('#description').val('');
						$("input:radio[name=zipper]:not(:disabled):first").attr('checked', true);
						$("input:radio[name=valve]:not(:disabled):first").attr('checked', true);
						$("input:radio[name=spout]:not(:disabled):first").attr('checked', true);
						$("input:radio[name=accessorie]:not(:disabled):first").attr('checked', true);
						$("#keyword").val('');
						$("#product_name").val('');
						$("#color_product").val('');
						$("#measurement").val('');
						
					}
				},
				error: function(){
		
					return false;
				}
			
			});
	}
	
});

 $("#btn_update").click(function() {
 
 $("#product").prop('disabled', true);
	$("#size").prop('disabled', true);
	$("#color_product").prop('disabled', true);
	$("#qty").prop('disabled', true);
	$("#rate").prop('disabled', true);
	
	var update_proforma_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateProforma', '',1);?>");
	var myform = $('#frm_add');
	var disabled = myform.find(':disabled').removeAttr('disabled');
	var formData = myform.serialize();
			$.ajax({
				url : update_proforma_url,
				method : 'post',		
				data : {formData : formData},
				success: function(response){
				
					if(response != 0){
						var url =  getUrl("<?php echo $obj_general->link($rout, '&mod=index&gen_pro_as='.$packing['gen_pro_as'].'', '',1); ?>");
					var redirect = setTimeout(function(){
							window.location = url; 	}, 800);	
				 set_alert_message('Proforma Invoice Record successfully updated ','alert-success','fa fa-check');
							
					}
				},
				error: function(){		
					return false;
				}
			});
});
$(document).ready(function() {
	 $("#input-proforma").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
	 $("#input-name").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');getgst();});
	 $("#input-buyerdate").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
	 $("#signature_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
});
var windowSizeArray = [ "width=200,height=200","width=300,height=400,scrollbars=yes" ];
        $(document).ready(function(){
            $('#mydiv').click(function (event){
			 var product_id = $("#product").val();
				var pop_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getViewToolprice', '',1);?>");
				$.ajax({
					method: "POST",					
					url: pop_url,
					data : {product_id : product_id},
					success: function(response){						
						$("#toolpriceview").html(response);
						$("#smail").modal('show');						
					},
					error: function(){
							return false;	
					}
				});
            });
        });


	function color_chng()
	{    
		var product=$("#product").val();
		var volume = $("#volume").val();
		var color = $("#color").val();
		var product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=product_name', '',1);?>");
		 $.ajax({
				   type: "POST",
				   url: product_url,
				   data: {product_name:product,volume:volume,color:color},
				   success: function(msg){
				 // alert(msg);
				   var msg = $.parseJSON(msg);
				  
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{
						for(var i=0;i<msg.length;i++)
						{	
							div =div+'<li><a href=\'javascript:void(0);\' discr="'+msg[i].description+'" size="'+msg[i].volume+'" mea="'+msg[i].measurement+'" color="'+msg[i].color+'" product_id="'+msg[i].product+'" product_name="'+msg[i].product_name+'"  id="'+msg[i].product_code_id+'"><span class="bold" >'+msg[i].product_code+'</span></a></li>';			
	
						}
					}
					
					div=div+'</ul>';
					if(msg != 0)
					  $("#ajax_response").fadeIn("slow").html(div);
					else
					{
					  $("#ajax_response").fadeIn("slow");	
					  $("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
					}
					$("#loading").css("visibility","hidden");
				   }
			});
		
	}
	
	$(document).click(function(){
			$("#ajax_response").fadeOut('slow');
			$("#ajax_response").html("");
		});
	   	$("#keyword").focus();
		var offset = $("#keyword").offset();
		var width = $("#holder").width();
		$("#ajax_response").css("width",width);
		
		$("#keyword").keyup(function(event){
		 var keyword = $("#keyword").val();
		 if(keyword == 'Cylinder' || keyword == 'cylinder' || keyword == 'CYLINDER')
		 {	
				$("#product_name").hide();
				$("#product_code_id").val('0');
				$("#color_product").val('Cylinder');
				$("#product_id").val('');
				$("#real_product_name").val('Cylinder');
				$('#size').removeAttr("readonly", "readonly");
				$('#measurement').removeAttr("readonly", "readonly");
		 }
		 else if(keyword == 'Custom' || keyword == 'custom' || keyword == 'CUSTOM')
		 {	
			//alert('hi');
				$("#product_name").hide();
				$("#product_code_id").val('-1');
				$("#color_txt").show();
				$("#color_product").val('Custom');
				$("#product_id").val('');
				$("#real_product_name").val('Custom');
				$('#size').removeAttr("readonly", "readonly");
				$('#measurement').removeAttr("readonly", "readonly");
		 }
		 else if(keyword.length)
		 {	
		 	
		 	$("#size").attr("readonly","readonly");
			$("#measurement").attr("readonly",true);
			$("#color_txt").hide();
			$("#product_name").show();
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {		
				 var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=product_code', '',1);?>");
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: product_code_url,
				   data: "product_code="+keyword,
				   success: function(msg){
				   var msg = $.parseJSON(msg);
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{
						for(var i=0;i<msg.length;i++)
						{	
							div =div+'<li><a href=\'javascript:void(0);\' discr="'+msg[i].description+'" size="'+msg[i].volume+'" mea="'+msg[i].measurement+'" color="'+msg[i].color+'" product_id="'+msg[i].product+'" product_name="'+msg[i].product_name+'"  id="'+msg[i].product_code_id+'"><span class="bold" >'+msg[i].product_code+'</span></a></li>';			

						}
					}
					
					div=div+'</ul>';
					if(msg != 0)
					  $("#ajax_response").fadeIn("slow").html(div);
					else
					{
					  $("#ajax_response").fadeIn("slow");	
					  $("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
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
							$("#keyword").val($(".list li[class='selected'] a").text());
							$("#product_div").show();
                  			$("#product_name").val($(".list li[class='selected'] a").attr("discr"));
							$("#color_product").val($(".list li[class='selected'] a").attr("color"));
							$("#real_product_name").val($(".list li[class='selected'] a").attr("product_name"));
							$("#product_id").val($(".list li[class='selected'] a").attr("product_id"));
							$("#size").val($(".list li[class='selected'] a").attr("size"));
							$("#measurement").val($(".list li[class='selected'] a").attr("mea"));
							$("#product_code_id").val($(".list li[class='selected'] a").attr("id"));
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
							$("#keyword").val($(".list li[class='selected'] a").text());
							$("#product_div").show();
                  			$("#product_name").val($(".list li[class='selected'] a").attr("discr"));
							$("#color_product").val($(".list li[class='selected'] a").attr("color"));
							$("#real_product_name").val($(".list li[class='selected'] a").attr("product_name"));
							$("#product_id").val($(".list li[class='selected'] a").attr("product_id"));
							$("#size").val($(".list li[class='selected'] a").attr("size"));
							$("#measurement").val($(".list li[class='selected'] a").attr("mea"));
							$("#product_code_id").val($(".list li[class='selected'] a").attr("id"));
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
	$('#keyword').keydown( function(e) {
    if (e.keyCode == 9) {
		 $("#ajax_response").fadeOut('slow');
		 $("#ajax_response").html("");
    }
	
});
	});
	$("#ajax_response").mouseover(function(){
			
			$(this).find(".list li a:first-child").mouseover(function () {
					$("#product_div").show();
                  $("#product_name").val($(this).attr("discr"));
				  $("#color_product").val($(this).attr("color"));
				$("#real_product_name").val($(this).attr("product_name"));
				  $("#product_id").val($(this).attr("product_id"));
				$("#size").val($(this).attr("size"));
				  $("#measurement").val($(this).attr("mea"));
				  $(this).addClass("selected");
				   $("#product_code_id").val($(this).attr("id"));
				  getStockQty($(this).attr("id"));
			});
			$(this).find(".list li a:first-child").mouseout(function () {
				  $(this).removeClass("selected");
			});
			$(this).find(".list li a:first-child").click(function () {
				//alert($(this).attr("color"));
				  $("#product_div").show();
                  $("#product_name").val($(this).attr("discr"));
				  $("#color_product").val($(this).attr("color"));
				  
				  if($(this).attr("color")=='Custom')
				    $("#color_txt").show();
				  else
				    $("#color_txt").hide();
				 //console.log($(this).attr("product_id"));
				 $("#real_product_name").val($(this).attr("product_name"));
				$("#product_id").val($(this).attr("product_id"));
				 $("#size").val($(this).attr("size"));
				  $("#measurement").val($(this).attr("mea"));
				  $("#product_code_id").val($(this).attr("id"));
				  $("#keyword").val($(this).text());
				  $("#ajax_response").fadeOut('slow');
				  $("#ajax_response").html("");
			
				//showSize();
				
				
				//sonu 30-6-2017
				var country_id = $("#country_id").val();
				//[kinjal] made cond for when user select custom product that it shown error so...[1-8-2017]
				if(country_id == '111' && $(this).attr("color")!='Custom'){
					getrate($(this).attr("id"));
				}
				else
				{
					$("#rate").val('');
				}
				});
				
			//sonu end
			
		});


function product_detail_for_code(){
	color_chng();
	showSize();
	
}


function displaygenerate()
	{
		//alert();
		var edit=$("#edit").val();
		if(edit=='')
			$("#generate_invoice").show();
		else
			$("#generate_invoice").hide();
				
	}

$('#btn_save').click(function(){
	
	if($("#frm_add").validationEngine('validate')){
			var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addPackingOrder', '',1);?>");
				var formData = $("#frm_add").serialize();
				$.ajax({
					url : add_product_url,
					method : 'post',		
					data : {formData : formData},
					success: function(response){
						//alert(response);
						//console.log(response);
						if(response != 0){
							$('#myTable tr').not(':eq(0)').not(':eq(0)').remove();
							$("#myTable tr:nth-child(2)").show();
							$("#invoice_results").html(response)
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
function removeInvoice(proforma_packing_order_id,packing_order_id){
	//alert(proforma_packing_order_id+''+packing_order_id);
	$("#alertbox_"+proforma_packing_order_id).modal("show");
	$(".modal-title").html("Delete Record".toUpperCase());
	$("#setmsg").html("Are you sure you want to delete ?");
	$("#popbtnok_"+proforma_packing_order_id).click(function()
	{
		var remove_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeInvoice', '',1);?>");
		$.ajax({
			url : remove_invoice_url,
			method : 'post',
			data : {proforma_packing_order_id : proforma_packing_order_id,packing_order_id:packing_order_id},
			success: function(response){
				if(response == 0) {			
				$("#alertbox_"+proforma_packing_order_id).hide();
			}
				$("#alertbox_"+proforma_packing_order_id).hide();
				$("#alertbox_"+proforma_packing_order_id).modal("hide");
				$('#proforma_invoice_id_'+proforma_packing_order_id).html('');
				set_alert_message('Proforma Invoice Record successfully deleted','alert-success','fa fa-check');
				location.reload();
				},
			error: function(){
				return false;	
			}
		});
	$("#alertbox_"+proforma_invoice_id).hide();
	$("#alertbox_"+proforma_invoice_id).modal("");
	 });
}
</script> 



<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>