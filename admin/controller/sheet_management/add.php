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
if(isset($_GET['sheet_id']) && !empty($_GET['sheet_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$sheet_id = base64_decode($_GET['sheet_id']);
		$sheet_data = $obj_sheet->getSheetData($sheet_id);
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
		$insert_id = $obj_sheet->addSheet($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$sheet_id = $sheet_data['sheet_id'];
		$obj_sheet->updateSheet($sheet_id,$post);
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
        
      <div class="col-sm-12">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Sticker Sheet Name</label>
                <div class="col-lg-4">
                  	<input type="text" name="name" id="name" value="<?php echo isset($sheet_data['sheet_name'])?$sheet_data['sheet_name']:'';?>" placeholder="Sticker Sheet Name" class="form-control validate[required]">
                </div>
              </div>
             
             <div class="form-group">
                <label class="col-lg-3 control-label">Size (Dimension) </label>
                <div class="col-lg-1"><input type="text" name="width" value="<?php echo isset($sheet_data['width'])?$sheet_data['width']:'';?>" placeholder="Width" class="form-control validate[required]"></div>
                <div class="col-lg-1"><b>Width</b></div>
                <div class="col-lg-1"><b>X</b></div>
                <div class="col-lg-1"><input type="text" name="height" value="<?php echo isset($sheet_data['height'])?$sheet_data['height']:'';?>" placeholder="Width" class="form-control validate[required]"></div>
                <div class="col-lg-1"><b>Height</b></div>
            </div>
             
             
               <div class="form-group">
                <label class="col-lg-3 control-label">Price (Rs)</label>
                <div class="col-lg-3">
                  	<input type="text" name="price" value="<?php echo isset($sheet_data['price'])?$sheet_data['price']:'';?>" placeholder="Price" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group" style="display:none;">
                <label class="col-lg-3 control-label">Printing Cost (Rs)</label>
                <div class="col-lg-3">
                  	<input type="text" name="printing_cost" value="<?php echo isset($sheet_data['printing_cost'])?$sheet_data['printing_cost']:'';?>" placeholder="Printing Cost" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Wastage (%)<br/><small>This will count on printing cost.</small></label>
                <div class="col-lg-3">
                  	<input type="text" name="wastage" value="<?php echo isset($sheet_data['wastage'])?$sheet_data['wastage']:'';?>" placeholder="Wastage (%)" class="form-control validate[required]">
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Weight (Kg)</label>
                <div class="col-lg-3">
                  	<input type="text" name="weight" value="<?php echo isset($sheet_data['weight'])?$sheet_data['weight']:'';?>" placeholder="Weight" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Margin for header (mm)</label>
                <div class="col-lg-3">
                  	<input type="text" name="header_margin" value="<?php echo isset($sheet_data['header_margin'])?$sheet_data['header_margin']:'';?>" placeholder="Margin for header" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Margin for left (mm)</label>
                <div class="col-lg-3">
                  	<input type="text" name="left_margin" value="<?php echo isset($sheet_data['left_margin'])?$sheet_data['left_margin']:'';?>" placeholder="Margin for left" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Margin for right (mm)</label>
                <div class="col-lg-3">
                  	<input type="text" name="right_margin" value="<?php echo isset($sheet_data['right_margin'])?$sheet_data['right_margin']:'';?>" placeholder="Margin for right" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Margin for footer (mm)</label>
                <div class="col-lg-3">
                  	<input type="text" name="footer_margin" value="<?php echo isset($sheet_data['footer_margin'])?$sheet_data['footer_margin']:'';?>" placeholder="Margin for footer" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Margin between two stickers</label>
                <div class="col-lg-3">
                  	<input type="text" name="between_stickers" value="<?php echo isset($sheet_data['between_stickers'])?$sheet_data['between_stickers']:'';?>" placeholder="Margin between two stickers" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Make of pouch</label>
                <div class="col-lg-3">
                  	<?php $getmake=$obj_sheet->getMake();
                  	       $make_pouch =array();
                  	      if(isset($sheet_data['make_pouch'])&& !empty($sheet_data['make_pouch']) ){
        					    $make_pouch = explode(',',$sheet_data['make_pouch']);
        				   }
                  	        foreach($getmake as $make){?>
                              	<div class="checkbox">
                                  <label class="checkbox-custom">
                                    <input type="checkbox" name="make_pouch[]" value="<?php echo $make['make_id'];?>" <?php echo (in_array($make['make_id'],$make_pouch))?'checked="checked"':'';?> class="validate[minCheckbox[1]]">
                                    <i class="fa fa-square-o"></i><?php echo $make['make_name'];?></label>
                                </div>
                        <?php } ?>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Printing effect</label>
                <div class="col-lg-3">
            	<?php $printing=$obj_sheet->getLabelPrintingEffect();
            	    $effects =array();
          	        if(isset($sheet_data['printing_effect'])&& !empty($sheet_data['printing_effect']) ){
					    $effects = explode(',',$sheet_data['printing_effect']);
				    }
          	        foreach($printing as $print){?>
                  	<div class="checkbox">
                      <label class="checkbox-custom">
                        <input type="checkbox" name="printing_effect[]" value="<?php echo $print['printing_effect_id'];?>" <?php echo (in_array($print['printing_effect_id'],$effects))?'checked="checked"':'';?> class="validate[minCheckbox[1]]">
                        <i class="fa fa-square-o"></i><?php echo $print['effect_name'];?></label>
                    </div>
            <?php } ?>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Qty</label>
                <div class="col-lg-5">
            	<?php $qtys=$obj_sheet->getLabelQty();
            	    $quantity =array();
          	        if(isset($sheet_data['qty'])&& !empty($sheet_data['qty']) ){
					    $quantity = explode(',',$sheet_data['qty']);
				    }
          	        foreach($qtys as $qty){?>
                  	<div class="checkbox" style="float: left; width: 30%;">
                      <label class="checkbox-custom">
                        <input type="checkbox" name="qty[]" value="<?php echo $qty['label_quantity_id'];?>" <?php echo (in_array($qty['label_quantity_id'],$quantity))?'checked="checked"':'';?> class="validate[minCheckbox[1]]">
                        <i class="fa fa-square-o"></i><?php echo $qty['quantity'];?></label>
                    </div>
            <?php } ?>
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