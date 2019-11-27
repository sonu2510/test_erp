<?php
// ---------------------------------------------------------------------------------------------------------------------------Mode setting for the ADD stock Starts here
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
  'href'  => $obj_general->link($rout, '', '',1),
  'icon'  => 'fa-list',
  'class' => '',
);

$bradcums[] = array(
  'text'  => $display_name.' Detail',
  'href'  => '',
  'icon'  => 'fa-edit',
  'class' => 'active',
);
//Close : bradcums

include('model/product_quotation.php');
$obj_quotation = new productQuotation;

//Start : edit
$edit = '';
if(isset($_GET['rack_master_id']) && !empty($_GET['rack_master_id'])){
  if(!$obj_general->hasPermission('edit',$menuId)){
    $display_status = false;
  }else{
    $rack_master_id = base64_decode($_GET['rack_master_id']);
    $rack_data = $obj_rack_master->getRackData($rack_master_id);
    $edit = 1;
  }
  
}else{
  if(!$obj_general->hasPermission('add',$menuId)){
    $display_status = false;
  }
}
//Close : edit
/*
if($display_status){
  //insert 
  if(isset($_POST['btn_save'])){
    $post = post($_POST);   
    //printr($post);//  die;
    $insert_id = $obj_rack_master->addstock($post,1);
    $obj_session->data['success'] = ADD;
    page_redirect($obj_general->link($rout, '&mod=india_add', '',1));
  }
  */
  
  

// ---------------------------------------------------------------------------------------------------- Mode setting for the ADD stock Ends here

// ------------------------------------------------------------------------------------------------------Mode setting for the dispatch Starts here

//include("mode_setting_dispatch.php");

$bradcums = array();
$bradcums[] = array(
  'text'  => 'Dashboard',
  'href'  => $obj_general->link('dashboard', '', '',1),
  'icon'  => 'fa-home',
  'class' => '',
);

$bradcums[] = array(
  'text'  => $display_name.' List',
  'href'  => '',
  'icon'  => 'fa-list',
  'class' => 'active',
);

if(!$obj_general->hasPermission('view',$menuId)){
  $display_status = false;
}


$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
  $limit = $_GET['limit'];  
}

$filter_data=array();
$filter_value='';

$class = 'collapse';

if(isset($_POST['btn_filter'])){
  
  $filter_edit = 1;
  $class =''; 
  if(isset($_POST['filter_row'])){
    $filter_row=$_POST['filter_row'];   
  }else{
    $filter_row='';
  }
  
  if(isset($_POST['filter_column'])){
    $filter_column=$_POST['filter_column'];   
  }else{
    $filter_column='';
  }
  
  if(isset($_POST['filter_status'])){
    $filter_status=$_POST['filter_status'];
  }else{
    $filter_status='';
  }
    
  $filter_data=array(
    'column_name' => $filter_column,
    'row' => $filter_row,
    'status' => $filter_status
  );
  
  //$obj_session->data['filter_data'] = $filter_data;
}

if(isset($_GET['order'])){
  $sort_order = $_GET['order']; 
}else{
  $sort_order = 'DESC'; 
}


if($display_status) {
    
    $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
  $user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

//active inactive delete
if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
{
  if(!$obj_general->hasPermission('edit',$menuId)){
    $display_status = false;
  } else {
    $status = 0;
    if($_POST['action'] == "active"){
      $status = 1;
    }
    $obj_goods_master->updateStatus($status,$_POST['post']);
    $obj_session->data['success'] = UPDATE;
    page_redirect($obj_general->link($rout, '', '',1));
  }
  
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
  if(!$obj_general->hasPermission('delete',$menuId)){
    $display_status = false;
  } else {
    $obj_goods_master->updateStatus(2,$_POST['post']);
    $obj_session->data['success'] = UPDATE;
    page_redirect($obj_general->link($rout, '', '',1));
  }
}
$purchase_notify_status=0;
$sales_notify_status=0;
$credit_notify_status=0;
$on_sa=$on_ch=$on_cr='';
//$purchase_notify=$obj_rack_master->getpurchasenotification($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
$purchase_notify=$obj_rack_master->getInvoice($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],$note=1);
//printr($purchase_notify);

