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
	'text' 	=> 'Product Order List',
	'href' 	=> $obj_general->link($rout,'mod=stock_list', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=>'Stock Order Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums

//Start : edit
$edit = '';
if(isset($_GET['country_id']) && !empty($_GET['country_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$country_id = base64_decode($_GET['country_id']);
		$country = $obj_inventory->getCountry($country_id);
		$courier_data = $obj_inventory->getCouriers();
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
		$item_id=$post['itemlist'];	
		$insert_id = $obj_inventory->addProductStock($post,$item_id);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, 'mod=list', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$country_id = $country['country_id'];
		$obj_inventory->updateCountry($country_id,$post);
		$obj_session->data['success'] = UPDATE;
		if(isset($obj_session->data['page'])){
			$pageString = '&page='.$obj_session->data['page'];
			unset($obj_session->data['page']);
		}else{
			$pageString = '';
		}
		page_redirect($obj_general->link($rout, $pageString.'&filter_edit='.$_GET['filter_edit'], '',1));
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
        
      <div class="col-sm-8" style="width:100%">
        <section class="panel">
          <header class="panel-heading bg-white"> Stock Order </header>
     
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="inventory-form" enctype="multipart/form-data" >
              
                
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Order No</label>
                <div class="col-lg-4"> <input type="text" name="ono" style="width:150px" 
                class="form-control validate[required]" value="" id="o_no"/>
              </div>
              </div>
         
 <div class="form-group">
                <label class="col-lg-3 control-label">Items</label>
                <div class="col-lg-4">
                <select name="itemlist" id="items" class="form-control validate[required]">
                <option value="">Select Item</option>
                 <optgroup label="Zipper">
                <?php $table = "product_zipper"; $list = $obj_inventory->getitemslist($table);
				//printr($list);
				foreach($list as $kk)
				{
					if($kk['zipper_name']!='No zip')
					{	$unit=$kk['zipper_unit'];
				?>
                  <option value="<?php echo $table.'-'.$kk['product_zipper_id'].'-'.$unit;?>"> <?php echo $kk['zipper_name'];?></option> 
                <?php } }?> 
                
                </optgroup> 
                 <optgroup label="Spout">
                <?php $table = "product_spout"; $list = $obj_inventory->getitemslist($table);
				foreach($list as $kk)
				{
					if($kk['spout_name']!='No Spout')
					{
				?>
                  <option value="<?php echo $table.'-'.$kk['product_spout_id'];?>"><?php echo $kk['spout_name'];?></option> 
                <?php }
				}?> 
                
                </optgroup> 
                 <optgroup label="Accessorie">
                <?php $table = "product_accessorie"; $list = $obj_inventory->getitemslist($table);
				foreach($list as $kk)
				{
					if($kk['product_accessorie_name']!='No accessorie')
					{
				?>
                  <option value="<?php echo $table.'-'.$kk['product_accessorie_id'];?>"><?php echo $kk['product_accessorie_name'];?></option> 
                <?php }
				}?> 
                
                </optgroup> 
                 <optgroup label="Material">
                <?php $table = "product_material"; $list = $obj_inventory->getitemslist($table);
				
				foreach($list as $kk)
				{
					if($kk['material_name']!='No Material')
					{
				?>
                  			<option value="<?php echo $table.'-'.$kk['product_material_id'];?>"><?php echo $kk['material_name'];?></option> 
                <?php }
				}?> 
                
                </optgroup> 
                 <optgroup label="Adhesive">
                <?php $table = "adhesive"; $list = $obj_inventory->getitemsOfInk($table);
				foreach($list as $kk)
				{
				?>
                  <option value="<?php echo $table.'-'.$kk['adhesive_id'];?>"><?php echo $kk['make_name'];?></option> 
                <?php 
				}?> 
                
                </optgroup> 
                <optgroup label="Adhesive Solvent">
                <?php $table = "adhesive_solvent"; $list = $obj_inventory->getitemsOfInk($table);
				//printr($list);
				foreach($list as $kk)
				{
				?>
                  <option value="<?php echo $table.'-'.$kk['adhesive_solvent_id'];?>"><?php echo $kk['make_name'];?></option> 
                <?php 
				}?> 
                
                </optgroup> 
                 <optgroup label="Ink Master">
                <?php $table = "ink_master"; $list = $obj_inventory->getitemsOfInk($table);
				foreach($list as $kk)
				{ 
				?>
                  <option value="<?php echo $table.'-'.$kk['ink_master_id'];?>"><?php echo $kk['make_name'];?></option> 
                <?php
				}?> 
                
                </optgroup> 
                <optgroup label="Ink Solvent">
                <?php $table = "ink_solvent"; $list = $obj_inventory->getitemsOfInk($table);
				foreach($list as $kk)
				{
				?>
                  <option value="<?php echo $table.'-'.$kk['ink_solvent_id'];?>"><?php echo $kk['make_name'];?></option> 
                <?php
				}?> 
                
                </optgroup> 
              
                </select>
                </div>
                </div>
              
             <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Order Date</label>
                <div class="col-lg-4">
               <input type="text" name="date"  value="" placeholder="Order Date" class="span2 validate[required]" data-date-format="yyyy-mm-dd" id="input_rdate" value=""  />
                </div>
              </div> 
             
                <div class="form-group">
               	 <label class="col-lg-3 control-label"><span class="required">*</span>Enter Qty</label>
                	<div class="col-lg-1">
                 		<input type="text" name="itemsqty" style="width:150px" class="form-control validate[required,custom[number]]" value="" id="itemsqty" value=""/>
                	</div>
              			</div> 
                <div class="col-lg-9 col-lg-offset-3">
                 <input type="submit" name="btn_save"  value="ADD" class="btn btn-primary" id="btn_save"/>
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
<style>

ul {
    list-style-type:none;
    padding:0px;
    margin:0px;
}

.selected {
    background-color:#efefef;
}
</style>


<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
jQuery(document).ready(function(){
	   jQuery("#inventory-form").validationEngine();
	   
$(document).ready(function() {
  $("#input_rdate").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
  });

		$('#itemsqty').keyup(function()
		{	//alert("click");
				var add_qty_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getApprove', '',1);?>");
				var val = $('#itemsqty').val();
				var item_id=$('#items').val();//alert(item_id); 
				var arr = item_id.split('-');//alert(arr);
				//alert("click");
				//$("#items").html("<span>"+arr[0] + "</span></br>" + arr[1]+"/"+arr[2]);
				var tname = arr[0];
				var id = arr[1];
				//alert(tname);
				//alert(id);
				$.ajax({
					url : add_qty_url,
					method : 'post',
      				 data:{qty:val,tname:tname,id:id},	
					success: function(response){ 
				//	alert(response);
						var res = $.parseJSON(response);
						if(val > res)
						{	//alert("hiii");
								alert('Out Of Stock Order' + res);
								$('#itemsqty').val('');
								return false;
						}
				},
					error: function(){
					return false;
				}
				
			});
				
		});
		
});
</script> 
<!-- Close : validation script -->
<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>