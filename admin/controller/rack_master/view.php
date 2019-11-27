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
	'href' 	=> $obj_general->link($rout, '', '',1),
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

//Start : edit
$edit = '';
if(isset($_GET['goods_master_id']) && !empty($_GET['goods_master_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
	    $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	

		$addedByInfo = $obj_goods_master->getUser($user_id,$user_type_id);
	    
		$goods_master_id = base64_decode($_GET['goods_master_id']);
		$goods_data = $obj_goods_master->getGoodsData($goods_master_id);
		//$stock_data = $obj_rack_master->getrackdetail($goods_master_id);
		//printr($goods_data);
	//	printr($addedByInfo);
		//die;
	}
}
//Close : edit
if($display_status){
	
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
		<div class="col-sm-12">
        
        <section class="panel">  
 
        	<header class="panel-heading bg-white"><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        		<span>Rack Detail</span> 
                
               <!-- <span style="float:right;width:35%; border:medium;"><marquee behavior="alternate" width="60%" style="cursor: pointer;" onclick='show_oldstock()'><a ><img src="<?php echo HTTP_SERVER.'images/notification.jpg';?>"><b> Notification Of Oldest Stock </b></img></a></marquee></span>-->
              <span style="float:right;width:35%; border:medium;">
                   <div class="button_base b05_3d_roll" onclick="show_oldstock()">
                        <div><b> Notification Of Oldest Stock </b></div>
                        <div><b> Notification Of Oldest Stock </b></div>
                    </div>
			 </span>
                
               <!-- <span style="float:right;width:35%; border:medium; margin-right:5%"><marquee behavior="alternate" style="cursor: pointer;" width="60%"  onclick='list_old_new()'><a ><img src="<?php echo HTTP_SERVER.'images/notification.jpg';?>"><b> Listing Of New To Oldest Stock </b></img></a></marquee></span>-->
                <span style="float:right;width:35%; border:medium; margin-right:5%">
                    <div class="button_base b05_3d_roll" onclick="list_old_new()">
                        <div><b> Listing Of New To Oldest Stock </b></div>
                        <div><b> Listing Of New To Oldest Stock </b></div>
                    </div>
              </span>
        		</header>
        	<?php if($goods_data) { ?>
        	<div class="panel-body">
        		<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
        		

        		<div class="form-group">
        			<div class="col-lg-10">
                    <input type="hidden" id="rack_name" value="<?php echo $goods_data['name']; ?>" />
                    <input type="hidden" id="rack_id" value="<?php echo $goods_data['goods_master_id']; ?>" />
                  

                  <div id="drop-area" class="drag-active" style=" overflow: auto; height:500px">
			<div> 
            
                  <?php
				  		if(isset($goods_data['row']))
                        {
							//get number of rows inputted through text box
							$row=$goods_data['row'];
							//printr($row);
							//get number of columns inputted through text box
							$col=$goods_data['column_name'];
							//printr($col);
							//create a table in php 
							echo '<div align="left" style=" width:100%"><div id="wrap" align="center"  style="width:100%" >';
							$rack_quantity=	$goods_data['capacity'];
							$c=1;
							for($i=1;$i<=$row;$i++)
							{
								echo '<ul  style="width:100%">';
															
								for($r=1;$r<=$col;$r++) {
									//echo $i.' '.$r;
										$arr=$obj_rack_master->getstock($goods_data['goods_master_id'],$i,$r);
										//printr($arr);
										//die;
										$store_qty=0;
										if($arr!='')
										{
											$username='';
											$volume='';
											foreach($arr as $stock)
											{
												$size_name=$obj_rack_master->getProductCode($stock['product_code_id']);
												//printr($size_name);
												$available_volume=isset($size_name['volume']) ? $size_name['volume'] :'';
												//$a = '70 gm';
												//$volume = $size_name['volume'].',';
												//$available_volume=substr($volume,0,-3);
												//echo $store_qty;
												//printr($stock['qty']);
												if($stock['dispatch_qty']==0)
												{
													$store_qty=$store_qty+$stock['qty'];											
												}
												$stock_user = $obj_rack_master->getUser($stock['user_id'],$stock['user_type_id']);
												$username.='<div><span style="color:#26B756">'.$stock_user['user_name'].'</span>  On  <span>'.dateFormat(4,$stock['date_added']).'</span><span style="color:#00F">'.$stock['qty'].'</span> &nbsp;</div><br>';
												//$desc=$stock['description'];
												//printr($stock['description']);
										
											}
										}
									//echo $username;
									//printr($desc);
									//die;										//$desc=$arr['desc'];
									//printr($desc);
										if(!isset($arr[0]['description']) || empty($arr[0]['description']))
										{	$desc=0;}
										else
											$desc=$arr[0]['description'];
										/*$store_qty= $arr['qty'];*/
										$available_store=$rack_quantity-$store_qty;
										//printr($desc);
										if(!isset($store_qty) || empty($store_qty))
											$store_qty=0;
										echo '<input type="hidden" id="desc-'.$i.'-'.$r.'" value="'.$desc.'" >';
										echo '<input type="hidden" id="store-'.$i.'-'.$r.'" value="'.$available_store.'" >';
										echo '<input type="hidden" id="store-qty-'.$i.'-'.$r.'" value="'.$store_qty.'" >';
										
										echo '<li class="selected_item " ondrop="drop(event)" ondragover="allowDrop(event)" id="'.$i.'-'.$r.'"  style="width:10%;  height:200px;border: solid #F5F6F1 1px;">';
										// mansi 22-1-2016
										
										if($addedByInfo['country_id']==42){
										    $rack_label=array();
										    	$rowcol = ''.$i.'@'.$r.'';
										$label =$obj_rack_master->getRackLabelCanada($rowcol,$goods_master_id);
											$rack_label=array('rack_label'=>$label);
                                          //  printr($store_qty);
                                      //    printr($rack_label['rack_label']);

										}else{
										$rack_label =$obj_rack_master->getRackLabel($goods_master_id,$i,$r);
										}
									//	printr($rack_label);
										if($store_qty==$rack_quantity){ 
										  // 	printr($rack_label);
										?>
										
										                                        
                                        <br clear="all">
                                          <?php echo ''.$c.'' ?>
                                        <br clear="all">
                                        <br clear="all">
                                        <div style=" color: red;width: 100%;height:100%;" class="grid__item" >
                                        <a  href="<?php echo $obj_general->link($rout, 'mod=rack_detail&data='.$i.'-'.$r.'&goods_id='.encode($goods_data['goods_master_id']), '',1); ?>" style="color:red"  /><br />
                                        <b >Full</b></a><br />
                                        <!--<div style="width: 100%;height:100%" class="grid__item" >-->
                                        <input type="text" id="rack_label_<?php echo $i;?>_<?php echo $r;?>" name="rack_label" class="form-control "  onblur="edit_value(<?php echo $goods_data['goods_master_id'];?>,<?php echo $i;?>,<?php echo $r;?>)" value="<?php echo isset($rack_label['rack_label']) ? $rack_label['rack_label'] : ''; ?>" >
										</div><!--</div>-->
                                        
										<?php
										}
										elseif($store_qty==0){
										    
										?>
									<br clear="all">
                                     <?php echo ''.$c ;  ?>
                                    <br clear="all">
                                    
                                    <br clear="all">
                                   <div style="width: 100%;height:100%" class="grid__item" >
                                   
                                   <b>Empty <?php //echo  $rack_label['rack_label'];?></b>
                                   
                                    <input type="text" id="rack_label_<?php echo $i;?>_<?php echo $r;?>" name="rack_label" style="margin:16px auto;" onblur="edit_value(<?php echo $goods_data['goods_master_id'];?>,<?php echo $i;?>,<?php echo $r;?>)" class="form-control" value="<?php echo isset($rack_label['rack_label']) ? $rack_label['rack_label'] : ''; ?>"  >
									</div>
									<?php 
											}
									else
									{
										?>
										<br clear="all">
                                        <?php echo ''.$c ;  ?>

                                        <br clear="all">
                                        <br clear="all">
                                        
                                        <div style="width: 100%;height:100%;color:green;" class="grid__item" TITLE="<?php echo 'Available Volume :'.$available_volume;?>">
                                    	<a href="<?php echo $obj_general->link($rout, 'mod=rack_detail&data='.$i.'-'.$r.'&goods_id='.encode($goods_data['goods_master_id']), '',1); ?>" style="color:green;" /><b><?php echo 'Available';?></b></a>
                                        <br />
                                        <div style="width: 100%;height:100%" class="grid__item" >
                                        <input type="text" id="rack_label_<?php echo $i;?>_<?php echo $r;?>" name="rack_label" class="form-control " onblur="edit_value(<?php echo $goods_data['goods_master_id'];?>,<?php echo $i;?>,<?php echo $r;?>)" value="<?php echo isset($rack_label['rack_label']) ? $rack_label['rack_label'] : '';?>">
                                        </div>
                                       </div>
                                        
                                        									
								<?php 
											}
									echo '</li>';
									$c++;
								}
								echo '<br clear="all"></ul>';
								
							}
							echo '<br clear="all">
							 
                							
	</div>
</div>';	
                        } ?>
                        </div>
                         
                        
		</div>
		<div class="drop-overlay"></div>
                    </div>       

                    <div id="grid" class="grid clearfix" style="  width: 20%;  height: 500px;  float: right; overflow: auto; height:500px;display:none;">
                    
                    <?php
					
						$img_data = $obj_rack_master->getImageurl();	
						//printr($img_data);
						//die;
						$i=0;
						if($img_data!='')
						{
						foreach($img_data as $img){
								//printr($zipper_name);
							?>
                            <div style="padding-bottom: 10px;">
							<div class="grid__item">
                            	<img id="<?php echo $img['product_id'];?>" class="merchandiser_image" src="<?php echo $img['product_image_url']; ?>"  draggable="true" 
                                ondragstart="drag(event)"/> 
                             <div style="  width: 100px;  background: gainsboro;" class="grid__action"><?php echo $img['product_name'];?>
                            </div>                           
                           </div>
                           </div>
							<?php
							$i++;
							}
						}
						else
						{
							echo "No Products Available";
						}
						
					?>
                   
				</div> 
	        </div>
        
        
    	    <div class="form-group">
        		<div class="col-lg-9 col-lg-offset-3">                
			        <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
		        </div>
	        </div>
        </form>
    </div>
    
    <?php } else { ?>
    <div class="text-center">No Data Available</div>
    <?php } ?>
    
    </section>    
  </div>
</div>
</section>
</section>
<div class="modal fade" id="product_list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:55%;">
    <div class="modal-content">
    
    	<form class="form-horizontal" method="post" name="credit_note" id="credit_note" style="margin-bottom:0px;">
              <div class="modal-header" style="background-color: #d0e1e1"> 
                   	<h4 class="dispatch" id="myModalLabel"><span id="span_inv_no"><b></b></span></h4>
              </div>
              
               <div class="modal-body">
                    <div class="form-group pro_data">
                    	
                    </div>
                    <div class="form-group new_old_data">
                    	
                    </div>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" style="background-color: #d0e1e1">Close</button>
              </div>
   		</form>   
    </div>
  </div>
</div>
<!-- modal -->
<!--added by priya-->
<div class="modal fade" id="stock_mang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Stock Management</h4>
              </div>
           
              <div class="modal-body">
                   <div class="form-group">
                		<label class="col-lg-3 control-label"  style="width:15%"  >Proforma No</label>
                        <div class="col-lg-3" style="width:120px">
                            <input type="text" name="proforma_no" id="proforma_no" class="form-control validate"   style="width: 100px;">
                        </div>
                        
                        <label class="col-lg-3 control-label" style="width:15%"  >Purchase Invoice No</label>
                        <div class="col-lg-3" style="width:120px">
                            <input type="text" name="invoice_no" id="invoice_no" class="form-control validate"  style="width: 100px;">
                        </div>
                        
                        <label class="col-lg-3 control-label" style="width:15%"  >Customer Order No</label>
                        <div class="col-lg-3" style="width:120px">
                            <input type="text" name="orderno" id="orderno" class="form-control validate"  style="width: 100px;">
                        </div>
                        
                        <label class="col-lg-3 control-label" style="width:15%"  >My Order No</label>
                        <div class="col-lg-3" style="width:120px">
                            <input type="text" name="my_orderno" id="my_orderno" class="form-control validate"  style="width: 100px;">
                        </div>
             		</div>                    
                    
              </div>
           
              <div class="modal-body">
                   <div class="form-group">
                    <label class="col-lg-3 control-label" style="width:15%"  > From Company Name</label>
                        <div class="col-lg-3" style="width:20%">
                            <input type="text" name="company_name" id="company_name" class="form-control validate">
                        </div>
                        
                         <label class="col-lg-3 control-label" style="width:5%;padding-left: 0px;"  > Date </label>
                        <div class="col-lg-3" style="width:30%">
               			 <input type="text" name="date" id="date" value="<?php echo date("Y-m-d");?>"  data-format="YYYY-MM-DD"  data-template="D MMM YYYY" 
                         placeholder="Date"  class="combodate form-control"/>
                		</div>
                        
                         <label class="col-lg-3 control-label" style="width:10%;padding-left: 0px;"><span class="required">*</span>Description</label>
                     <div class="col-lg-3" style="width: 17%;">
                     <select name="description" id="description" class="form-control validate[required]" >
                      <option value="">Select</option>
                      <option value="1" id="sto">Store</option>
                      <option value="3" id="good">Goods Returned</option>		         
                    </select>
                    </div>
                    
             		</div>
              </div>
             
                       
                 <div class="modal-body">
              		<div class="form-group option">
                       <label class="col-lg-3 control-label" style="width:15%"><span class="required">*</span>Product Code</label>
                            <div class="col-lg-3" id="holder">
                                <?php $product_codes=$obj_rack_master->getActiveProductCode(); ?>
                                       

                                         <input type="hidden" id="product_code_id" name="product_code_id" value="<?php //if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] != '-1')){ echo $product_code['product_code_id'];} else if(isset($invoice_product) && $invoice_product['product_code_id'] == '-1') { echo '-1'; } else { } ?>">
                                   <input type="text" id="keyword" class="form-control validate[required]"  autocomplete="off" value="<?php  //if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] != '-1')){ echo $product_code['product_code'];} else if( isset($invoice_product) && $invoice_product['product_code_id'] == '-1') { echo 'Custom'; } else { } ?>">
                                   <div id="ajax_response"></div>
                                </div>
                                   
                                   <label class="col-lg-3 control-label" style="width:5%;padding-left: 0px;"  > </label>
                                    <div class="col-lg-3" id="product_div"  style="width:30%"> 
                               <input type="text" name="product_name" id="product_name"  value="<?php //echo isset($_GET['invoice_product_id'])?$product_code['description']:'';?>" disabled="disabled" class="form-control validate" style="width:400px"/>
             	 	 				</div>
                             </div>
                           
                   		<div class="modal-body">
                   		   <div class="form-group">
                            <label class="col-lg-3 control-label" style="width:15%"><span class="required">*</span>Quantity</label>
                            <div class="col-lg-3"  style="width:10%">
                                <input type="text" name="qty" id="qty" placeholder="Qty" class="form-control validate[required],custom[number]">
                            </div>      
                                <input type="hidden" name="product" id="product" class="form-control"/>
                                <input type="hidden" name="product_id" id="product_id" class="form-control"/>
                                <input type="hidden" name="name" id="name" class="form-control"/>
                                <input type="hidden" name="goods_id" id="goods_id" class="form-control"/>
                                <input type="hidden" name="row1" id="row1" class="form-control"/>
                                <input type="hidden" name="col1" id="col1" class="form-control"/>
                            </div>                    
                </div>
          	</div>
            <!--	<center><div id="capacity"></div></center>-->
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="addstock()" name="btn_submit1" class="btn btn-warning">save</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>
		<link rel="stylesheet" type="text/css" href="<?php echo HTTP_SERVER;?>admin/controller/rack_master/css/normalize.css" />
	<!--	<link rel="stylesheet" type="text/css" href="<?php //echo HTTP_SERVER;?>admin/controller/rack_master/css/demo.css" />
		<link rel="stylesheet" type="text/css" href="<?php //echo HTTP_SERVER;?>admin/controller/rack_master/css/component.css" />-->
		<script src="<?php echo HTTP_SERVER;?>admin/controller/rack_master/js/modernizr.custom.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo HTTP_SERVER;?>admin/controller/rack_master/css/sidebar.css" />
        <link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
        <style>
#wrap{ width:100%;top:0px; position:relative; bottom:0px; }
#wrap ul{ margin:0px; padding:0px; width: 700px;text-align:center;  }
 
#wrap .detail-view {
    border: 1px solid #E2E2E2;
    border-top: 1px solid #E2E2E2;
    left: 0;
    height:380px;
    overflow: hidden;
    clear:both;
    display:none;
    margin-left:13px;
    margin-bottom:15px;
    width: 96%;
}
 
#wrap .detail-view .close{ text-align:right; width:98%; margin:5px; }
#wrap .close a{ padding:6px; height:10px; width:20px; color:#525049; }
#wrap .detail-view .detail_images{ float:left}
 
#wrap .detail-view .detail_info{
    float:right;
    font-family: "Helvetica Neue",Helvetica,"Nimbus Sans L",Arial,sans-serif;
    color:#525049;
    margin-right:20px;
    margin-top:30px;
    text-align:justify;
    width:250px;
    font-size:12px;
}

