<?php
class dbclass {
	
	private $link;
	
	public function __construct() {
		$hostname=DB_HOSTNAME;
		$username=DB_USERNAME;
		$password=DB_PASSWORD;
		$database=DB_DATABASE;
		
		$this->link = mysql_connect($hostname, $username, $password);
		
		if (!$this->link) {
			trigger_error('Error: Could not make a database link using ' . $username . '@' . $hostname);
		}

		if (!mysql_select_db($database, $this->link)) {
			trigger_error('Error: Could not connect to database ' . $database);
		}
	}
	
	
	
}
?>