<?php  
// Include Connaction Class
session_start(); 
// Include Connaction Class
require_once($_SESSION['SES_SERVER_PATH'].'ps-config.php');

include("mode_setting.php");

$user_id = isset($_POST['user_id'])?$_POST['user_id']:'';

if($user_id){
	$user_info = getUserDetail($user_id);
	if(!empty($user_info[0])){
		?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><b><?php echo ucwords($user_info[0]['name']);?></b></h4>
        </div>
        <div class="modal-body">
		<div class="row">
            <div class="center logo hidden-xs col-sm-3">
            	<img src="<?php echo getUserImage($user_id);?>" alt="No Image" class="img-responsive img-thumbnail" height="50" />
            </div>
        <?php
		$str = '';
		if($user_info[0]['address']){
            $str .= '<p><span>Address : </span>'.$user_info[0]['address'].'</p>';
        }
		if($user_info[0]['city']) {
			$str .= '<p><span>City : </span>'.getCityName($user_info[0]['city']).'</p>';
		}
		if($user_info[0]['state']) {
			$str .= '<p><span>State : </span>'.getStateName($user_info[0]['state']).'</p>';
		}
		if($user_info[0]['country']) {
			$str .= '<p><span>Country : </span>'.getCountryName($user_info[0]['country']).'</p>';
		}
		if($user_info[0]['zipcode']) {
			$str .= '<p><span>Zipcode : </span>'.$user_info[0]['zipcode'].'</p>';
		}
		if($user_info[0]['contact_number']){
			$str .= '<p><span> Contact Number : </span>'.$user_info[0]['contact_number'].'</p>';
		}
		if($user_info[0]['gender']){
			$str .= '<p><span>Gender : </span>'.$user_info[0]['gender'].'</p>';
		}
		if($user_info[0]['dateof_bitrh'] && $user_info[0]['dateof_bitrh'] != '0000-00-00'){
			$str .= '<p><span>Date Of Birth : </span>'.$user_info[0]['dateof_bitrh'].'</p>';
		}   
		if($str){
			echo '<div class="col-sm-9">'.$str.'</div>';
		}
        ?> 
			</div>
        </div>
        <?php
	}
}else{
	?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><b>User Detail</b></h4>
    </div>
    <div class="modal-body">
    	<p>Not Found....</p>
    </div>
    <?php
}
?>


