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

if(isset($_GET['color_id']) && !empty($_GET['color_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$color_id = base64_decode($_GET['color_id']);
		$color = $obj_color->getColor($color_id);
		//printr($color);
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
		$insert_id = $obj_color->addColor($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		//$printr($color['pouch_color_id']);die;
		$color_id = $color['pouch_color_id'];
		$obj_color->updateColor($color_id,$post);
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
	       <?php include("common/breadcrumb.php");		?>	
        </div> 
     
      <div class="col-sm-12">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
            <!-- manirul 11-2-17 -->
              <div class="row">
              <div class="col-lg-8" > 	
              <div class="form-group">
                <label class="col-lg-3 control-label">Color Category</label>
                <div class="col-lg-4">
                <?php
								
                   $color_category = $obj_color->color_category();   
				  ?>     
                  <select name="color_category" id="color_category" class="form-control validate">    
                   
                   <option value="">Select Color Category</option>
                               <?php  
								 foreach($color_category as $c){                                  		
                                        if(isset($color['color_category']) && $color['color_category'] == $c['color_catagory_id']){
                                            echo '<option value="'.$c['color_catagory_id'].'" selected="selected" >'.$c['color_name'].'</option>';
                                        }else{
                                            echo '<option value="'.$c['color_catagory_id'].'">'.$c['color_name'].'</option>';
                                        }
                                    } ?>
                                </select>
                   </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Pouch Color</label>
                <div class="col-lg-8">
             
                  	 <textarea name="email_color" id="email_color" class="form-control"><?php echo isset($color['email_color'])?$color['email_color']:'';?></textarea>
                      <input type="hidden" name="color" id="color" class="form-control" value="<?php echo isset($color['color'])?$color['color']:'';?>">
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Abbreviation</label>
                <div class="col-lg-8">
                  	<input type="text" name="abbrevation" id="abbrevation" value="<?php echo isset($color['pouch_color_abbr'])?$color['pouch_color_abbr']:'';?>" class=  "form-control validate[required]">
                </div>
              </div>
              
              <!--<div class="form-group">
                <label class="col-lg-3 control-label">Pouch Color</label>
                <div class="col-lg-8">
                <input type="text" name="pouch_color" placeholder="Pouch Color" value="<?php echo isset($color['color'])?$color['color']:'';?>" class="form-control validate[required]">
                
                </div>
              </div>--> 
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Color Value</label>
                <div class="col-lg-8">
                  	<input type="text" name="colorvalue" id="colorvalue" value="<?php echo isset($color['color_value'])?$color['color_value']:'';?>" class=  "form-control validate[required]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($color['status']) && $color['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($color['status']) && $color['status'] == 0)?'selected':'';?>> Inactive</option>
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
              </div>
              
            <!--  sonu add 11-5-2017-->
              <div class="col-lg-3" >              
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Product</label>
                <div class="col-lg-8">
              
                   
                	<div class="form-control scrollbar scroll-y" style="height:250px;width: 350px;" id="groupbox">
                <?php
					$selected = array();				
                   $Products = $obj_color->getProduct(); 
				     
				 //printr($Products);
				   if(isset($color['product_id'])&& !empty($color['product_id']) ){
					    $p_id = explode(',',$color['product_id']);
						
				   }
				   
				
					
			
				  ?>     
                
				   <?php  
                     foreach($Products as $Product){
                        
                      
                        echo '<div class="checkbox">';
                            echo '<label class="checkbox-custom">';
                            
                                if(in_array($Product['product_id'],$p_id)){
                                    echo '<input type="checkbox" name="product[]" id="'.$Product['product_id'].'" value="'.$Product['product_id'] .'" checked="checked" class="form-control "> ';
                                    }else{
                                        echo '<input type="checkbox" name="product[]" id="'.$Product['product_id'].'" value="'.$Product['product_id'] .'"  class="form-control "> ';
                                    }
                        
                            echo '<i class="fa fa-square-o"></i> '.$Product['product_name'].' </label>';
                        echo '</div>';
                    }
                    ?>
                                </select>
                   </div>
              </div>
              </div>
              </div>
                <!--  sonu end 11-5-2017-->
                
                <!--  sejal add 13-5-2017-->
              <div class="col-lg-3" >              
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Make Pouch</label>
                <div class="col-lg-8">
              
                   
                	<div class="form-control scrollbar scroll-y" style="height:180px;width: 350px;" id="groupbox">
                <?php
					$selected = array();				
                  
				   $Makepouch = $obj_color->getActiveMake();  
				   
				  //printr( $Makepouch);
				   if(isset($color['make_id'])&& !empty($color['make_id']) ){
					    $make_pouch = explode(',',$color['make_id']);
						//printr($make_pouch);die;
				   }
				   
			
				  ?>     
                
				   <?php  
                     foreach($Makepouch as $mp){
                       
                        echo '<div class="checkbox">';
                            echo '<label class="checkbox-custom">';
                            
                                if(in_array($mp['make_id'],$make_pouch)){
								
                                    echo '<input type="checkbox" name="make[]" id="'.$mp['make_id'].'" value="'.$mp['make_id'] .'" checked="checked" class="form-control"> ';
                                    }else{
                                        echo '<input type="checkbox" name="make[]" id="'.$mp['make_id'].'" value="'.$mp['make_id'] .'"  class="form-control "> ';
                                    }
                        
                            echo '<i class="fa fa-square-o"></i> '.$mp['make_name'].' </label>';
                        echo '</div>';
                    }
                    ?>
                                </select>
                   </div>
              </div>
              </div>
              </div>
                <!--  sejal end 13-5-2017-->

                
                
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
<script src="<?php echo HTTP_SERVER;?>ckeditor3/ckeditor.js"></script>

<script>
    jQuery(document).ready(function(){
		var editor = CKEDITOR.replace( 'email_color', {
			height: '65px',
			removePlugins: 'elementspath',
			resize_enabled: false,
 			toolbar: [ { name: 'colors', items: [ 'TextColor' ] },]}
);
	//CKEDITOR.replace( 'email_color');
		for (var i in CKEDITOR.instances) {
        CKEDITOR.instances[i].on('change', function() {
			//alert(CKEDITOR.instances[i].val());
			var value = CKEDITOR.instances['email_color'].getData();
			$('#color').val(value);
			});
    }
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();		
	
	
		/*$(".selectall").click(function(){
			alert($(this).parent().children().attr('class'));
			//$('#container i:has(.fa-square-o)').addClass('checked');
			/*var parentClass = $(this).parent().children().attr('class');
			alert(parentClass);
			$(+ parentClass + 'i:has(.fa-square-o)').addClass('checked');
			//$(this).parent().children().find('fa-square-o').addClass('checked');
		});*/
    });
	
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>