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
if(isset($_GET['material_id']) && !empty($_GET['material_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$material_id = base64_decode($_GET['material_id']);
		$material = $obj_material->getMaterial($material_id);
		$material_layer = $obj_material->getMaterialLayer($material['product_material_id']);
		 //(ruchi) 
		 $material_effect = $obj_material->getMaterialEffect($material['product_material_id']);
		 $material_make = $obj_material->getMaterialMake($material['product_material_id']);
		 //(ruchi)
		$material_thickness = $obj_material->getMaterialThickness($material['product_material_id']);
		$material_new_thickness = $obj_material->getMaterialNewThickness($material['product_material_id']);
		$material_quantity = $obj_material->getMaterialQuantity($material['product_material_id']);
		
		//printr($material_new_thickness);die;
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit
//printr($_POST);
if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		//printr($post);die;
		$insert_id = $obj_material->addMaterial($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$material_id = $material['product_material_id'];
		$obj_material->updateMaterial($material_id,$post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, 'filter_edit='.$_GET['filter_edit'], '',1));
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
                <label class="col-lg-3 control-label">Material Name</label>
                <div class="col-lg-8">
                  	<input type="text" name="name" id="name" placeholder="Material Name" value="<?php echo isset($material['material_name'])?$material['material_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
             
             	<div class="form-group">
                <label class="col-lg-3 control-label">Select Layer</label>
                <div class="col-lg-8">
                	<div class="form-control scrollbar scroll-y" style="height:200px">
                        <?php
						$sel_layer = array();
						if(isset($material_layer) && !empty($material_layer) && $material_layer){
							$sel_layer = explode(',',$material_layer);
						}
						//printr($sel_layer);die;
						$layers = $obj_material->getActiveLayer();
						
                        foreach($layers as $layer){
                            echo '<div class="checkbox">';
                            echo '	<label class="checkbox-custom">';
							if(isset($sel_layer) && in_array($layer['layer'],$sel_layer)){
                            	echo '	<input type="checkbox" name="layer[]" id="'.$layer['layer'].'" value="'.$layer['layer'].'" checked="checked" onclick="checklayer('.$layer['layer'].')"> ';
							}else{
								echo '	<input type="checkbox" name="layer[]" id="'.$layer['layer'].'" value="'.$layer['layer'].'" onclick="checklayer('.$layer['layer'].')" > ';
							}
                            echo '	<i class="fa fa-square-o"></i> '.$layer['layer'].' </label>';
                            echo '</div>';
                        }
						?>
                    </div>
                </div>
              </div>
            
               <div class="form-group">
                <label class="col-lg-3 control-label">GSM</label>
                <div class="col-lg-8">
                  	<input type="text" name="gsm" value="<?php echo isset($material['gsm'])?$material['gsm']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Minimum Product Quantity</label>
                <div class="col-lg-4">
                  	<input type="text" name="minimum_quantity" value="<?php echo isset($material['minimum_quantity'])?
					$material['minimum_quantity']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Thickness</label>
                <div class="col-lg-9">
                	<div class="row">
                		<div class="col-sm-6">
                    		
                    	</div>
                         <div class="col-lg-3">
                            <a class="btn btn-success btn-xs btn-circle addthickness" data-toggle="tooltip" data-placement="top" title="Add Price" ><i class="fa fa-plus"></i></a>
                        </div>
                   </div> 
                </div>
              </div>
              
              <?php 
				  if(isset($material_new_thickness) && $material_new_thickness){
					  $new_count = 1;
					  foreach($material_new_thickness as $material_details){
							  
							echo '<div class="form-group morethickness1" id="thickness_main_'.$new_count.'" >';
							echo	'<label class="col-lg-3 control-label"></label>';
							echo	'<div class="col-lg-9">';
							echo		'<div class="row">';
							echo			'<div class="col-sm-6">';
							echo				'<input type="text" name="new_thickness[]" id="form_'.$new_count.'" value="'.$material_details['thickness'].'" class="form-control validate[required,custom[number]]" placeholder="Thickness">';
							echo			'</div>';
							echo			'<div class="col-lg-3">';
							echo				'<a class="btn btn-warning btn-xs btn-circle removethickness1" id="'.$new_count.'"><i class="fa fa-minus"></i></a>';
							echo			'</div>';
							echo		'</div> ';
							echo	'</div>';
							echo '</div>';
							  
							$new_count++;  
					  }
				  }
			  ?>
              
              <div id="append_thickenssorg"></div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Thickness Price</label>
                <div class="col-lg-8">
                	<div class="row">
                		<div class="col-sm-3">
                    		Form Thickness
                    	</div>
                    	<div class="col-lg-3">
                            To Thickness
                        </div>
                     	<div class="col-lg-3">
                            Price / Kg
                        </div>
                        <?php if($edit){ ?>
                         <div class="col-lg-3">
                            <a class="btn btn-success btn-xs btn-circle addmore"><i class="fa fa-plus"></i></a>
                        </div>
                        <?php } ?>
                   </div> 
                </div>
              </div>
              
              
              <?php
			  $count = 1;
              if(isset($material_thickness) && $material_thickness){
				//printr($material_thickness);die;
			//	
				foreach ($material_thickness as $thickness){
					echo '<div class="form-group" >';
                	echo 	'<label class="col-lg-3 control-label"></label>';
                	echo 	'<div class="col-lg-8">';
                	echo 		'<div class="row">';
                	echo		'	<div class="col-sm-3">';
                    echo		'		<input type="text" name="thickness[from][]" id="form_'.$count.'" value="'.$thickness['from_thickness'].'" class="form-control validate[required,custom[number]]" readonly="readonly">';
                    echo		'	</div>';
                    echo		'	<div class="col-lg-3">';
                    echo		'        <input type="text" name="thickness[to][]" id="to_'.$count.'" value="'.$thickness['to_thickness'].'" class="form-control validate[required,custom[number]]" readonly="readonly">';
                    echo		'    </div>';
                    echo		' 	<div class="col-lg-3">';
                    echo 		'        <input type="text" name="thickness[price][]" id="price_'.$count.'" value="'.$thickness['price'].'" class="form-control validate[required,custom[number]]">';
                    echo 		'    </div>';
                    echo		'    <div class="col-lg-3">';
                    echo		'    	<a class="btn btn-warning btn-xs btn-circle removethickness" id="'.$count.'"><i class="fa fa-minus"></i></a>';
                    echo 		'    </div>';
                    echo		'    <input type="hidden" name="hdn_addcount" id="hdn_addcount" value="">';
                   	echo		'</div> ';
                	echo	'</div>';
              		echo '</div>';
					$count++;
				}
			  }
			  ?>
              
              <?php if(!$edit){?>
                  <div class="form-group more_thickness">
                    <label class="col-lg-3 control-label"></label>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-sm-3">
                                <input type="text" name="thickness[from][]" id="form_<?php echo $count;?>" value="0.00" class="form-control validate[required,custom[number]]" readonly="readonly">
                            </div>
                            <div class="col-lg-3">
                                <input type="text" name="thickness[to][]" id="to_<?php echo $count;?>" value="" class="form-control validate[required,custom[number]]">
                            </div>
                            <div class="col-lg-3">
                                <input type="text" name="thickness[price][]" id="price_<?php echo $count;?>" value="" class="form-control validate[required,custom[number]]">
                            </div>
                            <div class="col-lg-3">
                                <a class="btn btn-success btn-xs btn-circle addmore"><i class="fa fa-plus"></i></a>
                            </div>
                            <input type="hidden" name="hdn_addcount" id="hdn_addcount" value="">
                       </div> 
                    </div>
                  </div>
              <?php } ?>    
              <div id="append_thickness"></div>
              
              <!--(ruchi)-->
              <div class="form-group" id="printing_effect" style=" <?php if(isset($material_layer) && !empty($material_layer) && $material_layer){
							echo 'display:inline';
						}else{echo 'display:none';}?>">
                <label class="col-lg-3 control-label">Select Printing Effect</label>
                <div class="col-lg-8">
                        <?php
						$sel_effect = array();
						if(isset($material_effect) && !empty($material_effect) && $material_effect){
							$sel_effect = explode(',',$material_effect);
						}
						//printr($sel_layer);die;
						$effects = $obj_material->getActiveEffect();
						//printr($effects);die;
                        foreach($effects as $effect){
                            echo '<div class="checkbox" style="float:left;width: 200px;">';
                            echo '	<label class="checkbox-custom">';
							if(isset($sel_effect) && in_array($effect['printing_effect_id'],$sel_effect)){
                            	echo '	<input type="checkbox" name="effect[]" id="'.$effect['printing_effect_id'].'" value="'.$effect['printing_effect_id'].'" checked="checked" "> ';
							}else{
								echo '	<input type="checkbox" name="effect[]" id="'.$effect['printing_effect_id'].'" value="'.$effect['printing_effect_id'].'" > ';
							}
                            echo '	<i class="fa fa-square-o"></i> '.$effect['effect_name'].' </label>';
                            echo '</div>';
                        }
						?>
                    </div>
                </div>
                
                
                 <div class="form-group">
                <label class="col-lg-3 control-label">Make Pouch</label>
                <div class="col-lg-8">
                        <?php
						$sel_make = array();
						if(isset($material_make) && !empty($material_make) && $material_make){
							$sel_make = explode(',',$material_make);
						}
						//printr($sel_layer);die;
						$makes = $obj_material->getActiveMake();
						//$makes ='';
						//printr($effects);die;
						if($makes != '')
						{
                        foreach($makes as $make){
                            echo '<div class="checkbox" style="float:left;width: 200px;">';
                            echo '	<label class="checkbox-custom">';
							if(isset($sel_make) && in_array($make['make_id'],$sel_make)){
                            	echo '	<input type="checkbox" name="make[]" id="'.$make['make_id'].'" value="'.$make['make_id'].'" checked="checked" "> ';
							}else{
								echo '	<input type="checkbox" name="make[]" id="'.$make['make_id'].'" value="'.$make['make_id'].'" > ';
							}
                            echo '	<i class="fa fa-square-o"></i> '.$make['make_name'].' </label>';
                            echo '</div>';
                        }
						}
						?>
                    </div>
                </div>
               <!--(ruchi)-->
              
              
				  <div class="form-group">
					<label class="col-lg-3 control-label">Select Quantity</label>
					<div class="col-lg-9">
                    	 <?php
						 $sqlQuantity = array();
						 if(isset($material_quantity) && $material_quantity){
							 $sqlQuantity = array_column($material_quantity,'product_quantity_id');
						 }
						 
                    	 $quantitys = $obj_material->getQuantitys();
			 			 if($quantitys){
							    foreach($quantitys as $quantity){
                                    echo '<div class="checkbox" style="float:left;width:30%;">';
                                    echo '	<label class="checkbox-custom">';
                                    if(!empty($sqlQuantity) && in_array($quantity['product_quantity_id'],$sqlQuantity)){
                                        echo '	<input type="checkbox" name="quantity[]" id="'.$quantity['product_quantity_id'].'" value="'.$quantity['product_quantity_id'].'" checked="checked"> ';
                                    }else{
                                        echo '	<input type="checkbox" name="quantity[]" id="'.$quantity['product_quantity_id'].'" value="'.$quantity['product_quantity_id'].'" > ';
                                    }
                                    echo '	<i class="fa fa-square-o"></i> '.$quantity['quantity'].' </label>';
                                    echo '</div>';
                                }
								?>
                                <div class="clearfix"></div>
                                <div class="line m-t-large"></div>
                                <a class="label bg-success selectall mt5" href="javascript:void(0);">Select All</a>
                                <a class="label bg-warning unselectall mt5" href="javascript:void(0);">Unselect All</a>    
                            <?php
						 }else{
							 echo 'Please insert or active any quanity from "QUANTITY MASTER"';
						 } ?>
					</div>
				  </div>
                  
                  
                  <div class="form-group">
					<label class="col-lg-3 control-label">Select Min. Quantity For Roll</label>
					<div class="col-lg-9">
                    	 <?php
						
                    	 $quantitys = $obj_material->getQuantity();
                    	 $explode = explode(',',$material['roll_quantity']);
			 			 if($quantitys){
							    foreach($quantitys as $quantity){
                                    echo '<div class="checkbox" style="float:left;width:30%;">';
                                    echo '	<label class="checkbox-custom">';
                                    if(in_array($quantity['roll_quantity_id'],$explode)){
                                        echo '	<input type="checkbox" name="quantityRoll[]" id="'.$quantity['roll_quantity_id'].'" value="'.$quantity['roll_quantity_id'].'" checked="checked"> ';
                                    }else{
                                        echo '	<input type="checkbox" name="quantityRoll[]" id="'.$quantity['roll_quantity_id'].'" value="'.$quantity['roll_quantity_id'].'" > ';
                                    }
                                    echo '	<i class="fa fa-square-o"></i> '.$quantity['quantity'].' </label>';
                                    echo '</div>';
                                }
								?>
                                <div class="clearfix"></div>
                                <div class="line m-t-large"></div>
                                <a class="label bg-success selectall mt5" href="javascript:void(0);">Select All</a>
                                <a class="label bg-warning unselectall mt5" href="javascript:void(0);">Unselect All</a>    
                            <?php
						 }else{
							 echo 'Please insert or active any quanity from "QUANTITY MASTER"';
						 } ?>
					</div>
				  </div>
                  
                  
               <div class="form-group">
                <label class="col-lg-3 control-label">Material Unit</label>
                <div class="col-lg-4">
                  	<input type="text" name="qtyunit" value="<?php echo isset($material['material_unit'])?$material['material_unit']:'';?>" class="form-control">
                </div>
              </div>
               
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($material['status']) && $material['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($material['status']) && $material['status'] == 0)?'selected':'';?>> Inactive</option>
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
 <!--(ruchi)-->
function checklayer(layerId){
	if(layerId == 1)
	{		
		if(document.getElementById('printing_effect').style.display == 'none')
		{
			document.getElementById('printing_effect').style.display = 'inline';
		}
		else
		{
			document.getElementById('printing_effect').style.display = 'none';
		}		
	}
}
 <!--(ruchi)-->
 
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
		$("#name").blur(function(){
			var name = $(this).val();
			if(name.length > 0){
				$(".uniqname").remove();
				$.ajax({
					type: "POST",
					url: '<?php echo HTTP_ADMIN;?>ajax/uniqMaterialName.php',
					dataType: 'json',
					data:'name='+name,
					success: function(json) {
						if(json > 0){
							$("#name").after('<span class="alert-danger uniqname"> Name already exists! </span>');
							return false;
						}
					}
				});
			}
		});
		
	
    });

$(document).on('click', ".addthickness", function () {
	moreThickness();
	
});

  /*function checklayer(a) {
	  alert(a);      
    }
	*/
function moreThickness(){
	var total_count = parseInt( $(".morethickness1").size()) + 1;
	var html 	= '';
	html	+= '<div class="form-group morethickness1" id="thickness_main_'+total_count+'" >';
	html	+= '	<label class="col-lg-3 control-label"></label>';
	html	+= '	<div class="col-lg-9">';
	html	+= '		<div class="row">';
	html	+= '			<div class="col-sm-6">';
	html	+= '				<input type="text" name="new_thickness[]" id="form_'+total_count+'" value="" class="form-control validate[required,custom[number]]" placeholder="Thickness">';
	html	+= '			</div>';
	html	+= '			<div class="col-lg-3">';
	html	+= '				<a class="btn btn-warning btn-xs btn-circle removethickness1" id="'+total_count+'"><i class="fa fa-minus"></i></a>';
	html	+= '			</div>';
	html	+= '	   </div> ';
	html	+= '	</div>';
	html	+= '</div>';
	$("#append_thickenssorg").append(html);
	
	//total_count++;
}


//Start : wastage	
$(document).on('click', ".addmore", function () {
	more_thicknessPrice();
});

$(document).on('click', ".removethickness", function () {
    var id = $(this).attr("id");
	$(this).parent().closest(".form-group").remove();
});

$(document).on('click', ".removethickness1", function () {
    var id = $(this).attr("id");
	$(this).parent().closest(".form-group").remove();
});

function more_thicknessPrice(){
	var total_count = parseInt( $(".more_thickness").size()) + 1;
	//alert(total_count);
	$("#hdn_addcount").val(total_count);
	var html 	= '';
	html	+= '<div class="form-group more_thickness" id="thickness_main_'+total_count+'" >';
	html	+= '	<label class="col-lg-3 control-label"></label>';
	html	+= '	<div class="col-lg-8">';
	html	+= '		<div class="row">';
	html	+= '			<div class="col-sm-3">';
	html	+= '				<input type="text" name="thickness[from][]" id="form_'+total_count+'" value="" class="form-control validate[required,custom[number]]">';
	html	+= '			</div>';
	html	+= '			<div class="col-lg-3">';
	html	+= '				<input type="text" name="thickness[to][]" id="to_'+total_count+'" value="" class="form-control validate[required,custom[number]]">';
	html	+= '			</div>';
	html	+= '			<div class="col-lg-3">';
	html	+= '				<input type="text" name="thickness[price][]" id="price_'+total_count+'" value="" class="form-control validate[required,custom[number]]">';
	html	+= '			</div>';
	html	+= '			<div class="col-lg-3">';
	html	+= '				<a class="btn btn-warning btn-xs btn-circle removethickness" id="'+total_count+'"><i class="fa fa-minus"></i></a>';
	html	+= '			</div>';
	html	+= '	   </div> ';
	html	+= '	</div>';
	html	+= '</div>';
	$("#append_thickness").append(html);
}

/*jQuery(document).ready(function(){
	$('#form_').removeAttr("disabled");
}*/	

//Close : wastage	
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>