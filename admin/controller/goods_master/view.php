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
if(isset($_GET['goods_master_id']) && !empty($_GET['goods_master_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$goods_master_id = base64_decode($_GET['goods_master_id']);
		$goods_data = $obj_goods_master->getGoodsData($goods_master_id);
		
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
              
              <?php if($goods_data) { ?>
              <div class="panel-body">
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Row</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $goods_data['row'];?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Column</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $goods_data['column_name'];?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Description</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $goods_data['description'];?>
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