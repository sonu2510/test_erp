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
//[kinjal] (13-4-2017) for get address_book_id wise data
$add_book_id='0';
$add_url='';
if(isset($_GET['address_book_id']))
{
		$add_book_id = decode($_GET['address_book_id']);
		$add_url = '&address_book_id='.$_GET['address_book_id'];
}

$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1),
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
$edit = '';
$click = '';
if(isset($_POST['save_button'])) {
//	printr($_POST);die;
	$proforma_id = decode($_GET['proforma_id']);
	//echo $proforma_id;
	$obj_pro_invoice->saveProformaStatus($proforma_id);
    $obj_pro_invoice->UpdateTotalInvoicePrice($proforma_id);
	$obj_session->data['success'] = UPDATE;
	
	$url = HTTP_SERVER.'pdf/proformapdf_file.php?mod='.encode('proformainvoice_file').'&token='.encode($proforma_id).'&ext='.md5('php');
	$obj_pro_invoice->sendInvoiceEmail($proforma_id,'tech@swisspack.co.in',$url);
	if($_SESSION['ADMIN_LOGIN_SWISS']!='44' && $_SESSION['LOGIN_USER_TYPE']!='2')
	{
				page_redirect($obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'], '',1));
			
	}
	
}
$proforma_new=$obj_pro_invoice->getProforma(decode($_GET['proforma_id']));
//printr($proforma_new);
if(isset($_GET['proforma_id']) && !empty($_GET['proforma_id'])){
	if(!$obj_general->hasPermission('view',$menuId)){
		$display_status = false;
	}else{
		$proforma_id = base64_decode($_GET['proforma_id']);
		$proforma=$obj_pro_invoice->getProformaData($proforma_id);
		$click = 1;
		//printr($proforma);
	}
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
if(isset($_POST['btn_sendemail'])){
	$url = HTTP_SERVER.'pdf/proformapdf_file.php?mod='.encode('proformainvoice_file').'&token='.$_GET['proforma_id'].'&ext='.md5('php');
	$obj_pro_invoice->sendInvoiceEmail(decode($_GET['proforma_id']),$_POST['smail'],$url);
	}
	$proformaid=decode($_GET['proforma_id']); 
?>
<section id="content">
    <section class="main padder">
   		<div class="clearfix">
    		<h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    	</div>
    	<div class="row">
    		<div class="col-lg-12"><?php include("common/breadcrumb.php");?>	</div> 
		    <div class="col-sm-8" style="width:75%">
    			<section class="panel" >  
    				<header class="panel-heading bg-white">
    					<span>Proforma Invoice Detail</span>
                        <?php if(isset($proforma) && ($proforma['proforma_status'] != '1') && $proforma_new['status'] == '1') { ?>
    					<span class="text-muted m-l-small pull-right">
                        <?php $proformaid=decode($_GET['proforma_id']); //echo $proformaid;
							  $getdata=$obj_pro_invoice->getappdisdata($proformaid,'LIMIT 1');
							 
							  if(isset($getdata) && $getdata!='')
							  {
								  $dataval=array(1,2);
								  if(in_array($getdata[0]['appr_disapp_status'],$dataval))
								  {
									//echo "found"; echo "<br>";
									if($getdata[0]['appr_disapp_status']=='1')
									{
										echo "<b style='color:green'>This Proforma Invoice is Approved </b> &nbsp;&nbsp;&nbsp;&nbsp;
										<br><b style='color:red'> Do You Want to Disapprove?&nbsp;&nbsp;</b> ";
									?>
										<a class="label bg-danger" href="javascript:void(0);" onclick="approve(2,<?php echo $proformaid;?>);">
										<i class="fa fa-thumbs-down"></i> Disapprove</a>
							<?php  }
									else
									{
										echo "<b style='color:red'>This Proforma Invoice is Disapproved</b> &nbsp;&nbsp;&nbsp;&nbsp;
										<br><b style='color:green'> Do You Want to Approve?&nbsp;&nbsp;</b> ";
									?>
                                 	<a class="label bg-primary" href="javascript:void(0);" onclick="approve(1,<?php echo $proformaid;?>);">
										<i class="fa fa-thumbs-up"></i> Approve</a>
                            <?php
									}
								}
							}	
							else
							{
							?>
                        	    <!--<a class="label bg-primary" href="javascript:void(0);" onclick="approve(1,<?php echo $proformaid;?>);"><i class="fa fa-thumbs-up"></i> Approve</a>
                                <a class="label bg-danger" href="javascript:void(0);" onclick="approve(2,<?php echo $proformaid;?>);"><i class="fa fa-thumbs-down"></i> Disapprove</a>-->
                           <?php 
						   }
						   ?>
    						<a class="label bg-info " onclick="test();" href="javascript:void(0);"><i class="fa fa-print" ></i> Print</a>
                           <a class="label bg-info pdfcls" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>
                           <a class="label bg-success" href="javascript:void(0);" onclick="wordlink('<?php echo rawurlencode($proforma_id);?>')"><i class="fa fa-print"></i> Doc</a>

						    <a class="label bg-primary sendmailcls" href="javascript:void(0);"><i class="fa fa-envelope"></i> Send Mail</a>
    					</span>
                        <?php }?>
    				</header>
      
    				<div class="panel-body">
    			<span class="text-muted m-l-small pull-right"></span>
			    <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
    				<div>	 
					    <div class="form-group">
						    <h1>PROFORMA INVOICE</h1>
					    </div>
				    </div>
    				<div class="panel-body font_medium"  id="print_div" style="font-size: 25px;">
                    <?php
					$html = $obj_pro_invoice->viewProformaInvoice($proforma['proforma_id']); 
					echo $html;
					?> 
                   
                    </div>   					
               
                <?php /*?><div>
				 <div >
					<h1>HISTORY</h1>
				</div>
                	<table style="border: 1px solid black;font-size:15px;" cellspacing="0px" cellpadding="10px" style=" border-spacing: 0px; ">
                    	<tbody>
							<tr>
								<td width="5%"><div align="center"><b>Sr. No</b></div></td>
                                <td width="20%"><div align="center"><b>Status</b></div></td>
								<td width="40%"><div align="center"><b>Description</b></div></td>
								<td width="15%"><div align="center"><b>Date</b></div></td>
								<td width="10%"><div align="center"><b>User</b></div></td>
							</tr>
                            <?php $getdata=$obj_pro_invoice->getappdisdata($proformaid,'');
							//printr($getdata);die;
							$sl_no=1;
						if($getdata!='')
						{
							foreach($getdata as $data)
							{
								
								if($data['appr_disapp_status']=='1')
									$status="Approved";
								else
									$status="Disapproved";
								$userInfo = $obj_pro_invoice->getUser($data['app_dis_user_id'],$data['app_dis_user_type_id']);
								 							
								$addedByImage = $obj_general->getUserProfileImage($data['app_dis_user_type_id'],$data['app_dis_user_id'],'100_');
								$addedByInfo = '';
								$addedByInfo .= '<div class="row">';
									$addedByInfo .= '<div class="col-lg-3"><img src="'.$addedByImage.'"></div>';
									$addedByInfo .= '<div class="col-lg-9">';
									if($userInfo['city']){ $addedByInfo .= $userInfo['city'].', '; }
									if($userInfo['state']){ $addedByInfo .= $userInfo['state'].' '; }
									if(isset($userInfo['postcode'])){ $addedByInfo .= $userInfo['postcode']; }
									$addedByInfo .= '<br>Telephone : '.$userInfo['telephone'].'</div>';
								$addedByInfo .= '</div>';
								$addedByName = $userInfo['first_name'].' '.$userInfo['last_name'];
								str_replace("'","\'",$addedByName);
							?>
                    	   	<tr>
                        		<td><?php echo $sl_no++;?></td>
                            	<td><?php echo $status;?></td>
                            	<td><?php echo $data['description'];?></td>
                            	<td><?php echo dateFormat(4,$data['app_dis_date']);?></td>
                                <td><a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $addedByInfo;?>' title="" data-original-title="<b><?php echo $addedByName;?></b>"><?php echo $userInfo['user_name'];?></a></td>
                    		</tr> 
                            <?php }
						} ?>
                	</table>
                </div>
                <div>
                <table>
 					 <tr>
    					<td style="padding:10px; font-size:15px; font-family:Arial, Helvetica; text-align:center;">
                          
     					 <br/>
     					 
    					</td>
   					 <td>
     				 <img 
                     src="http://barcode.tec-it.com/barcode.ashx?code=Code128&modulewidth=fit&data=jayashree&dpi=96&imagetype=gif&rotation=0&color=&bgcolor=&fontcolor=&quiet=0&qunit=mm&download=true" alt="Barcode generated by TEC-IT"/>
   					 </td>
  					</tr>
                </table>
                </div>
                <div>
                   <?php 
				   include('model/Barcode39.php');
			    //include "Barcode39.php";
				// set Barcode39 object
				$bc = new Barcode39("Shay Anderson");
				// display new barcode
				$bc->draw();
				?>
                </div><?php */?>
              
                </div>
            </section>
            
    		</div> 
               
            
            <div class="form-group">
				<div class="col-lg-9 col-lg-offset-3">
                 <?php if($proforma['proforma_status'] == '1') { //echo "kinjal";?>
               	  <button class="btn btn-primary" name="save_button" type="submit">Save </button>
				<?php } ?>
				<a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'].$add_url,'',1);?>">Cancel</a>
    			</div>
			</div>
            
              	  </form>
		</div>
    </section>    
</section>
<!-- Modal -->

<div class="modal fade" id="smail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="sscurrency" id="sscurrency" value="" />
                <input type="hidden" name="sscurrencyrate" id="sscurrencyrate" value="" />
                <h4 class="modal-title" id="myModalLabel">Send Email</h4>
              </div>
              <div class="modal-body" style="height:65px;">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Email</label>
                        <div class="col-lg-8">
                             <input type="text" name="smail" placeholder="Email" value="" class="form-control validate[required,custom[email]]">
                        </div>
                     </div> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" name="btn_sendemail" class="btn btn-primary btn-sm">Send</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<div class="modal fade" id="approve" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:30%;height:40%;">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="appform" id="appform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="proforma_id" id="proforma_id" value="" />
                <input type="hidden" name="val" id="val" value="" />
                <h4 class="modal-title" id="myModalLabel">Approve Proforma Invoice</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-4 control-label" id="date"></label>
                        <div class="col-lg-8">
                            <input type="text" name="app_dis_date" id="app_dis_date" value="<?php echo date("Y-m-d");?>"  data-format="YYYY-MM-DD"  data-template="D MMM YYYY" 
                         placeholder="Date"  class="combodate form-control"/>
                        </div>
                     </div> 
              </div>
               <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-4 control-label">Description</label>
                        <div class="col-lg-8">
                        <textarea name="description" id="description" class="form-control validate"></textarea>
                        </div>
                     </div> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" name="btn_approve" id="btn_approve" onclick="approveordisapprove(1)" class="btn btn-primary btn-sm">Approve</button>
                <button type="button" name="btn_disapprove" id="btn_disapprove" onclick="approveordisapprove(2)" class="btn btn-primary btn-sm">Disapprove</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<style>
@media print{
  body{ background-color:#FFFFFF; background-image:none; color:#000000 }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
}

.col-lg-3 {
width: 15%;
}
#client {
    border-left: 6px solid #0087c3;
    float: left;
    padding-left: 6px;
}
h1 {
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
article, article address, table.meta, table.inventory { margin: 0 0 3em; }
table.meta, table.balance { float: right; width: 50%; }
table.meta:after, table.balance:after { clear: both; content: ""; display: table; }

/* table meta */

table.meta th { width: 40%; }
table.meta td { width: 60%; }

/* table items */

table { font-size: 75%; table-layout: fixed; width: 100%; }
table { border-collapse: separate; border-spacing: 2px; }
th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }
th, td { border-radius: 0.25em; border-style: solid; }
th { background: #EEE; border-color: #BBB; }
td { border-color: #DDD; }
</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
 jQuery(document).ready(function(){
        
        jQuery("#sform").validationEngine();
		$(".sendmailcls").click(function(){
			$(".note-error").remove();
			$("#smail").modal('show');			
			return false;
		});	
		$(".pdfcls").click(function(){			
			
				$(".note-error").remove();
				var url = '<?php echo HTTP_SERVER.'pdf/proformapdf_file.php?mod='.encode('proformainvoice_file').'&token='.rawurlencode($_GET['proforma_id']).'&ext='.md5('php');?>';				
				window.open(url, '_blank');
			return false;
		});		
	});
function wordlink(id){
		var url = '<?php echo HTTP_SERVER.'word/proformainvoice.php?mod='.encode('proformainvoice').'&ext='.md5('php');?>&token='+id;
		window.open(url, '_blank');
	return false;
}
function test() {
	 var html="<html>";
	
html+='<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/  media="print"><link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/font.css"><link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/app.v2.css" type="text/css" /><link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/custom.css">';
    html+= $('#print_div').html();
    html+="<style>.col-lg-3 {width: 15%;}#client {    border-left: 6px solid #0087c3;    float: left;    padding-left: 6px;}h1 {	background:#333;    border-bottom: 1px solid #5d6975;    border-top: 1px solid #5d6975;    color: #FFF;    font-size: 2.4em;    font-weight: normal;    line-height: 1.4em;    margin: 0 0 20px;    text-align: center;}article, article address, table.meta, table.inventory { margin: 0 0 3em; }table.meta, table.balance { float: right; width: 50%; }table.meta:after, table.balance:after { clear: both; display: table; }table.meta th { width: 40%; }table.meta td { width: 60%; }table { font-size: 75%; table-layout: fixed; width: 100%; }table { border-collapse: separate; border-spacing: 2px; }th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }th, td { border-radius: 0.25em; border-style: solid; }th { background: #EEE; border-color: #BBB; }td { border-color: #DDD; }</style></html>";	//alert(html);
    var printWin = window.open('','','');
    printWin.document.write(html);
    printWin.document.close();
    printWin.focus();
    printWin.print();
    printWin.close();
}
function approve(value,proforma_id)
{
	//alert(proforma_id);
	$("#approve").modal('show');
	$("#proforma_id").val(proforma_id);
	$("#val").val(value);
	if(value=='1')
	{
		$("#btn_disapprove").hide();
		$("#btn_approve").show();
		$("#date").text("Approve Date");
	}
	else
	{
		$("#btn_approve").hide();
		$("#btn_disapprove").show();
		$("#date").text("Disapprove Date");
	}
}
function approveordisapprove()
{
	var formData = $("#appform").serialize();
	var app_dis_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=approveordis', '',1);?>");
	$.ajax({
		url : app_dis_url,
		method : 'post',
		data : {formData : formData},
		success: function(response){
		//alert(response);
			set_alert_message('Successfully Added',"alert-success","fa-check");
			window.setTimeout(function(){location.reload()},100)
		},
		error: function(){
			return false;	
		}
		});

}
</script>	
<!-- Close : validation script -->
