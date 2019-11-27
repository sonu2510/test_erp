<?php
// Start: Building System
include_once("../../ps-config.php");
// End: Building System
die("Comming soon");
?>
<html>
  <head> 
  <title>Read Excel file</title>
  </head>
  <body>
	<?php
		include 'reader.php';
    	$excel = new Spreadsheet_Excel_Reader();
	?>
	Sheet 1:<br/><br/>
    <table  border="1">
		<?php
        $excel->read('Country List withe Code and Currency.xls'); // set the excel file name here   
        $x=5;
        while($x<=$excel->sheets[0]['numRows']) { // reading row by row 
			  //echo "\t<tr>\n";
			  //$y=1;
			  //while($y<=$excel->sheets[0]['numCols']) {// reading column by column 
					//$cell = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';
					//echo "\t\t<td>$cell</td>\n";  // get each cells values
					//echo $excel->sheets[0]['cells'][$x][1]."===".$excel->sheets[0]['cells'][$x][2]."==".$excel->sheets[0]['cells'][$x][3]."==".$excel->sheets[0]['cells'][$x][4]."<br>";
					$name = isset($excel->sheets[0]['cells'][$x][1]) ? $excel->sheets[0]['cells'][$x][1] : '';
					$currency = isset($excel->sheets[0]['cells'][$x][2]) ? $excel->sheets[0]['cells'][$x][2] : '';
					$code = isset($excel->sheets[0]['cells'][$x][3]) ? $excel->sheets[0]['cells'][$x][3] : '';
					$name = ucfirst(strtolower($name));
					$name = str_replace("'","",$name);
					// Save details
					$sql_insert="INSERT INTO country SET country_name = '".$name."', country_code = '".$code."', country_currency = '".$currency."', status='1', date_added = NOW(), is_delete = '0'";
					$obj_db->query($sql_insert);
					//$y++;
			  //}  
			  //echo "\t</tr>\n";
			  $x++;
        }
        ?>    
    </table><br/>
    
  </body>
</html>
