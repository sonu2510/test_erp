<?php

class address_book extends dbclass {

    public function add_product_customer($data, $logo) {
       // printr($data);die;

        $this->query("INSERT INTO " . DB_PREFIX . "address_book_master SET company_name='" . $data['company_name'] . "', contact_name = '" . $data['contact_name'] . "',designation = '" . $data['designation'] . "',department = '" . $data['department'] . "',website = '" . $data['website'] . "', exhibition_id = '" . $data['exhibition'] . "', remark = '" . $data['remark'] . "',vat_no = '" . $data['vat_no'] . "', logo = '" . $logo . "', user_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "',user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "',status = '" . $data['status'] . "', date_added = NOW(),date_modify = NOW(), industry='".$data['industry']."'");
        $address_id = $this->getLastId();

        foreach ($data['company'] as $company) {
            $this->query("INSERT INTO " . DB_PREFIX . "company_address SET address_book_id = '" . $address_id . "', c_address = '" . $company['c_address'] . "', city = '" . $company['city'] . "',state = '" . $company['state'] . "',country = '" . $company['country'] . "',pincode = '" . $company['pincode'] . "',phone_no = '" . $company['phone_no'] . "',email_1 = '" . $company['email_1'] . "',email_2 = '" . $company['email_2'] . "',  user_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "', user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "',	date_added = NOW(),date_modify = NOW()");
            $company_address_id = $this->getLastId();
        }
        foreach ($data['factory'] as $factory) {
            $this->query("INSERT INTO " . DB_PREFIX . "factory_address SET address_book_id = '" . $address_id . "', f_address = '" . $factory['f_address'] . "', city = '" . $factory['city'] . "',state = '" . $factory['state'] . "',country = '" . $factory['country'] . "',pincode = '" . $factory['pincode'] . "',phone_no = '" . $factory['phone_no'] . "',email_1 = '" . $factory['email_1'] . "',email_2 = '" . $factory['email_2'] . "',  user_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "', user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "', date_added = NOW(),date_modify = NOW()");
            $factory_address_id = $this->getLastId();
        }
        return $address_id;
    }

    public function update_address_recode($data) {
        $address_id = $data['address_id'];
//printr($data);die;
        $this->query(" UPDATE  address_book_master SET company_name='" . $data['company_name'] . "', contact_name = '" . $data['contact_name'] . "',designation = '" . $data['designation'] . "',department = '" . $data['department'] . "',website = '" . $data['website'] . "', remark = '" . $data['remark'] . "',vat_no = '" . $data['vat_no'] . "',exhibition_id = '" . $data['exhibition'] . "', user_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "',user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "',status = '" . $data['status'] . "', date_added = NOW(),date_modify = NOW(), industry='".$data['industry']."' WHERE address_book_id ='" . $address_id . "' AND is_delete = '0' ");

        foreach ($data['company'] as $company) {
            if (isset($company['company_address_id']) && ($company['company_address_id'] != '')) {
                $this->query("UPDATE  company_address  SET c_address = '" . $company['c_address'] . "', city = '" . $company['city'] . "',state = '" . $company['state'] . "',country = '" . $company['country'] . "',pincode = '" . $company['pincode'] . "',phone_no = '" . $company['phone_no'] . "', email_1 = '" . $company['email_1'] . "', email_2 = '" . $company['email_2'] . "',date_added = NOW(),date_modify = NOW() WHERE  company_address_id = '" . $company['company_address_id'] . "'");
            } else {
                $this->query("INSERT INTO " . DB_PREFIX . "company_address SET address_book_id = '" . $address_id . "', c_address = '" . $company['c_address'] . "', city = '" . $company['city'] . "',state = '" . $company['state'] . "',country = '" . $company['country'] . "',pincode = '" . $company['pincode'] . "',phone_no = '" . $company['phone_no'] . "',email_1 = '" . $company['email_1'] . "',email_2 = '" . $company['email_2'] . "',  user_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "', user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "',	date_added = NOW(),date_modify = NOW()");
                $company_address_id = $this->getLastId();
            }
        }

        foreach ($data['factory'] as $factory) {
            if (isset($factory['factory_address_id']) && ($factory['factory_address_id'] != '')) {    //printr($factory);die;
                $this->query("UPDATE  factory_address  SET address_book_id = '" . $address_id . "', f_address = '" . $factory['f_address'] . "', city = '" . $factory['city'] . "',state = '" . $factory['state'] . "',country = '" . $factory['country'] . "',pincode = '" . $factory['pincode'] . "',phone_no = '" . $factory['phone_no'] . "',email_1 = '" . $factory['email_1'] . "', email_2 = '" . $factory['email_2'] . "',date_added = NOW(),date_modify = NOW() WHERE address_book_id = '" . $address_id . "' AND factory_address_id = '" . $factory['factory_address_id'] . "'");
            } else {
                $this->query("INSERT INTO " . DB_PREFIX . "factory_address SET address_book_id = '" . $address_id . "', f_address = '" . $factory['f_address'] . "', city = '" . $factory['city'] . "',state = '" . $factory['state'] . "',country = '" . $factory['country'] . "',pincode = '" . $factory['pincode'] . "',phone_no = '" . $factory['phone_no'] . "',email_1 = '" . $factory['email_1'] . "',email_2 = '" . $factory['email_2'] . "',  user_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "', user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "', date_added = NOW(),date_modify = NOW()");
                $factory_address_id = $this->getLastId();
            }
        }

       /* if (isset($data['customer_product'])) {
            $this->query(" DELETE FROM  address_product_category  WHERE address_book_id ='" . $address_id . "'");
            foreach ($data['customer_product'] as $customer_product) {
                $this->query("INSERT INTO " . DB_PREFIX . "address_product_category SET address_book_id = '" . $address_id . "',customer_product_cat_id = '" . $customer_product . "', date_added = NOW(),date_modify = NOW()");
            }
        }*/
        return $address_id;
    }

    function get_country() {
        $sql = "SELECT * from country WHERE is_delete ='0' ORDER BY country_id ASC";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }

    public function get_active_customer_product() {
        $data = $this->query("SELECT * FROM `" . DB_PREFIX . "customer_product_category` WHERE status='1' AND is_delete = '0' ORDER BY product_name ASC");

        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }

    public function getProductcompany() {


        $sql = "SELECT * FROM `company_address` WHERE is_delete = 0 ORDER BY right(volume, 0)";

        $data = $this->query($sql);
        //printr($data);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }

    public function get_customer_total_address($filter_data = array(),$user_id,$user_type_id) {
       
        if($user_id=='' && $user_type_id=='')
        {
    		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
            $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
        }
       // printr($filter_data);
        //[kinjal] : changed on 24-6-2017
		//$sql= "SELECT COUNT(e.address_book_id) as total,am.user_name, e.*  FROM address_book_master e LEFT JOIN company_address as c ON (e.address_book_id=c.address_book_id) LEFT JOIN factory_address as f ON (e.address_book_id=f.address_book_id) LEFT JOIN account_master as am ON (e.user_id = am.user_id) AND (e.user_type_id = am.user_type_id) WHERE e.address_book_id =c.address_book_id AND e.is_delete='0' ";//AND e.user_id = am.user_id AND e.user_type_id = am.user_type_id AND(e.address_book_id = f.address_book_id)
		
		 $sql = "SELECT COUNT(e.address_book_id) as total,am.user_name, e.*  FROM `" . DB_PREFIX . "address_book_master` as e, company_address as c,account_master as am WHERE e.address_book_id = c.address_book_id AND e.user_id = am.user_id AND e.user_type_id = am.user_type_id AND e.is_delete = 0";

		//$sql= "SELECT  COUNT(e.address_book_id) as total,am.user_name, e.* FROM `address_book_master` as e ,company_address as c ,factory_address as f,account_master as am WHERE e.`address_book_id` =c.`address_book_id` AND e.`is_delete`='0' AND e.user_id = am.user_id AND e.user_type_id = am.user_type_id AND(e.address_book_id = f.address_book_id) ";        //echo $sql;die;
        if ($user_type_id != 1 && $user_id != 1) {
            if ($user_type_id == 2) {
                $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");
                $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
				 //$sql .= " AND e.user_id = '" . (int) $user_id . "' AND e.user_type_id = '" . (int) $user_type_id."'";
				 $set_user_id=$parentdata->row['user_id'];
				 $set_user_type_id = $parentdata->row['user_type_id'];
            } else {
                $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);
				$set_user_id=$user_id;
				 $set_user_type_id = $user_type_id;
            }
            $sql .= " AND( e.user_id = '" . (int) $set_user_id . "' AND e.user_type_id = '" . (int) $set_user_type_id . "' OR(e.user_id IN (".$userEmployee.") AND e.user_type_id='2'))";
        }

            if (!empty($filter_data)) {
                if (!empty($filter_data['customer'])) {
                    $sql .= " AND e.contact_name LIKE '%" . $filter_data['customer'] . "%' ";
                }

                if (!empty($filter_data['company'])) {
                    $sql .= " AND e.company_name LIKE '%" . $filter_data['company'] . "%' ";
                }
                if ($filter_data['vat'] != '') {
                    $sql .= " AND e.vat_no = '" . $filter_data['vat'] . "' ";
                }
                if ($filter_data['status'] != '') {
                    $sql .= " AND e.status = '" . $filter_data['status'] . "' ";
                }

                if ($filter_data['website'] != '') {
                    $sql .= " AND e.website LIKE '%" . $filter_data['website'] . "%' ";
                }
                if ($filter_data['email'] != '') {
                    $sql .= " AND c.email_1 LIKE '%" . $filter_data['email'] . "%' ";
                }  if ($filter_data['user_name'] != '') {
                      $arr=explode("=",$filter_data['user_name']);
                      $sql .= " AND e.user_id = '" . $arr[1] . "' AND e.user_type_id='".$arr[0]."' ";
                }
            }
          // echo $sql;die; 
        $data = $this->query($sql);
    
        if ($data->num_rows > 0) {
            return $data->row['total'];
        } else { 
            return false;
        }
    }

    public function get_customer_address($data, $filter_data = array(),$user_id,$user_type_id,$n=array()) {
        
     //   printr($filter_data);
        if($user_id=='' && $user_type_id=='')
        {
    		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
            $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
        }

        //$sql = "SELECT am.user_name, e.*,c.email_1  FROM `" . DB_PREFIX . "address_book_master` e LEFT JOIN `" . DB_PREFIX . "company_address` c ON (e.address_book_id = c.address_book_id) LEFT JOIN `" . DB_PREFIX . "factory_address` f ON (e.address_book_id = f.address_book_id) LEFT JOIN `" . DB_PREFIX . "account_master` am ON (e.user_id = am.user_id) AND (e.user_type_id = am.user_type_id) WHERE e.is_delete = 0";
        $con = '';
        if(!empty($n))
        {
            $con = "AND e.date_added >= '" . $n['f_date'] . "' AND  DATE(e.date_added) <='" . $n['t_date'] . "' ";
            
        }
    
        $sql = "SELECT am.user_name, e.*,c.*,e.user_id as address_user_id,e.user_type_id as address_user_type_id  FROM `" . DB_PREFIX . "address_book_master` as e, company_address as c, account_master as am WHERE e.address_book_id = c.address_book_id  AND e.user_id = am.user_id AND e.user_type_id = am.user_type_id AND e.is_delete = 0 $con";
    
        if ($user_type_id != 1 && $user_id != 1) {
            if ($user_type_id == 2) {
                $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");
                $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
                $set_user_id=$parentdata->row['user_id'];
				 $set_user_type_id = $parentdata->row['user_type_id'];
				// $sql .= " AND e.user_id = '" . (int) $user_id . "' AND e.user_type_id = '" . (int) $user_type_id."'";
            } else {
                $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);
                $set_user_id=$user_id;
				 $set_user_type_id = $user_type_id;
				//$sql .= " AND( e.user_id = '" . (int) $user_id . "' AND e.user_type_id = '" . (int) $user_type_id . "' OR(e.user_id IN (".$userEmployee.") AND e.user_type_id='2'))";
            }
            $sql .= " AND( e.user_id = '" . (int) $set_user_id . "' AND e.user_type_id = '" . (int) $set_user_type_id . "' OR(e.user_id IN (".$userEmployee.") AND e.user_type_id='2'))";
        }

            if (!empty($filter_data)) {
                if (!empty($filter_data['customer'])) {
                    $sql .= " AND e.contact_name LIKE '%" . $filter_data['customer'] . "%' ";
                }

                if (!empty($filter_data['company'])) {
                    $sql .= " AND e.company_name LIKE '%" . $filter_data['company'] . "%' ";
                }
                if ($filter_data['vat'] != '') {
                    $sql .= " AND e.vat_no = '" . $filter_data['vat'] . "' ";
                }
                if ($filter_data['status'] != '') {
                    $sql .= " AND e.status = '" . $filter_data['status'] . "' ";
                }

                if ($filter_data['website'] != '') {
                    $sql .= " AND e.website LIKE '%" . $filter_data['website'] . "%' ";
                }
                if ($filter_data['email'] != '') {
                    $sql .= " AND c.email_1 LIKE '%" . $filter_data['email'] . "%' ";
                }
                 if ($filter_data['user_name'] != '') {
                     $arr=explode("=",$filter_data['user_name']);
                      $sql .= " AND e.user_id = '" . $arr[1] . "' AND e.user_type_id='".$arr[0]."' ";
                }
            }
        //echo $sql;die;
        $sql .= " GROUP by e.address_book_id";
        if (isset($data['sort'])) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY e.address_book_id ";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }
     //   echo $sql;
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }
        /*else
            $sql .= " LIMIT 100";*/
///echo $sql;//die;
        $data = $this->query($sql);
        //printr($data);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }

    public function all_customer_address($address_id) {

        $sql = "SELECT * FROM `" . DB_PREFIX . "address_book_master` WHERE address_book_id = '" . $address_id . "' AND is_delete = '0'";
        $data = $this->query($sql);
        if ($data->row) {
            $address_company_query = "SELECT * FROM " . DB_PREFIX . "company_address c  WHERE c.address_book_id='" . $address_id . "' AND c.is_delete = '0' ";
           // echo $address_company_query;die;
            $address_company_data = $this->query($address_company_query);

            $company_array = array();

            foreach ($address_company_data->rows as $company) {
                $company_array[] = array(
                    'address_book_id' => $data->row['address_book_id'],
                    'company_address_id' => $company['company_address_id'],
                    'c_address' => $company['c_address'],
                    'city' => $company['city'],
                    'state' => $company['state'],
                    'country' => $company['country'],
                    'pincode' => $company['pincode'],
                    'phone_no' => $company['phone_no'],
                    'email_1' => $company['email_1'],
                    'email_2' => $company['email_2'],
                    'date_added' => $company['date_added'],
                    'date_modify' => $company['date_modify'],
                    'is_delete' => $company['is_delete'],
                    //'country_id' => $company['country'],
                    //'country_name' => $company['country_name'],
                );
            }

            //factory
            $factory_query = "SELECT * FROM " . DB_PREFIX . "factory_address f WHERE f.address_book_id='" . $address_id . "' AND 	f.is_delete='0'";
            $factory_data = $this->query($factory_query);

            $factory_array = array();

            foreach ($factory_data->rows as $factory) {
                $factory_array[] = array(
                    'address_book_id' => $data->row['address_book_id'],
                    'factory_address_id' => $factory['factory_address_id'],
                    'f_address' => $factory['f_address'],
                    'city' => $factory['city'],
                    'state' => $factory['state'],
                    'country' => $factory['country'],
                    'pincode' => $factory['pincode'],
                    'phone_no' => $factory['phone_no'],
                    'email_1' => $factory['email_1'],
                    'email_2' => $factory['email_2'],
                    'date_added' => $factory['date_added'],
                    'date_modify' => $factory['date_modify'],
                    'is_delete' => $factory['is_delete'],
                    //'country_id' => $factory['country'],
                   // 'country_name' => $factory['country_name'],
                );
            }

            //product category
            //$product_cat_query = "SELECT * FROM " . DB_PREFIX . "address_product_category a join customer_product_category c on a.customer_product_cat_id = c.customer_product_cat_id WHERE a.address_book_id='" . $address_id . "' AND c.is_delete='0'";

            //$product_cat_data = $this->query($product_cat_query);

           // $product_cat_array = array();

           // foreach ($product_cat_data->rows as $product_cat) {
              //  $product_cat_array[] = array(
                //    'customer_product_cat_id' => $product_cat['customer_product_cat_id'],
               // );
           // }

            $address_array = array(
                'address_book_id' => $data->row['address_book_id'],
                'company_name' => $data->row['company_name'],
                'contact_name' => $data->row['contact_name'],
                'designation' => $data->row['designation'],
                'department' => $data->row['department'],
                'website' => $data->row['website'],
                'remark' => $data->row['remark'],
				'industry' => $data->row['industry'],
                'vat_no' => $data->row['vat_no'],
                'exhibition_id' => $data->row['exhibition_id'],
                'logo' => $data->row['logo'],
                'user_id' => $data->row['user_id'],
                'user_type_id' => $data->row['user_type_id'],
                'status' => $data->row['status'],
                'date_added' => $data->row['date_added'],
                'date_added' => $data->row['date_added'],
                'is_delete' => $data->row['is_delete'],
                'company' => $company_array,
                'factory' => $factory_array,
                //'product_cat_array' => $product_cat_array,
            );
//printr($address_array);
            return $address_array;
        } else {
            return false;
        }
    }

    function update_logo($data, $logo) { //printr($data);
        //printr($logo);
        $address_id = $data['address_id'];
        //printr($address_id);die;
        $this->query(" UPDATE  address_book_master SET company_name='" . $data['company_name'] . "', contact_name = '" . $data['contact_name'] . "',designation = '" . $data['designation'] . "',department = '" . $data['department'] . "',website = '" . $data['website'] . "', remark = '" . $data['remark'] . "', vat_no = '" . $data['vat_no'] . "', exhibition_id = '" . $data['exhibition'] . "', logo = '" . $logo . "', user_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "',user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "',status = '" . $data['status'] . "', date_added = NOW(),date_modify = NOW() WHERE address_book_id ='" . $address_id . "' AND is_delete = '0' ");
        //echo $sql;die;

        foreach ($data['company'] as $company) {
            if (isset($company['company_address_id']) && ($company['company_address_id'] != '')) {
                $this->query("UPDATE  company_address  SET c_address = '" . $company['c_address'] . "', city = '" . $company['city'] . "',state = '" . $company['state'] . "',country = '" . $company['country'] . "',pincode = '" . $company['pincode'] . "',phone_no = '" . $company['phone_no'] . "', email_1 = '" . $company['email_1'] . "', email_2 = '" . $company['email_2'] . "',date_added = NOW(),date_modify = NOW() WHERE  company_address_id = '" . $company['company_address_id'] . "'");
            } else {
                $this->query("INSERT INTO " . DB_PREFIX . "company_address SET address_book_id = '" . $address_id . "', c_address = '" . $company['c_address'] . "', city = '" . $company['city'] . "',state = '" . $company['state'] . "',country = '" . $company['country'] . "',pincode = '" . $company['pincode'] . "',phone_no = '" . $company['phone_no'] . "',email_1 = '" . $company['email_1'] . "',email_2 = '" . $company['email_2'] . "',  user_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "', user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "',	date_added = NOW(),date_modify = NOW()");
                $company_address_id = $this->getLastId();
            }
        }

        foreach ($data['factory'] as $factory) {
            if (isset($factory['factory_address_id']) && ($factory['factory_address_id'] != '')) {    //printr($factory);die;
                $this->query("UPDATE  factory_address  SET address_book_id = '" . $address_id . "', f_address = '" . $factory['f_address'] . "', city = '" . $factory['city'] . "',state = '" . $factory['state'] . "',country = '" . $factory['country'] . "',pincode = '" . $factory['pincode'] . "',phone_no = '" . $factory['phone_no'] . "',email_1 = '" . $factory['email_1'] . "', email_2 = '" . $factory['email_2'] . "',date_added = NOW(),date_modify = NOW() WHERE address_book_id = '" . $address_id . "' AND factory_address_id = '" . $factory['factory_address_id'] . "'");
            } else {
                $this->query("INSERT INTO " . DB_PREFIX . "factory_address SET address_book_id = '" . $address_id . "', f_address = '" . $factory['f_address'] . "', city = '" . $factory['city'] . "',state = '" . $factory['state'] . "',country = '" . $factory['country'] . "',pincode = '" . $factory['pincode'] . "',phone_no = '" . $factory['phone_no'] . "',email_1 = '" . $factory['email_1'] . "',email_2 = '" . $factory['email_2'] . "',  user_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "', user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "', date_added = NOW(),date_modify = NOW()");
                $factory_address_id = $this->getLastId();
            }
        }

       /* if (isset($data['customer_product'])) {
            $this->query(" DELETE FROM  address_product_category  WHERE address_book_id ='" . $address_id . "'");
            foreach ($data['customer_product'] as $customer_product) {
                $this->query("INSERT INTO " . DB_PREFIX . "address_product_category SET address_book_id = '" . $address_id . "',customer_product_cat_id = '" . $customer_product . "', date_added = NOW(),date_modify = NOW()");
            }
        }*/
        return $address_id;
    }

    public function update_address_status($id, $status) {
        $this->query("UPDATE " . DB_PREFIX . "address_book_master SET status = '" . (int) $status . "', date_modify = NOW() WHERE address_book_id = '" . $id . "' ");
    }

    public function remove_company_record($company_id, $delete) {
        //printr($company_id);die;
        //printr($delete);die;
        //$this->query("DELETE FROM  company_address  WHERE company_address_id ='".$company_id."'");
        if ($delete == 0) {
            $sql = "UPDATE `" . DB_PREFIX . "company_address` SET is_delete = '1' ,date_modify = NOW() WHERE company_address_id ='" . $company_id . "'";
            echo $sql;
            $this->query($sql);
        }
    }

    public function remove_factory_record($factory_id, $delete) {
        //printr($company_id);
        //printr($product_enquiry_id);
        // $this->query("DELETE FROM  factory_address  WHERE factory_address_id ='".$factory_id."'");
        if ($delete == 0) {
            $sql = "UPDATE " . DB_PREFIX . "factory_address SET is_delete = '1' ,date_modify = NOW() WHERE factory_address_id ='" . $factory_id . "'";

            echo $sql;
            $this->query($sql);
        }
    }

    public function updateStatus($status, $data) {
        if ($status == 0 || $status == 1) {
            $sql = "UPDATE `" . DB_PREFIX . "address_book_master` SET status = '" . (int) $status . "',  date_modify = NOW() WHERE address_book_id IN (" . implode(",", $data) . ")";
            //echo $sql;die;
            $this->query($sql);
        } elseif ($status == 2) {
            $sql = "UPDATE `" . DB_PREFIX . "address_book_master` SET is_delete = '1', date_modify = NOW() WHERE address_book_id IN (" . implode(",", $data) . ")";
            //echo $sql;die;
            $this->query($sql);
        }
    }

    public function remove_logo($logo) {
        $sql = "UPDATE address_book_master SET logo = '' WHERE address_book_id='" . $logo . "'";
        //echo $sql;die;
        $this->query($sql);
    }

    public function getUser($user_id, $user_type_id) {
        //printr($user_id);
        //printr($user_type_id);
        if ($user_type_id == 1) {
            //$sql = "SELECT u.user_name,u.first_name,u.last_name,am.user_type_id,am.user_id FROM " . DB_PREFIX ."user u, " . DB_PREFIX ."account_master am WHERE u.user_id = '".(int)$user_id."' AND am.user_id = '".(int)$user_id."' AND am.user_type_id = '".(int)$user_type_id."'";
            $sql = "SELECT u.user_name, co.country_id, co.country_name, u.first_name, u.last_name, u.email_signature, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX . "user u LEFT JOIN " . DB_PREFIX . "address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '" . (int) $user_id . "') LEFT JOIN " . DB_PREFIX . "country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX . "account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '" . (int) $user_id . "' AND ad.address_type_id = '0' AND acc.user_type_id ='" . (int) $user_type_id . "' AND acc.user_id = '" . (int) $user_id . "'";
        } elseif ($user_type_id == 2) {
            $sql = "SELECT e.user_name, co.country_id, co.country_name, e.first_name, e.last_name, e.email_signature, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX . "employee e LEFT JOIN " . DB_PREFIX . "address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '" . (int) $user_id . "') LEFT JOIN " . DB_PREFIX . "country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX . "account_master acc ON(acc.user_name=e.user_name) WHERE e.employee_id = '" . (int) $user_id . "' AND ad.address_type_id = '0' AND acc.user_type_id ='" . (int) $user_type_id . "' AND acc.user_id = '" . (int) $user_id . "'";
        } elseif ($user_type_id == 4) {
            $sql = "SELECT ib.user_name,ib.discount, co.country_id, co.country_name, ib.first_name, ib.last_name, ib.email_signature, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX . "international_branch ib LEFT JOIN " . DB_PREFIX . "address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '" . (int) $user_id . "') LEFT JOIN " . DB_PREFIX . "country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX . "account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '" . (int) $user_id . "' AND ad.address_type_id = '0' AND acc.user_type_id ='" . (int) $user_type_id . "' AND acc.user_id = '" . (int) $user_id . "'";
        } elseif ($user_type_id == 5) {
            $sql = "SELECT a.user_name, co.country_id, co.country_name, a.first_name, a.last_name, a.email_signature, a.associate_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(a.first_name,' ',a.last_name) as name, a.telephone, acc.email FROM " . DB_PREFIX . "associate a LEFT JOIN " . DB_PREFIX . "address ad ON(ad.address_id=a.address_id AND ad.user_type_id = '5' AND ad.user_id = '" . (int) $user_id . "') LEFT JOIN " . DB_PREFIX . "country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX . "account_master acc ON(acc.user_name=a.user_name) WHERE a.associate_id = '" . (int) $user_id . "' AND ad.address_type_id = '0' AND acc.user_type_id ='" . (int) $user_type_id . "' AND acc.user_id = '" . (int) $user_id . "'";
        } else {
            /* $sql = "SELECT co.country_id, co.country_name, c.first_name, c.last_name, c.email_signature, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' "; */
            return false;
        }
        //echo $sql;die;
        $data = $this->query($sql);
        return $data->row;
    }

    public function getUserEmployeeIds($user_type_id, $user_id) {
        $sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '" . (int) $user_type_id . "' AND user_id = '" . (int) $user_id . "'";
//		echo $sql;
        $data = $this->query($sql);

        if ($data->num_rows) {
            return $data->row['ids'];
        } else {
            return false;
        }
    }

    public function get_address_code_values($address_code_id) {
        $sql = "SELECT * FROM address_book_master a JOIN company_address c on a.address_book_id=c.address_book_id where a.is_delete = '0' and a.address_book_id='" . $address_code_id . "'";
        //printr($sql);die;
        $data = $this->query($sql);

        if ($data->num_rows)
            return $data->row;
        else
            return false;
    }

    public function address_array_for_CSV($address_codes) {
        $i = 0;


        foreach ($address_codes as $add_code) {
        //printr($invoice_no);

            $address = $this->get_address_code_values($add_code);

            $input_array[$i++] = Array('Copany Name' => $address['company_name'],
                        'Contact Name' => $address['contact_name'],
                        'Designation' => $address['designation'],
                        'Phone Number' => $address['phone_no'],
                        'Email' => $address['email_1'],
            );
        }
        //printr($input_array);die;
        return $input_array;
    }

    public function exhibition_name() {
        $sql = "SELECT * FROM exhibition_details WHERE is_delete = 0";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }

    public function getLatestEnquiries($address_id,$n=0,$ib='',$emp='',$f_date='',$t_date='') {
			//$add_report
        $user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
        $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
       
        $sql = "SELECT am.user_name,c.country_name,e.*,CONCAT(e.first_name,' ',e.last_name) as name,es.source FROM `" . DB_PREFIX . "enquiry` e LEFT JOIN `" . DB_PREFIX . "enquiry_source` es ON (e.enquiry_source_id = es.enquiry_source_id) LEFT JOIN `" . DB_PREFIX . "country` c ON (e.country_id = c.country_id) LEFT JOIN `" . DB_PREFIX . "account_master` am ON (e.user_id = am.user_id) AND (e.user_type_id = am.user_type_id) WHERE
		 e.is_delete = 0 AND e.company_name_id ='" . $address_id . "' ";
        //echo $sql;die;
        if ($user_type_id != 1 && $user_id != 1) {
            if ($user_type_id == 2) {
                $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");
                $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
            } else {
                $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);
            }
            $str = '';
            if ($userEmployee) {
                //printr($user_type_id);
                $str = " OR ( e.user_id IN (" . $userEmployee . ") AND e.user_type_id = '2' )";
                //printr($str);
            }

            $sql .= " AND ((e.user_id = '" . (int) $user_id . "' AND e.user_type_id = '" . (int) $user_type_id . "') $str)";
        }
		$limit='';
		if($n=='0')
			$limit = 'LIMIT 0,5';
        $sql .= "ORDER BY enquiry_id DESC $limit";
        //echo $sql;

        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }

    public function getTotalEnquiry($company, $filter_data = array()) {
		///printr($company);
        $sql = "SELECT COUNT(*) as total,CONCAT(e.first_name,' ',e.last_name) as name,es.source FROM " . DB_PREFIX . "enquiry e LEFT JOIN `" . DB_PREFIX . "enquiry_source` es ON (e.enquiry_source_id = es.enquiry_source_id) LEFT JOIN `" . DB_PREFIX . "country` c ON (e.country_id = c.country_id) WHERE e.is_delete = 0 AND e.company_name='" . $company . "'";
        //echo $sql;die;
        $user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
        $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
 
        if ($user_type_id != 1 && $user_id != 1) {
            if ($user_type_id == 2) {
                $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");
                $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
            } else {
                $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);
            }
            if ($userEmployee) {
                //printr($user_type_id);
                $str = " OR ( e.user_id IN (" . $userEmployee . ") AND e.user_type_id = '2' )";
                //printr($str);
            }

            $sql .= " AND ((e.user_id = '" . (int) $user_id . "' AND e.user_type_id = '" . (int) $user_type_id . "') $str)";
        }

        if (!empty($filter_data)) {
            if (!empty($filter_data['enquiry_number'])) {
                $sql .= " AND e.enquiry_number = '" . $filter_data['enquiry_number'] . "' ";
            }


            if (!empty($filter_data['company'])) {
                $sql .= " AND e.company_name = '" . $filter_data['company'] . "' ";
            }
            if (!empty($filter_data['email'])) {
                $sql .= " AND e.email = '" . $filter_data['email'] . "' ";
            }


            if (!empty($filter_data['country'])) {
                $sql .= " AND e.country_id = '" . $filter_data['country'] . "' ";
            }

            if ($filter_data['status'] != '') {
                $sql .= " AND e.status = '" . $filter_data['status'] . "' ";
            }

            if (!empty($filter_data['customer'])) {
                $sql .= " HAVING name LIKE '%" . $this->escape($filter_data['customer']) . "%'";
            }
        }
        //echo $sql;
        $data = $this->query($sql);
        //printr($data);
        if ($data->num_rows > 0) {
            return $data->row['total'];
        } else {
            return false;
        }
    }

    public function getEnquiries($company, $data, $filter_data = array()) {

        $user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
        $user_type_id = $_SESSION['LOGIN_USER_TYPE'];

        $sql = "SELECT am.user_name,c.country_name,e.*,CONCAT(e.first_name,' ',e.last_name) as name,es.source FROM `" . DB_PREFIX . "enquiry` e LEFT JOIN `" . DB_PREFIX . "enquiry_source` es ON (e.enquiry_source_id = es.enquiry_source_id) LEFT JOIN `" . DB_PREFIX . "country` c ON (e.country_id = c.country_id) LEFT JOIN `" . DB_PREFIX . "account_master` am ON (e.user_id = am.user_id) AND (e.user_type_id = am.user_type_id) WHERE e.is_delete = 0
		AND	e.company_name='" . $company . "'";
        //echo $sql;die;
        if ($user_type_id != 1 && $user_id != 1) {
            if ($user_type_id == 2) {
                $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");
                $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
            } else {
                $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);
            }
            if ($userEmployee) {
                //printr($user_type_id);
                $str = " OR ( e.user_id IN (" . $userEmployee . ") AND e.user_type_id = '2' )";
                //printr($str);
            }

            $sql .= " AND ((e.user_id = '" . (int) $user_id . "' AND e.user_type_id = '" . (int) $user_type_id . "') $str)";
        }

        if (!empty($filter_data)) {
            if (!empty($filter_data['enquiry_number'])) {
                $sql .= " AND e.enquiry_number = '" . $filter_data['enquiry_number'] . "' ";
            }

            if (!empty($filter_data['company'])) {
                $sql .= " AND e.company_name = '" . $filter_data['company'] . "' ";
            }

            if (!empty($filter_data['email'])) {
                $sql .= " AND e.email = '" . $filter_data['email'] . "' ";
            }

            if (!empty($filter_data['country'])) {
                $sql .= " AND e.country_id = '" . $filter_data['country'] . "' ";
            }

            if ($filter_data['status'] != '') {
                $sql .= " AND e.status = '" . $filter_data['status'] . "' ";
            }

            if (!empty($filter_data['customer'])) {
                $sql .= " HAVING name LIKE '%" . $this->escape($filter_data['customer']) . "%'";
            }
        }

        if (isset($data['sort'])) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY enquiry_id";
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

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }
        //echo $sql;
        $data = $this->query($sql);
        //printr($data);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }

    public function updateStatus_enquiry($status, $data) {
        if ($status == 0 || $status == 1) {
            $sql = "UPDATE `" . DB_PREFIX . "enquiry` SET status = '" . (int) $status . "',  date_modify = NOW() WHERE enquiry_id IN (" . implode(",", $data) . ")";
            //echo $sql;die;
            $this->query($sql);
        } elseif ($status == 2) {
            $sql = "UPDATE `" . DB_PREFIX . "enquiry` SET is_delete = '1', date_modify = NOW() WHERE enquiry_id IN (" . implode(",", $data) . ")";
            $this->query($sql);
        }
    }

    public function updateEnquiryStatus($id, $status) {
        $this->query("UPDATE " . DB_PREFIX . "enquiry SET status = '" . (int) $status . "', date_modify = NOW() WHERE enquiry_id = '" . $id . "' ");
    }

    function get_proforma_invoice($user_id, $user_type_id, $address_id,$n=0) {
			//[kinjal] modify on (13-4-2017)
        if ($user_type_id == 1 && $user_id == 1) {
            $sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma as p,country as c WHERE c.country_id=p.destination AND p.is_delete =0  AND p.address_book_id='". $address_id ."' ";
        } else {
            if ($user_type_id == 2) {
                $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");
                $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
                $set_user_id = $parentdata->row['user_id'];
                $set_user_type_id = $parentdata->row['user_type_id'];
            } else {
                $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);
                $set_user_id = $user_id;
                $set_user_type_id = $user_type_id;
            }
            $str = '';
            if ($userEmployee) {
                $str = " OR ( p.added_by_user_id IN (" . $userEmployee . ") AND  p.added_by_user_type_id = '2' )";
            }
            $sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma as p,country as c WHERE c.country_id=p.destination AND 
				((p.added_by_user_id = '" . $set_user_id . "' AND p.added_by_user_type_id = '" . $set_user_type_id . "') $str) AND p.is_delete = 0 AND p.address_book_id='". $address_id ."' ";
        }
        $sql .= " AND p.status ='1' AND proforma_status ='0' ";
		$limit='';
		if($n=='0')
			$limit = 'LIMIT 0,5';
        $sql .= "ORDER BY proforma_id DESC LIMIT 0,5";
       // echo $sql;
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }

    public function getTotalInvoice($filter_data = array(), $status, $proforma_status, $user_id, $user_type_id, $is_delete, $company) {
        if ($user_type_id == 1 && $user_id == 1) {
            $sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma as p,country as c WHERE c.country_id=p.destination AND p.is_delete ='" . $is_delete . "' AND p.customer_name='" . $company . "'";
            //echo $sql;die;
        } else {
            if ($user_type_id == 2) {
                $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");
                $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
                $set_user_id = $parentdata->row['user_id'];
                $set_user_type_id = $parentdata->row['user_type_id'];
            } else {
                $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);
                $set_user_id = $user_id;
                $set_user_type_id = $user_type_id;
            }
            $str = '';
            if ($userEmployee) {
                $str = " OR ( p.added_by_user_id IN (" . $userEmployee . ") AND p.added_by_user_type_id = '2' )";
            }

            $sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma as p,country as c WHERE c.country_id=p.destination AND p.is_delete = '" . $is_delete . "' AND p.customer_name='" . $company . "' 
			    AND ((p.added_by_user_id='" . $set_user_id . "' AND p.added_by_user_type_id='" . $set_user_type_id . "')$str)";
            //echo $sql;
        }
        if ($status >= '0') {
            $sql .= " AND p.status ='" . $status . "' ";
        }
        if ($proforma_status >= '0') {
            $sql .= " AND p.proforma_status ='" . $proforma_status . "' ";
        }
        if (!empty($filter_data)) {
            if (!empty($filter_data['customer_name'])) {
                $sql .= " AND p.customer_name LIKE '%" . $filter_data['customer_name'] . "%' ";
            }
            if (!empty($filter_data['email'])) {
                $sql .= " AND p.email LIKE '%" . $filter_data['email'] . "%' ";
            }
            if (!empty($filter_data['invoice_number'])) {
                $sql .= " AND p.pro_in_no LIKE '%" . $filter_data['invoice_number'] . "%' ";
            }//echo $sql;die;
            if (!empty($filter_data['postedby'])) {
                $spitdata = explode("=", $filter_data['postedby']);
                $sql .= "AND p.added_by_user_type_id = '" . $spitdata[0] . "' AND p.added_by_user_id = '" . $spitdata[1] . "'";
            }
        }
        if (isset($data['sort'])) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY proforma_id";
        }
        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }
        //echo $sql;
        $data = $this->query($sql);
        return $data->num_rows;
    }

    public function getInvoices($data, $filter_data = array(), $status, $proforma_status, $user_id, $user_type_id, $is_delete, $company) {
        if ($user_type_id == 1 && $user_id == 1) {
            $sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma as p,country as c WHERE c.country_id=p.destination AND p.is_delete = '" . $is_delete . "' AND customer_name='" . $company . "'";
        } else {
            if ($user_type_id == 2) {
                $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");
                $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
                $set_user_id = $parentdata->row['user_id'];
                $set_user_type_id = $parentdata->row['user_type_id'];
            } else {
                $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);
                $set_user_id = $user_id;
                $set_user_type_id = $user_type_id;
            }
            $str = '';
            if ($userEmployee) {
                $str = " OR ( p.added_by_user_id IN (" . $userEmployee . ") AND  p.added_by_user_type_id = '2' )";
            }
            $sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma as p,country as c WHERE c.country_id=p.destination AND 
				((p.added_by_user_id = '" . $set_user_id . "' AND p.added_by_user_type_id = '" . $set_user_type_id . "') $str) AND p.is_delete = '" . $is_delete . "' AND customer_name='" . $company . "'";
        }
        if ($status >= '0') {
            $sql .= " AND p.status ='" . $status . "' ";
        }
        if ($proforma_status >= '0') {
            $sql .= " AND p.proforma_status ='" . $proforma_status . "' ";
        }
        if (!empty($filter_data)) {
            if (!empty($filter_data['customer_name'])) {
                $sql .= " AND p.customer_name LIKE  '%" . $filter_data['customer_name'] . "%'";
            }
            if (!empty($filter_data['email'])) {
                $sql .= " AND p.email LIKE '%" . $filter_data['email'] . "%' ";
            }
            if (!empty($filter_data['invoice_number'])) {
                $sql .= " AND p.pro_in_no = '" . $filter_data['invoice_number'] . "' ";
            }
            if (!empty($filter_data['postedby'])) {
                $spitdata = explode("=", $filter_data['postedby']);
                $sql .= "AND p.added_by_user_type_id = '" . $spitdata[0] . "' AND p.added_by_user_id = '" . $spitdata[1] . "'";
            }
        }
        if (isset($data['sort'])) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY proforma_id";
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
            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }
        //echo $sql;

        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }

    public function getUserList() {
        $sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX . "account_master ORDER BY user_name ASC";
        $data = $this->query($sql);
        //printr($data);die;
        return $data->rows;
    }

    public function updateProStatus($proforma_id, $status_value) {
        $sql = "UPDATE " . DB_PREFIX . "proforma SET status = '" . $status_value . "', date_modify = NOW() WHERE proforma_id = '" . (int) $proforma_id . "'";
        //echo $sql;die;
        $this->query($sql);
    }

    public function latestCustomOrders($user_type_id, $user_id, $address_id,$n=0) {
        if ($user_type_id == 1 && $user_id == 1) {
            $sql = "SELECT mco.*,c.country_name,mcoi.*,mcop.zipper_txt,mcop.valve_txt, mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number FROM multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.address_book_id='" . $address_id . "' ";
			//$sql = "SELECT c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mco.product_name,mcoi.address_book_id, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt, mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number FROM multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.address_book_id='43' AND mco.custom_order_status = 1 AND mco.status='1' GROUP BY mco.multi_custom_order_id";
            //echo $sql;
        } else {
            if ($user_type_id == 2) {
                //printr($user_type_id);	
                $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");
                $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
                $set_user_id = $parentdata->row['user_id'];
                $set_user_type_id = $parentdata->row['user_type_id'];
            } else {
                $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);
                $set_user_id = $user_id;
                $set_user_type_id = $user_type_id;
            }
            $str = '';
            if ($userEmployee) {
                $str = " OR ( mco.added_by_user_id IN (" . $userEmployee . ") AND mco.added_by_user_type_id = '2' )";
            }
            $sql = "SELECT mco.*,c.country_name,mcoi.*,mcop.zipper_txt,mcop.valve_txt, mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number FROM multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.address_book_id='" . $address_id . "' AND ((mco.added_by_user_id = '" . (int) $set_user_id . "' AND mco.added_by_user_type_id = '" . (int) $set_user_type_id . "' ) $str)";
        }
       $limit='';
		if($n=='0')
			$limit = 'LIMIT 0,5';
        $sql .= "GROUP BY mcoi.multi_custom_order_id ORDER BY mcoi.multi_custom_order_id DESC $limit";
        //echo $sql;
        //die;
		
		
        $data = $this->query($sql);
		//printr($data);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }

    public function getActiveProduct() {
        $sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete = '0' ORDER BY product_name ASC";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }

    public function getTotalCustomOrder($user_type_id, $user_id, $filter_array = array(), $cond, $company) {
        if ($user_type_id == 1 && $user_id == 1) {
            $sql = "SELECT c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mco.product_name, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt,
		mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number FROM multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND
		mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id ";
        } else {
            if ($user_type_id == 2) {
                $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");
                $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
                //printr($userEmployee);
                $set_user_id = $parentdata->row['user_id'];
                $set_user_type_id = $parentdata->row['user_type_id'];
                //printr($set_user_type_id);
            } else {
                $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);
                $set_user_id = $user_id;
                $set_user_type_id = $user_type_id;
                //printr($set_user_id);
                //printr($set_user_type_id);
            }
            $str = '';
            if ($userEmployee) {
                $str = " OR ( mco.added_by_user_id IN (" . $userEmployee . ") AND mco.added_by_user_type_id = '2' )";
            }//printr($str);
            $sql = "SELECT mcoi.company_name,c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mco.product_name, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt,mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number FROM multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND	mco.custom_order_id = mcop.custom_order_id AND mcoi.multi_custom_order_id=mco.multi_custom_order_id  AND ((mco.added_by_user_id = '" . (int) $set_user_id . "' AND mco.added_by_user_type_id = '" . (int) $set_user_type_id . "') $str) ";
        }//echo $sql;die;
        if (!empty($filter_array)) {
            if (!empty($filter_array['custom_order_no'])) {
                $sql .= " AND mcoi.multi_custom_order_number = '" . $filter_array['custom_order_no'] . "'";
            }

            if (!empty($filter_array['customer_name'])) {
                $sql .= " AND mcoi.customer_name LIKE '%" . $filter_array['customer_name'] . "%'";
            }

            if (!empty($filter_array['date'])) {
                $sql .= " AND date(mco.date_added) = '" . date('Y-m-d', strtotime($filter_array['date'])) . "'";
            }

            if (!empty($filter_array['layer'])) {
                $sql .= " AND mco.layer = '" . $filter_array['layer'] . "'";
            }

            if (!empty($filter_array['product_name'])) {
                $sql .= " AND mco.product_name = '" . $filter_array['product_name'] . "'";
            }

            if (!empty($filter_array['country'])) {
                $sql .= " AND mco.shipment_country_id = '" . $filter_array['country'] . "'";
            }

            if (!empty($filter_array['option'])) {
                $sql .= " AND mco.option_id = '" . $filter_array['option'] . "'";
            }
            if (!empty($filter_array['postedby'])) {
                $spitdata = explode("=", $filter_array['postedby']);
                $sql .= "AND mco.added_by_user_type_id = '" . $spitdata[0] . "' AND mco.added_by_user_id = '" . $spitdata[1] . "'";
            }
        }
        if (!empty($cond)) {
            $sql .= $cond;
        }
        $sql .= 'AND mcoi.company_name="' . $company . '" GROUP BY mco.multi_custom_order_id';
        //echo $sql;
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->num_rows;
        } else {
            return false;
        }
    }

    public function getCustomOrders($user_type_id, $user_id, $data, $filter_array = array(), $company) {
        //printr($user_id);printr($user_type_id);
        if ($user_type_id == 1 && $user_id == 1) {
            $sql = "SELECT mco.accept_decline_status,c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mcoi.company_name,mcoi.email,mco.product_name, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt,
		mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number FROM multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND
		mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.company_name='" . $company . "'";
        } else {
            if ($user_type_id == 2) {
                $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");
                $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
                $set_user_id = $parentdata->row['user_id'];
                $set_user_type_id = $parentdata->row['user_type_id'];
            } else {
                $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);
                $set_user_id = $user_id;
                $set_user_type_id = $user_type_id;
            }
            $str = '';
            if ($userEmployee) {
                $str = " OR ( mco.added_by_user_id IN (" . $userEmployee . ") AND mco.added_by_user_type_id = '2' )";
            }
            $sql = "SELECT mco.accept_decline_status,c.country_name,mco.*,mcop.valve_txt,mcop.zipper_txt,mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number,mcoi.customer_name,mcoi.email,mcoi.company_name FROM multi_custom_order mco LEFT JOIN country c ON c.country_id = mco.shipment_country_id LEFT JOIN multi_custom_order_price mcop ON mco.custom_order_id=mcop.custom_order_id LEFT JOIN multi_custom_order_id as mcoi ON mcoi.multi_custom_order_id=mco.multi_custom_order_id WHERE ((mco.added_by_user_id = '" . (int) $set_user_id . "' AND mco.added_by_user_type_id = '" . (int) $set_user_type_id . "')$str) ";
        }
        if (!empty($filter_array)) {
            if (!empty($filter_array['custom_order_no'])) {
                $sql .= " AND mcoi.multi_custom_order_number = '" . $filter_array['custom_order_no'] . "'";
            }

            if (!empty($filter_array['customer_name'])) {
                $sql .= " AND mcoi.customer_name LIKE '%" . $filter_array['customer_name'] . "%'";
            }

            if (!empty($filter_array['date'])) {
                $sql .= " AND date(mco.date_added) = '" . date('Y-m-d', strtotime($filter_array['date'])) . "'";
            }

            if (!empty($filter_array['layer'])) {
                $sql .= " AND mco.layer = '" . $filter_array['layer'] . "'";
            }

            if (!empty($filter_array['product_name'])) {
                $sql .= " AND mco.product_name = '" . $filter_array['product_name'] . "'";
            }

            if (!empty($filter_array['country'])) {
                $sql .= " AND mco.shipment_country_id = '" . $filter_array['country'] . "'";
            }

            if (!empty($filter_array['option'])) {
                $sql .= " AND mco.option_id = '" . $filter_array['option'] . "'";
            }
            if (!empty($filter_array['postedby'])) {
                $spitdata = explode("=", $filter_array['postedby']);
                $sql .= "AND mco.added_by_user_type_id = '" . $spitdata[0] . "' AND mco.added_by_user_id = '" . $spitdata[1] . "'";
            }
        }

        if (!empty($data['cond'])) {
            $sql .= $data['cond'];
        }
        $sql .= " AND mcoi.company_name='" . $company . "' GROUP BY mco.multi_custom_order_id";
        if (isset($data['sort'])) {


            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY mco.multi_custom_order_id";
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
            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }
        //echo $sql;
        //die;
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }

    public function updateCustomOrderStatus($custom_order_id, $status_value) {

        $sql = "UPDATE " . DB_PREFIX . "multi_custom_order SET status = '" . $status_value . "', date_modify = NOW() WHERE multi_custom_order_id = '" . (int) $custom_order_id . "'";
        $this->query($sql);

        $sql = "UPDATE " . DB_PREFIX . "multi_custom_order_id SET status = '" . $status_value . "', date_modify = NOW() WHERE multi_custom_order_id = '" . (int) $custom_order_id . "'";
        $this->query($sql);
    }
	
	// sejal 14-04
    public function all_Invoice($user_type_id, $user_id,$address_id,$n=0) {
		
        if ($user_type_id == 1 && $user_id == 1) {
            $sql = "SELECT inv.*,c.country_name FROM " . DB_PREFIX . "sales_invoice as inv LEFT JOIN country as c ON c.country_id=inv.final_destination  WHERE  inv.is_delete = 0 AND inv.gen_status='0' AND inv.address_book_id='".$address_id."' ";
        } else {

            if ($user_type_id == 2) {
                $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");
                $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
                $set_user_id = $parentdata->row['user_id'];
                $set_user_type_id = $parentdata->row['user_type_id'];
            } else {
                $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);
                $set_user_id = $user_id;
                $set_user_type_id = $user_type_id;
            }
            $str = '';
            if ($userEmployee) {
                $str = " OR ( inv.user_id IN (" . $userEmployee . ") AND inv.user_type_id ='2' )";
            }
            $sql = "SELECT inv.*,c.country_name FROM " . DB_PREFIX . "sales_invoice as inv LEFT JOIN country as c ON c.country_id=inv.final_destination WHERE  inv.is_delete = 0 AND inv.gen_status='0' AND ((inv.user_id = '" . (int) $set_user_id . "' AND inv.user_type_id = '" . (int) $set_user_type_id . "' ) $str )AND inv.address_book_id='".$address_id."'";
        }
		$limit='';
		if($n=='0')
			$limit = 'LIMIT 0,5';
        $sql .= " ORDER BY invoice_id DESC $limit";
       // echo $sql;
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }

    public function getTotalSalesInvoice($user_type_id, $user_id, $filter_data = array(), $is_delete, $company) {
        if ($user_type_id == 1 && $user_id == 1) {
            $sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "sales_invoice` WHERE is_delete = '" . $is_delete . "' AND gen_status='0' AND customer_name='" . $company . "' ";
        } else {
            if ($user_type_id == 2) {
                $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");
                $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
                $set_user_id = $parentdata->row['user_id'];
                $set_user_type_id = $parentdata->row['user_type_id'];
            } else {
                $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);
                $set_user_id = $user_id;
                $set_user_type_id = $user_type_id;
            }
            $str = '';
            if ($userEmployee) {
                $str = " OR ( user_id IN (" . $userEmployee . ") AND user_type_id = '2' )";
            }
            $sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "sales_invoice` WHERE is_delete = '" . $is_delete . "' AND gen_status='0' AND customer_name='" . $company . "'  AND ( ( user_id = '" . (int) $set_user_id . "' AND  user_type_id = '" . (int) $set_user_type_id . "' ) $str )";
        }

        if (!empty($filter_data)) {
            if (!empty($filter_data['invoice_no'])) {

                $sql .= " AND invoice_no LIKE '%" . $filter_data['invoice_no'] . "%' ";
            }
            if (!empty($filter_data['country_id'])) {
                $sql .= " AND final_destination = '" . $filter_data['country_id'] . "' ";
            }
            if (!empty($filter_data['customer_name'])) {
                $sql .= " AND customer_name LIKE '%" . $filter_data['customer_name'] . "%' ";
            }
            if (!empty($filter_data['email'])) {
                $sql .= " AND email LIKE '%" . $filter_data['email'] . "%' ";
            }
            if ($filter_data['status'] != '') {
                $sql .= " AND status = '" . $filter_data['status'] . "' ";
            }
            if (!empty($filter_data['user_name'])) {
                $spitdata = explode("=", $filter_data['user_name']);
                $sql .= " AND user_type_id = '" . $spitdata[0] . "' AND user_id = '" . $spitdata[1] . "'";
            }
        }
        //	echo $sql;

        $data = $this->query($sql);
        return $data->row['total'];
    }

    public function getSalesInvoice($user_type_id, $user_id, $data, $filter_data = array(), $is_delete, $company) {
        if ($user_type_id == 1 && $user_id == 1) {
            $sql = "SELECT inv.*,c.country_name FROM " . DB_PREFIX . "sales_invoice as inv LEFT JOIN country as c ON c.country_id=inv.final_destination  WHERE  inv.is_delete = '" . $is_delete . "' AND inv.gen_status='0' AND inv.customer_name='" . $company . "' ";
        } else {

            if ($user_type_id == 2) {
                $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");
                $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
                $set_user_id = $parentdata->row['user_id'];
                $set_user_type_id = $parentdata->row['user_type_id'];
            } else {
                $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);
                $set_user_id = $user_id;
                $set_user_type_id = $user_type_id;
            }
            $str = '';
            if ($userEmployee) {
                $str = " OR ( inv.user_id IN (" . $userEmployee . ") AND inv.user_type_id = '2' )";
            }
            $sql = "SELECT inv.*,c.country_name FROM " . DB_PREFIX . "sales_invoice as inv LEFT JOIN country as c ON c.country_id=inv.final_destination WHERE  inv.is_delete = '" . $is_delete . "' AND inv.gen_status='0' AND inv.customer_name='" . $company . "' AND  ((inv.user_id = '" . (int) $set_user_id . "' AND inv.user_type_id = '" . (int) $set_user_type_id . "' ) $str )";
        }
        //echo $sql;
        if (!empty($filter_data)) {
            if (!empty($filter_data['invoice_no'])) {
                $sql .= " AND invoice_no LIKE '%" . $filter_data['invoice_no'] . "%' ";
            }
            if (!empty($filter_data['country_id'])) {
                $sql .= " AND final_destination = '" . $filter_data['country_id'] . "' ";
            }
            if (!empty($filter_data['customer_name'])) {
                $sql .= " AND customer_name LIKE '%" . $filter_data['customer_name'] . "%' ";
            }
            if (!empty($filter_data['email'])) {
                $sql .= " AND email LIKE '%" . $filter_data['email'] . "%' ";
            }
            if ($filter_data['status'] != '') {
                $sql .= " AND inv.status = '" . $filter_data['status'] . "' ";
            }
            if (!empty($filter_data['user_name'])) {
                $spitdata = explode("=", $filter_data['user_name']);
                $sql .= " AND inv.user_type_id = '" . $spitdata[0] . "' AND inv.user_id = '" . $spitdata[1] . "'";
            }
        }
        $sql .= ' GROUP BY invoice_id ';
        //echo $sql;
        if (isset($data['sort'])) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY invoice_id";
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
            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }
        //echo $sql;
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }

    public function getCredit($invoice_id) {
        $sql = "SELECT * FROM sales_credit_note WHERE invoice_id = '" . $invoice_id . "' AND is_delete=0 GROUP BY cre_no";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }

    public function updateInvoiceStatus($status, $data) {
        if ($status == 0 || $status == 1) {
            $sql = "UPDATE " . DB_PREFIX . "sales_invoice SET status = '" . (int) $status . "',  date_modify = NOW() WHERE invoice_id IN (" . implode(",", $data) . ")";
            $this->query($sql);
        } elseif ($status == 2) {
            $sql = "UPDATE " . DB_PREFIX . "sales_invoice SET is_delete = '1', date_modify = NOW() WHERE invoice_id IN (" . implode(",", $data) . ")";
            $this->query($sql);
        }
    }

    public function GetLatestCartOrderList($user_id, $usertypeid, $cond = '', $status = '', $interval, $dis_table, $dis_select = '', $s, $address_id,$n=0) {
       
        
        
        $menu_id = $cust_data = '';
        if ($status == 0) {
            $menu_id = 79;
        } elseif ($status == 1) {
            $menu_id = 80;
        }
        $con = '';

        $perm_cond = 'add_permission LIKE "%' . $menu_id . '%" AND edit_permission LIKE "%' . $menu_id . '%" AND delete_permission LIKE "%' . $menu_id . '%" AND view_permission LIKE "%' . $menu_id . '%"';

        $sql = "SELECT email,user_name FROM " . DB_PREFIX . "account_master WHERE " . $perm_cond . " AND user_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "' AND user_type_id ='" . $_SESSION['LOGIN_USER_TYPE'] . "'";
        //echo $sql;
        $dataper = $this->query($sql);
		//printr($dataper);
        if ($dataper->num_rows) {
            if ($status == '') {
                if ($_SESSION['LOGIN_USER_TYPE'] == 2) {
                    $sqladmin = "SELECT user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "'";
                    $dataadmin = $this->query($sqladmin);
                    $con = 'AND pto.admin_user_id = "' . $dataadmin->row['user_id'] . '"';
                } elseif ($_SESSION['LOGIN_USER_TYPE'] == 4) {
                    $con = 'AND pto.admin_user_id = "' . $_SESSION['ADMIN_LOGIN_SWISS'] . '"';
                } elseif ($_SESSION['LOGIN_USER_TYPE'] == 1) {
                    $con = '';
                } else {
                    return false;
                }
                //echo $con;
            }
            $sql = "SELECT " . $dis_select . " t.buyers_order_no,t.order_type,t.price,t.product_template_order_id,sum(t.quantity) as  total_qty,sum(t.quantity*t.price) as total_price,t.template_order_id,cd.client_name,st.gen_order_id,t.stock_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,count(t.product_template_order_id) as tot_count,cu.currency_code,t.transport FROM " . DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto,product_template as pt,currency as cu  " . $dis_table . "  WHERE st.address_book_id='" .$address_id. "' AND c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0 " . $cond . " " . $con . " AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id  AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id  ";
        } else {  //echo "bye";
            if ($_SESSION['LOGIN_USER_TYPE'] == 2) {
                $sqladmin = "SELECT user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "'";
                $dataadmin = $this->query($sqladmin);
                $con = 'AND pto.admin_user_id = "' . $dataadmin->row['user_id'] . '"';
            } elseif ($_SESSION['LOGIN_USER_TYPE'] == 4) {
                $con = 'AND pto.admin_user_id = "' . $_SESSION['ADMIN_LOGIN_SWISS'] . '"';
            } elseif ($_SESSION['LOGIN_USER_TYPE'] == 1) {
                $con = '';
            } else {
                return false;
            }
            $sql = "SELECT " . $dis_select . " t.buyers_order_no,t.order_type,t.price,t.product_template_order_id,sum(t.quantity) as  total_qty,sum(t.quantity*t.price) as total_price,t.template_order_id,cd.client_name,st.gen_order_id,t.stock_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,count(t.product_template_order_id) as tot_count,cu.currency_code,t.transport FROM " . DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto,product_template as pt,currency as cu " . $dis_table . " WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0 " . $cond . " " . $con . " AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id  AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id AND st.address_book_id = '".$address_id."' ";
            //echo $sql;
        }
        if (!empty($filter_array)) {
            if (!empty($filter_array['order_no'])) {
                $sql .= " AND st.gen_order_id = '" . $filter_array['order_no'] . "'";
            }
            if (!empty($filter_array['date'])) {
                $sql .= " AND date(t.date_added) = '" . date('Y-m-d', strtotime($filter_array['date'])) . "'";
            }
        }
        if ($interval != '')
            $sql .= " AND t.date_added BETWEEN DATE_SUB(NOW(), INTERVAL " . $interval . " DAY) AND NOW()";
        $sql .= " GROUP BY st.stock_order_id, pto.admin_user_id ";
		$limit='';
		if($n=='0')
			$limit = 'LIMIT 0,5';
        $sql .= "ORDER BY template_order_id DESC $limit";
        //echo $sql;
        $data = $this->query($sql);
        if ($s != '0') {
            //echo 'jjjj';
            $cust_data = $this->getCustomAcceptedRecords($s);
            if (!empty($cust_data)) {
                foreach ($cust_data as $cust) {
                    array_push($data->rows, $cust);
                }
            }
        }
        //echo $sql;
        if ($data->num_rows) {
            //echo $con;
            return $data->rows;
        } else {
            if (!empty($cust_data))
                return $cust_data;
            else
                return false;
        }
    }

    public function checkNewCartPermission($user_id, $user_type_id) {
        $sql = "SELECT order_s_no,status FROM template_order WHERE status = 1 AND date_added =  DATE_FORMAT(NOW(),'%Y-%m-%d')  AND end_date > DATE_FORMAT(NOW(),'%Y-%m-%d') AND user_id=" . $user_id . " AND user_type_id=" . $user_type_id . " ORDER BY template_order_id DESC LIMIT 1";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }

    public function orderLimit($userid, $user_type_id) {
        if ($user_type_id == 2) {
            $sqladmin = "SELECT user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $userid . "'";
            $dataadmin = $this->query($sqladmin);
            $userid = $dataadmin->row['user_id'];
        }
        $sql = "SELECT order_limit FROM international_branch WHERE international_branch_id= " . $userid . " ORDER BY international_branch_id DESC LIMIT 1";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row['order_limit'];
        } else {
            return false;
        }
    }

    public function GetTotalCartOrderList($user_id, $usertypeid, $cond = '', $status = '', $filter_array, $interval, $dis_table, $dis_select, $s, $country) {
        //printr($filter_array);

        $con = '';
        if ($status == '') {
            if ($_SESSION['LOGIN_USER_TYPE'] == 2) {
                $sqladmin = "SELECT user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "'";
                $dataadmin = $this->query($sqladmin);
                $con = 'AND pto.admin_user_id = "' . $dataadmin->row['user_id'] . '"';
            } elseif ($_SESSION['LOGIN_USER_TYPE'] == 4) {
                $con = 'AND pto.admin_user_id = "' . $_SESSION['ADMIN_LOGIN_SWISS'] . '"';
            } elseif ($_SESSION['LOGIN_USER_TYPE'] == 1) {
                $con = '';
            } else {
                return false;
            }
        }
        $sql = "SELECT " . $dis_select . " t.product_template_order_id,t.customer_order_no,t.template_order_id,cd.client_name,st.gen_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id FROM " . DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto " . $dis_table . " WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0 " . $cond . " " . $con . "  AND t.template_order_id=sos.template_order_id AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND st.stock_order_id=t.stock_order_id AND cd.client_name='" . $country . "' ";
        //echo $sql;
        //	die;
        if (!empty($filter_array)) {
            if (!empty($filter_array['order_no'])) {
                $sql .= " AND st.gen_order_id = '" . $filter_array['order_no'] . "'";
            }
            //echo $sql;
            //die;
            if (!empty($filter_array['date'])) {
                $sql .= " AND date(t.date_added) = '" . date('Y-m-d', strtotime($filter_array['date'])) . "'";
                //echo $sql;die;
            }
        }

        if ($interval != '')
            $sql .= " AND t.date_added BETWEEN DATE_SUB(NOW(), INTERVAL " . $interval . " DAY) AND NOW()";

        $sql .= " GROUP BY st.stock_order_id ORDER BY t.template_order_id";
        //echo $sql;
        //die;
        $data = $this->query($sql);
        //printr($stus)
        if ($s != '0') {
            //echo 'jjjj';
            $cust_data = $this->getCustomAcceptedRecords($s);
            if (!empty($cust_data)) {
                foreach ($cust_data as $cust) {
                    array_push($data->rows, $cust);
                }
            }
        }

        if ($data->num_rows) {
            //echo $con;
            return $data->num_rows;
        } else {
            if (!empty($cust_data))
                return $cust_data;
            else
                return false;
        }
    }

    public function GetCartOrderList($user_id, $usertypeid, $cond = '', $status = '', $filter_array, $interval, $dis_table, $dis_select = '', $option, $s, $country) {
        //printr($status);
        //die;
        ///echo $status;
        $menu_id = $cust_data = '';
        if ($status == 0) {
            $menu_id = 79;
        } elseif ($status == 1) {
            $menu_id = 80;
        }
        $con = '';

        $perm_cond = 'add_permission LIKE "%' . $menu_id . '%" AND edit_permission LIKE "%' . $menu_id . '%" AND delete_permission LIKE "%' . $menu_id . '%" AND view_permission LIKE "%' . $menu_id . '%"';

        $sql = "SELECT email,user_name FROM " . DB_PREFIX . "account_master WHERE " . $perm_cond . " AND user_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "' 
		AND user_type_id ='" . $_SESSION['LOGIN_USER_TYPE'] . "'";
        //echo $sql;
        $dataper = $this->query($sql);
        if ($dataper->num_rows) {
            if ($status == '') {
                if ($_SESSION['LOGIN_USER_TYPE'] == 2) {
                    $sqladmin = "SELECT user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "'";
                    $dataadmin = $this->query($sqladmin);
                    $con = 'AND pto.admin_user_id = "' . $dataadmin->row['user_id'] . '"';
                } elseif ($_SESSION['LOGIN_USER_TYPE'] == 4) {
                    $con = 'AND pto.admin_user_id = "' . $_SESSION['ADMIN_LOGIN_SWISS'] . '"';
                } elseif ($_SESSION['LOGIN_USER_TYPE'] == 1) {
                    $con = '';
                } else {
                    return false;
                }
                //echo $con;
            }
            $sql = "SELECT " . $dis_select . " t.buyers_order_no,t.order_type,t.price,t.product_template_order_id,sum(t.quantity) as  total_qty,sum(t.quantity*t.price) as total_price,t.template_order_id,cd.client_name,st.gen_order_id,t.stock_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,count(t.product_template_order_id) as tot_count,cu.currency_code,t.transport FROM " . DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto,product_template as pt,currency as cu  " . $dis_table . "  WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0 " . $cond . " " . $con . " AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id  AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id AND cd.client_name='" . $country . "'";
        } else {  //echo "bye";
            if ($_SESSION['LOGIN_USER_TYPE'] == 2) {
                $sqladmin = "SELECT user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "'";
                $dataadmin = $this->query($sqladmin);
                $con = 'AND pto.admin_user_id = "' . $dataadmin->row['user_id'] . '"';
            } elseif ($_SESSION['LOGIN_USER_TYPE'] == 4) {
                $con = 'AND pto.admin_user_id = "' . $_SESSION['ADMIN_LOGIN_SWISS'] . '"';
            } elseif ($_SESSION['LOGIN_USER_TYPE'] == 1) {
                $con = '';
            } else {
                return false;
            }
            //echo $con;
            //$sql = "SELECT t.product_template_order_id,t.template_order_id,cd.client_name,st.gen_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,count(t.product_template_order_id) as tot_count FROM " .DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0 ".$cond." ".$con."  AND t.template_order_id=sos.template_order_id AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id ";	
            //echo $dis_table.'<br>';
            $sql = "SELECT " . $dis_select . " t.buyers_order_no,t.order_type,t.price,t.product_template_order_id,sum(t.quantity) as  total_qty,sum(t.quantity*t.price) as total_price,t.template_order_id,cd.client_name,st.gen_order_id,t.stock_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,count(t.product_template_order_id) as tot_count,cu.currency_code,t.transport FROM " . DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto,product_template as pt,currency as cu " . $dis_table . " WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0 " . $cond . " " . $con . " AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id  AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id AND cd.client_name='" . $country . "'";
            //echo $sql;
            //	die;
        }
        //echo $sql;
        if (!empty($filter_array)) {
            if (!empty($filter_array['order_no'])) {
                $sql .= " AND st.gen_order_id = '" . $filter_array['order_no'] . "'";
            }
            //echo $sql;
            //die;
            if (!empty($filter_array['date'])) {
                $sql .= " AND date(t.date_added) = '" . date('Y-m-d', strtotime($filter_array['date'])) . "'";
                //echo $sql;die;
            }
        }
        if ($interval != '')
            $sql .= " AND t.date_added BETWEEN DATE_SUB(NOW(), INTERVAL " . $interval . " DAY) AND NOW()";

        $sql .= " GROUP BY st.stock_order_id, pto.admin_user_id ";

        if (isset($option['sort'])) {
            $sql .= " ORDER BY st.stock_order_id";
        } else {
            $sql .= " ORDER BY st.stock_order_id";
        }

        if (isset($option['order']) && ($option['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }
        //echo $sql;
        if (isset($option['start']) || isset($option['limit'])) {
            if ($option['start'] < 0) {
                $option['start'] = 0;
            }
            if ($option['limit'] < 1) {
                $option['limit'] = 20;
            }
            $sql .= " LIMIT " . (int) $option['start'] . "," . (int) $option['limit'];
        }
        //echo $sql.'<br></br>';
        //die;
        $data = $this->query($sql);

        //$data->rows = array();
        if ($s != '0') {
            //echo 'jjjj';
            $cust_data = $this->getCustomAcceptedRecords($s);
            if (!empty($cust_data)) {
                foreach ($cust_data as $cust) {
                    array_push($data->rows, $cust);
                }
            }
            //printr($data->rows);
        }


        //printr($cust_data);
        if ($data->num_rows) {
            //echo $con;
            return $data->rows;
        } else {
            if (!empty($cust_data))
                return $cust_data;
            else
                return false;
        }
    }

    public function totalCount($user_id, $usertypeid, $client_id = '', $tot_status, $stock_order_id, $status = 0) {
        //echo $stock_order_id.'<br>';
        ///echo $tot_status.'<br>';
        //echo $stock_order_id;
        $menu_id = $this->getMenuPermission(ORDER_ACCEPT_ID, $_SESSION['ADMIN_LOGIN_SWISS'], $_SESSION['LOGIN_USER_TYPE']);
        if ($_SESSION['LOGIN_USER_TYPE'] == 2) {
            $sqladmin = "SELECT user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "'  ";
            $dataadmin = $this->query($sqladmin);
            $cond = 'AND pto.admin_user_id = ' . $dataadmin->row['user_id'] . '';
            $admin_user_id = $dataadmin->row['user_id'];
            $table = 'employee as ib ,';
        } elseif ($_SESSION['LOGIN_USER_TYPE'] == 4) {
            $cond = 'AND pto.admin_user_id = ' . $_SESSION['ADMIN_LOGIN_SWISS'] . ' ';
            //$cond = '';
            $admin_user_id = $user_id;
            $table = 'international_branch as ib , ';
        } else {
            $cond = ' ';
            $table = ' ';
        }
        if ($menu_id OR $_SESSION['LOGIN_USER_TYPE'] == 1) {
            $cond = ' ';
            $table = ' ';
        }
        //SELECT * FROM (SELECT count(t.template_order_id) as c FROM template_order t,product_template pt, product_template_order as pto,stock_order_status as sos WHERE pt.product_template_id = t.template_id AND t.template_order_id=sos.template_order_id AND t.status = 1 AND sos.status=0 AND t.is_delete = 0 AND t.client_id = '3' AND pto.order_id = t.product_template_order_id) as t1 , (SELECT count(t.template_order_id) as ct FROM template_order t,product_template pt, product_template_order as pto,stock_order_status as sos WHERE pt.product_template_id = t.template_id AND t.template_order_id=sos.template_order_id AND t.status = 1 AND sos.status=0 AND t.is_delete = 0 AND t.client_id = '3' AND pto.order_id = t.product_template_order_id) as t2
        $sql = "SELECT * FROM (SELECT count(t.template_order_id) as pending FROM template_order t,product_template pt, product_template_order as pto,stock_order_status as sos,stock_order as s
	WHERE pt.product_template_id = t.template_id " . $cond . " AND t.status = " . $tot_status . " AND sos.status=0 AND s.stock_order_id=t.stock_order_id AND t.stock_order_id='" . $stock_order_id . "' AND t.template_order_id=sos.template_order_id   
	AND t.is_delete = 0 AND t.client_id = " . $client_id . "  AND pto.order_id = t.product_template_order_id) as pending,(SELECT count(t.template_order_id) as total FROM template_order t,product_template pt, product_template_order as pto,stock_order_status as sos,stock_order as s
	WHERE pt.product_template_id = t.template_id " . $cond . " AND t.status = " . $tot_status . "  AND s.stock_order_id=t.stock_order_id AND t.stock_order_id='" . $stock_order_id . "' AND t.template_order_id=sos.template_order_id   
	AND t.is_delete = 0 AND t.client_id = " . $client_id . "  AND pto.order_id = t.product_template_order_id) as total,(SELECT count(t.template_order_id) as accepted FROM template_order t,product_template pt, product_template_order as pto,stock_order_status as sos,stock_order as s
	WHERE pt.product_template_id = t.template_id " . $cond . " AND t.status = " . $tot_status . " ANd s.stock_order_id=t.stock_order_id AND t.stock_order_id='" . $stock_order_id . "' AND (sos.status=1 OR sos.status=3) AND  t.template_order_id=sos.template_order_id   
	AND t.is_delete = 0 AND t.client_id = " . $client_id . "  AND pto.order_id = t.product_template_order_id) as accepted,(SELECT count(t.template_order_id) as decline FROM template_order t,product_template pt, product_template_order as pto,stock_order_status as sos,stock_order as s
	WHERE pt.product_template_id = t.template_id " . $cond . " AND t.status = " . $tot_status . " AND sos.status=2 AND s.stock_order_id=t.stock_order_id AND t.stock_order_id='" . $stock_order_id . "' AND t.template_order_id=sos.template_order_id   
	AND t.is_delete = 0 AND t.client_id = " . $client_id . "  AND pto.order_id = t.product_template_order_id) as decline,(SELECT count(t.template_order_id) as dispatch FROM template_order t,product_template pt, product_template_order as pto,stock_order_status as sos,stock_order as s
	WHERE pt.product_template_id = t.template_id " . $cond . " AND t.status = " . $tot_status . " AND sos.status=3  AND s.stock_order_id=t.stock_order_id AND t.stock_order_id='" . $stock_order_id . "' AND t.template_order_id=sos.template_order_id   
	AND t.is_delete = 0 AND t.client_id = " . $client_id . "  AND pto.order_id = t.product_template_order_id) as dispatch";

        //echo $sql;
        //echo "<br>";
        //echo "==========================================================";
        $data = $this->query($sql);
        //printr($data);//die;
        if ($data->num_rows) {
            //echo $con;
            $dis_status = '';
            if ($status == '3' || $tot_status == '1') {
                $dis_status = 'OR sos.status=1 OR sos.status=2';
            }
            $sql1 = "select t.transport FROM template_order as t,stock_order_status as sos WHERE t.stock_order_id=" . $stock_order_id . "  AND (sos.status=" . $status . " " . $dis_status . ") AND t.template_order_id=sos.template_order_id ";
            //echo $sql1;echo "<br>";
            $data1 = $this->query($sql1);
            $transport = '';
            foreach ($data1->rows as $key => $d_rows) {
                //printr($d_rows);
                if ($d_rows['transport'] == 'By Sea') {
                    $tran = 'By Sea';
                } else {
                    $tran = 'By Air';
                }
                //echo $tran;
                $transport[$tran] = $tran;
            }
            //printr($transport);             
            return $total = array('total_count' => $data->row,
                'tran' => $transport);
        } else {
            return false;
        }
    }

    public function getMenuPermission($menu_id, $user_id, $user_type_id ,$n ='0') {
		
		if($n =='1')
		{
			 $cond = 'add_permission LIKE "%' . $menu_id . '%" OR edit_permission LIKE "%' . $menu_id . '%" OR delete_permission LIKE "%' . $menu_id . '%" OR view_permission LIKE "%' . $menu_id . '%"';
		}else{
        $cond = 'add_permission LIKE "%' . $menu_id . '%" AND edit_permission LIKE "%' . $menu_id . '%" AND delete_permission LIKE "%' . $menu_id . '%" AND view_permission LIKE "%' . $menu_id . '%"';
		}
        $sql = "SELECT email,user_name FROM " . DB_PREFIX . "account_master WHERE " . $cond . " AND user_type_id = '" . $user_type_id . "' 
		AND user_id ='" . $user_id . "'";
	//	echo $sql;                                      
        $data = $this->query($sql);
        return $data->rows;
    }

    public function GetOrderList($user_id, $usertypeid, $status = '', $data = '', $con = '', $filter_array = array(), $client_id = '', $dis_cond = '', $dis_table = '', $dis_select = '', $page = '', $st = '', $stock_order_id = '', $custom_order_id = '') {
        //printr($custom_order_id);
        //die; 
        $menu_id = $this->getMenuPermission(ORDER_ACCEPT_ID, $_SESSION['ADMIN_LOGIN_SWISS'], $_SESSION['LOGIN_USER_TYPE']);
        //printr($menu_id);
        $admin = '';
        if ($status == '')
            $status = 'AND pto.order_id = t.product_template_order_id';

        if ($_SESSION['ADMIN_LOGIN_USER_TYPE'] == 4) {
            $sqladmin = "SELECT user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "'  ";
            $dataadmin = $this->query($sqladmin);
            $cond = 'AND pto.admin_user_id = ' . $dataadmin->row['user_id'] . '';
            $admin_user_id = $dataadmin->row['user_id'];
            $table = 'employee as ib ,';
            //echo $cond;
        } elseif ($_SESSION['LOGIN_USER_TYPE'] == 4) {
            $cond = 'AND pto.admin_user_id = ' . $_SESSION['ADMIN_LOGIN_SWISS'] . ' ';
            //$cond = '';

            $admin_user_id = $user_id;
            $table = 'international_branch as ib , ';
        } else {
            $cond = ' ';
            $table = ' ';
            $admin_user_id = '';
            $page = 0;
        }
        if (($menu_id OR $_SESSION['LOGIN_USER_TYPE'] == 1 ) OR $data != 2) {
            //$con='';
            $cond = ' ';
            $table = ' ';
        }
        if ($page == 1) {
            $admin = 'AND pto.admin_user_id="' . $admin_user_id . '"';
        }

        /* if($in_process_status != '0')
          {
          $sql = "SELECT t.quantity, sos.dis_qty FROM stock_order_status as sos, template_order as t WHERE sos.template_order_id = t.template_order_id  AND t.client_id = '".$client_id."'";
          $d = $this->query($sql);
          //foreach
          printr($d);

          }
          else
          {

          } */

        if ($client_id != '') {
            $client_id = " AND t.client_id = '" . $client_id . "' AND ";
        } else {
            $client_id = " AND ";
        }
        if ($stock_order_id != '') {
            $stock_order_id = " t.stock_order_id= '" . $stock_order_id . "' AND";
        } else {
            $stock_order_id = "";
        }
        $sql = "SELECT " . $dis_select . " so.gen_order_id,t.client_id,t.order_type,t.expected_ddate,t.note,t.template_order_id,t.quantity,p.product_id,p.email_product,p.product_name,pt.title,pts.width,pts.height,pts.gusset,pto.shipment_country,pto.ship_type as pto_ship_type,pto.admin_user_id,t.transport,
pts.volume,t.note,c.country_name,cu.currency_code,pc.email_color,pc.color,t.user_id,t.user_type_id,t.price,pts.valve,cd.client_name,		pts.zipper,pts.spout,pts.accessorie,t.ship_type,pt.product_template_id,pts.product_template_size_id,t.address,t.date_added,pt.transportation_type,t.product_template_order_id,sos.review,sos.date,pto.order_id,sos.process_by,sos.dispach_by,sos.status,pts.quantity1000,pts.quantity2000,pts.quantity5000,pts.quantity10000,t.price_uk FROM " . DB_PREFIX . " template_order t,currency as cu,product as p,product_template pt,product_template_size as pts,country as c,pouch_color as pc, product_template_order as pto,stock_order_status as sos, courier as co,client_details as cd,stock_order as so " . $dis_table . "  WHERE t.product_id = p.product_id AND pt.product_template_id = t.template_id AND so.stock_order_id=t.stock_order_id AND so.client_id=t.client_id AND pto.admin_user_id=so.admin_user_id AND " . $con . " t.template_order_id=sos.template_order_id  " . $status . " AND pt.currency = cu.currency_id AND t.is_delete = 0  " . $cond . "  
" . $client_id . " " . $stock_order_id . "  t.template_size_id=pts.product_template_size_id AND pts.template_id = pt.product_template_id AND pc.pouch_color_id = t.color AND t.country=c.country_id AND pto.order_id = t.product_template_order_id " . $admin . " AND  t.is_delete = 0 AND t.client_id=cd.client_id";
        if (!empty($filter_array)) {
            if (!empty($filter_array['order_no'])) {
                $sql .= " AND so.gen_order_id = '" . $filter_array['order_no'] . "'";
            }

            if (!empty($filter_array['date'])) {
                $sql .= " AND date(t.date_added) = '" . date('Y-m-d', strtotime($filter_array['date'])) . "'";
            }
            if (!empty($filter_array['product_name'])) {
                $sql .= " AND p.product_name = '" . $filter_array['product_name'] . "'";
            }
            if (!empty($filter_array['postedby'])) {
                $spitdata = explode("=", $filter_array['postedby']);
                $sql .= " AND t.user_type_id = '" . $spitdata[0] . "' AND t.user_id = '" . $spitdata[1] . "'";
            }
        }
        $sql .= " GROUP BY t.template_order_id";

        if (isset($data['sort'])) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY t.template_order_id";
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
            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }
        $data = $this->query($sql);
        //printr($data);
        if ($custom_order_id != '') {
            $cust_data = $this->getCustomAcceptedRecords('3', $custom_order_id);
            //printr($cust_data);
            if (!empty($cust_data)) {
                foreach ($cust_data as $cust) {
                    //printr($cust);
                    array_push($data->rows, $cust);
                }
            }
        }
        if ($data->num_rows) {
            return $data->rows;
        } else {
            if ($custom_order_id != '')
                return $cust_data;
            else
                return false;
        }
    }

    public function sendOrderEmail($post = '', $status, $adminEmail, $check = 0) {

        //printr($post);//die;
        $id = array();
        foreach ($post as $val) {
            $arr = explode("==", $val);
            if (count($arr) == '2')
                $id[] = array('custom_order_id' => $arr[0], 'multi_custom_order_id' => $arr[1]);
            else
                $id[] = array('template_order_id' => $arr[0], 'product_template_order_id' => $arr[1], 'client_id' => $arr[2]);

            $k = $id[0];
        }//printr($id);die;
        $data = $this->query("SELECT group_id FROM stock_order_email_history_id ORDER BY group_id DESC LIMIT 1");
        if ($data->num_rows > 0) {
            $group_id = $data->row['group_id'] + 1;
        } else
            $group_id = 1;
        $con = '';
        foreach ($id as $order_id) {
            $decline_html = '';
            $html_ddate = '';

            $menu_id = 0;
            $template_order_id = '';
            if (!isset($order_id['custom_order_id'])) {
                $con = '  t.template_order_id = ' . $order_id['template_order_id'] . ' AND';
                $template_order_id = $order_id['template_order_id'];
            } else {
                $con = '';
            }
            if ($status > 0) {
                $dis_status = $dis_cond = $dis_table = $dis_select = '';

                if ($status == '3') {
                    //$dis_cond = 'OR ()';OR t.template_order_id=sodh.template_order_id AND t.product_template_order_id = sodh.product_template_order_id
                    $dis_status = 'OR sos.status= 1 ';
                }
                if (isset($order_id['custom_order_id']))
                    $cond = '';
                else
                    $cond = 'AND t.status = 1 AND (sos.status=' . $status . ' ' . $dis_status . ')  AND  t.client_id =' . $order_id['client_id'] . '';

                if ($status == 1) {
                    //accept order
                    $bg_color_code = '#FFF0BA';
                    $color_code = '#FFDC5C';
                    $span_color = '#FFC800';
                    $span_class = 'label bg-warning';
                    $subject = 'Accepted Orders';
                    $user_detail = $this->getdeatiluser($order_id['product_template_order_id'], $order_id['template_order_id']);
                    $datauser = $this->getUser($user_detail['user_id'], $user_detail['user_type_id']);
                    $datauser_admin = $this->getUser($user_detail['admin_user_id'], 4);
                    $toEmail[$datauser['user_name']] = $datauser['email'];
                    $toEmail[$datauser_admin['user_name']] = $datauser_admin['email'];
                    $final_ddate = $this->getFinalddate($order_id['product_template_order_id'], $order_id['template_order_id']);
                    ///printr($final_ddate);die;
                    $new_date = $final_ddate['new_final_ddate'];

                    $menu_id = array('79', '80');

                    $html_ddate = $new_date;
                }
                if ($status == 2) {
                    //decline order
                    if (isset($order_id['custom_order_id'])) {
                        $user_cust_detail = $this->getdeatilCustuser($order_id['multi_custom_order_id']);
                        $user_detail['user_id'] = $user_cust_detail['added_by_user_id'];
                        $user_detail['user_type_id'] = $user_cust_detail['added_by_user_type_id'];
                        $user_detail['admin_user_id'] = $user_cust_detail['admin_user_id'];
                        $dec_qty_cust = $this->getCustomAcceptedRecords('2', $order_id['custom_order_id']);
                        $dec_qty['decline_qty'] = $dec_qty_cust[0]['quantity'];
                    } else {
                        $user_detail = $this->getdeatiluser($order_id['product_template_order_id'], $template_order_id);
                        $dec_qty = $this->declineQty($order_id['template_order_id'], $order_id['product_template_order_id']);
                    }
                    //$user_detail=$this->getdeatiluser($order_id['product_template_order_id'],$order_id['template_order_id']);
                    $datauser = $this->getUser($user_detail['user_id'], $user_detail['user_type_id']);
                    $datauser_admin = $this->getUser($user_detail['admin_user_id'], 4);
                    $toEmail[$datauser['user_name']] = $datauser['email'];
                    $toEmail[$datauser_admin['user_name']] = $datauser_admin['email'];
                    $menu_id = array('79');

                    $decline_html = '<br><span>Your ' . $dec_qty['decline_qty'] . ' Qty is Rejected</span>';
                }
                if ($status == 3) {
                    //dispatch order
                    //$dis_cond = 'OR (t.template_order_id=sodh.template_order_id AND t.product_template_order_id = sodh.product_template_order_id)';
                    //$dis_table = ', stock_order_dispatch_history as sodh';	
                    //$dis_select = 'sodh.dis_qty,max(sodh.stock_order_dispatch_history_id),';
                    $bg_color_code = '#D2FFE5';
                    $color_code = '#8CED9C';
                    $span_color = '#D2FFE5';
                    $span_class = 'label bg-success';
                    $subject = 'Dispatched Orders';
                    $user_detail = $this->getdeatiluser($order_id['product_template_order_id'], $order_id['template_order_id']);
                    $datauser = $this->getUser($user_detail['user_id'], $user_detail['user_type_id']);
                    $datauser_admin = $this->getUser($user_detail['admin_user_id'], 4);
                    $toEmail[$datauser['user_name']] = $datauser['email'];
                    $toEmail[$datauser_admin['user_name']] = $datauser_admin['email'];
                    $menu_id = array('79', '80');
                }
            } elseif ($status == 0) {
                $cond = 'AND t.status = 1 AND sos.status=' . $status . ' AND pto.order_id=' . $order_id['product_template_order_id'] . ' AND  t.client_id =' . $order_id['client_id'] . '';
                //echo $order_id['template_order_id'];
                $user_detail = $this->getdeatiluser($order_id['product_template_order_id'], $order_id['template_order_id']);

                //printr($user_detail);die;
                $datauser = $this->getUser($user_detail['user_id'], $user_detail['user_type_id']);
                $datauser_admin = $this->getUser($user_detail['admin_user_id'], 4);
                $toEmail[$datauser['user_name']] = $datauser['email'];
                $toEmail[$datauser_admin['user_name']] = $datauser_admin['email'];
                $menu_id = array('79');
            }
            $permissionData = '';
            if ($menu_id > 0)
                $permissionData = $this->getUserPermission($menu_id);
            if (!empty($permissionData)) {
                foreach ($permissionData as $email_id) {
                    //printr($email_id);
                    $toEmail[$email_id['user_name']] = $email_id['email'];
                }
            }
            $setHtml = '';
            $sub = '';
            $insert_qry = '';
            $setHtml .= '<div class="table-responsive">';

            $custom_order_id = '';
            if (isset($order_id['custom_order_id']))
                $custom_order_id = $order_id['custom_order_id'];

            if (isset($order_id['custom_order_id']))
                $orders = $this->getCustomAcceptedRecords('2', $custom_order_id);
            else
                $orders = $this->GetOrderList($_SESSION['ADMIN_LOGIN_SWISS'], $_SESSION['LOGIN_USER_TYPE'], $cond, $status, $con);

            //$orders = $this->GetOrderList($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],$cond,$status,$con);
            //printr($orders);die;
            foreach ($orders as $data) {
                $new_data[$data['gen_order_id']][] = $data;
            }
            //printr($new_data);
            ksort($new_data);
            $f = 1;
            $total = 0;
            $total_qty = 0;
            $toEmail['swisspac'] = $adminEmail;
            $order_type = '';
            foreach ($new_data as $gen_order_id => $data) {
                $setHtml .= "<div><b>Order No : " . $gen_order_id . '</b>';
                $sub .= $gen_order_id . ' , ';
                foreach ($data as $order) {
                    if ($order['order_type'] != '')
                        $order_type = $order['order_type'];

                    $insert_qry .= "('" . $gen_order_id . "','" . $order['template_order_id'] . "','" . $order['product_template_order_id'] . "','" . $_SESSION['ADMIN_LOGIN_SWISS'] . "','" . $_SESSION['LOGIN_USER_TYPE'] . "',NOW(),'" . $group_id . "','" . $order['client_id'] . "','" . $check . "') , ";

                    $setHtml .= '<br><br>Your Reference : ' . $order['note'] . '<br>';
                    $setHtml .= '<br><br>' . $order['quantity'] . '&nbsp;&nbsp; X &nbsp;&nbsp;' . $order['volume'] . '&nbsp;';

                    if (!isset($order['custom_order_id']))
                        $setHtml .= '<span><b>' . preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $order['email_color']) . ' </b></span><span>';

                    if (isset($order['product_id']) && $order['product_id'] != 3)
                        $setHtml .= '<b>';
                    if (!isset($order['custom_order_id']))
                        $setHtml .= preg_replace("/\([^)]+\)/", "", preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $order['email_product']));
                    else
                        $setHtml .= preg_replace("/\([^)]+\)/", "", preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $order['product_name']));

                    if (isset($order['product_id']) && $order['product_id'] != 3)
                        $setHtml .= '</b>';
                    $setHtml .= '</span>';
                    $setHtml .= $decline_html;
                    //if($order['zipper']!='No zip')
                    $setHtml .= '<br>Option : <span style="color:#FF0000;"> ' . $order['zipper'] . '</span>  <span style="color:#060;">' . $order['valve'] . '</span> <span style="color:#FF6600;">' . $order['spout'] . '</span> <span style="color:#0000FF;">' . $order['accessorie'] . '</span>';
                    $setHtml .= '<br>';
                    $postedByData = $this->getUser($order['user_id'], $order['user_type_id']);
                }
                if ($order['address'] != '') {
                    $name = 'Customer&prime;s Address Below ';
                    $address = $order['address'];
                    $color = 'red';
                } else {
                    $name = 'Below Address';
                    $address = $postedByData['address'] . '<br>' . $postedByData['city'] . ' , ' . $postedByData['state'] . ' ( ' . $postedByData['country_name'] . ' )<br>' . $postedByData['postcode'] . '<br>' . $postedByData['email'];
                    $color = 'black';
                }
                if ($status != 2) {
                    $setHtml .= '<br><br><b><span style="color:' . $color . '">Dispatch Directly To ' . $name . '   <span style="color:blue">' . $order['transportation_type'] . '</span> :-</span></b><br><br><pre style="font-size: 15px;font-weight: bolder;color: black;"><b>' . $address . '</b></pre><br><br>';
                }
                if ($order['review'] != '' && $status == 2)
                    $setHtml .= '<br><br><b><span style="color:red">Review :-</span></b><br><br><pre style="font-size: 15px;font-weight: bolder;color: black;"><b>' . $order['review'] . '</b></pre><br><br>';
                //else
                ///	$setHtml.='<br><br><span style="color:red"><b>Dispatch Directly To '.$postedByData['first_name'].' '.$postedByData['last_name'].' Address Below :-</b></span><br><br><b><address>'.$postedByData['address'].'<br>'.$postedByData['city'].' , '.$postedByData['state'].' ( '.$postedByData['country_name'].' )<br>'.$postedByData['postcode'].'<br>'.$postedByData['email'].'</address></b>';
                //	$setHtml.='<br><br><b>Dispatch: ';
                //$setHtml.=$order['transportation_type'].'</b><br><br>';
                $setHtml .= '</div>';
            }
            $setHtml .= '<br>';
            $toEmail[$postedByData['user_name']] = $postedByData['email'];
            if (isset($adminpostedByData) && $adminpostedByData != '')
                $toEmail[$adminpostedByData['user_name']] = $adminpostedByData['email'];
            $sub = substr($sub, 0, -2);
            if ($status > 0) {
                if ($status == 2)
                    $subject = 'YOUR REJECTED ' . strtoupper($order_type) . ' OREDR NO: ' . $sub . ' Submited By ' . $datauser['user_name'];
                elseif ($status == 1)
                    $subject = 'YOUR ACCEPTED ' . strtoupper($order_type) . ' ORDER NO : ' . $sub;
                elseif ($status == 3)
                    $subject = 'YOUR DISPATCHED ' . strtoupper($order_type) . ' ORDER NO : ' . $sub;
            } else
                $subject = 'NEW ' . strtoupper($order_type) . ' ORDER : ' . $sub . ' Submited By ' . $datauser['user_name'];
            $insert_qry = substr($insert_qry, 0, -2);
        }
        $obj_email = new email_template();
        $rws_email_template = $obj_email->get_email_template(4);
        //$fromEmail = $postedByData['email'];
        $temp_desc = str_replace('\r\n', ' ', $rws_email_template['discription']);

        $path = HTTP_SERVER . "template/order_template.html";
        $output = file_get_contents($path);
        //printr($postedByData);//die;
        $search = array('{tag:header}', '{tag:details}');

        $message = '';
        $signature = '';
        if ($postedByData['email_signature']) {
            $signature = nl2br($postedByData['email_signature']);
        }
        //printr($setHtml);
        //die;
        if ($setHtml) {
            $tag_val = array(
                "{{productDetail}}" => $setHtml,
                "{{ddsignature}}" => $signature,
            );
            if (!empty($tag_val)) {
                $desc = $temp_desc;
                foreach ($tag_val as $k => $v) {
                    @$desc = str_replace(trim($k), trim($v), trim($desc));
                }
            }
            $replace = array($subject, $desc);
            $message = str_replace($search, $replace, $output);
        }
        $qstr = '';
        foreach ($toEmail as $toemail) {
            send_email($toemail, $adminEmail, $subject, $message, ''); //uncomment this line
        }
        $sql = "INSERT INTO stock_order_email_history_id (stock_order_id,template_order_id,product_template_order_id,user_id,user_type_id,date,group_id,client_id,email_id) VALUES " . $insert_qry . "";
        $data = $this->query($sql);
        $customerMessage = '';
        $AdmincustomerMessage = '';
        $signature = 'Thanks.';
    }

    public function deleteorder($data) {
        foreach ($data as $val) {
            $splitdata = explode('==', $val);
            $this->query("DELETE FROM " . DB_PREFIX . " template_order WHERE template_order_id='" . $splitdata[0] . "'");
            $this->query("DELETE FROM " . DB_PREFIX . " stock_order WHERE gen_order_id='" . $splitdata[3] . "' AND client_id='" . $splitdata[2] . "'");
            $this->query("DELETE FROM " . DB_PREFIX . " stock_order_status WHERE template_order_id='" . $splitdata[0] . "' AND product_template_order_id='" . $splitdata[1] . "'");
        }
    }

    public function getUserList_stock_order() {
        $sql = "SELECT am.user_type_id,am.user_id,am.account_master_id,am.user_name,a.country_id,c.country_name FROM " . DB_PREFIX . "account_master as am,address as a,country as c WHERE am.user_type_id=a.user_type_id AND am.user_id=a.user_id AND c.country_id=a.country_id ORDER BY am.user_name ASC";
        $data = $this->query($sql);
        return $data->rows;
    }
    function get_country_selected($country) {
        $sql = "SELECT country_name from country WHERE is_delete ='0' AND country_id='".$country."' ";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row;
        } else {
            return false;
        }
    }
	//[kinjal] on 18-4-2017
	public function getIndustrys() {
        $data = $this->query("SELECT enquiry_industry_id, industry FROM " . DB_PREFIX . "enquiry_industry WHERE is_delete = 0 ORDER BY industry ASC");
        //printr($data);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }
	//add sonu 18-4-2017
	public function getLatestQuotation($user_type_id,$user_id,$address_id,$n=0){
		$limit='';
				if($n=='0')
					$limit = 'LIMIT 0,5';
		if($user_type_id == 1 && $user_id == 1){
			
			$sql = "SELECT c.country_name,pq.product_quotation_id,mpq.multi_quotation_number,pq.multi_product_quotation_id,pq.quotation_status,pq.status,pq.customer_name,mpq.address_book_id,pq.product_name,pq.added_by_user_id,pq.added_by_user_type_id,pq.quotation_type,pq.date_added,pq.layer FROM " . DB_PREFIX . "multi_product_quotation pq ,multi_product_quotation_id mpq,country c WHERE pq.multi_product_quotation_id = mpq.multi_product_quotation_id AND c.country_id = pq.shipment_country_id AND pq.quotation_status = 1 AND  pq.status='1' AND mpq.address_book_id = '".$address_id."' GROUP BY mpq.multi_product_quotation_id ORDER BY pq.product_quotation_id DESC $limit";
	
		}else{
		
			
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."'");
			 
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{	
				//echo $user_type_id; echo $user_id;
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				//printr($userEmployee);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			
			$str = '';
			$str1='';
			if($userEmployee){//printr($userEmployee);die;
				$str = ' OR ( pq.added_by_user_id IN ('.$userEmployee.') AND pq.added_by_user_type_id IN ("2") ) ';
				
				$str1=' GROUP BY mpq.multi_product_quotation_id ORDER BY pq.product_quotation_id DESC '.$limit;
			}
			
			$sql = "SELECT c.country_name,pq.*,mpq.multi_quotation_number FROM " . DB_PREFIX . "multi_product_quotation pq,multi_product_quotation_id mpq,country c WHERE pq.multi_product_quotation_id = mpq.multi_product_quotation_id AND c.country_id = pq.shipment_country_id AND pq.status = '1'  AND mpq.address_book_id = '".$address_id."'  AND pq.quotation_status='1' AND (pq.added_by_user_id = '".(int)$set_user_id."' AND pq.added_by_user_type_id = '".(int)$set_user_type_id."'  ".$str." )  ".$str1."";
			
		}
		
//	echo $sql;
		$data = $this->query($sql);
			//printr($data);
		return $data->rows;
	}



	public function getUpcomingFollowup($address_id,$n=0)
	{
			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
			$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		
			$today_date = date("Y-m-d");
			
			//echo $today_date;die;
			$sql = "SELECT am.user_name,e.enquiry_id,e.user_id,e.user_type_id ,e.enquiry_number,ef.followup_date,CONCAT(e.first_name,' ',e.last_name) as name FROM " . DB_PREFIX . "enquiry e LEFT JOIN `" . DB_PREFIX . "enquiry_followup` ef ON (ef.enquiry_id=e.enquiry_id) LEFT JOIN `" . DB_PREFIX . "account_master` am ON (e.user_id = am.user_id) AND (e.user_type_id = am.user_type_id) WHERE e.is_delete=0 ";
			
			
			if($user_type_id != 1 && $user_id != 1){
				if($user_type_id == 2){
					$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				}else{
					$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				}
				$str = '';
				if($userEmployee){
					$str = ' OR ( e.user_id IN ('.$userEmployee.') AND e.user_type_id = "2" )';
				}
				
				$sql.= " AND ( e.user_id = '".(int)$user_id."' AND e.user_type_id = '".(int)$user_type_id."' $str)";
			}
			$limit='';
			if($n=='0')
				$limit = 'LIMIT 0,5';
				
			$sql.= " AND e.company_name_id ='".$address_id."' ORDER BY ef.followup_date ASC $limit" ;	
			//echo $sql;
			$data = $this->query($sql);
			
			if($data->num_rows){
				return $data->rows;	
			}else{
				return false;
			}
		
		}

 public function getLatestProforma_pro_wise($user_id,$user_type_id,$address_book_id,$n=0) {

        if ($user_type_id == 1 && $user_id == 1) {

            $sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c WHERE c.country_id=p.destination AND p.address_book_id = '" . $address_book_id . "'  ";
        } else {

            if ($user_type_id == 2) {

                $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");

                $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);

                $set_user_id = $parentdata->row['user_id'];

                $set_user_type_id = $parentdata->row['user_type_id'];
            } else {

                $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);

                $set_user_id = $user_id;

                $set_user_type_id = $user_type_id;
            }

            $str = '';

            if ($userEmployee) {

                $str = ' OR ( p.added_by_user_id IN (' . $userEmployee . ') AND  p.added_by_user_type_id = 2 )';
            }

            $sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c WHERE c.country_id=p.destination AND (p.added_by_user_id = '" . $set_user_id . "' AND p.added_by_user_type_id = '" . $set_user_type_id . "' $str) AND p.address_book_id = '" . $address_book_id . "' ";
        }

        
        //echo $sql;


		if($n=='0')
				$sql .= "LIMIT 0,5";
				
        $data = $this->query($sql);

        if ($data->num_rows) {

            return $data->rows;
        } else {

            return false;
        }
    }
	//kavita 25-5-2017 start///////////////////////////////////////*********************
	
	
	
	public function getCustomerName()
	{
		
		
		$sql="SELECT DISTINCT company_name,address_book_id fROM address_book_master  WHERE  is_delete ='0' ORDER BY address_book_id DESC";
		$data = $this->query($sql);
			//printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
		
		
	}

	
	public function viewAddressBookReport($post)
	{	
		
		//printr($post);die;
		$to_date = $post['t_date'];
        $f_date = $post['f_date'];
        $con = "AND pb.date_added >= '" . $f_date . "' AND  pb.date_added <='" . $to_date . "' ";
        
		
		if(isset($post['user_name']) && !empty($post['user_name']))
			$ib=explode("=",$post['user_name']);
		
		if(isset($post['emp_name']) && !empty($post['emp_name']))
		{
			$emp=explode("=",$post['emp_name']);
			$datauser_emp = $this->getUser($emp[1], 2);
		}
		else
		{			
			$userEmployee = $this->getUserEmployeeIds('4', $ib[1]);
		}
		
		$datauser_admin = $this->getUser($ib[1], 4);
		
		//printr($datauser_admin);
		$address_book_id = $post['customer_name'];
		
		$str = '';
		$enquiry= $Followup=$Proforma_pro=$Proforma=$CustomOrders=$CartOrder=$Invoice=$Quotation=0;
		if(empty($post['emp_name']))
		{
			//Enquiry & //Followup
			if ($userEmployee) {

                $str = ' OR ( e.user_id IN (' . $userEmployee . ') AND  e.user_type_id = 2 )';
				$str_pro = ' OR ( p.added_by_user_id IN (' . $userEmployee . ') AND  p.added_by_user_type_id = 2 )';
				$str_p = " OR ( p.added_by_user_id IN (" . $userEmployee . ") AND  p.added_by_user_type_id = '2' )";
				$str_quo = ' OR ( pq.added_by_user_id IN ('.$userEmployee.') AND pq.added_by_user_type_id = 2 ) ';
				$str_cust = " OR ( mco.added_by_user_id IN (" . $userEmployee . ") AND mco.added_by_user_type_id = '2' )";
				$str_stk= "AND (pto.admin_user_id=".$ib[1]." OR ( t.user_type_id=2 AND t.user_id IN (" . $userEmployee . ")))";
				$str_inv="OR ( inv.user_type_id=2 AND inv.user_id IN (" . $userEmployee . "))";
            }
			$sql="SELECT COUNT(*) as total FROM `enquiry` e LEFT JOIN `enquiry_source` es ON (e.enquiry_source_id = es.enquiry_source_id) LEFT JOIN `country` c ON (e.country_id = c.country_id) LEFT JOIN `account_master` am ON (e.user_id = am.user_id) AND (e.user_type_id = am.user_type_id) WHERE e.is_delete = 0 AND e.company_name_id ='5' AND ((e.user_id='".$ib[1]."' AND  e.user_type_id=4) $str ) AND e.date_added >= '" . $f_date . "' AND  e.date_added <='" . $to_date . "'";
		
			$data = $this->query($sql);
			if($data->num_rows)
				$enquiry=$Followup=$data->row['total'];
			
			//Proforma Product code wise
			$sql_pro="SELECT  COUNT(*) as total FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c WHERE c.country_id=p.destination AND (p.added_by_user_id = '" .$ib[1]. "' AND p.added_by_user_type_id = '4' $str_pro) AND p.address_book_id = '" . $address_book_id . "' AND p.date_added >= '" . $f_date . "' AND  p.date_added <='" . $to_date . "' ";
			$data_pro = $this->query($sql_pro);
			if($data_pro->num_rows)
				$Proforma_pro=$data_pro->row['total'];
			
			//Proforma 
			$sql_p = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "proforma as p,country as c WHERE c.country_id=p.destination AND ((p.added_by_user_id = '" . $ib[1] . "' AND p.added_by_user_type_id = '4') $str_p) AND p.is_delete = 0 AND p.address_book_id='". $address_book_id ."' AND p.date_added >= '" . $f_date . "' AND  p.date_added <='" . $to_date . "'";
			$data_p = $this->query($sql_pro);
			if($data_p->num_rows)
				$Proforma=$data_p->row['total'];
			
			//Multi Quo
			$sql_quo="SELECT COUNT(*) as total FROM " . DB_PREFIX . "multi_product_quotation pq,multi_product_quotation_id mpq,country c WHERE pq.multi_product_quotation_id = mpq.multi_product_quotation_id AND c.country_id = pq.shipment_country_id AND pq.status = '1'  AND mpq.address_book_id = '".$address_book_id."'  AND pq.quotation_status='1' AND (pq.added_by_user_id = '".$ib[1]."' AND pq.added_by_user_type_id = '4'  ".$str_quo." ) AND mpq.date_added >= '" . $f_date . "' AND  mpq.date_added <='" . $to_date . "'";
			//echo $sql_quo;
			$data_quo = $this->query($sql_quo);
		//	printr($data_quo);
			if($data_quo->num_rows)
				$Quotation=$data_quo->row['total'];
			
			//Custom Order
			$sql_cust = "SELECT COUNT(*) as total FROM multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.address_book_id='" . $address_book_id . "' AND ((mco.added_by_user_id = '" .$ib[1]. "' AND mco.added_by_user_type_id = '4' ) $str_cust) AND mcoi.date_added >= '" . $f_date . "' AND  mcoi.date_added <='" . $to_date . "'";
			$data_cust = $this->query($sql_cust);
			if($data_cust->num_rows)
				$CustomOrders=$data_cust->row['total'];
			
			//Stock Order
			$sql_stk = 'SELECT COUNT(*) as total FROM template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto,product_template as pt,currency as cu WHERE c.country_id = t.country AND cd.client_id = t.client_id AND t.is_delete = 0 '.$str_stk.' AND (sos.status="0" ) AND t.status=1 AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id AND st.address_book_id = '. $address_book_id .' AND t.date_added >= ' . $f_date . ' AND  t.date_added <=' . $to_date . '  GROUP BY st.stock_order_id, pto.admin_user_id ORDER BY t.template_order_id DESC';
			$data_stk = $this->query($sql_stk);
			if($data_stk->num_rows)
				$CartOrder=$data_stk->row['total'];
			
			//Sales Inv and Payment
			$sql_inv = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "sales_invoice as inv LEFT JOIN country as c ON c.country_id=inv.final_destination WHERE  inv.is_delete = 0 AND inv.gen_status='0' AND ((inv.user_id = '" .$ib[1] . "' AND inv.user_type_id = '4' ) $str_inv )AND inv.address_book_id='".$address_book_id."'  AND inv.date_added >= '" . $f_date . "' AND  inv.date_added <='" . $to_date . "'";
			$data_inv = $this->query($sql_inv);
			if($data_inv->num_rows)
				$Invoice=$data_inv->row['total'];
		}
		else
		{
			//Enquiry & //Followup
			$sql="SELECT COUNT(*) as total FROM `enquiry` e LEFT JOIN `enquiry_source` es ON (e.enquiry_source_id = es.enquiry_source_id) LEFT JOIN `country` c ON (e.country_id = c.country_id) LEFT JOIN `account_master` am ON (e.user_id = am.user_id) AND (e.user_type_id = am.user_type_id) WHERE e.is_delete = 0 AND e.company_name_id ='" . $address_book_id . "' AND e.user_id = ".$emp[1]." AND  e.user_type_id = 2 AND e.date_added >= '" . $f_date . "' AND  e.date_added <='" . $to_date . "' ";
			//printr($sql);
			$data = $this->query($sql);
			if($data->num_rows)
				$enquiry=$Followup=$data->row['total'];
			
			//Proforma Product code wise
			$sql_pro="SELECT  COUNT(*) as total FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c WHERE c.country_id=p.destination AND p.added_by_user_id = '" .$emp[1]. "' AND p.added_by_user_type_id = '2' AND p.address_book_id = '" . $address_book_id . "' AND p.date_added >= '" . $f_date . "' AND  p.date_added <='" . $to_date . "'";
			$data_pro = $this->query($sql_pro);
			//printr($data_pro);
			if($data_pro->num_rows)
				$Proforma_pro=$data_pro->row['total'];
			
			//Proforma 
			$sql_p = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "proforma as p,country as c WHERE c.country_id=p.destination AND (p.added_by_user_id = '" . $emp[1] . "' AND p.added_by_user_type_id = '2') AND p.is_delete = 0 AND p.address_book_id='". $address_book_id ."' AND p.date_added >= '" . $f_date . "' AND  p.date_added <='" . $to_date . "'";
			//printr($sql_p);
			$data_p = $this->query($sql_p);
			if($data_p->num_rows)
				$Proforma=$data_p->row['total'];
			
			//Multi Quo
			$sql_quo="SELECT COUNT(*) as total FROM " . DB_PREFIX . "multi_product_quotation pq,multi_product_quotation_id mpq,country c WHERE pq.multi_product_quotation_id = mpq.multi_product_quotation_id AND c.country_id = pq.shipment_country_id AND pq.status = '1'  AND mpq.address_book_id = '".$address_book_id."'  AND pq.quotation_status='1' AND (pq.added_by_user_id = '".$emp[1]."' AND pq.added_by_user_type_id = '2') AND mpq.date_added >= '" . $f_date . "' AND  mpq.date_added <='" . $to_date . "'";
			$data_quo = $this->query($sql_quo);
			if($data_quo->num_rows)
				$Quotation=$data_quo->row['total'];
			
			//Custom Order
			$sql_cust = "SELECT COUNT(*) as total FROM multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.address_book_id='" . $address_book_id . "' AND mco.added_by_user_id = '" .$emp[1]. "' AND mco.added_by_user_type_id = '2' AND mcoi.date_added >= '" . $f_date . "' AND  mcoi.date_added <='" . $to_date . "'";
			//printr($sql_cust);
			$data_cust = $this->query($sql_cust);
			if($data_cust->num_rows)
				$CustomOrders=$data_cust->row['total'];
			
			//Stock Order
			$sql_stk = 'SELECT COUNT(*) as total FROM template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto,product_template as pt,currency as cu WHERE c.country_id = t.country AND cd.client_id = t.client_id AND t.is_delete = 0 AND (pto.admin_user_id='.$ib[1].' OR ( t.user_type_id=2 AND t.user_id = '.$emp[1].')) AND (sos.status="0" ) AND t.status=1 AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id AND st.address_book_id = '. $address_book_id .'  AND t.date_added >= ' . $f_date . ' AND  t.date_added <=' . $to_date . '  GROUP BY st.stock_order_id, pto.admin_user_id ORDER BY t.template_order_id DESC';
			$data_stk = $this->query($sql_stk);
			if($data_stk->num_rows)
				$CartOrder=$data_stk->row['total'];
			
			//Sales Inv and Payment
			$sql_inv = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "sales_invoice as inv LEFT JOIN country as c ON c.country_id=inv.final_destination WHERE  inv.is_delete = 0 AND inv.gen_status='0' AND ((inv.user_id = '" .$ib[1] . "' AND inv.user_type_id = '4' ) OR ( inv.user_type_id=2 AND inv.user_id = " . $emp[1] . ") )AND inv.address_book_id='".$address_book_id."'  AND inv.date_added >= '" . $f_date . "' AND  inv.date_added <='" . $to_date . "' ";
			$data_inv = $this->query($sql_inv);
			if($data_inv->num_rows)
				$Invoice=$data_inv->row['total'];
		}
		
		$address_book_details = $this->all_customer_address($post['customer_name']);
		$html = '';
		
			$html .= "<div class='form-group'>
						<div class='table-responsive'>";
						$html .= "<center><span><b>Contacts Report</b></span></center><br>";
			
						$html .= "<table class='table table-striped b-t text-small' id='add_book_report'>
									<thead>
										<tr><th colspan='4'>Admin Name : ".$datauser_admin['first_name'].' '.$datauser_admin['last_name']."</th>
											<th colspan='4'>Date From : ".dateFormat(4,$f_date).' To Date : '.dateFormat(4,$to_date)."</th>
										</tr>";
						if(isset($post['emp_name']) && !empty($post['emp_name']))		
							  $html .= "<tr><th colspan='8'>Employee Name : ".$datauser_emp['first_name'].' '.$datauser_emp['last_name']."</th></tr>";
								
							  $html .= "<tr><th colspan='8'>Company Name : ".$address_book_details['company_name']."</th></tr>
										<tr>
											  <th>Enquiry</th>
											  <th>Upcoming Folliowup</th>
											  <th>Qutation</th>            
											  <th>Stock order</th>    
											  <th>Payment</th>
											  <th>Proforma Invoice</th>
											  <th>Custom Order</th>
											  <th>Sales Invoice</th>
											 
										</tr>
									</thead>
									<tbody>";
									
								
										$html .= "<tr>
														<td ><a href=" . HTTP_SERVER . "admin/index.php?route=enquiry&mod=index&address_book_id=" . encode($address_book_id) . ">".$enquiry."</a></td>
														<td><a href=" . HTTP_SERVER . "admin/index.php?route=enquiry&mod=followup_history&address_book_id=" . encode($address_book_id) . ">".$Followup."</a></td>
														<td><a href=" . HTTP_SERVER . "admin/index.php?route=multi_product_quotation&mod=index&address_book_id=" . encode($address_book_id) . ">".$Quotation."</a></td>
														<td><a href=" . HTTP_SERVER . "admin/index.php?route=template_order&mod=cartlist_view&status=0&address_book_id=" . encode($address_book_id) . ">".$CartOrder."</a></td>
														<td><a href=" . HTTP_SERVER . "admin/index.php?route=sales_invoice&mod=index&is_delete=0&address_book_id=" . encode($address_book_id) . ">".$Invoice."</a></td>
														<td><a href=" . HTTP_SERVER . "admin/index.php?route=proforma_invoice_product_code_wise&mod=index&is_delete=0&address_book_id=" . encode($address_book_id) . ">".$Proforma_pro."</a></td>
														<td><a href=" . HTTP_SERVER . "admin/index.php?route=custom_order&mod=index&address_book_id=" . encode($address_book_id) . ">".$CustomOrders."</a></td>
														<td><a href=" . HTTP_SERVER . "admin/index.php?route=sales_invoice&mod=index&is_delete=0&address_book_id=" . encode($address_book_id) . ">".$Invoice."</a></td>
													
												  </tr>";
												  
								
									
						$html .= "</tbody>
								</table>
							 </div>
						</div>";
				
			//printr($html);
			return $html;
		
		
		
		

	}
