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
if(isset($_GET['wastage_id']) && !empty($_GET['wastage_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$wastage_id = base64_decode($_GET['wastage_id']);
		$wastage = $obj_wastage->getWastage($wastage_id);
		//printr($wastage);
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
		foreach($post['wastage'] as $k=>$v)
		{
			$test[$post['product_id'][$k]]=$v;	
		}
		$post['wastage']=json_encode($test);
		//printr($post);die;
		$insert_id = $obj_wastage->addWastage($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		foreach($post['wastage'] as $k=>$v)
		{
			$test[$post['product_id'][$k]]=$v;	
		}
		$post['wastage']=json_encode($test);
		$wastage_id = $wastage['stock_wastage_id'];
		$obj_wastage->updateWastage($wastage_id,$post);
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
        
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label">From Quantity </label>
                <div class="col-lg-4">
                  	<input type="text" name="from_quantity" value="<?php echo isset($wastage['from_quantity'])?$wastage['from_quantity']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">To Quantity </label>
                <div class="col-lg-4">
                  	<input type="text" name="to_quantity" value="<?php echo isset($wastage['to_quantity'])?$wastage['to_quantity']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]">
                </div>
              </div>
              <?php //printr($wastage); 
			  if(isset($wastage['wastage']) && $wastage['wastage']!='')
			  {
				$product = json_decode($wastage['wastage'],true);
				//printr("hi");
				$data_val=$obj_wastage->getProductOtherList($product);
				
				//$product_val=stdToArray($product);
				//printr($data_val);
				foreach($data_val as $values)
				{
					$value='';
					//$product=array($values['product_id']=>'');
					$product[$values['product_id']] = $value;
				}
				//$product=array(''=>'');
				//printr($product);
				
			  }
			  else
			  {
				$product = $obj_wastage->getProductList();
			  }
			  //printr($product);
			  foreach($product as $key=>$val)
			  { //printr($val);
				?>
                 <div class="form-group">
                <label class="col-lg-3 control-label"><?php echo isset($wastage['wastage'])?$obj_wastage->getProductName($key):$val['product_name'];?> Wastage (%) </label>
                <div class="col-lg-4">
                  	<input type="text" name="wastage[]" value="<?php echo !empty($val)?$val:'0';?>" class="form-control">
                    <input type="hidden" name="product_id[]" value="<?php echo isset($wastage['wastage'])?$key:$val['product_id'];?>" class="form-control validate[custom[number]]">
                </div>
              </div>
				<?php
                }?>
             
         		
              
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
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        //jQuery("#form").validationEngine();
    });
	
	
//Start : wastage	
/*$(document).on('click', ".wastageadd", function () {
	more_wastage();
});

$(document).on('click', ".wastageremove", function () {
    var id = $(this).attr("id");
	$(this).parent().closest(".form-group").remove();
});
function more_wastage(){
	var total_count = parseInt( $(".more_wastage").size()) + 1;
	//alert(total_count);
	$("#hdn_wastagecount").val(total_count);
	var html 	= '';
	html	+= '<div class="form-group more_wastage" id="wastage_main_'+total_count+'" >';
	html	+= '	<label class="col-lg-3 control-label"></label>';
	html	+= '	<div class="col-lg-8">';
	html	+= '		<div class="row">';
	html	+= '			<div class="col-sm-3">';
	html	+= '				<input type="text" name="form_wastage[]" id="form_wastage_'+total_count+'" value="" class="form-control validate[required,custom[onlyNumberSp]]">';
	html	+= '			</div>';
	html	+= '			<div class="col-lg-3">';
	html	+= '				<input type="text" name="to_wastage[]" id="to_wastage_'+total_count+'" value="" class="form-control validate[required,custom[onlyNumberSp]]">';
	html	+= '			</div>';
	html	+= '			<div class="col-lg-3">';
	html	+= '				<input type="text" name="wastage[]" id="wastage_'+total_count+'" value="" class="form-control validate[required,custom[onlyNumberSp]]">';
	html	+= '			</div>';
	html	+= '			<div class="col-lg-3">';
	html	+= '				<a class="btn btn-danger btn-circle btn-xs wastageremove" id="'+total_count+'" ><i class="fa fa-minus"></i></a>';
	//html	+= '				<span class="btn btn-warning btn-xs wastageremove" id="'+total_count+'" ><i class="fa fa-minus"></i> Remove</span>';
	html	+= '			</div>';
	html	+= '	   </div> ';
	html	+= '	</div>';
	html	+= '</div>';
	$("#append_wastage").append(html);
}*/
//Close : wastage
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>