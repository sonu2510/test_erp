<?php
class adminMenu extends dbclass{
	
	public function getTotalMenuCout(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "admin_menu` WHERE parent_id = '0' AND status = '1' ";
		$data = $this->query($sql);
		//printr($data);die;
		if($data->row['total'] > 0){
			return $data->row['total'];
		}else{
			return 0;
		}
	}	
	
	public function getMenu(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "admin_menu` WHERE parent_id = '0' AND status = '1' ORDER BY sort ASC,name ASC";
		$data = $this->query($sql);
		//printr($data);die;
		if($data->num_rows > 0){
			return $data->rows;
		}else{
			return 0;
		}
	}
	
	public function getNestedMenuCount($parent_id){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "admin_menu` WHERE parent_id = '" .(int)$parent_id. "' AND status = '1' ";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function editmenu_showNested($parent_id){
		$sub_menu_count = $this->getNestedMenuCount($parent_id);
		$return = '';
		if($sub_menu_count){
			$qry = "SELECT * FROM `" . DB_PREFIX . "admin_menu` WHERE parent_id = '" .(int)$parent_id. "' AND status = '1' ORDER BY sort ASC,name ASC ";
			$sub_menu_data = $this->query($qry);
			$sub_menu = $sub_menu_data->rows;
			
			$return .= '<ol class="dd-list">';
			for($i=0;$i<$sub_menu_data->num_rows;$i++) {
				
				$mod = '';
				if($sub_menu[$i]['page_name']){
					$mod = '&mod='.$sub_menu[$i]['page_name'];
				}
				$return .= '<li class="dd-item" data-id="'.$sub_menu[$i]['admin_menu_id'].'"><div class="dd-handle">'.$sub_menu[$i]['name'].'</div>';
					$return .=  $this->editmenu_showNested($sub_menu[$i]['admin_menu_id']);
				$return .= '</li>';
			}
			$return .= '</ol>';
		}
		return $return;
	}
	
	public function addMenu($data){
		//printr($data);die;
		$sql = "INSERT INTO `" . DB_PREFIX . "admin_menu` SET name = '" .$data['name']. "', controller = '" .$data['controller']. "', page_name = '" .$data['page_name']. "', parent_id = '" . $data['parent_id'] . "', sort = '" . $data['sort'] . "', status = '1', is_delete = '0', date_added = NOW()";
		//echo $sql;die;
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateMenu($admin_menu_id,$parent_id,$sort){
		$sql = "UPDATE `" . DB_PREFIX . "admin_menu` SET parent_id = '" . (int)$parent_id. "', sort = '".(int)$sort."', date_modify = NOW() WHERE admin_menu_id = '" .(int)$admin_menu_id. "'";
		$this->query($sql);
	}
	
}
?>