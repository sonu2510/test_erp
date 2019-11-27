<?php
//rohit
include("mode_setting.php");

//Start : bradcums
$bradcums = array();
$bradcums[] = array(
    'text'  => 'Dashboard',
    'href'  => $obj_general->link('dashboard', '', '',1),
    'icon'  => 'fa-home',
    'class' => '',
);
$bradcums[] = array(
    'text'  => $display_name.' List',
    'href'  =>  $obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'], '',1),
    'icon'  => 'fa-list',
    'class' => '',
);
$bradcums[] = array(
    'text'  => $display_name.' Details',
    'href'  => '',
    'icon'  => 'fa-edit',
    'class' => 'active',
);//$menuId='220';
$click = '';
if(isset($_GET['invoice_no']) && !empty($_GET['invoice_no'])){
    if(!$obj_general->hasPermission('view',$menuId)){
        $display_status = false;
    }else{
        $invoice_no = base64_decode($_GET['invoice_no']);
        $invoice_no_e = base64_decode('MTc0Ng==');
        $click = 1;
    }
}else{
    if(!$obj_general->hasPermission('add',$menuId)){
        $display_status = false;
    }
}

 $limit = '20';
if(isset($_GET['limit'])){
    $limit = $_GET['limit'];    
}
if(isset($_GET['sort'])){
    $sort_name = $_GET['sort'];
}else{
    $sort_name='ig.box_no';
}

if(isset($_GET['order'])){
    $sort_order = $_GET['order']; 
}else{
    $sort_order = 'ASC';
}
?>