//End
	public function getIBList() {
        $sql = "SELECT international_branch_id,address_id,CONCAT(first_name,' ',last_name) as user_name FROM international_branch";
        $data = $this->query($sql);
        //printr($data);die;
        return $data->rows;
    }
	
	public function getEmpList($ib_id,$val)
	{
		$ib=explode('=',$ib_id);
		
		$userEmployee = $this->getUserEmployeeIds('4', $ib[1]);
		
		$sql = "SELECT CONCAT(first_name,' ',last_name) as user_name,employee_id FROM employee WHERE employee_id IN (".$userEmployee.") AND is_delete=0";
		//echo $sql;
		$data = $this->query($sql);
		return $data->rows;
		
        
	}
	public function getCustomer($ib_id,$emp_id='')
	{
		$ib=explode('=',$ib_id);
		if($emp_id=='')
		{
			$userEmployee = $this->getUserEmployeeIds('4', $ib[1]);
			$sql = "SELECT company_name,address_book_id FROM address_book_master WHERE ((user_id='".$ib[1]."' AND user_type_id='4') OR (user_id IN (".$userEmployee.") AND user_type_id=2)) AND is_delete=0";
			echo $sql;
			$data = $this->query($sql);
			
		}
		else
		{
			$emp=explode('=',$emp_id);
			$sql = "SELECT company_name,address_book_id FROM address_book_master WHERE (user_id='".$emp[1]."' AND user_type_id='2') AND is_delete=0";
			$data = $this->query($sql);
		}
		return $data->rows;	
		
		
	}
	public function InsertCSVData($handle)
	{
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$data=array();
		$first_time = true;
		$invoice_no='';
		$ibInfo = $this->getUser($user_id,$user_type_id);
		
		$qty_new = 0;
		if($user_id==1 && $user_type_id==1)
		{
			$addedByInfo['company_address']=$ibInfo['address'];
			$addedByInfo['bank_address']='';
		}
		else
		{
			$addedByInfo['company_address']=$ibInfo['company_address'];
			$addedByInfo['bank_address']=$ibInfo['bank_address'];
		}
		
	  	//loop through the csv file 
		while($data = fgetcsv($handle,1000,","))
		{ 
			if ($first_time == true) {
				$first_time = false;
				continue;
			}
			//printr($data);	
				$company_name=$data[0];
				$contact_name=$data[1].''.$data[2];
				$address=$data[3];
				$city=$data[4];
				$country=$data[5];
				$state=$data[6];
				$zip=$data[7];
				$phone=$data[8];
				$phone1=$data[9];
				$email=$data[10];
				$email1=$data[11];
				$website=$data[12];
				$remarks=$data[13];
				$user_id=$data[14];
				$user_type_id=$data[15];

				$esql = $this->query("SELECT address_book_id FROM company_address WHERE email_1 ='".$email."' AND user_id = '" .$user_id . "' AND  user_type_id = '" .$user_type_id. "'");
				//printr($esql);
				if($esql->row['address_book_id']==0)
				{
				    /*printr("INSERT INTO " . DB_PREFIX . "address_book_master SET company_name='".addslashes($company_name). "', contact_name = '" . addslashes($contact_name) . "',website = '" . $website . "', remark = '" . $remarks. "', user_id = '" .$user_id . "',user_type_id = '" . $user_type_id . "',status = '1', date_added = NOW(),date_modify = NOW()");
				    printr("INSERT INTO " . DB_PREFIX . "company_address SET address_book_id = '" .$address_id. "', c_address = '" .addslashes($address) . "', city = '" . addslashes($city). "',state = '" . $state. "',country = '" . $country. "',pincode = '" . $zip. "',phone_no = '" . $phone. "',email_1='".$email."',email_2='".$email1."',user_id = '" .$user_id . "', user_type_id = '" .$user_type_id. "',	date_added = NOW(),date_modify = NOW()");
				    printr("INSERT INTO " . DB_PREFIX . "factory_address SET address_book_id = '" .$address_id. "', f_address = '" .addslashes($address) . "', city = '" . addslashes($city). "',state = '" . $state. "',country = '" . $country. "',pincode = '" . $zip. "',phone_no = '" . $phone1. "',user_id = '" .$user_id . "', user_type_id = '" .$user_type_id. "',	date_added = NOW(),date_modify = NOW()");
				    */
				    $this->query("INSERT INTO " . DB_PREFIX . "address_book_master SET company_name='".addslashes($company_name). "', contact_name = '" . addslashes($contact_name) . "',website = '" . $website . "', remark = '" . $remarks. "', user_id = '" .$user_id . "',user_type_id = '" . $user_type_id . "',status = '1', date_added = NOW(),date_modify = NOW()");
					$address_id = $this->getLastId();
						$this->query("INSERT INTO " . DB_PREFIX . "company_address SET address_book_id = '" .$address_id. "', c_address = '" .addslashes($address) . "', city = '" . addslashes($city). "',state = '" . $state. "',country = '" . $country. "',pincode = '" . $zip. "',phone_no = '" . $phone. "',email_1='".$email."',email_2='".$email1."',user_id = '" .$user_id . "', user_type_id = '" .$user_type_id. "',	date_added = NOW(),date_modify = NOW()");
						$company_address_id = $this->getLastId();
					$this->query("INSERT INTO " . DB_PREFIX . "factory_address SET address_book_id = '" .$address_id. "', f_address = '" .addslashes($address) . "', city = '" . addslashes($city). "',state = '" . $state. "',country = '" . $country. "',pincode = '" . $zip. "',phone_no = '" . $phone1. "',user_id = '" .$user_id . "', user_type_id = '" .$user_type_id. "',	date_added = NOW(),date_modify = NOW()");
						$factory_address_id = $this->getLastId();
				}
				else
				{
				    $this->query(" UPDATE  address_book_master SET company_name='" .addslashes($company_name). "', contact_name = '" .addslashes($contact_name). "',website = '" . $website . "',remark = '" . $remarks. "', user_id = '" .$user_id . "',user_type_id = '" . $user_type_id . "',status = '1', date_modify = NOW()  WHERE address_book_id ='" . $esql->row['address_book_id'] . "' AND is_delete = '0' ");
                    $this->query("UPDATE company_address SET  c_address = '" .addslashes($address) . "', city = '" . addslashes($city). "',state = '" . $state. "',country = '" . $country. "',pincode = '" . $zip. "',phone_no = '" . $phone. "',email_1='".$email."',email_2='".$email1."',user_id = '" .$user_id . "', user_type_id = '" .$user_type_id. "',	date_modify = NOW() WHERE address_book_id ='" . $esql->row['address_book_id'] . "' AND is_delete = '0'");
                    $fact = $this->query("SELECT address_book_id FROM factory_address WHERE address_book_id ='".$esql->row['address_book_id']."'");
                    if($fact->num_rows==0)
                        $this->query("INSERT INTO " . DB_PREFIX . "factory_address SET address_book_id = '" .$esql->row['address_book_id']. "', f_address = '" .addslashes($address) . "', city = '" . addslashes($city). "',state = '" . $state. "',country = '" . $country. "',pincode = '" . $zip. "',phone_no = '" . $phone1. "',user_id = '" .$user_id . "', user_type_id = '" .$user_type_id. "',	date_added = NOW(),date_modify = NOW()");
				    else
				        $this->query("UPDATE factory_address SET address_book_id = '" .$esql->row['address_book_id']. "', f_address = '" .addslashes($address) . "', city = '" . addslashes($city). "',state = '" . $state. "',country = '" . $country. "',pincode = '" . $zip. "',phone_no = '" . $phone1. "',user_id = '" .$user_id . "', user_type_id = '" .$user_type_id. "',date_modify = NOW() WHERE address_book_id ='" . $esql->row['address_book_id'] . "' AND is_delete = '0'");
				    
				}

		}
		//die;
		return true;
	}
	
	public function getTotalBranch($filter_array=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "international_branch` ib LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=ib.user_name AND am.user_id = ib.international_branch_id AND am.user_type_id = '4' LEFT JOIN " . DB_PREFIX . "address addr ON (ib.address_id = addr.address_id) WHERE ib.is_delete = '0'";
		
		if(!empty($filter_array)){						
			if(!empty($filter_array['email'])){
				$sql .= " AND am.email LIKE '%".$filter_array['email']."%'";
			}				
			if($filter_array['status'] != ''){
				$sql .= " AND ib.status = '".$filter_array['status']."' ";
			}			
			if(!empty($filter_array['name'])){
				$sql .= " AND CONCAT(ib.first_name,' ',ib.last_name) LIKE '%".$this->escape($filter_array['name'])."%'";
			}									
		}
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getBranchs($data = array(),$filter_array=array()){
		$sql = "SELECT *,CONCAT(ib.first_name,' ',ib.last_name) as name,am.email FROM `" . DB_PREFIX . "international_branch` ib LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=ib.user_name AND am.user_id = ib.international_branch_id AND am.user_type_id = '4' LEFT JOIN " . DB_PREFIX . "address addr ON (ib.address_id = addr.address_id)  WHERE ib.is_delete = '0'";

		if(!empty($filter_array)){			
			if(!empty($filter_array['email'])){
				$sql .= " AND am.email LIKE '%".$filter_array['email']."%'";
			}			
			if($filter_array['status'] != ''){
				$sql .= " AND ib.status = '".$filter_array['status']."' ";
			}			
			if(!empty($filter_array['name'])){
								$sql .= " HAVING name LIKE '%".$this->escape($filter_array['name'])."%'" ;
			}							
		}		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY international_branch";	
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
    public function getdefaultcurrencyCode($default_curr)
	{
		$sql = "SELECT currency_code FROM country where status = 1 and currency_code!='' and country_id = '".$default_curr."' LIMIT 1";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row['currency_code'];
		}
		else
		{
			return false;
		}
	}
	
	public function getCountryName($country_id)
	{
		$sql = "SELECT country_name FROM country where status = 1 and country_code!='' and currency_code!='' and country_id = '".$country_id."' LIMIT 1";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row['country_name'];
		}
		else
		{
			return false;
		}		
	}
	public function getTotalProducts()
	{
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product` WHERE status=1 AND is_delete = 0";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	public function getIbCountry()
	{
		$sql = "SELECT ib.international_branch_id,CONCAT(ib.first_name,' ',ib.last_name) as name,c.country_name FROM international_branch as ib, address as addr, country as c WHERE ib.address_id = addr.address_id AND ib.is_delete = '0' AND addr.country_id = c.country_id";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	public function report($post)
	{
	    $data=$this->get_customer_address(array(),array(),$post['group'],'4',$post);
	    $html ="";
	    if(!empty($data))
	    {
	         $html .= "<div class='form-group'>
    					 <div class='table-responsive'>";
    					 $html .= "<table class='table table-striped b-t text-small' id='' border='1'>
        								<thead>
        									<tr>
        										<th>Name Of Customer</th>
        										<th>FirstName</th>
        										<th>LastName</th>
        										<th>address</th>
        										<th>POCity</th>
        										<th>POCountry</th>
        										<th>STATE</th>
        										<th>POPostalCode</th>
        										<th>PhoneNumber</th>
        										<th>PHONE 1</th>
        										<th>Email</th>
        										<th>EmailAddress</th>
        										<th>WEBSITE</th>
        										<th>REMARK</th>
        										<th>USER ID</th>
        										<th>USER TYPE ID</th>
                    					    </tr>
                    					</thead>
                    					<tbody>";
                					     foreach($data as $dt)
                					     {    $country = $this->getCountryName($dt['country']);
                					         $html.="<tr>   
                					                    <td>".$dt['company_name']."</td>
                					                    <td>".$dt['contact_name']."</td>
                					                    <td></td>
                					                    <td>".$dt['c_address']."</td>
                										<td>".$dt['city']."</td>
                										<td>".$country."</td>
                										<td>".$dt['state']."</td>
                										<td>".$dt['pin_code']."</td>
                										<td>".$dt['phone_no']."</td>
                										<td></td>
                										<td>".$dt['email_1']."</td>
                										<td>".$dt['email_2']."</td>
                										<td>".$dt['website']."</td>
                										<td>".$dt['remark']."</td>
                										<td>".$dt['user_id']."</td>
                										<td>".$dt['user_type_id']."</td>
                									</tr>";
                					      }
        						$html .="</tbody>
    					        </table>
    		             </div>
		            </div>";
	    }
	    //printr($html);die;
	    return $html;
	}
	public function customer_followups_report($post)
	{//created by kinjal on 26-6-2019 for the custom / stock orders
	   $str =$date_con='';
	   $userEmployee = $this->getUserEmployeeIds('4', $post['group']);
	   if($userEmployee)
	        $str =" OR (p.added_by_user_id IN (".$userEmployee.") AND p.added_by_user_type_id=2)";
	   
	   if($post['f_date']!='' && $post['t_date']!='')
	        $date_con = " AND p.date_added >= '" .$post['f_date']. "' AND  p.date_added <='" .$post['t_date']. "' ";
	   
	   //printr("SELECT * FROM proforma_product_code_wise as p WHERE NOT EXISTS ( SELECT * FROM sales_invoice as s WHERE s.proforma_no=p.pro_in_no AND s.is_delete=0) ".$date_con." AND (p.added_by_user_id = '".$post['group']."' AND p.added_by_user_type_id = '4' $str) ");die;
	 // SELECT s.pro_in_no as sales,p.pro_in_no,p.invoice_date FROM proforma_product_code_wise as p LEFT JOIN packing_order as s ON s.pro_in_no=p.pro_in_no WHERE p.date_added >= '2019-06-01' AND p.date_added <='2019-06-30' AND (p.added_by_user_id = '10' AND p.added_by_user_type_id = '4' OR (p.added_by_user_id IN (35,49,50,51,66,79,181,190,192) AND p.added_by_user_type_id=2)) AND s.pro_in_no IS NULL GROUP BY p.email ORDER BY `proforma_id` DESC
    //SELECT s.proforma_no as sales,p.pro_in_no,p.invoice_date,p.customer_name,p.email FROM proforma_product_code_wise as p LEFT JOIN sales_invoice as s ON s.proforma_no=p.pro_in_no WHERE (p.added_by_user_id = '19' AND p.added_by_user_type_id = '4' OR (p.added_by_user_id IN (56,58,211,217) AND p.added_by_user_type_id=2)) AND s.proforma_no IS NULL GROUP BY p.email ORDER BY `proforma_id` DESC
	   /*if($post['order_type']=='Stock')
	   {*/
	       $stk = $this->query("SELECT s.pro_in_no as sales,p.pro_in_no,p.invoice_date,p.customer_name,p.email FROM proforma_product_code_wise as p LEFT JOIN sales_invoice as s ON s.proforma_no=p.pro_in_no WHERE ".$date_con." AND (p.added_by_user_id = '".$post['group']."' AND p.added_by_user_type_id = '4' $str) AND s.proforma_no IS NULL GROUP BY p.email ORDER BY `proforma_id` DESC");
	       $stk1 = $this->query("SELECT s.pro_in_no as sales,p.pro_in_no,p.invoice_date,p.customer_name,p.email FROM proforma_product_code_wise as p LEFT JOIN packing_order as s ON s.pro_in_no=p.pro_in_no WHERE ".$date_con." AND (p.added_by_user_id = '".$post['group']."' AND p.added_by_user_type_id = '4' $str) AND s.pro_in_no IS NULL GROUP BY p.email ORDER BY `proforma_id` DESC");
	   /*}
	   else if($post['order_type']=='Custom')
	   {
	       
	   }
	   else
	   {
	       
	   }*/
	}
	
}

?>