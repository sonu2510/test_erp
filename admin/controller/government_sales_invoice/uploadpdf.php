<?php
include('mode_setting.php');

if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' && $_FILES['file']['error'] == 0){
		
		$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		if($ext == 'pdf')
		{
			$validateImageExt = validateUploadPdf($_FILES['file']);			
			$upload_path = DIR_UPLOAD.'admin/return_doc/';				
			$file_name = $_FILES['file']['name'];
			$filetemp = $_FILES['file']['tmp_name'];	
			
			$upload_file_path = $upload_path.''.$_POST['invoice_no'].'/second.pdf';
			if(file_exists($upload_file_path)) 
			{
				$file_name = rand().'_'.$file_name;
				move_uploaded_file($filetemp,$upload_path.$file_name);
			}else{
				move_uploaded_file($filetemp,$upload_file_path);
			}
			
			$_SESSION['product_die_line'][$_POST['invoice_id']]=array(
				'inv_name' => $file_name,
				'inv_id' => $_POST['invoice_id'],
				'inv_ext' => 'pdf'
			);
			$obj_invoice->updateuploadstatus($_POST['invoice_id']);
			
			$post = $obj_invoice->shipedMailDetail($_POST['buyers_orderno'],$_POST['order_uder_id'],$_POST['date_added']);
			
			$obj_template->sendDispatchOrderEmail($post,'3',$_POST['admin'],'','',$on_ship='1');
		
			$return_array = array(
				'ext' => 'pdf',
				'name' => DIR_UPLOAD.'admin/return_doc/'.$file_name 
			);			
			echo json_encode($return_array);
		}
		else
		{
			echo 0;
		}	
}else{
	echo 0;	
}

	
?>