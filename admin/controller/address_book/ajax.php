<?php
include("mode_setting.php");

$fun = $_GET['fun'];
if($fun == 'update_address_status') {
	$enquiry_id = $_POST['address_id'];
	$status_value = $_POST['status_value'];
	$obj_address->$fun($enquiry_id,$status_value);
}

	if($fun == 'remove_company'){
	$company_id= $_POST['company_id'];
	$delete = $_POST['is_delete'];    
	//printr($delete);die;
	//$product_enquiry_id= $_POST['product_enquiry_id'];
	$result = $obj_address->remove_company_record($company_id,$delete);
}
	
	if($fun == 'remove_factory'){
	$factory_id= $_POST['factory_id'];
	$delete = $_POST['is_delete'];    
	//printr($company_id);
	//$product_enquiry_id= $_POST['product_enquiry_id'];
	$result = $obj_address->remove_factory_record($factory_id,$delete);
}

if($fun == 'csv_address'){
parse_str($_POST['formData'], $post);
	//printr($post['post']);die;
	$csv=$obj_address->address_array_for_CSV($post['post']);
	$input_array = Array(
    Array('*Copany Name',
        'Contact Name',
        'Designation',
		'Phone Number',
		'Email',
		
		)
	);
	$delimiter= ',';
	$output_file_name='report.csv';
    /** open raw memory as file, no need for temp files, be careful not to run out of memory thought */
    $f = fopen('php://memory', 'w');
    /** loop through array  */
	$d='';
    foreach ($input_array as $line)
	{
       $d.= fputcsv($f, $line, $delimiter);
    }
	foreach ($csv as $line)
	{
        $d.=fputcsv($f, $line, $delimiter);
    }
   	fseek($f, 0);
    fpassthru($f);
}

if($fun=='remove_logo')
{   
	$logo_remove = $_POST['del'];
	$obj_address->$fun($logo_remove);
}
if($fun=='report')
{   
	parse_str($_POST['formData'], $post);
	//printr($post);die;
	$html=$obj_address->$fun($post);
	echo $html;
}
if($fun=='customer_followups_report')
{   
	parse_str($_POST['formData'], $post);
	$html=$obj_address->$fun($post);
	//echo $html;
}
	?>