if(isset($_POST['btn_save'])){
    $post = post($_POST);   
  //  printr($post);  die;
    $insert_id = $obj_rack_master->addstock($post,1);
    $obj_session->data['success'] = ADD;
    page_redirect($obj_general->link($rout, '&mod=india_add', '',1));
  }
// --------------------------------------------------------------------------------------------------------------------------------------Mode setting for the dispatch Ends here
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
          <header class="panel-heading bg-white"> Inventory Stock Report 
          
             </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
                
<section class="panel">
   <header class="panel-heading">
      <ul class="nav nav-tabs nav-justified">
         <li class="active"><a data-toggle="tab" href="#product"><b>Product Code Wise Report</a></b></li>
         <li class=""><a data-toggle="tab" href="#dispatch"><b>Rack Wise  Report</a></b></li>
         <li class=""><a data-toggle="tab" href="#addstock"><b>Size Wise Report</b></a></li>
         <li class=""><a data-toggle="tab" href="#product_wise"><b>Product Wise Report</b></a></li>
     </ul>
     
   </header>
   <div class="panel-body">
        <div class="tab-content">
            <div id="product" class="tab-pane active">
                <section class="panel">
                   <header class="panel-heading bg-white">Product Code Wise Report </header>
                   <div class="panel-body">
                      <form class="form-horizontal" method="post" name="form_dis_product" id="form_dis_product" enctype="multipart/form-data">
                             <div class="form-group">
                                <label class="col-lg-3 control-label">Date From</label>
                                <div class="col-lg-3">
                                  <input type="text" class="form-control validate[required]" name="f_date" value="" placeholder="From Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly"  id="f_date"/>
                                    </div>
                              </div>
                              
                              <div class="form-group">
                                <label class="col-lg-3 control-label">Date To</label>
                                <div class="col-lg-3">
                                 <input type="text" class="form-control validate[required]" name="t_date" value="" placeholder="To Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="t_date"/>
                                </div>
                              </div>
                         <div class="form-group option">
                            <label class="col-lg-3 control-label"><span class="required">*</span>Product Code</label>
                            <div class="col-lg-4" id="holder">
                               <?php $product_codes=$obj_rack_master->getActiveProductCode(); 
                                  //printr($product_codes);
                                  ?>
                               <input type="hidden" id="product_code_id_add" name="product_code_id_add" value="">
                               <input type="text" id="keyword1" class="form-control validate[required]"  autocomplete="off" value=""> 
                               <input type="hidden" name="product_code_id_st" id="product_code_id_st" value="" />
                               <div id="ajax_response1"></div>
                                </div>
                                    <div class="col-lg-3" id="product_div"> 
                                       <input type="text" name="product_name_st" id="product_name_st"  value="<?php echo isset($_GET['proforma_in_id'])?$product_code['description']:'';?>" disabled="disabled" class="form-control validate" style="width:400px"/>
                                    </div>
                                </div>
                               <div class="panel-body">
                                  <div class="form-group ">
                                     <div class="table-responsive">
                                        <span class="text-muted m-l-small pull-right">
                                         <a class="label bg-success" href="javascript:void(0);" id="excel_link_product_code"><i class="fa fa-print"></i> Excel</a>
                                         </span>
                                         <div class="form-group"  id="table_details_product">
                                          </div>                    
                                     </div>                    
                                   </div>                    
                              </div>                    
                                      
                      </form>
                   </div>
                </section>
             </div>
            
            <div id="dispatch" class="tab-pane ">
                <section class="panel">
                    <header class="panel-heading bg-white">Rack Wise  Report </header>
                   <div class="panel-body">
                      <form class="form-horizontal" method="post" name="form_dis" id="form_dis" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Date From</label>
                                <div class="col-lg-3">
                                  <input type="text" class="form-control validate[required]" name="f_date" value="" placeholder="From Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly"  id="f_date1"/>
                                    </div>
                              </div>
                              
                              <div class="form-group">
                                <label class="col-lg-3 control-label">Date To</label>
                                <div class="col-lg-3">
                                 <input type="text" class="form-control validate[required]" name="t_date" value="" placeholder="To Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="t_date1"/>
                                </div>
                             </div>
                         <div class="form-group">
                            <label class="col-lg-3 control-label"><span class="required">*</span>Rack name</label>
                            <!--onchange="get_pallet_box()" -->
                            <div class="col-lg-4">
                               <?php $goods_master = $obj_goods_master->getGoodsMaster('','',$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']); ?>
                               <select name="rack_name" id="rack_name_dispatch"  class="form-control validate[required]">
                                  <option>Select Pallet</option>
                                  <?php foreach($goods_master as $gd)
                                     { ?>
                                  <option value="<?php echo $gd['row'].'='.$gd['column_name'].'='.$gd['goods_master_id'] ;?>"selected ><?php echo $gd['name']; ?></option>
                                  <?php }?>
                               </select>
                            </div>
                         </div>
                         <div class="form-group dis" style="display:none">
                            <label class="col-lg-3 control-label"><span class="required">*</span>Choose Box</label>
                            <div class="col-lg-4" id="rack_num">
                            </div>
                         </div>   
                         <div class="form-group dis" >
                            <label class="col-lg-3 control-label"></label>
                            <div class="col-lg-4" >
                            
                                <button type="button" name="btn_pro" id="btn_pro" onclick="getStockinfo()" class="btn btn-primary">Procced</button>
                            </div>
                         </div>
                         
                         <div id="getproduct" style="display:;" >
                            <input type="hidden" name="pid" id="pid" value="">
                             <div class="panel-body">
                                 <div class="table-responsive">
                                       <span class="text-muted m-l-small pull-right">
                                            <a class="label bg-success" href="javascript:void(0);" id="excel_link_rack_wise"><i class="fa fa-print"></i> Excel</a>
                                       </span>
                                        <div class="form-group"  id="table_details">
                                         </div>
                                   </div>
                            </div>
                         </div>
                      
                         
                    
                        
                      </form>
                   </div>
                </section>
             </div>
            <div id="addstock" class="tab-pane">
             <section class="panel">
                <header class="panel-heading bg-white">Size Wise Report </header>
                <div class="panel-body">
                   <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                       <div class="form-group">
                                <label class="col-lg-3 control-label">Date From</label>
                                <div class="col-lg-3">
                                  <input type="text" class="form-control validate[required]" name="f_date" value="" placeholder="From Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly"  id="f_date2"/>
                                    </div>
                              </div>
                              
                              <div class="form-group">
                                <label class="col-lg-3 control-label">Date To</label>
                                <div class="col-lg-3">
                                 <input type="text" class="form-control validate[required]" name="t_date" value="" placeholder="To Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="t_date2"/>
                                </div>
                        </div>
                    <div class="form-group">
                               <label class="col-lg-3 control-label">Product Name</label>
                               <div class="col-lg-3">
                                <?php
                                    $products = $obj_rack_master->getActiveProduct();
                                    ?>
                                    <select name="product_id" id="product_id" class="form-control validate" onchange="getsize()">
                                     <option value="">Select Product</option>
                                        <?php                                    
                                        foreach($products as $product){ 
                                                echo '<option value="'.$product['product_id'].'">'.$product['product_name'].'</option>';                                     
                                        } ?>
                                    </select>
                               </div>   
                           </div>   
                            <div class="form-group" id="zipper_div">
                               
                            </div>
                           <div class="form-group" >
                               <label class="col-lg-3 control-label">Size</label>
                               <div class="col-lg-3" id="size_div">
                                
                               </div>   
                           </div>
    
                     
    
                            <div class="panel-body">
                              <div class="form-group ">
                                 <div class="table-responsive">
                                    <span class="text-muted m-l-small pull-right">
                                     <a class="label bg-success" href="javascript:void(0);" id="excel_link_size_wise"><i class="fa fa-print"></i> Excel</a>
                                     </span>
                                     <div class="form-group"  id="size_wise_product">
                                      </div>                    
                                 </div>                    
                               </div>                    
                          </div> 
                   </form>
                </div>   
             </section>
          </div>
            
            <div id="product_wise" class="tab-pane ">
                <section class="panel">
                   <header class="panel-heading bg-white">Product Wise Report </header>
                   <div class="panel-body">
                      <form class="form-horizontal" method="post" name="form_dis_product" id="form_dis_product" enctype="multipart/form-data">
                         <div class="form-group option">
                            <label class="col-lg-3 control-label"><span class="required">*</span>Product Code</label>
                            <div class="col-lg-4" id="holder">
                               <input type="text" id="product_code1" name="product_code1" class="form-control validate[required]"  autocomplete="off" value=""> 
                            </div>
                         </div>
                         <div class="form-group option">
                             <label class="col-lg-3 control-label"></label>
                            <div class="col-lg-4" id="holder">
                                <button type="button"  name="btn_save" id="btn_save" class="btn btn-primary" onclick="getstatus();">Status</button> 
                            </div>
                         </div>
                           <div class="panel-body">
                              <div class="form-group ">
                                 <div class="table-responsive">
                                    <span class="text-muted m-l-small pull-right">
                                     <a class="label bg-success" href="javascript:void(0);" id="excel_link_product"><i class="fa fa-print"></i> Excel</a>
                                     </span>
                                     <div class="form-group"  id="table_details_productwise">
                                     </div>                    
                                 </div>                    
                               </div>                    
                          </div>                    
                                      
                      </form>
                   </div>
                </section>
             </div>
            
            
        </div>
   </div>
</section>
              
          </div>
      
        </section>
        
      </div>
    </div>
  </section>
</section>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
jQuery(document).ready(function(){
	
	   var nowTemp = new Date();
		//alert(nowTemp);
	    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		//alert(now);
	    var checkin = $('#f_date').datepicker({
   			onRender: function(date) {
    		return date.valueOf() < now.valueOf() ? '' : '';
    		}
    	}).on('changeDate', function(ev) {
			if (ev.date.valueOf() <= checkout.date.valueOf()) {
				var newDate = new Date(ev.date);
          		newDate.setDate(newDate.getDate());
    			checkout.setValue(newDate);
    		}
    		checkin.hide();
    		$('#t_date')[0].focus();
    	}).data('datepicker');
    	var checkout = $('#t_date').datepicker({
    		onRender: function(date) {
				if(checkin.date.valueOf() > date.valueOf())
						return 'disabled';
					else
						return '';
				
    		}
    	}).on('changeDate', function(ev) {
    		checkout.hide();
    	}).data('datepicker');
});

jQuery(document).ready(function(){
	 
	   var nowTemp = new Date();
		//alert(nowTemp);
	    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		//alert(now);
	    var checkin = $('#f_date1').datepicker({
   			onRender: function(date) {
    		return date.valueOf() < now.valueOf() ? '' : '';
    		}
    	}).on('changeDate', function(ev) {
			if (ev.date.valueOf() <= checkout.date.valueOf()) {
				var newDate = new Date(ev.date);
          		newDate.setDate(newDate.getDate());
    			checkout.setValue(newDate);
    		}
    		checkin.hide();
    		$('#t_date1')[0].focus();
    	}).data('datepicker');
    	var checkout = $('#t_date1').datepicker({
    		onRender: function(date) {
				if(checkin.date.valueOf() > date.valueOf())
						return 'disabled';
					else
						return '';
				
    		}
    	}).on('changeDate', function(ev) {
    		checkout.hide();
    	}).data('datepicker');
});

jQuery(document).ready(function(){
	  
	   var nowTemp = new Date();
		//alert(nowTemp);
	    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		//alert(now);
	    var checkin = $('#f_date2').datepicker({
   			onRender: function(date) {
    		return date.valueOf() < now.valueOf() ? '' : '';
    		}
    	}).on('changeDate', function(ev) {
			if (ev.date.valueOf() <= checkout.date.valueOf()) {
				var newDate = new Date(ev.date);
          		newDate.setDate(newDate.getDate());
    			checkout.setValue(newDate);
    		}
    		checkin.hide();
    		$('#t_date2')[0].focus();
    	}).data('datepicker');
    	var checkout = $('#t_date2').datepicker({
    		onRender: function(date) {
				if(checkin.date.valueOf() > date.valueOf())
						return 'disabled';
					else
						return '';
				
    		}
    	}).on('changeDate', function(ev) {
    		checkout.hide();
    	}).data('datepicker');
});

</script>
<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
    });
