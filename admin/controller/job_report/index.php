<?php
include("mode_setting.php");

$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.'',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

$class = 'collapse';

if($display_status) {

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
       
          	<span><?php echo $display_name;?></span>
          	<span class="text-muted m-l-small pull-right">
          			
            </span>
           
          </header>
          
          <div class="panel-body"></div>
           
			 <form class="form-horizontal" method="post" name="frm_add" id="frm_add" enctype="multipart/form-data" action="<?php echo $obj_general->link($rout, 'mod=view', '',1);?>">
            <div class="form-group">
                    <label class="col-lg-2 control-label"><span class="required">*</span>Job No</label>
                    <div class="col-lg-3">
                        <input type="text" name="job_name_text" id="job_name_text" value="<?php echo isset($job_detail['job_name_text'])?$job_detail['job_name_text']:'';?>" class="form-control validate[required]">
                        <input type="hidden" name="job_id" id="job_id" value="" />
                        <div id="ajax_response"></div>
                    </div>
                      <div class="col-lg-4">
                            <input type="text" class="form-control " readonly name="job_name" id="job_name" value=""/>
                        </div>
                </div>
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                  <button type="submit" name="btn_pro" id="btn_pro" class="btn btn-primary">Proceed</button>  
                </div>
              </div>

            </form>
                    
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-4 hidden-xs"> </div>
             
             
            </div>
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>
<style>
	.inactive{
		//background-color:#999;	
	}
</style>

<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<style type="text/css">
#ajax_response, #ajax_res,#ajax_return{
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
.select_choose{
  width:100px;
}
</style>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
jQuery(document).ready(function(){
	   jQuery("#frm_add").validationEngine();
   });

	   $("#job_name_text").focus();
  var offset = $("#product_item_id").offset();
  var width = $("#holder").width();
  $("#ajax_response").css("width",width);
  
  $("#job_name_text").keyup(function(event){    
     var keyword = $("#job_name_text").val();
     if(keyword.length)
     {  
       if(event.keyCode != 40 && event.keyCode != 38 )
       {
         var product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=job_detail', '',1);?>");
         $("#loading").css("visibility","visible");
         $.ajax({
           type: "POST",
           url: product_url,
           data: "job="+keyword,
           success: function(msg){  
         var msg = $.parseJSON(msg);
           var div='<ul class="list">';
           
          if(msg.length>0)
          {   
            for(var i=0;i<msg.length;i++)
            { 
              div =div+'<li><a href=\'javascript:void(0);\' job_no ="'+msg[i].job_no+'" id="'+msg[i].job_id+'" job_name="'+msg[i].job_name+'" ><span class="bold" >'+msg[i].job_no+'</span></a></li>';
            }
          }
          
          div=div+'</ul>';
        
          if(msg != 0)
            $("#ajax_response").fadeIn("slow").html(div);
          else
          {
            $("#ajax_response").fadeIn("slow"); 
            $("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
              $("#job_id").val('');
              $("#job_name").val('');
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
              $("#job_name_text").val($(".list li[class='selected'] a").text());
              $("#job_id").val($(".list li[class='selected'] a").attr("id"));
              $("#job_name").val($(".list li[class='selected'] a").attr("job_name"));
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
              $("#job_name_text").val($(".list li[class='selected'] a").text());
              $("#job_id").val($(".list li[class='selected'] a").attr("id"));
              $("#job_name").val($(".list li[class='selected'] a").attr("job_name"));
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
  
  $('#job_name_text').keydown( function(e) {
    if (e.keyCode == 9) {
       $("#ajax_response").fadeOut('slow');
       $("#ajax_response").html("");
    }
  });

  $("#ajax_response").mouseover(function(){
        $(this).find(".list li a:first-child").mouseover(function () {
            $("#job_id").val($(this).attr("id"));
            $("#job_name").val($(this).attr("job_name"));
            $(this).addClass("selected");
        });
        $(this).find(".list li a:first-child").mouseout(function () {
            $(this).removeClass("selected");
            $("#job_id").val('');
            $("#job_name").val('');
        });
        $(this).find(".list li a:first-child").click(function () {
            $("#job_id").val($(this).attr("id"));
            $("#job_name").val($(this).attr("job_name"));
            
            $("#job_name_text").val($(this).text());
           $("#ajax_response").fadeOut('slow');
            $("#ajax_response").html("");
            
          
        });
        
      });
	 

</script>
          
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>