#wrap .detail-view .detail_info label{ font-size:12px;text-transform:uppercase; letter-spacing:1px; line-height:60px;} 
 
#wrap .detail-view .detail_info p{ height:110px;}
 

#wrap ul li{
 
    list-style-type:none;
    height:146px;
    width:160px;
    margin-left:10px;
    margin-bottom:15px;
    float:left;
    padding:15px 0px 0px 0px;
    font-family:"LubalGraphBdBTBold",Tahoma;
    font-size:2em;
    border:solid #fff 1px;
    overflow:hidden;
}
 
 
#wrap ul li:hover{ border:solid #f3f4ee 1px; }
 
#wrap ul li div{ 
 
    height:31px;
    text-align:center;
    width:160px;
    margin-top:10px;
    position:relative;
    bottom:0px;
    padding-top:6px;
    padding-bottom:4px;
    background:#f3f4ee ;
    font: 12px/21px "Helvetica Neue",Helvetica,"Nimbus Sans L",Arial,sans-serif;
    opacity:0.8;
    color: #525049 ;
    text-shadow: 0px 2px 3px #555;
}
 
#wrap ul li { cursor:pointer;}
#ajax_response{
	border : 1px solid #13c4a5;
	background : #FFFFFF;
	position:relative;
	display:none;
	padding:2px 2px;
	top:auto;
	border-radius: 4px;
}
#holder{
	width : 350px;
}
.list {
	padding:0px 0px;
	margin:0px;
	list-style : none;
}
.list li a{
	text-align : left;
	padding:2px;
	cursor:pointer;
	display:block;
	text-decoration : none;
	color:#000000;
}
.selected{
	background : #13c4a5;
}
.bold{
	font-weight:bold;
	color: #227442;
}
.about{
	text-align:right;
	font-size:10px;
	margin : 10px 4px;
}
.about a{
	color:#BCBCBC;
	text-decoration : none;
}
.about a:hover{
	color:#575757;
	cursor : default;
}

