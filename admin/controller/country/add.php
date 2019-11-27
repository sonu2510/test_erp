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
if(isset($_GET['country_id']) && !empty($_GET['country_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$country_id = base64_decode($_GET['country_id']);
		$country = $obj_country->getCountry($country_id);
		//printr($country);
		$courier_data = $obj_country->getCouriers();
		//printr($courier_data);
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
		//printr($post);die;
		$insert_id = $obj_country->addCountry($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		
		$country_id = $country['country_id'];
		$obj_country->updateCountry($country_id,$post);
		//die;
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
        
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Country Name </label>
                <div class="col-lg-4">
                  	<input type="text" name="country_name" value="<?php echo isset($country['country_name'])?$country['country_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Country Code </label>
                <div class="col-lg-4">
                  	<input type="text" name="country_code" value="<?php echo isset($country['country_code'])?$country['country_code']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <!-- ruchi -->
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Currency Code</label>
                <div class="col-lg-4">
                    <input type="text" name="currency_code" value="<?php echo isset($country['currency_code'])?$country['currency_code']:'';?>" class="form-control validate[required]">
                   
                   
                </div>
              </div>
           <!-- <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Currency</label>
                <div class="col-lg-4">
                	<select name="currency_id" id="status" class="form-control">
                    <?php /*
						$currency_list = $obj_country->getCurrencyList();
						foreach($currency_list as $currency_detail){*/
					?>	
                    	<?php //if(isset($country['currency_id']) && $country['currency_id']==$currency_detail['currency_id']) { ?>
                    		<option value="<?php //echo $currency_detail['currency_id']; ?>" selected="selected"><?php //echo $currency_detail['currency_code']; ?></option>
                        <?php //} else { ?>
                        	<option value="<?php //echo $currency_detail['currency_id']; ?>"><?php //echo $currency_detail['currency_code']; ?></option>
                        <?php //} ?>
                    <?php //} ?>
                    </select>
                </div>
              </div>-->
              
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
             
              <input type="hidden" name="courier" value="<?php echo isset($country['default_courier_id']) ? $country['default_courier_id'] : '';?>" />
              <div class="form-group">
                <label class="col-lg-3 control-label">Courier</label>
                <div class="col-lg-4">
                  <select name="courier" id="status" class="form-control" <?php if(isset($country['default_courier_id'])) { echo "disabled=disabled"; }?> >
                  	<?php foreach($courier_data as $courier_name) { ?>
                    	<?php if($country['default_courier_id']==$courier_name['courier_id']) { ?>
	                   	 	<option value="<?php echo $courier_name['courier_id']; ?>" selected="selected"><?php echo $courier_name['courier_name']; ?></option>
                         <?php } else { ?>
                         	<option value="<?php echo $courier_name['courier_id']; ?>"><?php echo $courier_name['courier_name']; ?></option>
                         <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div> 
              
             <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($country['status']) && $country['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($country['status']) && $country['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Tax</label>
                <div class="col-lg-4">
                  <input type="text" class="form-control" name="tax" value="<?php echo isset($country['tax']) ? $country['tax'] : ''; ?> " />
                </div>
              </div>  
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Foreign Port(days)</label>
                <div class="col-lg-4">
                  <input type="text" class="form-control" name="foreign_port" value="<?php echo isset($country['foreign_port']) ? $country['foreign_port'] : ''; ?> " />
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
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
    });
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>