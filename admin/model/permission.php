<?php
class permission extends dbclass{
	
	//permission
	public function menu_combo($selected = ''){
		$sql = "SELECT * FROM `" . DB_PREFIX . "admin_menu` WHERE parent_id != '0' AND status = '1' ORDER BY name ASC ";
		$data = $this->query($sql);
		//printr($data);die;
		if($data->num_rows){
			$readval = $data->rows;
			$selid = '';
			if(!empty($selected)){
				$selid = explode(",",$selected);
			}
			for($i=0;$i<$data->num_rows;$i++)
			{
				 if(!empty($selid)){
					 if(in_array($readval[$i]['admin_menu_id'],$selid)){
						 $sele = 'selected="selected"';
					 }else{
						 $sele = '';
					 }
				 }else{
					 $sele = '';
				 }
				?>
				<option value="<?php echo $readval[$i]['admin_menu_id']; ?>" <?php echo  $sele;?> > - <?php echo ucwords($readval[$i]['name']);?></option>
				<?php
			}
		}
	}
	
	//new design
	public function getPermissionMenu($selected = ''){
		$sql = "SELECT * FROM `" . DB_PREFIX . "admin_menu` WHERE parent_id != '0' AND status = '1' ORDER BY name ASC ";
		$data = $this->query($sql);
		//printr($data);die;
		if($data->num_rows){
			$readval = $data->rows;
			$selid = '';
			if(!empty($selected)){
				$selid = explode(",",$selected);
			}
			for($i=0;$i<$data->num_rows;$i++)
			{
				 if(!empty($selid)){
					 if(in_array($readval[$i]['admin_menu_id'],$selid)){
						 $sele = 'selected="selected"';
					 }else{
						 $sele = '';
					 }
				 }else{
					 $sele = '';
				 }
				?>
				<option value="<?php echo $readval[$i]['admin_menu_id']; ?>" <?php echo  $sele;?> > - <?php echo ucwords($readval[$i]['name']);?></option>
				<?php
			}
		}
	}
	
	//new code : get menu data
	public function getMenuData($selColoum='*'){
		$sql = "SELECT $selColoum FROM `" . DB_PREFIX . "admin_menu` WHERE parent_id != '0' AND status = '1' ORDER BY name ASC ";
		//echo $sql;
		$data = $this->query($sql);
	//	printr($data);
		return $data->rows;
		
	}
	
	public function getMenuInfo($menuId){
		$sql = "SELECT * FROM `" . DB_PREFIX . "admin_menu` WHERE admin_menu_id = '".(int)$menuId."' ";
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getUserAssignPermissionMenu($user_type_id,$user_id){
		$sql = "SELECT add_permission,edit_permission,delete_permission,view_permission FROM `" . DB_PREFIX . "account_master` WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			$menuData = array();
			$addPermission = unserialize($data->row['add_permission']);
			if($addPermission && !empty($addPermission)){
				foreach($addPermission as $add){
					$menuInfo = $this->getMenuInfo($add);
					$menuData[$menuInfo['admin_menu_id']] = array(
						'admin_menu_id' => $menuInfo['admin_menu_id'],
						'name' => $menuInfo['name'],
					);
				}
			}
			
			$editPermission = unserialize($data->row['edit_permission']);
			if($editPermission && !empty($editPermission)){
				foreach($editPermission as $edit){
					$menuInfo = $this->getMenuInfo($edit);
					$menuData[$menuInfo['admin_menu_id']] = array(
						'admin_menu_id' => $menuInfo['admin_menu_id'],
						'name' => $menuInfo['name'],
					);
				}
			}
			
			$deletePermission = unserialize($data->row['delete_permission']);
			if($deletePermission && !empty($deletePermission)){
				foreach($deletePermission as $delete){
					$menuInfo = $this->getMenuInfo($delete);
					$menuData[$menuInfo['admin_menu_id']] = array(
						'admin_menu_id' => $menuInfo['admin_menu_id'],
						'name' => $menuInfo['name'],
					);
				}
			}
			$viewPermission = unserialize($data->row['view_permission']);
			if($viewPermission && !empty($viewPermission)){
				foreach($viewPermission as $view){
					$menuInfo = $this->getMenuInfo($view);
					$menuData[$menuInfo['admin_menu_id']] = array(
						'admin_menu_id' => $menuInfo['admin_menu_id'],
						'name' => $menuInfo['name'],
					);
				}
			}
			return $menuData;
		}else{
			return '';
		}
	}
	
	//add permission
	public function addPermission($user_type_id,$user_id,$data){
		//printr($data);die;
		if(isset($data['add']) && !empty($data['add'])){
			$addid = serialize($data['add']);
		}else{
			$addid = '';
		}
		if(isset($data['edit']) && !empty($data['edit'])){
			$editid = serialize($data['edit']);
		}else{
			$editid = '';
		}
		if(isset($data['view']) && !empty($data['view'])){
			$viewid =serialize($data['view']);
		}else{
			$viewid = '';
		}
		if(isset($data['delete']) && !empty($data['delete'])){
			$deletid = serialize($data['delete']);
		}else{
			$deletid = '';
		}
		//echo $viewid;die;
		$sql = "UPDATE `" . DB_PREFIX . "account_master` SET add_permission = '" .$addid. "', edit_permission = '" .$editid. "', delete_permission = '" . $deletid . "', view_permission = '" .$viewid. "', date_modify = NOW() WHERE user_id = '" .(int)$user_id. "' AND user_type_id = '".$user_type_id."'";
		//echo $sql;die;
		$this->query($sql);
	}
	
	public function getUserData($user_type_id,$user_id,$table,$pri_coloum){
		$sql = "SELECT tt.*,am.add_permission,am.view_permission,am.edit_permission,am.delete_permission FROM " . DB_PREFIX . "$table tt LEFT JOIN " . DB_PREFIX . "account_master am ON(am.user_name=tt.user_name) WHERE tt.$pri_coloum = '".(int)$user_id."' AND am.user_id = '".(int)$user_id."' AND am.user_type_id = '".$user_type_id."'";
		//echo $sql;die;
		$data = $this->query($sql);
		return $data->row;
	}
}
?>