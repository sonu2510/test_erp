<?php
include("mode_setting.php");

//Start : bradcums
$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'], '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums
$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
//Start : edit
$edit = '';
$click = '';
if(isset($_GET['invoice_no']) && !empty($_GET['invoice_no'])){
	if(!$obj_general->hasPermission('view',$menuId)){
		$display_status = false;
	}else{
		$invoice_no = base64_decode($_GET['invoice_no']);
		$click = 1;
	}
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div> 
      <div class="col-sm-8" style="width:75%">
            <section class="panel">  
                <header class="panel-heading bg-white">
                     <span>Invoice Detail</span>
                    <center><a class="label bg-info " onclick="downloadPdf('<?php echo $_GET['invoice_no'];?>',<?php echo $_GET['inv_status'];?>,<?php echo $_GET['status'];?>);" href="javascript:void(0);"><i class="fa fa-print" ></i> If you not able to download so plz click on this button</a></center>
                 <span class="text-muted m-l-small pull-right">
                 <a class="label bg-info " onclick="test();" href="javascript:void(0);"><i class="fa fa-print" ></i> Print</a>
                <!-- <a class="label bg-primary sendmailcls" href="javascript:void(0);"><i class="fa fa-envelope"></i> Send Mail</a>-->
                <a class="label bg-info pdfcls" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>
                <!--sonu 3/12/2016-->
				 <a class="label bg-success" href="javascript:void(0);" onclick="excelfile('<?php echo rawurlencode($_GET['invoice_no']);?>','<?php echo $_GET['status'];?>')"><i class="fa fa-print"></i> Excel</a>
                <a class="label bg-success" href="javascript:void(0);" onclick="wordlink('<?php echo rawurlencode($_GET['invoice_no']);?>')"><i class="fa fa-print"></i> Doc</a>
                 </span>
              </header>
              <div class="panel-body">
              	<!--label class="label bg-white m-l-mini">&nbsp;</label-->
                	<span class="text-muted m-l-small pull-right">
                    	 <b></b>
                    </span>
                    <div >
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                     
						<div >	 
                              <div class="form-group">
                              <?php if($_GET['status']==2)
							  {?>
                              		<h1>OUT</h1>
                              <?php }else{?>
                             		 <h1>IN</h1>
                              <?php }?>
							  </div>
                              </div>
                              
            <div class="panel-body" id="print_div ">
                <div class="">
                     <div class="form-group" id="in_out" style="width:900px; font-size:13px"> 
                    <style>@font-face {font-family: IDAutomationHC39M;src: url("<?php echo HTTP_SERVER.'css/fonts/IDAutomationHC39M.ttf';?>");font-size:10px;}
                .barcode{ font-family: IDAutomationHC39M;src:("<?php echo HTTP_SERVER.'css/fonts/IDAutomationHC39M.ttf';?>");font-size:8px;}
                .table,#sub_table {font-size:13px; }</style>
                 <?php //$html=$obj_invoice->viewInout($invoice_no,$_GET['status']);

                        $parent_id=0;
                           
                              $details=$obj_invoice->colordetailsTest($invoice_no,$parent_id);
                              $tot_box=count($details);
                         
                            $invoice=$obj_invoice->getInvoiceNetData($invoice_no);
                            $setHtml='';$description='';$valve='';$zipper_name='';
                            if($_GET['status']==2)  {
                                $i=3;$r=4;
                            
                            }else{
                                $i=3;$r=4;
                            }$gross_weight=0;
                            //printr($details);
                            if($details!='')
                            {
                                foreach($details as $val1)
                                { 
                                    $val=$obj_invoice->Product_detailTest($invoice_no,$val1['invoice_product_id'],$val1['invoice_color_id']);
                                  
                                  $val['box_no']=$val1['box_no'];
                                  $val['genqty']=$val1['genqty'];
                                  $val['net_weight']=$val1['net_weight'];
                                  $c_name='';$size='';$qty='';
                                  $zipper=$obj_invoice->getZipper(decode($val['zipper']));
                                  $zipper_name=$zipper['zipper_name'];
                                  $valve=$val['valve'];
                                  
                                  $p_name=$obj_invoice->getActiveProductName($val['product_id']);
                                  
                                  $childBox=$obj_invoice->colordetailsTest($invoice_no,$val1['in_gen_invoice_id']);
                                  $product_decreption = $obj_invoice->getProductCode($val['invoice_product_id']);
                                  
                                //printr($product_decreption);
                                  $net_w = $val['net_weight'];
                                  if(isset($childBox) && !empty($childBox))
                                  {
                                    foreach($childBox as $key=>$ch)
                                    {
                                        
                                      $net_w = $net_w+$ch['net_weight'];
                                    }
                                  }
                                //  printr($product_decreption);
                                  $gross_weight=$net_w+$val1['box_weight'];
                                  
                                       
                                // change by sonu   add change for color text 30-10-2017
                                              
                                  if($val['color_text']!=''){
                                      $c_name=$val['color_text'].'  '.$val['color'];
                                  }else{
                                      $c_name=$val['color'];
                                  }
                                  if($val['filling_details']!=''){
                                      $filling_details=$val['filling_details'];
                                  }else{
                                      $filling_details='';
                                  }
                                //  echo $c_name;
                                  //end
                                  if( $val['product_id']!='47' ||$val['product_id']!='48'||$val['product_id']!='72'){
                                      $val['size']= filter_var($val['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                                  }
                                  if($val['pouch_color_id']=='-1')
                                  {
                                    $size_cd =$size=$val['dimension'];
                                    
                                  }
                                  else  
                                  {
                                       if($invoice['country_destination'] != '253'){
                                            $size=$val['size'].' '.$val['measurement'];
                                              $size_cd = $val['size'].'='.$val['measurement']; 
                                     }else{
                                            $size_us=$val['size'].' '.$val['measurement'];
                                             $size_cd = $val['size'].'='.$val['measurement']; 
                                             $s_us=$obj_invoice->getsizeForUS($size_us);
                                             if($s_us!=''){
                                                  $size=$s_us;
                                             }else{
                                                  $size=$size_us;
                                             }
                                             
                                     }
                                  
                                  } //printr($size);
                                  $size_new='';
                                  if($size=='250. gm' || $size=='500. gm')
                                      $size_new=' [NEW SIZE] ';
                                    
                                  
                                  $qty=$val['genqty'];
                                  
                                  $description= $filling_details.''.$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
                                  
                                  //if($product_decreption==''  )
                                  //{
                                    
                                    //$description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
                                  //}else
                                  //{
                                      if( $val['product_id']=='13' ||$val['product_id']=='16'|| $val['product_id']=='31' || $val['product_id']=='30'|| $val['product_id']=='37'|| $val['product_id']=='38')
                                      {
                                          if($val['color_text']=='')
                                              $description=$product_decreption['description'].' '. $filling_details;
                                          else
                                              $description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') '. $filling_details;
                                      }
                                      else
                                      {
                                          $description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') '.$size_new ;
                                      }
                                    
                                //  }
                                  
                                  if($i%3==0)
                                  { $a=$i-$r;
                                    if($_GET['status']==2)    
                                      $c=$r;
                                    else
                                      $c=$r;
                                    $style='';
                                    if($i%3=='2' && $i!=2)
                                        $style="page-break-before:always;";
                                        
                                    //  echo '<style>.innerbox{  width:250px;     max-width:250px;  display: inline-block;  } </style>';
                                    


                                      echo '<div id="'.$i.'='.($i%3).'" style="'.$style.'">
                                              <table class="table"  border="0" width="100%" >
                                              
                                                  <tr>';                                          
                                   }     
                                 
                                                  //width:50%
                                                echo '<td style="width:33%;border:none; border-top:none;">
                                                        <table  style="" id="innerbox" class="innerbox" style="max-width:550px; height:300px;display: inline-block;" >
                                                          <tr>
                                                            <td  style="padding:0px;border:0px">BOX NO.&nbsp;:&nbsp;</td>
                                                            <td  style="padding:0px;border:0px;text-align:left;"><b>'.$val['box_no'].'</b></td>
                                                          </tr>
                                                          <tr>
                                                            <td  style="padding:0px;border:0px">QTY NOS.&nbsp;:&nbsp;</td>
                                                            <td  style="padding:0px;border:0px">'.$val['genqty'].' PCS</td>
                                                          </tr>
                                                          <tr>
                                                            <td  style="padding:0px;border:0px" valign="top">DESCRIPTION&nbsp;:&nbsp;</td>
                                                            
                                                            
                                                          </tr>
                                                          <tr>
                                                          <td  style="padding:0px;border:0px" colspan="2" class="test"><b>'.$description.'</b></td>
                                                          </tr>
                                                          <tr>
                                                            <td  style="padding:0px;border:0px">SIZE&nbsp;:&nbsp;</td>
                                                            <td  style="padding:0px;border:0px">'.$size.'</td>
                                                          </tr>
                                                        <tr>';
                                                                      if($invoice['country_destination'] == '170' || $invoice['country_destination'] == '253')
                                                                    {
                                                                      if($invoice['country_destination'] == '170')
                                                                      {
                                                                        $label ='SPECIAL CODE';
                                                                      
                                                                      } 
                                                                      else
                                                                      {
                                                                        $label = 'ITEM NO.';
                                                                      }
                                                                      echo '<td  style="padding:0px;border:0px">'.$label.'&nbsp;:&nbsp;</td>';
                                                                      echo '<td  style="padding:0px;border:0px">'.$val['item_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
                                                                    }
                                                                    //&& $invoice['country_destination']!='42' 04-01-2017
                                                                    
                                                                    
                                                                                                        
                                                                    else if($invoice['country_destination']!='14' && $invoice['country_destination']!='214'  && $invoice['country_destination']!='155' && $invoice['country_destination']!='42' ){
                                                                        
                                                                       if($val['ref_no']!=0){
                                                                       
                                                                                                    $val['ref_no']=$val['ref_no'];
                                                                                                 }else{
                                                                                                     $val['ref_no']= $val['buyers_o_no'];
                                                                                                 }
                                                                        
                                                                        echo '<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
                                                                      echo '<td  style="padding:0px;border:0px">'.$val['ref_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
                                                                    }
                                                                    else 
                                                                    {
                                                                      echo '<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
                                                                      echo '<td  style="padding:0px;border:0px">'.$val['buyers_o_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
                                                                    } 
                                                            $setHtml.='</tr>';
                                                          
                                                if($_GET['status']==2)    
                                                {
                                                echo '<tr>
                                                        <td  style="padding:0px;border:0px">GROSS WT&nbsp;:&nbsp;</td>
                                                        <td  style="padding:0px;border:0px">'.number_format($gross_weight,3).' Kg</td>
                                                      </tr>
                                                      <tr>
                                                        <td  style="padding:0px;border:0px">Net WT&nbsp;:&nbsp;</td>
                                                        <td  style="padding:0px;border:0px">'.$net_w.' Kg</td>
                                                      </tr>
                                                    ';
                                                      /*  if($invoice['country_destination'] == '170' || $invoice['country_destination'] == '253')
                                                        {
                                                          if($invoice['country_destination'] == '170')
                                                          {
                                                            $label ='SPECIAL CODE';
                                                          
                                                          } 
                                                          else
                                                          {
                                                            $label = 'ITEM NO.';
                                                          }
                                                          echo '<td  style="padding:0px;border:0px">'.$label.'&nbsp;:&nbsp;</td>';
                                                          echo '<td  style="padding:0px;border:0px">'.$val['item_no'].'</td>';
                                                        }
                                                        //&& $invoice['country_destination']!='42' 04-01-2017
                                                        
                                                        
                                                                                            
                                                        else if($invoice['country_destination']!='14' && $invoice['country_destination']!='214'  && $invoice['country_destination']!='155' && $invoice['country_destination']!='42' ){
                                                            
                                                           if($val['ref_no']!=0){
                                                           
                                                                                        $val['ref_no']=$val['ref_no'];
                                                                                     }else{ 
                                                                                         $val['ref_no']= $val['buyers_o_no'];
                                                                                     }
                                                            
                                                            echo '<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
                                                          echo '<td  style="padding:0px;border:0px">'.$val['ref_no'].'</td>';
                                                        }
                                                        else 
                                                        {
                                                          echo '<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
                                                          echo '<td  style="padding:0px;border:0px">'.$val['buyers_o_no'].'</td>';
                                                        } 
                                                          
                                                      echo '</tr>';*/
                                                      //told by pinak 9-3-2017
                                                    if(ucwords(decode($invoice['transportation']))=='Air')
                                                    {
                                                    echo '<tr>
                                                            <td  style="padding:0px;border:0px"><b>Marks & NO .&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                            <td  style="padding:0px;border:0px">'.$val['box_no'].'/'.$tot_box.'Boxes </b></td>
                                                          </tr>'; 
                                                          }     
                                                }
                                                    echo '<tr>
                                                            <td  style="padding:0px;border:0px" colspan="2"><div class="barcode_lable" style=" padding: 0px;"><b>INSPECTED BY</b>&nbsp;&nbsp;: </div></td>
                                                          </tr>
                                                          <tr>
                                                          
                                                            <td  style="padding:0px;border:0px" colspan="2"><div><span style="line-height:50px;"><img style="width:159px;" class="barcode" alt="'.trim($val1['box_unique_number']).'" src="'.HTTP_SERVER.'admin/barcode/barcode.php?text='.trim($val1['box_unique_number']).'&codetype=Code128&orientation=horizontal&size=40&print=true"/></span>';
                                                            
                                                          
                                                                                  if($invoice_no=='5'){
                                                                                            echo '<br><br><span><img src="https://chart.googleapis.com/chart?chs=156x81&cht=qr&chl='.$val1['box_unique_number'].'*'.$p_name['abbrevation'].'*'.$val1['genqty'].'*'.$size_cd.'*'.$val1['box_no'].'*'.$invoice['invoice_no'].'*'.$product_decreption['product_code_id'].'"></span>';
                                                                                           }
                                                      echo '<div></td>
                                                          </tr>
                                              
                                            </table>
                                          </td>';
                                        
                                        
                                  $description='';
                                  $i++; 
                                 
                                  if($i%3==0)
                                  {
                                    //  echo $i.'if';
                                    echo '</tr></table></div>'; 
                                    
                                  } 
                                  
                                  
                                  if(isset($childBox) && !empty($childBox))
                                  {
                                    foreach($childBox as $key=>$ch1)
                                    { 
                                          
                                          $ch=$obj_invoice->Product_detailTest($invoice_no,$ch1['invoice_product_id'],$ch1['invoice_color_id']);
                                      // product_id
                                        $product_decreption = $obj_invoice->getProductCode($ch['invoice_product_id']);
                                      
                                        
                                            $child_zipper=$obj_invoice->getZipper(decode($ch['zipper']));
                                                  if($i%3==0)
                                              { $a=$i-$r;
                                                if($_GET['status']==2)    
                                                  $c=$r;
                                                else
                                                  $c=$r;
                                                $style='';
                                                if($i%3=='2' && $i!=2)
                                                    $style="page-break-before:always;";
                                                  echo '<div id="'.$i.'='.($i%3).'" style="'.$style.'" >
                                                          <table class="table"  border="0" width="100%">
                                                          
                                                              <tr>';                                          
                                               }    
                                                  
                                      //} width:50%          
                                      
                                      // change by sonu   add change for color text 30-10-2017
                                      
                                                                      if($ch['color_text']!=''){ 
                                                                             $c_name_ch=$ch['color_text'] .'  '.$ch['color'];
                                                                      }else{
                                                                          $c_name_ch=$ch['color'];
                                                                      }
                                                                      if($ch['filling_details']!=''){ 
                                                                             $filling_details_ch=$ch['filling_details'];
                                                                      }else{
                                                                           $filling_details_ch='';
                                                                      }
                                                                      
                                                                      if( $ch['product_id']!='47' ||$ch['product_id']!='48'||$ch['product_id']!='72')
                                                                                        $ch['size']= filter_var($ch['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                                                                      //end             
                                                                  if($ch['pouch_color_id']=='-1')
                                                                      {
                                                                        $size_ch=$ch['dimension'];
                                                                        $size_code = $ch['dimension'];
                                                                        
                                                                      }
                                                                      else  
                                                                      {
                                                                           if($invoice['country_destination']!=253){
                                                            $size_ch=$ch['size'].' '.$ch['measurement'];
                                                         }else{
                                                              $size_us_c=$ch['size'].' '.$ch['measurement'];
                                                               $s_us_c=$obj_invoice->getsizeForUS($size_us_c);
                                                             if($s_us!=''){
                                                                                      $size_ch=$s_us_c;
                                                                                 }else{
                                                                                      $size_ch=$size_us_c;
                                                                                 }
                                                         }
                                                                          
                                                                      
                                                                        $size_code = $ch['size'].'='.$ch['measurement'];
                                                                      }
                                                                      
                                                                        $size_new_ch='';
                                                                          if($size_ch=='250. gm' || $size_ch=='500. gm')
                                                                              $size_new_ch=' [NEW SIZE] ';
                                                                      
                                                                      
                                                                        if( $ch['product_id']=='13' ||$ch['product_id']=='16'|| $ch['product_id']=='31' || $ch['product_id']=='30'|| $ch['product_id']=='37'|| $ch['product_id']=='38')
                                                                          {
                                                                          $description_ch=$product_decreption['description'].' '.$filling_details_ch;
                                                                          
                                                                          
                                                                          }
                                                                          else{
                                                                              $description_ch=$c_name_ch. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$ch['product_name'] ),0,3)).' '.$child_zipper['zipper_name'].' '.$ch['valve'].')'.$size_new_ch ;
                                                                          }
                                                      echo '<td style="width:33%;border:none; border-top:none;">
                                                      <table  style="" id="innerbox" class="innerbox" style=" max-width:550px; height:300px;display: inline-block;" >
                                                        <tr> 
                                                          <td  style="padding:0px;border:0px">BOX NO.&nbsp;:&nbsp;</td>
                                                          <td  style="padding:0px;border:0px"><b>'.$val['box_no'].'</b></td>
                                                        </tr>
                                                        <tr> 
                                                          <td  style="padding:0px;border:0px">QTY NOS.&nbsp;:&nbsp;</td>
                                                          <td  style="padding:0px;border:0px">'.$ch1['genqty'].' PCS</td>
                                                        </tr>
                                                        <tr>
                                                          <td  style="padding:0px;border:0px" valign="top">DESCRIPTION&nbsp;:&nbsp;</td>
                                                          
                                                        </tr> 
                                                        <tr>
                                                            <td  style="padding:0px;border:0px" colspan="2" class="test" ><b>'.$description_ch.'</b></td>
                                                        </tr>
                                                        <tr>
                                                          <td  style="padding:0px;border:0px">SIZE&nbsp;:&nbsp;</td>
                                                          <td  style="padding:0px;border:0px">';
                                                          
                                                          
                                                          //.$ch['size'].' '.$ch['measurement'].
                                                          
                                                          echo  $size_ch.'</td>
                                                        </tr>
                                                        ';
                                                        
                                                    echo '<tr>';
                                                      if($invoice['country_destination'] == '170' || $invoice['country_destination'] == '253')
                                                      {
                                                        if($invoice['country_destination'] == '170')
                                                        {
                                                          $label ='SPECIAL CODE';
                                                        } 
                                                        else
                                                        {
                                                          $label = 'ITEM NO.';
                                                        }
                                                        echo '<td  style="padding:0px;border:0px">'.$label.'&nbsp;:&nbsp;</td>';
                                                        echo '<td  style="padding:0px;border:0px">'.$ch['item_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
                                                      }
                                                      //&& $invoice['country_destination']!='42'  04-01-2018
                                                      
                                                      
                                                      
                                                    
                                                      else if($invoice['country_destination']!='14' && $invoice['country_destination']!='214'  && $invoice['country_destination']!='155' && $invoice['country_destination']!='42' ){
                                                          
                                                             if($ch['ref_no']!=0){
                                                                                       $ch['ref_no']=$ch['ref_no']; 
                                                                                    }else{
                                                                                       $ch['ref_no']= $ch['buyers_o_no'];
                                                                                    }
                                                                             echo '<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
                                                                            echo '<td  style="padding:0px;border:0px">'.$ch['ref_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
                                                                  }
                                                      else
                                                      {
                                                        echo '<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
                                                        echo '<td  style="padding:0px;border:0px">'.$ch['buyers_o_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
                                                      }
                                                
                                              
                                              echo '</tr>'; 
                                              if($_GET['status']==2)    
                                              {
                                              
                                                    echo '<tr>
                                                            <td  style="padding:0px;border:0px">GROSS WT&nbsp;:&nbsp;</td>
                                                            <td  style="padding:0px;border:0px">'.number_format($gross_weight,3).' Kg</td>
                                                          </tr>
                                                          <tr>
                                                            <td  style="padding:0px;border:0px">Net WT&nbsp;:&nbsp;</td>
                                                            <td  style="padding:0px;border:0px">'.$net_w.' Kg</td>
                                                          </tr>';
                                                  echo '<tr>
                                                      <td  style="padding:0px;border:0px"><b>Marks & NO.&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                      <td  style="padding:0px;border:0px">'.$val['box_no'].'/'.$tot_box.'Boxes </b></td>
                                                    </tr>';        
                                              }
                                          
                                          echo '<tr>
                                                  <td  style="padding:0px;border:0px" colspan="2"><div class="barcode_lable" style=" padding: 0px;"><b>INSPECTED BY</b>&nbsp;&nbsp;: </div></td>
                                                </tr>
                                                 <tr>
                                                  <td  style="padding:0px;border:0px" colspan="2"><div><span style="line-height:50px;"><img style="width:159px;" class="barcode" alt="'.trim($val1['box_unique_number']).'" src="'.HTTP_SERVER.'admin/barcode/barcode.php?text='.trim($val1['box_unique_number']).'&codetype=Code128&orientation=horizontal&size=40&print=true"/></span>';
                                                  
                                                  
                                                              if($invoice_no=='5'){
                                                                        echo '<br><br><span><img src="https://chart.googleapis.com/chart?chs=156x81&cht=qr&chl='.$val1['box_unique_number'].'*'.$p_name['abbrevation'].'*'.$val1['genqty'].'*'.$size_code.'*'.$val1['box_no'].'*'.$invoice['invoice_no'].'*'.$product_decreption['product_code_id'].'"></span>';
                                                                       }
                                            echo '<div></td>
                                                </tr>
                                              
                                              </table></td>
                                              ';
                                  
                                    $i++; 

                                          if($i%3==0)
                                          {
                                            //  echo $i.'if';
                                            echo '</tr></table></div>'; 
                                            
                                          }                               
                                      }
                                  
                                  
                                  }
                                   
                                }
                                //printr($setHtml);die;
                            }
                            echo '</tr></table></div>';



                			// echo $html;
                		?>
                     </div>
                    </div>
           </div>  
            <div class="form-group">
         <div class="col-lg-9 col-lg-offset-3"> 
        <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=view&status=1&invoice_no='.$_GET['invoice_no'].'&inv_status='.$_GET['inv_status'], '',1);?>">Cancel</a>
       </div>
       </div>   
           </form>
           </div>         
                  </div>
                </section>    
      </div>
    </div>
  </section>
</section>
<div id="er"></div>
<style>
.col-lg-3 {
width: 15%;
}
#client {
    border-left: 6px solid #0087c3;
    float: left;
    padding-left: 6px;
}
h1 {
   /* background: url("dimension.png") repeat scroll 0 0 rgba(0, 0, 0, 0);*/
	background:#333;
    border-bottom: 1px solid #5d6975;
    border-top: 1px solid #5d6975;
    color: #FFF;
    font-size: 2.4em;
    font-weight: normal;
    line-height: 1.4em;
    margin: 0 0 20px;
    text-align: center;
}
#in_out .in_out {
	float:left;width:100px;
}
.sign_td {
	height:150px;
}
.test {
	height:90px;
}
</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/FileSaver.js"></script> 
<script src="<?php echo HTTP_SERVER;?>js/jquery.wordexport.js"></script> 
<script>
 jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#sform").validationEngine();	

		$(".pdfcls").click(function(){			
			
				$(".note-error").remove();
			var url = '<?php echo HTTP_SERVER.'pdf/inoutpdf_test.php?mod='.encode('inout_test').'&token='.rawurlencode($_GET['invoice_no']).'&status='.rawurlencode($_GET['status']).'&ext='.md5('php');?>';
			window.open(url, '_blank');
			return false;
		});
	});
	var he=0;
	var page=1
	jQuery(window).load(function () {
		  jQuery("#print_div").children().each(function(n, i) {
    if($(this).height()>23)
	  he = he+$(this).height(); 
	   if(he>800)
	   {
		   page++;
		   var id =this.id;
		   	$("#"+id).before('<br style="page-break-before:always" >Page - '+page);
		he=0;
	   }
	  });
});
function te()
{
	 $("#in_out").wordExport();
}

function test() {

	 var html="<html>";
	html+='<head>';
	
 //html+="<style>.col-lg-3 {width: 15%;}#client {    border-left: 6px solid #0087c3;    float: left;    padding-left: 6px;}h1 {  background:#333;    border-bottom: 1px solid #5d6975;    border-top: 1px solid #5d6975;    color: #FFF;    font-size: 2.4em;    font-weight: normal;    line-height: 1.4em;    margin: 0 0 20px;    text-align: center;}article, article address, table.meta, table.inventory { margin: 0 0 3em; }table.meta, table.balance { float: right; width: 50%; }table.meta:after, table.balance:after { clear: both; display: table; }table.meta th { width: 40%; }table.meta td { width: 60%; }table { font-size: 65%; table-layout: fixed; width: 100%; }table { border-collapse: separate; border-spacing: 0px; }th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }th, td { border-radius: 0; border-style: solid; }th { background: #EEE; border-color: #BBB; }td { border-color: #DDD; }</style></html>";
  
 html+="<style> table-responsive{font-size: 10%;}.m-t-large {margin-top: 20px;}.line-dashed {border-style: dashed;background: transparent;}.line {height: 2px;margin: 10px 0;font-size: 0;overflow: hidden;background-color: #fff;border-width: 0;border-top: 1px solid #e0e4e8;} .detail_table {font-size: 10%; }table.detail_table { font-size: 10%; table-layout: fixed; width: 100%; font-size:  9px;}table.detail_table { border-collapse: separate; border-spacing: 0px;font-size:  9px; } table.detail_table th, table.detail_table td { border-width: 1px; padding: 0; position: relative; text-align: left;font-size:  9px; } table.detail_table th, table.detail_table td { border-radius: 0; border-style: solid;font-size:  9px; }table.detail_table th { background: #EEE; border-color: #BBB;font-size:  9px; } table.detail_table td { border-color: #DDD; font-size:  9px;}.no_border { border-bottom: 0 none; border-radius: 0; border-top: 0 none !important; }</style></html>";
	



    html+= $('#in_out').html();

    html+="</html>";	//alert(html);

    var printWin = window.open('','','');

    printWin.document.write(html);

    printWin.document.close();

    printWin.focus();

    printWin.print();

    printWin.close();

}
function wordlink(id){
		var url = '<?php echo HTTP_SERVER.'word/invoice_inout_test.php?mod='.encode('invoice').'&status='.$_GET['status'].'&ext='.md5('php');?>&token='+id;
		window.open(url, '_blank');
	return false;
}

<!---sonu 3/12/2016  -->
function excelfile(id,status){
	//debugger;
		var url = '<?php echo HTTP_SERVER.'word/invoice_inout_excel_test.php?mod='.encode('invoice').'&status='.$_GET['status'].'&ext='.md5('php');?>&token='+id;
		window.open(url, '_blank');
	return false;
}

function downloadPdf(invoice_no,inv_status,status)
{
	window.location  ='<?php echo HTTP_SERVER;?>admin/index.php?route=invoice_test&mod=downPdf&invoice_id='+invoice_no+'&status='+status+'&inv_status='+inv_status;
	//console.log(url);
//	window.open(url, '');
}
</script>	
<!-- Close : validation script -->