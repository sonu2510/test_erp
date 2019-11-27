<?php
include("mode_setting.php");

$edit = '';
$courier = '';
$qstring = '';
if(isset($_GET['courier_id']) && !empty($_GET['courier_id'])){
	$courier_id = decode($_GET['courier_id']);
	$getColoum = 'courier_id,courier_name';
	$courier = $obj_courier->getCourier($courier_id,$getColoum);
	$qstring = '&mod=zoneList&courier_id='.encode($courier['courier_id']);
	//echo $qstring;die;
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
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> 'Zone List',
	'href' 	=> $obj_general->link($rout, $qstring, '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> 'Zone Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums

//Start : edit



if(isset($_GET['zone_id']) && !empty($_GET['zone_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$courier_zone_id = decode($_GET['zone_id']);
		$zone = $obj_courier->getZone($courier['courier_id'],$courier_zone_id);
		//printr($zone);die;
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}


//Close : edit

if($display_status){

//insert
if(isset($_POST['btn_save'])){
	$post = post($_POST);
	//printr($post);die;
	$insert_id = $obj_courier->addCourierZone($courier['courier_id'],$post);
	$_SESSION['success'] = ADD;
	page_redirect($obj_general->link($rout, $qstring, '',1));
	
} 

//edit
if(isset($_POST['btn_update']) && $edit){
	$post = post($_POST);
	//printr($post);die;
	$courier_id = $courier['courier_id'];
	$courier_zone_id = $zone['courier_zone_id'];
	$obj_courier->updateCourierZone($courier_id,$courier_zone_id,$post);
	$_SESSION['success'] = UPDATE;
	page_redirect($obj_general->link($rout, $qstring, '',1));
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
          <header class="panel-heading bg-white"> <b><?php echo $courier['courier_name'];?></b> Zone Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" name="form" id="form" method="post" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Zone</label>
                <div class="col-lg-8">
                  <input type="text" name="zone" value="<?php echo isset($zone['zone'])?$zone['zone']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Select Country</label>
                <div class="col-lg-8">
                	
                   
                	<div class="form-control scrollbar scroll-y" style="height:200px" id="groupbox">
                        <?php
						
						 $selected = array();								  
						 if($edit){
							 $selCountry = $obj_courier->chckSelectedZoneCountry($courier_id,$zone['courier_zone_id']);
							 if($selCountry){
							 	$selected = explode(",",$selCountry);
							 }
							 $checkAddedZoneCountry = $obj_courier->chckAddedZoneCountry($courier['courier_id'],$zone['courier_zone_id']);
						 }else{
							$checkAddedZoneCountry = $obj_courier->chckAddedZoneCountry($courier['courier_id'],'');
						 }
						$countrys = $obj_courier->getCountry($checkAddedZoneCountry);
						//printr($countrys);die;
						
						
                        foreach($countrys as $country){
							echo '<div class="checkbox">';
								echo '<label class="checkbox-custom">';
								if(in_array($country['country_id'],$selected)){
										echo '<input type="checkbox" checked="checked" name="country[]" id="'.$country['country_id'].'" value="'.$country['country_id'].'"> ';
								}else{
									echo '<input type="checkbox" name="country[]" id="'.$country['country_id'].'" value="'.$country['country_id'].'"> ';
								}
								echo '<i class="fa fa-square-o"></i> '.$country['country_name'].' </label>';
							echo '</div>';
						}
						?>
                    </div>
                    <a class="btn btn-default btn-xs selectall mt5" href="javascript:void(0);">Select All</a>
                    <a class="btn btn-default btn-xs unselectall mt5" href="javascript:void(0);">Unselect All</a>    
                </div>
              </div>
              
              <?php if($edit){ ?>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Price </label>
                <div class="col-lg-9">
                	<div class="row">
                		<div class="col-sm-3">
                    		<b>Form Kg</b>
                    	</div>
                    	<div class="col-lg-3">
                            <b>To Kg</b>
                        </div>
                     	<div class="col-lg-3">
                            <b>Price </b>
                        </div>
                         <div class="col-lg-3">
                            <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add Price" ><i class="fa fa-plus"></i></a>
                        </div>
                   </div> 
                </div>
              </div>
              <?php /*?><div class="form-group">
                <label class="col-lg-3 control-label"></label>
                <div class="col-lg-9">
                    <div class="col-sm-3 cursorp show">Show All</div>
                    <div class="col-lg-3 hide">Hide All</div>
                </div>
              </div><?php */?>
              
              <div id="courier_pricing">
              	<div id="append_price"></div>
                <div id="courier_pricing-upper-div">
              <?php
			  if($edit){
				  $pagination = '';
				  $total_zone_price = $obj_courier->getTotalZonePrice($courier_id,$zone['courier_zone_id']);
				  
				  if (isset($_GET['page'])) {
                     $page = (int)$_GET['page'];
				  }else {
                     $page = 1;
                  }
				  
				  $option = array(             
                    'start' => ($page-1)*10,
                    'limit' => 10
                  );
				  //$option=array();
				  
				  $zonePrices = $obj_courier->getZonePrice($courier_id,$zone['courier_zone_id'],$option);
				  if(isset($zonePrices) && !empty($zonePrices)){
						$count = 1;
					 	foreach ($zonePrices as $zonePrice){
							echo '<div class="form-group more_price" >';
							echo 	'<label class="col-lg-3 control-label"></label>';
							echo 	'<div class="col-lg-9">';
							echo 		'<div class="row" id="m_'.$zonePrice['courier_zone_price_id'].'">';
							echo		'	<div class="col-sm-3">';
							echo		'		<input type="text" name="from_kg_'.$zonePrice['courier_zone_price_id'].'" id="form_'.$count.'" value="'.$zonePrice['from_kg'].'" class="form-control validate[required,custom[number]]">';
							echo		'	</div>';
							echo		'	<div class="col-lg-3">';
							echo		'        <input type="text" name="to_kg_'.$zonePrice['courier_zone_price_id'].'" id="to_'.$count.'" value="'.$zonePrice['to_kg'].'" class="form-control validate[required,custom[number]]">';
							echo		'    </div>';
							echo		' 	<div class="col-lg-3">';
							echo 		'        <input type="text" name="price_'.$zonePrice['courier_zone_price_id'].'" id="price_'.$count.'" value="'.$zonePrice['price'].'" class="form-control validate[required,custom[number]]">';
							echo 		'    </div>';
							echo		'    <div class="col-lg-3">';
							//echo		'    	<a class="btn btn-warning btn-xs btn-circle removetPrice" id="'.$count.'"><i class="fa fa-minus"></i></a>';
							echo		'    	<a class="btn btn-info btn-xs cursorp update" id="'.$zonePrice['courier_zone_price_id'].'">Update</a>';
							echo		'    	<a class="btn btn-danger btn-xs cursorp delete" id="'.$zonePrice['courier_zone_price_id'].'">Delete</a>';
							
							echo 		'    </div>';
							echo		'</div> ';
							echo	'</div>';
							echo '</div>';
							$count++;
						}
						
						//echo $total_zone_price."==".LISTING_LIMIT;die;
						$total_pages = ceil($total_zone_price/10);
						echo '<div class="form-group">';
                			echo '<label class="col-lg-3 control-label">Status</label>';
                				echo '<div class="col-lg-4">';
						
						if($total_pages > 1){
							$pagination .= '<ul class="pagination pagination-small m-t-none m-b-none">';	
							
							for($i=0;$i<$total_pages;$i++){
								if($i==0){
									$pagination .= '<li class="active"><a href="">'.($i+1).'</a></li>';	
								}else{
									$pagination .= '<li><a href="'.$obj_general->link($rout, 'page='.($i+1), '',1).'">'.($i+1).'</a></li>';	
								}
							}
							
							$pagination .= '</ul>';
						}
						echo '</div>';
						echo '</div>';
						
				  }
				  
			   } ?> 
               		</div>
                    	<?php echo "<div class='col-sm-12' style='margin-bottom:10px;'>".$pagination."</div>"; ?>
               </div>
              
              <?php } ?>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($courier['status']) && $courier['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($courier['status']) && $courier['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
                
              </div>
              
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                <?php if($edit){?>
                  	<button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                <?php } else { ?>
                	<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
                <?php } ?>  
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, $qstring, '',1);?>">Cancel</a>
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
<!-- select2 --> <script src="<?php echo HTTP_SERVER;?>js/select2/select2.min.js"></script>
<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
		
		//var data=[{id:0,tag:'hello'},{id:1,tag:'hi'},{id:2,tag:'world'},{id:3,tag:'yes'},{id:4,tag:'no'}];
		var country_data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=chckAddedZoneCountry&courier_id='.$courier_id.'&courier_zone_id='.$courier_zone_id, '',1);?>");
		
		var country_data;
		
		/*$("#select2-country").select2({
						
			tokenSeparators:[","," "],
			placeholder: "Select Country",
			multiple: true,
			ajax: {
				url: country_data_url,
				dataType: 'json',
				quietMillis: 100,
				data: function (term, page) {
					return {
						q: term, // search term
						page: page
					};
				},											
				results: function (data, page) {		
					return {results: data};
				}
			},
	
		});*/	
		
		
		$(".delete").click(function(){
			var courier_zone_id = $(this).attr('id');
			if(courier_zone_id.length > 0){
				var del_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=deleteCourierZonePrice', '',1);?>");
				$.post(del_url,{id:courier_zone_id},function(result){
					
					var response = JSON.parse(result);
					if(typeof response.success != 'undefined'){
						$("#m_"+courier_zone_id).remove();					
						set_alert_message(response.success,"alert-success","fa-check");
					}else{
						set_alert_message(response.warning,"alert-warning","fa-warning");
					}
					
				});
			}
		});
		
	
		$(".update").click(function(){						
			
			var courier_zone_id = $(this).attr('id');
			var from_kg = $('input[name="from_kg_'+courier_zone_id+'"]').val();
			var to_kg = $('input[name="to_kg_'+courier_zone_id+'"]').val();
			var price = $('input[name="price_'+courier_zone_id+'"]').val();
			//alert(from_kg+"==="+to_kg+"====="+price);return false;
			if(courier_zone_id.length > 0){ 
				var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateCourierZonePrice', '',1);?>");
				$.post(url,{id:courier_zone_id,fkg:from_kg,tkg:to_kg,price:price},function(result){
					
					var response = JSON.parse(result);
					if(typeof response.success != 'undefined'){					
						set_alert_message(response.success,"alert-success","fa-check");
					}else{
						set_alert_message(response.warning,"alert-warning","fa-warning");
					}
				});
			}
		});
		
		/*$("#courier_pricing").hide();
		$(".show").click(function(){
			$("#courier_pricing").show(800);
		});
		$(".hide").click(function(){
			$("#courier_pricing").hide(800);
		});*/
    });
	
<?php if($edit){?>	
//Start : zone	
$(document).on('click', ".addmore", function () {
	more_price();
	$(".addnew").click(function(){
		var cnt = $(this).attr('id');
		var from_kg = $("#form_"+cnt).val();
		var to_kg = $("#to_"+cnt).val();
		var price = $("#price_"+cnt).val();
		//alert(cnt+"===="+from_kg+"==="+to_kg+"=="+price);return false;
		var zone_id = '<?php echo $courier_zone_id;?>';
		var courier_id = '<?php echo $courier_id;?>';
		//alert(zone_id+"==="+courier_id);return false;
		if(from_kg.length > 0 && to_kg.length > 0 && price.length > 0){
			
			var add_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=addCourierZonePrice', '',1);?>");
			$.post(add_url,{courier_id:courier_id,zone_id:zone_id,fkg:from_kg,tkg:to_kg,price:price},function(result){
				var response = JSON.parse(result);
				if(typeof response.success != 'undefined'){
					set_alert_message(response.success,"alert-success","fa-check");
				}else{
					set_alert_message(response.warning,"alert-warning","fa-warning");
				}
			});
		}else{
			set_alert_message("insert valide input!","alert-warning","fa-warning");
		}
	});
});

$(document).on('click', ".removetPrice", function () {
    var id = $(this).attr("id");
	$(this).parent().closest(".form-group").remove();
});
function more_price(){
	
	var total_count = parseInt( $(".more_price").size()) + 1;
	var html 	= '';
	html	+= '<div class="form-group more_price" id="price_main_'+total_count+'" >';
	html	+= '	<label class="col-lg-3 control-label"></label>';
	html	+= '	<div class="col-lg-9">';
	html	+= '		<div class="row">';
	html	+= '			<div class="col-sm-3">';
	html	+= '				<input type="text" name="from_kg" id="form_'+total_count+'" value="" class="form-control validate[required,custom[number]]" placeholder="From kg">';
	html	+= '			</div>';
	html	+= '			<div class="col-lg-3">';
	html	+= '				<input type="text" name="to_kg" id="to_'+total_count+'" value="" class="form-control validate[required,custom[number]]" placeholder="To kg">';
	html	+= '			</div>';
	html	+= '			<div class="col-lg-3">';
	html	+= '				<input type="text" name="price" id="price_'+total_count+'" value="" class="form-control validate[required,custom[number]]" placeholder="Price / kg">';
	html	+= '			</div>';
	html	+= '			<div class="col-lg-3">';
	html	+= '				<a class="btn btn-success btn-xs addnew" id="'+total_count+'" title="Add New">Add</a>';
	html	+= '			</div>';
	html	+= '	   </div> ';
	html	+= '	</div>';
	html	+= '</div>';
	$("#append_price").append(html);
}
//Close : zone	

$('.pagination li').click(function(){
	
	var page_url = $(this).children('a').attr('href').split('&page=');
	var page_no = page_url[1];
	//alert(page_no);return false;
	var add_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajaxPagination&courier_id='.$courier_id.'&zone_id='.$zone['courier_zone_id'], '',1);?>");
	$('.pagination li').removeClass('active');
	$('#courier_pricing-upper-div').load(add_url,{'page':(page_no)});
	$(this).addClass('active');
	
	return false;
});
<?php } ?>

</script> 
<!-- Close : validation script -->

<?php
} else { 
	include_once(DIR_ADMIN.'access_denied.php');
}
?>