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
	'text' 	=> $display_name,
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);
$bradcums[] = array(
	'text' 	=> 'Product Details ',
	'href' 	=> $obj_general->link($rout, 'mod=view&user_id='.$_GET['user_id'].'&user_type_id='.$_GET['user_type_id'], '',1),
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
if(isset($_GET['user_id']) && !empty($_GET['user_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$admin_user_id = base64_decode($_GET['user_id']);
		$proforma_price_qty = $obj_pricelist->getproforma_price_qty($admin_user_id);
		//printr($proforma_price_qty);
		$edit = 1;
	}
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//printr($product_id);
//Close : edit
if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		$admin_user_id = base64_decode($_GET['user_id']);
		$insert_id = $obj_pricelist->Addproforma_price_qty($post,$admin_user_id);
		$obj_session->data['success'] = ADD;
			page_redirect($obj_general->link($rout, 'mod=view&user_id='.$_GET['user_id'].'&user_type_id='.$_GET['user_type_id'], '',1));
	}
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
	    $admin_user_id = base64_decode($_GET['user_id']); 
		$obj_pricelist->Updateproforma_price_qty($post,$admin_user_id);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, 'mod=view&user_id='.$_GET['user_id'].'&user_type_id='.$_GET['user_type_id'], '',1));
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
      <div class="col-sm-11">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
            
              <?php if($edit==1 && !empty($edit)){ ?>
                  <div class="form-group">
                      <label class="col-lg-3 control-label">Product Name</label>
                      <div class="col-lg-9">
                         <label class="control-label normal-font">
                           <?php echo $product['product_name']; ?>
                         </label>   
                      </div>
                  </div>
              <?php } ?>
              <div class="table-responsive"> 
              <table border="0"  width="100%"  class="tool-row table  b-t text-small"   id="myTable">
              	<tr>
                	 <th>From (Qty)</th>
                	 <th>To (Qty)</th> 
                	 <th>Fields Selection</th>
                	 <th>Action</th>
                </tr>
                <?php 
                    $struc= $obj_pricelist->getTableStructure();
                    //printr($struc);
                    if(!empty($proforma_price_qty))
                    {
                    $proforma_price_qty=$proforma_price_qty;
                    }else{
                        $proforma_price_qty[]= array(
											'price_qty_id' => '',
											'to_qty' => '',
											'from_qty' => '',
											'field' => '',
										);
                }	if($proforma_price_qty){
				   		$inner_count = 1;
				   		foreach($proforma_price_qty as $proforma_qty)
        				{ 
        				    $field=explode(',',$proforma_qty['field']);
                            ?>
                           	<tr>
                           	    <input type="hidden" name="qty[<?php echo $inner_count;?>][price_qty_id]" id="" value="<?php echo $proforma_qty['price_qty_id'];?>" class="form-control validate[required,custom[number]]">
                           	    <td><input type="text" name="qty[<?php echo $inner_count;?>][from_qty]" id="" value="<?php echo $proforma_qty['from_qty'];?>" class="form-control validate[required,custom[number]]"></td>
                           	    <td><input type="text" name="qty[<?php echo $inner_count;?>][to_qty]" id="" value="<?php echo $proforma_qty['to_qty'];?>" class="form-control validate[required,custom[number]]"></td>
                           	    <td><input type="hidden" id="proforma_qty" value='<?php echo json_encode($struc);?>' />
                           	        <select data-placeholder="Begin typing a name to filter..." multiple class="chosen-select form-control select2-container select2-container-multi" style="width: 400px;" name="qty[<?php echo $inner_count;?>][field][]">
                    						<option value=""></option>
                    						<?php foreach ($struc as $str) { //printr($str);?>
                    							<?php if(isset($proforma_qty) && in_array($str['COLUMN_NAME'],$field)) { ?>
                                                    <option value="<?php echo $str['COLUMN_NAME']; ?>" selected="selected"><?php echo  $str['COLUMN_NAME'];?></option> 
                    							<?php } else { ?>
                    								<option value="<?php echo $str['COLUMN_NAME']; ?>"><?php echo  $str['COLUMN_NAME'];?></option>
                    							<?php } ?>
                    						<?php } ?>                                       
                    					  </select>
                    			</td> 
                                 <td>
                               <?php if($edit==1 && $inner_count!=1 ){?> <a class="btn btn-danger btn-xs btn-circle remove" onclick="remove_row(<?php echo $proforma_qty['price_qty_id'];?>)" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-minus"></i></a></td>
                               <?php }else{?>
                                 <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add " ><i class="fa fa-plus"></i></a>
                           	    <?php }?>
                           	 </td>
                           	</tr>
                           	<?php $inner_count++; 
        				}
                   	}?>
               
              </table>
              
                        <div class="col-lg-9 col-lg-offset-3">  
                         <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add Profit" ><i class="fa fa-plus"></i></a>         
                        <?php if($edit){?>
                                <button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                        <?php    } else { ?>
                            <button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save</button>	
                        <?php } ?>  
                          <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
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
<style>   .chosen-container {
    width: 300px; 
}</style>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/select2.min.js"></script>
<script src="<?php echo HTTP_SERVER;?>js/chosen.jquery.min.js"></script>
<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
    });
	 $(".chosen-select").chosen({
		no_results_text: "Oops, nothing found!"
	});
	$(' .addmore').click(function(){
		var html = '';
	var arr = jQuery.parseJSON($('#proforma_qty').val());
    console.log(arr);
		var count =(($('#myTable tr').length ));
	//	alert(count);
				html +='<tr><td><input type="hidden" name="qty['+count+'][price_qty_id]" id="" value="" class="form-control validate[required,custom[number]]">';
            		    html += '<input type="text" name="qty['+count+'][from_qty]" id="" value="" class="form-control validate[required,custom[number]]">';
            	  html += '</td>';
            	  html +='<td>';
            		   html += '<input type="text" name="qty['+count+'][to_qty]" id="" value="" class="form-control validate[required,custom[number]]">';
            	html += '</td><td>';
			  html += ' <select data-placeholder="Begin typing a name to filter..." multiple class="chosen-select1 form-control select2-container select2-container-multi" style="width: 400px;" name="qty['+count+'][field][]">';
				  for(var i=0;i<arr.length;i++)
				  {
				 html += '<option value="'+arr[i].COLUMN_NAME+'">'+arr[i].COLUMN_NAME+'</option>';
				}
			html +=' </select></td><td>';
			   html +='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-minus"></i></a>';
			html +='</td></tr>';
			
	 
	//	alert(html);
		$('.tool-row').append(html);
		$(".chosen-select1").chosen({
		    no_results_text: "Oops, nothing found!"
	    });
		$('.remove').click(function(){
			$(this).parent().parent().remove();
		});
		
	});
	
	$('.remove').click(function(){
		$(this).parent().parent().remove();
	});
	
	function remove_row(price_qty_id) 
	{
		var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=remove', '',1);?>");
			$.ajax({
				url : remove_url,
				method : 'post',
				data : {price_qty_id : price_qty_id},
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