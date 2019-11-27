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
$branch = '';
$branchid = '';

$edit = '';
if(isset($_GET['branch_id']) && !empty($_GET['branch_id'])){
	$branchid=base64_decode($_GET['branch_id']);
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$branch_id = base64_decode($_GET['branch_id']);
		$branch = $obj_branch->getBranch($branch_id);
		//printr($branch);//die;
		$terms = $obj_branch->getTerms($branch_id);
		//printr($branch);die;
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
	$chckUserName = $obj_general->uniqUserName($post['user_name'],'','');
	if($chckUserName){
		$insert_id = $obj_branch->addBranch($post);
		if(isset($_FILES['logo']) && !empty($_FILES['logo']) && $_FILES['logo']['error'] == 0){
			$obj_branch->uploadLogoImage($insert_id,$_FILES['logo']);
		}
		$_SESSION['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}else{
		$_SESSION['warning'] = 'User name exist!';
		page_redirect($obj_general->link($rout, '', '',1));
	}
}
//$branch_id=0;
//edit
if(isset($_POST['btn_update']) && $edit){
	$post = post($_POST);
	//printr($post);die;
	$branch_id = $branch['international_branch_id'];
	
	$chckUserName = $obj_general->uniqUserName($post['user_name'],$branch_id,'4');
	if($chckUserName){
		$obj_branch->updateBranch($branch_id,$post);
		if(isset($_FILES['logo']) && !empty($_FILES['logo']) && $_FILES['logo']['error'] == 0){
			$obj_branch->uploadLogoImage($branch_id,$_FILES['logo']);
		}
		$_SESSION['success'] = UPDATE;
		page_redirect($obj_general->link($rout, 'filter_edit='.$_GET['filter_edit'], '',1));
	}else{
		$_SESSION['warning'] = 'User name exist!';
		page_redirect($obj_general->link($rout, 'filter_edit='.$_GET['filter_edit'], '',1));
	}
}
$gress_quantity=$obj_branch->getQuantity('p');	
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
      <div class="col-sm-10">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" name="form" id="form" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label class="col-lg-3 control-label">Logo</label>
                <div class="col-lg-9 media">
                  <div class="bg-light pull-left text-center media-large thumb-large">
                    <?php
						$upload_path = DIR_UPLOAD.'admin/logo/';
						$http_upload = HTTP_UPLOAD.'admin/logo/';

						if(isset($branch['logo']) && $branch['logo'] != '' && file_exists($upload_path.'200_'.$branch['logo'])){
							$logo = $http_upload.'200_'.$branch['logo'];
							//echo $logo;
						}else{
							$logo = HTTP_SERVER.'images/blank-user64x64.png';
						}
						//echo $logo;
                        ?>
                    <img src="<?php echo $logo;?>" alt="<?php echo $branch['ibfirst_name'];?>"> </div>
                  <div class="media-body">
                    <input type="file" name="logo" title="Change" class="btn btn-sm btn-info m-b-small">
                    <br>
                    <button type="button" class="btn btn-sm btn-default">Delete</button>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Company</label>
                <div class="col-lg-8">
                  <input type="text" name="company_name" value="<?php echo isset($branch['company_name'])?$branch['company_name']:'';?>" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>First Name</label>
                <div class="col-lg-8">
                  <input type="text" name="first_name" value="<?php echo isset($branch['ibfirst_name'])?$branch['ibfirst_name']:'';?>" class="form-control validate[required]" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Last Name</label>
                <div class="col-lg-8">
                  <input type="text" name="last_name" value="<?php echo isset($branch['iblast_name'])?$branch['iblast_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Email</label>
                <div class="col-lg-8">
                  <input type="text" name="email" value="<?php echo isset($branch['email'])?$branch['email']:'';?>" class="form-control validate[required,custom[email]]">
                  <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Email 1</label>
                <div class="col-lg-8">
                  <input type="text" name="email1" value="<?php echo isset($branch['email1'])?$branch['email1']:'';?>" class="form-control validate[custom[email]]">
                  <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Username</label>
                <div class="col-lg-8">
                  <input type="text" name="user_name" id="user_name" value="<?php echo isset($branch['user_name'])?$branch['user_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
               <?php if(isset($_SESSION['LOGIN_USER_TYPE']) && $_SESSION['LOGIN_USER_TYPE'] == 1 && $edit == 1) {?>
              <div class="form-group">
                <label class="col-lg-3 control-label">Old Password
               </label>
                <div class="col-lg-8">
                  <input type="text" name="oldpassword" value="<?php echo $branch['password_text'];?>" class="form-control" disabled="disabled">
                </div>
              </div>
              <?php }?>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Password
               </label>
                <div class="col-lg-8">
                  <input type="password" name="password" value="" class="form-control <?php echo ($edit == 0)?'validate[required]':'';?>">
                  <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Associated Account</label>
				<div class="col-lg-8">
					<script src="<?php echo HTTP_SERVER;?>js/select2.min.js"></script>
					<script src="<?php echo HTTP_SERVER;?>js/chosen.jquery.min.js"></script>
					<!--<link href="<?php //echo HTTP_SERVER;?>css/chosen.min.css" rel="stylesheet"/>-->
					<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
					
					<?php $userlist = $obj_branch->getUserList($branch_id);
					      $users = array();
						if (isset($branch) && !empty($branch) && $branch['associate_acnt'])
						{
							$users = explode(',',$branch['associate_acnt']);
							//printr($users);
							
							echo '<input type="hidden" name="edit_user_data" id="edit_user_data" value="'.json_encode($users).'">';	
						}?>
					<select data-placeholder="Begin typing a name to filter..." multiple class="chosen-select form-control select2-container select2-container-multi" name="associate_acnt[]">
						<option value=""></option>
						<?php foreach ($userlist as $user) { ?>
							<?php if (isset($branch) && in_array($user['user_type_id'].'='. $user['user_id'],$users)) { ?>

								<option value="<?php echo $user['user_type_id'] . "=" . $user['user_id']; ?>" selected="selected"><?php echo $user['user_name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $user['user_type_id'] . "=" . $user['user_id']; ?>"><?php echo $user['user_name']; ?></option>
							<?php } ?>
						<?php } ?>                                       
					  </select>
					
				</div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Telephone</label>
                <div class="col-lg-8">
                  <input type="text" name="telephone" value="<?php echo isset($branch['telephone'])?$branch['telephone']:'';?>" class="form-control validate[required,custom[onlyNumberSp],minSize[10],maxSize[10]]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Address</label>
                <div class="col-lg-8">
                  <input type="text" name="address" value="<?php echo isset($branch['address'])?$branch['address']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Postcode</label>
                <div class="col-lg-8">
                    <!--custom[onlyNumberSp],maxSize[6]-->
                  <input type="text" name="postcode" value="<?php echo isset($branch['postcode'])?$branch['postcode']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>City</label>
                <div class="col-lg-8">
                  <input type="text" name="city" value="<?php echo isset($branch['city'])?$branch['city']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>State</label>
                <div class="col-lg-8">
                  <input type="text" name="state" value="<?php echo isset($branch['state'])?$branch['state']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Country</label>
                <div class="col-lg-8">
                  <?php 
					$sel_country = (isset($branch['country_id']))?$branch['country_id']:'';
					$countrys = $obj_general->getCountryCombo($sel_country);
					echo $countrys;
					?>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Language Selection</label>
				<div class="col-lg-8">
					<?php $langlist = $obj_branch->getLanguage(); //printr($branch);
					      $lang = array();//$lang
						if (isset($branch) && !empty($branch) && $branch['lang_id'])
						{
							$lang = explode(',',$branch['lang_id']);
							//printr($lang);
							
							echo '<input type="hidden" name="edit_user_data" id="edit_user_data" value="'.json_encode($lang).'">';	
						}?>
					<select data-placeholder="Begin typing a name to filter..." multiple class="chosen-select form-control select2-container select2-container-multi" name="lang[]">
						<option value=""></option>
						<?php foreach ($langlist as $langs) { ?>
							<?php if (isset($branch) && in_array($langs['lang_id'],$lang)) { ?>

								<option value="<?php echo $langs['lang_id']; ?>" selected="selected"><?php echo $langs['language']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $langs['lang_id']; ?>"><?php echo $langs['language']; ?></option>
							<?php } ?>
						<?php } ?>                                       
					  </select>
					
				</div>
              </div>
              
              
              
              
                <div class="form-group" id="discount_div" style="display:none">
                <label class="col-lg-3 control-label">Maximum Allowed Discount %</label>
                <div class="col-lg-8">
                 <input type="text" id="discount" name="discount" value="<?php echo isset($branch['discount'])?$branch['discount']:'';?>" class="form-control"/>
                </div>
              </div>
              
                <div class="form-group" >
                <label class="col-lg-3 control-label">GST %(For Invoice)</label>
                <div class="col-lg-8">
                 <input type="text" id="gst" name="gst" value="<?php echo isset($branch['gst'])?$branch['gst']:'';?>" class="form-control"/>
                </div>
              </div>
               <div class="form-group" >
                <label class="col-lg-3 control-label">Company Address(For Invoice)</label>
                <div class="col-lg-8"><textarea id="company_address" name="company_address"  class="form-control"/> <?php echo isset($branch['company_address'])?$branch['company_address']:'';?></textarea>
                </div>
              </div>
               <div class="form-group" >
                <label class="col-lg-3 control-label">Bank Address (For Invoice)</label>
                <div class="col-lg-8"><textarea id="bank_address" name="bank_address" class="form-control"/><?php echo isset($branch['bank_address'])?$branch['bank_address']:'';?></textarea>
                </div>
              </div>
              
              <!--<div class="form-group">
                <label class="col-lg-3 control-label">Email Signature</label>
                <div class="col-lg-8">
                  <input type="text" name="emailsign" value="<?php //echo isset($branch['email_signature'])?$branch['email_signature']:'';?>" class="form-control">
                </div>
              </div>-->
              <div class="form-group">
                <label class="col-lg-3 control-label">Email Signature</label>
                <div class="col-lg-8">
                  <textarea name="email_signature" class="form-control"><?php echo isset($branch['email_signature'])?$branch['email_signature']:'';?></textarea>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Terms and conditions For Sales Invoice</label>
                <div class="col-lg-8">
                  <textarea name="termsandconditions_invoice" class="form-control validate[required]" id="desc_text"><?php echo isset($branch['termsandconditions_invoice'])?$branch['termsandconditions_invoice']:'';?></textarea>
                </div>
              </div>
              <br>
              <div class="line line-dashed m-t-large"></div>
             <center><b  style="color: green;"> For pouch quotation use</b><br></center><br>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Gres(Percentage % For Cylinder)</label>
                <div class="col-lg-8">
                  <input type="text" name="gres_cyli" value="<?php echo isset($branch['gres_cyli'])?$branch['gres_cyli']:'';?>" class="form-control" />
                </div>
              </div>
              <div class="form-group" style="display:none;">
                <label class="col-lg-3 control-label">Gres(Percentage % for Factory)</label>
                <div class="col-lg-8">
                  <input type="text" name="gres" value="<?php echo isset($branch['gres'])?$branch['gres']:'';?>" class="form-control" />
                </div>
              </div>
              
               <!-- [kinjal] 30-1-2018-->
              <div class="form-group">
                <label class="col-lg-3 control-label">Gress Percentage (% For Factory Qty Wise)</label>
                <div class="col-lg-8">
                	<div >
						<?php 	$qty_per = $obj_branch->getGressPer($branch_id,'pickup');
							foreach($gress_quantity as $key=>$qty){
								$val ='0';
								if(isset($qty_per) && $qty['quantity'] == $qty_per[$key]['product_quantity'])
									$val = $qty_per[$key]['percentage'];
								echo  '<label style="padding-right: 40px; ">'.$qty['quantity'].'<input type="text" name="gress_quantity[pickup]['.$qty['quantity'].']" value="'.$val.'" class="form-control validate[required]" style="width: 80px;color:black" ></label>';
							}?>                             
                    </div>
                </div>
              </div><!-- end-->
              
              <div class="form-group" style="display:none;">
                <label class="col-lg-3 control-label">Gress(Percentage % For Air)</label>
                <div class="col-lg-8">
                  <input type="text" name="gres_air" value="<?php echo isset($branch['gres_air'])?$branch['gres_air']:'';?>" class="form-control" />
                </div>
              </div>
              
              <!-- [kinjal] 30-1-2018-->
              <div class="form-group">
                <label class="col-lg-3 control-label">Gress Percentage (% For Air Qty Wise)</label>
                <div class="col-lg-8">
                	<div >
						<?php 	$qty_per = $obj_branch->getGressPer($branch_id,'air');
							foreach($gress_quantity as $key=>$qty){
								$val ='0';
								if(isset($qty_per) && $qty['quantity'] == $qty_per[$key]['product_quantity'])
									$val = $qty_per[$key]['percentage'];
								echo  '<label style="padding-right: 40px; ">'.$qty['quantity'].'<input type="text" name="gress_quantity[air]['.$qty['quantity'].']" value="'.$val.'" class="form-control validate[required]" style="width: 80px;color:black" ></label>';
							}?>                             
                    </div>
                </div>
              </div><!-- end--> 
              
              <div class="form-group" style="display:none;">
                <label class="col-lg-3 control-label">Gress(Percentage % For Sea)</label>
                <div class="col-lg-8">
                  <input type="text" name="gres_sea" value="<?php echo isset($branch['gres_sea'])?$branch['gres_sea']:'';?>" class="form-control" />
                </div>
              </div>
              
               <!-- [kinjal] 30-1-2018-->
              <div class="form-group">
                <label class="col-lg-3 control-label">Gress Percentage (% For Sea Qty Wise)</label>
                <div class="col-lg-8">
                	<div >
						<?php 	$qty_per = $obj_branch->getGressPer($branch_id,'sea');
							foreach($gress_quantity as $key=>$qty){
								$val ='0';
								if(isset($qty_per) && $qty['quantity'] == $qty_per[$key]['product_quantity'])
									$val = $qty_per[$key]['percentage'];
								echo  '<label style="padding-right: 40px; ">'.$qty['quantity'].'<input type="text" name="gress_quantity[sea]['.$qty['quantity'].']" value="'.$val.'" class="form-control validate[required]" style="width: 80px;color:black" ></label>';
							}?>                             
                    </div>
                </div>
              </div><!-- end-->
         
             
              <div class="form-group">
                <label class="col-lg-3 control-label">Stock price addition For Factory</label>
                <div class="col-lg-8">
                  <input type="text" name="stock_factory" value="<?php echo isset($branch['stock_factory'])?$branch['stock_factory']:'';?>" class="form-control" />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Stock price addition For Air</label>
                <div class="col-lg-8">
                  <input type="text" name="stock_air" value="<?php echo isset($branch['stock_air'])?$branch['stock_air']:'';?>" class="form-control" />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Stock price addition For Sea</label>
                <div class="col-lg-8">
                  <input type="text" name="stock_sea" value="<?php echo isset($branch['stock_sea'])?$branch['stock_sea']:'';?>" class="form-control" />
                </div>
              </div>
              <br>
              <div class="line line-dashed m-t-large"></div>
              <center>   <b  style="color: green;">  For Roll quotation use</b><br></center><br>
              <div class="form-group"> 
                <label class="col-lg-3 control-label">Gress Percentage (% For Factory Qty Wise)</label>
                <div class="col-lg-8">
                  <?php $gress_quantity=$obj_branch->getQuantity('','kg');
                  $qty_per = $obj_branch->getGressPer($branch_id,'pickup','roll');
    				foreach($gress_quantity as $key=>$qty){
    					$val ='0';
    					if(isset($qty_per) && $qty['quantity'] == $qty_per[$key]['product_quantity'])
    						$val = $qty_per[$key]['percentage'];
    					echo  '<label style="padding-right: 40px; ">'.$qty['quantity'].' Kgs <input type="text" name="gress_quantity_roll[pickup]['.$qty['quantity'].']" value="'.$val.'" class="form-control validate[required]" style="width: 80px;color:black" ></label>';
    				}?>
                </div>
              </div> 
              <div class="form-group"> 
                <label class="col-lg-3 control-label">GressPercentage(% For Air Qty Wise)</label>
                <div class="col-lg-8">
                  <?php //$gress_quantity=$obj_branch->getQuantity('','kg');
                  $qty_per = $obj_branch->getGressPer($branch_id,'air','roll');
    				foreach($gress_quantity as $key=>$qty){
    					$val ='0';
    					if(isset($qty_per) && $qty['quantity'] == $qty_per[$key]['product_quantity'])
    						$val = $qty_per[$key]['percentage'];
    					echo  '<label style="padding-right: 40px; ">'.$qty['quantity'].' Kgs <input type="text" name="gress_quantity_roll[air]['.$qty['quantity'].']" value="'.$val.'" class="form-control validate[required]" style="width: 80px;color:black" ></label>';
    				}?>
                </div>
              </div> 
              <div class="form-group"> 
                <label class="col-lg-3 control-label">Gress Percentage (% For Sea Qty Wise)</label>
                <div class="col-lg-8">
                  <?php //$gress_quantity=$obj_branch->getQuantity('','kg');
                  $qty_per = $obj_branch->getGressPer($branch_id,'sea','roll');
    				foreach($gress_quantity as $key=>$qty){
    					$val ='0';
    					if(isset($qty_per) && $qty['quantity'] == $qty_per[$key]['product_quantity'])
    						$val = $qty_per[$key]['percentage'];
    					echo  '<label style="padding-right: 40px; ">'.$qty['quantity'].' Kgs <input type="text" name="gress_quantity_roll[sea]['.$qty['quantity'].']" value="'.$val.'" class="form-control validate[required]" style="width: 80px;color:black" ></label>';
    				}?>
                </div>
              </div>
              <div class="line line-dashed m-t-large"></div>
              <center>   <b  style="color: green;">  For Label quotation use</b><br></center><br>
              <div class="form-group"> 
                <label class="col-lg-3 control-label">Provide the Profit</label>
                <div class="col-lg-3">
                    <select name="profit_type" id="profit_type" class="form-control">
                        <option value="profit" <?php echo (isset($branch['profit_type']) && $branch['profit_type'] == 'profit')?'selected':'';?> > Profit Rich</option>
                        <option value="profit_poor" <?php echo (isset($branch['profit_type']) && $branch['profit_type'] == 'profit_poor')?'selected':'';?>> Profit Poor</option>
                        <option value="profit_more_poor" <?php echo (isset($branch['profit_type']) && $branch['profit_type'] == 'profit_more_poor')?'selected':'';?>> Profit More Poor</option>
                  </select>
                </div>
              </div>
              <div class="form-group"> 
                <label class="col-lg-3 control-label">Gress Percentage (% For Factory Qty Wise)</label>
                <div class="col-lg-8">
                  <?php $gress_quantity=$obj_branch->getQuantity('label','');
                  $qty_per = $obj_branch->getGressPer($branch_id,'pickup','label');
    				foreach($gress_quantity as $key=>$qty){
    					$val ='0';
    					if(isset($qty_per) && $qty['quantity'] == $qty_per[$key]['product_quantity'])
    						$val = $qty_per[$key]['percentage'];
    					echo  '<label style="padding-right: 40px; ">'.$qty['quantity'].'<input type="text" name="gress_quantity_label[pickup]['.$qty['quantity'].']" value="'.$val.'" class="form-control validate[required]" style="width: 80px;color:black" ></label>';
    				}?>
                </div>
              </div> 
              <div class="form-group"> 
                <label class="col-lg-3 control-label">GressPercentage(% For Air Qty Wise)</label>
                <div class="col-lg-8">
                  <?php //$gress_quantity=$obj_branch->getQuantity('','kg');
                  $qty_per = $obj_branch->getGressPer($branch_id,'air','label');
    				foreach($gress_quantity as $key=>$qty){
    					$val ='0';
    					if(isset($qty_per) && $qty['quantity'] == $qty_per[$key]['product_quantity'])
    						$val = $qty_per[$key]['percentage'];
    					echo  '<label style="padding-right: 40px; ">'.$qty['quantity'].' <input type="text" name="gress_quantity_label[air]['.$qty['quantity'].']" value="'.$val.'" class="form-control validate[required]" style="width: 80px;color:black" ></label>';
    				}?>
                </div>
              </div> 
              <div class="form-group"> 
                <label class="col-lg-3 control-label">Gress Percentage (% For Sea Qty Wise)</label>
                <div class="col-lg-8">
                  <?php //$gress_quantity=$obj_branch->getQuantity('','kg');
                  $qty_per = $obj_branch->getGressPer($branch_id,'sea','label');
    				foreach($gress_quantity as $key=>$qty){
    					$val ='0';
    					if(isset($qty_per) && $qty['quantity'] == $qty_per[$key]['product_quantity'])
    						$val = $qty_per[$key]['percentage'];
    					echo  '<label style="padding-right: 40px; ">'.$qty['quantity'].' <input type="text" name="gress_quantity_label[sea]['.$qty['quantity'].']" value="'.$val.'" class="form-control validate[required]" style="width: 80px;color:black" ></label>';
    				}?>
                </div>
              </div> 
              <br>
                <div class="line line-dashed m-t-large"></div>
                  <center>   <b  style="color: blue;">  For Foil Stamping  use</b><br></center><br>
                <div class="form-group"> 
                <label class="col-lg-3 control-label">Plate Price For Foil Stamping (Country to Customer)</label>
                <div class="col-lg-8">
                  <input type="text" name="foil_plate_price"  value="<?php echo isset($branch['foil_plate_price'])?$branch['foil_plate_price']:'';?>" class="form-control" />
                </div>
              </div>  
              <div class="form-group"> 
                <label class="col-lg-3 control-label">Plate Price For Foil Stamping (Swisspac to Country)</label>
                <div class="col-lg-8">
                  <input type="text" name="foil_plate_price_swisspac"  value="<?php echo isset($branch['foil_plate_price_swisspac'])?$branch['foil_plate_price_swisspac']:'';?>" class="form-control" />
                </div>
              </div>
             <div class="line line-dashed m-t-large"></div>
             <br>
        <center> <b     style="color: blue;"> For Digital Printing   use</b><br> </center> <br>
              <div class="form-group">
                <label class="col-lg-3 control-label"> Plate Price For Digital Print (Country to Customer)</label>
                <div class="col-lg-8">
                  <input type="text" name="color_plate_price"  value="<?php echo isset($branch['color_plate_price'])?$branch['color_plate_price']:'';?>" class="form-control" />
                </div>
              </div> 
              <div class="form-group">
                <label class="col-lg-3 control-label">Plate Price For Digital Print (Swisspac to Country)</label>
                <div class="col-lg-8">
                  <input type="text" name="color_plate_price_swisspac"  value="<?php echo isset($branch['color_plate_price_swisspac'])?$branch['color_plate_price_swisspac']:'';?>" class="form-control" />
                </div> 
              </div>  
            
                <div class="form-group">
                <label class="col-lg-3 control-label"> Digital Product-Currency Rate</label>
                <div class="col-lg-8">
                <input type="text" name="digital_convert_rate" value="<?php echo isset($branch['digital_convert_rate'])?$branch['digital_convert_rate']:'';?>" class="form-control" />
                </div>
              </div>  
              <div class="form-group">
                <label class="col-lg-3 control-label"> Digital print discount(%)</label>
                <div class="col-lg-8">
                <input type="text" name="digital_print_discount" value="<?php echo isset($branch['digital_print_discount'])?$branch['digital_print_discount']:'';?>" class="form-control" />
                </div>
              </div>
             <div class="form-group">
                <label class="col-lg-3 control-label">Select Digital Quantity</label>
                <div class="col-lg-8">
                	<div class="form-control scrollbar scroll-y" style="height:150px">
                        <?php
						$sel_qty = array();
						if(isset($branch['digital_quantity']) && !empty($branch['digital_quantity'])){
						    
							$sel_qty = explode(',',$branch['digital_quantity']);
						//	printr($branch['digital_quantity']);
						}
					
						$digital_qty= $obj_branch->getDigitalQuantity();
						
                        foreach($digital_qty as $qty){
                            echo '<div class="checkbox">'; 
                            echo '	<label class="checkbox-custom">';
							if(isset($sel_qty) && in_array($qty['quantity'],$sel_qty)){
                            	echo '	<input type="checkbox" name="digital_quantity[]" id="'.$qty['quantity'].'" value="'.$qty['quantity'].'" checked="checked" onclick="checklayer('.$qty['quantity'].')"> ';
							}else{
								echo '	<input type="checkbox" name="digital_quantity[]" id="'.$qty['quantity'].'" value="'.$qty['quantity'].'" onclick="checklayer('.$qty['quantity'].')" > ';
							}
                            echo '	<i class="fa fa-square-o"></i> '.$qty['quantity'].' </label>';
                            echo '</div>';
                        }
						?>
                    </div>
			  
			  </div>
			  </div>
			  
               <br> 
               <div class="line line-dashed m-t-large"></div>
         <center>    <b  style="color: red;"> For Subsidary Discount</b><br></center><br>
               <div class="form-group">
                <label class="col-lg-3 control-label">Stock Order(Discount % For Air)</label>
                <div class="col-lg-8">
                  <input type="text" name="stock_discount_air" value="<?php echo isset($branch['stock_discount_air'])?$branch['stock_discount_air']:'';?>" class="form-control" />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Stock Order(Discount % For Sea)</label>
                <div class="col-lg-8">
                  <input type="text" name="stock_discount_sea" value="<?php echo isset($branch['stock_discount_sea'])?$branch['stock_discount_sea']:'';?>" class="form-control" />
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Custom Order(Discount % For Air)</label>
                <div class="col-lg-8">
                  <input type="text" name="custom_discount_air" value="<?php echo isset($branch['custom_discount_air'])?$branch['custom_discount_air']:'';?>" class="form-control" />
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Custom Order(Discount % For Sea)</label>
                <div class="col-lg-8">
                  <input type="text" name="custom_discount_sea" value="<?php echo isset($branch['custom_discount_sea'])?$branch['custom_discount_sea']:'';?>" class="form-control" />
                </div>
              </div>  
              <div class="form-group"> 
                <label class="col-lg-3 control-label">Digital Order(Discount % For Air)</label>
                <div class="col-lg-8">
                  <input type="text" name="digital_discount_air" value="<?php echo isset($branch['digital_discount_air'])?$branch['digital_discount_air']:'';?>" class="form-control" />
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Digital Order(Discount % For Sea)</label>
                <div class="col-lg-8">
                  <input type="text" name="digital_discount_sea" value="<?php echo isset($branch['digital_discount_sea'])?$branch['digital_discount_sea']:'';?>" class="form-control" />
                </div>
              </div>
              <br>
               <div class="line line-dashed m-t-large"></div>
              <br> 
              <div class="form-group">
                <label class="col-lg-3 control-label">Valve Price(Indian Rupee <i class="fa fa-inr"></i>)</label>
                <div class="col-lg-8">
                  <input type="text" name="valve_price" value="<?php echo isset($branch['valve_price'])?$branch['valve_price']:'';?>" class="form-control" />
                </div>
              </div>
               
               <div class="form-group">
                <label class="col-lg-3 control-label">Stock Valve Price(Indian Rupee <i class="fa fa-inr"></i>)</label>
                <div class="col-lg-8">
                  <input type="text" name="stock_valve_price" value="<?php echo isset($branch['stock_valve_price'])?$branch['stock_valve_price']:'';?>" class="form-control" />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Stock Order Quantity For Price</label>
                <div class="col-lg-8"><div class="radio">
                <?php 	$stock_qty=$obj_branch->getStockQty();
				foreach($stock_qty as $st){
					echo  '
                      	<label class="radio-custom" style="padding-left: 40px;">
						<input type="radio" name="stock_order_qty" value="'.$st['template_quantity_id'].'" class="required" 
';
if(isset($branch['stock_qty_price']) && ($branch['stock_qty_price']==$st['template_quantity_id']))
echo 'checked="checked"';
echo '><i class="fa fa-circle-o"></i> '.$st['quantity'].' </label>
                    ';
				}?>
                      	<label class="radio-custom" style="padding-left: 40px;">
                        <input type="radio" name="stock_order_qty" value="0" class="required" <?php if(!isset($branch['stock_qty_price']) || ($branch['stock_qty_price']==0)) echo 'checked="checked"';?>>
                        <i class="fa fa-circle-o"></i>Normal </label>
                    </div>
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Template Profit Price (For Air)</label>
                <div class="col-lg-8">
                	<div >
                <?php if(!isset($branch_id))	
				$branch_id=0;
				$profitPriceAir=$obj_branch->getProfitPrice($branch_id,'air');
				foreach($profitPriceAir as $st){
				if($st['quantity']==0)
					$st['quantity']='Normal';
					echo  '<label style="padding-right: 40px; ">'.$st['quantity'].'<input type="text" name="profit_price[air]['.$st['template_quantity_id'].']" value="'.$st['profit_price'].'" class="form-control validate[required]" style="width: 56px;color:black" ></label>';
				}?>                             
                    </div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Template Profit Price (For Sea)</label>
                <div class="col-lg-8">
                	<div >
                <?php $profitPriceSea=$obj_branch->getProfitPrice($branch_id,'sea');
				foreach($profitPriceSea as $st){
				if($st['quantity']==0)
					$st['quantity']='Normal';
					echo  '<label style="padding-right: 40px;">'.$st['quantity'].'<input type="text" name="profit_price[sea]['.$st['template_quantity_id'].']" value="'.$st['profit_price'].'" class="form-control validate[required]" style="width: 56px;color:black" ></label>';
				}?>                      
                    </div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Allow Currency Selection </label>
                <div class="col-lg-8">
                  <div class="btn-group" data-toggle="buttons">
                    <?php $classy='';
						$checky='';
						$classn='';
						$checkn='';
					if(isset($branch['allow_currency']) && $branch['allow_currency'] == 1 ){
						$classy='active';
						$checky='checked="checked" ';
					}
					else
					{
						$classn='active';
						$checkn='checked="checked" ';
					}?>
                    <label class="btn btn-sm btn-white btn-on <?php echo $classy;?>">
                      <input type="radio" name="allow_currency" id="allow_currency1" value="1" <?php echo $checky;?>>
                      Yes </label>
                    <label class="btn btn-sm btn-white btn-off <?php echo $classn;?>">
                      <input type="radio" name="allow_currency" id="allow_currency2" value="0" <?php echo $checkn;?>>
                      No </label>
                  
                  </div>
                  <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Stock Template Percentage</label>
                <div class="col-lg-8">
                  <input type="text" name="stock_template_percentage" value="<?php echo isset($branch['stock_template_percentage'])?$branch['stock_template_percentage']:'';?>" class="form-control" />
                </div>
              </div>
             
            <!-- modified by jayashree-->
               <div class="form-group">
                <label class="col-lg-3 control-label">Order Flush Date</label>
                <div class="col-lg-4">
				  <?php
				//  $orderflushdate =$obj_branch->getOrderFlushDate($branchid);
			
				   ?>
                <select name="order_flush_date" id="order_flush_date" class="form-control">
                
                <?php for($od=1;$od<=31;$od++)  {?>  
                <option value="<?php echo $od;?>" <?php if(isset($branch['order_flush_date'])==$od) { echo 'selected="selected"'; } else { echo '';} ?>  ><?php echo $od;?></option>
          <?php }?>
             
               </select>
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Order Limit</label>
                <div class="col-lg-4">
				  
                <select name="order_limit" id="order_limit" class="form-control">
                
                <?php for($od=1;$od<=10;$od++)  {?>  
                <option value="<?php echo $od;?>" <?php if(isset($branch['order_limit'])==$od) { echo 'selected="selected"'; } else { echo '';} ?>  ><?php echo $od;?></option>
          <?php }?>
             
               </select>
                </div>
              </div>
              
                <div class="form-group">
                    <label class="col-lg-3 control-label">Default Currency</label>
                    <div class="col-lg-4">
                        <select name="default_curr" id="default_curr" class="form-control" onchange='currencyvalue()'>
                         
                         <?php $currency = $obj_branch->getdefaultcurrency();
        							
            				foreach($currency as $curr)
            				{
            				?>      
                            
                           <option value="<?php echo $curr['country_id'];?>" <?php echo (isset($branch['default_curr']) && $branch['default_curr'] == $curr['country_id'])?'selected':'';?> > <?php echo $curr['currency_code']; ?></option>
            
                           <?php
            				}
            				?>
                          </select>
                    </div>
                    <label class="col-lg-2 control-label">Default Cylinder Base Price</label>
                    <div class="col-lg-3">
                        <input type="text" name="default_cyli_base_price" value="<?php echo isset($branch['default_cyli_base_price'])?$branch['default_cyli_base_price']:'';?>" class="form-control" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-lg-3 control-label">Secondary Currency</label>
                    <div class="col-lg-4">
                        <select name="secondary_currency" id="secondary_currency" class="form-control" onchange='currencyvalue()'>
                         
                         <?php $currency = $obj_branch->getdefaultcurrency();
        							
        				foreach($currency as $curr)
        				{
        				?>      
                        
                       <option value="<?php echo $curr['country_id'];?>" <?php echo (isset($branch['secondary_currency']) && $branch['secondary_currency'] == $curr['country_id'])?'selected':'';?> > <?php echo $curr['currency_code']; ?></option>
        
                       <?php
        				}
        				?>
                          </select>
                    </div>
                    
              </div>
              <input type="text" name="currval" id="currval" value="" hidden>
              
                <div class="form-group">
                <label class="col-lg-3 control-label">Product-Currency Rate</label>
                <div class="col-lg-8">
                <input type="text" name="p_rate" value="<?php echo isset($branch['product_rate'])?$branch['product_rate']:'';?>" class="form-control" />
                </div>
              </div>
                <div class="form-group">
                <label class="col-lg-3 control-label">Cylinder-Currency Rate</label>
                <div class="col-lg-8">
                  <input type="text" name="c_rate" value="<?php echo isset($branch['cylinder_rate'])?$branch['cylinder_rate']:'';?>"  class="form-control" />
                </div>
              </div>
               <div class="form-group">
                <label class="col-lg-3 control-label">Tool-Currency Rate</label>
                <div class="col-lg-8">
                  <input type="text" name="t_rate" value="<?php echo isset($branch['tool_rate'])?$branch['tool_rate']:'';?>"  class="form-control" />
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Plastic Scoop Currency Price</label>
                <div class="col-lg-8">
                  <input type="text" name="plastic_scoop_price" value="<?php echo isset($branch['plastic_scoop_price'])?$branch['plastic_scoop_price']:'';?>" class="form-control" />
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Terms and conditions</label>
                <div class="col-lg-8">
                 <textarea name="termsandconditions"  width="500px" height="500px" class="form-control"><?php echo isset($terms ['termsandconditions'])?$terms ['termsandconditions']:'';?></textarea>
               </div>
              </div>
              
             <!-- end of modification-->
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($branch['status']) && $branch['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($branch['status']) && $branch['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Stock Order Price Display</label>
          		<div class="col-lg-9">
                	<div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="stock_order" value="1" class="required"
						<?php echo (isset($branch['stock_order_price']) && $branch['stock_order_price'] == '1')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> Yes </label>
                    </div>
                    <div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="stock_order" value="0" <?php echo (isset($branch['stock_order_price']) && $branch['stock_order_price'] == '0')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> No </label>
                    </div>
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Multi Quotation Price Display</label>
          		<div class="col-lg-9">
                	<div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="multi_quotation_price" value="1" class="required"
						<?php echo (isset($branch['multi_quotation_price']) && $branch['multi_quotation_price'] == '1')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> Yes </label>
                    </div>
                    <div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="multi_quotation_price" value="0" <?php echo (isset($branch['multi_quotation_price']) && $branch['multi_quotation_price'] == '0')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> No </label>
                    </div>
                </div>
              </div>
              
                <div class="form-group">
                <label class="col-lg-3 control-label">Input Stock Order Price Display Compulsory</label>
          		<div class="col-lg-9">
                	<div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="stock_price_compulsory" value="1" class="required"
						<?php echo (isset($branch['stock_price_compulsory']) && $branch['stock_price_compulsory'] == '1')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> Yes </label>
                    </div>
                    <div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="stock_price_compulsory" value="0" <?php echo (isset($branch['stock_price_compulsory']) && $branch['stock_price_compulsory'] == '0')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> No </label>
                    </div>
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Send Email With Gress Price</label>
                <div class="col-lg-8">
                  <div class="btn-group" data-toggle="buttons">
                    <?php $classy='';
						$checky='';
						$classn='';
						$checkn='';
					if(isset($branch['email_confirm']) && $branch['email_confirm'] == 1 ){
						$classy='active';
						$checky='checked="checked" ';
					}
					else
					{
						$classn='active';
						$checkn='checked="checked" ';
					}?>
                    <label class="btn btn-sm btn-white btn-on <?php echo $classy;?>">
                      <input type="radio" name="email_confirm" id="email_confirm1" value="1" <?php echo $checky;?>>
                      Yes </label>
                    <label class="btn btn-sm btn-white btn-off <?php echo $classn;?>">
                      <input type="radio" name="email_confirm" id="email_confirm2" value="0" <?php echo $checkn;?>>
                      No </label>
                  
                  </div>
                  <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Commission In (%)</label>
                <div class="col-lg-4">
                  <input type="text" name="commission" value="<?php echo isset($branch['commission'])?$branch['commission']:'';?>" class="form-control" />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Vat No</label>
                <div class="col-lg-8">
                  <input type="text" name="vat_no" value="<?php echo isset($branch['vat_no'])?$branch['vat_no']:'';?>" class="form-control" />
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">ABN No</label>
                <div class="col-lg-8">
                  <input type="text" name="abn_no" value="<?php echo isset($branch['abn_no'])?$branch['abn_no']:'';?>" class="form-control" />
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Multi Quotation expiry Days</label>
                <div class="col-lg-8">
                  <input type="text" name="expiry_days" value="<?php echo isset($branch['Multi_Quotation_expiry_days'])?$branch['Multi_Quotation_expiry_days']:'';?>" class="form-control" />
                </div>
              </div>     
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Fedex Account number</label>
                <div class="col-lg-8">
                  <input type="text" name="fedex_account_no" value="<?php echo isset($branch['fedex_account_no'])?$branch['fedex_account_no']:'';?>" class="form-control" />
                </div>
              </div>
              
              
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                  <?php if($edit){?>
                  <button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                  <?php } else { ?>
                  <button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>
                  <?php } ?>
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a> </div>
              </div>
              <div id="loading"></div>
            </form>
          </div>
        </section>
      </div>
    </div>
  </section>
</section>
<style type="text/css">
.btn-on.active {
    background: none repeat scroll 0 0 #3fcf7f;
}
.btn-off.active{
	background: none repeat scroll 0 0 #3fcf7f;
	border: 1px solid #767676;
	color: #fff;
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
		// binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
		CKEDITOR.replace('email_signature', {
			toolbar: [ 
	{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
	{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source' ] },
	
	'/',
	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic','Strike' ] },
	{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
	
	{ name: 'styles', items: [ 'Styles', 'Format'] },
	]});
	
	CKEDITOR.replace('desc_text');
    });
</script>
<!-- select2 <script src="<?php echo HTTP_SERVER;?>js/select2/select2.min.js"></script>--> 
<script>

    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
		//salert($("#default_curr option:selected").text();
        jQuery("#form").validationEngine();
		
		$("#").submit(function (e) {
			//e.preventDefault();
			var name = $("#user_name").val();
			var check = checkUser(name);
			if(!check){
				e.preventDefault();
			}
		});
		
		// UserName Already Exsist Ajax
		$("#user_name").change(function(e){
			var name = $(this).val();
			checkUser(name);
		});
		//alert($('#country_id').val());
		$('#country_id').change(function(){
			var country_id =$('#country_id').val();
			if(country_id == 111 )
			{
				$('#discount_div').show();				
			}
			else
			{
				$('#discount_div').hide();				
			}
		});
		var country_id =$('#country_id').val();
			if(country_id == 111 )
			{
				$('#discount_div').show();				
			}
			else
			{
				$('#discount_div').hide();				
			}
    });
	
	function checkUser(name){
		var orgname = '<?php echo isset($branch['user_name'])?$branch['user_name']:'';?>';
		if(name.length > 0 && orgname != name){
			$(".uniqusername").remove();
			var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=UserNameAlreadyExsist', '',1);?>");
			$("#loading").show();
			$.ajax({
				url : status_url,
				type :'post',
				data :{name:name},
				success: function(json) {
					if(json > 0){
						$("#user_name").val('');
						$("#user_name").after('<span class="required uniqusername">Username already exists!</span>');
						$("#loading").hide();
						return false;
					}else{
						$("#loading").hide();
						$(".uniqusername").remove();
						return true;
					}
				}
			});
		}else{
			$("#loading").hide();
			$(".uniqusername").remove();
			return true;
		}
	}

//jayashree

function currencyvalue(value)
{
	//alert(this.value);
	//alert($("#default_curr option:selected").text();
	var index = document.getElementById('default_curr').selectedIndex;
	var opt = document.getElementById('default_curr').options;
	var currvalue = opt[index].text;
	//alert(currvalue);
	document.getElementById('currval').value = currvalue;
}
$(".chosen-select").chosen({
		no_results_text: "Oops, nothing found!"
	});
</script> 
<!-- Close : validation script -->

<?php
} else { 
	include_once(DIR_ADMIN.'access_denied.php');
}
?>
