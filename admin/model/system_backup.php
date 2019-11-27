<?php
class systemBackup extends dbclass{
	
	public function addBackup($data,$user_id,$user_type_id){
		//printr($data);die;
		$databaseBackup = 0;
		$fileBackup = 1;
		//if(isset($data['database_backup']) && $data['database_backup'] == on){
		if($data && $data == 1){
			
			$table_data = $this->query("SHOW TABLES");
			$backup_db_folder = DIR_SERVER."system_backup/".date('d-m-Y').'/database';
			
			if (!file_exists($backup_db_folder)) {
				mkdir($backup_db_folder, 0777, true);
			}
			
			foreach($table_data->rows as $i=>$row){
				$backup_file  = $backup_db_folder.'/'.$row['Tables_in_swisspac'].".sql";
				$sql = "SELECT * INTO OUTFILE '$backup_file' FROM " . DB_PREFIX .$row['Tables_in_swisspac'];
				
				if(file_exists($backup_file)){
					unlink($backup_file);	
				}
				$this->query($sql);
				if ($i > 0 && $i % 20 == 0) {
					sleep(20);
				}
			}
			
			$databaseBackup = 1;	
		}
		//if(isset($data['file_backup']) && $data['file_backup'] == on)
		elseif($data && $data == 2)
		{
			$backup_files_folder = DIR_SERVER."system_backup/".date('d-m-Y');
			if (!file_exists($backup_files_folder)) {
				mkdir($backup_files_folder, 0777, true);
			}
			$this->createZip(DIR_SERVER, $backup_files_folder.'/backup_file.zip',true);
			
			$fileBackup = 1;
		}
		
		$sql = "INSERT INTO `" . DB_PREFIX . "system_backup` SET user_id = '".$user_id."',user_type_id='".$user_type_id."',database_backup = '".$databaseBackup."',	file_backup = '".$fileBackup."',date_added=NOW(),status=1";
		
		$this->query($sql);
	}
	
	public function getDbTables(){
		$table_data = $this->query("SHOW TABLES");
		return $table_data;
	}
	
	function createZip($source, $destination) {
		//echo $source."===".$destination;die;
		if (extension_loaded('zip')) {
			if (file_exists($source)) {
				$zip = new ZipArchive();
				if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
					$source = realpath($source);
					if (is_dir($source)) {
						$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
						foreach ($files as $i=>$file) {
							$file = realpath($file);
							if (is_dir($file)) {
								$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
							} else if (is_file($file)) {
								$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
							}
							if ($i > 0 && $i % 20 == 0) {
								sleep(20);
							}
						}
					} else if (is_file($source)) {
						$zip->addFromString(basename($source), file_get_contents($source));
					}
				}
				return $zip->close();
			}
		}
		return false;
	}
	
	
	public function getTotalBackup(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "system_backup` WHERE is_delete = '0'";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getBackups($data){
		$sql = "SELECT am.user_name,sb.* FROM `" . DB_PREFIX . "system_backup` sb LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_id=sb.user_id AND sb.user_type_id=am.user_type_id WHERE is_delete = '0'";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY system_backup_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "department` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE department_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "department` SET is_delete = '1', date_modify = NOW() WHERE department_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function setDir($source,$destination){
		$images = $path;
		
		//this folder must be writeable by the server
		$zip_file = $destination.'/backup.zip';
		
		if ($handle = opendir($images))  
		{
			$zip = new ZipArchive();
		
			if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) 
			{
				exit("cannot open <$filename>\n");
			}
		
			while (false !== ($file = readdir($handle))) 
			{
				$zip->addFile('path/to/images/'.$file);
				echo "$file\n";
			}
			closedir($handle);
			echo "numfiles: " . $zip->numFiles . "\n";
			echo "status:" . $zip->status . "\n";
			$zip->close();
			echo 'Zip File:'.$zip_file . "\n";
			die;
		}
	}
	
}
?>