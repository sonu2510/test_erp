<?php
class attendance_calender extends dbclass{
	
	
	public function getTotalStaffGroup()
	{
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "staff_group_details` WHERE status = '1' AND  is_delete = '0' ";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	public function getStaffGroup($data=array())
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "staff_group_details` WHERE is_delete = '0' AND status = '1'";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY staff_group_id";	
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
	public function getGroup($staff_group_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "staff_group_details` WHERE 	staff_group_id = '" .(int)$staff_group_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function emp_staff_detail($staff_group_id,$date)
	{
	
	//	$sql = "SELECT * FROM `" . DB_PREFIX . "emp_staff_detail` WHERE  status ='1' AND  is_delete='0' AND staff_group_id = '" .(int)$staff_group_id. "' ORDER BY series ASC ";
			
    
    
    //	$sql = "SELECT * FROM `" . DB_PREFIX . "emp_staff_detail` WHERE is_delete = '0' AND is_delete=0  AND staff_group_id = '" .(int)$staff_group_id. "' AND (status = 1 OR (status=0 AND dolev >='".$date."'))  ORDER BY series ASC";
	
	    $d=strtotime($date);
	    $month=date('m',$d);
	    $year=date('Y',$d);
	    //printr($d.'date'.$d.'$month'.$month.'$year'.$year);die;
	 //   printr('$year'.$year);die;
//	 printr($date);
//	 printr($month);
		$sql = "SELECT * FROM `" . DB_PREFIX . "emp_staff_detail` WHERE is_delete = '0' AND is_delete=0  AND (status = 1 OR (status=0 AND dolev >='".$date."'))  ORDER BY series ASC";
	
	
//	echo $sql;
	
	//	$sql = "SELECT * FROM `" . DB_PREFIX . "emp_staff_detail` WHERE  status ='1' AND  is_delete='0' AND staff_group_id = '" .(int)$staff_group_id. "' ORDER BY series ASC ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getemployee()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "emp_staff_detail` WHERE  status ='1' AND  is_delete='0'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function addattendance($data)
	{
		$attendance='';
		if(isset($data['attendance']))
			$attendance = json_encode ($data['attendance']);	
		$o=array();
		foreach($data['other'] as $othr)
		{
			if($othr!='')
				$o[]=$othr;
		}
		$other='';
		if(!empty($o))
			$other = json_encode($o);
		$sql = "INSERT INTO `" . DB_PREFIX . "employee_attendance` SET  group_id = '" .$data['staff_group_id']. "',attendance_date = '" .$data['attendance_date']. "',attendance = '" .$attendance. "',	other_detail = '" .$other. "',date_added = NOW(),date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', user_type_id='".$_SESSION['LOGIN_USER_TYPE']."'";
		$this->query($sql);
		$pouching_id = $this->getLastId();
		
	}
	public function getattendance($staff_group_id,$date)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "employee_attendance` WHERE group_id = '" .(int)$staff_group_id. "' AND attendance_date='".$date."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function attendance_types()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "attendance_types` WHERE is_delete=0 AND status =1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getAttData($att_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "employee_attendance` WHERE 	attendance_id = '" .(int)$att_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateattendance($att_id,$data)
	{
		$attendance='';
		if(isset($data['attendance']))
			$attendance = json_encode ($data['attendance']);	
		$o=array();
		foreach($data['other'] as $othr)
		{
			if($othr!='')
				$o[]=$othr;
		}
		$other='';
		if(!empty($o))
			$other = json_encode($o);
		$sql = "UPDATE `" . DB_PREFIX . "employee_attendance` SET  group_id = '" .$data['staff_group_id']. "',attendance_date = '" .$data['attendance_date']. "',attendance = '" .$attendance. "',	other_detail = '" .$other. "',date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', user_type_id='".$_SESSION['LOGIN_USER_TYPE']."' WHERE attendance_id = '".$att_id."'";
		$this->query($sql);
		
	}
	public function returnDates($startDate, $endDate,$format="Y-m-d")
	{
		$datesArray = array();
		
		$total_days = round(abs(strtotime($endDate) - strtotime($startDate)) / 86400, 0) + 1;
		
		for($day=0; $day<$total_days; $day++)
		{
			$datesArray[] = date($format, strtotime("{$startDate} + {$day} days"));
		}
		
		return $datesArray;
	}
	public function getAttendanceReport($data) 
	{
		
		$getGroup = $this->getGroup($data['group']);
		
	//
	
	
	//$sql = "SELECT * FROM `" . DB_PREFIX . "emp_staff_detail` WHERE is_delete = '0' AND staff_group_id= ".$data['group']." AND doj <= '".$data['f_date']."'  AND is_delete=0 AND status = 1 ORDER BY series ASC";
	$sql = "SELECT * FROM `" . DB_PREFIX . "emp_staff_detail` WHERE is_delete = '0' AND staff_group_id= ".$data['group']." AND doj <= '".$data['f_date']."'  AND is_delete=0  AND (status = 1 OR (status=0 AND dolev >='".$data['t_date']."'))  ORDER BY series ASC";
//	echo $sql;
		$employee = $this->query($sql);
	
	   

	
		 
		$datePeriod = $this->returnDates($data['f_date'], $data['t_date']);
		$time=strtotime($data['f_date']);
		$html ="";
		 $html .= "<div class='form-group'>
					 <div class='table-responsive'>";
					 $html .= "<table class='table table-striped b-t text-small' id='' border='1'>
								<thead>
									<tr>
										<th rowspan='3'>Sr.no </th>
										<th rowspan='3'>Name Of Employee </th>
										<th>Gender</th>
										<th colspan=".count($datePeriod)."><center>".$getGroup['staff_group_name']." Daily Attendance <b>".date('F',$time)."  ".date('Y',$time)."</b></center></th>
										<th rowspan='2'>No. Of Days Worked</th>
									</tr>
									<tr>
										<th>Day </th>
										";
											foreach($datePeriod as $day)
											{ 
												$timestamp = strtotime($day);
												$day = date('D', $timestamp);
												$bg_clr='';
												if($day=='Sun')
													$bg_clr =' style="background-color: #ddd"';
												$html .= "<th ".$bg_clr.">".$day."</th>";
												
											}
											
											
							   $html .= "
									</tr>
									<tr>
										<th>Date </th>";
										foreach($datePeriod as $date)
										{ 
											$timestamp = strtotime($date);
											$day = date('d', $timestamp);
											$html .= "<th>".$day."</th>";
											
										}
										
										
									$html .="
										<th></th>
									</tr>
								<thead>
								<tbody>";
									$i=1;$t=0;
									foreach($employee->rows as $emp)
									{ 
							          
									       
                									    $total_days=array();$h=0;
                										$html.="<tr>
                													<td>".$i."</td>
                													<td>".ucfirst($emp['fname'])." ".ucfirst($emp['mname'])." ".ucfirst($emp['lname'])."</td>
                													<td>".substr(strtoupper($emp['gen']),0,1)."</td>";
                													foreach($datePeriod as $att)
                													{  //printr($emp['doj'] );
                														$getattendance = $this->getattendance($data['group'],$att);
                														$attendance=array();
                														if($getattendance['attendance']!='')
                															$attendance = json_decode($getattendance['attendance']);
                														
                														$timestamp = strtotime($att);
                														$day = date('D', $timestamp);
                														if(in_array($emp['emp_staff_detail_id'],$attendance))
                														{
                															$s= 'P';
                															$total_days[] = $s;
                														}
                														else
                															$s= 'A';
                														
                														$other = json_decode($getattendance['other_detail']);
                														$other_type='';
                														
                														if($day != 'Sun')
                														{	
                															
                															if($s=='A')
                															{ 
                																if($other!='')
                																{  
                																	foreach($other as $o)
                																	{
                																		$othr = explode('=',$o);
                																		if($emp['emp_staff_detail_id']==$othr[0])
                																		{
                																			$getType = $this->query("SELECT type_name FROM attendance_types WHERE attendance_types_id = ".$othr[2]);
                																			$other_type =$getType->row['type_name'];
                																		}
                																	}
                																	$ph = $other_type;
                																	if($other_type=='PH' || $other_type=='')
                																		$ph = 'A';
                																	
                																	if($other_type=='P4')
                																		$h+=0.5;
                																	
                																        $html .= "<td>".$ph."</td>";
                																}
                																else
                																    $html .= "<td>".$s."</td>";
                																
                															}
                															else
                															    $html .= "<td>".$s."</td>";
                
                														}	
                														else
                														{
                															if($other!='')
                															{
                																foreach($other as $o)
                																{
                																	$othr = explode('=',$o);
                																	if($emp['emp_staff_detail_id']==$othr[0])
                																	{
                																		$getType = $this->query("SELECT type_name FROM attendance_types WHERE attendance_types_id = ".$othr[2]);
                																		$other_type =$getType->row['type_name'];
                																		
                																	}	
                																}
                																$html .= "<td style='background-color: #ddd'>".$other_type."</td>";
                															}
                															else
                																$html .= "<td style='background-color: #ddd'></td>";
                														}
                													}
                													$total = count($total_days)+$h;
                													$html .= "<td>".$total."</td>";
                													$t+= $total;
                										$html.="</tr>";
                										
                										$i++;
									//    }
									}
									$c = count($datePeriod)+4;
									$html .="<tr>
													<td></td>
													<td></td>
													<td></td>
													<td colspan='".count($datePeriod)."'></td>
													<td>".$t."</td>
											</tr>";

						$html .="</tbody>
								</table>
					</div>
					</div>";
		return $html;
	}
	public function staff_group_detail()
	{
		$sql = "SELECT e.*,g.staff_group_name FROM `" . DB_PREFIX . "employee_attendance` as e,staff_group_details as g  where g.staff_group_id=e.group_id ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getAnnualAttendanceReport($post)
	{
		 $emp = explode('==',$post['employee']);
		 
		 $sql = "SELECT * FROM `" . DB_PREFIX . "emp_staff_detail` WHERE 	emp_staff_detail_id='".$emp[0]."'";
		 $employee = $this->query($sql);
		 
		 $html ="";
		 $html .= "<div class='form-group'>
					 <div class='table-responsive'>
						<table class='table table-striped b-t text-small' id='' border='1'>
								<thead>
								    <tr><th colspan='35'>Employee Name : ".$employee->row['fname']." ".$employee->row['mname']." ".$employee->row['lname']."</th></tr>
									<tr>
										<th>Date</th>";
										  for($i=1;$i<=31;$i++)
										  {
												$html .= "<th rowspan='2'>".$i."</th>";
										  }
								$html .= "<th rowspan='2'>No. Of Days Worked</th>
										<th rowspan='2'>IN %</th>
									</tr>
									<tr>
										<th>Month</th>
									</tr>
								</thead>
								<tbody>";
										$start    = new DateTime($post['f_date']);
										$end      = new DateTime($post['t_date']);
										$interval = DateInterval::createFromDateString('1 month');
										$period   = new DatePeriod($start, $interval, $end);
										$t=0;
										foreach ($period as $dt) {
											
											$html .= "<tr>
														<td>".$dt->format("Y-M")."</td>";
													 //$d=cal_days_in_month(CAL_GREGORIAN,$dt->format("m"),$dt->format("Y"));
													 $total_days=array();$h=0;
													 
													 for($i=1;$i<=31;$i++)
													 { 
														$date = $dt->format("Y").'-'.$dt->format("m").'-'.$i;
														$getattendance = $this->getattendance($emp[1],$date);
														if($getattendance)
														{
															$attendance=array();
															if($getattendance['attendance']!='')
																$attendance = json_decode($getattendance['attendance']);
															
															$timestamp = strtotime($date);
															$day = date('D', $timestamp);
															if(in_array($emp[0],$attendance))
															{
																$s= 'P';
																$total_days[] = $s;
															}
															else
																$s= 'A';
															
															$other = json_decode($getattendance['other_detail']);
															$other_type='';
															
															if($day != 'Sun')
															{	
																
																if($s=='A')
																{ 
																	if($other!='')
																	{  
																		foreach($other as $o)
																		{
																			$othr = explode('=',$o);
																			if($emp[0]==$othr[0])
																			{
																				$getType = $this->query("SELECT type_name FROM attendance_types WHERE attendance_types_id = ".$othr[2]);
																				$other_type =$getType->row['type_name'];
																			}
																		}
																		$ph = $other_type;
																		if($other_type=='PH' || $other_type=='')
																			$ph = 'A';
																		
																		if($other_type=='P4')
																			$h+=0.5;
																		
																		
																		$html .= "<td>".$ph."</td>";
																	}
																	else	
																		$html .= "<td>".$s."</td>";
																}
																else
																	$html .= "<td>".$s."</td>";
															}	
															else
															{
																if($other!='')
																{
																	foreach($other as $o)
																	{
																		$othr = explode('=',$o);
																		if($emp[0]==$othr[0])
																		{
																			$getType = $this->query("SELECT type_name FROM attendance_types WHERE attendance_types_id = ".$othr[2]);
																			$other_type =$getType->row['type_name'];
																			
																		}	
																	}
																	$html .= "<td style='background-color: #ddd'>".$other_type."</td>";
																}
																else
																	$html .= "<td style='background-color: #ddd'></td>";
															}
																
													 }
													 else
														$html .= "<td style='background-color: #ddd'></td>";
														
														
													 }
													    $total = count($total_days)+$h;
														$html .= "<td>".$total."</td>";
														
														$html .= "<td>".number_format((($total*100)/26),2)." %</td>";
														$t+= $total;
											$html.="  </tr>";
										}
										//$c = count($datePeriod)+4;
										$html .="<tr>
													<td colspan='32'></td>
													<td>".$t."</td>
													<td></td>
											</tr>";
											
									
						$html .= "";
		
		
		$html .="    			</tbody>
						</table>
					</div>
				</div>";
		return $html;
	}
}

?>