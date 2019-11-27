<?php
	header( "Content-Type: application/vnd.ms-excel" );
	header( "Content-disposition: attachment; filename=spreadsheet.xls" );
	
	// print your data here. note the following:
	// - cells/columns are separated by tabs ("\t")
	// - rows are separated by newlines ("\n")
	
	// for example:
	echo 'First Name' . "\t" . 'Last Name' . "\t" . 'Phone' . "\n";
	echo 'John' . "\t" . 'Doe' . "\t" . '555-5555' . "\n";
?>