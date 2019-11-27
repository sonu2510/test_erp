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
	'text' 	=> 'Customer Leads',
	'href' 	=> $obj_general->link($rout, 'mod=customer_enquiry'.$add_url, '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);
$bradcums[] = array(
	'text' 	=> 'Customer Leads Details',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> '',
); 


//Close : bradcums

//Start : edit
$edit = '';
if(isset($_GET['reuest_id']) && !empty($_GET['reuest_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$reuest_id = base64_decode($_GET['reuest_id']);
		$enquiry_details = $obj_enquiry->getCustomerEnquiry($reuest_id);
		
	    $county = $obj_enquiry->getUserClient($enquiry_details['sales_emp_id'],$enquiry_details['sales_emp_type_id']);
        $conversion = $obj_enquiry->getConversion($county['international_branch_id'],$county['lang_id'],$enquiry_details['sales_emp_id']);
        //        printr($conversion);
	//	printr($enquiry_details);
	 $b='<b style="font-size: medium;">';$end_b='</b>';
     if($county['international_branch_id']=='6' || $county['international_branch_id']=='35')
        $b=$end_b='';
        if($county['international_branch_id']=='6' || $county['international_branch_id']=='19')
         $conversion = $obj_enquiry->getConversion($county['international_branch_id'],$county['lang_id']);
	}
}
//Close : edit
if($display_status){
	
?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i>Request Detail </h4>
    </div>
    <div class="row">
        <div class="col-lg-12 ">
         <?php include("common/breadcrumb.php");?>	
        <!-- <div class="col-sm-8 container">
             
            <div  class="top-rgt" style="right: 49%;left: 55%;width: 9%;top: -85%;">
                     <marquee behavior="alternate">
        				<img src="<?php //echo HTTP_SERVER.'admin/controller/product_minimumqty/red_icon.png';?>" style="height: 8%;">
        			</marquee>
        	  </div>
        			<a class="file-input-wrapper btn btn btn-info pull-right top-right" href="https://www.swisspack.co.in" target="_blank">Click here to visit our website <br> हमारी वेबसाइट पर जाने के लिए यहाँ क्लिक करें। </a>
               
             </div>-->
         
         <div class="col-sm-10 container">
            <section class="panel">
                  <header class="panel-heading bg-white"> 
       
          	<span>Request Details Listing</span>
          
           
          </header>
                <div class="text-block">
                  
                    
                    <img src="<?php echo HTTP_UPLOAD.'admin/country_banner/'.$conversion[0]['banner']; ?>" style="width:100%;z-index:-50;" >
                </div>
            </section>
        </div>
    
    	 
      <div class="col-sm-10">
        <section class="panel">
      </header>
          <div class="panel-body" style="">
            <form class="form-horizontal" method="post" name="form" id="form"  enctype="multipart/form-data">
             
            
        	 <fieldset>
        	   <table class='p_cls table b-t text-small table-hover'>
        	                <tr>
        	                    <th style='font-size: 20px;text-align: left;vertical-align: top;border-bottom: 1px dotted black;'> 1) Your Company Name /
                    <?php echo $b.''.hex2bin($conversion[0]['company_name']).''.$end_b;?></th><td style='font-size: 20px;border-bottom: 1px dotted black;'><?php echo $enquiry_details['company_name'];?></td>
        	                </tr>
        	                <tr><th></th><td></td></tr>
        	                <tr>
        	                    <th style='font-size: 20px;text-align: left;vertical-align: top;border-bottom: 1px dotted black;'> 2) Your Name / 
                   <?php echo $b.''.hex2bin($conversion[0]['customer_name']).''.$end_b;?></th><td style='font-size: 20px;border-bottom: 1px dotted black;'><?php echo $enquiry_details['contact_name'];?></td>
        	                </tr>
        	                <tr><th></th><td></td></tr>
        	                <tr>
        	                    <th style='font-size: 20px;text-align: left;vertical-align: top;border-bottom: 1px dotted black;'>3) Email /
                  <?php echo $b.''.hex2bin($conversion[0]['email']).''.$end_b;?></th><td style='font-size: 20px;border-bottom: 1px dotted black;'><?php echo $enquiry_details['email'];?></td>
        	                </tr>
        	                <tr><th></th><td></td></tr>
        	                <tr>
        	                    <th style='font-size: 20px;text-align: left;vertical-align: top;border-bottom: 1px dotted black;'>4) Phone No. / Mobile No.
                  <?php echo $b.''.hex2bin($conversion[0]['phone_no']).''.$end_b;?></th><td style='font-size: 20px;border-bottom: 1px dotted black;'><?php echo $enquiry_details['phone_no'];?></td>
        	                </tr>
        	                <tr><th></th><td></td></tr>
        	                <tr>
        	                    <th style='font-size: 20px;text-align: left;vertical-align: top;border-bottom: 1px dotted black;'>5) Address With Pin code /
                   <?php echo $b.''.hex2bin($conversion[0]['address']).''.$end_b;?></th><td style='font-size: 20px;border-bottom: 1px dotted black;'>
        	                        <?php echo str_replace('\n','<br>',$enquiry_details['address']);?>
        	                       </td>
        	                </tr>
        	                <tr><th></th><td></td></tr>
        	                <tr>
        	                    <th style='font-size: 20px;text-align: left;vertical-align: top;border-bottom: 1px dotted black;'>6) What whould you like to fill in pouch? /
                    <?php echo $b.''.hex2bin($conversion[0]['fill_in_pouch']).''.$end_b;?> </th><td style='font-size: 20px;border-bottom: 1px dotted black;'><?php echo $enquiry_details['filling'];?></td>
        	                </tr>
        	                <tr><th></th><td></td></tr>
        	                <tr>
        	                    <th style='font-size: 20px;text-align: left;vertical-align: top;border-bottom: 1px dotted black;'> 7) How much of weight you want to fill in pouch? /
                    <?php echo $b.''.hex2bin($conversion[0]['weight']).''.$end_b;?> </th><td style='font-size: 20px;border-bottom: 1px dotted black;'><?php echo $enquiry_details['weight'];?></td>
        	                </tr>
        	                <tr><th></th><td></td></tr>
        	                <tr>
        	                    <th style='font-size: 20px;text-align: left;vertical-align: top;border-bottom: 1px dotted black;'>8) Number of bags required /
                    <?php echo $b.''.hex2bin($conversion[0]['no_of_bags']).''.$end_b;?> </th><td style='font-size: 20px;border-bottom: 1px dotted black;'><?php echo $enquiry_details['num_bag'];?></td>
        	                </tr>
        	                <tr><th></th><td></td></tr>
        	                <tr>
        	                    <th style='font-size: 20px;text-align: left;vertical-align: top;border-bottom: 1px dotted black;'>9) Upload Your Visiting / Business Card
                    <?php echo $b.''.hex2bin($conversion[0]['bussiness_card']).''.$end_b;?></th>
        	                    <td style='font-size: 20px;border-bottom: 1px dotted black;'>
        	                        	<?php 
        	                        //	printr($enquiry_details);
        	                        	//printr(HTTP_UPLOAD.'admin/client_busi_card/'.$enquiry_details['card_name']);
                                 $html = '';			 
                                 if(isset($enquiry_details['card_name']) && !empty($enquiry_details['card_name'])) {
								
										$html .='<div class="carousel-inner" style="height: 180px;">';
									
											$ext = pathinfo($enquiry_details['card_name'], PATHINFO_EXTENSION);
										
											if($ext!='pdf')
											{
                                            	$html .='<a href="'.HTTP_UPLOAD.'admin/client_busi_card/'.$enquiry_details['card_name'].'" target="_blank"><img class="" alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/client_busi_card/'.$enquiry_details['card_name'].'"></a><br><a href="'.HTTP_UPLOAD.'admin/client_busi_card/'.$enquiry_details['card_name'].'" target="_blank">'.$enquiry_details['card_name'].'</a>';
												
											}
											else
											{
												$html .='<a href="'.HTTP_UPLOAD.'admin/client_busi_card/'.$enquiry_details['card_name'].'" target="_blank"><img class="" alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/dieline/pdf.jpg"></a><br>
														<a href="'.HTTP_UPLOAD.'admin/client_busi_card/'.$enquiry_details['card_name'].'" target="_blank">'.$enquiry_details['card_name'].'</a>';	
											}
                                        	$html .='</div>';
                                        	$html .='</div>';
                                    	
								
                                 	echo $html;
                                 } else {
                                    echo '<p><img class="" width="100" height="100" src="'.HTTP_UPLOAD.'admin/dieline/blank.jpg" alt="Image"></p>';
                                 }
                                ?>
        	                    </td>
        	                </tr>
        	            </table>
    	           </fieldset>
    	           <div class="col-sm-10 container"> 
    	           <a href="<?php echo $obj_general->link($rout, 'mod=customer_enquiry'.$add_url, '',1); ?>" class="btn btn-inverse " >Cancel</a>
                </div>
           </form>
          </div>
        </section>
        
      </div>
      </div>
    </div>
  </section>
</section>
 

<link rel="stylesheet" href="css/font.css">
<link rel="stylesheet" href="css/app.v2.css" type="text/css" />
<link rel="stylesheet" href="css/custom.css">
<style>
    .file-input-wrapper { overflow: hidden; position: relative; cursor: pointer; z-index: 1; }
    .file-input-wrapper input[type=file], 
    .file-input-wrapper input[type=file]:focus, 
    .file-input-wrapper input[type=file]:hover { position: absolute; top: 0; left: 0; cursor: pointer; opacity: 0; filter: alpha(opacity=0); z-index: 99; outline: 0; }
    .file-input-name { margin-left: 8px; }
    .text-block {
        background-color: #f3f7fa;
        color: white;
    }
    .top-rgt {
         position: absolute;
        top: -9%;
        right: 2%;
    }
    .container 
    {
       position: relative;
        text-align: center;
        color: black;
    }
    .btn
    {
        font-size : 12px;
    }
    .body_tag
    {
        
         background-image: url(<?php echo HTTP_SERVER.'Home-page-banner.jpg'; ?>); 
            
    }
    
 
    
</style>


<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>