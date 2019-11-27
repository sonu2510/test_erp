<?php
//==>sonu
class production_operator_report extends dbclass{ 
	public function getOperator($user_type) 
	{
	    
		$sql="SELECT *,CONCAT(first_name ,' ', last_name) as operator_name FROM  user_type_production as utp,employee as e WHERE  e.is_delete=0 AND e.status = 1 ";
		 if($user_type!=0)
		    $sql.=" AND e.user_type='".$user_type."' AND utp.user_type_id='".$user_type."'";
		 else
		      $sql.="AND e.user_type=utp.user_type_id AND e.user_type !='1' AND e.user_type !='20'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}	public function getUsertype() 
	{
		$sql="SELECT * FROM  user_type_production as utp WHERE  user_type_id !='1' AND user_type_id !='20'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function get_all_operator_reports($post)
	{
	   //printr($post);die;
	   $str='';
		if ($post['f_date'] != '') {
            $f_date = $post['f_date'];
            $con = "AND p.job_date >= '" . $f_date . "' ";
        }
        if ($post['t_date'] != '') {
            $to_date = $post['t_date'];
            $con .= "AND  p.job_date <='" . $to_date . "'";
        }
	    
	    $sql="SELECT * ,CONCAT(e.first_name ,' ', e.last_name) as operator_name FROM `printing_operator_details` as po,printing_job as p,employee as e   WHERE p.roll_code!='' AND  p.job_id=po.printing_id AND po.operator_id=e.employee_id $con GROUP BY po.job_id";
	  
	 
	    $data=$this->query($sql);
	    //   printr($data);
	       $arr=array();
	   	if($data->num_rows){
		        foreach($data->rows as $printing_data){
		            
		                 
		                $lami_wast=$this->getLaminationWastage($printing_data['job_name_id']);
		                $slit_wast=$this->getSlittingWastage($printing_data['job_name_id']);
		                $pouch_wast=$this->getPouchingWastage($printing_data['job_name_id']);
		                
		                
		               // printr($printing_data);
		               // printr($lami_wast);
		              //  printr($slit_wast);
		             //   printr($pouch_wast);
		               $printing_data['total_wastage']=$printing_data['total_wastage']+$lami_wast['printing_wastage']+$slit_wast['printing_wastage']+$pouch_wast['printing_wastage'];
		                 $arr[$printing_data['operator_name']][]=array(
				                                    'job_id'=>$printing_data['job_id'],
				                                    'job_name_text'=>$printing_data['job_name_text'],
				                                    'roll_code'=>$printing_data['roll_code'],
				                                    'operator_id'=>$printing_data['operator_id'],
				                                    'total_wastage'=>$printing_data['total_wastage']
				                                   
				            );
		                
		             
		        }
		      //  printr($arr);
		        
		        $html='';
		        $html.='<style>
                    table, th, td {
                        border: 1px solid black;
                    }
                    </style>';		
		    	$html='	<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
    			    	<div class="panel-body font_medium" id="print_div" style="font-size: 20px; page-break-before: always;" >';
    			   $html .='<div style="text-align:center; font-size: 18px;"><b>PRINTING OPERATOR REPORT </b></div>';
    			  $html .='<div style="text-align:center; font-size: 18px;"><b><span><h4>Date From: <b>' . dateFormat(4, $f_date) . '</b> To: <b>' . dateFormat(4, $to_date) . '</b></h4><br><b>'.$user_name.'</b></span></b>';
    			  $html.='<div class="row">';
			   
			      $class="col-lg-4";
				 foreach($arr as $key=>$arr1){
				     
    			  $html.='<div class='.$class.'> <div class="table-responsive" style=" width: 100%;float: left;  font-size: 18px;">';
        				    $html.='<table class="table table-striped b-t text-small" style=" width: 100%; border:1; font-size: 18px;" >
                					<thead>
                					<tr><th colspan="4"><center><b> '.$key.'</b></center></th></tr>
                				        	<tr> 
                				        	   
                        						<th>Job No</th>
                        						<th>Roll Code</th>
                        						<th>Total Wastage(%)</th>
                        					';
                        						
                    				    $html.='</tr>';
                					
                        			$html.='</thead>
                					<tbody>';
                		 foreach($arr1 as $data){
                		     
                		     
                		    // printr($data);
                		     	$html.='<tr>
                		     	
                				        	   
                        						<td>'.$data['job_name_text'].'</td>
                        						<td>'.$data['roll_code'].'</td>
                        						<td>'.$data['total_wastage'].'</td>
                        					';
                        						
                    				    $html.='</tr>';
                		 }
                		 $html.='</tbody></table></div></div>';
                					
				 }
				 $html.='</div></div></form>';
		}
	    
	
	
	
		return $html;
	}
	

	public function getLaminationWastage($job_id){
	     $lamination_wast="SELECT ll.*,ll.job_id,ll.operator_shift,l.roll_code FROM `lamination_layer` as ll,lamination as l WHERE ll.lamination_id=l.lamination_id AND l.status='1' AND l.is_delete=0 AND  ll.job_id='".$job_id."' GROUP BY ll.job_id";
		  $lami_wast=$this->query($lamination_wast);
		  //	printr($lami_wast);
		  	
		if($lami_wast->num_rows){
			return $lami_wast->row;
		}else{
			return false; 
		}
	    
	}
	public function getSlittingWastage($job_id){
	     $slitting_wast="SELECT * FROM `slitting` WHERE  is_delete='0' AND job_id ='".$job_id."' GROUP BY job_id";
		  $slit_wast=$this->query($slitting_wast);
		 // echo $slitting_wast;die;
		 // 	printr($slitting_wast);
		  	
		if($slit_wast->num_rows){
			return $slit_wast->row;
		}else{
			return false;
		}
	    
	}	
	public function getPouchingWastage($job_id){
	     $pouching_wast="SELECT * FROM `pouching` WHERE is_delete='0' AND  job_id='".$job_id."' GROUP BY job_id";
		  $pouch_wast=$this->query($pouching_wast);
		 // echo $slitting_wast;die;
		 // 	printr($slitting_wast);
		  	
		if($pouch_wast->num_rows){
			return $pouch_wast->row;
		}else{
			return false;
		}
	    
	}
	
	
}
?>