#icon .fa fa-comment {
	
	width: 124px !important;
    height: 124px !important;
}
@import url('http://fonts.googleapis.com/css?family=Roboto+Condensed');

.preserve-3d {
    transform-style: preserve-3d;
    -webkit-transform-style: preserve-3d;
}

body {
    padding: 0;
    margin: 0;
    border: 0;
    overflow-x: none;
    background-color: #ffffff;
    font-family: Roboto Condensed, sans-serif;
    font-size: 12px;
    font-smooth: always;
    -webkit-font-smoothing: antialiased;
}


.button_base {
    margin: 0;
    border: 0;
    font-size: 18px;
    position: relative;
    top: 50%;
    left: 50%;
    margin-top: -25px;
    margin-left: -100px;
    width: 320px;
    height: 30px;
    text-align: center;
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-user-select: none;
    cursor: default;
}

.button_base:hover {
    cursor: pointer;
}

/* ### ### ### 05 */
.b05_3d_roll {
    perspective: 500px;
    -webkit-perspective: 500px;
    -moz-perspective: 500px;
}

.b05_3d_roll div {
    position: absolute;
    text-align: center;
    width: 100%;
    height: 50px;
    padding: 10px;
    border: #000000 solid 1px;
    pointer-events: none;
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
}

.b05_3d_roll div:nth-child(1) {
    color: #000000;
    background-color: #ffffff;
    transform: rotateX(90deg);
    -webkit-transform: rotateX(90deg);
    -moz-transform: rotateX(90deg);
    transition: all 0.2s ease;
    -webkit-transition: all 0.2s ease;
    -moz-transition: all 0.2s ease;
    transform-origin: 50% 50% -25px;
    -webkit-transform-origin: 50% 50% -25px;
    -moz-transform-origin: 50% 50% -25px;
}