</script> 

<!-- CSS JS FOR THE ADD STOCK STARTS  HERE  -->

<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<style type="text/css">
.btn-on.active {
    background: none repeat scroll 0 0 #3fcf7f;
}
.btn-off.active{
  background: none repeat scroll 0 0 #3fcf7f;
  border: 1px solid #767676;
  color: #fff;
}
@media (max-width: 400px) {
  .chunk {
    width: 100% !important;
  }
}
#ajax_response,#a_response{
  border : 1px solid #13c4a5;
  background : #FFFFFF;
  position:relative;
  display:none;
  padding:2px 2px;
  top:auto;
  border-radius: 4px;
}
#ajax_response1{
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
  /*color:#575757;*/
  color:#575757;
  cursor : default;
}
</style> 
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href=" https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script> 
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script> 
<script>
   jQuery(document).ready(function(){
        //get_pallet();
      //  get_pallet_box();
        getsize();
        // biget_pallet_boxnds form submission and fields to the validation engine
        $(".chosen_data").chosen();
       
    jQuery("#form").validationEngine();
    $("#input_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
    $("#dis_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
    
    
    

    });
  $(".chosen-select").chosen();
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
              div =div+'<li><a href=\'javascript:void(0);\' discr="'+msg[i].description+'" product_id="'+msg[i].product+'" color="'+msg[i].color+'" size="'+msg[i].volume+'" mea="'+msg[i].measurement+'" id="'+msg[i].product_code_id+'"><span class="bold" >'+msg[i].product_code+'</span></a></li>';     
            
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
              $("#product_code_id_add").val($(".list li[class='selected'] a").attr("id"));
              $("#product_id").val($(".list li[class='selected'] a").attr("product_id"));
              
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
              $("#product_code_id_add").val($(".list li[class='selected'] a").attr("id"));
              $("#product_id").val($(".list li[class='selected'] a").attr("product_id"));
              
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
      
           $("#product_code_id_add").val($(this).attr("id"));
           $("#product_id").val($(this).attr("product_id"));
          $(this).addClass("selected");
      });
      $(this).find(".list li a:first-child").mouseout(function () {
          $(this).removeClass("selected");
      });
      $(this).find(".list li a:first-child").click(function () {
          $("#product_div").show();
                  $("#product_name").val($(this).attr("discr"));
        
          $("#product_id").val($(this).attr("product_id"));
          $("#product_code_id_add").val($(this).attr("id"));
          $("#keyword").val($(this).text()); 
          $("#ajax_response").fadeOut('slow');
          $("#ajax_response").html("");
        });
      
    });
 
