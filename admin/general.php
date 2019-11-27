<?php

class general extends dbclass{

	

	//Start : Left side admin menu 

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

	//	echo "SELECT * FROM `" . DB_PREFIX . "admin_menu` WHERE parent_id = '0' AND status = '1' ORDER BY sort ASC,name ASC";

		$data = $this->query($sql);

	//	printr($data);die;

		if($data->num_rows > 0){

			return $data->rows;

		}else{

			return 0;

		}

	}

	

	public function getAllSettings(){

		

		$sql = "SELECT * FROM " . DB_PREFIX . "general_setting WHERE is_delete = 0";

		$data = $this->query($sql);

		if($data->num_rows){

			return $data->row;

		}else{

			return false;

		}				

	}

	//return sub menu count

	public function getNestedMenuCount($parent_id){

		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "admin_menu` WHERE parent_id = '" .(int)$parent_id. "' AND status = '1' ";

		$data = $this->query($sql);

		return $data->row['total'];

	}

	

	//this function return html ul li data

	public function nestedMenu($parent_id){

		$sub_menu_count = $this->getNestedMenuCount($parent_id);

		$return = '';

		if($sub_menu_count){

			$qry = "SELECT * FROM `" . DB_PREFIX . "admin_menu` WHERE parent_id = '" .(int)$parent_id. "' AND status = '1' ORDER BY sort ASC,name ASC ";

			$sub_menu_data = $this->query($qry);

			$sub_menu = $sub_menu_data->rows;

			$return .= '<ul class="dropdown-menu">';

			for($i=0;$i<$sub_menu_data->num_rows;$i++) {

				

				if($this->checkUser()){

					if ($this->hasPermission('view', $sub_menu[$i]['admin_menu_id'])) {

						

						$mod = '';

						if($sub_menu[$i]['page_name']){

							$mod = '&mod='.$sub_menu[$i]['page_name'];

						}

						if($sub_menu[$i]['controller']){

							$set_rout = $this->link($sub_menu[$i]['controller'], $mod, '',1);

						}else{

							$set_rout = 'javascript:void(0);';

						}

						if($sub_menu[$i]['admin_menu_id']!=ORDER_ACCEPT_ID && $sub_menu[$i]['admin_menu_id']!=ORDER_INPROCESS_ID && $sub_menu[$i]['admin_menu_id']!=ORDER_PRICEEDIT_ID && $sub_menu[$i]['admin_menu_id']!=DISCOUNT_RATE_PERMISSION && $sub_menu[$i]['admin_menu_id']!=GRESS_PRICE_PERMISSION && $sub_menu[$i]['admin_menu_id']!=LEAD_LIST_PERMISSION && $sub_menu[$i]['admin_menu_id']!=SHOW_CHART_PERMISSION && $sub_menu[$i]['admin_menu_id']!=SHOW_THICKNESS_PERMISSION  && $sub_menu[$i]['admin_menu_id']!=PHYSICAL_STOCK_PERMISSION)

						{

    						$return .= '<li class="dropdown-submenu" ><a href="' .$set_rout. '">'.$sub_menu[$i]['name'].'</a>';
    
    						$return .=  $this->nestedMenu($sub_menu[$i]['admin_menu_id']);
    
    						$return .= '</li>';

						}

					}

				}else{

					$mod = '';

					if($sub_menu[$i]['page_name']){

						$mod = '&mod='.$sub_menu[$i]['page_name'];

					}

					if($sub_menu[$i]['controller']){

						$set_rout = $this->link($sub_menu[$i]['controller'], $mod, '',1);

					}else{

						$set_rout = 'javascript:void(0);';

					}

					//if($sub_menu[$i]['admin_menu_id']!=ORDER_ACCEPT_ID && $sub_menu[$i]['admin_menu_id']!=ORDER_INPROCESS_ID && $sub_menu[$i]['admin_menu_id']!=ORDER_PRICEEDIT_ID) sonu add 8-4-2017
					
					if($sub_menu[$i]['admin_menu_id']!=ORDER_ACCEPT_ID && $sub_menu[$i]['admin_menu_id']!=ORDER_INPROCESS_ID && $sub_menu[$i]['admin_menu_id']!=ORDER_PRICEEDIT_ID && $sub_menu[$i]['admin_menu_id']!=DISCOUNT_RATE_PERMISSION && $sub_menu[$i]['admin_menu_id']!=GRESS_PRICE_PERMISSION && $sub_menu[$i]['admin_menu_id']!=LEAD_LIST_PERMISSION && $sub_menu[$i]['admin_menu_id']!=SHOW_CHART_PERMISSION && $sub_menu[$i]['admin_menu_id']!=SHOW_THICKNESS_PERMISSION  && $sub_menu[$i]['admin_menu_id']!=PHYSICAL_STOCK_PERMISSION)


					{

					$return .= '<li class="dropdown-submenu" ><a href="' .$set_rout. '">'.$sub_menu[$i]['name'].'</a>';

					$return .=  $this->nestedMenu($sub_menu[$i]['admin_menu_id']);

					$return .= '</li>';

					}

				}

			}

			$return .= '</ul>';

		}

		return $return;

	}

	

	public function permission($user_type_id,$user_id){

		$sql = "SELECT add_permission,view_permission,edit_permission,delete_permission FROM `" . DB_PREFIX . "account_master` WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '" .(int)$user_id. "' ";

		$data = $this->query($sql);

		$permission = array();

		if($data->num_rows){

			foreach ($data->rows as $result){

				$permission['add'] = $result['add_permission'];

				$permission['view'] = $result['view_permission'];

				$permission['edit']	= $result['edit_permission'];

				$permission['delete'] = $result['delete_permission'];

			}

		}

		return $permission;

	}

	

	public function hasPermission($key, $value) {

		if($this->checkUser()){

			global $obj_session;

			$permission = $this->permission($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);

			if (isset($permission[$key]) && !empty($permission[$key])) {

				return in_array($value, unserialize($permission[$key]) );//explode(',',$permission[$key])

			} else {

				return false;

			}

		}else{

			return true;

		}

	}

	

	public function hasMainMenuPermission($admin_menu_id){

		global $obj_session;

		//echo $admin_menu_id."<br>";

		$permission = $this->permission($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);

		

		$arra = unserialize($permission['view']);//explode(",",$permission['view']);

		if(isset($arra) && !empty($arra)){

			$parentIds = array();

			foreach ($arra as $val){

				$return_id = $this->getParentId($val);

				if(!in_array($return_id,$parentIds)){

					$parentIds[] = $return_id;

				}

			}

			if(in_array($admin_menu_id,$parentIds)){

				return true;

			} else {

				return false;

			}

		}else{

			return false;

		}

		//printr($parentIds);

	}

	

	public function getParentId($admin_menu_id){

		$sql = "SELECT parent_id FROM `" . DB_PREFIX . "admin_menu` WHERE admin_menu_id = '" .(int)$admin_menu_id. "'";

		$data = $this->query($sql);

		if($data->num_rows){

			return $data->row['parent_id'];

		}else{

			return '';

		}

	}

	

	public function getMenuId($rout){

		$sql = "SELECT admin_menu_id FROM `" . DB_PREFIX . "admin_menu` WHERE controller = '" .$rout. "'";

		$data = $this->query($sql);

		if($data->num_rows){

			return $data->row['admin_menu_id'];

		}else{

			return false;

		}

	}

	

	

	//Close : Left side admin menu 

	

	public function checkUser(){

		

		if($_SESSION['LOGIN_USER_TYPE'] == 1 && $_SESSION['ADMIN_LOGIN_SWISS'] == 1 ){

			return false;

		} else {

			return true;

		}

	}

	

	public function isLogged(){

		if(isset($_SESSION['ADMIN_LOGIN_SWISS']) && (int)$_SESSION['ADMIN_LOGIN_SWISS'] > 0 && isset($_SESSION['LOGIN_USER_TYPE']) && (int)$_SESSION['LOGIN_USER_TYPE'] > 0 && isset($_SESSION['ADMIN_LOGIN_EMAIL_SWISS']) && !empty($_SESSION['ADMIN_LOGIN_EMAIL_SWISS'])){

			$return['user_type_id'] = $_SESSION['LOGIN_USER_TYPE'];

			$return['user_id'] = $_SESSION['ADMIN_LOGIN_SWISS'];

			$return['user_email'] = $_SESSION['ADMIN_LOGIN_EMAIL_SWISS'];

			return $return;

		}else{

			return '';

		}

	}

	

	//URL FUNCTION

	private $url;

	private $ssl;

	private $rewrite = array();

	public function link($route, $args = '', $connection = 'NONSSL',$user_type=0) {

		

		if ($connection ==  'NONSSL') {

			$url = $this->url;

		} else {

			$url = $this->ssl;	

		}

		if($user_type){

			$url .= HTTP_ADMIN;

		}else{

			$url .= HTTP_SERVER;

		}

		$url .= 'index.php?route=' . $route;

			

		if ($args) {

			$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&')); 

		}

		

		foreach ($this->rewrite as $rewrite) {

			$url = $rewrite->rewrite($url);

		}

				

		return $url;

	}

	

	public function ajaxLink($route, $args = '', $connection = 'NONSSL',$user_type=0) {

		

		if ($connection ==  'NONSSL') {

			$url = $this->url;

		} else {

			$url = $this->ssl;	

		}

		if($user_type){

			$url .= HTTP_ADMIN;

		}else{

			$url .= HTTP_SERVER;

		}

		$url .= 'ajaxIndex.php?route=' . $route;

			

		if ($args) {

			$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&')); 

		}

		

		foreach ($this->rewrite as $rewrite) {

			$url = $rewrite->rewrite($url);

		}

				

		return $url;

	}

	

	//get name

	public function getName($tableName,$coloumName,$coloumValue,$returnColoum){

		$sql = "SELECT $returnColoum FROM " . DB_PREFIX . "$tableName WHERE $coloumName = '".$coloumValue."'";

				

		$data = $this->query($sql);

		if($data->num_rows){

			return $data->row["$returnColoum"];

		}else{

			return '-';

		}

	}

	

	public function getmeasurementCombo($selected=""){

		$sql = "SELECT * FROM " . DB_PREFIX . "template_measurement WHERE status = '1' AND is_delete = '0'";

		$data = $this->query($sql);

		$html = '';

		if($data->num_rows){

			//return $data->rows;

			$html = '';

			$html .= '<select name="measurement" id="user_id" class="form-control validate[required]" style="width:70%">';

					$html .= '<option value="">Select Measurement</option>';

			foreach($data->rows as $measurement){

				if($measurement['product_id'] == $selected ){

					$html .= '<option value="'.$measurement['product_id'].'" selected="selected">'.$measurement['measurement'].'</option>';

				}else{

					$html .= '<option value="'.$measurement['product_id'].'" >'.$measurement['measurement'].'</option>';

				}

			}

			$html .= '</select>';

		}

		return $html;

	}

	

	

	public function getCountryCombo($selected=""){

		$sql = "SELECT * FROM " . DB_PREFIX . "country WHERE status = '1' AND is_delete = '0'";

		$data = $this->query($sql);

		$html = '';

		if($data->num_rows){

			//return $data->rows;

			$html = '';

			$html .= '<select name="country_id" id="country_id" class="form-control validate[required]" style="width:70%" >';

					$html .= '<option value="">Select Country</option>';

			foreach($data->rows as $country){

				if($country['country_id'] == $selected ){

					$html .= '<option value="'.$country['country_id'].'" selected="selected">'.$country['country_name'].'</option>';

				}else{

					$html .= '<option value="'.$country['country_id'].'" >'.$country['country_name'].'</option>';

				}

			}

			$html .= '</select>';

		}

		return $html;

	}
	
	

	public function uniqUserName($user_name,$user_id="",$user_type_id=""){

		if($user_id > 0 && $user_type_id){

			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "account_master` WHERE user_name = '".$user_name."' AND user_id != '".(int)$user_id."' AND user_type_id != '".(int)$user_type_id."'";

		}else{

			

			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "account_master` WHERE user_name = '".$user_name."'";

		}

		$data = $this->query($sql);

		//printr($data->rows);die;

		if($data->row['total'] > 0){

			return false;

		}else{

			return true;

		}

	}

	

	public function getUserProfileImage($user_type_id,$user_id,$size){

		//echo $user_type_id;
       $selType='';
		if($user_type_id == 2){

			$selType = "profile_image";

			$data = $this->query("SELECT $selType FROM " . DB_PREFIX . "employee WHERE employee_id = '".(int)$user_id."'");

			$upload_path = DIR_UPLOAD.'admin/profile/employee/';

			$http_upload = HTTP_UPLOAD.'admin/profile/employee/';

		}elseif($user_type_id == 3){

			$selType = "profile_image";

			$data = $this->query("SELECT $selType FROM " . DB_PREFIX . "client WHERE client_id = '".(int)$user_id."'");

			$upload_path = DIR_UPLOAD.'admin/profile/client/';

			$http_upload = HTTP_UPLOAD.'admin/profile/client/';

		}elseif($user_type_id == 4){

			$selType = "logo";

			$data = $this->query("SELECT $selType FROM " . DB_PREFIX . "international_branch WHERE international_branch_id = '".(int)$user_id."'");

			$upload_path = DIR_UPLOAD.'admin/logo/';

			$http_upload = HTTP_UPLOAD.'admin/logo/';

		}elseif($user_type_id == 5){

			$selType = "logo";

			$data = $this->query("SELECT $selType FROM " . DB_PREFIX . "associate WHERE associate_id = '".(int)$user_id."'");

			$upload_path = DIR_UPLOAD.'admin/logo/';

			$http_upload = HTTP_UPLOAD.'admin/logo/';

			

		}elseif($user_type_id == 1){

			$selType = "profile_image";

			$data = $this->query("SELECT $selType FROM " . DB_PREFIX . "user WHERE user_id = '".(int)$user_id."'");

			$upload_path = DIR_UPLOAD.'admin/profile/user/';

			$http_upload = HTTP_UPLOAD.'admin/profile/user/';

		}
       // printr($selType);
		if(isset($data->row["$selType"]) && $data->row["$selType"] != '' && file_exists($upload_path.$size.$data->row["$selType"])){

//			printr($selType);

			$image = $http_upload.$size.$data->row["$selType"];

		}else{

			$image = HTTP_SERVER.'images/blank-user64x64.png';

		}

		return $image;

	}

	

	public function updateQuery($data){

		$sql = "UPDATE " . DB_PREFIX .$data['table']." SET ";

			foreach($data['update'] as $key=>$val){

				$sql .= "$key = '".$val."'";

			}

		$sql .= " WHERE ";	

			foreach($data['where'] as $key=>$val){

				$sql .= "$key = '".$val."'";

			}

		$this->query($sql);	

	}

	

	public function GetOrderList()

	{

		

		if($_SESSION['LOGIN_USER_TYPE']==2)

		{

			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";

			$dataadmin = $this->query($sqladmin);

			$cond =  'AND pto.admin_user_id = "'.$dataadmin->row['user_id'].'"';

		}

		elseif($_SESSION['LOGIN_USER_TYPE']==4)

		{

			$cond =  'AND pto.admin_user_id = "'.$_SESSION['ADMIN_LOGIN_SWISS'].'"';

		}

		elseif($_SESSION['LOGIN_USER_TYPE']==1)

		{

			$cond = '';

		}

		else

		{

			return false;

		}

		

		//$Sql = "SELECT template_order_id,quantity,p.product_id,p.product_name,currency_code,pto.price,t.status FROM " .DB_PREFIX . "template_order t,product as p ,product_template_order as pto, currency as c,product_template as pt WHERE pt.product_template_id=t.template_id  AND  t.product_id = p.product_id AND pt.currency = c.currency_id AND t.status != 1 AND end_date > NOW()  AND t.is_delete = 0 AND pto.order_id = t.product_template_order_id ".$cond." ORDER BY template_order_id DESC LIMIT 5";	

	 $Sql= "SELECT a.* FROM (SELECT t.client_id,client_name,template_order_id,quantity,p.product_id,p.product_name,currency_code,t.price,t.status FROM template_order t,product as p ,product_template_order as pto, currency as c,product_template as pt,client_details as cd WHERE pt.product_template_id=t.template_id  AND  t.product_id = p.product_id AND pt.currency = c.currency_id AND t.status != 1 AND end_date > NOW()  AND t.is_delete = 0 AND pto.order_id = t.product_template_order_id ".$cond." AND cd.client_id=t.client_id ORDER BY template_order_id DESC ) as a GROUP BY a.client_id ORDER BY template_order_id DESC LIMIT 5";

	// echo $Sql;

		$data = $this->query($Sql);

		

		//$Sql1 = "SELECT count(t.template_order_id) as count,t.status FROM " .DB_PREFIX . "template_order t,product as p , currency as c,product_template_order as pto,product_template as pt WHERE pt.product_template_id=t.template_id  AND t.product_id = p.product_id AND pt.currency = c.currency_id AND t.status != 1 AND end_date > NOW() AND t.is_delete = 0  AND pto.order_id = t.product_template_order_id ".$cond." ORDER BY template_order_id DESC ";	//	echo $Sql1;

		

		$Sql1 = "SELECT a.* FROM (SELECT count(t.template_order_id) as count,t.status,t.client_id FROM " .DB_PREFIX . "template_order t,product as p , currency as c,product_template_order as pto,product_template as pt,client_details as cd WHERE pt.product_template_id=t.template_id  AND t.product_id = p.product_id AND pt.currency = c.currency_id AND t.status != 1 AND end_date > NOW() AND t.is_delete = 0  AND pto.order_id = t.product_template_order_id ".$cond." AND cd.client_id=t.client_id ORDER BY template_order_id DESC) as a GROUP BY a.client_id";

		//echo $Sql1;

		$data1 = $this->query($Sql1);

		

		//printr($data1->row['count']);

		//die;

		$link ='';

		if($data->num_rows)

		{

			$count =$data1->row['count'];

			

			foreach($data->rows as $value)

			{

				$link .='<a href="#" class="media list-group-item" style="display: block;"> 

								<span class="media-body block m-b-none"><b>'.$value['client_name'].' : </b>'. preg_replace("/\([^)]+\)/","",$value['product_name']).'<br>

								<small class="text-muted">'.$value['quantity'].' Bags at '.$value['currency_code'].' '.$value['price'].' Per Bag</small></span> 

							</a>';

			}

		

		}

		else

		{

			$count = 0;

			$link ='';

		} //echo $data1->row['status'];

		//$count = '13';

		$result2 =' <ul class="nav navbar-nav hidden-xs"> 

							<li> 

								<div class="m-t m-b-small" id="panel-order"> 

									<a href="#" class="dropdown-toggle" data-toggle="dropdown">

								  <!--  fa fa-comment-o fa-fw fa-lg-->

										<i class=" text-default"><img src="'.HTTP_SERVER.'images/cart.jpg"></i>';

										if($count>0)

											$result2.='<b class="badge badge-notes bg-danger count-n" style="display: block;">'.$count.'</b>';

									$result2.='</a> ';

									if($count>0)

									{

								$result2.='<section class="dropdown-menu m-l-small m-t-mini"> 

									<section class="panel panel-large arrow arrow-top"> 

										<header class="panel-heading bg-white">

											<span class="h5">

												<strong>You have <span class="count-n" style="display: inline;">'.$count.'</span> Items</strong>

											</span>

										</header> 

										<div class="list-group">'.$link.'</div> 

                    <footer class="panel-footer text-small">';

					if($count>0)

					{

            			$result2 .=   '<a href="'.HTTP_ADMIN.'index.php?route=template_order&mod=cartlist_view">View all Items</a> ';

					}

                   $result2 .=   '</footer> 

     			</section> 

     		</section> ';

	}

     	$result2.='</div>

      </li>

    </ul>';

		return $result2;

	}

	//Create Insert Query For Save Domain Enquiry Data[26_11_2015(Thurs)][kinjal]

    public function add_domaindata($post,$referer,$file)

	{
		//if($post['domain_name']=='www.cupsandcontainers.sg')
		//printr($post['to_bcc']);die;
		
		
		if(!empty($file) && $file!='')
		{
    		$attachments[]= DIR_UPLOAD.'admin/career_resume/'.$file['attachments']['name'].'';
		//	printr(DIR_UPLOAD.'admin/career_resume/'.$file['attachments']['name'].'');die;
		}
		
		//referer_url save for posting url [kinjal : 1_12_2015 Tue]

		if(isset($post['address1']))

		{

			

			$post['address']=$post['address'].' '.$post['address1'].' '.$post['address2'].' '.$post['address3'];

		}

		$product_name='';

		$weight='';

		$number_bags ='';

		if(isset($post['product_name']) && isset($post['weight']) && isset($post['number_bags']))

		{

			$product_name=$post['product_name'];

			$weight = $post['weight'];

			$number_bags = $post['number_bags'];

		}
		//[kinjal]
		$cond='';
        if(isset($post['company_name']) && isset($post['address']))

		{
		    $cond=",company_name = '".$post['company_name']."',address = '".$post['address']."'";
		}
		 if(isset($post['selectpost']))
		    $cond.=",select_post = '".$post['selectpost']."'";
        if(isset($post['country']))
            $cond.=",country = '".$post['country']."'";
         
         if(isset($post['State']))
             $cond.=",state = '".$post['State']."'";
        if(isset($post['City']))
             $cond.=",city = '".$post['City']."'";
         
		if(empty($file) && $file=='')
		{
			$sql = "INSERT INTO domain_data SET  domain_name = '".$post['domain_name']."',from_email = '".$post['from_email']."',to_email = '".$post['to_email']."',thanks_url = '".$post['thanks_url']."',referer_url = '".$referer."',name = '".$post['name']."' $cond,phone_no = '".$post['phone_no']."',email = '".$post['email']."',product_name = '".$product_name."',weight = '".$weight."',number_bags = '".$number_bags."',message = '".addslashes($post['message'])."', date_added = NOW(),is_delete='0',status='1'";

			$this->query($sql);	
		}
		

		//[jayashree mam]

		$b='';

		$b =  $b ."You have got the Inquiry from<br>";

		$b =  $b . "\n"."Name : " . $post['name']."<br>";
        //[kinjal]
        if(isset($post['company_name']) && isset($post['address']))

		{
            //echo 'tyr';
	    	$b =  $b . "\n"."Company Name : " . $post['company_name']."<br>";
    
		    $b =  $b . "\n"."Address: " . $post['address']."<br>";
		    
        }
        
        if(isset($post['selectpost']))
		   $b =  $b . "\n"."Post: " . $post['selectpost']."<br>";
        
        if(isset($post['country']))
		    $b =  $b . "\n"."Country: " . $post['country']."<br>";
        
        if(isset($post['State']))
		    $b =  $b . "\n"."State: " . $post['State']."<br>";
		    
        if(isset($post['City']))
            $b =  $b . "\n"."City: " . $post['City']."<br>";
            
		$b =  $b . "\n"."Phone : " . $post['phone_no']."<br>";

		$b =  $b . "\n"."Email : " . $post['email']."<br>";

		if(isset($post['product_name']) && isset($post['weight']) && isset($post['number_bags']))

		{

			$b =  $b . "\n"."Name of the product to be filled inside the bags : " . $post['product_name']."<br>";

			$b =  $b . "\n"."Weight to be filled in each bags : " . $post['weight']."<br>";

			$b =  $b . "\n"."Number of bags / rolls required  : " . $post['number_bags']."<br>";

		}

		$b =  $b . "\n"."Remarks / Requirements  : " . $post['message']."<br>";

	

		

		$to = $post['to_email'];
        
        if(!empty($file) && $file!='')
            $subject = " Resume Post for ".$post['selectpost']."  from India Website";
        else
		    $subject = "Inquiry from ".$post['domain_name']." - " .date("d/m/y")." [ ".$post['name']." ]";
		
		//$subject = "Inquiry from pouchmakers.com - " .date("d/m/y");

		$message = $b;		

		$from =$post['email'];

		$headers = "From: ".$from;
        $to_bcc='0';
		if(isset($post['to_bcc']))
		    $to_bcc='1';
		    
		if(!empty($file) && $file!='')
		    send_email($to,$from,$subject,$message,$attachments,'',1);
		else
		    send_email($to,$from,$subject,$message,'','','',$to_bcc);
		


					

	}
	public function send_store_invoice_mail($email_temp,$data_no)

	{

		//printr($email_temp);//die;

		$formEmail = $email_temp[0]['formEmail'];	

		//$formEmail = 'p.kju99@yahoo.in';

		$obj_email = new email_template();

		//for Invoice & dispatch invoice email =>(0), Welcome Mail & contact mail =>(1);

		if($data_no == '0')

			$rws_email_template = $obj_email->get_email_template(6); //Offline id = 8 & Online id= 6

		else if($data_no == '1')

			$rws_email_template = $obj_email->get_email_template(7);//Offline id = 9 & Online id= 7

			

		$firstTimeemial = 0;

		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				

		$path = HTTP_SERVER."template/pouchmakerstemplate.html";

		$output = file_get_contents($path);  

		$search  = array('{tag:header}','{tag:details}');

		$signature = 'Thanks.';

		//printr($email_temp);

		//$email_temp[]=array('html'=>$email_temp[0]['html'],'email'=>$email_temp[0]['email'],'subject'=>$email_temp[0]['subject']);

		//printr($email_temp);

		foreach($email_temp as $val)

		{	

			if($val['email'] == '')

			{

				$toEmail = $formEmail;

				$firstTimeemial = 1;

			}				

			$subject = $val['subject'];

			$message = '';

			if($val['html'])

			{

				$tag_val = array(

					"{{PouchMakersDetail}}" =>$val['html'],

					"{{signature}}"	=> $signature,

				);

				if(!empty($tag_val))

				{

					$desc =$temp_desc;

					foreach($tag_val as $k=>$v)

					{

						@$desc = str_replace(trim($k),trim($v),trim($desc));

					} 

				}

				$replace = array($subject,$desc);

				$message = str_replace($search, $replace, $output);

				//printr($message);

			} 

			$s = send_email($val['email'],$formEmail,$subject,$message,'');

			//return $s;//die;

		}

	}
	//[kinjal] made on 6-6-2017 for testing cart
	public function GetTestOrderList()
	{
		
		if($_SESSION['LOGIN_USER_TYPE']==2)
		{
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
			$dataadmin = $this->query($sqladmin);
			$cond =  'AND pto.admin_user_id = "'.$dataadmin->row['user_id'].'"';
		}
		elseif($_SESSION['LOGIN_USER_TYPE']==4)
		{
			$cond =  'AND pto.admin_user_id = "'.$_SESSION['ADMIN_LOGIN_SWISS'].'"';
		}
		elseif($_SESSION['LOGIN_USER_TYPE']==1)
		{
			$cond = '';
		}
		else
		{
			return false;
		}
		
		//$Sql = "SELECT template_order_id,quantity,p.product_id,p.product_name,currency_code,pto.price,t.status FROM " .DB_PREFIX . "template_order t,product as p ,product_template_order as pto, currency as c,product_template as pt WHERE pt.product_template_id=t.template_id  AND  t.product_id = p.product_id AND pt.currency = c.currency_id AND t.status != 1 AND end_date > NOW()  AND t.is_delete = 0 AND pto.order_id = t.product_template_order_id ".$cond." ORDER BY template_order_id DESC LIMIT 5";	
	 $Sql= "SELECT a.* FROM (SELECT t.client_id,client_name,template_order_id,quantity,p.product_id,p.product_name,currency_code,t.price,t.status FROM template_order_test t,product as p ,product_template_order_test as pto, currency as c,product_template as pt,client_details as cd WHERE pt.product_template_id=t.template_id  AND  t.product_id = p.product_id AND pt.currency = c.currency_id AND t.status != 1 AND end_date > NOW()  AND t.is_delete = 0 AND pto.order_id = t.product_template_order_id ".$cond." AND cd.client_id=t.client_id ORDER BY template_order_id DESC ) as a GROUP BY a.client_id ORDER BY template_order_id DESC LIMIT 5";
	 //if($_SESSION['LOGIN_USER_TYPE']==1 && $_SESSION['ADMIN_LOGIN_SWISS']==1)
	    //echo $Sql;
		$data = $this->query($Sql);
		
		//$Sql1 = "SELECT count(t.template_order_id) as count,t.status FROM " .DB_PREFIX . "template_order t,product as p , currency as c,product_template_order as pto,product_template as pt WHERE pt.product_template_id=t.template_id  AND t.product_id = p.product_id AND pt.currency = c.currency_id AND t.status != 1 AND end_date > NOW() AND t.is_delete = 0  AND pto.order_id = t.product_template_order_id ".$cond." ORDER BY template_order_id DESC ";	//	echo $Sql1;
		
		$Sql1 = "SELECT a.* FROM (SELECT count(t.template_order_id) as count,t.status,t.client_id FROM " .DB_PREFIX . "template_order_test t,product as p , currency as c,product_template_order_test as pto,product_template as pt,client_details as cd WHERE pt.product_template_id=t.template_id  AND t.product_id = p.product_id AND pt.currency = c.currency_id AND t.status != 1 AND end_date > NOW() AND t.is_delete = 0  AND pto.order_id = t.product_template_order_id ".$cond." AND cd.client_id=t.client_id ORDER BY template_order_id DESC) as a GROUP BY a.client_id";
	//echo $Sql1;
		$data1 = $this->query($Sql1);
		//if($_SESSION['LOGIN_USER_TYPE']==1 && $_SESSION['ADMIN_LOGIN_SWISS']==1)
	        //echo $Sql1;
		//printr($data1->row['count']);
		//die;
		$link ='';
		if($data->num_rows)
		{
			$count =$data1->row['count'];
			
			foreach($data->rows as $value)
			{
				$link .='<a href="#" class="media list-group-item" style="display: block;"> 
								<span class="media-body block m-b-none"><b>'.$value['client_name'].' : </b>'. preg_replace("/\([^)]+\)/","",$value['product_name']).'<br>
								<small class="text-muted">'.$value['quantity'].' Bags at '.$value['currency_code'].' '.$value['price'].' Per Bag</small></span> 
							</a>';
			}
		
		}
		else
		{
			$count = 0;
			$link ='';
		} //echo $data1->row['status'];
		//$count = '13';
		$result2 =' <ul class="nav navbar-nav hidden-xs"> 
							<li> 
								<div class="m-t m-b-small" id="panel-order"> 
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								  <!--  fa fa-comment-o fa-fw fa-lg-->
										<i class=" text-default"><img src="'.HTTP_SERVER.'images/cart.jpg"></i>';
										if($count>0)
											$result2.='<b class="badge badge-notes bg-danger count-n" style="display: block;">'.$count.'</b>';
									$result2.='</a> ';
									if($count>0)
									{
								$result2.='<section class="dropdown-menu m-l-small m-t-mini"> 
									<section class="panel panel-large arrow arrow-top"> 
										<header class="panel-heading bg-white">
											<span class="h5">
												<strong>You have <span class="count-n" style="display: inline;">'.$count.'</span> Items</strong>
											</span>
										</header> 
										<div class="list-group">'.$link.'</div> 
                    <footer class="panel-footer text-small">';
					if($count>0)
					{
            			$result2 .=   '<a href="'.HTTP_ADMIN.'index.php?route=template_order_test&mod=cartlist_view">View all Items</a> ';
					}
                   $result2 .=   '</footer> 
     			</section> 
     		</section> ';
	}
     	$result2.='</div>
      </li>
    </ul>';
		return $result2;
	}

    public function getLogin($user_id,$user_type_id)
	{
		if($user_type_id=='2')
			$sql="SELECT associate_acnt FROM employee WHERE employee_id='".$user_id."'";
		else
			$sql="SELECT associate_acnt FROM international_branch WHERE international_branch_id='".$user_id."'";
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows)
		{
			return $data->row;
		}
		else
		{
			return false;
		}
	}
    public function getUserData($user_type_id,$user_id)
	{
		$sql = "SELECT user_name,password_text,user_name FROM " . DB_PREFIX . "account_master WHERE user_type_id = '".$user_type_id."' AND user_id='".$user_id."'";
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows)
			return $data->row;
		else
			return false;
	}
	
	// new calendar final 7-11-2017 [gaurav]
	public function GetCal()
	{

		if($_SESSION['LOGIN_USER_TYPE']==2)
		{
			//$newquery="select e.* from enquiry_followup as e,account_master as am where am.user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."' AND e.followup_date=CURDATE() ORDER BY e.enquiry_followup_id DESC LIMIT 5";
			$sql = "select * from enquiry_followup  where user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."' AND enquiry_note !='' AND user_type_id='".$_SESSION['LOGIN_USER_TYPE']."' AND ((followup_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 2 DAY)AND CURDATE()) OR (followup_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 2 DAY))) AND is_delete=0 ORDER BY enquiry_followup_id DESC ";

		}
		elseif($_SESSION['LOGIN_USER_TYPE']==4)
		{
			//$newquery="select e.* from enquiry_followup as e,account_master as am where am.user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."' AND e.followup_date=CURDATE() ORDER BY e.enquiry_followup_id DESC LIMIT 5";

			$sql = "select * from enquiry_followup  where user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."'  AND enquiry_note !='' AND user_type_id='".$_SESSION['LOGIN_USER_TYPE']."' AND ((followup_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 2 DAY)AND CURDATE()) OR (followup_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 2 DAY))) AND is_delete=0 ORDER BY enquiry_followup_id DESC ";
		}
		elseif($_SESSION['LOGIN_USER_TYPE']==1)
		{ //echo "hi";
			//$newquery="select * from enquiry_followup where is_delete = 0 ORDER BY chat_master_id DESC LIMIT 5";
			$sql = "select * from enquiry_followup where ((followup_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 2 DAY)AND CURDATE()) OR (followup_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 2 DAY)))  AND enquiry_note !='' AND  is_delete=0  ORDER BY enquiry_followup_id DESC ";
			//print_r($sql);
		}
		else
		{
			return false;
		}
		//print_r($sql);
			$sql2 = "select * from task_management where assign_to_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."' AND assign_to_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."' AND ((due_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 2 DAY)AND CURDATE()) OR (due_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 2 DAY))) AND is_delete=0 AND task_status <> 0 ORDER BY task_management_id DESC ";
		//print_r($sql2);
		$data2 = $this->query($sql2);
		$data = $this->query($sql);
		//print_r($data);
		$link ='';
		if($data->num_rows)
		{
			$count =$data->num_rows;
			
			$totalcount=$data2->num_rows+$count;
			
			
			$i=1;
			
			foreach (array_slice($data->rows, 0, 5) as $value) { //printr($data->num_rows);

					
					if ($value['enquiry_id'] == 0) {
						$link .= '<a href="'.HTTP_ADMIN.'index.php?route=followup_calender" class="media list-group-item" style="display: block;"> 
						<span class="pull-left thumb-small">
						<i class="fa fa-calendar fa-2x text-default">
						</i>
						</span> 
						<span class="media-body block m-b-none">' . $value['enquiry_note'] . '<i class="label bg-warning pull-right" style="font-size:10px;">FollowUp</i>
						<br>
						<small class="text-muted"><span class="" style="font-size:10px;">' . dateFormat("4", $value['followup_date']) . '</span></small>
						</span> 
						</a>';
					} elseif(!empty($value['enquiry_note'])){
						$link .= '<a href="'.HTTP_ADMIN.'index.php?route=followup_calender" class="media list-group-item" style="display: block;"> 
						<span class="pull-left thumb-small">
						<i class="fa  fa-question-circle fa-2x text-default">
						</i>
						</span> 
						<span class="media-body block m-b-none">' . $value['enquiry_note'] . '<i class="label bg-info pull-right" style="font-size:10px;">Enquiry</i>
						<br>
						<small class="text-muted"><span class="" style="font-size:10px;">' . dateFormat("4", $value['followup_date']) . '</span></small>
						</span> 
						</a>';
					}

				}
				foreach (array_slice($data2->rows, 0, 5) as $value) { //printr($data->num_rows);

					
					
						$link .= '<a href="'.HTTP_ADMIN.'index.php?route=task_management" class="media list-group-item" style="display: block;"> 
						<span class="pull-left thumb-small">
						<i class="fa fa-pencil-square-o fa-2x text-default">
						</i>
						</span> 
						<span class="media-body block m-b-none">' . $value['task_name'] . '<i class="label bg-inverse  pull-right" style="font-size:10px;">Task</i>
						<br>
						<small class="text-muted"><span class="" style="font-size:10px;">Due Date ' . dateFormat("4", $value['due_date']) . '</span></small>
						</span> 
						</a>';
					

				}



		}
		else
		{
			$count = 0;
			$link ='';
		} //echo $data1->row['status'];
		//$count = '13';
		//echo $link;
		$result2 =' <ul class="nav navbar-nav hidden-xs">
							<li>
								<div class="m-t m-b-small" id="panel-order">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								  <!--  fa fa-comment-o fa-fw fa-lg-->
										<i class=" text-default"><img src="'.HTTP_SERVER.'images/images.png" height="22" width="22"></i>';
		if($count>0)
			$result2.='<b class="badge badge-notes bg-danger count-n" style="display: block;">'.$totalcount.'</b>';
		$result2.='</a> ';
		if($count>0)
		{
			$result2.='<section class="dropdown-menu m-l-small m-t-mini">
									<section class="panel panel-large arrow arrow-top">
										<header class="panel-heading bg-white">
											<span class="h5">
												<strong>Notification</strong>
											</span>
										</header>
										<div class="list-group">'.$link.'</div>
                    <footer class="panel-footer text-small">';
			if($count>0)
			{
				$result2 .=   '<a href="'.HTTP_ADMIN.'index.php?route=followup_calender"><strong>View Calendar</strong></a> ';
			}
			$result2 .=   '</footer>
     			</section>
     		</section> ';
		}
		$result2.='</div>
      </li>
    </ul>';
		return $result2;
	}

	//end cal
	
	// add for leave 26-7-2017
	public function getUserEmployeeIds($user_type_id,$user_id){

			$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' 

			AND user_id = '".(int)$user_id."'";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->row['ids'];

			}else{

				return false;

			}

		}
	
		public function Getnotification()
	{
	
	
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
        $str='';
		if($user_type_id == 2){

			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");

			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);

			$set_user_id = $parentdata->row['user_id'];

			$set_user_type_id = $parentdata->row['user_type_id'];
			if($userEmployee)
			$str = ' OR ( l.user_id IN ('.$userEmployee.') AND l.user_type_id = 2 )';
			
			$sql = "SELECT l.*, am.user_name FROM leave_application l , account_master am WHERE am.user_id = l.user_id AND am.user_type_id =l.user_type_id AND approval_status=2 AND l.user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'ORDER BY leave_id DESC ";


		}
		else if($user_type_id == 4)
		{

			$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);

			$set_user_id = $user_id;
			$set_user_type_id = $user_type_id;
			
			if($userEmployee)
			$str = ' OR ( l.user_id IN ('.$userEmployee.') AND l.user_type_id = 2 )';
			
			$sql = "SELECT l.*, am.user_name FROM leave_application l , account_master am WHERE am.user_id = l.user_id AND am.user_type_id =l.user_type_id AND approval_status=2 AND ((l.user_id='".$set_user_id ."' AND l.user_type_id='".$set_user_type_id ."') $str  )  ORDER BY leave_id DESC ";

		}

		else

		{
			$sql = "SELECT l.*, am.user_name FROM leave_application l , account_master am WHERE am.user_id = l.user_id AND am.user_type_id =l.user_type_id AND approval_status=2 AND is_delete = 0 ORDER BY leave_id DESC ";
		}
		
		
			$data = $this->query($sql);

		$link ='';
		if($data->num_rows)
		{
			$count =$data->num_rows;
			$i=1;
			
			foreach($data->rows as $value)
			{
				$link .='<a href="'.HTTP_ADMIN.'index.php?route=leave_type_detail" class="media list-group-item" style="display: block;"><span class="pull-left thumb-small">
				<i class="fa fa-envelope-o fa-2x text-default"></i></span> 
			<span class="media-body block m-b-none"><b>'.$value['user_name'].'</b></a>';
			// Swisspac =user_name

							
			}

		}
		else
		{
			$count = 0;
			$result2='';
		}
		$result2 =' <ul class="nav navbar-nav hidden-xs  navbar-avatar pull-right">
							<li>
								<div class="m-t m-b-small" id="panel-order">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								  <!--  fa fa-comment-o fa-fw fa-lg-->
										<i class="fa fa-envelope fa-2x text-default"></i>';
		if($count>0)
			$result2.='<b class="badge badge-notes bg-danger count-n" style="display: block;">'.$count.'</b>';
		$result2.='</a> ';
		if($count>=0)
		{
			$result2.='<section class="dropdown-menu m-l-small m-t-mini">
									<section class="panel panel-large arrow arrow-top" >
										<header class="panel-heading bg-white">
											<span class="h5">
												<strong>You have <span class="count-n" style="display: inline;">'.$count.'</span> Leave Application</strong>
											</span>
										</header>
										<div class="list-group">'.$link.'</div>
                    <footer class="panel-footer text-small">';
			if($count>=0)
			{
				
			$result2 .=   '</footer>
     			</section>
     		</section> ';
		}
		$result2.='</div>
      </li>
    </ul>';
		return $result2;
		}
	}
	
	
	
	
//End leave	
	
	
	
	
	
	
	

}

?>