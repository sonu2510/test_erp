<?php
// Start: Building System
include_once("../../ps-config.php");
// End: Building System
include(DIR_ADMIN.'model/test.php');
$obj_menu = new test();

/*$template = $obj_menu->getTemplate('500g');
printr($template);
foreach($template as $k=>$v)
{
	$setTemplate = $obj_menu->setTemplate(55,$v['product_template_size_id']);
}*/
$user = $obj_menu->getUser();
foreach($user as $k=>$v)
{
	$permission[$v['international_branch_id']] = $obj_menu->getpermission($v['international_branch_id'],4);
	$decode[$v['international_branch_id']]=unserialize($permission[$v['international_branch_id']]);
	if(is_array($decode[$v['international_branch_id']]))
	{
	array_push($decode[$v['international_branch_id']],84);
	$newse[$v['international_branch_id']]=serialize($decode[$v['international_branch_id']]);
	$re= $obj_menu->updatepermission($v['international_branch_id'],$newse[$v['international_branch_id']],4);
	}
	else
	{
	$decode[$v['international_branch_id']]=array();
	array_push($decode[$v['international_branch_id']],84);
	$newse[$v['international_branch_id']]=serialize($decode[$v['international_branch_id']]);
	$re= $obj_menu->updatepermission($v['international_branch_id'],$newse[$v['international_branch_id']],4);
	}
	$emp = $obj_menu->getemp($v['international_branch_id']);
	if($emp)
	{
	foreach($emp as $ke=>$va)
	{
		$emp_permission[$va['employee_id']] = $obj_menu->getpermission($va['employee_id'],2);
		$emp_decode[$va['employee_id']]=unserialize($emp_permission[$va['employee_id']]);
		if(is_array($emp_decode[$va['employee_id']]))
		{
		//array_push($emp_decode[$va['employee_id']],94);
		 array_push($emp_decode[$va['employee_id']],84);
		$emp_newse[$va['employee_id']]=serialize($emp_decode[$va['employee_id']]);
		$emp_re= $obj_menu->updatepermission($va['employee_id'],$emp_newse[$va['employee_id']],2);
		}
		else
		{
		$emp_decode[$va['employee_id']]=array();
		//array_push($emp_decode[$va['employee_id']],94);
		 array_push($emp_decode[$va['employee_id']],84);
		$emp_newse[$va['employee_id']]=serialize($emp_decode[$va['employee_id']]);
		$emp_re= $obj_menu->updatepermission($va['employee_id'],$emp_newse[$va['employee_id']],2);
		}
	}
	}
}
printr($permission);
printr($decode);
printr($newse);
echo 'emp<br>';
printr($emp_permission);
printr($emp_decode);
printr($emp_newse);

?>