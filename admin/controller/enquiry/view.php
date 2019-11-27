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
	'text' 	=> $display_name.' Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
$address_id = '0';
$add_url='';
if (isset($_GET['address_book_id'])) {
    $address_id = decode($_GET['address_book_id']);
    $add_url='&address_book_id='.$_GET['address_book_id'];
}
$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, '', $add_url, '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

//Close : bradcums

//Start : edit
$edit = '';
if(isset($_GET['enquiry_id']) && !empty($_GET['enquiry_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$enquiry_id = base64_decode($_GET['enquiry_id']);
		$enquiry_details = $obj_enquiry->getEnquiry($enquiry_id);
		//printr($enquiry_details);
	}
}
//Close : edit
if($display_status){
	
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
        
      <div class="col-sm-9">
        
            <section class="panel">  
            
                <header class="panel-heading bg-white">
                 <span>Enquiry Detail</span> 
                </header>
              
              <?php if($enquiry_details) { 
			   //printr($enquiry_details);
			  
			  ?>
              <div class="panel-body">
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Enquiry Number</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $enquiry_details['enquiry_number'];?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Enquiry For</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($enquiry_details['enquiry_for']);?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Company Name</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php 
							$add_url=$enquiry_details['company_name'];
								if($enquiry_details['address_bk_id']!='0' && $obj_general->hasPermission('view',178))
									$add_url='<a href="'.$obj_general->link('address_book', '&mod=view&address_book_id=' . encode($enquiry_details['address_bk_id']), '', 1).'">'.$enquiry_details['company_name'].'</a>'?>
						   <?php echo $add_url;?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Customer Name</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($enquiry_details['client_name']);?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Mobile Number</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($enquiry_details['mobile_number']);?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Email</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($enquiry_details['email']);?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Industry</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php $industry = $obj_enquiry->getIndustry($enquiry_details['industry']);
                                  echo ucwords($industry['industry']);?>
                            </label>
                        </div>
                      </div>
                      
                      
                      <?php if(!empty($enquiry_details['website'])) { ?>
                          <div class="form-group">
                            <label class="col-lg-3 control-label">Website</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                <?php echo ucwords($enquiry_details['website']);?>
                                </label>
                            </div>
                          </div>
                      <?php } ?>
                      
                     
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Enquiry Source</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($enquiry_details['enquiry_source']);?>
                            </label>
                        </div>
                      </div>
                     
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Enquiry Type</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($enquiry_details['enquiry_type']);?>
                            </label>
                        </div>
                      </div>
                     
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Products</label>
                        <div class="col-lg-9">
						 	<section class="panel">
                              <div class="table-responsive">
                            	<table class="table table-striped b-t text-small">
                                 <thead>
                                    <tr>
                                       <th>Product</th>
                                       <th>Printing Option</th>
                                       <th>Printing Effect</th>
                                       <th>Size</th>
                                       <th>Valve</th>
                                       <th>Zipper</th>
                                       <th>Spout</th>
                                    </tr>
                                 </thead>
                                 <?php foreach($enquiry_details['products'] as $product){ ?>
                                     <tbody>
                                        <tr>
                                        
                                          <td><?php echo $product['product_name']; ?></td>
                                          <td>
                                            <?php foreach($product['printing_option'] as $option) { ?>
                                               <?php echo "- ".$option['printing_option']."<br/>"; ?>	
                                            <?php } ?>
                                          </td>
                                          
                                          <td>
                                            <?php foreach($product['printing_effect'] as $effect) { ?>
                                               <?php echo "- ".$effect['printing_effect']."<br/>"; ?>	
                                            <?php } ?>
                                          </td>
                                          
                                          <td>
                                            <?php foreach($product['size'] as $size) { ?>
                                               <?php echo "- ".$size['size']."<br/>"; ?>	
                                            <?php } ?>
                                          </td>
                                                    
                                          <td>
                                            <?php foreach($product['valve'] as $valve) { ?>
                                               <?php echo ucwords($valve['valve'])."<br/>"; ?>	
                                            <?php } ?>
                                          </td>
                                          
                                          <td>
                                            <?php foreach($product['zipper'] as $zipper) { ?>
                                               <?php echo "- ".ucwords($zipper['zipper'])."<br/>"; ?>	
                                            <?php } ?>
                                          </td>
                                          
                                         
                                              <td>
                                                <?php foreach($product['spout'] as $spout) { ?>
                                                   <?php echo "- ".$spout['spout']."<br/>"; ?>	
                                                <?php } ?>
                                              </td>
                                         
                                          
                                        </tr>    
                                      </tbody>
                                  <?php } ?>
                           </table>     
                           	  </div>
                            </section>  
                        </div>
                      </div>
                       <div class="form-group">
                        <div class="col-lg-12"><h4><i class="fa fa-tags"></i> Proforma  History</h4>
                        	<div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                        	
                        	<section class="panel">
                              <div class="table-responsive">
                           		<table class="table table-responsive table-striped b-t text-small">
                              <thead>
                                 <tr>
                                     
                                     <th>Proforma Invoice No</th>
                                     <th>Date </th>
                                     <th>Customer Name </th>
 									 <th>Amount</th>
 									 <th>Payment Status</th>
                                     <th>Added By</th>
                                  </tr>
                               </thead>
                               
                               <tbody id="history-body">
                               	<?php
                               	$proforma_data=$obj_enquiry->getProformaData(ucwords($enquiry_details['email']));
                               	   if($proforma_data) { ?>
                                  <?php foreach($proforma_data as $data){
									  
									 //   printr($data);
									 if($data['payment_status']==1){
									     $payment="Paid";
									 }else{
									      $payment="Not Paid";
									 }
									   ?>
                               		<tr> 
                                    <td><?php echo $data['pro_in_no']; ?></td>
                                      <td><?php echo dateFormat("4",$data['invoice_date']); ?></td>
                                      <td><?php echo $data['customer_name']; ?></td>
                                      <td><?php echo $data['invoice_total']; ?></td>
                                      <td><?php echo $payment; ?></td>
                                      <td><?php echo $data['user_name']; ?></td>
                                   
                                    </tr>
                                  <?php } ?> 
                                <?php } else { ?>
                                	<tr id="no-history">
                                    	<td colspan="5">No History Data Available</td>
                                    </td>    
                                <?php } ?>       
                               </tbody>
							</table> 
                            </div>
                            </section>
                         </div>
                        	
            	</div>
            	
            	<div class="line m-t-large" style="margin-top:-4px;"></div><br/>	
            	
                      <div class="form-group">
                        <div class="col-lg-12"><h4><i class="fa fa-tags"></i> Followup History</h4>
                        	<div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                        
                       	
                        <?php
							$history_data = $obj_enquiry->getFollowUpHistories($enquiry_id);	  
						?>
                        	<section class="panel">
                              <div class="table-responsive">
                           		<table class="table table-responsive table-striped b-t text-small">
                              <thead>
                                 <tr>
                                     
                                     <th>Followup Date</th>
                                     <th>Reminder</th>
 									 <th>Note</th>
                                     <th>Added By</th>
                                     <th>Date Added</th>
                                  </tr>
                               </thead>
                               
                               <tbody id="history-body">
                               	<?php if($history_data) { ?>
                                  <?php foreach($history_data as $data){
									  
									 
									   ?>
                               		<tr>
                                    <td><?php echo dateFormat("4",$data['followup_date']); ?></td>
                                      <td><?php echo dateFormat("4",$data['reminder']); ?></td>
                                      <td><?php echo $data['enquiry_note']; ?></td>
                                      <td><?php echo $data['user_name']; ?></td>
                                      <td><?php echo dateFormat("4",$data['date_added']); ?></td>
                                    </tr>
                                  <?php } ?> 
                                <?php } else { ?>
                                	<tr id="no-history">
                                    	<td colspan="5">No History Data Available</td>
                                    </td>    
                                <?php } ?>       
                               </tbody>
							</table> 
                            </div>
                            </section>
                         </div>
                         
                         <div class="col-lg-12 history-form"><h4><i class="fa fa-plus-circle"></i> Add History</h4>
                        	<div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                            
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Followup Date</label>
                               <div class="col-lg-4">
                                    <input type="text" class="input-sm form-control datepicker validate[required]" id="fol-date" placeholder="Date" value="" data-date-format="dd-mm-yyyy" readonly="readonly" name="new_follow_up_date">
                                    <span class="date-error required"></span>
                                    <input type="hidden" name="enquiry_id" value="<?php echo $enquiry_id; ?>" />
                               </div>
                            </div>
                            
   <!--kavita:28-2-2017-->
                <!--<div class="form-group">		
                    <label class="col-lg-3 control-label">Reminder</label>
                    <div class="col-lg-4">
                           <select name="reminder" class="form-control" id ="reminder">
                                <option value="2" <?php //echo (isset($enquiry['reminder']) && $enquiry['reminder'] == '2')?'selected':'';?> > Before 2 Days </option>
                                <option value="3" <?php //echo (isset($enquiry['reminder']) && $enquiry['reminder'] == '3')?'selected':'';?>> Before 3 Days</option>
                                <option value="5" <?php //echo (isset($enquiry['reminder']) && $enquiry['reminder'] == '5')?'selected':'';?>> Before 5 Days</option>
                              </select>
                   </div>
                </div>-->
    <!--kavita:25-2-2017-->
    				
    				
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Notes</label>
                               <div class="col-lg-9">
                                    <textarea class="form-control validate[required]" id="enq-note" rows="3" cols="8" name="new_note"></textarea>
                                    <span class="note-error required"></span>
                               </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-lg-9 pull-right">
                                    <a class="btn btn-primary" id="add-history"><i class="fa fa-plus"></i> Add History</a>
                                </div>
                          	</div>
                            
                         </div>
                         
                      </div>      
                      
                    </form>
                  </div>
                  
                  <?php } else { ?>
                  		<div class="text-center">No Data Available</div>
                  <?php } ?>
                </section>    
      </div>
    </div>
  </section>
</section>
<script>
	
	$('#add-history').click(function(){
		
		var history_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=addHistory', '',1);?>")
		$('.date-error').html('');
		$('.note-error').html();
		//var reminder  = $('select#reminder option:selected').val();
		
		//alert(reminder);
		var error = 0;
		
		if($('#fol-date').val()==''){	
				
			
			$('.date-error').html('Please Select Date');
			error++;
				
		}
		
		if($('#enq-note').val()==''){		
			$('.note-error').html('Please Enter Note');	
			error++;
		}
		if(error==0){
		   $('#loading').show();
		   $.ajax({
			 url : history_url,
			 type : 'post',				
			 data : $('.history-form input,.history-form textarea ,.history-form select'),
			 success : function(response){		
				$('#loading').remove();
				$('#no-history').remove();
				$('#history-body').append(response);
				$(this).removeAttr('disabled');
				$(this).html('<i class="fa fa-plus"></i> Add History');
		
			}
		  });
		}
	});
	
</script>	
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>