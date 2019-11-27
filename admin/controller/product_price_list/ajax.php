<?php
include("mode_setting.php");
$fun = $_GET['fun'];
$json=1;
if($fun == 'update_price'){
	
	
	
	$qry = $obj_pricelist->update_price($_POST['price_list_id'],$_POST['width'],$_POST['height'],$_POST['gusset'],$_POST['all_clr_price'],$_POST['clear_price']	,$_POST['biodegradable_price'],$_POST['ultra_clear_price'],$_POST['sup_zz_oval_window'],$_POST['stripped_bkp_look_zz'],$_POST['sup_zz_jtk'],$_POST['sup_bkp_zz'],$_POST['sup_bkp_zz_oval_window'],$_POST['sup_bkp_whp_zz_full_rec_win'],$_POST['sup_zz_clear_bkp'],$_POST['sup_whp_zz'],$_POST['sup_whp_zz'],$_POST['sup_crystal_clear_price'],$_POST['sup_gp_bp_zz'],$_POST['sup_gp_bp_zz_full_rect']);
	echo $qry;
}
?>