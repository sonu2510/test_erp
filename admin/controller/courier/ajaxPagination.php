<?php
include("mode_setting.php");
?>

          <div id="courier_pricing-upper-div">          
              		
              <?php
			 
				 
				  if (isset($_POST['page'])) {
                     $page = (int)$_POST['page'];
				  }else {
                     $page = 1;
                  }
				  
				  $option = array(             
                    'start' => ($page-1)*10,
                    'limit' => 10
                  );
				  
				  $zonePrices = $obj_courier->getZonePrice($_GET['courier_id'],$_GET['zone_id'],$option);
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
						
				  }
				  
			   ?> 
               </div>
                             
<script>

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


</script>