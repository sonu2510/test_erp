<?php
include("mode_setting.php");
$edit = '';

/*if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$product_id = base64_decode($_GET['product_id']);
	}
	
}else*/  
if(isset($_GET['catalogue_category_id']) && !empty($_GET['catalogue_category_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		 
		$catalogue_category_id= base64_decode($_GET['catalogue_category_id']);
    	$color = $obj_catalogue_category->getCatalogue_categoryDetails($catalogue_category_id);	
    	$product_id=$color['product_id'];
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	} 
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
		$insert_id = $obj_catalogue_category->addcatalogue_category($post);  
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	if(isset($_POST['btn_update'])){
		$post = post($_POST);
		$insert_id = $obj_catalogue_category->Updatecatalogue_category($post,$catalogue_category_id);
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
                <label class="col-lg-3 control-label"><span class="required">*</span> Catalogue Category Name </label>
                <div class="col-lg-8">
                  	<input type="text" name="catalogue_category_name" id="catalogue_category_name" value="<?php echo isset($color['catalogue_category_name'])?$color['catalogue_category_name']:'';?>" class=  "form-control validate[required]">
                </div>
              </div>
                 <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Product</label>
                        <div class="col-lg-6">
                            <?php
                            $products = $obj_catalogue_category->getActiveProduct();
                            ?>
                            <select name="product" id="product"  onchange="get_size()" class="form-control validate[required]">
                            <option value="">Select Product</option>
                                <?php
								
                                foreach($products as $product){
                                    if(isset($color['product_id']) && $color['product_id'] == $product['product_id']){
                                        echo '<option value="'.$product['product_id'].'" selected="selected" >'.$product['product_name'].'</option>';
                                    }else{
                                        echo '<option value="'.$product['product_id'].'">'.$product['product_name'].'</option>';
                                    }
                                } ?>
                            </select>
                        </div>
                      </div>
                       
             	   <div class="form-group" id="size_div">
             	   
             	        <?php if(isset($catalogue_category_id)) {
             	              	echo' <label class="col-lg-3 control-label">Size </label>';
                                	echo' <div class="col-lg-9" >';
                                    	echo'<div class="form-control scrollbar scroll-y" style="height:200px" id="groupbox">';
                    						 $selected = array();								  
                                            if(isset($color['size_master_id']) && $color['size_master_id']){
                                                $selected=explode(",",$color['size_master_id']);
                                            }
                    						$data = $obj_catalogue_category->getProductSize($color['product_id']);
                                            foreach($data as $item){ 
                    								echo'<div class="checkbox" >'; 
                    									echo'<label class="checkbox-custom" >';
                    								if(in_array($item['size_master_id'],$selected)){
                    										echo'<input type="checkbox" checked="checked" name="size_master_id[]" id="'.$item['size_master_id'].'" value="'.$item['size_master_id'].'" onchange="toggleCheckbox(this)"> ';
                    								}else{
                    										echo'<input type="checkbox" name="size_master_id[]" id="'.$item['size_master_id'].'" value="'.$item['size_master_id'].'" onchange="toggleCheckbox(this)"> ';
                    								}
                    								echo'<i class="fa fa-square-o" id="cust_checkbox_'.$item['size_master_id'].'"></i> '.$item['volume'].'['.$item['zipper_name'].']['.$item['width'].'X'.$item['height'].'X'.$item['gusset'].'] </label>';
                    							echo'</div>';
                    						}
                                         	echo'</div>';
                                         	echo' <a class="btn btn-default btn-xs selectall mt5" href="javascript:void(0);">Select All</a>';
                                          	echo'  <a class="btn btn-default btn-xs unselectall mt5" href="javascript:void(0);">Unselect All</a>';    
                                        //  echo' </div>';
             	
                        }
                        ?>
                       
                      </div>
                      
                      <?php //printr('dfhhaerher');?>
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Color Category</label>
                <div class="col-lg-4">
                <?php
								
                   $color_category = $obj_catalogue_category->color_category();   
				  ?>     
                  <select name="color_catagory_id" id="color_catagory_id" onchange="getColor()"class="form-control validate[required]">    
                    
                   <option value="">Select Color Category</option>
                               <?php  
								 foreach($color_category as $c){                                  		
                                        if(isset($color['color_catagory_id']) && $color['color_catagory_id'] == $c['color_catagory_id']){
                                            echo '<option value="'.$c['color_catagory_id'].'" selected="selected" >'.$c['color_name'].'</option>';
                                        }else{
                                            echo '<option value="'.$c['color_catagory_id'].'">'.$c['color_name'].'</option>';
                                        }
                                    } ?>
                                </select>
                   </div>
              </div>
                <div class="form-group" id="color_div">
                      <?php 
                   
                      if(isset($catalogue_category_id)) {
                          //   printr($catalogue_category_id);
                            $html .=' <div class="form-group option">';
                                 $html .='    <label class="col-lg-3 control-label">Color</label>';
                                 $html .='    <div class="col-lg-9">';
                                     	 $selected_color = array();								  
                                            if(isset($color['color']) && $color['color']){
                                                $selected_color=explode(",",$color['color']);
                                            }
                                            
                                         //   printr($selected_color);
                                       $spoutsTxt = '';
                					   $i=0;
                					    $color_data = $obj_catalogue_category->getCategoryColor($color['color_catagory_id']);
                                        foreach($color_data as $color1){
                							
                							//die;
                                           $spoutsTxt .= '<div style="float:left;width: 150px;">';
                                                $spoutsTxt .= '<label  style="font-weight: normal;">';
                								if(in_array($color1['pouch_color_id'],$selected_color))
                								{
                                                    $spoutsTxt .= '<input type="checkbox" id="color'.$i.'" name="color[]" value="'.$color1['pouch_color_id'].'" checked="checked" class="colortemp" >';
                								}
                								else 
                								{
                									$spoutsTxt .= '<input type="checkbox" id="color'.$i.'" name="color[]" value="'.$color1['pouch_color_id'].'" class="colortemp" >';
                								}
                                                $spoutsTxt .= ''.$color1['color'].'</label>';
                                            $spoutsTxt .= '</div>';
                							$i++;
                                        }
                                       $html .= $spoutsTxt;
                                       
                                    $html .=' </div>'; 
                                      $html .=' <div class="form-group option">';
                                        $html .='    <label class="col-lg-3 control-label"></label>';
                                        $html .='    <div class="col-lg-9">';
                                        $html .='<a  id="btn-all-check" class="label bg-success selectall1 mt5"  >Select All</a>';
                                         $html .='<a id="btn-all-uncheck" class="label bg-warning unselectall1 mt5" >Unselect All</a>';
                                         
                               $html .='  </div>';
                               $html .='  </div>';
                               $html .='  </div>';
                      	  echo $html;
                          
                      }?> 
                    
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
			}
			$("#loading").hide();
		}
	});
}
function  getColor(){
var color_catagory_id = $('#color_catagory_id').val(); 

//alert(color_catagory_id);
	var color_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getCategoryColor', '',1);?>");
	$.ajax({
		type: "POST",
		url: color_url,					
		data:{color_catagory_id:color_catagory_id},
		success: function(json) {
			if(json){
				$("#color_div").html(json);
			
				
			}
		
		}
	});
}
$(document).on('click','.selectall',function(){
   	            $(this).parent().children().find('i').addClass('checked');
				$(this).parent().children().find(':checkbox').attr('checked', true);
})
$(document).on('click','.unselectall',function(){
                $(this).parent().children().find('i').removeClass('checked');
				$(this).parent().children().find(':checkbox').attr('checked', false);
})