function get_pallet()
{
  var rack_val=$("#rack_name").val();
  var arr = rack_val.split('=');
  var row = arr[0];
  var col = arr[1];
  var goods_master_id = arr[2];
  var order_status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getLabel_pallet_add_stock', '',1);?>");
  $.ajax({
      url : order_status_url,
      method : 'post',
      data : {row:row,col:col,goods_master_id:goods_master_id},
      success: function(response){
        $("#rack_number").html(response);
    },
    error: function(){
      return false; 
    }
  }); 
  
  $(".dis").show();
//  $("#rack_number").html(sel);
  $(".chosen-select").chosen();
}
 
function get_pallet_box()
{
  var rack_val=$("#rack_name_dispatch").val();
  var arr = rack_val.split('=');
  var row = arr[0];
  var col = arr[1];
  var goods_master_id = arr[2];
  var order_status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getLabel_pallet_dis_stock', '',1);?>");
  $.ajax({
      url : order_status_url,
      method : 'post',
      data : {row:row,col:col,goods_master_id:goods_master_id},
      success: function(response){
        $("#rack_num").html(response);
    },
    error: function(){
      return false; 
    }
  }); 
  $(".dis").show();
//  $("#rack_num").html(sel);
  $(".chosen-select").chosen();
}

$(document).click(function(){
      $("#a_response").fadeOut('slow');
      $("#a_response").html("");
    });
      $("#added_by").focus();
    var offset = $("#added_by").offset();
    var width = $("#holder").width();
    $("#a_response").css("width",width);
    
    $("#added_by").keyup(function(event){
     var keyword = $("#added_by").val();
     //alert(keyword);
     
     if(keyword.length)
     {  
      
       if(event.keyCode != 40 && event.keyCode != 38 )
       {    
         var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=user_list', '',1);?>");
         $("#loading").css("visibility","visible");
         $.ajax({
           type: "POST",
           url: url,
           data: "user="+keyword,
           success: function(msg){  
          //console.log(msg);
           var msg = $.parseJSON(msg);
           var div='<ul class="list">';
           
          if(msg.length>0)
          {
            for(var i=0;i<msg.length;i++)
            { 
              div =div+'<li><a href=\'javascript:void(0);\' user_id="'+msg[i].user_id+'" user_type_id="'+msg[i].user_type_id+'"><span class="bold" >'+msg[i].user_name+'</span></a></li>';      
              
            }
          }
          //alert(div);
          div=div+'</ul>';
          if(msg != 0)
            $("#a_response").fadeIn("slow").html(div);
          else
          {
            $("#a_response").fadeIn("slow");  
            $("#a_response").html('<div style="text-align:left;">No Matches Found</div>');
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
              $("#added_by").val($(".list li[class='selected'] a").text());
                        $("#added_user_id").val($(".list li[class='selected'] a").attr("user_id"));
              $("#user_type_id").val($(".list li[class='selected'] a").attr("user_type_id"));
            
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
              $("#added_by").val($(".list li[class='selected'] a").text());
              $("#added_user_id").val($(".list li[class='selected'] a").attr("user_id"));
              $("#user_type_id").val($(".list li[class='selected'] a").attr("user_type_id"));
            }
         }
         break;        
        }
       }
     }
     else
     {  
      $("#a_response").fadeOut('slow');
      $("#a_response").html("");

     }
  });
  $('#added_by').keydown( function(e) {
    if (e.keyCode == 9) {
     $("#a_response").fadeOut('slow');
     $("#a_response").html("");
    }
  
});
  $("#a_response").mouseover(function(){
      $(this).find(".list li a:first-child").mouseover(function () {
                  $("#added_user_id").val($(this).attr("user_id"));
          $("#user_type_id").val($(this).attr("user_type_id"));
          $(this).addClass("selected");
      });
      $(this).find(".list li a:first-child").mouseout(function () {
          $(this).removeClass("selected");
      });
      $(this).find(".list li a:first-child").click(function () {
          $("#added_user_id").val($(this).attr("user_id"));
      
          $("#user_type_id").val($(this).attr("user_type_id"));
          $("#added_by").val($(this).text());
          $("#a_response").fadeOut('slow');
          $("#a_response").html("");
        });
      
    });
  // added for the stock product add  
  $(document).click(function(){
      $("#ajax_response1").fadeOut('slow');
      $("#ajax_response1").html("");
    });
      $("#keyword1").focus();
    var offset = $("#keyword1").offset();
    var width = $("#holder").width();
    $("#ajax_response1").css("width",width);
    
    $("#keyword1").keyup(function(event){
     var keyword = $("#keyword1").val();
     //alert(keyword);
     
     if(keyword.length)
     {  
      $("#color_txt").hide();
      $("#product_name_st").show();
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
              div =div+'<li><a href=\'javascript:void(0);\' discr="'+msg[i].description+'" product_id="'+msg[i].product+'" color="'+msg[i].color+'" size="'+msg[i].volume+'" mea="'+msg[i].measurement+'" id="'+msg[i].product_code_id+'"><span class="bold" >'+msg[i].product_code+'</span></a></li>';     
              
            }
          }
          
          div=div+'</ul>';
          if(msg != 0)
            $("#ajax_response1").fadeIn("slow").html(div);
          else
          {
            $("#ajax_response1").fadeIn("slow");  
            $("#ajax_response1").html('<div style="text-align:left;">No Matches Found</div>');
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
              $("#keyword1").val($(".list li[class='selected'] a").text());
              $("#product_div").show();
                        $("#product_name_st").val($(".list li[class='selected'] a").attr("discr"));
              $("#product_code_id_st").val($(".list li[class='selected'] a").attr("id"));
              $("#product_id").val($(".list li[class='selected'] a").attr("product_id"));
              
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
              $("#keyword1").val($(".list li[class='selected'] a").text());
              $("#product_div").show();
                        $("#product_name_st").val($(".list li[class='selected'] a").attr("discr"));
              $("#product_code_id_st").val($(".list li[class='selected'] a").attr("id"));
              $("#product_id").val($(".list li[class='selected'] a").attr("product_id"));
              
            }
         }
         break;        
        }
       }
     }
     else
     {  
      $("#ajax_response1").fadeOut('slow');
      $("#ajax_response1").html("");

     }
  });
  $('#keyword1').keydown( function(e) {
    if (e.keyCode == 9) {
     $("#ajax_response1").fadeOut('slow');
     $("#ajax_response1").html("");
    }
  
});
  $("#ajax_response1").mouseover(function(){
      $(this).find(".list li a:first-child").mouseover(function () {
          $("#product_div").show();
                  $("#product_name_st").val($(this).attr("discr"));
      
          
           $("#product_code_id_st").val($(this).attr("id"));
           $("#product_id").val($(this).attr("product_id"));
          $(this).addClass("selected");
      });
      $(this).find(".list li a:first-child").mouseout(function () {
          $(this).removeClass("selected");
      });
      $(this).find(".list li a:first-child").click(function () {
          $("#product_div").show();
                  $("#product_name_st").val($(this).attr("discr"));
          //$("#color_product").val($(this).attr("color"));
         // $("#size").val($(this).attr("size"));
          $("#product_id").val($(this).attr("product_id"));
          $("#product_code_id_st").val($(this).attr("id"));
           //
          $("#keyword1").val($(this).text());
          $("#ajax_response1").fadeOut('slow');
          $("#ajax_response1").html("");
         getStockForproductcode($("#product_code_id_st").val());
        });
      
    });
 
