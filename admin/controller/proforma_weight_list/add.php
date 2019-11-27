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
if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$product_id= base64_decode($_GET['product_id']);
		$catogery_detail= $obj_weight->getProductCategory($product_id);	
	
		$catogory= explode(',',$catogery_detail['category']);
		//printr($weight_details); 	
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
		$product_id = $post['product_id'];
		//printr($product_id);
		$obj_weight->addWeight($product_id,$post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
	//	printr($post);die;
		$product_id= base64_decode($_GET['product_id']);
		//printr($product_id);
		$obj_weight->updateWeight($product_id,$post);
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
     
      <div class="col-sm-10">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
       <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
           
              <div class="form-group">
                <label class="col-lg-3 control-label">Product Name</label>
                <div class="col-lg-9">
                  <label class="control-label normal-font"> <?php echo $catogery_detail['product_name']; ?> </label>
                </div>
              </div>
            	<?php  
        		 foreach($catogory as $cat)
                           {
                     $details=$obj_weight->Allcatogery_detail($cat);
                     	$weight_details = $obj_weight->getProductWeightDetails($product_id,$cat);
                           	?>	
          
          
           <div class="col-lg-9">
           	 	 <table border="0"  width="100%" class="table  b-t text-small" >
           	 	 	<tbody>
              <tr>
              	<td>
                  <input type="text" name="details[catogory_name]" readonly="readonly" class="form-control" value="<?php echo $details['color_name'];?>">
                  <input type="hidden" name="details[catogory_id]" value="<?php echo $details['color_catagory_id'];?>">
                  <input type="hidden" id="product_id" name="details[product_id]" value="<?php echo $product_id;?>">
	            </td>
	            <td>
	            	
	            		<?php  $id=$details['color_catagory_id'];?>

         	  <table border="0"  width="100%" class="table  b-t text-small"  id="myTable_<?php echo $details['color_catagory_id'];?>" class="myTable_<?php echo $details['color_catagory_id'];?>" >
               <thead>
                <th ><b>Zipper</b> </th>
             
 				  <th><b>Size</b> </th>
                  <th><b>Weight</b></th>                         
                  <th></th>
               </thead>
                <tbody>
          
                 	
                  	<?php 

								 if(!empty($weight_details))
								 {
									 $weight_details =$weight_details;
								 }
								 else
								 {
									$weight_details[]= array(
											'weight_id' => '',
											'product_id' => '',
											'zipper_id' => '',
											'size' => '',										
											'weight' => '',
										
								);
							}
                  	 $inner_count=0;
                  	 if($weight_details){
							   		$inner_count = 0;
							   		foreach($weight_details as $weight)
							   		{
										
										?>
									
 					 <tr class="multiplerows_<?php echo $details['color_catagory_id'];?>-<?php echo $inner_count; ?> " id="multiplerows_<?php echo $details['color_catagory_id'];?>-<?php echo $inner_count; ?> " > 
                        <input type="hidden" name="catogory[<?php echo $details['color_catagory_id'];?>][<?php echo $inner_count; ?>][weight_id]" value="<?php echo isset($weight['weight_id'])?$weight['weight_id']:'';?>" />
                        <input type="hidden" name="catogory[<?php echo $details['color_catagory_id'];?>][<?php echo $inner_count; ?>][category]" value="<?php echo $details['color_catagory_id'];?>" />

                     		<?php $size_details = $obj_weight->getSize($product_id);
                     			 $zipper = $obj_weight->getZipper();
                     			
                     		?>
                      		
                    
                        <td  width="auto"> 
                       
                          <select name="catogory[<?php echo $details['color_catagory_id'];?>][<?php echo $inner_count; ?>][product_zipper_id]" class="form-control " >
                        	<option value="">Select Zipper</option>
                        		<?php 
                        		 foreach($zipper as $zip)
                                                       { ?>	
                                                       	<option value="<?php echo $zip['product_zipper_id'];?>" <?php if(isset($weight['zipper_id'])&&($weight['zipper_id']==$zip['product_zipper_id'])){?> selected="selected" <?php }?>><?php echo $zip['zipper_name'];?></option>
                                            <?php }
                                            ?>


                        </select>
                        </td> 
                  
                         <td > 
                         		
                         		 <select name="catogory[<?php echo $details['color_catagory_id'];?>][<?php echo $inner_count; ?>][size]" class="form-control " >
                         	
                         		<option value="">Select Size</option>
                        		<?php  
                        		 foreach($size_details as $size)
                                                       {?>	
                                                       	<option value="<?php echo $size['size_master_id'];?>==<?php echo $size['volume'];?>"
                                                       	 <?php if(isset($weight['size_id'])&&($weight['size_id']==$size['size_master_id'])){?> 




                                                       		selected="selected"<?php }?>> <?php echo '('.$size['volume'].')'.$size['width'].'X'.$size['height'].'X'.$size['gusset'];?></option>
                                            <?php }
                                            ?>

                         </select>
                        </td> 
                        <td><input type="text" name="catogory[<?php echo $details['color_catagory_id'];?>][<?php echo $inner_count; ?>][weight]" value="<?php echo isset($weight['weight'])?$weight['weight']:'';?>" class="form-control " placeholder="Weight">
                        </td>
                      
                        <?php if($inner_count==0){ ?>
                        <td><a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add" onclick="addRow(<?php echo $id;?>)" ><i class="fa fa-plus"></i></a> </td>
                        <?php } else { ?>
                        <td><a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" onclick="remove_row(<?php echo $inner_count;?>,<?php echo $details['color_catagory_id'];?>,<?php echo $weight['weight_id'];?>)"><i class="fa fa-minus"></i></a> </td>
                      </tr>
                      <?php } ?>
                  </tr>
                  <?php $inner_count++; }}?>
               </tbody>
              
                    
              </table>
	            </td>
	        </tr>
	        </tbody>
	    </table>

	          
            
         	
         	  	
         	 </div>
             <?php 
           
         }?>

                <div class="col-lg-9 col-lg-offset-3"> 
                        <?php if($edit){
							//printr($edit);?>
                        <button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                        <?php } else { ?>
                        <button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>
                        <?php } ?>
                        <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a> </div>
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
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
    });
	
	//$(' .addmore').click(function(){
