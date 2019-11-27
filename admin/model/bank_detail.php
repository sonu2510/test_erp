<?php
class bank_detail extends dbclass{
	
	// kinjal -->
	public function addbankdetail($data)
	{
		if(isset($data['curr']) &$data['curr']== '8')
		{	
			$swift_cd_hsbc=$micr_code='';
			$bank_code= $data['bank_code'];
			$branch_code=$data['branch_code'];
			
		}
		else
		{
			$swift_cd_hsbc=$data['swift_cd_hsbc'];
			$micr_code = $data['micr_code'];
			$bank_code=$branch_code='';
		}
		$user_arr = '';
		if(isset($data['for_user'])&& !empty($data['for_user']))
		{
		    $user_arr = implode(",",$data['for_user']);
		}
		$sql = "INSERT INTO `" . DB_PREFIX . "bank_detail` SET bank_accnt = '".$data['bank_acnt']."',type_of_accnt='".$data['type_of_accnt']."', branch_nm='".$data['branch_nm']."',benefry_add = '".$data['bene_add']."', 	accnt_no = '".$data['accnt_num']."',benefry_bank_name = '".$data['bene_bank_nm']."',benefry_bank_add = '".$data['bebe_bank_add']."',swift_cd_hsbc='".$data['swift_cd_hsbc']."',micr_code='".$data['micr_code']."',bank_code='".$data['bank_code']."',branch_code='".$data['branch_code']."',intery_bank_name='".$data['inter_bank_nm']."',hsbc_accnt_intery_bank='".$data['hsbc_inter_bank']."',swift_cd_intery_bank='".$data['swfit_cd_bic']."',clabe='".$data['clabe']."',bsb='".$data['bsb']."',swift_code='".$data['swift_code']."',intery_aba_rout_no='".$data['inter_bank_aba']."',curr_code='".$data['curr']."',for_user='".$user_arr."',status = '".$data['status']."', date_added = NOW()";
		$this->query($sql);
		//echo $sql;die;
		return $this->getLastId();
	}
	
	public function updatebankdetail($bank_detail_id,$data)
	{
		
		if(isset($data['curr']) &$data['curr']== '8')
		{	
			$swift_cd_hsbc=$micr_code='';
			$bank_code= $data['bank_code'];
			$branch_code=$data['branch_code'];
			
		}
		else
		{
			$swift_cd_hsbc=$data['swift_cd_hsbc'];
			$micr_code = $data['micr_code'];
			$bank_code=$branch_code='';
		}
		$user_arr = '';
		if(isset($data['for_user'])&& !empty($data['for_user']))
		{
		    $user_arr = implode(",",$data['for_user']);
		}
		$sql = "UPDATE `" . DB_PREFIX .  "bank_detail` SET bank_accnt = '".$data['bank_acnt']."',type_of_accnt='".$data['type_of_accnt']."',branch_nm='".$data['branch_nm']."', benefry_add = '".$data['bene_add']."',accnt_no = '".$data['accnt_num']."',benefry_bank_name = '".$data['bene_bank_nm']."',benefry_bank_add = '".$data['bebe_bank_add']."',swift_cd_hsbc='".$swift_cd_hsbc."',micr_code='".$micr_code."',bank_code='".$bank_code."',branch_code='".$branch_code."', intery_bank_name='".$data['inter_bank_nm']."',hsbc_accnt_intery_bank='".$data['hsbc_inter_bank']."',swift_cd_intery_bank='".$data['swfit_cd_bic']."',clabe='".$data['clabe']."',bsb='".$data['bsb']."',swift_code='".$data['swift_code']."',intery_aba_rout_no='".$data['inter_bank_aba']."',curr_code='".$data['curr']."',for_user='".$user_arr."',status = '".$data['status']."', date_modify = NOW() WHERE bank_detail_id = '" .(int)$bank_detail_id. "'";
		$this->query($sql);
	}
	// kinjal -->
	
	public function getBankDetail($bank_detail_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "bank_detail` WHERE bank_detail_id = '" .(int)$bank_detail_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getCouriers(){
		
		$sql = "SELECT * FROM " . DB_PREFIX . "courier WHERE is_delete = 0 AND status=1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}	
	}
	
	public function getCurrencyList()
	{
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "currency WHERE is_delete = 0 AND status=1");
		return $data->rows;		
	}
	public function getCurrencyCode($currency)
	{
		$data = $this->query("SELECT currency_code FROM " . DB_PREFIX . "currency WHERE currency_id = '".$currency."' ");
		return $data->row;		
	}
	
	public function updateCurrencyPrice($country_id,$new_price){
		
		$sql = "UPDATE " . DB_PREFIX . "country SET currency_price ='".$new_price."' WHERE country_id = '".$country_id."' ";
		$this->query($sql);		
	}
	
	public function getTotalBankDetail($filter_data=array()){
		//printr($filter_data);
		$sql = "SELECT COUNT(*) as total FROM  bank_detail WHERE is_delete = '0'";
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['accnt_no'])){
			 		$sql .= " AND accnt_no LIKE '%".$filter_data['accnt_no']."%' ";
				
			}
			
			if(!empty($filter_data['curr_code'])){
				$sql .= " AND curr_code = '".$filter_data['curr_code']."' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}			
		}
		//echo $sql;		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getBankDtl($data,$filter_data=array()){
		//printr($filter_data);
		$sql = "SELECT * from bank_detail WHERE is_delete = '0'";
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['accnt_no'])){
				$sp = strlen($filter_data['accnt_no']);
				
			 		$sql .= " AND accnt_no LIKE '%".$filter_data['accnt_no']."%' ";
			}
			
			if(!empty($filter_data['curr_code']))
			{
				$sql .= " AND curr_code = '".$filter_data['curr_code']."' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}			
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY bank_detail_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
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
	
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "bank_detail` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE bank_detail_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "bank_detail` SET is_delete = '1', date_modify = NOW() WHERE bank_detail_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function updateBankStatus($bank_detail_id,$status_value){	
	   $sql = "UPDATE `" . DB_PREFIX . "bank_detail` SET status = '" .(int)$status_value ."' WHERE bank_detail_id = '".$bank_detail_id ."' ";
	   $this->query($sql);	
	}
	public function getIBUserList()
	{
		$sql = "SELECT CONCAT(first_name,' ',last_name) as user_name , international_branch_id FROM " . DB_PREFIX ."international_branch WHERE  is_delete = 0 AND status=1 ORDER BY international_branch_id ASC";
		$data = $this->query($sql);
        return $data->rows;
	}
}
?>