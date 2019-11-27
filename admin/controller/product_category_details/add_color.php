<?php
include("mode_setting.php");
$edit = '';
if(isset($_GET['catalogue_category_id']) && !empty($_GET['catalogue_category_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		
		$catalogue_category_id= base64_decode($_GET['catalogue_category_id']);
    	$color = $obj_catalogue_category->getCatalogue_categoryDetails($catalogue_category_id);	
    	$category_color_details = $obj_catalogue_category->getCategoryColorDetails($catalogue_category_id);	
    	$product_id=$color['product_id'];
		$edit = 0;
	//	printr($category_color_details);
	} 
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	} 
}

if(!empty($category_color_details)){
    

   	$edit = 1; 
}
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

 
if($display_status){ 
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST); 
		
	//	printr($post);die;
		$insert_id = $obj_catalogue_category->addcatalogue_category_color($post,$catalogue_category_id);  
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	if(isset($_POST['btn_update'])){
		$post = post($_POST);
		//	printr($post);die;
		$insert_id = $obj_catalogue_category->Updatecatalogue_category_color($post,$catalogue_category_id);
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
              <div class="col-lg-12" > 	
            <table id="MyStretchGrid" class="table table-striped b-t text-small">
                 	<tr>
                 	   <center> <header class="panel-heading bg bg-inverse"> <?php echo $color['catalogue_category_name'];?> </header></center>
                 	 
                	</tr>	
        	<?php   $selected_color = array();
                     if(isset($color['color']) && $color['color']){
                        $selected_color=explode(",",$color['color']);
                     }
                     $selected_size = array();
                     
                     if(isset($color['size_master_id']) && $color['size_master_id']){
                         
                        $selected_size=explode(",",$color['size_master_id']);
                     }
                     
                     ?>
                     
                     
               	<tr>
               	     <th>No</th>
               	     <th>Color Name</th>
               	    <?php foreach($selected_size as $size){
               	         $size_volume=$obj_catalogue_category->getSizeData($size);
               	        echo'<th>'.$size_volume['volume'].'<br>'.$size_volume['zipper_name'].'</th>';
               	    }?>
               	    <th>Select All</th>
               	</tr>
               	 <?php 
               	  $i=1;
               	 foreach($selected_color as $color){
               	    $color_name=$obj_catalogue_category->color_name($color);
               	 //  printr($category_color_details);
               	 ?>
               	        <tr>  
               	            <td><?php echo $i;?></td>
               	            <td><?php echo $color_name;?></td> 
               	               <?php 
               	               
               	               foreach($selected_size as $size){
               	                     $size_volume=$obj_catalogue_category->getSizeData($size);
               	                     $category_color_details = $obj_catalogue_category->getCategoryColorDetails($catalogue_category_id,$size,$color);	
               	                         $sel_color = array();								  
                                            if(isset($category_color_details['color']) && $category_color_details['color']){
                                                $sel_color=explode(",",$category_color_details['color']);
                                            }
               	                        $check='';
               	                        if(($size ==$category_color_details['size_master_id']) && (in_array($color,$sel_color))){
               	                           
               	                               $check='checked="checked"';
               	                        }
               	                     ?> 
               	                       <td>
               	                           <input type="checkbox" name="color[<?php echo $size.'=='.$category_color_details['catalogue_category_color_id'];?>][]" id="<?php echo $size; ?>" value="<?php echo $color; ?>" <?php echo $check;?> class="check_<?php echo $color; ?>"> </td>
               	         <?php    }?> 
               	         
               	                <td><input type="checkbox"  id="selectall_<?php echo $color; ?>" onclick="selecctall(<?php echo $color;?>)"  class="selecctall(<?php echo $color; ?>)"> </td>
               	       
               	        </tr>
               	        
               	        
               	       
               	  <?php  $i++;  }?>
               	</table>
             	   
              
         
         
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                <?php if($edit){?>
                  	<button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                <?php } else { ?>
                	<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
                <?php } ?>  
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout,'', '',1);?>">Cancel</a>
                </div>
              </div>
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
function  get_size(){
var product_id = $('#product').val(); 
	//var size_id = $('#size').val();
	var size_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getProductSize', '',1);?>");
	$.ajax({
		type: "POST",
		url: size_url,					
		data:{product_id:product_id},
		success: function(json) {
			if(json){
				$("#size_div").html(json);
				//if(product_id==16)
				if(product_id==10)
				{
					$("#size option[value='0']").prop('disabled',true);
				}
				if(product_id=='1' || product_id=='5')
				{
					$("#sealing_div").css("display","block");
				}else{
				    $("#sealing_div").css("display","none");
				}
				/*if((zip_id==5 || zip_id==6 || zip_id==7) && (product_id==1 || product_id==7))
				{
					$("#size option[value='0']").hide();
				}*/
				//$("#").addClass('asdasd');
				
			}
			$("#loading").hide();
		}
	});
}
/*
function toggleCheckbox(element){
 //alert(element.checked); 
 //alert(element.value);
 if(element.checked == true){
     
     jQuery('#cust_checkbox_'+element.value+'').addClass('checked');
 }else if(element.checked == false){
     jQuery('#cust_checkbox_'+element.value+'').removeClass('checked');
    
 }
}*/

</script>
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>