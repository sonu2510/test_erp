<?php
include("mode_setting.php");
$f = $_GET['fun'];
$fun = $_GET['fun'];
//echo $fun;die;
$json=array();
if($fun=='updateColorStatus') {
	
	$color_id = $_POST['color_id'];
	$status_value = $_POST['status_value'];
	
	$obj_color->$fun($color_id,$status_value);
}

if($fun='displaycolor')
{	
	$product = $_POST['product_id'];
	$volume = $_POST['volume_id'];
	//printr($_POST);die;
	$selected_colors = $obj_color->selected_color($product,$volume);
	$available = $obj_color->available($product,$volume);
	$all_colors = $obj_color->all_colors();
	$html = '';
	
	if(isset($selected_colors)&&($available)&&!empty($selected_colors)&&!empty($available))
	{		
			$html.='<div class="form-group select">';		
				foreach($available as $color){
							//$html.='<div style="display:inline-block;text-align:center;margin-left:10px;border-style:solid;border-width:1px;"><h4 class="green">Available</h4>';
							$html.='<div style="display:inline-block;text-align:center;margin:5px 5px 10px 10px;border-style:solid;border-width:1px;"><h4 class="green">Available</h4>';
									$html.='<div style="display:inline-block;padding:10px 10px;text-align:center">'.$color['color'].'<br>';
									$html.='<div style="display:inline-block;margin:5px;">';
									if($_SESSION['ADMIN_LOGIN_NAME']=='Swiss pac')
									{											
											$html.='<input type="button" class="button remove" id="'.$color['color_v_id'].'" value="Remove" onclick="remove('.$color['color_v_id'].')"><br />';
									}
									else
									{
											$html.='';	
									}
									$html.='<div class="btn" style="margin:5px 5px 5px 20px;background-color:'.$color['color_1'].'"></div>';
									$html.='<div class="btn" style="margin:5px 5px 5px -4px;background-color:'.$color['color_2'].'"></div>';
									$html.='</div>';
								$html.='</div>';
								$html.='</div>';
							}
							
				foreach($selected_colors as $all_color){
								$html.='<div style="display:inline-block;text-align:center;margin:5px 5px 10px 10px;border-style:solid;border-width:1px;"><h4 class="red">Not Available</h4>';
								$html.='<div style="display:inline-block;padding:10px 10px;text-align:center">'.$all_color['color'].'<br>';
								$html.='<div style="display:inline-block;margin:5px;">';
								if($_SESSION['ADMIN_LOGIN_NAME']=='Swiss pac')
								{
										$html.='<input type="button" class="add" id="'.$all_color['pouch_color_id'].'" value="Add" onclick="add('.$all_color['pouch_color_id'].','.$product.','.$volume.')"><br />';
								}
								else
								{
										$html.='';	
								}
									
									$html.='<div class="btn" style="margin:5px 5px 5px 20px;background-color:'.$all_color['color_1'].'"></div>';
									$html.='<div class="btn" style="margin:5px 5px 5px -4px;background-color:'.$all_color['color_2'].'"></div>';
									$html.='<input type="hidden" id="product" value="'.$product.'">';
									$html.='<input type="hidden" id="volume" value="'.$volume.'">';
									$html.='</div>';
									$html.='</div>';
							$html.='</div>';
						}			
			$html.='</div>';
	}
	else
	{
				$html.='<div class="form-group select">';
					foreach($all_colors as $all_color){
									$html.='<div style="display:inline-block;text-align:center;margin:5px 5px 10px 10px;border-style:solid;border-width:1px;"><h4 class="red">Not Available</h4>'; 
										$html.='<div style="display:inline-block;padding:10px 10px;text-align:center">'.$all_color['color'].'<br>';
										$html.='<div style="display:inline-block;margin:5px;">';
										if($_SESSION['ADMIN_LOGIN_NAME']=='Swiss pac'){
											$html.='<input type="button" class="add" id="'.$all_color['pouch_color_id'].'" value="Add" onclick="add('.$all_color['pouch_color_id'].','.$product.','.$volume.')"><br />';
											}
											else
											{
												$html.='';	
											}
										
										$html.='<div class="btn" style="margin:5px 5px 5px 20px;background-color:'.$all_color['color_1'].'"></div>';
										$html.='<div class="btn" style="margin:5px 5px 5px -4px;background-color:'.$all_color['color_2'].'"></div>';
										$html.='<input type="hidden" id="product" value="'.$product.'">';
										$html.='<input type="hidden" id="volume" value="'.$volume.'">';
										$html.='</div>';
									$html.='</div>';
									$html.='</div>';
							}
			$html.='</div>';
	}
	echo $html;
	
}
if($f=='delete_color'){
	$color_v_id = $_POST['color_v_id'];
	$obj_color->$f($_POST['product_id'],$_POST['volume_id'],$color_v_id);
}

if($f=='update_color'){
	$color = $_POST['color_id'];
	$obj_color->$f($_POST['product_id'],$_POST['volume_id'],$color);
}

?>

