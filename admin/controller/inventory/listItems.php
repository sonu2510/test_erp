<?php
include("mode_setting.php");

$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> 'Product List',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

$click = '';
if(isset($_GET['table_name']) && !empty($_GET['table_name'])){
	if(!$obj_general->hasPermission('view',$menuId)){
		$display_status = false;
	}else{
		$table=$_GET['table_name'];
		//printr($table);
		$click = 1;
	}
	
}/*else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}*/
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>
        <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
		  	<span>
            <?php if(isset($_GET['table_name']))
				{
					if($_GET['table_name']=='product_zipper')
					{
						$d_name='Zipper Product List';
						$op='Zipper-';	
						$name='zipper_name';
						$id_name='product_zipper_id';
						$noval='No zip';
						$unit='zipper_unit';
					}
						if($_GET['table_name']=='product_spout')
					{
						$d_name='Spout Product List';
						$op='Spout-';
						$name='spout_name';
						$id_name='product_spout_id';
						$noval='No Spout';
						$unit='spout_unit';
					}
						if($_GET['table_name']=='product_material')
					{
						$d_name='Material Product List';
						$op='Material-';
						$name='material_name';
						$id_name='product_material_id';
						$noval='No material';
						$unit='material_unit';
					}
						if($_GET['table_name']=='product_accessorie')
					{
						$d_name='Accessorie Product List';
						$op='Accessorie-';
						$name='product_accessorie_name';
						$id_name='product_accessorie_id';
						$noval='No accessorie';
						$unit='product_accessorie_unit';
					}
						if($_GET['table_name']=='adhesive')
					{
						$d_name='Adhesive Product List';
						$op='Adhesive-';
						$name='make_name';
						$id_name='adhesive_id';
						$unit='adhesive_unit';
					}
						if($_GET['table_name']=='adhesive_solvent')
					{
						$d_name='Adhesive Solvent Product List';
						$op='Adhesive Solvent-';
						$name='make_name';
						$id_name='adhesive_solvent_id';
						$unit='adhesive_solvent_unit';
					}
						if($_GET['table_name']=='ink_master')
					{
						$d_name='Ink Master Product List';
						$op='Ink-';
						$name='make_name';
						$id_name='ink_master_id';
						$unit='ink_master_unit';
					}
						if($_GET['table_name']=='ink_solvent')
					{
						$d_name='Ink Solvent Product List';
						$op='Ink Solvent-';
						$name='make_name';
						$id_name='ink_solvent_id';
						$unit='ink_solvent_unit';
					}
						
				}
				else
				{
					$d_name='';
				}echo $d_name;?></span>
          	<span class="text-muted m-l-small pull-right">
            </span>
          </header>
	<div class="panel-body">
    
                           <table class="table"> 
                           <thead>
                           <tr>
                           <th >Sr.No</th>
                            <th>Product Name</th>
                             <th>Available Quantity</th> 
                              </tr> 
                              </thead> 
                              <tbody> 
                              
                              <?php 
						if($table=='product_zipper' || $table=='product_spout' || $table=='product_accessorie' || $table=='product_material')
						{
							  $n = 1;
							  $items = $obj_inventory->getitemslist($table);
							  foreach($items as $item)
							  {
							  	if($item[$name]!=$noval)
								{?>
                              <tr>
                              <td><?php echo $n;?></td>
                              <td>
                              <?php echo $op; 
							  echo $item[$name]; $id=$item[$id_name];?>
                              </td>
                              <td>
                             <?php 
							 $app_qty = $obj_inventory->getApprove($id,$table);
							  $stock_qty = $obj_inventory->getStockQty($id,$table);
							  //printr($stock_qty);
								$remain=$app_qty[0]['SUM(ph.approve_qty)'];
								$rem=$stock_qty[0]['SUM(qty)'];
								$minus=$remain - $rem;
								echo $minus.'&nbsp;&nbsp;'.$item[$unit];
								
								?>
                             </td></tr>
							 
							 <?php 
							   $n++;}}?>
                             <?php
						 }
						else
						{ $n = 1;  
							 	$i = $obj_inventory->getitemsOfInk($table);
								foreach($i as $it)
								{?>
                              <tr>
                              <td><?php echo $n;?></td>
                              <td>
                              <?php  
							   echo $op; 
							  echo $it[$name];
									$id=$it[$id_name];?>
                              </td>
							  <td>
                            <?php   $app_qty = $obj_inventory->getApprove($id,$table);
							  $stock_qty = $obj_inventory->getStockQty($id,$table);
							  //printr($stock_qty);
								$remain=$app_qty[0]['SUM(ph.approve_qty)'];
								$rem=$stock_qty[0]['SUM(qty)'];
								$minus=$remain - $rem;
								echo $minus.'&nbsp;&nbsp;'.$it[$unit];
								?>

                              </td>
                                  </tr> <?php $n++;}} ?>
                                  </tbody> 
                                  </table>
                                <p class="m-t m-b">
                                 <a class="btn btn-default" href="<?php echo $obj_general->link($rout,'mod=list', '',1);?>" style="float: right;">Cancel</a></p> 
                                 
                      </div>           
              </section>
              </div>    
              </div>
                        
</section></section>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="application/javascript">
</script>