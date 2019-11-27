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
 //$countries = $obj_sample_sheet->getCountry();
// printr($countries);
//Start : edit
$edit = '';

if(isset($_GET['sample_id']) && !empty($_GET['sample_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$sample_id = base64_decode($_GET['sample_id']);
		$sample = $obj_sample_sheet->getSample($sample_id);
		//printr($sample);
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		$insert_id = $obj_sample_sheet->addSample($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		$sample_id = $sample['sample_id'];
		$obj_sample_sheet->updateSample($sample_id,$post);
		$obj_session->data['success'] = UPDATE;
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
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form_sample" id="form_sample" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Customer Name</label>
                <div class="col-lg-8">
                  	<input type="text" name="customer_name" id="customer_name" placeholder="Customer Name" value="<?php echo isset($sample['customer_name'])?$sample['customer_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
                <?php /*?><div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Customer Visit Date</label>
                <div class="col-lg-8">
                  	<input type="text" name="customer_visit_date" id="customer_visit_date" placeholder="C" value="<?php echo isset($sample['customer_visit_date'])?$sample['customer_visit_date']:'';?>" class="form-control validate[required]">
                </div>
              </div><?php */?>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Customer Visit Date</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control validate[required]" name="customer_visit_date" placeholder="Customer Visit Date" value="<?php echo isset($sample['customer_visit_date'])?$sample['customer_visit_date']:'';?>" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="customer_visit_date"/>
                    </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Customer Address</label>
                <div class="col-lg-8">
                  	<textarea type="text" name="customer_address" id="customer_address" value="" class="form-control validate[required]"><?php echo isset($sample['customer_address'])?$sample['customer_address']:'';?></textarea>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Email</label>
                <div class="col-lg-5">
                  	<input type="text" name="email" placeholder="test@gmail.com" value="<?php echo isset($sample['email'])?$sample['email']:'';?>" class="form-control validate[required,custom[email]]">
                </div>
              </div>
               
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Telephone</label>
                <div class="col-lg-5">
                  	<input type="text" name="telephone" placeholder="Telephone" value="<?php echo isset($sample['telephone'])?$sample['telephone']:'';?>" class="form-control validate[required,custom[onlyNumberSp],minSize[10],maxSize[10]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Customer Requirements</label>
                <div class="col-lg-8">
                  	<textarea type="text" name="customer_requirements" id="customer_requirements" value="" class="form-control validate[required]"><?php echo isset($sample['customer_requirements'])?$sample['customer_requirements']:'';?></textarea>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Customer Product</label>
                <div class="col-lg-8">
                  	<input type="text" name="customer_Product" id="customer_Product" value="<?php echo isset($sample['customer_Product'])?$sample['customer_Product']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Weight of Product in each bag</label>
                <div class="col-lg-8">
                  	<input type="text" name="weight" id="weight" value="<?php echo isset($sample['weight'])?$sample['weight']:'';?>" class="form-control validate[required,custom[number],min[0.001]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Total no of bag</label>
                <div class="col-lg-8">
                  	<input type="text" name="total_bag" id="total_bag" value="<?php echo isset($sample['total_bag'])?$sample['total_bag']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Sample Name given to Customer</label>
                <div class="col-lg-8">
                  	<input type="text" name="sample_name" id="sample_name" value="<?php echo isset($sample['sample_name'])?$sample['sample_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
             <?php /*?> <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Who attend Customer?</label>
                <div class="col-lg-8">
                  	<input type="text" name="attend_customer" id="attend_customer" value="<?php echo isset($sample['attend_customer'])?$sample['attend_customer']:'';?>" class="form-control validate[required]">
                </div>
              </div><?php */?>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Who attend Customer?</label>
                <div class="col-lg-4">
                <?php $userlist = $obj_sample_sheet->getUserList();
					  //printr($userlist); ?>
					<select class="form-control" name="attend_customer" >
                        <option value="">Please Select</option>
                         <?php foreach($userlist as $user) { ?>
                        <?php if($sample['attend_customer']==$user['user_name'])
				  		 {?>
                                <option value="<?php //echo $user['user_type_id']."=".$user['user_id']; ?><?php echo $user['user_name']; ?>" selected="selected"><?php echo $user['user_name']; ?></option>
                        <?php } else { ?>
                       			 <option value="<?php echo $user['user_name']; ?>"> <?php echo $user['user_name']; ?></option>
				   <?php } }?>
                        
                        
                        
                                                           
                    </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Result of Meeting</label>
                <div class="col-lg-8">
                  	<textarea type="text" name="result" id="result" value="" class="form-control validate[required]"> <?php echo isset($sample['result'])?$sample['result']:'';?></textarea>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Follow Up-1 Date </label>
                <div class="col-lg-8">
                  <input type="text" class="form-control validate[required]" name="f1_date" placeholder="Follow Up-1" value="<?php echo isset($sample['f1_date'])?$sample['f1_date']:'';?>" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="f1_date"/><br>
                  
                 <textarea type="text" class="form-control validate[required]" value="" name="f1_description" placeholder="Follow Up-1 Description" id="f1_description"/><?php echo isset($sample['f1_description'])?$sample['f1_description']:'';?></textarea> 
                    </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Follow Up-2 Date</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control validate[required]" name="f2_date" placeholder="Follow Up-2" value="<?php echo isset($sample['f2_date'])?$sample['f2_date']:'';?>" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="f2_date"/><br>
                  
                  <textarea type="text" class="form-control validate[required]" value="" name="f2_description" placeholder="Follow Up-2 Description" id="f2_description"/><?php echo isset($sample['f2_description'])?$sample['f2_description']:'';?></textarea>
                    </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Follow Up-3 Date</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control validate[required]" name="f3_date" placeholder="Follow Up-3" value="<?php echo isset($sample['f3_date'])?$sample['f3_date']:'';?>" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="f3_date"/><br>
                  
                  <textarea type="text" class="form-control validate[required]" value="" name="f3_description" placeholder="Follow Up-2 Description" id="f3_description"/><?php echo isset($sample['f3_description'])?$sample['f3_description']:'';?></textarea>
                    </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Deal Closed</label>
                <div class="col-lg-8">
                  	<textarea type="text" name="deal" id="deal" value="" class="form-control validate[required]"><?php echo isset($sample['deal'])?$sample['deal']:'';?></textarea>
                </div>
              </div>
             
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($sample['status']) && $sample['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($sample['status']) && $sample['status'] == 0)?'selected':'';?>> Inactive</option>
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
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
                </div>
              </div>
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
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>
<script>
jQuery(document).ready(function(){
	   jQuery("#form_sample").validationEngine();
	   
	   	 $("#customer_visit_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
		 $("#f1_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
		 $("#f2_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
		 $("#f3_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
	   
	   var nowTemp = new Date();
		//alert(nowTemp);
	    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		//alert(now);
	    var checkin = $('#customer_visit_date').datepicker({
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
    		$('#customer_visit_date')[0].focus();
    	}).data('datepicker');
		
    	var checkin = $('#f1_date').datepicker({
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
    		$('#f1_date')[0].focus();
    	}).data('datepicker');
		
		var checkin = $('#f2_date').datepicker({
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
    		$('#f2_date')[0].focus();
    	}).data('datepicker');
		
		var checkin = $('#f3_date').datepicker({
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
    		$('#f3_date')[0].focus();
    	}).data('datepicker');
});

$('#customer_name').keyup(function () {
			if (this.value.match(/[^a-zA-Z ]/g)) {
			//this.value = this.value.replace(/[^a-zA-Z]/g, ”);
			alert("Please Enter Only Characters");
			}
	});


</script>