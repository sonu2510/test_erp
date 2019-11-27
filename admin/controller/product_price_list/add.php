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
	'text' 	=> $display_name.' List ',
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
$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}
//Close : bradcums
//Start : edit
$edit = '';
if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$product_id = base64_decode($_GET['product_id']);

		$product = $obj_pricelist->getProduct($product_id);
		$edit = 1;

	}
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
if($display_status) {

$total_product = $obj_pricelist->getTotalProducts();

$pagination_data = '';
}


?>


<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-14">
	       <?php include("common/breadcrumb.php");?>	
        </div> 
      <div class="col-sm-12">
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
           
              
              <table border="0"  width="100%" class="">
              	
               	<tr>
                	<td colspan="6">
              			<table class="tool-row table  b-t text-small" id="myTable" width="100%">
                        <tr style="font-size:80%">
                                    <th>Zipper,
									Spout,
									Accessories Name</th>
                                    <th>Volume</th>
                                    <th>Width</th>
                                    <th>Height</th>
                                    <th>Gusset</th>
                                    <th>All Color</th>
                                    <th>Clear/Clear</th>
                                    <th>Bio
									degradable</th>
                                    <th>Ultra Clear</th>
                                    <th>With Zipper Oval Window</th>
                                    <th>Stripped Brown Paper Look With Zipper </th>
                                    <th>With Zipper Jute Look </th>
                                    <th>Brown paper with zipper</th>
                                    <th>Brown Paper With Zipper oval window </th>  
                                    <th>Brown Paper & White Paper  Zipper Full Rectangle Window </th>
                                    <th>One Side Brown Paper side /Clear with zipper </th>
                                    <th>Crystal Clear </th>
                                    <th>White paper with zipper </th>
                                    <th>Black Paper & Green Paper with zipper Zipper </th>
                                    <th>Black Paper & Green Paper Zipper Full Rectangle Window  </th>
									<th>Action</th>
							</tr>
                			<?php 
							 $product_prices = $obj_pricelist->getProductPrices($product_id);
							  ?>
                             
                              <?php
								 if($product_prices){
									 $product_prices =$product_prices;
								 }
								 else
								 {
									$product_prices[]= array(
											
											'product_id' =>  $product_price['product_id'],
											'zipper_id' => $product_price['zipper_id'] ,
											'volume' =>  $product_price['volume'],
											'width' =>  $product_price['width'],
											'height' => $product_price['height'],
											'gusset' => $product_price['gusset'],
											'all_price'=>$product_price['all_clr_price'],
											'clear_price'=>$product_price['clear_price'],
											'biodegradable_price'=> $product_price['biodegradable_price'],
											'ultra_clear_price'=> $product_price['ultra_clear_price'],
											'sup_zz_oval_window'=> $product_price['sup_zz_oval_window'],
											'stripped_bkp_look_zz'=> $product_price['stripped_bkp_look_zz'],
											'sup_zz_jtk'=> $product_price['sup_zz_jtk'],
											'sup_bkp_zz'=> $product_price['sup_bkp_zz'],
											'sup_bkp_zz_oval_window'=> $product_price['sup_bkp_zz_oval_window'],
											'sup_bkp_whp_zz_full_rec_win'=> $product_price['sup_bkp_whp_zz_full_rec_win'],
											'sup_zz_clear_bkp'=> $product_price['sup_zz_clear_bkp'],
											'sup_crystal_clear_price'=> $product_price['sup_crystal_clear_price'],
											'sup_whp_zz'=> $product_price['sup_whp_zz'],
											'sup_gp_bp_zz'=> $product_price['sup_gp_bp_zz'],
											'sup_gp_bp_zz_full_rect'=> $product_price['sup_gp_bp_zz_full_rect'],
											'date_added' =>  $product_price['date_added'],
											'date_modify' => $product_price['date_modify'],
											'status' =>  $product_price['status']
										);
								}
								   if( $product_prices){
							   		$cnt = 1;
									
							   		foreach( $product_prices as  $product_price)
							   		{ 
									?>	
                                   
                            <tr>
								
									<td><?php  echo $product_price['zipper_name'].'<br> '.$product_price['spout_name'].'<br> '.$product_price['product_accessorie_name'];?></td>
									<td><?php echo $product_price['volume'];?></td>
									<td><input type="text" name="width" value="<?php echo  $product_price['width'];?>" class="form-control input-sm " id="width_<?php echo $cnt; ?>" style=""></td>
									<td><input type="text" name="height" value="<?php echo  $product_price['height'];?>" class="form-control input-sm" id="height_<?php echo $cnt; ?>"style=""></td>
									<td><input type="text" name="gusset" value="<?php echo $product_price['gusset'];?>" class="form-control input-sm" id="gusset_<?php echo $cnt; ?>"style=""></td>
									<td><input type="text" name="all_clr_price" value="<?php  echo $product_price['all_clr_price']?>" class="form-control input-sm" id="all_clr_price_<?php echo $cnt; ?>"style=""></td>
									<td><input type="text" name="clear_price" value="<?php  echo $product_price['clear_price'];?>" class="form-control input-sm" id="clear_price_<?php echo $cnt; ?>"style=""></td>
									 <td><input type="text" name="biodegradable_price" value="<?php  echo $product_price['biodegradable_price'];?>" class="form-control input-sm" id="biodegradable_price_<?php echo $cnt; ?>"style=""></td>
									 <td><input type="text" name="ultra_clear_price" value="<?php echo $product_price['ultra_clear_price'];?>" class="form-control input-sm"id="ultra_clear_price_<?php echo $cnt; ?>"  style=""></td>
									 <td><input type="text" name="sup_zz_oval_window" value="<?php echo $product_price['sup_zz_oval_window'];?>" class="form-control input-sm" id="sup_zz_oval_window_<?php echo $cnt; ?>"style=""></td>
									 <td> <input type="text" name="stripped_bkp_look_zz" value="<?php  echo $product_price['stripped_bkp_look_zz'];?>" class="form-control input-sm" id="stripped_bkp_look_zz_<?php echo $cnt; ?>"style=""></td>
									 <td><input type="text" name="sup_zz_jtk" value="<?php echo $product_price['sup_zz_jtk'];?>" class="form-control input-sm"id="sup_zz_jtk_<?php echo $cnt; ?>"style=""></td>
									 <td><input type="text" name="sup_bkp_zz" value="<?php echo $product_price['sup_bkp_zz'];?>" class="form-control input-sm" id="sup_bkp_zz_<?php echo $cnt; ?>"style=""></td> 
									<td><input type="text" name="sup_bkp_zz_oval_window" value="<?php  echo $product_price['sup_bkp_zz_oval_window'];?>" class="form-control input-sm" id="sup_bkp_zz_oval_window_<?php echo $cnt; ?>"style=""></td>
									<td><input type="text" name="sup_bkp_whp_zz_full_rec_win" value="<?php echo $product_price['sup_bkp_whp_zz_full_rec_win'];?>" class="form-control input-sm" id="sup_bkp_whp_zz_full_rec_win_<?php echo $cnt; ?>"style=""></td>
									<td><input type="text" name="sup_zz_clear_bkp" value="<?php echo $product_price['sup_zz_clear_bkp'];?>" class="form-control input-sm" id="sup_zz_clear_bkp_<?php echo $cnt; ?>"style=""></td>
									 <td><input type="text" name="sup_crystal_clear_price" value="<?php echo $product_price['sup_crystal_clear_price'];?>" class="form-control input-sm" id="sup_crystal_clear_price_<?php echo $cnt; ?>"style=""></td>
									 <td><input type="text" name="sup_whp_zz" value="<?php echo $product_price['sup_whp_zz'];?>" class="form-control input-sm" id="sup_whp_zz_<?php echo $cnt; ?>"style=""></td>
									 <td><input type="text" name="sup_gp_bp_zz" value="<?php echo $product_price['sup_gp_bp_zz'];?>" class="form-control input-sm" id="sup_gp_bp_zz_<?php echo $cnt; ?>"style=""></td>
									 <td><input type="text" name="sup_gp_bp_zz_full_rect" value="<?php echo $product_price['sup_gp_bp_zz_full_rect'];?>" class="form-control input-sm" id="sup_gp_bp_zz_full_rect_<?php echo $cnt; ?>"style=""></td>
									<td><a name="btn_edit" class="btn btn-info btn-xs" onclick="update_price(<?php echo $product_price['price_list_id'];?>,<?php echo $cnt; ?>)">Update</a></td>
									
									<?php $cnt++; ?>
								
                            </tr>  
                   <?php
                      }
                  } else{ 
                      echo "<tr><td colspan='5'>No record found !</td></tr>";
                  } ?>
               </table> 
               </form>
            <footer class="panel-footer">
           	 <div class="row">
              <div class="col-sm-4 hidden-xs"> </div>
              <?php echo $pagination_data;?>
            </div>
          </footer>    
          
           </section>
		</div>             
      </div>
   
     
  </section>
