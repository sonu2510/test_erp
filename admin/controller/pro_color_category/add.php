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
//kavita:10-2-2017
if(isset($_GET['color']) && !empty($_GET['color'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$color= base64_decode($_GET['color']);
		$colors= $obj_color->getColor_detail($color);
		//printr($colors);
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
	if(isset($_POST['btn_edit']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$color_name= $colors['color_catagory_id'];
		$obj_color->updateColor($color_name,$post);
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
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label">color Catagory</label>
                <div class="col-lg-8">
                <input type="text" name="color" placeholder="Catagory" value="<?php echo isset($colors['color_name'])?$colors['color_name']:'';?>" class="form-control validate[required]" />
                     </div>
              </div>
             
            <div class="form-group">
                <label class="col-lg-3 control-label">Select Color</label>
                <div class="col-lg-8">
                	<div class="form-control scrollbar scroll-y" style="height:200px" id="groupbox">
                        <?php
						$clrs = $obj_color->getAllColor();
						$selected = array();
						 							  
						 if($edit)
						 {	
							$selected = explode(',',$colors['color']); 
						 }
							
                        foreach($clrs as $clr){
							echo '<div class="checkbox">';
								echo '<label class="checkbox-custom">';
								if(in_array($clr['pouch_color_id'],$selected)){
										echo '<input type="checkbox" checked="checked" name="pouch_color[]" id="'.$clr['pouch_color_id'].'" value="'.$clr['pouch_color_id'].'"> ';
								}else{
									echo '<input type="checkbox" name="pouch_color[]" id="'.$clr['pouch_color_id'].'" value="'.$clr['pouch_color_id'].'"> ';
								}
								echo '<i class="fa fa-square-o"></i> '.$clr['color'].' </label>';
							echo '</div>';
						}
						?>
                    </div>
                    <a class="btn btn-default btn-xs selectall mt5" onclick="javascript:checkall('form', true)">Select All</a>
                    <a class="btn btn-default btn-xs unselectall mt5" onclick="javascript:uncheckall('form', true)">Unselect All</a>    
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($colors['status']) && $colors['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($colors['status']) && $colors['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
            		<?php
					if($edit)
					{?>
						<button type="submit" name="btn_edit" id="btn_edit" class="btn btn-primary">Update</button>	
					<?php }else{?>
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
	
	
		$(".selectall").click(function(){
			alert($(this).parent().children().attr('class'));
			/*//$('#container i:has(.fa-square-o)').addClass('checked');
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