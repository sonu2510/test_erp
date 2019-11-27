<?php
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


if($display_status) {
    
    $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
    $user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
    $product_codes=$obj_rack_master->getActiveProductCode();
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
         <li class="active"><a data-toggle="tab" href="#product"><b>Inward Report</a></b></li>
         <li class=""><a data-toggle="tab" href="#dispatch"><b>Outward  Report</a></b></li>
        
     </ul>
     
   </header>
   <div class="panel-body">
        <div class="tab-content">
            <div id="product" class="tab-pane active">
                <section class="panel">
                   <header class="panel-heading bg-white">Inward Report </header>
                   <div class="panel-body">
                      <form class="form-horizontal" method="post" name="inward" id="inward" enctype="multipart/form-data">
                             <div class="form-group">
                                <label class="col-lg-3 control-label">Date From</label>
                                <div class="col-lg-3">
                                  <input type="text" class="form-control" name="f_date" value="" placeholder="From Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly"  id="f_date"/>
                                    </div>
                              </div>
                              
                              <div class="form-group">
                                <label class="col-lg-3 control-label">Date To</label>
                                <div class="col-lg-3">
                                 <input type="text" class="form-control" name="t_date" value="" placeholder="To Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="t_date"/>
                                </div>
                              </div>
                             <div class="form-group option">
                                <label class="col-lg-3 control-label"><span class="required">*</span>Product Code</label>
                                <div class="col-lg-4" id="holder">
                                      <input type="text" id="keyword" class="form-control" autocomplete="off" value=""> 
                                      <input type="hidden" name="product_code_id_st" id="product_code_id_st" value=""/>
                                      <div id="ajax_response"></div>
                                </div>
                                <div class="col-lg-3" id="product_div"> 
                                   <input type="text" name="product_name_st" id="product_name_st"  value="<?php echo isset($_GET['proforma_in_id'])?$product_code['description']:'';?>" disabled="disabled" class="form-control validate" style="width:400px"/>
                                </div>
                             </div>
                             <div class="form-group dis" >
                                <label class="col-lg-3 control-label"></label>
                                <div class="col-lg-4" >
                                    <button type="button" name="btn_inward" id="btn_inward" onclick="getStockinfoInward(1)" class="btn btn-primary">Procced</button>
                                </div>
                             </div>
                        
                               <div class="panel-body">
                                  <div class="form-group ">
                                     <div class="table-responsive">
                                        <span class="text-muted m-l-small pull-right">
                                         <a class="label bg-success" href="javascript:void(0);" id="excel_link_inward"><i class="fa fa-print"></i> Excel</a>
                                         </span>
                                         <div class="form-group"  id="inward">
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
                    <header class="panel-heading bg-white">OutWard Report </header>
                   <div class="panel-body">
                      <form class="form-horizontal" method="post" name="outward" id="outward" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Date From</label>
                                <div class="col-lg-3">
                                  <input type="text" class="form-control" name="f_date" value="" placeholder="From Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly"  id="f_date1"/>
                                    </div>
                              </div>
                              
                              <div class="form-group">
                                <label class="col-lg-3 control-label">Date To</label>
                                <div class="col-lg-3">
                                 <input type="text" class="form-control" name="t_date" value="" placeholder="To Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="t_date1"/>
                                </div>
                             </div>
                             <div class="form-group option">
                                <label class="col-lg-3 control-label"><span class="required">*</span>Product Code</label>
                                <div class="col-lg-4" id="holder">
                                    <input type="text" id="keyword1" class="form-control" autocomplete="off" value=""> 
                                    <input type="hidden" name="product_code_id_st1" id="product_code_id_st1" value="" />
                                    <div id="ajax_response1"></div>
                                </div>
                                <div class="col-lg-3" id="product_div1"> 
                                   <input type="text" name="product_name_st1" id="product_name_st1"  value="<?php echo isset($_GET['proforma_in_id'])?$product_code['description']:'';?>" disabled="disabled" class="form-control validate" style="width:400px"/>
                                </div>
                             </div>
                            <div class="form-group dis" >
                                <label class="col-lg-3 control-label"></label>
                                <div class="col-lg-4" >
                                    <button type="button" name="btn_outward" id="btn_outward" onclick="getStockinfo(2)" class="btn btn-primary">Procced</button>
                                </div>
                             </div>
                         
                             <div class="panel-body">
                                 <div class="table-responsive">
                                       <span class="text-muted m-l-small pull-right">
                                            <a class="label bg-success" href="javascript:void(0);" id="excel_link_out_ward"><i class="fa fa-print"></i> Excel</a>
                                       </span>
                                        <div class="form-group"  id="out_ward">
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
#ajax_response1,#ajax_response{
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
	
	    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	
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


</script>

<script>
   jQuery(document).ready(function(){
    jQuery("#form").validationEngine();
    $("#input_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
    $("#dis_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
    
    });
function getStockinfo(status)
{
    var fdate=$("#f_date1").val();
    var tdate=$("#t_date1").val();
    var product_code_id=$("#product_code_id_st1").val();
 
    var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getOutwardData', '',1);?>");
    $.ajax({
      url : data_url,
      method : 'post',
      data : {fdate:fdate,tdate:tdate,status:status,product_code_id:product_code_id},
      success: function(response){
        $("#out_ward").html(response);
      },
      error:function(){
      } 
    });
}
function getStockinfoInward(status)
{
    var fdate=$("#f_date").val();
    var tdate=$("#t_date").val();
    var product_code_id=$("#product_code_id_st").val();
   
 
    var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getInwardData', '',1);?>");
    $.ajax({
      url : data_url,
      method : 'post',
      data : {fdate:fdate,tdate:tdate,status:status,product_code_id:product_code_id},
      success: function(response){
     
        $("#inward").html(response);
    
      },
      error:function(){
      } 
    });
} 
$("#excel_link_inward").click(function(){


     var html='';
         html+= '<style>  table, th, td {   border: 1px solid black; }</style>'; 
       //  html+='<center><h2><b>Inventory Stock Report Product Wise</b></h2><br> </center>';
         html+= $('#inward').html(); 
      //alert(html);
         excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(html);
          $('<a></a>').attr({
                     'id':'downloadFile',
                     'download': 'Inward-report.xls',
                     'href': excelData,
                     'target': '_blank'
               }).appendTo('body');
               $('#downloadFile').ready(function() {
                  $('#downloadFile').get(0).click();
               });
    

});
$("#excel_link_out_ward").click(function(){


     var html='';
         html+= '<style>  table, th, td {   border: 1px solid black; }</style>'; 
        // html+='<center><h2><b>Inventory Stock Report Product Wise</b></h2><br> </center>';
   
     html+= $('#out_ward').html(); 
      //alert(html);
         excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(html);
          $('<a></a>').attr({
                     'id':'downloadFile3',
                     'download': 'Outward-stock-report.xls',
                     'href': excelData,
                     'target': '_blank'
               }).appendTo('body');
               $('#downloadFile3').ready(function() {
                  $('#downloadFile3').get(0).click();
               });
    

});
  $("#keyword").focus();
    var offset = $("#keyword").offset();
    var width = $("#holder").width();
    $("#ajax_response").css("width",width);
    
    $("#keyword").keyup(function(event){
     var keyword = $("#keyword").val();
     
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
              $("#keyword").val($(".list li[class='selected'] a").text());
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
          $("#product_id").val($(this).attr("product_id"));
          $("#product_code_id_st").val($(this).attr("id"));
          $("#keyword").val($(this).text());
          $("#ajax_response").fadeOut('slow');
          $("#ajax_response").html("");
         
        });
      
    });
    
    $("#keyword1").focus();
    var offset = $("#keyword1").offset();
    var width = $("#holder").width();
    $("#ajax_response1").css("width",width);
    
    $("#keyword1").keyup(function(event){
     var keyword = $("#keyword1").val();
     
     if(keyword.length)
     {  
      
      $("#product_name_st1").show();
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
              $("#product_div1").show();
              $("#product_name_st1").val($(".list li[class='selected'] a").attr("discr"));
              $("#product_code_id_st1").val($(".list li[class='selected'] a").attr("id"));
              $("#product_id1").val($(".list li[class='selected'] a").attr("product_id"));
              
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
              $("#product_div1").show();
              $("#product_name_st1").val($(".list li[class='selected'] a").attr("discr"));
              $("#product_code_id_st1").val($(".list li[class='selected'] a").attr("id"));
              $("#product_id1").val($(".list li[class='selected'] a").attr("product_id"));
              
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
          $("#product_div1").show();
          $("#product_name_st1").val($(this).attr("discr"));
          $("#product_code_id_st1").val($(this).attr("id"));
          $("#product_id1").val($(this).attr("product_id"));
          $(this).addClass("selected");
      });
      $(this).find(".list li a:first-child").mouseout(function () {
          $(this).removeClass("selected");
      });
      $(this).find(".list li a:first-child").click(function () {
          $("#product_div1").show();
          $("#product_name_st1").val($(this).attr("discr"));
          $("#product_id1").val($(this).attr("product_id"));
          $("#product_code_id_st1").val($(this).attr("id"));
          $("#keyword1").val($(this).text());
          $("#ajax_response1").fadeOut('slow');
          $("#ajax_response1").html("");
         
        });
      
    });
</script> 

<?php  }else { 
    include(SERVER_ADMIN_PATH.'access_denied.php');
  }
?>