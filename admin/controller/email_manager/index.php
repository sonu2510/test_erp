<?php
include("mode_setting.php");
ini_set('max_execution_time', 60);
//Start : bradcums

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
if(isset($_GET['accessorie_id']) && !empty($_GET['accessorie_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$accessorie_id = base64_decode($_GET['accessorie_id']);
		$accessorie= $obj_accessorie->getAccessorie($accessorie_id);
		//printr($accessorie);die;
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit


if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
	
		$post = post($_POST);
		//printr($post);die;
		$to = $post['to'];
		$from = $post['from'];
		$subject = $post['subject'];
		$f_message = str_replace('\r\n',' ',$post['message']); 
		
		$tmp_filename="index.html";
		$tmp_path = HTTP_SERVER."template/".$tmp_filename;
		
		if(!file_exists($tmp_path)){
			$output = file_get_contents($tmp_path);  
			$search  = array('{tag:title}','{tag:details}');
			$replace = array($subject,$f_message);
			$html = str_replace($search, $replace, $output); 
		}
		if(isset($_SESSION['image']) && !empty($_SESSION['image'])){
			$attachment = $_SESSION['image'];
		}else{
			$attachment= array();
		}
		//printr($attachment);die;
		
		$i=0;
		foreach($to as $i=>$moreto){
			
			$response = send_email($moreto,$from,$subject,$html,$attachment);
				if($i<4){ $i++; continue; }
				sleep(15);
			
		}
		
		if($response){
			unset($_SESSION['attachment']);
			$obj_session->data['success'] = 'Success : Email Sent Successfully!';
			page_redirect($obj_general->link($rout,'', '',1));
		}
	}else{
		if(isset($_SESSION['attachment'])){
			unset($_SESSION['attachment']);	
		}
	}
	/*$filetemp = $_FILES["attachment"]["tmp_name"];
		$attachment= array();
		if(isset($filetemp) && !empty($filetemp)){
			foreach ($filetemp as $key => $tmp_name){
				$upload_path = DIR_UPLOAD;
				$file_name = $_FILES["attachment"]["name"][$key];
				$file_temp = $_FILES["attachment"]["tmp_name"][$key];
				
				$upload_image_path = $upload_path."/".$file_name;
				if(file_exists($upload_image_path)){
					$file_name = rand().'_'.$file_name;
					uploadfile($file_temp,$file_name,$upload_path);
					$attachment[] = $upload_image_path; 
				}else{
					uploadfile($file_temp,$file_name,$upload_path);
					$attachment[] = $upload_image_path; 
				}
					
			}
		}*/
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
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              <div class="form-group">
                <div class="radio  radio-group">
                  <div class="col-lg-12">
                    <label class="col-lg-3 control-label"><strong>Select</strong></label>
                    <div class="col-lg-4">
                      <label class="radio-custom">
                        <input type="radio" class="user_typ" id="" value="1" name="user_type">
                        <i class="fa fa-circle-o"></i> User </label>
                    </div>
                    <div class="col-lg-4">
                      <label class="radio-custom">
                        <input type="radio" class="user_typ" id="" value="3" name="user_type">
                        <i class="fa fa-circle-o"></i> Client </label>
                    </div>
                    <div class="col-lg-4">
                      <label class="radio-custom">
                        <input type="radio" class="user_typ" value="4" id="" name="user_type">
                        <i class="fa fa-circle-o"></i> International Branch </label>
                    </div>
                    <div class="col-lg-4">
                      <label class="radio-custom">
                        <input type="radio" class="user_typ" value="5"  id="" name="user_type">
                        <i class="fa fa-circle-o"></i> Associate </label>
                    </div>
                    <div class="col-lg-3"></div>
                  </div>
                </div>
              </div>
              <div class="form-group" id="div_hide">
                <label class="col-lg-3 control-label">Select </label>
                <div class="col-lg-8">
                  <div class="form-control append_user scrollbar scroll-" style="height:200px" id="groupbox"> </div>
                  <a id="selectall" class="label bg-success selectall mt5">Select All</a> <a id="deselectall" class="label bg-warning unselectall mt5">Unselect All</a> </div>
              </div>
              <div class="form-group more_to">
                <label class="col-lg-3 control-label"><span class="required">*</span> To</label>
                <div class="col-lg-4">
                  <input type="text" name="to[]" class="form-control validate[required,validate[required]">
                </div>
                <div class="col-lg-4"> <a class="btn btn-success btn-xs btn-circle addmore"><i class="fa fa-plus"></i></a> </div>
              </div>
              <input type="hidden" name="hdn_addcount" id="hdn_addcount" value="">
              <div id="append_to"></div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> From </label>
                <div class="col-lg-4">
                  <input type="text" name="from" value="" class="form-control validate[required,validate[required]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Subject </label>
                <div class="col-lg-8">
                  <input type="text" name="subject" value="" class="form-control validate[required,validate[required]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Message</label>
                <div class="col-lg-9">
                  <textarea id="message" name="message"></textarea>
                </div>
              </div>
              <div class="form-group">
                <div class="more_attach media-body">
                  <label class="col-lg-3 control-label">Attachment</label>
                  <div class="col-lg-4">
                    <input type="file" name="attachment" id="attachment" class="btn btn-sm btn-info m-b-small" />
                  </div>
                </div>
              </div>
              <!--<div class="form-group prograss_bar" style="">
                <label class="col-lg-3 control-label"></label>
                <div class="col-lg-4">
                  <div class="progress progress-small progress-striped active">
                    <div id="progressBar" style="width: 55%;" data-original-title="53%" data-toggle="tooltip" class="progress-bar progress-bar-success"></div>
                  </div>
                </div>
              </div>-->
              <div id="appendattachment"  style="display:none"></div>
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                  <button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Send </button>
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a> </div>
              </div>
            </form>
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
<!--editor--> 
<script src="<?php echo HTTP_SERVER;?>ckeditor3/ckeditor.js"></script>
<style type="text/css">
@media (max-width: 400px) {
  .chunk {
    width: 100% !important;
  }
}
</style>
<script>
	$("#div_hide").hide();
    jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
		CKEDITOR.replace('message');
	
	
    });
	$(document).on("click",".addmore",function(){
			to_more();
	});
	
	$(document).on("click",".removeto",function(){
		var id = $(this).attr("id");
		$(this).parent().closest(".form-group").remove();
	});
	
	function to_more(){
		var total_count = parseInt($(".more_to").size())+1;
		//alert(total_count);	
		//$("#hdn_addcount").val(total_count);
		var html="";	
		html += '<div class="form-group more_to" id="more_to_'+total_count+'">';
        html += '  <label class="col-lg-3 control-label"></label>';
        html += ' 	  <div class="col-lg-4">';
        html += '      	<input type="text" name="to[]" class="form-control">';
        html += '  	  </div>';
        html += ' 	  <div class="col-lg-4">';
        html += '    	<a class="btn btn-warning btn-xs btn-circle removeto" id="'+total_count+'"><i class="fa fa-minus"></i></a>';
        html += '     </div>';
        html += '  	</div>';
       
		$("#append_to").append(html);
	}
	
	$(document).on("click",".moreattach",function(){
			more_attach();
	});
	$(document).on("click",".removeattach",function(){
		var id = $(this).attr("id");
		$(this).parent().closest(".form-group-attach").remove();
	});
	
	 function more_attach(){
		 var total_count = parseInt($(".more_attach").size())+ 1;
		 var html="";
		 
		html +='<div class="form-group more_attach  form-group-attach" id="more_attach_'+total_count+'">';
        html +='  <label class="col-lg-3 control-label"></label>';
        html +='    <div class="col-lg-4">';
        html +='       <input type="file" name="attachment" class="btn btn-sm btn-info m-b-small">';
        html +='    </div>';
        html +='    <div class="col-lg-4">';
        html +='    </div>';
        html +='</div>';
		 html +='        <a class="btn btn-warning btn-xs btn-circle removeattach" id="'+total_count+'"><i class="fa fa-minus"></i></a>';
		$(".append_attach").append(html);
	 }
	 
	$(document).ready(function() {
        $(".user_typ").click(function(){
			$("#div_hide").show();
			var user_type_id = $(this).val();
			var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getUserEmail', '',1);?>");
			$.ajax({
				url:url,
				type:'POST',
				data:{user_type_id:user_type_id},
				success: function(html){
					$(".append_user").html(html);
					$(".ui-page").trigger("create");
				}
			});			
		});
    });
	