<section id="content">
    <section class="main padder">
        <div class="clearfix">
            <h4><i class="fa fa-list"></i> <?php echo $display_name;?></h4>
        </div>
        <div class="row">
            <div class="col-lg-12">
            <?php include("common/breadcrumb.php");?>
            </div>
            
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading bg-white">
                        <span><?php echo $display_name;?> Listing</span>
                        <span class="text-muted m-l-small pull-right">
                             <a class="label bg-success" href="javascript:void(0);" onclick="excelfile('<?php echo rawurlencode($_GET['invoice_no']);?>')"><i class="fa fa-print"></i> Doc</a>
                             <a class="label bg-info pdfcls" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>
                             	<a class="label bg-inverse " onclick="test();" href="javascript:void(0);"><i class="fa fa-print" ></i> Print</a>
                             <a class="label bg-success" href="javascript:void(0);" id="excel_link"><i class="fa fa-print"></i> Excel</a>
                             <a id="btn_edit"  target="_blank"  href="<?php echo $obj_general->link($rout, 'mod=details&invoice_no='.$_GET['invoice_no'].'&price=1&show_status=1&inv_status='.$_GET['inv_status'],'',1); ?>" name="btn_edit" class="label bg-success">Show Box Detail</a>
                        </span>
                    </header>
                  <div class="panel-body">
                    <form name="form_list" id="form_list" method="post">
                    
                        <div id="test">
                <?php  $html =$obj_invoice->viewDetailsDatatable($invoice_no,1,$_GET['price']);

                
        
        
               //printr($html);die;
                  $option=array();
                  	$scoop_no = $roll_no = $mailer_no = $sealer_no = $storezo_no = $paper_no = $con_no =$gls_no=$chair_no =$oxygen_absorbers_no =$silica_gel_no =$val_no ='';
            		$scoop_series = $roll_series = $mailer_series = $sealer_series = $storezo_series = $paper_series = $con_series =$gls_series =$chair_series =$oxygen_absorbers_series=$silica_gel_series=$val_series ='';
            		$total_amt_scoop = $total_amt_roll = $total_amt_mailer = $total_amt_sealer = $total_amt_storezo = $total_amt_paper = $total_amt_con =$total_amt_gls=$total_amt_valve=$total_amt_val=$total_amt_oxygen_absorbers=$total_amt_silica_gel=$total_amt_chair=0;
            		$tot_scoop_qty = $tot_roll_qty = $tot_mailer_qty = $tot_sealer_qty = $tot_storezo_qty = $tot_paper_qty = $tot_con_qty = $tot_gls_qty =$tot_val_qty=$tot_chair_qty=$tot_silica_gel_qty=$tot_oxygen_absorbers_qty = 0;
            		$tot_scoop_rate = $tot_roll_rate = $tot_mailer_rate = $tot_sealer_rate = $tot_storezo_rate = $tot_paper_rate = $tot_con_rate =$tot_gls_rate=$tot_oxygen_absorbers_rate==$tot_silica_gel_rate=$tot_chair_rate=$tot_val_rate=0;    //  printr($alldetails);
                        foreach($html['alldetails'] as $details){
                                    if($details['product_id']=='11')
                                    {
                                        $tot_qty_scoop=$obj_invoice->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
                                        $tot_scoop_qty = $tot_scoop_qty + $tot_qty_scoop['total']; 
                                        $tot_scoop_rate = $tot_scoop_rate + $tot_qty_scoop['rate'];
                                        $total_amt_scoop = $total_amt_scoop + $tot_qty_scoop['tot_amt'];
                                    }
                                    else if($details['product_id']=='6')
                                    {
                                        $tot_qty_roll=$obj_invoice->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);

                                    //  printr($tot_qty_roll);
                                        $tot_roll_qty = $tot_roll_qty + $tot_qty_roll['total']; 
                                        $tot_roll_rate = $tot_roll_rate + $tot_qty_roll['rate'];
                                    //  $total_amt_roll = $total_amt_roll + $tot_qty_roll['tot_amt'];
                                        $net_pouches_roll = $obj_invoice->getIngenBox($invoice_no,$details['product_id'],0);
                                    
                                        $total_amt_roll = $net_pouches_roll['total_amt'];
                                    
                                    }
                                    else if($details['product_id']=='10')
                                    {
                                        $tot_qty_mailer=$obj_invoice->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
                                        $tot_mailer_qty = $tot_mailer_qty + $tot_qty_mailer['total']; 
                                        $tot_mailer_rate = $tot_mailer_rate + $tot_qty_mailer['rate'];
                                        $total_amt_mailer = $total_amt_mailer + $tot_qty_mailer['tot_amt'];
                                    }
                                    else if($details['product_id']=='23')
                                    {
                                        $tot_qty_sealer=$obj_invoice->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
                                        $tot_sealer_qty = $tot_sealer_qty + $tot_qty_sealer['total']; 
                                        $tot_sealer_rate = $tot_sealer_rate + $tot_qty_sealer['rate'];
                                        $total_amt_sealer = $total_amt_sealer + $tot_qty_sealer['tot_amt'];
                                    }
                                    else if($details['product_id']=='18')
                                    {
                                        $tot_qty_storezo=$obj_invoice->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
                                        $tot_storezo_qty = $tot_storezo_qty + $tot_qty_storezo['total']; 
                                        $tot_storezo_rate = $tot_storezo_rate + $tot_qty_storezo['rate'];
                                        $total_amt_storezo = $total_amt_storezo + $tot_qty_storezo['tot_amt'];
                                    }
                                    else if($details['product_id']=='34')
                                    {
                                        $tot_qty_paper=$obj_invoice->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
                                        $tot_paper_qty = $tot_paper_qty + $tot_qty_paper['total']; 
                                        $tot_paper_rate = $tot_paper_rate + $tot_qty_paper['rate'];
                                        $total_amt_paper = $total_amt_paper + $tot_qty_paper['tot_amt'];
                                    }
                                    else if($details['product_id']=='47')
                                    {
                                        $tot_qty_con=$obj_invoice->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
                                        $tot_con_qty = $tot_con_qty + $tot_qty_con['total']; 
                                        $tot_con_rate = $tot_con_rate + $tot_qty_con['rate'];
                                        $total_amt_con = $total_amt_con + $tot_qty_con['tot_amt'];
                                    }
                                    else if($details['product_id']=='48')
                                    {
                                        $tot_qty_gls=$obj_invoice->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
                                        $tot_gls_qty = $tot_gls_qty + $tot_qty_gls['total']; 
                                        $tot_gls_rate = $tot_gls_rate + $tot_qty_gls['rate'];
                                        $total_amt_gls = $total_amt_gls + $tot_qty_gls['tot_amt'];
                                    }
                                    else if($details['product_id']=='37')
                                        {
                                            $tot_qty_oxygen_absorbers=$obj_invoice->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
                                            $tot_oxygen_absorbers_qty = $tot_oxygen_absorbers_qty + $tot_qty_oxygen_absorbers['total']; 
                                            $tot_oxygen_absorbers_rate = $tot_oxygen_absorbers_rate + $tot_qty_oxygen_absorbers['rate'];
                                            $total_amt_oxygen_absorbers = $total_amt_oxygen_absorbers + $tot_qty_oxygen_absorbers['tot_amt'];
                                        } 
                                    else if($details['product_id']=='38')
                                        {
                                            $tot_qty_silica_gel=$obj_invoice->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
                                            $tot_silica_gel_qty = $tot_silica_gel_qty + $tot_qtysilica_gel['total']; 
                                            $tot_silica_gel_rate = $tot_silica_gel_rate + $tot_qty_silica_gel['rate'];
                                            $total_amt_silica_gel = $total_amtsilica_gel + $tot_qtysilica_gel['tot_amt'];
                                        }  
                                    else if($details['product_id']=='72')
                                        {
                                            $tot_qty_chair=$obj_invoice->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
                                            $tot_chair_qty = $tot_chair_qty + $tot_qty_chair['total']; 
                                            $tot_chair_rate = $tot_chair_rate + $tot_qty_chair['rate'];
                                            $total_amt_chair = $total_amt_chair + $tot_qty_chair['tot_amt'];
                                        }
                                    else if($details['product_id']=='63')
                                    {
                                        $tot_qty_val=$obj_invoice->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
                                        $tot_val_qty = $tot_val_qty + $tot_qty_val['total']; 
                                        $tot_val_rate = $tot_val_rate + $tot_qty_val['rate'];
                                        $total_amt_valve = $total_amt_valve + $tot_qty_val['tot_amt'];
                                    }else{
                                        $total_amt_val=$html['invoice_qty']['tot'];
                                    }
                        }
        
    
        $menu_id = $obj_invoice->getMenuPermission(177,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);     
        $invoice=$obj_invoice->getInvoiceNetData($invoice_no);
        $currency=$obj_invoice->getCurrencyName($invoice['curr_id']);
        if($invoice['country_destination']==170)
            $item_text='Special Code';
        else
            $item_text='Item No.';
            
        $totalboxes=$obj_invoice->colordetails($invoice_no,0,'','',''); 
        $total_box=$obj_invoice->colordetails($invoice_no,0,'','','',$option);
    
            if(isset($total_box) && !empty($total_box))
            {
               
                    $tot_inv=count($total_box);
           
            
                $per_page=$invoice['box_limit'];
                $total_pages=$tot_inv/$per_page;
                
                //getColorDetailstotalecho $tot_inv.'=='.$per_page;die;
                    for($page_no=0;$page_no<$total_pages;$page_no++)
                    {
                        $start=$per_page*$page_no;
                        //echo $start;
                        $end=$start+$per_page;
                        //echo $end;
                        if($tot_inv>$invoice['box_limit'])
                        {
                            if($page_no==0)
                                $limit=' LIMIT '.$invoice['box_limit'].' OFFSET 0';
                            else
                                $limit=' LIMIT '.$invoice['box_limit'].' OFFSET  '.$start;
                        }
                        else
                                $limit='';
                        
                        $description='';$valve='';$zipper_name='';$gross_weight='';
                    	$style='';
					
							if($page_no>0)
								$style='page-break-before:always;';
					
                           ?>
                             
                   <form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data" style="<?php echo $style;?>">                    
                       <div class="table-responsive"  style="width:730px; <?php echo $style;?>">
                                <?php     if($page_no>0)           {?>
                                        <div style="width:730px;">                                 
                                  <?php   }
                                    else
                                    { ?>
                                       <div style="width:730px;">                                    
                                       
                                  <?php   }
                                      
                                    if(!empty($menu_id) || !empty($menu_admin_permission)){
                                        $i = 13;
                                    }
                                    else{
                                        $i = 12;
                                    }
                                    ?>
                               <table  id="example"  class="table b-t table-striped text-small table-hover detail_table" style="width:730px;">

                                 <?php
                                    if($html['price']==1)
                                    {
                                        $colspan=$i;
                                        if($invoice['country_destination']==170)
                                            $colspan=12;
                                    }   
                                    else
                                    {
                                        $colspan=10;
                                        if($invoice['country_destination']==170)
                                            $colspan=12;
                                    }  ?> 
                                       <thead>
                                              <?php   if($page_no=='0')
                                                {?>
                                                    <tr><th colspan="<?php echo $colspan;?>" style="text-align:center;">

                                                         <?php
                                                        if($html['price']==1){?>
                                                           <h2>Packaging Details With Price List</h2>
                                                  <?php }else {?>
                                                            <h2>Packaging Details List </h2>
                                                        <?php }?>
                                                    </th></tr>
                                                <?php }?>
                                                <tr>
                                                            <th  colspan=" <?php echo $colspan;?>" style="text-align:center;padding:0;"><h4>Detailed Packing List  <?php echo $page_no+1;?> </h4></th>
                                                        </tr>
                                                        <tr>
                                                            <th  style="text-align:center" style="width:20px">Box Nos.</th>  
                                                             <?php                
                                                            if($invoice['country_destination']==253){
                                                                ?>
                                                                <th style="text-align:center" style="width:35px">Buyer\'s Order No</th><?php }?>
                                                         <th  style="text-align:center" colspan="3">Product</th>
                                                             <?php 

                                                          
                                                            if($invoice['country_destination']=='253')
                                                            { 
                                                                ?>
                                                              <th  style="text-align:center"style="width:35px"> <?php echo $item_text; ?></th>
                                                            <?php }
                                                            if($invoice['country_destination']!=253)
                                                                {?>
                                                                <th  style="text-align:center"colspan="2">Size</th>
                                                                 <?php } ?>
                                                            <th style="text-align:center" style="width:25px">Quantity</th>
                                                                       <th style="text-align:center" style="width:35px">Gr. Wt in kgs</th>
                                                                       <th  style="text-align:center" style="width:35px">Net. Wt in kgs </th>
                                                            <?php
                                                             if($invoice['country_destination']==170 && $html['price']==0)
                                                            {
                                                                ?>      
                                                                
                                                               <th style="text-align:center" style="width:35px">Invoice Number</th>
                                                               <th  style="text-align:center"style="width:35px"> <?php echo $item_text; ?></th>
                                                            <?php } ?>

                                                           <?php
                                                         if($html['price']==1)
                                                            {
                                                                ?>
                                                                <th style="text-align:center" style="width:25px">Rate</th>
                                                               <?php
                                                                if(!empty($menu_id) || !empty($menu_admin_permission))
                                                                    { 
                                                                        ?>
                                                                        <th  style="text-align:center"style="width:25px">Original Rate</th>
                                                                   <?php  } ?>
                                                                <th  style="text-align:center" style="width:30px">Total Amount</th>
                                                          <?php   } ?>
                                                             <?php if($invoice['country_destination']!=253){ ?>

                                                                <th style="text-align:center" style="width:35px">Buyer\'s Order No</th>

                                                            <?php } 

                                                            if($html['status']==0){
                                                                ?>
                                                                <th style="text-align:center" style="width:35px">Action</th>
                                                           <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                
                                                <?php
                                                    $parent_id=0;
                                                     
                                                    $colordetails=$obj_invoice->colordetailsTest($invoice_no,$parent_id,'','',$limit,$option);
                                                    //printr($colordetails);
                                                //die;  

                                                    $i=1;
                                                    $tot_qty=0;$tot_net_weight=0;$tot_gross_weight=0;$tot_rate=0;$tot_amt=0 ;$tot_discount_rate  =$tot_amt_show=$tot_show=$total_amt_air=0;
                                                    if(isset($colordetails) && !empty($colordetails))
                                                    {
                                                        $tot_qty=0;$tot_net_weight=0;$tot_gross_weight=0;$tot_rate=0;$tot_amt=$tot_netW=0;
                                                        $bono='';$o_no =$ref_number=$item_number='';
                                                        $i_color_id='0';
                                                        foreach($colordetails as $key=>$color1)
                                                        {
                                                             $color=$obj_invoice->Product_detailTest($invoice_no,$color1['invoice_product_id'],$color1['invoice_color_id']);
                                                          // printr($color);
                                                            $color['genqty']=$color1['genqty'];
                                                            $color['net_weight']=$color1['net_weight'];
                                                            $zipper=$obj_invoice->getZipper(decode($color['zipper']));
                                                            //if($zipper['zipper_name']!='No zip')
                                                                $zipper_name=$zipper['zipper_name'];
                                                            // if($color['valve']!='No Valve')
                                                                $valve=$color['valve'];
                                                            $childBox=$obj_invoice->colordetailsTEST($invoice_no,$color1['in_gen_invoice_id']);
                                                           // printr()// 
                                                            $c_name=$color['color'];
                                                        //  if($color['pouch_color_id'] == '-1')
                                                            if($color['color_text']!='')
                                                            {
                                                                $c_name = $color['color_text'].'   '.$color['color'];
                                                            }
                                                        if($color['filling_details']!=''){
                                                                $filling_details=$color['filling_details'];
                                                            }else{
                                                                $filling_details='';
                                                            }
                                                       
                                                                $product_decreption = $obj_invoice->getProductCode($color['invoice_product_id']);
                                                                
                                                                
                                                            if($color['product_id'] == '47'||$color['product_id'] == '48'||$color['product_id'] == '72'){
                                                                $color['size']=$color['size'];
                                                                 $description=$product_decreption['description'];
                                                            }else{
                                                                $color['size']= filter_var($color['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                                                                $description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$color['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') '.$filling_details.'' ;
                                                            }
                                                          
                                                            if($color['pouch_color_id'] == '-1')
                                                            {
                                                                    $size=$color['dimension'];
                                                              
                                                            }else{
                                                               if($invoice['country_destination']!=253){
                                                                    $size=$color['size'].' '.$color['measurement'];
                                                               }else{
                                                                    $size_us=$color['size'].' '.$color['measurement'];
                                                                     $s_us=$obj_invoice->getsizeForUS($size_us);
                                                                   if($s_us!=''){
                                                                          $size=$s_us;
                                                                     }else{
                                                                          $size=$size_us;
                                                                     }
                                                               }  
                                                            } 
                                                            //$size=$color['size'].' '.$color['measurement'];
                                                            $qty=$color['genqty'];
                                                            $rate=$color['rate'];
                                                            $discount_rate = $color['dis_rate'];
                                                            $net_w = $tot_netW = $color['net_weight'];
                                                            $child_qty=0;$total_r_child=$c_rate=$discount_total_r_child = $c_discount_rate =0 ;
                                                        
                                                            if($color['product_id']!=6){
                                                                $total_r=$qty*$rate;
                                                            }else{
                                                                $total_r=$color['net_weight']*$rate;
                                                            } 
                                                             $o_no = $color['buyers_o_no'];
                                                             $ref_number = $color['ref_no'];
                                                             $item_number = $color['item_no'];
                                                             
                                                             $div=' + ';
                                                             
                                                                
                                                                if(isset($childBox) && !empty($childBox))
                                                                {
                                                                    foreach($childBox as $ch1)
                                                                    {// printr($ch);

                                                                        $ch=$obj_invoice->Product_detailTest($invoice_no,$ch1['invoice_product_id'],$ch1['invoice_color_id']);
                                                                         $ch['genqty']=$ch1['genqty'];
                                                                    if($ch['product_id'] != '47'||$ch['product_id'] != '48'||$ch['product_id'] != '72')
                                                                        $ch['size']=filter_var($ch['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                                                                    if($ch['filling_details']!=''){
                                                                        $filling_details_ch=$ch['filling_details'];
                                                                    }else{
                                                                        $filling_details_ch='';
                                                                    }
                                                                    
                                                                    if($color['pouch_color_id'] == '-1' ||  $ch['color_text']!="")
                                                                    {
                                                                       
                                                                        $c_name_ch = $ch['color_text'].' '.$ch['color'] ;   
                                                                    
                                                                        $size_ch=$ch['dimension'];
                                                                            
                                                                        
                                                                    }
                                                                else{
                                                                            
                                                                      
                                                                        
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
                                                                    
                                                                        $c_name_ch=$ch['color'];
                                                                    }
                                                              
                                                                    $zipper_child=$obj_invoice->getZipper(decode($ch['zipper']));
                                                                    $description.=$div.''.$c_name_ch. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$ch['product_name'] ),0,3)).' '.$zipper_child['zipper_name'].' '.$ch['valve'].') '.$filling_details_ch.'' ;          
                                                                    /*if($ch['dimension']!='')
                                                                        $size.=' + '.$ch['dimension'];
                                                                    else*/
                                                                        $size.=$div.''.$size_ch;
                                                                    //$size.=' + '.$ch['size'].' '.$ch['measurement'];
                                                                    if($ch['genqty']!='')
                                                                    $qty.=$div.''.$ch['genqty'];
                                                                    //$child_qty += $ch['genqty'];
                                                                    if($ch['rate']!='')
                                                                    {
                                                                        $rate.=$div.''.$ch['rate'];
                                                                        $total_r_child+=($ch['genqty']*$ch['rate']);
                                                                        $c_rate +=$ch['rate'];
                                                                    }
                                                                    if($ch['dis_rate']!=''){
                                                                        $discount_rate.=$div.''.$ch['dis_rate'];
                                                                        $discount_total_r_child+=($ch['genqty']*$ch['dis_rate']);
                                                                        $c_discount_rate +=$ch['dis_rate'];
                                                                    }
                                                                    $net_w.=$div.''.$ch['net_weight'];
                                                                    
                                                                    $tot_qty=$tot_qty+$ch['genqty'];
                                                                    $tot_netW = $tot_netW + $ch['net_weight'];
                                                                    $o_no .= $div.''.$ch['buyers_o_no'];
                                                                    $ref_number .= $div.''.$ch['ref_no'];
                                                                    $item_number .=  $div.''.$ch['item_no'];
                                                                    //$tot_net_weight = $tot_netW;
                                                                    
                                                                //printr($rate);    printr($qty);   
                                                                }
                                                            }
                                                            $tot_ch_all_rate=$total_r_child+$total_r;
                                                            
                                                            //printr($description);
                                                            //$gross_weight=$color['net_weight']+$color['box_weight'];
                                                            $gross_weight=$tot_netW+$color1['box_weight'];
                                                            
                                                            //echo $gross_weight;
                                                            
                                                            $tot_qty=$color['genqty']+$tot_qty;
                                                            
                                                            $tot_gross_weight=$gross_weight+$tot_gross_weight;
                                                            
                                                            $tot_net_weight=$tot_netW+$tot_net_weight;
                                                            
                                                            $tot_rate=$tot_rate+$color['rate']+$c_rate;
                                                            $tot_discount_rate = $tot_discount_rate + $color['dis_rate']+$c_discount_rate;
                                                            $tot_amt=$tot_ch_all_rate+$tot_amt;
                                                            if(isset($color1['box_no']) && $color1['box_no']!=0)
                                                            {

                                                                $box_no=$color1['box_no'];
                                                            }
                                                            else
                                                            {
                                                                $box_no='';
                                                            }
                                                        ?>
                                                            <tr>
                                                         

                                                            <?php
                                                                    if($html['status']==0)
                                                                    { ?>
                                                                        
                                                                              <td style="text-align:center"><input type="text" class="form-control  validate[required]"  name="gen_id<?php echo $i;?>_page_no<?php echo ($page_no+1);?>"  onblur="<?php edit_box_no('.$i.','.($page_no+1).') ?>" id="gen_id<?php echo $i;?>_page_no<?php echo ($page_no+1);?>" style="width:auto;" value="<?php echo $box_no; ?>"  />                        
                                                                               <input type="hidden"  name="gen_unique_id<?php echo $i; ?>_page_no<?php echo ($page_no+1); ?>" id="gen_unique_id<?php echo $i; ?>_page_no<?php echo ($page_no+1); ?>" value="<?php echo $color['in_gen_invoice_id']; ?>"  />
                                                                                    </td>
                                                                                                
                                                                   <?php }
                                                                    else{ ?>
                                                                        <td style="text-align:center"><?php echo $box_no; ?></td>
                                                                   <?php } 
                                                                    
                                                                    $end=$box_no;
                                                                
                                                                    if($invoice['country_destination']==253){ ?>
                                                                        <td style="text-align:center"><?php echo $color['ref_no']; ?></td>
                                                                   <?php }
                                                                    
                                                            
                                                                    if($invoice['country_destination']==253){ ?>
                                                                                <td colspan="3" style="text-align:center"><?php echo $size.' '.$description; ?> </td>
                                                                   <?php 
                                                                        }
                                                                    else{
                                                                     ?>
                                                                    <td colspan="3" style="text-align:center"><?php echo $description; ?></td>
                                                                   <?php  }
                                                                        
                                                                    if($invoice['country_destination']=='253' ){
                                                                     ?>
                                                                    <td style="text-align:center"><?php echo $color['item_no'];?></td>
                                                                           <?php    }  
                                                                    if($invoice['country_destination']!=253){ ?>
                                                                            <td  colspan="2" style="text-align:center"><?php echo $size; ?></td>
                                                                            <?php }?>
                                                                            <td style="text-align:center"><?php echo $qty; ?></td>
                                                                            <td style="text-align:center"><?php echo number_format($gross_weight,3); ?></td>
                                                                            <td style="text-align:center"><?php echo $net_w;?></td>
                                                                            <?php 

                                                                    if($invoice['country_destination']=='170' && $html['price']==0 )
                                                                    {
                                                                        $row='';
                                                                        if($i == '1')
                                                                        {
                                                                            $row = 'rowspan="'.count($colordetails).'"';
                                                                            ?>
 													                                <td <?php echo $row;?> style="vertical-align: middle;"><b>Invoice No.<?php echo $invoice['invoice_no']; ?>/<?php echo (date('y')).'-'.(date('y')+1); ?></b></td> 
                                                                       <?php  } ?>
                                                                        
                                                                    <td style="text-align:center" ><?php echo  $item_number; ?></td>
                                                                   <?php  }
                                                                    
                                                                    if($html['price']==1)
                                                                    {
                                                                        $rate_for_print=number_format($color['rate'],3);
                                                                        ?>
                                                                        <td style="text-align:center"><?php echo $rate; ?> </td>


                                                                        <?php 
                                                                            if(!empty($menu_id) || !empty($menu_admin_permission))
                                                                            {?>
                                                                                <td style="text-align:center"><?php echo $discount_rate?></td>
                                                                         <?php    }?>
                                                                      <td style="text-align:center">  <?php  echo number_format($tot_ch_all_rate,3);?></td>
                                                                                    
                                                            <?php           }

                                                                    if($invoice['country_destination']!=253)
                                                                    { ?>
                                                                      
                                                                            
                                                                          <td style="text-align:center">
                                                                              <?php   
                                                                        
                                                                          if($ref_number!=0){
                                                                               $ref_number=$ref_number;
                                                                            }else{
                                                                               $ref_number= $o_no;
                                                                            }
                
                                                                            if($invoice['country_destination']!='14' && $invoice['country_destination']!='214'  && $invoice['country_destination']!='155')  
                                                                                echo $ref_number;
                                                                            else
                                                                                echo $o_no;
                                                                                
                                                                          ?>
                                                                        </td>
                                                                            
                                                                        <?php   
                                                                
                                                                            $bono=$color['buyers_o_no'];
                                                                    }
                                                                    
                                                                    if($html['status']==0)
                                                                    { ?>
                                                                      <td>
                                                                                        <a class="btn btn-danger btn-sm" id=" <?php echo $color['in_gen_invoice_id'];?>" href="javascript:void(0);">
                                                                                        <i class="fa fa-trash-o"></i></a>
                                                                                        <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="" id="addmore" data-original-title="Add Box Detail" onclick="add_box(   <?php   echo $invoice_no;?>,   <?php   echo $i;?>,   <?php   echo $color['in_gen_invoice_id'];?>,   <?php   echo $color1['box_weight'];?>)"><i class="fa fa-plus"></i></a>
                                                                                    </td>

                                                              <?php   
                                                                    }?>
                                                            
                                                   </tr>   <?php   
                                                            $description='';
                                                            $i++;   
                                                        }
                                        
                                            
                                                $sri_charge=0;
                                                if($html['price']==1)
                                                {
                                                    
                                                
                                                    if($tot_inv==$box_no)
                                                    {
                                                        
                                                        if($invoice['cylinder_charges']!='0.00')
                                                        {
                                                            
                                                            if(($box_no+1)==($tot_inv+1))
                                                            {?>
                                                                    <tr>
                                                                                    <td style="text-align:center"></td>
                                                                                    <td style="text-align:center" colspan="3">Cylinder Making Charges</td>
                                                                                    <td style="text-align:center" colspan="6"></td>
                                                                                    <td style="text-align:center" colspan="5">   <?php    echo $invoice['cylinder_charges'];?></td>
                                                                                </tr>
                                                         <?php         }
                                                            
                                                        }
                                                        if($invoice['tool_cost']!='0.00')
                                                        {
                                                            if(($box_no+1)==($tot_inv+1))
                                                            { ?>
                                                                    <tr>
                                                                                    <td style="text-align:center"></td>
                                                                                    <td style="text-align:center" colspan="3">Set Up Cost</td>
                                                                                    <td style="text-align:center" colspan="6"></td>
                                                                                    <td style="text-align:center" colspan="5">   <?php   echo $invoice['tool_cost'];?></td>
                                                                                </tr>
                                                             <?php     }
                                                            
                                                        }
                                                        if($invoice['invoice_id']=='1809')
                                                        {
                                                            $sri_charge='1200';
                                                           ?><tr>
                                                                                    <td style="text-align:center"></td>
                                                                                    <td style="text-align:center" colspan="3">Design Charges</td>
                                                                                    <td style="text-align:center" colspan="6"></td>
                                                                                    <td style="text-align:center" colspan="5">1200</td>
                                                                                </tr>
                                                         <?php     }
                                                        $tra =$invoice['extra_tran_charges'] ;
                                                        
                                                    if($invoice['invoice_date']<'2018-10-12'){  
                                                        if(($invoice['country_destination']==172  || $invoice['country_destination']==253) &&  ucwords(decode($invoice['transportation']))=='Air')
                                                        {
                                                            if($invoice['tran_charges']!=0 )
                                                            {
                                                            
                                                                if(($box_no-1)==($tot_inv-1))
                                                                {
                                                                 
                                                                }
                                                                
                                                            }
                                                          
                                                            $tra = $invoice['tran_charges'];
                                                        }
                                                        
                                                    }
                                                  
                                                      if($invoice['extra_tran_charges']!=0.00)
                                                        {
                                                        
                                                            if(($box_no-1)==($tot_inv-1))
                                                            { ?>
                                                                   <tr>
                                                                                    <td style="text-align:center"></td>
                                                                                    <td style="text-align:center" colspan="3">Extra Air Fright Charges</td>
                                                                                    <td style="text-align:center" colspan="6"></td>
                                                                                    <td style="text-align:center" colspan="5">   <?php   echo $invoice['extra_tran_charges'];?></td>
                                                                                </tr>
                                                            <?php      }
                                                            
                                                        }
                                                        if($invoice['country_destination']!=252)
                                                        {
                                                            $tot_amt_show = $tot_amt+$invoice['cylinder_charges']+$tra;
                                                            
                                                        }
                                                        else
                                                        {
                                                            $tot_amt_show = $tot_amt+$tra;
                                                        }
                                                        
                                                        if($invoice['country_destination']==252 || $invoice['order_user_id']==2)
                                                        {
                                                            $tot_show = $tot_amt+$tra+$sri_charge;
                                                            
                                                     
                                                               if(ucwords(decode($invoice['transportation']))=='Air')
                                                               {
                                                                   $insurance_rate= $total_amt_val - $total_amt_scoop - $total_amt_roll -$total_amt_mailer - $total_amt_sealer - $total_amt_paper - $total_amt_storezo - $total_amt_con - $total_amt_gls;

                                                                   $insurance=(($insurance_rate*110/100+$insurance_rate)*0.07)/100;
                                                                   
                                                              
                                                                   $tot_show = $tot_show + $invoice['cylinder_charges']+ $insurance;
                                                               }
                                                               else
                                                                    $tot_show = $tot_show + $invoice['cylinder_charges'];
                                                        }
                                                        else{
                                                           $tot_show = $tot_amt+$invoice['cylinder_charges']+$tra+$sri_charge+$invoice['tool_cost']; //comment by sonu 24-10-2019 beacuse duble time plus cylinder in packing list 
                                                          //  $tot_show = $tot_amt+$tra+$sri_charge+$invoice['tool_cost'];
                                                        
                                                        }
                                                        $tot_amt = $tot_amt_show;
                                                       
                                                    }
                                                    else
                                                    {
                                                        $tot_show = $tot_amt+$sri_charge;
                                                       
                                                    }
                                                }
                                            
                                                ?>
                                                   <tr>
                                                             <td style="text-align:center" colspan="'.$colspan.'">&nbsp;</td>
                                                               </tr> 
                                                               <tr>
                                                                   <td></td>   <?php   
                                                                    if($invoice['country_destination']==253){?>
                                                                      <td style="text-align:center"></td><td style="text-align:center"></td><td style="text-align:center"></td>                     
                                                                         <?php }?>
                                                                       <td style="text-align:center"><strong>Total</strong></td>
                                                                          <?php   
                                                                    if($invoice['country_destination']==253) { ?>              
                                                                        <td style="text-align:center"></td>   <?php }?> 
                                                                        
                                                                       <?php  if($invoice['country_destination']!=253){?>
                                                                           <td style="text-align:center" colspan="4"></td>    <?php }?>           
                                                                      
                                                                      <td style="text-align:center"><strong>   <?php  echo $tot_qty;?></strong></td>
                                                                                  <td style="text-align:center"><strong>   <?php   echo number_format($tot_gross_weight,3);?></strong></td>
                                                                                  <td style="text-align:center"><strong>   <?php   echo number_format($tot_net_weight,3);?></strong></td>
                                                                        
                                                                          <?php    if($html['price']==1)
                                                                        {?>
                                                                            <td style="text-align:center"><strong></strong></td>
                                                                                  <?php    if(!empty($menu_id) || !empty($menu_admin_permission))
                                                                                    { ?>
                                                                                        <td style="text-align:center"><strong></strong></td>                                                                                                <?php      }?>
                                                                                        
                                                                              <td style="text-align:center"><strong>   <?php   echo number_format($tot_show,2);?></strong></td>
                                                                       <?php       }
                                                                        
                                                                       if($invoice['country_destination']!=253){?>
                                                                           <td style="text-align:center"></td>
                                                                                
                                                                      <?php  }      if($html['status']==0) {  ?>   
                                                                          <td style="text-align:center"></td><?php }?>
                                                       </tr>
                                      <?php        }

                                                    $collapse_data[]=array('box_no'=>($start+1).' To '.$end,
                                                                            'page_no'=>($page_no+1),
                                                                            'qty'=>$tot_qty,
                                                                            'gross_weight'=>$tot_gross_weight,
                                                                            'net_weight'=>$tot_net_weight,
                                                                            'total_amount'=>$tot_show,                                  
                                                    );?>
                                                
                              </tbody>
                            </table>
                                </div>
                            </div>
                      <?php   
                }
                
            }
            
  //printr($collapse_data);      
         if(isset($html['cylinder_data'])){
             
        
       ?>
                     <div class="table-responsive"><br>
                                <div style="width:730px;">
                                    <table class="table b-t table-striped text-small table-hover detail_table"> 
                                        <thead>                         
                                            <tr>
                                                <th style="text-align:center" style="width:150px">Name of Cylinder</th>  
                                                <th style="text-align:center" style="150px">No Of Cylinder</th>
                                                <th style="text-align:center" style="width:100px">One Cylinder Price</th>
                                                <th style="text-align:center" style="width:100px">Total Price</th>
                                            </tr>
                                        </thead>
                                       <?php   
                                    foreach($html['cylinder_data']as $cylinder){?>              
                                         <tbody>                            
                                            <tr>
                                                <th style="text-align:center" style="width:150px"><?php echo $cylinder['color_text'];?></th>  
                                                <th style="text-align:center" style="150px"><?php echo $cylinder['no_of_cylinder'];?></th>
                                                <th style="text-align:center" style="width:100px"><?php echo $cylinder['cylinder_rate'];?></th>
                                                <th style="text-align:center" style="width:100px"><?php echo ($cylinder['no_of_cylinder']*$cylinder['cylinder_rate'])?></th>
                                            </tr>
                                        </tbody>    
                             <?php       }?>
                     </table>
                 </div>
             </div>
            
          <?php    } ?>
    
    
        
                        <div class="table-responsive"><br>
                                <div style="width:730px;">
                                    <table class="table b-t table-striped text-small table-hover detail_table"> 
                                        <thead>                         
                                            <tr>
                                                <th style="text-align:center" style="width:150px">Box Nos.</th>  
                                                <th style="text-align:center" style="150px">Page No.</th>
                                                <th style="text-align:center" style="width:100px">Quantity</th>
                                                <th style="text-align:center" style="width:100px">Gr. Wt in kgs</th>
                                                <th  style="text-align:center" style="width:100px">Net. Wt in kgs</th>
                                              <?php
                                                if($html['price']==1){?>
                                                  <th  style="text-align:center" style="width:100px">Extended cost</th>
                                                <?php }?>
                                            </tr>
                                        </thead>
                                        <tbody><?php
                                        $colordetails=$obj_invoice->colordetails($invoice_no,$parent_id,'','',$limit);
                                //    printr($collapse_data);
                                        $tot_qty=0;$tot_gross_weight=0;$tot_net_weight=0;$tot_amt1=0;
                                        if(isset($collapse_data))
                                        {
                                            foreach($collapse_data as $dat)
                                            { ?>
                                                <tr>
                                                                <td style="text-align:center"><?php echo $dat['box_no'];?></td>
                                                                <td style="text-align:center"><?php echo $dat['page_no'];?></td>
                                                                <td style="text-align:center"><?php echo $dat['qty'];?></td>
                                                                <td style="text-align:center"><?php echo number_format($dat['gross_weight'],3);?></td>
                                                                <td style="text-align:center"><?php echo number_format($dat['net_weight'],3);?></td>
                                                                
                                                                <?php
                                                                if($html['price']==1)
                                                                {
                                                                    if($invoice['country_destination']==252){?>
                                                                        <td style="text-align:center"><?php echo number_format($dat['total_amount'],2);?></td>
                                                                <?php  }  else { ?>
                                                                        <td style="text-align:center"><?php echo number_format($dat['total_amount'],2);?></td>
                                                          <?php    } }?>
                                                                    
                                                            </tr>
                                                            <?php
                                                $tot_qty=$tot_qty+$dat['qty'];
                                                $tot_gross_weight=$tot_gross_weight+$dat['gross_weight'];
                                                $tot_net_weight=$tot_net_weight+$dat['net_weight'];
                                                $tot_amt1=$tot_amt1+$dat['total_amount'];
                                              
                                                
                                                
                                            }
                                        }   
                                 
                                  
?>
                                        
                                        <tr>
                                                        <td style="text-align:center"></td>
                                                        <td style="text-align:center"></td>
                                                        <th style="text-align:center"><?php echo $tot_qty;?></th>
                                                        <th style="text-align:center"><?php echo number_format($tot_gross_weight,3);?></th>
                                                        <th style="text-align:center"><?php echo number_format($tot_net_weight,3);?></th>
                                                  <?php
                                                        $insurance_rate =0;
                                                 
                                                        if(($invoice['country_destination']==252 ||  $invoice['order_user_id']==2  ) && ucwords(decode($invoice['transportation']))=='Air')
                                                        {
                                                           
                                                           $insurance_rate= $tot_amt1 - $total_amt_scoop - $total_amt_roll -$total_amt_mailer - $total_amt_sealer - $total_amt_paper - $total_amt_storezo - $total_amt_con - $total_amt_gls;
                                                        
                                                            
                                                            $insurance_rate = $insurance_rate - $invoice['cylinder_charges'];
                                                             
                                                    
                                                           
                                                            $insurance=(($insurance_rate*110/100+$insurance_rate)*0.07)/100;
                                                        
                                                         
                                                        
                                                            $tot_amt1 = $tot_amt1;//
                                                       
                                                        }
                                                        else if(($invoice['country_destination']==172|| $invoice['country_destination']==125 || $invoice['country_destination']==253 ) && ucwords(decode($invoice['transportation']))=='Air')
                                                        {
                                                           $tot_amt1 = $tot_amt1;   
                                                        }
                                                        else{
                                                            $tot_amt1 = $tot_amt1;
                                                        }
                                                        if($html['price']==1) {?>
                                                         <th style="text-align:center"><?php echo number_format($tot_amt1,2);?></th>
                                                         <?php }?>
                                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    
      </form><?php
     
     if(decode($invoice['transportation'])== 'sea')
     {  ?>
          
      
                            <div class="table-responsive"><br>
                                    <div style="width:730px;">
                                        <table class="table b-t table-striped text-small table-hover detail_table"> 
                                            <thead>                         
                                                <tr>
                                                    <th style="text-align:center" style="width:150px">Detail</th>  
                                                    <th style="text-align:center"  style="150px">Total Boxes</th>
                                                    <th style="text-align:center" style="width:100px">Quantity</th>
                                                    <th  style="text-align:center"style="width:100px">G.W.T</th>
                                                    <th  style="text-align:center" style="width:100px">N.W.T</th>
                                                 <?php   if($html['price']==1)?>
                                                       <th style="text-align:center"  style="width:100px">Extended cost <?php echo $currency['currency_code'];?></th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody><?php
                                            $alldetails=$obj_invoice->getProductdeatils($invoice_no);
                                            
                                            $f_p=$f_scoop=$f_roll=$f_mailer=$f_sealer=$f_storezo=$f_box=$f_con=$f_oxygen_absorbers=$f_silica_gel=$f_val=$f_chair=true;
                                            foreach($alldetails as $detail)
                                            {
                                                    if($detail['product_id']!='11' && $detail['product_id']!='6' && $detail['product_id']!='10' && $detail['product_id']!='23' && $detail['product_id']!='18' && $detail['product_id']!='34' && $detail['product_id']!='47' && $detail['product_id']!='48' && $detail['product_id']!='63'&& $detail['product_id']!='72'&& $detail['product_id']!='37'&& $detail['product_id']!='38')
                                                {
                                                     
                                                    $charge = $invoice['tran_charges']+$invoice['cylinder_charges'];                                        
                                                    $boxDetail = $obj_invoice->getIngenBox($invoice_no,$n=2,$charge);
                                                    //printr($boxDetail);
                                                    $boxDetail['product_id']=$detail['product_id'];
                                                    if($f_p==true)
                                                    {
                                                        $pouch_detail['Pouch Detail']=$boxDetail;
                                                        $f_p=false;
                                                    }
                                                    
                                                }
                                                else if($detail['product_id']=='11')
                                                {
                                                    $boxDetail = $obj_invoice->getIngenBox($invoice_no,$detail['product_id']);
                                                    $boxDetail['product_id']=$detail['product_id'];
                                                    if($f_scoop==true)
                                                    {
                                                        $pouch_detail['scoop']=$boxDetail;
                                                        $f_scoop=false;
                                                    }
                                                }
                                                else if($detail['product_id']=='6')
                                                {
                                                    $boxDetail = $obj_invoice->getIngenBox($invoice_no,$detail['product_id']);
                                                    $boxDetail['product_id']=$detail['product_id'];
                                            //      $boxDetail['product_id']='6';
                                                    if($f_roll==true)
                                                    {
                                                        $pouch_detail['roll']=$boxDetail;
                                                        $f_roll=false;
                                                    }
                                                //  printr($boxDetail);
                                                }
                                                else if($detail['product_id']=='10')
                                                {
                                                    $boxDetail = $obj_invoice->getIngenBox($invoice_no,$detail['product_id']);
                                                        $boxDetail['product_id']=$detail['product_id'];
                                                    if($f_mailer==true)
                                                    {
                                                        $pouch_detail['Mailer Bag']=$boxDetail;
                                                        $f_mailer=false;
                                                    } 
                                                }
                                                else if($detail['product_id']=='23')
                                                {
                                                    $boxDetail = $obj_invoice->getIngenBox($invoice_no,$detail['product_id']);
                                                    $boxDetail['product_id']=$detail['product_id'];
                                                    if($f_sealer==true)
                                                    {
                                                        $pouch_detail['Sealer Machine']=$boxDetail;
                                                        $f_sealer=false;
                                                    }
                                                }
                                                else if($detail['product_id']=='18')
                                                {
                                                    $boxDetail = $obj_invoice->getIngenBox($invoice_no,$detail['product_id']);
                                                    $boxDetail['product_id']=$detail['product_id'];
                                                    if($f_storezo==true)
                                                    {
                                                        $pouch_detail['Storezo']=$boxDetail;
                                                        $f_storezo=false;
                                                    }
                                                }
                                                else if($detail['product_id']=='34')
                                                {
                                                    $boxDetail = $obj_invoice->getIngenBox($invoice_no,$detail['product_id']);
                                                    $boxDetail['product_id']=$detail['product_id'];
                                                    if($f_box==true)
                                                    {
                                                        $pouch_detail['Paper Box']=$boxDetail;
                                                        $f_box=false;
                                                    }
                                                }
                                                else if($detail['product_id']=='47')
                                                {
                                                    $boxDetail = $obj_invoice->getIngenBox($invoice_no,$detail['product_id']);
                                                    $boxDetail['product_id']=$detail['product_id'];
                                                    if($f_con==true)
                                                    {
                                                        $pouch_detail['Plastic Disposable Lid / Container']=$boxDetail;
                                                        $f_con=false;
                                                    }
                                                }
                                                else if($detail['product_id']=='48')
                                                {
                                                    $boxDetail = $obj_invoice->getIngenBox($invoice_no,$detail['product_id']);
                                                    $boxDetail['product_id']=$detail['product_id'];
                                                    if($f_gls==true)
                                                    {
                                                        $pouch_detail['Plastic Glasses']=$boxDetail;
                                                        $f_gls=false;
                                                    }
                                                }
                                                else if($detail['product_id']=='72')
                                                {
                                                    $boxDetail = $obj_invoice->getIngenBox($invoice_no,$detail['product_id']);
                                                    $boxDetail['product_id']=$detail['product_id'];
                                                    if($f_chair==true)
                                                    {
                                                        $pouch_detail['Chair']=$boxDetail;
                                                        $f_chair=false;
                                                    }
                                                } 
                                                else if($detail['product_id']=='37')
                                                {
                                                    $boxDetail = $obj_invoice->getIngenBox($invoice_no,$detail['product_id']);
                                                    $boxDetail['product_id']=$detail['product_id'];
                                                    if($f_oxygen_absorbers==true)
                                                    {
                                                        $pouch_detail['oxygen_absorbers']=$boxDetail;
                                                        $f_oxygen_absorbers=false;
                                                    }
                                                } 
                                                else if($detail['product_id']=='38')
                                                {
                                                    $boxDetail = $obj_invoice->getIngenBox($invoice_no,$detail['product_id']);
                                                    $boxDetail['product_id']=$detail['product_id'];
                                                    if($f_silica_gel==true)
                                                    {
                                                        $pouch_detail['silica_gel']=$boxDetail;
                                                        $f_silica_gel=false;
                                                    }
                                                }
                                                else if($detail['product_id']=='63')
                                                {
                                                    $boxDetail = $obj_invoice->getIngenBox($invoice_no,$detail['product_id']);
                                                    $boxDetail['product_id']=$detail['product_id'];
                                                    if($f_val==true)
                                                    {
                                                        $pouch_detail['Plastic Cap']=$boxDetail;
                                                        $f_val=false;
                                                    }
                                                }
                                            }
                                          
                                            $tot_qty=0;$tot_gross_weight=0;$tot_net_weight=0;$tot_amt=0;
                                            
                                            if(isset($pouch_detail))
                                            {
                                                foreach($pouch_detail as $key=>$data)
                                                {
                                               
                                                    
                                                    if($data['product_id']==6){
                                                        $data['total']=$data['total_amt'];
                                                    }
                                                    ?>
                                                    <tr>
                                                                    <td style="text-align:center"><?php echo $key;?></td>
                                                                    <td style="text-align:center"><?php echo $data['total_box'];?></td>
                                                                    <td style="text-align:center"><?php echo $data['qty'];?></td>
                                                                    <td style="text-align:center"><?php echo number_format($data['g_wt'],3);?></td>
                                                                    <td style="text-align:center"><?php echo number_format($data['n_wt'],3);?></td>
                                                                    
                                                                  <?php  if($key=='Pouch Detail'){
                                                                        $data['total']=$data['total']+$invoice['cylinder_charges']+ $invoice['tool_cost'];
                                                                  }
                                                                    if($html['price']==1){?>
                                                                       <td style="text-align:center"><?php echo number_format($data['total'],3);?></td>
                                                                       <?php }?>
                                                                </tr>
                                                  <?php              
                                                    $tot_qty=$tot_qty+$data['qty'];
                                                    $tot_gross_weight=$tot_gross_weight+$data['g_wt'];
                                                    $tot_net_weight=$tot_net_weight+$data['n_wt'];
                                                    $tot_amt=$tot_amt+$data['total'];
                                                    
                                                }
                                            }
                                           ?><tr>
                                                            <td style="text-align:center"></td>
                                                            <td style="text-align:center"></td>
                                                            <th style="text-align:center"><?php echo $tot_qty;?></th>
                                                            <th style="text-align:center"><?php echo number_format($tot_gross_weight,3);?></th>
                                                            <th style="text-align:center"><?php echo number_format($tot_net_weight,3);?></th>
                                                           <?php if($html['price']==1)?>
                                                               <th style="text-align:center" ><?php echo number_format($tot_amt,2);?></th>
                                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php
             }


                                ?>                    
           
                            </div>
                              <div class="form-group">
                             <div class="col-lg-9 col-lg-offset-3"> 
                                <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=view&status=1&invoice_no='.$_GET['invoice_no'].'&inv_status='.$_GET['inv_status'], '',1);?>">Cancel</a>
                             </div>
                             </div>
                            
                        </div>
                    
                    </form> 
                    <footer class="panel-footer">
                        <div class="row">
                          <div class="col-sm-3 hidden-xs"> </div>
                    
                      </footer>
                    </div>
                </section>
            </div>
        </div>
    </section>
</section>
<style type="text/css">
.table{ border-bottom:hidden; border-style:hidden; 
/* border:hidden; border-style:hidden;*/ 
    
    }
</style>


<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>-->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">

<script type="application/javascript">
/*$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
         lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
        
        buttons: [
            {   
                extend: 'pdfHtml5',
                orientation: 'portrait',
                pageSize: 'LEGAL',
                footer: 'true',
                pageLength: 'true',
                exportOptions: {
                modifier: {
                    page: 'current'
                },
            }
                
            }
        ],
         "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
             var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
         
 
            // Total over this page
            qtyTotal = api
                .column( 3, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 3 ).footer() ).html(
                qtyTotal 
            );

            GrTotal = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 4 ).footer() ).html(
                 parseFloat(GrTotal).toFixed(2) 
            );

            NetTotal = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 5 ).footer() ).html(
                 parseFloat(NetTotal).toFixed(2)
            );

            rate = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 6 ).footer() ).html(
                 parseFloat(rate).toFixed(2) 
            );

            Total = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 7 ).footer() ).html(
                  parseFloat(Total).toFixed(2)
            );
        }
    } );
} );*/
 
function test() {

	 var html="<html>";
	html+='<head>';
	
 //html+="<style>.col-lg-3 {width: 15%;}#client {    border-left: 6px solid #0087c3;    float: left;    padding-left: 6px;}h1 {  background:#333;    border-bottom: 1px solid #5d6975;    border-top: 1px solid #5d6975;    color: #FFF;    font-size: 2.4em;    font-weight: normal;    line-height: 1.4em;    margin: 0 0 20px;    text-align: center;}article, article address, table.meta, table.inventory { margin: 0 0 3em; }table.meta, table.balance { float: right; width: 50%; }table.meta:after, table.balance:after { clear: both; display: table; }table.meta th { width: 40%; }table.meta td { width: 60%; }table { font-size: 65%; table-layout: fixed; width: 100%; }table { border-collapse: separate; border-spacing: 0px; }th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }th, td { border-radius: 0; border-style: solid; }th { background: #EEE; border-color: #BBB; }td { border-color: #DDD; }</style></html>";
  
 html+="<style> table-responsive{font-size: 10%;}.m-t-large {margin-top: 20px;}.line-dashed {border-style: dashed;background: transparent;}.line {height: 2px;margin: 10px 0;font-size: 0;overflow: hidden;background-color: #fff;border-width: 0;border-top: 1px solid #e0e4e8;} .detail_table {font-size: 10%; }table.detail_table { font-size: 10%; table-layout: fixed; width: 100%; font-size:  9px;}table.detail_table { border-collapse: separate; border-spacing: 0px;font-size:  9px; } table.detail_table th, table.detail_table td { border-width: 1px; padding: 0; position: relative; text-align: left;font-size:  9px; } table.detail_table th, table.detail_table td { border-radius: 0; border-style: solid;font-size:  9px; }table.detail_table th { background: #EEE; border-color: #BBB;font-size:  9px; } table.detail_table td { border-color: #DDD; font-size:  9px;}.no_border { border-bottom: 0 none; border-radius: 0; border-top: 0 none !important; }</style></html>";
	



    html+= $('#test').html();

    html+="</html>";	//alert(html);

    var printWin = window.open('','','');

    printWin.document.write(html);

    printWin.document.close();

    printWin.focus();

    printWin.print();

    printWin.close();

}
$(".pdfcls").click(function(){          
                $(".note-error").remove();
                var url = '<?php echo HTTP_SERVER.'pdf/detailspdf_test.php?mod='.encode('details_test').'&token='.rawurlencode($_GET['invoice_no']).'&price='.$_GET['price'].'&ext='.md5('php');?>';
            //  var url = '<?php echo HTTP_SERVER.'pdf/detailspdf_test.php?mod='.encode('details_test').'&token=MTQyMA==&price='.$_GET['price'].'&ext='.md5('php');?>';
                window.open(url, '_blank');
            return false;
        });


function excelfile(id){
     var id='MTc0Ng==';
        var url = '<?php echo HTTP_SERVER.'word/invoice_packaging.php?mod='.encode('invoice').'&price='.$_GET['price'].'&ext='.md5('php');?>&token='+id;
        window.open(url, '_blank');
    return false;
}
$("#excel_link").click(function(){
   var id=<?php echo $invoice_no;?>;
  
    var price = <?php echo $_GET['price'];?>;
//  alert(lamination_id);
    
    var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=packing_detail', '',1);?>");
    
     $.ajax({
        url: url, // the url of the php file that will generate the excel file
        data : {id : id,price:price},
        method : 'post',
        success: function(response){
            excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
             $('<a></a>').attr({
                            'id':'downloadFile',
                            'download': 'packing_detail.xls',
                            'href': excelData,
                            'target': '_blank'
                    }).appendTo('body');
                    $('#downloadFile').ready(function() {
                        $('#downloadFile').get(0).click();
                    });
        }
        
    });


}); 
//var count = $('#tr').length;
//alert(count);
 
</script>