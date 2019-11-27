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
		$goods_master_id = base64_decode($_GET['goods_master_id']);
		$goods_data = $obj_goods_master->getGoodsData($goods_master_id);
		//$stock_data = $obj_rack_master->getrackdetail($goods_master_id);
		//printr($goods_data);
		//printr($stock_data);
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
 
        	<header class="panel-heading bg-white">
        		<span>Rack Detail</span> 
        		</header>
        	<?php if($goods_data) { ?>
        	<div class="panel-body">
        		<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
        		

        		<div class="form-group">
        			<div class="col-lg-9">
                    <input type="hidden" id="rack_name" value="<?php echo $goods_data['name']; ?>" />
                    <input type="hidden" id="rack_id" value="<?php echo $goods_data['goods_master_id']; ?>" />
                  

                  <div id="drop-area" class="drag-active">
			<div> 
            
                  <?php
				  		if(isset($goods_data['row']))
                        {
							//get number of rows inputted through text box
							$row=$goods_data['row'];
							//get number of columns inputted through text box
							$col=$goods_data['column_name'];
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
												//echo $available_volume;
												if($stock['dispatch_qty']==0)
												{
													$store_qty=$store_qty+$stock['qty'];											
												}
												$stock_user = $obj_rack_master->getUser($stock['user_id'],$stock['user_type_id']);
												$username.='<div><span style="color:#26B756">'.$stock_user['user_name'].'</span>  On  <span>'.dateFormat(4,$stock['date_added']).'</span>    <span style="color:#00F">'.$stock['qty'].'</span> &nbsp;</div><br>';
												//$desc=$stock['description'];
												//printr($stock['description']);
										
											}
										}
									//echo $username;
									//printr($desc);
									//die;										//$desc=$arr['desc'];
									//printr($desc);
										if(!isset($arr[0]['description']) || empty($arr[0]['description'])){$desc=0;}
										else
											$desc=$arr[0]['description'];
										/*$store_qty= $arr['qty'];*/
										$available_store=$rack_quantity-$store_qty;
										//printr($desc);
										if(!isset($store_qty) || empty($store_qty))$store_qty=0;
										echo '<input type="hidden" id="desc-'.$i.'-'.$r.'" value="'.$desc.'" >';
										echo '<input type="hidden" id="store-'.$i.'-'.$r.'" value="'.$available_store.'" >';
										echo '<input type="hidden" id="store-qty-'.$i.'-'.$r.'" value="'.$store_qty.'" >';
										
										echo '<li class="selected_item " ondrop="drop(event)" ondragover="allowDrop(event)" id="'.$i.'-'.$r.'"  style="width:20%;  height:100px;border: solid #F5F6F1 1px;">';
										if($store_qty==$rack_quantity){ 
										?>
										
										<?php echo ''.$c ?>
                                        
                                        <br clear="all">
                                        <a  
                                        href="<?php echo $obj_general->link($rout, 'mod=rack_detail&data='.$i.'-'.$r.'&goods_id='.encode($goods_data['goods_master_id']), '',1); ?>"  />
                                        <div style="  color: red;width: 100%;height:100%;" class="grid__item " ><b>Full</b></div>
                                        </a>
										<?php
										}
										elseif($store_qty==0){
										echo ''.$c ; ?>
									<br clear="all">
                                   <div style="width: 100%;height:100%" class="grid__item" ><b>Empty</b></div>
									<?php 
											}
									else
									{
										echo ''.$c ;?>
										<br clear="all">
                                    	<a href="<?php echo $obj_general->link($rout, 'mod=rack_detail&data='.$i.'-'.$r.'&goods_id='.encode($goods_data['goods_master_id']), '',1); ?>" /><div style="width: 100%;height:100%;color:green;" class="grid__item" TITLE="<?php echo 'Available Volume :'.$available_volume;?>"><b><?php echo 'Available';?>
                                        </b></div></a>
                                        <br clear="all">
                                        <a href="<?php echo $obj_general->link($rout, 'mod=rack_detail&data='.$i.'-'.$r.'&goods_id='.encode($goods_data['goods_master_id']), '',1); ?>" /><div style="width: 100%;height:100%;color:green;" class="grid__item" TITLE="<?php echo 'Available Volume :'.$available_volume;?>"><b><?php echo 'dgdfgdfg';?>
                                        </b></div></a>
                                                                         
									
								<?php 
											}
									echo '</li>';
									echo '<br><div style=""><input type="button" name="add" id="add" value="add"></div>';
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
                    <div id="grid" class="grid clearfix" style="  width: 20%;  height: 500px;  float: right;">
                    
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
                        
                        <label class="col-lg-3 control-label" style="width:15%"  >Order No</label>
                        <div class="col-lg-3" style="width:120px">
                            <input type="text" name="orderno" id="orderno" class="form-control validate"  style="width: 100px;">
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
             
             <!--  <div class="modal-body">
                    <div class="form-group">
                     <label class="col-lg-3 control-label" style="width:16%" >Product Name</label>
                        <div class="col-lg-3">
                   </div>                       
                       <label class="col-lg-3 control-label" style="width:10%;padding-left: 0px;">Rack Name</label>
                        <div class="col-lg-3" style="width:16%">
                        </div>
                  </div>
               </div>-->
          
             <?php /*?> <div class="modal-body">
              	<div class="form-group option">
                    <label class="col-lg-3 control-label" style="width:15%">Valve</label>
                    <div class="col-lg-3" style="width:12%">
                        <div  style="float:left;width: 100px;">
                            <label  style="font-weight: normal;">
                              <input type="radio" name="valve" id="nv" value="No Valve" checked="checked"  class="valve"  checked="checked" >No Valve </label>                          </div>
                               <div  style="float:left;width: 100px;">
                                 <label style="font-weight: normal;">
                                <input type="radio" name="valve" id="wv" value="With Valve" class="valve" >With Valve </label>
                        </div> 
             	   </div>
                   
                  <label class="col-lg-3 control-label" style="width:5%;padding-left: 0px;">Zipper</label>
                    <div class="col-lg-3" style="width:16%;"><?php $zippers = $obj_rack_master->getActiveProductZippers();
                        foreach($zippers as $zipper){?>           
                         <div  style="float:left;width: 200px;">                 
                                <label  style="font-weight: normal;">
                                     <input type="radio" name="zipper" value="<?php echo $zipper['product_zipper_id']; ?>"   class="zipper"
                                     <?php if(encode($zipper['product_zipper_id'])=='Mg==')
                                     { echo 'checked="checked"';} ?> >
                                     <?php echo $zipper['zipper_name']; ?>
                                </label>         
                                </div>                  
                        <?php } ?>
                    </div>
                    
                    <?php $spouts = $obj_rack_master->getActiveProductSpout();?>
              
                     <label class="col-lg-3 control-label" style="width:5%;padding-left: 0px;">Spout</label>
                      <div class="col-lg-3" style="width: 14%;">
                      <?php $spoutsTxt = '';
					  foreach($spouts as $spout){ ?>
                       <div  style="float:left;width: 200px;">
                          <label  style="font-weight: normal;">
                              <input type="radio" name="spout" class="spout" id="spout" value="<?php echo $spout['product_spout_id']; ?>"
                              <?php if(encode($spout['product_spout_id'])=='MQ=='){ echo 'checked="checked"';}?> />
                              <?php echo $spout['spout_name'];?>
                          </label>
                          </div>
                      <?php }?>
					</div>                    
				
                       <?php $accessories = $obj_rack_master->getActiveProductAccessorie();?>
                     <label class="col-lg-3 control-label"  style="width:8%;padding-left: 0px;">Accessorie</label>
                      <div class="col-lg-3" style="width: 15%;">
                      <?php $accessorieTxt = '';
					  foreach($accessories as $accessorie){ ?>
                          <div  style="float:left;width: 200px;">
                              <label  style="font-weight: normal;">
                                  <input type="radio" name="accessorie" class="accessorie" id="accessorie" 
                                  value="<?php echo $accessorie['product_accessorie_id']; ?>"
                                  <?php if(encode($accessorie['product_accessorie_id'])=='NA=='){  echo 'checked="checked"';}?> /> <?php echo $accessorie['product_accessorie_name'];?>
                               </label>
                           </div>
                       <?php }?>
                       </div>
                       
                </div>
              </div>
          
          	<div class="modal-body">
              	<div class="form-group option">
                    	<label class="col-lg-3 control-label" style="width:15%">Make Pouch</label>
                        	<div class="col-lg-3" style="width:10%">
                                <?php $makes = $obj_rack_master->getActiveMake();
                                foreach($makes as $make){?>
								<div style="float:left;width:100px;">
                                 <input type="radio" name="make" id="make" value="<?php echo $make['make_id'];?>" <?php if($make['make_id']==1){ ?> checked="checked" <?php } ?> >
								 <?php echo $make['make_name'];?> 
                                 </div>
                             	<?php  } ?>
                            	</div>
                                
                    <label class="col-lg-3 control-label" style="width:6%;padding-left: 0px;"><span class="required">*</span>Color</label>
                     <div class="col-lg-3" style="width: 17%;">
                     <select name="color" id="color" class="form-control validate[required]" >
                      <option value="">Select</option>
                       <?php $colors = $obj_rack_master->getPouchColor();
                            foreach($colors as $color){?>
                               <option value="<?php echo $color['pouch_color_id'];?>"><?php echo $color['color'];?></option>
                        <?php }?>
                    </select>
                    </div>
                    
                      <label class="col-lg-3 control-label" style="width:8%;padding-left: 0px;"><span class="required">*</span>Volume</label>
                     <div class="col-lg-3" style="width: 17%;">
                     <select name="volume" id="volume" class="form-control validate[required]" >
                      <option value="">Select</option>
                       <?php $volumes = $obj_rack_master->getPouchVolume();
                                foreach($volumes as $volume){?>
                                  <option value="<?php echo $volume['pouch_volume_id'];?>"><?php echo $volume['volume'];?></option>
                        <?php }?>
                    </select>
                    </div><?php */?>
                 
                 
                 <div class="modal-body">
              		<div class="form-group option">
                       <label class="col-lg-3 control-label" style="width:15%"><span class="required">*</span>Product Code</label>
                            <div class="col-lg-3" id="holder">
                                <?php $product_codes=$obj_rack_master->getActiveProductCode(); 
                                        //printr($product_codes); 
                                       //if(isset($invoice_product_id)) { 
                                            //$inv_product = $obj_invoice->getInvoiceProductId($invoice_no,$invoice_product_id); 
                                            //printr($color);
                                            //$product_code= $obj_invoice->getProductCode($invoice_product['product_code_id']);
                                            //printr($product_code); 
//}?>
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
				
				if(arr== ''){alert("select rack Properly");
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
					//alert(response);
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
	</script>
