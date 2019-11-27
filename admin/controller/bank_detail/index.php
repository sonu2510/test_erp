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

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

$class ='collapse';
$filter_data=array();

if(!isset($_GET['filter_edit'])){
	$filter_edit = 0;
}else{
	$filter_edit = $_GET['filter_edit'];
}

if(!isset($_GET['filter_edit']) || $_GET['filter_edit']==0){
	if(isset($obj_session->data['filter_data'])){
		unset($obj_session->data['filter_data']);	
	}
}

if(isset($obj_session->data['filter_data'])){
	//$filter_country = $obj_session->data['filter_data']['country'];
	$filter_accnt_no = $obj_session->data['filter_data']['accnt_no'];
	$filter_curr_code = $obj_session->data['filter_data']['curr_code'];
	$filter_status = $obj_session->data['filter_data']['status'];
	$class = '';
	
	$filter_data=array(
		//'country' => $filter_country,
		'accnt_no' => $filter_accnt_no, 
		'curr_code' => $filter_curr_code,
		'status' => $filter_status,		
	);
}
//printr($obj_session->data['filter_data']);
/*if(isset($obj_session->data['filter'])){
	
	$filter_country = $obj_session->data['filter']['country'];
	$filter_country_code = $obj_session->data['filter']['country_code'];
	$filter_currency_code = $obj_session->data['filter']['currency_code'];
	$filter_status = $obj_session->data['filter']['status'];
	
	$filter_data=array(
		'country' => $filter_country,
		'country_code' => $filter_country_code, 
		'currency_code' => $filter_currency_code,
		'status' => $filter_status,		
	);
	
	//print_r($obj_session->data['filter']);die;
	//unset($obj_session->data['filter']);
}*/


if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='bank_detail_id';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order']; 
}else{
	$sort_order = 'DESC';
}



if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class ='';
		
	/*if(isset($_POST['filter_country'])){
		$filter_country=$_POST['filter_country'];		
	}else{
		$filter_country='';
	}*/
	
	if(isset($_POST['filter_accnt_no'])){
		$filter_accnt_no=$_POST['filter_accnt_no'];		
	}else{
		$filter_accnt_no='';
	}

	if(isset($_POST['filter_curr_code'])){
		$filter_curr_code=$_POST['filter_curr_code'];
	}else{
		$filter_curr_code='';
	}
	
	if(isset($_POST['filter_status'])){
		$filter_status=$_POST['filter_status'];
	}else{
		$filter_status='';
	}

	$filter_data=array(
		//'country' => $filter_country,
		'accnt_no' => $filter_accnt_no, 
		'curr_code' => $filter_curr_code,
		'status' => $filter_status,		
	);
	
	$obj_session->data['filter_data'] = $filter_data;	
	//printr($filter_data);
}


