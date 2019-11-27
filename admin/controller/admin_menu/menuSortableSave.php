<?php
// Start: Building System
include_once("../../../ps-config.php");
// End: Building System
include(DIR_ADMIN.'model/admin_menu.php');
$obj_menu = new adminMenu();

// Get the JSON string
$jsonstring = $_GET['jsonstring'];

// Decode it into an array
$jsonDecoded = json_decode($jsonstring, true, 64);
//printr($jsonDecoded);die;
	

	/* Function to parse the multidimentional array into a more readable array 
	 * Got help from stackoverflow with this one:
	 *    http://stackoverflow.com/questions/11357981/save-json-or-multidimentional-array-to-db-flat?answertab=active#tab-top
	*/
	function parseJsonArray($jsonArray, $parentID = 0)
	{
	  $return = array();
	  foreach ($jsonArray as $subArray) {
		 $returnSubSubArray = array();
		 if (isset($subArray['children'])) {
		   $returnSubSubArray = parseJsonArray($subArray['children'], $subArray['id']);
		 }
		 $return[] = array('id' => $subArray['id'], 'parentID' => $parentID);
		 $return = array_merge($return, $returnSubSubArray);
	  }

	  return $return;
	}
	
	
	// Dump the array to debug
	//var_dump(parseJsonArray($jsonDecoded));
	
	// Run the function above
	$readbleArray = parseJsonArray($jsonDecoded);
	//printr($readbleArray);die;
	
	// Loop through the "readable" array and save changes to DB
	foreach ($readbleArray as $key => $value) {
	
		// $value should always be an array, but we do a check
		if (is_array($value)) {
			
			// Update DB
			$obj_menu->updateMenu($value['id'],$value['parentID'],$key);
			/*$where=array('id'=>$value['id']);
			$set=array('rang'=>$key,'parent_id'=>$value['parentID']);
			$update = $obj_conn->Updatein($table, $set, $where, $like_or_in='in', $oparand='AND');*/
		}
	}
	
	
	// Echo status message for the update
	echo "The list was updated ".date("y-m-d H:i:s")."!";
	
?>