function addRow(category_id){
	//	alert(category_id);
		var html = '';
		
		var product_id = $('#product_id').val();
	
		var count = $('#myTable_'+category_id+' tr').length;
		   var tab=$('#myTable_'+category_id+' tr:last').attr('id');
			var a=tab.split('-');
			//alert(a[1]);
			var count=(parseInt(a[1])+1);
		
				 html +='<tr class="multiplerows_'+category_id+'-'+count+'" id="multiplerows-'+count+'"> ';
				html +='<td>';
				  html +='<input type="hidden" name="catogory['+category_id+']['+count+'][category]" value="'+category_id+'" class="form-control validate[required,custom[number]]" placeholder="Weight">';
				  html += '<select name="catogory['+category_id+']['+count+'][product_zipper_id]" class="form-control validate[required]" ><option value="">Select Zipper</option>';
				  
				 html += '<?php  foreach($zipper as $zip) {?><option value="<?php echo $zip['product_zipper_id'];?>"><?php echo $zip['zipper_name'];?></option><?php }?>';

				
				  html +=  '</select>';							
				html += '</td>';
				
				
	
				html +='<td>';
				  html += '<select name="catogory['+category_id+']['+count+'][size]" class="form-control validate[required]" ><option value="">Select Size</option>';
				html +=  '<?php  foreach($size_details as $size)  {?><option value="<?php echo $size['size_master_id'];?>==<?php echo $size['volume'];?>"> <?php echo $size['width'].'X'.$size['height'].'X'.$size['gusset'].'('.$size['volume'].')';?></option> <?php } ?>';
				  html +=  '</select>';							
				html += '</td>';
				
			
				html +='<td>';
			   html +='<input type="text" name="catogory['+category_id+']['+count+'][weight]" value="" class="form-control validate[required,custom[number]]" placeholder="Weight">';
			html +='</td>';
			
			
			html +='<td>';
			   html +='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-minus"></i></a>';
			html +='</td></tr>';

		
	$('#myTable_'+category_id+' tr:last').after(html);
		
		$('.remove').click(function(){
			$(this).parent().parent().remove();
		});
		
	}
	
	$('.remove').click(function(){
		$(this).parent().parent().remove();
	});
	
	function remove_row(count,category_id,weight_id)
	{
			$('.multiplerows_'+category_id+'-'+count).remove();
		
		var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=remove', '',1);?>");
			$.ajax({
				url : remove_url,
				method : 'post',
				data : {weight_id : weight_id},
				success: function(response){
					
				},
				error: function(){
					return false;	
				}
		});
	}
	</script>
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>