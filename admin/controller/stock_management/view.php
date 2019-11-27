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
if(isset($_GET['stock_id']) && !empty($_GET['stock_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$stock_id = base64_decode($_GET['stock_id']);
		$stock_data = $obj_stock->getStockData($stock_id);
		$goods_data = $obj_stock->getGoodsRowColumn($stock_data['goods_id']);


	}
}
//Close : edit
if($display_status){
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
        
      <div class="col-sm-9">
        
            <section class="panel">  
            
                <header class="panel-heading bg-white">
                 <span>Stock Detail</span> 
                </header>
              
              <?php if($stock_data) { ?>
              <div class="panel-body">
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                           <?php $row= $obj_stock->getGoodsRowColumn($stock_data['row']); ?> 
							<input type="hidden" id="row" value=" <?php echo $row['row'];?>" />
                            <?php  $column= $obj_stock->getGoodsRowColumn($stock_data['column_name']); ?>
                            <input type="hidden" id="column" value=" <?php echo $column['column_name'];?>" />
                   

                      <?php

					  $product= $obj_stock->getActiveProductName($stock_data['product']);
					  if($stock_data['description']==1)
						{
							$desc = "Store";
						}
						elseif($stock_data['description']==2)
						{
							$desc = "Dispatched";
						}
						else
						{
							$desc = "Goods Returned";
						} ?>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Order Number</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $stock_data['order_no'];?>
                            </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Description</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $desc;?>
                            </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Product</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $product['product_name'];?>
                            </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Quantity</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $stock_data['qty'];?>
                            </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Rack Name</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($goods_data['name']);?>
                            </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Rows</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $stock_data['row'];?>
                            </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Columns</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $stock_data['column_name'];?>
                            </label>
                        </div>
                      </div>
                        <div class="form-group">
        					<div class="col-lg-9 col-lg-offset-3">                
          						<a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
        					</div>
        				</div>    
                         </div>
                         
                         
                      
                    </form>
                  </div>
                  
                  <?php } else { ?>
                  		<div class="text-center">No Data Available</div>
                  <?php } ?>
                </section>    
      </div>
    </div>
  </section>
</section>
<style>
 .in_out {
	float:left;width:70px;
}
.tr{
	height:100px;
}
.td{
	width:280px;
}
</style>												
<script>
$(document).ready(function(){
		    });
</script>	
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>