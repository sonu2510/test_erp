<?php
include("mode_setting.php");
if(isset($_GET['user_id']) && !empty($_GET['user_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$user_id = base64_decode($_GET['user_id']);
	
	}
}
if(isset($_GET['user_type_id']) && !empty($_GET['user_type_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$user_type_id = base64_decode($_GET['user_type_id']);
	
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
	'text' 	=> $display_name,
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);
$bradcums[] = array(
	'text' 	=> $display_name.'details',
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
		//$product_id='27';
		$product = $obj_pricelist->getProduct($product_id);
		$edit = 1;
		//printr($product);
	}
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
if($display_status) {

$total_product = $obj_pricelist->getTotalProducts();
//printr($total_productname);die;
$pagination_data = '';
}
//printr($product_id);
//printr($country_id);
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
                        <tr style="font-size:80%" align="left">
                                    <!--<th>Category</th> -->
                                   
                                    <th >Description</th>
									<th>width</th>
                                    <th>height</th>
                                    <th>gusset</th>                                   
                                    <th>quantity100</th>
									<th>Air quantity100</th>
                                    <th>quantity200</th>
									<th>Air quantity200</th>
                                    <th>quantity500</th>
									<th>Air quantity500</th>
                                    <th>quantity1000</th>
									<th>Air quantity1000</th>
                                    <th>quantity2000</th>
									<th>Air quantity2000</th> 
                                    <th>quantity5000</th>
									<th>Air quantity5000</th>
                                    <th>quantity10000</th>
                                    <th>Air quantity10000</th>
                                    
                                    
                                    
                                    
                                                                       
									<th>Action</th>
							</tr>
                			<?php 
							 $proforma_product_prices = $obj_pricelist->getProforma_ProductPrices($product_id,$user_id);
							//printr($proforma_product_prices);die;
							 if(!empty($proforma_product_prices)){ 
							  ?>
                             
                              <?php
								 if($proforma_product_prices){
									 $proforma_product_prices =$proforma_product_prices;
								 }
								 else
								 {
									$proforma_product_prices[]= array(
											
											'product_id' =>  $proforma_product_prices['product_id'],
											'category_id' =>  $proforma_product_prices['category_id'],
											'Accessorie' => $proforma_product_prices['accessorie_id'] ,
											'zipper' =>  $proforma_product_prices['zipper_id'],
											'spout' =>  $proforma_product_prices['spout_id'],
											'size' => $proforma_product_prices['size_id'],
											'volume' => $proforma_product_prices['volume'],
											'measurement'=>$proforma_product_prices['measurement'],
											'valve'=>$proforma_product_prices['valve'],
											'quantity100'=> $proforma_product_prices['quantity100'],
											'quantity200'=> $proforma_product_prices['quantity200'],
											'quantity500'=> $proforma_product_prices['quantity500'],
											'quantity1000'=> $proforma_product_prices['quantity1000'],
											'quantity2000'=> $proforma_product_prices['quantity2000'],
											'quantity5000'=> $proforma_product_prices['quantity5000'],
											'quantity10000'=> $proforma_product_prices['quantity10000'],
											'air_quantity5000'=> $proforma_product_prices['air_quantity5000'],
											'air_quantity10000'=> $proforma_product_prices['air_quantity10000'],
											'air_quantity100'=> $proforma_product_prices['air_quantity100'],
											'air_quantity200'=> $proforma_product_prices['air_quantity200'],
											'air_quantity500'=> $proforma_product_prices['air_quantity500'],
											'air_quantity1000'=> $proforma_product_prices['air_quantity1000'],
											'air_quantity2000'=> $proforma_product_prices['air_quantity2000'],
											'width'=> $proforma_product_prices['width'],
											'height'=> $proforma_product_prices['height'],
											'gusset'=> $proforma_product_prices['gusset'],											
											'date_added' =>  $proforma_product_prices['date_added'],
											'date_modify' => $proforma_product_prices['date_modify']
											
										);
								}
								   if( $proforma_product_prices){
							   		$cnt = 1;
									
							   		foreach( $proforma_product_prices as  $product_price)
							   		{ 
									 $style=''; if($product_price['price_status']==1)
								        { $style="style='background-color: antiquewhite;'"; }?>
                                   
                            <tr <?php echo $style;?>>
								    

									<td><strong><b style="color:red;"><?php echo  $product_price['volume'].' '. $product_price['measurement'] .'</b><br> '.$product_price['valve'].'<br> '.$product_price['zipper_name'].'<br> '.$product_price['product_accessorie_name'].'<br> '.$product_price['spout_name'].'<br><b style="font-size:8px;color:blueviolet;"> ['.$product_price['color_name'].']<b>';?></strong></td>
									<td><input type="text" name="width" value="<?php echo $product_price['width'];?>" class="form-control input-sm" id="width_<?php echo $cnt; ?>"style=""></td>
									 <td><input type="text" name="height" value="<?php echo $product_price['height'];?>" class="form-control input-sm" id="height_<?php echo $cnt; ?>"style=""></td>
									 <td><input type="text" name="gusset" value="<?php echo $product_price['gusset'];?>" class="form-control input-sm" id="gusset_<?php echo $cnt; ?>"style=""></td>
									 <td><input type="text" name="quantity100" value="<?php echo $product_price['quantity100'];?>" class="form-control input-sm"id="quantity100_<?php echo $cnt; ?>"  style=""></td>
									 <td><input type="text" name="air_quantity100" value="<?php echo $product_price['air_quantity100'];?>" class="form-control input-sm" id="air_quantity100_<?php echo $cnt; ?>"style=""></td>
									 <td><input type="text" name="quantity200" value="<?php echo $product_price['quantity200'];?>" class="form-control input-sm" id="quantity200_<?php echo $cnt; ?>"style=""></td>
									  <td><input type="text" name="air_quantity200" value="<?php echo $product_price['air_quantity200'];?>" class="form-control input-sm" id="air_quantity200_<?php echo $cnt; ?>"style=""></td>
									 <td> <input type="text" name="quantity500" value="<?php  echo $product_price['quantity500'];?>" class="form-control input-sm" id="quantity500_<?php echo $cnt; ?>"style=""></td>
									 <td><input type="text" name="air_quantity500" value="<?php echo $product_price['air_quantity500'];?>" class="form-control input-sm" id="air_quantity500_<?php echo $cnt; ?>"style=""></td>
									 <td><input type="text" name="quantity1000" value="<?php echo $product_price['quantity1000'];?>" class="form-control input-sm"id="quantity1000_<?php echo $cnt; ?>"style=""></td>
									 <td><input type="text" name="air_quantity1000" value="<?php echo $product_price['air_quantity1000'];?>" class="form-control input-sm" id="air_quantity1000_<?php echo $cnt; ?>"style=""></td>
									 <td><input type="text" name="quantity2000" value="<?php echo $product_price['quantity2000'];?>" class="form-control input-sm" id="quantity2000_<?php echo $cnt; ?>"style=""></td>
									<td><input type="text" name="air_quantity2000" value="<?php echo $product_price['air_quantity2000'];?>" class="form-control input-sm" id="air_quantity2000_<?php echo $cnt; ?>"style=""></td>						 
									<td><input type="text" name="quantity5000" value="<?php  echo $product_price['quantity5000'];?>" class="form-control input-sm" id="quantity5000_<?php echo $cnt; ?>"style=""></td>
									<td><input type="text" name="air_quantity5000" value="<?php echo $product_price['air_quantity5000'];?>" class="form-control input-sm" id="air_quantity5000_<?php echo $cnt; ?>"style=""></td>
									<td><input type="text" name="quantity10000" value="<?php echo $product_price['quantity10000'];?>" class="form-control input-sm" id="quantity10000_<?php echo $cnt; ?>"style=""></td>
									 <td><input type="text" name="air_quantity10000" value="<?php echo $product_price['air_quantity10000'];?>" class="form-control input-sm" id="air_quantity10000_<?php echo $cnt; ?>"style=""></td>
									 
						
						 
						 
						 
						 <td><a name="btn_edit" class="btn btn-info btn-xs" onclick="update_proforma_price(<?php echo $product_price['price_id'];?>,<?php echo $cnt; ?>);">Update</a></td>
						
									<?php $cnt++; ?>
								
                            </tr>  
                   <?php
                      }
                  } else{ 
                      echo "<tr><td colspan='5'>No record found !</td></tr>";
                  }
							 }else{ 
                      echo "<tr><td colspan='20'><br><br><br>No record found !</td></tr>";
                  }
				  ?>
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
<style>
body {
zoom: 95%;
}
</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
function update_proforma_price(price_list_id,count)
{
	//alert(price_list_id);
	//alert(count);
	
	var width =$('#width_'+count).val();
	var height =$('#height_'+count).val();
	var gusset =$('#gusset_'+count).val();
	var quantity100=$('#quantity100_'+count).val();
	var quantity200=$('#quantity200_'+count).val();
	var quantity500=$('#quantity500_'+count).val();
	var quantity1000=$('#quantity1000_'+count).val();
	var quantity2000=$('#quantity2000_'+count).val();
	var quantity5000=$('#quantity5000_'+count).val();
	var quantity10000=$('#quantity10000_'+count).val();
	var air_quantity5000 =$('#air_quantity5000_'+count).val();
	var air_quantity10000 =$('#air_quantity10000_'+count).val();
	var air_quantity100 =$('#air_quantity100_'+count).val();
	var air_quantity200 =$('#air_quantity200_'+count).val();
	var air_quantity500 =$('#air_quantity500_'+count).val();
	var air_quantity1000 =$('#air_quantity1000_'+count).val();
	var air_quantity2000 =$('#air_quantity2000_'+count).val();
	
	
	
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=update_proforma_price', '',1);?>");
			$.ajax({
				type: "POST",
				url: url,					
				data:{price_list_id:price_list_id,width:width,height:height,gusset:gusset,quantity100:quantity100,quantity200:quantity200,
				quantity500:quantity500,quantity1000:quantity1000,quantity2000:quantity2000,quantity5000:quantity5000,quantity10000:quantity10000,
				air_quantity5000:air_quantity5000,air_quantity10000:air_quantity10000,air_quantity100:air_quantity100,air_quantity200:air_quantity200,
				air_quantity500:air_quantity500,air_quantity1000:air_quantity1000,air_quantity2000:air_quantity2000},
				success: function(json) {
					
					location.reload();	
					
				}
			}); 
}
</script>
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>
