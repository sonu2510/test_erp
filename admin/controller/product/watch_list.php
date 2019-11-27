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
	'href' 	=> $obj_general->link('product', '', '',1),
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
if(isset($_GET['product_id']) && !empty($_GET['product_id']))
{
	if(!$obj_general->hasPermission('edit',$menuId))
	{
		$display_status = false;
	}
	else
	{
		$product_id = base64_decode($_GET['product_id']);
		$product = $obj_product->getProduct($product_id);
		//printr($wastage);
		$edit = 1;
	}
	
}else
{
	if(!$obj_general->hasPermission('add',$menuId))
	{
		$display_status = false;
	}
}
//Close : edit
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
      <?php //printr($rout2) ?>
      <div class="col-sm-12">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post"  name="form" id="form"  enctype="multipart/form-data">
           <div class="form-group">
                 <label class="col-lg-3 control-label">Product Name</label>
                  <header class="panel-heading bg-white"><?php echo $product['product_name'];?>  </header>
                </div>
		   <?php 
		   	$product_id = $product['product_id'];
			//printr($product_id);
			$size = $obj_product->getsize($product_id);
			//printr($size);
			$profit = $obj_product->getProfitPrices($product_id);
			//printr($profit);
			$stock_profit_by_air = $obj_product->getstockPrices($product_id);
			$stock_profit_by_sea = $obj_product->getstockPrices_by_sea($product_id);
			$stock_profit_by_factory = $obj_product->getstockPrices_by_factory($product_id);
			//printr($stock_wastage);
			$tool_pricing = $obj_product->getToolPrices($product_id);
		
		   ?>
           <?php
		  	 $stock_wastage = $obj_product->getWastage();
		  		//printr($stock_wastage);
			if(isset($stock_wastage['wastage']) && $stock_wastage['wastage']!='')
			  {
				$product_data = json_decode($stock_wastage['wastage'],true);
				//printr("hi");
				//printr(json_decode($stock_wastage['wastage'],true));
				$data_val=$obj_product->getProductOtherList($product_data);
				
				//printr($data_val);
				if(!empty($data_val))
				{
					foreach($data_val as $values)
					{
						$value='';
						
						$product_data[$values['product_id']] = $value;
					}
				}
				
				
				
			  }
			  else
			  {
				$product_data = $obj_product->getProductList();
			  }
			//  printr($product_data);
			  
		   ?>
           <?php
             $stock_profit_by_factory_page = 'stock_profit_factory';
			  $factory = $obj_general->getMenuId($stock_profit_by_factory_page);
			  
			  $stock_profit_by_sea_page = 'stock_profit_by_sea';
			  $sea = $obj_general->getMenuId($stock_profit_by_sea_page);
			  
			  $stock_profit_by_sea_page = 'stock_profit_by_sea';
			  $sea = $obj_general->getMenuId($stock_profit_by_sea_page);
			  
			  $size_master_page = 'size_master';
			  $size_pa = $obj_general->getMenuId($size_master_page);
			  
			  $product_profit_page = 'product_profit';
			  $profit_pa = $obj_general->getMenuId($product_profit_page);
			  
			  $stock_wastage_page = 'stock_wastage';
			  $wastage = $obj_general->getMenuId($stock_wastage_page);
			  
			  $tool_pricing_page = 'tool_pricing';
			  $tool = $obj_general->getMenuId($tool_pricing_page);
			  
			  $box_master_page = 'box_master';
			  $box = $obj_general->getMenuId($box_master_page);
		   ?>
           <div class="col-xs-3 ">
            <section class="panel text-center ">
              <div class="panel-body class">                
         		<div class="h4"><a href="<?php echo $obj_general->link('stock_profit_factory','mod=add&product_id='.encode($product['product_id']).'', '',1);?>">STOCK PROFIT BY FACTORY</a></div>
                <div class="line m-l m-r"></div>
            	  <h3 class="class"><strong><a href="<?php echo $obj_general->link('stock_profit_factory','mod=add&product_id='.encode($product['product_id']).'', '',1);?>"><?php if(isset($stock_profit_by_factory['profit'])) { echo 'Yes'; } else { echo 'No' ; } ?></a></strong></h3>               
                </div>
            </section>
          </div>
        
        
         <div class="col-xs-3">
            <section class="panel text-center ">
              <div class="panel-body class">
              
         		<div class="h4"><a href="<?php echo $obj_general->link('stock_profit_by_sea','mod=add&product_id='.encode($product['product_id']).'', '',1);?>">STOCK PROFIT BY SEA</a></div>
                <div class="line m-l m-r"></div>
         			 <h3 class="class"><strong><a href="<?php echo $obj_general->link('stock_profit_by_sea','mod=add&product_id='.encode($product['product_id']).'', '',1);?>"><?php if(isset($stock_profit_by_sea['profit'])) { echo 'Yes'; } else { echo 'No' ; } ?></a></strong></h3>
                </div>
            </section>
          </div>
        
        
        <div class="col-xs-3 ">
            <section class="panel text-center ">
              <div class="panel-body class"> 
         		<div class="h4"><a href="<?php echo $obj_general->link('stock_profit','mod=add&product_id='.encode($product['product_id']).'', '',1);?>">STOCK PROFIT BY AIR</a></div>
                <div class="line m-l m-r"></div>
          <h3 class="class"><strong><a href="<?php echo $obj_general->link('stock_profit','mod=add&product_id='.encode($product['product_id']).'', '',1);?>"><?php if(isset($stock_profit_by_air['profit'])) { echo 'Yes'; } else { echo 'No' ; } ?></a></strong></h3>
                </div>
            </section>
          </div>
          
          
          <div class="col-xs-3 ">
            <section class="panel text-center ">
              <div class="panel-body class"> 
         		<div class="h4"><a href="<?php echo $obj_general->link('size_master','mod=add&product_id='.encode($product['product_id']).'', '',1);?>">SIZE MASTER</a></div>
                <div class="line m-l m-r"></div>
          <h3 class="class"><strong><a href="<?php echo $obj_general->link('size_master','mod=add&product_id='.encode($product['product_id']).'', '',1);?>"><?php if(isset($size['width'])) { echo 'Yes'; } else { echo 'No' ; } ?></a></strong></h3>
                </div>
            </section>
          </div>
          
          
          <div class="col-xs-3 ">
            <section class="panel text-center ">
              <div class="panel-body class"> 
         		<div class="h4"><a href="<?php echo $obj_general->link('product_profit','mod=add&product_id='.encode($product['product_id']).'', '',1);?>">PRODUCT PROFIT DATA </a></div>
                <div class="line m-l m-r"></div>
          <h3 class="class"><strong><a href="<?php echo $obj_general->link('product_profit','mod=add&product_id='.encode($product['product_id']).'', '',1);?>"><?php if(isset($profit['profit'])) { echo 'Yes'; } else { echo 'No' ; } ?></a></strong></h3>
                </div>
            </section>
          </div>
          
          
          <div class="col-xs-3 ">
            <section class="panel text-center ">
              <div class="panel-body class"> 
         		<div class="h4"><a href="<?php echo $obj_general->link('product_profit','mod=add&product_id='.encode($product['product_id']).'', '',1);?>">CUSTOM WASTAGE </a></div>
                <div class="line m-l m-r"></div>
          <h3 class="class"><strong><a href="<?php echo $obj_general->link('product_profit','mod=add&product_id='.encode($product['product_id']).'', '',1);?>"><?php if(isset($profit['wastage_per'])) { echo 'Yes'; } else { echo 'No' ; } ?></a></strong></h3>
                </div>
            </section>
          </div>
          
          
          <div class="col-xs-3 ">
            <section class="panel text-center ">
              <div class="panel-body class"> 
         		<div class="h4"><a href="<?php echo $obj_general->link('stock_wastage', 'product_id='.encode($product['product_id']), '',1); ;?>">STOCK WASTAGE </a></div>
                <div class="line m-l m-r"></div>
                 <?php 
				  foreach($product_data as $key=>$val)
					  {  //printr($product_data);	?>
			
                          <h3 class="class"><strong><a href="<?php echo $obj_general->link('stock_wastage', 'product_id='.encode($product['product_id']), '',1);?>">
                          <?php if(isset($stock_wastage['wastage'])?$key == $product['product_id']&& $val:$stock_wastage['wastage']) { echo 'Yes'; } else { echo '';} ?></a></strong></h3>
           		     <?php
			 		 }			  	
					?>
                </div>
            </section>
          </div>
          
        
          <div class="col-xs-3 ">
            <section class="panel text-center ">
              <div class="panel-body class"> 
         		<div class="h4"><a href="<?php echo $obj_general->link('tool_pricing','mod=add&product_id='.encode($product['product_id']).'', '',1);?>">TOOL PRICE </a></div>
                <div class="line m-l m-r"></div>
          <h3 class="class"><strong><a href="<?php echo $obj_general->link('tool_pricing','mod=add&product_id='.encode($product['product_id']).'', '',1);?>"><?php if(isset($tool_pricing['price'])) { echo 'Yes'; } else { echo 'No' ; } ?></a></strong></h3>
                </div>
            </section>
          </div>
          
          
          <div class="col-xs-3">
            <section class="panel text-center ">
              <div class="panel-body class"> 
         		<div class="h4" ><a href="<?php echo $obj_general->link('box_master','mod=list&product_id='.encode($product['product_id']).'', '',1);?>">BOX MASTER</a></div>
                <div class="line m-l m-r"></div>
         		 <h3 class="class"><strong><a href="<?php echo $obj_general->link('box_master','mod=list&product_id='.encode($product['product_id']).'', '',1);?>"><?php if(isset($tool_pricing['price'])) { echo 'Yes'; } else { echo 'No' ; } ?></a></strong></h3>
                </div>
            </section>
          </div>
          
            </form>
          </div>
        </section>
      </div>
    </div>
  </section>
</section>
<script>
//$('.color').css('font-size', '8px');

$('.class:contains("Yes")').css('background-color', '#90a4ae');
$('.class:contains("No")').css('background-color', '#cfd8dc');
//$('.class:contains("")').css('background-color', 'Goldenrod');

</script>