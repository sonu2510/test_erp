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
if(isset($_GET['emp_staff_detail_id']) && !empty($_GET['emp_staff_detail_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$emp_staff_detail_id = base64_decode($_GET['emp_staff_detail_id']);
		$personainfo = $obj_empdetails->getpersonalinfo($emp_staff_detail_id);
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}

//Close : edit



if($display_status){

//insert
if(isset($_POST['btn_save'])){
	$post = post($_POST);
	//printr($post);die;
	$insert_id = $obj_empdetails->addpersonal_info($post);
	if(isset($_FILES['profile']) && !empty($_FILES['profile']) && $_FILES['profile']['error'] == 0){
		$obj_empdetails->uploadLogoImage($insert_id,$_FILES['profile']);
	}
	$_SESSION['success'] = ADD;
	page_redirect($obj_general->link($rout, '', '',1));
}

//edit
if(isset($_POST['btn_update']) && $edit){
	$post = post($_POST);
	
	$info_id = $personainfo['emp_staff_detail_id'];
	$obj_empdetails->updatepersonlainfo($info_id,$post);
	if(isset($_FILES['profile']) && !empty($_FILES['profile']) && $_FILES['profile']['error'] == 0){
		$obj_empdetails->uploadLogoImage($info_id,$_FILES['profile']);
	}
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
		<div class="col-sm-12"> <section class="panel"> 
		
		<header class="panel-heading bg-white"> Employee Detail </header>
		<div class="panel-body">
		
		<form class="form-horizontal" name="form" id="form" method="post" enctype="multipart/form-data">
		<div class="form-group"> 
			<label class="col-lg-2 control-label">Employee Supplier</label> 
			<div class="col-lg-3"> 
		
					<select name="staff_group_id" class="form-control">
				 <?php 
							   $data = $obj_empdetails->getSupplier(); 
							
							  foreach($data as $d){
						   ?>
								<option value="<?php echo $d['staff_group_id']; ?>" <?php if(isset($personainfo)) { if($personainfo['staff_group_id']==$d['staff_group_id']){ echo "selected"; }else { echo "";}  }?> ><?php echo $d['staff_group_name']; ?></option>
				<?php } ?>
				</select> 
			<label class="col-lg-2 control-label">Series Number</label>
			<div class="col-lg-3"> <input type="text" name="series" placeholder=""  class=" form-control" value="<?php  if(isset($personainfo)){ echo $personainfo['series']; }  ?>"></div>

			</div> 
		</div>
		
		<!--<div class="form-group"> 
			<label class="col-lg-3 control-label">Designation</label>
			<div class="col-lg-4"> <input type="text" name="desg" placeholder="Designation" class=" form-control " value="<?php  //if(isset($personainfo)){ echo $personainfo['desg']; }  ?>" > 
			</div>
		</div> -->
		<h4><i class="fa fa-edit"></i> Employee Basic Info.</h4>
		<div class="line m-t-large" style="margin-top:-4px;"></div>
		<br>
		<div class="form-group"> 
			<label class="col-lg-2 control-label">First Name</label>
			<div class="col-lg-4"> <input type="text" name="fname" placeholder="First Name"  class=" form-control" value="<?php  if(isset($personainfo)){ echo $personainfo['fname']; }  ?>"></div>
			<label class="col-lg-2 control-label">Profile Photo</label>
			 <div class="bg-light pull-left text-center media-large thumb-large">
                    <?php
						$upload_path = DIR_UPLOAD.'admin/profile/';
						$http_upload = HTTP_UPLOAD.'admin/profile/';

						if(isset($personainfo['profile']) && $personainfo['profile'] != '' && file_exists($upload_path.'200_'.$personainfo['profile']))
						{
							$logo = $http_upload.'200_'.$personainfo['profile'];
							//echo $logo;
						}else{
							$logo = HTTP_SERVER.'images/blank-user64x64.png';
						}
                        ?>
                    <img src="<?php echo $logo;?>" alt="<?php echo $personainfo['fname'];?>">
			</div>
			<div class="media-body col-lg-1">
					<input type="file" name="profile" title="Upload" class="btn btn-sm btn-info m-b-small">
			 </div>
			
		</div>
		<div class="form-group"> 
			<label class="col-lg-2 control-label">Father / Husband Name</label>
			<div class="col-lg-4"> <input type="text" name="mname" placeholder="Father / Husband Name"  class=" form-control" value="<?php  if(isset($personainfo)){ echo $personainfo['mname']; }  ?>"></div>
		</div>
		<div class="form-group"> 
			<label class="col-lg-2 control-label">Last Name</label>
			<div class="col-lg-4"> <input type="text" name="lname" placeholder="Last Name"  class=" form-control" value="<?php  if(isset($personainfo)){ echo $personainfo['lname']; }  ?>"></div>
		</div>
		
		<div class="form-group"> 
			<label class="col-lg-2 control-label">Gender</label>
			<div class="col-lg-3">
				<div class="radio"> 
				&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-custom"> <input type="radio" name="gen" value="male" checked="checked" <?php if(isset($personainfo) && $personainfo['gen']=='male') { ?> checked="checked" <?php } ?>> <i class="fa fa-circle-o "></i>Male </label>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-custom"> <input type="radio" name="gen"  value="female" <?php if(isset($personainfo) && $personainfo['gen']=='female') { ?> checked="checked"  <?php } ?>> <i class="fa fa-circle-o "></i> Female </label> 
				</div>
			</div>
			<label class="col-lg-2 control-label">Marital Status</label>
			<div class="col-lg-3">
				<div class="radio"> 
					&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-custom"> <input type="radio" name="radio_m" checked="checked" value="yes" <?php if(isset($personainfo) && $personainfo['radio_m']=='yes') { ?> checked="checked" <?php } ?>> <i class="fa fa-circle-o "></i>Yes </label> 
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-custom"> <input type="radio" name="radio_m" value="no" <?php if(isset($personainfo) && $personainfo['radio_m']=='no') { ?> checked="checked" <?php }?> > <i class="fa fa-circle-o "></i> No </label> 
				</div>
			</div>
		</div>
		<div class="form-group"> 
			<label class="col-lg-2 control-label">Date of Birth</label>
			<div class="col-lg-2"> <input type="text" id="db" name="dob" class=" form-control datepicker" placeholder="" class="form-control " data-date-format="dd-mm-yyyy" value="<?php  if(isset($personainfo)){ echo dateformat(1,$personainfo['dob']) ; }  ?>"> 
			
			</div>
		</div>
		
		<div class="form-group"> 
			<label class="col-lg-2 control-label">Phone Number</label>
			<div class="col-lg-3"> <input type="text" name="pno" placeholder="Number" class=" form-control"  value="<?php  if(isset($personainfo)){ echo $personainfo['pno']; }  ?>" > 
			</div>
		</div>
		<div class="form-group"> 
			<label class="col-lg-2 control-label">Mobile Number</label>
			<div class="col-lg-1"> <input type="text" name="mno1" placeholder="" Disabled value="+91" class=" form-control " > 
			</div>
			<div class="col-lg-3"> <input type="text" name="mno" placeholder="Number" class="form-control " value="<?php  if(isset($personainfo)){ echo $personainfo['mno']; }  // validate[required,custom[onlyNumberSp],minSize[10],maxSize[10]]?>" > 
			</div>
		</div>
		
		
		<h4><i class="fa fa-edit"></i> Residential Info.</h4>
		<div class="line m-t-large" style="margin-top:-4px;"></div>
		<br>
		<div class="form-group">
			<label class="col-lg-2 control-label">Address</label>
			<div class="col-lg-6">
				<textarea placeholder="Address" name="addr" rows="5" class="form-control" data-trigger="keyup" data-rangelength="[0,200]"><?php  if(isset($personainfo)){ echo $personainfo['addr']; }  ?></textarea> 
			
			</div> 
		</div>
		
		<div class="form-group">
			<label class="col-lg-2 control-label">Corresponding Address</label>
			<div class="col-lg-6">
				<textarea placeholder="Corresponding Address" name="corr_addr" rows="5" class="form-control " data-trigger="keyup" data-rangelength="[0,200]"><?php  if(isset($personainfo)){ echo $personainfo['corr_addr']; }  ?></textarea> 
			
			</div> 
		</div>
		
		<div class="form-group"> 
			<label class="col-lg-2 control-label">City</label>
			<div class="col-lg-3"> <input type="text" name="city" placeholder="city" class="bg-focus form-control " value="<?php  if(isset($personainfo)){ echo $personainfo['city']; }  ?>" > 
			</div>
			<label class="col-lg-2 control-label">State</label>
			<div class="col-lg-3"> <input type="text" name="state" placeholder="State" class="bg-focus form-control " value="<?php  if(isset($personainfo)){ echo $personainfo['state']; }  ?>" > 
			</div>
		</div>
				
		<div class="form-group"> 
			<label class="col-lg-2 control-label">Country</label>
			<div class="col-lg-3">
				<select name="country" id="country" class="form-control ">
					<option value="">Select Country</option>
				<?php $country = $obj_empdetails->getcountry();
						foreach($country as $con)
						{ if(isset($personainfo) && $personainfo['country']==$con['country_id']) { ?>
							<option value="<?php echo $con['country_id']; ?>" selected><?php echo $con['country_name']; ?></option>
						<?php } else 
							{?>
							<option value="<?php echo $con['country_id']; ?>"><?php echo $con['country_name']; ?></option>
						<?php }
						} ?>
				</select>
			</div>
			<label class="col-lg-2 control-label">Pin Code</label>
			<div class="col-lg-3"> <input type="text" name="pin" placeholder="Pincode" class="bg-focus form-control " value="<?php  if(isset($personainfo)){ echo $personainfo['pin']; }  ?>" > 
			</div>
		</div>
		
		<div class="form-group"> 
			<label class="col-lg-2 control-label">Email</label>
			<div class="col-lg-4"> 
				<input type="email" name="email" placeholder="test@example.com" class="bg-focus form-control " value="<?php  if(isset($personainfo)){ echo $personainfo['email']; }  ?>" > 
			</div>
			
		</div>
		<h4><i class="fa fa-edit"></i> Emergency Contact.</h4>
		<div class="line m-t-large" style="margin-top:-4px;"></div>
		<br>
		
		<div class="form-group"> 
			<label class="col-lg-2 control-label">Contact Name</label>
			<div class="col-lg-4"> <input type="text" name="c_name" placeholder="Contact Name"  class=" form-control " value="<?php  if(isset($personainfo)){ echo $personainfo['c_name']; }  ?>"></div>
		</div>
		
		<div class="form-group"> 
			<label class="col-lg-2 control-label">Primary Number</label>
			<div class="col-lg-3"> <input type="text" name="pri_no" placeholder="Primary Number" class=" form-control "  value="<?php  if(isset($personainfo)){ echo $personainfo['pri_no']; } // validate[required,custom[onlyNumberSp],minSize[10],maxSize[10]] ?>" > 
			</div>
			<label class="col-lg-2 control-label">Alternet Number</label>
			<div class="col-lg-3"> <input type="text" name="alter_no1" placeholder="Alternet Number" class="form-control " value="<?php  if(isset($personainfo)){ echo $personainfo['alter_no1']; }  ?>" > 
			</div>
		</div>
		<div class="form-group"> 
			<label class="col-lg-2 control-label">Relationship</label>
			<div class="col-lg-4"> <input type="text" name="relation" placeholder="Relationship"  class=" form-control" value="<?php  if(isset($personainfo)){ echo $personainfo['relation']; }  ?>"></div>
		</div>
		
		<h4><i class="fa fa-edit"></i> Documents Detail.</h4>
		<div class="line m-t-large" style="margin-top:-4px;"></div>
		<br>
		
		
		
		<div class="form-group"> 
			<label class="col-lg-2 control-label">Adhar Card Num.</label>
			<div class="col-lg-3"> <input type="text" name="adhar_card_no" placeholder="Adhar Card Num." class="bg-focus form-control" value="<?php  if(isset($personainfo)){ echo $personainfo['adhar_card_no']; }  ?>" > 
			</div>
			<label class="col-lg-2 control-label">As per document</label>
			<div class="col-lg-3"> <input type="text" name="adhar_card_nm" placeholder="Name As per document" class="bg-focus form-control " value="<?php  if(isset($personainfo)){ echo $personainfo['adhar_card_nm']; }  ?>" > 
			</div>
		</div>
		
		<div class="form-group"> 
			<label class="col-lg-2 control-label">Voter ID Card Num.</label>
			<div class="col-lg-3"> <input type="text" name="water_id_card_no" placeholder="Voter ID Card Num." class="bg-focus form-control " value="<?php  if(isset($personainfo)){ echo $personainfo['water_id_card_no']; }  ?>" > 
			</div>
			<label class="col-lg-2 control-label">As per document</label>
			<div class="col-lg-3"> <input type="text" name="water_id_card_nm" placeholder="Name As per document" class="bg-focus form-control " value="<?php  if(isset($personainfo)){ echo $personainfo['water_id_card_nm']; }  ?>" > 
			</div>
		</div>
		
		<div class="form-group"> 
			<label class="col-lg-2 control-label">PAN Card Num.</label>
			<div class="col-lg-3"> <input type="text" name="pan_card_no" placeholder="PAN ID Card Num." class="bg-focus form-control" value="<?php  if(isset($personainfo)){ echo $personainfo['pan_card_no']; }  ?>" > 
			</div>
			<label class="col-lg-2 control-label">As per document</label>
			<div class="col-lg-3"> <input type="text" name="pan_card_nm" placeholder="Name As per document" class="bg-focus form-control" value="<?php  if(isset($personainfo)){ echo $personainfo['pan_card_nm']; }  ?>" > 
			</div>
		</div>
		
		<div class="form-group"> 
			<label class="col-lg-2 control-label">Bank Account Num.</label>
			<div class="col-lg-3"> <input type="text" name="bank_acc_no" placeholder="Bank Account Num." class="bg-focus form-control" value="<?php  if(isset($personainfo)){ echo $personainfo['bank_acc_no']; }  ?>" > 
			</div>
			<label class="col-lg-2 control-label">As per document</label>
			<div class="col-lg-3"> <input type="text" name="bank_acc_nm" placeholder="Name As per document" class="bg-focus form-control " value="<?php  if(isset($personainfo)){ echo $personainfo['bank_acc_nm']; }  ?>" > 
			</div>
		</div>
		
		<div class="form-group"> 
			<label class="col-lg-2 control-label">IFSC Code</label>
			<div class="col-lg-3"> <input type="text" name="ifsc_code" placeholder="IFSC Code" class="bg-focus form-control " value="<?php  if(isset($personainfo)){ echo $personainfo['ifsc_code']; }  ?>" > 
			</div>
			<label class="col-lg-2 control-label">MICR Code</label>
			<div class="col-lg-3"> <input type="text" name="micr_code" placeholder="MICR Code" class="bg-focus form-control " value="<?php  if(isset($personainfo)){ echo $personainfo['micr_code']; }  ?>" > 
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-lg-2 control-label">Branch Address</label>
			<div class="col-lg-6">
				<textarea placeholder="Address" name="branch_addr" rows="5" class="form-control " data-trigger="keyup" data-rangelength="[0,200]"><?php  if(isset($personainfo)){ echo $personainfo['branch_addr']; }  ?></textarea> 
			
			</div> 
		</div>
		
		<div class="form-group"> 
			<label class="col-lg-2 control-label">Date of Joining</label>
			<div class="col-lg-2"> 
				<input type="text" id="dj" name="doj" class=" form-control datepicker"  placeholder="" data-date-format="dd-mm-yyyy" value="<?php  if(isset($personainfo)){ echo dateformat(1,$personainfo['doj']); }  ?>"> 
			</div>
			<label class="col-lg-3 control-label">Date of Leaving</label>
			<div class="col-lg-2"> 
				<input type="text" id="dj1" name="dolev" class=" form-control datepicker"  placeholder="" data-date-format="dd-mm-yyyy" value="<?php  if(isset($personainfo)){ echo dateformat(1,$personainfo['dolev']); }  ?>"> 
			</div>
		</div>
		<div class="form-group"> 
			<label class="col-lg-2 control-label">Salary</label>
			<div class="col-lg-3"> <input type="text" name="salary" placeholder="Salary" class="bg-focus form-control" value="<?php  if(isset($personainfo)){ echo $personainfo['salary']; }  ?>" > 
			</div>
			<label class="col-lg-2 control-label">PF Status</label>
			<div class="col-lg-3">
				<div class="radio"> 
					&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-custom"> <input type="radio" name="radio_pf" checked="checked" value="yes" <?php if(isset($personainfo) && $personainfo['radio_pf']=='yes') { ?> checked="checked" <?php } ?> > <i class="fa fa-circle-o "></i>Yes </label> 
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-custom"> <input type="radio" name="radio_pf"  value="no" <?php if(isset($personainfo) && $personainfo['radio_pf']=='no') { ?> checked="checked"  <?php } ?> > <i class="fa fa-circle-o "></i> No </label> 
				</div>
			</div>
		</div>
		<div class="form-group"> 
			
		</div>
		
		<hr>
		<div class="form-group">
                <label class="col-lg-2 control-label">Status</label>
                <div class="col-lg-3">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($personainfo['status']) && $personainfo['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($personainfo) && $personainfo['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
              </div>
		
		<!--<div class="form-group"> 
			<label class="col-lg-2 control-label">Leave</label>
			<div class="col-lg-4">
				<div style="width: 100%;  height: 34px;">
					<div class="radio" style="  width: 40%;  float: left;">
						<label class="checkbox-custom">
						<input type="checkbox" class="" name="printing_option_type[]" id="pType1" value="bottom" <?php //echo (in_array('bottom',$sel_printing))?'checked="checked"':'';?>>
						<i class="fa fa-square-o"></i> PH </label>
					</div>
					<div style="  width: auto;  float: right;">
						<select name="bottom_min_qty" id="bottom_min_qty" class="form-control validate[required]" style="display: block;">
							<option value="">Select Days</option>
							<?php //for($i=1;$i<=5;$i++) {?>
							<option value="7"><?php //echo $i;?></option>
							<?php //} ?>
						</select>
					</div>
				</div>
				<div style="width: 100%;  height: 34px;">
					<div class="radio" style="  width: 40%;  float: left;">
						<label class="checkbox-custom">
						<input type="checkbox" class="" name="printing_option_type[]" id="pType1" value="bottom" <?php //echo (in_array('bottom',$sel_printing))?'checked="checked"':'';?>>
						<i class="fa fa-square-o"></i> PL </label>
					</div>
					<div style="  width: auto;  float: right;">
						<select name="bottom_min_qty" id="bottom_min_qty" class="form-control validate[required]" style="display: block;">
							<option value="">Select Days</option>
							<?php //for($i=1;$i<=5;$i++) {?>
							<option value="7"><?php //echo $i;?></option>
							<?php //} ?>
						</select>
					</div>
				</div>
				<div style="width: 100%;  height: 34px;">
					<div class="radio" style="  width: 40%;  float: left;">
						<label class="checkbox-custom">
						<input type="checkbox" class="" name="printing_option_type[]" id="pType1" value="bottom" <?php //echo (in_array('bottom',$sel_printing))?'checked="checked"':'';?>>
						<i class="fa fa-square-o"></i> CL </label>
					</div>
					<div style="  width: auto;  float: right;">
						<select name="bottom_min_qty" id="bottom_min_qty" class="form-control validate[required]" style="display: block;">
							<option value="">Select Days</option>
							<?php //for($i=1;$i<=5;$i++) {?>
							<option value="7"><?php //echo $i;?></option>
							<?php //} ?>
						</select>
					</div>
				</div>
			</div>
		</div>-->
		
		 <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                <?php if($edit){?>
                  	<button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                <?php } else { ?>
                	<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
                <?php } ?>  
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
                </div>
				</form>
      
  </section>
</section>




<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
    });
	
	$('#dj , #dj1, #db').on('changeDate', function(ev){
		$(this).datepicker('hide');
	});
	$('img').bind('contextmenu', function(e) {
    return false;
});
	
</script> 
<!-- Close : validation script -->

<?php
} else { 
	include_once(DIR_ADMIN.'access_denied.php');
}
?>