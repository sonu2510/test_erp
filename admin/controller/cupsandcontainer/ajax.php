<?php
include("mode_setting.php");
//echo "ajax";
$ajax = $_GET['ajaxfun'];
//echo $ajax;

if($ajax=='UpdateStatus')
{
	//printr($_POST);
	$cup_id = $_POST['cup_id'];
	$cup_status = $_POST['cup_status'];
	
	$obj_cupsandcontainer->$ajax($cup_id,$cup_status);
	
}
if($ajax=='getsize')
{
	$pro_id = $_POST['id'];
	$size = $obj_cupsandcontainer->$ajax($pro_id);
	$html='<div class="form-group">
                    <label class="col-lg-3 control-label"><span class="required">*</span>Select Volume</label>
                        <div class="col-lg-4">';
	            $html .= '<select class="form-control" name="select_volume" id="select_volume"class="form-control validate[required]" >
                            <option value="">Select Volume</option>';
                            foreach($size as $s)
                            {
                                $html.='<option value="'.$s['volume'].'" >'.$s['volume'].'</option>';
                            } 
                $html.='</select>';
	
	$html.='</div></div>';
	echo $html;
        
}
?>