$(document).ready(function() {
	$('#selectall').click(function(event) {  
		$('.checkbox1').each(function() { 
			this.checked = true;            
		});
	});
   
	$('#deselectall').click(function(event) {      
		$('.checkbox1').each(function() { 
			this.checked = false;                    
		});        
	});	
});

$('.media-body').on('change','#attachment',function(){
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=imageupload', '',1);?>");
	var total_count = parseInt($(".input-group").size())+ 1;
	$('#loading').show();
	var html = '';
	var file_data = $("#attachment").prop("files")[0];          // Getting the properties of file from file field
	var form_data = new FormData();                            // Creating object of FormData class
	form_data.append("file", file_data)              			// Appending parameter named file with properties of file_field to form_data
	form_data.append("image_id",total_count)
	$.ajax({
		
		/*xhr: function() {
			var xhr = new window.XMLHttpRequest();
			xhr.upload.addEventListener("progress", function(evt) {
				if (evt.lengthComputable) {
					
					var done = evt.position || evt.loaded, total = evt.totalSize || evt.total;
            		var percentComplete = ('xhr.upload progress: ' + done + ' / ' + total + ' = ' + (Math.floor(done/total*1000)/10) + '%');
					
					alert(percentComplete);
					//Do something with upload progress here
				}
		   }, false);
	
		   xhr.addEventListener("progress", function(evt) {
			   if (evt.lengthComputable) {
				   var percentComplete = evt.loaded / evt.total;
				   //Do something with download progress
			   }
		   }, false);
	
		   return xhr;
		},*/
				
		url: url,
		dataType: 'script',
		cache: false,
		contentType: false,
		processData: false,
		data: form_data,                         // Setting the data attribute of ajax with file_data
		type: 'post',
		success : function(response){
			
			if(response!=0){
				
				html +='<div class="form-group">';
				html +='  <label class="col-lg-3 control-label"></label>';
				html +='	<div class="col-lg-9">';
				html +='		<div class="input-group media-body_'+total_count+'">';
				html +='			<a class="btn btn-default btn-block" style="text-align:left;">';
				html +='				'+JSON.parse(response)+'';
				html +='			</a>'; 
                html +='			<span class="input-group-btn">';
				html +='				<a class="btn btn-danger remove_i" onClick="removefile('+total_count+')"><i class="fa fa-times"></i></a>';
				html +=			'</span>';
				html +='		</div>';
				html +='	</div>';
				html +='</div>';
				
			
				$('#appendattachment').show();
				$('#appendattachment').append(html);
				//s$('#display-image-'+count+' img').attr('src',JSON.parse(response));
				$('#loading').remove();
			}else{
				$('#loading').remove();
			}
		}
   });
});
$(document).on("click",".remove_i",function(){
		var id = $(this).attr("id");
		$(this).parent().closest(".form-group").remove();
	});
function removefile(total_count){
	
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=removefile', '',1);?>");
	$('#loading').show();
	
	$.ajax({
		url: url,
		data: {image_id : total_count},       
		type: 'post',
		success : function(){
			$('#loading').remove();
			$('#media-body_'+total_count).remove();
			if($('.file-preview-thumbnails').children().size()==0){
				
			}
		}
	});
}
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>
