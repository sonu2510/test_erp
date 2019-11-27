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
if(isset($_GET['bank_detail_id']) && !empty($_GET['bank_detail_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		//printr($_GET['bank_detail_id']);
		$bank_detail_id = base64_decode($_GET['bank_detail_id']);
		//echo $bank_detail_id;die;
		$bank_detail = $obj_bank->getBankDetail($bank_detail_id);
		//$courier_data = $obj_country->getCouriers();
		//printr($bank_detail);
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
	////echo "add";
	if(isset($_POST['btn_save'])){
		//echo "hii";
		$post = post($_POST);		
		//printr($post);
		$insert_id = $obj_bank->addbankdetail($post);
		
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		$bank_id = $bank_detail['bank_detail_id'];
		$obj_bank->updatebankdetail($bank_id,$post);
		$obj_session->data['success'] = UPDATE;
		if(isset($obj_session->data['page'])){
			$pageString = '&page='.$obj_session->data['page'];
			unset($obj_session->data['page']);
		}else{
			$pageString = '';
		}
		page_redirect($obj_general->link($rout, $pageString.'&filter_edit='.$_GET['filter_edit'], '',1));
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
        
      <div class="col-sm-12">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Currency</label>
                <div class="col-lg-5">
                <?php $currency_list = $obj_bank->getCurrencyList(); ?> 
                  <select name="curr" id="curr" onchange="currency()" class="form-control validate[required]">
                  <option value=" " >Select Currency</option>
                  	<?php foreach($currency_list as $curry_list) { ?>
                    	<?php if(isset($bank_detail['curr_code']) && $bank_detail['curr_code']==$curry_list['currency_id']){ ?>
	                   	 	<option value="<?php echo $curry_list['currency_id']; ?>" selected="selected"><?php echo $curry_list['currency_code']; ?></option>
                         <?php } else { ?>
                         	<option value="<?php echo $curry_list['currency_id']; ?>"><?php echo $curry_list['currency_code']; ?></option>
                         <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Beneficiary Name</label>
                <div class="col-lg-6">
                  	<input type="text" name="bank_acnt" id="bankacnt" value="<?php echo isset($bank_detail['bank_accnt'])?$bank_detail['bank_accnt']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Account Number</label>
                <div class="col-lg-6">
                    <input type="text" name="accnt_num" id="accntnum" value="<?php echo isset($bank_detail['accnt_no'])?$bank_detail['accnt_no']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Beneficiary Address</label>
                <div class="col-lg-6">
                  	<textarea class="form-control validate[required]" id="beneadd" name="bene_add"><?php echo isset($bank_detail['benefry_add'])?$bank_detail['benefry_add']:'';?> </textarea>
                </div>
              </div>
              
              <!-- ruchi -->
              
              
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Beneficiary Bank Name</label>
                <div class="col-lg-6">
                    <input type="text" name="bene_bank_nm" id="benename" value="<?php echo isset($bank_detail['benefry_bank_name'])?$bank_detail['benefry_bank_name']:'';?>" class="form-control validate[required]">
                   
                   
                </div>
              </div>
              
                <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Branch Name</label>
                <div class="col-lg-6">
                    <input type="text" name="branch_nm" id="branch_nm" value="<?php echo isset($bank_detail['branch_nm'])?$bank_detail['branch_nm']:'';?>" class="form-control validate[required]">
                   
                   
                </div>
              </div> 
                <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Type Of Account</label>
                <div class="col-lg-6">
                    <input type="text" name="type_of_accnt" id="type_of_accnt" value="<?php echo isset($bank_detail['type_of_accnt'])?$bank_detail['type_of_accnt']:'';?>" class="form-control validate[required]">
                  </div>
              </div>
              
               <?php 
			 /* if(isset($bank_detail['curr_code']) &&  $bank_detail['curr_code']== '8')
			  {*/
				?>	
              <div class="form-group" id="bank_c">
                <label class="col-lg-3 control-label"><span class="required">*</span>Bank Code</label>
                <div class="col-lg-6">
                    <input type="text" name="bank_code" id="bank_code" value="<?php echo isset($bank_detail['bank_code'])?$bank_detail['bank_code']:'';?>" class="form-control validate[required]">
                  </div>
              </div>
                 
                 <div class="form-group" id="branch_c">
                <label class="col-lg-3 control-label"><span class="required">*</span>Branch Code</label>
                <div class="col-lg-6">
                    <input type="text" name="branch_code" id="branch_code" value="<?php echo isset($bank_detail['branch_code'])?$bank_detail['branch_code']:'';?>" class="form-control validate[required]">      
                   </div>
              </div> 
              <?php //} 
			 // else{ 
			 /*if(isset($bank_detail['curr_code']) &&  $bank_detail['curr_code']!= '8') 
			 {*/?>
              
               <div class="form-group" id="ifsc_div">
                <label class="col-lg-3 control-label"><?php //if(isset($bank_detail['curr_code']) && $bank_detail['curr_code']!='6') { ?><span class="required" id="ifsc_code">*</span><?php //} ?>IFSC Code</label>
                <div class="col-lg-6">
                    <input type="text" name="swift_cd_hsbc" id="swiftcode" value="<?php echo isset($bank_detail['swift_cd_hsbc'])?$bank_detail['swift_cd_hsbc']:'';?>" class="form-control validate[required]">
				 </div>
              </div>
              
              <div class="form-group" id="micr_div">
                <label class="col-lg-3 control-label"><?php //if(isset($bank_detail['curr_code']) && $bank_detail['curr_code']!='6') { ?><span class="required" id="micr_codes">*</span><?php //} ?>MICR Code</label>
                <div class="col-lg-6">
                    <input type="text" name="micr_code" id="micr_code" value="<?php echo isset($bank_detail['micr_code'])?$bank_detail['micr_code']:'';?>" class="form-control validate[required]">
                 </div>
              </div>
              
              <?php //} ?>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Beneficiary Bank Address</label>
                <div class="col-lg-6">
                   <textarea name="bebe_bank_add" id="benebankadd" class="form-control validate[required]" ><?php echo isset($bank_detail['benefry_bank_add'])?$bank_detail['benefry_bank_add']:'';?></textarea>
                </div>
              </div>
              <?php //printr($bank_detail['curr_code']);?>
              <div class="form-group" id="clabe_div" <?php if(isset($bank_detail['clabe']) && $bank_detail['clabe']=='0') { ?> style="display:none" <?php } ?>>
                <label class="col-lg-3 control-label"><span class="required">*</span> Clabe</label>
                <div class="col-lg-6">
                   <input type="text" name="clabe" id="clabe" value="<?php echo isset($bank_detail['clabe'])?$bank_detail['clabe']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group" id="bsb_div" <?php if(isset($bank_detail['bsb']) && ($bank_detail['bsb']=='0' || $bank_detail['bsb']=='') && $bank_detail['curr_code']!='6') { ?> style="display:none" <?php } ?>>
                <label class="col-lg-3 control-label"><span class="required">*</span> BSB</label>
                <div class="col-lg-6">
                   <input type="text" name="bsb" id="bsb" value="<?php echo isset($bank_detail['bsb'])?$bank_detail['bsb']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <?php //printr($bank_detail['curr_code']);?>
               <div class="form-group" id="swift_div" <?php if(isset($bank_detail['swift_code']) && ($bank_detail['swift_code']=='0' || $bank_detail['swift_code']=='') && ($bank_detail['curr_code']!='6' && $bank_detail['curr_code']!='2')) { ?>  <?php } ?>>
                <label class="col-lg-3 control-label"><span class="required">*</span> Swift Code</label>
                <div class="col-lg-6">
                   <input type="text" name="swift_code" id="swift_code" value="<?php echo isset($bank_detail['swift_code'])?$bank_detail['swift_code']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Intermediary Bank Name </label>
                <div class="col-lg-6">
                    <input type="text" name="inter_bank_nm" id="intername" value="<?php echo isset($bank_detail['intery_bank_name'])?$bank_detail['intery_bank_name']:'';?>" class="form-control">
                   
                   
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Intermediary Bank</label>
                <div class="col-lg-6">
                    <input type="text" name="hsbc_inter_bank" id="hsbcnum" value="<?php echo isset($bank_detail['hsbc_accnt_intery_bank'])?$bank_detail['hsbc_accnt_intery_bank']:'';?>" class="form-control">
                   
                   
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Swift Code Of Intermediary Bank</label>
                <div class="col-lg-6">
                    <input type="text" name="swfit_cd_bic" value="<?php echo isset($bank_detail['swift_cd_intery_bank'])?$bank_detail['swift_cd_intery_bank']:'';?>" class="form-control">
                   
                   
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Intermediary Bank ABA Routing Number</label>
                <div class="col-lg-6">
                    <input type="text" name="inter_bank_aba" id="abanum" value="<?php echo isset($bank_detail['intery_aba_rout_no'])?$bank_detail['intery_aba_rout_no']:'';?>" class="form-control">
                </div>
                <input type="hidden" name="edit_val" id="edit_val" value="<?php echo $edit;?>" />
                
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">This Bank Detail For The</label>
				<div class="col-lg-6">
					<script src="<?php echo HTTP_SERVER;?>js/select2.min.js"></script>
					<script src="<?php echo HTTP_SERVER;?>js/chosen.jquery.min.js"></script>
					<!--<link href="<?php //echo HTTP_SERVER;?>css/chosen.min.css" rel="stylesheet"/>-->
					<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
					
					<?php $userlist = $obj_bank->getIBUserList();//printr($userlist);
					      $users = array();
						if (isset($bank_detail) && !empty($bank_detail) && $bank_detail['for_user'])
						{
							$users = explode(',',$bank_detail['for_user']);
							
							echo '<input type="hidden" name="edit_user_data" id="edit_user_data" value="'.json_encode($users).'">';	
						}?>
					<select data-placeholder="Begin typing a name to filter..." multiple class="chosen-select form-control select2-container select2-container-multi" name="for_user[]">
						<option value=""></option>
						<?php foreach ($userlist as $user) { ?>
							<?php if (isset($bank_detail) && in_array($user['international_branch_id'],$users)) { ?>

								<option value="<?php echo $user['international_branch_id']; ?>" selected="selected"><?php echo $user['user_name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $user['international_branch_id']; ?>"><?php echo $user['user_name']; ?></option>
							<?php } ?>
						<?php } ?>                                       
					  </select>
				</div>
          </div>
          <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-6">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($bank_detail['status']) && $bank_detail['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($bank_detail['status']) && $bank_detail['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
              </div>
              
               <!-- ruchi -->
               
              <?php /* <div class="form-group">
                <label class="col-lg-3 control-label">Currency Symbol</label>
                <div class="col-lg-6">
                  	<select name="status" id="status" class="form-control">
                    	<option value="&#162;">&#162; / Cents sign</option>
                        <option value="&#163;">&#163; / Pound Sterling</option>
                        <option value="&#164;">&#164; / General currency</option>
                        <option value="&#165;">&#165; / Yen</option>
                        <option value="&#8364;">&#8364; / Euro</option>
                        <option value="&#8355;">&#8355; / Franc (French)</option>
                        <option value="">&#162; / Cents sign</option>
                        <option value="">&#163; / Pound Sterling</option>
                    </select>
                </div>
              </div> */ ?>
             
              
            <div class="form-group">
                <div class="col-lg-9 col-lg-offset-5">
                <?php if($edit){?>
                  	<button type="submit"  name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
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
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>



<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        //jQuery("#form").validationEngine();
		var edit=$("#edit_val").val();
		var data = $("#curr").val();
		//alert(data);
		if(edit=='1' && data=='8')
		{
			$("#ifsc_div").hide();
			$("#micr_div").hide();
		}
		else if(edit=='1' && data!='8')
		{
			$("#bank_c").hide();
			$("#branch_c").hide();
		}
		else
		{
			$("#ifsc_div").show();
			$("#micr_div").show();
			$("#bank_c").show();
			$("#branch_c").show();
		}
		
		if(data=='6' || data=='2')
		{
			$("#bsb_div").show();
			$("#swift_div").show();
			$("#ifsc_code").hide();
			$("#micr_codes").hide();
			//$("#swiftcode").
			$("#swiftcode").removeClass( "form-control validate[required]" ).addClass( "form-control validate" );
			$("#micr_code").removeClass( "form-control validate[required]" ).addClass( "form-control validate" );
		}
		else
		{
			$("#bsb_div").hide();
			$("#swift_div").hide();
			$("#ifsc_code").show();
			$("#micr_codes").show();
			$("#swiftcode").removeClass( "form-control validate" ).addClass( "form-control validate[required]" );
			$("#micr_code").removeClass( "form-control validate" ).addClass( "form-control validate[required]" );
		}
    });
	
	
	$('#bankacnt,#intername,#benename').keyup(function () {//#swiftcode,
			if (this.value.match(/[^a-zA-Z ]/g)) {
			//this.value = this.value.replace(/[^a-zA-Z]/g, ”);
			alert("Please Enter Only Characters");
			}
	});
	/*$('#beneadd').keyup(function () {
		alert("hii");
			if (this.value.match(/[^a-zA-Z0-9]/g)) {
			this.value = this.value.replace(/[^a-zA-Z0-9]/g, ”);
			alert(“Enter only alphanumeric characters.”)
			}
			});*/
		
	
	$("#accntnum,#hsbcnum").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && String.fromCharCode(e.which) != '-' && (e.which < 48 || e.which > 57)) {
        //display error message
		alert("Not Valide");
    //    $("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });
   
   $("#curr").change(function(){
   		var data = $("#curr").val();
		//alert(data);
		if(data=='10')
		{
   			$("#clabe_div").show();
		}
		else
		{
			$("#clabe_div").hide();
		}
		
		if(data=='6')
		{
			$("#bsb_div").show();
			$("#swift_div").show();
			$("#ifsc_code").hide();
			$("#micr_codes").hide();
			//$("#swiftcode").
			$("#swiftcode").removeClass( "form-control validate[required]" ).addClass( "form-control validate" );
			$("#micr_code").removeClass( "form-control validate[required]" ).addClass( "form-control validate" );
		}
		else
		{
			$("#bsb_div").hide();
			$("#swift_div").hide();
			$("#ifsc_code").show();
			$("#micr_codes").show();
			$("#swiftcode").removeClass( "form-control validate" ).addClass( "form-control validate[required]" );
			$("#micr_code").removeClass( "form-control validate" ).addClass( "form-control validate[required]" );
		}
		$("#clabe").val('0');
		$("#bsb").val('0');
		$("#swift_code").val('0');
		
		if(data=='8')
		{
		
		$("#ifsc_div").hide();
		$("#micr_div").hide();
		$("#bank_c").show();
		$("#branch_c").show();
		
		}
		else
		{
		
		$("#ifsc_div").show();
		$("#micr_div").show();
		$("#bank_c").hide();
		$("#branch_c").hide();

		}
		
	
   
   });
    $(".chosen-select").chosen({
		no_results_text: "Oops, nothing found!"
	});

	/*function Validate() {
        var mobile = document.getElementById("bankacnt").value;
		//alert(mobile);
        var pattern = /^(?:[a-z]+[0-9])[a-z0-9]*$/i;
        if (pattern.test(mobile)) {
            alert("Your mobile number : " + mobile);
            return true;
        }
        alert("It is not valid mobile number.input 10 digits number!");
        return false;
    }*/
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>