</section>

<style type="text/css">
input[type="text"] {

width: 55px;
}
</style>

<script>
function update_price(price_list_id,count)
{
	//alert($('#volume_'+count).val());
	//var volume =$('#volume_'+count).val();
	var width =$('#width_'+count).val();
	var height =$('#height_'+count).val();
	var gusset =$('#gusset_'+count).val();
	var all_clr_price =$('#all_clr_price_'+count).val();
	var clear_price =$('#clear_price_'+count).val();
	var biodegradable_price =$('#biodegradable_price_'+count).val();
	var ultra_clear_price =$('#ultra_clear_price_'+count).val();
	var sup_zz_oval_window =$('#sup_zz_oval_window_'+count).val();
	var stripped_bkp_look_zz =$('#stripped_bkp_look_zz_'+count).val();
	var sup_zz_jtk =$('#sup_zz_jtk_'+count).val();
	var sup_bkp_zz =$('#sup_bkp_zz_'+count).val();
	var sup_bkp_zz_oval_window =$('#sup_bkp_zz_oval_window_'+count).val();
	var sup_bkp_whp_zz_full_rec_win =$('#sup_bkp_whp_zz_full_rec_win_'+count).val();
	var sup_zz_clear_bkp =$('#sup_zz_clear_bkp_'+count).val();
	var sup_crystal_clear_price =$('#sup_crystal_clear_price_'+count).val();
	var sup_whp_zz =$('#sup_whp_zz_'+count).val();
	var sup_gp_bp_zz =$('#sup_gp_bp_zz_'+count).val();
	var sup_gp_bp_zz_full_rect =$('#sup_gp_bp_zz_full_rect_'+count).val();
	
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=update_price', '',1);?>");
			$.ajax({
				type: "POST",
				url: url,					
				data:{price_list_id:price_list_id,width:width,height:height,gusset:gusset,all_clr_price:all_clr_price,clear_price:clear_price,biodegradable_price:biodegradable_price,
				ultra_clear_price:ultra_clear_price,sup_zz_oval_window:sup_zz_oval_window,stripped_bkp_look_zz:stripped_bkp_look_zz,sup_zz_jtk:sup_zz_jtk,
				sup_bkp_zz:sup_bkp_zz,sup_bkp_zz_oval_window:sup_bkp_zz_oval_window,sup_bkp_whp_zz_full_rec_win:sup_bkp_whp_zz_full_rec_win,sup_zz_clear_bkp:sup_zz_clear_bkp,
				sup_crystal_clear_price:sup_crystal_clear_price,sup_whp_zz:sup_whp_zz,sup_gp_bp_zz:sup_gp_bp_zz,sup_gp_bp_zz_full_rect:sup_gp_bp_zz_full_rect},
				success: function(json) {
					
					location.reload();	
					
				}
			});
}
</script>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>