function getStockForproductcode(product_code_id)
{
  
   //alert(product_code_id);
    var fdate=$("#f_date").val();
    var tdate=$("#t_date").val();
    
    var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getrackdataforinventory', '',1);?>");
    $.ajax({
      url : data_url,
      method : 'post',
      data : {product_code_id : product_code_id,fdate:fdate,tdate:tdate},
      success: function(response){
        //alert(response);
        $("#table_details_product").html(response);
      
         
      },
      error:function(){
      } 
    });
} 

function getStockinfo()
{
    var fdate=$("#f_date1").val();
    var tdate=$("#t_date1").val();
   
   pallet_details_dis =$('#pallet_dispatch').val();
   var rack_name_dispatch =$('#rack_name_dispatch').val();
   //alert(product_code_id);
    var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=rack_qty_inventory', '',1);?>");
    $.ajax({
      url : data_url,
      method : 'post',
      data : {pallet_details_dis : pallet_details_dis,fdate:fdate,tdate:tdate,rack_name_dispatch:rack_name_dispatch},
      success: function(response){
        //alert(response);
        $("#table_details").html(response);
     //   $('#report_product_code').css('display','none');
      },
      error:function(){
      } 
    });
} 
function getsize()
{
   var  product_id=$('#product_id').val();
    //alert(product_code_id);
      var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getProductSize', '',1);?>");
      $.ajax({
         url : data_url,
         method : 'post',
         data : {product_id : product_id},
         success: function(response){
            //alert(response);
            $("#size_div").html(response);
            

          
         },
         error:function(){
         }  
      });
}  