if($display_status) {

	//active inactive delete
	if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
	{
		if(!$obj_general->hasPermission('edit',$menuId)){
			$display_status = false;
		} else {
			$status = 0;
			if($_POST['action'] == "active"){
				$status = 1;
			}
			$obj_bank->updateStatus($status,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			//printr($_POST['post']);die;
			$obj_bank->updateStatus(2,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}
	

$total_bank_detail = $obj_bank->getTotalBankDetail($filter_data);
$pagination_data = '';
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
            	
                <?php if($obj_general->hasPermission('add',$menuId)){ ?>
   							<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Bank Detail </a>
                    <?php }
					if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                     <?php }
					if($obj_general->hasPermission('delete',$menuId)){ ?>       
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>
                
            </span>
          </header>
          
          <div class="panel-body">
            
              <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '', '',1); ?>">
                
                <section class="panel pos-rlt clearfix">
                  <header class="panel-heading">
                    <ul class="nav nav-pills pull-right">
                      <li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> </li>
                    </ul>
                    <i class="fa fa-search"></i> Search 	
                  </header>
                        
            	<div class="panel-body clearfix <?php echo $class; ?>">        
                      <div class="row">
                        <div class="col-lg-4">
                               <div class="form-group">
                                <label class="col-lg-5 control-label">Account Number</label>
                                <div class="col-lg-7">                                                                                        
                                 <input type="text" name="filter_accnt_no" value="<?php echo isset($filter_accnt_no) ? $filter_accnt_no : '' ; ?>" placeholder="Account Number" id="input-name" class="form-control" />                                 
                                </div>
                              </div>
                       
               <?php $currency_list = $obj_bank->getCurrencyList();?>  
               <div class="form-group">
                <label class="col-lg-5 control-label">Currency Code</label>
                <div class="col-lg-7">
                  <select name="filter_curr_code"  class="form-control" >
                  <option value="">Select Cuurency</option>
                  	<?php foreach($currency_list as $curry_list) { ?>
                  <?php if(isset($filter_curr_code) && !empty($filter_curr_code) && $filter_curr_code == $curry_list['currency_id']){ ?>
	                   	 	<option value="<?php echo $curry_list['currency_id']; ?>" selected="selected"><?php echo $curry_list['currency_code']; ?></option>
                         <?php } else { ?>
                         	<option value="<?php echo $curry_list['currency_id']; ?>"><?php echo $curry_list['currency_code']; ?></option>
                         <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div></div>
                              
                               <div class="form-group">
                                  <div class="col-lg-4">  
                                <label class="col-lg-5 control-label">Status</label>
                                <div class="col-lg-7">                                                                                            
                                 	<select class="form-control" name="filter_status">
                                    	<option value=""></option>
                                        <option value="1" <?php echo (isset($filter_status) && $filter_status==1) ? 'selected=selected' : ''; ?>>Active</option>
                                        <option value="0" <?php echo (isset($filter_status) && $filter_status==0 && $filter_status !='' ) ? 'selected=selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                              </div>
                           </div>               
                     </div>                                          
                 </div>
            
                 <footer class="panel-footer <?php echo $class; ?>">
                   <div class="row">
                      <div class="col-lg-12">
                         <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                         <a href="<?php echo $obj_general->link($rout, '', '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                      </div> 
                   </div>
                </footer>                                  
              </section>
          </form>    
			  
              <div class="row">
              	
                <div class="col-lg-3 pull-right">	
                    <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                     <option value="<?php echo $obj_general->link($rout, '', '',1);?>" selected="selected">--Select--</option>
                    	<?php 
							$limit_array = getLimit(); 
							foreach($limit_array as $display_limit) {
								if($limit == $display_limit) {	 
						?>
                        	
                        		<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>	
              </div>                 
          </div>
          

          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox"></th>                     
                      
                      <!--<th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                         Country
                         <span class="th-sort">
                               <a href="<?php echo $obj_general->link($rout, 'sort=country_name'.'&order=ASC', '',1);?>">
                               <i class="fa fa-sort-down text"></i>
                               <a href="<?php echo $obj_general->link($rout, 'sort=country_name'.'&order=DESC', '',1);?>">
                               <i class="fa fa-sort-up text-active"></i>
                         <i class="fa fa-sort"></i></span>
                      </th>
                      
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                         Country Code
                         <span class="th-sort">
                               <a href="<?php echo $obj_general->link($rout, 'sort=country_code'.'&order=ASC', '',1);?>">
                               <i class="fa fa-sort-down text"></i>
                               <a href="<?php echo $obj_general->link($rout, 'sort=country_code'.'&order=DESC', '',1);?>">
                               <i class="fa fa-sort-up text-active"></i>
                         <i class="fa fa-sort"></i></span>
                      </th>-->
                      
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                         Currency
                       <!--  <span class="th-sort">
                               <a href="<?php echo $obj_general->link($rout, 'sort=curr_code'.'&order=ASC', '',1);?>">
                               <i class="fa fa-sort-down text"></i>
                               <a href="<?php echo $obj_general->link($rout, 'sort=curr_code'.'&order=DESC', '',1);?>">
                               <i class="fa fa-sort-up text-active"></i>
                         <i class="fa fa-sort"></i></span>-->
                      </th>                      
                      
                      <th>Beneficiary Name</th>
                      <th>Beneficiary Bank Name</th>
                      <th>Account Number</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
				 // printr($total_bank_detail);
                  if($total_bank_detail){
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
					// printr($option);	
                      $bank_dtl = $obj_bank->getBankDtl($option,$filter_data);
					//printr($bank_dtl);die;
						
                      foreach($bank_dtl as $bank_det){ 
					  //printr($bank_det['benefry_bank_name']);
					  $currency = $bank_det['curr_code'];
						$curr_nm = $obj_bank->getCurrencyCode($currency);
						
                        ?>
                        <tr <?php echo ($bank_det['status']==0) ? 'style="background-color:#FADADF" ' : '' ; ?>>
                          <td><input type="checkbox" name="post[]" value="<?php echo $bank_det['bank_detail_id'];?>"></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&bank_detail_id='.encode($bank_det['bank_detail_id']).'', '',1);?>"><?php echo $curr_nm['currency_code'];?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&bank_detail_id='.encode($bank_det['bank_detail_id']).'', '',1);?>"><?php echo $bank_det['bank_accnt'];?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&bank_detail_id='.encode($bank_det['bank_detail_id']).'', '',1);?>"><?php echo $bank_det['benefry_bank_name'];?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&bank_detail_id='.encode($bank_det['bank_detail_id']).'', '',1);?>"><?php echo $bank_det['accnt_no'];?></a></td>
                          <!--<td>
						  	<?php echo $bank_det['currency_code'] ."  ".$bank_det['price'] ;?>
                            <br /><small class="text-muted"><?php echo $bank_det['currency_name'];?></small>
                          </td>-->
                          <?php /*
                          <td align="right" class="cursorp">
                          		<input type="hidden" value="<?php echo $country['country_id'];?>" id="update-field-<?php echo $country['country_id'];?>" name="update_country_field"/>
						  		<span id="current-price-<?php echo $country['country_id'];?>"><?php echo $country['currency_price'];?></span>
                                <br /><i class="fa fa-edit btn btn-warning btn-xs currencyprice-<?php echo $country['country_id']; ?>" onclick="updatePrice('<?php echo $country['country_id']; ?>','<?php echo $country['currency_price'];?>')" ></i>
                          		<div style="display:none;margin-top:3px;" id="display-option-<?php echo $country['country_id']; ?>">       
                                	<i class="fa fa-check-square btn btn-success btn-xs currency-update-<?php echo $country['country_id']; ?>"></i><i class="fa fa-times-circle btn btn-danger btn-xs currency-cancel-<?php echo $country['country_id']; ?>" style="margin-left:5px;"></i>	
                                </div>
                          </td>
                          */ ?>
                          
                          <td>
                          	
                           		<div data-toggle="buttons" class="btn-group">
                                	<label class="btn btn-xs btn-success <?php echo ($bank_det['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="1" id="<?php echo $bank_det['bank_detail_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>
                                     
                                	<label class="btn btn-xs btn-danger <?php echo ($bank_det['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="0" id="<?php echo $bank_det['bank_detail_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                                </div>
                          
                           </td>
                          <td>
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&bank_detail_id='.encode($bank_det['bank_detail_id']).'&filter_edit='.$filter_edit, '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                           </td>
                        </tr>
                        <?php
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_bank_detail;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'&filter_edit=1', '',1);
                        $pagination_data = $pagination->render();
                    } else{ 
                      echo "<tr><td colspan='5'>No record found !</td></tr>";
                  } ?>
                  </tbody>
                </table>
              </div>
          </form>
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-3 hidden-xs"> </div>
              <?php echo $pagination_data;?>
             
            </div>
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>
<script type="application/javascript">

function updatePrice(country_id,price){		
	$('.currencyprice-'+country_id).before('<input type="text" name="new_price" class="form-control edit'+country_id+'" value="'+price+'" />');
	$('.currencyprice-'+country_id).hide();
	$('#display-option-'+country_id).show();
	
	$('.edit'+country_id).keypress(function (e) {
		if(e.which==13){
			updateCountryPrice(country_id);
			return false;
		}
	});
	
	$('.currency-cancel-'+country_id).click(function(){
		$('.edit'+country_id).remove();
		$('.currencyprice-'+country_id).show();
		$('#display-option-'+country_id).hide();
	});
	

	$('.currency-update-'+country_id).click(function(){
		updateCountryPrice(country_id);
	});
}

function updateCountryPrice(country_id){
	
	var new_val=$('.edit'+country_id).val();	
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateCurrencyPrice', '',1);?>");
			
		
	$.ajax({
		url: url,
		type: "post",
		data: $('.edit'+country_id+',#update-field-'+country_id),
		success: function(json){
			var response= JSON.parse(json);
						
			if(typeof response.error == 'undefined'){
						
				set_alert_message(response.success,"alert-success","fa-check");					
				$('.edit'+country_id).remove();
				$('.currencyprice-'+country_id).show();
				$('#display-option-'+country_id).hide();
				$('#current-price-'+country_id).html(new_val);
									
			}else{
				set_alert_message(response.error,"alert-warning","fa-warning");					
			}
						
		},
		error:function(){
					      
		}
	});			
	
}


	$('input[type=radio][name=status]').change(function() {
	
		//alert($(this).attr('id'));
		var bank_detail_id=$(this).attr('id');
		var status_value = this.value;
		
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateBankStatus', '',1);?>");
        $.ajax({
			
			url : status_url,
			type :'post',
			data :{bank_detail_id:bank_detail_id,status_value:status_value},
			success: function(){
				set_alert_message('Successfully Updated',"alert-success","fa-check");					
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}
			
		});
    });

</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>