/*$(document).on('click','.checkbox',function(){
   // alert('222');
    var id=$(this).children().find('input:checkbox').val();
   	      if($(this).parent().children().find('i').hasClass('checked') == false){
                       //$('#cust_checkbox_'+id+'').addClass('checked');
                       //jQuery('#cust_checkbox_'+id+'').addClass('checked');
                       //alert(document.getElementById('cust_checkbox_'+id+''));
                       document.getElementById('cust_checkbox_641').classList.add('checked');
                       document.getElementById('cust_checkbox_642').classList.add('checked');
				      //console.log(document.getElementById('cust_checkbox_'+id+'').classList.add('checked'));   
                }
    
})
$(document).on('click','.checkbox',function(){
    var id=$(this).children().find('input:checkbox').val();
    //alert('123');
   	      if($(this).parent().children().find('i').hasClass('checked') == true){
                       //$('#cust_checkbox_'+id+'').addClass('checked');
                       //jQuery('#cust_checkbox_'+id+'').addClass('checked');
                       //alert(document.getElementById('cust_checkbox_'+id+''));
                       document.getElementById('cust_checkbox_641').classList.remove('checked');
                       document.getElementById('cust_checkbox_642').classList.remove('checked');
				      //console.log(document.getElementById('cust_checkbox_'+id+'').classList.add('checked'));   
                }
    
})*/
$(document).on('click','.selectall1',function(){
    $('.colortemp').prop('checked',true)
})
$(document).on('click','.unselectall1',function(){
     $('.colortemp').prop('checked',false)           
})

function toggleCheckbox(element){
 if(element.checked == true){
     jQuery('#cust_checkbox_'+element.value+'').addClass('checked');
 }else if(element.checked == false){
     jQuery('#cust_checkbox_'+element.value+'').removeClass('checked');
    
 }
}

</script>
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>