.b05_3d_roll div:nth-child(2) {
    color: #ffffff;
    background-color:  #000000;
    transform: rotateX(0deg);
    -webkit-transform: rotateX(0deg);
    -moz-transform: rotateX(0deg);
    transition: all 0.2s ease;
    -webkit-transition: all 0.2s ease;
    -moz-transition: all 0.2s ease;
    transform-origin: 50% 50% -25px;
    -webkit-transform-origin: 50% 50% -25px;
    -moz-transform-origin: 50% 50% -25px;
}

.b05_3d_roll:hover div:nth-child(1) {
    color: #000000;
    transition: all 0.2s ease;
    -webkit-transition: all 0.2s ease;
    -moz-transition: all 0.2s ease;
    transform: rotateX(0deg);
    -webkit-transform: rotateX(0deg);
    -moz-transform: rotateX(0deg);
}

.b05_3d_roll:hover div:nth-child(2) {
    background-color: #000000;
    transition: all 0.2s ease;
    -webkit-transition: all 0.2s ease;
    -moz-transition: all 0.2s ease;
    transform: rotateX(-90deg);
    -webkit-transform: rotateX(-90deg);
    -moz-transform: rotateX(-90deg);
}
</style>
		<script src="<?php echo HTTP_SERVER;?>admin/controller/rack_master/js/modernizr.custom.js"></script>
        <script src="<?php echo HTTP_SERVER;?>admin/controller/rack_master/js/draggabilly.pkgd.min.js"></script>
		<script src="<?php echo HTTP_SERVER;?>admin/controller/rack_master/js/dragdrop.js"></script>
        <script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
 <!-- combodate --> <script src="<?php echo HTTP_SERVER;?>js/combodate/moment.min.js"></script> 
 <script src="<?php echo HTTP_SERVER;?>js/combodate/combodate.js"></script>
		<script>
		//added by priya 
	
 jQuery(document).ready(function(){	
	jQuery("#sform").validationEngine();
	
	$(document).click(function(){
			$("#ajax_response").fadeOut('slow');
			$("#ajax_response").html("");
		});
	   	$("#keyword").focus();
		var offset = $("#keyword").offset();
		var width = $("#holder").width();
		$("#ajax_response").css("width",width);
		
		$("#keyword").keyup(function(event){
		 var keyword = $("#keyword").val();
		 //alert(keyword);
		 
		 if(keyword.length)
		 {	
		 	$("#color_txt").hide();
			$("#product_name").show();
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {		
				 var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=product_code', '',1);?>");
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: product_code_url,
				   data: "product_code="+keyword,
				   success: function(msg){	
					//alert(msg);
				   var msg = $.parseJSON(msg);
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{
						for(var i=0;i<msg.length;i++)
						{	
							div =div+'<li><a href=\'javascript:void(0);\' discr="'+msg[i].description+'" color="'+msg[i].color+'" size="'+msg[i].volume+'" mea="'+msg[i].measurement+'" id="'+msg[i].product_code_id+'"><span class="bold" >'+msg[i].product_code+'</span></a></li>';			
							
							//$("#color_product").val(msg[i].color);
							//$("#size").val(msg[i].volume);
							//$("#measurement").val(msg[i].measurement);
						}
					}
					
					div=div+'</ul>';
					if(msg != 0)
					  $("#ajax_response").fadeIn("slow").html(div);
					else
					{
					  $("#ajax_response").fadeIn("slow");	
					  $("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
					}
					$("#loading").css("visibility","hidden");
				   }
				 });
			 }
			 else
			 {				
				switch (event.keyCode)
				{
				 case 40:
				 {
					  found = 0;
					  $(".list li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $(".list li[class='selected']");
						sel.next().addClass("selected");
						sel.removeClass("selected");										
					  }
					  else
						$(".list li:first").addClass("selected");
						if($(".list li[class='selected'] a").text()!='')
						{
							$("#keyword").val($(".list li[class='selected'] a").text());
							$("#product_div").show();
                  			$("#product_name").val($(".list li[class='selected'] a").attr("discr"));
							$("#product_code_id").val($(".list li[class='selected'] a").attr("id"));
							//$("#color_product").val($(".list li[class='selected'] a").attr("color"));
							//$("#size").val($(".list li[class='selected'] a").attr("size"));
							//$("#measurement").val($(".list li[class='selected'] a").attr("mea"));
						}
				}
				 break;
				 case 38:
				 {
					  found = 0;
					  $(".list li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $(".list li[class='selected']");
						sel.prev().addClass("selected");
						sel.removeClass("selected");
					  }
					  else
						$(".list li:last").addClass("selected");
						if($(".list li[class='selected'] a").text()!='')
						{
							$("#keyword").val($(".list li[class='selected'] a").text());
							$("#product_div").show();
                  			$("#product_name").val($(".list li[class='selected'] a").attr("discr"));
							$("#product_code_id").val($(".list li[class='selected'] a").attr("id"));
							//$("#color_product").val($(".list li[class='selected'] a").attr("color"));
							//$("#size").val($(".list li[class='selected'] a").attr("size"));
							//$("#measurement").val($(".list li[class='selected'] a").attr("mea"));
						}
				 }
				 break;				 
				}
			 }
		 }
		 else
		 {	
			$("#ajax_response").fadeOut('slow');
			$("#ajax_response").html("");

		 }
	});
	$('#keyword').keydown( function(e) {
    if (e.keyCode == 9) {
		 $("#ajax_response").fadeOut('slow');
		 $("#ajax_response").html("");
    }
	
});
	$("#ajax_response").mouseover(function(){
			$(this).find(".list li a:first-child").mouseover(function () {
					$("#product_div").show();
                  $("#product_name").val($(this).attr("discr"));
				//  $("#color_product").val($(this).attr("color"));
				  //$("#size").val($(this).attr("size"));
				 // $("#measurement").val($(this).attr("mea"));
				   $("#product_code_id").val($(this).attr("id"));
				  $(this).addClass("selected");
			});
			$(this).find(".list li a:first-child").mouseout(function () {
				  $(this).removeClass("selected");
			});
			$(this).find(".list li a:first-child").click(function () {
				  $("#product_div").show();
                  $("#product_name").val($(this).attr("discr"));
				  //$("#color_product").val($(this).attr("color"));
				 // $("#size").val($(this).attr("size"));
				 // $("#measurement").val($(this).attr("mea"));
				  $("#product_code_id").val($(this).attr("id"));
				  $("#keyword").val($(this).text());
				  $("#ajax_response").fadeOut('slow');
				  $("#ajax_response").html("");
				});
			
		});
	
});
$('#qty').change(function(){
	var qty=parseInt($('#qty').val());
	//alert(qty);
	var row=$("#row1").val();
	//alert(row);
	var col=$("#col1").val();
	var dec=$('#description').val();
//	alert(dec);
	//if(dec==''){alert('Please select description');}
	var dis_qty=$('#store-qty-'+row+'-'+col).val();
	var store=$('#store-'+row+'-'+col).val();
	//alert(store);
	
	if(dec == 1 && store < qty){
		alert('Available Space in Rack is '+store);
		$('#qty').val('');
		}
	 if(dec == 2 && dis_qty < qty){
			alert('Available dispatch in Rack is '+dis_qty);
			$('#qty').val('');
		}
	//alert(d_qty+' '+o_qty+' '+d_last_qty);
});
		
			function allowDrop(ev) {
				ev.preventDefault();
			}
			
			function drag(ev) {
				//debugger;
				//alert(ev.target.id);
				ev.dataTransfer.setData("text", ev.target.id);
			}
			
			function drop(ev) {
				//alert(ev.target.id);
				//$('#sform').reset();
				//stock_product=$("#stock_product").val();
				ev.preventDefault();
				var product_id = ev.dataTransfer.getData("text");
				//alert(product_id);
				
				$('#description').val('');
				$('#orderno').val('');
				$('#qty').val('');
				
				var row_col=ev.target.id;
				var p_name=$('#'+product_id).parent().children().eq(1).text();
				//alert(p_name);
				
				$('#product').val(p_name);
				$('#product_id').val(product_id);
				var rack_name=$('#rack_name').val();
				$('#name').val(rack_name);
				var rack_id=$('#rack_id').val();
				$('#goods_id').val(rack_id);
				$('#myModalLabel').html('Stock Management : '+p_name+' - '+rack_name);
				var arr=row_col.split('-');
				$('#row1').val(arr[0]);
				$('#col1').val(arr[1]);
				
				//console.log(arr.length);
				
				/*if(row_col.contains("rack_label_")){
					alert("String Found");
				}*/
				
				if(arr== '' || arr.length == '1'){alert("select rack Properly");
				return false;
				}
				//alert($('#stock_product_'+row_col).val());
			
				var store_qty=$('#store-qty-'+row_col).val();
				var store=$('#store-'+row_col).val();
				var display = "Available Rack Capacity is  "+store;
				$("#capacity").html(display);
				var desc=$('#desc-'+row_col).val();
				//alert(desc);
				if(store<=0){
					$("#sto").css("display", "none");
					}
					if(desc==1){						
						$("#good").css("display", "none");
							$("#sto").css("display", "inline");
						}
					
					else if(desc==3){
						$("#sto").css("display", "none");
						$("#good").css("display", "inline");	}
					/*var stock_arr = jQuery.parseJSON($('#stock_product_'+row_col).val());
				if(stock_arr!=0)
				{
					if(jQuery.inArray(product_id,stock_arr)>=0)
					{
						$("#stock_mang").modal("show");
					}
					else
					{
						alert("This Product is not there in the Rack");
						$("#stock_mang").modal("hide");
					}
				}
				else
				{
					$("#stock_mang").modal("show");
				}*/
					$('#stock_mang').modal({backdrop:'static'});
				$("#stock_mang").modal("show");
				
			}
		
		
	function addstock()
	{
			var postArray = {};
			postArray['orderno'] = $("#orderno").val();
			postArray['description'] = $("#description").val();
			//alert(postArray['qty']);
			/*var d = new Date();
			var curr_date = d.getDate();
    		var curr_month = d.getMonth();
   			 curr_month++;   // need to add 1 – as it’s zero based !
    		var curr_year = d.getFullYear();
    		var formattedDate = curr_date + "-" + curr_month + "-" + curr_year;
			postArray['currdate'] = formattedDate;*/
			var formData = $("#sform").serialize();
			if(postArray['orderno'] != '' && postArray['description']!='')
			{
				var order_status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addstock', '',1);?>");
				$.ajax({
					url : order_status_url,
					method : 'post',
					data : {formData : formData},
					success: function(response){
					//$('#sform').reset();
					//console.log(response);
					set_alert_message('Successfully Added',"alert-success","fa-check");
					window.setTimeout(function(){location.reload()},100)
					},
					error: function(){
						return false;	
					}
				});
			}
			else
			{
				alert('Please Fill Form');
			}
	}	
	
	// mansi 23-1-2016 (made function for rack label  )
