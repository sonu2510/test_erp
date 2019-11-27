<?php 

class usergroup extends dbclass
	{
		function addusergroup($groupname)
			{
				$sql = "INSERT INTO `user_group` (`user_group_name`,`is_delete`, `date_added`) VALUES ('$groupname','0', 'NOW()')"; 
					$data = $this->query($sql);
			}
	}
?>