$("#excel_link_rack_wise").click(function(){


     var html1='';
     html1+= '<style>  table, th, td {   border: 1px solid black; }</style>'; 
    
      var rack_name=$("#rack_name_dispatch option:selected").text();
      //var pallet_no=$("#pallet_dispatch option:selected").text();

     //alert(rack_name);


     html1+='<center><h2><b>Inventory Stock Report Rack Wise</b></h2><br> <b><h3>Product Details For '+rack_name+'</h3></b></center>';

     html1+= $('#print_table').html(); 

         excelData1 = 'data:application/excel;charset=utf-8,' + encodeURIComponent(html1);
          $('<a></a>').attr({
                     'id':'downloadFile1',
                     'download': 'Inventory-stock-report.xls',
                     'href': excelData1,
                     'target': '_blank'
               }).appendTo('body');
               $('#downloadFile1').ready(function() {
                  $('#downloadFile1').get(0).click();
               });
  



});   
$("#excel_link_product_code").click(function(){


     var html='';
         html+= '<style>  table, th, td {   border: 1px solid black; }</style>'; 
         html+='<center><h2><b>Inventory Stock Report Product Wise</b></h2><br> </center>';
   var product_code=$('#keyword1').val();
   var product_code_dec=$('#product_name_st').val();

   html+='<u><b><h3>Product Code : '+product_code+'  <br> Description: '+product_code_dec+'</h3></b></u>';
     html+= $('#print_product_code').html(); 
      //alert(html);
         excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(html);
          $('<a></a>').attr({
                     'id':'downloadFile',
                     'download': 'Inventory-stock-report-productcode.xls',
                     'href': excelData,
                     'target': '_blank'
               }).appendTo('body');
               $('#downloadFile').ready(function() {
                  $('#downloadFile').get(0).click();
               });
    

});
$("#excel_link_product").click(function(){


     var html='';
         html+= '<style>  table, th, td {   border: 1px solid black; }</style>'; 
         html+='<center><h2><b>Inventory Stock Report Product Wise</b></h2><br> </center>';
   
     html+= $('#print_product').html(); 
      //alert(html);
         excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(html);
          $('<a></a>').attr({
                     'id':'downloadFile3',
                     'download': 'Inventory-stock-report-product-wise.xls',
                     'href': excelData,
                     'target': '_blank'
               }).appendTo('body');
               $('#downloadFile3').ready(function() {
                  $('#downloadFile3').get(0).click();
               });
    

});
$("#excel_link_size_wise").click(function(){


    var html2='';
     html2+= '<style>  table, th, td {   border: 1px solid black; }</style>'; 
     html2+='<center><h2><b>Inventory Report Product & Size Wise</b></h2><br> </center>';
     html2+= $('#print_table_size_Wise').html(); 
     
         excelData2 = 'data:application/excel;charset=utf-8,' + encodeURIComponent(html2);
          $('<a></a>').attr({
                     'id':'downloadFile2',
                     'download': 'Inventory-Product-Sizewise-report-productcode.xls',
                     'href': excelData2,
                     'target': '_blank'
               }).appendTo('body');
               $('#downloadFile2').ready(function() {
                  $('#downloadFile2').get(0).click();
               });
    

});
$(document).on('change','#size',function(){
  
   var fdate=$("#f_date2").val();
   var tdate=$("#t_date2").val();
  
  var product_id =$('#product_id').val();
  var size_id= $('#size').val();
  if(product_id != '' && size_id != ''){

    var size_arr = size_id.split('==');
    var volume =parseInt(size_arr[1]);
    //alert(volume);
    //var col = arr[1];
     var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getSizeWiseReport', '',1);?>");
        $.ajax({
           url : data_url,
           method : 'post',
           data : {product_id : product_id,volume:volume,fdate:fdate,tdate:tdate},
           success: function(response){
              //alert(response);
             
              $("#size_wise_product").html(response); 

            
           },
           error:function(){
           }  
        });
    
  }
  
  
});

function getstatus(){
  var product =$("#product_code1").val();
  if(product != ''){
     var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getProductWiseReport', '',1);?>");
        $.ajax({
           url : data_url,
           method : 'post',
           data : {product : product},
           success: function(response){
              //console.log(response);
              $("#table_details_productwise").html(response); 
            },
           error:function(){
           }  
        });
  }
}

</script> 

<?php  }else { 
    include(SERVER_ADMIN_PATH.'access_denied.php');
  }
?>