function edit_value(goods_id,raw,col)
{
		////alert(goods_id);
		var rack_label = $("#rack_label_"+raw+"_"+col).val();
		var value_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=update_value', '',1);?>");
			$.ajax({
				url : value_url,
				method : 'post',
				data : {goods_id : goods_id,raw:raw,col:col,rack_label:rack_label},
				success: function(response){
					//alert(response);
					set_alert_message('Successfully Updated',"alert-success","fa-check");
					 window.setTimeout(function(){location.reload()},1000)
					},
					error: function(){
						return false;	
					}
				});
    //});
}
function show_oldstock()
{	
		$("#span_inv_no").html("Notification Of Oldest Stock");
		var goods_master_id = '<?php echo $_GET['goods_master_id'];?>';
		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=showoldstock', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {goods_master_id : goods_master_id},
			success: function(response){
				$(".new_old_data").html('');
				$(".pro_data").html(response);
			},
			error:function(){
			}	
		});
		$("#product_list").modal("show");
}
function list_old_new()
{
	$("#span_inv_no").html("Listing Of Old To New Stock");
	var goods_master_id = '<?php echo $_GET['goods_master_id'];?>';
		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=list_old_new', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {goods_master_id : goods_master_id},
			success: function(response){
				$(".pro_data").html('');
				$(".new_old_data").html(response);
			},
			error:function(){
			}	
		});
		$("#product_list").modal("show");
}
</script>
