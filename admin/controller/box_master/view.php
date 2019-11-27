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
//rohit
$edit = '';
if(isset($_GET['pouch_id']) && !empty($_GET['pouch_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$pouch_id = base64_decode($_GET['pouch_id']);
		$box_data = $obj_boxmaster->getPouchData($pouch_id);

		$product_data = $obj_boxmaster->getCurrentProduct($box_data['product_id']);
		$zipper_data = $obj_boxmaster->getCurrentZipper(decode($box_data['zipper']));
		$spout_data = $obj_boxmaster->getCurrentSpout(decode($box_data['spout']));
		$accessorie_data = $obj_boxmaster->getCurrentAccessorie(decode($box_data['accessorie']));
		$make_data = $obj_boxmaster->getCurrentMake($box_data['make_pouch']);
		$pouch_measure = $obj_boxmaster->getMeasurementName($box_data['pouch_volume_type']);
		$box_measure = $obj_boxmaster->getMeasurementName($box_data['box_weight_type']);
		

		
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
                 <span>Goods Detail</span> 
                </header>
              
              <?php if($box_data) { ?>
              <div class="panel-body">
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Product</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $product_data['product_name'];?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Valve</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $box_data['valve'];?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Zipper</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $zipper_data['zipper_name'];?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Spout</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $spout_data['spout_name'];?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Accesorie</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $accessorie_data['product_accessorie_name'];?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Make Pouch</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $make_data['make_name'];?>
                            </label>
                        </div>
                      </div>
                      
                      <!-- [kinjal]: add transport [30 apr(1:38 pm)] -->
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Transport By</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords(decode($box_data['transportation']));?>
                            </label>
                        </div>
                      </div>
                      <!--end [kinjal] -->
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Pouch Volume</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $box_data['pouch_volume'].' '.$pouch_measure['measurement'];?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Box Weight</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $box_data['box_weight'].' '.$box_measure['measurement'];?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Box Quantity</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $box_data['quantity'];?>
                            </label>
                        </div>
                      </div>
                      
                      
                        <div class="form-group">
        					<div class="col-lg-9 col-lg-offset-3">                
          						<a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
        					</div>
        				</div>    
                          <!--  <div class="form-group">
                                <div class="col-lg-9 pull-right">
                                    <a class="btn btn-primary" id="add-history"><i class="fa fa-plus"></i> Add History</a>
                                </div>
                          	</div>-->
                            
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
<script>
	
	
</script>	
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>