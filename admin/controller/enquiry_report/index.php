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
if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='country_name';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order']; 
}else{
	$sort_order = 'ASC';
}
if($display_status){
	//insert user
	/*if(isset($_POST['btn_pro'])){
		$post = post($_POST);
		//$obj_enquiry->getenquiryReport($post);
		$data = json_encode($post);
		$url = urlencode($data);
		page_redirect($obj_general->link($rout, 'mod=view', '',1));
	}*/
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

          <div class="panel-body"></div>
			 <form class="form-horizontal" method="post" name="frm_add" id="frm_add" enctype="multipart/form-data" action="<?php echo $obj_general->link($rout, 'mod=view', '',1);?>">
              <?php if($_SESSION['ADMIN_LOGIN_SWISS'] == '1' && $_SESSION['LOGIN_USER_TYPE']== '1')
					{?>
						<div class="form-group">
							<label class="col-lg-3 control-label"><span class="required">*</span>IB User</label>
							<div class="col-lg-3">
							<?php $userlist = $obj_enquiry->getIBList();
//							printr($userlist); 
							?>
								<select class="form-control validate[required]" name="user_name"  id="user_name"  >
								 <?php if($_SESSION['ADMIN_LOGIN_SWISS'] != '1' && $_SESSION['LOGIN_USER_TYPE']!= '1'){ echo "disabled=disabled" ;}?> >
									<option value="">Select User</option>
									<?php foreach($userlist as $user) { 
											?>
												<option value="<?php echo "4=".$user['international_branch_id']; ?>"><?php echo $user['user_name']; ?></option>
											
									<?php	} ?>                                       
								</select>
							</div>
						  </div>
				<?php } 
				else
				{?>
					<div class="form-group">
						<input type="hidden" name="user_name" id="user_name" value="<?php echo $_SESSION['LOGIN_USER_TYPE']."=".$_SESSION['ADMIN_LOGIN_SWISS'];?>" />
					</div>
				<?php }?>
              <?php if(($_SESSION['ADMIN_LOGIN_SWISS'] == '1' && $_SESSION['LOGIN_USER_TYPE']== '1')|| $_SESSION['LOGIN_USER_TYPE']=='4')
						{ ?>
							
							<div class="form-group">
							<label class="col-lg-3 control-label">Employee Selection</label>
								<div class="col-lg-3">                
									<div style="float:left;width: 200px;">
										<label style="font-weight: normal;">
											<input type="radio" name="emp[]" id="emp" value="0" checked="checked">
											All Employee
										</label>
									</div>
									<div style="float:left;width: 200px;">
										<label style="font-weight: normal;">
											<input type="radio" name="emp[]" id="emp" value="1">
											Employee
										</label>
									</div> 
								</div>
							</div>
							
							<div class="form-group ib_emp" style="display:none;">
							<label class="col-lg-3 control-label"><span class="required">*</span>IB Employee</label>
							<div class="col-lg-3">
								<select class="form-control validate[required]" name="emp_name" id="emp_name">
								
									<option value="">Select User</option>
									                                       
								</select>
							</div>
						  </div>
					<?php	}
					else
				{?>
					<div class="form-group">
						<input type="hidden" name="emp_name"  value="<?php echo $_SESSION['ADMIN_LOGIN_SWISS'];?>" />
					</div>
				<?php }?>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Product</label>
                <div class="col-lg-3">
                <?php $products = $obj_enquiry->getProducts();?>
					<select class="form-control" name="product" >
                        <option value="">Select Product</option>
                        <?php foreach($products as $product) { ?>
                                <option value="<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></option>
                        <?php } ?>                                       
                    </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Date From</label>
                <div class="col-lg-3">
                	<input type="text" name="f_date"  value="" placeholder="From Date" class="span2 form-control validate[required]" data-date-format="yyyy-mm-dd" readonly="readonly" id="f_date"/>
                    </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Date To</label>
                <div class="col-lg-3">
                  <input type="text" name="t_date"  value="" placeholder="To Date" class="span2 form-control validate[required]" data-date-format="yyyy-mm-dd" readonly="readonly" id="t_date"  />
                </div>
              </div>
           
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                	<button type="submit" name="btn_pro" id="btn_pro" class="btn btn-primary">Proceed</button>	
                </div>
              </div>
            </form>
          
          
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-3 hidden-xs"> </div>
             
            </div>
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
jQuery(document).ready(function(){
	   jQuery("#frm_add").validationEngine();
	   
	   var nowTemp = new Date();
		//alert(nowTemp);
	    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		//alert(now);
	    var checkin = $('#f_date').datepicker({
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
    		$('#t_date')[0].focus();
    	}).data('datepicker');
    	var checkout = $('#t_date').datepicker({
    		onRender: function(date) {
				if(checkin.date.valueOf() > date.valueOf())
						return 'disabled';
					else
						return '';
				
    		}
    	}).on('changeDate', function(ev) {
    		checkout.hide();
    	}).data('datepicker');
});

  jQuery(document).ready(function(){
$('input[name="emp[]"]').on('change', function () {
												
			var sel = $(this).val();
			if(sel=='1')
				$("div .ib_emp").show();
			else
				$("div .ib_emp").hide();
			var ib = $('#user_name').val();
			if(sel=='1')
			{
				var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getEmpList', '',1);?>");
				$.ajax({
					method: "POST",
					url:url,
					data:{ib:ib,sel:sel},
					success: function(response)
					{  
//						console.log(response);
						$("#emp_name").html(response);				
					}
				});
			}
		 });
            });
            

                 
 $( "#user_name, #emp_name" ).change(function() {
	var ib_user = $("#user_name").val();
	var emp_user = $("#emp_name").val();
	//alert(ib_user+'===='+emp_user);
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getProduct', '',1);?>");
	$.ajax({
		method: "POST",
		url:url ,
		data:{ib_user:ib_user,emp_user:emp_user},
		success: function(response)
		{  
				//console.log(response);				
				$("#customer_name").html(response);
		}
	});
  });
  var emp_user = $("#emp_name").val();
if(emp_user!='')
$( "#user_name, #emp_name" ).change();
</script>
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>