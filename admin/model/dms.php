<?php 
class dms extends dbclass
{
	public function addTax($post,$file)
	{
		$sql = "INSERT INTO dms SET  title = '".$post['title']."',document_name='".$file['name']."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',date_added = NOW(),date_modify=NOW(),is_delete=0 ";
		
		$data = $this->query($sql);
		$last_id=$this->getLastId();
	
				
	$fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'zip', 'xlsx', 'cad', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'pps', 'ppsx', 'odt', 'xls', 'xlsx', '.mp3', 'm4a', 'ogg', 'wav', 'mp4', 'm4v', 'mov', 'wmv', 'csv', 'txt' );
	if(isset($file['name']) && $file['name'] != '' && $file['error'] == 0){
	
	$validateImageExt = validateUploadImage($file);
	//$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	$userfile_extn = explode(".", strtolower($file['name']));
	//printr($userfile_extn);//die;
		if (in_array(strtolower($userfile_extn[1]), $fileTypes)) {
			//echo "get";die;
			require_once(DIR_SYSTEM . 'library/resize-class.php');
			mkdir(DIR_UPLOAD.'admin/dms/'.$last_id);
			$upload_path = DIR_UPLOAD.'admin/dms/'.$last_id.'/';
		
			$file_name = $file['name'];
			$filetemp = $file['tmp_name'];
			$upload_image_path = $upload_path.$file_name;
		
			if(file_exists($upload_image_path)) 
			{
				$file_name = rand().'_'.$file_name;
				if($userfile_extn[1]=="jpg" || $userfile_extn[1]=="jpeg" || $userfile_extn[1]=="png" || $userfile_extn[1]=="gif" || $userfile_extn[1]=="bmp")
				//Origional Image
				{
					compressImage($validateImageExt,$filetemp,$upload_path,$file_name,100);
				}
				else
				{	
					$file_name = '100_'.$file_name;
					move_uploaded_file($filetemp,$upload_path.$file_name);
					$file_name = $file['name'];
				}
			
			}else{
			
				if($userfile_extn[1]=="jpg" || $userfile_extn[1]=="jpeg" || $userfile_extn[1]=="png" || $userfile_extn[1]=="gif" || $userfile_extn[1]=="bmp")
				{
					compressImage($validateImageExt,$filetemp,$upload_path,$file_name,100);
				}
				else
				{
					$file_name = '100_'.$file_name;
					move_uploaded_file($filetemp,$upload_path.$file_name);
					$file_name = $file['name'];
				}
			//move_uploaded_file($filetemp,$upload_image_path);
				//compressImage($validateImageExt,$filetemp,$upload_path,$file_name,100);
			
				}
			
				
				//$user_name = $obj_tax_calender->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
				//printr($user_name);die;
			//	$img = array('img_nm' => $file_name,'date' => date("Y-m-d"),'user_name' => $user_name['user_name'],'img_path' => HTTP_UPLOAD.'admin/tax_img/100_' .$file_name);
				
				
			}
		
	}else{
	
	}
		
		
		//}
	
	}

	public function updateStatus($status,$post){
		if($status == 2){
			$sql = "UPDATE dms SET is_delete = '1', date_modify = NOW() WHERE dms_id IN (" .implode(",",$post). ")";
			$this->query($sql);
		}
	}
	
	public function getTotalDms(){
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		if($user_type_id == 2){
			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
			$set_user_id = $parentdata->row['user_id'];
			$set_user_type_id = $parentdata->row['user_type_id'];
			$cond=" AND user_id='".$set_user_id ."' AND user_type_id='".$set_user_type_id ."'";
		}else if($user_type_id == 4)
		{
			$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
			$set_user_id = $user_id;
			$set_user_type_id = $user_type_id;
			$cond=" AND user_id='".$set_user_id ."' AND user_type_id='".$set_user_type_id ."'";
		}
		else
		{
			$set_user_id = $user_id;
			$set_user_type_id  = $user_type_id;
			$userEmployee='';
			$cond=" ";
		}
		//printr($userEmployee);
		$str='';
		if($userEmployee) {
			$str=" OR ( user_id IN ('".$userEmployee ."') AND user_type_id ='2' ) ";
		}
		
		$sql = "SELECT COUNT(*) as total FROM dms WHERE is_delete=0  $cond $str ORDER BY dms_id DESC";
		//echo $sql;die; 
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['total'];
		}else{
			return false;
		}
	}
	
	public function getDms($data){
		//printr($data);
		
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		if($user_type_id == 2){
			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
			$set_user_id = $parentdata->row['user_id'];
			$set_user_type_id = $parentdata->row['user_type_id'];
			$cond=" AND user_id='".$set_user_id ."' AND user_type_id='".$set_user_type_id ."'";
		}else if($user_type_id == 4)
		{
			$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
			$set_user_id = $user_id;
			$set_user_type_id = $user_type_id;
			$cond=" AND user_id='".$set_user_id ."' AND user_type_id='".$set_user_type_id ."'";
		}
		else
		{
			$set_user_id = $user_id;
			$set_user_type_id  = $user_type_id;
			$userEmployee='';
			$cond=" ";
		}
		$str='';
		if($userEmployee) {
			$str=" OR ( user_id IN ('".$userEmployee ."') AND user_type_id ='2' ) ";
		}
		
		$sql = "SELECT * FROM dms WHERE is_delete=0 $cond $str";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY dms_id";	
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
		//echo $sql;
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getUserEmployeeIds($user_type_id,$user_id)
	{
		$sql = "SELECT GROUP_CONCAT(employee_id) as ids,GROUP_CONCAT(2) as type_ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."'";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['ids'];
		}else{
			return false;
		}
	} 
	
	public function getUser($user_id,$user_type_id){
		if($user_type_id == 1){
			//$sql = "SELECT u.user_name,u.first_name,u.last_name,am.user_type_id,am.user_id FROM " . DB_PREFIX ."user u, " . DB_PREFIX ."account_master am WHERE u.user_id = '".(int)$user_id."' AND am.user_id = '".(int)$user_id."' AND am.user_type_id = '".(int)$user_type_id."'";
			$sql = "SELECT u.user_name, co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 2){
			$sql = "SELECT e.user_name, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.user_name, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			//$sql = "SELECT co.country_name,c.first_name,c.last_name,c.client_id FROM " . DB_PREFIX ."client c , country co, address addr WHERE c.client_id = '".(int)$user_id."' AND addr.user_type_id = '3' AND c.address_id = addr.address AND co.country_id=addr.country_id ";
			$sql = "SELECT co.country_name, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";
		}
		//echo $sql;die;
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getUserPermission($menu_id)
	{
		$menu = implode('|',$menu_id);
		
		$sql = "SELECT email,user_name,user_type_id,user_id FROM " . DB_PREFIX ."account_master WHERE add_permission REGEXP '".$menu."' OR edit_permission REGEXP '".$menu."' OR delete_permission REGEXP '".$menu."' OR view_permission REGEXP '".$menu."'";
		$data = $this->query($sql);
		return $data->rows;
	}
	
}
?>